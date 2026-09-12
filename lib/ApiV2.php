<?php

class ApiV2
{

    private $secret_key;
    private $api_url;
    private $api_key;
    private $jwt;
    private $api_url_wv;

    function __construct($jwt = "")
    {
        $this->api_url     = rtrim(getenv('API_V2_URL') ?: 'https://api-v2.bukakios.net/v2', '/');
        $this->api_url_wv = rtrim(getenv('API_V2_WV_URL') ?: 'https://api-v2.bukakios.net/wv-x7Up2p', '/');
        $this->secret_key  = getenv('API_V2_SECRET') ?: '';
        $this->api_key     = getenv('API_V2_KEY') ?: '';
        $this->jwt         = $jwt;
    }

    function gen_token_expired()
    {
        $key = $this->secret_key;
        $start = time();
        $end = $start + (1 * 60); // 1 menit
        $sign = md5("$start:$end:$key");
        $token = "$start:$end:$sign";
        return $token;
    }

    function curl_post($path, $body)
    {
        $bodyJson = json_encode($body);
        $sign = md5($this->secret_key . ":" . $bodyJson);
        $token = $this->gen_token_expired();
        $api_key = $this->api_key;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $bodyJson,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Signature: " . $token,
                "Token: $token",
                "Api-Key: $api_key",
                "Authorization: " . $this->jwt,
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    private function grab_data($path)
    {
        $api_key = $this->api_key;
        $token = $this->gen_token_expired();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Signature: $token",
                "Token: $token",
                "Api-Key: $api_key",
                "Authorization: " . $this->jwt,
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    function curl_post_url($url, $body)
    {
        $token = $this->gen_token_expired();
        $bodyJson = json_encode($body);
        $api_key = $this->api_key;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $bodyJson,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Signature: " . $token,
                "Token: $token",
                "Api-Key: $api_key",
                "Authorization: " . $this->jwt,
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    function curl_get_url($url)
    {
        // Public wrapper untuk GET request ke WV API. Body-nya identik dengan
        // grab_data_url (private) supaya function ini bisa dipanggil dari mana
        // saja tanpa ngubah signature caller existing.
        return $this->grab_data_url($url);
    }

    // curl_request_url: wrapper generik untuk method non-POST (PUT/DELETE) ke WV
    // API. Header/signature identik dengan curl_post_url. Body dikirim sebagai
    // JSON di request body (termasuk untuk DELETE, sesuai kebutuhan controller
    // NomorPelanggan di apiv2).
    function curl_request_url($method, $url, $body)
    {
        $token = $this->gen_token_expired();
        $bodyJson = json_encode($body);
        $api_key = $this->api_key;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_POSTFIELDS => $bodyJson,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Signature: " . $token,
                "Token: $token",
                "Api-Key: $api_key",
                "Authorization: " . $this->jwt,
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    private function grab_data_url($url)
    {
        $api_key = $this->api_key;
        $token = $this->gen_token_expired();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Signature: $token",
                "Token: $token",
                "Api-Key: $api_key",
                "Authorization: " . $this->jwt,
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    function send_otp_bukakios($body)
    {
        return $this->curl_post("/send/wa/otp/bukakios", $body);
    }

    function request_otp_sms($body)
    {
        return $this->request_otp_sms_reset_pin($body);
    }

    function request_otp_wa($body){
        return $this->request_otp_wa_reset_pin($body);
    }

    function request_otp_sms_reset_pin($body)
    {
        return $this->curl_post("/otp/sms/reset/pin", $body);
    }

    function request_otp_wa_reset_pin($body)
    {
        return $this->curl_post("/otp/wa/reset/pin", $body);
    }

    function request_otp_email_reset_pin($body){
        return $this->curl_post("/otp/request/email/reset/pin", $body);
    }

    function proses_otp_reset_pin($body)
    {
        return $this->curl_post("/otp/reset/pin/proses", $body);
    }

    function verif_hp_user($body)
    {
        return $this->curl_post("/otp/verif/hp/user", $body);
    }

    function detail_user()
    {
        return $this->grab_data("/me");
    }

    function tukar_poin_list()
    {
        return $this->grab_data("/poin/list");
    }

    function tukar_poin_trx($body)
    {
        return $this->curl_post("/poin/trx", $body);
    }

    function request_verif_email(){
        return $this->grab_data("/verif/email/request");
    }

    function penarikan_komisi(){
        $url =  $this->api_url_wv."/tarik-komisi";
        return $this->grab_data_url($url);
    }

    // get_komisi: GET /user/komisi. Total komisi (my_komisi) milik user.
    // Response: {status, data:{komisi}} / {status, error_msg}.
    function get_komisi(){
        $url = $this->api_url_wv . "/user/komisi";
        return $this->grab_data_url($url);
    }

    // list_downline_user: POST /downline-user/list. Daftar referral/downline
    // milik user. Paginasi cursor: kirim last_id (0 untuk halaman pertama),
    // BE balikin data + last_id (id terkecil batch) untuk request berikutnya.
    // Response: {status, data:{data:[...], last_id}} / {status, error_msg}.
    function list_downline_user($limit = 30, $last_id = 0){
        $body = array(
            "limit"   => (int)$limit,
            "last_id" => (int)$last_id,
        );
        $url = $this->api_url_wv . "/downline-user/list";
        return $this->curl_post_url($url, $body);
    }

    // list_downline_komisi: POST /downline-user/komisi. Riwayat komisi dari
    // transaksi referral. Paginasi cursor sama seperti list_downline_user.
    // Response: {status, data:{data:[...], last_id}} / {status, error_msg}.
    function list_downline_komisi($limit = 30, $last_id = 0){
        $body = array(
            "limit"   => (int)$limit,
            "last_id" => (int)$last_id,
        );
        $url = $this->api_url_wv . "/downline-user/komisi";
        return $this->curl_post_url($url, $body);
    }

    function update_price_sell($data){
        $url =  $this->api_url_wv."/update-price-sell";
        return $this->curl_post_url($url, $data);
    }

    function delete_cache_list_topup(){
        $url =  $this->api_url_wv."/delete/cache-list-topup";
        return $this->grab_data_url($url);
    }

    function get_poin_by_tanggal($body)
    {
        $url =  $this->api_url_wv."/poin/get-poin-by-tanggal";
        return $this->curl_post_url($url, $body);
    }

    function list_product_pasca($operator_id)
    {
        $body = array('operator_id' => (int)$operator_id);
        $url  = $this->api_url_wv . "/pasca/product-list";
        return $this->curl_post_url($url, $body);
    }

    function inq_pasca($kode_produk, $nomor)
    {
        $body = array(
            'kode_produk' => $kode_produk,
            'nomor'       => $nomor,
        );
        $url = $this->api_url_wv . "/pasca/inq";
        return $this->curl_post_url($url, $body);
    }

    function pay_pasca($trx_id, $biaya_toko, $tanggal = '')
    {
        $body = array(
            'trx_id'      => $trx_id,
            'biaya_toko'  => $biaya_toko,
            'tanggal'     => $tanggal,
        );
        $url = $this->api_url_wv . "/pasca/pay";
        return $this->curl_post_url($url, $body);
    }

    function detail_produk_by_code($code)
    {
        $body = array('code' => $code);
        $url  = $this->api_url_wv . "/produk/detail";
        return $this->curl_post_url($url, $body);
    }

    function topup_cancel($id){
        $body = array(
            "id" => $id
        );
        $url =  $this->api_url_wv."/topup/cancel";
        return $this->curl_post_url($url, $body);
    }

    function topup_detail($id){
        $body = array(
            "id" => $id
        );
        $url =  $this->api_url_wv."/topup/detail-new/".$id;
        return $this->curl_post_url($url, $body);
    }

    // topup_payment: GET /topup/payment/:id
    // Generate/refresh instruksi pembayaran Tokopay (qr/link/va) atau return
    // info rekening bank manual. Tidak ada body — id ada di URL path.
    function topup_payment($id){
        $url =  $this->api_url_wv."/topup/payment/".$id;
        return $this->curl_get_url($url);
    }

    // transaksi_detail: GET /transaksi/:trx_id (WV route, JWT + Api-Key).
    // Detail transaksi PPOB untuk halaman info-transaksi. Id ada di URL path.
    function transaksi_detail($id){
        $url =  $this->api_url_wv."/transaksi/".$id;
        return $this->curl_get_url($url);
    }

    // transaksi_additional_info: GET /transaksi/additional-info/:id.
    // Info tambahan struk (mis. token PLN) yang tampil saat status transaksi > 0.
    function transaksi_additional_info($id){
        $url =  $this->api_url_wv."/transaksi/additional-info/".$id;
        return $this->curl_get_url($url);
    }

    function getLevelUser(){
        $url =  $this->api_url_wv."/user/get-level";
        return $this->grab_data_url($url);
    }

    function update_referral($kode){
        $body = array(
            "kode" => $kode
        );
        $url =  $this->api_url_wv."/user/update-kode-referral";
        return $this->curl_post_url($url, $body);
    }

    // redem_voucher: POST /voucher/redem (WV route, JWT + Api-Key).
    // Migrasi dari MS0 v1/proses_voucher.php. Kode dikirim plaintext; uid
    // diambil BE dari JWT. Response: {status, message} / {status, error_msg}.
    function redem_voucher($kode){
        $body = array(
            "kode" => $kode
        );
        $url =  $this->api_url_wv."/voucher/redem";
        return $this->curl_post_url($url, $body);
    }

    /************ NOMOR PELANGGAN / KONTAK FAVORIT ************/
    // Reuse controller co_public.NomorPelanggan di apiv2 via group WV.
    // uid diambil BE dari JWT. Semua response: {status, ...} / {status, error_msg}.

    // list_nomor_pelanggan: POST /nomor/pelanggan/list. Ambil daftar kontak
    // favorit milik user (paginasi last_id + pencarian). Struktur data list
    // ada di response.data.data.data (lihat handler apiv2).
    function list_nomor_pelanggan($cari = '', $limit = 300, $last_id = 0)
    {
        $body = array(
            "cari"    => (string)$cari,
            "limit"   => (int)$limit,
            "last_id" => (int)$last_id,
        );
        $url = $this->api_url_wv . "/nomor/pelanggan/list";
        return $this->curl_post_url($url, $body);
    }

    // simpan_nomor_pelanggan: POST /nomor/pelanggan (act=add).
    function simpan_nomor_pelanggan($nama, $hp)
    {
        $body = array(
            "act"  => "add",
            "nama" => $nama,
            "hp"   => $hp,
        );
        $url = $this->api_url_wv . "/nomor/pelanggan";
        return $this->curl_post_url($url, $body);
    }

    // update_nomor_pelanggan: PUT /nomor/pelanggan (act=update).
    function update_nomor_pelanggan($id, $nama, $hp)
    {
        $body = array(
            "act"  => "update",
            "id"   => (int)$id,
            "nama" => $nama,
            "hp"   => $hp,
        );
        $url = $this->api_url_wv . "/nomor/pelanggan";
        return $this->curl_request_url("PUT", $url, $body);
    }

    // hapus_nomor_pelanggan: DELETE /nomor/pelanggan (act=delete).
    function hapus_nomor_pelanggan($hp)
    {
        $body = array(
            "act" => "delete",
            "hp"  => $hp,
        );
        $url = $this->api_url_wv . "/nomor/pelanggan";
        return $this->curl_request_url("DELETE", $url, $body);
    }
    /************ NOMOR PELANGGAN / KONTAK FAVORIT ************/

    /************ QRIS (migrasi dari wv2.bukakios.net) ************/
    // Semua route di grup WV /qris (JWT). uid diambil BE dari JWT.
    // Response mengikuti bentuk standar apiv2:
    //  - list/detail : {status:1, data, message, rc, ts} / {status:0, rc, error_msg}
    //  - insert/cancel: {status:1, rc, message} (tanpa data)

    // qris_detail_user: GET /qris/user/detail. Detail akun QRIS user (saldo,
    // merchant, nmid, status verif, url_qris, admin_pencairan, dll).
    // Response: {status, data:{...UsersQris}} / {status, error_msg}.
    function qris_detail_user()
    {
        $url = $this->api_url_wv . "/qris/user/detail";
        return $this->curl_get_url($url);
    }

    // qris_riwayat_transaksi: POST /qris/riwayat/transaksi. Riwayat transaksi
    // QRIS masuk. Paginasi cursor last_id. status WAJIB (1=saldo real, 0=hold).
    // Response: {status, data:{last_id, riwayat_trx:[...]}} / {status, error_msg}.
    function qris_riwayat_transaksi($body)
    {
        $url = $this->api_url_wv . "/qris/riwayat/transaksi";
        return $this->curl_post_url($url, $body);
    }

    // qris_mutasi: POST /qris/mutasi. Mutasi saldo (tipe "real" atau selain itu
    // = kliring). Paginasi cursor last_id.
    // Response: {status, data:{data:[...], last_id}} / {status, error_msg}.
    function qris_mutasi($body)
    {
        $url = $this->api_url_wv . "/qris/mutasi";
        return $this->curl_post_url($url, $body);
    }

    // qris_payment_list: GET /qris/payment/list. Daftar metode payment QRIS
    // (Saldo Bukakios + bank). Berisi min, max, biaya_admin, biaya_persen, dll.
    // Response: {status, data:[...PaymentMethodQris]} / {status, error_msg}.
    function qris_payment_list()
    {
        $url = $this->api_url_wv . "/qris/payment/list";
        return $this->curl_get_url($url);
    }

    // qris_rekening_list: GET /qris/rekening/list. Daftar rekening bank user
    // untuk pencairan (nested rekening_qris + payment_method).
    // Response: {status, data:[...]} / {status, error_msg}.
    function qris_rekening_list()
    {
        $url = $this->api_url_wv . "/qris/rekening/list";
        return $this->curl_get_url($url);
    }

    // qris_pencairan_v2: POST /qris/pencairan-v2. Proses penarikan dana QRIS.
    // Body: {total, rekening_id} (rekening_id=0 = ke stok Bukakios).
    // Response: {status, data:{id}} / {status, error_msg}.
    function qris_pencairan_v2($total, $rekening_id = 0)
    {
        $body = array(
            "total"       => (int)$total,
            "rekening_id" => (int)$rekening_id,
        );
        $url = $this->api_url_wv . "/qris/pencairan-v2";
        return $this->curl_post_url($url, $body);
    }

    // qris_pencairan_list: POST /qris/pencairan/list. Riwayat penarikan dana.
    // Paginasi cursor last_id. Catatan: BE membalas key "LastId" (bukan snake).
    // Response: {status, data:{data:[...], LastId}} / {status, error_msg}.
    function qris_pencairan_list($payment_method_id = 0, $limit = 30, $last_id = 0)
    {
        $body = array(
            "payment_method_id" => (int)$payment_method_id,
            "limit"             => (int)$limit,
            "last_id"           => (int)$last_id,
        );
        $url = $this->api_url_wv . "/qris/pencairan/list";
        return $this->curl_post_url($url, $body);
    }

    // qris_pencairan_detail: GET /qris/pencairan/detail/:id. Detail 1 penarikan.
    // Response: {status, data:{...PencarianQrisDetail}} / {status, error_msg}.
    function qris_pencairan_detail($id)
    {
        $url = $this->api_url_wv . "/qris/pencairan/detail/" . (int)$id;
        return $this->curl_get_url($url);
    }

    // qris_pencairan_cancel: GET /qris/pencairan/cancel/:id. Batalkan penarikan.
    // Response: {status:1, message} / error.
    function qris_pencairan_cancel($id)
    {
        $url = $this->api_url_wv . "/qris/pencairan/cancel/" . (int)$id;
        return $this->curl_get_url($url);
    }
    /************ QRIS ************/

}
