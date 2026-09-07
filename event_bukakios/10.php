<?php
require_once("../config.php");
require_once("../_session.php");



ob_start();
$winner_utama = array(
    array(
        'nama' => "Maburo Cell",
        "email" => "solihinmuhammad376@gmail.com",
        "rata" => "25",
    ),
    array(
        'nama' => "Abdi Cell",
        "email" => "abdic183@gmail.com",
        "rata" => "25",
    ),
    array(
        'nama' => "Qirani cell",
        "email" => "fenny.bassamah@gmail.com",
        "rata" => "36",
    ),
    array(
        'nama' => "PR4M CELL",
        "email" => "riapramana98@gmail.com",
        "rata" => "25",
    ),
    array(
        'nama' => "Kings Cell",
        "email" => "Wijayadandi24@gmail.com",
        "rata" => "29",
    ),
);

$no = 1;

function replace_email($email)
{
    // echo $email;
    $count = abs((int) strlen($email)-14);
    // echo $count."<br/>";
    $nomor_hp_sensor = substr_replace($email, str_repeat('*', $count), 2, $count);
    return $nomor_hp_sensor;
}

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
    $ofset = 1;
    $jumlah = strlen($toko) / 2;
    if ($jumlah >= 4) {
        $ofset = 4;
    }
    return substr_replace($toko, str_repeat('*', $jumlah), $ofset, $jumlah);
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
        <span class="text-muted title-child">Daftar Pemenang Event BAKI Periode 1</span>
        <hr>
        <?php
        $no = 1;
        $utama_ni = '';

        $index = 0;
        foreach ($winner_utama as $utama) {
            $utama_ni .= '<tr>
            <td>' . $no . '</td>
            <td>' . rep_toko($utama['nama']) . '</td>
            <td><b>' . replace_email($utama['email']) . '</td>
            <td>' . $utama['rata']. '</td>

        </tr>';
            $no++;
            $index++;
        }


        $winner = html_entity_decode($data_res['data']['e_pemenang']);
        $winner = str_replace('{{pemenang}}', $utama_ni, $winner);

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
// file_put_contents("$e_id.html", ob_get_contents());
?>