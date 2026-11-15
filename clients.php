<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require_once 'db.php';

// 1. جلب رقم المشرف الحالي
$admin_id = $_SESSION['admin_id'];

// 2. جلب العملاء الذين يتبعون لهذا المشرف فقط عبر شرط WHERE admin_id = ?
$stmt = $pdo->prepare("SELECT * FROM clients WHERE admin_id = ? ORDER BY id DESC");
$stmt->execute([$admin_id]);
$clients = $stmt->fetchAll();
?>