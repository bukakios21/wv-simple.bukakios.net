<?PHP
// $data_post = array(
//     "key"=>$api_key,
//     "id"=>$topup_id
// );
// $data_info = $app->curl_post("$api_url/get_topup_detail.php",$data_post);
// $data_info = json_decode($data_info,true);
$data_info=callTopupApi($topup_id,$user_jwt);
$data_info = json_decode($data_info,true);
// echo var_dump($data_info);
if(isset($data_info['status'])){
    $pg_error = false;
    if($data_info['status']=="1"){
        //berhasil
        if(isset($data_info['data']['data'])){
            $payment_info = $data_info['data']['data'];
            // $invoice_url = $payment_info['checkout_url'];
            if(!empty($payment_info)){
                //ada informasi nomor rekening/pembayaran
                $checkout_url = $payment_info;
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
            <span class="font-weight-bold">Pembayaran Ke -</span>
            <div id='info-bank' class="text-center">
                <!-- <div class='col-md-6 col-xs-12 mx-auto'> -->
                <div class='box' style='padding:20px;cursor:default'>
                    <p>* Untuk melakukan pembayaran ShopeePay silahkan klik tombo bayar dibawah ini. Pastikan kamu sudah install aplikasi Shopee dan sudah melakukan aktivasi ShopeePay.
                        <a class='btn btn-primary btn-block' href="open://<?php echo $checkout_url?>">Bayar Melalui Shopee</a>
                       
                    <p>

                    <div>
                        <p>*Salin link bayar, paste di google chrome jika tombol di atas tidak berfungsi

                        <button class='btn btn-success btn-block'  onclick="copyText('<?php echo $checkout_url?>')" >Salin Link</button>
                        </p>
                   
                    </div>
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
            Informasi mengenai ShopeePay : <br>
            1. 24 Jam nonstop selama ShopeePay tidak maintenance.<br>
            2. Begitu transfer saldo langsung masuk.<br>
        </div>
    </div>


<?PHP }else{ ?>
    <div class='alert alert-danger mt-3 bayar-id'><?PHP echo $error_msg; ?></div>
<?PHP } ?>