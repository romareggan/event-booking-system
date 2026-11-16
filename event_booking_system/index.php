<?php
require_once "config.php";
$page_title = "Home";
$stmt = $pdo->query("SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC LIMIT 6");
$events = $stmt->fetchAll();
include "header.php";
?>
<section class="hero">
    <div>
        <p class="eyebrow">PLAN • BOOK • ATTEND</p>
        <h1>Find events worth attending.</h1>
        <p>Explore upcoming events and reserve your place in a few simple steps.</p>
        <a class="button" href="events.php">Explore Events</a>
    </div>
</section>

<div class="section-heading">
    <div><p class="eyebrow">WHAT'S HAPPENING</p><h2>Upcoming events</h2></div>
    <a href="events.php">View all events →</a>
</div>
 
<?php if (!$events): ?>
    <div class="notice">There are no upcoming events at the moment.</div>
<?php else: ?>
    <div class="grid">
        <?php foreach ($events as $event): ?>
            <article class="card">
                <div class="card-top"><span class="tag">Upcoming event</span></div>
                <h3><?= e($event["event_name"]) ?></h3>
                <p><?= e(mb_strimwidth($event["description"], 0, 110, "...")) ?></p>
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
