<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once("../config.php");

ob_start();
$winner_utama = array(
    '657307',
    '54970',
    '743156',
);

$beruntung_410_orang = array(
    '209701',
    '603350',
    '540132',
    '743156',
    '748283',
    '471511',
    '746775',
    '408585',
    '736176',
    '747497',
    '469982',
    '376200',
    '332286',
    '741526',
    '745267',
    '725246',
    '451019',
    '215661',
    '674918',
    '734353',
    '523537',
    '621583',
    '745995',
    '378352',
    '673906',
    '645877',
    '631658',
    '546254',
    '423968',
    '594082',
    '503900',
    '736742',
    '521232',
    '746029',
    '294621',
    '750774',
    '667303',
    '734483',
    '352800',
    '516533',
    '188731',
    '362806',
    '1000',
    '743812',
    '602204',
    '740339',
    '309049',
    '689202',
    '33128',
    '539354',
    '526102',
    '521514',
    '636941',
    '698449',
    '562465',
    '392608',
    '114567',
    '749780',
    '30518',
    '503044',
    '746100',
    '652963',
    '744668',
    '308975',
    '745790',
    '746301',
    '740778',
    '700796',
    '603359',
    '347213',
    '742061',
    '717755',
    '711328',
    '44665',
    '295114',
    '613209',
    '509815',
    '737876',
    '392403',
    '701636',
    '304956',
    '687652',
    '319358',
    '668403',
    '725541',
    '703601',
    '746182',
    '676919',
    '702414',
    '657319',
    '737988',
    '247274',
    '159235',
    '747642',
    '393230',
    '533406',
    '747122',
    '585251',
    '439885',
    '559687',
    '544714',
    '195123',
    '588302',
    '593760',
    '743603',
    '286937',
    '722980',
    '725639',
    '688043',
    '540812',
    '579987',
    '663601',
    '656671',
    '735069',
    '614172',
    '304103',
    '717595',
    '657090',
    '730387',
    '684392',
    '294974',
    '368923',
    '649119',
    '140504',
    '31909',
    '637381',
    '741195',
    '646454',
    '661912',
    '119934',
    '651199',
    '569581',
    '541304',
    '710659',
    '711189',
    '729976',
    '737815',
    '479165',
    '740080',
    '744936',
    '487217',
    '580216',
    '717064',
    '392992',
    '457412',
    '731029',
    '50158',
    '572179',
    '697430',
    '327276',
    '743926',
    '728922',
    '748332',
    '76221',
    '739026',
    '716316',
    '520365',
    '560833',
    '744882',
    '674731',
    '632619',
    '687284',
    '673189',
    '744695',
    '710931',
    '741429',
    '351009',
    '678363',
    '584272',
    '442900',
    '376351',
    '696058',
    '738554',
    '693267',
    '397603',
    '649094',
    '623299',
    '566180',
    '746245',
    '750055',
    '722079',
    '378980',
    '679624',
    '744584',
    '527261',
    '729449',
    '602851',
    '703172',
    '713977',
    '307318',
    '744698',
    '748278',
    '745978',
    '583719',
    '285556',
    '681559',
    '497174',
    '645885',
    '656531',
    '349662',
    '637148',
    '361939',
    '654916',
    '686603',
    '418597',
    '728804',
    '744513',
    '671971',
    '348141',
    '197745',
    '729347',
    '395638',
    '481880',
    '147825',
    '747311',
    '503677',
    '713387',
    '592574',
    '746121',
    '272200',
    '729292',
    '632961',
    '323399',
    '746821',
    '534795',
    '380673',
    '289652',
    '677033',
    '283509',
    '741174',
    '593966',
    '451981',
    '750009',
    '361260',
    '195820',
    '747384',
    '181018',
    '171838',
    '537189',
    '54970',
    '358767',
    '696663',
    '750011',
    '440703',
    '749200',
    '192947',
    '359295',
    '700849',
    '743968',
    '168256',
    '474740',
    '536746',
    '743498',
    '272109',
    '482227',
    '724777',
    '509231',
    '739769',
    '280471',
    '374546',
    '259507',
    '433176',
    '425044',
    '670519',
    '694615',
    '656542',
    '706938',
    '727426',
    '671841',
    '174599',
    '712123',
    '599522',
    '348848',
    '431394',
    '307605',
    '648755',
    '740916',
    '383353',
    '742856',
    '402997',
    '727228',
    '750349',
    '348272',
    '525726',
    '402317',
    '738549',
    '285766',
    '726306',
    '591423',
    '651468',
    '745913',
    '275880',
    '123996',
    '170768',
    '632842',
    '656581',
    '689423',
    '744761',
    '732421',
    '668398',
    '744457',
    '658693',
    '729452',
    '215792',
    '646390',
    '664569',
    '745366',
    '100349',
    '522074',
    '746588',
    '618027',
    '171653',
    '749958',
    '696931',
    '456893',
    '749175',
    '331058',
    '741788',
    '740795',
    '567228',
    '231509',
    '307100',
    '746011',
    '277997',
    '723692',
    '747388',
    '700230',
    '744622',
    '735327',
    '64323',
    '504690',
    '573334',
    '577264',
    '450480',
    '657712',
    '742341',
    '661863',
    '656832',
    '459254',
    '350002',
    '684554',
    '739659',
    '743123',
    '560666',
    '749681',
    '657307',
    '435143'
);


$beruntung_150_orang = array(
    '729452',
    '215661',
    '433176',
    '632619',
    '713387',
    '694615',
    '744695',
    '728804',
    '673906',
    '140504',
    '651199',
    '747384',
    '361939',
    '174599',
    '593966',
    '636941',
    '750774',
    '425044',
    '632961',
    '114567',
    '64323',
    '745913',
    '442900',
    '744698',
    '469982',
    '588302',
    '745790',
    '697430',
    '749200',
    '663601',
    '591423',
    '687652',
    '541304',
    '509231',
    '544714',
    '651468',
    '497174',
    '618027',
    '383353',
    '722980',
    '423968',
    '674918',
    '735069',
    '747497',
    '560833',
    '727426',
    '482227',
    '352800',
    '671841',
    '614172',
    '295114',
    '649094',
    '209701',
    '277997',
    '679624',
    '687284',
    '661912',
    '744513',
    '294621',
    '560666',
    '147825',
    '743123',
    '602851',
    '676919',
    '526102',
    '481880',
    '348848',
    '740795',
    '559687',
    '727228',
    '50158',
    '527261',
    '603350',
    '536746',
    '540132',
    '503044',
    '731029',
    '168256',
    '670519',
    '693267',
    '668403',
    '637381',
    '623299',
    '181018',
    '440703',
    '657090',
    '654916',
    '664569',
    '504690',
    '747642',
    '592574',
    '741195',
    '656581',
    '631658',
    '701636',
    '657712',
    '747122',
    '702414',
    '348272',
    '741174 ',
    '745995',
    '744584',
    '661863',
    '689202',
    '348141',
    '742856',
    '734483',
    '722079',
    '671971',
    '703172',
    '44665',
    '736742',
    '171653',
    '450480',
    '534795',
    '712123',
    '674731',
    '374546',
    '562465',
    '689423',
    '725246',
    '439885',
    '646454',
    '688043',
    '737988',
    '748283',
    '347213',
    '734353',
    '192947',
    '743498',
    '744761',
    '750011',
    '31909',
    '159235',
    '729347',
    '392608',
    '289652',
    '349662',
    '735327',
    '378352',
    '657307',
    '738554',
    '350002',
    '100349',
    '645885',
    '585251',
    '286937',
    '749175',
    '645877',
    '309049',
);

$beruntung_50_orang = array(
    '750009',
    '722079',
    '732421',
    '749958',
    '307318',
    '170768',
    '696931',
    '188731',
    '739769',
    '294621',
    '525726',
    '602851',
    '746775',
    '327276',
    '171653',
    '750011',
    '748278',
    '285766',
    '603350',
    '114567',
    '700849',
    '418597',
    '676919',
    '652963',
    '740080',
    '503900',
    '649094',
    '649119',
    '168256',
    '741195',
    '749780',
    '539354',
    '744584',
    '392992',
    '304103',
    '509231',
    '560666',
    '729452',
    '689423',
    '748332',
    '696058',
    '392608',
    '76221',
    '710931',
    '481880',
    '347213',
    '521232',
    '703172',
    '368923',
    '673189',
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
                $hadiah = "Rp 200.000";
            } elseif ($no == 2) {
                $hadiah = "Rp 150.000";
            } elseif ($no == 3) {
                $hadiah = "Rp 100.000";
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

        foreach ($beruntung_50_orang as $cepat) {
            $data_user = $app->grab_data("$api_url/v1/data_user.php?key=$api_key&id=$cepat");
            $data_user_res = json_decode($data_user, true);
            $hp = substr($data_user_res['data']['hp'], 0, -5) . 'xxx';
            if (isset($data_user_res['status'])) {
                if ($data_user_res['status'] == 1) {
                    $_50_winner .= '<tr><td><b>' . $data_user_res["data"]["nama"] . '</b>(' . $hp . ')</td>
                            <td>Rp20.000</td>
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
        foreach ($beruntung_150_orang as $untung) {
            $data_user = $app->grab_data("$api_url/v1/data_user.php?key=$api_key&id=$untung");
            $data_user_res = json_decode($data_user, true);
            $hp = substr($data_user_res['data']['hp'], 0, -5) . 'xxx';
            if (isset($data_user_res['status'])) {
                if ($data_user_res['status'] == 1) {
                    $_150_winner .= '<tr><td><b>' . $data_user_res["data"]["nama"] . '</b>(' . $hp . ')</td>
                            <td>Rp10.000</td>
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
        foreach ($beruntung_410_orang as $untung) {
            $data_user = $app->grab_data("$api_url/v1/data_user.php?key=$api_key&id=$untung");
            $data_user_res = json_decode($data_user, true);
            $hp = substr($data_user_res['data']['hp'], 0, -5) . 'xxx';
            if (isset($data_user_res['status'])) {
                if ($data_user_res['status'] == 1) {
                    $_410_winner .= '<tr><td><b>' . $data_user_res["data"]["nama"] . '</b>(' . $hp . ')</td>
                            <td>Rp5.000</td>
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
        $winner = str_replace('{{beruntung50}}', $_50_winner, $winner);
        $winner = str_replace('{{beruntung150}}', $_150_winner, $winner);
        $winner = str_replace('{{beruntung410}}', $_410_winner, $winner);

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