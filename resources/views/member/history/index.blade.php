@extends('layouts.member')
@section('title', 'Riwayat Peminjaman Saya')

@section('content')
<div class="flex-between mb-24">
    <div>
        <h1>Riwayat Peminjaman Saya</h1>
        <p class="text-gray" style="margin-top:4px;">Semua daftar pengajuan peminjaman alat Anda beserta statusnya.</p>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>TRANSAKSI</th>
                    <th>PERALATAN</th>
                    <th>TANGGAL PINJAM</th>
                    <th>TANGGAL KEMBALI</th>
                    <th>STATUS</th>
                    <th>KEPERLUAN</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrowings as $b)
                <tr>
                    <td>
                        <div class="font-bold text-sm">{{ $b->transaction_code }}</div>
                        <div class="text-sm text-gray">{{ $b->created_at->locale('id')->format('d M Y') }}</div>
                    </td>
                    <td>
                        <div class="font-bold">{{ $b->equipment->name }}</div>
                        <span class="badge badge-{{ $b->equipment->category }}" style="font-size:11px;">
                            @if(strtolower($b->equipment->category) === 'camera')
                                Kamera
                            @elseif(strtolower($b->equipment->category) === 'lens')
                                Lensa
                            @elseif(strtolower($b->equipment->category) === 'tripod')
                                Tripod
                            @elseif(strtolower($b->equipment->category) === 'lighting')
                                Lampu
                            @else
                                {{ ucfirst($b->equipment->category) }}
                            @endif
                        </span>
                    </td>

                    <!-- <td class="text-sm">{{ $b->borrow_date->format('M d, Y') }}</td>
                    <td class="text-sm">{{ $b->return_date->format('M d, Y') }}</td> -->

                    <td class="text-sm">
                        {{ $b->borrow_date->locale('id')->translatedFormat('d F Y') }}
                    </td>

                    <td class="text-sm">
                        {{ $b->return_date->locale('id')->translatedFormat('d F Y') }}
                    </td>
                    <td>
                        <span class="badge badge-{{ $b->status }}">
                            @if($b->status === 'pending')
                                Menunggu
                            @elseif($b->status === 'approved')
                                Disetujui
                            @elseif($b->status === 'rejected')
                                Ditolak
                            @elseif($b->status === 'returned')
                                Dikembalikan
                            @else
                                {{ ucfirst($b->status) }}
                            @endif
                        </span>
                    </td>
                    <td class="text-sm text-gray" style="max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ Str::limit($b->purpose, 50) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:48px; color:var(--gray-500);">
                        Belum ada riwayat peminjaman alat. <a href="{{ route('member.equipment.index') }}" class="text-primary" style="text-decoration:none; font-weight:600;">Jelajahi Daftar Alat →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">
        {{ $borrowings->links() }}
    </div>
</div>
@endsection