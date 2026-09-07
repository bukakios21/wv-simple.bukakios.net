
<?php
require_once("../config.php");

$html_title = "Pesan";

$lyt_button_link = "opentranslate://10;pulsa";
$lyt_button_name = "KEMBALI KE DASHBOARD";

$lyt_image = "https://finderiau.bukakios.net/assets/img/sukses.png";

$lyt_title = "Berhasil";

$lyt_description = $_SESSION['msg'];
// $id = $_GET['id_trx'];
require_once(ROOT."/_template/general_message.php");

?>