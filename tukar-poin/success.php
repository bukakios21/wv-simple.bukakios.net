<?php
require_once("../config.php");

$html_title = "Penukaran Berhasil";

$lyt_button_link = "opentranslate://10|pulsa";

$lyt_button_name = "KEMBALI KE DASHBOARD";

$lyt_image = "data:image/svg+xml;utf8," . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 240 240" fill="none"><circle cx="120" cy="120" r="96" fill="#e8f2fb"/><circle cx="120" cy="120" r="66" fill="#1a7fce"/><path d="M92 121l20 20 40-44" stroke="#ffffff" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/><path d="M120 34l9.5 19.3L151 56.4l-15.5 15.1L139.1 93 120 82.9 100.9 93l3.6-21.5L89 56.4l21.5-3.1z" fill="#f5b301"/></svg>');

$lyt_title = "Penukaran Berhasil!";

$lyt_description = isset($_SESSION['msg']) && trim($_SESSION['msg']) !== ''
    ? $_SESSION['msg']
    : "Poin kamu berhasil ditukarkan. Terima kasih telah menukarkan poin di BukaKios.";

require_once(ROOT."/_template/general_message.php");
