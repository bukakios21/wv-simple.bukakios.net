<?php
if(isset($_REQUEST["file"])){
    // Get parameters

    /* Test whether the file name contains illegal characters
    such as "../" using the regular expression */
    $file = abs((int) $_GET['file']);
    if ($file === 91){
        $file = "91-promosi-3-story.png";
    }else if ($file === 347){
        $file = "347-promosi-4-story.png";
    }else if ($file === 529){
        $file = "529-promosi-4.png";
    }else if ($file === 554){
        $file = "554-promosi-1.png";
    }else if ($file === 817){
        $file = "817-promosi-2-story.png";
    }else if ($file === 839){
        $file = "839-daftar-harga-story.png";
    }else if ($file === 894){
        $file = "894-daftar-harga.png";
    }else if ($file === 918){
        $file = "918-promosi-1-story.png";
    }else if ($file === 971){
        $file = "971-promosi-2.png";
    }else if ($file === 974){
        $file = "974-promosi-3.png";
    }else if ($file === 975){
        $file = "975-story-harga.png";
    }else if ($file === 976){
        $file = "976-master-ps-bukakios.zip";
    }else if ($file === 977){
        $file = "977-spanduk-bukakios.rar";
    }else if ($file === 978){
        $file = "978-spanduk.jpg";
    }else if ($file === 979){
        $file = "979-spanduk2.jpg";
    }else if ($file === 980){
        $file = "980-corel.zip";
    }else{
        http_response_code(404);
        die("no file ");
    }
    $filepath = $file;

    // Process download
    if(file_exists($filepath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        flush(); // Flush system output buffer
        readfile($filepath);
        die();
    } else {
        http_response_code(404);
        die("no file ");
    }
}