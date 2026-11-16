<?php
require_once "config.php";
require_admin();
$page_title = "Admin dashboard";
$event_count = (int)$pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
$user_count = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetchColumn();
$booking_count = (int)$pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
include "header.php";
?>
<div class="page-heading"><p class="eyebrow">CONTROL PANEL</p><h1>Admin dashboard</h1><p>Manage events and review bookings.</p></div>
<div class="stats-grid">
    <div class="stat-card"><span>Events</span><strong><?= $event_count ?></strong></div>
    <div class="stat-card"><span>Registered users</span><strong><?= $user_count ?></strong></div>
    <div class="stat-card"><span>Total bookings</span><strong><?= $booking_count ?></strong></div>
</div>
<div class="admin-links">
    <a class="button" href="manage_events.php">Manage events</a>
    <a class="button button-light" href="manage_bookings.php">View bookings</a>
</div>
<?php include "footer.php"; ?>
