<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('common.login_page', ['role' => 'hospital']);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        if (auth('hospital')->attempt($request->only('email', 'password'), $remember)) {
            $request->session()->regenerate();
            $hospital = auth('hospital')->user();

            ActivityLog::record('hospital', $hospital->id, $hospital->name, 'auth', 'login', __(':name logged in.', ['name' => $hospital->name]));

            return redirect()->route('hospital.dashboard')
                ->with('success', 'Welcome, ' . $hospital->name . '!');
        }

        Log::warning('Failed hospital login attempt', ['email' => $request->input('email'), 'ip' => $request->ip()]);

        return back()->withErrors([
            'email' => 'Invalid hospital credentials.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        $hospital = auth('hospital')->user();
        if ($hospital) {
            ActivityLog::record('hospital', $hospital->id, $hospital->name, 'auth', 'logout', __(':name logged out.', ['name' => $hospital->name]));
        }

        auth('hospital')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('hospital.login');
    }
}