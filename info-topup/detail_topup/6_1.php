<?PHP
//6 = BNIVA
//BNIVA get info nomor rekening dari api
//pg = winpay
$data_post = array(
	"key"=>$api_key,
	"id"=>$topup_id
);
$data_info_api = $app->curl_post("$api_url/get_topup_detail.php",$data_post);
$data_info = json_decode($data_info_api,true);
if(isset($data_info['status'])){
	$pg_error = false;
	if($data_info['status']==1){
        //berhasil
		if(isset($data_info['payment_info']['data']['response'])){
            $payment_info = $data_info['payment_info']['data']['response'];
           
            // $rc = $payment_info['attributes']['paymentMethod']['instructions']['accountNo'];
            // $rc = $payment_info['virtual_account'];
            //$py_url = $payment_info['paymentUrl'];
            $rc =$payment_info['account_number'];
			if($rc!=null){
				//ada informasi nomor rekening/pembayaran
				$nomor_rekening = $rc;
			}else{
				$pg_error = true;
				$error_msg = "Nomor rekening gagal di terbitkan, #RCERROR!";
			}
		}else{
			$pg_error = true;
			$error_msg = "Payment info tidak ada, silahkan kontak tim kami! ";
		}
	}else{
		$pg_error = true;
		$error_msg = "Ada Kesalahan -> ".$data_info['error_msg'];
	}
}else{
	$pg_error = true;
	$error_msg = "Server Api Respon Tidak Valid!!, silahkan kontak tim kami! ";
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
						<p style='margin-top:10px'>Bank : <b><?PHP echo $nama_metode; ?></b><br>
						Nomor Rekening : <b id='nomor_rekening' data-text="No. Rekening berhasil disalin" data-copy="<?PHP echo str_replace("-","",$nomor_rekening); ?>"><?PHP echo $nomor_rekening; ?></b> <br><span style="text-decoration:underline;color:#00bfff;cursor:pointer" onclick="copyToClipboard('nomor_rekening')">Salin No.Rek</span><br>
                        Atas Nama : BukaKios / nama akun kamu
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
		
        <div class="card alert alert-success">
            <!-- <a class="btn btn-block btn-primary" href="<?php echo $py_url?>">Lihat panduan pembayaran</a> -->
          
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
				<div style='cursor:pointer' class="card-header" id="headingThree" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
					<h5 class="mb-0">
						<a>
							iBank Personal BNI 
						</a>
						<span class='pull-right'><i class='fa fa-angle-down'></i></span>
					</h5>
				</div>
				<div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
					<div class="card-body">
						<div class='row'>
							<div class="col-md-12 col-sm-12">
								<ol style='padding-left:10px;padding-right:10px'>
									<li>Ketik alamat https://ibank.bni.co.id kemudian klik &quot;Enter&quot;.</li>
									<li>Masukkan User ID dan Password.</li>
									<li>Pilih menu &quot;Transfer&quot;.</li>
									<li>Pilih &quot;Virtual Account Billing&quot;.</li>
									<li>Kemudian masukan nomor Virtual Account Anda (contoh: <?PHP echo $nomor_rekening; ?>) yang hendak dibayarkan. Lalu pilih rekening debet yang akan digunakan. Kemudian tekan &quot;lanjut&quot;.</li>
									<li>Kemudian tagihan yang harus dibayarkan akan muncul pada layar konfirmasi.</li>
									<li>Masukkan Kode Otentikasi Token.</li>
									<li>Pembayaran Anda Telah Berhasil.</li>
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
							Mobile Banking BNI 
						</a>
						<span class='pull-right'><i class='fa fa-angle-down'></i></span>
					</h5>
				</div>
				<div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
					<div class="card-body">
						<div class='row'>
							<div class="col-md-12 col-sm-12">
								<ol style='padding-left:10px;padding-right:10px'>
									<li>Akses BNI Mobile Banking dari handphone kemudian masukkan user ID dan password.</li>
									<li>Pilih menu &quot;Transfer&quot;.</li>
									<li>Pilih menu &quot;Virtual Account Billing&quot; kemudian pilih rekening debet.</li>
									<li>Masukkan nomor Virtual Account Anda (contoh: <?PHP echo $nomor_rekening; ?>) pada menu &quot;input baru&quot;.</li>
									<li>Tagihan yang harus dibayarkan akan muncul pada layar konfirmasi.</li>
									<li>Konfirmasi transaksi dan masukkan Password Transaksi.</li>
									<li>Pembayaran Anda Telah Berhasil.</li>
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
							SMS Banking
						</a>
						<span class='pull-right'><i class='fa fa-angle-down'></i></span>
					</h5>
				</div>
				<div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
					<div class="card-body">
						<div class='row'>
							<div class="col-md-12 col-sm-12">
								<ol style='padding-left:10px;padding-right:10px'>
									<li>Buka aplikasi SMS Banking BNI</li>
									<li>Pilih menu Transfer</li>
									<li>Pilih menu Trf rekening BNI</li>
									<li>Masukkan nomor rekening tujuan dengan 16 digit Nomor Virtual Account (contoh: <?PHP echo $nomor_rekening; ?>).</li>
									<li>Masukkan nominal transfer sesuai tagihan atau kewajiban Anda. Nominal yang berbeda tidak dapat diproses.</li>
									<li>Pilih &quot;Proses&quot; kemudian &quot;Setuju&quot;</li>
									<li>Reply sms dengan ketik pin sesuai perintah</li>
									<li>Transaksi Berhasil</li>
								</ol>
								<p>Atau Dapat juga langsung mengetik sms dengan format:<br />
								<strong>TRF[SPASI]NomorVA[SPASI]NOMINAL</strong><br />
								dan kemudian kirim ke 3346<br />
								Contoh : TRF <?PHP echo $nomor_rekening; ?> 217338</p>
							</div>
						</div>
					</div>
				</div>
			</div> <!-- 5 -->
			
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