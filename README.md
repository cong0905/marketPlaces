# Amber Marketplace

A modern second-hand marketplace built with Laravel where users can list products, discover items, chat with sellers, place orders, make payments, review transactions, and report violations.

**Live demo:** https://amber-marketplace.onrender.com

## Highlights

- User registration, login, email verification, and profile management
- Product listing CRUD with image upload and moderation
- Favorites / wishlist
- Checkout and order lifecycle management
- VNPay payment integration
- Buyer–seller chat
- Reviews and notifications
- Product and user reporting
- Admin dashboard for users, categories, products, and reports
- Rate limiting on sensitive actions such as checkout, chat, product creation, and reviews
- Cloudinary for media storage
- Dockerized deployment on Render

## Tech Stack

- **Backend:** PHP 8.2, Laravel 12
- **Database:** MySQL-compatible database / TiDB
- **Frontend:** Blade, Tailwind CSS, Alpine.js, Vite
- **Realtime:** Laravel Reverb / Echo
- **Storage:** Cloudinary
- **Payment:** VNPay
- **Testing:** PHPUnit
- **Deployment:** Docker, Render

## Core Features

### Authentication & Profiles
Users can register, sign in, verify their email, update profile information, and manage their account.

### Marketplace
Authenticated users can create, update, and delete product listings. Public users can browse products and view seller profiles.

### Checkout & Orders
The application supports checkout, VNPay payment flow, and an order lifecycle including confirmation, shipping, completion, and cancellation.

### Chat
Buyers and sellers can start conversations around products and exchange messages inside the application.

### Trust & Safety
Users can submit reports for products or accounts. Admins can moderate listings, resolve reports, manage categories, and ban/unban users.

## Project Structure

```text
app/            Application logic, models, controllers
database/       Migrations, factories, seeders
resources/      Blade views, frontend assets
routes/         Web and authentication routes
tests/          Automated tests
Dockerfile      Production container
render.yaml     Render deployment configuration
```

## Local Setup

### Requirements

- PHP 8.2+
- Composer
- Node.js / npm
- SQLite or MySQL-compatible database

### Install

```bash
git clone https://github.com/cong0905/marketPlaces.git
cd marketPlaces

composer install
npm install

cp .env.example .env
php artisan key:generate

php artisan migrate
npm run build
php artisan serve
```

For active development:

```bash
composer run dev
```

## Environment Variables

Copy `.env.example` to `.env` and configure only the services you use.

Important production secrets such as database credentials, Cloudinary credentials, Reverb secrets, and payment credentials should be stored in your hosting provider's secret manager and never committed to Git.

## Testing

```bash
composer test
```

## Author

**Nguyễn Đức Công**

- GitHub: https://github.com/cong0905
