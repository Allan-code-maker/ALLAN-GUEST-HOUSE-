<?php
$admin_email = "emoruallan@gmail.com"; // Change to your admin email
$success = false;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $phone = htmlspecialchars($_POST["phone"]);
    $room = htmlspecialchars($_POST["room"]);
    $checkin = htmlspecialchars($_POST["checkin"]);
    $checkout = htmlspecialchars($_POST["checkout"]);
    $subject = "New Room Booking";
    $message = "Name: $name\nEmail: $email\nPhone: $phone\nRoom Type: $room\nCheck-in: $checkin\nCheck-out: $checkout";
   $headers = "From: emoruallan@gmail.com\r\nReply-To: $email\r\n";
    if (mail($admin_email, $subject, $message, $headers)) {
        header("Location: mpesa.php?success=1");
        exit;
    }
}
?>
<div id="booking" style="background:#f0f8ff; padding:24px; border-radius:8px; max-width:400px; margin:20px auto; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
    <h2>Book a Room</h2>
    <?php if ($success): ?>
        <div style="color:green; margin-bottom:16px;">Thank you for your booking! Your request was sent successfully. The admin will contact you soon.</div>
    <?php endif; ?>
    <form method="post" action="">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required style="width:100%; margin-bottom:10px;"><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required style="width:100%; margin-bottom:10px;"><br>
        <label for="phone">Phone Number:</label><br>
        <input type="tel" id="phone" name="phone" required pattern="[0-9\-\+\s]{7,15}" style="width:100%; margin-bottom:10px;" placeholder="e.g. +254700000000"><br>
        <label for="room">Room Type:</label><br>
        <select id="room" name="room" required style="width:100%; margin-bottom:10px;">
            <option value="single">Single Room</option>
            <option value="double">Double Room</option>
            <option value="suite">Suite</option>
            <option value="deluxe">Deluxe Room</option>
        </select><br>
        <label for="checkin">Check-in Date:</label><br>
        <input type="date" id="checkin" name="checkin" required style="width:100%; margin-bottom:10px;"><br>
        <label for="checkout">Check-out Date:</label><br>
        <input type="date" id="checkout" name="checkout" required style="width:100%; margin-bottom:10px;"><br>
        <button type="submit" style="background:#222; color:#fff; padding:8px 16px; border:none; border-radius:4px;">Book Now</button>
    </form>
</div>