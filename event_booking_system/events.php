<?php
require_once "config.php";
$page_title = "Events";
$search = trim($_GET["search"] ?? "");
$sql = "SELECT * FROM events WHERE event_date >= CURDATE()";
$params = [];

if ($search !== "") {
    $sql .= " AND (event_name LIKE ? OR location LIKE ?)";
    $term = "%$search%";
    $params = [$term, $term];
}

$stmt = $pdo->prepare("$sql ORDER BY event_date");
$stmt->execute($params);
$events = $stmt->fetchAll();
include "header.php";
?>
<div class="page-heading"><p class="eyebrow">DISCOVER</p><h1>Available events</h1><p>Search for an event by name or location.</p></div>
<form class="search-form" method="get">
    <input type="search" name="search" placeholder="Search events or locations..." value="<?= e($search) ?>">
    <button class="button" type="submit">Search</button>
    <a class="button button-light" href="events.php">Clear</a>
</form>
<?php if (!$events): ?>
    <div class="notice">No matching upcoming events found.</div>
<?php else: ?>
<div class="grid">
    <?php foreach ($events as $event): ?>
    <article class="card">
        <span class="tag">Event</span>
        <h3><?= e($event["event_name"]) ?></h3>
        <p><?= e(mb_strimwidth($event["description"], 0, 130, "...")) ?></p>
        <p class="meta"><?= e($event["event_date"]) ?> · <?= e(substr($event["event_time"], 0, 5)) ?></p>
        <p class="meta"><?= e($event["location"]) ?></p>
        <div class="card-bottom">
            <strong>KSh <?= number_format((float)$event["ticket_price"], 2) ?></strong>
            <a class="button button-small" href="event_details.php?id=<?= (int)$event["event_id"] ?>">View details</a>
        </div>
    </article>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<?php include "footer.php"; ?>
