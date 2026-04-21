<?php
/**
 * Appliance Fit Check - Detailed Design Match Template
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<style>
  /* Only non-Tailwind-expressible styles */
  .afc-select {
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23E9B535' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
  }
  .afc-input::placeholder { color: #7a6a55; }
  .afc-select option { background-color: #3a2a1a; color: #f0e6d3; }

  /* Remove spin buttons from number inputs */
  input::-webkit-outer-spin-button,
  input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }
  input[type=number] {
    -moz-appearance: textfield;
  }
</style>

<div id="afc-calculator" class="flex flex-col items-center justify-center border! border-[#E9B535]! rounded-2xl! px-2! py-5! max-w-md mx-auto h-auto bg-[#2a1f14]! text-[#f0e6d3]! w-full">

  <div class="w-full px-2 py-4">

    <div class="text-center text-2xl font-bold mb-4 tracking-wide text-[#f0e6d3]">Check Appliance Fit</div>

    <form action="submit" class="flex flex-col gap-4">

      <div class="flex flex-col gap-2">
        <label for="afc-appliance-type" class="text-base font-semibold text-[#f0e6d3]!">Step 1: Select Appliance Type</label>
        <select
          name="appliance_type"
          id="afc-appliance-type"
          class="afc-select w-full bg-[#3a2a1a]! text-[#f0e6d3]! border! border-[#6b4f2c]! rounded-xl px-4 py-3 text-sm font-medium cursor-pointer outline-none focus:border-[#E9B535] transition-colors"
        >
          <option value="">Select Appliance Type</option>
          <option value="dishwasher">Dishwasher</option>
          <option value="refrigerator">Refrigerator</option>
        </select>
      </div>

      <div class="flex flex-col gap-2 mt-1">
        <label for="afc-prod-height" class="text-base font-semibold text-[#f0e6d3]! my-1">Step 2: Enter Product dimension</label>
        <div class="text-xs text-[#9a8870]! text-center">Enter your product dimensions manually (take less than 60 seconds).</div>

        <div class="border! border-[#E9B535]! px-3! py-3! rounded-2xl! bg-[#E9B535]/5!">
          <div class="text-xs font-semibold text-[#c8b99a]! mb-2">Use exact measurements (example: 30.5)</div>
          <div class="grid grid-cols-3 border! border-[#5a3f20]! rounded-lg! overflow-hidden">
            <div class="flex flex-col border-r! border-[#5a3f20]!">
              <div class="text-xs font-medium text-[#c8b99a]! px-2.5 py-2 border-b! border-[#5a3f20]! bg-white/3">Height</div>
              <input type="number" name="product_height" id="afc-prod-height" placeholder="Height"
                class="afc-input bg-transparent! border-none! outline-none text-[#f0e6d3]! text-sm font-medium px-2.5 py-2 w-full" />
            </div>
            <div class="flex flex-col border-r! border-[#5a3f20]!">
              <div class="text-xs font-medium text-[#c8b99a]! px-2.5 py-2 border-b! border-[#5a3f20]! bg-white/3">Width</div>
              <input type="number" name="product_width" id="afc-prod-width" placeholder="Width"
                class="afc-input bg-transparent! border-none! outline-none text-[#f0e6d3]! text-sm font-medium px-2.5 py-2 w-full" />
            </div>
            <div class="flex flex-col">
              <div class="text-xs font-medium text-[#c8b99a]! px-2.5 py-2 border-b! border-[#5a3f20]! bg-white/3">Depth</div>
              <input type="number" name="product_depth" id="afc-prod-depth" placeholder="Depth"
                class="afc-input bg-transparent! border-none! outline-none text-[#f0e6d3]! text-sm font-medium px-2.5 py-2 w-full" />
            </div>
          </div>
        </div>
      </div>

      <!-- Step 3 -->
      <div class="flex flex-col gap-2">
        <div class="text-base font-semibold text-[#f0e6d3]! my-1">Step 3: Enter Space Dimensions</div>

        <div class="border! border-[#E9B535]! rounded-2xl! py-4! px-3! flex flex-col gap-2.5 bg-[#E9B535]/3!">
          <div class="text-sm font-semibold text-[#f0e6d3]!">Installation Space Dimensions</div>

          <div class="border! border-[#5a3f20]! rounded-lg! overflow-hidden">
            <div class="text-xs font-medium text-[#c8b99a]! px-3 py-2 border-b! border-[#5a3f20]! bg-white/3">Height</div>
            <input type="number" name="space_height" id="afc-space-height" placeholder="Height"
              class="afc-input bg-transparent! border-none! outline-none text-[#f0e6d3]! text-sm font-medium px-3 py-2 w-full" />
          </div>

          <div class="border! border-[#5a3f20]! rounded-lg! overflow-hidden">
            <div class="text-xs font-medium text-[#c8b99a]! px-3 py-2 border-b! border-[#5a3f20]! bg-white/3">Width</div>
            <input type="number" name="space_width" id="afc-space-width" placeholder="Width"
              class="afc-input bg-transparent! border-none! outline-none text-[#f0e6d3]! text-sm font-medium px-3 py-2 w-full" />
          </div>

          <div class="border! border-[#5a3f20]! rounded-lg! overflow-hidden">
            <div class="text-xs font-medium text-[#c8b99a]! px-3 py-2 border-b! border-[#5a3f20]! bg-white/3">Depth</div>
            <input type="number" name="space_depth" id="afc-space-depth" placeholder="Depth"
              class="afc-input bg-transparent! border-none! outline-none text-[#f0e6d3]! text-sm font-medium px-3 py-2 w-full" />
          </div>
        </div>

        <div class="text-xs text-[#7a6a55]! text-center mt-1">
          Use decimals only (example: 30.5)
        </div>

        <div class="flex flex-row items-start gap-3 bg-white/6! p-3! rounded-2xl! mt-2 mb-4 min-h-[58px]">
          <div class="shrink-0 mt-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-[#E9B535]!">
              <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
            </svg>
          </div>
          <div class="text-sm text-[#c8b99a]! leading-relaxed">Measure the actual opening or installation space, not the old appliance</div>
        </div>

        <div id="afc-check-btn" class="bg-linear-to-l from-[#E9B535] to-[#B28E72] text-white! px-4 py-4 rounded-2xl text-base font-semibold flex items-center justify-center cursor-pointer tracking-wide shadow-[0_4px_20px_rgba(233,181,53,0.25)] hover:opacity-90 transition-opacity">
          Check My Fit
        </div>

        <!-- Error UI -->
        <div id="afc-error" class="hidden p-3 bg-red-900/20 border border-red-500/50 rounded-lg text-red-100! text-xs text-center font-medium mt-2"></div>

        <!-- Result Box (Initially hidden) -->
        <div id="afc-result" class="hidden pt-6 space-y-4">
             <div id="afc-result-badge" class="p-6 rounded-2xl border-2 text-center space-y-2">
                 <div id="afc-result-icon" class="w-12 h-12 mx-auto rounded-full flex items-center justify-center text-xl shadow-inner text-white"></div>
                 <h4 id="afc-result-label" class="text-lg font-black tracking-tight uppercase"></h4>
             </div>

             <!-- Clearance Detail Rows -->
             <div class="space-y-2">
               <div id="afc-clr-height"></div>
               <div id="afc-clr-width"></div>
               <div id="afc-clr-depth"></div>
             </div>
             
             <!-- Reset -->
             <button type="button" id="afc-reset-btn" class="w-full py-3 text-[#7a6a55] text-[10px] font-bold uppercase tracking-widest hover:text-white transition-colors">
                 Restart Analysis
             </button>
        </div>

      </div>
    </form>
  </div>

</div>