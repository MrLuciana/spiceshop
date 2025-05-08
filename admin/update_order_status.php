<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

$order_id = $_POST['order_id'] ?? 0;
$status = $_POST['status'] ?? 'pending';

$stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
$stmt->execute([$status, $order_id]);

$_SESSION['flash'] = ['success' => 'อัปเดตสถานะเรียบร้อยแล้ว'];
header("Location: orders.php");
exit;