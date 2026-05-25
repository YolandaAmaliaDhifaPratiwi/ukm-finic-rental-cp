<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $settings = $this->getCurrentSettings();
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Save all settings.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'max_borrow_days'       => 'required|integer|min:1|max:365',
            'max_items_per_member'  => 'required|integer|min:1|max:20',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Gagal menyimpan. Pastikan semua input angka sudah benar.');
        }

        $currentSettings = $this->getCurrentSettings();

        $newSettings = array_merge($currentSettings, [
            'notif_email'           => $request->boolean('notif_email'),
            'notif_overdue'         => $request->boolean('notif_overdue'),
            'notif_approval'        => $request->boolean('notif_approval'),
            'max_borrow_days'       => (int) $request->max_borrow_days,
            'max_items_per_member'  => (int) $request->max_items_per_member,
        ]);

        Session::put('admin_settings', $newSettings);

        return back()->with('success', 'Pengaturan berhasil diperbarui!');
    }

    /**
     * Change language only.
     */
    public function setLanguage(Request $request, string $lang)
    {
        if (! in_array($lang, ['en', 'id'])) {
            abort(404);
        }

        Session::put('locale', $lang);

        $settings = Session::get('admin_settings', []);
        $settings['language'] = $lang;
        Session::put('admin_settings', $settings);

        return back()->with('success', $lang === 'id'
            ? 'Bahasa berhasil diubah ke Bahasa Indonesia.'
            : 'Language changed to English successfully.');
    }

    /**
     * Reset all settings to defaults.
     */
    public function reset()
    {
        Session::forget('admin_settings');
        Session::forget('locale');

        return redirect()->route('admin.settings')
            ->with('success', 'Pengaturan telah berhasil dikembalikan ke default.');
    }

    /**
     * Get current settings, merging session with defaults.
     */
    private function getCurrentSettings(): array
    {
        $defaults = [
            'language'              => 'id',
            'timezone'              => 'Asia/Jakarta',
            'date_format'           => 'D, d M Y',
            'theme'                 => 'light',
            'sidebar_position'      => 'left',
            'notif_email'           => true,
            'notif_overdue'         => true,
            'notif_approval'        => true,
            'max_borrow_days'       => 7,
            'max_items_per_member'  => 3,
        ];

        return array_merge($defaults, Session::get('admin_settings', []));
    }
}