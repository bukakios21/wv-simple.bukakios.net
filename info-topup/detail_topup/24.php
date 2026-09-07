<?PHP
$data_post = array(
    "key"=>$api_key,
    "id"=>$topup_id
);
$data_info = $app->curl_post("$api_url/get_topup_detail.php",$data_post);
$data_info = json_decode($data_info,true);
// echo var_dump($data_info);
//if ($user_id == 40408) {
  //  echo var_dump($data_info['payment_info']);
//}
if(isset($data_info['status'])){
    $pg_error = false;
    if($data_info['status']=="1"){
        //berhasil
        if(isset($data_info['status'])){
            $payment_info = $data_info['payment_info'];
            // $invoice_url = $payment_info['checkout_url'];
            if(!empty($payment_info)){
                //ada informasi nomor rekening/pembayaran
                if(isset($payment_info)){
                    //$total_transfer = $payment_info['amount'];
                    //$total_transfer_rp = $app->idr($total_transfer);
                    $checkout_url = $payment_info;
                }else{
                    $pg_error = true;
                    $error_msg = "Data Nomor rekening gagal di terbitkan, #PYERROR! $payment_info";
                }
            }else{
                $pg_error = true;
                $error_msg = "Nomor rekening gagal di terbitkan, #RCERROR!";
            }
        }else{
            $pg_error = true;
            $error_msg = "Payment info tidak ada, silahkan kontak tim kami! => $";
        }
    }else{
        $pg_error = true;
        $error_msg = "Ada Kesalahan -> ".$data_info['error_msg'];
    }
}else{
    $pg_error = true;
    // $error_msg = $data_info;
    $error_msg = "Server Api Respon Tidak Valid!!, silahkan kontak tim kami!";
}
?>
<?php if (!$pg_error) {?>
    <div class='row'>
        <div class="col-12">
            <span class="font-weight-bold">Pembayaran Ke</span>
            <div id='info-bank' class="text-center">
                <!-- <div class='col-md-6 col-xs-12 mx-auto'> -->
                <div class='box' style='padding:20px;cursor:default'>
                    <p>* Untuk melakukan pembayaran LinkAja silahkan klik tombo bayar dibawah ini. Pastikan kamu sudah install aplikasi LinkAja dan sudah melakukan aktivasi LinkAja.
                        <a class='btn btn-primary btn-block' href="open://<?php echo $checkout_url?>">Bayar Melalui LinkAja</a>
                    <p>
                </div>
                <!-- </div> -->
            </div>
        </div>
    </div>
    <!-- </div> dari awal index.php -->
<?php } ?>
    </div>
<?PHP if(!$pg_error){ ?>
    <div id='cara-bayar-id-1'>
        <div class='alert alert-info mt-3 bayar-id'>
            Informasi mengenai LinkAja : <br>
            1. 24 Jam nonstop selama LinkAja tidak maintenance.<br>
            2. Begitu transfer saldo langsung masuk.<br>
        </div>
    </div>


<?PHP }else{ ?>
    <div class='alert alert-danger mt-3 bayar-id'><?PHP echo $error_msg; ?></div>
<?PHP } ?>