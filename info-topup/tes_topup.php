<?php
//exit;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../config.php");
require_once("../config_db.php");

//require_once("../_session.php");
$openurl = "open://";
$open_url = "open://";
if(isset($_GET['id'])){
    $topup_id = 10557990;
    $user_id = 39958;
	$detail_topup = $db->fetch("select 
	t.uid,t.topup_metode,t.topup_metode_kategori,t.nominal_topup,t.kode_unik,t.fee,t.total_transfer,t.nomor_rekening,t.created_at,t.expired_at,t.status,m.nama_kategori,m.nama_metode,m.gambar_metode,m.nomor_rekening,m.nama_rekening,m.id
	from topup t inner join topup_metode m 
	on t.topup_metode=m.id
	where t.uid='$user_id' and t.id='$topup_id'
	");
	if(!isset($detail_topup['uid'])){
		echo "Data Topup tidak di temukan";
		exit;
	}
	$total_transfer_rp = $app->idr($detail_topup['total_transfer']);
	$topup_metode_kategori = $detail_topup['topup_metode_kategori'];
	$nama_kategori = $detail_topup['nama_kategori'];
	$metode_id = $detail_topup['id'];
	$uid = $detail_topup['uid'];
	$nama_metode = $detail_topup['nama_metode'];
	$topup_metode = $detail_topup['topup_metode'];
	$nominal_topup = $detail_topup['nominal_topup'];
	$kode_unik = $detail_topup['kode_unik'];
	$fee = $detail_topup['fee'];
	$total_transfer = $detail_topup['total_transfer'];
	$nomor_rekening = $detail_topup['nomor_rekening'];
	$created_at = $detail_topup['created_at'];
	$expired_at = $detail_topup['expired_at'];
	$status = $detail_topup['status'];
	$gambar_metode = $detail_topup['gambar_metode'];
	$nomor_rekening = $detail_topup['nomor_rekening'];
	$nama_rekening = $detail_topup['nama_rekening'];
	$terima_bersih = $total_transfer+$fee;
	if($topup_metode_kategori!=1){
		$terima_bersih = $total_transfer-$fee;
	}
	$hash_topup = md5("$topup_id:$uid:$topup_metode:$created_at");
	if($status==0){
		$st_image = "https://assets.bukakios.net/img2/uploads/2019/12/827-sand-clock.png";
		$statusnya = " <span class='badge badge-warning mx-auto text-center mt-1' style='color:white;padding:5px 10px 5px 10px'>Menunggu Pembayaran</span>";
		$st_label = "Topup Pending";
	}else if($status==1){
		$st_image = "https://assets.bukakios.net/img2/uploads/2019/12/474-checked.png";
		$statusnya = " <span class='badge badge-success mx-auto text-center mt-1' style='color:white;padding:5px 10px 5px 10px'>Topup Berhasil</span>";
		$st_label = "Topup Berhasil";
	}else{
		$st_image = "https://assets.bukakios.net/img2/uploads/2019/12/822-button.png";
		$statusnya = " <span class='badge badge-danger mx-auto text-center mt-1' style='color:white;padding:5px 10px 5px 10px'>Topup Dibatalkan</span>";
		$st_label = "Topup Di Batalkan";
	}
	if(isset($_GET['act'])){
		//batalkan topup 
		$act = $_REQUEST['act'];
		if($act=='cancel'){
			if(isset($_GET['id'])){
				//do
				$id = abs((int)$_GET['id']);
				$db->query("update topup set status=2 where uid='$user_id' and id='$id'");
				$_SESSION['success_msg'] = "Berhasil Membatalkan topup :)";
				header("location:$c_url/info-topup/?id=$topup_id&s=1");
				exit;
			}
		}
	}
}else{
	exit;
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Nunito" />
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
		<link rel="stylesheet" href="https://assets.bukakios.net/css/box.css" crossorigin="anonymous">
        <title>title:detail topup pending</title>
        <style>
            .body {
                left: 0;
                bottom: 0;
                width: 100%;
                height: 80%;
                background-color: #fff;
                border-radius: 0px 0px 0px 0px;
                margin-bottom:10px;
                padding-top:4px;
            }
            .footer {
                /* position: fixed; */
                left: 0;
                bottom: 0;
                width: 100%;
                background-color: #fff;
                color: #000;
                text-align: center;
                padding-top: 2px;
                padding-bottom: 10px;
            }
            .jumbotron {
                border-radius: 50px;
            }
            body {
                font-family: Nunito;
            }
			
			.card{
				box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
			}
            /* #3498db */
			

			a.disabled {
				pointer-events: none;
				cursor: default;
				color: grey;
			}

			.loader {
                border: 16px solid #f3f3f3;
                border-radius: 50%;
                border-top: 16px solid #3498db;
                width: 50px;
                height: 50px;
                -webkit-animation: spin 5s linear infinite; /* Safari */
                animation: spin 2s linear infinite;
            }
            hr {
                border: 2px dashed #9C9C9C;
            }
            #border-radius-bottom {
                -moz-border-radius-bottomleft: 100%60px;
                -webkit-border-bottom-left-radius: 100%60px;
                border-bottom-left-radius: 100%60px;
                -moz-border-radius-bottomright: 100%60px;
                -webkit-border-bottom-right-radius: 100%60px;
                border-bottom-right-radius: 100%60px;
            }
            .cir{
                position: absolute;
                margin-left:10px;
                margin-top:122px;
                z-index:1;
                width: 20px;
                height: 20px;
                background-color: #fff;
                box-shadow: inset -4px 0px 0px rgba(0,0,0,0.16);
                border-radius:50px;
            }
            .circle{
                position: absolute;
                right:0px;
                margin-right:12px;
                margin-top:121px;
                z-index:1;
                width: 20px;
                height: 20px;
                background-color: #fff;
                box-shadow: inset 4px 0px 0px rgba(0,0,0,0.16);
                border-radius:50px;
            }

            @media screen and (min-width: 576px) {
                .circle {
                    margin-right: 30px;
                }
                .cir {
                    margin-left: 28px;
                }
            }
            
            @media screen and (min-width: 590px) {
                .circle {
                    margin-right: 35px;
                }
                .cir {
                    margin-left: 34px;
                }
            }
            
            @media screen and (min-width: 600px) {
                .circle {
                    margin-right: 40px;
                }
                .cir {
                    margin-left: 39px;
                }
            }
            
            @media screen and (min-width: 610px) {
                .circle {
                    margin-right: 45px;
                }
                .cir {
                    margin-left: 43px;
                }
            }
            
            @media screen and (min-width: 620px) {
                .circle {
                    margin-right: 50px;
                }
                .cir {
                    margin-left: 48px;
                }
            }
            .wrap {
                position: absolute;
                bottom: 0;
                top: 0;
                left: 0;
                right: 0;
                margin: auto;
                height: 310px;
            }

            a {
                text-decoration: none;
                color: #1a1a1a;
            }

            h1 {
                margin-bottom: 60px;
                text-align: center;
                font: 300 2.25em 'Lato';
                text-transform: uppercase;
            }
            h1 strong {
                font-weight: 400;
                color: #ea4c4c;
            }

            h2 {
                margin-bottom: 80px;
                text-align: center;
                font: 300 0.7em 'Lato';
                text-transform: uppercase;
            }
            h2 strong {
                font-weight: 400;
            }

            .countdown {
                width: auto;
                margin: 0 auto;
            }
            .countdown .bloc-time {
                float: left;
                margin-right: 45px;
                text-align: center;
            }
            .countdown .bloc-time:last-child {
                margin-right: 0;
            }
            .countdown .count-title {
                display: block;
                margin-bottom: 15px;
                font: normal 0.94em 'Lato';
                color: #1a1a1a;
                text-transform: uppercase;
            }
            .countdown .figure {
                position: relative;
                float: left;
                height: 50px;
                width: 30px;
                margin-right: 10px;
                background-color: #fff;
                border-radius: 8px;
                -moz-box-shadow: 0 3px 4px 0 rgba(0, 0, 0, 0.2),
                    inset 2px 4px 0 0 rgba(255, 255, 255, 0.08);
                -webkit-box-shadow: 0 3px 4px 0 rgba(0, 0, 0, 0.2),
                    inset 2px 4px 0 0 rgba(255, 255, 255, 0.08);
                box-shadow: 0 3px 4px 0 rgba(0, 0, 0, 0.2),
                    inset 2px 4px 0 0 rgba(255, 255, 255, 0.08);
            }
            .countdown .figure:last-child {
                margin-right: 0;
            }
            .countdown .figure > span {
                position: absolute;
                left: 0;
                right: 0;
                margin: auto;
                font: normal 45px/50px 'Lato';
                font-weight: 700;
                color: #de4848;
            }
            .countdown .figure .top:after,
            .countdown .figure .bottom-back:after {
                content: '';
                position: absolute;
                z-index: -1;
                left: 0;
                bottom: 0;
                width: 100%;
                height: 100%;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            }
            .countdown .figure .top {
                z-index: 3;
                background-color: #f7f7f7;
                transform-origin: 50% 100%;
            -webkit-transform-origin: 50% 100%;
            -moz-border-radius-topleft: 10px;
            -webkit-border-top-left-radius: 10px;
                border-top-left-radius: 10px;
            -moz-border-radius-topright: 10px;
            -webkit-border-top-right-radius: 10px;
                border-top-right-radius: 10px;
            -moz-transform: perspective(200px);
            -ms-transform: perspective(200px);
            -webkit-transform: perspective(200px);
                transform: perspective(200px);
            }
            .countdown .figure .bottom {
                z-index: 1;
            }
            .countdown .figure .bottom:before {
                content: '';
                position: absolute;
                display: block;
                top: 0;
                left: 0;
                width: 100%;
                height: 50%;
                background-color: rgba(0, 0, 0, 0.02);
            }
            .countdown .figure .bottom-back {
                z-index: 2;
                top: 0;
                height: 50%;
                overflow: hidden;
                background-color: #f7f7f7;
            -moz-border-radius-topleft: 10px;
            -webkit-border-top-left-radius: 10px;
                border-top-left-radius: 10px;
            -moz-border-radius-topright: 10px;
            -webkit-border-top-right-radius: 10px;
                border-top-right-radius: 10px;
            }
            .countdown .figure .bottom-back span {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                margin: auto;
            }
            .countdown .figure .top,
            .countdown .figure .top-back {
                height: 50%;
                overflow: hidden;
                -moz-backface-visibility: hidden;
                -webkit-backface-visibility: hidden;
                backface-visibility: hidden;
            }
            .countdown .figure .top-back {
                z-index: 4;
                bottom: 0;
                background-color: #fff;
                -webkit-transform-origin: 50% 0;
                transform-origin: 50% 0;
                -moz-transform: perspective(200px) rotateX(180deg);
                -ms-transform: perspective(200px) rotateX(180deg);
                -webkit-transform: perspective(200px) rotateX(180deg);
                transform: perspective(200px) rotateX(180deg);
                -moz-border-radius-bottomleft: 10px;
                -webkit-border-bottom-left-radius: 10px;
                border-bottom-left-radius: 10px;
                -moz-border-radius-bottomright: 10px;
                -webkit-border-bottom-right-radius: 10px;
                border-bottom-right-radius: 10px;
            }
            .countdown .figure .top-back span {
                position: absolute;
                top: -100%;
                left: 0;
                right: 0;
                margin: auto;
            }
        </style>

    </head>
  <body>
    <div class="py-3" style="background-color:<?=$primary?>;height:200px">
        <div class="text-center mb-2">
            <!-- <img class="mb-1" src=<?= $st_image ?>><br/> -->
            <img class="mb-1" width="120px" src=<?= $st_image ?>><br/>
        </div>
    </div>
    <div style="margin-top:-50px;margin-right:2px;margin-left:2px">
        <div class="cir"></div>
        <div class="circle"></div>
        <div class="container ">
            <div class="card py-3 px-4" style="border-radius:10px">
                <span class="font-weight-bold">ID Pemesanan</span>
                <span class="font-weight-bold">#<?=$topup_id?> <a href=''><img src='https://assets.bukakios.net/img2/uploads/2019/12/569-refresh.png' style='height:20px;margin-left:2px;margin-top:-2px'/></a></span>
                <span class="font-weight-bold">Hingga Tanggal</span>
                <span class="font-weight-light"><?= $expired_at ?></span>
                <hr>
                <div class="countdown">
                    <div class="bloc-time hours" data-init-value="24">
                    <span class="count-title font-weight-bold">Hours</span>

                    <div class="figure hours hours-1">
                        <span class="top">0</span>
                        <span class="top-back">
                        <span>0</span>
                        </span>
                        <span class="bottom">0</span>
                        <span class="bottom-back">
                        <span>0</span>
                        </span>
                    </div>

                    <div class="figure hours hours-2">
                        <span class="top">0</span>
                        <span class="top-back">
                        <span>0</span>
                        </span>
                        <span class="bottom">0</span>
                        <span class="bottom-back">
                        <span>0</span>
                        </span>
                    </div>
                    </div>

                    <div class="bloc-time min" data-init-value="0">
                    <span class="count-title font-weight-bold">Minutes</span>

                    <div class="figure min min-1">
                        <span class="top">0</span>
                        <span class="top-back">
                        <span>0</span>
                        </span>
                        <span class="bottom">0</span>
                        <span class="bottom-back">
                        <span>0</span>
                        </span>
                    </div>

                    <div class="figure min min-2">
                        <span class="top">0</span>
                        <span class="top-back">
                        <span>0</span>
                        </span>
                        <span class="bottom">0</span>
                        <span class="bottom-back">
                        <span>0</span>
                        </span>
                    </div>
                    </div>

                    <div class="bloc-time sec" data-init-value="0">
                    <span class="count-title font-weight-bold">Seconds</span>

                    <div class="figure sec sec-1">
                        <span class="top">0</span>
                        <span class="top-back">
                        <span>0</span>
                        </span>
                        <span class="bottom">0</span>
                        <span class="bottom-back">
                        <span>0</span>
                        </span>
                    </div>

                    <div class="figure sec sec-2">
                        <span class="top">0</span>
                        <span class="top-back">
                        <span>0</span>
                        </span>
                        <span class="bottom">0</span>
                        <span class="bottom-back">
                        <span>0</span>
                        </span>
                    </div>
                    </div>
                </div>
                <!-- <span class="badge badge-danger mx-auto text-center mt-1" style="display:none" id="expire">Topup EXPIRED</span> -->
                <?= $statusnya ?>

                <hr>
                <div class="text-center mx-5">
                    <table width="100%" style="text-align: left">
                        <tr>
                            <td><span class="font-weight-bold text-left">Total Tagihan</span></td>
                            <td><span class="font-weight-light"><?= $app->idr($nominal_topup); ?></span></td>
                        </tr>
                        <tr>
                            <td><span class="font-weight-bold">Kode Unik</span></td>
                            <td><span class="font-weight-light"><?= $app->idr($kode_unik); ?></span></td>
                        </tr>
                        <tr>
                            <td><span class="font-weight-bold">Total</span></td>
                            <td><span class="font-weight-light"><?= $app->idr($total_transfer) ?></span></td>
                        </tr>
                    </table>
                    <div class="mt-2">
                        <span style="font-size:20px" class="badge badge-danger font-weight-bold"><?= $app->idr($total_transfer) ?></span>
                        <br/>
                        <span style="margin-bottom:18px;text-decoration:underline;color:#00bfff;cursor:pointer" onclick="copyToClipboard('nominal_transfer')">Salin Jumlah</span>
                    </div>
                </div>
                <hr/>

                <?PHP if($status==0){ ?>
				<div class='row'>
					<div class='container'>
						<div class="col-12">
							<?PHP require_once("detail_topup/$metode_id.php"); ?>
						</div>
					</div>
				</div>
				<?PHP } ?>

            </div>

            <?php if ($status == 0) { ?>
                <div class="mt-3 py-3 px-4 text-center" style="border: 3px dashed #9C9C9C">
                    <span>Pembatalan Topup</span>
                    <a class="btn btn-danger btn-sm" href="<?PHP echo "$c_url/info-topup/?id=$topup_id&act=cancel"; ?>">Batal</a>
                </div>
            <?php }else{ ?>
                <div class="mt-3 py-3 px-4 text-center" style="border: 3px dashed #9C9C9C">
                    <a style='margin-left:10px;margin-right:10px;background-color:<?=$primary;?>' href='livechat://open.it' class='btn btn-primary btn-block'>Kontak CS</a>
                </div>
            <?php } ?>
        </div>
    </div>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="../assets/js/jquery.js"></script>
        <script src="../assets/js/sweetalert.min.js"></script>
		<script src="https://member.bukakios.net/js/lib/notie/notie.js"></script>
        <script src="https://member.bukakios.net/js/me/copy.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.3.3/gsap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/es6-tween/5.5.11/Tween.min.js"></script>
        <script>
            // Create Countdown
            var tgl = "<?= $expired_at ?>"
            var tgl_las = "<?=date("Y-m-d H:i:s")?>"
            // console.log(tgl)
      var Countdown = {
        // Backbone-like structure
        $el: $('.countdown'),

        // Params
        countdown_interval: null,
        total_seconds: 0,
        // Initialize the countdown
        init: function () {
            
            var countDownDate = new Date(tgl).getTime();
          // DOM
            // console.log(tgl_las)
            var now = new Date(tgl_las).getTime();
            // console.log(now)
            var distance = countDownDate - now;
            // console.log(distance)
            var hours1 = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes1 = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds1 = Math.floor((distance % (1000 * 60)) / 1000);
          this.$ = {
            hours: this.$el.find('.bloc-time.hours .figure'),
            minutes: this.$el.find('.bloc-time.min .figure'),
            seconds: this.$el.find('.bloc-time.sec .figure'),
          };
        //   console.log(this.$)
          // Init countdown values
          this.values = {
            hours: hours1,
            minutes: minutes1,
            seconds: seconds1,
          };

          // Initialize total seconds
          this.total_seconds =
          this.values.hours * 60 * 60 +
          this.values.minutes * 60 +
          this.values.seconds;
          
        //   console.log(this.total_seconds)
          // Animate countdown to the end
          this.count();
        },

        count: function () {
          var that = this,
            $hour_1 = this.$.hours.eq(0),
            $hour_2 = this.$.hours.eq(1),
            $min_1 = this.$.minutes.eq(0),
            $min_2 = this.$.minutes.eq(1),
            $sec_1 = this.$.seconds.eq(0),
            $sec_2 = this.$.seconds.eq(1);

          this.countdown_interval = setInterval(function () {
            if (that.total_seconds > 0) {
              --that.values.seconds;

              if (that.values.minutes >= 0 && that.values.seconds < 0) {
                that.values.seconds = 59;
                --that.values.minutes;
              }

              if (that.values.hours >= 0 && that.values.minutes < 0) {
                that.values.minutes = 59;
                --that.values.hours;
              }

              // Update DOM values
              // Hours
              that.checkHour(that.values.hours, $hour_1, $hour_2);

              // Minutes
              that.checkHour(that.values.minutes, $min_1, $min_2);

              // Seconds
              that.checkHour(that.values.seconds, $sec_1, $sec_2);

              --that.total_seconds;
            } else {
              clearInterval(that.countdown_interval);
                $('#expire').show();
            }
          }, 1000);
        },

        animateFigure: function ($el, value) {
          var that = this,
            $top = $el.find('.top'),
            $bottom = $el.find('.bottom'),
            $back_top = $el.find('.top-back'),
            $back_bottom = $el.find('.bottom-back');

          // Before we begin, change the back value
          $back_top.find('span').html(value);

          // Also change the back bottom value
          $back_bottom.find('span').html(value);

          // Then animate
          TweenMax.to($top, 0.8, {
            rotationX: '-180deg',
            transformPerspective: 300,
            ease: Quart.easeOut,
            onComplete: function () {
              $top.html(value);

              $bottom.html(value);

              TweenMax.set($top, { rotationX: 0 });
            },
          });

          TweenMax.to($back_top, 0.8, {
            rotationX: 0,
            transformPerspective: 300,
            ease: Quart.easeOut,
            clearProps: 'all',
          });
        },

        checkHour: function (value, $el_1, $el_2) {
          var val_1 = value.toString().charAt(0),
            val_2 = value.toString().charAt(1),
            fig_1_value = $el_1.find('.top').html(),
            fig_2_value = $el_2.find('.top').html();

          if (value >= 10) {
            // Animate only if the figure has changed
            if (fig_1_value !== val_1) this.animateFigure($el_1, val_1);
            if (fig_2_value !== val_2) this.animateFigure($el_2, val_2);
          } else {
            // If we are under 10, replace first figure with 0
            if (fig_1_value !== '0') this.animateFigure($el_1, 0);
            if (fig_2_value !== val_1) this.animateFigure($el_2, val_1);
          }
        },
      };

      // Let's go !
      <?php
      if ($status == 0) {?>
        Countdown.init();
      <?php
      }
      ?>
      
        </script>
    </body>
</html>