<?php
// Partial modal untuk halaman QRIS. Di-include dari index.php.
// Variabel $merchant_name, $nmid, $url_qris, $download_qr_link, $admin_pencairan,
// $saldo_real, $csrf tersedia dari scope index.php.
// Guard: cegah akses langsung (harus di-include dari index.php).
if (!defined('ROOT')) { http_response_code(403); exit('Forbidden'); }
?>
<!-- ===================== MODAL: LIHAT QRIS ===================== -->
<div id="modal-qris" class="qris-modal" style="display:none;">
  <div class="qris-modal-card">
    <button class="qris-modal-close" data-close="modal-qris" aria-label="Tutup">&times;</button>
    <div class="text-center">
      <img class="h-8 mx-auto mb-4" src="/qris.png" alt="QRIS" />
      <div id="qris-canvas" class="mx-auto flex items-center justify-center bg-white p-2 rounded-xl border border-slate-100" style="width:240px;height:240px;"></div>
      <h3 class="mt-3 text-[16px] font-bold text-slate-800"><?= htmlspecialchars($merchant_name, ENT_QUOTES, 'UTF-8') ?></h3>
      <p class="text-[12px] text-slate-500">NMID : <?= htmlspecialchars($nmid, ENT_QUOTES, 'UTF-8') ?></p>
      <a href="<?= htmlspecialchars($download_qr_link, ENT_QUOTES, 'UTF-8') ?>" class="mt-5 flex items-center justify-center gap-2 rounded-xl bg-brand py-3 text-[14px] font-bold text-white active:scale-[0.98] transition">
        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12M7 11l5 5 5-5"/><path d="M5 21h14"/></svg>
        Download QRIS
      </a>
    </div>
  </div>
</div>

<!-- ===================== MODAL: INFO AKUN ===================== -->
<div id="modal-info" class="qris-modal" style="display:none;">
  <div class="qris-modal-card">
    <button class="qris-modal-close" data-close="modal-info" aria-label="Tutup">&times;</button>
    <div class="flex items-center gap-4">
      <img class="h-14 w-14 rounded-full shrink-0" src="https://ui-avatars.com/api/?background=0063F8&color=fff&name=<?= rawurlencode($merchant_name) ?>" alt="" />
      <div class="min-w-0">
        <p class="text-[12px] text-slate-500">Akun QRIS Mitra</p>
        <h3 class="text-[16px] font-bold text-slate-800 truncate"><?= htmlspecialchars($merchant_name, ENT_QUOTES, 'UTF-8') ?></h3>
        <p class="text-[12px] text-slate-500">NMID : <?= htmlspecialchars($nmid, ENT_QUOTES, 'UTF-8') ?></p>
      </div>
    </div>
    <div class="mt-4 space-y-2 text-[13px]">
      <div class="flex justify-between border-b border-slate-100 pb-2">
        <span class="text-slate-500">Saldo Real</span>
        <span class="font-semibold text-slate-800"><?= $app->idr($saldo_real) ?></span>
      </div>
      <div class="flex justify-between">
        <span class="text-slate-500">Saldo Kliring</span>
        <span class="font-semibold text-slate-800"><?= $app->idr($saldo_kliring) ?></span>
      </div>
    </div>
  </div>
</div>

<!-- ===================== MODAL: TARIK DANA ===================== -->
<div id="modal-tarik" class="qris-modal" style="display:none;">
  <div class="qris-modal-card">
    <button class="qris-modal-close" data-close="modal-tarik" aria-label="Tutup">&times;</button>
    <h3 class="text-[16px] font-bold text-slate-800 mb-3">Tarik Dana</h3>

    <!-- Sub-tabs: Stok Bukakios / Rekening -->
    <div class="grid grid-cols-2 gap-2 mb-4 text-[12px] font-semibold">
      <button data-tarik="saldo" class="tarik-btn rounded-xl py-2 border transition">Stok Bukakios</button>
      <button data-tarik="rekening" class="tarik-btn rounded-xl py-2 border transition">Rekening</button>
    </div>

    <!-- Panel: STOK BUKAKIOS -->
    <div id="tarik-saldo" class="tarik-panel">
      <div class="rounded-xl bg-blue-50 border border-blue-100 p-3 text-[12px] text-blue-700 mb-3">
        <p>Penarikan ke Stok Bukakios masuk otomatis secara realtime.</p>
        <?php if ($admin_pencairan > 0): ?>
        <p class="mt-1">Biaya admin penarikan ke stok sebesar <strong><?= $app->idr($admin_pencairan) ?></strong>.</p>
        <?php endif; ?>
      </div>
      <form id="form-tarik-saldo">
        <label class="text-[12px] font-medium text-slate-500">Masukkan Nominal</label>
        <input id="input-nominal-saldo" inputmode="numeric" autocomplete="off"
          class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-[15px] focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20"
          placeholder="0" />
        <div class="flex justify-between mt-1 min-h-[18px]">
          <p id="err-nominal-saldo" class="text-[11px] text-red-500"></p>
          <p id="masuk-nominal-saldo" class="text-[11px] text-slate-500 text-right"></p>
        </div>
        <button type="submit" class="mt-3 w-full rounded-xl bg-brand py-3 text-[14px] font-bold text-white active:scale-[0.98] transition disabled:opacity-50">Tarik Dana</button>
      </form>
    </div>

    <!-- Panel: REKENING -->
    <div id="tarik-rekening" class="tarik-panel hidden">
      <div id="rekening-loading" class="py-6 text-center">
        <div class="inline-block w-7 h-7 border-4 border-brand/20 border-t-brand rounded-full animate-spin"></div>
      </div>

      <!-- Belum ada rekening -->
      <div id="rekening-empty" class="hidden">
        <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 text-[12px] text-amber-700">
          <p class="font-semibold mb-1">Belum ada rekening terdaftar</p>
          <p>Penambahan atau perubahan rekening dilakukan melalui aplikasi BukaKios. Buka menu QRIS di aplikasi untuk menambah rekening.</p>
        </div>
      </div>

      <!-- Ada rekening -->
      <div id="rekening-ready" class="hidden">
        <div id="rekening-info" class="rounded-xl bg-blue-50 border border-blue-100 p-3 mb-3 text-[12px] text-slate-700"></div>
        <form id="form-tarik-rekening">
          <label class="text-[12px] font-medium text-slate-500">Masukkan Nominal</label>
          <input id="input-nominal-rekening" inputmode="numeric" autocomplete="off"
            class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-[15px] focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20"
            placeholder="0" />
          <div class="flex justify-between mt-1 min-h-[18px]">
            <p id="err-nominal-rekening" class="text-[11px] text-red-500"></p>
            <p id="masuk-nominal-rekening" class="text-[11px] text-slate-500 text-right"></p>
          </div>
          <button type="submit" class="mt-3 w-full rounded-xl bg-brand py-3 text-[14px] font-bold text-white active:scale-[0.98] transition disabled:opacity-50">Tarik Dana</button>
        </form>
        <div id="rekening-closed" class="hidden rounded-xl bg-red-50 border border-red-200 p-3 text-[12px] text-red-600 text-center mt-2">
          Penarikan ke rekening sedang ditutup.
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ===================== MODAL: DETAIL TRANSAKSI ===================== -->
<div id="modal-detail-trx" class="qris-modal" style="display:none;">
  <div class="qris-modal-card">
    <button class="qris-modal-close" data-close="modal-detail-trx" aria-label="Tutup">&times;</button>
    <h3 class="text-[16px] font-bold text-slate-800 mb-3">Detail Transaksi</h3>
    <div id="detail-trx-body" class="text-[13px]"></div>
  </div>
</div>

<!-- ===================== MODAL: DETAIL PENARIKAN ===================== -->
<div id="modal-detail-pen" class="qris-modal" style="display:none;">
  <div class="qris-modal-card">
    <button class="qris-modal-close" data-close="modal-detail-pen" aria-label="Tutup">&times;</button>
    <h3 class="text-[16px] font-bold text-slate-800 mb-3">Detail Penarikan</h3>
    <div id="detail-pen-body" class="text-[13px]"></div>
  </div>
</div>

<!-- Loading Overlay -->
<div id="loading-overlay" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:99999;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:16px;padding:24px;display:flex;flex-direction:column;align-items:center;gap:12px;box-shadow:0 20px 60px rgba(0,0,0,0.15);min-width:200px;">
    <div style="width:44px;height:44px;border:4px solid rgba(26,127,206,0.2);border-top-color:#1a7fce;border-radius:50%;animation:spin 0.8s linear infinite;"></div>
    <div id="loading-text" style="font-size:14px;font-weight:600;color:#334155;">Memproses...</div>
  </div>
</div>

<style>
  @keyframes spin { to { transform: rotate(360deg); } }
  @keyframes slideDown { from { top: 50px; opacity: 0; } to { top: 80px; opacity: 1; } }
  @keyframes slideUp { from { top: 80px; opacity: 1; } to { top: 50px; opacity: 0; } }
  .tab-btn.active, .mutasi-btn.active, .tarik-btn.active { background:#1a7fce; color:#fff; border-color:#1a7fce; }
  .tab-btn:not(.active) { color:#64748b; }
  .mutasi-btn:not(.active), .tarik-btn:not(.active) { color:#64748b; border-color:#e2e8f0; background:#fff; }
  .qris-modal { position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9998; align-items:center; justify-content:center; padding:16px; }
  .qris-modal-card { position:relative; background:#fff; border-radius:16px; padding:20px; width:100%; max-width:26rem; box-shadow:0 20px 60px rgba(0,0,0,0.18); max-height:90vh; overflow-y:auto; }
  .qris-modal-close { position:absolute; top:12px; right:14px; font-size:24px; line-height:1; color:#94a3b8; background:none; border:none; cursor:pointer; }
</style>
