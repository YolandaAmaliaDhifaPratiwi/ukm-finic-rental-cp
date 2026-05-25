<?php
namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Equipment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $totalBorrowings  = $user->borrowings()->count();
        $activeBorrowings = $user->borrowings()->where('status', 'approved')->get();
        $pendingRequests  = $user->borrowings()->where('status', 'pending')->count();
        $recommended = Equipment::where('status', 'available')
            ->whereNotIn('id', $activeBorrowings->pluck('equipment_id'))
            ->inRandomOrder()->limit(2)->get();
        $rank = \App\Models\User::where('role', 'member')
            ->where('reliability_score', '>', $user->reliability_score)
            ->count() + 1;

        $notifications = $user->notifications()->latest()->limit(5)->get();
        $unreadCount   = $user->unreadNotifications()->count();

        return view('member.dashboard.index', compact(
            'user', 'totalBorrowings', 'activeBorrowings',
            'pendingRequests', 'recommended', 'rank',
            'notifications', 'unreadCount'
        ));
    }

    public function activeGear()
    {
        $user = Auth::user();
        $activeBorrowings = $user->borrowings()
            ->where('status', 'approved')
            ->with('equipment')
            ->get();
        return view('member.dashboard.active_gear', compact('activeBorrowings'));
    }

    public function notifications()
    {
        $user = Auth::user();
        $borrowings = $user->borrowings()->with('equipment')->latest()->get();
        auth()->user()->unreadNotifications->markAsRead();
        return view('member.dashboard.notifications', compact('borrowings', 'user'));
    }

    public function gearGuides()
    {
        return view('member.dashboard.gear_guides');
    }

    public function profile()
    {
        $user = Auth::user();
        $totalBorrowings  = $user->borrowings()->count();
        $activeBorrowings = $user->borrowings()->where('status', 'approved')->get();
        $rank = \App\Models\User::where('role', 'member')
            ->where('reliability_score', '>', $user->reliability_score)
            ->count() + 1;
        return view('member.dashboard.profile', compact('user', 'totalBorrowings', 'activeBorrowings', 'rank'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $isGoogleUser = (bool) $user->google_id;

        // ── Validasi dasar ──────────────────────────────────────
        $rules = [
            'name'       => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'student_id' => 'nullable|string|max:20',
            'avatar'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        // ── Cek apakah user mengisi field password ──────────────
        $filledPassword     = $request->filled('password');
        $filledConfirmation = $request->filled('password_confirmation');
        $filledCurrent      = $request->filled('current_password');

        if ($isGoogleUser) {
            // ── AKUN GOOGLE: set password baru langsung (tanpa cek password lama) ──
            if ($filledPassword || $filledConfirmation) {
                $rules['password'] = 'required|string|min:8|confirmed';
            }

            $request->validate($rules);

            if ($filledPassword) {
                $user->password  = Hash::make($request->password);
                // Opsional: lepas ikatan Google setelah set password sendiri
                // $user->google_id = null;
            }

        } else {
            // ── AKUN BIASA: wajib verifikasi password lama dulu ──
            $changingPassword = $filledCurrent || $filledPassword || $filledConfirmation;

            if ($changingPassword) {
                $rules['current_password'] = 'required|string';
                $rules['password']         = 'required|string|min:8|confirmed';
            }

            $request->validate($rules);

            if ($changingPassword) {
                // Verifikasi password lama
                if (!Hash::check($request->current_password, $user->password)) {
                    return back()
                        ->withErrors(['current_password' => 'Kata sandi saat ini tidak sesuai.'])
                        ->withInput();
                }
                $user->password = Hash::make($request->password);
            }
        }

        // ── Update data profil ──────────────────────────────────
        $user->name       = $request->name;
        $user->student_id = $request->student_id;

        // Email tidak bisa diubah untuk akun Google
        if (!$isGoogleUser) {
            $user->email = $request->email;
        }

        // // ── Upload avatar ───────────────────────────────────────
        // if ($request->hasFile('avatar')) {
        //     if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
        //         Storage::disk('public')->delete($user->avatar);
        //     }
        //     $user->avatar = $request->file('avatar')->store('profile_photos', 'public');
        // }

        // $user->save();

                // ── Upload avatar ───────────────────────────────────────
        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            // Ganti 'profile_photos' menjadi 'avatars'
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->save();

        return redirect()->route('member.profile')->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        return $this->updateProfile($request);
    }
}