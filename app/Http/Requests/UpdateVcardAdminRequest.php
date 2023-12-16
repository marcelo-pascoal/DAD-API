<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVcardAdminRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'phone_number' => ['required', 'regex:/^9\d{8}$/'],
            'blocked' => 'required',
            'max_debit' => 'required',
        ];
    }
}
