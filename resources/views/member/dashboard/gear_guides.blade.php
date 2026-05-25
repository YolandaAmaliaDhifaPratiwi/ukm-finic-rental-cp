@extends('layouts.member')
@section('title', 'Panduan Peminjaman')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=400;500;600;700;800&display=swap');
* { font-family: 'Plus Jakarta Sans', sans-serif; }

.guide-page {
    max-width: 1200px; 
    margin: 0;         
    padding: 0 32px 40px !important;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.guide-header {
    margin-bottom: 4px;
}

.guide-header h1 {
    font-size: 22px;
    font-weight: 800;
    color: #111827;
    margin: 0 0 4px 0;
    letter-spacing: -.3px;
}

.guide-header p {
    font-size: 14px;
    color: #6b7280;
    margin: 0;
}

/* Layout Grid Utama */
.guide-grid {
    display: grid;
    grid-template-columns: 1.6fr 1fr;
    gap: 24px;
    align-items: flex-start;
}

/* Card Minimalis Mengikuti Style Profile */
.guide-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e5e7eb;
    padding: 28px;
}

.guide-card h3 {
    font-size: 15px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 24px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Timeline CSS */
.timeline-container {
    position: relative;
    padding-left: 28px;
}

.timeline-line {
    position: absolute;
    left: 8px;
    top: 8px;
    bottom: 8px;
    width: 2px;
    background: #e5e7eb;
}

.timeline-item {
    position: relative;
    margin-bottom: 24px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-dot {
    position: absolute;
    left: -28px;
    top: 2px;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #059669;
    border: 4px solid #f0fdf4;
    z-index: 2;
}

.timeline-title {
    font-size: 14px;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.timeline-desc {
    font-size: 13px;
    color: #6b7280;
    margin-top: 4px;
    line-height: 1.6;
}

/* List Ketentuan */
.rule-list {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 14px;
    padding: 0;
    margin: 0;
}

.rule-item {
    display: flex;
    gap: 10px;
    font-size: 13px;
    align-items: flex-start;
    line-height: 1.5;
    color: #374151;
}

.rule-dot {
    color: #059669;
    font-weight: bold;
    font-size: 16px;
    line-height: 1;
}

.rule-dot.danger {
    color: #ef4444;
}

/* Style tombol kembali hijau minimalis */
.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 600;
    color: #059669;
    text-decoration: none;
    margin-bottom: 4px;
}

.btn-back:hover { 
    color: #047857; 
}

@media (max-width: 768px) {
    .guide-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
<div class="guide-page">
    
    {{-- Tombol Kembali ditaruh di paling atas --}}
    <div>
        <a href="{{ route('member.dashboard') }}" class="btn-back">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
            Kembali ke Beranda
        </a>
    </div>

    {{-- HEADER HALAMAN --}}
    <div class="guide-header">
        <h1>Panduan Peminjaman Alat</h1>
        <p>Informasi lengkap mengenai alur, batasan, dan aturan operasional peminjaman inventaris UKM Finic.</p>
    </div>

    {{-- LAYOUT UTAMA --}}
    <div class="guide-grid">
        
        {{-- KIRI: ALUR TIMELINE --}}
        <div class="guide-card">
            <h3>🔄 Alur Pengajuan & Pengembalian</h3>
            
            <div class="timeline-container">
                <div class="timeline-line"></div>

                {{-- Langkah 1 --}}
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <h4 class="timeline-title">1. Cari & Pilih Alat</h4>
                    <p class="timeline-desc">Pilih peralatan fotografi atau videografi yang Anda butuhkan melalui menu <strong>Daftar Alat</strong>, lalu klik tombol <strong>Pinjam Alat</strong>.</p>
                </div>

                {{-- Langkah 2 --}}
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <h4 class="timeline-title">2. Isi Form & Ajukan</h4>
                    <p class="timeline-desc">Tentukan tanggal pengambilan, tanggal pengembalian, serta tuliskan keperluan peminjaman Anda dengan jelas pada form pengajuan.</p>
                </div>

                {{-- Langkah 3 --}}
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <h4 class="timeline-title">3. Tunggu Verifikasi Admin</h4>
                    <p class="timeline-desc">Admin akan memeriksa pengajuan Anda dalam waktu maksimal <strong>24 jam</strong>. Pantau status persetujuan secara berkala melalui menu <strong>Riwayat</strong> atau halaman <strong>Notifikasi</strong>.</p>
                </div>

                {{-- Langkah 4 --}}
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <h4 class="timeline-title">4. Cek Fisik & Ambil Alat</h4>
                    <p class="timeline-desc">Jika disetujui, ambil alat di sekretariat. <strong>Wajib melakukan pemeriksaan kondisi fisik alat bersama admin</strong> sebelum membawa barang keluar dari ruangan.</p>
                </div>

                {{-- Langkah 5 --}}
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <h4 class="timeline-title">5. Pengembalian Tepat Waktu</h4>
                    <p class="timeline-desc">Kembalikan alat sesuai tanggal kesepakatan. Pastikan alat dicek kembali bersama admin untuk memastikan tidak ada kerusakan baru.</p>
                </div>
            </div>
        </div>

        {{-- KANAN: BATASAN & KETENTUAN --}}
        <div class="guide-card">
            <h3>📋 Batasan & Ketentuan</h3>
            
            <ul class="rule-list">
                <li class="rule-item">
                    <span class="rule-dot">•</span>
                    <div>Setiap anggota hanya diperbolehkan meminjam maksimal <strong>3 alat sekaligus</strong> dalam satu waktu.</div>
                </li>
                <li class="rule-item">
                    <span class="rule-dot">•</span>
                    <div>Durasi pinjam per pengajuan dibatasi maksimal selama <strong>7 hari kalender</strong>.</div>
                </li>
                <li class="rule-item">
                    <span class="rule-dot danger">•</span>
                    <div>Keterlambatan akan memotong <strong>poin reputasi akun</strong> Anda, yang dapat mempengaruhi prioritas pengajuan berikutnya.</div>
                </li>
            </ul>
        </div>

    </div>

</div>
@endsection