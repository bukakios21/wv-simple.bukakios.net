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

if ($user_id == 278542) {
    header("Location: https://wv3.bukakios.id/tagihan-pdam");
    exit();
}

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="../assets/css/font-awesome.min.css"> -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0/js/fontawesome.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
    <title>title::Bayar Tagihan PDAM</title>
    <style>
        .search {
            position: relative;
            color: #aaa;
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
        }

        a {
            text-decoration: none;
            color: #000;
        }

        a:hover {
            text-decoration: none;
            color: #000;
        }

        body {
            /* font-family: Nunito; */
            font-family: 'Lato', sans-serif;
            color: #838383;
        }

        span {
            color: #838383;
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
    </style>
</head>

<body>
    <div style="background-color:<?= $primary ?>;">
        <div class="mx-auto py-5 mb-2 text-center">
            <img style="max-width:20%" src="drop.png">
        </div>
    </div>
    <div class="container">
        <div class="search ">
            <span class="fa fa-search"></span>
            <input required type="text" name="keyword" id="search_cat" onkeyup='search_city("search", this.value)' placeholder="Cari Lokasi ...">
        </div>

        <!-- loader -->
        <div id="load">
            <div class="loader mx-auto mt-4" id="load"></div>
            <div class="text-center mt-1 font-weight-bold">Loading...</div>
        </div>

        <div id="data"></div>
    </div>
    <script src="../assets/js/jquery-3.2.1.slim.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/sweetalert.min.js"></script>
    <script>
        function myFunction() {
            swal("Title", "Info disini", "info");
        }

        $(document).on('click', '.move', function() {
            var code = $(this).attr('data-code');
            var id = $(this).attr('data-id');
            setTimeout(function() {
                window.location.href = "pay.php?code=" + code + "&id=" + id;
            }, 500);
            document.getElementById("list-" + id).style.backgroundColor = "#f1f1f1";
            $(".img-apa-" + id).hide();
            $("#loading-" + id).show();
            // $("#ganti-"+id).addClass("fa fa-spinner");

        });

        $(document).ready(function() {
            search_city('all', '');
            // $("#search").hide();
        });

        function search_city(action, q) {
            $("#load").show();
            var dataString = 'action=' + action + '&q=' + q;
            $.ajax({
                type: "POST",
                url: "../ajax/load_pdam.php",
                data: dataString,
                cache: false,
                success: function(result) {
                    $("#load").hide();
                    // $("#search").show();
                    // console.log(result)
                    $("#data").html(result);
                }
            });
        }
    </script>
</body>

</html>