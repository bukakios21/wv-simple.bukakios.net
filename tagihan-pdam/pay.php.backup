<?php
require_once("../config.php");
// session_destroy();
require_once("../_session.php");
define("JWT", $user_jwt);

function CallApiV2($data, $is_auth = false, $url)
{
    $vars = json_encode($data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);  //Post Fields
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $headers = [
        'Api-Key: PLowElenThErTeRAphaRDwINEAntrIDe',
        "Authorization: ".JWT
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $server_output = curl_exec($ch);
    return $server_output;
}

$file_me = "pay.php";
$kode_produk = '000'; //data static id produk jarang berubah
if (isset($_GET['code'])) {
    $kode_produk = $_GET['code'];
}
/************ ACTION HERE *****************/
if (isset($_REQUEST['msg'], $_REQUEST['csrf'])) {
    //action here
    $msg = $_REQUEST['msg'];
    $csrf = $_REQUEST['csrf'];
    if ($_SESSION['csrf'] === $csrf) {
        if ($msg === "cek") {
            //cek tagihan...
            $id_pelanggan = $_REQUEST['id_pelanggan'];
            // $post_cek = [
            //     'key'=>$api_key,
            //     'id_pelanggan'=>$id_pelanggan,
            //     'code'=>$kode_produk,
            //     'uid'=>$user_id,
            //     "uid_secret"=>$user_token_trx,
            // ];
            // $cek_tagihan_http = $app->curl_post("$api_url/_produk_ppob/cek_tagihan.php",$post_cek);

            //************************** API V2 ***************************/
            $param = array(
                "kode_produk" => $kode_produk,
                "nomor" => $id_pelanggan,
            );
            $cek_tagihan_http = CallApiV2($param, true, "https://api-v2.bukakios.net/v2/trx-pascabayar/cek-tagihan");
            $app->simpan_file("cek-kode.json", $cek_tagihan_http);
            //************************** API V2 ***************************/

            $cek_tagihan = json_decode($cek_tagihan_http, true);
            if (isset($cek_tagihan['status'])) {
                $data_r = $cek_tagihan;
            } else {
                $data_r = ['status' => 0, "error_msg" => "Gagal Cek Tagihan, server a1 tidak merespon, no status", "r" => $cek_tagihan];
            }
        } else if ($msg === "bayar") {
            //bayar tagihan
            if (isset($_REQUEST['inq_id'], $_REQUEST['trx_id'], $_REQUEST['biaya_toko'])) {
                $inq_id = $_REQUEST['inq_id'];
                $trx_id = $_REQUEST['trx_id'];
                $biaya_toko = $_REQUEST['biaya_toko'];
                // $post_bayar = [
                //     'key' => $api_key,
                //     'uid' => $user_id,
                //     "uid_secret" => $user_token_trx,
                //     "inq_id" => $inq_id,
                //     "trx_id" => $trx_id,
                //     "biaya_toko" => $biaya_toko
                // ];
                // $bayar_tagihan_http = $app->curl_post("$api_url/_produk_ppob/bayar_tagihan.php", $post_bayar);

                //**************************** API V2 ********************************/
                $param = array(
                    "trx_id" => $trx_id,
                    "inq_id" => $inq_id,
                    "biaya_toko" => $biaya_toko
                );
                $bayar_tagihan_http = CallApiV2($param, true, "https://api-v2.bukakios.net/v2/trx-pascabayar/bayar-tagihan");
                $app->simpan_file("bayar.json", $bayar_tagihan_http);
                //**************************** API V2 ********************************/

                $bayar_tagihan = json_decode($bayar_tagihan_http, true);
                if (isset($bayar_tagihan['status'])) {
                    $data_r = $bayar_tagihan;
                } else {
                    $data_r = ['status' => 0, "error_msg" => "server a1 tidak merespon, status0", "r" => $bayar_tagihan, 'a' => $post_bayar];
                }
            } else {
                $data_r = ['status' => 0, "error_msg" => "Inquiry ID tidak di temukan, refresh halaman ini"];
            }
        } else {
            $data_r = ['status' => 0, "error_msg" => "Tidak ada aksi untuk msg ini"];
        }
    } else {
        $data_r = ['status' => 0, "error_msg" => "Halaman Kadaluarsa, silahkan tutup halaman ini, kemudian buka kembali"];
    }
    echo json_encode($data_r);
    exit;
}
/************ ACTION HERE *****************/


$data_post_produk = array(
    "key" => $api_key,
    "code" => $kode_produk
);
$data_produk = $app->curl_post("$api_url/v1/detail_produk.php", $data_post_produk);
$data_produk = json_decode($data_produk, true);
if (isset($data_produk['status']) and $data_produk['status'] == 1) {
    $data_produk = $data_produk['data'];
    $code = $data_produk['code'];
    $product_logo = $data_produk['product_logo'];
    $product_name = $data_produk['product_name'];
    $profit = str_replace("-", "", $data_produk['harga_jual']);
    $price_sell = $data_produk['price_sell'] - 1000;
} else {
    $error_msg = "Server untuk mendapatkan data produk gagal di muat... silahkan coba lagi beberapa saat!!";
    if (isset($data_produk['error_msg'])) {
        $error_msg = $data_produk['error_msg'];
    }
    $html_title = "Gagal";
    $lyt_button_link = "$c_url/tagihan-pdam/";
    $lyt_button_name = "COBA LAGI";
    $lyt_image = "https://assets.bukakios.net/img/illustration/bc_trx_gagal.png";
    $lyt_title = "Ada Kesalahan!";
    $lyt_description = $error_msg;
    require_once(ROOT . "/_template/general_message.php");
    exit;
}

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@200;400;600&display=swap" rel="stylesheet">
    <title>title::<?PHP echo $product_name; ?></title>
    <style>
        html * {
            font-family: 'Nunito Sans', sans-serif;
        }

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

        .bdy {
            font-family: 'Nunito Sans';
        }

        .text-aa {
            color: <?= $primary ?>;
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
    </style>
</head>

<body class="bdy">
    <div style="background-color:<?= $primary ?>;">
        <div class="mx-auto py-5 mb-2 text-center">
            <img width="64px" src="drop.png">
            <p>
                <!--<h5 style="color:white"><?PHP echo $product_name; ?></h5>-->
            </p>
        </div>
    </div>
    <div class="container" style="margin-bottom:60px;">
        <div class="row ">
            <div class="py-3 mx-auto col-md-10">
                <span class="font-weight-bold">Masukan ID tagihan / No Pelanggan</span>
                <div class="row mt-2">
                    <div class="col-12">
                        <input type="text" id="nope" class="form-control" placeholder="Masukkan Nomor tagihan" required>
                        <input type="hidden" id="csrf" class="form-control" value="<?php echo $app->csrf(); ?>">
                    </div>
                </div>
                <span class="font-weight-light text-muted" style="font-size:14px">Silahkan Masukan ID tagihan / No Pelanggan</span>
                <div id="infome">
                    <div class="alert alert-success text-justify" style="margin-top:10px">
                        Bayar tagihan <?PHP echo $product_name; ?> di bukakios kamu otomatis dapet diskon biaya admin sebesar <b><?PHP echo $app->idr($profit); ?></b>
                        <a href="index.php" class="btn btn-block btn-sm btn-danger">Ganti Lokasi</a>
                    </div>

                </div>
            </div>
        </div>
        <div class="loader mx-auto mt-4" id="load" style="display:none;"></div>
        <span class="text-danger" id="error" style="display:none"></span>

        <div id='detail_tagihan' style='display:none'>
            <div class="card shadow">
                <div class="card-header" style="background-color: <?php echo $primary; ?>;color:white">
                    Detail Tagihan
                </div>
                <div class="card-body" style="padding: 0px;">
                    <div>
                        <table class="table">
                            <tr>
                                <td width="55%%" style="">Total Tagihan</td>
                                <td><span class="text-aa" id="tot_ta" style="float:right;font-size:20px"></span></td>
                            </tr>
                            <tr>
                                <td width="55%%" height="10px">Nama Pelanggan</td>
                                <td height="10px"><span style="float:right;" id="nama"></span></td>
                            </tr>
                            <tr>
                                <td width="55%%" height="10px">Periode</td>
                                <td height="10px"><span style="float:right;" id="periode"></span></td>
                            </tr>
                            <tr>
                                <td width="55%%">Tagihan</td>
                                <td><span style="float:right;" id="tagihan"></span></td>
                            </tr>
                            <tr>
                                <td width="55%%">Biaya Admin</td>
                                <td><span style="float:right;" id="biaya"></span></td>
                            </tr>
                            <tr>
                                <td width="55%%">Diskon Biaya Admin</td>
                                <td><span style="float:right;" id="potongan"></span></td>
                            </tr>
                            <tr>
                                <td width="55%%">Total Bayar Kamu</td>
                                <td><b></b><span style="float:right;color:green;font-size:20px" id="tot_ka"></span></b></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="alert alert-primary" style="margin-top:20px">
                <h5>Detail Keuntungan</h5>
                <table>
                    <tr>
                        <td width="55%%" class="col-text" style="">Pelanggan Kamu Bayar</td>
                        <td class="det-buy-text" style="float:right;" id="tot_ta2"></td>
                    </tr>
                    <tr>
                        <td width="55%%" class="col-text" style="">Saldo Kamu Berkurang</td>
                        <td class="text-danger" style="float:right;" id="tot_ka2"></td>
                    </tr>
                    <tr>
                        <td width="55%%" class="col-text text-success" style="">Profit Kamu</td>
                        <td class="text-success" style="float:right;" id="profit"></td>
                    </tr>
                </table>
            </div>

            <div class="card shadow">
                <div class="card-body text-center" style="text-align:left !important;">
                    <div>
                        <h2 class="card-title colorku" style="text-align:center; "><b><span style="font-size:15px;">Buat Biaya Layanan Toko/Kios</span></b></h2>
                        <input type="text" class="form-control" id="biaya_profit" name="biaya_profit" value="0">
                        <small>* Jika ingin mendapatkan profit lebih, silahkan input biaya layanan toko kamu, biaya ini akan muncul di struk</small>
                    </div>
                </div>
            </div>


        </div> <!-- detail_tagihan -->
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
            <button class="btn btn-md btn-block" id="cek" style="background-color:<?= $primary ?>;color:#fff">Cek Tagihan</button>
        </div>
    </div>
    <script src="../assets/js/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="../assets/js/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="../assets/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/sweetalert.min.js"></script>
    <script src="../assets/js/sweetalert2.min.js"></script>
    <script>
        function custom() {
            Swal.fire({
                title: "<b>Cek Nomor Listrik Anda</b>",
                html: "Melihat ID pelanggan PLN yang terdiri dari 11 hingga 12 digit angka pada meteran listrik. jika sulit dilakukan silahkan melihat struk tagihan atau struk pembelian token listrik sebelumnya <br/><img src='<?= $c_url ?>/assets/img/tagihan_pln/antenna.png' class='mt-2' width='100px'>",
                confirmButtonText: "Pahim tum ?",
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            var bilangan = "";
            var number_string = "";

            function rubah_rp(bilangan) {

                number_string = bilangan.toString(),
                    sisa = number_string.length % 3,
                    rupiah = number_string.substr(0, sisa),
                    ribuan = number_string.substr(sisa).match(/\d{3}/g);

                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }
                return "Rp. " + rupiah;
            }

            var trx_id = "";
            var inq_id = "";


            $('#cek').on('click', function() {
                var id_pelanggan = $('#nope').val();
                var csrf = $('#csrf').val();
                // console.log(csrf);
                $('#cek').hide();
                document.getElementById("nope").disabled = true;
                $('#load').show();
                $('.footer').hide();
                $.ajax({
                    url: '<?= $file_me ?>?code=<?PHP echo $code; ?>&msg=cek&id_pelanggan=' + id_pelanggan + '&csrf=' + csrf,
                    success: function(output) {
                        $('.footer').show();
                        $('#load').hide();
                        var myJsn = JSON.parse(output);
                        console.log(myJsn.status);
                        if (myJsn.status == 1) {
                            var dataJsn = myJsn;
                            console.log(dataJsn);
                            // button
                            $('#cancel').show();
                            $('#fotfot').show();
                            $('#infome').hide();
                            $('#pay').show();
                            // form
                            $('#detail_tagihan').show();

                            trx_id = dataJsn.trx_id;
                            inq_id = dataJsn.inq_id;
                            var data = dataJsn.custom_data;
                            var nama_pel = data.nama_pelanggan;
                            var id_pel = data.id_pelanggan;
                            var tagihan = data.tagihan;
                            var biaya_admin = data.biaya_admin;
                            var periode = data.periode;
                            var potongan = data.potongan;
                            var total_bayar_seller = data.total_bayar_seller;
                            var total_bayar_buyer = data.total_bayar_buyer;
                            var tarif_daya = data.tarif_daya;
                            var profit = total_bayar_buyer - total_bayar_seller;
                            console.log(rubah_rp(tagihan));
                            $("#tot_ta").html(rubah_rp(total_bayar_buyer));
                            $("#nama").html(nama_pel);
                            $("#periode").html(periode);
                            $("#tagihan").html(rubah_rp(tagihan));
                            $("#profit").html(rubah_rp(profit));
                            $("#biaya").html(rubah_rp(biaya_admin));
                            $("#potongan").html("- " + rubah_rp(profit));
                            $("#tot_ka").html(rubah_rp(total_bayar_seller));
                            $("#tot_ka2").html(rubah_rp(total_bayar_seller));
                            $("#tot_ta2").html(rubah_rp(total_bayar_buyer));
                            console.log("trx id : " + trx_id);
                        } else {
                            $('#lol').show();
                            $('#error').show();
                            $("#error").html(myJsn.error_msg);
                            swal(myJsn.error_msg, {
                                icon: "warning"
                            });
                            //cancel_click();
                        }
                    }
                })
            });

            $('#pay').on('click', function() {
                if (inq_id != "") {
                    var id_pelanggan = $('#nope').val();
                    var profit = $('#biaya_profit').val();
                    var csrf = $('#csrf').val();
                    console.log(inq_id);
                    console.log(trx_id);
                    $('#payload').show();
                    $('#loadfot').show();
                    $('#mar').show();
                    $('#fotfot').hide();
                    $('#pay').hide();
                    $('#cancel').hide();

                    $.ajax({
                        url: '<?= $file_me ?>?code=<?PHP echo $code; ?>&msg=bayar&biaya_toko=' + profit + '&id_pelanggan=' + id_pelanggan + '&csrf=' + csrf + '&trx_id=' + trx_id + '&inq_id=' + inq_id,
                        success: function(output) {
                            $('.footer').show();
                            $('#load').hide();
                            var myJsn = JSON.parse(output);
                            console.log(id_pelanggan);
                            var d = myJsn.data;
                            if (myJsn.status == 1) {
                                //arahkan ke pesan transaksi sukses...
                                swal({
                                    title: 'Transaksi Berhasil',
                                    text: 'Transaksi anda sedang di proses. mohon menunggu. halaman akan otomatis dialihkan ketika proses selesai',
                                    icon: 'success'
                                });
                                setTimeout(function() {
                                    window.location.href = "<?= $c_url ?>/_template/session_success.php";
                                }, 3000);
                            } else {
                                var error_msg = myJsn.error_msg;
                                swal(error_msg, {
                                    icon: "warning"
                                });
                                $('#payload').hide();
                                $('#pay').show();
                                $('#cancel').show();
                                $('#fotfot').show();
                            }
                        }
                    })

                }
            })

            $('#cancel').on('click', function() {
                cancel_click();
            });

            function cancel_click() {
                $('#cek').show();
                document.getElementById("nope").disabled = false;
                $('#error').hide();
                $('#pay').hide();
                $('#fotfot').hide();
                $('#cancel').hide();
                $('#detail_tagihan').hide();
            }

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