import { Controller } from '@hotwired/stimulus';

/*
 * PrinterTheme - Page Transition & Preloader Controller
 * Manages the "first load" splash screen and transitions between pages.
 */
export default class extends Controller {
    static targets = [ 'loader', 'progressBar' ];

    connect() {
        console.log('PrinterTransitionController: connecting...');
        window.printerTransition = this;
        
        // Initial page load reveal
        this.revealPage();

        // Listen for internal navigation to show loader again
        this.setupNavigationInterceptors();
    }

    revealPage() {
        console.log('PrinterTransitionController: revealing page...');
        
        // Brute force check to ensure loader disappears
        const hideLoader = () => {
            console.log('PrinterTransitionController: executing hideLoader');
            const loader = document.getElementById('printer-loader');
            if (loader) {
                loader.classList.add('loader-hidden');
                console.log('PrinterTransitionController: loader-hidden class added');
            } else {
                console.error('PrinterTransitionController: FAILED to find #printer-loader by ID');
            }
        };

        // Run once immediately to check if we can catch it
        // And then again after a short delay for the "shiny" effect
        if (document.readyState === 'complete') {
            setTimeout(hideLoader, 600);
        } else {
            window.addEventListener('load', () => setTimeout(hideLoader, 600));
            // Safety fallback in case 'load' already fired or stays stuck
            setTimeout(hideLoader, 2000);
        }
    }

    prepareForNewPage() {
        if (this.hasLoaderTarget) {
            this.loaderTarget.classList.remove('loader-hidden');
            this.loaderTarget.style.opacity = '1';
            this.loaderTarget.style.visibility = 'visible';
        }
    }

    setupNavigationInterceptors() {
        // Use event delegation for better performance and to handle dynamic links
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (!link) return;

            const url = link.getAttribute('href');
            if (this.isInternalLink(link)) {
                // Ignore special cases
                if (e.metaKey || e.ctrlKey || (url && url.startsWith('#')) || link.getAttribute('download') !== null || link.getAttribute('target') === '_blank') {
                    return;
                }

                console.log('PrinterTransitionController: internal navigation triggered, showing loader');
                this.prepareForNewPage();
            }
        });

        // Also handle standard form submissions (e.g. search, add to cart that isn't AJAX)
        document.addEventListener('submit', (e) => {
            const form = e.target;
            // Only for standard navigation forms
            if (form.getAttribute('target') !== '_blank') {
                console.log('PrinterTransitionController: form submission triggered, showing loader');
                this.prepareForNewPage();
            }
        });
    }

    isInternalLink(link) {
        const href = link.getAttribute('href');
        if (!href) return false;
        if (href.startsWith('#')) return false;
        
        // Relative paths like /shop/products are internal
        if (href.startsWith('/') && !href.startsWith('//')) return true;

        // Same domain is internal
        try {
            const url = new URL(href, window.location.origin);
            return url.origin === window.location.origin;
        } catch (e) {
            return false;
        }
    }
}
