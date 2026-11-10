<?php
require_once 'config.php';
require_once 'functions.php';

/* ---------- Handle cart actions ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove_id'])) {
        unset($_SESSION['cart'][(int)$_POST['remove_id']]);
        set_flash('Item removed from cart.');
    } elseif (($_POST['action'] ?? '') === 'clear') {
        $_SESSION['cart'] = [];
        set_flash('Cart cleared.');
    } elseif (($_POST['action'] ?? '') === 'update' && !empty($_POST['qty']) && is_array($_POST['qty'])) {
        foreach ($_POST['qty'] as $pid => $q) {
            $pid = (int)$pid;
            $q   = (int)$q;
            if ($q <= 0)  unset($_SESSION['cart'][$pid]);
            else          $_SESSION['cart'][$pid] = min(50, $q);
        }
        set_flash('Cart updated.');
    }
    redirect('cart.php');
}

/* ---------- Load cart items ---------- */
$items = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    $ids = array_map('intval', array_keys($_SESSION['cart']));
    $in  = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id IN ($in)");
    $stmt->execute($ids);

    foreach ($stmt->fetchAll() as $p) {
        $p['qty']      = (int)$_SESSION['cart'][$p['product_id']];
        $p['subtotal'] = $p['qty'] * $p['price'];
        $total        += $p['subtotal'];
        $items[]       = $p;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart - QuickCart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="cart-wrap">
    <h2 class="section-title" style="padding-left:0;">🛒 My Cart</h2>

    <?php if (empty($items)): ?>
        <p class="empty-msg">Your cart is empty. <a href="index.php" style="color:#0c831f;font-weight:600;">Start shopping →</a></p>
    <?php else: ?>
        <form method="POST" action="cart.php">
            <div class="table-scroll">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td style="display:flex;align-items:center;gap:12px;">
                                    <img src="<?= e($item['image_url']) ?>" alt=""
                                         onerror="this.src='https://via.placeholder.com/60?text=IMG'">
                                    <a href="product.php?id=<?= (int)$item['product_id'] ?>" style="font-weight:600;">
                                        <?= e($item['product_name']) ?>
                                    </a>
                                </td>
                                <td>₹<?= number_format($item['price'], 2) ?></td>
                                <td>
                                    <input class="qty-input" type="number" min="0" max="50"
                                           name="qty[<?= (int)$item['product_id'] ?>]"
                                           value="<?= (int)$item['qty'] ?>">
                                </td>
                                <td style="font-weight:700;color:#0c831f;">₹<?= number_format($item['subtotal'], 2) ?></td>
                                <td>
                                    <button type="submit" name="remove_id" value="<?= (int)$item['product_id'] ?>"
                                            class="remove-btn" title="Remove">✕</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="cart-actions">
                <button type="submit" name="action" value="update" class="btn">Update Cart</button>
                <button type="submit" name="action" value="clear" class="btn btn-secondary">Clear Cart</button>
            </div>
        </form>

        <div class="cart-summary">
            <div>
                <div style="font-size:13px;color:#777;">Total Payable</div>
                <div class="total">₹<?= number_format($total, 2) ?></div>
            </div>
            <a href="checkout.php" class="btn">Proceed to Checkout →</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>