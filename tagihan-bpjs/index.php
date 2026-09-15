<?php
@ob_start();
error_reporting(E_ERROR | E_PARSE);
require_once("../config.php");
// session_destroy();
require_once("../_session.php");
require_once("../lib/ApiV2.php");
$api_v2 = new ApiV2($user_jwt);


$file_me = "index.php";
$kode_produk = 'BBPJSTV'; //data static kode produk BPJS Kesehatan
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
    $lyt_button_link = "$c_url/tagihan-bpjs/$file_me";
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
                // Debug log: tulis raw response supaya bisa dicek kalau ada keluhan
                if (isset($app) && method_exists($app, 'simpan_file')) {
                    $app->simpan_file("pay_bpjs_" . preg_replace('/[^A-Za-z0-9]/', '', $trx_id) . ".json", (string)$bayar_tagihan_http);
                }
            } else {
                $data_r = ['status' => 0, "error_msg" => "trx_id tidak di temukan, refresh halaman ini"];
            }
        } else if ($msg === "fav_list") {
            $cari    = $_REQUEST['cari'] ?? '';
            $last_id = (int)($_REQUEST['last_id'] ?? 0);
            $fav_http = $api_v2->list_nomor_pelanggan($cari, 300, $last_id);
            $fav = json_decode($fav_http, true);
            if (isset($fav['status']) && $fav['status'] == 1) {
                $list = $fav['data']['data']['data'] ?? [];
                $next = $fav['data']['data']['last_id'] ?? 0;
                $data_r = ['status' => 1, 'data' => $list, 'last_id' => $next];
            } else {
                $data_r = ['status' => 1, 'data' => [], 'last_id' => 0, 'note' => $fav['error_msg'] ?? ''];
            }
        } else if ($msg === "fav_add") {
            $nama = trim($_REQUEST['nama'] ?? '');
            $hp   = trim($_REQUEST['hp'] ?? '');
            if ($nama === '' || $hp === '') {
                $data_r = ['status' => 0, "error_msg" => "Nama dan nomor wajib diisi"];
            } else {
                $add_http = $api_v2->simpan_nomor_pelanggan($nama, $hp);
                $add = json_decode($add_http, true);
                $data_r = isset($add['status']) ? $add : ['status' => 0, "error_msg" => "Gagal menyimpan favorit, server tidak merespon"];
            }
        } else if ($msg === "fav_update") {
            $id   = (int)($_REQUEST['id'] ?? 0);
            $nama = trim($_REQUEST['nama'] ?? '');
            $hp   = trim($_REQUEST['hp'] ?? '');
            if ($id <= 0 || $nama === '' || $hp === '') {
                $data_r = ['status' => 0, "error_msg" => "Data favorit tidak lengkap"];
            } else {
                $upd_http = $api_v2->update_nomor_pelanggan($id, $nama, $hp);
                $upd = json_decode($upd_http, true);
                $data_r = isset($upd['status']) ? $upd : ['status' => 0, "error_msg" => "Gagal mengubah favorit, server tidak merespon"];
            }
        } else if ($msg === "fav_delete") {
            $hp = trim($_REQUEST['hp'] ?? '');
            if ($hp === '') {
                $data_r = ['status' => 0, "error_msg" => "Nomor tidak ditemukan"];
            } else {
                $del_http = $api_v2->hapus_nomor_pelanggan($hp);
                $del = json_decode($del_http, true);
                $data_r = isset($del['status']) ? $del : ['status' => 0, "error_msg" => "Gagal menghapus favorit, server tidak merespon"];
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
  <title><?= htmlspecialchars($product_name ?? 'Bayar Tagihan BPJS') ?></title>
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
    #customModal:not(.hidden) > div {
      animation: modalPopIn 0.32s cubic-bezier(0.34, 1.56, 0.64, 1) both;
    }
    @keyframes modalPopIn {
      0%   { opacity: 0; transform: scale(0.85) translateY(8px); }
      100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    @keyframes modalPopOut {
      0%   { opacity: 1; transform: scale(1) translateY(0); }
      100% { opacity: 0; transform: scale(0.92) translateY(4px); }
    }

    .modal-icon-circle {
      transform-origin: center;
      animation: iconCircleIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) 0.1s both;
    }
    @keyframes iconCircleIn {
      0%   { opacity: 0; transform: scale(0.4); }
      60%  { opacity: 1; transform: scale(1.08); }
      100% { opacity: 1; transform: scale(1); }
    }

    .modal-check-path {
      stroke-dasharray: 28;
      stroke-dashoffset: 28;
      animation: checkDraw 0.55s cubic-bezier(0.65, 0, 0.45, 1) 0.4s forwards;
    }
    @keyframes checkDraw {
      to { stroke-dashoffset: 0; }
    }

    .modal-check-circle-bg {
      transform-origin: center;
      animation: iconCircleIn 0.45s cubic-bezier(0.34, 1.56, 0.64, 1) 0.15s both;
    }

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

    .modal-title    { animation: fadeUp 0.35s ease-out 0.25s both; }
    .modal-subtitle { animation: fadeUp 0.35s ease-out 0.35s both; }
    .modal-body     { animation: fadeUp 0.4s ease-out 0.45s both; }
    .modal-btn      { animation: fadeUp 0.35s ease-out 0.55s both; }
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(8px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    #customModal { animation: backdropIn 0.2s ease-out both; }
    @keyframes backdropIn {
      from { opacity: 0; }
      to   { opacity: 1; }
    }

    #favListModal:not(.hidden) { animation: backdropIn 0.2s ease-out both; }
    #favListModal .sheet-panel { animation: sheetUp 0.34s cubic-bezier(0.16, 1, 0.3, 1) both; }
    @keyframes sheetUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
    @keyframes sheetDown { from { transform: translateY(0); } to { transform: translateY(100%); } }
    #favFormModal:not(.hidden) { animation: backdropIn 0.2s ease-out both; }
    #favFormModal .form-panel { animation: modalPopIn 0.32s cubic-bezier(0.34, 1.56, 0.64, 1) both; }
    #favConfirmModal:not(.hidden) { animation: backdropIn 0.2s ease-out both; }
    #favConfirmModal .confirm-panel { animation: modalPopIn 0.32s cubic-bezier(0.34, 1.56, 0.64, 1) both; }
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
      <p class="text-[13px] font-extrabold text-slate-700" id="loadingText">Memproses…</p>
    </div>
  </div>

  <!-- Toast error -->
  <div id="toastError" class="fixed top-4 left-1/2 z-[9998] hidden -translate-x-1/2 max-w-[90vw] w-full px-4">
    <div class="flex items-start gap-3 rounded-xl bg-red-600 px-4 py-3 shadow-lg text-white">
      <svg viewBox="0 0 24 24" class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
      <p id="toastErrorMsg" class="text-[13px] font-semibold leading-5"></p>
    </div>
  </div>

  <!-- Custom modal (success / fail / info) - z paling atas agar tidak tertindih modal favorit -->
  <div id="customModal" class="fixed inset-0 z-[10010] hidden items-center justify-center bg-black/50 backdrop-blur-[2px] px-4">
    <div class="w-[92vw] max-w-[400px] rounded-3xl bg-white px-7 py-7 shadow-2xl ring-1 ring-slate-900/5">
      <div id="customModalBody"></div>
      <button id="customModalBtn" type="button" class="mt-6 w-full rounded-xl bg-slate-900 hover:bg-slate-800 active:scale-[0.99] text-white text-[15px] font-bold py-3.5 px-5 transition">
        Tutup
      </button>
    </div>
  </div>

  <main class="relative w-full min-h-screen bg-slate-50 pb-36">

    <!-- Header -->
    <header class="relative z-10 px-5 pt-4 pb-3 bg-white border-b border-slate-100">
      <div class="flex items-center gap-3">
        <div class="h-1.5 flex-1 rounded-full bg-slate-200 overflow-hidden">
          <div class="h-full rounded-full bg-brand transition-all duration-300" id="progressBar" style="width: 33%"></div>
        </div>
      </div>
    </header>

    <!-- Product info -->
    <section class="px-6 pt-5 pb-4 bg-white">
      <div class="flex items-center gap-3">
        <div class="h-12 w-12 rounded-2xl shadow-card bg-brand/8 flex items-center justify-center overflow-hidden shrink-0">
          <img src="<?= htmlspecialchars($product_logo ?? '') ?>" class="h-full w-full object-contain" alt="BPJS" onerror="this.style.display='none';this.parentElement.innerHTML='<svg class=\'h-6 w-6 text-brand\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.8\'><path d=\'M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z\'/></svg>'"/>
        </div>
        <div>
          <h1 class="text-[17px] font-bold text-slate-900 leading-tight">Bayar Tagihan BPJS Kesehatan</h1>
          <p class="text-[12px] text-mutedText mt-0.5"><?= htmlspecialchars($code) ?></p>
        </div>
      </div>
    </section>

    <!-- Input section -->
    <section class="px-4 pb-4">
      <div class="rounded-[16px] border border-slate-200 bg-white shadow-soft overflow-hidden">
        <div class="px-5 pt-5 pb-4" id="inputSection">
          <label class="text-[13px] font-semibold text-slate-700">Nomor Peserta BPJS</label>
          <div class="mt-2 flex gap-2">
            <input
              type="tel"
              inputmode="numeric"
              pattern="[0-9]*"
              id="nope"
              class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-[15px] text-slate-800 placeholder-slate-400 outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 transition"
              placeholder="Masukkan nomor peserta"
              autocomplete="off"
            />
            <button type="button" onclick="showInfo()" class="grid h-[50px] w-[50px] shrink-0 place-items-center rounded-xl border border-slate-200 bg-slate-50 text-slate-500 transition hover:bg-slate-100">
              <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
            </button>
          </div>
          <div class="mt-3 flex items-center gap-2">
            <button id="btnFav" type="button" onclick="openFavList()" class="inline-flex items-center gap-1.5 rounded-xl border border-amber-200 bg-amber-50 px-3.5 py-2 text-[13px] font-bold text-amber-700 transition hover:bg-amber-100 active:scale-[0.99] disabled:opacity-60 disabled:cursor-wait">
              <svg id="btnFavStar" viewBox="0 0 24 24" class="h-4 w-4" fill="currentColor"><path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
              <svg id="btnFavSpin" class="hidden h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
              <span id="btnFavLabel">Favorit</span>
            </button>
            <button type="button" onclick="openFavForm()" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-[13px] font-bold text-slate-600 transition hover:bg-slate-50 active:scale-[0.99]">
              <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
              Simpan Nomor
            </button>
          </div>
          <input type="hidden" id="csrf" value="<?= $app->csrf(); ?>" />
          <p class="text-[12px] text-mutedText mt-2">Masukkan salah satu nomor peserta BPJS Kesehatan</p>
        </div>

        <!-- Info card -->
        <div class="px-5 pb-5" id="infoCard">
          <div class="rounded-xl bg-emerald-50 border border-emerald-100 px-4 py-3 flex items-start gap-3">
            <div class="shrink-0 mt-0.5">
              <svg class="h-4 w-4 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-[12px] text-emerald-700 leading-snug">
                Bayar tagihan BPJS Kesehatan di bukakios, kamu otomatis mendapatkan keuntungan dari biaya admin.
              </p>
            </div>
          </div>
          <div class="mt-3 rounded-xl bg-amber-50 border border-amber-100 px-4 py-3 flex items-start gap-3">
            <div class="shrink-0 mt-0.5">
              <svg class="h-4 w-4 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <p class="text-[12px] text-amber-700 leading-snug">
              Iuran BPJS Kesehatan jatuh tempo setiap tanggal 10. Pastikan bayar sebelum tanggal tersebut agar tidak terkena denda pelayanan.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Error message -->
    <div id="errorMsg" class="hidden mx-4 mb-3 rounded-xl bg-red-50 border border-red-100 px-4 py-3">
      <div class="flex items-start gap-3">
        <svg class="h-4 w-4 text-red-400 mt-0.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
        <p id="errorText" class="text-[13px] text-red-600 font-medium leading-snug"></p>
      </div>
    </div>

    <!-- Detail tagihan (hidden until CEK) -->
    <section id="detailSection" class="hidden px-4 pb-6">
      <div class="rounded-[20px] border border-slate-200 bg-white shadow-soft overflow-hidden">

        <!-- Header -->
        <div class="px-5 py-4 bg-brand text-white">
          <h2 class="text-[14px] font-bold">Detail Tagihan</h2>
        </div>

        <!-- Billing rows -->
        <div class="divide-y divide-slate-100">
          <div class="flex items-center justify-between px-5 py-3">
            <span class="text-[13px] text-slate-600">Total Tagihan</span>
            <span class="text-[15px] font-extrabold text-brand" id="tot_ta">-</span>
          </div>
          <div class="flex items-center justify-between px-5 py-3">
            <span class="text-[13px] text-slate-600">Nama Pelanggan</span>
            <span class="text-[13px] font-semibold text-slate-800" id="nama">-</span>
          </div>
          <div class="flex items-center justify-between px-5 py-3">
            <span class="text-[13px] text-slate-600">Jumlah Peserta</span>
            <span class="text-[13px] font-semibold text-slate-800" id="jumlah_peserta">-</span>
          </div>
          <div class="flex items-center justify-between px-5 py-3">
            <span class="text-[13px] text-slate-600">Lembar Tagihan</span>
            <span class="text-[13px] font-semibold text-slate-800" id="lembar_tagihan">-</span>
          </div>
          <div class="flex items-center justify-between px-5 py-3">
            <span class="text-[13px] text-slate-600">Periode</span>
            <span class="text-[13px] font-semibold text-slate-800" id="periode">-</span>
          </div>
          <div class="flex items-center justify-between px-5 py-3">
            <span class="text-[13px] text-slate-600">Tagihan</span>
            <span class="text-[13px] font-semibold text-slate-800" id="tagihan">-</span>
          </div>
          <div class="flex items-center justify-between px-5 py-3">
            <span class="text-[13px] text-slate-600">Biaya Admin</span>
            <span class="text-[13px] font-semibold text-slate-800" id="biaya">-</span>
          </div>
          <div class="flex items-center justify-between px-5 py-3">
            <span class="text-[13px] text-slate-600">Diskon Biaya Admin</span>
            <span class="text-[13px] font-semibold text-emerald-600" id="potongan">-</span>
          </div>
          <div class="flex items-center justify-between px-5 py-3 bg-slate-50">
            <span class="text-[14px] font-bold text-slate-800">Total Bayar</span>
            <span class="text-[17px] font-extrabold text-brand" id="tot_ka">-</span>
          </div>
        </div>
      </div>

      <!-- Profit detail -->
      <div class="mt-3 rounded-[20px] border border-slate-200 bg-white shadow-soft overflow-hidden">
        <div class="px-5 py-4 bg-slate-800 text-white">
          <h2 class="text-[14px] font-bold">Detail Keuntungan</h2>
        </div>
        <div class="divide-y divide-slate-100">
          <div class="flex items-center justify-between px-5 py-3">
            <span class="text-[13px] text-slate-600">Pelanggan Kamu Bayar</span>
            <span class="text-[13px] font-semibold text-slate-800" id="tot_ta2">-</span>
          </div>
          <div class="flex items-center justify-between px-5 py-3">
            <span class="text-[13px] text-slate-600">Saldo Kamu Berkurang</span>
            <span class="text-[13px] font-semibold text-red-500" id="tot_ka2">-</span>
          </div>
          <div class="flex items-center justify-between px-5 py-3 bg-emerald-50/50">
            <span class="text-[14px] font-bold text-emerald-700">Profit Kamu</span>
            <span class="text-[17px] font-extrabold text-emerald-600" id="profit">-</span>
          </div>
        </div>
      </div>

      <!-- Biaya layanan -->
      <div class="mt-3 rounded-[20px] border border-slate-200 bg-white shadow-soft px-5 py-4">
        <h3 class="text-[13px] font-bold text-slate-700 mb-1">Buat Biaya Layanan Toko/Kios</h3>
        <div class="relative">
          <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-[14px] font-semibold text-slate-500">Rp</span>
          <input
            type="text"
            id="biaya_profit"
            inputmode="numeric"
            autocomplete="off"
            oninput="onBiayaTokoInput()"
            class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-10 py-2.5 text-[14px] text-slate-800 outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 transition"
            value="0"
          />
          <button
            type="button"
            id="biaya_profit_clear"
            onclick="clearBiayaToko()"
            class="hidden absolute right-3 top-1/2 -translate-y-1/2 h-6 w-6 items-center justify-center rounded-full bg-slate-200 text-slate-500 hover:bg-slate-300 active:scale-95 transition"
            aria-label="Hapus biaya toko"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
          </button>
        </div>
        <p class="text-[11px] text-mutedText mt-1.5">* Akan muncul di struk transaksi</p>
      </div>

      <!-- Rincian sumber profit -->
      <div class="mt-3 rounded-[20px] border border-emerald-200 bg-emerald-50/60 shadow-soft overflow-hidden">
        <div class="px-5 py-3 border-b border-emerald-100">
          <h3 class="text-[14px] font-bold text-emerald-800">Sumber Keuntungan Kamu</h3>
          <p class="text-[12px] text-emerald-700/80 mt-0.5">Kamu bisa dapat profit double dari 2 sumber:</p>
        </div>
        <div class="divide-y divide-emerald-100">
          <div class="flex items-center justify-between px-5 py-2.5">
            <div>
              <span class="text-[14px] font-semibold text-slate-700">1. Potongan Harga Produk</span>
              <p class="text-[12px] text-slate-500">Diskon harga dari produk</p>
            </div>
            <span class="text-[14px] font-bold text-emerald-600" id="src_profit_be">-</span>
          </div>
          <div class="flex items-center justify-between px-5 py-2.5">
            <div>
              <span class="text-[14px] font-semibold text-slate-700">2. Biaya Layanan Toko</span>
              <p class="text-[12px] text-slate-500">Biaya yang kamu tetapkan sendiri</p>
            </div>
            <span class="text-[14px] font-bold text-emerald-600" id="src_profit_toko">-</span>
          </div>
          <div class="flex items-center justify-between px-5 py-2.5 bg-emerald-100/50">
            <span class="text-[15px] font-extrabold text-emerald-800">Total Profit Kamu</span>
            <span class="text-[18px] font-extrabold text-emerald-700" id="src_profit_total">-</span>
          </div>
        </div>
      </div>
    </section>

  </main>

  <!-- Fixed footer -->
  <footer class="fixed bottom-0 left-0 right-0 z-10 bg-white border-t border-slate-200 shadow-soft px-4 py-3">
    <button id="btnCek" onclick="doCek()" class="w-full rounded-2xl bg-brand py-3.5 text-[15px] font-bold text-white shadow-cta transition hover:bg-brandDark active:scale-[0.99]">
      Cek Tagihan
    </button>

    <div id="paySummary" class="hidden mb-3 flex items-center justify-between rounded-2xl bg-emerald-50 border border-emerald-200 px-4 py-3">
      <div>
        <p class="text-[13px] font-semibold text-emerald-800">Total Pelanggan Kamu Bayar</p>
      </div>
      <span class="text-[18px] font-extrabold text-emerald-700" id="total_bayar_pelanggan">-</span>
    </div>

    <div id="btnActionGroup" class="hidden grid grid-cols-2 gap-3">
      <button id="btnBatal" onclick="doBatal()" class="rounded-2xl border border-slate-200 bg-white py-3.5 text-[14px] font-bold text-slate-600 transition hover:bg-slate-50 active:scale-[0.99]">
        Batal
      </button>
      <button id="btnBayar" onclick="doBayar()" class="rounded-2xl bg-emerald-500 py-3.5 text-[14px] font-bold text-white shadow-cta transition hover:bg-emerald-600 active:scale-[0.99]">
        Bayar Sekarang
      </button>
    </div>

    <div id="btnProcessing" class="hidden">
      <div class="flex items-center justify-center gap-3 rounded-2xl bg-slate-100 py-3.5">
        <svg class="h-5 w-5 animate-spin text-brand" viewBox="0 0 24 24" fill="none">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
        </svg>
        <span class="text-[14px] font-semibold text-slate-600">Memproses transaksi…</span>
      </div>
    </div>
  </footer>

  <!-- ===== Modal: Daftar Favorit (bottom sheet) ===== -->
  <div id="favListModal" class="fixed inset-0 z-[10001] hidden items-end justify-center bg-black/50 backdrop-blur-[2px]" onclick="onFavListBackdrop(event)">
    <div class="sheet-panel w-full sm:max-w-[480px] mx-auto max-h-[85vh] flex flex-col rounded-t-3xl bg-white shadow-2xl">
      <div class="flex justify-center pt-3 pb-1"><span class="h-1.5 w-10 rounded-full bg-slate-300"></span></div>
      <div class="flex items-center justify-between px-5 py-3 border-b border-slate-100">
        <h2 class="text-[17px] font-bold text-slate-900">Nomor Favorit</h2>
        <button type="button" onclick="closeFavList()" class="grid h-8 w-8 place-items-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition"><svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg></button>
      </div>
      <div class="px-5 pt-4"><div class="relative">
        <svg viewBox="0 0 24 24" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
        <input id="favSearch" type="text" oninput="onFavSearch()" placeholder="Cari nama atau nomor…" autocomplete="off" class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-9 pr-3 py-2.5 text-[14px] text-slate-800 placeholder-slate-400 outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 transition" />
      </div></div>
      <div id="favListBody" class="flex-1 overflow-y-auto px-3 py-3 min-h-[160px]"></div>
      <div class="px-5 py-4 border-t border-slate-100"><button type="button" onclick="openFavForm()" class="w-full rounded-xl bg-brand py-3 text-[16px] font-bold text-white shadow-cta transition hover:bg-brandDark active:scale-[0.99]">+ Tambah Favorit Baru</button></div>
    </div>
  </div>

  <div id="favFormModal" class="fixed inset-0 z-[10002] hidden items-center justify-center bg-black/50 backdrop-blur-[2px] px-4" onclick="onFavFormBackdrop(event)">
    <div class="form-panel w-[92vw] max-w-[400px] rounded-3xl bg-white px-6 py-6 shadow-2xl">
      <div class="flex items-center justify-between mb-4">
        <h2 id="favFormTitle" class="text-[18px] font-bold text-slate-900">Tambah Favorit</h2>
        <button type="button" onclick="closeFavForm()" class="grid h-8 w-8 place-items-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition"><svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg></button>
      </div>
      <input type="hidden" id="favFormId" value="" />
      <label class="text-[14px] font-semibold text-slate-600">Nama</label>
      <input id="favFormNama" type="text" autocomplete="off" placeholder="Contoh: Rumah, Toko, Ibu" class="mt-1.5 mb-3 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-[15px] text-slate-800 placeholder-slate-400 outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 transition" />
      <label class="text-[14px] font-semibold text-slate-600">Nomor Peserta</label>
      <input id="favFormHp" type="tel" inputmode="numeric" autocomplete="off" placeholder="Masukkan nomor" class="mt-1.5 mb-5 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-[15px] text-slate-800 placeholder-slate-400 outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 transition" />
      <div class="flex gap-2">
        <button id="favFormDelete" type="button" onclick="submitFavDelete()" class="hidden flex-1 rounded-xl border border-red-200 bg-red-50 py-3 text-[16px] font-bold text-red-600 transition hover:bg-red-100 active:scale-[0.99]">Hapus</button>
        <button type="button" onclick="submitFavForm()" class="flex-1 rounded-xl bg-brand py-3 text-[16px] font-bold text-white shadow-cta transition hover:bg-brandDark active:scale-[0.99]">Simpan</button>
      </div>
    </div>
  </div>

  <div id="favConfirmModal" class="fixed inset-0 z-[10003] hidden items-center justify-center bg-black/50 backdrop-blur-[2px] px-4" onclick="onFavConfirmBackdrop(event)">
    <div class="confirm-panel w-[92vw] max-w-[360px] rounded-3xl bg-white px-6 py-6 shadow-2xl">
      <div class="flex flex-col items-center text-center">
        <div class="w-16 h-16 rounded-full flex items-center justify-center mb-4 ring-8 ring-red-50/60 bg-red-50"><svg viewBox="0 0 24 24" class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v6M14 11v6"/></svg></div>
        <h2 class="text-[19px] font-extrabold text-slate-900 mb-1.5">Hapus Favorit?</h2>
        <p class="text-[14.5px] text-slate-500 leading-relaxed mb-5 px-2">Nomor <span id="favConfirmName" class="font-semibold text-slate-700"></span> akan dihapus dari daftar favorit.</p>
      </div>
      <div class="flex gap-2"><button type="button" onclick="closeFavConfirm()" class="flex-1 rounded-xl border border-slate-200 bg-white py-3 text-[16px] font-bold text-slate-600 transition hover:bg-slate-50 active:scale-[0.99]">Batal</button><button type="button" onclick="confirmFavDelete()" class="flex-1 rounded-xl bg-red-500 py-3 text-[16px] font-bold text-white shadow-cta transition hover:bg-red-600 active:scale-[0.99]">Hapus</button></div>
    </div>
  </div>

  <script>
    // State
    var trx_id = "";
    var profitBE = 0;
    var totalBayarBuyerBE = 0;

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

    function getBiayaToko() {
      var el = document.getElementById('biaya_profit');
      return el ? parseNum(el.value) : 0;
    }

    function formatRibuan(angka) {
      var n = Number(angka) || 0;
      return n.toLocaleString('id-ID');
    }

    function onBiayaTokoInput() {
      var el = document.getElementById('biaya_profit');
      if (!el) return;
      var angka = parseNum(el.value);
      el.value = formatRibuan(angka);
      toggleClearBiayaToko();
      updateProfitSources();
    }

    function toggleClearBiayaToko() {
      var btn = document.getElementById('biaya_profit_clear');
      if (!btn) return;
      var val = getBiayaToko();
      btn.classList.toggle('hidden', val <= 0);
      btn.classList.toggle('flex', val > 0);
    }

    function clearBiayaToko() {
      var el = document.getElementById('biaya_profit');
      if (el) el.value = '0';
      toggleClearBiayaToko();
      updateProfitSources();
      if (el) el.focus();
    }

    function updateProfitSources() {
      var toko = getBiayaToko();
      var totalProfit = (Number(profitBE) || 0) + toko;
      var beEl = document.getElementById('src_profit_be');
      var tokoEl = document.getElementById('src_profit_toko');
      var totalEl = document.getElementById('src_profit_total');
      if (beEl) beEl.textContent = formatRupiah(profitBE);
      if (tokoEl) tokoEl.textContent = formatRupiah(toko);
      if (totalEl) totalEl.textContent = formatRupiah(totalProfit);

      var payEl = document.getElementById('total_bayar_pelanggan');
      if (payEl) payEl.textContent = formatRupiah((Number(totalBayarBuyerBE) || 0) + toko);
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
        title: 'Cek Nomor Peserta BPJS',
        message: 'Nomor peserta BPJS Kesehatan terdiri dari 13 digit angka yang tertera pada kartu BPJS/KIS. Kamu bisa menggunakan salah satu nomor peserta dalam satu keluarga.',
        variant: 'info',
        btnText: 'Ok'
      });
    }

    /**
     * Custom modal.
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

      var iconHtml = '';
      var iconAnimClass = 'modal-icon-circle';
      if (variant === 'success') {
        iconHtml = '<svg viewBox="0 0 24 24" class="w-11 h-11 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">' +
                     '<path class="modal-check-path" d="M4 12.5 L10 18.5 L20 6.5"/>' +
                   '</svg>';
      } else if (variant === 'fail') {
        iconHtml = '<svg viewBox="0 0 24 24" class="w-11 h-11 text-red-500" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">' +
                     '<path class="modal-fail-path" d="M6 6 L18 18 M18 6 L6 18"/>' +
                   '</svg>';
      } else {
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
        inner = bodyHtml;
      } else {
        inner =
          '<div class="flex flex-col items-center text-center">' +
            '<div class="w-20 h-20 rounded-full flex items-center justify-center mb-5 ring-8 ' + iconRing + ' ' + iconAnimClass + '">' + iconHtml + '</div>' +
            '<h2 class="modal-title text-[20px] font-extrabold leading-tight text-slate-900 mb-2">' + titleText + '</h2>' +
            '<p class="modal-subtitle text-[13.5px] text-slate-600 leading-relaxed max-w-[300px] mx-auto font-medium px-2">' + messageText + '</p>' +
          '</div>';
      }

      var bodyEl = document.getElementById('customModalBody');
      var btnEl = document.getElementById('customModalBtn');
      var modalEl = document.getElementById('customModal');

      bodyEl.innerHTML = inner;
      btnEl.textContent = btnText;
      btnEl.className = 'modal-btn mt-6 w-full rounded-xl text-white text-[15px] font-bold py-3.5 px-5 transition active:scale-[0.99] ' + btnColorClass;

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

      var backdropHandler = function(e) {
        if (e.target === modalEl) closeAndFire();
      };
      modalEl.addEventListener('click', backdropHandler);

      var escHandler = function(e) {
        if (e.key === 'Escape' || e.keyCode === 27) closeAndFire();
      };
      document.addEventListener('keydown', escHandler);

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

    // CEK
    function doCek() {
      var id_pelanggan = document.getElementById('nope').value.trim();
      if (!id_pelanggan) {
        showToastError('Nomor peserta tidak boleh kosong');
        document.getElementById('nope').focus();
        return;
      }

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
      var customData = d.custom_data || data.custom_data || {};
      trx_id = d.trx_id || data.trx_id || "";

      // Hitung diskon biaya admin (rumus wv3)
      var price     = parseNum(productDetail.price);
      var priceAdd  = parseNum(productDetail.price_add);
      var admin     = parseNum(d.admin);
      var bulan     = parseNum(d.jml_bulan) || 1;
      var diskonAngka = admin - ((price + priceAdd) * bulan);

      // Tagihan dan total bayar dari API
      var tagihan     = parseNum(d.tagihan);
      var totalBayarPelanggan = parseNum(d.total_bayar);

      // Total Bayar Kamu (saldo seller) = tagihan + admin - diskon (rumus wv3)
      var totalBayarKamuAngka = tagihan + admin - diskonAngka;
      var profitAngka = parseNum(customData.profit != null ? customData.profit : d.profit);
      if (!profitAngka) profitAngka = totalBayarPelanggan - totalBayarKamuAngka;
      var priceSell = parseNum(customData.price_sell != null ? customData.price_sell : d.price_sell);
      totalBayarBuyerBE = parseNum(customData.total_bayar_buyer != null ? customData.total_bayar_buyer : d.total_bayar_buyer);
      if (!totalBayarBuyerBE) totalBayarBuyerBE = totalBayarPelanggan;
      profitBE = profitAngka;

      var biayaProfitEl = document.getElementById('biaya_profit');
      if (biayaProfitEl) biayaProfitEl.value = formatRibuan(priceSell);
      toggleClearBiayaToko();
      updateProfitSources();

      // Tampilkan ke UI
      document.getElementById('tot_ta').textContent         = formatRupiah(totalBayarPelanggan);
      document.getElementById('nama').textContent           = d.customer_name || d.nama_pelanggan || '-';
      document.getElementById('jumlah_peserta').textContent = (d.jml_peserta != null ? d.jml_peserta + ' Orang' : (d.jumlah_peserta != null ? d.jumlah_peserta + ' Orang' : '-'));
      document.getElementById('lembar_tagihan').textContent = (d.jml_bulan != null ? d.jml_bulan + ' Bulan' : '-');
      document.getElementById('periode').textContent        = d.bln_th || d.periode || '-';
      document.getElementById('tagihan').textContent        = formatRupiah(tagihan);
      document.getElementById('biaya').textContent          = formatRupiah(admin);
      document.getElementById('potongan').textContent       = '- ' + formatRupiah(diskonAngka);
      document.getElementById('tot_ka').textContent         = formatRupiah(totalBayarKamuAngka);
      document.getElementById('tot_ka2').textContent        = formatRupiah(totalBayarKamuAngka);
      document.getElementById('tot_ta2').textContent        = formatRupiah(totalBayarPelanggan);
      document.getElementById('profit').textContent         = formatRupiah(profitAngka);
    }

    function switchToAction() {
      document.getElementById('inputSection').classList.add('hidden');
      document.getElementById('infoCard').classList.add('hidden');
      document.getElementById('detailSection').classList.remove('hidden');
      document.getElementById('btnCek').classList.add('hidden');
      document.getElementById('paySummary').classList.remove('hidden');
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
      document.getElementById('paySummary').classList.add('hidden');
      document.getElementById('btnProcessing').classList.add('hidden');
      document.getElementById('inputSection').classList.remove('hidden');
      document.getElementById('infoCard').classList.remove('hidden');
      document.getElementById('btnCek').classList.remove('hidden');
      document.getElementById('btnCek').disabled = false;
      document.getElementById('nope').disabled = false;
      document.getElementById('nope').value = '';
      profitBE = 0;
      totalBayarBuyerBE = 0;
      document.getElementById('biaya_profit').value = '0';
      toggleClearBiayaToko();
      updateProfitSources();
      document.getElementById('progressBar').style.width = '33%';
      document.getElementById('nope').focus();
    }

    // BAYAR
    function doBayar() {
      if (!trx_id) { showToastError('Silakan cek tagihan terlebih dahulu'); return; }

      var csrf       = document.getElementById('csrf').value;
      var id_pel     = document.getElementById('nope').value.trim();
      var biaya_toko = String(getBiayaToko());

      document.getElementById('btnActionGroup').classList.add('hidden');
      document.getElementById('paySummary').classList.add('hidden');
      document.getElementById('btnProcessing').classList.remove('hidden');

      var xhr = new XMLHttpRequest();
      xhr.open('GET', '<?= $file_me ?>?msg=bayar&biaya_toko=' + encodeURIComponent(biaya_toko) + '&id_pelanggan=' + encodeURIComponent(id_pel) + '&csrf=' + encodeURIComponent(csrf) + '&trx_id=' + encodeURIComponent(trx_id), true);
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
          if (xhr.status === 200) {
            try {
              var rawText = (xhr.responseText || '').replace(/^\uFEFF/, '').trim();
              var myJsn = JSON.parse(rawText);
              if (myJsn.status == 1) {
                var namaPelanggan = document.getElementById('nama').textContent || '-';
                var saldoBerkurang = document.getElementById('tot_ka2').textContent || 'Rp 0';
                var trxId         = myJsn.data || myJsn.trx_id || trx_id;

                var successHtml =
                  '<div class="flex flex-col items-center text-center">' +
                    '<div class="modal-icon-circle w-20 h-20 rounded-full bg-emerald-50 flex items-center justify-center mb-5 ring-8 ring-emerald-50/50">' +
                      '<svg viewBox="0 0 24 24" class="w-11 h-11 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">' +
                        '<path class="modal-check-path" d="M4 12.5 L10 18.5 L20 6.5"/>' +
                      '</svg>' +
                    '</div>' +
                    '<h2 class="modal-title text-[20px] font-extrabold text-slate-900 mb-1.5 leading-tight">Pembayaran Berhasil</h2>' +
                    '<p class="modal-subtitle text-[13.5px] text-slate-500 leading-relaxed mb-5 px-2">Transaksi sedang diproses. Silahkan periksa riwayat transaksi kamu.</p>' +
                    '<div class="modal-body w-full rounded-2xl bg-slate-50 border border-slate-100 p-4 text-left divide-y divide-slate-200/70">' +
                      '<div class="flex justify-between items-center py-2.5 text-[13px]"><span class="text-slate-500">ID Transaksi</span><span class="font-bold text-slate-900 font-mono">' + trxId + '</span></div>' +
                      '<div class="flex justify-between items-center py-2.5 text-[13px]"><span class="text-slate-500">Nama Pelanggan</span><span class="font-semibold text-slate-800 text-right ml-2 truncate max-w-[60%]">' + namaPelanggan + '</span></div>' +
                      '<div class="flex justify-between items-center py-2.5 text-[13px]"><span class="text-slate-500">Saldo Kamu Berkurang</span><span class="font-extrabold text-red-500 text-[15px]">' + saldoBerkurang + '</span></div>' +
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
                document.getElementById('paySummary').classList.remove('hidden');
              }
            } catch(e) {
              console.error('[doBayar] parse error:', e);
              var rawResp = (xhr.responseText || '').substring(0, 500);
              var debugInfo = '<div class="mt-4 w-full text-left"><details class="text-left"><summary class="text-[12px] text-slate-400 cursor-pointer">Detail teknis</summary><pre class="mt-2 max-h-40 overflow-auto rounded-lg bg-slate-100 p-3 text-[11px] text-slate-700 whitespace-pre-wrap break-all">' +
                'Parse error: ' + (e.message || e) + '\n\nRaw response:\n' + rawResp + '</pre></details></div>';
              showFailDialog('Respons Tidak Valid', 'Server mengembalikan data yang tidak dapat diproses. Silakan coba lagi.' + debugInfo);
              document.getElementById('btnProcessing').classList.add('hidden');
              document.getElementById('btnActionGroup').classList.remove('hidden');
              document.getElementById('paySummary').classList.remove('hidden');
            }
          } else {
            showFailDialog('Koneksi Gagal', 'Tidak dapat terhubung ke server (HTTP ' + xhr.status + '). Periksa koneksi Anda dan coba lagi.');
            document.getElementById('btnProcessing').classList.add('hidden');
            document.getElementById('btnActionGroup').classList.remove('hidden');
            document.getElementById('paySummary').classList.remove('hidden');
          }
        }
      };
      xhr.send();
    }

    /* =========================================================
       KONTAK FAVORIT (nomor pelanggan tersimpan)
       ========================================================= */
    var favData = [];
    var favPendingDeleteHp = null;

    function favReq(params, onDone) {
      var csrf = document.getElementById('csrf').value;
      var q = '<?= $file_me ?>?csrf=' + encodeURIComponent(csrf);
      for (var k in params) {
        if (Object.prototype.hasOwnProperty.call(params, k)) q += '&' + k + '=' + encodeURIComponent(params[k]);
      }
      var xhr = new XMLHttpRequest();
      xhr.open('GET', q, true);
      xhr.onreadystatechange = function() {
        if (xhr.readyState !== 4) return;
        if (xhr.status === 200) {
          try { onDone(null, JSON.parse((xhr.responseText || '').replace(/^\uFEFF/, '').trim())); }
          catch (e) { onDone('Respons server tidak valid', null); }
        } else {
          onDone('Koneksi gagal (HTTP ' + xhr.status + ')', null);
        }
      };
      xhr.send();
    }

    function esc(s) {
      return String(s == null ? '' : s)
        .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    function setFavBtnLoading(on) {
      var btn = document.getElementById('btnFav');
      if (!btn) return;
      document.getElementById('btnFavStar').classList.toggle('hidden', on);
      document.getElementById('btnFavSpin').classList.toggle('hidden', !on);
      document.getElementById('btnFavLabel').textContent = on ? 'Memuat…' : 'Favorit';
      btn.disabled = on;
    }

    function openFavList() {
      var btn = document.getElementById('btnFav');
      if (btn && btn.disabled) return;
      setFavBtnLoading(true);
      favReq({ msg: 'fav_list', cari: '', last_id: 0 }, function(err, res) {
        setFavBtnLoading(false);
        if (err) { showToastError(err); return; }
        favData = (res && res.data) || [];
        document.getElementById('favSearch').value = '';
        renderFavList('');
        showFavListSheet();
      });
    }

    function showFavListSheet() {
      var m = document.getElementById('favListModal');
      m.classList.remove('hidden'); m.classList.add('flex');
    }
    function closeFavList() {
      var m = document.getElementById('favListModal');
      var panel = m.querySelector('.sheet-panel');
      if (panel) {
        panel.style.animation = 'sheetDown 0.24s cubic-bezier(0.4, 0, 1, 1) forwards';
        setTimeout(function() { m.classList.add('hidden'); m.classList.remove('flex'); panel.style.animation = ''; }, 220);
      } else {
        m.classList.add('hidden'); m.classList.remove('flex');
      }
    }
    function onFavListBackdrop(e) { if (e.target === document.getElementById('favListModal')) closeFavList(); }
    function onFavFormBackdrop(e) { if (e.target === document.getElementById('favFormModal')) closeFavForm(); }
    function onFavConfirmBackdrop(e) { if (e.target === document.getElementById('favConfirmModal')) closeFavConfirm(); }
    function onFavSearch() { renderFavList(document.getElementById('favSearch').value.trim()); }
    function favListState(html) { document.getElementById('favListBody').innerHTML = html; }

    function refreshFavData() {
      favListState('<div class="flex items-center justify-center py-10 text-slate-400"><svg class="h-6 w-6 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg></div>');
      favReq({ msg: 'fav_list', cari: '', last_id: 0 }, function(err, res) {
        if (err) { favData = []; favListState(favEmpty(err)); return; }
        favData = (res && res.data) || [];
        renderFavList(document.getElementById('favSearch').value.trim());
      });
    }

    function renderFavList(cari) {
      var q = (cari || '').toLowerCase();
      var list = favData;
      if (q) {
        list = favData.filter(function(it) {
          return String(it.nama || '').toLowerCase().indexOf(q) !== -1 || String(it.hp || '').toLowerCase().indexOf(q) !== -1;
        });
      }
      if (!favData.length) { favListState(favEmpty('Belum ada nomor favorit.')); return; }
      if (!list.length) { favListState(favEmpty('Tidak ada favorit yang cocok.')); return; }
      var html = '';
      for (var i = 0; i < list.length; i++) {
        var it = list[i];
        var nama = esc(it.nama), hp = esc(it.hp), id = esc(it.id);
        var initial = (it.nama || '?').trim().charAt(0).toUpperCase();
        html += '<div class="group flex items-center gap-2 rounded-xl px-2 py-2.5 hover:bg-slate-50 transition">' +
          '<button type="button" class="flex flex-1 min-w-0 items-center gap-3 text-left" onclick="pickFav(\'' + hp + '\')">' +
            '<span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-brand/10 text-brand font-bold">' + esc(initial) + '</span>' +
            '<span class="min-w-0"><span class="block truncate text-[15px] font-semibold text-slate-800">' + nama + '</span><span class="block truncate text-[14px] text-slate-500">' + hp + '</span></span>' +
          '</button>' +
          '<button type="button" title="Ubah" class="grid h-8 w-8 shrink-0 place-items-center rounded-full text-slate-400 hover:bg-slate-200 hover:text-slate-700 transition" onclick="openFavForm({id:\'' + id + '\',nama:\'' + nama + '\',hp:\'' + hp + '\'})"><svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 013 3L7 19l-4 1 1-4z"/></svg></button>' +
          '<button type="button" title="Hapus" class="grid h-8 w-8 shrink-0 place-items-center rounded-full text-red-400 hover:bg-red-100 hover:text-red-600 transition" onclick="deleteFavQuick(\'' + hp + '\',\'' + nama + '\')"><svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg></button>' +
        '</div>';
      }
      favListState(html);
    }

    function favEmpty(text) {
      return '<div class="flex flex-col items-center justify-center py-10 text-center text-slate-400"><svg viewBox="0 0 24 24" class="mb-2 h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg><p class="text-[14px] px-6">' + esc(text) + '</p></div>';
    }

    function pickFav(hp) {
      document.getElementById('nope').value = String(hp).replace(/[^0-9]/g, '');
      closeFavList();
      document.getElementById('nope').focus();
    }

    function openFavForm(data) {
      var isEdit = data && data.id;
      document.getElementById('favFormTitle').textContent = isEdit ? 'Ubah Favorit' : 'Tambah Favorit';
      document.getElementById('favFormId').value = isEdit ? data.id : '';
      document.getElementById('favFormNama').value = isEdit ? data.nama : '';
      document.getElementById('favFormHp').value = isEdit ? data.hp : (document.getElementById('nope').value.trim() || '');
      document.getElementById('favFormDelete').classList.toggle('hidden', !isEdit);
      var m = document.getElementById('favFormModal');
      m.classList.remove('hidden'); m.classList.add('flex');
      document.getElementById('favFormNama').focus();
    }
    function closeFavForm() {
      var m = document.getElementById('favFormModal');
      m.classList.add('hidden'); m.classList.remove('flex');
    }

    function submitFavForm() {
      var id = document.getElementById('favFormId').value;
      var nama = document.getElementById('favFormNama').value.trim();
      var hp = document.getElementById('favFormHp').value.replace(/[^0-9]/g, '');
      if (!nama || !hp) { showToastError('Nama dan nomor wajib diisi'); return; }
      showLoading('Menyimpan…');
      var params = id ? { msg: 'fav_update', id: id, nama: nama, hp: hp } : { msg: 'fav_add', nama: nama, hp: hp };
      favReq(params, function(err, res) {
        hideLoading();
        if (err) { showToastError(err); return; }
        if (res && res.status == 1) {
          closeFavForm();
          if (!document.getElementById('favListModal').classList.contains('hidden')) refreshFavData();
          showModal({ title: 'Berhasil', message: (res.message || 'Favorit tersimpan.'), variant: 'success', btnText: 'Ok', btnColor: 'emerald' });
        } else {
          showToastError((res && res.error_msg) || 'Gagal menyimpan favorit');
        }
      });
    }

    function deleteFavQuick(hp, nama) {
      favPendingDeleteHp = hp;
      document.getElementById('favConfirmName').textContent = nama || hp;
      var m = document.getElementById('favConfirmModal');
      m.classList.remove('hidden'); m.classList.add('flex');
    }
    function closeFavConfirm() {
      favPendingDeleteHp = null;
      var m = document.getElementById('favConfirmModal');
      m.classList.add('hidden'); m.classList.remove('flex');
    }
    function confirmFavDelete() {
      var hp = favPendingDeleteHp;
      if (!hp) return;
      closeFavConfirm();
      showLoading('Menghapus…');
      favReq({ msg: 'fav_delete', hp: hp }, function(err, res) {
        hideLoading();
        if (err) { showToastError(err); return; }
        if (res && res.status == 1) {
          favData = favData.filter(function(it) { return String(it.hp) !== String(hp); });
          renderFavList(document.getElementById('favSearch').value.trim());
          showModal({ title: 'Terhapus', message: (res.message || 'Favorit berhasil dihapus.'), variant: 'success', btnText: 'Ok', btnColor: 'emerald' });
        } else {
          showToastError((res && res.error_msg) || 'Gagal menghapus favorit');
        }
      });
    }
    function submitFavDelete() {
      var hp = document.getElementById('favFormHp').value.replace(/[^0-9]/g, '');
      var nama = document.getElementById('favFormNama').value.trim();
      if (!hp) { showToastError('Nomor tidak ditemukan'); return; }
      closeFavForm();
      deleteFavQuick(hp, nama);
    }
  </script>
</body>
</html>
