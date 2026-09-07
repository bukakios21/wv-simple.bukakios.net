<?php 

if(isset($_REQUEST["file"])){
    $file = $_REQUEST['file'];
    // $file_url = 'https://wv.bukakios.net/info-topup/'.$file;
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary"); 
    header("Content-disposition: attachment; filename=\"" . basename($file) . "\""); 
    readfile("https://wv.bukakios.net/info-topup/qris_image/$file"); 
}