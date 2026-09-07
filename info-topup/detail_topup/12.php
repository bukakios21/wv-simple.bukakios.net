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
			$nomor_rekening = $payment_info;
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
				<div class='box' style='padding:20px;cursor:default'>
					<center>
						<img src='<?PHP echo "$assets_url/img/payment/indomaret.png"; ?>' style='height:25px'/>
					</center>
                    <div style="margin-top:10px;margin-bottom:-10px">
                    Bilang Ke Kasir mau bayar ke : <b>Linkita</b> lalu sebutkan kode pembayaran di bawah ini<br>
                    </div>
					<br><b style='font-size:21px' id='nomor_rekening' data-text="No. Rekening berhasil disalin" data-copy="<?PHP echo $nomor_rekening; ?>"><?PHP echo $nomor_rekening; ?></b>
					<br><span style="text-decoration:underline;color:#00bfff;cursor:pointer" onclick="copyToClipboard('nomor_rekening')">Salin Kode Pembayaran</span>
				</div>
			</div>   
		</div>
	</div>
	<?php } ?>
<!-- </div> dari awal index.php -->
</div>

<?PHP if(!$pg_error){ ?>

<div class='row'>
	<div class='col-md-12 '>
		<div id='infonya' class='card mt-3' style='margin-bottom:20px;padding:10px'>
		* <b>HARAP BACA</b> : Beritahu ke kasir mau bayar tagihan <strong>Linkita</strong>, lalu tunjukan kode pembayaranya &rarr; <?PHP echo $nomor_rekening; ?> , biasanya saat di kasir ada biaya admin dari pihak indomaretnya sebesar <b>Rp2.500</b>
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
							Kasir Indomaret
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
									<li>Jika Di tanya Merchant ID, sebutkan &quot;LinkKita&quot;</li>
									<li>Bayar Sesuai Tagihan di Bukakios Yakni : <b><?PHP echo $total_transfer_rp; ?></b> , biasanya ada biaya tambahan lagi sebesar <b>Rp2.500</b> dari pihak indomaret.</li>
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
<div class='alert alert-danger mt-3 bayar-id'><?PHP echo $error_msg; ?></div>
<?PHP } ?>