<?php
exit;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once("../config.php");
// require_once("../_session.php");
$openurl = "open://";
$open_url = "open://";
// $topup_id = 942831;
// $topup_id = 100;
// $topup_id = 128976;
// $user_id = 116291;
// $user_id = 1000;
$user_id = 39958;
if (isset($_GET['prov'])) {
    $provider = htmlentities($db->escape_string($_GET['prov']));
}

if (isset($_GET['id'])) {
    //unset($_SESSION['cek_sess']);
    // if (isset($user_id)) {
    $topup_id = abs((int)$_GET['id']);
    // $topup_id = 1017;
    // $topup_id = 1000;
    // $topup_id = 128976;
    $detail_topup = $db->fetch("select 
	t.nomor_rekening,t.uid,t.topup_metode,t.topup_metode_kategori,t.nominal_topup,t.kode_unik,t.fee,t.total_transfer,t.nomor_rekening as rekening_pg,t.created_at,t.expired_at,t.status,m.nama_kategori,m.nama_metode,m.gambar_metode,m.nomor_rekening as real_rek ,m.nama_rekening,m.id
	from topup t inner join topup_metode m 
	on t.topup_metode=m.id
	where t.uid='$user_id' and t.id='$topup_id'
	");
    if (!isset($detail_topup['uid'])) {
        echo "Data Topup tidak di temukan";
        exit;
    }
    // if ($user_id == 39958){
    //     echo json_encode($detail_topup);exit;
    // }
    $total_transfer_rp = $app->idr($detail_topup['total_transfer']);
    $topup_metode_kategori = $detail_topup['topup_metode_kategori'];
    $nama_kategori = $detail_topup['nama_kategori'];
    $metode_id = $detail_topup['id'];
    $uid = $detail_topup['uid'];
    $nama_metode = $detail_topup['nama_metode'];

    // $nama_m = explode(" ", $nama_metode);
    // $nama_m = $nama_m[2];

    $topup_metode = $detail_topup['topup_metode'];
    $nominal_topup = $detail_topup['nominal_topup'];
    $kode_unik = $detail_topup['kode_unik'];
    $fee = $detail_topup['fee'];
    $total_transfer = $detail_topup['total_transfer'];
    $nomor_rekening = $detail_topup['nomor_rekening'];
    $nom_rek = $detail_topup['real_rek'];
    $created_at = $detail_topup['created_at'];
    $expired_at = $detail_topup['expired_at'];
    $status = $detail_topup['status'];
    $gambar_metode = $detail_topup['gambar_metode'];
    $nomor_rekening = $detail_topup['nomor_rekening'];

    $nama_rekening = $detail_topup['nama_rekening'];
    $terima_bersih = $total_transfer + $fee;
    if ($topup_metode_kategori != 1) {
        $terima_bersih = $total_transfer - $fee;
    }
    
    //save nomor rekening
    $_SESSION["$topup_id-no_rekening"] = $nomor_rekening;

    $hash_topup = md5("$topup_id:$uid:$topup_metode:$created_at");
    if ($status == 0) {
        $st_image = "https://assets.bukakios.net/img2/uploads/2019/12/827-sand-clock.png";
        $statusnya = " <span class='badge badge-warning mx-auto text-center mt-1' style='color:white;padding:5px 10px 5px 10px'>Menunggu Pembayaran</span>";
        $st_label = "Topup Pending";
    } else if ($status == 1) {
        $st_image = "https://assets.bukakios.net/img2/uploads/2019/12/474-checked.png";
        $statusnya = " <span class='badge badge-success mx-auto text-center mt-1' style='color:white;padding:5px 10px 5px 10px'>Topup Berhasil</span>";
        $st_label = "Topup Berhasil";
    } else {
        $st_image = "https://assets.bukakios.net/img2/uploads/2019/12/822-button.png";
        $statusnya = " <span class='badge badge-danger mx-auto text-center mt-1' style='color:white;padding:5px 10px 5px 10px'>Topup Dibatalkan</span>";
        $st_label = "Topup Di Batalkan";
    }
    if (isset($_GET['act'])) {
        //batalkan topup
        $act = $_REQUEST['act'];
        if ($act == 'cancel') {
            if (isset($_GET['id'])) {
                //do
                $id = abs((int)$_GET['id']);
                $db->query("update topup set status=2 where uid='$user_id' and id='$id'");
                $_SESSION['success_msg'] = "Berhasil Membatalkan topup :)";
                $app->grab_data("$api_url/delete_cache/riwayat_topup.php?id=$user_id",10);
                header("location:$c_url/info-topup/?id=$topup_id&s=1");
                exit;
            }
        }
    }
} else {
    exit;
}

if (isset($_GET['act'])) {
    $act = htmlentities($db->escape_string($_GET['act']));
    if ($act == "cek") {
        $nomor = $_GET['nom'];
        $id = abs((int) $_GET['id']);
        $nominal = abs((int) $_GET['nominal']);
        $nomor_rekening = $_GET['nomor_rekening'];
       
        // echo $nomor;

	    $key_cari = "cari-$id";
        $headers = array(
            "Api-Key: dd7d17b3ee283d3c698bcad38e5fe5f7"
        );
        $json = array(
            "pengirim" => $nomor,
            "penerima" => $nomor_rekening,
            "nominal" => $total_transfer, 
            "id_topup" => $id
        );

        $rd_nomor_pengirim = "nomor-$id";
        // echo $rd_nomor_pengirim." pengirim";
        $rediw->setex($rd_nomor_pengirim,3600*2, $nomor);

        $res = $app->curl_with_json($json, 'https://sms.bukakios.net/v1/cek', $headers);
        $ress = json_decode($res, true);
        if (isset($ress['status'])) {
            if ($ress['status'] == 1) {
		    //$rediw->setex($key_cari,3600*2,1);
            //$rediw->setex("nomor-$id",3600*2,1, $nomor);
                $return = json_encode(
                    array(
                        "status" => 1,
                        "msg" => "Selamat, nomor kamu berhasil di verifikasi dan topup sudah berhasil di masukkan ke saldo. Silahkan cek saldo kamu. Terima kasih"
                    )
                    );
                    $_SESSION['msg'] = "Selamat, nomor kamu berhasil di verifikasi dan topup sudah berhasil di masukkan ke saldo. Silahkan cek saldo kamu. Terima kasih";
            }else {
		   // $rediw->setex($key_cari,3600*2,0);
            //$rediw->setex("nomor-$id",3600*2,1, $nomor);
                $return = json_encode(
                    array(
                        "status" => 0,
                        "error_msg" => $ress['error_msg']
                    )
                );
            }
        }else {
            $return = json_encode(
                array(
                    "status" => 0,
                    "error_msg" => "Terjadi error pada server"
                )
                );
        }
    }
    echo $return;
    exit;
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Nunito" />
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <title>title:detail topup pending</title>
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

        .title {
            background-color: #f8f8fe;
            padding: 10px;
            color: #66728a;
            font-weight: bold;
            font-size: 20px;
            border-radius: 10px;
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

<body>
    <div class="py-3" style="background-color:<?= $primary ?>;height:200px">
        <div class="text-center mb-2">
            <!-- <img class="mb-1" src=<?= $st_image ?>><br/> -->
            <img class="mb-1" width="120px" src=<?= $st_image ?>><br />
        </div>
    </div>
    <div style="margin-top:-50px;margin-right:2px;margin-left:2px">
        <div class="cir"></div>
        <div class="circle"></div>
        <div class="container ">
            <div class="card py-3 px-4 " style="border-radius:10px">
                <span class="font-weight-bold">ID Topup</span>

                <div id="copy_idd" data-text="ID Topup Berhasil Disalin" data-copy="<?= $topup_id ?>"></div>

                <!-- <span id="copy_id" data-text="User Id Berhasil Disalin" data-copy="<?= $topup_id ?>" class="font-weight-bold" style="text-decoration:underline;color:#00bfff;cursor:pointer" onclick="copyToClipboard('copy_idd')">#<?= $topup_id ?>  -->
                <span id="copy_id" class="font-weight-bold">#<?= $topup_id ?>
                    <!-- <img src="https://image.flaticon.com/icons/svg/926/926768.svg" style="position:absolute;top:40px;height:10px"> -->
                    <!-- <a href='' class="ml-3"><img src='https://assets.bukakios.net/img2/uploads/2019/12/569-refresh.png' style='height:20px;width:20px;margin-left:2px;margin-top:-2px'/></a> -->
                    <!-- <a href=''><img src='https://assets.bukakios.net/img2/uploads/2019/12/569-refresh.png' style='height:20px;width:20px;margin-left:2px;margin-top:-2px'/></a> -->
                    <a class="btn btn-sm btn-outline-primary" style="color:<?= $primary ?>;padding:2px;font-size:10px;margin-bottom:5px" onclick="copyToClipboard('copy_idd')">Copy</a>
                </span>
                <!-- <span style="text-decoration:underline;color:#00bfff;cursor:pointer" onclick="copyToClipboard('copy_id')">Copy Id</span> -->

                <span class="font-weight-bold">Hingga Tanggal</span>
                <span class="font-weight-light"><?= $expired_at ?></span>
                <hr>
                <div class="countdown">
                    <div class="bloc-time hours" data-init-value="24">
                        <span class="count-title font-weight-bold">Jam</span>

                        <div class="figure hours hours-1">
                            <span class="top">0</span>
                            <span class="top-back">
                                <span>0</span>
                            </span>
                            <span class="bottom">0</span>
                            <span class="bottom-back">
                                <span>0</span>
                            </span>
                        </div>

                        <div class="figure hours hours-2">
                            <span class="top">0</span>
                            <span class="top-back">
                                <span>0</span>
                            </span>
                            <span class="bottom">0</span>
                            <span class="bottom-back">
                                <span>0</span>
                            </span>
                        </div>
                    </div>

                    <div class="bloc-time min" data-init-value="0">
                        <span class="count-title font-weight-bold">Menit</span>

                        <div class="figure min min-1">
                            <span class="top">0</span>
                            <span class="top-back">
                                <span>0</span>
                            </span>
                            <span class="bottom">0</span>
                            <span class="bottom-back">
                                <span>0</span>
                            </span>
                        </div>

                        <div class="figure min min-2">
                            <span class="top">0</span>
                            <span class="top-back">
                                <span>0</span>
                            </span>
                            <span class="bottom">0</span>
                            <span class="bottom-back">
                                <span>0</span>
                            </span>
                        </div>
                    </div>

                    <div class="bloc-time sec" data-init-value="0">
                        <span class="count-title font-weight-bold">Detik</span>

                        <div class="figure sec sec-1">
                            <span class="top">0</span>
                            <span class="top-back">
                                <span>0</span>
                            </span>
                            <span class="bottom">0</span>
                            <span class="bottom-back">
                                <span>0</span>
                            </span>
                        </div>

                        <div class="figure sec sec-2">
                            <span class="top">0</span>
                            <span class="top-back">
                                <span>0</span>
                            </span>
                            <span class="bottom">0</span>
                            <span class="bottom-back">
                                <span>0</span>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- <span class="badge badge-danger mx-auto text-center mt-1" style="display:none" id="expire">Topup EXPIRED</span> -->
                <div class="mt-2 text-center">
                    <?= $statusnya ?> <a href=''><img src='https://assets.bukakios.net/img2/uploads/2019/12/569-refresh.png' style='height:20px;width:20px;margin-left:2px;margin-top:-2px' /></a>
                </div>

                <hr>
                <div class="text-center mx-5" style="padding:0px !important">
                    <?php
                    if ($metode_id >= 42 and $metode_id <= 48) {
                    ?>
                        <!-- <table width="150%" style="text-align: left; padding:0px; margin-left:-45px">
                            <tr>
                                <td><span class="font-weight-bold text-left">Nominal Saldo Masuk</span></td>
                                <td><span class="font-weight-light"><?= $app->idr($nominal_topup); ?></span></td>
                            </tr>
                            <tr>
                                <td><span class="font-weight-bold">Kode Unik</span></td>
                                <td><span class="font-weight-light"><?= $app->idr($kode_unik); ?></span></td>
                            </tr>
                            <tr>
                                <td><span class="font-weight-bold">Potongan Rate</span></td>
                                <td><span class="font-weight-light"><?= $app->idr($fee); ?></span></td>
                            </tr>
                            <tr>
                                <td><span class="font-weight-bold">Total</span></td>
                                <td><span class="font-weight-light"><?= $app->idr($total_transfer) ?></span></td>
                            </tr>
                        </table> -->
                        <table width="150%" style="text-align: left; padding:0px; margin-left:-45px">
                            <tr>
                                <td><span class="font-weight-bold text-left">Provider</span></td>
                                <td><span class="font-weight-light"><?php echo $nama_m; ?></span></td>
                            </tr>
                            <tr>
                                <td><span class="font-weight-bold text-left">Nominal Saldo Masuk</span></td>
                                <td><span class="font-weight-light"><?php echo $app->idr($nominal_topup); ?></span></td>
                            </tr>
                            <tr>
                                <td><span class="font-weight-bold">Total</span></td>
                                <td><span class="font-weight-light"><?php echo $app->idr($total_transfer) ?></span></td>
                            </tr>
                        </table>
                    <?php
                    } else {
                    ?>
                        <table width="100%" style="text-align: left">
                            <?php
                            if ($metode_id == 40) {
                                $disc = $nominal_topup * 0.01;
                            ?>
                                <tr>
                                    <td><span class="font-weight-bold text-left">Total Tagihan</span></td>
                                    <td><span class="font-weight-light"><?= $app->idr($nominal_topup); ?></span></td>
                                </tr>
                                <tr>
                                    <td><span class="font-weight-bold">Diskon</span></td>
                                    <td><span class="font-weight-light">0</span></td>
                                </tr>
                                <tr>
                                    <td><span class="font-weight-bold">Biaya admin</span></td>
                                    <td><span class="font-weight-light"><?= $app->idr($fee); ?></span></td>
                                </tr>
                                <tr>
                                    <td><span class="font-weight-bold">Total</span></td>
                                    <!-- <td><span class="font-weight-light"><?php // $app->idr($nominal_topup - $disc) 
                                                                                ?></span></td> -->
                                    <td><span class="font-weight-light"><?= $app->idr($nominal_topup + $fee) ?></span></td>
                                </tr>
                            <?php
                            } else { ?>
                                <tr>
                                    <td><span class="font-weight-bold text-left">Total Tagihan</span></td>
                                    <td><span class="font-weight-light"><?= $app->idr($nominal_topup); ?></span></td>
                                </tr>
                                <tr>
                                    <td><span class="font-weight-bold">Kode Unik</span></td>
                                    <td><span class="font-weight-light"><?= $app->idr($kode_unik); ?></span></td>
                                </tr>
                                <tr>
                                    <td><span class="font-weight-bold">Biaya admin</span></td>
                                    <td><span class="font-weight-light"><?= $app->idr($fee); ?></span></td>
                                </tr>
                                <tr>
                                    <td><span class="font-weight-bold">Total</span></td>
                                    <td><span class="font-weight-light"><?= $app->idr($total_transfer) ?></span></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </table>
                    <?php
                    }
                    ?>
                    <?php
                    if ($metode_id == 40) {
                        $disc = $nominal_topup * 0.01;
                    ?>
                        <div class="mt-2">
                            <div class="box-money">
                                <h3 id='nominal_transfer' data-text="Jumlah Transfer Berhasil Di Salin" data-copy="<?= $app->idr($nominal_topup + $fee); ?>"><?= $app->idr($nominal_topup + $fee); ?></h3>
                            </div>
                        </div>
                    <?php
                    } else { ?>
                        <div class="mt-2">
                            <div class="box-money">
                                <h3 id='nominal_transfer' data-text="Jumlah Transfer Berhasil Di Salin" data-copy="<?= $total_transfer; ?>"><?= $total_transfer_rp; ?></h3>
                            </div>
                            <br />
                            <span style="text-decoration:underline;color:#00bfff;cursor:pointer" onclick="copyToClipboard('nominal_transfer')">Salin Jumlah</span>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <hr />

            
                <?PHP if ($status == 0) { ?>
                    <?PHP require_once("detail_topup/$metode_id.php"); ?>
                <?php } else { ?>
            </div>
        <?php
                } ?>

        <?php if ($status == 0) { 
            
            ?>
            <div class="mt-3 py-3 px-4 text-center" style="border: 3px dashed #9C9C9C">
                <a class="btn btn-danger btn-md btn-block" href="<?PHP echo "$c_url/info-topup/?id=$topup_id&act=cancel"; ?>">Batal Topup</a>
            </div>
        <?php } else { ?>
            <div class="mt-3 py-3 px-4 text-center" style="border: 3px dashed #9C9C9C">
                <a style='margin-left:10px;margin-right:10px;background-color:<?= $primary; ?>' href='intercom://open.it' class='btn btn-primary btn-block'>Kontak CS</a>
            </div>
        <?php } ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/sweetalert.min.js"></script>
    <script src="https://member.bukakios.net/js/lib/notie/notie.js"></script>
    <script src="https://member.bukakios.net/js/me/copy.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.3.3/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/es6-tween/5.5.11/Tween.min.js"></script>
    <script>
        // Create Countdown
        var tgl = "<?= $expired_at ?>"
        var tgl_las = "<?= date("Y-m-d H:i:s") ?>"
        console.log(tgl)
        var Countdown = {
            // Backbone-like structure
            $el: $('.countdown'),

            // Params
            countdown_interval: null,
            total_seconds: 0,
            // Initialize the countdown
            init: function() {

                var countDownDate = new Date(tgl).getTime();
                // DOM
                console.log(tgl_las)
                var now = new Date(tgl_las).getTime();
                console.log(now)
                var distance = countDownDate - now;
                console.log(distance)
                var hours1 = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes1 = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds1 = Math.floor((distance % (1000 * 60)) / 1000);
                this.$ = {
                    hours: this.$el.find('.bloc-time.hours .figure'),
                    minutes: this.$el.find('.bloc-time.min .figure'),
                    seconds: this.$el.find('.bloc-time.sec .figure'),
                };
                //   console.log(this.$)
                // Init countdown values
                this.values = {
                    hours: hours1,
                    minutes: minutes1,
                    seconds: seconds1,
                };

                // Initialize total seconds
                this.total_seconds =
                    this.values.hours * 60 * 60 +
                    this.values.minutes * 60 +
                    this.values.seconds;

                //   console.log(this.total_seconds)
                // Animate countdown to the end
                this.count();
            },

            count: function() {
                var that = this,
                    $hour_1 = this.$.hours.eq(0),
                    $hour_2 = this.$.hours.eq(1),
                    $min_1 = this.$.minutes.eq(0),
                    $min_2 = this.$.minutes.eq(1),
                    $sec_1 = this.$.seconds.eq(0),
                    $sec_2 = this.$.seconds.eq(1);

                this.countdown_interval = setInterval(function() {
                    if (that.total_seconds > 0) {
                        --that.values.seconds;

                        if (that.values.minutes >= 0 && that.values.seconds < 0) {
                            that.values.seconds = 59;
                            --that.values.minutes;
                        }

                        if (that.values.hours >= 0 && that.values.minutes < 0) {
                            that.values.minutes = 59;
                            --that.values.hours;
                        }

                        // Update DOM values
                        // Hours
                        that.checkHour(that.values.hours, $hour_1, $hour_2);

                        // Minutes
                        that.checkHour(that.values.minutes, $min_1, $min_2);

                        // Seconds
                        that.checkHour(that.values.seconds, $sec_1, $sec_2);

                        --that.total_seconds;
                    } else {
                        clearInterval(that.countdown_interval);
                        $('#expire').show();
                    }
                }, 1000);
            },

            animateFigure: function($el, value) {
                var that = this,
                    $top = $el.find('.top'),
                    $bottom = $el.find('.bottom'),
                    $back_top = $el.find('.top-back'),
                    $back_bottom = $el.find('.bottom-back');

                // Before we begin, change the back value
                $back_top.find('span').html(value);

                // Also change the back bottom value
                $back_bottom.find('span').html(value);

                // Then animate
                TweenMax.to($top, 0.8, {
                    rotationX: '-180deg',
                    transformPerspective: 300,
                    ease: Quart.easeOut,
                    onComplete: function() {
                        $top.html(value);

                        $bottom.html(value);

                        TweenMax.set($top, {
                            rotationX: 0
                        });
                    },
                });

                TweenMax.to($back_top, 0.8, {
                    rotationX: 0,
                    transformPerspective: 300,
                    ease: Quart.easeOut,
                    clearProps: 'all',
                });
            },

            checkHour: function(value, $el_1, $el_2) {
                var val_1 = value.toString().charAt(0),
                    val_2 = value.toString().charAt(1),
                    fig_1_value = $el_1.find('.top').html(),
                    fig_2_value = $el_2.find('.top').html();

                if (value >= 10) {
                    // Animate only if the figure has changed
                    if (fig_1_value !== val_1) this.animateFigure($el_1, val_1);
                    if (fig_2_value !== val_2) this.animateFigure($el_2, val_2);
                } else {
                    // If we are under 10, replace first figure with 0
                    if (fig_1_value !== '0') this.animateFigure($el_1, 0);
                    if (fig_2_value !== val_1) this.animateFigure($el_2, val_1);
                }
            },
        };

        // Let's go !
        <?php
        if ($status == 0) { ?>
            console.log(<?= $status ?>)
            Countdown.init();
        <?php
        }
        ?>
    </script>
    <script>
        $(document).ready(function() {
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
        });

        
    </script>
    <script>
        $(document).on('click', '#cari', function() {
            // $("#konfirm").hide();
            $('#cari').attr("disabled", true);
            $('#cari').html("Proses.. please wait");
            var hp = $('#hp').val();
            if (hp.length < 9) {
                $('#cari').attr("disabled", false);
                $('#cari').html("Konfirmasi");
                Swal.fire({
                    title: "<b>Gagal</b>",
                    html: 'Minimal Input Ialah 9 Karakter',
                    icon: 'error',
                    showConfirmButton:true
                })
            }else{
                // console.log(hp)
                $.ajax({
                    url: 'index2_tes.php?act=cek&nom='+ hp+'&id=<?= $topup_id ?>&nominal=<?= $total_transfer ?>&nomor_rekening=<?php echo $nom_rek ?>',
                    success: function(output) {
                        // $('#form').hide();
                            console.log(output)
                        var myJsn = JSON.parse(output);
                        
                        if (myJsn.status == 1) {
                            Swal.fire({
                                title: "<b>Berhasil</b>",
                                // html:
                                //     'Berhasil Mendapatkan Nomor<div class="my-2"><a href="https://wv.bukakios.net/info-topup/index.php?id=<?php //echo  $topup_id ?>&prov=<?php //echo $provider ?>" class="btn btn-md btn-success">' + 'Oke' + '</a>' +
                                //     '</div>',
                                html: myJsn.msg,
                                icon: 'success',
                                showConfirmButton:true
                            });

                            setTimeout(function(){  window.location.href= "https://wv.bukakios.net/convert-pulsa/success.php" }, 2000);
                        }else{
                            Swal.fire({
                                title: "<b>Gagal</b>",
                                // html:
                                //         'Tidak Ditemukan Nomor <b>'+hp+'</b> Berikut<div class="my-2"><a href="https://wv.bukakios.net/convert-pulsa/upload.php?id=<?php //echo $topup_id ?>" class="btn btn-md btn-success">' + 'Oke' + '</a>' +
                                //         '</div>',
                                html: myJsn.error_msg+'<a style="margin-top:10px" href="https://wv.bukakios.net/convert-pulsa/upload.php?id=<?php echo $topup_id ?>&hp='+hp+'" class="btn btn-md btn-success btn-block">Upload bukti pembayaran</a>',
                                icon: 'error',
                                showConfirmButton:false
                            })
                        }
                        $('#cari').attr("disabled", false);
                        $('#cari').html("Konfirmasi");
                    }
                })
            }
        });
    </script></body>

</html>