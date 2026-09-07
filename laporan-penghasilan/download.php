<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('../config.php');
require_once('../_library/fpdf/fpdf.php');

if (isset($_REQUEST['act'], $_REQUEST['hash'], $_REQUEST['start'], $_REQUEST['end'], $_REQUEST['uid'])){
    $show = false;
    $start = $_REQUEST['start'];
    $end = $_REQUEST['end'];
    $act = $_REQUEST['act'];
    $user_id = abs((int) $_REQUEST['uid']);
    if ($act == "download"){
        $hash = md5("$user_id-bukakios");
        if ($hash == $_REQUEST['hash']){
            // echo "$ms1_url/v1/laporan-penghasilan/api_laporan_penghasilan.php?key=$api_key&uid=$user_id&act=download&start=$start&end=$end";
            // $data_api = $app->grab_data("$ms1_url/laporan-penghasilan/api_laporan_penghasilan.php?key=$api_key&uid=$user_id&act=download&start=$start&end=$end");
            $data_api = $app->grab_data("https://api-v2.bukakios.net/wv-x7Up2p/laporan?uid=$user_id&start=$start&end=$end");
            $data_res = json_decode($data_api, true);
            if (!isset($data_res['status'])){
                echo "Gagal menghubungi server $data_api";exit;
            }
            if ($data_res['status'] == 0){
                echo $data_res['error_msg'];exit;
            }else{
                $show =true;
            }
        }else{
            echo "Data tidak valid !!";exit;
        }
    }else{
        echo "Need action !!";exit;
    }
}else{
    $show = false;
    $msg = "Invalid request";
    $alert = "alert alert-danger";
}

if (isset($msg)){
    echo $msg;exit;
}
function tgl_indo($tanggal){
	$bulan = array (
		1 =>   'JAN',
		'FEB',
		'MAR',
		'APR',
		'MEI',
		'JUN',
		'JUL',
		'AGUS',
		'SPT',
		'OKT',
		'NVM',
		'DSM'
	);
	$pecahkan = explode('-', $tanggal);
	
	// variabel pecahkan 0 = tanggal
	// variabel pecahkan 1 = bulan
	// variabel pecahkan 2 = tahun
 
	return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}
$file_name = "Data Laporan Transaksi Tanggal ". tgl_indo($start) ." Sampai ". tgl_indo($end);

// Setting halaman PDF
    $pdf = new FPDF('l','mm','A5');
    // Menambah halaman baru
    $pdf->AddPage();
    // Setting jenis font
    $pdf->SetFont('Arial','B',12);
    // Membuat string
    $pdf->Cell(190,7,$file_name,0,1,"C");
    // $pdf->Cell(190,7,'Daftar Harga Motor Dealer Maju Motor',0,1,'C');
    // Setting spasi kebawah supaya tidak rapat
    $pdf->SetFont('Arial','B',10);

    $pdf->Cell(10,7,'',0,1);
    $pdf->Cell(50,6,'Keuntungan',1,0);
    $pdf->Cell(80,6,'Pemasukan',1,0);
    $pdf->Cell(50,6,'Pengeluaran',1,1);

    $pdf->Cell(50,6,$app->angka_id($data_res['untung']),1,0);
    $pdf->Cell(80,6,$app->angka_id($data_res['jual']),1,0);
    $pdf->Cell(50,6,$app->angka_id($data_res['modal']),1,1);


    $pdf->Cell(10,7,'',0,1);

    
    $pdf->Cell(50,6,'Tanggal',1,0);
    $pdf->Cell(80,6,'Pemasukan',1,0);
    $pdf->Cell(50,6,'Pengeluaran',1,1);
    
    $pdf->SetFont('Arial','',10);
    foreach ($data_res['data'] as $row){
        $pdf->Cell(50,6,tgl_indo($row['tanggal']),1,0);
        $pdf->Cell(80,6,$app->angka_id($row['jual']),1,0);
        $pdf->Cell(50,6,$app->angka_id($row['modal']),1,1);
    }
    

   // $pdf->Output($file_name, 'F');
$pdf->Output('D',$file_name.'.pdf', false);
//$pdf->Output(['I' [, $file_name [, false]]]);
//$pdf->Output('I','file.pdf');
?>