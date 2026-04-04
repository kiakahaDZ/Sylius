# AGENTS.md

This file provides guidance to agents when working with code in this repository.

## Stack
- **Sylius 2.2.4-dev** (monorepo e-commerce platform)
- **Symfony 7.4+**, **PHP 8.2+** (strict_types mandatory)
- **API Platform 4.x**, **Doctrine ORM**, **MySQL/PostgreSQL**
- **Twig**, **Webpack Encore**, **SCSS**, **Stimulus**, **Tabler Icons 1.x**

## Core Commands
```bash
composer install && yarn install
php bin/console cache:clear
yarn encore dev              # Build frontend assets
vendor/bin/phpunit           # Run all tests
vendor/bin/phpunit --filter TestClassName  # Single test
vendor/bin/ecs               # Fix code style
vendor/bin/phpstan analyse   # Static analysis
bin/console sylius:install   # Install Sylius
symfony server:start -d      # Local dev server
```

## Project Structure
- **Plugins:** `src/Plugin/` (7 custom plugins: Chargily, Offers, Banner, BestSeller, RepairService, Yalidine)
- **Theme:** `themes/PrinterTheme/` (Shop UI overrides)
- **Shop-only PHP:** `src/PrinterTheme/` (PSR-4 `PrinterTheme\`)
- **Translations:** `translations/` (fr_FR, en_US, ar_DZ - RTL support)

## Key Non-Obvious Rules
1. **Migrations:** ALWAYS create TWO (MySQL + PostgreSQL) — see `src/Sylius/Bundle/CoreBundle/Migrations/`
2. **Homepage hooks:** Use `config/packages/zz_printer_homepage_hooks.yaml` (late-loaded, `zz_` prefix wins over bundle `prepend`)
3. **Stimulus controllers:** ALL in `assets/shop/controllers/` — NEVER in theme entry.js (causes double-initialization bugs)
4. **Theme entry.js:** CSS-only (`themes/PrinterTheme/SyliusShopBundle/Resources/assets/entry.js`)
5. **Payment gateway config:** Twig hooks required in `src/Resources/config/app/config.yaml` — fields don't auto-render
6. **`prepend()` vs `load()`:** `prepend()` runs BEFORE `load()` — container params don't exist yet in `prepend()`
7. **Yalidine env var:** Use `%env(csv:YALIDINE_SHIPPING_METHOD_CODES)%` processor (not literal default syntax)

## Reference Files
- Entity: `src/Sylius/Component/Core/Model/Product.php`
- API resources: `src/Sylius/Bundle/ApiBundle/Resources/config/api_platform/resources/`
- Services: `src/Sylius/Bundle/CoreBundle/Resources/config/services.xml`
- Doctrine mapping: `src/Sylius/Bundle/CoreBundle/Resources/config/doctrine/model/`
