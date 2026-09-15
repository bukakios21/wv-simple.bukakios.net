<?php
require_once("../config.php");
require_once("../_session.php");
$real_hash = md5(md5("live-chat-x18klK-$user_id"));
if($bukakios_version_int>=15){
    $live_chat_url = "intercom://open.it";
}else{
    $live_chat_url = $open_url."https://bukakios.link/live-chat/?u=$user_id-$real_hash";
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kontak Kami - BukaKios</title>
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
  <header class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-slate-100">
    <div class="flex items-center gap-3 px-4 py-3">
      <div class="h-1 flex-1 rounded-full bg-slate-100 overflow-hidden">
        <div class="h-full w-full rounded-full bg-brand"></div>
      </div>
      <div class="shrink-0 text-[16px] font-bold tracking-[-0.04em] text-brand">BukaKios</div>
    </div>
  </header>

  <main class="px-4 pt-6">

    <!-- Hero Section -->
    <div class="flex flex-col items-center text-center mb-6">
      <div class="w-24 h-24 rounded-2xl bg-brand/10 flex items-center justify-center mb-4 shadow-soft">
        <svg viewBox="0 0 24 24" class="w-12 h-12 text-brand" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
        </svg>
      </div>
      <h1 class="text-[23px] font-extrabold tracking-tight text-slate-900">Butuh Bantuan?</h1>
      <p class="text-sm text-slate-500 mt-1">Tim kami siap membantu 24 jam</p>
    </div>

    <!-- Info Card -->
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-6">
      <div class="flex items-start gap-3">
        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center shrink-0">
          <svg viewBox="0 0 24 24" class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
          </svg>
        </div>
        <div>
          <p class="text-[14px] text-blue-800 leading-relaxed">
            Saat ini untuk menghubungi tim CS BukaKios tersedia melalui: WhatsApp, Telegram, Facebook, dan Email.
          </p>
        </div>
      </div>
    </div>

    <!-- Contact Buttons -->
    <div class="space-y-3 mb-6">

      <!-- WhatsApp -->
      <a href="<?php echo $open_url . wa_link(); ?>" target="_blank" class="flex items-center gap-4 w-full bg-white rounded-2xl border border-slate-100 p-4 shadow-card transition hover:shadow-md active:scale-[0.99]">
        <div class="w-12 h-12 rounded-xl bg-[#25D366] flex items-center justify-center shrink-0">
          <svg viewBox="0 0 24 24" class="w-6 h-6 text-white" fill="currentColor">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
          </svg>
        </div>
        <div class="flex-1 text-left">
          <h3 class="text-[16px] font-bold text-slate-800">WhatsApp</h3>
          <p class="text-[13px] text-slate-500">Chat Langsung</p>
        </div>
        <svg viewBox="0 0 24 24" class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="9 18 15 12 9 6"/>
        </svg>
      </a>

      <!-- Telegram -->
      <a href="<?php echo $open_url."https://t.me/cs1_bukakiosbot"; ?>" target="_blank" class="flex items-center gap-4 w-full bg-white rounded-2xl border border-slate-100 p-4 shadow-card transition hover:shadow-md active:scale-[0.99]">
        <div class="w-12 h-12 rounded-xl bg-[#0088cc] flex items-center justify-center shrink-0">
          <svg viewBox="0 0 24 24" class="w-6 h-6 text-white" fill="currentColor">
            <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
          </svg>
        </div>
        <div class="flex-1 text-left">
          <h3 class="text-[16px] font-bold text-slate-800">Telegram</h3>
          <p class="text-[13px] text-slate-500">@cs1_bukakiosbot</p>
        </div>
        <svg viewBox="0 0 24 24" class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="9 18 15 12 9 6"/>
        </svg>
      </a>

      <!-- Facebook -->
      <a href="<?php echo $open_url."https://www.facebook.com/bukakiosnet"; ?>" target="_blank" class="flex items-center gap-4 w-full bg-white rounded-2xl border border-slate-100 p-4 shadow-card transition hover:shadow-md active:scale-[0.99]">
        <div class="w-12 h-12 rounded-xl bg-blue-600 flex items-center justify-center shrink-0">
          <svg viewBox="0 0 24 24" class="w-6 h-6 text-white" fill="currentColor">
            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
          </svg>
        </div>
        <div class="flex-1 text-left">
          <h3 class="text-[16px] font-bold text-slate-800">Facebook</h3>
          <p class="text-[13px] text-slate-500">bukakiosnet</p>
        </div>
        <svg viewBox="0 0 24 24" class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="9 18 15 12 9 6"/>
        </svg>
      </a>

      <!-- Email -->
      <a href="<?php echo $open_url."mailto:support@bukakios.net"; ?>" target="_blank" class="flex items-center gap-4 w-full bg-white rounded-2xl border border-slate-100 p-4 shadow-card transition hover:shadow-md active:scale-[0.99]">
        <div class="w-12 h-12 rounded-xl bg-red-500 flex items-center justify-center shrink-0">
          <svg viewBox="0 0 24 24" class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
            <polyline points="22,6 12,13 2,6"/>
          </svg>
        </div>
        <div class="flex-1 text-left">
          <h3 class="text-[16px] font-bold text-slate-800">Email</h3>
          <p class="text-[13px] text-slate-500">support@bukakios.net</p>
        </div>
        <svg viewBox="0 0 24 24" class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="9 18 15 12 9 6"/>
        </svg>
      </a>

    </div>

    <!-- Working Hours Card -->
    <div class="bg-white rounded-2xl border border-slate-100 p-4 mb-6 shadow-card">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
          <svg viewBox="0 0 24 24" class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <polyline points="12 6 12 12 16 14"/>
          </svg>
        </div>
        <div>
          <p class="text-[13px] text-slate-500">Jam Kerja Bukakios Care</p>
          <p class="text-[15px] font-bold text-emerald-600">Setiap Hari, 24 Jam Non Stop</p>
        </div>
      </div>
    </div>

    <!-- Info Pills -->
    <div class="flex flex-wrap gap-2">
      <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-[12px] font-medium text-slate-600 shadow-soft">
        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
        Respon Cepat
      </span>
      <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-[12px] font-medium text-slate-600 shadow-soft">
        <span class="w-1.5 h-1.5 rounded-full bg-brand"></span>
        24/7 Online
      </span>
    </div>

  </main>

</body>
</html>
