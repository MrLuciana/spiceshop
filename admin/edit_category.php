<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM categories WHERE category_id = ?");
$stmt->execute([$id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    die("ไม่พบหมวดหมู่");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];

    $stmt = $pdo->prepare("UPDATE categories SET name = ?, description = ? WHERE category_id = ?");
    $stmt->execute([$name, $desc, $id]);

    $_SESSION['flash'] = ['success' => 'แก้ไขหมวดหมู่เรียบร้อยแล้ว'];
    header("Location: categories.php");
    exit;
}
$title = "แก้ไขหมวดหมู่";
require_once '../includes/header.php';
?>
<h1 class="text-2xl font-bold mb-4">แก้ไขหมวดหมู่</h1>
<form method="post" class="space-y-4">
    <div>
        <label class="block mb-1">ชื่อหมวดหมู่:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($category['name']) ?>" required class="w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block mb-1">คำอธิบาย:</label>
        <textarea name="description" class="w-full border rounded px-3 py-2"><?= htmlspecialchars($category['description']) ?></textarea>
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">บันทึกการแก้ไข</button>
</form>
<?php require_once '../includes/footer.php'; ?>
