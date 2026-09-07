<?php
require_once("../config.php");
// require_once("../_session.php");
$user_id = 40408;
// $user_token = "99113721eae0b8eea9e61d339a2655a9";
// $user_token_trx = "059e9eaaf32d0bd22505e35e7b7bb959";
// echo $bukakios_version;

if (isset($_GET['nomor_tujuan'], $_GET['kode_produk'])) {
    $nomor_tujuan = $_GET['nomor_tujuan'];
    $code = $_GET['kode_produk'];
    $_SESSION['nomor_tujuan'] = $nomor_tujuan;
    $_SESSION['kode_produk'] = $code;
} else {
    $nomor_tujuan = $_SESSION['nomor_tujuan'];
    $code = $_SESSION['kode_produk'];
}


$data = $app->grab_data("https://api.bukakios.net/v1/data_user_dev.php?key=$api_key&id=$user_id");
$data = json_decode($data, true);
$saldo = $data['data']['saldo'];
// $saldo = 165100;

$file_me = "index.php";
$data_post_produk = array(
    "key" => $api_key,
    "code" => $code
);
$data_produk = $app->curl_post("$api_url/v1/detail_produk.php", $data_post_produk);
$data_produk = json_decode($data_produk, true);
if (isset($data_produk['status']) and $data_produk['status'] == 1) {
    $data_produk = $data_produk['data'];
    $code = $data_produk['code'];
    $product_logo = $data_produk['product_logo'];
    $profit = str_replace("-", "", $data_produk['harga_jual']);
    $product_name = $data_produk['product_name'];
    $price = $data_produk['harga_modal'];
    $harga_client = $data_produk['harga_client'];
    $poin = $data_produk['reward_poin'];
    $deskripsi = $data_produk['product_description'];
    $operator_name = $data_produk['operator_name'];
} else {
    $error_msg = "Server untuk mendapatkan data produk gagal di muat... silahkan coba lagi beberapa saat!!";
    if (isset($data_produk['error_msg'])) {
        $error_msg = $data_produk['error_msg'];
    }
    $html_title = "Gagal";
    $lyt_button_link = "$c_url/beli_lagi/?nomor_tujuan=$nomor_tujuan&kode_produk=$code";
    $lyt_button_name = "COBA LAGI";
    $lyt_image = "https://assets.bukakios.net/img/illustration/bc_trx_gagal.png";
    $lyt_title = "Ada Kesalahan!";
    $lyt_description = $error_msg;
    require_once(ROOT . "/_template/general_message.php");
    exit;
}

if (strpos(strtolower($operator_name), "telkomsel") !== false) {
    $color_header = "#E53935";
} elseif (strpos(strtolower($operator_name), "axis") !== false) {
    $color_header = "#8E24AA";
} elseif (strpos(strtolower($operator_name), "indosat") !== false) {
    $color_header = "#F9A825";
} elseif (strpos(strtolower($operator_name), "xl") !== false) {
    $color_header = "#1E88E5";
} elseif (strpos(strtolower($operator_name), "tri") !== false) {
    $color_header = "#616161";
} elseif (strpos(strtolower($operator_name), "smartfren") !== false) {
    $color_header = "#EF5350";
} else {
    $color_header = "#2196F3";
}

if (isset($_REQUEST['msg'])) {
    require_once '_act.php';
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Nunito" />
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Poppins" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>title::Beli Produk </title>
    <style>
        .footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: #fff;
            color: white;
            border-top: 1px solid #3498db;
            text-align: center;
        }

        .aa {
            background-color: <?= $primary ?>;
        }

        .text-aa {
            color: <?= $primary ?>;
        }

        body {
            font-family: Poppins;
            /* font-family: 'Lato', sans-serif; */
            color: #838383;
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

        .shadow {
            /* box-shadow:  0 55px 110px -15px rgba(0, 0, 0, 0.07); */
            -webkit-box-shadow: 0px 10px 24px -4px rgba(48, 46, 48, 0.32);
            -moz-box-shadow: 0px 10px 24px -4px rgba(48, 46, 48, 0.32);
            box-shadow: 0px 10px 24px -4px rgba(48, 46, 48, 0.32);
        }

        .transition {
            animation: transitionIn 1s;
        }

        @keyframes transitionIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .margin {
            margin: 15px;
        }

        .card {
            color: white;
        }

        /* .col-md-12 { */
        /* padding-right:12px !important; */
        /* padding-left:12px !important; */
        /* } */

        input::placeholder {
            font-size: 12px;
            color: #838383;
        }

        .right {
            width: 5%;
        }

        .middle {
            width: 40%;
        }

        .float {
            float: left;
        }

        .left {
            width: 30%;
        }

        .right {
            width: 5%;
        }

        .middle {
            width: 70%;
        }

        .float {
            float: left;
        }

        body {
            font-family: Nunito;
            color: #838383;
            background-color: #f5f5f5;
        }

        label {
            color: #838383;
            font-size: 12px;
        }

        .colorku {
            color: #838383;
        }

        select option {
            font-size: 13pt;
        }

        select option:first-child {
            font-size: 7pt;
        }

        .myFont {
            font-size: 10px;
        }

        .select_join option {
            font-size: 13px;
        }

        .select_join select {
            font-size: 13px;
        }

        table tr td {
            font-size: 12px;
        }
    </style>
</head>

<body class="body">
    <!-- <div class="card margin shadow" style="background-color:<?= $primary ?>; background-size:cover">
        <div class="card-body text-center" style="text-align:left !important;">
            <div class="left float">
                <img class="img-header" style="max-width:70%" src="buy-online.svg" alt="">
            </div>
            <div class="middle float">
                <h5 class="card-title"><b><span style="font-size:14px; color:white">Beli lagi Produk Anda</span></b></h5>
                <p class="card-text" style="text-align:left; font-size:10px">Produk <b><?= $product_name ?></b></p>
            </div>
        </div>
    </div> -->
    <div class="container" style="margin-bottom:60px;margin-top:20px">
        <div class="row " id='input_data'>
            <div class="col-lg-12">
                <div class="card" style="margin-bottom:10px;border-radius:8px">
                    <div class="card-header" style="background-color:<?= $color_header ?>;padding:5px 0px 5px 10px;">
                        <table>
                            <tr>
                                <td><img style="border-radius:50px" src="<?= $product_logo ?>" width="35px" height="35px"></td>
                                <td style="padding:2px 0px 0px 7px"><?= $operator_name ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="card-body">
                        <table>
                            <tr>
                                <td width="240px" style="color:#000"><span class="mt-1  "><strong><?= $product_name ?></strong></span></td>
                                <td rowspan=2 style="vertical-align:top">
                                    <div class="align-right">
                                        <span style="color:#000"><strong><?= $app->idr($price) ?></strong> </span> <br />
                                        <img class="" src="https://i.pinimg.com/originals/eb/e0/ba/ebe0bab5a16088b7f66a806a7f522b23.png" height="20px" width="20px">
                                        <span class="point " style="color:<?= $success ?>">+ <?= $poin ?></span>
                                    </div>

                                </td>
                            </tr>
                            <tr>
                                <td style="color:#000"><small class="mt-1"><?= $deskripsi ?></small></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="card" style="margin-bottom:10px;">
                    <div style="padding:10px">
                        <div class="form-group" style="padding-left:12px">
                            <label for="">Nomor Tujuan</label>
                            <input type="hidden" id="csrf" class="form-control" value="<?= $app->csrf() ?>">
                            <input type="number" id="nope" class="form-control" value="<?= $nomor_tujuan ?>" placeholder="Masukkan Nomor Tujuan" required>
                        </div>
                        <div class="form-group" style="padding-left:12px">
                            <label for="">Harga Jual Di Kios Kamu</label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Rp</div>
                                </div>
                                <input type="number" id="harga" class="form-control" value="<?= $harga_client ?>" placeholder="Masukkan Nomor Tujuan" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card" style="margin-bottom:10px;">
                    <div style="padding:10px">
                        <div class="col-12">
                            <label>Saldo Kamu Sekarang</label>
                        </div>
                        <div class="col-12">
                            <strong style="color:#000"><?= $app->idr($saldo) ?></strong>
                        </div>
                        <div class="col-12">
                            <label>Pembayaran</label>
                        </div>
                        <div class="col-12">
                            <strong style="color:#000">- <?= $app->idr($price) ?></strong>
                        </div>
                        <div class="col-12">
                            <label>Sisa Saldo</label>
                        </div>
                        <div class="col-12">
                            <?php $sisa = $saldo - $price ?>
                            <strong style="color:#000"><?= $app->idr($sisa) ?></strong>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <div class="loader mx-auto mt-4" id="load" style="display:none;"></div>
        <span class="text-danger" id="error" style="display:none"></span>


        <div id="detail_tagihan" style="display:none">
            <div class="form-group" style="margin-top:15px">
                <div style="text-align:left ">
                    <div>

                        <div class="table-responsive shadow border1px" style="padding:10px;">
                            <h5 style=" padding-left:10px; padding-top:10px; text-align:center" class="black"><b>Informasi Transfer</b></h5>
                            <table style="width:100%" class="table">
                                <tr>
                                    <td width="10px;"><b>*</b></td>
                                    <td style="padding-top:10px;"><span>Nama pelanggan</span><br /><span class="txt-desc" style="font-size:12px"><b>Nama pelanggan</b></span></td>
                                    <td><b></span><span id="d_nama"></span></b><br /></td>
                                </tr>
                                <tr>
                                    <td width="10px;"><b>*</b></td>
                                    <td style="padding-top:10px;"><span>Nomor pelanggan</span><br /><span class="txt-desc" style="font-size:12px"><b>Nomor pelanggan</b></span></td>
                                    <td><b></span><span id="r_id_pelanggan"></span></b><br /></td>
                                </tr>
                                <tr>
                                    <td width="10px;"><b>*</b></td>
                                    <td style="padding-top:10px;"><span>Total tagihan</span><br /><span class="txt-desc" style="font-size:12px"><b>Total tagihan BPJS</b></span></td>
                                    <td><b><span style="font-size:9px;">Rp.</span> <span id="tot_ta"></span></b><br /><span class="txt-desc" style="font-size:12px">Total tagihan</span></td>
                                </tr>
                                <tr>
                                    <td width="10px;"><b>*</b></td>
                                    <td style="padding-top:10px;"><span>Biaya admin </span><br /><span class="txt-desc" style="font-size:12px"><b>Total admin </b></span></td>
                                    <td><b><span style="font-size:9px;">Rp.</span> <span id="biaya"></span></b><br /><span class="txt-desc" style="font-size:12px">Total admin</span></td>
                                </tr>
                                <tr>
                                    <td width="10px;"><b>*</b></td>
                                    <td style="padding-top:10px;"><span>Bayar kamu</span><br /><span class="txt-desc" style="font-size:12px"><b>Total keseluruhan transaksi</b></span></td>
                                    <td><b><span style="font-size:9px;">Rp.</span> <span id="tot_ka"></span></b><br /><span class="txt-desc" style="font-size:12px">Total transaksi</span></td>
                                </tr>
                                <tr>
                                    <td width="10px;"><b>*</b></td>
                                    <td style="padding-top:10px;"><span>Profit kamu</span><br /><span class="txt-desc" style="font-size:12px"><b>Total profit</b></span></td>
                                    <td><b><span style="font-size:9px;">Rp.</span> <span id="profit"></span></b><br /><span class="txt-desc" style="font-size:12px">Total profit</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card shadow">
                <div class="card-body text-center" style="text-align:left !important;">
                    <div>
                        <h2 class="card-title colorku" style="text-align:center; "><b><span style="font-size:15px;">Total saldo kamu berkurang</span></b></h2>
                        <div class="alert alert-info" id="tot_ka2">Rp. 20.000</div>
                    </div>

                </div>
            </div>


            <div class="card shadow" style="margin-top:20px; margin-bottom:20px">
                <div class="card-body text-center" style="text-align:left !important;">
                    <div>
                        <h2 class="card-title colorku" style="text-align:center;"><b><span style="font-size:15px; ">Total bayar pelanggan kamu</span></b></h2>
                        <div class="alert alert-info" id="tot_ta2">Rp. 20.000</div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="mx-3 mb-2">
            <div id="fotfot" class="row mt-2" style="display:none">
                <div class="col-6">
                    <button class="btn btn-md aa text-white btn-rounded btn-block" style="display:none;background-color:<?= $primary ?>;color:#fff" id="pay">Bayar</button>
                </div>
                <div class="col-6">
                    <button class="btn btn-md aa text-white btn-rounded btn-block" style="display:none;background-color:<?= $danger ?>;color:#fff" id="cancel">Batal</button>
                </div>
            </div>

            <div id="payload" class="row mt-2" style="display:none">
                <div class="col-2">
                    <div class="loader" id="loadfot" style="display:none;"></div>
                </div>
                <div class="col-auto my-auto">
                    <marquee><span id="mar" style="display:none;color:#000">Silahkan Tunggu Beberapa saat</span></marquee>
                </div>
            </div>

            <button class="btn btn-md aa text-white btn-block btn-rounded" style="display:none;background-color:<?= $danger ?>;color:#fff" id="lol">Batal</button>
            <?php
            if ($sisa < 0) { ?>
                <button class="btn btn-md btn-block" id="cek" style="background-color:<?= $primary ?>;color:#fff" disabled=true>Saldo anda tidak cukup</button>
            <?php
            } else { ?>
                <button class="btn btn-md btn-block" id="beli" style="background-color:<?= $primary ?>;color:#fff">Beli</button>
            <?php
            }
            ?>
            <!-- <div class="loader" id="loadfot" style="display:none;"></div> -->
            <button class="btn btn-md btn-block" id="wait" style="background-color:<?= $primary ?>;color:#fff;display:none" disabled=true>Silahkan Tunggu...</button>
        </div>
    </div>
    <script src="../assets/js/jquery-3.2.1.slim.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/sweetalert.min.js"></script>
    <!-- <script src="../assets/js/sweetalert2.min.js"></script> -->
    <script>
        function custom() {
            Swal.fire({
                title: "<b>Title</b>",
                html: "HTML Description",
                confirmButtonText: "Okey",
            });
        }
    </script>
    <script>
        // $("#periode").select2({ dropdownCssClass: "myFont" });
        $(document).ready(function() {
            function rubah_rp(angka) {
                var reverse = angka.toString().split('').reverse().join(''),
                    ribuan = reverse.match(/\d{1,3}/g);
                ribuan = ribuan.join('.').split('').reverse().join('');
                return "Rp. " + ribuan;
            }
            var profit = <?= $profit ?>;
            var trx_id = "";
            var inq_id = "";


            $('#beli').on('click', function() {
                var id_pelanggan = $('#nope').val();
                if (id_pelanggan != "") {
                    var no = $('#nope').val();
                    var harga = $('#harga').val();
                    var csrf = $('#csrf').val();
                    $('#beli').hide();
                    $('#wait').show();

                    $.ajax({
                        url: '<?= $file_me ?>?msg=beli&no=' + no + '&csrf=' + csrf + '&sell_price=' + harga,
                        success: function(output) {
                            $('.footer').show();
                            $('#load').hide();
                            // var myJsn = JSON.parse(output);
                            console.log(output);
                            // var d = myJsn.data;
                            // if (myJsn.status == 1) {
                            //     //arahkan ke pesan transaksi sukses...
                            //     swal({
                            //         title: 'Transaksi Berhasil',
                            //         text: 'Transaksi anda sedang di proses. mohon menunggu. halaman akan otomatis dialihkan ketika proses selesai',
                            //         icon: 'success'
                            //     });
                            //     setTimeout(function() {
                            //         window.location.href = "<?= $c_url ?>/_template/session_success.php";
                            //     }, 3000);
                            // } else {
                            //     var error_msg = myJsn.error_msg;
                            //     swal(error_msg, {
                            //         icon: "warning"
                            //     });
                            //     $('#payload').hide();
                            //     $('#pay').show();
                            //     $('#cancel').show();
                            //     $('#fotfot').show();
                            // }
                        }
                    })

                }
            })

            $('#cancel').on('click', function() {
                $('#cek').show();
                document.getElementById("nope").disabled = false;

                $('#error').hide();
                $('#pay').hide();
                $('#fotfot').hide();
                $('#cancel').hide();
                $('#detail_tagihan').hide();
                $('#input_data').show();
            });

            $('#lol').on('click', function() {
                $('#cek').show();
                document.getElementById("nope").disabled = false;

                $('#error').hide();
                $('#lol').hide();
            });
        })
    </script>
</body>

</html>