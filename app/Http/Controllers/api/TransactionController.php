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

    public function index()
    {
        $user = Auth::user();
        $vcard = $user->vcard;
        return TransactionResource::collection($vcard->transactions()->orderBy('datetime', 'desc')->get());
    }

    public function store(StoreUpdateTransactionRequest $request)
    {
        DB::beginTransaction();
        try {
            $validData = $request->validated();
            $user = Auth::user();
            $vcard = $user->vcard->lockForUpdate()->firstOrFail();
            $time = Carbon::now();
            $requestTransaction = Transaction::make($validData);

            if ($user && $user->user_type === 'V') {
                $requestTransaction->vcard = $vcard->phone_number;
                $requestTransaction->date = $time->toDateString();
                $requestTransaction->datetime = $time->toDateTimeString();
                $requestTransaction->old_balance = $vcard->balance;
                $requestTransaction->new_balance = $vcard->balance =
                    (string)((float) $vcard->balance - (float) $requestTransaction->value);

                switch ($requestTransaction->payment_type) {
                    case 'VCARD':
                        $pairVcard = Vcard::where('phone_number', $requestTransaction->payment_reference)->lockForUpdate()->firstOrFail();
                        unset($validData['category_id']);
                        unset($validData['description']);
                        $pairTransaction = Transaction::make($validData);
                        $pairTransaction->vcard = $pairVcard->phone_number;
                        $pairTransaction->date = $time->toDateString();
                        $pairTransaction->datetime = $time->toDateTimeString();
                        $pairTransaction->old_balance = $pairVcard->balance;
                        $pairTransaction->new_balance = $pairVcard->balance =
                            (string)((float) $pairVcard->balance + (float) $requestTransaction->value);
                        $pairTransaction->pair_vcard = $requestTransaction->vcard;
                        $pairTransaction->save();
                        $requestTransaction->pair_transaction = $pairTransaction->id;
                        $requestTransaction->pair_vcard = $pairTransaction->vcard;
                        $requestTransaction->save();
                        $pairTransaction->pair_transaction = $requestTransaction->id;
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
                            return $debitResponse;
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
                    return $creditResponse;
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

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        return new FullTransactionResource($transaction);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
