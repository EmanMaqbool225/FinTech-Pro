<div id="sidebarOverlay" class="fixed inset-0 bg-black/60 z-40 hidden md:hidden"></div>

<aside id="sidebar" class="w-72 glass-sidebar flex flex-col fixed md:static top-0 right-0 h-full z-50 transition-transform duration-300 translate-x-full md:translate-x-0">
    <div class="h-20 flex items-center justify-between px-6 border-b border-white/5">
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center mr-3 shadow-lg shadow-blue-500/30">
                <i class="fa-solid fa-layer-group text-white"></i>
            </div>
            <h1 class="text-2xl font-bold tracking-wider mr-3">FinTech<span class="text-blue-500">Pro</span></h1>
        </div>

        <!-- زر إغلاق القائمة في الهاتف فقط -->
        <button id="closeSidebar" type="button" class="text-slate-400 hover:text-white text-xl p-2 rounded-lg hover:bg-white/5 md:hidden">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    
    <nav class="flex-1 px-4 py-8 space-y-3 overflow-y-auto">
        <p class="px-4 text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">القائمة الرئيسية</p>
        
        <a href="dashboard.php" class="flex items-center px-4 py-3 hover:bg-white/10 rounded-xl text-slate-300 hover:text-white transition-all group">
            <i class="fa-solid fa-chart-pie w-6 group-hover:text-blue-400 transition-colors"></i> 
            <span class="font-medium mr-2">مركز القيادة</span>
        </a>
        
        <a href="create_invoice.php" class="flex items-center px-4 py-3 hover:bg-white/10 rounded-xl text-slate-300 hover:text-white transition-all group">
            <i class="fa-solid fa-file-invoice-dollar w-6 group-hover:text-purple-400 transition-colors"></i> 
            <span class="font-medium mr-2">إصدار فاتورة</span>
        </a>
        
        <a href="add_client.php" class="flex items-center px-4 py-3 hover:bg-white/10 rounded-xl text-slate-300 hover:text-white transition-all group">
            <i class="fa-solid fa-users w-6 group-hover:text-emerald-400 transition-colors"></i> 
            <span class="font-medium mr-2">إدارة العملاء</span>
        </a>

        <a href="finances.php" class="flex items-center px-4 py-3 hover:bg-white/10 rounded-xl text-slate-300 hover:text-white transition-all group mt-1">
            <i class="fa-solid fa-wallet w-6 group-hover:text-blue-400 transition-colors"></i> 
            <span class="font-medium mr-2">المصروفات والإيرادات</span>
        </a>

        <a href="client_login.php" target="_blank" class="flex items-center px-4 py-3 hover:bg-white/10 rounded-xl text-slate-300 hover:text-white transition-all group mt-2 border-t border-white/5 pt-2">
            <i class="fa-solid fa-briefcase w-6 group-hover:text-purple-400 transition-colors"></i> 
            <span class="font-medium mr-2">بوابة العملاء</span>
        </a>

        <a href="settings.php" class="flex items-center gap-3 text-slate-300 hover:text-white p-3 rounded-xl hover:bg-white/5 transition-all mb-2">
            <i class="fa-solid fa-gear"></i>
            <span class="font-bold">إعدادات الدفع</span>
        </a>

        <a href="invoices.php" class="flex items-center gap-3 text-slate-300 hover:text-white p-3 rounded-xl hover:bg-white/5 transition-all mb-2">
            <i class="fa-solid fa-file-invoice"></i>
            <span class="font-bold">إدارة الفواتير</span>
        </a>
    </nav>
    
    <div class="p-6 border-t border-white/5">
        <div class="glass-panel p-4 rounded-xl flex items-center gap-3">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['admin_name'] ?? 'Admin') ?>&background=3b82f6&color=fff" class="w-10 h-10 rounded-full border-2 border-white/20">
            <div>
                <p class="text-sm font-bold text-white"><?= htmlspecialchars($_SESSION['admin_name'] ?? 'مدير النظام') ?></p>
                <a href="logout.php" class="text-xs text-red-400 hover:text-red-300">تسجيل الخروج</a>
            </div>
        </div>
    </div>
    
</aside>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const closeBtn = document.getElementById('closeSidebar');
    const overlay = document.getElementById('sidebarOverlay');

    function toggleMenu() {
        if (!sidebar) return;
        
        if (window.innerWidth < 768) {
            const isClosed = sidebar.classList.contains('translate-x-full');
            if (isClosed) {
                sidebar.classList.remove('translate-x-full');
                if (overlay) overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('translate-x-full');
                if (overlay) overlay.classList.add('hidden');
            }
        } else {
            sidebar.classList.toggle('md:hidden');
        }
    }

    if (toggleBtn) toggleBtn.addEventListener('click', toggleMenu);
    if (closeBtn) closeBtn.addEventListener('click', toggleMenu);
    if (overlay) overlay.addEventListener('click', toggleMenu);
});
</script>