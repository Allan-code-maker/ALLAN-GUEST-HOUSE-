<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>allan web</title>
    <style>
        * {
    font-family: "Poppins", sans-serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background-color: aqua;
    width: 100%;
    height: 100dvh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-size: cover;
    background-position: center;
}

.container {
    background-color: aliceblue;
    width: 320px;
    padding: 24px;
    border-radius: 16px;
    border: solid 5px rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(25px);
    box-shadow: 0px 0px 30px 20px rgba(0, 0, 0, 0.1);
    color: blue;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.login-title
 {
    margin-bottom: 16px;
}

.input-box {
    display: flex;
    width: 100%;
    position: relative;
    margin-top: 20px;
}

.input-box input {
    width: 100%;
    padding: 10px 16px 10px 38px;
    border-radius: 99px;
    border: solid 3px transparent;
    background: rgba(255, 255, 255, 0.1);
    outline: none;
    color: white;
    font-weight: 500;
    transition: 0.25s;
}

.input-box i {
    position: absolute;
    top: 50%;
    left: 14px;
    transform: translateY(-50%);
    color: rgba(255, 255, 255, 0.75);
    font-size: 18px;
}

.login-button {
    width: 100%;
    margin-top: 24px;
    padding: 10px 0;
    background: #32CDD5;
    border-radius: 99px;
    color: white;
    font-weight: bold;
    font-size: 15px;
    cursor: pointer;
    transition: 0.1s;
}

.dont-have-an-account {
    margin-top: 12px;
    font-weight: normal;
}
    </style>
</head>
<body>
<?php
// Hardcoded credentials (for demo)
$valid_user = "admin";
$valid_pass = "password123";
$error = "";

// Database credentials (template)
$use_db = true; // Set to true to use database authentication
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "guesthouse";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";
    $login_type = $_POST["login_type"] ?? "admin";
if ($use_db) {
    // Database authentication
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    if ($conn->connect_error) {
        $error = "Database connection failed.";
    } else {
        if ($login_type === "admin") {
            $stmt = $conn->prepare("SELECT password FROM admins WHERE username = ? LIMIT 1");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($db_password);
                $stmt->fetch();
                if ($password === $db_password) { // Use password_verify() if hashed
                    header("Location: dashboard.php");
                    exit;
                } else {
                    $error = "Invalid password.";
                }
            } else {
                $error = "User not found.";
            }
            $stmt->close();
        } else if ($login_type === "user") {
            $stmt = $conn->prepare("SELECT password FROM users WHERE username = ? LIMIT 1");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($db_password);
                $stmt->fetch();
                if ($password === $db_password) { // Use password_verify() if hashed
                    header("Location: dashboard.php");
                    exit;
                } else {
                    $error = "Invalid password.";
                }
            } else {
                $error = "User not found.";
            }
            $stmt->close();
        }
        $conn->close();
    }
} else {
    // Hardcoded authentication for admin only
    if ($login_type === "admin") {
        if ($username === $valid_user && $password === $valid_pass) {
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "User login is not enabled.";
    }
}
}
?>
<form class="container" method="post" action="" style="max-width:340px; padding:18px 16px; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,0.08); background:#fff; color:#222;">
    <h2 class="login-title" style="margin-bottom:10px; font-size:1.5rem; color:#32CDD5;">Sign In</h2>
    <?php if ($error): ?>
        <div style="color:#d32f2f; margin-bottom:10px; text-align:center; font-size:0.95rem; border-radius:6px; background:#ffeaea; padding:6px 0;"><?php echo $error; ?></div>
    <?php endif; ?>
    <div style="margin-bottom:12px;">
        <select name="login_type" style="width:100%; padding:8px; border-radius:6px; border:1px solid #32CDD5; background:#f7f7f7; color:#222;">
            <option value="admin">Login as Admin</option>
            <option value="user">Login as User</option>
        </select>
    </div>
    <div style="margin-bottom:12px; position:relative;">
        <input type="text" name="username" placeholder="Username" required style="width:100%; padding:10px 12px; border-radius:6px; border:1px solid #32CDD5; background:#f7f7f7; color:#222; font-size:1rem;">
        <span style="position:absolute; left:8px; top:50%; transform:translateY(-50%); color:#32CDD5; font-size:18px;">&#128100;</span>
    </div>
    <div style="margin-bottom:8px; position:relative;">
        <input type="password" name="password" id="password" placeholder="Password" required style="width:100%; padding:10px 12px 10px 38px; border-radius:6px; border:1px solid #32CDD5; background:#f7f7f7; color:#222; font-size:1rem;">
        <span style="position:absolute; left:8px; top:50%; transform:translateY(-50%); color:#32CDD5; font-size:18px;">&#128274;</span>
        <button type="button" onclick="togglePassword()" style="position:absolute; right:8px; top:50%; transform:translateY(-50%); background:none; border:none; color:#32CDD5; font-size:16px; cursor:pointer;">Show</button>
    </div>
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
        <label style="display:flex; align-items:center; font-size:0.95rem;">
            <input type="checkbox" name="remember-me" id="remember-me" style="margin-right:6px;"> Remember me
        </label>
        <a class="forgot-password" href="#" style="font-size:0.95rem; color:#32CDD5; text-decoration:none;">Forgot password?</a>
    </div>
    <button class="login-button" type="submit" style="width:100%; margin-top:8px; padding:10px 0; background:#32CDD5; border-radius:6px; color:#fff; font-weight:bold; font-size:1rem; border:none; cursor:pointer;">Login</button>
    <div style="margin-top:10px; text-align:center; font-size:0.95rem;">
        Don't have an account? <a href="register.php" style="color:#32CDD5; text-decoration:none;"><b>Create Account</b></a>
    </div>
</form>
<script>
function togglePassword() {
    var pwd = document.getElementById('password');
    var btn = event.target;
    if (pwd.type === 'password') {
        pwd.type = 'text';
        btn.textContent = 'Hide';
    } else {
        pwd.type = 'password';
        btn.textContent = 'Show';
    }
}
</script>
</body>
</html>