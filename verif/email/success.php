<?php
require_once(__DIR__ . "/../../config.php");
require_once(__DIR__ . "/../../_session.php");

$msg = $_SESSION['msg'] ?? 'Link verifikasi berhasil dikirim ke email kamu.';
$email = $_SESSION['verif_email_addr'] ?? '';

// Clear session msg after read
unset($_SESSION['msg'], $_SESSION['verif_email_addr']);
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Email Terkirim - BukaKios</title>
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
<body class="min-h-screen bg-slate-50 font-sans text-slate-950 antialiased">

  <!-- Header -->
  <header class="sticky top-0 z-10 bg-white border-b border-slate-100">
    <div class="flex items-center gap-3 px-4 py-3">
      <div class="h-1 flex-1 rounded-full bg-slate-200 overflow-hidden">
        <div class="h-full w-full rounded-full bg-brand"></div>
      </div>
      <div class="shrink-0 text-[18px] font-extrabold tracking-[-0.04em] text-brand">BukaKios</div>
    </div>
  </header>

  <main class="flex flex-col items-center justify-center min-h-[calc(100vh-56px)] px-4 py-8">

    <!-- Success Icon -->
    <div class="w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center mb-6 shadow-soft">
      <svg viewBox="0 0 24 24" class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="20 6 9 17 4 12"/>
      </svg>
    </div>

    <h1 class="text-[24px] font-extrabold tracking-tight text-slate-900 text-center">Email Terkirim!</h1>
    <p class="text-sm text-slate-500 mt-2 text-center max-w-xs leading-relaxed">
      <?= htmlspecialchars($msg) ?>
    </p>

    <!-- Steps Card -->
    <div class="w-full max-w-sm bg-white rounded-2xl border border-slate-100 p-5 shadow-card mt-6">
      <h2 class="text-[13px] font-extrabold text-slate-800 mb-4 flex items-center gap-2">
        <svg viewBox="0 0 24 24" class="w-4 h-4 text-brand shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
          <polyline points="22,6 12,13 2,6"/>
        </svg>
        Langkah selanjutnya:
      </h2>
      <div class="space-y-3">
        <div class="flex items-start gap-3">
          <div class="w-6 h-6 rounded-full bg-brand/10 flex items-center justify-center shrink-0 mt-0.5">
            <span class="text-[11px] font-bold text-brand">1</span>
          </div>
          <div class="text-[13px] text-slate-600 leading-relaxed">Buka inbox atau folder <strong class="text-slate-800">Spam / Promo</strong> di email kamu</div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-6 h-6 rounded-full bg-brand/10 flex items-center justify-center shrink-0 mt-0.5">
            <span class="text-[11px] font-bold text-brand">2</span>
          </div>
          <div class="text-[13px] text-slate-600 leading-relaxed">Klik tombol <strong class="text-slate-800">"Verifikasi Sekarang"</strong> di dalam email</div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-6 h-6 rounded-full bg-brand/10 flex items-center justify-center shrink-0 mt-0.5">
            <span class="text-[11px] font-bold text-brand">3</span>
          </div>
          <div class="text-[13px] text-slate-600 leading-relaxed">Akun kamu akan langsung aktif dan terverifikasi</div>
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
        <div class="text-[12px] text-amber-700 leading-relaxed">Tidak menemukan email? Coba cek folder <strong class="font-semibold">Spam</strong> atau <strong class="font-semibold">Promosi</strong>. Link berlaku selama 24 jam.</div>
      </div>
    </div>

    <div class="flex gap-3 mt-6 w-full max-w-sm">
      <button onclick="history.back()" class="flex-1 rounded-xl border border-slate-200 bg-white py-3 text-[14px] font-bold text-slate-700 shadow-soft transition hover:bg-slate-50 active:scale-[0.98]">
        Kembali
      </button>
      <a href="index.php" class="flex-1 rounded-xl bg-brand py-3 text-[14px] font-bold text-white text-center shadow-lg transition hover:bg-brandDark active:scale-[0.98]">
        Cek Lagi
      </a>
    </div>

  </main>
</body>
</html>
