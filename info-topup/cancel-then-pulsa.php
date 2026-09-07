<?php
$id = 0;
if(isset($_GET['id'])){
    $id = abs((int)$_GET['id']);
}
require_once("../config.php");
require_once("../config_db.php");
require_once("../_session.php");
$db->query("update topup set status=2 where uid='$user_id' and id='$id'");
header("location:$c_url/convert-pulsa/");