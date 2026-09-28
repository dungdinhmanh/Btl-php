<?php
declare(strict_types=1);

/**
 * Applies a .sql file through the project's PDO connection, so remote/TLS
 * databases work without configuring the mysql CLI separately.
 *
 *   php backend/database/apply.php backend/database/schema.sql
 *   php backend/database/apply.php backend/database/seed.sql
 */

require_once __DIR__ . '/../bootstrap.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

$path = $argv[1] ?? '';
if ($path === '' || !is_file($path)) {
    fwrite(STDERR, "Usage: php backend/database/apply.php <file.sql>\n");
    exit(1);
}

/**
 * Splits a dump into statements, ignoring semicolons inside strings, quoted
 * identifiers and comments.
 *
 * @return string[]
 */
function splitSqlStatements(string $sql): array
{
    $statements = [];
    $buffer = '';
    $length = strlen($sql);
    $quote = null;
    $lineComment = false;
    $blockComment = false;

    for ($i = 0; $i < $length; $i++) {
        $char = $sql[$i];
        $next = $i + 1 < $length ? $sql[$i + 1] : '';

        if ($lineComment) {
            if ($char === "\n") {
                $lineComment = false;
                $buffer .= $char;
            }
            continue;
        }

        if ($blockComment) {
            if ($char === '*' && $next === '/') {
                $blockComment = false;
                $i++;
            }
            continue;
        }

        if ($quote !== null) {
            $buffer .= $char;

            if ($char === '\\' && $quote !== '`' && $next !== '') {
                $buffer .= $next;
                $i++;
                continue;
            }

            if ($char === $quote) {
                if ($next === $quote) {
                    $buffer .= $next;
                    $i++;
                    continue;
                }
                $quote = null;
            }
            continue;
        }

        if ($char === '-' && $next === '-') {
            $lineComment = true;
            $i++;
            continue;
        }

        if ($char === '#') {
            $lineComment = true;
            continue;
        }

        if ($char === '/' && $next === '*') {
            $blockComment = true;
            $i++;
            continue;
        }

        if ($char === "'" || $char === '"' || $char === '`') {
            $quote = $char;
            $buffer .= $char;
            continue;
        }

        if ($char === ';') {
            $statement = trim($buffer);
            if ($statement !== '') {
                $statements[] = $statement;
            }
            $buffer = '';
            continue;
        }

        $buffer .= $char;
    }

    $statement = trim($buffer);
    if ($statement !== '') {
        $statements[] = $statement;
    }

    return $statements;
}

$statements = splitSqlStatements((string) file_get_contents($path));
$connection = database();
$applied = 0;

foreach ($statements as $index => $statement) {
    try {
        $connection->exec($statement);
        $applied++;
    } catch (Throwable $exception) {
        fwrite(STDERR, sprintf(
            "Statement %d of %d failed:\n  %s\n  %s\n",
            $index + 1,
            count($statements),
            substr(preg_replace('/\s+/', ' ', $statement) ?? '', 0, 160),
            $exception->getMessage(),
        ));
        exit(1);
    }
}

printf("Applied %d statement(s) from %s\n", $applied, $path);
