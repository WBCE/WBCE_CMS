/**
 * tinymce_wbce — plugins/wbdroplets/plugin.js
 * TinyMCE 8 plugin: insert WBCE Droplets via dialog.
 * Fetches droplet list from ajax_droplets.php (JSON).
 * @author  SC-Peet
 * @license GNU GPL2
 */

(function () {
    'use strict';

    // Translations injected as window.TINYMCE_WBCE_I18N by include.php / tool.php;
    // English fallbacks keep the plugin usable without the injection.
    var I18N = window.TINYMCE_WBCE_I18N || {};
    var t = function (key, fallback) { return I18N[key] || fallback; };

    tinymce.PluginManager.add('wbdroplets', function (editor) {

        // Register custom options (required in TinyMCE 6+)
        editor.options.register('wbdroplets_ajax_url', { processor: 'string', default: '' });
        editor.options.register('wbdroplets_no_droplets', { processor: 'string', default: '' });

        var droplets = [];
        var loaded   = false;

        // -- Load droplet list once via AJAX ----------------------------------
        function loadDroplets(callback) {
            if (loaded) { callback(droplets); return; }

            var url = editor.options.get('wbdroplets_ajax_url');
            if (!url) { callback([]); return; }

            fetch(url)
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    droplets = data || [];
                    if (droplets.length > 0) { loaded = true; }
                    callback(droplets);
                })
                .catch(function () { callback([]); });
        }

        // -- Open dialog ------------------------------------------------------
        function openDialog() {
            loadDroplets(function (list) {

                if (!list.length) {
                    editor.windowManager.alert(
                        editor.options.get('wbdroplets_no_droplets')
                        || t('dropletNone', 'No droplets available.')
                    );
                    return;
                }

                // Pre-select if cursor is inside [[...]]
                var selected = getSelectedDropletName();

                var selectItems = list.map(function (d) {
                    return { text: d.name, value: d.name };
                });

                editor.windowManager.open({
                    title:       t('dropletTitle', 'Insert droplet'),
                    size:        'normal',
                    body: {
                        type: 'panel',
                        items: [
                            {
                                type:  'selectbox',
                                name:  'droplet',
                                label: 'Droplet',
                                items: selectItems
                            },
                            {
                                type:  'htmlpanel',
                                name:  'info',
                                html:  '<div id="tinymce-wbdroplets-info" style="min-height:2em;font-size:12px;color:#555;padding:4px 0"></div>'
                            },
                            {
                                type:  'input',
                                name:  'syntax',
                                label: 'Syntax',
                            }
                        ]
                    },
                    buttons: [
                        {
                            type:    'cancel',
                            text:    t('cancel', 'Cancel')
                        },
                        {
                            type:    'submit',
                            text:    t('insert', 'Insert'),
                            primary: true
                        }
                    ],
                    initialData: {
                        droplet: selected || (list[0] ? list[0].name : ''),
                        syntax:  selected ? '[[' + selected + ']]' : (list[0] ? '[[' + list[0].name + ']]' : ''),
                        info:    ''
                    },
                    onChange: function (api, details) {
                        if (details.name === 'droplet') {
                            var val  = api.getData().droplet;
                            var item = list.filter(function (d) { return d.name === val; })[0];
                            var desc = item ? (item.description || '') : '';
                            var use  = item ? (item.usage || '') : '';

                            // Update info panel
                            var el = document.getElementById('tinymce-wbdroplets-info');
                            if (el) { el.innerHTML = desc + (use ? '<br><em>' + use + '</em>' : ''); }

                            // Auto-fill syntax from usage if it contains [[...]]
                            var syntax = '[[' + val + ']]';
                            if (use && /\[\[.*\]\]/.test(use)) {
                                var m = use.match(/\[\[([^\]]*)\]\]/);
                                if (m) { syntax = '[[' + m[1] + ']]'; }
                            }
                            api.setData({ syntax: syntax });
                        }
                    },
                    onSubmit: function (api) {
                        var data = api.getData();
                        editor.insertContent(data.syntax || '[[' + data.droplet + ']]');
                        api.close();
                    }
                });
            });
        }

        // -- Get droplet name if cursor is inside [[name]] --------------------
        function getSelectedDropletName() {
            var content = editor.selection.getContent({ format: 'text' });
            if (!content) {
                // Check node text
                var node = editor.selection.getNode();
                content  = node ? (node.textContent || node.innerText || '') : '';
            }
            var m = content.match(/\[\[([^\]]+)\]\]/);
            return m ? m[1].split('?')[0].trim() : null;
        }

        // -- Register toolbar button ------------------------------------------
        // Flat droplet icon (💧 as monochrome SVG, follows the toolbar color)
        editor.ui.registry.addIcon('wb-droplet', '<svg width="24" height="24" viewBox="0 0 24 24"><path d="M12 2.4c.3 0 .57.15.73.4C14.4 5.4 18.5 10.3 18.5 14a6.5 6.5 0 1 1-13 0c0-3.7 4.1-8.6 5.77-11.2.16-.25.43-.4.73-.4zm0 2.53C10.4 7.3 7.5 11.2 7.5 14a4.5 4.5 0 0 0 9 0c0-2.8-2.9-6.7-4.5-9.07z" fill-rule="nonzero"/></svg>');

        editor.ui.registry.addButton('wbdroplets', {
            icon:    'wb-droplet',
            tooltip: t('dropletTitle', 'Insert droplet'),
            onAction: function () { openDialog(); }
        });

        // -- Register menu item -----------------------------------------------
        editor.ui.registry.addMenuItem('wbdroplets', {
            text:    t('dropletTitle', 'Insert droplet'),
            icon:    'wb-droplet',
            onAction: function () { openDialog(); }
        });

        // -- Double-click on [[...]] opens dialog -----------------------------
        editor.on('dblclick', function () {
            if (getSelectedDropletName()) { openDialog(); }
        });

        return {
            getMetadata: function () {
                return { name: 'WB Droplets', url: 'https://wbce.org' };
            }
        };
    });

}());
