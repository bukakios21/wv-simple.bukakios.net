<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once("../config.php");

$html_title = "Pesan";

$lyt_button_link = "opentranslate://10;pulsa";

$lyt_button_name = "KEMBALI KE DASHBOARD";

$lyt_image = "https://finderiau.bukakios.net/assets/img/sukses.png";

$lyt_title = "Berhasil";

$lyt_description = "Terima kasih. Transaksi kamu telah di teruskan ke operator :)";
require_once(ROOT."/_template/general_message.php");
// sleep(3);

?>
<!-- <script>
var id = ;
setTimeout(function(){ window.location.href = "https://wv.bukakios.net/info-transaksi/?id="+id; }, 3000);                             
</script> -->