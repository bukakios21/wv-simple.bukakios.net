<?php

require_once("../config.php");
require_once("../_session.php");
if (isset($_REQUEST['act'])){
    $act = $_REQUEST['act'];
    $show= false;
    if (!isset($_REQUEST['start'], $_REQUEST['end'])){
        $msg = "Silahkan masukkan tanggal mulai dan akhir !";
        $alert = "alert alert-warning";
    }else{
        $page = 1;
        if (isset($_REQUEST['page'])){
            $page = $_REQUEST['page'];
        }

        $end_date = $_REQUEST['end'];
        $start_date = $_REQUEST['start'];
        $data_api = $app->grab_data("https://api-v2.bukakios.net/wv-x7Up2p/laporan?uid=$user_id&start=$start_date&end=$end_date");
        $data_res = json_decode($data_api, true);
        if (!isset($data_res['status'])){
            $msg = "Gagal memangil server. Hubungi Customer Service ! ";
            $alert = "alert alert-danger";
        }else{
            if ($data_res['status'] == 0){
                $msg = $data_res['error_msg'];
                $alert = "alert alert-danger";
            }else{
                $show = true;
            }
        }
    }
}else{
    $start_date = date("Y-m-")."01";
    $end_date = date("Y-m-")."31";
    $page = 1;
    if (isset($_REQUEST['page'])){
        $page = $_REQUEST['page'];
    }

    $data_api = $app->grab_data("https://api-v2.bukakios.net/wv-x7Up2p/laporan?uid=$user_id&start=$start_date&end=$end_date");
    $data_res = json_decode($data_api, true);
    if (!isset($data_res['status'])){
        $msg = "Gagal memangil server. Hubungi Customer Service ! ";
        $alert = "alert alert-danger";
    }else{
        if ($data_res['status'] == 0){
            $msg = $data_res['error_msg'];
            $alert = "alert alert-danger";
        }else{
            $show = true;
        }
    }
}

if ($user_id == 39958){
    // echo json_encode($data_res);exit;
}

$today = date("Y-m-d");

$bulan_now = date("Y-m");
$bulan_now_t = $bulan_now."-01";
$bulan_last_day = date('t', strtotime($bulan_now.'-01'));
$bulan_last = $bulan_now."-".$bulan_last_day;

$bulan_kemaren = date('Y-m-d', strtotime('-1 month', strtotime( date("Y-m-d") )));
$ex_tp = explode("-", $bulan_kemaren);
$bulan_kemaren = $ex_tp[0]."-".$ex_tp[1]."-01";
$bulan_kemaren_last_day = date('t', strtotime($bulan_kemaren));
$bulan_kemaren_last = $ex_tp[0]."-".$ex_tp[1]."-".$bulan_kemaren_last_day;

function tgl_indo($tanggal){
    if (empty($tanggal) || strlen($tanggal) < 10) {
        return '-';
    }
    $bulan = array (
        1 =>   'JAN',
        'FEB',
        'MAR',
        'APR',
        'MEI',
        'JUN',
        'JUL',
        'AGUS',
        'SPT',
        'OKT',
        'NVM',
        'DSM'
    );
    $pecahkan = explode('-', $tanggal);
    if (count($pecahkan) < 3) {
        return '-';
    }
    return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}

$hash = md5("$user_id-bukakios");

$summary_pemasukan = (isset($show) && isset($data_res['jual'])) ? "Rp. ".$app->angka_id($data_res['jual']) : "Rp. -";
$summary_pengeluaran = (isset($show) && isset($data_res['modal'])) ? "Rp. ".$app->angka_id($data_res['modal']) : "Rp. -";
$summary_keuntungan = (isset($show) && isset($data_res['untung'])) ? "Rp. ".$app->angka_id($data_res['untung']) : "Rp. -";
$summary_total_trx = (isset($show) && isset($data_res['jumlah_trx'])) ? $app->angka_id($data_res['jumlah_trx'])." Transaksi" : "- Transaksi";
$date_range_text = tgl_indo($start_date)." - ".tgl_indo($end_date);

?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Laporan Penghasilan - BukaKios</title>
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

  <main class="px-4 pt-6">

    <!-- Hero Section -->
    <div class="flex flex-col items-center text-center mb-6">
      <div class="w-24 h-24 rounded-2xl bg-brand/10 flex items-center justify-center mb-4 shadow-soft">
        <svg viewBox="0 0 24 24" class="w-12 h-12 text-brand" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="20" x2="18" y2="10"/>
          <line x1="12" y1="20" x2="12" y2="4"/>
          <line x1="6" y1="20" x2="6" y2="14"/>
          <line x1="2" y1="20" x2="22" y2="20"/>
        </svg>
      </div>
      <h1 class="text-[23px] font-extrabold tracking-tight text-slate-900">Laporan Penghasilan</h1>
      <p class="text-sm text-slate-500 mt-1">Ringkasan keuangan usahamu</p>
    </div>

    <!-- Alert -->
    <?php if (isset($alert)): ?>
    <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6">
      <div class="flex items-start gap-3">
        <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center shrink-0">
          <svg viewBox="0 0 24 24" class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <line x1="15" y1="9" x2="9" y2="15"/>
            <line x1="9" y1="9" x2="15" y2="15"/>
          </svg>
        </div>
        <div>
          <p class="text-[14px] text-red-800 leading-relaxed"><?php echo $msg ?></p>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Date Range Card -->
    <div class="bg-white rounded-2xl border border-slate-100 p-4 mb-4 shadow-card">
      <div class="flex items-center gap-2 mb-3">
        <svg viewBox="0 0 24 24" class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
          <line x1="16" y1="2" x2="16" y2="6"/>
          <line x1="8" y1="2" x2="8" y2="6"/>
          <line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
        <span class="text-[13px] text-slate-500 font-medium">Periode</span>
      </div>
      <p class="text-[15px] font-semibold text-slate-800"><?php echo $date_range_text ?></p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-3 gap-3 mb-4">
      <!-- Pemasukan -->
      <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-card text-center">
        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center mx-auto mb-2">
          <svg viewBox="0 0 24 24" class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="19" x2="12" y2="5"/>
            <polyline points="5 12 12 5 19 12"/>
          </svg>
        </div>
        <p class="text-[11px] text-slate-500 font-medium mb-1">Pemasukan</p>
        <p class="text-[14px] font-bold text-emerald-600 leading-tight"><?php echo $summary_pemasukan ?></p>
      </div>

      <!-- Pengeluaran -->
      <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-card text-center">
        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center mx-auto mb-2">
          <svg viewBox="0 0 24 24" class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"/>
            <polyline points="19 12 12 19 5 12"/>
          </svg>
        </div>
        <p class="text-[11px] text-slate-500 font-medium mb-1">Pengeluaran</p>
        <p class="text-[14px] font-bold text-red-500 leading-tight"><?php echo $summary_pengeluaran ?></p>
      </div>

      <!-- Keuntungan -->
      <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-card text-center">
        <div class="w-10 h-10 rounded-xl bg-brand/10 flex items-center justify-center mx-auto mb-2">
          <svg viewBox="0 0 24 24" class="w-5 h-5 text-brand" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
          </svg>
        </div>
        <p class="text-[11px] text-slate-500 font-medium mb-1">Keuntungan</p>
        <p class="text-[14px] font-bold text-brand leading-tight"><?php echo $summary_keuntungan ?></p>
      </div>
    </div>

    <!-- Download Button -->
    <div class="mb-6">
      <a href="<?php echo "$open_url$c_url/laporan-penghasilan/download.php?act=download&start=$start_date&end=$end_date&hash=$hash&uid=$user_id"; ?>" class="flex items-center justify-center gap-2 bg-brand rounded-2xl p-3.5 shadow-soft transition hover:bg-brandDark active:scale-[0.98]">
        <svg viewBox="0 0 24 24" class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
          <polyline points="7 10 12 15 17 10"/>
          <line x1="12" y1="15" x2="12" y2="3"/>
        </svg>
        <span class="text-[14px] font-semibold text-white">Download Laporan</span>
      </a>
    </div>

    <!-- Transaction Table -->
    <?php if (isset($show) && $show): ?>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-card overflow-hidden mb-6">
      <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
        <h2 class="text-[15px] font-bold text-slate-800">Detail Transaksi</h2>
        <div class="flex items-center gap-2">
          <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-[12px] font-semibold text-slate-600">
            <span class="w-1.5 h-1.5 rounded-full bg-brand"></span>
            <?php echo $summary_total_trx ?>
          </span>
          <button type="button" class="grid h-8 w-8 place-items-center rounded-full border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-50 hover:border-slate-300 active:scale-95" onclick="openFilterModal()" title="Filter Tanggal">
            <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
            </svg>
          </button>
        </div>
      </div>

      <!-- Table Header -->
      <div class="grid grid-cols-4 gap-2 px-4 py-2 bg-slate-50 border-b border-slate-100">
        <span class="text-[12px] font-semibold text-slate-500">Tanggal</span>
        <span class="text-[12px] font-semibold text-slate-500 text-right">Pemasukan</span>
        <span class="text-[12px] font-semibold text-slate-500 text-right">Pengeluaran</span>
        <span class="text-[12px] font-semibold text-slate-500 text-right">Trx</span>
      </div>

      <!-- Table Body -->
      <div class="divide-y divide-slate-50">
        <?php
        if (isset($data_res['data']) && is_array($data_res['data'])):
        $no = 1;
        foreach($data_res['data'] as $data):
        ?>
        <div class="grid grid-cols-4 gap-2 px-4 py-3 items-center hover:bg-slate-50/50 transition">
          <span class="text-[13px] text-slate-700 font-medium"><?php echo tgl_indo($data['tanggal']); ?></span>
          <span class="text-[13px] text-emerald-600 font-semibold text-right"><?php echo "Rp. ".$app->angka_id($data['jual']); ?></span>
          <span class="text-[13px] text-red-500 font-semibold text-right"><?php echo "Rp. ".$app->angka_id($data['modal']); ?></span>
          <span class="text-[13px] text-slate-600 text-right"><?php echo $app->angka_id($data['jumlah_trx']); ?></span>
        </div>
        <?php
        $no++;
        endforeach;
        else:
        ?>
        <div class="px-4 py-8 text-center">
          <p class="text-[14px] text-slate-500">Tidak ada data transaksi</p>
        </div>
        <?php endif; ?>
      </div>

      <!-- Pagination -->
      <?php if (isset($page) && isset($data_res['total_page'])): ?>
      <?php
      $totalPages = $data_res['total_page'];
      if ($totalPages == 0) $totalPages = 1;
      $show_page = 7;
      $i = 1;
      ?>
      <div class="px-4 py-3 border-t border-slate-100">
        <div class="flex items-center justify-between">
          <?php if ($page > 1): ?>
          <a href="?act=filter&start=<?php echo $start_date ?>&end=<?php echo $end_date ?>&page=1" class="text-[13px] font-medium text-brand hover:underline">Pertama</a>
          <?php else: ?>
          <span class="text-[13px] font-medium text-slate-400">Pertama</span>
          <?php endif; ?>

          <div class="flex items-center gap-1">
            <?php
            if ($page >= $show_page) {
                $total_prev = $page - 3;
                $total_next = $page + 3;
                if ($total_next >= $totalPages) {
                    $total_next = $totalPages;
                    $total_prev = max(1, $total_next - 6);
                }
                $i = $total_prev;
            } else {
                $i = 1;
            }

            while ($i <= $show_page && $i < $totalPages + 1):
                if ($i == $page):
            ?>
            <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-brand text-white text-[13px] font-semibold"><?php echo $i ?></span>
            <?php else: ?>
            <a href="?act=filter&start=<?php echo $start_date ?>&end=<?php echo $end_date ?>&page=<?php echo $i ?>" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 text-[13px] font-medium hover:bg-slate-200 transition"><?php echo $i ?></a>
            <?php
                endif;
                $i++;
            endwhile;
            ?>
          </div>

          <?php if ($page < $totalPages): ?>
          <a href="?act=filter&start=<?php echo $start_date ?>&end=<?php echo $end_date ?>&page=<?php echo $totalPages ?>" class="text-[13px] font-medium text-brand hover:underline">Terakhir</a>
          <?php else: ?>
          <span class="text-[13px] font-medium text-slate-400">Terakhir</span>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Info Pills -->
    <div class="flex flex-wrap gap-2">
      <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-[12px] font-medium text-slate-600 shadow-soft">
        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
        Data Real-time
      </span>
      <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-[12px] font-medium text-slate-600 shadow-soft">
        <span class="w-1.5 h-1.5 rounded-full bg-brand"></span>
        Akurat & Terpercaya
      </span>
    </div>

  </main>

  <!-- Filter Modal -->
  <div id="filterModal" class="fixed inset-0 z-50 hidden" aria-labelledby="filterModalLabel" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closeFilterModal()"></div>
    <!-- Modal Content -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl w-full max-w-sm shadow-2xl overflow-hidden transform transition-all">
        <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
          <h5 class="text-[16px] font-bold text-slate-800">Filter Tanggal</h5>
          <button type="button" onclick="closeFilterModal()" class="grid h-8 w-8 place-items-center rounded-full bg-slate-100 text-slate-500 transition hover:bg-slate-200">
            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"/>
              <line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
          </button>
        </div>
        <div class="p-4">
          <form method="get">
            <!-- Quick Filters -->
            <div class="flex flex-wrap gap-2 mb-4">
              <button type="button" class="quick-filter inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1.5 text-[12px] font-medium text-slate-600 shadow-soft transition hover:border-brand hover:text-brand" data-start="<?php echo $today ?>" data-end="<?php echo $today ?>">
                Hari ini
              </button>
              <button type="button" class="quick-filter inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1.5 text-[12px] font-medium text-slate-600 shadow-soft transition hover:border-brand hover:text-brand" data-start="<?php echo date('Y-m-d', strtotime('-1 days', strtotime($today))) ?>" data-end="<?php echo $today ?>">
                Kemarin
              </button>
              <button type="button" class="quick-filter inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1.5 text-[12px] font-medium text-slate-600 shadow-soft transition hover:border-brand hover:text-brand" data-start="<?php echo date('Y-m-d', strtotime('-7 days', strtotime($today))) ?>" data-end="<?php echo $today ?>">
                Minggu ini
              </button>
              <button type="button" class="quick-filter inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1.5 text-[12px] font-medium text-slate-600 shadow-soft transition hover:border-brand hover:text-brand" data-start="<?php echo $bulan_now_t ?>" data-end="<?php echo $bulan_last ?>">
                Bulan ini
              </button>
              <button type="button" class="quick-filter inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1.5 text-[12px] font-medium text-slate-600 shadow-soft transition hover:border-brand hover:text-brand" data-start="<?php echo $bulan_kemaren ?>" data-end="<?php echo $bulan_kemaren_last ?>">
                Bulan kemarin
              </button>
            </div>

            <!-- Date Inputs -->
            <div class="space-y-3">
              <div>
                <label class="block text-[13px] font-semibold text-slate-600 mb-1.5">Tanggal Awal</label>
                <input type="date" id="start" name="start" value="<?php echo $start_date ?>" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[14px] text-slate-700 transition focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none">
              </div>
              <div>
                <label class="block text-[13px] font-semibold text-slate-600 mb-1.5">Tanggal Akhir</label>
                <input type="date" id="end" name="end" value="<?php echo $end_date ?>" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[14px] text-slate-700 transition focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none">
              </div>
            </div>

            <input type="hidden" name="act" value="filter">

            <!-- Actions -->
            <div class="flex gap-3 mt-5">
              <button type="button" onclick="closeFilterModal()" class="flex-1 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-[14px] font-semibold text-slate-600 transition hover:bg-slate-50">Batal</button>
              <button type="submit" class="flex-1 rounded-xl bg-brand px-4 py-2.5 text-[14px] font-semibold text-white transition hover:bg-brandDark">Terapkan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Modal functions
    function openFilterModal() {
      document.getElementById('filterModal').classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function closeFilterModal() {
      document.getElementById('filterModal').classList.add('hidden');
      document.body.style.overflow = '';
    }

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeFilterModal();
      }
    });

    // Quick filter buttons
    document.querySelectorAll('.quick-filter').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var start = this.getAttribute('data-start');
        var end = this.getAttribute('data-end');
        document.getElementById('start').value = start;
        document.getElementById('end').value = end;
      });
    });
  </script>

</body>
</html>
