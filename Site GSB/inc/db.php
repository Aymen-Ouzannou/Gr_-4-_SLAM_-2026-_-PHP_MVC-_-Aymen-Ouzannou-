<?php
require_once __DIR__ . '/config.php';

/**
 * Retourne un objet PDO connecté à la base de données GSB.
 *
 * @return PDO
 * @throws PDOException
 */
function getPDO(): PDO
{
    static $pdo;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = sprintf('mysql:host=127.0.0.1;dbname=GSB_V2;charset=utf8mb4', DB_HOST, DB_NAME, DB_CHARSET);
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    return $pdo;
}
