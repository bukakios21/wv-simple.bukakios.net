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


$dat_wi= '[
    {
      "no": 1,
      "id": "926616",
      "nama": "Andre",
      "email": "gyehun348@gmail.com",
      "nama_toko": ""
    },
    {
      "no": 2,
      "id": "942423",
      "nama": "Muhammad Jundi AL Haq",
      "email": "mtjundi95@gmail.com",
      "nama_toko": ""
    },
    {
      "no": 3,
      "id": "892629",
      "nama": "jhuny priyanto",
      "email": "jhuny.priyanto11@gmail.com",
      "nama_toko": ""
    },
    {
        "no": 4,
        "id": "940123",
        "nama": "Dua putri",
        "email": "ratnawati5141@gmail.com",
        "nama_toko": ""
      },
      {
        "no": 5,
        "id": "867279",
        "nama": "Muhammad Rizki",
        "email": "ikhysmith09@gmail.com",
        "nama_toko": "Ekhi cell"
      },
      {
        "no": 6,
        "id": "912579",
        "nama": "supardi",
        "email": "pardilhy@gmail.com",
        "nama_toko": "PD cell"
      },
      {
        "no": 7,
        "id": "318924",
        "nama": "jaka permana",
        "email": "jal4k4.p3rm4n4.1989@gmail.com",
        "nama_toko": "tokkac"
      },
      {
        "no": 8,
        "id": "684137",
        "nama": "Rins Cell",
        "email": "rindawahyunim@gmail.com",
        "nama_toko": "Rins Cell"
      },
      {
        "no": 9,
        "id": "883575",
        "nama": "Petrus ndraha",
        "email": "petrusndraha18@gmail.com",
        "nama_toko": "UD. Selo"
      },
      {
        "no": 10,
        "id": "430555",
        "nama": "aldy marukai",
        "email": "aldyveny@gmail.com",
        "nama_toko": null
      },
      {
        "no": 11,
        "id": "938474",
        "nama": "Gesan",
        "email": "ghaisonghaison@gmail.com",
        "nama_toko": ""
      },
      {
        "no": 12,
        "id": "935168",
        "nama": "Mufida",
        "email": "mufidayantisari0989@gmail.com",
        "nama_toko": ""
      },
      {
        "no": 13,
        "id": "929553",
        "nama": "Evan",
        "email": "hellomanusia.akun@gmail.com",
        "nama_toko": ""
      },
      {
        "no": 14,
        "id": "912293",
        "nama": "MOHAMMAD NOFAL AGIL AZAM",
        "email": "nofaleos@gmail.com",
        "nama_toko": ""
      },
      {
        "no": 15,
        "id": "311351",
        "nama": "MUHAMMAD RIZKY99",
        "email": "rizkykiwool@gmail.com",
        "nama_toko": "Toko RIZKY"
      },
      {
        "no": 16,
        "id": "194744",
        "nama": "Dian Antoni",
        "email": "dhiananthoni@gmail.com",
        "nama_toko": "Dian Cell"
      },
      {
        "no": 17,
        "id": "437722",
        "nama": "aceng cell",
        "email": "kutukampung123@gmail.com",
        "nama_toko": "Toko aceng cell"
      },
      {
        "no": 18,
        "id": "829885",
        "nama": "Eneng laelana sari",
        "email": "nenglaellana3@gmail.com",
        "nama_toko": "putribisma"
      },
      {
        "no": 19,
        "id": "370004",
        "nama": "Dian Haerudin",
        "email": "dianhaerudin36@gmail.com",
        "nama_toko": "from.d"
      },
      {
        "no": 20,
        "id": "853536",
        "nama": "Kristina",
        "email": "aunaasgar480@gmail.com",
        "nama_toko": "Ristina"
      },
      {
        "no": 21,
        "id": "602851",
        "nama": "Nur Hidayah",
        "email": "hidayahcm17@gmail.com",
        "nama_toko": "Toko Nur Hidayah"
      },
      {
        "no": 22,
        "id": "940322",
        "nama": "Arif Nasution",
        "email": "oppoaulia45@gmail.com",
        "nama_toko": ""
      },
      {
        "no": 23,
        "id": "843151",
        "nama": "lini",
        "email": "linilini005@gmail.com",
        "nama_toko": "LINI 2A"
      },
      {
        "no": 24,
        "id": "465710",
        "nama": "Fajar Ardiansyah",
        "email": "fa1978404@gmail.com",
        "nama_toko": "Rahayu cell"
      },
      {
        "no": 25,
        "id": "911517",
        "nama": "topik.hidayat.ashter@gmail.com",
        "email": "topik.hidayat.ashter@gmail.com",
        "nama_toko": "Topik store"
      },
      {
        "no": 26,
        "id": "880231",
        "nama": "tulus",
        "email": "tulusmanulang678@gmail.com",
        "nama_toko": ""
      },
      {
        "no": 27,
        "id": "939420",
        "nama": "juan",
        "email": "kianojuan5@gmail.com",
        "nama_toko": ""
      },
      {
        "no": 28,
        "id": "772272",
        "nama": "Neni sulis deanti",
        "email": "nenisulis111@gmail.com",
        "nama_toko": "Toko Neni sulis deanti"
      },
      {
        "no": 29,
        "id": "938398",
        "nama": "Dantea",
        "email": "ramdanj1501@gmail.com",
        "nama_toko": ""
      },
      {
        "no": 30,
        "id": "526832",
        "nama": "Putri",
        "email": "artatiana120120@gmail.com",
        "nama_toko": "Toko kembang"
      },
      {
        "no": 31,
        "id": "865624",
        "nama": "SriWahyuNingsih",
        "email": "sri766966@gmail.com",
        "nama_toko": "Sri counter pulsa"
      },
      {
        "no": 32,
        "id": "936260",
        "nama": "Wahyu ardiansyah",
        "email": "wahyuardiansyah2601@gmail.com",
        "nama_toko": ""
      },
      {
        "no": 33,
        "id": "936166",
        "nama": "Pulsa",
        "email": "kundahans0@gmail.com",
        "nama_toko": "Konfirmasi"
      },
      {
        "no": 34,
        "id": "931354",
        "nama": "Dwi Tegar Fernandes",
        "email": "id.dtegarf@gmail.com",
        "nama_toko": ""
      },
      {
        "no": 35,
        "id": "590149",
        "nama": "Eka pratiwi wahyuningtyas",
        "email": "ekapw27@gmail.com",
        "nama_toko": "Iza cell"
      },
      {
        "no": 36,
        "id": "421452",
        "nama": "Widiya Cell",
        "email": "watobae08@gmail.com",
        "nama_toko": "Warung Widiya Cell"
      },
      {
        "no": 37,
        "id": "683200",
        "nama": "abdilpapua",
        "email": "syiarislampapua01@gmail.com",
        "nama_toko": "kios Malik"
      },
      {
        "no": 38,
        "id": "698606",
        "nama": "taufikurrahman",
        "email": "taufikurrahman416@gmail.com",
        "nama_toko": "Toko Rahman"
      },
      {
        "no": 39,
        "id": "200185",
        "nama": "Hardiansyah",
        "email": "ansyahhardi992@gmail.com",
        "nama_toko": "Toko Hardiansyah"
      },
      {
        "no": 40,
        "id": "100349",
        "nama": "Yasri zulhaji",
        "email": "zulhajiyasrih@gmail.com",
        "nama_toko": "Yasrih Zulhaji"
      },
      {
        "no": 41,
        "id": "932714",
        "nama": "HANDOYO",
        "email": "doyelsoklin@gmail.com",
        "nama_toko": ""
      },
      {
        "no": 42,
        "id": "511256",
        "nama": "DIlStore",
        "email": "wg2700317@gmail.com",
        "nama_toko": "Dil Store Offc"
      },
      {
        "no": 43,
        "id": "441314",
        "nama": "Grizzly",
        "email": "eriqangga2701@gmail.com",
        "nama_toko": "Grizz_shop"
      },
      {
        "no": 44,
        "id": "544281",
        "nama": "Kuntum khaerun nisa",
        "email": "kuntum31@gmail.com",
        "nama_toko": "faiz cell"
      },
      {
        "no": 45,
        "id": "888196",
        "nama": "Praz",
        "email": "prazjb24@gmail.com",
        "nama_toko": ""
      },
      {
        "no": 46,
        "id": "715054",
        "nama": "Ratna wulandari",
        "email": "Ratnawulandari904@gmail.com",
        "nama_toko": "zakia cell"
      },
      {
        "no": 47,
        "id": "670078",
        "nama": "Saputra",
        "email": "riudwisaputra@gmail.com",
        "nama_toko": null
      },
      {
        "no": 48,
        "id": "161645",
        "nama": "Viki Ahmad Bayaki",
        "email": "va941470@gmail.com",
        "nama_toko": "Badra TopUp"
      },
      {
        "no": 49,
        "id": "909331",
        "nama": "Ayuni Ristianawati",
        "email": "ayunirstianawatii@gmail.com",
        "nama_toko": ""
      },
      {
        "no": 50,
        "id": "817422",
        "nama": "Reee",
        "email": "resturifai90@gmail.com",
        "nama_toko": "ReeeStore"
      }
]';
$winner_utama = json_decode($dat_wi, true);
// var_dump($winner_utama);
$no = 1;

$utama_ni = '';

$index = 0;
// $user_id = 909331;
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
$e_id=16;
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
        <span class="text-muted title-child">Daftar Pemenang Event Rating Playstore</span>
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