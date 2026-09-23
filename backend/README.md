# PHP + MySQL backend skeleton

The storefront now has PHP entry points (`index.php`, `products.php`, `news.php`, and the other page counterparts). Every page pulls the shared markup from `partial/header.php` and `partial/footer.php`, so the visual site stays consistent while the data layer is migrated.

## Local setup

1. Install PHP 8.1+ with the `pdo_mysql` extension and MySQL.
2. Copy `backend/config/.env.example` to `backend/config/.env`.
3. Fill in the `DB_*` values after creating the database and schema.
4. Serve the project root with Apache/XAMPP, or run `php -S localhost:8000` from the project root.
5. Open `http://localhost:8000/` (Apache loads `index.php` first via `.htaccess`).

No SQL is included or executed by this repository. Until the database is configured, the browser preserves the CSV-based product fallback and API calls respond with a safe `503` JSON message.

## Schema contract for your SQL

The repositories deliberately document only the columns they use:

- `products` belongs to a `brand` and a `category`; images and specifications use child tables.
- `news_posts` belongs to a `news_category` and can reference a `users` author.
- `users` belongs to a `role`; shipping addresses are stored in `addresses`.

The complete MySQL 8 schema, including columns and keys, is [database/schema.sql](database/schema.sql).

## Normalization and integrity review

The schema is designed to be in BCNF for its stored facts: brands, categories, roles, statuses, product images, inventory, cart lines, and order lines each have a key that determines their non-key attributes. Product specifications and inventory use composite keys because their values are facts about a specific product/specification or warehouse/product pair.

Derived checkout totals are intentionally not stored in `orders`; calculate them from `order_items`, `shipping_amount`, and `discount_amount`. This avoids the non-key dependency that a stored total would introduce. The schema also enforces positive quantities, non-negative prices/stock, one default address per user, unique public identifiers/slugs, valid cart ownership, and foreign-key referential integrity.

Two multi-row business rules should be enforced inside the transaction that creates or updates an order: inventory availability and reconciliation of the calculated order total. Category-tree cycle detection is likewise an application/service rule beyond the direct self-parent check in MySQL.

`status` values expected by the provided queries are `active` for products and `published` for news posts. User passwords are stored only through `password_hash()`; never insert plaintext passwords.

## Available endpoints

- `GET backend/api/products.php?featured=1&limit=4`
- `GET backend/api/products.php?q=...&category=...&limit=24`
- `GET backend/api/news.php?limit=8`
- `GET backend/api/news.php?slug=...`
- `POST backend/auth/register.php` with `name`, `email`, `password`
- `POST backend/auth/login.php` with `email`, `password`

Both auth endpoints accept regular form data or JSON and return JSON. They create and use the `tnc_store_session` PHP session. Cart, order, contact, and admin database operations are intentionally left for the schema/business rules you add next.

## Project layout

```text
backend/
  bootstrap.php                 # environment, session, shared dependencies
  config/database.php           # PDO connection factory
  config/.env.example           # never commit the real .env
  api/                          # read endpoints
  auth/                         # registration and login actions
  src/Repositories/             # prepared-statement data access
```
