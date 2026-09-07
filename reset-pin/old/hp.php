<?PHP
require_once("../../config.php");
require_once('../../_session.php');

$lyt_image = "https://assets2.bukakios.net/img2/uploads/2026/06/414-image-removebg-preview.png";
if (isset($_POST['act'])){
    require_once('proses_otp_v2.php');exit;
}
?> 
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reset PIN - BukaKios</title>
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
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
      <div class="shrink-0 text-[18px] font-extrabold tracking-[-0.04em] text-brand">BukaKios</div>
    </div>
  </header>

  <main class="px-4 pt-6">

    <!-- Icon -->
    <div class="flex flex-col items-center text-center mb-6">
      <div class="w-20 h-20 rounded-2xl bg-brand/10 flex items-center justify-center mb-4 shadow-soft">
        <svg viewBox="0 0 24 24" class="w-10 h-10 text-brand" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <rect x="5" y="2" width="14" height="20" rx="2" ry="2"/>
          <line x1="12" y1="18" x2="12.01" y2="18"/>
        </svg>
      </div>
      <h1 class="text-[22px] font-extrabold tracking-tight text-slate-900">Verifikasi OTP</h1>
      <p class="text-sm text-slate-500 mt-1">via SMS</p>
    </div>

    <!-- Info Card -->
    <div class="bg-brand/10 border border-brand/20 rounded-2xl p-4 mb-6">
      <div class="flex items-start gap-3">
        <div class="w-8 h-8 rounded-full bg-brand flex items-center justify-center shrink-0">
          <svg viewBox="0 0 24 24" class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"/>
          </svg>
        </div>
        <div>
          <p class="text-[13px] text-blue-800 leading-relaxed">
            Kode OTP telah dikirim ke nomor HP kamu. Mohon tunggu beberapa detik dan periksa pesan masuk.
          </p>
        </div>
      </div>
    </div>

    <!-- OTP Form -->
    <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-card">
      <div class="mb-4">
        <label class="block text-[13px] font-semibold text-slate-700 mb-2">Masukkan Kode OTP</label>
        <div class="flex justify-center gap-2">
          <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" class="otp-input w-12 h-14 text-center text-xl font-bold border border-slate-200 rounded-xl focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 transition">
          <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" class="otp-input w-12 h-14 text-center text-xl font-bold border border-slate-200 rounded-xl focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 transition">
          <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" class="otp-input w-12 h-14 text-center text-xl font-bold border border-slate-200 rounded-xl focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 transition">
          <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" class="otp-input w-12 h-14 text-center text-xl font-bold border border-slate-200 rounded-xl focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 transition">
          <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" class="otp-input w-12 h-14 text-center text-xl font-bold border border-slate-200 rounded-xl focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 transition">
          <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" class="otp-input w-12 h-14 text-center text-xl font-bold border border-slate-200 rounded-xl focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20 transition">
        </div>
      </div>

      <!-- Timer -->
      <div class="text-center mb-4">
        <span id="time" class="text-[14px] text-slate-500"></span>
        <button id="resend" onclick="kirimUlang()" class="hidden text-[13px] font-semibold text-brand hover:underline">Kirim Ulang OTP</button>
      </div>

      <!-- Button -->
      <button id="btn-sending" onclick="submitOtp()" class="w-full rounded-xl bg-brand py-3.5 text-[15px] font-bold text-white shadow-lg transition hover:bg-brandDark active:scale-[0.98] flex items-center justify-center gap-2">
        <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="20 6 9 17 4 12"/>
        </svg>
        Verifikasi
      </button>
    </div>

  </main>

  <!-- Loading Overlay -->
  <div id="loading-overlay" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:16px;padding:24px;display:flex;flex-direction:column;align-items:center;gap:12px;box-shadow:0 20px 60px rgba(0,0,0,0.15);min-width:200px;">
      <div style="width:44px;height:44px;border:4px solid rgba(26,127,206,0.2);border-top-color:#1a7fce;border-radius:50%;animation:spin 0.8s linear infinite;"></div>
      <div style="font-size:14px;font-weight:600;color:#334155;">Memverifikasi...</div>
    </div>
  </div>

  <style>
    @keyframes spin { to { transform: rotate(360deg); } }
  </style>

  <script>
    var csrf = "<?php echo $app->csrf() ?>";
    var isProcessing = false;
    var resendCount = 0;
    var timerInterval;

    // OTP Input Handling
    document.addEventListener('DOMContentLoaded', function() {
      const otpInputs = document.querySelectorAll('.otp-input');
      
      otpInputs.forEach((input, index) => {
        input.addEventListener('input', function(e) {
          const value = e.target.value;
          if (value && index < otpInputs.length - 1) {
            otpInputs[index + 1].focus();
          }
        });
        
        input.addEventListener('keydown', function(e) {
          if (e.key === 'Backspace' && !e.target.value && index > 0) {
            otpInputs[index - 1].focus();
          }
        });
        
        input.addEventListener('paste', function(e) {
          e.preventDefault();
          const pastedData = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
          pastedData.split('').forEach((char, i) => {
            if (otpInputs[i]) {
              otpInputs[i].value = char;
            }
          });
          if (pastedData.length > 0) {
            const focusIndex = Math.min(pastedData.length, otpInputs.length - 1);
            otpInputs[focusIndex].focus();
          }
        });
      });
    });

    function getOtpValue() {
      const otpInputs = document.querySelectorAll('.otp-input');
      return Array.from(otpInputs).map(input => input.value).join('');
    }

    // Timer countdown
    function startTimer(duration) {
      var timer = duration, minutes, seconds;
      clearInterval(timerInterval);
      
      timerInterval = setInterval(function () {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;
        
        document.getElementById('time').textContent = 'Kirim ulang dalam ' + minutes + ':' + seconds;
        
        if (--timer < 0) {
          clearInterval(timerInterval);
          document.getElementById('time').textContent = '';
          document.getElementById('resend').classList.remove('hidden');
        }
      }, 1000);
    }

    // Start 60 second timer on load
    startTimer(60);

    // Resend OTP
    function kirimUlang() {
      if (resendCount > 0) return;
      resendCount++;
      
      document.getElementById('resend').textContent = 'Mengirim...';
      document.getElementById('resend').classList.add('pointer-events-none');
      
      $.ajax({
        url: "index.php",
        data: {act: "sms", csrf: csrf},
        method: 'POST',
        dataType: 'json',
        success: function(data) {
          document.getElementById('resend').textContent = 'OTP Terkirim!';
          setTimeout(function() {
            document.getElementById('resend').classList.add('hidden');
            startTimer(60);
          }, 2000);
        }
      });
    }

    // Submit OTP
    function submitOtp() {
      if (isProcessing) return;
      
      var otp = getOtpValue();
      if (!otp || otp.length < 6) {
        showToast('Masukkan 6 digit kode OTP', 'error');
        return;
      }
      
      isProcessing = true;
      var overlay = document.getElementById('loading-overlay');
      var btn = document.getElementById('btn-sending');
      
      overlay.style.display = 'flex';
      btn.disabled = true;
      btn.style.opacity = '0.7';

      $.ajax({
        url: "index.php",
        data: {act: "sending", csrf: csrf, otp: otp},
        method: 'POST',
        dataType: 'json',
        timeout: 15000,
        success: function(data) {
          overlay.style.display = 'none';
          if (data.status == 1) {
            showToast('Reset PIN berhasil!', 'success');
            setTimeout(function() { window.location.replace("../"); }, 1500);
          } else if (data.status == 0) {
            isProcessing = false;
            btn.disabled = false;
            btn.style.opacity = '1';
            showToast(data.error_msg || 'Kode OTP salah', 'error');
          }
        },
        error: function() {
          overlay.style.display = 'none';
          isProcessing = false;
          btn.disabled = false;
          btn.style.opacity = '1';
          showToast('Koneksi gagal, coba lagi', 'error');
        }
      });
    }

    // Toast notification
    function showToast(message, type) {
      var bgColor = type === 'success' ? '#10B981' : '#EF4444';
      var toast = document.createElement('div');
      toast.style.cssText = 'position:fixed;top:80px;left:50%;transform:translateX(-50%);background:' + bgColor + ';color:white;padding:12px 24px;border-radius:12px;font-size:14px;font-weight:600;z-index:99999;box-shadow:0 4px 12px rgba(0,0,0,0.15);animation:slideDown 0.3s ease';
      toast.textContent = message;
      document.body.appendChild(toast);
      setTimeout(function() {
        toast.style.animation = 'slideUp 0.3s ease forwards';
        setTimeout(function() { toast.remove(); }, 300);
      }, 2500);
    }
  </script>
  <style>
    @keyframes slideDown { from { top: 50px; opacity: 0; } to { top: 80px; opacity: 1; } }
    @keyframes slideUp { from { top: 80px; opacity: 1; } to { top: 50px; opacity: 0; } }
  </style>

</body>
</html>
