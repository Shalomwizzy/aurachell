# Aurachell — Project Tracker

## Stack
- Laravel 10 · Blade · Livewire 3 · Alpine.js
- TailwindCSS (`darkMode: 'class'`, custom brand scale)
- MySQL · Paystack · Mailtrap SMTP (dev) → real SMTP in production
- Groq AI (customer chatbot) · Gemini 1.5 Flash (admin AI)
- Google Analytics 4 (GA Data API for admin analytics dashboard)

## Brand Colors
| Token | Hex | Usage |
|---|---|---|
| Primary / Mahogany | `#6B2016` | Buttons, active states, accents |
| Base / Warm Sand | `#D4B8A0` | Secondary fills |
| Ghost / Caramel | `#C4A48C` | Hover, muted accents |
| Surface | `#F5EDE4` | Page backgrounds |
| Text Dark | `#2C0F0A` | Headings, body |
| Admin BG | `#130B09` | Admin sidebar/canvas |
| Admin Gold | `#C4A48C` | Admin highlights |

CSS variables live in `resources/css/aurachell.css`.
Tailwind aliases live in `tailwind.config.js` under `theme.extend.colors`.
**No inline CSS** — always use CSS variables or Tailwind classes.

## Key Files
| File | Purpose |
|---|---|
| `resources/css/aurachell.css` | Brand CSS variable definitions |
| `resources/js/aurachell.js` | ThemeManager, Alpine stores, cart AJAX, AI helpers, toast |
| `resources/views/layouts/admin.blade.php` | Admin shell with grouped collapsible sidebar |
| `resources/views/layouts/app.blade.php` | Frontend shell with dark mode, navbar, chatbot |
| `app/Http/Controllers/Admin/AiController.php` | Gemini + Groq endpoints |
| `app/Http/Controllers/Admin/BlogController.php` | Blog CRUD |
| `app/Http/Controllers/Admin/SettingController.php` | Store settings + cache clear |
| `app/Http/Controllers/Admin/AnalyticsController.php` | GA4 Data API dashboard |
| `app/Models/BlogPost.php` | Blog model (tags cast to array) |
| `app/Models/Setting.php` | Key-value store, `Setting::get()` / `Setting::set()` |

## Admin Sidebar Groups
1. **Catalog** — Products, Categories, Low Stock, Product Requests, Pre-orders
2. **Finance** — All Orders, Pending, Paid, Shipped, Delivered, Coupons, Reports & Analytics, Google Analysis
3. **Customers** — All Customers, Messages, Newsletter, Email Campaigns
4. **AI Studio** — AI Assistant, Chat Logs
5. **System** — Staff, Pages, Settings, Activity Log

## Env Variables Required
```
APP_NAME=Aurachell
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_DATABASE=aurachell
DB_USERNAME=root
DB_PASSWORD=

PAYSTACK_PUBLIC_KEY=
PAYSTACK_SECRET_KEY=
PAYSTACK_PAYMENT_URL=https://api.paystack.co
MERCHANT_EMAIL=admin@yourdomain.com

GROQ_API_KEY=
GROQ_MODEL=llama-3.3-70b-versatile
GROQ_API_URL=https://api.groq.com/openai/v1/chat/completions

GEMINI_API_KEY=

ADMIN_EMAIL=admin@yourdomain.com
ADMIN_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hello@aurachell.com
MAIL_FROM_NAME="Aurachell"

GOOGLE_ANALYTICS_ID=
ANALYTICS_PROPERTY_ID=
```

## Order Status Flow
`pending` → `paid` (Paystack callback) → `processing` → `packed` → `shipped` → `out_for_delivery` → `delivered`
Also: `cancelled`, `refunded`

Customer email sent automatically on every status change (except pending/paid which have their own dedicated mails).

## Automated Emails (Artisan Commands)
| Command | Trigger | Description |
|---|---|---|
| `emails:new-product` | Manual / after product publish | Notify subscribers of new product |
| `emails:wishlist-reminder` | Scheduled weekly (Mon 9am) | Remind users items in wishlist still available |
| `emails:cart-reminder` | Scheduled daily (10am) | 3-stage cart abandonment: 24h / 48h / 72h |
| `emails:birthday` | Scheduled daily (9am) | Birthday email to users whose birthday is today |
| `emails:review-request` | Scheduled daily (11am) | Review request 7 days after delivery |
| `emails:reorder-reminder` | Scheduled daily (1pm) | Reorder nudge 60 days after last delivery |
| `emails:back-in-stock` | Manual / `--product=ID` | Notify wishlist users when product restocks |
| `emails:festive` | Manual with `--event=` flag | Christmas / Easter / Eid / Ramadan / New Year |
| `emails:new-month` | Scheduled 1st of month (8am) | Happy new month + featured products |

Mailables: `app/Mail/` — `OrderConfirmationMail`, `OrderShippedMail`, `OrderStatusUpdateMail`, `DeliveryCompletedMail`, `AdminOrderNotificationMail`, `WelcomeMail`, `ThankYouMail`, `NewProductMail`, `WishlistReminderMail`, `CartAbandonmentMail` (stage 1/2/3), `BirthdayMail`, `ReviewRequestMail`, `BackInStockMail`, `ReorderReminderMail`, `FestiveMail`, `NewMonthMail`, `NewsletterBroadcastMail`, `StaffInviteMail`, `ProductRequestFulfilledMail`, `AdminDeliveryNotificationMail`, `AdminReviewNotificationMail`

## Completed Phases
- [x] Phase 1: Project setup, migrations
- [x] Phase 2: All models with relationships
- [x] Phase 3: Roles/permissions seeder
- [x] Phase 4: Frontend layouts, home, shop, product, cart, checkout
- [x] Phase 5: User account dashboard (orders, addresses, wishlist, reviews, profile)
- [x] Phase 6: Admin dashboard, orders, products, categories, customers, coupons, reviews, messages, blog
- [x] Phase 7: Paystack integration + payment callback + webhook
- [x] Phase 8: Groq chatbot (Livewire) — order lookup by order number OR tracking code
- [x] Phase 9: Email mailables — all transactional + campaign emails built and wired
- [x] Phase 10: SEO — sitemap.xml, robots.txt, meta tags, OG tags, JSON-LD on product pages
- [x] Phase 11: .env.example with all variables documented
- [x] Admin AI Studio (Gemini + Groq chat)
- [x] Blog system (frontend + admin CRUD)
- [x] AI product description generation (Gemini)
- [x] Dark/light mode (class-based, localStorage)
- [x] Brand color system (CSS variables + Tailwind config)
- [x] Google Analytics 4 integration (tag injection + GA Data API admin dashboard)
- [x] Admin analytics dashboard (sessions, users, pageviews, top pages, revenue chart)
- [x] Order status emails — automated email on every status change
- [x] Low stock page + sidebar badge (≤3 units triggers alert)
- [x] Admin role-based sidebar visibility (permissions per section)
- [x] Production asset build (`npm run build` — CSS 104KB / JS 91KB gzipped)
- [x] `packed` status added to order enum + migration updated
- [x] Shipping zones & rates system (admin-configurable per zone, auto-calculated at checkout)
- [x] Referral program (cookie tracking, register linkage, post-purchase reward, admin controls)
- [x] `spatie/laravel-backup` installed and configured
- [x] Branded error pages (404, 419, 429, 500, 503)
- [x] Stock oversell protection (validated at checkout before payment)
- [x] Returns & refund requests system (customer submit + admin review/approve/reject, configurable window)
- [x] Facebook Pixel integration (admin Tracking tab, PageView + ViewContent + Purchase events, cookie-consent gated)
- [x] Honeypot + rate limiting on checkout, contact, and newsletter forms

- [x] Paystack reconciliation cron (every 30 min, dry-run flag, emails on recovery)
- [x] README.md — local setup, env reference, deployment, cron, troubleshooting
- [x] Scent selection on product page — Alpine pill buttons, required only when product has scent notes, tracked through cart → order → admin view → confirmation email
- [x] Product Request system — frontend form (name, description, scent preference, budget, optional image upload), admin CRUD with status tabs (pending/viewed/fulfilled), sidebar badge for pending count, automatic fulfilled email (`ProductRequestFulfilledMail`) sent to customer on status change
- [x] Admin order notifications — now sent to `ADMIN_EMAIL` from env (via `config('services.admin.email')`) + each sales rep's registered email; no longer uses hardcoded DB email
- [x] Product image management — per-image delete via AJAX fetch (no nested form), set-primary via AJAX, upload preview before save (Alpine FileReader, native input untouched so upload is reliable)
- [x] Product edit page — Danger Zone (permanent delete) moved outside the update `<form>` to prevent `_method=DELETE` override silently deleting products on every save
- [x] Product toggle/restore — `withTrashed()->findOrFail()` so soft-deleted products can be restored without 404
- [x] `uniqueSlug` — now checks `withTrashed()` to avoid DB unique-constraint collision with soft-deleted slugs
- [x] Admin sidebar product-requests badge — wrapped in try/catch so missing migration doesn't crash every admin page
- [x] Admin Abandoned Carts page — view all abandoned carts per user, send individual or bulk reminders, clear carts, expandable item previews
- [x] Admin Wishlists page — view each user's wishlist items, expandable product cards with images and prices
- [x] Premium email system — all transactional + lifecycle emails redesigned (dark hero, preheader, gold accents, philosophy quotes, brand voice)
- [x] 3-stage cart abandonment emails — Stage 1 (24h gentle), Stage 2 (48h emotional/aspirational), Stage 3 (72h urgency/expiry), tracked via `reminder_count` on carts table
- [x] Birthday email — personalized with optional coupon code, scheduled daily
- [x] Review request email — sent 7 days post-delivery, star rating links
- [x] Back-in-stock email — notify wishlist users when a wishlisted product restocks
- [x] Reorder reminder email — sent 60 days after delivery to re-engage past customers
- [x] `birthday` column added to users table (migration: `2026_05_26_000001`)
- [x] `last_reminder_at` + `reminder_count` columns added to carts table (migration: `2026_05_26_000000`)
- [x] Pre-order system — out-of-stock products show a Pre-Order button (product page form, shop/home card CTAs); creates `preorders` row, emails customer (`PreorderConfirmationMail`) + admin/sales reps (`AdminPreorderNotificationMail`); duplicate pending pre-orders per email blocked; admin page under Catalog with status tabs (pending/contacted/fulfilled/cancelled) + sidebar badge; JSON-LD availability now `PreOrder` when out of stock (migration: `2026_07_18_000001`)
- [x] Free shipping fully removed — dropped `free_shipping_threshold` column (migration: `2026_07_18_000002`), stripped from ShippingRate/ShippingService/admin zone form/cart/checkout/product copy/seeders/settings; announcement bar DB value replaced
- [x] Hero refresh — removed "For Moments That Stay With You" eyebrow and "Signature Blend / Oud & Amber" floating chip; pull quote now "Thoughtfully crafted fragrances for the moments and spaces you love most"
- [x] Email palette repaired — base layout + 14 templates had every brand token flattened to maroon `#371220` (invisible logo/eyebrows/borders on dark backgrounds); gold `#C9A96F` / sand `rgba(201,169,111,x)` / cream `#FAF5ED` restored
- [x] Paystack amount verification — callback + webhook now reject payments below the order total (mirrors existing Flutterwave check)
- [x] Queue drain scheduled — `queue:work --stop-when-empty --max-time=55` every minute (shared-hosting safe; queued mails like bank-transfer/returns now actually send)
- [x] AiController now reads Groq/Gemini credentials from `config('services.*')` (was raw `env()` — broken under `config:cache`); `services.gemini.model` added
- [x] Review guard — one review per user per product; resubmission updates the review and resets `is_approved` to false
- [x] Stock decrement floored at 0 via `GREATEST(...)` SQL — concurrent payments can no longer drive stock negative
- [x] Shipping zones now matched by **city** instead of state — `shipping_zones.states` column renamed to `cities` (migration: `2026_07_21_000001`); admin zone form field is now "Cities (comma-separated)"; `ShippingZone::forCity()` / `ShippingService::getRatesForCity()`; checkout keeps both City + State address fields but the shipping fee is looked up from the customer's **City** input (case-insensitive, trimmed, exact match against the zone's city list); cart preview + copy updated; seeder reseeded with Lagos city zones
- [x] Admin email design repaired — the `.value` CSS class was used by 7 templates but never defined in `emails/layouts/base.blade.php` (values under labels rendered unstyled); defined it once. `admin-order-notification` + `admin-preorder-notification` given `.eyebrow`, preheader, shared `.btn`; dropped leftover "Free" shipping text
- [x] Staff activate/deactivate now persists — `is_blocked` added to `User` `$fillable` + cast boolean (mass-assignment was silently dropping it); staff Remove button recolored `var(--adm-text)` so it's visible in dark mode
- [x] Admin notified on **order delivered** — `AdminDeliveryNotificationMail` + `emails/admin-delivery-notification.blade.php`; fires in `Admin/OrderController::updateStatus` when status → `delivered`, sent to `ADMIN_EMAIL` only (hello@), alongside the existing customer `DeliveryCompletedMail`
- [x] Remaining 4 plain email templates rebuilt on the shared base layout — `verify-email`, `bank-transfer-submitted` (admin), `bank-transfer-approved`, `bank-transfer-rejected`; previously standalone HTML with their own palette (verify-email also had the invisible maroon-on-maroon logo bug)
- [x] Mobile admin sidebar re-synced with desktop — the mobile nav (`#adm-mob-sidebar`) had drifted and was missing Bank Transfers, Abandoned Carts, Wishlists, Product Requests, Pre-orders; all added (Product Requests/Pre-orders keep their pending-count badges)
- [x] Admin notified on **new/updated review** — `AdminReviewNotificationMail` + `emails/admin-review-notification.blade.php`; fires in `AccountController::storeReview` (both create + resubmit) to `ADMIN_EMAIL`; review still requires admin approval before showing

## Pending / Next Steps
- [ ] Switch mail provider to production SMTP (Mailgun/Resend/Postmark) — client does this
- [ ] Switch Paystack keys to live (pk_live / sk_live) — client does this
- [ ] Run `php artisan migrate --force` on server for: `product_requests` table, `scent_note` on `cart_items` and `order_items`, `last_reminder_at`/`reminder_count` on `carts`, `birthday` on `users`, `preorders` table, drop `free_shipping_threshold` from `shipping_rates`, rename `states`→`cities` on `shipping_zones` (`2026_07_21_000001`)
- [ ] After the shipping-zones migration: re-enter each zone's areas in Admin → Shipping as **cities** (old rows keep their previous state names in the renamed `cities` column until edited)
- [ ] On server: update `announcement_bar` setting if it still mentions free shipping (Admin → Settings), and delete the `free_shipping_threshold` settings row
- [ ] Create `public/images/product-requests/` directory on server
- [ ] Run `php artisan config:clear && php artisan cache:clear` after uploading changed files
- [ ] (Optional) Add birthday field to user profile edit page so customers can set it

## Critical Blade Compiler Note
**Never put `@php`, `@if`, `@section`, or any Blade directive inside a `{{-- comment --}}`.**
Laravel's `storePhpBlocks()` runs BEFORE comments are stripped. A `@php` inside a Blade comment will cause it to consume all content until the next `@endphp`, silently dropping hundreds of lines from the compiled output with no error logged.

## Public Directory Structure
```
public/
├── index.php              # Laravel entry point
├── .htaccess              # Apache rewrite rules (redirect all to index.php)
├── favicon.ico
├── robots.txt             # Generated by SeoController — do not hand-edit
├── manifest.json          # PWA web app manifest (name, icons, shortcuts, theme colour)
├── sw.js                  # PWA service worker (caching/offline) — separate from OneSignal
├── OneSignalSDKWorker.js  # OneSignal service worker — OneSignal looks for this exact filename
├── blueprint.html         # Project blueprint reference page
├── build/                 # Vite compiled assets (committed, do not hand-edit)
│   ├── manifest.json      # Vite asset manifest
│   └── assets/
│       ├── app-*.css      # Compiled TailwindCSS (~104KB gzipped)
│       └── app-*.js       # Compiled Alpine + aurachell.js (~91KB gzipped)
├── storage/               # Symlink → storage/app/public (run `artisan storage:link`)
└── images/                # All user-uploaded and static images (NOT in git — server only)
    ├── products/          # Product images — filename: prod_<uniqid>.<ext>
    ├── product-requests/  # Customer request images — filename: req_<uniqid>.<ext>
    ├── blog/              # Blog post cover images
    ├── categories/        # Category images
    ├── avatars/           # User avatar uploads
    └── icons/             # PWA icons (72/96/128/144/152/192/384/512 px PNGs + SVG)
```

**Upload rules:**
- Never commit files under `public/images/` — they live only on the server
- After `npm run build`, commit the updated `public/build/` directory
- The `public/storage` symlink must exist on the server (`php artisan storage:link`)
- `public/manifest.json` is the PWA manifest — edit it here, not in `resources/`
- `public/OneSignalSDKWorker.js` must exist and contain `importScripts('https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.sw.js');` — OneSignal hardcodes this filename; `sw.js` is the PWA worker and is separate

## Notes
- Admin middleware: `EnsureIsAdmin` checks `role` is `admin` or `super_admin`
- `is_blocked` column on users table — blocked users are rejected at login
- Blog cover images: `public/images/blog/`
- Product images: `public/images/products/`
- Product request images: `public/images/product-requests/`
- Settings (logo, store name, GA ID, etc.) stored in `settings` key-value table via `Setting` model
- Cart uses session-based `CartService` — no auth required, guest checkout supported
- Admin notification email fires ONLY after Paystack payment confirmed (not on order create)
- Order status enum: `pending`, `paid`, `processing`, `packed`, `shipped`, `out_for_delivery`, `delivered`, `cancelled`, `refunded`
- `orders.status` DB column must match enum above — use migration + `DB::statement ALTER` if adding new values
- `ADMIN_EMAIL` env var is accessed via `config('services.admin.email')` — never call `env()` directly in controllers/commands (broken when config is cached)
- **Never nest a `<form>` inside another `<form>`** — browsers strip the inner `<form>` tag but keep its hidden inputs (including `_method`), which causes the outer form to submit with the wrong HTTP verb. Always put secondary forms (delete, restore) outside the primary update form.
- Product request status enum: `pending`, `viewed`, `fulfilled` — email auto-sent to customer on `fulfilled`
- Pre-order status enum: `pending`, `contacted`, `fulfilled`, `cancelled` — customer + admin emails sent on creation (frontend `PreorderController@store`); duplicate pending pre-order per product+email is rejected
- **Admin sidebar is duplicated** — `layouts/admin.blade.php` has TWO separate nav lists: `#adm-mob-sidebar` (mobile, flat list, uses `$cr`) and `#adm-desktop-sidebar` (desktop, collapsible groups, uses `$currentRoute` + permission gating). When adding/removing an admin nav item you MUST edit BOTH or they drift out of sync (mobile silently missing items).
- No `[x-cloak]` CSS rule exists in this project — for Alpine `x-show` panels that start hidden, use inline `style="display:none"` instead of `x-cloak`
