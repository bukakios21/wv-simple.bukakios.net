<?php
@ob_start();
error_reporting(E_ERROR | E_PARSE);
require_once("../config.php");
require_once("../_session.php");
require_once("../lib/ApiV2.php");
$api_v2 = new ApiV2($user_jwt);

$kode_produk = '000';
if (isset($_GET['code'])) {
    $kode_produk = $_GET['code'];
}

/************ ACTION HERE *****************/
if (isset($_REQUEST['msg'], $_REQUEST['csrf'])) {
    $msg   = $_REQUEST['msg'];
    $csrf  = $_REQUEST['csrf'];
    if ($_SESSION['csrf'] === $csrf) {
        if ($msg === "cek") {
            $id_pelanggan = $_REQUEST['id_pelanggan'];
            $cek_tagihan_raw = $api_v2->inq_pasca($kode_produk, $id_pelanggan);
            //var_dump($cek_tagihan_raw);
            $cek_tagihan      = json_decode($cek_tagihan_raw, true);
            if (isset($cek_tagihan['status'])) {
                $data_r = $cek_tagihan;
            } else {
                $data_r = array('status' => 0, "error_msg" => "Gagal Cek Tagihan, server tidak merespon");
            }
        } else if ($msg === "bayar") {
            if (isset($_REQUEST['inq_id'], $_REQUEST['trx_id'], $_REQUEST['biaya_toko'])) {
                $inq_id      = $_REQUEST['inq_id'];
                $trx_id      = $_REQUEST['trx_id'];
                $biaya_toko  = $_REQUEST['biaya_toko'];
                $bayar_tagihan_raw = $api_v2->pay_pasca($trx_id, $inq_id, $biaya_toko);
                $data_r = json_decode($bayar_tagihan_raw, true);
            } else {
                $data_r = array('status' => 0, "error_msg" => "Parameter tidak lengkap");
            }
        } else {
            $data_r = array('status' => 0, "error_msg" => "Aksi tidak dikenali");
        }
    } else {
        $data_r = array('status' => 0, "error_msg" => "Token tidak valid");
    }
    header('Content-Type: application/json');
    echo json_encode($data_r);
    exit;
}
/************ END ACTION *****************/

/************ LOAD PRODUK DETAIL ************/
$data_produk_raw = $api_v2->detail_produk_by_code($kode_produk);
//var_dump($data_produk_raw);
$data_produk     = json_decode($data_produk_raw, true);
if (isset($data_produk['status']) && $data_produk['status'] == 1) {
    $data_produk      = $data_produk['data'];
    $code             = $data_produk['code'];
    $product_logo     = $data_produk['product_logo'];
    $product_name     = $data_produk['product_name'];
    $profit           = str_replace("-", "", $data_produk['harga_jual']);
    $price_sell       = $data_produk['price_sell'];
} else {
    $error_msg = "Gagal memuat data produk.";
    if (isset($data_produk['error_msg'])) {
        $error_msg = $data_produk['error_msg'];
    }
    echo "<!doctype html><html lang='id'><head><meta charset='UTF-8'/><meta name='viewport' content='width=device-width,initial-scale=1'/><title>Gagal</title></head><body style='font-family:sans-serif;padding:40px 20px;text-align:center;'>";
    echo "<h2>⚠️</h2><p>" . htmlspecialchars($error_msg) . "</p>";
    echo "<a href='index.php' style='color:#1a7fce'>← Kembali</a></body></html>";
    exit;
}

$csrf_token = $app->csrf();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($product_name) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
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
          <img src="<?= htmlspecialchars($product_logo) ?>" class="h-full w-full object-contain" alt="<?= htmlspecialchars($product_name) ?>" onerror="this.style.display='none';this.parentElement.innerHTML='<svg class=\'h-6 w-6 text-brand\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.8\'><rect x=\'2\' y=\'7\' width=\'20\' height=\'15\' rx=\'2\'/><polyline points=\'17 2 12 7 7 2\'/></svg>'"/>
        </div>
        <div>
          <h1 class="text-[17px] font-bold text-slate-900 leading-tight"><?= htmlspecialchars($product_name) ?></h1>
          <p class="text-[12px] text-mutedText mt-0.5"><?= htmlspecialchars($code) ?></p>
        </div>
      </div>
    </section>

    <!-- Input section -->
    <section class="px-4 pb-4">
      <div class="rounded-[16px] border border-slate-200 bg-white shadow-soft overflow-hidden">

        <!-- Input form -->
        <div class="px-5 pt-5 pb-4" id="inputSection">
          <label class="text-[13px] font-semibold text-slate-700">Nomor Pelanggan</label>
          <input
            type="number"
            id="nope"
            class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-[15px] text-slate-800 placeholder-slate-400 outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 transition"
            placeholder="Masukkan nomor pelanggan"
            inputmode="numeric"
          />
          <input type="hidden" id="csrf" value="<?= $csrf_token ?>" />
          <p class="text-[12px] text-mutedText mt-2">Masukkan nomor pelanggan <?= htmlspecialchars($product_name) ?></p>
        </div>

        <!-- Info card -->
        <div class="px-5 pb-5" id="infoCard">
          <div class="rounded-xl bg-emerald-50 border border-emerald-100 px-4 py-3 flex items-start gap-3">
            <div class="shrink-0 mt-0.5">
              <svg class="h-4 w-4 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-[12px] text-emerald-700 leading-snug">
                Bayar tagihan <?= htmlspecialchars($product_name) ?> otomatis dapat diskon biaya admin <span class="font-bold"><?= $app->idr($profit) ?></span>
              </p>
            </div>
            <a href="index.php" class="shrink-0 rounded-lg border border-red-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-red-500 hover:bg-red-50 transition">
              Ganti
            </a>
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
            <span class="text-[13px] text-slate-600">Nama Pelanggan</span>
            <span class="text-[13px] font-semibold text-slate-800" id="nama_pel">-</span>
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
            <span class="text-[13px] font-semibold text-slate-800" id="biaya_admin">-</span>
          </div>
          <div class="flex items-center justify-between px-5 py-3">
            <span class="text-[13px] text-slate-600">Diskon Biaya Admin</span>
            <span class="text-[13px] font-semibold text-emerald-600" id="potongan">-</span>
          </div>
          <div class="flex items-center justify-between px-5 py-3 bg-slate-50">
            <span class="text-[14px] font-bold text-slate-800">Total Bayar</span>
            <span class="text-[17px] font-extrabold text-brand" id="tot_ta">-</span>
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
            <span class="text-[17px] font-extrabold text-emerald-600" id="profit_display">-</span>
          </div>
        </div>
      </div>

      <!-- Biaya layanan -->
      <div class="mt-3 rounded-[20px] border border-slate-200 bg-white shadow-soft px-5 py-4">
        <h3 class="text-[13px] font-bold text-slate-700 mb-1">Buat Biaya Layanan Toko/Kios</h3>
        <input
          type="number"
          id="biaya_profit"
          class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-[14px] text-slate-800 outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 transition"
          value="0"
          min="0"
        />
        <p class="text-[11px] text-mutedText mt-1.5">* Akan muncul di struk transaksi</p>
      </div>
    </section>

  </main>

  <!-- Fixed footer -->
  <footer class="fixed bottom-0 left-0 right-0 z-10 bg-white border-t border-slate-200 shadow-soft px-4 py-3">
    <!-- Initial: CEK button -->
    <button id="btnCek" onclick="doCek()" class="w-full rounded-2xl bg-brand py-3.5 text-[15px] font-bold text-white shadow-cta transition hover:bg-brandDark active:scale-[0.99]">
      Cek Tagihan
    </button>

    <!-- After CEK: PAY + Batal -->
    <div id="btnActionGroup" class="hidden grid grid-cols-2 gap-3">
      <button id="btnBatal" onclick="doBatal()" class="rounded-2xl border border-slate-200 bg-white py-3.5 text-[14px] font-bold text-slate-600 transition hover:bg-slate-50 active:scale-[0.99]">
        Batal
      </button>
      <button id="btnBayar" onclick="doBayar()" class="rounded-2xl bg-emerald-500 py-3.5 text-[14px] font-bold text-white shadow-cta transition hover:bg-emerald-600 active:scale-[0.99]">
        Bayar Sekarang
      </button>
    </div>

    <!-- Processing state -->
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

  <script>
    // State
    var trx_id = "";
    var inq_id = "";

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

    // Show/hide loading
    function showLoading(text) {
      document.getElementById('loadingText').textContent = text || 'Memproses…';
      document.getElementById('loadingOverlay').classList.remove('hidden');
      document.getElementById('loadingOverlay').classList.add('flex');
    }
    function hideLoading() {
      document.getElementById('loadingOverlay').classList.add('hidden');
      document.getElementById('loadingOverlay').classList.remove('flex');
    }

    // Toast error
    function showToastError(msg) {
      var el = document.getElementById('toastError');
      document.getElementById('toastErrorMsg').textContent = msg;
      el.classList.remove('hidden');
      el.classList.add('flex');
      setTimeout(function() { el.classList.add('hidden'); el.classList.remove('flex'); }, 4500);
    }

    // CEK button
    function doCek() {
      var id_pelanggan = document.getElementById('nope').value.trim();
      if (!id_pelanggan) {
        showToastError('Nomor pelanggan tidak boleh kosong');
        return;
      }

      var csrf = document.getElementById('csrf').value;
      showLoading('Memeriksa tagihan…');
      document.getElementById('btnCek').disabled = true;
      document.getElementById('nope').disabled = true;

      var xhr = new XMLHttpRequest();
      xhr.open('GET', '?code=<?= urlencode($code) ?>&msg=cek&id_pelanggan=' + encodeURIComponent(id_pelanggan) + '&csrf=' + encodeURIComponent(csrf), true);
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

    // Render tagihan detail
    function renderDetail(data) {
      trx_id = data.trx_id || "";
      inq_id = data.inq_id || "";
      var d = data.custom_data || {};
      var tagihan = Number(d.tagihan || 0);
      var biaya_admin = Number(d.biaya_admin || 0);
      var potongan = Number(d.potongan || 0);
      var total_buyer = Number(d.total_bayar_buyer || 0);
      var total_seller = Number(d.total_bayar_seller || 0);
      var profit = total_buyer - total_seller;

      document.getElementById('nama_pel').textContent   = d.nama_pelanggan || '-';
      document.getElementById('periode').textContent    = d.periode || '-';
      document.getElementById('tagihan').textContent    = formatRp(tagihan);
      document.getElementById('biaya_admin').textContent = formatRp(biaya_admin);
      document.getElementById('potongan').textContent   = '- ' + formatRp(profit);
      document.getElementById('tot_ta').textContent      = formatRp(total_buyer);
      document.getElementById('tot_ta2').textContent     = formatRp(total_buyer);
      document.getElementById('tot_ka2').textContent     = formatRp(total_seller);
      document.getElementById('profit_display').textContent = formatRp(profit);
    }

    // Switch to action buttons
    function switchToAction() {
      document.getElementById('inputSection').classList.add('hidden');
      document.getElementById('infoCard').classList.add('hidden');
      document.getElementById('detailSection').classList.remove('hidden');
      document.getElementById('btnCek').classList.add('hidden');
      document.getElementById('btnActionGroup').classList.remove('hidden');
      document.getElementById('progressBar').style.width = '100%';
    }

    // Show error state
    function showErrorState(msg) {
      document.getElementById('errorText').textContent = msg;
      document.getElementById('errorMsg').classList.remove('hidden');
    }

    // Batal
    function doBatal() {
      trx_id = ""; inq_id = "";
      document.getElementById('errorMsg').classList.add('hidden');
      document.getElementById('detailSection').classList.add('hidden');
      document.getElementById('btnActionGroup').classList.add('hidden');
      document.getElementById('btnCek').classList.remove('hidden');
      document.getElementById('btnCek').disabled = false;
      document.getElementById('nope').disabled = false;
      document.getElementById('nope').value = '';
      document.getElementById('biaya_profit').value = '0';
      document.getElementById('progressBar').style.width = '33%';
    }

    // Bayar
    function doBayar() {
      if (!inq_id) { showToastError('Silakan cek tagihan terlebih dahulu'); return; }

      var csrf      = document.getElementById('csrf').value;
      var id_pel    = document.getElementById('nope').value.trim();
      var biaya_toko = document.getElementById('biaya_profit').value || '0';

      document.getElementById('btnActionGroup').classList.add('hidden');
      document.getElementById('btnProcessing').classList.remove('hidden');

      var xhr = new XMLHttpRequest();
      xhr.open('GET', '?code=<?= urlencode($code) ?>&msg=bayar&biaya_toko=' + encodeURIComponent(biaya_toko)
        + '&id_pelanggan=' + encodeURIComponent(id_pel) + '&csrf=' + encodeURIComponent(csrf)
        + '&trx_id=' + encodeURIComponent(trx_id) + '&inq_id=' + encodeURIComponent(inq_id), true);
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
          document.getElementById('btnProcessing').classList.add('hidden');
          if (xhr.status === 200) {
            try {
              var myJsn = JSON.parse(xhr.responseText);
              if (myJsn.status == 1) {
                Swal.fire({
                  title: 'Transaksi Berhasil',
                  text: 'Transaksi sedang diproses. Halaman akan dialihkan…',
                  icon: 'success',
                  timer: 3000,
                  showConfirmButton: false
                });
                setTimeout(function() { window.location.href = '../_template/session_success.php'; }, 3000);
              } else {
                showErrorAfterPay(myJsn.error_msg || 'Gagal memproses pembayaran');
              }
            } catch(e) {
              showErrorAfterPay('Respons server tidak valid');
            }
          } else {
            showErrorAfterPay('Koneksi gagal (HTTP ' + xhr.status + ')');
          }
        }
      };
      xhr.send();
    }

    function showErrorAfterPay(msg) {
      Swal.fire({ title: 'Gagal', text: msg, icon: 'warning', confirmButtonText: 'OK' });
      document.getElementById('btnActionGroup').classList.remove('hidden');
    }

    // Back button
    document.getElementById('backBtn').addEventListener('click', function() {
      if (window.android && typeof window.android.back === 'function') {
        window.android.back();
      } else {
        history.back();
      }
    });

    // Enter key on input
    document.getElementById('nope').addEventListener('keydown', function(e) {
      if (e.key === 'Enter') { e.preventDefault(); doCek(); }
    });
  </script>
</body>
</html>
