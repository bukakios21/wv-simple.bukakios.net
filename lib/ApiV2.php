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

}
