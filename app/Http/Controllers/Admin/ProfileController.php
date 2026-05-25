<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('admin.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $isAvatarOnly = $request->hasFile('avatar')
            && ! $request->filled('name')
            && ! $request->filled('email');

        if ($isAvatarOnly) {
            $request->validate([
                'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            ], [
                'avatar.required' => 'Silakan pilih foto terlebih dahulu.',
                'avatar.image'    => 'File yang diunggah harus berupa gambar.',
                'avatar.mimes'    => 'Format foto harus JPG, JPEG, atau PNG.',
                'avatar.max'      => 'Ukuran foto tidak boleh lebih dari 2 MB.',
            ]);

            $this->handleAvatarUpload($request, $user);
            $user->save();

            return redirect()->route('admin.profile')
                ->with('success', 'Foto profil berhasil diperbarui!');
        }

        $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'email'  => ['required', 'email', 'unique:users,email,' . $user->id],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'name.required'  => 'Nama lengkap wajib diisi.',
            'name.max'       => 'Nama lengkap tidak boleh lebih dari 255 karakter.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email'    => 'Format alamat email tidak valid.',
            'email.unique'   => 'Alamat email sudah digunakan oleh akun lain.',
            'avatar.image'   => 'File yang diunggah harus berupa gambar.',
            'avatar.mimes'   => 'Format foto harus JPG, JPEG, atau PNG.',
            'avatar.max'     => 'Ukuran foto tidak boleh lebih dari 2 MB.',
        ]);

        if ($request->hasFile('avatar')) {
            $this->handleAvatarUpload($request, $user);
        }

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('admin.profile')
            ->with('success', 'Profil Anda berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'password.required'         => 'Kata sandi baru wajib diisi.',
            'password.confirmed'        => 'Konfirmasi kata sandi tidak cocok.',
            'password.min'              => 'Kata sandi baru minimal 8 karakter.',
        ]);

        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Kata sandi saat ini yang Anda masukkan salah.'])
                ->withInput();
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.profile')
            ->with('success', 'Kata sandi Anda berhasil diubah!');
    }

    private function handleAvatarUpload(Request $request, $user): void
    {
        $file        = $request->file('avatar');
        $filename    = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
        $destination = public_path('storage/profile_photos');

        // Pastikan folder ada
        if (! file_exists($destination)) {
            mkdir($destination, 0755, true);
        }

        // Hapus foto lama jika ada
        if ($user->avatar) {
            $oldFile = $destination . DIRECTORY_SEPARATOR . $user->avatar;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        // Simpan langsung ke public/storage/profile_photos — bypass symlink
        $file->move($destination, $filename);

        $user->avatar = $filename;
    }
}