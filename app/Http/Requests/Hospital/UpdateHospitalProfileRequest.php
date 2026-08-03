<?php

namespace App\Http\Requests\Hospital;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHospitalProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('hospital')->check();
    }

    public function rules(): array
    {
        $hospitalId = auth('hospital')->id();

        return [
            'name'            => 'required|string|max:150',
            'email'           => ['required', 'email', Rule::unique('hospitals', 'email')->ignore($hospitalId)],
            'password'        => 'nullable|min:8|confirmed',
            'registration_id' => ['nullable', 'string', 'max:50', Rule::unique('hospitals', 'registration_id')->ignore($hospitalId)],
            'phone'           => 'nullable|string|max:20',
            'city'            => 'nullable|string|max:100',
            'district'        => 'nullable|string|max:100',
            'address'         => 'nullable|string|max:255',
        ];
    }
}
