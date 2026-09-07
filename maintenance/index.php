<?php
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/../_session.php");
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Maintenance - BukaKios</title>
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
          <path d="M15 18l-6-6 6-6"/>
        </svg>
      </button>
      <div class="h-1 flex-1 rounded-full bg-slate-200 overflow-hidden">
        <div class="h-full w-full rounded-full bg-brand"></div>
      </div>
      <div class="shrink-0 text-[18px] font-extrabold tracking-[-0.04em] text-brand">BukaKios</div>
    </div>
  </header>

  <main class="flex flex-col items-center justify-center min-h-[calc(100vh-56px)] px-4 py-8">

    <!-- Maintenance Icon -->
    <div class="w-20 h-20 rounded-full bg-amber-100 flex items-center justify-center mb-6 shadow-soft">
      <svg viewBox="0 0 24 24" class="w-10 h-10 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
        <line x1="12" y1="9" x2="12" y2="13"/>
        <line x1="12" y1="17" x2="12.01" y2="17"/>
      </svg>
    </div>

    <h1 class="text-[24px] font-extrabold tracking-tight text-slate-900 text-center">Maintenance</h1>
    <p class="text-sm text-slate-500 mt-2 text-center max-w-xs leading-relaxed">
      Fitur ini sedang dalam perbaikan. Mohon tunggu beberapa saat lagi.
    </p>

    <!-- Info Card -->
    <div class="w-full max-w-sm bg-white rounded-2xl border border-slate-100 p-5 shadow-card mt-6">
      <h2 class="text-[13px] font-extrabold text-slate-800 mb-4 flex items-center gap-2">
        <svg viewBox="0 0 24 24" class="w-4 h-4 text-brand shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"/>
          <polyline points="12 6 12 12 16 14"/>
        </svg>
        Informasi:
      </h2>
      <div class="space-y-3">
        <div class="flex items-start gap-3">
          <div class="w-6 h-6 rounded-full bg-brand/10 flex items-center justify-center shrink-0 mt-0.5">
            <span class="text-[11px] font-bold text-brand">1</span>
          </div>
          <div class="text-[13px] text-slate-600 leading-relaxed">Tim kami sedang melakukan pemeliharaan sistem</div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-6 h-6 rounded-full bg-brand/10 flex items-center justify-center shrink-0 mt-0.5">
            <span class="text-[11px] font-bold text-brand">3</span>
          </div>
          <div class="text-[13px] text-slate-600 leading-relaxed">Mohon kembali nanti untuk melanjutkan</div>
        </div>
      </div>
    </div>

    <!-- Tips -->
    <div class="w-full max-w-sm bg-amber-50 rounded-xl border border-amber-100 p-4 mt-4">
      <div class="flex items-start gap-3">
        <div class="w-6 h-6 rounded-full bg-amber-100 flex items-center justify-center shrink-0 mt-0.5">
          <svg viewBox="0 0 24 24" class="w-3 h-3 text-amber-500" fill="currentColor">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
          </svg>
        </div>
        <div class="text-[12px] text-amber-700 leading-relaxed">Jika masalah tetap berlanjut, silakan hubungi kami melalui menu <strong class="font-semibold">Bantuan</strong> di aplikasi.</div>
      </div>
    </div>

    <div class="flex gap-3 mt-6 w-full max-w-sm">
      <button onclick="history.back()" class="flex-1 rounded-xl border border-slate-200 bg-white py-3 text-[14px] font-bold text-slate-700 shadow-soft transition hover:bg-slate-50 active:scale-[0.98]">
        Kembali
      </button>
      <a href="index.php" class="flex-1 rounded-xl bg-brand py-3 text-[14px] font-bold text-white text-center shadow-lg transition hover:bg-brandDark active:scale-[0.98]">
        Coba Lagi
      </a>
    </div>

  </main>
</body>
</html>
