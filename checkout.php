<?php
session_start();
require_once 'includes/db.php';
$title = "ชำระเงิน";

// 🔒 ต้อง login
if (!isset($_SESSION['user'])) {
  $_SESSION['flash'] = ['error' => 'กรุณาเข้าสู่ระบบก่อนทำรายการ'];
  header("Location: login.php");
  exit;
}

require_once 'includes/header-front.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;

// ดึงสินค้า
$cart_items = [];
if (!empty($cart)) {
    $ids = implode(',', array_keys($cart));
    $stmt = $pdo->query("SELECT * FROM products WHERE product_id IN ($ids)");
    $cart_items = $stmt->fetchAll(PDO::FETCH_UNIQUE);
}
?>

<section class="max-w-screen-xl mx-auto px-4 py-10">
  <h1 class="text-2xl font-bold mb-6">ชำระเงิน</h1>

  <?php if (empty($cart_items)): ?>
    <p class="text-gray-600">ไม่มีสินค้าในตะกร้า</p>
    <a href="products.php" class="btn btn-neutral mt-4">← เลือกสินค้า</a>
  <?php else: ?>
    <form method="post" action="process_order.php" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      
      <!-- รายการสินค้า (2/3) -->
      <div class="lg:col-span-2 space-y-6">
        <div class="bg-white p-6 rounded shadow">
          <h2 class="text-xl font-semibold mb-4">รายการสินค้า</h2>
          <ul class="divide-y">
            <?php foreach ($cart as $product_id => $item): 
              $product = $cart_items[$product_id] ?? null;
              if (!$product) continue;
              $subtotal = $item['quantity'] * $product['price'];
              $total += $subtotal;
            ?>
              <li class="flex items-center justify-between py-4">
                <div class="flex items-center gap-4">
                  <img src="uploads/<?= htmlspecialchars($product['image'] ?? 'default.jpg') ?>" class="w-16 h-16 rounded object-cover shadow" alt="">
                  <div>
                    <div class="font-medium"><?= htmlspecialchars($product['name']) ?></div>
                    <div class="text-sm text-gray-500">จำนวน: <?= $item['quantity'] ?></div>
                  </div>
                </div>
                <div class="text-right font-semibold">฿<?= number_format($subtotal, 2) ?></div>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- ที่อยู่และช่องทาง -->
        <div class="bg-white p-6 rounded shadow space-y-6">
          <div>
            <label class="block font-medium mb-1">ที่อยู่จัดส่ง</label>
            <textarea name="shipping_address" required rows="4"
                      class="w-full border border-gray-300 rounded px-3 py-2"
                      placeholder="กรอกที่อยู่สำหรับจัดส่งสินค้า..."></textarea>
          </div>
          <div>
            <label class="block font-medium mb-1">ช่องทางชำระเงิน</label>
            <select name="payment_method" required class="w-full border border-gray-300 rounded px-3 py-2">
              <option value="">-- เลือกช่องทาง --</option>
              <option value="qr">QR Code</option>
              <option value="cod">ชำระปลายทาง (COD)</option>
            </select>
          </div>
        </div>
      </div>

      <!-- สรุปยอด (1/3) -->
      <div class="bg-white p-6 rounded shadow h-fit">
        <h2 class="text-lg font-semibold mb-4">สรุปคำสั่งซื้อ</h2>
        <div class="flex justify-between text-sm mb-2">
          <span>ยอดรวมสินค้า</span>
          <span>฿<?= number_format($total, 2) ?></span>
        </div>
        <div class="flex justify-between text-sm mb-4">
          <span>ค่าจัดส่ง</span>
          <span>฿0.00</span>
        </div>
        <hr class="my-4">
        <div class="flex justify-between text-lg font-bold mb-6">
          <span>ยอดชำระทั้งหมด</span>
          <span>฿<?= number_format($total, 2) ?></span>
        </div>
        <button type="submit" class="btn btn-primary w-full">ยืนยันคำสั่งซื้อ</button>
      </div>

    </form>
  <?php endif; ?>
</section>

<?php require_once 'includes/footer-front.php'; ?>
