<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require_once 'db.php';

$admin_id = $_SESSION['admin_id']; // رقم المشرف الحالي
$page_title = 'المصروفات والإيرادات';
$page_description = 'تتبع التدفقات المالية، الأرباح، والمصروفات التشغيلية';
$msg = '';
$msgType = '';

// معالجة إضافة معاملة مالية مع ربطها بـ admin_id
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type']; 
    $title = trim($_POST['title']);
    $amount = floatval($_POST['amount']);
    $category = trim($_POST['category']);
    $date = $_POST['date'];

    try {
        $stmt = $pdo->prepare("INSERT INTO finances (admin_id, type, title, amount, category, date) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$admin_id, $type, $title, $amount, $category, $date])) {
            $msg = "تم تسجيل المعاملة المالية بنجاح!";
            $msgType = "success";
        }
    } catch (PDOException $e) {
        $msg = "حدث خطأ أثناء حفظ المعاملة: " . $e->getMessage();
        $msgType = "error";
    }
}

// حساب المؤشرات المالية للمشرف الحالي فقط
$incomeStmt = $pdo->prepare("SELECT SUM(amount) as total FROM finances WHERE admin_id = ? AND type = 'income'");
$incomeStmt->execute([$admin_id]);
$totalIncome = $incomeStmt->fetch()['total'] ?? 0;

$expenseStmt = $pdo->prepare("SELECT SUM(amount) as total FROM finances WHERE admin_id = ? AND type = 'expense'");
$expenseStmt->execute([$admin_id]);
$totalExpense = $expenseStmt->fetch()['total'] ?? 0;

$netProfit = $totalIncome - $totalExpense;

// جلب سجل المعاملات المالية الخاصة بالمشرف الحالي فقط
$transactionsStmt = $pdo->prepare("SELECT * FROM finances WHERE admin_id = ? ORDER BY date DESC, id DESC");
$transactionsStmt->execute([$admin_id]);
$transactions = $transactionsStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> | FinTech Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap');
        body { font-family: 'Tajawal', sans-serif; background: radial-gradient(circle at top left, #1e1b4b, #0f172a 40%, #020617); color: #f8fafc; }
        .glass-panel { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.08); box-shadow: 0 4px 30px rgba(0,0,0,0.1); }
        select option { background-color: #0f172a; color: #fff; }
    </style>
</head>
<body class="h-screen flex overflow-hidden selection:bg-blue-500 selection:text-white">

    <?php require_once 'includes/sidebar.php'; ?>

    <div class="flex-1 flex flex-col relative z-10 w-full">
        <?php require_once 'includes/header.php'; ?>

        <main class="flex-1 overflow-y-auto p-10">
            <div class="max-w-6xl mx-auto space-y-8">
                
                <?php if ($msg): ?>
                    <div class="p-4 rounded-xl border <?= $msgType === 'success' ? 'bg-emerald-500/20 border-emerald-500/50 text-emerald-300' : 'bg-red-500/20 border-red-500/50 text-red-300' ?> flex items-center gap-3 font-bold shadow-lg">
                        <i class="fa-solid <?= $msgType === 'success' ? 'fa-circle-check text-emerald-400' : 'fa-triangle-exclamation text-red-400' ?>"></i>
                        <?= $msg ?>
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="glass-panel p-6 rounded-3xl border-t-2 border-emerald-500">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-slate-400 text-sm font-bold">إجمالي الإيرادات</span>
                            <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-xl border border-emerald-500/20">
                                <i class="fa-solid fa-arrow-trend-up"></i>
                            </div>
                        </div>
                        <h3 class="text-3xl font-extrabold text-white font-mono" dir="ltr">$<?= number_format($totalIncome, 2) ?></h3>
                    </div>

                    <div class="glass-panel p-6 rounded-3xl border-t-2 border-red-500">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-slate-400 text-sm font-bold">إجمالي المصروفات</span>
                            <div class="w-12 h-12 rounded-2xl bg-red-500/10 text-red-400 flex items-center justify-center text-xl border border-red-500/20">
                                <i class="fa-solid fa-arrow-trend-down"></i>
                            </div>
                        </div>
                        <h3 class="text-3xl font-extrabold text-white font-mono" dir="ltr">$<?= number_format($totalExpense, 2) ?></h3>
                    </div>

                    <div class="glass-panel p-6 rounded-3xl border-t-2 border-blue-500">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-slate-400 text-sm font-bold">صافي الأرباح</span>
                            <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-400 flex items-center justify-center text-xl border border-blue-500/20">
                                <i class="fa-solid fa-wallet"></i>
                            </div>
                        </div>
                        <h3 class="text-3xl font-extrabold <?= $netProfit >= 0 ? 'text-emerald-400' : 'text-red-400' ?> font-mono" dir="ltr">$<?= number_format($netProfit, 2) ?></h3>
                    </div>
                </div>

                <div class="glass-panel p-8 rounded-3xl">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-circle-plus text-blue-500"></i> تسجيل معاملة مالية جديدة
                    </h3>
                    <form method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-6 items-end">
                        <div>
                            <label class="block text-sm font-bold text-slate-300 mb-2">نوع المعاملة</label>
                            <select name="type" required class="w-full px-4 py-3 bg-slate-900/50 rounded-xl border border-slate-700 text-white focus:outline-none focus:border-blue-500">
                                <option value="income">إيراد (مكتسب)</option>
                                <option value="expense">مصروف (مدفوع)</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-300 mb-2">عنوان أو وصف المعاملة</label>
                            <input type="text" name="title" required placeholder="مثال: أتعاب مشروع، أدوات..." class="w-full px-4 py-3 bg-slate-900/50 rounded-xl border border-slate-700 text-white focus:outline-none focus:border-blue-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-300 mb-2">المبلغ ($)</label>
                            <input type="number" step="0.01" min="0" name="amount" required placeholder="0.00" class="w-full px-4 py-3 bg-slate-900/50 rounded-xl border border-slate-700 text-white focus:outline-none focus:border-blue-500 text-sm text-left font-mono" dir="ltr">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-300 mb-2">التصنيف</label>
                            <input type="text" name="category" required placeholder="مبيعات، رواتب..." class="w-full px-4 py-3 bg-slate-900/50 rounded-xl border border-slate-700 text-white focus:outline-none focus:border-blue-500 text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-300 mb-2">تاريخ المعاملة</label>
                            <input type="text" name="date" id="txn_date" required class="w-full px-4 py-3 bg-slate-900/50 rounded-xl border border-slate-700 text-white text-left font-mono" dir="ltr">
                        </div>
                        <div class="md:col-span-3 flex justify-end">
                            <button type="submit" class="w-full py-3.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold shadow-[0_0_20px_rgba(37,99,235,0.4)] transition-all flex items-center justify-center gap-2">
                                <i class="fa-solid fa-check"></i> حفظ المعاملة المالية
                            </button>
                        </div>
                    </form>
                </div>

                <div class="glass-panel rounded-3xl overflow-hidden">
                    <div class="p-6 border-b border-white/5">
                        <h3 class="text-xl font-bold text-white">سجل المعاملات المالية الخاصة بك</h3>
                    </div>
                    <table class="w-full text-right text-sm">
                        <thead class="bg-white/5 border-b border-white/10 text-slate-400">
                            <tr>
                                <th class="px-6 py-4 font-bold">النوع</th>
                                <th class="px-6 py-4 font-bold">العنوان / الوصف</th>
                                <th class="px-6 py-4 font-bold">التصنيف</th>
                                <th class="px-6 py-4 font-bold">التاريخ</th>
                                <th class="px-6 py-4 font-bold text-left">المبلغ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <?php foreach ($transactions as $txn): ?>
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4">
                                    <?php if ($txn['type'] == 'income'): ?>
                                        <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400 text-xs border border-emerald-500/30 flex items-center gap-1 w-fit"><i class="fa-solid fa-plus text-[10px]"></i> إيراد</span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 rounded-full bg-red-500/20 text-red-400 text-xs border border-red-500/30 flex items-center gap-1 w-fit"><i class="fa-solid fa-minus text-[10px]"></i> مصروف</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 font-bold text-white"><?= htmlspecialchars($txn['title']) ?></td>
                                <td class="px-6 py-4 text-slate-300"><?= htmlspecialchars($txn['category']) ?></td>
                                <td class="px-6 py-4 text-slate-400 font-mono text-xs" dir="ltr"><?= $txn['date'] ?></td>
                                <td class="px-6 py-4 text-left font-bold font-mono <?= $txn['type'] == 'income' ? 'text-emerald-400' : 'text-red-400' ?>" dir="ltr">
                                    <?= $txn['type'] == 'income' ? '+' : '-' ?>$<?= number_format($txn['amount'], 2) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (count($transactions) == 0): ?>
                            <tr><td colspan="5" class="px-6 py-10 text-center text-slate-500">لا توجد معاملات مسجلة في حسابك حتى الآن.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("#txn_date", { dateFormat: "Y-m-d", defaultDate: "today", disableMobile: "true" });
    </script>
</body>
</html>