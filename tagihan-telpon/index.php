<?php
require_once("../config.php");
require_once("../_session.php");
require_once("../lib/ApiV2.php");

$api_v2 = new ApiV2($user_jwt);

/************ LOAD PRODUCTS (SSR) ************/
// operator_id untuk produk Telepon Pascabayar
$operator_id = 143;
$lists_raw   = $api_v2->list_product_pasca($operator_id);
$lists       = json_decode($lists_raw, true);
$products    = isset($lists['data']) ? $lists['data'] : [];

/************ END LOAD PRODUCTS ************/
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BukaKios - Tagihan Telepon Pascabayar</title>
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

  <main class="relative w-full min-h-screen bg-slate-50 pb-24">

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
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl shadow-card bg-brand/8 overflow-hidden">
          <!-- Phone icon (default fallback) -->
          <svg class="h-6 w-6 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
          </svg>
        </div>
        <div>
          <h1 class="text-[17px] font-bold text-slate-900 leading-tight">Tagihan Telepon Pascabayar</h1>
          <p class="text-[12px] text-mutedText mt-0.5">Pilih operator untuk membayar tagihan telepon</p>
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
          id="searchTelepon"
          type="text"
          inputmode="search"
          autocomplete="off"
          onkeyup="filterTelepon(this.value)"
          placeholder="Cari operator telepon..."
          class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-[14px] text-slate-800 placeholder-slate-400 outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 transition shadow-soft"
        />
      </div>
    </section>

    <!-- Product List -->
    <section class="px-4 pb-8">
      <?php if (count($products) > 0): ?>
        <!-- Product count pill -->
        <div class="flex items-center gap-2 mb-3 px-1">
          <span class="text-[11px] font-semibold text-mutedText uppercase tracking-wide">Daftar Operator</span>
          <span class="inline-flex h-5 min-w-[20px] items-center justify-center rounded-full bg-brand/10 px-1.5 text-[11px] font-bold text-brand">
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
            <p class="text-[14px] font-semibold text-slate-700">Operator tidak ditemukan</p>
            <p class="text-[12px] text-mutedText mt-1">Coba kata kunci lain, misalnya nama operator.</p>
          </div>
        </div>

        <!-- Product cards -->
        <div id="teleponList" class="flex flex-col gap-2">
          <?php foreach ($products as $list): ?>
            <a
              href="pay2.php?code=<?= urlencode($list['code']) ?>&id=<?= urlencode($list['id'] ?? $list['code']) ?>"
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
                  onerror="this.style.display='none';this.parentElement.innerHTML='<svg class=\'h-6 w-6 text-brand\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.8\'><path d=\'M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z\'/></svg>'"
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
        <!-- Empty / error state (gagal load dari API) -->
        <div class="mt-10 flex flex-col items-center gap-3 text-center px-4">
          <div class="flex h-16 w-16 items-center justify-center rounded-full bg-red-50">
            <svg class="h-8 w-8 text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/>
              <path d="M12 8v4M12 16h.01"/>
            </svg>
          </div>
          <div>
            <p class="text-[14px] font-semibold text-slate-700">Gagal memuat daftar operator</p>
            <p class="text-[12px] text-mutedText mt-1">Silakan coba lagi dalam beberapa saat.</p>
          </div>
          <button onclick="location.reload()" class="mt-1 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-[13px] font-semibold text-slate-700 shadow-soft transition hover:bg-slate-50 active:bg-slate-100">
            Coba Lagi
          </button>
        </div>
      <?php endif; ?>
    </section>

  </main>

  <script>
    document.getElementById('backBtn').addEventListener('click', function() {
      if (window.android && typeof window.android.back === 'function') {
        window.android.back();
      } else {
        history.back();
      }
    });

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

    // Live filter untuk daftar operator telepon
    function filterTelepon(q) {
      var list   = document.getElementById('teleponList');
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
