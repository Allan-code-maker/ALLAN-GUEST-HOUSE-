<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: "Poppins", sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .dashboard-container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            padding: 32px 28px;
        }
        h1 {
            color: #32CDD5;
            margin-bottom: 24px;
            text-align: center;
        }
        .section {
            margin-bottom: 32px;
        }
        .section h2 {
            color: #222;
            margin-bottom: 12px;
            font-size: 1.2rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        th, td {
            border: 1px solid #e0e0e0;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background: #32CDD5;
            color: #fff;
        }
        .btn {
            background: #32CDD5;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 6px 12px;
            cursor: pointer;
            font-size: 0.95rem;
        }
        .btn-danger {
            background: #d32f2f;
        }
        .message {
            background: #e0f7fa;
            padding: 8px;
            border-radius: 6px;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <h1>Admin Dashboard</h1>
    <div class="section">
        <h2>Room Bookings</h2>
        <?php
        $conn = new mysqli("localhost", "root", "", "guesthouse");
        if ($conn->connect_error) {
            echo '<div class="message">Database connection failed.</div>';
        } else {
            $result = $conn->query("SELECT name, email, phone, room, checkin, checkout, created_at FROM bookings ORDER BY created_at DESC");
            if ($result && $result->num_rows > 0) {
                echo '<table><tr><th>Name</th><th>Email</th><th>Phone</th><th>Room</th><th>Check-in</th><th>Check-out</th><th>Booked At</th></tr>';
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['phone']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['room']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['checkin']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['checkout']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['created_at']) . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo '<div class="message">No bookings found.</div>';
            }
            $conn->close();
        }
        ?>
    </div>
    <div class="section">
        <h2>Registered Users</h2>
        <?php
        if (file_exists("users.txt")) {
            $users = file("users.txt");
            if (count($users) > 0) {
                echo '<table><tr><th>Username</th><th>Email</th></tr>';
                foreach ($users as $user) {
                    $fields = explode(",", trim($user));
                    echo '<tr><td>' . htmlspecialchars($fields[0]) . '</td><td>' . htmlspecialchars($fields[1]) . '</td></tr>';
                }
                echo '</table>';
            } else {
                echo '<div class="message">No users found.</div>';
            }
        } else {
            echo '<div class="message">No users found.</div>';
        }
        ?>
    </div>
    <div class="section">
        <h2>Update Room Details</h2>
        <form method="post" action="">
            <input type="text" name="room_name" placeholder="Room Name" required style="margin-bottom:8px; width:200px;">
            <input type="number" name="room_price" placeholder="Price" required style="margin-bottom:8px; width:120px;">
            <button class="btn" type="submit">Update</button>
        </form>
        <!-- For demo, not saving room details. Implement DB or file save as needed. -->
    </div>
    <div class="section">
        <h2>Customer Requests / Messages</h2>
        <div class="message">No new messages.</div>
        <!-- Implement message display from a file or DB as needed. -->
    </div>
    <div class="section">
        <h2>Manage Site Content</h2>
        <div class="message">Content management coming soon.</div>
    </div>
</div>
</body>
</html>
