<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}
$title = "จัดการสินค้า";
require_once '../includes/header.php';

// ดึงข้อมูลสินค้า เรียงจากเก่าไปใหม่
$stmt = $pdo->query("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.category_id ORDER BY p.product_id ASC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1 class="text-2xl font-bold mb-4">จัดการสินค้า</h1>
<a href="add_product.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 inline-block mb-4">+ เพิ่มสินค้า</a>

<div class="overflow-x-auto">
    <table class="min-w-full bg-white shadow rounded">
        <thead class="bg-gray-200">
            <tr>
                <th class="px-4 py-2 text-left">ลำดับ</th>
                <th class="px-4 py-2 text-left">รูป</th>
                <th class="px-4 py-2 text-left">ชื่อสินค้า</th>
                <th class="px-4 py-2 text-left">หมวดหมู่</th>
                <th class="px-4 py-2 text-left">ราคา</th>
                <th class="px-4 py-2 text-center">จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php $index = 1; ?>
            <?php foreach ($products as $product): ?>
                <tr class="border-t">
                    <td class="px-4 py-2"><?= $index++ ?></td>

                    <?php
                    $filename = $product['image'] ?? '';
                    $filepath = '../uploads/' . $filename;
                    $imagePath = (file_exists($filepath) && is_file($filepath)) ? $filepath : '../uploads/default.jpg';
                    ?>
                    <td class="px-4 py-2">
                        <img src="<?= htmlspecialchars($imagePath) ?>" class="h-16 w-16 object-cover rounded" alt="รูปสินค้า">
                    </td>

                    <td class="px-4 py-2"><?= !empty($product['name']) ? htmlspecialchars($product['name']) : 'ไม่มีข้อมูล' ?></td>
                    <td class="px-4 py-2"><?= !empty($product['category_name']) ? htmlspecialchars($product['category_name']) : 'ไม่มีข้อมูล' ?></td>
                    <td class="px-4 py-2">
                        <?= isset($product['price']) ? number_format($product['price'], 2) . ' บาท' : 'ไม่มีข้อมูล' ?>
                    </td>

                    <td class="px-4 py-2 text-center">
                        <a href="edit_product.php?id=<?= $product['product_id'] ?>" class="text-blue-600 hover:underline">แก้ไข</a> |
                        <a href="delete_product.php?id=<?= $product['product_id'] ?>" class="text-red-600 hover:underline" data-confirm>ลบ</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../includes/footer.php'; ?>
