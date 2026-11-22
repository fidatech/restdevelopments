<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class SetLocale
{
    public function handle($request, \Closure $next)
    {
        $locale = $request->route('locale')
            ?: session('locale')
            ?: config('app.fallback_locale', 'en');

        app()->setLocale($locale);

        // Auto-fill {locale} when calling route()
        URL::defaults(['locale' => $locale]);

        return $next($request);
    }
}
