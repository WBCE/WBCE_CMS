/**
 * wysiwyg/ajax_save.js
 *
 * AjaxSave for the WBCE wysiwyg module.
 * Loaded once via I::insertJsFile('BODY BTM-') from modify.php.
 * jQuery and all WYSIWYG editor scripts are already present at this point.
 *
 * Finds every <form data-ajax-url[id^="wysiwyg_form_"]> on the page and wires:
 *   - AjaxSave checkbox  → toggles mode, persists to localStorage
 *   - Save & Back button → hidden while AjaxSave is active
 *   - Save button click  → AJAX save (form submit intercepted)
 *   - Ctrl+S (outer doc) → AJAX save when checkbox is checked
 *   - Ctrl+S in CKEditor → AJAX save via editor.addCommand / setKeystroke
 *   - Ctrl+S in TinyMCE  → AJAX save via editor.addShortcut
 *
 * Toast feedback via HX-Trigger response header (Alerts::toast() in PHP).
 * The same header HTMX reads — bridged here to plain jQuery AJAX.
 * Falls back to window.showToast if available, then to a minimal inline toast.
 */
(function ($) {
    'use strict';

    // ── Toast: read HX-Trigger header set by Alerts::toast() in PHP ─────────
    // window.showToast() is guaranteed by Alerts::ensureToastAssets() in modify.php
    function fireToast(xhr) {
        var raw = xhr && xhr.getResponseHeader('HX-Trigger');
        if (!raw) return;
        var ev;
        try { ev = JSON.parse(raw); } catch (e) { return; }
        var t = ev.showToast;
        if (!t || typeof window.showToast !== 'function') return;
        window.showToast(t.message, t.type, t.duration, t.icon || '');
    }

    // ── Editor-agnostic content sync ──────────────────────────────────────────
    function syncEditor(editorId) {
        // CKEditor 4
        if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances[editorId]) {
            CKEDITOR.instances[editorId].updateElement();
        }
        // TinyMCE 4 / 5 / 6
        if (typeof tinymce !== 'undefined') {
            var ed = tinymce.get(editorId);
            if (ed) {
                try { ed.save(); } catch (e) {}
            } else {
                try { tinymce.triggerSave(); } catch (e) {}
            }
        }
        // Plain textarea / editarea: value is always current — nothing needed
    }

    // ── Per-section initialisation ────────────────────────────────────────────
    function initSection($form) {
        var sid      = $form.find('input[name="section_id"]').val();
        var ajaxUrl  = $form.data('ajax-url');
        var editorId = 'content' + sid;
        var lsKey    = 'wysiwyg_ajaxSave_' + sid;

        var $cb       = $('#wysiwyg_ajaxsave_' + sid);
        var $saveBack = $('#wysiwyg_saveback_'  + sid);

        if (!$cb.length) return;

        // ── Restore persisted checkbox state ──────────────────────────────────
        var enabled = localStorage.getItem(lsKey) === 'true';
        $cb.prop('checked', enabled);
        $saveBack.toggle(!enabled);

        // ── Checkbox: toggle mode, persist, show/hide Save & Back ─────────────
        $cb.on('change', function () {
            enabled = this.checked;
            localStorage.setItem(lsKey, enabled ? 'true' : 'false');
            $saveBack.toggle(!enabled);
        });

        // ── AJAX save ─────────────────────────────────────────────────────────
        function doAjaxSave() {
            syncEditor(editorId);
            $.post(ajaxUrl, $form.serialize())
                .done(function (r, status, xhr) { fireToast(xhr); })
                .fail(function (xhr)            { fireToast(xhr); });
        }

        // ── Intercept form submit (Save button) ───────────────────────────────
        $form.on('submit', function (e) {
            if (!enabled) return;   // checkbox off → normal submit
            e.preventDefault();
            doAjaxSave();
        });

        // ── Ctrl+S on the outer document (focus outside any iframe) ───────────
        $(document).on('keydown.wysiwyg_' + sid, function (e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 's' && enabled) {
                e.preventDefault();
                doAjaxSave();
            }
        });

        // ── Ctrl+S inside a CKEditor iframe ───────────────────────────────────
        function addCkShortcut(editor) {
            var cmd = 'wbceAjaxSave_' + sid;
            if (editor.commands[cmd]) return;
            editor.addCommand(cmd, {
                exec: function () { if (enabled) doAjaxSave(); }
            });
            editor.setKeystroke(CKEDITOR.CTRL + 83 /* S */, cmd);
        }

        if (typeof CKEDITOR !== 'undefined') {
            var ckEd = CKEDITOR.instances[editorId];
            if (ckEd) {
                if (ckEd.status === 'ready') {
                    addCkShortcut(ckEd);
                } else {
                    ckEd.once('instanceReady', function () { addCkShortcut(ckEd); });
                }
            } else {
                CKEDITOR.on('instanceReady', function (evt) {
                    if (evt.editor.name === editorId) {
                        addCkShortcut(evt.editor);
                    }
                });
            }
        }

        // ── Ctrl+S inside a TinyMCE iframe ────────────────────────────────────
        function addTmceShortcut(editor) {
            editor.addShortcut('ctrl+s', '', function () {
                if (enabled) doAjaxSave();
                return false;
            });
        }

        if (typeof tinymce !== 'undefined') {
            var tmEd = tinymce.get(editorId);
            if (tmEd) {
                addTmceShortcut(tmEd);
            } else {
                tinymce.on('AddEditor', function (evt) {
                    if (evt.editor.id === editorId) {
                        evt.editor.on('init', function () { addTmceShortcut(evt.editor); });
                    }
                });
            }
        }
    }

    // ── Bootstrap: one pass over all wysiwyg forms on the page ───────────────
    $('form[data-ajax-url][id^="wysiwyg_form_"]').each(function () {
        initSection($(this));
    });

})(jQuery);
