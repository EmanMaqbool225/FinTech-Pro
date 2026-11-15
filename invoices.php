<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require_once 'db.php';

$admin_id = $_SESSION['admin_id'];
$page_title = 'إدارة الفواتير';

// جلب جميع فواتير المشرف الحالي مع اسم العميل
$stmt = $pdo->prepare("SELECT invoices.*, clients.name AS client_name 
                       FROM invoices 
                       LEFT JOIN clients ON invoices.client_id = clients.id 
                       WHERE invoices.admin_id = ? 
                       ORDER BY invoices.id DESC");
$stmt->execute([$admin_id]);
$invoices = $stmt->fetchAll();

// بناء الرابط الأساسي للموقع ديناميكياً لنسخ الروابط
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . dirname($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> | FinTech Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap');
        body { font-family: 'Tajawal', sans-serif; background: radial-gradient(circle at top left, #1e1b4b, #0f172a 40%, #020617); color: #f8fafc; }
        .glass-panel { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.08); }
    </style>
</head>
<body class="h-screen flex overflow-hidden selection:bg-blue-500 selection:text-white">

    <?php require_once 'includes/sidebar.php'; ?>

    <div class="flex-1 flex flex-col relative z-10 w-full">
        <?php require_once 'includes/header.php'; ?>

        <main class="flex-1 overflow-y-auto p-10">
            <div class="max-w-6xl mx-auto space-y-8">
                
                <div class="glass-panel p-8 rounded-3xl flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-extrabold text-white mb-2">إدارة الفواتير 📄</h1>
                        <p class="text-slate-400 text-sm">استعرض فواتيرك، انسخ روابطها، أو افتحها مباشرة لمعاينة بوابة الدفع.</p>
                    </div>
                    <a href="create_invoice.php" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold transition-all shadow-lg">
                        <i class="fa-solid fa-plus ml-2"></i> فاتورة جديدة
                    </a>
                </div>

                <div class="glass-panel rounded-3xl overflow-hidden">
                    <table class="w-full text-right">
                        <thead class="bg-slate-800/50 text-slate-300">
                            <tr>
                                <th class="p-4 font-bold">رقم الفاتورة</th>
                                <th class="p-4 font-bold">العميل</th>
                                <th class="p-4 font-bold">التاريخ</th>
                                <th class="p-4 font-bold">المبلغ</th>
                                <th class="p-4 font-bold">الحالة</th>
                                <th class="p-4 font-bold text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/50">
                            <?php foreach ($invoices as $inv): 
                                $invoice_link = $base_url . "/view_invoice.php?id=" . $inv['id'];
                            ?>
                            <tr class="hover:bg-slate-800/30 transition-all">
                                <td class="p-4 font-mono text-blue-400 font-bold" dir="ltr"><?= htmlspecialchars($inv['reference_number']) ?></td>
                                <td class="p-4 font-bold"><?= htmlspecialchars($inv['client_name']) ?></td>
                                <td class="p-4 text-slate-400 text-sm" dir="ltr"><?= $inv['issue_date'] ?></td>
                                <td class="p-4 font-mono font-bold" dir="ltr">$<?= number_format($inv['total_amount'], 2) ?></td>
                                <td class="p-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold 
                                        <?= $inv['status'] == 'paid' ? 'bg-emerald-500/20 text-emerald-400' : ($inv['status'] == 'overdue' ? 'bg-red-500/20 text-red-400' : 'bg-amber-500/20 text-amber-400') ?>">
                                        <?= $inv['status'] == 'paid' ? 'مدفوعة' : ($inv['status'] == 'overdue' ? 'متأخرة' : 'قيد الانتظار') ?>
                                    </span>
                                </td>
                                <td class="p-4 text-center space-x-2 space-x-reverse">
                                    <!-- زر فتح الفاتورة في نافذة جديدة -->
                                    <a href="<?= $invoice_link ?>" target="_blank" class="inline-block px-3 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition-all text-sm" title="فتح الفاتورة">
                                        <i class="fa-solid fa-eye"></i> عرض
                                    </a>
                                    <!-- زر نسخ الرابط -->
                                    <button onclick="copyToClipboard('<?= $invoice_link ?>')" class="inline-block px-3 py-2 bg-blue-600/20 hover:bg-blue-600 text-blue-400 hover:text-white rounded-lg transition-all text-sm border border-blue-500/30" title="نسخ الرابط للعميل">
                                        <i class="fa-solid fa-link"></i> نسخ الرابط
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            
                            <?php if (empty($invoices)): ?>
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-400">لا توجد فواتير مسجلة حتى الآن.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </main>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('تم نسخ رابط الفاتورة بنجاح! يمكنك لصقه وإرساله للعميل.');
            }, function(err) {
                alert('حدث خطأ أثناء النسخ.');
            });
        }
    </script>
</body>
</html>