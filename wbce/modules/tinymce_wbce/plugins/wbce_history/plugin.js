/**
 * tinymce_wbce — plugins/wbce_history/plugin.js
 *
 * Self-hosted version history (a free alternative to TinyMCE's premium plugin).
 * Since WBCE has no global save button, this takes its OWN rotating snapshots —
 * debounced on change, on blur, and on the surrounding form's submit — and
 * POSTs them to history_ajax.php, which keys them per instance and rotates to
 * 10 versions (see history.php). A toolbar button opens a dialog to preview and
 * restore any stored version.
 *
 * Instance key: the plugin sends a context string
 *   location.pathname + location.search + '|' + <editor DOM id>
 * and the server hashes it. Most WBCE modules embed section_id/field_id in the
 * DOM id, and the URL disambiguates across modules — so the same field maps to
 * the same history across reloads. The author (user) is taken from the session
 * server-side, never trusted from here.
 *
 * In the preset configurator the URL is empty, so nothing is snapshotted for
 * the throwaway preview editors.
 *
 * @license GNU GPL2
 */
(function () {
    'use strict';

    tinymce.PluginManager.add('wbce_history', function (editor) {

        editor.options.register('wbce_history_url',     { processor: 'string',  default: '' });
        editor.options.register('wbce_history_preset',  { processor: 'string',  default: '' });
        editor.options.register('wbce_history_enabled', { processor: 'boolean', default: true });

        function t(key, fallback) {
            var map = (window.TINYMCE_WBCE_I18N || {}).history || {};
            return map[key] || fallback;
        }

        function apiUrl() { return editor.options.get('wbce_history_url'); }

        // A module (e.g. wysiwyg) can declare a stable, URL-independent key for a
        // field so its server-side "real save" and this dialog share ONE timeline:
        //   window.WBCE_TINYMCE_HISTORY_KEYS['content42'] = 'wysiwyg-section-42';
        function canonicalKey() {
            var map = window.WBCE_TINYMCE_HISTORY_KEYS || {};
            return map[editor.id] || null;
        }
        // Server-managed fields: the module records versions at real save time,
        // so the client must NOT autosnapshot (it would mix drafts in).
        function serverManaged() { return !!canonicalKey(); }

        function context() {
            var ck = canonicalKey();
            if (ck) { return 'key:' + ck; } // matches the server-side context
            return window.location.pathname + window.location.search + '|' + (editor.id || '');
        }

        function post(action, extra) {
            var url = apiUrl();
            if (!url) { return Promise.resolve(null); }
            var body = new URLSearchParams();
            body.set('action', action);
            body.set('context', context());
            body.set('preset', editor.options.get('wbce_history_preset') || '');
            if (extra) { Object.keys(extra).forEach(function (k) { body.set(k, extra[k]); }); }
            return fetch(url, { method: 'POST', body: body, credentials: 'same-origin' })
                .then(function (r) { return r.json(); })
                .catch(function (err) { console.warn('[tinymce wbce_history]', err); return null; });
        }

        // ── Snapshotting ─────────────────────────────────────────────────────
        var lastSent = null;   // content string of the last POSTed snapshot
        var timer    = null;

        function snapshot() {
            if (!apiUrl()) { return; }
            var content = editor.getContent();
            if (content === lastSent) { return; }   // client-side dedup (server dedups too)
            lastSent = content;
            post('snapshot', { content: content });
        }

        function scheduleSnapshot() {
            if (!apiUrl()) { return; }
            if (timer) { clearTimeout(timer); }
            timer = setTimeout(snapshot, 4000); // debounce active typing
        }

        editor.on('init', function () {
            if (!apiUrl()) { return; }
            // History off for this preset → no autosnapshot (dialog still lists
            // any existing versions).
            if (!editor.options.get('wbce_history_enabled')) { return; }
            // Server-managed fields (e.g. wysiwyg) record versions at real save
            // time — skip client autosnapshotting, but keep the dialog usable.
            if (serverManaged()) { return; }
            editor.on('change SetContent', scheduleSnapshot);
            editor.on('blur', snapshot);
            // Snapshot when the surrounding form is submitted (classic modules).
            var el = editor.getElement();
            var form = el && el.form;
            if (form && !form._wbceHistoryHooked) {
                form._wbceHistoryHooked = true;
                form.addEventListener('submit', function () {
                    try { editor.save(); } catch (e) {}
                    snapshot();
                });
            }
        });

        // ── Split-canvas revision history (à la the premium plugin) ──────────
        function esc(s) {
            return String(s == null ? '' : s)
                .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }
        // Apply layout props as !important inline styles so no TinyMCE/admin-theme
        // rule can override them (that reset was making the panel stack below).
        function forceStyle(el, props) {
            Object.keys(props).forEach(function (k) { el.style.setProperty(k, props[k], 'important'); });
        }
        function fmtTs(ts) {
            var p = String(ts || '').split(' ');
            var time = (p[1] || '').slice(0, 5); // HH:MM, drop seconds
            return p[0] + (time ? ', ' + time : '');
        }
        function nowTs() {
            var d = new Date(), p = function (n) { return (n < 10 ? '0' : '') + n; };
            return d.getFullYear() + '-' + p(d.getMonth() + 1) + '-' + p(d.getDate())
                + ' ' + p(d.getHours()) + ':' + p(d.getMinutes()) + ':00';
        }
        function whoLine(v) {
            var name = v.user_name || '';
            if (v.user_login) { name += name ? ' (' + v.user_login + ')' : v.user_login; }
            return name;
        }

        var PANEL_W = 320;
        var TOP_H   = 48;
        var STYLE_ID = 'wbce-history-style';
        function ensureStyles() {
            if (document.getElementById(STYLE_ID)) { return; }
            var s = document.createElement('style');
            s.id = STYLE_ID;
            s.textContent = ''
                + '.wbceh-top{position:absolute;top:0;left:0;right:0;height:48px;z-index:60;display:flex;align-items:center;'
                +   'justify-content:space-between;padding:0 14px;background:#fff;border-bottom:1px solid #e1e4e8;box-sizing:border-box;font-family:Helvetica,Arial,sans-serif;}'
                + '.wbceh-title{font-weight:600;font-size:15px;color:#1a2733;}'
                + '.wbceh-actions{display:flex;gap:8px;}'
                + '.wbceh-btn{padding:7px 14px;border-radius:5px;border:1px solid transparent;cursor:pointer;font-size:13px;font-weight:600;}'
                + '.wbceh-btn-primary{background:#1a73e8;color:#fff;}'
                + '.wbceh-btn-primary:hover{background:#1666cc;}'
                + '.wbceh-btn-ghost{background:#f1f3f4;color:#333;border-color:#d7dae0;}'
                + '.wbceh-btn-ghost:hover{background:#e6e9ec;}'
                + '.wbceh-list{position:absolute;top:48px;right:0;bottom:0;width:320px;z-index:60;overflow-y:auto;'
                +   'background:#fff;border-left:1px solid #e1e4e8;box-sizing:border-box;font-family:Helvetica,Arial,sans-serif;}'
                + '.wbceh-list-hd{font-weight:600;font-size:16px;color:#1a2733;padding:14px 16px 8px;}'
                + '.wbceh-empty{font-size:12px;color:#6b7280;padding:0 16px 10px;line-height:1.4;}'
                + '.wbceh-item{display:flex;align-items:center;gap:10px;padding:2px 10px;margin:3px 6px;border:1px solid #e6e9ec;'
                +   'border-radius:8px;cursor:pointer;position:relative;background:#fff;box-shadow:0 1px 2px rgba(0,0,0,.04);}'
                + '.wbceh-item:hover{background:#f5f7fa;}'
                + '.wbceh-item.sel{background:#fff8e1;border-color:#f5c542;box-shadow:0 1px 3px rgba(0,0,0,.08);}'
                + '.wbceh-meta{flex:1;min-width:0;}'
                + '.wbceh-ts{font-weight:600;font-size:13px;color:#1a2733;}'
                + '.wbceh-who{font-size:12px;color:#6b7280;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}'
                + '.wbceh-tag{display:inline-block;font-size:10px;font-weight:700;letter-spacing:.03em;color:#5a6673;'
                +   'background:#eef1f4;border-radius:3px;padding:1px 6px;margin-bottom:4px;text-transform:uppercase;}'
                + '.wbceh-check{color:#1a73e8;font-weight:700;flex-shrink:0;}'
                // Per-item delete (×): faint until the row is hovered, red on hover.
                + '.wbceh-del{flex-shrink:0;border:0;background:transparent;color:#b0b8c0;font-size:16px;line-height:1;'
                +   'cursor:pointer;padding:2px 5px;border-radius:4px;opacity:0;transition:opacity .12s,background .12s,color .12s;}'
                + '.wbceh-item:hover .wbceh-del{opacity:1;}'
                + '.wbceh-del:hover{background:#fdecec;color:#d33;}'
                // Dark skin variant
                + '.wbceh--dark .wbceh-top,.wbceh--dark .wbceh-list{background:#222f3e;border-color:#3b4a5e;}'
                + '.wbceh--dark .wbceh-title,.wbceh--dark .wbceh-list-hd,.wbceh--dark .wbceh-ts{color:#eef2f6;}'
                + '.wbceh--dark .wbceh-btn-ghost{background:#2f3f52;color:#eef2f6;border-color:#4a5b6f;}'
                + '.wbceh--dark .wbceh-item:hover{background:#2a3949;}'
                + '.wbceh--dark .wbceh-item.sel{background:#3a3320;border-color:#8a7434;}'
                + '.wbceh--dark .wbceh-tag{background:#2f3f52;color:#9fb0c0;}'
                + '.wbceh--dark .wbceh-del:hover{background:#4a2a2a;color:#ff9c9c;}'
                + '.wbceh--dark .wbceh-empty{color:#9fb0c0;}';
            document.head.appendChild(s);
        }

        var openState = null;

        function closeView(restoreOriginal) {
            if (!openState) { return; }
            var st = openState; openState = null;
            if (restoreOriginal) {
                editor.setContent(st.orig, { no_events: true });
            }
            editor.mode.set('design');
            st.nodes.forEach(function (n) { if (n && n.parentNode) { n.parentNode.removeChild(n); } });
            if (st.header)  { st.header.style.removeProperty('display'); }
            if (st.editWrap) {
                st.editWrap.style.removeProperty('padding-right');
                st.editWrap.style.removeProperty('padding-top');
                st.editWrap.style.removeProperty('box-sizing');
            }
            window.removeEventListener('resize', st.reposition);
            window.removeEventListener('scroll', st.reposition, true);
            document.removeEventListener('keydown', st.onKey);
        }

        function openHistory() {
            if (openState) { return; }
            if (!apiUrl()) {
                editor.windowManager.alert(t('none', 'No versions available.'));
                return;
            }
            post('list').then(function (res) {
                var stored = (res && res.versions) || [];

                ensureStyles();
                var container = editor.getContainer();
                if (!container) { return; }

                var orig = editor.getContent();
                editor.mode.set('readonly');

                var dark = (editor.options.get('skin') || '').indexOf('dark') !== -1;
                var versions = stored.slice(); // real stored versions (delete mutates this)
                var selected = 0;
                var display  = [];             // what the list currently shows

                // Build the overlay OUTSIDE the .tox subtree (document.body) so the
                // admin theme's broad `.tox :not(svg)` reset can't strip its
                // styling. It is positioned over the editor via its bounding rect.
                var root = document.createElement('div');
                root.className = 'wbceh-root' + (dark ? ' wbceh--dark' : '');

                var top = document.createElement('div');
                top.className = 'wbceh-top';
                top.innerHTML =
                    '<span class="wbceh-title"></span>'
                    + '<span class="wbceh-actions">'
                    +   '<button type="button" class="wbceh-btn wbceh-btn-primary wbceh-restore">' + esc(t('restore', 'Restore this version')) + '</button>'
                    +   '<button type="button" class="wbceh-btn wbceh-btn-ghost wbceh-close">' + esc(t('close', 'Close')) + '</button>'
                    + '</span>';

                var list = document.createElement('div');
                list.className = 'wbceh-list';

                root.appendChild(top);
                root.appendChild(list);
                document.body.appendChild(root);

                var titleEl    = top.querySelector('.wbceh-title');
                var restoreBtn = top.querySelector('.wbceh-restore');

                // Hide the editor chrome (menubar + toolbar) and reserve room for
                // the top bar + right panel inside the edit area.
                var header   = container.querySelector('.tox-editor-header');
                var editWrap = container.querySelector('.tox-sidebar-wrap') || container.querySelector('.tox-edit-area');
                if (header) { header.style.setProperty('display', 'none', 'important'); }
                if (editWrap) {
                    editWrap.style.setProperty('box-sizing', 'border-box', 'important');
                    editWrap.style.setProperty('padding-top', TOP_H + 'px', 'important');
                    editWrap.style.setProperty('padding-right', PANEL_W + 'px', 'important');
                }

                // Keep the body-level overlay aligned over the editor. root spans
                // the editor but is click-through; only the bar + list catch input.
                function reposition() {
                    var r = container.getBoundingClientRect();
                    var x = r.left + window.scrollX, y = r.top + window.scrollY;
                    forceStyle(root, { position: 'absolute', top: y + 'px', left: x + 'px', width: r.width + 'px', height: r.height + 'px', margin: '0', 'z-index': '2147483000', 'pointer-events': 'none' });
                    forceStyle(top,  { position: 'absolute', top: '0', left: '0', width: r.width + 'px', height: TOP_H + 'px', 'pointer-events': 'auto' });
                    forceStyle(list, { position: 'absolute', top: TOP_H + 'px', left: (r.width - PANEL_W) + 'px', width: PANEL_W + 'px', height: (r.height - TOP_H) + 'px', 'pointer-events': 'auto' });
                }

                // (Re)build the list from `versions`. With no stored versions we
                // show the current content as a single "Current" entry (no delete,
                // no restore) plus a hint — the same split view, never a blank one.
                function renderList() {
                    var isEmpty = !versions.length;
                    display = isEmpty
                        ? [{ ts: nowTs(), user_name: '', user_login: '', content: orig }]
                        : versions;
                    if (selected >= display.length) { selected = display.length - 1; }
                    if (selected < 0) { selected = 0; }

                    var html = '<div class="wbceh-list-hd">' + esc(t('title', 'Revision history')) + '</div>';
                    if (isEmpty) {
                        html += '<div class="wbceh-empty">' + esc(t('none', 'No versions available.')) + '</div>';
                    }
                    display.forEach(function (v, i) {
                        var tag = (i === 0) ? '<span class="wbceh-tag">' + esc(t('current', 'Current')) + '</span>' : '';
                        var del = isEmpty ? ''
                            : '<button type="button" class="wbceh-del" data-i="' + i + '" title="' + esc(t('delete', 'Delete this version')) + '">×</button>';
                        html +=
                            '<div class="wbceh-item' + (i === selected ? ' sel' : '') + '" data-i="' + i + '">'
                            + '<span class="wbceh-meta">' + tag
                            +   '<div class="wbceh-ts">' + esc(fmtTs(v.ts)) + '</div>'
                            +   '<div class="wbceh-who">' + esc(whoLine(v)) + '</div>'
                            + '</span>'
                            + '<span class="wbceh-check">' + (i === selected ? '✓' : '') + '</span>'
                            + del
                            + '</div>';
                    });
                    list.innerHTML = html;
                    if (restoreBtn) { restoreBtn.style.display = isEmpty ? 'none' : ''; }
                }

                function preview(i) {
                    if (i < 0) { i = 0; }
                    if (i >= display.length) { i = display.length - 1; }
                    selected = i;
                    editor.setContent(display[i] ? display[i].content : '', { no_events: true });
                    titleEl.textContent = display[i] ? fmtTs(display[i].ts) : '';
                    list.querySelectorAll('.wbceh-item').forEach(function (el) {
                        var on = parseInt(el.getAttribute('data-i'), 10) === i;
                        el.classList.toggle('sel', on);
                        var chk = el.querySelector('.wbceh-check');
                        if (chk) { chk.textContent = on ? '✓' : ''; }
                    });
                }

                // Delete one stored version (× on the row). Confirm first, then
                // POST; on success drop it locally and re-render in place.
                function doDelete(i) {
                    if (i < 0 || i >= versions.length) { return; }
                    if (!window.confirm(t('confirmDelete', 'Are you sure?'))) { return; }
                    var ver = versions[i];
                    post('delete', { index: i, ts: (ver && ver.ts) || '' }).then(function (r) {
                        if (!r || !r.ok || !r.removed) { return; }
                        versions.splice(i, 1);
                        if (selected > i) { selected--; }
                        renderList();
                        preview(selected);
                    });
                }

                list.addEventListener('click', function (e) {
                    var del = e.target.closest('.wbceh-del');
                    if (del) { e.stopPropagation(); doDelete(parseInt(del.getAttribute('data-i'), 10) || 0); return; }
                    var item = e.target.closest('.wbceh-item');
                    if (item) { preview(parseInt(item.getAttribute('data-i'), 10) || 0); }
                });

                top.querySelector('.wbceh-close').addEventListener('click', function () { closeView(true); });
                restoreBtn.addEventListener('click', function () {
                    var content = display[selected] ? display[selected].content : orig;
                    // Reset to the original first so the transact records a clean
                    // original→restored step (previews used no_events).
                    editor.setContent(orig, { no_events: true });
                    editor.mode.set('design');
                    editor.undoManager.transact(function () { editor.setContent(content); });
                    editor.setDirty(true);
                    closeView(false);
                    editor.notificationManager.open({ text: t('restored', 'Version restored.'), type: 'success', timeout: 3000 });
                });

                var onKey = function (e) { if (e.key === 'Escape') { closeView(true); } };
                document.addEventListener('keydown', onKey);
                window.addEventListener('resize', reposition);
                window.addEventListener('scroll', reposition, true);

                openState = { orig: orig, nodes: [root], onKey: onKey, header: header, editWrap: editWrap, reposition: reposition };
                renderList();
                reposition();
                preview(0);
            });
        }

        editor.ui.registry.addIcon('wb-history', '<svg width="24" height="24" viewBox="0 0 24 24"><path d="M13 3a9 9 0 1 0 8.94 10h-2.02A7 7 0 1 1 13 5a7 7 0 0 1 6.32 4H16v2h6V5h-2v2.35A9 9 0 0 0 13 3z"/><path d="M12.5 8v5l4 2.3.75-1.3-3.25-1.9V8z"/></svg>');

        editor.ui.registry.addButton('wbce_history', {
            icon: 'wb-history',
            tooltip: t('title', 'Version history'),
            onAction: openHistory
        });

        editor.ui.registry.addMenuItem('wbce_history', {
            icon: 'wb-history',
            text: t('title', 'Version history'),
            onAction: openHistory
        });

        return { getMetadata: function () { return { name: 'WBCE Version History' }; } };
    });
}());
