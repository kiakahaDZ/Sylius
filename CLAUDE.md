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
1. **Dynamic Only:** NO hardcoded HTML in `themes/PrinterTheme`. Use Sylius Hooks/Templates.
2. **Theme Overrides:** Place in `themes/PrinterTheme/templates/bundles/[BundleName]/`.
3. **Component Logic:** Use `themes/PrinterTheme/SyliusShopBundle/Resources/assets/styles/` for SCSS partials.
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
- **Configs:** `config/packages/_sylius.yaml`, `config/routes.yaml`
- **Theme Root:** `themes/PrinterTheme/`
- **Custom Logic:** `src/Plugin/` (Each plugin contains internal `src/`, `Resources/`, `Controller/`)
- **Translations:** `translations/` (Global) or `src/Plugin/*/Resources/translations/`

---

## 🎨 Frontend & UI Goals (PrinterTheme)
- **Style:** Inspired by `easyprint-dz.com` (Modern, Glassmorphism, Premium).
- **Icons:** Use **Tabler Icons 1.x**.
- **Animations:** Gentle scroll reveals, card lift hovers, interactive buttons.
- **Homepage Sequence:** Hero -> Top Categories -> Best Sellers -> Offers -> Features Strip.

---

*This file acts as a persistent memory layer for AI agents to ensure high-quality, token-efficient contributions.*