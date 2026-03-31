<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $guest_name = trim($_POST['guest_name'] ?? '');
    $item_id    = intval($_POST['item_id'] ?? 0);
    $quantity   = intval($_POST['quantity'] ?? 1);

    if (!$guest_name || $item_id <= 0 || $quantity < 1) {
        $error = 'Please fill in all fields correctly.';
    } else {
        $conn = get_db();
        $stmt = $conn->prepare(
            'INSERT INTO orders (guest_name, item_id, quantity, status) VALUES (?, ?, ?, "pending")'
        );
        $stmt->bind_param('sii', $guest_name, $item_id, $quantity);
        if ($stmt->execute()) {
            $success = "Your order has been placed! We'll bring it to you shortly.";
        } else {
            $error = 'Order failed. Please try again.';
        }
        $stmt->close();
    }
}

// Fetch menu grouped by category
$conn  = get_db();
$items = $conn->query(
    "SELECT id, name, price, category FROM menu_items WHERE available = 1 ORDER BY category, name ASC"
);

$menu = [];
if ($items) {
    while ($row = $items->fetch_assoc()) {
        $menu[$row['category']][] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Menu — <?= htmlspecialchars(APP_NAME) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include __DIR__ . '/navbar.php'; ?>

<main class="page-main">
    <h2 class="section-title" style="text-align:center; margin-bottom:8px;">Restaurant Menu</h2>
    <p style="text-align:center; color:#666; margin-bottom:32px;">Fresh meals prepared daily for our guests.</p>

    <?php if ($error): ?>
        <div class="alert alert-error" style="max-width:500px; margin:0 auto 20px;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success" style="max-width:500px; margin:0 auto 20px;"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if (!empty($menu)): ?>
        <?php foreach ($menu as $category => $items): ?>
            <h3 class="menu-category"><?= htmlspecialchars($category) ?></h3>
            <div class="menu-grid">
                <?php foreach ($items as $item): ?>
                    <div class="menu-card">
                        <div class="menu-info">
                            <strong><?= htmlspecialchars($item['name']) ?></strong>
                            <span class="menu-price">Ksh <?= number_format($item['price'], 2) ?></span>
                        </div>
                        <form method="post" class="order-form">
                            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                            <input type="text" name="guest_name" class="form-control"
                                   placeholder="Your name" required style="margin-bottom:6px;">
                            <div style="display:flex; gap:8px; align-items:center;">
                                <input type="number" name="quantity" value="1" min="1" max="10"
                                       class="form-control" style="width:70px;">
                                <button type="submit" class="btn btn-primary" style="flex:1;">Order</button>
                            </div>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info" style="max-width:500px; margin:auto;">Menu is currently unavailable. Please check back soon.</div>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
