<?php
session_start();
$product_id = $_POST['product_id'] ?? null;
$quantity = max(1, (int)($_POST['quantity'] ?? 1));

if ($product_id && isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
}
header('Location: cart.php');
exit;
