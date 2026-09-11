<?php
@ob_start();
error_reporting(E_ERROR | E_PARSE);
require_once("../config.php");
// session_destroy();
require_once("../_session.php");
require_once("../lib/ApiV2.php");
$api_v2 = new ApiV2($user_jwt);


$file_me = "index.php";
$kode_produk = 'PGAS'; //data static kode produk PGN
$data_produk_raw = $api_v2->detail_produk_by_code($kode_produk);
$data_produk = json_decode($data_produk_raw, true);
if (isset($data_produk['status']) and $data_produk['status'] == 1) {
    $data_produk = $data_produk['data'];
    $code = $data_produk['code'];
    $product_logo = $data_produk['product_logo'];
    $product_name = $data_produk['product_name'];
    $product_price = $data_produk['price'] ?? 0;
    $product_price_add = $data_produk['price_add'] ?? 0;
} else {
    $error_msg = "Server untuk mendapatkan data produk gagal di muat... silahkan coba lagi beberapa saat!!";
    if (isset($data_produk['error_msg'])) {
        $error_msg = $data_produk['error_msg'];
    }
    $html_title = "Gagal";
    $lyt_button_link = "$c_url/tagihan-pgn/$file_me";
    $lyt_button_name = "COBA LAGI";
    $lyt_image = "https://assets.bukakios.net/img/illustration/bc_trx_gagal.png";
    $lyt_title = "Ada Kesalahan!";
    $lyt_description = $error_msg;
    require_once(ROOT . "/_template/general_message.php");
    exit;
}

/************ ACTION HERE *****************/
if (isset($_REQUEST['msg'], $_REQUEST['csrf'])) {
    //action here
    $msg = $_REQUEST['msg'];
    $csrf = $_REQUEST['csrf'];
    if ($_SESSION['csrf'] === $csrf) {
        if ($msg === "cek") {
            //cek tagihan...
            $id_pelanggan = $_REQUEST['id_pelanggan'];
            $cek_tagihan_http = $api_v2->inq_pasca($kode_produk, $id_pelanggan);
            // Debug log inquiry PGN
            if (isset($app) && method_exists($app, 'simpan_file')) {
                //$app->simpan_file("inq_pgn_" . preg_replace('/[^A-Za-z0-9]/', '', $id_pelanggan) . ".json", (string)$cek_tagihan_http);
            }

            $cek_tagihan = json_decode($cek_tagihan_http, true);
            if (isset($cek_tagihan['status'])) {
                $data_r = $cek_tagihan;
            } else {
                $data_r = ['status' => 0, "error_msg" => "Gagal Cek Tagihan, server a1 tidak merespon, no status", "r" => $cek_tagihan];
            }
        } else if ($msg === "bayar") {
            //bayar tagihan
            if (isset($_REQUEST['trx_id'], $_REQUEST['biaya_toko'])) {
                $trx_id = $_REQUEST['trx_id'];
                $biaya_toko = $_REQUEST['biaya_toko'];
                $tanggal = $_REQUEST['tanggal'] ?? '';
                $bayar_tagihan_http = $api_v2->pay_pasca($trx_id, $biaya_toko, $tanggal);
                $bayar_tagihan = json_decode($bayar_tagihan_http, true);
                if (isset($bayar_tagihan['status'])) {
                    $data_r = $bayar_tagihan;
                } else {
                    $data_r = ['status' => 0, "error_msg" => "server a1 tidak merespon, status0", "raw" => substr((string)$bayar_tagihan_http, 0, 1000)];
                }
                // Debug log: tulis raw response bayar PGN supaya bisa dicek kalau ada keluhan
                if (isset($app) && method_exists($app, 'simpan_file')) {
                    $app->simpan_file("pay_pgn_" . preg_replace('/[^A-Za-z0-9]/', '', $trx_id) . ".json", (string)$bayar_tagihan_http);
                }
            } else {
                $data_r = ['status' => 0, "error_msg" => "trx_id tidak di temukan, refresh halaman ini"];
            }
        } else {
            $data_r = ['status' => 0, "error_msg" => "Tidak ada aksi untuk msg ini"];
        }
    } else {
        $data_r = ['status' => 0, "error_msg" => "Halaman Kadaluarsa, silahkan tutup halaman ini, kemudian buka kembali"];
    }
    // Bersihkan semua output buffer agar JSON bersih tanpa noise dari PHP warning/notice
    while (ob_get_level() > 0) { ob_end_clean(); }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data_r);
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($product_name ?? 'Bayar Tagihan Gas PGN') ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: '#1a7fce',
            brandDark: '#1265a6',
            mutedText: '#6b7280',
            line: '#d9e1e8',
          },
          boxShadow: {
            card: '0 5px 14px rgba(16, 24, 40, 0.07)',
            soft: '0 4px 12px rgba(15, 23, 42, 0.06)',
            cta: '0 10px 22px rgba(26, 127, 206, 0.28)'
          },
          fontFamily: {
            sans: ['Inter', 'ui-sans-serif', 'system-ui', 'Segoe UI', 'Roboto', 'Arial', 'sans-serif'],
          }
        }
      }
    }
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <style>
    * { font-family: 'Inter', sans-serif; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .hide-scrollbar::-webkit-scrollbar { display: none; }

    /* ===== Custom modal animations ===== */
    /* Popup scale + fade in */
    #customModal:not(.hidden) > div {
      animation: modalPopIn 0.32s cubic-bezier(0.34, 1.56, 0.64, 1) both;
    }
    @keyframes modalPopIn {
      0%   { opacity: 0; transform: scale(0.85) translateY(8px); }
      100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    /* Popup scale + fade out (close) */
    @keyframes modalPopOut {
      0%   { opacity: 1; transform: scale(1) translateY(0); }
      100% { opacity: 0; transform: scale(0.92) translateY(4px); }
    }

    /* Icon circle: scale + fade in (delay 0.1s biar popup muncul dulu) */
    .modal-icon-circle {
      transform-origin: center;
      animation: iconCircleIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) 0.1s both;
    }
    @keyframes iconCircleIn {
      0%   { opacity: 0; transform: scale(0.4); }
      60%  { opacity: 1; transform: scale(1.08); }
      100% { opacity: 1; transform: scale(1); }
    }

    /* Success check: stroke-dashoffset draw animation */
    .modal-check-path {
      stroke-dasharray: 28;
      stroke-dashoffset: 28;
      animation: checkDraw 0.55s cubic-bezier(0.65, 0, 0.45, 1) 0.4s forwards;
    }
    @keyframes checkDraw {
      to { stroke-dashoffset: 0; }
    }

    /* Success check circle: scale pop in */
    .modal-check-circle-bg {
      transform-origin: center;
      animation: iconCircleIn 0.45s cubic-bezier(0.34, 1.56, 0.64, 1) 0.15s both;
    }

    /* Fail icon: shake (subtle attention grab) */
    .modal-fail-path {
      transform-origin: center;
      animation: failShake 0.45s cubic-bezier(0.36, 0.07, 0.19, 0.97) 0.2s both;
    }
    @keyframes failShake {
      0%   { opacity: 0; transform: scale(0.5); }
      40%  { opacity: 1; transform: scale(1.1) rotate(-6deg); }
      70%  { transform: scale(1) rotate(4deg); }
      100% { transform: scale(1) rotate(0); }
    }

    /* Title + subtitle: fade up stagger */
    .modal-title    { animation: fadeUp 0.35s ease-out 0.25s both; }
    .modal-subtitle { animation: fadeUp 0.35s ease-out 0.35s both; }
    .modal-body     { animation: fadeUp 0.4s ease-out 0.45s both; }
    .modal-btn      { animation: fadeUp 0.35s ease-out 0.55s both; }
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(8px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* Backdrop fade in */
    #customModal { animation: backdropIn 0.2s ease-out both; }
    @keyframes backdropIn {
      from { opacity: 0; }
      to   { opacity: 1; }
    }
  </style>
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-950">

  <!-- Loading overlay -->
  <div id="loadingOverlay" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/40 backdrop-blur-[2px]">
    <div class="flex flex-col items-center gap-3 rounded-2xl bg-white px-8 py-6 shadow-xl">
      <svg class="h-8 w-8 animate-spin text-brand" viewBox="0 0 24 24" fill="none">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
      </svg>
      <p class="text-[14px] font-extrabold text-slate-700" id="loadingText">Memproses…</p>
    </div>
  </div>

  <!-- Toast error -->
  <div id="toastError" class="fixed top-4 left-1/2 z-[9998] hidden -translate-x-1/2 max-w-[90vw] w-full px-4">
    <div class="flex items-start gap-3 rounded-xl bg-red-600 px-4 py-3 shadow-lg text-white">
      <svg viewBox="0 0 24 24" class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
      <p id="toastErrorMsg" class="text-[14px] font-semibold leading-5"></p>
    </div>
  </div>

  <!-- Custom modal (success / fail / info) -->
  <div id="customModal" class="fixed inset-0 z-[10000] hidden items-center justify-center bg-black/50 backdrop-blur-[2px] px-4">
    <div class="w-[92vw] max-w-[400px] rounded-3xl bg-white px-7 py-7 shadow-2xl ring-1 ring-slate-900/5">
      <div id="customModalBody"></div>
      <button id="customModalBtn" type="button" class="mt-6 w-full rounded-xl bg-slate-900 hover:bg-slate-800 active:scale-[0.99] text-white text-[16px] font-bold py-3.5 px-5 transition">
        Tutup
      </button>
    </div>
  </div>

  <main class="relative w-full min-h-screen bg-slate-50 pb-24">

    <!-- Header -->
    <header class="relative z-10 px-5 pt-4 pb-3 bg-white border-b border-slate-100">
      <div class="flex items-center gap-3">
        <button id="backBtn" onclick="history.back()" class="grid h-9 w-9 place-items-center rounded-full border border-slate-200 bg-white text-slate-700 transition hover:bg-slate-50 active:bg-slate-100">
          <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
        </button>
        <div class="h-1.5 flex-1 rounded-full bg-slate-200 overflow-hidden">
          <div class="h-full rounded-full bg-brand transition-all duration-300" id="progressBar" style="width: 33%"></div>
        </div>
      </div>
    </header>

    <!-- Product info -->
    <section class="px-6 pt-5 pb-4 bg-white">
      <div class="flex items-center gap-3">
        <div class="h-12 w-12 rounded-2xl shadow-card bg-brand/8 flex items-center justify-center overflow-hidden shrink-0">
          <img src="<?= htmlspecialchars($product_logo ?? '') ?>" class="h-full w-full object-contain" alt="PGN" onerror="this.style.display='none';this.parentElement.innerHTML='<svg class=\'h-6 w-6 text-brand\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.8\'><path d=\'M12 2C8 7 6 11 6 15a6 6 0 0012 0c0-4-2-8-6-13z\'/><circle cx=\'12\' cy=\'15\' r=\'2\'/></svg>'"/>
        </div>
        <div>
          <h1 class="text-[18px] font-bold text-slate-900 leading-tight">Bayar Tagihan Gas PGN</h1>
          <p class="text-[13px] text-mutedText mt-0.5"><?= htmlspecialchars($code) ?></p>
        </div>
      </div>
    </section>

    <!-- Input section -->
    <section class="px-4 pb-4">
      <div class="rounded-[16px] border border-slate-200 bg-white shadow-soft overflow-hidden">
        <div class="px-5 pt-5 pb-4" id="inputSection">
          <label class="text-[14px] font-semibold text-slate-700">Nomor Pelanggan</label>
          <div class="mt-2 flex gap-2">
            <input
              type="tel"
              inputmode="numeric"
              pattern="[0-9]*"
              id="nope"
              class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-[16px] text-slate-800 placeholder-slate-400 outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 transition"
              placeholder="Masukkan nomor pelanggan"
              autocomplete="off"
            />
            <button type="button" onclick="showInfo()" class="grid h-[50px] w-[50px] shrink-0 place-items-center rounded-xl border border-slate-200 bg-slate-50 text-slate-500 transition hover:bg-slate-100">
              <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
            </button>
          </div>
          <input type="hidden" id="csrf" value="<?= $app->csrf(); ?>" />
          <p class="text-[13px] text-mutedText mt-2">Masukkan nomor pelanggan gas PGN (8–11 digit)</p>
        </div>

        <!-- Info card -->
        <div class="px-5 pb-5" id="infoCard">
          <div class="rounded-xl bg-emerald-50 border border-emerald-100 px-4 py-3 flex items-start gap-3">
            <div class="shrink-0 mt-0.5">
              <svg class="h-4 w-4 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-[13px] text-emerald-700 leading-snug">
                Bayar tagihan PLN di bukakios, kamu otomatis mendapatkan keuntungan dari biaya admin.
              </p>
            </div>
          </div>
          <div class="mt-3 rounded-xl bg-amber-50 border border-amber-100 px-4 py-3 flex items-start gap-3">
            <div class="shrink-0 mt-0.5">
              <svg class="h-4 w-4 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <p class="text-[13px] text-amber-700 leading-snug">
              Pastikan tagihan gas PGN kamu sudah sesuai sebelum melakukan pembayaran. Cek nama pelanggan dan jumlah tagihan di struk/invoice PGN.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Error message -->
    <div id="errorMsg" class="hidden mx-4 mb-3 rounded-xl bg-red-50 border border-red-100 px-4 py-3">
      <div class="flex items-start gap-3">
        <svg class="h-4 w-4 text-red-400 mt-0.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
        <p id="errorText" class="text-[14px] text-red-600 font-medium leading-snug"></p>
      </div>
    </div>

    <!-- Detail tagihan (hidden until CEK) -->
    <section id="detailSection" class="hidden px-4 pb-6">
      <div class="rounded-[20px] border border-slate-200 bg-white shadow-soft overflow-hidden">

        <!-- Header -->
        <div class="px-5 py-4 bg-brand text-white">
          <h2 class="text-[15px] font-bold">Detail Tagihan</h2>
        </div>

        <!-- Billing rows -->
        <div class="divide-y divide-slate-100">
          <div class="flex items-center justify-between px-5 py-3">
            <span class="text-[14px] text-slate-600">Total Tagihan</span>
            <span class="text-[16px] font-extrabold text-brand" id="tot_ta">-</span>
          </div>
          <div class="flex items-center justify-between px-5 py-3">
            <span class="text-[14px] text-slate-600">Nama Pelanggan</span>
            <span class="text-[14px] font-semibold text-slate-800" id="nama">-</span>
          </div>
          <div class="flex items-center justify-between px-5 py-3" id="row_tarif_daya">
            <span class="text-[14px] text-slate-600">Tarif / Daya</span>
            <span class="text-[14px] font-semibold text-slate-800" id="tarif_daya">-</span>
          </div>
          <div class="flex items-center justify-between px-5 py-3">
            <span class="text-[14px] text-slate-600">Lembar Tagihan</span>
            <span class="text-[14px] font-semibold text-slate-800" id="lembar_tagihan">-</span>
          </div>
          <div class="flex items-center justify-between px-5 py-3">
            <span class="text-[14px] text-slate-600">Periode</span>
            <span class="text-[14px] font-semibold text-slate-800" id="periode">-</span>
          </div>
          <div class="flex items-center justify-between px-5 py-3" id="row_jml_meter">
            <span class="text-[14px] text-slate-600">Jumlah Meter</span>
            <span class="text-[14px] font-semibold text-slate-800" id="jml_meter">-</span>
          </div>
          <div class="flex items-center justify-between px-5 py-3">
            <span class="text-[14px] text-slate-600">Tagihan</span>
            <span class="text-[14px] font-semibold text-slate-800" id="tagihan">-</span>
          </div>
          <div class="flex items-center justify-between px-5 py-3">
            <span class="text-[14px] text-slate-600">Biaya Admin</span>
            <span class="text-[14px] font-semibold text-slate-800" id="biaya">-</span>
          </div>
          <div class="flex items-center justify-between px-5 py-3">
            <span class="text-[14px] text-slate-600">Diskon Biaya Admin</span>
            <span class="text-[14px] font-semibold text-emerald-600" id="potongan">-</span>
          </div>
          <div class="flex items-center justify-between px-5 py-3" id="row_denda">
            <span class="text-[14px] text-slate-600">Denda</span>
            <span class="text-[14px] font-semibold text-slate-800" id="denda">-</span>
          </div>
          <div class="flex items-center justify-between px-5 py-3 bg-slate-50">
            <span class="text-[15px] font-bold text-slate-800">Total Bayar</span>
            <span class="text-[18px] font-extrabold text-brand" id="tot_ka">-</span>
          </div>
        </div>
      </div>

      <!-- Profit detail -->
      <div class="mt-3 rounded-[20px] border border-slate-200 bg-white shadow-soft overflow-hidden">
        <div class="px-5 py-4 bg-slate-800 text-white">
          <h2 class="text-[15px] font-bold">Detail Keuntungan</h2>
        </div>
        <div class="divide-y divide-slate-100">
          <div class="flex items-center justify-between px-5 py-3">
            <span class="text-[14px] text-slate-600">Pelanggan Kamu Bayar</span>
            <span class="text-[14px] font-semibold text-slate-800" id="tot_ta2">-</span>
          </div>
          <div class="flex items-center justify-between px-5 py-3">
            <span class="text-[14px] text-slate-600">Saldo Kamu Berkurang</span>
            <span class="text-[14px] font-semibold text-red-500" id="tot_ka2">-</span>
          </div>
          <div class="flex items-center justify-between px-5 py-3 bg-emerald-50/50">
            <span class="text-[15px] font-bold text-emerald-700">Profit Kamu</span>
            <span class="text-[18px] font-extrabold text-emerald-600" id="profit">-</span>
          </div>
        </div>
      </div>

      <!-- Biaya layanan -->
      <div class="mt-3 rounded-[20px] border border-slate-200 bg-white shadow-soft px-5 py-4">
        <h3 class="text-[14px] font-bold text-slate-700 mb-1">Buat Biaya Layanan Toko/Kios</h3>
        <input
          type="number"
          id="biaya_profit"
          class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-[15px] text-slate-800 outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 transition"
          value="0"
          min="0"
        />
        <p class="text-[12px] text-mutedText mt-1.5">* Akan muncul di struk transaksi</p>
      </div>
    </section>

  </main>

  <!-- Fixed footer -->
  <footer class="fixed bottom-0 left-0 right-0 z-10 bg-white border-t border-slate-200 shadow-soft px-4 py-3">
    <button id="btnCek" onclick="doCek()" class="w-full rounded-2xl bg-brand py-3.5 text-[16px] font-bold text-white shadow-cta transition hover:bg-brandDark active:scale-[0.99]">
      Cek Tagihan
    </button>

    <div id="btnActionGroup" class="hidden grid grid-cols-2 gap-3">
      <button id="btnBatal" onclick="doBatal()" class="rounded-2xl border border-slate-200 bg-white py-3.5 text-[15px] font-bold text-slate-600 transition hover:bg-slate-50 active:scale-[0.99]">
        Batal
      </button>
      <button id="btnBayar" onclick="doBayar()" class="rounded-2xl bg-emerald-500 py-3.5 text-[15px] font-bold text-white shadow-cta transition hover:bg-emerald-600 active:scale-[0.99]">
        Bayar Sekarang
      </button>
    </div>

    <div id="btnProcessing" class="hidden">
      <div class="flex items-center justify-center gap-3 rounded-2xl bg-slate-100 py-3.5">
        <svg class="h-5 w-5 animate-spin text-brand" viewBox="0 0 24 24" fill="none">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
        </svg>
        <span class="text-[15px] font-semibold text-slate-600">Memproses transaksi…</span>
      </div>
    </div>
  </footer>

  <script>
    // State
    var trx_id = "";

    // Product detail (dari API V2 detail_produk_by_code)
    var productDetail = {
      price: <?= (int) $product_price ?>,
      price_add: <?= (int) $product_price_add ?>
    };

    // Parse string rupiah ke angka
    function parseRupiah(rupiahString) {
      try {
        if (rupiahString == null || rupiahString === '') return 0;
        var str = String(rupiahString);
        var isNegative = str.indexOf('-') !== -1;
        var numberOnly = str.replace(/[^\d]/g, '');
        var result = numberOnly ? Number(numberOnly) : 0;
        return isNegative ? -result : result;
      } catch (e) {
        return 0;
      }
    }

    // Format angka ke rupiah (Intl, fallback manual)
    function formatRupiah(angka) {
      try {
        if (typeof Intl !== 'undefined' && Intl.NumberFormat) {
          return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
          }).format(angka);
        }
        var num = Number(angka);
        if (isNaN(num)) return 'Rp 0';
        var parts = Math.abs(num).toString().split('.');
        var integerPart = parts[0];
        var formatted = '';
        for (var i = 0; i < integerPart.length; i++) {
          if (i > 0 && (integerPart.length - i) % 3 === 0) formatted += '.';
          formatted += integerPart[i];
        }
        return (num < 0 ? '-Rp ' : 'Rp ') + formatted;
      } catch (e) {
        return 'Rp 0';
      }
    }

    // Parse nilai numerik polos (untuk field yang bukan rupiah)
    function parseNum(v) {
      var n = Number(String(v == null ? 0 : v).replace(/[^\d]/g, ''));
      return isNaN(n) ? 0 : n;
    }

    // Format to Rp
    function formatRp(n) {
      var s = String(Math.abs(Number(n))),
          r = (s.length % 3),
          rp = s.substr(0, r),
          thousands = s.substr(r).match(/\d{3}/g);
      if (thousands) {
        var sep = r ? '.' : '';
        rp += sep + thousands.join('.');
      }
      return 'Rp. ' + rp;
    }

    function showLoading(text) {
      document.getElementById('loadingText').textContent = text || 'Memproses…';
      document.getElementById('loadingOverlay').classList.remove('hidden');
      document.getElementById('loadingOverlay').classList.add('flex');
    }
    function hideLoading() {
      document.getElementById('loadingOverlay').classList.add('hidden');
      document.getElementById('loadingOverlay').classList.remove('flex');
    }

    function showToastError(msg) {
      var el = document.getElementById('toastError');
      document.getElementById('toastErrorMsg').textContent = msg;
      el.classList.remove('hidden');
      el.classList.add('flex');
      setTimeout(function() { el.classList.add('hidden'); el.classList.remove('flex'); }, 4500);
    }

    function showInfo() {
      showModal({
        title: 'Cek Nomor Pelanggan Gas PGN',
        message: 'Nomor pelanggan gas PGN terdiri dari 8–11 digit angka, tertera pada kartu pelanggan, struk tagihan, atau invoice bulanan PGN. Jika sulit ditemukan, hubungi call center PGN 1500-645.',
        variant: 'info',
        btnText: 'Ok'
      });
    }

    /**
     * Custom modal.
     * @param {object} opts
     *  - title      : string
     *  - message    : string (untuk fail/info). Diabaikan kalau bodyHtml diset.
     *  - bodyHtml   : string HTML (untuk success yang lebih kompleks). Override message.
     *  - variant    : 'success' | 'fail' | 'info' (default 'info')
     *  - btnText    : string (default 'Tutup')
     *  - btnColor   : 'emerald' | 'slate' (default 'slate')
     *  - onClose    : function (dipanggil saat modal ditutup)
     */
    function showModal(opts) {
      var o = opts || {};
      var variant = o.variant || 'info';
      var bodyHtml = o.bodyHtml;
      var titleText = o.title || '';
      var messageText = o.message || '';
      var btnText = o.btnText || 'Tutup';
      var btnColor = o.btnColor || 'slate';
      var onClose = typeof o.onClose === 'function' ? o.onClose : null;

      // Tiap variant punya animasi icon berbeda
      var iconHtml = '';
      var iconAnimClass = 'modal-icon-circle';
      if (variant === 'success') {
        // Checkmark dengan draw animation (stroke-dashoffset)
        iconHtml = '<svg viewBox="0 0 24 24" class="w-11 h-11 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">' +
                     '<path class="modal-check-path" d="M4 12.5 L10 18.5 L20 6.5"/>' +
                   '</svg>';
      } else if (variant === 'fail') {
        // X icon dengan shake
        iconHtml = '<svg viewBox="0 0 24 24" class="w-11 h-11 text-red-500" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">' +
                     '<path class="modal-fail-path" d="M6 6 L18 18 M18 6 L6 18"/>' +
                   '</svg>';
      } else {
        // Info icon
        iconHtml = '<svg viewBox="0 0 24 24" class="w-11 h-11 text-brand" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">' +
                     '<circle cx="12" cy="12" r="10"/>' +
                     '<path d="M12 16v-4M12 8h.01"/>' +
                   '</svg>';
      }
      var iconRing = variant === 'success' ? 'bg-emerald-50 ring-emerald-50/50'
                    : variant === 'fail'    ? 'bg-red-50 ring-red-50/50'
                                            : 'bg-brand/10 ring-brand/5';

      var btnColorClass = btnColor === 'emerald'
        ? 'bg-emerald-500 hover:bg-emerald-600 shadow-cta'
        : 'bg-slate-900 hover:bg-slate-800';

      var inner;
      if (bodyHtml) {
        // bodyHtml diasumsikan sudah include animasi class di dalamnya (lihat doBayar).
        // Kalau belum, kita tambahkan default class wrapper biar animasi tetap jalan.
        // Cek apakah bodyHtml sudah punya class animasi di icon circle.
        if (bodyHtml.indexOf('modal-icon-circle') === -1 && bodyHtml.indexOf('modal-check-path') === -1) {
          // Patch icon: cari div w-20 h-20 rounded-full pertama, tambah class modal-icon-circle
          bodyHtml = bodyHtml.replace(
            /(<div class="[^"]*w-20 h-20 rounded-full[^"]*")>/,
            '$1 ' + iconAnimClass + '>'
          );
          // Patch check path
          if (variant === 'success') {
            bodyHtml = bodyHtml.replace(
              /<polyline points="20 6 9 17 4 12"\s*\/>/,
              '<path class="modal-check-path" d="M4 12.5 L10 18.5 L20 6.5"/>'
            );
          } else if (variant === 'fail') {
            bodyHtml = bodyHtml.replace(
              /<circle cx="12" cy="12" r="10"\s*\/>/,
              '<circle cx="12" cy="12" r="10"/><path class="modal-fail-path" d="M6 6 L18 18 M18 6 L6 18"/>'
            ).replace(
              /<line x1="15" y1="9" x2="9" y2="15"\s*\/>/,
              ''
            ).replace(
              /<line x1="9" y1="9" x2="15" y2="15"\s*\/>/,
              ''
            );
          }
        }
        inner = bodyHtml;
      } else {
        inner =
          '<div class="flex flex-col items-center text-center">' +
            '<div class="w-20 h-20 rounded-full flex items-center justify-center mb-5 ring-8 ' + iconRing + ' ' + iconAnimClass + '">' + iconHtml + '</div>' +
            '<h2 class="modal-title text-[21px] font-extrabold leading-tight text-slate-900 mb-2">' + titleText + '</h2>' +
            '<p class="modal-subtitle text-[13.5px] text-slate-600 leading-relaxed max-w-[300px] mx-auto font-medium px-2">' + messageText + '</p>' +
          '</div>';
      }

      var bodyEl = document.getElementById('customModalBody');
      var btnEl = document.getElementById('customModalBtn');
      var modalEl = document.getElementById('customModal');

      bodyEl.innerHTML = inner;
      btnEl.textContent = btnText;
      // Reset & set button color class + animasi
      btnEl.className = 'modal-btn mt-6 w-full rounded-xl text-white text-[16px] font-bold py-3.5 px-5 transition active:scale-[0.99] ' + btnColorClass;

      // Hapus listener lama, pasang yang baru (button + backdrop + escape)
      var newBtn = btnEl.cloneNode(true);
      btnEl.parentNode.replaceChild(newBtn, btnEl);
      newBtn.textContent = btnText;
      newBtn.className = btnEl.className;
      var closeAndFire = function() {
        hideModal();
        cleanupBackdrop();
        cleanupEscape();
        if (onClose) onClose();
      };
      newBtn.addEventListener('click', closeAndFire);

      // Click backdrop (selain inner card) untuk tutup
      var backdropHandler = function(e) {
        if (e.target === modalEl) closeAndFire();
      };
      modalEl.addEventListener('click', backdropHandler);

      // Escape key untuk tutup
      var escHandler = function(e) {
        if (e.key === 'Escape' || e.keyCode === 27) closeAndFire();
      };
      document.addEventListener('keydown', escHandler);

      // Cleanup handlers (disimpan di window agar bisa dihapus dari pemanggil luar kalau perlu)
      window.__customModalCleanup = function() {
        cleanupBackdrop();
        cleanupEscape();
      };
      function cleanupBackdrop() { modalEl.removeEventListener('click', backdropHandler); }
      function cleanupEscape() { document.removeEventListener('keydown', escHandler); }

      modalEl.classList.remove('hidden');
      modalEl.classList.add('flex');
    }

    function hideModal() {
      var modalEl = document.getElementById('customModal');
      if (!modalEl) return;
      // Close animation: scale down + fade out, lalu hide setelah selesai
      var innerCard = modalEl.querySelector(':scope > div');
      if (innerCard) {
        innerCard.style.animation = 'modalPopOut 0.2s cubic-bezier(0.4, 0, 1, 1) forwards';
        setTimeout(function() {
          modalEl.classList.add('hidden');
          modalEl.classList.remove('flex');
          if (innerCard) innerCard.style.animation = '';
        }, 180);
      } else {
        modalEl.classList.add('hidden');
        modalEl.classList.remove('flex');
      }
    }

    function showFailDialog(title, message) {
      showModal({ title: title, message: message, variant: 'fail', btnText: 'Tutup' });
    }

    function showSuccessDialog(html) {
      showModal({
        bodyHtml: html,
        variant: 'success',
        btnText: 'Tutup',
        btnColor: 'emerald'
      });
    }

    // CEK
    function doCek() {
      var id_pelanggan = document.getElementById('nope').value.replace(/[^0-9]/g, '');
      if (!id_pelanggan) {
        showToastError('Nomor pelanggan tidak boleh kosong');
        document.getElementById('nope').focus();
        return;
      }

      // Reset state: sembunyikan error dari percobaan sebelumnya
      hideErrorState();

      var csrf = document.getElementById('csrf').value;
      showLoading('Memeriksa tagihan…');
      document.getElementById('btnProcessing').classList.add('hidden');
      document.getElementById('btnCek').disabled = true;
      document.getElementById('nope').disabled = true;

      var xhr = new XMLHttpRequest();
      xhr.open('GET', '<?= $file_me ?>?msg=cek&id_pelanggan=' + encodeURIComponent(id_pelanggan) + '&csrf=' + encodeURIComponent(csrf), true);
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
          hideLoading();
          document.getElementById('btnCek').disabled = false;
          document.getElementById('nope').disabled = false;
          if (xhr.status === 200) {
            try {
              var myJsn = JSON.parse(xhr.responseText);
              if (myJsn.status == 1) {
                renderDetail(myJsn);
                switchToAction();
              } else {
                showErrorState(myJsn.error_msg || 'Gagal mengecek tagihan');
              }
            } catch(e) {
              showErrorState('Respons server tidak valid');
            }
          } else {
            showErrorState('Koneksi gagal (HTTP ' + xhr.status + ')');
          }
        }
      };
      xhr.send();
    }

    function renderDetail(data) {
      // API bisa return nested (data.data) atau flat (langsung field)
      var d = data.data || data.custom_data || data;
      trx_id = d.trx_id || data.trx_id || "";

      // Hitung diskon biaya admin (rumus wv3)
      var price     = parseNum(productDetail.price);
      var priceAdd  = parseNum(productDetail.price_add);
      var admin     = parseNum(d.admin);
      var bulan     = parseNum(d.jml_bulan) || 1;
      var diskonAngka = admin - ((price + priceAdd) * bulan);

      // Tagihan, denda, dan total bayar dari API
      // PGN biasanya tidak memiliki tarif_daya / jml_meter / denda, fallback ke '-' / 0
      var tagihan     = parseNum(d.tagihan);
      var denda       = parseNum(d.denda);
      var totalBayarPelanggan = parseNum(d.total_bayar);

      // Total Bayar Kamu (saldo seller) = tagihan + admin - diskon (rumus wv3)
      var totalBayarKamuAngka = tagihan + admin - diskonAngka;
      var profitAngka = totalBayarPelanggan - totalBayarKamuAngka;

      // Field tambahan PGN (fallback string kosong)
      var tarifDaya    = d.tarif_daya || '-';
      var jmlMeter     = (d.jml_meter != null && d.jml_meter !== '') ? d.jml_meter : '-';
      var alamat       = d.alamat || d.alamat_pelanggan || '';
      var nomorTagihan = d.nomor_tagihan || '';

      // Tampilkan ke UI
      document.getElementById('tot_ta').textContent         = formatRupiah(totalBayarPelanggan);
      document.getElementById('nama').textContent           = d.customer_name || d.nama_pelanggan || '-';
      document.getElementById('tarif_daya').textContent     = tarifDaya;
      document.getElementById('lembar_tagihan').textContent = (d.jml_bulan != null && d.jml_bulan !== '' ? d.jml_bulan + ' Bulan' : '1 Bulan');
      document.getElementById('periode').textContent        = d.bln_th || d.periode || '-';
      document.getElementById('jml_meter').textContent      = jmlMeter;
      document.getElementById('tagihan').textContent        = formatRupiah(tagihan);
      document.getElementById('biaya').textContent          = formatRupiah(admin);
      document.getElementById('potongan').textContent       = '- ' + formatRupiah(diskonAngka);
      document.getElementById('denda').textContent          = formatRupiah(denda);
      document.getElementById('tot_ka').textContent         = formatRupiah(totalBayarKamuAngka);
      document.getElementById('tot_ka2').textContent        = formatRupiah(totalBayarKamuAngka);
      document.getElementById('tot_ta2').textContent        = formatRupiah(totalBayarPelanggan);
      document.getElementById('profit').textContent         = formatRupiah(profitAngka);

      // Sembunyikan baris yang tidak relevan untuk PGN (tarif_daya, jml_meter, denda)
      if (tarifDaya === '-') {
        var rowTarif = document.getElementById('row_tarif_daya');
        if (rowTarif) rowTarif.style.display = 'none';
      }
      if (jmlMeter === '-') {
        var rowMeter = document.getElementById('row_jml_meter');
        if (rowMeter) rowMeter.style.display = 'none';
      }
      if (!denda || denda === 0) {
        var rowDenda = document.getElementById('row_denda');
        if (rowDenda) rowDenda.style.display = 'none';
      }
    }

    function switchToAction() {
      document.getElementById('inputSection').classList.add('hidden');
      document.getElementById('infoCard').classList.add('hidden');
      document.getElementById('detailSection').classList.remove('hidden');
      document.getElementById('btnCek').classList.add('hidden');
      document.getElementById('btnActionGroup').classList.remove('hidden');
      document.getElementById('progressBar').style.width = '100%';
    }

    function showErrorState(msg) {
      document.getElementById('errorText').textContent = msg;
      document.getElementById('errorMsg').classList.remove('hidden');
    }

    function hideErrorState() {
      document.getElementById('errorMsg').classList.add('hidden');
    }

    function doBatal() {
      trx_id = "";
      document.getElementById('errorMsg').classList.add('hidden');
      document.getElementById('detailSection').classList.add('hidden');
      document.getElementById('btnActionGroup').classList.add('hidden');
      document.getElementById('btnProcessing').classList.add('hidden');
      document.getElementById('inputSection').classList.remove('hidden');
      document.getElementById('infoCard').classList.remove('hidden');
      document.getElementById('btnCek').classList.remove('hidden');
      document.getElementById('btnCek').disabled = false;
      document.getElementById('nope').disabled = false;
      // Kosongkan nomor pelanggan agar tidak terbawa ke transaksi berikutnya
      document.getElementById('nope').value = '';
      document.getElementById('biaya_profit').value = '0';
      document.getElementById('progressBar').style.width = '33%';
      // Reset baris kondisional yang mungkin di-hide saat cek sebelumnya (khusus PGN)
      ['row_tarif_daya', 'row_jml_meter', 'row_denda'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.style.display = '';
      });
      // Fokus kembali ke input agar UX lebih baik
      document.getElementById('nope').focus();
    }

    // BAYAR
    function doBayar() {
      if (!trx_id) { showToastError('Silakan cek tagihan terlebih dahulu'); return; }

      var csrf      = document.getElementById('csrf').value;
      var id_pel    = document.getElementById('nope').value.trim();
      var biaya_toko = document.getElementById('biaya_profit').value || '0';

      document.getElementById('btnActionGroup').classList.add('hidden');
      document.getElementById('btnProcessing').classList.remove('hidden');

      var xhr = new XMLHttpRequest();
      xhr.open('GET', '<?= $file_me ?>?msg=bayar&biaya_toko=' + encodeURIComponent(biaya_toko) + '&id_pelanggan=' + encodeURIComponent(id_pel) + '&csrf=' + encodeURIComponent(csrf) + '&trx_id=' + encodeURIComponent(trx_id), true);
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
          if (xhr.status === 200) {
            try {
              // Strip BOM dan whitespace sebelum parse agar lebih toleran
              var rawText = (xhr.responseText || '').replace(/^\uFEFF/, '').trim();
              var myJsn = JSON.parse(rawText);
              if (myJsn.status == 1) {
                // Dialog sukses yang bagus
                var namaPelanggan = document.getElementById('nama').textContent || '-';
                var saldoBerkurang = document.getElementById('tot_ka2').textContent || 'Rp 0';
                var trxId         = myJsn.data || myJsn.trx_id || trx_id;

                var successHtml =
                  '<div class="flex flex-col items-center text-center">' +
                    // Animated check circle (scale pop + stroke draw)
                    '<div class="modal-icon-circle w-20 h-20 rounded-full bg-emerald-50 flex items-center justify-center mb-5 ring-8 ring-emerald-50/50">' +
                      '<svg viewBox="0 0 24 24" class="w-11 h-11 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">' +
                        '<path class="modal-check-path" d="M4 12.5 L10 18.5 L20 6.5"/>' +
                      '</svg>' +
                    '</div>' +
                    '<h2 class="modal-title text-[21px] font-extrabold text-slate-900 mb-1.5 leading-tight">Pembayaran Berhasil</h2>' +
                    '<p class="modal-subtitle text-[13.5px] text-slate-500 leading-relaxed mb-5 px-2">Transaksi sedang diproses. Silahkan periksa riwayat transaksi kamu.</p>' +
                    // Detail card - fade up
                    '<div class="modal-body w-full rounded-2xl bg-slate-50 border border-slate-100 p-4 text-left divide-y divide-slate-200/70">' +
                      '<div class="flex justify-between items-center py-2.5 text-[14px]"><span class="text-slate-500">ID Transaksi</span><span class="font-bold text-slate-900 font-mono">' + trxId + '</span></div>' +
                      '<div class="flex justify-between items-center py-2.5 text-[14px]"><span class="text-slate-500">Nama Pelanggan</span><span class="font-semibold text-slate-800 text-right ml-2 truncate max-w-[60%]">' + namaPelanggan + '</span></div>' +
                      '<div class="flex justify-between items-center py-2.5 text-[14px]"><span class="text-slate-500">Saldo Kamu Berkurang</span><span class="font-extrabold text-red-500 text-[16px]">' + saldoBerkurang + '</span></div>' +
                    '</div>' +
                  '</div>';

                showModal({
                  bodyHtml: successHtml,
                  variant: 'success',
                  btnText: 'Tutup',
                  btnColor: 'emerald',
                  onClose: function() {
                    doBatal();
                    document.getElementById('nope').focus();
                  }
                });
              } else {
                showFailDialog('Transaksi Gagal', myJsn.error_msg || 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
                document.getElementById('btnProcessing').classList.add('hidden');
                document.getElementById('btnActionGroup').classList.remove('hidden');
              }
            } catch(e) {
              console.error('[doBayar] parse error:', e);
              var rawResp = (xhr.responseText || '').substring(0, 500);
              var debugInfo = '<div class="mt-4 w-full text-left"><details class="text-left"><summary class="text-[13px] text-slate-400 cursor-pointer">Detail teknis</summary><pre class="mt-2 max-h-40 overflow-auto rounded-lg bg-slate-100 p-3 text-[12px] text-slate-700 whitespace-pre-wrap break-all">' +
                'Parse error: ' + (e.message || e) + '\n\nRaw response:\n' + rawResp + '</pre></details></div>';
              showFailDialog('Respons Tidak Valid', 'Server mengembalikan data yang tidak dapat diproses. Silakan coba lagi.' + debugInfo);
              document.getElementById('btnProcessing').classList.add('hidden');
              document.getElementById('btnActionGroup').classList.remove('hidden');
            }
          } else {
            showFailDialog('Koneksi Gagal', 'Tidak dapat terhubung ke server (HTTP ' + xhr.status + '). Periksa koneksi Anda dan coba lagi.');
            document.getElementById('btnProcessing').classList.add('hidden');
            document.getElementById('btnActionGroup').classList.remove('hidden');
          }
        }
      };
      xhr.send();
    }
  </script>
</body>
</html>
