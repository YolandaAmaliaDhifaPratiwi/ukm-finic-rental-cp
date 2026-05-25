@extends('layouts.admin')
@section('title', __('admin.settings_title'))

@section('content')
<div class="flex-between mb-24">
    <div>
        <h1>Pengaturan</h1>
        <p class="text-gray" style="margin-top:4px;">Kelola preferensi dan konfigurasi sistem.</p>
    </div>
    <form method="POST" action="{{ route('admin.settings.reset') }}">
        @csrf
        <button type="submit" class="btn btn-outline" style="font-size:13px;"
                onclick="return confirm('Reset semua pengaturan ke default?')">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="1,4 1,10 7,10"/><path d="M3.51 15a9 9 0 102.13-9.36L1 10"/></svg>
            Reset ke Default
        </button>
    </form>
</div>

{{-- Hanya menampilkan alert error jika inputan salah, alert sukses diserahkan sepenuhnya ke layout utama --}}
@if(session('error') || $errors->any())
    <div style="background: #FDE8E8; color: #9B1C1C; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
        {{ session('error') ?? 'Gagal menyimpan, mohon periksa kembali inputan Anda.' }}
    </div>
@endif

<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start;">

        {{-- KIRI --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            {{-- BORROWING RULES --}}
            <div class="card">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;padding-bottom:14px;border-bottom:1px solid var(--gray-200);">
                    <div style="width:36px;height:36px;background:#FEF3C7;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                        <svg width="18" height="18" fill="none" stroke="#D97706" stroke-width="2" viewBox="0 0 24 24"><polyline points="9,11 12,14 22,4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:15px;">Aturan Peminjaman</div>
                        <div style="font-size:12px;color:var(--gray-500);">Atur batas dan aturan peminjaman</div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Durasi Maks Peminjaman (hari)</label>
                    <div style="display:flex;align-items:center;gap:12px;">
                        <input type="number" name="max_borrow_days" class="form-control" value="{{ $settings['max_borrow_days'] }}" min="1" max="365" style="width:100px;">
                        <span class="text-gray text-sm">hari maksimal per peminjaman</span>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Maksimal Item per Anggota</label>
                    <div style="display:flex;align-items:center;gap:12px;">
                        <input type="number" name="max_items_per_member" class="form-control" value="{{ $settings['max_items_per_member'] }}" min="1" max="20" style="width:100px;">
                        <span class="text-gray text-sm">item per anggota sekaligus</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- KANAN --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            {{-- NOTIFICATIONS --}}
            <div class="card">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;padding-bottom:14px;border-bottom:1px solid var(--gray-200);">
                    <div style="width:36px;height:36px;background:#F0FDF4;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                        <svg width="18" height="18" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:15px;">Notifikasi</div>
                        <div style="font-size:12px;color:var(--gray-500);">Atur preferensi notifikasi</div>
                    </div>
                </div>

                @foreach([
                    ['key'=>'notif_email',    'label'=>'Notifikasi Email',    'desc'=>'Kirim email untuk setiap aktivitas'],
                    ['key'=>'notif_overdue',  'label'=>'Peringatan Keterlambatan',  'desc'=>'Peringatan item yang terlambat dikembalikan'],
                    ['key'=>'notif_approval', 'label'=>'Notifikasi Persetujuan', 'desc'=>'Notifikasi saat ada permintaan baru'],
                ] as $notif)
                <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid var(--gray-100);">
                    <div>
                        <div style="font-size:14px;font-weight:600;">{{ $notif['label'] }}</div>
                        <div style="font-size:12px;color:var(--gray-500);margin-top:2px;">{{ $notif['desc'] }}</div>
                    </div>
                    <label style="position:relative;display:inline-block;width:44px;height:24px;cursor:pointer;flex-shrink:0;">
                        <input type="checkbox" name="{{ $notif['key'] }}" value="1" {{ $settings[$notif['key']] ? 'checked' : '' }}
                               style="opacity:0;width:0;height:0;" onchange="toggleSwitch(this)">
                        <span class="toggle-track" style="position:absolute;inset:0;border-radius:99px;background:{{ $settings[$notif['key']] ? 'var(--orange)' : 'var(--gray-300)' }};transition:background .2s;">
                            <span style="position:absolute;top:3px;left:{{ $settings[$notif['key']] ? '23px' : '3px' }};width:18px;height:18px;background:#fff;border-radius:50%;transition:left .2s;box-shadow:0 1px 3px rgba(0,0,0,.2);"></span>
                        </span>
                    </label>
                </div>
                @endforeach
            </div>

            {{-- SAVE BUTTON --}}
            <button type="submit" class="btn btn-primary btn-full" style="padding:13px;font-size:15px;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17,21 17,13 7,13 7,21"/><polyline points="7,3 7,8 15,8"/></svg>
                Simpan Perubahan
            </button>

        </div>
    </div>
</form>

@push('scripts')
<script>
// Toggle switch visual
function toggleSwitch(input) {
    const track = input.nextElementSibling;
    const thumb = track.querySelector('span');
    if (input.checked) {
        track.style.background = 'var(--orange)';
        thumb.style.left = '23px';
    } else {
        track.style.background = 'var(--gray-300)';
        thumb.style.left = '3px';
    }
}
</script>
@endpush
@endsection