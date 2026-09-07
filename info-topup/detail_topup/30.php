<?PHP
//11 = ALFAMART
//ALFAMART get info nomor rekening dari api
//pg = winpay
// echo $metode_id;exit;
$data_post = array(
	"key"=>$api_key,
	"id"=>$topup_id
);
$data_info = $app->curl_post("$api_url/get_topup_detail.php",$data_post);
$enc_again = md5(md5("$user_id-bukakios-2020"));
$data_info = json_decode($data_info,true);
if(isset($data_info['status'])){
	$pg_error = false;
	if($data_info['status']==1){
		//berhasil
		if(isset($data_info['payment_info'])){
			$payment_info = $data_info['payment_info'];
			if($payment_info['status']=='1'){
				//ada informasi nomor rekening/pembayaran
                $nomor_rekening = $payment_info['t_nomor_rekening'];
                $nominal = $payment_info['t_nominal_topup'];
                $fee = $payment_info['t_biaya_admin'];
				$total = $app->angka_id($nominal + $fee);
                $info = "WAJIB TRANSFER DENGAN NOMINAL <b>RP. $total</b> YANG TERTERA (TIDAK KURANG & TIDAK LEBIH) KE NOMOR INI <b>$nomor_rekening</b>";
			}else{
				$pg_error = true;
				$error_msg = $payment_info['error_msg'];
			}
		}else{
			$pg_error = true;
			$error_msg = "Payment info tidak ada, silahkan kontak tim kami!";
		}
	}else{
		$pg_error = true;
		$error_msg = "Ada Kesalahan -> ".$data_info['error_msg']['error_msg'];
	}
}else{
	$pg_error = true;
	$error_msg = "Server Api Respon Tidak Valid!!, silahkan kontak tim kami!";
}
?>
<?PHP if(!$pg_error){ ?>

<div id='panduanbayar'>
	<div class="pl-16 pr-16 pt-24">
		<div class="">
			<h5>Topup Pulsa</h5>
		</div>
		
		<div class="alert alert-info">
            <ul style="width:100%">
                <li style="font-size:15px"><?=$info?></li>
                <li style="font-size:15px">TOP UP INI AKAN DIKENAKAN POTONGAN SEBESAR <b>18%</b></li>
                <li style="font-size:15px"><b>WAJIB UNTUK KONFIRMASI PEMBAYARAN DENGAN CARA UPLOAD BUKTI BAYAR KAMU</b></li>
            </ul>
        </div>
		<div class="card alert alert-danger">
            <b>Kesalahan transfer <b>nominal</b> bisa menyebabkan Topup gagal</b>
        </div>
        <div class="card alert alert-warning">
        Setelah transfer, silahkan upload bukti pembayarannya kesini
        <a href="https://wv.bukakios.net/topup_bukakios/topup_pulsa.php?enc=<?=$user_id."-".$enc_again?>&id_topup=<?=$topup_id?>" class="btn btn-primary btn-block">Konfirmasi Pembayaran</a>
        </div>
		
		
	</div>
</div>
<?PHP }else{ ?>
<div class='alert alert-danger'><?PHP echo $error_msg; ?></div>
<?PHP } ?>