/**
 * Appliance Fit Check — Frontend Logic
 * Version: 1.1.0
 *
 * Fit Logic remains consistent:
 *   clearance >= 0.25           → Fits Perfectly
 *   0.00 <= clearance <= 0.24   → Tight Fit
 *   -0.25 <= clearance <= -0.01 → Requires Modification
 *   clearance <= -0.26          → Does Not Fit
 */

(function () {
    'use strict';

    /* ------------------------------------------------------------------ */
    /*  Constants                                                           */
    /* ------------------------------------------------------------------ */

    var RESULTS = {
        PERFECT : { 
            key: 'afc-perfect', 
            label: 'Fits Perfectly',       
            icon: '✓', 
            rank: 0,
            classes: 'bg-emerald-50 border-emerald-200 text-emerald-700',
            iconClasses: 'bg-emerald-500 text-white',
            tagClasses: 'bg-emerald-100 text-emerald-700'
        },
        TIGHT   : { 
            key: 'afc-tight',   
            label: 'Tight Fit',            
            icon: '!', 
            rank: 1,
            classes: 'bg-amber-50 border-amber-200 text-amber-700',
            iconClasses: 'bg-amber-500 text-white',
            tagClasses: 'bg-amber-100 text-amber-700'
        },
        MODIFY  : { 
            key: 'afc-modify',  
            label: 'Needs Adjustment', 
            icon: '⚒', 
            rank: 2,
            classes: 'bg-orange-50 border-orange-200 text-orange-700',
            iconClasses: 'bg-orange-500 text-white',
            tagClasses: 'bg-orange-100 text-orange-700'
        },
        NOFIT   : { 
            key: 'afc-nofit',   
            label: 'Does Not Fit',         
            icon: '✕', 
            rank: 3,
            classes: 'bg-rose-50 border-rose-200 text-rose-700',
            iconClasses: 'bg-rose-500 text-white',
            tagClasses: 'bg-rose-100 text-rose-700'
        }
    };

    var UNITS = {
        IN: { label: 'Inches', marker: 'in', factor: 1 },
        CM: { label: 'Centimeters', marker: 'cm', factor: 2.54 }
    };

    /* ------------------------------------------------------------------ */
    /*  State                                                               */
    /* ------------------------------------------------------------------ */

    var state = {
        unit: 'IN'
    };

    /* ------------------------------------------------------------------ */
    /*  Fit Logic                                                           */
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
        var dimensionResults = dims.map(function (dim) {
            var clearance = Math.round((space[dim] - product[dim]) * 100) / 100;
            return {
                dim       : dim.charAt(0).toUpperCase() + dim.slice(1),
                clearance : clearance,
                result    : classifyClearance(clearance)
            };
        });

        return {
            overall    : worstResult(dimensionResults.map(function (d) { return d.result; })),
            dimensions : dimensionResults
        };
    }

    /* ------------------------------------------------------------------ */
    /*  Helpers                                                             */
    /* ------------------------------------------------------------------ */

    function parseVal(el) {
        var v = parseFloat(el.value);
        if (isNaN(v)) return null;
        // If in CM, convert back to IN for internal calculation
        return state.unit === 'CM' ? v / UNITS.CM.factor : v;
    }

    function showEl(el)  { 
        if (!el) return;
        el.classList.remove('hidden'); 
    }
    function hideEl(el)  { 
        if (!el) return;
        el.classList.add('hidden'); 
    }

    function setError(el, msg) {
        var textEl = el.querySelector('.text-rose-900');
        if (textEl) textEl.textContent = msg;
        showEl(el);
        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function formatClearance(val) {
        // Convert internal IN value to display unit
        var displayVal = state.unit === 'CM' ? val * UNITS.CM.factor : val;
        var sign = val > 0 ? '+' : '';
        return sign + displayVal.toFixed(2) + ' ' + UNITS[state.unit].marker;
    }

    function buildClearanceRow(container, dimData) {
        container.innerHTML = 
            '<div class="flex items-center gap-3">' +
                '<span class="w-2 h-2 rounded-full ' + dimData.result.iconClasses.split(' ')[0] + '"></span>' +
                '<span class="font-bold text-gray-700">' + dimData.dim + '</span>' +
            '</div>' +
            '<div class="flex items-center gap-4">' +
                '<span class="font-black text-gray-900">' + formatClearance(dimData.clearance) + '</span>' +
                '<span class="text-[10px] font-black uppercase px-2 py-1 rounded-md ' + dimData.result.tagClasses + '">' + dimData.result.label + '</span>' +
            '</div>';
    }

    /* ------------------------------------------------------------------ */
    /*  Initialise                                                          */
    /* ------------------------------------------------------------------ */

    function init() {
        var wrapper = document.getElementById('afc-calculator');
        if (!wrapper) return;

        /* Elements */
        var typeSelect    = document.getElementById('afc-appliance-type');
        var stepProduct   = document.getElementById('afc-step-product');
        var stepSpace     = document.getElementById('afc-step-space');
        var btnWrap       = document.getElementById('afc-btn-wrap');
        var checkBtn      = document.getElementById('afc-check-btn');
        var errorBox      = document.getElementById('afc-error');
        var resultBox     = document.getElementById('afc-result');
        var resultBadge   = document.getElementById('afc-result-badge');
        var resultIcon    = document.getElementById('afc-result-icon');
        var resultLabel   = document.getElementById('afc-result-label');
        var clrHeight     = document.getElementById('afc-clr-height');
        var clrWidth      = document.getElementById('afc-clr-width');
        var clrDepth      = document.getElementById('afc-clr-depth');
        var resetBtn      = document.getElementById('afc-reset-btn');

        var prodH = document.getElementById('afc-prod-height');
        var prodW = document.getElementById('afc-prod-width');
        var prodD = document.getElementById('afc-prod-depth');
        var spcH  = document.getElementById('afc-space-height');
        var spcW  = document.getElementById('afc-space-width');
        var spcD  = document.getElementById('afc-space-depth');

        /* Unit elements */
        var unitToggle = document.getElementById('afc-unit-toggle');
        var unitKnob   = document.getElementById('afc-unit-knob');
        var unitLabel  = document.getElementById('afc-unit-label');
        var unitMarkers = document.querySelectorAll('.afc-unit-marker');

        /* ---- Unit Toggle Logic ---- */
        if (unitToggle) {
            unitToggle.addEventListener('click', function() {
                var isCM = state.unit === 'CM';
                state.unit = isCM ? 'IN' : 'CM';
                
                // Update UI toggle appearance
                unitToggle.setAttribute('aria-checked', !isCM);
                unitToggle.classList.toggle('bg-indigo-600', !isCM);
                unitToggle.classList.toggle('bg-indigo-200', isCM);
                unitKnob.classList.toggle('translate-x-9', !isCM);
                unitKnob.classList.toggle('translate-x-1', isCM);
                
                // Update labels
                unitLabel.textContent = UNITS[state.unit].label;
                unitMarkers.forEach(function(m) { m.textContent = UNITS[state.unit].marker; });
                
                // If results are showing, re-run or just update labels? 
                // Let's hide results to avoid confusion if they were already showing
                hideEl(resultBox);
            });
        }

        /* ---- Step progression ---- */
        typeSelect.addEventListener('change', function () {
            hideEl(errorBox);
            if (this.value) {
                showEl(stepProduct);
                showEl(stepSpace);
                showEl(btnWrap);
            } else {
                hideEl(stepProduct);
                hideEl(stepSpace);
                hideEl(btnWrap);
                hideEl(resultBox);
            }
        });

        /* ---- Check Fit ---- */
        checkBtn.addEventListener('click', function () {
            hideEl(errorBox);

            if (!typeSelect.value) {
                setError(errorBox, 'Please select an appliance type.');
                typeSelect.focus();
                return;
            }

            var ph = parseVal(prodH), pw = parseVal(prodW), pd = parseVal(prodD);
            var sh = parseVal(spcH),  sw = parseVal(spcW),  sd = parseVal(spcD);

            if (ph === null || pw === null || pd === null) {
                setError(errorBox, 'Enter all three appliance dimensions.');
                return;
            }
            if (sh === null || sw === null || sd === null) {
                setError(errorBox, 'Enter all three space dimensions.');
                return;
            }
            if (ph <= 0 || pw <= 0 || pd <= 0 || sh <= 0 || sw <= 0 || sd <= 0) {
                setError(errorBox, 'All dimensions must be positive.');
                return;
            }

            var fit = checkFit(
                { height: ph, width: pw, depth: pd },
                { height: sh, width: sw, depth: sd }
            );

            /* Update badge with dynamic Tailwind classes */
            resultBadge.className = 'relative flex flex-col items-center gap-4 p-8 rounded-3xl border-4 shadow-xl overflow-hidden text-center transition-all duration-700 ' + fit.overall.classes;
            resultIcon.textContent = fit.overall.icon;
            resultIcon.className = 'w-20 h-20 rounded-full flex items-center justify-center text-4xl shadow-lg ring-8 ring-white/50 transition-all duration-700 ' + fit.overall.iconClasses;
            resultLabel.textContent = fit.overall.label;

            /* Clearance rows */
            buildClearanceRow(clrHeight, fit.dimensions[0]);
            buildClearanceRow(clrWidth,  fit.dimensions[1]);
            buildClearanceRow(clrDepth,  fit.dimensions[2]);

            showEl(resultBox);
            resultBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });

        /* ---- Reset ---- */
        resetBtn.addEventListener('click', function () {
            typeSelect.value = '';
            [prodH, prodW, prodD, spcH, spcW, spcD].forEach(function (i) { i.value = ''; });
            hideEl(stepProduct);
            hideEl(stepSpace);
            hideEl(btnWrap);
            hideEl(resultBox);
            hideEl(errorBox);
            typeSelect.focus();
            wrapper.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });

        /* ---- Live validation ---- */
        [prodH, prodW, prodD, spcH, spcW, spcD].forEach(function (input) {
            input.addEventListener('input', function () {
                hideEl(errorBox);
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
