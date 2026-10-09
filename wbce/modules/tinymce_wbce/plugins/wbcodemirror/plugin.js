/**
 * tinymce_wbce — plugins/wbcodemirror/plugin.js
 * TinyMCE plugin: source code editor backed by CodeMirror 5.
 * Replaces the built-in 'code' plugin with a CM5 bridge popup.
 * @author  Slugger & Claude KI
 * @license GNU GPL2
 */
tinymce.PluginManager.add('wbcodemirror', function(editor) {

    // Translations injected as window.TINYMCE_WBCE_I18N by include.php / tool.php;
    // English fallback keeps the plugin usable without the injection.
    var I18N = window.TINYMCE_WBCE_I18N || {};
    var cmTooltip = I18N.cmTooltip || 'Source code (CodeMirror)';

    // Open the CM5 popup
    function openCodeMirror() {
        var cbBase = 'wbcmCb_' + Date.now();
        var content = editor.getContent({ format: 'raw' });

        // Getter: the popup reads the current content
        window[cbBase + '_get'] = function() { return content; };

        // Setter: the popup writes back into the editor
        window[cbBase + '_set'] = function(newContent) {
            editor.setContent(newContent);
            editor.undoManager.add();
            delete window[cbBase + '_get'];
            delete window[cbBase + '_set'];
        };

        // postMessage fallback
        var msgHandler = function(e) {
            if (e.origin !== window.location.origin) return;
            if (e.data && e.data.mceAction === 'codeUpdate') {
                editor.setContent(e.data.content);
                editor.undoManager.add();
                window.removeEventListener('message', msgHandler);
            }
        };
        window.addEventListener('message', msgHandler);

        // Open the popup window
        var w = 900, h = 650;
        var left  = Math.round(screen.width  / 2 - w / 2);
        var top   = Math.round(screen.height / 2 - h / 2);
        var url   = TINYMCE_CODEMIRROR_URL + '?callback=' + encodeURIComponent(cbBase);

        window.open(url, 'wbce_codemirror',
            'width=' + w + ',height=' + h +
            ',top=' + top + ',left=' + left +
            ',resizable=yes,scrollbars=no');
    }

    // Re-point the default 'code' menu item (e.g. View > Source code) at the
    // CM5 source view, so it matches the toolbar button. Registered during
    // plugin init — after the bundled 'code' plugin — so this wins. onAction
    // calls openCodeMirror directly (no command indirection, fires reliably).
    // This does NOT touch Format > Code, which is a separate inline-code item.
    editor.ui.registry.addMenuItem('code', {
        icon: 'sourcecode',
        text: cmTooltip,
        onAction: openCodeMirror
    });

    // Register the toolbar button
    editor.ui.registry.addButton('wbcodemirror', {
        icon: 'sourcecode',
        tooltip: cmTooltip,
        onAction: openCodeMirror
    });

    // Also available as a menu item
    editor.ui.registry.addMenuItem('wbcodemirror', {
        icon: 'sourcecode',
        text: cmTooltip,
        onAction: openCodeMirror
    });
});
