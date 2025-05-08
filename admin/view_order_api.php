<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['admin'])) {
    exit("ไม่ได้รับอนุญาต");
}

$order_id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT o.*, u.username FROM orders o LEFT JOIN users u ON o.user_id = u.user_id WHERE o.order_id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    exit("ไม่พบคำสั่งซื้อ");
}

$stmt = $pdo->prepare("SELECT oi.*, p.name FROM order_items oi LEFT JOIN products p ON oi.product_id = p.product_id WHERE order_id = ?");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h2 class="text-xl font-bold mb-2">คำสั่งซื้อ #<?= $order['order_id'] ?></h2>
<p><strong>ผู้สั่งซื้อ:</strong> <?= htmlspecialchars($order['username']) ?></p>
<p><strong>วันที่:</strong> <?= $order['order_date'] ?></p>
<p><strong>สถานะ:</strong> <?= $order['status'] ?></p>
<p><strong>ยอดรวม:</strong> ฿<?= number_format($order['total_amount'], 2) ?></p>

<h3 class="text-lg font-semibold mt-4 mb-2">รายการสินค้า</h3>
<table class="min-w-full text-sm border rounded">
    <thead class="bg-gray-100">
        <tr>
            <th class="px-2 py-1 text-left">สินค้า</th>
            <th class="px-2 py-1 text-left">จำนวน</th>
            <th class="px-2 py-1 text-left">ราคา</th>
            <th class="px-2 py-1 text-left">รวม</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
            <td class="px-2 py-1"><?= htmlspecialchars($item['name']) ?></td>
            <td class="px-2 py-1"><?= $item['quantity'] ?></td>
            <td class="px-2 py-1">฿<?= number_format($item['price'], 2) ?></td>
            <td class="px-2 py-1">฿<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>