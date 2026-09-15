<?PHP
require_once("../../config.php");
require_once('../../_session.php');
require_once('../../lib/ApiV2.php');

$api_v2 = new ApiV2($user_jwt);
$data_user = $api_v2->detail_user();
$data_user_res = json_decode($data_user, true);
$is_error = false;
if (!isset($data_user_res['status'])) {
    $is_error = true;
    $error_title = "Error";
    $error_msg = "error get data user";
}
if ($data_user_res['status'] == 0) {
    $is_error = true;
    $error_title = "Error";
    $error_msg = $data_user_res['error_msg'];
}

$real_user = $data_user_res['data'];

// $lyt_image = "https://assets.bukakios.net/img2/uploads/2020/01/358-reset-pin-min.png";
$lyt_image = "https://assets2.bukakios.net/img2/uploads/2026/06/414-image-removebg-preview.png";
if (isset($_POST['act'], $_POST['csrf'])) {
    require_once('proses_otp_v2.php');
    exit;
} else {
    $csrf = md5(uniqid() . time());
    $_SESSION['csrf'] = $csrf;
}

$nomor_hp = $real_user['hp'];
$count = strlen($nomor_hp) - 9;
$nomor_hp_sensor = substr_replace($nomor_hp, str_repeat('*', $count), 4, $count);
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reset PIN - BukaKios</title>
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
  <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-950 antialiased pb-8">

  <!-- Header -->
  <header class="sticky top-0 z-10 bg-white border-b border-slate-100">
    <div class="flex items-center gap-3 px-4 py-3">
      <div class="h-1 flex-1 rounded-full bg-slate-200 overflow-hidden">
        <div class="h-full w-full rounded-full bg-brand"></div>
      </div>
      <div class="shrink-0 text-[18px] font-extrabold tracking-[-0.04em] text-brand">BukaKios</div>
    </div>
  </header>

  <main class="px-4 pt-6">

    <!-- Hero Section -->
    <div class="flex flex-col items-center text-center mb-6">
      <div class="w-24 h-24 rounded-2xl bg-brand/10 flex items-center justify-center mb-4 shadow-soft">
        <svg viewBox="0 0 24 24" class="w-12 h-12 text-brand" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
          <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
        </svg>
      </div>
      <h1 class="text-[22px] font-extrabold tracking-tight text-slate-900">Reset PIN</h1>
      <p class="text-sm text-slate-500 mt-1">Amankan akunmu dengan PIN baru</p>
    </div>

    <!-- Info Card -->
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6">
      <div class="flex items-start gap-3">
        <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
          <svg viewBox="0 0 24 24" class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
          </svg>
        </div>
        <div>
          <p class="text-[13px] text-amber-800 leading-relaxed">
            Untuk mereset PIN, kami akan mengirimkan kode OTP ke nomor <strong class="font-semibold"><?= $nomor_hp_sensor ?></strong>. Pilih metode pengiriman yang kamu sukai:
          </p>
        </div>
      </div>
    </div>

    <!-- Metode OTP -->
    <div class="space-y-3">
      
      <!-- WhatsApp -->
      <button id="btn-wa" onclick="setProses(this, 'wa.php')" class="w-full bg-white rounded-2xl border border-slate-100 p-4 shadow-card transition hover:shadow-md active:scale-[0.99] text-left">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl bg-[#25D366] flex items-center justify-center shrink-0">
            <svg viewBox="0 0 24 24" class="w-6 h-6 text-white" fill="currentColor">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
          </div>
          <div class="flex-1">
            <h3 class="text-[15px] font-bold text-slate-800">WhatsApp</h3>
            <p class="text-[12px] text-slate-500">Kirim OTP via WhatsApp</p>
          </div>
          <div id="btn-wa-spinner" class="hidden">
            <div style="width:20px;height:20px;border:2px solid rgba(37,211,102,0.3);border-top-color:#25D366;border-radius:50%;animation:spin .8s linear infinite;"></div>
          </div>
        </div>
      </button>

      <!-- SMS -->
      <button id="btn-hp" onclick="setProses(this, 'hp.php')" class="w-full bg-white rounded-2xl border border-slate-100 p-4 shadow-card transition hover:shadow-md active:scale-[0.99] text-left">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl bg-brand flex items-center justify-center shrink-0">
            <svg viewBox="0 0 24 24" class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="5" y="2" width="14" height="20" rx="2" ry="2"/>
              <line x1="12" y1="18" x2="12.01" y2="18"/>
            </svg>
          </div>
          <div class="flex-1">
            <h3 class="text-[15px] font-bold text-slate-800">SMS</h3>
            <p class="text-[12px] text-slate-500">Kirim OTP via SMS</p>
          </div>
          <div id="btn-hp-spinner" class="hidden">
            <div style="width:20px;height:20px;border:2px solid rgba(26,127,206,0.3);border-top-color:#1a7fce;border-radius:50%;animation:spin .8s linear infinite;"></div>
          </div>
        </div>
      </button>

      <!-- Email -->
      <button id="btn-email" onclick="setProses(this, 'email.php')" class="w-full bg-white rounded-2xl border border-slate-100 p-4 shadow-card transition hover:shadow-md active:scale-[0.99] text-left">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl bg-red-500 flex items-center justify-center shrink-0">
            <svg viewBox="0 0 24 24" class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
              <polyline points="22,6 12,13 2,6"/>
            </svg>
          </div>
          <div class="flex-1">
            <h3 class="text-[15px] font-bold text-slate-800">Email</h3>
            <p class="text-[12px] text-slate-500">Kirim OTP via Email</p>
          </div>
          <div id="btn-email-spinner" class="hidden">
            <div style="width:20px;height:20px;border:2px solid rgba(239,68,68,0.3);border-top-color:#ef4444;border-radius:50%;animation:spin .8s linear infinite;"></div>
          </div>
        </div>
      </button>

    </div>

    <!-- Info Pills -->
    <div class="flex flex-wrap gap-2 mt-6">
      <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-[11px] font-medium text-slate-600 shadow-soft">
        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
        Proses Cepat
      </span>
      <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-[11px] font-medium text-slate-600 shadow-soft">
        <span class="w-1.5 h-1.5 rounded-full bg-brand"></span>
        Aman & Terpercaya
      </span>
    </div>

  </main>

  <!-- Loading Overlay -->
  <div id="loading-overlay" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:16px;padding:24px;display:flex;flex-direction:column;align-items:center;gap:12px;box-shadow:0 20px 60px rgba(0,0,0,0.15);min-width:200px;">
      <div style="width:44px;height:44px;border:4px solid rgba(26,127,206,0.2);border-top-color:#1a7fce;border-radius:50%;animation:spin 0.8s linear infinite;"></div>
      <div style="font-size:14px;font-weight:600;color:#334155;">Memproses...</div>
    </div>
  </div>

  <style>
    @keyframes spin { to { transform: rotate(360deg); } }
  </style>

  <script>
    function setProses(btn, url) {
      var spinner = document.getElementById(btn.id + '-spinner');
      if (spinner) spinner.classList.remove('hidden');
      btn.disabled = true;
      btn.style.opacity = '0.7';
      setTimeout(function() {
        window.location.href = url;
      }, 500);
    }
  </script>

</body>
</html>
