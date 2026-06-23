<?php
// CI4 layout — child views inject via $this->section()
$gaId = $gaId ?? '';
$metaPixelId = $metaPixelId ?? '';
$title = $title ?? $meta_title ?? 'Charj.in — India\'s EV Decision Engine';
$meta_desc = $meta_desc ?? $meta_description ?? 'Compare, calculate and choose the perfect EV. FAME II subsidies, TCO calculator, charging guide and more.';
$categories = $categories ?? [];
// Only homepage gets transparent hero nav; all other pages are always white
$transparentNav = $transparentNav ?? false;
?>
<!DOCTYPE html>
<html lang="en-IN" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($title) ?></title>
  <meta name="description" content="<?= esc($meta_desc) ?>">
  <meta name="theme-color" content="#16a34a">
  <link rel="icon" href="<?= base_url('assets/images/favicon.ico') ?>" type="image/x-icon">
  <link rel="apple-touch-icon" href="<?= base_url('assets/images/apple-touch-icon.png') ?>">

  <!-- Open Graph -->
  <meta property="og:title" content="<?= esc($title) ?>">
  <meta property="og:description" content="<?= esc($meta_desc) ?>">
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?= current_url() ?>">
  <meta property="og:image" content="<?= base_url('assets/images/charj-og.jpg') ?>">
  <meta property="og:site_name" content="Charj.in">
  <meta property="og:locale" content="en_IN">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= esc($title) ?>">
  <meta name="twitter:description" content="<?= esc($meta_desc) ?>">
  <meta name="twitter:image" content="<?= base_url('assets/images/charj-og.jpg') ?>">

  <!-- Google Search Console verification -->
  <?php if (!empty($gscVerification = ($settings['gsc_verification'] ?? ''))): ?>
  <meta name="google-site-verification" content="<?= esc($gscVerification) ?>">
  <?php endif; ?>

  <!-- Canonical -->
  <link rel="canonical" href="<?= current_url() ?>">

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['-apple-system','BlinkMacSystemFont','Segoe UI','Roboto','sans-serif'] },
          colors: {
            brand: { DEFAULT: '#16a34a', light: '#dcfce7', dark: '#14532d' },
            'charj-navy': '#0d1f35',
            'charj-green': '#22c55e',
            'charj-green-dark': '#16a34a',
          }
        }
      }
    }
  </script>

  <!-- Alpine.js x-intersect plugin (must load before Alpine core) -->
  <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
  <!-- Alpine.js core -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <style>
    /* ═══ BASE TYPOGRAPHY ═══ */
    *, *::before, *::after { box-sizing: border-box; }
    [x-cloak] { display: none !important; }
    .line-clamp-2 { display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
    .line-clamp-3 { display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }
    .scrollbar-hide::-webkit-scrollbar { display:none; }
    .scrollbar-hide { -ms-overflow-style:none; scrollbar-width:none; }

    /* ═══ KEYFRAMES ═══ */
    @keyframes fadeInUp    { from{opacity:0;transform:translateY(28px)} to{opacity:1;transform:translateY(0)} }
    @keyframes fadeInLeft  { from{opacity:0;transform:translateX(-24px)} to{opacity:1;transform:translateX(0)} }
    @keyframes fadeInRight { from{opacity:0;transform:translateX(24px)} to{opacity:1;transform:translateX(0)} }
    @keyframes scaleIn     { from{opacity:0;transform:scale(.9)} to{opacity:1;transform:scale(1)} }
    @keyframes gradShift   { 0%{background-position:0% 50%} 50%{background-position:100% 50%} 100%{background-position:0% 50%} }
    @keyframes shimmer     { to{background-position:-200% center} }
    @keyframes pulse-glow  { 0%,100%{box-shadow:0 0 0 0 rgba(16,185,129,.0)} 50%{box-shadow:0 0 20px 4px rgba(16,185,129,.3),0 0 40px 8px rgba(16,185,129,.12)} }
    @keyframes neon-border { 0%,100%{border-color:rgba(16,185,129,.3)} 50%{border-color:rgba(16,185,129,.7)} }
    @keyframes float1      { 0%,100%{transform:translateY(0) translateX(0)} 33%{transform:translateY(-16px) translateX(8px)} 66%{transform:translateY(6px) translateX(-10px)} }
    @keyframes float2      { 0%,100%{transform:translateY(0) translateX(0)} 50%{transform:translateY(-22px) translateX(12px)} }
    @keyframes float3      { 0%,100%{transform:translateY(0) rotate(0deg)} 50%{transform:translateY(-12px) rotate(180deg)} }
    @keyframes blink       { 0%,100%{opacity:1} 50%{opacity:0} }
    @keyframes slideUp     { from{transform:translateY(100%);opacity:0} to{transform:translateY(0);opacity:1} }
    @keyframes aurora      { 0%,100%{opacity:.06;transform:scale(1) translate(0,0)} 33%{opacity:.1;transform:scale(1.1) translate(20px,-10px)} 66%{opacity:.07;transform:scale(.95) translate(-15px,10px)} }

    /* ═══ ANIMATION CLASSES ═══ */
    .animate-fade-in-up    { animation:fadeInUp .55s cubic-bezier(.4,0,.2,1) forwards; }
    .animate-fade-in-left  { animation:fadeInLeft .55s cubic-bezier(.4,0,.2,1) forwards; }
    .animate-fade-in-right { animation:fadeInRight .55s cubic-bezier(.4,0,.2,1) forwards; }
    .anim-grad             { background-size:200% 200%; animation:gradShift 8s ease infinite; }
    .float-1  { animation:float1 7s ease-in-out infinite; }
    .float-2  { animation:float2 9s ease-in-out infinite; }
    .float-3  { animation:float3 5.5s ease-in-out infinite; }
    .aurora   { animation:aurora 12s ease-in-out infinite; }
    .shimmer  { background:linear-gradient(90deg,#152b30 25%,#1c343a 50%,#152b30 75%); background-size:200%; animation:shimmer 1.5s linear infinite; }
    .pulse-glow { animation:pulse-glow 3s ease-in-out infinite; }
    .cursor   { display:inline-block; width:2px; height:1em; background:#10b981; margin-left:2px; animation:blink 1s step-end infinite; vertical-align:text-bottom; }
    .stagger-1{animation-delay:.05s} .stagger-2{animation-delay:.10s} .stagger-3{animation-delay:.15s}
    .stagger-4{animation-delay:.20s} .stagger-5{animation-delay:.25s} .stagger-6{animation-delay:.30s}

    /* ═══ SCROLL REVEAL ═══ */
    .reveal { opacity:0; transform:translateY(20px); transition:opacity .65s cubic-bezier(.4,0,.2,1), transform .65s cubic-bezier(.4,0,.2,1); }
    .reveal.visible { opacity:1; transform:translateY(0); }
    .sr       { opacity:0; transform:translateY(28px); transition:opacity .55s cubic-bezier(.4,0,.2,1), transform .55s cubic-bezier(.4,0,.2,1); }
    .sr-left  { opacity:0; transform:translateX(-28px); transition:opacity .55s cubic-bezier(.4,0,.2,1), transform .55s cubic-bezier(.4,0,.2,1); }
    .sr-right { opacity:0; transform:translateX(28px);  transition:opacity .55s cubic-bezier(.4,0,.2,1), transform .55s cubic-bezier(.4,0,.2,1); }
    .sr-scale { opacity:0; transform:scale(.92);        transition:opacity .55s cubic-bezier(.4,0,.2,1), transform .55s cubic-bezier(.4,0,.2,1); }
    .sr.sr-visible, .sr-left.sr-visible, .sr-right.sr-visible, .sr-scale.sr-visible { opacity:1; transform:none; }
    /* stagger grid */
    .sr-stagger > * { opacity:0; transform:translateY(22px); transition:opacity .5s cubic-bezier(.4,0,.2,1), transform .5s cubic-bezier(.4,0,.2,1); }
    .sr-stagger.sr-visible > * { opacity:1; transform:none; }
    .sr-stagger.sr-visible > *:nth-child(1)  { transition-delay:.04s; }
    .sr-stagger.sr-visible > *:nth-child(2)  { transition-delay:.08s; }
    .sr-stagger.sr-visible > *:nth-child(3)  { transition-delay:.12s; }
    .sr-stagger.sr-visible > *:nth-child(4)  { transition-delay:.16s; }
    .sr-stagger.sr-visible > *:nth-child(5)  { transition-delay:.20s; }
    .sr-stagger.sr-visible > *:nth-child(6)  { transition-delay:.24s; }
    .sr-stagger.sr-visible > *:nth-child(7)  { transition-delay:.28s; }
    .sr-stagger.sr-visible > *:nth-child(8)  { transition-delay:.32s; }
    .sr-stagger.sr-visible > *:nth-child(9)  { transition-delay:.36s; }
    .sr-stagger.sr-visible > *:nth-child(10) { transition-delay:.40s; }
    .sr-stagger.sr-visible > *:nth-child(11) { transition-delay:.44s; }
    .sr-stagger.sr-visible > *:nth-child(12) { transition-delay:.48s; }

    /* ═══ COMPONENTS (ELEGANT DARK · SIEMENS TEAL) ═══ */
    /* Buttons */
    .btn-primary { display:inline-flex; align-items:center; gap:8px; background:#10b981; color:#fff; padding:11px 24px; border-radius:100px; font-weight:800; font-size:.875rem; transition:all .2s; text-decoration:none; letter-spacing:.01em; box-shadow:0 4px 14px -2px rgba(16,185,129,.4); }
    .btn-primary:hover { background:#059669; transform:translateY(-2px); box-shadow:0 0 0 4px rgba(16,185,129,.18), 0 8px 24px -4px rgba(16,185,129,.5); }
    .btn-outline { display:inline-flex; align-items:center; gap:8px; border:1.5px solid rgba(16,185,129,.55); color:#10b981; padding:10px 22px; border-radius:100px; font-weight:700; font-size:.875rem; transition:all .2s; text-decoration:none; background:transparent; }
    .btn-outline:hover { background:rgba(16,185,129,.12); border-color:#10b981; transform:translateY(-1px); box-shadow:0 0 16px rgba(16,185,129,.25); }
    /* Cards */
    .card { background:#152b30; border:1px solid rgba(255,255,255,.06); border-radius:1rem; transition:all .25s cubic-bezier(.4,0,.2,1); }
    .card:hover { border-color:rgba(16,185,129,.4); box-shadow:0 0 0 1px rgba(16,185,129,.12), 0 20px 40px -12px rgba(0,0,0,.5), 0 0 22px -4px rgba(16,185,129,.18); transform:translateY(-3px); }
    .card-hover { transition:transform .25s ease, box-shadow .25s ease, border-color .25s ease; }
    .card-hover:hover { transform:translateY(-3px); box-shadow:0 20px 40px -12px rgba(0,0,0,.45), 0 0 22px -4px rgba(16,185,129,.18); border-color:rgba(16,185,129,.4); }
    /* Glass */
    .glass { background:rgba(255,255,255,.05); backdrop-filter:blur(12px); -webkit-backdrop-filter:blur(12px); border:1px solid rgba(255,255,255,.09); }
    .glass-green { background:rgba(16,185,129,.12); backdrop-filter:blur(12px); border:1px solid rgba(16,185,129,.3); }
    /* Glow text */
    .neon-green { color:#10b981; text-shadow:0 0 18px rgba(16,185,129,.7), 0 0 38px rgba(16,185,129,.3); }
    .neon-blue  { color:#38bdf8; text-shadow:0 0 18px rgba(56,189,248,.55), 0 0 38px rgba(56,189,248,.2); }
    /* Gradient text */
    .gradient-text { background:linear-gradient(135deg,#10b981,#38bdf8); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
    /* Nav */
    .nav-dark { background:rgba(12,26,29,.9); backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px); border-bottom:1px solid rgba(255,255,255,.06); }
    /* VC card */
    .vc-card { cursor:pointer; transition:transform .3s cubic-bezier(.4,0,.2,1), box-shadow .3s cubic-bezier(.4,0,.2,1), border-color .3s; }
    .vc-card:hover { transform:translateY(-6px); box-shadow:0 0 0 1px rgba(16,185,129,.35), 0 24px 60px -12px rgba(0,0,0,.6), 0 0 30px -6px rgba(16,185,129,.2); }
    /* Filter chip */
    .filter-chip { transition:all .15s; }
    .filter-chip:hover { border-color:rgba(248,113,113,.45) !important; color:#f87171 !important; }
    .filter-chip:hover .chip-x { opacity:1; }
    /* Misc */
    .range-btn.active, .price-btn.active { background:#10b981; color:#fff; font-weight:700; }
    .compare-bar-enter { animation:slideUp .3s cubic-bezier(.22,.68,0,1.2) both; }
    .gradient-border { position:relative; }
    .gradient-border::after { content:''; position:absolute; inset:-1px; border-radius:inherit; background:linear-gradient(135deg,#10b981,#38bdf8); z-index:-1; opacity:0; transition:opacity .3s; }
    .gradient-border:hover::after { opacity:1; }
    /* Page in */
    @keyframes pageIn { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:none} }
    #main-content { animation:pageIn .4s ease-out; }
  </style>

  <!-- Google Analytics -->
  <?php if (!empty($gaId) && $gaId !== 'G-XXXXXXXXXX'): ?>
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?= esc($gaId) ?>"></script>
  <script>
    window.dataLayer=window.dataLayer||[];
    function gtag(){dataLayer.push(arguments)}
    gtag('js',new Date());
    gtag('config','<?= esc($gaId) ?>',{'anonymize_ip':true});
    window.charjTrack=function(e,p){gtag('event',e,p||{})};
  </script>
  <?php else: ?>
  <script>window.charjTrack=function(e,p){console.log('[charjTrack]',e,p||{})};</script>
  <?php endif; ?>

  <!-- Meta Pixel -->
  <?php if (!empty($metaPixelId) && $metaPixelId !== 'XXXXXXXXXXXXXXXXXX'): ?>
  <script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init','<?= esc($metaPixelId) ?>');fbq('track','PageView');</script>
  <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?= esc($metaPixelId) ?>&ev=PageView&noscript=1"/></noscript>
  <?php endif; ?>

  <!-- Schema.org WebSite -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "Charj.in",
    "url": "<?= base_url() ?>",
    "description": "India's EV Decision Engine — Find, compare and choose the right electric vehicle.",
    "potentialAction": {
      "@type": "SearchAction",
      "target": { "@type": "EntryPoint", "urlTemplate": "<?= base_url('vehicles?q={search_term_string}') ?>" },
      "query-input": "required name=search_term_string"
    }
  }
  </script>

  <?= $this->renderSection('head') ?>
</head>
<body class="antialiased"
      style="background:linear-gradient(180deg,#dff5ea 0%,#e8f7ef 100%);color:#14532d"
      x-data="{mobileOpen:false, scrollY:0, lastScrollY:0, navHidden:false, transparentNav: <?= $transparentNav ? 'true' : 'false' ?>}"
      @scroll.window="lastScrollY=scrollY; scrollY=window.scrollY; navHidden=(scrollY > lastScrollY && scrollY > 100)">

<?php if (session()->get('admin_previewing_as_customer')): ?>
<!-- ADMIN PREVIEW BANNER -->
<div class="fixed top-0 inset-x-0 z-[100] bg-indigo-600 text-white text-sm flex items-center justify-between px-4 py-2 shadow-lg">
  <span class="flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
    <strong>Admin Preview</strong><span class="hidden sm:inline"> — Viewing as customer</span>
  </span>
  <a href="<?= site_url('admin/exit-customer-preview') ?>"
     class="flex items-center gap-1.5 bg-white text-indigo-700 font-bold text-xs px-3 py-1.5 rounded-full hover:bg-indigo-50 transition-colors">
    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    Back to Admin
  </a>
</div>
<div class="h-10"></div><!-- push content below banner -->
<?php endif; ?>

<!-- TOP NAV -->
<header class="z-50" style="position:fixed;top:0;left:0;right:0;height:56px;background:rgba(5,150,105,0.95);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border-bottom:1px solid rgba(255,255,255,0.1);box-shadow:0 4px 12px rgba(5,150,105,0.15);transition:transform 300ms cubic-bezier(0.4,0,0.2,1)"
        :style="{ transform: navHidden ? 'translateY(-100%)' : 'translateY(0)' }">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 flex items-center justify-between h-full" style="transition: transform 300ms ease-out;">

      <!-- Logo -->
      <a href="<?= base_url() ?>" class="flex items-center gap-2.5" aria-label="Charj.in — India's Dedicated EV Marketplace">
        <svg viewBox="0 0 36 36" class="h-8 w-8 flex-shrink-0" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="18" cy="18" r="17" fill="rgba(16,185,129,0.12)" stroke="rgba(16,185,129,0.4)" stroke-width="1"/>
          <path d="M26 10.5A10 10 0 1 0 26 25.5" stroke="#10b981" stroke-width="3.5" stroke-linecap="round" fill="none"/>
          <path d="M19.5 9L13 19h5l-1.5 8L24.5 17H20L19.5 9Z" fill="#10b981"/>
        </svg>
        <span class="font-black text-lg tracking-tight leading-none text-white">
          charj<span style="color:#6ee7b7">.in</span>
        </span>
      </a>

      <!-- Desktop Nav -->
      <nav class="hidden md:flex items-center gap-1" aria-label="Main navigation">
          <a href="<?= base_url('explore') ?>" class="rounded-full px-4 py-2 text-sm font-semibold transition-colors text-white hover:text-white hover:bg-white/10">Explore</a>
        <?php
        $navLinks = [
          ['label'=>'EVs','href'=>base_url('vehicles')],
          ['label'=>'Compare','href'=>base_url('compare')],
          ['label'=>'Calculators','href'=>'#','sub'=>[
            ['label'=>'Savings Calculator','href'=>base_url('tco-calculator'),'icon'=>'💰'],
            ['label'=>'Subsidy Finder','href'=>base_url('subsidy-calculator'),'icon'=>'🎁'],
            ['label'=>'EMI Calculator','href'=>base_url('ev-emi-calculator'),'icon'=>'📊'],
            ['label'=>'Fleet ROI','href'=>base_url('fleet-calculator'),'icon'=>'🚛'],
          ]],
          ['label'=>'Tools','href'=>'#','sub'=>[
            ['label'=>'EV Finder Quiz','href'=>base_url('find-my-ev'),'icon'=>'🎯'],
            ['label'=>'Charging Cost','href'=>base_url('charging-cost'),'icon'=>'⚡'],
            ['label'=>'Trip Range Check','href'=>base_url('can-i-make-it'),'icon'=>'🗺️'],
            ['label'=>'Charger Compatibility','href'=>base_url('charger-check'),'icon'=>'🔌'],
            ['label'=>'Resale Estimator','href'=>base_url('resale-estimator'),'icon'=>'📈'],
            ['label'=>'Battery Cost','href'=>base_url('battery-cost'),'icon'=>'🔋'],
          ]],
          ['label'=>'Charging','href'=>base_url('charging-stations')],
          ['label'=>'Community','href'=>'#','sub'=>[
            ['label'=>'News & Articles','href'=>base_url('news'),'icon'=>'📰'],
            ['label'=>'Events & Expos','href'=>base_url('events'),'icon'=>'🎪'],
            ['label'=>'Announcements','href'=>base_url('announcements'),'icon'=>'📢'],
            ['label'=>'Spare Parts','href'=>base_url('spare-parts'),'icon'=>'🔧'],
          ]],
          ['label'=>'Guides','href'=>'#','sub'=>[
            ['label'=>'Home Charger Guide','href'=>base_url('home-charger-guide'),'icon'=>'🏠'],
            ['label'=>'EV for Apartment','href'=>base_url('ev-for-apartment'),'icon'=>'🏢'],
            ['label'=>'Used EV Guide','href'=>base_url('used-ev'),'icon'=>'🔄'],
            ['label'=>'EV Glossary','href'=>base_url('ev-glossary'),'icon'=>'📖'],
          ]],
        ];
        foreach ($navLinks as $link):
          if (!empty($link['sub'])):
        ?>
        <div class="relative" x-data="{open:false}" @mouseenter="open=true" @mouseleave="open=false">
          <button class="flex items-center gap-1 rounded-full px-4 py-2 text-sm font-semibold transition-colors text-white hover:text-white hover:bg-white/10">
            <?= esc($link['label']) ?>
            <svg class="h-3.5 w-3.5 opacity-60 transition-transform" :class="open?'rotate-180':''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 9l-7 7-7-7"/></svg>
          </button>
          <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95 -translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-cloak
               class="absolute top-full left-1/2 -translate-x-1/2 mt-2 w-56 rounded-2xl p-2 shadow-2xl" style="background:#f5faf9;border:1px solid #d1fae5;box-shadow:0 20px 40px -12px rgba(0,0,0,.1)">
            <?php foreach ($link['sub'] as $sub): ?>
            <a href="<?= esc($sub['href']) ?>" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-emerald-100 hover:text-emerald-700 transition-colors">
              <span class="text-base" aria-hidden="true"><?= $sub['icon'] ?></span>
              <?= esc($sub['label']) ?>
            </a>
            <?php endforeach; ?>
          </div>
        </div>
        <?php else: ?>
        <a href="<?= esc($link['href']) ?>" class="rounded-full px-4 py-2 text-sm font-semibold transition-colors text-white hover:text-white hover:bg-white/10">
          <?= esc($link['label']) ?>
        </a>
        <?php endif; endforeach; ?>
      </nav>

      <!-- CTA + Mobile toggle -->
      <div class="flex items-center gap-3">
        <a href="<?= base_url('find-my-ev') ?>" onclick="charjTrack('header_cta_click',{location:'header'})"
           class="hidden sm:flex items-center gap-2 text-sm font-bold text-white rounded-full py-2.5 px-6 transition-all duration-250"
           style="background:#10b981;box-shadow:0 8px 24px rgba(16,185,129,0.3);border:1px solid rgba(255,255,255,0.2)"
           @mouseover="this.style.transform='scale(1.05)';this.style.boxShadow='0 12px 32px rgba(16,185,129,0.4)'"
           @mouseout="this.style.transform='scale(1)';this.style.boxShadow='0 8px 24px rgba(16,185,129,0.3)'">
          Find My EV ⚡
          <svg class="h-4 w-4 transition-transform duration-250" style="transform:translateX(0)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
        </a>
        <button @click="mobileOpen=!mobileOpen" class="md:hidden rounded-xl p-2 transition-colors text-white hover:bg-white/10"
                aria-label="Toggle navigation menu" :aria-expanded="mobileOpen.toString()">
          <svg x-show="!mobileOpen" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
          <svg x-show="mobileOpen" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
  </div>

  <!-- Mobile menu -->
  <div x-show="mobileOpen" x-cloak
       x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
       class="md:hidden px-4 py-4 shadow-2xl" style="background:#f5faf9;border-top:1px solid #d1fae5"
       @keydown.escape.window="mobileOpen=false">
    <div class="space-y-1">
      <a href="<?= base_url('explore') ?>" @click="mobileOpen=false" class="flex items-center gap-3 rounded-xl px-4 py-3 font-semibold text-slate-700 hover:bg-emerald-100 hover:text-emerald-700">🔍 Explore Brands</a>
      <a href="<?= base_url('vehicles') ?>" @click="mobileOpen=false" class="flex items-center gap-3 rounded-xl px-4 py-3 font-semibold text-slate-700 hover:bg-emerald-100 hover:text-emerald-700">🚗 Browse EVs</a>
      <a href="<?= base_url('compare') ?>" @click="mobileOpen=false" class="flex items-center gap-3 rounded-xl px-4 py-3 font-semibold text-slate-700 hover:bg-emerald-100 hover:text-emerald-700">⚖️ Compare</a>
      <a href="<?= base_url('find-my-ev') ?>" @click="mobileOpen=false" class="flex items-center gap-3 rounded-xl px-4 py-3 font-semibold text-slate-700 hover:bg-emerald-100 hover:text-emerald-700">🎯 Find My EV</a>
      <hr class="my-2" style="border-color:#d1fae5">
      <p class="px-4 text-xs font-bold uppercase tracking-widest text-slate-600">Calculators</p>
      <a href="<?= base_url('tco-calculator') ?>" @click="mobileOpen=false" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm text-slate-600 hover:bg-emerald-100 hover:text-emerald-700">💰 Savings Calculator</a>
      <a href="<?= base_url('subsidy-calculator') ?>" @click="mobileOpen=false" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm text-slate-600 hover:bg-emerald-100 hover:text-emerald-700">🎁 Subsidy Finder</a>
      <a href="<?= base_url('charging-cost') ?>" @click="mobileOpen=false" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm text-slate-600 hover:bg-emerald-100 hover:text-emerald-700">⚡ Charging Cost</a>
      <a href="<?= base_url('can-i-make-it') ?>" @click="mobileOpen=false" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm text-slate-600 hover:bg-emerald-100 hover:text-emerald-700">🗺️ Trip Range Check</a>
      <hr class="my-2" style="border-color:#d1fae5">
      <a href="<?= base_url('charging-stations') ?>" @click="mobileOpen=false" class="flex items-center gap-3 rounded-xl px-4 py-3 font-semibold text-slate-700 hover:bg-emerald-100 hover:text-emerald-700">⚡ Charging Stations</a>
      <a href="<?= base_url('ev-glossary') ?>" @click="mobileOpen=false" class="flex items-center gap-3 rounded-xl px-4 py-3 font-semibold text-slate-700 hover:bg-emerald-100 hover:text-emerald-700">📖 EV Glossary</a>
      <a href="<?= base_url('ev-dealers') ?>" @click="mobileOpen=false" class="flex items-center gap-3 rounded-xl px-4 py-3 font-semibold text-slate-700 hover:bg-emerald-100 hover:text-emerald-700">📍 Find EV Dealers</a>
    </div>
    <hr class="my-2" style="border-color:#d1fae5">
    <a href="<?= base_url('find-my-ev') ?>" @click="mobileOpen=false" onclick="charjTrack('header_cta_click',{location:'mobile_menu'})"
       class="mt-4 btn-primary w-full justify-center">Find My Perfect EV →</a>
  </div>
</header>

<!-- FLASH MESSAGES -->
<?php if (session()->getFlashdata('success')): ?>
<div class="fixed top-20 inset-x-4 z-40 max-w-md mx-auto" x-data="{show:true}" x-show="show" x-transition x-init="setTimeout(()=>show=false,4000)">
  <div class="flex items-center gap-3 rounded-2xl bg-green-600 px-5 py-4 text-white shadow-xl">
    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
    <p class="font-medium text-sm"><?= esc(session()->getFlashdata('success')) ?></p>
    <button @click="show=false" class="ml-auto opacity-70 hover:opacity-100" aria-label="Dismiss">✕</button>
  </div>
</div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
<div class="fixed top-20 inset-x-4 z-40 max-w-md mx-auto" x-data="{show:true}" x-show="show" x-transition x-init="setTimeout(()=>show=false,5000)">
  <div class="flex items-center gap-3 rounded-2xl bg-red-500 px-5 py-4 text-white shadow-xl">
    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
    <p class="font-medium text-sm"><?= esc(session()->getFlashdata('error')) ?></p>
    <button @click="show=false" class="ml-auto opacity-70 hover:opacity-100" aria-label="Dismiss">✕</button>
  </div>
</div>
<?php endif; ?>

<!-- PAGE CONTENT -->
<main id="main-content" style="padding-top:56px">
  <?= $this->renderSection('content') ?>
</main>

<!-- BOTTOM MOBILE NAV -->
<nav class="fixed bottom-0 left-0 right-0 z-40 md:hidden shadow-2xl" style="background:rgba(12,26,29,.98);border-top:1px solid rgba(16,185,129,.18);box-shadow:0 -4px 20px -8px rgba(0,0,0,.4)" aria-label="Mobile bottom navigation">
  <div class="grid grid-cols-5 h-[58px]">
    <a href="<?= base_url() ?>" class="flex flex-col items-center justify-center gap-0.5 text-emerald-700 transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
      <span class="text-[9px] font-semibold">Home</span>
    </a>
    <a href="<?= base_url('vehicles') ?>" class="flex flex-col items-center justify-center gap-0.5 text-slate-400 hover:text-emerald-700 transition-colors">
      <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M13 2L4.09 12.97H11L10 22L20.91 11.03H14L13 2Z"/></svg>
      <span class="text-[9px] font-semibold">EVs</span>
    </a>
    <a href="<?= base_url('compare') ?>" class="flex flex-col items-center justify-center gap-0.5 text-slate-400 hover:text-emerald-700 transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
      <span class="text-[9px] font-semibold">Compare</span>
    </a>
    <a href="<?= base_url('find-my-ev') ?>" class="flex flex-col items-center justify-center gap-0.5 text-slate-400 hover:text-emerald-700 transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
      <span class="text-[9px] font-semibold">Find EV</span>
    </a>
    <a href="<?= base_url('ev-dealers') ?>" class="flex flex-col items-center justify-center gap-0.5 text-slate-400 hover:text-emerald-700 transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
      <span class="text-[9px] font-semibold">Dealers</span>
    </a>
  </div>
</nav>

<!-- FOOTER -->
<footer class="mt-16 sm:mt-24 bg-slate-900 text-slate-400 pb-20 md:pb-0" role="contentinfo">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 py-16">
    <div class="grid gap-8 md:gap-12 md:grid-cols-2 lg:grid-cols-5">

      <!-- Brand -->
      <div class="lg:col-span-2">
        <a href="<?= base_url() ?>" class="flex items-center gap-2.5 mb-4">
          <svg viewBox="0 0 36 36" class="h-9 w-9 flex-shrink-0" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="18" cy="18" r="17" fill="#0f2942" stroke="rgba(255,255,255,0.1)" stroke-width="1"/>
            <path d="M26 10.5A10 10 0 1 0 26 25.5" stroke="white" stroke-width="3.5" stroke-linecap="round" fill="none"/>
            <path d="M19.5 9L13 19h5l-1.5 8L24.5 17H20L19.5 9Z" fill="#22c55e"/>
          </svg>
          <span class="font-black text-xl text-white tracking-tight leading-none">charj<span class="text-green-500">.in</span></span>
        </a>
        <p class="text-sm leading-relaxed text-slate-400 max-w-xs">India's dedicated EV marketplace. Discover, compare and choose the right electric vehicle. One platform. Every EV brand.</p>
        <div class="mt-6 flex gap-3">
          <a href="#" aria-label="Charj.in on Instagram" class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-800 text-slate-400 hover:bg-green-600 hover:text-white transition-colors">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
          </a>
          <a href="#" aria-label="Charj.in on YouTube" class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-800 text-slate-400 hover:bg-green-600 hover:text-white transition-colors">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
          </a>
          <a href="#" aria-label="Charj.in on X / Twitter" class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-800 text-slate-400 hover:bg-green-600 hover:text-white transition-colors">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
          </a>
          <a href="#" aria-label="Charj.in on LinkedIn" class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-800 text-slate-400 hover:bg-green-600 hover:text-white transition-colors">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
          </a>
        </div>
        <!-- Trust badges -->
        <div class="mt-5 flex flex-wrap gap-2">
          <span class="flex items-center gap-1.5 bg-white/5 border border-white/10 rounded-full px-3 py-1.5 text-xs text-slate-400 font-medium">
            <svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            100% Free
          </span>
          <span class="flex items-center gap-1.5 bg-white/5 border border-white/10 rounded-full px-3 py-1.5 text-xs text-slate-400 font-medium">
            <svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            FAME II Data
          </span>
          <span class="flex items-center gap-1.5 bg-white/5 border border-white/10 rounded-full px-3 py-1.5 text-xs text-slate-400 font-medium">
            <svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            All Major Brands
          </span>
        </div>
      </div>

      <!-- Explore EVs -->
      <div>
        <h3 class="mb-4 font-bold text-white text-sm uppercase tracking-widest">Explore EVs</h3>
        <ul class="space-y-2.5 text-sm">
          <?php foreach ([
            ['Electric Scooters','electric-scooters'],
            ['Electric Cars','electric-cars'],
            ['Electric Bikes','electric-bikes'],
            ['E-Rickshaws','e-rickshaws'],
            ['Commercial EV','commercial-ev'],
            ['All EVs','vehicles'],
            ['Compare EVs','compare'],
          ] as [$label,$path]): ?>
          <li><a href="<?= base_url($path) ?>" class="hover:text-green-400 transition-colors"><?= $label ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Tools & Calculators -->
      <div>
        <h3 class="mb-4 font-bold text-white text-sm uppercase tracking-widest">Tools & Calculators</h3>
        <ul class="space-y-2.5 text-sm">
          <?php foreach ([
            ['EV Finder Quiz','find-my-ev'],
            ['Subsidy Calculator','subsidy-calculator'],
            ['Savings Calculator','tco-calculator'],
            ['Charging Cost','charging-cost'],
            ['Trip Range Check','can-i-make-it'],
            ['Resale Estimator','resale-estimator'],
            ['Fleet Calculator','fleet-calculator'],
            ['EMI Calculator','ev-emi-calculator'],
          ] as [$label,$path]): ?>
          <li><a href="<?= base_url($path) ?>" class="hover:text-green-400 transition-colors"><?= $label ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Company -->
      <div>
        <h3 class="mb-4 font-bold text-white text-sm uppercase tracking-widest">Company</h3>
        <ul class="space-y-2.5 text-sm">
          <?php foreach ([
            ['Home Charger Guide','home-charger-guide'],
            ['EV Glossary','ev-glossary'],
            ['Charging Stations','charging-stations'],
            ['News & Reviews','blog'],
            ['Used EVs','used-ev'],
            ['For Dealers','for-dealers'],
            ['For Brands','for-brands'],
            ['About Us','about'],
            ['Contact','contact'],
          ] as [$label,$path]): ?>
          <li><a href="<?= base_url($path) ?>" class="hover:text-green-400 transition-colors"><?= $label ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

    <!-- Bottom bar -->
    <div class="mt-12 border-t border-slate-800 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
      <p>&copy; <?= date('Y') ?> Charj.in — India's EV Decision Engine. Made with ⚡ in India.</p>
      <nav class="flex gap-6" aria-label="Legal links">
        <a href="<?= base_url('privacy-policy') ?>" class="hover:text-slate-300 transition-colors">Privacy Policy</a>
        <a href="<?= base_url('terms-of-service') ?>" class="hover:text-slate-300 transition-colors">Terms of Use</a>
        <a href="<?= base_url('disclaimer') ?>" class="hover:text-slate-300 transition-colors">Disclaimer</a>
        <a href="<?= base_url('sitemap.xml') ?>" class="hover:text-slate-300 transition-colors">Sitemap</a>
      </nav>
    </div>
    <p class="mt-4 text-xs text-slate-600 leading-relaxed max-w-4xl">
      <strong class="text-slate-500">Prices are indicative.</strong> Vehicle prices, range figures, specifications and availability are indicative and subject to change without notice. Always verify details with the official dealer or manufacturer before making a purchase decision. Charj.in is an independent information platform and is not affiliated with any EV brand or dealer unless explicitly stated.
    </p>
  </div>
</footer>

<script>
// Reveal on scroll (for elements with class "reveal")
const _revealObserver = new IntersectionObserver((entries) => {
  entries.forEach(e => { if(e.isIntersecting){ e.target.classList.add('visible'); _revealObserver.unobserve(e.target); } });
}, {threshold:0.1, rootMargin:'0px 0px -40px 0px'});
document.querySelectorAll('.reveal').forEach(el => _revealObserver.observe(el));
</script>

<?= $this->renderSection('scripts') ?>

<!-- Global scroll-reveal -->
<script>
(function(){
  var io = new IntersectionObserver(function(entries){
    entries.forEach(function(e){
      if(e.isIntersecting){ e.target.classList.add('sr-visible'); io.unobserve(e.target); }
    });
  },{threshold:.12});
  function observe(){
    document.querySelectorAll('.sr,.sr-left,.sr-right,.sr-scale,.sr-stagger').forEach(function(el){
      io.observe(el);
    });
  }
  if(document.readyState==='loading'){ document.addEventListener('DOMContentLoaded',observe); }
  else { observe(); }
})();
</script>
</body>
</html>
