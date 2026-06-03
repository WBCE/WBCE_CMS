/**
 * JsAdmin — Drag & Drop (jQuery UI Sortable)
 *
 * Replaces the YUI 2.x drag-drop implementation (2006).
 * Requires: jQuery + jQuery UI (sortable widget).
 *
 * Handles:
 *   admin/pages/index.php  — nested page tree, each level independently sortable
 *   admin/pages/sections.php — flat section list, single sortable table
 */
(function ($) {
    'use strict';

    // ── Drop-flash ────────────────────────────────────────────────────────────
    // Inline-style approach: no CSS animation, no fill-mode, no class state.
    // Sets background via inline style, transitions to transparent, then removes
    // all inline styles — element is guaranteed clean afterwards.
    function flashItems($tds) {
        $tds.css({ transition: 'none', 'background-color': '#E8FFD9' });
        // Two rAF frames ensure the green is painted before the transition starts.
        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                $tds.css({ transition: 'background-color 2s linear', 'background-color': 'rgba(232,255,217,0)' });
                var cleaned = false;
                function cleanup() {
                    if (cleaned) return;
                    cleaned = true;
                    $tds.css({ 'background-color': '', transition: '' });
                }
                $tds.first().one('transitionend webkitTransitionEnd', cleanup);
                setTimeout(cleanup, 2300); // safety net
            });
        });
    }

    // ── Status indicator (reuses existing jsadmin body classes) ──────────────
    function setStatus(state) {
        $('body')
            .removeClass('jsadmin_busy jsadmin_success jsadmin_failure')
            .addClass('jsadmin_' + state);
    }

    // ── Pages ─────────────────────────────────────────────────────────────────

    /**
     * After a drag-drop reorder, fix up/down arrow visibility for all siblings
     * in the affected <ul>.  Titles are read from existing DOM links so they
     * stay localised without hardcoding strings here.
     */
    function updateSiblingArrows($ul) {
        var $items    = $ul.children('li[id^="pageID_"]');
        var count     = $items.length;

        // Read title text + icon class from the first existing arrow anywhere on
        // the page — works for any theme without hardcoding strings or class names.
        var SEL_UP   = 'td.btnup, td.page-move-up';
        var SEL_DOWN = 'td.btndown, td.page-move-down';
        var $anyUp   = $(SEL_UP   + ' a').first();
        var $anyDown = $(SEL_DOWN + ' a').first();
        var titleUp   = $anyUp.attr('title')              || '';
        var titleDown = $anyDown.attr('title')            || '';
        var iconUp    = $anyUp.find('i').attr('class')    || 'fa fa-chevron-circle-up';
        var iconDown  = $anyDown.find('i').attr('class')  || 'fa fa-chevron-circle-down';

        $items.each(function (i) {
            var pageId  = $(this).data('page-id');
            var $tdUp   = $(this).find('> table').find(SEL_UP);
            var $tdDown = $(this).find('> table').find(SEL_DOWN);

            // ── Up arrow ──────────────────────────────────────────────────────
            if (i === 0) {
                $tdUp.empty();
            } else if (!$tdUp.find('a').length) {
                $tdUp.html(
                    '<a href="' + JsAdmin.ADMIN_URL + '/pages/move_up.php?page_id=' + pageId + '"' +
                    (titleUp ? ' title="' + titleUp + '"' : '') + '>' +
                    '<i class="' + iconUp + '" aria-hidden="true"></i></a>'
                );
            }

            // ── Down arrow ────────────────────────────────────────────────────
            if (i === count - 1) {
                $tdDown.empty();
            } else if (!$tdDown.find('a').length) {
                $tdDown.html(
                    '<a href="' + JsAdmin.ADMIN_URL + '/pages/move_down.php?page_id=' + pageId + '"' +
                    (titleDown ? ' title="' + titleDown + '"' : '') + '>' +
                    '<i class="' + iconDown + '" aria-hidden="true"></i></a>'
                );
            }
        });
    }


    /**
     * The PHP renderer places child <ul id="pN"> as siblings of <li>, not
     * children.  Move each such list into its preceding <li> so jQuery UI
     * sortable can reason about the tree correctly.
     *
     * Before:  <li>…</li>  <ul id="p2">…</ul>
     * After:   <li>…  <ul id="p2">…</ul>  </li>
     */
    function fixTreeStructure() {
        // Work bottom-up: deepest lists first so parents are moved intact.
        var $lists = $('ul[id^="p"]').get().reverse();
        $($lists).each(function () {
            var $ul   = $(this);
            var $prev = $ul.prev('li');
            if ($prev.length) {
                $ul.appendTo($prev);
            }
        });
    }

    function initPagesSortable() {
        // Argos theme renders child <ul> as siblings of <li> — move them inside.
        // Flat theme already nests correctly, so this is a no-op there.
        fixTreeStructure();

        $('ul[id^="p"]').each(function () {
            var $ul = $(this);

            // Only enable when there are at least two addressable items.
            if ($ul.children('li[id^="pageID_"]').length < 2) return;

            $ul.sortable({
                items:                '> li[id^="pageID_"]',
                opacity:              0.75,
                cursor:               'move',
                placeholder:          'jsadmin-sort-placeholder',
                forcePlaceholderSize: true,
                tolerance:            'pointer',
                revert:               150,
                start: function (e, ui) {
                    ui.placeholder.height(ui.item.outerHeight());
                },
                stop: function (e, ui) {
                    // Only flash the direct table cells of the moved row,
                    // not nested child pages.
                    flashItems(ui.item.children('table').find('td'));
                },
                update: function () {
                    var $ul     = $(this);
                    var pageIDs = [];
                    $ul.children('li[id^="pageID_"]').each(function () {
                        pageIDs.push(this.id.replace('pageID_', ''));
                    });
                    updateSiblingArrows($ul);
                    postPageOrder(pageIDs);
                }
            });
        });
    }

    function postPageOrder(pageIDs) {
        setStatus('busy');
        $.ajax({
            type:     'POST',
            url:      JsAdmin.WB_URL + '/admin/pages/ajax/ajax_dragdrop.php',
            data:     { action: 'updateArray', pageID: pageIDs },
            dataType: 'json',
            success:  function (res) { setStatus(res.success ? 'success' : 'failure'); },
            error:    function ()    { setStatus('failure'); }
        });
    }

    // ── Sections ──────────────────────────────────────────────────────────────

function initSectionsSortable() {
    $('.not-when-dd').hide();

    var $table = $('.sections-dnd').first();
    if (!$table.length) return;

    var $tbody = $table.find('tbody');
    if ($tbody.find('tr.sectionrow').length < 2) return;

    var pageId = $tbody.find('tr.sectionrow input[name="page_id"]').first().val();
    if (!pageId) return;

    var colWidths = [];

    function cacheColumnWidths() {
        colWidths = [];
        $tbody.find('tr.sectionrow:first td').each(function () {
            colWidths.push($(this).outerWidth());
        });
    }
    cacheColumnWidths();

    // Drag Handle
    $tbody.find('tr.sectionrow td:last-child').append(
        '<span class="jsadmin-drag-handle" style="cursor:move;color:#aaa;padding:0 6px;font-size:1.2em;">⋮⋮</span>'
    );

    $tbody.sortable({
        items:                'tr.sectionrow',
        handle:               '.jsadmin-drag-handle',
        appendTo:             'body',
        forcePlaceholderSize: false,
        opacity:              0.9,
        revert:               120,
        tolerance:            'pointer',

        helper: function (e, tr) {
            var $helper = tr.clone();

            $helper.children('td').each(function (i) {
                $(this).width(colWidths[i]);
            });

            $helper.addClass('sections-drag-helper');

            return $helper;
        },

        // Allow ±50 px of horizontal wiggle room around the table's left edge.
        sort: function (e, ui) {
            var tableLeft = $table.offset().left;
            var left      = ui.helper.offset().left;
            var clamped   = Math.max(tableLeft - 50, Math.min(tableLeft + 50, left));
            ui.helper.css('left', clamped + 'px');
        },

        start: function (e, ui) {
            // Inject a spanning <td> so the placeholder <tr> actually
            // renders its background colour (browsers ignore background
            // on a bare <tr> in a table).
            var colspan = ui.item.children('td').length;
            ui.placeholder.html('<td colspan="' + colspan + '"></td>');
            ui.placeholder.height(8);

            $table.css({
                'table-layout': 'fixed',
                'width': $table.outerWidth() + 'px'
            });

            $table.find('thead tr, tbody tr').each(function () {
                $(this).children('td').each(function (i) {
                    if (colWidths[i] !== undefined) {
                        $(this).css({
                            'width': colWidths[i] + 'px',
                            'max-width': colWidths[i] + 'px',
                            'box-sizing': 'border-box'
                        });
                    }
                });
            });
        },

        stop: function (e, ui) {
            $table.css({ 'table-layout': '', 'width': '' });
            $table.find('td').css({ 'width': '', 'max-width': '' });

            // Flash only the direct cells of the dropped row.
            flashItems(ui.item.children('td'));
        },

        update: function () {
            setStatus('busy');
            $.ajax({
                type:     'POST',
                url:      JsAdmin.WB_URL + '/admin/pages/ajax/ajax_dragdrop_sections.php',
                data:     $tbody.sortable('serialize') + '&action=updateArray&page_id=' + pageId,
                dataType: 'json',
                success:  function (res) { setStatus(res.success ? 'success' : 'failure'); },
                error:    function ()    { setStatus('failure'); }
            });
        }
    });
}

    // ── Public entry point ────────────────────────────────────────────────────

    var _initialized = false;

    function init() {
        if (_initialized) return;
        _initialized = true;

        var path = window.location.pathname;

        if (path.indexOf('/pages/index.php') > -1) {
            initPagesSortable();
        } else if (path.indexOf('/pages/sections.php') > -1) {
            initSectionsSortable();
        }
    }

    // Called by JsAdmin.loadHandler if available.
    JsAdmin.init_drag_drop = init;

    // Self-contained fallback: initialize on DOM-ready regardless of loadHandler.
    jQuery(function () { init(); });

}(jQuery));
