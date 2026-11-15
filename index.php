<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinTech Pro | نظام الإدارة المالية المتقدم</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    
    <style>
        html { scroll-behavior: smooth; }
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap');
        
        /* إجبار الصفحة على عدم تجاوز الشاشة إطلاقاً */
        html, body { 
            font-family: 'Tajawal', sans-serif; 
            background-color: #020617; 
            color: #f8fafc;
            width: 100vw;
            max-width: 100%;
            overflow-x: hidden !important; 
            margin: 0;
            padding: 0;
        }
        
        .glass-navbar {
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* إغلاق الفجوة الجانبية إجبارياً */
        .swiper {
            width: 100%;
            height: 100vh;
            margin: 0 !important;
            padding: 0 !important;
        }
        .swiper-slide {
            overflow: hidden;
            width: 100% !important;
        }
        
        /* ربط الصورة بالحافة اليمنى لمنع أي فراغ */
        .swiper-slide img {
            position: absolute;
            top: 0;
            right: 0; 
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scale(1.05);
            transition: transform 8s ease-out;
        }
        .swiper-slide-active img {
            transform: scale(1);
        }

        .overlay-gradient {
            background: linear-gradient(90deg, rgba(2,6,23,0.95) 0%, rgba(15,23,42,0.7) 50%, rgba(15,23,42,0.3) 100%);
        }
    </style>
</head>
<body class="antialiased selection:bg-blue-500 selection:text-white">

    <header class="fixed w-full top-0 z-50 glass-navbar transition-all duration-300">
        <div class="container mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3 cursor-pointer">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                    <i class="fa-solid fa-layer-group text-white"></i>
                </div>
                <h1 class="text-2xl font-bold tracking-wider">FinTech<span class="text-blue-500">Pro</span></h1>
            </div>

            <nav class="hidden md:flex items-center gap-8 text-sm">
    <a href="#features" class="text-slate-300 hover:text-blue-400 font-bold transition-colors">المميزات</a>
    <a href="#features" class="text-slate-300 hover:text-blue-400 font-bold transition-colors">الحماية والأمان</a>
    <a href="#clients-cta" class="text-slate-300 hover:text-blue-400 font-bold transition-colors">عملائنا</a>
    <a href="#contact" class="text-slate-300 hover:text-blue-400 font-bold transition-colors">اتصل بنا</a>
</nav>

            <div class="flex gap-4">
                <a href="dashboard.php" class="px-6 py-2.5 rounded-lg text-sm font-bold text-white bg-white/10 hover:bg-white/20 border border-white/5 transition-all backdrop-blur-md">
                    تسجيل الدخول
                </a>
                <a href="dashboard.php" class="px-6 py-2.5 rounded-lg text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-600/30 transition-all active:scale-95 hidden md:block">
                    ابدأ تجربتك الآن
                </a>
            </div>
        </div>
    </header>

    <!-- تغليف السلايدر بحاوية تمنع أي تجاوز أفقي -->
    <main class="relative h-screen w-full overflow-hidden block">
        <div class="swiper heroSwiper w-full h-full" dir="rtl">
            <div class="swiper-wrapper w-full">
                
                <div class="swiper-slide w-full">
<img src="assets/images/slide1.jpg" alt="Data Analytics">                    <div class="absolute inset-0 overlay-gradient flex items-center w-full">
                        <div class="container mx-auto px-6 pt-20">
                            <div class="max-w-2xl">
                                <span class="inline-block py-1 px-3 rounded-full bg-blue-500/20 text-blue-400 text-sm font-bold border border-blue-500/20 mb-6">
                                    <i class="fa-solid fa-shield-halved ml-2"></i>معمارية برمجية مرنة وآمنة
                                <h2 class="text-3xl sm:text-4xl md:text-6xl font-extrabold text-white leading-tight mb-6">
                                    مستقبل <span class="text-transparent bg-clip-text bg-gradient-to-l from-blue-400 to-purple-500">الفوترة الرقمية</span> للمؤسسات
                                </h2>
                                <p class="text-lg text-slate-300 mb-10 leading-relaxed">
                                    نظام مركزي متكامل لإدارة التدفقات النقدية، إصدار الفواتير، ومتابعة العملاء بواجهات زجاجية حديثة وتحليلات بيانية دقيقة.
                                </p>
                                <div class="flex gap-4">
                                    <a href="dashboard.php" class="px-8 py-4 rounded-xl text-lg font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-[0_0_20px_rgba(37,99,235,0.4)] transition-all flex items-center gap-3">
                                        انتقل للوحة التحكم <i class="fa-solid fa-arrow-left"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="swiper-slide w-full">
<img src="assets/images/slide2.jpg" alt="Business Growth">                    <div class="absolute inset-0 overlay-gradient flex items-center w-full">
                        <div class="container mx-auto px-6 pt-20">
                            <div class="max-w-2xl">
                                <span class="inline-block py-1 px-3 rounded-full bg-emerald-500/20 text-emerald-400 text-sm font-bold border border-emerald-500/20 mb-6">
                                    <i class="fa-solid fa-chart-line ml-2"></i>تقارير مالية لحظية
                                </span>
                                <h2 class="text-5xl md:text-6xl font-extrabold text-white leading-tight mb-6">
                                    تحكم كامل في <span class="text-transparent bg-clip-text bg-gradient-to-l from-emerald-400 to-teal-500">الأداء المالي</span>
                                </h2>
                                <p class="text-lg text-slate-300 mb-10 leading-relaxed">
                                    احصل على رؤية شاملة لأرباحك وفواتيرك المتأخرة من خلال لوحات قيادة تفاعلية صُممت لتسريع اتخاذ القرارات الاستراتيجية.
                                </p>
                                <div class="flex gap-4">
                                    <a href="dashboard.php" class="px-8 py-4 rounded-xl text-lg font-bold text-white bg-white/10 hover:bg-white/20 border border-white/10 backdrop-blur-md transition-all flex items-center gap-3">
                                        تصفح النظام <i class="fa-solid fa-desktop"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide w-full">
    <img src="assets/images/slide3.jpg" alt="ميزة إضافية">
    <div class="absolute inset-0 overlay-gradient flex items-center w-full">
        <div class="container mx-auto px-6 pt-20">
            <div class="max-w-2xl">
                <span class="inline-block py-1 px-3 rounded-full bg-purple-500/20 text-purple-400 text-sm font-bold border border-purple-500/20 mb-6">
                    <i class="fa-solid fa-bolt ml-2"></i>سرعة وأداء فائق
                </span>
                <h2 class="text-5xl md:text-6xl font-extrabold text-white leading-tight mb-6">
                    إدارة مالية <span class="text-transparent bg-clip-text bg-gradient-to-l from-purple-400 to-pink-500"> مرنة</span>
                </h2>
                <p class="text-lg text-slate-300 mb-10 leading-relaxed">
                    نظام يواكب نمو أعمالك مع أدوات متقدمة لتوسيع نطاق عملياتك المالية بسهولة وأمان.
                </p>
                <div class="flex gap-4">
                    <a href="dashboard.php" class="px-8 py-4 rounded-xl text-lg font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-[0_0_20px_rgba(37,99,235,0.4)] transition-all flex items-center gap-3">
                        اكتشف المزيد <i class="fa-solid fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

            </div>
            <div class="swiper-pagination"></div>
        </div>
        
    </main>
    <!-- قسم النبذة التعريفية الاحترافية (About / Overview) -->
<section class="py-16 bg-[#020617] relative z-10 border-t border-white/5">
    <div class="container mx-auto px-6">
        <div class="glass-panel rounded-3xl p-8 md:p-12 relative overflow-hidden border border-blue-500/20 bg-gradient-to-br from-blue-950/20 via-slate-900/40 to-slate-900/80 shadow-2xl">
            <!-- تأثير إضاءة خلفي سينمائي خفيف -->
            <div class="absolute -right-20 -top-20 w-60 h-60 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
                <div class="lg:col-span-8">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-500/20 text-blue-400 border border-blue-500/30">
                            <i class="fa-solid fa-circle-info ml-1.5"></i> نبذة عن النظام
                        </span>
                        <span class="text-xs text-slate-400">إدارة مالية ذكية ومؤمنة بالكامل</span>
                    </div>
                    <h2 class="text-2xl md:text-3xl font-extrabold text-white mb-4 leading-tight">
                        منصة <span class="text-transparent bg-clip-text bg-gradient-to-l from-blue-400 to-purple-500">FinTech Pro</span> المصممة خصيصاً للشركات والمستقلين
                    </h2>
                    <p class="text-slate-300 text-base leading-relaxed mb-6">
                        هو نظام ويب متكامل يجمع بين قوة الأمان المتقدم وسلاسة تجربة المستخدم. يتيح للمنشآت إصدار الفواتير الضريبية ودعم البنود المتعددة، تتبع المدفوعات والديون، وإتمام المعاملات النقدية بمرونة عبر بوابات الدفع العالمية مثل PayPal، مع لوحة تحكم مركزية وسجل أحداث دقيق يضمن لك المراقبة اللحظية لكل حركة داخل النظام.
                    </p>
                    <div class="flex flex-wrap gap-6 text-sm text-slate-300 font-medium">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-check-circle text-blue-400"></i> معمارية آمنة ومحمية
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-check-circle text-blue-400"></i> تقارير وفواتير دقيقة
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-check-circle text-blue-400"></i> ربط حقيقي مع PayPal
                        </div>
                    </div>
                </div>
                
                <div class="lg:col-span-4 flex flex-col justify-center items-center lg:items-end gap-4 border-t lg:border-t-0 lg:border-r border-white/10 pt-6 lg:pt-0 lg:pr-8">
                    <div class="text-center lg:text-right">
                        <div class="text-3xl font-extrabold text-blue-400 mb-1 font-mono">SaaS</div>
                        <div class="text-xs text-slate-400">  جاهز للإنتاج والاستخدام العملي </div>
                    </div>
                    <div class="w-full h-[1px] bg-white/10 my-2"></div>
                    <div class="text-center lg:text-right">
                        <div class="text-3xl font-extrabold text-purple-400 mb-1 font-mono"></div>
                        <div class="text-xs text-slate-400"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- قسم المميزات (Features Section) -->
<section id="features" class="py-24 bg-[#020617] relative z-10 border-t border-white/5">
            <div class="container mx-auto px-6">
            <div class="text-center mb-20">
                <span class="inline-block py-1 px-4 rounded-full bg-blue-500/10 text-blue-400 text-sm font-bold border border-blue-500/20 mb-4 shadow-[0_0_10px_rgba(59,130,246,0.2)]">اكتشف قدرات النظام</span>
                <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-6">لماذا تختار <span class="text-transparent bg-clip-text bg-gradient-to-l from-blue-400 to-purple-500">FinTech Pro</span>؟</h2>
                <p class="text-slate-400 max-w-2xl mx-auto text-lg leading-relaxed">نقدم لك أدوات مالية متكاملة مصممة خصيصاً لتسريع نمو أعمالك، تقليل الجهد اليدوي، وتنظيم سجلاتك باحترافية.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- الميزة الأولى: الفوترة -->
                <div class="glass-panel p-8 rounded-3xl hover:-translate-y-2 transition-all duration-300 group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/5 rounded-full blur-[40px] group-hover:bg-blue-500/20 transition-all z-0"></div>
                    <div class="relative z-10">
                        <div class="w-14 h-14 rounded-2xl bg-blue-500/10 text-blue-400 flex items-center justify-center text-2xl mb-6 group-hover:bg-blue-500 group-hover:text-white transition-all border border-blue-500/20 group-hover:shadow-[0_0_20px_rgba(59,130,246,0.4)]">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">فوترة ذكية وتلقائية</h3>
                        <p class="text-slate-400 leading-relaxed text-sm mb-5">أصدر مطالباتك المالية بضغطة زر وتتبع حالات الدفع والديون المتأخرة عبر لوحة تحكم مركزية.</p>
                        <ul class="space-y-2 mt-4 border-t border-white/5 pt-4">
                            <li class="flex items-center text-xs font-medium text-slate-300"><i class="fa-solid fa-check text-blue-400 ml-2"></i> تتبع الفواتير المعلقة</li>
                            <li class="flex items-center text-xs font-medium text-slate-300"><i class="fa-solid fa-check text-blue-400 ml-2"></i> تحديث فوري لحالة الدفع</li>
                        </ul>
                    </div>
                </div>

                <!-- الميزة الثانية: التحليلات -->
                <div class="glass-panel p-8 rounded-3xl hover:-translate-y-2 transition-all duration-300 group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-full blur-[40px] group-hover:bg-emerald-500/20 transition-all z-0"></div>
                    <div class="relative z-10">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-2xl mb-6 group-hover:bg-emerald-500 group-hover:text-white transition-all border border-emerald-500/20 group-hover:shadow-[0_0_20px_rgba(16,185,129,0.4)]">
                            <i class="fa-solid fa-chart-pie"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">تحليلات مالية لحظية</h3>
                        <p class="text-slate-400 leading-relaxed text-sm mb-5">راقب تدفقاتك النقدية من خلال رسوم بيانية دقيقة تساعدك على اتخاذ قرارات استراتيجية صائبة.</p>
                        <ul class="space-y-2 mt-4 border-t border-white/5 pt-4">
                            <li class="flex items-center text-xs font-medium text-slate-300"><i class="fa-solid fa-check text-emerald-400 ml-2"></i> مؤشرات أداء تفاعلية</li>
                            <li class="flex items-center text-xs font-medium text-slate-300"><i class="fa-solid fa-check text-emerald-400 ml-2"></i> ملخص مالي دقيق</li>
                        </ul>
                    </div>
                </div>

                <!-- الميزة الثالثة: إدارة العملاء -->
                <div class="glass-panel p-8 rounded-3xl hover:-translate-y-2 transition-all duration-300 group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-purple-500/5 rounded-full blur-[40px] group-hover:bg-purple-500/20 transition-all z-0"></div>
                    <div class="relative z-10">
                        <div class="w-14 h-14 rounded-2xl bg-purple-500/10 text-purple-400 flex items-center justify-center text-2xl mb-6 group-hover:bg-purple-500 group-hover:text-white transition-all border border-purple-500/20 group-hover:shadow-[0_0_20px_rgba(168,85,247,0.4)]">
                            <i class="fa-solid fa-users-gear"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">إدارة متكاملة للعملاء</h3>
                        <p class="text-slate-400 leading-relaxed text-sm mb-5">نظام مرن لتنظيم بيانات عملائك، يتيح لك الوصول السريع لسجلاتهم وربطها بالفواتير بسهولة.</p>
                        <ul class="space-y-2 mt-4 border-t border-white/5 pt-4">
                            <li class="flex items-center text-xs font-medium text-slate-300"><i class="fa-solid fa-check text-purple-400 ml-2"></i> أرشيف مالي لكل عميل</li>
                            <li class="flex items-center text-xs font-medium text-slate-300"><i class="fa-solid fa-check text-purple-400 ml-2"></i> ربط الفواتير بدقة</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- دعوة لاتخاذ إجراء (CTA Banner) -->
<section id="clients-cta" class="py-16 relative z-10">
            <div class="container mx-auto px-6">
            <div class="glass-panel rounded-[2.5rem] p-10 md:p-16 relative overflow-hidden border border-blue-500/20">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-900/40 to-purple-900/40 z-0"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/30 rounded-full blur-[80px] z-0"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="text-right md:w-2/3">
                        <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4 leading-tight">جاهز للسيطرة على <br/>تدفقاتك النقدية؟</h2>
                        <p class="text-slate-300 text-lg">انضم إلى المنصة الأسرع نمواً وابدأ في تنظيم أعمالك باحترافية.</p>
                    </div>
                    <div class="md:w-1/3 flex flex-col sm:flex-row justify-end w-full gap-4">
    <a href="client_login.php" class="px-8 py-5 rounded-2xl text-lg font-bold text-purple-400 bg-purple-500/10 border border-purple-500/30 hover:bg-purple-500/20 transition-all flex items-center justify-center gap-3">
        <i class="fa-solid fa-briefcase"></i> بوابة العملاء
    </a>
    <a href="login.php" class="px-8 py-5 rounded-2xl text-lg font-bold text-white bg-blue-600 hover:bg-blue-500 shadow-[0_0_30px_rgba(37,99,235,0.4)] transition-all active:scale-95 flex items-center justify-center gap-3">
        دخول الإدارة <i class="fa-solid fa-arrow-left"></i>
    </a>
</div>
                </div>
            </div>
        </div>
    </section>

    <!-- تذييل الصفحة (Footer) -->
<footer id="contact" class="border-t border-white/5 bg-[#020617] pt-20 pb-10 relative z-10">
            <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                            <i class="fa-solid fa-layer-group text-white"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-white">FinTech<span class="text-blue-500">Pro</span></h2>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-sm">نظام متقدم لإدارة الفواتير والعملاء، صُمم خصيصاً لتسهيل العمليات المالية للشركات الناشئة والمستقلين بواجهة آمنة.</p>
                </div>
                
                <div>
                    <h4 class="text-white font-bold mb-6 uppercase tracking-wider text-sm">روابط سريعة</h4>
                    <ul class="space-y-4">
                        <li><a href="login.php" class="text-slate-400 hover:text-blue-400 text-sm transition-colors flex items-center gap-2"><i class="fa-solid fa-angle-left text-xs"></i> تسجيل الدخول</a></li>
                        <li><a href="client_login.php" class="text-purple-400 hover:text-purple-300 text-sm font-bold transition-colors flex items-center gap-2"><i class="fa-solid fa-briefcase text-xs"></i> دخول بوابة العملاء</a></li>
                        <li><a href="dashboard.php" class="text-slate-400 hover:text-blue-400 text-sm transition-colors flex items-center gap-2"><i class="fa-solid fa-angle-left text-xs"></i> لوحة التحكم</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-white font-bold mb-6 uppercase tracking-wider text-sm">اتصل بنا</h4>
                    
                    <div class="flex flex-col gap-4 mb-6">
                        <!-- زر الاتصال المباشر -->
                        <a href="tel:+967716058576" class="flex items-center gap-3 text-slate-400 hover:text-blue-400 transition-colors w-fit group">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-400 border border-blue-500/20 group-hover:bg-blue-500 group-hover:text-white transition-all">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <span dir="ltr" class="font-bold tracking-wider">+967 716 058 576</span>
                        </a>
                        
                        <!-- زر واتساب -->
                        <a href="https://wa.me/967716058576" target="_blank" class="flex items-center gap-3 text-slate-400 hover:text-emerald-400 transition-colors w-fit group">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-400 border border-emerald-500/20 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                                <i class="fa-brands fa-whatsapp text-lg"></i>
                            </div>
                            <span dir="ltr" class="font-bold tracking-wider">+967 716 058 576</span>
                        </a>
                    </div>

                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-xl glass-panel flex items-center justify-center text-slate-400 hover:text-white hover:bg-blue-600 transition-all shadow-lg"><i class="fa-brands fa-github"></i></a>
                        <a href="#" class="w-10 h-10 rounded-xl glass-panel flex items-center justify-center text-slate-400 hover:text-white hover:bg-blue-600 transition-all shadow-lg"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>
            
            <div class="border-t border-white/5 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-slate-500 text-xs font-medium">© 2026 FinTech Pro. جميع الحقوق محفوظة.</p>
                <div class="flex gap-4 text-xs text-slate-500">
                    <span class="hover:text-slate-300 cursor-pointer transition-colors">سياسة الخصوصية</span>
                    <span class="hover:text-slate-300 cursor-pointer transition-colors">شروط الاستخدام</span>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper(".heroSwiper", {
            effect: "fade",
            fadeEffect: {
                crossFade: true 
            },
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
        });

        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 50) {
                header.classList.add('bg-slate-900/90', 'shadow-lg');
                header.classList.remove('glass-navbar');
            } else {
                header.classList.remove('bg-slate-900/90', 'shadow-lg');
                header.classList.add('glass-navbar');
            }
        });
    </script>
</body>
</html>