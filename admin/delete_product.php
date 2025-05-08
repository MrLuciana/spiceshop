<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
$stmt->execute([$id]);

$_SESSION['flash'] = ['success' => 'ลบสินค้าสำเร็จ'];
header("Location: products.php");
exit;
