<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}
$title = "แดชบอร์ดแอดมิน";
require_once '../includes/header.php';
?>

<h1 class="text-2xl font-bold mb-6">แดชบอร์ดผู้ดูแลระบบ</h1>

<div class="mt-8">

    <div class="flex flex-wrap gap-4">
        <a href="products.php" class="bg-blue-600 text-white px-8 py-4 rounded hover:bg-blue-700">จัดการสินค้า</a>
        <a href="orders.php" class="bg-green-600 text-white px-8 py-4 rounded hover:bg-green-700">จัดการคำสั่งซื้อ</a>
        <a href="categories.php" class="bg-yellow-500 text-white px-8 py-4 rounded hover:bg-yellow-600">จัดการหมวดหมู่</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
