<!DOCTYPE html>
<html lang="id">
<head>
    <title>Admin – @yield('title', 'UKM Finic')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-head />
    @stack('styles')
</head>
<body>
<nav class="topnav">
    <a href="{{ route('admin.dashboard') }}" class="topnav-brand">
        <img src="{{ asset('images/logo_finic.png') }}" alt="UKM Finic" style="height:32px; width:auto;">
        UKM Finic
    </a>
    <div class="topnav-right">

        @include('admin.partials.notification-dropdown')

        <a href="{{ route('admin.profile') }}" style="display:flex;align-items:center;gap:10px;text-decoration:none;color:inherit;padding:4px 8px;border-radius:10px;transition:background .2s;"
           onmouseenter="this.style.background='var(--gray-100)'" 
           onmouseleave="this.style.background='none'">

            <div style="text-align:right;">
                <div style="font-size:13px;font-weight:700;color:#111827;">
                    {{ auth()->user()->name }}
                </div>
                <div style="font-size:11px;color:var(--gray-500);">
                    admin
                </div>
            </div>

            {{-- Cek foto profil: cukup cek kolom avatar, tidak perlu Storage::exists --}}
            @if(auth()->user()->avatar)
                <img src="{{ asset('storage/profile_photos/'.auth()->user()->avatar) }}"
                     style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid #22c55e;">
            @else
                <div style="width:36px;height:36px;border-radius:50%;background:#22c55e;display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:700;color:#fff;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            @endif
        </a>
    </div>
</nav>

<div class="layout-sidebar">
    <aside class="sidebar">
        <nav>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                Beranda
            </a>
            <a href="{{ route('admin.equipment.index') }}" class="sidebar-link {{ request()->routeIs('admin.equipment*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                Kelola Alat
            </a>
            <a href="{{ route('admin.borrowing.index') }}" class="sidebar-link {{ request()->routeIs('admin.borrowing*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9,11 12,14 22,4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
                Permintaan Peminjaman
                @php $pc = \App\Models\Borrowing::where('status','pending')->count(); @endphp
                @if($pc > 0)<span class="new-badge">{{ $pc }}</span>@endif
            </a>
            <a href="{{ route('admin.history.index') }}" class="sidebar-link {{ request()->routeIs('admin.history*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg>
                Riwayat Peminjaman
            </a>
            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zM23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                Anggota
            </a>
        </nav>

        <div class="sidebar-bottom">
            <a href="{{ route('admin.settings') }}" class="sidebar-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93A10 10 0 104.93 19.07"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
                Pengaturan
            </a>
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="sidebar-link" style="color:#EF4444;background:none;border:none;width:100%;text-align:left;cursor:pointer;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <div class="page-content">
        @if(session('success'))<div class="alert alert-success mb-16">✓ {{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-error mb-16">✗ {{ session('error') }}</div>@endif
        @yield('content')
    </div>
</div>

<script>
function openModal(id){document.getElementById(id).classList.add('open');document.body.style.overflow='hidden'}
function closeModal(id){document.getElementById(id).classList.remove('open');document.body.style.overflow=''}
document.querySelectorAll('.modal-overlay').forEach(el=>{el.addEventListener('click',e=>{if(e.target===el)closeModal(el.id)})});

const bell=document.getElementById('bell-btn'),panel=document.getElementById('notif-panel');
if(bell&&panel){
    bell.addEventListener('click',e=>{e.stopPropagation();panel.classList.toggle('open')});
    document.addEventListener('click',()=>panel.classList.remove('open'))
}

setTimeout(()=>{
    document.querySelectorAll('.alert').forEach(el=>{
        el.style.transition='opacity .4s';
        el.style.opacity='0';
        setTimeout(()=>el.remove(),400)
    })
},4000);

document.querySelectorAll('[data-edit]').forEach(btn=>{
    btn.addEventListener('click',()=>{
        const data=JSON.parse(btn.dataset.edit);
        const modal=document.getElementById(btn.dataset.modal||'editModal');
        if(!modal) return;
        Object.entries(data).forEach(([k,v])=>{
            const el=modal.querySelector('[name="'+k+'"]');
            if(el)el.value=v||''
        });
        if(btn.dataset.action)document.getElementById('editForm').action=btn.dataset.action;
        openModal(btn.dataset.modal||'editModal')
    })
});
</script>

@stack('scripts')
</body>
</html>