<?php
require_once('../_session.php');
header('Content-Type: application/json');

function set_msg($msg){
    $_SESSION['msg']  = $msg;
}

if (isset($_POST['act'], $_POST['csrf'], $_POST['id'])) {
    $act = $_POST['act'];
    $csrf = $_POST['csrf'];
    $id = abs((int) $_POST['id']);

    if ($csrf != $_SESSION['csrf']){
        set_msg("CSRF not valid");
        $out = array(
            'status' => 0,
            'error_msg' => "CSRF not valid"
        );
        echo json_encode($out);exit;
    }

    if ($act=="tukar"){
        if ($id == 7 or $id == 8 or $id == 9 or $id == 10 or $id == 11){ 
            $post_poin = array(
                'id_poin' => $id
            );
            $call_trx_api = $api_v2->tukar_poin_trx($post_poin);
            $data_poin = json_decode($call_trx_api, true);
            if (isset($data_poin['status'])){
                if ($data_poin['status']==1){
                    $msg = $data_poin['message'];

                    $out = array(
                        'status' => 1,
                        'msg' => $data_poin['message'],
                        'message' => $data_poin['message'],
                    ); 
                }else{
                    $msg = $data_poin['error_msg'];
                    $out = array(
                        'status' => 0,
                        'error_msg' => $data_poin['error_msg']
                    ); 
                }
            }else{
                $msg ="error call api server";
                $out = array(
                    'status' => 0,
                    'error_msg' => $msg
                ); 
            }
        }else{
            $msg ="Promo masih dalam pengembangan. Hubungi customer service";
            $out = array(
                'status' => 0,
                'error_msg' => $msg 
            );
        }
    }else{
        $msg ="No action";
        $out = array(
            'status' => 0,
            'error_msg' => "No action"
        );
    }
} else {
   $msg ="Invalid parameter";
    $out = array(
        'status' => 0,
        'error_msg' => $msg
    );
}
set_msg($msg);
echo json_encode($out);
