{{-- resources/views/member/returns/index.blade.php --}}
@extends('layouts.member')

@section('title', 'Pengembalian Alat')

@section('content')
<div class="returns-page">

    {{-- Header - Bersih Tanpa Ikon & Sejajar Sempurna Rata Kiri --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Pengembalian Alat</h1>
            <p class="page-subtitle">Kelola pengembalian peralatan yang saat ini sedang Anda pinjam.</p>
        </div>
    </div>

    {{-- Alert success/error --}}
    @if(session('success'))
        <div class="alert alert-success">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22,4 12,14.01 9,11.01"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="15" y1="9" x2="9" y2="15"/>
                <line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- ======= SECTION 1: Alat Aktif yang Bisa Dikembalikan ======= --}}
    <div class="section-card">
        <div class="section-header">
            <div class="section-title-wrap">
                <div class="dot dot-green"></div>
                <h2 class="section-title">Alat Aktif Siap Dikembalikan</h2>
            </div>
            <span class="badge-count">{{ $activeBorrowings->count() }} alat</span>
        </div>

        @if($activeBorrowings->isEmpty())
            <div class="empty-state">
                <div class="empty-icon-wrapper">
                    <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m-8-14l8 4m-8 4v10l8 4m0-10v10" />
                    </svg>
                </div>
                <p class="empty-text">Tidak ada peralatan aktif yang perlu dikembalikan.</p>
            </div>
        @else
            <div class="items-list">
                @foreach($activeBorrowings as $borrowing)
                    @php
                        $isOverdue = $borrowing->return_date && now()->gt($borrowing->return_date);
                        $daysLeft  = $borrowing->return_date ? now()->diffInDays($borrowing->return_date, false) : null;
                    @endphp
                    <div class="item-row {{ $isOverdue ? 'item-overdue' : '' }}">
                        <div class="item-img-wrap">
                            @if($borrowing->equipment->image ?? false)
                                <img src="{{ asset('storage/'.$borrowing->equipment->image) }}"
                                     alt="{{ $borrowing->equipment->name }}" class="item-img">
                            @else
                                <div class="item-img-placeholder">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="1.5">
                                        <rect x="2" y="3" width="20" height="14" rx="2"/>
                                        <path d="M8 21h8M12 17v4"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="item-info">
                            <div class="item-category">
                                @if(strtolower($borrowing->equipment->category ?? '') === 'camera')
                                    KAMERA
                                @elseif(strtolower($borrowing->equipment->category ?? '') === 'lens')
                                    LENSA
                                @elseif(strtolower($borrowing->equipment->category ?? '') === 'tripod')
                                    TRIPOD
                                @elseif(strtolower($borrowing->equipment->category ?? '') === 'lighting')
                                    LAMPU
                                @else
                                    {{ strtoupper($borrowing->equipment->category ?? 'Alat') }}
                                @endif
                            </div>
                            <div class="item-name">{{ $borrowing->equipment->name }}</div>
                            <div class="item-meta">
                                <span class="meta-chip">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                                        <line x1="16" y1="2" x2="16" y2="6"/>
                                        <line x1="8" y1="2" x2="8" y2="6"/>
                                        <line x1="3" y1="10" x2="21" y2="10"/>
                                    </svg>
                                    Dipinjam: {{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d M Y') }}
                                </span>
                                <span class="meta-chip {{ $isOverdue ? 'chip-red' : 'chip-orange' }}">
                                    @if($isOverdue)
                                        ⚠️ Telat {{ abs($daysLeft) }} Hari
                                    @elseif($daysLeft !== null)
                                        Tenggat: {{ \Carbon\Carbon::parse($borrowing->return_date)->format('d M Y') }}
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="item-action">
                            <a href="{{ route('member.returns.create', $borrowing->id) }}"
                               class="btn-return">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2">
                                    <polyline points="1 4 1 10 7 10"/>
                                    <path d="M3.51 15a9 9 0 1 0 .49-3.45"/>
                                </svg>
                                Kembalikan Alat
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ======= SECTION 2: Pengembalian yang Sedang Diproses ======= --}}
    @if($pendingReturns->isNotEmpty())
    <div class="section-card">
        <div class="section-header">
            <div class="section-title-wrap">
                <div class="dot dot-yellow"></div>
                <h2 class="section-title">Menunggu Konfirmasi</h2>
            </div>
            <span class="badge-count badge-yellow">{{ $pendingReturns->count() }} proses</span>
        </div>
        <div class="items-list">
            @foreach($pendingReturns as $return)
            <div class="item-row item-pending">
                <div class="item-img-wrap">
                    @if($return->borrowing->equipment->image ?? false)
                        <img src="{{ asset('storage/'.$return->borrowing->equipment->image) }}"
                             alt="{{ $return->borrowing->equipment->name }}" class="item-img">
                    @else
                        <div class="item-img-placeholder">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.5">
                                <rect x="2" y="3" width="20" height="14" rx="2"/>
                                <path d="M8 21h8M12 17v4"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="item-info">
                    <div class="item-category">
                        @if(strtolower($return->borrowing->equipment->category ?? '') === 'camera')
                            KAMERA
                        @elseif(strtolower($return->borrowing->equipment->category ?? '') === 'lens')
                            LENSA
                        @elseif(strtolower($return->borrowing->equipment->category ?? '') === 'tripod')
                            TRIPOD
                        @elseif(strtolower($return->borrowing->equipment->category ?? '') === 'lighting')
                            LAMPU
                        @else
                            {{ strtoupper($return->borrowing->equipment->category ?? 'Alat') }}
                        @endif
                    </div>
                    <div class="item-name">{{ $return->borrowing->equipment->name }}</div>
                    <div class="item-meta">
                        <span class="meta-chip">
                             Dikembalikan: {{ $return->returned_at->format('d M Y, H:i') }} WIB
                        </span>
                    </div>
                    @if($return->condition_notes)
                        <div class="condition-note">
                            <span>📝</span> {{ Str::limit($return->condition_notes, 80) }}
                        </div>
                    @endif
                </div>
                <div class="item-action">
                    <span class="status-pill pill-pending">
                        <span class="pulse-dot"></span>
                        Menunggu
                    </span>
                    <a href="{{ route('member.returns.show', $return->id) }}" class="btn-detail">
                        Lihat Detail
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ======= SECTION 3: Riwayat Pengembalian ======= --}}
    <div class="section-card">
        <div class="section-header">
            <div class="section-title-wrap">
                <div class="dot dot-gray"></div>
                <h2 class="section-title">Riwayat Pengembalian</h2>
            </div>
        </div>

        @if($returnHistory->isEmpty())
            <div class="empty-state">
                <div class="empty-icon-wrapper">
                    <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="empty-text">Belum ada riwayat pengembalian.</p>
            </div>
        @else
            <div class="items-list">
                @foreach($returnHistory as $return)
                <div class="item-row">
                    <div class="item-img-wrap">
                        <div class="item-img-placeholder">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.5">
                                <rect x="2" y="3" width="20" height="14" rx="2"/>
                                <path d="M8 21h8M12 17v4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="item-info">
                        <div class="item-name">{{ $return->borrowing->equipment->name }}</div>
                        <div class="item-meta">
                            <span class="meta-chip">
                                Dikembalikan: {{ $return->returned_at->format('d M Y') }}
                            </span>
                            @if($return->confirmed_at)
                            <span class="meta-chip">
                                Dikonfirmasi: {{ $return->confirmed_at->format('d M Y') }}
                            </span>
                            @endif
                        </div>
                        @if($return->admin_notes)
                            <div class="condition-note">
                                <span>💬 Catatan Admin:</span> {{ $return->admin_notes }}
                            </div>
                        @endif
                    </div>
                    <div class="item-action">
                        @if($return->status === 'confirmed')
                            <span class="status-pill pill-success">✓ Disetujui</span>
                        @else
                            <span class="status-pill pill-rejected">✕ Ditolak</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <div class="pagination-wrap">
                {{ $returnHistory->links() }}
            </div>
        @endif
    </div>

</div>

<style>
/* ===== GLOBAL RETURNS PAGE ===== */
.returns-page {
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    margin: 0;         
    padding: 24px 32px 60px !important; /* Disamakan dengan padding dasar layout sidebar agar simetris */
    font-family: 'Plus Jakarta Sans', sans-serif;
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.page-header {
    margin-bottom: 4px;
    padding: 0;
}

.page-title {
    font-size: 22px;
    font-weight: 800;
    color: #111827;
    margin: 0 0 6px;
    letter-spacing: -.3px;
}

.page-subtitle {
    color: #6b7280;
    font-size: 14px;
    margin: 0;
}

/* ===== ALERTS ===== */
.alert {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 18px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 500;
}

.alert-success {
    background: #f0fdf4;
    color: #15803d;
    border: 1px solid #bbf7d0;
}

.alert-error {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

/* ===== SECTION CARD ===== */
.section-card {
    width: 100%;
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e5e7eb;
    box-sizing: border-box;
    overflow: hidden;
}

.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid #f3f4f6;
}

.section-title-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
}

.dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
}

.dot-green  { background: #22c55e; box-shadow: 0 0 0 3px #dcfce7; }
.dot-yellow { background: #eab308; box-shadow: 0 0 0 3px #fef9c3; }
.dot-gray   { background: #94a3b8; box-shadow: 0 0 0 3px #f1f5f9; }

.section-title {
    font-size: 15px;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.badge-count {
    background: #f3f4f6;
    color: #4b5563;
    font-size: 12px;
    font-weight: 600;
    padding: 2px 10px;
    border-radius: 20px;
}

.badge-yellow {
    background: #fef9c3;
    color: #a16207;
}

/* ===== EMPTY STATE ===== */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 50px 20px;
    text-align: center;
}

.empty-icon-wrapper {
    width: 72px;
    height: 72px;
    background: #f9fafb;
    border: 1px solid #f3f4f6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    margin-bottom: 14px;
}

.empty-text {
    color: #888888;
    font-size: 13px;
    max-width: 360px;
    margin: 0;
    line-height: 1.5;
}

/* ===== ITEM ROW ===== */
.items-list { padding: 0; }

.item-row {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    border-bottom: 1px solid #f3f4f6;
    transition: background 0.15s;
    box-sizing: border-box;
}

.item-row:last-child { border-bottom: none; }
.item-row:hover { background: #f9fafb; }
.item-overdue { background: #fff8f8; }
.item-pending { background: #fffdf5; }

.item-img-wrap { flex-shrink: 0; }

.item-img {
    width: 52px;
    height: 52px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
}

.item-img-placeholder {
    width: 52px;
    height: 52px;
    background: #f3f4f6;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    border: 1px solid #e5e7eb;
}

.item-info { flex: 1; min-width: 0; }

.item-category {
    font-size: 11px;
    font-weight: 700;
    color: #059669;
    background: #f0fdf4;
    display: inline-block;
    padding: 2px 8px;
    border-radius: 6px;
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.item-name {
    font-size: 15px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.item-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.meta-chip {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    color: #6b7280;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    padding: 2px 8px;
    border-radius: 6px;
}

.chip-red    { background: #fef2f2; color: #ef4444; border-color: #fecaca; font-weight: 600; }
.chip-orange { background: #fff7ed; color: #f97316; border-color: #ffedd5; }

.condition-note {
    font-size: 12px;
    color: #4b5563;
    margin-top: 6px;
    background: #f9fafb;
    padding: 6px 10px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.item-action {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 8px;
    flex-shrink: 0;
}

/* ===== BUTTONS ===== */
.btn-return {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #059669;
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 8px;
    text-decoration: none;
    transition: background 0.2s;
    white-space: nowrap;
}

.btn-return:hover {
    background: #047857;
    color: #fff;
    text-decoration: none;
}

.btn-detail {
    font-size: 13px;
    color: #059669;
    text-decoration: none;
    font-weight: 600;
    padding: 4px 0;
}

.btn-detail:hover { text-decoration: underline; }

/* ===== STATUS PILLS ===== */
.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 12px;
}

.pill-pending  { background: #fef9c3; color: #a16207; }
.pill-success  { background: #f0fdf4; color: #15803d; }
.pill-rejected { background: #fef2f2; color: #dc2626; }

.pulse-dot {
    width: 6px;
    height: 6px;
    background: #eab308;
    border-radius: 50%;
    animation: pulse 1.5s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50%       { transform: scale(1.4); opacity: 0.6; }
}

.pagination-wrap {
    padding: 20px 24px;
    border-top: 1px solid #f3f4f6;
}

/* Responsive */
@media (max-width: 600px) {
    .returns-page { padding: 16px 16px 40px !important; }
    .item-row    { flex-wrap: wrap; }
    .item-action { width: 100%; flex-direction: row; justify-content: flex-end; }
}
</style>
@endsection