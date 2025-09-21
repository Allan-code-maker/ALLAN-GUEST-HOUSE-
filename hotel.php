<?php
// Sample menu items
$menu = [
    ['name' => 'Chicken Curry', 'price' => 500],
    ['name' => 'Beef Stew', 'price' => 450],
    ['name' => 'Vegetable Rice', 'price' => 350],
    ['name' => 'Fish Fillet', 'price' => 600],
    ['name' => 'Chapati', 'price' => 50],
];

// Handle booking submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['menu_item'])) {
    $item = $_POST['menu_item'];
    $message = "You have booked: " . htmlspecialchars($item);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Hotel Menu & Booking</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .menu { max-width: 400px; margin: auto; }
        .menu-item { border-bottom: 1px solid #ccc; padding: 10px 0; }
        .book-btn { background: #28a745; color: #fff; border: none; padding: 5px 10px; cursor: pointer; }
        .message { background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="menu">
        <h2>Hotel Menu</h2>
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php foreach ($menu as $item): ?>
            <div class="menu-item">
                <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                <span> - Ksh <?php echo number_format($item['price']); ?></span>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="menu_item" value="<?php echo htmlspecialchars($item['name']); ?>">
                    <button type="submit" class="book-btn">Book</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
