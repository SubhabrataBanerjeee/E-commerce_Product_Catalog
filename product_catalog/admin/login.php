<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

if (is_admin_logged_in()) redirect('index.php');

$error = '';
$flash = get_flash();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please enter username and password.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id']   = (int)$admin['admin_id'];
            $_SESSION['admin_name'] = $admin['name'];
            redirect('index.php');
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login · QuickCart</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="login-wrap">
    <div class="login-box">
        <h1>⚡ Admin Login</h1>
        <p class="sub">Sign in to manage products and categories.</p>

        <?php if ($flash): ?><div class="flash"><?= e($flash) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="error-msg"><?= e($error) ?></div><?php endif; ?>

        <form method="POST">
            <label>Username</label>
            <input type="text" name="username" value="<?= e($_POST['username'] ?? '') ?>" required autofocus>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        <a class="back" href="../index.php">← Back to Store</a>
    </div>
</div>
</body>
</html>