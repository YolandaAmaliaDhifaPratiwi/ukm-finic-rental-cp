{{-- resources/views/admin/notifications/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Pusat Notifikasi')

@push('styles')
<style>
/* ═══════════════════════════════════════════
   PREMIUM NOTIFICATION SYSTEM — UKM FINIC
   ═══════════════════════════════════════════ */

body {
    background-color: #f8fafc;
}

/* Container Wrapper Utama: Menghilangkan kekosongan di kanan-kiri desktop */
.notif-dashboard-grid {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 24px;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px 40px;
    box-sizing: border-box;
}

.notif-main-panel {
    min-width: 0;
}

/* ── Top Header Title ── */
.notif-page-title {
    font-size: 1.6rem;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.02em;
    margin: 0 0 4px 0;
}

.notif-page-subtitle {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0;
}

/* ── Filter Tab Navigation (Premium Capsule Design) ── */
.notif-filter-bar {
    display: flex;
    gap: 8px;
    margin: 24px 0 16px 0;
    background: #f1f5f9;
    padding: 6px;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
}

.notif-tab-btn {
    flex: 1;
    text-align: center;
    padding: 10px 16px;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 600;
    color: #64748b;
    background: transparent;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.notif-tab-btn:hover {
    color: #0f172a;
}

.notif-tab-btn.active {
    background: #ffffff;
    color: #10b981;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.02);
}

/* ── Notification Feed List ── */
.notif-feed-container {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

/* Main Notification Item Card */
.notif-item-card {
    display: flex;
    align-items: start;
    gap: 16px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 18px 24px;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    text-align: left;
    width: 100%;
    box-sizing: border-box;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
}

.notif-item-card:hover {
    border-color: #cbd5e1;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(15, 23, 42, 0.03);
}

/* Indikator Unread Bulat Hijau Sisi Kiri */
.notif-item-card.unread {
    background: #f0fdf4;
    border-color: #bbf7d0;
}

.notif-item-card.unread::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4.5px;
    background: #10b981;
    border-radius: 16px 0 0 16px;
}

/* ── Professional Vector-Style Icon Wrapper ── */
.notif-vector-icon-box {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border: 1px solid transparent;
}

/* Skema Warna Status Premium Pastel */
.icon-box-borrow_request   { background: #eff6ff; color: #2563eb; border-color: #dbeafe; }
.icon-box-return_submitted { background: #fffbeb; color: #d97706; border-color: #fef3c7; }
.icon-box-borrow_approved  { background: #ecfdf5; color: #059669; border-color: #d1fae5; }
.icon-box-borrow_rejected  { background: #fdf2f2; color: #dc2626; border-color: #fee2e2; }
.icon-box-return_confirmed { background: #ecfdf5; color: #059669; border-color: #d1fae5; }
.icon-box-return_rejected  { background: #fdf2f2; color: #dc2626; border-color: #fee2e2; }
.icon-box-default          { background: #f8fafc; color: #64748b; border-color: #e2e8f0; }

/* Typography inside card */
.notif-card-body {
    flex: 1;
    min-width: 0;
}

.notif-card-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 4px;
}

.notif-card-msg {
    font-size: 0.875rem;
    color: #475569;
    line-height: 1.5;
}

.notif-card-meta {
    font-size: 0.78rem;
    color: #94a3b8;
    margin-top: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
    font-weight: 500;
}

.notif-green-dot {
    width: 6px;
    height: 6px;
    background-color: #10b981;
    border-radius: 50%;
    display: inline-block;
}

/* ── Right Column Side Control Panel (Ramping & Pas Konten) ── */
.notif-sidebar-panel {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 16px 20px; /* Padding vertikal dipendekkan agar pas */
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    position: sticky;
    top: 24px;
    align-self: start; /* Mencegah card memanjang ke bawah secara otomatis */
}

.sidebar-panel-title {
    font-size: 0.78rem;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 12px;
}

.sidebar-stat-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f1f5f9;
}

.sidebar-stat-row:last-of-type {
    border-bottom: none;
    margin-bottom: 14px;
}

.stat-label {
    font-size: 0.85rem;
    color: #64748b;
    font-weight: 500;
}

.stat-value {
    font-size: 0.95rem;
    font-weight: 700;
    color: #0f172a;
}

/* Control Panel Buttons (Tema Hijau Premium) */
.sidebar-action-btn {
    width: 100%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    padding: 10px 16px;
    border-radius: 10px;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.2s ease;
    box-sizing: border-box;
}

.btn-sidebar-success {
    background: #10b981;
    color: #ffffff;
    box-shadow: 0 2px 6px rgba(16, 185, 129, 0.15);
}

.btn-sidebar-success:hover {
    background: #059669;
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
}

/* ── Empty State Design ── */
.notif-empty-state {
    text-align: center;
    padding: 64px 32px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    color: #64748b;
}

.notif-empty-icon-wrap {
    width: 56px;
    height: 56px;
    background: #f1f5f9;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    color: #94a3b8;
}

.notif-empty-title {
    font-size: 1rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 4px;
}

/* ── Professional Modal Overlays ── */
.nd-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.3);
    backdrop-filter: blur(6px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.25s ease;
}

.nd-overlay.open {
    opacity: 1;
    pointer-events: auto;
}

.nd-modal {
    background: #ffffff;
    border-radius: 24px;
    width: 100%;
    max-width: 560px;
    max-height: 85vh;
    overflow-y: auto;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    transform: scale(0.95) translateY(12px);
    transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.nd-overlay.open .nd-modal {
    transform: scale(1) translateY(0);
}

.nd-modal-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 24px;
    border-bottom: 1px solid #e2e8f0;
    position: sticky;
    top: 0;
    background: #ffffff;
    z-index: 10;
}

.nd-modal-icon-container {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.nd-modal-title {
    font-size: 1.1rem;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.3;
}

.nd-modal-time {
    font-size: 0.8rem;
    color: #64748b;
    margin-top: 2px;
}

.nd-close {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    border: none;
    background: #f1f5f9;
    color: #475569;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    transition: all 0.2s;
    margin-left: auto;
}

.nd-close:hover {
    background: #fee2e2;
    color: #ef4444;
}

/* Modal Body Inner Styling */
.nd-modal-body {
    padding: 24px;
}

.nd-message-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-left: 4px solid #10b981;
    border-radius: 12px;
    padding: 16px;
    font-size: 0.9rem;
    line-height: 1.6;
    color: #334155;
    margin-bottom: 24px;
}

.nd-section-label {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #94a3b8;
    margin-bottom: 12px;
}

.nd-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 24px;
}

.nd-info-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px;
}

.nd-info-card-label {
    font-size: 0.75rem;
    color: #64748b;
    font-weight: 600;
    margin-bottom: 4px;
}

.nd-info-card-value {
    font-size: 0.9rem;
    font-weight: 700;
    color: #0f172a;
}

.nd-info-card-sub {
    font-size: 0.78rem;
    color: #64748b;
    margin-top: 2px;
}

.nd-purpose-box {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-left: 4px solid #10b981;
    border-radius: 12px;
    padding: 16px;
    font-size: 0.9rem;
    color: #14532d;
    line-height: 1.6;
    white-space: pre-line;
    margin-bottom: 24px;
}

.nd-full-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    font-weight: 700;
    color: #ffffff;
    text-decoration: none;
    padding: 12px 20px;
    background: #10b981;
    border-radius: 12px;
    transition: all 0.2s;
    width: 100%;
    justify-content: center;
    box-sizing: border-box;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
}

.nd-full-link:hover {
    background: #059669;
}

/* Modal Status Badges */
.nd-badge {
    display: inline-block;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
}
.nd-badge-pending   { background: #fef3c7; color: #92400e; }
.nd-badge-approved  { background: #d1fae5; color: #065f46; }
.nd-badge-rejected  { background: #fee2e2; color: #991b1b; }
.nd-badge-confirmed { background: #dbeafe; color: #1e40af; }
.nd-badge-returned  { background: #f3e8ff; color: #6b21a8; }

/* Loading Spinner Design */
.nd-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 56px 24px;
    color: #64748b;
    font-size: 0.9rem;
    font-weight: 500;
}

.nd-spinner {
    width: 24px;
    height: 24px;
    border: 2.5px solid #e2e8f0;
    border-top-color: #10b981;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin { 
    to { transform: rotate(360deg); } 
}

/* Toast Notification Global style */
#ndToast {
    position: fixed;
    bottom: 32px;
    right: 32px;
    background: #0f172a;
    color: #ffffff;
    padding: 12px 20px;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: 600;
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
    z-index: 99999;
    opacity: 0;
    transform: translateY(12px);
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    pointer-events: none;
}

#ndToast.show {
    opacity: 1;
    transform: translateY(0);
}

/* Pagination UI controls */
.np-pagination {
    margin-top: 32px;
    display: flex;
    justify-content: center;
}

.np-pagination .pagination {
    display: flex;
    gap: 6px;
    align-items: center;
    padding: 0;
    margin: 0;
    list-style: none;
}

.np-pagination .page-item .page-link {
    padding: 10px 16px;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 600;
    border: 1px solid #e2e8f0;
    color: #334155;
    text-decoration: none;
    background-color: #ffffff;
    transition: all 0.15s ease;
}

.np-pagination .page-item.active .page-link {
    background: #10b981;
    color: #ffffff;
    border-color: #10b981;
}

.np-pagination .page-item .page-link:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
}

@media (max-width: 992px) {
    .notif-dashboard-grid { grid-template-columns: 1fr; }
    .notif-sidebar-panel { position: static; margin-top: 16px; }
}

@media (max-width: 640px) {
    .nd-info-grid { grid-template-columns: 1fr; }
    .notif-tab-btn { font-size: 0.78rem; padding: 8px 10px; }
}
</style>
@endpush

@section('content')
<div class="notif-dashboard-grid">

    {{-- SISI KIRI: UTAMA (HEADER, TABS, DAFTAR FEED NOTIFIKASI) --}}
    <div class="notif-main-panel">
        
        {{-- Header Section --}}
        <div>
            <h1 class="notif-page-title">Pusat Notifikasi</h1>
            <p class="notif-page-subtitle">
                Total keseluruhan sistem merekam {{ $notifications->total() }} riwayat aktivitas
            </p>
        </div>

        {{-- Filter Kategori Capsule Style Nav --}}
        <div class="notif-filter-bar">
            <button class="notif-tab-btn active" id="tab-all" onclick="filterTab('all', this)">Semua</button>
            <button class="notif-tab-btn" id="tab-borrow" onclick="filterTab('borrow', this)">Peminjaman</button>
            <button class="notif-tab-btn" id="tab-return" onclick="filterTab('return', this)">Pengembalian</button>
        </div>

        {{-- Feed List Row Penampung --}}
        <div class="notif-feed-container" id="notifList">
            @forelse($notifications as $notif)
                @php
                    $data = $notif->data;
                    $type = $data['type'] ?? 'info';
                    $isUnread = is_null($notif->read_at);
                    
                    // Box Icon Class Mapping
                    $iconBoxClass = match($type) {
                        'borrow_request'   => 'icon-box-borrow_request',
                        'return_submitted' => 'icon-box-return_submitted',
                        'borrow_approved'  => 'icon-box-borrow_approved',
                        'borrow_rejected'  => 'icon-box-borrow_rejected',
                        'return_confirmed' => 'icon-box-return_confirmed',
                        'return_rejected'  => 'icon-box-return_rejected',
                        default            => 'icon-box-default',
                    };

                    // Premium SVG Outline Vector Graphics Map (No more emojis)
                    $svgIcon = match($type) {
                        'borrow_request'   => '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>',
                        'return_submitted' => '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-14L4 7m8 4v10M4 7v10l8 4"/></svg>',
                        'borrow_approved', 'return_confirmed' => '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                        'borrow_rejected', 'return_rejected'  => '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                        default            => '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                    };
                @endphp

                <button
                    class="notif-item-card {{ $isUnread ? 'unread' : '' }}"
                    id="item-{{ $notif->id }}"
                    data-id="{{ $notif->id }}"
                    data-type="{{ $type }}"
                    onclick="openDetail('{{ $notif->id }}')"
                >
                    <div class="notif-vector-icon-box {{ $iconBoxClass }}">
                        {!! $svgIcon !!}
                    </div>
                    
                    <div class="notif-card-body">
                        <div class="notif-card-title">{{ $data['title'] ?? 'Pemberitahuan Sistem' }}</div>
                        <div class="notif-card-msg">{{ $data['message'] ?? '' }}</div>
                        <div class="notif-card-meta">
                            @if($isUnread)<span class="notif-green-dot" title="Belum dibaca"></span>@endif
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg>
                            {{ $notif->created_at->diffForHumans() }}
                        </div>
                    </div>
                </button>
            @empty
                <div class="notif-empty-state">
                    <div class="notif-empty-icon-wrap">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <div class="notif-empty-title">Kotak masuk bersih</div>
                    <p style="margin: 0; font-size: 0.875rem;">Tidak ada pemberitahuan baru yang terdaftar saat ini.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination Row Links Controls --}}
        @if($notifications->hasPages())
            <div class="np-pagination">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>

    {{-- SISI KANAN: PANEL KONTROL RINGKASAN AKTIVITAS --}}
    <div class="notif-sidebar-panel">
        <div class="sidebar-panel-title">Panel Kontrol</div>
        
        <div class="sidebar-stat-row">
            <span class="stat-label">Belum Dibaca</span>
            <span class="stat-value" style="color: #10b981;">
                {{ $notifications->getCollection()->where('read_at', null)->count() }}
            </span>
        </div>
        <div class="sidebar-stat-row">
            <span class="stat-label">Total Halaman Ini</span>
            <span class="stat-value">{{ $notifications->count() }}</span>
        </div>
        
        <div style="margin-top: 12px;">
            <button class="sidebar-action-btn btn-sidebar-success" onclick="clearRead()">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="3,6 5,6 21,6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                Bersihkan yang Dibaca
            </button>
        </div>
    </div>

</div>

{{-- ══ MODAL BOX POP-UP DETAIL NOTIFIKASI ══ --}}
<div class="nd-overlay" id="ndOverlay" onclick="closeModal()">
    <div class="nd-modal" onclick="event.stopPropagation()">

        {{-- Modal Header Row --}}
        <div class="nd-modal-header">
            <div class="nd-modal-icon-container" id="ndIcon" style="background:#f1f5f9;">
                {{-- Diisi secara dinamis melalui JavaScript --}}
            </div>
            <div style="flex: 1; min-width: 0;">
                <div class="nd-modal-title" id="ndTitle">Detail Notifikasi</div>
                <div class="nd-modal-time" id="ndTime"></div>
            </div>
            <button class="nd-close" onclick="closeModal()" title="Tutup">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        {{-- Loading Skeleton Loader Element --}}
        <div id="ndLoading" class="nd-loading">
            <div class="nd-spinner"></div>
            <span>Mengambil data dari server…</span>
        </div>

        {{-- Main Container Content Modal --}}
        <div id="ndContent" class="nd-modal-body" style="display:none;">
            <div class="nd-message-box" id="ndMessage"></div>

            {{-- Grid Section Data Transaksi --}}
            <div id="ndInfoWrap" style="display:none;">
                <div class="nd-section-label" id="ndInfoLabel">Informasi Lengkap</div>
                <div class="nd-info-grid" id="ndInfoGrid"></div>
            </div>

            {{-- Text Area Section Tujuan Peminjaman --}}
            <div id="ndPurposeWrap" style="display:none;">
                <div class="nd-section-label">Tujuan Keperluan</div>
                <div class="nd-purpose-box" id="ndPurpose"></div>
            </div>

            {{-- Text Area Section Catatan Penolakan/Admin --}}
            <div id="ndAdminNoteWrap" style="display:none;">
                <div class="nd-section-label">Catatan Tambahan Admin</div>
                <div class="nd-purpose-box" style="border-color:#fecaca; border-left-color:#ef4444; background:#fef2f2; color:#991b1b;" id="ndAdminNote"></div>
            </div>

            {{-- Action Link URL Go to Detail Page Trx --}}
            <a id="ndFullLink" href="#" class="nd-full-link" style="display:none;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15,3 21,3 21,9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                Buka Halaman Formulir Detail
            </a>
        </div>

    </div>
</div>

{{-- Global System Toast Box Element --}}
<div id="ndToast"></div>
@endsection

@push('scripts')
<script>
(function () {
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

    // Modern Vector Icons mapping inside popups
    const SVG_ICONS = {
        borrow_request   : '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>',
        return_submitted : '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-14L4 7m8 4v10M4 7v10l8 4"/></svg>',
        borrow_approved  : '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        return_confirmed : '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        borrow_rejected  : '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        return_rejected  : '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    };

    const ICON_BG_COLOR = {
        borrow_request   : '#eff6ff',
        return_submitted : '#fffbeb',
        borrow_approved  : '#ecfdf5',
        borrow_rejected  : '#fdf2f2',
        return_confirmed : '#ecfdf5',
        return_rejected  : '#fdf2f2',
    };

    const ICON_TEXT_COLOR = {
        borrow_request   : '#2563eb',
        return_submitted : '#d97706',
        borrow_approved  : '#059669',
        borrow_rejected  : '#dc2626',
        return_confirmed : '#059669',
        return_rejected  : '#dc2626',
    };

    /* ── Filter Tabs Kategori Realtime ── */
    window.filterTab = function (filter, btn) {
        document.querySelectorAll('.notif-tab-btn').forEach(t => t.classList.remove('active'));
        btn.classList.add('active');

        document.querySelectorAll('.notif-item-card').forEach(item => {
            const type = item.dataset.type || '';
            let show = true;
            if (filter === 'borrow')  show = type.startsWith('borrow');
            if (filter === 'return')  show = type.startsWith('return');
            item.style.display = show ? '' : 'none';
        });

        const list = document.getElementById('notifList');
        const visible = [...list.querySelectorAll('.notif-item-card')].filter(el => el.style.display !== 'none');
        let emptyEl = list.querySelector('.notif-empty-dynamic');
        
        if (visible.length === 0) {
            if (!emptyEl) {
                emptyEl = document.createElement('div');
                emptyEl.className = 'notif-empty-state notif-empty-dynamic';
                emptyEl.innerHTML = `
                    <div class="notif-empty-icon-wrap"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></div>
                    <div class="notif-empty-title">Tidak ditemukan</div>
                    <p style="margin:0;font-size:0.85rem;">Tidak ada riwayat untuk filter kategori saat ini.</p>`;
                list.appendChild(emptyEl);
            }
        } else {
            emptyEl?.remove();
        }
    };

    /* ── Action: Buka Box Modal Detail Notifikasi ── */
    window.openDetail = function (id) {
        const overlay = document.getElementById('ndOverlay');
        document.getElementById('ndLoading').style.display = 'flex';
        document.getElementById('ndContent').style.display = 'none';
        document.getElementById('ndTitle').textContent = 'Detail Notifikasi';
        document.getElementById('ndTime').textContent = '';
        overlay.classList.add('open');
        document.body.style.overflow = 'hidden';

        const item = document.getElementById('item-' + id);
        if (item) {
            item.classList.remove('unread');
            item.querySelector('.notif-green-dot')?.remove();
        }

        fetch(`/admin/notifications/${id}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(r => {
            if (!r.ok) throw new Error('fetch failed');
            return r.json();
        })
        .then(d => renderDetail(d))
        .catch(() => {
            const msgEl = item?.querySelector('.notif-card-msg');
            const titleEl = item?.querySelector('.notif-card-title');
            renderFallback(titleEl?.textContent || 'Notifikasi', msgEl?.textContent || '');
        });
    };

    function renderDetail(d) {
        const type = d.type || 'info';
        const iconContainer = document.getElementById('ndIcon');
        
        // Render Vector Graphic dynamically into the modal box header
        iconContainer.innerHTML = SVG_ICONS[type] || '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        iconContainer.style.background = ICON_BG_COLOR[type] || '#f1f5f9';
        iconContainer.style.color = ICON_TEXT_COLOR[type] || '#64748b';
        
        document.getElementById('ndTitle').textContent = d.title || 'Notifikasi';
        document.getElementById('ndTime').textContent = d.created_at || '';
        document.getElementById('ndMessage').textContent = d.message || '';

        const infoWrap = document.getElementById('ndInfoWrap');
        const infoGrid = document.getElementById('ndInfoGrid');
        const purposeWrap = document.getElementById('ndPurposeWrap');
        const adminNoteWrap = document.getElementById('ndAdminNoteWrap');
        const fullLink = document.getElementById('ndFullLink');

        infoWrap.style.display = 'none';
        purposeWrap.style.display = 'none';
        adminNoteWrap.style.display = 'none';
        fullLink.style.display = 'none';

        const purposeLabel = purposeWrap.querySelector('.nd-section-label');
        if (purposeLabel) purposeLabel.textContent = 'Tujuan Keperluan';

        // Tipe Alur Data Transaksi Peminjaman Alat UKM
        if (d.borrowing) {
            const b = d.borrowing;
            document.getElementById('ndInfoLabel').textContent = 'Informasi Transaksi Peminjaman';
            infoGrid.innerHTML = `
                <div class="nd-info-card">
                    <div class="nd-info-card-label">👤 Peminjam (Member)</div>
                    <div class="nd-info-card-value">${esc(b.user_name)}</div>
                    <div class="nd-info-card-sub">${esc(b.user_email || '')}${b.user_student_id && b.user_student_id !== '-' ? ' · ' + esc(b.user_student_id) : ''}</div>
                </div>
                <div class="nd-info-card">
                    <div class="nd-info-card-label">📑 Status Validasi</div>
                    <div class="nd-info-card-value">${statusBadge(b.status)}</div>
                    <div class="nd-info-card-sub">${esc(b.transaction_code || '')}</div>
                </div>
                <div class="nd-info-card">
                    <div class="nd-info-card-label">📷 Inventaris Alat</div>
                    <div class="nd-info-card-value">${esc(b.equipment_name)}</div>
                    <div class="nd-info-card-sub">${esc(b.equipment_category || '')}${b.equipment_condition ? ' · ' + esc(b.equipment_condition) : ''}</div>
                </div>
                <div class="nd-info-card">
                    <div class="nd-info-card-label">⏱ Batas Waktu</div>
                    <div class="nd-info-card-value">${b.duration_days || '-'} Hari Peminjaman</div>
                    <div class="nd-info-card-sub">Masuk sistem ${esc(b.created_at || '')}</div>
                </div>
                <div class="nd-info-card">
                    <div class="nd-info-card-label">📅 Tanggal Pengambilan</div>
                    <div class="nd-info-card-value">${b.borrow_date || '-'}</div>
                </div>
                <div class="nd-info-card">
                    <div class="nd-info-card-label">📅 Batas Target Kembali</div>
                    <div class="nd-info-card-value">${b.return_date || '-'}</div>
                </div>`;
            infoWrap.style.display = 'block';

            if (b.purpose) {
                document.getElementById('ndPurpose').textContent = b.purpose;
                purposeWrap.style.display = 'block';
            }
            if (b.admin_notes) {
                document.getElementById('ndAdminNote').textContent = b.admin_notes;
                adminNoteWrap.style.display = 'block';
            }
            if (b.link) {
                fullLink.href = b.link;
                fullLink.style.display = 'flex';
            }
        }

        // Tipe Alur Data Transaksi Pengembalian Alat Finic
        if (d.return) {
            const r = d.return;
            document.getElementById('ndInfoLabel').textContent = 'Informasi Transaksi Pengembalian';
            infoGrid.innerHTML = `
                <div class="nd-info-card">
                    <div class="nd-info-card-label">👤 Pengembali (Member)</div>
                    <div class="nd-info-card-value">${esc(r.user_name)}</div>
                    <div class="nd-info-card-sub">${esc(r.user_email || '')}</div>
                </div>
                <div class="nd-info-card">
                    <div class="nd-info-card-label">📑 Status Penyerahan</div>
                    <div class="nd-info-card-value">${statusBadge(r.status)}</div>
                    <div class="nd-info-card-sub">${esc(r.transaction_code || '')}</div>
                </div>
                <div class="nd-info-card">
                    <div class="nd-info-card-label">📷 Inventaris Alat</div>
                    <div class="nd-info-card-value">${esc(r.equipment_name)}</div>
                    <div class="nd-info-card-sub">${esc(r.equipment_category || '')}</div>
                </div>
                <div class="nd-info-card">
                    <div class="nd-info-card-label">📅 Waktu Penyerahan Fisik</div>
                    <div class="nd-info-card-value">${esc(r.returned_at || '-')}</div>
                    ${r.confirmed_at ? `<div class="nd-info-card-sub">Verif Admin: ${esc(r.confirmed_at)}</div>` : ''}
                </div>`;
            infoWrap.style.display = 'block';

            if (r.condition_notes) {
                document.getElementById('ndPurpose').textContent = r.condition_notes;
                if (purposeLabel) purposeLabel.textContent = 'Catatan Kondisi Fisik Alat';
                purposeWrap.style.display = 'block';
            }
            if (r.admin_notes) {
                document.getElementById('ndAdminNote').textContent = r.admin_notes;
                adminNoteWrap.style.display = 'block';
            }
            if (r.link) {
                fullLink.href = r.link;
                fullLink.style.display = 'flex';
            }
        }

        document.getElementById('ndLoading').style.display = 'none';
        document.getElementById('ndContent').style.display = 'block';
    }

    function renderFallback(title, message) {
        document.getElementById('ndTitle').textContent = title;
        document.getElementById('ndMessage').textContent = message;
        document.getElementById('ndIcon').innerHTML = '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        document.getElementById('ndIcon').style.background = '#f1f5f9';
        document.getElementById('ndIcon').style.color = '#64748b';
        document.getElementById('ndInfoWrap').style.display = 'none';
        document.getElementById('ndPurposeWrap').style.display = 'none';
        document.getElementById('ndAdminNoteWrap').style.display = 'none';
        document.getElementById('ndFullLink').style.display = 'none';
        document.getElementById('ndLoading').style.display = 'none';
        document.getElementById('ndContent').style.display = 'block';
    }

    window.closeModal = function () {
        document.getElementById('ndOverlay').classList.remove('open');
        document.body.style.overflow = '';
    };

    /* ── Action: Bersihkan Semua Notifikasi Sudah Dibaca ── */
    window.clearRead = function () {
        if (!confirm('Hapus seluruh riwayat notifikasi yang statusnya sudah terbaca?')) return;
        fetch('/admin/notifications/clear-read', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(r => r.json())
        .then(() => {
            document.querySelectorAll('.notif-item-card:not(.unread)').forEach(el => el.remove());
            showToast('Semua riwayat lama dibersihkan');
        })
        .catch(() => showToast('Gagal memproses penghapusan'));
    };

    /* ── Helper: Badge Style Generator di Dalam Modal ── */
    function statusBadge(status) {
        const map = {
            pending   : ['nd-badge-pending',   '⏳ Menunggu Verifikasi'],
            approved  : ['nd-badge-approved',  '✅ Disetujui Admin'],
            rejected  : ['nd-badge-rejected',  '❌ Pengajuan Ditolak'],
            confirmed : ['nd-badge-confirmed', '📋 Selesai Dikonfirmasi'],
            returned  : ['nd-badge-returned',  '📦 Alat Dikembalikan'],
        };
        const [cls, label] = map[status] || ['nd-badge-pending', status || '-'];
        return `<span class=\"nd-badge ${cls}\">${label}</span>`;
    }

    function esc(s) {
        if (!s) return '';
        return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function showToast(msg) {
        const t = document.getElementById('ndToast');
        t.textContent = msg;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 2500);
    }

    // Listener Keybind Escape untuk penutupan pop-up detail modal
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeModal();
    });
}());
</script>
@endpush