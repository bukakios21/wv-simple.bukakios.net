<?php
require_once "../config.php";
require_once "../_session.php";
require_once "../lib/ApiV2.php";
$app_id="net.bukakiosapps";

$api_v2 = new ApiV2($user_jwt);


if (isset($_REQUEST["msg"])) {
    require_once "_act.php";
}

function callApi($url, $data)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data,
    ]);

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;
}

function datee($date)
{
    $bulan = [
        "01" => "Januari",
        "02" => "Februari",
        "03" => "Maret",
        "04" => "April",
        "05" => "Mei",
        "06" => "Juni",
        "07" => "Juli",
        "08" => "Agustus",
        "09" => "September",
        "10" => "Oktober",
        "11" => "November",
        "12" => "Desember",
    ];

    $date = explode(" ", $date);
    $tgl = $date[0];
    $time = $date[1];

    $tgl = explode("-", $tgl);
    $tgl = $tgl[2] . " " . $bulan[$tgl[1]] . " " . $tgl[0] . " / " . $time;

    return $tgl;
}

if (isset($_GET["id"])) {
    $id = abs((int) $_GET["id"]);
    if ($user_id == 39958) {
     //   $id = 0;
    }

    $data_post = [
        "key" => $api_key,
        "uid" => $user_id,
        "order_id" => $id,
    ];
    $response = $api_v2->transaksi_detail($id);
    $transaksi = json_decode($response, true);

    if ($transaksi["status"] != 1) {
        require_once "404.php";
        exit();
    }
    //   echo "stop 1123";exit;
    $detail_transaksi = $transaksi["data"];
    $trx_id = $detail_transaksi["trx_id"];
    $product_name = $detail_transaksi["product_name"];
    $product_code = $detail_transaksi["product_code"];
    $nomor_tujuan = $detail_transaksi["nomor_tujuan"];
    $id_pel = $detail_transaksi["id_pel"];
    $price_client = $detail_transaksi["price_client"];
    $selling_price_client = $detail_transaksi["selling_price_client"];
    $sn = $detail_transaksi["sn"];
    $note = $detail_transaksi["note"];
    $saldo_before_trx = $detail_transaksi["saldo_before_trx"];
    $saldo_after_trx = $detail_transaksi["saldo_after_trx"];
    $created_at = $detail_transaksi["created_at"];
    $updated_at = $detail_transaksi["updated_at"];
    $status = $detail_transaksi["status"];
    $op_name = $detail_transaksi["op_name"];
    $u_nama = $detail_transaksi["nama"];
    $u_nama_toko = $detail_transaksi["nama_toko"];
    $signature = $detail_transaksi["signature"];
    $pembelianoperator_id = $detail_transaksi["pembelianoperator_id"];
    $pembelian_kategori_id = $detail_transaksi["pembeliankategori_id"];
    $product_logo = $detail_transaksi["product_logo"];

    //tambah data utang param
    $harga_hutang = str_replace(".", "", $selling_price_client);
    $msg = [
        "deskripsi" => $product_name,
        "harga" => $harga_hutang,
    ];
    $status_ppob = "refund";
    $msg = base64_encode(json_encode($msg));
    $teks_komplain = "";
    if ($status == 0) {
        $st_image =
            "https://assets.bukakios.net/img2/uploads/2019/12/827-sand-clock.png";
        $st_label = "Transaksi On Process";
        $statusnya =
            " <span class='badge badge-warning' style='color:white;padding:5px 10px 5px 10px'>Sedang Di Proses</span>";
        $status_ppob = "Process";
        $teks_komplain = "$trx_id Bantu Pending Kak";
    } elseif ($status == 1) {
        $st_image =
            "https://assets.bukakios.net/img2/uploads/2019/12/474-checked.png";
        $st_label = "Transaksi Berhasil";
        $statusnya =
            " <span class='badge badge-success' style='color:white;padding:5px 10px 5px 10px'>Berhasil</span>";
        $status_ppob = "Berhasil";
        $teks_komplain = "$trx_id Sukses Tapi Belum Masuk Kak";
    } elseif ($status == 2) {
        $st_image =
            "https://assets.bukakios.net/img2/uploads/2019/12/822-button.png";
        $st_label = "Transaksi Gagal";
        $statusnya =
            " <span class='badge badge-danger' style='color:white;padding:5px 10px 5px 10px'>Refund</span>";
    } elseif ($status == 3) {
        $st_image =
            "https://assets.bukakios.net/img2/uploads/2019/12/822-button.png";
        $st_label = "Transaksi Gagal";
        $statusnya =
            " <span class='badge badge-danger' style='color:white;padding:5px 10px 5px 10px'>Refund</span>";
        $teks_komplain = "$trx_id Gagal kenapa ya kak?";
    } elseif ($status == 4) {
        $st_image =
            "https://assets.bukakios.net/img2/uploads/2019/12/822-button.png";
        $st_label = "Transaksi Gagal";
        $statusnya =
            " <span class='badge badge-danger' style='color:white;padding:5px 10px 5px 10px'>Refund</span>";
        $teks_komplain = "$trx_id Gagal kenapa ya kak?";
    }
    $wa_komplain_link = wa_link($teks_komplain);

    $target = $nomor_tujuan;
    if (
        $detail_transaksi["pembelianoperator_id"] == 18 or
        $detail_transaksi["pembelianoperator_id"] == 113
    ) {
        //pln .. nomor jadi id_pel
        if (!empty($detail_transaksi["sn"])) {
            $sn_pecah = explode("/", $detail_transaksi["sn"]);
            $nama_pel = $sn_pecah[1];
            $target = "$id_pel <b>$nama_pel</b>";
        } else {
            $target = $id_pel;
        }
    }
    if (empty($sn) and $status != 0) {
        $sn = $note;
    }

    //jika status pending coba get status dari api
    /*if($status==0){
		$data_post = array(
			"key"=>$api_key,
			"trx_id"=>$trx_id
		);
		$data_respon = $app->curl_post("$api_url/cek_trx_id.php",$data_post);
	}*/
    if ($status == 1) {
        //ini untuk dapatkan berapa lama proses nya
        $a_c = strtotime($created_at);
        $a_u = strtotime($updated_at);
        $lama_proses = $a_u - $a_c;
        $lama_menit = round($lama_proses / 60);
        if ($lama_proses < 80) {
            //ini termasuk cepat;
            $fakta_tipe = "success";
            $link_open_rate =
                $open_url .
                "https://play.google.com/store/apps/details?id=$app_id";
            $fakta_teks = "Tahukah kamu bahwa pembelian kamu di proses sangat cepat loh oleh bukakios, hanya dalam <b>$lama_proses detik</b>, pembelian kamu telah berhasil di proses :)
			<p class='mb-0 mt-2.5'><a target='_blank' href='$link_open_rate' class='flex w-full items-center justify-center gap-1.5 rounded-xl bg-amber-400 py-2 text-[13px] font-bold text-slate-900 transition hover:bg-amber-300 active:scale-[0.99]'><svg viewBox='0 0 24 24' class='h-4 w-4' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg> Beri Rating</a>
			";
        } elseif ($lama_proses > 80 and $lama_proses < 420) {
            //8 menit
            //agak lambat
            $fakta_tipe = "warning";
            $fakta_teks = "Kami mohon maaf ya, proses transaksi kamu kali ini sedikit lebih lambat, namun sudah berhasil kok :) , waktu proses transaksi <b>$lama_menit menit</b>";
        } else {
            //sangat lambat
            $fakta_tipe = "danger";
            $fakta_teks = "Kami mohon maaf yang sebesar-besarnya, proses transaksi kamu kali ini sangat lambat, kami akan berusaha meningkatkan layanan kami, waktu proses transaksi kamu <b>$lama_menit menit</b>";
        }
    }
} else {
    require_once "404.php";
    exit();
}
?>
<!doctype html>
<html lang="en">

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css" integrity="sha512-rt/SrQ4UNIaGfDyEXZtNcyWvQeOq0QLygHluFQcSjaGB04IxWhal71tKuzP6K8eYXYB6vJV4pHkXcmFGGQ1/0w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://assets.bukakios.net/css/box.css" crossorigin="anonymous">
    <title>title::Detail Transaksi #<?= $trx_id ?></title>
    <style>
        /* Custom toast ala Tailwind, fixed top-center dengan animasi slide */
        .bk-toast-wrap {
            position: fixed;
            top: 16px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 8px;
            pointer-events: none;
        }
        .bk-toast {
            pointer-events: auto;
            min-width: 240px;
            max-width: 360px;
            padding: 10px 14px 10px 12px;
            border-radius: 12px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 600;
            color: #0f172a;
            opacity: 0;
            transform: translateY(-12px);
            transition: opacity 180ms ease-out, transform 180ms ease-out;
        }
        .bk-toast.is-show {
            opacity: 1;
            transform: translateY(0);
        }
        .bk-toast--success {
            border-color: #a7f3d0;
            background: #ecfdf5;
            color: #047857;
        }
        .bk-toast--success .bk-toast-icon {
            color: #059669;
        }
        .bk-toast-icon {
            display: inline-flex;
            width: 22px;
            height: 22px;
            align-items: center;
            justify-content: center;
            border-radius: 9999px;
            background: #ffffff;
        }
        .bk-toast-text {
            flex: 1 1 auto;
            line-height: 1.35;
        }

        a.disabled {
            pointer-events: none;
            cursor: default;
            opacity: 0.5;
        }

        a,
        a:hover {
            text-decoration: none;
        }

        body {
            font-family: Nunito;
        }

        .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #1a7fce;
            width: 50px;
            height: 50px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
        }

        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>

<body class="font-sans text-slate-950 antialiased">

    <!-- Header -->
    <header class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-slate-100">
        <div class="flex items-center gap-3 px-4 py-3">
            <button id="backBtn" aria-label="Kembali" class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-slate-100 text-slate-700 transition hover:bg-slate-200 active:scale-95">
                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 18l-6-6 6-6"/>
                </svg>
            </button>
            <div class="h-1 flex-1 rounded-full bg-slate-100 overflow-hidden">
                <div class="h-full w-full rounded-full bg-brand"></div>
            </div>
            <div class="shrink-0 text-[15px] font-bold tracking-[-0.04em] text-brand">BukaKios</div>
        </div>
    </header>

    <main class="mx-auto max-w-lg px-4 py-5">
        <!-- Produk -->
        <div class="mb-4 flex flex-col items-center text-center">
            <div class="inline-flex h-16 w-16 items-center justify-center overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-card">
                <?php if (!empty($product_logo)) { ?>
                    <img src="<?= $product_logo ?>" alt="<?= $product_name ?>" class="h-10 w-10 object-contain">
                <?php } else { ?>
                    <svg viewBox="0 0 24 24" class="h-7 w-7 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7l8-4 8 4v10l-8 4-8-4z"/><path d="M4 7l8 4 8-4M12 11v10"/></svg>
                <?php } ?>
            </div>
            <h1 class="m-0 mt-2.5 text-[15px] font-bold leading-tight text-slate-900"><?= $product_name ?></h1>
            <p class="m-0 mt-0.5 text-[14px] font-semibold text-slate-600"><?= $nomor_tujuan ?></p>
        </div>

        <div class="space-y-3.5">
            <!-- Status -->
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                <div class="flex items-center justify-between gap-3">
                    <?php if ($status == 1) { ?>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-[12px] font-bold text-emerald-600"><svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>Berhasil</span>
                    <?php } elseif ($status == 0) { ?>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-3 py-1.5 text-[12px] font-bold text-amber-600"><svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>Sedang Di Proses</span>
                    <?php } else { ?>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-rose-200 bg-rose-50 px-3 py-1.5 text-[12px] font-bold text-rose-600"><svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg>Refund</span>
                    <?php } ?>
                    <span class="text-[12px] font-medium text-slate-400">Pada <?= $app->time_ago($created_at) ?></span>
                </div>
            </div>

            <!-- Detail Pembelian -->
            <div class="rounded-2xl border border-slate-200 bg-white p-4 text-[13px]">
                <div class="mb-3 flex items-center gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand/10 text-brand"><svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="3" width="14" height="18" rx="2"/><path d="M9 8h6M9 12h6M9 16h4"/></svg></div>
                    <div><h3 class="m-0 text-[14px] font-bold leading-tight text-slate-900">Detail Pembelian</h3><p class="m-0 mt-0.5 text-[12px] font-medium text-slate-500">Informasi transaksi kamu</p></div>
                </div>
                <div class="flex items-center justify-between gap-2 border-b border-slate-100 py-2">
                    <span class="text-slate-500">ID Transaksi</span>
                    <span class="flex items-center gap-1.5">
                        <span id="copy_id" data-text="ID Transaksi Berhasil Disalin" data-copy="<?= $trx_id ?>" class="font-mono font-semibold text-slate-900">#<?= $trx_id ?></span>
                        <button onclick="copyToClipboard('copy_id')" type="button" aria-label="Salin ID Transaksi" class="inline-flex items-center justify-center rounded-md bg-slate-50 px-1.5 py-0.5 text-slate-500 hover:bg-slate-100 hover:text-slate-700 active:scale-95 transition">
                            <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        </button>
                        <a href="" aria-label="Refresh status" class="inline-flex items-center justify-center rounded-md px-1 py-0.5 text-slate-400 hover:text-brand active:scale-95 transition">
                            <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                        </a>
                    </span>
                </div>
                <div class="flex justify-between gap-3 border-b border-slate-100 py-2"><span class="shrink-0 text-slate-500">Produk</span><span class="text-right font-medium text-slate-900"><?= $product_name ?></span></div>
                <div class="flex justify-between gap-3 border-b border-slate-100 py-2"><span class="shrink-0 text-slate-500">No Tujuan</span><span class="break-words text-right font-medium text-slate-900"><?= $nomor_tujuan ?></span></div>
                <div class="flex justify-between gap-3 border-b border-slate-100 py-2"><span class="shrink-0 text-slate-500">Tanggal</span><span class="text-right font-medium text-slate-900"><?= datee($created_at) ?></span></div>
                <div class="flex justify-between gap-3 py-2">
                    <span class="shrink-0 text-slate-500">SN / Catatan</span>
                    <span class="break-words text-right font-medium text-slate-900"><?php if ($pembelian_kategori_id == 7) { echo $status_ppob; } else { echo $sn; } ?></span>
                </div>
            </div>

            <!-- Detail Pembayaran -->
            <div class="rounded-2xl border border-slate-200 bg-white p-4 text-[13px]">
                <div class="mb-3 flex items-center gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand/10 text-brand"><svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 10h18"/><circle cx="16" cy="14.5" r="1.2" fill="currentColor"/></svg></div>
                    <div><h3 class="m-0 text-[14px] font-bold leading-tight text-slate-900">Detail Pembayaran</h3><p class="m-0 mt-0.5 text-[12px] font-medium text-slate-500">Ringkasan saldo &amp; harga</p></div>
                </div>
                <div class="flex justify-between border-b border-slate-100 py-1.5"><span class="text-slate-500">Saldo Awal</span><span class="font-medium text-slate-900"><?= $app->idr($saldo_before_trx) ?></span></div>
                <div class="flex justify-between border-b border-slate-100 py-1.5"><span class="text-slate-500">Harga Produk</span><span class="font-medium text-slate-900"><?= $app->idr($price_client) ?></span></div>
                <div class="flex justify-between py-1.5"><span class="text-slate-500">Sisa Saldo</span><span class="font-semibold text-brand"><?= $app->idr($saldo_after_trx) ?></span></div>

                <div class="mt-2 rounded-xl border border-emerald-200 bg-emerald-50 px-3.5 py-3">
                    <div class="flex justify-between py-0.5">
                        <span class="text-emerald-700/80">Harga Jual Kamu</span>
                        <span class="flex items-center gap-1.5 font-bold text-emerald-700">
                            <span id="txt-harga-jual"><?= $app->idr($selling_price_client) ?></span>
                            <button type="button" id="btn-edit-harga" onclick="toggleEditHarga()" aria-label="Ubah harga jual" class="inline-flex items-center justify-center rounded-md px-1 py-0.5 text-emerald-600 hover:text-emerald-800 active:scale-95 transition">
                                <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.8 2.8 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                            </button>
                        </span>
                    </div>
                    <div class="flex justify-between py-0.5"><span class="text-emerald-700/80">Harga Modal/Produk</span><span class="font-bold text-rose-600"><?= $app->idr($price_client) ?></span></div>
                    <div class="mt-1 flex justify-between border-t border-emerald-200 pt-1.5"><span class="font-semibold text-emerald-800">Profit Kamu</span><span class="font-bold text-emerald-700" id="txt-profit"><?= $app->idr($selling_price_client - $price_client) ?></span></div>

                    <!-- Inline Ubah Harga Jual -->
                    <div id="edit-harga-box" style="display:none" class="mt-3 rounded-xl border border-emerald-200 bg-white p-3">
                        <p class="m-0 mb-1.5 text-[12px] font-bold text-emerald-800">Ubah Harga Jual</p>
                        <input type="number" id="fee-inline" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-[14px] font-semibold text-slate-900 outline-none focus:border-brand" value="<?= $selling_price_client ?>">
                        <div id="msg-invalid-inline" style="display:none" class="mt-1.5 text-[12px] font-medium text-rose-600"></div>
                        <div class="mt-2.5 grid grid-cols-2 gap-2.5">
                            <button type="button" id="btn-cancel-harga" onclick="toggleEditHarga()" class="rounded-xl border border-slate-200 py-2 text-[13px] font-semibold text-slate-700 transition hover:bg-slate-50">Batal</button>
                            <button type="button" id="btn-save-harga" onclick="submitHarga()" class="rounded-xl bg-brand py-2 text-[13px] font-bold text-white transition hover:bg-brandDark active:scale-[0.99]">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <?php if ($status > 0) {
                    require_once "additional_info_trx.php";
                } ?>
            </div>

            <?php if (isset($fakta_tipe)) { ?>
                <div class="flex items-start gap-2.5 rounded-2xl border px-4 py-3 text-[13px] <?= $fakta_tipe == 'success' ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : ($fakta_tipe == 'warning' ? 'border-amber-200 bg-amber-50 text-amber-700' : 'border-rose-200 bg-rose-50 text-rose-700') ?>">
                    <svg viewBox="0 0 24 24" class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                    <div class="min-w-0 leading-relaxed"><?= $fakta_teks ?></div>
                </div>
            <?php } ?>

            <!-- Aksi -->
            <div class="space-y-2.5">
                <?php if ($status == 1) { ?>
                    <div class="grid grid-cols-2 gap-2.5">
                        <a href='<?php echo "print://https://member.bukakios.net/print-json/$signature/$id.json"; ?>' class="flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white py-2.5 text-[13px] font-semibold text-slate-700 transition hover:bg-slate-50">
                            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                            Cetak Struk
                        </a>
                        <a href='<?php echo $open_url . "https://member.bukakios.net/pdf-mini/download/$signature/$id.pdf"; ?>' class="flex items-center justify-center gap-1.5 rounded-xl bg-brand/10 py-2.5 text-[13px] font-bold text-brand transition hover:bg-brand/20">
                            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Download Struk
                        </a>
                    </div>
                    <a href='<?php echo "catathutang://$msg"; ?>' class="flex w-full items-center justify-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-[13px] font-bold text-rose-700 transition active:scale-[0.99]">
                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                        Catat Hutang
                    </a>
                <?php } ?>

                <div class="rounded-2xl border border-slate-200 bg-white p-4 text-center">
                    <p class="m-0 text-[12px] font-medium text-slate-500">Butuh bantuan? hubungi customer care kami</p>
                    <div class="mt-2.5 grid grid-cols-2 gap-2.5">
                        <a href='<?php echo $openurl . $wa_komplain_link; ?>' class="flex items-center justify-center gap-1.5 rounded-xl bg-emerald-500 py-2 text-[13px] font-bold text-white transition hover:bg-emerald-600">
                            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            Via WhatsApp
                        </a>
                        <a href="../kontak/" class="flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 py-2 text-[13px] font-semibold text-slate-700 transition hover:bg-slate-50">
                            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            Kontak Lainnya
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Android-aware back button (pola reset-pin)
        (function() {
            function goBack(e) {
                e.preventDefault();
                if (window.android && typeof window.android.back === 'function') {
                    try { window.android.back(); return; } catch (_) {}
                }
                if (history.length > 1) { history.back(); }
                else { window.location.href = '<?= $c_url ?? "/" ?>'; }
            }
            var btn = document.getElementById('backBtn');
            if (btn) btn.addEventListener('click', goBack);
        })();
    </script>
    <script>
        var copyToasterTimeout = 0;
        var popupText = "";

        // Toast helper custom — Tailwind-style, fixed top-center, auto-hide 2.5s.
        function showToast(msg, kind) {
            if (!msg) return;
            var wrap = document.getElementById('bk-toast-wrap');
            if (!wrap) {
                wrap = document.createElement('div');
                wrap.id = 'bk-toast-wrap';
                wrap.className = 'bk-toast-wrap';
                document.body.appendChild(wrap);
            }
            var t = document.createElement('div');
            t.className = 'bk-toast bk-toast--' + (kind || 'success');
            t.innerHTML = '<span class="bk-toast-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><path d="M20 6L9 17l-5-5"/></svg></span><span class="bk-toast-text">' + msg + '</span>';
            wrap.appendChild(t);
            // trigger anim
            requestAnimationFrame(function () { t.classList.add('is-show'); });
            setTimeout(function () {
                t.classList.remove('is-show');
                setTimeout(function () { if (t.parentNode) t.parentNode.removeChild(t); }, 250);
            }, 2500);
        }

        function copyToClipboard(elementId) {
            var aux = document.createElement("input");
            aux.setAttribute("value", document.getElementById(elementId).getAttribute('data-copy'));
            document.body.appendChild(aux);
            if (navigator.userAgent.match(/ipad|ipod|iphone/i)) {
                var el = $(aux).get(0);
                var editable = el.contentEditable;
                var readOnly = el.readOnly;
                el.contentEditable = true;
                el.readOnly = false;
                var range = document.createRange();
                range.selectNodeContents(el);
                var sel = window.getSelection();
                sel.removeAllRanges();
                sel.addRange(range);
                el.setSelectionRange(0, 999999);
                el.contentEditable = editable;
                el.readOnly = readOnly;
            } else {
                aux.select();
            }
            document.execCommand("copy");
            aux.blur();
            document.body.removeChild(aux);
            var text = document.getElementById(elementId);
            popupText = text.getAttribute('data-text') || 'Berhasil disalin';
            window.clearTimeout(copyToasterTimeout);
            showToast(popupText, 'success');
        }

        // Toggle inline form ubah harga jual
        function toggleEditHarga() {
            var box = document.getElementById('edit-harga-box');
            var btn = document.getElementById('btn-edit-harga');
            var boxShow = box.style.display !== 'none';
            box.style.display = boxShow ? 'none' : 'block';
            btn.style.display = boxShow ? 'inline-flex' : 'none';
            if (!boxShow) {
                var inp = document.getElementById('fee-inline');
                inp.value = '<?= $selling_price_client ?>';
                showInvalid('');
            }
        }

        function showInvalid(msg) {
            var div = document.getElementById('msg-invalid-inline');
            var inp = document.getElementById('fee-inline');
            div.textContent = msg;
            div.style.display = msg ? 'block' : 'none';
            if (msg) { inp.classList.add('border-rose-400'); }
            else { inp.classList.remove('border-rose-400'); }
        }

        // Submit ubah harga jual via fetch (vanilla)
        function submitHarga() {
            var modal = <?= (int)$price_client ?>;
            var inp = document.getElementById('fee-inline');
            var simpan = document.getElementById('btn-save-harga');
            var batal = document.getElementById('btn-cancel-harga');
            var harga = Number(inp.value);
            var profit = harga - modal;

            if (!harga || harga < modal) {
                showInvalid('Harga jual tidak boleh dibawah harga modal');
                return;
            }
            if (profit > 50000) {
                showInvalid('Keuntungan tidak boleh diatas 50.000');
                return;
            }
            showInvalid('');

            simpan.disabled = true;
            batal.disabled = true;
            var csrf = '<?= $app->csrf() ?>';

            fetch('index.php?msg=update&harga=' + harga + '&csrf=' + csrf + '&id_trx=<?= $trx_id ?>')
                .then(function(r) { return r.json(); })
                .then(function(json) {
                    if (json.status == 1) {
                        showToast(json.msg || 'Harga jual berhasil diubah', 'success');
                        setTimeout(function() { location.reload(); }, 1200);
                    } else {
                        showToast(json.error_msg || 'Gagal mengubah harga jual', 'danger');
                    }
                })
                .catch(function() {
                    showToast('Gagal terhubung, coba lagi beberapa saat', 'danger');
                })
                .finally(function() {
                    simpan.disabled = false;
                    batal.disabled = false;
                });
        }
    </script>
<script>
    // Simpan status saat ini dari PHP
    currentStatus = <?php echo $status; ?>;

    // Fungsi untuk mengecek status transaksi
    function checkTransactionStatus() {
        fetch(`get_status.php?id=<?php echo $id; ?>`)
            .then(response => response.json())
            .then(data => {
                // Bandingkan status dari API dengan status saat ini
                if (data.status !== currentStatus) {
                    // Jika status berbeda, refresh halaman
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error checking status:', error);
            });
    }

    // Hanya jalankan interval jika status saat ini adalah 0 (pending)
    if (currentStatus === 0) {
        // Set interval untuk mengecek status setiap 3 detik (3000 ms)
        setInterval(checkTransactionStatus, 3000);
    }
</script>
</body>

</html>
