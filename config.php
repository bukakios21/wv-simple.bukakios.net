<?php
/**
 * BukaKios WebView Simple - Configuration
 * Loads environment variables from .env file
 */

// Start session if not already started (idempotent)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ---------------------------------------------------------------------------
// ENV FILE PARSER
// ---------------------------------------------------------------------------
$env_file = __DIR__ . '/.env';
if (file_exists($env_file)) {
    $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        if (strpos($line, '=') === false) continue;
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        // Remove surrounding quotes if present
        if (preg_match('/^["\'](.*)["\']\s*$/', $value, $m)) {
            $value = $m[1];
        }
        $_ENV[$key] = $value;
        putenv("$key=$value");
    }
}

// ---------------------------------------------------------------------------
// PATH CONSTANTS
// ---------------------------------------------------------------------------
define('ROOT', dirname(__FILE__));
define('APP_URL', rtrim(getenv('APP_URL') ?: 'https://wv-simple.bukakios.net', '/'));
define('TEMPLATE_DIR', ROOT);
define('TEMPLATE_URL', APP_URL);

// ---------------------------------------------------------------------------
// URL alias untuk backward compatibility (digunakan di banyak template)
// ---------------------------------------------------------------------------
$c_url = APP_URL;

// ---------------------------------------------------------------------------
// APP INSTANCE (global)
// ---------------------------------------------------------------------------
require_once ROOT . '/core/app.class.php';
$app = new App();

// ---------------------------------------------------------------------------
// API v1
// ---------------------------------------------------------------------------
$api_key = getenv('API_KEY') ?: '';
$api_url = rtrim(getenv('API_URL') ?: 'https://api.bukakios.net', '/');

// ---------------------------------------------------------------------------
// WhatsApp CS
// ---------------------------------------------------------------------------
$wa_number = getenv('WA_NUMBER') ?: '6282184284119';

// ---------------------------------------------------------------------------
// Brand Colors
// ---------------------------------------------------------------------------
$primary   = getenv('PRIMARY_COLOR') ?: '#E53935';
$secondary = getenv('SECONDARY_COLOR') ?: '#1976D2';

// ---------------------------------------------------------------------------
// Open URL scheme (for webview app links)
// ---------------------------------------------------------------------------
$openurl  = 'open://';
$open_url = 'open://';

// ---------------------------------------------------------------------------
// Firebase Cloud Messaging
// ---------------------------------------------------------------------------
define('_FCM_KEY', getenv('FCM_KEY') ?: '');

// ---------------------------------------------------------------------------
// Redis stubs (no direct Redis connection in wv-simple — stub for compatibility)
// Remove these stubs if a file that used Redis is no longer needed
// ---------------------------------------------------------------------------
class FakeRedis {
    public function connect($h, $p) {}
    public function auth($p) {}
    public function exists($k) { return false; }
    public function get($k) { return null; }
    public function setex($k, $t, $v) {}
    public function del($k) {}
}
$rediw = new FakeRedis();
$redis  = new FakeRedis();
$redip  = new FakeRedis();
$redis_con = "";

// ---------------------------------------------------------------------------
// (DB config removed — no direct DB connection in wv-simple)
// ---------------------------------------------------------------------------
