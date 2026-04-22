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

  /* Modal overlay */
  #afc-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 99999;
    background: rgba(0,0,0,0.55);
    backdrop-filter: blur(4px);
    align-items: center;
    justify-content: center;
    padding: 16px;
  }
  #afc-modal-overlay.afc-open {
    display: flex;
  }

  /* Error modal overlay */
  #afc-error-overlay {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 100000;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(4px);
    align-items: center;
    justify-content: center;
    padding: 16px;
  }
  #afc-error-overlay.afc-open {
    display: flex;
  }
  #afc-error-modal { animation: afcSlideUp 0.4s cubic-bezier(0.22,1,0.36,1) both; }

  /* Animate in */
  #afc-modal { animation: afcSlideUp 0.45s cubic-bezier(0.22,1,0.36,1) both; }
  @keyframes afcSlideUp {
    from { opacity:0; transform: translateY(32px) scale(0.97); }
    to   { opacity:1; transform: translateY(0) scale(1); }
  }

  /* Success circle pulse */
  #afc-status-icon-wrap .afc-ring {
    animation: afcPulseRing 2s ease-out infinite;
  }
  @keyframes afcPulseRing {
    0%   { transform: scale(1); opacity:0.5; }
    70%  { transform: scale(1.25); opacity:0; }
    100% { transform: scale(1.25); opacity:0; }
  }

  /* Divider row hover */
  .dim-row:hover { background: #f8fafb; border-radius: 8px; transition: background 0.2s; }

  /* App buttons shine effect */
  .app-btn::after {
    content:'';
    position:absolute; top:0; left:-75%;
    width:50%; height:100%;
    background: linear-gradient(120deg, transparent 0%, rgba(255,255,255,0.18) 50%, transparent 100%);
    transition: left 0.55s ease;
  }
  .app-btn:hover::after { left:125%; }
  .app-btn { position:relative; overflow:hidden; }
</style>

<div id="afc-calculator" class="flex flex-col items-center justify-center border! border-[#E9B535]! rounded-2xl! px-2! py-5! max-w-xl mx-auto h-auto bg-[#2a1f14]! text-[#f0e6d3]! w-full">

  <div class="w-full px-2 pt-2 pb-4">

    <div class="text-center text-2xl font-bold mb-4 tracking-wide text-[#f0e6d3]">Check Appliance Fit</div>

    <form action="submit" class="flex flex-col gap-4">

      <div class="flex flex-col gap-2">
        <div class="text-base font-semibold text-[#f0e6d3]!">Step 1: Select Appliance Type</div>
        <select
          name="appliance_type"
          id="afc-appliance-type"
          class="afc-select w-full bg-[#3a2a1a]! text-[#f0e6d3]! border! border-[#6b4f2c]! rounded-xl px-4 py-3 text-base font-medium cursor-pointer outline-none focus:border-[#E9B535] transition-colors"
        >
          <option value="">Select Appliance Type</option>
          <option value="dishwasher">Dishwasher</option>
          <option value="refrigerator">Refrigerator</option>
          <option value="wall-oven">Wall Oven</option>
        </select>
      </div>

      <div class="flex flex-col gap-2 mt-1">
        <div class="text-base font-semibold text-[#f0e6d3]! my-1">Step 2: Enter Product dimension</div>
        <div class="text-sm text-[#9a8870]! text-left">Enter your product dimensions manually (take less than 60 seconds).</div>

        <div class="border! border-[#E9B535]! px-3! py-3! rounded-2xl! bg-[#E9B535]/5!">
          <div class="text-sm font-semibold text-[#f0e6d3]! mb-2">Use exact measurements (example: 30.5)</div>
          <div class="grid grid-cols-3 border! border-[#5a3f20]! rounded-lg! overflow-hidden">
            <div class="relative flex flex-col border-r! border-[#5a3f20]!">
              <div class="text-base font-medium text-[#c8b99a]! px-2.5 py-2 border-b! border-[#5a3f20]! bg-white/3">Height</div>
              <input type="number" name="product_height" id="afc-prod-height" placeholder="Ex: 32.5" min="0" step="0.1" onkeypress="return /[0-9.]/i.test(event.key)"
                class="afc-input bg-transparent! border-none! outline-none text-[#f0e6d3]! text-base font-medium px-2.5 pr-8 py-2 w-full" />
                <div class="absolute right-2.5 bottom-2 text-base text-[#c8b99a]! pointer-events-none">in</div>
            </div>
            <div class="relative flex flex-col border-r! border-[#5a3f20]!">
              <div class="text-base font-medium text-[#c8b99a]! px-2.5 py-2 border-b! border-[#5a3f20]! bg-white/3">Width</div>
              <input type="number" name="product_width" id="afc-prod-width" placeholder="Ex: 30" min="0" step="0.1" onkeypress="return /[0-9.]/i.test(event.key)"
                class="afc-input bg-transparent! border-none! outline-none text-[#f0e6d3]! text-base font-medium px-2.5 pr-8 py-2 w-full" />
                <div class="absolute right-2.5 bottom-2 text-base text-[#c8b99a]! pointer-events-none">in</div>
            </div>
            <div class="relative flex flex-col">
              <div class="text-base font-medium text-[#c8b99a]! px-2.5 py-2 border-b! border-[#5a3f20]! bg-white/3">Depth</div>
              <input type="number" name="product_depth" id="afc-prod-depth" placeholder="Ex: 30.2" min="0" step="0.1" onkeypress="return /[0-9.]/i.test(event.key)"
                class="afc-input bg-transparent! border-none! outline-none text-[#f0e6d3]! text-base font-medium px-2.5 pr-8 py-2 w-full" />
                <div class="absolute right-2.5 bottom-2 text-base text-[#c8b99a]! pointer-events-none">in</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Step 3 -->
      <div class="flex flex-col gap-2">
        <div class="text-base font-semibold text-[#f0e6d3]! my-1">Step 3: Enter Space Dimensions</div>
        <div class="border! border-[#E9B535]! px-3! py-3! rounded-2xl! bg-[#E9B535]/5!">
          <div class="text-sm font-semibold text-[#f0e6d3]! mb-2">Installation Space Dimensions</div>
          <div class="grid grid-cols-3 border! border-[#5a3f20]! rounded-lg! overflow-hidden">
            <div class="relative flex flex-col border-r! border-[#5a3f20]!">
              <div class="text-base font-medium text-[#c8b99a]! px-2.5 py-2 border-b! border-[#5a3f20]! bg-white/3">Height</div>
              <input type="number" name="space_height" id="afc-space-height" placeholder="Ex: 34" min="0" step="0.1" onkeypress="return /[0-9.]/i.test(event.key)"
                class="afc-input bg-transparent! border-none! outline-none text-[#f0e6d3]! text-base font-medium px-2.5 pr-8 py-2 w-full" />
              <div class="absolute right-2.5 bottom-2 text-base text-[#c8b99a]! pointer-events-none">in</div>
            </div>
            <div class="relative flex flex-col border-r! border-[#5a3f20]!">
              <div class="text-base font-medium text-[#c8b99a]! px-2.5 py-2 border-b! border-[#5a3f20]! bg-white/3">Width</div>
              <input type="number" name="space_width" id="afc-space-width" placeholder="Ex: 30" min="0" step="0.1" onkeypress="return /[0-9.]/i.test(event.key)"
                class="afc-input bg-transparent! border-none! outline-none text-[#f0e6d3]! text-base font-medium px-2.5 pr-8 py-2 w-full" />
              <div class="absolute right-2.5 bottom-2 text-base text-[#c8b99a]! pointer-events-none">in</div>
            </div>
            <div class="relative flex flex-col">
              <div class="text-base font-medium text-[#c8b99a]! px-2.5 py-2 border-b! border-[#5a3f20]! bg-white/3">Depth</div>
              <input type="number" name="space_depth" id="afc-space-depth" placeholder="Ex: 24" min="0" step="0.1" onkeypress="return /[0-9.]/i.test(event.key)"
                class="afc-input bg-transparent! border-none! outline-none text-[#f0e6d3]! text-base font-medium px-2.5 pr-8 py-2 w-full" />
              <div class="absolute right-2.5 bottom-2 text-base text-[#c8b99a]! pointer-events-none">in</div>
            </div>
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

        <!-- Error UI (now a modal, no inline div needed) -->

      </div>
    </form>
  </div>

</div>


<!-- ══════════════════════════════════════════════════════ -->
<!--  RESULT MODAL OVERLAY                                  -->
<!-- ══════════════════════════════════════════════════════ -->
<div id="afc-modal-overlay">

  <div id="afc-modal" class="bg-[#F5F6FA]! relative min-w-sm w-full max-w-lg rounded-2xl px-3.5 py-4 shadow-2xl shadow-slate-200/80 overflow-hidden max-h-[90vh] overflow-y-auto">

    <!-- close button -->
    <div id="afc-modal-close" class="bg-gray-700 p-2 rounded-full cursor-pointer shadow-2xl shadow-slate-200/80 absolute top-2 right-2 z-50">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
    </div>

    <!-- ── Header ── -->
    <div class="relative flex items-center gap-3 px-5 pt-5 pb-4 border-b border-slate-100 shadow-2xl bg-white shadow-slate-200/80 mb-4 rounded-2xl">
      <div id="afc-modal-appliance-icon" class="w-11 h-11 rounded-2xl bg-slate-100 flex items-center justify-center shadow-inner shrink-0">
        <!-- Appliance icon injected by JS -->
      </div>
      <div>
        <div id="afc-modal-appliance-type" class="text-[15px] font-700 text-slate-800 font-semibold leading-tight">—</div>
        <div id="afc-modal-date" class="text-[11px] text-slate-400 mt-0.5">—</div>
      </div>
    </div>

    <!-- ── Fit Status Banner ── -->
    <div id="afc-modal-banner" class="flex flex-col items-center gap-3 pt-6 pb-5 rounded-2xl mb-4">
      <div id="afc-status-icon-wrap" class="relative flex items-center justify-center">
        <!-- pulse ring -->
        <div id="afc-status-ring" class="afc-ring absolute w-16 h-16 rounded-full opacity-40"></div>
        <!-- solid circle -->
        <div id="afc-status-circle" class="relative z-10 w-14 h-14 rounded-full flex items-center justify-center shadow-lg">
          <!-- icon injected by JS -->
        </div>
      </div>
      <div class="text-center">
        <div id="afc-status-text" class="text-[15px] font-semibold text-slate-700">
          Fit Status : <span id="afc-status-label" class="inline-flex items-center gap-1"></span>
        </div>
        <div id="afc-status-sub" class="text-[12px] text-slate-400 mt-1 leading-snug"></div>
      </div>
    </div>

    <!-- ── Dimensions Comparison ── -->
    <div class="pb-4">
      <div class="text-[13px] font-semibold text-slate-500 uppercase tracking-wider mb-3">Dimensions Comparison</div>

      <div class="flex items-center justify-center gap-2">

        <!-- Product Dimensions Card -->
        <div class="flex-1 items-center rounded-2xl border border-slate-100 bg-slate-50 mb-3 overflow-hidden">
          <div class="px-4 py-2.5 bg-white border-b border-slate-100">
            <span class="text-[13px] font-semibold text-sky-600">Product Dimensions</span>
          </div>
          <div class="px-4 py-1">
            <div class="dim-row flex justify-between items-center py-2.5 border-b border-slate-100 px-1">
              <div class="text-[13px] text-slate-500">Height</div>
              <div id="afc-prod-h-display" class="text-[13px] font-semibold text-slate-800">—</div>
            </div>
            <div class="dim-row flex justify-between items-center py-2.5 border-b border-slate-100 px-1">
              <div class="text-[13px] text-slate-500">Width</div>
              <div id="afc-prod-w-display" class="text-[13px] font-semibold text-slate-800">—</div>
            </div>
            <div class="dim-row flex justify-between items-center py-2.5 px-1">
              <div class="text-[13px] text-slate-500">Depth</div>
              <div id="afc-prod-d-display" class="text-[13px] font-semibold text-slate-800">—</div>
            </div>
          </div>
        </div>

        <!-- Cutout Dimensions Card -->
        <div class="flex-1 items-center rounded-2xl border border-amber-100 bg-amber-50/40 mb-3 overflow-hidden">
          <div class="px-4 py-2.5 bg-white border-b border-amber-100">
            <span class="text-[13px] font-semibold text-amber-500">Cutout Dimensions</span>
          </div>
          <div class="px-4 py-1">
            <div class="dim-row flex justify-between items-center py-2.5 border-b border-amber-100/60 px-1">
              <div class="text-[13px] text-slate-500">Height</div>
              <div id="afc-space-h-display" class="text-[13px] font-semibold text-slate-800">—</div>
            </div>
            <div class="dim-row flex justify-between items-center py-2.5 border-b border-amber-100/60 px-1">
              <div class="text-[13px] text-slate-500">Width</div>
              <div id="afc-space-w-display" class="text-[13px] font-semibold text-slate-800">—</div>
            </div>
            <div class="dim-row flex justify-between items-center py-2.5 px-1">
              <div class="text-[13px] text-slate-500">Depth</div>
              <div id="afc-space-d-display" class="text-[13px] font-semibold text-slate-800">—</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Clearance Notice -->
       <!--
      <div id="afc-clearance-notice" class="flex items-start gap-3 rounded-2xl px-4 py-3">
        <div id="afc-notice-icon-wrap" class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center mt-0.5">
          <span id="afc-notice-icon-char" class="text-white text-[11px] font-bold"></span>
        </div>
        <div>
          <div id="afc-notice-title" class="text-[13px] font-semibold"></div>
          <div id="afc-notice-sub" class="text-[12px] mt-0.5 leading-snug"></div>
        </div>
      </div>
-->
    </div>

    <!-- ── App Store CTA Section ── -->
    <div class="bg-linear-to-br from-slate-800 to-slate-900 mb-5 rounded-2xl px-4 pt-4 pb-4 mt-2">
      <div class="flex items-center gap-2 mb-1">
        <div class="w-7 h-7 rounded-lg bg-white/10 flex items-center justify-center">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#a5f3fc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
          </svg>
        </div>
        <div class="text-[11px] font-semibold text-cyan-300 uppercase tracking-widest">Free Fit Check</div>
      </div>
      <div class="text-[13px] text-white font-medium leading-snug mb-3.5">Run 1 <span class="text-cyan-300 font-bold">FREE</span> Fit Check in the app — takes 30 seconds</div>

      <div class="flex items-center justify-center gap-2">
        <!-- Apple Store Button -->
        <a href="https://apps.apple.com/jp/app/appliance-fit-check/id6754309083?l=en-US" target="_blank" rel="noopener noreferrer" class="app-btn! flex-1! flex! items-center! justify-center! gap-3! bg-white! rounded-xl! px-3.5! py-2.5! cursor-pointer! hover:bg-slate-50! transition-colors! shadow-md! shadow-black/20! no-underline!">
          <div class="flex-shrink-0">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="#1a1a1a">
              <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
            </svg>
          </div>
          <div>
            <div class="text-[9px] text-slate-400 font-medium leading-none mb-0.5">Download on the</div>
            <div class="text-[14px] font-bold text-slate-900 leading-tight">App Store</div>
          </div>
        </a>

        <!-- Google Play Button -->
        <div id="button-google-play" class="app-btn flex-1 flex items-center justify-center gap-3 bg-white rounded-xl px-3.5 py-2.5 cursor-pointer hover:bg-slate-50 transition-colors shadow-md shadow-black/20">
          <div class="flex-shrink-0">
            <svg width="20" height="20" viewBox="0 0 24 24">
              <path d="M3,20.5v-17C3,2.91,3.81,2.5,4.5,3l14,8.5L4.5,20C3.81,21.5,3,21.09,3,20.5z" fill="#4CAF50"/>
              <path d="M3,3.5l8.5,8.5L18.5,11.5L4.5,3C3.81,2.5,3,2.91,3,3.5z" fill="#F44336"/>
              <path d="M3,20.5c0,0.59,0.81,1,1.5,0.5L18.5,12.5L11.5,12L3,20.5z" fill="#2196F3"/>
              <path d="M11.5,12l7,0.5c0.46-0.28,0.46-0.72,0-1L11.5,12z" fill="#FFC107"/>
            </svg>
          </div>
          <div>
            <div class="text-[9px] text-slate-400 font-medium leading-none mb-0.5">Get it on</div>
            <div class="text-[14px] font-bold text-slate-900 leading-tight">Google Play</div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── Export PDF Button ── -->
    <div class="pb-2">
      <div class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl border border-red-100 bg-red-50 hover:bg-red-100 transition-colors group cursor-pointer">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
          <line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/>
        </svg>
        <span class="text-[13px] font-semibold text-red-500 group-hover:text-red-600 transition-colors">Export as pdf</span>
      </div>
    </div>

  </div><!-- /#afc-modal -->
</div><!-- /#afc-modal-overlay -->


<!-- ══════════════════════════════════════════════════════ -->
<!--  ERROR MODAL OVERLAY                                   -->
<!-- ══════════════════════════════════════════════════════ -->
<div id="afc-error-overlay">
  <div id="afc-error-modal" class="relative bg-white w-full max-w-sm rounded-2xl px-6 py-7 shadow-2xl shadow-black/20 text-center">

    <!-- Close button -->
    <div id="afc-error-modal-close" class="bg-gray-700 p-2 rounded-full cursor-pointer absolute top-2 right-2 z-50">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
    </div>

    <!-- Warning icon -->
    <div class="relative flex items-center justify-center mb-4">
      <div class="absolute w-16 h-16 rounded-full bg-rose-200 opacity-50" style="animation: afcPulseRing 2s ease-out infinite;"></div>
      <div class="relative z-10 w-14 h-14 rounded-full flex items-center justify-center shadow-lg" style="background: linear-gradient(to bottom right, #f43f5e, #be123c); box-shadow: 0 10px 15px -3px #fda4af;">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
          <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
        </svg>
      </div>
    </div>

    <!-- Title -->
    <div class="text-[16px] font-bold text-slate-800 mb-1">Missing Information</div>

    <!-- Dynamic message -->
    <div id="afc-error-msg" class="text-[13px] text-slate-500 leading-relaxed mb-5"></div>

    <!-- Dismiss button -->
    <div id="afc-error-dismiss" class="cursor-pointer bg-rose-500 hover:bg-rose-600 transition-colors text-white text-[13px] font-semibold px-6 py-2.5 rounded-xl inline-flex items-center gap-2">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
      Got it
    </div>

  </div>
</div><!-- /#afc-error-overlay -->