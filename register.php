<?php
session_start();
require_once 'includes/db.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirm = $_POST['confirm'] ?? '';

  if (!$username || !$email || !$password || !$confirm) {
    $error = 'กรุณากรอกข้อมูลให้ครบถ้วน';
  } elseif ($password !== $confirm) {
    $error = 'รหัสผ่านไม่ตรงกัน';
  } else {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
      $error = 'ชื่อผู้ใช้หรืออีเมลนี้มีอยู่แล้ว';
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role, created_at) VALUES (?, ?, ?, 'customer', NOW())");
      $stmt->execute([$username, $email, $hash]);
      $success = 'สมัครสมาชิกเรียบร้อยแล้ว! คุณสามารถเข้าสู่ระบบได้ทันที';
    }
  }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <title>สมัครสมาชิก</title>
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
    <div class="text-center">
      <h1 class="text-2xl font-bold text-[#5C3A21] mb-1">สมัครสมาชิก</h1>
      <p class="text-sm text-gray-600">สร้างบัญชีใหม่สำหรับร้านเครื่องแกงชุมชน</p>
    </div>

    <?php if ($error): ?>
      <script>
        alertError("<?= htmlspecialchars($error) ?>");
      </script>
    <?php elseif ($success): ?>
      <script>
        alertSuccess("<?= htmlspecialchars($success) ?>", "login.php");
      </script>
    <?php endif; ?>


    <form method="post" class="space-y-4">
      <div>
        <label class="block text-sm mb-1 text-[#5C3A21]">ชื่อผู้ใช้</label>
        <input type="text" name="username" required class="w-full border border-gray-300 rounded px-3 py-2">
      </div>
      <div>
        <label class="block text-sm mb-1 text-[#5C3A21]">อีเมล</label>
        <input type="email" name="email" required class="w-full border border-gray-300 rounded px-3 py-2">
      </div>
      <div>
        <label class="block text-sm mb-1 text-[#5C3A21]">รหัสผ่าน</label>
        <input type="password" name="password" required class="w-full border border-gray-300 rounded px-3 py-2">
      </div>
      <div>
        <label class="block text-sm mb-1 text-[#5C3A21]">ยืนยันรหัสผ่าน</label>
        <input type="password" name="confirm" required class="w-full border border-gray-300 rounded px-3 py-2">
      </div>
      <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-400 text-white font-semibold py-2 rounded transition">
        สมัครสมาชิก
      </button>
    </form>

    <div class="text-center text-sm text-gray-600">
      มีบัญชีอยู่แล้ว? <a href="login.php" class="text-yellow-600 hover:underline font-medium">เข้าสู่ระบบ</a>
    </div>
  </div>
</body>

</html>