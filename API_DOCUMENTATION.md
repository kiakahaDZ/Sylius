# NovaPrint Sylius API Documentation

This document outlines the available API endpoints for the NovaPrint Sylius project, including core Sylius functionality and developed custom plugins.

## 🔑 Authentication

The API uses JWT (JSON Web Token) for authentication.

### Shop User Authentication
- **Endpoint**: `POST /api/v2/shop/customers/token`
- **Request Body**:
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

### Admin Authentication
- **Endpoint**: `POST /api/v2/admin/administrators/token`
- **Request Body**:
  ```json
  {
    "email": "admin@example.com",
    "password": "password"
  }
  ```

---

## 🛠 Custom Plugin Endpoints (v1)

These endpoints use the `/api/v1` prefix as configured in the project routes.

### 🖼 Banners
- **Get Hero Banner**: `GET /api/v1/_banner/hero`
- **Get Collection**: `GET /api/v2/shop/banners` (via API Platform)
- **Get Single Banner**: `GET /api/v2/shop/banners/{id}` (via API Platform)

### 🏷 Offers (Promotions)
- **Get Collection**: `GET /api/v1/offers`
- **Get Single Offer**: `GET /api/v1/offers/{id}`
- **Track Click**: `POST /api/v1/offers/{id}/click`
- **Upcoming Offers**: `GET /api/v1/offers/upcoming`
- **Expiring Soon**: `GET /api/v1/offers/expiring`
- **Best Performing**: `GET /api/v1/offers/best-performing`

### 🏆 Best Sellers
- **Get Collection**: `GET /api/v1/best-sellers`
- **Top Product**: `GET /api/v1/best-sellers/top`
- **Product Stats**: `GET /api/v1/products/{productId}/stats`

### 🔧 Repair Service
- **List Requests**: `GET /api/v1/repair-requests` (Requires Authentication)
- **Create Request**: `POST /api/v1/repair-requests`
  - **Request Body**:
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
- **View Request**: `GET /api/v1/repair-requests/{code}`

### 🚚 Yalidine Shipping (Algeria)
- **List Wilayas**: `GET /yalidine/api/wilayas`
- **List Communes**: `GET /yalidine/api/communes?wilaya_id=16`
  - **Params**: `wilaya_id` (numeric ID from Wilayas list)
- **Calculate Fees**: `GET /yalidine/api/fees?to_wilaya_id=16&commune_id=581`
  - **Params**: 
    - `to_wilaya_id` (Required)
    - `from_wilaya_id` (Optional, defaults to store location)
    - `commune_id` (Optional, for precise commune pricing)
- **Webhook**: `POST /yalidine/webhook`

### 💳 Chargily Payment (CIB/Edahabia)
- **Payment Webhook**: `POST /chargily/webhook/{code}`
- **Invoice PDF/Response**: `POST /chargily/response/{OrderNumber}`

---

## 🛍 Core Shop API (v2)

These are the primary endpoints provided by Sylius API Platform 4.x.

### Products
- **List Products**: `GET /api/v2/shop/products`
  - Filters: `productTaxons.taxon.code`, `name`, `code`
- **Show Product**: `GET /api/v2/shop/products/{code}`

### Taxons (Categories)
- **List Categories**: `GET /api/v2/shop/taxons`
- **Show Category**: `GET /api/v2/shop/taxons/{code}`

### Cart Management
1. **Create Cart**: `POST /api/v2/shop/orders`
   - Body: `{}` (Empty JSON object)
2. **Add Item**: `POST /api/v2/shop/orders/{tokenValue}/items`
   - **Request Body**:
     ```json
     {
       "productVariantCode": "PRINTER_JET_100",
       "quantity": 1
     }
     ```
3. **View Cart**: `GET /api/v2/shop/orders/{tokenValue}`
4. **Update Quantity**: `PUT /api/v2/shop/orders/{tokenValue}/items/{itemId}`
   - **Request Body**:
     ```json
     {
       "quantity": 5
     }
     ```
5. **Clear Cart**: `DELETE /api/v2/shop/orders/{tokenValue}/items/{itemId}`

### Checkout Details & Promos
- **Set Account (Guest)**: `PATCH /api/v2/shop/orders/{tokenValue}`
  - Request Body: `{ "email": "guest@example.com" }`
- **Apply Coupon**: `PATCH /api/v2/shop/orders/{tokenValue}`
  - Request Body: `{ "couponCode": "WINTER_SALE" }`
- **Set Address**: `PUT /api/v2/shop/orders/{tokenValue}/address`
  - Body: see common address structure below.
- **Select Shipping**: `PUT /api/v2/shop/orders/{tokenValue}/shipments/{shipmentId}`
  - Request Body: `{ "shippingMethodCode": "ups_ground" }`
- **Select Payment**: `PUT /api/v2/shop/orders/{tokenValue}/payments/{paymentId}`
  - Request Body: `{ "paymentMethodCode": "cash_on_delivery" }`
- **Complete Order**: `PATCH /api/v2/shop/orders/{tokenValue}/complete`
  - Request Body: `{ "notes": "Please deliver after 5pm." }`

### Customer Account (Authenticated)
- **Register Account**: `POST /api/v2/shop/customers`
  - Body: 
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
- **Show Portfolio**: `GET /api/v2/shop/customers/{id}`
- **Update Profile**: `PUT /api/v2/shop/customers/{id}`
- **My Orders**: `GET /api/v2/shop/orders` (Lists orders for authenticated user)
- **Address Book**: `GET /api/v2/shop/addresses`

### Product Reviews
- **List Reviews**: `GET /api/v2/shop/product-reviews`
- **Add Review**: `POST /api/v2/shop/product-reviews`
  - Body: `{ "title": "Great!", "rating": 5, "comment": "Best printer ever", "product": "/api/v2/shop/products/CODE" }`

### ⚙ Store Configuration
- **Channels**: `GET /api/v2/shop/channels`
- **Currencies**: `GET /api/v2/shop/currencies`
- **Locales**: `GET /api/v2/shop/locales`
- **Countries**: `GET /api/v2/shop/countries`
- **Payment Methods**: `GET /api/v2/shop/payment-methods`
- **Shipping Methods**: `GET /api/v2/shop/shipping-methods`

### 📦 Common Address Object
Used in registration and checkout addressing.
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

---

## 🏗 Data Format (JSON-LD / Hydra)
All v2 endpoints return data in **JSON-LD** format.
- `@id`: The IRI (Internationalized Resource Identifier) of the resource. Use this for references (e.g., `product: "/api/v2/shop/products/X"`).
- `hydra:member`: The array containing the items in a collection.
- `hydra:totalItems`: Total number of items for pagination.

## ❌ Error Handling
Errors return standard HTTP status codes and a JSON body:
- **400 Bad Request**: Invalid input data.
- **401 Unauthorized**: Missing or expired JWT token.
- **403 Forbidden**: Access denied.
- **404 Not Found**: Resource does not exist.
- **422 Unprocessable Entity**: Validation errors.
  ```json
  {
    "@context": "/api/v2/contexts/ConstraintViolationList",
    "@type": "ConstraintViolationList",
    "hydra:title": "An error occurred",
    "hydra:description": "email: This email is already used.",
    "violations": [
      { "propertyPath": "email", "message": "This email is already used." }
    ]
  }
  ```

---

## 📝 Notes
- Most GET requests support `page` and `itemsPerPage` parameters for pagination.
- Ensure all requests include `Accept: application/ld+json` or `Accept: application/json`.
- For authenticated requests, use the header `Authorization: Bearer <TOKEN>`.
- Core API (v2) uses JSON-LD (Hydra) format by default.
