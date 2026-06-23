<?= $this->extend('layouts/public') ?>

<?= $this->section('head') ?>
<title><?= esc($meta_title ?? 'Charj.in — India\'s EV Decision Engine | Compare, Calculate, Choose') ?></title>
<meta name="description" content="<?= esc($meta_description ?? 'Compare 150+ EVs, calculate savings & subsidies, find the perfect electric vehicle for India. FAME II calculator, charging guide, free EV quiz.') ?>">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Charj.in",
  "url": "<?= base_url() ?>",
  "logo": "<?= base_url('assets/images/charj-logo.png') ?>",
  "description": "India's EV Decision Engine — Compare 150+ EVs, calculate 5-year savings, find subsidies and book test rides.",
  "areaServed": "IN"
}
</script>
<style>
  /* Hero grid overlay */
  .hero-grid {
    background-image:
      linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
      linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
    background-size: 48px 48px;
  }
  /* Stat dividers */
  @media (min-width: 768px) {
    .stat-item + .stat-item { border-left: 1px solid rgba(255,255,255,.06); }
  }
  @media (max-width: 767px) {
    .stat-item:nth-child(even) { border-left: 1px solid rgba(255,255,255,.06); }
  }
  /* Subsidy banner pills */
  .subsidy-pill {
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.25);
    backdrop-filter: blur(4px);
  }
  /* Line-clamp */
  .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
  /* Scroll indicator */
  @keyframes bounce-slow { 0%,100%{transform:translateX(-50%) translateY(0)} 50%{transform:translateX(-50%) translateY(8px)} }
  .scroll-indicator { animation: bounce-slow 2s ease-in-out infinite; }
  /* Hero gradient (brighter greens for dark bg) */
  .hero-gradient-text {
    background: linear-gradient(135deg, #4ade80, #2dd4bf);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  /* Category hover */
  .cat-card:hover { background: #009999 !important; border-color: #009999 !important; }
  .cat-card:hover .cat-emoji-wrap { background: rgba(255,255,255,0.2) !important; }
  .cat-card:hover .cat-name,
  .cat-card:hover .cat-count { color: #fff !important; }
  /* Star color */
  .star-icon { color: #f59e0b; }
  /* Press logo */
  .press-logo { color: #94a3b8; font-weight: 700; font-size: 0.8rem; letter-spacing: 0.08em; text-transform: uppercase; }
  /* Charging Stations Section Styles */
  .charging-map-container {
    border-radius: 1.5rem;
    overflow: hidden;
    border: 1px solid rgba(22, 163, 74, 0.3);
    background: #f0fdf4;
    height: 400px;
  }
  .leaflet-container {
    height: 100%;
    background: #f5fdf4;
  }
  .charging-station-item {
    transition: all 0.2s ease;
    cursor: pointer;
  }
  .charging-station-item:hover {
    transform: translateX(4px);
    background: rgba(22, 163, 74, 0.1);
  }
  .charging-station-item.active {
    background: rgba(22, 163, 74, 0.15);
    border-color: #16a34a;
  }
  .geolocation-spinner {
    display: inline-block;
    width: 18px;
    height: 18px;
    border: 2px solid rgba(0, 153, 153, 0.3);
    border-top-color: #009999;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
  }
  @keyframes spin {
    to { transform: rotate(360deg); }
  }
  .charging-distance {
    font-size: 0.875rem;
    font-weight: 600;
    color: #16c4c4;
  }
  .charger-type-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 700;
    background: rgba(22, 163, 74, 0.15);
    color: #16a34a;
    margin: 0.25rem 0.25rem 0.25rem 0;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- ================================================================
  SECTION 1 — HERO (full viewport)
================================================================ -->
<section class="relative min-h-screen flex flex-col justify-center overflow-hidden" style="background: linear-gradient(180deg, #dff5ea 0%, #e8f7ef 100%)">

  <!-- Grid overlay -->
  <div class="absolute inset-0 hero-grid pointer-events-none" aria-hidden="true"></div>

  <!-- Glow blobs -->
  <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
    <div class="absolute top-1/3 left-1/2 -translate-x-1/2 w-[700px] h-[400px] bg-emerald-300 opacity-[0.08] blur-3xl rounded-full"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-green-300 opacity-[0.06] blur-3xl rounded-full"></div>
  </div>

  <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-24 text-center">

    <!-- Eyebrow badge -->
    <div class="inline-flex items-center gap-2 rounded-full px-4 py-2 mb-8 animate-fade-in-up stagger-1" style="background:rgba(110,231,183,0.15);border:1px solid rgba(110,231,183,0.3)">
      <span class="text-sm" aria-hidden="true">&#x26A1;</span>
      <span class="text-xs font-bold tracking-wider uppercase" style="color:#059669">India's Dedicated EV Marketplace</span>
    </div>

    <!-- H1 -->
    <h1 class="text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-black leading-tight tracking-tight mb-6 animate-fade-in-up stagger-2" style="color:#0f172a">
      One platform.<br>
      <span style="color:#16a34a">Every EV brand.</span>
    </h1>

    <!-- Subheadline -->
    <p class="text-base sm:text-lg md:text-xl max-w-2xl mx-auto leading-relaxed mb-12 sm:mb-14 animate-fade-in-up stagger-3" style="color:#4b5563">
      We connect EV brands with buyers — making it simple to discover,
      compare and choose the right electric vehicle.
    </p>

    <!-- Search bar -->
    <form action="<?= base_url('vehicles') ?>" method="GET"
          class="relative max-w-2xl mx-auto mb-8 animate-fade-in-up stagger-4" role="search">
      <div class="flex items-center bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl overflow-hidden focus-within:border-teal-400 focus-within:bg-white/15 transition-all duration-200 shadow-2xl">
        <svg class="w-5 h-5 text-white/50 ml-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
        <input type="text" name="q" placeholder="Search EVs, Brands, Models..."
               class="flex-1 bg-transparent text-white placeholder-white/50 px-4 py-4 text-base outline-none min-w-0"
               aria-label="Search EVs">
        <button type="submit"
                class="flex-shrink-0 bg-[#009999] hover:bg-[#16c4c4] text-white font-bold px-6 py-4 transition-colors text-sm">
          Search
        </button>
      </div>
      <!-- Quick searches -->
      <div class="flex flex-wrap gap-2 justify-center mt-3">
        <?php foreach (['Ola S1 Pro','Tata Nexon EV','Ather 450X','TVS iQube'] as $qs): ?>
        <a href="<?= base_url('vehicles?q='.urlencode($qs)) ?>"
           class="text-xs text-white/50 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 rounded-full px-3 py-1 transition-all duration-150">
          <?= esc($qs) ?>
        </a>
        <?php endforeach; ?>
      </div>
    </form>

    <!-- Category quick tabs -->
    <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-3 mb-8 sm:mb-10 animate-fade-in-up stagger-5">
      <?php
      $heroTabs = [
        ['icon'=>'&#128757;', 'label'=>'2 Wheelers',  'url'=>base_url('electric-scooters')],
        ['icon'=>'&#128662;', 'label'=>'3 Wheelers',  'url'=>base_url('electric-rickshaws')],
        ['icon'=>'&#128664;', 'label'=>'4 Wheelers',  'url'=>base_url('electric-cars')],
        ['icon'=>'&#128665;', 'label'=>'Commercial',  'url'=>base_url('commercial-ev')],
      ];
      foreach ($heroTabs as $ht): ?>
      <a href="<?= $ht['url'] ?>"
         class="flex flex-col items-center gap-1 px-4 sm:px-6 py-2.5 rounded-xl bg-white/8 border border-white/15 hover:bg-white/15 hover:border-teal-400/50 transition-all duration-150 group">
        <span class="text-xl leading-none" aria-hidden="true"><?= $ht['icon'] ?></span>
        <span class="text-xs font-semibold text-white/70 group-hover:text-white whitespace-nowrap"><?= $ht['label'] ?></span>
      </a>
      <?php endforeach; ?>
    </div>

    <!-- Mini CTA row -->
    <div class="flex flex-col sm:flex-row gap-3 justify-center">
      <a href="<?= base_url('find-my-ev') ?>"
         onclick="charjTrack('hero_cta_finder',{})"
         class="inline-flex items-center justify-center gap-2 bg-[#009999] hover:bg-[#16c4c4] text-white font-bold text-base px-7 py-3.5 rounded-full transition-all duration-200 shadow-lg hover:-translate-y-0.5">
        Find My EV &#x2192;
      </a>
      <a href="<?= base_url('compare') ?>"
         class="inline-flex items-center justify-center gap-2 border border-white/25 text-white/80 hover:text-white font-semibold text-base px-7 py-3.5 rounded-full hover:bg-white/10 transition-all duration-200">
        Compare EVs
      </a>
    </div>

    <!-- Trust strip -->
    <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm mt-6">
      <?php
      $heroProof = [
        ['icon' => '&#9889;', 'label' => '150+ EVs listed'],
        ['icon' => '&#127978;', 'label' => '18 States'],
        ['icon' => '&#128295;', 'label' => '25+ Free tools'],
        ['icon' => '&#127873;', 'label' => 'Up to &#8377;1.5L subsidy'],
      ];
      foreach ($heroProof as $pi => $hp):
      ?>
        <?php if ($pi > 0): ?><span class="w-px h-3 bg-white/20 hidden sm:block"></span><?php endif; ?>
        <span class="flex items-center gap-1.5 text-white/50 text-xs">
          <span><?= $hp['icon'] ?></span>
          <span><?= $hp['label'] ?></span>
        </span>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Scroll indicator -->
  <div class="scroll-indicator absolute bottom-8 left-1/2 -translate-x-1/2" aria-hidden="true">
    <svg class="w-6 h-6 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
    </svg>
  </div>
</section>



<!-- ================================================================
  SECTION 2 — EV CATEGORIES (app-style tabs) [MOVED UP FROM SECTION 4]
================================================================ -->
<section class="py-16 reveal" style="background:#f8fdf8">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="flex items-center justify-between mb-12">
      <div>
        <span class="inline-block text-emerald-400 text-xs font-bold uppercase tracking-widest mb-3">Our Vehicle Range</span>
        <h2 class="text-4xl sm:text-5xl font-black leading-tight" style="color:#e6f1f1">Browse by<br><span style="color:#10b981">category</span></h2>
        <p class="text-base mt-3" style="color:#a8b9b9">All EV types. Every brand. One place.</p>
      </div>
      <a href="<?= base_url('vehicles') ?>" class="hidden sm:inline-flex items-center gap-1 text-cyan-400 font-semibold text-sm hover:gap-2 transition-all">
        View all <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
      </a>
    </div>

    <!-- 4 main category cards (like app tabs, large) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <?php
      $mainCats = [
        [
          'icon'  => '&#128757;',
          'name'  => '2 Wheelers',
          'sub'   => 'Scooters & Motorcycles',
          'url'   => base_url('electric-scooters'),
          'color' => 'from-[#152b30] to-[#13262b]',
          'bdr'   => 'border-white/10',
          'ic'    => 'bg-[rgba(0,153,153,.15)]',
        ],
        [
          'icon'  => '&#128662;',
          'name'  => '3 Wheelers',
          'sub'   => 'E-Rickshaws & Loaders',
          'url'   => base_url('electric-rickshaws'),
          'color' => 'from-[#152b30] to-[#13262b]',
          'bdr'   => 'border-white/10',
          'ic'    => 'bg-[rgba(22,196,196,.15)]',
        ],
        [
          'icon'  => '&#128664;',
          'name'  => '4 Wheelers',
          'sub'   => 'Cars, Sedans & SUVs',
          'url'   => base_url('electric-cars'),
          'color' => 'from-[#152b30] to-[#13262b]',
          'bdr'   => 'border-white/10',
          'ic'    => 'bg-[rgba(56,189,248,.15)]',
        ],
        [
          'icon'  => '&#128666;',
          'name'  => 'Commercial',
          'sub'   => 'Buses, Trucks & Fleets',
          'url'   => base_url('commercial-ev'),
          'color' => 'from-[#152b30] to-[#13262b]',
          'bdr'   => 'border-white/10',
          'ic'    => 'bg-[rgba(0,153,153,.15)]',
        ],
      ];
      foreach ($mainCats as $cat): ?>
      <a href="<?= $cat['url'] ?>"
         onclick="charjTrack('category_click',{category:'<?= addslashes(esc($cat['name'])) ?>'})"
         class="group flex flex-col items-center text-center p-6 sm:p-8 rounded-3xl bg-gradient-to-br <?= $cat['color'] ?> border-2 border-emerald-500/30 hover:border-emerald-500 hover:shadow-xl hover:-translate-y-2 transition-all duration-200 cursor-pointer">
        <div class="w-16 h-16 rounded-2xl <?= $cat['ic'] ?> flex items-center justify-center text-4xl mb-4 group-hover:scale-125 transition-transform duration-200">
          <?= $cat['icon'] ?>
        </div>
        <span class="font-black text-white text-lg"><?= $cat['name'] ?></span>
        <span class="text-slate-300 text-sm mt-2"><?= $cat['sub'] ?></span>
      </a>
      <?php endforeach; ?>
    </div>

    <!-- Sub-category quick links -->
    <div class="flex flex-wrap gap-3 mt-8">
      <?php
      $subCats = [
        ['label'=>'Electric Scooters',    'url'=>base_url('electric-scooters')],
        ['label'=>'Electric Motorcycles', 'url'=>base_url('electric-bikes')],
        ['label'=>'Electric Cars',        'url'=>base_url('electric-cars')],
        ['label'=>'Electric SUVs',        'url'=>base_url('electric-cars')],
        ['label'=>'E-Rickshaws',          'url'=>base_url('electric-rickshaws')],
        ['label'=>'Electric Buses',       'url'=>base_url('commercial-ev')],
      ];
      foreach ($subCats as $sc): ?>
      <a href="<?= $sc['url'] ?>"
         class="text-sm font-semibold text-slate-300 border-2 border-emerald-500/40 rounded-full px-5 py-2.5 hover:text-emerald-300 hover:border-emerald-500 hover:bg-emerald-500/10 transition-all duration-150" style="background:rgba(16,185,129,.04)">
        <?= $sc['label'] ?>
      </a>
      <?php endforeach; ?>
    </div>

  </div>
</section>


<!-- ================================================================
  SECTION 3 — CHARGING STATIONS NEAR YOU [NEW GEOLOCATION SECTION]
================================================================ -->
<section class="py-16 reveal" style="background:#f5fdf4">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="text-center mb-12">
      <span class="inline-block text-emerald-600 text-xs font-bold uppercase tracking-widest mb-3">Charging Infrastructure</span>
      <h2 class="text-4xl sm:text-5xl font-black text-slate-900 mb-4 leading-tight">
        Find charging stations<br><span style="color:#16a34a">near you</span>
      </h2>
      <p class="text-slate-600 text-lg max-w-2xl mx-auto">Get real-time charging station locations, compare networks, check availability and pricing — all in one place.</p>
    </div>

    <!-- Geolocation finder with map -->
    <div x-data="chargingStationsFinder()" class="space-y-6">

      <!-- Top controls -->
      <div class="flex flex-col sm:flex-row gap-3 justify-center mb-8">
        <button @click="detectLocation()"
                :disabled="detecting"
                class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold px-8 py-3 rounded-full transition-all duration-200 text-base">
          <span x-show="!detecting" aria-hidden="true">📍</span>
          <span x-show="detecting" class="geolocation-spinner"></span>
          <span x-text="detecting ? 'Detecting location...' : 'Detect My Location'"></span>
        </button>
        <div class="relative flex-1 sm:flex-initial">
          <input type="text" x-model="searchCity" placeholder="Or search by city..."
                 @keyup.enter="searchByCity()"
                 class="w-full sm:w-48 px-4 py-3 rounded-full border border-emerald-300 bg-white text-slate-900 placeholder-slate-400 focus:border-emerald-500 focus:bg-emerald-50 outline-none transition-all"
                 aria-label="Search charging stations by city">
          <button @click="searchByCity()" class="absolute right-2 top-1/2 -translate-y-1/2" style="color:#16a34a">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
          </button>
        </div>
      </div>

      <!-- User location display -->
      <div x-show="userLocation" class="text-center text-sm text-slate-600 mb-4">
        📌 Showing chargers near <span x-text="locationName" class="font-semibold" style="color:#16a34a"></span>
      </div>

      <!-- Map container -->
      <div id="charging-map" class="charging-map-container"></div>

      <!-- Results section -->
      <div class="grid md:grid-cols-3 gap-6">

        <!-- Map stats (left) -->
        <div class="space-y-4">
          <div class="rounded-2xl p-6" style="background:#f0fdf4;border:2px solid rgba(22,163,74,.3)">
            <div class="text-3xl font-black" style="color:#16a34a" mb-2 x-text="stationCount + '+'"></div>
            <p class="text-sm text-slate-600 font-semibold">Stations found</p>
          </div>
          <div class="rounded-2xl p-6" style="background:#f0fdf4;border:2px solid rgba(22,163,74,.3)">
            <div class="text-3xl font-black" style="color:#16a34a" mb-2 x-text="Math.round(nearestDistance || 0) + ' km'"></div>
            <p class="text-sm text-slate-600 font-semibold">Closest charger</p>
          </div>
          <a href="<?= base_url('charger-check') ?>"
             onclick="charjTrack('charging_section_explore',{})"
             class="inline-flex items-center justify-center gap-2 w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-4 py-3 rounded-full transition-all duration-200 text-sm">
            Advanced Search &#x2192;
          </a>
        </div>

        <!-- List of nearby stations (right 2 cols) -->
        <div class="md:col-span-2 space-y-3 max-h-96 overflow-y-auto">
          <template x-for="(station, idx) in nearbyStations.slice(0, 8)" :key="idx">
            <div class="charging-station-item rounded-2xl p-5 border-2 border-emerald-200 hover:border-emerald-500/60 hover:bg-emerald-50 cursor-pointer" style="background:#f0fdf4"
                 @click="selectStation(station)">
              <div class="flex items-start justify-between mb-3">
                <div class="flex-1">
                  <h3 class="font-bold text-slate-900 text-base" x-text="station.name"></h3>
                  <p class="text-sm text-slate-600 mt-1" x-text="station.network || 'Network info unavailable'"></p>
                </div>
                <span class="charging-distance font-bold text-lg" style="color:#16a34a" x-text="Math.round(station.distance * 10) / 10 + ' km'"></span>
              </div>

              <!-- Charger types -->
              <div class="mb-2 flex flex-wrap">
                <template x-for="type in (station.charger_types || ['Unknown'])" :key="type">
                  <span class="charger-type-badge" x-text="type"></span>
                </template>
              </div>

              <!-- Pricing if available -->
              <template x-if="station.pricing">
                <p class="text-xs" style="color:#16a34a">
                  <span class="font-semibold">₹<span x-text="station.pricing.toFixed(2)"></span>/kWh</span>
                </p>
              </template>

              <!-- View details link -->
              <a :href="'<?= base_url('charger-check') ?>?station=' + station.id" class="text-xs font-semibold inline-flex items-center gap-1 mt-2" style="color:#16a34a">
                View details <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
              </a>
            </div>
          </template>

          <!-- No results message -->
          <template x-if="!loading && nearbyStations.length === 0">
            <div class="text-center py-8 text-slate-400">
              <div class="text-4xl mb-3">📍</div>
              <p class="text-sm">Allow location access or search by city to find nearby chargers</p>
            </div>
          </template>

          <!-- Loading state -->
          <template x-if="loading">
            <div class="text-center py-8 text-slate-400">
              <div class="inline-block geolocation-spinner mb-3"></div>
              <p class="text-sm">Finding nearby charging stations...</p>
            </div>
          </template>
        </div>
      </div>

      <!-- Network stats -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 mt-12 pt-8 border-t border-emerald-200">
        <div class="text-center">
          <div class="text-3xl font-black" style="color:#16a34a" mb-2>5,000+</div>
          <p class="text-sm text-slate-600 font-medium">Public charging points</p>
        </div>
        <div class="text-center">
          <div class="text-3xl font-black" style="color:#16a34a" mb-2>12+</div>
          <p class="text-sm text-slate-600 font-medium">Major networks</p>
        </div>
        <div class="text-center">
          <div class="text-3xl font-black" style="color:#16a34a" mb-2>18</div>
          <p class="text-sm text-slate-600 font-medium">States covered</p>
        </div>
        <div class="text-center">
          <div class="text-3xl font-black" style="color:#16a34a" mb-2>Live</div>
          <p class="text-sm text-slate-600 font-medium">Real-time data</p>
        </div>
      </div>

    </div>

  </div>
</section>


<!-- ================================================================
  SECTION 4 — ANIMATED STATS BAR
================================================================ -->
<section
  class="py-12" style="background:#f0fdf4;border-top:1px solid #dcfce7;border-bottom:1px solid #dcfce7"
  x-data="{
    triggered: false,
    counters: [
      { label: 'EVs Listed',            raw: '0',   target: 150,  suffix: '+',  prefix: '',   decimal: false, icon: '⚡' },
      { label: 'Max Subsidy Available', raw: '₹0',  target: 1.5,  suffix: 'L',  prefix: '₹',  decimal: true,  icon: '🎁' },
      { label: 'Tools & Calculators',   raw: '0',   target: 8,    suffix: '+',  prefix: '',   decimal: false, icon: '🛠️' },
      { label: 'States Covered',        raw: '0',   target: 18,   suffix: '',   prefix: '',   decimal: false, icon: '🗺️' }
    ],
    animate() {
      if (this.triggered) return;
      this.triggered = true;
      const self = this;
      self.counters.forEach(function(c, i) {
        const steps = 50;
        const dur = 1600;
        let step = 0;
        const timer = setInterval(function() {
          step++;
          const val = Math.min((c.target / steps) * step, c.target);
          const fmt = c.decimal ? val.toFixed(1) : Math.floor(val).toLocaleString('en-IN');
          self.counters[i].raw = c.prefix + fmt;
          if (step >= steps) clearInterval(timer);
        }, dur / steps);
      });
    }
  }"
  x-intersect.once="animate()"
>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-2 md:grid-cols-4">
      <template x-for="(stat, idx) in counters" :key="idx">
        <div class="stat-item flex flex-col items-center text-center px-6 py-4">
          <span class="text-2xl mb-3" x-text="stat.icon" aria-hidden="true"></span>
          <div class="flex items-baseline gap-0.5">
            <span class="text-4xl font-black text-slate-100" x-text="stat.raw"></span>
            <span class="text-xl font-black text-cyan-400" x-text="stat.suffix"></span>
          </div>
          <span class="text-sm text-slate-400 mt-1 font-medium" x-text="stat.label"></span>
        </div>
      </template>
    </div>
  </div>
</section>


<!-- ================================================================
  SECTION 5 — WHAT IS CHARJ.IN?
================================================================ -->
<section class="bg-gradient-to-br from-slate-900 to-teal-950 py-16 reveal">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid md:grid-cols-2 gap-10 items-center">

      <!-- Left: description -->
      <div>
        <span class="inline-block text-teal-400 text-xs font-bold uppercase tracking-widest mb-4">What is charj.in?</span>
        <h2 class="text-3xl sm:text-4xl font-black text-white mb-5 leading-tight">
          India's dedicated<br><span class="text-teal-400">EV marketplace</span>
        </h2>
        <p class="text-slate-300 text-base leading-relaxed mb-6">
          charj.in is India's dedicated online marketplace for <strong class="text-white">electric vehicles</strong>. We list EVs from all brands across India — from e-scooters to electric cars and commercial EVs — bringing everything together in one place.
        </p>
        <p class="text-slate-400 text-sm leading-relaxed">
          We connect EV brands with buyers, making it simple to discover, compare and choose the right EV.
        </p>
      </div>

      <!-- Right: feature bullets -->
      <div class="grid grid-cols-1 gap-3">
        <?php
        $whatFeatures = [
          ['icon' => '&#128269;', 'text' => 'Discover all EV brands and models'],
          ['icon' => '&#9878;',   'text' => 'Compare features, specs, and prices'],
          ['icon' => '&#128240;', 'text' => 'Stay updated with the latest EV news'],
          ['icon' => '&#128101;', 'text' => 'Find dealers and booking links easily'],
        ];
        foreach ($whatFeatures as $wf): ?>
        <div class="flex items-center gap-4 bg-white/5 border border-white/10 rounded-xl px-5 py-4">
          <span class="text-2xl flex-shrink-0" aria-hidden="true"><?= $wf['icon'] ?></span>
          <span class="text-white font-semibold text-sm"><?= $wf['text'] ?></span>
          <svg class="w-4 h-4 text-teal-400 ml-auto flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
        </div>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</section>


<!-- ================================================================
  SECTION 6 — WHY CHARJ.IN? (5-col)
================================================================ -->
<section class="py-20 reveal" style="background:#0c1a1d">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="text-center mb-12">
      <h2 class="text-3xl sm:text-4xl font-black" style="color:#e6f1f1">
        Why <span class="text-cyan-400">charj.in</span>?
      </h2>
      <div class="w-12 h-1 bg-[#009999] rounded-full mx-auto mt-4"></div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-6 text-center">
      <?php
      $whyFeatures = [
        ['icon'=>'&#128269;', 'title'=>'All EVs. One Place.', 'desc'=>'Explore 2W, 3W, 4W and commercial EVs from all leading brands.'],
        ['icon'=>'&#9878;',   'title'=>'Compare Easily',      'desc'=>'Compare specifications, range, price and features to make the right choice.'],
        ['icon'=>'&#9889;',   'title'=>'100% Focused on EV',  'desc'=>'A platform built exclusively for electric mobility in India.'],
        ['icon'=>'&#128240;', 'title'=>'Latest EV Updates',   'desc'=>'News, launches, reviews and insights from the EV world.'],
        ['icon'=>'&#128205;', 'title'=>'Find & Connect',      'desc'=>'Find dealers, explore booking options and connect with brands near you.'],
      ];
      foreach ($whyFeatures as $wf): ?>
      <div class="flex flex-col items-center">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl mb-4" style="background:rgba(0,153,153,.12);border:1px solid rgba(0,153,153,.3)">
          <?= $wf['icon'] ?>
        </div>
        <h3 class="font-bold text-sm mb-2 leading-tight" style="color:#e6f1f1"><?= $wf['title'] ?></h3>
        <p class="text-xs leading-relaxed" style="color:#8ba3a3"><?= $wf['desc'] ?></p>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>


<!-- ================================================================
  SECTION 7 — FEATURE GRID "Everything you need"
================================================================ -->
<section class="py-20" style="background:#0f2125">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Header -->
    <div class="text-center mb-12 reveal">
      <span class="inline-block text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-full mb-4" style="background:rgba(0,153,153,.12);color:#16c4c4;border:1px solid rgba(0,153,153,.3)">What Charj.in does</span>
      <h2 class="text-3xl sm:text-4xl font-black leading-tight" style="color:#e6f1f1">Built for India's EV revolution</h2>
      <p class="mt-3 text-lg max-w-xl mx-auto" style="color:#8ba3a3">Not a listing site. A decision engine.</p>
    </div>

    <!-- 16-feature grid (6 cards per row) -->
    <?php
    $features = [
      ['icon' => '🔧', 'title' => 'Spare Parts',          'desc' => 'Quality EV parts from trusted vendors',                          'url' => base_url('spare-parts'),        'stagger' => 'stagger-1'],
      ['icon' => '🎯', 'title' => 'EV Finder Quiz',       'desc' => 'Find your perfect EV match in 2 min',                            'url' => base_url('find-my-ev'),         'stagger' => 'stagger-2'],
      ['icon' => '⚖️', 'title' => 'Compare EVs',          'desc' => 'Compare specs, range & price',                                   'url' => base_url('compare'),            'stagger' => 'stagger-3'],
      ['icon' => '🎁', 'title' => 'Subsidy Calculator',   'desc' => 'FAME II + state subsidies',                                      'url' => base_url('subsidy-calculator'), 'stagger' => 'stagger-4'],
      ['icon' => '🎪', 'title' => 'Events & Expos',       'desc' => 'Upcoming EV launches & expos',                                    'url' => base_url('events'),             'stagger' => 'stagger-5'],
      ['icon' => '📢', 'title' => 'Announcements',        'desc' => 'Latest EV news & updates',                                        'url' => base_url('announcements'),      'stagger' => 'stagger-6'],
      ['icon' => '⚡', 'title' => 'Charging Cost/KM',     'desc' => '1 km cost vs petrol rate',                                        'url' => base_url('charging-cost'),      'stagger' => 'stagger-1'],
      ['icon' => '📍', 'title' => 'Find Chargers',        'desc' => 'Charging stations near you',                                      'url' => base_url('charging-stations'),  'stagger' => 'stagger-2'],
      ['icon' => '🗺️', 'title' => 'Trip Range Checker',   'desc' => 'Plan your EV road trips',                                         'url' => base_url('can-i-make-it'),      'stagger' => 'stagger-3'],
      ['icon' => '💰', 'title' => 'Cost of Ownership',    'desc' => '5-year ownership costs',                                          'url' => base_url('tco-calculator'),     'stagger' => 'stagger-4'],
      ['icon' => '🔌', 'title' => 'Charger Check',        'desc' => 'EV & charger compatibility',                                      'url' => base_url('charger-check'),      'stagger' => 'stagger-5'],
      ['icon' => '📈', 'title' => 'Resale Value',         'desc' => '3-year resale estimates',                                         'url' => base_url('resale-estimator'),   'stagger' => 'stagger-6'],
    ];
    ?>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
      <?php foreach ($features as $f): ?>
      <a href="<?= $f['url'] ?>"
         onclick="charjTrack('feature_click',{feature:'<?= addslashes(esc($f['title'])) ?>'})"
         class="reveal animate-fade-in-up <?= $f['stagger'] ?> rounded-xl p-4 card-hover block transition-all duration-200 hover:-translate-y-1 hover:shadow-lg" style="background:#152b30;border:1px solid rgba(255,255,255,.06)">
        <span class="text-2xl block mb-2" aria-hidden="true"><?= $f['icon'] ?></span>
        <h3 class="font-bold text-sm" style="color:#e6f1f1"><?= esc($f['title']) ?></h3>
        <p class="text-xs mt-1 leading-relaxed line-clamp-2" style="color:#8ba3a3"><?= esc($f['desc']) ?></p>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- ================================================================
  SECTION 8 — RANKED EVs by CATEGORY (CarDekho style)
================================================================ -->
<section class="py-16 reveal" style="background:#f5fdf4">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8"
       x-data="{ tab: '2-wheeler' }">

    <!-- Section header -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
      <div>
        <span class="inline-block text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-full mb-3" style="background:rgba(0,153,153,.15);color:#16c4c4">Top Picks 2025</span>
        <h2 class="text-2xl sm:text-3xl font-black" style="color:#e6f1f1">Best EVs in India</h2>
        <p class="text-sm mt-1" style="color:#8ba3a3">Ranked by expert rating, range &amp; value</p>
      </div>
      <a href="<?= base_url('vehicles') ?>" class="text-sm font-bold text-cyan-400 hover:text-cyan-300 flex items-center gap-1 transition-colors">
        View all EVs <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
      </a>
    </div>

    <!-- Category tabs -->
    <div class="flex gap-2 mb-6 overflow-x-auto scrollbar-hide pb-1">
      <?php
      $catTabs = [
        '2-wheeler' => ['label' => '🛵 2-Wheelers', 'sub' => 'Scooters & Bikes'],
        '3-wheeler' => ['label' => '🛺 3-Wheelers', 'sub' => 'Rickshaws & Loaders'],
        '4-wheeler' => ['label' => '🚗 4-Wheelers', 'sub' => 'Cars & SUVs'],
      ];
      foreach ($catTabs as $key => $tab): ?>
      <button @click="tab = '<?= $key ?>'"
              :class="tab === '<?= $key ?>' ? 'text-white shadow-lg border-[#009999]' : 'border text-slate-400 hover:border-cyan-500 hover:text-cyan-300'" :style="tab === '<?= $key ?>' ? 'background:#009999;border:1px solid #009999' : 'background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1)'"
              class="flex-shrink-0 flex flex-col items-start px-5 py-3 rounded-2xl border-2 transition-all duration-200 text-left">
        <span class="font-bold text-sm"><?= $tab['label'] ?></span>
        <span class="text-xs opacity-70 mt-0.5"><?= $tab['sub'] ?></span>
      </button>
      <?php endforeach; ?>
    </div>

    <?php
    $rankedByCategory = $rankedByCategory ?? [];
    $badges = ['#1 Best', '#2', '#3', '#4', '#5', '#6'];
    $badgeColors = [
      'bg-amber-400 text-amber-900',
      'bg-white/10 text-slate-200',
      'bg-orange-300 text-orange-900',
      'bg-white/5 text-slate-400',
      'bg-white/5 text-slate-400',
      'bg-white/5 text-slate-400',
    ];
    $imgCdnMap = [
      'ather-450x'       => 'https://imgd.aeplcdn.com/664x374/n/cw/ec/130397/450x-exterior-right-front-three-quarter.jpeg',
      'ola-s1-pro'       => 'https://imgd.aeplcdn.com/664x374/n/cw/ec/155817/s1-pro-exterior-right-front-three-quarter-5.jpeg',
      'tvs-iqube'        => 'https://imgd.aeplcdn.com/664x374/n/cw/ec/44686/iqube-electric-right-front-three-quarter.jpeg',
      'revolt-rv400'     => 'https://imgd.aeplcdn.com/664x374/n/cw/ec/45166/rv400-right-front-three-quarter-2.jpeg',
      'tata-nexon-ev'    => 'https://imgd.aeplcdn.com/664x374/n/cw/ec/141867/nexon-ev-exterior-right-front-three-quarter-11.jpeg',
      'tata-tiago-ev'    => 'https://imgd.aeplcdn.com/664x374/n/cw/ec/166185/tiago-ev-exterior-right-front-three-quarter-2.jpeg',
      'mg-zs-ev'         => 'https://imgd.aeplcdn.com/664x374/n/cw/ec/87078/zs-ev-exterior-right-front-three-quarter-3.jpeg',
      'mg-comet-ev'      => 'https://imgd.aeplcdn.com/664x374/n/cw/ec/182877/comet-ev-exterior-right-front-three-quarter-5.jpeg',
      'mahindra-treo'    => 'https://imgd.aeplcdn.com/664x374/n/cw/ec/36659/treo-exterior-right-front-three-quarter-2.jpeg',
    ];

    foreach (['2-wheeler','3-wheeler','4-wheeler'] as $catKey):
      $vehicles = $rankedByCategory[$catKey] ?? [];
      // Fallback static data if DB empty
      if (empty($vehicles) && $catKey === '2-wheeler') {
        $vehicles = [
          ['name'=>'Ather 450X','slug'=>'ather-450x','brand_name'=>'Ather Energy','starting_price'=>139000,'claimed_range'=>146,'expert_rating'=>8.8,'category_name'=>'Electric Scooters'],
          ['name'=>'Ola S1 Pro','slug'=>'ola-s1-pro','brand_name'=>'Ola Electric','starting_price'=>139999,'claimed_range'=>195,'expert_rating'=>8.2,'category_name'=>'Electric Scooters'],
          ['name'=>'TVS iQube','slug'=>'tvs-iqube','brand_name'=>'TVS Motor','starting_price'=>142750,'claimed_range'=>145,'expert_rating'=>8.5,'category_name'=>'Electric Scooters'],
          ['name'=>'Revolt RV400','slug'=>'revolt-rv400','brand_name'=>'Revolt Motors','starting_price'=>124999,'claimed_range'=>150,'expert_rating'=>7.8,'category_name'=>'Electric Bikes'],
        ];
      }
      if (empty($vehicles) && $catKey === '4-wheeler') {
        $vehicles = [
          ['name'=>'Tata Nexon EV','slug'=>'tata-nexon-ev','brand_name'=>'Tata Motors','starting_price'=>1449000,'claimed_range'=>465,'expert_rating'=>9.0,'category_name'=>'Electric Cars'],
          ['name'=>'Tata Tiago EV','slug'=>'tata-tiago-ev','brand_name'=>'Tata Motors','starting_price'=>849000,'claimed_range'=>315,'expert_rating'=>8.4,'category_name'=>'Electric Cars'],
          ['name'=>'MG ZS EV','slug'=>'mg-zs-ev','brand_name'=>'MG Motor','starting_price'=>1898000,'claimed_range'=>461,'expert_rating'=>8.7,'category_name'=>'Electric SUVs'],
          ['name'=>'MG Comet EV','slug'=>'mg-comet-ev','brand_name'=>'MG Motor','starting_price'=>798000,'claimed_range'=>230,'expert_rating'=>7.9,'category_name'=>'Electric Cars'],
        ];
      }
      if (empty($vehicles) && $catKey === '3-wheeler') {
        $vehicles = [
          ['name'=>'Mahindra Treo','slug'=>'mahindra-treo','brand_name'=>'Mahindra','starting_price'=>295000,'claimed_range'=>170,'expert_rating'=>8.2,'category_name'=>'Electric Rickshaws'],
          ['name'=>'Piaggio Ape E-City','slug'=>'piaggio-ape-e-city','brand_name'=>'Piaggio','starting_price'=>350000,'claimed_range'=>104,'expert_rating'=>7.9,'category_name'=>'Electric Loaders'],
        ];
      }
    ?>
    <!-- Tab panel -->
    <div x-show="tab === '<?= $catKey ?>'" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0">

      <?php if (empty($vehicles)): ?>
      <div class="text-center py-12 text-slate-400">
        <div class="text-4xl mb-3"><?= ['2-wheeler'=>'🛵','3-wheeler'=>'🛺','4-wheeler'=>'🚗'][$catKey] ?></div>
        <p class="text-sm">No EVs in this category yet. <a href="<?= base_url('vehicles') ?>" class="text-cyan-400 font-semibold">Browse all EVs →</a></p>
      </div>
      <?php else: ?>

      <!-- Top 3 featured cards -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
        <?php foreach (array_slice($vehicles, 0, 3) as $ri => $v):
          $slug = $v['slug'] ?? '#';
          $price = (int)($v['starting_price'] ?? 0);
          $range = (int)($v['claimed_range'] ?? $v['real_world_range'] ?? 0);
          $rating = (float)($v['expert_rating'] ?? 0);
          $imgSrc = $imgCdnMap[$slug] ?? (!empty($v['image_url']) ? $v['image_url'] : '');
          $priceStr = $price >= 10000000 ? '₹'.round($price/10000000,2).' Cr' : ($price >= 100000 ? '₹'.round($price/100000,1).' L' : '₹'.number_format($price));
        ?>
        <a href="<?= base_url('vehicles/'.$slug) ?>"
           class="group relative rounded-2xl border-2 <?= $ri===0 ? 'border-amber-500' : '' ?> overflow-hidden transition-all hover:-translate-y-1" style="background:#152b30;border-color:<?= $ri===0 ? '#f59e0b' : 'rgba(255,255,255,.06)' ?>">

          <!-- Rank badge -->
          <div class="absolute top-3 left-3 z-10">
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full text-xs font-black <?= $badgeColors[$ri] ?> shadow-sm">
              <?= $ri+1 ?>
            </span>
          </div>

          <?php if ($ri === 0): ?>
          <div class="absolute top-3 right-3 z-10">
            <span class="bg-amber-400 text-amber-900 text-[10px] font-black px-2 py-0.5 rounded-full">⭐ #1 Rated</span>
          </div>
          <?php endif; ?>

          <!-- Vehicle image -->
          <div class="h-40 flex items-center justify-center overflow-hidden" style="background:linear-gradient(135deg,#0f2125,#13262b)">
            <?php if ($imgSrc): ?>
            <img src="<?= esc($imgSrc) ?>"
                 onerror="this.onerror=null;this.src='https://placehold.co/400x200/f0fdf4/006044?text=<?= urlencode($v['name']??'EV') ?>'"
                 alt="<?= esc($v['name']??'') ?>"
                 class="w-full h-full object-contain p-3 group-hover:scale-105 transition-transform duration-300">
            <?php else: ?>
            <div class="text-6xl opacity-30"><?= ['2-wheeler'=>'🛵','3-wheeler'=>'🛺','4-wheeler'=>'🚗'][$catKey] ?></div>
            <?php endif; ?>
          </div>

          <!-- Info -->
          <div class="p-4">
            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-0.5"><?= esc($v['brand_name']??'') ?></p>
            <h3 class="font-black text-slate-100 text-sm leading-tight mb-2 group-hover:text-cyan-300 transition-colors"><?= esc($v['name']??'') ?></h3>

            <div class="flex items-center gap-2 mb-3">
              <?php if ($rating > 0): ?>
              <div class="flex items-center gap-1 rounded-lg px-2 py-0.5" style="background:rgba(0,153,153,.1)">
                <span class="text-amber-400 text-xs">★</span>
                <span class="text-xs font-black text-cyan-300"><?= number_format($rating,1) ?></span>
              </div>
              <?php endif; ?>
              <?php if ($range > 0): ?>
              <span class="text-xs text-slate-400 font-semibold">📏 <?= $range ?> km</span>
              <?php endif; ?>
            </div>

            <div class="flex items-center justify-between">
              <div>
                <div class="text-[10px] text-slate-400">Starting</div>
                <div class="text-base font-black text-slate-100"><?= $priceStr ?></div>
              </div>
              <span class="text-[10px] font-bold text-cyan-300 px-2 py-1 rounded-lg" style="background:rgba(0,153,153,.1)">
                <?= esc($v['category_name']??'EV') ?>
              </span>
            </div>
          </div>
        </a>
        <?php endforeach; ?>
      </div>

      <!-- Remaining as compact list rows -->
      <?php $remaining = array_slice($vehicles, 3); if (!empty($remaining)): ?>
      <div class="rounded-2xl shadow-sm overflow-hidden" style="background:#152b30;border:1px solid rgba(255,255,255,.06)">
        <?php foreach ($remaining as $ri => $v):
          $actualRank = $ri + 4;
          $slug = $v['slug'] ?? '#';
          $price = (int)($v['starting_price'] ?? 0);
          $range = (int)($v['claimed_range'] ?? $v['real_world_range'] ?? 0);
          $rating = (float)($v['expert_rating'] ?? 0);
          $imgSrc = $imgCdnMap[$slug] ?? (!empty($v['image_url']) ? $v['image_url'] : '');
          $priceStr = $price >= 10000000 ? '₹'.round($price/10000000,2).' Cr' : ($price >= 100000 ? '₹'.round($price/100000,1).' L' : '₹'.number_format($price));
        ?>
        <a href="<?= base_url('vehicles/'.$slug) ?>"
           class="flex items-center gap-4 px-5 py-3.5 hover:bg-white/5 transition-colors group <?= $ri < count($remaining)-1 ? 'border-b border-white/10' : '' ?>">
          <!-- Rank -->
          <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-black flex-shrink-0" style="background:rgba(255,255,255,.06);color:#8ba3a3"><?= $actualRank ?></div>
          <!-- Image -->
          <div class="w-16 h-11 rounded-xl flex items-center justify-center overflow-hidden flex-shrink-0" style="background:linear-gradient(135deg,#0f2125,#13262b)">
            <?php if ($imgSrc): ?>
            <img src="<?= esc($imgSrc) ?>" alt="<?= esc($v['name']??'') ?>" class="w-full h-full object-contain p-1.5">
            <?php else: ?>
            <span class="text-2xl opacity-30"><?= ['2-wheeler'=>'🛵','3-wheeler'=>'🛺','4-wheeler'=>'🚗'][$catKey] ?></span>
            <?php endif; ?>
          </div>
          <!-- Name + type -->
          <div class="flex-1 min-w-0">
            <div class="font-bold text-slate-100 text-sm group-hover:text-cyan-300 transition-colors"><?= esc($v['name']??'') ?></div>
            <div class="text-xs text-slate-400"><?= esc($v['brand_name']??'') ?> · <?= esc($v['category_name']??'') ?></div>
          </div>
          <!-- Stats -->
          <div class="hidden sm:flex items-center gap-3 flex-shrink-0">
            <?php if ($rating > 0): ?><span class="text-xs font-bold text-amber-600">★ <?= number_format($rating,1) ?></span><?php endif; ?>
            <?php if ($range > 0): ?><span class="text-xs text-slate-400"><?= $range ?>km</span><?php endif; ?>
          </div>
          <!-- Price -->
          <div class="text-right flex-shrink-0">
            <div class="text-sm font-black text-slate-100"><?= $priceStr ?></div>
          </div>
          <svg class="w-4 h-4 text-slate-500 group-hover:text-cyan-400 flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
        </a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <div class="mt-5 text-center">
        <a href="<?= base_url('vehicles?category=' . urlencode($catKey)) ?>"
           class="inline-flex items-center gap-2 hover:brightness-110 text-white font-bold text-sm px-6 py-3 rounded-full transition-all shadow-md" style="background:#009999">
          See all <?= $catTabs[$catKey]['label'] ?> <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
        </a>
      </div>

      <?php endif; // empty vehicles ?>
    </div>
    <?php endforeach; ?>

  </div>
</section>


<!-- ================================================================
  SECTION 9 — HOW IT WORKS
================================================================ -->
<section class="py-20 reveal" style="background:#0c1a1d">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="text-center mb-12">
      <span class="inline-block text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-full mb-4" style="background:rgba(0,153,153,.15);color:#16c4c4">Simple Process</span>
      <h2 class="text-3xl sm:text-4xl font-black leading-tight" style="color:#e6f1f1">
        From confused to confident<br class="hidden sm:block"> in 3 steps
      </h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8 relative">

      <!-- Connector line (desktop) -->
      <div class="hidden md:block absolute top-10 left-[calc(33.33%+0px)] right-[calc(33.33%+0px)] h-px pointer-events-none"
           style="background: linear-gradient(90deg, transparent, rgba(0,153,153,0.4), #16c4c4, rgba(0,153,153,0.4), transparent);"
           aria-hidden="true"></div>

      <!-- Step 1 -->
      <div class="reveal animate-fade-in-up stagger-1 flex flex-col items-center text-center">
        <div class="relative mb-6">
          <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-4xl shadow-sm" style="background:rgba(0,153,153,.12)">
            🎯
            <span class="absolute -top-2 -right-2 w-7 h-7 text-white text-xs font-black rounded-full flex items-center justify-center shadow-md" style="background:#009999">01</span>
          </div>
        </div>
        <h3 class="text-lg font-bold text-slate-100 mb-2">Tell us about yourself</h3>
        <p class="text-sm text-slate-400 leading-relaxed max-w-xs mb-4">2-minute quiz about your daily commute, budget and charging situation.</p>
        <a href="<?= base_url('find-my-ev') ?>" class="text-cyan-400 text-sm font-semibold hover:text-cyan-300 transition-colors">Take the quiz →</a>
      </div>

      <!-- Step 2 -->
      <div class="reveal animate-fade-in-up stagger-2 flex flex-col items-center text-center">
        <div class="relative mb-6">
          <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-4xl shadow-sm" style="background:rgba(22,196,196,.12)">
            📊
            <span class="absolute -top-2 -right-2 w-7 h-7 text-white text-xs font-black rounded-full flex items-center justify-center shadow-md" style="background:#16c4c4">02</span>
          </div>
        </div>
        <h3 class="text-lg font-bold text-slate-100 mb-2">See your perfect matches</h3>
        <p class="text-sm text-slate-400 leading-relaxed max-w-xs">Ranked EVs with real-world range, subsidy-adjusted price and EMI breakdown.</p>
      </div>

      <!-- Step 3 -->
      <div class="reveal animate-fade-in-up stagger-3 flex flex-col items-center text-center">
        <div class="relative mb-6">
          <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-4xl shadow-sm" style="background:rgba(56,189,248,.12)">
            🤝
            <span class="absolute -top-2 -right-2 w-7 h-7 text-white text-xs font-black rounded-full flex items-center justify-center shadow-md" style="background:#38bdf8">03</span>
          </div>
        </div>
        <h3 class="text-lg font-bold text-slate-100 mb-2">Get the best deal</h3>
        <p class="text-sm text-slate-400 leading-relaxed max-w-xs">Connect with verified dealers. One call, no spam, on your terms.</p>
      </div>
    </div>
  </div>
</section>


<!-- ================================================================
  SECTION 10 — SUBSIDY SPOTLIGHT BANNER
================================================================ -->
<section class="py-6 px-4 sm:px-6 lg:px-8 reveal">
  <div class="max-w-7xl mx-auto">
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#009999] to-teal-600 px-8 py-12 md:py-14">

      <!-- Background dots -->
      <div class="absolute inset-0 pointer-events-none"
           style="background-image: radial-gradient(circle, rgba(255,255,255,0.07) 1px, transparent 1px); background-size: 28px 28px;"
           aria-hidden="true"></div>
      <div class="absolute -right-16 -top-16 w-72 h-72 bg-white opacity-5 blur-3xl rounded-full pointer-events-none" aria-hidden="true"></div>

      <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-8">

        <!-- Left content -->
        <div class="flex-1">
          <div class="text-4xl mb-4" aria-hidden="true">🎁</div>
          <h2 class="text-3xl sm:text-4xl font-black text-white mb-3 leading-tight">
            You could get up to<br>
            <span class="text-teal-100">₹1.5 lakh off</span>
          </h2>
          <p class="text-teal-50 text-base leading-relaxed max-w-lg">
            FAME II + state subsidies + 80EEB tax benefit add up fast. See what you qualify for.
          </p>
          <a href="<?= base_url('subsidy-calculator') ?>"
             onclick="charjTrack('subsidy_banner_click',{})"
             class="inline-flex items-center gap-2 mt-6 bg-white font-bold px-7 py-3.5 rounded-full hover:bg-slate-100 transition-all duration-200 shadow-lg hover:-translate-y-0.5" style="color:#007a7a">
            Check My Subsidy →
          </a>
        </div>

        <!-- Right: state pills (desktop only) -->
        <div class="hidden md:flex flex-col gap-3 flex-shrink-0">
          <?php foreach (['Delhi: up to ₹1.55L', 'Gujarat: ₹1.7L', 'Karnataka: ₹10K'] as $pill): ?>
          <div class="subsidy-pill rounded-xl px-5 py-3 text-white font-semibold text-sm">
            <?= esc($pill) ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ================================================================
  SECTION 11 — TESTIMONIALS
================================================================ -->
<section class="py-16 reveal" style="background:#f5fdf4">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="text-center mb-10">
      <h2 class="text-2xl sm:text-3xl font-black" style="color:#e6f1f1">Trusted by EV buyers across India</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
      <?php
      $testimonials = [
        ['name' => 'Rahul M.',  'city' => 'Bangalore', 'avatar' => 'R', 'color' => 'bg-violet-500',
         'text' => 'Found the Ather 450X after taking the quiz. The subsidy calculator showed me I\'d save ₹28,000 — I had no idea! Bought it within a week.'],
        ['name' => 'Priya S.',  'city' => 'Mumbai',    'avatar' => 'P', 'color' => 'bg-sky-500',
         'text' => 'Was confused between Nexon EV and ZS EV. The comparison tool made it so clear. Nexon won on range and after-sales.'],
        ['name' => 'Amit K.',   'city' => 'Delhi',     'avatar' => 'A', 'color' => 'bg-emerald-500',
         'text' => 'The fleet calculator convinced my boss to switch 15 delivery bikes to EVs. We\'re saving ₹45,000/month in fuel.'],
      ];
      foreach ($testimonials as $ti => $t):
      ?>
      <div class="reveal animate-fade-in-up stagger-<?= $ti + 1 ?> rounded-2xl p-6 card-hover" style="background:#152b30;border:1px solid rgba(255,255,255,.06)">
        <!-- Stars -->
        <div class="flex gap-0.5 mb-4" aria-label="5 out of 5 stars">
          <?php for ($s = 0; $s < 5; $s++): ?>
          <svg class="w-4 h-4 star-icon" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
          </svg>
          <?php endfor; ?>
        </div>

        <p class="text-slate-300 text-sm leading-relaxed mb-5">"<?= esc($t['text']) ?>"</p>

        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-full <?= $t['color'] ?> flex items-center justify-center text-white text-xs font-bold flex-shrink-0" aria-hidden="true">
            <?= esc($t['avatar']) ?>
          </div>
          <div>
            <p class="text-sm font-bold text-slate-100"><?= esc($t['name']) ?></p>
            <p class="text-xs text-slate-400"><?= esc($t['city']) ?></p>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Press logos -->
    <div class="mt-12 flex flex-col items-center gap-4">
      <p class="text-xs text-slate-400 uppercase tracking-widest font-semibold">Featured in</p>
      <div class="flex flex-wrap items-center justify-center gap-8">
        <?php foreach (['India Today', 'ET Auto', 'Moneycontrol'] as $press): ?>
        <span class="press-logo"><?= esc($press) ?></span>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>


<!-- ================================================================
  SECTION 12 — LATEST NEWS (conditional)
================================================================ -->
<?php if (!empty($latestArticles)): ?>
<section class="py-16 reveal" style="background:#f8fdf8">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="flex items-end justify-between mb-8">
      <div>
        <span class="text-xs font-bold uppercase tracking-widest text-cyan-400">Stay Updated</span>
        <h2 class="text-2xl sm:text-3xl font-black mt-1" style="color:#e6f1f1">EV News &amp; Guides</h2>
      </div>
      <a href="<?= base_url('blog') ?>" class="text-sm font-semibold text-cyan-400 hover:text-cyan-300 transition-colors hidden sm:block">All articles →</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
      <?php foreach (array_slice($latestArticles, 0, 3) as $article): ?>
      <article class="reveal group rounded-2xl overflow-hidden transition-all duration-300 card-hover flex flex-col" style="background:#152b30;border:1px solid rgba(255,255,255,.06)">

        <?php if (!empty($article['thumbnail_url'])): ?>
        <a href="<?= base_url('blog/' . esc($article['slug'] ?? '')) ?>" class="block overflow-hidden flex-shrink-0">
          <img src="<?= esc($article['thumbnail_url']) ?>"
               alt="<?= esc($article['title'] ?? '') ?>"
               class="w-full h-44 object-cover group-hover:scale-105 transition-transform duration-300"
               loading="lazy">
        </a>
        <?php else: ?>
        <a href="<?= base_url('blog/' . esc($article['slug'] ?? '')) ?>" class="block flex-shrink-0">
          <div class="w-full h-44 flex items-center justify-center text-5xl" style="background:linear-gradient(135deg,#0f2125,#13262b)" aria-hidden="true">⚡</div>
        </a>
        <?php endif; ?>

        <div class="flex flex-col flex-1 p-5">
          <?php if (!empty($article['category'])): ?>
          <span class="inline-block text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-full mb-3 w-fit" style="background:rgba(0,153,153,.15);color:#16c4c4">
            <?= esc($article['category']) ?>
          </span>
          <?php endif; ?>

          <h3 class="font-bold text-slate-100 text-base leading-snug line-clamp-2 mb-2 flex-1">
            <a href="<?= base_url('blog/' . esc($article['slug'] ?? '')) ?>" class="hover:text-cyan-300 transition-colors">
              <?= esc($article['title'] ?? '') ?>
            </a>
          </h3>

          <?php if (!empty($article['excerpt'])): ?>
          <p class="text-sm text-slate-400 line-clamp-2 leading-relaxed mb-3"><?= esc($article['excerpt']) ?></p>
          <?php endif; ?>

          <div class="flex items-center gap-2 pt-3 border-t border-white/10 text-xs text-slate-400 mt-auto">
            <?php if (!empty($article['published_at'])): ?>
            <time datetime="<?= esc($article['published_at']) ?>"><?= date('d M Y', strtotime($article['published_at'])) ?></time>
            <?php endif; ?>
            <?php if (!empty($article['read_time'])): ?>
            <span>· <?= (int)$article['read_time'] ?> min read</span>
            <?php endif; ?>
            <a href="<?= base_url('blog/' . esc($article['slug'] ?? '')) ?>" class="ml-auto text-cyan-400 font-semibold hover:text-cyan-300 transition-colors">Read →</a>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>

    <div class="mt-6 text-center sm:hidden">
      <a href="<?= base_url('blog') ?>" class="text-sm font-semibold text-cyan-400">View all articles →</a>
    </div>
  </div>
</section>
<?php endif; ?>


<!-- ================================================================
  FOR EV BRANDS SECTION
================================================================ -->
<section class="border-y py-16 reveal" style="background:#0f2125;border-top:1px solid rgba(0,153,153,.2);border-bottom:1px solid rgba(0,153,153,.2)">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid md:grid-cols-2 gap-10 items-center">

      <!-- Left -->
      <div class="flex items-start gap-4">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white text-2xl flex-shrink-0" style="background:#009999">&#128226;</div>
        <div>
          <h2 class="text-2xl font-black text-slate-100 mb-2">For EV Brands</h2>
          <p class="text-slate-400 text-sm leading-relaxed">List your EVs on charj.in and get discovered by thousands of buyers who are actively looking for their next electric ride.</p>
        </div>
      </div>

      <!-- Right: 4 benefits -->
      <div class="grid grid-cols-2 gap-4">
        <?php
        $brandBenefits = [
          ['icon'=>'&#128200;', 'label'=>'Increase Visibility'],
          ['icon'=>'&#127775;', 'label'=>'Generate Quality Leads'],
          ['icon'=>'&#129309;', 'label'=>'Build Trust & Brand Presence'],
          ['icon'=>'&#9889;',   'label'=>"Be Part of India's EV Future"],
        ];
        foreach ($brandBenefits as $b): ?>
        <div class="flex flex-col items-center text-center rounded-xl p-4 shadow-sm" style="background:#152b30;border:1px solid rgba(0,153,153,.2)">
          <span class="text-2xl mb-2" aria-hidden="true"><?= $b['icon'] ?></span>
          <span class="text-xs font-bold text-slate-100 leading-tight"><?= $b['label'] ?></span>
        </div>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</section>


<!-- ================================================================
  SECTION 14 — FINAL CTA
================================================================ -->
<section class="relative overflow-hidden py-24 bg-gradient-to-br from-emerald-600 to-green-600 text-center reveal">

  <!-- Grid overlay -->
  <div class="absolute inset-0 hero-grid pointer-events-none opacity-60" aria-hidden="true"></div>

  <!-- Glow -->
  <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[300px] bg-teal-500 opacity-[0.07] blur-3xl rounded-full pointer-events-none" aria-hidden="true"></div>

  <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Lightning icon -->
    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-teal-500/20 border border-teal-500/30 mb-8">
      <svg class="w-8 h-8 text-teal-400" fill="currentColor" viewBox="0 0 24 24"><path d="M13 2L4.09 12.97H11L10 22L20.91 11.03H14L13 2Z"/></svg>
    </div>

    <h2 class="text-4xl sm:text-5xl font-black text-white leading-tight mb-3">
      The EV revolution is here.
    </h2>
    <p class="text-3xl sm:text-4xl font-black mb-8">
      <span class="hero-gradient-text">Let's drive it together.</span>
    </p>
    <p class="text-slate-400 text-base mb-10 max-w-lg mx-auto">
      Find. Compare. Choose. All Electric. All in One Place.
    </p>

    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
      <a href="<?= base_url('find-my-ev') ?>"
         onclick="charjTrack('final_cta_click',{button:'finder'})"
         class="inline-flex items-center justify-center gap-2 bg-[#009999] hover:bg-[#16c4c4] text-white font-bold text-base px-8 py-4 rounded-full transition-all duration-200 shadow-xl hover:-translate-y-0.5">
        Find My EV &#x2192;
      </a>
      <a href="<?= base_url('vehicles') ?>"
         onclick="charjTrack('final_cta_click',{button:'browse'})"
         class="inline-flex items-center justify-center gap-2 border border-white/30 text-white font-bold text-base px-8 py-4 rounded-full hover:bg-white/10 transition-all duration-200">
        Browse all EVs
      </a>
    </div>

    <p class="text-slate-400 text-sm">Free forever &middot; No spam &middot; Built for India</p>

    <!-- Social -->
    <div class="mt-10 flex items-center justify-center gap-2 text-slate-400 text-sm">
      <span>Follow us</span>
      <span class="font-bold text-slate-400">@charj.in</span>
    </div>
  </div>
</section>

<!-- ================================================================
  CHARGING STATIONS FINDER - ALPINE.JS APPLICATION
================================================================ -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  window.chargingStationsFinder = function() {
    return {
      detecting: false,
      loading: false,
      userLocation: null,
      locationName: 'your location',
      searchCity: '',
      nearbyStations: [],
      selectedStation: null,
      map: null,
      mapMarkers: [],
      stationCount: 0,
      nearestDistance: null,

      // Initialize map on mount
      init() {
        this.$nextTick(() => {
          this.initMap();
        });
      },

      // Initialize Leaflet map
      initMap() {
        // Default to India center
        const mapCenter = [20.5937, 78.9629];

        this.map = L.map('charging-map', {
          center: mapCenter,
          zoom: 5,
          zoomControl: true,
          scrollWheelZoom: false,
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '© OpenStreetMap contributors',
          maxZoom: 19,
        }).addTo(this.map);

        // Load mock charging stations data
        this.loadMockStations();
      },

      // Load mock charging stations (replace with API call later)
      loadMockStations() {
        this.allStations = [
          { id: 1, name: 'Tata Power - Delhi Central', network: 'Tata Power Charging', lat: 28.6139, lng: 77.2090, charger_types: ['AC', 'DC'], pricing: 8.50 },
          { id: 2, name: 'Statiq - Bangalore Tech Park', network: 'Statiq', lat: 12.9716, lng: 77.5946, charger_types: ['DC'], pricing: 10.00 },
          { id: 3, name: 'Shell Recharge - Mumbai South', network: 'Shell Recharge', lat: 19.0760, lng: 72.8777, charger_types: ['AC', 'DC'], pricing: 12.50 },
          { id: 4, name: 'EVGO - Pune Downtown', network: 'EVGO', lat: 18.5204, lng: 73.8567, charger_types: ['AC'], pricing: 7.50 },
          { id: 5, name: 'Tata Power - Hyderabad Metro', network: 'Tata Power Charging', lat: 17.3850, lng: 78.4867, charger_types: ['DC'], pricing: 9.00 },
          { id: 6, name: 'Statiq - Bangalore Indiranagar', network: 'Statiq', lat: 12.9716, lng: 77.6412, charger_types: ['AC', 'DC'], pricing: 9.50 },
          { id: 7, name: 'Tata Power - Delhi Airport', network: 'Tata Power Charging', lat: 28.5562, lng: 77.1199, charger_types: ['DC'], pricing: 11.00 },
          { id: 8, name: 'Shell Recharge - Gurgaon', network: 'Shell Recharge', lat: 28.4089, lng: 77.0235, charger_types: ['AC'], pricing: 8.75 },
          { id: 9, name: 'EVGO - Pune Civil Lines', network: 'EVGO', lat: 18.5308, lng: 73.8446, charger_types: ['AC', 'DC'], pricing: 8.00 },
          { id: 10, name: 'Statiq - Hyderabad Banjara Hills', network: 'Statiq', lat: 17.3935, lng: 78.4444, charger_types: ['AC'], pricing: 8.50 },
        ];
      },

      // Detect user location using HTML5 Geolocation API
      async detectLocation() {
        this.detecting = true;
        this.loading = true;

        if (!navigator.geolocation) {
          alert('Geolocation is not supported by your browser. Please search by city instead.');
          this.detecting = false;
          this.loading = false;
          return;
        }

        navigator.geolocation.getCurrentPosition(
          (position) => {
            this.userLocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            this.locationName = `${this.userLocation.lat.toFixed(4)}, ${this.userLocation.lng.toFixed(4)}`;
            this.detectCity(this.userLocation);
            this.detectingComplete();
          },
          (error) => {
            console.error('Geolocation error:', error);
            alert('Could not detect your location. Please check permissions and try again, or search by city.');
            this.detecting = false;
            this.loading = false;
          }
        );
      },

      // Reverse geocode to get city name
      async detectCity(location) {
        try {
          const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${location.lat}&lon=${location.lng}`);
          const data = await response.json();
          if (data.address) {
            this.locationName = data.address.city || data.address.town || data.address.county || 'Your Location';
          }
        } catch (error) {
          console.error('Geocoding error:', error);
        }
      },

      // Find nearby stations based on user location
      detectingComplete() {
        if (!this.userLocation) {
          this.detecting = false;
          this.loading = false;
          return;
        }

        // Calculate distances and sort
        this.nearbyStations = this.allStations.map(station => ({
          ...station,
          distance: this.calculateDistance(this.userLocation.lat, this.userLocation.lng, station.lat, station.lng)
        })).sort((a, b) => a.distance - b.distance);

        this.stationCount = this.nearbyStations.length;
        this.nearestDistance = this.nearbyStations[0]?.distance || null;

        // Update map
        this.updateMap();

        this.detecting = false;
        this.loading = false;

        // Track event
        charjTrack('charging_geolocation_detect', {
          stations_found: this.stationCount,
          nearest_distance_km: Math.round(this.nearestDistance * 10) / 10
        });
      },

      // Search by city
      async searchByCity() {
        if (!this.searchCity.trim()) return;

        this.loading = true;
        try {
          const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.searchCity)}&limit=1`);
          const data = await response.json();

          if (data.length > 0) {
            this.userLocation = {
              lat: parseFloat(data[0].lat),
              lng: parseFloat(data[0].lon)
            };
            this.locationName = this.searchCity;
            this.detectingComplete();
          } else {
            alert('City not found. Please try another search.');
            this.loading = false;
          }
        } catch (error) {
          console.error('Search error:', error);
          alert('Error searching for city. Please try again.');
          this.loading = false;
        }
      },

      // Calculate distance between two coordinates (Haversine formula)
      calculateDistance(lat1, lng1, lat2, lng2) {
        const R = 6371; // Earth's radius in kilometers
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLng = (lng2 - lng1) * Math.PI / 180;
        const a =
          Math.sin(dLat / 2) * Math.sin(dLat / 2) +
          Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
          Math.sin(dLng / 2) * Math.sin(dLng / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
      },

      // Update map with markers
      updateMap() {
        // Clear existing markers
        this.mapMarkers.forEach(marker => this.map.removeLayer(marker));
        this.mapMarkers = [];

        if (!this.userLocation) return;

        // Add user location marker (blue)
        const userMarker = L.circleMarker([this.userLocation.lat, this.userLocation.lng], {
          radius: 8,
          fillColor: '#009999',
          color: '#16c4c4',
          weight: 3,
          opacity: 1,
          fillOpacity: 0.8
        }).bindPopup('<b>Your Location</b>').addTo(this.map);
        this.mapMarkers.push(userMarker);

        // Add station markers (teal)
        this.nearbyStations.slice(0, 15).forEach((station, idx) => {
          const marker = L.circleMarker([station.lat, station.lng], {
            radius: 6,
            fillColor: '#16c4c4',
            color: '#009999',
            weight: 2,
            opacity: 1,
            fillOpacity: 0.7
          }).bindPopup(`
            <div style="min-width: 180px;">
              <b>${station.name}</b><br/>
              <small>${station.network}</small><br/>
              <small style="color: #16c4c4;">${Math.round(station.distance * 10) / 10} km away</small>
            </div>
          `).addTo(this.map);
          this.mapMarkers.push(marker);
        });

        // Fit bounds to show all markers
        if (this.mapMarkers.length > 0) {
          const group = new L.featureGroup(this.mapMarkers);
          this.map.fitBounds(group.getBounds().pad(0.1), { maxZoom: 13 });
        }
      },

      // Select a station
      selectStation(station) {
        this.selectedStation = station;
        charjTrack('charging_station_selected', {
          station_id: station.id,
          station_name: station.name,
          distance_km: Math.round(station.distance * 10) / 10
        });
      }
    };
  };
});
</script>

<!-- Leaflet.js library for maps -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<?= $this->endSection() ?>
