<?php

declare(strict_types=1);

final class AdminProductRepository
{
    public function __construct(private readonly PDO $db) {}

    /**
     * @return array<int, array<string, mixed>>
     */
    public function list(): array
    {
        $statement = $this->db->query(
            'SELECT p.product_id AS id, p.product_name AS name, p.product_slug AS slug, p.sku,
                    p.unit_price AS price, p.product_status AS status, p.is_featured AS isFeatured,
                    p.socket AS socket, p.created_at AS createdAt,
                    b.brand_id AS brandId, b.brand_name AS brand,
                    c.category_id AS categoryId, c.category_name AS category,
                    COALESCE(SUM(i.quantity_on_hand - i.quantity_reserved), 0) AS stock
             FROM products p
             LEFT JOIN brands b ON b.brand_id = p.brand_id
             LEFT JOIN categories c ON c.category_id = p.category_id
             LEFT JOIN inventory i ON i.product_id = p.product_id
             GROUP BY p.product_id, p.product_name, p.product_slug, p.sku, p.unit_price,
                      p.product_status, p.is_featured, p.socket, p.created_at,
                      b.brand_id, b.brand_name, c.category_id, c.category_name
             ORDER BY p.created_at DESC, p.product_id DESC',
        );

        return array_map(static function (array $row): array {
            return [
                'id' => (int) $row['id'],
                'name' => (string) $row['name'],
                'slug' => (string) $row['slug'],
                'sku' => (string) $row['sku'],
                'brandId' => $row['brandId'] !== null ? (int) $row['brandId'] : null,
                'brand' => $row['brand'] !== null ? (string) $row['brand'] : 'Chưa gán',
                'categoryId' => $row['categoryId'] !== null ? (int) $row['categoryId'] : null,
                'category' => $row['category'] !== null ? (string) $row['category'] : 'Chưa gán',
                'price' => (float) $row['price'],
                'priceText' => number_format((float) $row['price'], 0, ',', '.') . ' đ',
                'status' => (string) $row['status'],
                'isFeatured' => (bool) $row['isFeatured'],
                'socket' => $row['socket'] !== null ? (string) $row['socket'] : '',
                'stock' => (int) $row['stock'],
                'createdAt' => (string) $row['createdAt'],
            ];
        }, $statement->fetchAll());
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function categories(): array
    {
        $statement = $this->db->query(
            'SELECT category_id AS id, category_name AS name, category_slug AS slug
             FROM categories
             ORDER BY category_name ASC',
        );

        return array_map(static fn (array $row): array => [
            'id' => (int) $row['id'],
            'name' => (string) $row['name'],
            'slug' => (string) $row['slug'],
        ], $statement->fetchAll());
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function brands(): array
    {
        $statement = $this->db->query(
            'SELECT brand_id AS id, brand_name AS name, brand_slug AS slug
             FROM brands
             ORDER BY brand_name ASC',
        );

        return array_map(static fn (array $row): array => [
            'id' => (int) $row['id'],
            'name' => (string) $row['name'],
            'slug' => (string) $row['slug'],
        ], $statement->fetchAll());
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function create(array $payload): int
    {
        $prepared = $this->normalize($payload);

        $statement = $this->db->prepare(
            'INSERT INTO products (
                brand_id, category_id, sku, product_slug, product_name, socket,
                unit_price, product_status, is_featured, created_at, updated_at
            ) VALUES (
                :brand_id, :category_id, :sku, :slug, :name, :socket,
                :price, :status, :is_featured, NOW(), NOW()
            )',
        );

        $statement->execute([
            ':brand_id' => $prepared['brand_id'],
            ':category_id' => $prepared['category_id'],
            ':sku' => $prepared['sku'],
            ':slug' => $prepared['slug'],
            ':name' => $prepared['name'],
            ':socket' => $prepared['socket'],
            ':price' => $prepared['price'],
            ':status' => $prepared['status'],
            ':is_featured' => $prepared['is_featured'] ? 1 : 0,
        ]);

        $productId = (int) $this->db->lastInsertId();
        $this->syncStock($productId, $prepared['stock']);

        return $productId;
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function update(int $productId, array $payload): void
    {
        $prepared = $this->normalize($payload, $productId);

        $statement = $this->db->prepare(
            'UPDATE products
             SET brand_id = :brand_id,
                 category_id = :category_id,
                 sku = :sku,
                 product_slug = :slug,
                 product_name = :name,
                 socket = :socket,
                 unit_price = :price,
                 product_status = :status,
                 is_featured = :is_featured,
                 updated_at = NOW()
             WHERE product_id = :id',
        );

        $statement->execute([
            ':brand_id' => $prepared['brand_id'],
            ':category_id' => $prepared['category_id'],
            ':sku' => $prepared['sku'],
            ':slug' => $prepared['slug'],
            ':name' => $prepared['name'],
            ':socket' => $prepared['socket'],
            ':price' => $prepared['price'],
            ':status' => $prepared['status'],
            ':is_featured' => $prepared['is_featured'] ? 1 : 0,
            ':id' => $productId,
        ]);

        $this->syncStock($productId, $prepared['stock']);
    }

    public function delete(int $productId): void
    {
        $statement = $this->db->prepare('DELETE FROM products WHERE product_id = :id');
        $statement->execute([':id' => $productId]);
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    private function normalize(array $payload, ?int $productId = null): array
    {
        $name = trim((string) ($payload['name'] ?? ''));
        if ($name === '') {
            throw new InvalidArgumentException('Tên sản phẩm không được để trống.');
        }

        $sku = trim((string) ($payload['sku'] ?? ''));
        if ($sku === '') {
            $sku = strtoupper(preg_replace('/[^A-Za-z0-9]+/', '-', $name));
        }

        $slug = trim((string) ($payload['slug'] ?? ''));
        if ($slug === '') {
            $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name));
        }
        $slug = trim((string) preg_replace('/-+/', '-', $slug), '-');
        if ($slug === '') {
            throw new InvalidArgumentException('Slug sản phẩm không hợp lệ.');
        }

        $brandId = $this->toNullableInt($payload['brand_id'] ?? $payload['brandId'] ?? null);
        $categoryId = $this->toNullableInt($payload['category_id'] ?? $payload['categoryId'] ?? null);

        if ($categoryId === null) {
            throw new InvalidArgumentException('Danh mục sản phẩm là bắt buộc.');
        }

        $status = strtolower((string) ($payload['product_status'] ?? $payload['status'] ?? 'draft'));
        if (!in_array($status, ['draft', 'active', 'archived'], true)) {
            $status = 'draft';
        }

        $price = (float) ($payload['unit_price'] ?? $payload['price'] ?? 0);
        $stock = max(0, (int) ($payload['stock'] ?? 0));

        $socket = trim((string) ($payload['socket'] ?? ''));
        $isFeatured = !empty($payload['is_featured'] ?? $payload['isFeatured'] ?? false)
            ? 1
            : 0;

        $uniqueSku = $this->uniqueValue('sku', $sku, $productId);
        $uniqueSlug = $this->uniqueValue('slug', $slug, $productId);

        return [
            'name' => $name,
            'sku' => $uniqueSku,
            'slug' => $uniqueSlug,
            'brand_id' => $brandId,
            'category_id' => $categoryId,
            'status' => $status,
            'price' => $price,
            'stock' => $stock,
            'socket' => $socket,
            'is_featured' => $isFeatured,
        ];
    }

    private function uniqueValue(string $field, string $value, ?int $ignoreProductId = null): string
    {
        $column = $field === 'sku' ? 'sku' : 'product_slug';
        $sql = 'SELECT product_id FROM products WHERE ' . $column . ' = :value';
        $params = [':value' => $value];

        if ($ignoreProductId !== null) {
            $sql .= ' AND product_id <> :ignore_id';
            $params[':ignore_id'] = $ignoreProductId;
        }

        $statement = $this->db->prepare($sql);
        $statement->execute($params);

        if ($statement->fetchColumn() !== false) {
            $suffix = $ignoreProductId !== null ? '-' . $ignoreProductId : '';
            $candidate = $value . $suffix;
            return $this->uniqueValue($field, $candidate, $ignoreProductId);
        }

        return $value;
    }

    private function syncStock(int $productId, int $stock): void
    {
        $warehouseId = $this->db->query(
            'SELECT warehouse_id FROM warehouses ORDER BY warehouse_id ASC LIMIT 1',
        )->fetchColumn();

        if ($warehouseId === false || $warehouseId === null) {
            return;
        }

        $statement = $this->db->prepare(
            'INSERT INTO inventory (warehouse_id, product_id, quantity_on_hand, quantity_reserved)
             VALUES (:warehouse_id, :product_id, :stock, 0)
             ON DUPLICATE KEY UPDATE quantity_on_hand = VALUES(quantity_on_hand), quantity_reserved = 0',
        );

        $statement->execute([
            ':warehouse_id' => (int) $warehouseId,
            ':product_id' => $productId,
            ':stock' => $stock,
        ]);
    }

    private function toNullableInt(mixed $value): ?int
    {
        if ($value === null || $value === '' || $value === 'null') {
            return null;
        }

        $int = filter_var($value, FILTER_VALIDATE_INT);
        if ($int === false) {
            return null;
        }

        return (int) $int;
    }
}
