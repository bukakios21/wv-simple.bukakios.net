<?php
require_once("../config.php");
require_once("../_session.php");
require_once("../lib/ApiV2.php");

$api_v2 = new ApiV2($user_jwt);

if (isset($_POST['act'], $_POST['csrf'])){
    require_once('_act/proses_tukar_poin.php');exit;
}else{
    $csrf = $app->csrf();
}

$id = isset($_GET['id']) ? abs((int) $_GET['id']) : 0;

// Data user (poin)
$data_user = $api_v2->detail_user();
$data_user_res = json_decode($data_user, true);
$is_error = false;
$error_msg = "";
if (!isset($data_user_res['status'])){
    $error_msg = "error call api server";
    $is_error = true;
} else if ($data_user_res['status'] == 0){
    $error_msg = $data_user_res['error_msg'];
    $is_error = true;
}

if ($is_error){
    $html_title = "Error";
    $lyt_button_link = "opentranslate://10|pulsa";
    $lyt_button_name = "KEMBALI KE DASHBOARD";
    $lyt_image = "https://assets.bukakios.net/img/illustration/bc_logout.png";
    $lyt_title = "Error!";
    $lyt_description = $error_msg;
    require_once(ROOT."/_template/general_message.php");
    exit;
}

$poin = $data_user_res['data']['my_poin'];

// Data hadiah dari list poin
$poin_list = $api_v2->tukar_poin_list();
$data_poin = json_decode($poin_list, true);
$list_poin = isset($data_poin['data']) && is_array($data_poin['data']) ? $data_poin['data'] : array();

$item = null;
foreach ($list_poin as $row){
    if ((int) $row['id_poin'] === $id){
        $item = $row;
        break;
    }
}

if ($item === null){
    $html_title = "Hadiah Tidak Ditemukan";
    $lyt_button_link = "index.php";
    $lyt_button_name = "KEMBALI KE DAFTAR HADIAH";
    $lyt_image = "https://assets.bukakios.net/img/illustration/bc_logout.png";
    $lyt_title = "Hadiah Tidak Ditemukan";
    $lyt_description = "Hadiah yang kamu pilih tidak tersedia. Silakan pilih hadiah lain.";
    require_once(ROOT."/_template/general_message.php");
    exit;
}

$harga = (int) $item['harga'];
$cukup = $poin >= $harga;
$persen = $harga > 0 ? min(100, floor(($poin / $harga) * 100)) : 100;

$syarat    = isset($item['syarat']) ? $item['syarat'] : '';
$cara      = isset($item['cara_pakai']) ? $item['cara_pakai'] : '';
$deskripsi = isset($item['deskripsi']) ? $item['deskripsi'] : '';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($item['judul'], ENT_QUOTES, 'UTF-8') ?> - Tukar Poin</title>
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
<body class="min-h-screen bg-slate-50 font-sans text-slate-950 antialiased pb-28">

  <!-- Header -->
  <header class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-slate-100">
    <div class="flex items-center gap-3 px-4 py-3">
      <div class="h-1 flex-1 rounded-full bg-slate-100 overflow-hidden">
        <div class="h-full w-full rounded-full bg-brand"></div>
      </div>
      <div class="shrink-0 text-[16px] font-bold tracking-[-0.04em] text-brand">BukaKios</div>
    </div>
  </header>

  <main class="max-w-lg mx-auto">

    <!-- Cover -->
    <div class="relative">
      <img src="<?= htmlspecialchars($item['gambar'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($item['judul'], ENT_QUOTES, 'UTF-8') ?>" class="w-full h-56 object-cover">
      <div class="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-black/40 to-transparent"></div>
    </div>

    <div class="px-4 pt-5">

      <!-- Title + Poin -->
      <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-card -mt-12 relative">
        <h1 class="text-[18px] font-extrabold text-slate-900 leading-snug"><?= htmlspecialchars($item['judul'], ENT_QUOTES, 'UTF-8') ?></h1>
        <div class="flex items-center gap-2 mt-3">
          <span class="inline-flex items-center gap-1.5 rounded-full bg-brand/10 px-3 py-1.5 text-[13px] font-bold text-brand">
            <svg viewBox="0 0 24 24" class="w-4 h-4" fill="currentColor"><path d="M12 2l2.4 7.4H22l-6 4.6 2.3 7.4L12 17l-6.3 4.4L8 14 2 9.4h7.6z"/></svg>
            <?= $app->angka_id($harga) ?> poin
          </span>
        </div>

        <!-- Poin user -->
        <div class="mt-4 rounded-xl bg-slate-50 border border-slate-100 p-3">
          <div class="flex items-center justify-between text-[13px]">
            <span class="text-slate-500">Poin kamu saat ini</span>
            <span class="font-bold text-slate-900"><?= $app->angka_id($poin) ?> poin</span>
          </div>
          <?php if (!$cukup){ ?>
            <div class="mt-2 h-1.5 w-full rounded-full bg-slate-200 overflow-hidden">
              <div class="h-full rounded-full bg-brand" style="width: <?= $persen ?>%"></div>
            </div>
            <p class="text-[11px] text-slate-400 mt-1.5">Kurang <?= $app->angka_id($harga - $poin) ?> poin lagi untuk menukarkan hadiah ini</p>
          <?php } ?>
        </div>
      </div>

      <?php if ($deskripsi !== ''){ ?>
      <!-- Deskripsi -->
      <div class="mt-4">
        <div class="flex items-center gap-2 mb-2">
          <div class="w-1 h-5 rounded-full bg-brand"></div>
          <h2 class="text-[15px] font-bold text-slate-800">Deskripsi</h2>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-card text-[13px] leading-relaxed text-slate-600">
          <?= $deskripsi ?>
        </div>
      </div>
      <?php } ?>

      <?php if ($syarat !== ''){ ?>
      <!-- Syarat & Ketentuan -->
      <div class="mt-4">
        <div class="flex items-center gap-2 mb-2">
          <div class="w-1 h-5 rounded-full bg-brand"></div>
          <h2 class="text-[15px] font-bold text-slate-800">Syarat & Ketentuan</h2>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-card text-[13px] leading-relaxed text-slate-600">
          <?= $syarat ?>
        </div>
      </div>
      <?php } ?>

      <?php if ($cara !== ''){ ?>
      <!-- Cara Pakai -->
      <div class="mt-4">
        <div class="flex items-center gap-2 mb-2">
          <div class="w-1 h-5 rounded-full bg-brand"></div>
          <h2 class="text-[15px] font-bold text-slate-800">Cara Pakai</h2>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-card text-[13px] leading-relaxed text-slate-600">
          <?= $cara ?>
        </div>
      </div>
      <?php } ?>

    </div>
  </main>

  <!-- Sticky bottom CTA -->
  <div class="fixed inset-x-0 bottom-0 z-20 border-t border-slate-100 bg-white/90 backdrop-blur-md">
    <div class="max-w-lg mx-auto px-4 py-3">
      <button
        id="btn-tukar"
        data-id="<?= (int) $item['id_poin'] ?>"
        class="w-full rounded-xl py-3.5 text-[15px] font-bold transition active:scale-[0.98] flex items-center justify-center gap-2 <?= $cukup ? 'bg-brand text-white hover:bg-brandDark shadow-lg' : 'bg-slate-100 text-slate-400 cursor-not-allowed' ?>"
        <?= $cukup ? '' : 'disabled' ?>>
        <?php if ($cukup){ ?>
          <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12v10H4V12"/><path d="M2 7h20v5H2z"/><path d="M12 22V7"/></svg>
          Tukarkan Poin
        <?php } else { ?>
          <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          Poin belum cukup
        <?php } ?>
      </button>
    </div>
  </div>

  <!-- Loading Overlay -->
  <div id="loading-overlay" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:16px;padding:24px;display:flex;flex-direction:column;align-items:center;gap:12px;box-shadow:0 20px 60px rgba(0,0,0,0.15);min-width:200px;">
      <div style="width:44px;height:44px;border:4px solid rgba(26,127,206,0.2);border-top-color:#1a7fce;border-radius:50%;animation:spin 0.8s linear infinite;"></div>
      <div style="font-size:14px;font-weight:600;color:#334155;">Memproses penukaran...</div>
    </div>
  </div>

  <style>
    @keyframes spin { to { transform: rotate(360deg); } }
    @keyframes slideDown { from { top: 50px; opacity: 0; } to { top: 80px; opacity: 1; } }
    @keyframes slideUp { from { top: 80px; opacity: 1; } to { top: 50px; opacity: 0; } }
  </style>

  <script>
    var csrf = "<?php echo $csrf ?>";
    var isProcessing = false;

    $(document).ready(function(){
      $('#btn-tukar').on('click', function(){
        if (isProcessing || $(this).is('[disabled]')) return;
        var id = $(this).attr('data-id');
        if (!id) return;

        isProcessing = true;
        document.getElementById('loading-overlay').style.display = 'flex';

        $.ajax({
          url: "detail.php",
          data: { act: "tukar", csrf: csrf, id: id },
          method: 'POST',
          dataType: 'json',
          timeout: 20000,
          success: function(data){
            document.getElementById('loading-overlay').style.display = 'none';
            if (data.status == 1){
              showToast('Penukaran berhasil!', 'success');
              setTimeout(function(){ window.location.href = "success.php"; }, 1200);
            } else {
              isProcessing = false;
              showToast(data.error_msg || 'Penukaran gagal', 'error');
            }
          },
          error: function(){
            document.getElementById('loading-overlay').style.display = 'none';
            isProcessing = false;
            showToast('Koneksi gagal, coba lagi', 'error');
          }
        });
      });
    });

    function showToast(message, type) {
      var bgColor = type === 'success' ? '#10B981' : '#EF4444';
      var toast = document.createElement('div');
      toast.style.cssText = 'position:fixed;top:80px;left:50%;transform:translateX(-50%);background:' + bgColor + ';color:white;padding:12px 24px;border-radius:12px;font-size:14px;font-weight:600;z-index:99999;box-shadow:0 4px 12px rgba(0,0,0,0.15);animation:slideDown 0.3s ease;max-width:90%;text-align:center';
      toast.textContent = message;
      document.body.appendChild(toast);
      setTimeout(function() {
        toast.style.animation = 'slideUp 0.3s ease forwards';
        setTimeout(function() { toast.remove(); }, 300);
      }, 2500);
    }
  </script>
</body>
</html>
