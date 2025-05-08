<?php
session_start();
require_once 'includes/db.php';
$title = "โปรไฟล์ของฉัน";

if (!isset($_SESSION['user'])) {
    $_SESSION['flash'] = ['error' => 'กรุณาเข้าสู่ระบบก่อนใช้งานหน้านี้'];
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user']['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// ดึงคำสั่งซื้อ
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once 'includes/header-front.php';
?>

<section class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Sidebar -->
        <aside class="space-y-4">
            <div class="bg-white shadow rounded-lg p-4 text-center">
                <div class="w-20 h-20 mx-auto bg-yellow-100 text-yellow-600 flex items-center justify-center rounded-full text-3xl font-bold">
                    <?= strtoupper(substr($user['username'], 0, 1)) ?>
                </div>
                <p class="mt-2 font-semibold"><?= htmlspecialchars($user['username']) ?></p>
                <p class="text-sm text-gray-500"><?= htmlspecialchars($user['email']) ?></p>
            </div>
            <nav class="bg-white shadow rounded-lg p-4 space-y-2 text-sm">
                <!-- <a href="#" class="block text-[#5C3A21] font-medium text-black">📄 ข้อมูลโปรไฟล์</a>
                <a href="#" class="block text-[#5C3A21] font-medium text-black">🧾 คำสั่งซื้อของฉัน</a> -->
                <a href="logout.php" class="block text-red-600 font-medium hover:underline">🚪 ออกจากระบบ</a>
            </nav>
        </aside>

        <!-- Content -->
        <div class="md:col-span-3 space-y-10">
            <!-- ข้อมูลโปรไฟล์ -->
            <div class="bg-white shadow rounded-xl p-6">
                <h2 class="text-xl font-bold mb-4">ข้อมูลผู้ใช้</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                    <div>
                        <p class="text-gray-500">ชื่อผู้ใช้</p>
                        <p class="font-semibold"><?= htmlspecialchars($user['username']) ?></p>
                    </div>
                    <div>
                        <p class="text-gray-500">อีเมล</p>
                        <p class="font-semibold"><?= htmlspecialchars($user['email']) ?></p>
                    </div>
                    <div>
                        <p class="text-gray-500">สิทธิ์</p>
                        <p class="font-semibold"><?= $user['role'] === 'admin' ? 'แอดมิน' : 'ลูกค้า' ?></p>
                    </div>
                    <div>
                        <p class="text-gray-500">สมัครเมื่อ</p>
                        <p class="font-semibold"><?= date('d M Y', strtotime($user['created_at'])) ?></p>
                    </div>
                </div>
            </div>

            <!-- ประวัติคำสั่งซื้อ -->
            <div class="bg-white shadow rounded-xl p-6">
                <h2 class="text-xl font-bold mb-4">ประวัติคำสั่งซื้อ</h2>

                <?php if (empty($orders)): ?>
                    <p class="text-gray-500">คุณยังไม่มีรายการสั่งซื้อ</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left">วันที่</th>
                                    <th class="px-4 py-3 text-left">ยอดรวม</th>
                                    <th class="px-4 py-3 text-left">สถานะ</th>
                                    <th class="px-4 py-3 text-center">การดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $o): ?>
                                    <tr class="border-t">
                                        <td class="px-4 py-2"><?= date('d/m/Y H:i', strtotime($o['order_date'])) ?></td>
                                        <td class="px-4 py-2 text-green-700 font-semibold">฿<?= number_format($o['total_amount'], 2) ?></td>
                                        <td class="px-4 py-2">
                                            <span class="inline-block px-2 py-1 rounded text-xs font-medium 
                                                <?= match ($o['status']) {
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'waiting_payment_verify' => 'bg-blue-100 text-blue-800',
                                                    'paid' => 'bg-green-100 text-green-800',
                                                    'shipped' => 'bg-indigo-100 text-indigo-800',
                                                    'delivered' => 'bg-gray-100 text-gray-700',
                                                    'cancelled' => 'bg-red-100 text-red-700',
                                                    default => 'bg-gray-100 text-gray-600'
                                                } ?>">
                                                <?php
                                                $statusText = match ($o['status']) {
                                                    'pending' => 'รอชำระเงิน',
                                                    'waiting_payment_verify' => 'รอตรวจสอบ',
                                                    'paid' => 'ชำระเงินแล้ว',
                                                    'shipped' => 'กำลังจัดส่ง',
                                                    'delivered' => 'จัดส่งสำเร็จ',
                                                    'cancelled' => 'ยกเลิกแล้ว',
                                                    default => 'ไม่ทราบสถานะ'
                                                };
                                                ?>
                                                <?= $statusText ?>

                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-center space-y-1">
                                            <a href="view_order.php?id=<?= $o['order_id'] ?>" class="text-blue-600 hover:underline text-sm block">ดู</a>
                                            <?php if ($o['status'] === 'pending'): ?>
                                                <a href="upload_payment.php?order_id=<?= $o['order_id'] ?>" class="text-yellow-600 hover:underline text-sm block">ชำระเงิน</a>
                                            <?php endif; ?>
                                            <?php if (!empty($o['payment_slip']) && $o['status'] !== 'pending'): ?>
                                                <a href="uploads/<?= $o['payment_slip'] ?>" target="_blank" class="text-green-600 hover:underline text-sm block">ดูสลิป</a>
                                            <?php endif; ?>
                                            <?php if (in_array($o['status'], ['paid', 'shipped', 'delivered'])): ?>
                                                <a href="invoice.php?order_id=<?= $o['order_id'] ?>" class="text-indigo-600 hover:underline text-sm block">พิมพ์ใบเสร็จ</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer-front.php'; ?>