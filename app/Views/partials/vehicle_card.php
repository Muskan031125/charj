<?php
$price    = $vehicle['ex_showroom_price'] ?? $vehicle['starting_price'] ?? 0;
$range    = (int)($vehicle['range_km'] ?? $vehicle['real_world_range'] ?? $vehicle['claimed_range'] ?? 0);
$slug     = $vehicle['slug'] ?? '';
$name     = $vehicle['name'] ?? 'EV';
$brand    = $vehicle['brand_name'] ?? '';
$logo     = $vehicle['brand_logo'] ?? '';
$isFeatured = !empty($vehicle['is_featured']);
$isFast   = !empty($vehicle['is_fast_charge']) || !empty($vehicle['fast_charging_supported']);
$rating   = isset($vehicle['expert_rating']) && $vehicle['expert_rating'] > 0 ? (float)$vehicle['expert_rating'] : 0;
$battery  = $vehicle['battery_capacity'] ?? 0;
$category = strtolower($vehicle['category_name'] ?? $vehicle['category'] ?? 'scooter');

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

$catLabel = match($category) {
    'scooter'    => '🛵 Scooter',
    'car'        => '🚗 Car',
    'bike'       => '🏍️ Bike',
    'rickshaw'   => '🛺 E-Rick',
    'commercial' => '🚚 Comm.',
    default      => '⚡ EV',
};

$imgGrad = match($category) {
    'car'        => 'linear-gradient(145deg,#EFF6FF,#DBEAFE)',
    'bike'       => 'linear-gradient(145deg,#FFF7ED,#FFEDD5)',
    'rickshaw'   => 'linear-gradient(145deg,#F0FDF4,#DCFCE7)',
    'commercial' => 'linear-gradient(145deg,#F5F3FF,#EDE9FE)',
    default      => 'linear-gradient(145deg,#ECFDF5,#D1FAE5)',
};

$imgSrc    = !empty($vehicle['image_url'])
    ? esc($vehicle['image_url'])
    : base_url('assets/images/vehicles/'.esc($slug).'.webp');
$jsPrice   = (int)$price;
$detailUrl = $slug ? base_url('vehicles/'.esc($slug)) : base_url('vehicles');

// Safely encode for HTML attributes — prevents " chars from breaking x-data / @click bindings
$jsSlugJson = htmlspecialchars(json_encode($slug), ENT_QUOTES, 'UTF-8');
$jsNameJson = htmlspecialchars(json_encode($name), ENT_QUOTES, 'UTF-8');
?>

<div class="vc-card group flex flex-col rounded-2xl overflow-hidden"
   style="background:#FFFFFF;border:1px solid rgba(0,230,118,.12);box-shadow:0 2px 8px rgba(0,0,0,.05),0 6px 20px rgba(0,200,100,.05);transition:transform .22s cubic-bezier(.22,1,.36,1),box-shadow .22s ease,border-color .22s ease"
   x-data="{
     inCompare:  false,
     inWishlist: false,
     init() {
       var self = this;
       self.sync();
       document.addEventListener('charj:compare-update',  function(){ self.sync(); });
       document.addEventListener('charj:wishlist-update', function(){ self.sync(); });
     },
     sync() {
       this.inCompare  = (window.charjGetCompare?.()  || []).some(function(x){ return x.slug === <?= $jsSlugJson ?>; });
       this.inWishlist = (window.charjGetWishlist?.() || []).some(function(x){ return x.slug === <?= $jsSlugJson ?>; });
     }
   }">

  <!-- Top accent bar -->
  <div class="h-[3px] w-full flex-shrink-0" style="background:linear-gradient(90deg,#00E676,#69FF97,rgba(0,230,118,.15))"></div>

  <!-- ── IMAGE AREA ── -->
  <div class="relative flex items-center justify-center overflow-hidden flex-shrink-0 h-[140px] sm:h-[168px]"
       style="<?= $imgGrad ?>">

    <div class="absolute inset-0 pointer-events-none" style="background-image:radial-gradient(rgba(0,230,118,.07) 1px,transparent 1px);background-size:16px 16px"></div>

    <img id="vi-<?= esc($slug) ?>"
         src="<?= $imgSrc ?>"
         alt="<?= esc($name) ?>"
         loading="lazy"
         class="absolute inset-0 w-full h-full object-contain p-4 opacity-0"
         style="transition:opacity .4s ease,transform .3s ease"
         onload="this.style.opacity='1';this.classList.add('group-hover:scale-105');var f=document.getElementById('vf-<?= esc($slug) ?>');if(f)f.style.display='none'"
         onerror="this.style.display='none'">

    <div id="vf-<?= esc($slug) ?>" class="flex flex-col items-center gap-2 select-none z-10">
      <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background:linear-gradient(135deg,rgba(0,230,118,.18),rgba(0,230,118,.06));border:1.5px solid rgba(0,230,118,.2)">
        <svg class="w-9 h-9" style="color:#00C060;transition:transform .3s ease" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
      </div>
      <span style="color:rgba(0,200,100,.5);font-size:9px;font-weight:800;letter-spacing:.12em;text-transform:uppercase"><?= esc($brand ?: 'EV') ?></span>
    </div>

    <!-- Badges top-left -->
    <div class="absolute top-2.5 left-2.5 flex flex-col gap-1 z-10">
      <?php if ($isFeatured): ?>
      <span class="flex items-center gap-1 text-[10px] font-black px-2.5 py-1 rounded-full text-white" style="background:linear-gradient(135deg,#00C060,#009944);box-shadow:0 2px 8px rgba(0,200,100,.35)">
        ⭐ FEATURED
      </span>
      <?php endif; ?>
      <?php if ($isFast): ?>
      <span class="flex items-center gap-1 text-[10px] font-black px-2.5 py-1 rounded-full text-white" style="background:linear-gradient(135deg,#F59E0B,#D97706);box-shadow:0 2px 8px rgba(245,158,11,.3)">
        ⚡ FAST
      </span>
      <?php endif; ?>
    </div>

    <!-- Wishlist heart button -->
    <button type="button"
            class="absolute top-2.5 right-2.5 z-20 w-8 h-8 rounded-full flex items-center justify-center transition-all duration-200"
            :style="inWishlist
              ? 'background:#FFF0F3;border:1.5px solid rgba(239,68,68,.3);box-shadow:0 2px 8px rgba(239,68,68,.15)'
              : 'background:rgba(255,255,255,.92);border:1px solid rgba(0,0,0,.09);box-shadow:0 2px 6px rgba(0,0,0,.1)'"
            @click.stop="window.charjToggleWishlist?.(<?= $jsSlugJson ?>, <?= $jsNameJson ?>)"
            :title="inWishlist ? 'Remove from saved' : 'Save for later'">
      <svg x-show="inWishlist" x-cloak class="w-3.5 h-3.5" style="color:#ef4444" fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
      </svg>
      <svg class="w-3.5 h-3.5" :class="inWishlist ? 'hidden' : ''" style="color:#94A3B8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
      </svg>
    </button>

    <!-- Rating -->
    <?php if ($rating > 0): ?>
    <div class="absolute bottom-2.5 left-2.5 z-10 flex items-center gap-1 px-2 py-0.5 rounded-full"
         style="background:rgba(255,255,255,.95);border:1px solid rgba(245,158,11,.2);box-shadow:0 2px 6px rgba(0,0,0,.08)">
      <svg class="h-3 w-3 fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
      <span class="text-[11px] font-black" style="color:#0F172A"><?= number_format($rating,1) ?></span>
    </div>
    <?php endif; ?>

    <!-- Category + brand logo -->
    <div class="absolute bottom-2.5 right-2.5 z-10 flex items-center gap-1.5">
      <?php if ($logo): ?>
      <div class="h-7 w-7 rounded-lg p-0.5 flex items-center justify-center"
           style="background:#FFFFFF;border:1px solid rgba(0,0,0,.08);box-shadow:0 1px 4px rgba(0,0,0,.06)">
        <img src="<?= base_url('assets/images/'.esc($logo)) ?>" alt="<?= esc($brand) ?>" class="h-full w-full object-contain">
      </div>
      <?php endif; ?>
      <span class="text-[9px] font-bold uppercase px-2 py-0.5 rounded-full"
            style="background:rgba(255,255,255,.92);color:#00C060;border:1px solid rgba(0,200,100,.2)"><?= $catLabel ?></span>
    </div>
  </div>

  <!-- ── CARD BODY ── -->
  <div class="flex flex-col flex-1 px-3 sm:px-4 pt-3 sm:pt-4 pb-2">
    <p class="text-[10px] font-black uppercase tracking-widest mb-0.5" style="color:#00C060"><?= esc($brand) ?></p>
    <h3 class="font-black text-sm leading-snug mb-3 line-clamp-2" style="color:#0F172A"><?= esc($name) ?></h3>

    <!-- Stat pills — 3-col grid -->
    <div class="grid grid-cols-3 gap-1.5 mb-3">
      <div class="rounded-xl px-2 py-2.5 text-center" style="<?= $range > 0 ? 'background:linear-gradient(145deg,rgba(0,200,100,.09),rgba(0,200,100,.04));border:1px solid rgba(0,200,100,.15)' : 'background:rgba(0,0,0,.02);border:1px solid rgba(0,0,0,.06)' ?>">
        <div class="text-sm font-black leading-none mb-0.5" style="color:<?= $range > 0 ? '#00C060' : '#CBD5E1' ?>"><?= $range > 0 ? $range : '—' ?></div>
        <div class="text-[8px] sm:text-[9px] font-semibold uppercase tracking-wide" style="color:<?= $range > 0 ? '#64748B' : '#CBD5E1' ?>">km</div>
      </div>
      <div class="rounded-xl px-2 py-2.5 text-center" style="<?= $battery ? 'background:linear-gradient(145deg,rgba(14,165,233,.08),rgba(14,165,233,.04));border:1px solid rgba(14,165,233,.14)' : 'background:rgba(0,0,0,.02);border:1px solid rgba(0,0,0,.06)' ?>">
        <div class="text-sm font-black leading-none mb-0.5" style="color:<?= $battery ? '#0EA5E9' : '#CBD5E1' ?>"><?= $battery ?: '—' ?></div>
        <div class="text-[8px] sm:text-[9px] font-semibold uppercase tracking-wide" style="color:<?= $battery ? '#64748B' : '#CBD5E1' ?>">kWh</div>
      </div>
      <div class="rounded-xl px-2 py-2.5 text-center" style="<?= $emi > 0 ? 'background:linear-gradient(145deg,rgba(99,102,241,.07),rgba(99,102,241,.03));border:1px solid rgba(99,102,241,.12)' : 'background:rgba(0,0,0,.02);border:1px solid rgba(0,0,0,.06)' ?>">
        <div class="text-sm font-black leading-none mb-0.5" style="color:<?= $emi > 0 ? '#6366F1' : '#CBD5E1' ?>"><?= $emi > 0 ? '₹'.number_format((int)($emi/1000)).'k' : '—' ?></div>
        <div class="text-[8px] sm:text-[9px] font-semibold uppercase tracking-wide" style="color:<?= $emi > 0 ? '#64748B' : '#CBD5E1' ?>">EMI</div>
      </div>
    </div>
  </div>

  <!-- ── PRICE + VIEW CTA ── -->
  <div class="mx-3 mb-2 rounded-xl px-3.5 py-2.5 flex items-center justify-between"
       style="background:linear-gradient(135deg,#F0FFF4,#E6FFED);border:1px solid rgba(0,230,118,.14)">
    <div>
      <div class="text-[9px] font-bold uppercase tracking-widest" style="color:#94A3B8">Ex-showroom</div>
      <div class="text-lg font-black leading-none mt-0.5" style="color:#0F172A"><?= vcFmt($price) ?></div>
    </div>
    <a href="<?= $detailUrl ?>"
       class="flex items-center gap-1.5 text-xs font-black px-3 py-2 min-h-[44px] rounded-lg text-white transition-all duration-150"
       style="background:linear-gradient(135deg,#00C060,#009944);box-shadow:0 3px 10px rgba(0,200,100,.3)"
       onmouseover="this.style.boxShadow='0 5px 16px rgba(0,200,100,.45)'"
       onmouseout="this.style.boxShadow='0 3px 10px rgba(0,200,100,.3)'">
      View
      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
    </a>
  </div>

  <!-- ── COMPARE STRIP ── -->
  <div class="mx-3 mb-3 rounded-xl overflow-hidden transition-all duration-200"
       :style="inCompare
         ? 'background:rgba(0,230,118,.1);border:1.5px solid rgba(0,230,118,.3)'
         : 'background:rgba(0,0,0,.02);border:1px solid rgba(0,230,118,.1)'"
       @click.stop>
    <button type="button"
            class="w-full flex items-center justify-center gap-2 py-2.5 min-h-[44px] text-[11px] font-bold transition-colors duration-200"
            :style="inCompare ? 'color:#00C060' : 'color:#64748B'"
            @click.stop="window.charjToggleCompare?.(<?= $jsSlugJson ?>, <?= $jsNameJson ?>, <?= $jsPrice ?>)">
      <svg class="w-3.5 h-3.5 flex-shrink-0" :class="inCompare ? 'hidden' : ''" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
      </svg>
      <svg x-show="inCompare" x-cloak class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
      </svg>
      <span x-text="inCompare ? '✓ Added to Compare' : 'Add to Compare'">Add to Compare</span>
    </button>
  </div>

</div>
