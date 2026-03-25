import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['scroll'];

    connect() {
        console.log('PrinterCategoriesController connected');
        this.scrollAmount = 500;
        this.container = this.hasScrollTarget ? this.scrollTarget : this.element.querySelector('.printer-categories__scroll, .printer-taxon-slider__track, .printer-products-scroll');
    }

    next(event) {
        if (event) event.preventDefault();
        console.log('Next clicked', this.container);
        if (this.container) {
            this.container.scrollBy({ left: 600, behavior: 'smooth' });
        }
    }

    prev(event) {
        if (event) event.preventDefault();
        console.log('Prev clicked', this.container);
        if (this.container) {
            this.container.scrollBy({ left: -600, behavior: 'smooth' });
        }
    }
}
