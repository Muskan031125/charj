<?= $this->extend('layouts/public') ?>

<?= $this->section('head') ?>
<title><?= esc($meta_title ?? 'Charj.in — India\'s EV Decision Engine | Compare, Calculate, Choose') ?></title>
<meta name="description" content="<?= esc($meta_description ?? 'Compare 150+ EVs, calculate savings & subsidies, find the perfect electric vehicle for India. FAME II calculator, charging guide, free EV quiz.') ?>">
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"Organization","name":"Charj.in","url":"<?= base_url() ?>","logo":"<?= base_url('assets/images/charj-logo.png') ?>","description":"India's EV Decision Engine","areaServed":"IN"}
</script>
<style>
  /* Hero mesh grid */
  .hero-mesh {
    background-image: radial-gradient(circle at 1px 1px, rgba(0,230,118,.12) 1px, transparent 0);
    background-size: 32px 32px;
  }
  /* Animated gradient orbs */
  .orb { position:absolute; border-radius:50%; filter:blur(60px); pointer-events:none; }
  /* EV card hover image zoom */
  .ev-img-wrap img { transition:transform .35s cubic-bezier(.4,0,.2,1); }
  .ev-img-wrap:hover img { transform:scale(1.06); }
  /* Category card active fill */
  .cat-card:hover .cat-emoji-wrap { background:rgba(255,255,255,.2) !important; border-color:rgba(255,255,255,.3) !important; }
  /* How-it-works connector */
  .hiw-connector { background:linear-gradient(90deg,transparent,rgba(0,168,150,.3),rgba(14,165,233,.2),transparent); height:1px; }
  /* Testimonial quote */
  .quote-mark { font-size:4rem; line-height:1; color:rgba(0,168,150,.12); font-family:Georgia,serif; }
  /* Stat dividers */
  @media(min-width:768px){ .stat-item+.stat-item { border-left:1px solid rgba(0,168,150,.1); } }
  @media(max-width:767px){ .stat-item:nth-child(even){ border-left:1px solid rgba(0,168,150,.1); } }
  /* Section divider */
  .section-divider { height:1px; background:linear-gradient(90deg,transparent,rgba(0,168,150,.15),transparent); }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- ════════════════════════════════════════════════════════
  HERO — Light crystal gradient, premium search
════════════════════════════════════════════════════════ -->
<section class="relative min-h-screen flex flex-col justify-center overflow-hidden" style="background:linear-gradient(160deg,#F0FFF4 0%,#EEFFF3 40%,#F5FFF7 70%,#F0FFF4 100%)">

  <!-- Mesh grid overlay -->
  <div class="absolute inset-0 hero-mesh opacity-40 pointer-events-none" aria-hidden="true"></div>

  <!-- Gradient orbs — ambient green, no harsh circles -->
  <div class="orb w-[700px] h-[500px] top-0 left-1/2 -translate-x-1/2 -translate-y-1/3" style="background:radial-gradient(ellipse,rgba(0,230,118,.1),transparent 68%)" aria-hidden="true"></div>
  <div class="orb w-[450px] h-[450px] bottom-0 right-0 translate-x-1/3 translate-y-1/4" style="background:radial-gradient(ellipse,rgba(105,255,151,.1),transparent 70%)" aria-hidden="true"></div>
  <div class="orb w-[350px] h-[350px] top-1/4 left-0 -translate-x-1/2" style="background:radial-gradient(ellipse,rgba(0,230,118,.07),transparent 70%)" aria-hidden="true"></div>
  <!-- Center white bloom -->
  <div class="orb w-[600px] h-[400px]" style="top:30%;left:50%;transform:translate(-50%,-50%);background:radial-gradient(ellipse,rgba(255,255,255,.75),transparent 65%)" aria-hidden="true"></div>

  <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-24 text-center">

    <!-- Eyebrow badge -->
    <div class="inline-flex items-center gap-2 rounded-full px-4 py-2 mb-8 animate-fade-in-up stagger-1"
         style="background:rgba(255,255,255,.82);border:1.5px solid rgba(0,230,118,.25);box-shadow:0 2px 8px rgba(0,230,118,.1);backdrop-filter:blur(8px)">
      <span class="neon-dot flex-shrink-0" style="width:7px;height:7px"></span>
      <span class="text-xs font-bold tracking-wider uppercase" style="color:#00963C">India's #1 EV Marketplace</span>
    </div>

    <!-- H1 -->
    <h1 class="text-2xl sm:text-4xl md:text-6xl lg:text-7xl font-black leading-[1.08] tracking-tight mb-6 animate-fade-in-up stagger-2" style="color:#0F172A">
      One platform.<br>
      <span class="hero-gradient-text">Every EV brand.</span>
    </h1>

    <!-- Sub -->
    <p class="text-base sm:text-lg md:text-xl max-w-2xl mx-auto leading-relaxed mb-10 animate-fade-in-up stagger-3" style="color:#475569">
      Discover, compare and choose the right electric vehicle for India —
      from scooters to SUVs, with subsidies, savings and charging all in one place.
    </p>

    <!-- Premium Search Bar -->
    <form action="<?= base_url('vehicles') ?>" method="GET"
          class="relative max-w-2xl mx-auto mb-8 animate-fade-in-up stagger-4" role="search">
      <div class="search-bar flex items-center rounded-2xl overflow-hidden"
           style="background:#FFFFFF;border:1.5px solid rgba(0,168,150,.2);box-shadow:0 4px 24px rgba(0,0,0,.08),0 1px 4px rgba(0,0,0,.04)">
        <!-- Search icon -->
        <div class="flex items-center pl-4 sm:pl-5 pr-2 flex-shrink-0">
          <svg class="w-5 h-5" style="color:#00A896" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
          </svg>
        </div>
        <!-- Input -->
        <input type="text" name="q"
               placeholder="Search EVs, brands..."
               class="flex-1 py-4 text-sm sm:text-base outline-none min-w-0 font-medium"
               style="background:transparent;color:#0F172A;caret-color:#00A896"
               aria-label="Search EVs">
        <!-- Divider -->
        <div class="hidden sm:block w-px h-8 mx-1 flex-shrink-0" style="background:rgba(0,168,150,.15)"></div>
        <!-- Submit -->
        <button type="submit" class="flex items-center gap-2 m-2 px-3 sm:px-5 py-2.5 rounded-xl text-white font-bold text-xs sm:text-sm transition-all duration-200 flex-shrink-0 ripple-btn"
                style="background:#00A896;box-shadow:0 4px 10px rgba(0,168,150,.3)"
                onmouseover="this.style.background='#009688';this.style.transform='scale(1.02)'"
                onmouseout="this.style.background='#00A896';this.style.transform=''">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
          Search
        </button>
      </div>
    </form>

    <!-- Category quick tabs — compact row -->
    <div class="flex flex-wrap items-center justify-center gap-1.5 mb-5 animate-fade-in-up stagger-5">
      <?php
      $heroTabs = [
        ['icon'=>'🛵','label'=>'2 Wheelers', 'url'=>base_url('vehicles?category=electric-scooters')],
        ['icon'=>'🛺','label'=>'3 Wheelers', 'url'=>base_url('vehicles?category=electric-rickshaws')],
        ['icon'=>'🚗','label'=>'4 Wheelers', 'url'=>base_url('vehicles?category=electric-cars')],
        ['icon'=>'🚛','label'=>'Commercial', 'url'=>base_url('vehicles?category=electric-buses')],
      ];
      foreach ($heroTabs as $ht): ?>
      <a href="<?= $ht['url'] ?>"
         class="filter-chip !py-1.5 !px-3 !text-xs hover:border-brand hover:text-brand">
        <span aria-hidden="true"><?= $ht['icon'] ?></span>
        <span><?= $ht['label'] ?></span>
      </a>
      <?php endforeach; ?>
    </div>

    <!-- CTA buttons -->
    <div class="flex flex-col sm:flex-row gap-3 justify-center mb-6 animate-fade-in-up stagger-6">
      <a href="<?= base_url('find-my-ev') ?>" onclick="charjTrack('hero_cta_finder',{})"
         class="inline-flex items-center justify-center gap-2 text-white font-bold text-base px-8 py-4 rounded-full transition-all duration-200 ripple-btn"
         style="background:linear-gradient(135deg,#00A896,#00bfa5);box-shadow:0 6px 20px rgba(0,168,150,.4)"
         onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 10px 28px rgba(0,168,150,.45)'"
         onmouseout="this.style.transform='';this.style.boxShadow='0 6px 20px rgba(0,168,150,.4)'">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M13 2L4.09 12.97H11L10 22L20.91 11.03H14L13 2Z"/></svg>
        Find My EV →
      </a>
      <a href="<?= base_url('compare') ?>"
         class="inline-flex items-center justify-center gap-2 font-semibold text-base px-8 py-4 rounded-full transition-all duration-200"
         style="background:rgba(0,168,150,.06);color:#00A896;border:1.5px solid rgba(0,168,150,.25)"
         onmouseover="this.style.background='rgba(0,168,150,.12)';this.style.borderColor='rgba(0,168,150,.5)'"
         onmouseout="this.style.background='rgba(0,168,150,.06)';this.style.borderColor='rgba(0,168,150,.25)'">
        Compare EVs
      </a>
    </div>

    <!-- Trust strip -->
    <div class="flex flex-wrap items-center justify-center gap-x-5 gap-y-1.5 text-xs animate-fade-in-up stagger-6">
      <?php
      $heroProof = [
        ['icon'=>'⚡','label'=>'150+ EVs listed'],
        ['icon'=>'🗺️','label'=>'18 States covered'],
        ['icon'=>'🛠️','label'=>'25+ Free tools'],
        ['icon'=>'🎁','label'=>'Up to ₹1.5L subsidy'],
      ];
      foreach ($heroProof as $pi => $hp):
      ?>
        <?php if ($pi > 0): ?><span class="w-px h-3 hidden sm:block" style="background:rgba(0,168,150,.2)"></span><?php endif; ?>
        <span class="flex items-center gap-1.5 font-medium" style="color:#64748B">
          <span><?= $hp['icon'] ?></span>
          <span><?= $hp['label'] ?></span>
        </span>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Scroll indicator -->
  <div class="scroll-indicator absolute bottom-8 left-1/2 -translate-x-1/2" aria-hidden="true">
    <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background:rgba(0,168,150,.1);border:1px solid rgba(0,168,150,.2)">
      <svg class="w-4 h-4" style="color:#00A896" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
    </div>
  </div>
</section>


<!-- ════════════════════════════════════════════════════════
  WHAT & WHY — Compact separated animated cards, side-by-side
════════════════════════════════════════════════════════ -->
<section class="py-8 sm:py-12" style="background:#F7FFFE">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 sr-stagger">

      <!-- LEFT: What is charj.in? -->
      <div class="card p-5 sm:p-6 flex flex-col">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0" style="background:rgba(0,168,150,.08);border:1px solid rgba(0,168,150,.18)">
            <svg class="w-5 h-5" style="color:#00A896" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
          </div>
          <span class="badge-green">What is charj.in?</span>
        </div>
        <h2 class="text-xl font-black leading-tight mb-2" style="color:#0F172A">
          India's dedicated <span class="hero-gradient-text">EV marketplace</span>
        </h2>
        <p class="text-sm leading-relaxed mb-4" style="color:#64748B">
          One platform for every EV in India — 2W, 3W, 4W and commercial.
        </p>
        <ul class="space-y-2.5 flex-1">
          <?php
          $whatItems = [
            ['M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z', 'Discover all EV brands & models'],
            ['M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'Compare specs, range & prices'],
            ['M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z', 'Latest EV launches & news'],
            ['M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z', 'Find dealers & booking links near you'],
          ];
          foreach ($whatItems as [$path, $txt]): ?>
          <li class="flex items-center gap-2.5">
            <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(0,168,150,.07);border:1px solid rgba(0,168,150,.12)">
              <svg class="w-3.5 h-3.5" style="color:#00A896" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="<?= $path ?>"/></svg>
            </div>
            <span class="text-sm font-medium" style="color:#334155"><?= $txt ?></span>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- RIGHT: Why charj.in? -->
      <div class="card p-5 sm:p-6 flex flex-col">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0" style="background:rgba(0,168,150,.08);border:1px solid rgba(0,168,150,.18)">
            <svg class="w-5 h-5" style="color:#00A896" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
          </div>
          <span class="badge-green">Why charj.in?</span>
        </div>
        <h2 class="text-xl font-black leading-tight mb-2" style="color:#0F172A">
          Built for India's <span class="hero-gradient-text">EV buyers</span>
        </h2>
        <p class="text-sm leading-relaxed mb-4" style="color:#64748B">
          Not just a listing site — a full decision engine for electric mobility.
        </p>
        <div class="grid grid-cols-2 gap-2 flex-1">
          <?php
          $whyItems = [
            ['M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',                                                                                                                                      'All EVs. One Place.'],
            ['M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'Easy Comparison'],
            ['M13 10V3L4 14h7v7l9-11h-7z',                                                                                                                                                        '100% EV Focused'],
            ['M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', 'Latest Updates'],
            ['M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z',                                                               'Find & Connect'],
            ['M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',  'Subsidy Help'],
          ];
          foreach ($whyItems as [$path, $title]): ?>
          <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl transition-all duration-150 hover:shadow-sm" style="background:#F7FFFE;border:1px solid rgba(0,168,150,.1);color:#334155"
               onmouseover="this.style.background='#FFFFFF';this.style.borderColor='rgba(0,168,150,.25)'"
               onmouseout="this.style.background='#F7FFFE';this.style.borderColor='rgba(0,168,150,.1)'">
            <svg class="w-3.5 h-3.5 flex-shrink-0" style="color:#00A896" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="<?= $path ?>"/></svg>
            <span class="leading-tight text-xs font-semibold"><?= $title ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════
  VEHICLE CATALOG + CHARJ POINTS — Light two-column panel
════════════════════════════════════════════════════════ -->
<section class="py-8 sm:py-10" style="background:#FFFFFF">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid lg:grid-cols-2 gap-4 sm:gap-5">

      <!-- ── LEFT: Vehicle Catalog ── -->
      <div class="panel-card flex flex-col p-4 sr-left">

        <span class="badge-green mb-3 w-fit">EV Catalog</span>

        <h2 class="text-lg font-black leading-tight mb-1" style="color:#0F172A">
          India's Largest <span class="hero-gradient-text">EV Catalog</span>
        </h2>
        <p class="text-xs leading-relaxed mb-4" style="color:#64748B">
          150+ electric vehicles — scooters, bikes, cars, SUVs and commercial EVs.
        </p>

        <!-- 4 category tiles -->
        <div class="grid grid-cols-2 gap-2 mb-4">
          <?php
          $catalogCats = [
            ['svg'=>'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4','name'=>'2 Wheelers', 'sub'=>'Scooters & Bikes','url'=>base_url('vehicles?category=electric-scooters')],
            ['svg'=>'M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z','name'=>'3 Wheelers', 'sub'=>'E-Rickshaws & Loaders','url'=>base_url('vehicles?category=electric-rickshaws')],
            ['svg'=>'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0','name'=>'4 Wheelers', 'sub'=>'Cars, Sedans & SUVs','url'=>base_url('vehicles?category=electric-cars')],
            ['svg'=>'M8 17a2 2 0 11-4 0 2 2 0 014 0zM18 17a2 2 0 11-4 0 2 2 0 014 0zM1 5h15M1 9h15m3-4v12m0 0h-3m3 0H18','name'=>'Commercial', 'sub'=>'Buses, Trucks & Fleets','url'=>base_url('vehicles?category=electric-buses')],
          ];
          foreach ($catalogCats as $cat): ?>
          <a href="<?= $cat['url'] ?>"
             onclick="charjTrack('catalog_category_click',{category:'<?= addslashes(esc($cat['name'])) ?>'})"
             class="cat-card group flex items-center gap-2.5 p-3 transition-all duration-200">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                 style="background:rgba(0,168,150,.07);border:1px solid rgba(0,168,150,.14)">
              <svg class="w-3.5 h-3.5" style="color:#00A896" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="<?= $cat['svg'] ?>"/></svg>
            </div>
            <div class="min-w-0">
              <div class="cat-name font-bold text-xs transition-colors" style="color:#0F172A"><?= $cat['name'] ?></div>
              <div class="cat-count text-[10px] truncate" style="color:#64748B"><?= $cat['sub'] ?></div>
            </div>
          </a>
          <?php endforeach; ?>
        </div>

        <a href="<?= base_url('vehicles') ?>"
           onclick="charjTrack('catalog_cta_click',{})"
           class="mt-auto w-full flex items-center justify-center gap-2 py-3 rounded-2xl font-bold text-sm text-white transition-all duration-200"
           style="background:#00A896;box-shadow:0 4px 14px rgba(0,168,150,.25)"
           onmouseover="this.style.background='#009688';this.style.boxShadow='0 6px 18px rgba(0,168,150,.38)'"
           onmouseout="this.style.background='#00A896';this.style.boxShadow='0 4px 14px rgba(0,168,150,.25)'">
          Browse All EVs
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
        </a>
      </div>

      <!-- ── RIGHT: Charj Points ── -->
      <div class="panel-card flex flex-col p-4 sr-right">

        <span class="badge-green mb-3 w-fit">Charj Points</span>

        <h2 class="text-lg font-black leading-tight mb-1" style="color:#0F172A">
          Charge Anywhere, <span class="hero-gradient-text">Anytime</span>
        </h2>
        <p class="text-xs leading-relaxed mb-4" style="color:#64748B">
          Discover public charging stations near you. Filter by speed, connector and operator.
        </p>

        <!-- Charging station rows — 2 visible + 1 blurred teaser -->
        <div class="flex-1">
          <?php
          $stations = [
            ['name'=>'Tata Power EV Hub — Koramangala','dist'=>'0.8 km','speed'=>'DC Fast 60kW','avail'=>2,'total'=>4,'op'=>'Tata Power'],
            ['name'=>'Statiq Station — MG Road',        'dist'=>'1.4 km','speed'=>'AC 7.4kW',    'avail'=>3,'total'=>6,'op'=>'Statiq'],
            ['name'=>'ChargeZone — Indiranagar',         'dist'=>'2.1 km','speed'=>'DC Fast 50kW','avail'=>1,'total'=>3,'op'=>'ChargeZone'],
          ];
          foreach ($stations as $i => $st):
            $av      = $st['avail'] > 0;
            $blurred = ($i === 2);
          ?>
          <div class="station-row flex items-center gap-3 px-3 py-2.5 cursor-default <?= $blurred ? 'mb-0' : 'mb-2' ?>"
               style="<?= $blurred ? 'filter:blur(3px);opacity:.55;pointer-events:none;user-select:none' : '' ?>">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(0,168,150,.07);border:1px solid rgba(0,168,150,.14)">
              <svg class="w-3.5 h-3.5" style="color:#00A896" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
              <div class="font-semibold text-xs truncate leading-snug" style="color:#0F172A"><?= esc($st['name']) ?></div>
              <div class="text-[10px] truncate mt-0.5" style="color:#64748B"><?= esc($st['speed']) ?> · <?= esc($st['op']) ?></div>
            </div>
            <div class="flex flex-col items-end gap-0.5 flex-shrink-0">
              <span class="text-[11px] font-bold px-2 py-0.5 rounded-full" style="background:<?= $av ? 'rgba(0,168,150,.1)' : 'rgba(239,68,68,.08)' ?>;color:<?= $av ? '#00A896' : '#ef4444' ?>"><?= $st['avail'] ?>/<?= $st['total'] ?></span>
              <span class="text-[9px] font-semibold" style="color:#94A3B8"><?= esc($st['dist']) ?></span>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <a href="<?= base_url('charging-stations') ?>"
           onclick="charjTrack('charj_points_cta_click',{})"
           class="mt-4 w-full flex items-center justify-center gap-2 py-3 rounded-2xl font-bold text-sm text-white transition-all duration-200"
           style="background:#00A896;box-shadow:0 4px 14px rgba(0,168,150,.25)"
           onmouseover="this.style.background='#009688';this.style.boxShadow='0 6px 18px rgba(0,168,150,.38)'"
           onmouseout="this.style.background='#00A896';this.style.boxShadow='0 4px 14px rgba(0,168,150,.25)'">
          Find Charging Stations
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
        </a>
      </div>

    </div>
  </div>
</section>

<div class="section-divider"></div>


<!-- ════════════════════════════════════════════════════════
  BROWSE BY BUDGET — India buyers think in monthly budgets
════════════════════════════════════════════════════════ -->
<section class="py-10 sm:py-12" style="background:#F7FFFE">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="flex items-center justify-between mb-6 reveal">
      <div>
        <h2 class="text-xl sm:text-2xl font-black leading-tight" style="color:#0F172A">Browse by <span class="hero-gradient-text">Budget</span></h2>
        <p class="text-sm mt-0.5" style="color:#64748B">Find EVs that fit your pocket — India's most popular price bands</p>
      </div>
      <a href="<?= base_url('vehicles') ?>" class="hidden sm:flex items-center gap-1 text-xs font-bold transition-colors" style="color:#00A896">
        All EVs
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
      </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sr-stagger">
      <?php
      $budgetTiles = [
        ['label'=>'Under ₹50K', 'sub'=>'Entry-level scooters','url'=>base_url('vehicles?price_max=50000'),         'color'=>'#00A896','bg'=>'rgba(0,168,150,.06)','border'=>'rgba(0,168,150,.18)'],
        ['label'=>'₹50K – 1L',  'sub'=>'Best-value 2-wheelers','url'=>base_url('vehicles?price_min=50000&price_max=100000'),'color'=>'#0EA5E9','bg'=>'rgba(14,165,233,.06)','border'=>'rgba(14,165,233,.18)'],
        ['label'=>'₹1L – 2L',   'sub'=>'Mid-range scooters & bikes','url'=>base_url('vehicles?price_min=100000&price_max=200000'),'color'=>'#8B5CF6','bg'=>'rgba(139,92,246,.06)','border'=>'rgba(139,92,246,.18)'],
        ['label'=>'₹2L – 5L',   'sub'=>'Premium 2W & compact cars','url'=>base_url('vehicles?price_min=200000&price_max=500000'),'color'=>'#F59E0B','bg'=>'rgba(245,158,11,.06)','border'=>'rgba(245,158,11,.18)'],
        ['label'=>'₹5L – 15L',  'sub'=>'Electric cars & SUVs','url'=>base_url('vehicles?price_min=500000&price_max=1500000'),'color'=>'#10B981','bg'=>'rgba(16,185,129,.06)','border'=>'rgba(16,185,129,.18)'],
        ['label'=>'₹15L+',      'sub'=>'Premium EVs','url'=>base_url('vehicles?price_min=1500000'),                'color'=>'#EF4444','bg'=>'rgba(239,68,68,.06)','border'=>'rgba(239,68,68,.18)'],
      ];
      foreach ($budgetTiles as $tile): ?>
      <a href="<?= $tile['url'] ?>"
         class="group flex flex-col items-center justify-center gap-2 rounded-2xl px-3 py-5 text-center transition-all duration-200 hover:-translate-y-1"
         style="background:<?= $tile['bg'] ?>;border:1.5px solid <?= $tile['border'] ?>"
         onmouseenter="this.style.boxShadow='0 8px 24px <?= str_replace('rgba','rgba',$tile['bg']) ?>'"
         onmouseleave="this.style.boxShadow='none'">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-transform duration-200 group-hover:scale-110"
             style="background:<?= $tile['color'] ?>;box-shadow:0 4px 12px <?= $tile['bg'] ?>">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <div>
          <div class="font-black text-xs sm:text-sm leading-tight" style="color:#0F172A"><?= $tile['label'] ?></div>
          <div class="text-[9px] sm:text-[10px] font-medium mt-0.5 leading-snug" style="color:#64748B"><?= $tile['sub'] ?></div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>

    <!-- Quick EMI prompt -->
    <div class="mt-4 flex items-center justify-center gap-3 text-sm" style="color:#64748B">
      <span>Not sure what fits your budget?</span>
      <a href="<?= base_url('ev-emi-calculator') ?>" class="flex items-center gap-1 font-bold transition-colors" style="color:#00A896"
         onmouseover="this.style.color='#009688'" onmouseout="this.style.color='#00A896'">
        Try the EMI Calculator
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
      </a>
    </div>

  </div>
</section>

<div class="section-divider"></div>


<!-- ════════════════════════════════════════════════════════
  ANIMATED STATS BAR
════════════════════════════════════════════════════════ -->
<section class="py-12 sm:py-16" style="background:#F0FDFA"
  x-data="{
    triggered:false,
    counters:[
      {label:'EVs Listed',raw:'0',target:150,suffix:'+',prefix:'',decimal:false,icon:'⚡'},
      {label:'Max Subsidy',raw:'₹0',target:1.5,suffix:'L',prefix:'₹',decimal:true,icon:'🎁'},
      {label:'Free Tools',raw:'0',target:25,suffix:'+',prefix:'',decimal:false,icon:'🛠️'},
      {label:'States Covered',raw:'0',target:18,suffix:'',prefix:'',decimal:false,icon:'🗺️'}
    ],
    animate(){
      if(this.triggered)return;
      this.triggered=true;
      const self=this;
      self.counters.forEach(function(c,i){
        const steps=50,dur=1600;let step=0;
        const t=setInterval(function(){
          step++;
          const val=Math.min((c.target/steps)*step,c.target);
          const fmt=c.decimal?val.toFixed(1):Math.floor(val).toLocaleString('en-IN');
          self.counters[i].raw=c.prefix+fmt;
          if(step>=steps)clearInterval(t);
        },dur/steps);
      });
    }
  }"
  x-intersect.once="animate()">
  <div class="max-w-4xl mx-auto px-4 sm:px-6">
    <div class="grid grid-cols-2 md:grid-cols-4 rounded-3xl overflow-hidden" style="background:#FFFFFF;box-shadow:0 2px 8px rgba(0,0,0,.06),0 8px 24px rgba(0,0,0,.04);border:1px solid rgba(0,168,150,.1)">
      <template x-for="(stat,idx) in counters" :key="idx">
        <div class="stat-item flex flex-col items-center text-center px-6 py-8">
          <span class="text-2xl mb-3" x-text="stat.icon" aria-hidden="true"></span>
          <div class="flex items-baseline gap-0.5">
            <span class="text-3xl sm:text-4xl font-black" style="color:#0F172A" x-text="stat.raw"></span>
            <span class="text-xl sm:text-2xl font-black" style="color:#00A896" x-text="stat.suffix"></span>
          </div>
          <span class="text-xs sm:text-sm font-medium mt-1" style="color:#64748B" x-text="stat.label"></span>
        </div>
      </template>
    </div>
  </div>
</section>

<div class="section-divider"></div>


<!-- ════════════════════════════════════════════════════════
  BEST EVs + SUBSIDY — equal-height side-by-side cards
════════════════════════════════════════════════════════ -->
<style>
/* Both panels same height */
.panels-wrap{display:grid;grid-template-columns:1fr 1fr;gap:16px;align-items:stretch}
@media(max-width:1023px){.panels-wrap{grid-template-columns:1fr}}
.panel-card{background:#fff;border:1px solid rgba(0,168,150,.15);border-radius:18px;overflow:hidden;box-shadow:0 1px 8px rgba(0,0,0,.05);display:flex;flex-direction:column}
.panel-hd{display:flex;align-items:center;justify-content:space-between;padding:13px 18px;background:rgba(0,168,150,.03);border-bottom:1px solid rgba(0,168,150,.09);cursor:pointer;user-select:none;flex-shrink:0}
/* Equal-height EV top-3 grid */
.ev3-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:8px}
.ev3-card{display:flex;flex-direction:column;text-decoration:none;border-radius:12px;overflow:hidden;transition:transform .2s,box-shadow .2s;position:relative;border:1.5px solid rgba(0,168,150,.13);background:#fff}
.ev3-card:hover{transform:translateY(-3px);box-shadow:0 8px 20px rgba(0,168,150,.14),0 2px 6px rgba(0,0,0,.06)}
.ev3-head{flex-shrink:0;height:62px;display:flex;align-items:center;justify-content:center}
.ev3-body{flex:1;display:flex;flex-direction:column;padding:8px 9px 9px}
.ev3-foot{margin-top:auto;padding-top:6px;border-top:1px solid rgba(0,168,150,.08)}
/* List rows */
.ev-row{display:flex;align-items:center;gap:10px;padding:9px 14px;text-decoration:none;transition:background .14s}
.ev-row:hover{background:rgba(0,168,150,.05)}
.ev-stat-chip{font-size:10px;padding:2px 6px;border-radius:20px;font-weight:600;background:#F0FDF9;color:#047857;border:1px solid rgba(0,168,150,.14)}
/* Category selector tiles */
.cat-tile{flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;padding:12px 8px;border-radius:14px;border:1.5px solid rgba(0,168,150,.13);background:#fff;cursor:pointer;transition:all .18s;text-align:center}
.cat-tile.active,.cat-tile:hover{background:rgba(0,168,150,.07);border-color:#00A896}
.cat-tile.active{border-width:2px}
</style>

<?php
$rankedByCategory = $rankedByCategory ?? [];
$evFallback = [
  '2-wheeler' => [
    ['name'=>'Ather 450X','slug'=>'ather-450x','brand_name'=>'Ather Energy','starting_price'=>139000,'claimed_range'=>146,'expert_rating'=>8.8],
    ['name'=>'TVS iQube','slug'=>'tvs-iqube','brand_name'=>'TVS Motor','starting_price'=>142750,'claimed_range'=>145,'expert_rating'=>8.5],
    ['name'=>'Ola S1 Pro','slug'=>'ola-s1-pro','brand_name'=>'Ola Electric','starting_price'=>139999,'claimed_range'=>195,'expert_rating'=>8.2],
    ['name'=>'Revolt RV400','slug'=>'revolt-rv400','brand_name'=>'Revolt Motors','starting_price'=>124999,'claimed_range'=>150,'expert_rating'=>7.8],
  ],
  '3-wheeler' => [
    ['name'=>'Mahindra Treo','slug'=>'mahindra-treo','brand_name'=>'Mahindra','starting_price'=>295000,'claimed_range'=>170,'expert_rating'=>8.2],
    ['name'=>'Piaggio Ape E-City','slug'=>'piaggio-ape-e-city','brand_name'=>'Piaggio','starting_price'=>350000,'claimed_range'=>104,'expert_rating'=>7.9],
  ],
  '4-wheeler' => [
    ['name'=>'Tata Nexon EV','slug'=>'tata-nexon-ev','brand_name'=>'Tata Motors','starting_price'=>1449000,'claimed_range'=>465,'expert_rating'=>9.0],
    ['name'=>'Tata Tiago EV','slug'=>'tata-tiago-ev','brand_name'=>'Tata Motors','starting_price'=>849000,'claimed_range'=>315,'expert_rating'=>8.4],
    ['name'=>'MG ZS EV','slug'=>'mg-zs-ev','brand_name'=>'MG Motor','starting_price'=>1898000,'claimed_range'=>461,'expert_rating'=>8.7],
    ['name'=>'MG Comet EV','slug'=>'mg-comet-ev','brand_name'=>'MG Motor','starting_price'=>798000,'claimed_range'=>230,'expert_rating'=>7.9],
  ],
];
$catDefs = [
  '2-wheeler' => ['emoji'=>'🛵','label'=>'2-Wheelers','sub'=>'Scooters & Bikes'],
  '3-wheeler' => ['emoji'=>'🛺','label'=>'3-Wheelers','sub'=>'Loaders & Autos'],
  '4-wheeler' => ['emoji'=>'🚗','label'=>'4-Wheelers','sub'=>'Cars & SUVs'],
];
$headGrads = ['linear-gradient(135deg,#FFFBEB,#FDE68A)','linear-gradient(135deg,#F1F5F9,#E2E8F0)','linear-gradient(135deg,#FFF7ED,#FDDCAD)'];
$rankStyles = ['background:#F59E0B;color:#092C22','background:#94A3B8;color:#fff','background:#CD7C3B;color:#fff'];
?>

<section class="py-10 sm:py-12 reveal" style="background:#F7FFFE">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="panels-wrap">

      <!-- ══ LEFT: Subsidy Card (full height flex-col) ══ -->
      <div x-data="{ open: true }" class="panel-card">

        <div class="panel-hd" @click="open=!open">
          <div class="flex items-center gap-3">
            <span class="text-lg">🎁</span>
            <div>
              <p class="text-sm font-black" style="color:#0F172A">Subsidy Spotlight</p>
              <p class="text-[11px]" style="color:#64748B">Save up to ₹1.5 lakh — check eligibility</p>
            </div>
          </div>
          <div class="flex items-center gap-2 flex-shrink-0">
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full" style="background:#00A896;color:#fff">LIVE</span>
            <svg class="w-4 h-4 transition-transform duration-200" :style="open?'transform:rotate(180deg)':''" style="color:#00A896" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
          </div>
        </div>

        <!-- Body grows to fill panel height -->
        <div x-show="open" class="flex flex-col flex-1"
             x-transition:enter="transition ease-out duration-180"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0">
          <!-- Green banner fills & grows -->
          <div class="flex-1 px-5 py-5 relative overflow-hidden" style="background:linear-gradient(135deg,#007a6e,#00A896,#00bfa5)">
            <div class="absolute inset-0" style="background-image:radial-gradient(circle,rgba(255,255,255,.06) 1px,transparent 1px);background-size:18px 18px;pointer-events:none"></div>
            <div class="relative">
              <p class="text-base font-black text-white mb-1">Up to <span style="color:#b2f5ea">₹1.5 lakh off</span> your next EV</p>
              <p class="text-[11px] mb-4" style="color:rgba(255,255,255,.8)">FAME II · State grants · 80EEB tax deduction</p>
              <div class="flex gap-2 mb-4">
                <?php foreach (['🏙️ Delhi<br>₹1.55L','🌿 Gujarat<br>₹1.7L','🌆 K\'taka<br>₹10K'] as $pill): ?>
                <div class="flex-1 text-center rounded-xl py-2 px-1" style="background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.2)">
                  <p class="text-white text-[10px] font-semibold leading-snug"><?= $pill ?></p>
                </div>
                <?php endforeach; ?>
              </div>
              <a href="<?= base_url('subsidy-calculator') ?>"
                 onclick="charjTrack('subsidy_banner_click',{})"
                 class="flex items-center justify-center gap-2 bg-white font-bold text-xs rounded-full py-2.5 w-full"
                 style="color:#007a6e;transition:box-shadow .18s"
                 onmouseover="this.style.boxShadow='0 6px 18px rgba(0,0,0,.18)'"
                 onmouseout="this.style.boxShadow=''">
                Check My Subsidy →
              </a>
            </div>
          </div>
          <!-- Tip strip at bottom -->
          <div class="flex items-start gap-2.5 px-5 py-3 flex-shrink-0" style="background:#F0FDF9;border-top:1px solid rgba(0,168,150,.1)">
            <span class="text-sm mt-px flex-shrink-0">💡</span>
            <p class="text-[11px] leading-relaxed" style="color:#047857">Stack FAME + state + 80EEB — most buyers miss all three.</p>
          </div>
        </div>
      </div>

      <!-- ══ RIGHT: Best EVs Card (flex-col, category tiles → expand) ══ -->
      <div x-data="{ cat: null }" class="panel-card">

        <div class="panel-hd" style="cursor:default">
          <div class="flex items-center gap-3">
            <span class="text-lg">⚡</span>
            <div>
              <p class="text-sm font-black" style="color:#0F172A">Best EVs in India</p>
              <p class="text-[11px]" style="color:#64748B">Pick a category to explore top picks</p>
            </div>
          </div>
          <a href="<?= base_url('vehicles') ?>" class="text-[11px] font-bold flex-shrink-0" style="color:#00A896">View all →</a>
        </div>

        <!-- Category tiles — always visible -->
        <div class="flex gap-3 p-4 flex-shrink-0" style="border-bottom:1px solid rgba(0,168,150,.08)">
          <?php foreach ($catDefs as $key => $cd): ?>
          <button @click="cat = (cat === '<?= $key ?>') ? null : '<?= $key ?>'"
                  :class="cat === '<?= $key ?>' ? 'active' : ''"
                  class="cat-tile">
            <span class="text-2xl"><?= $cd['emoji'] ?></span>
            <p class="text-xs font-bold" style="color:#0F172A"><?= $cd['label'] ?></p>
            <p class="text-[10px]" style="color:#94A3B8"><?= $cd['sub'] ?></p>
          </button>
          <?php endforeach; ?>
        </div>

        <!-- EV list — expands when category selected -->
        <div class="flex-1 overflow-hidden">
          <?php foreach ($catDefs as $catKey => $cd):
            $vehicles = !empty($rankedByCategory[$catKey]) ? $rankedByCategory[$catKey] : ($evFallback[$catKey] ?? []);
          ?>
          <div x-show="cat === '<?= $catKey ?>'" x-cloak
               x-transition:enter="transition ease-out duration-200"
               x-transition:enter-start="opacity-0 translate-y-2"
               x-transition:enter-end="opacity-100 translate-y-0">

            <?php if (empty($vehicles)): ?>
            <div class="text-center py-8" style="color:#94A3B8">
              <p class="text-2xl mb-1"><?= $cd['emoji'] ?></p>
              <p class="text-xs">No EVs yet. <a href="<?= base_url('vehicles') ?>" style="color:#00A896" class="font-bold">Browse all →</a></p>
            </div>
            <?php else: ?>

            <!-- Top 3 equal-height cards -->
            <div class="ev3-grid p-3 pb-2">
              <?php foreach (array_slice($vehicles,0,3) as $ri => $v):
                $slug  = $v['slug']??'#';
                $price = (int)($v['starting_price']??0);
                $range = (int)($v['claimed_range']??$v['real_world_range']??0);
                $rtng  = (float)($v['expert_rating']??0);
                $pStr  = $price>=10000000?'₹'.round($price/10000000,1).' Cr':($price>=100000?'₹'.round($price/100000,1).' L':'₹'.number_format($price));
                $hg    = $headGrads[$ri] ?? 'linear-gradient(135deg,#F0FDF9,#E0FFF9)';
                $rs    = $rankStyles[$ri] ?? 'background:#E2E8F0;color:#64748B';
              ?>
              <a href="<?= base_url('vehicles/'.$slug) ?>" class="ev3-card group" style="<?= $ri===0?'border-color:#F59E0B;border-width:2px':'' ?>">
                <span class="absolute top-1.5 left-1.5 z-10 w-[18px] h-[18px] rounded-full flex items-center justify-center" style="font-size:9px;font-weight:900;<?= $rs ?>"><?= $ri===0?'★':($ri+1) ?></span>
                <!-- Fixed-height colored head (no images needed) -->
                <div class="ev3-head" style="background:<?= $hg ?>">
                  <span class="text-3xl" style="opacity:.55"><?= $cd['emoji'] ?></span>
                </div>
                <!-- Body: flex-col, footer pinned to bottom -->
                <div class="ev3-body">
                  <p class="text-[9px] font-bold uppercase tracking-wide truncate mb-0.5" style="color:#94A3B8"><?= esc($v['brand_name']??'') ?></p>
                  <p class="text-[11px] font-black leading-tight truncate" style="color:#0F172A"><?= esc($v['name']??'') ?></p>
                  <div class="ev3-foot">
                    <div class="flex items-center justify-between gap-1">
                      <span class="text-xs font-black" style="color:#0F172A"><?= $pStr ?></span>
                      <?php if ($rtng>0): ?><span class="text-[10px] font-bold flex-shrink-0" style="color:#D97706">★<?= number_format($rtng,1) ?></span><?php endif; ?>
                    </div>
                    <?php if ($range>0): ?><p class="text-[10px] mt-0.5" style="color:#94A3B8"><?= $range ?> km</p><?php endif; ?>
                  </div>
                </div>
              </a>
              <?php endforeach; ?>
            </div>

            <!-- Rank 4+ rows -->
            <?php $rest=array_slice($vehicles,3); if (!empty($rest)): ?>
            <div class="mx-3 rounded-xl overflow-hidden mb-2" style="border:1px solid rgba(0,168,150,.1)">
              <?php foreach ($rest as $ri => $v):
                $pStr = (int)$v['starting_price']>=100000?'₹'.round($v['starting_price']/100000,1).' L':'₹'.number_format((int)$v['starting_price']);
              ?>
              <a href="<?= base_url('vehicles/'.($v['slug']??'#')) ?>"
                 class="ev-row group <?= $ri<count($rest)-1?'border-b':'' ?>"
                 style="<?= $ri<count($rest)-1?'border-color:rgba(0,168,150,.07)':'' ?>">
                <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-black flex-shrink-0" style="background:#F1F5F9;color:#94A3B8"><?= $ri+4 ?></span>
                <div class="flex-1 min-w-0">
                  <p class="text-xs font-bold truncate group-hover:text-[#00A896] transition-colors" style="color:#0F172A"><?= esc($v['name']??'') ?></p>
                  <p class="text-[10px]" style="color:#94A3B8"><?= esc($v['brand_name']??'') ?></p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                  <?php if (!empty($v['claimed_range'])): ?><span class="ev-stat-chip"><?= (int)$v['claimed_range'] ?>km</span><?php endif; ?>
                  <?php if (!empty($v['expert_rating'])): ?><span class="text-[10px] font-bold" style="color:#D97706">★<?= number_format($v['expert_rating'],1) ?></span><?php endif; ?>
                  <span class="text-xs font-black" style="color:#0F172A"><?= $pStr ?></span>
                </div>
              </a>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <div class="px-3 pb-3">
              <a href="<?= base_url('vehicles?category='.urlencode($catKey)) ?>"
                 class="flex items-center justify-center gap-1.5 w-full text-white font-bold text-xs rounded-xl py-2.5"
                 style="background:#00A896;box-shadow:0 2px 8px rgba(0,168,150,.2)"
                 onmouseover="this.style.boxShadow='0 4px 14px rgba(0,168,150,.35)'"
                 onmouseout="this.style.boxShadow='0 2px 8px rgba(0,168,150,.2)'">
                See all <?= $cd['emoji'] ?> <?= $cd['label'] ?>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
              </a>
            </div>
            <?php endif; ?>
          </div>
          <?php endforeach; ?>

          <!-- Placeholder when no category selected -->
          <div x-show="!cat" class="flex flex-col items-center justify-center py-10 px-4 text-center" style="color:#94A3B8">
            <span class="text-4xl mb-3 opacity-40">⚡</span>
            <p class="text-sm font-semibold" style="color:#64748B">Select a category above</p>
            <p class="text-xs mt-1">to see top-ranked EVs</p>
          </div>
        </div><!-- /flex-1 -->
      </div><!-- /best EVs card -->

    </div><!-- /panels-wrap -->
  </div>
</section>

<div class="section-divider"></div>


<!-- ════════════════════════════════════════════════════════
  FEATURE GRID — "Everything you need"
════════════════════════════════════════════════════════ -->
<section class="py-14 sm:py-20" style="background:#FFFFFF">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Header -->
    <div class="text-center mb-10 reveal">
      <span class="badge-green mb-3 inline-flex">What Charj.in does</span>
      <h2 class="text-2xl sm:text-4xl font-black leading-tight mt-3" style="color:#0F172A">
        Built for India's <span class="hero-gradient-text">EV revolution</span>
      </h2>
      <p class="mt-2 text-base max-w-xl mx-auto" style="color:#64748B">Not a listing site. A decision engine.</p>
    </div>

    <!-- !! DO NOT CHANGE: 6-column compact feature grid — user-locked layout !! -->
    <?php
    $features = [
      ['icon'=>'🎯','title'=>'EV Finder Quiz',        'desc'=>'3 matches in 2 min',           'url'=>base_url('find-my-ev')],
      ['icon'=>'⚖️','title'=>'Side-by-Side Compare',  'desc'=>'Compare 2–3 EVs',              'url'=>base_url('compare')],
      ['icon'=>'🎁','title'=>'Subsidy Calculator',    'desc'=>'FAME II + state + 80EEB',      'url'=>base_url('subsidy-calculator')],
      ['icon'=>'⚡','title'=>'Charging Cost/KM',      'desc'=>'₹/km vs petrol',              'url'=>base_url('charging-cost')],
      ['icon'=>'🗺️','title'=>'Trip Range Checker',   'desc'=>'Can your EV make it?',         'url'=>base_url('can-i-make-it')],
      ['icon'=>'💰','title'=>'5-Year TCO',            'desc'=>'Real ownership cost',          'url'=>base_url('tco-calculator')],
      ['icon'=>'🔌','title'=>'Charger Compatibility', 'desc'=>'Tata Power? Statiq?',          'url'=>base_url('charger-check')],
      ['icon'=>'📈','title'=>'Resale Estimator',      'desc'=>'Value in 3 years',             'url'=>base_url('resale-estimator')],
      ['icon'=>'🔋','title'=>'Battery Cost Guide',    'desc'=>'Replacement cost guide',       'url'=>base_url('battery-cost')],
      ['icon'=>'🏠','title'=>'Home Charger Guide',    'desc'=>'Setup cost by city',           'url'=>base_url('home-charger-guide')],
      ['icon'=>'🚛','title'=>'Fleet ROI Calculator',  'desc'=>'Savings for your fleet',       'url'=>base_url('fleet-calculator')],
      ['icon'=>'📖','title'=>'EV Glossary',           'desc'=>'No jargon, plain English',     'url'=>base_url('ev-glossary')],
    ];
    ?>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sr-stagger">
      <?php foreach ($features as $i => $f):
        $staggerMs = ($i % 6) * 70;
      ?>
      <a href="<?= $f['url'] ?>"
         onclick="charjTrack('feature_click',{feature:'<?= addslashes(esc($f['title'])) ?>'})"
         class="feat-tile sr group flex flex-col items-center text-center p-3.5 transition-all duration-200"
         style="background:#FFFFFF;border:1.5px solid rgba(0,168,150,.22);border-radius:12px"
         data-enter-delay="<?= $staggerMs ?>"
         onmouseenter="this.style.borderColor='rgba(0,168,150,.55)';this.style.boxShadow='0 6px 20px rgba(0,168,150,.16)';this.style.transform='translateY(-4px) scale(1.02)'"
         onmouseleave="this.style.borderColor='rgba(0,168,150,.22)';this.style.boxShadow='none';this.style.transform=''">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl mb-2.5 flex-shrink-0 transition-transform duration-200 group-hover:scale-110"
             style="background:rgba(0,168,150,.07);border:1px solid rgba(0,168,150,.15)"><?= $f['icon'] ?></div>
        <h3 class="font-bold text-[11px] leading-snug mb-1" style="color:#0F172A"><?= esc($f['title']) ?></h3>
        <p class="text-[10px] leading-snug" style="color:#64748B"><?= esc($f['desc']) ?></p>
      </a>
      <?php endforeach; ?>
    </div>
    <!-- !! END locked section !! -->
  </div>
</section>

<div class="section-divider"></div>


<!-- ════════════════════════════════════════════════════════
  HOW IT WORKS — 3 clean steps
════════════════════════════════════════════════════════ -->
<section class="py-16 sm:py-24 reveal" style="background:#FFFFFF">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="text-center mb-14">
      <span class="badge-green mb-4 inline-flex">Simple Process</span>
      <h2 class="text-3xl sm:text-4xl font-black leading-tight mt-3" style="color:#0F172A">
        From confused to confident<br class="hidden sm:block"> in 3 steps
      </h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative sr-stagger">
      <!-- Connector line -->
      <div class="hidden md:block absolute top-10 left-[calc(33.33%+16px)] right-[calc(33.33%+16px)] hiw-connector pointer-events-none" aria-hidden="true"></div>

      <?php
      $steps = [
        ['emoji'=>'🎯','step'=>'01','title'=>'Tell us about yourself','desc'=>'2-minute quiz about your commute, budget and charging situation.','cta'=>'Take the quiz →','url'=>base_url('find-my-ev'),'color'=>'rgba(0,168,150,.08)','border'=>'rgba(0,168,150,.2)'],
        ['emoji'=>'📊','step'=>'02','title'=>'See your perfect matches','desc'=>'Ranked EVs with real-world range, subsidy price and EMI breakdown.','cta'=>null,'url'=>null,'color'=>'rgba(0,168,150,.08)','border'=>'rgba(0,168,150,.2)'],
        ['emoji'=>'🤝','step'=>'03','title'=>'Get the best deal','desc'=>'Connect with verified dealers. One call, no spam, on your terms.','cta'=>null,'url'=>null,'color'=>'rgba(14,165,233,.08)','border'=>'rgba(14,165,233,.2)'],
      ];
      foreach ($steps as $s): ?>
      <div class="flex flex-col items-center text-center">
        <div class="relative mb-6">
          <div class="w-20 h-20 rounded-3xl flex items-center justify-center text-4xl shadow-sm" style="background:<?= $s['color'] ?>;border:1.5px solid <?= $s['border'] ?>">
            <?= $s['emoji'] ?>
          </div>
          <span class="absolute -top-2 -right-2 w-7 h-7 text-white text-xs font-black rounded-xl flex items-center justify-center shadow-md" style="background:#00A896"><?= $s['step'] ?></span>
        </div>
        <h3 class="text-lg font-bold mb-2" style="color:#0F172A"><?= $s['title'] ?></h3>
        <p class="text-sm leading-relaxed max-w-xs mb-4" style="color:#64748B"><?= $s['desc'] ?></p>
        <?php if ($s['cta']): ?>
        <a href="<?= $s['url'] ?>" class="text-sm font-bold transition-colors" style="color:#00A896"><?= $s['cta'] ?></a>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<div class="section-divider"></div>


<!-- ════════════════════════════════════════════════════════
  TESTIMONIALS
════════════════════════════════════════════════════════ -->
<section class="py-16 sm:py-24 reveal" style="background:#FFFFFF">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="text-center mb-12">
      <span class="badge-green mb-4 inline-flex">Real buyers. Real savings.</span>
      <h2 class="text-2xl sm:text-3xl font-black mt-3" style="color:#0F172A">Trusted by EV buyers across India</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 sr-stagger">
      <?php
      $testimonials = [
        ['name'=>'Rahul M.','city'=>'Bangalore','avatar'=>'R','color'=>'#7C3AED','text'=>"Found the Ather 450X after taking the quiz. The subsidy calculator showed me I'd save ₹28,000 — I had no idea! Bought it within a week."],
        ['name'=>'Priya S.','city'=>'Mumbai','avatar'=>'P','color'=>'#0EA5E9','text'=>"Was confused between Nexon EV and ZS EV. The comparison tool made it so clear. Nexon won on range and after-sales."],
        ['name'=>'Amit K.','city'=>'Delhi','avatar'=>'A','color'=>'#00A896','text'=>"The fleet calculator convinced my boss to switch 15 delivery bikes to EVs. We're saving ₹45,000/month in fuel."],
      ];
      foreach ($testimonials as $ti => $t): ?>
      <div class="card p-4 sm:p-6">
        <!-- Stars -->
        <div class="flex gap-0.5 mb-4" aria-label="5 out of 5 stars">
          <?php for ($s=0;$s<5;$s++): ?>
          <svg class="w-4 h-4 star-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <?php endfor; ?>
        </div>
        <p class="text-sm leading-relaxed mb-5" style="color:#475569">"<?= esc($t['text']) ?>"</p>
        <div class="flex items-center gap-3 pt-4" style="border-top:1px solid rgba(0,168,150,.08)">
          <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0" style="background:<?= $t['color'] ?>">
            <?= esc($t['avatar']) ?>
          </div>
          <div>
            <p class="text-sm font-bold" style="color:#0F172A"><?= esc($t['name']) ?></p>
            <p class="text-xs" style="color:#64748B"><?= esc($t['city']) ?></p>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Press logos -->
    <div class="mt-14 flex flex-col items-center gap-4">
      <p class="text-xs uppercase tracking-widest font-semibold" style="color:#94A3B8">Featured in</p>
      <div class="flex flex-wrap items-center justify-center gap-10">
        <?php foreach (['India Today','ET Auto','Moneycontrol'] as $press): ?>
        <span class="press-logo"><?= esc($press) ?></span>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<div class="section-divider"></div>


<!-- ════════════════════════════════════════════════════════
  LATEST ARTICLES (conditional)
════════════════════════════════════════════════════════ -->
<?php if (!empty($latestArticles)): ?>
<section class="py-16 sm:py-24 reveal" style="background:#F7FFFE">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="flex items-end justify-between mb-10">
      <div>
        <span class="badge-green mb-3 inline-flex">Stay Updated</span>
        <h2 class="text-2xl sm:text-3xl font-black mt-2" style="color:#0F172A">EV News &amp; Guides</h2>
      </div>
      <a href="<?= base_url('blog') ?>" class="text-sm font-bold transition-colors hidden sm:block" style="color:#00A896">All articles →</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sr-stagger">
      <?php foreach (array_slice($latestArticles,0,3) as $article): ?>
      <article class="card group flex flex-col overflow-hidden">
        <?php if (!empty($article['thumbnail_url'])): ?>
        <a href="<?= base_url('blog/'.esc($article['slug']??'')) ?>" class="block overflow-hidden flex-shrink-0">
          <img src="<?= esc($article['thumbnail_url']) ?>"
               alt="<?= esc($article['title']??'') ?>"
               class="w-full h-44 object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
        </a>
        <?php else: ?>
        <a href="<?= base_url('blog/'.esc($article['slug']??'')) ?>" class="block flex-shrink-0">
          <div class="w-full h-44 flex items-center justify-center text-5xl" style="background:linear-gradient(135deg,#F0FDFA,#E0FFF9)">⚡</div>
        </a>
        <?php endif; ?>
        <div class="flex flex-col flex-1 p-5">
          <?php if (!empty($article['category'])): ?>
          <span class="badge-green text-[10px] mb-3 w-fit"><?= esc($article['category']) ?></span>
          <?php endif; ?>
          <h3 class="font-bold text-base leading-snug line-clamp-2 mb-2 flex-1" style="color:#0F172A">
            <a href="<?= base_url('blog/'.esc($article['slug']??'')) ?>" class="transition-colors hover:text-[#00A896]"><?= esc($article['title']??'') ?></a>
          </h3>
          <?php if (!empty($article['excerpt'])): ?>
          <p class="text-sm line-clamp-2 leading-relaxed mb-3" style="color:#64748B"><?= esc($article['excerpt']) ?></p>
          <?php endif; ?>
          <div class="flex items-center gap-2 pt-3 text-xs mt-auto" style="color:#94A3B8;border-top:1px solid rgba(0,168,150,.07)">
            <?php if (!empty($article['published_at'])): ?>
            <time><?= date('d M Y',strtotime($article['published_at'])) ?></time>
            <?php endif; ?>
            <?php if (!empty($article['read_time'])): ?>
            <span>· <?= (int)$article['read_time'] ?> min read</span>
            <?php endif; ?>
            <a href="<?= base_url('blog/'.esc($article['slug']??'')) ?>" class="ml-auto font-bold transition-colors" style="color:#00A896">Read →</a>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
    <div class="mt-6 text-center sm:hidden">
      <a href="<?= base_url('blog') ?>" class="text-sm font-bold" style="color:#00A896">View all articles →</a>
    </div>
  </div>
</section>
<div class="section-divider"></div>
<?php endif; ?>


<!-- ════════════════════════════════════════════════════════
  FOR EV BRANDS
════════════════════════════════════════════════════════ -->
<section class="py-14 reveal" style="background:#F0FDFA;border-top:1px solid rgba(0,168,150,.1);border-bottom:1px solid rgba(0,168,150,.1)">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid md:grid-cols-2 gap-10 items-center">
      <div class="flex items-start gap-5 sr-left">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white text-2xl flex-shrink-0" style="background:#00A896;box-shadow:0 4px 14px rgba(0,168,150,.35)">📣</div>
        <div>
          <h2 class="text-2xl font-black mb-2" style="color:#0F172A">For EV Brands</h2>
          <p class="text-sm leading-relaxed" style="color:#475569">List your EVs on charj.in and get discovered by thousands of buyers actively looking for their next electric ride.</p>
          <a href="<?= base_url('for-brands') ?>" class="inline-flex items-center gap-1.5 mt-4 text-sm font-bold transition-colors" style="color:#00A896">
            Partner with us <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
          </a>
        </div>
      </div>
      <div class="grid grid-cols-2 gap-3 sr-stagger">
        <?php foreach ([
          ['📈','Increase Visibility'],
          ['⭐','Generate Quality Leads'],
          ['🤝','Build Brand Presence'],
          ['⚡',"India's EV Future"],
        ] as $b): ?>
        <div class="card flex flex-col items-center text-center p-5">
          <span class="text-2xl mb-2"><?= $b[0] ?></span>
          <span class="text-xs font-bold leading-tight" style="color:#0F172A"><?= $b[1] ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>


<!-- ════════════════════════════════════════════════════════
  FINAL CTA — Keep dark gradient for contrast impact
════════════════════════════════════════════════════════ -->
<section class="relative overflow-hidden py-24 text-center reveal" style="background:linear-gradient(160deg,#040C1E 0%,#071830 50%,#0A1E3A 100%)">

  <!-- Grid overlay -->
  <div class="absolute inset-0 hero-mesh opacity-40 pointer-events-none" aria-hidden="true"></div>

  <!-- Crystal glow -->
  <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[300px] pointer-events-none" style="background:radial-gradient(ellipse,rgba(0,168,150,.15),rgba(14,165,233,.08),transparent);filter:blur(40px)" aria-hidden="true"></div>

  <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="inline-flex items-center justify-center w-16 h-16 rounded-3xl mb-8" style="background:rgba(0,168,150,.15);border:1px solid rgba(0,168,150,.3);box-shadow:0 0 24px rgba(0,168,150,.2)">
      <svg class="w-8 h-8" style="color:#1AFFCC" fill="currentColor" viewBox="0 0 24 24"><path d="M13 2L4.09 12.97H11L10 22L20.91 11.03H14L13 2Z"/></svg>
    </div>

    <h2 class="text-4xl sm:text-5xl font-black text-white leading-tight mb-3">The EV revolution is here.</h2>
    <p class="text-3xl sm:text-4xl font-black mb-8">
      <span class="hero-gradient-text">Let's drive it together.</span>
    </p>
    <p class="text-base mb-10 max-w-lg mx-auto" style="color:#94A3B8">Find. Compare. Choose. All Electric. All in One Place.</p>

    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
      <a href="<?= base_url('find-my-ev') ?>"
         onclick="charjTrack('final_cta_click',{button:'finder'})"
         class="inline-flex items-center justify-center gap-2 text-white font-bold text-base px-8 py-4 rounded-full transition-all duration-200 hover:-translate-y-0.5 ripple-btn"
         style="background:linear-gradient(135deg,#00A896,#00bfa5);box-shadow:0 6px 20px rgba(0,168,150,.4)"
         onmouseover="this.style.boxShadow='0 10px 28px rgba(0,168,150,.5)'"
         onmouseout="this.style.boxShadow='0 6px 20px rgba(0,168,150,.4)'">
        Find My EV →
      </a>
      <a href="<?= base_url('vehicles') ?>"
         onclick="charjTrack('final_cta_click',{button:'browse'})"
         class="inline-flex items-center justify-center gap-2 font-bold text-base px-8 py-4 rounded-full hover:bg-white/10 transition-all duration-200 text-white"
         style="border:1.5px solid rgba(255,255,255,.2)">
        Browse all EVs
      </a>
    </div>

    <p class="text-sm" style="color:#64748B">Free forever &middot; No spam &middot; Built for India</p>
  </div>
</section>

<div class="pb-20 md:pb-0"></div>

<?= $this->endSection() ?>
