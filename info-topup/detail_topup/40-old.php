<?php
//jabber indomaret
if(!empty($detail_topup['rekening_pg'])){
    $exp1 = explode(":",$detail_topup['rekening_pg']);
    $paycode = $exp1[0];
    $orderid = $exp1[1];
    ?>
    <div id='carabayar' class='text-center'>
        Kode Pembayaran :
        <br>
        <img src='<?PHP echo "$assets_url/img/payment/indomaret.png"; ?>' style='height:25px'/><br>
        <b style='font-size:21px' id='nomor_rekening' data-text="No. Rekening berhasil disalin" data-copy="<?PHP echo $paycode; ?>"><?PHP echo $paycode; ?></b>
        <br><span style="text-decoration:underline;color:#00bfff;cursor:pointer" onclick="copyToClipboard('nomor_rekening')">Salin Kode Pembayaran</span>
        <p style='margin-top:10px'>Nama Merchant : <b>SHOPEE</b>
        <p style='margin-top:5px'>Nama Pelanggan : <b>kumadinyawelah22</b> atau <b>septian233</b>

    </div>
    <div class='row'>
        <div class='col-md-12'>
            <div class="alert alert-primary text-justify">
                <b>Info</b> :<p>
                    Mohon bayar sesuai kode unik karena ini jenis  pembayaran indomaret yg di limpahkan ke akun shopee kami, kelebihan Pembayaran melalui INDOMARET ini adalah <b>100% Gratis biaya admin</b>, tidak ada biaya admin <i>kasir indomaret</i>.
            </div>
            <div id='infonya' class='card' style='margin-bottom:20px;padding:10px'>
                * <b>HARAP BACA</b> : Beritahu ke kasir mau bayar tagihan <b>SHOPEE</b>, lalu tunjukan kode pembayaranya &rarr; <?PHP echo $paycode; ?>
            </div>
        </div>
    </div>
    <div id='panduanbayar'>
        <div class="pl-16 pr-16 pt-24">
            <div class="">
                <h5>PANDUAN PEMBAYARAN</h5>
            </div>

            <div id="accordion">
                <div class="card">
                    <div style='cursor:pointer' class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <h5 class="mb-0">
                            <a>
                                Kasir Indomaret
                            </a>
                            <span class='pull-right'><i class='fa fa-angle-down'></i></span>
                        </h5>
                    </div>
                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <div class='row'>
                                <div class="col-md-12 col-sm-12">
                                    <ol style='padding-left:10px;padding-right:10px'>
                                        <li>Tunjukkan nomor tagihan pembelian <b><?PHP echo $paycode; ?></b></li>
                                        <li>Jika Di tanya Merchant ID, sebutkan &quot;SHOPEE&quot;</li>
                                        <li>Bayar Sesuai Tagihan di Bukakios Yakni : <b><?PHP echo $total_transfer_rp; ?></b> </li>
                                        <li>Transaksi Anda sudah selesai, simpan struk transaksi sebagai bukti pembayaran</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- 1 -->
            </div>


        </div>
    </div>
<?php }else{ ?>
    <div class="alert alert-danger">
        Maaf kode pembayaran belum berhasil di terbitkan, silahkan tunggu 2 menit, kemudian refersh halaman ini kembali. seharusnya kode pembayaran sudah muncul : <?PHP echo $detail_topup['rekening_pg']; ?>
    </div>
<?php } ?>