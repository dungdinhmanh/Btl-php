<?php
declare(strict_types=1);

final class NewsRepository
{
    private const SELECT = 'SELECT n.news_post_id AS id, n.post_slug AS slug, n.title, n.excerpt, n.content,
                    c.category_name AS category, n.cover_image_path AS coverImage, n.published_at AS publishedAt,
                    n.view_count AS views';

    private const FROM = 'FROM news_posts n
             INNER JOIN news_categories c ON c.news_category_id = n.news_category_id';

    public function __construct(private readonly PDO $db) {}

    public function latest(int $limit = 8): array
    {
        $statement = $this->db->prepare(
            self::SELECT . ' ' . self::FROM . '
             WHERE n.post_status = :status
             ORDER BY n.published_at DESC, n.news_post_id DESC
             LIMIT :limit',
        );
        $statement->bindValue(':status', 'published');
        $statement->bindValue(':limit', max(1, min($limit, 24)), PDO::PARAM_INT);
        $statement->execute();

        return $this->presentMany($statement->fetchAll());
    }

    public function all(int $limit = 12, int $offset = 0, ?string $category = null): array
    {
        $where = ['n.post_status = :status'];
        $params = [':status' => 'published'];

        if ($category !== null && $category !== '') {
            $where[] = 'c.category_slug = :category';
            $params[':category'] = $category;
        }

        $statement = $this->db->prepare(
            self::SELECT . ' ' . self::FROM . '
             WHERE ' . implode(' AND ', $where) . '
             ORDER BY n.published_at DESC, n.news_post_id DESC
             LIMIT :limit OFFSET :offset',
        );
        foreach ($params as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->bindValue(':limit', max(1, min($limit, 50)), PDO::PARAM_INT);
        $statement->bindValue(':offset', max(0, $offset), PDO::PARAM_INT);
        $statement->execute();

        return $this->presentMany($statement->fetchAll());
    }

    public function categories(): array
    {
        $statement = $this->db->query(
            "SELECT c.news_category_id AS id, c.category_slug AS slug, c.category_name AS name,
                    COUNT(n.news_post_id) AS postCount
             FROM news_categories c
             LEFT JOIN news_posts n ON n.news_category_id = c.news_category_id AND n.post_status = 'published'
             GROUP BY c.news_category_id, c.category_slug, c.category_name
             ORDER BY c.category_name ASC",
        );

        return array_values(array_filter(
            array_map(static fn (array $row): array => [
                'id' => (int) $row['id'],
                'slug' => (string) $row['slug'],
                'name' => (string) $row['name'],
                'postCount' => (int) $row['postCount'],
            ], $statement->fetchAll()),
            static fn (array $category): bool => $category['postCount'] > 0,
        ));
    }

    public function findBySlug(string $slug): ?array
    {
        $statement = $this->db->prepare(
            self::SELECT . ' ' . self::FROM . '
             WHERE n.post_slug = :slug AND n.post_status = :status LIMIT 1',
        );
        $statement->execute([':slug' => $slug, ':status' => 'published']);
        $row = $statement->fetch();

        return $row === false ? null : $this->presentOne($row);
    }

    /** Adds one view to a post (the page calls this once per visitor session). */
    public function recordView(int $id): void
    {
        $statement = $this->db->prepare(
            'UPDATE news_posts SET view_count = view_count + 1 WHERE news_post_id = :id',
        );
        $statement->execute([':id' => $id]);
    }

    /** Latest other published posts, for the "Tin liên quan" block under an article. */
    public function related(int $excludeId, int $limit = 6): array
    {
        $statement = $this->db->prepare(
            self::SELECT . ' ' . self::FROM . '
             WHERE n.post_status = :status AND n.news_post_id <> :id
             ORDER BY n.published_at DESC, n.news_post_id DESC
             LIMIT :limit',
        );
        $statement->bindValue(':status', 'published');
        $statement->bindValue(':id', $excludeId, PDO::PARAM_INT);
        $statement->bindValue(':limit', max(1, min($limit, 12)), PDO::PARAM_INT);
        $statement->execute();

        return $this->presentMany($statement->fetchAll());
    }

    /** @param array<int, array<string, mixed>> $rows */
    private function presentMany(array $rows): array
    {
        return array_map(function (array $row): array {
            // List payloads stay small; only findBySlug returns the article body.
            unset($row['content']);

            return $this->presentOne($row);
        }, $rows);
    }

    /**
     * @param array<string, mixed> $row
     */
    private function presentOne(array $row): array
    {
        $publishedAt = $row['publishedAt'] !== null ? (string) $row['publishedAt'] : null;
        $timestamp = $publishedAt !== null ? strtotime($publishedAt) : false;

        $post = [
            'id' => (int) $row['id'],
            'slug' => (string) $row['slug'],
            'title' => (string) $row['title'],
            'excerpt' => $row['excerpt'] !== null ? (string) $row['excerpt'] : null,
            'category' => (string) $row['category'],
            'coverImage' => $row['coverImage'] !== null ? (string) $row['coverImage'] : null,
            'publishedAt' => $publishedAt,
            'date' => $timestamp !== false ? date('d.m.Y', $timestamp) : '',
            'dateTime' => $timestamp !== false ? date('d-m-Y, g:i a', $timestamp) : '',
            'views' => (int) $row['views'],
            'publishedAtIso' => $timestamp !== false ? date('Y-m-d', $timestamp) : '',
        ];

        if (array_key_exists('content', $row)) {
            $post['content'] = (string) $row['content'];
        }

        return $post;
    }
}
