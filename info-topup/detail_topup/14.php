	<div class='row'>
		<div class="col-12">
			<span class="font-weight-bold">Transfer Ke</span>          
			<div id='info-bank' class="text-center">
				<!-- <div class='col-md-6 col-xs-12 mx-auto'> -->
					<div class='box' style='padding:20px;cursor:default'>
						<center><img src='<?PHP echo $gambar_metode; ?>' width='120px'/></center>
						<p style='margin-top:10px'>Bank : <b><?PHP echo $nama_metode; ?></b><br>
						Nomor Rekening : <b id='nomor_rekening' data-text="No. Rekening berhasil disalin" data-copy="<?PHP echo str_replace("-","",$nomor_rekening); ?>"><?PHP echo $nomor_rekening; ?></b> <br><span style="text-decoration:underline;color:#00bfff;cursor:pointer" onclick="copyToClipboard('nomor_rekening')">Salin No HP</span><br>
						Atas Nama : <b><?PHP echo $nama_rekening; ?></b><br>
					</div>
				<!-- </div> -->
			</div>   
		</div>
	</div>
<!-- </div> dari awal index.php -->
</div>

<!-- <div class="alert alert-warning mt-3 bayar-id">
	<h5>OVO SEDANG GANGUAN</h5>
	<b>INFO*. TOPUP MELALUI OVO AKAN MEMAKAN WAKTU YANG LAMA. JIKA INGIN CEPAT SILAHKAN GUNAKAN METODE TOPUP YANG LAIN</b>
</div> -->
<div id='cara-bayar-id-1'>
	<div class='alert alert-danger bayar-id'>
		<h5>HARAP BACA PENTING!!!!!</h5>
		*Mohon transfer sesuai jumlah yang tertera (tidak lebih atau kurang). 
		tiga angka unik di gunakan untuk membantu kami mendeteksi bahwa kamu yang mentransfer. 
		tenang saja, saldo kamu tetap tertambah sejumlah yang ditransfer, 
		termasuk tiga angka unik <b><?PHP echo $total_transfer_rp; ?></b>
		<p style='margin-top:10px'>
		Dan juga di karenakan ini menggunakan metode pembayaran OVO bebas biaya admin, saat kamu 
		sudah melakukan transfer, saldo kamu tidak di proses saat itu juga, kami harus
		melakukan pengecekan otomatis yang memakan waktu 5 - 10 menit, namun tenang saja, selama 
		jumlah yg kamu transfer tepat, saldo pasti masuk :)</p>
	</div>
	<div class='alert alert-success bayar-id'>
		<b>JUST INFO!!</b><br> 
		Bagi akun OVO kamu yang belum premier masih bisa kok melakukan pembayaran, kamu bisa melakukan pembayaran dengan cara melakukan pengisian/topup saldo ke akun ovo kami yakni <b><?PHP echo $nomor_rekening; ?></b>, lakukan topup seperti petunjuk yang ada di OVO, dan jangan lupa nominal topupnya harus tepat ya :) yakni <b><?PHP echo $total_transfer_rp ?></b> <i>tidak kurang, tidak lebih</i>
	</div>
</div>

										
<div id='panduanbayar' >
	<div class="pl-16 pr-16 pt-24">
		<div class="">
			<h5>PANDUAN PEMBAYARAN</h5>
		</div>
		
		<div id="accordion">
			<div class="card">
				<div style='cursor:pointer' class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
					<h5 class="mb-0">
						<a>
							Melalui Aplikasi OVO
						</a>
						<span class='pull-right'><i class='fa fa-angle-down'></i></span>
					</h5>
				</div>
				<div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
					<div class="card-body">
						<div class='row'>
							<div class="col-md-12 col-sm-12">
								<ol style='padding-left:10px;padding-right:10px'>
									<li>Buka Aplikasi OVO Kamu</li>
									<li>Karna ini metode pembayaran OVO <b>gratis biaya admin</b>, jadi sistem nya itu transfer ke sesama akun OVO, sehingga untuk bisa melakukan transfer antar akun OVO, akun kamu harus <b>terverifikasi</b> terlebih dahulu, jadi pastikan kamu sudah melakukan verifikasi identitas ya :)</li>
									<li>Jika akun kamu sudah terverifkasi, pilih menu &quot;Transfer&quot;</li>
									<li>Pilih &quot;Antar OVO&quot;</li>
									<li>Masukkan nomor rekening/hp kami : <b><?PHP echo $nomor_rekening; ?></b></li>
									<li>Masukkan Jumlah transfer sesuai intruksi yakni : <b><?PHP echo $total_transfer_rp; ?></b> (tidak kurang, tidak lebih)</li>
									<li>Pastikan detil tagihan Anda sudah benar, kemudian pilih &ldquo;BENAR&rdquo;</li>
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