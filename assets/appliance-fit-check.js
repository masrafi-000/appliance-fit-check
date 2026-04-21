/**
 * Appliance Fit Check — Frontend Logic
 * Version: 1.2.0 (Target Design Match)
 */

(function () {
    'use strict';

    /* ------------------------------------------------------------------ */
    /*  Constants & Mock Data                                             */
    /* ------------------------------------------------------------------ */

    var MOCK_MODELS = {
        'WDT750SAHZ': { name: 'Whirlpool WDT750SAHZ', h: 34.5, w: 23.9, d: 24.5 },
        'LDF5545ST' : { name: 'LG LDF5545ST', h: 33.6, w: 23.8, d: 24.6 },
        'SHEM63W55N': { name: 'Bosch SHEM63W55N', h: 33.9, w: 23.6, d: 23.8 }
    };

    var RESULTS = {
        PERFECT : { 
            key: 'afc-perfect', label: 'Fits Perfectly', icon: '✓', rank: 0,
            classes: 'bg-emerald-500/10 border-emerald-500/50 text-emerald-100',
            iconClasses: 'bg-emerald-500 shadow-[0_0_15px_rgba(16,185,129,0.4)]'
        },
        TIGHT   : { 
            key: 'afc-tight', label: 'Tight Fit', icon: '!', rank: 1,
            classes: 'bg-amber-500/10 border-amber-500/50 text-amber-100',
            iconClasses: 'bg-amber-500 shadow-[0_0_15px_rgba(245,158,11,0.4)]'
        },
        MODIFY  : { 
            key: 'afc-modify', label: 'Adjustment Needed', icon: '⚒', rank: 2,
            classes: 'bg-orange-500/10 border-orange-500/50 text-orange-100',
            iconClasses: 'bg-orange-500 shadow-[0_0_15px_rgba(249,115,22,0.4)]'
        },
        NOFIT   : { 
            key: 'afc-nofit', label: 'Does Not Fit', icon: '✕', rank: 3,
            classes: 'bg-rose-500/10 border-rose-500/50 text-rose-100',
            iconClasses: 'bg-rose-500 shadow-[0_0_15px_rgba(244,63,94,0.4)]'
        }
    };

    /* ------------------------------------------------------------------ */
    /*  Logic                                                            */
    /* ------------------------------------------------------------------ */

    function classifyClearance(clearance) {
        if (clearance >= 0.25)                       return RESULTS.PERFECT;
        if (clearance >= 0.00 && clearance <= 0.24)  return RESULTS.TIGHT;
        if (clearance >= -0.25 && clearance <= -0.01) return RESULTS.MODIFY;
        return RESULTS.NOFIT;
    }

    function worstResult(results) {
        return results.reduce(function (worst, r) {
            return r.rank > worst.rank ? r : worst;
        }, results[0]);
    }

    function checkFit(product, space) {
        var dims = ['height', 'width', 'depth'];
        return {
            overall: worstResult(dims.map(function (dim) {
                return classifyClearance(Math.round((space[dim] - product[dim]) * 100) / 100);
            })),
            dimensions: dims.map(function (dim) {
                var clearance = Math.round((space[dim] - product[dim]) * 100) / 100;
                return { dim: dim, val: clearance, result: classifyClearance(clearance) };
            })
        };
    }

    /* ------------------------------------------------------------------ */
    /*  UI Helpers                                                       */
    /* ------------------------------------------------------------------ */

    function show(el) { if(el) el.classList.remove('hidden'); }
    function hide(el) { if(el) el.classList.add('hidden'); }

    function parseInput(val) {
        var v = parseFloat(val);
        // Reject non-numbers, negative numbers, or suspicious values (> 200)
        return (isNaN(v) || v < 0 || v > 200) ? null : v;
    }

    /* ------------------------------------------------------------------ */
    /*  Init                                                             */
    /* ------------------------------------------------------------------ */

    function init() {
        var wrapper = document.getElementById('afc-calculator');
        if (!wrapper) return;

        // Form elements
        var modelInput    = document.getElementById('afc-model-num');
        var lookupBtn     = document.getElementById('afc-lookup-btn');
        var modelFoundUI  = document.getElementById('afc-model-found');
        var foundName     = document.getElementById('afc-found-name');
        var fH = document.getElementById('afc-f-height'), fW = document.getElementById('afc-f-width'), fD = document.getElementById('afc-f-depth');

        var dimensionInputs = [
            document.getElementById('afc-prod-height'), document.getElementById('afc-prod-width'), document.getElementById('afc-prod-depth'),
            document.getElementById('afc-space-height'), document.getElementById('afc-space-width'), document.getElementById('afc-space-depth')
        ];
        
        var prodH = dimensionInputs[0], prodW = dimensionInputs[1], prodD = dimensionInputs[2];
        var spcH  = dimensionInputs[3], spcW  = dimensionInputs[4], spcD  = dimensionInputs[5];
        
        var checkBtn      = document.getElementById('afc-check-btn');
        var errorBox      = document.getElementById('afc-error');
        var resultBox     = document.getElementById('afc-result');
        var resetBtn      = document.getElementById('afc-reset-btn');

        // Results elements
        var resBadge = document.getElementById('afc-result-badge');
        var resIcon  = document.getElementById('afc-result-icon');
        var resLabel = document.getElementById('afc-result-label');

        /* ---- Sanitization Logic ---- */
        dimensionInputs.forEach(function(input) {
            if (!input) return;
            input.addEventListener('input', function() {
                // Remove anything that isn't a digit or decimal point
                var sanitized = this.value.replace(/[^0-9.]/g, '');
                
                // Prevent multiple decimals
                var parts = sanitized.split('.');
                if (parts.length > 2) {
                    sanitized = parts[0] + '.' + parts.slice(1).join('');
                }
                
                if (this.value !== sanitized) {
                    this.value = sanitized;
                }
                hide(errorBox);
            });
        });

        /* ---- Lookup Logic ---- */
        if (lookupBtn && modelInput) {
            lookupBtn.addEventListener('click', function () {
                hide(errorBox);
                var query = modelInput.value.trim().toUpperCase();
                
                if (MOCK_MODELS[query]) {
                    var model = MOCK_MODELS[query];
                    if (foundName) foundName.textContent = model.name;
                    if (fH) fH.textContent = model.h + ' in';
                    if (fW) fW.textContent = model.w + ' in';
                    if (fD) fD.textContent = model.d + ' in';
                    
                    // Auto-populate manual fields as well
                    if (prodH) prodH.value = model.h;
                    if (prodW) prodW.value = model.w;
                    if (prodD) prodD.value = model.d;
                    
                    show(modelFoundUI);
                } else {
                    hide(modelFoundUI);
                    if (errorBox) {
                        errorBox.textContent = 'Model not found. Please enter dimensions manually.';
                        show(errorBox);
                    }
                }
            });
        }

        /* ---- Check Fit ---- */
        if (checkBtn) {
            checkBtn.addEventListener('click', function () {
                hide(errorBox);
                hide(resultBox);

                var ph = parseInput(prodH.value), pw = parseInput(prodW.value), pd = parseInput(prodD.value);
                var sh = parseInput(spcH.value), sw = parseInput(spcW.value), sd = parseInput(spcD.value);

                if (ph === null || pw === null || pd === null || sh === null || sw === null || sd === null) {
                    if (errorBox) {
                        errorBox.textContent = 'Please enter valid dimensions between 0 and 200.';
                        show(errorBox);
                    }
                    return;
                }

                var fit = checkFit(
                    { height: ph, width: pw, depth: pd },
                    { height: sh, width: sw, depth: sd }
                );

                // Update UI
                if (resBadge) resBadge.className = 'p-6 rounded-2xl border-2 text-center space-y-2 transition-all duration-500 ' + fit.overall.classes;
                if (resIcon) {
                    resIcon.className  = 'w-12 h-12 mx-auto rounded-full flex items-center justify-center text-xl text-white ' + fit.overall.iconClasses;
                    resIcon.textContent = fit.overall.icon;
                }
                if (resLabel) resLabel.textContent = fit.overall.label;

                show(resultBox);
                resultBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            });
        }

        /* ---- Reset ---- */
        if (resetBtn) {
            resetBtn.addEventListener('click', function () {
                [modelInput, prodH, prodW, prodD, spcH, spcW, spcD].forEach(function(i) { if(i) i.value = ''; });
                hide(modelFoundUI);
                hide(resultBox);
                hide(errorBox);
                wrapper.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        }

        // Hide errors on input
        [modelInput, prodH, prodW, prodD, spcH, spcW, spcD].forEach(function(input) {
            if (input) {
                input.addEventListener('input', function() { hide(errorBox); });
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
