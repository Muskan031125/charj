<?php
/**
 * Brands Index â€” All EV brands listing
 * Variables: $brands (array), $meta_title, $meta_description
 */
$meta_title       = $meta_title       ?? 'Electric Vehicle Brands in India â€” Charj.in';
$meta_description = $meta_description ?? 'Explore all electric vehicle brands available in India. Find EVs by manufacturer â€” cars, scooters, bikes and more.';
?>
<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<!-- Page Hero -->
<section class="bg-[#0D2137] py-12 md:py-16">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
    <h1 class="text-3xl font-extrabold text-white md:text-4xl lg:text-5xl">
      Electric Vehicle Brands in India
    </h1>
    <p class="mt-4 text-lg text-slate-300 max-w-2xl mx-auto">
      India's EV market is growing fast, with both homegrown innovators and global giants bringing cutting-edge electric vehicles to Indian roads. Explore all major EV brands, their lineups, and find the perfect electric vehicle for you.
    </p>
    <div class="mt-6 flex flex-wrap justify-center gap-4 text-sm text-slate-400">
      <span class="flex items-center gap-1.5">
        <svg class="h-4 w-4 text-[#22C55E]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <?= count($brands ?? []) ?>+ Brands
      </span>
      <span class="flex items-center gap-1.5">
        <svg class="h-4 w-4 text-[#22C55E]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        Indian &amp; International
      </span>
      <span class="flex items-center gap-1.5">
        <svg class="h-4 w-4 text-[#22C55E]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        Cars, Scooters &amp; Bikes
      </span>
    </div>
  </div>
</section>

<!-- Brands Grid -->
<section class="py-12 md:py-16 bg-slate-50">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

    <?php if (empty($brands)): ?>
      <div class="text-center py-20">
        <svg class="mx-auto h-16 w-16 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
        <p class="mt-4 text-slate-500 text-lg">No brands found yet. Check back soon.</p>
      </div>
    <?php else: ?>

      <div class="grid grid-cols-2 gap-4 sm:gap-6 md:grid-cols-3 lg:grid-cols-4">
        <?php foreach ($brands as $brand):
          if (($brand['status'] ?? '') !== 'published') continue;
          $initial     = strtoupper(substr($brand['name'] ?? 'B', 0, 1));
          $slug        = $brand['slug'] ?? '';
          $name        = $brand['name'] ?? '';
          $country     = $brand['country_of_origin'] ?? 'India';
          $description = $brand['description'] ?? '';
          $excerpt     = mb_strlen($description) > 90 ? mb_substr($description, 0, 90) . 'â€¦' : $description;
          $count       = (int) ($brand['vehicle_count'] ?? 0);
        ?>
        <article class="group relative flex flex-col rounded-2xl bg-white shadow-sm border border-slate-100 hover:shadow-md hover:border-[#22C55E]/40 transition-all duration-200 overflow-hidden">
          <!-- Logo area -->
          <div class="flex items-center justify-center bg-gradient-to-br from-[#0D2137] to-[#1a3a5c] h-28 sm:h-32">
            <?php if (!empty($brand['logo_url'])): ?>
              <img src="<?= esc($brand['logo_url']) ?>" alt="<?= esc($name) ?> logo"
                   class="h-16 w-auto object-contain drop-shadow-md" loading="lazy">
            <?php else: ?>
              <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[#22C55E] shadow-lg">
                <span class="text-3xl font-black text-white"><?= esc($initial) ?></span>
              </div>
            <?php endif; ?>
          </div>

          <!-- Content -->
          <div class="flex flex-1 flex-col p-4 sm:p-5">
            <div class="flex items-start justify-between gap-2">
              <h2 class="text-base font-bold text-[#0D2137] group-hover:text-[#22C55E] transition-colors leading-tight">
                <?= esc($name) ?>
              </h2>
              <?php if ($count > 0): ?>
                <span class="shrink-0 inline-flex items-center rounded-full bg-[#22C55E]/10 px-2 py-0.5 text-xs font-semibold text-[#22C55E] ring-1 ring-[#22C55E]/20">
                  <?= $count ?> EV<?= $count !== 1 ? 's' : '' ?>
                </span>
              <?php endif; ?>
            </div>

            <p class="mt-1 text-xs font-medium text-slate-400 uppercase tracking-wide"><?= esc($country) ?></p>

            <?php if ($excerpt): ?>
              <p class="mt-2 text-sm text-slate-600 leading-relaxed flex-1"><?= esc($excerpt) ?></p>
            <?php else: ?>
              <div class="flex-1"></div>
            <?php endif; ?>

            <a href="/brands/<?= esc($slug) ?>"
               class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-[#22C55E] hover:text-[#16a34a] transition-colors">
              View EVs
              <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
              </svg>
            </a>
          </div>

          <!-- Full card link overlay -->
          <a href="/brands/<?= esc($slug) ?>" class="absolute inset-0" aria-label="View <?= esc($name) ?> EVs"></a>
        </article>
        <?php endforeach; ?>
      </div>

    <?php endif; ?>
  </div>
</section>

<!-- Bottom CTA -->
<section class="bg-[#0D2137] py-12">
  <div class="mx-auto max-w-3xl px-4 text-center">
    <h2 class="text-2xl font-bold text-white">Can't decide which brand to choose?</h2>
    <p class="mt-3 text-slate-300">Use our free EV recommendation tool â€” answer a few questions and get a personalised EV shortlist.</p>
    <a href="/recommendation"
       class="mt-6 inline-flex items-center gap-2 rounded-xl bg-[#22C55E] px-8 py-3.5 text-base font-bold text-white shadow-lg hover:bg-[#16a34a] transition-colors">
      Get My EV Recommendation
      <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
      </svg>
    </a>
  </div>
</section>

<?= $this->endSection() ?>
