<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once("../config.php");
//$user_id = 39958;
// require_once("../_session.php");
// if ($user_id != 39958){
//     exit;
// }
ob_start();
$winner_utama = array(
    "287391",
    "223540",
    "1008",
    "246219",
    "192235",
    "198928",
    "276425",
    "268602",
    "226129",
    "152306",
    "186529",
    "185669",
    "216614",
    "153666",
    "66506",
    "276638",
    "186071",
    "111463",
    "216984",
    "47849"

);

$_100rb = array(
    "291443",
    "296973",
    "274240",
    "304310",
    "49213",
    "30359",
    "269516",
    "270245",
    "293368",
    "130069",

);

$_50rb = array(
    "320930",
    "288195",
    "205024",
    "273571",
    "275483",
    "318221",
    "252062",
    "238844",
    "291367",
    "301353",
    "276361",
    "288685",
    "298587",
    "296112",
    "321123",
    "319424",
    "44375",
    "120262",
    "211636",
    "269343"

);

$_20rb = array(
    "44549",
    "290732",
    "314017",
    "310498",
    "301290",
    "321514",
    "181817",
    "186747",
    "304004",
    "321855",
    "318585",
    "264861",
    "244581",
    "206336",
    "322659",
    "300085",
    "208747",
    "290292",
    "229194",
    "309061",
    "314564",
    "308501",
    "294161",
    "266564",
    "311709",
    "266433",
    "306875",
    "162156",
    "308728",
    "113023",
    "308160",
    "295114",
    "222387",
    "294561",
    "284855",
    "319962",
    "211929",
    "252915",
    "267867",
    "211232",
    "304285",
    "199230",
    "314112",
    "297203",
    "126858",
    "295672",
    "292105",
    "301730",
    "69842",
    "214246",
    "320225",
    "323699",
    "312734",
    "296975",
    "303337",
    "323706",
    "218259",
    "295790",
    "314115",
    "315615",
    "261339",
    "254579",
    "49837",
    "298193",
    "306501",
    "160375",
    "269051",
    "306044",
    "297077",
    "205094"
);

$no = 1;


if (!isset($_GET['e_id'])) {
    echo "WRONG PARAMETER !!";
    exit;
}
$e_id  = abs((int) $_GET['e_id']);
if (file_exists("$e_id.html")) {
    include("$e_id.html");
    exit;
}

$e_id  = abs((int) $_GET['e_id']);
$data_api = $app->grab_data("https://ms1.bukakios.net/v1/api_event/api_show_event.php?key=$api_key&act=detailevent&e_id=$e_id");
$data_res = json_decode($data_api, true);
if (!isset($data_res['status'])) {
    echo "SORRY, UNDER MAINTENANCE !!";
    exit;
}

if ($data_res['status'] == 0) {
    echo $data_res['error_msg'];
    exit;
}

$real_data = $data_res['data'];

function tgl_indo($tanggal)
{
    $bulan = array(
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecahkan = explode('-', $tanggal);

    // variabel pecahkan 0 = tanggal
    // variabel pecahkan 1 = bulan
    // variabel pecahkan 2 = tahun

    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}

function rep_toko($toko)
{
    $jumlah = strlen($toko);
    if ($jumlah > 4) {
        return substr_replace($toko, 'xxxxx', -3);
    } else {
        return substr_replace($toko, 'xx', -2);
    }
}

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Nunito" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://assets.bukakios.net/css/box.css">
    <title>title::Detail Event</title>
    <style>
        body {
            font-family: Nunito;
        }

        a {
            text-decoration: none;
            color: #000;
        }

        a:hover {
            color: #000;
            text-decoration: none;

        }

        hr.first {
            border: 1px solid #f4f4f4;
        }

        hr.second {
            border: 3px solid #f4f4f4;
        }

        .title {
            background-color: #fff;
            padding-top: 6px;
            margin-bottom: -9px;
            font-weight: bold;
        }

        .img-child {
            width: 20px;
        }

        .title-child {
            font-size: 12px;
        }

        .sub-child {
            font-size: 9px;
        }

        .tab-menu {
            font-size: 10px;
        }

        .deskripsi-title {
            font-size: 12px;
        }

        .label-form {
            font-size: 10px;
        }

        .input-form {
            height: 30px;
            font-size: 10px;
        }

        .tmb-submit {
            font-size: 11px;
            height: 30px;
        }

        /* // Default styling here */

        /* // Little larger screen */
        @media only screen and (min-width: 450px) {
            .title {
                background-color: #fff;
                padding-top: 6px;
                margin-bottom: -9px;
                font-weight: bold;
            }

            .img-child {
                width: 26px;
            }

            .title-child {
                font-size: 14px;
            }

            .sub-child {
                font-size: 11px;
            }

            .tab-menu {
                font-size: 12px;
            }

            .deskripsi-title {
                font-size: 12px;
            }

            .label-form {
                font-size: 11px;
            }

            .input-form {
                height: 33px;
                font-size: 11px;
            }

            .tmb-submit {
                font-size: 12px;
                height: 30px;
            }
        }

        /* // Pads and larger phones */
        @media only screen and (min-width: 600px) {
            .title {
                background-color: #fff;
                padding-top: 6px;
                margin-bottom: -9px;
                font-weight: bold;
            }

            .img-child {
                width: 30px;
            }

            .title-child {
                font-size: 16px;
            }

            .sub-child {
                font-size: 13px;
            }

            .tab-menu {
                font-size: 13px;
            }

            .deskripsi-title {
                font-size: 14px;
            }

            .label-form {
                font-size: 13px;
            }

            .input-form {
                height: 37px;
                font-size: 13px;
            }

            .tmb-submit {
                font-size: 13px;
                height: 33px;
            }
        }

        /* // Larger pads */
        @media only screen and (min-width: 768px) {}

        /* // Horizontal pads and laptops */
        @media only screen and (min-width: 992px) {}

        /* // Really large screens */
        @media only screen and (min-width: 1382px) {}

        /* // 2X size (iPhone 4 etc) */
        @media only screen and (-webkit-min-device-pixel-ratio: 1.5),
        only screen and (-o-min-device-pixel-ratio: 3/2),
        only screen and (min-device-pixel-ratio: 1.5) {}
    </style>

</head>

<body>
    <div class="header">
        <img src="<?php echo $real_data['e_image'] ?>" width="100%">
    </div>
    <div class="container title" style="background-color:#fff">
        <span><?php echo $real_data['e_title'] ?></span>
    </div>
    <hr class="first">
    <div class="container" style="background-color:#fff">
        <table style="width:100%">
            <tr>
                <td rowspan="2">
                    <img class="img-child" src="https://assets.bukakios.net/img2/uploads/2021/01/950-clock.png">
                </td>
                <td>
                    <span class="text-muted title-child">Periode Event</span>
                </td>
                <td rowspan="2">
                    <img class="img-child" src="https://assets.bukakios.net/img2/uploads/2021/01/384-trophy-1.png" width="30px">
                </td>
                <td><span class="text-muted title-child">Total Hadiah</span></td>
            </tr>
            <tr>
                <td>
                    <span class="sub-child"><?php echo tgl_indo($real_data['e_mulai']) . " - " . tgl_indo($real_data['e_akhir']) ?></span>
                </td>
                <td><span class="sub-child">Rp. <?php echo $app->angka_id($real_data['e_hadiah']); ?></span></td>
            </tr>
        </table>
    </div>
    <hr class="second">
    <!-- <div class="body"> -->
    <div class="container">
        <span class="text-muted title-child">Daftar Pemenang Event Poin BukaKios</span>
        <hr>
        <?php
        $no = 1;
        $utama_ni = '';

        foreach ($winner_utama as $utama) {
            $data_user = $app->grab_data("$api_url/v1/data_user.php?key=$api_key&id=$utama");
            $data_user_res = json_decode($data_user, true);
            $hp = substr($data_user_res['data']['hp'], 0, -5) . 'xxx';
            if ($no == 1) {
                $hadiah = "Rp3.000.000";
            } elseif ($no == 2) {
                $hadiah = "Rp2.100.000";
            } elseif ($no == 3) {
                $hadiah = "Rp1.100.000";
            } elseif ($no >= 4 and $no <= 10) {
                $hadiah = "Rp400.000";
            } elseif ($no >= 11 and $no <= 20) {
                $hadiah = "Rp200.000";
            }
            if (isset($data_user_res['status'])) {
                if ($data_user_res['status'] == 1) {
                    $utama_ni .= '<tr><td><b>' . $data_user_res["data"]["nama"] . '</b>(' . $hp . ')</td>
                            <td>' . $hadiah . '</td>
                            <td>' . $no . '</td>
                        </tr>';
                } else {
        ?>
                    <div class="alert alert-danger deskripsi-title"><?php echo $data_user_res['error_msg'] ?></div>
                <?php
                }
            } else {
                ?>
                <div class="alert alert-danger deskripsi-title">Gagal menghubung server. Hubungi Customer Service!!</div>
                <?php
            }
            $no++;
        }

        $no = 1;
        $_100rb_winner = "";
        $_50rb_winner = "";
        $_20rb_winner = "";

        foreach ($_100rb as $cepat) {
            $data_user = $app->grab_data("$api_url/v1/data_user.php?key=$api_key&id=$cepat");
            $data_user_res = json_decode($data_user, true);
            $hp = substr($data_user_res['data']['hp'], 0, -5) . 'xxx';
            if (isset($data_user_res['status'])) {
                if ($data_user_res['status'] == 1) {
                    $_100rb_winner .= '<tr><td><b>' . $data_user_res["data"]["nama"] . '</b>(' . $hp . ')</td>
                            <td>Rp100.000</td>
                            <td>' . $no . '</td>
                        </tr>';
                } else {
                ?>
                    <div class="alert alert-danger deskripsi-title"><?php echo $data_user_res['error_msg'] ?></div>
                <?php
                }
            } else {
                ?>
                <div class="alert alert-danger deskripsi-title">Gagal menghubung server. Hubungi Customer Service!!</div>
                <?php
            }
            $no++;
        }

        $no = 1;
        foreach ($_50rb as $untung) {
            $data_user = $app->grab_data("$api_url/v1/data_user.php?key=$api_key&id=$untung");
            $data_user_res = json_decode($data_user, true);
            $hp = substr($data_user_res['data']['hp'], 0, -5) . 'xxx';
            if (isset($data_user_res['status'])) {
                if ($data_user_res['status'] == 1) {
                    $_50rb_winner .= '<tr><td><b>' . $data_user_res["data"]["nama"] . '</b>(' . $hp . ')</td>
                            <td>Rp50.000</td>
                            <td>' . $no . '</td>
                        </tr>';
                } else {
                ?>
                    <div class="alert alert-danger deskripsi-title"><?php echo $data_user_res['error_msg'] ?></div>
                <?php
                }
            } else {
                ?>
                <div class="alert alert-danger deskripsi-title">Gagal menghubung server. Hubungi Customer Service!!</div>
                <?php
            }
            $no++;
        }

        $no = 1;
        foreach ($_20rb as $untung) {
            $data_user = $app->grab_data("$api_url/v1/data_user.php?key=$api_key&id=$untung");
            $data_user_res = json_decode($data_user, true);
            $hp = substr($data_user_res['data']['hp'], 0, -5) . 'xxx';
            if (isset($data_user_res['status'])) {
                if ($data_user_res['status'] == 1) {
                    $_20rb_winner .= '<tr><td><b>' . $data_user_res["data"]["nama"] . '</b>(' . $hp . ')</td>
                            <td>Rp20.000</td>
                            <td>' . $no . '</td>
                        </tr>';
                } else {
                ?>
                    <div class="alert alert-danger deskripsi-title"><?php echo $data_user_res['error_msg'] ?></div>
                <?php
                }
            } else {
                ?>
                <div class="alert alert-danger deskripsi-title">Gagal menghubung server. Hubungi Customer Service!!</div>
        <?php
            }
            $no++;
        }


        $winner = html_entity_decode($data_res['data']['e_pemenang']);
        $winner = str_replace('{{utama}}', $utama_ni, $winner);
        $winner = str_replace('{{100rb}}', $_100rb_winner, $winner);
        $winner = str_replace('{{50rb}}', $_50rb_winner, $winner);
        $winner = str_replace('{{20rb}}', $_20rb_winner, $winner);

        echo $winner;

        ?>

    </div>
    <!-- </div> -->

    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>


<?php
file_put_contents("$e_id.html", ob_get_contents());
?>