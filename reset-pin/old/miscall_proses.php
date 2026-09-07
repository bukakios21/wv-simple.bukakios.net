<?php 
// require_once('../../_session.php');
// require_once('../../config.php');
require_once("../../config_redis.php");
if (isset($_GET['act'])){
    $act = $_GET['act'];

    // $user_id = 39958;
    $user = $app->grab_data("$api_url/v1/data_user.php?key=$api_key&id=$user_id");
    $user = json_decode($user, true);
    $user = $user['data'];
    $nohp = $user['hp'];

    $key_redis = "token-miscall-resetpin-$user_id";

    if ($act == "request"){    

        //call api reset pin
        $post = array(
            'key' => $api_key,
            "act" => "misscall",
            "tujuan" => "$nohp"
        );
	

        $data = $app->curl_post("$api_url/_citcall/", $post);

        $data = json_decode($data, true);
        if ($data['status'] == 1){
            $a = $data['data'];
            
            $token_api = substr($a['token'], -4);

            if ($pjg < 12) {
                if ($a['token'][0] == 6) {
                    $arr[] = substr($a['token'], 0, -10);
                    $arr[] = substr($a['token'], 2, -8);
                    $arr[] = substr($a['token'], 4, -4);
                }else {
                    $arr[] = "62";
                    $arr[] = substr($a['token'], 1, -8);
                    $arr[] = substr($a['token'], 3, -4);
                }
            }else {
                $arr[] = "62";
                $arr[] = substr($a['token'], 2, -8);
                $arr[] = substr($a['token'], 3, -4);
            }
            $aa = implode(" ", $arr);

            $_SESSION['token'] = $token_api;    

            $rediw->setex($key_redis,60*2,$token_api);    

            $out = array(
                'status' => 1,
                'message' => "Silahkan Tunggu Telepon Sebentar Lagi",
                "angka" => $aa
            );

        }else{
            $out = array(
                'status' => 0,
                'error_msg' => "Server Error"
            );
            $_SESSION['error_msg'] = "Server Error";
        }
    }
    else if($act == "sending"){
        if (!isset($_SESSION['token'])) {
            $out = array(
                'status' => 0,
                'error_msg' => "Terjadi Kesalahan Dalam Mendapatkan Data"
            );
            $_SESSION['error_msg'] = "Terjadi Kesalahan Dalam Mendapatkan Data";
            echo json_encode($out);exit;
        }
        
        $token = $_SESSION['token'];
        if (!isset($_GET['token'])) {
            $out = array(
                'status' => 0,
                'error_msg' => "Invalid Param"
            );
            $_SESSION['error_msg'] = "Invalid Param";
            echo json_encode($out);exit;
        }

        $get_token = $_GET['token']; 

        if ($get_token === $token) {

            $post = array(
                'key' => $api_key,
                'uid'=> $user_id,
                'otp' => $get_token
            );
            $send = $app->curl_post("https://ms0.bukakios.net/v1/submit_pinv2.php", $post);
            $send_res = json_decode($send, true);
            if (isset($send_res['status'])){
                if ($send_res['status']==1){
                    $out = array(
                        'status' => 1,
                        'msg' => $send_res['message']
        
                    );
                    $_SESSION['msg'] = $send_res['message'];
                }else{
                    $out = array(
                        'status' => 0,
                        'error_msg' => $send_res['error_msg']
        
                    );
                    $_SESSION['msg'] = $send_res['error_msg'];
                }
            }else{
                $out = array(
                    'status' => 0,
                    'error_msg' => "Server fatal error"
    
                );
                $_SESSION['msg'] = "Server fatal error ";
            }
            
        }else {
            $out = array(
                'status' => 0,
                'error_msg' => "Invalid token"

            );
            $_SESSION['msg'] = "Invalid token";
        }
    }
    else{
        $out = array(
            'status' => 0,
            'error_msg' => 'Invalid action'
        ); 
        $_SESSION['msg'] = "Invalid action";
    }
}else{
    $out = array(
        'status' => 0,
        'error_msg' => 'Invalid parameter2'
    );
    $_SESSION['msg'] = "Invalid parameter2";
}

echo json_encode($out);

?>