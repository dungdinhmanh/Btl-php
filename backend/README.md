# Backend skeleton

The current project is intentionally static. PHP and MySQL can be added behind the existing forms and links later.

## Suggested structure

```text
backend/
  config/database.php
  auth/login.php
  auth/register.php
  products/list.php
  cart/add.php
  orders/create.php
  contact/send.php
admin/
```

## Frontend handoff points

- Login/register: standalone pages `login.html` and `register.html` (linked from the header "Đăng nhập" button in `index.html` and `products.html`).
- Search form: `GET products.html?q=...`.
- Checkout form: `checkout.html`.
- Contact form: `contact.html`.
- Product and cart buttons currently use static demo content.
- `admin.html` is a presentation-only dashboard until protected PHP routes are added.

When the backend is introduced, keep database credentials outside the public web root or in environment variables, use prepared statements, validate all form input server-side, hash passwords with `password_hash()`, and protect admin routes with a server-side session.
