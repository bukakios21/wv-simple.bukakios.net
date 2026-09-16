<?php
require_once("../config.php");
require_once("../_session.php");
require_once("../lib/ApiV2.php");

$api_v2 = new ApiV2($user_jwt);

// AJAX actions (riwayat/mutasi/pencairan/dll) -> delegasikan ke handler _act.
if (isset($_POST['act'], $_POST['csrf'])) {
    require_once('_act/proses_qris.php');
    exit;
} else {
    $csrf = $app->csrf();
}

// -------------------------------------------------------------------------
// Data awal (server-side): detail akun QRIS user.
// -------------------------------------------------------------------------
$detail_res = json_decode($api_v2->qris_detail_user(), true);
// var_dump($detail_res);exit;
$is_error          = false;
$is_not_registered = false;
$error_msg         = "";

if (!isset($detail_res['status'])) {
    $error_msg = "error call api server";
    $is_error  = true;
} elseif ((int) $detail_res['status'] === 0) {
    $error_msg = $detail_res['error_msg'] ?? 'Sesi tidak valid';

    // Hanya response data kosong yang dianggap belum daftar QRIS.
    // Error token/API/server jangan diarahkan ke daftar QRIS karena membingungkan user.
    if (stripos($error_msg, 'Data Not Found') !== false || stripos($error_msg, 'Belum Pernah Daftar') !== false) {
        $is_not_registered = true;
    } else {
        $is_error = true;
    }
}

if ($is_not_registered) {
    $html_title      = "QRIS";
    $lyt_button_link = "opentranslate://14;register-qris";
    $lyt_button_name = "DAFTAR QRIS";
    $lyt_image       = "https://assets.bukakios.net/img/illustration/bc_logout.png";
    $lyt_title       = "Belum Terdaftar QRIS";
    $lyt_description = "Kamu belum memiliki akun QRIS Bukakios. Silakan daftar terlebih dahulu untuk mulai menerima pembayaran QRIS.";
    require_once(ROOT . "/_template/general_message.php");
    exit;
}

if ($is_error) {
    $html_title      = "QRIS";
    $lyt_button_link = "opentranslate://10|home";
    $lyt_button_name = "KEMBALI";
    $lyt_image       = "https://assets.bukakios.net/img/illustration/bc_logout.png";
    $lyt_title       = "Gagal Memuat QRIS";
    $lyt_description = $error_msg ?: "Terjadi kendala saat memuat data QRIS. Silakan coba beberapa saat lagi.";
    require_once(ROOT . "/_template/general_message.php");
    exit;
}

$user = $detail_res['data'] ?? array();

// Status akun QRIS dari data user:
// 0 = pendaftaran direview, 1 = sukses/aktif, 2 = pendaftaran diproses,
// 3 = ditolak, 4 = suspect account.
$qris_status = (int) ($user['status'] ?? 1);
if ($qris_status !== 1) {
    $html_title      = "QRIS";
    $lyt_button_link = "opentranslate://10|home";
    $lyt_button_name = "KEMBALI";
    $lyt_image       = "https://assets.bukakios.net/img/illustration/bc_logout.png";

    if ($qris_status === 0) {
        $lyt_title       = "Pendaftaran QRIS Sedang Direview";
        $lyt_description = "Pendaftaran QRIS kamu sedang dalam proses review. Mohon tunggu hingga proses verifikasi selesai.";
    } elseif ($qris_status === 2) {
        $lyt_title       = "Pendaftaran QRIS Sedang Diproses";
        $lyt_description = "Pendaftaran QRIS kamu sedang diproses. Mohon tunggu hingga proses selesai.";
    } elseif ($qris_status === 3) {
        $lyt_title       = "Pendaftaran QRIS Ditolak";
        $lyt_description = !empty($user['alasan']) ? $user['alasan'] : "Pendaftaran QRIS kamu ditolak. Silakan hubungi CS BukaKios untuk informasi lebih lanjut.";
    } elseif ($qris_status === 4) {
        $lyt_title       = "Akun QRIS Dalam Pemeriksaan";
        $lyt_description = "Akun QRIS kamu sedang dalam pemeriksaan. Silakan hubungi CS BukaKios untuk informasi lebih lanjut.";
    } else {
        $lyt_title       = "Status QRIS Tidak Dikenali";
        $lyt_description = "Status akun QRIS kamu belum dapat diproses. Silakan coba beberapa saat lagi atau hubungi CS BukaKios.";
    }

    require_once(ROOT . "/_template/general_message.php");
    exit;
}

$saldo_real       = (int) ($user['saldo_real'] ?? 0);
$saldo_kliring    = (int) ($user['saldo_kliring'] ?? 0);
$merchant_name    = $user['merchant_name'] ?? '-';
$nmid             = $user['nmid'] ?? '-';
$url_qris         = $user['url_qris'] ?? '';
$status_verif     = $user['status_verif_toko'] ?? '';
$alasan_verif     = $user['alasan_verif_toko'] ?? '';
$admin_pencairan  = (int) ($user['admin_pencairan'] ?? 0);
$is_hold          = !empty($user['is_hold']);

$download_qr_link = "open://https://w4.bukakios.net/qr/" . rawurlencode($nmid);
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>QRIS - BukaKios</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: '#1a7fce',
            brandDark: '#1265a6',
            qris: '#0063F8',
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
  <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
  <style>
    body { font-family: 'Inter', sans-serif; }
    .qris-modal { display: none; }
    .qris-modal.is-open { display: flex; }
    .qris-shimmer {
      position: relative;
      overflow: hidden;
      background: #f1f5f9;
    }
    .qris-shimmer::after {
      content: '';
      position: absolute;
      inset: 0;
      transform: translateX(-100%);
      background: linear-gradient(90deg, transparent, rgba(255,255,255,.75), transparent);
      animation: qris-shimmer 1.25s infinite;
    }
    @keyframes qris-shimmer {
      100% { transform: translateX(100%); }
    }
    #pull-refresh-indicator {
      position: fixed;
      top: 10px;
      left: 50%;
      z-index: 60;
      width: 42px;
      height: 42px;
      border-radius: 9999px;
      background: rgba(255, 255, 255, .96);
      border: 1px solid #e2e8f0;
      box-shadow: 0 10px 28px rgba(15, 23, 42, .14);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #1a7fce;
      opacity: 0;
      transform: translate(-50%, -64px) scale(.88) rotate(0deg);
      transition: opacity .16s ease, transform .16s ease;
      pointer-events: none;
    }
    #pull-refresh-indicator.is-visible {
      opacity: 1;
    }
    #pull-refresh-indicator.is-refreshing svg {
      animation: pull-refresh-spin .8s linear infinite;
    }
    #pull-refresh-shadow {
      position: fixed;
      top: 58px;
      left: 0;
      right: 0;
      height: 56px;
      z-index: 50;
      pointer-events: none;
      opacity: 0;
      background: linear-gradient(to bottom, rgba(15, 23, 42, .18), rgba(15, 23, 42, 0));
      transition: opacity .16s ease;
    }
    #pull-refresh-shadow.is-visible {
      opacity: 1;
    }
    @keyframes pull-refresh-spin {
      to { transform: rotate(360deg); }
    }
  </style>
</head>
<body class="min-h-screen bg-white font-sans text-slate-950 antialiased pb-10 overscroll-y-contain">
  <div id="pull-refresh-indicator" aria-hidden="true">
    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
  </div>
  <div id="pull-refresh-shadow" aria-hidden="true"></div>

  <!-- Header -->
  <header class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-slate-100">
    <div class="flex items-center gap-3 px-4 py-3">
      <div class="h-1 flex-1 rounded-full bg-slate-100 overflow-hidden">
        <div class="h-full w-full rounded-full bg-brand"></div>
      </div>
      <div class="shrink-0 text-[16px] font-bold tracking-[-0.04em] text-brand">BukaKios</div>
    </div>
  </header>

  <main class="mx-auto max-w-lg px-4 py-5">

    <!-- Merchant / Saldo -->
    <div class="mb-4 flex flex-col items-center text-center">
      <div class="inline-flex h-16 w-16 items-center justify-center overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-card text-brand">
        <svg viewBox="0 0 24 24" class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 14h3v3M21 14v.01M17 21v.01M21 17v4h-4"/></svg>
      </div>
      <h1 class="m-0 mt-2.5 text-[17px] font-bold leading-tight text-slate-900"><?= htmlspecialchars($merchant_name, ENT_QUOTES, 'UTF-8') ?></h1>
      <p class="m-0 mt-0.5 text-[14px] font-semibold text-slate-500">NMID <?= htmlspecialchars($nmid, ENT_QUOTES, 'UTF-8') ?></p>
    </div>

    <div class="mb-3.5 flex items-start gap-2.5 rounded-2xl border border-blue-100 bg-blue-50 px-3.5 py-3 text-[13px] text-blue-700">
      <svg viewBox="0 0 24 24" class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M18 13l-6 6-6-6"/></svg>
      <div class="min-w-0 leading-relaxed"><span class="font-bold">Tips:</span> Tarik halaman ke bawah untuk refresh data QRIS terbaru.</div>
    </div>

    <div class="space-y-3.5">
      <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
        <div class="flex items-start justify-between gap-3">
          <div>
            <p class="m-0 text-[17px] font-medium text-slate-500">Stok QRIS</p>
            <p class="m-0 mt-0.5 text-[27px] font-extrabold tracking-tight text-slate-900"><?= $app->idr($saldo_real) ?></p>
          </div>
          <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full border border-brand/20 bg-brand/10 px-3 py-1.5 text-[14px] font-bold text-brand"><svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-5"/></svg>Aktif</span>
        </div>
        <div class="mt-3 grid grid-cols-2 gap-2.5 text-[17px]">
          <div class="rounded-xl border border-slate-200 bg-white px-3 py-2.5">
            <p class="m-0 text-slate-500">Saldo Real</p>
            <p class="m-0 mt-0.5 font-bold text-slate-900"><?= $app->idr($saldo_real) ?></p>
          </div>
          <div class="rounded-xl border border-slate-200 bg-white px-3 py-2.5">
            <p class="m-0 text-slate-500">Saldo Kliring</p>
            <p class="m-0 mt-0.5 font-bold text-slate-900"><?= $app->idr($saldo_kliring) ?></p>
          </div>
        </div>
      </div>

      <!-- Action buttons -->
      <div class="rounded-2xl border border-slate-200 bg-white p-4 text-[17px]">
        <div class="mb-3 flex items-center gap-3">
          <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand/10 text-brand"><svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="2"/><rect x="14" y="3" width="7" height="7" rx="2"/><rect x="3" y="14" width="7" height="7" rx="2"/><rect x="14" y="14" width="7" height="7" rx="2"/></svg></div>
          <div><h3 class="m-0 text-[16px] font-bold leading-tight text-slate-900">Menu QRIS</h3><p class="m-0 mt-0.5 text-[14px] font-medium text-slate-500">Kelola QRIS dan pencairan dana</p></div>
        </div>
        <div class="grid grid-cols-4 gap-1">
        <button class="flex flex-col items-center gap-1.5" data-action="lihat-qris">
          <span class="grid h-11 w-11 place-items-center rounded-xl bg-brand/10 text-brand">
            <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 14h3v3M21 14v.01M17 21v.01M21 17v4h-4"/></svg>
          </span>
          <span class="text-[12px] font-semibold text-slate-600">Lihat QRIS</span>
        </button>
        <button class="flex flex-col items-center gap-1.5" data-action="info-akun">
          <span class="grid h-11 w-11 place-items-center rounded-xl bg-brand/10 text-brand">
            <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21v-1a6 6 0 0 1 6-6h4a6 6 0 0 1 6 6v1"/></svg>
          </span>
          <span class="text-[12px] font-semibold text-slate-600">Info Akun</span>
        </button>
        <button class="flex flex-col items-center gap-1.5" data-action="tarik-dana">
          <span class="grid h-11 w-11 place-items-center rounded-xl bg-brand/10 text-brand">
            <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v14M6 10l6 6 6-6"/><path d="M4 20h16"/></svg>
          </span>
          <span class="text-[12px] font-semibold text-slate-600">Tarik Dana</span>
        </button>
        <button class="flex flex-col items-center gap-1.5" data-action="mutasi">
          <span class="grid h-11 w-11 place-items-center rounded-xl bg-brand/10 text-brand">
            <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h13l-3-3M21 17H8l3 3"/></svg>
          </span>
          <span class="text-[12px] font-semibold text-slate-600">Mutasi Stok</span>
        </button>
        </div>
      </div>

    <?php if ($status_verif === 'Gagal'): ?>
    <div>
      <div class="rounded-2xl bg-red-50 border border-red-200 p-4 text-[17px] text-red-700">
        <p class="font-bold mb-1">Verifikasi Toko kamu Gagal!</p>
        <?php if ($alasan_verif !== ''): ?><p class="mb-1">Alasan: <?= htmlspecialchars($alasan_verif, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        <p>Silakan hubungi CS untuk <strong class="underline">verifikasi ulang</strong>.</p>
      </div>
    </div>
    <?php endif; ?>

    <!-- Tabs riwayat / mutasi / penarikan -->
    <div class="grid grid-cols-3 gap-2 rounded-2xl border border-slate-200 bg-white p-1.5 text-[17px] font-semibold">
        <button data-tab="riwayat"   class="tab-btn rounded-xl py-2.5 transition">Transaksi</button>
        <button data-tab="mutasi"    class="tab-btn rounded-xl py-2.5 transition">Mutasi</button>
        <button data-tab="penarikan" class="tab-btn rounded-xl py-2.5 transition">Penarikan</button>
      </div>
    </div>

    <!-- ============ TAB: RIWAYAT TRANSAKSI ============ -->
    <section id="tab-riwayat" class="tab-panel mt-3.5">
      <div id="riwayat-list" class="space-y-2.5"></div>
      <div id="riwayat-empty" class="hidden bg-white rounded-2xl border border-slate-200 p-8 text-center">
        <p class="text-[17px] font-semibold text-slate-700">Belum ada transaksi</p>
        <p class="text-[17px] text-slate-500 mt-1">Transaksi QRIS masuk akan tampil di sini.</p>
      </div>
      <div id="riwayat-loading" class="hidden space-y-2.5"></div>
      <div id="riwayat-pager" class="hidden mt-4 flex items-center justify-between">
        <button id="riw-prev" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-[17px] font-semibold text-slate-600 disabled:opacity-40 active:scale-[0.98] transition">Sebelumnya</button>
        <span id="riw-page" class="text-[17px] font-semibold text-slate-500">Hal 1</span>
        <button id="riw-next" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-[17px] font-semibold text-slate-600 disabled:opacity-40 active:scale-[0.98] transition">Berikutnya</button>
      </div>
    </section>

    <!-- ============ TAB: MUTASI ============ -->
    <section id="tab-mutasi" class="tab-panel hidden mt-3.5">
      <div class="flex gap-2 mb-3 text-[17px] font-semibold">
        <button data-mutasi="real" class="mutasi-btn flex-1 rounded-xl py-2 border transition">Saldo Real</button>
        <button data-mutasi="kliring" class="mutasi-btn flex-1 rounded-xl py-2 border transition">Saldo Kliring</button>
      </div>
      <div id="mutasi-list" class="space-y-2.5"></div>
      <div id="mutasi-empty" class="hidden bg-white rounded-2xl border border-slate-200 p-8 text-center">
        <p class="text-[17px] font-semibold text-slate-700">Belum ada mutasi</p>
        <p class="text-[17px] text-slate-500 mt-1">Mutasi saldo akan tampil di sini.</p>
      </div>
      <div id="mutasi-loading" class="hidden space-y-2.5"></div>
      <div id="mutasi-pager" class="hidden mt-4 flex items-center justify-between">
        <button id="mut-prev" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-[17px] font-semibold text-slate-600 disabled:opacity-40 active:scale-[0.98] transition">Sebelumnya</button>
        <span id="mut-page" class="text-[17px] font-semibold text-slate-500">Hal 1</span>
        <button id="mut-next" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-[17px] font-semibold text-slate-600 disabled:opacity-40 active:scale-[0.98] transition">Berikutnya</button>
      </div>
    </section>

    <!-- ============ TAB: PENARIKAN ============ -->
    <section id="tab-penarikan" class="tab-panel hidden mt-3.5">
      <div id="penarikan-list" class="space-y-2.5"></div>
      <div id="penarikan-empty" class="hidden bg-white rounded-2xl border border-slate-200 p-8 text-center">
        <p class="text-[17px] font-semibold text-slate-700">Belum ada penarikan</p>
        <p class="text-[17px] text-slate-500 mt-1">Riwayat tarik dana kamu akan tampil di sini.</p>
      </div>
      <div id="penarikan-loading" class="hidden space-y-2.5"></div>
      <div id="penarikan-pager" class="hidden mt-4 flex items-center justify-between">
        <button id="pen-prev" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-[17px] font-semibold text-slate-600 disabled:opacity-40 active:scale-[0.98] transition">Sebelumnya</button>
        <span id="pen-page" class="text-[17px] font-semibold text-slate-500">Hal 1</span>
        <button id="pen-next" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-[17px] font-semibold text-slate-600 disabled:opacity-40 active:scale-[0.98] transition">Berikutnya</button>
      </div>
    </section>

    <!-- Terms -->
    <div class="mt-3.5">
      <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4 text-center">
        <p class="text-[17px] text-slate-500">
          Dengan menggunakan layanan QRIS BukaKios, Anda setuju dengan
          <a href="https://bukakios.id/syarat-ketentuan-qris" target="_blank" class="text-brand underline">Syarat dan Ketentuan</a> yang berlaku.
        </p>
      </div>
    </div>

    <!-- Powered By -->
    <div class="flex flex-col items-center justify-center my-6">
      <p class="text-xs text-slate-400 mb-1 ">Powered By</p>
      <svg width="145" height="60" viewBox="0 0 145 60" xmlns="http://www.w3.org/2000/svg">
        <path
          d="M22.4086 23.7286C22.4086 23.7286 22.3559 23.7113 22.391 23.7631C22.4875 23.849 22.612 23.8981 22.742 23.9013C23.1 23.8812 23.4383 23.7332 23.6934 23.4852C23.9486 23.2371 24.1032 22.906 24.1282 22.5538C24.2686 21.3791 22.7069 19.9452 21.8295 19.2542C20.9521 18.5631 19.25 17.4057 18.864 16.3173C18.864 16.3173 18.8464 16.2309 18.8113 16.3691C18.7762 16.5073 18.5832 16.9219 18.2322 17.5784C17.8813 18.2349 17.9866 19.116 18.162 19.5651C18.3375 20.0143 18.7587 20.8781 18.8464 21.0508C18.9341 21.2236 18.9692 21.4827 19.3553 21.5864C19.5707 21.6169 19.7902 21.6052 20.001 21.5518C20.2118 21.4984 20.4098 21.4045 20.5836 21.2754C20.7112 21.1771 20.8139 21.0511 20.8839 20.9073C20.9539 20.7635 20.9893 20.6057 20.9872 20.4462C21.0223 19.4442 20.0572 19.5306 20.1098 19.8588C20.1625 20.1871 20.2853 19.9279 20.18 20.0834C20.139 20.1576 20.0705 20.2133 19.9888 20.2391C19.9071 20.2648 19.8185 20.2586 19.7413 20.2216C19.5659 20.1871 19.1623 19.617 20.0045 19.2887C20.8468 18.9605 21.812 19.7206 21.6189 20.7744C21.4259 21.8283 20.0221 22.2083 19.3026 22.0701C19.3026 22.0701 19.2149 22.0528 19.2149 22.0874C19.2356 22.2854 19.1927 22.4848 19.0921 22.6575C18.9166 22.8993 18.1971 24.9033 19.443 26.9937C19.5668 27.1205 19.7175 27.2188 19.8843 27.2815C20.0512 27.3442 20.2301 27.3698 20.4081 27.3565C21.0399 27.3392 21.7242 26.4581 21.2504 25.8535C21.2153 25.8189 21.1627 25.7671 21.1627 25.8708C21.1627 25.9744 21.1802 26.2508 20.7942 26.0781C20.4081 25.9053 20.3906 25.1625 21.0223 24.9724C21.654 24.7824 22.3208 25.0415 22.0752 26.2854C21.8295 27.5292 20.5836 27.9611 20.2678 27.9439C19.9519 27.9266 20.0747 27.8748 20.0923 27.9784C20.1098 28.0821 20.4783 29.0495 20.2502 29.6196C20.2502 29.6196 20.1976 29.7578 20.4432 29.6196C20.6889 29.4814 21.3557 29.1532 22.0401 28.8768C22.7244 28.6003 24.5494 28.203 25.8655 26.4754C27.1815 24.7478 27.8659 22.1738 25.9707 19.3233C25.6724 18.8741 25.3566 18.0967 23.7422 17.5093C23.7422 17.5093 23.3561 17.3884 23.5843 17.2502C23.6589 17.2025 23.7439 17.1729 23.8324 17.1639C23.9208 17.1549 24.0102 17.1667 24.0931 17.1984C24.0931 17.1984 25.883 17.5266 26.9008 19.8761C27.9185 22.2256 28.3397 23.2794 29.7259 24.005C31.1122 24.7306 33.797 25.5771 34.2883 26.5273C34.2883 26.5273 34.4638 26.8209 34.534 26.4582C34.6042 26.0954 34.6568 25.698 34.3059 24.9724C33.9549 24.2469 34.0075 24.1432 34.3059 24.1086C34.6042 24.0741 34.6042 24.0914 34.6393 23.9186C34.6744 23.7459 34.341 21.6037 30.5156 21.6382C30.5156 21.6382 30.112 21.6728 30.2173 21.5C30.3225 21.3273 30.6033 21.2754 31.0069 21.2754C31.4105 21.2754 33.3758 21.5 33.99 22.001C33.99 22.001 34.2357 22.191 34.0602 21.7937C33.8847 21.3964 32.9021 19.7033 29.6908 18.9778C26.4796 18.2522 26.4796 16.11 26.4796 16.11C26.4796 16.11 26.5849 15.7126 26.813 16.2482C27.7957 18.4768 31.5509 18.8395 31.6562 18.8568C31.7615 18.8741 32.3054 19.1505 32.4985 18.995C32.9547 18.5804 32.9898 17.7512 32.7792 17.3884C32.7792 17.3884 32.7441 17.2329 32.6564 17.3193C32.5318 17.4199 32.3889 17.4961 32.2352 17.5439C32.2001 17.5439 32.1826 17.5439 32.1826 17.492C32.084 17.3088 32.0187 17.11 31.9896 16.9047C31.9896 16.9047 31.9896 16.8356 31.9545 16.9219C31.8342 17.1836 31.6872 17.4325 31.5158 17.6648C31.506 17.6782 31.4926 17.6886 31.477 17.6947C31.4615 17.7008 31.4445 17.7024 31.428 17.6994C31.428 17.6994 29.919 17.613 29.7084 16.4555C29.4978 15.298 30.0593 14.4688 31.4105 14.6588C31.5365 14.6963 31.6596 14.7425 31.779 14.797C31.8717 14.9921 31.948 15.1943 32.0071 15.4017C32.0071 15.4017 32.0071 15.4362 32.0247 15.419L32.3581 15.0734C32.3581 15.0734 32.3932 15.0389 32.4283 15.0562L33.1126 15.3153H33.2179C33.4215 15.1243 33.5621 14.8776 33.6215 14.607C33.7268 14.106 33.8321 13.5186 33.7443 13.4841C33.6566 13.4495 33.1653 13.1386 33.0424 13.0349C32.9196 12.9312 32.9898 12.9485 33.06 12.9658C33.1302 12.9831 33.2004 12.9485 33.8672 13.1213C33.8672 13.1213 33.99 13.1731 33.9549 13.1213L33.9198 13.0522C33.6681 12.7168 33.3195 12.4639 32.9196 12.3266C32.0071 11.9811 31.8141 11.9465 31.3052 11.9984C31.3052 11.9984 31.1999 12.0156 31.1824 12.0502C31.1648 12.0847 30.9016 13.104 29.8663 13.1558C29.6355 13.1412 29.4148 13.0567 29.2346 12.914C28.8485 12.6548 28.8135 12.6721 28.2168 12.5685C27.6202 12.4648 26.2866 12.2402 25.6373 11.2209C24.9881 10.2017 24.6898 9.73524 23.2684 10.4435C21.847 11.1518 21.2504 11.1 20.1625 10.1499C20.1625 10.1499 20.1274 10.1153 20.1274 10.1499C20.1274 10.1844 20.2151 10.3226 20.0572 10.9618C20.0043 11.1638 19.9957 11.3746 20.032 11.5802C20.0683 11.7857 20.1487 11.9813 20.2678 12.1538C20.3906 12.3439 20.373 12.8794 21.1627 13.1558C21.9523 13.4322 22.2506 13.0003 22.935 13.415L23.0578 13.4668C23.0754 13.4668 23.1631 13.5186 23.0578 13.5186C23.0289 13.5274 22.9985 13.5303 22.9683 13.5274C22.9382 13.5244 22.909 13.5155 22.8824 13.5013C22.7195 13.4194 22.5388 13.3779 22.3559 13.3804C22.1278 13.3977 21.7593 13.5186 21.5487 13.5359C21.3382 13.5532 20.6714 13.5359 20.3906 13.1213C20.3906 13.1213 20.373 13.0867 20.373 13.1386C20.373 13.1904 20.3029 13.6568 19.6185 14.6761C18.9341 15.6954 19.5483 16.7319 21.0925 17.9585C22.6367 19.1851 24.4441 20.5844 24.6898 21.8801C24.9354 23.1758 24.058 24.3678 23.0227 24.4542C21.9874 24.5405 21.4259 23.4694 22.1805 23.1067C22.935 22.7439 23.0754 24.1086 22.4086 23.7459"
          fill="#9CA3AF"></path>
        <path
          d="M31.3402 11.3766C31.3402 11.3766 31.3578 11.342 31.2876 11.2038C31.1763 11.0187 31.0194 10.8642 30.8313 10.7546C30.4979 10.5646 29.7609 10.5819 29.2345 10.6337C28.7081 10.6855 28.322 10.53 27.9535 10.4264C27.585 10.3227 26.5848 10.029 25.9355 10.6855C25.9355 10.6855 25.8654 10.7719 25.9005 10.841C26.0433 11.0781 26.2274 11.2887 26.4444 11.4629C26.8129 11.7221 27.585 12.2231 28.4098 12.033C28.5576 11.9658 28.6987 11.8848 28.8309 11.7912C29.0826 11.6231 29.3713 11.5165 29.6732 11.4802C29.9089 11.4801 30.1441 11.5033 30.3751 11.5493L30.7085 11.6184C30.7085 11.6184 31.2349 11.7048 31.3402 11.3766Z"
          fill="#9CA3AF"></path>
        <path
          d="M18.6182 54.1684H18.6006C18.6006 54.1684 19.7237 53.0282 21.0573 54.2202C21.0573 54.2202 21.0749 54.2548 21.0749 54.2202C21.0453 53.9819 20.9582 53.754 20.8208 53.5556C20.6834 53.3572 20.4998 53.1941 20.2852 53.08C20.1953 53.0483 20.0995 53.0365 20.0045 53.0455H19.9694C19.6755 52.8857 19.3345 52.8307 19.0042 52.89C18.6157 52.9602 18.2419 53.0946 17.8987 53.2873C17.4776 53.5637 17.1442 53.4774 16.9336 53.3219C16.7231 53.1664 14.7928 50.6787 14.7402 47.9664C14.7668 47.9038 14.8075 47.848 14.8591 47.8031C14.9108 47.7583 14.9721 47.7255 15.0385 47.7073C15.0385 47.7073 18.2322 46.1179 19.478 44.874C20.7239 43.6302 21.7241 40.0887 22.9875 38.7757C22.9875 38.7757 23.5491 38.0674 24.1106 37.8947C24.6721 37.7219 27.3042 36.9272 28.0061 36.478C28.708 36.0289 29.9539 34.6123 30.7436 34.4913C30.8088 34.4632 30.8815 34.4565 30.951 34.472C31.0205 34.4876 31.0831 34.5246 31.1296 34.5777L34.0951 37.2727C34.0951 37.2727 34.5163 37.7392 34.9725 37.7737C35.4288 37.8083 36.1482 38.0329 36.2009 38.482C36.2535 38.9312 35.885 40.175 36.6395 40.6415C36.6395 40.6415 36.7975 40.8661 36.3763 41.177C36.3763 41.177 36.271 41.2289 36.3763 41.2289C36.4816 41.2289 38.7102 41.3152 38.1837 39.4149C38.1837 39.4149 38.1311 39.2594 38.2013 39.294C38.5944 39.5871 38.8881 39.9907 39.0436 40.4515C39.3243 41.2634 39.7279 41.3152 39.7279 41.3152C39.7622 41.3306 39.7917 41.3547 39.8133 41.3851C39.8349 41.4155 39.8478 41.451 39.8507 41.488C39.8507 41.488 39.9209 41.7471 39.5524 42.179C39.5524 42.179 39.4823 42.2309 39.5524 42.2136C39.6226 42.1963 40.5702 42.1963 40.5877 41.6089C40.5831 41.6089 40.5786 41.6071 40.5753 41.6039C40.5721 41.6006 40.5702 41.5962 40.5702 41.5917C40.5702 41.5871 40.5721 41.5827 40.5753 41.5794C40.5786 41.5762 40.5831 41.5744 40.5877 41.5744C40.5877 41.5744 41.1142 41.4362 41.2897 41.1425C41.4651 40.8488 41.8336 39.3804 40.0438 38.3265C40.0438 38.3265 39.9911 38.3093 40.0262 38.3093C40.308 38.3096 40.5846 38.3832 40.8281 38.5228C41.0715 38.6624 41.2732 38.8629 41.4125 39.104C41.9214 39.9677 42.2723 38.9485 42.4127 39.9505C42.4127 39.9505 42.4127 40.0023 42.4478 39.985C42.4829 39.9677 43.0795 39.6568 42.6057 38.8448C42.6005 38.8302 42.5994 38.8145 42.6025 38.7993C42.6056 38.7841 42.6127 38.77 42.6233 38.7584C42.6584 38.7239 43.097 37.2209 40.8159 36.9445C40.8159 36.9445 40.7457 36.9445 40.7632 36.9272C40.7808 36.9099 41.0089 36.6681 41.6231 36.8581C42.2372 37.0481 42.5531 36.9272 42.5706 36.6508C42.5706 36.6508 42.8514 36.6508 43.1672 37.0136C43.1672 37.0136 43.1848 37.0309 43.1848 37.0136C43.1848 36.9963 43.255 35.096 39.9911 35.6143C39.7383 35.7018 39.4678 35.7286 39.2023 35.6926C38.9368 35.6565 38.6839 35.5586 38.4645 35.407C37.6749 34.8887 33.8144 31.1744 33.8144 31.1744C33.8144 31.1744 33.5863 30.8462 33.4985 30.6907C33.339 30.192 33.2101 29.6843 33.1125 29.1704C33.1125 29.1704 33.13 29.1359 33.0598 29.0668C32.9897 28.9977 32.4983 28.583 32.2527 28.4276C32.007 28.2721 32.2351 28.4103 32.2351 28.4448C32.2351 28.4794 31.9894 30.4661 29.1467 32.1246C29.1467 32.1246 28.8309 32.2628 28.8309 32.0036C28.8309 31.7445 28.2167 28.7213 26.3742 29.7232C24.5317 30.7252 23.1806 31.3299 22.8823 31.1917C22.5839 31.0535 22.7594 31.0362 22.8998 30.9325C23.0402 30.8289 23.6894 29.8787 23.4789 28.8076C23.4789 28.8076 23.4789 28.7731 23.4438 28.7904L23.0226 28.9458C22.0754 29.2682 21.164 29.685 20.3028 30.1897L19.9343 30.4143C19.093 31.0857 18.1881 31.6758 17.2319 32.1764C15.8106 32.8847 13.4592 34.1113 12.8801 35.8907C12.3011 37.6701 10.2129 37.9638 9.07232 36.6163C7.93173 35.2687 9.07232 32.7983 14.1611 29.2222C14.1611 29.2222 15.6702 28.2548 16.2317 26.7691C16.7933 25.2834 16.4598 24.8169 16.337 24.1777C16.2142 23.5385 16.688 22.813 16.8283 22.6402C16.9687 22.4674 16.881 21.8973 16.8283 22.0356C16.7827 22.1538 16.7042 22.257 16.6018 22.3334C16.4994 22.4098 16.3772 22.4562 16.2493 22.4674C15.7755 22.5365 15.7053 22.6229 15.6878 22.6748C15.6702 22.7266 15.6702 22.6748 15.6702 22.6229C15.6702 22.5711 15.6527 22.2774 16.1089 21.9664C16.5651 21.6555 16.6002 20.9472 16.5476 20.5326C16.4949 20.1179 16.6353 19.8934 16.7055 19.8243H16.6704C16.4661 19.88 16.2906 20.0095 16.1791 20.187C16.1791 20.187 16.144 20.2043 16.144 20.1525C16.144 20.1007 15.8457 18.5459 16.6529 17.7512C16.7757 17.6302 16.9863 16.8701 16.3721 16.11C15.7579 15.3499 15.7404 15.0043 15.7404 15.0043C15.7404 15.0043 15.7404 14.9871 15.7053 15.0043C15.6702 15.0216 15.6351 15.0216 15.6527 15.2807C15.6702 15.5399 15.5123 15.8508 15.4947 15.9545C15.4772 16.0582 15.4245 16.4209 15.5298 16.611C15.6351 16.801 15.5298 16.6455 15.5123 16.6283C15.4947 16.611 15.056 15.9199 15.1613 15.2807C15.2666 14.6415 15.7053 13.6223 15.1964 13.104C15.1964 13.104 15.1262 13.0349 15.1438 13.1558C15.1493 13.5097 15.0839 13.8612 14.9515 14.1902C14.819 14.5193 14.622 14.8194 14.3717 15.0734C13.4943 15.9718 14.4243 17.2675 14.8104 17.7512C15.1964 18.2349 14.8279 19.0123 14.8981 19.2714C14.9683 19.5306 14.8981 19.3405 14.8806 19.2887C14.8418 19.183 14.8182 19.0726 14.8104 18.9605C14.8104 18.8568 14.7226 18.1658 14.3541 18.0621C14.3541 18.0621 14.3366 18.0621 14.3541 18.0794C14.3653 18.3244 14.3357 18.5695 14.2664 18.805C14.2045 19.0418 14.2058 19.2904 14.2704 19.5265C14.335 19.7627 14.4606 19.9783 14.6349 20.1525C15.0911 20.6362 14.7577 21.9319 14.4945 22.0528C14.4945 22.0528 14.4419 22.0701 14.4419 22.0183C14.446 21.8928 14.4759 21.7694 14.5296 21.6555C14.6349 21.5 14.6524 20.8954 14.5472 20.7917C14.5472 20.7871 14.5453 20.7827 14.542 20.7795C14.5387 20.7762 14.5343 20.7744 14.5296 20.7744C14.525 20.7744 14.5205 20.7762 14.5172 20.7795C14.5139 20.7827 14.5121 20.7871 14.5121 20.7917C14.5121 20.7917 14.4945 21.189 13.8453 21.6209C13.582 21.7937 13.2662 22.7439 14.126 23.6076L14.5472 24.0914C14.5472 24.0914 14.5823 24.1432 14.5472 24.1259C14.5121 24.1086 13.933 24.0741 13.8102 23.694H13.7751C13.7467 23.9369 13.8096 24.1817 13.9518 24.3821C14.094 24.5826 14.3057 24.725 14.5472 24.7824C15.1964 24.9379 15.7053 26.8036 12.4765 28.583C11.8165 28.9454 11.1885 29.3615 10.599 29.8269C10.3884 29.7405 9.9497 29.4814 9.38818 28.6349C9.38818 28.6349 9.30044 28.4621 9.23025 28.2894C9.16006 28.1166 8.82665 27.7711 8.24759 27.6847C8.24759 27.6847 8.23004 27.702 8.26513 27.702C8.30023 27.702 8.75646 27.892 8.75646 28.704C8.75646 29.5159 9.28289 30.2415 9.79177 30.4488H9.77422C9.77422 30.4488 9.84441 30.4661 9.72158 30.587H9.73913L9.17761 31.1571H9.16006C8.90764 30.8948 8.69489 30.5981 8.52834 30.2761C8.52834 30.2761 8.45816 30.1206 8.38797 29.9305C8.31778 29.7405 8.01947 29.395 7.45794 29.2741C7.45794 29.2741 7.4404 29.2741 7.47549 29.2914C7.51059 29.3086 7.94928 29.4987 7.89663 30.3106C7.88338 30.6028 7.93104 30.8945 8.03667 31.1679C8.14229 31.4412 8.30365 31.6904 8.5108 31.9C8.5108 31.9 8.52835 31.9345 8.5108 31.9518C8.35287 32.1591 8.21249 32.3664 8.07211 32.591H8.05456C7.84721 32.3076 7.67629 32.0001 7.54568 31.6754C7.54568 31.6754 7.49304 31.5026 7.45794 31.3299C7.42285 31.1571 7.14209 30.7425 6.58057 30.5525L6.61566 30.587C6.65076 30.6043 7.05435 30.8462 6.91397 31.6409C6.77359 32.4355 7.14209 33.1784 7.56323 33.5066C7.56323 33.5066 7.58078 33.5066 7.56323 33.5412H7.58078C6.98416 34.9232 6.93152 36.3571 7.84399 37.6701C9.65139 40.2787 12.5116 38.7584 12.5116 38.7584C12.5116 38.7584 12.6871 38.5857 12.6696 38.7412C12.652 38.8966 12.9328 40.9179 13.4592 42.1445C13.9856 43.3711 13.3013 44.2694 12.073 44.4249C10.8446 44.5804 9.68648 45.185 10.1076 46.6362C10.1954 46.9817 10.4059 47.5863 10.6691 48.2774C10.6867 48.2946 10.6691 48.2946 10.6691 48.2946C10.3536 48.3226 10.0359 48.3168 9.72158 48.2774C9.59552 48.2641 9.47169 48.2351 9.35308 48.191C9.17761 48.1392 8.68627 48.191 8.24759 48.571C8.24759 48.571 8.24758 48.5883 8.28268 48.571C8.31777 48.5538 8.73892 48.2946 9.37063 48.8129C9.83378 49.1529 10.4107 49.3073 10.985 49.2448C11.0029 49.2454 11.0203 49.2504 11.0357 49.2595C11.0511 49.2686 11.0638 49.2814 11.0727 49.2966C11.1429 49.4867 11.2131 49.6767 11.3009 49.8667C11.0292 49.8455 10.7594 49.8051 10.4937 49.7458C10.3784 49.7101 10.2668 49.6638 10.1603 49.6076C9.96098 49.5624 9.75408 49.5607 9.55403 49.6024C9.35399 49.6442 9.16563 49.7285 9.00213 49.8494C9.00213 49.8494 9.00213 49.884 9.05477 49.8494C9.10741 49.8149 9.52856 49.6594 10.0901 50.2295C10.5133 50.6326 11.0805 50.856 11.6694 50.8514H11.6869L11.8273 51.197C11.4893 51.1929 11.1532 51.1464 10.8271 51.0588L10.4761 50.9205C10.2797 50.8744 10.0753 50.8722 9.87795 50.914C9.68056 50.9559 9.49522 51.0408 9.33553 51.1624C9.33553 51.1624 9.33553 51.1797 9.37063 51.1624C9.40572 51.1451 9.84441 50.9551 10.4059 51.5425C10.6361 51.768 10.915 51.9396 11.2219 52.0442C11.5287 52.1489 11.8557 52.1841 12.1782 52.1471C12.1891 52.1484 12.1996 52.1522 12.2087 52.1582C12.2179 52.1642 12.2255 52.1723 12.2309 52.1817C12.5994 53.0627 12.845 53.7019 12.845 53.7019C12.845 53.7019 13.0381 54.7039 12.9503 55.1185C12.8626 55.5332 13.1083 56.3624 13.74 56.6388C14.3717 56.9152 14.8981 57.7099 14.8981 57.8654C14.8981 58.0209 14.8806 58.9365 16.0387 59.2993C16.0387 59.2993 16.1264 59.2647 16.144 59.3856C16.1417 59.5818 16.0999 59.7756 16.0212 59.9557C16.0212 59.9557 15.9861 60.0248 16.0563 59.9903C16.2382 59.9448 16.4072 59.8589 16.5501 59.7391C16.6931 59.6193 16.8063 59.4688 16.881 59.2993C16.881 59.2993 16.8634 59.282 16.8985 59.2647C16.9336 59.2474 17.3372 58.7983 17.4074 58.3837C17.4776 57.969 17.3372 57.4162 16.5651 56.9152C16.5651 56.9152 16.4774 56.8807 16.5476 56.8979C16.7278 56.9078 16.9001 56.9744 17.0389 57.088L17.8812 57.8136C18.0659 58.0061 18.2939 58.1532 18.5468 58.2432C18.7998 58.3332 19.0706 58.3636 19.3376 58.3318C19.3525 58.3267 19.3685 58.3256 19.3839 58.3286C19.3994 58.3317 19.4137 58.3387 19.4254 58.3491C19.4429 58.3664 19.6184 58.5391 19.5482 59.2302C19.5482 59.2302 19.5307 59.2993 19.5658 59.2993C19.6009 59.2993 20.408 59.0056 20.1799 57.9863C20.1799 57.9863 20.1624 57.969 20.1975 57.9345C20.2326 57.8999 20.5133 57.3298 20.4431 56.9325C20.373 56.5352 20.1097 55.8268 18.6708 55.5332C18.6708 55.5332 18.5831 55.5332 18.6357 55.5159C18.6884 55.4986 19.0218 55.2913 19.8465 55.6023C19.8966 55.6094 19.9445 55.6271 19.9869 55.6541C20.0779 55.8087 20.2192 55.9286 20.388 55.9944C20.5567 56.0602 20.7431 56.0682 20.9169 56.0169C20.9169 56.0169 20.9169 55.9996 20.9345 56.0169C20.952 56.0342 21.4258 56.0687 21.5311 56.7425C21.5311 56.7425 22.1277 56.1205 21.3205 55.274C21.3205 55.274 20.759 53.5465 18.6182 54.1684Z"
          fill="#9CA3AF"></path>
        <path
          d="M28.8836 12.1884C28.8661 12.1884 28.831 12.2057 28.8485 12.2229C29.4627 12.5509 30.34 13.3104 30.6734 12.292C30.691 12.2747 30.6734 12.2747 30.6734 12.2747C30.2523 11.7569 29.3749 12.0503 28.8836 12.1884Z"
          fill="#9CA3AF"></path>
        <path
          d="M25.1107 0.00918479C19.9526 0.133823 14.9516 1.78159 10.7564 4.73873C6.56121 7.69587 3.36544 11.8259 1.58365 16.5931C-0.198131 21.3603 -0.483696 26.5446 0.764004 31.4735C2.0117 36.4025 4.73509 40.8485 8.58086 44.235L8.73879 44.3732H8.75634L8.86163 44.2177C9.18297 43.8021 9.65405 43.5239 10.1777 43.4403L10.6339 43.4057L10.283 43.1293L9.4056 42.3519C5.99568 39.1643 3.62236 35.0528 2.58571 30.5373C1.54905 26.0219 1.89562 21.3052 3.58159 16.9836C5.26757 12.662 8.21723 8.92966 12.0577 6.25838C15.8981 3.58709 20.4568 2.09686 25.1574 1.97609C29.858 1.85532 34.4895 3.10945 38.4662 5.5799C42.4429 8.05035 45.5863 11.6262 47.4989 15.8553C49.4116 20.0844 50.0075 24.7769 49.2115 29.3394C48.4155 33.902 46.2632 38.1297 43.0268 41.4881C40.2016 44.425 36.8149 46.5845 32.7615 47.7765L32.586 47.8283L32.6386 48.0011C32.8874 48.5537 33.2886 49.0265 33.7968 49.3658L33.8845 49.4177H33.9196C33.9196 49.4004 34.0073 49.3658 34.0073 49.3658C44.6061 46.0662 51.7655 36.3573 51.5549 24.8862V24.6962C51.4662 21.3654 50.7117 18.0844 49.3346 15.0409C47.9574 11.9973 45.9845 9.25069 43.5287 6.95806C41.0729 4.66544 38.1823 2.87171 35.022 1.67939C31.8618 0.487068 28.4938 -0.0804778 25.1107 0.00918479Z"
          fill="#9CA3AF"></path>
        <path
          d="M27.4094 29.067C27.4094 29.067 28.9711 28.9979 29.3572 31.4337C29.3572 31.4337 29.3747 31.5546 29.4098 31.5201C29.4449 31.4855 31.6734 29.8098 31.7436 28.1687C31.7436 28.1687 31.7436 28.065 31.691 28.0305C31.0001 27.5943 30.2323 27.2896 29.4274 27.1321C29.4274 27.1321 29.3923 27.0976 29.3572 27.1494C29.3221 27.2012 28.7957 28.2032 27.3743 29.0152C27.3743 29.0152 27.269 29.067 27.4094 29.067Z"
          fill="#9CA3AF"></path>
        <path
          d="M23.9523 30.4317C27.339 28.9805 28.6375 27.5985 29.0762 26.6656C29.1843 26.4692 29.2668 26.2603 29.3219 26.0437C29.3541 25.8554 29.3658 25.6643 29.357 25.4736C29.3394 25.0417 29.3219 24.748 29.1815 24.4716C29.0074 24.2312 28.794 24.021 28.5498 23.8496C28.2515 23.5849 27.9702 23.3022 27.7075 23.0031C27.69 22.9513 27.69 23.0377 27.69 23.0377C27.883 26.9938 24.0225 28.4795 24.0225 28.4795L23.9874 28.5659C24.2156 29.8961 23.7593 30.3799 23.7769 30.449C23.7944 30.5181 23.9523 30.4317 23.9523 30.4317Z"
          fill="#9CA3AF"></path>
        <path
          d="M34.5339 22.0877C34.5339 22.0877 34.2707 22.0013 34.3935 22.2086C34.5163 22.4159 35.4288 23.6943 35.3937 24.0744C35.3586 24.4544 35.3937 24.4199 35.2182 24.4026C35.0428 24.3853 34.6041 24.3335 34.8848 24.9209C35.1656 25.5083 35.499 26.0438 35.2182 26.873C35.2182 26.873 35.2007 26.9421 35.148 26.9249C35.0954 26.9076 34.9901 26.7521 34.762 27.0112C34.5339 27.2704 34.4461 28.0651 34.5339 28.9116C34.5437 28.9664 34.5354 29.0229 34.5101 29.0726C34.4848 29.1224 34.4439 29.1629 34.3935 29.188C34.2005 29.2744 34.3409 29.5335 34.6041 29.4471L36.1483 28.9461C36.1483 28.9461 36.8502 28.8252 37.4819 27.1667C38.1136 25.5082 40.9914 21.9322 41.202 21.8112C41.4125 21.6903 41.9741 21.4485 42.132 21.2757C42.2899 21.1029 42.518 20.1873 42.7462 19.8073H42.7988C42.7988 19.8073 43.6762 20.2392 44.2904 19.5136H44.3079C44.498 19.6009 44.6482 19.7549 44.729 19.9455C44.7873 19.7812 44.8034 19.6054 44.7758 19.4336C44.7483 19.2617 44.6779 19.0993 44.5711 18.9607C44.5711 18.9607 44.5536 18.9607 44.5536 18.9262C44.5536 18.8916 44.536 17.596 42.6058 18.1315H42.5882C42.7801 17.9842 43.0013 17.8783 43.2375 17.8205C43.7288 17.6478 43.799 17.7342 44.15 17.2332C44.15 17.2332 44.3781 16.9913 44.7992 17.6996H44.8168C44.8907 17.4907 44.8949 17.264 44.8285 17.0526C44.7622 16.8413 44.629 16.6563 44.4483 16.5249C44.4483 16.5249 44.4307 16.5249 44.4307 16.4903C44.4307 16.4558 44.3605 15.3674 43.0094 15.5574C42.6938 15.5982 42.3915 15.7078 42.1244 15.8781C41.8572 16.0485 41.632 16.2753 41.4652 16.5421C41.4652 16.5421 41.4301 16.6112 41.4301 16.5421C41.5802 16.1294 41.8719 15.7811 42.2548 15.5574C42.2548 15.5574 42.6409 15.4192 42.676 15.0737C42.7111 14.7282 42.4303 14.6418 42.5531 14.5036C42.6293 14.419 42.7245 14.353 42.8311 14.311C42.9377 14.269 43.0529 14.2522 43.1673 14.2617C43.1673 14.2617 43.1849 14.2617 43.1673 14.2445C43.1498 14.2272 42.8514 13.7089 41.9916 14.0717H41.9565C41.9214 14.0544 40.2544 13.5016 40.307 16.0239C40.307 16.0239 40.3246 16.1275 40.307 16.0757C40.2895 16.0239 40.0614 15.782 40.1491 14.9355C40.1491 14.9355 40.272 14.5036 39.9035 14.4863H39.8508C39.8508 14.4863 39.8859 14.0372 40.2369 13.9508H40.2544C40.2544 13.9508 39.3595 13.4325 38.9734 15.6093C38.9734 15.6093 38.9734 16.4385 39.1138 16.7667C39.2542 17.095 39.2717 18.4425 38.447 19.5308C38.447 19.5308 36.0254 22.0185 34.5339 22.0877Z"
          fill="#9CA3AF"></path>
        <path
          d="M37.973 52.8211C37.938 52.8038 37.973 52.7693 37.973 52.7693C38.1836 51.1108 35.7796 51.3527 35.7796 51.3527L35.7621 51.3009C37.4993 51.0245 38.0432 51.871 38.0432 51.871L38.0783 51.8364C38.0257 50.7653 37.2887 50.6098 37.2887 50.6098C37.2711 50.6079 37.2542 50.6023 37.2391 50.5934C37.2239 50.5844 37.2109 50.5724 37.2009 50.558C36.8675 49.9879 35.7796 49.9015 35.2532 50.2298C34.9555 50.4214 34.621 50.5507 34.2705 50.6098C32.0946 51.059 30.9014 49.2969 30.9014 49.2969C28.8308 46.1009 31.9016 46.4464 31.4454 42.9395C30.9891 39.4325 25.0054 38.6897 25.0054 38.6897C23.391 38.4824 22.1276 41.6438 21.8644 42.5421C21.6012 43.4405 21.3906 43.7342 21.3906 43.7342L21.4959 43.8205C26.2688 44.5288 26.1285 47.0165 26.1285 47.0165C26.146 47.5348 25.4266 47.5866 25.4266 47.5866C24.9703 47.6557 23.935 48.1913 25.7073 49.4006L26.0583 49.6424C26.0758 49.6597 26.0583 49.6597 26.0583 49.677L25.5143 50.0225L25.1809 50.1607C25.0054 50.2125 24.6369 50.5235 24.5141 51.0936H24.5316C24.5492 51.0417 24.7422 50.6271 25.5669 50.6617C26.0429 50.6706 26.5095 50.5315 26.9006 50.2643H26.9532L27.427 50.5926L27.0409 50.7826L26.69 50.869C26.5145 50.9035 26.1109 51.1799 25.9003 51.7155C25.9003 51.7155 25.9179 51.7328 25.9354 51.6982C25.953 51.6637 26.1987 51.2663 27.0058 51.4045C27.4425 51.4729 27.89 51.4066 28.2868 51.2145H28.3044L28.7255 51.5082L28.1113 51.7673L27.7428 51.8364C27.5674 51.8537 27.1462 52.0955 26.9181 52.6484H26.9532C26.9707 52.6138 27.234 52.2165 28.0411 52.4065C28.5554 52.5074 29.0892 52.4279 29.5502 52.1819H29.6029C30.6908 53.0457 31.5506 53.8749 31.5857 54.3932C31.6208 55.4988 32.4807 55.7407 32.4807 55.7407C32.4982 56.2417 32.1999 56.3281 32.1999 56.3281H32.235C32.4603 56.38 32.6972 56.3489 32.9008 56.2407C33.1045 56.1325 33.2609 55.9546 33.3405 55.7407C33.3405 55.7234 33.3756 55.7234 33.3756 55.7234C33.5992 55.5484 33.7664 55.3132 33.8567 55.0463C33.9471 54.7795 33.9567 54.4926 33.8845 54.2205C33.8845 54.1859 33.9196 54.255 33.9196 54.255C34.7969 55.6889 35.6743 55.3434 35.6743 55.3434L35.7094 55.3606C35.7571 55.7936 35.645 56.2289 35.3936 56.5872C35.3585 56.6045 35.3936 56.6045 35.3936 56.6045C36.5517 55.9653 36.5341 55.1706 36.5341 55.1706V55.1015C37.6572 53.6676 35.4111 52.6484 35.4111 52.6484V52.6311C36.2709 52.7348 36.6394 52.9421 36.6921 53.0457C36.7447 53.1494 36.8851 53.4431 37.1308 53.4949C37.2998 53.5237 37.473 53.5178 37.6396 53.4776C37.8678 53.4949 37.9029 54.0822 37.9029 54.0822H37.9204C38.517 53.2876 37.9555 52.8211 37.9555 52.8211"
          fill="#9CA3AF"></path>
        <path
          d="M36.6922 16.4904C36.2711 16.6458 34.7269 16.4213 34.3233 16.1448C34.113 15.9994 33.8738 15.8993 33.6214 15.8511C32.1123 15.6093 31.5859 16.404 31.5332 16.4385C31.4806 16.4731 31.5508 16.5076 31.5508 16.5076C31.5508 16.5076 32.4457 16.1103 34.0425 16.8532C35.6394 17.596 36.622 16.594 36.622 16.594L36.6922 16.4904Z"
          fill="#9CA3AF"></path>
        <path
          d="M33.9191 27.1665C33.4454 25.9226 31.1993 25.2489 30.7606 25.0934C30.3219 24.9379 29.9008 24.7306 29.9008 24.7306C30.1464 25.8362 29.5498 26.821 29.5498 26.821H29.5849C29.6812 26.7904 29.7839 26.7844 29.8832 26.8037C32.1644 27.3392 33.7437 28.9286 33.7437 28.9286L33.8139 28.9113C33.9085 28.6876 33.9447 28.4441 33.9191 28.203C33.9016 28.0476 33.9191 27.3392 33.9367 27.1665H33.9191Z"
          fill="#9CA3AF"></path>
        <path
          d="M67.2927 21.793H67.3496L74.9495 35.1289H80.8132V13.7637H75.4619V26.9051H75.3765L67.7766 13.7637H61.9414V35.1289H67.2927V21.793ZM124.534 28.4887C124.534 26.0438 123.082 24.0712 120.748 23.4044C122.485 22.5709 123.652 21.0151 123.652 19.098C123.652 14.6528 119.496 13.6803 115.739 13.7637H105.492V35.1289H116.023C120.691 35.1289 124.534 33.573 124.534 28.4887ZM111.184 18.0701H115.596C116.792 18.0701 117.93 18.5702 117.93 20.126C117.93 21.6819 116.593 22.1542 115.596 22.1542H111.184V18.0701ZM115.767 30.6558H111.184V25.7382H115.767C117.475 25.7382 118.841 26.3772 118.841 28.2665C118.841 30.1557 117.56 30.6558 115.767 30.6558ZM93.1381 35.5734C100.026 35.5734 103.926 30.628 103.926 24.4324C103.926 18.2368 100.026 13.2914 93.1381 13.2914C86.2498 13.2914 82.3502 18.2368 82.3502 24.4324C82.3502 30.628 86.2498 35.5734 93.1381 35.5734ZM93.1381 18.0979C96.81 18.0979 98.2332 21.2651 98.2332 24.4324C98.2332 27.5997 96.81 30.7669 93.1381 30.7669C89.4663 30.7669 88.0431 27.5997 88.0431 24.4324C88.0431 21.2651 89.4663 18.0979 93.1381 18.0979ZM139.307 13.7637V26.6273C139.307 29.3778 138.254 30.7669 135.464 30.7669C132.675 30.7669 131.65 29.3778 131.65 26.6273V13.7637H125.929V26.2939C125.929 32.4895 128.861 35.5734 135.464 35.5734C142.068 35.5734 145 32.4895 145 26.2939V13.7637H139.307ZM66.9226 44.1861H66.8942L63.9339 37.8238H61.9414V46.3532H63.4785V39.8242H63.5069L66.4672 46.3532H68.4597V37.8238H66.9226V44.1861ZM75.1773 38.9907H77.5967V46.3532H79.2192V38.9907H81.6102V37.8238H75.1773V38.9907ZM71.6477 37.8238L68.7443 46.3532H70.4522L71.1069 44.2695H74.0956L74.7218 46.3532H76.4297L73.754 37.8238H71.6477ZM71.42 43.186L72.6155 38.9907H72.6439L73.8394 43.186H71.42ZM120.805 41.8524C121.802 41.6023 122.285 40.9633 122.285 39.9353C122.285 38.4351 121.062 37.8516 119.553 37.8516H116.251V46.381H119.468C121.403 46.381 122.627 45.4642 122.627 44.0472C122.627 42.6303 121.915 42.0469 120.805 41.8802V41.8524ZM117.874 38.8796H119.012C119.923 38.8796 120.663 39.1019 120.663 40.1021C120.663 41.1022 120.151 41.4634 119.154 41.4634H117.874V38.8796ZM119.496 45.353H117.874V42.3803H119.382C120.35 42.3803 121.005 42.9081 121.005 43.825C121.005 44.7418 120.464 45.3253 119.496 45.3253V45.353ZM135.464 44.1861H135.436L132.476 37.8238H130.483V46.3532H132.02V39.8242H132.049L135.009 46.3532H137.001V37.8238H135.464V44.1861ZM125.417 37.8238L122.513 46.3532H124.221L124.876 44.2695H127.864L128.491 46.3532H130.199L127.523 37.8238H125.417ZM125.189 43.186L126.384 38.9907H126.441L127.608 43.186H125.189ZM108.793 37.8238H107.171V46.3532H112.722V45.2141H108.793V37.8238ZM82.0656 46.3532H83.688V37.8238H82.0656V46.3532ZM144.687 37.8238H142.894L139.819 41.5468H139.791V37.8238H138.168V46.3532H139.791V43.6027L140.702 42.5192L143.15 46.3532H145L141.755 41.4356L144.687 37.8238ZM87.9861 37.6571C85.6521 37.6571 84.6274 39.0463 84.6274 42.1024C84.6274 45.1586 85.6521 46.5199 87.9861 46.5199C90.3202 46.5199 91.3734 45.1308 91.3734 42.1024C91.3734 39.0741 90.3487 37.6571 87.9861 37.6571ZM87.9861 45.492C87.0184 45.492 86.2498 44.9919 86.2498 42.0469C86.2498 39.1019 87.0184 38.6851 87.9861 38.6851C88.9539 38.6851 89.7509 39.1852 89.7509 42.0469C89.7509 44.9085 88.9824 45.492 87.9861 45.492ZM97.3793 44.1861H97.3224L94.3621 37.8238H92.3981V46.3532H93.9351V39.8242H93.9636L96.9239 46.3532H98.9164V37.8238H97.3793V44.1861ZM102.104 37.8238L99.201 46.3532H100.909L101.564 44.2695H104.552L105.178 46.3532H106.886L104.182 37.8238H102.104ZM101.848 43.186L103.072 38.9907H103.101L104.268 43.186H101.848Z"
          fill="#9CA3AF"></path>
      </svg>
    </div>
  </main>

  <!-- Modal wajib simpan bukti pembayaran QRIS -->
  <div id="modal-validasi-qris" class="qris-modal is-open fixed inset-0 z-50 items-center justify-center px-4">
    <div class="relative z-10 w-full max-w-sm rounded-2xl bg-white p-5 shadow-card -mt-24">
      <div class="mb-2 mt-1 text-center text-sm text-slate-700">
        <img src="/assets/warning.png" class="w-9 h-9 mx-auto mb-4 object-contain" alt="" />
        <p class="mb-3 px-2 text-center text-[17px] leading-5 font-extrabold text-slate-900">WAJIB SIMPAN BUKTI PEMBAYARAN QRIS!</p>
        <p class="mb-3">Simpan selalu bukti pembayaran QRIS yang menampilkan detail transaksi (kode RRN).</p>
        <p>Jika pembayaran tidak masuk dan tidak ada bukti lengkap yang menampilkan detail transaksi (kode RRN), maka di luar tanggung jawab Bukakios.</p>
        <p class="text-center mt-6">Terima kasih.</p>
      </div>
      <div class="mt-6">
        <button type="button" data-close="modal-validasi-qris" class="w-full rounded-xl bg-brand px-4 py-3 text-sm font-bold text-white active:scale-[0.99] transition">Oke</button>
      </div>
    </div>
    <div class="absolute inset-0 bg-black/45"></div>
  </div>

  <?php require_once('_modals.php'); ?>

  <?php require_once('_script.php'); ?>
</body>
</html>
