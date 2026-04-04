# AGENTS.md - Architect Mode

This file provides guidance to agents when planning architecture for this repository.

## Architectural Constraints (Non-Obvious)
1. **Monorepo structure:** Sylius replaces all its own packages — version is `v2.2.4-dev`
2. **Theme isolation:** Theme can ONLY contain CSS and Twig templates — PHP code goes in `src/PrinterTheme/`
3. **Stimulus single-init:** Core Sylius shop app owns the Stimulus app — theme must NOT start another one
4. **Hook override order:** Bundle `prepend()` runs first → app `config/packages/` runs later → `zz_*` files run last (win)
5. **Dual-database migrations:** Every migration needs MySQL AND PostgreSQL versions

## Plugin Architecture
Each plugin is self-contained with:
- `src/` — PHP classes (Entities, Forms, Services, Controllers)
- `Resources/config/app/config.yaml` — Twig hooks, services, Sylius config
- `Resources/views/` — Templates (overridable by theme)
- `Resources/translations/` — Plugin-specific translations

**Plugin loading:** Extension class uses `PrependExtensionInterface` to merge `config/app/config.yaml` into Sylius config.

## Theme Architecture
```
themes/PrinterTheme/
├── SyliusShopBundle/
│   ├── Resources/assets/
│   │   ├── entry.js          # CSS-only import
│   │   └── styles/
│   │       ├── main.scss     # Imports all partials
│   │       ├── _variables.scss
│   │       └── _*.scss       # Component partials
│   └── templates/            # (Not used — overrides go in bundles/)
└── templates/bundles/
    ├── SyliusShopBundle/     # Shop template overrides
    ├── SyliusOffersPlugin/   # Plugin template overrides
    └── SyliusRepairServicePlugin/
```

## Service Registration
- **Plugin services:** Auto-registered via `Resources/config/services.xml` or `config/app/config.yaml`
- **Theme services:** Registered in `config/packages/printer_theme.yaml`
- **Twig extensions:** Must be tagged with `['twig.extension']`

## State Machine Integration
- **Yalidine shipping:** Uses `winzou_state_machine` callbacks for automated parcel creation
  - `sylius_order` fulfill callback → create parcel
  - `sylius_shipment` ship callback → track shipment
- **Payment flow:** Chargily uses messenger transport `SYLIUS_MESSENGER_TRANSPORT_PAYMENT_REQUEST_DSN=sync://`

## CI/CD Pipeline
- **Static checks:** ECS, PHPStan, Twig lint, YAML lint, Composer validate
- **E2E tests:** PHPUnit + Behat (API, CLI, UI suites)
- **Databases tested:** MySQL, MariaDB, PostgreSQL
- **Node versions:** 20.x (branch 1.14), 24.x (branch 2.2+)

## Performance Considerations
- **Doctrine hydrators:** Uses `sylius-labs/association-hydrator` to prevent N+1 queries
- **Cache:** Symfony cache with `test_cached` environment for CI
- **Messenger:** Async transports for catalog promotion removal, payment requests
- **Image handling:** Custom `PrinterTheme\Serializer\ImageNormalizer` prepends base URL for API responses

## Security Considerations
- **JWT auth:** Lexik JWT bundle for API authentication
- **Payment encryption:** `SYLIUS_PAYMENT_ENCRYPTION_KEY_PATH` for gateway config encryption
- **XFrameOptions:** Tested in `tests/Controller/XFrameOptionsTest.php`
- **Secrets:** Never commit production secrets to `.env` — use `.env.local`
