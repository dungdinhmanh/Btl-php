# TNC Store — PHP Learning Project

A PHP/MySQL e-commerce website developed as a learning and coursework project. The original TNC Store website was used as a visual and functional reference while building this project.

**This is an independent, unofficial project. It is not affiliated with, endorsed by, or sponsored by TNC Store.** The name “TNC Store” is used here only to identify the website used as a reference.

## Project overview

The application demonstrates a storefront for PC components, gaming hardware, accessories, and related technology products. It includes product browsing, filtering, a cart and checkout flow, customer authentication, news pages, and an admin dashboard.

The project uses a lightweight architecture:

- PHP pages in the project root render HTML pages and layouts.
- Shared partials under `partial/` provide reusable sections such as the header and footer.
- JavaScript files in `js/` fetch data from backend APIs and populate the UI.
- The backend under `backend/` handles database access, repositories, authentication endpoints, and API responses.

## Features

- Product catalog with category and brand filtering
- Product search and sorting
- Product detail pages with specifications and inventory information
- Shopping cart and checkout flow
- User registration and login
- News listing and detail pages
- Admin dashboard with sales and inventory summaries
- Responsive storefront layout

## Technology stack

- PHP 8+
- MySQL / MariaDB
- PDO
- Vanilla JavaScript and jQuery
- HTML, CSS, and Bootstrap

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

- `backend/bootstrap.php` initializes shared dependencies.
- `backend/config/database.php` creates the PDO connection using environment variables.
- `backend/src/Repositories/` contains repository classes for application data.
- `backend/api/` exposes JSON endpoints used by the frontend.
- `backend/auth/` contains authentication-related logic.
- `backend/database/` contains database schema and seed scripts.

## Local setup

1. Copy the environment example file:

   ```bash
   cp backend/config/.env.example backend/config/.env
   ```

2. Set the database credentials in `backend/config/.env` for your local MySQL/MariaDB instance.

   Example:

   ```env
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=tnc-db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

3. Create the database using the SQL scripts in `backend/database/`.

## Run locally

From the project root:

```bash
php -S localhost:8000
```

Open `http://localhost:8000` in your browser. This built-in server is for local development only; do not use it as a production server.

## Database scripts

- `backend/database/schema.sql`
- `backend/database/seed.sql`
- `backend/database/apply.php`

Use `schema.sql` to create the database structure and `seed.sql` to load sample data.

## Project notes

- A configured `.env` file and a running MySQL/MariaDB server are required.
- Most storefront data is loaded through JavaScript calls to backend API endpoints.
- The frontend uses vanilla JavaScript for most storefront behavior and jQuery for selected account-related flows.
- This repository is intended for learning and demonstration. It should not be treated as a production-ready commerce platform without further security and functional review.

## Attribution and third-party materials

TNC Store is the reference website for this educational project. Any third-party names, logos, product photographs, banners, text, fonts, or other assets that may appear in the repository remain the property of their respective owners and are not relicensed by this project. Their presence does not imply permission for reuse. The repository maintainer does not claim ownership of materials they did not create or have permission to license.

Before redistributing or publicly deploying the project, replace reference-site branding and any assets or content for which you do not have permission with original or properly licensed alternatives.

## Contributor

<a href="https://github.com/dungdinhmanh/Btl-php/graphs/contributors">
  <img src="https://contrib.rocks/image?repo=dungdinhmanh/Btl-php" alt="Repository contributors" />
</a>

## License

No blanket open-source license is currently granted for the repository as a whole. Unless a separate license or written permission says otherwise, no permission is granted to copy, redistribute, or reuse the repository's contents. Third-party materials remain subject to their respective owners' rights.

This notice is not a substitute for reviewing and replacing materials that you do not have the right to publish.
