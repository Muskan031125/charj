<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<?php /* ============================================================
   Vehicle Listing — Charj.in  |  CI4 · Tailwind · Alpine.js
   Variables expected from controller:
     $title         string  — "All EVs in India" or "Electric Scooters in India"
     $subtitle      string  — optional subtitle
     $vehicles      array   — paginated vehicle rows
     $totalVehicles int     — total count before pagination
     $brands        array   — [{id, name, slug, count}]
     $categories    array   — [{id, name, slug, count}]
     $activeFilters array   — currently applied filter keys
     $pager         object  — CI4 Pager instance
   ============================================================ */ ?>

<style>
[x-cloak]{display:none!important}
.filter-chip:hover .chip-x{opacity:1}
.compare-bar-enter{animation:slideUp .3s cubic-bezier(.22,.68,0,1.2) both}
@keyframes slideUp{from{transform:translateY(100%);opacity:0}to{transform:translateY(0);opacity:1}}
.range-btn.active{background:#009999;color:#fff}
.price-btn.active{background:#009999;color:#fff}
.peer:checked ~ .range-pill{background:#009999!important;color:#fff}
.range-pill:hover{background:#009999!important}
</style>

<?php
$hasFilters = !empty($activeFilters) || !empty($_GET['q']) || !empty($_GET['brand'])
           || !empty($_GET['category']) || !empty($_GET['price_min']) || !empty($_GET['price_max'])
           || !empty($_GET['range_min']) || !empty($_GET['sort'] && $_GET['sort'] !== 'relevance');
$selectedBrands  = isset($_GET['brand'])    ? (array) $_GET['brand']    : [];
$selectedCat     = $_GET['category'] ?? '';
$selectedPriceMin= $_GET['price_min'] ?? '';
$selectedPriceMax= $_GET['price_max'] ?? '';
$selectedRange   = (int)($_GET['range_min'] ?? 0);
$selectedSort    = $_GET['sort'] ?? 'relevance';
$searchQ         = $_GET['q']   ?? '';

// Price range quick-selects
$priceRanges = [
    ['Under ₹50K',    0,       50000],
    ['₹50K–1L',       50000,   100000],
    ['₹1L–2L',        100000,  200000],
    ['₹2L–5L',        200000,  500000],
    ['₹5L–15L',       500000,  1500000],
    ['₹15L+',         1500000, ''],
];
$rangeOptions = [0 => 'Any', 50 => '50+ km', 100 => '100+ km', 150 => '150+ km', 200 => '200+ km'];

// Active filter chips for display
$chips = [];
if ($searchQ) $chips[] = ['label' => '🔍 "' . esc($searchQ) . '"', 'remove' => 'q'];
if ($selectedCat) {
    $catName = '';
    foreach (($categories ?? []) as $c) { if (($c['slug'] ?? '') === $selectedCat || ($c['id'] ?? '') == $selectedCat) { $catName = $c['name']; break; } }
    $chips[] = ['label' => '⚡ ' . ($catName ?: $selectedCat), 'remove' => 'category'];
}
foreach ($selectedBrands as $b) {
    $bName = '';
    foreach (($brands ?? []) as $br) { if (($br['slug'] ?? '') === $b || ($br['id'] ?? '') == $b) { $bName = $br['name']; break; } }
    $chips[] = ['label' => '🏷 ' . ($bName ?: $b), 'remove' => 'brand', 'val' => $b];
}
if ($selectedPriceMin || $selectedPriceMax) {
    $pLabel = ($selectedPriceMin ? '₹'.number_format((int)$selectedPriceMin/100000,1).'L' : '₹0') . '–' . ($selectedPriceMax ? '₹'.number_format((int)$selectedPriceMax/100000,1).'L' : 'Any');
    $chips[] = ['label' => '💰 ' . $pLabel, 'remove' => 'price'];
}
if ($selectedRange > 0) {
    $chips[] = ['label' => '📏 ' . $selectedRange . '+ km', 'remove' => 'range_min'];
}
if ($selectedSort && $selectedSort !== 'relevance') {
    $sortLabels = ['price_low'=>'Price: Low→High','price_high'=>'Price: High→Low','range'=>'Best Range','rating'=>'Top Rated','newest'=>'Newest'];
    $chips[] = ['label' => '↕ ' . ($sortLabels[$selectedSort] ?? $selectedSort), 'remove' => 'sort'];
}

// Build URL helper: current params minus a given key
function removeParam(string $key, ?string $val = null): string {
    $p = $_GET;
    if ($key === 'price') { unset($p['price_min'], $p['price_max']); }
    elseif ($key === 'brand' && $val !== null) {
        $arr = isset($p['brand']) ? (array)$p['brand'] : [];
        $arr = array_filter($arr, fn($x) => $x !== $val);
        $p['brand'] = array_values($arr);
        if (empty($p['brand'])) unset($p['brand']);
    } else { unset($p[$key]); }
    unset($p['page']);
    return '?' . http_build_query($p);
}
?>

<div
  x-data="vehicleListing()"
  x-init="init()"
  class="min-h-screen" style="background:#0c1a1d"
>

<!-- PAGE HEADER — animated dark hero -->
<div style="background:linear-gradient(135deg,#0a1628 0%,#0a2e2c 50%,#0c1a2e 100%)" class="relative overflow-hidden pt-28 pb-10 px-4">
  <!-- dot grid overlay -->
  <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image:radial-gradient(rgba(255,255,255,.5) 1px,transparent 1px);background-size:24px 24px"></div>
  <!-- glow blobs -->
  <div class="absolute top-0 right-1/3 w-96 h-48 bg-teal-500 opacity-[0.07] blur-3xl rounded-full pointer-events-none anim-grad" style="background:linear-gradient(135deg,#009999,#0d9488,#38bdf8)"></div>
  <div class="absolute bottom-0 left-1/4 w-72 h-40 bg-teal-400 opacity-[0.06] blur-3xl rounded-full pointer-events-none"></div>
  <!-- floating particles -->
  <div class="absolute top-10 left-10 w-2 h-2 bg-teal-400 rounded-full opacity-30 float-1 pointer-events-none"></div>
  <div class="absolute top-20 right-20 w-1.5 h-1.5 bg-teal-400 rounded-full opacity-25 float-2 pointer-events-none"></div>
  <div class="absolute bottom-10 left-1/2 w-2 h-2 bg-white rounded-full opacity-10 float-3 pointer-events-none"></div>
  <div class="absolute top-1/2 right-10 w-1 h-1 bg-teal-300 rounded-full opacity-20 float-1 pointer-events-none" style="animation-delay:2s"></div>

  <div class="relative max-w-7xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="text-xs mb-4 text-slate-500 flex items-center gap-1.5" aria-label="Breadcrumb">
      <a href="<?= base_url('/') ?>" class="hover:text-cyan-400 transition-colors text-slate-400">Home</a>
      <svg class="w-3 h-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      <span class="text-slate-300"><?= esc($title ?? 'All EVs') ?></span>
    </nav>

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
      <div>
        <div class="inline-flex items-center gap-2 bg-teal-500/15 border border-teal-500/25 rounded-full px-3 py-1 text-cyan-300 text-xs font-bold uppercase tracking-widest mb-3">
          ⚡ Charj.in EV Database
        </div>
        <h1 class="text-3xl sm:text-4xl font-black text-white leading-tight">
          <?= esc($title ?? 'All EVs in India') ?>
        </h1>
        <p class="text-slate-400 text-sm mt-2">
          <?= esc($subtitle ?? 'Compare prices, range & features across all electric vehicles') ?>
        </p>
        <!-- Quick stats -->
        <div class="flex flex-wrap gap-4 mt-5">
          <div class="glass rounded-xl px-4 py-2.5 text-center">
            <div class="text-xl font-black text-white">150+</div>
            <div class="text-[10px] text-slate-400 uppercase tracking-widest font-semibold">EVs Listed</div>
          </div>
          <div class="glass rounded-xl px-4 py-2.5 text-center">
            <div class="text-xl font-black text-cyan-400">₹59k</div>
            <div class="text-[10px] text-slate-400 uppercase tracking-widest font-semibold">Starting From</div>
          </div>
          <div class="glass rounded-xl px-4 py-2.5 text-center">
            <div class="text-xl font-black text-teal-400">700+km</div>
            <div class="text-[10px] text-slate-400 uppercase tracking-widest font-semibold">Best Range</div>
          </div>
        </div>
      </div>

      <div class="flex flex-wrap items-center gap-2 flex-shrink-0">
        <span class="rounded-full text-xs font-bold px-4 py-2 text-white shadow-lg" style="background:linear-gradient(135deg,#009999,#0d9488)">
          ⚡ <?= number_format($totalVehicles ?? count($vehicles ?? [])) ?> EVs
        </span>
        <?php if ($hasFilters): ?>
        <a href="<?= base_url('vehicles') ?>" class="rounded-full border border-white/20 text-white/60 hover:border-red-400/60 hover:text-red-300 text-xs font-medium px-3 py-2 transition-colors">
          ✕ Clear filters
        </a>
        <?php endif; ?>

        <!-- Sort -->
        <form method="GET" id="headerSortForm" class="hidden sm:flex items-center gap-2">
          <?php foreach ($_GET as $k => $v): if ($k === 'sort') continue; ?>
            <?php if (is_array($v)): foreach ($v as $vi): ?><input type="hidden" name="<?= esc($k) ?>[]" value="<?= esc($vi) ?>"><?php endforeach; else: ?><input type="hidden" name="<?= esc($k) ?>" value="<?= esc($v) ?>"><?php endif; ?>
          <?php endforeach; ?>
          <select name="sort" onchange="this.form.submit()"
            class="rounded-full border border-white/20 bg-white/10 text-white px-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-cyan-500 cursor-pointer backdrop-blur-sm">
            <option value="relevance"  <?= $selectedSort==='relevance' ?'selected':'' ?>>Sort: Relevance</option>
            <option value="price_low"  <?= $selectedSort==='price_low' ?'selected':'' ?>>Price: Low → High</option>
            <option value="price_high" <?= $selectedSort==='price_high'?'selected':'' ?>>Price: High → Low</option>
            <option value="range"      <?= $selectedSort==='range'     ?'selected':'' ?>>Best Range</option>
            <option value="rating"     <?= $selectedSort==='rating'    ?'selected':'' ?>>Top Rated</option>
            <option value="newest"     <?= $selectedSort==='newest'    ?'selected':'' ?>>Newest</option>
          </select>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════════════════
     MAIN LAYOUT — sidebar + content
════════════════════════════════════════════════════════════════ -->
<div class="mx-auto max-w-7xl px-4 sm:px-6 py-8">
  <div class="lg:grid lg:grid-cols-[280px_1fr] lg:gap-8 flex flex-col gap-6">

    <!-- ── MOBILE TOP BAR ──────────────────────────────────────── -->
    <div class="lg:hidden flex items-center justify-between gap-3 mb-1">
      <button
        @click="filterOpen = true"
        class="flex items-center gap-2 rounded-full bg-slate-900 text-white px-4 py-2.5 font-semibold text-sm shadow-sm"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
        </svg>
        Filters
        <?php if ($hasFilters): ?>
        <span class="bg-teal-500 rounded-full w-5 h-5 text-xs flex items-center justify-center font-bold">
          <?= count($chips) ?>
        </span>
        <?php endif; ?>
      </button>

      <!-- Mobile sort -->
      <form method="GET" id="mobileSortForm" class="flex items-center gap-2 flex-1 justify-end">
        <?php foreach ($_GET as $k => $v): if ($k === 'sort') continue; ?>
          <?php if (is_array($v)): foreach ($v as $vi): ?><input type="hidden" name="<?= esc($k) ?>[]" value="<?= esc($vi) ?>"><?php endforeach; else: ?><input type="hidden" name="<?= esc($k) ?>" value="<?= esc($v) ?>"><?php endif; ?>
        <?php endforeach; ?>
        <select name="sort" onchange="this.form.submit()"
          class="rounded-full border px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-cyan-500 cursor-pointer" style="background:#152b30;border:1px solid rgba(255,255,255,.07);color:#e6f1f1">
          <option value="relevance" <?= $selectedSort==='relevance'?'selected':'' ?>>Sort: Relevance</option>
          <option value="price_low" <?= $selectedSort==='price_low'?'selected':'' ?>>Price: Low to High</option>
          <option value="price_high" <?= $selectedSort==='price_high'?'selected':'' ?>>Price: High to Low</option>
          <option value="range" <?= $selectedSort==='range'?'selected':'' ?>>Best Range</option>
          <option value="rating" <?= $selectedSort==='rating'?'selected':'' ?>>Top Rated</option>
          <option value="newest" <?= $selectedSort==='newest'?'selected':'' ?>>Newest</option>
        </select>
      </form>
    </div>

    <!-- ── MOBILE FILTER DRAWER ────────────────────────────────── -->
    <div x-show="filterOpen" x-cloak class="fixed inset-0 z-50 lg:hidden flex" @keydown.escape.window="filterOpen=false">
      <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"
           @click="filterOpen=false"
           x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="opacity-0"
           x-transition:enter-end="opacity-100"
           x-transition:leave="transition ease-in duration-150"
           x-transition:leave-start="opacity-100"
           x-transition:leave-end="opacity-0"></div>
      <div class="relative w-80 max-w-[88vw] h-full flex flex-col shadow-2xl overflow-y-auto" style="background:#152b30"
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full">
        <div class="sticky top-0 z-10 flex items-center justify-between px-4 py-3.5 shadow-sm" style="background:#152b30;border-bottom:1px solid rgba(255,255,255,.07)">
          <h2 class="font-bold text-slate-100 text-base">Filter EVs</h2>
          <button @click="filterOpen=false" class="p-1.5 rounded-lg text-slate-400 hover:bg-white/10 transition-colors" aria-label="Close filters">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>
        <div class="flex-1 overflow-y-auto p-4">
          <?= view('partials/filter_panel', ['categories' => $categories ?? [], 'brands' => $brands ?? [], 'request' => service('request')]) ?>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════
         DESKTOP FILTER SIDEBAR — sticky 260px
    ══════════════════════════════════════════════════════════════ -->
    <aside class="hidden lg:block w-72 flex-shrink-0 self-start sticky top-24" aria-label="Filter panel">
      <div class="rounded-2xl shadow-sm overflow-hidden" style="background:#152b30;border:1px solid rgba(255,255,255,.07)">

        <!-- Sidebar header -->
        <div class="flex items-center justify-between px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,.07)">
          <h2 class="font-bold text-slate-100 text-base">Filters</h2>
          <?php if ($hasFilters): ?>
          <a href="<?= base_url('vehicles') ?>" class="text-xs text-cyan-400 hover:text-cyan-300 font-semibold transition-colors">
            Clear all
          </a>
          <?php endif; ?>
        </div>

        <form method="GET" action="<?= base_url('vehicles') ?>" x-data="filterForm()" class="divide-y divide-white/10">

          <!-- SECTION 1: Search -->
          <div class="px-5 py-4">
            <label for="q_search" class="block text-[11px] font-bold uppercase tracking-widest text-slate-400 mb-2.5">Search</label>
            <div class="relative">
              <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
              <input
                type="text"
                id="q_search"
                name="q"
                value="<?= esc($searchQ) ?>"
                placeholder="Search EV model or brand..."
                class="w-full pl-9 pr-3 py-2 rounded-xl text-sm text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-cyan-500" style="background:#152b30;border:1px solid rgba(255,255,255,.07)"
              >
            </div>
          </div>

          <!-- SECTION 2: Vehicle Type -->
          <div class="px-5 py-4">
            <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400 mb-3">Vehicle Type</p>
            <div class="space-y-2">
              <label class="flex items-center gap-2.5 cursor-pointer group">
                <input type="radio" name="category" value="" <?= empty($selectedCat)?'checked':'' ?> class="w-4 h-4 accent-teal-500">
                <span class="text-sm text-slate-300 group-hover:text-slate-100 transition-colors font-medium">All EVs</span>
              </label>
              <?php
              $typeOptions = [
                ['Electric Scooters', 'scooter',    '🛵'],
                ['Electric Cars',     'car',         '🚗'],
                ['Electric Bikes',    'bike',        '🏍️'],
                ['E-Rickshaws',       'rickshaw',    '🛺'],
                ['Commercial EVs',    'commercial',  '🚐'],
              ];
              foreach ($typeOptions as [$typeName, $typeSlug, $typeEmoji]):
                // Also check against $categories if passed
                $catSlugMatch = $selectedCat;
                $isSelected = $catSlugMatch === $typeSlug;
                // find count
                $typeCount = '';
                foreach (($categories ?? []) as $c) {
                    if (($c['slug'] ?? '') === $typeSlug) { $typeCount = $c['count'] ?? ''; break; }
                }
              ?>
              <label class="flex items-center gap-2.5 cursor-pointer group">
                <input type="radio" name="category" value="<?= $typeSlug ?>"
                  <?= $isSelected ? 'checked' : '' ?>
                  class="w-4 h-4 accent-teal-500">
                <span class="text-lg leading-none" aria-hidden="true"><?= $typeEmoji ?></span>
                <span class="text-sm text-slate-300 group-hover:text-slate-100 transition-colors flex-1"><?= $typeName ?></span>
                <?php if ($typeCount): ?>
                <span class="text-[11px] text-slate-500 px-1.5 py-0.5 rounded-full" style="background:rgba(255,255,255,.06)"><?= $typeCount ?></span>
                <?php endif; ?>
              </label>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- SECTION 3: Brand -->
          <?php if (!empty($brands)): ?>
          <div class="px-5 py-4" x-data="{showAll: false}">
            <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400 mb-3">Brand</p>
            <div class="space-y-2 overflow-hidden" :class="showAll ? '' : 'max-h-44'">
              <?php foreach ($brands as $brand):
                $bSlug = $brand['slug'] ?? $brand['id'] ?? '';
                $checked = in_array($bSlug, $selectedBrands);
              ?>
              <label class="flex items-center gap-2.5 cursor-pointer group">
                <input type="checkbox" name="brand[]"
                  value="<?= esc($bSlug) ?>"
                  <?= $checked ? 'checked' : '' ?>
                  class="w-4 h-4 accent-teal-500 rounded">
                <span class="w-6 h-6 rounded flex items-center justify-center text-[11px] font-bold text-slate-400 flex-shrink-0" style="background:rgba(255,255,255,.06)" aria-hidden="true">
                  <?= strtoupper(substr($brand['name'], 0, 1)) ?>
                </span>
                <span class="text-sm text-slate-300 group-hover:text-slate-100 transition-colors flex-1 leading-tight">
                  <?= esc($brand['name']) ?>
                </span>
                <?php if (!empty($brand['count'])): ?>
                <span class="text-[11px] text-slate-400">(<?= $brand['count'] ?>)</span>
                <?php endif; ?>
              </label>
              <?php endforeach; ?>
            </div>
            <?php if (count($brands) > 5): ?>
            <button type="button" @click="showAll = !showAll"
              class="mt-2.5 text-xs text-cyan-400 hover:text-cyan-300 font-semibold transition-colors">
              <span x-text="showAll ? '▲ Show less' : '▼ Show all brands'"></span>
            </button>
            <?php endif; ?>
          </div>
          <?php endif; ?>

          <!-- SECTION 4: Price Range -->
          <div class="px-5 py-4" x-data="{priceMin: '<?= esc($selectedPriceMin) ?>', priceMax: '<?= esc($selectedPriceMax) ?>'}">
            <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400 mb-3">Price Range</p>
            <!-- Quick-select buttons -->
            <div class="flex flex-wrap gap-1.5 mb-3">
              <?php foreach ($priceRanges as [$label, $min, $max]):
                $isActive = ((string)$selectedPriceMin === (string)$min && (string)$selectedPriceMax === (string)$max);
              ?>
              <button type="button"
                @click="priceMin='<?= $min ?>'; priceMax='<?= $max ?>'; $refs.priceMinI.value='<?= $min ?>'; $refs.priceMaxI.value='<?= $max ?>'"
                class="price-btn text-xs px-2.5 py-1 rounded-lg hover:text-white text-slate-300 transition-colors <?= $isActive ? 'active' : '' ?>" style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.07)" onmouseover="this.style.background='#009999'" onmouseout="if(!this.classList.contains('active'))this.style.background='rgba(255,255,255,.06)'">
                <?= $label ?>
              </button>
              <?php endforeach; ?>
            </div>
            <!-- Manual inputs -->
            <div class="grid grid-cols-2 gap-2">
              <div>
                <label class="text-[10px] text-slate-400 mb-1 block">Min (₹)</label>
                <div class="relative">
                  <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-medium" aria-hidden="true">₹</span>
                  <input type="number" name="price_min" x-ref="priceMinI"
                    :value="priceMin"
                    @input="priceMin=$event.target.value"
                    placeholder="0"
                    class="w-full pl-6 pr-2 py-2 rounded-xl text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-500" style="background:#152b30;border:1px solid rgba(255,255,255,.07)">
                </div>
              </div>
              <div>
                <label class="text-[10px] text-slate-400 mb-1 block">Max (₹)</label>
                <div class="relative">
                  <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-medium" aria-hidden="true">₹</span>
                  <input type="number" name="price_max" x-ref="priceMaxI"
                    :value="priceMax"
                    @input="priceMax=$event.target.value"
                    placeholder="Any"
                    class="w-full pl-6 pr-2 py-2 rounded-xl text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-500" style="background:#152b30;border:1px solid rgba(255,255,255,.07)">
                </div>
              </div>
            </div>
          </div>

          <!-- SECTION 5: Range -->
          <div class="px-5 py-4">
            <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400 mb-3">Minimum Range</p>
            <div class="flex flex-wrap gap-1.5">
              <?php foreach ($rangeOptions as $km => $label): ?>
              <label class="cursor-pointer">
                <input type="radio" name="range_min" value="<?= $km ?>"
                  <?= $selectedRange == $km ? 'checked' : '' ?>
                  class="sr-only peer">
                <span class="range-pill peer-checked:text-white inline-block text-xs px-3 py-1.5 rounded-lg text-slate-300 hover:text-white transition-colors cursor-pointer" style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.07)">
                  <?= $label ?>
                </span>
              </label>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- SECTION 6: Features -->
          <div class="px-5 py-4">
            <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400 mb-3">Features</p>
            <div class="space-y-2.5">
              <label class="flex items-center gap-2.5 cursor-pointer group">
                <input type="checkbox" name="fast_charging" value="1"
                  <?= !empty($_GET['fast_charging']) ? 'checked' : '' ?>
                  class="w-4 h-4 accent-teal-500 rounded">
                <span class="text-sm text-slate-300 group-hover:text-slate-100 transition-colors">Fast Charging Available</span>
              </label>
              <label class="flex items-center gap-2.5 cursor-pointer group">
                <input type="checkbox" name="connected" value="1"
                  <?= !empty($_GET['connected']) ? 'checked' : '' ?>
                  class="w-4 h-4 accent-teal-500 rounded">
                <span class="text-sm text-slate-300 group-hover:text-slate-100 transition-colors">Connected Features</span>
              </label>
              <label class="flex items-center gap-2.5 cursor-pointer group">
                <input type="checkbox" name="fame_eligible" value="1"
                  <?= !empty($_GET['fame_eligible']) ? 'checked' : '' ?>
                  class="w-4 h-4 accent-teal-500 rounded">
                <span class="text-sm text-slate-300 group-hover:text-slate-100 transition-colors">FAME II Eligible</span>
              </label>
            </div>
          </div>

          <!-- SECTION 7: Sort -->
          <div class="px-5 py-4">
            <label for="sidebar_sort" class="block text-[11px] font-bold uppercase tracking-widest text-slate-400 mb-2.5">Sort By</label>
            <select id="sidebar_sort" name="sort"
              class="w-full rounded-xl px-3 py-2.5 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-500 cursor-pointer appearance-none" style="background:#152b30;border:1px solid rgba(255,255,255,.07)"
              style="background-image:url(\"data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e\");background-position:right .5rem center;background-repeat:no-repeat;background-size:1.5em 1.5em;padding-right:2.5rem">
              <option value="relevance"  <?= $selectedSort==='relevance' ?'selected':'' ?>>Relevance</option>
              <option value="price_low"  <?= $selectedSort==='price_low' ?'selected':'' ?>>Price: Low → High</option>
              <option value="price_high" <?= $selectedSort==='price_high'?'selected':'' ?>>Price: High → Low</option>
              <option value="range"      <?= $selectedSort==='range'     ?'selected':'' ?>>Best Range</option>
              <option value="rating"     <?= $selectedSort==='rating'    ?'selected':'' ?>>Top Rated</option>
              <option value="newest"     <?= $selectedSort==='newest'    ?'selected':'' ?>>Newest First</option>
            </select>
          </div>

          <!-- Actions -->
          <div class="px-5 py-4 space-y-2" style="background:#0f2125">
            <button type="submit"
              class="w-full flex items-center justify-center gap-2 text-white py-3 rounded-xl font-bold text-sm transition-colors shadow-sm" style="background:#009999" onmouseover="this.style.background='#007a7a'" onmouseout="this.style.background='#009999'">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
              Apply Filters
            </button>
            <?php if ($hasFilters): ?>
            <a href="<?= base_url('vehicles') ?>" class="block w-full text-center text-slate-400 hover:text-cyan-300 font-medium py-2 text-sm transition-colors">
              ✕ Clear All Filters
            </a>
            <?php endif; ?>
          </div>

        </form>
      </div>
    </aside>

    <!-- ═══════════════════════════════════════════════════════════
         MAIN CONTENT AREA
    ══════════════════════════════════════════════════════════════ -->
    <main class="flex-1 min-w-0">

      <!-- TOP BAR: count (desktop) -->
      <div class="hidden lg:flex items-center mb-5 gap-3">
        <p class="text-slate-400 text-sm">
          Showing <strong class="text-slate-100"><?= count($vehicles ?? []) ?></strong>
          of <strong class="text-slate-100"><?= number_format($totalVehicles ?? 0) ?></strong> EVs
        </p>
        <?php if ($hasFilters): ?>
        <a href="<?= base_url('vehicles') ?>" class="text-xs text-cyan-400 hover:text-cyan-300 font-semibold underline-offset-2 hover:underline transition-colors">
          Clear all
        </a>
        <?php endif; ?>
      </div>

      <!-- ACTIVE FILTER CHIPS -->
      <?php if (!empty($chips)): ?>
      <div class="flex flex-wrap items-center gap-2 mb-5" aria-label="Active filters">
        <span class="text-xs text-slate-400 font-medium">Active:</span>
        <?php foreach ($chips as $chip):
          $removeUrl = isset($chip['val'])
            ? removeParam($chip['remove'], $chip['val'])
            : removeParam($chip['remove']);
        ?>
        <a href="<?= base_url('vehicles') . $removeUrl ?>"
           class="filter-chip inline-flex items-center gap-1.5 text-slate-100 text-xs font-semibold px-3 py-1.5 rounded-full hover:text-red-500 transition-colors" style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.07)">
          <?= esc($chip['label']) ?>
          <span class="chip-x opacity-60 ml-0.5 text-[10px]">✕</span>
        </a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <!-- VEHICLE GRID -->
      <?php if (!empty($vehicles)): ?>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sr-stagger">
        <?php foreach ($vehicles as $vehicle): ?>
          <?= view('partials/vehicle_card', ['vehicle' => $vehicle]) ?>
        <?php endforeach; ?>
      </div>

      <!-- PAGINATION -->
      <?php if (!empty($pager) && ($pager->getPageCount('default') ?? 1) > 1): ?>
      <?php
        $curPage  = $pager->getCurrentPage('default') ?? 1;
        $totPages = $pager->getPageCount('default')   ?? 1;
        $prevPage = $curPage > 1 ? $curPage - 1 : null;
        $nextPage = $curPage < $totPages ? $curPage + 1 : null;
        $qp       = $_GET;
      ?>
      <nav class="mt-10 flex flex-col items-center gap-4" aria-label="Pagination">
        <div class="flex items-center gap-1.5 flex-wrap justify-center">

          <!-- Prev -->
          <?php if ($prevPage): $qp['page'] = $prevPage; ?>
          <a href="?<?= http_build_query($qp) ?>"
             class="flex items-center gap-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-100 hover:bg-teal-600 hover:text-white transition-all shadow-sm" style="background:#152b30;border:1px solid rgba(255,255,255,.07)">
            ← Prev
          </a>
          <?php else: ?>
          <span class="flex items-center gap-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-500 cursor-not-allowed" style="background:#0f2125;border:1px solid rgba(255,255,255,.07)">
            ← Prev
          </span>
          <?php endif; ?>

          <!-- Page numbers -->
          <?php
          $startP = max(1, $curPage - 2);
          $endP   = min($totPages, $curPage + 2);
          if ($startP > 1): $qp['page'] = 1; ?>
          <a href="?<?= http_build_query($qp) ?>" class="px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-300 hover:bg-teal-600 hover:text-white transition-all" style="background:#152b30;border:1px solid rgba(255,255,255,.07)">1</a>
          <?php if ($startP > 2): ?><span class="px-2 text-slate-400 text-sm self-center">…</span><?php endif; ?>
          <?php endif; ?>

          <?php for ($i = $startP; $i <= $endP; $i++): $qp['page'] = $i; ?>
          <?php if ($i === $curPage): ?>
          <span class="px-3.5 py-2.5 text-white rounded-xl text-sm font-bold shadow-md" style="background:#009999"><?= $i ?></span>
          <?php else: ?>
          <a href="?<?= http_build_query($qp) ?>" class="px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-300 hover:bg-teal-600 hover:text-white transition-all" style="background:#152b30;border:1px solid rgba(255,255,255,.07)"><?= $i ?></a>
          <?php endif; ?>
          <?php endfor; ?>

          <?php if ($endP < $totPages): ?>
          <?php if ($endP < $totPages - 1): ?><span class="px-2 text-slate-400 text-sm self-center">…</span><?php endif; ?>
          <?php $qp['page'] = $totPages; ?>
          <a href="?<?= http_build_query($qp) ?>" class="px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-300 hover:bg-teal-600 hover:text-white transition-all" style="background:#152b30;border:1px solid rgba(255,255,255,.07)"><?= $totPages ?></a>
          <?php endif; ?>

          <!-- Next -->
          <?php if ($nextPage): $qp['page'] = $nextPage; ?>
          <a href="?<?= http_build_query($qp) ?>"
             class="flex items-center gap-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-100 hover:bg-teal-600 hover:text-white transition-all shadow-sm" style="background:#152b30;border:1px solid rgba(255,255,255,.07)">
            Next →
          </a>
          <?php else: ?>
          <span class="flex items-center gap-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-500 cursor-not-allowed" style="background:#0f2125;border:1px solid rgba(255,255,255,.07)">
            Next →
          </span>
          <?php endif; ?>

        </div>
        <p class="text-xs text-slate-400">Page <?= $curPage ?> of <?= $totPages ?></p>
      </nav>
      <?php endif; ?>

      <?php else: ?>
      <!-- EMPTY STATE -->
      <div class="text-center py-20 rounded-2xl shadow-sm" style="background:#152b30;border:1px solid rgba(255,255,255,.07)">
        <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6" style="background:rgba(255,255,255,.06)">
          <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <h3 class="text-2xl font-bold text-slate-100 mb-2">No EVs Found</h3>
        <p class="text-slate-400 mb-8 max-w-sm mx-auto text-sm leading-relaxed">
          No electric vehicles match your current filters. Try broadening your search or clearing some filters.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center mb-8">
          <a href="<?= base_url('vehicles') ?>" class="text-white px-6 py-3 rounded-xl font-bold transition-colors" style="background:#009999" onmouseover="this.style.background='#007a7a'" onmouseout="this.style.background='#009999'">
            ✕ Clear All Filters
          </a>
          <a href="<?= base_url('find-my-ev') ?>" class="bg-slate-900 text-white px-6 py-3 rounded-xl font-bold hover:bg-slate-700 transition-colors">
            🎯 Find My EV →
          </a>
        </div>
        <div class="pt-6" style="border-top:1px solid rgba(255,255,255,.07)">
          <p class="text-xs text-slate-400 mb-3 uppercase tracking-wide font-medium">Popular searches</p>
          <div class="flex flex-wrap gap-2 justify-center">
            <a href="<?= base_url('vehicles?category=scooter') ?>" class="text-slate-300 px-3 py-1.5 rounded-full text-sm hover:bg-teal-600 hover:text-white transition-colors" style="background:rgba(255,255,255,.06)">Electric Scooters</a>
            <a href="<?= base_url('vehicles?category=car') ?>" class="text-slate-300 px-3 py-1.5 rounded-full text-sm hover:bg-teal-600 hover:text-white transition-colors" style="background:rgba(255,255,255,.06)">Electric Cars</a>
            <a href="<?= base_url('vehicles?category=bike') ?>" class="text-slate-300 px-3 py-1.5 rounded-full text-sm hover:bg-teal-600 hover:text-white transition-colors" style="background:rgba(255,255,255,.06)">Electric Bikes</a>
            <a href="<?= base_url('vehicles?price_max=100000') ?>" class="text-slate-300 px-3 py-1.5 rounded-full text-sm hover:bg-teal-600 hover:text-white transition-colors" style="background:rgba(255,255,255,.06)">Under ₹1 Lakh</a>
            <a href="<?= base_url('vehicles?range_min=150') ?>" class="text-slate-300 px-3 py-1.5 rounded-full text-sm hover:bg-teal-600 hover:text-white transition-colors" style="background:rgba(255,255,255,.06)">150+ km Range</a>
          </div>
        </div>
      </div>
      <?php endif; ?>

    </main>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════════════════
     COMPARE BAR — fixed bottom, Alpine.js
════════════════════════════════════════════════════════════════ -->
<div
  x-show="compareList.length >= 2"
  x-cloak
  x-transition:enter="transition ease-out duration-300"
  x-transition:enter-start="translate-y-full opacity-0"
  x-transition:enter-end="translate-y-0 opacity-100"
  x-transition:leave="transition ease-in duration-200"
  x-transition:leave-start="translate-y-0 opacity-100"
  x-transition:leave-end="translate-y-full opacity-0"
  class="fixed bottom-0 left-0 right-0 z-40 bg-slate-900 text-white shadow-2xl border-t-2 border-teal-500"
  role="region"
  aria-label="Compare bar"
>
  <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-4 flex-wrap">
    <div class="flex items-center gap-3 flex-wrap min-w-0">
      <span class="text-cyan-400 font-black text-lg hidden sm:block" aria-hidden="true">⚡</span>
      <span class="font-bold text-sm whitespace-nowrap">
        Comparing <span x-text="compareList.length" class="text-cyan-400 text-base"></span> EVs
      </span>
      <!-- Vehicle thumbnails -->
      <div class="flex items-center gap-2">
        <template x-for="(item, idx) in compareList" :key="item.id">
          <div class="flex items-center gap-1.5 bg-white/10 rounded-lg px-2.5 py-1.5 text-sm border border-white/20">
            <span x-text="item.name" class="max-w-[90px] truncate text-xs font-medium"></span>
            <button @click="removeFromCompare(idx)"
              class="ml-0.5 text-slate-400 hover:text-white transition-colors flex-shrink-0"
              :aria-label="'Remove ' + item.name">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
          </div>
        </template>
        <template x-if="compareList.length < 3">
          <div class="flex items-center gap-1 border border-white/20 border-dashed rounded-lg px-2.5 py-1.5 text-xs text-slate-400">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add 3rd
          </div>
        </template>
      </div>
    </div>
    <div class="flex items-center gap-3 flex-shrink-0">
      <button @click="clearCompare()" class="text-slate-400 hover:text-white text-xs font-medium transition-colors">
        Clear
      </button>
      <a :href="compareUrl()"
         class="text-white px-5 py-2.5 rounded-xl font-bold transition-colors text-sm whitespace-nowrap shadow-lg" style="background:#009999" onmouseover="this.style.background='#007a7a'" onmouseout="this.style.background='#009999'">
        Compare Now →
      </a>
    </div>
  </div>
</div>

</div><!-- /x-data vehicleListing -->

<script>
function vehicleListing() {
  return {
    filterOpen: false,
    compareList: [],

    init() {
      try {
        const s = localStorage.getItem('charj_compare');
        if (s) this.compareList = JSON.parse(s);
      } catch(e) { this.compareList = []; }

      window.addEventListener('charj:add-compare', (e) => {
        this.addToCompare(e.detail);
      });
      window.addEventListener('charj:remove-compare', (e) => {
        const idx = this.compareList.findIndex(v => v.id == e.detail.id);
        if (idx > -1) this.removeFromCompare(idx);
      });
    },

    addToCompare(vehicle) {
      if (this.compareList.length >= 3) {
        alert('You can compare up to 3 EVs. Remove one to add another.');
        return false;
      }
      if (!this.compareList.find(v => v.id == vehicle.id)) {
        this.compareList.push(vehicle);
        this.saveCompare();
        return true;
      }
      return false;
    },

    removeFromCompare(idx) {
      this.compareList.splice(idx, 1);
      this.saveCompare();
    },

    clearCompare() {
      this.compareList = [];
      localStorage.removeItem('charj_compare');
    },

    saveCompare() {
      localStorage.setItem('charj_compare', JSON.stringify(this.compareList));
    },

    compareUrl() {
      const ids = this.compareList.map(v => v.slug || v.id).join(',');
      return '<?= base_url("compare") ?>?ids=' + ids;
    }
  }
}

function filterForm() {
  return {}; // placeholder for potential future use
}
</script>

<?= $this->endSection() ?>
