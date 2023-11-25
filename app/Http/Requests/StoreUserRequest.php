<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends UpdateUserRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // StoreUserRequest apply all the rules from the parent, but:
        // adds a new rule for the password field (password and password_confirm
        // removes the rules related to the followinf fields:
        //   "type" (it will be always type "M")
        //   "deletePhotoOnServer" - there is not photo on the server to delete
        $rules = parent::rules();
        unset($rules['type']);
        unset($rules['deletePhotoOnServer']);
        return array_merge($rules, [
            'password' => ['required', 'confirmed', Password::min(3)],
        ]);
    }
}
