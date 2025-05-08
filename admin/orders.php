<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}
$title = "จัดการคำสั่งซื้อ";
require_once '../includes/header.php';

$stmt = $pdo->query("SELECT o.*, u.username FROM orders o LEFT JOIN users u ON o.user_id = u.user_id ORDER BY o.order_id DESC");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h1 class="text-2xl font-bold mb-4">จัดการคำสั่งซื้อ</h1>

<div class="overflow-x-auto">
    <table class="min-w-full bg-white shadow rounded">
        <thead class="bg-gray-200">
            <tr>
                <th class="px-4 py-2 text-left">ลำดับ</th>
                <th class="px-4 py-2 text-left">ผู้สั่ง</th>
                <th class="px-4 py-2 text-left">วันที่</th>
                <th class="px-4 py-2 text-left">ยอดรวม</th>
                <th class="px-4 py-2 text-left">สถานะ</th>
                <th class="px-4 py-2 text-left">จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php $index = 1; ?>
            <?php foreach ($orders as $order): ?>
            <tr class="border-t">
                <td class="px-4 py-2"><?= $index++ ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($order['username'] ?? 'ไม่มีข้อมูล') ?></td>
                <td class="px-4 py-2"><?= $order['order_date'] ?? 'ไม่มีข้อมูล' ?></td>
                <td class="px-4 py-2">฿<?= number_format($order['total_amount'], 2) ?></td>
                <td class="px-4 py-2">
                    <form method="post" action="update_order_status.php" class="inline">
                        <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                        <select name="status" onchange="setSelectColor(this); this.form.submit();" class="border rounded px-2 py-1 text-white font-semibold">
                            <?php foreach (['pending', 'processing', 'shipped', 'delivered'] as $status): ?>
                                <option value="<?= $status ?>" <?= $order['status'] === $status ? 'selected' : '' ?>>
                                    <?= ucfirst($status) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </td>
                <td class="px-4 py-2">
                    <button onclick="viewOrder(<?= $order['order_id'] ?>)" class="text-blue-600 hover:underline">ดูรายละเอียด</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="orderModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded shadow-lg max-w-2xl w-full relative">
        <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-black">✕</button>
        <div id="modalContent">กำลังโหลด...</div>
    </div>
</div>

<script>
function viewOrder(id) {
    const modal = document.getElementById('orderModal');
    const content = document.getElementById('modalContent');
    modal.classList.remove('hidden');
    fetch('view_order_api.php?id=' + id)
        .then(res => res.text())
        .then(html => {
            content.innerHTML = html;
        });
}
function closeModal() {
    document.getElementById('orderModal').classList.add('hidden');
}

function setSelectColor(select) {
    const statusColors = {
        pending: 'bg-gray-400',
        processing: 'bg-blue-500',
        shipped: 'bg-yellow-500',
        delivered: 'bg-green-600'
    };
    select.className = 'border rounded px-2 py-1 text-white font-semibold';
    const selected = select.value;
    if (statusColors[selected]) {
        select.classList.add(statusColors[selected]);
    }
}
// Apply colors on load
document.querySelectorAll('select[name=status]').forEach(setSelectColor);
</script>

<?php require_once '../includes/footer.php'; ?>
