/**
 * Admin panel JS entry — Prokar Elektronik
 * Livewire 3 bundle otomatis memuat Alpine.js via @livewireScripts di layout.
 */

// Pastikan semua link di sidebar (termasuk tombol Kecilkan Menu) memiliki atribut title
// agar browser menampilkan box tooltip informatif saat sidebar dalam kondisi collapsed
function initSidebarTooltips() {
    document.querySelectorAll('.drawer-side .menu li a, .drawer-side .menu li button').forEach(el => {
        if (!el.getAttribute('title')) {
            const hideable = el.querySelector('.mary-hideable');
            const text = hideable ? hideable.textContent.trim() : el.textContent.trim();
            if (text) {
                el.setAttribute('title', text);
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', initSidebarTooltips);
document.addEventListener('livewire:navigated', initSidebarTooltips);
window.addEventListener('sidebar-toggled', () => setTimeout(initSidebarTooltips, 50));
