<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require_once 'db.php';

$admin_id = $_SESSION['admin_id'];
$page_title = 'إعدادات الدفع (بوابات الدفع)';
$msg = '';
$msgType = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $paypal_client_id = trim($_POST['paypal_client_id']);

    try {
        $stmtUpdate = $pdo->prepare("UPDATE admins SET paypal_client_id = ? WHERE id = ?");
        if ($stmtUpdate->execute([$paypal_client_id, $admin_id])) {
            $msg = "تم حفظ إعدادات بوابات الدفع بنجاح!";
            $msgType = "success";
        }
    } catch (PDOException $e) {
        $msg = "حدث خطأ أثناء الحفظ: " . $e->getMessage();
        $msgType = "error";
    }
}

$stmt = $pdo->prepare("SELECT paypal_client_id FROM admins WHERE id = ?");
$stmt->execute([$admin_id]);
$admin = $stmt->fetch();
// تم وضع المفتاح الخاص بك هنا كقيمة افتراضية تظهر فوراً
$current_paypal_id = !empty($admin['paypal_client_id']) ? $admin['paypal_client_id'] : 'BAAZLEm2CBOeQ_lpX79u4OSdzz1YhzXKYAZxdFaoKE6CIYQiXYWUkR45g5CT_1NQ191mKkhNgTMtBdL8s0';
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
        .glass-panel { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.08); box-shadow: 0 4px 30px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="h-screen flex overflow-hidden selection:bg-blue-500 selection:text-white">

    <?php require_once 'includes/sidebar.php'; ?>

    <div class="flex-1 flex flex-col relative z-10 w-full">
        <?php require_once 'includes/header.php'; ?>

        <main class="flex-1 overflow-y-auto p-10">
            <div class="max-w-3xl mx-auto space-y-8">
                
                <div class="glass-panel p-8 rounded-3xl flex justify-between items-center relative overflow-hidden">
                    <div>
                        <h1 class="text-3xl font-extrabold text-white mb-2">إعدادات الدفع الإلكتروني 💳</h1>
                        <p class="text-slate-400 text-sm">اربط حسابك لاستقبال مدفوعات فواتيرك مباشرة عبر بوابات الدفع العالمية.</p>
                    </div>
                </div>

                <?php if ($msg): ?>
                    <div class="p-4 rounded-xl border <?= $msgType === 'success' ? 'bg-emerald-500/20 border-emerald-500/50 text-emerald-300' : 'bg-red-500/20 border-red-500/50 text-red-300' ?> flex items-center gap-3 font-bold shadow-lg">
                        <i class="fa-solid <?= $msgType === 'success' ? 'fa-circle-check text-emerald-400' : 'fa-triangle-exclamation text-red-400' ?>"></i>
                        <?= $msg ?>
                    </div>
                <?php endif; ?>

                <div class="glass-panel p-8 rounded-3xl">
                    <form method="POST" class="space-y-6">
                        <div class="p-6 bg-blue-900/10 border border-blue-500/20 rounded-2xl space-y-4">
                            <div class="flex items-center gap-3 mb-4">
                                <i class="fa-brands fa-paypal text-blue-400 text-2xl"></i>
                                <h3 class="text-xl font-bold text-white">إعدادات PayPal</h3>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">معرف العميل (Client ID)</label>
                                <div class="relative">
                                    <input type="text" name="paypal_client_id" value="<?= htmlspecialchars($current_paypal_id) ?>" class="w-full px-4 py-3 bg-slate-900/50 rounded-xl border border-slate-700 focus:outline-none focus:border-blue-500 text-white transition-all text-left font-mono" dir="ltr">
                                    <i class="fa-solid fa-key absolute right-4 top-4 text-slate-500"></i>
                                </div>
                            </div>
                        </div>
                        <div class="pt-6 flex justify-end">
                            <button type="submit" class="px-8 py-3.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold shadow-lg transition-all flex items-center gap-2">
                                <i class="fa-solid fa-save"></i> حفظ التعديلات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>