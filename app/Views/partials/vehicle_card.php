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

// Category → image-area gradient (elegant dark, teal-leaning)
$catGrad = [
    'scooter'    => ['#0c1a2e','#10263f','#1e4d6b'],
    'car'        => ['#08221f','#0c322f','#0e5c57'],
    'bike'       => ['#1c1208','#2e1f10','#5c3a18'],
    'rickshaw'   => ['#0a2424','#0e3232','#11595a'],
    'commercial' => ['#13191b','#1c2528','#3a474a'],
][$category] ?? ['#08221f','#0c322f','#0e5c57'];

// Category SVG icon path
$catIcon = [
    'car'    => 'M5 11l1.5-4.5A2 2 0 018.4 5h7.2a2 2 0 011.9 1.5L19 11m-14 0h14m-14 0v5a1 1 0 001 1h1m11-1a1 1 0 01-1 1h-1m-9 0a2 2 0 104 0m5 0a2 2 0 104 0',
    'scooter'=> 'M12 3a2 2 0 012 2v1h3l2 4H5l2-4h3V5a2 2 0 012-2zM5 10v4a1 1 0 001 1h1a3 3 0 006 0h1a1 1 0 001-1v-4H5z',
    'bike'   => 'M5 19a4 4 0 110-8 4 4 0 010 8zm14 0a4 4 0 110-8 4 4 0 010 8zm-9-4h4m1-8l-3 6',
][$category] ?? 'M5 11l1.5-4.5A2 2 0 018.4 5h7.2a2 2 0 011.9 1.5L19 11m-14 0h14m-14 0v5a1 1 0 001 1h1m11-1a1 1 0 01-1 1h-1m-9 0a2 2 0 104 0m5 0a2 2 0 104 0';

$imgSrc = !empty($vehicle['image_url'])
    ? esc($vehicle['image_url'])
    : base_url('assets/images/vehicles/'.esc($slug).'.webp');
?>

<a href="<?= site_url('vehicles/'.esc($slug)) ?>"
   class="vc-card group flex flex-col rounded-2xl overflow-hidden"
   style="background:#152b30;border:1px solid rgba(255,255,255,.06)">

  <!-- ── IMAGE AREA ── -->
  <div class="relative h-44 flex items-center justify-center overflow-hidden"
       style="background:linear-gradient(145deg,<?= $catGrad[0] ?>,<?= $catGrad[1] ?>)">

    <!-- dot grid -->
    <div class="absolute inset-0 pointer-events-none"
         style="background-image:radial-gradient(rgba(255,255,255,.16) 1px,transparent 1px);background-size:18px 18px;opacity:.3"></div>

    <!-- aurora glow blob -->
    <div class="absolute inset-0 aurora pointer-events-none rounded-full blur-3xl"
         style="background:radial-gradient(ellipse at 60% 40%,<?= $catGrad[2] ?>66,transparent 70%)"></div>

    <!-- actual image -->
    <img id="vi-<?= esc($slug) ?>"
         src="<?= $imgSrc ?>"
         alt="<?= esc($name) ?>"
         loading="lazy"
         class="absolute inset-0 w-full h-full object-contain p-4 opacity-0 transition-all duration-500 group-hover:scale-110"
         onload="this.classList.remove('opacity-0');document.getElementById('vf-<?= esc($slug) ?>').style.display='none'"
         onerror="this.style.display='none'">

    <!-- SVG fallback -->
    <div id="vf-<?= esc($slug) ?>" class="flex flex-col items-center gap-2 select-none">
      <svg class="w-14 h-14 transition-transform duration-500 group-hover:scale-110"
           style="color:<?= $catGrad[2] ?>;opacity:.6"
           fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="<?= $catIcon ?>"/>
      </svg>
      <span style="color:<?= $catGrad[2] ?>;opacity:.55;font-size:10px;font-weight:800;letter-spacing:.15em;text-transform:uppercase"><?= esc($brand ?: $category) ?></span>
    </div>

    <!-- top-left badges -->
    <div class="absolute top-2.5 left-2.5 flex flex-col gap-1 z-10">
      <?php if ($isFeatured): ?>
      <span class="text-[10px] font-black px-2.5 py-0.5 rounded-full tracking-wide"
            style="background:#009999;color:#fff">⭐ FEATURED</span>
      <?php endif; ?>
      <?php if ($isFast): ?>
      <span class="text-[10px] font-black px-2.5 py-0.5 rounded-full"
            style="background:#f59e0b;color:#0c1a1d">⚡ FAST CHARGE</span>
      <?php endif; ?>
    </div>

    <!-- rating -->
    <?php if ($rating > 0): ?>
    <div class="absolute top-2.5 right-2.5 z-10 flex items-center gap-1 px-2 py-1 rounded-full"
         style="background:rgba(12,26,29,.75);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.12)">
      <svg class="h-3 w-3 fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
      <span class="text-[11px] font-bold text-white"><?= number_format($rating,1) ?></span>
    </div>
    <?php endif; ?>

    <!-- brand logo -->
    <?php if ($logo): ?>
    <div class="absolute bottom-2.5 right-2.5 z-10 h-9 w-9 rounded-xl p-1.5 shadow-lg"
         style="background:rgba(255,255,255,.92);border:1px solid rgba(255,255,255,.2)">
      <img src="<?= base_url('assets/images/'.esc($logo)) ?>" alt="<?= esc($brand) ?>" class="h-full w-full object-contain">
    </div>
    <?php endif; ?>

    <!-- category chip -->
    <div class="absolute bottom-2.5 left-2.5 z-10">
      <span class="text-[9px] font-bold uppercase tracking-widest px-2 py-0.5 rounded-full"
            style="background:rgba(12,26,29,.7);color:rgba(255,255,255,.6);border:1px solid rgba(255,255,255,.12)"><?= ucfirst($category) ?></span>
    </div>
  </div>

  <!-- ── CARD BODY ── -->
  <div class="flex flex-col flex-1 p-4 pb-0">
    <p class="text-[11px] font-black uppercase tracking-widest mb-0.5" style="color:#16c4c4"><?= esc($brand) ?></p>
    <h3 class="font-black text-base leading-snug mb-3 line-clamp-2 transition-colors duration-200"
        style="color:#e6f1f1" onmouseover="this.style.color='#16c4c4'" onmouseout="this.style.color='#e6f1f1'"
    ><?= esc($name) ?></h3>

    <!-- stat pills -->
    <div class="flex gap-1.5 mb-3">
      <?php if ($range > 0): ?>
      <div class="flex-1 rounded-lg px-2 py-2 text-center" style="background:rgba(0,153,153,.12);border:1px solid rgba(0,153,153,.28)">
        <div class="text-sm font-black leading-none" style="color:#16c4c4"><?= $range ?></div>
        <div class="text-[9px] font-semibold mt-0.5" style="color:#0d9999">km</div>
      </div>
      <?php endif; ?>
      <?php if ($battery): ?>
      <div class="flex-1 rounded-lg px-2 py-2 text-center" style="background:rgba(56,189,248,.1);border:1px solid rgba(56,189,248,.2)">
        <div class="text-sm font-black leading-none" style="color:#7dd3fc"><?= esc($battery) ?></div>
        <div class="text-[9px] font-semibold mt-0.5" style="color:#38bdf8">kWh</div>
      </div>
      <?php endif; ?>
      <?php if ($emi > 0): ?>
      <div class="flex-1 rounded-lg px-2 py-2 text-center" style="background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1)">
        <div class="text-sm font-black leading-none" style="color:#cbd5e1">₹<?= number_format((int)($emi/1000)) ?>k</div>
        <div class="text-[9px] font-semibold mt-0.5" style="color:#94a3b8">EMI</div>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- ── PRICE FOOTER ── -->
  <div class="mx-3 mb-3 mt-0 rounded-xl flex items-center justify-between px-4 py-3"
       style="background:linear-gradient(135deg,#0c1a1d,#152b30);border:1px solid rgba(255,255,255,.06)">
    <div>
      <div class="text-[9px] font-bold uppercase tracking-widest" style="color:#5e7575">Ex-showroom</div>
      <div class="text-lg font-black" style="color:#e6f1f1"><?= vcFmt($price) ?></div>
    </div>
    <!-- animated arrow on hover -->
    <div class="flex items-center gap-1.5 text-[11px] font-black uppercase tracking-wider rounded-lg px-3 py-1.5 opacity-0 group-hover:opacity-100 transition-all duration-300 -translate-x-2 group-hover:translate-x-0"
         style="background:#009999;color:#fff">
      View
      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
    </div>
  </div>

</a>
