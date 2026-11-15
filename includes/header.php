<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../db.php';

$admin_id = $_SESSION['admin_id'];

// 1. جلب بيانات المشرف
$stmtAdmin = $pdo->prepare("SELECT * FROM admins WHERE id = ?");
$stmtAdmin->execute([$admin_id]);
$admin = $stmtAdmin->fetch();

$first_name = 'عزيزي';
if ($admin) {
    if (isset($admin['name'])) $first_name = explode(' ', trim($admin['name']))[0];
    elseif (isset($admin['username'])) $first_name = explode(' ', trim($admin['username']))[0];
    elseif (isset($admin['email'])) $first_name = explode('@', trim($admin['email']))[0];
}

// إرسال إشعار ترحيبي
if (!isset($_SESSION['welcome_notif_sent'])) {
    if (function_exists('sendNotification')) {
        sendNotification($pdo, $admin_id, 'info', 'تسجيل دخول ناجح',  '👋أهلا بك يا ' . $first_name . ' في لوحة تحكم FinTech Pro  نتمنى لك تجربة ممتعة وإدارة مالية ناجحة!');
    }
    $_SESSION['welcome_notif_sent'] = true;
}

// 2. جلب الإشعارات بأمان
$notifications = [];
$unread_count = 0;

try {
    $stmtNotifs = $pdo->prepare("SELECT * FROM notifications WHERE admin_id = ? ORDER BY id DESC LIMIT 10");
    $stmtNotifs->execute([$admin_id]);
    $notifications = $stmtNotifs->fetchAll();

    $stmtUnread = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE admin_id = ? AND is_read = 0");
    $stmtUnread->execute([$admin_id]);
    $unread_count = $stmtUnread->fetchColumn();
} catch (PDOException $e) {
    // الجدول غير موجود بعد - سيتم إظهار 0 إشعارات لتجنب توقف النظام
}

function getNotifStyle($type) {
    switch($type) {
        case 'success': return ['icon' => 'fa-check-circle', 'color' => 'text-emerald-400', 'bg' => 'bg-emerald-500/20'];
        case 'error': return ['icon' => 'fa-circle-xmark', 'color' => 'text-red-400', 'bg' => 'bg-red-500/20'];
        case 'warning': return ['icon' => 'fa-triangle-exclamation', 'color' => 'text-amber-400', 'bg' => 'bg-amber-500/20'];
        default: return ['icon' => 'fa-bell', 'color' => 'text-blue-400', 'bg' => 'bg-blue-500/20'];
    }
}
?>
<header class="h-20 bg-slate-900/50 backdrop-blur-md border-b border-slate-800 flex items-center justify-between px-4 md:px-10 z-50">
    <div class="flex items-center gap-3">
        <button id="sidebarToggle" type="button" class="text-slate-400 hover:text-white p-2 rounded-xl hover:bg-slate-800 transition-all text-xl">
            <i class="fa-solid fa-bars"></i>
        </button>
        <h2 class="text-lg md:text-xl font-bold text-white"><?= $page_title ?? 'لوحة التحكم' ?></h2>
    </div>
    <div class="flex items-center gap-6">
        <div class="relative group">
            <button class="text-slate-400 hover:text-white relative transition-all">
                <i class="fa-solid fa-bell text-xl"></i>
                <?php if ($unread_count > 0): ?>
                    <span class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 rounded-full text-xs font-bold text-white flex items-center justify-center border-2 border-slate-900">
                        <?= $unread_count ?>
                    </span>
                <?php endif; ?>
            </button>
            
            <div class="absolute left-0 mt-4 w-80 bg-slate-800 border border-slate-700 rounded-2xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                <div class="p-4 border-b border-slate-700 flex justify-between items-center">
                    <h3 class="font-bold text-white">الإشعارات</h3>
                    <span class="text-xs text-slate-400">آخر الإشعارات</span>
                </div>
                <div class="p-2 max-h-80 overflow-y-auto">
                    
                    <?php if (count($notifications) > 0): ?>
                        <?php foreach ($notifications as $notif): 
                            $style = getNotifStyle($notif['type']);
                        ?>
                            <div class="block p-3 hover:bg-slate-700/50 border border-transparent hover:border-slate-600 rounded-xl transition-all mb-1 <?= $notif['is_read'] == 0 ? 'bg-slate-700/20' : '' ?>">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full <?= $style['bg'] ?> <?= $style['color'] ?> flex items-center justify-center flex-shrink-0 mt-1">
                                        <i class="fa-solid <?= $style['icon'] ?> text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold <?= $style['color'] ?>"><?= htmlspecialchars($notif['title']) ?></div>
                                        <div class="text-xs text-slate-300 mt-1 leading-relaxed"><?= htmlspecialchars($notif['message']) ?></div>
                                        <div class="text-[10px] text-slate-500 mt-2" dir="ltr"><?= date('Y-m-d h:i A', strtotime($notif['created_at'])) ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="p-6 text-center text-slate-400 flex flex-col items-center gap-2">
                            <i class="fa-regular fa-bell-slash text-2xl opacity-50"></i>
                            <span class="text-sm font-bold">لا توجد إشعارات حالياً</span>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</header>