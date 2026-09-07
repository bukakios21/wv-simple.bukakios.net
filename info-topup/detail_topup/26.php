<?PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// header('Content-Type: application/json');
require_once("phpqrcode/qrlib.php");
// $data_post = array(
// 	"key" => $api_key,
// 	"id" => $topup_id
// );
// $data_info1 = $app->curl_post("$api_url/get_topup_detail.php", $data_post);

// $data_info = json_decode($data_info1, true);
$data_info=callTopupApi($topup_id,$user_jwt);
$data_info = json_decode($data_info,true);
// echo var_dump($data_info);
if (isset($data_info['status'])) {
	$pg_error = false;
	if ($data_info['status'] == "1") {
		//berhasil
		if (isset($data_info['data']['data'])) {
			$qr = $data_info['data']['data'];
			// $total_transfer = $data_info['amount'];
			// $total_transfer_rp = $app->idr($total_transfer);
			// $file_qris = md5("$user_id-$topup_id").".png";
			// QRcode::png($qr, "qris_image/$file_qris");
			$file_qris = $data_info['data']['data'];
		} else {
			$pg_error = true;
			$error_msg = "Payment info tidak ada, silahkan kontak tim kami!";
		}
	} else {
		$pg_error = true;
		$error_msg = "Ada Kesalahan -> " . $data_info['error_msg'];
	}
} else {
	$pg_error = true;
	// $error_msg = $data_info;
	$error_msg = "Server Api Respon Tidak Valid!!, silahkan kontak tim kami!";
}
?>
<?php if (!$pg_error) { ?>
	<div class='row'>
		<div class="col-12">
			<span class="font-weight-bold">Pembayaran Ke</span>
			<div class='alert alert-danger '>
				<div class="text-center"><strong>Perhatian :</strong></div> <br />
				<div class="text-justify">Mohon untuk melakukan scan barcode <b>QRIS SATU KALI SAJA</b>, bukan berulang kali. Tiket top up hanya dapat memproses hasil scan barcode yang pertama. Jika ingin menambah saldo dengan metode QRIS kembali, silahkan membuat tiket top up yang baru agar pembayaran dapat diproses oleh sistem.</div>
			</div>
			<div id='info-bank' class="text-center">
				<!-- <div class='col-md-6 col-xs-12 mx-auto'> -->
				<div class='box' style='padding:20px;cursor:default'>
					* Untuk melakukan pembayaran silahkan scan QR ini.<br/>
					<img width="70%" src="<?php echo $c_url ?>info-topup/qris_image/<?php echo $file_qris ?>" alt="">
					<img width="70%" src="<?php echo $file_qris ?>" alt="">
					<!-- <a class='btn btn-primary btn-block' href="open://https://wv.bukakios.net/info-topup/">Buka di chrome</a> -->
					<a class='btn btn-success btn-block' href="open://https://wv.bukakios.net/info-topup/download.php?file=<?php echo $file_qris ?>">Download file</a>
					<br />
					QR ini bisa di scan untuk :<br />
					<img width="100%" src="https://assets2.bukakios.net/img2/uploads/2025/03/772-logo-3.png" alt="">
				</div>
				<!-- </div> -->
			</div>
		</div>
	</div>
	<!-- </div> dari awal index.php -->
<?php } ?>
</div>
<?PHP if (!$pg_error) { ?>
	<div id='cara-bayar-id-1'>
		<div class='alert alert-info mt-3 bayar-id'>
			Informasi mengenai QRIS : <br>
			1. 24 Jam nonstop selama QRIS tidak maintenance.<br>
			2. Begitu transfer saldo langsung masuk.<br>
		</div>

	</div>
	<script src="https://wv.bukakios.net/assets/js/jquery.js"></script>
	<script src="https://wv.bukakios.net/assets/js/sweetalert.min.js"></script>
	<script>
		Swal.fire({
			title: "<b>PENTING</b>",
			html: "<p class='text-justify'>PERHATIAN : Mohon untuk melakukan scan barcode <b>QRIS SATU KALI SAJA</b> Tiket Top UP hanya dapat Memproses Hasil Scan Barcode yang Pertama.</p><img width='300px' src='https://assets.bukakios.net/img2/uploads/2020/10/206-qris.png'>",
			confirmButtonText: "Ok, Paham",
		});
	</script>
<?PHP } else { ?>
	<div class='alert alert-danger mt-3 bayar-id'><?PHP echo $error_msg; ?></div>
<?PHP } ?>