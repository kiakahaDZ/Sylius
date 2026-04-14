# Flutter Integration API Documentation

## Overview
- **Base URL**: `https://your-domain.com` (replace with your actual domain)
- **API Versions**:
  - Core Sylius API: `v2` (prefixed with `/api/v2`)
  - Custom plugin API: `v1` (prefixed with `/api/v1`)
- **Data format**: JSON‑LD (Hydra) for core endpoints, plain JSON for custom plugin endpoints.
- **Authentication**: JWT token (see [Authentication](#authentication)).

## Authentication
### Shop User
- **Endpoint**: `POST /api/v2/shop/customers/token`
- **Headers**: `Content-Type: application/json`
- **Request body**:
```json
{
  "email": "customer@example.com",
  "password": "password"
}
```
- **Response**:
```json
{
  "token": "JWT_TOKEN_HERE",
  "customer": "/api/v2/shop/customers/1"
}
```

### Admin User
- **Endpoint**: `POST /api/v2/admin/administrators/token`
- **Headers**: `Content-Type: application/json`
- **Request body**:
```json
{
  "email": "admin@example.com",
  "password": "password"
}
```
- **Response**:
```json
{
  "token": "JWT_TOKEN_HERE",
  "administrator": "/api/v2/admin/administrators/1"
}
```

> **Note**: Include the token in subsequent requests with the header `Authorization: Bearer <TOKEN>`.

## Core Shop API (`v2`)

### Products
- **List**: `GET /api/v2/shop/products`
  - **Query parameters**: `productTaxons.taxon.code`, `name`, `code`, `page`, `itemsPerPage`.
- **Show**: `GET /api/v2/shop/products/{code}`

### Taxons (Categories)
- **List**: `GET /api/v2/shop/taxons`
- **Show**: `GET /api/v2/shop/taxons/{code}`

### Cart Management
| Action | Method | Endpoint | Request Body | Description |
|--------|--------|----------|--------------|-------------|
| Create cart | `POST` | `/api/v2/shop/orders` | `{}` (empty) | Returns an order object with `tokenValue`. |
| Add item | `POST` | `/api/v2/shop/orders/{tokenValue}/items` | ```json { "productVariantCode": "PRINTER_JET_100", "quantity": 1 } ``` | Adds a product variant to the cart. |
| View cart | `GET` | `/api/v2/shop/orders/{tokenValue}` | – | Retrieves the current cart state. |
| Update quantity | `PUT` | `/api/v2/shop/orders/{tokenValue}/items/{itemId}` | ```json { "quantity": 5 } ``` | Changes the quantity of a cart item. |
| Remove item | `DELETE` | `/api/v2/shop/orders/{tokenValue}/items/{itemId}` | – | Deletes a cart item. |

### Checkout Flow
1. **Set guest email** – `PATCH /api/v2/shop/orders/{tokenValue}`
   ```json
   { "email": "guest@example.com" }
   ```
2. **Apply coupon** – `PATCH /api/v2/shop/orders/{tokenValue}`
   ```json
   { "couponCode": "WINTER_SALE" }
   ```
3. **Set address** – `PUT /api/v2/shop/orders/{tokenValue}/address`
   Use the common address object defined in [`API_DOCUMENTATION.md`](API_DOCUMENTATION.md:174):
   ```json
   {
     "firstName": "John",
     "lastName": "Doe",
     "street": "123 Main St",
     "city": "Algiers",
     "postcode": "16000",
     "countryCode": "DZ",
     "phoneNumber": "0555000000"
   }
   ```
4. **Select shipping method** – `PUT /api/v2/shop/orders/{tokenValue}/shipments/{shipmentId}`
   ```json
   { "shippingMethodCode": "ups_ground" }
   ```
5. **Select payment method** – `PUT /api/v2/shop/orders/{tokenValue}/payments/{paymentId}`
   ```json
   { "paymentMethodCode": "cash_on_delivery" }
   ```
6. **Complete order** – `PATCH /api/v2/shop/orders/{tokenValue}/complete`
   ```json
   { "notes": "Please deliver after 5pm." }
   ```

### Customer Account (Authenticated)
- **Register**: `POST /api/v2/shop/customers`
  ```json
  {
    "firstName": "John",
    "lastName": "Doe",
    "email": "j@example.com",
    "password": "secure_pass",
    "subscribedToNewsletter": true,
    "channelCode": "default",
    "localeCode": "fr_FR"
  }
  ```
- **Show**: `GET /api/v2/shop/customers/{id}`
- **Update**: `PUT /api/v2/shop/customers/{id}`
- **My Orders**: `GET /api/v2/shop/orders` (requires JWT)
- **Address Book**: `GET /api/v2/shop/addresses`

### Product Reviews
- **List**: `GET /api/v2/shop/product-reviews`
- **Add**: `POST /api/v2/shop/product-reviews`
  ```json
  {
    "title": "Great!",
    "rating": 5,
    "comment": "Best printer ever",
    "product": "/api/v2/shop/products/CODE"
  }
  ```

### Store Configuration
- **Channels**: `GET /api/v2/shop/channels`
- **Currencies**: `GET /api/v2/shop/currencies`
- **Locales**: `GET /api/v2/shop/locales`
- **Countries**: `GET /api/v2/shop/countries`
- **Payment Methods**: `GET /api/v2/shop/payment-methods`
- **Shipping Methods**: `GET /api/v2/shop/shipping-methods`

## Custom Plugin Endpoints (`v1`)

### Banners
- **Hero banner**: `GET /api/v1/_banner/hero`
- **Collection**: `GET /api/v2/shop/banners`
- **Single**: `GET /api/v2/shop/banners/{id}`

### Offers (Promotions)
| Action | Method | Endpoint |
|--------|--------|----------|
| List offers | `GET` | `/api/v1/offers` |
| Show offer | `GET` | `/api/v1/offers/{id}` |
| Track click | `POST` | `/api/v1/offers/{id}/click` |
| Upcoming offers | `GET` | `/api/v1/offers/upcoming` |
| Expiring soon | `GET` | `/api/v1/offers/expiring` |
| Best‑performing | `GET` | `/api/v1/offers/best-performing` |

### Best Sellers
- **List**: `GET /api/v1/best-sellers`
- **Top product**: `GET /api/v1/best-sellers/top`
- **Product stats**: `GET /api/v1/products/{productId}/stats`

### Repair Service
- **List requests**: `GET /api/v1/repair-requests` (auth required)
- **Create request**: `POST /api/v1/repair-requests`
  ```json
  {
    "deviceName": "Printer",
    "deviceBrand": "HP",
    "deviceModel": "LaserJet Pro",
    "issueDescription": "Paper jam and strange noise",
    "customerName": "John Doe",
    "customerEmail": "john@example.com",
    "customerPhoneNumber": "0555000000"
  }
  ```
- **View request**: `GET /api/v1/repair-requests/{code}`

### Yalidine Shipping (Algeria)
- **Wilayas**: `GET /yalidine/api/wilayas`
- **Communes**: `GET /yalidine/api/communes?wilaya_id=16`
  *Param*: `wilaya_id` (numeric)
- **Calculate fees**: `GET /yalidine/api/fees?to_wilaya_id=16&commune_id=581`
  *Params*: `to_wilaya_id` (required), `from_wilaya_id` (optional), `commune_id` (optional)
- **Webhook**: `POST /yalidine/webhook`

### Chargily Payment (CIB/Edahabia)
- **Payment webhook**: `POST /chargily/webhook/{code}`
- **Invoice PDF / response**: `POST /chargily/response/{OrderNumber}`

## Data Format (JSON‑LD / Hydra)
All core (`v2`) endpoints return JSON‑LD. Key fields:
- `@id`: IRI of the resource (use for further calls).
- `hydra:member`: Array of items in a collection.
- `hydra:totalItems`: Total count for pagination.

## Error Handling
| Status | Meaning | Example body |
|--------|---------|--------------|
| 400 | Bad Request | `{ "detail": "Invalid input." }` |
| 401 | Unauthorized | `{ "detail": "JWT Token not found." }` |
| 403 | Forbidden | `{ "detail": "Access denied." }` |
| 404 | Not Found | `{ "detail": "Resource not found." }` |
| 422 | Unprocessable Entity | ```json { "@context": "/api/v2/contexts/ConstraintViolationList", "@type": "ConstraintViolationList", "hydra:title": "An error occurred", "hydra:description": "email: This email is already used.", "violations": [{ "propertyPath": "email", "message": "This email is already used." }] } ``` |

## Integration Tips for Flutter
- Use the `http` or `dio` package.
- Always set request headers:
  ```dart
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    if (token != null) 'Authorization': 'Bearer $token',
  }
  ```
- Store the **order token** (`tokenValue`) after creating a cart; pass it to all subsequent cart/checkout calls.
- Retrieve available shipping and payment methods before step 4/5 of the checkout flow:
  - `GET /api/v2/shop/shipping-methods`
  - `GET /api/v2/shop/payment-methods`
- For Yalidine shipping, first fetch wilayas and communes, then calculate fees using the endpoint described above.
- For Chargily payment, handle the webhook (`/chargily/webhook/{code}`) on your server to receive payment status updates.

---

*Generated on 2026‑04‑09.*

