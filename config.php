<?php
declare(strict_types=1);

const APP_NAME = 'ЯРКО! Night Show';
const DB_HOST = '127.0.0.1';
define('DB_PORT', getenv('YARKO_DB_PORT') ?: '3306');
const DB_NAME = 'animator_services';
const DB_USER = 'root';
const DB_PASS = '';
const DB_CHARSET = 'utf8mb4';

if (session_status() !== PHP_SESSION_ACTIVE) {
    $sessionDirectory = __DIR__ . '/.sessions';
    if (!is_dir($sessionDirectory)) {
        mkdir($sessionDirectory, 0775, true);
    }
    session_save_path($sessionDirectory);
    session_start();
}

function db(): ?PDO
{
    static $pdo = false;
    if ($pdo instanceof PDO) {
        return $pdo;
    }
    if ($pdo === null) {
        return null;
    }

    try {
        $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (PDOException $exception) {
        $pdo = null;
    }

    return $pdo instanceof PDO ? $pdo : null;
}
