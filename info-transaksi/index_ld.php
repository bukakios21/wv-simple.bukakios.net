<?php
//echo "d";exit;
require_once "../config.php";
require_once "../_session.php";
$openurl = "open://";
$open_url = "open://";
// if ($user_id != 40408) {
//    if ($new_detail){
// $id = abs((int)$_GET['id']);
//    header("Location: https://wv3.bukakios.id/transaksi/$id");
//    exit();
//  }
// }

function getTransaksi($id_trx, $token_jwt)
{
    $url = "https://api-v2.bukakios.net/wv-x7Up2p/transaksi/" . $id_trx;

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Authorization: $token_jwt",
            "Api-key: PLowElenThErTeRAphaRDwINEAntrIDe",
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return "cURL Error: " . $err;
    } else {
        return $response;
    }
}

if ($user_id == 40408) {
    // $id = abs((int)$_GET['id']);
    // $response = getTransaksi($id, $user_jwt);
    // $transaksi = json_decode($response, true);
    // var_dump($transaksi["data"]);
    // exit;
}
// $user_id = "14353";
// $id = "11283";
//$user_id = "46873";
//$id = "589869";
//if (isset($_GET['id'])) {
//  $id = abs((int)$_GET['id']);
// header("Location: https://wv3.bukakios.id/transaksi/$id");
// exit();
//}
//if ($user_id == 28834 || $user_id == 278542 || $user_id == 40056) {
//  $id = abs((int)$_GET['id']);
// header("Location: https://wv3.bukakios.id/transaksi/$id");
//exit();
//}
if ($user_id != 39958) {
    // echo "maintenance";
    // exit;
}

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
    $id =  $_GET["id"];
    if ($user_id == 39958) {
     //   $id = 0;
    }
    /*
    $detail_transaksi = $db->fetch("select
	t.uid,t.pembelianoperator_id,t.pembeliankategori_id,t.trx_id,t.product_name,product_code,t.nomor_tujuan,t.id_pel,t.price_client,t.selling_price_client,
	t.sn,t.note,t.saldo_before_trx,t.saldo_after_trx,t.created_at,t.updated_at,t.status,
	o.product_name as op_name, o.product_logo
	from transaksi t inner join p_operator o
	on t.pembelianoperator_id=o.id
	where t.uid='$user_id' and t.id='$id'
	");
    if ($user_id != $detail_transaksi['uid']) {
        exit;
    }*/

    $data_post = [
        "key" => $api_key,
        "uid" => $user_id,
        "order_id" => $id,
    ];

    // $url = "$api_url/detail_transaksi_full.php";
    // $data_respon = $app->curl_post("$api_url/detail_transaksi_full.php", $data_post);
    // $data_respon = callApi($url, $data_post);
    // $transaksi = json_decode($data_respon, true);
    $response = getTransaksi($id, $user_jwt);
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

    if (isset($transaksi["h2h_id"])) {
        $h2h_id = $transaksi["h2h_id"];
        if ($h2h_id == 2566) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://svd.bukakios.net/voucher/detail/$sn");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $headers = ["Api-Key: A599084647F381FFFD16C6E5948341E65FC0D7AB"];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $call_api_svd = curl_exec($ch);
            curl_close($ch);

            $call_api_svd_res = json_decode($call_api_svd, true);
            if (isset($call_api_svd_res["status"])) {
                if ($call_api_svd_res["status"] == 0) {
                    $svd_error = true;
                    $svd_error_msg = $call_api_svd_res["error_msg"];
                } else {
                    $svd_data = $call_api_svd_res["data"];
                    $svd_error = false;
                    $svd_error_msg = "";
                }
            } else {
                $svd_error = true;
                $svd_error_msg = "Gagal menghubungi api SVD";
            }
        }
    }

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
    $wa_komplain_link =
        "https://api.whatsapp.com/send?phone=$wa_number&text=" .
        urlencode($teks_komplain);

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
			<p><a target='_blank' href='$link_open_rate' class='btn btn-warning btn-block btn-sm'><i class='font-icon font-icon-star'></i> Beri Rating</a>
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
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/notie/dist/notie.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#1a7fce',
                        brandDark: '#1265a6',
                        mutedText: '#6b7280',
                        line: '#d9e1e8'
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
    <title>title::Detail Transaksi #<?= $trx_id ?></title>
    <style>
        .notie-container {
            box-shadow: none;
        }

        body {
            font-family: Inter, ui-sans-serif, system-ui, Segoe UI, Roboto, Arial, sans-serif;
        }

        .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 50px;
            height: 50px;
            -webkit-animation: spin 5s linear infinite;
            /* Safari */
            animation: spin 2s linear infinite;
        }

        /* Safari */
        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* #3498db */
    </style>
</head>
<body class="min-h-screen bg-white font-sans text-slate-950">
    <?php
    $status_badge_class = 'bg-slate-100 text-slate-600 border-slate-200';
    $status_dot_class = 'bg-slate-400';
    if ($status == 0) {
        $status_badge_class = 'bg-amber-50 text-amber-600 border-amber-100';
        $status_dot_class = 'bg-amber-500';
    } elseif ($status == 1) {
        $status_badge_class = 'bg-emerald-50 text-emerald-600 border-emerald-100';
        $status_dot_class = 'bg-emerald-500';
    } elseif ($status >= 2) {
        $status_badge_class = 'bg-red-50 text-red-600 border-red-100';
        $status_dot_class = 'bg-red-500';
    }
    ?>
    <main class="relative min-h-screen w-full bg-white pb-8">
        <header class="sticky top-0 z-20 border-b border-slate-100 bg-white/95 px-5 pb-3 pt-4 backdrop-blur">
            <div class="flex items-center gap-3">
                <button type="button" onclick="history.back()" aria-label="Kembali" class="grid h-9 w-9 place-items-center rounded-full border border-slate-200 bg-white text-slate-700 transition active:scale-[0.98] hover:bg-slate-50">
                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6" /></svg>
                </button>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.08em] text-slate-400">BukaKios</p>
                    <h1 class="truncate text-[17px] font-black tracking-[-0.04em] text-slate-950">Detail Transaksi</h1>
                </div>
                <a href="" aria-label="Refresh" class="grid h-9 w-9 place-items-center rounded-full border border-slate-200 bg-white text-brand transition active:scale-[0.98] hover:bg-slate-50">
                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 1-15 6.7L3 16"/><path d="M3 16v5h5"/><path d="M3 12a9 9 0 0 1 15-6.7L21 8"/><path d="M21 8V3h-5"/></svg>
                </a>
            </div>
        </header>

        <section class="px-5 pt-5">
            <div class="overflow-hidden rounded-[24px] border border-slate-100 bg-white shadow-card">
                <div class="relative bg-gradient-to-br from-[#1a7fce] via-[#238ee2] to-[#1265a6] px-5 pb-14 pt-6 text-white">
                    <div class="pointer-events-none absolute -right-12 -top-12 h-40 w-40 rounded-full bg-white/10"></div>
                    <div class="pointer-events-none absolute right-12 top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="relative flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-[11px] font-extrabold uppercase tracking-[0.08em] text-white/70">Transaksi</p>
                            <h2 class="mt-1 text-[24px] font-black leading-7 tracking-[-0.055em]"><?= $st_label ?></h2>
                            <p class="mt-1 text-[12px] font-semibold text-white/80">Pada <?= $app->time_ago($created_at); ?></p>
                        </div>
                        <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full border border-white/20 bg-white/15 px-3 py-1 text-[11px] font-extrabold backdrop-blur">
                            <span class="h-1.5 w-1.5 rounded-full bg-white"></span><?= $status_ppob ?>
                        </span>
                    </div>
                </div>

                <div class="relative px-5 pb-5 pt-0">
                    <div class="-mt-10 flex justify-center">
                        <div class="grid h-[76px] w-[76px] place-items-center rounded-[24px] border-4 border-white bg-white shadow-soft">
                            <img src="<?= $product_logo ?>" class="h-14 w-14 rounded-2xl object-contain" alt="Produk">
                        </div>
                    </div>
                    <div class="mt-3 text-center">
                        <h3 class="text-[17px] font-black leading-5 tracking-[-0.035em]"><?= $product_name ?></h3>
                        <p class="mt-1 break-words text-[13px] font-extrabold text-slate-500"><?= $nomor_tujuan ?></p>
                    </div>

                    <div id="copy_idd" data-text="ID Transaksi Berhasil Disalin" data-copy="<?= $trx_id ?>"></div>
                    <div class="mt-5 rounded-[18px] border border-slate-200 bg-slate-50/70 px-4 py-3">
                        <div class="flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-[11px] font-bold text-slate-400">ID Transaksi</p>
                                <p class="mt-0.5 truncate text-[15px] font-black text-slate-950">#<?= $trx_id ?></p>
                            </div>
                            <button type="button" onclick="copyToClipboard('copy_idd')" class="shrink-0 rounded-full border border-brand/20 bg-white px-4 py-2 text-[12px] font-extrabold text-brand shadow-sm active:scale-[0.98]">Copy</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 rounded-[20px] border border-slate-200 bg-white px-4 py-4 shadow-soft">
                <div class="mb-3 flex items-center gap-2">
                    <span class="grid h-7 w-7 place-items-center rounded-xl bg-blue-50 text-brand"><i class="fa fa-info-circle text-[13px]"></i></span>
                    <h4 class="text-[15px] font-black tracking-[-0.035em]">Detail Pembelian</h4>
                </div>
                <div class="divide-y divide-slate-100">
                    <div class="flex gap-4 py-3"><span class="w-28 shrink-0 text-[12px] font-bold text-slate-400">Produk</span><span class="min-w-0 flex-1 break-words text-right text-[12px] font-extrabold text-slate-900"><?= $product_name ?></span></div>
                    <div class="flex gap-4 py-3"><span class="w-28 shrink-0 text-[12px] font-bold text-slate-400">No Tujuan</span><span class="min-w-0 flex-1 break-words text-right text-[12px] font-extrabold text-slate-900"><?= $nomor_tujuan ?></span></div>
                    <div class="flex gap-4 py-3"><span class="w-28 shrink-0 text-[12px] font-bold text-slate-400">Tanggal</span><span class="min-w-0 flex-1 break-words text-right text-[12px] font-extrabold text-slate-900"><?= datee($created_at) ?></span></div>
                    <div class="flex gap-4 py-3"><span class="w-28 shrink-0 text-[12px] font-bold text-slate-400">SN / Catatan</span><span class="min-w-0 flex-1 break-words text-right text-[12px] font-extrabold text-slate-900"><?= $pembelian_kategori_id == 7 ? $status_ppob : $sn ?></span></div>
                </div>
            </div>

            <div class="mt-4 rounded-[20px] border border-slate-200 bg-white px-4 py-4 shadow-soft">
                <div class="mb-3 flex items-center gap-2">
                    <span class="grid h-7 w-7 place-items-center rounded-xl bg-emerald-50 text-emerald-600"><i class="fa fa-money text-[13px]"></i></span>
                    <h4 class="text-[15px] font-black tracking-[-0.035em]">Detail Pembayaran</h4>
                </div>
                <div class="divide-y divide-slate-100">
                    <div class="flex items-center justify-between py-3"><span class="text-[12px] font-bold text-slate-400">Saldo Awal</span><span class="text-[13px] font-black text-slate-900"><?= $app->idr($saldo_before_trx) ?></span></div>
                    <div class="flex items-center justify-between py-3"><span class="text-[12px] font-bold text-slate-400">Harga Produk</span><span class="text-[13px] font-black text-slate-900"><?= $app->idr($price_client) ?></span></div>
                    <div class="flex items-center justify-between py-3"><span class="text-[12px] font-bold text-slate-400">Sisa Saldo</span><span class="text-[13px] font-black text-brand"><?= $app->idr($saldo_after_trx) ?></span></div>
                </div>
            </div>

            <div class="mt-4 rounded-[20px] border border-blue-100 bg-blue-50/70 px-4 py-4 shadow-soft">
                <div class="mb-3 flex items-center justify-between gap-2">
                    <h4 class="text-[15px] font-black tracking-[-0.035em] text-slate-950">Detail Keuntungan</h4>
                    <a data-toggle="modal" data-target="#exampleModal" class="rounded-full bg-white px-3 py-1.5 text-[11px] font-extrabold text-brand shadow-sm">Ubah</a>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between"><span class="text-[12px] font-bold text-slate-500">Harga Jual Kamu</span><span class="text-[13px] font-black text-slate-950"><?= $app->idr($selling_price_client) ?></span></div>
                    <div class="flex items-center justify-between"><span class="text-[12px] font-bold text-slate-500">Harga Modal/Produk</span><span class="text-[13px] font-black text-red-500"><?= $app->idr($price_client) ?></span></div>
                    <div class="rounded-2xl bg-white px-3 py-3"><div class="flex items-center justify-between"><span class="text-[12px] font-extrabold text-emerald-600">Profit Kamu</span><span class="text-[15px] font-black text-emerald-600"><?= $app->idr($selling_price_client - $price_client) ?></span></div></div>
                </div>
            </div>

            <?php if (isset($h2h_id)) { ?>
                <?php if (isset($svd_error)) { ?>
                    <?php if ($svd_error) { ?>
                        <div class="mt-4 rounded-[18px] border border-amber-100 bg-amber-50 px-4 py-3 text-[13px] font-semibold text-amber-700"><?php echo $svd_error_msg; ?></div>
                    <?php } else { ?>
                        <div class="mt-4 rounded-[20px] border border-slate-200 bg-white px-4 py-4 text-center shadow-soft">
                            <h4 class="mb-3 text-[15px] font-black tracking-[-0.035em]">Voucher Kamu</h4>
                            <img class="mx-auto w-4/5 rounded-2xl" src="<?php echo $svd_data["voucher_image"]; ?> " alt="Voucher">
                        </div>
                    <?php } ?>
                <?php } ?>
            <?php } ?>

            <div class="mt-4">
                <?php if ($status > 0) {
                    require_once "additional_info_trx.php";
                } ?>
            </div>

            <?php if (isset($fakta_tipe)) { ?>
                <div class="mt-4 rounded-[20px] border border-slate-200 bg-white px-4 py-4 text-[13px] leading-5 text-slate-600 shadow-soft">
                    <?php echo $fakta_teks; ?>
                </div>
            <?php } ?>

            <div class="mt-5 space-y-3">
                <a href="https://wv.bukakios.net/beli_lagi/index.php?nomor_tujuan=<?= $nomor_tujuan ?>&kode_produk=<?= $product_code ?>" class="flex h-[52px] w-full items-center justify-center rounded-full bg-emerald-600 text-[14px] font-extrabold text-white shadow-soft transition active:scale-[0.99] <?= $user_id != "40408" ? "pointer-events-none opacity-50" : "" ?>"><i class="fa fa-shopping-cart mr-2"></i> Beli Lagi</a>

                <?php if ($status == 1) { ?>
                    <div class="grid grid-cols-2 gap-3">
                        <a href='<?php echo "print://https://member.bukakios.net/print-json/$signature/$id.json"; ?>' class='flex h-[50px] items-center justify-center rounded-full border border-slate-200 bg-white text-[13px] font-extrabold text-slate-700 shadow-sm'>Cetak Struk</a>
                        <a href='<?php echo $open_url . "https://member.bukakios.net/pdf-mini/download/$signature/$id.pdf"; ?>' class='flex h-[50px] items-center justify-center rounded-full bg-brand text-[13px] font-extrabold text-white shadow-cta'>Download</a>
                    </div>
                    <div class='rounded-[20px] border border-red-100 bg-red-50 px-4 py-4 text-[13px] font-semibold leading-5 text-red-600'>
                        Transaksi ini dihutangi pelanggan? Catat agar tidak lupa.
                        <a href='<?php echo "catathutang://$msg"; ?>' class='mt-3 flex h-[48px] w-full items-center justify-center rounded-full bg-red-500 text-[13px] font-extrabold text-white shadow-soft'>Catat Hutang</a>
                    </div>
                <?php } ?>

                <div class='rounded-[20px] border border-slate-200 bg-white px-4 py-4 shadow-soft'>
                    <h4 class="text-[15px] font-black tracking-[-0.035em]">Butuh Bantuan?</h4>
                    <p class="mt-1 text-[12px] leading-5 text-slate-500">Hubungi customer care BukaKios jika transaksi kamu membutuhkan bantuan.</p>
                    <div class="mt-4 grid grid-cols-1 gap-3">
                        <a href='<?php echo $openurl . $wa_komplain_link; ?>' class='flex h-[50px] w-full items-center justify-center rounded-full bg-emerald-600 text-[13px] font-extrabold text-white shadow-soft'><i class="fa fa-whatsapp mr-2"></i> Via WhatsApp</a>
                        <a href='../kontak/' class='flex h-[50px] w-full items-center justify-center rounded-full border border-brand/20 bg-blue-50 text-[13px] font-extrabold text-brand'><i class="fa fa-envelope mr-2"></i> Via Kontak Lainnya</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ubah Harga Jual Kamu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="loader mx-auto mt-4" id="load" style="display:none"></div>
                    <div class="text-center mt-1">
                        <span class="text-muted" style="font-weight:bold;display:none" id="wait">Please Wait...</span>
                        <div class="text-muted" style="font-weight:bold;display:none" id="msg"></div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-2">
                            <input type="number" id="fee" class="form-control" value="<?php echo $selling_price_client; ?>">
                            <div id="msg-invalid" style="display:none" class="invalid-feedback">

                            </div>
                            <input type="hidden" id="csrf" class="form-control" value="<?= $app->csrf() ?>">
                        </div>
                        <div class="col-6 text-center ">
                            <button type="button" class="btn btn-danger btn-block" id="cancel" data-dismiss="modal">Tidak</button>
                        </div>
                        <div class="col-6 text-center">
                            <button type="button" onclick="change()" id="change" class="btn btn-success btn-block">Ubah</button>
                        </div>

                        <div class="col-12 text-center">
                            <a href="" id="refresh" class="btn btn-primary " style="width:150px;display:none">
                                <img src='https://assets.bukakios.net/img2/uploads/2021/02/233-569-refresh.png' style='height:20px;width:20px;margin-left:2px;margin-top:-2px' />
                            </a>
                        </div>
                    </div>
                </div>
                <!-- <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div> -->
            </div>
        </div>
    </div>

    <div class="footer">
        <!-- <div class="mx-3 my-3">
        <a href="livechat://open.it">Need help?</a>
    </div> -->
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/sweetalert.min.js"></script>
    <script src="https://unpkg.com/notie"></script>
    <!-- <script src="https://member.bukakios.net/js/lib/notie/notie.js"></script> -->
    <!-- <script src="https://member.bukakios.net/js/me/copy.js"></script> -->
    <script>
        $(document).ready(function() {
            $('#myModal').on('shown.bs.modal', function() {
                $('#myInput').trigger('focus')
            })
        })

        var copyToasterTimeout = 0;
        var popupText = "";

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
            popupText = text.getAttribute('data-text');
            console.log(popupText);
            //$('.popup-action__text').text(popupText);
            /// $('.popup-action').addClass('active');
            //$('.popup-action__text').text(popupText);
            window.clearTimeout(copyToasterTimeout);
            notie.alert({
                type: 'success',
                text: popupText,
                time: 2
            }) // Hides after 2 seconds
        }

        function change() {
            var feee = document.getElementById("fee");
            var modal = <?php echo $price_client; ?>;
            var harga = $('#fee').val();
            var profit = Number(harga) - Number(modal);
            var csrf = $('#csrf').val();
            if (harga < modal) {
                $('#msg-invalid').show();
                document.getElementById("msg-invalid").textContent = "Harga Jual Tidak Boleh Dibawah Harga Modal";
                feee.classList.add("is-invalid");
            } else if (profit > 50000) {
                $('#msg-invalid').show();
                document.getElementById("msg-invalid").textContent = "Keuntungan Tidak Boleh Diatas 50.000";
                feee.classList.add("is-invalid");
            } else if (harga > modal && profit < 50000) {
                // console.log(harga);
                $('#fee').hide();
                $('#msg-invalid').hide();
                $('#cancel').hide();
                $('#change').hide();
                $('#load').show();
                $('#wait').show();
                $.ajax({
                    url: 'index.php?msg=update&harga=' + harga + '&csrf=' + csrf + '&id_trx=<?php echo $trx_id; ?>',
                    success: function(output) {
                        $('#load').hide();
                        $('#refresh').show();
                        $('#wait').hide();
                        $('#msg').show();
                        var myJsn = JSON.parse(output);
                        var d = myJsn.msg;
                        console.log(d);
                        if (myJsn.status == 1) {
                            var dataJsn = myJsn.msg;
                            console.log(dataJsn);
                            document.getElementById("msg").textContent = myJsn.msg;

                        } else {
                            // console.log(myJsn.error_msg)
                            document.getElementById("msg").textContent = myJsn.error_msg;
                        }
                    }
                })
            }
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
