# AGENTS.md - Code Mode

This file provides guidance to agents when working with code in this repository.

## Code Style Rules (Non-Obvious Only)
- **strict_types=1** mandatory in ALL PHP files
- **4 spaces** indentation (not tabs) — enforced by ECS
- **final** for all service classes, EXCEPT entities and repositories
- **readonly** for immutable services and value objects
- **snake_case** for: config keys, route names, template vars, template filenames
- **camelCase** for: PHP variables and methods
- **SCREAMING_SNAKE_CASE** for constants
- **Trailing commas** in multi-line arrays and argument lists
- **Fast returns** preferred over nested logic

## Import/Dependency Rules
- Use `PrependExtensionInterface` in plugin Extension classes to load `config/app/config.yaml`
- Use `Yaml::parseFile` to load plugin-specific Sylius configs
- **DO NOT** reference container params (e.g. `%plugin.some_param%`) in `prepend()` — they don't exist yet
- Sort `use` imports alphabetically, group by type (classes, functions, constants)

## Plugin Structure
Each plugin in `src/Plugin/` follows this pattern:
```
src/Plugin/SyliusXxxPlugin/
├── src/                    # PHP classes
├── Resources/
│   ├── config/
│   │   ├── app/
│   │   │   └── config.yaml   # Twig hooks, services
│   │   └── services.xml
│   ├── translations/
│   └── views/
```

## Theme Override Rules
- Theme templates: `themes/PrinterTheme/templates/bundles/[BundleName]/`
- Mirror the bundle path exactly (e.g. `SyliusShopBundle/product/show/content.html.twig`)
- SCSS partials: `themes/PrinterTheme/SyliusShopBundle/Resources/assets/styles/`
- **NEVER** add Stimulus controllers to theme entry.js — use `assets/shop/controllers/`

## Payment Gateway Config (Critical)
Gateway config fields do NOT auto-render in Admin UI. You MUST:
1. Register Twig hook in `src/Resources/config/app/config.yaml`
2. Hook name: `sylius_admin.payment_method.[create|update].content.form.sections.gateway_configuration.{factory_name}`
3. Template must use `hookable_metadata.context.form.gatewayConfig.config`

## Common Mistakes to Avoid
- **Double initialization:** Loading Stimulus twice (core + theme) causes bugs like double cart additions
- **Homepage duplicates:** If theme renders banners/offers explicitly, disable same `sylius_twig_hooks` hookables in `zz_printer_homepage_hooks.yaml`
- **Admin menu null:** If `$menu->getChild('sales')` returns null, add fallback to create dedicated top-level section
- **Webhook URL:** Chargily plugin webhook endpoint may be hardcoded in `ChargilyCheckoutPayloadProvider` — verify for different environments
- **Autoload:** After adding classes under `src/PrinterTheme/` or `src/Plugin/`, run `composer dump-autoload`
