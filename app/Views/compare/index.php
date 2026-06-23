<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<?php /* ============================================================
   EV Compare Page — Charj.in  |  CI4 · Tailwind · Alpine.js
   Best EV comparison experience in India.

   Controller provides:
     $vehicles   array   — 0-3 vehicle arrays (from ?ids=slug1,slug2)
     $title      string  — page title
   ============================================================ */ ?>

<?php
// Helper: format INR
if (!function_exists('fmtINR')) {
    function fmtINR(int|float|null $n): string {
        if ($n === null || $n === 0) return '—';
        if ($n >= 10000000) return '₹' . number_format($n / 10000000, 2) . ' Cr';
        if ($n >= 100000)   return '₹' . number_format($n / 100000, 2) . ' L';
        return '₹' . number_format((int)$n);
    }
}

// Helper: EMI calculation
if (!function_exists('calcEMI')) {
    function calcEMI(float $price, float $downPct = 0.20, float $annualRate = 9.0, int $months = 36): float {
        $principal = $price * (1 - $downPct);
        $r = ($annualRate / 12) / 100;
        if ($r == 0) return $principal / $months;
        return round($principal * $r * pow(1 + $r, $months) / (pow(1 + $r, $months) - 1));
    }
}

// Helper: monthly fuel (40km/day × 30 × rate/kWh ÷ range × kWh)
if (!function_exists('calcMonthlyFuel')) {
    function calcMonthlyFuel(float|null $kWh, float|null $rangeKm): float {
        if (!$kWh || !$rangeKm || $rangeKm == 0) return 0;
        $kmPerDay = 40;
        $ratePerKWh = 8;
        $kWhPer100km = ($kWh / $rangeKm) * 100;
        return round(($kWhPer100km / 100) * $kmPerDay * 30 * $ratePerKWh);
    }
}

// Helper: winner detection
function detectWinners(array $vals, string $mode): array {
    $out = array_fill(0, count($vals), false);
    $nums = [];
    foreach ($vals as $i => $v) {
        $n = preg_replace('/[^0-9.]/', '', (string)($v ?? ''));
        $nums[$i] = $n !== '' ? (float)$n : null;
    }
    $valid = array_filter($nums, fn($x) => $x !== null);
    if (count($valid) < 2) return $out;
    if ($mode === 'high') { $best = max($valid); }
    else                  { $best = min($valid); }
    foreach ($nums as $i => $n) {
        if ($n !== null && $n == $best) $out[$i] = true;
    }
    return $out;
}

// Helper: comparison row HTML
function cmpRow(string $label, array $vv, string $field, string $mode = 'text', string $unit = '', bool $formatPrice = false, bool $formatFn = false): string {
    $count = count($vv);
    $vals = array_map(fn($v) => $v[$field] ?? null, $vv);
    $winners = ($mode !== 'text') ? detectWinners($vals, $mode) : array_fill(0, $count, false);
    $labelW = 200;

    $html  = '<div class="grid divide-x divide-slate-100 hover:bg-slate-50/50 transition-colors"';
    $html .= ' style="grid-template-columns:' . $labelW . 'px repeat(' . $count . ',1fr)">';
    $html .= '<div class="px-4 py-3 flex items-center text-sm text-slate-500 font-medium bg-slate-50/70 leading-tight">'
           . htmlspecialchars($label) . '</div>';

    foreach ($vals as $i => $raw) {
        $isW = $winners[$i] ?? false;
        if ($raw === null || $raw === '' || $raw === 0) {
            $display = '—';
        } elseif ($formatPrice) {
            $display = fmtINR((float)$raw);
        } else {
            $display = htmlspecialchars((string)$raw) . ($unit ? ' ' . $unit : '');
        }
        $cellCls = $isW ? 'bg-green-50 border-l border-green-100' : 'border-l border-slate-50';
        $valCls  = $isW ? 'font-bold text-green-700' : 'font-semibold text-charj-navy';
        $html .= '<div class="px-4 py-3 text-sm text-center ' . $cellCls . '">';
        $html .= '<span class="' . $valCls . '">' . $display . '</span>';
        if ($isW && $mode !== 'text') {
            $html .= '<span class="ml-1.5 text-[10px] bg-charj-green text-white px-1.5 py-0.5 rounded-full font-bold align-middle">BEST</span>';
        }
        $html .= '</div>';
    }
    $html .= '</div>';
    return $html;
}

// Section header
function cmpSection(string $label, int $count): string {
    return '<div class="grid" style="grid-template-columns:200px repeat(' . $count . ',1fr)">'
         . '<div class="col-span-full bg-charj-navy/5 px-4 py-2 border-y border-charj-navy/10">'
         . '<span class="text-xs font-bold text-charj-navy uppercase tracking-wider">' . $label . '</span>'
         . '</div></div>';
}

$vehicles = $vehicles ?? [];
$count    = count($vehicles);
$hasComparison = $count >= 2;

// Pre-compute per-vehicle derived values for verdict cards
$priceWinnerIdx = null;
$rangeWinnerIdx = null;
$cityWinnerIdx  = null; // lower weight = better city commuter (proxy: lower starting price)
if ($hasComparison) {
    $prices  = array_map(fn($v) => (float)($v['starting_price'] ?? 0), $vehicles);
    $ranges  = array_map(fn($v) => (float)($v['real_world_range'] ?? $v['claimed_range'] ?? 0), $vehicles);
    $weights = array_map(fn($v) => (float)($v['kerb_weight'] ?? 0), $vehicles);

    $validPrices  = array_filter($prices,  fn($x) => $x > 0);
    $validRanges  = array_filter($ranges,  fn($x) => $x > 0);
    $validWeights = array_filter($weights, fn($x) => $x > 0);

    if (count($validPrices)  >= 2) $priceWinnerIdx = array_search(min($validPrices),  $prices);
    if (count($validRanges)  >= 2) $rangeWinnerIdx = array_search(max($validRanges),  $ranges);
    // City commute: lightest vehicle (fastest to charge proportionally, easier to manoeuvre)
    if (count($validWeights) >= 2) {
        $cityWinnerIdx = array_search(min($validWeights), $weights);
    } elseif (count($validPrices) >= 2) {
        // Fall back to cheapest if no weight data
        $cityWinnerIdx = $priceWinnerIdx;
    }
}

// Pre-calculate financials
$monthlyData = [];
foreach ($vehicles as $vv) {
    $emi  = $vv['starting_price'] ? calcEMI((float)$vv['starting_price']) : 0;
    $fuel = calcMonthlyFuel((float)($vv['battery_capacity'] ?? 0), (float)($vv['real_world_range'] ?? $vv['claimed_range'] ?? 0));
    $monthlyData[] = ['emi' => $emi, 'fuel' => $fuel, 'total' => $emi + $fuel];
}

// Overall winner (most category wins)
$winCounts = array_fill(0, $count, 0);
if ($hasComparison) {
    $metricFields = [
        ['real_world_range','high'],['starting_price','low'],['battery_capacity','high'],
        ['expert_rating','high'],['motor_power','high'],['top_speed','high'],
    ];
    foreach ($metricFields as [$f, $m]) {
        $ww = detectWinners(array_map(fn($v) => $v[$f] ?? null, $vehicles), $m);
        foreach ($ww as $i => $w) { if ($w) $winCounts[$i]++; }
    }
    // Monthly cost winner
    $totalWins = detectWinners(array_column($monthlyData, 'total'), 'low');
    foreach ($totalWins as $i => $w) { if ($w) $winCounts[$i]++; }

    $bestIdx = array_search(max($winCounts), $winCounts);
    $bestVeh = $vehicles[$bestIdx] ?? null;
}
?>

<style>
[x-cloak]{display:none!important}
.cmp-table{overflow-x:auto;-webkit-overflow-scrolling:touch}
.autocomplete-dropdown{max-height:280px;overflow-y:auto}
.slot-filled{border-color:#22C55E}
.star-filled{color:#f59e0b}
.star-empty{color:#e2e8f0}
#cmp-sticky-bar{position:fixed;top:0;left:0;right:0;z-index:40;transform:translateY(-100%);transition:transform .3s ease;background:#0d2137;border-bottom:2px solid #16a34a;box-shadow:0 4px 12px rgba(0,0,0,.35)}
#cmp-sticky-bar.visible{transform:translateY(0)}
</style>

<div x-data="compareApp()" x-init="init()" class="min-h-screen bg-slate-50">

<!-- ── STICKY VEHICLE NAME BAR (appears on scroll) ─────────── -->
<?php if ($hasComparison): ?>
<div id="cmp-sticky-bar" aria-hidden="true">
  <div class="max-w-7xl mx-auto px-4 py-2.5 flex items-center gap-4 overflow-x-auto">
    <span class="text-slate-400 text-xs font-bold uppercase tracking-widest flex-shrink-0 hidden sm:block">Comparing</span>
    <?php foreach ($vehicles as $vi => $vv): ?>
    <a href="<?= base_url('vehicles/' . esc($vv['slug'] ?? $vv['id'])) ?>"
       class="flex items-center gap-2 flex-shrink-0 group">
      <?php if (!empty($vv['featured_image'] ?? $vv['image_url'] ?? '')): ?>
      <img src="<?= esc($vv['featured_image'] ?? $vv['image_url']) ?>" alt="" class="h-8 w-12 object-contain rounded" loading="lazy">
      <?php endif; ?>
      <div>
        <p class="text-[10px] text-slate-400 leading-none"><?= esc($vv['brand_name'] ?? '') ?></p>
        <p class="text-white font-bold text-sm group-hover:text-green-400 transition-colors leading-tight"><?= esc($vv['name']) ?></p>
      </div>
      <?php if ($vi === ($bestIdx ?? -1)): ?>
      <span class="bg-amber-400 text-charj-navy text-[9px] font-extrabold px-1.5 py-0.5 rounded-full">TOP</span>
      <?php endif; ?>
    </a>
    <?php if ($vi < $count - 1): ?>
    <span class="text-slate-600 font-bold text-sm flex-shrink-0">vs</span>
    <?php endif; ?>
    <?php endforeach; ?>
    <a href="#cmp-table" class="ml-auto flex-shrink-0 bg-charj-green text-white text-xs font-bold px-3 py-1.5 rounded-lg hover:bg-green-700 transition-colors whitespace-nowrap">
      View Specs ↓
    </a>
  </div>
</div>
<script>
(function(){
  var bar = document.getElementById('cmp-sticky-bar');
  var trigger = document.getElementById('cmp-table');
  if (!bar || !trigger) return;
  var io = new IntersectionObserver(function(entries){
    entries.forEach(function(e){
      if (!e.isIntersecting) { bar.classList.add('visible'); bar.removeAttribute('aria-hidden'); }
      else                   { bar.classList.remove('visible'); bar.setAttribute('aria-hidden','true'); }
    });
  }, { threshold: 0 });
  io.observe(trigger);
})();
</script>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════════════
     HEADER
════════════════════════════════════════════════════════════════ -->
<div class="bg-charj-navy text-white py-10 px-4">
  <div class="max-w-7xl mx-auto">
    <nav class="text-sm mb-3 text-slate-400 flex items-center gap-1.5" aria-label="Breadcrumb">
      <a href="<?= base_url('/') ?>" class="hover:text-charj-green transition-colors">Home</a>
      <svg class="w-3 h-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      <span class="text-white">Compare EVs</span>
    </nav>
    <h1 class="text-3xl md:text-4xl font-extrabold mb-2">Compare Electric Vehicles in India</h1>
    <p class="text-slate-300 text-base">Side-by-side specs, real-world range, monthly costs &amp; more — the most detailed EV comparison.</p>
  </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-6 space-y-6">

  <!-- ════════════════════════════════════════════════════════════
       VEHICLE SELECTOR — 3 slots with autocomplete
  ═════════════════════════════════════════════════════════════ -->
  <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
    <div class="flex items-center justify-between mb-5">
      <h2 class="font-bold text-charj-navy text-lg">Select EVs to Compare</h2>
      <span class="text-xs text-slate-400 bg-slate-100 px-2.5 py-1 rounded-full">Up to 3 vehicles</span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <?php for ($slot = 0; $slot < 3; $slot++):
        $sv = $vehicles[$slot] ?? null;
      ?>
      <div class="relative"
           x-data="vehicleSearch(<?= $slot ?>, <?= $sv ? json_encode([
              'id'             => $sv['id'],
              'name'           => ($sv['brand_name'] ?? '') . ' ' . $sv['name'],
              'slug'           => $sv['slug'] ?? $sv['id'],
              'image'          => $sv['featured_image'] ?? $sv['image_url'] ?? '',
              'brand'          => $sv['brand_name'] ?? '',
              'starting_price' => $sv['starting_price'] ?? 0,
           ]) : 'null' ?>)">

        <?php if ($sv): ?>
        <!-- Pre-filled slot (server-side) -->
        <div class="slot-filled border-2 rounded-2xl p-4 text-center relative bg-green-50/30 h-full flex flex-col">
          <button @click="clearSlot()" aria-label="Remove <?= esc($sv['name']) ?>"
            class="absolute top-2 right-2 w-7 h-7 bg-red-100 text-red-500 rounded-full flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors text-xs font-bold z-10">
            ✕
          </button>
          <div class="flex-1 flex flex-col items-center justify-center">
            <?php if (!empty($sv['featured_image'] ?? $sv['image_url'] ?? '')): ?>
            <img src="<?= esc($sv['featured_image'] ?? $sv['image_url']) ?>" alt="<?= esc($sv['name']) ?>"
                 class="w-full h-24 object-contain mb-3">
            <?php else: ?>
            <div class="w-full h-24 flex items-center justify-center text-4xl mb-3 text-slate-300">🔋</div>
            <?php endif; ?>
            <p class="text-xs text-slate-400 mb-0.5 font-medium"><?= esc($sv['brand_name'] ?? '') ?></p>
            <p class="font-bold text-charj-navy text-sm leading-tight mb-1"><?= esc($sv['name']) ?></p>
            <p class="text-charj-green font-bold text-sm"><?= fmtINR($sv['starting_price'] ?? null) ?></p>
          </div>
        </div>

        <?php else: ?>
        <!-- Empty slot -->
        <div :class="focused ? 'border-charj-green ring-2 ring-charj-green/20' : 'border-dashed border-slate-300'"
             class="border-2 rounded-2xl p-4 transition-all min-h-[160px] flex flex-col"
             x-show="!selected">
          <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2.5">EV Slot <?= $slot + 1 ?></p>
          <div class="relative flex-1">
            <div class="relative">
              <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
              <input type="text"
                x-model="query"
                @input.debounce.350ms="search()"
                @focus="focused=true; if(query.length>=2) search()"
                @blur="setTimeout(() => { dropdown=[]; focused=false }, 200)"
                placeholder="Search EV name or brand..."
                class="w-full pl-9 pr-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-charj-green bg-slate-50 placeholder-slate-400">
              <div x-show="loading" class="absolute right-3 top-1/2 -translate-y-1/2">
                <div class="w-4 h-4 border-2 border-charj-green border-t-transparent rounded-full animate-spin"></div>
              </div>
            </div>

            <!-- Dropdown results -->
            <div x-show="dropdown.length > 0" x-cloak
                 class="autocomplete-dropdown absolute z-20 mt-1 left-0 right-0 bg-white border border-slate-200 rounded-xl shadow-2xl overflow-hidden">
              <template x-for="item in dropdown" :key="item.id">
                <button @mousedown.prevent="selectVehicle(item)"
                  class="w-full flex items-center gap-3 px-3 py-2.5 hover:bg-charj-navy group transition-colors text-left border-b border-slate-50 last:border-0">
                  <img :src="item.image || ''" :alt="item.name"
                       class="w-10 h-8 object-contain flex-shrink-0 bg-slate-100 rounded"
                       onerror="this.style.display='none'">
                  <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold truncate text-charj-navy group-hover:text-white" x-text="item.name"></p>
                    <p class="text-xs text-slate-400 group-hover:text-slate-300 truncate" x-text="(item.brand || '') + (item.starting_price ? ' · ' + formatPrice(item.starting_price) : '')"></p>
                  </div>
                  <svg class="w-4 h-4 text-slate-300 group-hover:text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </button>
              </template>
            </div>

            <!-- Empty state icon -->
            <div x-show="!query && !loading && dropdown.length === 0" class="mt-4 text-center text-slate-200">
              <div class="text-4xl mb-1">+</div>
              <p class="text-xs text-slate-400">Add an EV</p>
            </div>
          </div>
        </div>

        <!-- JS-selected slot -->
        <div x-show="selected" x-cloak
             class="slot-filled border-2 rounded-2xl p-4 text-center relative bg-green-50/30 h-full flex flex-col">
          <button @click="clearSlot()" aria-label="Remove vehicle"
            class="absolute top-2 right-2 w-7 h-7 bg-red-100 text-red-500 rounded-full flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors text-xs font-bold z-10">
            ✕
          </button>
          <div class="flex-1 flex flex-col items-center justify-center">
            <img :src="selected?.image || ''" :alt="selected?.name"
                 class="w-full h-20 object-contain mb-3 rounded"
                 onerror="this.style.display='none'"
                 x-show="selected?.image">
            <div x-show="!selected?.image" class="w-full h-20 flex items-center justify-center text-4xl text-slate-300 mb-3">🔋</div>
            <p class="font-bold text-charj-navy text-sm leading-tight mb-1" x-text="selected?.name"></p>
            <p class="text-charj-green font-bold text-sm" x-text="formatPrice(selected?.starting_price)"></p>
          </div>
        </div>
        <?php endif; ?>
      </div>
      <?php endfor; ?>
    </div>

    <div class="mt-5 flex flex-col sm:flex-row items-center justify-center gap-3">
      <button onclick="applyComparison()"
        class="inline-flex items-center gap-2 bg-charj-navy text-white px-8 py-3 rounded-xl font-bold hover:bg-charj-navy-light transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        Compare Selected EVs
      </button>
      <a href="<?= base_url('vehicles') ?>" class="text-sm text-charj-green hover:text-green-700 font-medium transition-colors">
        Browse all EVs →
      </a>
    </div>
  </div>

  <?php if ($hasComparison): ?>

  <!-- ════════════════════════════════════════════════════════════
       COMPARISON TABLE
  ═════════════════════════════════════════════════════════════ -->
  <div id="cmp-table" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="cmp-table">

      <!-- Vehicle header row -->
      <div class="grid border-b-2 border-slate-100"
           style="grid-template-columns:200px repeat(<?= $count ?>,1fr)">
        <div class="p-5 bg-charj-navy flex items-end">
          <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Specification</span>
        </div>
        <?php foreach ($vehicles as $vi => $vv):
          $imgSrc = $vv['featured_image'] ?? $vv['image_url'] ?? '';
        ?>
        <div class="p-5 text-center border-l border-slate-100 flex flex-col items-center bg-gradient-to-b from-white to-slate-50/50">
          <?php if ($imgSrc): ?>
          <img src="<?= esc($imgSrc) ?>" alt="<?= esc($vv['name']) ?>" class="h-24 w-full object-contain mb-3">
          <?php else: ?>
          <div class="h-24 w-full flex items-center justify-center text-4xl mb-3 text-slate-200">⚡</div>
          <?php endif; ?>
          <p class="text-[11px] text-slate-400 font-medium mb-0.5"><?= esc($vv['brand_name'] ?? '') ?></p>
          <p class="font-extrabold text-charj-navy text-sm leading-tight mb-1.5"><?= esc($vv['name']) ?></p>
          <p class="text-charj-green font-bold text-base mb-2"><?= fmtINR($vv['starting_price'] ?? null) ?></p>
          <?php if ($vi === ($bestIdx ?? -1) && $hasComparison): ?>
          <span class="bg-amber-400 text-charj-navy text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase tracking-wide mb-2">
            🏆 Top Pick
          </span>
          <?php endif; ?>
          <a href="<?= base_url('vehicles/' . esc($vv['slug'] ?? $vv['id'])) ?>"
             class="text-xs text-charj-green hover:text-green-700 font-medium underline-offset-2 hover:underline transition-colors">
            View Details →
          </a>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- ── PRICING ── -->
      <?= cmpSection('💰 Pricing', $count) ?>
      <?php
      foreach ([
        ['Starting Price',       'starting_price',      'low', '', true],
        ['Max Price (Top Trim)', 'max_price',            'low', '', true],
        ['Ex-showroom Delhi',    'ex_showroom_delhi',   'low', '', true],
      ] as [$lbl, $fld, $mode, $unit, $fp]):
        echo cmpRow($lbl, $vehicles, $fld, $mode, $unit, $fp);
      endforeach; ?>

      <!-- ── RANGE ── -->
      <?= cmpSection('📏 Range', $count) ?>
      <?php
      foreach ([
        ['Claimed Range (ARAI)', 'claimed_range',    'high', 'km'],
        ['Real-World Range',     'real_world_range', 'high', 'km'],
        ['City Range (est.)',    'city_range',       'high', 'km'],
        ['Highway Range (est.)', 'highway_range',    'high', 'km'],
      ] as [$lbl, $fld, $mode, $unit]):
        echo cmpRow($lbl, $vehicles, $fld, $mode, $unit);
      endforeach; ?>

      <!-- ── BATTERY ── -->
      <?= cmpSection('🔋 Battery', $count) ?>
      <?php
      foreach ([
        ['Battery Capacity',  'battery_capacity', 'high', 'kWh'],
        ['Battery Type',      'battery_type',     'text', ''],
        ['Battery Warranty',  'battery_warranty', 'text', ''],
        ['Charging Connector','connector_type',   'text', ''],
      ] as [$lbl, $fld, $mode, $unit]):
        echo cmpRow($lbl, $vehicles, $fld, $mode, $unit);
      endforeach; ?>

      <!-- ── CHARGING ── -->
      <?= cmpSection('⚡ Charging', $count) ?>
      <?php
      foreach ([
        ['AC Charging Time',   'ac_charging_time', 'low', 'hr'],
        ['DC Fast Charging',   'dc_charging_time', 'low', 'min'],
      ] as [$lbl, $fld, $mode, $unit]):
        echo cmpRow($lbl, $vehicles, $fld, $mode, $unit);
      endforeach;

      // Home charging cost row
      $count2 = count($vehicles);
      echo '<div class="grid divide-x divide-slate-100 hover:bg-slate-50/50 transition-colors" style="grid-template-columns:200px repeat('.$count2.',1fr)">';
      echo '<div class="px-4 py-3 flex items-center text-sm text-slate-500 font-medium bg-slate-50/70">Home Charging Cost</div>';
      $homeCosts = array_map(fn($v) => $v['battery_capacity'] ? round((float)$v['battery_capacity'] * 8) : null, $vehicles);
      $hWin = detectWinners($homeCosts, 'low');
      foreach ($homeCosts as $i => $hc) {
          $isW = $hWin[$i];
          echo '<div class="px-4 py-3 text-sm text-center ' . ($isW ? 'bg-green-50' : '') . '">';
          echo '<span class="' . ($isW ? 'font-bold text-green-700' : 'font-semibold text-charj-navy') . '">';
          echo $hc ? '₹' . number_format($hc) . '/full charge' : '—';
          echo '</span>';
          if ($isW) echo '<span class="ml-1.5 text-[10px] bg-charj-green text-white px-1.5 py-0.5 rounded-full font-bold">BEST</span>';
          echo '</div>';
      }
      echo '</div>';
      ?>

      <!-- ── PERFORMANCE ── -->
      <?= cmpSection('🏎 Performance', $count) ?>
      <?php
      foreach ([
        ['Motor Power',  'motor_power',   'high', 'kW'],
        ['Top Speed',    'top_speed',     'high', 'km/h'],
        ['0–60 kmph',   'acceleration',  'low',  'sec'],
      ] as [$lbl, $fld, $mode, $unit]):
        echo cmpRow($lbl, $vehicles, $fld, $mode, $unit);
      endforeach; ?>

      <!-- ── OWNERSHIP ── -->
      <?= cmpSection('🛡 Ownership', $count) ?>
      <?php
      foreach ([
        ['Vehicle Warranty', 'vehicle_warranty', 'text', ''],
        ['Battery Warranty', 'battery_warranty', 'text', ''],
        ['Service Centers',  'service_centers',  'high', 'approx.'],
      ] as [$lbl, $fld, $mode, $unit]):
        echo cmpRow($lbl, $vehicles, $fld, $mode, $unit);
      endforeach; ?>

      <!-- ── RATINGS ── -->
      <?= cmpSection('⭐ Ratings', $count) ?>
      <?php
      // Expert rating with visual stars
      $eRatings = array_map(fn($v) => (float)($v['expert_rating'] ?? 0), $vehicles);
      $eWin     = detectWinners($eRatings, 'high');
      $uRatings = array_map(fn($v) => (float)($v['user_rating'] ?? 0), $vehicles);
      $uWin     = detectWinners($uRatings, 'high');

      foreach ([['Expert Rating', $eRatings, $eWin], ['User Rating', $uRatings, $uWin]] as [$label, $ratings, $wins]):
      ?>
      <div class="grid divide-x divide-slate-100 hover:bg-slate-50/50 transition-colors"
           style="grid-template-columns:200px repeat(<?= $count ?>,1fr)">
        <div class="px-4 py-3 flex items-center text-sm text-slate-500 font-medium bg-slate-50/70"><?= $label ?></div>
        <?php foreach ($ratings as $i => $r):
          $isW = $wins[$i];
          $full = (int)floor($r);
          $half = ($r - $full) >= 0.5;
          $emp  = 5 - $full - ($half ? 1 : 0);
        ?>
        <div class="px-4 py-4 text-sm text-center <?= $isW ? 'bg-green-50' : '' ?>">
          <?php if ($r > 0): ?>
          <div class="flex items-center justify-center gap-0.5 mb-1" aria-label="<?= $r ?>/5 stars">
            <?php for ($s=0;$s<$full;$s++): ?><svg class="w-4 h-4 star-filled" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg><?php endfor; ?>
            <?php if ($half): ?><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" style="color:#f59e0b"><defs><linearGradient id="hg<?= $i ?>"><stop offset="50%" stop-color="#f59e0b"/><stop offset="50%" stop-color="#e2e8f0"/></linearGradient></defs><path fill="url(#hg<?= $i ?>)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg><?php endif; ?>
            <?php for ($s=0;$s<$emp;$s++): ?><svg class="w-4 h-4 star-empty" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg><?php endfor; ?>
          </div>
          <span class="<?= $isW ? 'font-bold text-green-700' : 'font-semibold text-charj-navy' ?> text-sm">
            <?= number_format($r, 1) ?>/5
          </span>
          <?php if ($isW): ?><span class="block mt-1 text-[10px] bg-charj-green text-white px-1.5 py-0.5 rounded-full font-bold inline-block">BEST</span><?php endif; ?>
          <?php else: ?><span class="text-slate-400 text-sm">—</span><?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endforeach; ?>

      <!-- ── MONTHLY COST (CALCULATED) ── -->
      <?= cmpSection('💼 Monthly Cost (Calculated)', $count) ?>

      <?php
      // EMI row
      $emis = array_column($monthlyData, 'emi');
      $emiW = detectWinners($emis, 'low');
      echo '<div class="grid divide-x divide-slate-100 hover:bg-slate-50/50 transition-colors" style="grid-template-columns:200px repeat('.$count.',1fr)">';
      echo '<div class="px-4 py-3 flex items-center text-sm text-slate-500 font-medium bg-slate-50/70 leading-tight">EMI <span class="text-xs text-slate-400 ml-1">(9% • 36M • 20% down)</span></div>';
      foreach ($emis as $i => $emi) {
          $isW = $emiW[$i];
          echo '<div class="px-4 py-3 text-sm text-center '.($isW?'bg-green-50':'').'">';
          echo '<span class="'.($isW?'font-bold text-green-700':'font-semibold text-charj-navy').'">'.($emi?'₹'.number_format($emi).'/mo':'—').'</span>';
          if ($isW) echo '<span class="ml-1.5 text-[10px] bg-charj-green text-white px-1.5 py-0.5 rounded-full font-bold">BEST</span>';
          echo '</div>';
      }
      echo '</div>';

      // Fuel cost row
      $fuels = array_column($monthlyData, 'fuel');
      $fuelW = detectWinners($fuels, 'low');
      echo '<div class="grid divide-x divide-slate-100 hover:bg-slate-50/50 transition-colors" style="grid-template-columns:200px repeat('.$count.',1fr)">';
      echo '<div class="px-4 py-3 flex items-center text-sm text-slate-500 font-medium bg-slate-50/70 leading-tight">Est. Charging Cost <span class="text-xs text-slate-400 ml-1">(40km/day)</span></div>';
      foreach ($fuels as $i => $fuel) {
          $isW = $fuelW[$i];
          echo '<div class="px-4 py-3 text-sm text-center '.($isW?'bg-green-50':'').'">';
          echo '<span class="'.($isW?'font-bold text-green-700':'font-semibold text-charj-navy').'">'.($fuel?'₹'.number_format($fuel).'/mo':'—').'</span>';
          if ($isW) echo '<span class="ml-1.5 text-[10px] bg-charj-green text-white px-1.5 py-0.5 rounded-full font-bold">CHEAPEST</span>';
          echo '</div>';
      }
      echo '</div>';

      // Total monthly cost row
      $totals = array_column($monthlyData, 'total');
      $totW   = detectWinners($totals, 'low');
      echo '<div class="grid divide-x divide-slate-100 bg-green-50/30 border-y-2 border-green-100" style="grid-template-columns:200px repeat('.$count.',1fr)">';
      echo '<div class="px-4 py-3.5 flex items-center text-sm font-bold text-charj-navy bg-green-50/50 leading-tight">Est. Total Monthly Cost</div>';
      foreach ($totals as $i => $tot) {
          $isW = $totW[$i];
          echo '<div class="px-4 py-3.5 text-sm text-center '.($isW?'bg-green-100/50':'').'">';
          echo '<span class="'.($isW?'font-extrabold text-green-700 text-base':'font-bold text-charj-navy text-sm').'">'.($tot?'₹'.number_format($tot).'/mo':'—').'</span>';
          if ($isW) echo '<span class="block mt-1 text-[10px] bg-green-600 text-white px-1.5 py-0.5 rounded-full font-bold inline-block">LOWEST COST</span>';
          echo '</div>';
      }
      echo '</div>';
      ?>

      <!-- Get Best Price row -->
      <div class="grid border-t-2 border-slate-100 bg-slate-50" style="grid-template-columns:200px repeat(<?= $count ?>,1fr)">
        <div class="px-4 py-4 text-sm font-bold text-charj-navy flex items-center">Get Best Price</div>
        <?php foreach ($vehicles as $vv): ?>
        <div class="px-4 py-4 text-center border-l border-slate-100">
          <a href="<?= base_url('vehicles/' . esc($vv['slug'] ?? $vv['id'])) ?>#lead-form"
             class="inline-flex items-center gap-1.5 bg-charj-green text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-charj-green-dark transition-colors shadow-sm">
            Get Price →
          </a>
        </div>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
  <!-- /comparison table -->

  <!-- ════════════════════════════════════════════════════════════
       WINNER SUMMARY CARD
  ═════════════════════════════════════════════════════════════ -->
  <?php if (isset($bestVeh) && $bestVeh): ?>
  <div class="bg-gradient-to-br from-charj-navy via-charj-navy-light to-[#1a3a5c] text-white rounded-2xl p-6 md:p-8 shadow-xl">
    <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
      <div class="flex-1">
        <div class="flex flex-wrap items-center gap-2 mb-3">
          <span class="text-2xl">🏆</span>
          <span class="bg-amber-400 text-charj-navy text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">
            Our Recommendation
          </span>
        </div>
        <h3 class="text-xl md:text-2xl font-extrabold mb-2 leading-tight">
          Based on this comparison,&nbsp;
          <span class="text-charj-green"><?= esc($bestVeh['name']) ?></span> wins overall
        </h3>
        <p class="text-slate-300 text-sm leading-relaxed mb-3">
          It leads on <strong class="text-white"><?= $winCounts[$bestIdx] ?></strong> out of <strong class="text-white"><?= count($metricFields) + 1 ?></strong> key metrics including range, value, performance and monthly cost — making it the better all-round choice for most Indian buyers.
        </p>
        <!-- Win counts per vehicle -->
        <div class="flex flex-wrap gap-3 mt-4">
          <?php foreach ($vehicles as $vi => $vv): ?>
          <div class="flex items-center gap-2 bg-white/10 rounded-xl px-3 py-2 border border-white/10">
            <span class="font-bold text-sm text-white"><?= esc($vv['name']) ?></span>
            <span class="bg-charj-green text-white text-xs font-bold px-2 py-0.5 rounded-full">
              <?= $winCounts[$vi] ?> wins
            </span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="flex-shrink-0 flex flex-col gap-3 w-full md:w-auto">
        <a href="<?= base_url('vehicles/' . esc($bestVeh['slug'] ?? $bestVeh['id'])) ?>"
           class="flex items-center justify-center gap-2 bg-charj-green text-white px-6 py-3.5 rounded-xl font-bold hover:bg-charj-green-dark transition-colors text-center whitespace-nowrap shadow-lg">
          Get the best price on <?= esc($bestVeh['name']) ?> →
        </a>
        <a href="<?= base_url('find-my-ev') ?>"
           class="flex items-center justify-center gap-2 bg-white/10 border border-white/20 text-white px-6 py-3 rounded-xl font-semibold hover:bg-white/20 transition-colors text-center whitespace-nowrap">
          🎯 Find My Perfect EV
        </a>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- ════════════════════════════════════════════════════════════
       BEST FOR CARDS
  ═════════════════════════════════════════════════════════════ -->
  <?php if ($hasComparison): ?>
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

    <!-- Best for Value -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
      <div class="flex items-center gap-2 mb-3">
        <span class="text-2xl">💰</span>
        <h3 class="font-bold text-slate-800 text-sm">Best for Value</h3>
      </div>
      <?php if ($priceWinnerIdx !== null && isset($vehicles[$priceWinnerIdx])): $pv = $vehicles[$priceWinnerIdx]; ?>
      <div class="flex items-center gap-3 mb-2">
        <?php if (!empty($pv['featured_image'] ?? $pv['image_url'] ?? '')): ?>
        <img src="<?= esc($pv['featured_image'] ?? $pv['image_url']) ?>" alt="" class="h-10 w-14 object-contain rounded" loading="lazy">
        <?php endif; ?>
        <div>
          <p class="font-bold text-charj-navy text-sm"><?= esc($pv['name']) ?></p>
          <p class="text-charj-green font-semibold text-sm"><?= fmtINR($pv['starting_price'] ?? null) ?></p>
        </div>
      </div>
      <p class="text-xs text-slate-500">Lowest starting price among compared vehicles — best entry point for budget-conscious buyers.</p>
      <?php else: ?><p class="text-xs text-slate-400">Not enough price data to determine.</p><?php endif; ?>
    </div>

    <!-- Best for Range -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
      <div class="flex items-center gap-2 mb-3">
        <span class="text-2xl">📏</span>
        <h3 class="font-bold text-slate-800 text-sm">Best for Range</h3>
      </div>
      <?php if ($rangeWinnerIdx !== null && isset($vehicles[$rangeWinnerIdx])): $rv = $vehicles[$rangeWinnerIdx]; $rng = (int)($rv['real_world_range'] ?? $rv['claimed_range'] ?? 0); ?>
      <div class="flex items-center gap-3 mb-2">
        <?php if (!empty($rv['featured_image'] ?? $rv['image_url'] ?? '')): ?>
        <img src="<?= esc($rv['featured_image'] ?? $rv['image_url']) ?>" alt="" class="h-10 w-14 object-contain rounded" loading="lazy">
        <?php endif; ?>
        <div>
          <p class="font-bold text-charj-navy text-sm"><?= esc($rv['name']) ?></p>
          <?php if ($rng > 0): ?><p class="text-charj-green font-semibold text-sm"><?= $rng ?> km range</p><?php endif; ?>
        </div>
      </div>
      <p class="text-xs text-slate-500">Highest real-world range — ideal for long commutes, inter-city travel and range-anxiety-free driving.</p>
      <?php else: ?><p class="text-xs text-slate-400">Not enough range data to determine.</p><?php endif; ?>
    </div>

    <!-- Best for City Commute -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
      <div class="flex items-center gap-2 mb-3">
        <span class="text-2xl">🏙️</span>
        <h3 class="font-bold text-slate-800 text-sm">Best for City Commute</h3>
      </div>
      <?php if ($cityWinnerIdx !== null && isset($vehicles[$cityWinnerIdx])): $cv = $vehicles[$cityWinnerIdx]; ?>
      <div class="flex items-center gap-3 mb-2">
        <?php if (!empty($cv['featured_image'] ?? $cv['image_url'] ?? '')): ?>
        <img src="<?= esc($cv['featured_image'] ?? $cv['image_url']) ?>" alt="" class="h-10 w-14 object-contain rounded" loading="lazy">
        <?php endif; ?>
        <div>
          <p class="font-bold text-charj-navy text-sm"><?= esc($cv['name']) ?></p>
          <?php if (!empty($cv['kerb_weight'])): ?><p class="text-slate-500 font-medium text-sm"><?= (int)$cv['kerb_weight'] ?> kg</p><?php endif; ?>
        </div>
      </div>
      <p class="text-xs text-slate-500">Lightest vehicle — easier to manoeuvre, faster to charge and most efficient in stop-go city traffic.</p>
      <?php else: ?><p class="text-xs text-slate-400">Not enough data to determine.</p><?php endif; ?>
    </div>

  </div>
  <?php endif; ?>

  <!-- NOT SURE BANNER -->
  <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 bg-charj-green rounded-2xl flex items-center justify-center flex-shrink-0 shadow-sm">
        <span class="text-2xl">🎯</span>
      </div>
      <div>
        <p class="font-bold text-charj-navy text-lg">Not sure which EV to pick?</p>
        <p class="text-slate-600 text-sm">Answer 5 quick questions and get a personalised EV recommendation — completely free.</p>
      </div>
    </div>
    <a href="<?= base_url('find-my-ev') ?>"
       class="flex-shrink-0 bg-charj-green text-white px-6 py-3.5 rounded-xl font-bold hover:bg-charj-green-dark transition-colors whitespace-nowrap shadow-sm">
      Find My EV →
    </a>
  </div>

  <?php else: ?>
  <!-- No vehicles yet — empty state -->
  <div class="text-center py-20 bg-white rounded-2xl border border-slate-100 shadow-sm">
    <div class="text-6xl mb-4">⚖️</div>
    <h3 class="text-2xl font-bold text-charj-navy mb-2">Compare up to 3 EVs Side-by-Side</h3>
    <p class="text-slate-500 text-sm mb-8 max-w-md mx-auto leading-relaxed">
      Search and add at least 2 electric vehicles above to see a detailed side-by-side comparison of specs, range, costs and more.
    </p>
    <a href="<?= base_url('vehicles') ?>"
       class="inline-flex items-center gap-2 bg-charj-navy text-white px-6 py-3.5 rounded-xl font-bold hover:bg-charj-navy-light transition-colors">
      ⚡ Browse All EVs
    </a>
  </div>
  <?php endif; ?>

</div><!-- /max-w-7xl -->
</div><!-- /x-data compareApp -->

<script>
function vehicleSearch(slot, preselected) {
  return {
    slot:     slot,
    query:    preselected ? preselected.name : '',
    dropdown: [],
    focused:  false,
    loading:  false,
    selected: preselected,

    async search() {
      if (this.query.length < 2) { this.dropdown = []; return; }
      this.loading = true;
      try {
        const res  = await fetch(`<?= base_url('api/vehicles/search') ?>?q=${encodeURIComponent(this.query)}&limit=8`);
        const data = await res.json();
        this.dropdown = data.vehicles || data || [];
      } catch(e) {
        this.dropdown = [];
      } finally {
        this.loading = false;
      }
    },

    selectVehicle(item) {
      this.selected = item;
      this.query    = item.name;
      this.dropdown = [];
      window._compareSlots = window._compareSlots || {};
      window._compareSlots[this.slot] = item.slug || item.id;
    },

    clearSlot() {
      this.selected = null;
      this.query    = '';
      window._compareSlots = window._compareSlots || {};
      delete window._compareSlots[this.slot];
    },

    formatPrice(p) {
      if (!p) return '';
      if (p >= 100000) return '₹' + (p / 100000).toFixed(2) + ' L';
      return '₹' + Number(p).toLocaleString('en-IN');
    }
  };
}

function compareApp() {
  return {
    init() {
      window._compareSlots = {};
      <?php foreach ($vehicles as $vi => $vv): ?>
      window._compareSlots[<?= $vi ?>] = '<?= esc($vv['slug'] ?? $vv['id']) ?>';
      <?php endforeach; ?>
    }
  };
}

function applyComparison() {
  const slots  = window._compareSlots || {};
  const allIds = [...new Set(Object.values(slots))].filter(Boolean).slice(0, 3);
  if (allIds.length < 2) {
    alert('Please select at least 2 EVs to compare.');
    return;
  }
  window.location = '<?= base_url('compare') ?>?ids=' + allIds.join(',');
}
</script>

<?= $this->endSection() ?>
