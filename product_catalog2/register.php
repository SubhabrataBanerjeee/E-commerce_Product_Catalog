<?php
require_once 'config.php';
require_once 'functions.php';

if (is_logged_in()) redirect('index.php');

$error = '';
$redirect_to = safe_redirect($_GET['redirect'] ?? ($_POST['redirect'] ?? 'index.php'), 'index.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'An account with this email already exists.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hash]);

            $_SESSION['user_id']   = (int)$pdo->lastInsertId();
            $_SESSION['user_name'] = $name;
            set_flash('🎉 Welcome to QuickCart, ' . $name . '!');
            redirect($redirect_to);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - QuickCart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="auth-box">
    <h2>Create an account</h2>
    <p class="sub">Sign up to track orders and check out faster.</p>

    <?php if ($error): ?><div class="error-msg"><?= e($error) ?></div><?php endif; ?>

    <form method="POST">
        <input type="hidden" name="redirect" value="<?= e($redirect_to) ?>">

        <label>Full Name</label>
        <input type="text" name="name" placeholder="Your name"
               value="<?= e($_POST['name'] ?? '') ?>" required>

        <label>Email Address</label>
        <input type="email" name="email" placeholder="you@example.com"
               value="<?= e($_POST['email'] ?? '') ?>" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Minimum 6 characters" required>

        <label>Confirm Password</label>
        <input type="password" name="confirm" placeholder="Re-enter password" required>

        <button type="submit">Create Account</button>
    </form>

    <p class="alt">Already have an account? <a href="login.php">Login</a></p>
</div>

<?php include 'footer.php'; ?>