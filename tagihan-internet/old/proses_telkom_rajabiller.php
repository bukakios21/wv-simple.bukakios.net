<?PHP
$act = $_REQUEST['act'];
if($act=='cek'){
	//cek tagihan
	
	if(isset($_GET['id_pelanggan'],$_GET['csrf'])){
		$csrf = $_GET['csrf'];
		$id_pelanggan = str_replace(" ","",$_GET['id_pelanggan']);
		$id_pelanggan = htmlentities($id_pelanggan);
		$session_cache = "$code"."-".md5("$code-$id_pelanggan");
		if($csrf!=$_SESSION['csrf']){
			//$return = array("status"=>0,"error_msg"=>"CSRF Wrong!, silahkan refresh halaman ini");
			$return = array("status"=>0,"error_msg"=>"Silahkan Masuk Kembali ke Halaman Ini");
			echo json_encode($return);
			exit;
		}
		if(isset($_SESSION[$session_cache])){
			//session.....
			$return = $_SESSION[$session_cache];
		}else{
			$data_post = array( 
				"key"=>$api_key,
				"code"=>"$code",
				"id_pelanggan"=>$id_pelanggan,
				"uid"=>$user_id,
				"sleep_time"=>3
			);
			// echo var_dump($data_post);
			$query_url = http_build_query($data_post);
			$respon = $app->curl_post("$api_url/_rajabiller/telkom_cek.php", $data_post);
			// exit;
			$rsp = json_decode($respon,true);
			// echo $rsp['status'];
			// $rsp[] = $rsp['data'];
			// e$rsp['data']['nama_pelanggan'];
			// echo var_dump($rsp);
            // exit;
			if(isset($rsp['status'])){
				if($rsp['status']==1){
					$data_r['status']=1;
					
					// $sn = $rsp['sn']; //pecahkan sn ini 
					// $sn_split = explode("/",$sn);
					// //AZARINE YUSRIYAH ASHALINA/RP104500/1BLN/Apr19/ADM2500/BPJSRef:D0FA83E9FE8EC9EE
					//translate sendiri info tagihan nya
					// $total_tagihan = filter_var($sn_split[1],FILTER_SANITIZE_NUMBER_INT);
					// $biaya_admin = filter_var($sn_split[4],FILTER_SANITIZE_NUMBER_INT);
					// $lembar_tagihan = filter_var($sn_split[2],FILTER_SANITIZE_NUMBER_INT); 
					// $row['profit'] = $profit*$lembar_tagihan;
					// $row['nama_pelanggan'] = $sn_split[0];
					// $row['total_tagihan'] = $total_tagihan;
					// $row['jumlah_tagihan'] = $lembar_tagihan;//jumlah bulan tagihan 1 bulan 2 bulan?
					// $row['biaya_admin'] = $biaya_admin;
					// $row['periode'] = $sn_split[3];
					// $row['tagihan_produk'] = $total_tagihan-$biaya_admin; //ini untuk struk
					// $row['tagihan_client'] = $total_tagihan-$profit; //ini yang akan di gunakan untuk memotong saldo
					$total_tagihan = $rsp['data']['tagihan'] +$rsp['data']['admin'] ;
					$row['nama_pelanggan'] = $rsp['data']['nama_pelanggan'];
					$row['id_pelanggan'] = $rsp['data']['id_pelanggan'];
					$row['profit'] = $profit;
					$row['ref1'] = $rsp['data']['ref1'];
					$row['ref2'] = $rsp['data']['ref2']; 
					$row['ref3'] = $rsp['data']['ref3'];
					$row['saldo_t'] = $rsp['data']['saldo_t'];
					$row['periode'] = $rsp['data']['periode'];
					$row['admin'] = $rsp['data']['admin'];
					$row['tagihan'] = $rsp['data']['tagihan'];
					$row['total_tagihan'] = $total_tagihan;
					$data_r['data'] = $row;
					
					$return = json_encode($data_r);
					$_SESSION[$session_cache] = $return;
				}else{
					if(isset($rsp['error_msg'])){
						$error_msg = $rsp['error_msg'];
					}else{
						$error_msg = "Ada kesalahan di server kami yang tidak di ketahui penyebabnya, silahkan coba lagi nanti";
					}
					$return = json_encode(array("status"=>0,"error_msg"=>$error_msg));
				}
			}else{
				$return = json_encode(array("status"=>0,"error_msg"=>"Server kami sedang kepenuhan, silahkan di coba kembali!"));
			}
		}
		echo $return;
	}
}else if($act=='bayar'){
	// echo "ada";
	// exit;
	if(isset($_GET['id_pelanggan'],$_GET['csrf'])){
		$csrf = $_GET['csrf'];
		$id_pelanggan = htmlentities($_GET['id_pelanggan']);
		$session_cache = "$code"."-".md5("$code-$id_pelanggan");
		$inquiry_id = $_GET['inquiry_id'];
		if($csrf!=$_SESSION['csrf']){
			$return = array("status"=>0,"error_msg"=>"CSRF Wrong!, silahkan refresh halaman ini");
			echo json_encode($return);
			exit;
		}
		if(isset($_SESSION[$session_cache])){
			$data_json = $_SESSION[$session_cache];
			$datanya = json_decode($data_json,true);
			// echo var_dump($datanya);
			// exit;
			if(isset($datanya['data'])){
				//doooo
				$data_post = array(
					"key"=>$api_key,
					"id_pelanggan"=>$id_pelanggan,
					"uid"=>$user_id,
					"data_json"=>base64_encode($data_json),
					"code"=>$code,
					"uid_secret"=>$user_token_trx
				);
				$respon = $app->curl_post("$api_url/_rajabiller/telkom_bayar.php",$data_post);
				$rsp = json_decode($respon,true);
			}else{
				$respon = json_encode(array("status"=>0,"error_msg"=>"Gagal melakukan pembayaran, tidak dapat mendapatkan sesssion data tagihan, silahkan coba lagi!"));
			}
		}else{
			$respon = json_encode(array("status"=>0,"error_msg"=>"Gagal melakukan pembayaran, tidak dapat mendapatkan data tagihan, silahkan coba lagi!"));
		}
		echo $respon;
	}
}
exit;
?>