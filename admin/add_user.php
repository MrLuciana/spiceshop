<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $email, $password, $role]);

    $_SESSION['flash'] = ['success' => 'เพิ่มผู้ใช้เรียบร้อยแล้ว'];
    header("Location: users.php");
    exit;
}
$title = "เพิ่มผู้ใช้";
require_once '../includes/header.php';
?>
<h1 class="text-2xl font-bold mb-4">เพิ่มผู้ใช้</h1>
<form method="post" class="space-y-4">
    <div>
        <label class="block mb-1">ชื่อผู้ใช้:</label>
        <input type="text" name="username" required class="w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block mb-1">อีเมล:</label>
        <input type="email" name="email" required class="w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block mb-1">รหัสผ่าน:</label>
        <input type="password" name="password" required class="w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block mb-1">สิทธิ์:</label>
        <select name="role" required class="w-full border rounded px-3 py-2">
            <option value="customer">ลูกค้า</option>
            <option value="admin">แอดมิน</option>
        </select>
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">บันทึก</button>
</form>
<?php require_once '../includes/footer.php'; ?>
