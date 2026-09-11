<?PHP
require_once("../config.php");
require_once('../_session.php');
?> 

<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Keuntungan Pakai BukaKios</title>
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
      <div class="shrink-0 text-[19px] font-extrabold tracking-[-0.04em] text-brand">BukaKios</div>
    </div>
  </header>

  <main class="px-4 pt-6">

    <!-- Hero Section -->
    <div class="flex flex-col items-center text-center mb-8">
      <div class="w-20 h-20 rounded-2xl bg-brand/10 flex items-center justify-center mb-4 shadow-soft">
        <svg viewBox="0 0 24 24" class="w-10 h-10 text-brand" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
        </svg>
      </div>
      <h1 class="text-[23px] font-extrabold tracking-tight text-slate-900">Keuntungan Pakai BukaKios</h1>
      <p class="text-sm text-slate-500 mt-1">Nikmati berbagai keuntungan untuk bisnismu</p>
    </div>

    <!-- Feature Cards -->
    <div class="space-y-4">

      <!-- Produk Lengkap -->
      <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-card">
        <div class="flex items-start gap-4">
          <div class="w-14 h-14 rounded-xl bg-brand/10 flex items-center justify-center shrink-0">
            <svg viewBox="0 0 24 24" class="w-8 h-8 text-brand" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
              <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <h3 class="font-bold text-slate-900 mb-1">Produk Lengkap</h3>
            <p class="text-sm text-slate-600 leading-relaxed">Ada ratusan jenis produk dan ribuan produk digital yang dihadirkan untuk melengkapi kebutuhan pembayaran, komunikasi dan online kamu sehari-hari.</p>
          </div>
        </div>
      </div>

      <!-- Transaksi Cepat -->
      <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-card">
        <div class="flex items-start gap-4">
          <div class="w-14 h-14 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
            <svg viewBox="0 0 24 24" class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <h3 class="font-bold text-slate-900 mb-1">Transaksi Cepat</h3>
            <p class="text-sm text-slate-600 leading-relaxed">Proses transaksi secepat kilat! Tidak perlu menunggu lama. Semua proses transaksi bakal diproses hanya dalam beberapa detik saja.</p>
          </div>
        </div>
      </div>

      <!-- Laporan Lengkap -->
      <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-card">
        <div class="flex items-start gap-4">
          <div class="w-14 h-14 rounded-xl bg-green-50 flex items-center justify-center shrink-0">
            <svg viewBox="0 0 24 24" class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14 2 14 8 20 8"/>
              <line x1="16" y1="13" x2="8" y2="13"/>
              <line x1="16" y1="17" x2="8" y2="17"/>
              <polyline points="10 9 9 9 8 9"/>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <h3 class="font-bold text-slate-900 mb-1">Laporan Lengkap</h3>
            <p class="text-sm text-slate-600 leading-relaxed">Bye bye buat laporan tertulis! Fitur laporan yang mencatat omset dan penjualan secara online kini sudah hadir secara otomatis.</p>
          </div>
        </div>
      </div>

      <!-- Harga Murah -->
      <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-card">
        <div class="flex items-start gap-4">
          <div class="w-14 h-14 rounded-xl bg-purple-50 flex items-center justify-center shrink-0">
            <svg viewBox="0 0 24 24" class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="12" y1="1" x2="12" y2="23"/>
              <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <h3 class="font-bold text-slate-900 mb-1">Harga Murah</h3>
            <p class="text-sm text-slate-600 leading-relaxed">Ga bikin kantong seret! Semua produk yang kami berikan dijamin murah. Saking murahnya, bisa banget buat dijual kembali.</p>
          </div>
        </div>
      </div>

      <!-- 100% Gratis -->
      <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-card">
        <div class="flex items-start gap-4">
          <div class="w-14 h-14 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
            <svg viewBox="0 0 24 24" class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 2L2 7l10 5 10-5-10-5z"/>
              <path d="M2 17l10 5 10-5"/>
              <path d="M2 12l10 5 10-5"/>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <h3 class="font-bold text-slate-900 mb-1">100% Gratis</h3>
            <p class="text-sm text-slate-600 leading-relaxed">Tidak ada biaya pendaftaran dan berlangganan. Semua fitur dapat diakses secara gratis. Kamu cukup keluar uang buat isi saldo transaksi saja!</p>
          </div>
        </div>
      </div>

      <!-- Cetak Struk -->
      <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-card">
        <div class="flex items-start gap-4">
          <div class="w-14 h-14 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0">
            <svg viewBox="0 0 24 24" class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="6 9 6 2 18 2 18 9"/>
              <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
              <rect x="6" y="14" width="12" height="8"/>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <h3 class="font-bold text-slate-900 mb-1">Cetak Struk</h3>
            <p class="text-sm text-slate-600 leading-relaxed">Kamu bisa cetak struk semua transaksi yang kamu lakukan di BukaKios. Compatible dengan printer bluetooth/thermal dan printer biasa.</p>
          </div>
        </div>
      </div>

      <!-- Bantuan CS Ramah -->
      <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-card">
        <div class="flex items-start gap-4">
          <div class="w-14 h-14 rounded-xl bg-pink-50 flex items-center justify-center shrink-0">
            <svg viewBox="0 0 24 24" class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <h3 class="font-bold text-slate-900 mb-1">Bantuan CS Ramah</h3>
            <p class="text-sm text-slate-600 leading-relaxed">Mengalami kendala atau punya pertanyaan? BukaKios Care dengan senang hati membantu mencarikan solusi via Live Chat dan WhatsApp.</p>
          </div>
        </div>
      </div>

      <!-- Transfer Bank -->
      <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-card">
        <div class="flex items-start gap-4">
          <div class="w-14 h-14 rounded-xl bg-cyan-50 flex items-center justify-center shrink-0">
            <svg viewBox="0 0 24 24" class="w-8 h-8 text-cyan-600" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
              <line x1="1" y1="10" x2="23" y2="10"/>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <h3 class="font-bold text-slate-900 mb-1">Transfer Bank</h3>
            <p class="text-sm text-slate-600 leading-relaxed">Bisnis transfer bank, kenapa tidak? Kamu bisa mulai bisnis transfer antar bank walaupun tidak punya rekening. Biaya admin mulai Rp 2.500.</p>
          </div>
        </div>
      </div>

      <!-- Ajak Orang Dapat Cuan -->
      <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-card">
        <div class="flex items-start gap-4">
          <div class="w-14 h-14 rounded-xl bg-orange-50 flex items-center justify-center shrink-0">
            <svg viewBox="0 0 24 24" class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
              <circle cx="9" cy="7" r="4"/>
              <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
              <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <h3 class="font-bold text-slate-900 mb-1">Ajak Orang Dapat Cuan</h3>
            <p class="text-sm text-slate-600 leading-relaxed">Buat downline dengan mengundang keluarga, teman dan kerabat untuk bergabung. Kamu akan mendapatkan komisi hingga Rp 100.000 setiap downlinemu bertransaksi.</p>
          </div>
        </div>
      </div>

    </div>

  </main>

</body>
</html>
