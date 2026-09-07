<?php
require_once("../config.php");
//$user_id = 39958;
require_once("../_session.php");

ob_start();
$winner_utama = array(
    '304819',
    '216614',
    '323202',
);

$winner_utama_ke2 = array(
    '222046',
    '186529',
    '276425',
    '246219',
    '216984',
    '66506',
    '198928',
);

$winner_utama_ke3 = array(
    '76209',
    '306600',
    '192235',
    '47178',
    '162249',
    '160519',
    '12683',
    '301060',
    '302710',
    '160355'
);

$beruntung_10 = array(
    '229968',
    '301839',
    '359668',
    '340090',
    '404648',
    '300062',
    '347222',
    '331918',
    '362314',
    '393689',
);

$beruntung_20 = array(
    '404286',
    '348877',
    '399028',
    '316679',
    '317190',
    '350209',
    '141936',
    '204576',
    '335004',
    '322674',
    '69870',
    '66233',
    '343736',
    '303181',
    '404217',
    '299153',
    '404323',
    '335442',
    '382438',
    '348679',
);
$beruntung_70 = array(
	'359832',
	'277895',
	'356824',
	'379346',
	'342713',
	'354419',
	'402908',
	'206121',
	'357659',
	'373720',
	'368933',
	'296333',
	'414324',
	'244756',
	'415502',
	'266007',
	'291355',
	'402766',
	'341416',
	'268349',
	'359099',
	'264656',
	'349887',
	'404290',
	'286305',
	'32075',
	'385230',
	'351910',
	'341362',
	'338817',
	'351795',
	'132252',
	'347898',
	'404187',
	'234542',
	'378393',
	'411007',
	'328991',
	'364574',
	'330399',
	'315364',
	'395663',
	'369515',
	'404830',
	'406352',
	'359582',
	'309590',
	'212367',
	'380472',
	'344322',
	'381870',
	'364200',
	'359806',
	'305090',
	'380840',
	'329614',
	'55203',
	'333093',
	'367978',
	'346928',
	'376620',
	'342021',
	'315359',
	'241907',
	'375612',
	'380242',
	'398527',
	'349696',
	'358939',
	'397589',
);
$no=1;


if (!isset($_GET['e_id'])) {
    echo "WRONG PARAMETER !!";
    exit;
}
$e_id  = abs((int) $_GET['e_id']);
if ($e_id > 4){
    header("Location: $e_id.php?e_id=$e_id");exit;
}
if (file_exists("$e_id.html")){
    include( "$e_id.html");exit;
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

function rep_toko($toko){
    return substr_replace($toko, 'xxxxx', -3);
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
    <span class="text-muted title-child">Daftar Pemenang Event FreeFire BukaKios</span>
    <hr>
    <?php 
        $no=1;
        foreach ($winner_utama as $utama){
            $data_user = $app->grab_data("$api_url/v1/data_user.php?key=$api_key&id=$utama");
            $data_user_res = json_decode($data_user, true);
            if (isset($data_user_res['status'])){
                if ($data_user_res['status'] == 1){
                    $utama_ni .= '<tr><td><b>'.$data_user_res["data"]["nama"].'</b></td>
                            <td>'.rep_toko($data_user_res["data"]["nama_toko"]).'</td>
                            <td>'.$no.'</td>
                        </tr>';
                    
                }else{
                    ?>
                    <div class="alert alert-danger deskripsi-title"><?php echo $data_user_res['error_msg']?></div>
                <?php    
                }
            }else{
                ?>
                    <div class="alert alert-danger deskripsi-title">Gagal menghubung server. Hubungi Customer Service!!</div>
                <?php   
            }
            $no++;
        }

        $no=1;
        foreach ($winner_utama_ke2 as $utama_2){
            $data_user = $app->grab_data("$api_url/v1/data_user.php?key=$api_key&id=$utama_2");
            $data_user_res = json_decode($data_user, true);
            if (isset($data_user_res['status'])){
                if ($data_user_res['status'] == 1){
                    $utama_ke2 .= '<tr><td><b>'.$data_user_res["data"]["nama"].'</b></td>
                            <td>'.rep_toko($data_user_res["data"]["nama_toko"]).'</td>
                            <td>'.$no.'</td>
                        </tr>';
                    
                }else{
                    ?>
                    <div class="alert alert-danger deskripsi-title"><?php echo $data_user_res['error_msg']?></div>
                <?php    
                }
            }else{
                ?>
                    <div class="alert alert-danger deskripsi-title">Gagal menghubung server. Hubungi Customer Service!!</div>
                <?php   
            }
            $no++;
        }

        $no=1;
        foreach ($winner_utama_ke3 as $utama_3){
            $data_user = $app->grab_data("$api_url/v1/data_user.php?key=$api_key&id=$utama_3");
            $data_user_res = json_decode($data_user, true);
            if (isset($data_user_res['status'])){
                if ($data_user_res['status'] == 1){
                    $utama_ke3 .= '<tr><td><b>'.$data_user_res["data"]["nama"].'</b></td>
                            <td>'.rep_toko($data_user_res["data"]["nama_toko"]).'</td>
                            <td>'.$no.'</td>
                        </tr>';
                    
                }else{
                    ?>
                    <div class="alert alert-danger deskripsi-title"><?php echo $data_user_res['error_msg']?></div>
                <?php    
                }
            }else{
                ?>
                    <div class="alert alert-danger deskripsi-title">Gagal menghubung server. Hubungi Customer Service!!</div>
                <?php   
            }
            $no++;
		}
		
		$no=1;
        foreach ($beruntung_10 as $untung_10){
            $data_user = $app->grab_data("$api_url/v1/data_user.php?key=$api_key&id=$untung_10");
            $data_user_res = json_decode($data_user, true);
            if (isset($data_user_res['status'])){
                if ($data_user_res['status'] == 1){
                    $untung_10 .= '<tr><td><b>'.$data_user_res["data"]["nama"].'</b></td>
                            <td>'.rep_toko($data_user_res["data"]["nama_toko"]).'</td>
                            <td>'.$no.'</td>
                        </tr>';
                    
                }else{
                    ?>
                    <div class="alert alert-danger deskripsi-title"><?php echo $data_user_res['error_msg']?></div>
                <?php    
                }
            }else{
                ?>
                    <div class="alert alert-danger deskripsi-title">Gagal menghubung server. Hubungi Customer Service!!</div>
                <?php   
            }
            $no++;
		}
		
		$no=1;
        foreach ($beruntung_20 as $untung_20){
            $data_user = $app->grab_data("$api_url/v1/data_user.php?key=$api_key&id=$untung_20");
            $data_user_res = json_decode($data_user, true);
            if (isset($data_user_res['status'])){
                if ($data_user_res['status'] == 1){
                    $untung_20 .= '<tr><td><b>'.$data_user_res["data"]["nama"].'</b></td>
                            <td>'.rep_toko($data_user_res["data"]["nama_toko"]).'</td>
                            <td>'.$no.'</td>
                        </tr>';
                    
                }else{
                    ?>
                    <div class="alert alert-danger deskripsi-title"><?php echo $data_user_res['error_msg']?></div>
                <?php    
                }
            }else{
                ?>
                    <div class="alert alert-danger deskripsi-title">Gagal menghubung server. Hubungi Customer Service!!</div>
                <?php   
            }
            $no++;
		}
		
		$no=1;
        foreach ($beruntung_70 as $untung_70){
            $data_user = $app->grab_data("$api_url/v1/data_user.php?key=$api_key&id=$untung_70");
            $data_user_res = json_decode($data_user, true);
            if (isset($data_user_res['status'])){
                if ($data_user_res['status'] == 1){
                    $untung_70 .= '<tr><td><b>'.$data_user_res["data"]["nama"].'</b></td>
                            <td>'.rep_toko($data_user_res["data"]["nama_toko"]).'</td>
                            <td>'.$no.'</td>
                        </tr>';
                    
                }else{
                    ?>
                    <div class="alert alert-danger deskripsi-title"><?php echo $data_user_res['error_msg']?></div>
                <?php    
                }
            }else{
                ?>
                    <div class="alert alert-danger deskripsi-title">Gagal menghubung server. Hubungi Customer Service!!</div>
                <?php   
            }
            $no++;
        }
        
        
        $winner = html_entity_decode($data_res['data']['e_pemenang']);
        $winner = str_replace('{{utama}}', $utama_ni, $winner);
        $winner = str_replace('{{utama_ke2}}', $utama_ke2, $winner);
        $winner = str_replace('{{utama_ke3}}', $utama_ke3, $winner);
        $winner = str_replace('{{untung_10}}', $untung_10, $winner);
        $winner = str_replace('{{untung_20}}', $untung_20, $winner);
        $winner = str_replace('{{untung_70}}', $untung_70, $winner);

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