<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UKM Finic – Sistem Peminjaman Alat Digital</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

{{-- HERO NAV --}}
<nav class="hero-nav">
    <div style="display:flex; align-items:center; gap:10px; font-size:17px; font-weight:700; color:#fff;">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="var(--primary)"><path d="M12 2a10 10 0 100 20A10 10 0 0012 2zm0 4a3 3 0 110 6 3 3 0 010-6zm0 14a8 8 0 01-6.22-2.98A9.97 9.97 0 0112 14c2.33 0 4.46.8 6.22 2.02A8 8 0 0112 20z"/></svg>
        UKM Finic
    </div>
    <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Masuk Akun</a>
</nav>

{{-- HERO SECTION --}}
<div class="hero-body">
    <div class="hero-content">
        <span class="hero-tag">📷 Tersedia Kamera Mirrorless & Aksesori</span>
        <h1 class="hero-title">
            Manajemen Alat<br>
            <span>Digital</span> untuk UKM Finic
        </h1>
        <p class="hero-sub">Mendukung kreativitas komunitas UKM Finic dengan peralatan fotografi standar profesional. Cari, pesan, dan kelola peminjaman alat dalam satu platform terpadu.</p>
        <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('register') }}" class="btn btn-primary" style="font-size:16px;padding:13px 28px;">
                Mulai Sekarang →
            </a>
            <a href="{{ route('member.equipment.index') }}" class="btn" style="background:rgba(255,255,255,.15);color:#fff;border:1.5px solid rgba(255,255,255,.3);font-size:16px;padding:13px 28px;">
                Lihat Daftar Alat
            </a>
        </div>
    </div>
</div>

{{-- HERO STATS --}}
<div class="hero-stats">
    <div class="hero-stat" style="text-align:center;">
        <div class="num">248+</div>
        <div class="lbl">ALAT AKTIF</div>
    </div>
    <div class="hero-stat" style="text-align:center;">
        <div class="num">1.2k</div>
        <div class="lbl">ANGGOTA AKTIF</div>
    </div>
    <div class="hero-stat" style="text-align:center;">
        <div class="num">5.6k</div>
        <div class="lbl">TOTAL PEMINJAMAN</div>
    </div>
    <div class="hero-stat" style="text-align:center;">
        <div class="num">24/7</div>
        <div class="lbl">JAM LAYANAN</div>
    </div>
</div>

{{-- FEATURES --}}
<section class="features">
    <div style="max-width:1000px;margin:0 auto;">
        <div style="text-align:center;">
            <h2>Mengapa UKM Finic Rental?</h2>
            <p class="text-gray" style="margin-top:8px;">Kami menyediakan infrastruktur terbaik agar Anda bisa fokus menangkap momen yang sempurna.</p>
        </div>
        <div class="features-grid">
            <div class="feature-item">
                {{-- Diubah ke tema Hijau Utama --}}
                <div class="feature-icon" style="background:var(--primary-light);color:var(--primary);">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg>
                </div>
                <h4>Pelacakan Real-time</h4>
                <p class="text-sm text-gray" style="margin-top:6px;">Cek ketersediaan bodi kamera maupun lensa secara instan langsung dari inventaris kami.</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon" style="background:#FFF7ED;color:var(--orange);">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                </div>
                <h4>Alat Standar Profesional</h4>
                <p class="text-sm text-gray" style="margin-top:6px;">Akses berbagai pilihan kamera DSLR, Mirrorless, hingga kamera sinema kelas atas untuk proyek Anda.</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon" style="background:#D1FAE5;color:#059669;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9,11 12,14 22,4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
                </div>
                <h4>Persetujuan Praktis</h4>
                <p class="text-sm text-gray" style="margin-top:6px;">Formulir pengajuan digital dan alur verifikasi otomatis membuat proses pinjam alat jadi lebih cepat.</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon" style="background:#FEE2E2;color:#EF4444;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <h4>Keamanan Peralatan</h4>
                <p class="text-sm text-gray" style="margin-top:6px;">Perawatan berkala dan catatan kondisi log memastikan semua alat selalu siap digunakan beraksi.</p>
            </div>
        </div>
    </div>
</section>

{{-- CATALOG BROWSE --}}
<section class="catalog-section">
    <div style="max-width:1000px;margin:0 auto;">
        <div class="flex-between">
            <div>
                <h2>Jelajahi Katalog Kami</h2>
                <p class="text-gray" style="margin-top:6px;">Mulai dari kamera full-frame andalan, lensa makro khusus, hingga perlengkapan lampu nirkabel.</p>
            </div>
            <a href="{{ route('member.equipment.index') }}" class="text-primary flex-center gap-8" style="text-decoration:none;font-weight:600;">Lihat Semua Alat →</a>
        </div>
        <div class="catalog-grid">
            <div class="catalog-cat" style="background:#1a1a2e;">
                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:48px;">📷</div>
                <div class="catalog-cat-label">Bodi Kamera</div>
            </div>
            <div class="catalog-cat" style="background:#16213e;">
                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:48px;">🔭</div>
                <div class="catalog-cat-label">Lensa Premium</div>
            </div>
            <div class="catalog-cat" style="background:#0f3460;">
                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:48px;">💡</div>
                <div class="catalog-cat-label">Paket Pencahayaan</div>
            </div>
            <div class="catalog-cat" style="background:#533483;">
                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:48px;">🎬</div>
                <div class="catalog-cat-label">Aksesori Alat</div>
            </div>
        </div>
    </div>
</section>

{{-- 3-STEP PROCESS --}}
<section class="steps-section">
    <div style="max-width:800px;margin:0 auto;text-align:center;">
        <h2>Alur Mudah 3 Langkah</h2>
        <div class="steps-grid" style="margin-top:40px;">
            <div>
                <div class="step-num">🔍</div>
                <h4>Cari Alat</h4>
                <p class="text-sm text-gray" style="margin-top:6px;">Temukan perlengkapan yang Anda butuhkan di katalog pencarian kami.</p>
            </div>
            <div>
                <div class="step-num">📅</div>
                <h4>Pilih Tanggal</h4>
                <p class="text-sm text-gray" style="margin-top:6px;">Tentukan tanggal pengambilan dan pengembalian untuk sesi pemotretan Anda.</p>
            </div>
            <div>
                <div class="step-num">🎨</div>
                <h4>Mulai Berkarya</h4>
                <p class="text-sm text-gray" style="margin-top:6px;">Ambil peralatan Anda di Ruang 402 dan mulailah menghasilkan karya terbaik.</p>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="cta-section">
    <h2>Siap untuk Memulai Proyek Anda?</h2>
    <p>Bergabunglah dengan ratusan mahasiswa UKM Finic yang telah menggunakan peralatan kami untuk menangkap visi kreatif mereka.</p>
    <div class="cta-btns">
        <a href="{{ route('register') }}" class="btn" style="background:#fff; color:var(--primary); font-size:15px; padding:13px 28px; font-weight:600;">Daftar Akun</a>
        <a href="{{ route('member.equipment.index') }}" class="btn" style="background:rgba(255,255,255,.15); color:#fff; border:1.5px solid rgba(255,255,255,.3); font-size:15px; padding:13px 28px;">Jelajahi Inventaris</a>
    </div>
</section>

{{-- FOOTER --}}
<footer class="landing-footer">
    <div class="footer-grid" style="max-width:1000px; margin:0 auto 32px;">
        <div class="footer-brand">
            <div style="font-size:18px; font-weight:700; color:var(--primary);">📷 UKM Finic</div>
            <p>Mempermudah manajemen peralatan fotografi untuk komunitas UKM Finic. Peralatan profesional untuk hasil karya yang profesional.</p>
        </div>
        <div class="footer-col">
            <h4>Tautan Pintas</h4>
            <a href="{{ route('member.equipment.index') }}">Katalog Alat</a>
            <a href="#">Aturan Peminjaman</a>
            <a href="#">FAQ Anggota</a>
        </div>
        <div class="footer-col">
            <h4>Kategori</h4>
            <a href="#">DSLR & Mirrorless</a>
            <a href="#">Lensa Prime</a>
            <a href="#">Paket Pencahayaan</a>
        </div>
        <div class="footer-col">
            <h4>Kontak</h4>
            <a href="mailto:finic@ukm.edu">finic@ukm.edu</a>
            <a href="#">Gedung Kesenian, Ruang 402</a>
            <a href="#">Senin - Jumat: 09:00 - 17:00</a>
        </div>
    </div>
    <div class="footer-bottom" style="max-width:1000px; margin:0 auto;">
        <span>© 2026 UKM Finic Equipment Rental & Management System</span>
        <div style="display:flex; gap:16px;">
            <a href="#">Kebijakan Privasi</a>
            <a href="#">Ketentuan Layanan</a>
        </div>
    </div>
</footer>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>