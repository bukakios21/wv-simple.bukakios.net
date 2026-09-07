<?PHP
if (isset($_POST['ewallet_number'])) {
    $number = $_POST['ewallet_number'];
    // $data_post = array(
    //     "key" => $api_key,
    //     "id" => $topup_id,
    //     "pg" => "linkqu",
    //     "ewallet_number" => $number
    // );
    // $data_info = $app->curl_post("$api_url/get_topup_detail.php", $data_post);
    // $data_info = json_decode($data_info, true);
    // if (isset($data_info['status'])) {
    // } else {
    //     $pg_error = true;
    //     // $error_msg = $data_info;
    //     $error_msg = "Server Api Respon Tidak Valid!!, silahkan kontak tim kami!";
    // }
    $out = array(
        "status" => 1,
        "msg" => "Work $number"
    );
    echo json_encode($out);
    exit;
}
?>
<?php if (!$pg_error) { ?>
    <div class='row'>
        <div class="col-12">
            <!--span class="font-weight-bold">Request Pembayaran</span---!>
            <div id='info-bank' class="text-left">
                <!-- <div class='col-md-6 col-xs-12 mx-auto'> -->
                <div class="alert alert-warning" style="display: none;"></div>
                <div class="alert alert-success" style="display: none;"></div>
                <div class='box' style='padding:5px;cursor:default'>
                    <form>
                        <div class="form-group">
                            <label for=""><b>*Nomor OVO</b></label>
                            <input type="text" name="wallet_number" id="wallet_number" class="form-control" placeholder="08xx">
                            <small class="text-warning">Nomor ewallet OVO kamu</small>
                            <button class="btn btn-block btn-primary btn_submit_ewallet" style="margin-top: 20px;" type="button">Request pembayaran</button>
                        </div>
                    </form>
                </div>
                <!-- </div> -->
            </div>
        </div>
    </div>
    <!-- </div> dari awal index.php -->
<?php } ?>
</div>
<?PHP if (!$pg_error) { ?>
    <div id='cara-bayar-id-1'>
        <div class='alert alert-info mt-3 bayar-id'>
            Informasi mengenai OVOPOINT : <br>
            1. 24 Jam nonstop selama OVOPOINT tidak maintenance.<br>
            2. Begitu transfer saldo langsung masuk.<br>
        </div>
    </div>


<?PHP } else { ?>
    <div class='alert alert-danger mt-3 bayar-id'><?PHP echo $error_msg; ?></div>
<?PHP } ?>


<!-- <script>
    var alert_warning = $(".alert-warning")
    var alert_success = $(".alert-success")
    var btn_submit_ewallet = $(".btn_submit_ewallet")
    var wallet_number = $("#wallet_number")

    function show_alert_success() {
        alert_success.show()
        alert_warning.hide()
    }

    function show_alert_warning() {
        alert_success.hide()
        alert_warning.show()
    }

    function hide_alert() {
        alert_success.hide()
        alert_warning.hide()
    }

    $(document).ready(function() {
        show_alert_success()
        btn_submit_ewallet.click(function() {
            hide_alert()
            btn_submit_ewallet.html("Please wait..")
            btn_submit_ewallet.attr("disabled", true)
            var number = wallet_number.val()
            // alert("oke")
            var dataString = "ewallet_number=" + number
            $.ajax({
                url: "index.php",
                method: "POST",
                data: dataString,
                success: function(data) {
                    alert("success")
                    if (data.status == 1) {
                        show_alert_success()
                        alert_success.html(data.msg)
                    } else {
                        show_alert_warning()
                        alert_warning.html(data.error_msg)
                    }
                    alert_success.html("work")

                },
                error: function(request, status, error) {
                    alert(request.responseText);
                }
            });

            // alert('woi')
            btn_submit_ewallet.html("Request pembayaran ajha")
            btn_submit_ewallet.attr("disabled", false)
        });
    });
</script> -->