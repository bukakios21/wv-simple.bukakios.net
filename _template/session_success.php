<?php
//cara akses ini langsung redirect kan aja ke url/_template/msg_show.php
require_once("../config.php");
if(!isset($_SESSION['lyt_image'])){
	$html_title = "title::Transaksi Berhasil";
	$lyt_button_link = "opentranslate://10;pulsa";
	$lyt_button_name = "KEMBALI KE HOME";
	$lyt_image = "https://assets.bukakios.net/img/illustration/bc_trx_berhasil.png";
	$lyt_title = "Transaksi Sukses!";
	$lyt_description = "Transaksi kamu berhasil di teruskan ke operator, biasa proses transaksi 3 - 30 detik";
}else{
	/*
	di matikan agar tidak terreplace...
	$html_title = $_SESSION['html_title'];
	$lyt_button_link = $_SESSION['lyt_button_link'];
	$lyt_button_name = $_SESSION['lyt_button_name'];
	$lyt_image = $_SESSION['lyt_image'];
	$lyt_title = $_SESSION['lyt_title'];
	$lyt_description = $_SESSION['lyt_description'];*/
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Nunito" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <title><?PHP echo $html_title; ?></title>
        <style>
            .footer {
                position: fixed;
                left: 0;
                bottom: 0;
                width: 100%;
                background-color: <?=$primary?>;
                color: #fff;
                text-align: center;
                padding-top: 6px;
                padding-bottom: 6px;
            }

            body a {
                font-family: Nunito;
            }

            h1 { font-family: Nunito; font-size: 24px; font-style: normal; font-variant: normal; font-weight: 700; line-height: 26.4px; } 
            h3 { font-family: Nunito; font-size: 28px; font-style: normal; font-variant: normal; font-weight: 700;margin-top:5px; } 
            span { font-family: Nunito; font-size: 15px; font-style: normal; font-variant: normal; font-weight: 600;} 
            blockquote { font-family: Nunito; font-size: 21px; font-style: normal; font-variant: normal; font-weight: 400; line-height: 30px; } 
            a { font-family: Nunito; font-size: 21px; font-style: normal; font-variant: normal; font-weight: 400; line-height: 30px; } 
            pre { font-family: Nunito; font-size: 13px; font-style: normal; font-variant: normal; font-weight: 400; line-height: 18.5714px; }
        </style>
    </head>
    <body style="background-color:#f7f7f7">
        <div class="container text-center" style="margin-top:100px" >
            <img src="<?PHP echo $lyt_image; ?>" width="75%"> <br/>
            <h4 style='margin-top:10px;margin-bottom:5px'><?PHP echo $lyt_title; ?></h4>
            <span><?PHP echo $lyt_description; ?></span>
        </div>
        <div class="footer">
            <a href="<?PHP echo $lyt_button_link; ?>" style="color:#fff" class="btn btn-md btn-block" ><?PHP echo $lyt_button_name; ?></a>
        </div>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    </body>
</html>