<?php
declare(strict_types=1);

final class AdminCustomerRepository
{
    public function __construct(private readonly PDO $db) {}

    /**
        * @return array{total: int, active: int, disabled: int, deleted: int, newThisMonth: int}
     */
    public function stats(): array
    {
        $statement = $this->db->query(
            "SELECT COUNT(*) AS total,
                    COALESCE(SUM(u.account_status = 'active'), 0) AS active,
                    COALESCE(SUM(u.account_status = 'disabled'), 0) AS disabled,
                    COALESCE(SUM(u.account_status = 'deleted'), 0) AS deleted,
                    COALESCE(SUM(u.created_at >= DATE_FORMAT(CURRENT_DATE, '%Y-%m-01')), 0) AS newThisMonth
             FROM users u
             INNER JOIN roles r ON r.role_id = u.role_id
             WHERE r.role_code = 'customer'",
        );
        $row = $statement->fetch();

        return [
            'total' => (int) $row['total'],
            'active' => (int) $row['active'],
            'disabled' => (int) $row['disabled'],
            'deleted' => (int) $row['deleted'],
            'newThisMonth' => (int) $row['newThisMonth'],
        ];
    }

    /**
     * @return array{items: array<int, array<string, mixed>>, total: int, page: int, perPage: int}
     */
    public function list(
        string $search = '',
        string $status = 'all',
        int $page = 1,
        int $perPage = 20,
        string $registeredFrom = '',
        string $registeredTo = '',
    ): array
    {
        if (!in_array($status, ['all', 'active', 'disabled', 'deleted'], true)) {
            throw new InvalidArgumentException('Trạng thái tài khoản không hợp lệ.');
        }

        $conditions = ["r.role_code = 'customer'"];
        $parameters = [];
        if ($status !== 'all') {
            $conditions[] = 'u.account_status = :status';
            $parameters[':status'] = $status;
        }
        if ($search !== '') {
            $conditions[] = '(u.full_name LIKE :search_name OR u.email LIKE :search_email OR u.phone LIKE :search_phone)';
            $searchTerm = '%' . $search . '%';
            $parameters[':search_name'] = $searchTerm;
            $parameters[':search_email'] = $searchTerm;
            $parameters[':search_phone'] = $searchTerm;
        }
        if ($registeredFrom !== '') {
            $conditions[] = 'u.created_at >= :registered_from';
            $parameters[':registered_from'] = $registeredFrom;
        }
        if ($registeredTo !== '') {
            $conditions[] = 'u.created_at < DATE_ADD(:registered_to, INTERVAL 1 DAY)';
            $parameters[':registered_to'] = $registeredTo;
        }
        $where = implode(' AND ', $conditions);

        $countStatement = $this->db->prepare(
            "SELECT COUNT(*) FROM users u
             INNER JOIN roles r ON r.role_id = u.role_id
             WHERE {$where}",
        );
        $countStatement->execute($parameters);
        $total = (int) $countStatement->fetchColumn();
        $perPage = max(1, min($perPage, 100));
        $page = max(1, min($page, max(1, (int) ceil($total / $perPage))));

        $statement = $this->db->prepare(
            "SELECT u.user_id AS id, u.full_name AS name, u.email, u.phone,
                    u.account_status AS status, u.created_at AS joinedAt, COUNT(o.order_id) AS orderCount
             FROM users u
             INNER JOIN roles r ON r.role_id = u.role_id
             LEFT JOIN orders o ON o.user_id = u.user_id
             WHERE {$where}
             GROUP BY u.user_id, u.full_name, u.email, u.phone, u.account_status, u.created_at
             ORDER BY u.created_at DESC, u.user_id DESC
             LIMIT :limit OFFSET :offset",
        );
        foreach ($parameters as $key => $value) {
            $statement->bindValue($key, $value, PDO::PARAM_STR);
        }
        $statement->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $statement->bindValue(':offset', ($page - 1) * $perPage, PDO::PARAM_INT);
        $statement->execute();

        $items = array_map(static fn (array $row): array => [
            'id' => (int) $row['id'],
            'name' => (string) $row['name'],
            'email' => (string) $row['email'],
            'phone' => $row['phone'] !== null ? (string) $row['phone'] : '',
            'status' => (string) $row['status'],
            'joinedAt' => (new DateTimeImmutable((string) $row['joinedAt']))->format('d/m/Y'),
            'orderCount' => (int) $row['orderCount'],
        ], $statement->fetchAll());

        return ['items' => $items, 'total' => $total, 'page' => $page, 'perPage' => $perPage];
    }

    /** @return array<string, mixed>|null */
    public function detail(int $userId): ?array
    {
        $statement = $this->db->prepare(
            "SELECT u.user_id AS id, u.full_name AS name, u.email, u.phone,
                    u.account_status AS status, u.created_at AS joinedAt
             FROM users u
             INNER JOIN roles r ON r.role_id = u.role_id
             WHERE u.user_id = :id AND r.role_code = 'customer'
             LIMIT 1",
        );
        $statement->execute([':id' => $userId]);
        $row = $statement->fetch();
        if ($row === false) {
            return null;
        }

        $orders = $this->db->prepare(
            'SELECT o.order_number AS number, o.placed_at AS placedAt, s.status_name AS status,
                    COALESCE(SUM(i.quantity * i.unit_price), 0) + o.shipping_amount - o.discount_amount AS total
             FROM orders o
             INNER JOIN order_statuses s ON s.order_status_id = o.order_status_id
             LEFT JOIN order_items i ON i.order_id = o.order_id
             WHERE o.user_id = :id
             GROUP BY o.order_id, o.order_number, o.placed_at, s.status_name, o.shipping_amount, o.discount_amount
             ORDER BY o.placed_at DESC, o.order_id DESC
             LIMIT 10',
        );
        $orders->execute([':id' => $userId]);

        $address = $this->db->prepare(
            'SELECT recipient_name, recipient_phone, line_1, line_2, ward, district, city
             FROM addresses
             WHERE user_id = :id
             ORDER BY is_default DESC, address_id DESC
             LIMIT 1',
        );
        $address->execute([':id' => $userId]);
        $addressRow = $address->fetch();

        return [
            'id' => (int) $row['id'],
            'name' => (string) $row['name'],
            'email' => (string) $row['email'],
            'phone' => $row['phone'] !== null ? (string) $row['phone'] : '',
            'status' => (string) $row['status'],
            'joinedAt' => (new DateTimeImmutable((string) $row['joinedAt']))->format('d/m/Y'),
            'address' => $addressRow === false ? null : [
                'recipientName' => (string) $addressRow['recipient_name'],
                'recipientPhone' => (string) $addressRow['recipient_phone'],
                'text' => implode(', ', array_filter([
                    $addressRow['line_1'], $addressRow['line_2'], $addressRow['ward'],
                    $addressRow['district'], $addressRow['city'],
                ])),
            ],
            'orders' => array_map(static fn (array $order): array => [
                'number' => (string) $order['number'],
                'placedAt' => (new DateTimeImmutable((string) $order['placedAt']))->format('d/m/Y'),
                'status' => (string) $order['status'],
                'total' => (float) $order['total'],
            ], $orders->fetchAll()),
        ];
    }

    public function updateStatus(int $userId, string $status): bool
    {
        if (!in_array($status, ['active', 'disabled'], true)) {
            throw new InvalidArgumentException('Trạng thái tài khoản không hợp lệ.');
        }

        $statement = $this->db->prepare(
            "UPDATE users u
             INNER JOIN roles r ON r.role_id = u.role_id
             SET u.account_status = :status
             WHERE u.user_id = :id AND r.role_code = 'customer'",
        );
        $statement->execute([':status' => $status, ':id' => $userId]);

        return $statement->rowCount() > 0;
    }

    public function delete(int $userId): bool
    {
        $statement = $this->db->prepare(
            "DELETE u FROM users u
             INNER JOIN roles r ON r.role_id = u.role_id
             WHERE u.user_id = :id AND r.role_code = 'customer'",
        );
        $statement->execute([':id' => $userId]);

        return $statement->rowCount() > 0;
    }
}