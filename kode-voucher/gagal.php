<?php
require_once("../config.php");

$html_title = "Pesan";

$lyt_button_link = "opentranslate://10;pulsa";

$lyt_button_name = "KEMBALI KE DASHBOARD";

$lyt_image = "https://finderiau.bukakios.net/assets/img/gagal.png";

$lyt_title = "Gagal";

$lyt_description = $_GET['desc'];
// $id = $_GET['id_trx'];
require_once(ROOT."/_template/general_message.php");
// sleep(3);
// header("Location: https://wv.bukakios.net/info-transaksi/?id=$id"); 

?>
<!-- <script>
var id = ;
setTimeout(function(){ window.location.href = "https://wv.bukakios.net/info-transaksi/?id="+id; }, 3000);                             
</script> -->