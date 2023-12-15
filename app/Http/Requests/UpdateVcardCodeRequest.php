<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateVcardCodeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [

            'confirmation_code' => ['required', 'confirmed', Password::min(6), 'regex:/^[0-9]+$/'],
            'current_password' => 'current_password:api',
        ];
    }
}
