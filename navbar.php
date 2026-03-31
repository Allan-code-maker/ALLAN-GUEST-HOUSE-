<?php
// navbar.php — shared navigation bar
// Requires config.php and auth.php to have been included already
$current = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar">
    <a href="index.php" class="navbar-brand">&#127968; <?= htmlspecialchars(APP_NAME) ?></a>
    <ul class="navbar-links">
        <li><a href="index.php"  <?= $current === 'index.php'  ? 'class="active"' : '' ?>>Home</a></li>
        <li><a href="rooms.php"  <?= $current === 'rooms.php'  ? 'class="active"' : '' ?>>Rooms</a></li>
        <li><a href="hotel.php"  <?= $current === 'hotel.php'  ? 'class="active"' : '' ?>>Restaurant</a></li>
        <li><a href="book.php"   <?= $current === 'book.php'   ? 'class="active"' : '' ?>>Book Now</a></li>
        <?php if (is_logged_in()): ?>
            <li><a href="dashboard.php" <?= $current === 'dashboard.php' ? 'class="active"' : '' ?>>Dashboard</a></li>
            <li><a href="logout.php" class="btn-logout">Logout</a></li>
        <?php else: ?>
            <li><a href="admin.php" <?= $current === 'admin.php' ? 'class="active"' : '' ?>>Login</a></li>
        <?php endif; ?>
    </ul>
</nav>
