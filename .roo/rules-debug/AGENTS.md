# AGENTS.md - Debug Mode

This file provides guidance to agents when debugging code in this repository.

## Debugging Commands
```bash
bin/console cache:clear                    # Clear cache
bin/console debug:router [name]            # Debug routes
bin/console debug:container [service]      # Debug services
bin/console debug:twig                     # Debug Twig functions/filters
bin/console debug:config sylius_twig_hooks # Debug twig hooks config
bin/console messenger:failed:show          # View failed messenger messages
bin/console messenger:failed:retry         # Retry failed messages
```

## Log Locations
- **Symfony logs:** `var/log/dev.log` or `var/log/test.log`
- **PHP error log:** Check your PHP-FPM/Apache error log
- **Webhook logs:** Chargily/Yalidine webhooks log to `var/log/` — check for incoming payload issues

## Known Debugging Gotchas
1. **`[data-reveal]` elements hidden on load:** `PrinterRevealController` must check `getBoundingClientRect()` on `connect()` and immediately add `.revealed` to elements already in viewport — not only on IntersectionObserver callbacks
2. **Silent IPC/message failures:** Webhook endpoints fail silently if not wrapped in try/catch — check `ChargilyWebhookVerifier` and Yalidine webhook handlers
3. **Double cart additions:** If products add twice with doubled quantities, Stimulus is being initialized twice — check that theme entry.js does NOT start a second Stimulus app
4. **Payment confirmations failing:** Check `ChargilyCheckoutPayloadProvider.php` for hardcoded webhook URLs that may be wrong for the current environment
5. **Admin menu not showing:** If `MenuListener` calls `$menu->getChild('sales')` and gets null, it exits silently — add fallback `addChild()` for dedicated top-level section
6. **Plugin pages unstyled:** If plugin pages render bare HTML, theme template overrides are missing — add templates to `themes/PrinterTheme/templates/bundles/{PluginName}/`

## Environment Variables for Debugging
- `APP_DEBUG=1` — Enable debug mode
- `APP_ENV=dev` — Development environment
- `IS_DOCTRINE_ORM_SUPPORTED=true` — Required for Doctrine ORM tests
- `YALIDINE_SHIPPING_METHOD_CODES` — Must use `%env(csv:YALIDINE_SHIPPING_METHOD_CODES)%` processor

## Database Debugging
- **Migrations:** Check `src/Sylius/Bundle/CoreBundle/Migrations/` — must have BOTH MySQL and PostgreSQL versions
- **Doctrine queries:** Use `bin/console debug:doctrine:mapping` to verify entity mappings
- **Query profiling:** Enable Doctrine query logging in `config/packages/doctrine.yaml`

## Test Debugging
```bash
vendor/bin/phpunit --filter TestClassName     # Single test class
vendor/bin/phpunit --testsuite sylius         # Sylius test suite only
vendor/bin/behat --dry-run                    # Check Behat scenarios without running
vendor/bin/ecs                                # Fix code style issues
vendor/bin/phpstan analyse                    # Static analysis errors
```
