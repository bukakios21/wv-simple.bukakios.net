<?PHP

require_once("../config.php");

$html_title = "Penukaran Gagal";

$lyt_button_link = "opentranslate://10|pulsa";

$lyt_button_name = "KEMBALI KE DASHBOARD";

$lyt_image = "https://assets.bukakios.net/img/illustration/bc_logout.png";

$lyt_title = "Penukaran Gagal!";

$lyt_description = isset($_SESSION['msg']) && trim($_SESSION['msg']) !== ''
    ? $_SESSION['msg']
    : "Maaf, penukaran poin kamu tidak dapat diproses. Silakan coba lagi nanti.";

require_once(ROOT."/_template/general_message.php");
