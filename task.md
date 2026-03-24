# Printer E-Commerce Platform - Task Breakdown

## Phase 1: Custom Sylius Theme (PrinterTheme) ✅
- [x] Create Sylius theme structure (themes/PrinterTheme/SyliusShopBundle/)
- [x] Override variables with printer-brand color palette (dark/cyan/orange)
- [x] Create custom homepage layout with hero banners, animations, categories grid
- [x] Override product listing templates with modern card UI + hover effects
- [x] Override product show page with gallery, specs accordion, related parts
- [x] Style checkout flow with step-by-step wizard UI
- [x] Add Google Fonts (Inter/Outfit), micro-animations, glassmorphism effects
- [x] Create responsive mobile-first layout

## Phase 2: Banner & CMS Plugin (`SyliusBannerPlugin`) ✅
- [x] Create `Banner` entity (image, title, subtitle, link, position, enabled, sort)
- [x] Create Doctrine XML mapping for Banner entity
- [x] Create admin CRUD for managing banners
- [x] Create Twig components for rendering banners (hero slider, promotional strips)
- [x] Expose Banner API endpoints (GET collection, GET item) for Flutter app
- [x] Create database migrations (MySQL + PostgreSQL)

## Phase 3: Best Seller Plugin (`SyliusBestSellerPlugin`)
- [x] Create service to compute best sellers from order data
- [x] Create admin config for best seller display settings
- [x] Create Twig component for homepage best seller section
- [x] Expose Best Seller API endpoint for Flutter app

## Phase 4: Offers/Promotions Display Plugin (`SyliusOffersPlugin`)
- [x] Create `Offer` entity (image, title, description, link, badge, start/end dates)
- [x] Create Doctrine XML mapping for Offer entity
- [x] Create admin CRUD for managing offers
- [x] Create Twig components (offer cards, offer banners, countdown timers)
- [x] Expose Offer API endpoints for Flutter app
- [x] Create database migrations (MySQL + PostgreSQL)

## Phase 5: Chargily Pay Payment Plugin (`SyliusChargilyPlugin`)
- [x] Install `chargily/chargily-pay` composer package
- [x] Create Chargily payment gateway factory
- [x] Create Chargily checkout action (redirect to Chargily hosted page)
- [x] Create webhook controller for payment notifications
- [x] Create admin configuration form for API keys
- [x] Add EDAHABIA and CIB payment method support
- [x] Create payment method templates for shop checkout

## Phase 6: Printer Repair Service Module
- [X] Create `RepairRequest` entity (device, issue, status, customer, etc.)
- [X] Create repair request form for shop frontend
- [X] Create admin management interface for repair requests
- [X] Create repair status workflow (submitted → diagnosed → in_progress → completed)
- [X] Expose Repair API endpoints for Flutter app
- [X] Create database migrations (MySQL + PostgreSQL)

## Phase 7: API Enhancements for Flutter App
- [ ] Verify all custom entities have proper API Platform resources
- [ ] Add serialization groups for all custom entities
- [ ] Test all API endpoints with PHPUnit
- [ ] Document API endpoints for Flutter integration

## Phase 8: Testing & Verification
- [ ] PHPUnit tests for all custom services
- [ ] API tests for all custom endpoints  
- [ ] Browser-based visual verification of theme
- [ ] Payment flow integration test
