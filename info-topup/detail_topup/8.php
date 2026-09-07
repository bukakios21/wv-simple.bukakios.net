<?PHP
//6 = BCAVA
//BCAVA get info nomor rekening dari api
//pg = winpay
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// $data_post = array(
//     "key"=>$api_key,
//     "id"=>$topup_id
// );
// $data_info = $app->curl_post("$api_url/get_topup_detail.php",$data_post);
// $data_info = json_decode($data_info,true);
$data_info=callTopupApi($topup_id,$user_jwt);
$data_info = json_decode($data_info,true);
if(isset($data_info['status'])){
    $pg_error = false;
    if($data_info['status']==1){
        //berhasil
        if(isset($data_info['data']['data'])){
            $nomor_rekening = $data_info['data']['data'];
            $atas_nama = "";
        }else{
            $pg_error = true;
            $error_msg = "Payment info tidak ada VAnya, silahkan kontak tim kami!";
        }
    }else{
        $pg_error = true;
        $error_msg = "Ada Kesalahan -> ".$data_info['error_msg'];
    }
}else{
    $pg_error = true;
    $error_msg = "Server Api Respon Tidak Valid!!, silahkan kontak tim kami!";
}
?>
<?php if (!$pg_error) {?>
    <div class='row'>
        <div class="col-12">
            <span class="font-weight-bold">Transfer Ke</span>
            <div id='info-bank' class="text-center">
                <!-- <div class='col-md-6 col-xs-12 mx-auto'> -->
                <div class='box' style='padding:20px;cursor:default'>
                    <center><img src='<?PHP echo "https://www.jagoanhosting.com/wp-content/uploads/2017/06/VAbca-square.png"; ?>' width='120px'/></center>
                    <p style='margin-top:10px'>Bank : <b><?PHP echo $nama_metode; ?></b><br>
                        Nomor Virtual Account : <b id='nomor_rekening' data-text="No. Rekening VA berhasil disalin" data-copy="<?PHP echo str_replace("-","",$nomor_rekening); ?>"><?PHP echo $nomor_rekening; ?></b> <br><span style="text-decoration:underline;color:#00bfff;cursor:pointer" onclick="copyToClipboard('nomor_rekening')">Salin No.Rek</span>
                        <br>Atas Nama : <?PHP echo $atas_nama; ?>
                </div>
                <!-- </div> -->
            </div>
        </div>
    </div>
<?php } ?>
    <!-- </div> dari awal index.php -->
    </div>

<?PHP if(!$pg_error){ ?>

    <?PHP require_once("detail_topup/warning-transfer-sesuai-nominal.php"); ?>
    <div id='panduanbayar'>
        <div class="pl-16 pr-16 pt-24">
            <div class="">
                <h5>PANDUAN PEMBAYARAN</h5>
            </div>

            <div id="accordion">
                <div class="card">
                    <div style='cursor:pointer' class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <h5 class="mb-0">
                            <a>
                                ATM BCA
                            </a>
                            <span class='pull-right'><i class='fa fa-angle-down'></i></span>
                        </h5>
                    </div>
                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <div class='row'>
                                <div class="col-md-12 col-sm-12">
                                    <ol style='padding-left:10px;padding-right:10px'>
                                        <li>Masukkan Kartu ATM BCA &amp; PIN</li>
                                        <li>Pilih menu &quot;Transaksi Lainnya&quot;</li>
                                        <li>Pilih &quot;Transfer&quot;</li>
                                        <li>Pilih &quot;ke Rekening BCA Virtual Account&quot;</li>
                                        <li>Masukan Nomor Rekening Virtual Account Anda : <?PHP echo $nomor_rekening; ?></li>
                                        <li>Pastikan detil tagihan Anda sudah benar, kemudian pilih &ldquo;BENAR&rdquo;</li>
                                        <li>Perhatikan Konfirmasi Pembayaran Anda, jika sudah benar pilih &ldquo;YA&rdquo;, atau pilih &ldquo;TIDAK&rdquo; jika data di layar masih salah</li>
                                        <li>Transaksi Anda sudah selesai, simpan struk transaksi sebagai bukti pembayaran</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- 1 -->

                <div class="card" style='margin-top:0px'>
                    <div style='cursor:pointer' class="card-header" id="headingTwo" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                        <h5 class="mb-0">
                            <a>
                                m-BCA (BCA mobile)
                            </a>
                            <span class='pull-right'><i class='fa fa-angle-down'></i></span>
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            <div class='row'>
                                <div class="col-md-12 col-sm-12">
                                    <ol style='padding-left:10px;padding-right:10px'>
                                        <li>Lakukan log in pada aplikasi BCA Mobile</li>
                                        <li>Pilih menu &ldquo;m-Transfer&rdquo;</li>
                                        <li>Pilih BCA Virtual Account</li>
                                        <li>Lalu masukan nomor virtual account kamu : <b><?PHP echo $nomor_rekening; ?></b></li>
                                        <li>Pastikan detil tagihan Anda sudah benar, kemudian pilih &ldquo;OK&rdquo;</li>
                                        <li>Masukkan pin m-BCA</li>
                                        <li>Pembayaran selesai. Simpan notifikasi yang muncul sebagai bukti pembayaran</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- 2 -->

                <div class="card" style='margin-top:0px'>
                    <div style='cursor:pointer' class="card-header" id="headingThree" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                        <h5 class="mb-0">
                            <a>
                                Internet Banking BCA
                            </a>
                            <span class='pull-right'><i class='fa fa-angle-down'></i></span>
                        </h5>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="card-body">
                            <div class='row'>
                                <div class="col-md-12 col-sm-12">
                                    <ol style='padding-left:10px;padding-right:10px'>
                                        <li>Login pada alamat Internet Banking BCA (<a href="https://klikbca.com" target="_blank">https://klikbca.com</a>)</li>
                                        <li>Masukkan user ID dan PIN</li>
                                        <li>Pilih &ldquo;Transfer Dana&rdquo;</li>
                                        <li>Pilih &ldquo;Transfer Ke BCA Virtual Account&rdquo;</li>
                                        <li>Lalu masukan nomor virtual account : <b><?PHP echo $nomor_rekening; ?></b></li>
                                        <li>Validasi detil tagihan Anda, kemudian pilih &ldquo;Lanjutkan&rdquo;</li>
                                        <li>Masukkan mtoken Anda</li>
                                        <li>Cetak atau simpan bukti pembayaran Anda</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- 3 -->

                <div class="card" style='margin-top:0px'>
                    <div style='cursor:pointer' class="card-header" id="headingFour" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                        <h5 class="mb-0">
                            <a>
                                Kantor Bank BCA
                            </a>
                            <span class='pull-right'><i class='fa fa-angle-down'></i></span>
                        </h5>
                    </div>
                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                        <div class="card-body">
                            <div class='row'>
                                <div class="col-md-12 col-sm-12">
                                    <ol style='padding-left:10px;padding-right:10px'>
                                        <li>Ambil nomor antrian transaksi Teller dan isi slip setoran</li>
                                        <li>Serahkan slip dan jumlah setoran kepada Teller BCA</li>
                                        <li>Teller BCA akan melakukan validasi transaksi</li>
                                        <li>Simpan slip setoran hasil validasi sebagai bukti pembayaran</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- 4 -->

            </div>


        </div>
    </div>
<?PHP }else{ ?>
    <div class='alert alert-danger mt-3 bayar-id'><?PHP echo $error_msg; ?></div>
<?PHP } ?>