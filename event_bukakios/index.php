<?php
require_once("../config.php");
require_once("../_session.php");


?>
<!doctype html>
<html lang="id">
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
    <title>Event BukaKios</title>
    <style>
        * { font-family: 'Inter', sans-serif; }
        a, a:hover { text-decoration: none; }
        .event-card { transition: transform .14s ease, box-shadow .14s ease, border-color .14s ease; }
        .event-card:not(.is-ended):active { transform: scale(.985); }
        .event-card:not(.is-ended):hover { border-color: rgba(26, 127, 206, .35); box-shadow: 0 8px 24px rgba(15, 23, 42, .10); }
        .shimmer { position: relative; overflow: hidden; background: #e5e7eb; }
        .shimmer::after {
            content: '';
            position: absolute;
            inset: 0;
            transform: translateX(-100%);
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.65), transparent);
            animation: shimmer 1.15s infinite;
        }
        @keyframes shimmer { 100% { transform: translateX(100%); } }
    </style>
</head>
<body class="font-sans text-slate-950 antialiased bg-slate-50">
    <header class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-slate-100">
        <div class="flex h-12 items-center gap-3 px-4">
            <div class="h-1 flex-1 rounded-full bg-slate-100 overflow-hidden">
                <div class="h-full w-full rounded-full bg-brand"></div>
            </div>
            <div class="shrink-0 text-[16px] font-bold tracking-[-0.04em] text-brand">BukaKios</div>
        </div>
    </header>

    <main class="mx-auto max-w-lg px-4 py-5 pb-10">
        <section class="mb-4 rounded-3xl border border-blue-100 bg-gradient-to-br from-blue-50 to-white p-4 shadow-soft">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-brand/10 text-brand">
                    <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 21h8"/><path d="M12 17v4"/><path d="M7 4h10"/><path d="M17 4v6a5 5 0 0 1-10 0V4"/><path d="M5 9H4a2 2 0 0 1-2-2V5h5"/><path d="M19 9h1a2 2 0 0 0 2-2V5h-5"/></svg>
                </div>
                <div class="min-w-0">
                    <h1 class="m-0 text-[18px] font-extrabold leading-tight text-slate-900">Event BukaKios</h1>
                    <p class="m-0 mt-1 text-[13px] font-medium text-slate-500">Ikuti promo dan kompetisi terbaru dari BukaKios.</p>
                </div>
            </div>
        </section>

        <div id="eventMeta" class="mb-3 hidden items-center justify-between px-1">
            <span class="text-[12px] font-bold uppercase tracking-wide text-slate-500">Daftar Event</span>
            <span id="eventCount" class="rounded-full bg-brand/10 px-2.5 py-1 text-[12px] font-bold text-brand">0</span>
        </div>

        <div id="eventList" class="space-y-3.5"></div>
    </main>

    <template id="shimmerTemplate">
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-soft">
            <div class="aspect-[16/8] w-full shimmer"></div>
            <div class="p-4">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0 flex-1">
                        <div class="h-5 w-4/5 rounded-lg shimmer"></div>
                        <div class="mt-2 h-3.5 w-3/5 rounded-lg shimmer"></div>
                    </div>
                    <div class="h-12 w-20 rounded-2xl shimmer"></div>
                </div>
                <div class="mt-3 h-9 w-full rounded-xl shimmer"></div>
            </div>
        </div>
    </template>

    <script>
        const today = new Date().toISOString().slice(0, 10);
        const eventList = document.getElementById('eventList');
        const eventMeta = document.getElementById('eventMeta');
        const eventCount = document.getElementById('eventCount');
        const shimmerTemplate = document.getElementById('shimmerTemplate');

        function escapeHtml(value) {
            return String(value ?? '').replace(/[&<>'"]/g, function(char) {
                return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' })[char];
            });
        }

        function formatIDR(value) {
            const number = Number(value || 0);
            return new Intl.NumberFormat('id-ID').format(number);
        }

        function formatDateIndo(value) {
            if (!value) return '-';
            const dateOnly = String(value).slice(0, 10);
            const parts = dateOnly.split('-');
            if (parts.length !== 3) return value;
            const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            const monthIndex = Number(parts[1]) - 1;
            return `${parts[2]} ${bulan[monthIndex] || parts[1]} ${parts[0]}`;
        }

        function isEnded(eAkhir) {
            if (!eAkhir) return false;
            return String(eAkhir).slice(0, 10) < today;
        }

        function showShimmer(total = 7) {
            eventMeta.classList.add('hidden');
            eventMeta.classList.remove('flex');
            eventList.innerHTML = '';
            for (let i = 0; i < total; i++) {
                eventList.appendChild(shimmerTemplate.content.cloneNode(true));
            }
        }

        function showError(message) {
            eventMeta.classList.add('hidden');
            eventMeta.classList.remove('flex');
            eventList.innerHTML = `
                <div class="mt-8 flex flex-col items-center rounded-2xl border border-rose-100 bg-white p-6 text-center shadow-soft">
                    <div class="mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-rose-50 text-rose-500">
                        <svg viewBox="0 0 24 24" class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                    </div>
                    <p class="m-0 text-[15px] font-bold text-slate-800">${escapeHtml(message)}</p>
                    <button onclick="loadEvents()" class="mt-4 rounded-xl bg-brand px-5 py-2.5 text-[14px] font-bold text-white shadow-soft active:scale-[0.99]">Coba Lagi</button>
                </div>
            `;
        }

        function showEmpty() {
            eventMeta.classList.add('hidden');
            eventMeta.classList.remove('flex');
            eventList.innerHTML = `
                <div class="mt-8 flex flex-col items-center rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-soft">
                    <div class="mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                        <svg viewBox="0 0 24 24" class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 21h8"/><path d="M12 17v4"/><path d="M7 4h10"/><path d="M17 4v6a5 5 0 0 1-10 0V4"/></svg>
                    </div>
                    <p class="m-0 text-[15px] font-bold text-slate-800">Belum ada event</p>
                    <p class="m-0 mt-1 text-[13px] font-medium text-slate-500">Event terbaru akan tampil di halaman ini.</p>
                </div>
            `;
        }

        function imageWebpUrl(url) {
            if (!url) return '';
            const raw = String(url);
            if (!raw.includes('assets.bukakios.net/img2/uploads/')) return raw;
            return raw.replace(/\.(jpe?g|png)$/i, '.webp');
        }

        function eventCard(row) {
            const eId = Number(row.e_id || 0);
            const ended = isEnded(row.e_akhir);
            const clickable = !ended;
            const tagClass = ended ? 'border-slate-200 bg-slate-100 text-slate-500' : 'border-emerald-200 bg-emerald-50 text-emerald-700';
            const tagText = ended ? 'Event Berakhir' : 'Masih Berjalan';
            const href = clickable ? `detail2.php?e_id=${encodeURIComponent(eId)}` : '#';
            const disabledAttrs = clickable ? '' : 'aria-disabled="true" onclick="return false;"';
            const imageUrl = row.e_image ? String(row.e_image) : '';
            const webpUrl = imageWebpUrl(imageUrl);
            const fallbackImage = '<div class="flex h-full w-full items-center justify-center text-slate-300"><svg viewBox="0 0 24 24" class="h-12 w-12" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M8 11l2.5 2.5L14 10l4 5"/></svg></div>';
            const image = imageUrl ? `<picture><source srcset="${escapeHtml(webpUrl)}" type="image/webp"><img src="${escapeHtml(imageUrl)}" alt="${escapeHtml(row.e_title || 'Event BukaKios')}" class="h-full w-full object-cover" loading="lazy" decoding="async" fetchpriority="low" onerror="this.onerror=null;this.parentElement.outerHTML='${fallbackImage.replace(/"/g, '&quot;')}'"></picture>` : `
                <div class="flex h-full w-full items-center justify-center text-slate-300">
                    <svg viewBox="0 0 24 24" class="h-12 w-12" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M8 11l2.5 2.5L14 10l4 5"/></svg>
                </div>
            `;
            const endedInfo = ended ? '<div class="mt-3 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[12px] font-semibold text-slate-500">Event sudah berakhir dan tidak bisa dibuka.</div>' : '';

            return `
                <a href="${href}" ${disabledAttrs} class="event-card ${ended && !clickable ? 'is-ended opacity-75' : ''} block overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-soft">
                    <div class="relative aspect-[16/8] w-full overflow-hidden bg-slate-100">
                        ${image}
                        <div class="absolute right-3 top-3 rounded-full border px-3 py-1 text-[11px] font-extrabold backdrop-blur ${tagClass}">${tagText}</div>
                    </div>
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h2 class="m-0 line-clamp-2 text-[16px] font-extrabold leading-snug text-slate-900">${escapeHtml(row.e_title || '-')}</h2>
                                <div class="mt-2 flex items-center gap-1.5 text-[12px] font-semibold text-slate-500">
                                    <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                                    <span>${formatDateIndo(row.e_mulai)} - ${formatDateIndo(row.e_akhir)}</span>
                                </div>
                            </div>
                            <div class="shrink-0 rounded-2xl bg-amber-50 px-3 py-2 text-right">
                                <div class="text-[10px] font-bold uppercase text-amber-600">Hadiah</div>
                                <div class="text-[13px] font-extrabold text-amber-700">Rp ${formatIDR(row.e_hadiah)}</div>
                            </div>
                        </div>
                        ${endedInfo}
                    </div>
                </a>
            `;
        }

        function renderEvents(events) {
            if (!Array.isArray(events) || events.length === 0) {
                showEmpty();
                return;
            }

            eventCount.textContent = events.length;
            eventMeta.classList.remove('hidden');
            eventMeta.classList.add('flex');
            eventList.innerHTML = events.map(eventCard).join('');
        }

        async function loadEvents() {
            showShimmer(7);
            try {
                const response = await fetch('api_list.php', {
                    method: 'GET',
                    headers: { 'Accept': 'application/json' },
                    cache: 'no-store',
                    credentials: 'same-origin'
                });
                const result = await response.json();
                if (!result || Number(result.status) !== 1) {
                    showError(result?.error_msg || result?.message || 'Maaf, data event belum tersedia.');
                    return;
                }
                renderEvents(result.data || []);
            } catch (err) {
                showError('Maaf, gagal memuat daftar event.');
            }
        }

        document.addEventListener('DOMContentLoaded', loadEvents);
    </script>
</body>
</html>
