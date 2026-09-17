<?php
require_once("../config.php");
require_once("../_session.php");
require_once("../lib/ApiV2.php");

if (!isset($_GET['e_id'])) {
    echo "WRONG PARAMETER !!";
    exit;
}

$today = $today ?? date('Y-m-d');

$e_id = abs((int) $_GET['e_id']);
$api_v2 = new ApiV2($user_jwt);

$data_api = $api_v2->event_detail($e_id);
$data_res = json_decode($data_api, true);
if (!is_array($data_res) || !isset($data_res['status'])) {
    echo "SORRY, UNDER MAINTENANCE !!";
    exit;
}

if ((int)$data_res['status'] !== 1) {
    echo $data_res['error_msg'] ?? $data_res['message'] ?? 'Data event tidak ditemukan';
    exit;
}

$payload = $data_res['data'] ?? [];
$real_data = $payload['data'] ?? $payload;
$pemenang = $payload['pemenang'] ?? ($real_data['e_pemenang'] ?? '');

if (!is_array($real_data) || empty($real_data['e_id'])) {
    echo "Data event tidak ditemukan";
    exit;
}

function bulan_indo_event($bulan)
{
    $list = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    return $list[(int)$bulan] ?? $bulan;
}

function bulan_indo_event_short($bulan)
{
    $list = array(
        1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
        'Jul', 'Agus', 'Sep', 'Okt', 'Nov', 'Des'
    );
    return $list[(int)$bulan] ?? $bulan;
}

function tgl_indo($tanggal)
{
    if (empty($tanggal)) return '-';
    $tanggal = substr($tanggal, 0, 10);
    $pecahkan = explode('-', $tanggal);
    if (count($pecahkan) !== 3) return $tanggal;

    return $pecahkan[2] . ' ' . bulan_indo_event($pecahkan[1]) . ' ' . $pecahkan[0];
}

function periode_event_short($mulai, $akhir)
{
    if (empty($mulai) || empty($akhir)) return '-';
    $mulai = substr($mulai, 0, 10);
    $akhir = substr($akhir, 0, 10);
    $m = explode('-', $mulai);
    $a = explode('-', $akhir);
    if (count($m) !== 3 || count($a) !== 3) return tgl_indo($mulai) . ' - ' . tgl_indo($akhir);

    if ($m[0] === $a[0] && $m[1] === $a[1]) {
        return $m[2] . ' - ' . $a[2] . ' ' . bulan_indo_event_short($a[1]);
    }

    if ($m[0] === $a[0]) {
        return $m[2] . ' ' . bulan_indo_event_short($m[1]) . ' - ' . $a[2] . ' ' . bulan_indo_event_short($a[1]);
    }

    return $m[2] . ' ' . bulan_indo_event_short($m[1]) . ' ' . $m[0] . ' - ' . $a[2] . ' ' . bulan_indo_event_short($a[1]) . ' ' . $a[0];
}

function render_pemenang_table($pemenang)
{
    $decoded = json_decode($pemenang, true);
    if (!is_array($decoded) || !isset($decoded['data']) || !is_array($decoded['data'])) {
        return '<div class="mt-2 text-[13px] leading-relaxed text-emerald-700">' . nl2br(htmlspecialchars($pemenang)) . '</div>';
    }

    $html = '<div class="mt-3 overflow-hidden rounded-xl bg-white">';
    $html .= '<div class="overflow-x-auto"><table class="w-full text-left text-[13px]">';
    $html .= '<thead class="bg-slate-50 text-[11px] uppercase tracking-wide text-slate-500"><tr><th class="px-3 py-2">No</th><th class="px-3 py-2">Nama</th><th class="px-3 py-2 text-right">Hadiah</th></tr></thead><tbody class="divide-y divide-slate-100">';

    $no = 1;
    foreach ($decoded['data'] as $row) {
        $nama = htmlspecialchars($row['nama'] ?? '-', ENT_QUOTES, 'UTF-8');
        $hadiah = htmlspecialchars($row['toko'] ?? '-', ENT_QUOTES, 'UTF-8');
        $html .= '<tr><td class="w-12 px-3 py-2 font-semibold text-slate-500">' . $no . '</td><td class="px-3 py-2 font-bold text-slate-800">' . $nama . '</td><td class="px-3 py-2 text-right font-extrabold text-slate-800">' . $hadiah . '</td></tr>';
        $no++;
    }

    $html .= '</tbody></table></div></div>';
    return $html;
}

function build_event_form($real_data, $e_id, $today)
{
    if (($real_data['e_akhir'] ?? '') < $today) {
        return "<div class='rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-[14px] font-semibold text-amber-700'>Event ini telah berakhir.</div>";
    }

    $e_form = trim($real_data['e_form'] ?? '');
    if ($e_form === '') {
        return '';
    }

    $build_form = "<form method='post' action='' class='space-y-3'>";
    $ex_form = explode("\n", $e_form);
    foreach ($ex_form as $ex_form_res) {
        $data_ex = trim($ex_form_res);
        if ($data_ex === '') continue;
        $ex_data = explode(";", $data_ex);
        if (count($ex_data) < 2) continue;

        $label = htmlspecialchars($ex_data[0], ENT_QUOTES, 'UTF-8');
        $name = htmlspecialchars($ex_data[1], ENT_QUOTES, 'UTF-8');
        $required = (isset($ex_data[2]) && trim($ex_data[2]) == '1') ? 'required' : '';
        $build_form .= "<div><label class='mb-1.5 block text-[13px] font-bold text-slate-700'>{$label}</label><input class='w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-[14px] font-semibold text-slate-900 outline-none focus:border-brand focus:ring-2 focus:ring-brand/20' type='text' name='{$name}' {$required}></div>";
    }
    $build_form .= "<input type='hidden' name='submit'><button class='w-full rounded-xl bg-brand py-3 text-[14px] font-extrabold text-white shadow-soft transition active:scale-[0.99]' type='submit'>Kirim Data</button></form>";
    return $build_form;
}

if (isset($_POST['submit'])) {
    require_once('_act.php');
}

$is_ended = ($real_data['e_akhir'] ?? '') < $today;
$build_form = build_event_form($real_data, $e_id, $today);
$html = html_entity_decode($real_data['e_html'] ?? '');
$html = str_replace("{{formIkuti}}", $build_form, $html);


?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
    <title><?= htmlspecialchars($real_data['e_title'] ?? 'Detail Event') ?></title>
    <style>
        * { font-family: 'Inter', sans-serif; }
        a, a:hover { text-decoration: none; }
        .event-content { color: #334155; font-size: 14px; line-height: 1.7; }
        .event-content img { max-width: 100%; border-radius: 16px; }
        .event-content p { margin-bottom: 12px; }
        .event-content .btn, .event-content button, .event-content a.btn { display: inline-flex; align-items: center; justify-content: center; border-radius: 12px; padding: 10px 14px; font-size: 14px; font-weight: 800; }
        .event-content .btn-success { width: 100%; background: #10b981; color: #fff; }
    </style>
</head>
<body class="font-sans text-slate-950 antialiased bg-slate-50">
    <header class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-slate-100">
        <div class="flex items-center gap-3 px-4 py-3">
            <a href="index.php" class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-600 active:scale-95">
                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
            </a>
            <div class="h-1 flex-1 rounded-full bg-slate-100 overflow-hidden">
                <div class="h-full w-full rounded-full bg-brand"></div>
            </div>
            <div class="shrink-0 text-[16px] font-bold tracking-[-0.04em] text-brand">BukaKios</div>
        </div>
    </header>

    <main class="mx-auto max-w-lg px-4 py-5 pb-10">
        <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-soft">
            <div class="relative aspect-[16/8] w-full overflow-hidden bg-slate-100">
                <?php if (!empty($real_data['e_image'])): ?>
                    <img src="<?= htmlspecialchars($real_data['e_image']) ?>" alt="<?= htmlspecialchars($real_data['e_title'] ?? 'Event BukaKios') ?>" class="h-full w-full object-cover">
                <?php else: ?>
                    <div class="flex h-full w-full items-center justify-center text-slate-300">
                        <svg viewBox="0 0 24 24" class="h-12 w-12" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M8 11l2.5 2.5L14 10l4 5"/></svg>
                    </div>
                <?php endif; ?>
                <div class="absolute right-3 top-3 rounded-full border px-3 py-1 text-[11px] font-extrabold backdrop-blur <?= $is_ended ? 'border-slate-200 bg-slate-100 text-slate-500' : 'border-emerald-200 bg-emerald-50 text-emerald-700' ?>">
                    <?= $is_ended ? 'Event Berakhir' : 'Masih Berjalan' ?>
                </div>
            </div>
            <div class="p-4">
                <h1 class="m-0 text-[19px] font-extrabold leading-tight text-slate-900"><?= htmlspecialchars($real_data['e_title'] ?? '-') ?></h1>
                <?php if (!empty($real_data['e_desc'])): ?>
                    <p class="m-0 mt-2 text-[13px] font-medium leading-relaxed text-slate-500"><?= htmlspecialchars($real_data['e_desc']) ?></p>
                <?php endif; ?>
            </div>
        </section>

        <section class="mt-3.5 grid grid-cols-2 gap-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-3.5 shadow-soft">
                <div class="mb-2 flex h-9 w-9 items-center justify-center rounded-xl bg-brand/10 text-brand">
                    <svg viewBox="0 0 24 24" class="h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                </div>
                <p class="m-0 text-[11px] font-bold uppercase text-slate-400">Periode</p>
                <p class="m-0 mt-1 text-[15px] font-extrabold leading-snug text-slate-800"><?= periode_event_short($real_data['e_mulai'] ?? '', $real_data['e_akhir'] ?? '') ?></p>
            </div>
            <div class="rounded-2xl border border-amber-100 bg-amber-50 p-3.5 shadow-soft">
                <div class="mb-2 flex h-9 w-9 items-center justify-center rounded-xl bg-white text-amber-600">
                    <svg viewBox="0 0 24 24" class="h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 21h8"/><path d="M12 17v4"/><path d="M7 4h10"/><path d="M17 4v6a5 5 0 0 1-10 0V4"/></svg>
                </div>
                <p class="m-0 text-[11px] font-bold uppercase text-amber-600/70">Total Hadiah</p>
                <p class="m-0 mt-1 text-[16px] font-extrabold text-amber-700">Rp <?= $app->angka_id((int)($real_data['e_hadiah'] ?? 0)) ?></p>
            </div>
        </section>

        <?php if ($is_ended): ?>
            <section class="mt-3.5 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-[14px] font-semibold text-amber-700">
                Event ini sudah berakhir. Kamu tetap bisa melihat informasi event di bawah ini.
            </section>
        <?php endif; ?>

        <section class="mt-3.5 rounded-2xl border border-slate-200 bg-white p-4 shadow-soft">
            <div class="mb-3 flex items-center gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand/10 text-brand">
                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8M16 17H8M10 9H8"/></svg>
                </div>
                <div>
                    <h2 class="m-0 text-[15px] font-extrabold leading-tight text-slate-900">Detail Event</h2>
                    <p class="m-0 mt-0.5 text-[13px] font-medium text-slate-500">Informasi lengkap event</p>
                </div>
            </div>
            <div class="event-content">
                <?= $html ?>
            </div>
        </section>

        <?php if ($is_ended && !empty($pemenang)): ?>
            <section class="mt-3.5 rounded-2xl border border-slate-200 bg-white p-4 shadow-soft">
                <h2 class="m-0 text-[15px] font-extrabold text-slate-900">Daftar Pemenang</h2>
                <?= render_pemenang_table($pemenang) ?>
            </section>
        <?php endif; ?>
    </main>
</body>
</html>
