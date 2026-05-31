/**
 * CodeEditorToolbar — Search & Replace panel for CodeMirror 5
 * Requires: searchcursor.js (bundled in CodeEditorToolbar)
 *
 * Usage: CETSearch.open(editor)  — called by toolbar search button
 */
var CETSearch = (function () {
    "use strict";

    var panel     = null;   // DOM element
    var overlay   = null;   // current highlight overlay
    var lastQuery = null;
    var lastCase  = false;
    var lastRegex = false;

    // ── Overlay: highlight all matches ────────────────────────────────────
    function makeOverlay(query, caseF) {
        return {
            token: function (stream) {
                if (typeof query === "string") {
                    var q = caseF ? query : query.toLowerCase();
                    var s = stream.string.slice(stream.pos);
                    var idx = (caseF ? s : s.toLowerCase()).indexOf(q);
                    if (idx === 0) { stream.pos += query.length; return "searching"; }
                    if (idx > 0)  { stream.pos += idx;           return null; }
                    stream.skipToEnd();
                } else {
                    query.lastIndex = stream.pos;
                    var m = query.exec(stream.string);
                    if (m && m.index === stream.pos) { stream.pos += m[0].length || 1; return "searching"; }
                    if (m)  { stream.pos = m.index; return null; }
                    stream.skipToEnd();
                }
            }
        };
    }

    function buildQuery(str, useRegex, matchCase) {
        if (!str) return null;
        if (useRegex) {
            try { return new RegExp(str, matchCase ? "gm" : "gmi"); }
            catch (e) { return null; }
        }
        return str;
    }

    function clearOverlay(cm) {
        if (overlay) { cm.removeOverlay(overlay); overlay = null; }
    }

    function applyOverlay(cm, query, caseF) {
        clearOverlay(cm);
        if (query) { overlay = makeOverlay(query, caseF); cm.addOverlay(overlay); }
    }

    // ── Find next / prev ─────────────────────────────────────────────────
    function find(cm, reverse) {
        var qStr = panel.querySelector('.cet-search-input').value;
        if (!qStr) return;
        var caseF = panel.querySelector('.cet-match-case').checked;
        var useRe = panel.querySelector('.cet-use-regex').checked;
        var q     = buildQuery(qStr, useRe, caseF);
        if (!q) return;

        lastQuery = q; lastCase = caseF; lastRegex = useRe;
        applyOverlay(cm, q, caseF);

        var start  = cm.getCursor(reverse ? "start" : "end");
        var cursor = cm.getSearchCursor(q, start, !caseF && typeof q === "string");
        var found  = reverse ? cursor.findPrevious() : cursor.findNext();
        if (!found) {
            // wrap around
            cursor = cm.getSearchCursor(q, reverse ? CodeMirror.Pos(cm.lastLine()) : CodeMirror.Pos(0, 0), !caseF && typeof q === "string");
            found  = reverse ? cursor.findPrevious() : cursor.findNext();
        }
        if (found) {
            cm.setSelection(cursor.from(), cursor.to());
            cm.scrollIntoView({from: cursor.from(), to: cursor.to()}, 80);
            updateCount(cm, q, caseF);
        }
    }

    // Count capped at MAX_COUNT to avoid blocking on huge files
    var MAX_COUNT = 200;
    var _countTimer = null;

    function updateCount(cm, q, caseF) {
        var counter = panel && panel.querySelector('.cet-match-count');
        if (!counter) return;
        clearTimeout(_countTimer);
        _countTimer = setTimeout(function () {
            if (!panel) return;
            var count = 0;
            var cur = cm.getSearchCursor(q, CodeMirror.Pos(0, 0), !caseF && typeof q === "string");
            while (cur.findNext()) {
                count++;
                if (count > MAX_COUNT) { counter.textContent = ">" + MAX_COUNT; counter.className = "cet-match-count"; return; }
            }
            counter.textContent = count > 0 ? count + (count === 1 ? " match" : " matches") : "no matches";
            counter.className = "cet-match-count" + (count === 0 ? " cet-no-match" : "");
        }, 80);
    }

    // ── Replace ───────────────────────────────────────────────────────────
    function replaceCurrent(cm) {
        var qStr = panel.querySelector('.cet-search-input').value;
        var rStr = panel.querySelector('.cet-replace-input').value;
        if (!qStr) return;
        var caseF = panel.querySelector('.cet-match-case').checked;
        var useRe = panel.querySelector('.cet-use-regex').checked;
        var q     = buildQuery(qStr, useRe, caseF);
        if (!q) return;

        var sel = cm.getSelection();
        // if current selection matches query, replace it
        var cursor = cm.getSearchCursor(q, cm.getCursor("start"), !caseF && typeof q === "string");
        if (cursor.findNext() && CodeMirror.cmpPos(cursor.from(), cm.getCursor("start")) === 0) {
            cursor.replace(rStr);
        }
        // then find next
        find(cm, false);
    }

    function replaceAll(cm) {
        var qStr = panel.querySelector('.cet-search-input').value;
        var rStr = panel.querySelector('.cet-replace-input').value;
        if (!qStr) return;
        var caseF = panel.querySelector('.cet-match-case').checked;
        var useRe = panel.querySelector('.cet-use-regex').checked;
        var q     = buildQuery(qStr, useRe, caseF);
        if (!q) return;

        cm.operation(function () {
            var cursor = cm.getSearchCursor(q, CodeMirror.Pos(0, 0), !caseF && typeof q === "string");
            var count = 0;
            while (cursor.findNext()) { cursor.replace(rStr); count++; }
            var counter = panel.querySelector('.cet-match-count');
            if (counter) {
                counter.textContent = count + " replacement" + (count !== 1 ? "s" : "");
                counter.className = "cet-match-count";
            }
        });
        clearOverlay(cm);
    }

    // ── Panel DOM ─────────────────────────────────────────────────────────
    function buildPanel(cm) {
        var d = document.createElement('div');
        d.className = 'cet-search-panel cet-panel-top-right';
    d.addEventListener('mousedown', function(e) { e.stopPropagation(); });
    d.addEventListener('submit',    function(e) { e.preventDefault(); e.stopPropagation(); });
    // Prevent any click/mousedown inside the panel from reaching the form
    d.addEventListener('mousedown', function(e) { e.stopPropagation(); });
    d.addEventListener('submit', function(e) { e.preventDefault(); e.stopPropagation(); });
        d.innerHTML = [
            '<div class="cet-row">',
            '  <div class="cet-input-wrap">',
            '    <input class="cet-search-input" type="text" placeholder="Find…" spellcheck="false">',
            '    <span class="cet-match-count"></span>',
            '  </div>',
            '  <button type="button" class="cet-btn cet-btn-prev" title="Previous (Shift+Enter)">&#8593;</button>',
            '  <button type="button" class="cet-btn cet-btn-next" title="Next (Enter)">&#8595;</button>',
            '  <label class="cet-toggle cet-toggle-compact" title="Match case"><input type="checkbox" class="cet-match-case"><span>Aa</span></label>',
            '  <label class="cet-toggle cet-toggle-compact" title="Use regex"><input type="checkbox" class="cet-use-regex"><span>.*</span></label>',
            '  <button type="button" class="cet-btn cet-btn-close" title="Close (Esc)">&#x2715;</button>',
            '</div>',
            '<div class="cet-row cet-replace-row">',
            '  <div class="cet-input-wrap">',
            '    <input class="cet-replace-input" type="text" placeholder="Replace…" spellcheck="false">',
            '  </div>',
            '  <button type="button" class="cet-btn cet-btn-replace" title="Replace current">Replace</button>',
            '  <button type="button" class="cet-btn cet-btn-replace-all" title="Replace all">All</button>',
            '</div>',
        ].join('');

       // Translate panel strings via L_()
    (function() {
        var map = [
            ['.cet-search-input',   'placeholder', 'Find…'],
            ['.cet-replace-input',  'placeholder', 'Replace…'],
            ['.cet-btn-prev',       'title', 'Previous (Shift+Enter)'],
            ['.cet-btn-next',       'title', 'Next (Enter)'],
            ['.cet-btn-close',      'title', 'Close (Esc)'],
            ['.cet-btn-replace',    'title', 'Replace current'],
            ['.cet-btn-replace-all','title', 'Replace all'],
        ];
        map.forEach(function(m) {
            var el = d.querySelector(m[0]);
            if (el) el.setAttribute(m[1], L_(m[2]));
        });
        var rb = d.querySelector('.cet-btn-replace');     if (rb) rb.textContent = L_('Replace current');
        var ra = d.querySelector('.cet-btn-replace-all'); if (ra) ra.textContent = L_('Replace all');
        var lc = d.querySelector('.cet-match-case'); if (lc && lc.closest) { var ll=lc.closest('label'); if(ll) ll.setAttribute('title', L_('Match case')); }
        var lr = d.querySelector('.cet-use-regex');  if (lr && lr.closest) { var ll=lr.closest('label'); if(ll) ll.setAttribute('title', L_('Use regex'));  }
    })();

     // Events
    var _inputTimer = null;
    d.querySelector('.cet-search-input').addEventListener('input', function () {
            var val = this.value;
            clearTimeout(_inputTimer);
            // Clear overlay immediately so stale highlights don't linger
            clearOverlay(cm);
            d.querySelector('.cet-match-count').textContent = '';
            if (!val) return;
            // Debounce: only apply overlay + count after user pauses typing
            _inputTimer = setTimeout(function () {
                if (!panel) return;
                var caseF = d.querySelector('.cet-match-case').checked;
                var useRe = d.querySelector('.cet-use-regex').checked;
                var q = buildQuery(val, useRe, caseF);
                if (!q) return;
                applyOverlay(cm, q, caseF);
                updateCount(cm, q, caseF);
            }, 350);
        });

        d.querySelector('.cet-search-input').addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); find(cm, false); }
            if (e.key === 'Enter' &&  e.shiftKey) { e.preventDefault(); find(cm, true);  }
            if (e.key === 'Escape') { close(cm); }
        });

        d.querySelector('.cet-replace-input').addEventListener('keydown', function (e) {
            if (e.key === 'Enter')  { e.preventDefault(); replaceCurrent(cm); }
            if (e.key === 'Escape') { close(cm); }
        });

        function refreshFromCheckbox() {
            var val   = d.querySelector('.cet-search-input').value;
            var caseF = d.querySelector('.cet-match-case').checked;
            var useRe = d.querySelector('.cet-use-regex').checked;
            clearOverlay(cm);
            if (!val) return;
            var q = buildQuery(val, useRe, caseF);
            if (!q) return;
            applyOverlay(cm, q, caseF);
            updateCount(cm, q, caseF);
        }
        d.querySelector('.cet-match-case').addEventListener('change', refreshFromCheckbox);
        d.querySelector('.cet-use-regex').addEventListener('change', refreshFromCheckbox);

        d.querySelector('.cet-btn-next').addEventListener('click',        function () { find(cm, false); });
        d.querySelector('.cet-btn-prev').addEventListener('click',        function () { find(cm, true); });
        d.querySelector('.cet-btn-replace').addEventListener('click',     function () { replaceCurrent(cm); });
        d.querySelector('.cet-btn-replace-all').addEventListener('click', function () { replaceAll(cm); });
        d.querySelector('.cet-btn-close').addEventListener('click',       function () { close(cm); });

        return d;
    }

    // ── Public API ────────────────────────────────────────────────────────
    function open(cm) {
        if (panel) { panel.querySelector('.cet-search-input').focus(); panel.querySelector('.cet-search-input').select(); return; }
        panel = buildPanel(cm);
        cm.getWrapperElement().appendChild(panel);

        // Pre-fill with current selection (if single-line)
        var sel = cm.getSelection().trim();
        if (sel && !sel.includes('\n')) {
            var inp = panel.querySelector('.cet-search-input');
            inp.value = sel;
            var caseF = panel.querySelector('.cet-match-case').checked;
            var useRe = panel.querySelector('.cet-use-regex').checked;
            var q = buildQuery(sel, useRe, caseF);
            if (q) { applyOverlay(cm, q, caseF); updateCount(cm, q, caseF); }
        }

        panel.querySelector('.cet-search-input').focus();
        panel.querySelector('.cet-search-input').select();

        // Global Escape to close
        function onKey(e) {
            if (e.key === 'Escape') { close(cm); document.removeEventListener('keydown', onKey, true); }
        }
        document.addEventListener('keydown', onKey, true);
    }

    function close(cm) {
        if (panel) { panel.parentNode && panel.parentNode.removeChild(panel); panel = null; }
        clearOverlay(cm);
        cm.focus();
    }

    function toggle(cm) {
        if (panel) close(cm); else open(cm);
    }

    return { open: open, close: close, toggle: toggle };
})();
