import { startStimulusApp } from '@symfony/stimulus-bridge';
import './styles/main.scss';

import PrinterCategoriesController from './controllers/PrinterCategoriesController';
import BestSellerScrollController from './controllers/BestSellerScrollController';
import PrinterHeroController from './controllers/PrinterHeroController';
import PrinterRevealController from './controllers/PrinterRevealController';
import PrinterTransitionController from './controllers/PrinterTransitionController';

export const app = startStimulusApp(require.context(
    './controllers',
    true,
    /\.(j|t)sx?$/
));

console.log('Printer theme Stimulus app starting...');

app.register('printer-categories', PrinterCategoriesController);
app.register('best-seller-scroll', BestSellerScrollController);
app.register('printer-transition', PrinterTransitionController);