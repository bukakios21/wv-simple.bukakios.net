<?PHP
//20 = permata
//permata get info nomor rekening dari api
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
			$rc = $payment_info['virtual_account'];
			if($rc!=''){
                $nomor_rekening = $payment_info['virtual_account'];
			}else{
				$pg_error = true;
				$error_msg = "Nomor rekening gagal di terbitkan karna empty, #RCERROR!";
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
						<center><img src='<?PHP echo $gambar_metode; ?>' width='120px'/></center><br>
						Nomor Rekening : <b id='nomor_rekening' data-text="No. Rekening berhasil disalin" data-copy="<?PHP echo str_replace("-","",$nomor_rekening); ?>"><?PHP echo $nomor_rekening; ?></b> <br><span style="text-decoration:underline;color:#00bfff;cursor:pointer" onclick="copyToClipboard('nomor_rekening')">Salin No.Rek</span><br>
					</div>
				<!-- </div> -->
			</div>   
		</div>
	</div>
	<!-- </div> dari awal index.php -->
	<?php } ?>
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
							ATM BERSAMA
						</a>
						<span class='pull-right'><i class='fa fa-angle-down'></i></span>
					</h5>
				</div>
				<div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
					<div class="card-body">
						<div class='row'>
							<div class="col-md-12 col-sm-12">
								<ol style='padding-left:10px;padding-right:10px'>
									
									<li>Masukkan kartu ATM</li>
									<li>Silahkan pilih Bahasa</li>
									<li>Masukkan nomor PIN Anda</li>
									<li>Silahkan pilih &gt; Transaksi Lain</li>
									<li>Silahkan pilih &gt; Transfer dana antar jaringan ATM Bersama</li>
									<li>Masukkan Kode Bank Permata + Rekening Pembayaran Anda (Masukkan Rekening Pembayaran Anda dengan format 013 + nomor rekening pembayaran</li>
									<li>Masukkan jumlah cicilan Anda</li>
									<li>Bila transaksi Anda telah sesuai, tekan &gt; Benar</li>
								-10">Simpan struk transaksi sebagai bukti pembayaran</li>
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
<div class='alert alert-danger mt-3 bayar-id'><?PHP echo $error_msg; ?></div>
<?PHP } ?>