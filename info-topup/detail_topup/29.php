<?PHP
//11 = ALFAMART
//ALFAMART get info nomor rekening dari api
//pg = winpay
// echo $metode_id;exit;

/*
$data_post = array(
	"key"=>$api_key,
	"id"=>$topup_id
);
$data_info_s = $app->curl_post("$api_url/get_topup_detail.php",$data_post);
$data_info = json_decode($data_info_s,true);
if(isset($data_info['status'])){
	$pg_error = false;
	if($data_info['status']==1){
		//berhasil
		if(isset($data_info['payment_info'])){
			$payment_info = $data_info['payment_info'];
			if($payment_info['status']=='1'){
				//ada informasi nomor rekening/pembayaran
				$info = $payment_info['msg'];
			}else{
				$pg_error = true;
				$error_msg = $payment_info['error_msg'];
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
	$error_msg = "Server Api Respon Tidak Valid!!, silahkan kontak tim kami!";
}
?>
<?PHP if(!$pg_error){ */

$info = "Penarikan komisi kamu sedang di proses oleh operator. Mohon menunggu"
?>

<div id='panduanbayar'>
	<div class="pl-16 pr-16 pt-24">
		<div class="">
			<h5>Redeem Komisi BukaKios</h5>
		</div>
		
		<div class="card alert alert-info">
            <b><?php echo $info?></b>
        </div>
		
		
	</div>
</div>

<?PHP /* }else{ ?>
<div class='alert alert-danger'><?PHP echo $error_msg; ?></div>
<?PHP } ?> */