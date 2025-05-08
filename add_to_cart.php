<?php
session_start();
require_once 'includes/db.php';

$product_id = $_POST['product_id'] ?? null;
$quantity = max(1, (int)($_POST['quantity'] ?? 1));

// ดึงสินค้าจาก DB
$stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product || $product['stock'] < $quantity) {
    $_SESSION['flash'] = ['error' => 'ไม่สามารถเพิ่มสินค้าได้ (อาจหมดหรือไม่พอในสต๊อก)'];
    header("Location: products.php");
    exit;
}

// เพิ่มลงตะกร้า
$_SESSION['cart'][$product_id]['quantity'] = ($_SESSION['cart'][$product_id]['quantity'] ?? 0) + $quantity;

// ตั้งข้อความสำเร็จ
$_SESSION['flash'] = ['success' => 'เพิ่ม <strong>' . htmlspecialchars($product['name']) . '</strong> ลงตะกร้าแล้ว'];
header("Location: products.php");
exit;
