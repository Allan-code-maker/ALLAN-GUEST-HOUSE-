<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

$conn  = get_db();
$rooms = $conn->query("SELECT number, type, price, description FROM rooms WHERE available = 1 ORDER BY price ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Rooms — <?= htmlspecialchars(APP_NAME) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include __DIR__ . '/navbar.php'; ?>

<main class="page-main">
    <h2 class="section-title" style="text-align:center; margin-bottom:32px;">Available Rooms</h2>

    <?php if ($rooms && $rooms->num_rows > 0): ?>
        <div class="rooms-grid">
            <?php while ($room = $rooms->fetch_assoc()): ?>
                <div class="room-card">
                    <div class="room-badge"><?= htmlspecialchars($room['type']) ?></div>
                    <h3>Room <?= htmlspecialchars($room['number']) ?></h3>
                    <p class="room-price">Ksh <?= number_format($room['price'], 2) ?> <span>/night</span></p>
                    <p class="room-desc"><?= htmlspecialchars($room['description']) ?></p>
                    <a href="book.php?room=<?= urlencode($room['type']) ?>" class="btn btn-primary">Book Now</a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info" style="max-width:500px; margin:auto;">No rooms are currently available. Please check back soon.</div>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
