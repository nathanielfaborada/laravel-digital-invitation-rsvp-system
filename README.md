# Invitr - Digital Invitation & RSVP System

> A digital invitation and live RSVP tracking system built with Laravel, Tailwind CSS, and Alpine.js.

---

## Overview

**Invitr** is a web application designed for event hosts to create branded digital invitations, distribute personalized guest links, and monitor attendee RSVPs in real time.

Instead of generic forms or manual spreadsheet tracking, Invitr provides:
- **Unique Guest Links**: Every guest gets a dedicated, non-guessable link (`/invite/{unique_code}`) to view their invitation and submit their RSVP with companion details without needing to register or log in.
- **Real-Time RSVP Tracking**: The host dashboard updates live attendance numbers (Attending, Not Attending, Pending, Headcount) via background polling without page refreshes.
- **Brevo REST API Mailer**: Direct API-based transactional email delivery via Brevo (Sendinblue) HTTP API to prevent SMTP timeout issues on cloud hosting providers.
- **Email Verification Auto-Redirect**: Automatically detects when a newly registered host verifies their email on another device/tab and redirects them straight to the dashboard.
- **Template Switcher**: Multiple built-in visual styles (Classic, Modern, Floral) with responsive live previews.
- **Safe CSV Exports**: Download attendee lists with built-in protection against formula injection attacks (`=`, `+`, `-`, `@`).

---

## Tech Stack

- **Backend Framework:** Laravel 11 / 12 (PHP 8.2+)
- **Database:** MySQL / MariaDB (Compatible with SQLite and PostgreSQL)
- **Frontend:** Blade Templates, Tailwind CSS, Alpine.js, SweetAlert2
- **Asset Bundler:** Vite
- **Email Delivery:** Brevo (Sendinblue) HTTP REST API via `symfony/brevo-mailer` & `symfony/http-client`
- **Media & File Storage:** Cloudinary via `cloudinary-labs/cloudinary-laravel`
- **Deployment / Hosting:** Railway / VPS (configured with `TrustProxies` and reverse proxy HTTPS support)

---

## Getting Started

### Prerequisites

- PHP >= 8.2 (with `pdo`, `mbstring`, `openssl`, `curl`, `gd` extensions enabled)
- Composer >= 2.x
- Node.js >= 18.x and npm
- MySQL or MariaDB

### Local Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/nathanielfaborada/laravel-digital-invitation-rsvp-system.git
   cd laravel-digital-invitation-rsvp-system
   ```

2. **Install PHP and Node dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Configure environment variables**
   ```bash
   cp .env.example .env
   ```

   Open `.env` and set your local database credentials and API keys:
   ```dotenv
   APP_NAME=Invitr
   APP_ENV=local
   APP_KEY=
   APP_DEBUG=true
   APP_URL=http://localhost:8000

   # Database
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=invitation_rsvp_db
   DB_USERNAME=root
   DB_PASSWORD=

   # Brevo HTTP API Mailer
   MAIL_MAILER=brevo
   BREVO_API_KEY=your_brevo_api_key_here
   MAIL_FROM_ADDRESS="hello@yourdomain.com"
   MAIL_FROM_NAME="${APP_NAME}"

   # Cloudinary Storage (Optional for cover photos)
   CLOUDINARY_URL=cloudinary://API_KEY:API_SECRET@CLOUD_NAME
   ```

4. **Generate app key & run migrations**
   ```bash
   php artisan key:generate
   php artisan migrate
   ```

5. **Build assets & start development servers**
   ```bash
   # Run Vite asset compiler
   npm run dev

   # In a separate terminal, start Laravel server
   php artisan serve
   ```

   Visit `http://localhost:8000` in your browser.

---

## Architecture & Codebase Breakdown

### Backend

#### 1. Models & Database Relationships
- `User`: Event host account implementing `MustVerifyEmail`. Has many `Event`s.
- `Event`: Event details (title, description, event_date, event_time, venue, cover_image, template). Belongs to `User`, has many `Guest`s.
- `Guest`: Guest record with an auto-generated 10-character `unique_code`, contact info, and `max_companions`. Belongs to `Event`, has one `Rsvp`.
- `Rsvp`: Response record (status: `attending` / `not_attending`, `companions_count`, `companion_name`, `message`, `responded_at`). Belongs to `Guest`.

#### 2. Key Controllers & Routes
- `EventController`:
  - `index()`, `create()`, `store()`, `show()`, `edit()`, `update()`, `destroy()`: Full event management workflow.
  - `rsvpStats(Event $event)`: Returns JSON `{ stats: {...}, guests: [...] }` for live polling.
  - `dashboardStats()`: Returns JSON `{ stats: {...}, events: [...] }` for aggregate counts.
  - `export(Event $event)`: Streams sanitized CSV guest lists.
- `GuestController`:
  - `store()`: Validates and creates guest, optionally sending invite email.
  - `sendInvite(Guest $guest)`: Triggers transactional invite email via Brevo API.
  - `bulkDestroy()`: Handles multi-guest deletion requests.
- `InviteController` & `RsvpController`:
  - Public routes (`/invite/{unique_code}`) allowing guests to view invitations and submit RSVPs without authentication.
- `EmailVerificationPromptController`:
  - Renders the `/verify-email` view and provides `GET /email/verification-status` JSON endpoint for real-time verification polling.

#### 3. Middleware & Security Handling
- **Reverse Proxy & HTTPS Enforcement (`app/Providers/AppServiceProvider.php` & `bootstrap/app.php`)**:
  - Sets `$middleware->trustProxies(at: '*')` to trust upstream headers (`X-Forwarded-Proto`, `X-Forwarded-For`).
  - Automatically invokes `URL::forceScheme('https')` in production or when `HTTP_X_FORWARDED_PROTO` is present, fixing `403 INVALID SIGNATURE` errors on email verification URLs.
- **Authorization (`app/Policies/EventPolicy.php`)**:
  - Enforces strict event-level ownership checks across view, edit, delete, and guest management actions.
- **Formula Injection Defense (`GuestController.php`)**:
  - Sanitizes guest names and inputs starting with `=`, `+`, `-`, or `@` to prevent CSV spreadsheet injection vulnerabilities.

---

### Frontend

#### 1. Blade Views & Layout Structure
```
resources/views/
├── layouts/
│   ├── app.blade.php           # Authenticated host layout (header, nav, modals, OG tags)
│   ├── guest.blade.php         # Public auth layout (login, register, verification)
│   └── navigation.blade.php    # Responsive navigation bar
├── events/
│   ├── index.blade.php         # Host dashboard (active vs. archived tabs, filters)
│   ├── show.blade.php          # Event management room with live template preview
│   ├── create.blade.php        # Event creation form
│   ├── edit.blade.php          # Event edit form
│   └── preview/                # Template preview components (classic, modern, floral)
├── guests/
│   └── index.blade.php         # Reactive guest list & real-time counter cards
├── invite/
│   ├── show.blade.php          # Public guest invitation view
│   └── confirmation.blade.php  # RSVP confirmation state
└── auth/
    └── verify-email.blade.php  # Email verification view with auto-redirect polling
```

#### 2. JavaScript Polling Logic
- **RSVP Dashboard Updates (`guests/index.blade.php`)**:
  - An Alpine.js component initializes an interval polling `GET /events/{event}/rsvp-stats` every 3 seconds.
  - Updates reactive properties `this.stats` and `this.guests` dynamically without page refreshes or UI flicker.
- **Email Verification Auto-Redirect (`auth/verify-email.blade.php`)**:
  - Vanilla JS `fetch()` polls `GET /email/verification-status` every 3 seconds.
  - Immediately redirects to `/dashboard` (`window.location.href = '/dashboard'`) once verified.
- **Dashboard Events Sync (`events/index.blade.php`)**:
  - Polls `GET /dashboard/stats` every 4 seconds to sync active and archived event counts.

---

## Testing

The project includes an automated PHPUnit/Pest test suite covering authentication, signed route verification behind reverse proxies, guest validation, CSV formula injection defense, polling endpoints, and authorization policies.

Run the test suite:
```bash
php artisan test
```

---

## License

This project is open-sourced under the [MIT License](LICENSE).
