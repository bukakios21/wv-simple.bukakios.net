<?php
require_once("../config.php");
//require_once("../config_db.php");
$b = $api_url;
require_once("../_session.php");


$pemenang = $app->grab_data("$api_url/_event/pemenang_poin_4500.php?key=$api_key&periode=02-2023&uid=$user_id&page=1&limit=50");
$pemenang_res = json_decode($pemenang, true);
$rank = 1;
foreach ($data_user['data'] as $a) {
    if ($a['nama'] == $personal['data']['nama']) {
        $str = $rank;
        break;
    } else {
        $str = $rank + 1;
    }
    $rank++;
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
    <title>Ranking sementara</title>
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
            <img width="20%" src="https://assets.bukakios.net/img2/uploads/2021/01/537-trophy.png">
            <h5 style="color:white;margin-top:10px">Pemenang sementara</h5>
        </div>
    </div>
    <div class="">

        <div class="container">

            <div class="alert alert-primary">
                Data ranking di update secara realtime, poin yang di hitung hanya poin yang kamu kumpulkan di bulan Februari 2023</div>
            <hr />
            <div class="table-responsive" style="padding: 10px;">
                <table class="table table-bordered table-striped">
                    <thead>
                        <th>#ID</th>
                        <th>Nama</th>
                        <th>Toko</th>
                    </thead>
                    <tbody>
                    <?php 
                        if (isset($pemenang_res['status']) and $pemenang_res['status']==1){
                            $no=1;
                            foreach ($pemenang_res['data'] as $row){
                                ?>
                                    <tr>
                                        <td><?php echo $no ?></td>
                                        <td><?php echo $row['nama'] ?></td>
                                        <td><?php echo $row['nama_toko'] ?></td>
                                    </tr>
                                <?php 
                                $no++;
                            }
                        }
                    ?>
                </tbody>
                </table>
              
            </div>
            <hr />
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