-- TNC Store database schema
-- Target: MySQL 8.0.16+ / InnoDB / utf8mb4
-- This file creates structure and small lookup data only; it contains no sample customers or orders.

CREATE DATABASE IF NOT EXISTS tnc_store
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE tnc_store;

CREATE TABLE roles (
  role_id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  role_code VARCHAR(30) NOT NULL,
  role_name VARCHAR(80) NOT NULL,
  CONSTRAINT uq_roles_code UNIQUE (role_code)
) ENGINE=InnoDB;

CREATE TABLE users (
  user_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  role_id TINYINT UNSIGNED NOT NULL,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(254) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  phone VARCHAR(25) NULL,
  account_status ENUM('active', 'disabled') NOT NULL DEFAULT 'active',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT uq_users_email UNIQUE (email),
  CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles (role_id),
  CONSTRAINT chk_users_name CHECK (CHAR_LENGTH(TRIM(full_name)) >= 1)
) ENGINE=InnoDB;

CREATE TABLE addresses (
  address_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL,
  recipient_name VARCHAR(150) NOT NULL,
  recipient_phone VARCHAR(25) NOT NULL,
  line_1 VARCHAR(255) NOT NULL,
  line_2 VARCHAR(255) NULL,
  ward VARCHAR(120) NULL,
  district VARCHAR(120) NULL,
  city VARCHAR(120) NOT NULL,
  is_default BOOLEAN NOT NULL DEFAULT FALSE,
  default_address_user_id BIGINT UNSIGNED GENERATED ALWAYS AS (
    CASE WHEN is_default THEN user_id ELSE NULL END
  ) STORED,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT uq_addresses_one_default UNIQUE (default_address_user_id),
  CONSTRAINT fk_addresses_user FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE CASCADE,
  CONSTRAINT chk_addresses_recipient CHECK (CHAR_LENGTH(TRIM(recipient_name)) >= 1)
) ENGINE=InnoDB;
CREATE INDEX ix_addresses_user ON addresses (user_id);

CREATE TABLE brands (
  brand_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  brand_name VARCHAR(120) NOT NULL,
  brand_slug VARCHAR(140) NOT NULL,
  CONSTRAINT uq_brands_name UNIQUE (brand_name),
  CONSTRAINT uq_brands_slug UNIQUE (brand_slug)
) ENGINE=InnoDB;

CREATE TABLE categories (
  category_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  parent_category_id INT UNSIGNED NULL,
  category_name VARCHAR(120) NOT NULL,
  category_slug VARCHAR(140) NOT NULL,
  CONSTRAINT uq_categories_slug UNIQUE (category_slug),
  CONSTRAINT fk_categories_parent FOREIGN KEY (parent_category_id)
    REFERENCES categories (category_id) ON DELETE SET NULL,
  CONSTRAINT chk_categories_not_own_parent CHECK (parent_category_id IS NULL OR parent_category_id <> category_id)
) ENGINE=InnoDB;
CREATE INDEX ix_categories_parent ON categories (parent_category_id);

CREATE TABLE products (
  product_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  brand_id INT UNSIGNED NULL,
  category_id INT UNSIGNED NOT NULL,
  sku VARCHAR(80) NOT NULL,
  product_slug VARCHAR(180) NOT NULL,
  product_name VARCHAR(255) NOT NULL,
  socket VARCHAR(80) NULL,
  unit_price DECIMAL(15,2) NOT NULL,
  product_status ENUM('draft', 'active', 'archived') NOT NULL DEFAULT 'draft',
  is_featured BOOLEAN NOT NULL DEFAULT FALSE,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT uq_products_sku UNIQUE (sku),
  CONSTRAINT uq_products_slug UNIQUE (product_slug),
  CONSTRAINT fk_products_brand FOREIGN KEY (brand_id) REFERENCES brands (brand_id) ON DELETE SET NULL,
  CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories (category_id),
  CONSTRAINT chk_products_name CHECK (CHAR_LENGTH(TRIM(product_name)) >= 1),
  CONSTRAINT chk_products_price CHECK (unit_price >= 0)
) ENGINE=InnoDB;
CREATE INDEX ix_products_catalog ON products (product_status, is_featured, created_at);
CREATE INDEX ix_products_category ON products (category_id);
CREATE INDEX ix_products_brand ON products (brand_id);

CREATE TABLE product_images (
  product_image_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  product_id BIGINT UNSIGNED NOT NULL,
  image_path VARCHAR(500) NOT NULL,
  alt_text VARCHAR(255) NULL,
  display_order SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  CONSTRAINT uq_product_images_order UNIQUE (product_id, display_order),
  CONSTRAINT fk_product_images_product FOREIGN KEY (product_id)
    REFERENCES products (product_id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE specification_definitions (
  specification_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  specification_code VARCHAR(80) NOT NULL,
  specification_name VARCHAR(120) NOT NULL,
  display_unit VARCHAR(30) NULL,
  CONSTRAINT uq_specifications_code UNIQUE (specification_code)
) ENGINE=InnoDB;

CREATE TABLE product_specifications (
  product_id BIGINT UNSIGNED NOT NULL,
  specification_id INT UNSIGNED NOT NULL,
  specification_value VARCHAR(255) NOT NULL,
  PRIMARY KEY (product_id, specification_id),
  CONSTRAINT fk_product_specs_product FOREIGN KEY (product_id)
    REFERENCES products (product_id) ON DELETE CASCADE,
  CONSTRAINT fk_product_specs_definition FOREIGN KEY (specification_id)
    REFERENCES specification_definitions (specification_id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE warehouses (
  warehouse_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  warehouse_code VARCHAR(30) NOT NULL,
  warehouse_name VARCHAR(120) NOT NULL,
  CONSTRAINT uq_warehouses_code UNIQUE (warehouse_code)
) ENGINE=InnoDB;

CREATE TABLE inventory (
  warehouse_id INT UNSIGNED NOT NULL,
  product_id BIGINT UNSIGNED NOT NULL,
  quantity_on_hand INT NOT NULL DEFAULT 0,
  quantity_reserved INT NOT NULL DEFAULT 0,
  PRIMARY KEY (warehouse_id, product_id),
  CONSTRAINT fk_inventory_warehouse FOREIGN KEY (warehouse_id)
    REFERENCES warehouses (warehouse_id) ON DELETE CASCADE,
  CONSTRAINT fk_inventory_product FOREIGN KEY (product_id)
    REFERENCES products (product_id) ON DELETE CASCADE,
  CONSTRAINT chk_inventory_nonnegative CHECK (quantity_on_hand >= 0 AND quantity_reserved >= 0),
  CONSTRAINT chk_inventory_reservation CHECK (quantity_reserved <= quantity_on_hand)
) ENGINE=InnoDB;

CREATE TABLE carts (
  cart_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NULL,
  guest_token CHAR(36) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT uq_carts_user UNIQUE (user_id),
  CONSTRAINT uq_carts_guest_token UNIQUE (guest_token),
  CONSTRAINT fk_carts_user FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE CASCADE,
  CONSTRAINT chk_carts_owner CHECK ((user_id IS NULL) <> (guest_token IS NULL))
) ENGINE=InnoDB;

CREATE TABLE cart_items (
  cart_id BIGINT UNSIGNED NOT NULL,
  product_id BIGINT UNSIGNED NOT NULL,
  quantity INT UNSIGNED NOT NULL,
  PRIMARY KEY (cart_id, product_id),
  CONSTRAINT fk_cart_items_cart FOREIGN KEY (cart_id) REFERENCES carts (cart_id) ON DELETE CASCADE,
  CONSTRAINT fk_cart_items_product FOREIGN KEY (product_id) REFERENCES products (product_id) ON DELETE RESTRICT,
  CONSTRAINT chk_cart_items_quantity CHECK (quantity > 0)
) ENGINE=InnoDB;

CREATE TABLE order_statuses (
  order_status_id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  status_code VARCHAR(30) NOT NULL,
  status_name VARCHAR(80) NOT NULL,
  CONSTRAINT uq_order_statuses_code UNIQUE (status_code)
) ENGINE=InnoDB;

CREATE TABLE orders (
  order_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_number VARCHAR(32) NOT NULL,
  user_id BIGINT UNSIGNED NULL,
  order_status_id TINYINT UNSIGNED NOT NULL,
  shipping_amount DECIMAL(15,2) NOT NULL DEFAULT 0,
  discount_amount DECIMAL(15,2) NOT NULL DEFAULT 0,
  placed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT uq_orders_number UNIQUE (order_number),
  CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE SET NULL,
  CONSTRAINT fk_orders_status FOREIGN KEY (order_status_id) REFERENCES order_statuses (order_status_id),
  CONSTRAINT chk_orders_amounts CHECK (shipping_amount >= 0 AND discount_amount >= 0)
) ENGINE=InnoDB;
CREATE INDEX ix_orders_user_placed_at ON orders (user_id, placed_at);
CREATE INDEX ix_orders_status_placed_at ON orders (order_status_id, placed_at);

CREATE TABLE order_items (
  order_id BIGINT UNSIGNED NOT NULL,
  line_number SMALLINT UNSIGNED NOT NULL,
  product_id BIGINT UNSIGNED NOT NULL,
  quantity INT UNSIGNED NOT NULL,
  unit_price DECIMAL(15,2) NOT NULL,
  PRIMARY KEY (order_id, line_number),
  CONSTRAINT uq_order_items_product UNIQUE (order_id, product_id),
  CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders (order_id) ON DELETE CASCADE,
  CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products (product_id) ON DELETE RESTRICT,
  CONSTRAINT chk_order_items_quantity CHECK (quantity > 0),
  CONSTRAINT chk_order_items_price CHECK (unit_price >= 0)
) ENGINE=InnoDB;

CREATE TABLE order_addresses (
  order_id BIGINT UNSIGNED PRIMARY KEY,
  recipient_name VARCHAR(150) NOT NULL,
  recipient_phone VARCHAR(25) NOT NULL,
  line_1 VARCHAR(255) NOT NULL,
  line_2 VARCHAR(255) NULL,
  ward VARCHAR(120) NULL,
  district VARCHAR(120) NULL,
  city VARCHAR(120) NOT NULL,
  CONSTRAINT fk_order_addresses_order FOREIGN KEY (order_id) REFERENCES orders (order_id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE news_categories (
  news_category_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_name VARCHAR(120) NOT NULL,
  category_slug VARCHAR(140) NOT NULL,
  CONSTRAINT uq_news_categories_name UNIQUE (category_name),
  CONSTRAINT uq_news_categories_slug UNIQUE (category_slug)
) ENGINE=InnoDB;

CREATE TABLE news_posts (
  news_post_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  news_category_id INT UNSIGNED NOT NULL,
  author_user_id BIGINT UNSIGNED NULL,
  post_slug VARCHAR(180) NOT NULL,
  title VARCHAR(255) NOT NULL,
  excerpt TEXT NULL,
  content LONGTEXT NOT NULL,
  cover_image_path VARCHAR(500) NULL,
  post_status ENUM('draft', 'published', 'archived') NOT NULL DEFAULT 'draft',
  published_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT uq_news_posts_slug UNIQUE (post_slug),
  CONSTRAINT fk_news_posts_category FOREIGN KEY (news_category_id) REFERENCES news_categories (news_category_id),
  CONSTRAINT fk_news_posts_author FOREIGN KEY (author_user_id) REFERENCES users (user_id) ON DELETE SET NULL,
  CONSTRAINT chk_news_posts_title CHECK (CHAR_LENGTH(TRIM(title)) >= 1),
  CONSTRAINT chk_news_posts_publication CHECK (
    (post_status = 'published' AND published_at IS NOT NULL) OR post_status <> 'published'
  )
) ENGINE=InnoDB;
CREATE INDEX ix_news_posts_listing ON news_posts (post_status, published_at);

INSERT INTO roles (role_code, role_name) VALUES
  ('customer', 'Customer'),
  ('staff', 'Staff'),
  ('admin', 'Administrator');

INSERT INTO order_statuses (status_code, status_name) VALUES
  ('pending', 'Pending'),
  ('confirmed', 'Confirmed'),
  ('shipping', 'Shipping'),
  ('completed', 'Completed'),
  ('cancelled', 'Cancelled');
