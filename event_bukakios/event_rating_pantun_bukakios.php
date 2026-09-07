<?php
require_once("../config.php");
if (!isset($_GET['data'])) {
	echo "<h1>INVALID URL</h1>";
	exit;
}

$data = $_GET['data'];
if (substr_count($data, "-") <= 0) {
	echo "<h1>INVALID URL</h1>";
	exit;
}

$data_ex = explode("-", $data);
$uid = abs((int) $data_ex[0]);
$user_id = $uid;
$hash = $data_ex[1];
$today = date("Y-m-d");
$hash_real = md5(md5("event-bukakios-pantun-2021::$uid:$today"));
if ($hash !== $hash_real) {
	echo "<h1>INVALID DATA</h1>";
	exit;
}

// https://ms1.bukakios.net/v1/api_event/api_cek_peserta_rating_pantun.php
$post = array(
	'key' => $api_key,
	'uid' => $uid
);
$api_event = $app->curl_post("https://ms1.bukakios.net/v1/api_event/api_cek_peserta_rating_pantun.php", $post);
$api_event_res = json_decode($api_event, true);
$is_error = false;
$error_msg = "";
if (isset($api_event_res['status'])) {
	if ($api_event_res['status'] == 0) {
		$is_error = true;
		$error_msg = $api_event_res['error_msg'];
	}
} else {
	$is_error = true;
	$error_msg = "Gagal Menghubungi Api Event";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Event Pantun BukaKios</title>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/style.css">
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0/js/fontawesome.min.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.13.0/css/mdb.min.css" rel="stylesheet">
	<style media="screen">
		/* pake font lato */
		 body {
			 padding: 5px;
		 }
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


		.upload-btn-wrapper {
			position: relative;
			overflow: hidden;
			display: inline-block;
		}

		/* .btn {
        border: 2px solid gray;
        color: gray;
        background-color: white;
        padding: 8px 20px;
        border-radius: 8px;
        font-size: 20px;
        font-weight: bold;
        } */

		.upload-btn-wrapper input[type=file] {
			font-size: 100px;
			position: absolute;
			left: 0;
			top: 0;
			opacity: 0;
		}

		.left {
			width: 35%;
		}

		.right {
			width: 5%;
		}

		.middle {
			width: 60%;
		}

		.float {
			float: left;
		}

		.img-produk {
			max-width: 80px;
			height: auto;
			margin-left: 15px;
			/* border-radius: 50%; */
		}

		.mg-60 {
			margin-bottom: 70px;
			margin-top: 10px;
		}

		.bold {
			font-weight: bold;
		}

		ul {
			padding-left: -20px;
		}

		ul li {
			font-size: 12px;
			margin-left: -20px;
		}

		.b-bottom {
			border-bottom: 1px solid #ccc;
		}

		.col-sm-12 {
			margin-top: 10px;
			padding-left: 2px !important;
			padding-right: 2px !important;
		}

		.text-sm {
			font-size: 14px;
		}

		.card-text {
			text-align: justify;
			font-size: 12px;
		}

		.img-header {
			max-width: 80px;

		}

		.border-radius {
			border: 3px solid red;
		}

		.radius {
			border-radius: 50%;
		}

		.btn {
			margin-top: 30px;
			margin-bottom: 5px;
		}

		.margin {
			margin: 10px;
		}

		.img-sm {
			max-width: 40px;
		}

		.move:active {
			background-color: black;
			transition: filter 0s;
		}

		body {
			font-family: 'Lato', sans-serif;
			color: #838383;
			/* background-color: #f4f9ff; */
		}

		ul.checkmark {
			content: "\f3fd";
		}

		.card {
			/* background-color: #f4f9ff !important ; */
		}

		.progress {
			/* display: none; */
			/* position: relative; */
			/* margin: 20px; */
			/* width: 400px; */
			background-color: #ddd;
			/* border: 1px solid blue; */
			/* left: 15px; */
			/* padding: 1px; */
			/* border-radius: 3px; */
		}

		.progress-bar {
			background-color: #007bff;
			width: 0%;
			height: 30px;
			border-radius: 4px;
			-webkit-border-radius: 4px;
			-moz-border-radius: 4px;
		}
	</style>

</head>

<body>
	<?php
	if ($is_error) {
		?>
			<div class="alert alert-success"><?php echo $error_msg ?></div>
		<?php
	} else {
	?>
		<div class="card margin shadow" style="background-image:url('assets/img/bg-2.png'); background-size:cover">
			<div class="card-body text-center" style="text-align:left !important;">
				<div class="alert alert-warning msgUpload"  style="display:none"></div>
				<form id="uploadForm" name="frmupload" enctype="multipart/form-data">
					<input type="hidden" name="uid" value="<?php echo  $user_id ?>">

					<div id="step1">
                        <div class="alert alert-warning" style="font-size: 14px;">*Wajib memasukkan IG yang valid. Karena akan di lakukan pengecekan !</div>
                        <div class="form-group" >
                            <label for="" style="font-size: 16px;">Masukan username ig kamu</label>
                            <input type="text" class="form-control" name="ig" id="ig">
                        </div>
                        <button class="btn btn-block btn-success" type="button" onclick="step1()">Step Selanjutnya</button>
                    </div>

                    <div id="step2" style="display: none;">
                        <div class="form-group" >
                            <label for="" style="font-size: 16px;">Berikan rating yg bertuliskan pantun karya kamu</label>
                        </div>
                        <a style="margin-top: -20px;" href="https://play.google.com/store/apps/details?id=net.bukakiosapps" class="btn btn-primary btn-block" target="_blank">Berikan Rating &nbsp;<i class="fa fa-external-link-square"></i></a>

                        <button style="margin-top: 20px;" class="btn btn-block btn-success" type="button" onclick="step2()">Step Selanjutnya</button>
                    </div>

					<div id="step3" style="display: none;">
                        <div class="form-group" >
                            <label for="" style="font-size: 16px;">Upload Screenshoot Rating dan Pantun Kamu</label>
                            <div class="input-group" style="margin-top:20px; font-size:12px !important">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroupFileAddon01" style="font-size:12px !important">Upload</span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="image" id='imgFile' aria-describedby="inputGroupFileAddon01">
                                    <!-- <input type="file" id="uploadImage" name="uploadImage" /> -->
                                    <label class="custom-file-label" for="inputGroupFile01">Pilih file</label>
                                </div>
                            </div>
                        </div>
                        <button style="margin-top:-10px; margin-left:-1px" type="submit" class="btnImage btn btn-primary btn-block" name="btnSubmit">Submit&nbsp;<i class="fa fa-paper-plane"></i></button>
                    </div>
				</form>
			</div>
		</div>
	<?php
	}
	?>

	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"> </script>
	<!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script> -->
	<!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script> -->
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {

			$(document).on('submit', '#uploadForm', function(e) {
				document.cookie = "name=asu";
				e.preventDefault();

				var extension = $('#imgFile').val();
				var ig = $('#ig').val();
				if (extension == '' || ig == '') {

					$('.msgUpload').html("Mohon Melengkapi Semua Data");

					$('#imgFile').focus();
					$('.msgUpload').show();
					return false;
				} else {
					$('.btnImage').html('Uploading..');
					$('.btnImage').prop("disabled", true);

					$.ajax({
						url: "https://assets.bukakios.net/rating_bukakios/api_upload_event_rating_pantun.php",
						method: 'POST',
						data: new FormData(this),
						contentType: false,
						processData: false,
						dataType: 'json',
						success: function(data) {

							console.log(data);
							if (data.status == "1") {
								$('.msgUpload').html(data.msg);
								$('.msgUpload').show();
							} else {
								$('.msgUpload').html(data.error);
								$('.msgUpload').show();
							}
							setTimeout(function() {
								location.reload();
							}, 3000);
							$('.btnImage').html('Submit');
							$('.btnImage').prop("disabled", false);
						}
					});
				}

			});

		});

        function step1() {
            var ig = $('#ig').val();
            if (ig == ""){
                $('.msgUpload').html("Mohon Melengkapi Data");
                $('.msgUpload').show();
                return
            }
            $('.msgUpload').hide();
            $('#step1').hide();
            $('#step2').show(); 
        }

        function step2() {
            $('#step2').hide();
            $('#step3').show(); 
        }
	</script>

</body>

</html>