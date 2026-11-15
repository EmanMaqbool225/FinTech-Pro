<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require_once 'db.php';

$page_title = 'إضافة عميل جديد';
$page_description = 'تسجيل بيانات عميل جديد مع صلاحيات دخول البوابة';
$msg = '';
$msgType = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. جلب رقم المشرف الحالي من جلسة التسجيل
    $admin_id = $_SESSION['admin_id']; 

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // 2. إدخال العميل مع ربطه بـ admin_id الخاص بكِ
    $stmt = $pdo->prepare("INSERT INTO clients (admin_id, name, email, password, phone, address) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$admin_id, $name, $email, $password, $phone, $address]);
    
    // رسالة النجاح أو إعادة التوجيه...
}
sendNotification($pdo, $admin_id, 'success', 'عميل جديد', 'تم إضافة العميل ' . $client_name . ' بنجاح إلى النظام.');
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
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: rgba(0,0,0,0.2); }
        ::-webkit-scrollbar-thumb { background: #3b82f6; border-radius: 10px; }
    </style>
</head>
<body class="h-screen flex overflow-hidden selection:bg-blue-500 selection:text-white">
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-blue-600/20 rounded-full blur-[100px] pointer-events-none"></div>
    <?php require_once 'includes/sidebar.php'; ?>
    <div class="flex-1 flex flex-col relative z-10 w-full">
        <?php require_once 'includes/header.php'; ?>
        <main class="flex-1 overflow-y-auto p-10">
            <div class="max-w-3xl mx-auto">
                <?php if ($msg): ?>
                    <div class="p-4 mb-6 rounded-xl border <?= $msgType === 'success' ? 'bg-emerald-500/20 border-emerald-500/50 text-emerald-400' : 'bg-red-500/20 border-red-500/50 text-red-400' ?> flex items-center gap-3 font-bold">
                        <i class="fa-solid <?= $msgType === 'success' ? 'fa-circle-check' : 'fa-triangle-exclamation' ?>"></i> <?= $msg ?>
                    </div>
                <?php endif; ?>
                <div class="glass-panel p-8 rounded-3xl">
                    <form method="POST" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">اسم العميل أو الشركة <span class="text-red-400">*</span></label>
                                <div class="relative">
                                    <input type="text" name="name" required class="w-full px-4 py-3 bg-slate-900/50 rounded-xl border border-slate-700 focus:outline-none focus:border-blue-500 text-white transition-all pr-10">
                                    <i class="fa-solid fa-building absolute right-4 top-4 text-slate-500"></i>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">البريد الإلكتروني (للدخول) <span class="text-red-400">*</span></label>
                                <div class="relative">
                                    <input type="email" name="email" required class="w-full px-4 py-3 bg-slate-900/50 rounded-xl border border-slate-700 focus:outline-none focus:border-blue-500 text-white transition-all pr-10">
                                    <i class="fa-solid fa-envelope absolute right-4 top-4 text-slate-500"></i>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">كلمة المرور المؤقتة للعميل <span class="text-red-400">*</span></label>
                                <div class="relative">
                                    <input type="text" name="password" required placeholder="مثال: client123" class="w-full px-4 py-3 bg-slate-900/50 rounded-xl border border-slate-700 focus:outline-none focus:border-blue-500 text-white transition-all pr-10" dir="ltr" style="text-align: right;">
                                    <i class="fa-solid fa-key absolute right-4 top-4 text-slate-500"></i>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">رقم الهاتف</label>
                                <div class="relative">
                                    <input type="text" name="phone" class="w-full px-4 py-3 bg-slate-900/50 rounded-xl border border-slate-700 focus:outline-none focus:border-blue-500 text-white transition-all pr-10">
                                    <i class="fa-solid fa-phone absolute right-4 top-4 text-slate-500"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-300 mb-2">العنوان بالتفصيل</label>
                            <div class="relative">
                                <textarea name="address" rows="2" class="w-full px-4 py-3 bg-slate-900/50 rounded-xl border border-slate-700 focus:outline-none focus:border-blue-500 text-white transition-all pr-10"></textarea>
                                <i class="fa-solid fa-map-location-dot absolute right-4 top-4 text-slate-500"></i>
                            </div>
                        </div>
                        <div class="pt-4 flex justify-end border-t border-white/5">
                            <button type="submit" class="px-8 py-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold shadow-[0_0_20px_rgba(37,99,235,0.3)] transition-all active:scale-95 flex items-center gap-2">
                                <i class="fa-solid fa-user-plus"></i> حفظ وإنشاء الحساب
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>