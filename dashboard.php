<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
require_login(); // Any logged-in user can view; admin-only sections are gated below

$conn = get_db();
$is_admin = (current_role() === 'admin');

// Handle booking status update (admin only)
if ($is_admin && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['status'])) {
    $bid    = intval($_POST['booking_id']);
    $bstat  = in_array($_POST['status'], ['pending','confirmed','cancelled']) ? $_POST['status'] : 'pending';
    $stmt   = $conn->prepare('UPDATE bookings SET status = ? WHERE id = ?');
    $stmt->bind_param('si', $bstat, $bid);
    $stmt->execute();
    $stmt->close();
    header('Location: dashboard.php');
    exit;
}

// Handle room update (admin only)
if ($is_admin && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['room_id'])) {
    $rid   = intval($_POST['room_id']);
    $rtype = trim($_POST['room_type'] ?? '');
    $rprice= floatval($_POST['room_price'] ?? 0);
    $rdesc = trim($_POST['room_desc'] ?? '');
    $ravail= isset($_POST['room_available']) ? 1 : 0;
    if ($rtype && $rprice > 0) {
        $stmt = $conn->prepare('UPDATE rooms SET type=?, price=?, description=?, available=? WHERE id=?');
        $stmt->bind_param('sdsii', $rtype, $rprice, $rdesc, $ravail, $rid);
        $stmt->execute();
        $stmt->close();
    }
    header('Location: dashboard.php');
    exit;
}

// Fetch stats
$total_bookings  = $conn->query("SELECT COUNT(*) AS c FROM bookings")->fetch_assoc()['c'];
$pending_bookings= $conn->query("SELECT COUNT(*) AS c FROM bookings WHERE status='pending'")->fetch_assoc()['c'];
$total_users     = $conn->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc()['c'];
$total_orders    = $conn->query("SELECT COUNT(*) AS c FROM orders")->fetch_assoc()['c'];

// Fetch bookings
$bookings = $conn->query(
    "SELECT id, name, email, phone, room, checkin, checkout, status, created_at
     FROM bookings ORDER BY created_at DESC LIMIT 50"
);

// Fetch rooms (admin)
$rooms = $is_admin ? $conn->query("SELECT id, number, type, price, description, available FROM rooms ORDER BY price ASC") : null;

// Fetch users (admin)
$users = $is_admin ? $conn->query("SELECT id, username, email, created_at FROM users ORDER BY created_at DESC") : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — <?= htmlspecialchars(APP_NAME) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include __DIR__ . '/navbar.php'; ?>

<main class="page-main">
    <div class="dashboard-header">
        <h1>Welcome, <?= htmlspecialchars(current_username()) ?> <span class="role-badge"><?= ucfirst(current_role()) ?></span></h1>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number"><?= $total_bookings ?></div>
            <div class="stat-label">Total Bookings</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= $pending_bookings ?></div>
            <div class="stat-label">Pending Bookings</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= $total_users ?></div>
            <div class="stat-label">Registered Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= $total_orders ?></div>
            <div class="stat-label">Restaurant Orders</div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="card section">
        <h2 class="section-title">Room Bookings</h2>
        <?php if ($bookings && $bookings->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th><th>Name</th><th>Email</th><th>Phone</th>
                            <th>Room</th><th>Check-in</th><th>Check-out</th>
                            <th>Status</th>
                            <?php if ($is_admin): ?><th>Action</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($b = $bookings->fetch_assoc()): ?>
                            <tr>
                                <td><?= $b['id'] ?></td>
                                <td><?= htmlspecialchars($b['name']) ?></td>
                                <td><?= htmlspecialchars($b['email']) ?></td>
                                <td><?= htmlspecialchars($b['phone']) ?></td>
                                <td><?= htmlspecialchars($b['room']) ?></td>
                                <td><?= htmlspecialchars($b['checkin']) ?></td>
                                <td><?= htmlspecialchars($b['checkout']) ?></td>
                                <td><span class="badge badge-<?= $b['status'] ?>"><?= ucfirst($b['status']) ?></span></td>
                                <?php if ($is_admin): ?>
                                <td>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
                                        <select name="status" class="form-control" style="width:auto;display:inline;">
                                            <option value="pending"   <?= $b['status']==='pending'   ?'selected':'' ?>>Pending</option>
                                            <option value="confirmed" <?= $b['status']==='confirmed' ?'selected':'' ?>>Confirmed</option>
                                            <option value="cancelled" <?= $b['status']==='cancelled' ?'selected':'' ?>>Cancelled</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                    </form>
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No bookings yet.</div>
        <?php endif; ?>
    </div>

    <?php if ($is_admin): ?>
    <!-- Manage Rooms -->
    <div class="card section">
        <h2 class="section-title">Manage Rooms</h2>
        <?php if ($rooms && $rooms->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr><th>#</th><th>Room No.</th><th>Type</th><th>Price (Ksh)</th><th>Description</th><th>Available</th><th>Save</th></tr>
                    </thead>
                    <tbody>
                        <?php while ($r = $rooms->fetch_assoc()): ?>
                            <tr>
                                <form method="post">
                                    <input type="hidden" name="room_id" value="<?= $r['id'] ?>">
                                    <td><?= $r['id'] ?></td>
                                    <td><?= htmlspecialchars($r['number']) ?></td>
                                    <td><input type="text" name="room_type" value="<?= htmlspecialchars($r['type']) ?>" class="form-control" style="width:100px;"></td>
                                    <td><input type="number" name="room_price" value="<?= $r['price'] ?>" class="form-control" style="width:100px;" step="0.01" min="0"></td>
                                    <td><input type="text" name="room_desc" value="<?= htmlspecialchars($r['description']) ?>" class="form-control"></td>
                                    <td style="text-align:center;"><input type="checkbox" name="room_available" <?= $r['available'] ? 'checked' : '' ?>></td>
                                    <td><button type="submit" class="btn btn-sm btn-primary">Save</button></td>
                                </form>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No rooms found.</div>
        <?php endif; ?>
    </div>

    <!-- Registered Users -->
    <div class="card section">
        <h2 class="section-title">Registered Users</h2>
        <?php if ($users && $users->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr><th>#</th><th>Username</th><th>Email</th><th>Registered At</th></tr>
                    </thead>
                    <tbody>
                        <?php while ($u = $users->fetch_assoc()): ?>
                            <tr>
                                <td><?= $u['id'] ?></td>
                                <td><?= htmlspecialchars($u['username']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td><?= htmlspecialchars($u['created_at']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No users registered yet.</div>
        <?php endif; ?>
    </div>

    <!-- Quick Links -->
    <div class="card section">
        <h2 class="section-title">Quick Actions</h2>
        <div style="display:flex; gap:12px; flex-wrap:wrap;">
            <a href="order.php" class="btn btn-primary">View Restaurant Orders</a>
            <a href="rooms.php" class="btn btn-secondary">View Rooms Page</a>
            <a href="hotel.php" class="btn btn-secondary">View Menu Page</a>
        </div>
    </div>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
