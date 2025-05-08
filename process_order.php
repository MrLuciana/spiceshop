<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user']['user_id'];
$shipping_address = trim($_POST['shipping_address'] ?? '');
$payment_method = $_POST['payment_method'] ?? '';

$cart = $_SESSION['cart'] ?? [];
$total = 0;

if (empty($shipping_address) || empty($payment_method) || empty($cart)) {
    $_SESSION['flash'] = ['error' => 'ข้อมูลไม่ครบถ้วน หรือไม่มีสินค้าในตะกร้า'];
    header("Location: checkout.php");
    exit;
}

// คำนวณยอดรวมและเตรียมข้อมูลสินค้า
$ids = implode(',', array_keys($cart));
$stmt = $pdo->query("SELECT * FROM products WHERE product_id IN ($ids)");
$products = $stmt->fetchAll(PDO::FETCH_UNIQUE);

foreach ($cart as $product_id => $item) {
    $product = $products[$product_id] ?? null;
    if (!$product) continue;
    $total += $product['price'] * $item['quantity'];
}

// เริ่มบันทึกคำสั่งซื้อ
$pdo->beginTransaction();

try {
    // บันทึก order
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, order_date, shipping_address, total_amount, payment_method, status) 
                           VALUES (?, NOW(), ?, ?, ?, 'pending')");
    $stmt->execute([$user_id, $shipping_address, $total, $payment_method]);

    $order_id = $pdo->lastInsertId();

    // บันทึกรายการสินค้า
    $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($cart as $product_id => $item) {
        $product = $products[$product_id];
        $stmt_item->execute([$order_id, $product_id, $item['quantity'], $product['price']]);
    }

    $pdo->commit();

    // ล้างตะกร้า
    unset($_SESSION['cart']);

    // ไปหน้าอัปโหลดสลิปถ้า QR
    if ($payment_method === 'qr') {
        header("Location: upload_payment.php?order_id=$order_id");
    } else {
        $_SESSION['flash'] = ['success' => 'สั่งซื้อสำเร็จ! จะจัดส่งตามที่อยู่ที่ให้ไว้'];
        header("Location: index.php");
    }

    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['flash'] = ['error' => 'เกิดข้อผิดพลาดในการบันทึกคำสั่งซื้อ'];
    header("Location: checkout.php");
    exit;
}
