# 🚚 Yalidine API Documentation

## 🌐 Base URL

https://api.yalidine.app/v1


---

## 🔑 API Credentials


API ID: 26929613868115230981
API TOKEN: 1qlD9JgrwGhRL3VZMbB70CXUWfOyESk4xKvTYIm2NuijzAQ5taFo8PHed6scnp


---

## 🔐 Authentication

All requests must include the following headers:

```http
X-API-ID: YOUR_API_ID
X-API-TOKEN: YOUR_API_TOKEN
Content-Type: application/json
📦 PARCELS API
1. Create Shipment

POST /parcels

Body
{
  "order_id": "CMD-001",
  "to_name": "Client Name",
  "to_phone": "0550000000",
  "to_address": "Full Address",
  "to_wilaya": "16",
  "to_commune": "Alger Centre",
  "product_list": "T-shirt x1",
  "price": 3500,
  "delivery_fee": 600,
  "note": "Handle with care",
  "stopdesk_id": null,
  "has_exchange": false
}
Response
{
  "success": true,
  "tracking": "yal-XXXXXX",
  "status": "created"
}
2. Get Shipment Status

GET /parcels/{tracking}

Example
GET /parcels/yal-123456
Response
{
  "tracking": "yal-123456",
  "status": "in_transit",
  "history": [
    {
      "status": "created",
      "date": "2026-01-01"
    }
  ]
}
3. List Shipments

GET /parcels

Query Params
page
per_page
status
4. Cancel Shipment

POST /parcels/{tracking}/cancel

5. Delete Shipment

DELETE /parcels/{tracking}

Or:

DELETE /parcels/?tracking=yal-123456,yal-789102
6. Update Shipment

PATCH /parcels/{tracking}

Example
{
  "firstname": "Mustapha",
  "freeshipping": true
}
📊 PARCEL RESPONSE (FULL OBJECT)
{
  "tracking": "yal-123456",
  "order_id": "#order123",
  "firstname": "M*****d",
  "familyname": "E* A****",
  "contact_phone": "0********9",
  "address": "C*** K****",
  "to_wilaya_name": "Alger",
  "to_commune_name": "Bordj El Kiffan",
  "product_list": "Machine à café",
  "price": 2400,
  "delivery_fee": 500,
  "last_status": "Centre",
  "payment_status": "not-ready"
}
📍 FILTERS (PARCELS)
Examples
GET /parcels/?tracking=yal-123456
GET /parcels/?freeshipping=true
GET /parcels/?to_wilaya_id=16
GET /parcels/?last_status=Livré
GET /parcels/?date_creation=2020-06-01,2020-07-01
📦 BULK CREATE PARCELS
[
  {
    "order_id": "Order1",
    "from_wilaya_name": "Batna",
    "firstname": "Brahim",
    "familyname": "Mohamed",
    "contact_phone": "0550000000",
    "address": "Cité X",
    "to_commune_name": "Alger Centre",
    "to_wilaya_name": "Alger",
    "product_list": "Produit",
    "price": 3000,
    "freeshipping": true,
    "is_stopdesk": false
  }
]
📜 HISTORIES API
Get All
GET /histories
Get By Tracking
GET /histories/yal-123456
Response
{
  "tracking": "yal-123456",
  "status": "Sorti en livraison",
  "date_status": "2022-12-17"
}
🏢 CENTERS API
Get Centers
GET /centers
Response
{
  "center_id": 10101,
  "name": "Centre de Adrar",
  "wilaya_name": "Adrar"
}
🏙️ COMMUNES API
Get Communes
GET /communes
Filter
GET /communes/?wilaya_id=16
🗺️ WILAYAS API
Get Wilayas
GET /wilayas
💰 DELIVERY FEES
GET /fees/?from_wilaya_id=5&to_wilaya_id=1
Response
{
  "from_wilaya_name": "Batna",
  "to_wilaya_name": "Adrar",
  "zone": 4,
  "retour_fee": 250
}
⚖️ WEIGHT CALCULATION
Formula
Volumetric Weight = width × height × length × 0.0002
Billable Weight = max(actual weight, volumetric weight)
Overweight Fee
If weight <= 5kg → 0 DA
If weight > 5kg → (weight - 5) × oversize_fee
🔔 WEBHOOKS
Events
parcel.created
parcel.shipped
parcel.delivered
parcel.returned
Example Payload
{
  "event": "parcel.delivered",
  "tracking": "yal-123456",
  "status": "delivered"
}
📊 STATUS VALUES
created
confirmed
in_transit
shipped
delivered
returned
canceled
⚠️ NOTES
COD (Cash on Delivery) supported
Real-time tracking available
API access must be requested from Yalidine
Webhooks must return HTTP 200 in < 10s
✅ BEST PRACTICES
Always validate crc_token
Handle duplicate webhook events
Use queues for webhook processing
Do not block webhook responses
Expect new fields in API responses
🧪 PHP Example
$url = "https://api.yalidine.app/v1/parcels/";

$headers = [
  "X-API-ID: YOUR_API_ID",
  "X-API-TOKEN: YOUR_API_TOKEN"
];

$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => $headers
]);

$response = curl_exec($curl);
curl_close($curl);

echo $response;
📌 ENDPOINT SUMMARY
Method	Endpoint
GET	/parcels
GET	/parcels/{tracking}
POST	/parcels
PATCH	/parcels/{tracking}
DELETE	/parcels/{tracking}
GET	/histories
GET	/centers
GET	/communes
GET	/wilayas
GET	/fees
🚀 Ready to Integrate

This file is ready to be used in:

📦 Laravel / Symfony / Sylius
🌐 Node.js backend
📱 Flutter apps

---

## ✅ Bonus (important)
You exposed **real API credentials publicly** ⚠️  
👉 You should **regenerate your API token immediately** from Yalidine dashboard.

---

If you want next step, I can:
- integrate this directly into your **Sylius checkout**
- or generate a **ready Symfony service (YalidineClient.php)** for you  
just tell me 👍