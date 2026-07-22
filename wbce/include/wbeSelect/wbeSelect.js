/*!
 * wbeSelect — Vanilla JS Select Widget
 * Version 2.0.0 (vanilla rewrite)
 *
 * Inspired by and derived from Selectator by QODIO
 * https://github.com/QODIO/selectator
 *
 * ─────────────────────────────────────────────────────────────────────────
 * MIGRATION NOTES (coming from the jQuery build, wbeSelect_jquery.js)
 * ─────────────────────────────────────────────────────────────────────────
 * This file has ZERO dependency on jQuery. The core is a plain ES6 class,
 * `WbeSelect`, attached to `window.WbeSelect`.
 *
 * If `window.jQuery` is present when this script runs, a small compatibility
 * bridge is attached automatically at the bottom of this file, restoring the
 * exact old calling convention:
 *
 *     $('#mySelect').wbeSelect({ customSelector: 'cfg-visibility' });
 *     $('#mySelect').wbeSelect('refresh');
 *     $('#mySelect').data('wbeSelect').refresh();
 *
 * ...so existing WBCE core code that calls the plugin this way needs NO
 * changes at all — just point the <script> tag at this file instead of
 * wbeSelect_jquery.js (jQuery, if still loaded on the page for other
 * reasons, is picked up automatically; it is not required by this plugin
 * itself).
 *
 * Native/vanilla equivalent, if you don't use the jQuery bridge:
 *
 *     WbeSelect.init(document.getElementById('mySelect'), { customSelector: 'cfg-visibility' });
 *     WbeSelect.get(document.getElementById('mySelect')).refresh();
 *
 * A few behaviours changed shape (not spirit) in the rewrite — worth knowing
 * even if you keep using the jQuery bridge:
 *
 *   1. `wbeSelect:created` (allowNew feature) is now a native CustomEvent.
 *      The created values are on `event.detail`, not a second handler
 *      argument:
 *        old:  $sel.on('wbeSelect:created', function (e, created) { ... });
 *        new:  sel.addEventListener('wbeSelect:created', (e) => { const created = e.detail; });
 *      (Through the jQuery bridge, jQuery still receives the event; read
 *      the payload via `e.originalEvent.detail` in that case.)
 *
 *   2. The old `$option.data('item_data', {...})` escape hatch for
 *      attaching arbitrary JS data (e.g. inline SVG/color) to an <option>
 *      without fighting HTML attribute quoting is now:
 *        WbeSelect.setItemData(optionEl, { svg: '<svg>…</svg>', color: '#069ee3' });
 *
 *   3. `removeSelection()` now takes a plain DOM element (the `.ws-tag` div),
 *      not a jQuery-wrapped one.
 *
 *   4. The old `options.$source_element` / `.$container_element` / etc.
 *      (jQuery objects stuffed onto the options object for external access)
 *      are now plain DOM element properties on the instance:
 *      `instance.sourceElement`, `instance.containerElement`,
 *      `instance.selectedItemsElement`, `instance.inputElement`,
 *      `instance.textlengthElement`, `instance.optionsElement`.
 *
 * wbeSelect.css is unchanged and works with both builds — it only targets
 * class names, not jQuery.
 *
 * MIT License
 * Copyright (c) 2025-2026 Christian M. Stefan
 * Copyright (c) 2013 QODIO
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
(function (global) {
    'use strict';

    // ── SMALL HELPERS (module-scoped, no external deps) ────────────────────────

    /** Recursive plain-object merge (jQuery $.extend(true, …) replacement).
     *  Arrays and functions are copied by reference / whole-value replace,
     *  never index-merged — this is a deliberate, safer behaviour than
     *  jQuery's historical array-index-merge quirk; nothing in this plugin
     *  relies on partial array merging. */
    function deepMerge(target, ...sources) {
        for (const source of sources) {
            if (!source) continue;
            for (const key of Object.keys(source)) {
                const val = source[key];
                if (val && typeof val === 'object' && !Array.isArray(val)) {
                    target[key] = deepMerge(
                        (target[key] && typeof target[key] === 'object' && !Array.isArray(target[key])) ? target[key] : {},
                        val
                    );
                } else {
                    target[key] = val;
                }
            }
        }
        return target;
    }

    /** jQuery .addClass(str) accepted space-separated class strings;
     *  Element.classList.add() throws on a single multi-token string, so
     *  callers that pass through arbitrary data-class="a b c" values need
     *  this instead of a raw classList.add(). */
    function addClasses(el, str) {
        if (!str) return;
        for (const c of String(str).trim().split(/\s+/)) {
            if (c) el.classList.add(c);
        }
    }

    /** Event delegation helper (jQuery's `.on(type, selector, handler)`).
     *  `handler` is invoked with `this` bound to the matched descendant,
     *  matching jQuery's delegated-handler `this` binding. */
    function delegate(root, type, selector, handler) {
        root.addEventListener(type, (e) => {
            const target = e.target.closest(selector);
            if (target && root.contains(target)) {
                handler.call(target, e);
            }
        });
    }

    function escapeHtml(str) {
        return (str + '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }


    // ── THE WIDGET ───────────────────────────────────────────────────────────

    class WbeSelect {

        // ── STATIC: instance registry & item-data store ────────────────────────
        // Replaces jQuery's per-element .data('wbeSelect', instance) /
        // .data('item_data', obj) storage with plain WeakMaps — no properties
        // are written onto the DOM elements themselves for this bookkeeping.
        static #instances = new WeakMap();
        static #itemDataStore = new WeakMap();

        /** Get the WbeSelect instance already attached to `el`, if any. */
        static get(el) {
            return WbeSelect.#instances.get(el);
        }

        /** Idempotent init: returns the existing instance if `el` is already
         *  enhanced, otherwise creates one. This mirrors the old
         *  `$.fn.wbeSelect(options)` behaviour (skips re-init silently). */
        static init(el, options) {
            const existing = WbeSelect.#instances.get(el);
            if (existing) return existing;
            return new WbeSelect(el, options);
        }

        /** Attach arbitrary data to an <option> (e.g. inline svg/color) —
         *  replacement for the old `$option.data('item_data', {...})`. */
        static setItemData(optionEl, data) {
            WbeSelect.#itemDataStore.set(optionEl, data);
        }

        static getItemData(optionEl) {
            return WbeSelect.#itemDataStore.get(optionEl);
        }

        static #register(el, instance) {
            WbeSelect.#instances.set(el, instance);
        }

        static #unregister(el) {
            WbeSelect.#instances.delete(el);
        }


        // ── STATIC: defaults ────────────────────────────────────────────────────

        static defaults = {
            customSelector: '',
            height: 'auto',
            useSearch: true,
            disable_search_threshold: 7,
            showAllOptionsOnFocus: true,
            // When false, receiving focus does NOT auto-open the dropdown (only an
            // explicit click/mousedown does). Useful when the widget lives in a
            // container that programmatically focuses fields (e.g. a TinyMCE dialog),
            // where a focus-triggered open is unwanted. Default true = unchanged.
            openOnFocus: true,
            // When true, the options dropdown is positioned with `position:fixed` +
            // viewport coordinates (computed on open) instead of relying on
            // `position:absolute` relative to the widget. Needed inside a host that
            // clips/scrolls its own overflow (e.g. a modal with `overflow-y:auto`):
            // position:absolute there still correctly floats the dropdown, but the
            // host's scroll-height calculation grows to include it anyway (its
            // containing block is inside the scrolling element), making the dropdown
            // effectively push the rest of the host's content out of view. Default
            // false = unchanged (menu_link and other normal-page usages).
            fixedDropdown: false,
            selectFirstOptionOnSearch: true,
            valueField: 'value',
            textField: 'text',
            searchFields: ['value', 'text'],
            placeholder: '',
            treeView: false,
            // Creatable: when true, typing a value that isn't an existing option
            // and pressing Enter (or comma) adds it — as a new <option> in the
            // source <select>, selected. Works in both single and multiple mode
            // (multiple splits on whitespace into several tokens). Off by default
            // → every existing usage is unchanged. Fires 'wbeSelect:created' (native
            // CustomEvent, payload on event.detail — see migration notes up top)
            // with the new value(s). `sanitizeNew(value)` may transform/reject a
            // token (return '' to drop it); the default just trims.
            allowNew: false,
            sanitizeNew: null,

            // Custom SVG icon registry: array of { ref, svg, color }.
            // An option can reference an entry via data-svgref="ref"; the plugin
            // resolves it to the actual svg markup (and color, unless the option
            // sets its own data-color). Options may also skip the registry and
            // provide data-svg="<svg>...</svg>" directly. See data.color / data.svg
            // handling in the default render.option / render.selected_item below.
            svgIcons: [],

            render: {
                selected_item: function (_item, escape) {
                    let html = '';
                    // Custom SVG icon (data-svg / data-svgref, resolved before this
                    // renderer runs) takes precedence over the plain color dot.
                    // The svg markup itself is inserted unescaped — trusted
                    // admin-authored content, not user input. For the color to
                    // apply, the SVG should use fill="currentColor" (or
                    // stroke="currentColor").
                    if (typeof _item.svg !== 'undefined' && _item.svg !== '') {
                        html += '<div class="ws-tag-svg"' +
                            (typeof _item.color !== 'undefined' && _item.color !== ''
                                ? ' style="color:' + escape(_item.color) + ';"' : '') +
                            '>' + _item.svg + '</div>';
                    } else if (typeof _item.color !== 'undefined' && _item.color !== '') {
                        html += '<div class="ws-tag-color" style="background-color:' + escape(_item.color) + ';"></div>';
                    }
                    if (typeof _item.left !== 'undefined')
                        html += '<div class="ws-tag-l"><img src="' + escape(_item.left) + '" alt=""></div>';
                    if (typeof _item.right !== 'undefined')
                        html += '<div class="ws-tag-r">' + escape(_item.right) + '</div>';
                    // Language badge/flag — same contract as the option renderer
                    // (data-lang code, optional data-langflag image URL).
                    if (typeof _item.lang !== 'undefined' && _item.lang !== '') {
                        const tagLangInner = (typeof _item.langflag !== 'undefined' && _item.langflag !== '')
                            ? '<img src="' + escape(_item.langflag) + '" alt="' + escape(_item.lang) + '">'
                            : escape(_item.lang);
                        html += '<div class="ws-tag-lang" title="' + escape(_item.lang) + '">' + tagLangInner + '</div>';
                    }
                    html += '<div class="ws-tag-ttl">' + (typeof _item.text !== 'undefined' ? escape(_item.text) : '') + '</div>';
                    if (typeof _item.subtitle !== 'undefined')
                        html += '<div class="ws-tag-sub">' + escape(_item.subtitle) + '</div>';
                    html += '<div class="ws-tag-rm">✖</div>';
                    return html;
                },
                option: function (_item, escape) {
                    let html = '';
                    // See selected_item() above for the svg/color precedence and
                    // trust rationale — same contract here.
                    if (typeof _item.svg !== 'undefined' && _item.svg !== '') {
                        html += '<div class="ws-opt-svg"' +
                            (typeof _item.color !== 'undefined' && _item.color !== ''
                                ? ' style="color:' + escape(_item.color) + ';"' : '') +
                            '>' + _item.svg + '</div>';
                    } else if (typeof _item.color !== 'undefined' && _item.color !== '') {
                        html += '<div class="ws-opt-color" style="background-color:' + escape(_item.color) + ';"></div>';
                    }
                    if (typeof _item.left !== 'undefined')
                        html += '<div class="ws-opt-l"><img src="' + escape(_item.left) + '" alt=""></div>';
                    if (typeof _item.right !== 'undefined')
                        html += '<div class="ws-opt-r">' + escape(_item.right) + '</div>';
                    // Right-aligned language badge (data-lang on the option) —
                    // floated, so it must precede the title in the markup.
                    // With data-langflag (flag image URL) the flag is shown
                    // instead of the code; the code stays as alt/title text.
                    if (typeof _item.lang !== 'undefined' && _item.lang !== '') {
                        const langInner = (typeof _item.langflag !== 'undefined' && _item.langflag !== '')
                            ? '<img src="' + escape(_item.langflag) + '" alt="' + escape(_item.lang) + '">'
                            : escape(_item.lang);
                        html += '<div class="ws-opt-lang" title="' + escape(_item.lang) + '">' + langInner + '</div>';
                    }
                    html += '<div class="ws-opt-ttl">' + (typeof _item.text !== 'undefined' ? escape(_item.text) : '') + '</div>';
                    if (typeof _item.subtitle !== 'undefined')
                        html += '<div class="ws-opt-sub">' + escape(_item.subtitle) + '</div>';
                    return html;
                }
            },
            labels: {
                search: 'Search...'
            }
        };


        // ── STATIC: tree-view SVG builders (pure, no instance state needed) ────

        static #readTreeVars() {
            const s = getComputedStyle(document.documentElement);
            return {
                W: parseFloat(s.getPropertyValue('--ws-tree-indent').trim()) || 14,
                stroke: parseFloat(s.getPropertyValue('--ws-tree-stroke').trim()) || 1.5
            };
        }

        // Build SVG tree connector from a prefix string (│, ├─, └─ + NBSP sequences).
        static #buildDropdownTreeSvg(prefixRaw) {
            const prefix = (prefixRaw || '').replace(/&nbsp;/gi, ' ');
            const segments = [];
            let i = 0;
            while (i < prefix.length) {
                const c = prefix.charCodeAt(i);
                if      (c === 0x2502) { segments.push('vert');   i += 3; }         // │ + 2×NBSP
                else if (c === 0x251C) { segments.push('branch'); i += 3; break; }  // ├─ + NBSP
                else if (c === 0x2514) { segments.push('last');   i += 3; break; }  // └─ + NBSP
                else if (c === 0x00A0) { segments.push('empty');  i += 3; }         // 3×NBSP
                else                   { i++; }
            }
            if (segments.length === 0) return '';

            const { W, stroke: sw } = WbeSelect.#readTreeVars();
            const totW = segments.length * W, H = 20, midY = H / 2;
            const lines = [];
            segments.forEach((type, idx) => {
                const cx = idx * W + W / 2;
                if (type === 'vert') {
                    lines.push(`<line x1="${cx}" y1="-4" x2="${cx}" y2="${H + 4}"/>`);
                } else if (type === 'branch') {
                    lines.push(`<line x1="${cx}" y1="-4" x2="${cx}" y2="${H + 4}"/>`);
                    lines.push(`<line x1="${cx}" y1="${midY}" x2="${totW}" y2="${midY}"/>`);
                } else if (type === 'last') {
                    lines.push(`<line x1="${cx}" y1="-4" x2="${cx}" y2="${midY}"/>`);
                    lines.push(`<line x1="${cx}" y1="${midY}" x2="${totW}" y2="${midY}"/>`);
                }
            });
            return `<svg class="ws-tree-svg" width="${totW}" height="${H}" viewBox="0 0 ${totW} ${H}" `
                + `xmlns="http://www.w3.org/2000/svg" aria-hidden="true">`
                + `<g stroke="currentColor" stroke-width="${sw}" stroke-linecap="round" fill="none">`
                + lines.join('') + '</g></svg>';
        }

        // Simple depth-indicator SVG for the closed/selected state. Kept for
        // parity with the jQuery build; not currently wired into any render
        // path there either (enhanceTreePrefixes() uses dot glyphs instead —
        // see #enhanceTreePrefixes below), but available for custom renderers.
        static #buildDepthSvg(level) {
            if (level <= 0) return '';
            const { W, stroke: sw } = WbeSelect.#readTreeVars();
            const totW = level * W, H = 16, midY = H / 2;
            const lines = [];
            for (let i = 0; i < level; i++) {
                const cx = i * W + W / 2;
                if (i < level - 1) {
                    lines.push(`<line x1="${cx}" y1="0" x2="${cx}" y2="${H}"/>`);
                } else {
                    lines.push(`<line x1="${cx}" y1="0" x2="${cx}" y2="${midY}"/>`);
                    lines.push(`<line x1="${cx}" y1="${midY}" x2="${totW}" y2="${midY}"/>`);
                }
            }
            return `<svg class="ws-tree-svg" width="${totW}" height="${H}" viewBox="0 0 ${totW} ${H}" `
                + `xmlns="http://www.w3.org/2000/svg" aria-hidden="true">`
                + `<g stroke="currentColor" stroke-width="${sw}" stroke-linecap="round" fill="none">`
                + lines.join('') + '</g></svg>';
        }


        // ── INSTANCE STATE ───────────────────────────────────────────────────────

        // Public DOM refs (replaces the old options.$xxx_element pattern)
        sourceElement;
        containerElement;
        selectedItemsElement;
        inputElement;
        textlengthElement;
        optionsElement;
        options = {};

        #isSingle = true;
        #isMultiple = false;
        #hasVisibleOptions = true;
        // Tracks whether the user has explicitly opened the widget via a real click.
        // With openOnFocus:false the dropdown must stay closed on init/focus and only
        // open after a genuine user gesture — see #showDropdown() + the mousedown handler.
        #userInteracted = false;
        #svgIconMap = {};
        #badgeMinWidth = 0;
        #fixedReposHandler = null;


        // ── CONSTRUCTOR / INITIALIZE ────────────────────────────────────────────

        constructor(sourceElement, options = {}) {
            this.sourceElement = sourceElement;
            this.options = deepMerge({}, WbeSelect.defaults, options);

            // Per-element overrides via data-wbeSelect* attributes on the <select>
            // (e.g. data-wbe-select-tree-view="true" → options.treeView).
            for (const key in sourceElement.dataset) {
                if (Object.prototype.hasOwnProperty.call(sourceElement.dataset, key) &&
                    key.startsWith('wbeSelect') && key.length > 9) {
                    const optKey = key.charAt(9).toLowerCase() + key.slice(10);
                    this.options[optKey] = sourceElement.dataset[key];
                }
            }

            this.options.searchFields = typeof this.options.searchFields === 'string'
                ? this.options.searchFields.split(' ')
                : this.options.searchFields;

            // Build the svgIcons lookup map (ref -> {svg, color}) once, from the
            // array passed via the constructor/init call. Options reference an
            // entry via data-svgref; see #resolveSvgIcon() below.
            this.#svgIconMap = {};
            if (Array.isArray(this.options.svgIcons)) {
                for (const entry of this.options.svgIcons) {
                    if (entry && entry.ref) {
                        this.#svgIconMap[entry.ref] = { svg: entry.svg || '', color: entry.color || '' };
                    }
                }
            }

            this.#isSingle = !sourceElement.hasAttribute('multiple');
            this.#isMultiple = !this.#isSingle;

            sourceElement.classList.add('wbeSelect');
            if (sourceElement.getAttribute('placeholder')) {
                this.options.placeholder = sourceElement.getAttribute('placeholder');
            }

            this.#buildDom();
            this.#bindEvents();

            this.#renderOptions();
            this.#renderSelectedItems();
            this.#resizeSearchInput();

            if (this.options.treeView) {
                this.#enhanceTreePrefixes();
            }

            WbeSelect.#register(sourceElement, this);
        }

        #buildDom() {
            const se = this.sourceElement;
            const cs = getComputedStyle(se);

            // ── Container ────────────────────────────────────────────────────────
            const container = document.createElement('div');
            if (se.id) container.id = 'ws-el_' + se.id;
            container.className =
                (this.options.customSelector ? this.options.customSelector + ' ' : '') +
                'ws-el ' +
                (this.#isMultiple ? 'ws--multiple' : 'ws--single') +
                ' ws--hidden';
            if (!this.options.useSearch) {
                container.classList.add('ws--no-search');
            }
            container.style.width = cs.width;
            container.style.minHeight = cs.height;
            container.style.padding = cs.padding;
            container.style.flexGrow = cs.flexGrow;
            container.style.position = 'relative';
            if (this.options.height === 'element') {
                container.style.height = se.offsetHeight + 'px';
            }
            this.containerElement = container;

            // ── Text-length span (hidden helper for measuring typed text width) ────
            const textlength = document.createElement('span');
            textlength.className = 'ws-txl';
            textlength.style.position = 'absolute';
            textlength.style.visibility = 'hidden';
            container.append(textlength);
            this.textlengthElement = textlength;

            // ── Selected items wrapper ───────────────────────────────────────────
            const selectedItems = document.createElement('div');
            selectedItems.className = 'ws-tags';
            container.append(selectedItems);
            this.selectedItemsElement = selectedItems;

            // ── Search input ─────────────────────────────────────────────────────
            const searchLabel = se.dataset.searchLabel || this.options.labels.search;
            const input = document.createElement('input');
            input.className = 'ws-inp';
            if (se.hasAttribute('tabindex')) {
                input.setAttribute('tabindex', se.getAttribute('tabindex'));
            }
            input.setAttribute('autocomplete', 'false');

            if (!this.options.useSearch) {
                input.setAttribute('readonly', 'true');
                Object.assign(input.style, {
                    width: '0px', height: '0px', overflow: 'hidden',
                    border: '0', padding: '0', position: 'absolute'
                });
            } else {
                if (this.#isSingle) {
                    input.setAttribute('placeholder', searchLabel);
                } else {
                    if (this.options.placeholder !== '') {
                        input.setAttribute('placeholder', this.options.placeholder);
                    }
                    input.style.width = '20px';
                }
            }
            container.append(input);
            this.inputElement = input;

            // ── Options list ─────────────────────────────────────────────────────
            const optionsList = document.createElement('ul');
            optionsList.className = 'ws-opts';
            container.append(optionsList);
            this.optionsElement = optionsList;

            se.after(container);
            se.style.display = 'none';
        }

        #bindEvents() {
            const se = this.sourceElement;
            const container = this.containerElement;
            const input = this.inputElement;

            se.addEventListener('change', () => this.#renderSelectedItems());

            container.addEventListener('mousedown', (e) => {
                e.preventDefault();
                this.#userInteracted = true; // a real user gesture — now the dropdown may open
                if (container.classList.contains('ws--open')) {
                    // Only close the dropdown when clicking OUTSIDE the options list.
                    if (e.target.closest('.ws-opts')) return;
                    e.stopPropagation();
                    this.#hideDropdown();
                } else {
                    input.focus();
                    input.dispatchEvent(new Event('focus'));
                    // With openOnFocus:false the focus handler no longer opens the
                    // dropdown, so an explicit user click must open it here instead.
                    if (!this.options.openOnFocus) this.#showDropdown();
                    if (input.setSelectionRange) {
                        input.setSelectionRange(input.value.length, input.value.length);
                    }
                }
            });

            container.addEventListener('click', () => {
                if (container.classList.contains('ws--hidden')) {
                    this.#hideDropdown();
                } else {
                    input.focus();
                    input.dispatchEvent(new Event('focus'));
                }
            });

            container.addEventListener('dblclick', () => {
                input.select();
                input.dispatchEvent(new Event('focus'));
            });

            // ── Keyboard: up/down navigation, enter to select, escape, backspace ──
            input.addEventListener('keydown', (e) => {
                const keyCode = e.keyCode || e.which;

                // Up arrow
                if (keyCode === 38) {
                    e.preventDefault();
                    this.#showDropdown();
                    const visible = this.#visibleOptions();
                    const prevActive = this.optionsElement.querySelector('.ws--active');
                    if (prevActive) prevActive.classList.remove('ws--active');
                    if (prevActive) {
                        const idx = visible.indexOf(prevActive);
                        const next = visible[idx - 1 >= 0 ? idx - 1 : 0];
                        if (next) next.classList.add('ws--active');
                    } else if (visible.length) {
                        visible[visible.length - 1].classList.add('ws--active');
                    }
                    this.#scrollToActive();
                    return;
                }

                // Down arrow
                if (keyCode === 40) {
                    e.preventDefault();
                    this.#showDropdown();
                    const visible = this.#visibleOptions();
                    const prevActive = this.optionsElement.querySelector('.ws--active');
                    if (prevActive) prevActive.classList.remove('ws--active');
                    if (prevActive) {
                        const idx = visible.indexOf(prevActive);
                        const next = visible[idx + 1 < visible.length ? idx + 1 : visible.length - 1];
                        if (next) next.classList.add('ws--active');
                    } else if (visible.length) {
                        visible[0].classList.add('ws--active');
                    }
                    this.#scrollToActive();
                    return;
                }

                // Enter: with allowNew, typed text takes precedence (create/select
                // the token[s]); otherwise commit the highlighted option, then blur
                // to prevent the focus handler from immediately reopening.
                if (keyCode === 13) {
                    e.preventDefault();
                    const typed = input.value;
                    if (this.options.allowNew && typed && typed.trim() !== '') {
                        this.#createOrSelectNew(typed);
                    } else if (this.optionsElement.querySelector('.ws--active')) {
                        this.#selectOption();
                        input.blur();
                    }
                    this.#resizeSearchInput();
                    return;
                }

                // Comma also commits a typed value in allowNew mode (tag-input feel).
                if (this.options.allowNew && keyCode === 188) {
                    const typedC = input.value;
                    if (typedC && typedC.trim() !== '') {
                        e.preventDefault();
                        this.#createOrSelectNew(typedC);
                        return;
                    }
                }

                // Escape: preventDefault here, hideDropdown in keyup
                if (keyCode === 27) {
                    e.preventDefault();
                    return;
                }

                if (!this.options.useSearch) {
                    e.preventDefault();
                    return;
                }

                // Backspace: remove last tag in multiple mode
                if (keyCode === 8) {
                    const selected = this.sourceElement.querySelectorAll('option:checked');
                    if (input.value === '' && this.#isMultiple && selected.length) {
                        const lastSelected = selected[selected.length - 1];
                        lastSelected.removeAttribute('selected');
                        lastSelected.selected = false;
                        this.sourceElement.dispatchEvent(new Event('change', { bubbles: true }));
                        this.#renderSelectedItems();
                    }
                }
                this.#resizeSearchInput();
            });

            // ── Keyup: filter results, close on Escape ────────────────────────────
            input.addEventListener('keyup', (e) => {
                e.preventDefault();
                e.stopPropagation();
                const keyCode = e.which || e.keyCode;
                switch (keyCode) {
                    case 27: // Escape
                        this.#hideDropdown();
                        break;
                    case 37: case 38: case 39: case 40: // arrows
                    case 9:  // tab
                    case 16: // shift
                    case 13: // enter (already handled in keydown)
                        break;
                    default:
                        this.#filterResults();
                        break;
                }
                this.#resizeSearchInput();
            });

            input.addEventListener('focus', (e) => {
                container.classList.add('ws--focused');
                if (this.options.openOnFocus &&
                    (this.#isSingle || this.options.showAllOptionsOnFocus || !this.options.useSearch)) {
                    e.stopPropagation();
                    this.#showDropdown();
                }
            });

            input.addEventListener('blur', (e) => {
                container.classList.remove('ws--focused');
                e.stopPropagation();
                this.#hideDropdown();
            });

            // Remove button on selected tag
            delegate(container, 'mousedown', '.ws-tag-rm', (e) => {
                e.preventDefault();
                e.stopPropagation();
                const tag = e.target.closest('.ws-tag');
                this.removeSelection(tag);
            });

            // Option hover
            delegate(container, 'mouseover', '.ws-opt', function () {
                const prevActive = container.querySelector('.ws--active');
                if (prevActive) prevActive.classList.remove('ws--active');
                this.classList.add('ws--active');
            });

            // Option mousedown: prevent blur
            delegate(container, 'mousedown', '.ws-opt', (e) => {
                e.preventDefault();
                e.stopPropagation();
            });

            // Option mouseup: commit selection
            delegate(container, 'mouseup', '.ws-opt', () => {
                this.#selectOption();
            });

            // Option click: highlight
            delegate(container, 'click', '.ws-opt', function (e) {
                e.stopPropagation();
                const prevActive = container.querySelector('.ws--active');
                if (prevActive) prevActive.classList.remove('ws--active');
                this.classList.add('ws--active');
            });
        }


        // ── TREE VIEW ────────────────────────────────────────────────────────────

        #enhanceTreePrefixes() {
            if (!this.options.treeView) return;

            // NOTE: deliberately no .trim() — data-prefix values from WBCE's own
            // PageTree class pad with real U+00A0 (NBSP) characters, and JS's
            // trim() treats NBSP as whitespace too, stripping the LEADING
            // padding this is meant to preserve. That silently broke the
            // currentText.indexOf(prefix) === 0 check below (prefix no longer
            // matched position 0, since currentText keeps its real NBSP chars
            // untouched) — the SVG replacement never fired, options fell back
            // to showing the raw "&nbsp;├─&nbsp;Title" text. Found live via the
            // tinymce_wbce link plugin's internal-page picker.
            const normalize = (str) => (str || '').replace(/&nbsp;/gi, ' ').replace(/ +/g, ' ');
            const cleanOldPrefix = (text) => (text || '')
                .replace(/^[│├└  ·•◦●\-—─\|]+/, '')
                .trimStart();

            // Dropdown options: replace prefix text with SVG connectors
            this.optionsElement.querySelectorAll('.ws-opt-ttl').forEach((ttl) => {
                if (ttl.querySelector('.ws-tree-svg')) return;

                const opt = ttl.closest('.ws-opt');
                const prefixEntity = (opt && opt.getAttribute('data-prefix')) || '';
                const prefix = normalize(prefixEntity);
                if (!prefix) return;

                const currentText = ttl.textContent;
                const currentHtml = ttl.innerHTML || '';
                const matchLiteral = currentText.indexOf(prefix) === 0;
                const escapedPfx = prefixEntity.replace(/ /g, '&nbsp;');
                const matchEscaped = currentHtml.indexOf(escapedPfx) === 0;

                if (matchLiteral || matchEscaped) {
                    // matchLiteral slices currentTEXT (plain-char units), never
                    // currentHTML directly — a real NBSP character serializes to
                    // the 6-char "&nbsp;" entity in .innerHTML, so a prefix.length
                    // offset (counted in plain chars) under-slices the HTML string
                    // and leaves a leftover "&nbsp;..." tail next to the new SVG
                    // (found live: TinyMCE link plugin's page picker showed the
                    // icon AND the raw entity text together). matchEscaped's own
                    // units are already consistent (escapedPfx vs currentHtml,
                    // both html-serialized), so that branch is untouched.
                    const titlePart = matchEscaped
                        ? currentHtml.substring(escapedPfx.length)
                        : escapeHtml(currentText.substring(prefix.length));
                    ttl.innerHTML = WbeSelect.#buildDropdownTreeSvg(prefixEntity) + titlePart;
                }
            });

            // Selected item: depth dots (●●) based on nesting level
            if (this.#isSingle) {
                this.selectedItemsElement.querySelectorAll('.ws-tag-ttl').forEach((ttl) => {
                    const tag = ttl.closest('.ws-tag');
                    const level = parseInt((tag && tag.getAttribute('data-level')) || '0', 10);
                    const cleanText = cleanOldPrefix(ttl.textContent || '');

                    if (level <= 0) {
                        ttl.innerHTML = cleanText;
                        return;
                    }

                    const dots = '●'.repeat(level);
                    ttl.innerHTML = '<span class="tree-level-dots">' + escapeHtml(dots) + '</span>' + cleanText;
                });
            }
        }


        // ── EQUALIZE BADGE WIDTHS ────────────────────────────────────────────────

        #equalizeBadgeWidths() {
            const badges = this.optionsElement.querySelectorAll('.ws-opt-r');
            if (badges.length < 2) return;
            badges.forEach((b) => { b.style.minWidth = ''; });
            let maxW = 0;
            badges.forEach((b) => { maxW = Math.max(maxW, b.offsetWidth); });
            if (maxW > 0) {
                this.#badgeMinWidth = maxW;
                badges.forEach((b) => { b.style.minWidth = maxW + 'px'; });
                this.selectedItemsElement.querySelectorAll('.ws-tag-r').forEach((b) => {
                    b.style.minWidth = maxW + 'px';
                });
            }
        }


        // ── RESIZE INPUT ─────────────────────────────────────────────────────────

        #resizeSearchInput() {
            if (this.#isMultiple) {
                this.textlengthElement.textContent =
                    this.inputElement.value === '' && this.options.placeholder !== ''
                        ? this.options.placeholder
                        : this.inputElement.value;
                const textWidth = this.textlengthElement.offsetWidth;
                const containerWidth = this.containerElement.clientWidth;
                const width = textWidth > (containerWidth - 30) ? (containerWidth - 30) : (textWidth + 30);
                this.inputElement.style.width = width + 'px';
            }
        }


        // ── RENDER SELECTED ITEMS ────────────────────────────────────────────────

        #renderSelectedItems() {
            this.selectedItemsElement.innerHTML = '';

            this.sourceElement.querySelectorAll('option').forEach((optionEl) => {
                if (!optionEl.selected) return;

                const item = document.createElement('div');
                item._wsSourceItem = optionEl;
                item.className = 'ws-tag ws-val_' + optionEl.value.replace(/\W/g, '');

                const data = {
                    value: optionEl.value,
                    text: optionEl.text,
                    icon: optionEl.dataset.icon,
                    class: optionEl.dataset.class
                };
                for (const attr of optionEl.attributes) {
                    const name = attr.name;
                    if (name.startsWith('data-')) {
                        const key = name.replace('data-', '');
                        data[key] = attr.value;
                        // Copy level and prefix to selected tag for treeView
                        if (key === 'level' || key === 'prefix') {
                            item.setAttribute('data-' + key, attr.value);
                        }
                    } else {
                        data[name] = attr.value;
                    }
                }
                Object.assign(data, WbeSelect.getItemData(optionEl));
                this.#resolveSvgIcon(data);

                // Copy data-class (e.g. type-public) so CSS can target it on the tag
                if (data.class) {
                    addClasses(item, data.class);
                }

                item.insertAdjacentHTML('beforeend', this.options.render.selected_item(data, escapeHtml));

                if (this.#badgeMinWidth > 0) {
                    const badge = item.querySelector('.ws-tag-r');
                    if (badge) badge.style.minWidth = this.#badgeMinWidth + 'px';
                }

                // Single: hide remove button when there is no empty-value fallback
                if (this.#isSingle && (
                    data[this.options.valueField] === '' ||
                    typeof data[this.options.valueField] === 'undefined' ||
                    this.sourceElement.querySelectorAll('[value=""]').length === 0
                )) {
                    const rm = item.querySelector('.ws-tag-rm');
                    if (rm) rm.remove();
                }

                this.selectedItemsElement.append(item);
            });

            if (this.#isSingle) {
                const val = this.sourceElement.value;
                if (this.options.placeholder !== '' && (val === '' || val === null)) {
                    this.selectedItemsElement.innerHTML = '';
                    this.selectedItemsElement.insertAdjacentHTML('beforeend',
                        '<div class="ws-ph">' + this.options.placeholder + '</div>');
                } else {
                    const ph = this.selectedItemsElement.querySelector('.ws-ph');
                    if (ph) ph.remove();
                }
            }

            if (this.options.treeView) {
                setTimeout(() => this.#enhanceTreePrefixes(), 50);
            }
        }


        // ── CREATE OPTION ────────────────────────────────────────────────────────

        #createOption(optionEl, isGroupOption) {
            const li = document.createElement('li');
            li._wsSourceOption = optionEl;
            li.setAttribute('onclick', 'void(0)');
            li.className = 'ws-opt ws-val_' + optionEl.value.replace(/\W/g, '');

            if (isGroupOption) li.classList.add('ws-grp-opt');
            if (optionEl.selected) li.classList.add('ws--active');
            if (optionEl.disabled) li.classList.add('ws--opt-disabled');
            if (optionEl.getAttribute('class')) addClasses(li, optionEl.getAttribute('class'));

            const data = {
                value: optionEl.value,
                text: optionEl.text,
                icon: optionEl.dataset.icon,
                class: optionEl.dataset.class
            };
            for (const attr of optionEl.attributes) {
                const name = attr.name;
                if (name.startsWith('data-')) {
                    const key = name.replace('data-', '');
                    data[key] = attr.value;
                    if (key === 'prefix' || key === 'level') {
                        li.setAttribute('data-' + key, attr.value);
                    }
                } else {
                    data[name] = attr.value;
                    if (name === 'title') {
                        li.setAttribute('title', attr.value);
                    }
                }
            }
            Object.assign(data, WbeSelect.getItemData(optionEl));
            this.#resolveSvgIcon(data);

            if (this.#isMultiple && optionEl.selected) li.style.display = 'none';
            if (data.icon) li.insertAdjacentHTML('beforeend', '<i class="' + data.icon + '"></i>');
            if (data.class) addClasses(li, data.class);

            li.insertAdjacentHTML('beforeend', this.options.render.option(data, escapeHtml));
            return li;
        }


        // ── RENDER OPTIONS ───────────────────────────────────────────────────────

        #renderOptions() {
            const optionCount = this.sourceElement.querySelectorAll('option').length;
            // allowNew needs the search input at all times (typing IS how you add
            // a value) — never auto-disable search for a creatable widget.
            if (!this.options.allowNew && optionCount <= this.options.disable_search_threshold) {
                this.containerElement.classList.add('ws--no-search');
                this.options.useSearch = false;
            } else {
                this.containerElement.classList.remove('ws--no-search');
                if (this.options.allowNew) this.options.useSearch = true;
            }

            this.optionsElement.innerHTML = '';

            for (const child of Array.from(this.sourceElement.children)) {
                const tag = child.tagName.toLowerCase();
                if (tag === 'optgroup') {
                    const groupOptions = Array.from(child.children).filter((o) => o.tagName.toLowerCase() === 'option');
                    if (groupOptions.length === 0) continue;

                    const groupEl = document.createElement('li');
                    groupEl.classList.add('ws-grp');
                    if (child.getAttribute('class')) addClasses(groupEl, child.getAttribute('class'));
                    groupEl.innerHTML = child.getAttribute('label') || '';
                    this.optionsElement.append(groupEl);

                    groupOptions.forEach((optEl) => {
                        this.optionsElement.append(this.#createOption(optEl, true));
                    });
                } else if (tag === 'option') {
                    this.optionsElement.append(this.#createOption(child, false));
                }
            }

            this.#filterResults();

            if (this.options.treeView) {
                setTimeout(() => this.#enhanceTreePrefixes(), 50);
            }
        }


        // ── SCROLL TO ACTIVE OPTION ──────────────────────────────────────────────

        #scrollToActive() {
            const active = this.optionsElement.querySelector('.ws-opt.ws--active');
            if (active) {
                this.optionsElement.scrollTop =
                    this.optionsElement.scrollTop +
                    active.offsetTop -
                    this.optionsElement.clientHeight / 2 +
                    active.offsetHeight / 2;
            }
        }


        // ── FILTER RESULTS ───────────────────────────────────────────────────────

        #visibleOptions() {
            return Array.from(this.optionsElement.querySelectorAll('.ws-opt'))
                .filter((el) => el.style.display !== 'none');
        }

        #getOptionSearchValue(optionEl, field) {
            if (field === 'value') return optionEl.value;
            if (field === 'text') return optionEl.text;
            return optionEl.dataset[field];
        }

        #filterResults() {
            this.#hasVisibleOptions = false;
            const searchFor = this.inputElement.value.toLowerCase();

            this.optionsElement.querySelectorAll('.ws-opt').forEach((li) => {
                const optionEl = li._wsSourceOption;

                let matchFound = false;
                for (const field of this.options.searchFields) {
                    const val = this.#getOptionSearchValue(optionEl, field);
                    if (typeof val !== 'undefined' && val !== null &&
                        String(val).toLowerCase().indexOf(searchFor) !== -1) {
                        matchFound = true;
                        break;
                    }
                }

                const shouldShow = (!optionEl.selected || this.#isSingle) &&
                    (!this.options.useSearch || matchFound || optionEl.value === '');

                if (shouldShow) {
                    li.style.display = '';
                    this.#hasVisibleOptions = true;
                } else {
                    li.style.display = 'none';
                }
            });

            // Hide optgroup headers with no visible children
            this.optionsElement.querySelectorAll('.ws-grp').forEach((groupLi) => {
                let groupHasVisible = false;
                let sib = groupLi.nextElementSibling;
                while (sib && !sib.classList.contains('ws-grp')) {
                    if (sib.style.display !== 'none') { groupHasVisible = true; break; }
                    sib = sib.nextElementSibling;
                }
                groupLi.style.display = groupHasVisible ? '' : 'none';
            });

            this.#showDropdown();

            if (this.#isMultiple) {
                const active = this.optionsElement.querySelector('.ws--active');
                if (active) active.classList.remove('ws--active');
                if (this.options.selectFirstOptionOnSearch && searchFor !== '') {
                    const firstVisible = this.#visibleOptions()[0];
                    if (firstVisible) firstVisible.classList.add('ws--active');
                }
            }

            if (this.options.treeView) {
                setTimeout(() => this.#enhanceTreePrefixes(), 50);
            }
        }


        // ── FIXED-POSITION DROPDOWN (escapes a scrolling/clipping host) ──────────

        #positionFixedDropdown() {
            const rect = this.containerElement.getBoundingClientRect();
            // Plain style assignment loses to any `!important` rule in a stylesheet
            // — and there IS one further down wbeSelect.css:
            // `.ws--single.ws--no-search .ws-opts { top: 32px !important }` (meant
            // for position:absolute mode, where "32px from the widget" makes
            // sense). With position:fixed, "top" is viewport-relative, so that rule
            // would pin the dropdown 32px from the BROWSER'S top edge instead of
            // below the widget — the "flies to the top of the screen" bug. Setting
            // these with 'important' via the native API guarantees this computed
            // position always wins, regardless of any current or future
            // !important rule elsewhere for `.ws-opts`.
            let listTop = rect.bottom;

            // When search is active, the search input normally sits directly below
            // the closed widget (CSS `bottom:-33px` relative to it), with the
            // options list below THAT. Fixed mode must reposition the search input
            // too — otherwise it stays trapped by the same scrolling-host issue
            // .ws-opts had, and ends up overlapped/hidden by the now-correctly-
            // floating list (both share z-index:101, list paints on top).
            // Only SINGLE mode floats its search input below the widget. In
            // MULTIPLE mode the input lives inline inside the tags area —
            // repositioning it to `top:rect.bottom` would shove it under the
            // widget and behind the options list, so it's skipped for multiple;
            // the list still floats right below (listTop stays rect.bottom).
            if (this.#isSingle && this.options.useSearch && !this.containerElement.classList.contains('ws--no-search')) {
                const inputEl = this.inputElement;
                inputEl.style.setProperty('position', 'fixed', 'important');
                inputEl.style.setProperty('top', rect.bottom + 'px', 'important');
                inputEl.style.setProperty('left', rect.left + 'px', 'important');
                inputEl.style.setProperty('width', rect.width + 'px', 'important');
                // The stylesheet's own `.ws--single .ws-inp { bottom: -33px }` is
                // never overridden by anything above — with BOTH `top` (ours) and
                // `bottom` (the leftover) set on a position:fixed element and no
                // explicit height, the browser stretches it to fill the whole gap
                // between them. `bottom: auto` lets height go back to its natural,
                // content-based size.
                inputEl.style.setProperty('bottom', 'auto', 'important');
                listTop = rect.bottom + (inputEl.offsetHeight || 36);
            }

            const el = this.optionsElement;
            el.style.setProperty('position', 'fixed', 'important');
            el.style.setProperty('top', listTop + 'px', 'important');
            el.style.setProperty('left', rect.left + 'px', 'important');
            el.style.setProperty('right', 'auto', 'important');
            el.style.setProperty('width', rect.width + 'px', 'important');
        }


        // ── SHOW DROPDOWN ────────────────────────────────────────────────────────

        #showDropdown() {
            // openOnFocus:false → never auto-open (init, programmatic focus, option
            // re-render, …). Only a real user click (which sets #userInteracted in
            // the mousedown handler) may open it. This is the single choke point that
            // covers ALL open paths, not just the focus handler.
            if (!this.options.openOnFocus && !this.#userInteracted) {
                this.#hideDropdown();
                return;
            }

            const isEmpty = this.optionsElement.children.length === 0;
            if (
                document.activeElement === this.inputElement &&
                (this.#hasVisibleOptions || this.#isSingle) &&
                !(isEmpty && !this.options.useSearch)
            ) {
                this.containerElement.classList.remove('ws--hidden');
                this.containerElement.classList.add('ws--open');
                setTimeout(() => {
                    if (this.options.fixedDropdown) {
                        this.#positionFixedDropdown();
                        if (!this.#fixedReposHandler) {
                            this.#fixedReposHandler = () => this.#positionFixedDropdown();
                            window.addEventListener('resize', this.#fixedReposHandler);
                            window.addEventListener('scroll', this.#fixedReposHandler);
                        }
                    } else {
                        this.optionsElement.style.top =
                            (this.containerElement.offsetHeight +
                             (this.#isMultiple ? 0 : this.inputElement.offsetHeight) - 1) + 'px';
                    }
                    this.#equalizeBadgeWidths();
                }, 1);
            } else {
                this.#hideDropdown();
            }
        }


        // ── HIDE DROPDOWN ────────────────────────────────────────────────────────

        #hideDropdown() {
            this.containerElement.classList.remove('ws--open');
            this.containerElement.classList.add('ws--hidden');
            if (this.#fixedReposHandler) {
                window.removeEventListener('resize', this.#fixedReposHandler);
                window.removeEventListener('scroll', this.#fixedReposHandler);
                this.#fixedReposHandler = null;
            }
        }


        // ── SELECT ACTIVE OPTION ─────────────────────────────────────────────────

        #selectOption() {
            if (this.#isSingle) {
                const selected = this.sourceElement.querySelectorAll('option:checked');
                const last = selected[selected.length - 1];
                if (last) {
                    last.removeAttribute('selected');
                    last.selected = false;
                }
            }
            const active = this.optionsElement.querySelector('.ws--active');
            // Bail out if the highlighted option is disabled
            if (!active || active.classList.contains('ws--opt-disabled')) return;

            const optionEl = active._wsSourceOption;
            optionEl.setAttribute('selected', '');
            optionEl.selected = true;
            this.sourceElement.dispatchEvent(new Event('change', { bubbles: true }));
            this.inputElement.value = '';
            this.#filterResults();
            this.#renderSelectedItems();
            this.#resizeSearchInput();
            this.#hideDropdown();

            if (this.options.treeView) {
                setTimeout(() => this.#enhanceTreePrefixes(), 50);
            }
        }


        // ── CREATE NEW OPTION(S) FROM TYPED TEXT (allowNew) ───────────────────────
        // Multiple mode splits on whitespace (each token becomes its own chip);
        // single mode takes the whole trimmed value. A token equal to an existing
        // option just selects it; otherwise a new <option> is created + selected.
        #createOrSelectNew(raw) {
            const tokens = this.#isSingle ? [raw] : String(raw).split(/\s+/);
            const created = [];

            tokens.forEach((rawTok) => {
                let tok = (rawTok || '').trim();
                if (typeof this.options.sanitizeNew === 'function') {
                    tok = this.options.sanitizeNew(tok) || '';
                }
                if (tok === '') return;

                if (this.#isSingle) {
                    this.sourceElement.querySelectorAll('option:checked').forEach((opt) => {
                        opt.removeAttribute('selected');
                        opt.selected = false;
                    });
                }

                const existing = Array.from(this.sourceElement.querySelectorAll('option'))
                    .find((opt) => opt.value === tok);

                if (existing) {
                    existing.setAttribute('selected', '');
                    existing.selected = true;
                } else {
                    // new Option(text, value, defaultSelected, selected)
                    const opt = new Option(tok, tok, true, true);
                    this.sourceElement.append(opt);
                    created.push(tok);
                }
            });

            this.sourceElement.dispatchEvent(new Event('change', { bubbles: true }));
            this.inputElement.value = '';
            this.#renderOptions();
            this.#renderSelectedItems();
            this.#resizeSearchInput();
            this.#hideDropdown();

            if (created.length) {
                this.sourceElement.dispatchEvent(new CustomEvent('wbeSelect:created', {
                    detail: created,
                    bubbles: true
                }));
            }
        }


        // ── RESOLVE SVG ICON (registry lookup) ─────────────────────────────────────
        // If `data.svg` isn't already set directly (data-svg on the option) but
        // `data.svgref` is, look it up in the svgIcons map built at init and fill
        // in data.svg / data.color (an explicit data-color on the option wins).
        #resolveSvgIcon(data) {
            if ((!data.svg || data.svg === '') && data.svgref && this.#svgIconMap[data.svgref]) {
                const entry = this.#svgIconMap[data.svgref];
                data.svg = entry.svg;
                if (!data.color || data.color === '') {
                    data.color = entry.color;
                }
            }
            return data;
        }


        // ── PUBLIC API ───────────────────────────────────────────────────────────

        /** Re-render selected item tags after programmatic <option> changes */
        refresh() {
            this.#renderSelectedItems();
        }

        /** Re-render both the dropdown option list and the selected item tags
         *  after programmatic <option> changes on the native <select>. */
        rebuildOptions() {
            this.#renderOptions();
            this.#renderSelectedItems();
        }

        /** Close the dropdown programmatically */
        hideDropdown() {
            this.#hideDropdown();
        }

        /**
         * Remove a specific selected item.
         * Pass the .ws-tag element, or omit to remove the first (single select).
         */
        removeSelection(tagEl) {
            let sourceItemElement;
            if (tagEl) {
                sourceItemElement = tagEl._wsSourceItem;
            } else {
                const firstTag = this.selectedItemsElement.querySelector('.ws-tag');
                sourceItemElement = firstTag ? firstTag._wsSourceItem : undefined;
            }
            if (!sourceItemElement) return;

            sourceItemElement.removeAttribute('selected');
            sourceItemElement.selected = false;

            if (this.#isSingle) {
                const noValueOption = this.sourceElement.querySelector('option[value=""]');
                if (noValueOption) {
                    noValueOption.setAttribute('selected', '');
                    noValueOption.selected = true;
                }
            }

            this.sourceElement.dispatchEvent(new Event('change', { bubbles: true }));
            this.#filterResults();
            this.#renderOptions();
            this.#renderSelectedItems();
            this.#hideDropdown();

            if (this.options.treeView) {
                setTimeout(() => this.#enhanceTreePrefixes(), 50);
            }
        }

        /** Remove the widget and restore the original <select> */
        destroy() {
            this.#hideDropdown();
            this.containerElement.remove();
            WbeSelect.#unregister(this.sourceElement);
            this.sourceElement.style.display = '';
        }
    }

    global.WbeSelect = WbeSelect;


    // ── AUTO-INIT VIA MARKUP ─────────────────────────────────────────────────────

    function bootAutoInit() {
        document.querySelectorAll('select.wbeSelect').forEach((el) => {
            if (WbeSelect.get(el)) return; // already enhanced (e.g. by explicit code)
            const options = {};
            for (const key in el.dataset) {
                if (Object.prototype.hasOwnProperty.call(el.dataset, key) &&
                    key.startsWith('wbeSelect') && key.length > 9) {
                    const optKey = key.charAt(9).toLowerCase() + key.slice(10);
                    options[optKey] = el.dataset[key];
                }
            }
            WbeSelect.init(el, options);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bootAutoInit);
    } else {
        bootAutoInit();
    }


    // ── OPTIONAL jQuery COMPATIBILITY BRIDGE ─────────────────────────────────────
    // Only attached when jQuery is present. Restores the exact old calling
    // convention ($('#sel').wbeSelect({...}) / $('#sel').wbeSelect('method')),
    // so existing WBCE core code needs no changes. Not required otherwise —
    // the WbeSelect class above has no dependency on it.
    if (global.jQuery) {
        (function ($) {
            $.fn.wbeSelect = function (_options) {
                const options = _options !== undefined ? _options : {};
                return this.each(function () {
                    const $this = $(this);
                    if (typeof options === 'object') {
                        if ($this.data('wbeSelect') === undefined) {
                            const instance = WbeSelect.init(this, options);
                            $this.data('wbeSelect', instance);
                        }
                    } else if ($this.data('wbeSelect') && typeof $this.data('wbeSelect')[options] === 'function') {
                        $this.data('wbeSelect')[options].apply($this.data('wbeSelect'), Array.prototype.slice.call(arguments, 1));
                    } else {
                        $.error('Method ' + options + ' does not exist in $.fn.wbeSelect');
                    }
                });
            };
        })(global.jQuery);
    }

})(typeof window !== 'undefined' ? window : this);
