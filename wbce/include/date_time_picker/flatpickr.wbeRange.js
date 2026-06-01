/**
 * wbeRangePlugin — flatpickr plugin for WBCE publication date ranges
 *
 * Replaces the official rangePlugin with a WBCE-specific implementation.
 *
 * Key difference from the official plugin:
 *   The official rangePlugin forces mode:"range" — the two fields are
 *   tightly coupled and a start date is always required before an end date
 *   can be set. This breaks WBCE's use-case where either field may be
 *   empty independently (e.g. "visible until end-date, no start restriction").
 *
 * This plugin creates TWO independent flatpickr instances (one for the start
 * input, one for the end input). Both can be cleared individually. When both
 * dates are set the calendar shows the highlighted range exactly as the
 * official plugin does (using the same CSS classes: startRange / inRange /
 * endRange from flatpickr.min.css).
 *
 * Usage (identical to the official rangePlugin):
 *   flatpickr(startInput, {
 *       plugins: [ new wbeRangePlugin({ input: endInputElement }) ]
 *   });
 *
 * After initialisation:
 *   startInput._flatpickr  → start flatpickr instance
 *   endInput._flatpickr    → end flatpickr instance (created by this plugin)
 *
 * Each instance can be cleared independently:
 *   startInput._flatpickr.clear()   // clears only start date
 *   endInput._flatpickr.clear()     // clears only end date
 */
(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
    typeof define === 'function' && define.amd ? define(factory) :
    (global = typeof globalThis !== 'undefined' ? globalThis : global || self,
     global.wbeRangePlugin = factory());
}(this, (function () {
    'use strict';

    function wbeRangePlugin(config) {
        if (config === void 0) { config = {}; }

        return function (fp) {
            var secondInput = null;
            var fpEnd       = null;

            /* ── date helpers ─────────────────────────────────────────────── */

            function getStart() { return fp.selectedDates[0]              || null; }
            function getEnd()   { return (fpEnd && fpEnd.selectedDates && fpEnd.selectedDates[0]) || null; }

            /** Strip time so day comparisons are date-only. */
            function midnight(d) {
                return new Date(d.getFullYear(), d.getMonth(), d.getDate()).getTime();
            }

            /* ── range CSS ────────────────────────────────────────────────── */

            /**
             * Apply startRange / inRange / endRange classes to a single
             * flatpickr day element.  flatpickr sets dayElem.dateObj before
             * calling onDayCreate, so we rely on that property here as well.
             */
            function styleDayElem(dayElem) {
                if (!dayElem.dateObj) return;

                dayElem.classList.remove('startRange', 'inRange', 'endRange');

                var s = getStart();
                var e = getEnd();
                if (!s && !e) return;

                var dT = midnight(dayElem.dateObj);
                var sT = s ? midnight(s) : null;
                var eT = e ? midnight(e) : null;

                if (sT !== null && dT === sT) dayElem.classList.add('startRange');
                if (eT !== null && dT === eT) dayElem.classList.add('endRange');

                // Fill between the two boundaries
                if (sT !== null && eT !== null && dT > sT && dT < eT)
                    dayElem.classList.add('inRange');
            }

            /**
             * Re-apply range classes to every rendered day element of an instance.
             * Needed when one side changes while the other calendar is still open.
             */
            function refreshDays(fpInstance) {
                if (!fpInstance || !fpInstance.calendarContainer) return;
                fpInstance.calendarContainer
                    .querySelectorAll('.flatpickr-day')
                    .forEach(function (el) { styleDayElem(el); });
            }

            /* ── plugin lifecycle ─────────────────────────────────────────── */

            var plugin = {

                /**
                 * onReady fires after the START picker is fully built.
                 * We create the END picker here and wire up cross-instance
                 * constraints and range highlighting.
                 */
                onReady: function () {

                    /* resolve secondInput */
                    if (config.input) {
                        secondInput = config.input instanceof Element
                            ? config.input
                            : document.querySelector(config.input);
                    }
                    if (!secondInput) return;

                    /* build end-picker options — mirror every relevant setting */
                    var c = fp.config;
                    var endOpts = {
                        locale:        c.locale,
                        dateFormat:    c.dateFormat,
                        enableTime:    !!c.enableTime,
                        time_24hr:     !!c.time_24hr,
                        allowInput:    !!c.allowInput,
                        disableMobile: !!c.disableMobile,
                        clickOpens:    c.clickOpens !== false,
                        plugins:       [],  // no recursion

                        onDayCreate: function (dObj, dStr, fpInst, dayElem) {
                            styleDayElem(dayElem);
                        },

                        onChange: function () {
                            // End date changed → update start picker's maxDate
                            fp.set('maxDate', getEnd() || null);
                            refreshDays(fp);
                        },
                    };

                    /* create the end flatpickr instance
                       flatpickr reads secondInput.value automatically as the
                       default date, so pre-existing DB values work out of the box */
                    fpEnd = window.flatpickr(secondInput, endOpts);

                    /* hook into start picker */

                    // Range highlighting for START picker's calendar
                    fp.config.onDayCreate.push(function (dObj, dStr, fpInst, dayElem) {
                        styleDayElem(dayElem);
                    });

                    // Start date changed → update end picker's minDate
                    fp.config.onChange.push(function () {
                        if (fpEnd) {
                            fpEnd.set('minDate', getStart() || null);
                            refreshDays(fpEnd);
                        }
                        refreshDays(fp);
                    });

                    /* set initial min/maxDate constraints from pre-existing values */
                    var initStart = getStart();
                    var initEnd   = getEnd();
                    if (initStart) fpEnd.set('minDate', initStart);
                    if (initEnd)   fp.set('maxDate',   initEnd);

                    /* enable flatpickr's range-mode CSS
                       The .rangeMode class on the calendar container is required by
                       flatpickr.min.css for the inRange connecting strip between days */
                    fp.calendarContainer.classList.add('rangeMode');
                    fpEnd.calendarContainer.classList.add('rangeMode');

                    /* inject styles once per page:
                       - compact day-cell size (33 px instead of 39 px default)
                       - startRange → green (publication start)
                       - endRange   → red   (publication end / expiry) */
                    if (!document.getElementById('wbeRangePlugin-style')) {
                        var style = document.createElement('style');
                        style.id   = 'wbeRangePlugin-style';
                        style.textContent =
                            /* ── compact day size ── */
                            '.flatpickr-day {' +
                            '    max-width:33px;' +
                            '    height:33px;' +
                            '    line-height:33px;' +
                            '}' +
                            '.dayContainer {' +
                            '    min-width:231px;' +   /* 7 × 33 px */
                            '    max-width:231px;' +
                            '    width:231px;' +
                            '}' +
                            '.flatpickr-calendar {' +
                            '    width:231px;' +
                            '}' +
                            /* ── range colours ── */
                            '.flatpickr-day.startRange,' +
                            '.flatpickr-day.startRange:hover,' +
                            '.flatpickr-day.startRange.selected,' +
                            '.flatpickr-day.startRange.selected:hover {' +
                            '    background:#28a745 !important;' +
                            '    border-color:#28a745 !important;' +
                            '}' +
                            '.flatpickr-day.endRange,' +
                            '.flatpickr-day.endRange:hover,' +
                            '.flatpickr-day.endRange.selected,' +
                            '.flatpickr-day.endRange.selected:hover {' +
                            '    background:#dc3545 !important;' +
                            '    border-color:#dc3545 !important;' +
                            '}';
                        document.head.appendChild(style);
                    }

                    /* make both inputs editable when allowInput is on */
                    if (c.allowInput) {
                        fp._input.removeAttribute('readonly');
                        secondInput.removeAttribute('readonly');
                    }

                    fp.loadedPlugins.push('wbceRange');
                },

                onDestroy: function () {
                    if (fpEnd) {
                        fpEnd.destroy();
                        fpEnd = null;
                    }
                },
            };

            return plugin;
        };
    }

    return wbeRangePlugin;

})));
