/**
 *
 * @category        tool
 * @package         Outputfilter Dashboard
 * @version         1.6.3
 * @authors         Thomas "thorn" Hornik <thorn@nettest.thekk.de>, Christian M. Stefan (Stefek) <stefek@designthings.de>, Martin Hecht (mrbaseman) <mrbaseman@gmx.de>
 * @copyright       (c) 2009,2010 Thomas "thorn" Hornik, 2010-2023 Christian M. Stefan (Stefek), 2016-2023 Martin Hecht (mrbaseman)
 * @link            https://github.com/mrbaseman/outputfilter_dashboard
 * @link            https://addons.wbce.org/pages/addons.php?do=item&item=53
 * @link            https://forum.wbce.org/viewtopic.php?id=176
 * @license         GNU General Public License, Version 3
 * @platform        WBCE 1.x
 * @requirements    PHP 7.4 - 8.2
 *
 * This file is part of OutputFilter-Dashboard, a module for WBCE and Website Baker CMS.
 *
 * OutputFilter-Dashboard is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OutputFilter-Dashboard is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OutputFilter-Dashboard. If not, see <http://www.gnu.org/licenses/>.
 *
 **/


var MODULE_URL = ADMIN_URL + '/admintools/tool.php?tool=outputfilter_dashboard';
var ICONS = IMAGE_URL;
var AJAX_PLUGINS =  WB_URL + '/modules/outputfilter_dashboard/ajax';

// Truncates each .short-description's text at a fixed character count and
// appends a "show more/less" toggle -- vanilla replacement for the
// jquery.collapser.min.js plugin this used to dynamically load (same
// mode:'chars', truncate:130, ellipsis/show/hide-text as before). Only the
// filter list's description column (tool_dashboard.twig) uses this class,
// and its content is always plain text, so a straight textContent slice is
// enough -- no HTML-in-description case to preserve.
function opfInitShortDescriptions(root) {
    var TRUNCATE = 130;
    root.querySelectorAll('.short-description').forEach(function(el) {
        var full = el.textContent;
        if (full.length <= TRUNCATE) return;
        var short = full.slice(0, TRUNCATE) + '… ';
        el.textContent = short;
        var toggle = document.createElement('a');
        toggle.href = 'javascript:void(0)';
        toggle.setAttribute('data-ctrl', '');
        toggle.textContent = '▼';
        toggle.addEventListener('click', function() {
            var expanded = el.classList.toggle('expanded');
            el.textContent = expanded ? full + ' ' : short;
            el.appendChild(toggle);
            toggle.textContent = expanded ? '▲' : '▼';
        });
        el.appendChild(toggle);
    });
}

document.addEventListener('DOMContentLoaded', function ()
{
    // upload panel show/close is handled by inline vanilla JS in tool_dashboard.twig
    // export/upload/save feedback uses Alerts::sessionToast() (server-side)
    // instead of the old jquery.dialog.js popup; filter conversion uses the
    // inline row-confirm pattern (.convert-item, see ajax.js) instead too.

    document.querySelectorAll('[data-redirect-location]').forEach(function(el) {
        el.addEventListener('click', function () {
            var uri = el.dataset.redirectLocation;
            if (el.dataset.newWindow) {
                window.open(uri, '_blank');
            } else {
                window.location = uri;
            }
        });
    });

    // scroll to Last Modified Filter
    // also: scroll to Type Section after deleting a plugin
    var lastModified = document.querySelector('.last-modified');
    if (lastModified) {
        var y = lastModified.getBoundingClientRect().top + window.pageYOffset - 250;
        window.scrollTo({ top: y, behavior: 'smooth' });
    }

    opfInitShortDescriptions(document);
});

// --| drag&drop

$(function() {
    // Load external ajax_dragdrop file
    $.insert(AJAX_PLUGINS +"/ajax.js");
});

// Checktree (module/page target picker) -- vanilla JS, event delegation.
// Markup convention (rendered server-side by opf_checktree_node() in
// functions.php): each <li> has a .node-row containing either a
// .node-toggle (branches) or a .node-spacer (leaves, keeps checkboxes
// aligned), then the real <input type=checkbox>, then a .node-label.
// Children live in a sibling <ul class="node-children">. No data-node/
// data-parent bookkeeping needed -- state is derived purely from DOM
// nesting, same as the old jQuery plugin did, just without hiding the
// real inputs behind synthetic image-based ones.
//
// A branch's own checked state is never auto-derived from its descendants
// -- it only ever changes via a direct click on that exact checkbox, which
// then cascades down to every descendant (cascadeCheck()). Descendant
// checkboxes are free to be toggled individually without that silently
// flipping an ancestor's own checked/submitted state: recomputeNode() only
// ever sets the native `indeterminate` (dash) visual, shown whenever a
// branch's descendants don't unanimously agree with the branch's own
// value -- e.g. every child checked but the branch itself deliberately
// left off shows the dash, not a silently auto-checked branch (which would
// wrongly add the branch's own page to the selection). Pages that have
// sub-pages render as two separate rows, same as the original
// mechanism -- "<title> (einzelne Seite)" (a plain leaf, no cascade) and
// "<title> (Seitenhierarchie)" (a plain branch, cascades to its children)
// -- see opf_build_tree_page_hierarchy() in functions.php. An earlier
// version of this control merged both into one three-state row per page;
// that added enough save-path complexity for not enough payoff, so it was
// reverted in favor of this simpler, plain-checkbox-only tree.
function opfInitCheckTree(root) {
    function directChildLis(li) {
        var ul = li.querySelector(':scope > .node-children');
        return ul ? Array.prototype.filter.call(ul.children, function(c) { return c.tagName === 'LI'; }) : [];
    }
    function ownCheckbox(li) {
        return li.querySelector(':scope > .node-row > input[type=checkbox]');
    }
    function parentLi(li) {
        var ul = li.parentElement;
        if (!ul || !ul.classList.contains('node-children')) return null;
        return ul.closest('li');
    }
    function recomputeNode(li) {
        var kids = directChildLis(li);
        if (kids.length === 0) return;
        var allChecked = true, anyChecked = false;
        kids.forEach(function(kidLi) {
            var kidCb = ownCheckbox(kidLi);
            if (!kidCb) return;
            if (kidCb.indeterminate) { allChecked = false; anyChecked = true; }
            else if (kidCb.checked) { anyChecked = true; }
            else { allChecked = false; }
        });
        var cb = ownCheckbox(li);
        if (!cb) return;
        // cb.checked is intentionally never assigned here -- see the big
        // comment above opfInitCheckTree(). Only the dash visual is derived
        // from descendant state, relative to this checkbox's own value.
        cb.indeterminate = cb.checked ? !allChecked : anyChecked;
    }
    function recomputeAncestors(li) {
        var p = parentLi(li);
        while (p) {
            recomputeNode(p);
            p = parentLi(p);
        }
    }
    function cascadeCheck(li, checked) {
        directChildLis(li).forEach(function(childLi) {
            var kidCb = ownCheckbox(childLi);
            if (!kidCb) return;
            kidCb.checked = checked;
            kidCb.indeterminate = false;
            cascadeCheck(childLi, checked);
        });
    }
    function toggleNode(toggle) {
        var li = toggle.closest('li');
        var kids = li.querySelector(':scope > .node-children');
        if (!kids) return;
        var expanded = toggle.dataset.expanded === 'true';
        toggle.dataset.expanded = expanded ? 'false' : 'true';
        toggle.setAttribute('aria-label', expanded ? 'Ausklappen' : 'Einklappen');
        li.setAttribute('aria-expanded', expanded ? 'false' : 'true');
        kids.style.display = expanded ? 'none' : '';
        var icon = toggle.querySelector('.fa');
        if (icon) icon.className = 'fa fa-chevron-' + (expanded ? 'right' : 'down');
    }

    root.querySelectorAll('ul.node-tree').forEach(function(tree) {
        tree.addEventListener('change', function(e) {
            var cb = e.target;
            if (cb.type !== 'checkbox') return;
            var li = cb.closest('li');
            cascadeCheck(li, cb.checked);
            cb.indeterminate = false;
            recomputeAncestors(li);
        });

        tree.addEventListener('click', function(e) {
            var toggle = e.target.closest('.node-toggle');
            if (toggle) toggleNode(toggle);
        });

        tree.addEventListener('keydown', function(e) {
            if (e.key !== 'Enter' && e.key !== ' ') return;
            var toggle = e.target.closest('.node-toggle');
            if (toggle) { e.preventDefault(); toggleNode(toggle); }
        });

        // Server-rendered `checked` attributes are the source of truth for
        // every checkbox and are never touched here; this pass only sets
        // the initial indeterminate dash on branches bottom-up.
        Array.prototype.slice.call(tree.querySelectorAll('li')).reverse().forEach(recomputeNode);
    });
}



// jQuery custom checkboxes plugin ($.fn.checkbox, Khavilo Dmitry) removed here --
// its only call site was `$("input[class=activity]").checkbox(...)`, further
// down in this file (also removed), and no template anywhere outputs an
// <input class="activity">, so the plugin never actually ran. Took
// activity.png/empty.gif/empty.png (assets/images/) with it.

// Auto-grows a textarea to fit its content as the admin types -- vanilla
// replacement for the ~280-line jQuery Growfield Library 2 plugin this used
// to load, which was only ever invoked here with default options (no min/
// max/animate configured anywhere), so a plain resize-on-input is equivalent
// to what was actually in use.
function opfAutoGrowTextarea(el) {
    if (!el) return;
    function resize() {
        el.style.height = 'auto';
        el.style.height = el.scrollHeight + 'px';
    }
    el.style.overflow = 'hidden';
    el.addEventListener('input', resize);
    resize();
}

/*
    local functions
*/


/* activate CheckTree */
if(typeof opf_use_checktrees!='undefined') {
    opfInitCheckTree(document);

    //modules_checktree_visibility();

}



function modules_checktree_visibility() {
    i = document.outputfilter.type.selectedIndex;
    if(document.outputfilter.type.options[i].value == '6page_first' || document.outputfilter.type.options[i].value == '7page' || document.outputfilter.type.options[i].value == '8page_last' || document.outputfilter.type.options[i].value == '9page_final') {
        document.getElementById('OPF_ID_CHECKTREE').style.display = 'none';
        document.getElementById('OPF_ID_SEC_DESC').style.display = 'none';
        document.getElementById('OPF_ID_SEC_DESC_2').style.display = 'none';
        document.getElementById('OPF_ID_PAGE_DESC').style.display = '';
        document.getElementById('OPF_ID_PAGE_DESC_2').style.display = '';
    } else {
        document.getElementById('OPF_ID_CHECKTREE').style.display = '';
        document.getElementById('OPF_ID_SEC_DESC').style.display = '';
        document.getElementById('OPF_ID_SEC_DESC_2').style.display = '';
        document.getElementById('OPF_ID_PAGE_DESC').style.display = 'none';
        document.getElementById('OPF_ID_PAGE_DESC_2').style.display = 'none';
    }
}



/* activate auto-grow textareas */
opfAutoGrowTextarea(document.getElementById('desc'));
if(typeof opf_growfield_list!='undefined') {
    for(var i in opf_growfield_list) {
        opfAutoGrowTextarea(document.getElementById(opf_growfield_list[i]));
    }
}


/* popup-window */
function opf_popup(url) {
 w = window.open(
         url, 
        "OutputFilter-Dashboard Documentation", 
        "width=980,height=650,resizable=yes,scrollbars=yes,titlebar=no,toolbar=no,location=no,status=no,menubar=no"
    );
 w.focus();
 return false;
}


if(typeof document.outputfilter!='undefined') {
    if(typeof document.outputfilter.type!='undefined') {
        // display text-block for page-type or section-type, and module-checktree
        modules_checktree_visibility();
        // display page-checktree
        document.getElementById('OPF_ID_PAGECHECKTREE').style.display = '';
    }
}
