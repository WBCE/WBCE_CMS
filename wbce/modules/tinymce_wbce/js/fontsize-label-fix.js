/**
 * tinymce_wbce — js/fontsize-label-fix.js
 * Fixes the closed-state label of the fontsize dropdown for em/rem size lists.
 *
 * TinyMCE displays the COMPUTED font size of the selection (always px) and
 * matches it against font_size_formats using an internal conversion table that
 * only knows px/pt/cm/pc/mm/in — em/rem entries can never match, so the label
 * falls back to e.g. "14px" even though "0.875rem" is in the list.
 *
 * This helper re-computes the label after every TinyMCE text update:
 * px → unit (rem: relative to the iframe root font size; em: relative to the
 * parent element), snaps to the closest list entry within epsilon, and writes
 * the result into the dropdown label. Cosmetic only — content is untouched.
 *
 * Reads its inputs from the editor options, so callers just do:
 *   setup: function (ed) { tinymceWbceSizeLabelFix(ed); }
 *
 * @license GNU GPL2
 */
(function () {
    'use strict';

    window.tinymceWbceSizeLabelFix = function (editor) {
        var unit = editor.options.get('font_size_input_default_unit') || '';
        if (unit !== 'rem' && unit !== 'em') return; // px/pt match natively

        var sizes = (editor.options.get('font_size_formats') || '')
            .split(/\s+/).filter(function (s) { return s !== ''; });

        function baseFontPx() {
            try {
                if (unit === 'rem') {
                    var doc = editor.getDoc();
                    return parseFloat(getComputedStyle(doc.documentElement).fontSize) || 16;
                }
                // em — relative to the parent of the current selection node
                var node = editor.selection.getNode();
                var ref  = node && node.parentElement ? node.parentElement : node;
                return ref ? (parseFloat(getComputedStyle(ref).fontSize) || 16) : 16;
            } catch (e) { return 16; }
        }

        function update() {
            var raw = editor.queryCommandValue('FontSize'); // computed, e.g. "14px"
            if (!raw || raw.slice(-2) !== 'px') return;
            var px = parseFloat(raw);
            if (!isFinite(px)) return;

            var val = px / baseFontPx();
            var label = null;
            for (var i = 0; i < sizes.length; i++) {
                var f = parseFloat(sizes[i]);
                if (isFinite(f) && Math.abs(f - val) < 0.011) { label = sizes[i]; break; }
            }
            if (!label) label = (Math.round(val * 100) / 100) + unit;

            var container = editor.getContainer();
            if (!container) return;
            var lbl = container.querySelector('[data-mce-name="fontsize"] .tox-tbtn__select-label');
            if (lbl && lbl.textContent !== label) lbl.textContent = label;
        }

        // FontSizeTextUpdate fires whenever TinyMCE (re)writes the label —
        // patch right after, plus the usual lifecycle events.
        var deferred = function () { setTimeout(update, 0); };
        editor.on('FontSizeTextUpdate', deferred);
        editor.on('NodeChange SetContent init', deferred);
    };
}());
