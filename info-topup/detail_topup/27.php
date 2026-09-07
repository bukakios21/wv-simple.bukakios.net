<?PHP
//16 = OVO Instan
//pg = xendit
// require_once("../../config.php");
// require_once("phpqrcode/qrlib.php");
// echo QRcode::png('00020101021226690017COM.TELKOMSEL.WWW011893600911002416370202152005140416370290303UME51450015ID.OR.GPNQR.WWW02150000000000000000303UME520454995802ID5908BukaKios6015JAKARTA SELATAN61051295062380115RhmVOWedqNBXiWE0715RhmVOWedqNBXiWE5303360540550000630428BB');exit;
// $api_key = "77e2edcc9b40441200e31dc57dbb8829";
// $topup_id = "433732";
// $api_url = "https://api.bukakios.net/";
// $data_post = array(
// 	"key"=>$api_key,
// 	"id"=>$topup_id
// );
// $data_info = $app->curl_post("$api_url/get_topup_detail.php",$data_post);
// $data_info = json_decode($data_info,true);
$data_info=callTopupApi($topup_id,$user_jwt);
$data_info = json_decode($data_info,true);
if ($user_id == "39958"){
	// echo json_encode($data_info);
}
if(isset($data_info['status'])){
	$pg_error = false;
	if($data_info['status']=="1"){
		//berhasil
		if(isset($data_info['data'])){
			$payment_info = $data_info['data']['data'];
			$checkout_url = $payment_info;
			// cek apakah transaksi gagal dari sisi payment gateway
			if(isset($data_info['data']['status']) && strtolower($data_info['data']['status']) == "gagal"){
				$pg_error = true;
				$error_msg = "Transaksi gagal diproses.";
			}
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
						* Untuk melakukan pembayaran silahkan buka link dibawah ini.
						<img src="<?=$file_name?>" width='100%'/>
						<a class='btn btn-primary btn-block' href="open://<?=$checkout_url?>">Buka di browser</a>
					</div>

					<div>
                        <p>*Salin link bayar, paste di google chrome jika tombol di atas tidak berfungsi

                        <button class='btn btn-success btn-block'  onclick="copyText('<?php echo $checkout_url?>')" >Salin Link</button>
                        </p>
                   
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
	Informasi mengenai DANA : <br>
	1. 24 Jam nonstop selama DANA tidak maintenance.<br>
	2. Begitu transfer saldo langsung masuk.<br>
	</div>
</div>
<?PHP }else{ ?>
<div class='alert alert-danger mt-3 bayar-id'>
	<strong>⚠️ Transaksi Gagal</strong><br>
	<?PHP echo $error_msg; ?><br><br>
	Silahkan hubungi <strong>Customer Service</strong> kami untuk bantuan lebih lanjut.
</div>
<?PHP } ?>