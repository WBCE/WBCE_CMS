/**
 * @file       backend_body.js
 * @category   admintool
 * @package    addon_monitor
 * @author     Christian M. Stefan (https://www.wbeasy.de)
 * @license    http://www.gnu.org/licenses/gpl.html
 * @platform   WBCE CMS 1.7.0
 */
(function () {
  'use strict';

  // ── 1. TYPE FILTER CHECKBOXES ───────────────────────────────────────────────
  // Checkboxes carry value="page|tool|snippet|wysiwyg|template|theme"
  document.querySelectorAll('.am-type-toggle').forEach(cb => {
    cb.addEventListener('change', applyFilters);
  });

  // ── 2. LIVE SEARCH FILTERS ──────────────────────────────────────────────────
  // Inputs carry data-filter-col (0-based column index) and data-clear="#id"
  document.querySelectorAll('.am-filter').forEach(input => {
    input.addEventListener('input', applyFilters);
    const clearId = input.dataset.clear;
    if (clearId) {
      const btn = document.getElementById(clearId);
      if (btn) btn.addEventListener('click', () => { input.value = ''; applyFilters(); });
    }
  });

  function applyFilters() {
    // Collect active type values (empty set = all visible)
    const activeTypes = new Set(
      [...document.querySelectorAll('.am-type-toggle:checked')].map(cb => cb.value)
    );

    // Collect non-empty filter terms with their column index
    const terms = [...document.querySelectorAll('.am-filter')]
      .map(el => ({ col: parseInt(el.dataset.filterCol, 10), term: el.value.trim().toLowerCase() }))
      .filter(f => f.term);

    document.querySelectorAll('#htmlgrid tbody tr').forEach(tr => {
      const type    = tr.dataset.function || '';
      const typeOk  = activeTypes.size === 0 || activeTypes.has(type);
      const termOk  = terms.every(({ col, term }) =>
        (tr.cells[col]?.textContent ?? '').toLowerCase().includes(term)
      );
      tr.hidden = !(typeOk && termOk);
    });
  }

  // Run once so initial checkbox state is respected
  applyFilters();


  // ── 3. SORTABLE COLUMNS ─────────────────────────────────────────────────────
  // <th class="am-sort"> triggers sort on its cellIndex
  let lastTh = null;
  let ascending = true;

  document.querySelectorAll('#htmlgrid th.am-sort').forEach(th => {
    th.addEventListener('click', () => {
      if (lastTh === th) {
        ascending = !ascending;
      } else {
        if (lastTh) lastTh.removeAttribute('data-sort');
        ascending = true;
        lastTh = th;
      }
      th.dataset.sort = ascending ? 'asc' : 'desc';

      const col   = th.cellIndex;
      const tbody = document.querySelector('#htmlgrid tbody');
      if (!tbody) return;

      const rows = [...tbody.querySelectorAll('tr')];
      rows.sort((a, b) => {
        const av = (a.cells[col]?.textContent ?? '').trim();
        const bv = (b.cells[col]?.textContent ?? '').trim();
        const an = parseFloat(av), bn = parseFloat(bv);
        const cmp = (!isNaN(an) && !isNaN(bn))
          ? an - bn
          : av.localeCompare(bv, undefined, { sensitivity: 'base' });
        return ascending ? cmp : -cmp;
      });
      rows.forEach(r => tbody.appendChild(r));
    });
  });


  // ── 4. SECTION LIST TRUNCATION ──────────────────────────────────────────────
  // Shows first 3 page links; the count <li> is always visible.
  document.querySelectorAll('ul.using_sections').forEach(ul => {
    // li:first-child is the count badge — skip it
    const linkItems = [...ul.querySelectorAll('li:not(:first-child):not(.show_all)')];
    if (linkItems.length <= 3) return;

    linkItems.slice(3).forEach(li => { li.hidden = true; li.classList.add('am-extra'); });

    const toggle = document.createElement('li');
    toggle.className = 'show_all';
    toggle.textContent = '[ + ]';
    ul.appendChild(toggle);

    toggle.addEventListener('click', () => {
      const hidden = toggle.textContent.includes('+');
      ul.querySelectorAll('.am-extra').forEach(li => { li.hidden = !hidden; });
      toggle.textContent = hidden ? '[ − ]' : '[ + ]';
    });
  });


  // ── 5. DESCRIPTION COLLAPSE ─────────────────────────────────────────────────
  // <div class="am-desc"> collapses to ~4 lines (≈ 220 chars)
  const TRUNCATE = 220;

  document.querySelectorAll('.am-desc').forEach(div => {
    const full = div.textContent.trim();
    if (full.length <= TRUNCATE) return;

    const short = full.slice(0, TRUNCATE).replace(/\s+\S*$/, '') + '…';
    const textNode = document.createTextNode(short);
    const link = document.createElement('a');
    link.href = '#';
    link.className = 'am-desc-toggle';
    link.textContent = ' [+ more]';

    div.textContent = '';
    div.appendChild(textNode);
    div.appendChild(link);

    let collapsed = true;
    link.addEventListener('click', e => {
      e.preventDefault();
      collapsed = !collapsed;
      textNode.textContent = collapsed ? short : full;
      link.textContent     = collapsed ? ' [+ more]' : ' [− less]';
    });
  });

})();
