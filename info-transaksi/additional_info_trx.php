<?PHP
$show_additional = false;

if (!isset($api_v2)) {
    require_once "../lib/ApiV2.php";
    $api_v2 = new ApiV2($user_jwt);
}
$data_additional = $api_v2->transaksi_additional_info($id);
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
            <button type="button" onclick="copyToClipboard('sn_catatan')" class="mt-8 inline-flex items-center justify-center gap-1.5 rounded-xl bg-brand px-5 py-2 text-[13px] font-bold text-white shadow-md transition hover:bg-brandDark active:scale-95">
                <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                <?PHP echo htmlspecialchars($ax_text_salin, ENT_QUOTES, 'UTF-8'); ?>
            </button></div>
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