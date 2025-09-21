<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            background: #e0f7fa;
            font-family: "Poppins", sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .register-container {
            background: #fff;
            padding: 28px 24px;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            max-width: 350px;
            width: 100%;
        }
        .register-title {
            color: #32CDD5;
            margin-bottom: 18px;
            text-align: center;
            font-size: 1.5rem;
        }
        .register-container input {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 12px;
            border-radius: 6px;
            border: 1px solid #32CDD5;
            background: #f7f7f7;
            font-size: 1rem;
        }
        .register-container button {
            width: 100%;
            padding: 10px 0;
            background: #32CDD5;
            border-radius: 6px;
            color: #fff;
            font-weight: bold;
            font-size: 1rem;
            border: none;
            cursor: pointer;
        }
        .register-container .login-link {
            margin-top: 10px;
            text-align: center;
            font-size: 0.95rem;
        }
        .register-container .login-link a {
            color: #32CDD5;
            text-decoration: none;
        }
        .success-message {
            color: green;
            text-align: center;
            margin-bottom: 12px;
        }
        .error-message {
            color: #d32f2f;
            text-align: center;
            margin-bottom: 12px;
        }
    </style>
</head>
<body>
<?php
$success = false;
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $email = trim($_POST["email"] ?? "");
    if ($username && $password && $email) {
        $conn = new mysqli("localhost", "root", "", "guesthouse");
        if ($conn->connect_error) {
            $error = "Database connection failed.";
        } else {
            // Check if username or email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $error = "Username or email already exists.";
            } else {
                $stmt->close();
                $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $email, $password);
                if ($stmt->execute()) {
                    $success = true;
                } else {
                    $error = "Registration failed.";
                }
            }
            $stmt->close();
            $conn->close();
        }
    } else {
        $error = "All fields are required.";
    }
}
?>
<div class="register-container">
    <h2 class="register-title">Create Account</h2>
    <?php if ($success): ?>
        <div class="success-message">Registration successful! <a href="admin.php">Login here</a>.</div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="post" action="">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>
    <div class="login-link">
        Already have an account? <a href="admin.php">Login</a>
    </div>
</div>
</body>
</html>
