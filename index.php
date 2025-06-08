<?php
require_once 'includes/db.php';
session_start();
$title = "หน้าแรก";
require_once 'includes/header-front.php';

$category_id = $_GET['category'] ?? null;

// ดึงข้อมูลจากฐานข้อมูล
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$products = $pdo->query("SELECT * FROM products ORDER BY product_id DESC LIMIT 4")->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Hero Section -->
<section class="relative bg-cover bg-center h-96 flex items-center justify-center text-center text-white"
  style="background-image: url('assets/banner-curry.jpg');">
  <div class="absolute inset-0 bg-black bg-opacity-50"></div>
  <div class="relative z-10">
    <h2 class="text-5xl font-bold mb-4 animate-fade-in text-white">ยินดีต้อนรับสู่ร้านเครื่องแกงชุมชนใสหลวง</h2>
    <p class="text-lg animate-fade-in">สดใหม่จากชุมชนบ้านใสหลวง ทุกคำคือความตั้งใจ</p>
    <a href="products.php" class="mt-6 inline-block bg-yellow-500 text-white px-6 py-2 rounded-lg shadow hover:bg-yellow-400 transition animate-fade-in">
      เลือกซื้อสินค้า
    </a>
  </div>
</section>

<!-- จุดเด่นของร้าน -->
<section class="py-12 bg-yellow-50 text-center">
  <h3 class="text-2xl font-bold mb-8">ทำไมต้องเครื่องแกงใสหลวง?</h3>
  <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-6xl mx-auto text-[#5C3A21] animate-fade-in">
    <div class="bg-white shadow-md rounded-lg p-4">🌶 สดใหม่ทุกวัน</div>
    <div class="bg-white shadow-md rounded-lg p-4">👩‍🍳 ทำมือจากชาวบ้าน</div>
    <div class="bg-white shadow-md rounded-lg p-4">🚚 ส่งทั่วไทย</div>
    <div class="bg-white shadow-md rounded-lg p-4">📜 สูตรดั้งเดิมแท้</div>
  </div>
</section>

<!-- สินค้าขายดี -->
<section class="py-12 bg-gray-50">
  <div class="max-w-7xl mx-auto px-4">
    <h3 class="text-2xl font-bold mb-6 text-center">สินค้าขายดี</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 animate-fade-in">
      <?php foreach ($products as $p): ?>
        <div class="bg-white shadow-lg rounded-2xl overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1">
          <img src="uploads/<?= htmlspecialchars($p['image'] ?: 'default.jpg') ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="h-64 w-full object-cover">
          <div class="p-5 space-y-2">
            <h4 class="font-bold text-lg text-[#5C3A21]"><?= htmlspecialchars($p['name']) ?></h4>
            <p class="text-[#A47551] font-bold text-md">฿<?= number_format($p['price'], 2) ?></p>
            <?php if ($p['stock'] < 5 && $p['stock'] > 0): ?>
              <span class="inline-flex items-center bg-red-500 text-white text-xs px-2 py-1 rounded">
                ❗ ใกล้หมด (<?= $p['stock'] ?>)
              </span>
            <?php elseif ($p['stock'] == 0): ?>
              <span class="inline-flex items-center bg-red-500 text-white text-xs px-2 py-1 rounded">
                ❌ หมด
              </span>
            <?php else: ?>
              <span class="inline-flex items-center bg-green-500 text-white text-xs px-2 py-1 rounded">
                ✅ มีสินค้า (<?= $p['stock'] ?>)
              </span>
            <?php endif; ?>
            <a href="product_detail.php?id=<?= $p['product_id'] ?>" class="bg-yellow-500 hover:bg-yellow-400 text-white px-4 py-2 rounded w-full inline-block text-center">ดูรายละเอียด</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- หมวดหมู่สินค้า -->
<section class="py-12 max-w-7xl mx-auto px-4 text-center">
  <h3 class="text-2xl font-bold mb-6">หมวดหมู่สินค้า</h3>
  <div class="grid grid-cols-2 md:grid-cols-4 gap-6 animate-fade-in">
    <?php foreach ($categories as $cat): ?>
      <a href="products.php?category=<?= $cat['category_id'] ?>"
        class="px-4 py-2 rounded-full border text-sm font-semibold <?= $category_id == $cat['category_id'] ? 'bg-yellow-500 text-white' : 'bg-white text-gray-700' ?>">
        <?= htmlspecialchars($cat['name']) ?>
      </a>
    <?php endforeach; ?>
  </div>
</section>
 <!-- Footer -->
<footer class="bg-gray-800 text-white py-4">
  <div class="max-w-6xl mx-auto px-4">
    <!-- ส่วนข้อมูลหลัก -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
      <!-- เกี่ยวกับเรา -->
      <div>
        <h3 class="text-xl font-bold mb-4">เกี่ยวกับเรา</h3>
        <h3 class="text-gray-400">เราคือร้านเครื่องแกงชุมชนบ้านใสหลวง<p>
        จำหน่ายเครื่องแกงสดตำมือ หอม เข้มข้น ส่งตรงจากครัวชุมชนถึงบ้านคุณ ปรุงง่าย ได้รสแท้แบบใต้</h3>
      </div>
      <!-- ติดต่อเรา -->
      <div>
        <h3 class="text-xl font-bold mb-4">ติดต่อเรา</h3>
        <ul class="text-gray-400">
          <li class="mb-2">โทร: 098-254-8215</li>
          <li class="mb-2">ที่อยู่: บ้านเลขที่ 135 หมู่ 10 ตำบลปันแต อำเภอควนขนุน จังหวัดพัทลุง</li>
        </ul>
      </div>
      <!-- ติดตามเรา -->
      <div>
        <h3 class="text-xl font-bold mb-4">ติดตามเรา</h3>
        <div class="flex space-x-4">
          <!-- Facebook -->
        <a href="https://www.facebook.com/share/16YJXR2JDv/?mibextid=wwXIfr" target="_blank">
        <img src="https://cdn-icons-png.flaticon.com/512/733/733547.png" alt="Facebook" class="w-6 h-6">
      </a>
        </div>
      </div>
    </div>

<?php require_once 'includes/footer-front.php'; ?>