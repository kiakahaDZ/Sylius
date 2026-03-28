/*
 * PrinterTheme – CSS-only entry point.
 *
 * All Stimulus controllers are imported and registered by the core Sylius
 * shop entry (node_modules/@sylius-ui/shop/Resources/assets/app.js).
 * Do NOT start a second Stimulus app here — it causes every controller
 * (including the built-in add-to-cart) to fire twice, doubling quantities.
 */
import './styles/main.scss';