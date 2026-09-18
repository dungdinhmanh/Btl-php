<?php
declare(strict_types=1);

final class UserRepository
{
    public function __construct(private readonly PDO $db) {}

    /** Reads the normalized users and roles tables. */
    public function findByEmail(string $email): ?array
    {
        $statement = $this->db->prepare(
            'SELECT u.user_id AS id, u.full_name AS name, u.email, u.password_hash, r.role_code AS role
             FROM users u INNER JOIN roles r ON r.role_id = u.role_id
             WHERE u.email = :email AND u.account_status = :status LIMIT 1',
        );
        $statement->execute([':email' => $email, ':status' => 'active']);
        return $statement->fetch() ?: null;
    }

    public function create(string $name, string $email, string $password): int
    {
        $statement = $this->db->prepare(
            'INSERT INTO users (role_id, full_name, email, password_hash)
             SELECT role_id, :name, :email, :password_hash FROM roles WHERE role_code = :role_code',
        );
        $statement->execute([
            ':name' => $name,
            ':email' => $email,
            ':password_hash' => password_hash($password, PASSWORD_DEFAULT),
            ':role_code' => 'customer',
        ]);
        return (int) $this->db->lastInsertId();
    }
}
