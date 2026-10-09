/**
 * tinymce_wbce — plugins/wbce_casechange/plugin.js
 *
 * Case-change dropdown ("Aa"): lowercase / UPPERCASE / Title Case / small caps.
 *
 * TinyMCE has NO core commands for this (case change is a premium plugin —
 * the often-quoted execCommand('UpperCase') etc. simply do not exist), so the
 * transforms are done here: the selected fragment is parsed, only its TEXT
 * nodes are rewritten (locale-aware), and the fragment is re-inserted — all
 * markup inside the selection survives.
 *
 * "Small caps" is styling, not a text transform: a toggleable inline format
 * (span with font-variant: small-caps) with a checkmark reflecting the
 * cursor state, like bold/italic.
 *
 * A collapsed selection is expanded to the current word first.
 *
 * @license GNU GPL2
 */
(function () {
    'use strict';

    tinymce.PluginManager.add('wbce_casechange', function (editor) {

        function t(key, fallback) {
            var map = (window.TINYMCE_WBCE_I18N || {}).caseChange || {};
            return map[key] || fallback;
        }

        // ── Text transforms ───────────────────────────────────────────────────
        function toLower(s) { return s.toLocaleLowerCase(); }
        function toUpper(s) { return s.toLocaleUpperCase(); }
        function toTitle(s) {
            return s.replace(/[^\s \-\/\(\)"'„“»«]+/g, function (w) {
                return w.charAt(0).toLocaleUpperCase() + w.slice(1).toLocaleLowerCase();
            });
        }

        /** Rewrite only the text nodes of the selected fragment. */
        function transformSelection(fn) {
            if (editor.selection.isCollapsed()) {
                // Expand to the word under the cursor (TinyMCE 6.2+)
                try { editor.selection.expand({ type: 'word' }); } catch (e) { /* older API — ignore */ }
                if (editor.selection.isCollapsed()) { return; }
            }
            var html = editor.selection.getContent({ format: 'html' });
            if (!html) { return; }
            var div = document.createElement('div');
            div.innerHTML = html;
            var walker = document.createTreeWalker(div, NodeFilter.SHOW_TEXT, null);
            var node;
            while ((node = walker.nextNode())) {
                node.nodeValue = fn(node.nodeValue);
            }
            editor.undoManager.transact(function () {
                editor.selection.setContent(div.innerHTML);
            });
        }

        // ── Small caps as a toggleable inline format ─────────────────────────
        editor.on('PreInit', function () {
            editor.formatter.register('wbce_smallcaps', {
                inline: 'span',
                styles: { 'font-variant': 'small-caps' }
            });
        });

        // ── UI ────────────────────────────────────────────────────────────────
        editor.ui.registry.addMenuButton('wbce_casechange', {
            text:    'Aa',
            tooltip: t('tooltip', 'Change case'),
            fetch: function (callback) {
                callback([
                    {
                        type: 'menuitem',
                        text: t('lower', 'lowercase'),
                        onAction: function () { transformSelection(toLower); }
                    },
                    {
                        type: 'menuitem',
                        text: t('upper', 'UPPERCASE'),
                        onAction: function () { transformSelection(toUpper); }
                    },
                    {
                        type: 'menuitem',
                        text: t('title', 'Title Case'),
                        onAction: function () { transformSelection(toTitle); }
                    },
                    {
                        type: 'togglemenuitem',
                        text: t('smallcaps', 'Small caps'),
                        onAction: function () {
                            editor.formatter.toggle('wbce_smallcaps');
                            editor.nodeChanged();
                        },
                        onSetup: function (api) {
                            function sync() { api.setActive(editor.formatter.match('wbce_smallcaps')); }
                            sync();
                            editor.on('NodeChange', sync);
                            return function () { editor.off('NodeChange', sync); };
                        }
                    }
                ]);
            }
        });

        return { getMetadata: function () { return { name: 'WBCE Case Change' }; } };
    });
}());
