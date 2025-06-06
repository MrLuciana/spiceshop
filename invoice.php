<?php
require_once __DIR__ . '/vendor/autoload.php';

use Mpdf\Mpdf;

$defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$mpdf = new Mpdf([
  'fontDir' => array_merge($fontDirs, [__DIR__ . '/fonts']),
  'fontdata' => $fontData + [
    'thsarabunnew' => [
      'R' => 'THSarabunNew.ttf',
      'B' => 'THSarabunNew Bold.ttf',
      'I' => 'THSarabunNew Italic.ttf',
      'BI' => 'THSarabunNew BoldItalic.ttf',
    ]
  ],
  'default_font' => 'thsarabunnew'
]);

session_start();
require_once 'includes/db.php';

$order_id = $_GET['order_id'] ?? null;
if (!$order_id) {
    exit('ไม่พบคำสั่งซื้อ');
}

// ดึงข้อมูลคำสั่งซื้อ
$stmt = $pdo->prepare("SELECT o.*, u.username FROM orders o LEFT JOIN users u ON o.user_id = u.user_id WHERE o.order_id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    exit("ไม่พบคำสั่งซื้อ");
}

// ดึงรายการสินค้า
$stmt = $pdo->prepare("SELECT oi.*, p.name FROM order_items oi LEFT JOIN products p ON oi.product_id = p.product_id WHERE order_id = ?");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// สร้าง HTML สำหรับ PDF
ob_start();
?>

<h1 style="font-family: 'thsarabunnew'; text-align: center; font-size: 26pt; margin-bottom: 10px;">ใบเสร็จรับเงิน</h1>

<table width="100%" style="font-family: 'thsarabunnew'; font-size: 16pt; margin-bottom: 20px;">
  <tr>
    <td><strong>คำสั่งซื้อ:</strong> #<?= $order['order_id'] ?></td>
    <td align="right"><strong>วันที่:</strong> <?= $order['order_date'] ?></td>
  </tr>
  <tr>
    <td colspan="2"><strong>ลูกค้า:</strong> <?= htmlspecialchars($order['username']) ?></td>
  </tr>
</table>

<hr style="margin: 10px 0; border: none; border-top: 1px solid #999;">

<table width="100%" border="1" cellspacing="0" cellpadding="8" style="font-family: 'thsarabunnew'; font-size: 16pt; border-collapse: collapse; margin-top: 10px;">
  <thead style="background-color: #f2f2f2;">
    <tr>
      <th align="left">สินค้า</th>
      <th align="center">จำนวน</th>
      <th align="right">ราคาต่อหน่วย</th>
      <th align="right">รวม</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($items as $item): ?>
    <tr>
      <td><?= htmlspecialchars($item['name']) ?></td>
      <td align="center"><?= $item['quantity'] ?></td>
      <td align="right">฿<?= number_format($item['price'], 2) ?></td>
      <td align="right">฿<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<p style="text-align: right; font-family: 'thsarabunnew'; font-size: 18pt; margin-top: 20px;">
  <strong>รวมทั้งสิ้น:</strong> ฿<?= number_format($order['total_amount'], 2) ?>
</p>

<?php
$html = ob_get_clean();

// แสดง PDF
$mpdf->WriteHTML($html);
$mpdf->Output("invoice_{$order['order_id']}.pdf", 'I');