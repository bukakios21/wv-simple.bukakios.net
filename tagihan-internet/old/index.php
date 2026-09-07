<?PHP
$auto_connect = 1; //auto connect database;
require_once("../config.php");
require_once("../_session.php");
// require_once("_/session_pin.php");
// $user_id = 39958;
// $user_id = 40408;
// $user_token_trx = "5b6b8fe76b9ff145ad9d969b3177f6cd";
$kode = "telkom";
// if (!isset($_GET['e'])){
//     echo "Maintenance";exit;
// }
// $user_id = 39958;

$id_produk = 840; //telkom
$detail_produk_a = $app->grab_data("$api_url/produk_detail.php?key=$api_key&id=$id_produk");
$detail_produk_a = json_decode($detail_produk_a, true);
$pesan_masih_tutup = "";
if (!isset($detail_produk_a['data'])) {
	echo "Ada sedikit masalah di server kami, silahkan di ulangi kembali! <a href='home.html'>Kembali</a>";
	exit;
} else {
	$detail_produk = $detail_produk_a['data'];
	$code = $detail_produk['code'];
	$price = $detail_produk['price'];
	$price_add = $detail_produk['price_add'];
	$price_admin = $detail_produk['price_admin'];
	$product_logo = $detail_produk['product_logo'];
	$profit = str_replace("-", "", $price) - $price_add;
	$biaya_admin = 2500 - $profit;
}
$file_sn = "telkom";
if (isset($_REQUEST['act'])) {
	require_once("proses_telkom.php");
} else {
	$csrf = $app->csrf();
}

// $bank_lists = $app->grab_data("$ms1_url/json-static/bank-list.json");
// $bank_lists = json_decode($bank_lists,true);
// echo $code;
?>
<!DOCTYPE html>
<html>

<head lang="en">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>title::Bayar tagihan Produk </title>
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Nunito" />
	<link rel="stylesheet" href="https://member.bukakios.net/css/separate/vendor/bootstrap-select/bootstrap-select.min.css">
	<link rel="stylesheet" href="https://member.bukakios.net/css/separate/vendor/select2.min.css">
	<link rel="stylesheet" href="<?= $c_url ?>/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://wv.bukakios.net/assets/css/font-awesome.min.css">

	<style>
		.img-produk {
			max-height: 80px;
		}

		.col-md-12 {
			/* padding:15px; */
			border: none !important;
		}

		body {
			font-family: Nunito;
			color: #838383;
			background-color: #f5f5f5;

		}

		#resend:after {
			color: blue;
		}

		table tr td {
			border: 0px !important;
		}

		.txt-desc {
			font-size: 10px;
		}

		.border1px {
			border: 1px solid #dfdfdf;
			border-radius: 5px;
		}

		.shadow {
			/* box-shadow:  0 55px 110px -15px rgba(0, 0, 0, 0.07); */
			-webkit-box-shadow: 0px 10px 24px -4px rgba(48, 46, 48, 0.32);
			-moz-box-shadow: 0px 10px 24px -4px rgba(48, 46, 48, 0.32);
			box-shadow: 0px 10px 24px -4px rgba(48, 46, 48, 0.32);
		}

		.transition {
			animation: transitionIn 1.2s;
		}

		@keyframes transitionIn {
			from {
				opacity: 0;
				transform: translateY(-10px);
			}

			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		.left {
			width: 30%;
		}

		.right {
			width: 5%;
		}

		.middle {
			width: 70%;
		}

		.float {
			float: left;
		}

		/* pake font lato */

		.primary-bg {
			background-color: #fff;
			/* background-color:#f4f9ff; */
		}

		.black {
			color: #192139;
		}

		.txt-desc {
			font-size: 10px;
		}

		.shadow {
			/* box-shadow:  0 55px 110px -15px rgba(0, 0, 0, 0.07); */
			-webkit-box-shadow: 0px 10px 24px -4px rgba(48, 46, 48, 0.32);
			-moz-box-shadow: 0px 10px 24px -4px rgba(48, 46, 48, 0.32);
			box-shadow: 0px 10px 24px -4px rgba(48, 46, 48, 0.32);
		}

		.transition {
			animation: transitionIn 1s;
		}

		@keyframes transitionIn {
			from {
				opacity: 0;
				transform: translateY(-10px);
			}

			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		.margin {
			margin: 15px;
		}

		.card {
			color: white;
		}

		.col-md-12 {
			/* padding-right:12px !important; */
			/* padding-left:12px !important; */
		}

		input::placeholder {
			font-size: 12px;
		}
	</style>
</head>

<body>
	<div class="card margin shadow" style="background-color:#007bff; background-size:cover">
		<div class="card-body text-center" style="text-align:left !important;">
			<div class="left float">
				<img class="img-header" style="max-width:50%;margin-left:15px" src="https://assets.bukakios.net/img2/uploads/2020/09/182-logo-telkom1.png" alt="">
			</div>
			<div class="middle float">
				<!-- <h5 class="card-title"><b><span style="font-size:14px;">Maintenance</span></b></h5> -->
				<h5 class="card-title"><b><span style="font-size:14px;">Bayar Tagihan Produk Telkom</span></b></h5>
				<p class="card-text" style="text-align:left; font-size:10px">Silahkan masukkan no pelanggan kamu di bawah ini</p>
			</div>
		</div>
	</div>

	<div class="page-content">
		<div class="container-fluid">
			<div class='row'>

				<div class="col-md-12 col-lg-12 col-sm-12">
					<section>
						<!-- <header class="card-header card-header-lg">
							<i class="fa fa-bank"></i> Transfer Antar Bank MAX
						</header> -->
						<div class="">
							<div class="row">
								<div class="col-md-12">

									<?PHP if ($pesan_masih_tutup != "") {
										echo "<div class='alert alert-danger'>$pesan_masih_tutup</div>";
									} ?>
									<div id="form_cek" style="display:block">

										<?PHP if ($pesan_masih_tutup == "") { ?>
											<div class="card shadow" style="background-color:white;">
												<div class="card-body text-center" style="text-align:left !important;">
													<div class="">
														<fieldset class="form-group">
															<label class="form-label" for="account">Nomor Pelanggan *</label>
															<input id="account" name="account" type="number" class="form-control" placeholder="No pelanggan" required="">
														</fieldset>
														<input type="hidden" name="csrf" id="csrf" value="<?PHP echo $csrf; ?>">
														<button class="btn btn-primary btn-cek btn-block btn-sm" style="padding:10px" type="button">Cek Tujuan</button>
														<a class="btn btn-warning btn-block btn-sm" style="padding:10px; color:white" href="index.php">Batal</a>
													</div>
												</div>
											</div>
										<?PHP } ?>
									</div>


									<div id="form_detail" style="display:none;margin-top:20px">
										<div style="text-align:center;">
											<div class="card shadow">
												<div class="card-body text-center" style="text-align:left !important;padding-top:-100px">
													<div class="row">
														<div class="col-12 mx-auto text-center">
															<img width="200px" src="https://vignette.wikia.nocookie.net/logopedia/images/4/4d/Indihome_Fiber.png/revision/latest?cb=20160614170220" alt="" id="user_gambar">
														</div>
													</div>

												</div>
											</div>

											<div class="form-group" style="margin-top:15px">
												<div style="text-align:left ">
													<div>

														<div class="table-responsive shadow border1px" style="padding:10px;">
															<h5 style=" padding-left:10px; padding-top:10px; text-align:center" class="black"><b>Informasi Transfer</b></h5>
															<table style="width:100%" class="table">
																<tr>
																	<td width="10px;"><b>*</b></td>
																	<td style="padding-top:10px;"><span>Nama pelanggan</span><br /><span class="txt-desc" style="font-size:12px"><b>Nama pelanggan</b></span></td>
																	<td><b></span><span id="r_nama_bank"></span></b><br /></td>
																</tr>
																<tr>
																	<td width="10px;"><b>*</b></td>
																	<td style="padding-top:10px;"><span>Nomor pelanggan</span><br /><span class="txt-desc" style="font-size:12px"><b>Nomor pelanggan</b></span></td>
																	<td><b></span><span id="r_nomor_rekening"></span></b><br /></td>
																</tr>
																<tr>
																	<td width="10px;"><b>*</b></td>
																	<td style="padding-top:10px;"><span>Jumlah tagihan</span><br /><span class="txt-desc" style="font-size:12px"><b>Total tagihan pdam</b></span></td>
																	<td><b><span style="font-size:9px;">Rp.</span> <span id="r_jumlah_transfer"></span></b><br /><span class="txt-desc" style="font-size:12px">Total tagihan</span></td>
																</tr>
																<tr>
																	<td width="10px;"><b>*</b></td>
																	<td style="padding-top:10px;"><span>Biaya admin </span><br /><span class="txt-desc" style="font-size:12px"><b>Total admin </b></span></td>
																	<td><b><span style="font-size:9px;">Rp.</span> <span id="r_biaya_admin"></span></b><br /><span class="txt-desc" style="font-size:12px">Total admin</span></td>
																</tr>
																<tr>
																	<td width="10px;"><b>*</b></td>
																	<td style="padding-top:10px;"><span>Bayar kamu</span><br /><span class="txt-desc" style="font-size:12px"><b>Total keseluruhan transaksi</b></span></td>
																	<td><b><span style="font-size:9px;">Rp.</span> <span id="r_total_bayar"></span></b><br /><span class="txt-desc" style="font-size:12px">Total transaksi</span></td>
																</tr>
																<tr>
																	<td width="10px;"><b>*</b></td>
																	<td style="padding-top:10px;"><span>Profit kamu</span><br /><span class="txt-desc" style="font-size:12px"><b>Total profit</b></span></td>
																	<td><b><span style="font-size:9px;">Rp.</span> <span id="total_profit"></span></b><br /><span class="txt-desc" style="font-size:12px">Total profit</span></td>
																</tr>
															</table>
														</div>
													</div>

												</div>
											</div>

											<div class="card shadow">
												<div class="card-body text-center" style="text-align:left !important;">
													<div>
														<h2 class="card-title" style="text-align:center;"><b><span style="font-size:15px;color:#000 ">Total saldo kamu berkurang</span></b></h2>
														<div class="alert alert-info" id="r_total_bayar2">Rp. 20.000</div>
													</div>

												</div>
											</div>


											<div class="card shadow" style="margin-top:20px; margin-bottom:20px">
												<div class="card-body text-center" style="text-align:left !important;">
													<div>
														<h2 class="card-title" style="text-align:center;"><b><span style="font-size:15px;color:#000 ">Total bayar pelanggan kamu</span></b></h2>
														<div class="alert alert-info" id="r_total_bayar_pelanggan">Rp. 20.000</div>
													</div>

												</div>
											</div>
											
                                            <div class="card shadow">
                                                <div class="card-body text-center" style="text-align:left !important;">
                                                    <div>
                                                        <h2 class="card-title colorku" style="text-align:center; "><b><span style="font-size:15px; color:#000">Profit kamu</span></b></h2>
                                                        <input type="text" class="form-control" id="biaya_profit"  name="biaya_profit" value="2500">
                                                        <small style=" color:#000">Ini adalah keuntungan bersih untuk kamu. Keuntungan ini akan menjadi admin di struknya  </small>
                                                    </div>
                                                </div>
                                            </div>

											<div class="alert alert-warning">
												* Pastikan data sudah sesuai :), setelah itu klik Bayar
											</div>
											<button id="transfer_sekarang" type="button" style="padding:10px;" class="btn btn-success btn-block">Bayar</button>
											<button id="cancel_transfer" type="button" style="padding:10px;" class="btn btn-danger btn-block">Batal</button>
										</div>
									</div>
								</div>
							</div>

						</div>
					</section>
				</div>
			</div>
		</div>
		<!--.container-fluid-->
	</div>
	<!--.page-content-->
	<script src="https://member.bukakios.net/js/lib/jquery/jquery-3.2.1.min.js"></script>
	<script src="https://member.bukakios.net/js/lib/bootstrap-notify/bootstrap-notify.min.js"></script>
	<script src="https://member.bukakios.net/js/lib/notie/notie.js"></script>

	<script src="https://member.bukakios.net/js/lib/bootstrap-select/bootstrap-select.min.js"></script>
	<script src="https://member.bukakios.net/js/lib/select2/select2.full.min.js"></script>
	<script type='text/javascript'>
		var timerid;
		var operator = $("#operator");
		var produk = $("#produk");
		var target = $("#target");
		//operator.attr('disabled', 'disabled');
		produk.attr('disabled', 'disabled');
		if ($('.bootstrap-select').length) {
			// Bootstrap-select
			$('.bootstrap-select').selectpicker({
				style: '',
				width: '100%',
				size: 8
			});
		}

		if ($('.select2').length) {
			// Select2
			//$.fn.select2.defaults.set("minimumResultsForSearch", "Infinity");

			$('.select2').not('.manual').select2();

			$(".select2-icon").not('.manual').select2({
				templateSelection: select2Icons,
				templateResult: select2Icons
			});

			$(".select2-arrow").not('.manual').select2({
				theme: "arrow"
			});

			$('.select2-no-search-arrow').select2({
				minimumResultsForSearch: "Infinity",
				theme: "arrow"
			});

			$('.select2-no-search-default').select2({
				minimumResultsForSearch: "Infinity"
			});

			$(".select2-white").not('.manual').select2({
				theme: "white"
			});

			$(".select2-photo").not('.manual').select2({
				templateSelection: select2Photos,
				templateResult: select2Photos
			});
		}

		function select2Icons(state) {
			if (!state.id) {
				return state.text;
			}
			var $state = $(
				'<span class="font-icon ' + state.element.getAttribute('data-icon') + '"></span><span>' + state.text + '</span>'
			);
			return $state;
		}

		function select2Photos(state) {
			if (!state.id) {
				return state.text;
			}
			var $state = $(
				'<span class="user-item"><img src="' + state.element.getAttribute('data-photo') + '"/>' + state.text + '</span>'
			);
			return $state;
		}
	</script>
	<script type='text/javascript'>
		$(document).ready(function() {

			function format_rp(bilangan) {
				reverse = bilangan.toString().split('').reverse().join(''),
					ribuan = reverse.match(/\d{1,3}/g);
				ribuan = ribuan.join('.').split('').reverse().join('');
				return "Rp. " + ribuan;
			}

			$('#cancel_transfer').click(function() {
				$('#form_cek').fadeIn();
				$('#form_detail').fadeOut();
				$('.btn-cek').html("Cek Tujuan");
				$('.btn-cek').attr('disabled', false);
				$('.btn-cek').show();
				$('#transfer_sekarang').attr('disabled', false);
			});

			$('#transfer_sekarang').on('click', function() {
				var account = $('#account').val();
				var csrf = $('#csrf').val();
                var profit = $("#biaya_profit").val();
				console.log(account)
				$('#transfer_sekarang').html("Proses..");
				$('#transfer_sekarang').attr('disabled', true);
				$.ajax({
					url: 'index.php?act=bayar&e=1&biaya_profit='+profit+'&id_pelanggan=' + account + '&csrf=' + csrf,
					dataType: 'json',
					success: function(data) {
						console.log(data);
						// console.log('ananf');
						// var myJsn = JSON.parse(data);
						var myJsn = data;
						if (myJsn.status == 1) {
							notie.alert(1, myJsn.trx_id, 3);
							// notie.alert(1, myJsn.msg, 3);
							window.setTimeout(function() {
								window.location.href = "success.php?id_trx=" + myJsn.trx_id;
							}, 3000);
						} else {
							window.setTimeout(function() {
								window.location.href = "error.php?desc=" + myJsn.error_msg;
							}, 3000);
							notie.alert(3, myJsn.error_msg, 10);
						}
						$('#transfer_sekarang').attr('disabled', true);
						$('#transfer_sekarang').html("Bayar");
					}
				});
			});

			$(document).on('click', '.btn-cancel', function() {
				$('.btn-cek').html("Cek").show();
				$('.res-cek').fadeOut();
				$('#btn_beli').hide();
				$('.nama-res').val();
				$('.btn-cancel').hide();
			});

			function change_photo(codenya) {
				var res = codenya.split(";");
				var i952a = "https://assets.bukakios.net/img/bank-code/" + res[1] + ".jpg";
				$("#user_gambar").attr("src", i952a);
			}

			function cek_account() {
				var account = $('#account').val();
				console.log(account);
				// var account = "214010042020";
				// var  account = "ckr1300044";
				// var account = "1621200000";
				var csrf = $('#csrf').val();
				var code = "<?= $kode ?>";
				// var code = "PP_FNMEGA";
				// PP_FNMEGA
				// var code = $('#code').val();
				// if (jumlah < 10000){
				// 	notie.alert(2, "Jumlah transfer minimal 10.000", 5);
				// 	return;
				// }

				$('.btn-cek').html("Process..");
				$('.btn-cek').attr('disabled', true);
				$('.alert-danger').hide();
				var status = "";
				$.ajax({
					url: 'index.php?act=cek&e=1&id_pelanggan=' + account + '&csrf=<?PHP echo $csrf; ?>',
					success: function(output) {
						// $('.footer').show(); 
						console.log(output);
						// $('#load').hide(); 
						var myJsn = JSON.parse(output);
						var d = myJsn.data;
						if (myJsn.status == 1) {
							var dataJsn = myJsn.data;
							var total_bayar = dataJsn.total_tagihan - dataJsn.profit;
							// button 
							// $('#cancel').show(); 
							// $('#fotfot').show();
							// $('#pay').show();
							// form
							// $('#detail').show();
							// $('#det_ta').show();


							// $("#tot_ta").html(rubah_rp(dataJsn.tagihan));
							// $("#nama").html(dataJsn.nama_pelanggan);
							// $("#profit").html(rubah_rp(dataJsn.profit));
							// $("#biaya").html(rubah_rp(dataJsn.admin));

							// $("#periode").html(dataJsn.periode);
							// $("#tot_ka").html(rubah_rp(total_bayar));
							///detail
							$('#r_nama_bank').html(dataJsn.nama_pelanggan);
							$('#user_nama').html(dataJsn.nama_pelanggan);
							$('#r_nomor_rekening').html(dataJsn.id_pelanggan);
							$('#r_jumlah_transfer').html(format_rp(dataJsn.total_tagihan));
							$('#r_biaya_admin').html(dataJsn.admin);
							$('#r_total_bayar').html(format_rp(total_bayar));
							$('#r_total_bayar2').html(format_rp(total_bayar));
							$('#r_total_bayar_pelanggan').html(format_rp(dataJsn.total_tagihan));
							$('#total_profit').html(format_rp(dataJsn.profit))
							$('#form_detail').fadeIn();
							// $('#user_nama').html(data.account_holder);
							$('#form_cek').fadeOut();
							// change_photo(kode);

						} else {
							notie.alert(3, myJsn.error_msg, 3);
							$('.btn-cek').html("Cek tujuan");
							$('.btn-cek').attr('disabled', false);
						}
					}
				})
				// $.ajax({
				// 	url:"max.php",
				// 	data:{kode:kode, account:account, act:"cek", csrf:csrf, jumlah:jumlah},
				// 	method:'post',
				// 	dataType:'json',
				// 	success: function(data){
				// 		console.log(data);

				// 		if (data.status == "SUCCESS"){
				// 			$('#nama-res').val(data.account_holder);
				// 			$('.res-cek').fadeIn();
				// 			$('.alert-danger').fadeOut();
				// 			$('.btn-cek').html("Cek");
				// 			$('.btn-cek').attr('disabled', false);
				// 			$('.btn-cek').hide();
				// 			$('.btn-cancel').show();
				// 			$('#btn_beli').show();

				// 			///detail
				// 			$('#r_nama_bank').html(kode);
				// 			$('#r_nomor_rekening').html(account);
				// 			$('#r_nama_rekening').html(data.account_holder);
				// 			$('#r_jumlah_transfer').html(format_rp(jumlah));
				// 			$('#r_biaya_admin').html("Rp. 5000");
				// 			var total_bayar = parseInt(jumlah) + 5000;
				// 			$('#r_total_bayar').html(format_rp(total_bayar));
				// 			$('#r_total_bayar2').html(format_rp(total_bayar))
				// 			$('#form_detail').fadeIn();
				// 			$('#user_nama').html(data.account_holder);
				// 			$('#form_cek').fadeOut();
				// 			change_photo(kode);

				// 		}
				// 		else if (data.status == "INVALID_ACCOUNT_NUMBER"){
				// 			notie.alert(2, data.status, 5);
				// 			$('.btn-cek').html("Cek");
				// 			$('.btn-cek').attr('disabled', false);
				// 		}
				// 		else if (data.status == "PENDING"){
				// 			cek_account();
				// 		}else{
				// 			notie.alert(2, data.error_msg, 5);
				// 		}
				// 		$('.btn-cek').html("Cek");
				// 			$('.btn-cek').attr('disabled', false);
				// 	}
				// });
			}

			$(document).on('click', '.btn-cek', function() {
				cek_account();
			});



		});

		operator.change(function(e) {
			load_harga(operator.val(), produk, operator);
		});
		produk.change(function(e) {
			get_produk_detail();
		});
	</script>

	<script>
		// var jumlah_transfer = document.getElementById("jumlah_transfer");
		// 	jumlah_transfer.addEventListener("keyup", function(e) {
		// 	jumlah_transfer.value = formatjumlah_transfer(this.value, "Rp. ");
		// });
		function formatjumlah_transfer(angka, prefix) {
			var number_string = angka.replace(/[^,\d]/g, "").toString(),
				split = number_string.split(","),
				sisa = split[0].length % 3,
				jumlah_transfer = split[0].substr(0, sisa),
				ribuan = split[0].substr(sisa).match(/\d{3}/gi);
			if (ribuan) {
				separator = sisa ? "." : "";
				jumlah_transfer += separator + ribuan.join(".");
			}

			jumlah_transfer = split[1] != undefined ? jumlah_transfer + "," + split[1] : jumlah_transfer;
			return prefix == undefined ? jumlah_transfer : jumlah_transfer ? "Rp. " + jumlah_transfer : "";
		}
	</script>

</body>

</html>