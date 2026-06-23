<?php
/**
 * Brand Show ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â Single brand detail page
 * Variables: $brand (array), $vehicles (array), $pager (object), $meta_title, $meta_description
 */
$brandName  = $brand['name']              ?? 'Brand';
$brandSlug  = $brand['slug']              ?? '';
$country    = $brand['country_of_origin'] ?? 'India';
$desc       = $brand['description']       ?? '';
$website    = $brand['website_url']       ?? '';
$totalCount = (int) ($brand['vehicle_count'] ?? count($vehicles ?? []));

$meta_title       = $meta_title       ?? $brandName . ' Electric Vehicles in India ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â Charj.in';
$meta_description = $meta_description ?? 'Explore all ' . $brandName . ' electric vehicles available in India. Compare prices, range and features on Charj.in.';
?>
<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<!-- Breadcrumb -->
<nav class="bg-white border-b border-slate-100" aria-label="Breadcrumb">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-3">
    <ol class="flex items-center gap-2 text-sm text-slate-500">
      <li><a href="/" class="hover:text-[#22C55E] transition-colors">Home</a></li>
      <li class="flex items-center gap-2">
        <svg class="h-4 w-4 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        <a href="/brands" class="hover:text-[#22C55E] transition-colors">Brands</a>
      </li>
      <li class="flex items-center gap-2">
        <svg class="h-4 w-4 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        <span class="font-medium text-[#0D2137]"><?= esc($brandName) ?></span>
      </li>
    </ol>
  </div>
</nav>

<!-- Brand Header -->
<section class="bg-[#0D2137] py-12 md:py-16">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col items-center gap-8 md:flex-row md:items-start">

      <!-- Logo -->
      <div class="shrink-0 flex h-28 w-28 md:h-36 md:w-36 items-center justify-center rounded-3xl bg-white/10 ring-1 ring-white/20 shadow-xl">
        <?php if (!empty($brand['logo_url'])): ?>
          <img src="<?= esc($brand['logo_url']) ?>" alt="<?= esc($brandName) ?> logo"
               class="h-20 w-20 md:h-24 md:w-24 object-contain" loading="eager">
        <?php else: ?>
          <span class="text-5xl font-black text-[#22C55E]"><?= esc(strtoupper(substr($brandName, 0, 1))) ?></span>
        <?php endif; ?>
      </div>

      <!-- Info -->
      <div class="flex-1 text-center md:text-left">
        <div class="flex flex-wrap items-center justify-center gap-3 md:justify-start">
          <h1 class="text-3xl font-extrabold text-white md:text-4xl"><?= esc($brandName) ?></h1>
          <span class="inline-flex items-center rounded-full bg-[#22C55E]/20 px-3 py-1 text-sm font-semibold text-[#22C55E] ring-1 ring-[#22C55E]/30">
            <?= esc($country) ?>
          </span>
        </div>

        <?php if ($desc): ?>
          <p class="mt-4 text-slate-300 leading-relaxed max-w-2xl text-base md:text-lg"><?= esc($desc) ?></p>
        <?php endif; ?>

        <div class="mt-5 flex flex-wrap items-center justify-center gap-4 md:justify-start">
          <div class="flex items-center gap-2 text-slate-300 text-sm">
            <svg class="h-5 w-5 text-[#22C55E]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            <span><strong class="text-white"><?= $totalCount ?></strong> Electric Vehicle<?= $totalCount !== 1 ? 's' : '' ?></span>
          </div>
          <?php if ($website): ?>
            <a href="<?= esc($website) ?>" target="_blank" rel="noopener noreferrer"
               class="flex items-center gap-1.5 text-sm text-[#22C55E] hover:text-white transition-colors">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
              </svg>
              Official Website
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Main Content -->
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10 md:py-14">
  <div class="flex flex-col gap-10 lg:flex-row lg:gap-12">

    <!-- Vehicles Section -->
    <div class="flex-1 min-w-0">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-[#0D2137] md:text-2xl">
          <?= $totalCount ?> Electric Vehicle<?= $totalCount !== 1 ? 's' : '' ?> by <?= esc($brandName) ?>
        </h2>
      </div>

      <?php if (empty($vehicles)): ?>
        <div class="rounded-2xl bg-slate-50 border border-slate-200 py-16 text-center">
          <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
          </svg>
          <p class="mt-4 text-slate-500">No vehicles listed yet for <?= esc($brandName) ?>. Check back soon.</p>
        </div>
      <?php else: ?>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
          <?php foreach ($vehicles as $vehicle): ?>
            <?= view('partials/vehicle_card', ['vehicle' => $vehicle]) ?>
          <?php endforeach; ?>
        </div>

        <!-- Pager -->
        <?php if (!empty($pager)): ?>
          <div class="mt-10 flex justify-center">
            <?= $pager->links() ?>
          </div>
        <?php endif; ?>
      <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <aside class="w-full lg:w-80 lg:shrink-0">
      <div class="sticky top-24 space-y-6">

        <!-- Lead Form Card -->
        <div class="rounded-2xl bg-white shadow-md border border-slate-100 overflow-hidden">
          <div class="bg-[#0D2137] px-5 py-4">
            <h3 class="text-base font-bold text-white">Interested in <?= esc($brandName) ?> EVs?</h3>
            <p class="mt-1 text-sm text-slate-300">Get price, dealer info and expert guidance ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â free.</p>
          </div>
          <div class="p-5">
            <form action="/leads/store" method="post" class="space-y-4">
              <?= csrf_field() ?>
              <input type="hidden" name="source" value="brand_page">
              <input type="hidden" name="brand_name" value="<?= esc($brandName) ?>">

              <div>
                <label for="sidebar_name" class="block text-sm font-medium text-slate-700 mb-1">Your Name *</label>
                <input type="text" id="sidebar_name" name="name" required placeholder="e.g. Rahul Sharma"
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-[#22C55E] focus:outline-none focus:ring-2 focus:ring-[#22C55E]/20">
              </div>

              <div>
                <label for="sidebar_phone" class="block text-sm font-medium text-slate-700 mb-1">Phone Number *</label>
                <div class="flex">
                  <span class="inline-flex items-center rounded-l-xl border border-r-0 border-slate-200 bg-slate-50 px-3 text-sm text-slate-500">+91</span>
                  <input type="tel" id="sidebar_phone" name="phone" required placeholder="98765 43210"
                         maxlength="10" pattern="[6-9][0-9]{9}"
                         class="flex-1 rounded-r-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-[#22C55E] focus:outline-none focus:ring-2 focus:ring-[#22C55E]/20">
                </div>
              </div>

              <div>
                <label for="sidebar_city" class="block text-sm font-medium text-slate-700 mb-1">Your City</label>
                <input type="text" id="sidebar_city" name="city" placeholder="e.g. Mumbai"
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-[#22C55E] focus:outline-none focus:ring-2 focus:ring-[#22C55E]/20">
              </div>

              <div>
                <label for="sidebar_interest" class="block text-sm font-medium text-slate-700 mb-1">I'm interested in</label>
                <select id="sidebar_interest" name="interest"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 focus:border-[#22C55E] focus:outline-none focus:ring-2 focus:ring-[#22C55E]/20">
                  <option value="">Select vehicle type</option>
                  <option value="car">Electric Car</option>
                  <option value="scooter">Electric Scooter</option>
                  <option value="bike">Electric Bike</option>
                  <option value="any">Any / Not sure yet</option>
                </select>
              </div>

              <button type="submit"
                      class="w-full rounded-xl bg-[#22C55E] px-5 py-3 text-sm font-bold text-white shadow hover:bg-[#16a34a] transition-colors">
                Get Free Dealer Info
              </button>
              <p class="text-center text-xs text-slate-400">No spam. 100% free. Your data is safe.</p>
            </form>
          </div>
        </div>

        <!-- Quick links -->
        <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
          <h4 class="text-sm font-bold text-[#0D2137] mb-3">Quick Links</h4>
          <ul class="space-y-2">
            <li>
              <a href="/vehicles?brand=<?= esc($brandSlug) ?>" class="flex items-center gap-2 text-sm text-slate-600 hover:text-[#22C55E] transition-colors">
                <svg class="h-4 w-4 text-[#22C55E]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                All <?= esc($brandName) ?> EVs
              </a>
            </li>
            <li>
              <a href="/compare?brand=<?= esc($brandSlug) ?>" class="flex items-center gap-2 text-sm text-slate-600 hover:text-[#22C55E] transition-colors">
                <svg class="h-4 w-4 text-[#22C55E]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                Compare with other brands
              </a>
            </li>
            <li>
              <a href="/dealers?brand=<?= esc($brandSlug) ?>" class="flex items-center gap-2 text-sm text-slate-600 hover:text-[#22C55E] transition-colors">
                <svg class="h-4 w-4 text-[#22C55E]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                Find <?= esc($brandName) ?> Dealers
              </a>
            </li>
          </ul>
        </div>

      </div>
    </aside>

  </div>
</div>

<!-- Brand CTA Banner -->
<section class="bg-gradient-to-r from-[#0D2137] to-[#1a3a5c] py-12">
  <div class="mx-auto max-w-4xl px-4 text-center">
    <h2 class="text-2xl font-bold text-white md:text-3xl">
      Interested in <?= esc($brandName) ?> EVs?
    </h2>
    <p class="mt-3 text-slate-300 text-base md:text-lg">
      Get latest price, nearest dealer contacts, and expert buying guidance ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â completely free.
    </p>
    <div class="mt-6 flex flex-wrap justify-center gap-4">
      <a href="#sidebar_name"
         class="inline-flex items-center gap-2 rounded-xl bg-[#22C55E] px-7 py-3.5 text-base font-bold text-white shadow-lg hover:bg-[#16a34a] transition-colors">
        Get Price &amp; Dealer Info
        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
        </svg>
      </a>
      <a href="/compare"
         class="inline-flex items-center gap-2 rounded-xl bg-white/10 px-7 py-3.5 text-base font-semibold text-white ring-1 ring-white/20 hover:bg-white/20 transition-colors">
        Compare EVs
      </a>
    </div>
  </div>
</section>

<?= $this->endSection() ?>
