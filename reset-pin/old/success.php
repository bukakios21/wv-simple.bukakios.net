<?php
require_once("../../config.php");

$lyt_description = isset($_SESSION['msg']) ? $_SESSION['msg'] : 'Berhasil.';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BukaKios - Berhasil</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: '#1a7fce',
            brandDark: '#1265a6',
          },
          fontFamily: {
            sans: ['Inter', 'ui-sans-serif', 'system-ui', 'Segoe UI', 'Roboto', 'Arial', 'sans-serif'],
          }
        }
      }
    }
  </script>
</head>
<body class="min-h-screen bg-white font-sans text-slate-950 flex items-center justify-center px-6">

  <div class="w-full max-w-sm text-center">
    <!-- Success icon -->
    <div class="mx-auto grid h-20 w-20 place-items-center rounded-full bg-emerald-50">
      <svg class="h-10 w-10 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
        <polyline points="22 4 12 14.01 9 11.01" />
      </svg>
    </div>

    <h1 class="mt-6 text-[22px] font-extrabold tracking-[-0.03em] text-slate-900">Berhasil!</h1>
    <p class="mt-2 text-[14px] leading-5 text-slate-500"><?php echo htmlspecialchars($lyt_description); ?></p>

    <a href="index.php" class="mt-8 inline-flex h-[48px] w-full items-center justify-center rounded-full bg-brand text-[14px] font-extrabold text-white transition hover:bg-brandDark active:scale-[0.99]">
      Kembali
    </a>
  </div>

</body>
</html>
<script>
window.history.pushState(null, '', window.location.href);
window.addEventListener('popstate', function() {
    window.history.pushState(null, '', window.location.href);
    window.location.replace('index.php');
});
</script>