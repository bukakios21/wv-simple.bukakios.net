<?php 
if (isset($_POST['submit'])){
    $e_form = $real_data['e_form'];
    $ex_form = explode("\n", $e_form);
    foreach ($ex_form as $ex_form_res){
        $data_ex = $ex_form_res;
        $ex_data = explode(";", $data_ex);
        $tmp[] = $_POST[$ex_data[1]];
    }
    $json_form = base64_encode(json_encode($tmp));
    $post = array(
        'uid' => $user_id,
        'e_id' => $e_id,
        'key' => $api_key,
        'e_form' => $json_form
    ); 
    
    $send = $app->curl_post("https://ms1.bukakios.net/v1/api_event/api_submit_event.php", $post);
    $send_res = json_decode($send, true);
    if (isset($send_res['status'])){
        if ($send_res['status'] == 1){
            header("location: success.php?msg=$send_res[msg]");
        }else{
            header("location: error.php?msg=$send_res[error_msg]");
        }
    }else{
        header("location: error.php?msg=MAAF, GAGAL MENGHUBUNGI API !!. HUBUNGI CUSTOMER SERVICE !!");
    }
}
