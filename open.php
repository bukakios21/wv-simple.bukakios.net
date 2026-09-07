<?PHP
require_once ("config.php");
if(isset($_GET['url'])){
    $url = $_GET['url'];
    $open = "$open_url$url";
}else{
    exit;
}
?>
<meta http-equiv="refresh" content="3;url=<?PHP echo $open; ?>" />
Mohon tunggu sebentar...