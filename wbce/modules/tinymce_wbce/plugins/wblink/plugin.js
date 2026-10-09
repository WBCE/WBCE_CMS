/**
 * tinymce_wbce — plugins/wblink/plugin.js
 * TinyMCE 8 plugin: internal WBCE page links with page tree picker.
 * @author  Slugger & Claude KI
 * @license GNU GPL2
 */
(function () {
    'use strict';

    // Translations injected as window.TINYMCE_WBCE_I18N by include.php / tool.php;
    // English fallbacks keep the plugin usable without the injection.
    var I18N = window.TINYMCE_WBCE_I18N || {};
    var t = function (key, fallback) { return I18N[key] || fallback; };

    tinymce.PluginManager.add('wblink', function (editor) {

        editor.options.register('wblink_ajax_url', { processor: 'string', default: '' });

        var pages  = [];
        var loaded = false;

        function loadPages(callback) {
            if (loaded) { callback(pages); return; }
            var url = editor.options.get('wblink_ajax_url');
            if (!url) { callback([]); return; }
            fetch(url)
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    pages = data || [];
                    if (pages.length) { loaded = true; }
                    callback(pages);
                })
                .catch(function() { callback([]); });
        }

        function getSelectedLink() {
            var anchor = editor.dom.getParent(editor.selection.getNode(), 'a');
            if (anchor) {
                return {
                    href:   anchor.getAttribute('data-mce-href') || anchor.getAttribute('href') || '',
                    text:   anchor.textContent || '',
                    target: anchor.getAttribute('target') || ''
                };
            }
            return {
                href:   '',
                text:   editor.selection.getContent({ format: 'text' }) || '',
                target: ''
            };
        }

        function esc(s) {
            return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        function openDialog() {
            loadPages(function(list) {
                if (!list.length) {
                    editor.windowManager.alert(t('wblinkNoPages', 'No pages available.'));
                    return;
                }

                var current      = getSelectedLink();
                var selectedPage = null;

                if (current.href) {
                    list.forEach(function(p) {
                        if (p.link === current.href) { selectedPage = p; }
                    });
                }

                // Inline styles instead of CSS classes — works regardless of the Shadow DOM
                function renderItems(filter) {
                    var f = (filter || '').toLowerCase().trim();
                    var html = '';
                    var visIcon = { hidden: ' 👁', none: ' 🔒', registered: ' 🔑' };

                    list.forEach(function(p) {
                        if (f && String(p.page_id).indexOf(f) < 0 && p.title.toLowerCase().indexOf(f) < 0) return;

                        var indent  = parseInt(p.level || 0, 10);
                        var isSel   = selectedPage && selectedPage.page_id === p.page_id;
                        var bgColor = isSel ? '#cce0ff' : 'transparent';
                        var fwt     = isSel ? 'bold' : 'normal';
                        var vis     = (p.visibility && p.visibility !== 'public') ? (visIcon[p.visibility] || '') : '';

                        html += '<div'
                            + ' data-link="' + esc(p.link) + '"'
                            + ' data-title="' + esc(p.title) + '"'
                            + ' data-id="' + p.page_id + '"'
                            + ' class="wbl-item"'
                            + ' style="'
                            +   'display:flex;align-items:center;gap:6px;'
                            +   'padding:5px 8px 5px ' + (8 + indent * 14) + 'px;'
                            +   'cursor:pointer;font-size:13px;'
                            +   'border-bottom:1px solid #eee;'
                            +   'background:' + bgColor + ';'
                            +   'font-weight:' + fwt + ';'
                            +   'box-sizing:border-box;'
                            + '">';

                        // ID Badge — inline style
                        html += '<span style="'
                            +   'background:#2d6aa0;color:#fff;'
                            +   'border-radius:3px;padding:2px 6px;'
                            +   'font-size:11px;font-weight:bold;'
                            +   'min-width:24px;text-align:center;'
                            +   'flex-shrink:0;line-height:1.4;'
                            +   'font-family:monospace;'
                            + '">' + p.page_id + '</span>';

                        if (indent > 0) {
                            html += '<span style="color:#bbb;font-size:11px;flex-shrink:0">└</span>';
                        }

                        html += '<span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#111">'
                            + esc(p.title) + vis + '</span>';

                        html += '</div>';
                    });

                    if (!html) {
                        html = '<div style="padding:12px;color:#888;text-align:center;font-size:13px">'
                            + esc(t('wblinkNoResults', 'No pages found.')) + '</div>';
                    }
                    return html;
                }

                var initText = current.text || '';
                var initTgt  = current.target || '';

                // Input field style as a string (reusable)
                var inputStyle = 'width:100%;padding:7px 10px;'
                    + 'border:2px solid #b0bec5;border-radius:4px;'
                    + 'font-size:13px;color:#222;background:#fff;'
                    + 'box-sizing:border-box;outline:none;'
                    + 'font-family:inherit;';

                var labelStyle = 'display:block;font-size:12px;font-weight:bold;'
                    + 'color:#444;margin-bottom:3px;';

                var html = ''
                    // Search field
                    + '<div style="margin-bottom:6px">'
                    + '<input id="wbl_search" type="text" placeholder="'
                    + esc(t('wblinkSearch', 'Search (ID or title)…')) + '"'
                    + ' autocomplete="off"'
                    + ' style="' + inputStyle + '">'
                    + '</div>'

                    // Page tree
                    + '<div id="wbl_tree" style="'
                    +   'max-height:220px;overflow-y:auto;'
                    +   'border:2px solid #b0bec5;border-radius:4px;'
                    +   'background:#fff;margin-bottom:8px;'
                    + '">' + renderItems('') + '</div>'

                    // Link text
                    + '<div style="margin-bottom:6px">'
                    + '<label style="' + labelStyle + '">' + esc(t('wblinkText', 'Link text')) + '</label>'
                    + '<input id="wbl_text" type="text" value="' + esc(initText) + '"'
                    + ' style="' + inputStyle + '">'
                    + '</div>'

                    // Target
                    + '<div>'
                    + '<label style="' + labelStyle + '">' + esc(t('wblinkOpenIn', 'Open in')) + '</label>'
                    + '<select id="wbl_target" style="' + inputStyle + '">'
                    + '<option value=""' + (!initTgt ? ' selected' : '') + '>'
                    + esc(t('wblinkSameTab', 'Same tab (_self)')) + '</option>'
                    + '<option value="_blank"' + (initTgt === '_blank' ? ' selected' : '') + '>'
                    + esc(t('wblinkNewTab', 'New tab (_blank)')) + '</option>'
                    + '</select>'
                    + '</div>';

                editor.windowManager.open({
                    title:  t('wblinkTitle', 'Insert internal link'),
                    size:   'normal',
                    body: {
                        type:  'panel',
                        items: [{ type: 'htmlpanel', html: html }]
                    },
                    buttons: [
                        { type: 'cancel',  text: t('cancel', 'Cancel') },
                        { type: 'submit',  text: t('insert', 'Insert'), primary: true }
                    ],
                    onSubmit: function(api) {
                        var href = selectedPage ? selectedPage.link : (list[0] ? list[0].link : '');
                        if (!href) { api.close(); return; }

                        var txtEl = document.getElementById('wbl_text');
                        var tgtEl = document.getElementById('wbl_target');
                        var text  = (txtEl && txtEl.value) ? txtEl.value : href;
                        var tgt   = tgtEl ? tgtEl.value : '';

                        var anchor = editor.dom.getParent(editor.selection.getNode(), 'a');
                        if (anchor) {
                            editor.dom.setAttrib(anchor, 'href', href);
                            editor.dom.setAttrib(anchor, 'data-mce-href', href);
                            if (tgt) editor.dom.setAttrib(anchor, 'target', tgt);
                            else editor.dom.removeAttrib(anchor, 'target');
                            anchor.textContent = text;
                        } else {
                            var tgtAttr = tgt ? ' target="' + tgt + '"' : '';
                            editor.insertContent('<a href="' + href + '"' + tgtAttr + '>' + text + '</a>');
                        }
                        api.close();
                    },
                    onOpen: function() {
                        setTimeout(function() {
                            var tree   = document.getElementById('wbl_tree');
                            var search = document.getElementById('wbl_search');
                            var txtIn  = document.getElementById('wbl_text');
                            if (!tree) return;

                            function bindClicks() {
                                tree.querySelectorAll('.wbl-item').forEach(function(item) {
                                    item.addEventListener('click', function() {
                                        // Deselect all
                                        tree.querySelectorAll('.wbl-item').forEach(function(i) {
                                            i.style.background = 'transparent';
                                            i.style.fontWeight = 'normal';
                                        });
                                        // Select this one
                                        this.style.background = '#cce0ff';
                                        this.style.fontWeight = 'bold';

                                        var id = parseInt(this.dataset.id, 10);
                                        list.forEach(function(p) {
                                            if (p.page_id === id) { selectedPage = p; }
                                        });
                                        // Prefill the link text
                                        if (txtIn && !txtIn.value) {
                                            txtIn.value = this.dataset.title;
                                        }
                                    });
                                    // Hover effect
                                    item.addEventListener('mouseenter', function() {
                                        if (this.style.background !== 'rgb(204, 224, 255)') {
                                            this.style.background = '#eef3fb';
                                        }
                                    });
                                    item.addEventListener('mouseleave', function() {
                                        var isSel = selectedPage && parseInt(this.dataset.id,10) === selectedPage.page_id;
                                        this.style.background = isSel ? '#cce0ff' : 'transparent';
                                    });
                                });
                            }
                            bindClicks();

                            // Scroll the preselected page into view
                            var selEl = tree.querySelector('[data-id="' + (selectedPage ? selectedPage.page_id : '') + '"]');
                            if (selEl) selEl.scrollIntoView({ block: 'nearest' });

                            // Focus styles for the inputs
                            [search, txtIn, document.getElementById('wbl_target')].forEach(function(el) {
                                if (!el) return;
                                el.addEventListener('focus', function() {
                                    this.style.borderColor = '#2d6aa0';
                                    this.style.boxShadow  = '0 0 0 2px rgba(45,106,160,.2)';
                                });
                                el.addEventListener('blur', function() {
                                    this.style.borderColor = '#b0bec5';
                                    this.style.boxShadow  = 'none';
                                });
                            });

                            // Search
                            if (search) {
                                search.addEventListener('input', function() {
                                    tree.innerHTML = renderItems(this.value);
                                    bindClicks();
                                });
                                search.focus();
                            }
                        }, 80);
                    }
                });
            });
        }

        editor.ui.registry.addButton('wblink', {
            icon: 'link',
            tooltip: t('wblinkTitle', 'Insert internal link'),
            onAction: openDialog
        });

        editor.ui.registry.addMenuItem('wblink', {
            text: t('wblinkTitle', 'Insert internal link'),
            onAction: openDialog
        });

        editor.on('dblclick', function() {
            var anchor = editor.dom.getParent(editor.selection.getNode(), 'a');
            if (anchor && /^\[wblink\d+\]/.test(anchor.getAttribute('href') || '')) {
                openDialog();
            }
        });

        return { getMetadata: function() { return { name: 'WB Link', url: 'https://wbce.org' }; } };
    });
}());
