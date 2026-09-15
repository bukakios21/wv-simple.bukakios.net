<?php
require_once("../config.php");
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Giveaway BukaKios</title>
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
  <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-950 antialiased pb-8">

  <!-- Header -->
  <header class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-slate-100">
    <div class="flex items-center gap-3 px-4 py-3">
      <div class="h-1 flex-1 rounded-full bg-slate-100 overflow-hidden">
        <div class="h-full w-full rounded-full bg-brand"></div>
      </div>
      <div class="shrink-0 text-[16px] font-bold tracking-[-0.04em] text-brand">BukaKios</div>
    </div>
  </header>

  <main class="px-4 pt-6">

    <!-- Hero Section -->
    <div class="flex flex-col items-center text-center mb-6">
      <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center mb-4 shadow-soft">
        <svg viewBox="0 0 24 24" class="w-12 h-12 text-white" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
        </svg>
      </div>
      <h1 class="text-[22px] font-extrabold tracking-tight text-slate-900">Giveaway BukaKios</h1>
      <p class="text-sm text-slate-500 mt-1">Bagikan & menangkan saldo Rp100.000!</p>
    </div>

    <!-- Info Card -->
    <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-2xl p-4 mb-6">
      <div class="flex items-start gap-3">
        <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
          <svg viewBox="0 0 24 24" class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
          </svg>
        </div>
        <div>
          <p class="text-[13px] text-amber-800 leading-relaxed">
            Setelah sekian lama BukaKios hadir menemani kamu dalam transaksi digital, kini waktunya untuk bagi-bagi saldo sebanyak <strong class="font-bold">Rp100.000</strong> untuk <strong class="font-bold">2 orang pemenang</strong>! Silahkan ikuti di akun sosial media kami ya.
          </p>
        </div>
      </div>
    </div>

    <!-- Prize Info Card -->
    <div class="bg-white rounded-2xl border border-slate-100 p-4 mb-6 shadow-card">
      <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
          <span class="text-2xl">🎁</span>
        </div>
        <div class="flex-1">
          <h3 class="text-[15px] font-bold text-slate-800">Total Hadiah</h3>
          <p class="text-[20px] font-extrabold text-amber-500">Rp100.000</p>
        </div>
        <div class="w-px h-12 bg-slate-200"></div>
        <div class="text-center">
          <p class="text-[20px] font-extrabold text-brand">2</p>
          <p class="text-[11px] text-slate-500">Pemenang</p>
        </div>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="space-y-3 mb-6">
      
      <!-- Facebook -->
      <a href="https://www.facebook.com/bukakios.net/photos/a.2276572252456596/2661379127309238/" class="flex items-center gap-4 w-full bg-white rounded-2xl border border-slate-100 p-4 shadow-card transition hover:shadow-md active:scale-[0.99]">
        <div class="w-12 h-12 rounded-xl bg-blue-600 flex items-center justify-center shrink-0">
          <svg viewBox="0 0 24 24" class="w-6 h-6 text-white" fill="currentColor">
            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
          </svg>
        </div>
        <div class="flex-1 text-left">
          <h3 class="text-[15px] font-bold text-slate-800">Facebook</h3>
          <p class="text-[12px] text-slate-500">Ikuti Giveaway di Facebook</p>
        </div>
        <svg viewBox="0 0 24 24" class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="9 18 15 12 9 6"/>
        </svg>
      </a>

      <!-- Instagram -->
      <a href="https://www.instagram.com/p/B7sZNVbpZyZ/" target="_blank" class="flex items-center gap-4 w-full bg-white rounded-2xl border border-slate-100 p-4 shadow-card transition hover:shadow-md active:scale-[0.99]">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-600 via-pink-500 to-orange-400 flex items-center justify-center shrink-0">
          <svg viewBox="0 0 24 24" class="w-6 h-6 text-white" fill="currentColor">
            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
          </svg>
        </div>
        <div class="flex-1 text-left">
          <h3 class="text-[15px] font-bold text-slate-800">Instagram</h3>
          <p class="text-[12px] text-slate-500">Ikuti Giveaway di Instagram</p>
        </div>
        <svg viewBox="0 0 24 24" class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="9 18 15 12 9 6"/>
        </svg>
      </a>

    </div>

    <!-- Period Card -->
    <div class="bg-white rounded-2xl border border-slate-100 p-4 mb-6 shadow-card">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center shrink-0">
          <svg viewBox="0 0 24 24" class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
          </svg>
        </div>
        <div>
          <p class="text-[12px] text-slate-500">Periode Giveaway</p>
          <p class="text-[14px] font-semibold text-slate-700">24 - 27 January 2020</p>
        </div>
      </div>
    </div>

    <!-- Info Pills -->
    <div class="flex flex-wrap gap-2">
      <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-[11px] font-medium text-slate-600 shadow-soft">
        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
        Berhadiah
      </span>
      <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-[11px] font-medium text-slate-600 shadow-soft">
        <span class="w-1.5 h-1.5 rounded-full bg-brand"></span>
        2 Pemenang
      </span>
      <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-[11px] font-medium text-slate-600 shadow-soft">
        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
        Gratis Ikut
      </span>
    </div>

  </main>

</body>
</html>
