<?php
// Handler AJAX untuk halaman Referral. Di-include dari index.php ketika
// request datang dengan $_POST['act'] + $_POST['csrf']. Semua respon JSON
// mengikuti bentuk BE apiv2: {status:1, data/message} atau {status:0, error_msg}.
require_once('../_session.php');
header('Content-Type: application/json');

// $api_v2 sudah diinisialisasi di index.php (require ini di-include setelahnya).

function ref_out($status, $extra = array())
{
    echo json_encode(array_merge(array('status' => $status), $extra));
    exit;
}

$act  = $_POST['act'] ?? '';
$csrf = $_POST['csrf'] ?? '';

if ($csrf !== ($_SESSION['csrf'] ?? '')) {
    ref_out(0, array('error_msg' => 'Sesi tidak valid, muat ulang halaman.'));
}

// --------------------------------------------------------------------------
// LIST REFERRAL  (POST /downline-user/list)
// --------------------------------------------------------------------------
if ($act === 'list_referral') {
    $limit   = isset($_POST['limit']) ? abs((int) $_POST['limit']) : 30;
    $last_id = isset($_POST['last_id']) ? abs((int) $_POST['last_id']) : 0;
    if ($limit <= 0 || $limit > 100) $limit = 30;

    $raw = $api_v2->list_downline_user($limit, $last_id);
    $res = json_decode($raw, true);

    if (!isset($res['status'])) {
        ref_out(0, array('error_msg' => 'Gagal menghubungi server.', 'data' => array(), 'last_id' => 0));
    }
    if ((int) $res['status'] !== 1) {
        // BE mengembalikan "Data Not Found" saat kosong — treat as empty list.
        ref_out(0, array(
            'error_msg' => $res['error_msg'] ?? 'Data tidak ditemukan.',
            'data'      => array(),
            'last_id'   => 0,
        ));
    }

    $data    = isset($res['data']['data']) && is_array($res['data']['data']) ? $res['data']['data'] : array();
    $last    = isset($res['data']['last_id']) ? (int) $res['data']['last_id'] : 0;
    ref_out(1, array('data' => $data, 'last_id' => $last));
}

// --------------------------------------------------------------------------
// LIST KOMISI  (POST /downline-user/komisi)
// --------------------------------------------------------------------------
if ($act === 'list_komisi') {
    $limit   = isset($_POST['limit']) ? abs((int) $_POST['limit']) : 30;
    $last_id = isset($_POST['last_id']) ? abs((int) $_POST['last_id']) : 0;
    if ($limit <= 0 || $limit > 100) $limit = 30;

    $raw = $api_v2->list_downline_komisi($limit, $last_id);
    $res = json_decode($raw, true);

    if (!isset($res['status'])) {
        ref_out(0, array('error_msg' => 'Gagal menghubungi server.', 'data' => array(), 'last_id' => 0));
    }
    if ((int) $res['status'] !== 1) {
        ref_out(0, array(
            'error_msg' => $res['error_msg'] ?? 'Data tidak ditemukan.',
            'data'      => array(),
            'last_id'   => 0,
        ));
    }

    $data = isset($res['data']['data']) && is_array($res['data']['data']) ? $res['data']['data'] : array();
    $last = isset($res['data']['last_id']) ? (int) $res['data']['last_id'] : 0;
    ref_out(1, array('data' => $data, 'last_id' => $last));
}

// --------------------------------------------------------------------------
// UPDATE KODE REFERRAL  (POST /user/update-kode-referral)
// --------------------------------------------------------------------------
if ($act === 'update_kode') {
    // Ambil hanya sebagai string, buang whitespace di ujung.
    $kode = is_string($_POST['kode'] ?? null) ? trim($_POST['kode']) : '';

    // Kode wajib ada.
    if ($kode === '') {
        ref_out(0, array('error_msg' => 'Kode referral wajib diisi.'));
    }

    // Allowlist ketat: hanya huruf (a-z, A-Z) dan angka (0-9), 6 - 15 karakter.
    // Pendekatan allowlist menolak semua karakter lain (spasi, simbol,
    // <, >, ', /, dll.) sehingga aman dari injeksi/XSS dan konsisten dengan
    // validasi IsDangerString di BE. Pakai strlen (bukan mb_strlen) karena
    // pola sudah membatasi hanya ASCII single-byte.
    if (!preg_match('/^[A-Za-z0-9]{6,15}$/', $kode)) {
        ref_out(0, array('error_msg' => 'Kode referral harus 6 - 15 karakter dan hanya boleh huruf & angka (tanpa spasi/simbol).'));
    }

    $raw = $api_v2->update_referral($kode);
    $res = json_decode($raw, true);

    if (!isset($res['status'])) {
        ref_out(0, array('error_msg' => 'Gagal menghubungi server.'));
    }
    if ((int) $res['status'] === 1) {
        ref_out(1, array('message' => $res['message'] ?? 'Berhasil mengubah kode referral.', 'kode' => $kode));
    }
    ref_out(0, array('error_msg' => $res['error_msg'] ?? 'Gagal mengubah kode referral.'));
}

// --------------------------------------------------------------------------
// TARIK KOMISI  (GET /tarik-komisi)
// --------------------------------------------------------------------------
if ($act === 'tarik_komisi') {
    $raw = $api_v2->penarikan_komisi();
    $res = json_decode($raw, true);

    if (!isset($res['status'])) {
        ref_out(0, array('error_msg' => 'Gagal menghubungi server.'));
    }
    if ((int) $res['status'] === 1) {
        $topup_id = isset($res['data']['topup_id']) ? (int) $res['data']['topup_id'] : 0;
        ref_out(1, array(
            'message'  => $res['message'] ?? 'Penarikan komisi berhasil.',
            'topup_id' => $topup_id,
        ));
    }
    ref_out(0, array('error_msg' => $res['error_msg'] ?? 'Penarikan komisi gagal.'));
}

// --------------------------------------------------------------------------
ref_out(0, array('error_msg' => 'Aksi tidak dikenali.'));
