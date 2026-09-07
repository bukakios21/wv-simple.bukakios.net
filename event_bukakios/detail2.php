<?php
require_once("../config.php");
//$user_id = 39958;
require_once("../_session.php");



if (!isset($_GET['e_id'])) {
    echo "WRONG PARAMETER !!";
    exit;
}
$e_id  = abs((int) $_GET['e_id']);

/*if ($e_id == 7 and $user_id != 39958 and $user_id != 1000 and $user_id != 29084){
	echo "<h4>Maaf, Event Belum di Buka !</h4>";exit;
}*/

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

if (isset($_POST['submit'])) {
    require_once('_act.php');
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
    <span class="text-muted title-child">Detail event</span>
    <hr>
    <?php
       if ($real_data['e_akhir'] < $today){
           ?>
                <a class='btn btn-success btn-block' href='winner.php?e_id=<?php echo $e_id ?>'>Daftar Pemenang Event</a><br/>
           <?php
       }
    ?>

    <?php
    $belum_mendaftar = true;
    if($belum_mendaftar) {
        if ($real_data['e_akhir'] < $today){
            $build_form = "<div class='alert alert-warning'>Event ini telah berakhir !!</div><br/><a class='btn btn-success btn-block' href='winner.php?e_id=$e_id'>Daftar Pemenang Event</a>";
        }else{
            $build_form = "<form method='post' action=''>";
            $e_form = $real_data['e_form'];
            $ex_form = explode("\n", $e_form);
            foreach ($ex_form as $ex_form_res) {
                $data_ex = $ex_form_res;
                $ex_data = explode(";", $data_ex);
                $required = "";
                if ($ex_data[2] == 1) {
                    $required = "required";
                }
                $build_form = $build_form . "<div class='form-group'>
                <label for='' class='label-form'>$ex_data[0]</label>
                <input class='form-control input-form' type='text' name='$ex_data[1]' $required/>
            </div>";
            }
            $build_form = $build_form . "<input type='hidden' name='submit'>
            <button class='btn btn-success btn-block tmb-submit' type='submit'>Kirim Data</button>
            </form>";
        }
    }else{
        $build_form = "<div class='alert alert-warning'>Kamu sudah mendaftar ke event ini, 1 akun bukakios hanya bisa mengikuti 1x event ini</div><br/>";
    }
    $html =  html_entity_decode($real_data['e_html']);
    $html = str_replace("{{formIkuti}}",$build_form,$html);
    if ($e_id == 7 ){
        $data =  md5(md5("event-bukakios-pantun-2021::$user_id:$today"));
        $open_url = "open://https://wv.bukakios.net/event_bukakios/event_rating_pantun_bukakios.php?data=$user_id-$data";
        $html = str_replace("{{linkEvent}}", $open_url, $html);
    }

    if ($e_id == 9 ){
        $hash = md5("$user_id-bk-rating-2022");
        $open_url = "open://https://wv.bukakios.net/event_bukakios/event_rating_ps.php?auth=$user_id-$hash";
        $html = str_replace("{{linkEvent}}", $open_url, $html);
    }

    if ($e_id == 16 ){
        $hash = md5("$user_id-bk-rating-2022");
        $open_url = "open://https://wv.bukakios.net/event_bukakios/event_rating_ps.php?auth=$user_id-$hash";
        $html = str_replace("{{linkEvent}}", $open_url, $html);
    }

    if ($e_id == 21 ){
        $hash = md5("$user_id-bk-rating-2022");
        $open_url = "open://https://wv.bukakios.net/event_bukakios/event_rating_ps.php?auth=$user_id-$hash";
        $html = str_replace("{{linkEvent}}", $open_url, $html);
    }

    echo $html;
    ?>

</div>
<!-- </div> -->

<?php 


?>

</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>