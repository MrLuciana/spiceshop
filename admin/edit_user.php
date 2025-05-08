<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("ไม่พบผู้ใช้");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE user_id = ?");
    $stmt->execute([$username, $email, $role, $id]);

    $_SESSION['flash'] = ['success' => 'แก้ไขผู้ใช้เรียบร้อยแล้ว'];
    header("Location: users.php");
    exit;
}
$title = "แก้ไขผู้ใช้";
require_once '../includes/header.php';
?>
<h1 class="text-2xl font-bold mb-4">แก้ไขผู้ใช้</h1>
<form method="post" class="space-y-4">
    <div>
        <label class="block mb-1">ชื่อผู้ใช้:</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required class="w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block mb-1">อีเมล:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required class="w-full border rounded px-3 py-2">
    </div>
    <div>
        <label class="block mb-1">สิทธิ์:</label>
        <select name="role" required class="w-full border rounded px-3 py-2">
            <option value="customer" <?= $user['role'] === 'customer' ? 'selected' : '' ?>>ลูกค้า</option>
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>แอดมิน</option>
        </select>
    </div>
    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">บันทึกการแก้ไข</button>
</form>
<?php require_once '../includes/footer.php'; ?>
