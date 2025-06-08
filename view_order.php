<?php
session_start();
require_once 'includes/db.php';
$title = "รายละเอียดคำสั่งซื้อ";

if (!isset($_SESSION['user'])) {
  $_SESSION['flash'] = ['error' => 'กรุณาเข้าสู่ระบบ'];
  header("Location: login.php");
  exit;
}

$order_id = $_GET['id'] ?? null;
$user_id = $_SESSION['user']['user_id'];

$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ? AND user_id = ?");
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
  $_SESSION['flash'] = ['error' => 'ไม่พบคำสั่งซื้อ'];
  header("Location: profile.php");
  exit;
}

// ดึงรายการสินค้า
$stmt = $pdo->prepare("SELECT oi.*, p.name, p.image FROM order_items oi 
                       JOIN products p ON oi.product_id = p.product_id
                       WHERE oi.order_id = ?");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once 'includes/header-front.php';
?>

<section class="max-w-screen-lg mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
  <div class="bg-white shadow rounded-xl p-6 space-y-4">
    <h1 class="text-2xl font-bold mb-4">คำสั่งซื้อ #<?= $order['order_id'] ?></h1>

    <div class="grid sm:grid-cols-2 gap-6 text-sm">
      <div>
        <p class="text-gray-500">วันที่สั่งซื้อ</p>
        <p class="font-semibold"><?= date('d/m/Y H:i', strtotime($order['order_date'])) ?></p>
      </div>
      <div>
        <p class="text-gray-500">สถานะ</p>
        <p class="font-semibold">
          <?php
          $statusText = match ($order['status']) {
            'pending' => 'รอชำระเงิน',
            'waiting_payment_verify' => 'รอตรวจสอบการชำระเงิน',
            'processing' => 'กำลังเตรียมสินค้า',
            'shipped' => 'กำลังจัดส่ง',
            'delivered' => 'จัดส่งสำเร็จ',
            'cancelled' => 'ยกเลิกแล้ว',
            default => 'ไม่ทราบสถานะ'
          };
          echo $statusText;
          ?>
        </p>
      </div>
      <div>
        <p class="text-gray-500">ยอดรวม</p>
        <p class="text-green-700 font-bold">฿<?= number_format($order['total_amount'], 2) ?></p>
      </div>
      <?php if (!empty($order['payment_slip'])): ?>
        <div>
          <p class="text-gray-500">หลักฐานการชำระเงิน</p>
          <button onclick="showSlipModal('uploads/slips/<?= $order['payment_slip'] ?>')" target="_blank" class="text-blue-600 hover:underline">ดูสลิป</button>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="bg-white shadow rounded-xl p-6">
    <h2 class="text-xl font-semibold mb-4">รายการสินค้า</h2>
    <div class="divide-y">
      <?php foreach ($items as $item): ?>
        <div class="py-4 flex items-center gap-4">
          <img src="uploads/<?= htmlspecialchars($item['image'] ?? 'default.jpg') ?>" class="w-16 h-16 object-cover rounded">
          <div class="flex-1">
            <p class="font-medium text-[#5C3A21]"><?= htmlspecialchars($item['name']) ?></p>
            <p class="text-sm text-gray-500">จำนวน: <?= $item['quantity'] ?> × ฿<?= number_format($item['price'], 2) ?></p>
          </div>
          <div class="text-right text-green-600 font-semibold">
            ฿<?= number_format($item['quantity'] * $item['price'], 2) ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="text-right">
    <a href="profile.php" class="btn btn-neutral">← กลับ</a>

    <?php if (in_array($order['status'], ['paid', 'shipped', 'delivered'])): ?>
      <a href="invoice.php?order_id=<?= $order['order_id'] ?>" target="_blank" class="btn btn-primary">พิมพ์ใบเสร็จ</a>
    <?php endif; ?>
  </div>
</section>

<!-- Modal ดูสลิป -->
<div id="slipModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg overflow-hidden shadow-lg max-w-sm w-full relative">
        <button onclick="closeSlipModal()" class="absolute top-2 right-2 text-gray-400 hover:text-black">✕</button>
        <div class="p-4">
            <h2 class="text-lg font-bold mb-2">หลักฐานการชำระเงิน</h2>
            <img id="slipImage" src="" alt="สลิป" class="w-full rounded shadow">
        </div>
    </div>
</div>

<?php require_once 'includes/footer-front.php'; ?>
<script>
    function showSlipModal(imageUrl) {
        const modal = document.getElementById('slipModal');
        const img = document.getElementById('slipImage');
        img.src = imageUrl;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeSlipModal() {
        const modal = document.getElementById('slipModal');
        const img = document.getElementById('slipImage');
        img.src = "";
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>