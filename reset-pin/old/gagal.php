<?php
require_once("../../config.php");

$lyt_description = isset($_SESSION['error']) ? $_SESSION['error'] : 'Terjadi kesalahan. Silakan coba lagi.';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BukaKios - Gagal</title>
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
    <!-- Error icon -->
    <div class="mx-auto grid h-20 w-20 place-items-center rounded-full bg-red-50">
      <svg class="h-10 w-10 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10" />
        <line x1="15" y1="9" x2="9" y2="15" />
        <line x1="9" y1="9" x2="15" y2="15" />
      </svg>
    </div>

    <h1 class="mt-6 text-[22px] font-extrabold tracking-[-0.03em] text-slate-900">Gagal!</h1>
    <p class="mt-2 text-[14px] leading-5 text-slate-500"><?php echo htmlspecialchars($lyt_description); ?></p>

    <a href="index.php" class="mt-8 inline-flex h-[48px] w-full items-center justify-center rounded-full bg-brand text-[14px] font-extrabold text-white transition hover:bg-brandDark active:scale-[0.99]">
      Coba Lagi
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

