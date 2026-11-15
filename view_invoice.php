<?php
session_start();
require_once 'db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("رابط الفاتورة غير صحيح.");
}

$invoice_id = intval($_GET['id']);

// 1. جلب بيانات الفاتورة
$stmtInv = $pdo->prepare("SELECT * FROM invoices WHERE id = ?");
$stmtInv->execute([$invoice_id]);
$invoice = $stmtInv->fetch();

if (!$invoice) {
    die("الفاتورة غير موجودة.");
}

// 2. جلب بنود الفاتورة
$stmtItems = $pdo->prepare("SELECT * FROM invoice_items WHERE invoice_id = ?");
$stmtItems->execute([$invoice_id]);
$items = $stmtItems->fetchAll();

// 3. جلب بيانات العميل
$stmtClient = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
$stmtClient->execute([$invoice['client_id']]);
$client = $stmtClient->fetch();

// 4. جلب مفتاح باي بال الخاص بالمدير الذي أصدر الفاتورة (تصحيح المتغير إلى $invoice)
$stmtAdmin = $pdo->prepare("SELECT paypal_client_id FROM admins WHERE id = ?");
$stmtAdmin->execute([$invoice['admin_id']]);
$adminData = $stmtAdmin->fetch();
$paypal_client_id = !empty($adminData['paypal_client_id']) ? $adminData['paypal_client_id'] : 'BAAZLEm2CBOeQ_lpX79u4OSdzz1YhzXKYAZxdFaoKE6CIYQiXYWUkR45g5CT_1NQ191mKkhNgTMtBdL8s0';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض الفاتورة | <?= htmlspecialchars($invoice['reference_number']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap');
        body { font-family: 'Tajawal', sans-serif; background: #0f172a; color: #f8fafc; }
        .glass-panel { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">

    <div class="max-w-3xl w-full glass-panel p-8 rounded-3xl bg-slate-900 shadow-2xl">
        
        <!-- رأس الفاتورة -->
        <div class="flex justify-between items-start border-b border-slate-800 pb-6 mb-6">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">فاتورة ضريبية</h1>
                <p class="text-slate-400">رقم: <span class="font-mono text-blue-400" dir="ltr"><?= htmlspecialchars($invoice['reference_number']) ?></span></p>
            </div>
            <div class="text-left">
                <span class="px-4 py-2 rounded-full text-sm font-bold 
                    <?= $invoice['status'] == 'paid' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : ($invoice['status'] == 'overdue' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 'bg-amber-500/20 text-amber-400 border border-amber-500/30') ?>">
                    <?= $invoice['status'] == 'paid' ? 'مدفوعة' : ($invoice['status'] == 'overdue' ? 'متأخرة' : 'قيد الانتظار') ?>
                </span>
                <p class="text-slate-400 text-sm mt-3">تاريخ الإصدار: <span dir="ltr"><?= $invoice['issue_date'] ?></span></p>
            </div>
        </div>

        <!-- معلومات العميل -->
        <div class="mb-8">
            <h3 class="text-slate-400 text-sm font-bold mb-2">مطلوبة من:</h3>
            <p class="text-xl font-bold text-white"><?= htmlspecialchars($client['name']) ?></p>
            <p class="text-slate-400 text-sm"><?= htmlspecialchars($client['email']) ?></p>
        </div>

        <!-- جدول البنود -->
        <table class="w-full text-right mb-8">
            <thead class="border-b border-slate-700 text-slate-400">
                <tr>
                    <th class="py-3 font-bold">الوصف</th>
                    <th class="py-3 font-bold text-left">المبلغ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                <?php foreach ($items as $item): ?>
                <tr>
                    <td class="py-4 text-white"><?= htmlspecialchars($item['description']) ?></td>
                    <td class="py-4 text-left font-mono font-bold text-white" dir="ltr">$<?= number_format($item['amount'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="border-t-2 border-slate-700">
                <tr>
                    <th class="py-4 text-xl font-bold text-slate-300">الإجمالي:</th>
                    <th class="py-4 text-left text-2xl font-bold text-blue-400 font-mono" dir="ltr">$<?= number_format($invoice['total_amount'], 2) ?></th>
                </tr>
            </tfoot>
        </table>

        <!-- منطقة الدفع الإلكتروني -->
        <?php if ($invoice['status'] !== 'paid'): ?>
            <div class="bg-blue-900/10 border border-blue-500/20 rounded-2xl p-6 text-center">
                <h3 class="text-lg font-bold text-white mb-4">الدفع الإلكتروني الآمن</h3>
                
                <?php if (!empty($paypal_client_id)): ?>
                    <!-- تضمين سكربت الدفع الخاص بالمشرف الحالي -->
                    <script src="https://www.paypal.com/sdk/js?client-id=<?= htmlspecialchars($paypal_client_id) ?>&currency=USD"></script>
                    
                    <div id="paypal-button-container" class="max-w-md mx-auto"></div>
                    
                    <script>
                        paypal.Buttons({
                            createOrder: function(data, actions) {
                                return actions.order.create({
                                    purchase_units: [{
                                        amount: { value: '<?= $invoice['total_amount'] ?>' },
                                        description: 'فاتورة رقم: <?= $invoice['reference_number'] ?>'
                                    }]
                                });
                            },
                            onApprove: function(data, actions) {
                                return actions.order.capture().then(function(details) {
                                    // بعد نجاح الدفع في باي بال، نرسل البيانات للخادم لتأكيدها في قاعدة البيانات
                                    fetch('process_payment.php', {
                                        method: 'POST',
                                        headers: { 'Content-Type': 'application/json' },
                                        body: JSON.stringify({
                                            invoice_id: <?= $invoice_id ?>,
                                            transaction_id: details.id,
                                            status: details.status,
                                            amount: '<?= $invoice['total_amount'] ?>'
                                        })
                                    }).then(response => response.json())
                                      .then(data => {
                                          if(data.success) {
                                              alert('تم سداد الفاتورة بنجاح! شكراً لك.');
                                              window.location.reload();
                                          } else {
                                              alert('حدث خطأ أثناء تسجيل الدفع في النظام.');
                                          }
                                      });
                                });
                            }
                        }).render('#paypal-button-container');
                    </script>
                <?php else: ?>
                    <div class="p-4 bg-amber-500/20 text-amber-300 rounded-xl text-center font-bold">
                        تنبيه: لم يتم ضبط مفتاح PayPal Client ID في إعدادات النظام.
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="bg-emerald-500/10 border border-emerald-500/30 rounded-2xl p-6 text-center">
                <i class="fa-solid fa-check-circle text-4xl text-emerald-400 mb-2"></i>
                <h3 class="text-xl font-bold text-emerald-400">هذه الفاتورة مدفوعة بالكامل</h3>
                <p class="text-slate-400 text-sm mt-1">شكراً لتعاملكم معنا.</p>
            </div>
        <?php endif; ?>

    </div>

</body>
</html>