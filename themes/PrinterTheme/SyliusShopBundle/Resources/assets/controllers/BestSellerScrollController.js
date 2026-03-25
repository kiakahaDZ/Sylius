import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['container', 'scroll'];

    connect() {
        console.log('BestSellerScrollController connected');
        this.scrollAmount = 350;
        this.scrollContainer = this.hasContainerTarget ? this.containerTarget : (this.hasScrollTarget ? this.scrollTarget : this.element.querySelector('.printer-best-sellers__scroll'));
    }

    next(event) {
        if (event) event.preventDefault();
        if (this.scrollContainer) {
            this.scrollContainer.scrollBy({ left: this.scrollAmount, behavior: 'smooth' });
        }
    }

    prev(event) {
        if (event) event.preventDefault();
        if (this.scrollContainer) {
            this.scrollContainer.scrollBy({ left: -this.scrollAmount, behavior: 'smooth' });
        }
    }
}
