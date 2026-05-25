@extends('layouts.admin')
@section('title', 'Permintaan Peminjaman')

@section('content')
<div class="flex-between mb-24">
    <div>
        <h1>Permintaan Peminjaman</h1>
        <p class="text-gray" style="margin-top:4px;">Tinjau dan kelola pengajuan peminjaman alat dari anggota.</p>
    </div>
</div>

{{-- STATS --}}
<div class="stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:24px;">
    <div class="stat-card">
        <div>
            <div class="label">Menunggu Persetujuan</div>
            <div class="value">{{ $pendingCount }}</div>
        </div>
        <div class="stat-icon stat-icon-orange">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="label">Disetujui Hari Ini</div>
            <div class="value" style="color:#059669;">{{ $approvedToday }}</div>
        </div>
        <div class="stat-icon stat-icon-green">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20,6 9,17 4,12"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="label">Permintaan Penting / Mendesak</div>
            <div class="value" style="color:#EF4444;">{{ $urgentCount }}</div>
        </div>
        <div class="stat-icon stat-icon-red">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
    </div>
</div>

{{-- FILTERS / TABS --}}
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;gap:16px;flex-wrap:wrap;">
        <div class="tabs" style="margin-bottom:0;">
            @foreach(['all'=>'Semua', 'pending'=>'Menunggu', 'approved'=>'Disetujui', 'rejected'=>'Ditolak', 'returned'=>'Dikembalikan'] as $val=>$lbl)
            <a href="{{ route('admin.borrowing.index', ['status'=>$val]) }}"
               class="tab-btn {{ (request('status','all')===$val)?'active':'' }}">
                {{ $lbl }}
                @if($val==='pending' && $pendingCount > 0)
                    <span class="new-badge">{{ $pendingCount }}</span>
                @endif
            </a>
            @endforeach
        </div>
        <form method="GET" action="{{ route('admin.borrowing.index') }}" style="display:flex;gap:8px;">
            <input type="hidden" name="status" value="{{ request('status','all') }}">
            <div class="search-bar">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="search" class="form-control" placeholder="Cari anggota atau alat..." value="{{ request('search') }}" style="width:220px;">
            </div>
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Detail Anggota</th>
                    <th>Alat</th>
                    <th>Periode Pinjam</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrowings as $b)
                <tr>
                    <td>
                        <div style="display:flex;gap:10px;align-items:center;">
                            <div class="avatar">{{ strtoupper(substr($b->user->name,0,1)) }}</div>
                            <div>
                                <div class="font-bold">{{ $b->user->name }}</div>
                                <div class="text-sm text-gray">{{ $b->user->student_id }} • {{ $b->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="display:flex;gap:10px;align-items:center;">
                            <div style="width:36px;height:36px;background:var(--gray-100);border-radius:8px;display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;">
                                @if($b->equipment->image ?? false)
                                    <img src="{{ asset('storage/'.$b->equipment->image) }}" style="width:100%;height:100%;object-fit:cover;">
                                @else
                                    📷
                                @endif
                            </div>
                            <div>
                                <div class="font-bold">{{ $b->equipment->name }}</div>
                                <span class="badge badge-{{ $b->equipment->category }}" style="font-size:11px;">
                                    @if($b->equipment->category === 'camera') Kamera
                                    @elseif($b->equipment->category === 'lens') Lensa
                                    @elseif($b->equipment->category === 'tripod') Tripod
                                    @elseif($b->equipment->category === 'lighting') Lighting
                                    @elseif($b->equipment->category === 'accessory') Aksesoris
                                    @else {{ ucfirst($b->equipment->category) }} @endif
                                </span>
                            </div>
                        </div>
                    </td>
                    <td class="text-sm">
                        <div>📅 {{ $b->borrow_date->format('d M Y') }}</div>
                        <div class="text-gray">→ {{ $b->return_date->format('d M Y') }}</div>
                        @if($b->isOverdue())
                            <div style="color:#EF4444;font-weight:600;font-size:11px;margin-top:2px;">⚠ Terlambat {{ abs($b->days_remaining) }} hari</div>
                        @endif
                    </td>
                    <td>
                        {!! $b->status_badge !!}
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;">

                            {{-- DETAIL BUTTON --}}
                            <a href="{{ route('admin.borrowing.show', $b->id) }}"
                               style="display:inline-flex;align-items:center;gap:5px;padding:6px 12px;border-radius:8px;font-size:12px;font-weight:600;background:#f0f9ff;color:#0369a1;border:1px solid #bae6fd;text-decoration:none;transition:all .15s;"
                               onmouseover="this.style.background='#e0f2fe'" onmouseout="this.style.background='#f0f9ff'">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                Detail
                            </a>

                        </div>
                    </td>
                </tr>

                @empty
                <tr><td colspan="5" style="text-align:center;padding:48px;color:var(--gray-500);">Tidak ada data permintaan peminjaman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:16px;">
        <span class="text-sm text-gray">
            @if($borrowings->total() > 0)
                Menampilkan {{ $borrowings->firstItem() }}–{{ $borrowings->lastItem() }} dari {{ $borrowings->total() }} permintaan
            @else
                Data tidak ditemukan
            @endif
        </span>
        {{ $borrowings->links() }}
    </div>
</div>
@endsection