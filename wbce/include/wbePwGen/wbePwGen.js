/**
 * wbe_pw_gen — Password Strength & Validation Widget
 *
 * Vanilla JS, no dependencies.
 *
 * Usage:
 *   WbePwGen.attach('input-id', 'container-id', options);
 *
 * Options:
 *   minLength      {number}   Minimum required length (default: 12)
 *   levels         {string[]} Five labels for scores 1–5
 *   hints          {object}   Hint strings (length, case, number, special)
 *   warn           {object}   Warning strings (invalid, allowed)
 *   allowedDisplay {string}   Human-readable allowed-chars string
 *
 * The widget fires on the native 'input' event of the target element, so it
 * works automatically when the value is changed programmatically via
 *   el.dispatchEvent(new Event('input'))
 */
(function (global) {
    'use strict';

    /* ── Allowed character test ──────────────────────────────────────────── */
    var ALLOWED_RE = /[a-zA-Z0-9_\-!#*+@$&:]/;

    /* ── Defaults (all overridable via options) ──────────────────────────── */
    var DEFAULTS = {
        minLength: 12,
        levels: [
            'Very Weak',
            'Weak',
            'Fair',
            'Good',
            'Strong'
        ],
        hints: {
            length:  'At least {n} characters',
            case:    'Upper + lowercase letters',
            number:  'Contains a number',
            special: 'Special char (_-!#*+@$&:)'
        },
        warn: {
            invalid: 'Invalid characters:',
            allowed: 'Allowed:'
        },
        allowedDisplay: 'a–z   A–Z   0–9   _ - ! # * + @ $ & :'
    };

    /* ── Instance ────────────────────────────────────────────────────────── */
    function Instance(input, container, opts) {
        this.input     = input;
        this.container = container;
        this.cfg       = merge(DEFAULTS, opts || {});
        this.minLen    = this.cfg.minLength;
        this._build();
        this._listen();
    }

    Instance.prototype._build = function () {
        var c   = this.cfg;
        var hl  = c.hints.length.replace('{n}', this.minLen);

        this.container.innerHTML =
            '<div class="wpg-bar">' +
            '  <div class="wpg-segments">' +
            '    <span></span><span></span><span></span><span></span><span></span>' +
            '  </div>' +
            '  <span class="wpg-label"></span>' +
            '</div>' +
            '<ul class="wpg-hints">' +
            '  <li data-k="len">'  + esc(hl)              + '</li>' +
            '  <li data-k="case">' + esc(c.hints.case)    + '</li>' +
            '  <li data-k="num">'  + esc(c.hints.number)  + '</li>' +
            '  <li data-k="spec">' + esc(c.hints.special) + '</li>' +
            '</ul>' +
            '<div class="wpg-warn">' +
            '  <div class="wpg-warn-top">' +
            '    <span class="wpg-warn-icon">⚠</span>' +
            '    <span class="wpg-warn-msg">' + esc(c.warn.invalid) + '</span>' +
            '    <code class="wpg-warn-chars"></code>' +
            '  </div>' +
            '  <div class="wpg-allowed">' +
            '    ' + esc(c.warn.allowed) + ' <code>' + esc(c.allowedDisplay) + '</code>' +
            '  </div>' +
            '</div>';

        /* Cache DOM refs */
        this._segs  = this.container.querySelectorAll('.wpg-segments span');
        this._lbl   = this.container.querySelector('.wpg-label');
        this._warn  = this.container.querySelector('.wpg-warn');
        this._wChars = this.container.querySelector('.wpg-warn-chars');
        this._hints = {};
        var self = this;
        this.container.querySelectorAll('[data-k]').forEach(function (li) {
            self._hints[li.getAttribute('data-k')] = li;
        });
    };

    Instance.prototype._listen = function () {
        var self = this;
        this.input.addEventListener('input', function () { self.update(); });
    };

    Instance.prototype.update = function () {
        var val = this.input.value;

        if (!val) {
            this.container.classList.remove('wpg-visible');
            return;
        }
        this.container.classList.add('wpg-visible');

        /* ── Invalid-char detection ─────────────────── */
        var bad = [];
        for (var i = 0; i < val.length; i++) {
            var ch = val[i];
            if (!ALLOWED_RE.test(ch) && bad.indexOf(ch) === -1) {
                bad.push(ch);
            }
        }
        if (bad.length) {
            this._warn.classList.add('wpg-warn-visible');
            this._wChars.textContent = bad.join('  ');
        } else {
            this._warn.classList.remove('wpg-warn-visible');
        }

        /* ── Criteria ───────────────────────────────── */
        var len     = val.length;
        var okLen   = len >= this.minLen;
        var okLong  = len >= 16;
        var okUpper = /[A-Z]/.test(val);
        var okLower = /[a-z]/.test(val);
        var okCase  = okUpper && okLower;
        var okNum   = /[0-9]/.test(val);
        var okSpec  = /[_\-!#*+@$&:]/.test(val);

        this._hint('len',  okLen);
        this._hint('case', okCase);
        this._hint('num',  okNum);
        this._hint('spec', okSpec);

        /* ── Score 1–5 ──────────────────────────────── */
        var score;
        if (bad.length) {
            score = 1;
        } else {
            score  = 0;
            if (okLen)  score++;
            if (okLong) score++;
            if (okCase) score++;
            if (okNum)  score++;
            if (okSpec) score++;
            score = Math.min(5, Math.max(1, score));
        }

        this._render(score);
    };

    Instance.prototype._hint = function (key, ok) {
        var li = this._hints[key];
        if (!li) return;
        li.classList.toggle('wpg-ok',  ok);
        li.classList.toggle('wpg-bad', !ok);
    };

    Instance.prototype._render = function (score) {
        var cls = 'wpg-s' + score;
        for (var i = 0; i < this._segs.length; i++) {
            var seg = this._segs[i];
            seg.className = i < score ? ('wpg-on ' + cls) : '';
        }
        this._lbl.textContent = this.cfg.levels[score - 1] || '';
        this._lbl.className   = 'wpg-label ' + cls;
    };

    /* ── Helpers ─────────────────────────────────────────────────────────── */
    function merge(defaults, opts) {
        var out = {};
        for (var k in defaults) {
            if (Object.prototype.hasOwnProperty.call(defaults, k)) {
                if (defaults[k] && typeof defaults[k] === 'object' && !Array.isArray(defaults[k])) {
                    out[k] = merge(defaults[k], opts[k] || {});
                } else {
                    out[k] = Object.prototype.hasOwnProperty.call(opts, k) ? opts[k] : defaults[k];
                }
            }
        }
        return out;
    }

    function esc(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    /* ── Public API ──────────────────────────────────────────────────────── */
    global.WbePwGen = {
        /**
         * Attach the strength widget.
         *
         * @param  {string|Element} inputId     Target <input> or its id
         * @param  {string|Element} containerId Container element or its id
         * @param  {object}         opts        Options (see DEFAULTS above)
         * @return {Instance|null}
         */
        attach: function (inputId, containerId, opts) {
            var inp = typeof inputId     === 'string' ? document.getElementById(inputId)     : inputId;
            var con = typeof containerId === 'string' ? document.getElementById(containerId) : containerId;
            if (!inp || !con) {
                console.warn('WbePwGen.attach: element not found', inputId, containerId);
                return null;
            }
            return new Instance(inp, con, opts || {});
        }
    };

}(window));
