<?php
// This file is included via require_once from index.php.
// It needs its own config + session includes to access $user_jwt.
// _session.php does NOT call $app->csrf(), so CSRF will NOT be regenerated here.

require_once(__DIR__ . "/../../config.php");
require_once(__DIR__ . "/../../lib/ApiV2.php");

$user_jwt = !empty($user_jwt) ? $user_jwt : ($_SESSION['user_jwt'] ?? '');
//var_dump($user_jwt);exit;
$api_v2 = new ApiV2($user_jwt);

function set_msg($msg) {
    $_SESSION['msg'] = $msg;
}

if (!isset($_POST['act']) || !isset($_POST['csrf'])) {
    $out = array(
        'status' => 0,
        'error_msg' => 'Invalid parameter'
    );
    echo json_encode($out);
    exit;
}

$csrf = $_POST['csrf'];

// Validate against the CSRF token stored by index.php
// (NOT regenerated here because _session.php doesn't call $app->csrf())
if ($csrf !== ($_SESSION['csrf'] ?? '')) {
    set_msg("Token tidak valid. Silakan refresh halaman.");
    $out = array(
        'status' => 0,
        'error_msg' => 'CSRF token tidak valid'
    );
    #echo json_encode($out);
    #exit;
}

$call = $api_v2->request_verif_email();
$call_res = json_decode($call, true);

if (isset($call_res['status']) && $call_res['status'] == 1) {
    $msg = $call_res['message'] ?? 'Link verifikasi berhasil dikirim ke email kamu.';
    set_msg($msg);

    // Ambil email user untuk halaman success
    $detail = json_decode($api_v2->detail_user(), true);
    $user_email = $detail['data']['email'] ?? '';
    $_SESSION['verif_email_addr'] = $user_email;

    $out = array(
        'status' => 1,
        'message' => $msg
    );
} else {
    $msg = $call_res['error_msg'] ?? $call_res['message'] ?? 'Terjadi kesalahan. Silakan coba lagi.';
    set_msg($msg);
    $out = array(
        'status' => 0,
        'error_msg' => $msg
    );
}

echo json_encode($out);
