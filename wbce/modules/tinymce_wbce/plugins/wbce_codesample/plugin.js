/**
 * tinymce_wbce — plugins/wbce_codesample/plugin.js
 *
 * Insert/edit a syntax-highlightable code block. Opens a CodeMirror 5 popup
 * (codesample_tinymce.php) with a language dropdown; on apply it inserts
 * <pre class="language-x"><code class="language-x">…escaped…</code></pre>
 * — Prism-compatible markup, so a frontend/editor highlighter picks it up.
 *
 * A WBCE alternative to TinyMCE's bundled 'codesample' plugin: it uses the
 * CM5 that already ships in modules/CodeMirror_Config (same engine as the
 * wbcodemirror source view) and offers WBCE-relevant languages (incl. Twig).
 *
 * Double-clicking an existing code block re-opens it for editing.
 *
 * @license GNU GPL2
 */
(function () {
    'use strict';

    tinymce.PluginManager.add('wbce_codesample', function (editor) {

        // Popup URL injected by include.php / the configurator.
        editor.options.register('wbce_codesample_url', { processor: 'string', default: '' });

        function t(key, fallback) {
            var map = (window.TINYMCE_WBCE_I18N || {}).codeSample || {};
            return map[key] || fallback;
        }

        function esc(s) {
            return String(s == null ? '' : s)
                .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }
        function unesc(s) {
            var d = document.createElement('textarea');
            d.innerHTML = String(s == null ? '' : s);
            return d.value;
        }

        // The <pre> code block under the cursor, if any.
        function currentPre() {
            var node = editor.selection.getNode();
            var pre  = editor.dom.getParent(node, 'pre');
            return (pre && /language-/.test(pre.className || '')) ? pre : null;
        }

        function langFromClass(cls) {
            var m = /language-([\w-]+)/.exec(cls || '');
            return m ? m[1] : 'markup';
        }

        function openPopup() {
            var url = editor.options.get('wbce_codesample_url');
            if (!url) { console.warn('[tinymce wbce_codesample] wbce_codesample_url not configured.'); return; }

            var pre  = currentPre();
            var code = '';
            var language = 'markup';
            if (pre) {
                var codeEl = pre.querySelector('code') || pre;
                code = unesc(codeEl.innerHTML.replace(/<br\s*\/?>/gi, '\n'));
                language = langFromClass(pre.className) || langFromClass(codeEl.className);
            }

            var cbBase = 'wbcsCb_' + Date.now();

            // Getter: the popup reads the current { code, language }
            window[cbBase + '_get'] = function () { return { code: code, language: language }; };

            // Setter: the popup writes back the edited block
            window[cbBase + '_set'] = function (data) {
                insertBlock(pre, data.code || '', data.language || 'markup');
                cleanup();
            };

            var msgHandler = function (e) {
                if (e.origin !== window.location.origin) { return; }
                if (e.data && e.data.mceAction === 'codeSampleUpdate') {
                    var p = e.data.payload || {};
                    insertBlock(pre, p.code || '', p.language || 'markup');
                    window.removeEventListener('message', msgHandler);
                    cleanup();
                }
            };
            window.addEventListener('message', msgHandler);

            function cleanup() {
                delete window[cbBase + '_get'];
                delete window[cbBase + '_set'];
                window.removeEventListener('message', msgHandler);
            }

            var w = 900, h = 600;
            var left = Math.round(screen.width  / 2 - w / 2);
            var top  = Math.round(screen.height / 2 - h / 2);
            window.open(url + '?callback=' + encodeURIComponent(cbBase), 'wbce_codesample',
                'width=' + w + ',height=' + h + ',top=' + top + ',left=' + left + ',resizable=yes,scrollbars=yes');
        }

        function insertBlock(pre, code, language) {
            var cls  = 'language-' + language;
            var html = '<pre class="' + cls + '"><code class="' + cls + '">' + esc(code) + '</code></pre>';
            editor.undoManager.transact(function () {
                if (pre) {
                    editor.dom.setOuterHTML(pre, html);
                } else {
                    editor.insertContent(html);
                }
            });
            editor.nodeChanged();
        }

        editor.ui.registry.addIcon('wb-codesample', '<svg width="24" height="24" viewBox="0 0 24 24"><path d="M9.4 16.6 4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0 4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"/><path d="M11 4h1.6l-2.4 16H8.6z" opacity=".5"/></svg>');

        editor.ui.registry.addButton('wbce_codesample', {
            icon: 'wb-codesample',
            tooltip: t('tooltip', 'Code sample'),
            onAction: openPopup
        });

        editor.ui.registry.addMenuItem('wbce_codesample', {
            icon: 'wb-codesample',
            text: t('tooltip', 'Code sample'),
            onAction: openPopup
        });

        editor.on('dblclick', function () {
            if (currentPre()) { openPopup(); }
        });

        return { getMetadata: function () { return { name: 'WBCE Code Sample' }; } };
    });
}());
