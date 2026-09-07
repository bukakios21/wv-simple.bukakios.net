<?PHP
//7 = MANDIRIVA
//MANDIRI VA get info nomor rekening dari api
//pg = winpay
$data_post = array(
	"key"=>$api_key,
	"id"=>$topup_id
);
$kode_pg = "88898"; //kode perusahaan payment gateway
$data_info = $app->curl_post("$api_url/get_topup_detail.php",$data_post);
$data_info = json_decode($data_info,true);
if(isset($data_info['status'])){
	$pg_error = false;
	if($data_info['status']==1){
		//berhasil
		if(isset($data_info['payment_info'])){
			$payment_info = $data_info['payment_info'];
			$rc = $payment_info['virtual_account'];
			if($rc!=null){
				//ada informasi nomor rekening/pembayaran
				if(isset($payment_info['virtual_account'])){
					$nomor_rekening = $rc;
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
						<center><img src='<?PHP echo "$assets_url/img/payment/mandiri.png"; ?>' width='120px'/></center>
						<b>Instutusi Multipayment : I-Pay (70017)</b><br>
                        <p style='margin-top:10px'>Bank : <b><?PHP echo $nama_metode; ?></b><br>
						Nomor Rekening : <b id='nomor_rekening' data-text="No. Rekening berhasil disalin" data-copy="<?PHP echo str_replace("-","",$nomor_rekening); ?>"><?PHP echo $nomor_rekening; ?></b> <br><span style="text-decoration:underline;color:#00bfff;cursor:pointer" onclick="copyToClipboard('nomor_rekening')">Salin No.Rek</span><br>
Atas Nama : Nama Kamu - BukaKios
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
							ATM Mandiri 
						</a>
						<span class='pull-right'><i class='fa fa-angle-down'></i></span>
					</h5>
				</div>
				<div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
					<div class="card-body">
						<div class='row'>
							<div class="col-md-12 col-sm-12">
								<ol style='padding-left:10px;padding-right:10px'>
									<li>Masukkan kartu ATM dan PIN</li>
									<li>Pilih menu &quot;Pembayaran&quot;</li>
									<li>Pilih menu &quot;Lainnya&quot;, hingga menemukan menu &quot;Multifinance&quot;</li>
									<li>Masukkan Kode Biller I-Pay (70017) (<?PHP echo $kode_pg; ?>), lalu pilih Benar</li>
									<li>Masukkan &quot;Nomor Virtual Account : <b><?PHP echo $nomor_rekening; ?></b>&quot; I-Pay (70017), lalu pilih tombol Benar</li>
									<li>Masukkan Angka &quot;1&quot; untuk memilih tagihan, lalu pilih tombol Ya</li>
									<li>Akan muncul konfirmasi pembayaran, lalu pilih tombol Ya</li>
									<li>Simpan struk sebagai bukti pembayaran Anda</li>
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
							Mandiri Internet Banking / Mandiri Online 
						</a>
						<span class='pull-right'><i class='fa fa-angle-down'></i></span>
					</h5>
				</div>
				<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
					<div class="card-body">
						<div class='row'>
							<div class="col-md-12 col-sm-12">
								<ol style='padding-left:10px;padding-right:10px'>
									<li>Login Mandiri Online dengan memasukkan Username dan Password</li>
									<li>Pilih menu &quot;Pembayaran&quot;</li>
									<li>Pilih menu &quot;Multifinance&quot;</li>
									<li>Pilih penyedia jasa &quot;I-Pay&quot;</li>
									<li>Masukkan &quot;Nomor Virtual Account : <b><?PHP echo $nomor_rekening; ?></b>&quot; dan &quot;Nominal&quot; yang akan dibayarkan, lalu pilih Lanjut</li>
									<li>Setelah muncul tagihan, pilih Konfirmasi</li>
									<li>Masukkan PIN / Challenge Code Token</li>
									<li>Transaksi selesai, simpan bukti bayar Anda</li>
								</ol>
								
								<hr />Jangan gunakan fitur &quot;Simpan Daftar Transfer&quot; untuk pembayaran melalui Internet Banking karena dapat mengganggu proses pembayaran berikutnya.<br />
								<br />
								Untuk menghapus daftar transfer tersimpan ikuti langkah berikut:
								<ol style='padding-left:10px;padding-right:10px'>
									<li>Login Mandiri Online</li>
									<li>Pilih ke menu Pembayaran</li>
									<li>Pilih menu Daftar Pembayaran</li>
									<li>Pilih pada pembayaran yang tersimpan, lalu pilih menu untuk hapus</li>
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