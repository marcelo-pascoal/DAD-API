<?php

namespace App\Http\Requests;

use App\Models\Vcard;
use Illuminate\Support\Facades\Hash;

use Illuminate\Foundation\Http\FormRequest;

class DeleteVcardRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [

            'confirmation_code' => [
                'required',
                function ($attribute, $value, $fail) {
                    $user = auth()->user();
                    $vcard = Vcard::where('phone_number', $user->id)->first();

                    if (!$vcard || !Hash::check($value, $vcard->confirmation_code)) {
                        $fail('The current confirmation code is incorrect.');
                    }
                },
            ],
            'password' => 'required|current_password:api',
        ];
    }
}
