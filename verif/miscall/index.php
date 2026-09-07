<?PHP
// Maintenance mode - uncomment to enable
 header("Location: ../../maintenance/index.php"); exit;
require_once("../../config.php");
require_once('../../_session.php');
//$user_id = 40408;
$lyt_image = "https://assets.bukakios.net/img2/uploads/2020/01/345-phone-verified.png";
if (isset($_GET['act'])){
    require_once('proses_otp.php');
    exit;
}
$data_user = $app->grab_data("https://api.bukakios.net/v1/data_user.php?key=77e2edcc9b40441200e31dc57dbb8829&id=$user_id");
$data_user = json_decode($data_user,true);
$data_user = $data_user['data'];
$nomor_hp = $data_user['hp'];
$count = strlen($nomor_hp) - 9;
//$verif_hp = $data_user['verif_hp'];
$verif_hp = $data_user['verif_hp']
;
$nomor_hp_sensor = substr_replace($nomor_hp, str_repeat('*', $count), 4, $count);
?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Nunito" />
        <link rel="stylesheet" href="<?php echo $c_url?>/assets/css/bootstrap.min.css"/>
		<script src="https://kit.fontawesome.com/a3fa74c388.js" crossorigin="anonymous"></script>
        <style>
        body {
                        font-family: Nunito;
                    }
                    #resend:after {
                        color:blue;
                    }
        </style>
        <title>Verifikasi No HP</title>
    </head>
<body>
    <?php
        if ($verif_hp != 0){
            ?>
                <div style="text-align:center; padding:20px;" class="container">
                    <div class="alert alert-info">No HP kamu sudah terverifikasi!</div>
                </div>
            <?php
	}else{
            ?>
                <div style="text-align:center; padding:20px;" class="container">
                    <img src="https://assets.bukakios.net/img2/uploads/2021/08/89-calling-pana.png" alt="" style="width:200px; margin-top:50px;">
                    <div class="form-group" style="margin-top:40px;">
                        <div style="text-align:left">
                            <div class='alert alert-warning'>
                            Untuk Verifikasi, kami akan mencoba miscall ke nomor ini <?=$nomor_hp_sensor?> hp kamu, silahkan ingat <b>4 Angka Terakhir dari nomor yang menelpon</b> :
                            </div>
                        </div>
                    </div>
                    <div class="form-group in-otp" style="display:none">
                        <label >Masukkan 4 Angka Belakang yang menelpon Kamu*</label>
                        <!-- <input type="number" class="form-control" name="otp" id="otp" required=""> -->
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <!-- <span class="input-group-text" id="basic-addon3">+62 21 3118</span> -->
                                <span class="input-group-text" id="angkaawal"></span>
                            </div>
                            <input type="text" class="form-control" id="token" aria-describedby="basic-addon3" maxlength="4" placeholder="xxxx">
                        </div>
                        <!-- <small id="time" class="form-text text-muted"></small> -->
                    </div>
                    <div class="form-group in-otp" style="display:none">
                        <small id="msg" class="form-text text-muted"></small>
                    </div>
                </div>
                <div class="footer">
                    <div class="mx-3 mb-2 mt-3">
                        <button  id="btn-wa" class="btn  btn-block btn-rounded btn-success" style="color:#fff; padding:10px;"><i class='fa fa-phone'></i> Miscall Sekarang</button>
                        <button  id="btn-submit" class="btn  btn-block btn-rounded btn-success" style="color:#fff; padding:10px; display:none"> Submit</button>
                    </div>
                </div>
            <?php
        }
    ?>
    <script src="<?php echo $c_url ?>/assets/js/jquery.js"></script>
    <script>
        $(document).ready(function(){

           var csrf = "<?php echo $app->csrf() ?>";

           $('#msg').on('click', function(){
                $('#btn-hp').attr('disabled', false);
                $('#btn-hp').html("Melalui SMS");
                $('#btn-wa').attr('disabled', false);
                $('#btn-wa').html("Melalui WA");
                $('#btn-wa').show();
                $('#btn-hp').show();
            });

            $('#btn-hp').on('click', function(){
                $('#btn-hp').html("proses..");
                $('#btn-hp').attr('disabled', true);
                $('#btn-wa').attr('disabled', true);
                $.ajax({
                    url:"index.php",
                    data:{act:"hp",csrf:csrf},
                    method:'get',
                    dataType:'json',
                    success: function(data){
                        // $('#btn-sending').html("Berhasil");
                        // $('#btn-sending').attr('disabled', false);
                        if (data.status == 1){
                            $('#btn-hp').html(data.message);
                            setTimeout(function(){ $('#btn-hp').fadeOut();$('#btn-wa').fadeOut();}, 1000);
                            $('#btn-submit').show();
                            $('.in-otp').show();
                            gen();
                        }else if (data.status == 0){
                            setTimeout(function(){ window.location.href = "error.php?desc="+data.error_msg}, 2000);
                        }
                        // setTimeout(function(){ location.reload() }, 1000);
                        console.log(data);
                    }
                });
            });

            $('#btn-wa').on('click', function(){
                $('#btn-wa').html("proses..");
                $('#btn-wa').attr('disabled', true);
                $.ajax({
                    url:"index.php",
                    data:{act:"wa",csrf:csrf},
                    method:'get',
                    dataType:'json',
                    success: function(data){
                        // $('#btn-sending').html("Berhasil");
                        // $('#btn-sending').attr('disabled', false);
                        if (data.status == 1){
                            $('#btn-wa').html("Berhasil. silahkan masukkan 4 angka Terakhir yang Menelpon Kamu");
                            $('#angkaawal').html("+"+data.angka);
//alert(data.status);
                            setTimeout(function(){ $('#btn-wa').fadeOut();$('#btn-hp').fadeOut();}, 1000);
                            $('#btn-submit').show();
                            $('.in-otp').show();
                            gen();
                        }else if (data.status == 0){
                            setTimeout(function(){ window.location.href = "error.php"}, 2000);
                        }
                        // setTimeout(function(){ location.reload() }, 1000);
                        console.log(data);
                    }
                });
            });

            $('#btn-submit').on('click', function(){
                $('#btn-submit').html("proses..");
                $('#btn-submit').attr('disabled', true);
                $('#btn-kirim').attr('disabled', true);
                var otp = $('#token').val();
                $.ajax({
                    url:"index.php",
                    data:{act:"sending",token:otp, csrf:csrf},
                    method:'get',
                    dataType:'json',
                    success: function(data){
                        // $('#btn-sending').html("Berhasil");
                        // $('#btn-sending').attr('disabled', false);
                        if (data.status == 1){
                            setTimeout(function(){ window.location.href = "success.php"}, 1000);
                        }else if (data.status == 0){
                            setTimeout(function(){ window.location.href = "error.php"}, 1000);
                        }
                        // setTimeout(function(){ location.reload() }, 1000);
                        console.log(data);
                    }
                });
            });

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
                        $('#msg').html("Kirim ulang?");
                        $('#btn-submit').show();
                    }
                }, 1000);
            }

            function gen () {
                var fiveMinutes = 60 * 1,
                    display = document.querySelector('#time');
                startTimer(fiveMinutes, display);
            };



       });



    </script>
</body>
</html>
