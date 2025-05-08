<?php
require_once 'includes/db.php';
session_start();
$title = "รายละเอียดสินค้า";
require_once 'includes/header-front.php';

$product_id = $_GET['id'] ?? null;
if (!$product_id) {
  echo "<p class='text-center text-red-600 mt-10'>ไม่พบสินค้า</p>";
  require_once 'includes/footer-front.php';
  exit;
}

// ดึงสินค้าหลัก
$stmt = $pdo->prepare("SELECT p.*, c.name AS category_name FROM products p 
                       LEFT JOIN categories c ON p.category_id = c.category_id 
                       WHERE p.product_id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
  echo "<p class='text-center text-red-600 mt-10'>ไม่พบสินค้า</p>";
  require_once 'includes/footer-front.php';
  exit;
}

// ดึงสินค้าใกล้เคียง
$stmt_related = $pdo->prepare("SELECT * FROM products WHERE category_id = ? AND product_id != ? ORDER BY RAND() LIMIT 4");
$stmt_related->execute([$product['category_id'], $product_id]);
$related = $stmt_related->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="max-w-screen-xl mx-auto px-4 py-12">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start">
    <!-- รูปสินค้า -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
      <img src="uploads/<?= htmlspecialchars($product['image'] ?: 'default.jpg') ?>"
           alt="<?= htmlspecialchars($product['name']) ?>"
           class="w-full h-[400px] object-cover">
    </div>

    <!-- รายละเอียดสินค้า -->
    <div class="space-y-4 animate-fade-in">
      <span class="inline-block bg-yellow-400 text-white text-xs font-bold px-3 py-1 rounded-full">
        <?= htmlspecialchars($product['category_name']) ?>
      </span>

      <h1 class="text-3xl font-bold text-[#5C3A21]"><?= htmlspecialchars($product['name']) ?></h1>
      <p class="text-[#A47551] text-2xl font-bold">฿<?= number_format($product['price'], 2) ?></p>

      <?php if (($product['stock'] ?? 0) <= 0): ?>
        <p class="text-sm text-red-600 font-medium">❌ สินค้าหมด</p>
      <?php elseif ($product['stock'] <= 5): ?>
        <p class="text-sm text-orange-500 font-medium">❗ ใกล้หมด (เหลือ <?= $product['stock'] ?> ชิ้น)</p>
      <?php else: ?>
        <p class="text-sm text-green-600 font-medium">✅ มีสินค้าในสต๊อก (<?= $product['stock'] ?>)</p>
      <?php endif; ?>

      <p class="text-gray-700 leading-relaxed">
        <?= nl2br(htmlspecialchars($product['description'] ?: 'ไม่มีรายละเอียดสินค้า')) ?>
      </p>

      <!-- เพิ่มลงตะกร้า -->
      <?php if ($product['stock'] > 0): ?>
        <form method="post" action="add_to_cart.php" class="space-y-3 mt-4">
          <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
          <label class="block text-sm font-medium">จำนวน</label>
          <input type="number" name="quantity" value="1" min="1"
                 class="w-24 border border-gray-300 rounded px-3 py-2">

          <div class="flex flex-col sm:flex-row gap-4 mt-4">
            <button type="submit" class="btn btn-primary w-full sm:w-auto">เพิ่มลงตะกร้า</button>
            <a href="products.php" class="btn btn-neutral w-full sm:w-auto">← กลับไปหน้ารวมสินค้า</a>
          </div>
        </form>
      <?php else: ?>
        <p class="mt-4 text-red-500 font-semibold">ไม่สามารถเพิ่มลงตะกร้าได้ (สินค้าหมด)</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- สินค้าใกล้เคียง -->
<?php if ($related): ?>
<section class="max-w-screen-xl mx-auto px-4 py-12">
  <h2 class="text-xl font-bold mb-6">สินค้าใกล้เคียง</h2>
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
    <?php foreach ($related as $item): ?>
      <a href="product_detail.php?id=<?= $item['product_id'] ?>" class="block group">
        <div class="bg-white rounded-xl overflow-hidden shadow hover:shadow-lg transition transform hover:-translate-y-1">
          <img src="uploads/<?= htmlspecialchars($item['image'] ?: 'default.jpg') ?>" class="w-full h-48 object-cover">
          <div class="p-4 space-y-1">
            <h3 class="font-medium text-[#5C3A21]"><?= htmlspecialchars($item['name']) ?></h3>
            <p class="text-[#A47551] font-bold">฿<?= number_format($item['price'], 2) ?></p>
          </div>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<?php require_once 'includes/footer-front.php'; ?>

<!-- SweetAlert -->
<?php if (!empty($_SESSION['flash'])):
  $flash = $_SESSION['flash'];
  unset($_SESSION['flash']);
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  Swal.fire({
    icon: '<?= isset($flash['success']) ? 'success' : 'error' ?>',
    html: '<?= $flash['success'] ?? $flash['error'] ?>',
    confirmButtonText: 'ตกลง',
    timer: 2000,
    timerProgressBar: true
  });
</script>
<?php endif; ?>
