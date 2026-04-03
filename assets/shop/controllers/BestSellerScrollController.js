import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['container', 'scroll'];

    connect() {
        console.log('BestSellerScrollController connected');
        this.scrollAmount = 350;
    }

    getContainer() {
        if (this.hasContainerTarget) {
            return this.containerTarget;
        }
        if (this.hasScrollTarget) {
            return this.scrollTarget;
        }
        return this.element.querySelector('.printer-best-sellers__scroll') || this.element;
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
