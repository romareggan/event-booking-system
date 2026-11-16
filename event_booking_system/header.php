<?php
require_once "config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? e($page_title) . " | EventBook" : "EventBook" ?></title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/script.js" defer></script>
</head>
<body>
<header class="site-header">
    <div class="header-inner">
        <a class="logo" href="index.php">EventBook</a>
        <nav>
            <a href="index.php">Home</a>
            <a href="events.php">Events</a>
            <?php if (!empty($_SESSION["user_id"])): ?>
                <a href="my_bookings.php">My Bookings</a>
                <?php if (($_SESSION["role"] ?? "") === "admin"): ?>
                    <a href="admin_dashboard.php">Admin</a>
                <?php endif; ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a class="nav-button" href="register.php">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="container">
