/*
 * PrinterTheme - Hero Slider Stimulus Controller
 * Handles automatic slide rotation, prev/next arrows, and dot navigation.
 */

import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['slide'];

    static values = {
        interval: { type: Number, default: 5000 },
    };

    connect() {
        this._current = 0;
        this._total = this.slideTargets.length;
        this._startAutoplay();
    }

    disconnect() {
        this._stopAutoplay();
    }

    next() {
        this._goTo((this._current + 1) % this._total);
    }

    prev() {
        this._goTo((this._current - 1 + this._total) % this._total);
    }

    goTo(event) {
        const index = parseInt(event.currentTarget.dataset.index, 10);
        if (!isNaN(index)) {
            this._goTo(index);
        }
    }

    // =========================================
    // Private
    // =========================================

    _goTo(index) {
        const slides = this.slideTargets;
        const dots = this.element.querySelectorAll('.printer-hero__dot');

        slides[this._current]?.classList.remove('active');
        dots[this._current]?.classList.remove('active');
        dots[this._current]?.setAttribute('aria-selected', 'false');

        this._current = index;

        slides[this._current]?.classList.add('active');
        dots[this._current]?.classList.add('active');
        dots[this._current]?.setAttribute('aria-selected', 'true');

        // Reset autoplay timer on manual navigation
        this._stopAutoplay();
        this._startAutoplay();
    }

    _startAutoplay() {
        if (this._total <= 1) {
            return;
        }
        this._timer = setInterval(() => this.next(), this.intervalValue);
    }

    _stopAutoplay() {
        if (this._timer) {
            clearInterval(this._timer);
            this._timer = null;
        }
    }
}
