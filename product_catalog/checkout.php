<?php
require_once 'config.php';
require_once 'functions.php';

$success = isset($_GET['success']);

if (!$success) {
    if (empty($_SESSION['cart'])) redirect('cart.php');
    if (!is_logged_in())       redirect('login.php?redirect=checkout.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $_SESSION['cart'] = [];
    set_flash('🎉 Order placed successfully! Thank you for shopping with QuickCart.');
    redirect('checkout.php?success=1');
}

/* Build the summary */
$items = [];
$total = 0;
if (!$success && !empty($_SESSION['cart'])) {
    $ids = array_map('intval', array_keys($_SESSION['cart']));
    $in  = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id IN ($in)");
    $stmt->execute($ids);
    foreach ($stmt->fetchAll() as $p) {
        $p['qty'] = (int)$_SESSION['cart'][$p['product_id']];
        $p['subtotal'] = $p['qty'] * $p['price'];
        $total += $p['subtotal'];
        $items[] = $p;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - QuickCart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="cart-wrap">
    <?php if ($success): ?>
        <div class="auth-box" style="text-align:center;">
            <div style="font-size:48px;">🎉</div>
            <h2>Order Placed!</h2>
            <p class="sub">Your order is confirmed and will be delivered soon.</p>
            <a href="index.php" class="btn" style="display:inline-block;">Continue Shopping</a>
        </div>
    <?php else: ?>
        <h2 class="section-title" style="padding-left:0;">Checkout</h2>
        <p style="color:#777;font-size:14px;margin-bottom:16px;">
            Delivering to: <strong><?= e(current_user_name()) ?></strong>
        </p>

        <div class="table-scroll">
            <table class="cart-table">
                <thead>
                    <tr><th>Item</th><th>Qty</th><th>Subtotal</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= e($item['product_name']) ?></td>
                            <td><?= (int)$item['qty'] ?></td>
                            <td>₹<?= number_format($item['subtotal'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="cart-summary">
            <div>
                <div style="font-size:13px;color:#777;">Total Payable</div>
                <div class="total">₹<?= number_format($total, 2) ?></div>
            </div>
            <form method="POST">
                <button type="submit" name="place_order" value="1" class="btn">Place Order 🚀</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>