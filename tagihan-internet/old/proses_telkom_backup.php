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
                "tujuan"=>$id_pelanggan,
                "kode_produk"=>$code
            );
            $respon = $app->curl_post("$api_url/_pluslink/cek_postpaid.php", $data_post);
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
                    
                    $dataJsn = $rsp['data'];
                    
                    $data_r['data']['inquiryId']=$rsp['ref_2'];
                    $data_r['data']['nama_pelanggan']=$dataJsn['nama'];
                    $data_r['data']['profit'] = $profit;
                    $data_r['data']['id_pelanggan'] = $dataJsn['id_pelanggan'];
                    $data_r['data']['periode'] = $dataJsn['periode'];
                    $data_r['data']['total_tagihan'] = $dataJsn['total'];
                    $data_r['data']['tagihan'] = $dataJsn['nominal'];
                    $data_r['data']['admin'] = $dataJsn['admin'];
					
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
            
               
            if ($user_id == 39958){
                $out = array(
                    'status' => 0,
                    'error_msg' => $data_json
                );
                // echo json_encode($out);exit;
            }


			if(isset($datanya['data'])){
                $data_post = array(
                    "key"=>$api_key,
                    "tujuan"=>$id_pelanggan,
                    "uid"=>$user_id,
                    "data_json"=>base64_encode($data_json),
                    "kode_produk"=>$code,
                    "uid_secret"=>$user_token_trx,
                    "profit" => $profit
                );
                // echo json_encode($data_post);
                // exit;
                // echo var_dump($data_post);
                if ($user_id == 39958){
                    echo $respon = $app->curl_post("$api_url/_pluslink/bayar_postpaid2.php",$data_post);exit;
                }else{
                    echo $respon = $app->curl_post("$api_url/_pluslink/bayar_postpaid.php",$data_post);exit;
                }
				// $rsp = json_decode($respon,true);
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