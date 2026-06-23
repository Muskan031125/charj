<?php
$price    = $vehicle['ex_showroom_price'] ?? $vehicle['starting_price'] ?? 0;
$range    = (int)($vehicle['range_km'] ?? $vehicle['real_world_range'] ?? $vehicle['claimed_range'] ?? 0);
$slug     = $vehicle['slug'] ?? '#';
$name     = $vehicle['name'] ?? 'EV';
$brand    = $vehicle['brand_name'] ?? '';
$logo     = $vehicle['brand_logo'] ?? '';
$isFeatured = !empty($vehicle['is_featured']);
$isFast   = !empty($vehicle['is_fast_charge']) || !empty($vehicle['fast_charging_supported']);
$rating   = isset($vehicle['expert_rating']) && $vehicle['expert_rating'] > 0 ? (float)$vehicle['expert_rating'] : 0;
$battery  = $vehicle['battery_capacity'] ?? 0;
$category = strtolower($vehicle['category_name'] ?? $vehicle['category'] ?? 'scooter');
$updated  = !empty($vehicle['updated_at']) ? $vehicle['updated_at'] : $vehicle['created_at'] ?? null;

// EMI: 9% pa, 36mo, 80% loan
$loan = $price * 0.8; $r = 0.09/12;
$emi  = $loan > 0 ? round(($loan * $r * pow(1+$r,36)) / (pow(1+$r,36)-1)) : 0;

if (!function_exists('vcFmt')) {
    function vcFmt($n) {
        if (!$n) return '—';
        $n = (int)$n;
        if ($n >= 10000000) return '₹'.round($n/10000000,1).' Cr';
        if ($n >= 100000)   return '₹'.round($n/100000,1).' L';
        return '₹'.number_format($n);
    }
}

$imgSrc = !empty($vehicle['image_url'])
    ? esc($vehicle['image_url'])
    : base_url('assets/images/vehicles/'.esc($slug).'.webp');
?>

<a href="<?= site_url('vehicles/'.esc($slug)) ?>"
   class="vc-card group flex flex-col rounded-3xl overflow-hidden"
   style="background:#ffffff;border:1.5px solid #d1fae5;box-shadow:0 8px 24px rgba(0,0,0,.05);transition:all 250ms cubic-bezier(0.4,0,0.2,1);"
   @mouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 20px 40px rgba(16,185,129,.12)';this.style.borderColor='#10b981'"
   @mouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.05)';this.style.borderColor='#d1fae5'">

  <!-- ── IMAGE AREA ── -->
  <div class="relative h-56 flex items-center justify-center overflow-hidden" style="background:linear-gradient(180deg,#e7f8ef 0%,#f3fbf7 100%)">

    <!-- actual image -->
    <img id="vi-<?= esc($slug) ?>"
         src="<?= $imgSrc ?>"
         alt="<?= esc($name) ?>"
         loading="lazy"
         class="absolute inset-0 w-full h-full object-contain p-6 opacity-0 transition-all duration-500"
         style="transition: opacity 500ms, transform 500ms"
         onload="this.classList.remove('opacity-0');document.getElementById('vf-<?= esc($slug) ?>').style.display='none'"
         onerror="this.style.display='none'">

    <!-- SVG fallback -->
    <div id="vf-<?= esc($slug) ?>" class="flex flex-col items-center gap-3 select-none">
      <svg class="w-20 h-20 transition-transform duration-500"
           style="color:#10b981;opacity:.3"
           fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 11l1.5-4.5A2 2 0 018.4 5h7.2a2 2 0 011.9 1.5L19 11m-14 0h14m-14 0v5a1 1 0 001 1h1m11-1a1 1 0 01-1 1h-1m-9 0a2 2 0 104 0m5 0a2 2 0 104 0"/>
      </svg>
      <span style="color:#6ee7b7;font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase"><?= esc($brand ?: $category) ?></span>
    </div>

    <!-- badges - top left -->
    <div class="absolute top-3 left-3 flex flex-col gap-2 z-10">
      <?php if ($isFeatured): ?>
      <span class="text-[10px] font-black px-3 py-1 rounded-full tracking-wide transition-all duration-300" style="background:#059669;color:#fff;animation:slideDown 500ms ease-out">🔥 Best Seller</span>
      <?php endif; ?>
      <?php if ($isFast): ?>
      <span class="text-[10px] font-black px-3 py-1 rounded-full transition-all duration-300" style="background:#10b981;color:#fff;animation:slideDown 600ms ease-out">⚡ Fast Charging</span>
      <?php endif; ?>
      <?php if ($rating > 4.5): ?>
      <span class="text-[10px] font-black px-3 py-1 rounded-full transition-all duration-300" style="background:#16a34a;color:#fff;animation:slideDown 700ms ease-out">🏆 Top Rated</span>
      <?php endif; ?>
    </div>

    <!-- rating - top right -->
    <?php if ($rating > 0): ?>
    <div class="absolute top-3 right-3 z-10 flex items-center gap-1.5 px-3 py-1.5 rounded-full transition-all duration-300"
         style="background:rgba(255,255,255,.95);border:1px solid #d1fae5;box-shadow:0 4px 12px rgba(0,0,0,.08)">
      <svg class="h-4 w-4 fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
      <span class="text-sm font-bold" style="color:#0f172a"><?= number_format($rating,1) ?></span>
    </div>
    <?php endif; ?>
  </div>

  <!-- ── CARD BODY ── -->
  <div class="flex flex-col flex-1 p-6">

    <!-- Brand + Category -->
    <div class="flex items-center justify-between mb-2">
      <p class="text-xs font-black uppercase tracking-widest" style="color:#6ee7b7"><?= esc($brand) ?></p>
      <span class="text-[10px] font-semibold uppercase tracking-wide px-2 py-1 rounded" style="background:#f0fdf4;color:#6ee7b7">⚡ <?= ucfirst($category) ?></span>
    </div>

    <!-- Vehicle Name - PRIMARY HIERARCHY -->
    <h3 class="font-black text-lg leading-tight mb-4 line-clamp-2 transition-colors duration-250"
        style="color:#0f172a"><?= esc($name) ?></h3>

    <!-- PRICE - HERO ELEMENT -->
    <div class="mb-6 pb-6 border-b" style="border-color:#d1fae5">
      <p class="text-xs font-semibold uppercase tracking-wide mb-1" style="color:#6ee7b7">Starting From</p>
      <div class="text-3xl font-black leading-none transition-transform duration-250 group-hover:scale-105" style="color:#059669;transform-origin:left"><?= vcFmt($price) ?></div>
    </div>

    <!-- Specs Grid - Clear Hierarchy -->
    <div class="space-y-3 mb-6">
      <!-- Range -->
      <?php if ($range > 0): ?>
      <div class="flex items-center justify-between p-3 rounded-lg transition-all duration-250" style="background:#f0fdf4;border:1px solid #e0f5ed">
        <div class="flex items-center gap-2">
          <span style="font-size:18px">⚡</span>
          <div>
            <p class="text-xs" style="color:#6ee7b7">Range</p>
            <p class="text-sm font-bold" style="color:#14532d"><?= $range ?> km</p>
          </div>
        </div>
        <span class="text-xs font-semibold" style="color:#6ee7b7">WLTP</span>
      </div>
      <?php endif; ?>

      <!-- Battery -->
      <?php if ($battery): ?>
      <div class="flex items-center justify-between p-3 rounded-lg transition-all duration-250" style="background:#f0fdf4;border:1px solid #e0f5ed">
        <div class="flex items-center gap-2">
          <span style="font-size:18px">🔋</span>
          <div>
            <p class="text-xs" style="color:#6ee7b7">Battery</p>
            <p class="text-sm font-bold" style="color:#14532d"><?= esc($battery) ?> kWh</p>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <!-- EMI -->
      <?php if ($emi > 0): ?>
      <div class="flex items-center justify-between p-3 rounded-lg transition-all duration-250" style="background:#f0fdf4;border:1px solid #e0f5ed">
        <div class="flex items-center gap-2">
          <span style="font-size:18px">💰</span>
          <div>
            <p class="text-xs" style="color:#6ee7b7">Monthly EMI</p>
            <p class="text-sm font-bold" style="color:#14532d">₹<?= number_format((int)($emi/1000)) ?>k</p>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>

    <!-- Trust Signals -->
    <div class="mb-6 pt-3 border-t" style="border-color:#e0f5ed">
      <div class="flex items-center gap-2 text-xs" style="color:#6ee7b7">
        <span>✓</span>
        <span class="font-semibold">Verified Specifications</span>
      </div>
      <?php if ($updated): ?>
      <div class="flex items-center gap-2 text-xs mt-1.5" style="color:#a0aaa8">
        <span>🔄</span>
        <span>Updated recently</span>
      </div>
      <?php endif; ?>
    </div>

    <!-- CTA Button -->
    <div class="mt-auto pt-4 border-t transition-all duration-250" style="border-color:#d1fae5">
      <div class="flex items-center justify-between text-sm font-bold uppercase tracking-wide transition-all duration-250 group-hover:text-white group-hover:px-3 group-hover:py-2 group-hover:rounded-lg"
           style="color:#10b981;padding:8px 0;gap:8px">
        <span>View Details</span>
        <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
      </div>
    </div>

  </div>

</a>

<style>
@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-8px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
