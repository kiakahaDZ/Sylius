/*
 * PrinterTheme - Scroll Reveal Stimulus Controller
 * Animates elements with data-reveal attribute when they enter the viewport.
 */

import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this._observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                        // Also reveal stagger-children items
                        entry.target.querySelectorAll('.stagger-children > *').forEach((child) => {
                            child.classList.add('revealed');
                        });
                        this._observer.unobserve(entry.target);
                    }
                });
            },
            {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px',
            }
        );

        document.querySelectorAll('[data-reveal]').forEach((el) => {
            this._observer.observe(el);
        });
    }

    disconnect() {
        if (this._observer) {
            this._observer.disconnect();
        }
    }
}
