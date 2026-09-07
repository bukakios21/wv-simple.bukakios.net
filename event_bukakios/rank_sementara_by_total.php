<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
if (!isset($_GET['total'], $_GET['periode'])) {
    echo "Need data";
    exit;
}
$total = abs((int) $_GET['total']);
$periode = htmlentities($_GET['periode']);

require_once("../config.php");
require_once("../_session.php");
// $user_id = 39958;
$url = "$api_url/_event/pemenang_by_total.php?key=$api_key&periode=$periode&uid=$user_id&page=1&limit=500&total=$total";
// echo $url;

$res = $app->grab_data($url);
$res_en = json_decode($res, true);
if (isset($res_en['status'])) {
    if ($res_en['status'] == 1) {
        $winner_utama = $res_en['data'];
    }
}


?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Nunito" />
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <title>title::Calon Pemenang</title>
    <style>
        /* body {
            background-color: #f4f4f4;
        } */

        a {
            text-decoration: none;
            color: #000;
        }

        a:hover {
            color: #000;
            text-decoration: none;

        }

        .child {
            position: absolute;
            right: 0px;
            border-top-right-radius: 10px;
            border-bottom-left-radius: 10px;
            background-color: #f9a825;
            color: #fff;
            width: 55%;
            font-size: 15px;
            padding: 1px 0px 1px 10px;
        }

        .title {
            background-color: #fff;
            padding-top: 6px;
            margin-bottom: -9px;
            font-weight: bold;
        }

        hr.first {
            border: 1px solid #f4f4f4;
        }

        hr.second {
            border: 3px solid #f4f4f4;
        }

        div.scrollmenu {
            /* background-color: #333; */
            overflow: auto;
            white-space: nowrap;
        }

        div.scrollmenu a {
            display: inline-block;
            color: #333;
            text-align: center;
            padding: 14px;
            text-decoration: none;
        }

        div.scrollmenu a:hover {
            background-color: #0082ed;
            color: #fff;
            border-radius: 5px;
            padding: 5px 5px 5px 5px;
        }

        .circle {
            border-radius: 50%;
            height: 65px;
            width: 100px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #fff;
        }

        @-webkit-keyframes anim-glow1 {
            0% {
                box-shadow: 0 0 #FFD700;
            }

            100% {
                box-shadow: 0 0 10px 8px transparent;
                border-width: 2px;
            }
        }

        @keyframes anim-glow1 {
            0% {
                box-shadow: 0 0 #FFD700;
            }

            100% {
                box-shadow: 0 0 10px 8px transparent;
                border-width: 2px;
            }
        }

        .img-glow1 {
            animation: anim-glow1 2s ease infinite;
            border-radius: 50%;
            width: 100%;
        }

        @-webkit-keyframes anim-glow2 {
            0% {
                box-shadow: 0 0 #C0C0C0;
            }

            100% {
                box-shadow: 0 0 10px 8px transparent;
                border-width: 2px;
            }
        }

        @keyframes anim-glow2 {
            0% {
                box-shadow: 0 0 #C0C0C0;
            }

            100% {
                box-shadow: 0 0 10px 8px transparent;
                border-width: 2px;
            }
        }

        .img-glow2 {
            animation: anim-glow2 2s ease infinite;
            border-radius: 50%;
            width: 90%;
        }

        @-webkit-keyframes anim-glow3 {
            0% {
                box-shadow: 0 0 #CD7F32;
            }

            100% {
                box-shadow: 0 0 10px 8px transparent;
                border-width: 2px;
            }
        }

        @keyframes anim-glow3 {
            0% {
                box-shadow: 0 0 #CD7F32;
            }

            100% {
                box-shadow: 0 0 10px 8px transparent;
                border-width: 2px;
            }
        }

        .img-glow3 {
            animation: anim-glow3 2s ease infinite;
            border-radius: 50%;
            width: 90%;
        }

        .bayar-id {
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);

        }
    </style>
</head>

<body style="background-color:#fff">
    <div style="background-color:<?= $primary ?>">
        <div class="mx-auto py-5 mb-2 text-center">
            <!-- <img width="20%" src="https://assets.bukakios.net/img2/uploads/2021/01/537-trophy.png"> -->
            <!-- <h5 style="color:white;margin-top:10px">Daftar Ranking Sementara</h5> -->
        </div>
    </div>
    <div class="container">

        <div class="container">
            <div class="alert alert-info">Calon Pemenang Event!</div>
        </div>
        <div class="mt-3">
            <div class="container">
                <div class="table-responsive">
                    <table class="table table-bordered table-sriped">
                        <thead>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Toko</th>
                            <!-- <th>Poin</th> -->
                        </thead>
                        <tbody>
                            <?php
                            if (isset($winner_utama)) {
                                $no = 1;
                                foreach ($winner_utama as $row) {
                                    $nama = $row['nama'];
                                    $toko = $row['nama_toko'];
                                    $poin = $row['poin'];
                            ?>
                                    <tr>
                                        <td><?php echo $no ?></td>
                                        <td><?php echo $nama ?></td>
                                        <td><?php echo $toko ?></td>
                                        <!-- <td><?php echo $poin ?></td> -->
                                    </tr>

                            <?php
                                    $no++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <script src="../assets/js/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="../assets/js/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="../assets/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/sweetalert.min.js"></script>
    <script src="https://member.bukakios.net/js/lib/notie/notie.js"></script>
    <script src="https://member.bukakios.net/js/me/copy.js"></script>

</body>

</html>