<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<!-- Hero -->
<div style="background:linear-gradient(135deg,#0a1628 0%,#1a0a28 50%,#0a1628 100%)" class="relative overflow-hidden pt-28 pb-10 px-4">
  <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image:radial-gradient(rgba(255,255,255,.5) 1px,transparent 1px);background-size:24px 24px"></div>
  <div class="absolute top-0 left-1/3 w-80 h-40 bg-purple-500 opacity-5 blur-3xl rounded-full pointer-events-none"></div>
  <div class="absolute bottom-0 right-1/4 w-64 h-32 bg-teal-400 opacity-5 blur-3xl rounded-full pointer-events-none"></div>
  <div class="relative max-w-7xl mx-auto">
    <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-3 py-1 text-white/70 text-xs font-bold uppercase tracking-widest mb-4">
      📰 EV News & Insights
    </div>
    <h1 class="text-3xl md:text-4xl font-black text-white leading-tight">EV News &amp; Updates</h1>
    <p class="mt-3 max-w-2xl text-base text-slate-400">
      Latest electric vehicle launches, reviews, government policies, subsidies and industry news from India.
    </p>
  </div>
</div>

<!-- Articles grid -->
<div class="mx-auto max-w-7xl px-4 py-10 pb-24">

    <?php if (!empty($articles)): ?>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 sr-stagger">
        <?php foreach ($articles as $article): ?>
        <?php
            // Pick a gradient per category for placeholder image
            $gradients = [
                'news'       => 'from-blue-500 to-indigo-600',
                'review'     => 'from-emerald-500 to-teal-600',
                'policy'     => 'from-amber-500 to-orange-600',
                'comparison' => 'from-purple-500 to-pink-600',
                'ev-guide'   => 'from-slate-600 to-slate-800',
                'technology' => 'from-cyan-500 to-blue-600',
            ];
            $catSlug = strtolower(str_replace(' ', '-', $article['category'] ?? 'news'));
            $gradient = $gradients[$catSlug] ?? 'from-emerald-500 to-slate-700';
            $date = !empty($article['published_at'])
                ? date('d M Y', strtotime($article['published_at']))
                : date('d M Y', strtotime($article['created_at']));
        ?>
        <article class="flex flex-col overflow-hidden rounded-2xl transition hover:-translate-y-1 duration-300 card-hover" style="background:#152b30;border:1px solid rgba(255,255,255,.07)">

            <!-- Image placeholder -->
            <a href="/news/<?= esc($article['slug']) ?>" class="block">
                <?php if (!empty($article['featured_image'])): ?>
                    <img src="<?= esc($article['featured_image']) ?>"
                         alt="<?= esc($article['title']) ?>"
                         class="h-44 w-full object-cover">
                <?php else: ?>
                    <div class="flex h-44 w-full items-center justify-center bg-gradient-to-br <?= $gradient ?>">
                        <svg class="h-10 w-10 text-white/60" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                <?php endif; ?>
            </a>

            <div class="flex flex-1 flex-col p-5">
                <!-- Category badge -->
                <?php if (!empty($article['category'])): ?>
                <span class="mb-2 inline-block self-start rounded-full px-2.5 py-0.5 text-xs font-bold uppercase tracking-wide" style="background:rgba(0,168,150,.12);color:#1AFFCC;border:1px solid rgba(0,168,150,.3)">
                    <?= esc($article['category']) ?>
                </span>
                <?php endif; ?>

                <!-- Title -->
                <h2 class="text-base font-bold leading-snug text-slate-100" style="color:#e6f1f1">
                    <a href="/news/<?= esc($article['slug']) ?>" class="transition-colors hover:text-[#1AFFCC]">
                        <?= esc($article['title']) ?>
                    </a>
                </h2>

                <!-- Excerpt (2-line clamp) -->
                <?php if (!empty($article['excerpt'])): ?>
                <p class="mt-2 flex-1 text-sm leading-relaxed"
                   style="color:#8ba3a3;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                    <?= esc($article['excerpt']) ?>
                </p>
                <?php endif; ?>

                <!-- Spacer -->
                <div class="flex-1"></div>

                <!-- Meta -->
                <div class="mt-4 flex items-center justify-between pt-3 text-xs" style="border-top:1px solid rgba(255,255,255,.07);color:#8ba3a3">
                    <div class="flex items-center gap-1.5">
                        <div class="flex h-6 w-6 items-center justify-center rounded-full text-xs font-bold" style="background:rgba(255,255,255,.06);color:#8ba3a3">
                            <?= mb_strtoupper(mb_substr($article['author_name'] ?? 'C', 0, 1)) ?>
                        </div>
                        <span><?= esc($article['author_name'] ?? 'Charj.in Team') ?></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span><?= $date ?></span>
                        <?php if (!empty($article['view_count'])): ?>
                        <span class="flex items-center gap-1">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <?= number_format($article['view_count']) ?>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </article>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if (!empty($pager)): ?>
    <div class="mt-10 flex justify-center">
        <?= $pager->links() ?>
    </div>
    <?php endif; ?>

    <?php else: ?>
    <div class="py-20 text-center">
        <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
        </svg>
        <p class="mt-4 text-slate-400">No articles yet. Check back soon!</p>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
