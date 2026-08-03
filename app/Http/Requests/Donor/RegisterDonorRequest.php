<?php

namespace App\Http\Requests\Donor;

use Illuminate\Foundation\Http\FormRequest;

class RegisterDonorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name'          => 'required|string|max:100',
            'last_name'           => 'required|string|max:100',
            'email'                => 'required|email|unique:donors,email',
            'password'             => 'required|min:8|confirmed',
            'phone'                => 'nullable|string|max:20',
            'date_of_birth'        => 'nullable|date|before:today',
            'gender'               => 'nullable|in:Male,Female,Other',
            'nic'                  => 'nullable|string|max:20|unique:donors,nic',
            'blood_group'          => 'nullable|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'weight_kg'            => 'nullable|numeric|min:30|max:200',
            'hemoglobin'           => 'nullable|numeric|min:5|max:25',
            'total_donations'      => 'nullable|integer|min:0|max:500',
            'last_donation_date'   => 'nullable|date|before_or_equal:today',
            'city'                 => 'nullable|string|max:100',
            'district'             => 'nullable|string|max:100',
            'donation_center'      => 'nullable|string|max:150',
            'medical_condition'    => 'nullable|in:Diabetes (controlled),Hypertension (controlled),Asthma,Other',
            'medical_notes'        => 'nullable|string|max:1000',
            'profile_image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }
}
