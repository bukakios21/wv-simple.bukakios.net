<?PHP
$auto_connect = 1; //auto connect database;
require_once("../config.php");
require_once("../_session.php");
$id_produk = 840;//telkom
$detail_produk_a = $app->grab_data("$api_url/produk_detail.php?key=$api_key&id=$id_produk");
$detail_produk_a = json_decode($detail_produk_a,true);
if(!isset($detail_produk_a['data'])){
	echo "Ada sedikit masalah di server kami, silahkan di ulangi kembali! <a href='home.html'>Kembali</a>";
	exit;
}else{
	$detail_produk = $detail_produk_a['data'];
	$code = $detail_produk['code'];
	$price = $detail_produk['price'];
	$price_add = $detail_produk['price_add'];
	$price_admin = $detail_produk['price_admin'];
	$product_logo = $detail_produk['product_logo'];
	$profit = str_replace("-","",$price)-$price_add;
	$biaya_admin = 2500 - $profit;
}
$file_sn = "telkom";
if(isset($_REQUEST['act'])){
	require_once("proses_telkom.php");
}else{
	$csrf = $app->csrf();
}

?>
<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>Bayar Tagihan Telkom - <?PHP echo $c_name; ?></title>
	<link rel="stylesheet" href="https://member.bukakios.net/css/separate/vendor/bootstrap-select/bootstrap-select.min.css">
	<link rel="stylesheet" href="https://member.bukakios.net/css/separate/vendor/select2.min.css">
	<link rel="stylesheet" href="<?=$c_url?>/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://wv.bukakios.net/assets/css/font-awesome.min.css">

	<style>
	.img-produk{
		max-height:80px;
	}
	.col-md-12 {
		/* padding:15px; */
		border:none !important;
        /* padding:0px !important; */
	}
    .row {
        padding:5px;
    }
	</style>
</head>
<body>
	
	<div class="page-content">
	    <div class="container-fluid">
			<div class='row'>
				
				<div class="col-md-12">
					<section class="card mb-3">
						<header class="card-header card-header-lg">
							<i class="fa fa-bank"></i> Bayar Tagihan TELKOM
						</header>
						<div class="card-block">
							<div class="row">
								<div class="col-md-12">
                                <div class="alert alert-info">Potensi keuntungan kamu <b>Rp. 700</b> , biaya admin telkom di kami hanya <b>Rp. 1.800</b></div>
                                <div id="form_cek" style="display:block">	
                                    <fieldset class="form-group">
                                        <label class="form-label" for="nomor">Masukan Nomor Pelanggan *</label>
                                        <input id="nomor" name="nomor" type="text" class="form-control" placeholder="123456" required="">
                                        <small class="text-mute">* Nomor Pelanggan berupa angka</small>
                                    </fieldset>
                                    <button id="cek_tagihan" class="btn btn-primary">Cek Tagihan</button>
                                    
                                </div>
																		
								<div id="form_detail_tagihan" style="margin-top:20px; display:none">
                                        <h4>Detail Tagihan</h4>
                                        <div class="card" style="padding:20px;margin-top:20px">
                                            Tagihan untuk pelanggan : 
                                            <div class="user-card-row" style="margin-top:10px">
                                                <div class="tbl-row">
                                                    <div class="tbl-cell tbl-cell-photo tbl-cell-photo-64">
                                                        <a href="#">
                                                            <img style="max-width:100px" id="user_gambar" src="https://farm5.staticflickr.com/4913/31233670747_3a0fe03aa5_b.jpg" alt="">
                                                        </a>
                                                    </div>
                                                    <div class="tbl-cell">
                                                        <p class="user-card-row-name font-16"><a href="#" id="user_nama">...</a></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div style="margin-top:-20px; ">
                                            <table class="table table-bordered table-hover" style="margin-bottom:20px">
                                                <thead>
                                                    <tr>
                                                        <th colspan="3"><center>Informasi tagihan</center></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr class="">
                                                        <td>ID Pelanggan</td>
                                                        <td id="r_id_pelanggan"></td>
                                                    </tr>
                                                    <tr class="">
                                                        <td>Jumlah Tagihan </td>
                                                        <td id="r_jumlah_tagihan"></td>
                                                    </tr>
                                                    <tr class="">
                                                        <td>Periode Tagihan </td>
                                                        <td id="r_periode"></td>
                                                    </tr>
                                                    <tr class="">
                                                        <td>Total Tagihan Pengguna</td>
                                                        <td id="r_tagihan_pengguna"></td>
                                                    </tr>
                                                    <tr class="">
                                                        <td>Biaya Admin </td>
                                                        <td id="r_biaya_admin"></td>
                                                    </tr>
                                                    <tr class="">
                                                        <td>Profit Kamu </td>
                                                        <td id="r_profit"></td>
                                                    </tr>
                                                    <tr class="">
                                                        <td><b>Total Bayar Kamu</b></td>
                                                        <td id="r_total_bayar"></td>
                                                    </tr>
                                                    </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                        <div class="col-md-6 col-xs-9 mx-auto">
                                            <center>Total saldo kamu Berkurang : <br>
                                            <div class="card" style="padding:20px">
                                                <h3 style="color:red;margin-bottom:0px" id="r_total_bayar2"></h3>
                                            </div></center>
                                        </div>
                                        <div class="col-md-6 col-xs-9 mx-auto">
                                            <center>Total Bayar Pelanggan Kamu : <br>
                                            <div class="card" style="padding:20px">
                                                <h3 style="color:green;margin-bottom:0px" id="r_total_tagihan"></h3>
                                            </div></center>
                                        </div>
                                        </div>
                                        <!--
                                        <div class='row'>
                                            <div class='col-md-12'>
                                                <fieldset class="form-group">
                                                    <label class="form-label" for="sell_price">Ubah Biaya Admin? </label>
                                                    <input id="sell_price" name="sell_price" type="text" class="form-control" placeholder="" value='0'/>
                                                    <div class='alert alert-info' style='margin-top:10px'>
                                                    Ini untuk merubah biaya admin di struk toko kamu, tanpa merubah biaya admin ini sebenarnya kamu sudah mendapatkan profit sebesar <b id=''>Rp 700</b>, karna biaya admin dari bukakios untuk tagihan ini hanya sebesar <b id='admin_bukakios'>1800</b>.
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div> -->
                                        * Pastikan data tagihan sudah sesuai :), setelah itu klik bayar tagihan
                                        <button id="bayar_tagihan" class="btn btn-success btn-block">Bayar Tagihan</button>
                                    </div>
								</div>
							</div>
							
						</div>
					</section>
				</div>
			</div>
	    </div><!--.container-fluid-->
	</div><!--.page-content-->
	<script src="https://member.bukakios.net/js/lib/jquery/jquery-3.2.1.min.js"></script>
	<script src="https://member.bukakios.net/js/lib/bootstrap-notify/bootstrap-notify.min.js"></script>
	<script src="https://member.bukakios.net/js/lib/notie/notie.js"></script>
	<script type='text/javascript'>
        $(document).ready(function() {
		
            function rubah_rp(angka){
            var reverse = angka.toString().split('').reverse().join('');
            ribuan = reverse.match(/\d{1,3}/g);
            ribuan = ribuan.join('.').split('').reverse().join('');
            return "Rp. "+ribuan;
            }
            
            var id_pelanggan = $("#nomor"); //input
            var cek_tagihan = $("#cek_tagihan"); //button
            var bayar_tagihan = $("#bayar_tagihan"); //button
            var trx_id = "";
            var inq_id = "";
            var form_cek = $("#form_cek");
            var form_detail_tagihan = $("#form_detail_tagihan");
            cek_tagihan.click(function(){
                    cek_tagihan.attr('disabled', 'disabled');
                    cek_tagihan.html("<i class='fa fa-spinner'></i> Mohon Tunggu...");
                    $.ajax({url: 'index.html?act=cek&id_pelanggan='+id_pelanggan.val()+'&csrf=<?PHP echo $csrf; ?>',
                        success: function(output) {
                            console.log(output);
                            var myJsn = JSON.parse(output);
                            if(myJsn.status==1){
                                cek_tagihan.attr('disabled', 'disabled');
                                cek_tagihan.html("Cek Tagihan");
                                var dataJsn = myJsn.data;
                                // alert(dataJsn.periode);
                                form_cek.hide();
                                form_detail_tagihan.show();
                                var admin_bukakios = dataJsn.biaya_admin-dataJsn.profit;
                                $("#user_nama").html(dataJsn.nama_pelanggan);
                                //$("#user_daya").html(dataJsn.tarif_daya);
                                $("#r_id_pelanggan").html(id_pelanggan.val());
                                $("#r_jumlah_tagihan").html(rubah_rp(dataJsn.tagihan));
                                $("#r_tagihan_pengguna").html(rubah_rp(dataJsn.total_tagihan));
                                $("#r_total_tagihan").html(rubah_rp(dataJsn.total_tagihan));
                                $("#r_biaya_admin").html(rubah_rp(dataJsn.admin));
                                $("#r_profit").html(rubah_rp(dataJsn.profit));
                                //$("#profit_info").html(rubah_rp(dataJsn.profit));
                                $("#r_periode").html(dataJsn.periode);
                                //$("#admin_bukakios").html(rubah_rp(admin_bukakios));
                                var total_bayar = dataJsn.total_tagihan-dataJsn.profit;
                                var html_total_bayar = rubah_rp(dataJsn.total_tagihan)+" - "+rubah_rp(dataJsn.profit)+" = <b>"+rubah_rp(total_bayar)+"</b>";
                                $("#r_total_bayar").html(html_total_bayar);
                                $("#r_total_bayar2").html(rubah_rp(total_bayar));
                                $("#sell_price").val(rubah_rp(dataJsn.biaya_admin));
                            }else{
                                cek_tagihan.html("Cek Tagihan");
                                cek_tagihan.removeAttr('disabled');
                                form_cek.show();
                                form_detail_tagihan.hide();
                                $.notify({
                                    icon: 'font-icon font-icon-warning',
                                    title: '<strong>Ada Masalah!</strong>',
                                    message: myJsn.error_msg
                                },{
                                    type: 'danger'
                                });
                                notie.alert(3, myJsn.error_msg, 60);
                                
                            }
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.status + " "+ thrownError);
                    }});
            }); 
            
            bayar_tagihan.click(function(){
                
                    bayar_tagihan.attr('disabled', 'disabled');
                    bayar_tagihan.html("Mohon menunggu...");
                    $.ajax({url: 'index.html?act=bayar&id_pelanggan='+id_pelanggan.val()+'&csrf=<?PHP echo $csrf; ?>',
                        success: function(output) {
                            //alert(output);
                            console.log(output);
                            var myJsn = JSON.parse(output);
                            if(myJsn.status==1){
                                bayar_tagihan.html("Sukses, Tagihan Sudah Di Bayar");
                                bayar_tagihan.attr('disabled', 'disabled');
                                setTimeout(function(){ window.location.href = "success.php?desc="+myJsn.message}, 1000);
                            }else{
                                bayar_tagihan.html("Bayar Tagihan");
                                bayar_tagihan.removeAttr('disabled');
                                setTimeout(function(){ window.location.href = "error.php?desc="+myJsn.error_msg}, 1000);
                            }
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.status + " "+ thrownError);
                    }});
                
            }); 
            
        });
	</script>
	
</body>
</html>