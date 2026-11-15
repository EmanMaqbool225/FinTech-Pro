<?php
session_start();
if (!isset($_SESSION['client_id'])) {
    header("Location: client_login.php");
    exit;
}
require_once 'db.php';

$invoice_id = $_GET['id'] ?? 0;
$client_id = $_SESSION['client_id'];

// جلب تفاصيل الفاتورة الرئيسية
$stmt = $pdo->prepare("SELECT i.*, c.name as client_name, c.email as client_email, c.phone as client_phone, c.address as client_address 
                       FROM invoices i 
                       JOIN clients c ON i.client_id = c.id 
                       WHERE i.id = ? AND i.client_id = ?");
$stmt->execute([$invoice_id, $client_id]);
$invoice = $stmt->fetch();

if (!$invoice) {
    die("الفاتورة غير موجودة أو ليس لديك صلاحية لعرضها.");
}

// جلب جميع البنود المتعددة التابعة لهذه الفاتورة
$stmtItems = $pdo->prepare("SELECT * FROM invoice_items WHERE invoice_id = ?");
$stmtItems->execute([$invoice_id]);
$items = $stmtItems->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة رقم <?= htmlspecialchars($invoice['reference_number']) ?> | FinTech Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap');
        body { font-family: 'Tajawal', sans-serif; background: #f8fafc; color: #1e293b; }
        @media print {
            body { background: white; }
            .no-print { display: none !important; }
            shadow, .shadow-lg, .shadow-2xl { box-shadow: none !important; }
        }
    </style>
</head>
<body class="p-8 md:p-16">

    <div class="max-w-3xl mx-auto mb-8 flex justify-between items-center no-print">
        <a href="client_dashboard.php" class="px-5 py-2.5 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold transition-all flex items-center gap-2">
            <i class="fa-solid fa-arrow-right"></i> عودة للبوابة
        </a>
        <button onclick="window.print()" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold shadow-lg transition-all flex items-center gap-2">
            <i class="fa-solid fa-print"></i> طباعة / حفظ PDF
        </button>
    </div>

    <div class="max-w-3xl mx-auto bg-white p-8 md:p-12 rounded-3xl shadow-2xl border border-slate-100">
        
        <div class="flex justify-between items-start border-b border-slate-200 pb-8 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 mb-1">FinTech<span class="text-blue-600">Pro</span></h1>
                <p class="text-slate-500 text-sm">نظام إدارة الفواتير والعملاء المتقدم</p>
            </div>
            <div class="text-left">
                <h2 class="text-xl font-bold text-slate-800">مطالبة مالية</h2>
                <p class="text-sm font-mono text-blue-600 font-bold mt-1" dir="ltr"><?= htmlspecialchars($invoice['reference_number']) ?></p>
                <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-bold 
                    <?= $invoice['status'] == 'paid' ? 'bg-emerald-100 text-emerald-700' : ($invoice['status'] == 'overdue' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') ?>">
                    <?= $invoice['status'] == 'paid' ? 'مدفوعة' : ($invoice['status'] == 'overdue' ? 'متأخرة' : 'قيد الانتظار') ?>
                </span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8 mb-8 text-sm">
            <div>
                <h3 class="text-slate-400 font-bold uppercase tracking-wider mb-2 text-xs">مُصدر الفاتورة:</h3>
                <p class="font-bold text-slate-800">منصة FinTech Pro للأعمال</p>
                <p class="text-slate-600">الدعم المالي والتقني</p>
            </div>
            <div>
                <h3 class="text-slate-400 font-bold uppercase tracking-wider mb-2 text-xs">فاتورة إلى (العميل):</h3>
                <p class="font-bold text-slate-800"><?= htmlspecialchars($invoice['client_name']) ?></p>
                <p class="text-slate-600 font-mono text-xs mt-1" dir="ltr" style="text-align: right;"><?= htmlspecialchars($invoice['client_email']) ?></p>
                <p class="text-slate-600 font-mono text-xs mt-1" dir="ltr" style="text-align: right;"><?= htmlspecialchars($invoice['client_phone']) ?></p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-2xl mb-8 text-sm">
            <div>
                <span class="text-slate-500 block text-xs">تاريخ الإصدار:</span>
                <span class="font-bold text-slate-800 font-mono" dir="ltr"><?= $invoice['issue_date'] ?></span>
            </div>
            <div>
                <span class="text-slate-500 block text-xs">تاريخ الاستحقاق:</span>
                <span class="font-bold text-slate-800 font-mono" dir="ltr"><?= $invoice['due_date'] ?></span>
            </div>
        </div>

        <!-- جدول عرض كافة البنود المتعددة -->
        <table class="w-full text-right mb-8 text-sm">
            <thead>
                <tr class="border-b border-slate-200 text-slate-400">
                    <th class="py-3 font-bold">وصف الخدمة / البند</th>
                    <th class="py-3 font-bold text-left">المبلغ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($items as $item): ?>
                <tr>
                    <td class="py-4 text-slate-800 font-medium leading-relaxed"><?= htmlspecialchars($item['description']) ?></td>
                    <td class="py-4 text-left font-bold text-slate-900 font-mono" dir="ltr">$<?= number_format($item['amount'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="flex justify-end border-t border-slate-200 pt-6 mb-12">
            <div class="w-64 space-y-2">
                <div class="flex justify-between text-slate-600 text-sm">
                    <span>المجموع الفرعي:</span>
                    <span dir="ltr" class="font-mono">$<?= number_format($invoice['total_amount'], 2) ?></span>
                </div>
                <div class="flex justify-between text-lg font-extrabold text-slate-900 border-t border-slate-200 pt-2">
                    <span>الصافي المستحق:</span>
                    <span class="text-blue-600 font-mono" dir="ltr">$<?= number_format($invoice['total_amount'], 2) ?></span>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-100 pt-6 text-center text-xs text-slate-400">
            <p>هذه الفاتورة صادرة إلكترونياً من نظام FinTech Pro ولا تحتاج إلى ختم أو توقيع.</p>
            <p class="mt-1">شكراً لثقتكم بنا.</p>
        </div>

    </div>

</body>
</html>