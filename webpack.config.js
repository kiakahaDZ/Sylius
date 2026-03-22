const path = require('path');

const SyliusAdmin = require('@sylius-ui/admin');
const SyliusShop = require('@sylius-ui/shop');

const adminConfig = SyliusAdmin.getWebpackConfig(path.resolve(__dirname));
const shopConfig = SyliusShop.getWebpackConfig(path.resolve(__dirname));

// Add the PrinterTheme entrypoint
shopConfig.entry['printer-theme'] = './themes/PrinterTheme/SyliusShopBundle/Resources/assets/entry.js';

module.exports = [adminConfig, shopConfig];
