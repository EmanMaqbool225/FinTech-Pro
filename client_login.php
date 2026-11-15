<?php
session_start();
if (isset($_SESSION['client_id'])) {
    header("Location: client_dashboard.php");
    exit;
}

require_once 'db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM clients WHERE email = ?");
    $stmt->execute([$email]);
    $client = $stmt->fetch();

    if ($client && password_verify($password, $client['password'])) {
        $_SESSION['client_id'] = $client['id'];
        $_SESSION['client_name'] = $client['name'];
        header("Location: client_dashboard.php");
        exit;
    } else {
        $error = "بيانات الدخول غير صحيحة، يرجى المحاولة مجدداً.";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بوابة العملاء | FinTech Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap');
        body { font-family: 'Tajawal', sans-serif; background: radial-gradient(circle at top left, #1e1b4b, #0f172a 40%, #020617); color: #f8fafc; }
        .glass-panel { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.08); box-shadow: 0 4px 30px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="h-screen flex items-center justify-center overflow-hidden relative">
    <div class="absolute top-[-20%] left-[-10%] w-96 h-96 bg-purple-600/30 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-[-20%] right-[-10%] w-96 h-96 bg-blue-600/30 rounded-full blur-[120px]"></div>

    <div class="w-full max-w-md p-8 glass-panel rounded-3xl relative z-10 mx-4 border-t border-purple-500/30">
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-500 to-blue-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-purple-500/40">
                <i class="fa-solid fa-briefcase text-2xl text-white"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-white">بوابة العملاء</h2>
            <p class="text-slate-400 mt-2 text-sm">قم بتسجيل الدخول لاستعراض فواتيرك ومدفوعاتك</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-bold text-slate-300 mb-2">البريد الإلكتروني</label>
                <div class="relative">
                    <input type="email" name="email" required class="w-full px-4 py-3 bg-slate-900/50 rounded-xl border border-slate-700 focus:outline-none focus:border-purple-500 text-white transition-all pr-10">
                    <i class="fa-solid fa-envelope absolute right-4 top-4 text-slate-500"></i>
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-300 mb-2">كلمة المرور</label>
                <div class="relative">
                    <input type="password" name="password" required class="w-full px-4 py-3 bg-slate-900/50 rounded-xl border border-slate-700 focus:outline-none focus:border-purple-500 text-white transition-all pr-10">
                    <i class="fa-solid fa-lock absolute right-4 top-4 text-slate-500"></i>
                </div>
            </div>
            <button type="submit" class="w-full py-4 mt-2 rounded-xl bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-500 hover:to-blue-500 text-white font-bold shadow-[0_0_20px_rgba(168,85,247,0.4)] transition-all active:scale-95">
                تسجيل الدخول للبوابة
            </button>
        </form>
    </div>
</body>
</html>