<?PHP
require_once("../../config.php");
// require_once('../../_session.php');
require_once('../../lib/ApiV2.php');

$user_agent = "(Android 3.0; en-us; Xoom Build/HRI39) AppleWebKit/534.13 |BukaKiosNative|1.8.1|40056|86288af17599fd1e8dc26309325d73fa|61e1f6ad68dc4dbc489eb28b69e7a4f8|eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOiIxNjU4MjI0NDUxIiwidG9rZW4iOiI4NjI4OGFmMTc1OTlmZDFlOGRjMjYzMDkzMjVkNzNmYSIsInRva2VuX3RyeCI6IjYxZTFmNmFkNjhkYzRkYmM0ODllYjI4YjY5ZTdhNGY4IiwidWlkIjoiNDAwNTYifQ.C52AjP_bw7wB3LSS-btmU2C0ZAes5EfgG0e5db1oKt0";
$user_agent_split = explode("|", $user_agent);
$bukakios_version = $user_agent_split[2]; //ex 1.0
$split_ex = explode(".", $bukakios_version);
$bukakios_version_int = ($split_ex[0] * 10) + $split_ex[1]; // example 1.0 = 10 , 1.1 = 11
$user_id = $user_agent_split[3];
$user_id = abs((int)$user_id);
$user_token = $user_agent_split[4];
$user_token_trx = $user_agent_split[5];

//prepare jwt
if (isset($user_agent_split[6])) {
    $user_jwt = $user_agent_split[6];
}


$api_v2 = new ApiV2($user_jwt);
$data_user = $api_v2->detail_user();
$data_user_res = json_decode($data_user, true);
$is_error = false;
if (!isset($data_user_res['status'])) {
    $is_error = true;
    $error_title = "Error";
    $error_msg = "error get data user";
}
if ($data_user_res['status'] == 0) {
    $is_error = true;
    $error_title = "Error";
    $error_msg = $data_user_res['error_msg'];
}

$real_user = $data_user_res['data'];

$lyt_image = "https://assets.bukakios.net/img2/uploads/2020/01/358-reset-pin-min.png";
if (isset($_POST['act'], $_POST['csrf'])) {
    require_once('proses_otp_v2.php');
    exit;
} else {
    $csrf = md5(uniqid() . time());
    $_SESSION['csrf'] = $csrf;
}

$nomor_hp = $real_user['hp'];
$count = strlen($nomor_hp) - 9;
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
    <title>Reset PIN</title>
</head>

<body>
    <div style="text-align:center; padding:20px;" class="container">
        <img src="<?= $lyt_image ?>" alt="" style="width:200px; margin-top:50px;">
        <div class="form-group" style="margin-top:40px;">
            <div style="text-align:left">
                <div class='alert alert-warning'>
                    Untuk mereset PIN kamu, kami akan mengirimkan kode OTP ke nomor <?= $nomor_hp_sensor; ?>, silahkan pilih metode pengiriman otp yang kamu suka :
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="mx-3 mb-2 mt-3">
            <button id="btn-hp" class="btn  btn-block btn-rounded" style="background-color:<?= $primary ?>;color:#fff;padding:10px;"><i class='fa fa-envelope'></i> Melalui SMS</button>
            <button id="btn-wa" class="btn  btn-block btn-rounded btn-success" style="color:#fff; padding:10px;"><i class='fa fa-phone'></i> Melalui WhatsApp</button>
            <a href="miscall.php" class="btn  btn-block btn-rounded btn-primary" style="color:#fff; padding:10px;"><i class='fa fa-phone'></i> Melalui Miscall</a>
            <button id="btn-email" class="btn  btn-block btn-rounded btn-danger" style="color:#fff; padding:10px;"><i class='fa fa-email'></i> Melalui Email</button>

        </div>
    </div>
    <script src="<?php echo $c_url ?>/assets/js/jquery.js"></script>
    <script>
        $(document).ready(function() {

            var csrf = "<?php echo $csrf ?>";

            function call_net(data, act) {
                $.ajax({
                    url: "index_tes.php",
                    data,
                    method: 'POST',
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == 1) {
                            setTimeout(function() {
                                window.location.replace(act)
                            }, 1000);
                        } else if (data.status == 0) {
                            setTimeout(function() {
                                window.location.replace("gagal.php")
                            }, 2000);
                        }
                    }
                });
            }

            $('#btn-wa').on('click', function() {
                $('#btn-wa').html("proses..");
                $('#btn-wa').attr('disabled', true);
                var data = {
                    csrf,
                    act: "wa",
                }
                call_net(data, "wa.php")
            });

            $('#btn-hp').on('click', function() {
                $('#btn-hp').html("proses..");
                $('#btn-hp').attr('disabled', true);
                $('#btn-wa').attr('disabled', true);
                var data = {
                    csrf,
                    act: "sms",
                    csrf: csrf
                }
                call_net(data, "hp.php")
            });

            $('#btn-email').on('click', function() {
                $('#btn-email').html("proses..");
                $('#btn-email').attr('disabled', true);
                $('#btn-wa').attr('disabled', true);
                $('#btn-hp').attr('disabled', true);
                var data = {
                    csrf,
                    act: "email",
                    csrf: csrf
                }
                call_net(data, "email.php")
            });
        });

    </script>
</body>

</html>