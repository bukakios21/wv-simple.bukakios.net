<?PHP
if (isset($_POST['file'])){
    $file = $_POST['file'];
    $respon = $app->grab_data("$api_url/mail/mail_download_file.php?key=$api_key&uid=$user_id&file=$file");
        
    $mail = json_decode($respon,true);
    if($mail['status'] == 1){
        $return = json_encode(array("status"=>1,"msg"=>"Silahkan Cek Email Anda"));
    }else{
        $return = json_encode(array("status"=>0,"error_msg"=>"Pastikan Jaringan Anda Aktif","error_code"=>"100"));
    }
}else{
    $out = array(
        'status' => 0,
        'error_msg' => "Invalid action"
    );
}

echo json_encode($out);
?>