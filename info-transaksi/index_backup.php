<?php
require_once("../config.php");
require_once("../_session.php");
$openurl = "open://";
$open_url = "open://";
// $user_id = "14353";
// $id = "11283";
//$user_id = "46873";
//$id = "589869";


if (isset($_REQUEST['msg'])) {
    require_once '_act.php';
}

function datee($date)
{
    $bulan = array(
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
        "12" => "Desember"
    );

    $date = explode(" ", $date);
    $tgl = $date[0];
    $time = $date[1];

    $tgl = explode("-", $tgl);
    $tgl = $tgl[2] . " " . $bulan[$tgl[1]] . " " . $tgl[0] . " / " . $time;

    return $tgl;
}

if (isset($_GET['id'])) {
    $id = abs((int)$_GET['id']);
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
    $data_post = array(
        "key" => $api_key,
        "uid" => $user_id,
        "order_id" => $id
    );


    $data_respon = $app->curl_post("$api_url/detail_transaksi_full.php", $data_post);
    $transaksi = json_decode($data_respon, true);
    if ($transaksi['status'] != 1) {
        exit;
    }
    $detail_transaksi = $transaksi['data'];
    $trx_id = $detail_transaksi['trx_id'];
    $product_name = $detail_transaksi['product_name'];
    $product_code = $detail_transaksi['product_code'];
    $nomor_tujuan = $detail_transaksi['nomor_tujuan'];
    $id_pel = $detail_transaksi['id_pel'];
    $price_client = $detail_transaksi['price_client'];
    $selling_price_client = $detail_transaksi['selling_price_client'];
    $sn = base64_decode($detail_transaksi['sn']);
    $note = base64_decode($detail_transaksi['note']);
    $saldo_before_trx = $detail_transaksi['saldo_before_trx'];
    $saldo_after_trx = $detail_transaksi['saldo_after_trx'];
    $created_at = $detail_transaksi['created_at'];
    $updated_at = $detail_transaksi['updated_at'];
    $status = $detail_transaksi['status'];
    $op_name = $detail_transaksi['op_name'];
    $u_nama = $detail_transaksi['nama'];
    $u_nama_toko = $detail_transaksi['nama_toko'];
    $signature = $detail_transaksi['signature'];
    $pembelianoperator_id = $detail_transaksi['pembelianoperator_id'];
    $pembelian_kategori_id = $detail_transaksi['pembeliankategori_id'];
    $product_logo = $detail_transaksi['product_logo'];

    if (isset($transaksi['h2h_id'])) {
        $h2h_id = $transaksi['h2h_id'];
        if ($h2h_id == 25) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://svd.bukakios.net/voucher/detail/$sn");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $headers = [
                'Api-Key: A599084647F381FFFD16C6E5948341E65FC0D7AB',
            ];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $call_api_svd = curl_exec($ch);
            curl_close($ch);

            $call_api_svd_res = json_decode($call_api_svd, true);
            if (isset($call_api_svd_res['status'])) {
                if ($call_api_svd_res['status'] == 0) {
                    $svd_error = true;
                    $svd_error_msg = $call_api_svd_res['error_msg'];
                } else {
                    $svd_data = $call_api_svd_res['data'];
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
    $msg = array(
        'deskripsi' => $product_name,
        'harga' => $harga_hutang
    );
    $status_ppob = "refund";
    $msg = base64_encode(json_encode($msg));
    if ($status == 0) {
        $st_image = "https://assets.bukakios.net/img2/uploads/2019/12/827-sand-clock.png";
        $st_label = "Transaksi On Process";
        $statusnya = " <span class='badge badge-warning' style='color:white;padding:5px 10px 5px 10px'>Sedang Di Proses</span>";
        $status_ppob = 'Process';
    } else if ($status == 1) {
        $st_image = "https://assets.bukakios.net/img2/uploads/2019/12/474-checked.png";
        $st_label = "Transaksi Berhasil";
        $statusnya = " <span class='badge badge-success' style='color:white;padding:5px 10px 5px 10px'>Berhasil</span>";
        $status_ppob = "Berhasil";
    } else if ($status == 2) {
        $st_image = "https://assets.bukakios.net/img2/uploads/2019/12/822-button.png";
        $st_label = "Transaksi Gagal";
        $statusnya = " <span class='badge badge-danger' style='color:white;padding:5px 10px 5px 10px'>Refund</span>";
    } else if ($status == 3) {
        $st_image = "https://assets.bukakios.net/img2/uploads/2019/12/822-button.png";
        $st_label = "Transaksi Gagal";
        $statusnya = " <span class='badge badge-danger' style='color:white;padding:5px 10px 5px 10px'>Refund</span>";
    } else if ($status == 4) {
        $st_image = "https://assets.bukakios.net/img2/uploads/2019/12/822-button.png";
        $st_label = "Transaksi Gagal";
        $statusnya = " <span class='badge badge-danger' style='color:white;padding:5px 10px 5px 10px'>Refund</span>";
    }
    $target = $nomor_tujuan;
    if ($detail_transaksi['pembelianoperator_id'] == 18 or $detail_transaksi['pembelianoperator_id'] == 113) {
        //pln .. nomor jadi id_pel
        if (!empty($detail_transaksi['sn'])) {
            $sn_pecah = explode("/", $detail_transaksi['sn']);
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
            $link_open_rate = $open_url . "https://play.google.com/store/apps/details?id=$app_id";
            $fakta_teks = "Tahukah kamu bahwa pembelian kamu di proses sangat cepat loh oleh bukakios, hanya dalam <b>$lama_proses detik</b>, pembelian kamu telah berhasil di proses :)
			<p><a target='_blank' href='$link_open_rate' class='btn btn-warning btn-block btn-sm'><i class='font-icon font-icon-star'></i> Beri Rating</a>
			";
        } else if ($lama_proses > 80 and $lama_proses < 420) { //8 menit
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
    exit;
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Nunito" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://assets.bukakios.net/css/box.css" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/notie/dist/notie.min.css">
    <title>title::Detail Transaksi #<?= $id; ?></title>
    <style>
        .notie-container {
            box-shadow: none;
        }

        .title {
            background-color: #f8f8fe;
            padding: 10px;
            color: #66728a;
            font-weight: bold;
            font-size: 20px;
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .cell-breakWord {
            /* word-wrap: break-word;
            max-width: 1px; */
            word-wrap: break-word;
            max-width: 1px;
            -webkit-hyphens: auto;
            /* iOS 4.2+ */
            -moz-hyphens: auto;
            /* Firefox 5+ */
            -ms-hyphens: auto;
            /* IE 10+ */
            hyphens: auto;
        }

        .card {
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
            /* border: 1px; */
        }

        .box-circle {
            width: auto;
            height: 100px;
            /* border: 0px solid #2196F3; */
            border: 0px;
            background-color: #2196F3;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            border-bottom-left-radius: 150%;
            border-bottom-right-radius: 150%;
        }

        .product-logo {
            margin-top: -20px;
            text-align: center;
        }

        .bayar-id {
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
        }

        .circle-logo {
            display: flex;
            justify-content: center;
            position: absolute;
            top: 10%;
            left: 41%;
            background-color: #fff;
            width: 18%;
            height: 10%;
            border-radius: 50%;
            border: 1px solid #fff;
        }

        .product-img {
            position: relative;
            width: 15%;
            border-radius: 50%;
            border: 3px solid #2196F3;
            background-color: #fff;
        }

        .det-buy-text {
            font-size: 12px;
            font-weight: bold;
        }

        .col-text {
            font-size: 14px;
            width: 90px;
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
            color: white;
            text-align: center;
            padding-top: 2px;
            padding-bottom: 10px;
        }

        body {
            font-family: Nunito;
        }

        hr {
            border: 2px dashed #9C9C9C;
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

<body>
    <div class="py-3" style='background-color:<?PHP echo $primary; ?>;height:90px'>
        <!-- <div class="mx-auto py-3 px-3 text-center">
             <img src="<?= $st_image; ?>" height="60px"><br />
            <span class="mt-3 font-weight-bold text-white"><?= $st_label; ?></span>
        </div> -->
        <!--<div class="text-center mb-1">
         <img class="mb-1" src=<?= $st_image ?>><br/>
         <img class="mb-1" width="120px" src=<?= $st_image ?>><br />

    </div> -->
    </div>
    <div class="body">
        <div style="margin-top:-50px;margin-right:2px;margin-left:2px">
            <div class="container">
                <div class="card" style="border-radius:10px">
                    <!-- <div class="box-circle">
                        <div class="text-center mt-3" style="color:#fff">
                            <strong style="font-size:10px"><?= $product_name ?></strong><br />
                            <span style="font-size:8px"><?= $nomor_tujuan ?></span>
                        </div>
                        <div class="circle-logo"></div>
                    </div> -->
                    <!-- <div class="container"> -->
                    <div class="product-logo">
                        <img src="<?= $product_logo; ?>" class="product-img">
                    </div>
                    <div class="py-1 px-4">
                        <div class="row">
                            <div class="col-12 text-center">
                                <strong style="font-size:15px"><?= $product_name ?></strong>
                            </div>
                            <div class="col-12 text-center">
                                <span style="font-size:14px;font-weight:bold"><?= $nomor_tujuan ?></span>
                            </div>
                        </div>
                        <hr />
                    </div>
                    <div class="pb-1 px-4">
                        <span class="font-weight-bold">ID Transaksi</span>

                        <div id="copy_idd" data-text="ID Topup Berhasil Disalin" data-copy="<?= $id ?>"></div>
                        <span id="copy_id" class="font-weight-bold">#<?= $id ?>
                            <a href=''>
                                <img src='https://assets.bukakios.net/img2/uploads/2019/12/569-refresh.png' style='height:20px;width:20px;margin-left:2px;margin-top:-2px' />
                            </a>
                        </span>
                        <br />
                        <span class="my-2" style='color:grey;font-size:19px'>Status Transaksi</span>
                        <br />
                        <?PHP echo $statusnya; ?><br />
                        <span class="text-wrap font-weight-light" style="font-size:13px">Pada <?PHP echo $app->time_ago($created_at); ?></span>
                        <hr>

                        <div class="title">
                            <span><i class="fa fa-info-circle mr-1"></i> Detail Pembelian</span>
                        </div>

                        <table style="margin-top:10px">
                            <tr>
                                <td class="col-text">Produk</td>
                                <td class="det-buy-text">: <?= $product_name; ?></td>
                            </tr>
                            <tr>
                                <td class="col-text">No Tujuan</td>
                                <td class="det-buy-text">: <?= $nomor_tujuan; ?></td>
                            </tr>
                            <tr>
                                <td class="col-text" style="vertical-align: text-top;">Tanggal</td>
                                <td class="cell-breakWord det-buy-text">: <?= datee($created_at); ?></td>
                            </tr>
                            <?php
                            if ($pembelian_kategori_id == 7) {
                            ?>
                                <tr>
                                    <td class="col-text" style="vertical-align: text-top;">SN / Catatan</td>
                                    <td class="cell-breakWord det-buy-text">: <?= $status_ppob; ?></td>
                                </tr>
                            <?php
                            } else {
                            ?>
                                <tr>
                                    <td class="col-text" style="vertical-align: text-top;">SN / Catatan</td>
                                    <td class="cell-breakWord det-buy-text">: <?= $sn; ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </table>
                        <hr>
                        <div class="title">
                            <span><i class="fa fa-money mr-1"></i> Detail Pembayaran</span>
                        </div>
                        <table style="margin-bottom: 10px;margin-top: 10px">
                            <tr>
                                <td class="col-text" style="width:150px">Saldo Awal</td>
                                <td class=" det-buy-text" style="font-weight:bold;text-align: right"><?= $app->idr($saldo_before_trx); ?></td>
                            </tr>
                            <tr>
                                <td class="col-text" style="width:150px">Harga Produk</td>
                                <td class=" det-buy-text " style="font-weight:bold;text-align: right"><?= $app->idr($price_client); ?></td>
                            </tr>
                            <!--<tr>
                            <td style="vertical-align: text-top; margin-bottom:-10px"></td>
                            <td class="cell-breakWord">
                                <hr />
                            </td>
                        </tr>-->
                            <tr>
                                <td class="col-text" style="width:150px">Sisa Saldo</td>
                                <td class=" det-buy-text text-primary" style="font-weight:bold;text-align: right"><?= $app->idr($saldo_after_trx); ?></td>
                            </tr>

                        </table>

                        <div class="alert alert-primary">
                            <h5>Detail Keuntungan</h5>
                            <table>
                                <tr>
                                    <td class="col-text" style="width:150px">Harga Jual Kamu</td>
                                    <td class="det-buy-text" style="font-weight:bold;"><?= $app->idr($selling_price_client); ?> <a data-toggle="modal" data-target="#exampleModal"><i class="fa fa-pencil ml-1 text-primary"></i></a></td>
                                </tr>
                                <tr>
                                    <td class="col-text" style="width:150px">Harga Modal/Produk</td>
                                    <td class="det-buy-text text-danger" style="font-weight:bold;"><?= $app->idr($price_client); ?></td>
                                </tr>
                                <tr>
                                    <td class="col-text text-success" style="width:150px">Profit Kamu</td>
                                    <td class=" det-buy-text text-success" style="font-weight:bold;"><?= $app->idr($selling_price_client - $price_client); ?></td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            <div class="mt-3">
                <?php
                if (isset($h2h_id)) {
                ?>
                    <div class='container'>
                        <?php
                        if (isset($svd_error)) {
                            if ($svd_error) {
                        ?>
                                <div class="alert alert-warning"><?php echo $svd_error_msg ?></div>
                            <?php
                            } else {
                            ?>
                                <div class="alert alert-succcess">
                                    <h5>Voucher Kamu</h5>
                                    <img width="80%" src="<?php echo $svd_data['voucher_image']; ?> " alt="">
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </div>
                <?php
                }
                ?>

                <div class='container'>
                    <?PHP if ($status > 0) {
                        require_once("additional_info_trx.php");
                    } ?>
                </div>
                <div class='container'>
                    <?PHP
                    if (isset($fakta_tipe)) {
                        echo "
						<div class='alert bayar-id alert-fill alert-$fakta_tipe'>
						$fakta_teks
						</div>
						";
                    }
                    ?>
                </div>
                <div class="col-12 mb-2 text-center">
                    <a href="https://wv.bukakios.net/beli_lagi/index.php?nomor_tujuan=<?= $nomor_tujuan; ?>&kode_produk=<?= $product_code; ?>" class="btn btn-success btn-block <?= $user_id != '40408' ? 'disabled' : '' ?>" style="color:#fff"><i class="fa fa-shopping-cart"></i> Beli Lagi</a>
                </div>

                <?PHP
                if ($status == 1) {
                ?>
                    <div class="col-12 mb-2 text-center">
                        <a href='<?PHP echo "print://https://member.bukakios.net/print-json/$signature/$id.json"; ?>' style='' class='btn btn-success btn-block'>Cetak Struk</a>
                        <a href='<?PHP echo $open_url . "https://member.bukakios.net/pdf-mini/download/$signature/$id.pdf"; ?>' style='background-color:<?= $primary; ?>' class='btn btn-primary btn-block'>Download Struk</a>
                        <div class='alert alert-primary bayar-id' style='margin-top:10px'>
                            Apakah transaksi ini di hutangi oleh pelanggan, biar gak lupa yukk di catet di aplikasi aja. <a href='<?PHP echo "catathutang://$msg"; ?>' class='btn btn-danger btn-block'>Catat Hutang</a>
                        </div>
                    </div>
                <?PHP } ?>
                <div class="col-12 mb-2 text-center">
                    <div class='alert alert-primary bayar-id' style='margin-top:10px'>
                        Butuh Bantuan? silahkan hubungi customer care kami di sini. <a style='margin-left:10px;margin-right:10px;background-color:<?= $primary; ?>' href='intercom://open.it' class='btn btn-primary btn-block'><i class="fa fa-envelope"></i> Hubungi CS BukaKios</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

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
                            <input type="number" id="fee" class="form-control" value="<?php echo $selling_price_client ?>">
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
                    url: 'index.php?msg=update&harga=' + harga + '&csrf=' + csrf + '&id_trx=<?php echo $trx_id ?>',
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
</body>

</html>