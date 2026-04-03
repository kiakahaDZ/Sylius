/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import '@sylius/shop-bundle/entrypoint';
import { startStimulusApp } from '@symfony/stimulus-bridge';

console.log('STIMULUS APP STARTING - PRINTER THEME VERSION');

// Core controllers
import ProductShowImagesController from './controllers/ProductShowImagesController';

import PrinterCategoriesController from '../../../../../../assets/shop/controllers/PrinterCategoriesController';
import BestSellerScrollController from '../../../../../../assets/shop/controllers/BestSellerScrollController';
import PrinterHeroController from '../../../../../../assets/shop/controllers/PrinterHeroController';
import PrinterRevealController from '../../../../../../assets/shop/controllers/PrinterRevealController';

export const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.[jt]sx?$/
));

// Register core
app.register('product-show-images', ProductShowImagesController);

// Register theme
app.register('printer-categories', PrinterCategoriesController);
app.register('best-seller-scroll', BestSellerScrollController);
app.register('printer-hero', PrinterHeroController);
app.register('printer-reveal', PrinterRevealController);

app.debug = process.env.NODE_ENV !== 'production';
