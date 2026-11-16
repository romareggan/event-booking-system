<?php
require_once "config.php";
require_login();
$page_title = "Book event";
$event_id = filter_input(INPUT_GET, "event_id", FILTER_VALIDATE_INT) ?: filter_input(INPUT_POST, "event_id", FILTER_VALIDATE_INT);
$stmt = $pdo->prepare("SELECT * FROM events WHERE event_id = ?");
$stmt->execute([$event_id ?: 0]);
$event = $stmt->fetch();
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && $event) {
    $quantity = filter_input(INPUT_POST, "number_of_tickets", FILTER_VALIDATE_INT);
    if (!$quantity || $quantity < 1) {
        $error = "Enter a valid number of tickets.";
    } else {
        try {
            $pdo->beginTransaction();
            $check = $pdo->prepare("SELECT available_slots FROM events WHERE event_id = ? FOR UPDATE");
            $check->execute([$event_id]);
            $available = (int)$check->fetchColumn();

            if ($quantity > $available) {
                throw new RuntimeException("Only $available spaces are currently available.");
            }

            $insert = $pdo->prepare("INSERT INTO bookings (user_id, event_id, number_of_tickets) VALUES (?, ?, ?)");
            $insert->execute([$_SESSION["user_id"], $event_id, $quantity]);

            $update = $pdo->prepare("UPDATE events SET available_slots = available_slots - ? WHERE event_id = ?");
            $update->execute([$quantity, $event_id]);
            $pdo->commit();
            header("Location: my_bookings.php?booked=1");
            exit;
        } catch (Throwable $ex) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $error = $ex instanceof RuntimeException ? $ex->getMessage() : "The booking could not be completed. Please try again.";
        }
    }
}
include "header.php";
?>
<?php if (!$event): ?>
    <div class="notice">Event not found.</div>
<?php else: ?>
<div class="form-card">
    <h1>Book an event</h1>
    <h3><?= e($event["event_name"]) ?></h3>
    <p class="muted"><?= e($event["event_date"]) ?> · <?= e($event["location"]) ?></p>
    <p>Ticket price: <strong>KSh <?= number_format((float)$event["ticket_price"], 2) ?></strong></p>
    <p>Available spaces: <strong><?= (int)$event["available_slots"] ?></strong></p>
    <?php if ($error): ?><div class="alert"><?= e($error) ?></div><?php endif; ?>
    <form method="post" id="booking-form">
        <input type="hidden" name="event_id" value="<?= (int)$event["event_id"] ?>">
        <label>Number of tickets
            <input type="number" name="number_of_tickets" id="ticket-count" data-price="<?= (float)$event["ticket_price"] ?>" min="1" max="<?= (int)$event["available_slots"] ?>" value="1" required>
        </label>
        <p class="total-line">Estimated total: <strong id="booking-total">KSh <?= number_format((float)$event["ticket_price"], 2) ?></strong></p>
        <button class="button full-width" type="submit" <?= (int)$event["available_slots"] < 1 ? "disabled" : "" ?>>Confirm booking</button>
    </form>
    <p class="small muted">This class-project version records a booking; it does not process online payments.</p>
</div>
<?php endif; ?>
<?php include "footer.php"; ?>
