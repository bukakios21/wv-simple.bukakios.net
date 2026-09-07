<?php
require_once("../config.php");
require_once("../_session.php");
require_once("../lib/ApiV2.php");

$api_v2 = new ApiV2($user_jwt);

/************ LOAD PRODUCTS (SSR) ************/
$kategori_id = 144;
$lists_raw   = $api_v2->list_product_pasca($kategori_id);
//var_dump($lists_raw);
$lists       = json_decode($lists_raw, true);
$products    = isset($lists['data']) ? $lists['data'] : [];
/************ END LOAD PRODUCTS ************/
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BukaKios - Tagihan TV Pascabayar</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: '#1a7fce',
            brandDark: '#1265a6',
            mutedText: '#6b7280',
            line: '#d9e1e8',
          },
          boxShadow: {
            card: '0 5px 14px rgba(16, 24, 40, 0.07)',
            soft: '0 4px 12px rgba(15, 23, 42, 0.06)',
          },
          fontFamily: {
            sans: ['Inter', 'ui-sans-serif', 'system-ui', 'Segoe UI', 'Roboto', 'Arial', 'sans-serif'],
          }
        }
      }
    }
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <style>
    * { font-family: 'Inter', sans-serif; }
    .card-product { transition: all 0.15s ease; }
    .card-product:active { transform: scale(0.98); }
    .card-product:hover { background-color: #f8fafc; }
    /* Hide scrollbar but keep scroll functionality */
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .hide-scrollbar::-webkit-scrollbar { display: none; }
  </style>
</head>
<body class="min-h-screen bg-white font-sans text-slate-950">

  <!-- Toast error -->
  <div id="toastError" class="fixed top-4 left-1/2 z-[9998] hidden -translate-x-1/2 max-w-[90vw] w-full px-4">
    <div class="flex items-start gap-3 rounded-xl bg-red-600 px-4 py-3 shadow-lg text-white">
      <svg viewBox="0 0 24 24" class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
      <p id="toastErrorMsg" class="text-[13px] font-semibold leading-5"></p>
    </div>
  </div>

  <main class="relative w-full min-h-screen bg-slate-50">

    <!-- Header -->
    <header class="relative z-10 px-5 pt-4 pb-3 bg-white border-b border-slate-100">
      <div class="flex items-center gap-3">
        <button id="backBtn" aria-label="Kembali" onclick="history.back()" class="grid h-9 w-9 place-items-center rounded-full border border-slate-200 bg-white text-slate-700 transition hover:bg-slate-50 active:bg-slate-100">
          <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 18l-6-6 6-6" />
          </svg>
        </button>
        <div class="h-1.5 flex-1 rounded-full bg-slate-200 overflow-hidden">
          <div class="h-full w-full rounded-full bg-brand"></div>
        </div>
      </div>
    </header>

    <!-- Title Section -->
    <section class="px-6 pt-5 pb-4 bg-white">
      <div class="flex items-center gap-3">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl shadow-card bg-brand/8">
          <!-- TV icon -->
          <svg class="h-6 w-6 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="7" width="20" height="15" rx="2" ry="2"/>
            <polyline points="17 2 12 7 7 2"/>
          </svg>
        </div>
        <div>
          <h1 class="text-[17px] font-bold text-slate-900 leading-tight">Tagihan TV Pascabayar</h1>
          <p class="text-[12px] text-mutedText mt-0.5">Pilih produk untuk melihat tagihan</p>
        </div>
      </div>
    </section>

    <!-- Product List -->
    <section class="px-4 pb-8">
      <?php if (count($products) > 0): ?>
        <!-- Product count pill -->
        <div class="flex items-center gap-2 mb-3 px-1">
          <span class="text-[11px] font-semibold text-mutedText uppercase tracking-wide">Daftar Produk</span>
          <span class="inline-flex h-5 min-w-[20px] items-center justify-center rounded-full bg-brand/10 px-1.5 text-[11px] font-bold text-brand">
            <?= count($products) ?>
          </span>
        </div>

        <!-- Product cards -->
        <div class="flex flex-col gap-2">
          <?php foreach ($products as $list): ?>
            <a href="pay.php?code=<?= urlencode($list['code']) ?>" class="card-product group flex items-center gap-4 rounded-[16px] border border-slate-200 bg-white px-4 py-3 shadow-soft text-decoration-none">
              <!-- Product image -->
              <div class="h-11 w-11 flex items-center justify-center rounded-xl bg-slate-100 overflow-hidden shrink-0">
                <img
                  src="<?= htmlspecialchars($list['product_img']) ?>"
                  alt="<?= htmlspecialchars($list['product_name']) ?>"
                  class="h-full w-full object-contain"
                  loading="lazy"
                  onerror="this.style.display='none';this.parentElement.innerHTML='<svg class=\'h-6 w-6 text-slate-400\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.8\'><rect x=\'2\' y=\'7\' width=\'20\' height=\'15\' rx=\'2\'/><polyline points=\'17 2 12 7 7 2\'/></svg>'"
                />
              </div>

              <!-- Product name -->
              <div class="flex-1 min-w-0">
                <p class="text-[14px] font-semibold text-slate-800 truncate leading-tight">
                  <?= htmlspecialchars($list['product_name']) ?>
                </p>
                <p class="text-[11px] text-mutedText mt-0.5 truncate">
                  <?= htmlspecialchars($list['code']) ?>
                </p>
              </div>

              <!-- Arrow icon -->
              <div class="shrink-0 text-slate-300 group-hover:text-brand transition-colors">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M9 18l6-6-6-6" />
                </svg>
              </div>
            </a>
          <?php endforeach; ?>
        </div>

      <?php else: ?>
        <!-- Empty / error state -->
        <div class="mt-10 flex flex-col items-center gap-3 text-center px-4">
          <div class="flex h-16 w-16 items-center justify-center rounded-full bg-red-50">
            <svg class="h-8 w-8 text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/>
              <path d="M12 8v4M12 16h.01"/>
            </svg>
          </div>
          <div>
            <p class="text-[14px] font-semibold text-slate-700">Gagal memuat produk</p>
            <p class="text-[12px] text-mutedText mt-1">Silakan coba lagi dalam beberapa saat.</p>
          </div>
          <button onclick="location.reload()" class="mt-1 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-[13px] font-semibold text-slate-700 shadow-soft transition hover:bg-slate-50 active:bg-slate-100">
            Coba Lagi
          </button>
        </div>
      <?php endif; ?>
    </section>

  </main>

  <!-- Back button: direct location for webview -->
  <script>
    document.getElementById('backBtn').addEventListener('click', function() {
      if (window.android && typeof window.android.back === 'function') {
        window.android.back();
      } else {
        history.back();
      }
    });

    function showToastError(msg) {
      const el = document.getElementById('toastError');
      document.getElementById('toastErrorMsg').textContent = msg;
      el.classList.remove('hidden');
      el.classList.add('flex');
      setTimeout(function() {
        el.classList.add('hidden');
        el.classList.remove('flex');
      }, 4000);
    }
  </script>
</body>
</html>
