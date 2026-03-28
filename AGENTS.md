# AI Contribution Guidelines

Guidelines for AI assistants contributing to Sylius.

## Reference Files

When working on specific areas, check these files for patterns:

### Entities & Models
- Entity pattern: `src/Sylius/Component/Core/Model/Product.php`
- Interface pattern: `src/Sylius/Component/Core/Model/ProductInterface.php`
- Doctrine mapping: `src/Sylius/Bundle/CoreBundle/Resources/config/doctrine/model/`

### API Platform 4.x
- Resource definitions: `src/Sylius/Bundle/ApiBundle/Resources/config/api_platform/resources/`
- Properties/serialization: `src/Sylius/Bundle/ApiBundle/Resources/config/api_platform/properties/`
- Admin resources: `resources/admin/Product.xml`
- Shop resources: `resources/shop/Product.xml`

### Payment Gateways (Sylius 2.x)
- Configuration Type: `src/Form/Type/GatewayConfigurationType.php`
- Factory: `src/Payum/GatewayFactory.php`
- UI Rendering (Twig Hooks): `src/Resources/config/app/config.yaml`
- Hook Template: `src/Resources/views/admin/payment_method/form/sections/gateway_configuration/config.html.twig`
- Pattern: Hooks must be named `sylius_admin.payment_method.[create|update].content.form.sections.gateway_configuration.{factory_name}`

### Services & Configuration
- Service definitions: `src/Sylius/Bundle/CoreBundle/Resources/config/services.xml`
- Bundle config: `src/Sylius/Bundle/*/Resources/config/`

### Templates & Hooks
- Admin templates: `src/Sylius/Bundle/AdminBundle/templates/`
- Shop templates: `src/Sylius/Bundle/ShopBundle/templates/`
- Twig hooks: check existing hooks in templates for naming patterns
- **PrinterTheme (this project):** Shop UI overrides live under `themes/PrinterTheme/templates/bundles/` mirroring bundle paths (`SyliusShopBundle/`, `SyliusOffersPlugin/`, `SyliusRepairServicePlugin/`, etc.). Theme SCSS/Stimulus: `themes/PrinterTheme/SyliusShopBundle/Resources/assets/`.
- **Homepage hook merge:** Bundles may `prepend` `sylius_twig_hooks`. To override or **disable** a hookable (`enabled: false`) after bundles load, use a late-loaded file such as `config/packages/zz_printer_homepage_hooks.yaml` so the app wins.
- **Shop-only PHP outside plugins:** Namespace `PrinterTheme\` → `src/PrinterTheme/` (see `composer.json` autoload). Example: `PrinterTheme\Twig\HomepageProductExtension` registered in `config/packages/printer_theme.yaml`.

### Tests
- PHPUnit functional: `tests/Functional/`
- PHPUnit API: `tests/Api/`
- Behat contexts: `src/Sylius/Behat/Context/`

### Migrations
- Location: `src/Sylius/Bundle/CoreBundle/Migrations/`
- **ALWAYS create TWO migrations**: one for MySQL, one for PostgreSQL
- MySQL: extend `Sylius\Bundle\CoreBundle\Doctrine\Migrations\AbstractMigration`
- PostgreSQL: extend `Sylius\Bundle\CoreBundle\Doctrine\Migrations\AbstractPostgreSQLMigration`
- When reviewing PRs that touch migrations, verify both versions exist

## General Guidelines

### Project Structure & Design

- Sylius is an e-commerce framework built on **Symfony**
- Bundles and Components can be used independently
- Follow the Sylius Backward Compatibility (BC) policy
- When changing interfaces, always provide BC layer

### Compatibility & Security

- Ensure compatibility with **Symfony** and **PHP** versions defined in `composer.json`
- For API configuration, use **API Platform 4.x**
- Follow secure coding practices to prevent XSS, CSRF, injections, auth bypasses, etc.

### Coding Standards & Tooling

- Use **4 spaces** for indentation in all files (PHP, YAML, XML, Twig, etc.)
- Use **PHPUnit** for unit and functional testing
- Use **Behat** for behavior-driven scenarios
- Use **ECS** to ensure consistent code style
- Use **PHPStan** for static analysis
- Use **CI** to run all tests and checks automatically

## Commands

- Run `composer install` to install PHP dependencies
- Run `vendor/bin/ecs` to fix PHP code style issues
- Run `vendor/bin/phpstan analyse` to perform static analysis
- Run `vendor/bin/phpunit` to execute unit and functional tests
- Run `yarn install` to install JavaScript dependencies
- Run `yarn encore dev` to compile frontend assets

## PHP Code

- Use modern PHP 8.2+ syntax and features
- Declare `strict_types=1` in all PHP files
- Follow the **Sylius Coding Standard**
- Do not use deprecated features from PHP, Symfony, or Sylius
- Use `final` for all classes, except entities and repositories
- Use `readonly` for immutable services and value objects
- Add type declarations for all properties, arguments, and return values
- Use `camelCase` for variables and method names
- Use `SCREAMING_SNAKE_CASE` for constants
- Use `snake_case` for configuration keys, route names, and template variables
- Use **fast returns** instead of nesting logic unnecessarily
- Use trailing commas in multi-line arrays and argument lists
- Order array keys alphabetically where applicable
- Use PHPDoc only when necessary (e.g. `@var Collection<ProductInterface>`)
- Group class elements in this order: constants, properties, constructor, public methods, protected methods, private methods
- Group getter and setter methods for the same properties together
- Suffix interfaces with Interface, traits with Trait
- Use `use` statements for all non-global classes
- Sort `use` imports alphabetically and group by type (classes, functions, constants)

### Dependency Injection (Plugins)
- Use `PrependExtensionInterface` in Extension classes to load `config/app/config.yaml`.
- Use `Yaml::parseFile` to load plugin-specific Sylius configurations (hooks, grids, etc.).

## Templates and Hooks

- Use modern HTML5 syntax
- Always use the most modern Twig syntax and features
- Icon names must be from the Tabler 1.x library
- Use `snake_case` for all template directory and file names
- Use `snake_case` for all variable names in Twig files
- Ensure the directory structure under `templates/` matches the structure of the corresponding Twig hooks
- Use translations for all strings in templates, never hardcode text

## API

- Define resources in `admin/` and `shop/` folders accordingly
- Define operations in the following order: `get collection`, `get item`, `post`, `put`, `patch`, `delete`
- Define resource serialization in the `serialization/` folder
- Use serialization groups for: `index`, `show`, `create`, `update`
- Use **PHPUnit** tests to validate API configuration and API responses

## PHPUnit

- Place unit tests in `tests/Unit/`
- Place functional tests in `tests/Functional/`
- Place API tests in `tests/Api/`
- Test class names must end with `Test` suffix
- Use `#[Covers]` attribute for unit tests
- For API tests, extend `ApiTestCase` and use `assertResponse*` methods
- Run specific test: `vendor/bin/phpunit --filter TestClassName`

## Behat

- Place feature files in `features/` directory
- Use existing contexts from `src/Sylius/Behat/Context/`
- Follow Given-When-Then pattern strictly
- Use `@ui` tag for UI tests, `@api` for API tests
- Page objects are in `src/Sylius/Behat/Page/`

## JavaScript

- Use TypeScript where possible
- Stimulus controllers for interactive components:
    - Sylius core: `assets/admin/controllers/` or `assets/shop/controllers/`
    - **PrinterTheme (Consolidated):** All theme Stimulus controllers (e.g., `PrinterHeroController`, `PrinterRevealController`) now live in `assets/shop/controllers/` and are registered by the core Sylius shop app to prevent double-initialization.
- Follow existing naming conventions for controller files.

## CSS

- Use SCSS (`.scss`) syntax – plain CSS files are not allowed
- Use Bootstrap 5 utility classes where possible
- Keep component styles modular – 1 component = 1 partial
- Use variables from Sylius theme
- Place all theme variables in `_variables.scss`
- **PrinterTheme:** Partials under `themes/PrinterTheme/SyliusShopBundle/Resources/assets/styles/`, imported from `main.scss`
- Avoid `!important` unless absolutely necessary
- Prefer `rem` over `px` for spacing, font size, etc.
- Use `mixins/` for reusable logic (e.g., `@include icon-size(24px)`)

## Common Mistakes to Avoid

- **BC breaks**: Never change method signatures in interfaces without deprecation layer
- **Missing Doctrine mapping**: New entity properties need XML mapping in `Resources/config/doctrine/`
- **Hardcoded strings**: Always use translation keys in templates
- **Missing serialization groups**: API properties need proper groups in `properties/*.xml`
- **Wrong namespace**: Components have no Symfony dependency, Bundles can
- **Forgetting tests**: API changes need PHPUnit tests in `tests/Api/`
- **Homepage duplicates**: If the theme renders banners, best sellers, or offers **explicitly**, disable the same `sylius_twig_hooks` hookables (or remove the explicit blocks). Never chain `homepage/banner.html.twig` → controller → template that includes `banner.html.twig` again; use a dedicated fallback (e.g. `banner_static_fallback.html.twig`).
- **Autoload**: After adding classes under `src/PrinterTheme/` or any plugin under `src/Plugin/`, run `composer dump-autoload`.
- **Double Initialisation/Addition**: Avoid loading Stimulus or JS multiple times via multiple Encore entries (e.g. core + theme). This causes bugs like products being added to the cart twice with doubled quantities. Use `assets/shop/controllers/` for all shop JS.
- **Scroll-reveal hidden elements**: `[data-reveal]` starts with `opacity: 0`. The Stimulus controller (`PrinterRevealController`) must immediately reveal elements already in the viewport on `connect()`, not only on IntersectionObserver callbacks. Check `getBoundingClientRect()` vs `window.innerHeight` on connect.
- **Admin menu not showing**: If a plugin's `MenuListener` calls `$menu->getChild('sales')` and receives null, the listener exits silently. Always add a fallback that creates a dedicated top-level menu section instead of returning early.
- **Payment Webhook URL**: In `SyliusChargilyPlugin`, verify the webhook endpoint URL in `ChargilyCheckoutPayloadProvider`. It may be hardcoded or require explicit configuration for different environments.
- **`prepend()` vs `load()` parameter order**: In a bundle Extension, `prepend()` runs BEFORE `load()`. Do NOT reference container parameters (e.g. `%plugin.some_param%`) inside twig hook `context:` blocks in `prepend()` config — those parameters don't exist yet. Expose dynamic values via a Twig Extension service instead.
- **Invalid env default in arrayNode**: `%env(default:literal:ENV_VAR)%` is invalid — `default:` expects an env var name, not a literal string. Use `%env(ENV_VAR)%` with a defined fallback in `.env`, and set `->defaultValue([])` in `Configuration.php`. Configure the actual values in `config/packages/sylius_*.yaml`.
- **SyliusYalidinePlugin**: Yalidine DZ shipping integration. Shipping method codes configured via `YALIDINE_SHIPPING_METHOD_CODES` env var. It MUST be injected into `services.yaml` using the `%env(csv:YALIDINE_SHIPPING_METHOD_CODES)%` processor to evaluate at runtime. Implemented dynamic Wilaya/Commune selectors in checkout via `YalidineAddressController`. Automated API parcel creation is triggered natively via `winzou_state_machine` callbacks (`sylius_order` fulfill, `sylius_shipment` ship) instead of just the checkout event.
