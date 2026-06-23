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

/* Animations */
.filter-chip:hover .chip-x{opacity:1}
.compare-bar-enter{animation:slideUp .3s cubic-bezier(.22,.68,0,1.2) both}
@keyframes slideUp{from{transform:translateY(100%);opacity:0}to{transform:translateY(0);opacity:1}}

/* Filter Buttons */
.range-btn.active{background:#10b981;color:#fff;transform:scale(1.05)}
.price-btn.active{background:#10b981;color:#fff;transform:scale(1.05)}
.range-btn, .price-btn{transition:all 250ms cubic-bezier(0.4,0,0.2,1)}
.peer:checked ~ .range-pill{background:#10b981!important;color:#fff;transform:scale(1.05)}
.range-pill:hover{background:#10b981!important;transform:scale(1.05)}
.range-pill{transition:all 250ms cubic-bezier(0.4,0,0.2,1)}

/* Accordion Sections */
.accordion-header{cursor:pointer;transition:all 250ms cubic-bezier(0.4,0,0.2,1);user-select:none}
.accordion-header:hover{color:#10b981}
.accordion-content{max-height:500px;opacity:1;overflow:hidden;transition:all 300ms cubic-bezier(0.4,0,0.2,1)}
.accordion-content.collapsed{max-height:0;opacity:0;overflow:hidden}

/* Form Elements */
input[type="checkbox"],
input[type="radio"]{transition:all 250ms cubic-bezier(0.4,0,0.2,1)}
input[type="checkbox"]:hover,
input[type="radio"]:hover{transform:scale(1.1)}

/* Labels */
label{transition:color 250ms cubic-bezier(0.4,0,0.2,1)}
label:hover{color:#10b981}

/* Buttons */
button{transition:all 250ms cubic-bezier(0.4,0,0.2,1)}
button:hover{transform:translateY(-2px)}

/* Links */
a{transition:all 250ms cubic-bezier(0.4,0,0.2,1)}

/* Vehicle Grid */
[class*="grid"]{transition:opacity 200ms ease}

/* Filter Loading */
.filter-loading{opacity:0.6;pointer-events:none}
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
  class="min-h-screen" style="background: linear-gradient(180deg, #e7f8ef 0%, #f3fbf7 100%)"
>

<!-- PAGE HEADER — premium marketplace -->
<div style="background: linear-gradient(180deg, #e7f8ef 0%, #f3fbf7 100%)" class="relative overflow-hidden pt-12 pb-16 px-4">
  <!-- floating particles -->
  <div class="absolute top-10 left-10 w-2 h-2 rounded-full opacity-20 float-1 pointer-events-none" style="background:#6ee7b7"></div>
  <div class="absolute top-20 right-20 w-1.5 h-1.5 rounded-full opacity-15 float-2 pointer-events-none" style="background:#10b981"></div>
  <div class="absolute bottom-10 left-1/2 w-2 h-2 rounded-full opacity-10 float-3 pointer-events-none" style="background:#16a34a"></div>

  <div class="relative max-w-4xl mx-auto text-center">
    <!-- Breadcrumb -->
    <nav class="text-xs mb-4 flex items-center justify-center gap-1.5" aria-label="Breadcrumb">
      <a href="<?= base_url('/') ?>" class="hover:text-[#059669] transition-colors" style="color:#14532d">Home</a>
      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="stroke:#14532d"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      <span style="color:#14532d"><?= esc($title ?? 'All EVs') ?></span>
    </nav>

    <div>
      <div class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-widest mb-4" style="background:rgba(110,231,183,0.15);border:1px solid rgba(110,231,183,0.3);color:#059669">
        ⚡ Charj.in EV Database
      </div>
      <h1 class="text-3xl sm:text-5xl font-black leading-tight mb-3" style="color:#0f172a">
        <?= esc($title ?? 'All EVs in India') ?>
      </h1>
      <p class="text-sm sm:text-base mb-8" style="color:#4b5563">
        <?= esc($subtitle ?? 'Compare prices, range & features across all electric vehicles') ?>
      </p>

      <!-- Quick stats -->
      <div class="flex flex-wrap gap-4 justify-center mb-8">
        <div class="rounded-xl px-6 py-3 text-center" style="background:#f2fbf6;border:1px solid rgba(16,185,129,0.15)">
          <div class="text-2xl font-black" style="color:#0f172a">150+</div>
          <div class="text-[10px] uppercase tracking-widest font-semibold" style="color:#6ee7b7">EVs Listed</div>
        </div>
        <div class="rounded-xl px-6 py-3 text-center" style="background:#f2fbf6;border:1px solid rgba(16,185,129,0.15)">
          <div class="text-2xl font-black" style="color:#10b981">₹59k</div>
          <div class="text-[10px] uppercase tracking-widest font-semibold" style="color:#6ee7b7">Starting From</div>
        </div>
        <div class="rounded-xl px-6 py-3 text-center" style="background:#f2fbf6;border:1px solid rgba(16,185,129,0.15)">
          <div class="text-2xl font-black" style="color:#16a34a">700+km</div>
          <div class="text-[10px] uppercase tracking-widest font-semibold" style="color:#6ee7b7">Best Range</div>
        </div>
      </div>

      <!-- Controls -->
      <div class="flex flex-wrap items-center justify-center gap-3">
        <span class="rounded-full text-xs font-bold px-4 py-2 text-white shadow-lg" style="background:#10b981">
          ⚡ <?= number_format($totalVehicles ?? count($vehicles ?? [])) ?> EVs
        </span>
        <?php if ($hasFilters): ?>
        <a href="<?= base_url('vehicles') ?>" class="rounded-full border text-xs font-medium px-3 py-2 transition-colors" style="border-color:#10b981;color:#10b981" @mouseover="this.style.background='rgba(16,185,129,0.1)'" @mouseout="this.style.background='transparent'">
          ✕ Clear filters
        </a>
        <?php endif; ?>

        <!-- Sort -->
        <form method="GET" id="headerSortForm" class="flex items-center gap-2">
          <?php foreach ($_GET as $k => $v): if ($k === 'sort') continue; ?>
            <?php if (is_array($v)): foreach ($v as $vi): ?><input type="hidden" name="<?= esc($k) ?>[]" value="<?= esc($vi) ?>"><?php endforeach; else: ?><input type="hidden" name="<?= esc($k) ?>" value="<?= esc($v) ?>"><?php endif; ?>
          <?php endforeach; ?>
          <select name="sort" onchange="this.form.submit()"
            class="rounded-full border px-4 py-2 text-xs focus:outline-none focus:ring-2 cursor-pointer" style="border-color:#6ee7b7;background:#f2fbf6;color:#14532d;focus-ring-color:#10b981">
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
        class="flex items-center gap-2 rounded-full px-4 py-2.5 font-semibold text-sm shadow-sm transition-colors"
        style="background:#f2fbf6;color:#14532d;border:1px solid #6ee7b7"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
        </svg>
        Filters
        <?php if ($hasFilters): ?>
        <span class="rounded-full w-5 h-5 text-xs flex items-center justify-center font-bold text-white" style="background:#10b981">
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
          class="rounded-full border px-3 py-2 text-xs focus:outline-none focus:ring-2 cursor-pointer" style="background:#f2fbf6;border-color:#6ee7b7;color:#14532d;focus-ring-color:#10b981">
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
      <div class="absolute inset-0 backdrop-blur-sm" style="background:rgba(0,0,0,0.3)"
           @click="filterOpen=false"
           x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="opacity-0"
           x-transition:enter-end="opacity-100"
           x-transition:leave="transition ease-in duration-150"
           x-transition:leave-start="opacity-100"
           x-transition:leave-end="opacity-0"></div>
      <div class="relative w-80 max-w-[88vw] h-full flex flex-col shadow-2xl overflow-y-auto" style="background:#f2fbf6"
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full">
        <div class="sticky top-0 z-10 flex items-center justify-between px-4 py-3.5 shadow-sm" style="background:#f2fbf6;border-bottom:1px solid #6ee7b7">
          <h2 class="font-bold text-base" style="color:#14532d">Filter EVs</h2>
          <button @click="filterOpen=false" class="p-1.5 rounded-lg transition-colors" style="color:#14532d" @mouseover="this.style.background='rgba(16,185,129,0.1)'" @mouseout="this.style.background='transparent'" aria-label="Close filters">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>
        <div class="flex-1 overflow-y-auto p-4">
          <?= view('partials/filter_panel', ['categories' => $categories ?? [], 'brands' => $brands ?? [], 'request' => service('request')]) ?>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════
         DESKTOP FILTER SIDEBAR — sticky with cards
    ══════════════════════════════════════════════════════════════ -->
    <aside class="hidden lg:block w-72 flex-shrink-0 self-start sticky" style="top:80px;max-height:calc(100vh - 100px);overflow-y:auto" aria-label="Filter panel">
      <div class="rounded-2xl shadow-sm overflow-hidden" style="background:#f2fbf6;border:1px solid #6ee7b7">

        <!-- Sidebar header -->
        <div class="flex items-center justify-between px-5 py-4" style="border-bottom:1px solid #6ee7b7">
          <h2 class="font-bold text-base" style="color:#14532d">Filters</h2>
          <?php if ($hasFilters): ?>
          <a href="<?= base_url('vehicles') ?>" class="text-xs font-semibold transition-colors" style="color:#10b981" @mouseover="this.style.color='#059669'" @mouseout="this.style.color='#10b981'">
            Clear all
          </a>
          <?php endif; ?>
        </div>

        <form method="GET" action="<?= base_url('vehicles') ?>" x-data="filterForm()" class="divide-y" style="border-color:#6ee7b7">

          <!-- SECTION 1: Search -->
          <div class="px-5 py-4">
            <label for="q_search" class="block text-[11px] font-bold uppercase tracking-widest mb-2.5" style="color:#6ee7b7">Search</label>
            <div class="relative">
              <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#6ee7b7"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
              <input
                type="text"
                id="q_search"
                name="q"
                value="<?= esc($searchQ) ?>"
                placeholder="Search EV model or brand..."
                class="w-full pl-9 pr-3 py-2 rounded-xl text-sm focus:outline-none focus:ring-2" style="background:#fff;border:1px solid #6ee7b7;color:#14532d;focus-ring-color:#10b981" placeholder-style="color:#a0aaa8"
              >
            </div>
          </div>

          <!-- SECTION 2: Vehicle Type -->
          <div class="px-5 py-4">
            <p class="text-[11px] font-bold uppercase tracking-widest mb-3" style="color:#6ee7b7">Vehicle Type</p>
            <div class="space-y-2">
              <label class="flex items-center gap-2.5 cursor-pointer group">
                <input type="radio" name="category" value="" <?= empty($selectedCat)?'checked':'' ?> class="w-4 h-4" style="accent-color:#10b981">
                <span class="text-sm transition-colors font-medium" style="color:#14532d">All EVs</span>
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
                  class="w-4 h-4" style="accent-color:#10b981">
                <span class="text-lg leading-none" aria-hidden="true"><?= $typeEmoji ?></span>
                <span class="text-sm transition-colors flex-1" style="color:#14532d"><?= $typeName ?></span>
                <?php if ($typeCount): ?>
                <span class="text-[11px] px-1.5 py-0.5 rounded-full" style="background:#e8f7ef;color:#6ee7b7"><?= $typeCount ?></span>
                <?php endif; ?>
              </label>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- SECTION 3: Brand -->
          <?php if (!empty($brands)): ?>
          <div class="px-5 py-4" x-data="{brandOpen: true}">
            <button type="button" @click="brandOpen = !brandOpen" class="accordion-header flex items-center justify-between w-full mb-3" style="color:#6ee7b7">
              <span class="text-[11px] font-bold uppercase tracking-widest">🏷 Brands (<?= count($brands) ?>)</span>
              <svg class="w-4 h-4 transition-transform duration-300" :style="{ transform: brandOpen ? 'rotate(0deg)' : 'rotate(-90deg)' }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
            </button>
            <div class="accordion-content space-y-2" :class="!brandOpen ? 'collapsed' : ''">
              <?php foreach ($brands as $brand):
                $bSlug = $brand['slug'] ?? $brand['id'] ?? '';
                $checked = in_array($bSlug, $selectedBrands);
              ?>
              <label class="flex items-center gap-2.5 cursor-pointer group p-2 rounded-lg transition-all duration-250" style="color:#14532d" @mouseover="this.style.backgroundColor='#f0fdf4'" @mouseout="this.style.backgroundColor='transparent'">
                <input type="checkbox" name="brand[]"
                  value="<?= esc($bSlug) ?>"
                  <?= $checked ? 'checked' : '' ?>
                  class="w-4 h-4 rounded" style="accent-color:#10b981;cursor:pointer">
                <span class="w-6 h-6 rounded flex items-center justify-center text-[10px] font-bold flex-shrink-0 transition-all duration-250" style="background:#e8f7ef;color:#6ee7b7;<?= $checked ? 'background:#10b981;color:white' : '' ?>" aria-hidden="true">
                  <?= strtoupper(substr($brand['name'], 0, 1)) ?>
                </span>
                <span class="text-sm font-medium flex-1 leading-tight">
                  <?= esc($brand['name']) ?>
                </span>
                <?php if (!empty($brand['count'])): ?>
                <span class="text-xs font-semibold px-2 py-0.5 rounded" style="background:#f0fdf4;color:#6ee7b7"><?= $brand['count'] ?></span>
                <?php endif; ?>
              </label>
              <?php endforeach; ?>
            </div>
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
      <div class="hidden lg:flex items-center mb-6 gap-4">
        <p class="text-sm" style="color:#14532d">
          Showing <strong style="color:#0f172a"><?= count($vehicles ?? []) ?></strong>
          of <strong style="color:#0f172a"><?= number_format($totalVehicles ?? 0) ?></strong> EVs
        </p>
        <?php if ($hasFilters): ?>
        <a href="<?= base_url('vehicles') ?>" class="text-xs font-semibold transition-colors" style="color:#10b981" @mouseover="this.style.color='#059669'" @mouseout="this.style.color='#10b981'">
          ✕ Clear all
        </a>
        <?php endif; ?>
      </div>

      <!-- ACTIVE FILTER CHIPS -->
      <?php if (!empty($chips)): ?>
      <div class="flex flex-wrap items-center gap-2 mb-6" aria-label="Active filters">
        <span class="text-xs font-medium" style="color:#6ee7b7">Active Filters:</span>
        <?php foreach ($chips as $chip):
          $removeUrl = isset($chip['val'])
            ? removeParam($chip['remove'], $chip['val'])
            : removeParam($chip['remove']);
        ?>
        <a href="<?= base_url('vehicles') . $removeUrl ?>"
           class="filter-chip inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full transition-all hover:bg-red-50" style="background:#f0fdf4;border:1px solid #d1fae5;color:#14532d">
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
      <nav class="mt-12 flex flex-col items-center gap-4" aria-label="Pagination">
        <div class="flex items-center gap-1.5 flex-wrap justify-center">

          <!-- Prev -->
          <?php if ($prevPage): $qp['page'] = $prevPage; ?>
          <a href="?<?= http_build_query($qp) ?>"
             class="flex items-center gap-1 px-4 py-2.5 rounded-lg text-sm font-semibold transition-all" style="background:#ffffff;border:1px solid #d1fae5;color:#10b981" @mouseover="this.style.background='#f0fdf4'" @mouseout="this.style.background='#ffffff'">
            ← Prev
          </a>
          <?php else: ?>
          <span class="flex items-center gap-1 px-4 py-2.5 rounded-lg text-sm font-semibold cursor-not-allowed" style="background:#ffffff;border:1px solid #e0e7e0;color:#a0aaa8">
            ← Prev
          </span>
          <?php endif; ?>

          <!-- Page numbers -->
          <?php
          $startP = max(1, $curPage - 2);
          $endP   = min($totPages, $curPage + 2);
          if ($startP > 1): $qp['page'] = 1; ?>
          <a href="?<?= http_build_query($qp) ?>" class="px-3.5 py-2.5 rounded-lg text-sm font-semibold transition-all" style="background:#ffffff;border:1px solid #d1fae5;color:#14532d" @mouseover="this.style.background='#f0fdf4'" @mouseout="this.style.background='#ffffff'">1</a>
          <?php if ($startP > 2): ?><span class="px-2 text-sm self-center" style="color:#6ee7b7">…</span><?php endif; ?>
          <?php endif; ?>

          <?php for ($i = $startP; $i <= $endP; $i++): $qp['page'] = $i; ?>
          <?php if ($i === $curPage): ?>
          <span class="px-3.5 py-2.5 text-white rounded-lg text-sm font-bold" style="background:#10b981"><?= $i ?></span>
          <?php else: ?>
          <a href="?<?= http_build_query($qp) ?>" class="px-3.5 py-2.5 rounded-lg text-sm font-semibold transition-all" style="background:#ffffff;border:1px solid #d1fae5;color:#14532d" @mouseover="this.style.background='#f0fdf4'" @mouseout="this.style.background='#ffffff'"><?= $i ?></a>
          <?php endif; ?>
          <?php endfor; ?>

          <?php if ($endP < $totPages): ?>
          <?php if ($endP < $totPages - 1): ?><span class="px-2 text-sm self-center" style="color:#6ee7b7">…</span><?php endif; ?>
          <?php $qp['page'] = $totPages; ?>
          <a href="?<?= http_build_query($qp) ?>" class="px-3.5 py-2.5 rounded-lg text-sm font-semibold transition-all" style="background:#ffffff;border:1px solid #d1fae5;color:#14532d" @mouseover="this.style.background='#f0fdf4'" @mouseout="this.style.background='#ffffff'"><?= $totPages ?></a>
          <?php endif; ?>

          <!-- Next -->
          <?php if ($nextPage): $qp['page'] = $nextPage; ?>
          <a href="?<?= http_build_query($qp) ?>"
             class="flex items-center gap-1 px-4 py-2.5 rounded-lg text-sm font-semibold transition-all" style="background:#ffffff;border:1px solid #d1fae5;color:#10b981" @mouseover="this.style.background='#f0fdf4'" @mouseout="this.style.background='#ffffff'">
            Next →
          </a>
          <?php else: ?>
          <span class="flex items-center gap-1 px-4 py-2.5 rounded-lg text-sm font-semibold cursor-not-allowed" style="background:#ffffff;border:1px solid #e0e7e0;color:#a0aaa8">
            Next →
          </span>
          <?php endif; ?>

        </div>
        <p class="text-xs" style="color:#6ee7b7">Page <?= $curPage ?> of <?= $totPages ?></p>
      </nav>
      <?php endif; ?>

      <?php else: ?>
      <!-- EMPTY STATE -->
      <div class="text-center py-20 rounded-3xl" style="background:#ffffff;border:1.5px solid #d1fae5;box-shadow:0 8px 24px rgba(0,0,0,.04)">
        <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6" style="background:#f0fdf4">
          <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#6ee7b7">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <h3 class="text-2xl font-bold mb-2" style="color:#0f172a">No EVs Found</h3>
        <p class="mb-8 max-w-sm mx-auto text-sm leading-relaxed" style="color:#4b5563">
          No electric vehicles match your current filters. Try broadening your search or clearing some filters.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center mb-8">
          <a href="<?= base_url('vehicles') ?>" class="text-white px-6 py-3 rounded-xl font-bold transition-colors" style="background:#10b981" @mouseover="this.style.background='#059669'" @mouseout="this.style.background='#10b981'">
            ✕ Clear All Filters
          </a>
          <a href="<?= base_url('find-my-ev') ?>" class="px-6 py-3 rounded-xl font-bold transition-all" style="background:#f0fdf4;color:#10b981;border:1px solid #d1fae5" @mouseover="this.style.background='#e0f5ed'" @mouseout="this.style.background='#f0fdf4'">
            🎯 Find My EV →
          </a>
        </div>
        <div class="pt-6" style="border-top:1px solid #d1fae5">
          <p class="text-xs mb-3 uppercase tracking-wide font-medium" style="color:#6ee7b7">Popular searches</p>
          <div class="flex flex-wrap gap-2 justify-center">
            <a href="<?= base_url('vehicles?category=scooter') ?>" class="px-3 py-1.5 rounded-full text-sm transition-all" style="background:#f0fdf4;color:#14532d;border:1px solid #d1fae5" @mouseover="this.style.background='#e0f5ed'" @mouseout="this.style.background='#f0fdf4'">Electric Scooters</a>
            <a href="<?= base_url('vehicles?category=car') ?>" class="px-3 py-1.5 rounded-full text-sm transition-all" style="background:#f0fdf4;color:#14532d;border:1px solid #d1fae5" @mouseover="this.style.background='#e0f5ed'" @mouseout="this.style.background='#f0fdf4'">Electric Cars</a>
            <a href="<?= base_url('vehicles?category=bike') ?>" class="px-3 py-1.5 rounded-full text-sm transition-all" style="background:#f0fdf4;color:#14532d;border:1px solid #d1fae5" @mouseover="this.style.background='#e0f5ed'" @mouseout="this.style.background='#f0fdf4'">Electric Bikes</a>
            <a href="<?= base_url('vehicles?price_max=100000') ?>" class="px-3 py-1.5 rounded-full text-sm transition-all" style="background:#f0fdf4;color:#14532d;border:1px solid #d1fae5" @mouseover="this.style.background='#e0f5ed'" @mouseout="this.style.background='#f0fdf4'">Under ₹1 Lakh</a>
            <a href="<?= base_url('vehicles?range_min=150') ?>" class="px-3 py-1.5 rounded-full text-sm transition-all" style="background:#f0fdf4;color:#14532d;border:1px solid #d1fae5" @mouseover="this.style.background='#e0f5ed'" @mouseout="this.style.background='#f0fdf4'">150+ km Range</a>
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
  class="fixed bottom-0 left-0 right-0 z-40 text-white shadow-2xl" style="background:#10b981"
  role="region"
  aria-label="Compare bar"
>
  <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between gap-4 flex-wrap">
    <div class="flex items-center gap-3 flex-wrap min-w-0">
      <span class="font-black text-lg hidden sm:block" aria-hidden="true">⚡</span>
      <span class="font-bold text-sm whitespace-nowrap">
        Comparing <span x-text="compareList.length" class="font-black text-base"></span> EVs
      </span>
      <!-- Vehicle thumbnails -->
      <div class="flex items-center gap-2">
        <template x-for="(item, idx) in compareList" :key="item.id">
          <div class="flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-sm border" style="background:rgba(255,255,255,.2);border-color:rgba(255,255,255,.3)">
            <span x-text="item.name" class="max-w-[90px] truncate text-xs font-medium"></span>
            <button @click="removeFromCompare(idx)"
              class="ml-0.5 transition-colors flex-shrink-0" style="color:rgba(255,255,255,.7)"
              :aria-label="'Remove ' + item.name">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
          </div>
        </template>
        <template x-if="compareList.length < 3">
          <div class="flex items-center gap-1 border border-dashed rounded-lg px-2.5 py-1.5 text-xs" style="border-color:rgba(255,255,255,.4);color:rgba(255,255,255,.6)">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add 3rd
          </div>
        </template>
      </div>
    </div>
    <div class="flex items-center gap-3 flex-shrink-0">
      <button @click="clearCompare()" class="text-xs font-medium transition-colors" style="color:rgba(255,255,255,.8)">
        Clear
      </button>
      <a :href="compareUrl()"
         class="text-white px-5 py-2.5 rounded-lg font-bold transition-colors text-sm whitespace-nowrap" style="background:rgba(255,255,255,.2);border:1px solid rgba(255,255,255,.3)" @mouseover="this.style.background='rgba(255,255,255,.3)'" @mouseout="this.style.background='rgba(255,255,255,.2)'">
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

// ═══════════════════════════════════════════════════════════════════════
// DYNAMIC FILTER LOADING — AJAX without page reload
// ═══════════════════════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', function() {
  const filterForm = document.querySelector('form[action*="vehicles"]');
  if (!filterForm) return;

  let filterTimeout;
  const allInputs = filterForm.querySelectorAll('input[type="checkbox"], input[type="radio"], select[name="sort"]');

  allInputs.forEach(input => {
    input.addEventListener('change', function() {
      clearTimeout(filterTimeout);
      filterTimeout = setTimeout(applyFiltersAjax, 300);
    });
  });

  async function applyFiltersAjax() {
    const formData = new FormData(filterForm);
    const params = new URLSearchParams(formData);

    // Show loading state
    const vehicleGrid = document.querySelector('[class*="grid"]');
    if (vehicleGrid) {
      vehicleGrid.style.opacity = '0.6';
      vehicleGrid.style.pointerEvents = 'none';
    }

    try {
      const response = await fetch('<?= base_url("vehicles") ?>?' + params.toString(), {
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      });

      if (!response.ok) throw new Error('Filter failed');

      const html = await response.text();
      const parser = new DOMParser();
      const newDoc = parser.parseFromString(html, 'text/html');

      // Extract new vehicle grid
      const newGrid = newDoc.querySelector('[class*="grid"][class*="cols"]');
      const oldGrid = vehicleGrid;

      if (newGrid && oldGrid) {
        // Smooth transition
        oldGrid.style.opacity = '0';
        oldGrid.style.transition = 'opacity 200ms ease';

        setTimeout(() => {
          oldGrid.innerHTML = newGrid.innerHTML;
          oldGrid.style.opacity = '1';
          oldGrid.style.pointerEvents = 'auto';
        }, 200);
      }

      // Update URL without reload
      window.history.replaceState(null, '', '<?= base_url("vehicles") ?>?' + params.toString());

      // Update vehicle count
      const countEl = document.querySelector('[class*="Showing"]');
      const newCount = newDoc.querySelector('[class*="Showing"]');
      if (countEl && newCount) {
        countEl.innerHTML = newCount.innerHTML;
      }

    } catch (error) {
      console.error('Filter error:', error);
      if (vehicleGrid) {
        vehicleGrid.style.opacity = '1';
        vehicleGrid.style.pointerEvents = 'auto';
      }
    }
  }
});
</script>

<?= $this->endSection() ?>
