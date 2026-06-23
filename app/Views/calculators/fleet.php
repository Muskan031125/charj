<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<div x-data="fleetCalc()" class="min-h-screen bg-slate-50">

  <!-- ── Hero ── -->
  <section class="bg-gradient-to-br from-[#0a1628] via-[#0d2137] to-[#1a3a55] text-white py-16 px-4">
    <div class="max-w-5xl mx-auto">
      <div class="flex flex-col lg:flex-row gap-8 items-center">
        <div class="flex-1">
          <div class="inline-flex items-center gap-2 bg-green-500/20 border border-green-400/30 rounded-full px-4 py-1.5 text-green-300 text-sm font-semibold mb-5">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
            Trusted by 500+ Fleet Operators
          </div>
          <h1 class="text-4xl lg:text-5xl font-black tracking-tight mb-4">EV Fleet ROI Calculator</h1>
          <p class="text-xl text-slate-300 mb-6">
            Calculate exact savings from switching your business fleet to electric. Real numbers, real payback period, real ROI.
          </p>
          <div class="grid grid-cols-3 gap-4 max-w-sm">
            <div class="bg-white/10 rounded-xl p-3 text-center">
              <div class="text-2xl font-black text-green-400" x-text="fmt(r.annualSaving)"></div>
              <div class="text-xs text-slate-400 mt-1">Annual saving</div>
            </div>
            <div class="bg-white/10 rounded-xl p-3 text-center">
              <div class="text-2xl font-black text-green-400" x-text="r.breakEvenMonths + 'M'"></div>
              <div class="text-xs text-slate-400 mt-1">Break-even</div>
            </div>
            <div class="bg-white/10 rounded-xl p-3 text-center">
              <div class="text-2xl font-black text-green-400" x-text="r.co2Annual + 'T'"></div>
              <div class="text-xs text-slate-400 mt-1">CO₂ saved/yr</div>
            </div>
          </div>
        </div>
        <div class="lg:w-72 bg-white/10 backdrop-blur rounded-3xl p-6 border border-white/20 w-full">
          <div class="text-center mb-4">
            <div class="text-xs text-slate-400 uppercase tracking-wide mb-1">Your Fleet Saves</div>
            <div class="text-4xl font-black text-green-400" x-text="fmt(r.dailySaving)"></div>
            <div class="text-slate-300 text-sm">every single day</div>
          </div>
          <div class="h-1 bg-white/20 rounded-full mb-4">
            <div class="h-full bg-green-400 rounded-full transition-all duration-700" :style="'width: ' + Math.min(100, (inputs.numVehicles / 100) * 100) + '%'"></div>
          </div>
          <div class="text-xs text-slate-400 text-center" x-text="inputs.numVehicles + ' vehicles × ' + fmt(r.perVehicleDailySaving) + '/vehicle/day'"></div>
        </div>
      </div>
    </div>
  </section>

  <div class="max-w-5xl mx-auto px-4 py-10">
    <div class="grid lg:grid-cols-[1fr_380px] gap-8">

      <!-- ── Inputs ── -->
      <div class="space-y-6">
        <div class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-200 p-6">
          <h2 class="font-black text-slate-900 text-lg mb-5">Fleet & Usage Details</h2>

          <div class="space-y-6">
            <!-- Number of vehicles -->
            <div>
              <label class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold text-slate-700">Number of Vehicles</span>
                <span class="text-2xl font-black text-[#0d2137]" x-text="inputs.numVehicles"></span>
              </label>
              <input type="range" x-model="inputs.numVehicles" min="1" max="500" step="1" @input="calc()"
                class="w-full accent-green-600 h-2 rounded-lg">
              <div class="flex justify-between text-xs text-slate-400 mt-1"><span>1</span><span>500</span></div>
            </div>

            <!-- Vehicle type -->
            <div>
              <label class="text-sm font-semibold text-slate-700 block mb-2">Vehicle Type</label>
              <div class="grid grid-cols-2 gap-2">
                <template x-for="vt in vehicleTypes" :key="vt.id">
                  <button @click="inputs.vehicleType = vt.id; calc()"
                    :class="inputs.vehicleType === vt.id ? 'ring-2 ring-green-500 bg-green-50 border-green-300' : 'bg-slate-50 border-slate-200 hover:border-green-300'"
                    class="border rounded-xl p-3 text-left transition-all">
                    <div class="text-lg mb-1" x-text="vt.icon"></div>
                    <div class="font-semibold text-slate-800 text-xs" x-text="vt.label"></div>
                    <div class="text-[10px] text-slate-500" x-text="vt.sub"></div>
                  </button>
                </template>
              </div>
            </div>

            <!-- Daily km & Working days -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="flex items-center justify-between mb-2">
                  <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Daily km/vehicle</span>
                  <span class="font-black text-green-700" x-text="inputs.dailyKm + ' km'"></span>
                </label>
                <input type="range" x-model="inputs.dailyKm" min="20" max="300" step="5" @input="calc()" class="w-full accent-green-500">
              </div>
              <div>
                <label class="flex items-center justify-between mb-2">
                  <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Working days/month</span>
                  <span class="font-black text-green-700" x-text="inputs.workDays"></span>
                </label>
                <input type="range" x-model="inputs.workDays" min="15" max="30" step="1" @input="calc()" class="w-full accent-green-500">
              </div>
            </div>
          </div>
        </div>

        <!-- Fuel & Energy Costs -->
        <div class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-200 p-6">
          <h2 class="font-black text-slate-900 text-lg mb-5">Current Petrol Fleet vs EV Fleet</h2>
          <div class="grid grid-cols-2 gap-6">
            <div class="space-y-4">
              <h3 class="text-xs font-bold text-orange-600 uppercase tracking-wide flex items-center gap-1.5">
                <span class="w-2 h-2 bg-orange-500 rounded-full"></span> Current Petrol Fleet
              </h3>
              <label class="grid gap-1.5">
                <span class="text-xs text-slate-600">Fuel cost ₹/litre</span>
                <div class="flex items-center gap-2">
                  <input type="range" x-model="inputs.fuelPrice" min="80" max="140" step="1" @input="calc()" class="flex-1 accent-orange-500">
                  <span class="w-12 text-center bg-orange-50 text-orange-700 font-bold rounded-lg py-1 text-xs" x-text="'₹' + inputs.fuelPrice"></span>
                </div>
              </label>
              <label class="grid gap-1.5">
                <span class="text-xs text-slate-600">Mileage km/litre</span>
                <div class="flex items-center gap-2">
                  <input type="range" x-model="inputs.mileage" min="15" max="60" step="1" @input="calc()" class="flex-1 accent-orange-500">
                  <span class="w-12 text-center bg-orange-50 text-orange-700 font-bold rounded-lg py-1 text-xs" x-text="inputs.mileage + ' km'"></span>
                </div>
              </label>
              <label class="grid gap-1.5">
                <span class="text-xs text-slate-600">Annual maintenance/vehicle (₹)</span>
                <input type="number" x-model="inputs.petrolMaintenance" step="1000" @input="calc()"
                  class="rounded-xl border border-orange-200 px-3 py-2 text-sm font-bold focus:border-orange-500 focus:outline-none">
              </label>
            </div>
            <div class="space-y-4">
              <h3 class="text-xs font-bold text-green-600 uppercase tracking-wide flex items-center gap-1.5">
                <span class="w-2 h-2 bg-green-500 rounded-full"></span> EV Fleet (Proposed)
              </h3>
              <label class="grid gap-1.5">
                <span class="text-xs text-slate-600">Electricity ₹/kWh</span>
                <div class="flex items-center gap-2">
                  <input type="range" x-model="inputs.elecRate" min="4" max="15" step="0.5" @input="calc()" class="flex-1 accent-green-500">
                  <span class="w-12 text-center bg-green-50 text-green-700 font-bold rounded-lg py-1 text-xs" x-text="'₹' + Number(inputs.elecRate).toFixed(1)"></span>
                </div>
              </label>
              <label class="grid gap-1.5">
                <span class="text-xs text-slate-600">EV efficiency km/kWh</span>
                <div class="flex items-center gap-2">
                  <input type="range" x-model="inputs.evEfficiency" min="2" max="12" step="0.5" @input="calc()" class="flex-1 accent-green-500">
                  <span class="w-12 text-center bg-green-50 text-green-700 font-bold rounded-lg py-1 text-xs" x-text="Number(inputs.evEfficiency).toFixed(1)"></span>
                </div>
              </label>
              <label class="grid gap-1.5">
                <span class="text-xs text-slate-600">Annual maintenance/vehicle (₹)</span>
                <input type="number" x-model="inputs.evMaintenance" step="500" @input="calc()"
                  class="rounded-xl border border-green-200 px-3 py-2 text-sm font-bold focus:border-green-500 focus:outline-none">
              </label>
            </div>
          </div>
        </div>

        <!-- Payback Inputs -->
        <div class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-200 p-6">
          <h2 class="font-black text-slate-900 text-lg mb-5">Payback Period Calculator</h2>
          <div class="grid grid-cols-2 gap-4">
            <label class="grid gap-1.5">
              <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Extra cost per EV vs ICE (₹)</span>
              <input type="number" x-model="inputs.extraCostPerVehicle" step="5000" @input="calc()"
                class="rounded-xl border border-slate-200 px-4 py-3 font-bold text-slate-800 focus:border-green-500 focus:outline-none">
              <span class="text-xs text-slate-400">Ex: EV costs ₹1,50,000 more than petrol equivalent</span>
            </label>
            <label class="grid gap-1.5">
              <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">FAME II commercial subsidy/vehicle (₹)</span>
              <input type="number" x-model="inputs.fameSubsidy" step="5000" @input="calc()"
                class="rounded-xl border border-slate-200 px-4 py-3 font-bold text-slate-800 focus:border-green-500 focus:outline-none">
              <span class="text-xs text-slate-400">₹50,000 for 3W; ₹1,50,000 for 4W commercial</span>
            </label>
          </div>

          <!-- Payback Result -->
          <div class="mt-5 bg-gradient-to-r from-[#0d2137] to-[#1e3f5a] rounded-2xl p-5 text-white flex flex-wrap gap-6 items-center justify-between">
            <div>
              <div class="text-xs text-slate-400 uppercase tracking-wide mb-1">Net extra investment (fleet)</div>
              <div class="text-2xl font-black text-white" x-text="fmt(r.netExtraInvestment)"></div>
            </div>
            <div>
              <div class="text-xs text-slate-400 uppercase tracking-wide mb-1">Break-even</div>
              <div class="text-2xl font-black text-green-400" x-text="r.breakEvenMonths + ' months'"></div>
            </div>
            <div>
              <div class="text-xs text-slate-400 uppercase tracking-wide mb-1">5-year net profit</div>
              <div class="text-2xl font-black text-green-400" x-text="fmt(r.fiveYearNetProfit)"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- ── Results Panel (sticky) ── -->
      <div class="space-y-5 lg:sticky lg:top-24 lg:self-start">

        <!-- Main Savings Card -->
        <div class="bg-gradient-to-br from-green-600 to-green-500 rounded-3xl p-6 text-white shadow-xl shadow-green-500/25">
          <h2 class="text-sm font-bold text-green-100 uppercase tracking-widest mb-4">Fleet Savings Summary</h2>

          <div class="space-y-4">
            <div class="bg-white/15 rounded-2xl p-4">
              <div class="text-xs text-green-100 mb-1">Monthly Fuel Saving (fleet)</div>
              <div class="text-3xl font-black" x-text="fmt(r.monthlyFuelSaving)"></div>
            </div>
            <div class="bg-white/15 rounded-2xl p-4">
              <div class="text-xs text-green-100 mb-1">Monthly Maintenance Saving</div>
              <div class="text-3xl font-black" x-text="fmt(r.monthlyMaintSaving)"></div>
            </div>
            <div class="bg-white/25 border border-white/30 rounded-2xl p-4">
              <div class="text-xs text-green-100 mb-1 font-bold uppercase tracking-wide">Total Monthly Saving</div>
              <div class="text-4xl font-black" x-text="fmt(r.totalMonthlySaving)"></div>
            </div>
          </div>

          <div class="mt-4 grid grid-cols-2 gap-3">
            <div class="bg-white/15 rounded-xl p-3 text-center">
              <div class="text-xs text-green-100 mb-1">Annual Saving</div>
              <div class="text-xl font-black" x-text="fmt(r.annualSaving)"></div>
            </div>
            <div class="bg-white/15 rounded-xl p-3 text-center">
              <div class="text-xs text-green-100 mb-1">5-Year Saving</div>
              <div class="text-xl font-black" x-text="fmt(r.fiveYearSaving)"></div>
            </div>
          </div>
        </div>

        <!-- Per-vehicle stats -->
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200 p-5">
          <h3 class="font-bold text-slate-700 text-sm mb-3">Per Vehicle Economics</h3>
          <div class="space-y-3 text-sm">
            <div class="flex justify-between items-center">
              <span class="text-slate-500">Daily saving/vehicle</span>
              <span class="font-black text-green-600" x-text="fmt(r.perVehicleDailySaving)"></span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-slate-500">Monthly saving/vehicle</span>
              <span class="font-black text-green-600" x-text="fmt(r.perVehicleMonthlySaving)"></span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-slate-500">Annual saving/vehicle</span>
              <span class="font-black text-green-600" x-text="fmt(r.perVehicleAnnualSaving)"></span>
            </div>
            <div class="border-t border-slate-100 pt-3 flex justify-between items-center">
              <span class="text-slate-500">CO₂ reduced/vehicle/yr</span>
              <span class="font-black text-green-600" x-text="(r.co2Annual / Number(inputs.numVehicles)).toFixed(2) + ' T'"></span>
            </div>
          </div>
        </div>

        <!-- CO2 Card -->
        <div class="bg-[#0d2137] rounded-2xl p-5 text-white">
          <h3 class="font-bold text-sm mb-3 text-slate-300">Environmental Impact (Annual)</h3>
          <div class="space-y-3">
            <div class="flex items-center gap-3">
              <span class="text-2xl">🌿</span>
              <div>
                <div class="text-xl font-black text-green-400" x-text="r.co2Annual + ' tonnes'"></div>
                <div class="text-xs text-slate-400">CO₂ emissions avoided</div>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <span class="text-2xl">🌳</span>
              <div>
                <div class="text-xl font-black text-green-400" x-text="(r.co2Annual * 50).toLocaleString('en-IN')"></div>
                <div class="text-xs text-slate-400">Equivalent trees planted</div>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <span class="text-2xl">⛽</span>
              <div>
                <div class="text-xl font-black text-orange-400" x-text="r.litresSaved.toLocaleString('en-IN') + ' L'"></div>
                <div class="text-xs text-slate-400">Petrol not burned</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Daily saving callout -->
        <div class="bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200 rounded-2xl p-5">
          <div class="text-xs font-bold text-amber-700 uppercase tracking-wide mb-2">At a glance</div>
          <p class="text-amber-900 font-semibold text-sm" x-text="'Your fleet of ' + inputs.numVehicles + ' vehicles saves ' + fmt(r.dailySaving) + ' per day — that is ' + fmt(r.annualSaving) + ' every year that flows directly to your bottom line.'"></p>
        </div>
      </div>
    </div>

    <!-- ══ Benefits Section ══ -->
    <section class="mt-12">
      <h2 class="text-3xl font-black text-slate-900 mb-2">Why EV Fleet Makes Business Sense</h2>
      <p class="text-slate-500 mb-8">Beyond fuel savings — the strategic advantages of going electric for your business fleet.</p>

      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php
        $benefits = [
          ['🏛️', 'Higher FAME II Commercial Subsidy', 'Commercial EV buyers receive higher FAME II subsidies (₹50,000–₹1,50,000 per vehicle) compared to personal use — directly reducing your fleet acquisition cost.', 'bg-blue-50 border-blue-200'],
          ['📊', 'GST Input Tax Credit', 'Businesses can claim 28% GST paid on EV purchases as input credit, unlike personal buyers. This significantly reduces effective cost for registered businesses.', 'bg-purple-50 border-purple-200'],
          ['🌱', 'ESG & CSR Reporting Benefits', 'A documented green fleet strengthens your ESG score, supports SEBI-mandated BRSR reporting, and can qualify for green bonds/financing at lower rates.', 'bg-green-50 border-green-200'],
          ['⚡', 'Insulation from Fuel Price Volatility', 'Petrol prices have risen 40%+ in 5 years. EV electricity costs are regulated and far more stable. Predictable operating costs improve fleet P&L forecasting.', 'bg-amber-50 border-amber-200'],
          ['🛡️', 'Improved Driver Safety', 'No fuel tanks, no combustion risk. EV fires are significantly rarer than petrol vehicle fires. Lower insurance claims = lower fleet insurance premiums over time.', 'bg-red-50 border-red-200'],
          ['🔋', 'Priority Commercial Charging', 'Registered commercial EV fleets get priority access at EESL-managed charging stations and can negotiate dedicated charging infrastructure with state DISCOMs.', 'bg-teal-50 border-teal-200'],
        ];
        foreach ($benefits as $b):
        ?>
        <div class="bg-white rounded-2xl border <?= $b[3] ?> p-5 shadow-sm">
          <div class="text-3xl mb-3"><?= $b[0] ?></div>
          <h3 class="font-bold text-slate-800 mb-2"><?= $b[1] ?></h3>
          <p class="text-slate-500 text-sm leading-relaxed"><?= $b[2] ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- ══ Lead Form ══ -->
    <section class="mt-12 bg-[#0d2137] rounded-3xl p-8 lg:p-12 text-white" x-data="{ sent: false, form: { company:'', fleetSize:'', vehicleType:'', contact:'', mobile:'', email:'' } }">
      <div class="grid lg:grid-cols-2 gap-10 items-start">
        <div>
          <h2 class="text-3xl font-black mb-3">Get a Custom Fleet EV Proposal</h2>
          <p class="text-slate-300 mb-6">Our fleet team will analyse your specific routes, vehicle types and financials to build a detailed ROI report — completely free.</p>
          <ul class="space-y-3 text-sm text-slate-300">
            <li class="flex items-start gap-2"><svg class="w-4 h-4 text-green-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Custom ROI report for your exact fleet composition</li>
            <li class="flex items-start gap-2"><svg class="w-4 h-4 text-green-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>EV model recommendations per vehicle category</li>
            <li class="flex items-start gap-2"><svg class="w-4 h-4 text-green-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>FAME II application guidance</li>
            <li class="flex items-start gap-2"><svg class="w-4 h-4 text-green-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Charging infrastructure planning</li>
          </ul>
          <div class="mt-5 inline-flex items-center gap-2 bg-green-500/20 border border-green-400/30 rounded-xl px-4 py-2.5 text-green-300 text-sm">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
            Our fleet team responds within <strong class="text-green-200">4 business hours</strong>
          </div>
        </div>

        <div x-show="!sent">
          <form class="space-y-4" @submit.prevent="sent = true; charjTrack && charjTrack('fleet_lead', { fleet_size: form.fleetSize })">
            <div class="grid grid-cols-2 gap-3">
              <input type="text" x-model="form.company" placeholder="Company name *" required
                class="bg-white/10 border border-white/30 rounded-xl px-4 py-3 text-white placeholder-slate-400 text-sm focus:outline-none focus:border-green-400">
              <input type="text" x-model="form.fleetSize" placeholder="Fleet size (vehicles) *" required
                class="bg-white/10 border border-white/30 rounded-xl px-4 py-3 text-white placeholder-slate-400 text-sm focus:outline-none focus:border-green-400">
            </div>
            <select x-model="form.vehicleType"
              class="w-full bg-white/10 border border-white/30 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-green-400">
              <option value="" class="text-slate-800">Select primary vehicle type *</option>
              <option value="2w_delivery" class="text-slate-800">2-Wheeler — Last mile delivery</option>
              <option value="3w_cargo" class="text-slate-800">3-Wheeler — Cargo/auto</option>
              <option value="4w_cab" class="text-slate-800">4-Wheeler — Cab/corporate</option>
              <option value="lcv" class="text-slate-800">LCV — Light commercial van</option>
              <option value="mixed" class="text-slate-800">Mixed fleet</option>
            </select>
            <input type="text" x-model="form.contact" placeholder="Contact person name *" required
              class="w-full bg-white/10 border border-white/30 rounded-xl px-4 py-3 text-white placeholder-slate-400 text-sm focus:outline-none focus:border-green-400">
            <div class="grid grid-cols-2 gap-3">
              <input type="tel" x-model="form.mobile" placeholder="Mobile *" required pattern="[6-9]\d{9}"
                class="bg-white/10 border border-white/30 rounded-xl px-4 py-3 text-white placeholder-slate-400 text-sm focus:outline-none focus:border-green-400">
              <input type="email" x-model="form.email" placeholder="Work email *" required
                class="bg-white/10 border border-white/30 rounded-xl px-4 py-3 text-white placeholder-slate-400 text-sm focus:outline-none focus:border-green-400">
            </div>
            <button type="submit"
              class="w-full bg-green-500 hover:bg-green-400 text-white font-bold py-4 rounded-2xl transition-colors text-lg">
              Get My Fleet ROI Report →
            </button>
            <p class="text-xs text-slate-400 text-center">No spam. No sales pressure. Just real numbers.</p>
          </form>
        </div>
        <div x-show="sent" class="bg-green-500/20 border border-green-400/30 rounded-2xl p-8 text-center">
          <div class="text-5xl mb-4">✅</div>
          <h3 class="text-2xl font-black mb-2" x-text="'Thank you, ' + (form.contact || 'there') + '!'"></h3>
          <p class="text-green-200">Your fleet enquiry is received. Our specialist will contact you within 4 business hours with a custom EV fleet ROI report.</p>
        </div>
      </div>
    </section>

  </div>
</div>

<script>
function fleetCalc() {
  return {
    inputs: {
      numVehicles:        50,
      vehicleType:        '2w_delivery',
      dailyKm:            40,
      workDays:           26,
      fuelPrice:          105,
      mileage:            35,
      elecRate:           8,
      evEfficiency:       5,
      petrolMaintenance:  15000,
      evMaintenance:      3000,
      extraCostPerVehicle:120000,
      fameSubsidy:        50000,
    },

    vehicleTypes: [
      { id: '2w_delivery', label: '2W Delivery',    sub: 'Scooter / e-bike', icon: '🛵', fameSubsidy: 10000  },
      { id: '3w_cargo',    label: '3W Auto/Cargo',  sub: 'E-rickshaw / L5',  icon: '🛺', fameSubsidy: 50000  },
      { id: '4w_cab',      label: '4W Cab/Corp.',   sub: 'Car / MUV',        icon: '🚗', fameSubsidy: 150000 },
      { id: 'lcv',         label: 'LCV',            sub: 'Light comm. van',  icon: '🚚', fameSubsidy: 150000 },
    ],

    r: {},

    init() { this.calc(); },

    calc() {
      const n            = Number(this.inputs.numVehicles);
      const dailyKm      = Number(this.inputs.dailyKm);
      const workDays     = Number(this.inputs.workDays);
      const fuelPrice    = Number(this.inputs.fuelPrice);
      const mileage      = Number(this.inputs.mileage);
      const elecRate     = Number(this.inputs.elecRate);
      const evEff        = Number(this.inputs.evEfficiency);
      const petrolMaint  = Number(this.inputs.petrolMaintenance);
      const evMaint      = Number(this.inputs.evMaintenance);
      const extraCost    = Number(this.inputs.extraCostPerVehicle);
      const fameSubsidy  = Number(this.inputs.fameSubsidy);

      const monthlyKmPerVehicle   = dailyKm * workDays;
      const annualKmPerVehicle    = monthlyKmPerVehicle * 12;

      // Per-vehicle monthly costs
      const petrolFuelMonthly = (monthlyKmPerVehicle / mileage) * fuelPrice;
      const evFuelMonthly     = (monthlyKmPerVehicle / evEff) * elecRate;

      const fuelSavingPerVehicleMonthly = petrolFuelMonthly - evFuelMonthly;
      const maintSavingPerVehicleMonthly = (petrolMaint - evMaint) / 12;
      const totalSavingPerVehicleMonthly = fuelSavingPerVehicleMonthly + maintSavingPerVehicleMonthly;

      const perVehicleDailySaving    = totalSavingPerVehicleMonthly / workDays;
      const perVehicleMonthlySaving  = totalSavingPerVehicleMonthly;
      const perVehicleAnnualSaving   = totalSavingPerVehicleMonthly * 12;

      const monthlyFuelSaving  = fuelSavingPerVehicleMonthly * n;
      const monthlyMaintSaving = maintSavingPerVehicleMonthly * n;
      const totalMonthlySaving = totalSavingPerVehicleMonthly * n;
      const annualSaving       = totalMonthlySaving * 12;
      const fiveYearSaving     = annualSaving * 5;
      const dailySaving        = totalMonthlySaving / workDays;

      // Payback
      const netExtraPerVehicle    = Math.max(0, extraCost - fameSubsidy);
      const netExtraInvestment    = netExtraPerVehicle * n;
      const breakEvenMonths       = totalMonthlySaving > 0 ? Math.ceil(netExtraInvestment / totalMonthlySaving) : 999;
      const fiveYearNetProfit     = fiveYearSaving - netExtraInvestment;

      // CO2
      const litresSaved  = Math.round(annualKmPerVehicle * n / mileage);
      const co2Annual    = Number(((annualKmPerVehicle * n * 0.12 - annualKmPerVehicle * n * 0.05) / 1000).toFixed(1));

      this.r = {
        monthlyFuelSaving, monthlyMaintSaving, totalMonthlySaving, annualSaving, fiveYearSaving,
        dailySaving, perVehicleDailySaving, perVehicleMonthlySaving, perVehicleAnnualSaving,
        netExtraInvestment, breakEvenMonths, fiveYearNetProfit,
        co2Annual, litresSaved
      };
    },

    fmt(n) {
      if (!n && n !== 0) return '₹0';
      const abs = Math.abs(Math.round(n));
      if (abs >= 10000000) return (n < 0 ? '-' : '') + '₹' + (abs / 10000000).toFixed(2) + 'Cr';
      if (abs >= 100000)   return (n < 0 ? '-' : '') + '₹' + (abs / 100000).toFixed(2) + 'L';
      if (abs >= 1000)     return (n < 0 ? '-' : '') + '₹' + (abs / 1000).toFixed(1) + 'K';
      return (n < 0 ? '-' : '') + '₹' + abs.toLocaleString('en-IN');
    }
  }
}
</script>

<?= $this->endSection() ?>
