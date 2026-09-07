<?PHP
$show_additional = false;

if (!function_exists('getAdditionalTrx')) {
    function getAdditionalTrx($id_trx, $token_jwt)
    {
        $url = "https://api-v2.bukakios.net/wv-x7Up2p/transaksi/additional-info/" . $id_trx;

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: $token_jwt",
                "Api-key: PLowElenThErTeRAphaRDwINEAntrIDe",
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}

$data_additional = getAdditionalTrx($id, $user_jwt);
$data_additional = json_decode($data_additional, true);
if (
    isset($data_additional['status']) &&
    $data_additional['status'] == 1 &&
    isset($data_additional['data']['show_additional']) &&
    $data_additional['data']['show_additional'] &&
    isset($data_additional['data']['data'])
) {
    $axme = $data_additional['data']['data'];
    $show_additional = true;
    $ax_text_info_salin = isset($axme['ax_text_info_salin']) ? $axme['ax_text_info_salin'] : '';
    $ax_text_salin = isset($axme['ax_text_salin']) ? $axme['ax_text_salin'] : '';
    $ax_info = isset($axme['ax_info']) ? $axme['ax_info'] : '';
    $ax_sn = isset($axme['ax_sn']) ? $axme['ax_sn'] : '';
    $ax_alert = isset($axme['ax_alert']) ? $axme['ax_alert'] : 'info';
    $ax_additional = isset($axme['ax_additional']) ? $axme['ax_additional'] : '';

    $allowed_alerts = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'];
    if (!in_array($ax_alert, $allowed_alerts)) {
        $ax_alert = 'info';
    }
}
?>
<?PHP if($show_additional){ ?>
    <div class="alert alert-<?PHP echo htmlspecialchars($ax_alert, ENT_QUOTES, 'UTF-8'); ?>" style="padding:20px;">
        <div style="text-align:center;"><h3><?PHP echo htmlspecialchars($ax_info, ENT_QUOTES, 'UTF-8'); ?> :</h3>
            <h3
                    style="margin-bottom:-20px;margin-top:10px;font-size:20px"
                    id="sn_catatan"
                    data-text="<?PHP echo htmlspecialchars($ax_text_info_salin, ENT_QUOTES, 'UTF-8'); ?>"
                    data-copy="<?PHP echo htmlspecialchars($ax_sn, ENT_QUOTES, 'UTF-8'); ?>">
                <?PHP echo nl2br(htmlspecialchars($ax_sn, ENT_QUOTES, 'UTF-8')); ?>
            </h3>
            <br>
            <span style="text-decoration:underline;color:#00bfff;cursor:pointer"
                  onclick="copyToClipboard('sn_catatan')">
                <?PHP echo htmlspecialchars($ax_text_salin, ENT_QUOTES, 'UTF-8'); ?>
            </span></div>
        <?PHP echo $ax_additional; ?>
    </div> <?PHP } ?>

<?PHP
if($status>1){
    //refund
    $jumlah_refund = $app->idr($price_client);;
    echo "<div class='alert alert-info'>
	<b>Harap Baca!!!</b><br>
	Di karenakan transaksi ini gagal, dana sebesar <b>$jumlah_refund</b> sudah kami kembalikan ke saldo akun kamu :) , jika ada yang ingin di tanyakan silahkan <a href='kontak.html'>kontak kami</a>
	</div>";
}
?>