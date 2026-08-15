<?php

namespace App\Http\Requests\Hospital;

use Illuminate\Foundation\Http\FormRequest;

class RegisterHospitalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'            => 'required|string|max:150',
            'email'           => 'required|email|unique:hospitals,email',
            'password'        => 'required|min:8|confirmed',
            'registration_id' => 'nullable|string|max:50|unique:hospitals,registration_id',
            'phone'           => 'nullable|string|max:20',
            'city'            => 'nullable|string|max:100',
            'district'        => 'nullable|string|max:100',
            'address'         => 'nullable|string|max:255',
        ];
    }
}
