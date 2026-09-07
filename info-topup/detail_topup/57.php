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
					<center><img src='<?PHP echo "$assets_url/img2/uploads/2021/09/117-bsi.png"; ?>' width='120px'/></center>
						<p>No Va BSI</p>
						Nomor Rekening : <b id='nomor_rekening' data-text="No. Rekening berhasil disalin" data-copy="<?PHP echo str_replace("-","",$checkout_url); ?>"><?PHP echo $checkout_url; ?></b> <br><span style="text-decoration:underline;color:#00bfff;cursor:pointer" onclick="copyToClipboard('nomor_rekening')">Salin No.Rek</span>
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
	Informasi mengenai BSI : <br>
	1. 24 Jam nonstop selama BSI tidak maintenance.<br>
	2. Begitu transfer saldo langsung masuk.<br>
	</div>
</div>	
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
							ATM 
						</a>
						<span class='pull-right'><i class='fa fa-angle-down'></i></span>
					</h5>
				</div>
				<div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
					<div class="card-body">
						<div class='row'>
							<div class="col-md-12 col-sm-12">
								<ol style='padding-left:10px;padding-right:10px'>
									<li>Pilih Menu Utama</li>
									<li>Pilih "Pembayaran / Pembelian"</li>
									<li>Pilih "Menu Akademik"</li>
									<li>Masukkan Kode Institusi <b>1062</b> + Nomor Virtual Account kamu<br>
										<small>Format: <b>1062<?PHP echo $nomor_rekening; ?></b> (1062 = Kode Institusi)</small>
									</li>
									<li>Pilih "Benar"</li>
									<li>Periksa informasi data transaksi yang tampil, pastikan nama tujuan dan jumlah pembayaran sesuai dengan tagihan kamu</li>
									<li>Jika data sudah benar, pilih "Benar"</li>
									<li>Bukti transaksi akan keluar jika pembayaran berhasil</li>
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
							Mobile Banking
						</a>
						<span class='pull-right'><i class='fa fa-angle-down'></i></span>
					</h5>
				</div>
				<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
					<div class="card-body">
						<div class='row'>
							<div class="col-md-12 col-sm-12">
								<ol style='padding-left:10px;padding-right:10px'>
									<li>Masuk ke New Livin by Mandiri</li>
									<li>Pilih Menu "Bayar dan Beli"</li>
									<li>Pilih "Lembaga"</li>
									<li>Masukkan Kode LinkQu <b>1062</b></li>
									<li>Masukkan Nomor Bayar <b><?PHP echo $nomor_rekening; ?></b></li>
									<li>Klik "Check Bill"</li>
									<li>Masukkan PIN</li>
									<li>Pembayaran selesai</li>
								</ol>
							</div>
						</div>
					</div>
				</div>
			</div> <!-- 2 -->

			<div class="card" style='margin-top:0px'>
				<div style='cursor:pointer' class="card-header" id="headingFour" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
					<h5 class="mb-0">
						<a>
							Bank Lain
						</a>
						<span class='pull-right'><i class='fa fa-angle-down'></i></span>
					</h5>
				</div>
				<div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
					<div class="card-body">
						<div class='row'>
							<div class="col-md-12 col-sm-12">
								<ol style='padding-left:10px;padding-right:10px'>
									<li>Masukkan kartu ATM dan PIN kamu</li>
									<li>Pilih "Bayar / Beli"</li>
									<li>Pilih "Lainnya"</li>
									<li>Pilih "Bank Transfer"</li>
									<li>Pilih BSI Bank (451)</li>
									<li>Masukkan kode <b>9001062</b> + Nomor Virtual Account kamu<br>
										<small>Format: <b>9001062<?PHP echo $nomor_rekening; ?></b></small>
									</li>
									<li>Masukkan jumlah transfer sesuai dengan tagihan kamu. Jumlah yang berbeda tidak bisa diproses</li>
									<li>Lakukan Pembayaran</li>
								</ol>
							</div>
						</div>
					</div>
				</div>
			</div> <!-- 2 -->
			
		</div>
		
		
	</div>
</div>								
<?PHP }else{ ?>
<div class='alert alert-danger mt-3 bayar-id'><?PHP echo $error_msg; ?></div>
<?PHP } ?>