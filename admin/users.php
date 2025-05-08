<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}
$title = "จัดการสมาชิก";
require_once '../includes/header.php';

$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h1 class="text-2xl font-bold mb-4">จัดการสมาชิก</h1>

<a href="add_user.php" class="mb-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ เพิ่มผู้ใช้</a>

<div class="overflow-x-auto">
    <table class="min-w-full bg-white shadow rounded">
        <thead class="bg-gray-200">
            <tr>
                <th class="px-4 py-2 text-left">ลำดับ</th>
                <th class="px-4 py-2 text-left">ชื่อผู้ใช้</th>
                <th class="px-4 py-2 text-left">อีเมล</th>
                <th class="px-4 py-2 text-left">สิทธิ์</th>
                <th class="px-4 py-2 text-left">วันที่สมัคร</th>
                <th class="px-4 py-2 text-center">จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php $index = 1; ?>
            <?php foreach ($users as $user): ?>
            <tr class="border-t">
                <td class="px-4 py-2"><?= $index++ ?></td>
                <td class="px-4 py-2"><?= !empty($user['username']) ? htmlspecialchars($user['username']) : 'ไม่มีข้อมูล' ?></td>
                <td class="px-4 py-2"><?= !empty($user['email']) ? htmlspecialchars($user['email']) : 'ไม่มีข้อมูล' ?></td>
                <td class="px-4 py-2"><?= !empty($user['role']) ? htmlspecialchars($user['role']) : 'ไม่มีข้อมูล' ?></td>
                <td class="px-4 py-2"><?= !empty($user['created_at']) ? $user['created_at'] : 'ไม่มีข้อมูล' ?></td>
                <td class="px-4 py-2 text-center">
                    <a href="edit_user.php?id=<?= $user['user_id'] ?>" class="text-indigo-600 hover:underline">แก้ไข</a> |
                    <a href="delete_user.php?id=<?= $user['user_id'] ?>" class="text-red-600 hover:underline" data-confirm>ลบ</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once '../includes/footer.php'; ?>
