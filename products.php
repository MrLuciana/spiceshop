<?php
require_once 'includes/db.php';
session_start();
$title = "สินค้าทั้งหมด";
require_once 'includes/header-front.php';

$category_id = $_GET['category'] ?? null;

// ดึงหมวดหมู่ทั้งหมด
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

// ดึงสินค้าตามหมวด (ถ้ามี)
if ($category_id) {
  $stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? ORDER BY product_id DESC");
  $stmt->execute([$category_id]);
} else {
  $stmt = $pdo->query("SELECT * FROM products ORDER BY product_id DESC");
}
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="max-w-screen-xl mx-auto px-4 py-10">
  <h1 class="text-3xl font-bold mb-6 text-center">สินค้าทั้งหมด</h1>

  <!-- หมวดหมู่ -->
  <div class="flex flex-wrap justify-center gap-3 mb-10">
    <a href="products.php" class="px-4 py-2 rounded-full border text-sm font-semibold <?= !$category_id ? 'bg-yellow-500 text-white' : 'bg-white text-gray-700' ?>">
      ทั้งหมด
    </a>
    <?php foreach ($categories as $cat): ?>
      <a href="products.php?category=<?= $cat['category_id'] ?>"
        class="px-4 py-2 rounded-full border text-sm font-semibold <?= $category_id == $cat['category_id'] ? 'bg-yellow-500 text-white' : 'bg-white text-gray-700' ?>">
        <?= htmlspecialchars($cat['name']) ?>
      </a>
    <?php endforeach; ?>
  </div>

  <!-- สินค้า -->
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 animate-fade-in">
    <?php foreach ($products as $p): ?>
      <a href="product_detail.php?id=<?= $p['product_id'] ?>" class="block group">
        <div class="bg-white shadow-lg rounded-2xl overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1 relative">

          <!-- รูปสินค้า -->
          <div class="relative">
            <img src="uploads/<?= htmlspecialchars($p['image'] ?: 'default.jpg') ?>"
              alt="<?= htmlspecialchars($p['name']) ?>"
              class="w-full h-48 object-cover transition duration-300 group-hover:scale-105">
            <?php if ($p['stock'] == 0): ?>
              <div class="absolute top-2 left-2 bg-red-600 text-white text-xs font-semibold px-2 py-1 rounded shadow">
                หมด
              </div>
            <?php endif; ?>
          </div>

          <!-- เนื้อหาสินค้า -->
          <div class="p-6 space-y-3">
            <h3 class="text-xl font-semibold text-[#5C3A21]"><?= htmlspecialchars($p['name']) ?></h3>
            <p class="text-[#A47551] font-bold text-lg">฿<?= number_format($p['price'], 2) ?></p>

            <?php if ($p['stock'] < 5 && $p['stock'] > 0): ?>
              <span class="inline-flex bg-red-500 text-white text-xs px-2 py-1 rounded">❗ ใกล้หมด (<?= $p['stock'] ?>)</span>
            <?php elseif ($p['stock'] == 0): ?>
              <span class="inline-flex bg-red-500 text-white text-xs px-2 py-1 rounded">❌ หมด</span>
            <?php else: ?>
              <span class="inline-flex bg-green-500 text-white text-xs px-2 py-1 rounded">✅ มีสินค้า (<?= $p['stock'] ?>)</span>
            <?php endif; ?>

            <!-- ปุ่มเพิ่มตะกร้า -->
            <form method="post" action="add_to_cart.php" onclick="event.stopPropagation()">
              <input type="hidden" name="product_id" value="<?= $p['product_id'] ?>">
              <input type="hidden" name="quantity" value="1">
              <button type="submit" class="btn btn-primary w-full mt-2">เพิ่มลงตะกร้า</button>
            </form>
          </div>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<?php require_once 'includes/footer-front.php'; ?>