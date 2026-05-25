// ===== UKM FINIC - Main JS =====

// Modal helpers
function openModal(id) {
    document.getElementById(id).classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById(id).classList.remove('open');
    document.body.style.overflow = '';
}
// Close modal on overlay click
document.querySelectorAll('.modal-overlay').forEach(el => {
    el.addEventListener('click', e => { if (e.target === el) closeModal(el.id); });
});

// Tab switcher
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const group = btn.closest('.tabs');
        group.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const target = btn.dataset.tab;
        if (target) {
            document.querySelectorAll('.tab-panel').forEach(p => p.style.display = 'none');
            const panel = document.getElementById(target);
            if (panel) panel.style.display = '';
        }
    });
});

// Notification panel toggle
const bellBtn = document.getElementById('bell-btn');
const notifPanel = document.getElementById('notif-panel');
if (bellBtn && notifPanel) {
    bellBtn.addEventListener('click', e => {
        e.stopPropagation();
        notifPanel.classList.toggle('open');
    });
    document.addEventListener('click', () => notifPanel.classList.remove('open'));
}

// Flash auto-dismiss
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(el => {
        el.style.transition = 'opacity .4s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 400);
    });
}, 4000);

// Confirm delete
document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', e => {
        if (!confirm(el.dataset.confirm || 'Are you sure?')) e.preventDefault();
    });
});

// Populate edit modal dynamically
document.querySelectorAll('[data-edit]').forEach(btn => {
    btn.addEventListener('click', () => {
        const data = JSON.parse(btn.dataset.edit);
        const modalId = btn.dataset.modal || 'editModal';
        const modal = document.getElementById(modalId);
        if (!modal) return;
        Object.entries(data).forEach(([key, val]) => {
            const el = modal.querySelector(`[name="${key}"]`);
            if (el) el.value = val;
        });
        openModal(modalId);
    });
});

// Borrow date: auto-calculate rental summary
const borrowDate = document.getElementById('borrow_date');
const returnDate = document.getElementById('return_date');
const rentalSummary = document.getElementById('rental_summary');
if (borrowDate && returnDate && rentalSummary) {
    function updateSummary() {
        const b = new Date(borrowDate.value);
        const r = new Date(returnDate.value);
        if (b && r && r > b) {
            const days = Math.round((r - b) / 86400000);
            rentalSummary.innerHTML = `You are requesting this equipment for <strong>${days} day${days > 1 ? 's' : ''}</strong>. Please ensure you return the gear by <strong>5:00 PM</strong> on the return date.`;
        }
    }
    borrowDate.addEventListener('change', updateSummary);
    returnDate.addEventListener('change', updateSummary);
}

// Image preview before upload
document.querySelectorAll('input[type="file"][data-preview]').forEach(input => {
    input.addEventListener('change', () => {
        const preview = document.getElementById(input.dataset.preview);
        if (!preview || !input.files[0]) return;
        preview.src = URL.createObjectURL(input.files[0]);
    });
});