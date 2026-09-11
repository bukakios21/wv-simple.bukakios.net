<?php
// File ini di-require dari index.php, jadi variabel config sudah tersedia
// Jika diakses langsung, load config yang diperlukan
if (!isset($primary)) {
    require_once "../config.php";
    require_once "../_session.php";
}
$teks_komplain = "Halo kak, saya butuh bantuan terkait transaksi di Bukakios";
$wa_link = wa_link($teks_komplain);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
    <title>title::Transaksi Tidak Ditemukan</title>
</head>

<body class="font-sans text-slate-950 antialiased">

    <!-- Header -->
    <header class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-slate-100">
        <div class="flex items-center gap-3 px-4 py-3">
            <button id="backBtn" aria-label="Kembali" class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-slate-100 text-slate-700 transition hover:bg-slate-200 active:scale-95">
                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 18l-6-6 6-6"/>
                </svg>
            </button>
            <div class="h-1 flex-1 rounded-full bg-slate-100 overflow-hidden">
                <div class="h-full w-full rounded-full bg-brand"></div>
            </div>
            <div class="shrink-0 text-[16px] font-bold tracking-[-0.04em] text-brand">BukaKios</div>
        </div>
    </header>

    <main class="mx-auto flex min-h-[calc(100vh-61px)] max-w-lg flex-col justify-center px-4 py-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-card">
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full border border-rose-200 bg-rose-50">
                <svg viewBox="0 0 24 24" class="h-9 w-9 text-rose-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
            </div>
            <div class="mt-3 text-[45px] font-black leading-none tracking-tight text-slate-900">404</div>
            <h1 class="m-0 mt-1.5 text-[16px] font-bold text-slate-900">Transaksi Tidak Ditemukan</h1>
            <p class="m-0 mt-1 text-[14px] leading-relaxed text-slate-500">Maaf, data transaksi yang kamu cari tidak ditemukan. Kemungkinan ID transaksi salah atau transaksi sudah tidak tersedia.</p>

            <div class="mt-5 grid grid-cols-2 gap-2.5">
                <button type="button" id="btnBack" class="flex items-center justify-center gap-1.5 rounded-xl bg-brand py-2.5 text-[14px] font-bold text-white transition hover:bg-brandDark active:scale-[0.99]">
                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Kembali
                </button>
                <a href='<?php echo $openurl . $wa_link; ?>' class="flex items-center justify-center gap-1.5 rounded-xl bg-emerald-500 py-2.5 text-[14px] font-bold text-white transition hover:bg-emerald-600">
                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    Via WhatsApp
                </a>
            </div>
        </div>

        <p class="m-0 mt-4 text-center text-[13px] font-medium text-slate-400">Butuh bantuan lainnya? Customer care kami siap membantu kamu.</p>
    </main>

    <script>
        // Android-aware back button (pola index.php)
        function goBack(e) {
            e.preventDefault();
            if (window.android && typeof window.android.back === 'function') {
                try { window.android.back(); return; } catch (_) {}
            }
            if (history.length > 1) { history.back(); }
            else { window.location.href = '<?= $c_url ?? "/" ?>'; }
        }
        document.getElementById('backBtn').addEventListener('click', goBack);
        document.getElementById('btnBack').addEventListener('click', goBack);
    </script>
</body>

</html>
