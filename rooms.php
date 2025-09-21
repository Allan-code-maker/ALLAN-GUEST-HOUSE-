<?php
$rooms = [
    [
        'number' => 101,
        'type' => 'Single',
        'price' => 50,
        'description' => 'A cozy single room with a comfortable bed and a view of the garden.'
    ],
    [
        'number' => 102,
        'type' => 'Double',
        'price' => 80,
        'description' => 'A spacious double room perfect for couples, featuring modern amenities.'
    ],
    [
        'number' => 103,
        'type' => 'Suite',
        'price' => 120,
        'description' => 'An elegant suite with a living area, kitchenette, and luxury bathroom.'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Allan Guest House</title>
    <style>
        body {
            background-color: #f5f5f5;
            font-family: "Poppins", sans-serif;
            margin: 0;
            padding: 0;
        }
        .rooms-container, .dashboard-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.1);
        }
        h1, h2 {
            color: #32CDD5;
        }
        .room {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 8px;
            background: #e0f7fa;
        }
        .room h3 {
            margin-bottom: 10px;
        }
        .room p {
            margin-bottom: 5px;
        }
        .section {
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }
        .btn {
            padding: 5px 10px;
            background-color: #32CDD5;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }
        .btn-danger {
            background-color: #e74c3c;
        }
        .message {
            padding: 10px;
            background-color: #f1f1f1;
            border-left: 5px solid #32CDD5;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <!-- Rooms Section -->
    <div class="rooms-container">
        <h2>Available Rooms</h2>
        <?php foreach ($rooms as $room): ?>
            <div class="room">
                <h3>Room <?= $room['number'] ?></h3>
                <p>Type: <?= $room['type'] ?></p>
                <p>Price: $<?= $room['price'] ?> per night</p>
                <p>Description: <?= $room['description'] ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Admin Dashboard Section -->
    <div class="dashboard-container">
        <h1>Admin Dashboard</h1>

        <div class="section">
            <h2>Manage Rooms</h2>
            <table>
                <tr>
                    <th>Room Number</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($rooms as $room): ?>
                    <tr>
                        <td><?= $room['number'] ?></td>
                        <td><?= $room['type'] ?></td>
                        <td>$<?= $room['price'] ?></td>
                        <td>
                            <button class="btn">Edit</button>
                            <button class="btn btn-danger">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="section">
            <h2>Recent Bookings</h2>
            <div class="message">No new bookings at the moment.</div>
        </div>

        <div class="section">
            <h2>Contact Messages</h2>
            <div class="message">No new messages.</div>
        </div>
    </div>

</body>
</html>
