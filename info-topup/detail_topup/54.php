<?PHP
//16 = OVO Instan
//pg = xendit
// require_once("../../config.php");
// require_once("phpqrcode/qrlib.php");
// echo QRcode::png('00020101021226690017COM.TELKOMSEL.WWW011893600911002416370202152005140416370290303UME51450015ID.OR.GPNQR.WWW02150000000000000000303UME520454995802ID5908BukaKios6015JAKARTA SELATAN61051295062380115RhmVOWedqNBXiWE0715RhmVOWedqNBXiWE5303360540550000630428BB');exit;
// $api_key = "77e2edcc9b40441200e31dc57dbb8829";
// $topup_id = "433732";
// $api_url = "https://api.bukakios.net/";
$data_post = array(
	"key"=>$api_key,
	"id"=>$topup_id
);
$data_info = $app->curl_post("$api_url/get_topup_detail.php",$data_post);
$data_info = json_decode($data_info,true);
// echo var_dump($data_info);
if(isset($data_info['status'])){
	$pg_error = false;
	if($data_info['status']=="1"){
		//berhasil
		if(isset($data_info['payment_info'])){
			$payment_info = $data_info['payment_info'];
			$checkout_url = $payment_info;
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
						<p>No Va NEO</p>
						<h5 class=''><?php echo $checkout_url ?></h5>
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
	Informasi mengenai NEO : <br>
	1. 24 Jam nonstop selama NEO tidak maintenance.<br>
	2. Begitu transfer saldo langsung masuk.<br>
	</div>
</div>									
<?PHP }else{ ?>
<div class='alert alert-danger mt-3 bayar-id'><?PHP echo $error_msg; ?></div>
<?PHP } ?>