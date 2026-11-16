<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

$pdo = null;
foreach ([3307, 3306] as $port) {
    try {
        $pdo = new PDO("mysql:host=127.0.0.1;port=$port;dbname=event_booking;charset=utf8mb4", "root", "", $options);
        break;
    } catch (PDOException $e) {
        // Try fallback port
    }
}

if (!$pdo) {
    exit("Database connection failed. Ensure MySQL is running and database.sql is imported.");
}

function require_login(): void {
    if (empty($_SESSION["user_id"])) {
        header("Location: login.php");
        exit;
    }
}

function require_admin(): void {
    require_login();
    if (($_SESSION["role"] ?? "") !== "admin") {
        header("Location: index.php");
        exit;
    }
}

function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}
?>