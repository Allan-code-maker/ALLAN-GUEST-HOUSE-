<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(APP_NAME) ?> — Malaba</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include __DIR__ . '/navbar.php'; ?>

<main class="page-main">

    <!-- Hero / Slideshow -->
    <div class="hero" id="hero">
        <img id="hero-img" src="r1.jpg.png" alt="Allan Guest House">
        <div class="hero-overlay">
            <h1>Welcome to <?= htmlspecialchars(APP_NAME) ?></h1>
            <p>Experience comfort and warm hospitality in the heart of Malaba.</p>
            <a href="book.php" class="btn btn-primary">Book a Room</a>
        </div>
    </div>

    <!-- Quick Info Cards -->
    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:20px; margin-bottom:40px;">
        <div class="card" style="text-align:center; border-top:4px solid #32CDD5;">
            <div style="font-size:2rem; margin-bottom:8px;">&#127968;</div>
            <h3 style="margin-bottom:6px;">Comfortable Rooms</h3>
            <p style="color:#666; font-size:0.9rem;">Single, Double, Suite &amp; Deluxe rooms available for every budget.</p>
            <a href="rooms.php" class="btn btn-primary" style="margin-top:12px;">View Rooms</a>
        </div>
        <div class="card" style="text-align:center; border-top:4px solid #32CDD5;">
            <div style="font-size:2rem; margin-bottom:8px;">&#127869;</div>
            <h3 style="margin-bottom:6px;">Restaurant &amp; Bar</h3>
            <p style="color:#666; font-size:0.9rem;">Enjoy freshly prepared local and international dishes right at your table.</p>
            <a href="hotel.php" class="btn btn-primary" style="margin-top:12px;">View Menu</a>
        </div>
        <div class="card" style="text-align:center; border-top:4px solid #32CDD5;">
            <div style="font-size:2rem; margin-bottom:8px;">&#128241;</div>
            <h3 style="margin-bottom:6px;">Easy Booking</h3>
            <p style="color:#666; font-size:0.9rem;">Book online and pay securely via M-Pesa. Confirmation in minutes.</p>
            <a href="book.php" class="btn btn-primary" style="margin-top:12px;">Book Now</a>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="card" id="contact">
        <h2 class="section-title">Contact Us</h2>
        <div class="contact-section">
            <div>
                <p style="margin-bottom:10px;"><strong>&#128222; Phone:</strong> <a href="tel:+254703630174">+254 703 630 174</a></p>
                <p style="margin-bottom:10px;"><strong>&#128231; Email:</strong> <a href="mailto:<?= htmlspecialchars(APP_EMAIL) ?>"><?= htmlspecialchars(APP_EMAIL) ?></a></p>
                <p><strong>&#128205; Location:</strong> Malaba Town, Kenya</p>
            </div>
            <div>
                <p style="color:#666; font-size:0.95rem; line-height:1.7;">
                    We are open <strong>24 hours a day, 7 days a week</strong>. Whether you need a room for a night or a week, we have the perfect option for you. Reach out and we will get back to you promptly.
                </p>
            </div>
        </div>
    </div>

</main>

<?php include __DIR__ . '/footer.php'; ?>

<script>
(function () {
    const images = [
        'r1.jpg.png','r2.png','r3.png','r4.png',
        'd1.png','d2.png','d3.png','d4.png',
        'p1.png','p3.png'
    ];
    let idx = 0;
    const img = document.getElementById('hero-img');
    if (!img) return;
    setInterval(function () {
        idx = (idx + 1) % images.length;
        img.style.opacity = '0';
        setTimeout(function () {
            img.src = images[idx];
            img.style.opacity = '1';
        }, 400);
    }, 4000);
})();
</script>
</body>
</html>
