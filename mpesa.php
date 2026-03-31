<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

$booking = null;
$booking_id = intval($_GET['booking_id'] ?? 0);

if ($booking_id > 0) {
    $conn = get_db();
    $stmt = $conn->prepare(
        'SELECT name, email, phone, room, checkin, checkout, status, created_at
         FROM bookings WHERE id = ? LIMIT 1'
    );
    $stmt->bind_param('i', $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();
    $stmt->close();
}

$nights = 0;
$price_per_night = 0;
$total = 0;
if ($booking) {
    $nights = (int) round((strtotime($booking['checkout']) - strtotime($booking['checkin'])) / 86400);
    $conn  = get_db();
    $stmt2 = $conn->prepare('SELECT price FROM rooms WHERE type = ? LIMIT 1');
    $stmt2->bind_param('s', $booking['room']);
    $stmt2->execute();
    $r2 = $stmt2->get_result()->fetch_assoc();
    $stmt2->close();
    $price_per_night = $r2 ? (float)$r2['price'] : 0;
    $total = $nights * $price_per_night;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include __DIR__ . '/navbar.php'; ?>
<main class="page-main">
    <div class="card" style="max-width:540px; margin:40px auto;">
        <?php if ($booking): ?>
            <div class="confirmation-header">
                <span class="checkmark">&#10003;</span>
                <h2>Booking Received!</h2>
                <p>Thank you, <strong><?= htmlspecialchars($booking['name']) ?></strong>. Your booking is <strong>pending confirmation</strong>.</p>
            </div>
            <table class="detail-table">
                <tr><th>Booking Reference</th><td>#<?= $booking_id ?></td></tr>
                <tr><th>Room Type</th><td><?= htmlspecialchars($booking['room']) ?></td></tr>
                <tr><th>Check-in</th><td><?= htmlspecialchars($booking['checkin']) ?></td></tr>
                <tr><th>Check-out</th><td><?= htmlspecialchars($booking['checkout']) ?></td></tr>
                <tr><th>Nights</th><td><?= $nights ?></td></tr>
                <?php if ($total > 0): ?>
                <tr><th>Price per Night</th><td>Ksh <?= number_format($price_per_night, 2) ?></td></tr>
                <tr><th>Total Amount</th><td><strong>Ksh <?= number_format($total, 2) ?></strong></td></tr>
                <?php endif; ?>
            </table>
            <div class="mpesa-instructions">
                <h3>&#128241; Pay via M-Pesa</h3>
                <p>To complete your booking, please send the total amount via M-Pesa:</p>
                <ol>
                    <li>Go to <strong>M-Pesa</strong> on your phone.</li>
                    <li>Select <strong>Lipa na M-Pesa &rarr; Pay Bill</strong>.</li>
                    <li>Enter Business Number: <strong>174379</strong></li>
                    <li>Enter Account Number: <strong>Booking #<?= $booking_id ?></strong></li>
                    <li>Enter Amount: <strong>Ksh <?= number_format($total, 2) ?></strong></li>
                    <li>Enter your M-Pesa PIN and confirm.</li>
                </ol>
                <p>A confirmation email will be sent to <strong><?= htmlspecialchars($booking['email']) ?></strong> once your payment is verified.</p>
            </div>
            <div style="text-align:center; margin-top:24px;">
                <a href="index.php" class="btn btn-secondary">Back to Home</a>
            </div>
        <?php else: ?>
            <div class="alert alert-error">Booking not found. Please <a href="book.php">try again</a>.</div>
        <?php endif; ?>
    </div>
</main>
<?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
