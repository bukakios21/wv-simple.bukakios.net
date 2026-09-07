<?PHP
//11 = ALFAMART
//ALFAMART get info nomor rekening dari api
//pg = winpay
$data_post = array(
	"key"=>$api_key,
	"id"=>$topup_id
);
$kode_pg  = "710516"; //kode paymnet gateway
$data_info = $app->curl_post("$api_url/get_topup_detail.php",$data_post);
$data_info = json_decode($data_info,true);
if(isset($data_info['status'])){
	$pg_error = false;
	if($data_info['status']==1){
		//berhasil
		if(isset($data_info['payment_info'])){
			$payment_info = $data_info['payment_info'];
			$rc = $payment_info['response_code'];
			if($rc=='00'){
				//ada informasi nomor rekening/pembayaran
				/*if(isset($payment_info['data'])){
					$data_payment = $payment_info['data'];
					$nomor_rekening = $data_payment['payment_code'];
					$spi_status_url = $data_payment['spi_status_url'];
				}else{
					$pg_error = true;
					$error_msg = "Data Nomor rekening gagal di terbitkan, #PYERROR!";
				}*/
                $nomor_rekening = $payment_info['payment_code'];
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
<?PHP if(!$pg_error){ ?>
<div id='carabayar' class='text-center'>
	Kode Pembayaran :
	<br>
	<img src='<?PHP echo "$assets_url/img/payment/alfamart.png"; ?>' style='height:25px'/>
	<img src='<?PHP echo "$assets_url/img/payment/alfamidi2.png"; ?>' style='height:25px;margin-left:5px'/>
	<img src='<?PHP echo "$assets_url/img/payment/dandan.png"; ?>' style='height:25px;margin-left:5px'/>
	<br><b style='font-size:21px' id='nomor_rekening' data-text="No. Rekening berhasil disalin" data-copy="<?PHP echo $nomor_rekening; ?>"><?PHP echo $nomor_rekening; ?></b>
	<br><span style="text-decoration:underline;color:#00bfff;cursor:pointer" onclick="copyToClipboard('nomor_rekening')">Salin Kode Pembayaran</span>
	<p style='margin-top:10px'>
	<div class='col-md-6 col-xs-9 mx-auto'>
		Jumlah yang harus dibayar : <br>
		<div class='card' style='padding:20px;padding-bottom:0px'>
			<h3 style='color:green;margin-bottom:0px' id='nominal_transfer' data-text="Jumlah Transfer Berhasil Di Salin" data-copy="<?PHP echo $total_transfer; ?>"><?PHP echo $total_transfer_rp; ?></h3>
			<br><span style="margin-bottom:18px;text-decoration:underline;color:#00bfff;cursor:pointer" onclick="copyToClipboard('nominal_transfer')">Salin Jumlah</span>
		</div>
	</div>
	
</div>
<div class='row'>
<div class='col-md-12'>
	<div id='infonya' class='card' style='margin-bottom:20px;padding:10px'>
	* <b>HARAP BACA</b> : Beritahu ke kasir mau bayar tagihan <b>LINKITA</b>, lalu tunjukan kode pembayaranya &rarr; <?PHP echo $nomor_rekening; ?> , biasanya saat di kasir ada biaya admin dari pihak alfamartnya sebesar Rp2.500
	</div>
</div>
</div>
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
							Kasir Alfamart/Alfamidi/Dandan
						</a>
						<span class='pull-right'><i class='fa fa-angle-down'></i></span>
					</h5>
				</div>
				<div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
					<div class="card-body">
						<div class='row'>
							<div class="col-md-12 col-sm-12">
								<ol style='padding-left:10px;padding-right:10px'>
									<li>Tunjukkan nomor tagihan pembelian <b><?PHP echo $nomor_rekening; ?></b></li>
									<li>Sebutkan untuk pembayaran tagihan <b>&quot;PLASAMALL&quot;</b></li>
									<li>Bayar Sesuai Tagihan di Bukakios Yakni : <b><?PHP echo $total_transfer_rp; ?></b> , biasanya ada biaya tambahan lagi sebesar <b>Rp2.500</b> dari pihak alfamart/alfamidi/dandan.</li>
									<li>Transaksi Anda sudah selesai, simpan struk transaksi sebagai bukti pembayaran</li>
								</ol>
							</div>
						</div>
					</div>
				</div>
			</div> <!-- 1 -->
		</div>
		
		
	</div>
</div>
<?PHP }else{ ?>
<div class='alert alert-danger'><?PHP echo $error_msg; ?></div>
<?PHP } ?>
