<?php
// Handler AJAX untuk halaman QRIS. Di-include dari index.php ketika request
// datang dengan $_POST['act'] + $_POST['csrf']. Semua respon JSON dinormalisasi
// mengikuti bentuk BE apiv2: {status:1, data/message} atau {status:0, error_msg}.
require_once('../_session.php');
header('Content-Type: application/json');

// $api_v2 sudah diinisialisasi di index.php (require ini di-include setelahnya).

function qris_out($status, $extra = array())
{
    echo json_encode(array_merge(array('status' => $status), $extra));
    exit;
}

$act  = $_POST['act'] ?? '';
$csrf = $_POST['csrf'] ?? '';

if ($csrf !== ($_SESSION['csrf'] ?? '')) {
    qris_out(0, array('error_msg' => 'Sesi tidak valid, muat ulang halaman.'));
}

// --------------------------------------------------------------------------
// RIWAYAT TRANSAKSI  (POST /qris/riwayat/transaksi)
// status wajib: 1 = saldo real, 0 = saldo hold. Paginasi cursor last_id.
// --------------------------------------------------------------------------
if ($act === 'riwayat') {
    $limit   = isset($_POST['limit']) ? abs((int) $_POST['limit']) : 20;
    $last_id = isset($_POST['last_id']) ? abs((int) $_POST['last_id']) : 0;
    $status  = isset($_POST['tipe_status']) && (int) $_POST['tipe_status'] === 0 ? 0 : 1;
    if ($limit <= 0 || $limit > 50) $limit = 20;

    $body = array(
        'last_id'    => $last_id,
        'start_date' => '',
        'end_date'   => '',
        'limit'      => $limit,
        'text'       => '',
        'trx_id'     => '',
        'status'     => $status,
    );

    $res = json_decode($api_v2->qris_riwayat_transaksi($body), true);

    if (!isset($res['status'])) {
        qris_out(0, array('error_msg' => 'Gagal menghubungi server.', 'data' => array(), 'last_id' => 0));
    }
    if ((int) $res['status'] !== 1) {
        // BE balikin error "Belum ada riwayat transaksi" saat kosong.
        qris_out(0, array(
            'error_msg' => $res['error_msg'] ?? 'Belum ada riwayat transaksi.',
            'data'      => array(),
            'last_id'   => 0,
        ));
    }

    $rows = isset($res['data']['riwayat_trx']) && is_array($res['data']['riwayat_trx']) ? $res['data']['riwayat_trx'] : array();
    $last = isset($res['data']['last_id']) ? (int) $res['data']['last_id'] : 0;
    qris_out(1, array('data' => $rows, 'last_id' => $last));
}

// --------------------------------------------------------------------------
// MUTASI SALDO  (POST /qris/mutasi)
// tipe: "real" (default) atau "kliring". Paginasi cursor last_id.
// --------------------------------------------------------------------------
if ($act === 'mutasi') {
    $limit   = isset($_POST['limit']) ? abs((int) $_POST['limit']) : 20;
    $last_id = isset($_POST['last_id']) ? abs((int) $_POST['last_id']) : 0;
    $tipe    = (isset($_POST['tipe']) && $_POST['tipe'] === 'kliring') ? 'kliring' : 'real';
    if ($limit <= 0 || $limit > 50) $limit = 20;

    $body = array(
        'start_at' => '',
        'end_at'   => '',
        'text'     => '',
        'kategori' => '',
        'last_id'  => $last_id,
        'limit'    => $limit,
        'tipe'     => $tipe,
    );

    $res = json_decode($api_v2->qris_mutasi($body), true);

    if (!isset($res['status'])) {
        qris_out(0, array('error_msg' => 'Gagal menghubungi server.', 'data' => array(), 'last_id' => 0));
    }
    if ((int) $res['status'] !== 1) {
        qris_out(0, array(
            'error_msg' => $res['error_msg'] ?? 'Data mutasi tidak ditemukan.',
            'data'      => array(),
            'last_id'   => 0,
        ));
    }

    $rows = isset($res['data']['data']) && is_array($res['data']['data']) ? $res['data']['data'] : array();
    $last = isset($res['data']['last_id']) ? (int) $res['data']['last_id'] : 0;
    qris_out(1, array('data' => $rows, 'last_id' => $last));
}

// --------------------------------------------------------------------------
// LIST METODE PAYMENT  (GET /qris/payment/list)
// Dipakai untuk ambil min/max/biaya penarikan ke Stok Bukakios.
// --------------------------------------------------------------------------
if ($act === 'payment_list') {
    $res = json_decode($api_v2->qris_payment_list(), true);

    if (!isset($res['status'])) {
        qris_out(0, array('error_msg' => 'Gagal menghubungi server.', 'data' => array()));
    }
    if ((int) $res['status'] !== 1) {
        qris_out(0, array('error_msg' => $res['error_msg'] ?? 'Metode tidak ditemukan.', 'data' => array()));
    }

    $rows = isset($res['data']) && is_array($res['data']) ? $res['data'] : array();
    qris_out(1, array('data' => $rows));
}

// --------------------------------------------------------------------------
// LIST REKENING  (GET /qris/rekening/list)
// --------------------------------------------------------------------------
if ($act === 'rekening_list') {
    $res = json_decode($api_v2->qris_rekening_list(), true);

    if (!isset($res['status'])) {
        qris_out(0, array('error_msg' => 'Gagal menghubungi server.', 'data' => array()));
    }
    if ((int) $res['status'] !== 1) {
        // Kosong bukan error fatal — kembalikan list kosong.
        qris_out(1, array('data' => array()));
    }

    $rows = isset($res['data']) && is_array($res['data']) ? $res['data'] : array();
    qris_out(1, array('data' => $rows));
}

// --------------------------------------------------------------------------
// PENCAIRAN / TARIK DANA  (POST /qris/pencairan-v2)
// rekening_id = 0 -> ke stok Bukakios; > 0 -> ke rekening bank.
// --------------------------------------------------------------------------
if ($act === 'pencairan') {
    // Total: ambil hanya digit (buang titik/karakter lain), lalu integer.
    $total_raw = is_string($_POST['total'] ?? null) ? $_POST['total'] : '';
    $total     = (int) preg_replace('/[^0-9]/', '', $total_raw);
    $rekening_id = isset($_POST['rekening_id']) ? abs((int) $_POST['rekening_id']) : 0;

    if ($total <= 0) {
        qris_out(0, array('error_msg' => 'Nominal penarikan tidak valid.'));
    }

    $res = json_decode($api_v2->qris_pencairan_v2($total, $rekening_id), true);

    if (!isset($res['status'])) {
        qris_out(0, array('error_msg' => 'Gagal menghubungi server.'));
    }
    if ((int) $res['status'] === 1) {
        $id = isset($res['data']['id']) ? (int) $res['data']['id'] : 0;
        qris_out(1, array(
            'message' => $res['message'] ?? 'Penarikan berhasil diproses.',
            'id'      => $id,
        ));
    }
    qris_out(0, array('error_msg' => $res['error_msg'] ?? 'Penarikan dana gagal.'));
}

// --------------------------------------------------------------------------
// LIST PENCAIRAN / RIWAYAT TARIK DANA  (POST /qris/pencairan/list)
// Catatan: BE membalas key pagination "LastId" (bukan snake_case).
// --------------------------------------------------------------------------
if ($act === 'pencairan_list') {
    $limit   = isset($_POST['limit']) ? abs((int) $_POST['limit']) : 20;
    $last_id = isset($_POST['last_id']) ? abs((int) $_POST['last_id']) : 0;
    if ($limit <= 0 || $limit > 50) $limit = 20;

    $res = json_decode($api_v2->qris_pencairan_list(0, $limit, $last_id), true);

    if (!isset($res['status'])) {
        qris_out(0, array('error_msg' => 'Gagal menghubungi server.', 'data' => array(), 'last_id' => 0));
    }
    if ((int) $res['status'] !== 1) {
        qris_out(0, array(
            'error_msg' => $res['error_msg'] ?? 'Belum ada riwayat penarikan.',
            'data'      => array(),
            'last_id'   => 0,
        ));
    }

    $rows = isset($res['data']['data']) && is_array($res['data']['data']) ? $res['data']['data'] : array();
    // BE tidak konsisten: pencairan pakai "LastId", fallback ke "last_id" untuk aman.
    $last = 0;
    if (isset($res['data']['LastId'])) $last = (int) $res['data']['LastId'];
    elseif (isset($res['data']['last_id'])) $last = (int) $res['data']['last_id'];
    qris_out(1, array('data' => $rows, 'last_id' => $last));
}

// --------------------------------------------------------------------------
// DETAIL PENCAIRAN  (GET /qris/pencairan/detail/:id)
// --------------------------------------------------------------------------
if ($act === 'pencairan_detail') {
    $id = isset($_POST['id']) ? abs((int) $_POST['id']) : 0;
    if ($id <= 0) {
        qris_out(0, array('error_msg' => 'ID penarikan tidak valid.'));
    }

    $res = json_decode($api_v2->qris_pencairan_detail($id), true);

    if (!isset($res['status'])) {
        qris_out(0, array('error_msg' => 'Gagal menghubungi server.'));
    }
    if ((int) $res['status'] !== 1) {
        qris_out(0, array('error_msg' => $res['error_msg'] ?? 'Detail penarikan tidak ditemukan.'));
    }

    qris_out(1, array('data' => $res['data'] ?? array()));
}

// --------------------------------------------------------------------------
// CANCEL PENCAIRAN  (GET /qris/pencairan/cancel/:id)
// --------------------------------------------------------------------------
if ($act === 'pencairan_cancel') {
    $id = isset($_POST['id']) ? abs((int) $_POST['id']) : 0;
    if ($id <= 0) {
        qris_out(0, array('error_msg' => 'ID penarikan tidak valid.'));
    }

    $res = json_decode($api_v2->qris_pencairan_cancel($id), true);

    if (!isset($res['status'])) {
        qris_out(0, array('error_msg' => 'Gagal menghubungi server.'));
    }
    if ((int) $res['status'] === 1) {
        qris_out(1, array('message' => $res['message'] ?? 'Penarikan berhasil dibatalkan.'));
    }
    qris_out(0, array('error_msg' => $res['error_msg'] ?? 'Gagal membatalkan penarikan.'));
}

// --------------------------------------------------------------------------
qris_out(0, array('error_msg' => 'Aksi tidak dikenali.'));
