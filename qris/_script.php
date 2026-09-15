<?php
// Partial JS untuk halaman QRIS. Di-include dari index.php.
// Variabel $csrf, $url_qris, $saldo_real, $admin_pencairan tersedia dari scope.
// Guard: cegah akses langsung (harus di-include dari index.php).
if (!defined('ROOT')) { http_response_code(403); exit('Forbidden'); }
?>
<script>
  var csrf = "<?php echo $csrf ?>";
  var URL_QRIS = <?php echo json_encode($url_qris) ?>;
  var SALDO_REAL = <?php echo (int) $saldo_real ?>;
  var ADMIN_PENCAIRAN = <?php echo (int) $admin_pencairan ?>;
  var LIMIT = 20;

  // Metode payment "Saldo Bukakios" (min/max/biaya) — diisi saat buka tarik dana.
  var paymentSaldo = null;
  // Rekening user (array) — diisi saat buka tarik dana.
  var rekeningUser = [];
  var qrRendered = false;

  var riwState = { page: 1, cache: [], cursor: 0, done: false, loaded: false };
  var mutState = { page: 1, cache: [], cursor: 0, done: false, loaded: false, tipe: 'real' };
  var mutasiXhr = null;
  var penState = { page: 1, cache: [], cursor: 0, done: false, loaded: false };

  $(document).ready(function () {
    initPullToRefresh();
    switchTab('riwayat');

    $('.tab-btn').on('click', function () { switchTab($(this).attr('data-tab')); });

    // Sub-tab mutasi
    $('.mutasi-btn').on('click', function () {
      var tipe = $(this).attr('data-mutasi');
      if (tipe === mutState.tipe) return;
      $('.mutasi-btn').removeClass('active');
      $(this).addClass('active');
      mutState = { page: 1, cache: [], cursor: 0, done: false, loaded: true, tipe: tipe };
      $('#mutasi-list').empty();
      $('#mutasi-empty').addClass('hidden');
      $('#mutasi-pager').addClass('hidden');
      fetchMutasi();
    });
    $('.mutasi-btn[data-mutasi="real"]').addClass('active');

    // Sub-tab tarik dana
    $('.tarik-btn').on('click', function () { switchTarik($(this).attr('data-tarik')); });

    // Action buttons
    $('[data-action]').on('click', function () {
      var act = $(this).attr('data-action');
      if (act === 'lihat-qris') openQris();
      else if (act === 'info-akun') openModal('modal-info');
      else if (act === 'tarik-dana') openTarik();
      else if (act === 'mutasi') switchTab('mutasi');
    });

    // Close modal
    $('[data-close]').on('click', function () { closeModal($(this).attr('data-close')); });
    $('.qris-modal').on('click', function (e) { if (e.target === this) $(this).hide(); });

    // Pagers
    $('#riw-prev').on('click', function () { if (riwState.page > 1) { riwState.page--; renderRiwayat(); } });
    $('#riw-next').on('click', function () { gotoNext(riwState, fetchRiwayat, renderRiwayat); });
    $('#mut-prev').on('click', function () { if (mutState.page > 1) { mutState.page--; renderMutasi(); } });
    $('#mut-next').on('click', function () { gotoNext(mutState, fetchMutasi, renderMutasi); });
    $('#pen-prev').on('click', function () { if (penState.page > 1) { penState.page--; renderPenarikan(); } });
    $('#pen-next').on('click', function () { gotoNext(penState, fetchPenarikan, renderPenarikan); });

    // Input nominal formatting + realtime info
    $('#input-nominal-saldo').on('input', function () { onNominalInput($(this), 'saldo'); });
    $('#input-nominal-rekening').on('input', function () { onNominalInput($(this), 'rekening'); });

    // Submit tarik dana
    $('#form-tarik-saldo').on('submit', function (e) { e.preventDefault(); prosesPenarikan('saldo'); });
    $('#form-tarik-rekening').on('submit', function (e) { e.preventDefault(); prosesPenarikan('rekening'); });
  });

  /* ---------------- PULL TO REFRESH ---------------- */
  function initPullToRefresh() {
    var indicator = document.getElementById('pull-refresh-indicator');
    if (!indicator) return;

    var startY = 0;
    var pullY = 0;
    var tracking = false;
    var refreshing = false;
    var threshold = 86;
    var maxPull = 124;

    function atTop() {
      return window.scrollY <= 0 && document.documentElement.scrollTop <= 0 && document.body.scrollTop <= 0;
    }

    function setIndicator(distance) {
      var progress = Math.min(distance / threshold, 1);
      var translate = -64 + Math.min(distance, maxPull);
      var scale = .88 + (progress * .12);
      var rotate = progress * 180;
      indicator.classList.add('is-visible');
      indicator.style.transform = 'translate(-50%, ' + translate + 'px) scale(' + scale + ') rotate(' + rotate + 'deg)';
    }

    function resetIndicator() {
      indicator.classList.remove('is-visible', 'is-refreshing');
      indicator.style.transform = 'translate(-50%, -64px) scale(.88) rotate(0deg)';
    }

    window.addEventListener('touchstart', function (e) {
      if (refreshing || !atTop() || !e.touches || !e.touches.length) return;
      startY = e.touches[0].clientY;
      pullY = 0;
      tracking = true;
    }, { passive: true });

    window.addEventListener('touchmove', function (e) {
      if (!tracking || refreshing || !e.touches || !e.touches.length) return;
      var currentY = e.touches[0].clientY;
      var diff = currentY - startY;
      if (diff <= 0) return;

      pullY = Math.min(diff * .62, maxPull);
      setIndicator(pullY);
    }, { passive: true });

    window.addEventListener('touchend', function () {
      if (!tracking || refreshing) return;
      tracking = false;

      if (pullY >= threshold) {
        refreshing = true;
        indicator.classList.add('is-visible', 'is-refreshing');
        indicator.style.transform = 'translate(-50%, 58px) scale(1) rotate(0deg)';
        setTimeout(function () { window.location.reload(); }, 250);
      } else {
        resetIndicator();
      }
    }, { passive: true });

    window.addEventListener('touchcancel', function () {
      if (refreshing) return;
      tracking = false;
      resetIndicator();
    }, { passive: true });
  }

  /* ---------------- MODAL / QR ---------------- */
  function openModal(id) { $('#' + id).css('display', 'flex'); }
  function closeModal(id) { $('#' + id).hide(); }

  function openQris() {
    if (!qrRendered) renderQrisCode();
    openModal('modal-qris');
  }

  function renderQrisCode() {
    var el = document.getElementById('qris-canvas');
    if (!el) return;

    if (!URL_QRIS) {
      el.innerHTML = '<p class="px-4 text-center text-[12px] font-semibold text-rose-500">Data QRIS belum tersedia</p>';
      return;
    }

    el.innerHTML = '';

    if (isQrisImageUrl(URL_QRIS)) {
      var img = document.createElement('img');
      img.src = URL_QRIS;
      img.alt = 'QRIS Merchant';
      img.className = 'max-h-full max-w-full object-contain';
      img.onload = function () { qrRendered = true; };
      img.onerror = function () { showQrisRenderError(el); };
      el.appendChild(img);
      return;
    }

    try {
      if (typeof QRCode !== 'undefined' && typeof QRCode.toCanvas === 'function') {
        var canvas = document.createElement('canvas');
        el.appendChild(canvas);

        QRCode.toCanvas(canvas, URL_QRIS, {
          width: 220,
          margin: 1,
          errorCorrectionLevel: 'M',
          color: { dark: '#0f172a', light: '#ffffff' }
        }, function (err) {
          if (err) showQrisRenderError(el);
          else qrRendered = true;
        });
        return;
      }

      if (typeof QRCode !== 'undefined') {
        new QRCode(el, {
          text: URL_QRIS,
          width: 220,
          height: 220,
          colorDark: '#0f172a',
          colorLight: '#ffffff',
          correctLevel: QRCode.CorrectLevel ? QRCode.CorrectLevel.M : undefined
        });
        qrRendered = true;
        return;
      }
    } catch (e) {}

    showQrisRenderError(el);
  }

  function isQrisImageUrl(value) {
    return /^https?:\/\//i.test(value) && /\.(png|jpe?g|webp|gif|svg)(\?|#|$)/i.test(value);
  }

  function showQrisRenderError(el) {
    el.innerHTML = '<p class="px-4 text-center text-[12px] font-semibold text-rose-500">Gagal memuat QRIS</p>';
  }

  function openTarik() {
    switchTarik('saldo');
    openModal('modal-tarik');
    loadPaymentMetode();
    loadRekening();
  }

  function switchTarik(tab) {
    $('.tarik-btn').removeClass('active');
    $('.tarik-btn[data-tarik="' + tab + '"]').addClass('active');
    $('.tarik-panel').addClass('hidden');
    $('#tarik-' + tab).removeClass('hidden');
  }

  /* ---------------- TABS ---------------- */
  function switchTab(tab) {
    $('.tab-btn').removeClass('active');
    $('.tab-btn[data-tab="' + tab + '"]').addClass('active');
    $('.tab-panel').addClass('hidden');
    $('#tab-' + tab).removeClass('hidden');

    if (tab === 'riwayat') {
      riwState = { page: 1, cache: [], cursor: 0, done: false, loaded: true };
      $('#riwayat-list').empty();
      $('#riwayat-empty').addClass('hidden');
      $('#riwayat-pager').addClass('hidden');
      fetchRiwayat();
      return;
    }

    if (tab === 'mutasi' && !mutState.loaded) { mutState.loaded = true; fetchMutasi(); }

    if (tab === 'penarikan') {
      penState = { page: 1, cache: [], cursor: 0, done: false, loaded: true };
      $('#penarikan-list').empty();
      $('#penarikan-empty').addClass('hidden');
      $('#penarikan-pager').addClass('hidden');
      fetchPenarikan();
    }
  }

  function gotoNext(state, fetchFn, renderFn) {
    var need = state.page * LIMIT;
    if (state.cache.length <= need && !state.done) { state.page++; fetchFn(); return; }
    if (state.cache.length > need) { state.page++; renderFn(); }
  }

  function renderListSkeleton(selector, count) {
    var html = '';
    count = count || 3;
    for (var i = 0; i < count; i++) {
      html +=
        '<div class="bg-white rounded-2xl border border-slate-200 p-3 flex items-center justify-between gap-3">' +
          '<div class="flex items-center gap-2.5 min-w-0 flex-1">' +
            '<div class="qris-shimmer h-10 w-10 rounded-lg shrink-0"></div>' +
            '<div class="min-w-0 flex-1 space-y-2">' +
              '<div class="qris-shimmer h-3.5 w-2/3 rounded-full"></div>' +
              '<div class="qris-shimmer h-3 w-1/2 rounded-full"></div>' +
              '<div class="qris-shimmer h-3 w-1/3 rounded-full"></div>' +
            '</div>' +
          '</div>' +
          '<div class="shrink-0 space-y-2 text-right">' +
            '<div class="qris-shimmer h-3.5 w-20 rounded-full"></div>' +
            '<div class="qris-shimmer h-3 w-14 rounded-full ml-auto"></div>' +
          '</div>' +
        '</div>';
    }
    $(selector).html(html);
  }

  function showListLoading(selector) {
    renderListSkeleton(selector, 7);
    $(selector).removeClass('hidden');
  }

  function hideListLoading(selector) {
    $(selector).addClass('hidden').empty();
  }

  /* ---------------- RIWAYAT TRANSAKSI ---------------- */
  function fetchRiwayat() {
    $('#riwayat-list').empty();
    $('#riwayat-empty').addClass('hidden');
    showListLoading('#riwayat-loading');
    $('#riwayat-pager').addClass('hidden');
    $.ajax({
      url: 'index.php', method: 'POST', dataType: 'json', timeout: 20000,
      data: { act: 'riwayat', csrf: csrf, limit: LIMIT, last_id: riwState.cursor, tipe_status: 1 },
      success: function (d) {
        hideListLoading('#riwayat-loading');
        var rows = (d && d.data) ? d.data : [];
        if (rows.length) {
          riwState.cache = riwState.cache.concat(rows);
          riwState.cursor = d.last_id || riwState.cursor;
          if (rows.length < LIMIT) riwState.done = true;
        } else { riwState.done = true; }
        renderRiwayat();
      },
      error: function () { hideListLoading('#riwayat-loading'); showToast('Gagal memuat transaksi', 'error'); }
    });
  }

  function renderRiwayat() {
    var start = (riwState.page - 1) * LIMIT;
    var slice = riwState.cache.slice(start, start + LIMIT);
    var $list = $('#riwayat-list').empty();
    if (!riwState.cache.length) {
      $('#riwayat-empty').removeClass('hidden'); $('#riwayat-pager').addClass('hidden'); return;
    }
    $('#riwayat-empty').addClass('hidden');
    slice.forEach(function (r) {
      var icon = r.icon ? r.icon : 'https://ui-avatars.com/api/?background=random&name=' + encodeURIComponent(r.merchant || 'Q');
      $list.append(
        '<div class="qris-row bg-white rounded-2xl border border-slate-200 p-3 flex items-center justify-between gap-3" style="cursor:pointer">' +
          '<div class="flex items-center gap-2.5 min-w-0">' +
            '<img class="h-10 w-10 rounded-lg object-cover shrink-0" src="' + icon + '" alt="" />' +
            '<div class="min-w-0">' +
              '<p class="text-[13px] font-semibold text-slate-800 truncate">' + esc(r.pengirim || '-') + '</p>' +
              '<p class="text-[11px] text-slate-400 truncate">' + esc(r.no_pengirim || '') + '</p>' +
              '<p class="text-[11px] text-slate-400">' + fmtDate(r.created_at) + '</p>' +
            '</div>' +
          '</div>' +
          '<div class="text-right shrink-0">' +
            '<p class="text-[13px] font-bold text-emerald-600">+' + rupiah(r.total_diterima) + '</p>' +
            '<p class="text-[11px] text-slate-400">' + rupiah(r.nominal) + '</p>' +
          '</div>' +
        '</div>'
      ).children().last().on('click', function () { showDetailTrx(r); });
    });
    $('#riwayat-pager').removeClass('hidden');
    $('#riw-page').text('Hal ' + riwState.page);
    $('#riw-prev').prop('disabled', riwState.page <= 1);
    $('#riw-next').prop('disabled', riwState.done && (riwState.page * LIMIT >= riwState.cache.length));
  }

  function showDetailTrx(r) {
    var html =
      '<div class="space-y-2">' +
        row('Pengirim', esc(r.pengirim || '-')) +
        row('No. Pengirim', esc(r.no_pengirim || '-')) +
        row('Merchant', esc(r.merchant || '-')) +
        row('Trx ID', esc(r.trx_id || '-')) +
        row('Waktu', fmtDate(r.created_at)) +
        row('Status', esc(r.status_text || '-')) +
        '<div class="border-t border-slate-100 my-2"></div>' +
        row('Nominal', rupiah(r.nominal)) +
        row('Biaya Admin', '- ' + rupiah(r.biaya_admin)) +
        rowStrong('Total Diterima', '+ ' + rupiah(r.total_diterima)) +
      '</div>';
    $('#detail-trx-body').html(html);
    openModal('modal-detail-trx');
  }

  /* ---------------- MUTASI ---------------- */
  function fetchMutasi() {
    var requestTipe = mutState.tipe;
    var requestCursor = mutState.cursor;

    if (mutasiXhr && mutasiXhr.readyState !== 4) mutasiXhr.abort();

    $('#mutasi-list').empty();
    $('#mutasi-empty').addClass('hidden');
    showListLoading('#mutasi-loading');
    $('#mutasi-pager').addClass('hidden');
    mutasiXhr = $.ajax({
      url: 'index.php', method: 'POST', dataType: 'json', timeout: 20000,
      data: { act: 'mutasi', csrf: csrf, limit: LIMIT, last_id: requestCursor, tipe: requestTipe },
      success: function (d) {
        if (requestTipe !== mutState.tipe) return;
        hideListLoading('#mutasi-loading');
        var rows = (d && d.data) ? d.data : [];
        if (rows.length) {
          mutState.cache = mutState.cache.concat(rows);
          mutState.cursor = d.last_id || mutState.cursor;
          if (rows.length < LIMIT) mutState.done = true;
        } else { mutState.done = true; }
        renderMutasi();
      },
      error: function (xhr, status) {
        if (status === 'abort') return;
        if (requestTipe !== mutState.tipe) return;
        hideListLoading('#mutasi-loading');
        showToast('Gagal memuat mutasi', 'error');
      }
    });
  }

  function renderMutasi() {
    var start = (mutState.page - 1) * LIMIT;
    var slice = mutState.cache.slice(start, start + LIMIT);
    var $list = $('#mutasi-list').empty();
    if (!mutState.cache.length) {
      $('#mutasi-empty').removeClass('hidden'); $('#mutasi-pager').addClass('hidden'); return;
    }
    $('#mutasi-empty').addClass('hidden');
    slice.forEach(function (m) {
      var nominal = parseInt(m.nominal || 0, 10);
      var pos = nominal >= 0;
      var amountCls = pos ? 'text-emerald-600' : 'text-red-500';
      var sign = pos ? '+' : '-';
      $list.append(
        '<div class="bg-white rounded-2xl border border-slate-200 p-3 flex items-center justify-between gap-3">' +
          '<div class="min-w-0">' +
            '<p class="text-[13px] font-semibold text-slate-800 truncate">' + esc(m.deskripsi || m.kategori || '-') + '</p>' +
            '<p class="text-[11px] text-slate-400">' + fmtDate(m.created_at) + '</p>' +
          '</div>' +
          '<div class="text-right shrink-0">' +
            '<p class="text-[13px] font-bold ' + amountCls + '">' + sign + rupiah(Math.abs(nominal)) + '</p>' +
            '<p class="text-[11px] text-slate-400">Saldo: ' + rupiah(m.saldo_after) + '</p>' +
          '</div>' +
        '</div>'
      );
    });
    $('#mutasi-pager').removeClass('hidden');
    $('#mut-page').text('Hal ' + mutState.page);
    $('#mut-prev').prop('disabled', mutState.page <= 1);
    $('#mut-next').prop('disabled', mutState.done && (mutState.page * LIMIT >= mutState.cache.length));
  }

  /* ---------------- PENARIKAN (RIWAYAT TARIK DANA) ---------------- */
  function fetchPenarikan() {
    $('#penarikan-list').empty();
    $('#penarikan-empty').addClass('hidden');
    showListLoading('#penarikan-loading');
    $('#penarikan-pager').addClass('hidden');
    $.ajax({
      url: 'index.php', method: 'POST', dataType: 'json', timeout: 20000,
      data: { act: 'pencairan_list', csrf: csrf, limit: LIMIT, last_id: penState.cursor },
      success: function (d) {
        hideListLoading('#penarikan-loading');
        var rows = (d && d.data) ? d.data : [];
        if (rows.length) {
          penState.cache = penState.cache.concat(rows);
          penState.cursor = d.last_id || penState.cursor;
          if (rows.length < LIMIT) penState.done = true;
        } else { penState.done = true; }
        renderPenarikan();
      },
      error: function () { hideListLoading('#penarikan-loading'); showToast('Gagal memuat penarikan', 'error'); }
    });
  }

  function renderPenarikan() {
    var start = (penState.page - 1) * LIMIT;
    var slice = penState.cache.slice(start, start + LIMIT);
    var $list = $('#penarikan-list').empty();
    if (!penState.cache.length) {
      $('#penarikan-empty').removeClass('hidden'); $('#penarikan-pager').addClass('hidden'); return;
    }
    $('#penarikan-empty').addClass('hidden');
    slice.forEach(function (p) {
      $list.append(
        '<div class="qris-pen-row bg-white rounded-2xl border border-slate-200 p-3 flex items-center justify-between gap-3" style="cursor:pointer">' +
          '<div class="min-w-0">' +
            '<p class="text-[13px] font-semibold text-slate-800 truncate">' + esc(p.metode_payment || 'Penarikan') + '</p>' +
            '<p class="text-[11px] text-slate-400">' + fmtDate(p.created_at) + '</p>' +
            statusBadge(p.status) +
          '</div>' +
          '<div class="text-right shrink-0">' +
            '<p class="text-[13px] font-bold text-slate-800">' + rupiah(p.nominal) + '</p>' +
            '<p class="text-[11px] text-emerald-600">Diterima: ' + rupiah(p.total) + '</p>' +
          '</div>' +
        '</div>'
      ).children().last().on('click', function () { showDetailPenarikan(p.id); });
    });
    $('#penarikan-pager').removeClass('hidden');
    $('#pen-page').text('Hal ' + penState.page);
    $('#pen-prev').prop('disabled', penState.page <= 1);
    $('#pen-next').prop('disabled', penState.done && (penState.page * LIMIT >= penState.cache.length));
  }

  function statusBadge(status) {
    status = parseInt(status, 10);
    var map = {
      0: ['Menunggu', 'bg-amber-100 text-amber-700'],
      1: ['Diproses', 'bg-blue-100 text-blue-700'],
      2: ['Sukses', 'bg-emerald-100 text-emerald-700'],
      3: ['Gagal', 'bg-red-100 text-red-700']
    };
    var s = map[status] || ['-', 'bg-slate-100 text-slate-600'];
    return '<span class="inline-block mt-1 text-[10px] font-semibold px-2 py-0.5 rounded-full ' + s[1] + '">' + s[0] + '</span>';
  }

  function showDetailPenarikan(id) {
    showLoading('Memuat detail...');
    $.ajax({
      url: 'index.php', method: 'POST', dataType: 'json', timeout: 20000,
      data: { act: 'pencairan_detail', csrf: csrf, id: id },
      success: function (d) {
        hideLoading();
        if (d.status != 1) { showToast(d.error_msg || 'Gagal memuat detail', 'error'); return; }
        var p = d.data || {};
        var cancelBtn = (parseInt(p.status, 10) === 0)
          ? '<button id="btn-cancel-pen" class="mt-4 w-full rounded-xl border border-red-200 bg-red-50 py-2.5 text-[13px] font-semibold text-red-600 active:scale-[0.98] transition">Batalkan Penarikan</button>'
          : '';
        var html =
          '<div class="space-y-2">' +
            row('Metode', esc(p.metode_payment || '-')) +
            row('Waktu', esc(p.created_at || '-')) +
            row('Status', statusBadge(p.status)) +
            '<div class="border-t border-slate-100 my-2"></div>' +
            row('Nominal', rupiah(p.nominal)) +
            row('Biaya Admin', '- ' + rupiah(p.biaya_admin)) +
            rowStrong('Total Diterima', '+ ' + rupiah(p.total)) +
          '</div>' + cancelBtn;
        $('#detail-pen-body').html(html);
        openModal('modal-detail-pen');
        $('#btn-cancel-pen').on('click', function () { cancelPenarikan(p.id); });
      },
      error: function () { hideLoading(); showToast('Koneksi gagal', 'error'); }
    });
  }

  function cancelPenarikan(id) {
    if (!confirm('Batalkan penarikan ini?')) return;
    showLoading('Membatalkan...');
    $.ajax({
      url: 'index.php', method: 'POST', dataType: 'json', timeout: 20000,
      data: { act: 'pencairan_cancel', csrf: csrf, id: id },
      success: function (d) {
        hideLoading();
        if (d.status == 1) {
          showToast(d.message || 'Penarikan dibatalkan', 'success');
          closeModal('modal-detail-pen');
          setTimeout(function () { window.location.reload(); }, 1000);
        } else { showToast(d.error_msg || 'Gagal membatalkan', 'error'); }
      },
      error: function () { hideLoading(); showToast('Koneksi gagal', 'error'); }
    });
  }

  /* ---------------- TARIK DANA: metode & rekening ---------------- */
  function loadPaymentMetode() {
    if (paymentSaldo) return;
    $.ajax({
      url: 'index.php', method: 'POST', dataType: 'json', timeout: 20000,
      data: { act: 'payment_list', csrf: csrf },
      success: function (d) {
        if (d.status == 1 && d.data) {
          d.data.forEach(function (el) {
            if ((el.nama || '').toLowerCase().indexOf('aldo') !== -1) { paymentSaldo = el; }
          });
        }
      }
    });
  }

  function loadRekening() {
    $('#rekening-loading').removeClass('hidden');
    $('#rekening-empty').addClass('hidden');
    $('#rekening-ready').addClass('hidden');
    $.ajax({
      url: 'index.php', method: 'POST', dataType: 'json', timeout: 20000,
      data: { act: 'rekening_list', csrf: csrf },
      success: function (d) {
        $('#rekening-loading').addClass('hidden');
        rekeningUser = (d && d.data) ? d.data : [];
        if (!rekeningUser.length) { $('#rekening-empty').removeClass('hidden'); return; }
        var r0 = rekeningUser[0];
        var pm = r0.payment_method || {};
        var biaya = pm.biaya_persen && pm.biaya_persen !== 0
          ? 'sebesar ' + pm.biaya_persen + '%'
          : 'Rp' + (parseInt(pm.biaya_admin || 0, 10)).toLocaleString('id-ID');
        $('#rekening-info').html(
          '<p class="font-semibold mb-1">' + esc(pm.nama || 'Bank') + ' - ' + esc((r0.rekening_qris || {}).rekening || '') + '</p>' +
          '<p>a.n ' + esc((r0.rekening_qris || {}).nama_rekening || '-') + '</p>' +
          '<p class="mt-1">Biaya admin penarikan ' + biaya + '.</p>' +
          '<p>Jadwal penarikan jam ' + esc(pm.open || '-') + ' - ' + esc(pm.close || '-') + ' WIB.</p>'
        );
        $('#rekening-ready').removeClass('hidden');
        if (!pm.status) {
          $('#form-tarik-rekening').addClass('hidden');
          $('#rekening-closed').removeClass('hidden');
        } else {
          $('#form-tarik-rekening').removeClass('hidden');
          $('#rekening-closed').addClass('hidden');
        }
      },
      error: function () { $('#rekening-loading').addClass('hidden'); $('#rekening-empty').removeClass('hidden'); }
    });
  }

  function onNominalInput($el, mode) {
    var digits = $el.val().replace(/[^0-9]/g, '');
    $el.val(digits === '' ? '' : parseInt(digits, 10).toLocaleString('id-ID'));
    var n = parseInt(digits || 0, 10);
    var errSel = mode === 'saldo' ? '#err-nominal-saldo' : '#err-nominal-rekening';
    var masukSel = mode === 'saldo' ? '#masuk-nominal-saldo' : '#masuk-nominal-rekening';
    $(errSel).text('');
    if (n > SALDO_REAL) { $(errSel).text('Nominal melebihi saldo (' + rupiah(SALDO_REAL) + ')'); }

    if (n >= 10000) {
      var masuk = n;
      if (mode === 'saldo') { masuk = n - ADMIN_PENCAIRAN; }
      else if (rekeningUser.length) {
        var pm = rekeningUser[0].payment_method || {};
        if (pm.biaya_persen && pm.biaya_persen !== 0) masuk = n - (n * pm.biaya_persen / 100);
        else masuk = n - (parseInt(pm.biaya_admin || 0, 10));
      }
      $(masukSel).text('Diterima: ' + rupiah(masuk));
    } else { $(masukSel).text(''); }
  }

  function prosesPenarikan(mode) {
    var inputSel = mode === 'saldo' ? '#input-nominal-saldo' : '#input-nominal-rekening';
    var errSel = mode === 'saldo' ? '#err-nominal-saldo' : '#err-nominal-rekening';
    var total = parseInt(($(inputSel).val() || '').replace(/[^0-9]/g, '') || 0, 10);
    $(errSel).text('');

    var min = 0, max = 0, rekening_id = 0;
    if (mode === 'saldo') {
      if (!paymentSaldo) { showToast('Metode belum siap, coba lagi', 'error'); return; }
      min = parseInt(paymentSaldo.min || 0, 10); max = parseInt(paymentSaldo.max || 0, 10);
      rekening_id = 0;
    } else {
      if (!rekeningUser.length) { showToast('Belum ada rekening', 'error'); return; }
      var pm = rekeningUser[0].payment_method || {};
      min = parseInt(pm.min || 0, 10); max = parseInt(pm.max || 0, 10);
      rekening_id = (rekeningUser[0].rekening_qris || {}).id || 0;
    }

    if (total > SALDO_REAL) { $(errSel).text('Nominal melebihi saldo anda'); return; }
    if (min > 0 && total < min) { $(errSel).text('Minimal penarikan ' + rupiah(min)); return; }
    if (max > 0 && total > max) { $(errSel).text('Maksimal penarikan ' + rupiah(max)); return; }

    showLoading('Memproses penarikan...');
    $.ajax({
      url: 'index.php', method: 'POST', dataType: 'json', timeout: 30000,
      data: { act: 'pencairan', csrf: csrf, total: total, rekening_id: rekening_id },
      success: function (d) {
        hideLoading();
        if (d.status == 1) {
          showToast(d.message || 'Penarikan berhasil diproses', 'success');
          closeModal('modal-tarik');
          setTimeout(function () { window.location.reload(); }, 1200);
        } else { showToast(d.error_msg || 'Penarikan gagal', 'error'); }
      },
      error: function () { hideLoading(); showToast('Koneksi gagal, coba lagi', 'error'); }
    });
  }

  /* ---------------- helpers ---------------- */
  function row(label, val) {
    return '<div class="flex justify-between gap-3"><span class="text-slate-500">' + label + '</span><span class="text-slate-800 text-right">' + val + '</span></div>';
  }
  function rowStrong(label, val) {
    return '<div class="flex justify-between gap-3"><span class="text-slate-500">' + label + '</span><span class="font-bold text-brand text-right">' + val + '</span></div>';
  }
  function esc(s) { return $('<div>').text(s == null ? '' : String(s)).html(); }
  function rupiah(n) { n = parseInt(n || 0, 10); return 'Rp' + n.toLocaleString('id-ID'); }
  function fmtDate(s) {
    if (!s) return '-';
    var d = new Date(String(s).replace(' ', 'T'));
    if (isNaN(d.getTime())) return s;
    var bln = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    var p = function (x) { return x < 10 ? '0' + x : x; };
    return p(d.getDate()) + ' ' + bln[d.getMonth()] + ' ' + d.getFullYear() + ', ' + p(d.getHours()) + ':' + p(d.getMinutes());
  }
  function showLoading(txt) { $('#loading-text').text(txt || 'Memproses...'); $('#loading-overlay').css('display', 'flex'); }
  function hideLoading() { $('#loading-overlay').hide(); }
  function showToast(message, type) {
    var bgColor = type === 'success' ? '#10B981' : '#EF4444';
    var toast = document.createElement('div');
    toast.style.cssText = 'position:fixed;top:80px;left:50%;transform:translateX(-50%);background:' + bgColor + ';color:white;padding:12px 24px;border-radius:12px;font-size:14px;font-weight:600;z-index:999999;box-shadow:0 4px 12px rgba(0,0,0,0.15);animation:slideDown 0.3s ease;max-width:90%;text-align:center';
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(function () {
      toast.style.animation = 'slideUp 0.3s ease forwards';
      setTimeout(function () { toast.remove(); }, 300);
    }, 2500);
  }
</script>
