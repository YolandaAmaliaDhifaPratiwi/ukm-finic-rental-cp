<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     * Reads the locale from the session and applies it globally.
     */
    public function handle(Request $request, Closure $next)
    {
        // Priority: session locale → default 'en'
        $locale = Session::get('locale', config('app.locale', 'en'));

        // Ensure only valid locales are applied
        if (in_array($locale, ['en', 'id'])) {
            App::setLocale($locale);
        } else {
            App::setLocale('en');
        }

        return $next($request);
    }
}
