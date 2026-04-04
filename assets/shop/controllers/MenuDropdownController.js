import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static values = {
        delay: { type: Number, default: 150 },
    };

    connect() {
        this.timeout = null;
    }

    show(event) {
        clearTimeout(this.timeout);
        const dropdownMenu = this.element.closest('.dropdown')?.querySelector(':scope > .dropdown-menu')
            ?? this.element.nextElementSibling;
        
        this.element.setAttribute('aria-expanded', 'true');
        this.element.closest('.dropdown')?.classList.add('show');
        dropdownMenu?.classList.add('show');
    }

    hide(event) {
        this.timeout = setTimeout(() => {
            const dropdownMenu = this.element.closest('.dropdown')?.querySelector(':scope > .dropdown-menu')
                ?? this.element.nextElementSibling;
            
            this.element.setAttribute('aria-expanded', 'false');
            this.element.closest('.dropdown')?.classList.remove('show');
            dropdownMenu?.classList.remove('show');
        }, this.delayValue);
    }
}
