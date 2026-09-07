<?php
// require_once('../_session.php');
// require_once('../config.php');


function set_msg($msg)
{
    $_SESSION['msg'] = $msg;
}

if (isset($_POST['act'], $_POST['csrf'])) {
    $act = $_POST['act'];
    $csrf = $_POST['csrf'];

    if ($csrf != $_SESSION['csrf']) {
        $out = array(
            'status' => 0,
            'error_msg' => 'CSRF not valid. Silahkan reload halaman ini'
        );
        set_msg('CSRF not valid. Silahkan reload halaman ini');
        echo json_encode($out);
        exit;
    }


    if ($act == "sms") {
        //call api reset pin
        $post = array(
            'uid' => $user_id,
            "token" => $user_token,
            "token_trx" => $user_token_trx
        );

        $res = $api_v2->request_otp_sms($post);
        $res_json = json_decode($res, true);
        if (isset($res_json['status'])) {
            if ($res_json['status'] == 1) {
                $msg = $res_json['message'];
                $out = array(
                    'status' => 1,
                    'msg' => $msg,
                    'message' => $msg
                );
                set_msg($msg);
            } else {
                $msg = $res_json['error_msg'];
                $out = array(
                    'status' => 0,
                    'error_msg' => $msg,
                    'err_msg' => $msg
                );
                set_msg($msg);
            }
        } else {
            $out = array(
                'status' => 0,
                'error_msg' => "Gagal menghubungi API OTP",
                'err_msg' => "Gagal menghubungi API OTP",
            );
            set_msg("Gagal menghubungi API OTP");
        }
        echo json_encode($out);
        exit;
    } else if ($act == "wa") {

        $post = array(
            'uid' => $user_id,
            "token" => $user_token,
            "token_trx" => $user_token_trx
        );

        $res = $api_v2->request_otp_wa($post);
        $res_json = json_decode($res, true);
        if (isset($res_json['status'])) {
            if ($res_json['status'] == 1) {
                $msg = $res_json['message'];
                $out = array(
                    'status' => 1,
                    'msg' => $msg,
                    'message' => $msg
                );
                set_msg($msg);
            } else {
                $msg = $res_json['error_msg'];
                $out = array(
                    'status' => 0,
                    'error_msg' => $msg,
                    'err_msg' => $msg
                );
                set_msg($msg);
            }
        } else {
            $out = array(
                'status' => 0,
                'error_msg' => "Gagal menghubungi API OTP",
                'err_msg' => "Gagal menghubungi API OTP",
            );
            set_msg("Gagal menghubungi API OTP");
        }
        echo json_encode($out);
        exit;
    } else if ($act == "sending") {
        $otp = $app->clear_xss($_POST['otp']);
        
        $post = array(
            'uid' => $user_id,
            "token" => $user_token,
            "token_trx" => $user_token_trx,
            'otp' => $otp
        );
        $app->simpan_file("tov2.txt", json_encode($post));
        $res = $api_v2->verif_hp_user($post);
        $res_json = json_decode($res, true);
        if (isset($res_json['status'])) {
            if ($res_json['status'] == 1) {
                $msg = $res_json['message'];
                $out = array(
                    'status' => 1,
                    'msg' => $msg,
                    'message' => $msg
                );
                set_msg($msg);
            } else {
                $msg = $res_json['error_msg'];
                $out = array(
                    'status' => 0,
                    'error_msg' => $msg,
                    'err_msg' => $msg
                );
                set_msg($msg);
            }
        } else {
            $out = array(
                'status' => 0,
                'error_msg' => "Gagal menghubungi API OTP",
                'err_msg' => "Gagal menghubungi API OTP",
            );
            set_msg("Gagal menghubungi API OTP");
        }
        echo json_encode($out);
        exit;
    } else {
        $out = array(
            'status' => 0,
            'error_msg' => 'Invalid action'
        );
        set_msg("Invalid action");
    }
} else {
    $out = array(
        'status' => 0,
        'error_msg' => 'Invalid parameter2'
    );
    set_msg("Invalid parameter");
}

echo json_encode($out);
