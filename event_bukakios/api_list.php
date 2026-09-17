<?php
require_once("../config.php");
require_once("../_session.php");
require_once("../lib/ApiV2.php");

header('Content-Type: application/json; charset=utf-8');

$api_v2 = new ApiV2($user_jwt);
$res = $api_v2->event_list();

if ($res === false || $res === '') {
    echo json_encode(array(
        'status' => 0,
        'error_msg' => 'Gagal menghubungi API event',
    ));
    exit;
}

echo $res;
