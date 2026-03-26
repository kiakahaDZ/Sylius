import { startStimulusApp } from '@symfony/stimulus-bridge';
import PrinterCategoriesController from './controllers/PrinterCategoriesController';
import BestSellerScrollController from './controllers/BestSellerScrollController';
import PrinterHeroController from './controllers/PrinterHeroController';
import PrinterRevealController from './controllers/PrinterRevealController';

export const app = startStimulusApp(require.context(
    './controllers',
    true,
    /\.(j|t)sx?$/
));

console.log('Printer theme Stimulus app starting...');

app.register('printer-categories', PrinterCategoriesController);
app.register('best-seller-scroll', BestSellerScrollController);