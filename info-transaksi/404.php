<?php
// File ini di-require dari index.php, jadi variabel $primary dll sudah tersedia
// Jika diakses langsung, load config yang diperlukan
if (!isset($primary)) {
    require_once "../config.php";
    require_once "../_session.php";
} ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Nunito" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <title>title::Transaksi Tidak Ditemukan</title>
    <style>
        body {
            font-family: Nunito, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        .card {
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
            border: none;
        }

        .error-icon {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: #fee2e2;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .error-icon i {
            font-size: 60px;
            color: #ef4444;
        }

        .error-code {
            font-size: 72px;
            font-weight: 800;
            color: #e0e0e0;
            margin-bottom: -10px;
            letter-spacing: 5px;
        }

        .error-title {
            font-size: 20px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .error-description {
            font-size: 14px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .btn-back {
            background-color: <?= $primary ?>;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
            transition: opacity 0.3s;
        }

        .btn-back:hover {
            opacity: 0.9;
            color: #fff;
            text-decoration: none;
        }

        .btn-contact {
            background-color: #fff;
            color: <?= $primary ?>;
            border: 2px solid <?= $primary ?>;
            border-radius: 8px;
            padding: 10px 30px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }

        .btn-contact:hover {
            background-color: <?= $primary ?>;
            color: #fff;
            text-decoration: none;
        }

        .footer-help {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 15px;
            margin-top: 15px;
        }

        .footer-help p {
            font-size: 13px;
            color: #888;
            margin-bottom: 10px;
        }

        .product-img-placeholder {
            position: relative;
            width: 15%;
            border-radius: 50%;
            border: 3px solid #ef4444;
            background-color: #fff;
        }

        .product-logo {
            margin-top: -20px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="py-3" style="background-color:<?= $primary ?>;height:90px"></div>

    <div style="margin-top:-50px;margin-right:2px;margin-left:2px;margin-bottom:20px">
        <div class="container">
            <div class="card" style="border-radius:10px">
                <div class="product-logo">
                    <div class="error-icon" style="margin-top:-30px;width:70px;height:70px;border:3px solid #ef4444;background-color:#fff">
                        <i class="fa fa-times" style="font-size:30px;color:#ef4444"></i>
                    </div>
                </div>
                <div class="text-center px-4 pb-4">
                    <div class="error-code">404</div>
                    <div class="error-title">Transaksi Tidak Ditemukan</div>
                    <div class="error-description">
                        Maaf, data transaksi yang kamu cari tidak ditemukan.
                        Kemungkinan ID transaksi salah atau transaksi sudah tidak tersedia.
                    </div>

                    <hr style="border: 2px dashed #9C9C9C;">

                    <div class="mb-3">
                        <a href="javascript:history.back()" class="btn-back btn-block mb-2">
                            <i class="fa fa-arrow-left mr-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="footer-help text-center">
                <p class="mb-2"><i class="fa fa-question-circle mr-1"></i> Butuh Bantuan?</p>
                <p class="mb-2" style="font-size:12px">Jika kamu merasa ini adalah kesalahan, silahkan hubungi customer care kami.</p>
                <a href="../kontak/" class="btn-contact btn-sm">
                    <i class="fa fa-envelope mr-1"></i> Hubungi Kami
                </a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>
