<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'type' => 'required',
            'value' => 'required',
            'payment_type' => 'required|in:VCARD,MBWAY,PAYPAL,IBAN,MB,VISA',
            'payment_reference' => 'required',
            'category_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    $user = auth()->user();
                    // Check if the category_id exists in the categories table
                    $exists = DB::table('categories')
                        ->where('id', $value)
                        ->where('vcard', $user->id)
                        ->exists();

                    if (!$exists) {
                        $fail("The selected $attribute is invalid.");
                    }
                },
            ],
            'description' => 'nullable|string',
        ];
    }
}
