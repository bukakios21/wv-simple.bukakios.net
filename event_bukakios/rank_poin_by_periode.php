<?php
require_once("../config.php");
// if ($user_id == "39958"){
//     $app->simpan_file("ab.txt", $user_jwt);
// }
if (!isset($_GET['periode'])){
    echo "ERROR #1";exit;
}
$periode = $_GET['periode'];

require_once("../_session.php");
require_once("../lib/ApiV2.php");
$api_v2 = new ApiV2($user_jwt);

//get user
$user = $api_v2->detail_user();
$user_res = json_decode($user, true);

// $user_id = $user_res['id'];
// $data_user = $app->grab_data("$api_url/v1/data_user.php?key=$api_key&id=$user_id");
$data_user = $app->grab_data("$api_url/_event/pemenang.php?key=$api_key&periode=$periode&uid=$user_id&page=1&limit=50");
// echo $data_user;
// $personal = $app->grab_data("$api_url/v1/data_user.php?key=$api_key&id=$user_id");
$data_user = json_decode($data_user, true);
// $personal = json_decode($personal, true);
$personal = $user_res;
$rank = 1;
foreach ($data_user['data'] as $a) {
    if ($a['uid'] == $personal['data']['id']) {
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
<div style="background-color:<?=$primary?>">
    <div class="mx-auto py-5 mb-2 text-center">
        <img width="20%" src="https://assets.bukakios.net/img2/uploads/2021/01/537-trophy.png">
        <h5 style="color:white;margin-top:10px">Daftar Ranking Sementara</h5>
    </div>
</div>
<div class="container">

    <div class="container">

        <div class="alert alert-primary">
            Data ranking di update secara realtime, poin yang di hitung hanya poin yang kamu kumpulkan di bulan Agustus 2023
        </div>
        <hr />
        <strong style="font-size:20px">Rangking Kamu</strong>
        <table border="0" width="100%">
            <tr>
                <td rowspan="2" class="text-center" width="20px">
                    <?php
                    if ($str > 50) {
                        echo "<strong>50+</strong>";
                        $notiff = "<b>Silahkan Lanjutkan Transaksi anda Kembali, untuk Mendapatkan Point Lebih Tinggi</b>";
                    } elseif ($str == 1) { ?>
                        <img src='../assets/img/gold-medal.png' width='30px'>
                        <?php
                        $notiff = "<b>Waahh Ayoo Tetap Berada Dijalur Ini, Pertahankan Transaksi Anda.</b>";
                    } elseif ($str == 2) { ?>
                        <img src='../assets/img/silver-medal.png' width='30px'>
                        <?php
                        $notiff = "<b>Ayoo Sedikit Lagi Supaya Bisa Meraih Ke Podium Utama, Silahkan Tingkatkan Transaksi Anda.</b>";
                    } elseif ($str == 3) { ?>
                        <img src='../assets/img/bronze-medal.png' width='30px'>
                        <?php
                        $notiff = "<b>Ayoo Sedikit Lagi Supaya Bisa Meraih Ke Podium Utama, Silahkan Tingkatkan Transaksi Anda.</b>";
                    } else {
                        echo "<strong>" . $str . "</strong>";
                        $notiff = "<b>Ayoo Jangan Merasa nyaman Di zona ini Mari Bangkit dengan Tingkatkan Transaksi Anda.</b>";
                    }
                    $max_p_p = 10000;

                    $progress = ($data_user['poin_saya'] / $max_p_p) * 100;
                    if ($progress >= 90) {
                        $style = "background-image: linear-gradient(to right, #ff0000 , #e60000)";
                    } elseif ($progress >= 80) {
                        $style = "background-image: linear-gradient(to right, #53ff1a , #ff0000)";
                    } elseif ($progress >= 60) {
                        $style = "background-image: linear-gradient(to right, #ffff00 , #53ff1a)";
                    } elseif ($progress >= 40) {
                        $style = "background-image: linear-gradient(to right, #2196F3 , #ffff00)";
                    } elseif ($progress >= 20) {
                        $style = "background-image: linear-gradient(to right, #1ac6ff , #2196F3)";
                    } else {
                        $style = "background-color: #1ac6ff";
                    }
                    ?>
                </td>
                <td rowspan="2" class="text-center" width="70px">
                    <img src="<?php echo $personal['data']['image']; ?>" style="background-size: cover;border-radius:50% 50% 50% 50%;width:50px;height:50px;">
                </td>
                <td width="180px"><?php echo $personal['data']['nama']; ?></td>
                <td>
                    <!-- <div class="badge badge-primary text-white">
                            <img src="../assets/img/produk/ic_coin.png" width="15px">
                            <?php echo $data_user['poin_saya']; ?>
                        </div> -->
                    <!-- <div class="badge bayar-id">
                            <img src="../assets/img/produk/ic_coin.png" width="15px">
                            <?php //echo $data_user['poin_saya']; ?>
                        </div> -->
                    <div class="text-center">
                        <img src="../assets/img/produk/ic_coin.png" width="15px">
                        <b style="font-size:11px"><?php echo $data_user['poin_saya']; ?></b>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" style="width: <?php echo $progress ?>%;<?php echo $style ?>" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </td>
            </tr>
        </table>
        <hr />
        <strong style="font-size:20px">Ranking 1 - 50</strong>
        <hr />
    </div>
    <div class="mt-3">
        <div class="container">
            <table border="0" width="100%">
                <?php
                $a = 1;
                for ($i = 0; $i < count($data_user['data']); $i++) {
                    $no = substr($data_user['data'][$i]['hp'], 0, -4) . 'xxxx';
                    $point = $data_user['data'][$i]['poin'];
                    $nama = $data_user['data'][$i]['nama'];
                    $jml = ceil(strlen($nama) / 2);
                    //$namaaa = substr($data_user['data'][$i]['nama'], 0, -$jml) . '...';
                    $namaaa = $data_user['data'][$i]['nama'];
                    $max_p_p = 10000;

                    $progress = ($point / $max_p_p) * 100;

                    if ($a == 1) {
                        $class_img = "img-glow1";
                        $style_img = "border:2px solid #FFD700";
                        $medal = "<img src='https://assets.bukakios.net/img2/uploads/2021/03/748-gold-medal.png' width='30px'>";
                    } elseif ($a == 2) {
                        $class_img = "img-glow2";
                        $style_img = "border:2px solid #C0C0C0";
                        $medal = "<img src='https://assets.bukakios.net/img2/uploads/2021/03/729-silver-medal.png' width='30px'>";
                    } elseif ($a == 3) {
                        $class_img = "img-glow3";
                        $style_img = "border:2px solid #CD7F32";
                        $medal = "<img src='https://assets.bukakios.net/img2/uploads/2021/03/233-bronze-medal.png' width='30px'>";
                    } else {
                        $medal = "<strong>" . $a . "</strong>";
                        $class_img = "";
                        $style_img = "";
                    }

                    if ($progress > 80) {
                        $style = "background-image: linear-gradient(to right, #53ff1a , #ff0000)";
                    } elseif ($progress > 60) {
                        $style = "background-image: linear-gradient(to right, #ffff00 , #53ff1a)";
                    } elseif ($progress > 40) {
                        $style = "background-image: linear-gradient(to right, #2196F3 , #ffff00)";
                    } elseif ($progress > 20) {
                        $style = "background-image: linear-gradient(to right, #1ac6ff , #2196F3)";
                    } else {
                        $style = "background-color: #1ac6ff";
                    }
                    if ($data_user['data'][$i]['uid'] != "1001" and $data_user['data'][$i]['uid'] != "819500"){
                        ?>
                        <tr>
                            <td rowspan="2" class="text-center" width="20px" height="80px">
                                <?php echo $medal ?>
                            </td>
                            <td rowspan="2" class="text-center" width="70px">
                                <img class="<?php echo $class_img; ?>" src="<?php echo $data_user['data'][$i]['image'] ?>" style="background-size: cover;border-radius:50% 50% 50% 50%;width:50px;height:50px; <?php echo $style_img; ?>">
                            </td>
                            <td width="180px"><?php echo $namaaa . "<br>(" . $no . ")"; ?></td>
                            <td>
                                <!-- <span class="badge badge-primary text-white"><i class="fa fa-coins text-white"></i> <?php echo $data_user['data'][$i]['poin'] ?></span> -->
                                <div class="text-center">
                                    <img src="../assets/img/produk/ic_coin.png" width="15px">
                                    <b style="font-size:11px"><?php echo $data_user['data'][$i]['poin'] ?></b>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar" role="progressbar" style="width: <?php echo $progress ?>%;<?php echo $style ?>" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                    
                    $a++;
                }
                ?>
            </table>

            <div class="my-3">
                <!-- <div class="container"> -->
                <div class="alert alert-warning">
                    <div class="row">
                        <div class="col-2 my-auto ">
                            <img src="https://assets.bukakios.net/img2/uploads/2021/03/857-disclaimer.png" width="50px">
                        </div>
                        <div class="col-10 text-justify">
                            Berdsarkan Rank Anda, <?php echo $notiff ?>.
                        </div>
                    </div>
                </div>
                <!-- </div> -->
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