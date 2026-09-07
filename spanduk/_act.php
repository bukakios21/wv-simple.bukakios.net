<?PHP
$act = $_REQUEST['msg'];
if($act=='send'){
    //cek tagihan
    if(isset($_GET['text'])){
        $text = $_GET['text'];
        $text = base64_decode($text);
        $text = explode(':',$text);
        $uid = $text[0];
        $file = $text[1];

        $respon = $app->grab_data("$api_url/mail/mail_download_file.php?key=77e2edcc9b40441200e31dc57dbb8829&uid=$uid&file=$file");
        
        $mail = json_decode($respon,true);
        if($mail['status'] == 1){
            $return = json_encode(array("status"=>1,"msg"=>"Silahkan Cek Email Anda"));
        }else{
            $return = json_encode(array("status"=>0,"error_msg"=>"Pastikan Jaringan Anda Aktif","error_code"=>"100"));
        }
    }
    echo $return;
}
exit;
?>