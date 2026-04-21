<?php
/**
 * Appliance Fit Check - Calculator Template
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div id="afc-calculator" class="max-w-xl mx-auto my-8 overflow-hidden transition-all duration-500 bg-white/80 backdrop-blur-md rounded-3xl shadow-2xl border border-indigo-100 font-sans" role="main" aria-label="<?php echo $calculator_title; ?>">
    
    <!-- Premium Header -->
    <div class="relative px-8 py-10 overflow-hidden bg-gradient-to-br from-indigo-700 via-indigo-600 to-blue-600">
        <!-- Abstract Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 0L100 0L100 100L0 100Z" fill="url(#grid)" />
                <defs>
                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5" />
                    </pattern>
                </defs>
            </svg>
        </div>

        <div class="relative flex flex-col items-center gap-4 text-center">
            <div class="p-3 bg-white/20 backdrop-blur-lg rounded-2xl border border-white/30 shadow-lg">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-extrabold tracking-tight text-white mb-1 leading-tight">
                <?php echo $calculator_title; ?>
            </h2>
            <p class="text-indigo-100 text-sm font-medium opacity-90">Precision mapping for your master kitchen suite</p>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="p-8 space-y-10 bg-gradient-to-b from-white to-indigo-50/30">
        
        <!-- Unit Toggle -->
        <div class="flex items-center justify-between p-4 bg-indigo-50/50 rounded-2xl border border-indigo-100/50">
            <span class="text-sm font-bold text-indigo-900 tracking-wide uppercase"><?php _e( 'Display Units', 'appliance-fit-check' ); ?></span>
            <div class="flex items-center gap-3">
                <button type="button" id="afc-unit-toggle" class="relative inline-flex h-8 w-16 items-center rounded-full bg-indigo-200 transition-colors focus:outline-none ring-2 ring-offset-2 ring-indigo-500 focus:ring-indigo-600" role="switch" aria-checked="false">
                    <span class="sr-only">Toggle Units</span>
                    <span id="afc-unit-knob" class="inline-block h-6 w-6 transform rounded-full bg-white shadow-md transition-transform duration-200 translate-x-1"></span>
                </button>
                <div class="flex flex-col text-xs font-black text-indigo-900 leading-none">
                    <span id="afc-unit-label" class="uppercase">Inches</span>
                </div>
            </div>
        </div>

        <!-- Step 1: Selection -->
        <div class="space-y-4 group">
            <div class="flex items-center gap-3">
                <span class="flex items-center justify-center w-8 h-8 text-xs font-black text-white bg-indigo-600 rounded-lg shadow-indigo-200 shadow-lg">01</span>
                <label class="text-lg font-bold text-gray-900" for="afc-appliance-type">Select Appliance Type</label>
            </div>
            <div class="relative">
                <select id="afc-appliance-type" class="w-full px-5 py-4 transition-all bg-white border-2 border-gray-100 rounded-2xl shadow-sm appearance-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none text-gray-700 font-semibold text-base cursor-pointer">
                    <option value=""><?php _e( '— Choose an appliance —', 'appliance-fit-check' ); ?></option>
                    <option value="refrigerator"><?php _e( 'Refrigerator', 'appliance-fit-check' ); ?></option>
                    <option value="dishwasher"><?php _e( 'Dishwasher', 'appliance-fit-check' ); ?></option>
                    <option value="wall_oven"><?php _e( 'Wall Oven', 'appliance-fit-check' ); ?></option>
                </select>
                <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
        </div>

        <!-- Step 2: Product Dimensions -->
        <div id="afc-step-product" class="space-y-6 hidden animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="flex items-center gap-3">
                <span class="flex items-center justify-center w-8 h-8 text-xs font-black text-white bg-indigo-600 rounded-lg shadow-indigo-200 shadow-lg">02</span>
                <p class="text-lg font-bold text-gray-900">Appliance Dimensions</p>
            </div>
            
            <div class="grid grid-cols-3 gap-4">
                <?php foreach(['height' => 'Height', 'width' => 'Width', 'depth' => 'Depth'] as $key => $label): ?>
                <div class="p-4 bg-white border-2 border-gray-100 rounded-2xl transition-all focus-within:border-indigo-500 focus-within:shadow-md focus-within:ring-4 focus-within:ring-indigo-500/5 group/dim">
                    <label for="afc-prod-<?php echo $key; ?>" class="block text-[10px] font-black tracking-widest text-gray-400 uppercase mb-2 group-focus-within/dim:text-indigo-600 transition-colors"><?php echo $label; ?></label>
                    <div class="flex items-baseline gap-1">
                        <input type="number" id="afc-prod-<?php echo $key; ?>" class="w-full text-xl font-bold bg-transparent border-none outline-none text-gray-900 p-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" placeholder="0.0" min="0" step="0.1">
                        <span class="afc-unit-marker text-xs font-bold text-indigo-300">in</span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Step 3: Space Dimensions -->
        <div id="afc-step-space" class="space-y-6 hidden animate-in fade-in slide-in-from-bottom-4 duration-500 delay-75">
            <div class="flex items-center gap-3">
                <span class="flex items-center justify-center w-8 h-8 text-xs font-black text-white bg-indigo-600 rounded-lg shadow-indigo-200 shadow-lg">03</span>
                <p class="text-lg font-bold text-gray-900">Space / Opening Dimensions</p>
            </div>
            
            <div class="grid grid-cols-3 gap-4">
                <?php foreach(['height' => 'Height', 'width' => 'Width', 'depth' => 'Depth'] as $key => $label): ?>
                <div class="p-4 bg-white border-2 border-gray-100 rounded-2xl transition-all focus-within:border-indigo-500 focus-within:shadow-md focus-within:ring-4 focus-within:ring-indigo-500/5 group/dim">
                    <label for="afc-space-<?php echo $key; ?>" class="block text-[10px] font-black tracking-widest text-gray-400 uppercase mb-2 group-focus-within/dim:text-indigo-600 transition-colors"><?php echo $label; ?></label>
                    <div class="flex items-baseline gap-1">
                        <input type="number" id="afc-space-<?php echo $key; ?>" class="w-full text-xl font-bold bg-transparent border-none outline-none text-gray-900 p-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" placeholder="0.0" min="0" step="0.1">
                        <span class="afc-unit-marker text-xs font-bold text-indigo-300">in</span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Context Hint & Guide -->
            <div class="flex items-center gap-4 p-5 bg-blue-50/60 border border-blue-100 rounded-2xl">
                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 shadow-inner">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="flex-grow">
                    <p class="text-sm font-semibold text-blue-900 mb-1 leading-tight"><?php _e( 'Precision Matters', 'appliance-fit-check' ); ?></p>
                    <p class="text-xs text-blue-700/80 leading-relaxed"><?php _e( 'Measure the actual gap, not your existing appliance. Small decimals (e.g. 30.2) are supported.', 'appliance-fit-check' ); ?></p>
                </div>
            </div>
        </div>

        <!-- Call to Action / Check Button -->
        <div id="afc-btn-wrap" class="hidden animate-in zoom-in-95 duration-300">
            <button type="button" id="afc-check-btn" class="group relative w-full py-5 px-6 bg-indigo-600 rounded-2xl text-white font-black text-xl tracking-widest uppercase overflow-hidden shadow-xl shadow-indigo-200 transition-all active:scale-95 hover:bg-indigo-700">
                <span class="relative z-10 flex items-center justify-center gap-3">
                    <?php _e( 'Analyze Fit', 'appliance-fit-check' ); ?>
                    <svg class="w-6 h-6 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </span>
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
            </button>
        </div>

        <!-- Result Section -->
        <div id="afc-result" class="hidden space-y-8 animate-in slide-in-from-top-6 duration-500">
            
            <!-- Result Card -->
            <div id="afc-result-badge" class="relative flex flex-col items-center gap-4 p-8 rounded-3xl border-4 shadow-xl overflow-hidden text-center">
                <div id="afc-result-icon" class="w-20 h-20 rounded-full flex items-center justify-center text-4xl shadow-lg ring-8 ring-white/50 bg-white"></div>
                <div id="afc-result-label" class="text-2xl font-black tracking-tight leading-tight"></div>
            </div>

            <!-- Detail Analysis Grid -->
            <div id="afc-clearance" class="bg-white border-2 border-gray-100 rounded-3xl p-6 shadow-sm overflow-hidden">
                <h3 class="text-[10px] font-black tracking-[0.2em] text-gray-400 uppercase mb-6 px-2"><?php _e( 'Precision Analysis Breakdown', 'appliance-fit-check' ); ?></h3>
                <div class="space-y-4">
                    <?php foreach(['height' => 'Height', 'width' => 'Width', 'depth' => 'Depth'] as $key => $label): ?>
                    <div id="afc-clr-<?php echo $key; ?>" class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-50 transition-all hover:bg-white hover:border-gray-100 hover:shadow-sm"></div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Professional Recommendation CTA -->
            <div class="bg-gradient-to-br from-indigo-900 to-blue-900 rounded-3xl p-8 text-center shadow-2xl relative overflow-hidden group">
                <!-- Inner Glow -->
                <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-64 h-64 bg-indigo-500/20 blur-[80px]"></div>
                
                <h4 class="relative z-10 text-white font-bold text-lg mb-2 uppercase tracking-wide"><?php _e( 'Unlock Professional Precision', 'appliance-fit-check' ); ?></h4>
                <p class="relative z-10 text-indigo-200/90 text-sm mb-8 leading-relaxed"><?php _e( 'Run 1 FREE AI-powered fit consultation with AR mapping in our mobile app.', 'appliance-fit-check' ); ?></p>
                
                <div class="relative z-10 flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo $app_store_url; ?>" target="_blank" class="flex items-center justify-center gap-3 px-6 py-4 bg-white text-indigo-900 rounded-2xl font-bold text-sm shadow-xl hover:scale-105 active:scale-95 transition-all">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 814 1000"><path d="M788.1 340.9c-5.8 4.5-108.2 62.2-108.2 190.5 0 148.4 130.3 200.9 134.2 202.2-.6 3.2-20.7 71.9-68.7 141.9-42.8 61.6-87.5 123.1-155.5 123.1s-85.5-39.5-164-39.5c-76 0-103.7 40.8-165.9 40.8s-105-57.8-155.5-127.4C46 790.7 0 663 0 541.8c0-207.3 134.4-316.9 266.5-316.9 99.7 0 183.2 65.6 245.7 65.6 59.2 0 151.8-69.4 265.1-69.4zm-57.4-237.6c30.7-36.7 52.8-87.4 52.8-138.1 0-7.1-.6-14.3-1.9-20.1-49.4 1.9-107.8 33.7-142.5 75.6-27.2 30.8-55.1 81.7-55.1 133.1 0 7.7 1.3 15.5 1.9 18.1 3.2.6 8.4 1.3 13.6 1.3 44.3 0 99.3-29.5 131.2-69.9z"/></svg>
                        <span>App Store</span>
                    </a>
                    <a href="<?php echo $play_store_url; ?>" target="_blank" class="flex items-center justify-center gap-3 px-6 py-4 bg-white text-indigo-900 rounded-2xl font-bold text-sm shadow-xl hover:scale-105 active:scale-95 transition-all">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 512 512"><path d="M325.3 234.3L104.6 13l280.8 161.2-60.1 60.1zM47 0C34 6.8 25.3 19.2 25.3 35.3v441.3c0 16.1 8.7 28.5 21.7 35.3l256.6-256L47 0zm425.6 225.6l-58.9-34.1-65.7 64.5 65.7 64.5 60.1-34.1c17.1-9.8 17.1-26 0-35.3l-1.2-.5zM104.6 499l280.8-161.2-60.1-60.1L104.6 499z"/></svg>
                        <span>Google Play</span>
                    </a>
                </div>
            </div>

            <!-- Premium Reset -->
            <div class="text-center pt-4">
                <button type="button" id="afc-reset-btn" class="inline-flex items-center gap-2 text-indigo-600 font-bold hover:text-indigo-700 transition-colors py-2 px-4 rounded-xl hover:bg-indigo-50 border-2 border-transparent hover:border-indigo-100 uppercase tracking-widest text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <?php _e( 'Restart Analysis', 'appliance-fit-check' ); ?>
                </button>
            </div>
        </div>

        <!-- Modern Error Notification -->
        <div id="afc-error" class="hidden p-5 bg-rose-50 border border-rose-100 rounded-2xl animate-in shake-in duration-300" role="alert">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-rose-100 rounded-xl flex items-center justify-center text-rose-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div class="text-sm font-semibold text-rose-900 leading-tight"></div>
            </div>
        </div>

    </div>
</div>
