<?php

if (isset($_POST['uid'], $_FILES['image'])) {
    $curl = curl_init();
    $cfile = new CURLFile($_FILES['image']['tmp_name'], $_FILES['image']['type'], $_FILES['image']['name']);
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://assets.bukakios.net/rating_bukakios/upload_file_rating.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('uid' => $_POST['uid'], 'image' => $cfile),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    $res = json_decode($response, true);
    if (isset($res['status'])) {
        if ($res['status'] == 1) {
            setcookie('done3', "1", time() + (86400 * 30), "/", null, null, true);
            header("Refresh:0");
        }
    }
}

if (!isset($_GET['auth'])) {
    echo "<h1>FORBIDDEN</h1>";
    exit;
}
$auth = $_GET['auth'];
$auth_ex = explode("-", $auth);
$uid = $auth_ex[0];
$hash = $auth_ex[1];
$hash_real = md5("$uid-bk-rating-2022");
if ($hash != $hash_real) {
    echo "<h1>AUTH FORBIDDEN</h1>";
    exit;
}

$done = 0;
if (isset($_COOKIE['done3'])) {
    $done = true;
}
// echo $done;
// exit;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Event Rating BukaKios</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#0165d2",
                    },
                },
            },
        };
    </script>
    <style type="text/tailwindcss">
        @tailwind base;
      @tailwind components;
      @tailwind utilities;
      @layer components {
        .title {
          @apply font-bold text-[16px]  text-gray-500;
        }
        .deskripsi {
          @apply text-sm font-normal text-gray-500;
        }
        li {
          @apply ml-6;
        }
      }
    </style>
</head>

<body class="bg-gray-100">
    <div class="w-full sm:w-[480px] m-auto bg-white flex flex-col min-h-screen">
        <img src="https://assets.bukakios.net/img2/uploads/2022/07/397-event-rating-bukakios-cover.png" class="" />
        <div class="rounded bg-green-600 py-4 px-4 m-4 <?php echo $done != 1 ? 'hidden' : 'block' ?>">
            <p class="deskripsi text-white">
                Terima kasih telah mengikuti event ini. Pememang akan di umumkan pada
                tanggal <b>03 Juli 2023</b>. Terima kasih, salam hangat dari tim
                BukaKios
            </p>
        </div>
        <div class="p-4 <?php echo $done  == 1 ? 'hidden' : 'block' ?>" id="informasi">
            <div class="flex flex-col space-y-4 h-full mt-2">
                <div>
                    <p class="title text-[16px]">06 juni 2023 - 01 Juli 2023</p>
                    <p class="deskripsi">
                        Total Hadiah : <b class="text-primary">Rp. 1.000.000</b>
                    </p>
                </div>
                <div>
                    <h1 class="title">Deskripsi event:</h1>
                    <p class="deskripsi">
                    Hallo Sobat BukaKios! Tunjukkan sisi kreatifmu dengan memberikan ulasan menarik untuk Bukakios. Ada total Hadiah Rp.1.000.000 Menunggumu. Bagaimana caranya? Cukup berikan ulasan terbaik dan menarik di play store dengan menyebutkan keunggulan bukakios, produk/fitur favoritmu. kemudian screenshot dan upload di menu event. Jangan lupa share di story instagram dan tag instagram @bukakios.net
                    </p>
                </div>
                <div>
                    <h1 class="title">Informasi:</h1>
                    <ul class="list-disc">
                        <li class="deskripsi">
                            Semua pengguna Aplikasi BukaKios bisa mengikuti event ini
                        </li>
                        <li class="deskripsi">
                            berikan review terbaik agar kesempatan menang jadi lebih besar.
                        </li>
                        <li class="deskripsi">Total Hadiah Rp. 1.000.000</li>
                        <li class="deskripsi">
                            Hadiah akan dikirim langsung ke masing-masing akun pada 03 Juli 2023
                        </li>
                    </ul>
                </div>
                <div>
                    <h1 class="title">Syarat dan ketentuan:</h1>
                    <ul class="list-disc">
                        <li class="deskripsi">
                            Berikan ulasan terbaik dan menarik untuk aplikasi BukaKios di
                            Plays store.
                        </li>
                        <li class="deskripsi">
                        Sebutkan keunggulan bukakios dan  produk/fitur favorit kalian pada ulasan tersebut.
                        </li>
                        <li class="deskripsi">Berikan rating 5 bintang</li>
                        <li class="deskripsi">
                        Screenshot dan share ulasanmu di story instagram. tag @bukakiosnet (jika memiliki instagram) 
                        </li>
                        <li class="deskripsi">
                            50 orang beruntung akan mendapatkan masing-masing Saldo
                            Rp.20.000
                        </li>
                        <li class="deskripsi">
                        Bagi yang sudah pernah memberi ulasan sebelumnya, bisa update dengan ulasan yang terbaru, agar ulasan update ke tanggal periode Juni 2023
                        </li>
                    </ul>
                </div>
            </div>

            <div class="px-8 w-full h-full mt-8 border rounded-lg py-8">
                <div class="flex flex-row items-center rounded justify-center hover:cursor-pointer bg-[#0165d2]">
                    <a class="px-4 py-2 text-white" target="_blank" href="https://play.google.com/store/apps/details?id=net.bukakiosapps">Rating playstore</a>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                    </svg>
                </div>
            </div>
            <div class="px-8 w-full h-full mt-2 border rounded-lg py-8">
                <div class="justify-center flex flex-col space-y-2">
                    <form method="POST" id="uploadForm" enctype="multipart/form-data">
                        <input type="hidden" name="uid" value="<?php echo $uid ?>">
                        <div class="mb-3 w-full">
                            <label for="formFile" class="form-label inline-block mb-2 text-gray-700 deskripsi">Upload bukti screenshoot review play store</label>
                            <input name="image" class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" type="file" id="imgFile" />
                        </div>
                        <button class="hover:cursor-pointer space-x-6 h-full bg-primary px-4 py-2 text-white rounded flex flex-row w-full items-center justify-center" type="submit">
                            <span>Upload file</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-Z2RJRNQL1K"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-Z2RJRNQL1K');
    </script>
</body>

</html>