<?PHP
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
$act = $_REQUEST['msg'];
require_once "../lib/ApiV2.php";
$api_v2 = new ApiV2($user_jwt);


if ($act == 'update') {

    if (isset($_REQUEST['csrf'], $_REQUEST['harga'], $_REQUEST['id_trx'])) {
        $csrf = $_REQUEST['csrf'];
        $id_trx = $_REQUEST['id_trx'];
        $harga = abs((int) $_REQUEST['harga']);
        if ($csrf != $_SESSION['csrf']) {
            $return = array("status" => 0, "error_msg" => "CSRF Wrong!, silahkan refresh halaman ini");
            echo json_encode($return);
            exit;
        }

        $data_up = array(
            "trx_id" => $id_trx,
            "nominal" => $harga,
        );
        $res_api = $api_v2->update_price_sell($data_up);
        // $respon = json_decode($res_api, true);
        // file_put_contents("log.txt", $res_api);
        // file_put_contents("log_payload.txt", json_encode($data_up));
        // $respon = $app->grab_data("$api_url/v1/api_update_harga_kamu.php?key=$api_key&id_trx=$id_trx&uid=$user_id&harga=$harga");
        // $respon = $app->grab_data("http://localhost:8080/api.bukakios.net/v1/api_update_harga_kamu.php?key=$apia_key&id_trx=$id_trx&uid=$user_id&harga=$harga");

        $rsp = json_decode($res_api, true);
        if (isset($rsp['status'])) {
            if ($rsp['status'] == 1) {
                $data_r['status'] = 1;
                $data_r['msg'] = $rsp['message'];
                $return = json_encode($data_r);
            } else {
                if (isset($rsp['error_msg'])) {
                    $error_msg = $rsp['error_msg'];
                } else {
                    $error_msg = "Ada kesalahan di server kami yang tidak di ketahui penyebabnya, silahkan coba lagi nanti";
                }
                $return = json_encode(array("status" => 0, "error_msg" => $error_msg));
            }
        } else {
            $return = json_encode(array("status" => 0, "error_msg" => "Server kami sedang kepenuhan, silahkan di coba kembali!"));
        }
        // }
    } else {
        $return = json_encode(array("status" => 0, "error_msg" => "Data Kosong"));
    }
    echo $return;
}
exit;
