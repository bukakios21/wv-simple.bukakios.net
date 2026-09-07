<?php
//echo "d";exit;
require_once("../config.php");
require_once("../_session.php");
$openurl = "open://";
$open_url = "open://";
// if ($user_id != 40408) {
//    if ($new_detail){
// $id = abs((int)$_GET['id']);
//    header("Location: https://wv3.bukakios.id/transaksi/$id");
//    exit();
//  }
// }

function getTransaksi($id_trx, $token_jwt) {

    $url = "https://api-v2.bukakios.net/wv-x7Up2p/transaksi/" . $id_trx;

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            "Authorization: $token_jwt",
            "Api-key: PLowElenThErTeRAphaRDwINEAntrIDe"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);


    curl_close($curl);

    if ($err) {
        return "cURL Error: " . $err;
    } else {
        return $response;
    }
}

if (isset($_GET['id'])) {
    $id = abs((int)$_GET['id']);
    $json_data = getTransaksi($id, $user_jwt);
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