<?php
session_start();

$product_id = $_GET['id'] ?? null;

if ($product_id && isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
    $_SESSION['flash'] = ['success' => 'ลบสินค้าออกจากตะกร้าแล้ว'];
}

header('Location: cart.php');
exit;
