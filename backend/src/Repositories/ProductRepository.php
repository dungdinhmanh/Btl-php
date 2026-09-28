<?php
declare(strict_types=1);

final class ProductRepository
{
    private const SORTS = [
        'newest' => 'p.created_at DESC, p.product_id ASC',
        'price-asc' => 'p.unit_price ASC, p.product_id ASC',
        'price-desc' => 'p.unit_price DESC, p.product_id ASC',
        'name-asc' => 'p.product_name ASC',
    ];

    private const LIST_SELECT = 'SELECT p.product_id AS id, p.product_slug AS slug, p.product_name AS name,
                b.brand_name AS brand, c.category_slug AS category, c.category_name AS categoryName,
                p.unit_price AS price, image.image_path AS image, p.socket AS socket,
                p.is_featured AS isFeatured';

    private const LIST_FROM = 'FROM products p
                LEFT JOIN brands b ON b.brand_id = p.brand_id
                INNER JOIN categories c ON c.category_id = p.category_id
                LEFT JOIN product_images image ON image.product_id = p.product_id AND image.display_order = 1';

    public function __construct(private readonly PDO $db) {}

    public function featured(int $limit = 4): array
    {
        $statement = $this->db->prepare(
            self::LIST_SELECT . ' ' . self::LIST_FROM . '
             WHERE p.product_status = :status AND p.is_featured = 1
             ORDER BY p.created_at DESC, p.product_id ASC
             LIMIT :limit',
        );
        $statement->bindValue(':status', 'active');
        $statement->bindValue(':limit', max(1, min($limit, 24)), PDO::PARAM_INT);
        $statement->execute();

        return $this->presentMany($statement->fetchAll());
    }

    /**
     * Catalog listing with search, category/brand filters and sorting.
     *
     * @param string[] $categories additional category slugs, OR-ed together
     */
    public function search(
        string $query = '',
        ?string $category = null,
        int $limit = 24,
        int $offset = 0,
        ?string $brand = null,
        string $sort = 'newest',
        array $categories = [],
    ): array {
        [$where, $params] = $this->buildFilters($query, $category, $brand, $categories);

        $statement = $this->db->prepare(
            self::LIST_SELECT . ' ' . self::LIST_FROM . '
             WHERE ' . implode(' AND ', $where) . '
             ORDER BY ' . (self::SORTS[$sort] ?? self::SORTS['newest']) . '
             LIMIT :limit OFFSET :offset',
        );
        foreach ($params as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->bindValue(':limit', max(1, min($limit, 100)), PDO::PARAM_INT);
        $statement->bindValue(':offset', max(0, $offset), PDO::PARAM_INT);
        $statement->execute();

        return $this->presentMany($statement->fetchAll());
    }

    public function count(
        string $query = '',
        ?string $category = null,
        ?string $brand = null,
        array $categories = [],
    ): int {
        [$where, $params] = $this->buildFilters($query, $category, $brand, $categories);

        $statement = $this->db->prepare(
            'SELECT COUNT(*) FROM products p
             LEFT JOIN brands b ON b.brand_id = p.brand_id
             INNER JOIN categories c ON c.category_id = p.category_id
             WHERE ' . implode(' AND ', $where),
        );
        $statement->execute($params);

        return (int) $statement->fetchColumn();
    }

    /**
     * A few products per category, keyed by category slug, for the home page rows.
     *
     * @param string[] $slugs
     * @return array<string, array<int, array<string, mixed>>>
     */
    public function byCategories(array $slugs, int $perCategory = 8): array
    {
        $grouped = [];
        foreach (array_values(array_unique(array_filter(array_map('trim', $slugs)))) as $slug) {
            $grouped[$slug] = $this->search(category: $slug, limit: $perCategory);
        }

        return $grouped;
    }

    public function findBySlug(string $slug): ?array
    {
        $statement = $this->db->prepare(
            self::LIST_SELECT . ', d.specification_code AS specCode, d.specification_name AS specName,
                    d.display_unit AS specUnit, spec.specification_value AS specValue
             ' . self::LIST_FROM . '
             LEFT JOIN product_specifications spec ON spec.product_id = p.product_id
             LEFT JOIN specification_definitions d ON d.specification_id = spec.specification_id
             WHERE p.product_slug = :slug AND p.product_status = :status
             ORDER BY d.specification_id ASC',
        );
        $statement->execute([':slug' => $slug, ':status' => 'active']);
        $rows = $statement->fetchAll();

        if ($rows === []) {
            return null;
        }

        $product = $this->presentOne($rows[0]);
        $product['specs'] = [];
        foreach ($rows as $row) {
            if ($row['specCode'] === null) {
                continue;
            }
            $product['specs'][] = [
                'code' => (string) $row['specCode'],
                'label' => (string) $row['specName'],
                'value' => (string) $row['specValue'],
                'unit' => $row['specUnit'] !== null ? (string) $row['specUnit'] : null,
            ];
        }

        $product['images'] = $this->imagesFor((int) $product['id']);
        $product['stock'] = $this->stockFor((int) $product['id']);

        return $product;
    }

    /** Best-effort lookup so legacy ?model= / ?id= links keep working. */
    public function findByIdentifier(string $identifier): ?array
    {
        $identifier = trim($identifier);
        if ($identifier === '') {
            return null;
        }

        $direct = $this->findBySlug($identifier);
        if ($direct !== null) {
            return $direct;
        }

        if (ctype_digit($identifier)) {
            $byId = $this->findBySlug($this->slugForId((int) $identifier));
            if ($byId !== null) {
                return $byId;
            }
        }

        $statement = $this->db->prepare(
            'SELECT p.product_slug FROM products p
             WHERE p.product_status = :status AND p.product_name LIKE :term
             ORDER BY p.product_id ASC LIMIT 1',
        );
        $statement->execute([':status' => 'active', ':term' => '%' . $identifier . '%']);
        $slug = $statement->fetchColumn();

        return is_string($slug) ? $this->findBySlug($slug) : null;
    }

    public function categories(): array
    {
        $statement = $this->db->query(
            "SELECT c.category_id AS id, c.category_slug AS slug, c.category_name AS name,
                    COUNT(p.product_id) AS productCount
             FROM categories c
             LEFT JOIN products p ON p.category_id = c.category_id AND p.product_status = 'active'
             GROUP BY c.category_id, c.category_slug, c.category_name
             ORDER BY c.category_name ASC",
        );

        return array_map(static fn (array $row): array => [
            'id' => (int) $row['id'],
            'slug' => (string) $row['slug'],
            'name' => (string) $row['name'],
            'productCount' => (int) $row['productCount'],
        ], $statement->fetchAll());
    }

    public function brands(): array
    {
        $statement = $this->db->query(
            "SELECT b.brand_id AS id, b.brand_name AS name, b.brand_slug AS slug,
                    COUNT(p.product_id) AS productCount
             FROM brands b
             LEFT JOIN products p ON p.brand_id = b.brand_id AND p.product_status = 'active'
             GROUP BY b.brand_id, b.brand_name, b.brand_slug
             ORDER BY b.brand_name ASC",
        );

        return array_values(array_filter(
            array_map(static fn (array $row): array => [
                'id' => (int) $row['id'],
                'name' => (string) $row['name'],
                'slug' => (string) $row['slug'],
                'productCount' => (int) $row['productCount'],
            ], $statement->fetchAll()),
            static fn (array $brand): bool => $brand['productCount'] > 0,
        ));
    }

    /**
     * @param string[] $categories
     * @return array{0: string[], 1: array<string, string>}
     */
    private function buildFilters(string $query, ?string $category, ?string $brand, array $categories): array
    {
        $where = ['p.product_status = :status'];
        $params = [':status' => 'active'];

        $categories = array_values(array_filter(array_map('trim', $categories)));
        if ($categories !== []) {
            $placeholders = [];
            foreach ($categories as $index => $slug) {
                $key = ':category' . $index;
                $placeholders[] = $key;
                $params[$key] = $slug;
            }
            $where[] = 'c.category_slug IN (' . implode(', ', $placeholders) . ')';
        } elseif ($category !== null && $category !== '') {
            $where[] = 'c.category_slug = :category';
            $params[':category'] = $category;
        }

        if ($brand !== null && $brand !== '') {
            $where[] = 'b.brand_slug = :brand';
            $params[':brand'] = $brand;
        }

        if ($query !== '') {
            // Separate placeholders: native prepares reject the same named parameter twice.
            $where[] = '(p.product_name LIKE :queryName OR b.brand_name LIKE :queryBrand
                         OR c.category_name LIKE :queryCategory OR p.sku LIKE :querySku)';
            $term = '%' . $query . '%';
            $params[':queryName'] = $term;
            $params[':queryBrand'] = $term;
            $params[':queryCategory'] = $term;
            $params[':querySku'] = $term;
        }

        return [$where, $params];
    }

    private function slugForId(int $id): string
    {
        $statement = $this->db->prepare('SELECT product_slug FROM products WHERE product_id = :id LIMIT 1');
        $statement->execute([':id' => $id]);
        $slug = $statement->fetchColumn();

        return is_string($slug) ? $slug : '';
    }

    /** @return string[] */
    private function imagesFor(int $productId): array
    {
        $statement = $this->db->prepare(
            'SELECT image_path FROM product_images WHERE product_id = :id ORDER BY display_order ASC',
        );
        $statement->execute([':id' => $productId]);

        return array_values(array_map(
            static fn ($path): string => (string) $path,
            $statement->fetchAll(PDO::FETCH_COLUMN),
        ));
    }

    private function stockFor(int $productId): int
    {
        $statement = $this->db->prepare(
            'SELECT COALESCE(SUM(quantity_on_hand - quantity_reserved), 0) FROM inventory WHERE product_id = :id',
        );
        $statement->execute([':id' => $productId]);

        return (int) $statement->fetchColumn();
    }

    /** @param array<int, array<string, mixed>> $rows */
    private function presentMany(array $rows): array
    {
        return array_map(fn (array $row): array => $this->presentOne($row), $rows);
    }

    /** @param array<string, mixed> $row */
    private function presentOne(array $row): array
    {
        $price = (float) $row['price'];

        return [
            'id' => (int) $row['id'],
            'slug' => (string) $row['slug'],
            'name' => (string) $row['name'],
            'brand' => $row['brand'] !== null ? (string) $row['brand'] : null,
            'category' => (string) $row['category'],
            'categoryName' => (string) $row['categoryName'],
            'price' => $price,
            'priceText' => $price > 0 ? number_format($price, 0, ',', '.') . ' đ' : 'Liên hệ',
            'image' => $row['image'] !== null ? (string) $row['image'] : 'assets/img/branding/tnc.png',
            'socket' => $row['socket'] !== null ? (string) $row['socket'] : null,
            'isFeatured' => (bool) ($row['isFeatured'] ?? false),
        ];
    }
}
