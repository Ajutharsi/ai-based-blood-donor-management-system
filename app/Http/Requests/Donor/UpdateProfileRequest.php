<?php

namespace App\Http\Requests\Donor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('donor')->check();
    }

    public function rules(): array
    {
        $donorId = auth('donor')->id();

        return [
            'first_name'          => 'required|string|max:100',
            'last_name'           => 'required|string|max:100',
            'email'                => ['required', 'email', Rule::unique('donors', 'email')->ignore($donorId)],
            'password'             => 'nullable|min:8|confirmed',
            'phone'                => 'nullable|string|max:20',
            'date_of_birth'        => 'nullable|date|before:today',
            'gender'               => 'nullable|in:Male,Female,Other',
            'nic'                  => ['nullable', 'string', 'max:20', Rule::unique('donors', 'nic')->ignore($donorId)],
            'blood_group'          => 'nullable|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'weight_kg'            => 'nullable|numeric|min:30|max:200',
            'hemoglobin'           => 'nullable|numeric|min:5|max:25',
            'city'                 => 'nullable|string|max:100',
            'district'             => 'nullable|string|max:100',
            'donation_center'      => 'nullable|string|max:150',
            'medical_condition'    => 'nullable|in:Diabetes (controlled),Hypertension (controlled),Asthma,Other',
            'medical_notes'        => 'nullable|string|max:1000',
            'profile_image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }
}
