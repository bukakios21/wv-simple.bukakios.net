<?php
require_once("../config.php");
require_once("../_session.php");
require_once("../lib/ApiV2.php");

$api_v2 = new ApiV2($user_jwt);

// AJAX actions (list/update/tarik) -> delegasikan ke handler _act.
if (isset($_POST['act'], $_POST['csrf'])) {
    require_once('_act/proses_referral.php');
    exit;
} else {
    $csrf = $app->csrf();
}

// -------------------------------------------------------------------------
// Data awal (server-side): profil user (untuk downline_link) + total komisi.
// -------------------------------------------------------------------------
$data_user_res = json_decode($api_v2->detail_user(), true);

$is_error  = false;
$error_msg = "";
if (!isset($data_user_res['status'])) {
    $error_msg = "error call api server";
    $is_error  = true;
} elseif ((int) $data_user_res['status'] === 0) {
    $error_msg = $data_user_res['error_msg'] ?? 'Sesi tidak valid';
    $is_error  = true;
}

if ($is_error) {
    $html_title      = "Error";
    $lyt_button_link = "opentranslate://10|pulsa";
    $lyt_button_name = "KEMBALI KE DASHBOARD";
    $lyt_image       = "https://assets.bukakios.net/img/illustration/bc_logout.png";
    $lyt_title       = "Error!";
    $lyt_description = $error_msg;
    require_once(ROOT . "/_template/general_message.php");
    exit;
}

$data          = $data_user_res['data'];
$downline_link = $data['downline_link'] ?? '';
$nama_toko     = $data['nama_toko'] ?? '';
$image         = $data['image'] ?? '';

// Total komisi user (my_komisi) via /user/komisi.
$komisi_res  = json_decode($api_v2->get_komisi(), true);
$total_komisi = 0;
if (isset($komisi_res['status']) && (int) $komisi_res['status'] === 1) {
    $total_komisi = (int) ($komisi_res['data']['komisi'] ?? 0);
}

$register_link = "https://bukakios.link/register/?by=" . $downline_link;
$harga_link    = "https://bukakios.link/daftar-harga/?by=" . $downline_link;
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Referral - BukaKios</title>
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

    <!-- Hero -->
    <div class="flex flex-col items-center text-center mb-6">
      <div class="w-24 h-24 rounded-2xl bg-brand/10 flex items-center justify-center mb-4 shadow-soft">
        <svg viewBox="0 0 24 24" class="w-12 h-12 text-brand" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
          <circle cx="9" cy="7" r="4"/>
          <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
          <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
        </svg>
      </div>
      <h1 class="text-[22px] font-extrabold tracking-tight text-slate-900">Referral</h1>
      <p class="text-sm text-slate-500 mt-1">Ajak teman & dapatkan komisi hingga Rp500 tiap transaksi</p>
    </div>

    <!-- Tabs -->
    <div class="grid grid-cols-3 gap-2 rounded-2xl bg-white p-1.5 shadow-card border border-slate-100 mb-6 text-[13px] font-semibold">
      <button data-tab="penjelasan" class="tab-btn rounded-xl py-2.5 transition">Penjelasan</button>
      <button data-tab="referral"   class="tab-btn rounded-xl py-2.5 transition">Referral Saya</button>
      <button data-tab="komisi"     class="tab-btn rounded-xl py-2.5 transition">Komisi Saya</button>
    </div>

    <!-- ================= TAB: PENJELASAN ================= -->
    <section id="tab-penjelasan" class="tab-panel space-y-4">

      <!-- Kode & Link -->
      <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-card space-y-4">
        <div>
          <h2 class="text-[15px] font-bold text-slate-800">Apa Itu Referral?</h2>
          <p class="text-[13px] text-slate-500 leading-relaxed mt-1">
            Ajak orang bergabung di BukaKios menggunakan kode/link referral kamu. Kamu dapat
            <strong class="text-slate-700">komisi gratis hingga Rp500</strong> setiap kali mereka bertransaksi.
          </p>
        </div>

        <!-- Kode Referral -->
        <div class="rounded-xl bg-brand/10 border border-brand/20 p-3 text-center">
          <p class="text-[11px] font-medium text-brand uppercase tracking-wide">Kode Referral Kamu</p>
          <p id="kode_ref_label" class="text-[18px] font-extrabold text-brand mt-0.5"><?= htmlspecialchars($downline_link, ENT_QUOTES, 'UTF-8') ?></p>
        </div>

        <!-- Link Pendaftaran -->
        <div class="rounded-xl bg-slate-50 border border-slate-100 p-3">
          <p class="text-[11px] font-semibold text-slate-500 mb-1">Link Pendaftaran</p>
          <p id="reg_link" class="text-[12px] font-mono break-all text-slate-700"><?= htmlspecialchars($register_link, ENT_QUOTES, 'UTF-8') ?></p>
          <button class="btn-copy mt-2 w-full rounded-lg border border-slate-200 bg-white py-2 text-[12px] font-semibold text-slate-600 active:scale-[0.98] transition" data-copy="<?= htmlspecialchars($register_link, ENT_QUOTES, 'UTF-8') ?>">Salin Link Pendaftaran</button>
        </div>

        <!-- Link Daftar Harga -->
        <div class="rounded-xl bg-slate-50 border border-slate-100 p-3">
          <p class="text-[11px] font-semibold text-slate-500 mb-1">Link Daftar Harga</p>
          <p id="harga_link" class="text-[12px] font-mono break-all text-slate-700"><?= htmlspecialchars($harga_link, ENT_QUOTES, 'UTF-8') ?></p>
          <button class="btn-copy mt-2 w-full rounded-lg border border-slate-200 bg-white py-2 text-[12px] font-semibold text-slate-600 active:scale-[0.98] transition" data-copy="<?= htmlspecialchars($harga_link, ENT_QUOTES, 'UTF-8') ?>">Salin Link Daftar Harga</button>
        </div>
      </div>

      <!-- Ubah Kode -->
      <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-card">
        <h2 class="text-[15px] font-bold text-slate-800">Ubah Kode Referral</h2>
        <p class="text-[12px] text-slate-500 mt-1 mb-3">Ganti kode sesuai keinginan (6 - 15 karakter).</p>
        <form id="form-kode" class="flex gap-2">
          <input id="input-kode" type="text" minlength="6" maxlength="15" required
            pattern="[A-Za-z0-9]{6,15}" title="6 - 15 karakter, hanya huruf & angka"
            autocomplete="off" autocapitalize="off" spellcheck="false"
            placeholder="6 - 15 karakter (huruf & angka)"
            class="flex-1 rounded-xl border border-slate-200 px-3 py-2.5 text-[14px] focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20" />
          <button type="submit" class="rounded-xl bg-brand px-4 py-2.5 text-[14px] font-bold text-white hover:bg-brandDark active:scale-[0.98] transition">Simpan</button>
        </form>
      </div>

      <!-- Syarat -->
      <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-card">
        <h2 class="text-[15px] font-bold text-slate-800 mb-2">Syarat &amp; Ketentuan</h2>
        <ul class="list-disc pl-4 space-y-1.5 text-[13px] text-slate-500 leading-relaxed">
          <li>Komisi dari transaksi referral langsung kamu terima saat itu juga.</li>
          <li>Komisi bisa dicairkan atau ditransfer ke stok BukaKios kamu.</li>
          <li>Masa aktif referral selamanya — selama ada transaksi, kamu dapat komisi.</li>
          <li>Referral hanya 1 tingkat.</li>
          <li>Bonus langsung dari BukaKios, tidak dibebankan ke referral kamu.</li>
          <li>Syarat &amp; ketentuan dapat berubah tanpa pemberitahuan.</li>
        </ul>
      </div>
    </section>

    <!-- ================= TAB: REFERRAL SAYA ================= -->
    <section id="tab-referral" class="tab-panel hidden">
      <div id="referral-list" class="space-y-3"></div>
      <div id="referral-empty" class="hidden bg-white rounded-2xl border border-slate-100 p-8 text-center shadow-card">
        <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3">
          <svg viewBox="0 0 24 24" class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        </div>
        <p class="text-[14px] font-semibold text-slate-700">Belum ada referral</p>
        <p class="text-[12px] text-slate-500 mt-1">Ajak teman kamu untuk mulai dapat komisi.</p>
      </div>
      <div id="referral-loading" class="hidden py-8 text-center">
        <div class="inline-block w-8 h-8 border-4 border-brand/20 border-t-brand rounded-full animate-spin"></div>
      </div>
      <div id="referral-pager" class="hidden mt-4 flex items-center justify-between">
        <button id="ref-prev" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-[13px] font-semibold text-slate-600 disabled:opacity-40 active:scale-[0.98] transition">Sebelumnya</button>
        <span id="ref-page" class="text-[13px] font-semibold text-slate-500">Hal 1</span>
        <button id="ref-next" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-[13px] font-semibold text-slate-600 disabled:opacity-40 active:scale-[0.98] transition">Berikutnya</button>
      </div>
    </section>

    <!-- ================= TAB: KOMISI SAYA ================= -->
    <section id="tab-komisi" class="tab-panel hidden">
      <!-- Kartu total komisi -->
      <div class="bg-gradient-to-br from-brand to-brandDark rounded-2xl p-5 mb-4 text-center shadow-card">
        <p class="text-[12px] font-medium text-white/80 uppercase tracking-wide">Total Komisi</p>
        <p id="komisi-total" class="text-[26px] font-extrabold text-white mt-1"><?= $app->idr($total_komisi) ?></p>
        <button id="btn-tarik" class="mt-3 inline-flex items-center gap-2 rounded-xl bg-white px-5 py-2.5 text-[14px] font-bold text-brand active:scale-[0.98] transition">
          <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          Tarik Komisi
        </button>
      </div>

      <div id="komisi-list" class="space-y-3"></div>
      <div id="komisi-empty" class="hidden bg-white rounded-2xl border border-slate-100 p-8 text-center shadow-card">
        <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3">
          <svg viewBox="0 0 24 24" class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        </div>
        <p class="text-[14px] font-semibold text-slate-700">Belum ada komisi</p>
        <p class="text-[12px] text-slate-500 mt-1">Komisi muncul saat referral kamu bertransaksi.</p>
      </div>
      <div id="komisi-loading" class="hidden py-8 text-center">
        <div class="inline-block w-8 h-8 border-4 border-brand/20 border-t-brand rounded-full animate-spin"></div>
      </div>
      <div id="komisi-pager" class="hidden mt-4 flex items-center justify-between">
        <button id="kom-prev" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-[13px] font-semibold text-slate-600 disabled:opacity-40 active:scale-[0.98] transition">Sebelumnya</button>
        <span id="kom-page" class="text-[13px] font-semibold text-slate-500">Hal 1</span>
        <button id="kom-next" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-[13px] font-semibold text-slate-600 disabled:opacity-40 active:scale-[0.98] transition">Berikutnya</button>
      </div>
    </section>

  </main>

  <!-- Loading Overlay -->
  <div id="loading-overlay" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:16px;padding:24px;display:flex;flex-direction:column;align-items:center;gap:12px;box-shadow:0 20px 60px rgba(0,0,0,0.15);min-width:200px;">
      <div style="width:44px;height:44px;border:4px solid rgba(26,127,206,0.2);border-top-color:#1a7fce;border-radius:50%;animation:spin 0.8s linear infinite;"></div>
      <div id="loading-text" style="font-size:14px;font-weight:600;color:#334155;">Memproses...</div>
    </div>
  </div>

  <!-- Modal Konfirmasi Tarik Komisi -->
  <div id="modal-tarik" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9998;align-items:center;justify-content:center;padding:16px;">
    <div class="bg-white rounded-2xl p-5 w-full max-w-sm shadow-card">
      <div class="text-center">
        <div class="w-14 h-14 rounded-full bg-brand/10 flex items-center justify-center mx-auto mb-3">
          <svg viewBox="0 0 24 24" class="w-7 h-7 text-brand" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
        <h3 class="text-[16px] font-bold text-slate-800">Tarik Komisi</h3>
        <p class="text-[13px] text-slate-500 mt-1">Cairkan komisi kamu sebesar</p>
        <p id="modal-tarik-nominal" class="text-[20px] font-extrabold text-brand mt-1"><?= $app->idr($total_komisi) ?></p>
      </div>
      <div class="flex gap-2 mt-5">
        <button id="modal-tarik-batal" class="flex-1 rounded-xl border border-slate-200 bg-white py-2.5 text-[14px] font-semibold text-slate-600 active:scale-[0.98] transition">Batal</button>
        <button id="modal-tarik-ok" class="flex-1 rounded-xl bg-brand py-2.5 text-[14px] font-bold text-white active:scale-[0.98] transition">Iya, Lanjutkan</button>
      </div>
    </div>
  </div>

  <style>
    @keyframes spin { to { transform: rotate(360deg); } }
    @keyframes slideDown { from { top: 50px; opacity: 0; } to { top: 80px; opacity: 1; } }
    @keyframes slideUp { from { top: 80px; opacity: 1; } to { top: 50px; opacity: 0; } }
    .tab-btn.active { background:#1a7fce; color:#fff; }
    .tab-btn:not(.active) { color:#64748b; }
  </style>

  <script>
    var csrf = "<?php echo $csrf ?>";
    var infoTopupBase = "<?php echo $c_url ?>/info-topup/";
    var totalKomisi = <?php echo (int) $total_komisi ?>;
    var LIMIT = 30;

    // State cursor paging per tab: cache batch + cursor last_id BE.
    var refState = { page: 1, cache: [], cursor: 0, done: false, loaded: false };
    var komState = { page: 1, cache: [], cursor: 0, done: false, loaded: false };

    $(document).ready(function () {
      switchTab('penjelasan');

      $('.tab-btn').on('click', function () {
        switchTab($(this).attr('data-tab'));
      });

      // Copy link
      $('.btn-copy').on('click', function () {
        var text = $(this).attr('data-copy');
        copyText(text);
      });

      // Ubah kode referral
      $('#form-kode').on('submit', function (e) {
        e.preventDefault();
        var kode = $('#input-kode').val().trim();
        if (!/^[A-Za-z0-9]{6,15}$/.test(kode)) {
          showToast('Kode harus 6 - 15 karakter, hanya huruf & angka', 'error');
          return;
        }
        showLoading('Menyimpan kode...');
        $.ajax({
          url: 'index.php', method: 'POST', dataType: 'json', timeout: 20000,
          data: { act: 'update_kode', csrf: csrf, kode: kode },
          success: function (d) {
            hideLoading();
            if (d.status == 1) {
              $('#kode_ref_label').text(d.kode);
              $('#reg_link').text('https://bukakios.link/register/?by=' + d.kode);
              $('#harga_link').text('https://bukakios.link/daftar-harga/?by=' + d.kode);
              $('.btn-copy').each(function () {
                var t = $(this).attr('data-copy');
                $(this).attr('data-copy', t.replace(/by=.*$/, 'by=' + d.kode));
              });
              $('#input-kode').val('');
              showToast(d.message || 'Berhasil mengubah kode referral', 'success');
            } else {
              showToast(d.error_msg || 'Gagal mengubah kode', 'error');
            }
          },
          error: function () { hideLoading(); showToast('Koneksi gagal, coba lagi', 'error'); }
        });
      });

      // Pager referral
      $('#ref-prev').on('click', function () { if (refState.page > 1) { refState.page--; renderRef(); } });
      $('#ref-next').on('click', function () { gotoNextRef(); });

      // Pager komisi
      $('#kom-prev').on('click', function () { if (komState.page > 1) { komState.page--; renderKom(); } });
      $('#kom-next').on('click', function () { gotoNextKom(); });

      // Tarik komisi
      $('#btn-tarik').on('click', function () {
        if (totalKomisi < 1000) {
          showToast('Minimal penarikan komisi Rp1.000', 'error');
          return;
        }
        $('#modal-tarik').css('display', 'flex');
      });
      $('#modal-tarik-batal').on('click', function () { $('#modal-tarik').hide(); });
      $('#modal-tarik-ok').on('click', function () {
        $('#modal-tarik').hide();
        showLoading('Memproses penarikan...');
        $.ajax({
          url: 'index.php', method: 'POST', dataType: 'json', timeout: 30000,
          data: { act: 'tarik_komisi', csrf: csrf },
          success: function (d) {
            hideLoading();
            if (d.status == 1) {
              showToast(d.message || 'Penarikan berhasil', 'success');
              setTimeout(function () {
                if (d.topup_id) { window.location.href = infoTopupBase + '?id=' + d.topup_id; }
                else { window.location.reload(); }
              }, 1200);
            } else {
              showToast(d.error_msg || 'Penarikan gagal', 'error');
            }
          },
          error: function () { hideLoading(); showToast('Koneksi gagal, coba lagi', 'error'); }
        });
      });
    });

    function switchTab(tab) {
      $('.tab-btn').removeClass('active');
      $('.tab-btn[data-tab="' + tab + '"]').addClass('active');
      $('.tab-panel').addClass('hidden');
      $('#tab-' + tab).removeClass('hidden');
      if (tab === 'referral' && !refState.loaded) { refState.loaded = true; fetchRef(); }
      if (tab === 'komisi' && !komState.loaded) { komState.loaded = true; fetchKom(); }
    }

    /* ---------------- REFERRAL LIST (cursor paging + slice) ---------------- */
    function fetchRef() {
      $('#referral-loading').removeClass('hidden');
      $('#referral-pager').addClass('hidden');
      $.ajax({
        url: 'index.php', method: 'POST', dataType: 'json', timeout: 20000,
        data: { act: 'list_referral', csrf: csrf, limit: LIMIT, last_id: refState.cursor },
        success: function (d) {
          $('#referral-loading').addClass('hidden');
          var rows = (d && d.data) ? d.data : [];
          if (rows.length) {
            refState.cache = refState.cache.concat(rows);
            refState.cursor = d.last_id || refState.cursor;
            if (rows.length < LIMIT) refState.done = true;
          } else {
            refState.done = true;
          }
          renderRef();
        },
        error: function () { $('#referral-loading').addClass('hidden'); showToast('Gagal memuat data', 'error'); }
      });
    }

    function gotoNextRef() {
      var need = refState.page * LIMIT; // butuh data untuk halaman berikutnya
      if (refState.cache.length <= need && !refState.done) { refState.page++; fetchRef(); return; }
      if (refState.cache.length > need) { refState.page++; renderRef(); }
    }

    function renderRef() {
      var start = (refState.page - 1) * LIMIT;
      var slice = refState.cache.slice(start, start + LIMIT);
      var $list = $('#referral-list').empty();
      if (!refState.cache.length) {
        $('#referral-empty').removeClass('hidden');
        $('#referral-pager').addClass('hidden');
        return;
      }
      $('#referral-empty').addClass('hidden');
      slice.forEach(function (d) {
        $list.append(
          '<div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-card flex items-center gap-3">' +
            '<div class="w-10 h-10 rounded-full bg-brand/10 flex items-center justify-center shrink-0 text-brand font-bold">' + initial(d.nama) + '</div>' +
            '<div class="min-w-0">' +
              '<p class="text-[14px] font-semibold text-slate-800 truncate">' + esc(d.nama || '-') + '</p>' +
              '<p class="text-[12px] text-slate-400">' + fmtDate(d.created_at) + '</p>' +
            '</div>' +
          '</div>'
        );
      });
      $('#referral-pager').removeClass('hidden');
      $('#ref-page').text('Hal ' + refState.page);
      $('#ref-prev').prop('disabled', refState.page <= 1);
      $('#ref-next').prop('disabled', refState.done && (refState.page * LIMIT >= refState.cache.length));
    }

    /* ---------------- KOMISI LIST ---------------- */
    function fetchKom() {
      $('#komisi-loading').removeClass('hidden');
      $('#komisi-pager').addClass('hidden');
      $.ajax({
        url: 'index.php', method: 'POST', dataType: 'json', timeout: 20000,
        data: { act: 'list_komisi', csrf: csrf, limit: LIMIT, last_id: komState.cursor },
        success: function (d) {
          $('#komisi-loading').addClass('hidden');
          var rows = (d && d.data) ? d.data : [];
          if (rows.length) {
            komState.cache = komState.cache.concat(rows);
            komState.cursor = d.last_id || komState.cursor;
            if (rows.length < LIMIT) komState.done = true;
          } else {
            komState.done = true;
          }
          renderKom();
        },
        error: function () { $('#komisi-loading').addClass('hidden'); showToast('Gagal memuat data', 'error'); }
      });
    }

    function gotoNextKom() {
      var need = komState.page * LIMIT;
      if (komState.cache.length <= need && !komState.done) { komState.page++; fetchKom(); return; }
      if (komState.cache.length > need) { komState.page++; renderKom(); }
    }

    function renderKom() {
      var start = (komState.page - 1) * LIMIT;
      var slice = komState.cache.slice(start, start + LIMIT);
      var $list = $('#komisi-list').empty();
      if (!komState.cache.length) {
        $('#komisi-empty').removeClass('hidden');
        $('#komisi-pager').addClass('hidden');
        return;
      }
      $('#komisi-empty').addClass('hidden');
      slice.forEach(function (d) {
        $list.append(
          '<div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-card flex items-center justify-between gap-3">' +
            '<div class="min-w-0">' +
              '<p class="text-[14px] font-semibold text-slate-800 truncate">' + esc(d.nama || '-') + '</p>' +
              '<p class="text-[12px] text-slate-500 truncate">' + esc(d.keterangan_komisi || '') + '</p>' +
              '<p class="text-[12px] text-slate-400">' + fmtDate(d.created_at) + '</p>' +
            '</div>' +
            '<p class="text-[14px] font-extrabold text-emerald-600 shrink-0">+ ' + rupiah(d.jumlah_komisi) + '</p>' +
          '</div>'
        );
      });
      $('#komisi-pager').removeClass('hidden');
      $('#kom-page').text('Hal ' + komState.page);
      $('#kom-prev').prop('disabled', komState.page <= 1);
      $('#kom-next').prop('disabled', komState.done && (komState.page * LIMIT >= komState.cache.length));
    }

    /* ---------------- helpers ---------------- */
    function esc(s) { return $('<div>').text(s == null ? '' : String(s)).html(); }
    function initial(nama) { nama = (nama || '?').trim(); return nama ? nama.charAt(0).toUpperCase() : '?'; }
    function rupiah(n) {
      n = parseInt(n || 0, 10);
      return 'Rp' + n.toLocaleString('id-ID');
    }
    function fmtDate(s) {
      if (!s) return '-';
      var d = new Date(s.replace(' ', 'T'));
      if (isNaN(d.getTime())) return s;
      var bln = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
      var p = function (x) { return x < 10 ? '0' + x : x; };
      return p(d.getDate()) + ' ' + bln[d.getMonth()] + ' ' + d.getFullYear() + ', ' + p(d.getHours()) + ':' + p(d.getMinutes());
    }
    function copyText(text) {
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(function () { showToast('Berhasil disalin', 'success'); },
          function () { fallbackCopy(text); });
      } else { fallbackCopy(text); }
    }
    function fallbackCopy(text) {
      var t = document.createElement('textarea');
      t.value = text; document.body.appendChild(t); t.select();
      try { document.execCommand('copy'); showToast('Berhasil disalin', 'success'); }
      catch (e) { showToast('Gagal menyalin', 'error'); }
      document.body.removeChild(t);
    }
    function showLoading(txt) { $('#loading-text').text(txt || 'Memproses...'); $('#loading-overlay').css('display', 'flex'); }
    function hideLoading() { $('#loading-overlay').hide(); }
    function showToast(message, type) {
      var bgColor = type === 'success' ? '#10B981' : '#EF4444';
      var toast = document.createElement('div');
      toast.style.cssText = 'position:fixed;top:80px;left:50%;transform:translateX(-50%);background:' + bgColor + ';color:white;padding:12px 24px;border-radius:12px;font-size:14px;font-weight:600;z-index:99999;box-shadow:0 4px 12px rgba(0,0,0,0.15);animation:slideDown 0.3s ease;max-width:90%;text-align:center';
      toast.textContent = message;
      document.body.appendChild(toast);
      setTimeout(function () {
        toast.style.animation = 'slideUp 0.3s ease forwards';
        setTimeout(function () { toast.remove(); }, 300);
      }, 2500);
    }
  </script>
</body>
</html>
