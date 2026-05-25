<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // ================================================================
    // SHOW PAGES
    // ================================================================

    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    // ================================================================
    // LOGIN — satu form, redirect otomatis berdasarkan role
    // ================================================================

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return $this->redirectByRole(Auth::user());
        }

        return back()
            ->withErrors(['email' => 'Email atau kata sandi tidak sesuai.'])
            ->withInput();
    }

    // ================================================================
    // GOOGLE OAUTH
    // ================================================================

    /**
     * Redirect ke halaman login Google.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Callback dari Google setelah user memberi izin.
     * Cari user berdasarkan email, atau buat akun baru (role: member).
     */

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Login Google gagal. Silakan coba lagi.']);
        }

        // PERBAIKAN: Tambahkan ->first() di kueri pertama agar mengembalikan objek User, bukan Builder instance
        $user = User::where('google_id', $googleUser->getId())->first();

        // Jika tidak ketemu berdasarkan google_id, cari berdasarkan email
        if (!$user) {
            $user = User::where('email', $googleUser->getEmail())->first();
        }

        if ($user) {
            // ---- USER SUDAH PUNYA AKUN ----
            // Jika akun terdaftar manual dan baru pertama kali klik tombol Google, hubungkan google_id-nya
            if (!$user->google_id) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                ]);
            }

            Auth::login($user, true);
            return $this->redirectByRole($user);

        } else {
            // ---- USER BARU (BELUM PUNYA AKUN) ----
            // Data dari Google hanya disimpan sementara di session flash sebagai saran isian form
            return redirect()->route('register')->with([
                'google_name'   => $googleUser->getName(),
                'google_email'  => $googleUser->getEmail(),
                'google_id'     => $googleUser->getId(),
                'google_avatar' => $googleUser->getAvatar(),
            ]);
        }
    }

    // public function handleGoogleCallback()
    // {
    //     try {
    //         $googleUser = Socialite::driver('google')->user();
    //     } catch (\Exception $e) {
    //         return redirect()->route('login')
    //             ->withErrors(['email' => 'Login Google gagal. Silakan coba lagi.']);
    //     }

    //     // Cari user berdasarkan google_id dulu, lalu fallback ke email
    //     $user = User::where('google_id', $googleUser->getId())->first()
    //          ?? User::where('email', $googleUser->getEmail())->first();

    //     if ($user) {
    //         // Update google_id jika belum tersimpan (akun lama yang baru pakai Google)
    //         if (! $user->google_id) {
    //             $user->update(['google_id' => $googleUser->getId()]);
    //         }
    //     } else {
    //         // Buat akun baru — role default: member
    //         $user = User::create([
    //             'name'      => $googleUser->getName(),
    //             'email'     => $googleUser->getEmail(),
    //             'google_id' => $googleUser->getId(),
    //             'avatar'    => $googleUser->getAvatar(),
    //             'password'  => Hash::make(Str::random(24)), // password acak, tidak dipakai
    //             'role'      => 'member',
    //         ]);
    //     }

    //     Auth::login($user, true); // remember: true

    //     return $this->redirectByRole($user);
    // }

    // ================================================================
    // REGISTER
    // ================================================================

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users|ends_with:ukm.edu,ukm.edu.my,gmail.com',
            'student_id' => 'required|string|max:20|unique:users,student_id',
            'password'   => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $user = User::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'student_id' => $data['student_id'],
            'password'   => Hash::make($data['password']),
            'role'       => 'member',
        ]);

        Auth::login($user);

        return redirect()->route('member.dashboard')
            ->with('success', 'Akun berhasil dibuat! Selamat datang di UKM Finic, ' . $user->name . '!');
    }

    // ================================================================
    // FORGOT PASSWORD – Send Reset Link
    // ================================================================

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('reset_status', 'Tautan atur ulang kata sandi telah dikirim ke email Anda.');
        }

        return back()->with('reset_status', 'Jika email terdaftar, tautan telah dikirim.');
    }

    // ================================================================
    // FORGOT PASSWORD – Show Reset Form
    // ================================================================

    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    // ================================================================
    // FORGOT PASSWORD – Reset Password
    // ================================================================

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')
                ->with('success', 'Password berhasil diatur ulang! Silakan masuk dengan password baru.');
        }

        return back()
            ->withErrors(['email' => __($status)])
            ->withInput();
    }

    // ================================================================
    // LOGOUT
    // ================================================================

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // ================================================================
    // PRIVATE HELPER
    // ================================================================

    private function redirectByRole(User $user)
    {
        return match ($user->role) {
            'admin'  => redirect()->route('admin.dashboard'),
            'member' => redirect()->route('member.dashboard'),
            default  => redirect('/'),
        };
    }
}