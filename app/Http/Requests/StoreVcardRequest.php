<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use App\Http\Requests\UpdateVcardRequest;

class StoreVcardRequest extends UpdateVcardRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = parent::rules();
        unset($rules['deletePhotoOnServer']);
        return array_merge($rules, [
            'password' => ['required', 'confirmed', Password::min(3)],
            'confirmation_code' => ['required', 'confirmed', Password::min(6)],
        ]);
    }
}
