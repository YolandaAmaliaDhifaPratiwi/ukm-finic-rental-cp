{{-- resources/views/admin/returns/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manajemen Pengembalian')

@section('content')
<div class="admin-returns-page">

    {{-- Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Manajemen Pengembalian</h1>
            <p class="page-subtitle">Monitor dan konfirmasi pengembalian alat dari member</p>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22,4 12,14.01 9,11.01"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Stats Row --}}
    <div class="stats-row">
        <div class="stat-card stat-pending">
            <div class="stat-icon">⏳</div>
            <div class="stat-body">
                <div class="stat-num">{{ $counts['pending'] }}</div>
                <div class="stat-label">Menunggu Konfirmasi</div>
            </div>
        </div>
        <div class="stat-card stat-confirmed">
            <div class="stat-icon">✅</div>
            <div class="stat-body">
                <div class="stat-num">{{ $counts['confirmed'] }}</div>
                <div class="stat-label">Dikonfirmasi</div>
            </div>
        </div>
        <div class="stat-card stat-rejected">
            <div class="stat-icon">❌</div>
            <div class="stat-body">
                <div class="stat-num">{{ $counts['rejected'] }}</div>
                <div class="stat-label">Ditolak</div>
            </div>
        </div>
        <div class="stat-card stat-total">
            <div class="stat-icon">📋</div>
            <div class="stat-body">
                <div class="stat-num">{{ array_sum($counts) }}</div>
                <div class="stat-label">Total Pengajuan</div>
            </div>
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="filter-tabs">
        <a href="{{ route('admin.returns.index', ['status' => 'pending']) }}"
           class="tab {{ $status === 'pending' ? 'tab-active' : '' }}">
            Menunggu
            @if($counts['pending'] > 0)
                <span class="tab-badge">{{ $counts['pending'] }}</span>
            @endif
        </a>
        <a href="{{ route('admin.returns.index', ['status' => 'confirmed']) }}"
           class="tab {{ $status === 'confirmed' ? 'tab-active' : '' }}">
            Dikonfirmasi
        </a>
        <a href="{{ route('admin.returns.index', ['status' => 'rejected']) }}"
           class="tab {{ $status === 'rejected' ? 'tab-active' : '' }}">
            Ditolak
        </a>
        <a href="{{ route('admin.returns.index', ['status' => 'all']) }}"
           class="tab {{ $status === 'all' ? 'tab-active' : '' }}">
            Semua
        </a>
    </div>

    {{-- Table --}}
    <div class="table-card">
        @if($returns->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <p class="empty-title">Tidak ada data pengembalian</p>
                <p class="empty-sub">Pengajuan pengembalian dari member akan muncul di sini</p>
            </div>
        @else
            <div class="table-wrap">
                <table class="returns-table">
                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Alat</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Kondisi</th>
                            <th>Foto</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($returns as $return)
                        <tr class="{{ $return->status === 'pending' ? 'row-pending' : '' }}">
                            {{-- Member --}}
                            <td>
                                <div class="member-cell">
                                    <div class="member-avatar">
                                        {{ strtoupper(substr($return->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="member-name">{{ $return->user->name }}</div>
                                        <div class="member-email">{{ $return->user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Alat --}}
                            <td>
                                <div class="equipment-cell">
                                    <div class="eq-name">{{ $return->borrowing->equipment->name }}</div>
                                    @if($return->borrowing->equipment->category ?? false)
                                        <div class="eq-cat">{{ $return->borrowing->equipment->category }}</div>
                                    @endif
                                </div>
                            </td>

                            {{-- Tanggal --}}
                            <td>
                                <div class="date-cell">
                                    <div class="date-main">{{ $return->returned_at->format('d M Y') }}</div>
                                    <div class="date-time">{{ $return->returned_at->format('H:i') }}</div>
                                </div>
                            </td>

                            {{-- Kondisi --}}
                            <td>
                                <div class="condition-cell">
                                    {{ Str::limit($return->condition_notes, 60) }}
                                </div>
                            </td>

                            {{-- Foto --}}
                            <td>
                                @if($return->photo)
                                    <a href="{{ asset('storage/'.$return->photo) }}" target="_blank"
                                       class="photo-thumb-link">
                                        <img src="{{ asset('storage/'.$return->photo) }}"
                                             alt="Foto kondisi" class="photo-thumb">
                                    </a>
                                @else
                                    <span class="no-photo">—</span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td>
                                @if($return->status === 'pending')
                                    <span class="status-badge badge-pending">
                                        <span class="pulse-dot"></span> Menunggu
                                    </span>
                                @elseif($return->status === 'confirmed')
                                    <span class="status-badge badge-confirmed">✓ Dikonfirmasi</span>
                                @else
                                    <span class="status-badge badge-rejected">✕ Ditolak</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td>
                                <div class="action-btns">
                                    <a href="{{ route('admin.returns.show', $return->id) }}"
                                       class="btn-view" title="Lihat Detail">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                        Detail
                                    </a>

                                    @if($return->status === 'pending')
                                        <button class="btn-confirm"
                                                onclick="openConfirm({{ $return->id }}, '{{ $return->user->name }}', '{{ $return->borrowing->equipment->name }}')">
                                            ✓ Konfirmasi
                                        </button>
                                        <button class="btn-reject"
                                                onclick="openReject({{ $return->id }}, '{{ $return->user->name }}', '{{ $return->borrowing->equipment->name }}')">
                                            ✕ Tolak
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($returns->hasPages())
            <div class="pagination-wrap">
                {{ $returns->appends(['status' => $status])->links() }}
            </div>
            @endif
        @endif
    </div>

</div>

{{-- ===== MODAL KONFIRMASI ===== --}}
<div class="modal-overlay" id="confirmModal" style="display:none;">
    <div class="modal-box">
        <div class="modal-icon modal-icon-green">✓</div>
        <h3 class="modal-title">Konfirmasi Pengembalian</h3>
        <p class="modal-desc" id="confirmDesc"></p>

        <form id="confirmForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="modal-field">
                <label class="modal-label">Catatan Admin (opsional)</label>
                <textarea name="admin_notes" rows="3" class="modal-textarea"
                          placeholder="Kondisi barang setelah dicek, catatan tambahan..."></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="modal-cancel" onclick="closeModals()">Batal</button>
                <button type="submit" class="modal-ok modal-ok-green">✓ Ya, Konfirmasi</button>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL TOLAK ===== --}}
<div class="modal-overlay" id="rejectModal" style="display:none;">
    <div class="modal-box">
        <div class="modal-icon modal-icon-red">✕</div>
        <h3 class="modal-title">Tolak Pengembalian</h3>
        <p class="modal-desc" id="rejectDesc"></p>

        <form id="rejectForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="modal-field">
                <label class="modal-label">Alasan Penolakan <span class="required">*</span></label>
                <textarea name="admin_notes" rows="3" class="modal-textarea"
                          placeholder="Jelaskan alasan penolakan..." required></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="modal-cancel" onclick="closeModals()">Batal</button>
                <button type="submit" class="modal-ok modal-ok-red">✕ Ya, Tolak</button>
            </div>
        </form>
    </div>
</div>

<style>
.admin-returns-page {
    padding: 28px 24px 60px;
    font-family: 'Poppins', sans-serif;
    max-width: 1200px;
}

.page-header { margin-bottom: 24px; }
.page-title  { font-size: 1.5rem; font-weight: 700; color: #1a2e1a; margin: 0 0 4px; }
.page-subtitle { color: #64748b; font-size: 0.88rem; margin: 0; }

.alert {
    display: flex; align-items: center; gap: 10px;
    padding: 14px 18px; border-radius: 12px;
    font-size: 0.88rem; margin-bottom: 20px; font-weight: 500;
}
.alert-success { background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; }

/* Stats */
.stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 24px;
}

@media(max-width:768px){ .stats-row{ grid-template-columns:repeat(2,1fr); } }

.stat-card {
    background: #fff;
    border-radius: 14px;
    padding: 16px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    border: 1px solid #e8f0e8;
    box-shadow: 0 1px 6px rgba(0,0,0,0.04);
}

.stat-icon { font-size: 1.6rem; }
.stat-num  { font-size: 1.6rem; font-weight: 700; line-height: 1; }
.stat-label{ font-size: 0.76rem; color: #64748b; margin-top: 2px; }

.stat-pending   .stat-num { color: #d97706; }
.stat-confirmed .stat-num { color: #15803d; }
.stat-rejected  .stat-num { color: #dc2626; }
.stat-total     .stat-num { color: #1a6b1a; }

/* Filter Tabs */
.filter-tabs {
    display: flex;
    gap: 4px;
    margin-bottom: 18px;
    background: #f0f6f0;
    padding: 5px;
    border-radius: 14px;
    width: fit-content;
}

.tab {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 18px;
    border-radius: 10px;
    font-size: 0.84rem;
    font-weight: 500;
    color: #64748b;
    text-decoration: none;
    transition: all 0.15s;
}

.tab:hover { color: #1a6b1a; text-decoration: none; }

.tab-active {
    background: #fff;
    color: #1a6b1a;
    font-weight: 600;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
}

.tab-badge {
    background: #dc2626;
    color: #fff;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 1px 7px;
    border-radius: 10px;
}

/* Table Card */
.table-card {
    background: #fff;
    border-radius: 18px;
    border: 1px solid #e8f0e8;
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    overflow: hidden;
}

.table-wrap { overflow-x: auto; }

.returns-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.84rem;
}

.returns-table thead tr {
    background: #fafcfa;
    border-bottom: 2px solid #e8f0e8;
}

.returns-table th {
    padding: 14px 16px;
    text-align: left;
    font-size: 0.78rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
}

.returns-table td {
    padding: 14px 16px;
    border-bottom: 1px solid #f5f8f5;
    vertical-align: middle;
}

.returns-table tbody tr:hover { background: #fafcfa; }
.row-pending { background: #fffdf5; }

/* Cells */
.member-cell { display: flex; align-items: center; gap: 10px; }

.member-avatar {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #1a6b1a, #2d9e2d);
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.88rem;
    font-weight: 700;
    flex-shrink: 0;
}

.member-name  { font-weight: 600; color: #1a2e1a; }
.member-email { font-size: 0.76rem; color: #94a3b8; }

.eq-name { font-weight: 600; color: #1a2e1a; }
.eq-cat  { font-size: 0.74rem; color: #22a322; background: #f0fdf0;
           display: inline-block; padding: 1px 8px; border-radius: 8px; margin-top: 2px; }

.date-main { font-weight: 600; color: #1a2e1a; }
.date-time { font-size: 0.76rem; color: #94a3b8; }

.condition-cell { font-size: 0.82rem; color: #374151; max-width: 200px; }

.photo-thumb-link { display: block; }
.photo-thumb {
    width: 48px; height: 48px;
    object-fit: cover;
    border-radius: 8px;
    border: 1.5px solid #e8f0e8;
    transition: transform 0.2s;
}
.photo-thumb:hover { transform: scale(1.08); }
.no-photo { color: #cbd5e1; font-size: 1.2rem; }

/* Status Badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.76rem;
    font-weight: 600;
    padding: 5px 12px;
    border-radius: 20px;
    white-space: nowrap;
}

.badge-pending   { background: #fef9c3; color: #a16207; }
.badge-confirmed { background: #f0fdf4; color: #15803d; }
.badge-rejected  { background: #fef2f2; color: #dc2626; }

.pulse-dot {
    width: 7px; height: 7px;
    background: #eab308;
    border-radius: 50%;
    animation: pulse 1.5s ease-in-out infinite;
}

@keyframes pulse {
    0%,100% { transform:scale(1); opacity:1; }
    50%      { transform:scale(1.4); opacity:0.6; }
}

/* Action Buttons */
.action-btns { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }

.btn-view {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 12px; border-radius: 8px;
    font-size: 0.78rem; font-weight: 500;
    color: #1a6b1a; background: #f0fdf4;
    border: 1px solid #bbf7d0;
    text-decoration: none;
    transition: all 0.15s;
}
.btn-view:hover { background: #dcfce7; text-decoration: none; }

.btn-confirm {
    padding: 6px 12px; border-radius: 8px;
    font-size: 0.78rem; font-weight: 600;
    background: #22a322; color: #fff;
    border: none; cursor: pointer;
    transition: all 0.15s;
}
.btn-confirm:hover { background: #1a6b1a; }

.btn-reject {
    padding: 6px 12px; border-radius: 8px;
    font-size: 0.78rem; font-weight: 600;
    background: #fef2f2; color: #dc2626;
    border: 1px solid #fecaca;
    cursor: pointer; transition: all 0.15s;
}
.btn-reject:hover { background: #dc2626; color: #fff; border-color: #dc2626; }

/* Pagination */
.pagination-wrap { padding: 16px 20px; border-top: 1px solid #f0f6f0; }

/* Empty */
.empty-state { padding: 60px 20px; text-align: center; }
.empty-icon  { font-size: 3rem; margin-bottom: 12px; }
.empty-title { font-size: 1rem; font-weight: 600; color: #374151; margin: 0 0 6px; }
.empty-sub   { font-size: 0.85rem; color: #94a3b8; margin: 0; }

/* ===== MODAL ===== */
.modal-overlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,0.45);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    backdrop-filter: blur(3px);
}

.modal-box {
    background: #fff;
    border-radius: 20px;
    padding: 32px 28px;
    max-width: 440px;
    width: 100%;
    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    text-align: center;
}

.modal-icon {
    width: 60px; height: 60px;
    border-radius: 50%;
    font-size: 1.5rem;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 16px;
    font-weight: 700;
}
.modal-icon-green { background: #f0fdf4; color: #15803d; border: 2px solid #bbf7d0; }
.modal-icon-red   { background: #fef2f2; color: #dc2626; border: 2px solid #fecaca; }

.modal-title { font-size: 1.1rem; font-weight: 700; color: #1a2e1a; margin: 0 0 8px; }
.modal-desc  { font-size: 0.86rem; color: #64748b; margin: 0 0 20px; line-height: 1.5; }

.modal-field { text-align: left; margin-bottom: 20px; }
.modal-label {
    display: block; font-size: 0.84rem;
    font-weight: 600; color: #374151; margin-bottom: 8px;
}
.required { color: #dc2626; }

.modal-textarea {
    width: 100%; padding: 12px 14px;
    border: 1.5px solid #e8f0e8;
    border-radius: 10px; font-family: 'Poppins', sans-serif;
    font-size: 0.84rem; color: #1a2e1a; resize: vertical;
    background: #fafcfa; box-sizing: border-box;
}
.modal-textarea:focus { outline: none; border-color: #22a322; }

.modal-actions { display: flex; gap: 10px; justify-content: flex-end; }

.modal-cancel {
    padding: 10px 20px; border-radius: 10px;
    border: 1.5px solid #e2e8f0; background: #fff;
    color: #64748b; font-size: 0.88rem; font-weight: 500;
    cursor: pointer; transition: all 0.15s;
}
.modal-cancel:hover { border-color: #94a3b8; }

.modal-ok {
    padding: 10px 22px; border-radius: 10px;
    border: none; font-size: 0.88rem; font-weight: 700;
    cursor: pointer; color: #fff; transition: all 0.2s;
}
.modal-ok-green { background: linear-gradient(135deg, #1a6b1a, #2d9e2d); }
.modal-ok-green:hover { box-shadow: 0 4px 12px rgba(26,107,26,0.35); }
.modal-ok-red   { background: linear-gradient(135deg, #b91c1c, #dc2626); }
.modal-ok-red:hover   { box-shadow: 0 4px 12px rgba(220,38,38,0.35); }
</style>

<script>
function openConfirm(id, member, equipment) {
    document.getElementById('confirmDesc').textContent =
        `Konfirmasi pengembalian alat "${equipment}" dari member ${member}?`;
    document.getElementById('confirmForm').action = `/admin/returns/${id}/confirm`;
    document.getElementById('confirmModal').style.display = 'flex';
}

function openReject(id, member, equipment) {
    document.getElementById('rejectDesc').textContent =
        `Tolak pengembalian alat "${equipment}" dari member ${member}?`;
    document.getElementById('rejectForm').action = `/admin/returns/${id}/reject`;
    document.getElementById('rejectModal').style.display = 'flex';
}

function closeModals() {
    document.getElementById('confirmModal').style.display = 'none';
    document.getElementById('rejectModal').style.display = 'none';
}

// Close on overlay click
document.querySelectorAll('.modal-overlay').forEach(el => {
    el.addEventListener('click', e => {
        if (e.target === el) closeModals();
    });
});
</script>

@endsection
