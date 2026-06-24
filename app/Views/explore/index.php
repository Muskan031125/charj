<?= $this->extend('layouts/public') ?>

<?= $this->section('head') ?>
<title><?= esc($meta_title ?? 'Explore EVs by Brand — Charj.in') ?></title>
<meta name="description" content="Browse every EV brand in India. Find electric scooters, bikes, cars and commercial vehicles from Ola, Ather, Tata, TVS, MG and more.">
<style>
  .brand-card-fallback { display: none; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero -->
<div class="relative overflow-hidden pt-28 pb-14 px-4 text-center anim-grad"
     style="background:linear-gradient(135deg,#030712,#04302e,#0a2e2c,#030712)">
  <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image:radial-gradient(rgba(255,255,255,.4) 1px,transparent 1px);background-size:20px 20px"></div>
  <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[500px] h-48 rounded-full pointer-events-none" style="background:#00A896;opacity:.07;filter:blur(60px)"></div>
  <div class="absolute bottom-0 right-0 w-64 h-32 rounded-full pointer-events-none" style="background:#38bdf8;opacity:.05;filter:blur(50px)"></div>
  <div class="absolute top-8 left-16 w-2 h-2 rounded-full float-1 pointer-events-none" style="background:#00A896;opacity:.35"></div>
  <div class="absolute top-16 right-24 w-1.5 h-1.5 rounded-full float-2 pointer-events-none" style="background:#38bdf8;opacity:.25"></div>
  <div class="absolute bottom-8 left-1/3 w-1 h-1 bg-white rounded-full float-3 pointer-events-none" style="opacity:.2"></div>

  <div class="relative">
    <div class="inline-flex items-center gap-2 glass rounded-full px-4 py-1.5 text-xs font-bold uppercase tracking-widest mb-4 text-[#1AFFCC]">
      🏭 <?= count($brands ?? []) ?>+ Brands · India's Biggest EV Database
    </div>
    <h1 class="text-4xl sm:text-5xl font-black text-white tracking-tight leading-tight">
      Explore by <span class="neon-green">Brand</span>
    </h1>
    <p class="mt-3 text-lg" style="color:#8ba3a3">Find. Compare. Choose your electric future.</p>
  </div>
</div>

<!-- Main content -->
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-10"
     style="background:#0c1a1d"
     x-data="{ filter: 'all', search: '' }">

  <!-- Search + filter row -->
  <div class="flex flex-col sm:flex-row gap-3 mb-8">
    <div class="relative flex-1">
      <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4" style="color:#8ba3a3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/></svg>
      <input x-model="search" type="text" placeholder="Search brands..."
             class="w-full pl-10 pr-4 py-2.5 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500"
             style="background:#152b30;border:1px solid rgba(255,255,255,.07);color:#e6f1f1">
    </div>
    <div class="flex gap-2 overflow-x-auto scrollbar-hide pb-0.5">
      <?php foreach ([['all','All'],['2-wheeler','2-Wheelers'],['3-wheeler','3-Wheelers'],['4-wheeler','4-Wheelers'],['commercial','Commercial']] as [$key,$label]): ?>
      <button @click="filter = '<?= $key ?>'"
              :class="filter === '<?= $key ?>' ? '' : ''"
              :style="filter === '<?= $key ?>' ? 'background:#00A896;color:#fff;border:1px solid #00A896' : 'background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);color:<?= $key === 'all' ? '#1AFFCC' : '#8ba3a3' ?>'"
              class="flex-shrink-0 rounded-full px-4 py-2 text-xs font-semibold transition-all">
        <?= esc($label) ?>
      </button>
      <?php endforeach; ?>
    </div>
  </div>

  <?php if (empty($brands)): ?>
  <div class="flex flex-col items-center justify-center py-24 text-center">
    <div class="w-20 h-20 rounded-full flex items-center justify-center mb-4" style="background:rgba(0,168,150,.12);color:#1AFFCC;border:1px solid rgba(0,168,150,.3)">
      <svg class="w-10 h-10" style="color:#1AFFCC" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
      </svg>
    </div>
    <h2 class="text-xl font-bold mb-2" style="color:#e6f1f1">No brands yet</h2>
    <p class="text-sm max-w-xs" style="color:#8ba3a3">Brand listings will appear here once they're added.</p>
  </div>

  <?php else: ?>

  <!-- Brand grid -->
  <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 sr-stagger">
    <?php foreach ($brands as $brand):
      $slug       = esc($brand['slug'] ?? strtolower(str_replace(' ', '-', $brand['name'])));
      $name       = esc($brand['name']);
      $evCount    = (int)($brand['ev_count'] ?? 0);
      $firstLetter = strtoupper(substr($brand['name'], 0, 1));
    ?>
    <a href="<?= base_url('brands/' . $slug) ?>"
       data-name="<?= strtolower($brand['name']) ?>"
       x-show="search === '' || '<?= addslashes(strtolower($brand['name'])) ?>'.includes(search.toLowerCase())"
       class="group relative flex flex-col items-center text-center p-5 rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-2"
       style="background:#152b30;border:1px solid rgba(255,255,255,.07)"
       onmouseenter="this.style.borderColor='rgba(26,255,204,.35)';this.style.boxShadow='0 20px 40px -12px rgba(0,0,0,.5),0 0 24px -4px rgba(0,168,150,.25)'"
       onmouseleave="this.style.borderColor='rgba(255,255,255,.07)';this.style.boxShadow=''">

      <!-- Hover bg sweep -->
      <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"
           style="background:radial-gradient(ellipse at 50% 0%,rgba(34,197,94,.06),transparent 70%)"></div>

      <!-- Logo / fallback -->
      <div class="relative w-20 h-16 flex items-center justify-center mb-3">
        <img src="<?= base_url('assets/images/brands/' . $slug . '.png') ?>"
             alt="<?= $name ?> logo"
             class="max-w-full max-h-full object-contain transition-transform duration-300 group-hover:scale-110"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
        <div class="brand-card-fallback w-14 h-14 rounded-2xl items-center justify-center text-2xl font-black"
             style="background:rgba(0,168,150,.12);color:#1AFFCC;border:1px solid rgba(0,168,150,.3)">
          <?= $firstLetter ?>
        </div>
      </div>

      <span class="relative font-bold text-sm transition-colors duration-200" style="color:#e6f1f1"><?= $name ?></span>

      <?php if ($evCount > 0): ?>
      <span class="relative mt-1.5 text-[11px] font-bold px-2.5 py-0.5 rounded-full" style="background:rgba(0,168,150,.12);color:#1AFFCC;border:1px solid rgba(0,168,150,.3)">
        ⚡ <?= $evCount ?> <?= $evCount === 1 ? 'EV' : 'EVs' ?>
      </span>
      <?php endif; ?>

      <div class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 transition-all duration-200 translate-x-1 group-hover:translate-x-0">
        <svg class="w-4 h-4" style="color:#1AFFCC" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
      </div>
    </a>
    <?php endforeach; ?>
  </div>

  <p x-show="search !== ''"
     class="text-center text-sm mt-10" style="color:#8ba3a3">
    No brands match "<span x-text="search" style="color:#1AFFCC"></span>".
    <button @click="search=''" class="font-semibold hover:underline" style="color:#1AFFCC">Clear</button>
  </p>

  <?php endif; ?>

</div>

<?= $this->endSection() ?>
