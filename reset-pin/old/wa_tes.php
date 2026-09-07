<?PHP
require_once("../../config.php");
// require_once('../../_session.php');

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


$lyt_image = "https://assets.bukakios.net/img2/uploads/2020/01/358-reset-pin-min.png";
if (isset($$_POST['act'])){
    require_once('proses_otp_v2.php');exit;
}
?> 

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Nunito" />
        <link rel="stylesheet" href="<?php echo $c_url?>/assets/css/bootstrap.min.css"/>
        <style>
        body {
                        font-family: Nunito;
                    }
                    #resend:after {
                        color:blue;
                    }
        </style>
        <title>Reset PIN</title>
    </head>
<body>
    <div style="text-align:center; padding:20px;" class="container">
        <img src="<?=$lyt_image?>" alt="" style="width:200px; margin-top:50px;">
        <div class="form-group" style="margin-top:40px;">
            <label for="otp" style="font-weight:bold">Masukkan OTP *</label>
            <input type="number" required="" id="number" class="form-control">
            <div style="text-align:left">
            <small style="color:#9b9aa0">*OTP dikirim melalui WA. mohon tunggu beberapa detik lagi</small>
            </div>
            <div style="text-align:left; margin-top:5px;">
                <small id="time" style=""></small>
                <small id="resend" style="font-weight:bold; display:none; cursor:pointer">Kirim ulang?</small><br/>
                <small id="proses" style=" display:none;">Mengirim ulang OTP...</small>
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="mx-3 mb-2 mt-3">
            <button  id="btn-sending" class="btn  btn-block btn-rounded" style="background-color:<?=$primary?>;color:#fff; padding:10px;">Reset</button>
        </div>
    </div>
    <script src="<?php echo $c_url ?>/assets/js/jquery.js"></script>
    <script>
        $(document).ready(function(){    
     
           var csrf = "<?php echo $app->csrf() ?>"; 
            gen();
            $('#btn-sending').on('click', function(){
                $('#btn-sending').html("proses..");
                $('#btn-sending').attr('disabled', true);
                var otp = $('#number').val();
                $.ajax({
                    url:"index_tes.php",
                    data:{act:"sending",csrf:csrf,otp:otp},
                    method:'POST',
                    dataType:'json',
                    success: function(data){
                        if (data.status == 1){
                            setTimeout(function(){ window.location.replace("sukses.php")}, 2000);
                        }else if (data.status == 0){
                            setTimeout(function(){ window.location.replace("gagal.php")}, 2000);
                        }
                    }
                });
            });
            function kirim_otp(csrf){
                $.ajax({
                    url:"index.php",
                    data:{act:"wa",csrf:csrf},
                    method:'POST',
                    dataType:'JSON',
                    success: function(data){
                        console.log(data);
                        
                    }
                });
            }

            function startTimer(duration, display) {
            var timer = duration, minutes, seconds;
            setInterval(function () {
                    minutes = parseInt(timer / 60, 10)
                    seconds = parseInt(timer % 60, 10);

                    minutes = minutes < 10 ? "0" + minutes : minutes;
                    seconds = seconds < 10 ? "0" + seconds : seconds;

                    display.textContent = minutes + ":" + seconds;

                    if (--timer < 0) {
                        $('#time').hide();
                        $('#resend').show();
                        // return;
                    }
                }, 1000);
            }

            function gen () {
                var fiveMinutes = 60 * 1,
                    display = document.querySelector('#time');
                    resend = document.querySelector('#resend');
                startTimer(fiveMinutes, display);
            };
            var i = 1;
            $('#resend').on('click', function(){
                // $('#proses').show();
                if (i > 1){
                    return;
                }
                $('#resend').html("Megirim ulang OTP..");
                kirim_otp(csrf);
                console.log(i);
                i++;
                $('#resend').html("Berhasil, silahkan masukkan OTP yang dikirim");
                setTimeout(function(){ location.reload() }, 1000);
            });  
       });

             
        
    </script>
</body>
</html>