<?PHP
//16 = OVO Instan
//pg = xendit
// require_once("../../config.php");
require_once("phpqrcode/qrlib.php");
// echo QRcode::png('00020101021226690017COM.TELKOMSEL.WWW011893600911002416370202152005140416370290303UME51450015ID.OR.GPNQR.WWW02150000000000000000303UME520454995802ID5908BukaKios6015JAKARTA SELATAN61051295062380115RhmVOWedqNBXiWE0715RhmVOWedqNBXiWE5303360540550000630428BB');exit;
// $api_key = "77e2edcc9b40441200e31dc57dbb8829";
// $topup_id = "433732";
// $api_url = "https://api.bukakios.net/";
$data_post = array(
	"key"=>$api_key,
	"id"=>$topup_id
);
$data_info1 = $app->curl_post("$api_url/get_topup_detail.php",$data_post);
if ($topup_id == 861625){
    echo $data_info1;
}
$data_info = json_decode($data_info1,true);
// echo var_dump($data_info);
if(isset($data_info['status'])){
	$pg_error = false;
	if($data_info['status']=="1"){
		//berhasil
		if(isset($data_info['payment_info'])){
			$payment_info = $data_info['payment_info'];
			// $invoice_url = $payment_info['checkout_url'];
			if(!empty($payment_info)){
				//ada informasi nomor rekening/pembayaran
				if(isset($data_info['amount'])){
					$total_transfer = $data_info['amount'];
                    			$total_transfer_rp = $app->idr($total_transfer);
					   $qr = $payment_info['qr_string'];
					   $file_name = $payment_info['link'];
				}else{
					
					$pg_error = true;
                    $error_msg = "Data Nomor rekeningnya gagal di terbitkan 1, #PYERROR! $payment_info";
                    if ($topup_id == 861625){
                        $error_msg = "Data Nomor rekening gagal di terbitkan 2, #PYERROR! $data_info1";
                    }
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
	// $error_msg = $data_info;
	$error_msg = "Server Api Respon Tidak Valid!!, silahkan kontak tim kami!";
}
?>
	<?php if (!$pg_error) {?>
	<div class='row'>
		<div class="col-12">
			<span class="font-weight-bold">Pembayaran Ke</span>   
			<div class='alert alert-danger '>
				<div class="text-center"><strong>Perhatian :</strong></div> <br/> <div class="text-justify">Mohon untuk melakukan scan barcode <b>QRIS SATU KALI SAJA</b>, bukan berulang kali. Tiket top up hanya dapat memproses hasil scan barcode yang pertama. Jika ingin menambah saldo dengan metode QRIS kembali, silahkan membuat tiket top up yang baru agar pembayaran dapat diproses oleh sistem.</div>
			</div>       
			<div id='info-bank' class="text-center">
				<!-- <div class='col-md-6 col-xs-12 mx-auto'> -->
					<div class='box' style='padding:20px;cursor:default'>
					* Untuk melakukan pembayaran silahkan scan QR ini.
						<img src="<?=$file_name?>" width='100%'/>
						<a class='btn btn-primary btn-block' href="open://https://wv.bukakios.net/info-topup/<?=$file_name?>">Buka di chrome</a>
							<a class='btn btn-success btn-block' href="open://https://wv.bukakios.net/info-topup/download.php?file=<?=$nama_aja?>">Download file</a>
						<br/>
					QR ini bisa di scan untuk :<br/>
						<img width="100%" src="https://assets.bukakios.net/img2/uploads/2020/11/404-qris-scan.png" alt="">
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
	Informasi mengenai QRIS : <br>
	1. 24 Jam nonstop selama QRIS tidak maintenance.<br>
	2. Begitu transfer saldo langsung masuk.<br>
	</div>
	
</div>
	<script src="https://wv.bukakios.net/assets/js/jquery.js"></script>
	<script src="https://wv.bukakios.net/assets/js/sweetalert.min.js"></script>
	<script>
		Swal.fire({
			title: "<b>PENTING</b>",
			html: "<p class='text-justify'>PERHATIAN : Mohon untuk melakukan scan barcode <b>QRIS SATU KALI SAJA</b> Tiket Top UP hanya dapat Memproses Hasil Scan Barcode yang Pertama.</p><img width='300px' src='https://assets.bukakios.net/img2/uploads/2020/10/206-qris.png'>",
			confirmButtonText: "Ok, Paham",
		});
	</script>
<?PHP }else{ ?>
<div class='alert alert-danger mt-3 bayar-id'><?PHP echo $error_msg; ?></div>
<?PHP } ?>