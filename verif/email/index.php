<?php
require_once(__DIR__ . "/../../config.php");
require_once(__DIR__ . "/../../_session.php");
require_once(__DIR__ . "/../../lib/ApiV2.php");
//var_dump($user_jwt);
$api_v2 = new ApiV2($user_jwt);

if (isset($_POST['act'])) {
    require_once("proses_email.php");
    exit;
}

$call_user = $api_v2->detail_user();
$data_user = json_decode($call_user, true);
$is_error = false;

if (!isset($data_user['status'])) {
    $is_error = true;
    $error_title = "Error";
    $error_msg = "Gagal mengambil data user.";
}
if (isset($data_user['status']) && $data_user['status'] == 0) {
    $is_error = true;
    $error_title = "Error";
    $error_msg = $data_user['error_msg'] ?? "Terjadi kesalahan.";
}

if ($is_error) {
    // Inline error view
    ?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Error - Verifikasi Email</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: '#1a7fce',
            brandDark: '#1265a6',
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
<body class="min-h-screen bg-slate-50 font-sans text-slate-950 antialiased flex flex-col items-center justify-center px-4">
  <div class="text-center">
    <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
      <svg viewBox="0 0 24 24" class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/>
        <line x1="12" y1="8" x2="12" y2="12"/>
        <line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
    </div>
    <h1 class="text-xl font-extrabold text-slate-900"><?= htmlspecialchars($error_title) ?></h1>
    <p class="text-sm text-slate-500 mt-2"><?= htmlspecialchars($error_msg) ?></p>
    <button onclick="history.back()" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-brand px-6 py-3 text-sm font-bold text-white shadow-lg transition hover:bg-brandDark active:scale-95">
      <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M15 18l-6-6 6-6"/>
      </svg>
      Kembali
    </button>
  </div>
</body>
</html>
    <?php
    exit;
}

$data_user = $data_user['data'];
$email = $data_user['email'] ?? '';
$verif_email = $data_user['verif_email'] ?? 0;



// Generate CSRF for AJAX
$csrf = md5(uniqid() . time());
$_SESSION['csrf'] = $csrf;
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Verifikasi Email - BukaKios</title>
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

  <main class="px-4 pt-6 pb-10">

    <!-- Icon Hero -->
    <div class="flex flex-col items-center text-center mb-6">
      <?php if ($verif_email == 0): ?>
        <!-- Email not verified -->
        <div class="w-20 h-20 rounded-2xl bg-brand/10 flex items-center justify-center mb-4 shadow-soft">
          <svg viewBox="0 0 24 24" class="w-10 h-10 text-brand" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
            <polyline points="22,6 12,13 2,6"/>
          </svg>
        </div>
        <h1 class="text-[22px] font-extrabold tracking-tight text-slate-900">Verifikasi Email</h1>
        <p class="text-sm text-slate-500 mt-1">Aktifkan email untuk keamanan akunmu</p>
      <?php else: ?>
        <!-- Email already verified -->
        <div class="w-20 h-20 rounded-2xl bg-emerald-50 flex items-center justify-center mb-4 shadow-soft">
          <svg viewBox="0 0 24 24" class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
            <polyline points="22 4 12 14.01 9 11.01"/>
          </svg>
        </div>
        <h1 class="text-[22px] font-extrabold tracking-tight text-slate-900">Email Terverifikasi</h1>
        <p class="text-sm text-slate-500 mt-1">Email kamu sudah aktif dan terverifikasi</p>
      <?php endif; ?>
    </div>

    <?php if ($verif_email == 0): ?>
    <!-- Main Card -->
    <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-card mb-4">
      <div class="flex items-start gap-3 mb-4">
        <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center shrink-0 mt-0.5">
          <svg viewBox="0 0 24 24" class="w-4 h-4 text-amber-500" fill="currentColor">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
          </svg>
        </div>
        <div>
          <div class="text-[13px] font-bold text-slate-800">Link verifikasi akan dikirim ke:</div>
          <div class="text-[13px] text-brand font-semibold mt-1 font-mono"><?= htmlspecialchars($email) ?></div>
        </div>
      </div>

      <p class="text-[13px] text-slate-600 leading-relaxed">
        Klik tombol <strong class="text-slate-800 font-semibold">Kirim Link Verifikasi</strong> di bawah untuk mengirim email verifikasi ke alamat di atas. Buka email lalu klik tombol <strong class="text-slate-800 font-semibold">"Verifikasi Sekarang"</strong> untuk mengaktifkan email kamu.
      </p>

    </div>

    <!-- Info Pills -->
    <div class="flex flex-wrap gap-2 mb-6">
      <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-[11px] font-medium text-slate-600 shadow-soft">
        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
        Keamanan Akun
      </span>
      <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-[11px] font-medium text-slate-600 shadow-soft">
        <span class="w-1.5 h-1.5 rounded-full bg-brand"></span>
        Proses Instan
      </span>

    </div>

    <!-- Send Button -->
    <button id="btn-kirim" class="w-full rounded-xl bg-brand py-3.5 text-[15px] font-bold text-white shadow-lg transition hover:bg-brandDark active:scale-[0.98] flex items-center justify-center gap-2">
      <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="22" y1="2" x2="11" y2="13"/>
        <polygon points="22 2 15 22 11 13 2 9 22 2"/>
      </svg>
      Kirim Link Verifikasi
    </button>

    <!-- Status message -->
    <div id="status-msg" class="hidden mt-3 text-center text-[13px] font-medium"></div>

    <?php else: ?>
    <!-- Already Verified Card -->
    <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-card mb-4">
      <div class="flex flex-col items-center text-center">
        <div class="w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center mb-4">
          <svg viewBox="0 0 24 24" class="w-7 h-7 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"/>
          </svg>
        </div>
        <h2 class="text-[15px] font-extrabold text-slate-800">Email Sudah Aktif</h2>
        <p class="text-[13px] text-slate-500 mt-2 leading-relaxed">
          Email kamu <strong class="text-slate-700"><?= htmlspecialchars($email) ?></strong> sudah terverifikasi dan aktif untuk keamanan akun BukaKios.
        </p>
      </div>
    </div>

    <!-- Keunggulan mini card -->
    <div class="bg-gradient-to-br from-brand to-brandDark rounded-2xl p-5 shadow-card mb-4">
      <div class="space-y-3">
        <div class="flex items-start gap-3">
          <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center shrink-0 mt-0.5">
            <svg viewBox="0 0 24 24" class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
          </div>
          <div>
            <div class="text-[13px] font-bold text-white">Akun Lebih Aman</div>
            <div class="text-[11px] text-white/70 mt-0.5">Email terverifikasi menambah lapisan keamanan akunmu</div>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center shrink-0 mt-0.5">
            <svg viewBox="0 0 24 24" class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
          </div>
          <div>
            <div class="text-[13px] font-bold text-white">Notifikasi Transaksi</div>
            <div class="text-[11px] text-white/70 mt-0.5">Dapatkan notifikasi setiap transaksi via email</div>
          </div>
        </div>
      </div>
    </div>

    <button onclick="history.back()" class="w-full rounded-xl border border-slate-200 bg-white py-3.5 text-[15px] font-bold text-slate-700 shadow-soft transition hover:bg-slate-50 active:scale-[0.98]">
      Kembali
    </button>
    <?php endif; ?>

  </main>

  <!-- Loading overlay -->
  <div id="loading-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:16px;padding:24px;display:flex;flex-direction:column;align-items:center;gap:12px;box-shadow:0 20px 60px rgba(0,0,0,0.15);min-width:200px;">
      <div style="width:44px;height:44px;border:4px solid rgba(26,127,206,0.2);border-top-color:#1a7fce;border-radius:50%;animation:spin 0.8s linear infinite;"></div>
      <div style="font-size:14px;font-weight:600;color:#334155;">Mengirim email...</div>
    </div>
  </div>
  <style>
    @keyframes spin { to { transform: rotate(360deg); } }
  </style>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      var csrf = "<?= $csrf ?>";

      $('#btn-kirim').on('click', function() {
        var $btn = $('#btn-kirim');
        var $overlay = $('#loading-overlay');
        var $msg = $('#status-msg');

        $btn.html('<div style="width:18px;height:18px;border:2px solid rgba(255,255,255,0.4);border-top-color:#fff;border-radius:50%;animation:spin .8s linear infinite;display:inline-block"></div> Mengirim...').attr('disabled', true);
        $overlay.show();

        $.ajax({
          url: window.location.pathname,
          data: {act: "kirim", csrf: csrf},
          method: 'POST',
          dataType: 'json',
          timeout: 15000,
          success: function(data) {
            if (data.status == 1) {
              // Jangan hide overlay — spinner terus berputar saat redirect
              $btn.html('<svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Email Terkirim!');
              $btn.removeClass('bg-brand hover:bg-brandDark').addClass('bg-emerald-500 cursor-default');
              window.location.href = 'success.php';
            } else {
              $btn.html('<svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> Gagal - Coba Lagi');
              $overlay.hide();
              $msg.removeClass('hidden text-emerald-600').addClass('text-red-500').html(data.error_msg || 'Terjadi kesalahan. Silakan coba lagi.');
              $msg.show();
              $btn.attr('disabled', false);
            }
          },
          error: function(xhr, status, err) {
            $overlay.hide();
            $btn.html('Kirim Link Verifikasi').attr('disabled', false);
            $msg.removeClass('hidden text-emerald-600').addClass('text-red-500').html('Gagal terhubung ke server. Periksa koneksi internet.');
            $msg.show();
          }
        });
      });
    });
  </script>

</body>
</html>
