<?php
// منع الوصول المباشر لملف الاتصال للحماية
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die("الوصول المباشر لهذا الملف غير مسموح.");
}
$host = 'mysql-eman-pro.alwaysdata.net';
$dbname = 'eman-pro_fintech';
$username = 'eman-pro'; 
$password = '736313293';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // الخطوة الأمنية الأهم: تعطيل محاكاة الاستعلامات الجاهزة لزيادة الأمان ضد SQL Injection
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    // تسجيل الخطأ داخلياً وعدم عرضه للمستخدم لمنع تسريب هيكل قاعدة البيانات
    error_log("Database Connection Error: " . $e->getMessage());
    die("عذراً، حدث خطأ في الاتصال بالنظام. يرجى المحاولة لاحقاً.");
}

// دالة الإشعارات الآمنة
function sendNotification($pdo, $admin_id, $type, $title, $message) {
    try {
        $stmt = $pdo->prepare("INSERT INTO notifications (admin_id, type, title, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$admin_id, $type, $title, $message]);
    } catch (PDOException $e) {
        // تجاهل صامت لتجنب تعطيل النظام الأساسي إذا حدث خطأ في الجدول
    }
}
?>