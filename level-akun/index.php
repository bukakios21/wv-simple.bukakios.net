<?php
require_once("../config.php");
require_once("../_session.php");
require_once("../lib/ApiV2.php");

$api_v2 = new ApiV2($user_jwt);
$user = $api_v2->detail_user();
$user_res = json_decode($user, true);

$is_error = false;
if (!isset($user_res['status'])) {
    $is_error = true;
    $error_msg = "error call api server";
}

if (isset($user_res['status']) && $user_res['status'] == 0) {
    $is_error = true;
    $error_msg = $user_res['error_msg'];
}

if ($is_error) {
    $html_title = "title::Error";
    $lyt_button_link = "opentranslate://10|pulsa";
    $lyt_button_name = "KEMBALI KE DASHBOARD";
    $lyt_image = "https://assets.bukakios.net/img/illustration/bc_logout.png";
    $lyt_title = "Error!";
    $lyt_description = $error_msg;
    require_once(ROOT . "/_template/general_message.php");
    exit;
}

$data_user = $user_res['data'];
$img = $data_user['image'] ?? '';
$user_name = $data_user['nama'] ?? 'User';

$hp = $data_user['verif_hp'] ?? 0;
$email = $data_user['verif_email'] ?? 0;
$ktp = $data_user['verif_user'] ?? 0;
$premium = $data_user['is_ultimate'] ?? 0;
$saldo = $data_user['saldo'] ?? 0;

$levelApi = $api_v2->getLevelUser();
$levelApiDec = json_decode($levelApi, true);
$level = '';
$limit_saldo = 0;
$max_nominal_topup = 0;
$max_nominal_trx = 0;
$max_akumulasi_trx = 0;

if (isset($levelApiDec['status']) && $levelApiDec['status'] == 1) {
    $dataLevel = $levelApiDec['data'];
    $limit_saldo = $dataLevel['limit_saldo'] ?? 0;
    $max_nominal_topup = $dataLevel['max_nominal_topup'] ?? 0;
    $max_nominal_trx = $dataLevel['max_nominal_trx'] ?? 0;
    $max_akumulasi_trx = $dataLevel['max_akumulasi_trx'] ?? 0;
    $level = $dataLevel['level'] ?? '';
}

$link = "https://wv.bukakios.net/verif";

// Count unverified items
$unverified_count = (($hp != 1) ? 1 : 0) + (($email != 1) ? 1 : 0) + (($ktp != 1) ? 1 : 0);
$total_verif = 3;
$verif_done = $total_verif - $unverified_count;
$verif_percent = round(($verif_done / $total_verif) * 100);
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Level Akun - BukaKios</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: '#1a7fce',
            brandDark: '#1265a6',
          },
          boxShadow: {
            card: '0 5px 14px rgba(16, 24, 40, 0.07)',
            soft: '0 4px 12px rgba(15, 23, 42, 0.06)',
            glow: '0 0 40px rgba(26, 127, 206, 0.15)',
            'glow-sm': '0 0 20px rgba(26, 127, 206, 0.1)',
          },
          fontFamily: {
            sans: ['Inter', 'ui-sans-serif', 'system-ui', 'Segoe UI', 'Roboto', 'Arial', 'sans-serif'],
          }
        }
      }
    }
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body { font-family: 'Inter', sans-serif; }
    @keyframes shine {
      0% { transform: translateX(-100%); }
      100% { transform: translateX(200%); }
    }
    .animate-shine {
      animation: shine 2s ease-in-out infinite;
    }
  </style>
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-950 antialiased pb-8">

  <!-- Background Decorations -->
  <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
    <!-- Top Gradient Blob -->
    <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-brand/20 to-purple-500/10 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute -top-20 -right-20 w-60 h-60 bg-gradient-to-br from-brand/30 to-blue-500/20 rounded-full blur-2xl"></div>
    <!-- Bottom Gradient Blob -->
    <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-tr from-emerald-500/10 to-brand/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
    <!-- Grid Pattern -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#00000005_1px,transparent_1px),linear-gradient(to_bottom,#00000005_1px,transparent_1px)] bg-[size:24px_24px]"></div>
  </div>

  <!-- Header -->
  <header class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-slate-100">
    <div class="flex items-center gap-3 px-4 py-3">
      <div class="h-1 flex-1 rounded-full bg-slate-100 overflow-hidden">
        <div class="h-full w-full rounded-full bg-brand transition-all duration-500"></div>
      </div>
      <div class="shrink-0 text-[16px] font-bold tracking-[-0.04em] text-brand">BukaKios</div>
    </div>
  </header>

  <main class="px-4 pt-6">

    <!-- Hero Section -->
    <div class="relative mb-8">
      <!-- Gradient Card -->
      <div class="relative overflow-hidden bg-gradient-to-br from-brand via-brandDark to-[#0d4a7c] rounded-2xl p-5 shadow-glow">
        <!-- Decorative Elements -->
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/5 rounded-full blur-xl"></div>
        <div class="absolute top-4 right-4 w-20 h-20 border border-white/20 rounded-full"></div>
        <div class="absolute bottom-4 right-12 w-12 h-12 border border-white/10 rounded-full"></div>
        
        <!-- Content -->
        <div class="relative">
          <div class="flex items-center gap-3">
            <!-- Avatar with glow -->
            <div class="relative">
              <div class="absolute inset-0 bg-white/30 rounded-full blur-md"></div>
              <img src="<?= htmlspecialchars($img) ?>" alt="Profile" class="relative w-14 h-14 rounded-xl border-2 border-white/50 object-cover shadow-lg">
              <?php if ($premium == 1): ?>
              <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center shadow-lg border-2 border-white">
                <svg viewBox="0 0 24 24" class="w-3.5 h-3.5 text-white" fill="currentColor">
                  <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
              </div>
              <?php endif; ?>
            </div>
            
            <!-- User Info -->
            <div class="flex-1">
              <h1 class="text-[19px] font-bold text-white"><?= htmlspecialchars($user_name) ?></h1>
              <div class="flex items-center gap-2 mt-1">
                <span class="inline-flex items-center gap-1 bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-[12px] font-bold px-3 py-1 rounded-full shadow-lg">
                  <svg viewBox="0 0 24 24" class="w-3.5 h-3.5" fill="currentColor">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                  </svg>
                  <?= htmlspecialchars($level) ?>
                </span>
                <?php if ($unverified_count > 0): ?>
                <span class="inline-flex items-center gap-1 bg-white/20 backdrop-blur text-white/90 text-[12px] font-semibold px-3 py-1 rounded-full">
                  <?= $verif_done ?>/<?= $total_verif ?> Terverifikasi
                </span>
                <?php else: ?>
                <span class="inline-flex items-center gap-1 bg-emerald-400/90 backdrop-blur text-white text-[12px] font-bold px-3 py-1 rounded-full">
                  <svg viewBox="0 0 24 24" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3">
                    <polyline points="20 6 9 17 4 12"/>
                  </svg>
                  Lengkap
                </span>
                <?php endif; ?>
              </div>
            </div>
          </div>
          
          <!-- Saldo Display -->
          <div class="mt-4 bg-white/10 backdrop-blur rounded-xl p-3 border border-white/10">
            <p class="text-white/70 text-[12px] font-medium">Saldo Tersedia</p>
            <p class="text-[23px] font-bold text-white"><?= $app->idr($saldo) ?></p>
          </div>
        </div>
      </div>
    </div>

    <!-- Limit Stats Cards -->
    <div class="grid grid-cols-2 gap-3 mb-6">
      <!-- Limit Saldo -->
      <div class="group relative bg-white rounded-2xl border border-slate-100 p-4 shadow-card hover:shadow-lg hover:border-brand/20 transition-all duration-300">
        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-brand to-purple-500 rounded-t-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand/10 to-brand/5 flex items-center justify-center mb-3">
          <svg viewBox="0 0 24 24" class="w-5 h-5 text-brand" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
            <line x1="1" y1="10" x2="23" y2="10"/>
          </svg>
        </div>
        <p class="text-[12px] text-slate-500 font-medium mb-1">Limit Saldo</p>
        <p class="text-[16px] font-bold text-slate-800"><?= $app->idr($limit_saldo) ?></p>
        <button onclick="showInfo('Limit Saldo', 'Jumlah maksimal saldo yang dapat ditransfer ke akun Anda dalam satu waktu.')" class="absolute top-3 right-3 w-7 h-7 rounded-full bg-slate-100 hover:bg-brand/10 flex items-center justify-center transition-colors">
          <svg viewBox="0 0 24 24" class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="16" x2="12" y2="12"/>
            <line x1="12" y1="8" x2="12.01" y2="8"/>
          </svg>
        </button>
      </div>

      <!-- Max Topup -->
      <div class="group relative bg-white rounded-2xl border border-slate-100 p-4 shadow-card hover:shadow-lg hover:border-emerald-500/20 transition-all duration-300">
        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-t-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500/10 to-emerald-500/5 flex items-center justify-center mb-3">
          <svg viewBox="0 0 24 24" class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="12" y1="5" x2="12" y2="19"/>
            <polyline points="19 12 12 5 5 12"/>
          </svg>
        </div>
        <p class="text-[12px] text-slate-500 font-medium mb-1">Max Topup</p>
        <p class="text-[16px] font-bold text-slate-800"><?= $app->idr($max_nominal_topup) ?></p>
        <button onclick="showInfo('Maksimal Topup', 'Batas tertinggi nominal yang dapat Anda topup dalam satu transaksi.')" class="absolute top-3 right-3 w-7 h-7 rounded-full bg-slate-100 hover:bg-emerald-500/10 flex items-center justify-center transition-colors">
          <svg viewBox="0 0 24 24" class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="16" x2="12" y2="12"/>
            <line x1="12" y1="8" x2="12.01" y2="8"/>
          </svg>
        </button>
      </div>

      <!-- Max Transaksi -->
      <div class="group relative bg-white rounded-2xl border border-slate-100 p-4 shadow-card hover:shadow-lg hover:border-amber-500/20 transition-all duration-300">
        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-t-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500/10 to-amber-500/5 flex items-center justify-center mb-3">
          <svg viewBox="0 0 24 24" class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="17 1 21 5 17 9"/>
            <path d="M3 11V9a4 4 0 0 1 4-4h14"/>
            <polyline points="7 23 3 19 7 15"/>
            <path d="M21 13v2a4 4 0 0 1-4 4H3"/>
          </svg>
        </div>
        <p class="text-[12px] text-slate-500 font-medium mb-1">Max Transaksi</p>
        <p class="text-[16px] font-bold text-slate-800"><?= $app->idr($max_nominal_trx) ?></p>
        <button onclick="showInfo('Maksimal Transaksi', 'Batas tertinggi nominal transaksi yang dapat Anda lakukan dalam satu kali transaksi.')" class="absolute top-3 right-3 w-7 h-7 rounded-full bg-slate-100 hover:bg-amber-500/10 flex items-center justify-center transition-colors">
          <svg viewBox="0 0 24 24" class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="16" x2="12" y2="12"/>
            <line x1="12" y1="8" x2="12.01" y2="8"/>
          </svg>
        </button>
      </div>

      <!-- Max Akumulasi -->
      <div class="group relative bg-white rounded-2xl border border-slate-100 p-4 shadow-card hover:shadow-lg hover:border-purple-500/20 transition-all duration-300">
        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-purple-500 to-pink-500 rounded-t-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500/10 to-purple-500/5 flex items-center justify-center mb-3">
          <svg viewBox="0 0 24 24" class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="20" x2="18" y2="10"/>
            <line x1="12" y1="20" x2="12" y2="4"/>
            <line x1="6" y1="20" x2="6" y2="14"/>
          </svg>
        </div>
        <p class="text-[12px] text-slate-500 font-medium mb-1">Max Akumulasi</p>
        <p class="text-[16px] font-bold text-slate-800"><?= $app->idr($max_akumulasi_trx) ?></p>
        <button onclick="showInfo('Maksimal Akumulasi', 'Total nominal transaksi maksimum yang dapat Anda kumulasi dalam periode tertentu.')" class="absolute top-3 right-3 w-7 h-7 rounded-full bg-slate-100 hover:bg-purple-500/10 flex items-center justify-center transition-colors">
          <svg viewBox="0 0 24 24" class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="16" x2="12" y2="12"/>
            <line x1="12" y1="8" x2="12.01" y2="8"/>
          </svg>
        </button>
      </div>
    </div>

    <!-- Verification Section -->
    <div class="bg-white rounded-3xl border border-slate-100 p-5 shadow-card mb-6">
      <div class="flex items-center justify-between mb-5">
        <div>
          <h3 class="text-[17px] font-bold text-slate-800">Verifikasi Akun</h3>
          <p class="text-[13px] text-slate-500 mt-0.5">Lengkapi verifikasi untuk naik level</p>
        </div>
        <?php if ($unverified_count > 0): ?>
        <div class="flex items-center gap-2 bg-brand/10 px-3 py-1.5 rounded-full">
          <span class="text-[13px] font-bold text-brand"><?= $verif_percent ?>%</span>
        </div>
        <?php else: ?>
        <div class="flex items-center gap-2 bg-emerald-100 px-3 py-1.5 rounded-full">
          <svg viewBox="0 0 24 24" class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="20 6 9 17 4 12"/>
          </svg>
          <span class="text-[13px] font-bold text-emerald-600">Lengkap</span>
        </div>
        <?php endif; ?>
      </div>

      <!-- Progress Bar -->
      <div class="mb-5">
        <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden">
          <div class="h-full bg-brand rounded-full transition-all duration-500 relative overflow-hidden" style="width: <?= $verif_percent ?>%">
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/60 to-transparent animate-shine"></div>
          </div>
        </div>
        <div class="flex justify-between mt-2">
          <span class="text-[12px] text-slate-400"><?= $verif_done ?> dari <?= $total_verif ?> lengkap</span>
          <span class="text-[12px] text-slate-400"><?= $unverified_count ?> tersisa</span>
        </div>
      </div>

      <!-- Verification Items -->
      <div class="space-y-3">
        <!-- HP Verification -->
        <a href="<?= $link ?>/hp" class="group flex items-center gap-4 p-4 rounded-2xl <?= $hp == 1 ? 'bg-gradient-to-r from-emerald-50 to-emerald-50/50 border border-emerald-200' : 'bg-gradient-to-r from-red-50 to-orange-50/50 border border-red-200' ?> transition-all duration-300 hover:scale-[1.01] hover:shadow-md">
          <div class="w-10 h-10 rounded-xl <?= $hp == 1 ? 'bg-gradient-to-br from-emerald-400 to-emerald-600 shadow-lg shadow-emerald-500/30' : 'bg-gradient-to-br from-red-400 to-orange-500 shadow-lg shadow-red-500/30' ?> flex items-center justify-center shrink-0">
            <?php if ($hp == 1): ?>
            <svg viewBox="0 0 24 24" class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
            <?php else: ?>
            <svg viewBox="0 0 24 24" class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="5" y="2" width="14" height="20" rx="2" ry="2"/>
              <line x1="12" y1="18" x2="12.01" y2="18"/>
            </svg>
            <?php endif; ?>
          </div>
          <div class="flex-1">
            <h4 class="text-[14px] font-semibold <?= $hp == 1 ? 'text-emerald-800' : 'text-red-800' ?>">Verifikasi HP</h4>
            <p class="text-[12px] <?= $hp == 1 ? 'text-emerald-600' : 'text-red-600' ?> mt-0.5"><?= $hp == 1 ? 'Terverifikasi' : 'Belum verifikasi' ?></p>
          </div>
          <svg viewBox="0 0 24 24" class="w-5 h-5 <?= $hp == 1 ? 'text-emerald-500' : 'text-red-400' ?> group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="9 18 15 12 9 6"/>
          </svg>
        </a>

        <!-- Email Verification -->
        <a href="<?= $link ?>/email" class="group flex items-center gap-4 p-4 rounded-2xl <?= $email == 1 ? 'bg-gradient-to-r from-emerald-50 to-emerald-50/50 border border-emerald-200' : 'bg-gradient-to-r from-red-50 to-orange-50/50 border border-red-200' ?> transition-all duration-300 hover:scale-[1.01] hover:shadow-md">
          <div class="w-10 h-10 rounded-xl <?= $email == 1 ? 'bg-gradient-to-br from-emerald-400 to-emerald-600 shadow-lg shadow-emerald-500/30' : 'bg-gradient-to-br from-red-400 to-orange-500 shadow-lg shadow-red-500/30' ?> flex items-center justify-center shrink-0">
            <?php if ($email == 1): ?>
            <svg viewBox="0 0 24 24" class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
            <?php else: ?>
            <svg viewBox="0 0 24 24" class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
              <polyline points="22,6 12,13 2,6"/>
            </svg>
            <?php endif; ?>
          </div>
          <div class="flex-1">
            <h4 class="text-[14px] font-semibold <?= $email == 1 ? 'text-emerald-800' : 'text-red-800' ?>">Verifikasi Email</h4>
            <p class="text-[12px] <?= $email == 1 ? 'text-emerald-600' : 'text-red-600' ?> mt-0.5"><?= $email == 1 ? 'Terverifikasi' : 'Belum verifikasi' ?></p>
          </div>
          <svg viewBox="0 0 24 24" class="w-5 h-5 <?= $email == 1 ? 'text-emerald-500' : 'text-red-400' ?> group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="9 18 15 12 9 6"/>
          </svg>
        </a>

        <!-- KTP Verification -->
        <a href="<?= $link ?>/ktp" class="group flex items-center gap-4 p-4 rounded-2xl <?= $ktp == 1 ? 'bg-gradient-to-r from-emerald-50 to-emerald-50/50 border border-emerald-200' : 'bg-gradient-to-r from-red-50 to-orange-50/50 border border-red-200' ?> transition-all duration-300 hover:scale-[1.01] hover:shadow-md">
          <div class="w-10 h-10 rounded-xl <?= $ktp == 1 ? 'bg-gradient-to-br from-emerald-400 to-emerald-600 shadow-lg shadow-emerald-500/30' : 'bg-gradient-to-br from-red-400 to-orange-500 shadow-lg shadow-red-500/30' ?> flex items-center justify-center shrink-0">
            <?php if ($ktp == 1): ?>
            <svg viewBox="0 0 24 24" class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
            <?php else: ?>
            <svg viewBox="0 0 24 24" class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="2" y="5" width="20" height="14" rx="2"/>
              <line x1="2" y1="10" x2="22" y2="10"/>
            </svg>
            <?php endif; ?>
          </div>
          <div class="flex-1">
            <h4 class="text-[14px] font-semibold <?= $ktp == 1 ? 'text-emerald-800' : 'text-red-800' ?>">Verifikasi KTP</h4>
            <p class="text-[12px] <?= $ktp == 1 ? 'text-emerald-600' : 'text-red-600' ?> mt-0.5"><?= $ktp == 1 ? 'Terverifikasi' : 'Belum verifikasi' ?></p>
          </div>
          <svg viewBox="0 0 24 24" class="w-5 h-5 <?= $ktp == 1 ? 'text-emerald-500' : 'text-red-400' ?> group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="9 18 15 12 9 6"/>
          </svg>
        </a>
      </div>
    </div>

    <!-- Warning Alert -->
    <?php if ($unverified_count > 0): ?>
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6">
      <div class="flex items-start gap-3">
        <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
          <svg viewBox="0 0 24 24" class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
          </svg>
        </div>
        <div>
          <p class="text-[14px] text-amber-800 leading-relaxed">
            Silahkan verifikasi akun kamu untuk meningkatkan level dan limit transaksi.
          </p>
          <button onclick="showSyarat()" class="mt-3 inline-flex items-center gap-2 bg-amber-500 text-white text-[13px] font-bold px-4 py-2 rounded-xl hover:bg-amber-600 transition">
            <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14 2 14 8 20 8"/>
              <line x1="16" y1="13" x2="8" y2="13"/>
              <line x1="16" y1="17" x2="8" y2="17"/>
              <polyline points="10 9 9 9 8 9"/>
            </svg>
            Syarat & Ketentuan
          </button>
        </div>
      </div>
    </div>
    <?php endif; ?>

  </main>

  <!-- Toast Container -->
  <div id="toast-container" class="fixed bottom-6 left-4 right-4 z-50 flex flex-col items-center gap-2 pointer-events-none"></div>

  <script>
    // Toast notification
    function showToast(message, type = 'info') {
      const container = document.getElementById('toast-container');
      const toast = document.createElement('div');
      const bgColor = type === 'success' ? 'bg-emerald-500' : type === 'error' ? 'bg-red-500' : 'bg-slate-800';
      toast.className = `${bgColor} text-white px-4 py-3 rounded-xl shadow-lg text-[14px] font-medium pointer-events-auto transform translate-y-20 opacity-0 transition-all duration-300`;
      toast.textContent = message;
      container.appendChild(toast);
      
      requestAnimationFrame(() => {
        toast.classList.remove('translate-y-20', 'opacity-0');
      });
      
      setTimeout(() => {
        toast.classList.add('translate-y-20', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
      }, 3000);
    }

    function showInfo(title, message) {
      Swal.fire({
        title: `<span class="text-[19px] font-bold text-slate-800">${title}</span>`,
        html: `<p class="text-[14px] text-slate-600">${message}</p>`,
        confirmButtonText: 'OK',
        confirmButtonColor: '#1a7fce',
        customClass: {
          popup: 'rounded-2xl',
          confirmButton: 'rounded-xl px-6 py-2.5 text-[14px] font-bold'
        }
      });
    }

    function showSyarat() {
      Swal.fire({
        title: `<span class="text-[19px] font-bold text-slate-800">Syarat & Ketentuan</span>`,
        html: `
          <div class="text-left text-[14px] text-slate-600">
            <p class="mb-3 font-semibold text-slate-800">Syarat verifikasi identitas:</p>
            <ol class="list-decimal ml-4 space-y-2">
              <li>Gunakan kartu identitas milik sendiri</li>
              <li>Masukkan data sesuai dengan data diri sebenarnya</li>
              <li>Foto selfie dengan pakaian yang sopan</li>
              <li>Data yang tidak valid akan menyebabkan pemblokiran akun</li>
            </ol>
            <div class="mt-4 p-3 bg-brand/5 rounded-xl border border-brand/20">
              <p class="font-semibold text-slate-800 mb-1">Benefit Verifikasi:</p>
              <ul class="space-y-1 text-slate-600">
                <li>• Limit saldo lebih tinggi</li>
                <li>• Maksimal topup lebih besar</li>
                <li>• Akumulasi transaksi tak Terbatas</li>
              </ul>
            </div>
          </div>
        `,
        confirmButtonText: 'Saya Pahami',
        confirmButtonColor: '#1a7fce',
        customClass: {
          popup: 'rounded-2xl',
          confirmButton: 'rounded-xl px-6 py-2.5 text-[14px] font-bold'
        }
      });
    }
  </script>

</body>
</html>
