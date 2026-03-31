<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';

// Already logged in — redirect to dashboard
if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username   = trim($_POST['username'] ?? '');
    $password   = $_POST['password'] ?? '';
    $login_type = $_POST['login_type'] ?? 'admin';

    if ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {
        $conn  = get_db();
        $table = ($login_type === 'admin') ? 'admins' : 'users';

        $stmt = $conn->prepare("SELECT id, username, password FROM `$table` WHERE username = ? LIMIT 1");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id']  = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role']     = $login_type;
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Invalid username or password.';
            }
        } else {
            $error = 'Invalid username or password.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — <?= htmlspecialchars(APP_NAME) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
<div class="auth-card">
    <h2 class="auth-title">Sign In</h2>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <div class="form-group">
            <label for="login_type">Login as</label>
            <select name="login_type" id="login_type" class="form-control">
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" class="form-control"
                   placeholder="Enter your username" required
                   value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
        </div>
        <div class="form-group password-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control"
                   placeholder="Enter your password" required>
            <button type="button" class="toggle-pwd" onclick="togglePassword()">Show</button>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>

    <p class="auth-footer">
        Don't have an account? <a href="register.php">Create Account</a>
    </p>
</div>

<script>
function togglePassword() {
    var pwd = document.getElementById('password');
    var btn = event.currentTarget;
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
