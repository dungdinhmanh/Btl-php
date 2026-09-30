-- Adds the view counter used by the news article page.
-- Run once on an existing database:
--   php backend/database/apply.php backend/database/migrations/2026-09-add-news-view-count.sql
-- (New installs already get the column from schema.sql.)
ALTER TABLE news_posts
  ADD COLUMN view_count INT UNSIGNED NOT NULL DEFAULT 0 AFTER published_at;
