/**
 * tinymce_wbce — plugins/wbce_shy/plugin.js
 *
 * Inserts a soft hyphen (&shy;, U+00AD) at the cursor: an invisible break
 * opportunity — the browser hyphenates there only when the word has to wrap.
 * TinyMCE has no core button for it.
 *
 * @license GNU GPL2
 */
(function () {
    'use strict';

    tinymce.PluginManager.add('wbce_shy', function (editor) {

        function t(fallback) {
            var map = window.TINYMCE_WBCE_I18N || {};
            return map.shyTooltip || fallback;
        }

        function insertShy() {
            // entity_encoding:'raw' keeps this as the raw U+00AD character
            editor.insertContent('­');
            // visualchars only redecorates on keydown (throttled) — its sole
            // public hook is the mceVisualChars toggle. Off+on redecorates
            // immediately so the pink dot shows without further typing.
            editor.execCommand('mceVisualChars');
            editor.execCommand('mceVisualChars');
        }

        // "a-b" glyph as a flat icon
        editor.ui.registry.addIcon('wb-shy', '<svg width="24" height="24" viewBox="0 0 24 24"><text x="12" y="16" text-anchor="middle" font-size="11" font-family="sans-serif" fill="currentColor">a-b</text></svg>');

        editor.ui.registry.addButton('wbce_shy', {
            icon:    'wb-shy',
            tooltip: t('Soft hyphen'),
            onAction: insertShy
        });

        editor.ui.registry.addMenuItem('wbce_shy', {
            text:    t('Soft hyphen'),
            icon:    'wb-shy',
            onAction: insertShy
        });

        return { getMetadata: function () { return { name: 'WBCE Soft Hyphen' }; } };
    });
}());
