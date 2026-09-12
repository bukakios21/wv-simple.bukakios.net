<?php
require_once("../config.php");
require_once("../_session.php");
require_once('../lib/ApiV2.php');

$api_v2 = new ApiV2($user_jwt);


if (isset($_GET['id'])) {
    $topup_id = abs((int) $_GET['id']);

    // ambil detail topup dari API V2 (ganti DB lokal)
    $resApi = $api_v2->topup_detail($topup_id);
    $detail_topup = json_decode($resApi, true);

    if (!isset($detail_topup['status']) || $detail_topup['status'] != 1 || empty($detail_topup['data']['uid'])) {
        require_once "404.php";
        exit;
    }


    // flatten response: dari {data: {...}} jadi variabel yang sama seperti legacy
    // agar 80+ file di detail_topup/*.php tetap kompatibel tanpa diubah.
    $detail_topup = $detail_topup['data'];

    $total_transfer_rp = $app->idr($detail_topup['total_transfer']);
    $topup_metode_kategori = $detail_topup['topup_metode_kategori'];
    $nama_kategori = $detail_topup['nama_kategori'];
    $metode_id = $detail_topup['metode_id'];
    // metode_id 29 = Klaim Komisi Referral. Topup ini di-approve manual oleh,
    // admin (bukan pembayaran user), jadi: JANGAN load info payment (Tokopay/
    // rekening) dan JANGAN tampilkan tombol Batalkan Topup.
    $is_klaim_komisi = ((int) $metode_id === 29);
    $uid = $detail_topup['uid'];
    $nama_metode = $detail_topup['nama_metode'];
    $topup_metode = $detail_topup['topup_metode'];
    $nominal_topup = $detail_topup['nominal_topup'];
    $kode_unik = $detail_topup['kode_unik'];
    $fee = $detail_topup['fee'];
    $total_transfer = $detail_topup['total_transfer'];
    // Backend: data.nomor_rekening = t.nomor_rekening (rekening_pg), data.nomor_rekening_bk = m.nomor_rekening
    // Legacy PHP behavior: $nomor_rekening akhir yang dipakai = m.nomor_rekening (= nomor_rekening_bk)
    $nomor_rekening_topup = $detail_topup['nomor_rekening'];
    $nomor_rekening = $detail_topup['nomor_rekening_bk'];
    $created_at = $detail_topup['created_at'];
    $expired_at = $detail_topup['expired_at'];
    $status = $detail_topup['status'];
    $gambar_metode = $detail_topup['gambar_metode'];
    $nama_rekening = $detail_topup['nama_rekening'];
    $terima_bersih = $total_transfer + $fee;
    if ($topup_metode_kategori != 1) {
        $terima_bersih = $total_transfer - $fee;
    }
    $hash_topup = md5("$topup_id:$uid:$topup_metode:$created_at");

        // Payment info: kalau status masih pending, ambil instruksi bayar dari BE.
        // - kategori BANK (topup_metode_kategori == 1): return rekening manual,
        //   sehingga FE tidak perlu render DB nomor_rekening manual lagi (konsisten).
        // - selainnya (qris/va/ewallet): return QR / VA / link dari Tokopay.
        // Response shape: { topup_id, tipe, nilai, cara_bayar, expired_at }
        // Klaim komisi (metode 29): skip fetch payment sepenuhnya. Approval
        // manual admin, tidak ada instruksi bayar yang perlu ditampilkan.
        $payment_info = null;
        $payment_error = null;
        if ((int)$status === 0 && !$is_klaim_komisi) {
            $resPay = $api_v2->topup_payment($topup_id);
            //var_dump($resPay); //ini jangan dihapusm
            $resPayJson = json_decode($resPay, true);
            if (is_array($resPayJson) && isset($resPayJson['status']) && (int)$resPayJson['status'] === 1
                && isset($resPayJson['data']['tipe'])) {
                $payment_info = $resPayJson['data'];
            } else {
                $payment_error = is_array($resPayJson) && isset($resPayJson['error_msg'])
                    ? $resPayJson['error_msg']
                    : 'Gagal memuat info pembayaran';
            }
        }
    // Format created_at (ISO/RFC3339 atau "YYYY-MM-DD HH:MM:SS") jadi "09 Sep 2026, 14:00 WIB"
    function _formatTopupDate($s) {
        if (!$s) return '-';
        $ts = strtotime($s);
        if (!$ts) return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
        return date('d M Y, H:i', $ts) . ' WIB';
    }
    // _renderCaraBayar: sanitize & render instruksi bayar dari Tokopay.
    // Tokopay kirim HTML berisi <p>...</p> per step (mis. "<p>1. Datang ke...</p><p>2. ...").
    // Approach:
    //   1. Decode HTML entities supaya tag jadi tag beneran (kalau JSON-encoded string)
    //   2. Whitelist tag yang aman: <p>, <strong>, <b>, <br>
    //   3. Return HTML bersih; tiap <p> jadi paragraf terpisah (margin-bottom via Tailwind [&>p])
    function _renderCaraBayar($s) {
        if (!$s) return '';
        // Decode entities supaya tag tidak double-escape (JSON balikin "<p>...</p>" mentah)
        $s = html_entity_decode($s, ENT_QUOTES, 'UTF-8');
        // Whitelist tag aman: <p>, <strong>, <b>, <br>, <em>, <i>
        $allowed = '<p><strong><b><br><em><i>';
        $s = strip_tags($s, $allowed);
        return $s;
    }
    $created_at_fmt = _formatTopupDate($created_at);
    // Tampilkan baris Kode Unik hanya untuk kategori Transfer Bank (kategori = 1)
    // atau kategori yang mengandung kata 'bank' (mis. 'Virtual Account Bank').
    $show_kode_unik = ((string)$topup_metode_kategori === '1')
        || (stripos((string)$nama_kategori, 'bank') !== false);
    $teks_komplain = "";
    if ($status == 0) {
        $st_image = "https://assets.bukakios.net/img2/uploads/2019/12/827-sand-clock.png";
        $statusnya = " <span class='badge badge-warning mx-auto text-center mt-1' style='color:white;padding:5px 10px 5px 10px'>Menunggu Pembayaran</span>";
        $st_label = "Topup Pending";
        $teks_komplain = "Topup #$topup_id pending, bantu kak";
    } else if ($status == 1) {
        $st_image = "https://assets.bukakios.net/img2/uploads/2019/12/474-checked.png";
        $statusnya = " <span class='badge badge-success mx-auto text-center mt-1' style='color:white;padding:5px 10px 5px 10px'>Topup Berhasil</span>";
        $st_label = "Topup Berhasil";
        $teks_komplain = "Topup #$topup_id sukses, tapi masih ada kendala kak";
    } else {
        $st_image = "https://assets.bukakios.net/img2/uploads/2019/12/822-button.png";
        $statusnya = " <span class='badge badge-danger mx-auto text-center mt-1' style='color:white;padding:5px 10px 5px 10px'>Topup Dibatalkan</span>";
        $st_label = "Topup Di Batalkan";
        $teks_komplain = "Topup #$topup_id expirated, mohon di bantu kak";
    }
    $wa_komplain_link = wa_link($teks_komplain);

    //tambahan url background
    if ($topup_metode == 2 or $topup_metode == 4 or $topup_metode == 1 or $topup_metode == 43 or $topup_metode == 3) {
        if ($topup_metode == 2) {
            // $url_bg = "https://assets.bukakios.net/img2/uploads/2022/06/651-mandiri-2.png";
        } else if ($topup_metode == 4) {
            $url_bg = "https://assets.bukakios.net/img2/uploads/2022/06/689-bca-2-2.png";
        } else if ($topup_metode == 1) {
            //$url_bg = "https://assets.bukakios.net/img2/uploads/2022/07/516-perubahan-rek-bri-popup.png";
            //$url_bg = "https://assets.bukakios.net/img2/uploads/2022/11/959-popup-perubahan-bank-bri.png";
            // $url_bg = "https://assets.bukakios.net/img2/uploads/2022/12/557-pop-up-top-up-bri.png";
            //$url_bg = "https://assets.bukakios.net/img2/uploads/2023/01/946-bri-resize.png";
            //$url_bg = "https://assets.bukakios.net/img2/uploads/2023/01/662-img1605.png";
            //$url_bg = "https://assets.bukakios.net/img2/uploads/2023/02/522-popup-perubahan-bank-bri.png";
            // $url_bg = "https://assets2.bukakios.net/img2/uploads/2024/09/737-bri-ganti-ke-307.png";
        }else if ($topup_metode == 43) {
            // $url_bg = "https://assets2.bukakios.net/img2/uploads/2025/05/584-ppup-buat-tiket-bsi.png";
        }else if ($topup_metode == 3) {
	    // $url_bg = "https://assets2.bukakios.net/img2/uploads/2025/10/370-pop-up---bni-ganti-rek-10-okt-2025.png";
        }
    }

    if (isset($_GET['act'])) {
        //batalkan topup
        $act = $_REQUEST['act'];
        if ($act == 'cancel') {
            if (isset($_GET['id']) || isset($_POST['id'])) {
                $id = abs((int) ($_GET['id'] ?? $_POST['id']));

                require_once('../lib/ApiV2.php');
                $api_v2 = new ApiV2($user_jwt);
                $resApi = $api_v2->topup_cancel($id);
                //$app->simpan_file("resapi.txt", $resApi);

                // Kalau request via AJAX (XMLHttpRequest / fetch), balikin JSON.
                // Kalau browser navigation biasa (legacy), redirect seperti biasa.
                $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
                    && strcasecmp($_SERVER['HTTP_X_REQUESTED_WITH'], 'XMLHttpRequest') === 0;
                if ($isAjax) {
                    header('Content-Type: application/json; charset=utf-8');
                    $resp = json_decode($resApi, true);
                    if (is_array($resp) && isset($resp['status']) && (int)$resp['status'] === 1) {
                        echo json_encode(['status' => 1, 'msg' => 'Topup berhasil dibatalkan']);
                    } else {
                        $msg = (is_array($resp) && isset($resp['error_msg'])) ? $resp['error_msg'] : 'Gagal membatalkan topup';
                        echo json_encode(['status' => 0, 'msg' => $msg]);
                    }
                    exit;
                }

                header("location:$c_url/info-topup/?id=$topup_id&s=1");
                exit;
            }
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
    <!---link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous"--->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css" integrity="sha512-rt/SrQ4UNIaGfDyEXZtNcyWvQeOq0QLygHluFQcSjaGB04IxWhal71tKuzP6K8eYXYB6vJV4pHkXcmFGGQ1/0w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Nunito" />
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>title:detail topup pending</title>
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
            font-size: 14px;
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

        .body {
            left: 0;
            bottom: 0;
            width: 100%;
            height: 80%;
            background-color: #fff;
            border-radius: 0px 0px 0px 0px;
            margin-bottom: 10px;
            padding-top: 4px;
        }

        .footer {
            /* position: fixed; */
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: #fff;
            color: #000;
            text-align: center;
            padding-top: 2px;
            padding-bottom: 10px;
        }

        .jumbotron {
            border-radius: 50px;
        }

        body {
            font-family: Nunito;
        }

        .card {
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
        }

        .bayar-id {
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
        }

        /* #3498db */


        a.disabled {
            pointer-events: none;
            cursor: default;
            color: grey;
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

        hr {
            border: 2px dashed #9C9C9C;
        }

        #border-radius-bottom {
            -moz-border-radius-bottomleft: 100%60px;
            -webkit-border-bottom-left-radius: 100%60px;
            border-bottom-left-radius: 100%60px;
            -moz-border-radius-bottomright: 100%60px;
            -webkit-border-bottom-right-radius: 100%60px;
            border-bottom-right-radius: 100%60px;
        }

        .cir {
            position: absolute;
            margin-left: 10px;
            margin-top: 122px;
            z-index: 1;
            width: 20px;
            height: 20px;
            background-color: #fff;
            box-shadow: inset -4px 0px 0px rgba(0, 0, 0, 0.16);
            border-radius: 50px;
        }

        .circle {
            position: absolute;
            right: 0px;
            margin-right: 12px;
            margin-top: 121px;
            z-index: 1;
            width: 20px;
            height: 20px;
            background-color: #fff;
            box-shadow: inset 4px 0px 0px rgba(0, 0, 0, 0.16);
            border-radius: 50px;
        }

        @media screen and (min-width: 576px) {
            .circle {
                margin-right: 30px;
            }

            .cir {
                margin-left: 28px;
            }
        }

        @media screen and (min-width: 590px) {
            .circle {
                margin-right: 35px;
            }

            .cir {
                margin-left: 34px;
            }
        }

        @media screen and (min-width: 600px) {
            .circle {
                margin-right: 40px;
            }

            .cir {
                margin-left: 39px;
            }
        }

        @media screen and (min-width: 610px) {
            .circle {
                margin-right: 45px;
            }

            .cir {
                margin-left: 43px;
            }
        }

        @media screen and (min-width: 620px) {
            .circle {
                margin-right: 50px;
            }

            .cir {
                margin-left: 48px;
            }
        }

        .wrap {
            position: absolute;
            bottom: 0;
            top: 0;
            left: 0;
            right: 0;
            margin: auto;
            height: 310px;
        }

        a {
            text-decoration: none;
            color: #1a1a1a;
        }

        h1 {
            margin-bottom: 60px;
            text-align: center;
            font: 300 2.25em 'Lato';
            text-transform: uppercase;
        }

        h1 strong {
            font-weight: 400;
            color: #ea4c4c;
        }

        h2 {
            margin-bottom: 80px;
            text-align: center;
            font: 300 0.7em 'Lato';
            text-transform: uppercase;
        }

        h2 strong {
            font-weight: 400;
        }

        .countdown {
            width: auto;
            margin: 0 auto;
        }

        .countdown .bloc-time {
            float: left;
            margin-right: 20px;
            text-align: center;
        }

        .countdown .bloc-time:last-child {
            margin-right: 0;
        }

        .countdown .count-title {
            display: block;
            margin-bottom: 10px;
            font: normal 0.55em 'Lato';
            color: #1a1a1a;
            text-transform: uppercase;
        }

        .countdown .figure {
            position: relative;
            float: left;
            height: 35px;
            width: 28px;
            margin-right: 5px;
            background-color: #fff;
            border-radius: 3px;
            -moz-box-shadow: 0 3px 4px 0 rgba(0, 0, 0, 0.2),
                inset 2px 4px 0 0 rgba(255, 255, 255, 0.08);
            -webkit-box-shadow: 0 3px 4px 0 rgba(0, 0, 0, 0.2),
                inset 2px 4px 0 0 rgba(255, 255, 255, 0.08);
            box-shadow: 0 3px 4px 0 rgba(0, 0, 0, 0.2),
                inset 2px 4px 0 0 rgba(255, 255, 255, 0.08);
        }

        .countdown .figure:last-child {
            margin-right: 0;
        }

        .countdown .figure>span {
            position: absolute;
            left: 0;
            right: 0;
            margin: auto;
            font: normal 25px/30px 'Lato';
            font-weight: 400;
            color: #2196F3;
        }

        .countdown .figure .top:after,
        .countdown .figure .bottom-back:after {
            content: '';
            position: absolute;
            z-index: -1;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .countdown .figure .top {
            z-index: 3;
            background-color: #f7f7f7;
            transform-origin: 50% 100%;
            -webkit-transform-origin: 50% 100%;
            -moz-border-radius-topleft: 10px;
            -webkit-border-top-left-radius: 10px;
            border-top-left-radius: 10px;
            -moz-border-radius-topright: 10px;
            -webkit-border-top-right-radius: 10px;
            border-top-right-radius: 10px;
            -moz-transform: perspective(200px);
            -ms-transform: perspective(200px);
            -webkit-transform: perspective(200px);
            transform: perspective(200px);
        }

        .countdown .figure .bottom {
            z-index: 1;
        }

        .countdown .figure .bottom:before {
            content: '';
            position: absolute;
            display: block;
            top: 0;
            left: 0;
            width: 100%;
            height: 50%;
            background-color: rgba(0, 0, 0, 0.02);
        }

        .countdown .figure .bottom-back {
            z-index: 2;
            top: 0;
            height: 50%;
            overflow: hidden;
            background-color: #f7f7f7;
            -moz-border-radius-topleft: 10px;
            -webkit-border-top-left-radius: 10px;
            border-top-left-radius: 10px;
            -moz-border-radius-topright: 10px;
            -webkit-border-top-right-radius: 10px;
            border-top-right-radius: 10px;
        }

        .countdown .figure .bottom-back span {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            margin: auto;
        }

        .countdown .figure .top,
        .countdown .figure .top-back {
            height: 50%;
            overflow: hidden;
            -moz-backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
        }

        .countdown .figure .top-back {
            z-index: 4;
            bottom: 0;
            background-color: #fff;
            -webkit-transform-origin: 50% 0;
            transform-origin: 50% 0;
            -moz-transform: perspective(200px) rotateX(180deg);
            -ms-transform: perspective(200px) rotateX(180deg);
            -webkit-transform: perspective(200px) rotateX(180deg);
            transform: perspective(200px) rotateX(180deg);
            -moz-border-radius-bottomleft: 10px;
            -webkit-border-bottom-left-radius: 10px;
            border-bottom-left-radius: 10px;
            -moz-border-radius-bottomright: 10px;
            -webkit-border-bottom-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .countdown .figure .top-back span {
            position: absolute;
            top: -100%;
            left: 0;
            right: 0;
            margin: auto;
        }

        .box-money {
            background-color: <?= $primary ?>;
            color: #fff;
            border-radius: 100px;
            padding-top: 8px;
            padding-bottom: 1px;
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
            <div class="shrink-0 text-[16px] font-bold tracking-[-0.04em] text-brand">BukaKios</div>
        </div>
    </header>

    <main class="mx-auto max-w-lg px-4 py-5">
        <div class="mb-4 flex justify-center">
            <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl <?= $status == 1 ? 'bg-emerald-100 text-emerald-600' : ($status == 2 ? 'bg-rose-100 text-rose-600' : 'bg-amber-100 text-amber-600') ?>">
                <?php if ($status == 1) { ?>
                    <svg viewBox="0 0 24 24" class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 12.5l2.5 2.5L16 9"/></svg>
                <?php } elseif ($status == 2) { ?>
                    <svg viewBox="0 0 24 24" class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
                <?php } else { ?>
                    <svg viewBox="0 0 24 24" class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                <?php } ?>
            </div>
        </div>

        <div class="space-y-3.5">
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                <div class="flex items-center justify-between gap-3">
                    <?php if ($status == 1) { ?>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-[13px] font-bold text-emerald-600"><svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>Topup Berhasil</span>
                    <?php } elseif ($status == 2) { ?>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-rose-200 bg-rose-50 px-3 py-1.5 text-[13px] font-bold text-rose-600"><svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg>Topup Dibatalkan</span>
                    <?php } elseif ($is_klaim_komisi) { ?>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-3 py-1.5 text-[13px] font-bold text-amber-600"><svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>Menunggu Approval</span>
                    <?php } else { ?>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-3 py-1.5 text-[13px] font-bold text-amber-600"><svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>Menunggu Pembayaran</span>
                        <span id="countdown" class="font-mono text-[14px] font-bold tabular-nums text-amber-600">--:--:--</span>
                    <?php } ?>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-4 text-[14px]">
                <div class="mb-3 flex items-center gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand/10 text-brand"><svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 10h18"/><circle cx="16" cy="14.5" r="1.2" fill="currentColor"/></svg></div>
                    <div><h3 class="m-0 text-[15px] font-bold leading-tight text-slate-900">Rincian Nominal</h3><p class="m-0 mt-0.5 text-[13px] font-medium text-slate-500"><?= $is_klaim_komisi ? 'Nominal komisi yang akan diterima' : 'Pastikan bayar sesuai total transfer' ?></p></div>
                </div>
                <div class="flex items-center justify-between py-1.5 border-b border-slate-100 mb-1">
                    <span class="text-slate-500">Topup ID</span>
                    <span class="flex items-center gap-1.5">
                        <span id="copy_topup_id" data-text="Topup ID Berhasil Disalin" data-copy="<?= $topup_id ?>" class="font-mono font-medium text-slate-900">#<?= $topup_id ?></span>
                        <button onclick="copyToClipboard('copy_topup_id')" type="button" aria-label="Salin Topup ID" class="inline-flex items-center justify-center rounded-md bg-slate-50 px-1.5 py-0.5 text-slate-500 hover:bg-slate-100 hover:text-slate-700 active:scale-95 transition">
                            <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        </button>
                    </span>
                </div>
                <?php if ($topup_metode == 30) { ?>
                    <div class="flex justify-between py-1"><span class="text-slate-500">Nominal Saldo Masuk</span><span class="font-medium text-slate-900"><?= $app->idr($nominal_topup) ?></span></div>
                    <?php if ($show_kode_unik) { ?><div class="flex justify-between py-1"><span class="text-slate-500">Kode Unik</span><span class="font-medium text-slate-900"><?= $app->idr($kode_unik) ?></span></div><?php } ?>
                    <div class="flex justify-between py-1"><span class="text-slate-500">Potongan Rate</span><span class="font-medium text-slate-900"><?= $app->idr($fee) ?></span></div>
                <?php } elseif ($metode_id == 40) { $disc = $nominal_topup * 0.01; ?>
                    <div class="flex justify-between py-1"><span class="text-slate-500">Total Tagihan</span><span class="font-medium text-slate-900"><?= $app->idr($nominal_topup) ?></span></div>
                    <div class="flex justify-between py-1"><span class="text-slate-500">Diskon</span><span class="font-medium text-emerald-600">−<?= $app->idr($disc) ?></span></div>
                    <div class="flex justify-between py-1"><span class="text-slate-500">Biaya Admin</span><span class="font-medium text-slate-900"><?= $app->idr($fee) ?></span></div>
                <?php } else { ?>
                    <div class="flex justify-between py-1"><span class="text-slate-500">Total Tagihan</span><span class="font-medium text-slate-900"><?= $app->idr($nominal_topup) ?></span></div>
                    <?php if ($show_kode_unik) { ?><div class="flex justify-between py-1"><span class="text-slate-500">Kode Unik</span><span class="font-medium text-slate-900"><?= $app->idr($kode_unik) ?></span></div><?php } ?>
                    <div class="flex justify-between py-1"><span class="text-slate-500">Biaya Admin</span><span class="font-medium text-slate-900"><?= $app->idr($fee) ?></span></div>
                <?php } ?>
                <div class="mt-2 flex items-center justify-between border-t border-slate-200 pt-2.5">
                    <span class="font-semibold text-slate-700"><?= $is_klaim_komisi ? 'Total Diterima' : 'Total Transfer' ?></span>
                    <button id="nominal_transfer" onclick="copyToClipboard('nominal_transfer')" data-text="Jumlah Transfer Berhasil Di Salin" data-copy="<?= ($metode_id == 40) ? $app->idr($nominal_topup + $fee) : $total_transfer ?>" type="button" class="text-right text-[16px] font-bold text-brand active:scale-95"><?php if ($metode_id == 40) { echo $app->idr($nominal_topup + $fee); } else { echo $total_transfer_rp; } ?></button>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                <div class="mb-3 flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 overflow-hidden">
                        <?php if (!empty($gambar_metode)) { ?><img src="<?= $gambar_metode ?>" alt="<?= $nama_metode ?>" class="h-7 w-7 object-contain"><?php } else { ?><svg viewBox="0 0 24 24" class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 10h18"/></svg><?php } ?>
                    </div>
                    <div class="min-w-0 flex-1"><h3 class="m-0 truncate text-[15px] font-bold leading-tight text-slate-900"><?= $nama_metode ?></h3><p class="m-0 mt-0.5 truncate text-[12px] capitalize text-slate-400"><?= $nama_kategori ?></p></div>
                </div>
                <?php if ($is_klaim_komisi) { ?>
                    <div class="flex items-start gap-2.5 rounded-xl border border-amber-200 bg-amber-50 px-3.5 py-3">
                        <svg viewBox="0 0 24 24" class="mt-0.5 h-4 w-4 shrink-0 text-amber-500" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                        <div class="min-w-0">
                            <p class="m-0 text-[13px] font-bold text-amber-700">Menunggu persetujuan admin</p>
                            <p class="m-0 mt-0.5 break-words text-[13px] text-amber-600">Penarikan komisi kamu sedang diproses dan akan di-approve manual oleh admin. Mohon menunggu.</p>
                        </div>
                    </div>
                <?php } elseif (!empty($payment_error)) { ?>
                    <div class="flex items-start gap-2.5 rounded-xl border border-rose-200 bg-rose-50 px-3.5 py-3">
                        <svg viewBox="0 0 24 24" class="mt-0.5 h-4 w-4 shrink-0 text-rose-500" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                        <div class="min-w-0">
                            <p class="m-0 text-[13px] font-bold text-rose-700">Gagal memuat info pembayaran</p>
                            <p class="m-0 mt-0.5 break-words text-[13px] text-rose-600"><?= htmlspecialchars($payment_error) ?></p>
                        </div>
                    </div>
                <?php } elseif ($payment_info !== null && in_array($payment_info['tipe'], ['qr','link','va','bank','image_src'], true)) { ?>
                    <?php if ($payment_info['tipe'] === 'bank') { ?>
                        <div class="rounded-xl border border-slate-200 bg-white p-3">
                            <p class="mb-1.5 text-center text-[12px] font-semibold uppercase tracking-wide text-slate-400">Nomor Rekening / Tujuan</p>
                            <div class="flex items-center justify-center rounded-lg bg-slate-50 px-3 py-2">
                                <span id="copy_rekening" data-text="Nomor Rekening Berhasil Disalin" data-copy="<?= htmlspecialchars($payment_info['nilai']['nomor'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="leading-none font-mono text-[19px] font-bold tracking-wide text-slate-900"><?= htmlspecialchars($payment_info['nilai']['nomor'] ?? '') ?></span>
                            </div>
                            <button onclick="copyToClipboard('copy_rekening')" type="button" class="mt-1.5 flex w-full items-center justify-center gap-1.5 rounded-lg bg-brand py-1.5 text-[13px] font-bold text-white transition active:scale-[0.99]">
                                <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                Salin Nomor Rekening
                            </button>
                            <?php if (!empty($payment_info['nilai']['nama'])) { ?><p class="mt-1.5 text-center text-[13px] text-slate-500">a/n <span class="font-semibold text-slate-700"><?= htmlspecialchars($payment_info['nilai']['nama']) ?></span></p><?php } ?>
                        </div>
                    <?php } elseif ($payment_info['tipe'] === 'image_src') { ?>
                        <!-- image_src: Tokopay sudah generate gambar QR PNG, FE tinggal <img> -->
                        <div class="rounded-xl bg-slate-50 px-3 py-4 text-center">
                            <p class="mb-2 text-[12px] text-slate-400">QRIS</p>
                            <div class="mx-auto inline-block rounded-lg bg-white p-2">
                                <img src="<?= $payment_info['nilai']?>" alt="QRIS Bukakios" class="h-44 w-44 object-contain" loading="lazy">
                            </div>
                            <?php if (!empty($payment_info['cara_bayar'])) { ?>
                                <div class="mt-3 text-left text-[13px] text-slate-600 leading-relaxed [&>p]:mb-1.5"><?= _renderCaraBayar($payment_info['cara_bayar']) ?></div>
                            <?php } ?>
                        </div>
                    <?php } elseif ($payment_info['tipe'] === 'qr') { ?>
                        <div class="rounded-xl bg-slate-50 px-3 py-4 text-center">
                            <p class="mb-2 text-[12px] text-slate-400">QRIS</p>
                            <div id="qris-render" data-qr="<?= $payment_info['nilai']?>" class="mx-auto inline-block rounded-lg bg-white p-2"></div>
                            <?php if (!empty($payment_info['cara_bayar'])) { ?><div class="mt-3 text-left text-[13px] text-slate-600 leading-relaxed [&>p]:mb-1.5"><?= _renderCaraBayar($payment_info['cara_bayar']) ?></div><?php } ?>
                        </div>
                    <?php } elseif ($payment_info['tipe'] === 'va') { ?>
                        <div class="rounded-xl border border-slate-200 bg-white p-3">
                            <p class="mb-1.5 text-center text-[12px] font-semibold uppercase tracking-wide text-slate-400">Nomor Virtual Account</p>
                            <div class="flex items-center justify-center rounded-lg bg-slate-50 px-3 py-2">
                                <span id="copy_va" data-text="Nomor VA Berhasil Disalin" data-copy="<?= htmlspecialchars($payment_info['nilai'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="leading-none font-mono text-[19px] font-bold tracking-wide text-slate-900"><?= htmlspecialchars($payment_info['nilai'] ?? '') ?></span>
                            </div>
                            <button onclick="copyToClipboard('copy_va')" type="button" class="mt-1.5 flex w-full items-center justify-center gap-1.5 rounded-lg bg-brand py-1.5 text-[13px] font-bold text-white transition active:scale-[0.99]">
                                <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                Salin Nomor VA
                            </button>
                            <?php if (!empty($payment_info['cara_bayar'])) { ?><div class="mt-3 text-left text-[13px] text-slate-600 leading-relaxed [&>p]:mb-1.5"><?= _renderCaraBayar($payment_info['cara_bayar']) ?></div><?php } ?>
                        </div>
                    <?php } elseif ($payment_info['tipe'] === 'link') { ?>
                        <div class="rounded-xl bg-slate-50 px-3 py-3 text-center">
                            <p class="mb-2 text-[12px] text-slate-400">Bayar via Link</p>
                            <a href="<?= $openurl.$payment_info['nilai']?>" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-brand px-4 py-2 text-[14px] font-bold text-white transition active:scale-95">
                                Buka Aplikasi Pembayaran
                                <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7M9 7h8v8"/></svg>
                            </a>
                            <?php if (!empty($payment_info['cara_bayar'])) { ?><div class="mt-3 text-left text-[13px] text-slate-600 leading-relaxed [&>p]:mb-1.5"><?= _renderCaraBayar($payment_info['cara_bayar']) ?></div><?php } ?>
                        </div>
                    <?php } ?>
                <?php } elseif (!empty($nomor_rekening)) { ?>
                    <div class="rounded-xl bg-slate-50 px-3 py-3 text-center">
                        <p class="mb-1 text-[12px] text-slate-400">Nomor Rekening / Tujuan</p>
                        <div class="flex items-center justify-center gap-2"><p id="copy_rekening" data-text="Nomor Rekening Berhasil Disalin" data-copy="<?= $nomor_rekening ?>" class="font-mono text-[18px] font-bold tracking-wide text-slate-900"><?= $nomor_rekening ?></p><button onclick="copyToClipboard('copy_rekening')" type="button" class="rounded-lg bg-white px-2.5 py-1 text-[12px] font-semibold text-slate-700 shadow-sm">Salin</button></div>
                        <?php if (!empty($nama_rekening)) { ?><p class="mt-1 text-[12px] text-slate-500">a/n <?= $nama_rekening ?></p><?php } ?>
                    </div>
                <?php } else { ?>
                    <div class="rounded-xl bg-slate-50 px-3 py-3 text-center text-[13px] text-slate-400">Detail pembayaran mengikuti metode topup yang dipilih.</div>
                <?php } ?>
            </div>

            <?php
            // Payment Info legacy sengaja di-comment dulu. File detail_topup/* tetap disimpan.
            // if ($metode_id == 29) {
            //     require_once("detail_topup/$metode_id.php");
            // }
            ?>
            <?php // if ($status == 0 and $metode_id != 29) { ?>
                <?php // require_once("detail_topup/$metode_id.php"); ?>
            <?php // } ?>

            <?php if ($status == 1) { ?>
                <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3">
                    <svg viewBox="0 0 24 24" class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                    <p class="text-[14px] font-semibold text-emerald-700">Saldo sudah masuk ke akun kamu.</p>
                </div>
            <?php } ?>

            <?php if ($status == 0 && !$is_klaim_komisi) { ?>
                <button type="button" id="btn-cancel-topup" data-id="<?= $topup_id ?>" data-csrf="<?= htmlspecialchars($_SESSION['csrf'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="flex w-full items-center justify-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-[14px] font-bold text-rose-700 transition active:scale-[0.99] disabled:opacity-60">
                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
                    <span class="btn-cancel-label">Batalkan Topup</span>
                </button>
            <?php } ?>

            <div class="flex gap-2">
                <button type="button" onclick="location.reload()" class="flex-1 rounded-xl border border-slate-200 py-2 text-center text-[14px] font-medium text-slate-700 transition hover:bg-slate-50 active:scale-[0.99]">Cek Status</button>
                <a href="<?= $openurl . $wa_komplain_link ?>" class="flex-1 rounded-xl bg-brand py-2 text-center text-[14px] font-medium text-white transition hover:bg-brandDark">Bantuan</a>
            </div>
        </div>
    </main>

    <div class="modal" id="modal_notif_bank" tabindex="-1" role="dialog" style="
        background-image: url(<?php echo $url_bg ?>);
        background-repeat: no-repeat;
        background-size: contain;
        width: 80%;
        margin-top: 100px;
        margin-left:40px
      ">
        <div class="modal-dialog" role="document" style="width: 100%"></div>
    </div>

    <script src="../assets/js/jquery.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

    <script src="../assets/js/sweetalert.min.js"></script>
    <!-- <script src="https://member.bukakios.net/js/lib/notie/notie.js"></script> -->
    <!-- <script src="https://member.bukakios.net/js/me/copy.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.3.3/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/es6-tween/5.5.11/Tween.min.js"></script>
    <!-- QRCode renderer (dipakai kalau payment.tipe === 'qr') -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
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
            ['backBtn', 'backBtnPage'].forEach(function(id) {
                var btn = document.getElementById(id);
                if (btn) btn.addEventListener('click', goBack);
            });
        })();
    </script>
    <script>
        // Create Countdown
        var tgl = "<?= $expired_at ?>";
        var tgl_las = "<?= date("Y-m-d H:i:s") ?>";

        // Countdown flat ala teraflazz: render HH:MM:SS sebagai teks biasa,
        // bukan flip animation. Update tiap detik via setInterval.
        var Countdown = {
            $el: null,
            target: 0,
            interval: null,

            init: function() {
                var target = new Date(tgl).getTime();
                if (isNaN(target)) {
                    if (this.$el) this.$el.text('--:--:--');
                    return;
                }
                this.$el = document.getElementById('countdown');
                if (!this.$el) return;
                this.target = target;

                var tick = function() {
                    var diff = Math.max(0, target - Date.now());
                    if (diff === 0) {
                        Countdown.$el.textContent = '00:00';
                        if (Countdown.$el) Countdown.$el.classList.add('text-rose-600');
                        clearInterval(Countdown.interval);
                        var expire = document.getElementById('expire');
                        if (expire) expire.style.display = '';
                        return;
                    }
                    var total = Math.floor(diff / 1000);
                    var h = String(Math.floor(total / 3600)).padStart(2, '0');
                    var m = String(Math.floor((total % 3600) / 60)).padStart(2, '0');
                    var s = String(total % 60).padStart(2, '0');
                    Countdown.$el.textContent = h + ':' + m + ':' + s;
                };

                tick();
                this.interval = setInterval(tick, 1000);
            },
        };

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

        function copyText(input) {
        // Cek apakah input adalah string atau elemen HTML
        let textToCopy;

        if (typeof input === "string") {
          // Jika input adalah string langsung
          textToCopy = input;
        } else if (input instanceof HTMLElement) {
          // Jika input adalah elemen HTML, ambil teksnya
          textToCopy = input.innerText || input.textContent;
        }

            // Jika teks ditemukan, salin ke clipboard
            if (textToCopy) {
            navigator.clipboard
                .writeText(textToCopy)
                .then(function () {
                //   document.getElementById("message").innerText =
                //     "Teks '" + textToCopy + "' berhasil disalin!";
                })
                .catch(function (error) {
                //   document.getElementById("message").innerText =
                //     "Gagal menyalin teks!";
                //   console.error("Gagal menyalin teks:", error);
                });
            } else {
            //   document.getElementById("message").innerText =
            //     "Tidak ada teks untuk disalin!";
            }
            showToast("Berhasil menyalin", 'success');
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
            console.log(popupText);
            window.clearTimeout(copyToasterTimeout);
            showToast(popupText, 'success');
        }

        // Handler tombol Batalkan Topup: pakai fetch ke endpoint ?act=cancel,
        // lalu tampilkan toast success/error. Kalau sukses, reload halaman
        // supaya status badge dan tombol ter-update.
                // Saat proses cancel, SEMUA tombol interaktif di-disable supaya user
                // ga bisa double-click / interaksi yang tidak perlu.
                (function() {
                    var btn = document.getElementById('btn-cancel-topup');
                    if (!btn) return;

                    // Global flag: kalau true, semua onclick lain di halaman dibatalkan.
                    window.__bkCanceling = false;

                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        if (window.__bkCanceling) return;

                        var id = btn.getAttribute('data-id');
                        if (!id) { showToast('ID topup tidak ditemukan', 'error'); return; }

                        // Tandai global lock + disable SEMUA tombol interaktif + tambah overlay spinner.
                        window.__bkCanceling = true;
                        var interactive = document.querySelectorAll('button, a[href], [onclick]');
                        interactive.forEach(function(el) {
                            if (el === btn) return;
                            el.setAttribute('aria-disabled', 'true');
                            el.classList.add('pointer-events-none', 'opacity-60');
                        });
                        btn.disabled = true;
                        var labelEl = btn.querySelector('.btn-cancel-label');
                        if (labelEl) labelEl.textContent = 'Membatalkan...';

                        // Pasang spinner kecil di tengah tombol
                        var spinner = document.createElement('span');
                        spinner.className = 'inline-block h-3.5 w-3.5 animate-spin rounded-full border-2 border-rose-300 border-t-rose-700';
                        btn.insertBefore(spinner, labelEl);

                        var url = window.location.pathname + '?id=' + encodeURIComponent(id) + '&act=cancel';
                        fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: 'id=' + encodeURIComponent(id)
                        }).then(function(r) { return r.json(); })
                        .then(function(data) {
                            if (data && data.status === 1) {
                                showToast(data.msg || 'Topup berhasil dibatalkan', 'success');
                                // Reload setelah toast sebentar, supaya status + tombol update
                                setTimeout(function() { window.location.reload(); }, 900);
                            } else {
                                showToast((data && data.msg) || 'Gagal membatalkan topup', 'error');
                                // Restore state kalau gagal
                                window.__bkCanceling = false;
                                interactive.forEach(function(el) {
                                    if (el === btn) return;
                                    el.removeAttribute('aria-disabled');
                                    el.classList.remove('pointer-events-none', 'opacity-60');
                                });
                                btn.disabled = false;
                                if (spinner.parentNode) spinner.parentNode.removeChild(spinner);
                                if (labelEl) labelEl.textContent = 'Batalkan Topup';
                            }
                        }).catch(function() {
                            showToast('Tidak dapat terhubung ke server', 'error');
                            window.__bkCanceling = false;
                            interactive.forEach(function(el) {
                                if (el === btn) return;
                                el.removeAttribute('aria-disabled');
                                el.classList.remove('pointer-events-none', 'opacity-60');
                            });
                            btn.disabled = false;
                            if (spinner.parentNode) spinner.parentNode.removeChild(spinner);
                            if (labelEl) labelEl.textContent = 'Batalkan Topup';
                        });
                    });
                })();

        // Let's go !
        <?php
        if ($status == 0 && !$is_klaim_komisi) { ?>
            console.log(<?= $status ?>)
            Countdown.init();
        <?php
        }
        ?>
    </script>
    <script>
        var alert_warning = $(".alert-warning")
        var alert_success = $(".alert-success")
        var btn_submit_ewallet = $(".btn_submit_ewallet")
        var wallet_number = $("#wallet_number")

        function show_alert_success() {
            alert_success.show()
            alert_warning.hide()
        }

        function show_alert_warning() {
            alert_success.hide()
            alert_warning.show()
        }

        function hide_alert() {
            alert_success.hide()
            alert_warning.hide()
        }

        $(document).ready(function() {

            <?php
            if ($topup_metode == 4) {
            ?>
                $("#modal_notif_bank").modal("show");
            <?php
            }
            ?>

            $("#show").click(function() {
                $("#show").hide();
                $("#hide").show();
                $("#inffo").fadeIn();
                document.getElementById("head").style.borderBottomLeftRadius = "0px";
                document.getElementById("head").style.borderBottomRightRadius = "0px";
                document.getElementById("inffo").style.borderTopLeftRadius = "0px";
                document.getElementById("inffo").style.borderTopRightRadius = "0px";
            });

            $("#hide").click(function() {
                $("#hide").hide();
                $("#show").show();
                $("#inffo").fadeOut();
                document.getElementById("head").style.borderBottomLeftRadius = "0.25rem";
                document.getElementById("head").style.borderBottomRightRadius = "0.25rem";
            });

            btn_submit_ewallet.click(function() {
                hide_alert()
                var number = wallet_number.val()
                if (number == "") {
                    show_alert_warning()
                    alert_warning.html("Mohon mengisi nomor OVO kamu")
                    return
                }
                btn_submit_ewallet.html("Menunggu pembayaran ...")
                btn_submit_ewallet.attr("disabled", true)
                // alert("oke")
                var topup_id = "<?php echo $topup_id ?>";
                var dataString = "ewallet_number=" + number + "&topup_id=" + topup_id

                show_alert_success()
                alert_success.html("Silahkan cek notifikasi OVO kamu.")
                $.ajax({
                    url: "index.php",
                    method: "POST",
                    data: dataString,
                    dataType: "JSON",
                    success: function(data) {
                        // alert(dataString)
                        if (data.status == 1) {
                            show_alert_success()
                            alert_success.html(data.msg)
                        } else {
                            show_alert_warning()
                            alert_warning.html(data.error_msg)
                        }
                        btn_submit_ewallet.html("Request pembayaran")
                        btn_submit_ewallet.attr("disabled", false)
                    },
                    error: function(request, status, error) {
                        //alert(request.responseText);
                    }
                });
            });
        });

        // Render QR Code (kalau payment.tipe === 'qr'). Pakai qrcode CDN
        // yang sudah di-load di <head>. Element target: #qris-render dengan
        // data-qr="<qr_string>". Render ke <canvas> 192px.
        (function() {
            var el = document.getElementById('qris-render');
            if (!el) return;
            var payload = el.getAttribute('data-qr');
            if (!payload || typeof QRCode === 'undefined') return;
            try {
                QRCode.toCanvas(el, payload, { width: 192, margin: 1, color: { dark: '#0f172a', light: '#ffffff' } }, function (err) {
                    if (err) {
                        el.innerHTML = '<p class="text-[12px] text-rose-500">Gagal render QRIS</p>';
                    }
                });
            } catch (e) {
                el.innerHTML = '<p class="text-[12px] text-rose-500">Gagal render QRIS</p>';
            }
        })();

    </script>
</body>

</html>
