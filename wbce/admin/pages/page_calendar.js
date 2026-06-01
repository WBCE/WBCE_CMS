/**
 * Flatpickr + wbeRangePlugin — per-section calendar initialiser
 *
 * wbeRangePlugin creates two independent flatpickr instances so that
 * either the start or the end date can be empty without forcing the other.
 * Range highlighting is still shown whenever both dates are set.
 *
 * Expects the following globals set by the inline <script> in calendar_block:
 *
 *   fpStartId      — id of the start-date input  (e.g. "start_date42")
 *   fpEndId        — id of the end-date input    (e.g. "end_date42")
 *   fpTriggerStart — id of the start-date button (e.g. "trigger_start42")
 *   fpTriggerEnd   — id of the end-date button   (e.g. "trigger_stop42")
 *   fpDateFormat   — Flatpickr/PHP date format   (e.g. "d.m.Y" or "d.m.Y H:i")
 *   fpLocaleKey    — key into flatpickr.l10ns    (e.g. 'de', 'default')
 *   fpUseTime      — true | false
 *
 * After initialisation:
 *   inputStart._flatpickr  → start instance  (clear: inputStart._flatpickr.clear())
 *   inputEnd._flatpickr    → end instance    (clear: inputEnd._flatpickr.clear())
 */
(function () {
    var inputStart = document.getElementById(fpStartId);
    var inputEnd   = document.getElementById(fpEndId);
    var btnStart   = document.getElementById(fpTriggerStart);
    var btnEnd     = document.getElementById(fpTriggerEnd);

    if (!inputStart || !inputEnd) { return; }

    var locale = (window.flatpickr && window.flatpickr.l10ns && window.flatpickr.l10ns[fpLocaleKey])
                 || {};

    var opts = {
        locale:        locale,
        dateFormat:    fpDateFormat,
        allowInput:    true,
        disableMobile: true,
        plugins:       [new wbeRangePlugin({ input: inputEnd })],
    };

    if (fpUseTime) {
        opts.enableTime = true;
        opts.time_24hr  = true;
    }

    // wbeRangePlugin creates the end-date flatpickr instance on inputEnd
    // during initialisation. After this call:
    //   inputStart._flatpickr = start instance
    //   inputEnd._flatpickr   = end instance
    flatpickr(inputStart, opts);

    // Open start calendar
    if (btnStart) {
        btnStart.addEventListener('click', function (e) {
            e.preventDefault();
            inputStart._flatpickr.open();
        });
    }

    // Open end calendar — the end instance lives on inputEnd._flatpickr
    if (btnEnd) {
        btnEnd.addEventListener('click', function (e) {
            e.preventDefault();
            if (inputEnd._flatpickr) inputEnd._flatpickr.open();
        });
    }
}());
