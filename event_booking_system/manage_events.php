<?php
require_once "config.php";
require_admin();
$page_title = "Manage events";
$error = "";
$edit_event = null;

if (isset($_GET["edit"]) && ctype_digit($_GET["edit"])) {
    $stmt = $pdo->prepare("SELECT * FROM events WHERE event_id = ?");
    $stmt->execute([(int)$_GET["edit"]]);
    $edit_event = $stmt->fetch();
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";
    if ($action === "delete") {
        $id = (int)($_POST["event_id"] ?? 0);
        $check = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE event_id = ?");
        $check->execute([$id]);
        if ((int)$check->fetchColumn() > 0) {
            $error = "This event has booking records and cannot be deleted. You can leave it in the system instead.";
        } else {
            $pdo->prepare("DELETE FROM events WHERE event_id = ?")->execute([$id]);
            header("Location: manage_events.php?deleted=1"); exit;
        }
    } else {
        $id = (int)($_POST["event_id"] ?? 0);
        $name = trim($_POST["event_name"] ?? "");
        $description = trim($_POST["description"] ?? "");
        $date = $_POST["event_date"] ?? "";
        $time = $_POST["event_time"] ?? "";
        $location = trim($_POST["location"] ?? "");
        $price = filter_input(INPUT_POST, "ticket_price", FILTER_VALIDATE_FLOAT);
        $slots = filter_input(INPUT_POST, "available_slots", FILTER_VALIDATE_INT);

        if ($name === "" || $description === "" || $date === "" || $time === "" || $location === "" || $price === false || $price < 0 || $slots === false || $slots < 0) {
            $error = "Please complete all fields with valid values.";
        } else {
            $params = [$name, $description, $date, $time, $location, $price, $slots];
            if ($action === "update") {
                $params[] = $id;
                $pdo->prepare("UPDATE events SET event_name=?, description=?, event_date=?, event_time=?, location=?, ticket_price=?, available_slots=? WHERE event_id=?")->execute($params);
            } else {
                $pdo->prepare("INSERT INTO events (event_name, description, event_date, event_time, location, ticket_price, available_slots) VALUES (?, ?, ?, ?, ?, ?, ?)")->execute($params);
            }
            header("Location: manage_events.php?saved=1");
            exit;
        }
    }
}
$events = $pdo->query("SELECT * FROM events ORDER BY event_date DESC")->fetchAll();
include "header.php";
?>
<div class="page-heading"><p class="eyebrow">ADMINISTRATION</p><h1>Manage events</h1></div>
<?php if ($error): ?><div class="alert"><?= e($error) ?></div><?php endif; ?>
<?php if (isset($_GET["saved"])): ?><div class="success">Event saved successfully.</div><?php endif; ?>
<?php if (isset($_GET["deleted"])): ?><div class="success">Event deleted.</div><?php endif; ?>

<div class="form-card wide-card">
    <h2><?= $edit_event ? "Edit event" : "Add a new event" ?></h2>
    <form method="post">
        <input type="hidden" name="event_id" value="<?= (int)($edit_event["event_id"] ?? 0) ?>">
        <input type="hidden" name="action" value="<?= $edit_event ? "update" : "add" ?>">
        <label>Event name<input name="event_name" required value="<?= e($edit_event["event_name"] ?? "") ?>"></label>
        <label>Description<textarea name="description" rows="3" required><?= e($edit_event["description"] ?? "") ?></textarea></label>
        <div class="two-col">
            <label>Date<input type="date" name="event_date" required value="<?= e($edit_event["event_date"] ?? "") ?>"></label>
            <label>Time<input type="time" name="event_time" required value="<?= e(substr($edit_event["event_time"] ?? "", 0, 5)) ?>"></label>
        </div>
        <label>Location<input name="location" required value="<?= e($edit_event["location"] ?? "") ?>"></label>
        <div class="two-col">
            <label>Ticket price (KSh)<input type="number" name="ticket_price" min="0" step="0.01" required value="<?= e($edit_event["ticket_price"] ?? "0") ?>"></label>
            <label>Available spaces<input type="number" name="available_slots" min="0" required value="<?= e($edit_event["available_slots"] ?? "0") ?>"></label>
        </div>
        <button class="button" type="submit"><?= $edit_event ? "Update event" : "Add event" ?></button>
        <?php if ($edit_event): ?><a class="button button-light" href="manage_events.php">Cancel edit</a><?php endif; ?>
    </form>
</div>

<h2 class="list-title">All events</h2>
<div class="table-wrap"><table>
<thead><tr><th>Event</th><th>Date</th><th>Spaces</th><th>Actions</th></tr></thead>
<tbody>
<?php foreach ($events as $event): ?>
<tr>
    <td><?= e($event["event_name"]) ?></td><td><?= e($event["event_date"]) ?></td><td><?= (int)$event["available_slots"] ?></td>
    <td class="actions">
        <a href="manage_events.php?edit=<?= (int)$event["event_id"] ?>">Edit</a>
        <form method="post" class="inline-form" onsubmit="return confirm('Delete this event?')">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="event_id" value="<?= (int)$event["event_id"] ?>">
            <button class="link-button text-danger" type="submit">Delete</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
</tbody></table></div>
<?php include "footer.php"; ?>
