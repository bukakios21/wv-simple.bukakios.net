<?PHP
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require_once("../../config.php");
require_once('../../_session.php');
require_once("../../lib/ApiV2.php");

// Maintenance mode - uncomment to enable
 header("Location: ../../maintenance/index.php"); exit;

$api_v2 = new ApiV2($user_jwt);

$lyt_image = "https://assets.bukakios.net/img2/uploads/2020/01/345-phone-verified.png";
if (isset($_POST['act'], $_POST['csrf'])) {
    require_once('proses_otp.php');
    exit;
} else {
    $csrf = $app->csrf();
}
$user = $api_v2->detail_user();
$data_user = json_decode($user, true);
$is_error = false;
if (!isset($data_user['status'])) {
    $is_error = true;
    $error_msg = "error call api server";
}
if ($data_user['status'] == 0) {
    $is_error = true;
    $error_msg = $data_user['error_msg'];
}

if ($is_error) {
    $html_title = "title::Error";
    $lyt_button_link = "opentranslate://10|pulsa";
    $lyt_button_name = "KEMBALI KE DASHBOARD";
    $lyt_image = "https://assets.bukakios.net/img/illustration/bc_logout.png";
    $lyt_title = "Error!";
    $lyt_description = $error_msg;
    require_once(ROOT . "/_template/general_message.php");
    exit;
}


$data_user = $data_user['data'];
$nomor_hp = $data_user['hp'];
$count = strlen($nomor_hp) - 9;
$verif_hp = $data_user['verif_hp'];
$nomor_hp_sensor = substr_replace($nomor_hp, str_repeat('*', $count), 4, $count);



?>

<html>

<head>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Nunito" />
    <link rel="stylesheet" href="<?php echo $c_url ?>/assets/css/bootstrap.min.css" />
    <script src="https://kit.fontawesome.com/a3fa74c388.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: Nunito;
        }

        #resend:after {
            color: blue;
        }
    </style>
    <title>Verifikasi No HP</title>
</head>

<body>
    <?php
    if ($verif_hp != 0) {
    ?>
        <div style="text-align:center; padding:20px;" class="container">
            <div class="alert alert-info">No HP kamu sudah terverifikasi!</div>
        </div>
    <?php
    } else {
    ?>
        <div style="text-align:center; padding:20px;" class="container">
            <img src="<?= $lyt_image ?>" alt="" style="width:200px; margin-top:50px;">
            <div class="form-group" style="margin-top:40px;">
                <div style="text-align:left">
                    <div class='alert alert-warning'>
                        Untuk Verifikasi no hp kamu, kami akan mengirimkan kode OTP ke nomor ini <?= $nomor_hp_sensor ?> hp kamu, silahkan klik tombol kirim untuk mengirim OTP :
                    </div>
                </div>
            </div>
            <div class="form-group in-otp" style="display:none">
                <label>Masukkan Kode OTP*</label>
                <input type="number" class="form-control" name="otp" id="otp" required="">
                <small id="time" class="form-text text-muted"></small>
            </div>
            <div class="form-group in-otp" style="display:none">
                <small id="msg" class="form-text text-muted"></small>
            </div>
        </div>
        <div class="footer">
            <div class="mx-3 mb-2 mt-3">
                <div id="request_otp">
                    <button id="btn-hp" class="btn  btn-block btn-rounded" style="background-color:<?= $primary ?>;color:#fff; padding:10px;"><i class='fa fa-envelope'></i> Melalui SMS</button>
                    <button id="btn-wa" class="btn  btn-block btn-rounded btn-success" style="color:#fff; padding:10px;"><i class='fa fa-phone'></i> Melalui WhatsApp</button>
                    <a href="https://wv.bukakios.net/verif/miscall" style="color:#fff; padding:10px;" class="btn btn-block btn-rounded btn-primary"><i class='fa fa-phone'></i> Melalui Telpon</a>
                </div>
                <button id="btn-submit" class="btn  btn-block btn-rounded btn-success" style="color:#fff; padding:10px; display:none"> Submit</button>
            </div>
        </div>
    <?php
    }
    ?>
    <script src="<?php echo $c_url ?>/assets/js/jquery.js"></script>
    <script>
        $(document).ready(function() {

            var csrf = "<?php echo $csrf ?>";
            var btn_wa = $("#btn-wa")
            var btn_sms = $("#btn-hp")
            var btn_submit = $("#btn-submit")
            var request_otp = $("#request_otp")

            function goMaintenance() {
                window.location.href = "../../maintenance/index.php";
            }

            function open_btn() {
                btn_wa.attr("disabled", false)
                btn_sms.attr("disabled", false)
                btn_wa.html("<i class='fa fa-phone'></i> Melalui WhatsApp")
                btn_sms.html("<i class='fa fa-envelope'></i> Melalui SMS")
            }

            function hide_request() {
                request_otp.hide()
            }

            function show_request() {
                request_otp.show()
            }

            function open_submit() {
                btn_submit.show();
            }

            function hide_submit() {
                btn_submit.hide()
            }

            $('#msg').on('click', function() {
                location.reload()
            });

            btn_sms.on('click', function() {
                btn_sms.html("proses..");
                var data = {
                    "act": "sms",
                    "csrf": csrf
                }
                call_net(data)
            })

            btn_wa.on('click', function() {
                btn_wa.html("proses..");
                var data = {
                    "act": "wa",
                    "csrf": csrf
                }
                call_net(data)
            })

            btn_submit.on('click', function() {
                btn_submit.html("proses..");
                btn_submit.attr('disabled', true);
                var otp = $('#otp').val();
                $.ajax({
                    url: "index.php",
                    data: {
                        act: "sending",
                        otp: otp,
                        csrf: csrf
                    },
                    method: 'POST',
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == 1) {
                            setTimeout(function() {
                                window.location.href = "success.php"
                            }, 1000);
                        } else if (data.status == 0) {
                            setTimeout(function() {
                                window.location.href = "error.php"
                            }, 1000);
                        }
                    }
                });
            });

            function startTimer(duration, display) {
                var timer = duration,
                    minutes, seconds;
                setInterval(function() {
                    minutes = parseInt(timer / 60, 10)
                    seconds = parseInt(timer % 60, 10);

                    minutes = minutes < 10 ? "0" + minutes : minutes;
                    seconds = seconds < 10 ? "0" + seconds : seconds;

                    display.textContent = minutes + ":" + seconds;

                    if (--timer < 0) {
                        $('#time').hide();
                        $('#msg').html("Kirim ulang?");
                        $('#btn-submit').show();
                    }
                }, 1000);
            }

            function gen() {
                var fiveMinutes = 20 * 1,
                    display = document.querySelector('#time');
                startTimer(fiveMinutes, display);
            };

            function call_net(data) {
                $.ajax({
                    url: "index.php",
                    data: data,
                    method: 'POST',
                    dataType: 'json',
                    success: function(data) {
                        // $('#btn-sending').html("Berhasil");
                        // $('#btn-sending').attr('disabled', false);
                        if (data.status == 1) {
                            hide_request();
                            open_submit()
                            // $('#btn-hp').html(data.message);
                            // setTimeout(function() {
                            //     $('#btn-hp').fadeOut();
                            //     $('#btn-wa').fadeOut();
                            // }, 1000);
                            // $('#btn-submit').show();
                            $('.in-otp').show();
                            gen();
                        } else if (data.status == 0) {
                            setTimeout(function() {
                                window.location.href = "error.php"
                            }, 2000);
                        }
                        // setTimeout(function(){ location.reload() }, 1000);
                        console.log(data);
                    }
                });
            }

        });
    </script>
</body>

</html>
