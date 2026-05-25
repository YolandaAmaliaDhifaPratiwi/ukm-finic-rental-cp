@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="flex-between mb-24">
    <div>
        <h1>Beranda</h1>
        <p class="text-gray" style="margin-top:4px;">Selamat datang kembali, {{ auth()->user()->name }}. Berikut adalah aktivitas hari ini.</p>
    </div>
    <div class="text-sm text-gray">{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</div>
</div>

{{-- STATS --}}
<div class="stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:24px;">
    <div class="stat-card">
        <div>
            <div class="label">Total Inventaris</div>
            <div class="value">{{ $totalInventory }}</div>
            <div class="sub">Unit terdaftar di katalog</div>
        </div>
        <div class="stat-icon stat-icon-blue">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="label">Peminjaman Aktif</div>
            <div class="value" style="color:var(--orange);">{{ $activeBorrowings }}</div>
            <div class="sub">Alat yang sedang dipinjam</div>
        </div>
        <div class="stat-icon stat-icon-orange">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="13,2 3,14 12,14 11,22 21,10 12,10 13,2"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="label">Permintaan Tertunda</div>
            <div class="value" style="color:#EF4444;">{{ $pendingRequests }}</div>
            <div class="sub">Menunggu persetujuan Anda</div>
        </div>
        <div class="stat-icon stat-icon-red">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg>
        </div>
    </div>
</div>

@if($pendingRequests > 0)
<div class="alert alert-warning mb-24">
    ⚠ Anda memiliki <strong>{{ $pendingRequests }} permintaan peminjaman</strong> yang menunggu persetujuan.
    <a href="{{ route('admin.borrowing.index', ['status'=>'pending']) }}" class="font-bold text-orange" style="margin-left:8px;">Tinjau sekarang →</a>
</div>
@endif

<div style="display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start;">
    <div>
        {{-- RECENT ACTIVITY --}}
        <div class="card mb-16">
            <div class="flex-between mb-16">
                <div class="section-title">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg>
                    Aktivitas Terbaru
                </div>
                <a href="{{ route('admin.history.index') }}" class="text-orange text-sm">Lihat Semua ↗</a>
            </div>
            @forelse($recentActivity as $act)
            <div class="activity-item">
                <div class="avatar">{{ strtoupper(substr($act->user->name,0,1)) }}</div>
                <div style="flex:1;">
                    <div class="text-sm">
                        <strong>{{ $act->user->name }}</strong>
                        @if($act->status==='approved') meminjam
                        @elseif($act->status==='pending') mengajukan permohonan untuk
                        @elseif($act->status==='returned') mengembalikan
                        @elseif($act->status==='overdue') terlambat mengembalikan
                        @else berinteraksi dengan @endif
                        <strong>{{ $act->equipment->name }}</strong>
                    </div>
                    <div style="margin-top:5px;">
                        <span class="activity-badge activity-{{ $act->status }}">
                            @if($act->status==='approved') disetujui
                            @elseif($act->status==='pending') tertunda
                            @elseif($act->status==='returned') dikembalikan
                            @elseif($act->status==='overdue') terlambat
                            @else {{ $act->status }} @endif
                        </span>
                    </div>
                </div>
                <div class="text-sm text-gray" style="white-space:nowrap;">{{ $act->updated_at->diffForHumans() }}</div>
            </div>
            @empty
            <div style="text-align:center;padding:32px;color:var(--gray-500);">Tidak ada aktivitas terbaru.</div>
            @endforelse
        </div>

        {{-- TRENDING EQUIPMENT --}}
        <div class="card">
            <div class="flex-between mb-16">
                <div class="section-title">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="23,6 13.5,15.5 8.5,10.5 1,18"/><polyline points="17,6 23,6 23,12"/></svg>
                    Alat Terpopuler
                </div>
                <span class="text-sm text-gray">Paling sering dipinjam</span>
            </div>
            @forelse($trendingEquipment as $eq)
            <div class="trending-item">
                <div class="trending-img">
                    @if($eq->image)
                        <img src="{{ asset('storage/'.$eq->image) }}" style="width:100%;height:100%;object-fit:contain;border-radius:8px;">
                    @else
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:var(--gray-100);border-radius:8px;font-size:20px;">📷</div>
                    @endif
                </div>
                <div style="flex:1;">
                    <div class="font-bold text-sm">{{ $eq->name }}</div>
                    <div class="text-sm text-gray">{{ ucfirst($eq->category) }}</div>
                </div>
                <div class="count">
                    <div class="num">{{ $eq->rental_count }}</div>
                    <div class="lbl">PINJAMAN</div>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:24px;color:var(--gray-500);">Belum ada data.</div>
            @endforelse
        </div>
    </div>

    {{-- RIGHT PANEL --}}
    <div style="display:flex;flex-direction:column;gap:16px;">
        {{-- QUICK ACTIONS --}}
        <div class="card">
            <h4 class="mb-16">Aksi Cepat</h4>
            <div style="display:flex;flex-direction:column;gap:8px;">
                <a href="{{ route('admin.borrowing.index', ['status'=>'pending']) }}" class="btn btn-primary btn-full">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9,11 12,14 22,4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
                    Tinjau Permintaan Tertunda
                    @if($pendingRequests > 0)<span class="new-badge">{{ $pendingRequests }}</span>@endif
                </a>
                <a href="{{ route('admin.equipment.index') }}" class="btn btn-outline btn-full">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah Alat Baru
                </a>
                <a href="{{ route('admin.history.export') }}" class="btn btn-outline btn-full">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7,10 12,15 17,10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Ekspor Laporan CSV
                </a>
            </div>
        </div>

        {{-- OVERDUE ALERT --}}
        @php $overdueCount = \App\Models\Borrowing::where('status','approved')->where('return_date','<',now())->count(); @endphp
        @if($overdueCount > 0)
        <div class="card" style="border-left:4px solid #EF4444;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                <svg width="20" height="20" fill="none" stroke="#EF4444" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span style="font-weight:700;color:#EF4444;">{{ $overdueCount }} Alat Terlambat</span>
            </div>
            <p class="text-sm text-gray" style="margin-bottom:12px;">Beberapa anggota belum mengembalikan alat yang melewati batas waktu.</p>
            <a href="{{ route('admin.borrowing.index', ['status'=>'approved']) }}" class="btn btn-danger btn-sm btn-full">Lihat Alat Terlambat</a>
        </div>
        @endif

        {{-- SYSTEM STATUS --}}
        <div class="card">
            <h4 class="mb-16">Status Sistem</h4>
            <div style="display:flex;flex-direction:column;gap:10px;">
                <div class="flex-between text-sm">
                    <span class="text-gray">Alat Tersedia</span>
                    <span class="font-bold" style="color:#059669;">{{ \App\Models\Equipment::where('status','available')->count() }} unit</span>
                </div>
                <div class="flex-between text-sm">
                    <span class="text-gray">Sedang Dipinjam</span>
                    <span class="font-bold" style="color:var(--orange);">{{ \App\Models\Equipment::where('status','borrowed')->count() }} unit</span>
                </div>
                <div class="flex-between text-sm">
                    <span class="text-gray font-sans">Dalam Perawatan</span>
                    <span class="font-bold" style="color:#EF4444;">{{ \App\Models\Equipment::where('status','maintenance')->count() }} unit</span>
                </div>
                <div class="flex-between text-sm">
                    <span class="text-gray">Total Anggota</span>
                    <span class="font-bold">{{ \App\Models\User::where('role','member')->count() }}</span>
                </div>
                <hr style="border:none;border-top:1px solid var(--gray-100);margin:4px 0;">
                <div class="flex-between text-sm">
                    <span class="text-gray">Basis Data</span>
                    <span style="color:#059669;font-weight:600;">✓ Normal</span>
                </div>
                <div class="flex-between text-sm">
                    <span class="text-gray">Penyimpanan</span>
                    <span style="color:#059669;font-weight:600;">✓ Optimal</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection