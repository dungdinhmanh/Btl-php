<?php
declare(strict_types=1);

final class ProductRepository
{
    public function __construct(private readonly PDO $db) {}

    /**
     * Reads the normalized products, brands, categories, and product_images tables.
     */
    public function featured(int $limit = 4): array
    {
        $statement = $this->db->prepare(
            'SELECT p.product_id AS id, p.product_slug AS slug, p.product_name AS name,
                    b.brand_name AS brand, c.category_slug AS category, p.unit_price AS price,
                    image.image_path AS image, p.socket
             FROM products p
             LEFT JOIN brands b ON b.brand_id = p.brand_id
             INNER JOIN categories c ON c.category_id = p.category_id
             LEFT JOIN product_images image ON image.product_id = p.product_id AND image.display_order = 1
             WHERE p.product_status = :status AND p.is_featured = 1
             ORDER BY p.created_at DESC
             LIMIT :limit',
        );
        $statement->bindValue(':status', 'active');
        $statement->bindValue(':limit', max(1, min($limit, 24)), PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function search(string $query = '', ?string $category = null, int $limit = 24): array
    {
        $where = ['p.product_status = :status'];
        $params = [':status' => 'active'];

        if ($query !== '') {
            $where[] = '(p.product_name LIKE :query OR b.brand_name LIKE :query OR c.category_name LIKE :query)';
            $params[':query'] = '%' . $query . '%';
        }
        if ($category !== null && $category !== '') {
            $where[] = 'c.category_slug = :category';
            $params[':category'] = $category;
        }

        $statement = $this->db->prepare(
            'SELECT p.product_id AS id, p.product_slug AS slug, p.product_name AS name,
                    b.brand_name AS brand, c.category_slug AS category, p.unit_price AS price,
                    image.image_path AS image, p.socket
             FROM products p
             LEFT JOIN brands b ON b.brand_id = p.brand_id
             INNER JOIN categories c ON c.category_id = p.category_id
             LEFT JOIN product_images image ON image.product_id = p.product_id AND image.display_order = 1
             WHERE ' . implode(' AND ', $where) . '
             ORDER BY p.created_at DESC LIMIT :limit',
        );
        foreach ($params as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->bindValue(':limit', max(1, min($limit, 100)), PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }
}
