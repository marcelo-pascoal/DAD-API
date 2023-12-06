<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends UpdateUserRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = parent::rules();
        return array_merge($rules, [
            'password' => ['required', 'confirmed', Password::min(3)],
        ]);
    }
}
