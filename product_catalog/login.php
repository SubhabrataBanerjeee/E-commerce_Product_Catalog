<?php
require_once 'config.php';
require_once 'functions.php';

if (is_logged_in()) redirect('index.php');

$error = '';
$redirect_to = safe_redirect($_GET['redirect'] ?? ($_POST['redirect'] ?? 'index.php'), 'index.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Please enter both email and password.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id']   = (int)$user['user_id'];
            $_SESSION['user_name'] = $user['name'];
            set_flash('👋 Welcome back, ' . $user['name'] . '!');
            redirect($redirect_to);
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - QuickCart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="auth-box">
    <h2>Login</h2>
    <p class="sub">Welcome back! Please enter your details.</p>

    <?php if ($error): ?><div class="error-msg"><?= e($error) ?></div><?php endif; ?>

    <form method="POST">
        <input type="hidden" name="redirect" value="<?= e($redirect_to) ?>">

        <label>Email Address</label>
        <input type="email" name="email" placeholder="you@example.com"
               value="<?= e($_POST['email'] ?? '') ?>" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Your password" required>

        <button type="submit">Login</button>
    </form>

    <p class="alt">New here? <a href="register.php">Create an account</a></p>
</div>

<?php include 'footer.php'; ?>