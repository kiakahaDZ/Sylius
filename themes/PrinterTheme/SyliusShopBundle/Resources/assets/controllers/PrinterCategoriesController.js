/*
 * PrinterTheme - Categories Scroll Stimulus Controller
 * Handles horizontal scrolling of category cards with prev/next buttons.
 */

import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['scroll'];

    connect() {
        this._scrollContainer = this.hasScrollTarget ? this.scrollTarget : this.element;
    }

    prev() {
        this._scroll(-300);
    }

    next() {
        this._scroll(300);
    }

    _scroll(amount) {
        if (this._scrollContainer) {
            this._scrollContainer.scrollBy({
                left: amount,
                behavior: 'smooth',
            });
        }
    }
}
