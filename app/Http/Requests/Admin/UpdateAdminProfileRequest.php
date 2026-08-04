<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        $adminId = auth('admin')->id();

        return [
            'name'     => 'required|string|max:150',
            'email'    => ['required', 'email', Rule::unique('admins', 'email')->ignore($adminId)],
            'password' => 'nullable|min:8|confirmed',
        ];
    }
}
