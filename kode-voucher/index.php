<?PHP
require_once("../config.php");
require_once('../_session.php');

/*
if ($user_id != 0){
    $url = "https://wv2.bukakios.net/v/radeem-voucher";
    header("Location: $url");
    exit;
}
*/

$lyt_image = "https://assets.bukakios.net/img2/uploads/2020/01/392-slack-imgs.com-min.png";
if (isset($_GET['act'])){
    require_once('proses_voucher.php');
    exit;
}
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kode Voucher - BukaKios</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: '#1a7fce',
            brandDark: '#1265a6',
          },
          boxShadow: {
            card: '0 5px 14px rgba(16, 24, 40, 0.07)',
            soft: '0 4px 12px rgba(15, 23, 42, 0.06)',
          },
          fontFamily: {
            sans: ['Inter', 'ui-sans-serif', 'system-ui', 'Segoe UI', 'Roboto', 'Arial', 'sans-serif'],
          }
        }
      }
    }
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-950 antialiased pb-8">

  <!-- Header -->
  <header class="sticky top-0 z-10 bg-white border-b border-slate-100">
    <div class="flex items-center gap-3 px-4 py-3">
      <button onclick="history.back()" aria-label="Kembali" class="grid h-9 w-9 shrink-0 place-items-center rounded-full border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-50 hover:border-slate-300 active:scale-95">
        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M15 18l-6-6 6-6"/>
        </svg>
      </button>
      <div class="h-1 flex-1 rounded-full bg-slate-200 overflow-hidden">
        <div class="h-full w-full rounded-full bg-brand"></div>
      </div>
      <div class="shrink-0 text-[19px] font-extrabold tracking-[-0.04em] text-brand">BukaKios</div>
    </div>
  </header>

  <main class="px-4 pt-6">

    <!-- Hero Section -->
    <div class="flex flex-col items-center text-center mb-6">
      <div class="w-24 h-24 rounded-2xl bg-brand/10 flex items-center justify-center mb-4 shadow-soft">
        <svg viewBox="0 0 24 24" class="w-12 h-12 text-brand" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
          <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
          <line x1="8" y1="10" x2="8" y2="10"/>
          <line x1="12" y1="10" x2="12" y2="10"/>
          <line x1="16" y1="10" x2="16" y2="10"/>
        </svg>
      </div>
      <h1 class="text-[23px] font-extrabold tracking-tight text-slate-900">Tukar Voucher</h1>
      <p class="text-sm text-slate-500 mt-1">Masukkan kode voucher untuk redeem</p>
    </div>

    <!-- Info Card -->
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6">
      <div class="flex items-start gap-3">
        <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
          <svg viewBox="0 0 24 24" class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
          </svg>
        </div>
        <div>
          <p class="text-[14px] text-amber-800 leading-relaxed">
            Kode voucher hanya bisa digunakan <strong class="font-semibold">sekali</strong>. Pastikan tidak ada yang mengetahui kode voucher kamu!
          </p>
        </div>
      </div>
    </div>

    <!-- Input Card -->
    <div class="bg-white rounded-2xl border border-slate-100 p-4 mb-6 shadow-card">
      <label for="kode" class="block text-[13px] font-semibold text-slate-600 mb-2">Masukkan Kode Voucher *</label>
      <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
          <svg viewBox="0 0 24 24" class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
          </svg>
        </div>
        <input type="text" required="" placeholder="ZBND1792SB10" id="kode" class="w-full rounded-xl border border-slate-200 bg-white pl-12 pr-4 py-3.5 text-[15px] text-slate-700 transition focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none font-mono tracking-wider" oninput="toggleButton()">
      </div>
      <button id="btn-use" onclick="useVoucher()" disabled class="w-full rounded-xl bg-brand px-4 py-3.5 mt-4 text-[15px] font-semibold text-white transition hover:bg-brandDark active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed">
        <span id="btn-text">Gunakan Kode</span>
      </button>
    </div>

    <!-- Error Message Section -->
    <div id="error-section" class="hidden bg-red-50 border border-red-200 rounded-2xl p-4 mb-6">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center shrink-0">
          <svg viewBox="0 0 24 24" class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <line x1="15" y1="9" x2="9" y2="15"/>
            <line x1="9" y1="9" x2="15" y2="15"/>
          </svg>
        </div>
        <div>
          <p id="error-message" class="text-[14px] text-red-800 leading-relaxed"></p>
        </div>
      </div>
    </div>



  </main>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script>
    var csrf = "<?php echo $app->csrf() ?>";

    function toggleButton() {
      var kode = $('#kode').val();
      if (kode.trim()) {
        $('#btn-use').attr('disabled', false);
      } else {
        $('#btn-use').attr('disabled', true);
      }
    }

    function useVoucher() {
      var kode = $('#kode').val();

      if (!kode) {
        alert('Silakan masukkan kode voucher');
        return;
      }

      $('#btn-use').attr('disabled', true);
      $('#btn-text').text('Memproses...');

      $.ajax({
        url: "index.php",
        data: {act: "use", csrf: csrf, kode: kode},
        method: 'get',
        dataType: 'json',
        success: function(data) {
          $('#btn-use').attr('disabled', false);
          $('#btn-text').text('Gunakan Kode');
          
          if (data.status == 1) {
            $('#success-message').text(data.message);
            $('#success-modal').removeClass('hidden');
            $('#kode').val('');
            $('#btn-use').attr('disabled', true);
          } else if (data.status == 0) {
            $('#error-message').text(data.error_msg);
            $('#error-section').removeClass('hidden');
            // Scroll to error section
            document.getElementById('error-section').scrollIntoView({ behavior: 'smooth', block: 'center' });
          }
        },
        error: function() {
          $('#btn-use').attr('disabled', false);
          $('#btn-text').text('Gunakan Kode');
          $('#error-message').text('Terjadi kesalahan. Silakan coba lagi.');
          $('#error-section').removeClass('hidden');
          document.getElementById('error-section').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      });
    }
  </script>

  <!-- Success Modal -->
  <div id="success-modal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-sm p-6 text-center animate-bounce-in">
      <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
        <svg viewBox="0 0 24 24" class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
          <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
      </div>
      <h2 class="text-xl font-bold text-slate-900 mb-2">Berhasil!</h2>
      <p id="success-message" class="text-sm text-slate-600 mb-6"></p>
      <button onclick="closeSuccessModal()" class="w-full rounded-xl bg-brand px-4 py-3.5 text-[15px] font-semibold text-white transition hover:bg-brandDark active:scale-[0.98]">
        Tutup
      </button>
    </div>
  </div>

  <style>
    @keyframes bounce-in {
      0% { transform: scale(0.9); opacity: 0; }
      50% { transform: scale(1.02); }
      100% { transform: scale(1); opacity: 1; }
    }
    .animate-bounce-in { animation: bounce-in 0.3s ease-out; }
  </style>

  <script>
    function closeSuccessModal() {
      $('#success-modal').addClass('hidden');
    }
    // Close modal on backdrop click
    $('#success-modal').click(function(e) {
      if (e.target === this) {
        closeSuccessModal();
      }
    });
  </script>

</body>
</html>
