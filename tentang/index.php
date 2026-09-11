<?php
require_once("../config.php");
require_once("../_session.php");
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tentang - BukaKios</title>
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
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-950 antialiased">

  <!-- Header -->
  <header class="sticky top-0 z-10 bg-white border-b border-slate-100">
    <div class="flex items-center gap-3 px-4 py-3">
      <button onclick="history.back()" aria-label="Kembali" class="grid h-9 w-9 shrink-0 place-items-center rounded-full border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-50 hover:border-slate-300 active:scale-95">
        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M15 18l-6-6 6-6" />
        </svg>
      </button>
      <div class="h-1 flex-1 rounded-full bg-slate-200 overflow-hidden">
        <div class="h-full w-full rounded-full bg-brand"></div>
      </div>
      <div class="shrink-0 text-[19px] font-extrabold tracking-[-0.04em] text-brand">BukaKios</div>
    </div>
  </header>

  <main class="px-4 pt-6 pb-10">

    <!-- Logo & Title -->
    <div class="flex flex-col items-center text-center mb-8">
      <img src="../assets/img/info/logo.png" alt="BukaKios" class="w-28 h-28 object-contain mb-4 drop-shadow-md" onerror="this.style.display='none'">
      <h1 class="text-[23px] font-extrabold tracking-tight text-slate-900">Tentang BukaKios</h1>
      <p class="text-sm text-slate-500 mt-1">Versi <?= htmlspecialchars($bukakios_version ?? '1.0') ?></p>
    </div>

    <!-- About Card -->
    <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-card mb-4">
      <h2 class="text-[16px] font-extrabold text-slate-800 mb-3 flex items-center gap-2">
        <svg viewBox="0 0 24 24" class="w-4 h-4 text-brand shrink-0" fill="currentColor">
          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
        </svg>
        Apa itu BukaKios?
      </h2>
      <p class="text-[14px] text-slate-600 leading-relaxed">
        BukaKios merupakan aplikasi dan layanan web yang bergerak di bidang transaksi digital atau yang lebih dikenal dengan <strong class="text-slate-800 font-semibold">PPOB (Payment Point Online Bank)</strong>. Produk yang dapat dibeli di layanan ini antara lain pulsa, paket data, paket telepon, token listrik, top up saldo E-Money, voucher game, pembayaran tagihan BPJS, Indihome, listrik pascabayar, dan masih banyak lagi.
      </p>
      <p class="text-[14px] text-slate-600 leading-relaxed mt-3">
        Aplikasi dan fitur yang diberikan dijamin <strong class="text-brand font-semibold">100% GRATIS</strong> dan harga produk yang disediakan <strong class="text-brand font-semibold">SANGAT MURAH</strong>, sehingga sangat memungkinkan untuk menjadikan BukaKios sebagai distributor bisnis PPOB kamu.
      </p>
    </div>

    <!-- Fitur Unggulan -->
    <div class="mb-4">
      <h2 class="text-[16px] font-extrabold text-slate-800 mb-3">Fitur Unggulan</h2>
      <div class="grid grid-cols-2 gap-3">

        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-soft">
          <div class="w-9 h-9 rounded-lg bg-brand/10 flex items-center justify-center mb-2">
            <svg viewBox="0 0 24 24" class="w-4.5 h-4.5 text-brand" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
          </div>
          <div class="text-[14px] font-bold text-slate-800">Reset PIN</div>
          <div class="text-[12px] text-slate-500 mt-0.5">Atur ulang PIN dengan mudah</div>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-soft">
          <div class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center mb-2">
            <svg viewBox="0 0 24 24" class="w-4.5 h-4.5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 5H3a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h18a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2z"/>
              <path d="M1 10h22"/>
            </svg>
          </div>
          <div class="text-[14px] font-bold text-slate-800">Kode Voucher</div>
          <div class="text-[12px] text-slate-500 mt-0.5"> voucher &amp; kode promo</div>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-soft">
          <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center mb-2">
            <svg viewBox="0 0 24 24" class="w-4.5 h-4.5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
              <circle cx="9" cy="7" r="4"/>
              <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
              <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
          </div>
          <div class="text-[14px] font-bold text-slate-800">Downline</div>
          <div class="text-[12px] text-slate-500 mt-0.5">Kelola jaringan bisnis</div>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-soft">
          <div class="w-9 h-9 rounded-lg bg-purple-50 flex items-center justify-center mb-2">
            <svg viewBox="0 0 24 24" class="w-4.5 h-4.5 text-purple-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/>
              <path d="M12 6v6l4 2"/>
            </svg>
          </div>
          <div class="text-[14px] font-bold text-slate-800">Tukar Poin</div>
          <div class="text-[12px] text-slate-500 mt-0.5">Tukarkan poin jadi reward</div>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-soft">
          <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center mb-2">
            <svg viewBox="0 0 24 24" class="w-4.5 h-4.5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
          </div>
          <div class="text-[14px] font-bold text-slate-800">PDAM</div>
          <div class="text-[12px] text-slate-500 mt-0.5">Pembayaran tagihan air</div>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-soft">
          <div class="w-9 h-9 rounded-lg bg-rose-50 flex items-center justify-center mb-2">
            <svg viewBox="0 0 24 24" class="w-4.5 h-4.5 text-rose-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
            </svg>
          </div>
          <div class="text-[14px] font-bold text-slate-800">Token Listrik</div>
          <div class="text-[12px] text-slate-500 mt-0.5">Token PLN cepat &amp; mudah</div>
        </div>

      </div>
    </div>

    <!-- Produk & Layanan -->
    <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-card mb-4">
      <h2 class="text-[16px] font-extrabold text-slate-800 mb-4 flex items-center gap-2">
        <svg viewBox="0 0 24 24" class="w-4 h-4 text-brand shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
          <line x1="3" y1="6" x2="21" y2="6"/>
          <path d="M16 10a4 4 0 0 1-8 0"/>
        </svg>
        Produk &amp; Layanan
      </h2>
      <div class="flex flex-wrap gap-2">
        <?php
        $produk = ['Pulsa', 'Paket Data', 'Paket Telepon', 'Token Listrik', 'E-Money', 'Voucher Game', 'BPJS', 'Indihome', 'PLN Pascabayar', 'PDAM', 'PGN', 'TV Kabel'];
        foreach ($produk as $item): ?>
        <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-[12px] font-medium text-slate-600">
          <span class="w-1.5 h-1.5 rounded-full bg-brand"></span>
          <?= htmlspecialchars($item) ?>
        </span>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Keunggulan -->
    <div class="bg-gradient-to-br from-brand to-brandDark rounded-2xl p-5 shadow-card mb-4">
      <h2 class="text-[16px] font-extrabold text-white mb-4 flex items-center gap-2">
        <svg viewBox="0 0 24 24" class="w-4 h-4 shrink-0" fill="currentColor">
          <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
        </svg>
        Keunggulan BukaKios
      </h2>
      <div class="space-y-3">
        <div class="flex items-start gap-3">
          <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center shrink-0 mt-0.5">
            <svg viewBox="0 0 24 24" class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
          </div>
          <div>
            <div class="text-[14px] font-bold text-white">100% Gratis</div>
            <div class="text-[12px] text-white/70 mt-0.5">Tidak dipungut biaya pendaftaran maupun langganan</div>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center shrink-0 mt-0.5">
            <svg viewBox="0 0 24 24" class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
          </div>
          <div>
            <div class="text-[14px] font-bold text-white">Harga Paling Murah</div>
            <div class="text-[12px] text-white/70 mt-0.5">Dapatkan harga terbaik untuk setiap produk digital</div>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center shrink-0 mt-0.5">
            <svg viewBox="0 0 24 24" class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
          </div>
          <div>
            <div class="text-[14px] font-bold text-white">Transaksi Cepat</div>
            <div class="text-[12px] text-white/70 mt-0.5">Proses otomatis 24 jam dengan hasil instan</div>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center shrink-0 mt-0.5">
            <svg viewBox="0 0 24 24" class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
          </div>
          <div>
            <div class="text-[14px] font-bold text-white">Bisnis PPOB</div>
            <div class="text-[12px] text-white/70 mt-0.5">Cocok untuk memulai usaha distributor digital</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer info -->
    <div class="text-center mt-6">
      <div class="text-[12px] text-slate-400">© <?= date('Y') ?> BukaKios. Seluruh hak dilindungi.</div>
      <div class="text-[12px] text-slate-300 mt-1">www.bukakios.net</div>
    </div>

  </main>
</body>
</html>
