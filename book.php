<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

$error   = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']     ?? '');
    $email    = trim($_POST['email']    ?? '');
    $phone    = trim($_POST['phone']    ?? '');
    $room     = trim($_POST['room']     ?? '');
    $checkin  = trim($_POST['checkin']  ?? '');
    $checkout = trim($_POST['checkout'] ?? '');

    // Basic validation
    if (!$name || !$email || !$phone || !$room || !$checkin || !$checkout) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif ($checkin >= $checkout) {
        $error = 'Check-out date must be after check-in date.';
    } elseif (strtotime($checkin) < strtotime('today')) {
        $error = 'Check-in date cannot be in the past.';
    } else {
        $conn = get_db();
        $stmt = $conn->prepare(
            'INSERT INTO bookings (name, email, phone, room, checkin, checkout, status)
             VALUES (?, ?, ?, ?, ?, ?, "pending")'
        );
        $stmt->bind_param('ssssss', $name, $email, $phone, $room, $checkin, $checkout);

        if ($stmt->execute()) {
            $booking_id = $stmt->insert_id;
            $stmt->close();

            // Optionally send a confirmation email (requires mail server)
            $subject = 'Booking Confirmation — ' . APP_NAME;
            $body    = "Dear $name,\n\nYour booking has been received!\n\n"
                     . "Room: $room\nCheck-in: $checkin\nCheck-out: $checkout\n\n"
                     . "We will confirm your reservation shortly.\n\nRegards,\n" . APP_NAME;
            $headers = 'From: ' . APP_EMAIL . "\r\nReply-To: " . APP_EMAIL;
            @mail($email, $subject, $body, $headers);

            // Redirect to payment/confirmation page
            header('Location: mpesa.php?booking_id=' . $booking_id);
            exit;
        } else {
            $error = 'Booking failed. Please try again.';
            $stmt->close();
        }
    }
}

// Fetch available rooms for the dropdown
$conn  = get_db();
$rooms = $conn->query("SELECT type, price FROM rooms WHERE available = 1 ORDER BY price ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Room — <?= htmlspecialchars(APP_NAME) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include __DIR__ . '/navbar.php'; ?>

<main class="page-main">
    <div class="card" style="max-width:480px; margin:40px auto;">
        <h2 class="section-title">Book a Room</h2>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" class="form-control"
                       placeholder="Your full name" required
                       value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control"
                       placeholder="Your email" required
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" class="form-control"
                       placeholder="e.g. +254700000000" required
                       pattern="[0-9\-\+\s]{7,15}"
                       value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="room">Room Type</label>
                <select id="room" name="room" class="form-control" required>
                    <option value="">— Select a room —</option>
                    <?php if ($rooms && $rooms->num_rows > 0):
                        while ($r = $rooms->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($r['type']) ?>"
                            <?= (($_POST['room'] ?? '') === $r['type']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($r['type']) ?> — Ksh <?= number_format($r['price'], 2) ?>/night
                        </option>
                    <?php endwhile; else: ?>
                        <option value="Single">Single Room</option>
                        <option value="Double">Double Room</option>
                        <option value="Suite">Suite</option>
                        <option value="Deluxe">Deluxe Room</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="checkin">Check-in Date</label>
                <input type="date" id="checkin" name="checkin" class="form-control" required
                       min="<?= date('Y-m-d') ?>"
                       value="<?= htmlspecialchars($_POST['checkin'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="checkout">Check-out Date</label>
                <input type="date" id="checkout" name="checkout" class="form-control" required
                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                       value="<?= htmlspecialchars($_POST['checkout'] ?? '') ?>">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Book Now</button>
        </form>
    </div>
</main>

<?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
