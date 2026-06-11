/**
 * wbePwGen — Password Strength Meter, Generator & Confirm Field
 * Vanilla JS, no dependencies.
 *
 * @author    Christian M. Stefan (https://www.wbEasy.de)
 * @copyright Christian M. Stefan
 * @license   GNU General Public License v2 (GPL-2.0)
 *            https://www.gnu.org/licenses/gpl-2.0.html
 *
 * Usage:
 *   WbePwGen.attach('input-id', 'container-id', options);
 */
(function (global) {
    'use strict';

    /* ── Allowed: all printable ASCII (space 0x20 through tilde 0x7E) ─────── */
    var ALLOWED_RE = /^[\x20-\x7E]$/;

    /* Characters used by the generator — printable ASCII minus ambiguous ones */
    var GEN_LOWER   = 'abcdefghijklmnopqrstuvwxyz';
    var GEN_UPPER   = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    var GEN_DIGITS  = '0123456789';
    var GEN_SPECIAL = '!\"#$%&\'()*+,-./:;<=>?@[\\]^_`{|}~';
    var GEN_ALL     = GEN_LOWER + GEN_UPPER + GEN_DIGITS + GEN_SPECIAL;
    /* Spaces are allowed when typed but NOT injected by the generator
       (copy-paste trimming risk). They do count toward the score silently. */

    var DEFAULTS = {
        minLength:        12,
        genLength:        12,
        maxWidth:         0,    /* explicit row max-width in px; 0 = auto-detect from theme */
        showGenerate:     true,
        generateLabel:    '\u21ba',          /* ↺ */
        generateTitle:    'Generate password',
        levels:           ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'],
        nudges: [
            'Enter at least %d characters',
            'Keep going \u2014 try mixing letters & numbers',
            'Almost there \u2014 add special characters',
            'Just %d more\u2026'
        ],
        messages: {
            allSameClass: 'Weak \u2014 mix letters, numbers and special characters',
            noUpper:      'Add an uppercase letter to strengthen',
            noLower:      'Add a lowercase letter to strengthen',
            noNumber:     'Add a number to strengthen',
            noSpecial:    'Add a special character',
            good:         'Good password',
            strong:       'Strong password \u2713'
        },
        invalidMsg:       'Invalid character: %s',
        invalidMsgPlural: 'Invalid characters: %s',
        /* How to label specific control characters */
        charNames: {
            '\t':  'tab',
            '\n':  'newline',
            '\r':  'newline',
            '\x00':'null'
        },
        confirmId:        '',
        confirmMatch:     'Passwords match \u2713',
        confirmNoMatch:   'Passwords do not match',
        showToggle:       true,
        toggleShowLabel:  '\uD83D\uDC41',
        toggleHideLabel:  '\uD83D\uDC41',
        showCopy:         true,
        copyLabel:        '\uD83D\uDCCB\uFE0E',  /* 📋︎ */
        copiedLabel:      '\u2713',         /* ✓  */
        copyTitle:        'Copy to clipboard'
    };

    /* ── Score ────────────────────────────────────────────────────────────── */
    function removeRepeats(seqLen, str) {
        var out = '';
        for (var i = 0; i < str.length; i++) {
            var rep = true;
            for (var j = 0; j < seqLen && j + i + seqLen < str.length; j++)
                rep = rep && (str[j+i] === str[j+i+seqLen]);
            if (j < seqLen) rep = false;
            if (rep) { i += seqLen - 1; } else { out += str[i]; }
        }
        return out;
    }

    function numericScore(val) {
        var s = 4 * val.length;
        s += removeRepeats(1,val).length - val.length;
        s += removeRepeats(2,val).length - val.length;
        s += removeRepeats(3,val).length - val.length;
        s += removeRepeats(4,val).length - val.length;
        if (val.match(/(.*[0-9].*[0-9].*[0-9])/))           s += 5;
        if (val.match(/(.*\W.*\W)/))                        s += 5;
        if (val.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))     s += 10;
        if (val.match(/[a-zA-Z]/) && val.match(/[0-9]/))    s += 15;
        if (val.match(/\W/) && val.match(/[0-9]/))          s += 15;
        if (val.match(/\W/) && val.match(/[a-zA-Z]/))       s += 15;
        if (val.match(/^[a-zA-Z0-9]+$/))                     s -= 10;   /* pure alpha-numeric only: penalise; space counts as special */
        return Math.min(100, Math.max(0, s));
    }

    function bucket(s) {
        if (s < 20) return 1; if (s < 40) return 2;
        if (s < 60) return 3; if (s < 80) return 4; return 5;
    }

    /* ── Bad-char formatter ───────────────────────────────────────────────── */
    function formatBadChars(bad, cfg) {
        return bad.map(function(ch) {
            var name = cfg.charNames[ch];
            if (name) return '"' + name + '"';
            /* Other control chars: show hex code */
            var code = ch.charCodeAt(0);
            if (code < 32 || code === 127)
                return '"0x' + code.toString(16).toUpperCase() + '"';
            return '"' + ch + '"';
        }).join(', ');
    }

    /* ── Message resolver ─────────────────────────────────────────────────── */
    function resolveMessage(val, bad, cfg) {
        var n = cfg.minLength, m = cfg.messages;

        if (bad.length) {
            var f = formatBadChars(bad, cfg);
            return (bad.length === 1 ? cfg.invalidMsg : cfg.invalidMsgPlural)
                   .replace('%s', f);
        }
        if (val.length < n) {
            var idx = Math.min(Math.floor(val.length / n * 4), cfg.nudges.length - 1);
            /* Last nudge bucket: %d = remaining chars; earlier buckets: %d = minLength */
            var sub = (idx === cfg.nudges.length - 1) ? (n - val.length) : n;
            return cfg.nudges[idx].replace('%d', sub);
        }
        var hasU=/[A-Z]/.test(val), hasL=/[a-z]/.test(val),
            hasN=/[0-9]/.test(val), hasS=/[^a-zA-Z0-9]/.test(val),
            same=/^[a-zA-Z]+$/.test(val) || /^[0-9]+$/.test(val);
        if (same && !hasS) return m.allSameClass;
        if (!hasU) return m.noUpper;
        if (!hasL) return m.noLower;
        if (!hasN) return m.noNumber;
        if (!hasS) return m.noSpecial;
        return numericScore(val) >= 80 ? m.strong : m.good;
    }

    /* ── Generator ────────────────────────────────────────────────────────── */
    function generatePassword(len) {
        /* Guarantee at least one from each group */
        function pick(s){ return s[Math.floor(Math.random()*s.length)]; }
        var all = [pick(GEN_LOWER), pick(GEN_UPPER), pick(GEN_DIGITS), pick(GEN_SPECIAL)];
        while (all.length < len) all.push(pick(GEN_ALL));
        /* Fisher-Yates shuffle */
        for (var i=all.length-1; i>0; i--) {
            var j=Math.floor(Math.random()*(i+1)), t=all[i]; all[i]=all[j]; all[j]=t;
        }
        return all.join('');
    }

    /* ── Instance ─────────────────────────────────────────────────────────── */
    function Instance(input, container, opts) {
        this.input     = input;
        this.container = container;
        this.cfg       = merge(DEFAULTS, opts||{});
        this.confirm   = null;
        this._revealed = false;
        this._build();
        this._listen();
        this._renderBar(0);
        this._renderHint('');
    }

    Instance.prototype._build = function() {
        var c = this.cfg, self = this, input = this.input;

        /* Read the input's computed max-width BEFORE we restructure the DOM.
         * Once the input sits inside .wpg-input-wrap our CSS forces max-width:none,
         * so this is our only chance to capture the theme's real constraint.
         * We apply the value to the row so the whole widget (input + gen button)
         * shares that width — context-aware, no hardcoded values. */
        var naturalMaxW = window.getComputedStyle(input).maxWidth; /* e.g. '300px' or 'none' */

        /*
         * Layout:
         *   .wpg-input-row
         *     .wpg-input-wrap          ← relative, flex:1
         *       <input>
         *       .wpg-eye               ← absolute, inside input
         *     .wpg-btn.wpg-gen         ← flex sibling, align-self:stretch
         */
        var row = document.createElement('div');
        row.className = 'wpg-input-row';
        /* Priority: 1) explicit cfg.maxWidth option  2) theme's CSS max-width on the input */
        var rowMaxW = (c.maxWidth > 0) ? (c.maxWidth + 'px')
                    : (naturalMaxW !== 'none' ? naturalMaxW : null);
        if (rowMaxW) {
            row.style.maxWidth           = rowMaxW;
            this.container.style.maxWidth = rowMaxW; /* bar + hint match input width */
        }
        input.parentNode.insertBefore(row, input);

        /* Relative wrapper so the eye can overlay the input */
        var wrap = document.createElement('div');
        wrap.className = 'wpg-input-wrap';
        wrap.appendChild(input);
        row.appendChild(wrap);

        /* Eye toggle — absolutely positioned inside the input */
        if (c.showToggle) {
            var eye = document.createElement('button');
            eye.type        = 'button';
            eye.className   = 'wpg-eye';
            eye.tabIndex    = -1;
            eye.setAttribute('aria-label', 'Show/hide password');
            eye.textContent = c.toggleShowLabel;
            wrap.appendChild(eye);

            eye.addEventListener('click', function() {
                self._revealed = !self._revealed;
                input.type      = self._revealed ? 'text' : 'password';
                eye.textContent = self._revealed ? c.toggleHideLabel : c.toggleShowLabel;
                eye.classList.toggle('wpg-btn-active', self._revealed);
                if (self._copy) {
                    self._copy.style.display = self._revealed ? '' : 'none';
                }
            });
            this._eye = eye;

            /* Copy button — flex sibling, hidden until password is revealed */
            if (c.showCopy) {
                var copy = document.createElement('button');
                copy.type        = 'button';
                copy.className   = 'wpg-btn wpg-copy';
                copy.tabIndex    = -1;
                copy.setAttribute('aria-label', c.copyTitle);
                copy.setAttribute('title',      c.copyTitle);
                copy.textContent = c.copyLabel;
                copy.style.display = 'none';
                row.appendChild(copy);
                copy.addEventListener('click', function() { self._copyPassword(); });
                this._copy = copy;
            }
        }

        /* Generate button — flex sibling of the input wrap */
        if (c.showGenerate) {
            var gen = document.createElement('button');
            gen.type      = 'button';
            gen.className = 'wpg-btn wpg-gen';
            gen.tabIndex  = -1;
            gen.setAttribute('aria-label', c.generateTitle);
            gen.setAttribute('title',      c.generateTitle);
            gen.textContent = c.generateLabel;
            row.appendChild(gen);

            gen.addEventListener('click', function() { self._generate(); });
            this._gen = gen;
        }

        /* Widget HTML */
        this.container.innerHTML =
            '<div class="wpg-bar">' +
              '<div class="wpg-track"><div class="wpg-fill"></div></div>' +
              '<span class="wpg-label"></span>' +
            '</div>' +
            '<div class="wpg-hint"></div>' +
            '<div class="wpg-warn"></div>';

        this._fill = this.container.querySelector('.wpg-fill');
        this._lbl  = this.container.querySelector('.wpg-label');
        this._hint = this.container.querySelector('.wpg-hint');
        this._warn = this.container.querySelector('.wpg-warn');

        /* Confirm feedback — injected directly after the confirm input */
        this._cfm = document.createElement('div');
        this._cfm.className = 'wpg-confirm';

        if (c.confirmId) {
            this.confirm = document.getElementById(c.confirmId);
            if (this.confirm) {
                this.confirm.addEventListener('input', function(){ self._renderConfirm(); });
                /* Insert after the confirm input's row wrapper (or the input itself) */
                var anchor = this.confirm.closest('.wpg-input-row') || this.confirm;
                anchor.parentNode.insertBefore(self._cfm, anchor.nextSibling);
            }
        }
    };

    Instance.prototype._generate = function() {
        var pw = generatePassword(this.cfg.genLength);
        this.input.value = pw;
        /* Reveal so user can read & copy */
        if (!this._revealed && this._eye) {
            this._revealed  = true;
            this.input.type = 'text';
            this._eye.textContent = this.cfg.toggleHideLabel;
            this._eye.classList.add('wpg-btn-active');
            if (this._copy) { this._copy.style.display = ''; }
        }
        if (this.confirm) this.confirm.value = pw;
        this.input.dispatchEvent(new Event('input'));
        this.input.focus();
        this.input.select();
    };

    Instance.prototype._copyPassword = function() {
        var self = this, val = this.input.value;
        if (!val) return;
        var btn = this._copy;
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(val)
                .then(function()  { self._flashCopied(btn); })
                .catch(function() { self._legacyCopy(btn);  });
        } else {
            this._legacyCopy(btn);
        }
    };

    Instance.prototype._legacyCopy = function(btn) {
        var input = this.input;
        input.select();
        try { document.execCommand('copy'); this._flashCopied(btn); } catch(e) { /* silent */ }
        input.setSelectionRange(0, 0);
    };

    Instance.prototype._flashCopied = function(btn) {
        var self = this;
        btn.textContent = this.cfg.copiedLabel;
        btn.classList.add('wpg-copied');
        setTimeout(function() {
            btn.textContent = self.cfg.copyLabel;
            btn.classList.remove('wpg-copied');
        }, 1500);
    };

    Instance.prototype._listen = function() {
        var self = this;
        this.input.addEventListener('input', function(){ self.update(); });
    };

    Instance.prototype.update = function() {
        var val = this.input.value;

        /* Detect bad chars (control characters only) */
        var bad = [];
        for (var i = 0; i < val.length; i++) {
            var ch = val[i], code = ch.charCodeAt(0);
            var isControl = (code < 32 || code === 127) && ch !== ' ';
            /* Space (32) is allowed — only true control chars are bad */
            if (isControl && bad.indexOf(ch) === -1) bad.push(ch);
        }

        var score = 0, rawPct = 0;
        if (val.length > 0) {
            if (bad.length) {
                score  = 1;
                rawPct = 20;
            } else {
                var ns = numericScore(val);
                /* Scale quality proportionally when below minLength — no hard cap */
                if (val.length < this.cfg.minLength) {
                    ns = Math.floor(ns * val.length / this.cfg.minLength);
                }
                score  = bucket(ns);
                rawPct = ns;
            }
        }

        this._renderBar(score, rawPct);
        this._renderHint(val, bad, score);
        this._renderWarn(bad);
        this._renderConfirm();
    };

    Instance.prototype._renderBar = function(score, rawPct) {
        /* rawPct (0-100): use the actual score for smooth fill; fall back to bucket steps */
        var pct = score > 0 ? (rawPct !== undefined ? rawPct : score * 20) : 0;
        var cls = score > 0 ? 'wpg-s'+score : '';
        this._fill.style.width     = pct + '%';
        this._fill.className       = 'wpg-fill' + (cls ? ' '+cls : '');
        this._lbl.textContent      = score > 0 ? (this.cfg.levels[score-1]||'') : '';
        this._lbl.className        = 'wpg-label'+(cls?' '+cls:'');
    };

    Instance.prototype._renderHint = function(val, bad, score) {
        var el = this._hint;
        if (!val) {
            el.className   = 'wpg-hint wpg-hint-idle';
            el.textContent = this.cfg.nudges[0].replace('%d', this.cfg.minLength);
            return;
        }
        el.className   = 'wpg-hint wpg-s' + score;
        el.textContent = resolveMessage(val, bad || [], this.cfg);
    };

    Instance.prototype._renderWarn = function(bad) {
        var el = this._warn;
        if (!bad || !bad.length) { el.className = 'wpg-warn'; el.textContent = ''; return; }
        el.className   = 'wpg-warn wpg-warn-visible';
        el.textContent = (bad.length === 1 ? this.cfg.invalidMsg : this.cfg.invalidMsgPlural)
                         .replace('%s', formatBadChars(bad, this.cfg));
    };

    Instance.prototype._renderConfirm = function() {
        if (!this.confirm) return;
        var pw=this.input.value, cfm=this.confirm.value, el=this._cfm;
        if (!cfm) { el.className='wpg-confirm'; el.textContent=''; return; }
        var match = cfm === pw;
        el.className   = 'wpg-confirm '+(match?'wpg-cfm-match':'wpg-cfm-nomatch');
        el.textContent = match ? this.cfg.confirmMatch : this.cfg.confirmNoMatch;
    };

    /* ── Helpers ──────────────────────────────────────────────────────────── */
    function merge(def, opt) {
        var o = {};
        for (var k in def) {
            if (!Object.prototype.hasOwnProperty.call(def,k)) continue;
            if (def[k] && typeof def[k]==='object' && !Array.isArray(def[k]))
                o[k] = merge(def[k], opt[k]||{});
            else
                o[k] = Object.prototype.hasOwnProperty.call(opt,k) ? opt[k] : def[k];
        }
        return o;
    }

    /* ── Public API ───────────────────────────────────────────────────────── */
    global.WbePwGen = {
        attach: function(inputId, containerId, opts) {
            var inp = typeof inputId==='string' ? document.getElementById(inputId) : inputId;
            var con = typeof containerId==='string' ? document.getElementById(containerId) : containerId;
            if (!inp||!con) {
                console.warn('WbePwGen.attach: not found', inputId, containerId);
                return null;
            }
            return new Instance(inp, con, opts||{});
        }
    };

}(window));
