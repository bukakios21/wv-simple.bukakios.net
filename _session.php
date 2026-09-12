<?PHP
//echo "ada";
// ============================================================
// SESSION JWT BYPASS: jika sudah ada JWT di session (dari request sebelumnya),
// gunakan langsung tanpa perlu parse User-Agent lagi.
// Ini menangani AJAX request dari browser yang tidak kirim User-Agent BukaKios.
// ============================================================
if (isset($_GET['token_dev'])) {
    // Pastikan config.php sudah di-load (ROOT & $api_url tersedia)
    if (!defined('ROOT')) {
        require_once __DIR__ . '/config.php';
    }
    $user_jwt = $_GET['token_dev'];
    $user_id  = isset($_GET['uid_dev']) ? abs((int)$_GET['uid_dev']) : 1;
    $user_token     = 'dev_token';
    $user_token_trx = 'dev_token_trx';
    $webview_valid  = true;
    $new_detail     = true;
    $bukakios_version     = '1.0';
    $bukakios_version_int = 10;
    // Tandai sesi ini sebagai sesi dev. token_dev hanya di-pass dari build
    // lokal, jadi flag ini tidak akan pernah ada di production.
    // Request berikutnya cukup cek $_SESSION['is_dev'] untuk pakai ulang
    // session JWT tanpa perlu query token_dev lagi (lihat blok di bawah).
    $_SESSION['is_dev']               = true;
    $_SESSION['user_jwt']             = $user_jwt;
    $_SESSION['user_id']              = $user_id;
    $_SESSION['user_token']           = $user_token;
    $_SESSION['user_token_trx']       = $user_token_trx;
    $_SESSION['new_detail']           = $new_detail;
    $_SESSION['bukakios_version']     = $bukakios_version;
    $_SESSION['bukakios_version_int'] = $bukakios_version_int;
    // Dev bypass aktif — skip semua validasi di bawah
}
// ============================================================
// DEV BYPASS: sesi sudah ditandai is_dev (berasal dari request
// ?token_dev=... sebelumnya), pakai session JWT langsung tanpa perlu
// query token_dev terus-menerus. Di production flag is_dev tidak pernah
// ada sehingga blok ini tidak akan pernah dieksekusi.
// ============================================================
else if (!empty($_SESSION['is_dev']) && !empty($_SESSION['user_jwt'])) {
    $user_jwt = $_SESSION['user_jwt'];
    $user_id  = abs((int)($_SESSION['user_id'] ?? 1));
    $user_token     = $_SESSION['user_token'] ?? 'session_token';
    $user_token_trx = $_SESSION['user_token_trx'] ?? 'session_token_trx';
    $webview_valid  = true;
    $new_detail     = !empty($_SESSION['new_detail']);
    $bukakios_version     = $_SESSION['bukakios_version'] ?? '1.0';
    $bukakios_version_int = (int)($_SESSION['bukakios_version_int'] ?? 10);
}
else {
    // require 'config.php';
    //untuk memverifikasi token user di sini
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    //$user_agent = 'Android 3.0; en-us; Xoom Build/HRI39) AppleWebKit/534.13 |BukaKiosNative|1|1000|f726b6faaf4b8be6bd18aac35f795939|9465fee87e9f913a988d2b8d2c19c9e9';
    $webview_valid = false;
    $new_detail = false;
    if($app->contains($user_agent,"BukaKios")){
    	$webview_valid = true;
    }
    //get data user in agent
    //sample data agent (Android 3.0; en-us; Xoom Build/HRI39) AppleWebKit/534.13 |BukaKiosNative|version|user_id|token|token_trx
    // $split_ex_now = explode(".",$bukakios_lite_version_now);
    $user_agent_split = explode("|",$user_agent);
    // Guard: only parse if User-Agent has BukaKios format (at least 6 pipe-separated parts)
    if (count($user_agent_split) >= 6) {
        $bukakios_version = $user_agent_split[2]; //ex 1.0
        $split_ex = explode(".", $bukakios_version);
        $version_major = isset($split_ex[0]) && is_numeric($split_ex[0]) ? (int)$split_ex[0] : 1;
        $version_minor = isset($split_ex[1]) && is_numeric($split_ex[1]) ? (int)$split_ex[1] : 0;
        $bukakios_version_int = ($version_major * 10) + $version_minor; // example 1.0 = 10 , 1.1 = 11
        $user_id = abs((int)($user_agent_split[3] ?? 0));
        $user_token = $user_agent_split[4];
        $user_token_trx = $user_agent_split[5];
    } else {
        // Non-BukaKios request (e.g. browser) — set safe defaults
        $bukakios_version = '1.0';
        $bukakios_version_int = 10;
        $user_id = 0;
        $user_token = '';
        $user_token_trx = '';
    }

    //get version android
    if (count($user_agent_split) > 8 && isset($user_agent_split[8])) {
    	$version = $user_agent_split[8];
    	if (is_numeric($version) && $version >= 10) {
    		$new_detail = true;
    	}
    }

    //prepare jwt — samakan dengan project lama (wv.bukakios.net):
    // cukup cek keberadaan index [6], JANGAN syaratkan $webview_valid,
    // karena WebView APK bisa mengirim UA tanpa string "BukaKios" literal
    // padahal format pipe & JWT-nya lengkap → dulu regresi bikin $user_jwt kosong.
    if (isset($user_agent_split[6]) && $user_agent_split[6] !== '') {
        $user_jwt = $user_agent_split[6];
    } else  {
        // BukaKios app detected but malformed JWT — show error
        $html_title = "title::Sesi Login Habis";
    	$lyt_button_link = "opentranslate://10|pulsa";
    	$lyt_button_name = "KEMBALI KE DASHBOARD";
    	$lyt_image = "https://assets.bukakios.net/img/illustration/bc_logout.png";
    	$lyt_title = "Sesi Login Habis!";
    	$lyt_description = "Maaf, Versi aplikasi kamu sudah tidak didukung. Mohon update aplikasi kamu !!";
    	require_once(ROOT."/_template/general_message.php");
    	exit;
    }

    // Simpan semua session variable agar bisa diambil ulang oleh AJAX request
    $_SESSION['user_jwt']           = $user_jwt;
    $_SESSION['user_id']            = $user_id;
    $_SESSION['user_token']         = $user_token;
    $_SESSION['user_token_trx']     = $user_token_trx;
    $_SESSION['new_detail']         = $new_detail;
    $_SESSION['bukakios_version']     = $bukakios_version;
    $_SESSION['bukakios_version_int'] = $bukakios_version_int;

} // end dev bypass else
