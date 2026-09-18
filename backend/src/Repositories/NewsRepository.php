<?php
declare(strict_types=1);

final class NewsRepository
{
    public function __construct(private readonly PDO $db) {}

    /** Reads the normalized news_posts and news_categories tables. */
    public function latest(int $limit = 8): array
    {
        $statement = $this->db->prepare(
            'SELECT n.news_post_id AS id, n.post_slug AS slug, n.title, n.excerpt,
                    c.category_name AS category, n.cover_image_path AS cover_image, n.published_at
             FROM news_posts n
             INNER JOIN news_categories c ON c.news_category_id = n.news_category_id
             WHERE n.post_status = :status
             ORDER BY n.published_at DESC LIMIT :limit',
        );
        $statement->bindValue(':status', 'published');
        $statement->bindValue(':limit', max(1, min($limit, 24)), PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function findBySlug(string $slug): ?array
    {
        $statement = $this->db->prepare(
            'SELECT n.news_post_id AS id, n.post_slug AS slug, n.title, n.excerpt, n.content,
                    c.category_name AS category, n.cover_image_path AS cover_image, n.published_at
             FROM news_posts n
             INNER JOIN news_categories c ON c.news_category_id = n.news_category_id
             WHERE n.post_slug = :slug AND n.post_status = :status LIMIT 1',
        );
        $statement->execute([':slug' => $slug, ':status' => 'published']);
        return $statement->fetch() ?: null;
    }
}
