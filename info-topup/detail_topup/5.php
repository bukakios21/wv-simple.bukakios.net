<?PHP
//5 = briva
//briva get info nomor rekening dari api
//pg = winpay
$data_info=callTopupApi($topup_id,$user_jwt);
$data_info = json_decode($data_info,true);
// $data_post = array(
// 	"key"=>$api_key,
// 	"id"=>$topup_id
// );
// $data_info = $app->curl_post("$api_url/get_topup_detail.php",$data_post);
// $data_info = json_decode($data_info,true);
//echo var_dump($data_info);
//exit;
if(isset($data_info['status'])){
	$pg_error = false;
	if($data_info['status']==1){
		//berhasil
		if(isset($data_info['data']['data'])){
            $payment_info = $data_info['data']['data'];
            // $rc = $payment_info['attributes']['paymentMethod']['instructions']['accountNo'];
			$rc = $payment_info;
			if(isset($rc)){
				//ada informasi nomor rekening/pembayaran
				$nomor_rekening = $rc;
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
						<center><img src='<?PHP echo "$assets_url/img/payment/briva.png"; ?>' width='120px'/></center>
						<br>
						Nomor Rekening : <b id='nomor_rekening' data-text="No. Rekening berhasil disalin" data-copy="<?PHP echo str_replace("-","",$nomor_rekening); ?>"><?PHP echo $nomor_rekening; ?></b> <br><span style="text-decoration:underline;color:#00bfff;cursor:pointer" onclick="copyToClipboard('nomor_rekening')">Salin No.Rek</span>
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
							ATM BRI
						</a>
						<span class='pull-right'><i class='fa fa-angle-down'></i></span>
					</h5>
				</div>
				<div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
					<div class="card-body">
						<div class='row'>
							<div class="col-md-12 col-sm-12">
								<ol style='padding-left:10px;padding-right:10px'>
									<li class="mt-10 mb-10">Masukkan Kartu Debit BRI dan PIN Anda</li>
									<li class="mt-10 mb-10">Pilih menu Transaksi Lain &gt; Pembayaran &gt; Lainnya &gt; BRIVA</li>
									<li class="mt-10 mb-10">Masukkan Nomor Rekening BRIVA : <b><?PHP echo $nomor_rekening; ?></b></li>
									<li class="mt-10 mb-10">Di halaman konfirmasi, pastikan detil pembayaran sudah sesuai seperti Nomor BRIVA, Nama Pelanggan dan Jumlah Pembayaran</li>
									<li class="mt-10 mb-10">Ikuti instruksi untuk menyelesaikan transaksi</li>
									<li class="mt-10 mb-10">Simpan struk transaksi sebagai bukti pembayaran</li>
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
							Mobile Banking BRI
						</a>
						<span class='pull-right'><i class='fa fa-angle-down'></i></span>
					</h5>
				</div>
				<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
					<div class="card-body">
						<div class='row'>
							<div class="col-md-12 col-sm-12">
								<ol style='padding-left:10px;padding-right:10px'>
									<li class="mt-10 mb-10">Masukkan Kartu Debit BRI dan PIN Anda</li>
									<li class="mt-10 mb-10">Pilih menu Transaksi Lain &gt; Pembayaran &gt; Lainnya &gt; BRIVA</li>
									<li class="mt-10 mb-10">Masukkan Nomor Rekening BRIVA : <b><?PHP echo $nomor_rekening; ?></b></li>
									<li class="mt-10 mb-10">Di halaman konfirmasi, pastikan detil pembayaran sudah sesuai seperti Nomor BRIVA, Nama Pelanggan dan Jumlah Pembayaran</li>
									<li class="mt-10 mb-10">Ikuti instruksi untuk menyelesaikan transaksi</li>
									<li class="mt-10 mb-10">Simpan struk transaksi sebagai bukti pembayaran</li>
								</ol>
							</div>
						</div>
					</div>
				</div>
			</div> <!-- 2 -->
			
			<div class="card" style='margin-top:0px'>
				<div style='cursor:pointer' class="card-header" id="headingThree" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
					<h5 class="mb-0">
						<a>
							Internet Banking BRI
						</a>
						<span class='pull-right'><i class='fa fa-angle-down'></i></span>
					</h5>
				</div>
				<div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
					<div class="card-body">
						<div class='row'>
							<div class="col-md-12 col-sm-12">
								<ol style='padding-left:10px;padding-right:10px'>
									<li class="mt-10 mb-10">Login pada alamat Internet Banking BRI (<a href="https://ib.bri.co.id/ib-bri/Login.html" target="_blank">https://ib.bri.co.id/ib-bri/Login.html</a>)</li>
									<li class="mt-10 mb-10">Pilih menu Pembayaran Tagihan &gt; Pembayaran &gt; BRIVA </li>
									<li class="mt-10 mb-10">Pada kolom kode bayar, Masukkan Nomor Rekening BRIVA : <b><?PHP echo $nomor_rekening; ?></b></li>
									<li class="mt-10 mb-10">Di halaman konfirmasi, pastikan detil pembayaran sudah sesuai seperti Nomor BRIVA, Nama Pelanggan dan Jumlah Pembayaran</li>
									<li class="mt-10 mb-10">Masukkan <span class="italic"> password</span> dan mToken</li>
									<li class="mt-10 mb-10">Cetak/simpan struk pembayaran BRIVA sebagai bukti pembayaran</li>
								</ol>
							</div>
						</div>
					</div>
				</div>
			</div> <!-- 3 -->
			
			<div class="card" style='margin-top:0px'>
				<div style='cursor:pointer' class="card-header" id="headingFour" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
					<h5 class="mb-0">
						<a>
							Mini ATM/EDC BRI
						</a>
						<span class='pull-right'><i class='fa fa-angle-down'></i></span>
					</h5>
				</div>
				<div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
					<div class="card-body">
						<div class='row'>
							<div class="col-md-12 col-sm-12">
								<ol style='padding-left:10px;padding-right:10px'>
									<li class="mt-10 mb-10">Pilih menu Mini ATM &gt; Pembayaran &gt; BRIVA</li>
									<li class="mt-10 mb-10"> <span class="italic">Swipe</span> Kartu Debit BRI Anda </li>
									<li class="mt-10 mb-10">Masukkan Nomor Rekening BRIVA : <b><?PHP echo $nomor_rekening; ?></b></li>
									<li class="mt-10 mb-10">Masukkan PIN</li>
									<li class="mt-10 mb-10">Di halaman konfirmasi, pastikan detil pembayaran sudah sesuai seperti Nomor BRIVA, Nama Pelanggan dan Jumlah Pembayaran</li>
									<li class="mt-10 mb-10">Simpan struk transaksi sebagai bukti pembayaran</li>
								</ol>
							</div>
						</div>
					</div>
				</div>
			</div> <!-- 4 -->
			
			<div class="card" style='margin-top:0px'>
				<div style='cursor:pointer' class="card-header" id="headingFive" data-toggle="collapse" data-target="#collapseFive" aria-expanded="true" aria-controls="collapseFive">
					<h5 class="mb-0">
						<a>
							Kantor Bank BRI
						</a>
						<span class='pull-right'><i class='fa fa-angle-down'></i></span>
					</h5>
				</div>
				<div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
					<div class="card-body">
						<div class='row'>
							<div class="col-md-12 col-sm-12">
								<ol style='padding-left:10px;padding-right:10px'>
									<li class="mt-10 mb-10">Ambil nomor antrian transaksi Teller dan isi slip setoran</li>
									<li class="mt-10 mb-10">Serahkan slip dan jumlah setoran kepada Teller BRI</li>
									<li class="mt-10 mb-10">Teller BRI akan melakukan validasi transaksi</li>
									<li class="mt-10 mb-10">Simpan slip setoran hasil validasi sebagai bukti pembayaran</li>
								</ol>
							</div>
						</div>
					</div>
				</div>
			</div> <!-- 5 -->
			
			<div class="card" style='margin-top:0px'>
				<div style='cursor:pointer' class="card-header" id="headingSix" data-toggle="collapse" data-target="#collapseSix" aria-expanded="true" aria-controls="collapseSix">
					<h5 class="mb-0">
						<a>
							ATM Bank Lain
						</a>
						<span class='pull-right'><i class='fa fa-angle-down'></i></span>
					</h5>
				</div>
				<div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
					<div class="card-body">
						<div class='row'>
							<div class="col-md-12 col-sm-12">
								<ol style='padding-left:10px;padding-right:10px'>
									<li class="mt-10 mb-10">Masukkan Kartu Debit dan PIN Anda</li>
									<li class="mt-10 mb-10">Pilih menu Transaksi Lainnya &gt; Transfer &gt; Ke Rek Bank Lain</li>
									<li class="mt-10 mb-10">Masukkan kode bank BRI (002) kemudian Masukkan Nomor Rekening BRIVA : <b><?PHP echo $nomor_rekening; ?></b></li>
									<li class="mt-10 mb-10">Ikuti instruksi untuk menyelesaikan transaksi</li>
									<li class="mt-10 mb-10">Simpan struk transaksi sebagai bukti pembayaran</li>
								</ol>
							</div>
						</div>
					</div>
				</div>
			</div> <!-- 6 -->
			
		</div>
		
		
	</div>
</div>
<?PHP }else{ ?>
<div class='alert alert-danger mt-3 bayar-id'><?PHP echo $error_msg; ?></div>
<?PHP } ?>