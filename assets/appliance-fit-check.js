/**
 * Appliance Fit Check — Frontend Logic
 * Version: 2.0.0 (AJAX + Dynamic Modal)
 */

(function ($) {
    'use strict';

    /* ------------------------------------------------------------------ */
    /*  Result Config                                                       */
    /* ------------------------------------------------------------------ */

    var RESULT_CONFIG = {
        PERFECT: {
            // Banner gradient
            bannerFrom: '#ecfdf5', bannerTo: '#ffffff',
            // Ring bg colour
            ringColor: '#6ee7b7',
            // Circle gradient
            circleFrom: '#34d399', circleTo: '#059669',
            circleShadow: 'shadow-emerald-200',
            // Icon inside circle
            circleIcon: '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
            // Status label colour & icon
            labelColor: '#10b981',
            labelIcon: '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block"><polyline points="20 6 9 17 4 12"/></svg>',
            // Sub text
            subText: 'Your appliance fits securely and meets<br>clearance guidelines.',
            // Notice box
            noticeBg: 'bg-sky-50', noticeBorder: 'border-sky-100',
            noticeIconBg: '#3b82f6',
            noticeIconChar: 'i',
            noticeTitle: 'Clearance Available',
            noticeTitleColor: '#0369a1',
            noticeSub: 'You have enough space for proper ventilation and easy installation.',
            noticeSubColor: '#38bdf8',
        },
        TIGHT: {
            bannerFrom: '#fffbeb', bannerTo: '#ffffff',
            ringColor: '#fcd34d',
            circleFrom: '#fbbf24', circleTo: '#d97706',
            circleShadow: 'shadow-amber-200',
            circleIcon: '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
            labelColor: '#f59e0b',
            labelIcon: '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
            subText: 'The appliance will fit, but clearance is minimal.<br>Installation may be tricky.',
            noticeBg: 'bg-amber-50', noticeBorder: 'border-amber-100',
            noticeIconBg: '#f59e0b',
            noticeIconChar: '!',
            noticeTitle: 'Tight Clearance',
            noticeTitleColor: '#92400e',
            noticeSub: 'Minimal clearance detected. Ensure ventilation requirements are met.',
            noticeSubColor: '#d97706',
        },
        MODIFY: {
            bannerFrom: '#fff7ed', bannerTo: '#ffffff',
            ringColor: '#fdba74',
            circleFrom: '#fb923c', circleTo: '#ea580c',
            circleShadow: 'shadow-orange-200',
            circleIcon: '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>',
            labelColor: '#f97316',
            labelIcon: '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>',
            subText: 'Minor adjustments needed.<br>The space requires slight modification.',
            noticeBg: 'bg-orange-50', noticeBorder: 'border-orange-100',
            noticeIconBg: '#f97316',
            noticeIconChar: '⚒',
            noticeTitle: 'Adjustment Required',
            noticeTitleColor: '#9a3412',
            noticeSub: 'Small modifications to the opening may be necessary before installation.',
            noticeSubColor: '#ea580c',
        },
        NOFIT: {
            bannerFrom: '#fff1f2', bannerTo: '#ffffff',
            ringColor: '#fda4af',
            circleFrom: '#f43f5e', circleTo: '#be123c',
            circleShadow: 'shadow-rose-200',
            circleIcon: '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
            labelColor: '#f43f5e',
            labelIcon: '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
            subText: 'This appliance does not fit the space.<br>Please choose a different model or size.',
            noticeBg: 'bg-rose-50', noticeBorder: 'border-rose-100',
            noticeIconBg: '#f43f5e',
            noticeIconChar: '✕',
            noticeTitle: 'Does Not Fit',
            noticeTitleColor: '#9f1239',
            noticeSub: 'The appliance exceeds the available space. Consider a smaller model.',
            noticeSubColor: '#f43f5e',
        }
    };

    /* Appliance type icons */
    var APPLIANCE_ICONS = {
        'dishwasher': '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><circle cx="8" cy="6" r="0.8" fill="#475569"/><path d="M8 14h8M8 17h5"/></svg>',
        'refrigerator': '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2"/><path d="M4 10h16"/><path d="M9 6v2M9 14v4"/></svg>',
        'wall-oven': '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><rect x="6" y="8" width="12" height="9" rx="1"/><circle cx="8" cy="6" r="0.8" fill="#475569"/><circle cx="12" cy="6" r="0.8" fill="#475569"/><circle cx="16" cy="6" r="0.8" fill="#475569"/></svg>',
        '': '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><rect x="6" y="8" width="12" height="9" rx="1"/><circle cx="8" cy="6" r="0.8" fill="#475569"/><circle cx="12" cy="6" r="0.8" fill="#475569"/><circle cx="16" cy="6" r="0.8" fill="#475569"/></svg>',
    };

    /* ------------------------------------------------------------------ */
    /*  Helpers                                                            */
    /* ------------------------------------------------------------------ */

    function parseInput(val) {
        var v = parseFloat(val);
        return (isNaN(v) || v < 0 || v > 200) ? null : v;
    }

    function formatDim(val) {
        return val + '"';
    }

    function labelFromType(type) {
        var map = { 'dishwasher': 'Dishwasher', 'refrigerator': 'Refrigerator', 'wall-oven': 'Wall Oven' };
        return map[type] || 'Appliance';
    }

    /* ------------------------------------------------------------------ */
    /*  Modal Population                                                   */
    /* ------------------------------------------------------------------ */

    function populateModal(data) {
        var inputs  = data.inputs;
        var overall = data.overall;
        var cfg     = RESULT_CONFIG[overall.key] || RESULT_CONFIG.PERFECT;
        var type    = data.appliance_type || '';

        /* --- Header --- */
        var icon = APPLIANCE_ICONS[type] || APPLIANCE_ICONS[''];
        $('#afc-modal-appliance-icon').html(icon);
        $('#afc-modal-appliance-type').text(labelFromType(type));
        $('#afc-modal-date').text('Checked on: ' + data.date);

        /* --- Banner --- */
        $('#afc-modal-banner').css('background', 'linear-gradient(to bottom, ' + cfg.bannerFrom + ', ' + cfg.bannerTo + ')');

        /* Ring */
        $('#afc-status-ring').css('background-color', cfg.ringColor);

        /* Circle */
        $('#afc-status-circle')
            .css('background', 'linear-gradient(to bottom right, ' + cfg.circleFrom + ', ' + cfg.circleTo + ')')
            .css('box-shadow', '0 10px 15px -3px ' + cfg.ringColor)
            .html(cfg.circleIcon);

        /* Status label */
        $('#afc-status-label')
            .css('color', cfg.labelColor)
            .html(cfg.labelIcon + ' ' + overall.label);

        /* Sub text */
        $('#afc-status-sub').html(cfg.subText);

        /* --- Dimension cards --- */
        $('#afc-prod-h-display').text(formatDim(inputs.prod_height));
        $('#afc-prod-w-display').text(formatDim(inputs.prod_width));
        $('#afc-prod-d-display').text(formatDim(inputs.prod_depth));

        $('#afc-space-h-display').text(formatDim(inputs.space_height));
        $('#afc-space-w-display').text(formatDim(inputs.space_width));
        $('#afc-space-d-display').text(formatDim(inputs.space_depth));

        /* --- Clearance notice --- */
        var $notice = $('#afc-clearance-notice');
        $notice.attr('class', 'flex items-start gap-3 rounded-2xl px-4 py-3 border ' + cfg.noticeBg + ' ' + cfg.noticeBorder);

        $('#afc-notice-icon-wrap').css('background-color', cfg.noticeIconBg);
        $('#afc-notice-icon-char').text(cfg.noticeIconChar);
        $('#afc-notice-title').text(cfg.noticeTitle).css('color', cfg.noticeTitleColor);
        $('#afc-notice-sub').html(cfg.noticeSub).css('color', cfg.noticeSubColor);
    }

    /* ------------------------------------------------------------------ */
    /*  Modal Open / Close                                                 */
    /* ------------------------------------------------------------------ */

    function openModal() {
        $('#afc-modal-overlay').addClass('afc-open');
        $('body').css('overflow', 'hidden');
    }

    function closeModal() {
        $('#afc-modal-overlay').removeClass('afc-open');
        $('body').css('overflow', '');
    }

    function openErrorModal(msg) {
        $('#afc-error-msg').text(msg);
        $('#afc-error-overlay').addClass('afc-open');
        $('body').css('overflow', 'hidden');
    }

    function closeErrorModal() {
        $('#afc-error-overlay').removeClass('afc-open');
        $('body').css('overflow', '');
    }

    /* ------------------------------------------------------------------ */
    /*  Init                                                              */
    /* ------------------------------------------------------------------ */

    function init() {
        var $wrapper = $('#afc-calculator');
        if (!$wrapper.length) return;

        var $checkBtn = $('#afc-check-btn');

        /* Input sanitization */
        $('input[type=number]', $wrapper).on('input', function () {
            var sanitized = this.value.replace(/[^0-9.]/g, '');
            var parts = sanitized.split('.');
            if (parts.length > 2) sanitized = parts[0] + '.' + parts.slice(1).join('');
            if (this.value !== sanitized) this.value = sanitized;
        });

        /* Close result modal */
        $('#afc-modal-close').on('click', closeModal);
        $('#afc-modal-overlay').on('click', function (e) {
            if ($(e.target).is('#afc-modal-overlay')) closeModal();
        });

        /* Close error modal */
        $('#afc-error-modal-close, #afc-error-dismiss').on('click', closeErrorModal);
        $('#afc-error-overlay').on('click', function (e) {
            if ($(e.target).is('#afc-error-overlay')) closeErrorModal();
        });

        /* Check Fit — AJAX */
        $checkBtn.on('click', function () {
            var ph = parseInput($('#afc-prod-height').val());
            var pw = parseInput($('#afc-prod-width').val());
            var pd = parseInput($('#afc-prod-depth').val());
            var sh = parseInput($('#afc-space-height').val());
            var sw = parseInput($('#afc-space-width').val());
            var sd = parseInput($('#afc-space-depth').val());

            if (ph === null || pw === null || pd === null || sh === null || sw === null || sd === null) {
                openErrorModal('Please enter valid dimensions (0 – 200) in all 6 fields before checking.');
                return;
            }

            /* Loading state */
            $checkBtn.text('Checking…').css('opacity', '0.75').css('pointer-events', 'none');

            $.ajax({
                url: AFC_SETTINGS.ajax_url,
                type: 'POST',
                data: {
                    action:       'afc_check_fit',
                    nonce:        AFC_SETTINGS.nonce,
                    appliance_type: $('#afc-appliance-type').val(),
                    prod_height:  ph,
                    prod_width:   pw,
                    prod_depth:   pd,
                    space_height: sh,
                    space_width:  sw,
                    space_depth:  sd,
                },
                success: function (response) {
                    $checkBtn.text('Check My Fit').css('opacity', '').css('pointer-events', '');
                    if (response.success) {
                        populateModal(response.data);
                        openModal();
                    } else {
                        var msg = (response.data && response.data.message) ? response.data.message : 'An error occurred.';
                        openErrorModal(msg);
                    }
                },
                error: function () {
                    $checkBtn.text('Check My Fit').css('opacity', '').css('pointer-events', '');
                    openErrorModal('Server error. Please try again.');
                }
            });
        });
    }

    $(document).ready(init);

}(jQuery));
