<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("ไม่พบสินค้า");
}

$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    $image = $product['image'];
    if (!empty($_FILES['image']['name'])) {
        $target_dir = '../uploads/';
        $filename = time() . '_' . basename($_FILES['image']['name']);
        $target_file = $target_dir . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = $filename;
        }
    }
    $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, category_id = ?, image = ? WHERE product_id = ?");
    $stmt->execute([$name, $desc, $price, $category_id, $image, $id]);

    $_SESSION['flash'] = ['success' => 'แก้ไขสินค้าสำเร็จ'];
    header("Location: products.php");
    exit;
}
$title = "แก้ไขสินค้า";
require_once '../includes/header.php';
?>
<h1 class="text-2xl font-bold mb-4">แก้ไขสินค้า</h1>
<form method="post" enctype="multipart/form-data" class="space-y-4">
    <div>
        <label class="block mb-1">ชื่อสินค้า:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required class="w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block mb-1">รายละเอียด:</label>
        <textarea name="description" class="w-full border rounded px-3 py-2"><?= htmlspecialchars($product['description']) ?></textarea>
    </div>
    <div>
        <label class="block mb-1">ราคา:</label>
        <input type="number" name="price" step="0.01" value="<?= $product['price'] ?>" required class="w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block mb-1">หมวดหมู่:</label>
        <select name="category_id" required class="w-full border rounded px-3 py-2">
            <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['category_id'] ?>" <?= $product['category_id'] == $cat['category_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php if (!empty($product['image'])): ?>
    <div>
        <label class="block mb-1">รูปภาพปัจจุบัน:</label>
        <img src="../uploads/<?= htmlspecialchars($product['image']) ?>" alt="Product Image" class="h-24 mb-2">
    </div>
    <?php endif; ?>
    <div>
        <label class="block mb-1">เปลี่ยนรูปภาพ:</label>
        <input type="file" name="image" accept="image/*" class="w-full border rounded px-3 py-2">
    </div>
    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">บันทึกการแก้ไข</button>
</form>
<?php require_once '../includes/footer.php'; ?>
