<?php
session_start();
require_once 'includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->execute([$username]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user && password_verify($password, $user['password_hash'])) {
    $_SESSION['user'] = [
      'user_id' => $user['user_id'],
      'username' => $user['username'],
      'role' => $user['role']
    ];
    if ($user['role'] === 'admin') {
      $_SESSION['admin'] = true;
      $_SESSION['flash'] = ['success' => 'เข้าสู่ระบบสำเร็จ!'];
      header("Location: admin/index.php");
    } else {
      $_SESSION['flash'] = ['success' => 'เข้าสู่ระบบสำเร็จ!'];
      header("Location: index.php");
    }
    exit;
  } else {
    $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
  }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <title>เข้าสู่ระบบ</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="assets/js/sweetalert.js"></script>

  <style>
    body {
      font-family: 'Sarabun', sans-serif;
    }
  </style>
</head>

<body class="bg-[#FDF7F1] flex items-center justify-center min-h-screen px-4">
  <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md space-y-6 animate-fade-in">

    <!-- โลโก้/หัวเรื่อง -->
    <div class="text-center">
      <h1 class="text-2xl font-bold text-[#5C3A21] mb-1">เข้าสู่ระบบ</h1>
      <p class="text-sm text-gray-600">ยินดีต้อนรับกลับสู่ร้านเครื่องแกงชุมชนใสหลวง</p>
    </div>

    <!-- แจ้งเตือน -->
    <?php if ($error): ?>
      <div class="bg-red-100 text-red-700 px-4 py-2 rounded">
        <script>
          alertError(<?= htmlspecialchars($error) ?>);
        </script>
      </div>
    <?php endif; ?>

    <!-- ฟอร์ม -->
    <form method="post" class="space-y-4">
      <div>
        <label class="block text-sm mb-1 text-[#5C3A21]">ชื่อผู้ใช้</label>
        <input type="text" name="username" required
          class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
      </div>
      <div>
        <label class="block text-sm mb-1 text-[#5C3A21]">รหัสผ่าน</label>
        <input type="password" name="password" required
          class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
      </div>
      <button type="submit"
        class="w-full bg-yellow-500 hover:bg-yellow-400 text-white font-semibold py-2 rounded transition">
        เข้าสู่ระบบ
      </button>
    </form>

    <!-- ลิงก์สมัคร -->
    <div class="text-center text-sm text-gray-600">
      ยังไม่มีบัญชี? <a href="register.php" class="text-yellow-600 hover:underline font-medium">สมัครสมาชิก</a>
    </div>
  </div>
</body>

</html>