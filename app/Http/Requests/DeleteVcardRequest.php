<?php

namespace App\Http\Requests;

use App\Models\Vcard;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
                function ($attribute, $value, $fail) {
                    $user = auth()->user();

                    // Check if user_type is 'V' before validating confirmation code
                    if ($user->user_type == 'V') {
                        $vcard = Vcard::where('phone_number', $user->id)->first();

                        if (!$vcard || !Hash::check($value, $vcard->confirmation_code)) {
                            $fail('The current confirmation code is incorrect.');
                        }
                    }
                },
            ],
            'password' => [
                function ($attribute, $value, $fail) {
                    $user = auth()->user();
                    if ($user->user_type == 'V') {
                        $validator = Validator::make(['password' => $value], [
                            'password' => 'required|current_password:api',
                        ]);
                        if ($validator->fails()) {
                            $fail($validator->errors()->first('password'));
                        }
                    }
                },
            ],
        ];
    }
}
