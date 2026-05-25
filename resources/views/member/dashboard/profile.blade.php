@extends('layouts.member')
@section('title', 'Profil Saya')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
* { font-family: 'Plus Jakarta Sans', sans-serif; }

.profile-page {
    max-width: 1200px;
    margin: 0;
    padding: 0 32px !important;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.profile-page-title {
    font-size: 22px;
    font-weight: 800;
    color: #111827;
    margin-bottom: 4px;
    letter-spacing: -.3px;
}

/* ── Top Card ── */
.profile-top-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e5e7eb;
    padding: 24px 28px;
    display: flex;
    align-items: center;
    gap: 24px;
}

.avatar-initials,
.avatar-img {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    border: 3px solid #059669;
    flex-shrink: 0;
    object-fit: cover;
}

.avatar-initials {
    background: #059669;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    font-weight: 800;
    color: #fff;
}

.profile-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.profile-info-name { font-size: 18px; font-weight: 800; color: #111827; }
.profile-info-meta { font-size: 13px; color: #6b7280; }

.profile-stats {
    display: flex;
    gap: 32px;
    margin-left: auto;
    flex-shrink: 0;
}

.stat-item { text-align: center; }
.stat-val { font-size: 22px; font-weight: 800; color: #111827; }
.stat-val.green { color: #059669; }
.stat-label {
    font-size: 11px; font-weight: 600; color: #9ca3af;
    text-transform: uppercase; letter-spacing: .05em; margin-top: 2px;
}
.stat-divider { width: 1px; background: #f0f0f0; align-self: stretch; }

/* ── Form Card ── */
.profile-form-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e5e7eb;
    padding: 28px 28px 32px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.card-section-title {
    font-size: 13px; font-weight: 700; color: #6b7280;
    text-transform: uppercase; letter-spacing: .08em;
    padding-bottom: 14px; border-bottom: 1px solid #f3f4f6; margin-bottom: 4px;
}
.card-section-title span {
    font-size: 10px; color: #9ca3af; font-weight: 500;
    text-transform: none; letter-spacing: 0;
}

/* Photo row */
.photo-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 11px 14px; border: 1.5px dashed #d1d5db;
    border-radius: 10px; background: #fafafa;
}
.photo-row-left {
    display: flex; align-items: center; gap: 10px;
    font-size: 13px; color: #6b7280; font-weight: 500;
}
.photo-icon {
    width: 30px; height: 30px; border-radius: 8px; background: #e5e7eb;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.btn-upload-photo {
    background: #059669; color: #fff; border: none; border-radius: 8px;
    padding: 7px 14px; font-size: 12px; font-weight: 700; cursor: pointer;
    transition: background .2s; position: relative; overflow: hidden; white-space: nowrap;
}
.btn-upload-photo:hover { background: #047857; }
.btn-upload-photo input[type="file"] {
    position: absolute; inset: 0; opacity: 0; cursor: pointer; font-size: 0;
}

/* Fields */
.fields-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.field-group { display: flex; flex-direction: column; gap: 5px; }
.field-group label {
    font-size: 11px; font-weight: 700; color: #374151;
    text-transform: uppercase; letter-spacing: .06em;
}
.field-input {
    width: 100%; padding: 10px 13px; border: 1.5px solid #e5e7eb;
    border-radius: 9px; font-size: 14px; font-weight: 500; color: #111827;
    background: #fff; transition: border-color .2s, box-shadow .2s; box-sizing: border-box;
}
.field-input:focus {
    outline: none; border-color: #059669;
    box-shadow: 0 0 0 3px rgba(5,150,105,.1);
}
.field-input:disabled {
    background: #f9fafb; color: #9ca3af; cursor: not-allowed;
}

/* Password wrapper */
.password-wrapper { position: relative; display: flex; align-items: center; }
.password-wrapper .field-input { padding-right: 42px; }

.toggle-password {
    position: absolute; right: 12px;
    background: none; border: none; cursor: pointer;
    color: #6b7280; display: flex; align-items: center;
    justify-content: center; padding: 4px; transition: color 0.2s;
    /* ✅ FIX: pastikan tombol tidak terhalang apapun */
    z-index: 2;
    line-height: 1;
}
.toggle-password:hover { color: #059669; }
.toggle-password svg { width: 20px; height: 20px; display: block; pointer-events: none; }

.section-divider { border: none; border-top: 1px solid #f3f4f6; margin: 4px 0; }

/* Google badge */
.google-login-badge {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 16px; background: #f0f9ff;
    border: 1px solid #bae6fd; border-radius: 10px;
    font-size: 13px; color: #0369a1; font-weight: 500;
}

/* Save button */
.btn-save {
    width: 100%; padding: 13px; background: #059669; color: #fff;
    border: none; border-radius: 11px; font-size: 14px; font-weight: 800;
    cursor: pointer; transition: background .2s, transform .1s;
    letter-spacing: .02em; margin-top: 4px;
}
.btn-save:hover { background: #047857; }
.btn-save:active { transform: scale(.99); }

.field-error { color: #ef4444; font-size: 11px; margin-top: 2px; font-weight: 600; }

/* Back link */
.btn-back {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 13px; font-weight: 600; color: #059669; text-decoration: none; margin-bottom: 4px;
}
.btn-back:hover { color: #047857; }

@media (max-width: 600px) {
    .profile-top-card { flex-wrap: wrap; }
    .profile-stats { margin-left: 0; gap: 20px; }
    .fields-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="profile-page">

    <div>
        <a href="{{ route('member.dashboard') }}" class="btn-back">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M15 18l-6-6 6-6"/>
            </svg>
            Kembali ke Beranda
        </a>
    </div>

    {{-- ── TOP CARD ── --}}
    <div class="profile-top-card">
        @if($user->avatar)
            <img class="avatar-img" src="{{ asset('storage/'.$user->avatar) }}" alt="{{ $user->name }}">
        @else
            <div class="avatar-initials">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        @endif

        <div class="profile-info">
            <div class="profile-info-name">{{ $user->name }}</div>
            <div class="profile-info-meta">Bergabung Sejak: {{ optional($user->created_at)->format('Y/m/d') }}</div>
            @if($user->student_id)
                <div class="profile-info-meta">NIM: {{ $user->student_id }}</div>
            @endif
            {{-- ✅ Badge Google jika login via Google --}}
            @if($user->google_id)
                <div class="profile-info-meta" style="display:inline-flex;align-items:center;gap:5px;margin-top:4px;">
                    <svg width="12" height="12" viewBox="0 0 48 48"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.31-8.16 2.31-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>
                    Akun Google
                </div>
            @endif
        </div>

        <div class="profile-stats">
            <div class="stat-item">
                <div class="stat-val green">{{ $totalBorrowings }}</div>
                <div class="stat-label">Total Pinjaman</div>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <div class="stat-val">{{ $activeBorrowings->count() }}</div>
                <div class="stat-label">Aktif</div>
            </div>
        </div>
    </div>

    {{-- ── FORM CARD ── --}}
    <div class="profile-form-card">
        <form method="POST" action="{{ route('member.profile.update') }}" enctype="multipart/form-data"
              style="display:flex;flex-direction:column;gap:20px;">
            @csrf
            @method('PUT')

            {{-- Informasi Pribadi --}}
            <div class="card-section-title">Informasi Pribadi</div>

            {{-- Upload foto --}}
            <div class="photo-row">
                <div class="photo-row-left">
                    <div class="photo-icon">
                        <svg width="15" height="15" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
                            <circle cx="12" cy="13" r="4"/>
                        </svg>
                    </div>
                    <span id="photo-label" style="color:{{ $user->avatar ? '#059669' : '#6b7280' }}">
                        {{ $user->avatar ? '✓ Foto profil tersimpan' : 'Belum ada foto — pilih file untuk mengunggah' }}
                    </span>
                </div>
                <label class="btn-upload-photo">
                    Unggah Foto Baru
                    <input type="file" name="avatar" accept="image/*" onchange="handlePhotoChange(this)">
                </label>
            </div>
            @error('avatar') <div class="field-error" style="margin-top:-14px;">{{ $message }}</div> @enderror

            <div class="fields-grid">
                <div class="field-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" class="field-input"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="field-group">
                    <label>Alamat Email</label>
                    {{-- ✅ Email tidak bisa diubah jika akun Google --}}
                    <input type="email" name="email" class="field-input"
                           value="{{ old('email', $user->email) }}"
                           {{ $user->google_id ? 'disabled' : 'required' }}>
                    @if($user->google_id)
                        <input type="hidden" name="email" value="{{ $user->email }}">
                        <div style="font-size:11px;color:#6b7280;margin-top:3px;">
                            Email dikelola oleh Google, tidak dapat diubah.
                        </div>
                    @endif
                    @error('email') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="field-group">
                    <label>NIM / ID Siswa</label>
                    <input type="text" name="student_id" class="field-input"
                           value="{{ old('student_id', $user->student_id) }}"
                           placeholder="Contoh: L2023010456">
                    @error('student_id') <div class="field-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <hr class="section-divider">

            {{-- Ganti / Buat Kata Sandi --}}
            @if($user->google_id)
                {{-- Akun Google: bisa set password baru langsung tanpa perlu password lama --}}
                <div class="card-section-title">
                    Buat Kata Sandi
                    <span>(kosongkan jika tidak ingin menambahkan)</span>
                </div>

                <div class="google-login-badge" style="margin-bottom:4px;">
                    <svg width="16" height="16" viewBox="0 0 48 48" style="flex-shrink:0;">
                        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.31-8.16 2.31-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                    </svg>
                    Kamu masuk via Google. Tambahkan kata sandi agar bisa login langsung tanpa Google juga.
                </div>

                <div class="fields-grid">
                    <div class="field-group">
                        <label>Kata Sandi Baru</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password"
                                   class="field-input" placeholder="Min. 8 karakter" autocomplete="new-password">
                            <button type="button" class="toggle-password"
                                    onclick="togglePw('password', this)" tabindex="-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                            </button>
                        </div>
                        @error('password') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field-group">
                        <label>Konfirmasi Kata Sandi Baru</label>
                        <div class="password-wrapper">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="field-input" placeholder="Ulangi kata sandi" autocomplete="new-password">
                            <button type="button" class="toggle-password"
                                    onclick="togglePw('password_confirmation', this)" tabindex="-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                </div>
            @else
                {{-- Akun biasa: tampilkan form ganti password --}}
                <div class="card-section-title">
                    Ganti Kata Sandi
                    <span>(kosongkan jika tidak ingin mengubah)</span>
                </div>

                <div class="fields-grid">
                    <div class="field-group">
                        <label>Kata Sandi Saat Ini</label>
                        <div class="password-wrapper">
                            <input type="password" id="current_password" name="current_password"
                                   class="field-input" placeholder="••••••••" autocomplete="current-password">
                            <button type="button" class="toggle-password"
                                    onclick="togglePw('current_password', this)" tabindex="-1">
                                <svg id="eye-current" xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                            </button>
                        </div>
                        @error('current_password') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field-group">
                        <label>Kata Sandi Baru</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password"
                                   class="field-input" placeholder="Min. 8 karakter" autocomplete="new-password">
                            <button type="button" class="toggle-password"
                                    onclick="togglePw('password', this)" tabindex="-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                            </button>
                        </div>
                        @error('password') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field-group">
                        <label>Konfirmasi Kata Sandi Baru</label>
                        <div class="password-wrapper">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="field-input" placeholder="Ulangi password baru" autocomplete="new-password">
                            <button type="button" class="toggle-password"
                                    onclick="togglePw('password_confirmation', this)" tabindex="-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                </div>
            @endif

            <button type="submit" class="btn-save">Simpan Perubahan</button>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
// ✅ FIX: Pakai data attribute di tombol untuk simpan state show/hide
// Tidak lagi mengandalkan ID icon yang bisa konflik
function togglePw(inputId, btn) {
    const input = document.getElementById(inputId);
    if (!input) return;

    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';

    // Ganti ikon: mata terbuka ↔ mata dicoret
    const svg = btn.querySelector('svg');
    if (!svg) return;

    if (isHidden) {
        // Tampilkan ikon "mata dicoret" (password visible)
        svg.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5
                     c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5
                     c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774
                     M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.822 7.822 3 3m-3-3-3.65-3.65
                     m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/>
        `;
    } else {
        // Tampilkan ikon "mata terbuka" (password hidden)
        svg.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5
                     c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639
                     C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
        `;
    }
}

// ✅ Preview nama file foto yang dipilih
function handlePhotoChange(input) {
    const label = document.getElementById('photo-label');
    if (input.files && input.files[0]) {
        const file = input.files[0];
        // Tampilkan nama file asli pilihan user (bukan path storage)
        label.textContent = '📎 ' + file.name;
        label.style.color = '#374151';
    } else {
        label.textContent = 'Belum ada foto — pilih file untuk mengunggah';
        label.style.color = '#6b7280';
    }
}
</script>
@endpush