import './styles/main.scss';

import { startStimulusApp } from '@symfony/stimulus-bridge';

// Registers Stimulus controllers from the theme
export const app = startStimulusApp(require.context(
    './controllers',
    true,
    /\.(j|t)sx?$/
));
