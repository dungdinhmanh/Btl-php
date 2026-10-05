# TNC Store

A PHP-based e-commerce and content website for a computer & gaming gear store. The project uses plain PHP with a MySQL database, server-side API endpoints, and frontend JavaScript to render product and dashboard data dynamically.

## Project overview

This application is a storefront for selling PC components, gaming hardware, accessories, and related tech items. It includes a public shopping experience, product browsing/filtering, cart and checkout flow, customer login/register, news pages, and an admin dashboard overview.

The codebase follows a lightweight architecture:

- PHP pages in the project root render HTML pages and layouts.
- Shared partials under `partial/` provide reusable sections such as the header, footer, and stylesheets.
- JavaScript files in `js/` fetch data from backend APIs and populate the UI.
- The backend layer under `backend/` handles database access, repositories, auth endpoints, and API responses.

## Features

- Product catalog with category and brand filtering
- Search and sorting for products
- Product detail pages with specs and inventory information
- Shopping cart and checkout flow
- User registration and login
- News/blog listing and detail pages
- Admin dashboard with sales and inventory summary
- Responsive storefront styling powered by shared CSS and Bootstrap-based layout

## Tech stack

- PHP 8+
- MySQL / MariaDB
- PDO for database access
- Vanilla JavaScript for most UI logic and API-driven rendering
- jQuery for specific interactive flows such as account login/register forms and the profile dashboard
- HTML + CSS + Bootstrap styling assets

## Project structure

```text
Btl-php/
├── 404.php
├── about.php
├── admin.php
├── buildpc.php
├── cart.php
├── checkout.php
├── contact.php
├── forgot-password.php
├── index.php
├── login.php
├── news.php
├── news-post.php
├── product-detail.php
├── products.php
├── profile.php
├── register.php
├── success.php
├── assets/
├── backend/
│   ├── api/
│   ├── auth/
│   ├── bootstrap.php
│   ├── config/
│   ├── database/
│   └── src/
├── css/
├── js/
├── partial/
├── README.MD
└── LICENSE
```
## Backend architecture

The backend is intentionally simple and does not use a framework.

- `backend/bootstrap.php` initializes the environment and shared dependencies.
- `backend/config/database.php` creates the PDO connection from environment variables.
- `backend/src/Repositories/` contains repository classes for products, news, dashboard metrics, and users.
- `backend/api/` exposes JSON endpoints consumed by frontend JavaScript.
- `backend/auth/` contains authentication-related logic.
- `backend/database/` includes schema and seed scripts for the MySQL database.

## Environment setup

1. Copy the environment example file:

```bash
cp backend/config/.env.example backend/config/.env
```

2. Update the database credentials in `backend/config/.env` with your local MySQL settings.

Example:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tnc_store
DB_USERNAME=root
DB_PASSWORD=
```

3. Create the database schema using the SQL scripts in `backend/database/`.

## Running the app

From the project root, serve the app with PHP's built-in server:

```bash
php -S localhost:8000
```

Then open:

```text
http://localhost:8000
```

## Database setup

The project includes SQL scripts for schema creation and initial seed data:

- `backend/database/schema.sql`
- `backend/database/seed.sql`
- `backend/database/apply.php`

Use `schema.sql` to create the database structure, and `seed.sql` if you want demo content and sample records.

## Notes

- The site relies on a MySQL database; a configured `.env` file is required.
- Frontend pages are thin shells; most data is loaded via JavaScript calls to backend API endpoints.
- The frontend uses a mixed stack: vanilla JavaScript for the main storefront logic, and jQuery for specific flows like auth forms and profile/account UI in `js/account.js` and `js/profile.js`.
- Authentication and session handling are managed through the shared PHP bootstrap and server-side request handling.

## Contributor
<a href="https://github.com/dungdinhmanh/Btl-php/graphs/contributors">
  <img src="https://contrib.rocks/image?repo=dungdinhmanh/Btl-php" />
</a>

## License
This project is distributed under the license included in the repository: [LICENSE](LICENSE).
