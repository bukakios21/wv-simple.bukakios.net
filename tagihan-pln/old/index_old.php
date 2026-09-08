<?php
header("location:pln2.php");
exit;
require_once("../config.php");
// session_destroy();
require_once("../_session.php");

// if ()

$file_me = "index.php";
$id_produk = '1454'; //data static id produk jarang berubah
$data_post_produk = array(
	"key"=>$api_key,
	"id"=>$id_produk
);
$data_produk = $app->curl_post("$api_url/v1/detail_produk.php",$data_post_produk);
$data_produk = json_decode($data_produk, true);
if(isset($data_produk['status']) and $data_produk['status']==1){
	$data_produk = $data_produk['data'];
	$code = $data_produk['code'];
	$product_logo = $data_produk['product_logo'];
	$profit = str_replace("-","",$data_produk['harga_jual']);
	$price_sell = $data_produk['price_sell'] - 1000;
}else{
	$error_msg = "Server untuk mendapatkan data produk gagal di muat... silahkan coba lagi beberapa saat!!";
	if(isset($data_produk['error_msg'])){
		$error_msg = $data_produk['error_msg'];
	}
	$html_title = "Gagal";
	$lyt_button_link = "$c_url/tagihan-pln/";
	$lyt_button_name = "COBA LAGI";
	$lyt_image = "https://assets.bukakios.net/img/illustration/bc_trx_gagal.png";
	$lyt_title = "Ada Kesalahan!";
	$lyt_description = $error_msg;
	require_once(ROOT."/_template/general_message.php");
	exit;
}
if (isset($_REQUEST['msg'])) {
    require_once '_act.php';
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Nunito" />
        <link rel="stylesheet" href="../assets/css/font-awesome.min.css">
        <title>title::Bayar Tagihan Listrik</title>
        <style>
            .footer {
                position: fixed;
                left: 0;
                bottom: 0;
                width: 100%;
                background-color: #fff;
                color: white;
                border-top: 1px solid #3498db;
                text-align: center;
            }

            .aa {
                background-color:<?=$primary?>;
            }

            .text-aa {
                color: <?=$primary?>;
            }
            body {
                font-family: Nunito;
            }

            .loader {
                border: 16px solid #f3f3f3;
                border-radius: 50%;
                border-top: 16px solid #3498db;
                width: 50px;
                height: 50px;
                -webkit-animation: spin 5s linear infinite; /* Safari */
                animation: spin 2s linear infinite;
            }

                /* Safari */
            @-webkit-keyframes spin {
                0% { -webkit-transform: rotate(0deg); }
                100% { -webkit-transform: rotate(360deg); }
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>
    </head>
  <body class="bdy">
    <div style="background-color:<?=$primary?>;">
            <div class="mx-auto py-5 mb-2 text-center">    
                <img  src="<?=$c_url?>/assets/img/tagihan_pln/listrik.png" >
            </div>
    </div>
    <div class="container" style="margin-bottom:60px;">
        <div class="row ">
            <div class="py-3 mx-auto col-md-10">
                <span class="font-weight-bold">No. Pelanggan</span>
                <div class="row mt-2">
                    <div class="col-10">
                        <input type="number" id="nope" class="form-control" placeholder="Masukkan Nomor Tagihan" required>
                        <input type="hidden" id="csrf" class="form-control" value="<?=$app->csrf()?>">
                    </div>
                    <div class="col-auto">
                        <a id="inpo" onclick="custom()"><img class="mt-1 " src="<?=$c_url?>/assets/img/tagihan_pln/info.svg" width="30px"></a>
                    </div>
                </div>
                <span class="font-weight-light text-muted" style="font-size:14px">Silahkan Masukkan No. Pelanggan</span>
            </div>
        </div>
            <div class="loader mx-auto mt-4" id="load" style="display:none;"></div>
            <span class="text-danger" id="error" style="display:none"></span>
			
			<div id='detail_tagihan' style='display:none'>
				<h4 class="text-center aa text-white py-1">Detail Tagihan</h4>
				<div class="px-3 mt-2 mx-auto">
					<table class="table" >
						<tr>
							<td width="55%%" style="font-size:20px">Total Tagihan</td>
							<td><span class="text-aa" id="tot_ta" style="float:right;font-size:20px"></span></td>
						</tr>
						<tr>
							<td width="55%%" height="10px" >Nama Pelanggan</td>
							<td height="10px"><span style="float:right;" id="nama" ></span></td>
						</tr>
						<!-- <tr>
							<td width="55%%" height="10px" >Tarif Daya</td>
							<td height="10px"><span style="float:right;" id="tarif_daya" ></span></td>
						</tr> -->
						<tr>
							<td width="55%%" >Profit</td>
							<td><span style="float:right;" id="profit"></span></td>
						</tr>
						<tr>
							<td width="55%%" >Biaya Admin</td>
							<td><span style="float:right;" id="biaya"></span></td>
						</tr>
						<!-- <tr>
							<td width="55%%" >Denda</td>
							<td><span style="float:right;" id="denda"></span></td>
						</tr> -->
						<tr>
							<td width="55%%" >Bulan/Tahun</td>
							<td><span style="float:right;" id="periode"></span></td>
						</tr>
						<tr>
							<td width="55%%" >Total Bayar Kamu</td>
							<td><b></b><span style="float:right;color:green;font-size:20px" id="tot_ka" ></span></b></td>
						</tr>
					</table>
				</div>

                <div class="card shadow">
                    <div class="card-body text-center" style="text-align:left !important;">
                        <div>
                            <h2 class="card-title colorku" style="text-align:center; "><b><span style="font-size:15px;">Total saldo kamu berkurang</span></b></h2>
                            <div class="alert alert-info" id="tot_ka2"></div>
                        </div>

                    </div>
                </div>


                <div class="card shadow" style="margin-top:20px; margin-bottom:20px">
                    <div class="card-body text-center" style="text-align:left !important;">
                        <div>
                            <h2 class="card-title colorku" style="text-align:center;"><b><span style="font-size:15px; ">Total bayar pelanggan kamu</span></b></h2>
                            <div class="alert alert-info" id="tot_ta2"></div>
                        </div>

                    </div>
                </div>


                <div class="card shadow">
                    <div class="card-body text-center" style="text-align:left !important;">
                        <div>
                            <h2 class="card-title colorku" style="text-align:center; "><b><span style="font-size:15px;">Biaya admin rekomendasi</span></b></h2>
                            <input type="text" class="form-control" id="biaya_profit"  name="biaya_profit" value="<?php  echo $price_sell ?>">
                            <small><b>Rp. <?php echo $price_sell ?> adalah keuntungan bersih untuk kamu</b></small>
                        </div>

                    </div>
                </div>


			</div> <!-- detail_tagihan -->
    </div>
    <div class="footer">
        <div class="mx-3 mb-2">
            <div id="fotfot" class="row mt-2" style="display:none">
                <div class="col-6">
                    <button class="btn btn-md aa text-white btn-rounded btn-block" style="display:none;background-color:<?=$primary?>;color:#fff" id="pay">Bayar</button>
                </div>
                <div class="col-6">
                    <button class="btn btn-md aa text-white btn-rounded btn-block" style="display:none;background-color:<?=$danger?>;color:#fff" id="cancel">Batal</button>
                </div>
            </div>
            
            <div id="payload" class="row mt-2" style="display:none">
                <div class="col-2">
                    <div class="loader" id="loadfot" style="display:none;"></div>
                </div>
                <div class="col-auto my-auto">
                    <marquee><span id="mar" style="display:none;color:#000">Silahkan Tunggu Beberapa saat</span></marquee>
                </div>
            </div>
            
            <button class="btn btn-md aa text-white btn-block btn-rounded" style="display:none;background-color:<?=$danger?>;color:#fff" id="lol">Batal</button>
            <button class="btn btn-md btn-block" id="cek" style="background-color:<?=$primary?>;color:#fff">Cek Tagihan</button>
        </div>
    </div>
        <script src="../assets/js/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="../assets/js/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="../assets/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="../assets/js/jquery.js"></script>
        <script src="../assets/js/sweetalert.min.js"></script>
		<script src="../assets/js/sweetalert2.min.js"></script>
        <script>
            function custom() {
                Swal.fire({
                    title: "<b>Cek Nomor Listrik Anda</b>", 
                    html: "Melihat ID pelanggan PLN yang terdiri dari 11 hingga 12 digit angka pada meteran listrik. jika sulit dilakukan silahkan melihat struk tagihan atau struk pembelian token listrik sebelumnya <br/><img src='<?=$c_url?>/assets/img/tagihan_pln/antenna.png' class='mt-2' width='100px'>",  
                    confirmButtonText: "OK", 
                });
            }
        </script>
        <script>
        $(document).ready(function() {
            var bilangan = "";
            var	number_string = "";
            function rubah_rp(){
               
                number_string =  bilangan.toString(),
                    sisa 	= number_string.length % 3,
                    rupiah 	= number_string.substr(0, sisa),
                    ribuan 	= number_string.substr(sisa).match(/\d{3}/g);
                        
                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }
                return "Rp. "+rupiah;
            }

            var trx_id = "";
		    var inq_id = "";
            

            $('#cek').on('click', function(){
                var id_pelanggan = $('#nope').val();
                var csrf = $('#csrf').val();
                // console.log(csrf);
                $('#cek').hide();
                document.getElementById("nope").disabled = true;
                $('#load').show(); 
                $('.footer').hide(); 
                $.ajax({
                    url: '<?=$file_me?>?msg=cek&id_pelanggan='+id_pelanggan+'&csrf='+csrf,
                    success: function(output) {
                        $('.footer').show(); 
                        $('#load').hide(); 
                        var myJsn = JSON.parse(output);
                        console.log(myJsn.status);
                        var d = myJsn.data;
                        if(myJsn.status==1){
                            var dataJsn = myJsn.data;
                            console.log(dataJsn.admin);
                            // button 
                            $('#cancel').show(); 
                            $('#fotfot').show();
                            $('#pay').show();
                            // form
                            $('#detail_tagihan').show();

                            var total_bayar = dataJsn.total_tagihan-dataJsn.profit;
                            bilangan = total_bayar;
                            console.log(rubah_rp());
                            bilangan = dataJsn.total_tagihan;
                            $("#tot_ta").html(rubah_rp());
                            $("#nama").html(dataJsn.nama);
                            bilangan = dataJsn.profit;
                            $("#profit").html(rubah_rp());
                            bilangan = dataJsn.admin;
                            $("#biaya").html(rubah_rp());
                            $("#biaya_admin").val( dataJsn.admin);
                            // $("#denda").html(rubah_rp(dataJsn.denda));
                            $("#periode").html(dataJsn.bl_th);
                            // $("#tarif_daya").html(dataJsn.tarif_daya);
                            bilangan = total_bayar;
                            $("#tot_ka").html(rubah_rp());
                            $("#tot_ka2").html(rubah_rp());
                            bilangan =  dataJsn.total_tagihan;
                            $("#tot_ta2").html(rubah_rp());
                            inq_id = dataJsn.inquiry_id;
                            trx_id =  dataJsn.inquiry_id;
							
							
						}else{
                            $('#lol').show();
                            $('#error').show();
							$("#error").html(myJsn.error_msg);
							swal(myJsn.error_msg, {
								icon: "warning"
							});
						}
                    }
                })
            });

            $('#pay').on('click', function(){
                if (inq_id!="") {
					var id_pelanggan = $('#nope').val();
					var profit = $('#biaya_profit').val();
					var csrf = $('#csrf').val();
                    console.log(inq_id);
                    console.log(trx_id);
                    $('#payload').show(); 
                    $('#loadfot').show(); 
                    $('#mar').show(); 
                    $('#fotfot').hide(); 
                    $('#pay').hide(); 
                    $('#cancel').hide();
					
					$.ajax({
						url: '<?=$file_me?>?msg=bayar&biaya_profit='+profit+'&id_pelanggan='+id_pelanggan+'&csrf='+csrf+'&trx_id='+trx_id+'&inquiry_id='+inq_id,
						success: function(output) {
							$('.footer').show(); 
							$('#load').hide(); 
							var myJsn = JSON.parse(output);
							console.log(id_pelanggan);
							var d = myJsn.data;
							if(myJsn.status==1){
								//arahkan ke pesan transaksi sukses...
								swal({
									title: 'Transaksi Berhasil',
									text: 'Transaksi anda sedang di proses. mohon menunggu. halaman akan otomatis dialihkan ketika proses selesai',
									icon: 'success'
								});
								setTimeout(function(){ window.location.href = "<?=$c_url?>/_template/session_success.php"; }, 3000);
							}else{
								var error_msg = myJsn.error_msg;
								swal(error_msg, {
									icon: "warning"
								});
								$('#payload').hide(); 
								$('#pay').show(); 
								$('#cancel').show();
								$('#fotfot').show();
							}
						}
					})
					
                }
            })

            $('#cancel').on('click', function(){
                $('#cek').show();
                document.getElementById("nope").disabled = false;

                $('#error').hide();
                $('#pay').hide();
                $('#fotfot').hide();
                $('#cancel').hide();
                $('#detail_tagihan').hide();
            });

            $('#lol').on('click', function(){
                $('#cek').show();
                document.getElementById("nope").disabled = false;

                $('#error').hide();
                $('#lol').hide();
            });
        })
        </script>
    </body>
</html>