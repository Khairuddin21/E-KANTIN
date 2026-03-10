/* ================================================
   Dashboard JavaScript — E-Canteen
   ================================================ */

// Sidebar Toggle (Mobile)
function toggleSidebar() {
    var sidebar = document.getElementById('sidebar');
    var overlay = document.getElementById('sidebarOverlay');

    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}

// Auto-dismiss toasts
document.addEventListener('DOMContentLoaded', function () {
    var toasts = document.querySelectorAll('.toast-notification');
    toasts.forEach(function (toast) {
        setTimeout(function () {
            toast.style.display = 'none';
        }, 5000);
    });
});

// Update sidebar cart badge in real-time
function updateSidebarCartBadge(count) {
    var badge = document.getElementById('sidebar-cart-badge');
    if (!badge) return;
    if (count > 0) {
        badge.textContent = count;
        badge.style.display = 'flex';
    } else {
        badge.style.display = 'none';
        badge.textContent = '';
    }
}
