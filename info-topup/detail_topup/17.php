<?PHP
//17 = mandiri click pay
//mandiri click pay get info nomor rekening dari api
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
			$rc = $payment_info['rc'];
			if($rc=='00'){
				//ada informasi nomor rekening/pembayaran
				if(isset($payment_info['data'])){
					$data_payment = $payment_info['data'];
					$nomor_rekening = $data_payment['payment_code'];
					$spi_status_url = $data_payment['spi_status_url'];
					$spi_url_payment = $data_payment['url_payment'];
				}else{
					$pg_error = true;
					$error_msg = "Data Nomor rekening gagal di terbitkan, #PYERROR!";
				}
			}else{
				$pg_error = true;
				$error_msg = "Nomor rekening gagal di terbitkan, #RCERROR!";
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
	<?php if (!$pg_error) {?>
	<div class='row'>
		<div class="col-12">
			<span class="font-weight-bold">Transfer Ke</span>          
			<div id='info-bank' class="text-center">
				<!-- <div class='col-md-6 col-xs-12 mx-auto'> -->
					<div class='box' style='padding:20px;cursor:default'>
						<center><img src='<?PHP echo $gambar_metode; ?>' width='120px'/></center>
						<p>* Untuk melakukan pembayaran klik tombol di bawah ini.
				<p>
				<a target='_blank' class='btn btn-success btn-block' href='<?PHP echo $open_url; ?><?PHP echo "$spi_url_payment"; ?>'>Bayar Sekarang</a>
					</div>
				<!-- </div> -->
			</div>   
		</div>
	</div>
	<?php } ?>
<!-- </div> dari awal index.php -->
</div>
<?PHP if(!$pg_error){ ?>
<div id='cara-bayar-id-1'>
	<div class='alert alert-info mt-3 bayar-id'>
	Ini merupakan metode pembayaran Mandiri Click Pay kelebihan menggunakan metode ini : <br>
	1. 24 Jam nonstop selama mandiri tidak maintenance.<br>
	2. Begitu transfer saldo langsung masuk.<br>
	</div>
	
</div>

										
<?PHP }else{ ?>
<div class='alert alert-danger mt-3 bayar-id'><?PHP echo $error_msg; ?></div>
<?PHP } ?>