<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk – UKM Finic Equipment Rental</title>
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

        /* Forgot Password Modal */
        .forgot-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.65);
            z-index: 500;
            align-items: center;
            justify-content: center;
        }
        .forgot-modal-overlay.open { display: flex; }
        .forgot-modal {
            background: #fff;
            border-radius: 16px;
            padding: 36px 40px;
            width: 420px;
            max-width: 95vw;
            box-shadow: 0 24px 64px rgba(0,0,0,.4);
            position: relative;
            animation: slideUp .25s ease;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .forgot-modal-close {
            position: absolute;
            top: 16px; right: 16px;
            background: none; border: none;
            font-size: 22px; cursor: pointer;
            color: var(--gray-500); line-height: 1;
        }
        .forgot-modal-close:hover { color: #EF4444; }
        .forgot-modal-icon {
            width: 52px; height: 52px;
            background: #eff6ff;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
            color: #3b82f6;
        }
        .success-state { display: none; text-align: center; }
        .success-state.show { display: block; }
        .form-state.hide { display: none; }

        /* Google OAuth Button */
        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 12px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            background: #fff;
            color: #374151;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: background .2s, border-color .2s, box-shadow .2s;
            cursor: pointer;
        }
        .btn-google:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            box-shadow: 0 2px 8px rgba(0,0,0,.08);
            color: #111;
        }
    </style>
</head>
<body>
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-logo">
            <span class="brand" style="display:flex;align-items:center;justify-content:center;gap:10px;">
                <img src="{{ asset('images/logo_finic.png') }}" alt="UKM Finic" style="height:52px;width:auto;">
                <span style="font-size:26px;font-weight:700;letter-spacing:2px;color:#000;">FINIC</span>
            </span>
        </div>

        <h2 class="auth-title">Selamat Datang Kembali</h2>
        <p class="auth-sub">Silakan masukkan kredensial Anda untuk mengakses portal peminjaman alat.</p>

        @if($errors->any())
            <div class="alert alert-error">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- ✅ SATU form untuk semua user — backend yang deteksi role --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Alamat Email</label>
                <div class="input-wrap">
                    <svg class="icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <input type="email" name="email" class="form-control with-icon" placeholder="m.mahasiswa@ukm.edu" value="{{ old('email') }}" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Kata Sandi</label>
                <div class="input-wrap">
                    <svg class="icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    <input type="password" name="password" id="loginPassword" class="form-control with-icon-both" placeholder="••••••••" required>
                    <button type="button" class="eye-btn" onclick="togglePassword('loginPassword', this)" title="Tampilkan/Sembunyikan Kata Sandi">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>
                <div style="text-align:right;margin-top:6px;">
                    <a href="#" onclick="openForgotModal(event)" class="text-green" style="font-size:13px;font-weight:500;color:#16a34a;text-decoration:none;">Lupa kata sandi?</a>
                </div>
            </div>

            <div class="form-group flex-center gap-8" style="margin-bottom:20px;">
                <input type="checkbox" name="remember" id="remember" style="width:16px;height:16px;accent-color:#16a34a;">
                <label for="remember" style="font-size:13px;cursor:pointer;">Ingat perangkat ini</label>
            </div>

            <button type="submit" class="btn btn-primary btn-full" style="font-size:15px;padding:13px;background-color:#16a34a;border-color:#15803d;">
                Masuk ke Dashboard →
            </button>
        </form>

        {{-- ✅ Divider & tombol admin DIHAPUS. Role dideteksi otomatis di backend. --}}

        <div class="auth-divider" style="margin:20px 0 16px;">atau</div>

        {{-- ✅ Tombol Google OAuth --}}
        <a href="{{ route('auth.google') }}" class="btn-google">
            <svg width="18" height="18" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.31-8.16 2.31-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                <path fill="none" d="M0 0h48v48H0z"/>
            </svg>
            Masuk dengan Google
        </a>
    </div>

    <div class="auth-bottom">
        <p style="color:#ccc;font-size:13px;">Anggota baru UKM Finic?</p>
        <a href="{{ route('register') }}" class="btn btn-outline" style="background:rgba(255,255,255,.1);color:#fff;border-color:rgba(255,255,255,.3);margin-top:8px;">
            Buat Akun Baru
        </a>
    </div>
</div>
<div class="copyright-bar">© 2026 UKM Finic Equipment Rental. Hak Cipta Dilindungi.</div>


{{-- ====== FORGOT PASSWORD MODAL ====== --}}
<div class="forgot-modal-overlay" id="forgotModalOverlay" onclick="closeForgotModal(event)">
    <div class="forgot-modal">
        <button class="forgot-modal-close" onclick="closeForgotModalDirect()">&times;</button>

        <div class="form-state" id="forgotFormState">
            <div class="forgot-modal-icon">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
            </div>
            <h2 style="text-align:center;font-size:20px;margin-bottom:6px;">Atur Ulang Kata Sandi</h2>
            <p style="text-align:center;color:var(--gray-500);font-size:13px;margin-bottom:24px;">Masukkan email Anda yang terdaftar dan kami akan mengirimkan tautan pengaturan ulang.</p>

            @if(session('reset_status'))
                <div class="alert alert-success">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    {{ session('reset_status') }}
                </div>
            @endif

            @if($errors->hasBag('forgot'))
                <div class="alert alert-error">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ $errors->getBag('forgot')->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Alamat Email</label>
                    <div class="input-wrap">
                        <svg class="icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <input type="email" name="email" class="form-control with-icon" placeholder="m.mahasiswa@ukm.edu" value="{{ old('email') }}" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-full" style="font-size:15px;padding:13px;background:#16a34a;">
                    Kirim Tautan Atur Ulang
                </button>
            </form>

            <p style="text-align:center;font-size:12px;color:var(--gray-500);margin-top:16px;">
                Ingat kata sandi Anda? <a href="#" onclick="closeForgotModalDirect()" style="color:#16a34a;font-weight:600;">Kembali ke Login</a>
            </p>
        </div>

        @if(session('reset_status'))
        <div class="success-state show" id="forgotSuccessState">
            <div style="text-align:center;padding:12px 0;">
                <div style="width:64px;height:64px;background:#d1fae5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <svg width="28" height="28" fill="none" stroke="#16a34a" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <h3 style="margin-bottom:8px;">Periksa Email Anda!</h3>
                <p style="color:var(--gray-500);font-size:13px;line-height:1.6;">Kami telah mengirimkan tautan atur ulang kata sandi ke alamat email Anda. Silakan periksa kotak masuk (dan folder spam).</p>
                <button onclick="closeForgotModalDirect()" class="btn btn-primary" style="margin-top:20px;background:#16a34a;">Kembali ke Login</button>
            </div>
        </div>
        @endif
    </div>
</div>


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

// ── Forgot Password Modal ────────────────────────────────────
function openForgotModal(e) {
    e.preventDefault();
    document.getElementById('forgotModalOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeForgotModal(e) {
    if (e.target === document.getElementById('forgotModalOverlay')) closeForgotModalDirect();
}
function closeForgotModalDirect() {
    document.getElementById('forgotModalOverlay').classList.remove('open');
    document.body.style.overflow = '';
}

// ── Auto-open forgot modal jika ada error/session reset ──────
document.addEventListener('DOMContentLoaded', function () {
    @if($errors->hasBag('forgot') || session('reset_status'))
        document.getElementById('forgotModalOverlay').classList.add('open');
        document.body.style.overflow = 'hidden';
    @endif
});

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