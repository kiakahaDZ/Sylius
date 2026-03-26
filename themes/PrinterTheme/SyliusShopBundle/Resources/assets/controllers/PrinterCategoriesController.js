import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['scroll'];

    connect() {
        console.log('PrinterCategoriesController connected');
        this.scrollAmount = 500;
    }

    getContainer() {
        if (this.hasScrollTarget) {
            return this.scrollTarget;
        }
        return this.element.querySelector('.printer-categories__scroll, .printer-taxon-slider__track, .printer-products-scroll') || this.element;
    }

    next(event) {
        if (event) event.preventDefault();
        const container = this.getContainer();
        console.log('Next clicked', container);
        if (container) {
            if (typeof container.scrollBy === 'function') {
                container.scrollBy({ left: this.scrollAmount, behavior: 'smooth' });
            } else {
                container.scrollLeft += this.scrollAmount;
            }
        }
    }

    prev(event) {
        if (event) event.preventDefault();
        const container = this.getContainer();
        console.log('Prev clicked', container);
        if (container) {
            if (typeof container.scrollBy === 'function') {
                container.scrollBy({ left: -this.scrollAmount, behavior: 'smooth' });
            } else {
                container.scrollLeft -= this.scrollAmount;
            }
        }
    }
}
