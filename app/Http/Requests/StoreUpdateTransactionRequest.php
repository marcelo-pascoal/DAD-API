<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateTransactionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type' => 'required',
            'value' => 'required',
            'payment_type' => 'required|in:VCARD,MBWAY,PAYPAL,IBAN,MB,VISA',
            'payment_reference' => 'required',
            'description' => 'nullable|string',
            'category_id' => [
                'nullable',
                function ($category, $value, $fail) {
                    $user = auth()->user();
                    $exists = DB::table('categories')
                        ->where('id', $value)
                        ->where('vcard', $user->id)
                        ->exists();

                    if (!$exists) {
                        $fail("The selected $category is invalid.");
                    }
                },
            ],
        ];
    }
}
