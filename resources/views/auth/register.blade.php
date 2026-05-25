<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun – UKM Finic Equipment Rental</title>
    <x-head />
    <style>
        .auth-page {
            background-image: url('{{ asset('images/bg_finic.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .form-control:focus {
            border-color: #16a34a !important;
            box-shadow: 0 0 0 0.2rem rgba(22,163,74,.2) !important;
        }
        /* Wider card for register */
        .auth-card-wide {
            width: 520px;
        }
        /* Password toggle */
        .input-wrap .eye-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--gray-500);
            padding: 0;
            display: flex;
            align-items: center;
        }
        .input-wrap .eye-btn:hover { color: var(--orange); }
        .form-control.with-icon-both { padding-left: 40px; padding-right: 40px; }

        /* Password strength indicator */
        .strength-bar {
            display: flex;
            gap: 4px;
            margin-top: 6px;
        }
        .strength-seg {
            height: 3px;
            flex: 1;
            border-radius: 99px;
            background: var(--gray-200, #e5e7eb);
            transition: background .3s;
        }
        .strength-label {
            font-size: 11px;
            margin-top: 4px;
            color: var(--gray-500);
        }

        /* Terms checkbox */
        .terms-check {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 14px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            margin-bottom: 18px;
        }
        .terms-check input[type="checkbox"] {
            width: 16px; height: 16px;
            margin-top: 2px;
            accent-color: #16a34a;
            flex-shrink: 0;
        }
        .terms-check label {
            font-size: 12px;
            color: var(--gray-700);
            cursor: pointer;
            line-height: 1.5;
        }
        .terms-check a { color: #16a34a; font-weight: 600; }
    </style>
</head>
<body>
<div class="auth-page">
    <div class="auth-card auth-card-wide">

        {{-- Logo --}}
        <div class="auth-logo">
            <span class="brand" style="display:flex;align-items:center;justify-content:center;gap:10px;">
                <img src="{{ asset('images/logo_finic.png') }}" alt="UKM Finic" style="height:52px;width:auto;">
                <span style="font-size:26px;font-weight:700;letter-spacing:2px;color:#000;">FINIC</span>
            </span>
        </div>

        <h2 class="auth-title">Buat Akun</h2>
        <p class="auth-sub">Daftar untuk mengakses portal peminjaman alat UKM Finic.</p>

        {{-- Error Alert --}}
        @if($errors->any())
            <div class="alert alert-error">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf

            {{-- Hidden data untuk menyimpan identitas Google OAuth sementara --}}
            @if(session('google_id'))
                <input type="hidden" name="google_id" value="{{ session('google_id') }}">
                <input type="hidden" name="avatar" value="{{ session('google_avatar') }}">
            @endif

            {{-- Full Name + Student ID --}}
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <div class="input-wrap">
                        <svg class="icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        {{-- Mengisi saran nama dari Google jika ada, tapi user bebas mengubahnya --}}
                        <input type="text" name="name" class="form-control with-icon" placeholder="contoh: Budi Santoso" value="{{ session('google_name') ?? old('name') }}" required>
                    </div>
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">NIM / ID Mahasiswa</label>
                    <div class="input-wrap">
                        <svg class="icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                        <input type="text" name="student_id" class="form-control with-icon" placeholder="contoh: M10423" value="{{ old('student_id') }}" required>
                    </div>
                    @error('student_id')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label class="form-label">Alamat Email UKM</label>
                <div class="input-wrap">
                    <svg class="icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    {{-- Mengisi saran email dari Google jika ada, tapi user tetap bebas mengubahnya --}}
                    <input type="email" name="email" class="form-control with-icon" placeholder="mahasiswa@ukm.edu" value="{{ session('google_email') ?? old('email') }}" required>
                </div>
                <p class="form-hint">Harus menggunakan alamat email valid berbasis <strong>@ukm.edu</strong>, <strong>@ukm.edu.my</strong>, atau <strong>@gmail.com</strong>.</p>
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password + Confirm --}}
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Kata Sandi</label>
                    <div class="input-wrap">
                        <svg class="icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                        {{-- Atribut autocomplete ditambahkan agar browser tidak otomatis mengisi password Google milikmu --}}
                        <input type="password" name="password" id="regPassword" class="form-control with-icon-both" placeholder="Min. 8 karakter" required oninput="checkStrength(this.value)" autocomplete="new-password">
                        <button type="button" class="eye-btn" onclick="togglePassword('regPassword', this)">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                    <div class="strength-bar" id="strengthBar">
                        <div class="strength-seg" id="seg1"></div>
                        <div class="strength-seg" id="seg2"></div>
                        <div class="strength-seg" id="seg3"></div>
                        <div class="strength-seg" id="seg4"></div>
                    </div>
                    <p class="strength-label" id="strengthLabel"></p>
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Kata Sandi</label>
                    <div class="input-wrap">
                        <svg class="icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                        <input type="password" name="password_confirmation" id="regPasswordConf" class="form-control with-icon-both" placeholder="Ulangi kata sandi" required oninput="checkMatch()" autocomplete="new-password">
                        <button type="button" class="eye-btn" onclick="togglePassword('regPasswordConf', this)">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                    <p class="form-hint" id="matchLabel"></p>
                </div>
            </div>

            {{-- Terms --}}
            <div class="terms-check">
                <input type="checkbox" name="agree_terms" id="agreeTerms" required>
                <label for="agreeTerms">
                    Saya menyetujui <a href="#">Ketentuan Layanan</a> dan <a href="#">Kebijakan Privasi</a> dari Sistem Peminjaman Alat UKM Finic.
                </label>
            </div>

            <button type="submit" class="btn btn-primary btn-full" style="font-size:15px;padding:13px;background-color:#16a34a;border-color:#15803d;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                Buat Akun Baru
            </button>
        </form>

        <div class="auth-divider">Sudah punya akun?</div>

        <a href="{{ route('login') }}" class="btn btn-outline btn-full">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
            Masuk ke Akun Terdaftar
        </a>

        <div class="auth-footer-links">
            <a href="#">Tentang Sistem</a>
            <span>▪</span>
            <a href="#">Kebijakan Privasi</a>
            <span>▪</span>
            <span>⊙ Versi 2.4.0</span>
        </div>
    </div>

    <div class="auth-bottom">
        <p style="color:#ccc;font-size:13px;">Sudah terdaftar sebagai anggota?</p>
        <a href="{{ route('login') }}" class="btn btn-outline" style="background:rgba(255,255,255,.1);color:#fff;border-color:rgba(255,255,255,.3);margin-top:8px;">
            Masuk ke Dashboard
        </a>
    </div>
</div>
<div class="copyright-bar">© 2026 UKM Finic Equipment Rental. Hak Cipta Dilindungi.</div>

<script>
// ── Password Toggle ──────────────────────────────────────────
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const isPass = input.type === 'password';
    input.type = isPass ? 'text' : 'password';
    const icons = btn.querySelectorAll('svg');
    icons[0].style.display = isPass ? 'none' : '';
    icons[1].style.display = isPass ? '' : 'none';
}

// ── Password Strength ────────────────────────────────────────
function checkStrength(val) {
    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const colors = ['', '#ef4444', '#f97316', '#eab308', '#16a34a'];
    const labels = ['', 'Lemah', 'Cukup', 'Baik', 'Kuat'];
    const segs = document.querySelectorAll('.strength-seg');
    const lbl  = document.getElementById('strengthLabel');

    segs.forEach((seg, i) => {
        seg.style.background = i < score ? colors[score] : '#e5e7eb';
    });

    if (val.length === 0) {
        lbl.textContent = '';
        lbl.style.color = '';
    } else {
        lbl.textContent = 'Kekuatan: ' + labels[score];
        lbl.style.color = colors[score];
    }
}

// ── Password Match ───────────────────────────────────────────
function checkMatch() {
    const pw  = document.getElementById('regPassword').value;
    const conf = document.getElementById('regPasswordConf').value;
    const lbl = document.getElementById('matchLabel');
    if (conf.length === 0) { lbl.textContent = ''; return; }
    if (pw === conf) {
        lbl.textContent = '✓ Kata sandi cocok';
        lbl.style.color = '#16a34a';
    } else {
        lbl.textContent = '✗ Kata sandi tidak cocok';
        lbl.style.color = '#ef4444';
    }
}

// ── Auto-dismiss alerts ──────────────────────────────────────
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(el => {
        el.style.transition = 'opacity .4s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 400);
    });
}, 5000);
</script>
</body>
</html>