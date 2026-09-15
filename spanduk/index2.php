<?php
require_once("../config.php");
require_once("../_session.php");

if (isset($_REQUEST['msg'])) {
    require_once '_act.php';
}

if (isset($_POST['file'])) {
    require_once "_actv2.php";
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Bahan Promosi - BukaKios</title>
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
      <div class="w-20 h-20 rounded-2xl bg-brand/10 flex items-center justify-center mb-4 shadow-soft">
        <svg viewBox="0 0 24 24" class="w-10 h-10 text-brand" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
          <circle cx="8.5" cy="8.5" r="1.5"/>
          <polyline points="21 15 16 10 5 21"/>
        </svg>
      </div>
      <h1 class="text-[22px] font-extrabold tracking-tight text-slate-900">Bahan Promosi</h1>
      <p class="text-sm text-slate-500 mt-1">Download berbagai materi promosi untuk media sosial</p>
    </div>

    <!-- Info Pills -->
    <div class="flex flex-wrap gap-2 mb-6">
      <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-[11px] font-medium text-slate-600 shadow-soft">
        <span class="w-1.5 h-1.5 rounded-full bg-brand"></span>
        Free Download
      </span>
      <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-[11px] font-medium text-slate-600 shadow-soft">
        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
        Format PNG & JPG
      </span>
    </div>

    <!-- Section: Spanduk Banner -->
    <div class="mb-4">
      <div class="flex items-center gap-2 mb-3">
        <div class="w-8 h-8 rounded-lg bg-brand/10 flex items-center justify-center">
          <svg viewBox="0 0 24 24" class="w-4 h-4 text-brand" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="18" height="18" rx="2"/>
            <path d="M3 9h18"/>
            <path d="M9 21V9"/>
          </svg>
        </div>
        <h2 class="text-[15px] font-bold text-slate-800">Spanduk Banner</h2>
      </div>

      <div class="space-y-3">
        <!-- Card: Story Harga -->
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-card">
          <div class="aspect-[9/16] bg-slate-100 flex items-center justify-center">
            <img src="../file_download/975-story-harga-lite.png" alt="Bahan promosi lebaran" class="w-full h-full object-cover" onerror="this.style.display='none';this.parentElement.innerHTML='<div class=\'text-slate-400 text-sm\'>Gambar tidak tersedia</div>'">
          </div>
          <div class="p-4 flex items-center justify-between">
            <div>
              <h3 class="text-[13px] font-semibold text-slate-800">Bahan Promosi Lebaran</h3>
              <p class="text-[11px] text-slate-500 mt-0.5">Story 9:16 • PNG</p>
            </div>
            <button onclick="downloadFile(975, 'Bahan Promosi Lebaran')" class="inline-flex items-center gap-1.5 rounded-xl bg-brand px-4 py-2 text-[12px] font-bold text-white shadow-sm transition hover:bg-brandDark active:scale-95">
              <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
              </svg>
              Download
            </button>
          </div>
        </div>

        <!-- Card: Spanduk Toko -->
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-card">
          <div class="aspect-video bg-slate-100 flex items-center justify-center">
            <img src="https://assets2.bukakios.net/img2/uploads/2025/12/685-cover-spanduk-v1.png" alt="Spanduk Toko Bukakios" class="w-full h-full object-cover" onerror="this.style.display='none';this.parentElement.innerHTML='<div class=\'text-slate-400 text-sm\'>Gambar tidak tersedia</div>'">
          </div>
          <div class="p-4 flex items-center justify-between">
            <div>
              <h3 class="text-[13px] font-semibold text-slate-800">Spanduk Toko Bukakios</h3>
              <p class="text-[11px] text-slate-500 mt-0.5">Google Drive</p>
            </div>
            <a href="https://drive.google.com/drive/folders/1BrQ0IorA2yJNnPGsU_-BX1Rj9EKoe9dt?usp=drive_link" target="_blank" class="inline-flex items-center gap-1.5 rounded-xl bg-brand px-4 py-2 text-[12px] font-bold text-white shadow-sm transition hover:bg-brandDark active:scale-95 no-underline">
              <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
              </svg>
              Download
            </a>
          </div>
        </div>

        <!-- Card: Spanduk Mitra -->
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-card">
          <div class="aspect-video bg-slate-100 flex items-center justify-center">
            <img src="https://assets2.bukakios.net/img2/uploads/2025/12/1000-cover-spanduk-v2.png" alt="Spanduk Mitra Bukakios" class="w-full h-full object-cover" onerror="this.style.display='none';this.parentElement.innerHTML='<div class=\'text-slate-400 text-sm\'>Gambar tidak tersedia</div>'">
          </div>
          <div class="p-4 flex items-center justify-between">
            <div>
              <h3 class="text-[13px] font-semibold text-slate-800">Spanduk Mitra Bukakios</h3>
              <p class="text-[11px] text-slate-500 mt-0.5">Google Drive</p>
            </div>
            <a href="https://drive.google.com/drive/folders/1BrQ0IorA2yJNnPGsU_-BX1Rj9EKoe9dt?usp=drive_link" target="_blank" class="inline-flex items-center gap-1.5 rounded-xl bg-brand px-4 py-2 text-[12px] font-bold text-white shadow-sm transition hover:bg-brandDark active:scale-95 no-underline">
              <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
              </svg>
              Download
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Section: Story Sosial Media -->
    <div class="mb-4">
      <div class="flex items-center gap-2 mb-3">
        <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
          <svg viewBox="0 0 24 24" class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
          </svg>
        </div>
        <h2 class="text-[15px] font-bold text-slate-800">Story Sosial Media</h2>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <!-- Story 1 -->
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-card">
          <div class="aspect-[9/16] bg-slate-100 flex items-center justify-center">
            <img src="../file_download/918-promosi-1-story-lite.png" alt="Promosi 1" class="w-full h-full object-cover" onerror="this.style.display='none';this.parentElement.innerHTML='<div class=\'text-slate-400 text-xs text-center p-2\'>Tidak tersedia</div>'">
          </div>
          <div class="p-3">
            <p class="text-[11px] text-slate-500 mb-2">Story 9:16 • PNG</p>
            <button onclick="downloadFile(918, 'Promosi 1')" class="w-full inline-flex items-center justify-center gap-1 rounded-xl bg-brand/10 px-3 py-2 text-[11px] font-bold text-brand transition hover:bg-brand/20 active:scale-95">
              <svg viewBox="0 0 24 24" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
              </svg>
              Download
            </button>
          </div>
        </div>

        <!-- Story 2 -->
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-card">
          <div class="aspect-[9/16] bg-slate-100 flex items-center justify-center">
            <img src="../file_download/817-promosi-2-story-lite.png" alt="Promosi 2" class="w-full h-full object-cover" onerror="this.style.display='none';this.parentElement.innerHTML='<div class=\'text-slate-400 text-xs text-center p-2\'>Tidak tersedia</div>'">
          </div>
          <div class="p-3">
            <p class="text-[11px] text-slate-500 mb-2">Story 9:16 • PNG</p>
            <button onclick="downloadFile(817, 'Promosi 2')" class="w-full inline-flex items-center justify-center gap-1 rounded-xl bg-brand/10 px-3 py-2 text-[11px] font-bold text-brand transition hover:bg-brand/20 active:scale-95">
              <svg viewBox="0 0 24 24" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
              </svg>
              Download
            </button>
          </div>
        </div>

        <!-- Story 3 -->
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-card">
          <div class="aspect-[9/16] bg-slate-100 flex items-center justify-center">
            <img src="../file_download/91-promosi-3-story-lite.png" alt="Promosi 3" class="w-full h-full object-cover" onerror="this.style.display='none';this.parentElement.innerHTML='<div class=\'text-slate-400 text-xs text-center p-2\'>Tidak tersedia</div>'">
          </div>
          <div class="p-3">
            <p class="text-[11px] text-slate-500 mb-2">Story 9:16 • PNG</p>
            <button onclick="downloadFile(91, 'Promosi 3')" class="w-full inline-flex items-center justify-center gap-1 rounded-xl bg-brand/10 px-3 py-2 text-[11px] font-bold text-brand transition hover:bg-brand/20 active:scale-95">
              <svg viewBox="0 0 24 24" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
              </svg>
              Download
            </button>
          </div>
        </div>

        <!-- Story 4 -->
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-card">
          <div class="aspect-[9/16] bg-slate-100 flex items-center justify-center">
            <img src="../file_download/347-promosi-4-story-lite.png" alt="Promosi 4" class="w-full h-full object-cover" onerror="this.style.display='none';this.parentElement.innerHTML='<div class=\'text-slate-400 text-xs text-center p-2\'>Tidak tersedia</div>'">
          </div>
          <div class="p-3">
            <p class="text-[11px] text-slate-500 mb-2">Story 9:16 • PNG</p>
            <button onclick="downloadFile(347, 'Promosi 4')" class="w-full inline-flex items-center justify-center gap-1 rounded-xl bg-brand/10 px-3 py-2 text-[11px] font-bold text-brand transition hover:bg-brand/20 active:scale-95">
              <svg viewBox="0 0 24 24" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
              </svg>
              Download
            </button>
          </div>
        </div>

        <!-- Story 5 - Daftar Harga -->
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-card">
          <div class="aspect-[9/16] bg-slate-100 flex items-center justify-center">
            <img src="../file_download/839-daftar-harga-story-lite.png" alt="Daftar Harga" class="w-full h-full object-cover" onerror="this.style.display='none';this.parentElement.innerHTML='<div class=\'text-slate-400 text-xs text-center p-2\'>Tidak tersedia</div>'">
          </div>
          <div class="p-3">
            <p class="text-[11px] text-slate-500 mb-2">Story 9:16 • PNG</p>
            <button onclick="downloadFile(839, 'Daftar Harga Story')" class="w-full inline-flex items-center justify-center gap-1 rounded-xl bg-brand/10 px-3 py-2 text-[11px] font-bold text-brand transition hover:bg-brand/20 active:scale-95">
              <svg viewBox="0 0 24 24" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
              </svg>
              Download
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Section: Postingan Sosial Media -->
    <div class="mb-4">
      <div class="flex items-center gap-2 mb-3">
        <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
          <svg viewBox="0 0 24 24" class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="18" height="18" rx="2"/>
            <circle cx="8.5" cy="8.5" r="1.5"/>
            <polyline points="21 15 16 10 5 21"/>
          </svg>
        </div>
        <h2 class="text-[15px] font-bold text-slate-800">Postingan Sosial Media</h2>
      </div>

      <div class="space-y-3">
        <!-- Post 1 -->
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-card">
          <div class="aspect-square bg-slate-100 flex items-center justify-center">
            <img src="../file_download/554-promosi-1-lite.png" alt="Postingan 1" class="w-full h-full object-cover" onerror="this.style.display='none';this.parentElement.innerHTML='<div class=\'text-slate-400 text-sm\'>Gambar tidak tersedia</div>'">
          </div>
          <div class="p-4 flex items-center justify-between">
            <div>
              <h3 class="text-[13px] font-semibold text-slate-800">Postingan Promosi 1</h3>
              <p class="text-[11px] text-slate-500 mt-0.5">Square 1:1 • PNG</p>
            </div>
            <button onclick="downloadFile(554, 'Postingan Promosi 1')" class="inline-flex items-center gap-1.5 rounded-xl bg-brand px-4 py-2 text-[12px] font-bold text-white shadow-sm transition hover:bg-brandDark active:scale-95">
              <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
              </svg>
              Download
            </button>
          </div>
        </div>

        <!-- Post 2 -->
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-card">
          <div class="aspect-square bg-slate-100 flex items-center justify-center">
            <img src="../file_download/971-promosi-2-lite.png" alt="Postingan 2" class="w-full h-full object-cover" onerror="this.style.display='none';this.parentElement.innerHTML='<div class=\'text-slate-400 text-sm\'>Gambar tidak tersedia</div>'">
          </div>
          <div class="p-4 flex items-center justify-between">
            <div>
              <h3 class="text-[13px] font-semibold text-slate-800">Postingan Promosi 2</h3>
              <p class="text-[11px] text-slate-500 mt-0.5">Square 1:1 • PNG</p>
            </div>
            <button onclick="downloadFile(971, 'Postingan Promosi 2')" class="inline-flex items-center gap-1.5 rounded-xl bg-brand px-4 py-2 text-[12px] font-bold text-white shadow-sm transition hover:bg-brandDark active:scale-95">
              <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
              </svg>
              Download
            </button>
          </div>
        </div>

        <!-- Post 3 -->
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-card">
          <div class="aspect-square bg-slate-100 flex items-center justify-center">
            <img src="../file_download/974-promosi-3-lite.png" alt="Postingan 3" class="w-full h-full object-cover" onerror="this.style.display='none';this.parentElement.innerHTML='<div class=\'text-slate-400 text-sm\'>Gambar tidak tersedia</div>'">
          </div>
          <div class="p-4 flex items-center justify-between">
            <div>
              <h3 class="text-[13px] font-semibold text-slate-800">Postingan Promosi 3</h3>
              <p class="text-[11px] text-slate-500 mt-0.5">Square 1:1 • PNG</p>
            </div>
            <button onclick="downloadFile(974, 'Postingan Promosi 3')" class="inline-flex items-center gap-1.5 rounded-xl bg-brand px-4 py-2 text-[12px] font-bold text-white shadow-sm transition hover:bg-brandDark active:scale-95">
              <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
              </svg>
              Download
            </button>
          </div>
        </div>

        <!-- Post 4 -->
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-card">
          <div class="aspect-square bg-slate-100 flex items-center justify-center">
            <img src="../file_download/529-promosi-4-lite.png" alt="Postingan 4" class="w-full h-full object-cover" onerror="this.style.display='none';this.parentElement.innerHTML='<div class=\'text-slate-400 text-sm\'>Gambar tidak tersedia</div>'">
          </div>
          <div class="p-4 flex items-center justify-between">
            <div>
              <h3 class="text-[13px] font-semibold text-slate-800">Postingan Promosi 4</h3>
              <p class="text-[11px] text-slate-500 mt-0.5">Square 1:1 • PNG</p>
            </div>
            <button onclick="downloadFile(529, 'Postingan Promosi 4')" class="inline-flex items-center gap-1.5 rounded-xl bg-brand px-4 py-2 text-[12px] font-bold text-white shadow-sm transition hover:bg-brandDark active:scale-95">
              <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
              </svg>
              Download
            </button>
          </div>
        </div>

        <!-- Post 5 - Daftar Harga -->
        <div class="bg-white hidden rounded-2xl border border-slate-100 overflow-hidden shadow-card">
          <div class="aspect-square bg-slate-100 flex items-center justify-center">
            <img src="../file_download/839-daftar-harga-lite.png" alt="Daftar Harga" class="w-full h-full object-cover" onerror="this.style.display='none';this.parentElement.innerHTML='<div class=\'text-slate-400 text-sm\'>Gambar tidak tersedia</div>'">
          </div>
          <div class="p-4 flex items-center justify-between">
            <div>
              <h3 class="text-[13px] font-semibold text-slate-800">Daftar Harga</h3>
              <p class="text-[11px] text-slate-500 mt-0.5">Square 1:1 • PNG</p>
            </div>
            <button onclick="downloadFile(839, 'Daftar Harga')" class="inline-flex items-center gap-1.5 rounded-xl bg-brand px-4 py-2 text-[12px] font-bold text-white shadow-sm transition hover:bg-brandDark active:scale-95">
              <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
              </svg>
              Download
            </button>
          </div>
        </div>
      </div>
    </div>

  </main>

  <!-- Loading Overlay -->
  <div id="loading-overlay" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:16px;padding:24px;display:flex;flex-direction:column;align-items:center;gap:12px;box-shadow:0 20px 60px rgba(0,0,0,0.15);min-width:200px;">
      <div id="spinner" style="width:44px;height:44px;border:4px solid rgba(26,127,206,0.2);border-top-color:#1a7fce;border-radius:50%;animation:spin 0.8s linear infinite;display:none;"></div>
      <div id="loading-text" style="font-size:14px;font-weight:600;color:#334155;">Memproses download...</div>
    </div>
  </div>

  <style>
    @keyframes spin { to { transform: rotate(360deg); } }
  </style>

  <script>
    function downloadFile(fileId, fileName) {
      var overlay = document.getElementById('loading-overlay');
      var spinner = document.getElementById('spinner');
      var loadingText = document.getElementById('loading-text');

      // Tampilkan loading dengan spinner
      spinner.style.display = 'block';
      overlay.style.display = 'flex';

      // Trigger download
      window.location.href = '../file_download/download.php?file=' + fileId;

      // Update teks dan hide overlay setelah download dipicu
      setTimeout(function() {
        loadingText.textContent = 'Download dimulai...';
        spinner.style.display = 'none';
      }, 300);

      // Hide overlay sepenuhnya
      setTimeout(function() {
        overlay.style.display = 'none';
      }, 600);
    }
  </script>

</body>
</html>
