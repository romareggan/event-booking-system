<?php
require_once "config.php";
require_login();
$page_title = "My bookings";
if (isset($_GET["cancel"]) && ctype_digit($_GET["cancel"])) {
    $booking_id = (int)$_GET["cancel"];
    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("SELECT event_id, number_of_tickets FROM bookings WHERE booking_id = ? AND user_id = ? AND booking_status = 'Confirmed' FOR UPDATE");
        $stmt->execute([$booking_id, $_SESSION["user_id"]]);
        $booking = $stmt->fetch();
        if ($booking) {
            $pdo->prepare("UPDATE bookings SET booking_status = 'Cancelled' WHERE booking_id = ?")->execute([$booking_id]);
            $pdo->prepare("UPDATE events SET available_slots = available_slots + ? WHERE event_id = ?")->execute([$booking["number_of_tickets"], $booking["event_id"]]);
        }
        $pdo->commit();
    } catch (Throwable $ex) {
        if ($pdo->inTransaction()) $pdo->rollBack();
    }
    header("Location: my_bookings.php");
    exit;
}
$stmt = $pdo->prepare("SELECT b.*, e.event_name, e.event_date, e.event_time, e.location, e.ticket_price
                       FROM bookings b JOIN events e ON b.event_id = e.event_id
                       WHERE b.user_id = ? ORDER BY b.booking_date DESC");
$stmt->execute([$_SESSION["user_id"]]);
$bookings = $stmt->fetchAll();
include "header.php";
?>
<div class="page-heading"><p class="eyebrow">YOUR ACCOUNT</p><h1>My bookings</h1><p>Review your event reservations.</p></div>
<?php if (isset($_GET["booked"])): ?><div class="success">Your booking was successful.</div><?php endif; ?>
<?php if (!$bookings): ?>
    <div class="notice">You have not booked any events yet. <a href="events.php">Browse events</a>.</div>
<?php else: ?>
<div class="table-wrap"><table>
    <thead><tr><th>Event</th><th>Date</th><th>Tickets</th><th>Total</th><th>Status</th><th>Action</th></tr></thead>
    <tbody>
    <?php foreach ($bookings as $b): ?>
    <tr>
        <td><?= e($b["event_name"]) ?></td>
        <td><?= e($b["event_date"]) ?></td>
        <td><?= (int)$b["number_of_tickets"] ?></td>
        <td>KSh <?= number_format((float)$b["ticket_price"] * (int)$b["number_of_tickets"], 2) ?></td>
        <td><span class="status"><?= e($b["booking_status"]) ?></span></td>
        <td>
        <?php if ($b["booking_status"] === "Confirmed" && $b["event_date"] >= date("Y-m-d")): ?>
            <a class="text-danger" href="my_bookings.php?cancel=<?= (int)$b["booking_id"] ?>" data-confirm="Cancel this booking?">Cancel</a>
        <?php else: ?>—<?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table></div>
<?php endif; ?>
<?php include "footer.php"; ?>
