-- ============================================================================
-- CHỈ DÙNG KHI PHÁT TRIỂN. KHÔNG áp dụng lên database đang chạy thật / công khai.
-- Các tài khoản dưới đây dùng mật khẩu "123": ai biết email là đăng nhập được, kể cả quản trị.
--
-- Áp dụng (sau schema.sql; cần đã có sản phẩm trong bảng products, đơn mẫu lấy 3 sản phẩm đầu):
--   php backend/database/apply.php backend/database/seed-dev.sql
-- Chạy lại nhiều lần được: tài khoản được đặt lại mật khẩu/vai trò, đơn mẫu được tạo lại.
--
-- Tài khoản (mật khẩu đều là 123):
--   abc@test.com    khách hàng  (có 1 địa chỉ mặc định và 2 đơn mẫu để thử trang tài khoản)
--   staff@test.com  nhân viên   (vào được trang quản trị, không xóa được sản phẩm)
--   admin@test.com  quản trị    (toàn quyền)
--
-- Gỡ bỏ khi đưa lên thật:
--   DELETE FROM users WHERE email IN ('abc@test.com', 'staff@test.com', 'admin@test.com');
-- ============================================================================

SET NAMES utf8mb4;

INSERT INTO users (role_id, full_name, email, password_hash, phone, account_status) VALUES
  ((SELECT role_id FROM roles WHERE role_code = 'customer'), 'abc', 'abc@test.com', '$2y$10$94a6OOLDVzlVBBZaK4ZXjOcJB69bhjZeEtMek93gYZX0ArDeOLEqC', '0900000001', 'active'),
  ((SELECT role_id FROM roles WHERE role_code = 'staff'), 'staff', 'staff@test.com', '$2y$10$94a6OOLDVzlVBBZaK4ZXjOcJB69bhjZeEtMek93gYZX0ArDeOLEqC', NULL, 'active'),
  ((SELECT role_id FROM roles WHERE role_code = 'admin'), 'admin', 'admin@test.com', '$2y$10$94a6OOLDVzlVBBZaK4ZXjOcJB69bhjZeEtMek93gYZX0ArDeOLEqC', NULL, 'active')
ON DUPLICATE KEY UPDATE
  role_id = VALUES(role_id),
  full_name = VALUES(full_name),
  password_hash = VALUES(password_hash),
  phone = VALUES(phone),
  account_status = 'active';

DELETE FROM addresses WHERE user_id = (SELECT user_id FROM users WHERE email = 'abc@test.com');

INSERT INTO addresses (user_id, recipient_name, recipient_phone, line_1, ward, district, city, is_default)
SELECT user_id, 'abc', '0900000001', '12 Nguyễn Huệ', 'Bến Nghé', 'Quận 1', 'TP. Hồ Chí Minh', 1
FROM users WHERE email = 'abc@test.com';

DELETE FROM orders WHERE order_number IN ('TNC-DEMO-1001', 'TNC-DEMO-1002');

INSERT INTO orders (order_number, user_id, order_status_id, shipping_amount, discount_amount, placed_at)
SELECT 'TNC-DEMO-1001', u.user_id, s.order_status_id, 30000, 0, '2026-09-10 10:00:00'
FROM users u, order_statuses s WHERE u.email = 'abc@test.com' AND s.status_code = 'completed';

INSERT INTO orders (order_number, user_id, order_status_id, shipping_amount, discount_amount, placed_at)
SELECT 'TNC-DEMO-1002', u.user_id, s.order_status_id, 0, 100000, '2026-10-01 09:30:00'
FROM users u, order_statuses s WHERE u.email = 'abc@test.com' AND s.status_code = 'shipping';

-- Đơn mẫu lấy 3 sản phẩm đầu tiên trong bảng products (cần đã có sản phẩm); đơn giá chụp theo giá hiện tại.
INSERT INTO order_items (order_id, line_number, product_id, quantity, unit_price)
SELECT o.order_id, 1, p.product_id, 2, p.unit_price
FROM orders o JOIN (SELECT product_id, unit_price FROM products ORDER BY product_id LIMIT 1) p
WHERE o.order_number = 'TNC-DEMO-1001';

INSERT INTO order_items (order_id, line_number, product_id, quantity, unit_price)
SELECT o.order_id, 2, p.product_id, 1, p.unit_price
FROM orders o JOIN (SELECT product_id, unit_price FROM products ORDER BY product_id LIMIT 1 OFFSET 1) p
WHERE o.order_number = 'TNC-DEMO-1001';

INSERT INTO order_items (order_id, line_number, product_id, quantity, unit_price)
SELECT o.order_id, 1, p.product_id, 1, p.unit_price
FROM orders o JOIN (SELECT product_id, unit_price FROM products ORDER BY product_id LIMIT 1 OFFSET 2) p
WHERE o.order_number = 'TNC-DEMO-1002';

INSERT INTO order_addresses (order_id, recipient_name, recipient_phone, line_1, ward, district, city)
SELECT o.order_id, 'abc', '0900000001', '12 Nguyễn Huệ', 'Bến Nghé', 'Quận 1', 'TP. Hồ Chí Minh'
FROM orders o WHERE o.order_number = 'TNC-DEMO-1001';
