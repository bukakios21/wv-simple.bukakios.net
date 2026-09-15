<?php
require_once("../config.php");
require_once("../_session.php");
require_once("../lib/ApiV2.php");

$api_v2 = new ApiV2($user_jwt);

if ($user_id == 55721 || $user_id == 278542) {
    header("Location: https://wv3.bukakios.id/angsuran-kredit");
    exit();
}

/************ LOAD PRODUCTS (SSR) ************/
// pembelianoperator_id untuk produk Multifinance (sesuai referensi legacy id_operator=142)
$operator_id = 142;
$lists_raw   = $api_v2->list_product_pasca($operator_id);
$lists       = json_decode($lists_raw, true);
$products    = isset($lists['data']) ? $lists['data'] : [];

/************ END LOAD PRODUCTS ************/

/**
 * Client-side search filter: hide card yang tidak match nama/code.
 * Dipanggil dari onkeyup di search bar dan saat load pertama (render semua).
 */
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BukaKios - Angsuran Multifinance</title>
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
      <p id="toastErrorMsg" class="text-[14px] font-semibold leading-5"></p>
    </div>
  </div>

  <main class="relative w-full min-h-screen bg-slate-50 pb-24">

    <!-- Header -->
    <header class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-slate-100">
      <div class="flex items-center gap-3 px-4 py-3">
        <div class="h-1 flex-1 rounded-full bg-slate-100 overflow-hidden">
          <div class="h-full w-full rounded-full bg-brand"></div>
        </div>
        <div class="shrink-0 text-[16px] font-bold tracking-[-0.04em] text-brand">BukaKios</div>
      </div>
    </header>

    <!-- Title Section -->
    <section class="px-6 pt-5 pb-4 bg-white">
      <div class="flex items-center gap-3">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl shadow-card bg-brand/8 overflow-hidden">
          <!-- Invoice/document icon (default fallback) -->
          <svg class="h-6 w-6 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="9" y1="13" x2="15" y2="13"/>
            <line x1="9" y1="17" x2="13" y2="17"/>
          </svg>
        </div>
        <div>
          <h1 class="text-[18px] font-bold text-slate-900 leading-tight">Angsuran Multifinance</h1>
          <p class="text-[13px] text-mutedText mt-0.5">Pilih finance untuk membayar angsuran</p>
        </div>
      </div>
    </section>

    <!-- Search bar -->
    <section class="px-4 pt-4 pb-3">
      <div class="relative">
        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
          <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="7"/>
            <path d="M21 21l-4.3-4.3"/>
          </svg>
        </span>
        <input
          id="searchMultifinance"
          type="text"
          inputmode="search"
          autocomplete="off"
          onkeyup="filterMultifinance(this.value)"
          placeholder="Cari finance / perusahaan..."
          class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-[15px] text-slate-800 placeholder-slate-400 outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 transition shadow-soft"
        />
      </div>
    </section>

    <!-- Product List -->
    <section class="px-4 pb-8">
      <?php if (count($products) > 0): ?>
        <!-- Product count pill -->
        <div class="flex items-center gap-2 mb-3 px-1">
          <span class="text-[12px] font-semibold text-mutedText uppercase tracking-wide">Daftar Multifinance</span>
          <span class="inline-flex h-5 min-w-[20px] items-center justify-center rounded-full bg-brand/10 px-1.5 text-[12px] font-bold text-brand">
            <?= count($products) ?>
          </span>
        </div>

        <!-- Empty search state (hidden by default, shown when filter result = 0) -->
        <div id="emptySearch" class="hidden mt-6 flex flex-col items-center gap-3 text-center px-4">
          <div class="flex h-14 w-14 items-center justify-center rounded-full bg-slate-100">
            <svg class="h-7 w-7 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="7"/>
              <path d="M21 21l-4.3-4.3"/>
            </svg>
          </div>
          <div>
            <p class="text-[15px] font-semibold text-slate-700">Finance tidak ditemukan</p>
            <p class="text-[13px] text-mutedText mt-1">Coba kata kunci lain, misalnya nama finance.</p>
          </div>
        </div>

        <!-- Product cards -->
        <div id="multifinanceList" class="flex flex-col gap-2">
          <?php foreach ($products as $list): ?>
            <a
              href="pay2.php?code=<?= urlencode($list['code']) ?>"
              data-name="<?= htmlspecialchars(strtolower($list['product_name'])) ?>"
              data-code="<?= htmlspecialchars(strtolower($list['code'])) ?>"
              class="card-product group flex items-center gap-4 rounded-[16px] border border-slate-200 bg-white px-4 py-3 shadow-soft text-decoration-none"
            >
              <!-- Product image -->
              <div class="h-11 w-11 flex items-center justify-center rounded-xl bg-slate-100 overflow-hidden shrink-0">
                <img
                  src="<?= htmlspecialchars($list['product_img']) ?>"
                  alt="<?= htmlspecialchars($list['product_name']) ?>"
                  class="h-full w-full object-contain"
                  loading="lazy"
                  onerror="this.style.display='none';this.parentElement.innerHTML='<svg class=\'h-6 w-6 text-brand\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.8\'><path d=\'M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z\'/><polyline points=\'14 2 14 8 20 8\'/><line x1=\'9\' y1=\'13\' x2=\'15\' y2=\'13\'/><line x1=\'9\' y1=\'17\' x2=\'13\' y2=\'17\'/></svg>'"
                />
              </div>

              <!-- Product name -->
              <div class="flex-1 min-w-0">
                <p class="text-[15px] font-semibold text-slate-800 truncate leading-tight">
                  <?= htmlspecialchars($list['product_name']) ?>
                </p>
                <p class="text-[12px] text-mutedText mt-0.5 truncate">
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
        <!-- Empty / error state (gagal load dari API) -->
        <div class="mt-10 flex flex-col items-center gap-3 text-center px-4">
          <div class="flex h-16 w-16 items-center justify-center rounded-full bg-red-50">
            <svg class="h-8 w-8 text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/>
              <path d="M12 8v4M12 16h.01"/>
            </svg>
          </div>
          <div>
            <p class="text-[15px] font-semibold text-slate-700">Gagal memuat daftar Multifinance</p>
            <p class="text-[13px] text-mutedText mt-1">Silakan coba lagi dalam beberapa saat.</p>
          </div>
          <button onclick="location.reload()" class="mt-1 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-[14px] font-semibold text-slate-700 shadow-soft transition hover:bg-slate-50 active:bg-slate-100">
            Coba Lagi
          </button>
        </div>
      <?php endif; ?>
    </section>

  </main>

  <script>

    function showToastError(msg) {
      var el = document.getElementById('toastError');
      document.getElementById('toastErrorMsg').textContent = msg;
      el.classList.remove('hidden');
      el.classList.add('flex');
      setTimeout(function() {
        el.classList.add('hidden');
        el.classList.remove('flex');
      }, 4000);
    }

    // Live filter untuk daftar Multifinance
    function filterMultifinance(q) {
      var list   = document.getElementById('multifinanceList');
      var empty  = document.getElementById('emptySearch');
      if (!list) return;
      var query = (q || '').trim().toLowerCase();
      var cards = list.querySelectorAll('a.card-product');
      var visibleCount = 0;

      cards.forEach(function(card) {
        var name = card.getAttribute('data-name') || '';
        var code = card.getAttribute('data-code') || '';
        var match = !query || name.indexOf(query) !== -1 || code.indexOf(query) !== -1;
        card.style.display = match ? '' : 'none';
        if (match) visibleCount++;
      });

      // Toggle empty-search state
      if (empty) {
        if (visibleCount === 0 && query) {
          empty.classList.remove('hidden');
          empty.classList.add('flex');
        } else {
          empty.classList.add('hidden');
          empty.classList.remove('flex');
        }
      }
    }
  </script>
</body>
</html>
