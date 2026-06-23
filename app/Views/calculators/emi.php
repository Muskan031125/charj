<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<div class="min-h-screen bg-slate-50">

  <!-- Page hero -->
  <div class="bg-gradient-to-br from-green-900 to-teal-900 py-12 pt-28">
    <div class="mx-auto max-w-2xl px-4 text-center">
      <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-xs font-semibold text-green-200 mb-4">
        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
        EMI Calculator
      </div>
      <h1 class="text-3xl font-black text-white">EV Loan EMI Calculator</h1>
      <p class="mt-2 text-green-200 text-sm">Calculate your monthly EMI for any electric vehicle loan in India.</p>
    </div>
  </div>

  <!-- Tool content -->
  <div class="mx-auto max-w-5xl px-4 py-10 -mt-6">
    <div class="lg:grid lg:grid-cols-[1fr_360px] lg:gap-8">
    <!-- EMI Calculator -->
    <div x-data="{
        price: 1400000,
        down: 200000,
        rate: 9,
        tenure: 36,
        get loan() { return Math.max(0, this.price - this.down) },
        get emi() {
            const p = this.loan, r = this.rate/12/100, n = this.tenure;
            if (p <= 0 || r <= 0) return 0;
            return (p * r * Math.pow(1+r,n)) / (Math.pow(1+r,n)-1);
        },
        get totalAmt() { return this.emi * this.tenure },
        get totalInt() { return this.totalAmt - this.loan },
        fmt(n) { return '₹' + Math.round(n).toLocaleString('en-IN') }
    }" class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h1 class="text-3xl font-black text-slate-900">EV EMI Calculator</h1>
        <p class="mt-2 text-slate-600">Calculate your monthly EMI for any electric vehicle loan in India.</p>

        <div class="mt-8 grid gap-5">
            <label class="grid gap-1">
                <span class="text-sm font-semibold text-slate-700">Vehicle Price (₹)</span>
                <input type="number" x-model="price" min="50000" max="10000000" step="10000"
                    class="rounded-xl border border-slate-300 px-4 py-3 text-lg font-bold focus:border-green-500 focus:outline-none">
                <span class="text-xs text-slate-500">Ex-showroom price</span>
            </label>
            <label class="grid gap-1">
                <span class="text-sm font-semibold text-slate-700">Down Payment (₹)</span>
                <input type="number" x-model="down" min="0" max="5000000" step="10000"
                    class="rounded-xl border border-slate-300 px-4 py-3 text-lg font-bold focus:border-green-500 focus:outline-none">
                <span class="text-xs text-slate-500" x-text="'Loan amount: ' + fmt(loan)"></span>
            </label>
            <label class="grid gap-1">
                <span class="text-sm font-semibold text-slate-700">Interest Rate (% p.a.)</span>
                <div class="flex items-center gap-3">
                    <input type="range" x-model="rate" min="6" max="20" step="0.5" class="flex-1 accent-green-500">
                    <span class="w-14 text-center font-bold text-green-600" x-text="rate + '%'"></span>
                </div>
            </label>
            <label class="grid gap-1">
                <span class="text-sm font-semibold text-slate-700">Loan Tenure</span>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ([12, 24, 36, 48, 60, 72, 84] as $m): ?>
                    <button type="button" @click="tenure = <?= $m ?>"
                        :class="tenure == <?= $m ?> ? 'bg-green-600 text-white' : 'bg-slate-100 text-slate-700'"
                        class="rounded-xl px-4 py-2 text-sm font-bold transition-colors">
                        <?= $m ?>M
                    </button>
                    <?php endforeach; ?>
                </div>
            </label>
        </div>

        <!-- Results -->
        <div class="mt-8 grid grid-cols-3 gap-3">
            <div class="rounded-2xl bg-green-50 p-4 text-center">
                <div class="text-xs font-bold uppercase tracking-wide text-green-700">Monthly EMI</div>
                <div class="mt-1 text-2xl font-black text-green-800" x-text="fmt(emi)"></div>
            </div>
            <div class="rounded-2xl bg-slate-50 p-4 text-center">
                <div class="text-xs font-bold uppercase tracking-wide text-slate-500">Total Interest</div>
                <div class="mt-1 text-xl font-black" x-text="fmt(totalInt)"></div>
            </div>
            <div class="rounded-2xl bg-slate-50 p-4 text-center">
                <div class="text-xs font-bold uppercase tracking-wide text-slate-500">Total Amount</div>
                <div class="mt-1 text-xl font-black" x-text="fmt(totalAmt)"></div>
            </div>
        </div>

        <!-- Visual breakdown -->
        <div class="mt-6">
            <div class="flex justify-between text-xs text-slate-500 mb-1">
                <span>Principal</span><span x-text="fmt(loan)"></span>
            </div>
            <div class="h-4 rounded-full bg-slate-200 overflow-hidden flex">
                <div class="bg-green-500 h-4 transition-all" :style="'width:' + (loan/totalAmt*100).toFixed(1) + '%'"></div>
                <div class="bg-amber-400 h-4 flex-1"></div>
            </div>
            <div class="flex justify-between text-xs text-slate-500 mt-1">
                <span class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded-full bg-green-500"></span> Principal</span>
                <span class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded-full bg-amber-400"></span> Interest</span>
            </div>
        </div>

        <!-- Finance tips -->
        <div class="mt-8 rounded-2xl bg-blue-50 p-5">
            <h3 class="font-bold text-blue-900">EV Finance Tips</h3>
            <ul class="mt-3 space-y-2 text-sm text-blue-800">
                <li>✓ FAME II subsidy reduces effective loan amount</li>
                <li>✓ Tax benefit under Section 80EEB: ₹1.5L interest deduction</li>
                <li>✓ Compare rates: SBI (8.5%), HDFC (9%), ICICI (9.5%)</li>
                <li>✓ Higher down payment = lower EMI and less interest</li>
            </ul>
        </div>
    </div>

    <aside><?= view('partials/lead_form', ['vehicle' => []]) ?></aside>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
