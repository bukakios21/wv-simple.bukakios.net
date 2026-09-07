<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once("../config.php");


ob_start();
$winner_utama = array(
    '75887',
    '50258',
    '404414',
    '361854',
    '415997',
    '332168',
    '744983',
    '600843',
    '276425',
    '713932',
    '760490',
    '637078',
    '780114',
    '196389',
    '793718',
    '697658',
    '742481',
    '418597',
    '195451',
    '308539',
    '205126',
    '616964',
    '523273',
    '725973',
    '413070',
    '592668',
    '186529',
    '444665',
    '757940',
    '91109',
    '265132',
    '180423',
    '788638',
    '787354',
    '802174',
    '622650',
    '476792',
    '390509',
    '306600',
    '303312',
    '279352',
    '642943',
    '289755',
    '707216',
    '300948',
    '683112',
    '246199',
    '564798',
    '820010',
    '380655',
);

$beruntung_10 = array(
    '633172',
    '746862',
    '737328',
    '317070',
    '561293',
    '661333',
    '382802',
    '711510',
    '265410',
    '724921',
);


$beruntung_20_orang = array(
    '649057',
    '615911',
    '29303',
    '245853',
    '343177',
    '504104',
    '505955',
    '200395',
    '733705',
    '395873',
    '421531',
    '455386',
    '495904',
    '646136',
    '799207',
    '428140',
    '520358',
    '522567',
    '316221',
    '354145',
);

$beruntung_70_orang = array(
    '451618',
    '804831',
    '601818',
    '355408',
    '326176',
    '241137',
    '583373',
    '753840',
    '531543',
    '347142',
    '216972',
    '587198',
    '583431',
    '571981',
    '385319',
    '396552',
    '504923',
    '642855',
    '375579',
    '420064',
    '293545',
    '308733',
    '769887',
    '283405',
    '220880',
    '231509',
    '572175',
    '447878',
    '204245',
    '709119',
    '348589',
    '394774',
    '535169',
    '284341',
    '790327',
    '653693',
    '704444',
    '163997',
    '214832',
    '406096',
    '296145',
    '758481',
    '579906',
    '390481',
    '812903',
    '263441',
    '659014',
    '188104',
    '516869',
    '30518',
    '766657',
    '487599',
    '322824',
    '598045',
    '676472',
    '237642',
    '682820',
    '558318',
    '532308',
    '395029',
    '380148',
    '725266',
    '422369',
    '663887',
    '365085',
    '427812',
    '464632',
    '336439',
    '196742',
    '774942',
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

function rupiah($angka)
{

    $hasil_rupiah = "Rp " . number_format($angka, 0, ',', '.');
    return $hasil_rupiah;
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
        <span class="text-muted title-child">Daftar Pemenang Event Pantun BukaKios</span>
        <hr>
        <?php
        $no = 1;
        $utama_ni = '';

        foreach ($winner_utama as $utama) {
            $data_user = $app->grab_data("$api_url/v1/data_user.php?key=$api_key&id=$utama");
            $data_user_res = json_decode($data_user, true);
            $hp = substr($data_user_res['data']['hp'], 0, -5) . 'xxx';
            if ($no == 1) {
                $hadiah = "Rp 1.500.000";
            } elseif ($no == 2) {
                $hadiah = "Rp 1.200.000";
            } elseif ($no == 3) {
                $hadiah = "Rp 800.000";
            } elseif ($no == 4) {
                $hadiah = "Rp 500.000";
            } elseif ($no == 5) {
                $hadiah = "Rp 450.000";
            } elseif ($no == 6) {
                $hadiah = "Rp 420.000";
            } elseif ($no == 7) {
                $hadiah = "Rp 390.000";
            } elseif ($no == 8) {
                $hadiah = "Rp 360.000";
            } elseif ($no == 9) {
                $hadiah = "Rp 330.000";
            } elseif ($no == 10) {
                $hadiah = "Rp 300.000";
            } elseif ($no == 11) {
                $hadiah = "Rp 280.000";
            } elseif ($no == 12) {
                $hadiah = "Rp 260.000";
            } elseif ($no == 13) {
                $hadiah = "Rp 240.000";
            } elseif ($no == 14) {
                $hadiah = "Rp 220.000";
            } elseif ($no == 15) {
                $hadiah = "Rp 200.000";
            } elseif ($no >= 16 and $no <= 20) {
                $hadiah = "Rp 180.000";
            } elseif ($no >= 21 and $no <= 25) {
                $hadiah = "Rp 160.000";
            } elseif ($no >= 26 and $no <= 30) {
                $hadiah = "Rp 140.000";
            } elseif ($no >= 31 and $no <= 35) {
                $hadiah = "Rp 120.000";
            } elseif ($no >= 36 and $no <= 40) {
                $hadiah = "Rp 100.000";
            } elseif ($no >= 41 and $no <= 45) {
                $hadiah = "Rp 80.000";
            } elseif ($no >= 46 and $no <= 50) {
                $hadiah = "Rp 50.000";
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
        $_50_winner = "";
        $_150_winner = "";
        $_410_winner = "";

        foreach ($beruntung_10 as $cepat) {
            $data_user = $app->grab_data("$api_url/v1/data_user.php?key=$api_key&id=$cepat");
            $data_user_res = json_decode($data_user, true);
            $hp = substr($data_user_res['data']['hp'], 0, -5) . 'xxx';
            if (isset($data_user_res['status'])) {
                if ($data_user_res['status'] == 1) {
                    $_50_winner .= '<tr><td><b>' . $data_user_res["data"]["nama"] . '</b>(' . $hp . ')</td>
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
        foreach ($beruntung_20_orang as $untung) {
            $data_user = $app->grab_data("$api_url/v1/data_user.php?key=$api_key&id=$untung");
            $data_user_res = json_decode($data_user, true);
            $hp = substr($data_user_res['data']['hp'], 0, -5) . 'xxx';
            if (isset($data_user_res['status'])) {
                if ($data_user_res['status'] == 1) {
                    $_150_winner .= '<tr><td><b>' . $data_user_res["data"]["nama"] . '</b>(' . $hp . ')</td>
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
        foreach ($beruntung_70_orang as $untung) {
            $data_user = $app->grab_data("$api_url/v1/data_user.php?key=$api_key&id=$untung");
            $data_user_res = json_decode($data_user, true);
            $hp = substr($data_user_res['data']['hp'], 0, -5) . 'xxx';
            if (isset($data_user_res['status'])) {
                if ($data_user_res['status'] == 1) {
                    $_410_winner .= '<tr><td><b>' . $data_user_res["data"]["nama"] . '</b>(' . $hp . ')</td>
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
        $winner = str_replace('{{100rb}}', $_50_winner, $winner);
        $winner = str_replace('{{50rb}}', $_150_winner, $winner);
        $winner = str_replace('{{20rb}}', $_410_winner, $winner);

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