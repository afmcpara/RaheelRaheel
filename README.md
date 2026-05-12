# Ship2Aruba — Package Forwarding Platform

Ship2Aruba is a full-stack package forwarding management system that lets customers shop from US retailers, consolidate multiple packages into a single shipment, and have their items forwarded to Aruba. The platform exposes two completely separate experiences: a **client portal** for end customers and an **admin portal** for warehouse and operations staff.

---

## Table of Contents

1. [Tech Stack](#tech-stack)
2. [Features](#features)
3. [Admin Modules](#admin-modules)
4. [Client Modules](#client-modules)
5. [Public-Facing Site](#public-facing-site)
6. [Database Schema](#database-schema)
7. [Package Lifecycle](#package-lifecycle)
8. [Project Structure](#project-structure)
9. [Getting Started](#getting-started)
10. [Default Credentials](#default-credentials)
11. [Common Commands](#common-commands)
12. [Security Notes](#security-notes)

---

## Tech Stack

### Backend
| Layer | Technology |
|---|---|
| Language | **PHP 8.3+** |
| Framework | **Laravel 13** |
| ORM | **Eloquent** |
| Authentication | Laravel built-in auth (sessions, hashed passwords via Bcrypt) |
| Authorization | Custom `EnsureRole` middleware (admin / client) |
| File storage | Laravel filesystem (`public` disk) for invoice uploads |
| Validation | Laravel form-request validation rules (incl. `Password::min(8)`, `current_password`) |

### Frontend
| Layer | Technology |
|---|---|
| Templating | **Blade** (Laravel views) |
| Styling | **Tailwind CSS 4 + custom CSS design system** (`resources/css/app.css`) |
| JavaScript | **Vanilla ES modules** — no jQuery / framework |
| Charts | **Chart.js 4** (bar chart on the dashboards) |
| Build tool | **Vite 8** + `laravel-vite-plugin` |
| Fonts | Self-hosted **Instrument Sans** (woff/woff2) |
| Icons | Inline SVG (consistent stroke-style icons everywhere) |

### Database
| Layer | Technology |
|---|---|
| RDBMS | **MySQL 8** (or MariaDB 10.6+) |
| Sessions | `database` driver (persistent sessions) |
| Migrations | Laravel migrations under `database/migrations/` |
| Seeders | `AdminClientSeeder` — creates an admin user + ~9 realistic US clients + 18 sample packages, invoices, ship requests, and full status history |

### Dev / Tooling
- **Laravel Pint** (code style)
- **PHPUnit 12** (test runner)
- **Faker** (factories)
- **Laravel Pail** (live log tail)
- **Laravel Tinker** (REPL)

---

## Features

- Two separate, role-gated portals (admin + client) with a dedicated admin login URL.
- Self-service client registration with **auto-assigned US suite numbers** (e.g. `S2A-1042`).
- Full invoice → review → ship-request → delivery lifecycle, enforced server-side.
- Live **Chart.js** status-distribution chart on both dashboards.
- AJAX invoice upload with real-time progress, client-side size validation, and JSON error feedback.
- Searchable client combobox for the admin's "Log Package" form (handles 1000+ clients).
- Search + status filter + paginated lists (10/25/50/100 per page) on every admin & client list view.
- Animated, full-bleed marketing landing page (paper plane, drifting clouds, route line with US → Aruba pin, floating cargo boxes, KPI counters).
- "My Account" page (shared by clients and admins) to edit name / email and change password.
- Branded SVG logo + custom favicon used across every layout.
- `robots.txt` blocks `/admin/`, `/client/`, and `/account` from search engines.
- `prefers-reduced-motion` support — all hero animations gracefully disable themselves.

---

## Admin Modules

All admin routes live under `/admin/*` and require the `admin` role. Sign in via `/admin/login` (not the regular `/login`, which rejects admin accounts for security).

| # | Module | Route | What it does |
|---|---|---|---|
| 1 | **Dashboard** | `/admin/dashboard` | KPI cards (total packages, pending reviews, shipped, clients), recent packages table, invoice queue widget, Chart.js status distribution + per-status tiles with progress bars. |
| 2 | **Packages — Log + List** | `/admin/packages` | Full-width horizontal form to log a new package (searchable client combobox, tracking, dimensions, weight, description). Below: searchable/filterable paginated package table. |
| 3 | **Package Detail** | `/admin/packages/{id}` | Complete package info, owning client, attached invoice (with file viewer link), full status history timeline, and inline buttons to mark as `Ready for Pickup` or `Delivered` when valid. |
| 4 | **Clients — List + Search** | `/admin/clients` | Paginated client list with name / email / suite / package count / join date. Search by name, email, or suite. Each row has an **Add Package** shortcut that deep-links to the log form with that client pre-selected. |
| 5 | **Client Profile** | `/admin/clients/{id}` | Client details + their entire package history with links to each package detail. |
| 6 | **Invoice Queue** | `/admin/invoices` | Compact row-based list of all pending invoices with one-click **Approve** and an inline **Flag** form (collapsible textarea, "send back to client" with admin note). Search + pagination. |
| 7 | **Invoice File Viewer** | `/admin/invoice-file/{id}` | Streams the uploaded invoice (PDF / image) inline. |
| 8 | **Ship Requests** | `/admin/ship-requests` | Compact list of all client ship requests — request #, client, package count + preview tags, status chip, and one-click **Mark as Shipped**. Status filter (Submitted / Processed) + search + pagination. |
| 9 | **My Account** | `/account` | Shared profile page — admin can update name / email and change password. |

### Admin-only workflow buttons
- **Approve invoice** → moves package from `pending_invoice_review` → `invoice_approved`.
- **Flag invoice** → moves package from `pending_invoice_review` → `needs_review` with an admin note.
- **Mark as Shipped** (on ship request) → moves every attached package to `shipped`.
- **Mark Ready for Pickup** → `shipped` → `ready_for_pickup`.
- **Mark Delivered** → `shipped` or `ready_for_pickup` → `delivered`.

All transitions are enforced by `Package::transitionTo()` — invalid jumps are blocked.

---

## Client Modules

All client routes live under `/client/*` and require the `client` role. Sign in via `/login` (the regular login page) or self-register via `/register`.

| # | Module | Route | What it does |
|---|---|---|---|
| 1 | **Dashboard** | `/client/dashboard` | KPI cards (Total / Needs your action / Ready to ship / In transit), recent packages table, quick-action shortcuts (Upload invoices, Create ship request, Track shipments), and a Chart.js status overview + per-status tiles. |
| 2 | **My Packages** | `/client/packages` | Filter chips at the top (`All`, `Action needed`, `In review`, `Ready to ship`, `In transit`, `Delivered` — each with live counts; "Action needed" turns red when there's anything urgent). Search bar. Slim list rows with status-colored left border, inline **AJAX invoice upload** (PDF/JPG/PNG, max 2 MB, live progress bar). Server-side ordering puts action-required items first. Pagination. |
| 3 | **Package Detail** | `/client/packages/{id}` | Package info, invoice status + file link, full status history timeline, and the upload zone for `ready_to_send` or `needs_review` packages. |
| 4 | **Create Ship Request** | (panel on `/client/packages`) | Compact selection list of all `invoice_approved` packages. **Select all** checkbox with indeterminate state, live count + total-weight summary, submit button disabled until ≥1 selected. |
| 5 | **Shipment Tracking** | `/client/shipments` | 4-step timeline (`Ship Requested → Shipped → Ready for Pickup → Delivered`) per shipment, showing every relevant package's progress. |
| 6 | **My Account** | `/account` | Shared profile page — client can update name / email and change password. |

### Client-only actions
- **Self-register** → instant US suite number assignment (`S2A-NNNN`).
- **Upload invoice** → AJAX upload with `XMLHttpRequest`, real-time progress, JSON error responses; moves package to `pending_invoice_review`.
- **Create ship request** → submits one or more `invoice_approved` packages; each package transitions to `ship_requested`.

---

## Public-Facing Site

| Route | View |
|---|---|
| `/` | **Landing page** — full-bleed animated hero (paper plane on a curved path, drifting clouds, US → Aruba route with sliding plane, floating cargo boxes, count-up trust strip), features grid, "How It Works" timeline, comparison table, pricing tiles, testimonial slider, FAQ accordion, CTA strip. |
| `/login` | Client login (rejects admin accounts). |
| `/register` | Client self-registration. |
| `/admin/login` | Dedicated admin login (rejects non-admin accounts). |

---

## Database Schema

| Table | Purpose | Notable Columns |
|---|---|---|
| `users` | All accounts (admin + client) | `role` enum (`admin`/`client`), `suite_number` (unique, nullable — clients only) |
| `packages` | Every package logged at the warehouse | `tracking_number`, `client_id`, dimensions (W × H × L cm), `weight` (kg), `contents_description`, `status` enum, `received_at` |
| `invoices` | One per package (1:1) | `file_path`, `review_status` enum (`pending`/`approved`/`needs_review`), `admin_note`, `reviewed_by`, `reviewed_at` |
| `ship_requests` | Client-submitted shipment requests | `client_id`, `status` (`submitted`/`processed`), `submitted_at`, `processed_at`, `processed_by` |
| `package_ship_request` | Pivot (many-to-many: a request can contain multiple packages) | `package_id`, `ship_request_id` |
| `package_status_histories` | Append-only audit log for every status transition | `old_status`, `new_status`, `changed_by`, `changed_at` |
| `sessions`, `cache`, `jobs` | Laravel infrastructure | (auto-created by `php artisan migrate`) |

---

## Package Lifecycle

The `status` enum on `packages` flows through these states:

```
ready_to_send
      │  (client uploads invoice)
      ▼
pending_invoice_review
      │  (admin reviews)
      ├── approve ──► invoice_approved ──► ship_requested ──► shipped ──┬──► ready_for_pickup ──► delivered
      │                                  (client submits)   (admin)     │
      │                                                                 └──► delivered
      └── flag ─────► needs_review ──► (client re-uploads) ──► pending_invoice_review …
```

Every transition is checked by `Package::transitionTo()` and recorded in `package_status_histories`. The Blade view `partials/status-history.blade.php` renders this history as a timeline.

---

## Project Structure

```
ship2aruba/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminController.php       ← Admin dashboard, packages, invoices, ship requests, clients
│   │   │   ├── ClientController.php      ← Client dashboard, packages, uploads, ship requests, shipments
│   │   │   ├── AuthController.php        ← login, register, admin login, logout
│   │   │   └── ProfileController.php     ← My Account (edit, update, change password)
│   │   └── Middleware/
│   │       └── EnsureRole.php            ← role:admin / role:client gate
│   └── Models/
│       ├── User.php                      ← admin + client
│       ├── Package.php                   ← states + transitionTo()
│       ├── Invoice.php
│       ├── ShipRequest.php
│       └── PackageStatusHistory.php
├── database/
│   ├── migrations/                       ← schema definitions
│   └── seeders/
│       └── AdminClientSeeder.php         ← realistic US data
├── resources/
│   ├── css/app.css                       ← entire design system (~2.9k lines)
│   ├── js/app.js                         ← combobox, AJAX upload, Chart.js init,
│   │                                       ship-form select-all, hero KPI counter
│   └── views/
│       ├── layouts/                      ← app (public), admin, client
│       ├── auth/                         ← login, admin-login, register
│       ├── admin/                        ← dashboard, packages, invoice-queue,
│       │                                   ship-requests, clients, client-show,
│       │                                   package-show
│       ├── client/                       ← dashboard, packages, package-show,
│       │                                   shipments
│       ├── profile/edit.blade.php
│       ├── partials/                     ← logo, status-history, pagination,
│       │                                   pagination-bar
│       └── landing.blade.php             ← animated marketing page
├── routes/web.php
├── public/
│   ├── favicon.svg                       ← custom brand favicon
│   └── robots.txt                        ← disallows /admin, /client, /account
├── composer.json
├── package.json
└── vite.config.js
```

---

## Getting Started

### Prerequisites
- PHP **8.3+**
- Composer **2+**
- Node.js **20+** & npm
- MySQL **8** (or MariaDB **10.6+**)
- A web server (Apache via WAMP / XAMPP, or `php artisan serve`)

### Setup

```bash
git clone <your-repo-url>
cd ship2aruba

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Edit `.env` and set the database credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ship2aruba
DB_USERNAME=root
DB_PASSWORD=
```

Create the database, then run the migrations + seeder:

```bash
php artisan migrate:fresh --seed
php artisan storage:link
npm run build
php artisan serve
```

Open <http://127.0.0.1:8000>.

---

## Default Credentials

After running the seeder you can log in immediately:

| Role | URL | Email | Password |
|---|---|---|---|
| **Admin** | `/admin/login` | `admin@ship2aruba.com` | `password` |
| **Client (demo)** | `/login` | `michael.thompson@example.com` | `password` |

There are 8 additional seeded client accounts, all with realistic US names and the password `password`. The seeder also generates 18 sample packages spread across every status, with full invoices, ship requests, and status history.

---

## Common Commands

```bash
# rebuild front-end assets after CSS / JS changes
npm run build

# watch mode during development
npm run dev

# reset database and reseed everything
php artisan migrate:fresh --seed

# tail Laravel logs in real time
php artisan pail

# run tests
php artisan test
```

---

## Security Notes

- The two login pages enforce strict role separation — an admin cannot sign in through `/login`, and a client cannot sign in through `/admin/login`.
- The public footer and landing page do **not** link to the admin portal — security through reduced discoverability.
- `public/robots.txt` `Disallow`s `/admin/`, `/client/`, and `/account` so the portals never appear in search engines.
- Every authenticated route is wrapped in either `auth + role:admin` or `auth + role:client` middleware.
- File uploads are validated server-side (`mimes:pdf,jpg,jpeg,png`, `max:2048` KB) and client-side, with separate error paths for JSON (AJAX) and form (legacy) submissions.
- Passwords go through Bcrypt (`BCRYPT_ROUNDS=12`) and changes require the current password (`current_password` rule).
- All package status transitions are guarded by `Package::transitionTo()` — invalid transitions throw, preventing tampered requests from corrupting state.

---

## License

This project is licensed under the [MIT License](https://opensource.org/licenses/MIT).
