<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require_once 'db.php';

$admin_id = $_SESSION['admin_id']; // رقم المشرف الحالي
$page_title = 'إصدار مطالبة مالية (فاتورة متعددة البنود)';
$page_description = 'إضافة بنود متعددة داخل الفاتورة الواحدة';
$msg = '';
$msgType = '';

// جلب عملاء المشرف الحالي فقط للقائمة المنسدلة
$stmtClients = $pdo->prepare("SELECT id, name FROM clients WHERE admin_id = ? ORDER BY name ASC");
$stmtClients->execute([$admin_id]);
$clients = $stmtClients->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_id = $_POST['client_id'];
    $reference_number = trim($_POST['reference_number']);
    $issue_date = $_POST['issue_date'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];
    
    $descriptions = $_POST['descriptions'] ?? [];
    $amounts = $_POST['amounts'] ?? [];

    try {
        $pdo->beginTransaction();

        $total_amount = array_sum($amounts);

        // إدخال الفاتورة الرئيسية مع ربطها بـ admin_id
        $stmt = $pdo->prepare("INSERT INTO invoices (admin_id, client_id, reference_number, issue_date, due_date, total_amount, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$admin_id, $client_id, $reference_number, $issue_date, $due_date, $total_amount, $status]);
        $invoice_id = $pdo->lastInsertId();

        // إدخال البنود المتعددة
        $stmtItem = $pdo->prepare("INSERT INTO invoice_items (invoice_id, description, amount) VALUES (?, ?, ?)");
        for ($i = 0; $i < count($descriptions); $i++) {
            if (!empty($descriptions[$i]) && isset($amounts[$i])) {
                $stmtItem->execute([$invoice_id, trim($descriptions[$i]), floatval($amounts[$i])]);
            }
        }

        $pdo->commit();

        // إرسال الإشعار بعد نجاح الحفظ الفعلي حصرياً
        $ref_num = $reference_number ?: 'جديدة'; 
        $inv_total = number_format($total_amount, 2);
        if (function_exists('sendNotification')) {
            sendNotification($pdo, $admin_id, 'info', 'فاتورة جديدة', 'تم إصدار الفاتورة رقم ' . $ref_num . ' بمبلغ $' . $inv_total);
        }

        $msg = "تم إصدار الفاتورة وبنودها المتعددة بنجاح!";
        $msgType = "success";

    } catch (Exception $e) {
        $pdo->rollBack();
        $msg = "حدث خطأ أثناء الإصدار: " . $e->getMessage();
        $msgType = "error";
    }
}

$suggested_ref = 'INV-' . date('Y') . '-' . rand(1000, 9999);
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
            <div class="max-w-4xl mx-auto">
                <?php if ($msg): ?>
                    <div class="p-4 mb-6 rounded-xl border <?= $msgType === 'success' ? 'bg-emerald-500/25 border-emerald-500/50 text-emerald-300' : 'bg-red-500/25 border-red-500/50 text-red-300' ?> flex items-center gap-3 font-bold shadow-lg">
                        <i class="fa-solid <?= $msgType === 'success' ? 'fa-circle-check text-emerald-400' : 'fa-triangle-exclamation text-red-400' ?>"></i>
                        <?= $msg ?>
                    </div>
                <?php endif; ?>

                <div class="glass-panel p-8 rounded-3xl relative overflow-hidden">
                    <form method="POST" class="space-y-6">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">العميل المرتبط <span class="text-red-400">*</span></label>
                                <div class="relative">
                                    <select name="client_id" required class="w-full px-4 py-3 bg-slate-900/50 rounded-xl border border-slate-700 focus:outline-none focus:border-blue-500 text-white transition-all appearance-none pr-10">
                                        <option value="" disabled selected>-- اختر عميلك من القائمة --</option>
                                        <?php foreach ($clients as $client): ?>
                                            <option value="<?= $client['id'] ?>"><?= htmlspecialchars($client['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <i class="fa-solid fa-user-tie absolute right-4 top-4 text-slate-500"></i>
                                    <i class="fa-solid fa-chevron-down absolute left-4 top-4 text-slate-500 pointer-events-none"></i>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">الرقم المرجعي (ID) <span class="text-red-400">*</span></label>
                                <div class="relative">
                                    <input type="text" name="reference_number" required value="<?= $suggested_ref ?>" class="w-full px-4 py-3 bg-slate-900/50 rounded-xl border border-slate-700 focus:outline-none focus:border-blue-500 text-white transition-all pr-10" dir="ltr" style="text-align: left;">
                                    <i class="fa-solid fa-hashtag absolute right-4 top-4 text-slate-500"></i>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">تاريخ الإصدار <span class="text-red-400">*</span></label>
                                <input type="text" name="issue_date" id="issue_date" required class="w-full px-4 py-3 bg-slate-900/50 rounded-xl border border-slate-700 text-white text-left" dir="ltr">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">تاريخ الاستحقاق <span class="text-red-400">*</span></label>
                                <input type="text" name="due_date" id="due_date" required class="w-full px-4 py-3 bg-slate-900/50 rounded-xl border border-slate-700 text-white text-left" dir="ltr">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">حالة الدفع <span class="text-red-400">*</span></label>
                                <select name="status" class="w-full px-4 py-3 bg-slate-900/50 rounded-xl border border-slate-700 text-white">
                                    <option value="pending">قيد الانتظار</option>
                                    <option value="paid">مدفوعة</option>
                                    <option value="overdue">متأخرة</option>
                                </select>
                            </div>
                        </div>

                        <div class="border-t border-white/10 pt-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold text-white"><i class="fa-solid fa-list-check text-blue-400 ml-2"></i> بنود المطالبة المالية</h3>
                                <button type="button" id="addItemBtn" class="px-4 py-2 rounded-xl bg-blue-600/20 text-blue-400 border border-blue-500/30 hover:bg-blue-600 hover:text-white transition-all text-sm font-bold flex items-center gap-2">
                                    <i class="fa-solid fa-plus"></i> إضافة بند جديد
                                </button>
                            </div>

                            <div id="itemsContainer" class="space-y-4">
                                <div class="item-row grid grid-cols-1 md:grid-cols-12 gap-4 items-center bg-slate-900/40 p-4 rounded-2xl border border-slate-800">
                                    <div class="md:col-span-8">
                                        <input type="text" name="descriptions[]" required placeholder="وصف الخدمة أو البند..." class="w-full px-4 py-2.5 bg-slate-900 rounded-xl border border-slate-700 text-white focus:outline-none focus:border-blue-500 text-sm">
                                    </div>
                                    <div class="md:col-span-3">
                                        <input type="number" step="0.01" min="0" name="amounts[]" required placeholder="المبلغ ($)" class="w-full px-4 py-2.5 bg-slate-900 rounded-xl border border-slate-700 text-white focus:outline-none focus:border-blue-500 text-sm text-left font-mono" dir="ltr">
                                    </div>
                                    <div class="md:col-span-1 text-center"></div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 flex items-center justify-end border-t border-white/10">
                            <button type="submit" class="px-10 py-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold shadow-[0_0_20px_rgba(37,99,235,0.4)] transition-all flex items-center gap-3 text-lg">
                                <i class="fa-solid fa-paper-plane"></i> إصدار الفاتورة واعتمادها
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("#issue_date", { dateFormat: "Y-m-d", defaultDate: "today", disableMobile: "true" });
        flatpickr("#due_date", { dateFormat: "Y-m-d", disableMobile: "true" });

        document.getElementById('addItemBtn').addEventListener('click', function() {
            const container = document.getElementById('itemsContainer');
            const newRow = document.createElement('div');
            newRow.className = 'item-row grid grid-cols-1 md:grid-cols-12 gap-4 items-center bg-slate-900/40 p-4 rounded-2xl border border-slate-800';
            newRow.innerHTML = `
                <div class="md:col-span-8">
                    <input type="text" name="descriptions[]" required placeholder="وصف البند الإضافي..." class="w-full px-4 py-2.5 bg-slate-900 rounded-xl border border-slate-700 text-white focus:outline-none focus:border-blue-500 text-sm">
                </div>
                <div class="md:col-span-3">
                    <input type="number" step="0.01" min="0" name="amounts[]" required placeholder="المبلغ ($)" class="w-full px-4 py-2.5 bg-slate-900 rounded-xl border border-slate-700 text-white focus:outline-none focus:border-blue-500 text-sm text-left font-mono" dir="ltr">
                </div>
                <div class="md:col-span-1 text-center">
                    <button type="button" class="remove-item w-10 h-10 rounded-xl bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white transition-all border border-red-500/20 flex items-center justify-center mx-auto">
                        <i class="fa-solid fa-trash-can text-sm"></i>
                    </button>
                </div>
            `;
            container.appendChild(newRow);
            newRow.querySelector('.remove-item').addEventListener('click', function() {
                newRow.remove();
            });
        });
    </script>
</body>
</html>