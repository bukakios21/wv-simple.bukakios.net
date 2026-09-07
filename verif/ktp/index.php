<?php
require_once("../../config.php");
require_once('../../_session.php');
if ($user_id != 39958){
   // echo "UNDER MAINTENANCE";exit;
}
$real_has = md5(md5("bukakios-2021").$user_id);

header("Location: https://wv.bukakios.net/open.php?url=https://wv.bukakios.net/verif/ktp/verifikasi_biodata.php?data=$user_id-$real_has");
