@extends('layouts.member')
@section('title', 'Daftar Alat')

@section('content')
<div style="margin-bottom:8px;">
    <span style="background:var(--blue-light); color:var(--blue); font-size:11px; font-weight:700; padding:3px 10px; border-radius:99px;">
        Katalog UKM Finic
    </span>
</div>
<div class="flex-between mb-16">
    <div>
        <h1>Jelajahi Alat Profesional</h1>
        <p class="text-gray" style="margin-top:4px; max-width:500px;">
            Peralatan fotografi dan videografi berkualitas tinggi yang tersedia untuk anggota. Jelajahi koleksi lengkap kami dan pesan alat kamu sekarang.
        </p>
    </div>
    <div style="text-align:right; display:flex; gap:12px;">
        <div class="card card-sm" style="text-align:center; min-width:80px;">
            <div style="font-size:22px; font-weight:800; color:var(--primary);">{{ $available }}</div>
            <div class="text-sm text-gray">Tersedia</div>
        </div>
        <div class="card card-sm" style="text-align:center; min-width:80px;">
            <div style="font-size:22px; font-weight:800;">{{ $total }}</div>
            <div class="text-sm text-gray">Total Alat</div>
        </div>
    </div>
</div>

{{-- FILTERS --}}
<form method="GET" action="{{ route('member.equipment.index') }}" id="filterForm">
    <div class="flex-between mb-24" style="gap:12px;">

        {{-- Category Tabs --}}
        <div style="display:flex;gap:4px;background:var(--gray-100);border-radius:8px;padding:4px;">
            @foreach([
                'all'      => 'Semua',
                'camera'   => 'Kamera',
                'lens'     => 'Lensa',
                'tripod'   => 'Tripod',
                'lighting' => 'Lampu',
            ] as $val => $lbl)
            <button type="submit" name="category" value="{{ $val }}"
                class="tab-btn {{ (request('category','all') === $val) ? 'active' : '' }}">
                {{ $lbl }}
            </button>
            @endforeach
        </div>

        @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
        @endif
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        @if(request('condition'))
            <input type="hidden" name="condition" value="{{ request('condition') }}">
        @endif

        <div style="display:flex;gap:10px;align-items:center;position:relative;">

            {{-- Search --}}
            <div class="search-bar">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="search" class="form-control" placeholder="Cari peralatan..."
                    value="{{ request('search') }}" style="width:220px;"
                    onchange="document.getElementById('filterForm').submit()">
            </div>

            {{-- Filter Button --}}
            <div style="position:relative;">
                <button type="button" id="filterToggle"
                    onclick="toggleFilterPanel()"
                    class="btn btn-outline btn-sm"
                    style="{{ (request('status') || request('condition')) ? 'border-color:var(--primary); color:var(--primary); background:var(--primary-light);' : '' }}">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="22,3 2,3 10,12.46 10,19 14,21 14,12.46 22,3"/></svg>
                    @if(request('status') || request('condition'))
                        <span style="width:7px; height:7px; background:var(--primary); border-radius:50%; display:inline-block; margin-left:-4px;"></span>
                    @endif
                </button>

{{-- Filter Dropdown Panel --}}
                <div id="filterPanel" style="
                    display:none;
                    position:absolute;
                    top:calc(100% + 8px);
                    right:0;
                    width:260px;
                    background:#fff;
                    border-radius:12px;
                    box-shadow:0 8px 32px rgba(0,0,0,.15);
                    border:1px solid var(--gray-300);
                    z-index:100;
                    padding:20px;
                ">
                    <div style="position:absolute;top:-7px;right:14px;width:14px;height:14px;background:#fff;border-left:1px solid var(--gray-300);border-top:1px solid var(--gray-300);transform:rotate(45deg);"></div>

                    <div style="font-size:13px;font-weight:700;margin-bottom:14px;display:flex;align-items:center;justify-content:space-between;">
                        <span>Filter Peralatan</span>
                        @if(request('status') || request('condition'))
                            <a href="{{ route('member.equipment.index', array_filter(['category' => request('category'), 'search' => request('search')])) }}"
                               style="font-size:11px;color:#ef4444;font-weight:600;text-decoration:none;">
                                Hapus filter
                            </a>
                        @endif
                    </div>

                    {{-- Status Filter --}}
                    <div style="margin-bottom:16px;">
                        <div style="font-size:11px;font-weight:700;color:var(--gray-500);text-transform:uppercase;letter-spacing:.6px;margin-bottom:8px;">Ketersediaan</div>
                        <div style="display:flex;flex-direction:column;gap:6px;">
                            @foreach(['' => 'Semua', 'available' => 'Tersedia', 'borrowed' => 'Dipinjam'] as $val => $lbl)
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:6px 10px;border-radius:7px;border:1.5px solid {{ request('status','') === $val ? 'var(--primary)' : 'var(--gray-300)' }};background:{{ request('status','') === $val ? 'var(--primary-light)' : '#fff' }};">
                                <input type="radio" name="status" value="{{ $val }}"
                                    {{ request('status', '') === $val ? 'checked' : '' }}
                                    onchange="document.getElementById('filterForm').submit()"
                                    style="accent-color:var(--primary);width:14px;height:14px;">
                                <span style="font-size:13px;font-weight:{{ request('status','') === $val ? '600' : '400' }};">
                                    @if($val === 'available')
                                        <span style="display:inline-block;width:8px;height:8px;background:#16a34a;border-radius:50%;margin-right:4px;"></span>
                                    @elseif($val === 'borrowed')
                                        <span style="display:inline-block;width:8px;height:8px;background:#ef4444;border-radius:50%;margin-right:4px;"></span>
                                    @endif
                                    {{ $lbl }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Condition Filter --}}
                    <div style="margin-bottom:16px;">
                        <div style="font-size:11px;font-weight:700;color:var(--gray-500);text-transform:uppercase;letter-spacing:.6px;margin-bottom:8px;">Kondisi</div>
                        <div style="display:flex;flex-direction:column;gap:6px;">
                            @foreach([
                                        '' => 'Semua Kondisi',
                                        'excellent' => 'Sangat Baik',
                                        'good' => 'Baik',
                                        'fair' => 'Cukup'
                                    ] as $val => $lbl)
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:6px 10px;border-radius:7px;border:1.5px solid {{ request('condition','') === $val ? 'var(--primary)' : 'var(--gray-300)' }};background:{{ request('condition','') === $val ? 'var(--primary-light)' : '#fff' }};">
                                <input type="radio" name="condition" value="{{ $val }}"
                                    {{ request('condition', '') === $val ? 'checked' : '' }}
                                    onchange="document.getElementById('filterForm').submit()"
                                    style="accent-color:var(--primary);width:14px;height:14px;">
                                <span style="font-size:13px;font-weight:{{ request('condition','') === $val ? '600' : '400' }};">{{ $lbl }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Sort --}}
                    <div>
                        <div style="font-size:11px; font-weight:700; color:var(--gray-500); text-transform:uppercase; letter-spacing:.6px; margin-bottom:8px;">Urutkan Berdasarkan</div>
                        <select name="sort" onchange="document.getElementById('filterForm').submit()"
                            class="form-control" style="font-size:13px; padding:8px 12px;">
                                <option value="name" 
                                    {{ request('sort','name') === 'name' ? 'selected' : '' }}>
                                    Nama (A - Z)
                                </option>

                                <option value="name_desc" 
                                    {{ request('sort') === 'name_desc' ? 'selected' : '' }}>
                                    Nama (Z - A)
                                </option>

                                <option value="available" 
                                    {{ request('sort') === 'available' ? 'selected' : '' }}>
                                    Tersedia Dahulu
                                </option>

                                <option value="newest" 
                                    {{ request('sort') === 'newest' ? 'selected' : '' }}>
                                    Terbaru
                                </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{-- Active Filter Tags --}}
    @if(request('status') || request('condition') || request('search'))
    <div style="display:flex; gap:8px; align-items:center; margin-bottom:16px; flex-wrap:wrap;">
        <span style="font-size:12px; color:var(--gray-500); font-weight:600;">
            Filter aktif:
        </span>

        @if(request('status'))
            <a href="{{ route('member.equipment.index', array_filter(['category'=>request('category'),'search'=>request('search'),'condition'=>request('condition'),'sort'=>request('sort')])) }}"
               style="display:inline-flex; align-items:center; gap:5px; background:var(--primary-light); color:var(--primary); border:1px solid var(--border); border-radius:99px; padding:3px 10px; font-size:12px; font-weight:600; text-decoration:none;">
                Status: 
                @if(request('status') === 'available')
                    Tersedia
                @elseif(request('status') === 'borrowed')
                    Dipinjam
                @else
                    {{ request('status') }}
                @endif
                ✕
            </a>
        @endif

        @if(request('condition'))
            <a href="{{ route('member.equipment.index', array_filter(['category'=>request('category'),'search'=>request('search'),'status'=>request('status'),'sort'=>request('sort')])) }}"
               style="display:inline-flex; align-items:center; gap:5px; background:var(--primary-light); color:var(--primary); border:1px solid var(--border); border-radius:99px; padding:3px 10px; font-size:12px; font-weight:600; text-decoration:none;">
                Kondisi: 
                @if(request('condition') === 'excellent')
                    Sangat Baik
                @elseif(request('condition') === 'good')
                    Baik
                @elseif(request('condition') === 'fair')
                    Cukup
                @else
                    {{ request('condition') }}
                @endif
                ✕
            </a>
        @endif

        @if(request('search'))
            <a href="{{ route('member.equipment.index', array_filter(['category'=>request('category'),'status'=>request('status'),'condition'=>request('condition'),'sort'=>request('sort')])) }}"
               style="display:inline-flex; align-items:center; gap:5px; background:#fef9c3; color:#854d0e; border:1px solid #fef08a; border-radius:99px; padding:3px 10px; font-size:12px; font-weight:600; text-decoration:none;">
                Cari: "{{ request('search') }}" ✕
            </a>
        @endif
    </div>
    @endif

</form>

{{-- EQUIPMENT GRID --}}
<div class="equip-grid">
    @forelse($equipment as $eq)
    <div class="equip-card">
        <div class="equip-img">
            @if($eq->image)
                <img src="{{ asset('storage/'.$eq->image) }}" alt="{{ $eq->name }}">
            @else
                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:var(--gray-100);">
                    <svg width="52" height="52" fill="none" stroke="var(--gray-300)" stroke-width="1" viewBox="0 0 24 24"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                </div>
            @endif
            <div class="equip-status-badge">
                @if($eq->status === 'available')
                    <span class="badge badge-available">Tersedia</span>
                @else
                    <span class="badge badge-borrowed">Dipinjam</span>
                @endif
            </div>
        </div>
        <div class="equip-body">
            <div class="equip-category">
                @if($eq->category === 'camera')
                    KAMERA
                @elseif($eq->category === 'lens')
                    LENSA
                @elseif($eq->category === 'tripod')
                    TRIPOD
                @elseif($eq->category === 'lighting')
                    LAMPU
                @else
                    {{ strtoupper($eq->category) }}
                @endif
            </div>

 <div class="equip-name">{{ $eq->name }}</div>
            
            {{-- PENGKONDISIAN BAHASA UNTUK KONDISI ALAT --}}
            <div class="equip-condition">
                Kondisi: 
                <strong>
                    @if(strtolower($eq->condition) === 'excellent')
                        Sangat Baik
                    @elseif(strtolower($eq->condition) === 'good')
                        Baik
                    @elseif(strtolower($eq->condition) === 'fair')
                        Cukup
                    @else
                        {{ $eq->condition_label }}
                    @endif
                </strong>
            </div>
            
            @if($eq->isAvailable())
                <a href="{{ route('member.equipment.borrow', $eq) }}" class="btn btn-primary btn-full">Pinjam Alat</a>
            @else
                <button class="btn btn-outline btn-full" disabled style="opacity:.5;cursor:not-allowed;">Tidak Tersedia</button>
            @endif
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1;text-align:center;padding:60px;color:var(--gray-500);">
        <svg width="64" height="64" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24" style="margin:0 auto 16px;display:block;"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
        <h3 style="margin-bottom:8px;">Peralatan Tidak Ditemukan</h3>
        <p>Maaf, peralatan dengan kriteria filter tersebut saat ini tidak tersedia atau tidak dapat ditemukan.</p>
        <a href="{{ route('member.equipment.index') }}" class="btn btn-outline" style="margin-top:16px;">Atur Ulang Semua Filter</a>
    </div>
    @endforelse
</div>

{{-- BORROWING RULES --}}
<div class="card flex-center gap-16" style="margin-top:36px;">
    {{-- Kotak Ikon Kiri (Memakai kode warna hijau langsung) --}}
    <div style="width:44px; height:44px; background:#e6f4ea; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
        <svg width="22" height="22" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
    </div>
    
    {{-- Teks Informasi --}}
    <div style="flex:1;">
        <div style="font-weight:700; margin-bottom:3px;">Aturan Peminjaman Alat</div>
        <div class="text-sm text-gray">Maksimal peminjaman adalah 3 hari. Harap jaga kondisi barang dengan baik dan kembalikan tepat waktu.</div>
    </div>
    
    {{-- Tombol Link Kanan (Memakai warna hijau langsung) --}}
    <a href="{{ route('member.gearGuides') }}"
        style="flex-shrink:0; font-weight:600; text-decoration:none; color:#16a34a;">
        Lihat Panduan Peminjaman
    </a>
</div>

{{-- FOOTER --}}
<!-- <footer style="background:var(--gray-900); color:#fff; padding:48px; border-radius:12px; margin-top:32px;">
    <div class="footer-grid">
        <div class="footer-brand">
            <div style="font-size:18px; font-weight:700; color:var(--primary);">📷 UKM Finic</div>
            <p>Kreativitas tanpa batas. Menyediakan layanan inventarisasi dan peminjaman alat fotografi terbaik untuk seluruh anggota.</p>
        </div>
        <div class="footer-col">
            <h4>Tautan Langsung</h4>
            <a href="#">Katalog Peralatan</a>
            <a href="#">Aturan Peminjaman</a>
            <a href="#">FAQ Anggota</a>
        </div>
        <div class="footer-col">
            <h4>Kategori Alat</h4>
            <a href="#">Kamera DSLR & Mirrorless</a>
            <a href="#">Lensa Utama & Zoom</a>
            <a href="#">Paket Pencahayaan</a>
        </div>
        <div class="footer-col">
            <h4>Hubungi Kami</h4>
            <a href="mailto:finicums0125@gmail.com">finicums0125@gmail.com</a>
            <a href="#">Gedung UKM, Universitas Muhammadiyah Surakarta</a>
            <a href="#">Jam Kerja: 09:00 - 17:00 WIB</a>
        </div>
    </div>
    <div class="footer-bottom">
        <span>© 2026 UKM Finic Rental. Hak cipta dilindungi undang-undang.</span>
        <div style="display:flex; gap:16px;">
            <a href="#" style="color:#6B7280;">Kebijakan Privasi</a>
            <a href="#" style="color:#6B7280;">Syarat & Ketentuan</a>
        </div>
    </div>
</footer> -->

<script>
function toggleFilterPanel() {
    const panel = document.getElementById('filterPanel');
    const isOpen = panel.style.display === 'block';
    panel.style.display = isOpen ? 'none' : 'block';
}

document.addEventListener('click', function(e) {
    const panel  = document.getElementById('filterPanel');
    const toggle = document.getElementById('filterToggle');
    if (panel && !panel.contains(e.target) && !toggle.contains(e.target)) {
        panel.style.display = 'none';
    }
});
</script>
@endsection
