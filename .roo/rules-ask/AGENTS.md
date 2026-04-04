# AGENTS.md - Ask Mode

This file provides guidance to agents when answering questions about this repository.

## Project Context (Non-Obvious)
- **Sylius 2.2.4-dev** is a monorepo e-commerce platform built on Symfony 7.4+
- This is a **PrinterTheme** customization project for a printer/toner e-commerce site
- **7 custom plugins** in `src/Plugin/`: Chargily (payment), Offers, Banner, BestSeller, RepairService, Yalidine (shipping)
- **Multilingual:** French (fr_FR), English (en_US), Arabic (ar_DZ with RTL support)
- **Shop-only PHP code** lives in `src/PrinterTheme/` (PSR-4 `PrinterTheme\`), NOT in the theme folder

## Architecture Overview
```
src/
├── Plugin/                    # Custom plugins (7 total)
├── PrinterTheme/              # Shop-only PHP (Twig extensions, serializers)
├── Sylius/                    # Core Sylius code (monorepo)
│   ├── Bundle/
│   ├── Component/
│   └── Behat/

themes/PrinterTheme/           # Theme (CSS + template overrides only)
├── SyliusShopBundle/
│   ├── Resources/assets/      # SCSS only (entry.js is CSS-only)
│   └── templates/
└── templates/bundles/         # Template overrides (mirror bundle paths)

config/packages/
├── zz_printer_homepage_hooks.yaml   # Late-loaded hook overrides (zz_ prefix)
└── printer_theme.yaml               # Services + Twig hooks
```

## Homepage Render Order (Critical for Understanding)
1. Dynamic hero banner (`BannerController` via `homepage/banner.html.twig`)
2. **Taxon product sliders** (per menu-taxon child)
3. Categories grid
4. **Best sellers component**
5. **Offer banners + offers section** (SyliusOffersPlugin)
6. **`{% hook sylius_shop.homepage.index %}`** (latest deals, new collection, latest products)
7. Repair CTA banner
8. Features strip

**Note:** Duplicate hookables are disabled in `zz_printer_homepage_hooks.yaml` to prevent double-rendering.

## Key Documentation Files
- **API docs:** `API_DOCUMENTATION.md`
- **CLAUDE.md:** Detailed project intelligence (payment gateway config, frontend goals, pitfalls)
- **AGENTS.md:** General contribution guidelines

## Counterintuitive Things
- `src/PrinterTheme/` is NOT the theme — it's PHP code for the shop. The actual theme is in `themes/PrinterTheme/`
- Theme `entry.js` is **CSS-only** — Stimulus controllers are in `assets/shop/controllers/` and auto-registered by core Sylius shop app
- `prepend()` in bundle Extension runs BEFORE `load()` — container params don't exist yet
- Payment gateway config fields require manual Twig hooks — they don't auto-render
- Migrations must be created in pairs (MySQL + PostgreSQL)

## Translation Files
- **Global:** `translations/messages.*.yaml` (fr_FR, en_US, ar_DZ)
- **Plugin-specific:** `src/Plugin/*/Resources/translations/`
- **PrinterTheme:** `themes/PrinterTheme/translations/messages.ar.yaml`
