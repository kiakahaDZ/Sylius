/*
 * PrinterTheme - Scroll Reveal Stimulus Controller
 * Animates elements with data-reveal attribute when they enter the viewport.
 * Elements already visible on page load are revealed immediately.
 */

import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        const elements = Array.from(document.querySelectorAll('[data-reveal]'));

        this._observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        this._reveal(entry.target);
                        this._observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.05, rootMargin: '0px 0px -30px 0px' }
        );

        // Immediately reveal elements already in the viewport (e.g. on page load)
        // so they don't stay invisible when IntersectionObserver fires too late.
        elements.forEach((el) => {
            const rect = el.getBoundingClientRect();
            const alreadyVisible = rect.top < window.innerHeight && rect.bottom > 0;
            if (alreadyVisible) {
                this._reveal(el);
            } else {
                this._observer.observe(el);
            }
        });
    }

    _reveal(el) {
        el.classList.add('revealed');
        el.querySelectorAll('.stagger-children > *').forEach((child) => {
            child.classList.add('revealed');
        });
    }

    disconnect() {
        if (this._observer) {
            this._observer.disconnect();
        }
    }
}
