<?php

namespace App\Http\Controllers;

use App\Http\Middleware\SetLocale;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    // Stores the chosen language in the session (so it applies immediately,
    // including for guests) and, if the visitor is logged into any guard,
    // persists it to that account's own record so the preference survives
    // future logins from any device.
    public function switch(Request $request, string $locale)
    {
        if (!in_array($locale, SetLocale::SUPPORTED, true)) {
            abort(404);
        }

        $request->session()->put('locale', $locale);

        foreach (['donor', 'hospital', 'admin'] as $guard) {
            if (auth($guard)->check()) {
                auth($guard)->user()->update(['locale' => $locale]);
                break;
            }
        }

        return back();
    }
}
