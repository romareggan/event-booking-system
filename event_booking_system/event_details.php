<?php
require_once "config.php";
$page_title = "Event details";
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
$stmt = $pdo->prepare("SELECT * FROM events WHERE event_id = ?");
$stmt->execute([$id ?: 0]);
$event = $stmt->fetch();
include "header.php";
?>
<?php if (!$event): ?>
    <div class="notice">Event not found. <a href="events.php">Return to events</a>.</div>
<?php else: ?>
<div class="detail-card">
    <span class="tag">Event details</span>
    <h1><?= e($event["event_name"]) ?></h1>
    <p><?= nl2br(e($event["description"])) ?></p>
    <div class="detail-list">
        <p><strong>Date:</strong> <?= e($event["event_date"]) ?></p>
        <p><strong>Time:</strong> <?= e(substr($event["event_time"], 0, 5)) ?></p>
        <p><strong>Location:</strong> <?= e($event["location"]) ?></p>
        <p><strong>Ticket price:</strong> KSh <?= number_format((float)$event["ticket_price"], 2) ?></p>
        <p><strong>Available spaces:</strong> <?= (int)$event["available_slots"] ?></p>
    </div>
    <?php if ((int)$event["available_slots"] > 0 && $event["event_date"] >= date("Y-m-d")): ?>
        <a class="button" href="booking.php?event_id=<?= (int)$event["event_id"] ?>">Book this event</a>
    <?php else: ?>
        <div class="alert">This event is fully booked or has already passed.</div>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php include "footer.php"; ?>
