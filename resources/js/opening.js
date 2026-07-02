/**
 * Opening Animation — Vanilla JS
 * ponytail: self-contained, no external deps.
 */
(function () {
    'use strict';

    const cfgEl = document.getElementById('opening-config');
    if (!cfgEl) return;

    let config;
    try {
        config = JSON.parse(cfgEl.textContent);
    } catch (e) {
        console.error('Invalid opening config', e);
        return;
    }

    const { text_color, bg_color, accent_color, show_once, messages } = config;
    const overlay = document.getElementById('opening-overlay');
    if (!overlay) return;

    // Always auto-hide after 5s no matter what
    const safety = setTimeout(() => overlay.classList.add('is-hidden'), 5000);

    // Check localStorage (cross-session, unlike sessionStorage)
    let alreadyPlayed = false;
    if (show_once) {
        try {
            alreadyPlayed = localStorage.getItem('prokar_opening_played') === '1';
        } catch (e) { /* private mode */ }
    }

    if (alreadyPlayed) {
        overlay.classList.add('is-hidden');
        clearTimeout(safety);
        return;
    }

    const msgContainer = document.getElementById('opening-messages');
    if (!msgContainer) return;

    // Apply dynamic styles
    overlay.style.setProperty('--opening-text', text_color || '#ffffff');
    overlay.style.setProperty('--opening-bg', bg_color || '#000000');
    overlay.style.setProperty('--opening-accent', accent_color || '#fecb00');

    // Render messages
    messages.forEach((msg) => {
        const el = document.createElement('div');
        el.className = 'opening-msg';
        el.setAttribute('aria-hidden', 'true');

        if (msg.type === 'line') {
            el.innerHTML = `<p class="font-body-md text-[var(--opening-text)] text-xl sm:text-2xl md:text-3xl font-bold leading-snug text-center px-4">${escapeHtml(msg.text)}</p>`;
        } else if (msg.type === 'logo') {
            el.innerHTML = `
                <div class="flex flex-col items-center gap-2">
                    <span class="font-brand-display font-black uppercase tracking-tight text-[var(--opening-accent)] text-4xl sm:text-5xl md:text-6xl logo-border px-6 py-3">${escapeHtml(msg.text)}</span>
                    <span class="font-label-mono text-[11px] uppercase tracking-[0.3em] text-tertiary-fixed-dim mt-2">Mlonggo · Jepara</span>
                </div>
            `;
        }
        msgContainer.appendChild(el);
    });

    // Reduced motion → skip
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        hideAndSave(overlay, safety);
        return;
    }

    // Play sequence
    let current = 0;
    const msgEls = msgContainer.querySelectorAll('.opening-msg');

    function showIndex(i) {
        msgEls.forEach((el, idx) => {
            if (idx === i) {
                el.classList.add('is-active');
                el.removeAttribute('aria-hidden');
            } else {
                el.classList.remove('is-active');
                el.setAttribute('aria-hidden', 'true');
            }
        });
    }

    function next() {
        if (current < messages.length) {
            showIndex(current);
            const duration = messages[current]?.duration || 900;
            current++;
            setTimeout(next, duration);
        } else {
            hideAndSave(overlay, safety);
        }
    }

    next();

    // ── Helpers ──
    function hideAndSave(overlayEl, timer) {
        clearTimeout(timer);
        overlayEl.classList.add('is-hidden');
        try {
            localStorage.setItem('prokar_opening_played', '1');
        } catch (e) { /* ignore */ }
    }

    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
})();
