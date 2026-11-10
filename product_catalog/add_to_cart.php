<?php
require_once 'config.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$pid = (int)($_POST['product_id'] ?? 0);
$qty = max(1, min(50, (int)($_POST['qty'] ?? 1)));

if ($pid > 0) {
    $stmt = $pdo->prepare("SELECT product_name FROM products WHERE product_id = ?");
    $stmt->execute([$pid]);
    $prod = $stmt->fetch();

    if ($prod) {
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        $_SESSION['cart'][$pid] = ($_SESSION['cart'][$pid] ?? 0) + $qty;
        set_flash('✅ ' . $prod['product_name'] . ' added to cart.');
    }
}

$redirect = safe_redirect($_POST['redirect'] ?? 'cart.php', 'cart.php');
redirect($redirect);