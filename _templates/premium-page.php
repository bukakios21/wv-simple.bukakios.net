<?php
/**
 * BukaKios WebView Simple — Premium Page Template
 * A rich, modern, fintech-style page template with glassmorphism,
 * animated elements, and premium visual design inspired by DANA, GoPay, OVO.
 * 
 * Usage: Include this file and set variables before including:
 * 
 *   $page_title = "Judul Halaman";
 *   $page_subtitle = "Deskripsi halaman";
 *   $page_icon = "fas fa-wallet"; // FontAwesome icon class
 *   include '_templates/premium-page.php';
 * 
 * Or copy this file and customize as needed.
 */

// Default values if not set
if (!isset($html_title)) $html_title = "BukaKios";
if (!isset($page_title)) $page_title = "Premium Page";
if (!isset($page_subtitle)) $page_subtitle = "Nikmati pengalaman terbaik dengan fitur premium kami";
if (!isset($page_icon)) $page_icon = "fas fa-star";
if (!isset($primary)) $primary = "#1a7fce";
if (!isset($secondary)) $secondary = "#8b5cf6";
if (!isset($accent)) $accent = "#ec4899";
if (!isset($c_url)) $c_url = "https://wv-simple.bukakios.net";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title><?php echo $html_title; ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'brand': '<?php echo $primary; ?>',
                        'brand-dark': '<?php echo $primary; ?>',
                        'purple-accent': '<?php echo $secondary; ?>',
                        'pink-accent': '<?php echo $accent; ?>',
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'float-slow': 'float 8s ease-in-out infinite',
                        'float-delayed': 'float 7s ease-in-out infinite 2s',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-subtle': 'bounce-subtle 2s ease-in-out infinite',
                        'shimmer': 'shimmer 2s linear infinite',
                        'gradient': 'gradient 8s ease infinite',
                        'spin-slow': 'spin 20s linear infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px) rotate(0deg)' },
                            '50%': { transform: 'translateY(-20px) rotate(5deg)' },
                        },
                        'bounce-subtle': {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-5px)' },
                        },
                        shimmer: {
                            '0%': { backgroundPosition: '-200% 0' },
                            '100%': { backgroundPosition: '200% 0' },
                        },
                        gradient: {
                            '0%, 100%': { backgroundSize: '200% 200%', backgroundPosition: 'left center' },
                            '50%': { backgroundSize: '200% 200%', backgroundPosition: 'right center' },
                        },
                    },
                    backgroundImage: {
                        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                        'gradient-conic': 'conic-gradient(from 180deg at 50% 50%, var(--tw-gradient-stops))',
                    },
                    boxShadow: {
                        'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                        'medium': '0 4px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 30px -5px rgba(0, 0, 0, 0.1)',
                        'hard': '0 10px 40px -10px rgba(0, 0, 0, 0.2), 0 20px 50px -15px rgba(0, 0, 0, 0.15)',
                        'glow-brand': '0 0 40px rgba(26, 127, 206, 0.3)',
                        'glow-purple': '0 0 40px rgba(139, 92, 246, 0.3)',
                        'glow-pink': '0 0 40px rgba(236, 72, 153, 0.3)',
                    }
                }
            }
        }
    </script>
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, <?php echo $primary; ?>, <?php echo $secondary; ?>);
            border-radius: 10px;
        }
        
        /* Glassmorphism card */
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .glass-card-dark {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Gradient border */
        .gradient-border {
            position: relative;
            background: white;
            border-radius: 1rem;
        }
        .gradient-border::before {
            content: '';
            position: absolute;
            inset: -2px;
            background: linear-gradient(135deg, <?php echo $primary; ?>, <?php echo $secondary; ?>, <?php echo $accent; ?>);
            border-radius: inherit;
            z-index: -1;
        }
        
        /* Shimmer effect */
        .shimmer {
            background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.4) 50%, transparent 100%);
            background-size: 200% 100%;
            animation: shimmer 2s linear infinite;
        }
        
        /* Animated gradient background */
        .animated-gradient-bg {
            background: linear-gradient(-45deg, <?php echo $primary; ?>20, <?php echo $secondary; ?>20, <?php echo $accent; ?>20, #10b98120);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }
        
        /* Grid pattern overlay */
        .grid-pattern {
            background-image: 
                linear-gradient(rgba(26, 127, 206, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(26, 127, 206, 0.03) 1px, transparent 1px);
            background-size: 30px 30px;
        }
        
        /* Dot pattern overlay */
        .dot-pattern {
            background-image: radial-gradient(rgba(26, 127, 206, 0.1) 1px, transparent 1px);
            background-size: 20px 20px;
        }
        
        /* Floating blob animation */
        @keyframes blob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(20px, -30px) scale(1.1); }
            50% { transform: translate(-20px, 20px) scale(0.9); }
            75% { transform: translate(30px, 10px) scale(1.05); }
        }
        
        .blob {
            animation: blob 7s ease-in-out infinite;
        }
        
        /* Pulse ring animation */
        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 1; }
            100% { transform: scale(1.5); opacity: 0; }
        }
        
        .pulse-ring::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            border: 2px solid currentColor;
            animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        /* Step connector animation */
        .step-connector {
            background: linear-gradient(90deg, <?php echo $primary; ?>, <?php echo $secondary; ?>);
            height: 3px;
            position: relative;
            overflow: hidden;
        }
        .step-connector::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 30%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.8), transparent);
            animation: shimmer 2s linear infinite;
        }
        
        /* Loading overlay */
        .loading-overlay {
            background: linear-gradient(135deg, <?php echo $primary; ?> 0%, <?php echo $secondary; ?> 100%);
        }
        
        /* Progress bar animation */
        @keyframes progress {
            0% { width: 0%; }
            100% { width: 100%; }
        }
        
        .progress-animate {
            animation: progress 2s ease-out forwards;
        }
        
        /* Safe area padding for mobile */
        @supports (padding: max(0px)) {
            .safe-bottom {
                padding-bottom: max(1rem, env(safe-area-inset-bottom));
            }
            .safe-top {
                padding-top: max(1rem, env(safe-area-inset-top));
            }
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 font-inter antialiased overflow-x-hidden">
    
    <!-- =====================================================
         FLOATING BACKGROUND DECORATIONS
    ====================================================== -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <!-- Animated Gradient Background -->
        <div class="absolute inset-0 animated-gradient-bg"></div>
        
        <!-- Grid Pattern -->
        <div class="absolute inset-0 grid-pattern"></div>
        
        <!-- Dot Pattern -->
        <div class="absolute inset-0 dot-pattern"></div>
        
        <!-- Large Blobs -->
        <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full opacity-30 blob" style="background: radial-gradient(circle, <?php echo $primary; ?>40, transparent 70%);"></div>
        <div class="absolute top-1/3 -left-20 w-72 h-72 rounded-full opacity-20 blob" style="background: radial-gradient(circle, <?php echo $secondary; ?>50, transparent 70%); animation-delay: -3s;"></div>
        <div class="absolute bottom-20 right-10 w-80 h-80 rounded-full opacity-25 blob" style="background: radial-gradient(circle, <?php echo $accent; ?>40, transparent 70%); animation-delay: -5s;"></div>
        
        <!-- Decorative Circles -->
        <div class="absolute top-32 right-10 w-4 h-4 rounded-full bg-<?php echo $primary; ?> opacity-60 animate-float"></div>
        <div class="absolute top-48 left-16 w-3 h-3 rounded-full bg-purple-500 opacity-50 animate-float-slow"></div>
        <div class="absolute bottom-40 left-20 w-5 h-5 rounded-full bg-pink-500 opacity-40 animate-float-delayed"></div>
        <div class="absolute top-1/2 right-1/4 w-2 h-2 rounded-full bg-emerald-500 opacity-60 animate-pulse"></div>
        
        <!-- Abstract Shapes -->
        <div class="absolute top-20 left-1/4 w-16 h-16 border-2 border-brand/20 rotate-45 animate-spin-slow rounded-xl"></div>
        <div class="absolute bottom-32 right-1/3 w-12 h-12 border-2 border-purple-500/20 rotate-12 animate-spin-slow rounded-lg" style="animation-direction: reverse;"></div>
    </div>
    
    <!-- =====================================================
         LOADING OVERLAY
    ====================================================== -->
    <div id="loadingOverlay" class="fixed inset-0 z-[100] loading-overlay flex items-center justify-center transition-opacity duration-500">
        <div class="text-center">
            <!-- Animated Logo -->
            <div class="relative w-20 h-20 mx-auto mb-6">
                <div class="absolute inset-0 rounded-2xl bg-white/20 backdrop-blur-sm animate-pulse"></div>
                <div class="absolute inset-2 rounded-xl bg-white flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-3xl text-brand"></i>
                </div>
                <!-- Spinning ring -->
                <div class="absolute inset-0 rounded-2xl border-4 border-transparent border-t-white animate-spin"></div>
            </div>
            
            <!-- Loading Text -->
            <p class="text-white font-semibold text-lg mb-3">Memuat...</p>
            
            <!-- Progress Bar -->
            <div class="w-48 h-1.5 bg-white/30 rounded-full overflow-hidden mx-auto">
                <div id="loadingProgress" class="h-full bg-white rounded-full progress-animate" style="width: 0%;"></div>
            </div>
            
            <!-- Loading dots -->
            <div class="flex justify-center gap-2 mt-4">
                <div class="w-2 h-2 bg-white rounded-full animate-bounce" style="animation-delay: 0s;"></div>
                <div class="w-2 h-2 bg-white rounded-full animate-bounce" style="animation-delay: 0.1s;"></div>
                <div class="w-2 h-2 bg-white rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
            </div>
        </div>
    </div>
    
    <!-- =====================================================
         STICKY HEADER
    ====================================================== -->
    <header class="sticky top-0 z-50 safe-top">
        <div class="glass-card mx-4 mt-2 rounded-2xl shadow-soft">
            <div class="flex items-center justify-between px-4 py-3">
                <!-- Back Button -->
                <button onclick="history.back()" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all duration-200 active:scale-95">
                    <i class="fas fa-arrow-left text-slate-600"></i>
                </button>
                
                <!-- Title -->
                <h1 class="font-bold text-slate-800 text-sm truncate max-w-[60%]"><?php echo $page_title; ?></h1>
                
                <!-- Actions -->
                <div class="flex items-center gap-2">
                    <button class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all duration-200 active:scale-95">
                        <i class="fas fa-bell text-slate-600"></i>
                    </button>
                    <button class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-all duration-200 active:scale-95">
                        <i class="fas fa-ellipsis-v text-slate-600"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>
    
    <!-- =====================================================
         MAIN CONTENT
    ====================================================== -->
    <main class="relative z-10 pb-8">
        
        <!-- =====================================================
             HERO SECTION
        ====================================================== -->
        <section class="px-4 pt-6 pb-8">
            <div class="relative">
                <!-- Gradient Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gradient-to-r from-brand/10 to-purple-500/10 border border-brand/20 mb-4 animate-fade-in">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-semibold text-brand">Premium Features</span>
                </div>
                
                <!-- Main Title with Gradient -->
                <h2 class="text-3xl md:text-4xl font-extrabold mb-3 leading-tight">
                    <span class="bg-gradient-to-r from-slate-800 via-brand to-purple-600 bg-clip-text text-transparent"><?php echo $page_title; ?></span>
                </h2>
                
                <!-- Subtitle -->
                <p class="text-slate-600 text-base md:text-lg leading-relaxed max-w-xl">
                    <?php echo $page_subtitle; ?>
                </p>
                
                <!-- Stats Row -->
                <div class="flex items-center gap-6 mt-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold bg-gradient-to-r from-brand to-purple-500 bg-clip-text text-transparent">10K+</div>
                        <div class="text-xs text-slate-500">Pengguna Aktif</div>
                    </div>
                    <div class="w-px h-10 bg-slate-200"></div>
                    <div class="text-center">
                        <div class="text-2xl font-bold bg-gradient-to-r from-purple-500 to-pink-500 bg-clip-text text-transparent">4.9</div>
                        <div class="text-xs text-slate-500">Rating Aplikasi</div>
                    </div>
                    <div class="w-px h-10 bg-slate-200"></div>
                    <div class="text-center">
                        <div class="text-2xl font-bold bg-gradient-to-r from-emerald-500 to-teal-500 bg-clip-text text-transparent">99%</div>
                        <div class="text-xs text-slate-500">Uptime Server</div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- =====================================================
             INFO CARD WITH ICON
        ====================================================== -->
        <section class="px-4 mb-6">
            <div class="glass-card rounded-3xl p-6 shadow-medium border border-white/50">
                <div class="flex items-start gap-4">
                    <!-- Large Icon with Gradient Background -->
                    <div class="relative flex-shrink-0">
                        <div class="absolute inset-0 bg-gradient-to-br from-brand to-purple-500 rounded-2xl blur-lg opacity-30"></div>
                        <div class="relative w-16 h-16 rounded-2xl bg-gradient-to-br from-brand to-purple-500 flex items-center justify-center shadow-glow-brand">
                            <i class="<?php echo $page_icon; ?> text-2xl text-white"></i>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-slate-800 text-lg mb-1">Informasi Penting</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            Pastikan data Anda sudah benar sebelum melanjutkan. 
                            Tim support kami siap membantu 24/7 jika ada pertanyaan.
                        </p>
                        
                        <!-- Action Link -->
                        <a href="#" class="inline-flex items-center gap-2 mt-3 text-brand font-semibold text-sm hover:gap-3 transition-all">
                            <span>Hubungi Support</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- =====================================================
             PROGRESS / STEP INDICATOR
        ====================================================== -->
        <section class="px-4 mb-6">
            <div class="glass-card rounded-3xl p-5 shadow-soft">
                <h3 class="font-bold text-slate-800 text-sm mb-4">Langkah Penggunaan</h3>
                
                <div class="flex items-center justify-between">
                    <!-- Step 1 -->
                    <div class="flex flex-col items-center">
                        <div class="relative">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand to-purple-500 flex items-center justify-center text-white font-bold text-sm shadow-glow-brand">
                                1
                            </div>
                            <div class="absolute -inset-1 rounded-full border-2 border-brand/30 animate-ping"></div>
                        </div>
                        <span class="text-xs text-slate-600 mt-2 text-center leading-tight">Pilih<br>Layanan</span>
                    </div>
                    
                    <!-- Connector 1 -->
                    <div class="flex-1 mx-2 h-1 rounded-full bg-gradient-to-r from-brand to-purple-500 relative overflow-hidden">
                        <div class="absolute inset-0 shimmer"></div>
                    </div>
                    
                    <!-- Step 2 -->
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand to-purple-500 flex items-center justify-center text-white font-bold text-sm">
                            2
                        </div>
                        <span class="text-xs text-slate-600 mt-2 text-center leading-tight">Masukkan<br>Data</span>
                    </div>
                    
                    <!-- Connector 2 -->
                    <div class="flex-1 mx-2 h-1 rounded-full bg-slate-200"></div>
                    
                    <!-- Step 3 -->
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-400 font-bold text-sm">
                            3
                        </div>
                        <span class="text-xs text-slate-400 mt-2 text-center leading-tight">Proses<br>Pembayaran</span>
                    </div>
                    
                    <!-- Connector 3 -->
                    <div class="flex-1 mx-2 h-1 rounded-full bg-slate-200"></div>
                    
                    <!-- Step 4 -->
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-400 font-bold text-sm">
                            4
                        </div>
                        <span class="text-xs text-slate-400 mt-2 text-center leading-tight">Selesai</span>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- =====================================================
             ACTION CARDS (3 OPTIONS)
        ====================================================== -->
        <section class="px-4 mb-6">
            <h3 class="font-bold text-slate-800 text-lg mb-4">Opsi Tersedia</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Card 1: Pulsa -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-brand to-purple-500 rounded-2xl blur-lg opacity-20 group-hover:opacity-40 transition-opacity"></div>
                    <div class="relative glass-card rounded-2xl p-5 shadow-medium hover:shadow-hard transition-all duration-300 hover:-translate-y-1 cursor-pointer">
                        <!-- Icon -->
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand to-brand/80 flex items-center justify-center mb-4 shadow-soft">
                            <i class="fas fa-mobile-alt text-2xl text-white"></i>
                        </div>
                        
                        <!-- Title & Desc -->
                        <h4 class="font-bold text-slate-800 mb-1">Pulsa & Data</h4>
                        <p class="text-slate-500 text-sm mb-3">Pulsa all operator dengan harga terbaik</p>
                        
                        <!-- Price -->
                        <div class="flex items-baseline gap-1">
                            <span class="text-xs text-slate-400">Mulai dari</span>
                            <span class="font-bold text-brand text-lg">Rp 1.000</span>
                        </div>
                        
                        <!-- Arrow -->
                        <div class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center group-hover:bg-brand group-hover:text-white transition-all">
                            <i class="fas fa-arrow-right text-xs"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Card 2: E-Wallet -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl blur-lg opacity-20 group-hover:opacity-40 transition-opacity"></div>
                    <div class="relative glass-card rounded-2xl p-5 shadow-medium hover:shadow-hard transition-all duration-300 hover:-translate-y-1 cursor-pointer">
                        <!-- Icon -->
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center mb-4 shadow-soft">
                            <i class="fas fa-wallet text-2xl text-white"></i>
                        </div>
                        
                        <!-- Title & Desc -->
                        <h4 class="font-bold text-slate-800 mb-1">E-Wallet</h4>
                        <p class="text-slate-500 text-sm mb-3">Top up DANA, OVO, GoPay, ShopeePay</p>
                        
                        <!-- Price -->
                        <div class="flex items-baseline gap-1">
                            <span class="text-xs text-slate-400">Mulai dari</span>
                            <span class="font-bold text-purple-500 text-lg">Rp 10.000</span>
                        </div>
                        
                        <!-- Arrow -->
                        <div class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center group-hover:bg-purple-500 group-hover:text-white transition-all">
                            <i class="fas fa-arrow-right text-xs"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Card 3: Tagihan -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl blur-lg opacity-20 group-hover:opacity-40 transition-opacity"></div>
                    <div class="relative glass-card rounded-2xl p-5 shadow-medium hover:shadow-hard transition-all duration-300 hover:-translate-y-1 cursor-pointer">
                        <!-- Icon -->
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center mb-4 shadow-soft">
                            <i class="fas fa-file-invoice-dollar text-2xl text-white"></i>
                        </div>
                        
                        <!-- Title & Desc -->
                        <h4 class="font-bold text-slate-800 mb-1">Bayar Tagihan</h4>
                        <p class="text-slate-500 text-sm mb-3">PLN, PDAM, Internet, TV Kabel</p>
                        
                        <!-- Price -->
                        <div class="flex items-baseline gap-1">
                            <span class="text-xs text-slate-400">Mulai dari</span>
                            <span class="font-bold text-emerald-500 text-lg">Rp 5.000</span>
                        </div>
                        
                        <!-- Arrow -->
                        <div class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-all">
                            <i class="fas fa-arrow-right text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- =====================================================
             FEATURE PILLS
        ====================================================== -->
        <section class="px-4 mb-6">
            <h3 class="font-bold text-slate-800 text-lg mb-4">Keunggulan Kami</h3>
            
            <div class="flex flex-wrap gap-3">
                <!-- Pill 1 -->
                <div class="inline-flex items-center gap-3 px-4 py-3 rounded-2xl bg-white shadow-soft border border-slate-100 hover:shadow-medium hover:border-brand/20 transition-all duration-300">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand/10 to-brand/5 flex items-center justify-center">
                        <i class="fas fa-bolt text-brand"></i>
                    </div>
                    <div>
                        <div class="font-semibold text-slate-800 text-sm">Proses Cepat</div>
                        <div class="text-xs text-slate-500">Maksimal 1 menit</div>
                    </div>
                </div>
                
                <!-- Pill 2 -->
                <div class="inline-flex items-center gap-3 px-4 py-3 rounded-2xl bg-white shadow-soft border border-slate-100 hover:shadow-medium hover:border-purple-500/20 transition-all duration-300">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500/10 to-purple-500/5 flex items-center justify-center">
                        <i class="fas fa-shield-alt text-purple-500"></i>
                    </div>
                    <div>
                        <div class="font-semibold text-slate-800 text-sm">100% Aman</div>
                        <div class="text-xs text-slate-500">Enkripsi end-to-end</div>
                    </div>
                </div>
                
                <!-- Pill 3 -->
                <div class="inline-flex items-center gap-3 px-4 py-3 rounded-2xl bg-white shadow-soft border border-slate-100 hover:shadow-medium hover:border-pink-500/20 transition-all duration-300">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-pink-500/10 to-pink-500/5 flex items-center justify-center">
                        <i class="fas fa-headset text-pink-500"></i>
                    </div>
                    <div>
                        <div class="font-semibold text-slate-800 text-sm">Support 24/7</div>
                        <div class="text-xs text-slate-500">Always ready to help</div>
                    </div>
                </div>
                
                <!-- Pill 4 -->
                <div class="inline-flex items-center gap-3 px-4 py-3 rounded-2xl bg-white shadow-soft border border-slate-100 hover:shadow-medium hover:border-emerald-500/20 transition-all duration-300">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500/10 to-emerald-500/5 flex items-center justify-center">
                        <i class="fas fa-tags text-emerald-500"></i>
                    </div>
                    <div>
                        <div class="font-semibold text-slate-800 text-sm">Harga Murah</div>
                        <div class="text-xs text-slate-500">Bersaing di pasaran</div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- =====================================================
             CTA SECTION
        ====================================================== -->
        <section class="px-4 mb-6">
            <div class="relative overflow-hidden rounded-3xl">
                <!-- Background Gradient -->
                <div class="absolute inset-0 bg-gradient-to-br from-brand via-purple-500 to-pink-500"></div>
                
                <!-- Decorative elements -->
                <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2"></div>
                
                <!-- Content -->
                <div class="relative z-10 px-6 py-8 text-center">
                    <h3 class="text-white font-bold text-xl mb-2">Siap Memulai?</h3>
                    <p class="text-white/80 text-sm mb-6 max-w-sm mx-auto">Bergabung dengan ribuan pengguna yang sudah merasakan kemudahan bertransaksi</p>
                    
                    <button class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl bg-white text-brand font-bold text-base shadow-hard hover:shadow-glow-brand hover:scale-105 transition-all duration-300 active:scale-95 animate-pulse-slow">
                        <span>Mulai Sekarang</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </section>
        
        <!-- =====================================================
             ADDITIONAL CONTENT CARDS
        ====================================================== -->
        <section class="px-4 mb-6">
            <div class="glass-card rounded-3xl p-6 shadow-medium border border-white/50">
                <h3 class="font-bold text-slate-800 text-lg mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-line text-brand"></i>
                    Statistik Layanan
                </h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 rounded-2xl bg-gradient-to-br from-slate-50 to-slate-100">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 rounded-xl bg-brand/10 flex items-center justify-center">
                                <i class="fas fa-shopping-cart text-brand"></i>
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-slate-800">125.430</div>
                        <div class="text-xs text-slate-500">Total Transaksi</div>
                    </div>
                    
                    <div class="p-4 rounded-2xl bg-gradient-to-br from-slate-50 to-slate-100">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center">
                                <i class="fas fa-users text-purple-500"></i>
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-slate-800">10.234</div>
                        <div class="text-xs text-slate-500">Pengguna Aktif</div>
                    </div>
                    
                    <div class="p-4 rounded-2xl bg-gradient-to-br from-slate-50 to-slate-100">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                                <i class="fas fa-check-circle text-emerald-500"></i>
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-slate-800">99.9%</div>
                        <div class="text-xs text-slate-500">Success Rate</div>
                    </div>
                    
                    <div class="p-4 rounded-2xl bg-gradient-to-br from-slate-50 to-slate-100">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center">
                                <i class="fas fa-star text-amber-500"></i>
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-slate-800">4.9/5</div>
                        <div class="text-xs text-slate-500">Rating Pelanggan</div>
                    </div>
                </div>
            </div>
        </section>
        
    </main>
    
    <!-- =====================================================
         RICH FOOTER WITH SECURITY & SOCIAL PROOF
    ====================================================== -->
    <footer class="relative z-10 safe-bottom">
        <!-- Security Badges -->
        <div class="px-4 mb-6">
            <div class="glass-card rounded-3xl p-5 shadow-soft">
                <h4 class="font-bold text-slate-800 text-sm mb-4 text-center">Keamanan & Privasi</h4>
                
                <div class="grid grid-cols-3 gap-3">
                    <!-- SSL Badge -->
                    <div class="flex flex-col items-center text-center">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center mb-2">
                            <i class="fas fa-lock text-emerald-500 text-lg"></i>
                        </div>
                        <span class="text-xs font-medium text-slate-600">SSL Secure</span>
                    </div>
                    
                    <!-- Verified Badge -->
                    <div class="flex flex-col items-center text-center">
                        <div class="w-12 h-12 rounded-2xl bg-brand/10 flex items-center justify-center mb-2">
                            <i class="fas fa-shield-alt text-brand text-lg"></i>
                        </div>
                        <span class="text-xs font-medium text-slate-600">Terverified</span>
                    </div>
                    
                    <!-- Encrypted Badge -->
                    <div class="flex flex-col items-center text-center">
                        <div class="w-12 h-12 rounded-2xl bg-purple-50 flex items-center justify-center mb-2">
                            <i class="fas fa-key text-purple-500 text-lg"></i>
                        </div>
                        <span class="text-xs font-medium text-slate-600">Encrypted</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Social Proof -->
        <div class="px-4 mb-6">
            <div class="flex items-center justify-center gap-2 text-slate-500 text-sm">
                <div class="flex -space-x-2">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-brand to-purple-500 border-2 border-white flex items-center justify-center text-white text-xs font-bold">A</div>
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 border-2 border-white flex items-center justify-center text-white text-xs font-bold">B</div>
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-pink-500 to-amber-500 border-2 border-white flex items-center justify-center text-white text-xs font-bold">C</div>
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-500 to-teal-500 border-2 border-white flex items-center justify-center text-white text-xs font-bold">D</div>
                </div>
                <span>Dipercaya <strong class="text-slate-700">10.000+</strong> pengguna</span>
            </div>
        </div>
        
        <!-- Payment Methods -->
        <div class="px-4 mb-6">
            <div class="flex items-center justify-center gap-4">
                <div class="px-4 py-2 rounded-xl bg-white shadow-soft">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa" class="h-4 object-contain opacity-60">
                </div>
                <div class="px-4 py-2 rounded-xl bg-white shadow-soft">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard" class="h-4 object-contain opacity-60">
                </div>
                <div class="px-4 py-2 rounded-xl bg-white shadow-soft">
                    <div class="text-xs font-bold text-slate-400">BCA</div>
                </div>
                <div class="px-4 py-2 rounded-xl bg-white shadow-soft">
                    <div class="text-xs font-bold text-slate-400">MANDIRI</div>
                </div>
            </div>
        </div>
        
        <!-- Main Footer -->
        <div class="bg-gradient-to-t from-slate-100 to-transparent pt-6 pb-4">
            <div class="px-4 text-center">
                <!-- Logo & Name -->
                <div class="flex items-center justify-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand to-purple-500 flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-white"></i>
                    </div>
                    <span class="font-bold text-slate-800 text-lg">BukaKios</span>
                </div>
                
                <!-- Links -->
                <div class="flex flex-wrap items-center justify-center gap-x-4 gap-y-2 text-sm mb-4">
                    <a href="#" class="text-slate-500 hover:text-brand transition-colors">Tentang</a>
                    <span class="text-slate-300">•</span>
                    <a href="#" class="text-slate-500 hover:text-brand transition-colors">Kebijakan Privasi</a>
                    <span class="text-slate-300">•</span>
                    <a href="#" class="text-slate-500 hover:text-brand transition-colors">Syarat & Ketentuan</a>
                    <span class="text-slate-300">•</span>
                    <a href="#" class="text-slate-500 hover:text-brand transition-colors">Kontak</a>
                </div>
                
                <!-- Social Media -->
                <div class="flex items-center justify-center gap-3 mb-4">
                    <a href="#" class="w-10 h-10 rounded-xl bg-white shadow-soft hover:shadow-medium flex items-center justify-center text-slate-400 hover:text-brand transition-all">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-xl bg-white shadow-soft hover:shadow-medium flex items-center justify-center text-slate-400 hover:text-brand transition-all">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-xl bg-white shadow-soft hover:shadow-medium flex items-center justify-center text-slate-400 hover:text-brand transition-all">
                        <i class="fab fa-tiktok"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-xl bg-white shadow-soft hover:shadow-medium flex items-center justify-center text-slate-400 hover:text-brand transition-all">
                        <i class="fab fa-twitter"></i>
                    </a>
                </div>
                
                <!-- Copyright -->
                <p class="text-xs text-slate-400">
                    &copy; <?php echo date('Y'); ?> BukaKios. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
    
    <!-- =====================================================
         JAVASCRIPT INTERACTIONS
    ====================================================== -->
    <script>
        // ==========================================
        // LOADING OVERLAY
        // ==========================================
        document.addEventListener('DOMContentLoaded', function() {
            // Hide loading overlay after page loads
            setTimeout(function() {
                const loadingOverlay = document.getElementById('loadingOverlay');
                if (loadingOverlay) {
                    loadingOverlay.style.opacity = '0';
                    setTimeout(function() {
                        loadingOverlay.style.display = 'none';
                    }, 500);
                }
            }, 1500);
        });
        
        // ==========================================
        // SMOOTH SCROLL
        // ==========================================
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // ==========================================
        // CARD CLICK HANDLERS
        // ==========================================
        document.querySelectorAll('.glass-card').forEach(card => {
            card.addEventListener('click', function() {
                // Add ripple effect
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });
        
        // ==========================================
        // INTERSECTION OBSERVER FOR ANIMATIONS
        // ==========================================
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('section').forEach(section => {
            section.style.opacity = '0';
            section.style.transform = 'translateY(20px)';
            section.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(section);
        });
        
        // ==========================================
        // BUTTON RIPPLE EFFECT
        // ==========================================
        document.querySelectorAll('button').forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    position: absolute;
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    background: rgba(255, 255, 255, 0.5);
                    border-radius: 50%;
                    transform: scale(0);
                    animation: ripple 0.6s linear;
                    pointer-events: none;
                `;
                
                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);
                
                setTimeout(() => ripple.remove(), 600);
            });
        });
        
        // ==========================================
        // HAPTIC FEEDBACK SIMULATION
        // ==========================================
        function hapticFeedback() {
            if (navigator.vibrate) {
                navigator.vibrate(10);
            }
        }
        
        document.querySelectorAll('button').forEach(button => {
            button.addEventListener('click', hapticFeedback);
        });
        
        // ==========================================
        // SCROLL PROGRESS INDICATOR
        // ==========================================
        window.addEventListener('scroll', function() {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            
            // You can add a scroll progress bar if needed
            // document.getElementById('scrollProgress').style.width = scrolled + '%';
        });
        
        // ==========================================
        // PARALLAX EFFECT FOR DECORATIONS
        // ==========================================
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const decorations = document.querySelectorAll('.blob');
            
            decorations.forEach((blob, index) => {
                const speed = 0.05 * (index + 1);
                blob.style.transform = `translateY(${scrolled * speed}px)`;
            });
        });
        
        // ==========================================
        // LONG PRESS TO COPY (for phone numbers, etc.)
        // ==========================================
        document.querySelectorAll('[data-copy]').forEach(element => {
            let pressTimer;
            
            element.addEventListener('touchstart', function() {
                pressTimer = setTimeout(() => {
                    const text = this.dataset.copy;
                    navigator.clipboard.writeText(text).then(() => {
                        // Show toast notification
                        showToast('Teks berhasil disalin!');
                    });
                }, 500);
            });
            
            element.addEventListener('touchend', function() {
                clearTimeout(pressTimer);
            });
        });
        
        // ==========================================
        // TOAST NOTIFICATION
        // ==========================================
        function showToast(message, duration = 3000) {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-20 left-1/2 -translate-x-1/2 px-6 py-3 bg-slate-800 text-white text-sm font-medium rounded-full shadow-hard z-[200] animate-bounce-in';
            toast.textContent = message;
            toast.style.animation = 'bounce-in 0.3s ease';
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }
        
        // ==========================================
        // DARK MODE TOGGLE (if needed)
        // ==========================================
        // Uncomment to enable dark mode
        /*
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
        }
        
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
        */
    </script>
    
    <!-- CSS for JavaScript animations -->
    <style>
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
        
        @keyframes bounce-in {
            0% {
                opacity: 0;
                transform: translateX(-50%) translateY(20px) scale(0.8);
            }
            50% {
                transform: translateX(-50%) translateY(-5px) scale(1.05);
            }
            100% {
                opacity: 1;
                transform: translateX(-50%) translateY(0) scale(1);
            }
        }
        
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fade-in 0.6s ease forwards;
        }
    </style>
</body>
</html>
