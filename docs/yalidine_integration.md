# Yalidine integration (Sylius plugin)

This project now includes a dedicated plugin bundle: `SyliusYalidinePlugin`.

## What is implemented

1. **Automatic shipment creation after order placement**
   - Trigger: `sylius.order.post_complete`
   - If shipment method code is one of configured `shipping_method_codes`, the plugin sends `POST /parcels` to Yalidine API.
   - Returned `tracking` is stored in Sylius shipment tracking field.

2. **Shipment tracking sync in admin**
   - Admin Shipment show page renders Yalidine actions for configured methods.
   - Action **Sync status** fetches `GET /parcels/{tracking}`.

3. **Printable shipping ticket**
   - Action **Print shipping ticket** opens a printable ticket page.

4. **Webhook endpoint**
   - `POST /yalidine/webhook`
   - Optional `X-YALIDINE-WEBHOOK-TOKEN` validation.
   - Duplicate payload deduplication via cache.
   - `delivered` webhook status updates shipment state to `shipped`.

## Environment variables

```dotenv
YALIDINE_API_BASE_URL="https://api.yalidine.app/v1"
YALIDINE_API_ID="<your_api_id>"
YALIDINE_API_TOKEN="<your_api_token>"
YALIDINE_WEBHOOK_TOKEN="<optional_shared_secret>"
YALIDINE_SHIPPING_METHOD_CODES="yalidine"
YALIDINE_DEFAULT_TO_WILAYA="16"
```

## Plugin configuration

```yaml
# config/packages/sylius_yalidine.yaml
sylius_yalidine:
    shipping_method_codes: ['yalidine']
```

## Security note

If credentials were exposed publicly, rotate API tokens immediately in Yalidine dashboard.
