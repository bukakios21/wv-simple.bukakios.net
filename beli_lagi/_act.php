<?php
$act = $_REQUEST['msg'];

if ($act == "beli") {
    if (isset($_GET['no'], $_GET['sell_price'], $_GET['csrf'])) {
        $csrf = $_GET['csrf'];

        $no = htmlentities($_GET['no']);
        $harga_client = htmlentities($_GET['sell_price']);
        $session_cache = "$code" . "--" . md5("$code-$no");
        if ($csrf != $_SESSION['csrf']) {
            $return = array("status" => 0, "error_msg" => "CSRF Wrong!, silahkan refresh halaman ini");
            echo json_encode($return);
            exit;
        }

        if (isset($_SESSION[$session_cache])) {
            //session.....
            $return = $_SESSION[$session_cache];
        } else {
            $data_post = array(
                "key" => $api_key,
                "uid" => $user_id,
                "token" => $user_token,
                "token_trx" => $user_token_trx,
                "kode_produk" => $code,
                "tujuan" => $no,
                "sell_price" => $harga_client
            );
            // echo json_encode($data_post);

            $beli_produk = $app->curl_post("$api_url/v1/beli_prabayar.php?version=$bukakios_version", $data_post);

            $beli_produk = json_decode($beli_produk, true);
            if ($beli_produk['status'] == 1) {
                echo $respon = json_encode(array("status" => 1, "msg" => "Berhasil Membeli produk"));
            } else {
                echo $respon = json_encode(array("status" => 0, "error_msg" => $beli_produk['error_msg']));
            }
        }
    }
}
exit;
