ALTER TABLE users
  MODIFY account_status ENUM('active', 'disabled', 'deleted') NOT NULL DEFAULT 'active';