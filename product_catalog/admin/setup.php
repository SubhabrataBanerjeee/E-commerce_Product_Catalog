<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

$count = (int)$pdo->query("SELECT COUNT(*) FROM admins")->fetchColumn();
$done  = false;
$error = '';

if ($count > 0) {
    $error = 'An admin account already exists. For security, delete setup.php now.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $name     = trim($_POST['name'] ?? '');
    $pass     = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if ($username === '' || $name === '' || $pass === '') {
        $error = 'All fields are required.';
    } elseif (strlen($pass) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($pass !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO admins (username, password_hash, name) VALUES (?, ?, ?)");
        $stmt->execute([$username, password_hash($pass, PASSWORD_DEFAULT), $name]);
        $done = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Setup · QuickCart</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="login-wrap">
    <div class="login-box">
        <?php if ($done): ?>
            <h1>✅ Admin Created</h1>
            <p class="sub">You can now log in. Delete <strong>setup.php</strong> from the server.</p>
            <a class="back" href="login.php">Go to Login →</a>
        <?php else: ?>
            <h1>🛠️ Create First Admin</h1>
            <p class="sub">This page only works while the admins table is empty.</p>

            <?php if ($error): ?><div class="error-msg"><?= e($error) ?></div><?php endif; ?>

            <?php if ($count === 0): ?>
            <form method="POST">
                <label>Full Name</label>
                <input type="text" name="name" value="<?= e($_POST['name'] ?? '') ?>" required>

                <label>Username</label>
                <input type="text" name="username" value="<?= e($_POST['username'] ?? '') ?>" required>

                <label>Password</label>
                <input type="password" name="password" placeholder="Min. 6 characters" required>

                <label>Confirm Password</label>
                <input type="password" name="confirm" required>

                <button type="submit">Create Admin</button>
            </form>
            <?php endif; ?>

            <a class="back" href="../index.php">← Back to Store</a>
        <?php endif; ?>
    </div>
</div>
</body>
</html>