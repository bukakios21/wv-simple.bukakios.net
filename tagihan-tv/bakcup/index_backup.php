<?php
require_once("../config.php");
require_once("../_session.php");
define("JWT", $user_jwt);

function CallApiV2($data, $is_auth = false, $url)
{
    $vars = json_encode($data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);  //Post Fields
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $headers = [
        'Api-Key: PLowElenThErTeRAphaRDwINEAntrIDe',
        "Authorization: ".JWT
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $server_output = curl_exec($ch);
    return $server_output;
}

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="../assets/css/font-awesome.min.css"> -->
    <!--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0/js/fontawesome.min.js"></script>-->
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@200;400;600&display=swap" rel="stylesheet">
    <title>title::Tagihan TV Pascabayar</title>
    <style>
        .search {
            position: relative;
            /* color: #aaa; */

            font-size: 16px;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .right {
            width: 5%;
        }

        .middle {
            width: 40%;
        }

        .float {
            float: left;
        }

        .search input {
            width: 100%;
            height: 40px;

            background: #fff;
            border: 1px solid #aaa;
            border-radius: 5px;
            /* box-shadow: 0 0 3px #ccc, 0 10px 15px #ebebeb inset; */
        }

        .search input {
            text-indent: 32px;
        }

        .search .fa-search {
            position: absolute;
            top: 10px;
            left: 10px;
            color: "<?= $primary ?>";
        }

        a {
            text-decoration: none;
            color: #000;
        }

        a:hover {
            text-decoration: none;
            color: #000;
        }

        html * {
            font-family: 'Nunito Sans', sans-serif;
        }

        body {
            /* font-family: Nunito; */
            font-family: 'Lato', 'Nunito Sans';
            color: #838383;
        }

        span {
            /* color:#838383; */
        }

        .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 50px;
            height: 50px;
            -webkit-animation: spin 5s linear infinite;
            /* Safari */
            animation: spin 2s linear infinite;
        }

        /* Safari */
        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .shadow {
            /* box-shadow:  0 55px 110px -15px rgba(0, 0, 0, 0.07); */
            -webkit-box-shadow: 0px 10px 24px -4px rgba(48, 46, 48, 0.32);
            -moz-box-shadow: 0px 10px 24px -4px rgba(48, 46, 48, 0.32);
            box-shadow: 0px 10px 24px -4px rgba(48, 46, 48, 0.32);
        }

        .transition {
            animation: transitionIn 1s;
        }

        @keyframes transitionIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .margin {
            margin: 15px;
        }

        .card {
            color: white;
        }

        .col-md-12 {
            /* padding-right:12px !important; */
            /* padding-left:12px !important; */
        }

        input::placeholder {
            font-size: 12px;
            color: #838383;
        }

        .left {
            width: 30%;
        }

        .right {
            width: 5%;
        }

        .middle {
            width: 70%;
        }

        .float {
            float: left;
        }

        body {
            font-family: Nunito;
            color: #838383;
            background-color: #f5f5f5;
        }

        .list-group-item {
            margin: 1px !important;
        }
    </style>
</head>

<body>
    <div style="background-color:<?= $primary ?>;">
        <div class="mx-auto py-5 mb-2 text-center">
            <img width="64px" src="https://assets.bukakios.net/img2/uploads/2021/06/247-tv-screen.png">
            <p>
            </p>
        </div>
    </div>
    <div class="container">

        <div id="data">
            <div class="list-group list-group-flush mt-3">
                <?php
                $lists = $app->curl_post("$api_url/v1/api_detail_produk.php", ['key' => $api_key, "id" => 144]);
                $lists = json_decode($lists, true);
                if (isset($lists['data'])) {
                    foreach ($lists['data'] as $list) {
                ?>
                        <li class="list-group-item">
                            <div class="move">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="float">
                                            <a href="pay.php?code=<?PHP echo $list['code']; ?>">
                                                <img src="<?PHP echo $list['product_img']; ?>" width="40px;">
                                                <!-- <img src="<?PHP// echo $list['code'] ==  "FIRSTMEDIA" ? "https://upload.wikimedia.org/wikipedia/commons/thumb/8/8f/First_Media_logo.svg/1024px-First_Media_logo.svg.png"  :  "https://assets.bukakios.net/img/icon/produk-by-id/$list[id_produk].png"; ?>" width="40px;"> -->
                                                <span class="fa fa-spinner" id="" style="display:none"></span>
                                                <span class="" style="margin-left:10px; font-size:14px"><?php echo $list['product_name']; ?></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?PHP
                    }
                    ?>

                    <!-- <li class="list-group-item">
                        <div class="move">
                            <div class="row">
                                <div class="col-12">
                                    <div class="float">
                                        <a href="pay.php?code=FIRTSMEDIA">
                                            <img src="<?PHP //echo "https://assets.bukakios.net/img/icon/produk-by-id/2785.png"; ?>" width="40px;">
                                            <span class="fa fa-spinner" id="" style="display:none"></span>
                                            <span class="" style="margin-left:10px; font-size:14px">FIRTSMEDIA</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li> -->

                <?PHP
                } else {
                    echo "Gagal mengambil list produk, silahakan ulangi";
                }
                ?>
            </div>
        </div>
    </div>
    <script src="../assets/js/jquery-3.2.1.slim.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/sweetalert.min.js"></script>
</body>

</html>