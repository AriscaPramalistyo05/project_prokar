/**
 * Opening Animation — Vanilla JS
 * ponytail: self-contained, no external deps. Reads config from <script id="opening-config">.
 */
(function () {
    'use strict';

    // ── Config ──
    const cfgEl = document.getElementById('opening-config');
    if (!cfgEl) return; // safety: no config = no animation

    let config;
    try {
        config = JSON.parse(cfgEl.textContent);
    } catch (e) {
        console.error('Invalid opening config JSON', e);
        return;
    }

    const { text_color, bg_color, accent_color, fade_duration, show_once, messages } = config;

    // ── Check sessionStorage (show_once) ──
    const alreadyPlayed = show_once && (() => {
        try {
            return sessionStorage.getItem('prokar_opening_played') === '1';
        } catch (e) {
            return false; // private mode / storage blocked
        }
    })();

    if (alreadyPlayed) {
        document.getElementById('opening-overlay')?.classList.add('is-hidden');
        return;
    }

    // ── DOM refs ──
    const overlay = document.getElementById('opening-overlay');
    if (!overlay) return;

    const msgContainer = document.getElementById('opening-messages');
    if (!msgContainer) return;

    // ── Apply dynamic styles via CSS vars ──
    overlay.style.setProperty('--opening-text', text_color || '#ffffff');
    overlay.style.setProperty('--opening-bg', bg_color || '#000000');
    overlay.style.setProperty('--opening-accent', accent_color || '#fecb00');

    // ── Render messages ──
    messages.forEach((msg, i) => {
        const el = document.createElement('div');
        el.className = 'opening-msg' + (window.matchMedia('(prefers-reduced-motion: reduce)').matches ? ' is-active' : '');
        el.dataset.index = String(i);

        // Accessibility: hide inactive messages from screen readers
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

    // ── Reduced motion → skip animation ──
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        overlay.classList.add('is-hidden');
        if (show_once) markPlayed();
        return;
    }

    // ── Play sequence ──
    let current = 0;
    const total = messages.length;
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
        if (current < total) {
            showIndex(current);
            const duration = messages[current]?.duration || 1900;
            current++;
            setTimeout(next, duration);
        } else {
            // Reveal
            overlay.classList.add('is-hidden');
            if (show_once) markPlayed();
        }
    }

    function markPlayed() {
        try {
            sessionStorage.setItem('prokar_opening_played', '1');
        } catch (e) { /* ignore */ }
    }

    // Start
    next();

    // ── Helpers ──
    function escapeHtml(str) {
        if (!str) return '';
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }
})();
