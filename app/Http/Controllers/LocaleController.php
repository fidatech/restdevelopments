<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switch(string $locale, Request $request)
    {
        $allowed = config('app.available_locales', ['en']);
        abort_unless(in_array($locale, $allowed), 404);

        session(['locale' => $locale]);

        // optional cookie for longer persistence (1 year)
        return back()->withCookie(cookie()->forever('locale', $locale));
    }
}
