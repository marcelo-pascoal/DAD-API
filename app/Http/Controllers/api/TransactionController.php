<?php

namespace App\Http\Controllers\api;

use App\Models\Vcard;
use App\Models\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\FullTransactionResource;
use App\Http\Requests\StoreUpdateTransactionRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TransactionController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        $vcard = $user->vcard;
        $query = $vcard->transactions()->orderBy('datetime', 'desc');

        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }
        if ($request->has('pair_vcard')) {
            $query->where('pair_vcard', $request->input('pair_vcard'));
        }

        if ($request->has('min')) {
            $query->where('value', '>=', $request->input('min'));
        }

        if ($request->has('max')) {
            $query->where('value', '<=', $request->input('max'));
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        return TransactionResource::collection($query->paginate(7));
    }

    public function statistics(Request $request)
    {
        $user = Auth::user();
        $vcard = $user->vcard;

        $query = $vcard->transactions()->orderBy('datetime', 'desc');

        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }
        //isto tudo em baixo provavelmente pode ser removido
        if ($request->has('pair_vcard')) {
            $query->where('pair_vcard', $request->input('pair_vcard'));
        }

        if ($request->has('min')) {
            $query->where('value', '>=', $request->input('min'));
        }

        if ($request->has('max')) {
            $query->where('value', '<=', $request->input('max'));
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        return TransactionResource::collection($query->get());
    }

    public function allStatistics()
    {
        $user = Auth::user();

        if ($user && $user->user_type === 'A') {
            $transactions = Transaction::all();
            return TransactionResource::collection($transactions);
        } else {
            abort(403, 'Unauthorized');
        }
    }

    public function store(StoreUpdateTransactionRequest $request)
    {
        DB::beginTransaction();
        try {
            $validData = $request->validated();
            $user = Auth::user();
            $time = Carbon::now();
            $requestTransaction = Transaction::make($validData);

            if ($user && $user->user_type === 'V') {
                $vcard = $user->vcard->lockForUpdate()->firstOrFail();
                if ($vcard->balance < $requestTransaction->value) {
                    return response()->json('Transaction value exceeds vcard balance!', 401);
                } else if ($vcard->max_debit < $requestTransaction->value) {
                    return response()->json('Transaction value exceeds vcard maximum debit value!', 401);
                }
            } else {
                if (!Vcard::find($requestTransaction->vcard)) {
                    return response()->json(['errors' => ['destination_vcard' => ['Vcard does not exist!']]], 422);
                }
                $vcard = Vcard::find($requestTransaction->vcard);
            }
            $requestTransaction->date = $time->toDateString();
            $requestTransaction->datetime = $time->toDateTimeString();
            $requestTransaction->old_balance = $vcard->balance;
            if ($user && $user->user_type === 'V') {
                $requestTransaction->new_balance = $vcard->balance =
                    (string)((float) $vcard->balance - (float) $requestTransaction->value);
            } else {
                $requestTransaction->new_balance = $vcard->balance =
                    (string)((float) $vcard->balance + (float) $requestTransaction->value);
            }

            if ($user && $user->user_type === 'V') {
                switch ($requestTransaction->payment_type) {
                    case 'VCARD':
                        if (!Vcard::where('phone_number', $requestTransaction->payment_reference)->exists()) {
                            return response()->json(['errors' => ['reference' => ['Vcard does not exist!']]], 422);
                        }
                        $pairVcard = Vcard::where('phone_number', $requestTransaction->payment_reference)->lockForUpdate()->firstOrFail();
                        unset($validData['category_id']);
                        unset($validData['description']);
                        unset($validData['type']);
                        $pairTransaction = Transaction::make($validData);
                        $pairTransaction->type = 'C';
                        $pairTransaction->vcard = $pairVcard->phone_number;
                        $pairTransaction->date = $time->toDateString();
                        $pairTransaction->datetime = $time->toDateTimeString();
                        $pairTransaction->old_balance = $pairVcard->balance;
                        $pairTransaction->new_balance = $pairVcard->balance =
                            (string)((float) $pairVcard->balance + (float) $requestTransaction->value);
                        $requestTransaction->pair_vcard = $pairTransaction->vcard;
                        $pairTransaction->pair_vcard = $requestTransaction->vcard;
                        $requestTransaction->save();
                        $pairTransaction->save();
                        $pairTransaction->pair_transaction = $requestTransaction->id;
                        $requestTransaction->pair_transaction = $pairTransaction->id;
                        $requestTransaction->save();
                        $pairTransaction->save();
                        $pairVcard->save();
                        break;
                    default:
                        $debitResponse = Http::post('https://dad-202324-payments-api.vercel.app/api/debit', [
                            'type' =>  $requestTransaction->payment_type,
                            'reference' => $requestTransaction->payment_reference,
                            'value' => $requestTransaction->value
                        ]);
                        if (!$debitResponse->successful()) {
                            DB::rollback();
                            return response($debitResponse, 491);
                        }
                        break;
                }
            } else {
                $creditResponse = Http::post('https://dad-202324-payments-api.vercel.app/api/credit', [
                    'type' =>  $requestTransaction->payment_type,
                    'reference' => $requestTransaction->payment_reference,
                    'value' => $requestTransaction->value
                ]);
                if (!$creditResponse->successful()) {
                    DB::rollback();
                    return response($creditResponse, 491);
                }
            }
            $requestTransaction->save();
            $vcard->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        return new FullTransactionResource($requestTransaction);
    }

    public function show(Transaction $transaction)
    {
        return new FullTransactionResource($transaction);
    }

    public function update(StoreUpdateTransactionRequest $request, Transaction $transaction)
    {
        $dataToSave = $request->validated();

        $transaction->fill($dataToSave);

        $transaction->save();
        return new TransactionResource($transaction);
    }

    public function destroy(Transaction $transaction)
    {
        //
    }
}
