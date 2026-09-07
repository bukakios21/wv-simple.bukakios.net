<?php
$url_bg = "";
if ($metode_id == 2 or $metode_id == 4 or $metode_id == 1 or $metode_id == 43 or $metode_id == 3) {
    if ($metode_id == 2) {
        $url_bg = "https://assets.bukakios.net/img2/uploads/2022/06/651-mandiri-2.png";
    } else if ($metode_id == 4) {
        $url_bg = "https://assets.bukakios.net/img2/uploads/2022/06/689-bca-2-2.png";
    }else if ($metode_id == 1) {
        // $url_bg = "https://assets2.bukakios.net/img2/uploads/2024/09/737-bri-ganti-ke-307.png";
    }else if ($metode_id == 43) {
        // $url_bg = "https://assets2.bukakios.net/img2/uploads/2025/05/584-ppup-buat-tiket-bsi.png";
    }else if ($metode_id == 3) {
        // $url_bg = "https://assets2.bukakios.net/img2/uploads/2025/10/370-pop-up---bni-ganti-rek-10-okt-2025.png";
    }
}?>
<div class='row'>
    <div class="col-12">
        <span class="font-weight-bold">Transfer Ke</span>
        <div id='info-bank' class="text-center">
            <!-- <div class='col-md-6 col-xs-12 mx-auto'> -->
            <div class='box' style='padding:20px;cursor:default'>
                <center><img src='<?PHP echo $gambar_metode; ?>' width='120px' /></center>
                <p style='margin-top:10px'>Bank : <b><?PHP echo $nama_metode; ?></b><br>
                    Nomor Rekening : <b id='nomor_rekening' data-text="No. Rekening berhasil disalin" data-copy="<?PHP echo str_replace("-", "", $nomor_rekening); ?>"><?PHP echo $nomor_rekening; ?></b> <br><span style="text-decoration:underline;color:#00bfff;cursor:pointer" onclick="copyToClipboard('nomor_rekening')">Salin No.Rek</span><br>
                    Atas Nama : <b><?PHP echo $nama_rekening; ?></b><br>
            </div>
            <!-- </div> -->
        </div>
    </div>
</div>
<!-- </div> dari awal index.php -->
</div>

<div class='row'>
    <div class="col-12">
        <div id='cara-bayar-id-1' class="mt-3">
            <div class='alert alert-danger bayar-id'>
                <h5>HARAP BACA PENTING!!!!!</h5>
                *Mohon transfer sesuai jumlah yang tertera (tidak lebih atau kurang).
                tiga angka unik di gunakan untuk membantu kami mendeteksi bahwa kamu yang mentransfer.
                tenang saja, saldo kamu tetap tertambah sejumlah yang ditransfer,
                termasuk tiga angka unik <?PHP echo $total_transfer_rp; ?>
            </div>
            <div class='alert alert-warning bayar-id'>
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>INFO BANK OFFLINE : </strong>
                Deposit dapat dilakukan 24 jam otomatis selama bank terkait tidak maintenance (offline). Namun layanan internet banking dari Bank BCA, BNI, Mandiri dan BRI, pada jam-jam tertentu pasti Offline / Maintenance setiap harinya. Tidak ada jadwal yang resmi dari bank kapan akan melakukan maintenance, Namun rata-rata Bank Offline antara jam 21:00 - 00:00 WIB. Sehingga transfer deposit yang dilakukan pada saat bank offline akan diproses otomatis setelah bank kembali normal.
            </div>
            *Apabila Saldo tidak masuk dalam waktu 30 menit setelah transfer, harap hubungi tim kami :)
        </div>
    </div>
</div>

<!-- <div class="modal" id="modal_notif_bank" tabindex="-1" role="dialog" style="

        width: 80%;
        margin-top: 100px;
        margin-left:40px;
        background-image: url(<?php //echo $url_bg ?>);
        background-repeat: no-repeat;
        background-size: contain;
      ">
    <div class="modal-dialog" role="document" style="width: 80%; margin:auto; background-image: url(<?php echo $url_bg ?>);"></div>
</div> -->


<!-- <script src="https://wv.bukakios.net/assets/js/jquery.js"></script> -->
<script src="https://wv.bukakios.net/assets/js/sweetalert.min.js"></script>
<?php
if ($metode_id == 1) { ?>
    <script>
        Swal.fire({
            title: "<b>PENTING</b>",
            html: "<p class='text-justify'>*Mohon maaf untuk topup via Bank Transfer BRI sukses lebih lambat dari metode Bank Lainnya. Untuk deposit lebih cepat dapat menggunakan metode Virtual Account/Bank Lainnya/Emoney.</p>",
            confirmButtonText: "Ok",
        });
    </script>
<?php } elseif ($metode_id == 2) { ?>
    <script>
        Swal.fire({
            title: "<b>PENTING</b>",
            html: "<p class='text-justify'>*Mohon maaf untuk topup via Mandiri bank transfer sukses lebih lambat dari metode bank lainnya. untuk deposit lebih cepat dapat menggunakan metode BANK lainnya, Virtual Account atau QRIS.</p>",
            confirmButtonText: "Ok",
        });
    </script>
<?php } elseif ($metode_id == 43) { ?>
    <script>
    Swal.fire({
        title: "<b>PENTING</b>",
        html: "<p class='text-justify'>*Mohon maaf untuk topup via Bank Transfer BSI sukses lebih lambat dari metode Bank Lainnya. Untuk deposit lebih cepat dapat menggunakan metode Virtual Account/Bank Lainnya/Emoney.</p>",
        confirmButtonText: "Ok",
    });
</script>
<?php } ?>
