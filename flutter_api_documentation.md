# Sylius Flutter Application API Documentation

This document outlines the API endpoints exposed by your custom Sylius plugins. These endpoints are designed to be consumed by your Flutter mobile application for features ranging from delivery location management to special offers and hardware repair services.

---

## 1. Sylius Yalidine Plugin (Shipping & Delivery)
Used for fetching dynamic Wilayas, Communes, and calculating shipping fees based on the Algerian destination matrix.

**Base URL Context:** `https://your-domain.com/yalidine/api`

*   **Get Wilayas**
    *   **Endpoint:** `/wilayas`
    *   **Method:** `GET`
    *   **Description:** Returns a list of all 58 administrative Wilayas.
*   **Get Communes**
    *   **Endpoint:** `/communes`
    *   **Method:** `GET`
    *   **Description:** Returns a list of Communes. You will generally pass the chosen wilaya ID/Code via query parameters (e.g., `?wilaya_unideals=16`).
*   **Calculate Fees**
    *   **Endpoint:** `/fees`
    *   **Method:** `GET`
    *   **Description:** Returns the calculated shipping fee based on Wilaya, Commune, and delivery mode (Home vs. Desk).

---

## 2. Sylius Banner Plugin (Marketing & Carousel)
Used to fetch active promotional banners configured in the admin dashboard. This plugin is natively integrated with Sylius API Platform.

**Base URL Context:** `https://your-domain.com/api/v2/shop`

*   **Get Active Banners**
    *   **Endpoint:** `/banners`
    *   **Method:** `GET`
    *   **Description:** Retrieves a collection of active banners (includes image URLs, alt texts, titles, and href links).
*   **Get Single Banner**
    *   **Endpoint:** `/banners/{id}`
    *   **Method:** `GET`
    *   **Description:** Retrieves specific banner details by its API Platform ID.

---

## 3. Sylius Offers Plugin (Promotions & Deals)
Used to list and track customer interactions with special promotional offers.

**Base URL Context:** `https://your-domain.com/api/v1`

*   **Get All Offers**
    *   **Endpoint:** `/offers`
    *   **Method:** `GET`
    *   **Description:** Retrieves a generic list of active offers.
*   **Get Single Offer**
    *   **Endpoint:** `/offers/{id}`
    *   **Method:** `GET`
    *   **Description:** Retrieves detailed information for a specific offer.
*   **Track Offer Click**
    *   **Endpoint:** `/offers/{id}/click`
    *   **Method:** `POST`
    *   **Description:** Registers a click/interaction metric for the specified offer (useful for analytics on the app side).
*   **Get Best-Performing Offers**
    *   **Endpoint:** `/offers/best-performing`
    *   **Method:** `GET`
    *   **Description:** Retrieves offers ordered by conversion or click rate.
*   **Get Expiring Offers**
    *   **Endpoint:** `/offers/expiring`
    *   **Method:** `GET`
    *   **Description:** Retrieves offers that are close to their end date.
*   **Get Upcoming Offers**
    *   **Endpoint:** `/offers/upcoming`
    *   **Method:** `GET`
    *   **Description:** Retrieves scheduled offers that have not started yet.

---

## 4. Sylius Repair Service Plugin (Hardware Repairs)
Used to submit and track hardware/printer repair requests directly from the app.

**Base URL Context:** `https://your-domain.com/api/v1`

*   **List Repair Requests**
    *   **Endpoint:** `/repair-requests`
    *   **Method:** `GET`
    *   **Description:** Retrieves all repair requests associated with the authenticated customer.
*   **Create Repair Request**
    *   **Endpoint:** `/repair-requests`
    *   **Method:** `POST`
    *   **Payload (JSON):** Requires product/device details and issue description.
    *   **Description:** Submits a new repair request ticket.
*   **Get Repair Request Details**
    *   **Endpoint:** `/repair-requests/{code}`
    *   **Method:** `GET`
    *   **Description:** Fetch details and status updates (Pending, Fixed, Canceled) for a specific repair request by its code.

---

## 5. Sylius Best Seller Plugin (Trending Products)
*(Note: Verify its API prefix in your `routes.yaml` as it might lack a global `/api/v1` mapping, but expected endpoints are below)*

*   **Get Best Sellers**
    *   **Endpoint:** `/best-sellers`
    *   **Method:** `GET`
    *   **Description:** Retrieves a list of the top-selling products in the catalog.
*   **Get Top Product**
    *   **Endpoint:** `/best-sellers/top`
    *   **Method:** `GET`
    *   **Description:** Retrieves the single highest-selling superstar product.
*   **Get Product Stats**
    *   **Endpoint:** `/products/{productId}/stats`
    *   **Method:** `GET`
    *   **Description:** Retrieves best-seller metrics (sales count, ranking) for a specific product.

---
### 🔒 Authentication Note for Flutter
*   **API Platform (`/api/v2/*`)**: Relies on the standard Sylius API Platform JWT authentication flow (`Bearer <token>`).
*   **Custom API (`/api/v1/*`)**: Verify if you manually configured restricted firewall rules in `security.yaml` for these routes. If not restricted, they may be publicly accessible or require passing the session/cookie. Add a JWT authenticator to `/api/v1` routes if they expose private customer data (like repair requests).
