<?php
session_start();
if (!isset($_SESSION['client_id'])) {
    header("Location: client_login.php");
    exit;
}
require_once 'db.php';

$client_id = $_SESSION['client_id'];
$stmt = $pdo->prepare("SELECT * FROM invoices WHERE client_id = ? ORDER BY created_at DESC");
$stmt->execute([$client_id]);
$invoices = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فواتيري | FinTech Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap');
        body { font-family: 'Tajawal', sans-serif; background: #020617; color: #f8fafc; }
        .glass-panel { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.08); }
    </style>
</head>
<body class="h-screen flex flex-col overflow-hidden relative selection:bg-purple-500 selection:text-white">
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-purple-600/20 rounded-full blur-[100px] pointer-events-none"></div>
    
    <header class="h-20 glass-panel border-b-0 border-x-0 border-t-0 flex items-center justify-between px-10">
        <h2 class="text-2xl font-bold text-white">بوابة <span class="text-purple-500">العملاء</span></h2>
        <div class="flex items-center gap-4">
            <span class="text-sm font-bold text-slate-300 border border-white/10 px-4 py-2 rounded-xl bg-white/5"><?= htmlspecialchars($_SESSION['client_name']) ?></span>
            <a href="logout.php" class="w-10 h-10 rounded-xl glass-panel flex items-center justify-center text-red-400 hover:bg-red-500/10 transition-all"><i class="fa-solid fa-right-from-bracket"></i></a>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto p-10">
        <div class="max-w-5xl mx-auto">
            <h3 class="text-xl font-bold text-white mb-6">سجل الفواتير والمطالبات</h3>
            
            <div class="glass-panel rounded-3xl overflow-hidden">
                <table class="w-full text-right text-sm">
                    <thead class="bg-white/5 border-b border-white/10 text-slate-400">
                        <tr>
                            <th class="px-6 py-4 font-bold">الرقم المرجعي</th>
                            <th class="px-6 py-4 font-bold">تاريخ الإصدار</th>
                            <th class="px-6 py-4 font-bold">الاستحقاق</th>
                            <th class="px-6 py-4 font-bold">المبلغ</th>
                            <th class="px-6 py-4 font-bold">الحالة</th>
                            <th class="px-6 py-4 font-bold text-center">إجراء</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <?php foreach ($invoices as $inv): ?>
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4 font-bold text-white" dir="ltr"><?= htmlspecialchars($inv['reference_number']) ?></td>
                            <td class="px-6 py-4 text-slate-300"><?= $inv['issue_date'] ?></td>
                            <td class="px-6 py-4 text-slate-300"><?= $inv['due_date'] ?></td>
                            <td class="px-6 py-4 font-bold text-purple-400">$<?= number_format($inv['total_amount'], 2) ?></td>
                            <td class="px-6 py-4">
                                <?php if ($inv['status'] == 'paid'): ?>
                                    <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400 text-xs border border-emerald-500/30">مدفوعة</span>
                                <?php elseif ($inv['status'] == 'overdue'): ?>
                                    <span class="px-3 py-1 rounded-full bg-red-500/20 text-red-400 text-xs border border-red-500/30">متأخرة</span>
                                <?php else: ?>
                                    <span class="px-3 py-1 rounded-full bg-amber-500/20 text-amber-400 text-xs border border-amber-500/30">معلقة</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-center">
<a href="print_invoice.php?id=<?= $inv['id'] ?>" class="px-4 py-2 rounded-lg bg-white/10 text-white hover:bg-purple-500 hover:text-white transition-all text-xs border border-white/15 inline-flex items-center gap-1">
    <i class="fa-solid fa-print"></i> معاينة / PDF
</a>                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (count($invoices) == 0): ?>
                        <tr><td colspan="6" class="px-6 py-10 text-center text-slate-500">لا توجد فواتير مسجلة حالياً.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>