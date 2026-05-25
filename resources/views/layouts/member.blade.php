<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      data-theme="{{ session('theme', 'light') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'UKM Finic') – Rental Alat Finic</title>
    <x-head />
    <style>
        /* ══════════════════════════════
           CSS Variables – Light / Dark
        ══════════════════════════════ */
        :root {
            --primary:       #059669;
            --primary-dark:  #047857;
            --primary-light: #f0fdf4;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-700: #374151;
            --gray-900: #111827;
            --bg-page:  #f1f5f9;
            --bg-nav:   #ffffff;
            --bg-card:  #ffffff;
            --bg-sidebar: #ffffff;
            --text-main:#111827;
            --border:   #e5e7eb;
            --shadow-sm: 0 1px 3px rgba(0,0,0,.08);
            --radius:   10px;
            --sidebar-w: 220px;
        }
        [data-theme="dark"] {
            --primary:       #10b981;
            --primary-dark:  #059669;
            --primary-light: #052e16;
            --gray-100: #1f2937;
            --gray-200: #374151;
            --gray-300: #4b5563;
            --gray-400: #6b7280;
            --gray-500: #9ca3af;
            --gray-700: #d1d5db;
            --gray-900: #f9fafb;
            --bg-page:  #0f172a;
            --bg-nav:   #1e293b;
            --bg-card:  #1e293b;
            --bg-sidebar: #1e293b;
            --text-main:#f1f5f9;
            --border:   #334155;
            --shadow-sm: 0 1px 3px rgba(0,0,0,.4);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: var(--bg-page);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: background .25s, color .25s;
        }

        /* ══════════════════════════════
           TOPNAV (hanya logo + page title + kanan)
        ══════════════════════════════ */
        .topnav {
            position: sticky; top: 0; z-index: 200;
            background: var(--bg-nav);
            border-bottom: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            display: flex; align-items: center;
            height: 60px;
            padding: 0 20px 0 0;
            transition: background .25s, border-color .25s;
        }

        /* Brand di dalam topnav (lebar sama dengan sidebar) */
        .topnav-brand-area {
            width: var(--sidebar-w);
            min-width: var(--sidebar-w);
            display: flex;
            align-items: center;
            padding: 0 20px;
            border-right: 1px solid var(--border);
            height: 100%;
            flex-shrink: 0;
        }

        .topnav-brand {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none; color: var(--primary);
            font-weight: 700; font-size: 14px;
            white-space: nowrap;
        }
        .topnav-brand img { height: 30px; width: auto; }

        /* Nama halaman aktif di tengah topnav */
        .topnav-page-title {
            flex: 1;
            padding-left: 24px;
            font-size: 16px;
            font-weight: 700;
            color: var(--text-main);
        }

        .topnav-right { display: flex; align-items: center; gap: 4px; }

        /* Bell */
        .icon-btn {
            position: relative; background: none; border: none; cursor: pointer;
            color: var(--gray-500); width: 38px; height: 38px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            transition: background .15s, color .15s; flex-shrink: 0;
        }
        .icon-btn:hover { background: var(--gray-100); color: var(--gray-900); }
        .bell-badge {
            position: absolute; top: 6px; right: 6px;
            width: 8px; height: 8px; background: #ef4444;
            border-radius: 50%; border: 2px solid var(--bg-nav);
        }

        .topnav-divider { width: 1px; height: 28px; background: var(--border); margin: 0 4px; }

        /* User pill */
        .user-pill {
            display: flex; align-items: center; gap: 10px;
            padding: 6px 10px; border-radius: 10px;
            cursor: pointer; border: none; background: none; color: inherit;
            transition: background .2s;
        }
        .user-pill:hover { background: var(--gray-100); }
        .user-pill .uname { font-size: 14px; font-weight: 600; color: var(--gray-900); line-height: 1.2; text-align: right; }
        .user-pill .urole { font-size: 11px; color: var(--gray-400); text-transform: capitalize; text-align: right; }
        .user-pill .avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: var(--primary); color: #fff;
            font-size: 14px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; border: 2px solid var(--primary);
        }
        .user-pill img {
            width: 36px; height: 36px; border-radius: 50%;
            object-fit: cover; border: 2px solid var(--primary); flex-shrink: 0;
        }
        .pill-chevron { color: var(--gray-400); transition: transform .2s; flex-shrink: 0; }
        .user-pill.open .pill-chevron { transform: rotate(180deg); }

        /* ══════════════════════════════
           LAYOUT: sidebar kiri + konten kanan
        ══════════════════════════════ */
        .page-body {
            display: flex;
            flex: 1;
            min-height: 0;
        }

        /* ══════════════════════════════
           SIDEBAR KIRI
        ══════════════════════════════ */
        .sidebar {
            width: var(--sidebar-w);
            min-width: var(--sidebar-w);
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            padding: 16px 12px;
            position: sticky;
            top: 60px;
            height: calc(100vh - 60px);
            overflow-y: auto;
            flex-shrink: 0;
        }

        .sidebar-section-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--gray-400);
            padding: 0 8px;
            margin-bottom: 6px;
            margin-top: 12px;
        }
        .sidebar-section-label:first-child { margin-top: 0; }

        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 9px;
            text-decoration: none; font-size: 13.5px; font-weight: 500;
            color: var(--gray-500);
            transition: background .15s, color .15s;
            white-space: nowrap;
            margin-bottom: 2px;
            position: relative;
        }
        .sidebar-link:hover { background: var(--gray-100); color: var(--gray-900); }
        .sidebar-link.active {
            background: var(--primary-light);
            color: var(--primary);
            font-weight: 700;
        }
        .sidebar-link svg { flex-shrink: 0; }

        .sidebar-badge {
            background: #ef4444; color: #fff;
            font-size: 10px; font-weight: 700;
            padding: 1px 6px; border-radius: 10px;
            line-height: 1.6; margin-left: auto;
        }

        /* ══════════════════════════════
           MAIN CONTENT AREA
        ══════════════════════════════ */
        .main-content {
            flex: 1;
            min-width: 0;
            padding: 28px 32px;
            overflow-y: auto;
        }

        /* ══════════════════════════════
           DROPDOWN – generic
        ══════════════════════════════ */
        .dropdown-wrap { position: relative; }

        .dropdown-panel {
            display: none;
            position: absolute; top: calc(100% + 10px); right: 0;
            background: var(--bg-card);
            border-radius: 14px;
            box-shadow: 0 8px 32px rgba(0,0,0,.18);
            border: 1px solid var(--border);
            z-index: 200; overflow: visible;
        }
        .dropdown-panel.open { display: block; }

        /* Notification panel */
        .notif-panel { width: 320px; overflow: hidden; border-radius: 14px; }
        .dropdown-header {
            padding: 14px 16px; border-bottom: 1px solid var(--border);
            font-size: 14px; font-weight: 700; color: var(--gray-900);
        }
        .notif-item {
            padding: 12px 16px; border-bottom: 1px solid var(--gray-100);
            transition: background .15s;
        }
        .notif-item:hover { background: var(--gray-100); }
        .notif-item .ntitle { font-size: 13px; color: var(--gray-700); line-height: 1.4; }
        .notif-item .nbadge { color: var(--primary); font-size: 11px; font-weight: 700; }
        .notif-item .ntime  { font-size: 11px; color: var(--gray-400); margin-top: 4px; }
        .dropdown-footer {
            padding: 12px 16px; text-align: center; font-size: 13px;
        }
        .dropdown-footer a { color: var(--primary); text-decoration: none; font-weight: 600; }
        .dropdown-footer a:hover { text-decoration: underline; }

        /* ══════════════════════════════
           PROFILE DROPDOWN
        ══════════════════════════════ */
        .profile-panel { width: 240px; border-radius: 14px; overflow: visible; }

        .pp-top {
            display: flex; align-items: center; gap: 12px;
            padding: 16px; border-bottom: 1px solid var(--border);
        }
        .pp-avatar {
            width: 42px; height: 42px; border-radius: 50%;
            background: var(--primary); color: #fff;
            font-size: 17px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; border: 2px solid var(--primary); overflow: hidden;
        }
        .pp-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .pp-name { font-size: 13.5px; font-weight: 700; color: var(--gray-900); line-height: 1.3; }
        .pp-role { font-size: 11px; color: var(--gray-400); text-transform: capitalize; margin-top: 1px; }

        .pp-menu { padding: 6px; border-bottom: 1px solid var(--border); }
        .pp-menu:last-child { border-bottom: none; }

        .pp-item {
            display: flex; align-items: center; justify-content: space-between;
            padding: 9px 10px; border-radius: 8px;
            font-size: 13.5px; font-weight: 500; color: var(--gray-700);
            text-decoration: none; cursor: pointer;
            border: none; background: none; width: 100%; text-align: left;
            transition: background .15s, color .15s;
        }
        .pp-item:hover { background: var(--gray-100); color: var(--gray-900); }
        .pp-item-left { display: flex; align-items: center; gap: 10px; }
        .pp-item-left svg { flex-shrink: 0; color: var(--gray-400); }
        .pp-item:hover .pp-item-left svg { color: var(--primary); }
        .pp-item.danger { color: #dc2626; }
        .pp-item.danger .pp-item-left svg { color: #dc2626; }
        .pp-item.danger:hover { background: #fee2e2; color: #991b1b; }

        /* ══════════════════════════════
           ALERTS
        ══════════════════════════════ */
        .alert {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 16px; border-radius: var(--radius);
            font-size: 14px; font-weight: 500;
            margin-bottom: 16px;
        }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        /* ══════════════════════════════
           MODAL GLOBAL STYLING
        ══════════════════════════════ */
        .modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px); z-index: 300;
            align-items: center; justify-content: center; padding: 20px;
        }
        .modal-overlay.open { display: flex; }
        
        .modal {
            background: var(--bg-card); border-radius: 16px; padding: 0;
            width: 100%; max-width: 480px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            display: flex; flex-direction: column; 
            max-height: 90vh; /* Membatasi tinggi maksimal agar tidak tembus layar */
            overflow: hidden;
        }
        
        /* Kontainer khusus scrollable area internal modal */
        .modal-scroll-area {
            padding: 24px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .modal-header {
            display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;
        }
        .modal-close {
            background: none; border: none; font-size: 20px; cursor: pointer;
            color: var(--gray-400); line-height: 1; padding: 4px; border-radius: 6px;
            transition: background .15s, color .15s;
        }
        .modal-close:hover { background: var(--gray-100); color: var(--gray-900); }

        .text-orange { color: var(--primary); }
        .text-gray   { color: var(--gray-500); }

        /* Footer */
        .site-footer {
            background: var(--bg-nav); border-top: 1px solid var(--border);
            padding: 14px 32px;
            text-align: center;
            font-size: 12px; color: var(--gray-400); transition: background .25s;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar { display: none; }
            :root { --sidebar-w: 0px; }
            .main-content { padding: 16px; }
            .modal { max-height: 95vh; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ══════════════════════════════
     TOPNAV
══════════════════════════════ --}}
<nav class="topnav">

    {{-- Brand area (lebar = sidebar) --}}
    <div class="topnav-brand-area">
        <a href="{{ route('member.dashboard') }}" class="topnav-brand">
            <img src="{{ asset('images/logo_finic.png') }}" alt="UKM Finic">
            UKM Finic Rental
        </a>
    </div>

    {{-- Nama halaman aktif --}}
    <div class="topnav-page-title">
        @if(request()->routeIs('member.dashboard'))
            Beranda
        @elseif(request()->routeIs('member.equipment*'))
            Daftar Alat
        @elseif(request()->routeIs('member.returns*'))
            Pengembalian
        @elseif(request()->routeIs('member.history*'))
            Riwayat
        @elseif(request()->routeIs('member.profile*'))
            Profil Saya
        @elseif(request()->routeIs('member.notifications*'))
            Notifikasi
        @elseif(request()->routeIs('member.gearGuides*'))
            Panduan Alat
        @else
            @yield('title', 'UKM Finic')
        @endif
    </div>

    {{-- Kanan: Bell + User --}}
    <div class="topnav-right">

        {{-- Bell --}}
        <div class="dropdown-wrap">
            <button class="icon-btn" id="bell-btn" aria-label="Notifications">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
                    <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
                </svg>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="bell-badge"></span>
                @endif
            </button>

            <div class="dropdown-panel notif-panel" id="notif-panel">
                <div class="dropdown-header">Notifikasi</div>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    @foreach(auth()->user()->unreadNotifications as $notif)
                        <div class="notif-item">
                            <div class="ntitle">{{ __($notif->data['message']) }}</div>
                            <div class="ntime">{{ $notif->created_at->diffForHumans() }}</div>
                        </div>
                    @endforeach
                @else
                    <div class="notif-item">Tidak ada notifikasi baru</div>
                @endif
                <div class="dropdown-footer">
                    <a href="{{ route('member.notifications') }}">Lihat Semua Notifikasi</a>
                </div>
            </div>
        </div>

        <div class="topnav-divider"></div>

        {{-- Profile Dropdown --}}
        <div class="dropdown-wrap">
            <button class="user-pill" id="profile-btn">
                <div>
                    <div class="uname">{{ auth()->user()->name }}</div>
                    <div class="urole">
                        @if(auth()->user()->role === 'member') Anggota
                        @else {{ ucfirst(auth()->user()->role) }}
                        @endif
                    </div>
                </div>
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
                @else
                    <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                @endif
                <svg class="pill-chevron" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </button>

            <div class="dropdown-panel profile-panel" id="profile-panel">
                <div class="pp-top">
                    <div class="pp-avatar">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <div>
                        <div class="pp-name">{{ auth()->user()->name }}</div>
                        <div class="pp-role">
                            @if(auth()->user()->role === 'member')
                                Anggota
                            @else
                                {{ ucfirst(auth()->user()->role) }}
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Baris 1: Profil Saya --}}
                <div class="pp-menu">
                    <a href="{{ route('member.profile') }}" class="pp-item">
                        <span class="pp-item-left">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            Profil Saya
                        </span>
                    </a>
                </div>

                {{-- Baris 2: Panduan Peralatan --}}
                <div class="pp-menu">
                    <a href="{{ route('member.gearGuides') }}" class="pp-item">
                        <span class="pp-item-left">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/>
                                <path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/>
                            </svg>
                            Panduan Peralatan
                        </span>
                    </a>
                </div>

                {{-- Baris 3: Hubungi Admin --}}
                <div class="pp-menu">
                    <button class="pp-item" onclick="openModal('contactModal')">
                        <span class="pp-item-left">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="2" y="4" width="20" height="16" rx="2"/>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                            </svg>
                            Hubungi Admin
                        </span>
                    </button>
                </div>

                {{-- Baris 4: Keluar --}}
                <div class="pp-menu">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="pp-item danger">
                            <span class="pp-item-left">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                                    <polyline points="16 17 21 12 16 7"/>
                                    <line x1="21" y1="12" x2="9" y2="12"/>
                                </svg>
                                Keluar
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</nav>

{{-- ══════════════════════════════
     BODY: SIDEBAR + MAIN
══════════════════════════════ --}}
<div class="page-body">

    {{-- SIDEBAR KIRI --}}
    <aside class="sidebar">
        <div class="sidebar-section-label">Menu Utama</div>

        <a href="{{ route('member.dashboard') }}"
           class="sidebar-link {{ request()->routeIs('member.dashboard') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
            </svg>
            Beranda
        </a>

        <a href="{{ route('member.equipment.index') }}"
           class="sidebar-link {{ request()->routeIs('member.equipment*') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
                <circle cx="12" cy="13" r="4"/>
            </svg>
            Daftar Alat
        </a>

        <a href="{{ route('member.returns.index') }}"
           class="sidebar-link {{ request()->routeIs('member.returns*') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <polyline points="1 4 1 10 7 10"/>
                <path d="M3.51 15a9 9 0 1 0 .49-3.45"/>
            </svg>
            Pengembalian
            @php
                $activeReturnCount = \App\Models\Borrowing::where('user_id', auth()->id())
                    ->whereIn('status', ['approved','borrowed'])
                    ->where('return_status', 'none')
                    ->count();
            @endphp
            @if($activeReturnCount > 0)
                <span class="sidebar-badge">{{ $activeReturnCount }}</span>
            @endif
        </a>

        <a href="{{ route('member.history.index') }}"
           class="sidebar-link {{ request()->routeIs('member.history*') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12,6 12,12 16,14"/>
            </svg>
            Riwayat
        </a>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="main-content">

        @if(session('success'))
            <div class="alert alert-success">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">✗ {{ session('error') }}</div>
        @endif

        @yield('content')
    </div>

</div>

<footer class="site-footer">
    © 2026 UKM Finic Rental. Hak cipta dilindungi undang-undang.
</footer>

{{-- ══════════════════════════════
     CONTACT ADMIN MODAL (ELEGANT & SCROLLABLE FIX)
══════════════════════════════ --}}
<div class="modal-overlay" id="contactModal">
    <div class="modal">

        {{-- Sticky Elegant Header --}}
        <div style="background: linear-gradient(135deg, #059669, #047857); padding: 20px 24px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(0,0,0,0.06); flex-shrink: 0;">
            <div>
                <p style="font-size: 10px; color: #a7f3d0; margin: 0 0 2px; letter-spacing: .08em; text-transform: uppercase; font-weight: 700;">UKM Finic Rental</p>
                <h3 style="font-size: 18px; font-weight: 700; color: #fff; margin: 0;">Hubungi Admin</h3>
            </div>
            <button class="modal-close" onclick="closeModal('contactModal')" style="background: rgba(255,255,255,0.18); color: #fff; border-radius: 50%; font-size: 14px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; transition: all 0.2s; border: none;">✕</button>
        </div>

        {{-- Scrollable Container Internal --}}
        <div class="modal-scroll-area">

            {{-- Alert sukses (Ditambahkan class "alert alert-success" supaya hilang otomatis oleh JS) --}}
            @if(session('contact_success'))
                <div class="alert alert-success" style="display: flex; align-items: flex-start; gap: 10px; padding: 12px 14px; margin-bottom: 0; font-size: 13px; font-weight: 500;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="flex-shrink: 0; margin-top: 1px;"><polyline points="20 6 9 17 4 12"/></svg>
                    {{ session('contact_success') }}
                </div>
            @endif

            {{-- Info Pengirim Akun Login --}}
            <div style="display: flex; align-items: center; gap: 12px; padding: 12px; background: var(--gray-100); border: 1px solid var(--border); border-radius: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 50%; background: #059669; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 15px; flex-shrink: 0; box-shadow: 0 2px 4px rgba(5,150,105,0.15);">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div style="flex: 1; min-width: 0;">
                    <p style="font-size: 13.5px; font-weight: 700; color: var(--text-main); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ auth()->user()->name }}</p>
                    <p style="font-size: 12px; color: #059669; margin: 2px 0 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-weight: 500;">{{ auth()->user()->email }}</p>
                </div>
                <div style="font-size: 10px; color: var(--gray-400); text-align: right; line-height: 1.4; font-weight: 500; background: var(--bg-card); padding: 4px 8px; border-radius: 6px; border: 1px solid var(--border);">
                    Akun<br>Aktif
                </div>
            </div>

            {{-- Form Kirim Pesan --}}
            <form method="POST" action="{{ route('member.contact.send') }}" id="contactForm" style="display: flex; flex-direction: column; gap: 14px;">
                @csrf

                <div>
                    <label style="font-size: 11px; font-weight: 700; color: var(--gray-500); text-transform: uppercase; letter-spacing: .06em; display: block; margin-bottom: 6px;">Subjek Pesan</label>
                    <input type="text" name="subject" required maxlength="100"
                           placeholder="Contoh: Pertanyaan konfirmasi peminjaman"
                           style="width: 100%; padding: 11px 14px; border: 1.5px solid var(--border); border-radius: 10px; font-size: 13.5px; color: var(--text-main); background: var(--bg-card); box-sizing: border-box; transition: all .2s; font-family: inherit; outline: none;"
                           onfocus="this.style.borderColor='#059669'; this.style.boxShadow='0 0 0 3px rgba(5,150,105,0.15)'" onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
                </div>

                <div>
                    <label style="font-size: 11px; font-weight: 700; color: var(--gray-500); text-transform: uppercase; letter-spacing: .06em; display: block; margin-bottom: 6px;">Isi Pesan</label>
                    <textarea name="message" required maxlength="2000" rows="4"
                              placeholder="Tulis detail masalah atau pertanyaan kamu di sini..."
                              style="width: 100%; padding: 11px 14px; border: 1.5px solid var(--border); border-radius: 10px; font-size: 13.5px; color: var(--text-main); background: var(--bg-card); box-sizing: border-box; resize: vertical; transition: all .2s; font-family: inherit; outline: none; min-height: 80px;"
                              onfocus="this.style.borderColor='#059669'; this.style.boxShadow='0 0 0 3px rgba(5,150,105,0.15)'" onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"></textarea>
                </div>

                <button type="submit"
                        style="width: 100%; padding: 12px; background: #059669; color: #fff; border: none; border-radius: 10px; font-size: 14px; font-weight: 700; cursor: pointer; transition: background .2s, transform 0.1s; font-family: inherit; display: flex; align-items: center; justify-content: center; gap: 6px; box-shadow: 0 4px 12px rgba(5,150,105,0.15);"
                        onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#059669'"
                        onmousedown="this.style.transform='scale(0.99)'" onmouseup="this.style.transform='scale(1)'">
                    Kirim Pesan ke Admin
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                </button>
            </form>

            {{-- Alternatif Hubungi Langsung --}}
            <div style="border-top: 1px dashed var(--border); padding-top: 16px; display: flex; flex-direction: column; gap: 10px;">
                <p style="font-size: 11px; color: var(--gray-400); margin: 0; font-weight: 700; text-transform: uppercase; letter-spacing: .06em;">Atau Hubungi Langsung</p>

                <a href="https://wa.me/6285823203030" target="_blank"
                   style="display: flex; align-items: center; gap: 12px; padding: 11px 14px; border-radius: 10px; border: 1px solid var(--border); text-decoration: none; background: var(--bg-page); transition: all 0.2s;"
                   onmouseover="this.style.background='var(--gray-100)'; this.style.borderColor='#16a34a';" onmouseout="this.style.background='var(--bg-page)'; this.style.borderColor='var(--border)';">
                    <div style="width: 32px; height: 32px; border-radius: 8px; background: #dcfce7; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#16a34a"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.125.558 4.12 1.533 5.851L0 24l6.335-1.508A11.954 11.954 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 0 1-5.006-1.371l-.36-.214-3.76.895.952-3.667-.234-.375A9.818 9.818 0 0 1 2.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/></svg>
                    </div>
                    <div style="flex: 1;">
                        <p style="font-size: 11px; color: var(--gray-400); margin: 0; font-weight: 500;">Fast Response via WhatsApp</p>
                        <p style="font-size: 13.5px; font-weight: 700; color: #16a34a; margin: 1px 0 0;">+62 858-2320-3030</p>
                    </div>
                    <svg style="color: var(--gray-400);" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>

                <div style="display: flex; align-items: flex-start; gap: 12px; padding: 11px 14px; border-radius: 10px; border: 1px solid var(--border); background: var(--bg-page);">
                    <div style="width: 32px; height: 32px; border-radius: 8px; background: #fef9c3; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#a16207" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <div>
                        <p style="font-size: 11px; color: var(--gray-400); margin: 0; font-weight: 500;">Lokasi Sekretariat</p>
                        <p style="font-size: 13.5px; font-weight: 700; color: var(--text-main); margin: 1px 0 0;">Kampus 2, Gedung J</p>
                        <p style="font-size: 12px; color: var(--gray-500); margin: 2px 0 0; font-weight: 500;">Sayap Kanan · Sekretariat Finic</p>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 8px; padding: 10px 12px; border-radius: 8px; background: #eff6ff; border: 1px solid #bfdbfe;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2.5" style="flex-shrink: 0;"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                    <p style="font-size: 11.5px; color: #1e40af; margin: 0; font-weight: 600;">Jam operasional: Senin–Jumat, 08.00–16.00 WIB</p>
                </div>
            </div>

        </div>
    </div>
</div>

@if(session('contact_success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        openModal('contactModal');
    });
</script>
@endif

{{-- How to Borrow Modal --}}
<div class="modal-overlay" id="helpModal">
    <div class="modal">
        <div style="padding: 24px;">
            <div class="modal-header">
                <h3 style="font-size:16px; font-weight:700; color:var(--gray-900);">📋 Cara Peminjaman Alat</h3>
                <button class="modal-close" onclick="closeModal('helpModal')">✕</button>
            </div>
            <ol style="padding-left:18px; display:flex; flex-direction:column; gap:10px; font-size:14px; color:var(--gray-700); line-height:1.6;">
                <li>Buka halaman <strong>Daftar Alat</strong> dan pilih peralatan yang ingin dipinjam.</li>
                <li>Klik tombol <strong>Pinjam</strong> lalu isi tanggal pinjam dan tanggal kembali.</li>
                <li>Tunggu persetujuan admin. Status akan berubah menjadi <strong>Disetujui</strong>.</li>
                <li>Ambil peralatan dari tempat penyimpanan sesuai jadwal yang disepakati.</li>
                <li>Kembalikan peralatan tepat waktu untuk menjaga <strong>skor kepercayaan</strong> kamu.</li>
            </ol>
            <div style="margin-top:20px; padding-top:16px; border-top:1px solid var(--border); display:flex; justify-content:flex-end;">
                <button onclick="closeModal('helpModal')"
                        style="padding:9px 20px; border-radius:8px; border:none; background:var(--primary); color:#fff; font-weight:600; font-size:13px; cursor:pointer;">
                    Mengerti
                </button>
            </div>
        </div>
    </div>
</div>

<script>
/* ── Modals ── */
function openModal(id) {
    const el = document.getElementById(id);
    if (el) { el.classList.add('open'); document.body.style.overflow = 'hidden'; }
}
function closeModal(id) {
    const el = document.getElementById(id);
    if (el) { el.classList.remove('open'); document.body.style.overflow = ''; }
}
document.querySelectorAll('.modal-overlay').forEach(el => {
    el.addEventListener('click', e => { if (e.target === el) closeModal(el.id); });
});

/* ── Dropdown Klik Handler ── */
document.addEventListener('DOMContentLoaded', function() {
    const bellBtn = document.getElementById('bell-btn');
    const notifPanel = document.getElementById('notif-panel');
    const profileBtn = document.getElementById('profile-btn');
    const profilePanel = document.getElementById('profile-panel');

    // Klik Lonceng Notifikasi
    if (bellBtn && notifPanel) {
        bellBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = notifPanel.classList.contains('open');
            closeAllDropdowns();
            if (!isOpen) {
                notifPanel.classList.add('open');
            }
        });
    }

    // Klik Avatar Profil Pojok Kanan
    if (profileBtn && profilePanel) {
        profileBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = profilePanel.classList.contains('open');
            closeAllDropdowns();
            if (!isOpen) {
                profilePanel.classList.add('open');
                profileBtn.classList.add('open');
            }
        });
    }

    // Mencegah penutupan ketika mengklik bagian dalam panel dropdown
    profilePanel?.addEventListener('click', e => e.stopPropagation());
    notifPanel?.addEventListener('click', e => e.stopPropagation());

    // Fungsi Reset Penutup Semua Menu Dropdown
    function closeAllDropdowns() {
        notifPanel?.classList.remove('open');
        profilePanel?.classList.remove('open');
        profileBtn?.classList.remove('open');
    }

    // Tutup Otomatis Jika Klik di Sembarang Tempat Luar Menu
    document.addEventListener('click', closeAllDropdowns);
});

/* ── Auto-dismiss alerts ── */
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(el => {
        el.style.transition = 'opacity .4s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 400);
    });
}, 4000);

/* ── data-edit buttons ── */
document.querySelectorAll('[data-edit]').forEach(btn => {
    btn.addEventListener('click', () => {
        const data  = JSON.parse(btn.dataset.edit);
        const modal = document.getElementById(btn.dataset.modal || 'editModal');
        if (!modal) return;
        Object.entries(data).forEach(([k, v]) => {
            const el = modal.querySelector('[name="' + k + '"]');
            if (el) el.value = v;
        });
        if (btn.dataset.action) {
            const form = document.getElementById('editForm');
            if (form) form.action = btn.dataset.action;
        }
        openModal(btn.dataset.modal || 'editModal');
    });
});

/* ── Ringkasan tanggal pinjam ── */
const bd = document.getElementById('borrow_date');
const rd = document.getElementById('return_date');
const rs = document.getElementById('rental_summary');
if (bd && rd && rs) {
    function updateSummary() {
        const b = new Date(bd.value), r = new Date(rd.value);
        if (b && r && r > b) {
            const d = Math.round((r - b) / 86400000);
            rs.innerHTML = 'Kamu mengajukan peminjaman alat ini selama <strong>' + d + ' hari</strong>. Pastikan kamu mengembalikan alat paling lambat jam <strong>17:00 WIB...';
        }
    }
    bd.addEventListener('change', updateSummary);
    rd.addEventListener('change', updateSummary);
}
</script>
@stack('scripts')
</body>
</html>