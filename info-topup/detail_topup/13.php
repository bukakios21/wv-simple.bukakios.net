<?PHP
//6 = BNIVA
//BNIVA get info nomor rekening dari api
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
			$rc = $payment_info['vaNumber'];
			if($rc!=null){
				//ada informasi nomor rekening/pembayaran
				if(isset($payment_info['vaNumber'])){
					$nomor_rekening = $payment_info['vaNumber'];
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
	<?php if (!$pg_error) { ?>
	<div class='row'>
		<div class="col-12">
			<span class="font-weight-bold">Transfer Ke</span>          
			<div id='info-bank' class="text-center">
				<div class='box' style='padding:20px;cursor:default'>
					<center>
						<img src='<?PHP echo "$assets_url/img/payment/bni.png"; ?>' style='height:25px'/>
					</center>
					<br><b style='font-size:21px' id='nomor_rekening' data-text="No. Rekening berhasil disalin" data-copy="<?PHP echo $nomor_rekening; ?>"><?PHP echo $nomor_rekening; ?></b>
					<br><span style="text-decoration:underline;color:#00bfff;cursor:pointer" onclick="copyToClipboard('nomor_rekening')">Salin No.Rek</span>
				</div>
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
							Cabang atau Outlet BNI (Teller) 
						</a>
						<span class='pull-right'><i class='fa fa-angle-down'></i></span>
					</h5>
				</div>
				<div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
					<div class="card-body">
						<div class='row'>
							<div class="col-md-12 col-sm-12">
								<ol style='padding-left:10px;padding-right:10px'>
									<li>Kunjungi Kantor Cabang/outlet BNI terdekat.</li>
									<li>Informasikan kepada Teller, bahwa ingin melakukan pembayaran &quot;Virtual Account Billing&quot;.</li>
									<li>Serahkan nomor Virtual Account Anda kepada Teller.</li>
									<li>Teller melakukan konfirmasi kepada Anda.</li>
									<li>Teller memproses Transaksi.</li>
									<li>Apabila transaksi Sukses anda akan menerima bukti pembayaran dari Teller tersebut.</li>
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
							ATM BNI 
						</a>
						<span class='pull-right'><i class='fa fa-angle-down'></i></span>
					</h5>
				</div>
				<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
					<div class="card-body">
						<div class='row'>
							<div class="col-md-12 col-sm-12">
								<ol style='padding-left:10px;padding-right:10px'>
									<li>Masukkan Kartu Anda.</li>
									<li>Pilih Bahasa.</li>
									<li>Masukkan PIN ATM Anda.</li>
									<li>Pilih &quot;Menu Lainnya&quot;.</li>
									<li>Pilih &quot;Transfer&quot;.</li>
									<li>Pilih Jenis rekening yang akan Anda gunakan (Contoh: &quot;Dari Rekening Tabungan&quot;).</li>
									<li>Pilih &quot;Virtual Account Billing&quot;.</li>
									<li>Masukkan nomor Virtual Account Anda (contoh: <?PHP echo $nomor_rekening; ?>).</li>
									<li>Tagihan yang harus dibayarkan akan muncul pada layar konfirmasi.</li>
									<li>Konfirmasi, apabila telah sesuai, lanjutkan transaksi.</li>
									<li>Transaksi telah selesai.</li>
								</ol>
							</div>
						</div>
					</div>
				</div>
			</div> <!-- 2 -->
			
			<div class="card" style='margin-top:0px'>
				<div style='cursor:pointer' class="card-header" id="headingSix" data-toggle="collapse" data-target="#collapseSix" aria-expanded="true" aria-controls="collapseSix">
					<h5 class="mb-0">
						<a>
							ATM Bersama
						</a>
						<span class='pull-right'><i class='fa fa-angle-down'></i></span>
					</h5>
				</div>
				<div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
					<div class="card-body">
						<div class='row'>
							<div class="col-md-12 col-sm-12">
								<ol style='padding-left:10px;padding-right:10px'>
									<li>Masukkan kartu ke mesin ATM Bersama.</li>
									<li>Pilih &quot;Transaksi Lainnya&quot;.</li>
									<li>Pilih menu &quot;Transfer&quot;.</li>
									<li>Pilih &quot;Transfer ke Bank Lain&quot;.</li>
									<li>Masukkan kode bank BNI (009) dan 16 Digit Nomor Virtual Account (contoh: <?PHP echo $nomor_rekening; ?>).</li>
									<li>Masukkan nominal transfer sesuai tagihan atau kewajiban Anda. Nominal yang berbeda tidak dapat diproses.</li>
									<li>Konfirmasi rincian Anda akan tampil di layar, cek dan tekan &#39;Ya&#39; untuk melanjutkan.</li>
									<li>Transaksi Berhasil.</li>
								</ol>
							</div>
						</div>
					</div>
				</div>
			</div> <!-- 6 -->
			
			<div class="card" style='margin-top:0px'>
				<div style='cursor:pointer' class="card-header" id="headingSeven" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="true" aria-controls="collapseSeven">
					<h5 class="mb-0">
						<a>
							ATM/Transfer Bank Lain
						</a>
						<span class='pull-right'><i class='fa fa-angle-down'></i></span>
					</h5>
				</div>
				<div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion">
					<div class="card-body">
						<div class='row'>
							<div class="col-md-12 col-sm-12">
								<ol style='padding-left:10px;padding-right:10px'>
									<li>Pilih menu &quot;Transfer antar bank&quot; atau &quot;Transfer online antarbank&quot;.</li>
									<li>Masukkan kode bank BNI (009) atau pilih bank yang dituju yaitu BNI.</li>
									<li>Masukan 16 Digit Nomor Virtual Account pada kolom rekening tujuan, (contoh: <?PHP echo $nomor_rekening; ?>).</li>
									<li>Masukkan nominal transfer sesuai tagihan atau kewajiban Anda. Nominal yang berbeda tidak dapat diproses.</li>
									<li>Masukkan jumlah pembayaran : <?PHP echo $total_transfer_rp; ?>.</li>
									<li>Konfirmasi rincian Anda akan tampil di layar, cek dan apabila sudah sesuai silahkan lanjutkan transaksi sampai dengan selesai.</li>
									<li>Transaksi Berhasil.</li>
								</ol>
							</div>
						</div>
					</div>
				</div>
			</div> <!-- 7 -->
			
		</div>
		
		
	</div>
</div>
<?PHP }else{ ?>
<div class='alert alert-danger mt-3 bayar-id'><?PHP echo $error_msg; ?></div>
<?PHP } ?>