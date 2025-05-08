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

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-2">ยอดขายวันนี้</h2>
        <p class="text-2xl text-green-600 font-bold">฿5,400</p>
    </div>
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-2">คำสั่งซื้อใหม่</h2>
        <p class="text-2xl text-blue-600 font-bold">8 รายการ</p>
    </div>
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-2">จำนวนสมาชิก</h2>
        <p class="text-2xl text-purple-600 font-bold">154 คน</p>
    </div>
</div>

<div class="mt-8">
    <h2 class="text-xl font-semibold mb-4">เมนูด่วน</h2>
    <div class="flex flex-wrap gap-4">
        <a href="products.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">จัดการสินค้า</a>
        <a href="orders.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">จัดการคำสั่งซื้อ</a>
        <a href="categories.php" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">จัดการหมวดหมู่</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
