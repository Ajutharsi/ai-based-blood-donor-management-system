<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('common.login_page', ['role' => 'admin']);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (auth('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $admin = auth('admin')->user();

            ActivityLog::record('admin', $admin->id, $admin->name, 'auth', 'login', __(':name logged in.', ['name' => $admin->name]));

            return redirect()->route('admin.dashboard')
                             ->with('success', 'Welcome back, ' . $admin->name . '!');
        }

        Log::warning('Failed admin login attempt', ['email' => $request->input('email'), 'ip' => $request->ip()]);

        return back()->withErrors([
            'email' => 'Invalid admin credentials.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        $admin = auth('admin')->user();
        if ($admin) {
            ActivityLog::record('admin', $admin->id, $admin->name, 'auth', 'logout', __(':name logged out.', ['name' => $admin->name]));
        }

        auth('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}