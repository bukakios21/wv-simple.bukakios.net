<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// require_once('../_session.php');
require_once('../config.php');
//require_once('../config_db.php');

$csrf = $_GET['csrf'];
if ($csrf != $_SESSION['csrf']) {
    $out = array(
        'status' => 0,
        'cc' => $csrf,
        'c' => $_SESSION['csrf'],
        'error_msg' => "Error csrf"
    );
    echo json_encode($out);
    exit;
}

if (isset($_GET['act'], $_GET['csrf'])) {
    $act = $_GET['act'];
    if ($act == "use") {
        $kode = $db->escape_string($_GET['kode']);
        if (empty($kode)) {
            $out = array(
                'status' => 0,
                'error_msg' => "Kode voucher wajib di isi!"
            );
            echo json_encode($out);
            exit;
        }
        //call api proses kode
        if ($user_id == 30009958) {
            $url = "$apiv2_url/$apiv2_slug/voucher/redem";
            $send = array(
                "uid" => $user_id,
                "kode" => $kode
            );
            $ch = curl_init();
            $header = array(
                "auth: bukakios-x98x",
                "Api-Key: OmAtIcHULeTZsChiPTIVatHyprOvenTY"
            );
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_USERAGENT, 'bukakios-curl-webview 71b97cdb7dc7a7bbe205c48d5241d64b');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $send);
            $result = curl_exec($ch);
            $return = $result;
            curl_close($ch);
            $res = json_decode($return, true);
            if (isset($res['status'])) {
                if ($res['status'] == 1) {
                    $out = array(
                        'status' => 1,
                        'message' => $res['message']
                    );
                } else {
                    $out = array(
                        'status' => 0,
                        'error_msg' => $res['error_msg']
                    );
                }
            } else {
                $out = array(
                    'status' => 0,
                    'error_msg' => "Error server. #$result"
                );
            }
        } else {
            $post = array(
                'key' => $ms0_key,
                'kode' => $kode,
                'uid' => $user_id,
                'token_trx' => $user_token_trx
            );
            $res = $app->curl_post("$ms0_url/proses_voucher.php", $post);
            $res = json_decode($res, true);
            if ($res['status'] == 1) {
                $out = array(
                    'status' => 1,
                    'message' => $res['message']
                );
            } else {
                $out = array(
                    'status' => 0,
                    'error_msg' => $res['error_msg']
                );
            }
        }
    } else {
        $out = array(
            'status' => 0,
            'error_msg' => "Invalid action"
        );
    }
} else {
    $out = array(
        'status' => 0,
        'error_msg' => "Invalid parameter"
    );
}

echo json_encode($out);
