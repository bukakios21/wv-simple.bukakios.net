<?php 
exit;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (isset($_GET['qr'])){
    $qr = $_GET['qr'];
    require_once("phpqrcode/qrlib.php");
    QRcode::png($qr);
}

