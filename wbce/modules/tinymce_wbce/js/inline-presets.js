/**
 * tinymce_wbce — js/inline-presets.js
 * The SECOND, lightweight preset list: inline (FEE) presets.
 *
 * Self-contained on purpose — it does NOT touch the full configurator
 * (preset-configurator.js). It reuses only the shared, read-only globals
 * (TKFG_ICONS, BUTTON_LABELS, TKFG_I18N, TKFG_CONFIG) and the same .chip /
 * .palette / .toolbar-row CSS, so it looks identical while staying decoupled.
 *
 * Per inline preset it renders a SINGLE drag-and-drop toolbar row plus a few
 * appearance fields, and talks to tool.php:
 *   save_inline_preset / add_inline_preset / delete_inline_preset / set_default_inline
 *
 * @license GNU GPL2
 */
(function () {

  var acc = document.getElementById('tmce-inline-accordion');
  if (!acc) { return; }

  var I18N = window.TKFG_I18N || {};
  function t(key, fallback) { return I18N[key] || fallback; }
  function toast(msg, type) { if (window.showToast) { window.showToast(msg, type); } }

  // ── Shared palette data (mirrors preset-configurator.js — kept local so this
  //    module has no dependency on that file's private scope). ──
  var GROUPS = [
    ['undo','redo'],
    ['blocks','styles','fontfamily','fontsize'],
    ['bold','italic','underline','strikethrough','wbce_casechange','removeformat'],
    ['forecolor','backcolor'],
    ['alignleft','aligncenter','alignright','alignjustify'],
    ['bullist','numlist','outdent','indent','lineheight'],
    ['blockquote'],
    ['link','wblink','image','media','table','hr'],
    ['wbdroplets','fa_picker'],
    ['subscript','superscript','charmap','emoticons','wbce_shy'],
    ['wbce_codesample','wbce_history'],
    ['code','fullscreen']
  ];
  var DROPDOWN_CHIPS = { blocks:'Paragraph', styles:'Styles', fontfamily:'Font', fontsize:'12px', wbce_casechange:'Aa' };
  var CHEVRON = '<svg class="dd-chevron" width="10" height="10" viewBox="0 0 10 10"><path d="M2 3.5L5 6.5L8 3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
  var iconMap = window.TKFG_ICONS || {};

  var drag   = null;   // { type:'palette'|'sep-palette'|'row', tok, id, ci }
  var state  = {};     // inline id → { rows: [tokens], mounted:bool }
  var openId = null;

  // ── Chip helpers (identical output to the config builder) ──
  function chipClass(tok) {
    if (tok === '|') { return 'sep-chip'; }
    if (DROPDOWN_CHIPS[tok]) { return 'dropdown-chip'; }
    return iconMap[tok] ? 'icon-chip' : 'text-chip';
  }
  function chipInner(tok) {
    if (tok === '|') { return '|'; }
    if (DROPDOWN_CHIPS[tok]) { return '<span>' + DROPDOWN_CHIPS[tok] + '</span>' + CHEVRON; }
    if (iconMap[tok]) { return iconMap[tok]; }
    return '<span style="font-size:.74rem">' + tok + '</span>';
  }
  function chipTitle(tok) {
    if (typeof BUTTON_LABELS !== 'undefined' && BUTTON_LABELS[tok]) { return BUTTON_LABELS[tok]; }
    return tok === '|' ? t('separator', 'Separator') : tok;
  }
  function esc(s) { return String(s).replace(/[&<>"]/g, function (c) { return { '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;' }[c]; }); }

  // Collapsed-header icon preview — same markup as tool.php tmce_inline_preview_chips()
  // (iconMap === window.TKFG_ICONS === server tinymce_wbce_button_icons()).
  var PV_TEXT = { wbdroplets: '[[]]' };
  function previewHtml(tokens) {
    var html = '';
    (tokens || []).forEach(function (tok) {
      if (tok === '|')            { html += '<span class="tmce-pv-sep"></span>'; return; }
      if (iconMap[tok])           { html += '<span class="tmce-pv-icon">' + iconMap[tok] + '</span>'; return; }
      if (DROPDOWN_CHIPS[tok])    { html += '<span class="tmce-pv-dd">' + esc(DROPDOWN_CHIPS[tok]) + '</span>'; return; }
      html += '<span class="tmce-pv-txt">' + esc(PV_TEXT[tok] || tok) + '</span>';
    });
    return '<span class="tmce-pv-row">' + html + '</span>';
  }

  // ── DOM getters ──
  function presetEl(id) { return acc.querySelector('.tmce-inline-preset[data-inline="' + id + '"]'); }
  function bodyEl(id)   { return document.getElementById('tmce-inline-body-' + id); }
  function paletteEl(id){ return document.getElementById('tmce-inline-palette-' + id); }
  function rowEl(id)    { return document.getElementById('tmce-inline-row-' + id); }

  // ── Palette ──
  function usedSet(id) { var s = {}; state[id].rows.forEach(function (tk) { if (tk !== '|') { s[tk] = 1; } }); return s; }

  function renderPalette(id) {
    var el = paletteEl(id);
    if (!el) { return; }
    var used = usedSet(id);
    el.innerHTML = '';
    var divider = function () { var d = document.createElement('div'); d.className = 'palette-divider'; return d; };
    el.appendChild(makePaletteChip(id, '|', used)); el.appendChild(divider());
    GROUPS.forEach(function (grp, gi) {
      grp.forEach(function (tok) { el.appendChild(makePaletteChip(id, tok, used)); });
      if (gi < GROUPS.length - 1) { el.appendChild(divider()); }
    });
  }

  function makePaletteChip(id, tok, used) {
    var isSep = tok === '|';
    var isUsed = !isSep && used[tok];
    var c = document.createElement('div');
    c.className = 'chip ' + chipClass(tok) + (isUsed ? ' used' : '');
    c.innerHTML = chipInner(tok);
    c.title = isUsed ? chipTitle(tok) + ' — ' + t('alreadyUsed', 'already used') : chipTitle(tok);
    c.draggable = !isUsed;
    c.addEventListener('dragstart', function (e) {
      drag = { type: isSep ? 'sep-palette' : 'palette', id: id, tok: tok };
      c.classList.add('dragging');
      e.dataTransfer.effectAllowed = isSep ? 'copy' : 'move';
    });
    c.addEventListener('dragend', function () { c.classList.remove('dragging'); });
    return c;
  }

  // ── Row ──
  function renderRow(id) {
    var el = rowEl(id);
    if (!el) { return; }
    el.innerHTML = '';
    state[id].rows.forEach(function (tok, ci) { el.appendChild(makeRowChip(id, tok, ci)); });
    el.ondragover = function (e) {
      e.preventDefault();
      e.dataTransfer.dropEffect = (drag && drag.type === 'sep-palette') ? 'copy' : 'move';
      el.classList.add('drag-over');
    };
    el.ondragleave = function (e) { if (!el.contains(e.relatedTarget)) { el.classList.remove('drag-over'); } };
    el.ondrop = function (e) {
      e.preventDefault();
      el.classList.remove('drag-over');
      drop(id, insertIdx(el, e.clientX));
    };
  }

  function makeRowChip(id, tok, ci) {
    var c = document.createElement('div');
    c.className = 'chip ' + chipClass(tok) + ' in-row';
    c.innerHTML = chipInner(tok);
    c.title = chipTitle(tok);
    c.draggable = true;
    var x = document.createElement('span');
    x.className = 'chip-x';
    x.textContent = '×';
    x.onclick = function (ev) { ev.stopPropagation(); removeChip(id, ci); };
    c.appendChild(x);
    c.addEventListener('dragstart', function (e) {
      drag = { type: 'row', id: id, tok: tok, ci: ci };
      c.classList.add('dragging');
      e.dataTransfer.effectAllowed = 'move';
    });
    c.addEventListener('dragend', function () { c.classList.remove('dragging'); });
    return c;
  }

  // Compute insertion index from the pointer x among the row's chips.
  function insertIdx(rowEl, clientX) {
    var chips = [].slice.call(rowEl.querySelectorAll('.chip'));
    for (var i = 0; i < chips.length; i++) {
      var r = chips[i].getBoundingClientRect();
      if (clientX < r.left + r.width / 2) { return i; }
    }
    return chips.length;
  }

  function drop(id, at) {
    if (!drag || drag.id !== id) { drag = null; return; }
    var rows = state[id].rows;
    if (drag.type === 'sep-palette') {
      rows.splice(at, 0, '|');
    } else if (drag.type === 'palette') {
      if (rows.indexOf(drag.tok) === -1) { rows.splice(at, 0, drag.tok); }
    } else if (drag.type === 'row') {
      var from = drag.ci;
      var tok  = rows[from];
      rows.splice(from, 1);
      if (from < at) { at--; }
      rows.splice(at, 0, tok);
    }
    drag = null;
    renderRow(id);
    renderPalette(id);
  }

  function removeChip(id, ci) {
    state[id].rows.splice(ci, 1);
    renderRow(id);
    renderPalette(id);
  }

  // ── Mount / accordion ──
  function readIsland(id) {
    var host = presetEl(id);
    var tag  = host && host.querySelector('.tmce-inline-data');
    if (!tag) { return {}; }
    try { return JSON.parse(tag.textContent || '{}') || {}; } catch (e) { return {}; }
  }

  function mount(id) {
    if (state[id] && state[id].mounted) { return; }
    var data = readIsland(id);
    var toolbar = (data.toolbar || '').trim();
    var rows = toolbar === '' ? [] : toolbar.split(/\s+/).filter(function (s) { return s !== ''; });
    state[id] = { rows: rows, mounted: true };
    renderPalette(id);
    renderRow(id);
  }

  function open(id) {
    if (openId && openId !== id) { close(openId); }
    var head = presetEl(id) && presetEl(id).querySelector('.tmce-inline-head');
    var body = bodyEl(id);
    if (!head || !body) { return; }
    body.hidden = false;
    head.setAttribute('aria-expanded', 'true');
    var chev = head.querySelector('.tmce-chev'); if (chev) { chev.textContent = '▾'; }
    presetEl(id).classList.add('open');
    openId = id;
    mount(id);
  }

  function close(id) {
    var head = presetEl(id) && presetEl(id).querySelector('.tmce-inline-head');
    var body = bodyEl(id);
    if (!head || !body) { return; }
    body.hidden = true;
    head.setAttribute('aria-expanded', 'false');
    var chev = head.querySelector('.tmce-chev'); if (chev) { chev.textContent = '▸'; }
    presetEl(id).classList.remove('open');
    if (openId === id) { openId = null; }
  }

  function toggle(id) { if (openId === id) { close(id); } else { open(id); } }

  // ── Server actions ──
  function url(name) { return acc.getAttribute('data-' + name + '-url'); }

  function saveInline(id) {
    var fd = new FormData();
    fd.set('id', id);
    fd.set('toolbar', (state[id] ? state[id].rows.join(' ') : ''));
    fd.set('label',      val('tmce-inline-name-' + id));
    fd.set('min_height', val('tmce-inline-minh-' + id));
    fd.set('skin',       val('tmce-inline-skin-' + id));
    fd.set('menubar',    checked('tmce-inline-menubar-' + id) ? '1' : '0');
    post(url('save'), fd).then(function (res) {
      if (res && res.success) {
        toast(t('savedMsg', 'Saved'), 'success');
        // Refresh the collapsed toolbar preview + header name.
        var host = presetEl(id);
        var prev = host && host.querySelector('.tmce-inline-tb-preview');
        if (prev) { prev.innerHTML = previewHtml((res.toolbar || '').split(/\s+/).filter(Boolean)); }
        var lbl  = val('tmce-inline-name-' + id).trim();
        var nEl  = host && host.querySelector('.tmce-inline-name');
        if (nEl) { nEl.textContent = lbl !== '' ? lbl : id; }
      } else {
        toast(t('saveError', 'Error'), 'error');
      }
    });
  }

  function addInline() {
    var nameEl = document.getElementById('tmce-inline-add-name');
    var fd = new FormData();
    fd.set('label', nameEl ? nameEl.value : '');
    post(url('add'), fd).then(function (res) {
      if (res && res.success) { window.location.reload(); }
      else { toast(t('saveError', 'Error'), 'error'); }
    });
  }

  function deleteInline(id) {
    if (!window.confirm(t('deleteConfirm', 'Delete this profile?'))) { return; }
    var fd = new FormData(); fd.set('id', id);
    post(url('delete'), fd).then(function (res) {
      if (res && res.success) { window.location.reload(); }
      else { toast(t('saveError', 'Error'), 'error'); }
    });
  }

  function setDefaultInline(id) {
    acc.querySelectorAll('.tmce-badge-default').forEach(function (b) {
      b.classList.toggle('on', b.getAttribute('data-inline') === id);
    });
    var fd = new FormData(); fd.set('id', id);
    post(url('default'), fd).then(function (res) {
      if (res && res.success) { toast(t('defaultSet', 'Default updated.'), 'success'); }
    });
  }

  // ── small utils ──
  function val(id)     { var e = document.getElementById(id); return e ? e.value : ''; }
  function checked(id) { var e = document.getElementById(id); return !!(e && e.checked); }
  function post(u, fd) {
    return fetch(u, { method: 'POST', body: fd, credentials: 'same-origin' })
      .then(function (r) { return r.json().catch(function () { return null; }); })
      .catch(function () { return null; });
  }

  // ── Wiring ──
  acc.addEventListener('click', function (e) {
    var badge = e.target.closest('.tmce-badge');
    if (badge && badge.hasAttribute('data-iact')) {
      e.stopPropagation();
      var bid = badge.getAttribute('data-inline');
      if (badge.getAttribute('data-iact') === 'default') { setDefaultInline(bid); }
      else if (badge.getAttribute('data-iact') === 'delete') { deleteInline(bid); }
      return;
    }
    var save = e.target.closest('.tmce-inline-save');
    if (save) { saveInline(save.getAttribute('data-inline')); return; }
    var addBtn = e.target.closest('#tmce-inline-add-btn');
    if (addBtn) { addInline(); return; }
    var head = e.target.closest('.tmce-inline-head');
    if (head) { toggle(head.closest('.tmce-inline-preset').getAttribute('data-inline')); }
  });

  acc.addEventListener('keydown', function (e) {
    var head = e.target.closest && e.target.closest('.tmce-inline-head');
    if (head && (e.key === 'Enter' || e.key === ' ')) {
      e.preventDefault();
      toggle(head.closest('.tmce-inline-preset').getAttribute('data-inline'));
    }
  });
}());
