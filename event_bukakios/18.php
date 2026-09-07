<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// if (!isset($_GET['dev'])){
//     echo "<h1>FORBIDDEN</h1>";exit;
// }
require_once("../config.php");
require_once("../_session.php");
ob_start();


$dat_wi= '[{"no":1,"id":"50258","uid":null,"nama":"SHZ_07_Store","email":"herypratama01as@gmail.com","nama_toko":"SHZ 07 Net"},{"no":2,"id":"866902","uid":null,"nama":"Syamsumarlin","email":"faiqaaah08@gmail.com","nama_toko":"Fuad cell"},{"no":3,"id":"255707","uid":null,"nama":"Faisal","email":"nurilma0901@gmail.com","nama_toko":"Ahsan 28"},{"no":4,"id":"707198","uid":null,"nama":"Dendi Fransisko Soru","email":"Dendysoru28@gmail.com","nama_toko":"Toko Dendi Soru_28"},{"no":5,"id":"160519","uid":null,"nama":"andiarzad","email":"andiariscell@gmail.com","nama_toko":"Avlyn cell"},{"no":6,"id":"276425","uid":null,"nama":"ABI PONSEL 29","email":"sandytyastitofranditha@gmail.com","nama_toko":"ABI PONSEL 29"},{"no":7,"id":"602204","uid":null,"nama":"Arfisaga Pratama","email":"arfan64646@gmail.com","nama_toko":"SC (cell)"},{"no":8,"id":"806135","uid":null,"nama":"Solihin","email":"solihinmuhammad376@gmail.com","nama_toko":"Maburo Cell"},{"no":9,"id":"857327","uid":null,"nama":"Riski pasaoran","email":"riskyparsaoranmanurung08@gmail.com","nama_toko":"Toko AZZALEA CELL"},{"no":10,"id":"289755","uid":null,"nama":"MANDIRI PONSEL","email":"nellifitriana3@gmail.com","nama_toko":"MANDIRI PONSEL"},{"no":11,"id":"47178","uid":null,"nama":"Sri wahyuni","email":"sriwahyuni45355@gmail.com","nama_toko":"Zivanna Cell"},{"no":12,"id":"878777","uid":null,"nama":"Musar","email":"Musarnarwin1234@gmail.com","nama_toko":"Dua Putra"},{"no":13,"id":"562984","uid":null,"nama":"Ayukartika","email":"ayukartika1202200@gmail.com","nama_toko":"Toko Ayukartika"},{"no":14,"id":"784311","uid":null,"nama":"Ibrahim dita Kusuma","email":"ibrahimitakusuma@gmail.com","nama_toko":null},{"no":15,"id":"246219","uid":null,"nama":"Counter Abang Cell","email":"tusisusantimimang@gmail.com","nama_toko":"Counter Abang Cell"},{"no":16,"id":"548582","uid":null,"nama":"ISNAWATI","email":"isnawatiseptember2017@gmail.com","nama_toko":"I M CELL"},{"no":17,"id":"617551","uid":null,"nama":"Prenkimalta","email":"prenkilongka@gmail.com","nama_toko":"Longka jaya"},{"no":18,"id":"877928","uid":null,"nama":"Hatibul umam","email":"sayyidilimam@gmail.com","nama_toko":"Dhuta mandiri"},{"no":19,"id":"322613","uid":null,"nama":"Dhika","email":"dhikadbr150393@gmail.com","nama_toko":null},{"no":20,"id":"198928","uid":null,"nama":"dodi awaludin","email":"awaludindodi74@gmail.com","nama_toko":"Toko dodi awaludin"},{"no":21,"id":"188731","uid":null,"nama":"Eman macho","email":"dearaujodearaujo803@gmail.com","nama_toko":"Toko Eman macho"},{"no":22,"id":"547659","uid":null,"nama":"Bismillah ponsel","email":"ponselbismillah743@gmail.com","nama_toko":"Toko Bismillah ponsel"},{"no":23,"id":"771160","uid":null,"nama":"sumiatiponi87@gmail.com","email":"sumiatiponi87@gmail.com","nama_toko":"Konter Fauzi cell"},{"no":24,"id":"959581","uid":null,"nama":"Kimochi","email":"donnymaison5@gmail.com","nama_toko":""},{"no":25,"id":"935781","uid":null,"nama":"Rahmi Oktapianti Junaidi","email":"dhika.rayyan@gmail.com","nama_toko":"Dhira shop"},{"no":26,"id":"472785","uid":null,"nama":"Denisaputri","email":"denisaputri791@gmail.com","nama_toko":"Toko Denisaputri"},{"no":27,"id":"915448","uid":null,"nama":"Muhammad Sugeng Mulyono","email":"muhammadsugengD@gmail.com","nama_toko":""},{"no":28,"id":"654141","uid":null,"nama":"WaNz","email":"hermawan.a30@yahoo.com","nama_toko":"Rie cell"},{"no":29,"id":"862786","uid":null,"nama":"Juwina","email":"njuwina@gmail.com","nama_toko":"Wincellwkwk"},{"no":30,"id":"806484","uid":null,"nama":"Ari","email":"ariperdinal12@gmail.com","nama_toko":"Aiza ponsel"},{"no":31,"id":"223540","uid":null,"nama":"Prasetyo Hadi Anggara","email":"anggaprasetyo870@gmail.com","nama_toko":"Kios GGC"},{"no":32,"id":"315206","uid":null,"nama":"Cepi S Shidiq","email":"cepissidik04@gmail.com","nama_toko":"Toko Hana"},{"no":33,"id":"621263","uid":null,"nama":"KIOS AMAJON","email":"horasfoi@gmail.com","nama_toko":"Toko KIOS AMAJON"},{"no":34,"id":"523273","uid":null,"nama":"Riko Masdian","email":"masdianriko@gmail.com","nama_toko":"Toko Riko Masdian"},{"no":35,"id":"75887","uid":null,"nama":"Jefri manullang","email":"jefrimanullang16@gmail.com","nama_toko":"Manullang"},{"no":36,"id":"948842","uid":null,"nama":"Hermanto","email":"zasus8808@gmail.com","nama_toko":""},{"no":37,"id":"644033","uid":null,"nama":"Kanser yulianto","email":"laetigerlaw@gmail.com","nama_toko":"Toko Kanser yulianto"},{"no":38,"id":"415997","uid":null,"nama":"Suratman","email":"Suratmanrubyangkasa006@gmail.com","nama_toko":"Arsya ponsel"},{"no":39,"id":"162729","uid":null,"nama":"Vherianie","email":"vherianie@gmail.com","nama_toko":"Ratu Kartu"},{"no":40,"id":"757940","uid":null,"nama":"Anggriana Adha Naim","email":"anggrianaadhanaim@gmail.com","nama_toko":"Ayah counter"},{"no":41,"id":"938533","uid":null,"nama":"Hairuddin","email":"rinyiswahyuni2@gmail.com","nama_toko":"Sotok cell"},{"no":42,"id":"793747","uid":null,"nama":"Muhammad Fadilul Haq","email":"balistikfadil@gmail.com","nama_toko":"F-Computer Accessories"},{"no":43,"id":"713932","uid":null,"nama":"Eggi cell","email":"egicell@gmail.com","nama_toko":null},{"no":44,"id":"339526","uid":null,"nama":"Endang susetiyowati","email":"nugrohobudirahayu@gmail.com","nama_toko":"Toko Endang susetiyowati"},{"no":45,"id":"21170","uid":null,"nama":"DESRI GUNAWAN","email":"Desrigunawan75@yahoo.com","nama_toko":"KARYA MANDIRI CELL"},{"no":46,"id":"318662","uid":null,"nama":"Hidayat mansur","email":"Mamangdayat41@gmail.com","nama_toko":"Mila"},{"no":47,"id":"565439","uid":null,"nama":"Riko Purnama","email":"prajamudakarana97@gmail.com","nama_toko":"NR Printing"},{"no":48,"id":"828571","uid":null,"nama":"rahmadi","email":"bungas237@gmail.com","nama_toko":"Ayucell"},{"no":49,"id":"901747","uid":null,"nama":"Jefri","email":"ramadaninadia30@gmail.com","nama_toko":"Jay Ponsel 2"},{"no":50,"id":"377549","uid":null,"nama":"Dikdianing Catur Putra","email":"caturputra1999@gmail.com","nama_toko":"Catur cell"}]';
$winner_utama = json_decode($dat_wi, true);
// var_dump($winner_utama);
$no = 1;

$utama_ni = '';

$index = 0;
// $user_id = 377549;
foreach ($winner_utama as $utama) {
    if ($utama['id']==$user_id){
        $my_winner = "<div class='alert alert-success'>Selamat kamu terpilih menjadi <b>pemenang</b> di urutan <b>$utama[no]</b></div>";
    }
    $utama_ni .= '<tr>
    <td>' . $utama['no'] . '</td>
    <td>' . rep_toko($utama['nama_toko']) . '</td>
    <td><b>' . replace_email($utama['email']) . '</td>

</tr>';
    $no++;
    $index++;
}


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

// $e_id  = abs((int) $_GET['e_id']);
$e_id=18;
$data_api = $app->grab_data("https://ms1.bukakios.net/v1/api_event/api_show_event.php?key=$api_key&act=detailevent&e_id=$e_id");
$data_res = json_decode($data_api, true);
if (!isset($data_res['status'])) {
    echo "SORRY, UNDER MAINTENANCE !!";
    exit;
}

// echo $data_api;exit;

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

        table {
            font-size: 12px;
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
        <span class="text-muted title-child">Daftar Pemenang Event Poin</span>
        <hr>
        <?php
        if (isset($my_winner)){
            echo $my_winner;
        }
        $no = 1;
       

        // echo $utama_ni;

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