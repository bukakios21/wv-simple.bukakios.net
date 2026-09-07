<?PHP
/**
 * @package Megablogging 5
 * @copyright 2012-2014 Megasoft Informer (http://megasoft-id.com) | License: http://megasoft-id.com/license
 * @since version 5
 */
class App{
	/**
	 * Load All Stylesheet
	 * @since v5
	 */
	private $email='', $name='';
	public function validasi_email($email) {
      return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
	public function csrf(){
		$csrf_length = 32;
		$csrf = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $csrf_length); 
		$_SESSION['csrf'] = $csrf;
		return $csrf;
	}
	
	public function random_string(){
		$length = 32;
		$random_string = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $length); 
		return $random_string;
	}
	
	public function bulatkan($angka, $precision = 1){
		if ($angka < 1000) {
        $n_format = number_format($angka);
		} else if ($angka > 1000 and $angka <= 1000000) {
			$n_format = number_format($angka / 1000, $precision) . 'K';
		} else if ($angka > 1000000 and $angka <= 1000000000) {
			$n_format = number_format($angka / 1000000, $precision) . 'M';
		} else {
			$n_format = number_format($angka / 1000000000, $precision) . 'B';
		}
		return $n_format;
	}
	
	public function load_stylesheet(){
		$f_stylesheet = TEMPLATE_DIR.'/stylesheet.php';
		$file_template=fopen($f_stylesheet, 'r');
		$data_template=fread($file_template,filesize($f_stylesheet));
		$data_template_new=str_replace('{template_url}', TEMPLATE_URL, $data_template);
		$data_template_new=str_replace('{url}', APP_URL, $data_template_new);
		return "$data_template_new";
	}
	
	/**
	 * Load All Javascript
	 * @since v5
	 */
	public function load_javascript(){
		$f_stylesheet = TEMPLATE_DIR.'/javascript.php';
		$file_template=fopen($f_stylesheet, 'r');
		$data_template=fread($file_template,filesize($f_stylesheet));
		$data_template_new=str_replace('{template_url}', TEMPLATE_URL, $data_template);
		$data_template_new=str_replace('{url}', APP_URL, $data_template_new);
		return "$data_template_new";
	}
	
	/**
	 * Show HOME PAGE
	 * @since v5
	 */
	public function _home(){
		return(TEMPLATE_DIR."/index.php");		
	}
	
	
	/**
	* Dapatkan CURRENT URL
	*/
	public function strleft($s1, $s2) {
		return substr($s1, 0, strpos($s1, $s2));
	}
	public function CURRENT_URL() {
		$s = (empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on" ? "s" : ""));
		$protocol = $this->strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s; 
		$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]); 
		return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI']; 
	}
	
	/**
	* GET CONTENT OF File utf8
	* since v5
	*/
	function open_file($fn) {
		$content = file_get_contents($fn);
		return mb_convert_encoding($content, 'UTF-8', mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
	}
	
	/**
	* SAVE COTENT OF FILE utf8
	* since v5
	*/
	function save_file($filename, $content) {
		$temp = tempnam(0777, 'temp');
		if (!($f = @fopen($temp, 'wb'))) {
			$temp = 0777 . DIRECTORY_SEPARATOR . uniqid('temp');
			if (!($f = @fopen($temp, 'wb'))) {
				trigger_error("file_put_contents_atomic() : error writing temporary file '$temp'", E_USER_WARNING);
				return false;
			}
		}
		$content = mb_convert_encoding($content, 'UTF-8', mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
		fwrite($f, $content);
		fclose($f);
	  
		if (!@rename($temp, $filename)) {
			@unlink($filename);
			@rename($temp, $filename);
		}
	  
		@chmod($filename, FILE_PUT_CONTENTS_ATOMIC_MODE);
	  
		return true;
	  
	}
	
	//remove directory
	//since version 5
	function rrmdir($dir) {
	if (is_dir($dir)) {
		 $objects = scandir($dir);
		 foreach ($objects as $object) {
		   if ($object != "." && $object != "..") {
			 if (filetype($dir."/".$object) == "dir") $this->rrmdir($dir."/".$object); else unlink($dir."/".$object);
		   }
		 }
		 reset($objects);
		 rmdir($dir);
		 return 1;
		}
		return 0;
	}
	
	//check template [ASLI APA KAGAK]
	//since version 5
	function check_template($folder_name){
		$template_dir = ROOT."/template";
		//cek file xmlnya
		if (file_exists($template_dir."/$folder_name.xml")){
			return true;
		}else{
			return false;
		}
	}
	
	function mc_encrypt($encrypt){
			$key = "63ff1cbaccb145ff27e1cf347715633b";
			$encrypt = serialize($encrypt);
			$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
			$key = pack('H*', $key);
			$mac = hash_hmac('sha256', $encrypt, substr(bin2hex($key), -32));
			$passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt.$mac, MCRYPT_MODE_CBC, $iv);
			$encoded = base64_encode($passcrypt).'|'.base64_encode($iv);
			return $encoded;
		}

		function mc_decrypt($decrypt){
			$key = "63ff1cbaccb145ff27e1cf347715633b";
			$decrypt = explode('|', $decrypt.'|');
			$decoded = base64_decode($decrypt[0]);
			$iv = base64_decode($decrypt[1]);
			if(strlen($iv)!==mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)){ return false; }
			$key = pack('H*', $key);
			$decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_CBC, $iv));
			$mac = substr($decrypted, -64);
			$decrypted = substr($decrypted, 0, -64);
			$calcmac = hash_hmac('sha256', $decrypted, substr(bin2hex($key), -32));
			if($calcmac!==$mac){ return false; }
			$decrypted = unserialize($decrypted);
			return $decrypted;
		}
		
	function send_fcm($token,$title,$body,$sound=1){
		$api_key_google = _FCM_KEY;
		$result = $this->fcm($token,$msg);
		return $result;
	}
	function fcm($token,$msg,$tipe='notification'){ //(token,array,data)
		$api_key_google = _FCM_KEY;
		if(is_array($token)){
			$registrationIds = $token;
		}else{
			$registrationIds = array($token);
		}
		$fields = array
		(
			'registration_ids' 	=> $registrationIds,
			$tipe				=> $msg
		);
		 
		$headers = array
		(
			'Authorization: key=' . $api_key_google,
			'Content-Type: application/json'
		);
		 
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );
		return $result;
	}

	function fcm_topic($topic,$msg,$tipe='notification'){ //(token,array,data)
		$api_key_google = _FCM_KEY;
		$fields = array
		(
			'to' 	=> "/topics/$topic",
			$tipe	=> $msg
		);
		 
		$headers = array
		(
			'Authorization: key=' . $api_key_google,
			'Content-Type: application/json'
		);
		 
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );
		return $result;
	}
	
	function fcm2($token,$msg,$tipe='notification'){
		if(is_array($token)){
			$registrationIds = $token;
		}else{
			$registrationIds = array($token);
		}
		if($tipe=='notification'){
			$fcmFields = array(
				'registration_ids' => $registrationIDs,
				'priority' => 'high',
				'notification' => $msg
			);
		}else{
			$fcmFields = array(
				'registration_ids' => $registrationIDs,
				'priority' => 'high',
				'notification' => $msg
			);
		}

		$headers = array(
			'Authorization: key=' . _FCM_KEY,
			'Content-Type: application/json'
		);
		 
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
		$result = curl_exec($ch );
		curl_close( $ch );
		return $result;;
	}
	
	function idr($angka){
        $jadi="Rp. ".number_format($angka,0,',','.');
        return $jadi;
    }
	
	function angka_id($angka){
        $jadi=number_format($angka,0,',','.');
        return $jadi;
    }
	
	function idr_singkat($angka,$precision = 1){
		if ($angka < 1000) {
        $n_format = number_format($angka);
		} else if ($angka > 1000 and $angka <= 1000000) {
			$n_format = number_format($angka / 1000, 0) . 'rb';
		} else if ($angka > 1000000 and $angka <= 1000000000) {
			$n_format = number_format($angka / 1000000, $precision) . 'jt';
		} else {
			$n_format = number_format($angka / 1000000000, $precision) . 'M';
		}
        $jadi="Rp. ".$n_format;
        return $jadi;
    }
	
	function time_ago($date_time){
		$cur_time 	= time();
		$time_ago = strtotime($date_time);
		$time_elapsed 	= $cur_time - $time_ago;
		$seconds 	= $time_elapsed ;
		$minutes 	= round($time_elapsed / 60 );
		$hours 		= round($time_elapsed / 3600);
		$days 		= round($time_elapsed / 86400 );
		$weeks 		= round($time_elapsed / 604800);
		$months 	= round($time_elapsed / 2600640 );
		$years 		= round($time_elapsed / 31207680 );
		if($seconds <= 60){
			$data = "$seconds detik lalu";
		}else if($minutes <=60){
			$data = "$minutes menit lalu";
		}else if($hours <=24){
			$data = "$hours jam lalu";
		}else if($days <= 7){
			$data = "$days hari lalu";
		}else if($weeks <= 4.3){
			if($weeks==1){
				$data = "seminggu lalu";
			}else{
				$data = "$weeks minggu lalu";
			}
		}else{
			$time = substr($date_time, -8, 8);
			$date = substr($date_time, 0, 10);
			$data = "$date - $time";
		}
		return $data;
	}
	
	function size_from_url($url){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		curl_setopt($ch, CURLOPT_NOBODY, TRUE);
		$data = curl_exec($ch);
		$size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
		curl_close($ch);
		return $size;
	}
	
	function filesize_string($size_in_bytes){ #in bytes (B)
		$hasil_size_in_bytes = "Unknow KB";
		if ($size_in_bytes>1024 and $size_in_bytes < 1024000){
			$size_in_bytes=$size_in_bytes/1024;
			$me=substr($size_in_bytes,0,4);
			$hasil_size_in_bytes="$me KB";
		}
		
		if ($size_in_bytes>1024000 and $size_in_bytes < 1024000000){
			$size_in_bytes=$size_in_bytes/1024000;
			$me=substr($size_in_bytes,0,4);
			$hasil_size_in_bytes="$me MB";
		}
		if ($size_in_bytes>1024000000){
			$size_in_bytes=$size_in_bytes/1024000000;
			$me=substr($size_in_bytes,0,4);
			$hasil_size_in_bytes="$me GB";
		}
		return $hasil_size_in_bytes;
	}
	
	function my_gdrive($gdrive_id){
		$gdrive_id=str_replace("/view?usp=sharing","",$gdrive_id);
		$grab_json = $this->grab_data("http://gd.drakor.id/gdrive/api.php?id=$gdrive_id&key=6327d822e995250e6da4c10c6608ee7a");
		$hasil_grab = json_decode($grab_json,true);
		return $hasil_grab;
	}
	
	function buka_file($path){
		if(file_exists($path)){
			return file_get_contents($path);
		}else{
			return json_encode(array("error"=>"file not found"));
		}
	}
	
	function simpan_file($path,$content){
		file_put_contents($path, $content);
	}
	
	function my_curl($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_REFERER, 'http://drakor.id');
		curl_setopt($ch, CURLOPT_USERAGENT, 'bukakios-curl-webview 98bc73c6c70a8bc7936cb86565c0cdbf new'); 
		$html = curl_exec($ch);
		return $html;
	}

	function contains($soource,$partof){
		$hasil_validate=substr_count($soource, $partof);
		if($hasil_validate>0){
			return true;
		}else{
			return false;
		}
	}
	
	public function grab_data($url,$timeout=15){
		$ch      = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, "bukakios-curl-webview 71b97cdb7dc7a7bbe205c48d5241d64b new");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	
	function get_http_content_type($theURL) {
		$headers = get_headers($theURL);
		return substr($headers[3], 14);
	}
	
	function get_http_response_code($theURL) {
		$headers = get_headers($theURL);
		return substr($headers[0], 9, 3);
	}
	
	function splitid($url){
	  if(preg_match("/file\/d\/([0-9a-zA-Z-_]+)\//", $url)){
		preg_match("/file\/d\/([0-9a-zA-Z-_]+)\//", $url, $mach);
		$gid = $mach[1];
	  }
	  else if(preg_match("/file\/d\/([0-9a-zA-Z-_]+)/", $url)){
		preg_match("/file\/d\/([0-9a-zA-Z-_]+)/", $url, $mach);
		$gid = $mach[1];
	  }
	  else if(preg_match("/id=([0-9a-zA-Z-_]+)/", $url)){
		  preg_match("/id=([0-9a-zA-Z-_]+)/", $url, $mach);
		  $gid = $mach[1];
	  } else {
		$gid = $url;
	  }

	  return $gid;
	}
	
	function curl_post($url,$data){
		$ch = curl_init();
		$header = array(
		"auth: bukakios-x98x"
		);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT,'bukakios-curl-webview 71b97cdb7dc7a7bbe205c48d5241d64b new');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec($ch);
		$return = $result;
		curl_close ($ch);
		return $return;
		
	}
	
	function roundUpHargaJual($n, $increment = 2000)
	{
		if($n>50000){
			$penambah = 0;
		}else{
			$penambah = $increment - 1000;
		}
		$hasil = (int) ($increment * ceil($n / $increment));
		return $hasil+$penambah;
	}
	
	function perbaiki_nomor($nomor){
		$tujuan = $nomor;
		$tujuan = str_replace("+628","08",$tujuan);
		$tujuan = str_replace("+","",$tujuan);
		$tujuan = str_replace("+","",$tujuan);
		$tujuan_awal = substr($tujuan,0,2);
		$ending = substr($tujuan,2);
		if($tujuan_awal==62){
			$tujuan_awal = "0";
		}
		$semua_nomor = $tujuan_awal.$ending;
		return $semua_nomor;
	}
	
	function check_string_safe($string){
		//true = string is safe to store database
		//false = don't save 
		//di gunakan untuk cek keamanan nama, email, nama toko
		$list_block_keyword = array("(",")","document","script","cookie","<",">","prompt","domain");
		$safe = true;
		foreach($list_block_keyword as $a){
			if(substr_count($string, $a)>0){
				$safe = false;
			}
		}
		return $safe;
	}

function getUserIpAddr(){
    $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    return $ip;
}

function clear_xss($input){
        // %$€×¥?/×÷;'"
        $input = str_replace("<","", $input);
        $input = str_replace(">","", $input);
        $input = str_replace("%","", $input);
        $input = str_replace("€","", $input);
        $input = str_replace("$","", $input);
        $input = str_replace("×","", $input);
        $input = str_replace("¥","", $input);
        $input = str_replace("?","", $input);
        $input = str_replace("","", $input);
        $input = str_replace("/","", $input);
        $input = str_replace("÷","", $input);
        $input = str_replace(";","", $input);
        $input = str_replace('\"\"',"", $input);
        $input = str_replace('"',"", $input);
        $input = str_replace('(',"", $input);
        $input = str_replace(')',"", $input);
        $input = str_replace('!',"", $input);
        $input = str_replace('?',"", $input);
        $input = str_replace("'","", $input);
        $input = str_replace("*","", $input);
        $input = str_replace("-","", $input);
        $input = str_replace("#","", $input);
        $input = str_replace("@","", $input);

        return $input;
}

  function curl_with_json($json, $url, $headers) {
        // $headers = array(
        //     "Api-Key: dd7d17b3ee283d3c698bcad38e5fe5f7"
        //   );
        // $json = array(
        //     "provider"=>"Telkomsel,XL,Axis,Tri",
        //     "nominal"=>100000
        // );
        $xml_data = json_encode($json);
        // $url = "https://sms.bukakios.net/v1/get-rate";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        // Apply the XML to our curl call
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
        $data = curl_exec($ch);
        return $data;
    }

}
?>
