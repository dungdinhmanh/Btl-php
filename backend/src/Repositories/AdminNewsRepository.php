<?php

declare(strict_types=1);

/**
 * Quản trị tin tức trong admin: đọc/ghi được cả bản nháp, đã đăng và lưu trữ.
 * Storefront vẫn đọc bằng NewsRepository (chỉ bài published, không trả `content` ở danh sách).
 */
final class AdminNewsRepository
{
    /** Bảng chuyển dấu tiếng Việt sang ASCII để sinh slug (mb_strtolower chạy trước nên chỉ cần chữ thường). */
    private const VIETNAMESE_MAP = [
        'à' => 'a', 'á' => 'a', 'ạ' => 'a', 'ả' => 'a', 'ã' => 'a',
        'â' => 'a', 'ầ' => 'a', 'ấ' => 'a', 'ậ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a',
        'ă' => 'a', 'ằ' => 'a', 'ắ' => 'a', 'ặ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a',
        'è' => 'e', 'é' => 'e', 'ẹ' => 'e', 'ẻ' => 'e', 'ẽ' => 'e',
        'ê' => 'e', 'ề' => 'e', 'ế' => 'e', 'ệ' => 'e', 'ể' => 'e', 'ễ' => 'e',
        'ì' => 'i', 'í' => 'i', 'ị' => 'i', 'ỉ' => 'i', 'ĩ' => 'i',
        'ò' => 'o', 'ó' => 'o', 'ọ' => 'o', 'ỏ' => 'o', 'õ' => 'o',
        'ô' => 'o', 'ồ' => 'o', 'ố' => 'o', 'ộ' => 'o', 'ổ' => 'o', 'ỗ' => 'o',
        'ơ' => 'o', 'ờ' => 'o', 'ớ' => 'o', 'ợ' => 'o', 'ở' => 'o', 'ỡ' => 'o',
        'ù' => 'u', 'ú' => 'u', 'ụ' => 'u', 'ủ' => 'u', 'ũ' => 'u',
        'ư' => 'u', 'ừ' => 'u', 'ứ' => 'u', 'ự' => 'u', 'ử' => 'u', 'ữ' => 'u',
        'ỳ' => 'y', 'ý' => 'y', 'ỵ' => 'y', 'ỷ' => 'y', 'ỹ' => 'y',
        'đ' => 'd',
    ];

    public function __construct(private readonly PDO $db) {}

    public function list(): array
    {
        $statement = $this->db->query(
            'SELECT n.news_post_id AS id, n.post_slug AS slug, n.title, n.excerpt,
                    n.cover_image_path AS coverImage, n.view_count AS views,
                    n.post_status AS status, n.published_at AS publishedAt,
                    n.created_at AS createdAt,
                    c.news_category_id AS categoryId, c.category_name AS category,
                    u.full_name AS author
             FROM news_posts n
             INNER JOIN news_categories c ON c.news_category_id = n.news_category_id
             LEFT JOIN users u ON u.user_id = n.author_user_id
             ORDER BY n.created_at DESC, n.news_post_id DESC',
        );

        return array_map(static function (array $row): array {
            $timestamp = $row['publishedAt'] !== null ? strtotime((string) $row['publishedAt']) : false;

            return [
                'id' => (int) $row['id'],
                'slug' => (string) $row['slug'],
                'title' => (string) $row['title'],
                'excerpt' => $row['excerpt'] !== null ? (string) $row['excerpt'] : null,
                'categoryId' => (int) $row['categoryId'],
                'category' => (string) $row['category'],
                'coverImage' => $row['coverImage'] !== null ? (string) $row['coverImage'] : null,
                'views' => (int) $row['views'],
                'status' => (string) $row['status'],
                'author' => $row['author'] !== null ? (string) $row['author'] : 'Không rõ',
                'publishedAt' => $row['publishedAt'] !== null ? (string) $row['publishedAt'] : null,
                'dateLabel' => $timestamp !== false ? date('d/m/Y H:i', $timestamp) : '—',
                'createdAt' => (string) $row['createdAt'],
            ];
        }, $statement->fetchAll());
    }

    /** Trả về đầy đủ (kể cả content) để đổ vào form sửa bài. Null nếu không tồn tại. */
    public function find(int $id): ?array
    {
        $statement = $this->db->prepare(
            'SELECT n.news_post_id AS id, n.post_slug AS slug, n.title, n.excerpt, n.content,
                    n.cover_image_path AS coverImage, n.view_count AS views,
                    n.post_status AS status, n.published_at AS publishedAt,
                    c.news_category_id AS categoryId, c.category_name AS category,
                    u.full_name AS author
             FROM news_posts n
             INNER JOIN news_categories c ON c.news_category_id = n.news_category_id
             LEFT JOIN users u ON u.user_id = n.author_user_id
             WHERE n.news_post_id = :id
             LIMIT 1',
        );
        $statement->execute([':id' => $id]);
        $row = $statement->fetch();
        if ($row === false) {
            return null;
        }

        $timestamp = $row['publishedAt'] !== null ? strtotime((string) $row['publishedAt']) : false;

        return [
            'id' => (int) $row['id'],
            'slug' => (string) $row['slug'],
            'title' => (string) $row['title'],
            'excerpt' => $row['excerpt'] !== null ? (string) $row['excerpt'] : null,
            'content' => (string) $row['content'],
            'categoryId' => (int) $row['categoryId'],
            'category' => (string) $row['category'],
            'coverImage' => $row['coverImage'] !== null ? (string) $row['coverImage'] : null,
            'views' => (int) $row['views'],
            'status' => (string) $row['status'],
            'author' => $row['author'] !== null ? (string) $row['author'] : 'Không rõ',
            'publishedAt' => $row['publishedAt'] !== null ? (string) $row['publishedAt'] : null,
            'publishedInput' => $timestamp !== false ? date('Y-m-d\TH:i', $timestamp) : '',
            'dateLabel' => $timestamp !== false ? date('d/m/Y H:i', $timestamp) : '—',
        ];
    }

    public function categories(): array
    {
        $statement = $this->db->query(
            'SELECT news_category_id AS id, category_name AS name, category_slug AS slug
             FROM news_categories
             ORDER BY category_name ASC',
        );

        return array_map(static fn (array $row): array => [
            'id' => (int) $row['id'],
            'name' => (string) $row['name'],
            'slug' => (string) $row['slug'],
        ], $statement->fetchAll());
    }

    public function create(array $payload, ?int $authorId = null): int
    {
        $prepared = $this->normalize($payload);

        $statement = $this->db->prepare(
            'INSERT INTO news_posts (
                news_category_id, author_user_id, post_slug, title, excerpt, content,
                cover_image_path, post_status, published_at, created_at, updated_at
            ) VALUES (
                :category_id, :author_id, :slug, :title, :excerpt, :content,
                :cover, :status, :published_at, NOW(), NOW()
            )',
        );
        $statement->execute([
            ':category_id' => $prepared['category_id'],
            ':author_id' => $authorId,
            ':slug' => $prepared['slug'],
            ':title' => $prepared['title'],
            ':excerpt' => $prepared['excerpt'],
            ':content' => $prepared['content'],
            ':cover' => $prepared['cover'],
            ':status' => $prepared['status'],
            ':published_at' => $prepared['published_at'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $payload): void
    {
        $prepared = $this->normalize($payload, $id);

        $statement = $this->db->prepare(
            'UPDATE news_posts
             SET news_category_id = :category_id,
                 post_slug = :slug,
                 title = :title,
                 excerpt = :excerpt,
                 content = :content,
                 cover_image_path = :cover,
                 post_status = :status,
                 published_at = :published_at,
                 updated_at = NOW()
             WHERE news_post_id = :id',
        );
        $statement->execute([
            ':category_id' => $prepared['category_id'],
            ':slug' => $prepared['slug'],
            ':title' => $prepared['title'],
            ':excerpt' => $prepared['excerpt'],
            ':content' => $prepared['content'],
            ':cover' => $prepared['cover'],
            ':status' => $prepared['status'],
            ':published_at' => $prepared['published_at'],
            ':id' => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $statement = $this->db->prepare('DELETE FROM news_posts WHERE news_post_id = :id');
        $statement->execute([':id' => $id]);
    }

    private function normalize(array $payload, ?int $postId = null): array
    {
        $title = trim((string) ($payload['title'] ?? ''));
        if ($title === '') {
            throw new InvalidArgumentException('Tiêu đề bài viết không được để trống.');
        }
        $title = mb_substr($title, 0, 255);

        $slug = trim((string) ($payload['slug'] ?? ''));
        if ($slug === '') {
            $slug = $this->slugify($title);
        } else {
            $slug = $this->slugify($slug);
        }
        if ($slug === '') {
            $slug = 'bai-viet';
        }
        $slug = $this->uniqueSlug($slug, $postId);

        $categoryId = (int) ($payload['category_id'] ?? $payload['categoryId'] ?? 0);
        if (!$this->categoryExists($categoryId)) {
            throw new InvalidArgumentException('Danh mục tin tức không hợp lệ.');
        }

        $excerpt = trim((string) ($payload['excerpt'] ?? ''));

        $content = $this->sanitizeContent((string) ($payload['content'] ?? ''));

        $cover = trim((string) ($payload['cover_image_path'] ?? $payload['coverImage'] ?? ''));
        $cover = $cover !== '' ? mb_substr($cover, 0, 500) : null;

        $status = strtolower((string) ($payload['post_status'] ?? $payload['status'] ?? 'draft'));
        if (!in_array($status, ['draft', 'published', 'archived'], true)) {
            $status = 'draft';
        }

        $publishedAt = $this->parseDateTime((string) ($payload['published_at'] ?? ''));
        if ($status === 'published' && $publishedAt === null) {
            // Ràng buộc chk_news_posts_publication: bài published phải có ngày đăng.
            $publishedAt = date('Y-m-d H:i:s');
        }

        return [
            'title' => $title,
            'slug' => $slug,
            'category_id' => $categoryId,
            'excerpt' => $excerpt !== '' ? $excerpt : null,
            'content' => $content,
            'cover' => $cover,
            'status' => $status,
            'published_at' => $publishedAt,
        ];
    }

    /** Sinh slug từ tiêu đề tiếng Việt: hạ chữ, bỏ dấu, gom ký tự lạ thành dấu gạch. */
    private function slugify(string $value): string
    {
        $value = mb_strtolower(trim($value), 'UTF-8');
        $value = strtr($value, self::VIETNAMESE_MAP);
        $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';

        return trim($value, '-');
    }

    private function uniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $candidate = $slug;
        $suffix = 2;

        while ($this->slugExists($candidate, $ignoreId)) {
            $candidate = $slug . '-' . $suffix;
            $suffix++;
        }

        return $candidate;
    }

    private function slugExists(string $slug, ?int $ignoreId): bool
    {
        $sql = 'SELECT news_post_id FROM news_posts WHERE post_slug = :slug';
        $params = [':slug' => $slug];
        if ($ignoreId !== null) {
            $sql .= ' AND news_post_id <> :id';
            $params[':id'] = $ignoreId;
        }
        $statement = $this->db->prepare($sql);
        $statement->execute($params);

        return $statement->fetchColumn() !== false;
    }

    private function categoryExists(int $id): bool
    {
        if ($id <= 0) {
            return false;
        }
        $statement = $this->db->prepare('SELECT 1 FROM news_categories WHERE news_category_id = :id LIMIT 1');
        $statement->execute([':id' => $id]);

        return $statement->fetchColumn() !== false;
    }

    /** Nhận cả "Y-m-d\TH:i" (input datetime-local) lẫn "Y-m-d H:i:s". Null nếu trống/không hợp lệ. */
    private function parseDateTime(string $value): ?string
    {
        $value = trim($value);
        if ($value === '') {
            return null;
        }
        $timestamp = strtotime(str_replace('T', ' ', $value));

        return $timestamp !== false ? date('Y-m-d H:i:s', $timestamp) : null;
    }

    /**
     * Admin là người dùng tin cậy nên không dựng full allowlist như trang công khai;
     * vẫn lọc các thứ nguy hiểm/hay rơi vào khi dán từ nguồn ngoài (script, style, thuộc tính on*, javascript:).
     */
    private function sanitizeContent(string $html): string
    {
        $html = preg_replace('#<script\b[^>]*>.*?</script>#is', '', $html) ?? '';
        $html = preg_replace('#<style\b[^>]*>.*?</style>#is', '', $html) ?? '';
        $html = preg_replace('/\son\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html) ?? '';
        $html = preg_replace('/(href|src)\s*=\s*(["\'])\s*javascript:[^"\']*\2/i', '$1="#"', $html) ?? '';

        return trim($html);
    }
}
