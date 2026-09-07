<?php
require_once("../config.php");
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <title>Hello, world!</title>
        <style>
            .body {
                left: 0;
                bottom: 0;
                width: 100%;
                height: 80%;
                background-color: #fff;
                border-radius: 0px 0px 0px 0px;
                margin-bottom:10px;
                padding-top:4px;
            }
            .footer {
                /* position: fixed; */
                left: 0;
                bottom: 0;
                width: 100%;
                background-color: #fff;
                color: #000;
                text-align: center;
                padding-top: 2px;
                padding-bottom: 10px;
            }
            .jumbotron {
                border-radius: 50px;
                font-family: Comic Sans MS, cursive, sans-serif;
            }
            body {
                font-family: Nunito;
            }
            /* #3498db */
        </style>
    </head>
  <body style="background-color:#dcdde1">
    <div>
        <div class="mx-auto py-2 mb-2 text-center">    
            <img  src="<?=$c_url?>assets/img/sign_det/warning.png" width="90px"><br/>
            <span class="mt-2 font-weight-bold" style="color:#EFCE4A" >On Progress</span>
        </div>
    </div>
    <div class="body">
        <div class="container">
            <h5 class="mt-2">ID Order : #407874 <img  src="<?=$c_url?>assets/img/sign_det/warning.png" class="mb-2" height="25px" width="25px"></h5>
            <!-- <span class="text-success font-weight-bold">Berhasil</span> -->
            <div class="row">
                <div class="col-12 text-center" >
                    <span class="px-4 py-1 text-wrap" style="background-color:#EFCE4A;color:#fff;border-radius:20px">Detail</span>
                    <hr/>
                </div>
                <div class="col-12 ">
                    <span class="my-2" style='color:grey;font-size:19px;font-family: "Comic Sans MS", cursive, sans-serif'>Status Tagihan</span>
                    <br/>
                    <span class="text-wrap" style="background-color:#EFCE4A;color:#fff;border-radius:10px;padding:2px 100px 2px 3px;border: 1px solid #707070">Menunggu Pembayaran</span><br/>
                    <span class="text-wrap font-weight-light" style="font-size:13px">Hingga 16 Des 2019 - 23:59</span>
                    <hr/>
                </div>
                <div class="col-12 mb-2">
                    <table width=100% >
                        <tr>
                            <td width="65%">Total Tagihan</td>
                            <td><span class="font-weight-bold mr-1 float-left " style="color:#9CCC65">Rp. 20.000</span></td>
                        </tr>
                        <tr>
                            <td>Kode Unik</td>
                            <td><span class="font-weight-bold mr-1 float-left ">Rp. 3</span></td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td><span class="font-weight-bold mr-1 float-left" style="color:#E57373">Rp. 20.003</span></td>
                        </tr>
                    </table>
                </div>
                <div class="col-12 text-center justify-content-md-center" >
                    <div class="row justify-content-md-center">
                        <div class="col-md-auto ">
                            <div class="jumbotron mx-5 my-1">
                            <span class="text-wrap" style="font-size:25px;color:#9CCC65">Rp 20.003</span>
                            </div>
                        </div>
                    </div>
                    <hr/>
                </div>
                <div class="col-12 mb-2 text-center" >
                    <span class="text-wrap" style='font-family: "Comic Sans MS", cursive, sans-serif'>Transfer ke Rekening</span><br/>
                    <img src="<?=$c_url?>assets/img/riwayat_trx/1280px-BANK_BRI_logo.svg.png" width="145px" height="35px"><br/>
                    <span class='font-weight-bold' style='font-family: "Comic Sans MS", cursive, sans-serif;font-size:17px'>Rekening</span><br/>
                    <span class='font-weight-light' style='font-family: "Comic Sans MS", cursive, sans-serif;font-size:14px'>208701000251302</span><br/>
                    <span class='font-weight-bold' style='font-family: "Comic Sans MS", cursive, sans-serif;font-size:17px'>Atas Nama</span><br/>
                    <span class='font-weight-light' style='font-family: "Comic Sans MS", cursive, sans-serif;font-size:14px'>PT Aplikasi Kreasi Indonesia</span>
                </div>
    
            </div>
        </div>
    </div>
        <div class="footer">
            <div class="mx-3 my-2">
                <span>Pembatalan Topup </span><a onclick="custom()"><img src="../assets/img/tagihan_pln/info.svg" width="15px"></a><br/>
                <a class="btn btn-sm btn-block mt-1" style="background-color:#E57373;color:#fff;text-decoration:none" href="">Batal</a>
            </div>
        </div>
    </div>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="../assets/js/jquery.js"></script>
        <script src="../assets/js/sweetalert.min.js"></script>
    </body>
</html>