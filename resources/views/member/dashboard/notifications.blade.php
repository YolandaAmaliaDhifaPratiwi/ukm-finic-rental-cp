@extends('layouts.member')

@section('title', 'Pusat Notifikasi')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=400;500;600;700;800&display=swap');
* { font-family: 'Plus Jakarta Sans', sans-serif; }

.notification-page {
    max-width: 1200px; 
    margin: 0;         
    padding: 0 32px !important;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.notification-header {
    margin-bottom: 4px;
}

.notification-header h1 {
    font-size: 22px;
    font-weight: 800;
    color: #111827;
    margin: 0 0 4px 0;
    letter-spacing: -.3px;
}

.notification-header p {
    font-size: 14px;
    color: #6b7280;
    margin: 0;
}

/* Kolom Card Utama mengikuti style Profile */
.notification-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e5e7eb;
    padding: 14px 28px;
    display: flex;
    flex-direction: column;
}

.notification-item {
    display: flex; 
    gap: 16px; 
    align-items: flex-start; 
    padding: 20px 0; 
    border-bottom: 1px solid #f3f4f6;
}

.notification-item:last-child {
    border-bottom: none;
}

.gear-img-wrapper {
    width: 48px; 
    height: 48px; 
    border-radius: 10px; 
    background: #f3f4f6; 
    flex-shrink: 0; 
    display: flex; 
    align-items: center; 
    justify-content: center;
    border: 1px solid #e5e7eb;
}

.gear-title {
    font-weight: 700; 
    font-size: 15px;
    color: #111827;
}

.meta-row {
    font-size: 13px; 
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.text-muted {
    color: #6b7280;
}

.date-range {
    font-size: 13px;
    color: #4b5563;
    margin-top: 6px;
}

.time-ago {
    color: #9ca3af; 
    font-size: 12px;
    font-weight: 500;
    flex-shrink: 0;
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
</style>
@endpush

@section('content')
<div class="notification-page">

    {{-- Tombol Kembali ditaruh di paling atas --}}
    <div>
        <a href="{{ route('member.dashboard') }}" class="btn-back">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
            Kembali ke Beranda
        </a>
    </div>

    {{-- Judul Atas --}}
    <div class="notification-header">
        <h1>Pusat Notifikasi</h1>
        <p class="text-muted">Semua pembaruan mengenai aktivitas peminjaman alat Anda.</p>
    </div>

    {{-- Box Container Notifikasi --}}
    <div class="notification-card">
        @forelse($borrowings as $b)
        <div class="notification-item">
            
            {{-- Foto Alat --}}
            <div class="gear-img-wrapper">
                @if($b->equipment->image)
                    <img src="{{ asset('storage/'.$b->equipment->image) }}" style="width:100%; height:100%; object-fit:contain; border-radius:10px;">
                @else
                    <svg width="22" height="22" fill="none" stroke="#9ca3af" stroke-width="1.5" viewBox="0 0 24 24"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                @endif
            </div>

            {{-- Detail Informasi --}}
            <div style="flex:1;">
                <div class="gear-title">{{ $b->equipment->name }}</div>
                
                <div class="meta-row">
                    {!! $b->status_badge !!}
                    <span class="text-muted" style="font-weight: 600;">{{ $b->transaction_code }}</span>
                </div>

                <div class="date-range">
                    <span class="text-muted">Tanggal Pinjam:</span> {{ $b->borrow_date->locale('id')->format('d M Y') }} 
                    <span class="text-muted" style="margin: 0 4px;">→</span> 
                    <span class="text-muted">Tanggal Kembali:</span> {{ $b->return_date->locale('id')->format('d M Y') }}
                </div>

                @if($b->isOverdue())
                    <div style="color:#ef4444; font-size:12px; font-weight:700; margin-top:6px; display:flex; align-items:center; gap:4px;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Terlambat {{ abs($b->days_remaining) }} Hari
                    </div>
                @endif

                @if($b->status === 'rejected' && $b->admin_notes)
                    <div style="margin-top:10px; background:#fef2f2; border-left:3px solid #ef4444; border-radius:6px; padding:10px 12px; display:flex; align-items:flex-start; gap:8px;">
                        <svg width="14" height="14" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <div>
                            <div style="font-size:11px; font-weight:700; color:#dc2626; text-transform:uppercase; margin-bottom:2px;">Alasan Penolakan</div>
                            <div style="font-size:13px; color:#7f1d1d;">{{ $b->admin_notes }}</div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Waktu Notifikasi --}}
            <div class="time-ago">
                {{ $b->created_at->locale('id')->diffForHumans() }}
            </div>

        </div>
        @empty
        <div style="text-align:center; padding:50px 0; color:#9ca3af; font-size:14px; font-weight:500;">
            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:10px; color:#d1d5db;"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/></svg>
            <div>Tidak ada notifikasi baru saat ini.</div>
        </div>
        @endforelse
    </div>

</div>
@endsection