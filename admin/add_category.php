<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];

    $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
    $stmt->execute([$name, $desc]);

    $_SESSION['flash'] = ['success' => 'เพิ่มหมวดหมู่เรียบร้อยแล้ว'];
    header("Location: categories.php");
    exit;
}
$title = "เพิ่มหมวดหมู่";
require_once '../includes/header.php';
?>
<h1 class="text-2xl font-bold mb-4">เพิ่มหมวดหมู่</h1>
<form method="post" class="space-y-4">
    <div>
        <label class="block mb-1">ชื่อหมวดหมู่:</label>
        <input type="text" name="name" required class="w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block mb-1">คำอธิบาย:</label>
        <textarea name="description" class="w-full border rounded px-3 py-2"></textarea>
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">บันทึก</button>
</form>
<?php require_once '../includes/footer.php'; ?>
