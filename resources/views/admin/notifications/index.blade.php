{{-- resources/views/admin/notifications/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Notifikasi')

@push('styles')
<style>
/* ═══════════════════════════════════════════
   NOTIFICATION PAGE — UKM Finic Admin
   ═══════════════════════════════════════════ */

.notifpage-wrap {
    max-width: 760px;
    margin: 0 auto;
    padding: 0 0 48px;
}

/* ── Header ── */
.notifpage-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 12px;
}
.notifpage-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
}
.notifpage-icon {
    width: 44px; height: 44px;
    background: linear-gradient(135deg, #22c55e, #16a34a);
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 12px rgba(34,197,94,0.3);
}
.notifpage-title {
    font-size: 1.5rem;
    font-weight: 800;
    color: #111827;
    letter-spacing: -0.3px;
}
.notifpage-subtitle {
    font-size: 0.8rem;
    color: #6b7280;
    margin-top: 2px;
}

/* Header Actions */
.notifpage-actions {
    display: flex;
    gap: 8px;
    align-items: center;
}
.np-btn {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 0.78rem; font-weight: 600;
    padding: 8px 14px; border-radius: 10px;
    border: none; cursor: pointer;
    transition: all 0.15s;
    text-decoration: none;
}
.np-btn-green {
    background: #f0fdf4; color: #16a34a;
    border: 1px solid #bbf7d0;
}
.np-btn-green:hover { background: #dcfce7; }
.np-btn-red {
    background: #fef2f2; color: #dc2626;
    border: 1px solid #fecaca;
}
.np-btn-red:hover { background: #fee2e2; }

/* ── Filter Tabs ── */
.notifpage-tabs {
    display: flex;
    gap: 6px;
    margin-bottom: 20px;
    background: #f9fafb;
    padding: 5px;
    border-radius: 14px;
    border: 1px solid #f0f0f0;
}
.np-tab {
    flex: 1; text-align: center;
    padding: 8px 14px;
    border-radius: 10px;
    font-size: 0.8rem; font-weight: 600;
    color: #94a3b8;
    background: transparent;
    border: none; cursor: pointer;
    transition: all 0.18s;
}
.np-tab.active {
    background: #fff;
    color: #16a34a;
    box-shadow: 0 1px 6px rgba(0,0,0,0.08);
}

/* ── List ── */
.notifpage-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.np-item {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    background: #fff;
    border: 1px solid #f0f0f0;
    border-radius: 16px;
    padding: 16px 18px;
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
    text-align: left;
    width: 100%;
    box-sizing: border-box;
}
.np-item:hover {
    border-color: #bbf7d0;
    box-shadow: 0 4px 20px rgba(34,197,94,0.08);
    transform: translateY(-1px);
}
.np-item.unread {
    background: #f0fdf4;
    border-color: #d1fae5;
}
.np-item.unread::before {
    content: '';
    position: absolute;
    left: 0; top: 50%;
    transform: translateY(-50%);
    width: 4px; height: 60%;
    background: #22c55e;
    border-radius: 0 4px 4px 0;
}

/* Icon */
.np-item-icon {
    width: 42px; height: 42px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.np-icon-borrow_request   { background: #dbeafe; }
.np-icon-return_submitted { background: #fef3c7; }
.np-icon-borrow_approved  { background: #d1fae5; }
.np-icon-borrow_rejected  { background: #fee2e2; }
.np-icon-return_confirmed { background: #d1fae5; }
.np-icon-return_rejected  { background: #fee2e2; }
.np-icon-info             { background: #f3f4f6; }

/* Content */
.np-item-body { flex: 1; min-width: 0; }
.np-item-title {
    font-size: 0.88rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 3px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.np-item-msg {
    font-size: 0.82rem;
    color: #4b5563;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.np-item-time {
    font-size: 0.74rem;
    color: #9ca3af;
    margin-top: 5px;
    display: flex;
    align-items: center;
    gap: 4px;
}
.np-unread-dot {
    width: 6px; height: 6px;
    background: #22c55e;
    border-radius: 50%;
    flex-shrink: 0;
}

/* Delete btn */
.np-item-del {
    width: 30px; height: 30px;
    border-radius: 8px;
    border: none; background: transparent;
    color: #d1d5db;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; flex-shrink: 0;
    transition: all 0.15s;
    align-self: center;
}
.np-item-del:hover { background: #fee2e2; color: #dc2626; }

/* ── Empty ── */
.np-empty {
    text-align: center;
    padding: 64px 24px;
    color: #9ca3af;
}
.np-empty-icon {
    font-size: 3rem;
    margin-bottom: 12px;
    opacity: 0.5;
}
.np-empty-text {
    font-size: 0.95rem;
    font-weight: 600;
    color: #6b7280;
    margin-bottom: 4px;
}
.np-empty-sub { font-size: 0.82rem; }

/* ── Detail Modal ── */
.nd-overlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,0.45);
    backdrop-filter: blur(4px);
    z-index: 9999;
    display: flex; align-items: center; justify-content: center;
    padding: 20px;
    opacity: 0; pointer-events: none;
    transition: opacity 0.22s;
}
.nd-overlay.open {
    opacity: 1; pointer-events: auto;
}
.nd-modal {
    background: #fff;
    border-radius: 22px;
    width: 100%; max-width: 560px;
    max-height: 85vh;
    overflow-y: auto;
    box-shadow: 0 24px 80px rgba(0,0,0,0.2);
    transform: scale(0.94) translateY(16px);
    transition: transform 0.25s cubic-bezier(0.34,1.56,0.64,1);
}
.nd-overlay.open .nd-modal {
    transform: scale(1) translateY(0);
}

/* Modal Header */
.nd-modal-header {
    display: flex; align-items: flex-start; gap: 14px;
    padding: 24px 24px 18px;
    border-bottom: 1px solid #f0f0f0;
    position: sticky; top: 0;
    background: #fff; z-index: 1;
    border-radius: 22px 22px 0 0;
}
.nd-modal-icon {
    width: 46px; height: 46px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem;
    flex-shrink: 0;
}
.nd-modal-title-wrap { flex: 1; }
.nd-modal-title {
    font-size: 1rem; font-weight: 800; color: #111827;
    line-height: 1.3;
}
.nd-modal-time {
    font-size: 0.76rem; color: #9ca3af; margin-top: 3px;
}
.nd-close {
    width: 32px; height: 32px;
    border-radius: 8px; border: none;
    background: #f3f4f6; color: #6b7280;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; flex-shrink: 0;
    transition: all 0.15s;
}
.nd-close:hover { background: #fee2e2; color: #dc2626; }

/* Modal Body */
.nd-modal-body { padding: 20px 24px 24px; }

.nd-message-box {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-left: 4px solid #22c55e;
    border-radius: 10px;
    padding: 14px 16px;
    font-size: 0.88rem; line-height: 1.7;
    color: #374151;
    margin-bottom: 20px;
}

.nd-section-label {
    font-size: 0.72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.6px;
    color: #9ca3af; margin-bottom: 10px;
}

.nd-info-grid {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 10px; margin-bottom: 20px;
}
.nd-info-card {
    background: #f9fafb;
    border: 1px solid #f0f0f0;
    border-radius: 12px;
    padding: 12px 14px;
}
.nd-info-card-label {
    font-size: 0.72rem; color: #9ca3af;
    font-weight: 600; margin-bottom: 4px;
}
.nd-info-card-value {
    font-size: 0.86rem; font-weight: 700; color: #111827;
}
.nd-info-card-sub {
    font-size: 0.74rem; color: #6b7280; margin-top: 2px;
}

.nd-purpose-box {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-left: 4px solid #22c55e;
    border-radius: 10px;
    padding: 14px 16px;
    font-size: 0.86rem; color: #1a2e1a;
    line-height: 1.7; white-space: pre-line;
    margin-bottom: 20px;
}

.nd-full-link {
    display: inline-flex; align-items: center; gap: 6px;
    margin-top: 4px;
    font-size: 0.82rem; font-weight: 700;
    color: #16a34a;
    text-decoration: none;
    padding: 10px 16px;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 10px;
    transition: all 0.15s;
    width: 100%; justify-content: center;
    box-sizing: border-box;
}
.nd-full-link:hover { background: #dcfce7; }

/* Status badges */
.nd-badge {
    display: inline-block; font-size: 0.75rem; font-weight: 700;
    padding: 3px 10px; border-radius: 20px;
}
.nd-badge-pending   { background: #fef3c7; color: #92400e; }
.nd-badge-approved  { background: #d1fae5; color: #065f46; }
.nd-badge-rejected  { background: #fee2e2; color: #991b1b; }
.nd-badge-confirmed { background: #dbeafe; color: #1e40af; }
.nd-badge-returned  { background: #f3e8ff; color: #6b21a8; }

/* Loading */
.nd-loading {
    display: flex; align-items: center; justify-content: center;
    gap: 10px; padding: 48px 24px;
    color: #9ca3af; font-size: 0.86rem;
}
.nd-spinner {
    width: 22px; height: 22px;
    border: 2px solid #e5e7eb;
    border-top-color: #22c55e;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* Toast */
#ndToast {
    position: fixed; bottom: 24px; right: 24px;
    background: #111827; color: #fff;
    padding: 12px 18px; border-radius: 12px;
    font-size: 0.82rem; font-weight: 600;
    box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    z-index: 99999;
    opacity: 0; transform: translateY(10px);
    transition: all 0.25s;
    pointer-events: none;
}
#ndToast.show { opacity: 1; transform: translateY(0); }

/* Pagination */
.np-pagination {
    margin-top: 24px;
    display: flex;
    justify-content: center;
}
.np-pagination .pagination {
    display: flex; gap: 4px; align-items: center;
}
.np-pagination .page-item .page-link {
    padding: 8px 13px; border-radius: 10px;
    font-size: 0.8rem; font-weight: 600;
    border: 1px solid #e5e7eb;
    color: #374151; text-decoration: none;
    transition: all 0.15s;
}
.np-pagination .page-item.active .page-link {
    background: #16a34a; color: #fff; border-color: #16a34a;
}
.np-pagination .page-item .page-link:hover {
    background: #f0fdf4; border-color: #bbf7d0; color: #16a34a;
}

@media (max-width: 600px) {
    .notifpage-header { flex-direction: column; align-items: flex-start; }
    .nd-info-grid { grid-template-columns: 1fr; }
    .np-tab { font-size: 0.75rem; padding: 7px 10px; }
}
</style>
@endpush

@section('content')
<div class="notifpage-wrap">

    {{-- Header --}}
    <div class="notifpage-header">
        <div class="notifpage-title-wrap">
            <div class="notifpage-icon">
                <svg width="22" height="22" fill="none" stroke="#fff" stroke-width="2.2"
                     viewBox="0 0 24 24">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
            </div>
            <div>
                <div class="notifpage-title">Notifikasi</div>
                <div class="notifpage-subtitle">
                    {{ $notifications->total() }} notifikasi total
                    @php $unread = $notifications->getCollection()->where('read_at', null)->count(); @endphp
                    @if($notifications->currentPage() === 1 && $unread > 0)
                        · Semua sudah ditandai dibaca
                    @endif
                </div>
            </div>
        </div>

        <div class="notifpage-actions">
            <button class="np-btn np-btn-green" onclick="clearRead()">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24"><polyline points="3,6 5,6 21,6"/><path d="M19 6l-1 14H6L5 6"/>
                    <path d="M10 11v6M14 11v6"/></svg>
                Hapus sudah dibaca
            </button>
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="notifpage-tabs">
        <button class="np-tab active" id="tab-all" onclick="filterTab('all', this)">Semua</button>
        <button class="np-tab" id="tab-borrow" onclick="filterTab('borrow', this)">Peminjaman</button>
        <button class="np-tab" id="tab-return" onclick="filterTab('return', this)">Pengembalian</button>
    </div>

    {{-- List --}}
    <div class="notifpage-list" id="notifList">
        @forelse($notifications as $notif)
            @php
                $data = $notif->data;
                $type = $data['type'] ?? 'info';
                $isUnread = is_null($notif->read_at);
                $icons = [
                    'borrow_request'   => '📋',
                    'return_submitted' => '📦',
                    'borrow_approved'  => '✅',
                    'borrow_rejected'  => '❌',
                    'return_confirmed' => '✅',
                    'return_rejected'  => '❌',
                ];
                $icon = $icons[$type] ?? '🔔';
            @endphp

            <button
                class="np-item {{ $isUnread ? 'unread' : '' }}"
                id="item-{{ $notif->id }}"
                data-id="{{ $notif->id }}"
                data-type="{{ $type }}"
                onclick="openDetail('{{ $notif->id }}')"
            >
                <div class="np-item-icon np-icon-{{ $type }}">{{ $icon }}</div>
                <div class="np-item-body">
                    <div class="np-item-title">{{ $data['title'] ?? 'Notifikasi' }}</div>
                    <div class="np-item-msg">{{ $data['message'] ?? '' }}</div>
                    <div class="np-item-time">
                        @if($isUnread)<span class="np-unread-dot"></span>@endif
                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg>
                        {{ $notif->created_at->diffForHumans() }}
                    </div>
                </div>
                <button class="np-item-del" onclick="event.stopPropagation(); deleteNotif('{{ $notif->id }}')" title="Hapus">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24"><polyline points="3,6 5,6 21,6"/><path d="M19 6l-1 14H6L5 6"/>
                        <path d="M10 11v6M14 11v6"/></svg>
                </button>
            </button>
        @empty
            <div class="np-empty">
                <div class="np-empty-icon">🔔</div>
                <div class="np-empty-text">Belum ada notifikasi</div>
                <div class="np-empty-sub">Aktivitas terbaru akan muncul di sini</div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($notifications->hasPages())
        <div class="np-pagination">
            {{ $notifications->links() }}
        </div>
    @endif

</div>

{{-- ══ DETAIL MODAL ══ --}}
<div class="nd-overlay" id="ndOverlay" onclick="closeModal()">
    <div class="nd-modal" onclick="event.stopPropagation()">

        <div class="nd-modal-header">
            <div class="nd-modal-icon" id="ndIcon">🔔</div>
            <div class="nd-modal-title-wrap">
                <div class="nd-modal-title" id="ndTitle">Detail Notifikasi</div>
                <div class="nd-modal-time" id="ndTime"></div>
            </div>
            <button class="nd-close" onclick="closeModal()">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"
                     viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <div id="ndLoading" class="nd-loading">
            <div class="nd-spinner"></div>
            <span>Memuat detail…</span>
        </div>

        <div id="ndContent" class="nd-modal-body" style="display:none;">
            <div class="nd-message-box" id="ndMessage"></div>

            <div id="ndInfoWrap" style="display:none;">
                <div class="nd-section-label" id="ndInfoLabel">Informasi</div>
                <div class="nd-info-grid" id="ndInfoGrid"></div>
            </div>

            <div id="ndPurposeWrap" style="display:none;">
                <div class="nd-section-label">Tujuan Peminjaman</div>
                <div class="nd-purpose-box" id="ndPurpose"></div>
            </div>

            <div id="ndAdminNoteWrap" style="display:none;">
                <div class="nd-section-label">Catatan Admin</div>
                <div class="nd-purpose-box" style="border-color:#fecaca;border-left-color:#ef4444;background:#fef2f2;"
                     id="ndAdminNote"></div>
            </div>

            <a id="ndFullLink" href="#" class="nd-full-link" style="display:none;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/>
                    <polyline points="15,3 21,3 21,9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                Lihat halaman detail lengkap
            </a>
        </div>

    </div>
</div>

<div id="ndToast"></div>
@endsection

@push('scripts')
<script>
(function () {
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

    /* ── Icon map ── */
    const ICONS = {
        borrow_request   : '📋',
        return_submitted : '📦',
        borrow_approved  : '✅',
        borrow_rejected  : '❌',
        return_confirmed : '✅',
        return_rejected  : '❌',
    };

    const ICON_BG = {
        borrow_request   : '#dbeafe',
        return_submitted : '#fef3c7',
        borrow_approved  : '#d1fae5',
        borrow_rejected  : '#fee2e2',
        return_confirmed : '#d1fae5',
        return_rejected  : '#fee2e2',
    };

    /* ── Filter Tabs ── */
    window.filterTab = function (filter, btn) {
        document.querySelectorAll('.np-tab').forEach(t => t.classList.remove('active'));
        btn.classList.add('active');

        document.querySelectorAll('.np-item').forEach(item => {
            const type = item.dataset.type || '';
            let show = true;
            if (filter === 'borrow')  show = type.startsWith('borrow');
            if (filter === 'return')  show = type.startsWith('return');
            item.style.display = show ? '' : 'none';
        });

        // Tampilkan empty state jika semua hidden
        const list = document.getElementById('notifList');
        const visible = [...list.querySelectorAll('.np-item')].filter(el => el.style.display !== 'none');
        let emptyEl = list.querySelector('.np-empty-dynamic');
        if (visible.length === 0) {
            if (!emptyEl) {
                emptyEl = document.createElement('div');
                emptyEl.className = 'np-empty np-empty-dynamic';
                emptyEl.innerHTML = '<div class="np-empty-icon">🔍</div><div class="np-empty-text">Tidak ada notifikasi di kategori ini</div>';
                list.appendChild(emptyEl);
            }
        } else {
            emptyEl?.remove();
        }
    };

    /* ── Open Detail Modal ── */
    window.openDetail = function (id) {
        const overlay = document.getElementById('ndOverlay');
        document.getElementById('ndLoading').style.display = 'flex';
        document.getElementById('ndContent').style.display = 'none';
        document.getElementById('ndTitle').textContent = 'Detail Notifikasi';
        document.getElementById('ndTime').textContent = '';
        overlay.classList.add('open');
        document.body.style.overflow = 'hidden';

        // Tandai item sebagai sudah dibaca di UI
        const item = document.getElementById('item-' + id);
        if (item) {
            item.classList.remove('unread');
            item.querySelector('.np-unread-dot')?.remove();
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
            // Fallback: tampilkan pesan dari DOM
            const msgEl = item?.querySelector('.np-item-msg');
            const titleEl = item?.querySelector('.np-item-title');
            renderFallback(titleEl?.textContent || 'Notifikasi', msgEl?.textContent || '');
        });
    };

    function renderDetail(d) {
        // Header
        const type = d.type || 'info';
        const icon = document.getElementById('ndIcon');
        icon.textContent = ICONS[type] || '🔔';
        icon.style.background = ICON_BG[type] || '#f3f4f6';
        document.getElementById('ndTitle').textContent = d.title || 'Notifikasi';
        document.getElementById('ndTime').textContent = d.created_at || '';

        // Message
        document.getElementById('ndMessage').textContent = d.message || '';

        const infoWrap = document.getElementById('ndInfoWrap');
        const infoGrid = document.getElementById('ndInfoGrid');
        const purposeWrap = document.getElementById('ndPurposeWrap');
        const adminNoteWrap = document.getElementById('ndAdminNoteWrap');
        const fullLink = document.getElementById('ndFullLink');

        // Reset semua section
        infoWrap.style.display = 'none';
        purposeWrap.style.display = 'none';
        adminNoteWrap.style.display = 'none';
        fullLink.style.display = 'none';
        // Reset label purpose ke default
        const purposeLabel = purposeWrap.querySelector('.nd-section-label');
        if (purposeLabel) purposeLabel.textContent = 'Tujuan Peminjaman';

        // Borrowing detail
        if (d.borrowing) {
            const b = d.borrowing;
            document.getElementById('ndInfoLabel').textContent = 'Informasi Peminjaman';
            infoGrid.innerHTML = `
                <div class="nd-info-card">
                    <div class="nd-info-card-label">👤 Member</div>
                    <div class="nd-info-card-value">${esc(b.user_name)}</div>
                    <div class="nd-info-card-sub">${esc(b.user_email || '')}${b.user_student_id && b.user_student_id !== '-' ? ' · ' + esc(b.user_student_id) : ''}</div>
                </div>
                <div class="nd-info-card">
                    <div class="nd-info-card-label">📋 Status</div>
                    <div class="nd-info-card-value">${statusBadge(b.status)}</div>
                    <div class="nd-info-card-sub">${esc(b.transaction_code || '')}</div>
                </div>
                <div class="nd-info-card">
                    <div class="nd-info-card-label">📷 Alat</div>
                    <div class="nd-info-card-value">${esc(b.equipment_name)}</div>
                    <div class="nd-info-card-sub">${esc(b.equipment_category || '')}${b.equipment_condition ? ' · ' + esc(b.equipment_condition) : ''}</div>
                </div>
                <div class="nd-info-card">
                    <div class="nd-info-card-label">⏱ Durasi</div>
                    <div class="nd-info-card-value">${b.duration_days || '-'} Hari</div>
                    <div class="nd-info-card-sub">Diajukan ${esc(b.created_at || '')}</div>
                </div>
                <div class="nd-info-card">
                    <div class="nd-info-card-label">📅 Tgl Pinjam</div>
                    <div class="nd-info-card-value">${b.borrow_date || '-'}</div>
                </div>
                <div class="nd-info-card">
                    <div class="nd-info-card-label">📅 Rencana Kembali</div>
                    <div class="nd-info-card-value">${b.return_date || '-'}</div>
                </div>
            `;
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

        // Return detail
        if (d.return) {
            const r = d.return;
            document.getElementById('ndInfoLabel').textContent = 'Informasi Pengembalian';
            infoGrid.innerHTML = `
                <div class="nd-info-card">
                    <div class="nd-info-card-label">👤 Member</div>
                    <div class="nd-info-card-value">${esc(r.user_name)}</div>
                    <div class="nd-info-card-sub">${esc(r.user_email || '')}</div>
                </div>
                <div class="nd-info-card">
                    <div class="nd-info-card-label">📋 Status</div>
                    <div class="nd-info-card-value">${statusBadge(r.status)}</div>
                    <div class="nd-info-card-sub">${esc(r.transaction_code || '')}</div>
                </div>
                <div class="nd-info-card">
                    <div class="nd-info-card-label">📷 Alat</div>
                    <div class="nd-info-card-value">${esc(r.equipment_name)}</div>
                    <div class="nd-info-card-sub">${esc(r.equipment_category || '')}</div>
                </div>
                <div class="nd-info-card">
                    <div class="nd-info-card-label">📅 Dikembalikan</div>
                    <div class="nd-info-card-value">${esc(r.returned_at || '-')}</div>
                    ${r.confirmed_at ? `<div class="nd-info-card-sub">Dikonfirmasi: ${esc(r.confirmed_at)}</div>` : ''}
                </div>
            `;
            infoWrap.style.display = 'block';

            // Kondisi alat saat dikembalikan
            if (r.condition_notes) {
                document.getElementById('ndPurpose').textContent = r.condition_notes;
                const purposeLabel = purposeWrap.querySelector('.nd-section-label');
                if (purposeLabel) purposeLabel.textContent = 'Catatan Kondisi Alat';
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

    /* ── Delete ── */
    window.deleteNotif = function (id) {
        if (!confirm('Hapus notifikasi ini?')) return;
        fetch(`/admin/notifications/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(r => r.json())
        .then(() => {
            const el = document.getElementById('item-' + id);
            if (el) {
                el.style.transition = 'opacity 0.25s, transform 0.25s';
                el.style.opacity = '0';
                el.style.transform = 'translateX(20px)';
                setTimeout(() => el.remove(), 260);
            }
            showToast('Notifikasi dihapus');
        })
        .catch(() => showToast('Gagal menghapus'));
    };

    /* ── Clear Read ── */
    window.clearRead = function () {
        if (!confirm('Hapus semua notifikasi yang sudah dibaca?')) return;
        fetch('/admin/notifications/clear-read', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(r => r.json())
        .then(() => {
            document.querySelectorAll('.np-item:not(.unread)').forEach(el => el.remove());
            showToast('Notifikasi sudah dibaca dihapus');
        })
        .catch(() => showToast('Gagal menghapus'));
    };

    /* ── Helpers ── */
    function statusBadge(status) {
        const map = {
            pending   : ['nd-badge-pending',   '⏳ Menunggu'],
            approved  : ['nd-badge-approved',  '✅ Disetujui'],
            rejected  : ['nd-badge-rejected',  '❌ Ditolak'],
            confirmed : ['nd-badge-confirmed', '📋 Dikonfirmasi'],
            returned  : ['nd-badge-returned',  '📦 Dikembalikan'],
        };
        const [cls, label] = map[status] || ['nd-badge-pending', status || '-'];
        return `<span class="nd-badge ${cls}">${label}</span>`;
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

    /* ── ESC close modal ── */
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeModal();
    });
}());
</script>
@endpush