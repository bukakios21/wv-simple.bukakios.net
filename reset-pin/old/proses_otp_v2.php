<?php
require_once('../../config.php');

function set_error($msg){
    $_SESSION['error'] = $msg;
}

if (isset($_POST['act'], $_POST['csrf'])) {
    $act = $_POST['act'];
    $csrf = $_POST['csrf'];
    if ($csrf !== $_SESSION['csrf']){
        $error = "CSRF not valid";
        set_error($error);
        $out = array(
            'status' => 0,
            'error_msg' => "CSRF not valid"
        );
        echo json_encode($out);exit;
    }


    if ($act == "wa"){
        $req = $api_v2->request_otp_wa_reset_pin(array());
        $req_res = json_decode($req, true);
        if (isset($req_res['status'])){
            if ($req_res['status']==1){
                $out = array(
                    'status'=>1,
                    'message' => $req_res['message'],
                    'msg' => $req_res['message'],
                );
            }else{
                $error =$req_res['error_msg'];
                set_error($error);
                $out = array(
                    'status'=>0,
                    'error_msg' => $req_res['error_msg']
                );
            }
        }else{
            $error ="error call server otp wa";
            set_error($error);
            $out = array(
                'status'=>0,
                'error_msg' => "error call server otp wa"
            );
        }
    }else if ($act == "sms"){
        $req = $api_v2->request_otp_sms_reset_pin(array());
        $req_res = json_decode($req, true);
        if (isset($req_res['status'])){
            if ($req_res['status']==1){
                $out = array(
                    'status'=>1,
                    'message' => $req_res['message'],
                    'msg' => $req_res['message'],
                );
            }else{
                $error =$req_res['error_msg'];
                set_error($error);
                $out = array(
                    'status'=>0,
                    'error_msg' => $req_res['error_msg']
                );
            }
        }else{
            $error ="error call server otp wa";
            set_error($error);
            $out = array(
                'status'=>0,
                'error_msg' => "error call server otp wa"
            );
        }
    }else if ($act == "email"){
        $req = $api_v2->request_otp_email_reset_pin(array());
        $req_res = json_decode($req, true);
        if (isset($req_res['status'])){
            if ($req_res['status']==1){
                $out = array(
                    'status'=>1,
                    'message' => $req_res['message'],
                    'msg' => $req_res['message'],
                );
            }else{
                $error =$req_res['error_msg'];
                set_error($error);
                $out = array(
                    'status'=>0,
                    'error_msg' => $req_res['error_msg']
                );
            }
        }else{
            $error ="error call server otp wa";
            set_error($error);
            $out = array(
                'status'=>0,
                'error_msg' => "error call server otp wa"
            );
        }
    }else if ($act == "sending"){
        if (!isset($_POST['otp'])){
            $out = array(
                'status'=>0,
                'error_msg' => "need OTP"
            );
            $error ="need otp";
            set_error($error);
            echo json_encode($out);exit;
        }
        $otp= $_POST['otp'];
        $req = $api_v2->proses_otp_reset_pin(array('otp' => $otp));
        $req_res = json_decode($req, true);
        if (isset($req_res['status'])){
            if ($req_res['status']==1){
                $out = array(
                    'status'=>1,
                    'message' => $req_res['message'],
                    'msg' => $req_res['message'],
                );
            }else{
                $error = $req_res['error_msg'];
                set_error($error);
                $out = array(
                    'status'=>0,
                    'error_msg' => $req_res['error_msg']
                );
            }
        }else{
            $error = "error call server otp wa";
            set_error($error);
            $out = array(
                'status'=>0,
                'error_msg' => "error call server otp wa"
            );
        }
    }else{
        $error = "No action";
        set_error($error);
        $out = array(
            'status' => 0,
            'error_msg' => "No action"
        );
    }
}
echo json_encode($out);exit;