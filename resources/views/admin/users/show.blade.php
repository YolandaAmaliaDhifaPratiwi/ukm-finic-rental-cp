@extends('layouts.admin')
@section('title', 'Detail Member')

@section('content')
<div style="margin-bottom:20px;">
    <a href="{{ route('admin.users.index') }}" style="color:var(--gray-500);font-size:13px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15,18 9,12 15,6"/></svg>
        Kembali ke Daftar Anggota
    </a>
</div>

<div style="display:grid;grid-template-columns:300px 1fr;gap:20px;align-items:start;">

    {{-- PROFIL CARD --}}
    <div style="display:flex;flex-direction:column;gap:16px;">
        <div class="card" style="text-align:center;padding:28px 20px;">
            @if($user->avatar)
                <img src="{{ asset('storage/'.$user->avatar) }}"
                     style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid var(--orange);margin:0 auto 14px;display:block;">
            @else
                <div style="width:80px;height:80px;border-radius:50%;background:var(--orange);display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:700;color:#fff;margin:0 auto 14px;">
                    {{ strtoupper(substr($user->name,0,1)) }}
                </div>
            @endif
            
            <div style="font-size:18px;font-weight:700;margin-bottom:4px;">{{ $user->name }}</div>
            <div style="color:var(--gray-500);font-size:13px;">{{ $user->email }}</div>
            
            @if($user->student_id)
            <div style="color:var(--gray-500);font-size:13px;margin-top:2px;">NIM: {{ $user->student_id }}</div>
            @endif

            {{-- TOTAL PINJAM --}}
            <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--gray-200);">
                <div style="text-align:center;">
                    <div style="font-size:24px;font-weight:800;color:var(--orange);">{{ $user->borrowings_count }}</div>
                    <div style="font-size:12px;color:var(--gray-500);font-weight:500;margin-top:2px;">Total Peminjaman</div>
                </div>
            </div>

            <button onclick="openResetPassword()"
                    style="width:100%;margin-top:24px;padding:9px;background:#FEF2F2;color:#DC2626;border:1px solid #FCA5A5;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">
                Reset Password
            </button>
        </div>

        <div class="card card-sm">
            <div style="font-size:12px;color:var(--gray-500);margin-bottom:2px;">Bergabung</div>
            <div style="font-weight:600;font-size:14px;">{{ $user->created_at->format('d M Y') }}</div>
            <div style="font-size:12px;color:var(--gray-500);margin-top:8px;margin-bottom:2px;">Peminjaman Aktif</div>
            <div style="font-weight:600;font-size:14px;color:var(--orange);">{{ $activeBorrowings->count() }} item</div>
        </div>
    </div>

    {{-- RIWAYAT --}}
    <div>
        <div class="card" style="padding:0;overflow:hidden;">
            <div style="padding:16px 20px;border-bottom:1px solid var(--gray-200);font-weight:700;font-size:15px;">
                Riwayat Peminjaman
            </div>
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:var(--gray-50);">
                        <th style="padding:10px 16px;text-align:left;font-size:11px;font-weight:700;color:var(--gray-500);text-transform:uppercase;">Alat</th>
                        <th style="padding:10px 16px;text-align:left;font-size:11px;font-weight:700;color:var(--gray-500);text-transform:uppercase;">Pinjam</th>
                        <th style="padding:10px 16px;text-align:left;font-size:11px;font-weight:700;color:var(--gray-500);text-transform:uppercase;">Kembali</th>
                        <th style="padding:10px 16px;text-align:left;font-size:11px;font-weight:700;color:var(--gray-500);text-transform:uppercase;">Kondisi Akhir</th>
                        <th style="padding:10px 16px;text-align:left;font-size:11px;font-weight:700;color:var(--gray-500);text-transform:uppercase;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($borrowings as $b)
                    <tr style="border-top:1px solid var(--gray-100);">
                        <td style="padding:12px 16px;">
                            <div style="font-weight:600;font-size:13px;">{{ $b->equipment->name }}</div>
                            <div style="font-size:11px;color:var(--gray-500);">{{ $b->transaction_code }}</div>
                        </td>
                        <td style="padding:12px 16px;font-size:13px;">{{ $b->borrow_date->format('d M Y') }}</td>
                        <td style="padding:12px 16px;font-size:13px;">{{ $b->return_date->format('d M Y') }}</td>
                        <td style="padding:12px 16px;font-size:13px;">
                            @if($b->final_condition)
                                @if($b->final_condition === 'excellent') Sangat Bagus
                                @elseif($b->final_condition === 'good') Bagus
                                @elseif($b->final_condition === 'minor_scratches') Luka Gores Ringan
                                @elseif($b->final_condition === 'needs_repair') Perlu Perbaikan
                                @else {{ ucfirst($b->final_condition) }} @endif
                            @else
                                <span style="color:var(--gray-400);">—</span>
                            @endif
                        </td>
                        <td style="padding:12px 16px;">
                            {{-- Menggunakan badge bawaan status, disesuaikan ke teks Indonesia --}}
                            @php
                                $statusLabel = match($b->status) {
                                    'pending' => 'Menunggu',
                                    'approved' => 'Disetujui',
                                    'rejected' => 'Ditolak',
                                    'returned' => 'Dikembangkan',
                                    default => ucfirst($b->status)
                                };
                            @endphp
                            <span class="badge badge-{{ $b->status }}">{{ $statusLabel }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding:32px;text-align:center;color:var(--gray-500);font-size:13px;">Belum ada riwayat peminjaman.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL RESET PASSWORD --}}
<div id="resetPassModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:16px;padding:28px;width:100%;max-width:420px;margin:0 16px;position:relative;">
        <button onclick="closeResetPassword()" style="position:absolute;top:16px;right:16px;background:none;border:none;font-size:20px;cursor:pointer;color:#6B7280;">✕</button>
        <h3 style="margin-bottom:6px;font-size:18px;font-weight:700;">Reset Password</h3>
        <p class="text-gray" style="font-size:13px;margin-bottom:20px;">Setel password baru untuk <strong>{{ $user->name }}</strong>.</p>
        <form method="POST" action="{{ route('admin.users.resetPassword', $user) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Password Baru</label>
                <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter" required>
            </div>
            <div class="form-group">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
            </div>
            <div style="display:flex;gap:10px;margin-top:8px;">
                <button type="submit" style="flex:1;padding:10px;background:#DC2626;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;">Reset Password</button>
                <button type="button" onclick="closeResetPassword()" class="btn btn-outline" style="flex:1;">Batal</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openResetPassword() {
    document.getElementById('resetPassModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeResetPassword() {
    document.getElementById('resetPassModal').style.display = 'none';
    document.body.style.overflow = '';
}
document.getElementById('resetPassModal').addEventListener('click', function(e){ if(e.target===this) closeResetPassword(); });
</script>
@endpush
@endsection