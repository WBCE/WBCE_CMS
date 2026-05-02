/**
 * WBCE CMS AdminTool: tool_debug_dump
 * debug_dump.js
 * Client-side logic for WBCE dump() panels.
 * 
 * @platform    WBCE CMS 1.7.x and higher
 * @package     modules/tool_debug_dump
 * @author      Christian M. Stefan (https://www.wbEasy.de)
 * @copyright   Christian M. Stefan (2018, 2026)
 * @license     GNU/GPL2
 *
 * Features:
 * - Syntax colouring for JSON, var_dump and print_r
 * - Collapse state persisted in localStorage
 * - Smart expand button: shown only when content overflows data-box-height
 * - Toggle between full height and reduced height
 */

(function () {
    'use strict';

    // ── Helpers ───────────────────────────────────────────────────────────────

    function esc(str) {
        return String(str)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function indent(n) {
        return '<span class="dd-pp-indent"></span>'.repeat(n);
    }

    // ── JSON recursive formatter ──────────────────────────────────────────────
    function formatValue(value, depth) {
        depth = depth || 0;
        var inner = depth + 1;

        if (value === null) return '<span class="dd-pp-null">null</span>';

        if (typeof value === 'boolean')
            return '<span class="dd-pp-bool">' + (value ? 'true' : 'false') + '</span>';

        if (typeof value === 'number') {
            var cls = Number.isInteger(value) ? 'dd-pp-int' : 'dd-pp-float';
            return '<span class="' + cls + '">' + esc(String(value)) + '</span>';
        }

        if (typeof value === 'string') {
            return '<span class="dd-pp-str-q">&quot;</span>'
                 + '<span class="dd-pp-str">' + esc(value) + '</span>'
                 + '<span class="dd-pp-str-q">&quot;</span>'
                 + ' <span class="dd-pp-count">(' + value.length + ')</span>';
        }

        // Array
        if (Array.isArray(value)) {
            if (!value.length)
                return '<span class="dd-pp-brace">[]</span>';

            var aLines = value.map(function (v, i) {
                return '<span class="dd-pp-line">'
                    + indent(inner)
                    + '<span class="dd-pp-key-int">' + i + '</span>'
                    + ' <span class="dd-pp-arrow">=&gt;</span> '
                    + formatValue(v, inner) + '</span>';
            }).join('\n');

            return '<span class="dd-pp-brace">[</span>'
                 + '<span class="dd-pp-count">(' + value.length + ')</span>\n'
                 + aLines + '\n'
                 + '<span class="dd-pp-line">'
                 + indent(depth) + '<span class="dd-pp-brace">]</span></span>';
        }

        // Object
        if (typeof value === 'object' && value !== null) {
            var keys = Object.keys(value);
            if (!keys.length)
                return '<span class="dd-pp-brace">{}</span>';

            var oLines = keys.map(function (k) {
                var isInt = /^\d+$/.test(k);
                var kHtml = isInt
                    ? '<span class="dd-pp-key-int">' + esc(k) + '</span>'
                    : '<span class="dd-pp-key">' + esc(k) + '</span>';

                return '<span class="dd-pp-line">'
                    + indent(inner) + kHtml
                    + ' <span class="dd-pp-arrow">=&gt;</span> '
                    + formatValue(value[k], inner) + '</span>';
            }).join('\n');

            return '<span class="dd-pp-brace">{</span>'
                 + '<span class="dd-pp-count">(' + keys.length + ')</span>\n'
                 + oLines + '\n'
                 + '<span class="dd-pp-line">'
                 + indent(depth) + '<span class="dd-pp-brace">}</span></span>';
        }

        return esc(String(value));
    }

    // ── Colorisers (var_dump, print_r, scalar) ────────────────────────────────
    function colorizeVarDump(html) { /* ... your existing code ... */ 
        return html
            .replace(/\bNULL\b/g, '<span class="dd-pp-null">NULL</span>')
            .replace(/\bbool\((true|false)\)/g, 'bool(<span class="dd-pp-bool">$1</span>)')
            .replace(/\bint\((-?\d+)\)/g, 'int(<span class="dd-pp-int">$1</span>)')
            .replace(/\bfloat\(([^)]+)\)/g, 'float(<span class="dd-pp-float">$1</span>)')
            .replace(/string\((\d+)\) &quot;(.*?)&quot;/g,
                'string(<span class="dd-pp-count">$1</span>)' +
                ' <span class="dd-pp-str-q">&quot;</span><span class="dd-pp-str">$2</span><span class="dd-pp-str-q">&quot;</span>')
            .replace(/\[&quot;([^&]+)&quot;\]=&gt;/g, '[<span class="dd-pp-key">&quot;$1&quot;</span>]<span class="dd-pp-arrow">=&gt;</span>')
            .replace(/\[(\d+)\]=&gt;/g, '[<span class="dd-pp-key-int">$1</span>]<span class="dd-pp-arrow">=&gt;</span>')
            .replace(/=&gt;/g, '<span class="dd-pp-arrow">=&gt;</span>')
            .replace(/\barray\((\d+)\)/g, 'array(<span class="dd-pp-count">$1</span>)')
            .replace(/\bobject\(([^)]+)\)#(\d+) \((\d+)\)/g,
                'object(<span class="dd-pp-cls">$1</span>)#$2 (<span class="dd-pp-count">$3</span>)');
    }

    function colorizePrintR(html) {
        return html
            .replace(/\b(Array|Object)\b/g, '<span class="dd-pp-cls">$1</span>')
            .replace(/\[([^\]]+)\] =&gt; /g, function (_, key) {
                var cls = /^\d+$/.test(key) ? 'dd-pp-key-int' : 'dd-pp-key';
                return '[<span class="' + cls + '">' + key + '</span>] <span class="dd-pp-arrow">=&gt;</span> ';
            });
    }

    function colorizeScalar(html, format) {
        var t = html.trim();
        if (t === 'true' || t === 'false') return '<span class="dd-pp-bool">' + t + '</span>';
        if (t === 'null') return '<span class="dd-pp-null">null</span>';
        if (t !== '' && !isNaN(Number(t))) {
            var cls = t.indexOf('.') !== -1 ? 'dd-pp-float' : 'dd-pp-int';
            return '<span class="' + cls + '">' + t + '</span>';
        }
        if (format === 'string') {
            return '<span class="dd-pp-str-q">&quot;</span>'
                 + '<span class="dd-pp-str">' + html + '</span>'
                 + '<span class="dd-pp-str-q">&quot;</span>'
                 + ' <span class="dd-pp-count">(' + t.length + ')</span>';
        }
        return html;
    }

    function colorizePanel(pre) {
        var format = pre.dataset.format || 'print_r';
        if (format === 'json') {
            try {
                pre.innerHTML = formatValue(JSON.parse(pre.textContent), 0);
            } catch (e) {
                pre.innerHTML = colorizePrintR(pre.innerHTML);
            }
        } else if (format === 'var_dump') {
            pre.innerHTML = colorizeVarDump(pre.innerHTML);
        } else if (format === 'print_r') {
            pre.innerHTML = colorizePrintR(pre.innerHTML);
        } else {
            pre.innerHTML = colorizeScalar(pre.innerHTML, format);
        }
    }

    // ── Combined SVG Icon ─────────────────────────────────────────────────
    const CODE_TOGGLE_SVG = `
    <svg id="code-toggle-svg" width="20" height="20" viewBox="0 0 1024 1024" 
         xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">

        <!-- EXPAND state (default) -->
        <g id="expand-state">
            <g transform="matrix(1.12335,0,0,1.12335,-75.7722,-74.8015)">
                <path fill="currentColor" d="M880,224L144,224C117.5,224 96,202.5 96,176C96,149.5 117.5,128 144,128L880,128C906.5,128 928,149.5 928,176C928,202.5 906.5,224 880,224ZM144,576L328.549,576C355.049,576 376.549,597.5 376.549,624C376.549,650.5 355.049,672 328.549,672L144,672C117.5,672 96,650.5 96,624C96,597.5 117.5,576 144,576ZM144,352L880,352C906.5,352 928,373.5 928,400C928,426.5 906.5,448 880,448L144,448C117.5,448 96,426.5 96,400C96,373.5 117.5,352 144,352ZM704.024,813.391C683.477,833.937 650.274,833.937 629.727,813.226L421.467,604.966C388.264,571.927 411.769,515.219 458.615,515.219L875.3,515.219C922.146,515.219 945.651,571.763 912.448,604.966L704.024,813.391Z"/>
            </g>
        </g>

        <!-- COLLAPSE state -->
        <g id="collapse-state" style="display: none;">
            <g transform="matrix(1.12335,0,0,1.12335,-75.7722,-74.8015)">
                <path fill="currentColor" d="M880,224L144,224C117.5,224 96,202.5 96,176C96,149.5 117.5,128 144,128L880,128C906.5,128 928,149.5 928,176C928,202.5 906.5,224 880,224ZM144,780.746L328.549,780.746C355.049,780.746 376.549,802.246 376.549,828.746C376.549,855.246 355.049,876.746 328.549,876.746L144,876.746C117.5,876.746 96,855.246 96,828.746C96,802.246 117.5,780.746 144,780.746ZM144,352L880,352C906.5,352 928,373.5 928,400C928,426.5 906.5,448 880,448L144,448C117.5,448 96,426.5 96,400C96,373.5 117.5,352 144,352Z"/>
            </g>
            <g transform="matrix(1.12335,0,0,1.12335,-75.7722,175.199)">
                <path fill="currentColor" d="M798.866,448L706.731,352L880,352C906.5,352 928,373.5 928,400C928,426.5 906.5,448 880,448L798.866,448Z" />
            </g>
            <g transform="matrix(1.12335,0,0,1.12335,-75.7722,175.199)">
                <path fill="currentColor" d="M144,352L632.052,352L530.559,448L144,448C117.5,448 96,426.5 96,400C96,373.5 117.5,352 144,352Z"/>
            </g>
            <g transform="matrix(1.13064e-16,-1.84648,1.84648,1.13064e-16,-271.85,2329.35)">
                <path fill="currentColor" d="M917.4,489.4L790.6,362.6C770.4,342.4 736,356.7 736,385.2L736,638.7C736,667.2 770.5,681.5 790.6,661.3L917.3,534.6C929.9,522.1 929.9,501.9 917.4,489.4Z"/>
            </g>
        </g>
    </svg>
    `;

    // ── Smart Expand Button Logic ─────────────────────────────────────────────
    function initExpandButtons() {
        document.querySelectorAll('details.debug-dump').forEach(function (details) {
            const btn = details.querySelector('button.dd-expand');
            if (!btn) return;

            const pre = details.querySelector('.dd-pre');
            if (!pre) return;

            const maxHeight = parseInt(details.dataset.boxHeight, 10);

            // No height limit defined → always show button
            if (!maxHeight || isNaN(maxHeight)) {
                btn.style.display = 'inline-flex';
                return;
            }

            // Temporary measurement to check if content overflows
            const originalMaxHeight = pre.style.maxHeight;
            const originalOverflow  = pre.style.overflow;

            pre.style.maxHeight = maxHeight + 'px';
            pre.style.overflow  = 'hidden';

            const needsExpandButton = pre.scrollHeight > maxHeight + 8; // +8px tolerance

            // Restore original styles
            pre.style.maxHeight = originalMaxHeight;
            pre.style.overflow  = originalOverflow;

            // Show button only if needed
            btn.style.display = needsExpandButton ? 'inline-flex' : 'none';
        });
    }

    // ── Expand / Collapse Handler ─────────────────────────────────────────────
    window.ddExpand = function (btn) {
        const details = btn.closest('details.debug-dump');
        const pre     = details.querySelector('.dd-pre');
        if (!pre) return;

        const expanded = pre.classList.toggle('dd-is-expanded');

        // Swap title <-> data-alt-title
        if (expanded) {
            btn.title = btn.dataset.altTitle || 'Auf volle Höhe verkleinern';
        } else {
            btn.title = btn.getAttribute('title') || 'Auf volle Höhe erweitern';
        }

        // Toggle SVG icon
        const wrapper = btn.querySelector('.dd-expand-icon-wrapper');
        if (wrapper) {
            const svg = wrapper.querySelector('svg');
            if (svg) {
                const expandGroup   = svg.querySelector('#expand-state');
                const collapseGroup = svg.querySelector('#collapse-state');

                if (expandGroup && collapseGroup) {
                    expandGroup.style.display   = expanded ? 'none' : 'block';
                    collapseGroup.style.display = expanded ? 'block' : 'none';
                }
            }
        }
    };

    // ── Main Initialization ───────────────────────────────────────────────────
    function init() {
        document.querySelectorAll('details.debug-dump').forEach(function (panel) {

            // Restore collapse state from localStorage
            if (localStorage.getItem(panel.id) === '0') {
                panel.open = false;
            }

            panel.addEventListener('toggle', function () {
                localStorage.setItem(panel.id, panel.open ? '1' : '0');
            });

            // Embed SVG icon
            var wrapper = panel.querySelector('.dd-expand-icon-wrapper');
            if (wrapper) {
                wrapper.innerHTML = CODE_TOGGLE_SVG;
            }

            // Colorise content
            var pre = panel.querySelector('.dd-pre');
            if (pre) colorizePanel(pre);
        });

        // Initialize smart expand buttons
        initExpandButtons();
    }

    // Run init when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

}());