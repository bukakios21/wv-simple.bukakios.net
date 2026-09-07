<?PHP
header("location:../reset-pin/");
exit;
require_once("../config.php");
require_once("../_session.php");
$html_title = "Pesan";
$lyt_button_link = "opentranslate://10;pulsa";
$lyt_button_name = "KEMBALI KE DASHBOARD";
$lyt_image = "https://assets.bukakios.net/img2/uploads/2019/12/963-becek5.png";
$lyt_title = "Coming Soon!";
$lyt_description = "Maaf Fitur Ini Masih Dalam tahap pengembangan oleh tim kami";
require_once(ROOT."/_template/general_message.php");
?>