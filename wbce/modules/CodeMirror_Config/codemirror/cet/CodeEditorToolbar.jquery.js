/**
 * CodeEditorToolbar.jquery.js
 * jQuery plugin — CodeMirror-based code editor with toolbar.
 * Drop-in replacement for aceToolbarWrapper.jquery.js.
 *
 * Requires: jQuery, CodeMirror (loaded via registerCodeEditorToolbar() in PHP)
 * Globals set by PHP before this script runs:
 *   CmDir        — URL to the codemirror/ folder
 *   CmTheme      — initial theme name  ('wbce-day' | 'wbce-night')
 *   CmFontSize   — initial font size in px (integer)
 *   CEditorCfg   — localStorage snapshot (same key 'AceCfg' for backward compat)
 *
 * @author  Christian M. Stefan (original aceToolbarWrapper)
 * @version 2.0.0 (CodeMirror port)
 */

// CeToolbarDir: set by CodeEditor PHP class (CM_Config) or by AFE fallback.
// When loaded from CM_Config, PHP already inserted CSS+JS — self-load is skipped.
var CeToolbarDir = (typeof CeToolbarDir !== 'undefined')
    ? CeToolbarDir
    : WB_URL + '/modules/cwsoft-addon-file-editor/CodeEditorToolbar';

// Self-load only when NOT loaded by PHP (AFE standalone mode)
if (typeof CET_PHP_LOADED === 'undefined') {
    (function () {
        var head = document.head || document.getElementsByTagName('head')[0];
        var link = document.createElement('link');
        link.rel  = 'stylesheet';
        link.href = CeToolbarDir + '/CodeEditorToolbar.css';
        head.appendChild(link);
        ['/i18n.js', '/searchcursor.js', '/search.js'].forEach(function(f) {
            var s = document.createElement('script');
            s.src = CeToolbarDir + f;
            head.appendChild(s);
        });
    })();
}

// Translation helper (same as before)
var languageData = languageData || {};
function L_(key) {
    var lang = (typeof LANGUAGE !== 'undefined' ? LANGUAGE : 'en').toLowerCase();
    if (languageData[lang]) {
        return languageData[lang][key] || (languageData['en'] && languageData['en'][key]) || key;
    }
    return key;
}

// ── CodeMirror mode mapping ───────────────────────────────────────────────
function getCMMode(ext) {
    var map = {
        php:        'application/x-httpd-php',
        phtml:      'application/x-httpd-php',
        tpl:        'application/x-httpd-php',
        html:       'text/html',
        htm:        'text/html',
        htt:        'text/html',
        twig:       {name: 'twig', base: 'text/html'},
        css:        'text/css',
        scss:       'text/x-scss',
        js:         'text/javascript',
        json:       'application/json',
        xml:        'text/xml',
        svg:        'text/xml',
        sql:        'text/x-sql',
        ini:        'text/x-properties',
        properties: 'text/x-properties',
        md:         'text/x-markdown',
        txt:        'text/plain'
    };
    return map[ext] || 'text/plain';
}

// ── jQuery plugin ─────────────────────────────────────────────────────────
(function ($) {

    // ── Toolbar HTML templates ────────────────────────────────────────────
    var wrapperToolbarTpl = `
        <div class="toolbar">
            <ul class="button-group">
                <li title="${L_('Fullscreen mode')}">
                    <a class="grp-btn" data-ace-action="fullscreen" id="fullscreen_[instanceId]">
                        <i class="ico-fullscreen">&nbsp;</i>
                    </a>
                </li>
            </ul>
            <ul class="button-group">
                <li title="${L_('Toggle LineWrapping')}"><a class="grp-btn" data-ace-action="line-wrapping"><i class="ico-line-wrapping">&nbsp;</i></a></li>
                <li title="${L_('Toggle day/night mode')}"><a class="grp-btn" data-ace-action="toggle-day-night"><i class="ico-blackwhite">&nbsp;</i></a></li>
            </ul>
            <ul class="button-group">
                <li title="${L_('Increase Font Size')}"><a class="grp-btn" data-ace-action="fontsize-plus"><i class="ico-plus">&nbsp;</i></a></li>
                <li><a class="grp-btn readonly"><span data-ace-action="show-fontsize"></span>px</a></li>
                <li title="${L_('Decrease Font Size')}"><a class="grp-btn" data-ace-action="fontsize-minus"><i class="ico-minus">&nbsp;</i></a></li>
            </ul>
            <ul class="button-group">
                <li title="${L_('Search')}"><a class="grp-btn" data-ace-action="search"><i class="ico-search">&nbsp;</i></a></li>
            </ul>
            <div style="clear:both;"></div>
        </div>
    `;

    var wrapperFootTpl = `
        <div class="ace-footer">
            <section class="AceBar bottomBar">
                <div class="pos-right bottomBarCheckbox">
                    [AjaxSaveBtn]
                    <label title="${L_('Show/Hide Toolbar')}"><input type="checkbox" data-ace="show-toolbar">Toolbar</label>
                </div>
                <br style="clear:both;">
            </section>
        </div>
    `;

    // ── Helpers ───────────────────────────────────────────────────────────

    function updateCEditorCfg(property, value) {
        var cfg = JSON.parse(localStorage.getItem('AceCfg')) || {};
        cfg[property] = value;
        localStorage.setItem('AceCfg', JSON.stringify(cfg));
    }

    function toggleTheme(day, night, $btn, editor) {
        var cfg     = JSON.parse(localStorage.getItem('AceCfg')) || {};
        var current = cfg.theme || day;
        var next    = (current === day) ? night : day;

        editor.setOption('theme', next);
        updateCEditorCfg('theme', next);

        if (next === night) {
            $('.AceBar').addClass('darkMode');
            $btn.addClass('active');
        } else {
            $('.AceBar').removeClass('darkMode');
            $btn.removeClass('active');
        }
    }

    function updateFontSize(editor, action) {
        var cfg      = JSON.parse(localStorage.getItem('AceCfg')) || {};
        var fontSize = cfg.fontSize || (typeof CmFontSize !== 'undefined' ? CmFontSize : 15);

        if (action === 'fontsize-plus'  && fontSize < 24) fontSize++;
        if (action === 'fontsize-minus' && fontSize > 10) fontSize--;

        editor.getWrapperElement().style.fontSize = fontSize + 'px';
        editor.refresh();
        updateCEditorCfg('fontSize', fontSize);
        $('[data-ace-action="show-fontsize"]').text(fontSize);
    }

    function toggleLineWrapping(editor, e) {
        e && e.preventDefault();
        var wrap = !editor.getOption('lineWrapping');
        editor.setOption('lineWrapping', wrap);
        updateCEditorCfg('lineWrapping', wrap);
        $('a[data-ace-action="line-wrapping"]').toggleClass('active', wrap);
    }

    function toggleSearch(editor) {
        if (typeof CETSearch !== 'undefined') {
            CETSearch.toggle(editor);
        } else {
            editor.focus();
        }
    }

    function showFullscreenHint(msg) {
        var hint = $('<div class="fullscreen-hint">' + (msg || L_('Press [F11] to exit fullscreen')) + '</div>');
        $('body').append(hint);
        hint.delay(2000).fadeOut('slow', function () { $(this).remove(); });
    }

    function toggleFullscreen(editor, settings, instanceId, applySize) {
        var wrapper = (settings.single !== false)
            ? $('#' + instanceId).closest('form')
            : (function () {
                var wid = instanceId + '_wrapper';
                var w   = $('#' + wid);
                if (!w.length) {
                    w = $('<div>', {id: wid, 'class': 'editor-wrapper'}).insertBefore('#' + instanceId);
                    $('#' + instanceId).appendTo(w);
                }
                return w;
            })();

        wrapper.toggleClass('fullscreen');

        if (wrapper.hasClass('fullscreen')) {
            // Fullscreen: expand to full viewport width
            editor.setSize(window.innerWidth, null);
            $('i.ico-fullscreen').addClass('exit');
            if (settings.showFullscreenHint) showFullscreenHint();
        } else {
            // Restore: go back to initially measured width
            if (typeof applySize === 'function') applySize();
            $('i.ico-fullscreen').removeClass('exit');
        }
        editor.refresh();
    }

    function updateToolbarVisibility(editor, settings, isVisible, instanceId, toolbarHtml) {
        var bar = $('#' + instanceId + ' .aceWrapperHead');
        if (isVisible) {
            if (!bar.find('.toolbar').length) {
                bar.append(toolbarHtml);
                updateFontSize(editor, 'initial');
            }
        } else {
            bar.find('.toolbar').remove();
        }
        bar.toggleClass('active', isVisible);
        $('#' + instanceId + ' [data-ace="show-toolbar"]').prop('checked', isVisible);
        localStorage.setItem('toolbarVisible', isVisible);
    }

    function bindEventHandlers(editor, settings, instanceId, toolbarHtml, applySize) {

        // F11 key
        $(document).off('keydown.cet').on('keydown.cet', function (e) {
            if (e.key === 'F11') {
                e.preventDefault();
                toggleFullscreen(editor, settings, instanceId, applySize);
            }
        });

        // Fullscreen button
        $(document).off('click.cet-fs', '.aceWrapperHead a[data-ace-action="fullscreen"]')
            .on('click.cet-fs', '.aceWrapperHead a[data-ace-action="fullscreen"]', function (e) {
                e.preventDefault();
                var id = $(this).closest('.ce-container').attr('id');
                toggleFullscreen(editor, settings, id, applySize);
            });

        // Search
        $('#' + instanceId + ' .aceWrapperHead').on('click', 'a[data-ace-action="search"]', function () {
            toggleSearch(editor);
        });

        // Day/night
        $('#' + instanceId + ' .aceWrapperHead').on('click', 'a[data-ace-action="toggle-day-night"]', function (e) {
            e.preventDefault();
            toggleTheme('wbce-day', 'wbce-night', $(this), editor);
        });

        // Font size
        $('#' + instanceId + ' .aceWrapperHead').on('click',
            'a[data-ace-action="fontsize-plus"], a[data-ace-action="fontsize-minus"]',
            function (e) {
                e.preventDefault();
                updateFontSize(editor, $(this).data('ace-action'));
            }
        );

        // Line wrap
        $('#' + instanceId + ' .aceWrapperHead').off('click.lw', 'a[data-ace-action="line-wrapping"]')
            .on('click.lw', 'a[data-ace-action="line-wrapping"]', function (e) {
                toggleLineWrapping(editor, e);
            });

        // Toolbar show/hide checkbox
        $(document).on('change', '#' + instanceId + ' input[data-ace="show-toolbar"]', function () {
            updateToolbarVisibility(editor, settings, this.checked, instanceId, toolbarHtml);
            bindEventHandlers(editor, settings, instanceId, toolbarHtml, _applySize);
        });

        // Initial button states
        var cfg = JSON.parse(localStorage.getItem('AceCfg')) || {};
        var theme = cfg.theme || (typeof CmTheme !== 'undefined' ? CmTheme : 'wbce-day');
        $('#' + instanceId + " a[data-ace-action='toggle-day-night']").toggleClass('active', theme === 'wbce-day');
        $('#' + instanceId + " a[data-ace-action='line-wrapping']").toggleClass('active', !!cfg.lineWrapping);
    }

    // ── Plugin definition ─────────────────────────────────────────────────
    $.fn.codeEditorToolbar = function (options) {

        var defaults = {
            initialTheme:           'wbce-day',
            initialFontSize:        15,
            initialHeight:          '500px',
            autoGrow:               false,
            showPrintMargin:        false,
            showFullscreenHint:     false,
            toolbarVisible:         true,
            ajaxSaveCheckbox:       false,
            ajaxUrl:                '',
            ajaxData:               {},
            onSave:                 null,
            single:                 true,
            instanceId:             'ce-instance-0'
        };

        var settings = $.extend({}, defaults, options);

        return this.each(function () {
            var textarea   = $(this);
            // data-cm-mode: exact MIME string set by CodeEditor PHP class
            var cmModeRaw  = textarea.attr('data-cm-mode') || null;
            var mode       = textarea.data('editor') || 'txt';
            var instanceId = settings.instanceId || ('ce-' + mode + '-' + Date.now());
            settings.instanceId = instanceId;

            // Read persisted config (backward-compat key 'AceCfg')
            var cfg = JSON.parse(localStorage.getItem('AceCfg')) || {};

            var initialTheme    = cfg.theme        || (typeof CmTheme    !== 'undefined' ? CmTheme    : settings.initialTheme);
            var initialFontSize = cfg.fontSize      || (typeof CmFontSize !== 'undefined' ? CmFontSize : settings.initialFontSize);
            var initialWrap     = cfg.lineWrapping  || false;
            var initialHeight   = settings.initialHeight;

            // ── Measure available width BEFORE any DOM changes ──────────────
            // Must happen before wrap() and before CM init — both can expand
            // the container and corrupt any measurement taken afterwards.
            var _measuredWidth = (function() {
                var el = textarea[0].parentElement;
                while (el && el !== document.body) {
                    var r = el.getBoundingClientRect();
                    if (r.width > 20) return Math.floor(r.width);
                    el = el.parentElement;
                }
                return Math.floor(window.innerWidth * 0.85);
            })();

            // ── Measure available width BEFORE any DOM changes ───────────────
            // Must happen before wrap() and before CM init — both can expand the
            // container and corrupt any measurement taken afterwards.
            var _measuredWidth = (function() {
                var el = textarea[0].parentElement;
                while (el && el !== document.body) {
                    var r = el.getBoundingClientRect();
                    if (r.width > 20) return Math.floor(r.width);
                    el = el.parentElement;
                }
                return Math.floor(window.innerWidth * 0.85);
            })();

            // ── DOM: wrap textarea in container ───────────────────────────
            textarea.wrap('<div class="ce-container" id="' + instanceId + '"></div>');
            var container = $('#' + instanceId);

            // Toolbar header placeholder
            container.prepend('<section class="AceBar aceWrapperHead"></section>');

            // Resolve toolbar template (replace instanceId placeholder)
            var toolbarHtml = wrapperToolbarTpl.replace(/\[instanceId\]/g, instanceId);

            // ── Init CodeMirror ───────────────────────────────────────────
            var editor = CodeMirror.fromTextArea(textarea[0], {
                mode:            cmModeRaw || getCMMode(mode),
                theme:           initialTheme,
                lineNumbers:     true,
                lineWrapping:    initialWrap,
                foldGutter:      true,
                gutters:         ['CodeMirror-linenumbers', 'CodeMirror-foldgutter'],
                matchBrackets:   true,
                styleActiveLine: true,
                extraKeys: {
                    'Ctrl-S': function (cm) {
                        cm.save();
                        textarea.closest('form').trigger('submit');
                    },
                    'Cmd-S': function (cm) {
                        cm.save();
                        textarea.closest('form').trigger('submit');
                    },
                    'Ctrl-F': function (cm) {
                        if (typeof CETSearch !== 'undefined') CETSearch.open(cm);
                    },
                    'Cmd-F': function (cm) {
                        if (typeof CETSearch !== 'undefined') CETSearch.open(cm);
                    }
                }
            });

            // ── Width constraint ──────────────────────────────────────────────
            // CM with lineWrapping=false can expand the page because
            // percentage widths don't help if a parent container has width:auto.
            // Solution: measure the nearest fixed-width ancestor in pixels,
            // set CM to that value, and update on window resize.
            function _applySize(w) {
                w = w || _measuredWidth;
                if (settings.autoGrow) {
                    editor.setSize(w, 'auto');
                } else {
                    editor.setSize(w, initialHeight);
                }
                editor.refresh();
            }

            if (settings.autoGrow) {
                editor.getWrapperElement().style.minHeight = '80px';
                editor.on('change', function() { _applySize(); });
            }
            _applySize();
            // Re-apply on window resize (e.g. sidebar toggle, responsive)
            $(window).on('resize.cet-' + instanceId, function() {
                // On resize, measure fresh — layout is settled at this point
                var el = container[0].parentElement;
                var rw = _measuredWidth;
                while (el && el !== document.body) {
                    var r = el.getBoundingClientRect();
                    if (r.width > 20) { rw = Math.floor(r.width); break; }
                    el = el.parentElement;
                }
                _applySize(rw);
            });

            editor.getWrapperElement().style.fontSize = initialFontSize + 'px';
            editor.refresh();

            // Apply dark mode class if needed
            if (initialTheme === 'wbce-night') {
                $('.AceBar').addClass('darkMode');
            }

            // ── Footer ────────────────────────────────────────────────────
            var footHtml = wrapperFootTpl;
            if (settings.ajaxSaveCheckbox && settings.single) {
                var ajaxBtn = '<label title="' + L_('Enable/Disable Ajax Save') +
                    '"><input type="checkbox" data-ace="ajax-save">AjaxSave</label>';
                footHtml = footHtml.replace('[AjaxSaveBtn]', ajaxBtn);
            } else {
                footHtml = footHtml.replace('[AjaxSaveBtn]', '');
            }
            // Wire AJAX save when URL is provided
            if (settings.ajaxUrl) {
                $(document).on('change', '#' + instanceId + ' [data-ace="ajax-save"]', function() {
                    var $cb = $(this);
                    if (!$cb.is(':checked')) return;
                    editor.save();
                    var postData = $.extend({}, settings.ajaxData || {},
                        { code_area_text: textarea.val() });
                    var $hint = $('<div class="fullscreen-hint">&#8987;</div>').appendTo('body');
                    $.post(settings.ajaxUrl, postData)
                        .done(function(r) {
                            $hint.addClass('success').text('\u2713 Saved').delay(1500).fadeOut(function(){ $(this).remove(); });
                            if (typeof settings.onSave === 'string' && window[settings.onSave]) window[settings.onSave](r);
                            else if (typeof settings.onSave === 'function') settings.onSave(r);
                        })
                        .fail(function() {
                            $hint.addClass('error').text('\u2717 Save failed').delay(2000).fadeOut(function(){ $(this).remove(); });
                        });
                    $cb.prop('checked', false);
                });
            }
            $(editor.getWrapperElement()).after(footHtml);

            // ── Sync CM → textarea on form submit ─────────────────────────
            textarea.closest('form').on('submit', function () {
                editor.save(); // copies CM value back to the hidden textarea
            });

            // ── Toolbar visibility ────────────────────────────────────────
            var toolbarVisible = cfg.toolbarVisible !== undefined
                ? cfg.toolbarVisible
                : (localStorage.getItem('toolbarVisible') === 'true' || settings.toolbarVisible);

            updateToolbarVisibility(editor, settings, toolbarVisible, instanceId, toolbarHtml);
            bindEventHandlers(editor, settings, instanceId, toolbarHtml, _applySize);

            // Show fontsize counter
            updateFontSize(editor, 'initial');

            // Store editor on textarea element for external access
            textarea.data('cm', editor);

            // Cleanup resize listener when editor is removed from DOM
            textarea.on('remove', function() {
                $(window).off('resize.cet-' + instanceId);
            });
        });
    };

    // Backward-compat alias
    $.fn.aceToolbarWrapper = $.fn.codeEditorToolbar;

})(jQuery);
