<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAdminProfileRequest;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $admin = auth('admin')->user();

        return view('admin.profile_edit', compact('admin'));
    }

    public function update(UpdateAdminProfileRequest $request)
    {
        $admin = auth('admin')->user();

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('admin.profile.edit')
                         ->with('success', 'Your profile has been updated.');
    }
}
