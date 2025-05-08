<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}
$title = "จัดการหมวดหมู่";
require_once '../includes/header.php';

// ดึงข้อมูลหมวดหมู่พร้อมนับจำนวนสินค้าต่อหมวด
$stmt = $pdo->query("
    SELECT c.*, COUNT(p.product_id) AS product_count
    FROM categories c
    LEFT JOIN products p ON c.category_id = p.category_id
    GROUP BY c.category_id
    ORDER BY c.category_id ASC
");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1 class="text-2xl font-bold mb-4">จัดการหมวดหมู่</h1>
<a href="add_category.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mb-4">+ เพิ่มหมวดหมู่</a>

<div class="overflow-x-auto">
    <table class="min-w-full bg-white rounded shadow">
        <thead class="bg-gray-200">
            <tr>
                <th class="px-4 py-2 text-left">ลำดับ</th>
                <th class="px-4 py-2 text-left">ชื่อหมวดหมู่</th>
                <th class="px-4 py-2 text-left">คำอธิบาย</th>
                <th class="px-4 py-2 text-center">จำนวนสินค้า</th>
                <th class="px-4 py-2 text-center">จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php $index = 1; ?>
            <?php foreach ($categories as $cat): ?>
            <tr class="border-t">
                <td class="px-4 py-2"><?= $index++ ?></td>
                <td class="px-4 py-2"><?= !empty($cat['name']) ? htmlspecialchars($cat['name']) : 'ไม่มีข้อมูล' ?></td>
                <td class="px-4 py-2"><?= !empty($cat['description']) ? htmlspecialchars($cat['description']) : 'ไม่มีข้อมูล' ?></td>
                <td class="px-4 py-2 text-center"><?= $cat['product_count'] ?></td>
                <td class="px-4 py-2 text-center">
                    <a href="edit_category.php?id=<?= $cat['category_id'] ?>" class="text-blue-600 hover:underline">แก้ไข</a> |
                    <a href="delete_category.php?id=<?= $cat['category_id'] ?>" class="text-red-600 hover:underline" data-confirm>ลบ</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../includes/footer.php'; ?>
