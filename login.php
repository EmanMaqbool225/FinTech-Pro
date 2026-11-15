<?php
session_start();
$google_client_id = '982278044702-9vorrhl9c488nlogpac7bu1hpgou3341.apps.googleusercontent.com';
$redirect_uri = 'https://eman-pro.alwaysdata.net/google_callback.php';
$google_login_url = "https://accounts.google.com/o/oauth2/auth?response_type=code&client_id={$google_client_id}&redirect_uri={$redirect_uri}&scope=email%20profile";
require_once 'db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // تم تصحيح اسم الجدول من users إلى admins ليتطابق مع قاعدة البيانات
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // التحقق من كلمة المرور المشفرة
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_email'] = $user['email'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "البريد الإلكتروني أو كلمة المرور غير صحيحة.";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول | FinTech Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap');
        body { 
            font-family: 'Tajawal', sans-serif; 
            background: radial-gradient(circle at center, #1e1b4b, #020617);
            color: #f8fafc;
        }
        .glass-panel {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body class="h-screen flex items-center justify-center relative overflow-hidden">
    
    <!-- خلفيات مضيئة -->
    <div class="absolute w-96 h-96 bg-blue-600/20 rounded-full blur-[100px] top-10 right-10"></div>
    <div class="absolute w-96 h-96 bg-purple-600/20 rounded-full blur-[100px] bottom-10 left-10"></div>

    <div class="glass-panel p-10 rounded-3xl w-full max-w-md relative z-10">
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-blue-500/30">
                <i class="fa-solid fa-lock text-white text-2xl"></i>
            </div>
            <h2 class="text-2xl font-bold">تسجيل الدخول للنظام</h2>
            <p class="text-slate-400 text-sm mt-2">أدخل بيانات الاعتماد للوصول إلى لوحة التحكم</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-500/20 border border-red-500/50 text-red-400 p-3 rounded-lg text-sm mb-6 text-center font-bold">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <a href="<?= $google_login_url ?>" class="w-full flex items-center justify-center gap-3 bg-white/5 hover:bg-white/10 text-white font-bold py-3 rounded-xl border border-white/10 transition-all mb-6">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5">
            الدخول باستخدام حساب جوجل
        </a>

        <!-- فاصل -->
        <div class="flex items-center gap-3 mb-6">
            <div class="flex-1 h-px bg-slate-700/50"></div>
            <span class="text-xs text-slate-500 font-bold">أو باستخدام البريد الإلكتروني</span>
            <div class="flex-1 h-px bg-slate-700/50"></div>
        </div>

        <form method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-bold text-slate-300 mb-2">البريد الإلكتروني</label>
                <div class="relative">
                    <input type="email" name="email" required placeholder="name@example.com" class="w-full px-4 py-3 bg-slate-900/50 rounded-xl border border-slate-700 focus:outline-none focus:border-blue-500 text-white transition-all pr-10 text-left" dir="ltr" style="text-align: right;">
                    <i class="fa-solid fa-envelope absolute right-4 top-4 text-slate-500"></i>
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-300 mb-2">كلمة المرور</label>
                <div class="relative">
                    <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-3 bg-slate-900/50 rounded-xl border border-slate-700 focus:outline-none focus:border-blue-500 text-white transition-all pr-10 text-left" dir="ltr" style="text-align: right;">
                    <i class="fa-solid fa-lock absolute right-4 top-4 text-slate-500"></i>
                </div>
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-blue-600/30 transition-all active:scale-95 mt-4">
                دخول آمن
            </button>
            <div class="text-center mt-6 text-sm text-slate-400">
                ليس لديك حساب؟ <a href="register.php" class="text-blue-400 font-bold hover:underline">انشئ حساباً جديداً</a>
            </div>
        </form>
    </div>
</body>
</html>