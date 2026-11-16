<?php
require_once "config.php";
require_admin();
$page_title = "Manage bookings";
$stmt = $pdo->query("SELECT b.*, u.full_name, u.email, e.event_name, e.event_date, e.ticket_price
                     FROM bookings b
                     JOIN users u ON b.user_id = u.user_id
                     JOIN events e ON b.event_id = e.event_id
                     ORDER BY b.booking_date DESC");
$bookings = $stmt->fetchAll();
include "header.php";
?>
<div class="page-heading"><p class="eyebrow">ADMINISTRATION</p><h1>All bookings</h1><p>View reservations made by registered users.</p></div>
<?php if (!$bookings): ?>
    <div class="notice">No bookings have been made yet.</div>
<?php else: ?>
<div class="table-wrap"><table>
<thead><tr><th>Customer</th><th>Email</th><th>Event</th><th>Tickets</th><th>Total</th><th>Status</th><th>Booked on</th></tr></thead>
<tbody>
<?php foreach ($bookings as $b): ?>
<tr>
    <td><?= e($b["full_name"]) ?></td><td><?= e($b["email"]) ?></td><td><?= e($b["event_name"]) ?></td>
    <td><?= (int)$b["number_of_tickets"] ?></td>
    <td>KSh <?= number_format((float)$b["ticket_price"] * (int)$b["number_of_tickets"], 2) ?></td>
    <td><?= e($b["booking_status"]) ?></td><td><?= e($b["booking_date"]) ?></td>
</tr>
<?php endforeach; ?>
</tbody></table></div>
<?php endif; ?>
<?php include "footer.php"; ?>
