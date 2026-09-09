<?php
//echo "d";exit;
require_once("../config.php");
require_once("../_session.php");
require_once("../lib/ApiV2.php");
$openurl = "open://";
$open_url = "open://";

$api_v2 = new ApiV2($user_jwt);
// if ($user_id != 40408) {
//    if ($new_detail){
// $id = abs((int)$_GET['id']);
//    header("Location: https://wv3.bukakios.id/transaksi/$id");
//    exit();
//  }
// }

if (isset($_GET['id'])) {
    $id = abs((int)$_GET['id']);
    $json_data = $api_v2->transaksi_detail($id);
    $transaksi = json_decode($json_data, true);
    if ($transaksi['status'] != 1) {
        echo "no data trx";
        echo $response;
        exit;
    }
    //   echo "stop 1123";exit;
    $detail_transaksi = $transaksi['data'];
    $trx_id = $detail_transaksi['trx_id'];
    $product_name = $detail_transaksi['product_name'];
    $product_code = $detail_transaksi['product_code'];
    $nomor_tujuan = $detail_transaksi['nomor_tujuan'];
    $id_pel = $detail_transaksi['id_pel'];
    $price_client = $detail_transaksi['price_client'];
    $selling_price_client = $detail_transaksi['selling_price_client'];
    $sn = $detail_transaksi['sn'];
    $note = $detail_transaksi['note'];
    $saldo_before_trx = $detail_transaksi['saldo_before_trx'];
    $saldo_after_trx = $detail_transaksi['saldo_after_trx'];
    $created_at = $detail_transaksi['created_at'];
    $updated_at = $detail_transaksi['updated_at'];
    $status = $detail_transaksi['status'];
    $op_name = $detail_transaksi['op_name'];
    $u_nama = $detail_transaksi['nama'];
    $u_nama_toko = $detail_transaksi['nama_toko'];
    $signature = $detail_transaksi['signature'];
    $pembelianoperator_id = $detail_transaksi['pembelianoperator_id'];
    $pembelian_kategori_id = $detail_transaksi['pembeliankategori_id'];
    $product_logo = $detail_transaksi['product_logo'];
    echo json_encode(array("status"=>$status));
}else{
    echo json_encode(array("error_msg"=>"id tidak ada"));
}