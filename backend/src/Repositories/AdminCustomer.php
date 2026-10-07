<?php
declare(strict_types=1);

final class AdminCustomerRepository
{
    public function __construct(private readonly PDO $db) {}

    /**
     * @return array<int, array<string, mixed>>
     */
    public function list(): array
    {
        $statement = $this->db->query(
            'SELECT user_id AS id, full_name AS name, email, phone, created_at AS joinedAt
             FROM users
             WHERE account_status = :status
             ORDER BY created_at DESC',
        );
        $statement->execute([':status' => 'active']);

        return array_map(static function (array $row): array {
            $joinedAt = strtotime((string) $row['joinedAt']);

            return [
                'id' => (int) $row['id'],
                'name' => (string) $row['name'],
                'email' => (string) $row['email'],
                'phone' => (string) $row['phone'],
                'joinedAt' => date('d/m/Y', $joinedAt),
            ];
        }, $statement->fetchAll());
    }
    public function delete(int $userId): bool
    {
        $statement = $this->db->prepare(
            'UPDATE users SET account_status = :status WHERE user_id = :id',
        );
        $statement->execute([':status' => 'deleted', ':id' => $userId]);

        return $statement->rowCount() > 0;
    }
    public function 
}