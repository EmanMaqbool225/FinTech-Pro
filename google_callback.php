<?php
session_start();
require_once 'db.php';

// مفاتيح Google OAuth
$client_id = '982278044702-9vorrhl9c488nlogpac7bu1hpgou3341.apps.googleusercontent.com';
$client_secret = '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';
$redirect_uri = 'https://eman-pro.alwaysdata.net/google_callback.php';

if (isset($_GET['code'])) {
    // 1. تبادل الكود للحصول على Access Token
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://oauth2.googleapis.com/token");
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'code' => $_GET['code'],
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'redirect_uri' => $redirect_uri,
        'grant_type' => 'authorization_code'
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $token_data = json_decode($response, true);
    
    if (isset($token_data['access_token'])) {
        // 2. جلب بيانات المستخدم من جوجل
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, "https://www.googleapis.com/oauth2/v2/userinfo");
        curl_setopt($ch2, CURLOPT_HTTPHEADER, ["Authorization: Bearer " . $token_data['access_token']]);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, TRUE);
        $user_response = curl_exec($ch2);
        curl_close($ch2);
        
        $google_user = json_decode($user_response, true);
        
        if (isset($google_user['email'])) {
            $email = $google_user['email'];
            $name  = $google_user['name'] ?? 'Admin';
            
            // 3. التحقق إذا كان المشرف مسجلاً مسبقاً في جدول admins
            $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if (!$user) {
                // حفظ البريد وكلمة المرور المشفرة فقط دون حقل name
                $random_password = password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT);
                $stmtInsert = $pdo->prepare("INSERT INTO admins (email, password) VALUES (?, ?)");
                $stmtInsert->execute([$email, $random_password]);
                $user_id = $pdo->lastInsertId();
            } else {
                $user_id = $user['id'];
            }
            
            // 4. إنشاء جلسة الدخول وتوجيهه للوحة التحكم
            $_SESSION['admin_id']    = $user_id;
            $_SESSION['admin_email'] = $email;
            $_SESSION['admin_name']  = $name;
            header("Location: dashboard.php");
            exit;
        }
    }
}

// في حال فشل المصادقة
header("Location: login.php?error=google_auth_failed");
exit;
?>