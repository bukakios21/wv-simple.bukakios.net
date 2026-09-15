<?php
require_once("../config.php");
require_once("../_session.php");

// Redirect logged in users to index2.php
if ($user_id != 0){
    header("location:index2.php");
    exit;
}

// Special user redirect
if ($user_id == 40408 or $user_id == 28834 or $user_id == 278542 or $user_id == 1000){
    $secret_key = "bukakios:selalu-dihati_";
    $hash_req = $request->hash;
    $next = str_replace("_", "/", $request->next);

    $hash = md5("$user_id:$user_token-$user_token_trx|$secret_key");
    header("Location: https://wv2.bukakios.net/login/$user_id/$user_token/$user_token_trx/$hash/v_spanduk");
    exit;
}

$cd = base64_encode("$user_id:cd.zip");
$ps = base64_encode("$user_id:ps.zip");
$spn = base64_encode("$user_id:spanduk_new.rar");

if (isset($_REQUEST['msg'])) {
    require_once '_act.php';
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
  <header class="sticky top-0 z-10 bg-white border-b border-slate-100">
    <div class="flex items-center gap-3 px-4 py-3">
      <div class="h-1 flex-1 rounded-full bg-slate-200 overflow-hidden">
        <div class="h-full w-full rounded-full bg-brand"></div>
      </div>
      <div class="shrink-0 text-[19px] font-extrabold tracking-[-0.04em] text-brand">BukaKios</div>
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
      <h1 class="text-[23px] font-extrabold tracking-tight text-slate-900">Bahan Promosi</h1>
      <p class="text-sm text-slate-500 mt-1">Download materi promosi untuk media sosial</p>
    </div>

    <!-- Info Pills -->
    <div class="flex flex-wrap gap-2 mb-6">
      <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-[12px] font-medium text-slate-600 shadow-soft">
        <span class="w-1.5 h-1.5 rounded-full bg-brand"></span>
        Free Download
      </span>
      <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-[12px] font-medium text-slate-600 shadow-soft">
        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
        File Photoshop & CorelDraw
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
        <h2 class="text-[16px] font-bold text-slate-800">Spanduk Banner</h2>
      </div>

      <div class="space-y-3">
        <!-- Card: Spanduk Toko -->
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-card">
          <div class="aspect-video bg-slate-100 flex items-center justify-center overflow-hidden">
            <img src="https://assets2.bukakios.net/img2/uploads/2025/12/685-cover-spanduk-v1.png" alt="Spanduk Toko Bukakios" class="w-full h-full object-cover" onerror="this.style.display='none';this.parentElement.innerHTML='<div class=\'text-slate-400 text-sm p-4\'>Gambar tidak tersedia</div>'">
          </div>
          <div class="p-4 flex items-center justify-between">
            <div>
              <h3 class="text-[14px] font-semibold text-slate-800">Spanduk Toko Bukakios</h3>
              <p class="text-[12px] text-slate-500 mt-0.5">Photoshop & CorelDraw</p>
            </div>
            <button onclick="showPsDownload()" class="inline-flex items-center gap-1.5 rounded-xl bg-brand px-4 py-2 text-[13px] font-bold text-white shadow-sm transition hover:bg-brandDark active:scale-95">
              <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
              </svg>
              Download
            </button>
          </div>
        </div>

        <!-- Card: Spanduk Mitra -->
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-card">
          <div class="aspect-video bg-slate-100 flex items-center justify-center overflow-hidden">
            <img src="https://assets2.bukakios.net/img2/uploads/2025/12/1000-cover-spanduk-v2.png" alt="Spanduk Mitra Bukakios" class="w-full h-full object-cover" onerror="this.style.display='none';this.parentElement.innerHTML='<div class=\'text-slate-400 text-sm p-4\'>Gambar tidak tersedia</div>'">
          </div>
          <div class="p-4 flex items-center justify-between">
            <div>
              <h3 class="text-[14px] font-semibold text-slate-800">Spanduk Mitra Bukakios</h3>
              <p class="text-[12px] text-slate-500 mt-0.5">Photoshop & CorelDraw</p>
            </div>
            <button onclick="showCdDownload()" class="inline-flex items-center gap-1.5 rounded-xl bg-brand px-4 py-2 text-[13px] font-bold text-white shadow-sm transition hover:bg-brandDark active:scale-95">
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

    <!-- Info Card -->
    <div class="bg-gradient-to-br from-brand to-brandDark rounded-2xl p-5 shadow-card">
      <div class="flex items-start gap-3">
        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center shrink-0">
          <svg viewBox="0 0 24 24" class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="16" x2="12" y2="12"/>
            <line x1="12" y1="8" x2="12.01" y2="8"/>
          </svg>
        </div>
        <div>
          <h3 class="text-[14px] font-bold text-white">Tips Penggunaan</h3>
          <p class="text-[12px] text-white/80 mt-1 leading-relaxed">
            File tersedia dalam format Photoshop (.PSD) dan CorelDraw (.CDR). Link download akan dikirim ke email kamu.
          </p>
        </div>
      </div>
    </div>

  </main>

  <!-- Loading Overlay -->
  <div id="loading-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:16px;padding:24px;display:flex;flex-direction:column;align-items:center;gap:12px;box-shadow:0 20px 60px rgba(0,0,0,0.15);min-width:200px;">
      <div style="width:44px;height:44px;border:4px solid rgba(26,127,206,0.2);border-top-color:#1a7fce;border-radius:50%;animation:spin 0.8s linear infinite;"></div>
      <div style="font-size:15px;font-weight:600;color:#334155;">Mengirim link download...</div>
    </div>
  </div>

  <style>
    @keyframes spin { to { transform: rotate(360deg); } }
  </style>

  <script>
    function showPsDownload() {
      Swal.fire({
        title: '<span class="text-[19px] font-bold text-slate-800">Download Spanduk Toko</span>',
        html: `
          <div class="text-left">
            <p class="text-[14px] text-slate-600 mb-4">Pilih format file yang diinginkan:</p>
            <div class="space-y-2">
              <button onclick="sendDownloadLink('<?php echo $ps ?>', 'Photoshop')" class="w-full flex items-center justify-between px-4 py-3 bg-blue-50 hover:bg-blue-100 rounded-xl transition">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-lg bg-blue-500 flex items-center justify-center">
                    <svg viewBox="0 0 24 24" class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                      <polyline points="14 2 14 8 20 8"/>
                    </svg>
                  </div>
                  <div>
                    <p class="text-[14px] font-semibold text-slate-800">Photoshop</p>
                    <p class="text-[12px] text-slate-500">.PSD format</p>
                  </div>
                </div>
                <svg viewBox="0 0 24 24" class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="9 18 15 12 9 6"/>
                </svg>
              </button>
              <button onclick="sendDownloadLink('<?php echo $cd ?>', 'CorelDraw')" class="w-full flex items-center justify-between px-4 py-3 bg-amber-50 hover:bg-amber-100 rounded-xl transition">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-lg bg-amber-500 flex items-center justify-center">
                    <svg viewBox="0 0 24 24" class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                      <polyline points="14 2 14 8 20 8"/>
                    </svg>
                  </div>
                  <div>
                    <p class="text-[14px] font-semibold text-slate-800">CorelDraw</p>
                    <p class="text-[12px] text-slate-500">.CDR format</p>
                  </div>
                </div>
                <svg viewBox="0 0 24 24" class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="9 18 15 12 9 6"/>
                </svg>
              </button>
            </div>
          </div>
        `,
        showCloseButton: true,
        showConfirmButton: false,
        customClass: {
          popup: 'rounded-2xl',
          title: 'text-lg'
        }
      });
    }

    function showCdDownload() {
      Swal.fire({
        title: '<span class="text-[19px] font-bold text-slate-800">Download Spanduk Mitra</span>',
        html: `
          <div class="text-left">
            <p class="text-[14px] text-slate-600 mb-4">File akan dikirim ke email kamu.</p>
            <div class="bg-emerald-50 rounded-xl p-4 flex items-start gap-3">
              <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                <svg viewBox="0 0 24 24" class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                  <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
              </div>
              <div>
                <p class="text-[14px] font-semibold text-emerald-800">Proses Instan</p>
                <p class="text-[12px] text-emerald-600 mt-0.5">Cek email untuk link download</p>
              </div>
            </div>
            <button onclick="sendDownloadLink('<?php echo $spn ?>', 'Spanduk Mitra')" class="w-full mt-4 inline-flex items-center justify-center gap-2 rounded-xl bg-brand px-4 py-3 text-[14px] font-bold text-white transition hover:bg-brandDark active:scale-95">
              <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="22" y1="2" x2="11" y2="13"/>
                <polygon points="22 2 15 22 11 13 2 9 22 2"/>
              </svg>
              Kirim Link Download
            </button>
          </div>
        `,
        showCloseButton: true,
        showConfirmButton: false,
        customClass: {
          popup: 'rounded-2xl',
          title: 'text-lg'
        }
      });
    }

    function sendDownloadLink(text, formatName) {
      var $overlay = $('#loading-overlay');
      $overlay.show();

      $.ajax({
        type: "POST",
        url: "index.php?msg=send&text=" + text,
        success: function(result) {
          $overlay.hide();
          var data = JSON.parse(result);
          if (data.status == 1) {
            Swal.fire({
              icon: 'success',
              title: '<span class="text-[19px] font-bold text-slate-800">Link Terkirim!</span>',
              html: '<p class="text-[14px] text-slate-600">Silakan cek email kamu untuk link download file ' + formatName + '.</p>',
              confirmButtonText: 'OK',
              customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl bg-brand px-6 py-2.5 text-[14px] font-bold'
              }
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: '<span class="text-[19px] font-bold text-slate-800">Gagal</span>',
              html: '<p class="text-[14px] text-slate-600">' + (data.error_msg || 'Pastikan koneksi internet aktif.') + '</p>',
              confirmButtonText: 'OK',
              customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl bg-red-500 px-6 py-2.5 text-[14px] font-bold'
              }
            });
          }
        },
        error: function() {
          $overlay.hide();
          Swal.fire({
            icon: 'error',
            title: '<span class="text-[19px] font-bold text-slate-800">Gagal</span>',
            html: '<p class="text-[14px] text-slate-600">Terjadi kesalahan koneksi. Silakan coba lagi.</p>',
            confirmButtonText: 'OK',
            customClass: {
              popup: 'rounded-2xl',
              confirmButton: 'rounded-xl bg-red-500 px-6 py-2.5 text-[14px] font-bold'
            }
          });
        }
      });
    }
  </script>

</body>
</html>
