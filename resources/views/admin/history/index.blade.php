@extends('layouts.admin')
@section('title', 'Riwayat Peminjaman')

@section('content')
<div class="flex-between mb-8">
    <div>
        <h1>Riwayat Peminjaman</h1>
        <p class="text-gray" style="margin-top:4px;max-width:620px;">Log riwayat komprehensif dari semua transaksi peminjaman alat. Tinjau aktivitas anggota, pantau kondisi alat saat dikembalikan, dan ekspor laporan untuk audit administrasi.</p>
    </div>
    <div style="display:flex;gap:12px;">
        <div class="card card-sm" style="text-align:center;min-width:100px;">
            <div style="font-size:22px;font-weight:800;color:var(--blue);">{{ number_format($totalRecords) }}</div>
            <div class="text-sm text-gray">TOTAL DATA</div>
        </div>
        <div class="card card-sm" style="text-align:center;min-width:100px;">
            <div style="font-size:22px;font-weight:800;color:var(--orange);">{{ $thisMonth }}</div>
            <div class="text-sm text-gray">BULAN INI</div>
        </div>
    </div>
</div>

{{-- SEARCH / FILTER --}}
<div class="card mb-16" style="padding:16px 20px;">
    <form method="GET" action="{{ route('admin.history.index') }}" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
        <div class="search-bar" style="flex:1;min-width:200px;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="search" class="form-control" placeholder="Cari nama anggota atau alat..." value="{{ request('search') }}">
        </div>
        <div style="display:flex;gap:8px;align-items:center;">
            <div style="display:flex;flex-direction:column;gap:2px;">
                <span style="font-size:11px;color:#6B7280;font-weight:600;">TGL PINJAM</span>
                <input type="date" name="from_date" value="{{ request('from_date') }}"
                    style="height:36px;padding:0 10px;border:1px solid #D1D5DB;border-radius:8px;font-size:13px;color:#374151;background:#fff;">
            </div>
            <div style="display:flex;flex-direction:column;gap:2px;">
                <span style="font-size:11px;color:#6B7280;font-weight:600;">TGL KEMBALI</span>
                <input type="date" name="to_date" value="{{ request('to_date') }}"
                    style="height:36px;padding:0 10px;border:1px solid #D1D5DB;border-radius:8px;font-size:13px;color:#374151;background:#fff;">
            </div>
        </div>
        <button type="submit" class="btn btn-outline btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="22,3 2,3 10,12.46 10,19 14,21 14,12.46 22,3"/></svg>
            Terapkan
        </button>
        @if(request('from_date') || request('to_date') || request('search'))
        <a href="{{ route('admin.history.index') }}" class="btn btn-outline btn-sm" style="color:#6B7280;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            Reset
        </a>
        @endif
        <a href="{{ route('admin.history.export', request()->only(['from_date','to_date','search'])) }}" class="btn btn-blue btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7,10 12,15 17,10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Ekspor CSV
        </a>
    </form>
</div>

{{-- TABLE --}}
<div class="card">
    <div class="flex-between mb-16">
        <div class="section-title">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14,2 14,8 20,8"/></svg>
            Buku Besar Transaksi
        </div>
        <span class="text-sm text-gray">Menampilkan {{ $borrowings->firstItem() }}–{{ $borrowings->lastItem() }} dari {{ $borrowings->total() }} hasil</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama Anggota</th>
                    <th>Alat</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Kondisi Akhir</th>
                    <th>Status</th>
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
                                <div class="text-sm text-gray">{{ $b->transaction_code }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="font-bold">{{ $b->equipment->name }}</div>
                        <span class="badge badge-{{ $b->equipment->category }}" style="font-size:11px;">
                            @if($b->equipment->category === 'camera') Kamera
                            @elseif($b->equipment->category === 'lens') Lensa
                            @elseif($b->equipment->category === 'tripod') Tripod
                            @elseif($b->equipment->category === 'lighting') Lighting
                            @elseif($b->equipment->category === 'accessory') Aksesoris
                            @else {{ ucfirst($b->equipment->category) }} @endif
                        </span>
                    </td>
                    <td class="text-sm">📅 {{ $b->borrow_date->format('d/m/Y') }}</td>
                    <td class="text-sm">📅 {{ $b->return_date->format('d/m/Y') }}</td>
                    <td>
                        @if($b->final_condition)
                            @php
                                $condColor = match($b->final_condition) {
                                    'excellent' => 'var(--gray-700)',
                                    'good' => 'var(--blue)',
                                    'minor_scratches' => 'var(--gray-500)',
                                    'needs_repair' => '#EF4444',
                                    default => 'var(--gray-500)'
                                };
                                $condLabel = match($b->final_condition) {
                                    'excellent' => 'Sangat Bagus',
                                    'good' => 'Bagus',
                                    'minor_scratches' => 'Luka Gores Ringan',
                                    'needs_repair' => 'Perlu Perbaikan',
                                    default => ucfirst($b->final_condition)
                                };
                            @endphp
                            <span style="color:{{ $condColor }};font-weight:600;">{{ $condLabel }}</span>
                        @else
                            <span class="text-gray">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-{{ $b->status }}">
                            @if($b->status === 'pending') Menunggu
                            @elseif($b->status === 'approved') Disetujui
                            @elseif($b->status === 'rejected') Ditolak
                            @elseif($b->status === 'returned') Dikembalikan
                            @else {{ ucfirst($b->status) }} @endif
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:48px;color:var(--gray-500);">Data riwayat tidak ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:16px;">
        <span class="text-sm text-gray">Halaman {{ $borrowings->currentPage() }} dari {{ $borrowings->lastPage() }}</span>
        {{ $borrowings->appends(request()->query())->links() }}
    </div>
</div>
@endsection