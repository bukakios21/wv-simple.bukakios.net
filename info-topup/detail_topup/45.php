<div class="title">
    <span><i class="fa fa-sort-alpha-down mr-1"></i> Cara Pembayaran</span>
</div>
<div class="mt-2 mb-1">
    <?PHP echo "Silahkan lakukan transfer pulsa sebesar <b>$total_transfer</b> ke nomor <b>$nom_rek</b> dengan cara kirim sms dengan format di bawah ini :"; ?>
</div>
<div class="mt-1 py-3 px-4 text-center" style="border: 3px dashed #9C9C9C">
    <div id='cara-bayar-id-1' class="mt-1">
        <?PHP
        $fomat_sms = "Transferpulsa $nom_rek $total_transfer";
        ?>
        SMS : <span id='query_text' style="font-weight:bold" data-text="Berhasil Salin " data-copy="<?PHP echo $fomat_sms; ?>">
            <?PHP echo $fomat_sms; ?></span> kirim ke 151
        <a class="btn btn-sm btn-outline-primary" onclick="copyToClipboard('query_text')"><i class="fa fa-copy"></i></a>
        <br />
        Atau
        <br />
        <a class="btn btn-sm btn-outline-primary" href="open://sms:151?body=<?PHP echo $fomat_sms; ?>"><i class="fa fa-envelope"></i> Transfer Pulsa Sekarang</a>
    </div>
</div>
<div class="alert alert-danger mt-2">MOHON BACA!!!<br> Setelah kamu melakukan transfer pulsa, kamu wajib melakukan
    konfirmasi melalui form di bawah ini agar proses convert pulsa kamu bisa di proses otomatis</div>

</div>
<?php
$key_cari = "caris-$topup_id";
$key_upload = "upload-$topup_id";
// $rediw->del($key_cari);
// $rediw->del($key_upload);
if (!$rediw->exists($key_upload)) {
    //$rediw->setex($key_cari,3600*24,1);
?>
    <div class="mt-3 py-3 px-4 text-center" style="border: 3px dashed #9C9C9C" id="konfirm">
        <div id='cara-bayar-id-1' class="mt-3 ">
            <div class='alert alert-success bayar-id'>
                <h4>Konfirmasi Pembayaran</h4>
                <div class='text-justify'>
                    <p>
                        <b>Silahkan masukan nomor yang kamu gunakan untuk mentransfer pulsa</b>
                    </p>
                </div>
                <input type="number" name="hp" id="hp" class="form-control" placeholder="082285xxxxxx">
                <div class="d-grid gap-2">
                    <button class="btn btn-sm btn-block btn-primary mt-1" id="cari">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>
<?php
}
if (!$rediw->exists($key_upload)) {
    /*
    // $rediw->setex($key_upload,3600*24,1);
?>
    <div id='cara-bayar-id-1' class="mt-3 ">
        <div class='alert alert-success bayar-id'>
            <h4>Input Manual</h4>
            <div class="d-grid gap-2">
                <a class="btn btn-sm btn-block btn-primary mt-1" href="https://wv.bukakios.net/convert-pulsa/upload.php?id=<?= $topup_id ?>&hp=<?php echo $redis->get("nomor-$topup_id"); ?>">Input
                    Manual</a>
            </div>
        </div>
    </div>
<?php
*/
} else {
?>
    <div class="card alert alert-succes" style="margin-top:10px">
        <h3>Informasi</h3>
        <p>Untuk saat ini bukti pembayaran kamu sedang dalam proses verifikasi oleh admin. Mohon menunggu atau chat customer
            service</p>
    </div>
    <?php
}
if (isset($_SESSION['cek_sess'])) {
    $a = "cek-$topup_id";
    if ($_SESSION['cek_sess'] != $a) {
    ?>
        <!-- <div class="mt-3 py-3 px-4 text-center" style="border: 3px dashed #9C9C9C" id="konfirm">
            <div id='cara-bayar-id-1' class="mt-3 " >
                <div class='alert alert-success bayar-id'>
                    <h4>Konfirmasi Pembayaran</h4>
                    <div class='text-justify'>
                        <p>
                            <b>Silahkan Cari Nomor Anda</b>
                        </p>
                    </div>
                    <input type="number" name="hp" id="hp" class="form-control" placeholder="082285xxxxxx">
                    <div class="d-grid gap-2">
                        <button class="btn btn-sm btn-block btn-primary mt-1" id="cari">Cari</button>
                    </div>
                </div>
            </div>
        </div> -->
    <?php
    } else {
    ?>
        <!-- <div id='cara-bayar-id-1' class="mt-3 ">
            <div class='alert alert-success bayar-id'>
                <h4>Input Manual</h4>
                <div class="d-grid gap-2">
                    <a class="btn btn-sm btn-block btn-primary mt-1" href="https://wv.bukakios.net/convert-pulsa/upload.php?id=<?= $topup_id ?>">Input Manual</a>
                </div>
            </div>
        </div> -->
    <?php
    }
} else { ?>
    <!-- <div class="mt-3 py-3 px-4 text-center" style="border: 3px dashed #9C9C9C" id="konfirm">
            <div id='cara-bayar-id-1' class="mt-3 ">
                <div class='alert alert-success bayar-id'>
                    <h4>Konfirmasi Pembayaran</h4>
                    <div class='text-justify'>
                        <p>
                            <b>Silahkan Cari Nomor Anda</b>
                        </p>
                    </div>
                    <input type="number" name="hp" id="hp" class="form-control" placeholder="082285xxxxxx">
                    <div class="d-grid gap-2">
                        <button class="btn btn-sm btn-block btn-primary mt-1" id="cari">Cari</button>
                    </div>
                </div>
            </div>
        </div> -->
<?php
}
?>