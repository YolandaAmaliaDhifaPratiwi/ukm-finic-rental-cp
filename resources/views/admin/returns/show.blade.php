{{-- resources/views/admin/returns/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Detail Pengembalian')

@section('content')
<div class="return-detail-page">

    {{-- Breadcrumb --}}
    <div class="breadcrumb">
        <a href="{{ route('admin.returns.index') }}">Pengembalian</a>
        <span class="bc-sep">›</span>
        <span>Detail #{{ $return->id }}</span>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success">✓ {{ session('success') }}</div>
    @endif

    <div class="detail-grid">

        {{-- LEFT: Info pengembalian --}}
        <div class="left-col">

            {{-- Status Card --}}
            <div class="status-card
                @if($return->status === 'pending')   status-card-pending
                @elseif($return->status === 'confirmed') status-card-confirmed
                @else status-card-rejected @endif">
                <div class="status-top">
                    <div class="status-icon-lg">
                        @if($return->status === 'pending')   ⏳
                        @elseif($return->status === 'confirmed') ✅
                        @else ❌ @endif
                    </div>
                    <div>
                        <div class="status-label-lg">
                            @if($return->status === 'pending')   Menunggu Konfirmasi
                            @elseif($return->status === 'confirmed') Pengembalian Dikonfirmasi
                            @else Pengembalian Ditolak @endif
                        </div>
                        <div class="status-time">
                            Diajukan {{ $return->returned_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info Alat --}}
            <div class="info-card">
                <div class="info-card-header">
                    <span>🎞</span> Alat yang Dikembalikan
                </div>
                <div class="info-card-body">
                    <div class="equipment-row">
                        @if($return->borrowing->equipment->photo ?? false)
                            <img src="{{ asset('storage/'.$return->borrowing->equipment->photo) }}"
                                 alt="" class="eq-photo">
                        @else
                            <div class="eq-photo-placeholder">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.5">
                                    <rect x="2" y="3" width="20" height="14" rx="2"/>
                                    <path d="M8 21h8M12 17v4"/>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <div class="eq-name">{{ $return->borrowing->equipment->name }}</div>
                            @if($return->borrowing->equipment->category ?? false)
                                <div class="eq-category">{{ $return->borrowing->equipment->category }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="info-rows">
                        <div class="info-row">
                            <span class="info-key">Tanggal Pinjam</span>
                            <span class="info-val">{{ \Carbon\Carbon::parse($return->borrowing->borrow_date)->format('d M Y') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Jatuh Tempo</span>
                            <span class="info-val">{{ \Carbon\Carbon::parse($return->borrowing->return_date)->format('d M Y') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Tanggal Kembali</span>
                            <span class="info-val fw-green">{{ $return->returned_at->format('d M Y, H:i') }}</span>
                        </div>
                        @if($return->confirmed_at)
                        <div class="info-row">
                            <span class="info-key">Dikonfirmasi</span>
                            <span class="info-val">{{ $return->confirmed_at->format('d M Y, H:i') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Info Member --}}
            <div class="info-card">
                <div class="info-card-header">
                    <span>👤</span> Anggota
                </div>
                <div class="info-card-body">
                    <div class="member-row">
                        <div class="member-avatar-lg">
                            {{ strtoupper(substr($return->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="member-name-lg">{{ $return->user->name }}</div>
                            <div class="member-email-lg">{{ $return->user->email }}</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- RIGHT: Kondisi & Aksi --}}
        <div class="right-col">

            {{-- Kondisi Barang --}}
            <div class="info-card">
                <div class="info-card-header">
                    <span>📝</span> Laporan Kondisi dari Anggota
                </div>
                <div class="info-card-body">
                    <div class="condition-text">
                        {{ $return->condition_notes ?? '-' }}
                    </div>
                </div>
            </div>

            {{-- Foto --}}
            @if($return->photo)
            <div class="info-card">
                <div class="info-card-header">
                    <span>📷</span> Foto Kondisi Barang
                </div>
                <div class="info-card-body">
                    <a href="{{ asset('storage/'.$return->photo) }}" target="_blank">
                        <img src="{{ asset('storage/'.$return->photo) }}"
                             alt="Foto kondisi" class="condition-photo">
                    </a>
                    <p class="photo-caption">Klik foto untuk melihat ukuran penuh</p>
                </div>
            </div>
            @endif

            {{-- Catatan Admin --}}
            @if($return->admin_notes)
            <div class="info-card">
                <div class="info-card-header">
                    <span>💬</span> Catatan Admin
                </div>
                <div class="info-card-body">
                    <div class="admin-note-text">{{ $return->admin_notes }}</div>
                </div>
            </div>
            @endif

            {{-- ACTION: hanya jika pending --}}
            @if($return->status === 'pending')
            <div class="action-card">
                <div class="action-card-header">⚡ Ambil Tindakan</div>
                <div class="action-card-body">

                    {{-- Konfirmasi --}}
                    <form action="{{ route('admin.returns.confirm', $return->id) }}"
                          method="POST" class="action-form">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label class="form-label">Catatan Konfirmasi (opsional)</label>
                            <textarea name="admin_notes" rows="3" class="form-textarea"
                                      placeholder="Misal: Kondisi baik, semua aksesori lengkap."></textarea>
                        </div>
                        <button type="submit" class="btn-confirm-full">
                            ✓ Konfirmasi Pengembalian
                        </button>
                    </form>

                    <div class="divider">atau</div>

                    {{-- Tolak --}}
                    <form action="{{ route('admin.returns.reject', $return->id) }}"
                          method="POST" class="action-form">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label class="form-label">Alasan Penolakan <span class="required">*</span></label>
                            <textarea name="admin_notes" rows="3" class="form-textarea is-red"
                                      placeholder="Alasan penolakan..." required></textarea>
                        </div>
                        <button type="submit" class="btn-reject-full"
                                onclick="return confirm('Yakin ingin menolak pengembalian ini?')">
                            ✕ Tolak Pengembalian
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Tombol Kembali --}}
            <a href="{{ route('admin.returns.index') }}" class="btn-back">
                ← Kembali ke Daftar
            </a>

        </div>
    </div>
</div>

<style>
.return-detail-page {
    padding: 28px 24px 60px;
    max-width: 1000px;
    font-family: 'Poppins', sans-serif;
}

.breadcrumb {
    display: flex; align-items: center; gap: 8px;
    font-size: 0.82rem; color: #64748b; margin-bottom: 20px;
}
.breadcrumb a { color: #22a322; text-decoration: none; font-weight: 500; }
.bc-sep { color: #cbd5e1; }

.alert {
    padding: 14px 18px; border-radius: 12px;
    font-size: 0.88rem; margin-bottom: 20px; font-weight: 500;
}
.alert-success { background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; }

/* Grid */
.detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    align-items: start;
}
@media(max-width:768px){ .detail-grid{ grid-template-columns:1fr; } }

.left-col, .right-col { display: flex; flex-direction: column; gap: 16px; }

/* Status Card */
.status-card {
    border-radius: 16px;
    padding: 18px 20px;
    border: 1.5px solid;
}
.status-card-pending  { background:#fffdf5; border-color:#fde68a; }
.status-card-confirmed{ background:#f0fdf4; border-color:#bbf7d0; }
.status-card-rejected { background:#fef2f2; border-color:#fecaca; }

.status-top { display:flex; align-items:center; gap:14px; }
.status-icon-lg { font-size:2rem; }
.status-label-lg { font-size:0.96rem; font-weight:700; color:#1a2e1a; }
.status-time { font-size:0.78rem; color:#94a3b8; margin-top:2px; }

/* Info Cards */
.info-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e8f0e8;
    box-shadow: 0 1px 6px rgba(0,0,0,0.04);
    overflow: hidden;
}

.info-card-header {
    display: flex; align-items: center; gap: 8px;
    padding: 14px 18px;
    background: #fafcfa;
    border-bottom: 1px solid #f0f6f0;
    font-size: 0.86rem;
    font-weight: 600;
    color: #1a2e1a;
}

.info-card-body { padding: 18px; }

.equipment-row { display:flex; align-items:center; gap:14px; margin-bottom:14px; }
.eq-photo {
    width:64px; height:64px; object-fit:cover;
    border-radius:12px; border:1px solid #e8f0e8;
}
.eq-photo-placeholder {
    width:64px; height:64px; background:#f0f6f0;
    border-radius:12px; display:flex; align-items:center;
    justify-content:center; color:#86a886; border:1px solid #e8f0e8;
}
.eq-name { font-size:1rem; font-weight:700; color:#1a2e1a; }
.eq-category {
    font-size:0.72rem; color:#22a322; background:#f0fdf0;
    display:inline-block; padding:2px 10px; border-radius:10px;
    margin-top:3px; font-weight:600; text-transform:uppercase;
}

.info-rows { border-top:1px solid #f0f6f0; padding-top:12px; }
.info-row {
    display:flex; justify-content:space-between; align-items:center;
    padding:7px 0; border-bottom:1px solid #f8faf8; font-size:0.83rem;
}
.info-key { color:#64748b; }
.info-val { font-weight:600; color:#1a2e1a; }
.fw-green { color:#15803d; }

.member-row { display:flex; align-items:center; gap:12px; }
.member-avatar-lg {
    width:48px; height:48px;
    background:linear-gradient(135deg,#1a6b1a,#2d9e2d);
    color:#fff; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-size:1.1rem; font-weight:700;
}
.member-name-lg { font-size:0.96rem; font-weight:700; color:#1a2e1a; }
.member-email-lg { font-size:0.78rem; color:#94a3b8; }

.condition-text {
    font-size:0.88rem; color:#374151; line-height:1.7;
    background:#f8fafc; padding:14px; border-radius:10px;
    border-left:3px solid #22a322;
}

.condition-photo {
    width:100%; border-radius:12px;
    border:1px solid #e8f0e8; display:block;
    transition:opacity 0.2s;
}
.condition-photo:hover { opacity:0.9; }
.photo-caption { font-size:0.74rem; color:#94a3b8; text-align:center; margin:8px 0 0; }

.admin-note-text {
    font-size:0.88rem; color:#374151; line-height:1.6;
    padding:12px; background:#fffdf5; border-radius:10px;
    border-left:3px solid #eab308;
}

/* Action Card */
.action-card {
    background:#fff; border-radius:16px;
    border:1.5px solid #e8f0e8;
    box-shadow:0 2px 12px rgba(0,0,0,0.06);
    overflow:hidden;
}
.action-card-header {
    padding:14px 18px; background:linear-gradient(135deg,#1a6b1a,#2d9e2d);
    color:#fff; font-size:0.9rem; font-weight:700;
}
.action-card-body { padding:18px; }
.action-form { margin-bottom:0; }

.form-group { margin-bottom:14px; }
.form-label { display:block; font-size:0.83rem; font-weight:600; color:#374151; margin-bottom:6px; }
.required { color:#dc2626; }
.form-textarea {
    width:100%; padding:12px 14px;
    border:1.5px solid #e8f0e8; border-radius:10px;
    font-family:'Poppins',sans-serif; font-size:0.84rem;
    color:#1a2e1a; resize:vertical; background:#fafcfa;
    box-sizing:border-box; transition:border-color 0.2s;
}
.form-textarea:focus { outline:none; border-color:#22a322; }
.form-textarea.is-red { border-color:#fecaca; background:#fff8f8; }
.form-textarea.is-red:focus { border-color:#dc2626; }

.divider {
    text-align:center; color:#94a3b8; font-size:0.8rem;
    margin:16px 0; position:relative;
}
.divider::before {
    content:'';
    position:absolute; top:50%; left:0; right:0;
    height:1px; background:#f0f6f0;
}
.divider span, .divider { background:#fff; padding:0 10px; display:inline-block; position:relative; }

.btn-confirm-full, .btn-reject-full {
    width:100%; padding:13px;
    border-radius:12px; font-size:0.88rem; font-weight:700;
    cursor:pointer; transition:all 0.2s; border:none;
    margin-top:4px;
}
.btn-confirm-full {
    background:linear-gradient(135deg,#1a6b1a,#2d9e2d);
    color:#fff; box-shadow:0 2px 8px rgba(26,107,26,0.25);
}
.btn-confirm-full:hover { box-shadow:0 4px 16px rgba(26,107,26,0.35); transform:translateY(-1px); }
.btn-reject-full {
    background:#fef2f2; color:#dc2626;
    border:1.5px solid #fecaca;
}
.btn-reject-full:hover { background:#dc2626; color:#fff; border-color:#dc2626; }

.btn-back {
    display:inline-block;
    font-size:0.84rem; color:#64748b; text-decoration:none;
    padding:8px 0; font-weight:500;
}
.btn-back:hover { color:#1a6b1a; text-decoration:none; }
</style>
@endsection
