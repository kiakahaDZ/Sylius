# CLAUDE.md - Project Intelligence & Assistant Guidelines

## 🤖 AI Token Efficiency & Behavior Protocol
**CRITICAL: Follow these to save user tokens and maximize focus.**
- **Concise Response:** No small talk. Skip "I understand," "Sure," or "Here is the code."
- **Focus on Diffs:** Use `replace_file_content` or `multi_replace_file_content` chunks instead of printing entire files.
- **No Recaps:** Do not summarize what you are about to do unless it's a complex multi-step plan.
- **Reference only:** If a file was already viewed, don't ask to view it again unless you need a refresher on specific lines.

---

## 🏗️ Tech Stack & Versions
- **Platform:** Sylius 2.2+ (v2.2.4-dev)
- **Framework:** Symfony 7.4+
- **PHP:** 8.2+ (Strict Types Mandatory)
- **Frontend:** Twig, Webpack Encore, SCSS, Stimulus (JS), Tabler Icons
- **Database:** MySQL / PostgreSQL (Migrations required for both)
- **Plugins in use:** 
  - `src/Plugin/SyliusBannerPlugin` (Banners/Hero)
  - `src/Plugin/SyliusBestSellerPlugin` (Featured/Sales)
  - `src/Plugin/SyliusOffersPlugin` (Marketing/Promos)
  - `src/Plugin/SyliusChargilyPlugin` (Gateway Pay V2)
  - `src/Plugin/ChargilyEpayPlugin` (Legacy Epay Gateway)
  - `src/Plugin/SyliusRepairServicePlugin` (Custom Repairs)

---

## 🧭 Payment Gateway Config (Sylius 2.x)
**CRITICAL:** Gateway configuration fields (API keys, URLs, etc.) do NOT show up automatically in the Admin UI.
1. **Twig Hooks:** Must be registered in `src/Resources/config/app/config.yaml`.
2. **Hook Name:** `sylius_admin.payment_method.[create|update].content.form.sections.gateway_configuration.{factory_name}`.
3. **Template:** Use `hookable_metadata.context.form.gatewayConfig.config`.

---

## 💻 Core Commands Reference
- **Cache:** `php bin/console cache:clear`
- **Assets:** `yarn encore dev` or `yarn encore production`
- **Migrations:** `php bin/console doctrine:migrations:migrate`
- **Debug Routes:** `php bin/console debug:router [name]`
- **List Commands:** `php bin/console list`
- **Local Server:** `symfony server:start -d`

---

## 🎯 Project-Specific Rules
1. **Dynamic first:** Prefer Sylius data, hooks, and components. Static marketing copy lives only in **translations** (`translations/messages.*.yaml`) and the **hero fallback** template (`homepage/banner_static_fallback.html.twig`), not scattered in random templates.
2. **Theme Overrides:** Place in `themes/PrinterTheme/templates/bundles/[BundleName]/` (e.g. `SyliusShopBundle/`, `SyliusOffersPlugin/`).
3. **Component Logic:** SCSS partials in `themes/PrinterTheme/SyliusShopBundle/Resources/assets/styles/`; Stimulus in `.../Resources/assets/controllers/` (Encore `entry.js` loads them).
4. **Multilingual (i18n):**
   - French (`fr_FR`), English (`en_US`), Arabic (`ar_DZ`).
   - RTL check: Arabic requires `ar` translation files and the theme must handle `dir="rtl"` layout.
5. **Coding Standards:** 
   - Use `final` for services.
   - Use `readonly` for immutable objects.
   - Method signatures must include types.
   - Use `snake_case` for templates and `camelCase` for PHP.

---

## 🧭 Project Architecture Map
- **Configs:** `config/packages/_sylius.yaml`, `config/routes.yaml`, `config/packages/printer_theme.yaml` (services), `config/packages/zz_printer_homepage_hooks.yaml` (twig-hooks overrides — `zz_` so app config wins over bundle `prepend`)
- **Theme Root:** `themes/PrinterTheme/`
- **Shop glue (non-plugin PHP):** `src/PrinterTheme/` — PSR-4 `PrinterTheme\\` in `composer.json` (e.g. `Twig\HomepageProductExtension`: `printer_products_for_taxon`, `printer_latest_channel_products`)
- **Custom Logic:** `src/Plugin/` (each plugin: `src/`, `Resources/`, `Controller/` as applicable)
- **Translations:** `translations/` (global, including `printer_shop.*`) or `src/Plugin/*/Resources/translations/`

---

## 🎨 Frontend & UI Goals (PrinterTheme)
- **Style:** Inspired by `easyprint-dz.com` (Modern, Glassmorphism, Premium).
- **Icons:** Use **Tabler Icons 1.x**.
- **Animations:** Gentle scroll reveals, card lift hovers, interactive buttons.
- **Homepage sequence:** Dynamic hero (`BannerController` via `homepage/banner.html.twig`) → **taxon product sliders** (per menu-taxon child, `taxon_product_sliders.html.twig`) → categories → **best sellers component** → **offer banners + offers section** (SyliusOffersPlugin templates overridden in theme) → **`{% hook sylius_shop.homepage.index %}`** (latest deals, new collection, latest products only; duplicate hookables disabled in `zz_printer_homepage_hooks.yaml`) → repair CTA → features strip.
- **Hero image:** Plugin template uses `printer-hero__visual--banner-photo` for uploaded banner art (`object-fit: cover`, framed aspect ratio).
- **Product cards:** Simple products → compact add-to-cart (`product/common/add_to_cart_card.html.twig`); configurable → “choose options” link to product page (`printer_shop.product.choose_options`).

---

*This file acts as a persistent memory layer for AI agents to ensure high-quality, token-efficient contributions.*

---

## ⚠️ Known Pitfalls
- **`[data-reveal]` hidden on load**: `PrinterRevealController` must check `getBoundingClientRect()` on `connect()` and immediately add `.revealed` to elements already in the viewport — not only rely on IntersectionObserver callbacks.
- **Admin menu item missing**: If `$menu->getChild('sales')` is null, `AdminMenuListener` silently returns. Always add a fallback `addChild()` to create a dedicated top-level section.
- **Plugin pages unstyled**: Override plugin Twig templates in `themes/PrinterTheme/templates/bundles/{PluginName}/` to apply the PrinterTheme layout; otherwise plugins render bare Sylius HTML.
- **Encore entry**: The `printer-theme` entry in `webpack.config.js` must load `entry.js` which calls `startStimulusApp`. All Stimulus controllers in `themes/PrinterTheme/.../controllers/` are auto-registered via that context.