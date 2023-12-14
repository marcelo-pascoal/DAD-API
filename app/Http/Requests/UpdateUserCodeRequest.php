<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Models\Vcard;
use Illuminate\Support\Facades\Hash;

class UpdateUserCodeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'password' => ['required', 'confirmed', Password::min(6), 'regex:/^[0-9]+$/'],
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    $user = auth()->user();
                    $vcard = Vcard::where('phone_number', $user->id)->first();

                    if (!$vcard || !Hash::check($value, $vcard->confirmation_code)) {
                        $fail('The current confirmation code is incorrect.');
                    }
                },
            ]
        ];
    }
}
