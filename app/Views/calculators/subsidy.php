<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<div x-data="subsidyCalc()" class="min-h-screen bg-slate-50">

  <!-- Page hero -->
  <div class="bg-gradient-to-br from-green-900 to-teal-900 py-12 pt-28">
    <div class="mx-auto max-w-2xl px-4 text-center">
      <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-xs font-semibold text-green-200 mb-4">
        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        Updated for 2024–25 Schemes
      </div>
      <h1 class="text-3xl font-black text-white">Find Your EV Subsidy</h1>
      <p class="mt-2 text-green-200 text-sm">Most buyers miss <strong class="text-white">&#8377;15,000–&#8377;1,50,000</strong> in government subsidies. Check in 2 minutes.</p>
    </div>
  </div>

  <!-- Step Progress Bar -->
  <div class="bg-white border-b border-slate-200 sticky top-16 z-40 -mt-6 shadow-sm">
    <div class="max-w-4xl mx-auto px-4 py-3">
      <div class="flex items-center gap-2">
        <template x-for="(label, i) in ['Select State', 'Vehicle Type', 'Vehicle Price', 'Your Results']" :key="i">
          <div class="flex items-center gap-2 flex-1">
            <div class="flex items-center gap-2 min-w-0">
              <div :class="{
                  'bg-green-600 text-white': step > i+1,
                  'bg-slate-900 text-white ring-2 ring-slate-900 ring-offset-2': step === i+1,
                  'bg-slate-200 text-slate-500': step < i+1
                }"
                class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 transition-all">
                <template x-if="step > i+1">
                  <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                </template>
                <template x-if="step <= i+1">
                  <span x-text="i+1"></span>
                </template>
              </div>
              <span :class="step === i+1 ? 'text-slate-900 font-semibold' : 'text-slate-400'" class="text-xs hidden sm:block" x-text="label"></span>
            </div>
            <template x-if="i < 3">
              <div :class="step > i+1 ? 'bg-green-500' : 'bg-slate-200'" class="h-0.5 flex-1 rounded transition-colors"></div>
            </template>
          </div>
        </template>
      </div>
    </div>
  </div>

  <div class="max-w-4xl mx-auto px-4 py-8">

    <!-- ══ STEP 1: State Selection ══ -->
    <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
      <div class="text-center mb-8">
        <h2 class="text-2xl font-black text-slate-900">Which state are you buying your EV in?</h2>
        <p class="text-slate-500 mt-2">State policies change frequently — we show you only verified active schemes.</p>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 mb-8">
        <template x-for="s in states" :key="s.id">
          <button @click="selectState(s)"
            :class="selectedState && selectedState.id === s.id
              ? 'ring-2 ring-green-500 bg-green-50 border-green-300'
              : 'bg-white border-slate-200 hover:border-green-300 hover:bg-green-50/50'"
            class="border rounded-2xl p-4 text-left transition-all group relative">
            <div class="flex items-start justify-between mb-2">
              <span class="text-xl" x-text="s.flag"></span>
              <span :class="s.active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600'"
                class="text-[10px] font-bold px-1.5 py-0.5 rounded-full" x-text="s.active ? 'ACTIVE' : 'ENDED'"></span>
            </div>
            <div class="font-bold text-slate-800 text-sm" x-text="s.name"></div>
            <div class="text-xs text-slate-500 mt-0.5" x-text="s.scheme"></div>
            <div class="mt-2 pt-2 border-t border-slate-100">
              <div class="text-green-600 font-black text-sm" x-text="s.active ? 'Up to ' + fmt(Math.max(s.subsidy2w, s.subsidy4w)) : 'Central only'"></div>
              <div class="text-[10px] text-slate-400 mt-0.5">state subsidy</div>
            </div>
            <template x-if="selectedState && selectedState.id === s.id">
              <div class="absolute top-2 right-2 w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
              </div>
            </template>
          </button>
        </template>
      </div>

      <!-- Selected state detail panel -->
      <div x-show="selectedState" x-transition class="bg-slate-900 text-white rounded-2xl p-6 mb-6">
        <div class="flex items-start justify-between flex-wrap gap-4">
          <div>
            <h3 class="text-xl font-black" x-text="selectedState?.name + ' EV Policy'"></h3>
            <p class="text-slate-300 text-sm mt-1" x-text="selectedState?.scheme"></p>
          </div>
          <span :class="selectedState?.active ? 'bg-green-500/20 text-green-300 border-green-400/30' : 'bg-red-500/20 text-red-300 border-red-400/30'"
            class="border rounded-full px-3 py-1 text-xs font-bold" x-text="selectedState?.active ? 'SCHEME ACTIVE' : 'SCHEME ENDED'"></span>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-5">
          <div class="bg-white/10 rounded-xl p-3">
            <div class="text-xs text-slate-400 mb-1">2-Wheeler Subsidy</div>
            <div class="text-lg font-black text-green-400" x-text="fmt(selectedState?.subsidy2w ?? 0)"></div>
          </div>
          <div class="bg-white/10 rounded-xl p-3">
            <div class="text-xs text-slate-400 mb-1">3-Wheeler Subsidy</div>
            <div class="text-lg font-black text-green-400" x-text="fmt(selectedState?.subsidy3w ?? 0)"></div>
          </div>
          <div class="bg-white/10 rounded-xl p-3">
            <div class="text-xs text-slate-400 mb-1">4-Wheeler Subsidy</div>
            <div class="text-lg font-black text-green-400" x-text="fmt(selectedState?.subsidy4w ?? 0)"></div>
          </div>
          <div class="bg-white/10 rounded-xl p-3">
            <div class="text-xs text-slate-400 mb-1">Road Tax</div>
            <div class="text-lg font-black text-green-400">100% Exempt</div>
          </div>
        </div>
        <div class="mt-4 text-xs text-slate-400 flex items-center gap-1.5">
          <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
          Subsidy subject to scheme availability and vehicle eligibility. Verify with dealer.
        </div>
      </div>

      <div class="text-center">
        <button @click="step = 2" :disabled="!selectedState"
          :class="selectedState ? 'bg-green-600 hover:bg-green-700 text-white' : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
          class="px-10 py-4 rounded-2xl font-bold text-lg transition-colors">
          Next: Select Vehicle Type →
        </button>
      </div>
    </div>

    <!-- ══ STEP 2: Vehicle Type ══ -->
    <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
      <div class="text-center mb-8">
        <h2 class="text-2xl font-black text-slate-900">What type of EV are you buying?</h2>
        <p class="text-slate-500 mt-2">Subsidy amounts differ significantly by vehicle category.</p>
      </div>

      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <template x-for="vt in vehicleTypes" :key="vt.id">
          <button @click="vehicleType = vt.id"
            :class="vehicleType === vt.id ? 'ring-2 ring-green-500 bg-green-50 border-green-300' : 'bg-white border-slate-200 hover:border-green-300'"
            class="border rounded-2xl p-6 text-center transition-all">
            <div class="text-5xl mb-3" x-text="vt.icon"></div>
            <div class="font-bold text-slate-800" x-text="vt.label"></div>
            <div class="text-xs text-slate-500 mt-1" x-text="vt.sub"></div>
            <div class="mt-3 pt-3 border-t border-slate-100">
              <div class="text-xs text-slate-500">FAME II central</div>
              <div class="text-green-600 font-black" x-text="fmt(vt.fameSubsidy)"></div>
            </div>
          </button>
        </template>
      </div>

      <div class="flex gap-3 justify-center">
        <button @click="step = 1" class="px-6 py-3 rounded-xl border border-slate-300 text-slate-600 font-semibold hover:bg-slate-50">← Back</button>
        <button @click="step = 3" :disabled="!vehicleType"
          :class="vehicleType ? 'bg-green-600 hover:bg-green-700 text-white' : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
          class="px-10 py-3 rounded-xl font-bold transition-colors">
          Next: Vehicle Price →
        </button>
      </div>
    </div>

    <!-- ══ STEP 3: Vehicle Price ══ -->
    <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
      <div class="text-center mb-8">
        <h2 class="text-2xl font-black text-slate-900">What is the approximate price of your EV?</h2>
        <p class="text-slate-500 mt-2">We'll calculate exact road tax savings and loan interest benefits.</p>
      </div>

      <div class="bg-white rounded-3xl p-8 shadow-sm ring-1 ring-slate-200 mb-6">
        <label class="block">
          <span class="text-sm font-semibold text-slate-700">Vehicle Ex-Showroom Price</span>
          <div class="mt-4">
            <input type="range" x-model="vehiclePrice"
              :min="vehicleType === '2w' ? 50000 : vehicleType === '3w' ? 100000 : 500000"
              :max="vehicleType === '2w' ? 300000 : vehicleType === '3w' ? 600000 : 5000000"
              :step="vehicleType === '4w' || vehicleType === 'commercial' ? 25000 : 5000"
              class="w-full accent-green-500 h-2 rounded-lg">
          </div>
          <div class="flex justify-between text-xs text-slate-400 mt-1">
            <span x-text="vehicleType === '2w' ? '₹50,000' : vehicleType === '3w' ? '₹1,00,000' : '₹5,00,000'"></span>
            <span x-text="vehicleType === '2w' ? '₹3,00,000' : vehicleType === '3w' ? '₹6,00,000' : '₹50,00,000'"></span>
          </div>
        </label>

        <div class="mt-6 text-center">
          <div class="text-4xl font-black text-slate-900" x-text="fmt(vehiclePrice)"></div>
          <div class="text-slate-500 text-sm mt-1">ex-showroom price</div>
        </div>

        <!-- Quick preset buttons -->
        <div class="mt-6 flex flex-wrap gap-2 justify-center">
          <span class="text-xs text-slate-500 w-full text-center mb-1">Quick select:</span>
          <template x-if="vehicleType === '2w'">
            <div class="flex flex-wrap gap-2 justify-center">
              <template x-for="preset in [75000, 100000, 130000, 160000, 200000]" :key="preset">
                <button @click="vehiclePrice = preset"
                  :class="vehiclePrice == preset ? 'bg-green-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                  class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors" x-text="fmt(preset)"></button>
              </template>
            </div>
          </template>
          <template x-if="vehicleType === '4w'">
            <div class="flex flex-wrap gap-2 justify-center">
              <template x-for="preset in [800000, 1200000, 1500000, 2000000, 3000000]" :key="preset">
                <button @click="vehiclePrice = preset"
                  :class="vehiclePrice == preset ? 'bg-green-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                  class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors" x-text="fmt(preset)"></button>
              </template>
            </div>
          </template>
        </div>
      </div>

      <div class="flex gap-3 justify-center">
        <button @click="step = 2" class="px-6 py-3 rounded-xl border border-slate-300 text-slate-600 font-semibold hover:bg-slate-50">← Back</button>
        <button @click="step = 4; calcResults()"
          class="px-10 py-3 rounded-xl font-bold bg-green-600 hover:bg-green-700 text-white transition-colors">
          Calculate My Subsidy →
        </button>
      </div>
    </div>

    <!-- ══ STEP 4: Results ══ -->
    <div x-show="step === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">

      <!-- Total Benefit Banner -->
      <div class="bg-gradient-to-r from-green-600 to-green-500 text-white rounded-3xl p-8 mb-6 text-center shadow-xl shadow-green-500/20">
        <div class="text-sm font-semibold text-green-100 uppercase tracking-widest mb-2">Total Government Benefit</div>
        <div class="text-6xl font-black mb-3" x-text="fmt(results.totalBenefit)"></div>
        <div class="text-green-100 text-lg">
          Your <span class="font-bold text-white" x-text="fmt(vehiclePrice)"></span> EV effectively costs
          <span class="font-black text-white" x-text="fmt(Math.max(0, vehiclePrice - results.totalBenefit))"></span> after all benefits
        </div>
        <div class="mt-4 inline-flex items-center gap-2 bg-white/20 rounded-full px-4 py-1.5 text-sm">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
          Based on <span x-text="selectedState?.name + ' + Central benefits'"></span>
        </div>
      </div>

      <!-- Breakdown Card -->
      <div class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-200 overflow-hidden mb-6">
        <div class="p-6 border-b border-slate-100">
          <h3 class="text-lg font-black text-slate-900">Complete Subsidy Breakdown</h3>
          <p class="text-slate-500 text-sm mt-1">Every rupee you're entitled to claim</p>
        </div>

        <div class="divide-y divide-slate-100">
          <!-- FAME II -->
          <div class="px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/></svg>
              </div>
              <div>
                <div class="font-semibold text-slate-800">FAME II — Central Government Subsidy</div>
                <div class="text-xs text-slate-500 mt-0.5" x-text="'For ' + (vehicleTypes.find(v=>v.id===vehicleType)?.label ?? 'EV')"></div>
              </div>
            </div>
            <div class="text-right">
              <div class="text-lg font-black text-green-600" x-text="fmt(results.fameSubsidy)"></div>
              <div class="text-xs text-slate-400">Direct on-road</div>
            </div>
          </div>

          <!-- State Subsidy -->
          <div class="px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/></svg>
              </div>
              <div>
                <div class="font-semibold text-slate-800" x-text="selectedState?.name + ' State Subsidy'"></div>
                <div class="text-xs text-slate-500 mt-0.5" x-text="selectedState?.scheme + (selectedState?.active ? ' — Active' : ' — Ended')"></div>
              </div>
            </div>
            <div class="text-right">
              <div class="text-lg font-black" :class="results.stateSubsidy > 0 ? 'text-green-600' : 'text-slate-400'" x-text="results.stateSubsidy > 0 ? fmt(results.stateSubsidy) : '—'"></div>
              <div class="text-xs" :class="selectedState?.active ? 'text-green-500' : 'text-red-400'" x-text="selectedState?.active ? 'Active scheme' : 'No active scheme'"></div>
            </div>
          </div>

          <!-- Road Tax -->
          <div class="px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
              </div>
              <div>
                <div class="font-semibold text-slate-800">Road Tax Exemption (100%)</div>
                <div class="text-xs text-slate-500 mt-0.5">Most states waive road tax on EVs completely</div>
              </div>
            </div>
            <div class="text-right">
              <div class="text-lg font-black text-green-600" x-text="fmt(results.roadTaxSaving)"></div>
              <div class="text-xs text-slate-400" x-text="'~' + results.roadTaxPct + '% of ex-showroom'"></div>
            </div>
          </div>

          <!-- Section 80EEB -->
          <div class="px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-teal-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-teal-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
              </div>
              <div>
                <div class="font-semibold text-slate-800">Section 80EEB Income Tax Benefit</div>
                <div class="text-xs text-slate-500 mt-0.5">₹1,50,000 deduction on loan interest (30% tax bracket)</div>
              </div>
            </div>
            <div class="text-right">
              <div class="text-lg font-black text-green-600">₹46,800</div>
              <div class="text-xs text-slate-400">If 31.2% tax bracket</div>
            </div>
          </div>

          <!-- Registration -->
          <div class="px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm2 10a1 1 0 10-2 0v3a1 1 0 102 0v-3zm2-3a1 1 0 011 1v5a1 1 0 11-2 0v-5a1 1 0 011-1zm4-1a1 1 0 10-2 0v7a1 1 0 102 0V8z" clip-rule="evenodd"/></svg>
              </div>
              <div>
                <div class="font-semibold text-slate-800">Registration Fee Exemption</div>
                <div class="text-xs text-slate-500 mt-0.5">EVs exempt from registration fees in most states</div>
              </div>
            </div>
            <div class="text-right">
              <div class="text-lg font-black text-green-600" x-text="fmt(results.regFeeWaiver)"></div>
              <div class="text-xs text-slate-400">Saved on registration</div>
            </div>
          </div>
        </div>

        <!-- Total -->
        <div class="bg-green-50 px-6 py-5 border-t-2 border-green-200 flex items-center justify-between">
          <div>
            <div class="text-sm font-semibold text-green-700">TOTAL BENEFIT AVAILABLE</div>
            <div class="text-xs text-green-600 mt-0.5">Excluding 80EEB (loan interest benefit over tenure)</div>
          </div>
          <div class="text-3xl font-black text-green-700" x-text="fmt(results.totalBenefit)"></div>
        </div>
      </div>

      <!-- Disclaimer -->
      <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 flex gap-3">
        <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
        <div class="text-sm text-amber-800">
          <strong>Important:</strong> Subsidy amounts are as per latest published government data (2024–25). Actual subsidy is subject to scheme availability, vehicle eligibility (registered OEM, battery capacity), and state-specific conditions. Always verify with your EV dealer before purchase.
        </div>
      </div>

      <!-- Lead Capture -->
      <div class="bg-slate-900 text-white rounded-3xl p-8 mb-6">
        <div class="flex flex-col lg:flex-row gap-6 items-start">
          <div class="flex-1">
            <h3 class="text-2xl font-black mb-2">Claim Every Subsidy — Get Expert Help</h3>
            <p class="text-slate-300 text-sm">Our EV advisors will walk you through the exact paperwork to claim all <span class="text-green-400 font-bold" x-text="fmt(results.totalBenefit)"></span> in benefits. Free consultation.</p>
            <ul class="mt-4 space-y-2 text-sm text-slate-300">
              <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Help you choose FAME II eligible models</li>
              <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>State subsidy application assistance</li>
              <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>80EEB tax filing guidance</li>
            </ul>
          </div>
          <form class="bg-white/10 backdrop-blur rounded-2xl p-5 w-full lg:w-80 space-y-3" @submit.prevent="submitLead">
            <div>
              <input type="text" x-model="lead.name" placeholder="Your name *" required
                class="w-full bg-white/20 border border-white/30 rounded-xl px-4 py-3 text-white placeholder-slate-400 text-sm focus:outline-none focus:border-green-400">
            </div>
            <div>
              <input type="tel" x-model="lead.mobile" placeholder="Mobile number *" required pattern="[6-9]\d{9}"
                class="w-full bg-white/20 border border-white/30 rounded-xl px-4 py-3 text-white placeholder-slate-400 text-sm focus:outline-none focus:border-green-400">
            </div>
            <div>
              <input type="text" x-model="lead.city" placeholder="Your city *" required
                class="w-full bg-white/20 border border-white/30 rounded-xl px-4 py-3 text-white placeholder-slate-400 text-sm focus:outline-none focus:border-green-400">
            </div>
            <button type="submit" :disabled="leadSent"
              class="w-full bg-green-500 hover:bg-green-400 text-white font-bold py-3 rounded-xl transition-colors disabled:opacity-60">
              <span x-show="!leadSent">Get Free Subsidy Advice →</span>
              <span x-show="leadSent">✓ We'll call you within 24 hours!</span>
            </button>
          </form>
        </div>
      </div>

      <!-- Share + Recalculate -->
      <div class="flex flex-wrap gap-3 justify-center">
        <button @click="step = 1; selectedState = null; vehicleType = null; vehiclePrice = 150000"
          class="flex items-center gap-2 px-5 py-3 rounded-xl border border-slate-300 text-slate-600 font-semibold hover:bg-slate-50 transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
          Recalculate
        </button>
        <button @click="shareResult"
          class="flex items-center gap-2 px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold transition-colors">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z"/></svg>
          Share My Result
        </button>
      </div>
    </div>

  </div>
</div>

<script>
function subsidyCalc() {
  return {
    step: 1,
    selectedState: null,
    vehicleType: null,
    vehiclePrice: 150000,
    results: {},
    lead: { name: '', mobile: '', city: '' },
    leadSent: false,

    states: [
      { id: 'delhi',     name: 'Delhi',       flag: '🏙️', scheme: 'Delhi EV Policy',     subsidy2w: 30000,  subsidy3w: 30000,  subsidy4w: 150000, active: true  },
      { id: 'mh',        name: 'Maharashtra', flag: '🌆', scheme: 'Mahavikas EV Policy', subsidy2w: 10000,  subsidy3w: 25000,  subsidy4w: 150000, active: true  },
      { id: 'gj',        name: 'Gujarat',     flag: '🏭', scheme: 'Gujarat EV Policy',   subsidy2w: 20000,  subsidy3w: 50000,  subsidy4w: 150000, active: true  },
      { id: 'rj',        name: 'Rajasthan',   flag: '🏜️', scheme: 'Rajasthan EV Policy', subsidy2w: 2500,   subsidy3w: 10000,  subsidy4w: 100000, active: true  },
      { id: 'ka',        name: 'Karnataka',   flag: '🌿', scheme: 'Karnataka EV Policy', subsidy2w: 10000,  subsidy3w: 30000,  subsidy4w: 100000, active: true  },
      { id: 'tn',        name: 'Tamil Nadu',  flag: '🏛️', scheme: 'TN Green Mobility',   subsidy2w: 12000,  subsidy3w: 30000,  subsidy4w: 100000, active: true  },
      { id: 'tg',        name: 'Telangana',   flag: '🌐', scheme: 'Telangana EV Policy', subsidy2w: 10000,  subsidy3w: 25000,  subsidy4w: 100000, active: true  },
      { id: 'up',        name: 'Uttar Pradesh',flag:'🕌', scheme: 'UP EV Policy 2022',   subsidy2w: 5000,   subsidy3w: 12000,  subsidy4w: 100000, active: true  },
      { id: 'wb',        name: 'West Bengal', flag: '🐯', scheme: 'WB EV Policy',        subsidy2w: 10000,  subsidy3w: 20000,  subsidy4w: 100000, active: true  },
      { id: 'kl',        name: 'Kerala',      flag: '🥥', scheme: 'Kerala EV Policy',    subsidy2w: 15000,  subsidy3w: 30000,  subsidy4w: 100000, active: true  },
      { id: 'pb',        name: 'Punjab',      flag: '🌾', scheme: 'Punjab EV Policy',    subsidy2w: 10000,  subsidy3w: 20000,  subsidy4w: 100000, active: true  },
      { id: 'mp',        name: 'Madhya Pradesh',flag:'🌳',scheme: 'MP EV Policy 2021',   subsidy2w: 5000,   subsidy3w: 12000,  subsidy4w: 50000,  active: true  },
      { id: 'ap',        name: 'Andhra Pradesh',flag:'🌴',scheme: 'AP EV Policy',         subsidy2w: 5000,   subsidy3w: 12000,  subsidy4w: 50000,  active: true  },
      { id: 'hr',        name: 'Haryana',     flag: '🚜', scheme: 'Haryana EV Policy',   subsidy2w: 15000,  subsidy3w: 30000,  subsidy4w: 100000, active: true  },
      { id: 'other',     name: 'Other State', flag: '🇮🇳', scheme: 'Central schemes only', subsidy2w: 0,      subsidy3w: 0,      subsidy4w: 0,      active: false },
    ],

    vehicleTypes: [
      { id: '2w',         label: '2-Wheeler',   sub: 'Scooter / Bike',        icon: '🛵', fameSubsidy: 10000  },
      { id: '3w',         label: '3-Wheeler',   sub: 'Auto / E-Rickshaw',     icon: '🛺', fameSubsidy: 50000  },
      { id: '4w',         label: '4-Wheeler',   sub: 'Car / SUV',             icon: '🚗', fameSubsidy: 150000 },
      { id: 'commercial', label: 'Commercial',  sub: 'LCV / Delivery',        icon: '🚚', fameSubsidy: 150000 },
    ],

    selectState(s) {
      this.selectedState = s;
      // Preset vehicle price based on state selection context
      if (this.vehiclePrice === 150000 && (s.id === 'delhi' || s.subsidy4w >= 100000)) {
        // keep current price
      }
    },

    calcResults() {
      const vt = this.vehicleType;
      const state = this.selectedState;
      const price = Number(this.vehiclePrice);

      const fameSubsidy = this.vehicleTypes.find(v => v.id === vt)?.fameSubsidy ?? 0;

      let stateSubsidy = 0;
      if (state && state.active) {
        if (vt === '2w') stateSubsidy = state.subsidy2w;
        else if (vt === '3w') stateSubsidy = state.subsidy3w;
        else if (vt === '4w' || vt === 'commercial') stateSubsidy = state.subsidy4w;
      }

      // Road tax: approx 4% for 2W, 6% for 3W, 10% for 4W (national avg)
      const roadTaxPcts = { '2w': 4, '3w': 5, '4w': 10, 'commercial': 8 };
      const roadTaxPct = roadTaxPcts[vt] ?? 8;
      const roadTaxSaving = Math.round(price * roadTaxPct / 100);

      // Registration fee (approx)
      const regFeeWaiver = vt === '4w' || vt === 'commercial' ? 15000 : vt === '3w' ? 5000 : 2000;

      const totalBenefit = fameSubsidy + stateSubsidy + roadTaxSaving + regFeeWaiver;

      this.results = { fameSubsidy, stateSubsidy, roadTaxSaving, roadTaxPct, regFeeWaiver, totalBenefit };
    },

    fmt(n) {
      if (!n && n !== 0) return '—';
      return '₹' + Math.round(n).toLocaleString('en-IN');
    },

    submitLead() {
      if (!this.lead.name || !this.lead.mobile || !this.lead.city) return;
      this.leadSent = true;
      // In production: POST to backend
      if (window.charjTrack) charjTrack('subsidy_lead', { state: this.selectedState?.id, vehicle_type: this.vehicleType });
    },

    shareResult() {
      const text = `I found ₹${Math.round(this.results.totalBenefit).toLocaleString('en-IN')} in EV subsidies on Charj.in! Check yours at charj.in/calculators/subsidy`;
      if (navigator.share) {
        navigator.share({ title: 'My EV Subsidy', text });
      } else {
        navigator.clipboard.writeText(text).then(() => alert('Result copied to clipboard!'));
      }
    }
  }
}
</script>

<?= $this->endSection() ?>
