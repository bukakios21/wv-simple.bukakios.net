<?php
require_once("../config.php");
require_once("../_session.php");
if ($new_detail){
   	header("Location: https://wv3.bukakios.id/event");
	   exit();
}
//if ($user_id == 54379 || $user_id == 278542 || $user_id == 1225305 || $user_id == 39958 || $user_id == 40408) {
//    header("Location: https://wv3.bukakios.id/event");
//    exit();
//}
$data_api = $app->grab_data("https://ms1.bukakios.net/v1/api_event/api_show_event.php?key=$api_key&act=listevent");
$data_res = json_decode($data_api, true);
if (!isset($data_res['status'])) {
        echo "SORY, UNDER MAINTENANCE!!";
    }
    if ($data_res['status'] == 0) {
        echo $data_res['error_msg'];
        exit;
    }

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

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Nunito" />
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css">
    <title>title::Event Bukakios</title>
    <style>
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
            width: 40%;
            font-size: 10px;
            padding: 1px 0px 1px 5px;
        }

        .title {
            font-weight: bold;
            font-size: 17px
        }

        .img-watch {
            width: 15px;
        }

        .txt-limit {
            font-size: 10px;
        }

        /* // Default styling here */

        /* // Little larger screen */
        @media only screen and (min-width: 450px) {
            .child {
                width: 55%;
                font-size: 15px;
            }

            .title {
                font-size: 19px;
            }

            .txt-limit {
                font-size: 15px;
            }

            .tombol {
                width: 70%;
                font-size: 15px;
            }
        }

        /* // Pads and larger phones */
        @media only screen and (min-width: 600px) {}

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

<body class="bdy">
    <div style="background-color:<?= $primary ?>;">
        <div class="mx-auto pt-5 mb-2 text-center">
            <!-- <img width="30%" src="https://assets.bukakios.net/img2/uploads/2021/01/384-trophy-1.png"> -->
            <img width="30%" src="https://assets.bukakios.net/img2/uploads/2021/01/537-trophy.png">
        </div>
    </div>
    <div class="container" style="margin-bottom:60px;">

        <?php
        foreach ($data_res['data']  as $row) {
            $btn_title = "Ikuti Event";
            if ($row['e_akhir'] < $today) {
                $btn_title = "Lihat Event";
            }
        ?>
            <div class="card card-4 my-4 click-me" style="border-radius:10px" data-id="<?php echo $row['e_id'] ?>">
                <div class="child">Total Hadiah Rp <?php echo $row['e_hadiah'] != 0 ? $app->angka_id($row['e_hadiah']) : "-"?></div>
                <img class="card-img-top" src="<?php echo $row['e_image'] ?>" alt="100%x180" style="height: 194px; width: 100%; display: block;border-top-left-radius:10px;border-top-right-radius:10px">
                <div class="card-body">
                    <table style="width:100%">
                        <tr>
                            <td style="width:250px">
                                <span class="title"><?php echo $row['e_title'] ?></span>
                            </td>
                            <td style="vertical-align:middle;text-align:right" rowspan="2"><a class="btn btn-sm btn-primary tombol" href="detail2.php?e_id=<?php echo $row['e_id'] ?>"><?php echo $btn_title ?></a></td>
                        </tr>
                        <tr>
                            <td style="width:250px">
                                <img src="https://assets.bukakios.net/img2/uploads/2021/01/950-clock.png" class="img-watch"><span class="text-muted txt-limit"> Berlaku Sampai <?php echo tgl_indo($row['e_akhir']); ?></span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        <?php
        }

        ?>
        <!-- <div class="card card-4 my-4" style="border-radius:10px"> -->
        <!-- <div class="child">Total Hadiah Rp 2.000.000</div> -->
        <!-- <img class="card-img-top" src="https://www.mockbank.com/bulletin/wp-content/uploads/2015/09/300x250.png" alt="100%x180" style="height: 194px; width: 100%; display: block;border-top-left-radius:10px;border-top-right-radius:10px">
            <div class="card-body">
                <table style="width:100%">
                    <tr>
                        <td style="width:250px">
                            <span style="font-weight:bold;font-size:18px">Beri Rating Bintang 5</span>
                        </td>
                        <td style="vertical-align:middle;text-align:right" rowspan="2"><button class="btn btn-sm btn-primary">Ikuti Event</button></td>
                    </tr>
                    <tr>
                        <td style="width:250px">
                            <img src="../assets/img/event/clock.png" width="15px"><span class="text-muted" style="font-size:12px"> Berlaku Sampai 31 January 2021</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div> -->
    </div>

    <script src="../assets/js/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="../assets/js/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="../assets/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/sweetalert.min.js"></script>
    <script>
        $(document).ready(function() {

            $('.click-me').on('click', function({

            }))

        });
    </script>

</body>

</html>