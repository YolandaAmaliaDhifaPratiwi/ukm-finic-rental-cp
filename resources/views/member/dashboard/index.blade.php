@extends('layouts.member')
@section('title', __('member.nav_dashboard'))

@section('content')
{{-- WELCOME BANNER --}}
<div class="welcome-banner" style="margin-bottom:24px;">
    <div>
        <h2>Selamat datang kembali, {{ explode(' ', $user->name)[0] }}! 👋</h2>
        <p>
            @if($activeBorrowings->count() > 0)
                Kamu memiliki {{ $activeBorrowings->count() }} alat yang sedang dipinjam.
            @else
                Tidak ada peminjaman aktif. Siap untuk meminjam alat?
            @endif
        </p>
        <div class="banner-actions">
            <a href="{{ route('member.equipment.index') }}" class="btn btn-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Cari Alat
            </a>
            <a href="{{ route('member.history.index') }}" class="btn btn-outline" style="background:rgba(255,255,255,.15);color:#fff;border-color:rgba(255,255,255,.3);">
                Lihat Riwayat
            </a>
        </div>
    </div>
<div style="position:absolute; right:32px; top:50%; transform:translateY(-50%); opacity:.3;">
        <img src="{{ asset('images/logo_finic.png') }}" style="width:150px; height:auto; object-fit:contain;">
    </div>
</div>

{{-- STATS --}}
<div class="stats-grid">
    <div class="stat-card">
        <div>
            <div class="label">Total Peminjaman</div>
            <div class="value">{{ $totalBorrowings }}</div>
            <div class="sub">⊙ Total peminjaman selama ini</div>
        </div>
        <div class="stat-icon stat-icon-blue">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="label">Peminjaman Aktif</div>
            <div class="value" style="color:var(--orange);">{{ $activeBorrowings->count() }}</div>
            <div class="sub">⊙ Alat yang sedang kamu bawa</div>
        </div>
        <div class="stat-icon stat-icon-orange">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="13,2 3,14 12,14 11,22 21,10 12,10 13,2"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="label">Permintaan Tertunda</div>
            <div class="value">{{ $pendingRequests }}</div>
            <div class="sub">⊙ Menunggu persetujuan admin</div>
        </div>
        <div class="stat-icon stat-icon-green">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;">
    <div>
        {{-- ACTIVE GEAR --}}
        <div class="card" style="margin-bottom:20px;">
            <div class="section-header">
                <div class="section-title">
                    <svg width="18" height="18" fill="none" stroke="var(--orange)" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg>
                    Alat Aktif Kamu
                </div>
                <a href="{{ route('member.history.index') }}" class="text-orange text-sm">Lihat semua detail</a>
            </div>

            @forelse($activeBorrowings as $borrowing)
            <div style="display:flex;align-items:center;gap:14px;padding:14px;border:1px solid var(--gray-300);border-radius:10px;margin-bottom:12px;cursor:pointer;"
                 onclick="openGearDetail('{{ $borrowing->id }}','{{ $borrowing->equipment->name }}','{{ $borrowing->equipment->category }}','{{ $borrowing->return_date->format('M d, Y') }}','{{ $borrowing->transaction_code }}','{{ $borrowing->status }}','{{ $borrowing->borrow_date->format('M d, Y') }}','{{ $borrowing->days_remaining }}','{{ $borrowing->equipment->image ? asset('storage/'.$borrowing->equipment->image) : '' }}','{{ $borrowing->equipment->condition_label }}')">
                <div style="width:60px;height:60px;background:var(--gray-100);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                    @if($borrowing->equipment->image)
                        <img src="{{ asset('storage/'.$borrowing->equipment->image) }}" style="width:100%;height:100%;object-fit:contain;border-radius:8px;">
                    @else
                        <svg width="28" height="28" fill="none" stroke="var(--gray-500)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                    @endif
                </div>
                <div style="flex:1;">
                    <span class="badge badge-{{ $borrowing->equipment->category }}" style="font-size:11px;margin-bottom:4px;">{{ ucfirst($borrowing->equipment->category) }}</span>
                    <div style="font-weight:700;">{{ $borrowing->equipment->name }}</div>
                    <div class="text-sm text-gray">📅 Batas Waktu: {{ $borrowing->return_date->format('d M Y') }}
                        @if($borrowing->days_remaining >= 0)
                            <span style="color:var(--orange);font-weight:600;">⊙ Sisa {{ $borrowing->days_remaining }} hari</span>
                        @else
                            <span style="color:#EF4444;font-weight:600;">Terlambat {{ abs($borrowing->days_remaining) }} hari</span>
                        @endif
                    </div>
                </div>
                @if($borrowing->days_remaining >= 3)
                    <span class="badge badge-approved">Aman</span>
                @else
                    <span class="badge badge-overdue">Segera Dikembalikan</span>
                @endif
                <svg width="16" height="16" fill="none" stroke="var(--gray-500)" stroke-width="2" viewBox="0 0 24 24"><polyline points="9,18 15,12 9,6"/></svg>
            </div>
            @empty
            <div style="text-align:center;padding:32px;color:var(--gray-500);">
                <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24" style="margin:0 auto 12px;display:block;"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                <p>Tidak ada alat yang aktif. <a href="{{ route('member.equipment.index') }}" class="text-orange">Cari alat</a></p>
            </div>
            @endforelse
        </div>

       {{-- RECOMMENDED --}}
        @if($recommended->count() > 0)
        <div class="card">
            <h4 class="mb-16">Rekomendasi untuk Kamu</h4>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                @foreach($recommended as $eq)
                <div style="display:flex;gap:12px;align-items:center;padding:12px;border:1px solid var(--gray-300);border-radius:10px;">
                    <div style="width:50px;height:50px;background:var(--gray-100);border-radius:8px;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                        @if($eq->image)
                            <img src="{{ asset('storage/'.$eq->image) }}" style="width:100%;height:100%;object-fit:contain;border-radius:8px;">
                        @else
                            📷
                        @endif
                    </div>
                    <div>
                        <div style="font-weight:600;font-size:13px;">{{ $eq->name }}</div>
                        <div style="font-size:11px;color:var(--gray-500);">{{ $eq->category_label }}</div>
                        <div style="font-size:11px;font-weight:700;color:#059669;margin-top:2px;">TERSEDIA SEKARANG</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- RIGHT SIDEBAR --}}
    <div>
        {{-- LATEST UPDATES --}}
        <!-- <div class="card" style="margin-bottom:16px;">
            <div class="section-header">
                <div class="section-title">Pembaruan Terbaru</div>
                <span class="new-badge">2 Baru</span>
            </div>
            <div style="font-size:13px;">
                <div style="padding:10px 0;border-bottom:1px solid var(--gray-100);">
                    <a href="#" class="text-blue font-bold">Permintaan kamu untuk Canon R5 telah disetujui. ●</a>
                    <div class="text-gray text-sm mt-4">2 jam yang lalu</div>
                </div>
                <div style="padding:10px 0;border-bottom:1px solid var(--gray-100);">
                    <div class="font-bold">Keterlambatan pengembalian sekarang akan dikenakan potongan poin.</div>
                    <div class="text-gray text-sm mt-4">Kemarin, 10:30 WIB</div>
                </div>
            </div>
            <a href="{{ route('member.notifications') }}" class="text-orange text-sm" style="margin-top:12px;display:inline-block;">Lihat Pusat Notifikasi</a>
        </div> -->

    {{-- LATEST UPDATES (REAL) --}}
        <div class="card" style="margin-bottom:16px;">
            <div class="section-header">
                <div class="section-title">Pembaruan Terbaru</div>
                @if($unreadCount > 0)
                    <span class="new-badge">{{ $unreadCount }} Baru</span>
                @endif
            </div>
            <div style="font-size:13px;">
                @forelse($notifications as $notif)
                    @php
                        $data    = $notif->data;
                        $isUnread = is_null($notif->read_at);
                    @endphp
                    <div style="padding:10px 0;border-bottom:1px solid var(--gray-100);">
                        @if($isUnread)
                            <div class="font-bold" style="color:var(--blue, #2563EB);">
                                {{ $data['message'] ?? $data['title'] ?? 'Notifikasi baru' }} ●
                            </div>
                        @else
                            <div class="font-bold">
                                {{ $data['message'] ?? $data['title'] ?? 'Notifikasi' }}
                            </div>
                        @endif
                        <div class="text-gray text-sm mt-4">
                            {{ $notif->created_at->diffForHumans() }}
                        </div>
                    </div>
                @empty
                    <div style="padding:16px 0;text-align:center;color:var(--gray-500);">
                        Tidak ada notifikasi.
                    </div>
                @endforelse
            </div>
            <a href="{{ route('member.notifications') }}" class="text-orange text-sm"
            style="margin-top:12px;display:inline-block;">Lihat Pusat Notifikasi</a>
        </div>

        {{-- HELP --}}
        <div class="card" style="background:#111827;color:#fff;">
            <div style="background:var(--orange);width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:12px;">
                <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
            <h4 style="margin-bottom:8px;">Butuh Bantuan?</h4>
            <p style="font-size:13px;opacity:.75;margin-bottom:16px;">Bingung tentang alat? Cek panduan anggota kami atau hubungi admin.</p>
            <a href="{{ route('member.gearGuides') }}" class="btn btn-primary btn-full">Baca Panduan Alat</a>
        </div>
    </div>
</div>

{{-- MODAL GEAR DETAIL --}}
<div id="gearDetailModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:16px;padding:28px;width:100%;max-width:460px;margin:0 16px;position:relative;">
        <button onclick="closeGearDetail()" style="position:absolute;top:16px;right:16px;background:none;border:none;cursor:pointer;font-size:20px;color:#6B7280;">✕</button>
        <div style="display:flex;gap:16px;align-items:center;margin-bottom:20px;">
            <div id="gd-img" style="width:72px;height:72px;border-radius:10px;background:var(--gray-100);overflow:hidden;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                <svg width="32" height="32" fill="none" stroke="var(--gray-400)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
            </div>
            <div>
                <div id="gd-category" style="font-size:11px;font-weight:700;color:var(--orange);margin-bottom:4px;"></div>
                <div id="gd-name" style="font-size:18px;font-weight:700;"></div>
                <div id="gd-status" style="margin-top:4px;"></div>
            </div>
        </div>
        <div style="background:var(--gray-100);border-radius:10px;padding:16px;display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
            <div>
                <div style="font-size:11px;color:#6B7280;margin-bottom:2px;">KODE TRANSAKSI</div>
                <div id="gd-trx" style="font-size:13px;font-weight:600;"></div>
            </div>
            <div>
                <div style="font-size:11px;color:#6B7280;margin-bottom:2px;">KONDISI</div>
                <div id="gd-condition" style="font-size:13px;font-weight:600;"></div>
            </div>
            <div>
                <div style="font-size:11px;color:#6B7280;margin-bottom:2px;">TANGGAL PINJAM</div>
                <div id="gd-borrow" style="font-size:13px;font-weight:600;"></div>
            </div>
            <div>
                <div style="font-size:11px;color:#6B7280;margin-bottom:2px;">TANGGAL KEMBALI</div>
                <div id="gd-return" style="font-size:13px;font-weight:600;"></div>
            </div>
        </div>
        <div id="gd-overdue" style="display:none;background:#FEF2F2;border:1px solid #FCA5A5;border-radius:8px;padding:10px 14px;font-size:13px;color:#DC2626;font-weight:600;margin-bottom:16px;"></div>
        <a href="{{ route('member.history.index') }}" class="btn btn-primary btn-full">Lihat Riwayat Lengkap →</a>
    </div>
</div>

@push('scripts')
<script>
const overdueMsg     = "⚠️ Kamu terlambat mengembalikan alat ini selama :days hari!";
const onTrackLabel    = "Aman";
const returningSoon   = "Segera Dikembalikan";

function openGearDetail(id, name, category, returnDate, trx, status, borrowDate, daysRemaining, imgUrl, condition) {
    document.getElementById('gd-name').textContent = name;
    document.getElementById('gd-category').textContent = category.toUpperCase();
    document.getElementById('gd-trx').textContent = trx;
    document.getElementById('gd-condition').textContent = condition;
    document.getElementById('gd-borrow').textContent = borrowDate;
    document.getElementById('gd-return').textContent = returnDate;
    const imgEl = document.getElementById('gd-img');
    if (imgUrl) imgEl.innerHTML = '<img src="'+imgUrl+'" style="width:100%;height:100%;object-fit:contain;">';
    const days = parseInt(daysRemaining);
    const overdueEl = document.getElementById('gd-overdue');
    if (days < 0) {
        overdueEl.style.display = 'block';
        overdueEl.textContent = overdueMsg.replace(':days', Math.abs(days));
    } else {
        overdueEl.style.display = 'none';
    }
    const statusEl = document.getElementById('gd-status');
    if (status === 'approved') {
        statusEl.innerHTML = days >= 3
            ? '<span class="badge badge-approved">'+onTrackLabel+'</span>'
            : '<span class="badge badge-overdue">'+returningSoon+'</span>';
    }
    const modal = document.getElementById('gearDetailModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeGearDetail() {
    document.getElementById('gearDetailModal').style.display = 'none';
    document.body.style.overflow = '';
}

document.getElementById('gearDetailModal').addEventListener('click', function(e) {
    if (e.target === this) closeGearDetail();
});
</script>
@endpush
@endsection