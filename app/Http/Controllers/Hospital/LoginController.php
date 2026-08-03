<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

        if (auth('hospital')->attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            return redirect()->route('hospital.dashboard')
                ->with('success', 'Welcome, ' . auth('hospital')->user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'Invalid hospital credentials.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        auth('hospital')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('hospital.login');
    }
}