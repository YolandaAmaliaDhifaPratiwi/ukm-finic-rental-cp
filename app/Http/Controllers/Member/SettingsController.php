<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SettingsController extends Controller
{
    /**
     * Switch the app language (en / id).
     * Stores the choice in the session so SetLocale middleware picks it up.
     */
    public function setLanguage(string $lang)
    {
        $allowed = ['en', 'id'];

        if (in_array($lang, $allowed)) {
            Session::put('locale', $lang);
            App::setLocale($lang);
        }

        return redirect()->back()->withHeaders([
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }

    /**
     * Save dark / light theme preference in the session.
     * The frontend reads this and toggles the class on <html>.
     */
    public function setTheme(Request $request)
    {
        $theme = $request->input('theme', 'light');
        Session::put('theme', in_array($theme, ['light', 'dark']) ? $theme : 'light');

        return response()->json(['theme' => Session::get('theme')]);
    }
}
