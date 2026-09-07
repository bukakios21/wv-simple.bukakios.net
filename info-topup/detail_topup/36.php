<?PHP
//6 = BNIVA
//BNIVA get info nomor rekening dari api
//pg = winpay
$data_post = array(
    "key"=>$api_key,
    "id"=>$topup_id
);
$data_info = $app->curl_post("$api_url/get_topup_detail.php",$data_post);
$data_info = json_decode($data_info,true);
if(isset($data_info['status'])){
    $pg_error = false;
    if($data_info['status']==1){
        //berhasil
        if(isset($data_info['payment_info'])){
            $payment_info = $data_info['payment_info'];
            $nomor_rekening = $payment_info;

            // $rc = $payment_info['vaNumber'];
            // if($rc!=null){
            //     //ada informasi nomor rekening/pembayaran
            //     if(isset($payment_info['vaNumber'])){
            //         $nomor_rekening = $payment_info['vaNumber'];
            //     }else{
            //         $pg_error = true;
            //         $error_msg = "Data Nomor rekening gagal di terbitkan, #PYERROR!";
            //     }
            // }else{
            //     $pg_error = true;
            //     $error_msg = "Nomor rekening gagal di terbitkan, #RCERROR!";
            // }
        }else{
            $pg_error = true;
            $error_msg = "Payment info tidak ada, silahkan kontak tim kami!";
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
                    <center><img src='<?PHP echo $gambar_metode; ?>' width='120px'/></center>
                    <p style='margin-top:10px'>Bank : <b><?PHP echo $nama_metode; ?></b><br>
                        Nomor Rekening : <b id='nomor_rekening' data-text="No. Rekening berhasil disalin" data-copy="<?PHP echo str_replace("-","",$nomor_rekening); ?>"><?PHP echo $nomor_rekening; ?></b> <br><span style="text-decoration:underline;color:#00bfff;cursor:pointer" onclick="copyToClipboard('nomor_rekening')">Salin No.Rek</span><br>
                        Atas Nama : -nama akun kamu-
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
    
<?PHP }else{ ?>
    <div class='alert alert-danger mt-3 bayar-id'><?PHP echo $error_msg; ?></div>
<?PHP } ?>