<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$order_id = $_POST['order_id'] ?? null;
$user_id = $_SESSION['user']['user_id'] ?? null;
$upload_dir = 'uploads/slips/';

if (!$order_id || !isset($_FILES['slip'])) {
    $_SESSION['flash'] = ['error' => 'ข้อมูลไม่ครบถ้วน'];
    header("Location: index.php");
    exit;
}

// ตรวจสอบคำสั่งซื้อว่าเป็นของผู้ใช้จริง
$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ? AND user_id = ?");
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    $_SESSION['flash'] = ['error' => 'ไม่พบคำสั่งซื้อ'];
    header("Location: index.php");
    exit;
}

// จัดการอัปโหลดไฟล์
$file = $_FILES['slip'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$filename = 'slip_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
$target_path = $upload_dir . $filename;

if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
    $_SESSION['flash'] = ['error' => 'อนุญาตเฉพาะไฟล์ .jpg, .jpeg, .png เท่านั้น'];
    header("Location: upload_payment.php?order_id=$order_id");
    exit;
}

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

if (move_uploaded_file($file['tmp_name'], $target_path)) {
    // อัปเดตคำสั่งซื้อ
    $stmt = $pdo->prepare("UPDATE orders SET payment_slip = ?, status = 'waiting_payment_verify' WHERE order_id = ?");
    $stmt->execute([$filename, $order_id]);

    // 🔔 (ภายหลัง) แจ้ง admin ทาง email ได้ที่นี่

    $_SESSION['flash'] = ['success' => 'อัปโหลดสลิปเรียบร้อย รอการตรวจสอบจากแอดมิน'];
    header("Location: index.php");
    exit;
} else {
    $_SESSION['flash'] = ['error' => 'เกิดข้อผิดพลาดในการอัปโหลดสลิป'];
    header("Location: upload_payment.php?order_id=$order_id");
    exit;
}
