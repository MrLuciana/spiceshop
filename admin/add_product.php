<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    $image = '';
    if (!empty($_FILES['image']['name'])) {
        $target_dir = '../uploads/';
        $filename = time() . '_' . basename($_FILES['image']['name']);
        $target_file = $target_dir . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = $filename;
        }
    }
    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, category_id, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $desc, $price, $category_id, $image]);

    $_SESSION['flash'] = ['success' => 'เพิ่มสินค้าสำเร็จ'];
    header("Location: products.php");
    exit;
}
$title = "เพิ่มสินค้า";
require_once '../includes/header.php';
?>
<h1 class="text-2xl font-bold mb-4">เพิ่มสินค้า</h1>
<form method="post" enctype="multipart/form-data" class="space-y-4">
    <div>
        <label class="block mb-1">ชื่อสินค้า:</label>
        <input type="text" name="name" required class="w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block mb-1">รายละเอียด:</label>
        <textarea name="description" class="w-full border rounded px-3 py-2"></textarea>
    </div>
    <div>
        <label class="block mb-1">ราคา:</label>
        <input type="number" name="price" step="0.01" required class="w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block mb-1">หมวดหมู่:</label>
        <select name="category_id" required class="w-full border rounded px-3 py-2">
            <option value="">-- เลือกหมวดหมู่ --</option>
            <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label class="block mb-1">รูปภาพสินค้า:</label>
        <input type="file" name="image" accept="image/*" class="w-full border rounded px-3 py-2">
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">เพิ่มสินค้า</button>
</form>
<?php require_once '../includes/footer.php'; ?>
