<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

header("Location: old/index.php");
exit;

require_once("../config.php");
require_once("../_session.php");
// $user_jwt = "oke";

if (!isset($user_jwt) || strlen(trim($user_jwt)) < 10) {

}

$api_v2_url = "https://api-dev.bukakios.net";
// $api_v2_url = "https://61db-36-90-34-211.ngrok-free.app";
$file_me = "index.php";

function writeApiLog($label, $url, $payload, $response) {
    $logFile = __DIR__ . '/api_log.txt';
    $time    = date('Y-m-d H:i:s');
    $entry   = "[$time] $label\n"
             . "  URL     : $url\n"
             . "  PAYLOAD : " . (is_array($payload) ? json_encode($payload) : $payload) . "\n"
             . "  RESPONSE: $response\n"
             . str_repeat('-', 80) . "\n";
    file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
}

function CallApiV2WV($data, $url) {
    global $user_jwt;
    $vars = json_encode($data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Api-Key: PLowElenThErTeRAphaRDwINEAntrIDe',
        'Authorization: ' . $user_jwt,
    ]);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function CallApiV2WVGet($url) {
    global $user_jwt;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Api-Key: PLowElenThErTeRAphaRDwINEAntrIDe',
        'Authorization: ' . $user_jwt,
    ]);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

/************ ACTION HANDLER ************/
if (isset($_REQUEST['msg'])) {
    $msg = $_REQUEST['msg'];

    // msg=channels — ambil list metode OTP yang tersedia
    if ($msg === 'channels') {
        $res = CallApiV2WVGet("$api_v2_url/wv-x7Up2p/channels-otp");
        writeApiLog('channels', "$api_v2_url/wv-x7Up2p/channels-otp", '-', $res);
        $data = json_decode($res, true);
        echo json_encode($data !== null ? $data : array('status' => 0, 'error_msg' => 'Gagal mengambil channel OTP'));
        exit;
    }

    // msg=request_otp — kirim OTP reset PIN (metode dari body)
    if ($msg === 'request_otp') {
        $metode = isset($_REQUEST['metode']) ? $_REQUEST['metode'] : '';
        if (empty($metode)) {
            //bisa kosong untuk wa self
            // echo json_encode(array('status' => 0, 'error_msg' => 'Metode tidak boleh kosong'));
            // exit;
        }
        $res = CallApiV2WV(array('metode' => $metode), "$api_v2_url/wv-x7Up2p/user/pin/lupa/otp");
        writeApiLog('request_otp', "$api_v2_url/wv-x7Up2p/user/pin/lupa/otp", array('metode' => $metode), $res);
        $data = json_decode($res, true);
        echo json_encode($data !== null ? $data : array('status' => 0, 'error_msg' => 'Gagal request OTP'));
        exit;
    }

    // msg=check_wa_self — polling status verifikasi WA self (belum ada pin)
    if ($msg === 'check_wa_self') {
        $res  = CallApiV2WV(array('otp' => '', 'pin' => '', 'metode' => 'wa_self', 'token_otp' => ''), "$api_v2_url/wv-x7Up2p/user/pin/lupa/proses");
        writeApiLog('check_wa_self', "$api_v2_url/wv-x7Up2p/user/pin/lupa/proses", array('metode' => 'wa_self'), $res);
        $data = json_decode($res, true);
        echo json_encode($data !== null ? $data : array('status' => 0, 'error_msg' => 'Gagal cek status'));
        exit;
    }

    // msg=reset_pin — verifikasi OTP dan set PIN baru
    if ($msg === 'reset_pin') {
        $otp       = isset($_REQUEST['otp'])       ? $_REQUEST['otp']       : '';
        $pin       = isset($_REQUEST['pin'])       ? $_REQUEST['pin']       : '';
        $metode    = isset($_REQUEST['metode'])    ? $_REQUEST['metode']    : '';
        $token_otp = isset($_REQUEST['token_otp']) ? $_REQUEST['token_otp'] : '';

        if (empty($pin)) {
            echo json_encode(array('status' => 0, 'error_msg' => 'PIN tidak boleh kosong'));
            exit;
        }
        // wa_self: tidak perlu otp, tapi butuh token_otp
        if ($metode === 'wa_self' && empty($token_otp) && empty($otp)) {
            // polling — kirim tanpa otp & token_otp untuk cek status
        } elseif ($metode !== 'wa_self' && empty($otp)) {
            echo json_encode(array('status' => 0, 'error_msg' => 'OTP tidak boleh kosong'));
            exit;
        }

        $payload = array('otp' => $otp, 'pin' => $pin, 'metode' => $metode);
        if (!empty($token_otp)) $payload['token_otp'] = $token_otp;

        $res  = CallApiV2WV($payload, "$api_v2_url/wv-x7Up2p/user/pin/lupa/proses");
        writeApiLog('reset_pin', "$api_v2_url/wv-x7Up2p/user/pin/lupa/proses", $payload, $res);
        $data = json_decode($res, true);
        echo json_encode($data !== null ? $data : array('status' => 0, 'error_msg' => 'Gagal reset PIN'));
        exit;
    }

    echo json_encode(['status' => 0, 'error_msg' => 'Aksi tidak dikenali']);
    exit;
}
/************ END ACTION HANDLER ************/

/************ LOAD CHANNELS (SSR) ************/
$channels_raw = CallApiV2WVGet("$api_v2_url/wv-x7Up2p/channels-otp");
// var_dump($channels_raw);
  writeApiLog('channels', "$api_v2_url/wv-x7Up2p/channels-otp", '-', $channels_raw);
$channels_res = json_decode($channels_raw, true);

$otp_categories = [];
$otp_channels   = [];

if (isset($channels_res['status']) && $channels_res['status'] == 1) {
    $raw_categories = isset($channels_res['data']['categories']) ? $channels_res['data']['categories'] : array();
    $raw_channels   = isset($channels_res['data']['channel'])    ? $channels_res['data']['channel']    : array();

    // filter aktif, sort by display_order
    $otp_categories = array_values(array_filter($raw_categories, function($c) {
        return isset($c['is_active']) ? $c['is_active'] : true;
    }));
    usort($otp_categories, function($a, $b) {
        $ao = isset($a['display_order']) ? $a['display_order'] : 0;
        $bo = isset($b['display_order']) ? $b['display_order'] : 0;
        if ($ao == $bo) return 0;
        return ($ao < $bo) ? -1 : 1;
    });

    $otp_channels = array_values(array_filter($raw_channels, function($ch) {
        return isset($ch['is_active']) ? $ch['is_active'] : true;
    }));
    usort($otp_channels, function($a, $b) {
        $ao = isset($a['display_order']) ? $a['display_order'] : 0;
        $bo = isset($b['display_order']) ? $b['display_order'] : 0;
        if ($ao == $bo) return 0;
        return ($ao < $bo) ? -1 : 1;
    });
}

// group channels by category_id
$channels_by_cat = [];
foreach ($otp_channels as $ch) {
    $channels_by_cat[$ch['category_id']][] = $ch;
}

// helper: icon SVG per code/slug
function getChannelIcon($code) {
    if (strpos($code, 'wa') !== false) {
        // WhatsApp logo: bubble + handset
        return '<svg viewBox="0 0 24 24" class="h-6 w-6" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12.004 2C6.477 2 2 6.477 2 12.004c0 1.771.463 3.432 1.27 4.876L2 22l5.272-1.381A9.953 9.953 0 0 0 12.004 22C17.523 22 22 17.523 22 12.004 22 6.477 17.523 2 12.004 2zm0 18.214a8.21 8.21 0 0 1-4.187-1.148l-.3-.178-3.129.82.834-3.048-.196-.313A8.214 8.214 0 1 1 12.004 20.214z"/></svg>';
    }
    if (strpos($code, 'miss') !== false || strpos($code, 'miscall') !== false) {
        return '<svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.8 19.8 0 0 1 3.09 5.18 2 2 0 0 1 5.08 3h3a2 2 0 0 1 2 1.72c.12.9.33 1.77.63 2.61a2 2 0 0 1-.45 2.11L9 10.7a16 16 0 0 0 4.3 4.3l1.26-1.26a2 2 0 0 1 2.11-.45c.84.3 1.71.51 2.61.63A2 2 0 0 1 22 16.92Z" /><path d="m15 9 4-4m0 0h-3.5M19 5v3.5" /></svg>';
    }
    if (strpos($code, 'voice') !== false || strpos($code, 'call') !== false) {
        return '<svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.8 19.8 0 0 1 3.09 5.18 2 2 0 0 1 5.08 3h3a2 2 0 0 1 2 1.72c.12.9.33 1.77.63 2.61a2 2 0 0 1-.45 2.11L9 10.7a16 16 0 0 0 4.3 4.3l1.26-1.26a2 2 0 0 1 2.11-.45c.84.3 1.71.51 2.61.63A2 2 0 0 1 22 16.92Z" /></svg>';
    }
    if (strpos($code, 'sms') !== false) {
        return '<svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z" /></svg>';
    }
    if (strpos($code, 'email') !== false) {
        return '<svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>';
    }
    // default: OTP/chat icon
    return '<svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="7" width="14" height="10" rx="2" /><path d="M8 11h8M8 14h5" /></svg>';
}

// helper: icon bg + text color per code
function getChannelIconStyle($code) {
    if (strpos($code, 'wa_self') !== false) return array('bg-brand text-white', 'shadow-soft');
    if (strpos($code, 'wa') !== false)      return array('bg-emerald-50 text-brand', '');
    if ((strpos($code, 'voice') !== false || strpos($code, 'call') !== false) && strpos($code, 'miss') === false)
                                            return array('bg-blue-50 text-blue-500', '');
    if (strpos($code, 'miss') !== false || strpos($code, 'miscall') !== false)
                                            return array('bg-amber-50 text-amber-500', '');
    if (strpos($code, 'sms') !== false)     return array('bg-slate-100 text-slate-500', '');
    return array('bg-slate-100 text-slate-500', '');
}

// helper: card border style (featured = wa_self)
function getCardStyle($code) {
    if ($code === 'wa_self') {
        return 'mt-3 w-full rounded-[20px] border border-brand/70 bg-emerald-50/50 px-4 py-4 text-left shadow-card transition active:scale-[0.99] hover:bg-emerald-50';
    }
    return 'mt-3 w-full rounded-[20px] border border-slate-200 bg-white px-4 py-4 text-left shadow-soft transition hover:bg-slate-50';
}

// helper: category icon
function getCategoryIcon($slug) {
    if (strpos($slug, 'wa') !== false || strpos($slug, 'whatsapp') !== false) {
        return '<svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12.004 2C6.477 2 2 6.477 2 12.004c0 1.771.463 3.432 1.27 4.876L2 22l5.272-1.381A9.953 9.953 0 0 0 12.004 22C17.523 22 22 17.523 22 12.004 22 6.477 17.523 2 12.004 2zm0 18.214a8.21 8.21 0 0 1-4.187-1.148l-.3-.178-3.129.82.834-3.048-.196-.313A8.214 8.214 0 1 1 12.004 20.214z"/></svg>';
    }
    return '<svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.8 19.8 0 0 1 3.09 5.18 2 2 0 0 1 5.08 3h3a2 2 0 0 1 2 1.72c.12.9.33 1.77.63 2.61a2 2 0 0 1-.45 2.11L9 10.7a16 16 0 0 0 4.3 4.3l1.26-1.26a2 2 0 0 1 2.11-.45c.84.3 1.71.51 2.61.63A2 2 0 0 1 22 16.92Z" /></svg>';
}

function getCategoryIconBg($slug) {
    if (strpos($slug, 'wa') !== false || strpos($slug, 'whatsapp') !== false) return 'bg-brand';
    return 'bg-slate-800';
}
/************ END LOAD CHANNELS ************/
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BukaKios - Verifikasi WhatsApp</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: '#1a7fce',
            brandDark: '#1265a6',
            waDark: '#08733f',
            mutedText: '#6b7280',
            line: '#d9e1e8',
            chatBg: '#e7e1d5'
          },
          boxShadow: {
            card: '0 5px 14px rgba(16, 24, 40, 0.07)',
            soft: '0 4px 12px rgba(15, 23, 42, 0.06)',
            cta: '0 10px 22px rgba(26, 127, 206, 0.28)'
          },
          fontFamily: {
            sans: ['Inter', 'ui-sans-serif', 'system-ui', 'Segoe UI', 'Roboto', 'Arial', 'sans-serif'],
          }
        }
      }
    }
  </script>
  <style>
    .otpBox,
    .otpNewPinBox,
    .phoneBox,
    .phoneNewPinBox,
    .missedBox,
    .missedNewPinBox,
    .newPinBox,
    .confirmPinBox {
      border-color: #1a7fce !important;
    }

    .otpBox:focus,
    .otpNewPinBox:focus,
    .phoneBox:focus,
    .phoneNewPinBox:focus,
    .missedBox:focus,
    .missedNewPinBox:focus,
    .newPinBox:focus,
    .confirmPinBox:focus {
      border-color: #1a7fce !important;
      box-shadow: 0 0 0 2px rgba(26, 127, 206, 0.4) !important;
    }
  </style>
</head>
<body class="min-h-screen bg-white font-sans text-slate-950">

  <!-- Loading overlay: nindih seluruh layar, user ga bisa klik apapun -->
  <div id="loadingOverlay" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/40 backdrop-blur-[2px]">
    <div class="flex flex-col items-center gap-3 rounded-2xl bg-white px-8 py-6 shadow-xl">
      <svg class="h-8 w-8 animate-spin text-brand" viewBox="0 0 24 24" fill="none">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
      </svg>
      <p class="text-[14px] font-extrabold text-slate-700">Mengirim kode verifikasi…</p>
    </div>
  </div>

  <!-- Toast error: muncul di atas, auto-dismiss -->
  <div id="toastError" class="fixed top-4 left-1/2 z-[9998] hidden -translate-x-1/2 max-w-[90vw] w-full px-4">
    <div class="flex items-start gap-3 rounded-xl bg-red-600 px-4 py-3 shadow-lg text-white">
      <svg viewBox="0 0 24 24" class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
      <p id="toastErrorMsg" class="text-[14px] font-semibold leading-5"></p>
    </div>
  </div>

  <main class="relative w-full min-h-screen bg-white">

    <!-- Shared header -->
    <header class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-slate-100">
      <div class="flex items-center gap-3 px-4 py-3">
        <div class="h-1 flex-1 rounded-full bg-slate-100 overflow-hidden">
          <div id="topProgress" class="h-full w-[27%] rounded-full bg-[#1a7fce] transition-all duration-300"></div>
        </div>
        <div class="shrink-0 text-[16px] font-bold tracking-[-0.04em] text-brand">BukaKios</div>
      </div>
    </header>

    <!-- PAGE 1: pilih metode -->
    <section id="pageSelect" class="px-6 pt-8 pb-8">
      <h1 class="text-[27px] leading-8 font-extrabold tracking-[-0.04em]">Pilih cara verifikasi</h1>

      <?php if (empty($otp_categories)): ?>
      <div class="mt-6 rounded-xl bg-red-50 px-4 py-4 text-[14px] text-red-600 border border-red-200">
        Gagal memuat metode verifikasi. Silahkan tutup dan buka kembali halaman ini.
      </div>
      <?php else: ?>

      <?php
        // missed_call dan sms selalu masuk opsi lain, apapun kategorinya
        $hidden_codes = ['missed_call', 'miscall', 'sms'];
        function isHiddenChannel($ch) {
            global $hidden_codes;
            $code = strtolower($ch['code']);
            foreach ($hidden_codes as $h) {
                if ($code === $h || strpos($code, $h) !== false) return true;
            }
            return false;
        }

        // Kumpulkan channel "opsi lain" (missed_call & sms) per kategori
        // Format: [ ['cat' => $cat, 'channels' => [...]] ]
        $other_groups = [];
        foreach ($otp_categories as $cat) {
            $all_chs = isset($channels_by_cat[$cat['id']]) ? $channels_by_cat[$cat['id']] : array();
            $hidden  = array_values(array_filter($all_chs, 'isHiddenChannel'));
            if (!empty($hidden)) {
                $other_groups[] = ['cat' => $cat, 'channels' => $hidden];
            }
        }
        $has_other = !empty($other_groups);
      ?>

      <?php // ---- KATEGORI UTAMA: semua channel kecuali missed_call & sms ----
      foreach ($otp_categories as $cat):
        $all_chs   = isset($channels_by_cat[$cat['id']]) ? $channels_by_cat[$cat['id']] : array();
        $main_chs  = array_values(array_filter($all_chs, function($ch) { return !isHiddenChannel($ch); }));
        if (empty($main_chs)) continue;
      ?>
      <div class="mt-5 flex items-center gap-2 text-[12px] font-extrabold uppercase tracking-[0.08em] text-slate-500">
        <span class="grid h-5 w-5 place-items-center rounded-md <?php echo getCategoryIconBg($cat['slug']) ?> text-white">
          <?php echo getCategoryIcon($cat['slug']) ?>
        </span>
        <?php echo htmlspecialchars($cat['name']) ?>
      </div>

      <?php foreach ($main_chs as $ch):
        $icon      = getChannelIcon($ch['code']);
        $iconStyle = getChannelIconStyle($ch['code']);
        $cardStyle = getCardStyle($ch['code']);
        $badge     = isset($ch['badge'])       ? $ch['badge']       : null;
        $desc      = isset($ch['description']) ? $ch['description'] : '';
        $jsFunc    = "selectChannel('" . htmlspecialchars($ch['code'], ENT_QUOTES) . "', " . (int)$ch['otp_length'] . ", " . (int)$ch['countdown_seconds'] . ")";
      ?>
      <button type="button" class="<?php echo $cardStyle ?>" onclick="<?php echo $jsFunc ?>">
        <div class="flex items-center gap-4">
          <div class="grid h-[46px] w-[46px] shrink-0 place-items-center rounded-[14px] <?php echo $iconStyle[0] ?> <?php echo $iconStyle[1] ?>">
            <?php echo $icon ?>
          </div>
          <div class="min-w-0 flex-1">
            <div class="flex items-center gap-2">
              <h2 class="text-[16px] leading-5 font-extrabold"><?php echo htmlspecialchars($ch['name']) ?></h2>
              <?php if ($badge): ?>
              <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-extrabold text-brandDark"><?php echo htmlspecialchars($badge) ?></span>
              <?php endif; ?>
            </div>
            <?php if ($desc): ?>
            <p class="mt-0.5 text-[13px] leading-4 text-slate-500"><?php echo htmlspecialchars($desc) ?></p>
            <?php endif; ?>
          </div>
          <svg viewBox="0 0 24 24" class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg>
        </div>
      </button>
      <?php endforeach; ?>
      <?php endforeach; // end main categories ?>

      <?php if ($has_other): ?>
      <button id="toggleOtherOptions" type="button" class="mt-5 inline-block text-left text-[13px] font-extrabold text-brandDark">Lihat opsi lain (missed call, SMS)</button>

      <div id="otherOptions" class="hidden">
        <button id="hideOtherOptions" type="button" class="mt-5 inline-block text-left text-[13px] font-extrabold text-brandDark">Sembunyikan opsi lain</button>

        <?php // ---- OPSI LAIN: missed_call & sms, dikelompokkan per kategori asal ----
        foreach ($other_groups as $group):
          $cat = $group['cat'];
        ?>
        <div class="mt-6 flex items-center gap-2 text-[12px] font-extrabold uppercase tracking-[0.08em] text-slate-500">
          <span class="grid h-5 w-5 place-items-center rounded-md <?php echo getCategoryIconBg($cat['slug']) ?> text-white">
            <?php echo getCategoryIcon($cat['slug']) ?>
          </span>
          <?php echo htmlspecialchars($cat['name']) ?>
        </div>

        <?php foreach ($group['channels'] as $ch):
          $icon      = getChannelIcon($ch['code']);
          $iconStyle = getChannelIconStyle($ch['code']);
          $badge     = isset($ch['badge'])       ? $ch['badge']       : null;
          $desc      = isset($ch['description']) ? $ch['description'] : '';
          $jsFunc    = "selectChannel('" . htmlspecialchars($ch['code'], ENT_QUOTES) . "', " . (int)$ch['otp_length'] . ", " . (int)$ch['countdown_seconds'] . ")";
        ?>
        <button type="button" class="mt-3 w-full rounded-[18px] border border-slate-200 bg-white px-4 py-3 text-left shadow-soft transition hover:bg-slate-50" onclick="<?php echo $jsFunc ?>">
          <div class="flex items-center gap-4">
            <div class="grid h-[46px] w-[46px] shrink-0 place-items-center rounded-[14px] <?php echo $iconStyle[0] ?>">
              <?php echo $icon ?>
            </div>
            <div class="min-w-0 flex-1">
              <div class="flex items-center gap-2">
                <h2 class="text-[16px] leading-5 font-extrabold"><?php echo htmlspecialchars($ch['name']) ?></h2>
                <?php if ($badge): ?><span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-extrabold text-slate-500"><?php echo htmlspecialchars($badge) ?></span><?php endif; ?>
              </div>
              <?php if ($desc): ?><p class="mt-0.5 text-[13px] leading-4 text-slate-500"><?php echo htmlspecialchars($desc) ?></p><?php endif; ?>
            </div>
            <svg viewBox="0 0 24 24" class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg>
          </div>
        </button>
        <?php endforeach; ?>
        <?php endforeach; // end other_groups ?>

      </div><!-- #otherOptions -->
      <?php endif; ?>

      <?php endif; // end empty check ?>
    </section>

    <!-- PAGE 2: direct WhatsApp -->
    <section id="pageDirect" class="hidden px-6 pt-5 pb-8">
      <div class="overflow-hidden rounded-[11px] bg-chatBg shadow-soft ring-1 ring-slate-200/60">
        <div class="flex items-center justify-between bg-waDark px-3 py-2 text-white">
          <div class="flex items-center gap-2">
            <div class="grid h-7 w-7 place-items-center rounded-full bg-white text-waDark text-[12px] font-extrabold">BK</div>
            <div>
              <div class="flex items-center gap-1 text-[13px] font-extrabold leading-none">
                BukaKios
                <span class="grid h-3 w-3 place-items-center rounded-full bg-sky-500 text-[8px]">✓</span>
              </div>
              <div class="mt-0.5 text-[10px] text-white/80">Akun bisnis resmi · online</div>
            </div>
          </div>
        </div>

        <div class="px-3 pb-3 pt-2.5">
          <div class="mx-auto mb-1.5 w-max rounded-full bg-white/80 px-2 py-0.5 text-[9px] font-bold text-slate-500 shadow-sm">Hari ini</div>
          <div class="ml-auto w-max max-w-[60%] rounded-lg rounded-tr-sm bg-[#d7ffd8] px-2.5 py-1.5 shadow-sm">
            <div class="text-[11px] font-extrabold tracking-[0.06em] text-slate-900">VERIFY &nbsp;BK02H-BKA</div>
            <div class="mt-0.5 text-right text-[9px] font-semibold text-teal-600">09.41 ✓✓</div>
          </div>
          <div class="mt-2 text-center text-[10px] font-medium text-slate-500">pesan udah kami siapin — tinggal kirim</div>
        </div>
      </div>

      <h1 class="mt-7 text-[25px] leading-[25px] font-black tracking-[-0.055em]">Kirim 1 pesan, langsung terverifikasi</h1>
      <p class="mt-2 text-[14px] leading-[18px] text-slate-500">
        Dari nomor <b id="directWaNumber" class="font-extrabold text-slate-900">—</b> — begitu pesan masuk, kami cocokkan otomatis. Nggak ada kode yang perlu diketik.
      </p>

      <button id="openWa" type="button" onclick="handleOpenWa()" class="mt-6 flex h-[54px] w-full items-center justify-center rounded-full bg-brand text-[16px] font-extrabold text-white shadow-cta transition active:scale-[0.99] hover:bg-brandDark disabled:opacity-50 disabled:cursor-not-allowed disabled:active:scale-100">
        Buka WhatsApp &amp; Kirim pesan
      </button>

      <p class="mt-4 text-center text-[12px] leading-4 text-slate-400">
        Pastikan WhatsApp di HP ini pakai nomor<br /><span id="directWaNumberHint" class="font-semibold text-slate-600">—</span>
      </p>

      <div id="directTimerSection" class="hidden mt-5 text-center">
        <p class="text-[13px] text-slate-500">
          Kirim ulang? Tunggu <b id="timer" class="font-extrabold text-slate-900">00:49</b>
        </p>
        <div class="mt-2 h-0.5 w-full bg-slate-200">
          <div id="bottomTimerProgress" class="h-full bg-brand transition-all duration-1000" style="width:8%"></div>
        </div>
      </div>
    </section>

    <!-- PAGE 3: OTP (wa_otp / sms) -->
    <section id="pageOtp" class="hidden px-5 pt-5 pb-8">
      <h1 class="text-[25px] leading-[28px] font-black tracking-[-0.055em]">Masukkan kode verifikasi</h1>
      <p id="otpSubtitle" class="mt-1.5 text-[14px] leading-[18px] text-slate-500">Masukkan 6 digit kode yang sudah dikirim.</p>

      <div class="mt-6 flex justify-between gap-2 w-full">
        <input class="otpBox flex-1 min-w-0" inputmode="numeric" maxlength="1" autofocus />
        <input class="otpBox flex-1 min-w-0" inputmode="numeric" maxlength="1" />
        <input class="otpBox flex-1 min-w-0" inputmode="numeric" maxlength="1" />
        <input class="otpBox flex-1 min-w-0" inputmode="numeric" maxlength="1" />
        <input class="otpBox flex-1 min-w-0" inputmode="numeric" maxlength="1" />
        <input class="otpBox flex-1 min-w-0" inputmode="numeric" maxlength="1" />
      </div>

      <!-- Timer countdown -->
      <div id="otpTimerWrap" class="mt-5">
        <p class="text-center text-[13px] text-slate-500">
          Kode belum sampai? Tunggu <b id="otpTimer" class="font-extrabold text-slate-900">00:30</b>
        </p>
        <div class="mt-2 h-0.5 w-full bg-slate-200">
          <div id="otpTimerProgress" class="h-full w-[7%] bg-brand transition-all duration-1000"></div>
        </div>
      </div>

      <!-- Tombol kirim ulang — muncul setelah countdown habis -->
      <button id="otpResendBtn" type="button" onclick="resendOtp()"
        class="hidden mt-5 flex h-[50px] w-full items-center justify-center rounded-full border border-brand text-[15px] font-extrabold text-brand transition hover:bg-emerald-50 active:scale-[0.99]">
        Kirim ulang kode
      </button>

      <!-- Input PIN baru — muncul setelah 6 digit OTP terisi -->
      <div id="otpPinSection" class="hidden mt-7">
        <div class="flex items-center gap-2 mb-3">
          <div class="h-px flex-1 bg-slate-200"></div>
          <span class="text-[12px] font-bold text-slate-400 uppercase tracking-wide">Buat PIN baru</span>
          <div class="h-px flex-1 bg-slate-200"></div>
        </div>
        <p class="text-[14px] text-slate-500 mb-3">Masukkan 6 digit PIN baru kamu.</p>
        <div class="flex justify-between gap-2 w-full">
          <input class="otpNewPinBox flex-1 min-w-0 h-[54px] rounded-xl border border-slate-300 bg-white text-center text-[23px] font-extrabold outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/40" inputmode="numeric" maxlength="1" type="text" />
          <input class="otpNewPinBox flex-1 min-w-0 h-[54px] rounded-xl border border-slate-300 bg-white text-center text-[23px] font-extrabold outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/40" inputmode="numeric" maxlength="1" type="text" />
          <input class="otpNewPinBox flex-1 min-w-0 h-[54px] rounded-xl border border-slate-300 bg-white text-center text-[23px] font-extrabold outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/40" inputmode="numeric" maxlength="1" type="text" />
          <input class="otpNewPinBox flex-1 min-w-0 h-[54px] rounded-xl border border-slate-300 bg-white text-center text-[23px] font-extrabold outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/40" inputmode="numeric" maxlength="1" type="text" />
          <input class="otpNewPinBox flex-1 min-w-0 h-[54px] rounded-xl border border-slate-300 bg-white text-center text-[23px] font-extrabold outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/40" inputmode="numeric" maxlength="1" type="text" />
          <input class="otpNewPinBox flex-1 min-w-0 h-[54px] rounded-xl border border-slate-300 bg-white text-center text-[23px] font-extrabold outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/40" inputmode="numeric" maxlength="1" type="text" />
        </div>
      </div>
    </section>


    <!-- PAGE 4: Telepon Suara -->
    <section id="pagePhone" class="hidden px-6 pt-5 pb-8">
      <div class="relative overflow-hidden rounded-[14px] bg-gradient-to-br from-[#1d8bff] via-[#1e83f2] to-[#0865d5] px-5 py-8 text-white shadow-soft">
        <div class="pointer-events-none absolute -right-10 -top-12 h-40 w-40 rounded-full bg-white/10"></div>
        <div class="pointer-events-none absolute right-10 -top-20 h-44 w-44 rounded-full bg-white/5"></div>

        <div class="relative mx-auto grid h-[58px] w-[58px] place-items-center rounded-full bg-white text-blue-600 shadow-sm">
          <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.8 19.8 0 0 1 3.09 5.18 2 2 0 0 1 5.08 3h3a2 2 0 0 1 2 1.72c.12.9.33 1.77.63 2.61a2 2 0 0 1-.45 2.11L9 10.7a16 16 0 0 0 4.3 4.3l1.26-1.26a2 2 0 0 1 2.11-.45c.84.3 1.71.51 2.61.63A2 2 0 0 1 22 16.92Z" />
          </svg>
        </div>

        <div class="relative mt-5 text-center">
          <div class="text-[15px] font-extrabold leading-none">BukaKios Verifikasi</div>
        </div>
      </div>

      <h1 class="mt-6 text-[25px] leading-[28px] font-black tracking-[-0.055em]">Angkat &amp; dengerin 4 digitnya</h1>
      <p class="mt-1 text-[14px] leading-[18px] text-slate-500">
        Robot kami bacain kodenya <b class="font-extrabold text-slate-900">1 kali</b>, pelan-pelan.
      </p>

      <div class="mt-5 flex justify-between gap-4 w-full">
        <input class="phoneBox flex-1 min-w-0" inputmode="numeric" maxlength="1" autofocus />
        <input class="phoneBox flex-1 min-w-0" inputmode="numeric" maxlength="1" />
        <input class="phoneBox flex-1 min-w-0" inputmode="numeric" maxlength="1" />
        <input class="phoneBox flex-1 min-w-0" inputmode="numeric" maxlength="1" />
      </div>

      <!-- Timer countdown -->
      <div id="phoneTimerWrap" class="mt-5">
        <p class="text-center text-[13px] text-slate-500">
          Kode belum sampai? Tunggu <b id="phoneTimer" class="font-extrabold text-slate-900">00:30</b>
        </p>
        <div class="mt-2 h-0.5 w-full bg-slate-200">
          <div id="phoneTimerProgress" class="h-full w-[9%] bg-brand transition-all duration-1000"></div>
        </div>
      </div>

      <!-- Tombol kirim ulang — muncul setelah countdown habis -->
      <button id="phoneResendBtn" type="button" onclick="resendPhone()"
        class="hidden mt-5 flex h-[50px] w-full items-center justify-center rounded-full border border-brand text-[15px] font-extrabold text-brand transition hover:bg-emerald-50 active:scale-[0.99]">
        Kirim ulang panggilan
      </button>

      <!-- Input PIN baru — muncul setelah 4 digit OTP voice terisi -->
      <div id="phonePinSection" class="hidden mt-7">
        <div class="flex items-center gap-2 mb-3">
          <div class="h-px flex-1 bg-slate-200"></div>
          <span class="text-[12px] font-bold text-slate-400 uppercase tracking-wide">Buat PIN baru</span>
          <div class="h-px flex-1 bg-slate-200"></div>
        </div>
        <p class="text-[14px] text-slate-500 mb-3">Masukkan 6 digit PIN baru kamu.</p>
        <div class="flex justify-between gap-2 w-full">
          <input class="phoneNewPinBox flex-1 min-w-0 h-[54px] rounded-xl border border-slate-300 bg-white text-center text-[23px] font-extrabold outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/40" inputmode="numeric" maxlength="1" type="text" />
          <input class="phoneNewPinBox flex-1 min-w-0 h-[54px] rounded-xl border border-slate-300 bg-white text-center text-[23px] font-extrabold outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/40" inputmode="numeric" maxlength="1" type="text" />
          <input class="phoneNewPinBox flex-1 min-w-0 h-[54px] rounded-xl border border-slate-300 bg-white text-center text-[23px] font-extrabold outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/40" inputmode="numeric" maxlength="1" type="text" />
          <input class="phoneNewPinBox flex-1 min-w-0 h-[54px] rounded-xl border border-slate-300 bg-white text-center text-[23px] font-extrabold outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/40" inputmode="numeric" maxlength="1" type="text" />
          <input class="phoneNewPinBox flex-1 min-w-0 h-[54px] rounded-xl border border-slate-300 bg-white text-center text-[23px] font-extrabold outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/40" inputmode="numeric" maxlength="1" type="text" />
          <input class="phoneNewPinBox flex-1 min-w-0 h-[54px] rounded-xl border border-slate-300 bg-white text-center text-[23px] font-extrabold outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/40" inputmode="numeric" maxlength="1" type="text" />
        </div>
      </div>
    </section>

    <!-- PAGE 5: Missed Call -->
    <section id="pageMissed" class="hidden px-6 pt-5 pb-8">
      <h1 class="text-[25px] leading-[28px] font-black tracking-[-0.055em]">Lihat nomor yang missed call kamu</h1>
      <p class="mt-1.5 text-[14px] leading-[18px] text-slate-500">
        Masukkan <b class="font-extrabold text-slate-900">4 digit terakhir</b> nomor penelepon ke kolom di bawah.
      </p>

      <!-- Card prefix nomor penelepon -->
      <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
        <p class="text-[12px] font-semibold text-slate-400 leading-none mb-1.5">Nomor penelepon</p>
        <div class="flex items-center gap-1.5">
          <p id="missedPrefix" class="text-[18px] font-extrabold text-slate-800 tracking-wide leading-none">—</p>
          <span class="text-[18px] font-extrabold text-brand leading-none" id="missedPrefixDots">_ _ _ _</span>
        </div>
      </div>

      <!-- Input OTP — jumlah box sesuai otp_length, di-render JS -->
      <div id="missedBoxWrap" class="mt-4 flex justify-between gap-2 w-full">
        <!-- diisi oleh JS -->
      </div>

      <!-- Timer countdown -->
      <div id="missedTimerWrap" class="mt-5">
        <p class="text-center text-[13px] text-slate-500">
          Belum ada missed call? Tunggu <b id="missedTimer" class="font-extrabold text-slate-900">00:30</b>
        </p>
        <div class="mt-2 h-0.5 w-full bg-slate-200">
          <div id="missedTimerProgress" class="h-full w-[6%] bg-brand transition-all duration-1000"></div>
        </div>
      </div>

      <!-- Tombol kirim ulang — muncul setelah countdown habis -->
      <button id="missedResendBtn" type="button" onclick="resendMissed()"
        class="hidden mt-5 flex h-[50px] w-full items-center justify-center rounded-full border border-brand text-[15px] font-extrabold text-brand transition hover:bg-emerald-50 active:scale-[0.99]">
        Kirim ulang missed call
      </button>

      <!-- Input PIN baru — muncul setelah 4 digit terisi -->
      <div id="missedPinSection" class="hidden mt-7">
        <div class="flex items-center gap-2 mb-3">
          <div class="h-px flex-1 bg-slate-200"></div>
          <span class="text-[12px] font-bold text-slate-400 uppercase tracking-wide">Buat PIN baru</span>
          <div class="h-px flex-1 bg-slate-200"></div>
        </div>
        <p class="text-[14px] text-slate-500 mb-3">Masukkan 6 digit PIN baru kamu.</p>
        <div class="flex justify-between gap-2 w-full">
          <input class="missedNewPinBox flex-1 min-w-0 h-[54px] rounded-xl border border-slate-300 bg-white text-center text-[23px] font-extrabold outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/40" inputmode="numeric" maxlength="1" type="text" />
          <input class="missedNewPinBox flex-1 min-w-0 h-[54px] rounded-xl border border-slate-300 bg-white text-center text-[23px] font-extrabold outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/40" inputmode="numeric" maxlength="1" type="text" />
          <input class="missedNewPinBox flex-1 min-w-0 h-[54px] rounded-xl border border-slate-300 bg-white text-center text-[23px] font-extrabold outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/40" inputmode="numeric" maxlength="1" type="text" />
          <input class="missedNewPinBox flex-1 min-w-0 h-[54px] rounded-xl border border-slate-300 bg-white text-center text-[23px] font-extrabold outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/40" inputmode="numeric" maxlength="1" type="text" />
          <input class="missedNewPinBox flex-1 min-w-0 h-[54px] rounded-xl border border-slate-300 bg-white text-center text-[23px] font-extrabold outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/40" inputmode="numeric" maxlength="1" type="text" />
          <input class="missedNewPinBox flex-1 min-w-0 h-[54px] rounded-xl border border-slate-300 bg-white text-center text-[23px] font-extrabold outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/40" inputmode="numeric" maxlength="1" type="text" />
        </div>
      </div>
    </section>


    <!-- PAGE 6: Input PIN Baru (setelah wa_self verified / OTP valid) -->
    <section id="pageNewPin" class="hidden px-6 pt-5 pb-8">
      <div class="flex items-center justify-center">
        <div class="grid h-16 w-16 place-items-center rounded-full bg-emerald-100 text-emerald-600">
          <svg viewBox="0 0 24 24" class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
          </svg>
        </div>
      </div>

      <h1 class="mt-5 text-[25px] leading-[28px] font-black tracking-[-0.055em]">Buat PIN baru</h1>
      <p class="mt-1.5 text-[14px] leading-[18px] text-slate-500">
        Identitas kamu sudah terverifikasi. Masukkan 6 digit PIN baru, lalu sistem akan langsung memproses.
      </p>

      <!-- Input PIN baru -->
      <div class="mt-6">
        <label class="block text-[13px] font-bold text-slate-600 mb-2">PIN Baru</label>
        <div class="flex justify-between gap-2 w-full" id="newPinBoxesWrap">
          <input class="newPinBox flex-1 min-w-0" inputmode="numeric" maxlength="1" />
          <input class="newPinBox flex-1 min-w-0" inputmode="numeric" maxlength="1" />
          <input class="newPinBox flex-1 min-w-0" inputmode="numeric" maxlength="1" />
          <input class="newPinBox flex-1 min-w-0" inputmode="numeric" maxlength="1" />
          <input class="newPinBox flex-1 min-w-0" inputmode="numeric" maxlength="1" />
          <input class="newPinBox flex-1 min-w-0" inputmode="numeric" maxlength="1" />
        </div>
      </div>

      <!-- Konfirmasi PIN lama — tidak dipakai, disimpan hidden agar kompatibel dengan JS lama -->
      <div id="confirmPinSection" class="hidden mt-5 transition-all">
        <label class="block text-[13px] font-bold text-slate-600 mb-2">Ulangi PIN Baru</label>
        <div class="flex justify-between gap-2 w-full" id="confirmPinBoxesWrap">
          <input class="confirmPinBox flex-1 min-w-0" inputmode="numeric" maxlength="1" />
          <input class="confirmPinBox flex-1 min-w-0" inputmode="numeric" maxlength="1" />
          <input class="confirmPinBox flex-1 min-w-0" inputmode="numeric" maxlength="1" />
          <input class="confirmPinBox flex-1 min-w-0" inputmode="numeric" maxlength="1" />
          <input class="confirmPinBox flex-1 min-w-0" inputmode="numeric" maxlength="1" />
          <input class="confirmPinBox flex-1 min-w-0" inputmode="numeric" maxlength="1" />
        </div>
        <p id="pinMismatchMsg" class="hidden mt-2 text-[13px] font-semibold text-red-500">PIN tidak cocok. Coba lagi.</p>
      </div>

      <button id="submitNewPin" type="button" onclick="submitNewPin()"
        class="hidden mt-7 flex h-[54px] w-full items-center justify-center rounded-full bg-brand text-[16px] font-extrabold text-white shadow-cta transition active:scale-[0.99] hover:bg-brandDark disabled:opacity-50 disabled:cursor-not-allowed disabled:active:scale-100">
        Simpan PIN Baru
      </button>
    </section>

  </main>

  <script>
    const pageSelect  = document.getElementById('pageSelect');
    const pageDirect  = document.getElementById('pageDirect');
    const pageOtp     = document.getElementById('pageOtp');
    const pagePhone   = document.getElementById('pagePhone');
    const pageMissed  = document.getElementById('pageMissed');
    const pageNewPin  = document.getElementById('pageNewPin');
    const toggleOtherOptions = document.getElementById('toggleOtherOptions');
    const hideOtherOptions = document.getElementById('hideOtherOptions');
    const otherOptions = document.getElementById('otherOptions');
    const backBtn = document.getElementById('backBtn') || { classList: { add: function(){}, remove: function(){} }, addEventListener: function(){} };
    const topProgress = document.getElementById('topProgress');
    const timerEl = document.getElementById('timer');
    const bottomProgress = document.getElementById('bottomTimerProgress');
    const otpTimerEl   = document.getElementById('otpTimer');
    const otpProgress  = document.getElementById('otpTimerProgress');
    const otpTimerWrap = document.getElementById('otpTimerWrap');
    const otpResendBtn = document.getElementById('otpResendBtn');
    const otpBoxes     = Array.from(document.querySelectorAll('.otpBox'));
    let _otpMetode = ''; // metode aktif di pageOtp (wa_otp / sms)
    let _otpTokenOtp = ''; // token_otp dari BE setelah OTP valid
    let _isOtpVerifying = false;
    const otpPinSection  = document.getElementById('otpPinSection');
    const otpNewPinBoxes = Array.from(document.querySelectorAll('.otpNewPinBox'));
    const phoneNewPinBoxes  = Array.from(document.querySelectorAll('.phoneNewPinBox'));
    const missedNewPinBoxes = Array.from(document.querySelectorAll('.missedNewPinBox'));
    let _phoneMetode  = 'voice';
    let _phoneTokenOtp = '';
    let _isPhoneVerifying = false;
    let _missedMetode = 'missed_call';
    let _missedTokenOtp = '';
    let _isMissedVerifying = false;
    let _missedPrefix = '';
    const phoneTimerEl = document.getElementById('phoneTimer');
    const phoneProgress = document.getElementById('phoneTimerProgress');
    const phoneBoxes = Array.from(document.querySelectorAll('.phoneBox'));
    const missedTimerEl = document.getElementById('missedTimer');
    const missedProgress = document.getElementById('missedTimerProgress');
    let missedBoxes = []; // diisi dinamis saat showMissed()

    let countdownInterval = null;
    let otpCountdownInterval = null;
    let phoneCountdownInterval = null;
    let missedCountdownInterval = null;
    let remaining = 30;
    let otpRemaining = 30;
    let phoneRemaining = 30;
    let missedRemaining = 30;
    const duration = 30;
    const otpDuration = 30;
    const phoneDuration = 30;
    const missedDuration = 30;

    const loadingOverlay = document.getElementById('loadingOverlay');
    const toastError     = document.getElementById('toastError');
    const toastErrorMsg  = document.getElementById('toastErrorMsg');
    let toastTimer = null;

    function showLoading() {
      loadingOverlay.classList.remove('hidden');
      loadingOverlay.classList.add('flex');
    }

    function hideLoading() {
      loadingOverlay.classList.add('hidden');
      loadingOverlay.classList.remove('flex');
    }

    function showToastError(msg) {
      toastErrorMsg.textContent = msg;
      toastError.classList.remove('hidden');
      if (toastTimer) clearTimeout(toastTimer);
      toastTimer = setTimeout(() => toastError.classList.add('hidden'), 4000);
    }

    // Mapping code → page function (dipanggil setelah OTP berhasil dikirim)
    function goToPage(code, apiData) {
      if (code === 'wa_self') {
        showDirect(apiData);
      } else if (code.startsWith('wa')) {
        showOtp(code);
      } else if (code === 'missed_call' || code === 'miscall' || code.includes('miss')) {
        showMissed(code, apiData);
      } else if (code === 'sms') {
        showOtp('sms');
      } else {
        showPhone(code); // voice, dll
      }
    }

    // Dipanggil dari onclick PHP-generated card
    async function selectChannel(code, otpLength, countdown) {
      showLoading();
      try {
        const res = await fetch('<?php echo $file_me ?>?msg=request_otp', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'metode=' + encodeURIComponent(code),
        });
        const json = await res.json();
        if (json.status === 1) {
          hideLoading();
          goToPage(code, json.data ?? {});
        } else {
          hideLoading();
          showToastError(json.error_msg || 'Gagal mengirim kode verifikasi. Coba lagi.');
        }
      } catch (e) {
        hideLoading();
        showToastError('Koneksi bermasalah. Periksa internet kamu dan coba lagi.');
      }
    }

    function showSelect() {
      pageDirect.classList.add('hidden');
      pageOtp.classList.add('hidden');
      pagePhone.classList.add('hidden');
      pageMissed.classList.add('hidden');
      pageNewPin.classList.add('hidden');
      pageSelect.classList.remove('hidden');
      topProgress.style.width = '27%';
      otherOptions?.classList.add('hidden');
      toggleOtherOptions?.classList.remove('hidden');
      history.replaceState(null, '', location.pathname);
      stopTimer();
      stopOtpTimer();
      stopPhoneTimer();
      stopMissedTimer();
      stopPollingWaSelf();
    }

    let _waLink     = '#';  // simpan link WA untuk dibuka saat tombol diklik
    let _tokenOtp   = '';   // token_otp dari BE setelah wa_self verified
    let _metode     = '';   // metode yang sedang aktif
    let _pollingInterval = null; // interval polling wa_self

    const newPinBoxes     = Array.from(document.querySelectorAll('.newPinBox'));
    const confirmPinBoxes = Array.from(document.querySelectorAll('.confirmPinBox'));
    const confirmPinSection = document.getElementById('confirmPinSection');
    const submitNewPinBtn   = document.getElementById('submitNewPin');

    const pinBoxClass = 'flex-1 min-w-0 h-[54px] rounded-xl border border-slate-300 bg-white text-center text-[23px] font-extrabold outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/40';

    // Setup box PIN baru wa_self — saat digit ke-6 terisi, langsung submit seperti metode lain
    newPinBoxes.forEach((box, index) => {
      box.className = pinBoxClass;
      box.addEventListener('input', (e) => {
        const v = e.target.value.replace(/\D/g, '');
        e.target.value = v.slice(-1);
        if (v && index < newPinBoxes.length - 1) {
          newPinBoxes[index + 1].focus();
        } else if (v && index === newPinBoxes.length - 1) {
          submitNewPin();
        }
      });
      box.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !box.value && index > 0) newPinBoxes[index - 1].focus();
        if (e.key === 'Backspace' && index === newPinBoxes.length - 1) {
          confirmPinSection.classList.add('hidden');
          submitNewPinBtn.classList.add('hidden');
          confirmPinBoxes.forEach(b => b.value = '');
          document.getElementById('pinMismatchMsg')?.classList.add('hidden');
        }
      });
    });

    // Setup box konfirmasi PIN
    confirmPinBoxes.forEach((box, index) => {
      box.className = pinBoxClass;
      box.addEventListener('input', (e) => {
        const v = e.target.value.replace(/\D/g, '');
        e.target.value = v.slice(-1);
        if (v && index < confirmPinBoxes.length - 1) confirmPinBoxes[index + 1].focus();
      });
      box.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !box.value && index > 0) confirmPinBoxes[index - 1].focus();
      });
    });

    function showDirect(apiData) {
      _waLink = apiData?.wa_link   || '#';
      const waNumber = apiData?.wa_number || '—';

      const openWaEl         = document.getElementById('openWa');
      const directWaNumberEl = document.getElementById('directWaNumber');
      const directWaHintEl   = document.getElementById('directWaNumberHint');
      const timerSection     = document.getElementById('directTimerSection');

      if (directWaNumberEl) directWaNumberEl.textContent = waNumber;
      if (directWaHintEl)   directWaHintEl.textContent   = waNumber;

      // Reset tombol & sembunyikan timer
      if (openWaEl) openWaEl.disabled = false;
      if (timerSection) timerSection.classList.add('hidden');

      pageSelect.classList.add('hidden');
      pageOtp.classList.add('hidden');
      pagePhone.classList.add('hidden');
      pageMissed.classList.add('hidden');
      pageDirect.classList.remove('hidden');
      topProgress.style.width = '50%';
      history.replaceState(null, '', location.pathname);
      stopOtpTimer();
      stopPhoneTimer();
      stopMissedTimer();
      stopTimer();
    }

    // Dipanggil saat tombol "Buka WhatsApp" diklik
    function handleOpenWa() {
      if (_waLink && _waLink !== '#') {
        window.location.href = 'open://' + _waLink;
      }
      // Setelah klik: disable tombol, tampilkan timer countdown
      const openWaEl     = document.getElementById('openWa');
      const timerSection = document.getElementById('directTimerSection');
      if (openWaEl)     openWaEl.disabled = true;
      if (timerSection) timerSection.classList.remove('hidden');
      startTimer();
      // Mulai polling setiap 3 detik untuk cek apakah WA sudah terverifikasi
      startPollingWaSelf();
    }

    function showOtp(metode) {
      _otpMetode = metode || 'wa_otp';
      _otpTokenOtp = '';
      _isOtpVerifying = false;
      // Subtitle sesuai metode
      const subtitle = document.getElementById('otpSubtitle');
      if (subtitle) {
        if (_otpMetode === 'sms') subtitle.textContent = 'Masukkan 6 digit kode yang dikirim via SMS.';
        else subtitle.textContent = 'Masukkan 6 digit kode yang dikirim via WhatsApp.';
      }
      // Reset OTP boxes
      otpBoxes.forEach(b => { b.value = ''; b.disabled = false; });
      // Reset PIN section
      otpNewPinBoxes?.forEach(b => { b.value = ''; b.disabled = false; });
      otpPinSection?.classList.add('hidden');
      // Reset resend btn & timer
      otpResendBtn?.classList.add('hidden');
      otpTimerWrap?.classList.remove('hidden');

      pageSelect.classList.add('hidden');
      pageDirect.classList.add('hidden');
      pagePhone.classList.add('hidden');
      pageMissed.classList.add('hidden');
      pageNewPin.classList.add('hidden');
      pageOtp.classList.remove('hidden');
      topProgress.style.width = '50%';
      history.replaceState(null, '', location.pathname);
      stopTimer();
      stopPhoneTimer();
      stopMissedTimer();
      startOtpTimer();
      setTimeout(() => otpBoxes[0]?.focus(), 50);
    }



    function showPhone(metode) {
      _phoneMetode = metode || 'voice';
      _phoneTokenOtp = '';
      _isPhoneVerifying = false;
      pageSelect.classList.add('hidden');
      pageDirect.classList.add('hidden');
      pageOtp.classList.add('hidden');
      pageMissed.classList.add('hidden');
      pageNewPin.classList.add('hidden');
      pagePhone.classList.remove('hidden');
      // Reset phone boxes & PIN section
      phoneBoxes.forEach(b => { b.value = ''; b.disabled = false; });
      phoneNewPinBoxes?.forEach(b => { b.value = ''; b.disabled = false; });
      document.getElementById('phonePinSection')?.classList.add('hidden');
      topProgress.style.width = '50%';
      history.replaceState(null, '', location.pathname);
      stopTimer();
      stopOtpTimer();
      stopMissedTimer();
      startPhoneTimer();
      setTimeout(() => phoneBoxes[0]?.focus(), 50);
    }


    function buildMissedBoxes(otpLength) {
      const wrap = document.getElementById('missedBoxWrap');
      if (!wrap) return;
      wrap.innerHTML = '';
      const n = otpLength || 4;
      // Update dots placeholder di card prefix
      const dotsEl = document.getElementById('missedPrefixDots');
      if (dotsEl) dotsEl.textContent = Array(n).fill('_').join(' ');
      for (let i = 0; i < n; i++) {
        const inp = document.createElement('input');
        inp.className = 'missedBox flex-1 min-w-0 h-[64px] rounded-xl border border-slate-300 bg-white text-center text-[27px] font-extrabold outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/40';
        inp.inputMode = 'numeric';
        inp.maxLength = 1;
        if (i === 0) inp.autofocus = true;
        wrap.appendChild(inp);
      }
      missedBoxes = Array.from(wrap.querySelectorAll('.missedBox'));
      setupMissedBoxListeners();
    }

    function setupMissedBoxListeners() {
      missedBoxes.forEach((box, index) => {
        box.addEventListener('input', (event) => {
          const value = event.target.value.replace(/\D/g, '');
          event.target.value = value.slice(-1);
          if (value && index < missedBoxes.length - 1) {
            missedBoxes[index + 1].focus();
          } else if (value && index === missedBoxes.length - 1) {
            checkMissedComplete();
          }
        });
        box.addEventListener('keydown', (event) => {
          if (event.key === 'Backspace') {
            if (!box.value && index > 0) missedBoxes[index - 1].focus();
            _missedTokenOtp = '';
            missedPinSection?.classList.add('hidden');
          }
        });
        box.addEventListener('paste', (event) => {
          event.preventDefault();
          const pasted = (event.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, missedBoxes.length);
          pasted.split('').forEach((digit, i) => { if (missedBoxes[i]) missedBoxes[i].value = digit; });
          missedBoxes[Math.min(pasted.length, missedBoxes.length - 1)]?.focus();
          setTimeout(checkMissedComplete, 50);
        });
      });
    }

    function showMissed(metode, apiData) {
      _missedMetode = metode || 'missed_call';
      _missedTokenOtp = '';
      _isMissedVerifying = false;
      _missedPrefix = apiData?.prefix || '';
      const otpLength = apiData?.otp_length || 4;
      // Tampilkan prefix di UI
      const prefixEl = document.getElementById('missedPrefix');
      if (prefixEl) prefixEl.textContent = _missedPrefix || '—';
      // Render input boxes sesuai otp_length
      buildMissedBoxes(otpLength);
      // Reset PIN section
      missedNewPinBoxes?.forEach(b => { b.value = ''; b.disabled = false; });
      document.getElementById('missedPinSection')?.classList.add('hidden');
      document.getElementById('missedResendBtn')?.classList.add('hidden');
      document.getElementById('missedTimerWrap')?.classList.remove('hidden');

      pageSelect.classList.add('hidden');
      pageDirect.classList.add('hidden');
      pageOtp.classList.add('hidden');
      pagePhone.classList.add('hidden');
      pageNewPin.classList.add('hidden');
      pageMissed.classList.remove('hidden');
      topProgress.style.width = '50%';
      history.replaceState(null, '', location.pathname);
      stopTimer();
      stopOtpTimer();
      stopPhoneTimer();
      startMissedTimer();
      setTimeout(() => missedBoxes[0]?.focus(), 50);
    }

    function showNewPin(tokenOtp, metode) {
      _tokenOtp = tokenOtp || '';
      _metode   = metode   || '';
      // Reset semua input & sembunyikan konfirmasi + tombol
      newPinBoxes.forEach(b => { b.value = ''; b.disabled = false; });
      confirmPinBoxes.forEach(b => b.value = '');
      document.getElementById('pinMismatchMsg')?.classList.add('hidden');
      confirmPinSection.classList.add('hidden');
      submitNewPinBtn.classList.add('hidden');
      submitNewPinBtn.disabled = false;

      pageSelect.classList.add('hidden');
      pageDirect.classList.add('hidden');
      pageOtp.classList.add('hidden');
      pagePhone.classList.add('hidden');
      pageMissed.classList.add('hidden');
      pageNewPin.classList.remove('hidden');
      topProgress.style.width = '75%';
      history.replaceState(null, '', location.pathname);
      stopTimer(); stopOtpTimer(); stopPhoneTimer(); stopMissedTimer();
      setTimeout(() => newPinBoxes[0]?.focus(), 50);
    }

    async function submitNewPin() {
      const pin = newPinBoxes.map(b => b.value).join('');
      const mismatchMsg = document.getElementById('pinMismatchMsg');
      const submitBtn   = document.getElementById('submitNewPin');

      if (pin.length < 6) {
        showToastError('PIN harus 6 digit.');
        return;
      }
      mismatchMsg?.classList.add('hidden');

      submitBtn.disabled = true;
      newPinBoxes.forEach(b => b.disabled = true);
      showLoading();
      try {
        const body = new URLSearchParams({ otp: '', pin, metode: _metode, token_otp: _tokenOtp });
        const res  = await fetch('<?php echo $file_me ?>?msg=reset_pin', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: body.toString(),
        });
        const json = await res.json();
        hideLoading();
        if (json.status === 1) {
          // Sukses — tampilkan halaman sukses atau redirect
          topProgress.style.width = '100%';
          pageNewPin.classList.add('hidden');
          // Tampilkan pesan sukses sederhana
          document.querySelector('main').insertAdjacentHTML('beforeend', `
            <section id="pageSuccess" class="flex flex-col items-center justify-center px-6 text-center" style="min-height: calc(100vh - 64px);">
              <div class="mx-auto grid h-20 w-20 place-items-center rounded-full bg-emerald-100 text-emerald-600">
                <svg viewBox="0 0 24 24" class="h-10 w-10" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              </div>
              <h1 class="mt-6 text-[27px] font-black tracking-[-0.055em]">PIN berhasil direset!</h1>
              <button onclick="window.location.replace('<?php echo $file_me ?>')" class="mt-8 flex h-[50px] w-full max-w-xs items-center justify-center rounded-full border border-brand text-[15px] font-extrabold text-brand transition hover:bg-emerald-50 active:scale-[0.99]">
                Kembali
              </button>
            </section>
          `);
          backBtn.classList.add('hidden');
        } else {
          newPinBoxes.forEach(b => { b.disabled = false; b.value = ''; });
          setTimeout(() => newPinBoxes[0]?.focus(), 50);
          submitBtn.disabled = false;
          showToastError(json.error_msg || 'Gagal menyimpan PIN. Coba lagi.');
        }
      } catch (e) {
        hideLoading();
        newPinBoxes.forEach(b => { b.disabled = false; b.value = ''; });
        setTimeout(() => newPinBoxes[0]?.focus(), 50);
        submitBtn.disabled = false;
        showToastError('Koneksi bermasalah. Periksa internet kamu.');
      }
    }

    // ── Polling wa_self ──────────────────────────────────────────────────────
    function startPollingWaSelf() {
      stopPollingWaSelf();
      _pollingInterval = setInterval(async () => {
        try {
          const res  = await fetch('<?php echo $file_me ?>?msg=check_wa_self', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: '',
          });
          const json = await res.json();
          if (json.status === 1 && json.data?.token_otp) {
            // Verified! Dapat token_otp
            stopPollingWaSelf();
            stopTimer();
            showNewPin(json.data.token_otp, 'wa_self');
          }
          // Kalau status pending atau error lain → lanjut polling
        } catch (_) { /* abaikan error jaringan sementara */ }
      }, 3000);
    }

    function stopPollingWaSelf() {
      if (_pollingInterval) clearInterval(_pollingInterval);
      _pollingInterval = null;
    }

    function startTimer() {
      stopTimer();
      remaining = duration;
      updateTimer();
      countdownInterval = setInterval(() => {
        remaining = Math.max(remaining - 1, 0);
        updateTimer();
        if (remaining === 0) {
          stopTimer();
          // Countdown habis: unlock tombol, sembunyikan timer
          const openWaEl     = document.getElementById('openWa');
          const timerSection = document.getElementById('directTimerSection');
          if (openWaEl)     openWaEl.disabled = false;
          if (timerSection) timerSection.classList.add('hidden');
        }
      }, 1000);
    }

    function stopTimer() {
      if (countdownInterval) clearInterval(countdownInterval);
      countdownInterval = null;
    }

    function updateTimer() {
      timerEl.textContent = '00:' + String(remaining).padStart(2, '0');
      const elapsed = duration - remaining;
      bottomProgress.style.width = Math.min(100, Math.max(8, (elapsed / duration) * 100)) + '%';
    }

    function startOtpTimer() {
      stopOtpTimer();
      otpRemaining = otpDuration;
      updateOtpTimer();
      otpCountdownInterval = setInterval(() => {
        otpRemaining = Math.max(otpRemaining - 1, 0);
        updateOtpTimer();
        if (otpRemaining === 0) {
          stopOtpTimer();
          // Countdown habis: sembunyikan timer, tampilkan tombol kirim ulang
          otpTimerWrap?.classList.add('hidden');
          otpResendBtn?.classList.remove('hidden');
        }
      }, 1000);
    }

    function stopOtpTimer() {
      if (otpCountdownInterval) clearInterval(otpCountdownInterval);
      otpCountdownInterval = null;
    }

    function updateOtpTimer() {
      otpTimerEl.textContent = '00:' + String(otpRemaining).padStart(2, '0');
      const elapsed = otpDuration - otpRemaining;
      otpProgress.style.width = Math.min(100, Math.max(7, (elapsed / otpDuration) * 100)) + '%';
    }



    function startPhoneTimer() {
      stopPhoneTimer();
      phoneRemaining = phoneDuration;
      document.getElementById('phoneTimerWrap')?.classList.remove('hidden');
      document.getElementById('phoneResendBtn')?.classList.add('hidden');
      updatePhoneTimer();
      phoneCountdownInterval = setInterval(() => {
        phoneRemaining = Math.max(phoneRemaining - 1, 0);
        updatePhoneTimer();
        if (phoneRemaining === 0) {
          stopPhoneTimer();
          document.getElementById('phoneTimerWrap')?.classList.add('hidden');
          document.getElementById('phoneResendBtn')?.classList.remove('hidden');
        }
      }, 1000);
    }

    function stopPhoneTimer() {
      if (phoneCountdownInterval) clearInterval(phoneCountdownInterval);
      phoneCountdownInterval = null;
    }

    function updatePhoneTimer() {
      phoneTimerEl.textContent = '00:' + String(phoneRemaining).padStart(2, '0');
      const elapsed = phoneDuration - phoneRemaining;
      phoneProgress.style.width = Math.min(100, Math.max(9, (elapsed / phoneDuration) * 100)) + '%';
    }


    function startMissedTimer() {
      stopMissedTimer();
      missedRemaining = missedDuration;
      updateMissedTimer();
      missedCountdownInterval = setInterval(() => {
        missedRemaining = Math.max(missedRemaining - 1, 0);
        updateMissedTimer();
        if (missedRemaining === 0) {
          stopMissedTimer();
          document.getElementById('missedTimerWrap')?.classList.add('hidden');
          document.getElementById('missedResendBtn')?.classList.remove('hidden');
        }
      }, 1000);
    }

    function stopMissedTimer() {
      if (missedCountdownInterval) clearInterval(missedCountdownInterval);
      missedCountdownInterval = null;
    }

    function updateMissedTimer() {
      missedTimerEl.textContent = '00:' + String(missedRemaining).padStart(2, '0');
      const elapsed = missedDuration - missedRemaining;
      missedProgress.style.width = Math.min(100, Math.max(6, (elapsed / missedDuration) * 100)) + '%';
    }

    toggleOtherOptions?.addEventListener('click', () => {
      otherOptions.classList.remove('hidden');
      toggleOtherOptions.classList.add('hidden');
    });
    document.getElementById('hideOtherOptions')?.addEventListener('click', () => {
      otherOptions.classList.add('hidden');
      toggleOtherOptions?.classList.remove('hidden');
    });

    async function submitOtpWithPin() {
      const otp = otpBoxes.map(b => b.value).join('');
      const pin = otpNewPinBoxes.map(b => b.value).join('');

      if (otp.length !== 6) { showToastError('Masukkan 6 digit kode OTP.'); return; }
      if (pin.length !== 6) { showToastError('Masukkan 6 digit PIN baru.'); return; }
      if (!_otpTokenOtp) { showToastError('OTP belum tervalidasi. Masukkan ulang kode OTP.'); return; }

      // Disable semua input PIN agar tidak bisa diubah saat loading
      otpNewPinBoxes.forEach(b => b.disabled = true);
      showLoading();
      try {
        const body = new URLSearchParams({ otp, pin, metode: _otpMetode, token_otp: _otpTokenOtp });
        const res  = await fetch('<?php echo $file_me ?>?msg=reset_pin', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: body.toString(),
        });
        const json = await res.json();
        hideLoading();
        if (json.status === 1) {
          stopOtpTimer();
          // Inject pageSuccess
          const main = document.querySelector('main');
          const existing = document.getElementById('pageSuccess');
          if (!existing) {
            main.insertAdjacentHTML('beforeend', `
              <section id="pageSuccess" class="flex flex-col items-center justify-center px-6 text-center" style="min-height: calc(100vh - 64px);">
                <div class="mx-auto grid h-20 w-20 place-items-center rounded-full bg-emerald-100 text-emerald-600">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h1 class="mt-6 text-[27px] font-black tracking-[-0.055em]">PIN berhasil direset!</h1>
                <button onclick="window.location.replace('<?php echo $file_me ?>')" class="mt-8 flex h-[50px] w-full max-w-xs items-center justify-center rounded-full border-2 border-brand text-[15px] font-extrabold text-brand transition hover:bg-emerald-50 active:scale-[0.99]">
                  Kembali
                </button>
              </section>
            `);
          }
          pageOtp.classList.add('hidden');
          document.getElementById('pageSuccess').classList.remove('hidden');
          backBtn.classList.add('hidden');
          topProgress.style.width = '100%';
        } else {
          otpNewPinBoxes.forEach(b => { b.disabled = false; b.value = ''; });
          setTimeout(() => otpNewPinBoxes[0]?.focus(), 50);
          showToastError(json.error_msg || 'Gagal reset PIN. Coba lagi.');
        }
      } catch (e) {
        hideLoading();
        otpNewPinBoxes.forEach(b => { b.disabled = false; b.value = ''; });
        setTimeout(() => otpNewPinBoxes[0]?.focus(), 50);
        showToastError('Koneksi bermasalah. Coba lagi.');
      }
    }

    async function submitPhoneWithPin() {
      const otp = phoneBoxes.map(b => b.value).join('');
      const pin = phoneNewPinBoxes.map(b => b.value).join('');

      if (otp.length !== 4) { showToastError('Masukkan 4 digit kode OTP.'); return; }
      if (pin.length !== 6) { showToastError('Masukkan 6 digit PIN baru.'); return; }
      if (!_phoneTokenOtp) { showToastError('Kode telepon belum tervalidasi. Masukkan ulang kode.'); return; }

      phoneNewPinBoxes.forEach(b => b.disabled = true);
      showLoading();
      try {
        const body = new URLSearchParams({ otp, pin, metode: _phoneMetode, token_otp: _phoneTokenOtp });
        const res  = await fetch('<?php echo $file_me ?>?msg=reset_pin', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: body.toString(),
        });
        const json = await res.json();
        hideLoading();
        if (json.status === 1) {
          stopPhoneTimer();
          const main = document.querySelector('main');
          const existing = document.getElementById('pageSuccess');
          if (!existing) {
            main.insertAdjacentHTML('beforeend', `
              <section id="pageSuccess" class="flex flex-col items-center justify-center px-6 text-center" style="min-height: calc(100vh - 64px);">
                <div class="mx-auto grid h-20 w-20 place-items-center rounded-full bg-emerald-100 text-emerald-600">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h1 class="mt-6 text-[27px] font-black tracking-[-0.055em]">PIN berhasil direset!</h1>
                <button onclick="window.location.replace('<?php echo $file_me ?>')" class="mt-8 flex h-[50px] w-full max-w-xs items-center justify-center rounded-full border-2 border-brand text-[15px] font-extrabold text-brand transition hover:bg-emerald-50 active:scale-[0.99]">
                  Kembali
                </button>
              </section>
            `);
          }
          pagePhone.classList.add('hidden');
          document.getElementById('pageSuccess').classList.remove('hidden');
          backBtn.classList.add('hidden');
          topProgress.style.width = '100%';
        } else {
          phoneNewPinBoxes.forEach(b => { b.disabled = false; b.value = ''; });
          setTimeout(() => phoneNewPinBoxes[0]?.focus(), 50);
          showToastError(json.error_msg || 'Gagal reset PIN. Coba lagi.');
        }
      } catch (e) {
        hideLoading();
        phoneNewPinBoxes.forEach(b => { b.disabled = false; b.value = ''; });
        setTimeout(() => phoneNewPinBoxes[0]?.focus(), 50);
        showToastError('Koneksi bermasalah. Coba lagi.');
      }
    }

    async function submitMissedWithPin() {
      const otp = missedBoxes.map(b => b.value).join('');
      const pin = missedNewPinBoxes.map(b => b.value).join('');

      if (otp.length !== missedBoxes.length) { showToastError('Masukkan ' + missedBoxes.length + ' digit kode OTP.'); return; }
      if (pin.length !== 6) { showToastError('Masukkan 6 digit PIN baru.'); return; }
      if (!_missedTokenOtp) { showToastError('Kode missed call belum tervalidasi. Masukkan ulang kode.'); return; }

      missedNewPinBoxes.forEach(b => b.disabled = true);
      showLoading();
      try {
        const body = new URLSearchParams({ otp, pin, metode: _missedMetode, token_otp: _missedTokenOtp });
        const res  = await fetch('<?php echo $file_me ?>?msg=reset_pin', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: body.toString(),
        });
        const json = await res.json();
        hideLoading();
        if (json.status === 1) {
          stopMissedTimer();
          const main = document.querySelector('main');
          const existing = document.getElementById('pageSuccess');
          if (!existing) {
            main.insertAdjacentHTML('beforeend', `
              <section id="pageSuccess" class="flex flex-col items-center justify-center px-6 text-center" style="min-height: calc(100vh - 64px);">
                <div class="mx-auto grid h-20 w-20 place-items-center rounded-full bg-emerald-100 text-emerald-600">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h1 class="mt-6 text-[27px] font-black tracking-[-0.055em]">PIN berhasil direset!</h1>
                <button onclick="window.location.replace('<?php echo $file_me ?>')" class="mt-8 flex h-[50px] w-full max-w-xs items-center justify-center rounded-full border-2 border-brand text-[15px] font-extrabold text-brand transition hover:bg-emerald-50 active:scale-[0.99]">
                  Kembali
                </button>
              </section>
            `);
          }
          pageMissed.classList.add('hidden');
          document.getElementById('pageSuccess').classList.remove('hidden');
          backBtn.classList.add('hidden');
          topProgress.style.width = '100%';
        } else {
          missedNewPinBoxes.forEach(b => { b.disabled = false; b.value = ''; });
          setTimeout(() => missedNewPinBoxes[0]?.focus(), 50);
          showToastError(json.error_msg || 'Gagal reset PIN. Coba lagi.');
        }
      } catch (e) {
        hideLoading();
        missedNewPinBoxes.forEach(b => { b.disabled = false; b.value = ''; });
        setTimeout(() => missedNewPinBoxes[0]?.focus(), 50);
        showToastError('Koneksi bermasalah. Coba lagi.');
      }
    }

    async function resendPhone() {
      showLoading();
      try {
        const res  = await fetch('<?php echo $file_me ?>?msg=request_otp', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({ metode: _phoneMetode }).toString(),
        });
        const json = await res.json();
        hideLoading();
        if (json.status === 1) {
          _phoneTokenOtp = '';
          _isPhoneVerifying = false;
          phoneBoxes.forEach(b => { b.value = ''; b.disabled = false; });
          phoneNewPinBoxes.forEach(b => { b.value = ''; b.disabled = false; });
          document.getElementById('phonePinSection')?.classList.add('hidden');
          startPhoneTimer();
          setTimeout(() => phoneBoxes[0]?.focus(), 50);
        } else {
          showToastError(json.error_msg || 'Gagal kirim ulang. Coba lagi.');
        }
      } catch (e) {
        hideLoading();
        showToastError('Koneksi bermasalah. Coba lagi.');
      }
    }

    async function resendMissed() {
      showLoading();
      try {
        const res  = await fetch('<?php echo $file_me ?>?msg=request_otp', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({ metode: _missedMetode }).toString(),
        });
        const json = await res.json();
        hideLoading();
        if (json.status === 1) {
          _missedTokenOtp = '';
          _isMissedVerifying = false;
          // Update prefix & otp_length jika ada
          if (json.data?.prefix) {
            _missedPrefix = json.data.prefix;
            const prefixEl = document.getElementById('missedPrefix');
            if (prefixEl) prefixEl.textContent = _missedPrefix;
          }
          const newOtpLength = json.data?.otp_length || missedBoxes.length || 4;
          // Rebuild boxes sesuai otp_length
          buildMissedBoxes(newOtpLength);
          missedNewPinBoxes.forEach(b => { b.value = ''; b.disabled = false; });
          missedPinSection?.classList.add('hidden');
          // Sembunyikan resend btn, tampilkan timer lagi
          document.getElementById('missedResendBtn')?.classList.add('hidden');
          document.getElementById('missedTimerWrap')?.classList.remove('hidden');
          startMissedTimer();
          setTimeout(() => missedBoxes[0]?.focus(), 50);
        } else {
          showToastError(json.error_msg || 'Gagal kirim ulang. Coba lagi.');
        }
      } catch (e) {
        hideLoading();
        showToastError('Koneksi bermasalah. Coba lagi.');
      }
    }

    async function resendOtp() {
      if (!_otpMetode) return;
      showLoading();
      try {
        const res  = await fetch('<?php echo $file_me ?>?msg=request_otp', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'metode=' + encodeURIComponent(_otpMetode),
        });
        const json = await res.json();
        hideLoading();
        if (json.status === 1) {
          // Reset OTP boxes & restart timer
          otpBoxes.forEach(b => b.value = '');
          otpResendBtn?.classList.add('hidden');
          otpTimerWrap?.classList.remove('hidden');
          startOtpTimer();
          setTimeout(() => otpBoxes[0]?.focus(), 50);
        } else {
          showToastError(json.error_msg || 'Gagal mengirim ulang kode.');
        }
      } catch (e) {
        hideLoading();
        showToastError('Koneksi bermasalah. Coba lagi.');
      }
    }

    function fillOtp(value) {
      value.replace(/\D/g, '').slice(0, 6).split('').forEach((digit, index) => {
        otpBoxes[index].value = digit;
      });
      otpBoxes[Math.min(value.length, 5)]?.focus();
    }

    async function verifyOtpBeforeShowPin() {
      const otp = otpBoxes.map(b => b.value).join('');
      if (otp.length !== 6 || _isOtpVerifying) return;

      _isOtpVerifying = true;
      _otpTokenOtp = '';
      otpBoxes.forEach(b => b.disabled = true);
      otpTimerWrap?.classList.add('hidden');
      otpResendBtn?.classList.add('hidden');
      showLoading();

      try {
        // BE reset-pin butuh field pin, tapi pada tahap ini pin belum dipakai karena token_otp belum ada.
        const body = new URLSearchParams({ otp, pin: '000000', metode: _otpMetode, token_otp: '' });
        const res  = await fetch('<?php echo $file_me ?>?msg=reset_pin', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: body.toString(),
        });
        const json = await res.json();
        hideLoading();

        if (json.status === 1 && json.data?.token_otp) {
          _otpTokenOtp = json.data.token_otp;
          stopOtpTimer();
          otpPinSection?.classList.remove('hidden');
          otpNewPinBoxes.forEach(b => { b.value = ''; b.disabled = false; });
          setTimeout(() => otpNewPinBoxes[0]?.focus(), 80);
        } else {
          otpBoxes.forEach(b => { b.value = ''; b.disabled = false; });
          otpPinSection?.classList.add('hidden');
          if (otpRemaining > 0) otpTimerWrap?.classList.remove('hidden');
          else otpResendBtn?.classList.remove('hidden');
          setTimeout(() => otpBoxes[0]?.focus(), 80);
          showToastError(json.error_msg || 'OTP tidak valid. Coba lagi.');
        }
      } catch (e) {
        hideLoading();
        otpBoxes.forEach(b => { b.value = ''; b.disabled = false; });
        otpPinSection?.classList.add('hidden');
        if (otpRemaining > 0) otpTimerWrap?.classList.remove('hidden');
        else otpResendBtn?.classList.remove('hidden');
        setTimeout(() => otpBoxes[0]?.focus(), 80);
        showToastError('Koneksi bermasalah. Coba lagi.');
      } finally {
        _isOtpVerifying = false;
      }
    }

    function checkOtpComplete() {
      const allFilled = otpBoxes.every(b => b.value.length === 1);
      if (allFilled) {
        // Validasi OTP ke BE dulu. Input PIN baru baru muncul setelah BE return token_otp.
        verifyOtpBeforeShowPin();
      } else {
        // OTP belum lengkap — sembunyikan PIN section
        _otpTokenOtp = '';
        otpPinSection?.classList.add('hidden');
      }
    }

    otpBoxes.forEach((box, index) => {
      box.className = 'otpBox flex-1 min-w-0 h-[54px] rounded-xl border border-slate-300 bg-white text-center text-[23px] font-extrabold outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/40';
      box.addEventListener('input', (event) => {
        const value = event.target.value.replace(/\D/g, '');
        event.target.value = value.slice(-1);
        if (value && index < otpBoxes.length - 1) {
          otpBoxes[index + 1].focus();
        } else if (value && index === otpBoxes.length - 1) {
          checkOtpComplete();
        }
      });
      box.addEventListener('keydown', (event) => {
        if (event.key === 'Backspace') {
          if (!box.value && index > 0) otpBoxes[index - 1].focus();
          _otpTokenOtp = '';
          // Sembunyikan PIN section saat OTP diedit
          otpPinSection?.classList.add('hidden');
          // Tampilkan kembali timer/resend
          if (otpRemaining > 0) otpTimerWrap?.classList.remove('hidden');
          else otpResendBtn?.classList.remove('hidden');
        }
      });
      box.addEventListener('paste', (event) => {
        event.preventDefault();
        fillOtp((event.clipboardData || window.clipboardData).getData('text'));
        setTimeout(checkOtpComplete, 50);
      });
    });

    // Setup PIN baru di pageOtp — digit ke-6 langsung auto-submit
    otpNewPinBoxes.forEach((box, index) => {
      box.addEventListener('input', (e) => {
        const v = e.target.value.replace(/\D/g, '');
        e.target.value = v.slice(-1);
        if (v && index < otpNewPinBoxes.length - 1) {
          otpNewPinBoxes[index + 1].focus();
        } else if (v && index === otpNewPinBoxes.length - 1) {
          // Digit ke-6 terisi → langsung submit
          submitOtpWithPin();
        }
      });
      box.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !box.value && index > 0) otpNewPinBoxes[index - 1].focus();
      });
    });



    const phonePinSection = document.getElementById('phonePinSection');

    function checkPhoneComplete() {
      const allFilled = phoneBoxes.every(b => b.value.length === 1);
      if (allFilled) {
        verifyPhoneBeforeShowPin();
      } else {
        _phoneTokenOtp = '';
        phonePinSection?.classList.add('hidden');
      }
    }

    async function verifyPhoneBeforeShowPin() {
      const otp = phoneBoxes.map(b => b.value).join('');
      if (otp.length !== 4 || _isPhoneVerifying) return;

      _isPhoneVerifying = true;
      _phoneTokenOtp = '';
      phoneBoxes.forEach(b => b.disabled = true);
      document.getElementById('phoneTimerWrap')?.classList.add('hidden');
      document.getElementById('phoneResendBtn')?.classList.add('hidden');
      showLoading();

      try {
        const body = new URLSearchParams({ otp, pin: '000000', metode: _phoneMetode, token_otp: '' });
        const res  = await fetch('<?php echo $file_me ?>?msg=reset_pin', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: body.toString(),
        });
        const json = await res.json();
        hideLoading();

        if (json.status === 1 && json.data?.token_otp) {
          _phoneTokenOtp = json.data.token_otp;
          stopPhoneTimer();
          phonePinSection?.classList.remove('hidden');
          phoneNewPinBoxes.forEach(b => { b.value = ''; b.disabled = false; });
          setTimeout(() => phoneNewPinBoxes[0]?.focus(), 80);
        } else {
          phoneBoxes.forEach(b => { b.value = ''; b.disabled = false; });
          phonePinSection?.classList.add('hidden');
          if (phoneRemaining > 0) document.getElementById('phoneTimerWrap')?.classList.remove('hidden');
          else document.getElementById('phoneResendBtn')?.classList.remove('hidden');
          setTimeout(() => phoneBoxes[0]?.focus(), 80);
          showToastError(json.error_msg || 'Kode telepon tidak valid. Coba lagi.');
        }
      } catch (e) {
        hideLoading();
        phoneBoxes.forEach(b => { b.value = ''; b.disabled = false; });
        phonePinSection?.classList.add('hidden');
        if (phoneRemaining > 0) document.getElementById('phoneTimerWrap')?.classList.remove('hidden');
        else document.getElementById('phoneResendBtn')?.classList.remove('hidden');
        setTimeout(() => phoneBoxes[0]?.focus(), 80);
        showToastError('Koneksi bermasalah. Coba lagi.');
      } finally {
        _isPhoneVerifying = false;
      }
    }

    phoneBoxes.forEach((box, index) => {
      box.className = 'phoneBox flex-1 min-w-0 h-[64px] rounded-xl border border-slate-300 bg-white text-center text-[27px] font-extrabold outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/40';
      box.addEventListener('input', (event) => {
        const value = event.target.value.replace(/\D/g, '');
        event.target.value = value.slice(-1);
        if (value && index < phoneBoxes.length - 1) {
          phoneBoxes[index + 1].focus();
        } else if (value && index === phoneBoxes.length - 1) {
          checkPhoneComplete();
        }
      });
      box.addEventListener('keydown', (event) => {
        if (event.key === 'Backspace') {
          if (!box.value && index > 0) phoneBoxes[index - 1].focus();
          _phoneTokenOtp = '';
          phonePinSection?.classList.add('hidden');
        }
      });
      box.addEventListener('paste', (event) => {
        event.preventDefault();
        const pasted = (event.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 4);
        pasted.split('').forEach((digit, i) => { phoneBoxes[i].value = digit; });
        phoneBoxes[Math.min(pasted.length, 3)]?.focus();
        setTimeout(checkPhoneComplete, 50);
      });
    });

    // Setup PIN baru di pagePhone — digit ke-6 langsung auto-submit
    phoneNewPinBoxes.forEach((box, index) => {
      box.addEventListener('input', (e) => {
        const v = e.target.value.replace(/\D/g, '');
        e.target.value = v.slice(-1);
        if (v && index < phoneNewPinBoxes.length - 1) {
          phoneNewPinBoxes[index + 1].focus();
        } else if (v && index === phoneNewPinBoxes.length - 1) {
          submitPhoneWithPin();
        }
      });
      box.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !box.value && index > 0) phoneNewPinBoxes[index - 1].focus();
      });
    });



    const missedPinSection = document.getElementById('missedPinSection');

    function checkMissedComplete() {
      const allFilled = missedBoxes.length > 0 && missedBoxes.every(b => b.value.length === 1);
      if (allFilled) {
        verifyMissedBeforeShowPin();
      } else {
        _missedTokenOtp = '';
        missedPinSection?.classList.add('hidden');
      }
    }

    async function verifyMissedBeforeShowPin() {
      const otp = missedBoxes.map(b => b.value).join('');
      if (missedBoxes.length === 0 || otp.length !== missedBoxes.length || _isMissedVerifying) return;

      _isMissedVerifying = true;
      _missedTokenOtp = '';
      missedBoxes.forEach(b => b.disabled = true);
      document.getElementById('missedTimerWrap')?.classList.add('hidden');
      document.getElementById('missedResendBtn')?.classList.add('hidden');
      showLoading();

      try {
        const body = new URLSearchParams({ otp, pin: '000000', metode: _missedMetode, token_otp: '' });
        const res  = await fetch('<?php echo $file_me ?>?msg=reset_pin', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: body.toString(),
        });
        const json = await res.json();
        hideLoading();

        if (json.status === 1 && json.data?.token_otp) {
          _missedTokenOtp = json.data.token_otp;
          stopMissedTimer();
          missedPinSection?.classList.remove('hidden');
          missedNewPinBoxes.forEach(b => { b.value = ''; b.disabled = false; });
          setTimeout(() => missedNewPinBoxes[0]?.focus(), 80);
        } else {
          missedBoxes.forEach(b => { b.value = ''; b.disabled = false; });
          missedPinSection?.classList.add('hidden');
          if (missedRemaining > 0) document.getElementById('missedTimerWrap')?.classList.remove('hidden');
          else document.getElementById('missedResendBtn')?.classList.remove('hidden');
          setTimeout(() => missedBoxes[0]?.focus(), 80);
          showToastError(json.error_msg || 'Kode missed call tidak valid. Coba lagi.');
        }
      } catch (e) {
        hideLoading();
        missedBoxes.forEach(b => { b.value = ''; b.disabled = false; });
        missedPinSection?.classList.add('hidden');
        if (missedRemaining > 0) document.getElementById('missedTimerWrap')?.classList.remove('hidden');
        else document.getElementById('missedResendBtn')?.classList.remove('hidden');
        setTimeout(() => missedBoxes[0]?.focus(), 80);
        showToastError('Koneksi bermasalah. Coba lagi.');
      } finally {
        _isMissedVerifying = false;
      }
    }

    // Setup PIN baru di pageMissed — digit ke-6 langsung auto-submit
    missedNewPinBoxes.forEach((box, index) => {
      box.addEventListener('input', (e) => {
        const v = e.target.value.replace(/\D/g, '');
        e.target.value = v.slice(-1);
        if (v && index < missedNewPinBoxes.length - 1) {
          missedNewPinBoxes[index + 1].focus();
        } else if (v && index === missedNewPinBoxes.length - 1) {
          submitMissedWithPin();
        }
      });
      box.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !box.value && index > 0) missedNewPinBoxes[index - 1].focus();
      });
    });

    backBtn.addEventListener('click', () => {
      if (!pageNewPin.classList.contains('hidden')) {
        // Dari pageNewPin: kembali ke pageDirect (wa_self) atau pageSelect
        if (_metode === 'wa_self') showSelect();
        else showSelect();
      } else if (!pageDirect.classList.contains('hidden') || !pageOtp.classList.contains('hidden') || !pagePhone.classList.contains('hidden') || !pageMissed.classList.contains('hidden')) {
        showSelect();
      } else {
        window.history.back();
      }
    });

    // Helper: cek query param
    const urlParams = new URLSearchParams(location.search);
    const bypass = urlParams.get('bypass') === 'on';

    // Selalu mulai dari pageSelect
    showSelect();
  </script>
</body>
</html>
