<?php
if (!isset($api_url)){
    require_once("../config.php");
}
if(!isset($lyt_image)){
	$html_title = "Pesan";
	$lyt_button_link = "#";
	$lyt_button_name = "KEMBALI KE HOME";
	$lyt_image = "https://assets.bukakios.net/img2/uploads/2019/12/963-becek5.png";
	$lyt_title = "Coming Soon";
	$lyt_description = "Maaf Fitur Ini Masih Dalam tahap pengembangan oleh tim kami";
}
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
    <title><?PHP echo $html_title; ?></title>
</head>

<body class="font-sans text-slate-950 antialiased">

    <!-- Header -->
    <header class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-slate-100">
        <div class="flex items-center gap-3 px-4 py-3">
            <div class="h-1 flex-1 rounded-full bg-slate-100 overflow-hidden">
                <div class="h-full w-full rounded-full bg-brand"></div>
            </div>
            <div class="shrink-0 text-[16px] font-bold tracking-[-0.04em] text-brand">BukaKios</div>
        </div>
    </header>

    <main class="mx-auto flex min-h-[calc(100vh-61px)] max-w-lg flex-col justify-center px-4 py-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-card">
            <img src="<?PHP echo $lyt_image; ?>" alt="<?PHP echo htmlspecialchars($lyt_title, ENT_QUOTES, 'UTF-8'); ?>" class="mx-auto h-32 w-auto max-w-full object-contain">
            <h1 class="m-0 mt-5 text-[15px] font-bold text-slate-900"><?PHP echo htmlspecialchars($lyt_title, ENT_QUOTES, 'UTF-8'); ?></h1>
            <p class="m-0 mt-1 text-[13px] leading-relaxed text-slate-500"><?PHP echo htmlspecialchars($lyt_description, ENT_QUOTES, 'UTF-8'); ?></p>

            <?php if (!empty($lyt_button_link) && $lyt_button_link !== '#') { ?>
                <a href="<?PHP echo htmlspecialchars($lyt_button_link, ENT_QUOTES, 'UTF-8'); ?>" class="mt-5 flex w-full items-center justify-center gap-1.5 rounded-xl bg-brand py-2.5 text-[13px] font-bold text-white transition hover:bg-brandDark active:scale-[0.99]">
                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12h18M12 5l7 7-7 7"/></svg>
                    <?PHP echo htmlspecialchars($lyt_button_name, ENT_QUOTES, 'UTF-8'); ?>
                </a>
            <?php } ?>
        </div>
    </main>

    <script>
        // Android-aware back button (pola reset-pin / 404)
        function goBack(e) {
            e.preventDefault();
            if (window.android && typeof window.android.back === 'function') {
                try { window.android.back(); return; } catch (_) {}
            }
            if (history.length > 1) { history.back(); }
            else { window.location.href = '<?= $c_url ?? "/" ?>'; }
        }
    </script>
</body>

</html>