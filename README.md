# 🚖 Maharana Travels — Laravel + Vue.js Website

A full-stack cab booking website built with **Laravel 11** (API backend) and **Vue 3 + Pinia + Vue Router** (SPA frontend), bundled with **Vite**.

---

## 📁 Project Structure

```
maharana-travels/
│
├── app/
│   └── Http/
│       └── Controllers/
│           └── Api/
│               ├── CabController.php       ← All cabs + search with pricing
│               ├── BookingController.php   ← Store booking
│               ├── ServiceController.php   ← Outstation route pages
│               └── ContactController.php  ← Contact form
│
├── routes/
│   ├── web.php          ← SPA catch-all → returns app.blade.php
│   └── api.php          ← All /api/* endpoints
│
├── resources/
│   ├── views/
│   │   └── app.blade.php           ← Single HTML shell, mounts Vue
│   │
│   ├── css/
│   │   └── app.css                 ← All site styles
│   │
│   └── js/
│       ├── app.js                  ← Vue entry point
│       ├── App.vue                 ← Root component (Header + RouterView + MobileBar)
│       │
│       ├── router/
│       │   └── index.js            ← All routes
│       │
│       ├── stores/
│       │   └── booking.js          ← Pinia store (search, selected cab, confirmation)
│       │
│       ├── composables/
│       │   ├── useApi.js           ← All HTTP calls (fetch wrapper)
│       │   └── useWatermark.js     ← Canvas watermark: covers old text, stamps Maharana Travels
│       │
│       ├── components/
│       │   ├── layout/
│       │   │   ├── AppHeader.vue   ← Top bar + sticky navbar
│       │   │   ├── AppFooter.vue   ← CTA section + footer
│       │   │   ├── MobileBar.vue   ← Fixed bottom call/WhatsApp bar (mobile)
│       │   │   └── FloatCall.vue   ← Floating call button (desktop)
│       │   │
│       │   ├── common/
│       │   │   ├── CabCard.vue     ← Reusable cab card with canvas image + Book Now
│       │   │   ├── PageHeader.vue  ← Reusable dark header with breadcrumb
│       │   │   └── LoadingSpinner.vue
│       │   │
│       │   └── home/
│       │       └── BookingForm.vue ← Hero booking widget (from/to/date/time/trip type)
│       │
│       └── pages/
│           ├── HomePage.vue        ← Hero + Routes + Cabs + Destinations + FAQ
│           ├── SearchResults.vue   ← Cabs with calculated pricing for a route
│           ├── BookingPage.vue     ← Vehicle summary + customer form
│           ├── BookingSuccess.vue  ← Thank you + booking reference
│           ├── ServicesPage.vue    ← Paginated service cards grid
│           ├── ServiceDetail.vue   ← One Way/Round Trip tabs + content + FAQ
│           ├── OurCabsPage.vue     ← Full cab fleet listing
│           ├── AboutPage.vue
│           ├── ContactPage.vue
│           ├── CitiesPage.vue
│           ├── WeddingPage.vue
│           └── NotFound.vue        ← 404 page
│
├── package.json
├── vite.config.js
└── README.md
```

---

## 🚀 Installation & Setup

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+ & npm
- MySQL / SQLite

---

### Step 1 — Clone & Install PHP Dependencies

```bash
git clone <your-repo-url> maharana-travels
cd maharana-travels
composer install
```

---

### Step 2 — Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```env
APP_NAME="Maharana Travels"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=maharana_travels
DB_USERNAME=root
DB_PASSWORD=your_password
```

---

### Step 3 — Database

```bash
php artisan migrate
# Optional: seed with sample data
php artisan db:seed
```

---

### Step 4 — Install JS Dependencies

```bash
npm install
```

---

### Step 5 — Run Development Servers

Open **two terminals**:

**Terminal 1 — Laravel:**
```bash
php artisan serve
# Runs at http://localhost:8000
```

**Terminal 2 — Vite (Hot Module Replacement):**
```bash
npm run dev
```

Now open **http://localhost:8000** in your browser. ✅

---

### Step 6 — Production Build

```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🌐 Page Routes

| URL | Page |
|-----|------|
| `/` | Home — booking form, popular routes, cabs, FAQ |
| `/search?from=X&to=Y&date=Z&time=T&trip_type=oneway` | Search results with pricing |
| `/booking` | Booking form (requires cab selected via store) |
| `/booking/success` | Confirmation page |
| `/our-services` | All service routes (paginated) |
| `/our-services/:slug` | Service detail — one way / round trip cabs |
| `/our-cabs` | Full cab fleet |
| `/about` | About Maharana Travels |
| `/contact` | Contact form |
| `/cities` | Cities we serve |
| `/wedding-cars` | Wedding car service |

---

## 🔌 API Endpoints

| Method | URL | Description |
|--------|-----|-------------|
| `GET` | `/api/cabs` | All cabs (no pricing) |
| `GET` | `/api/cabs/search?from=Delhi&to=Shimla&date=2026-05-01&time=10:00&trip_type=oneway` | Cabs with route pricing |
| `GET` | `/api/services` | All outstation service routes |
| `GET` | `/api/services/{slug}` | Single service details |
| `POST` | `/api/bookings` | Create booking |
| `POST` | `/api/contact` | Send contact message |

---

## 🎨 Key Features

- **Canvas Watermark** — Car images are drawn on `<canvas>`. The bottom strip (where "Sharma Travels" text is baked into the photos) is covered with a matching colour block, then "🚖 Maharana Travels" + tagline is painted over it cleanly.
- **Pinia Store** — Search params and selected cab persist across route navigation (Home → Search → Booking → Success).
- **Reactive Pricing** — Fare = `price_per_km × distance_km × (2 if round-trip) × 0.95` (5% discount shown).
- **SPA Navigation** — Vue Router with `createWebHistory()`. Laravel catches all non-API routes and returns the Vue shell.
- **Mobile First** — Fixed bottom bar (Call + WhatsApp), hamburger menu, fully responsive grid.

---

## 🔧 Customisation

### Change brand details
Edit `app/Http/Controllers/Api/CabController.php` (backend) and update the `BRAND` object references in Vue components.

### Add real DB support
Replace the array-based data in controllers with Eloquent models:
```bash
php artisan make:model Cab -m
php artisan make:model Booking -m
php artisan make:model ServiceRoute -m
```

### Add email notifications
```bash
php artisan make:mail BookingConfirmation
```
Then in `BookingController::store()`:
```php
Mail::to($validated['email'])->send(new BookingConfirmation($validated));
```

---

## 📞 Contact

**Maharana Travels**
- Phone: +91 9416198045
- Email: maharanatravels0001@gmail.com
- Address: Maharana Travels, Shop No 7, Juneja Square, Highland Marg, Zirakpur, Nabha, Punjab 140603
