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

$data_user = $api_v2->detail_user();
$data_user_res = json_decode($data_user, true);

$is_error = false;
$error_msg = "";
if (!isset($data_user_res['status'])){
    $error_msg = "error call api server";
    $is_error=true;
}

if ($data_user_res['status']==0){
    $error_msg = $data_user_res['error_msg'];
    $is_error=true;
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

$data = $data_user_res['data'];
$poin = $data['my_poin'];
$toko = $data['nama_toko'];
$saldo = $data['saldo'];
$image = $data['image'];
$hp = $data['hp'];
$email = $data['email'];

$poin_list = $api_v2->tukar_poin_list();
$data_poin = json_decode($poin_list, true);
$is_error = false;
if (!isset($data_poin['status'])){
    $error_msg = "error call api server";
    $is_error=true;
}

if ($data_poin['status']==0){
    $error_msg = $data_poin['error_msg'];
    $is_error=true;
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

$list_poin = isset($data_poin['data']) && is_array($data_poin['data']) ? $data_poin['data'] : array();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tukar Poin - BukaKios</title>
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
  <header class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-slate-100">
    <div class="flex items-center gap-3 px-4 py-3">
      <div class="h-1 flex-1 rounded-full bg-slate-100 overflow-hidden">
        <div class="h-full w-full rounded-full bg-brand"></div>
      </div>
      <div class="shrink-0 text-[16px] font-bold tracking-[-0.04em] text-brand">BukaKios</div>
    </div>
  </header>

  <main class="px-4 pt-6 max-w-lg mx-auto">

    <!-- Hero Section -->
    <div class="flex flex-col items-center text-center mb-6">
      <div class="w-24 h-24 rounded-2xl bg-brand/10 flex items-center justify-center mb-4 shadow-soft">
        <svg viewBox="0 0 24 24" class="w-12 h-12 text-brand" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M20 12v10H4V12"/>
          <path d="M2 7h20v5H2z"/>
          <path d="M12 22V7"/>
          <path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/>
          <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/>
        </svg>
      </div>
      <h1 class="text-[22px] font-extrabold tracking-tight text-slate-900">Tukar Poin</h1>
      <p class="text-sm text-slate-500 mt-1">Tukarkan poinmu dengan berbagai hadiah menarik</p>
    </div>

    <!-- User / Poin Card -->
    <div class="bg-white rounded-2xl border border-slate-100 p-4 mb-6 shadow-card">
      <div class="flex items-center gap-3 mb-4">
        <img src="<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>" alt="Foto Toko" class="w-12 h-12 rounded-full border-2 border-brand object-cover shrink-0">
        <div class="min-w-0">
          <h3 class="text-[15px] font-bold text-slate-800 truncate"><?= htmlspecialchars($toko, ENT_QUOTES, 'UTF-8') ?></h3>
          <p class="text-[12px] text-slate-500">Nama Toko</p>
        </div>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div class="rounded-xl bg-slate-50 border border-slate-100 p-3 text-center">
          <p class="text-[11px] font-medium text-slate-500 uppercase tracking-wide">Saldo</p>
          <p class="text-[16px] font-extrabold text-slate-900 mt-0.5"><?= $app->idr($saldo) ?></p>
        </div>
        <div class="rounded-xl bg-brand/10 border border-brand/20 p-3 text-center">
          <p class="text-[11px] font-medium text-brand uppercase tracking-wide">Poin</p>
          <p class="text-[16px] font-extrabold text-brand mt-0.5"><?= $app->angka_id($poin) ?></p>
        </div>
      </div>
    </div>

    <!-- Section title -->
    <div class="flex items-center gap-2 mb-3">
      <div class="w-1 h-5 rounded-full bg-brand"></div>
      <h2 class="text-[15px] font-bold text-slate-800">Pilihan Hadiah</h2>
    </div>

    <!-- Poin List -->
    <div class="space-y-4">
      <?php
        if (empty($list_poin)){
      ?>
        <div class="bg-white rounded-2xl border border-slate-100 p-8 text-center shadow-card">
          <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3">
            <svg viewBox="0 0 24 24" class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 12v10H4V12"/><path d="M2 7h20v5H2z"/><path d="M12 22V7"/>
            </svg>
          </div>
          <p class="text-[14px] font-semibold text-slate-700">Belum ada hadiah</p>
          <p class="text-[12px] text-slate-500 mt-1">Hadiah tukar poin belum tersedia saat ini.</p>
        </div>
      <?php
        }
        foreach ($list_poin as $row){
            $harga = (int) $row['harga'];
            $cukup = $poin >= $harga;
            $persen = $harga > 0 ? min(100, floor(($poin / $harga) * 100)) : 100;
      ?>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-card overflow-hidden">
          <div class="relative">
            <img src="<?= htmlspecialchars($row['gambar'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($row['judul'], ENT_QUOTES, 'UTF-8') ?>" class="w-full h-44 object-cover">
            <span class="absolute top-3 left-3 inline-flex items-center gap-1 rounded-full bg-white/90 backdrop-blur px-2.5 py-1 text-[11px] font-bold text-brand shadow-soft">
              <svg viewBox="0 0 24 24" class="w-3.5 h-3.5" fill="currentColor"><path d="M12 2l2.4 7.4H22l-6 4.6 2.3 7.4L12 17l-6.3 4.4L8 14 2 9.4h7.6z"/></svg>
              <?= $app->angka_id($harga) ?> poin
            </span>
          </div>
          <div class="p-4">
            <h3 class="text-[15px] font-bold text-slate-800"><?= htmlspecialchars($row['judul'], ENT_QUOTES, 'UTF-8') ?></h3>
            <p class="text-[13px] text-slate-500 leading-relaxed mt-1"><?= $row['deskripsi'] ?></p>

            <?php if (!$cukup){ ?>
              <div class="mt-3">
                <div class="h-1.5 w-full rounded-full bg-slate-100 overflow-hidden">
                  <div class="h-full rounded-full bg-brand" style="width: <?= $persen ?>%"></div>
                </div>
                <p class="text-[11px] text-slate-400 mt-1.5">Poinmu <?= $persen ?>% dari yang dibutuhkan</p>
              </div>
            <?php } ?>

            <button
              data-id="<?= (int) $row['id_poin'] ?>"
              data-judul="<?= htmlspecialchars($row['judul'], ENT_QUOTES, 'UTF-8') ?>"
              class="btn-tukar mt-4 w-full rounded-xl py-3 text-[14px] font-bold transition active:scale-[0.98] flex items-center justify-center gap-2 <?= $cukup ? 'bg-brand text-white hover:bg-brandDark shadow-lg' : 'bg-slate-100 text-slate-400 cursor-not-allowed' ?>"
              <?= $cukup ? '' : 'disabled' ?>>
              <?php if ($cukup){ ?>
                <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12v10H4V12"/><path d="M2 7h20v5H2z"/><path d="M12 22V7"/></svg>
                Tukar Poin
              <?php } else { ?>
                <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                Poin belum cukup
              <?php } ?>
            </button>
          </div>
        </div>
      <?php
        }
      ?>
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
      $('.btn-tukar').on('click', function(){
        if (isProcessing) return;
        var id = $(this).attr('data-id');
        if (!id) return;

        isProcessing = true;
        document.getElementById('loading-overlay').style.display = 'flex';

        $.ajax({
          url: "index.php",
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
