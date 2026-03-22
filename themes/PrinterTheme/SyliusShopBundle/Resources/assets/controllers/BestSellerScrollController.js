import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['scroll'];

    connect() {
        this.scrollAmount = 350;
    }

    next() {
        this.scrollTarget.scrollBy({ left: this.scrollAmount, behavior: 'smooth' });
    }

    prev() {
        this.scrollTarget.scrollBy({ left: -this.scrollAmount, behavior: 'smooth' });
    }
}
