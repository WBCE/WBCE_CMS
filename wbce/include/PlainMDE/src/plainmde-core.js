/**
 * include/PlainMDE/src/plainmde-core.js
 *
 * PlainMDE editor engine. Purpose-built replacement for easymde.js: only the
 * toolbar actions and modes PlainMDE actually ships are implemented here
 * (~15 actions instead of EasyMDE's ~30, no image-upload/XHR machinery, no
 * spell-checker integration, no i18n string tables). Built directly on the
 * CodeMirror instance already loaded by CodeMirror_Config — this file does
 * not load or bundle CodeMirror itself.
 *
 * Public surface:
 *   var editor = new PlainMDE({ element, toolbar, previewRender, ... });
 *   editor.codemirror        // the underlying CodeMirror instance
 *   editor.value([text])     // get/set the raw markdown
 *   editor.togglePreview() / toggleSideBySide() / toggleFullscreen()
 *   editor.destroy()
 */
var PlainMDE = (function () {
    'use strict';

    // ── Selection helpers ───────────────────────────────────────────────
    // All toolbar actions operate on the CodeMirror selection API directly
    // (no textarea shimming) — every editor instance here is always a real
    // CodeMirror, unlike EasyMDE which also had to support a plain-textarea
    // fallback path we never use.

    function activeLine(cm) {
        return cm.getLine(cm.getCursor().line);
    }

    var IMAGE_MARKDOWN_RE = /!\[([^\]]*)\]\(([^)]+)\)/g;

    // Returns { from, to, alt, url } if the cursor sits inside an existing
    // ![alt](url) span on its current line, else null. Used by the image
    // toolbar action to replace-in-place instead of always inserting a new,
    // separate image link.
    function findImageAtCursor(cm) {
        var cursor = cm.getCursor();
        var line = cm.getLine(cursor.line);
        IMAGE_MARKDOWN_RE.lastIndex = 0;
        var m;
        while ((m = IMAGE_MARKDOWN_RE.exec(line))) {
            var start = m.index, end = start + m[0].length;
            if (cursor.ch >= start && cursor.ch <= end) {
                return {
                    from: { line: cursor.line, ch: start },
                    to: { line: cursor.line, ch: end },
                    alt: m[1],
                    url: m[2]
                };
            }
        }
        return null;
    }

    // Wrap the current selection (or the word under the cursor, if nothing
    // is selected) in a prefix/suffix pair, e.g. toggleWrap(cm, '**', '**').
    // If the selection is already wrapped, unwraps instead (toggle).
    function toggleWrap(cm, prefix, suffix) {
        suffix = suffix === undefined ? prefix : suffix;
        var range = cm.listSelections()[0];
        var from = range.from(), to = range.to();
        var selected = cm.getRange(from, to);

        if (!selected) {
            // Nothing selected — try the word under the cursor so the
            // wrap has something visible to apply to.
            var word = cm.findWordAt(from);
            from = word.anchor;
            to = word.head;
            selected = cm.getRange(from, to);
        }

        var before = cm.getRange(
            { line: from.line, ch: Math.max(0, from.ch - prefix.length) }, from
        );
        var after = cm.getRange(
            to, { line: to.line, ch: to.ch + suffix.length }
        );

        if (before === prefix && after === suffix) {
            // Already wrapped — remove
            cm.replaceRange(selected, { line: from.line, ch: from.ch - prefix.length },
                                       { line: to.line, ch: to.ch + suffix.length });
            cm.setSelection(
                { line: from.line, ch: from.ch - prefix.length },
                { line: to.line, ch: to.ch - prefix.length }
            );
        } else {
            cm.replaceRange(prefix + selected + suffix, from, to);
            var newFrom = { line: from.line, ch: from.ch + prefix.length };
            var newTo = { line: to.line, ch: (from.line === to.line ? to.ch + prefix.length : to.ch) };
            cm.setSelection(newFrom, newTo);
        }
        cm.focus();
    }

    // Toggle a line-leading marker (used for headings/quote/lists) on every
    // selected line. `nextMarker(currentMarker)` decides the new marker for
    // a line given its current one (or '' if none) — lets heading levels
    // cycle and quote/list toggling stay a single shared implementation.
    function toggleLinePrefix(cm, re, nextMarker) {
        var range = cm.listSelections()[0];
        var startLine = range.from().line;
        var endLine = range.to().line;
        cm.operation(function () {
            for (var i = startLine; i <= endLine; i++) {
                var line = cm.getLine(i);
                var m = re.exec(line);
                var current = m ? m[0] : '';
                var next = nextMarker(current);
                cm.replaceRange(
                    next + line.slice(current.length),
                    { line: i, ch: 0 }, { line: i, ch: line.length }
                );
            }
        });
        cm.focus();
    }

    // ── Toolbar actions ─────────────────────────────────────────────────

    var actions = {

        bold: function (cm) { toggleWrap(cm, '**'); },
        italic: function (cm) { toggleWrap(cm, '_'); },
        strikethrough: function (cm) { toggleWrap(cm, '~~'); },
        code: function (cm) {
            var range = cm.listSelections()[0];
            var multiline = range.from().line !== range.to().line;
            if (multiline) {
                var from = { line: range.from().line, ch: 0 };
                var to = { line: range.to().line, ch: cm.getLine(range.to().line).length };
                var body = cm.getRange(from, to);
                cm.replaceRange('```\n' + body + '\n```', from, to);
            } else {
                toggleWrap(cm, '`');
            }
        },

        heading: function (cm) {
            // Cycles H1 → H2 → H3 → (none) on repeated clicks. Capped at
            // H3 deliberately — H4-H6 are rare in CMS body content and a
            // single toolbar button cycling through all six starts costing
            // more clicks than typing '#### ' by hand.
            toggleLinePrefix(cm, /^#{1,6}\s/, function (current) {
                var level = current ? current.trim().length : 0;
                return level >= 3 ? '' : new Array(level + 2).join('#') + ' ';
            });
        },

        quote: function (cm) {
            toggleLinePrefix(cm, /^>\s?/, function (current) {
                return current ? '' : '> ';
            });
        },

        'unordered-list': function (cm) {
            toggleLinePrefix(cm, /^(\s*)([-*+]\s)?/, function (current) {
                return current.replace(/[-*+]\s?$/, '') + (/[-*+]\s$/.test(current) ? '' : '- ');
            });
        },

        'ordered-list': function (cm) {
            var range = cm.listSelections()[0];
            var startLine = range.from().line, endLine = range.to().line;
            var n = 1;
            cm.operation(function () {
                for (var i = startLine; i <= endLine; i++) {
                    var line = cm.getLine(i);
                    var stripped = line.replace(/^(\s*)\d+[.)]\s+/, '$1');
                    var indent = (stripped.match(/^\s*/) || [''])[0];
                    cm.replaceRange(
                        indent + n + '. ' + stripped.slice(indent.length),
                        { line: i, ch: 0 }, { line: i, ch: line.length }
                    );
                    n++;
                }
            });
            cm.focus();
        },

        'check-list': function (cm) {
            toggleLinePrefix(cm, /^(\s*)([-*+]\s\[[ xX]\]\s|[-*+]\s)?/, function (current) {
                if (/\[[ xX]\]\s$/.test(current)) return current.replace(/[-*+]\s\[[ xX]\]\s$/, '');
                return current.replace(/[-*+]\s$/, '') + '- [ ] ';
            });
        },

        link: function (cm) {
            var sel = cm.getSelection();
            var url = window.prompt(PlainMDE.i18n.linkPrompt || 'URL:', 'https://');
            if (!url) { cm.focus(); return; }
            var text = sel || (PlainMDE.i18n.linkText || 'Link-Text');
            cm.replaceSelection('[' + text + '](' + url + ')');
            cm.focus();
        },

        image: function (cm, editorInstance) {
            var sel = cm.getSelection();
            var mediaUrl = (editorInstance && editorInstance.options.mediaUrl) || PlainMDE.mediaUrl;
            // If the cursor is sitting inside an already-placed image link
            // (the user clicked into it, then hit this button), replace
            // that link in place and pre-search elFinder for its filename
            // instead of inserting a second, unrelated image.
            var existing = findImageAtCursor(cm);

            function insert(url, alt) {
                var text = '![' + (alt || (existing && existing.alt) || sel || PlainMDE.i18n.imageAlt || 'Beschreibung') + '](' + url + ')';
                if (existing) {
                    cm.replaceRange(text, existing.from, existing.to);
                } else {
                    cm.replaceSelection(text);
                }
                cm.focus();
            }

            if (mediaUrl && window.PlainMDEMedia) {
                PlainMDEMedia.pick(mediaUrl, function (url) { insert(url); }, existing && existing.url);
                return;
            }

            var url = window.prompt(PlainMDE.i18n.imagePrompt || 'Bild-URL:', existing ? existing.url : 'https://');
            if (!url) { cm.focus(); return; }
            insert(url);
        },

        table: function (cm) {
            var template = '\n| Spalte 1 | Spalte 2 | Spalte 3 |\n' +
                            '| -------- | -------- | -------- |\n' +
                            '| Text     | Text     | Text     |\n';
            var cur = cm.getCursor();
            cm.replaceRange(template, cur);
            cm.focus();
        },

        'horizontal-rule': function (cm) {
            var cur = cm.getCursor();
            var prefix = (cur.ch === 0 && activeLine(cm) === '') ? '' : '\n';
            cm.replaceRange(prefix + '\n---\n', cur);
            cm.focus();
        },

        undo: function (cm) { cm.undo(); cm.focus(); },
        redo: function (cm) { cm.redo(); cm.focus(); },

        preview: function (cm, editorInstance) { editorInstance.togglePreview(); },
        'side-by-side': function (cm, editorInstance) { editorInstance.toggleSideBySide(); },
        fullscreen: function (cm, editorInstance) { editorInstance.toggleFullscreen(); },

        guide: function () {
            window.open('https://www.markdownguide.org/basic-syntax/', '_blank', 'noopener');
        }
    };

    // ── Constructor ──────────────────────────────────────────────────────

    function PlainMDE(options) {
        if (!options || !options.element) {
            throw new Error('PlainMDE: options.element (a <textarea>) is required.');
        }
        this.options = options;
        this.textarea = options.element;
        this.previewRender = options.previewRender || function (text) {
            return PlainMDE.markdown(text);
        };

        this._buildDom();
        this._buildCodeMirror();
        this._buildToolbar();

        this.previewActive = false;
        this.sideBySideActive = false;
        this.fullscreenActive = false;

        // Inline tree previews for ```file-tree / ```page-tree blocks, opt-out
        // via { treeView: false }. No-ops when plainmde-treeview.js or the tree
        // renderer it needs (window.WbceFileTree) is not loaded on this page.
        if (options.treeView !== false && window.PlainMDETreeView) {
            this.treeView = PlainMDETreeView.attach(this.codemirror);
        }

        if (options.initialCleanup !== false) {
            this.codemirror.refresh();
        }
    }

    PlainMDE.actions = actions;
    PlainMDE.i18n = {}; // caller may overwrite prompt strings, e.g. for other locales
    // Global default elFinder picker URL (see plugin.php); the image action
    // falls back to window.prompt() when this is empty. Per-instance
    // options.mediaUrl overrides this default.
    PlainMDE.mediaUrl = '';

    PlainMDE.prototype._buildDom = function () {
        var wrapper = document.createElement('div');
        wrapper.className = 'PlainMDE';
        this.textarea.parentNode.insertBefore(wrapper, this.textarea);

        var toolbarEl = document.createElement('div');
        toolbarEl.className = 'pmde-toolbar';
        wrapper.appendChild(toolbarEl);

        var editorRow = document.createElement('div');
        editorRow.className = 'pmde-editor-row';
        wrapper.appendChild(editorRow);

        var cmHost = document.createElement('div');
        cmHost.className = 'pmde-cm-host';
        editorRow.appendChild(cmHost);

        var previewEl = document.createElement('div');
        previewEl.className = 'pmde-preview';
        editorRow.appendChild(previewEl);

        wrapper.appendChild(this.textarea);
        this.textarea.style.display = 'none';

        this.wrapper = wrapper;
        this.toolbarEl = toolbarEl;
        this.editorRow = editorRow;
        this.cmHost = cmHost;
        this.previewEl = previewEl;
    };

    PlainMDE.prototype._buildCodeMirror = function () {
        var self = this;
        var opts = this.options;

        this.codemirror = window.CodeMirror(this.cmHost, {
            value: this.textarea.value,
            // "plainmde-gfm", not "gfm" — PlainMDE's own forked copy (see
            // vendor/codemirror-addons/mode/markdown/plainmde-markdown.js),
            // registered under its own mode name so it can't collide with
            // another module's unpatched "gfm"/"markdown" mode sharing the
            // same global CodeMirror instance on the same admin page.
            mode: 'plainmde-gfm',
            theme: opts.theme || 'default',
            lineNumbers: false,
            lineWrapping: opts.lineWrapping !== false,
            placeholder: opts.placeholder || '',
            autoRefresh: true,
            extraKeys: {
                'Cmd-B': function (cm) { actions.bold(cm, self); },
                'Ctrl-B': function (cm) { actions.bold(cm, self); },
                'Cmd-I': function (cm) { actions.italic(cm, self); },
                'Ctrl-I': function (cm) { actions.italic(cm, self); },
                'Cmd-K': function (cm) { actions.link(cm, self); },
                'Ctrl-K': function (cm) { actions.link(cm, self); },
                'Cmd-P': function (cm) { self.togglePreview(); },
                'Ctrl-P': function (cm) { self.togglePreview(); },
                'Esc': function (cm) { if (self.fullscreenActive) self.toggleFullscreen(); },
                // Without this, Enter falls through to CodeMirror's plain default
                // (newlineAndIndent) and list/checklist continuation — the
                // command continuelist.js defines and plainmde-decorations.js's
                // enableChecklistContinuation() patches — never actually fires.
                'Enter': 'newlineAndIndentContinueMarkdownList'
            }
        });

        if (opts.maxHeight) {
            this.codemirror.setSize(null, opts.maxHeight);
            // .pmde-preview is a plain div with no content-independent height
            // of its own (unlike CodeMirror, which setSize() just gave a hard
            // pixel height) — in side-by-side mode it was growing to fit
            // whatever markdown it renders, stretching .pmde-editor-row along
            // with it while the editor stayed pinned at maxHeight, leaving a
            // blank gap below the (now too-short-looking) editor. Give the
            // row the same bound so both panes actually share it; fullscreen
            // mode overrides this via `flex: 1 1 auto` + `height: 100%
            // !important` (a flex item's own basis, which is what this
            // becomes, still grows/shrinks with flex-grow set), so this
            // doesn't fight that.
            this.editorRow.style.height = opts.maxHeight;
        }

        this.codemirror.on('change', function () {
            self.textarea.value = self.codemirror.getValue();
            if (self.previewActive || self.sideBySideActive) self._renderPreview();
            if (typeof opts.onChange === 'function') opts.onChange(self.textarea.value);
        });

        if (opts.autofocus) this.codemirror.focus();
    };

    PlainMDE.prototype._buildToolbar = function () {
        var self = this;
        var names = this.options.toolbar || PlainMDE.defaultToolbar;
        this.toolbarEl.innerHTML = '';
        this.toolbarButtons = {};

        names.forEach(function (name) {
            if (name === '|') {
                var sep = document.createElement('span');
                sep.className = 'pmde-separator';
                self.toolbarEl.appendChild(sep);
                return;
            }
            if (name === 'spacer') {
                // Flex-grows to push everything after it (the view-switching
                // group by default) to the far right of the toolbar row.
                var spacer = document.createElement('span');
                spacer.className = 'pmde-spacer';
                self.toolbarEl.appendChild(spacer);
                return;
            }
            var def = PlainMDE.buttons[name];
            if (!def) return; // unknown button name — skip rather than throw

            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'pmde-btn pmde-btn-' + name;
            btn.title = def.title;
            btn.setAttribute('aria-label', def.title);
            btn.innerHTML = (window.PLAINMDE_ICONS && window.PLAINMDE_ICONS[def.icon]) || def.title;
            btn.addEventListener('click', function () {
                var fn = actions[name];
                if (fn) fn(self.codemirror, self);
            });
            self.toolbarEl.appendChild(btn);
            self.toolbarButtons[name] = btn;
        });
    };

    PlainMDE.prototype._renderPreview = function () {
        this.previewEl.innerHTML = this.previewRender(this.codemirror.getValue(), this);
        // Tree fences (see plainmde-preview.js) become visual trees — no-op when
        // no renderer is loaded on this page, and it must run before hljs below,
        // which would otherwise highlight a tree's <pre><code> as source.
        if (window.WbceFileTree && window.WbceFileTree.enhance) {
            window.WbceFileTree.enhance(this.previewEl);
        }
        // Trial: highlight.js, loaded by the page (not bundled here) — no-op
        // when window.hljs isn't present, so this stays safe for every
        // PlainMDE consumer whether or not it opts into loading hljs.
        if (window.hljs) {
            var blocks = this.previewEl.querySelectorAll('pre code');
            for (var i = 0; i < blocks.length; i++) {
                hljs.highlightElement(blocks[i]);
            }
        }
    };

    PlainMDE.prototype.togglePreview = function () {
        this.previewActive = !this.previewActive;
        if (this.previewActive) this.sideBySideActive = false;

        this.wrapper.classList.toggle('pmde-preview-active', this.previewActive);
        this.wrapper.classList.remove('pmde-sidebyside-active');
        this._setButtonActive('preview', this.previewActive);
        this._setButtonActive('side-by-side', false);

        if (this.previewActive) this._renderPreview();
        this.codemirror.refresh();
    };

    PlainMDE.prototype.toggleSideBySide = function () {
        this.sideBySideActive = !this.sideBySideActive;
        if (this.sideBySideActive) this.previewActive = false;

        this.wrapper.classList.toggle('pmde-sidebyside-active', this.sideBySideActive);
        this.wrapper.classList.remove('pmde-preview-active');
        this._setButtonActive('side-by-side', this.sideBySideActive);
        this._setButtonActive('preview', false);

        if (this.sideBySideActive) this._renderPreview();
        this.codemirror.refresh();
        if (typeof this.options.onSideBySideToggle === 'function') {
            this.options.onSideBySideToggle(this.sideBySideActive);
        }
    };

    PlainMDE.prototype.toggleFullscreen = function () {
        this.fullscreenActive = !this.fullscreenActive;
        this.wrapper.classList.toggle('pmde-fullscreen-active', this.fullscreenActive);
        this._setButtonActive('fullscreen', this.fullscreenActive);
        this.codemirror.refresh();
    };

    PlainMDE.prototype._setButtonActive = function (name, isActive) {
        var btn = this.toolbarButtons[name];
        if (btn) btn.classList.toggle('pmde-btn-active', isActive);
    };

    PlainMDE.prototype.value = function (text) {
        if (text === undefined) return this.codemirror.getValue();
        this.codemirror.setValue(text);
        return this;
    };

    PlainMDE.prototype.destroy = function () {
        this.wrapper.parentNode.removeChild(this.wrapper);
        this.textarea.style.display = '';
    };

    return PlainMDE;
})();
