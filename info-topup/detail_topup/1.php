	<div class='row'>
		<div class="col-12">
			<span class="font-weight-bold">Transfer Ke</span>          
			<div id='info-bank' class="text-center">
				<!-- <div class='col-md-6 col-xs-12 mx-auto'> -->
					<div class='box' style='padding:20px;cursor:default'>
						<center><img src='<?PHP echo $gambar_metode; ?>' width='120px'/></center>
						<p style='margin-top:10px'>Bank : <b><?PHP echo $nama_metode; ?></b><br>
						Nomor Rekening : <b id='nomor_rekening' data-text="No. Rekening berhasil disalin" data-copy="<?PHP echo str_replace("-","",$nomor_rekening); ?>"><?PHP echo $nomor_rekening; ?></b> <br><span style="text-decoration:underline;color:#00bfff;cursor:pointer" onclick="copyToClipboard('nomor_rekening')">Salin No.Rek</span><br>
						Atas Nama : <b><?PHP echo $nama_rekening; ?></b><br>
					</div>
				<!-- </div> -->
			</div>   
		</div>
	</div>
<!-- </div> dari awal index.php -->
</div>


<div class='row '>
	<div class="col-12">
		<div id='cara-bayar-id-1' class="mt-3 ">
			<div class='alert alert-danger bayar-id'>
				<h4>PENTING !!!!!!</h4>
				<div class='text-justify'>
					YUK, BACA INFORMASI INI SEBELUM TOP-UP DAN TRANSAKSI!. BAGI KAMU PENGGUNA <b>*BANK BRI*</b>, JIKA HENDAK MELAKUKAN *TOP-UP SALDO LEBIH CEPAT DALAM SEKEJAP*, KAMI SARANKAN
					UNTUK MELAKUKAN TOP-UP MELALUI <b>*BRI VIRTUAL ACCOUNT (BRIVA)*</b>, ATAU TRANSFER KE BANK LAIN <b>*(BANK BCA, ATAU BANK MANDIRI)*</b>.
					<p>NAMUN, JIKA KAMU MASIH MENGGUNAKAN METODE TOP-UP MELALUI BANK TRANSFER SESAMA BRI, SEBAIKNYA KAMU MELAKUKAN TOP-UP SEBELUM SALDO DI AKUN KAMU MENIPIS.
					<p>
					MISALNYA SATU ATAU DUA HARI SEBELUM SALDO KAMU HABIS.SELAMAT MELAKUKAN TRANSAKSI, TERIMAKASIH SUDAH MEMBACA INFORMASI INI :)
					<p>
					JIKA ADA PERTANYAAN LANJUTAN SILAHKAN HUBUNGI BAGIAN CS KAMI:)KAMI SENANTIASA MENUNGGU KABAR DARI KAMU.
				</div>
				<p>
					<b>*S&K</b>
				</p>
				<ul>
					<li>1. top-up dari BRI ke Briva dikenakan biaya admin Rp. 2000</li>
					<li>2. top-up dari BRI ke Bank Lain (BCA atau Mandiri) dikenakan biaya admin Rp. 6500</li>
					<li>3. top-up sesama BRI tidak dikenakan biaya (terjadi keterlambatan proses top-up)</li>
					<li>3.a. Jika TRANSAKSI DI BANK BRI SEDANG NORMAL, TOP-UP BARU DAPAT DIPROSES DALAM WAKTU 10 MENIT</li>
					<li>3.B. JIKA TRANSAKSI DI BANK BRI SEDANG MENGALAMI GANGGUAN, TOP-UP BARU DAPAT DIPROSES DALAM WAKTU 1 X 24 JAM</li>
				</ul>
			</div>
													
			<div class='alert alert-danger bayar-id'>
				<h5>HARAP BACA PENTING!!!!!</h5>
				*Mohon transfer sesuai jumlah yang tertera (tidak lebih atau kurang). 
				tiga angka unik di gunakan untuk membantu kami mendeteksi bahwa kamu yang mentransfer. 
				tenang saja, saldo kamu tetap tertambah sejumlah yang ditransfer, 
				termasuk tiga angka unik <?PHP echo $total_transfer_rp; ?>
			</div>
			<div class='alert alert-warning bayar-id'>
			
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>INFO BANK OFFLINE : </strong> 
				Deposit dapat dilakukan 24 jam otomatis selama bank terkait tidak maintenance (offline). Namun layanan internet banking dari Bank BCA, BNI, Mandiri dan BRI, pada jam-jam tertentu pasti Offline / Maintenance setiap harinya. Tidak ada jadwal yang resmi dari bank kapan akan melakukan maintenance, Namun rata-rata Bank Offline antara jam 21:00 - 00:00 WIB. Sehingga transfer deposit yang dilakukan pada saat bank offline akan diproses otomatis setelah bank kembali normal.
			</div>
			*Apabila Saldo tidak masuk dalam waktu 30 menit setelah transfer, harap hubungi tim kami :)
		</div>
	</div>
</div>

	<script src="https://wv.bukakios.net/assets/js/sweetalert.min.js"></script>
	<script>
		//Swal.fire({
		//	title: "<b>PENTING</b>",
		//	html: "<p class='text-justify'>*Mohon Transfer sejumlah <?= $total_transfer_rp ?> (tidak lebih atau kurang). Jangan khawatir, Saldo kamu akan bertambah sesuai nominal transfer.</p><img width='300px' src='https://assets.bukakios.net/img2/uploads/2020/10/753-kodeunik.png'>",
		//	confirmButtonText: "Ok, Paham",
		//});
	</script>
	<?php
if ($metode_id == 1) { ?>
<script>
        Swal.fire({
            title: "<b>PENTING</b>",
            html: "<p class='text-justify'>*Mohon maaf untuk topup via Bank Transfer BRI sukses lebih lambat dari metode Bank Lainnya. Untuk deposit lebih cepat dapat menggunakan metode Virtual Account/Bank Lainnya/Emoney.</p>",
            confirmButtonText: "Ok",
        });
    </script>
<?php } ?>
