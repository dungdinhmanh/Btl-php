<?php
declare(strict_types=1);

function database(): PDO
{
    static $connection = null;

    if ($connection instanceof PDO) {
        return $connection;
    }

    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $port = getenv('DB_PORT') ?: '3306';
    $name = getenv('DB_DATABASE') ?: '';
    $user = getenv('DB_USERNAME') ?: '';
    $password = getenv('DB_PASSWORD') ?: '';
    $sslCa = getenv('DB_SSL_CA') ?: '';

    if ($name === '' || $user === '') {
        throw new RuntimeException('Database is not configured. Copy backend/config/.env.example to .env and fill in the connection values.');
    }

    $option = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    if ($sslCa !== '') {
    $options[PDO::MYSQL_ATTR_SSL_CA] = __DIR__ . DIRECTORY_SEPARATOR . $sslCa;
    $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = true;
}

    $connection = new PDO (
        "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4",
        $user,
        $password,
        $option,
    );

    return $connection;
}
