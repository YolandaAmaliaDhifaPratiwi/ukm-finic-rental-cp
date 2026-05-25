<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Kirim pesan ke admin dari user yang sedang login.
     * Email dikirim dari server — sender name = nama user, reply-to = email user.
     */
    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:100',
            'message' => 'required|string|max:2000',
        ]);

        $user = Auth::user();

        // Kirim email ke admin
        Mail::send([], [], function ($mail) use ($user, $request) {
            $mail->to('finicums26@gmail.com', 'Admin UKM Finic')
                 ->replyTo($user->email, $user->name)   // ← balasan masuk ke email akun UKM user
                 ->subject('[Pesan Member] ' . $request->subject)
                 ->html(
                     '<div style="font-family:sans-serif;max-width:600px;margin:0 auto;">'
                   . '<div style="background:#059669;padding:20px 24px;border-radius:8px 8px 0 0;">'
                   . '<h2 style="color:#fff;margin:0;font-size:18px;">Pesan dari Member UKM Finic</h2>'
                   . '</div>'
                   . '<div style="background:#f9fafb;padding:24px;border:1px solid #e5e7eb;border-top:none;border-radius:0 0 8px 8px;">'
                   . '<table style="width:100%;font-size:14px;border-collapse:collapse;">'
                   . '<tr><td style="padding:6px 0;color:#6b7280;width:120px;">Nama</td>'
                   .     '<td style="padding:6px 0;font-weight:600;color:#111827;">' . e($user->name) . '</td></tr>'
                   . '<tr><td style="padding:6px 0;color:#6b7280;">Email</td>'
                   .     '<td style="padding:6px 0;font-weight:600;color:#059669;">' . e($user->email) . '</td></tr>'
                   . '<tr><td style="padding:6px 0;color:#6b7280;">NIM</td>'
                   .     '<td style="padding:6px 0;color:#374151;">' . e($user->student_id ?? '-') . '</td></tr>'
                   . '<tr><td style="padding:6px 0;color:#6b7280;">Subjek</td>'
                   .     '<td style="padding:6px 0;color:#374151;">' . e($request->subject) . '</td></tr>'
                   . '</table>'
                   . '<hr style="border:none;border-top:1px solid #e5e7eb;margin:16px 0;">'
                   . '<p style="font-size:13px;color:#6b7280;margin:0 0 8px;">Pesan:</p>'
                   . '<p style="font-size:14px;color:#111827;line-height:1.7;white-space:pre-wrap;background:#fff;padding:14px;border-radius:8px;border:1px solid #e5e7eb;">'
                   . e($request->message)
                   . '</p>'
                   . '<p style="font-size:12px;color:#9ca3af;margin-top:20px;">Untuk membalas, klik Reply — balasan akan masuk ke ' . e($user->email) . '</p>'
                   . '</div></div>'
                 );
        });

        return back()->with('contact_success', 'Pesan berhasil dikirim! Admin akan membalas ke ' . $user->email);
    }
}
