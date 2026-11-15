<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require_once 'db.php';

$admin_id = $_SESSION['admin_id']; // رقم المشرف الحالي المسجل

// متغيرات الصفحة ليتم قراءتها في ملف header.php
$page_title = 'مركز القيادة';
$page_description = 'نظرة عامة على الأداء والتدفقات النقدية';

// استعلامات الإحصائيات مع عزلها برقم المشرف الحالي (admin_id)
$stmtRevenue = $pdo->prepare("SELECT SUM(total_amount) as total_revenue FROM invoices WHERE admin_id = ? AND status = 'paid'");
$stmtRevenue->execute([$admin_id]);
$revenue = $stmtRevenue->fetch()['total_revenue'] ?? 0.00;

$stmtOverdue = $pdo->prepare("SELECT COUNT(*) as overdue_count FROM invoices WHERE admin_id = ? AND status = 'overdue'");
$stmtOverdue->execute([$admin_id]);
$overdueCount = $stmtOverdue->fetch()['overdue_count'] ?? 0;

$stmtClients = $pdo->prepare("SELECT COUNT(*) as clients_count FROM clients WHERE admin_id = ?");
$stmtClients->execute([$admin_id]);
$clientsCount = $stmtClients->fetch()['clients_count'] ?? 0;

$stmtPending = $pdo->prepare("SELECT SUM(total_amount) as pending_rev FROM invoices WHERE admin_id = ? AND status = 'pending'");
$stmtPending->execute([$admin_id]);
$pendingRev = $stmtPending->fetch()['pending_rev'] ?? 0.00;

$stmtLatest = $pdo->prepare("
    SELECT i.reference_number, i.total_amount, i.status, c.name as client_name 
    FROM invoices i JOIN clients c ON i.client_id = c.id 
    WHERE i.admin_id = ? 
    ORDER BY i.id DESC LIMIT 5
");
$stmtLatest->execute([$admin_id]);
$latestInvoices = $stmtLatest->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> | FinTech Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap');
        body { 
            font-family: 'Tajawal', sans-serif; 
            background: radial-gradient(circle at top left, #1e1b4b, #0f172a 40%, #020617);
            color: #f8fafc;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }
        .glass-sidebar {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(20px);
            border-left: 1px solid rgba(255, 255, 255, 0.05);
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: rgba(0,0,0,0.2); }
        ::-webkit-scrollbar-thumb { background: #3b82f6; border-radius: 10px; }
    </style>
</head>
<body class="min-h-screen flex flex-col md:flex-row overflow-x-hidden selection:bg-blue-500 selection:text-white">
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-blue-600/20 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-purple-600/20 rounded-full blur-[100px] pointer-events-none"></div>

    <!-- استدعاء الشريط الجانبي برمجياً -->
    <?php require_once 'includes/sidebar.php'; ?>

    <div class="flex-1 flex flex-col relative z-10 w-full">
        
        <!-- استدعاء الشريط العلوي برمجياً -->
        <?php require_once 'includes/header.php'; ?>

        <main class="flex-1 overflow-y-auto p-4 md:p-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="glass-panel p-6 rounded-2xl relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full blur-[40px] group-hover:bg-emerald-500/20 transition-all"></div>
                    <div class="flex justify-between items-start mb-4 relative z-10">
                        <p class="text-sm text-slate-400 font-medium">الرصيد المحصل</p>
                        <div class="w-10 h-10 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center"><i class="fa-solid fa-arrow-trend-up"></i></div>
                    </div>
                    <h3 class="text-3xl font-bold text-white relative z-10 tracking-wider">$<?= number_format($revenue, 2) ?></h3>
                </div>

                <div class="glass-panel p-6 rounded-2xl relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/10 rounded-full blur-[40px] group-hover:bg-amber-500/20 transition-all"></div>
                    <div class="flex justify-between items-start mb-4 relative z-10">
                        <p class="text-sm text-slate-400 font-medium">قيد الانتظار</p>
                        <div class="w-10 h-10 rounded-lg bg-amber-500/20 text-amber-400 flex items-center justify-center"><i class="fa-regular fa-clock"></i></div>
                    </div>
                    <h3 class="text-3xl font-bold text-white relative z-10 tracking-wider">$<?= number_format($pendingRev, 2) ?></h3>
                </div>

                <div class="glass-panel p-6 rounded-2xl relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full blur-[40px] group-hover:bg-blue-500/20 transition-all"></div>
                    <div class="flex justify-between items-start mb-4 relative z-10">
                        <p class="text-sm text-slate-400 font-medium">العملاء النشطين</p>
                        <div class="w-10 h-10 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center"><i class="fa-solid fa-users"></i></div>
                    </div>
                    <h3 class="text-3xl font-bold text-white relative z-10"><?= $clientsCount ?></h3>
                </div>

                <div class="glass-panel p-6 rounded-2xl relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-red-500/10 rounded-full blur-[40px] group-hover:bg-red-500/20 transition-all"></div>
                    <div class="flex justify-between items-start mb-4 relative z-10">
                        <p class="text-sm text-slate-400 font-medium">فواتير متأخرة</p>
                        <div class="w-10 h-10 rounded-lg bg-red-500/20 text-red-400 flex items-center justify-center"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    </div>
                    <h3 class="text-3xl font-bold text-white relative z-10"><?= $overdueCount ?></h3>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 glass-panel p-6 rounded-2xl">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-white">تحليل التدفق النقدي</h3>
                        <span class="text-xs px-3 py-1 rounded-full bg-white/10 text-slate-300 border border-white/5">2026</span>
                    </div>
                    <div class="relative h-72 w-full">
                        <canvas id="darkChart"></canvas>
                    </div>
                </div>

                <div class="glass-panel p-6 rounded-2xl flex flex-col">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-white">سجل المعاملات</h3>
                        <a href="#" class="text-sm text-blue-400 hover:text-blue-300 transition-colors">الكل</a>
                    </div>
                    
                    <div class="space-y-4 flex-1 overflow-y-auto pr-2">
                        <?php if (count($latestInvoices) > 0): ?>
                            <?php foreach ($latestInvoices as $inv): 
                                $bgClass = $inv['status'] == 'paid' ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : ($inv['status'] == 'overdue' ? 'bg-red-500/20 text-red-400 border-red-500/30' : 'bg-amber-500/20 text-amber-400 border-amber-500/30');
                                $icon = $inv['status'] == 'paid' ? 'fa-check' : ($inv['status'] == 'overdue' ? 'fa-xmark' : 'fa-hourglass-half');
                            ?>
                            <div class="glass-panel p-4 rounded-xl flex items-center justify-between hover:bg-white/5 transition-all cursor-pointer">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center border border-white/10 text-slate-300">
                                        <i class="fa-solid fa-file-invoice"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-white mb-1"><?= htmlspecialchars($inv['reference_number']) ?></p>
                                        <p class="text-xs text-slate-400 truncate w-32"><?= htmlspecialchars($inv['client_name']) ?></p>
                                    </div>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-bold text-white mb-1">$<?= number_format($inv['total_amount'], 2) ?></p>
                                    <span class="text-[10px] px-2 py-1 rounded border <?= $bgClass ?> flex items-center justify-center gap-1 w-fit mr-auto">
                                        <i class="fa-solid <?= $icon ?>"></i>
                                    </span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="flex flex-col items-center justify-center h-full text-slate-500 opacity-50">
                                <i class="fa-solid fa-box-open text-4xl mb-3"></i>
                                <p class="text-sm">لا توجد سجلات</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        const chartElement = document.getElementById('darkChart');
        if (chartElement) {
            const ctx = chartElement.getContext('2d');
            let gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.5)');   
            gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
                    datasets: [{
                        label: 'الإيرادات',
                        data: [1200, 1900, 3000, 2500, 4200, <?= $revenue > 0 ? $revenue : 5000 ?>],
                        borderColor: '#3b82f6',
                        backgroundColor: gradient,
                        borderWidth: 3,
                        pointBackgroundColor: '#0f172a',
                        pointBorderColor: '#3b82f6',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: { 
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { 
                            grid: { display: false, drawBorder: false }, 
                            ticks: { color: '#64748b', font: { family: 'Tajawal' } } 
                        },
                        y: { 
                            grid: { color: 'rgba(255,255,255,0.05)', drawBorder: false }, 
                            ticks: { color: '#64748b', padding: 10 } 
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>