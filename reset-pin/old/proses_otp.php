<?php
require_once('../../_session.php');
require_once('../../config.php');
if (isset($_GET['act'], $_GET['csrf'])) {
    $act = $_GET['act'];

    $user = $app->grab_data("https://api.bukakios.net/v1/data_user.php?id=$user_id&key=$api_key");
    $user = json_decode($user, true);
    $user = $user['data'];
    $nohp = $user['hp'];

    if ($act == "resend") {
        //call api reset pin
        $post = array(
            'key' => "$api_key",
            "uid" => "$user_id"
        );
        $data = $app->curl_post("https://ms0.bukakios.net/v1/reset_pin.php", $post);
        $data = json_decode($data, true);
        if ($data['status'] == 1) {
            $otp = $data['otp'];
            $out = array(
                'status' => 1,
                'message' => "OTP Berhasil dikirim",
                'otp' => $otp
                // 'message' => ""
            );
            $tipe = 2;
            $msg_p = "BukaK10s: $otp ";
            if (!isset($_SESSION['countsds'])) {
                $tipe = 1;
                $_SESSION['countsds'] = 1;
                $msg_p =  "BukaK10s: $otp :)";
            }

            $data_post_sms = array(
                "tujuan" => $nohp,
                "message" => $msg_p,
                "tipe" => $tipe
            );
            $res = $app->curl_post("$api_url/mail/send_sms.php", $data_post_sms);
        } else {
            $out = array(
                'status' => 0,
                // 'error_msg' => ""
                'error_msg' => $data['error_msg']

            );
        }
    } else if ($act == "sending") {
        $otp = $_GET['otp'];
        $post = array(
            'key' => "$api_key",
            "uid" => "$user_id",
            'otp' => $otp
        );
        echo $data = $app->curl_post("https://ms0.bukakios.net/v1/submit_pin.php", $post);
        exit;
    } else if ($act == "wa") {
        $post = array(
            'key' => "$api_key",
            "uid" => "$user_id"
        );
        $json = $app->curl_post("https://ms0.bukakios.net/v1/reset_pin.php", $post);
        $data = json_decode($json, true);
        $otp = $data['otp'];

        $post_wa = array(
            'tujuan' => $nohp,
            'otp' => $otp
        );
        // $send = $app->curl_post("$api_url/_waba/", $post_wa);
        // $app->simpan_file("res_officical_wa.txt", $send);

        require_once("../../lib/ApiV2.php");
        $api_v2 = new ApiV2();
        $res = $api_v2->send_otp_bukakios($post_wa);
        $app->simpan_file("res_wa_bukakios.txt", $res);

        $out = array(
            'status' => 1,
            'message' => "OTP Berhasil dari wa"
        );
    } else if ($act == "email") {
        $post = array(
            'key' => "$api_key",
            "uid" => "$user_id"
        );
        $json = $app->curl_post("https://ms0.bukakios.net/v1/reset_pin.php", $post);
        $data = json_decode($json, true);
        $otp = $data['otp'];

        $post_email = array(
            'uid' => $user_id,
            'otp' => $otp
        );

        $ch = curl_init();
        $header = array(
            "Api-Key: PLowElenThErTeRAphaRDwINEAntrIDe"
        );
        $url = "https://api-v2.bukakios.net/v2/postmark/reset/pin";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'bukakios-curl-webview 71b97cdb7dc7a7bbe205c48d5241d64b');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_email));
        $result = curl_exec($ch);
        curl_close($ch);

        $app->simpan_file("email_res.txt", $result);

        // $post = array(
        //     'key' => $api_key,
        //     'pesan' => "Kode OTP untuk mereset PIN kamu $otp. Jangan pernah berikan kode ini kesiapapun termasuk TIM BukaKios :)",
        //     'nohp' => $nohp
        // );

        // $data = $app->curl_post("https://api.bukakios.net/bukakios/api_wa.php", $post);

        $out = array(
            'status' => 1,
            'message' => "OTP Berhasil dari wa"
        );
    } else {
        $out = array(
            'status' => 0,
            'error_msg' => 'Invalid action'
        );
    }
} else {
    $out = array(
        'status' => 0,
        'error_msg' => 'Invalid parameter2'
    );
}

echo json_encode($out);
