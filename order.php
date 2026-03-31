<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
require_login('admin');

$conn = get_db();

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = intval($_POST['order_id']);
    $status   = in_array($_POST['status'], ['pending','prepared','delivered']) ? $_POST['status'] : 'pending';
    $stmt = $conn->prepare('UPDATE orders SET status = ? WHERE id = ?');
    $stmt->bind_param('si', $status, $order_id);
    $stmt->execute();
    $stmt->close();
    header('Location: order.php');
    exit;
}

$orders = $conn->query(
    'SELECT o.id, o.guest_name, m.name AS item_name, m.price, o.quantity,
            (m.price * o.quantity) AS subtotal, o.status, o.created_at
     FROM orders o
     JOIN menu_items m ON o.item_id = m.id
     ORDER BY o.created_at DESC'
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders — <?= htmlspecialchars(APP_NAME) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include __DIR__ . '/navbar.php'; ?>
<main class="page-main">
    <div class="card">
        <h2 class="section-title">Restaurant Orders</h2>
        <?php if ($orders && $orders->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr><th>#</th><th>Guest</th><th>Item</th><th>Qty</th>
                            <th>Subtotal</th><th>Status</th><th>Ordered At</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php while ($o = $orders->fetch_assoc()): ?>
                            <tr>
                                <td><?= $o['id'] ?></td>
                                <td><?= htmlspecialchars($o['guest_name']) ?></td>
                                <td><?= htmlspecialchars($o['item_name']) ?></td>
                                <td><?= $o['quantity'] ?></td>
                                <td>Ksh <?= number_format($o['subtotal'], 2) ?></td>
                                <td><span class="badge badge-<?= $o['status'] ?>"><?= ucfirst($o['status']) ?></span></td>
                                <td><?= htmlspecialchars($o['created_at']) ?></td>
                                <td>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                                        <select name="status" class="form-control" style="width:auto;display:inline;">
                                            <option value="pending"   <?= $o['status']==='pending'   ? 'selected':'' ?>>Pending</option>
                                            <option value="prepared"  <?= $o['status']==='prepared'  ? 'selected':'' ?>>Prepared</option>
                                            <option value="delivered" <?= $o['status']==='delivered' ? 'selected':'' ?>>Delivered</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No orders yet.</div>
        <?php endif; ?>
        <div style="margin-top:16px;"><a href="dashboard.php" class="btn btn-secondary">&larr; Back to Dashboard</a></div>
    </div>
</main>
<?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
