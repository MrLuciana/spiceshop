<?php
session_start();
require_once 'includes/db.php';
$title = "อัปโหลดสลิป";

if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}

$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
  $_SESSION['flash'] = ['error' => 'ไม่พบคำสั่งซื้อ'];
  header("Location: index.php");
  exit;
}

// ดึงข้อมูลคำสั่งซื้อ
$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ? AND user_id = ?");
$stmt->execute([$order_id, $_SESSION['user']['user_id']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order || $order['status'] !== 'pending') {
  $_SESSION['flash'] = ['error' => 'คำสั่งซื้อนี้ไม่สามารถอัปโหลดสลิปได้'];
  header("Location: index.php");
  exit;
}

require_once 'includes/header-front.php';
?>

<section class="max-w-lg mx-auto px-4 sm:px-6 py-12">
  <h1 class="text-2xl sm:text-3xl font-bold mb-6 text-center">ชำระเงินด้วย QR Code</h1>

  <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg space-y-8">
    <div class="text-center">
      <p class="mb-2 text-gray-600 text-base sm:text-lg">ยอดชำระ:</p>
      <p class="text-xl sm:text-2xl font-bold text-green-600 mb-4">฿<?= number_format($order['total_amount'], 2) ?></p>

      <img src="assets/qr-payment.png" alt="QR Code"
           class="mx-auto w-full max-w-xs aspect-[1/1] border rounded shadow" />
      <p class="text-sm mt-3 text-gray-500">กรุณาสแกน QR Code เพื่อทำการชำระเงิน</p>
    </div>

    <form action="confirm_payment.php" method="post" enctype="multipart/form-data" class="space-y-6">
      <input type="hidden" name="order_id" value="<?= $order_id ?>">

      <div>
        <label class="block font-medium mb-2 text-[#5C3A21] text-sm sm:text-base">
          อัปโหลดสลิปชำระเงิน <span class="text-red-500">*</span>
        </label>
        <label class="flex items-center justify-between px-4 py-3 border border-dashed border-gray-400 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
          <span class="text-sm text-gray-600">เลือกไฟล์ .JPG / .PNG</span>
          <input type="file" name="slip" accept="image/*" required class="hidden" onchange="previewImage(event)">
        </label>

        <!-- Preview -->
        <div class="mt-4">
          <img id="preview" src="#" alt="Preview"
               class="hidden w-full max-w-xs mx-auto border rounded shadow">
        </div>
      </div>

      <div class="text-right">
        <button type="submit" class="btn btn-primary inline-flex items-center gap-2 px-6 py-2 text-sm sm:text-base">
          ส่งหลักฐานการชำระเงิน
        </button>
      </div>
    </form>
  </div>
</section>

<script>
  function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('preview');
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        preview.src = e.target.result;
        preview.classList.remove('hidden');
      };
      reader.readAsDataURL(input.files[0]);
    }
  }
</script>

<?php require_once 'includes/footer-front.php'; ?>