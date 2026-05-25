{{--
    resources/views/admin/partials/notification-dropdown.blade.php
    @include('admin.partials.notification-dropdown')
    
    Versi baru: bell button hanya sebagai link ke halaman /admin/notifications
    Panel dropdown dihapus, diganti halaman penuh.
--}}

<a href="{{ route('admin.notifications.page') }}" class="notif-bell" id="notifBell" aria-label="Notifikasi">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
    </svg>
    <span class="notif-count" id="notifCount" style="display:none;">0</span>
</a>

<style>
.notif-bell {
    position: relative;
    width: 40px; height: 40px;
    border-radius: 50%;
    border: none;
    background: #f0f6f0;
    color: #1a6b1a;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s, transform 0.15s;
    text-decoration: none;
}
.notif-bell:hover {
    background: #dcfce7;
    transform: scale(1.07);
}
.notif-bell.has-unread {
    animation: bellRing 3s ease-in-out infinite;
}
@keyframes bellRing {
    0%,85%,100% { transform: rotate(0); }
    88%          { transform: rotate(-12deg); }
    92%          { transform: rotate(12deg); }
    96%          { transform: rotate(-8deg); }
}
.notif-count {
    position: absolute; top: 2px; right: 2px;
    min-width: 18px; height: 18px; padding: 0 4px;
    background: #ef4444; color: #fff;
    font-size: 0.65rem; font-weight: 700;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    border: 2px solid #fff;
    box-sizing: border-box; line-height: 1;
}
</style>

<script>
(function () {
    function updateBadge(count) {
        const badge = document.getElementById('notifCount');
        const bell  = document.getElementById('notifBell');
        if (!badge || !bell) return;
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.style.display = 'flex';
            bell.classList.add('has-unread');
        } else {
            badge.style.display = 'none';
            bell.classList.remove('has-unread');
        }
    }

    function pollUnread() {
        fetch('/admin/notifications/unread-count', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(d => updateBadge(d.count || 0))
        .catch(() => {});
    }

    document.addEventListener('DOMContentLoaded', function () {
        pollUnread();
        setInterval(pollUnread, 30000);
    });
}());
</script>