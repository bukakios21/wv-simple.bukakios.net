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
    '160519',
    '424060',
    '323202',
    '276425',
    '304819',
    '390274',
    '306600',
    '236995',
    '314270',
    '377549',
    '246219',
    '321814',
    '188731',
    '340131',
    '323755',
    '377906',
    '192235',
    '216614',
    '47178',
    '346426',
    '66506',
    '195451',
    '188497',
    '305352',
    '302710',
    '75887',
    '238204',
    '198928',
    '361854',
    '226129',
    '288933',
    '186071',
    '186529',
    '322613',
    '111463',
    '281376',
    '307044',
    '222046',
    '12683',
    '287773',
    '278450',
    '289755',
    '43406',
    '152791',
    '333257',
    '378578',
    '281707',
    '266632',
    '349254',
    '67477',
);

$_100rb = array(
  '422031',
  '33847',
  '296610',
  '404109',
  '457623',
  '416022',
  '43764',
  '405546',
  '397507',
  '402909'

);

$_50rb = array(
  '341549',
  '360191',
  '293755',
  '290971',
  '467341',
  '169191',
  '470976',
  '314702',
  '306982',
  '464747',
  '348092',
  '452618',
  '458988',
  '44429',
  '112424',
  '467372',
  '271729',
  '333172',
  '185939',
  '394162',

);

$_20rb = array(
  '313464',
  '418119',
  '437311',
  '226962',
  '353114',
  '433691',
  '470459',
  '12878',
  '378155',
  '461728',
  '345766',
  '442346',
  '276413',
  '314983',
  '319956',
  '416916',
  '47242',
  '458731',
  '364023',
  '406394',
  '413864',
  '357319',
  '296856',
  '253075',
  '428156',
  '428934',
  '188004',
  '445432',
  '431793',
  '358563',
  '419995',
  '380701',
  '445998',
  '446897',
  '212099',
  '376487',
  '245285',
  '376348',
  '322168',
  '329794',
  '179553',
  '428164',
  '467313',
  '65896',
  '435003',
  '426720',
  '413529',
  '223236',
  '464868',
  '450545',
  '440098',
  '418039',
  '464791',
  '388991',
  '456129',
  '446062',
  '421378',
  '267196',
  '264213',
  '450993',
  '66195',
  '422425',
  '448652',
  '456042',
  '314346',
  '309703',
  '384063',
  '203157',
  '347317',
  '379929',
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

function rupiah($angka){
	
	$hasil_rupiah = "Rp " . number_format($angka,0,',','.');
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
                $hadiah = "Rp 1.500.000";
            } elseif ($no == 2) {
                $hadiah = "Rp 1.200.000";
            } elseif ($no == 3) {
                $hadiah = "Rp 800.000";
            } elseif ($no == 4) {
                $hadiah = "Rp 500.000";
            } elseif ($no == 5) {
                $hadiah = "Rp 450.000";
            } elseif ($no  ==6) {
                $hadiah = "Rp 420.000";
                $hadiah_c = 420000;
            } elseif ($no  > 6 and $no <= 10) {
              $hadiah = $hadiah_c - 30000;
              $hadiah_c = $hadiah;
              $hadiah = rupiah($hadiah_c);     
            } elseif ($no == 11) {
              $hadiah = $hadiah_c - 20000;
              $hadiah_c = $hadiah;
              $hadiah = rupiah($hadiah_c);     
            }  elseif ($no >= 12 and $no <= 15) {
              $hadiah = $hadiah_c - 20000;
              $hadiah_c = $hadiah;
              $hadiah = rupiah($hadiah_c);     
            }elseif ($no >= 16 and $no <=20) {
              // $hadiah = $hadiah_c - 20000;
              $hadiah_c = 180000;
              $hadiah = rupiah($hadiah_c);     
            } elseif ($no >= 21 and $no <=25) {
              // $hadiah = $hadiah_c - 20000;
              $hadiah_c = 160000;
              $hadiah = rupiah($hadiah_c);     
            } elseif ($no >= 26 and $no <=30) {
              // $hadiah = $hadiah_c - 20000;
              $hadiah_c = 140000;
              $hadiah = rupiah($hadiah_c);     
            } 
            elseif ($no >= 31 and $no <=35) {
              // $hadiah = $hadiah_c - 20000;
              $hadiah_c = 120000;
              $hadiah = rupiah($hadiah_c);     
            } 
            elseif ($no >= 36 and $no <=40) {
              // $hadiah = $hadiah_c - 20000;
              $hadiah_c = 100000;
              $hadiah = rupiah($hadiah_c);     
            } 
            elseif ($no >= 41 and $no <=45) {
              // $hadiah = $hadiah_c - 20000;
              $hadiah_c = 80000;
              $hadiah = rupiah($hadiah_c);     
            } elseif ($no >= 46 and $no <=50) {
              // $hadiah = $hadiah_c - 20000;
              $hadiah_c = 50000;
              $hadiah = rupiah($hadiah_c);     
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