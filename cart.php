<?php
session_start();
require_once 'includes/db.php';
$title = "ตะกร้าสินค้า";
require_once 'includes/header-front.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;

// ดึงข้อมูลสินค้าในตะกร้าจาก DB
$cart_items = [];
if (!empty($cart)) {
    $ids = implode(',', array_keys($cart));
    $stmt = $pdo->query("SELECT * FROM products WHERE product_id IN ($ids)");
    $cart_items = $stmt->fetchAll(PDO::FETCH_UNIQUE);
}
?>

<section class="max-w-screen-xl mx-auto px-4 py-12">
    <h1 class="text-2xl font-bold mb-6">ตะกร้าสินค้า</h1>

    <?php if (empty($cart_items)): ?>
        <p class="text-gray-600">ตะกร้าสินค้าว่างเปล่า</p>
        <a href="products.php" class="inline-block mt-4 text-yellow-600 hover:underline">← กลับไปเลือกสินค้า</a>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow rounded">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="px-4 py-3">สินค้า</th>
                        <th class="px-4 py-3">จำนวน</th>
                        <th class="px-4 py-3">ราคาต่อหน่วย</th>
                        <th class="px-4 py-3">รวม</th>
                        <th class="px-4 py-3 text-center">ลบ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $product_id => $item):
                        $product = $cart_items[$product_id] ?? null;
                        if (!$product) continue;
                        $subtotal = $item['quantity'] * $product['price'];
                        $total += $subtotal;
                    ?>
                        <tr class="border-t">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-4">
                                    <img src="uploads/<?= htmlspecialchars($product['image'] ?? 'default.jpg') ?>"
                                        alt="<?= htmlspecialchars($product['name']) ?>"
                                        class="w-16 h-16 object-cover rounded shadow">
                                    <span class="font-medium"><?= htmlspecialchars($product['name']) ?></span>
                                </div>
                            </td>

                            <!-- ปรับจำนวน -->
                            <td class="px-4 py-3">
                                <form method="post" action="update_cart.php" class="update-form flex items-center gap-2">
                                    <input type="hidden" name="product_id" value="<?= $product_id ?>">
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1"
                                        class="w-16 border border-gray-300 rounded px-2 py-1 text-center quantity-input">
                                </form>
                            </td>

                            <td class="px-4 py-3">฿<?= number_format($product['price'], 2) ?></td>
                            <td class="px-4 py-3 font-semibold">฿<?= number_format($subtotal, 2) ?></td>
                            <td class="px-4 py-3 text-center">
                                <a href="remove_from_cart.php?id=<?= $product_id ?>" class="text-red-600 hover:text-red-800" title="ลบสินค้า">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5 inline-block">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7v10m6-10v10M5 7h14l-1 12a2 2 0 01-2 2H8a2 2 0 01-2-2L5 7z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="text-right mt-6 text-lg font-bold">
            ยอดรวม: ฿<?= number_format($total, 2) ?>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row-reverse justify-between gap-4">
            <a href="checkout.php" class="btn btn-primary">ดำเนินการชำระเงิน</a>
            <a href="products.php" class="btn btn-neutral">← เลือกซื้อสินค้าต่อ</a>
        </div>
    <?php endif; ?>
</section>
<script>
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', () => {
            const form = input.closest('.update-form');
            if (form) form.submit();
        });
    });
</script>

<?php require_once 'includes/footer-front.php'; ?>