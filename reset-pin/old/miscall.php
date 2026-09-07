<?PHP
require_once("../../config.php");
require_once('../../_session.php');
// $user_id = 39958;
// $lyt_image = "https://assets.bukakios.net/img2/uploads/2020/01/345-phone-verified.png";
$lyt_image = "https://assets2.bukakios.net/img2/uploads/2026/06/598-phoneverificationtransparent.png";

$data_user = $app->grab_data("$api_url/v1/data_user.php?key=$api_key&id=$user_id");
$data_user = json_decode($data_user,true);
$data_user = $data_user['data'];
$nomor_hp = $data_user['hp'];
$count = strlen($nomor_hp) - 9;
//$verif_hp = $data_user['verif_hp'];
$verif_hp = $data_user['verif_hp']
;
$nomor_hp_sensor = substr_replace($nomor_hp, str_repeat('*', $count), 4, $count);


if (isset($_GET['act'])){
    require_once("miscall_proses.php");exit;
}

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
        <title>Reset PIN</title>
    </head>
<body>
    <div style="text-align:center; padding:20px;" class="container">
        <img src="https://assets.bukakios.net/img2/uploads/2021/08/89-calling-pana.png" alt="" style="width:200px; margin-top:50px;">
        <div class="form-group" style="margin-top:40px;">
            <div style="text-align:left">
                <div class='alert alert-warning'>
                Untuk Verifikasi, kami akan mencoba miscall ke nomor ini <?=$nomor_hp_sensor?> hp kamu, silahkan ingat <b>4 Angka Terakhir dari nomor yang menelpon</b> :
                </div>
            </div>
        </div>
        <div class="form-group in-otp1" style="display:none">
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
        <div class="form-group in-otp2" style="display:none">
            <small id="msg" class="form-text text-muted"></small>
        </div>
    </div>
    <div class="footer">
        <div class="mx-3 mb-2 mt-3">
            <button  id="btn-wa" class="btn  btn-block btn-rounded btn-success" style="color:#fff; padding:10px;"><i class='fa fa-phone'></i> Miscall Sekarang</button>
            <button  id="btn-submit" class="btn  btn-block btn-rounded btn-success" style="color:#fff; padding:10px; display:none"> Submit</button>
        </div>
    </div>
    <script src="<?php echo $c_url ?>/assets/js/jquery.js"></script>
    <script>
        $(document).ready(function(){    
     
           var csrf = "<?php echo $app->csrf() ?>";

  
            $('#btn-wa').on('click', function(){
                $('#btn-wa').html("proses..");
                $('#btn-wa').attr('disabled', true);
                $.ajax({
                    url:"miscall.php",
                    data:{act:"request"},
                    method:'get',
                    dataType:'json',
                    success: function(data){
                        if (data.status == 1){
                            $("#angkaawal").html(data.angka);
                            $(".in-otp1").show();
                            $("#btn-submit").show();
                            $('#btn-wa').html("Miscall Sekarang");
                            $('#btn-wa').attr('disabled', false);
                            $('#btn-wa').hide();
                        }else if (data.status == 0){
                            $(".in-otp2").hide();
                        }
                    }
                });
            });

            $('#btn-submit').on('click', function(){
                $('#btn-submit').html("proses..");
                $('#btn-submit').attr('disabled', true);
                $('#btn-kirim').attr('disabled', true);
                var token = $('#token').val();
                $.ajax({
                    url:"miscall.php",
                    data:{act:"sending",token:token},
                    method:'get',
                    dataType:'json',
                    success: function(data){
                        if (data.status == 1){
                            setTimeout(function(){ window.location.replace("success.php")}, 1000);
                        }else if (data.status == 0){
                            setTimeout(function(){ window.location.replace("error.php")}, 1000);
                        }
                    }
                });
            });

           
            

       });

             
        
    </script>
</body>
</html>