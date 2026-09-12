<?php
require_once('../_session.php');
require_once('../config.php');
require_once('../lib/ApiV2.php');

$api_v2 = new ApiV2($user_jwt);

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
    $kode = isset($_GET['kode']) ? trim($_GET['kode']) : '';
    if ($act == "use") {
        if (empty($kode)) {
            $out = array(
                'status' => 0,
                'error_msg' => "Kode voucher wajib di isi!"
            );
            echo json_encode($out);
            exit;
        }
        //call api proses kode voucher via libapiv2 (BE: api-bukakios-v2)
        $raw = $api_v2->redem_voucher($kode);
        $res = json_decode($raw, true);
        if (!is_array($res) || !isset($res['status'])) {
            $out = array(
                'status' => 0,
                'error_msg' => "Gagal memproses kode voucher. Silakan coba lagi."
            );
        } else if ($res['status'] == 1) {
            $out = array(
                'status' => 1,
                'message' => isset($res['message']) ? $res['message'] : 'Kode voucher berhasil digunakan.'
            );
        } else {
            $out = array(
                'status' => 0,
                'error_msg' => isset($res['error_msg']) ? $res['error_msg'] : 'Kode voucher tidak valid.'
            );
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
