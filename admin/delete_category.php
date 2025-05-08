<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("DELETE FROM categories WHERE category_id = ?");
$stmt->execute([$id]);

$_SESSION['flash'] = ['success' => 'ลบหมวดหมู่เรียบร้อยแล้ว'];
header("Location: categories.php");
exit;
