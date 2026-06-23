<?= $this->extend('layouts/public') ?>

<?= $this->section('head') ?>
<title>EV Charging Cost Calculator — Cost Per KM | Charj.in</title>
<meta name="description" content="Calculate your real EV running cost per km. Enter your electricity tariff and EV efficiency to see exact cost vs petrol.">
<style>
[x-cloak]{display:none!important}

@keyframes gradShift { 0%,100%{background-position:0% 50%} 50%{background-position:100% 50%} }
@keyframes pulse-ring { 0%{transform:scale(.95);box-shadow:0 0 0 0 rgba(34,197,94,.4)} 70%{transform:scale(1);box-shadow:0 0 0 16px rgba(34,197,94,0)} 100%{transform:scale(.95);box-shadow:0 0 0 0 rgba(34,197,94,0)} }
@keyframes countUp { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
@keyframes fadeInUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }

.hero-bg {
  background: linear-gradient(-45deg,#0f172a,#052e16,#0c1a2e,#14532d);
  background-size:400% 400%;
  animation: gradShift 10s ease infinite;
}
.cost-ring { animation: pulse-ring 2.5s ease-in-out infinite; }
.fadeInUp-1 { animation: fadeInUp .5s ease forwards; }
.fadeInUp-2 { animation: fadeInUp .5s .1s ease both; }
.fadeInUp-3 { animation: fadeInUp .5s .2s ease both; }
.fadeInUp-4 { animation: fadeInUp .5s .3s ease both; }

/* Custom range slider */
input[type=range] {
  -webkit-appearance:none;
  appearance:none;
  width:100%;
  height:6px;
  border-radius:9999px;
  outline:none;
  cursor:pointer;
}
input[type=range].green-track {
  background: linear-gradient(to right, #22c55e var(--val,50%), rgba(255,255,255,.15) var(--val,50%));
}
input[type=range]::-webkit-slider-thumb {
  -webkit-appearance:none;
  width:22px; height:22px;
  border-radius:50%;
  background:#22c55e;
  border:3px solid #fff;
  box-shadow:0 2px 8px rgba(0,0,0,.4);
  cursor:pointer;
  transition:transform .15s;
}
input[type=range]::-webkit-slider-thumb:hover { transform:scale(1.15); }
input[type=range]::-moz-range-thumb {
  width:22px; height:22px;
  border-radius:50%;
  background:#22c55e;
  border:3px solid #fff;
  box-shadow:0 2px 8px rgba(0,0,0,.4);
  cursor:pointer;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="hero-bg min-h-screen text-white"
     x-data="{
       rate: 8,
       eff: 5,
       km: 40,
       days: 26,
       petrolRate: 2.10,
       get costPerKm() { return this.rate / this.eff },
       get evMonth()   { return this.costPerKm * this.km * this.days },
       get petMonth()  { return this.petrolRate * this.km * this.days },
       get saved()     { return this.petMonth - this.evMonth },
       get annSaved()  { return this.saved * 12 },
       get breakEven() { return Math.round(80000 / (this.saved > 0 ? this.saved : 1)) },
       rs(n) { const s='₹'; if(n>=100000) return s+(n/100000).toFixed(1)+'L'; return s+Math.round(n).toLocaleString('en-IN'); },
       fmt2(n) { return '₹'+n.toFixed(2); },
       setPct(el, min, max) { const v=(el.value-min)/(max-min)*100; el.style.setProperty('--val',v+'%'); },
       preset(eff, name) { this.eff = eff; }
     }"
     x-init="$nextTick(() => { $el.querySelectorAll('input[type=range]').forEach(r => setPct(r,+r.min,+r.max)) })">

  <!-- Dots overlay -->
  <div class="fixed inset-0 pointer-events-none opacity-10"
       style="background-image:radial-gradient(rgba(255,255,255,.5) 1px,transparent 1px);background-size:28px 28px"></div>

  <div class="relative max-w-2xl mx-auto px-4 pt-28 pb-20">

    <!-- Hero text -->
    <div class="text-center mb-10 fadeInUp-1">
      <div class="inline-flex items-center gap-2 bg-green-500/20 border border-green-500/30 rounded-full px-4 py-1.5 text-green-300 text-xs font-bold uppercase tracking-widest mb-5">
        ⚡ Live Cost Calculator
      </div>
      <h1 class="text-4xl sm:text-5xl font-black leading-tight mb-3">
        What does <span style="background:linear-gradient(135deg,#4ade80,#2dd4bf);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">1 km</span> cost you?
      </h1>
      <p class="text-slate-400 text-base">Adjust sliders to see your real EV running cost vs petrol — live</p>
    </div>

    <!-- Big cost circle -->
    <div class="flex justify-center mb-10 fadeInUp-2">
      <div class="relative">
        <div class="w-44 h-44 rounded-full cost-ring flex flex-col items-center justify-center"
             style="background:radial-gradient(circle,rgba(34,197,94,.15),rgba(34,197,94,.05));border:3px solid rgba(34,197,94,.4)">
          <div class="text-xs text-green-400 font-bold uppercase tracking-widest mb-1">Cost per km</div>
          <div class="text-4xl font-black text-green-400" x-text="fmt2(costPerKm)"></div>
          <div class="text-xs text-green-600 mt-1">vs petrol ₹2.10/km</div>
        </div>
        <!-- Savings badge -->
        <div class="absolute -top-2 -right-2 bg-green-500 text-white text-xs font-black px-3 py-1.5 rounded-full shadow-lg"
             x-text="Math.round((1 - costPerKm/petrolRate)*100) + '% cheaper'"></div>
      </div>
    </div>

    <!-- Sliders card -->
    <div class="rounded-3xl p-6 sm:p-8 space-y-7 fadeInUp-3 mb-6"
         style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);backdrop-filter:blur(12px)">

      <!-- Rate slider -->
      <div>
        <div class="flex items-center justify-between mb-3">
          <label class="text-sm font-bold text-white/80">Your electricity rate</label>
          <div class="flex items-baseline gap-1">
            <span class="text-2xl font-black text-green-400" x-text="rate"></span>
            <span class="text-xs text-white/40 font-semibold">₹/kWh</span>
          </div>
        </div>
        <input type="range" min="4" max="20" step="0.5" x-model.number="rate"
               class="green-track w-full"
               @input="setPct($el, 4, 20)">
        <div class="flex justify-between text-xs text-white/30 mt-1.5 font-medium">
          <span>₹4 (cheap)</span><span>₹20 (commercial)</span>
        </div>
      </div>

      <!-- Efficiency slider -->
      <div>
        <div class="flex items-center justify-between mb-3">
          <label class="text-sm font-bold text-white/80">EV efficiency</label>
          <div class="flex items-baseline gap-1">
            <span class="text-2xl font-black text-green-400" x-text="eff"></span>
            <span class="text-xs text-white/40 font-semibold">km/kWh</span>
          </div>
        </div>
        <input type="range" min="2" max="8" step="0.5" x-model.number="eff"
               class="green-track w-full"
               @input="setPct($el, 2, 8)">
        <div class="flex justify-between text-xs text-white/30 mt-1.5 font-medium">
          <span>2 (heavy SUV)</span><span>8 (efficient scooter)</span>
        </div>
        <!-- Preset chips -->
        <div class="flex flex-wrap gap-2 mt-4">
          <?php
          $presets = [
            ['Ola S1 Pro',  5.2],
            ['Ather 450X',  4.5],
            ['Nexon EV',    6.0],
            ['TVS iQube',   4.8],
            ['Tata Tiago',  6.8],
            ['MG ZS EV',    5.5],
          ];
          foreach ($presets as $p): ?>
          <button @click="preset(<?= $p[1] ?>)"
                  :class="eff==<?= $p[1] ?> ? 'bg-green-500 text-white border-green-500' : 'bg-white/8 text-white/70 border-white/15 hover:border-green-400/60 hover:text-white'"
                  class="rounded-full border px-3 py-1.5 text-xs font-bold transition-all duration-150">
            <?= $p[0] ?> <span class="opacity-60">(<?= $p[1] ?>)</span>
          </button>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Daily km slider -->
      <div>
        <div class="flex items-center justify-between mb-3">
          <label class="text-sm font-bold text-white/80">Daily distance</label>
          <div class="flex items-baseline gap-1">
            <span class="text-2xl font-black text-green-400" x-text="km"></span>
            <span class="text-xs text-white/40 font-semibold">km/day</span>
          </div>
        </div>
        <input type="range" min="10" max="150" step="5" x-model.number="km"
               class="green-track w-full"
               @input="setPct($el, 10, 150)">
        <div class="flex justify-between text-xs text-white/30 mt-1.5 font-medium">
          <span>10 km</span><span>150 km</span>
        </div>
      </div>

    </div>

    <!-- Stats grid -->
    <div class="grid grid-cols-2 gap-3 mb-4 fadeInUp-4">
      <div class="rounded-2xl p-5 text-center"
           style="background:rgba(34,197,94,.12);border:1px solid rgba(34,197,94,.2)">
        <div class="text-xs text-green-400 font-bold uppercase tracking-wide mb-1">EV Monthly Cost</div>
        <div class="text-2xl font-black text-green-300" x-text="rs(evMonth)"></div>
        <div class="text-xs text-green-600 mt-1" x-text="km + ' km/day × ' + days + ' days'"></div>
      </div>
      <div class="rounded-2xl p-5 text-center"
           style="background:rgba(251,146,60,.1);border:1px solid rgba(251,146,60,.2)">
        <div class="text-xs text-orange-400 font-bold uppercase tracking-wide mb-1">Petrol Monthly Cost</div>
        <div class="text-2xl font-black text-orange-300" x-text="rs(petMonth)"></div>
        <div class="text-xs text-orange-600 mt-1">@ ₹2.10/km avg</div>
      </div>
      <div class="rounded-2xl p-5 text-center col-span-2"
           style="background:rgba(99,102,241,.12);border:1px solid rgba(99,102,241,.2)">
        <div class="text-xs text-indigo-400 font-bold uppercase tracking-wide mb-2">Monthly Savings</div>
        <div class="text-3xl font-black text-white" x-text="rs(saved)"></div>
        <div class="text-xs text-indigo-400 mt-1" x-text="'Annual: ' + rs(annSaved) + ' · Break-even in ~' + breakEven + ' months'"></div>
      </div>
    </div>

    <!-- Annual savings banner -->
    <div class="rounded-2xl p-6 text-center mb-6"
         style="background:linear-gradient(135deg,rgba(22,163,74,.9),rgba(15,118,110,.9));border:1px solid rgba(34,197,94,.3)">
      <div class="text-xs text-green-100 font-bold uppercase tracking-widest mb-2">Total 5-Year Savings Over Petrol</div>
      <div class="text-4xl font-black text-white mb-1" x-text="rs(annSaved * 5)"></div>
      <div class="text-xs text-green-200 opacity-80">That's how much you pocket by switching to EV</div>
    </div>

    <!-- CTAs -->
    <div class="grid grid-cols-2 gap-3">
      <a href="<?= base_url('find-my-ev') ?>"
         class="flex items-center justify-center gap-2 rounded-2xl font-bold py-3.5 text-sm transition-all hover:-translate-y-0.5 text-white"
         style="background:linear-gradient(135deg,#16a34a,#0d9488)">
        Find My EV ⚡
      </a>
      <a href="<?= base_url('vehicles') ?>"
         class="flex items-center justify-center gap-2 rounded-2xl bg-white/10 border border-white/20 text-white font-bold py-3.5 text-sm hover:bg-white/20 transition-all hover:-translate-y-0.5">
        Browse EVs →
      </a>
    </div>

    <p class="text-center text-xs text-white/25 mt-5">Petrol cost comparison uses ₹2.10/km average. Electricity tariff varies by state and DISCOM slab.</p>

  </div>
</div>

<?= $this->endSection() ?>
