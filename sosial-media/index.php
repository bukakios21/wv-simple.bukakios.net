<?php
require_once("../config.php");
require_once("../_session.php");
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Social Media Kami - BukaKios</title>
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
      <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-pink-500 via-red-500 to-yellow-500 flex items-center justify-center mb-4 shadow-soft overflow-hidden">
        <svg viewBox="0 0 24 24" class="w-12 h-12 text-white" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
        </svg>
      </div>
      <h1 class="text-[23px] font-extrabold tracking-tight text-slate-900">Social Media Kami</h1>
      <p class="text-sm text-slate-500 mt-1">Ikuti kami untuk info terbaru</p>
    </div>

    <!-- Social Media List -->
    <div class="space-y-3">
      
      <!-- Facebook -->
      <a href="https://web.facebook.com/bukakios.net/" target="_blank" class="block bg-white rounded-2xl border border-slate-100 p-4 shadow-card transition hover:shadow-md active:scale-[0.99]">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl bg-[#3B5998] flex items-center justify-center shrink-0">
            <svg viewBox="0 0 24 24" class="w-6 h-6 text-white" fill="currentColor">
              <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
            </svg>
          </div>
          <div class="flex-1">
            <h3 class="text-[16px] font-bold text-slate-800">Facebook</h3>
            <p class="text-[13px] text-slate-500">@bukakios.net</p>
          </div>
          <svg viewBox="0 0 24 24" class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"/>
          </svg>
        </div>
      </a>

      <!-- Instagram -->
      <a href="https://www.instagram.com/bukakiosnet/" target="_blank" class="block bg-white rounded-2xl border border-slate-100 p-4 shadow-card transition hover:shadow-md active:scale-[0.99]">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-400 via-pink-500 to-purple-600 flex items-center justify-center shrink-0">
            <svg viewBox="0 0 24 24" class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
              <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
              <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
            </svg>
          </div>
          <div class="flex-1">
            <h3 class="text-[16px] font-bold text-slate-800">Instagram</h3>
            <p class="text-[13px] text-slate-500">@bukakiosnet</p>
          </div>
          <svg viewBox="0 0 24 24" class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"/>
          </svg>
        </div>
      </a>

      <!-- YouTube -->
      <a href="https://www.youtube.com/channel/UCYSR6hSEnupOo5abO1U0Y4A" target="_blank" class="block bg-white rounded-2xl border border-slate-100 p-4 shadow-card transition hover:shadow-md active:scale-[0.99]">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl bg-[#bb0000] flex items-center justify-center shrink-0">
            <svg viewBox="0 0 24 24" class="w-6 h-6 text-white" fill="currentColor">
              <path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"/>
              <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02" fill="#fff"/>
            </svg>
          </div>
          <div class="flex-1">
            <h3 class="text-[16px] font-bold text-slate-800">YouTube</h3>
            <p class="text-[13px] text-slate-500">BukaKios Official</p>
          </div>
          <svg viewBox="0 0 24 24" class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"/>
          </svg>
        </div>
      </a>

      <!-- Telegram -->
      <a href="https://t.me/infobukakios" target="_blank" class="block bg-white rounded-2xl border border-slate-100 p-4 shadow-card transition hover:shadow-md active:scale-[0.99]">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl bg-[#0088cc] flex items-center justify-center shrink-0">
            <svg viewBox="0 0 24 24" class="w-6 h-6 text-white" fill="currentColor">
              <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
            </svg>
          </div>
          <div class="flex-1">
            <h3 class="text-[16px] font-bold text-slate-800">Telegram Channel</h3>
            <p class="text-[13px] text-slate-500">@infobukakios</p>
          </div>
          <svg viewBox="0 0 24 24" class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"/>
          </svg>
        </div>
      </a>

    </div>


    <!-- Info Card -->
    <div class="mt-6 bg-gradient-to-br from-brand to-brandDark rounded-2xl p-5 shadow-card">
      <div class="flex items-start gap-3">
        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center shrink-0">
          <svg viewBox="0 0 24 24" class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="16" x2="12" y2="12"/>
            <line x1="12" y1="8" x2="12.01" y2="8"/>
          </svg>
        </div>
        <div>
          <h3 class="text-[14px] font-bold text-white">Ikuti Kami</h3>
          <p class="text-[12px] text-white/80 mt-1 leading-relaxed">
            Dapatkan info terbaru, promo spesial, dan update produk BukaKios di semua social media kami.
          </p>
        </div>
      </div>
    </div>

  </main>

</body>
</html>
