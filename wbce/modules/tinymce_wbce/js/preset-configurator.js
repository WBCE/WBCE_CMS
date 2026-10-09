/**
 * tinymce_wbce — js/preset-configurator.js
 * Per-preset toolbar configurator, driven by the accordion (js/preset-accordion.js).
 *
 * One panel is active at a time; each panel keeps its own state in panels[id].
 * DOM ids inside a panel are suffixed with the preset id (see tool.php
 * tmce_render_panel_body). The shared icon map (harvested once from a hidden
 * TinyMCE) is reused across all panels.
 *
 * Public API (window.TkfgPanel):
 *   mount(id, data)  — first open: build state, palettes, rows, boot preview editor
 *   resume(id)       — re-open a previously mounted panel: re-boot the editor
 *   suspend(id)      — collapse: tear the editor down, keep the state/DOM
 *   serialize(id)    — return the save payload (rows + settings) for this panel
 *
 * Expects globals from tool.php: BUTTON_LABELS, TKFG_CONFIG, TKFG_I18N.
 * @license GNU GPL2
 */
(function () {

// ── i18n ──
var I18N = window.TKFG_I18N || {};
function t(key, fallback) { return I18N[key] || fallback; }

// ── DATA (button groups + dropdown/separator chips) ──
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
  ['code','fullscreen']  // canonical, portable token; swapped to cmButton for the preview
];
// The source button is stored as the neutral 'code' (portable); real editors
// and the preview swap it to 'wbcodemirror' when CM5 is present.
var CM_BUTTON = (window.TKFG_CONFIG && TKFG_CONFIG.cmButton) || 'code';
var DROPDOWN_CHIPS = { blocks:'Paragraph', styles:'Styles', fontfamily:'Font', fontsize:'12px', wbce_casechange:'Aa' };
var CHEVRON = '<svg class="dd-chevron" width="10" height="10" viewBox="0 0 10 10"><path d="M2 3.5L5 6.5L8 3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';

var LS_KEY  = 'tinymce_wbce_user_cfg';
// Icons are extracted server-side (icons.php) and injected as window.TKFG_ICONS —
// no hidden-editor probe needed. buttonId → normalized svg string.
var iconMap = window.TKFG_ICONS || {};
var drag    = null;
var panels  = {};   // id → { rows:[[],[],[],[]], usedMain:Set, usedMin:Set }

// ── DOM helpers ──
function byId(x)          { return document.getElementById(x); }
function g(prefix, id)    { return byId('tkfg-' + prefix + '-' + id); }
function mainRow(id, ri)  { return byId('tkfg-row-' + id + '-' + ri); }
function miniRow(id)      { return byId('tkfg-row-m0-' + id); }

// ── Chip helpers ──
function chipClass(tok) {
  if (tok === '|') return 'sep-chip';
  if (DROPDOWN_CHIPS[tok]) return 'dropdown-chip';
  return iconMap[tok] ? 'icon-chip' : 'text-chip';
}
function chipInner(tok) {
  if (tok === '|') return '|';
  if (DROPDOWN_CHIPS[tok]) return '<span>' + DROPDOWN_CHIPS[tok] + '</span>' + CHEVRON;
  if (iconMap[tok]) return iconMap[tok];
  return '<span style="font-size:.74rem">' + tok + '</span>';
}
function chipTitle(tok) {
  if (typeof BUTTON_LABELS !== 'undefined' && BUTTON_LABELS[tok]) return BUTTON_LABELS[tok];
  return tok === '|' ? t('separator', 'Separator') : tok;
}

// ── Palette ──
function renderPalette(id, isMin) {
  var el = isMin ? g('palette-min', id) : g('palette', id);
  if (!el) return;
  el.innerHTML = '';
  var divider = function () { var d = document.createElement('div'); d.className = 'palette-divider'; return d; };
  el.appendChild(makePaletteChip(id, '|', isMin)); el.appendChild(divider());
  GROUPS.forEach(function (grp, gi) {
    grp.forEach(function (tok) { el.appendChild(makePaletteChip(id, tok, isMin)); });
    if (gi < GROUPS.length - 1) el.appendChild(divider());
  });
}

function makePaletteChip(id, tok, isMin) {
  var st   = panels[id];
  var isSep = tok === '|';
  var used  = !isSep && (isMin ? st.usedMin : st.usedMain).has(tok);
  var c = document.createElement('div');
  c.className = 'chip ' + chipClass(tok) + (used ? ' used' : '');
  c.innerHTML = chipInner(tok);
  c.title = used ? chipTitle(tok) + ' — ' + t('alreadyUsed', 'already used') : chipTitle(tok);
  c.draggable = !used;
  c.addEventListener('dragstart', function (e) {
    drag = { type: isSep ? 'sep-palette' : 'palette', id: id, tok: tok };
    c.classList.add('dragging');
    e.dataTransfer.effectAllowed = isSep ? 'copy' : 'move';
  });
  c.addEventListener('dragend', function () { c.classList.remove('dragging'); });
  return c;
}

// ── Rows ──
// The "custom styles live in the Styles dropdown — put the Styles button in the
// toolbar" hint is only useful while that button is missing. Hide it once any
// main toolbar row already contains 'styles' (the condition it advises about).
function stylesInToolbar(st) {
  for (var r = 0; r < 3; r++) { if (st.rows[r].indexOf('styles') !== -1) { return true; } }
  return false;
}
function updateStylesHint(id) {
  var el = g('styles-hint', id); if (!el) { return; }
  el.style.display = stylesInToolbar(panels[id]) ? 'none' : '';
}

function renderRow(id, ri) {
  var st = panels[id];
  var el = ri === 3 ? miniRow(id) : mainRow(id, ri);
  if (!el) return;
  el.innerHTML = '';
  if (!st.rows[ri].length) {
    var ph = document.createElement('span');
    ph.className = 'placeholder';
    ph.textContent = t('dragHere', 'Drag buttons here…');
    el.appendChild(ph);
  }
  st.rows[ri].forEach(function (tok, ci) { el.appendChild(makeRowChip(id, tok, ri, ci)); });
  updateStylesHint(id);
  el.ondragover = function (e) {
    e.preventDefault(); el.classList.add('drag-over');
    e.dataTransfer.dropEffect = (drag && drag.type === 'sep-palette') ? 'copy' : 'move';
    showGhost(el, e.clientX);
  };
  el.ondragleave = function (e) { if (!el.contains(e.relatedTarget)) { el.classList.remove('drag-over'); removeGhost(); } };
  el.ondrop = function (e) {
    e.preventDefault(); el.classList.remove('drag-over'); removeGhost();
    if (!drag || drag.id !== id) { drag = null; return; }
    drop(id, ri, insertIdx(el, e.clientX));
  };
}

function makeRowChip(id, tok, ri, ci) {
  var c = document.createElement('div');
  c.className = 'chip ' + chipClass(tok);
  c.innerHTML = chipInner(tok);
  c.title = chipTitle(tok); c.draggable = true;
  c.dataset.ri = ri; c.dataset.ci = ci;
  var x = document.createElement('button');
  x.type = 'button'; x.className = 'chip-x'; x.textContent = '×'; x.title = t('remove', 'Remove');
  x.onclick = function (ev) { ev.stopPropagation(); removeChip(id, ri, ci); };
  c.appendChild(x);
  c.addEventListener('dragstart', function (e) { drag = { type: 'row', id: id, tok: tok, ri: ri, ci: ci }; c.classList.add('dragging'); e.dataTransfer.effectAllowed = 'move'; });
  c.addEventListener('dragend', function () { c.classList.remove('dragging'); });
  return c;
}

// ── Drop ghost ──
var ghost = null;
function removeGhost() {
  if (!ghost) return;
  var row = ghost.parentNode; ghost.remove(); ghost = null;
  if (row) { var ph = row.querySelector('.placeholder'); if (ph) ph.style.display = ''; }
}
function showGhost(rowEl, clientX) {
  removeGhost();
  var ph = rowEl.querySelector('.placeholder'); if (ph) ph.style.display = 'none';
  ghost = document.createElement('div'); ghost.className = 'tkfg-drop-ghost';
  var ref = refChip(rowEl, clientX);
  if (ref) rowEl.insertBefore(ghost, ref); else rowEl.appendChild(ghost);
}
function refChip(rowEl, clientX) {
  var chips = [].slice.call(rowEl.querySelectorAll('.chip'));
  for (var i = 0; i < chips.length; i++) {
    var r = chips[i].getBoundingClientRect();
    if (clientX < r.left + r.width / 2) return chips[i];
  }
  return null;
}
function insertIdx(rowEl, clientX) {
  var chips = [].slice.call(rowEl.querySelectorAll('.chip'));
  for (var i = 0; i < chips.length; i++) {
    var r = chips[i].getBoundingClientRect();
    if (clientX < r.left + r.width / 2) return parseInt(chips[i].dataset.ci, 10);
  }
  return chips.length;
}
document.addEventListener('dragend', removeGhost);

// ── Drop / remove ──
function drop(id, targetRi, at) {
  var st = panels[id];
  var isMin = targetRi === 3;
  var usedSet = isMin ? st.usedMin : st.usedMain;

  if (drag.type === 'sep-palette') {
    st.rows[targetRi].splice(at, 0, '|'); renderRow(id, targetRi);
  } else if (drag.type === 'palette') {
    if (usedSet.has(drag.tok)) { drag = null; return; }
    usedSet.add(drag.tok);
    st.rows[targetRi].splice(at, 0, drag.tok);
    renderPalette(id, isMin); renderRow(id, targetRi);
  } else if (drag.type === 'row') {
    var tok = drag.tok, src = drag.ri, srcCi = drag.ci;
    var srcIsMin = src === 3;
    if (src === targetRi) {
      st.rows[src].splice(srcCi, 1);
      st.rows[src].splice(at > srcCi ? at - 1 : at, 0, tok);
      renderRow(id, src);
    } else {
      st.rows[src].splice(srcCi, 1); st.rows[targetRi].splice(at, 0, tok);
      if (tok !== '|') { (srcIsMin ? st.usedMin : st.usedMain).delete(tok); usedSet.add(tok); }
      renderRow(id, src); renderRow(id, targetRi);
      renderPalette(id, srcIsMin); renderPalette(id, isMin);
    }
  }
  drag = null;
  if (isLive(id)) refreshEditor(id);
  if (targetRi === 3) refreshMini(id);
}

function removeChip(id, ri, ci) {
  var st = panels[id];
  var tok = st.rows[ri][ci];
  st.rows[ri].splice(ci, 1);
  if (tok !== '|') { (ri === 3 ? st.usedMin : st.usedMain).delete(tok); renderPalette(id, ri === 3); }
  renderRow(id, ri);
  if (isLive(id)) refreshEditor(id);
  if (ri === 3) refreshMini(id);
}

// ── Settings ──
function getSettings(id) {
  var skin = g('skin', id).value;
  return {
    height:              parseInt(g('height', id).value, 10) || 400,
    show_toolbar:        g('show-toolbar', id).checked,
    toolbar_sliding:     g('toolbar-sliding', id).checked,
    menubar:             g('menubar', id).checked,
    statusbar:           g('statusbar', id).checked,
    wordcount:           (g('wordcount', id) || {}).checked ? '1' : '0',
    skin:                skin,
    content_theme:       g('content-theme', id).value,
    content_css:         skin.indexOf('dark') !== -1 ? 'dark' : g('content-theme', id).value,
    editor_css_source:   (g('editorcss-source', id) || {}).value || 'auto',
    alt_reminder:        (g('alt-reminder', id) && !g('alt-reminder', id).checked) ? '0' : '1',
    editorcss_classes:         (g('editorcss-classes', id) || {}).value || 'off',
    editorcss_selector_filter: (g('editorcss-filter', id) || {}).value || '',
    auto_minimal:        g('auto-minimal', id).checked ? '1' : '0',
    auto_minimal_height: g('auto-minimal-height', id).value
  };
}
function isLive(id) { var el = g('liveupdate', id); return el && el.checked; }

// ── Typography sub-sections (fonts / sizes / block formats / custom styles) ──
var INLINE_ELEMENTS = ['span', 'code', 'strong', 'em'];

function collectTypography(id) {
  // Fonts: checked catalogue entries + custom textarea lines (Name=stack)
  var entries = [];
  var fontsBox = g('fonts', id);
  if (fontsBox) {
    fontsBox.querySelectorAll('.tkfg-font-cb:checked').forEach(function (cb) { entries.push(cb.value); });
    var extra = g('fonts-extra', id);
    if (extra) {
      extra.value.split('\n').forEach(function (line) {
        line = line.trim();
        if (line && line.indexOf('=') > 0) entries.push(line);
      });
    }
  }

  // Sizes + unit
  var unitEl  = g('size-unit', id);
  var sizesEl = g('sizes-list', id);

  // Block formats
  var blocks = [];
  var blocksBox = g('blocks', id);
  if (blocksBox) {
    blocksBox.querySelectorAll('.tkfg-block-cb:checked').forEach(function (cb) { blocks.push(cb.value); });
  }

  // Custom styles rows → array of {title, element, classes}
  var styles = [];
  var stylesBox = g('styles', id);
  if (stylesBox) {
    stylesBox.querySelectorAll('.tkfg-style-row').forEach(function (row) {
      var title = row.querySelector('.tkfg-style-title').value.trim();
      var el    = row.querySelector('.tkfg-style-element').value;
      var cls   = row.querySelector('.tkfg-style-class').value.trim();
      if (title !== '') styles.push({ title: title, element: el, classes: cls });
    });
  }

  return {
    families: entries.join(';'),
    sizes:    sizesEl ? sizesEl.value.trim() : '',
    unit:     unitEl ? unitEl.value : 'px',
    blocks:   blocks.join(';'),
    styles:   styles,
    colors:   collectColors(id)
  };
}

// ── Color swatches (forecolor / backcolor palettes) ──

/** Read one palette editor into an array of {color, label}. */
function readColorList(listId) {
  var box = byId(listId);
  var out = [];
  if (!box) return out;
  box.querySelectorAll('.tkfg-color-row').forEach(function (row) {
    var color = row.querySelector('.tkfg-color-val').value.trim();
    var label = row.querySelector('.tkfg-color-label').value.trim();
    if (color !== '') out.push({ color: color, label: label });
  });
  return out;
}

function collectColors(id) {
  var modeEl = g('color-mode', id);
  var colsEl = g('color-cols', id);
  var custEl = g('custom-colors', id);
  return {
    mode:   modeEl ? modeEl.value : '',
    shared: readColorList('tkfg-colors-shared-' + id),
    fore:   readColorList('tkfg-colors-fore-' + id),
    back:   readColorList('tkfg-colors-back-' + id),
    icons:  readColorList('tkfg-colors-icons-' + id),
    cols:   colsEl ? colsEl.value.trim() : '',
    custom: custEl ? (custEl.checked ? '1' : '0') : '1'
  };
}

/** {color,label} list → TinyMCE flat color_map (label falls back to the color). */
function toColorMap(list) {
  var map = [];
  list.forEach(function (c) { map.push(c.color, c.label || c.color); });
  return map;
}

/** Apply the collected palettes to a TinyMCE config object. */
function applyColors(cfg, co) {
  if (co.mode === 'shared' && co.shared.length) {
    cfg.color_map = toColorMap(co.shared);
  } else if (co.mode === 'split') {
    if (co.fore.length) cfg.color_map_foreground = toColorMap(co.fore);
    if (co.back.length) cfg.color_map_background = toColorMap(co.back);
  } else {
    return; // TinyMCE default palette — leave cols/custom alone too
  }
  var cols = parseInt(co.cols, 10);
  if (cols >= 1) cfg.color_cols = cols;
  if (co.custom === '0') cfg.custom_colors = false;
}

/** fa_picker swatches: icon palette, falling back to fore (split) / shared. */
function iconSwatches(co) {
  var list = co.icons.length ? co.icons
    : (co.mode === 'split' ? co.fore : (co.mode === 'shared' ? co.shared : []));
  return list.map(function (c) { return c.color; });
}

/** One-time global WbeColoris setup (picker binds via [data-wbecoloris]). */
var colorisReady = false;
function initColoris() {
  if (colorisReady || !window.WbeColoris) return;
  colorisReady = true;
  WbeColoris({
    okButton: true,
    format:   'hex',
    alpha:    true,
    onChange: function (color, input) {
      // Confirmed pick (OK button) → notify the swatch editor; the panel's
      // live-refresh listens for bubbled input events.
      if (input) input.dispatchEvent(new Event('input', { bubbles: true }));
    }
  });
}

/**
 * Wrap color inputs in Coloris' .clr-field (adds the swatch preview button).
 * Wrapping normally happens once at script init — our inputs arrive later via
 * HTMX panels and template clones, so re-run it; already-wrapped fields are
 * skipped.
 */
function wrapColorInputs() {
  if (window.WbeColoris && WbeColoris.wrap) WbeColoris.wrap('.tkfg-color-val');
}

/** Show only the palette editors that match the selected mode. */
function syncColorMode(id) {
  var modeEl = g('color-mode', id);
  var wrap   = byId('tkfg-color-palettes-' + id);
  if (!modeEl || !wrap) return;
  var mode = modeEl.value;
  wrap.querySelectorAll('.tkfg-color-palette').forEach(function (pal) {
    var p = pal.getAttribute('data-palette');
    var show = p === 'icons'  ? mode !== ''            // icons: any palette mode
             : p === 'shared' ? mode === 'shared'
             : mode === 'split';                        // fore/back
    pal.style.display = show ? '' : 'none';
  });
}

/** Map our style rows to TinyMCE style_formats entries (block vs inline). */
function toMceStyleFormats(styles) {
  return styles.map(function (s) {
    var entry = { title: s.title };
    entry[INLINE_ELEMENTS.indexOf(s.element) !== -1 ? 'inline' : 'block'] = s.element;
    if (s.classes) entry.classes = s.classes;
    return entry;
  });
}

/**
 * Build the "Styles" dropdown as a SUPERSET of the Paragraph dropdown:
 * the selected block formats first, the custom styles appended. Replacing
 * the 'blocks' button with 'styles' then yields ONE dropdown with everything.
 */
function toStylesSuperset(blocksStr, styles) {
  var out = [];
  (blocksStr || 'Paragraph=p;Heading 1=h1;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Preformatted=pre')
    .split(';').forEach(function (e) {
      var i = e.indexOf('=');
      if (i > 0) out.push({ title: e.slice(0, i).trim(), block: e.slice(i + 1).trim() });
    });
  return out.concat(toMceStyleFormats(styles));
}

// ── Serialize (save payload / localStorage) ──
function buildPayload(id) {
  var st = panels[id];
  var ts = st.rows.slice(0, 3).map(function (r) { return r.join(' '); });
  var s  = getSettings(id);
  var ty = collectTypography(id);
  return {
    toolbar_row1:        ts[0] || '',
    toolbar_row2:        ts[1] || '',
    toolbar_row3:        ts[2] || '',
    toolbar_minimal:     st.rows[3].join(' '),
    height:              String(s.height),
    show_toolbar:        s.show_toolbar    ? '1' : '0',
    toolbar_sliding:     s.toolbar_sliding ? '1' : '0',
    skin:                s.skin,
    menubar:             s.menubar   ? '1' : '0',
    statusbar:           s.statusbar ? '1' : '0',
    wordcount:           s.wordcount,
    auto_minimal:        s.auto_minimal,
    auto_minimal_height: s.auto_minimal_height,
    content_theme:       s.content_theme,
    editor_css_source:         s.editor_css_source,
    editorcss_classes:         s.editorcss_classes,
    editorcss_selector_filter: s.editorcss_selector_filter,
    font_families:       ty.families,
    font_sizes:          ty.sizes,
    font_size_unit:      ty.unit,
    block_formats:       ty.blocks,
    style_formats:       ty.styles.length ? JSON.stringify(ty.styles) : '',
    color_mode:          ty.colors.mode,
    colors_shared:       ty.colors.shared.length ? JSON.stringify(ty.colors.shared) : '',
    colors_fore:         ty.colors.fore.length   ? JSON.stringify(ty.colors.fore)   : '',
    colors_back:         ty.colors.back.length   ? JSON.stringify(ty.colors.back)   : '',
    colors_icons:        ty.colors.icons.length  ? JSON.stringify(ty.colors.icons)  : '',
    color_cols:          ty.colors.cols,
    custom_colors:       ty.colors.custom,
    history_enabled:     (g('history-enabled', id) && g('history-enabled', id).checked) ? '1' : '0',
    history_max:         (g('history-max', id) || {}).value || '10',
    history_keep:        (g('history-keep', id) || {}).value || '10',
    history_autoclean:   (g('history-autoclean', id) && g('history-autoclean', id).checked) ? '1' : '0',
    history_ttl_days:    (g('history-ttl', id) || {}).value || '90',
    paste_images:        (g('paste-images', id) && g('paste-images', id).checked) ? '1' : '0',
    paste_folder:        ((g('paste-folder', id) || {}).value || 'pasted').trim(),
    paste_webp:          (g('paste-webp', id) && g('paste-webp', id).checked) ? '1' : '0',
    paste_webp_quality:  (g('paste-webp-quality', id) || {}).value || '82',
    img_dblclick:        (g('img-dblclick', id) && g('img-dblclick', id).checked) ? '1' : '0',
    img_size_badge:      (g('img-size-badge', id) && g('img-size-badge', id).checked) ? '1' : '0',
    alt_reminder:        (g('alt-reminder', id) && g('alt-reminder', id).checked) ? '1' : '0',
    link_classes:        (g('link-classes', id) || {}).value || '',
    link_rels:           (g('link-rels', id) || {}).value || ''
  };
}

// Insert menu mirrors the insert-capable buttons placed in the toolbar rows —
// same rule/mapping as include.php for real editors. Unregistered names are
// ignored by TinyMCE, so referencing e.g. 'hr' is safe.
var INSERT_MENU_MAP = { image:'image', link:'link', wblink:'link', media:'media',
  table:'inserttable', charmap:'charmap', emoticons:'emoticons',
  fa_picker:'fa_picker', wbdroplets:'wbdroplets', hr:'hr', wbce_shy:'wbce_shy' };
var INSERT_MENU_GROUPS = [ ['image','link','media','inserttable'],
  ['charmap','emoticons','fa_picker','wbdroplets'], ['hr','wbce_shy'] ];

function applyInsertMenu(cfg, toolbarRows, menubarOn) {
  if (!menubarOn) return;
  var have = {};
  (toolbarRows || []).join(' ').split(/\s+/).forEach(function (tk) {
    if (INSERT_MENU_MAP[tk]) have[INSERT_MENU_MAP[tk]] = true;
  });
  var parts = [];
  INSERT_MENU_GROUPS.forEach(function (g) {
    var it = g.filter(function (i) { return have[i]; });
    if (it.length) parts.push(it.join(' '));
  });
  var items = parts.join(' | ');
  if (items) {
    cfg.menu = cfg.menu || {};
    cfg.menu.insert = { title: 'Insert', items: items };
    cfg.menubar = 'file edit view insert format tools table help';
  } else {
    cfg.menubar = 'file edit view format tools table help';
  }
}

// ── Live preview editor ──
function mceConfig(id, content, isMin) {
  var st = panels[id];
  var s  = getSettings(id);
  var src = isMin ? [st.rows[3]] : st.rows.slice(0, 3);
  // Swap the portable 'code' token to the active source button for the preview,
  // mirroring the runtime swap real editors do (presets.php runtime_toolbar).
  var swap = function (tok) { return tok === 'code' ? CM_BUTTON : tok; };
  var ts  = src.map(function (r) { return r.map(swap).join(' '); }).filter(function (x) { return x.trim(); });
  var tgt = isMin ? g('mce-min', id) : g('mce', id);
  var cfg = {
    target: tgt, height: isMin ? 150 : s.height, menubar: isMin ? false : s.menubar,
    statusbar: isMin ? false : s.statusbar, skin: s.skin,
    content_css: [s.content_css, TKFG_CONFIG.faCssUrl, TKFG_CONFIG.contentExtrasUrl].filter(Boolean),
    promotion: false, branding: false, license_key: 'gpl',
    base_url: TKFG_CONFIG.modUrl + '/tinymce', suffix: '.min',
    plugins: 'lists link image table code fullscreen charmap media emoticons visualchars'
             + (s.wordcount === '1' && !isMin ? ' wordcount' : ''),
    visualchars_default_state: true,
    external_plugins: {
      wbdroplets:   TKFG_CONFIG.modUrl + '/plugins/wbdroplets/plugin.js',
      wblink:       TKFG_CONFIG.modUrl + '/plugins/wblink/plugin.js',
      wbcodemirror: TKFG_CONFIG.modUrl + '/plugins/wbcodemirror/plugin.js',
      fa_picker:    TKFG_CONFIG.modUrl + '/plugins/fa_picker/plugin.min.js',
      wbce_casechange: TKFG_CONFIG.modUrl + '/plugins/wbce_casechange/plugin.js',
      wbce_shy:     TKFG_CONFIG.modUrl + '/plugins/wbce_shy/plugin.js',
      wbce_codesample: TKFG_CONFIG.modUrl + '/plugins/wbce_codesample/plugin.js',
      wbce_history: TKFG_CONFIG.modUrl + '/plugins/wbce_history/plugin.js'
    },
    fa_picker_css_url: TKFG_CONFIG.faCssUrl || '',
    wbce_codesample_url: TKFG_CONFIG.modUrl + '/codesample_tinymce.php',
    // Empty on purpose: preview editors must NOT record history snapshots.
    wbce_history_url: '',
    extended_valid_elements: 'i[class|style|aria-hidden]',
    toolbar_mode: 'wrap', toolbar: ts.length ? ts : false
  };
  // Toolbar visibility / compact mode (main preview only — the mini preview
  // is a single row anyway). Sliding needs ONE toolbar string: TinyMCE
  // ignores toolbar_mode when rows are given as an array.
  if (!isMin) {
    if (!s.show_toolbar) {
      cfg.toolbar = false;
    } else if (s.toolbar_sliding && ts.length) {
      cfg.toolbar = ts.join(' | ');
      cfg.toolbar_mode = 'sliding';
    }
  }
  // Typography — mirrors what include.php passes to real editors
  var ty = collectTypography(id);
  if (ty.families)      cfg.font_family_formats = ty.families;
  if (ty.sizes)         cfg.font_size_formats   = ty.sizes;
  if (ty.unit)          cfg.font_size_input_default_unit = ty.unit;
  if (ty.blocks)        cfg.block_formats       = ty.blocks;
  // Styles dropdown = block formats + custom styles in ONE list (superset)
  if (ty.styles.length) cfg.style_formats = toStylesSuperset(ty.blocks, ty.styles);
  // Color swatches for forecolor/backcolor
  applyColors(cfg, ty.colors);
  var faSw = iconSwatches(ty.colors);
  if (faSw.length) cfg.fa_picker_swatches = faSw;
  // Insert menu reflects the configured toolbar buttons (main preview only)
  if (!isMin) applyInsertMenu(cfg, ts, s.menubar);
  if (TKFG_CONFIG.lang && TKFG_CONFIG.lang !== 'en') {
    cfg.language = TKFG_CONFIG.lang;
    cfg.language_url = TKFG_CONFIG.modUrl + '/tinymce/langs/' + TKFG_CONFIG.lang + '.js';
  }
  // Label fix for em/rem size lists + optional content restore
  cfg.setup = function (ed) {
    if (window.tinymceWbceSizeLabelFix) tinymceWbceSizeLabelFix(ed);
    // #5 Alt-text reminder in the preview (opt-out) so it's testable here too.
    if (s.alt_reminder !== '0' && window.tinymceWbceImageAltBadge) tinymceWbceImageAltBadge(ed);
    // Bundled de.js ships empty strings for "Format"/"Code"/"Emojis…" — an
    // empty value defeats TinyMCE's key fallback, so re-add them after the
    // lang pack loads (menu items/labels would otherwise render blank).
    if (TKFG_CONFIG.lang === 'de') {
      ed.on('init', function () { if (window.tinymce && tinymce.addI18n) tinymce.addI18n('de', {'Format': 'Format', 'Code': 'Code', 'Emojis...': 'Emojis...', 'Emojis': 'Emojis'}); });
    }
    if (content !== undefined) ed.on('init', function () { ed.setContent(content); });
  };
  return cfg;
}

function refreshEditor(id) {
  var ed = tinymce.get('tkfg-mce-' + id);
  var content = ed ? ed.getContent() : '';
  var scrollY = window.scrollY;
  if (ed) tinymce.remove(ed);
  g('editor-wrap', id).innerHTML = '<textarea id="tkfg-mce-' + id + '"></textarea>';
  var cfg = mceConfig(id, content, false);
  var prev = cfg.setup;
  cfg.setup = function (editor) { if (prev) prev(editor); editor.on('init', function () { window.scrollTo({ top: scrollY, behavior: 'instant' }); }); };
  tinymce.init(cfg);
  refreshMini(id);
}

function refreshMini(id) {
  var s = getSettings(id);
  var wrap = g('mini-wrap', id);
  if (!wrap) return;
  var show = s.auto_minimal === '1' && panels[id].rows[3].length > 0;
  wrap.style.display = show ? '' : 'none';
  if (!show) return;
  var ed = tinymce.get('tkfg-mce-min-' + id);
  if (ed) tinymce.remove(ed);
  g('mini-editor-wrap', id).innerHTML = '<textarea id="tkfg-mce-min-' + id + '"></textarea>';
  tinymce.init(mceConfig(id, undefined, true));
}

// ── Rows seeding from data ──
// Canonicalize a stored token: any 'wbcodemirror' collapses back to the
// portable 'code' so the palette/dedup treat them as one button.
function canonToken(tok) { return tok === 'wbcodemirror' ? 'code' : tok; }

function seedRows(id, data) {
  var st = panels[id];
  st.rows = [[], [], [], []]; st.usedMain = new Set(); st.usedMin = new Set();
  ['toolbar_row1', 'toolbar_row2', 'toolbar_row3'].forEach(function (key, ri) {
    var val = (data[key] || '').trim(); if (!val) return;
    val.split(/\s+/).filter(Boolean).forEach(function (b) {
      if (b === '|') { st.rows[ri].push(b); return; }
      b = canonToken(b);
      if (st.usedMain.has(b)) return; // dedupe buttons (e.g. legacy code + wbcodemirror)
      st.rows[ri].push(b); st.usedMain.add(b);
    });
  });
  var minVal = (data.toolbar_minimal || '').trim();
  if (minVal) {
    minVal.split(/\s+/).filter(Boolean).forEach(function (b) {
      if (b === '|') { st.rows[3].push(b); return; }
      b = canonToken(b);
      if (st.usedMin.has(b)) return;
      st.rows[3].push(b); st.usedMin.add(b);
    });
  }
}

function renderAll(id) {
  renderPalette(id, false); renderPalette(id, true);
  [0, 1, 2, 3].forEach(function (ri) { renderRow(id, ri); });
}

// ── localStorage "Only me" ──
function updateLocalBadge(id) {
  var has = false, forThis = false;
  try {
    var raw = localStorage.getItem(LS_KEY);
    if (raw) { has = true; var c = JSON.parse(raw); forThis = (c._preset === id); }
  } catch (e) {}
  var badge = g('local-badge', id), clr = g('clear-local-btn', id);
  if (badge) badge.style.display = forThis ? 'inline' : 'none';
  if (clr)   clr.style.display   = forThis ? '' : 'none';
}

function saveLocal(id) {
  try {
    var p = buildPayload(id); p._preset = id;
    localStorage.setItem(LS_KEY, JSON.stringify(p));
    if (window.showToast) window.showToast(t('savedMsg', 'Settings saved.'), 'success');
    updateLocalBadge(id);
    window.dispatchEvent(new CustomEvent('tkfg:localChanged', { detail: { id: id } }));
  } catch (e) {}
}
function clearLocal(id) {
  try { localStorage.removeItem(LS_KEY); updateLocalBadge(id); window.dispatchEvent(new CustomEvent('tkfg:localChanged', { detail: { id: null } })); } catch (e) {}
}

// ── Server save (AJAX) ──
function saveServer(id) {
  var btn = g('save-btn', id);
  var fd = new FormData();
  fd.append('id', id);
  fd.append('preset_json', JSON.stringify(buildPayload(id)));
  fetch(TKFG_CONFIG.toolUrl + '&action=save_preset', { method: 'POST', body: fd })
    .then(function (r) { return r.text().then(function (txt) { return { ok: r.ok, txt: txt }; }); })
    .then(function (res) {
      if (res.ok) {
        if (window.showToast) window.showToast(t('savedMsg', 'Settings saved.'), 'success');
        writePanelData(id, buildPayload(id)); // keep the header data island fresh
        var pv = byId('tmce-preview-' + id); if (pv && res.txt) pv.innerHTML = res.txt;
        refreshEditor(id); // the preview always reflects what was just saved
        if (btn) { btn.textContent = t('saveOk', '✓ Saved'); btn.disabled = true; setTimeout(function () { btn.textContent = t('save', 'Save'); btn.disabled = false; }, 2200); }
      } else {
        if (window.showToast) window.showToast(t('saveFailed', 'Saving failed.'), 'error');
        if (btn) { btn.textContent = t('saveError', '✗ Error'); setTimeout(function () { btn.textContent = t('save', 'Save'); }, 2200); }
      }
    })
    .catch(function () {
      if (window.showToast) window.showToast(t('saveFailed', 'Saving failed.'), 'error');
    });
}

// ── Event binding (once per panel, on mount) ──
function bind(id) {
  ['height', 'show-toolbar', 'toolbar-sliding', 'menubar', 'statusbar', 'wordcount', 'skin', 'content-theme', 'editorcss-classes', 'editorcss-filter', 'alt-reminder'].forEach(function (name) {
    var el = g(name, id); if (!el) return;
    var evt = (el.type === 'number') ? 'input' : 'change';
    el.addEventListener(evt, function () { if (isLive(id)) refreshEditor(id); });
  });
  g('auto-minimal-height', id).addEventListener('input', function () {
    var lbl = g('minimal-height-label', id); if (lbl) lbl.textContent = this.value;
    refreshMini(id);
  });
  g('auto-minimal', id).addEventListener('change', function () { refreshMini(id); });
  g('liveupdate', id).addEventListener('change', function (e) {
    g('btn-refresh', id).style.display = e.target.checked ? 'none' : '';
    if (e.target.checked) refreshEditor(id);
  });
  g('btn-refresh', id).addEventListener('click', function () { refreshEditor(id); });
  // NOTE: there is no dedicated "reset rows" button in the current markup (the
  // "Only me" local-override save/clear flow moved to the accordion header
  // badges — see preset-accordion.js). Guarded so a stale/removed control never
  // breaks the rest of bind() again.
  var resetBtn = g('btn-reset', id);
  if (resetBtn) { resetBtn.addEventListener('click', function () { resetRows(id); }); }

  // ── Typography bindings ──
  var liveRefresh = function () { if (isLive(id)) refreshEditor(id); };

  var fontsBox = g('fonts', id);
  if (fontsBox) fontsBox.addEventListener('change', liveRefresh);
  var fontsExtra = g('fonts-extra', id);
  if (fontsExtra) fontsExtra.addEventListener('input', liveRefresh);

  var sizeDefaults = (TKFG_CONFIG.sizeDefaults || {});
  var unitEl  = g('size-unit', id);
  var sizesEl = g('sizes-list', id);
  if (unitEl && sizesEl) {
    unitEl.addEventListener('change', function () {
      var val = sizesEl.value.trim();
      // Auto-fill only when empty or still equal to another unit's default
      var isDefault = val === '' || Object.keys(sizeDefaults).some(function (u) { return sizeDefaults[u] === val; });
      if (isDefault) sizesEl.value = sizeDefaults[unitEl.value] || '';
      sizesEl.placeholder = sizeDefaults[unitEl.value] || '';
      liveRefresh();
    });
    sizesEl.addEventListener('input', liveRefresh);
    var defBtn = g('size-defaults', id);
    if (defBtn) defBtn.addEventListener('click', function () {
      sizesEl.value = sizeDefaults[unitEl.value] || '';
      // Fire a real input event so downstream listeners run even when Live-update
      // is off, and give a visible flash — otherwise the button looks dead
      // whenever the field already holds this unit's defaults.
      sizesEl.dispatchEvent(new Event('input', { bubbles: true }));
      sizesEl.focus();
      try { sizesEl.select(); } catch (e) {}
      var prev = sizesEl.style.backgroundColor;
      sizesEl.style.transition = 'background-color .15s';
      sizesEl.style.backgroundColor = 'rgba(96,166,255,.28)';
      setTimeout(function () { sizesEl.style.backgroundColor = prev; }, 260);
    });
  }

  // Version history: the "max age" (B) and "keep" (C) fields only make sense when
  // auto-cleanup (A) is on — reveal them only then.
  var acEl = g('history-autoclean', id);
  var acFields = g('history-clean', id);
  if (acEl && acFields) {
    var syncAc = function () { acFields.style.display = acEl.checked ? '' : 'none'; };
    acEl.addEventListener('change', syncAc);
    syncAc();
  }

  var blocksBox = g('blocks', id);
  if (blocksBox) blocksBox.addEventListener('change', liveRefresh);

  var stylesBox = g('styles', id);
  if (stylesBox) {
    stylesBox.addEventListener('click', function (e) {
      var del = e.target.closest('.tkfg-style-del');
      if (del) { del.closest('.tkfg-style-row').remove(); liveRefresh(); }
    });
    stylesBox.addEventListener('input', liveRefresh);
    stylesBox.addEventListener('change', liveRefresh);
  }
  var addBtn = g('style-add', id);
  if (addBtn) addBtn.addEventListener('click', function () {
    var tpl = byId('tkfg-style-tpl-' + id);
    if (tpl && stylesBox) stylesBox.appendChild(tpl.content.cloneNode(true));
  });
  // ── Color swatch bindings ──
  initColoris();
  wrapColorInputs();
  syncColorMode(id);
  var colorsBox = g('colors', id);
  if (colorsBox) {
    colorsBox.addEventListener('input', liveRefresh);
    colorsBox.addEventListener('change', function (e) {
      if (e.target === g('color-mode', id)) syncColorMode(id);
      liveRefresh();
    });
    colorsBox.addEventListener('click', function (e) {
      var del = e.target.closest('.tkfg-color-del');
      if (del) { del.closest('.tkfg-color-row').remove(); liveRefresh(); return; }
      var add = e.target.closest('.tkfg-color-add');
      if (add) {
        var ctpl = byId('tkfg-color-tpl-' + id);
        var box  = byId(add.getAttribute('data-target'));
        if (ctpl && box) { box.appendChild(ctpl.content.cloneNode(true)); wrapColorInputs(); }
        liveRefresh();
      }
    });
  }

  g('save-btn', id).addEventListener('click', function () { saveServer(id); });
  // NOTE: no dedicated "save locally" panel button either (same "Only me" flow
  // now lives in the accordion header badge — TkfgPanel.localizeFromData()).
  var saveLocalBtn = g('save-local-btn', id);
  if (saveLocalBtn) { saveLocalBtn.addEventListener('click', function () { saveLocal(id); }); }
  g('clear-local-btn', id).addEventListener('click', function () { clearLocal(id); });
}

function panelData(id) {
  var isl = byId('tkfg-data-' + id);
  try { return JSON.parse(isl.textContent); } catch (e) { return {}; }
}

/** Update the header data island (keeps reset/localize in sync after a save). */
function writePanelData(id, payload) {
  var isl = byId('tkfg-data-' + id);
  if (isl) { try { isl.textContent = JSON.stringify(payload); } catch (e) {} }
}

/** Normalize a data-island object into a save payload shape. */
function payloadFromData(d) {
  return {
    toolbar_row1: d.toolbar_row1 || '', toolbar_row2: d.toolbar_row2 || '', toolbar_row3: d.toolbar_row3 || '',
    toolbar_minimal: d.toolbar_minimal || '', height: String(d.height || 400), skin: d.skin || 'oxide',
    show_toolbar: d.show_toolbar || '1', toolbar_sliding: d.toolbar_sliding || '0',
    menubar: d.menubar || '0', statusbar: d.statusbar || '1', wordcount: d.wordcount || '0', auto_minimal: d.auto_minimal || '1',
    auto_minimal_height: d.auto_minimal_height || '200', content_theme: d.content_theme || 'default',
    editor_css_source: d.editor_css_source || 'auto',
    editorcss_classes: d.editorcss_classes || 'off', editorcss_selector_filter: d.editorcss_selector_filter || '',
    font_families: d.font_families || '', font_sizes: d.font_sizes || '',
    font_size_unit: d.font_size_unit || 'px', block_formats: d.block_formats || '',
    style_formats: d.style_formats || '',
    color_mode: d.color_mode || '', colors_shared: d.colors_shared || '',
    colors_fore: d.colors_fore || '', colors_back: d.colors_back || '',
    colors_icons: d.colors_icons || '',
    color_cols: d.color_cols || '', custom_colors: d.custom_colors || '1',
    history_enabled: d.history_enabled || '1', history_max: d.history_max || '10', history_keep: d.history_keep || '10',
    history_autoclean: d.history_autoclean || '0', history_ttl_days: d.history_ttl_days || '90',
    paste_images: d.paste_images || '0', paste_folder: d.paste_folder || 'pasted',
    paste_webp: d.paste_webp || '0', paste_webp_quality: d.paste_webp_quality || '82',
    img_dblclick: d.img_dblclick || '0', img_size_badge: d.img_size_badge || '0', alt_reminder: (d.alt_reminder === '0' ? '0' : '1'),
    link_classes: d.link_classes || '', link_rels: d.link_rels || ''
  };
}

/** Reseed the typography controls from data-island values (used by reset). */
function seedTypography(id, data) {
  var entries = (data.font_families || '').split(';').map(function (s) { return s.trim(); }).filter(Boolean);
  var fontsBox = g('fonts', id);
  if (fontsBox) {
    var known = {};
    fontsBox.querySelectorAll('.tkfg-font-cb').forEach(function (cb) {
      known[cb.value] = true;
      cb.checked = entries.indexOf(cb.value) !== -1;
    });
    var extra = g('fonts-extra', id);
    if (extra) extra.value = entries.filter(function (e) { return !known[e]; }).join('\n');
  }
  var unitEl = g('size-unit', id);   if (unitEl)  unitEl.value  = data.font_size_unit || 'px';
  var sizesEl = g('sizes-list', id); if (sizesEl) sizesEl.value = data.font_sizes || '';

  var blocks = (data.block_formats || '').split(';').map(function (s) { return s.trim(); }).filter(Boolean);
  var blocksBox = g('blocks', id);
  if (blocksBox) {
    // Empty stored list = TinyMCE default (p, h1–h6, pre)
    var defaults = ['Paragraph=p','Heading 1=h1','Heading 2=h2','Heading 3=h3','Heading 4=h4','Heading 5=h5','Heading 6=h6','Preformatted=pre'];
    var ref = blocks.length ? blocks : defaults;
    blocksBox.querySelectorAll('.tkfg-block-cb').forEach(function (cb) { cb.checked = ref.indexOf(cb.value) !== -1; });
  }

  var stylesBox = g('styles', id);
  if (stylesBox) {
    stylesBox.innerHTML = '';
    var rows = [];
    try { rows = JSON.parse(data.style_formats || '[]'); } catch (e) { rows = []; }
    var tpl = byId('tkfg-style-tpl-' + id);
    (Array.isArray(rows) ? rows : []).forEach(function (r) {
      if (!tpl) return;
      var frag = tpl.content.cloneNode(true);
      frag.querySelector('.tkfg-style-title').value = r.title || '';
      frag.querySelector('.tkfg-style-element').value = r.element || 'p';
      frag.querySelector('.tkfg-style-class').value = r.classes || '';
      stylesBox.appendChild(frag);
    });
  }

  // Color swatches
  var modeEl = g('color-mode', id);    if (modeEl) modeEl.value = data.color_mode || '';
  var colsEl = g('color-cols', id);    if (colsEl) colsEl.value = data.color_cols || '';
  var custEl = g('custom-colors', id); if (custEl) custEl.checked = (data.custom_colors || '1') === '1';
  var tpl = byId('tkfg-color-tpl-' + id);
  [['shared', 'colors_shared'], ['fore', 'colors_fore'], ['back', 'colors_back'], ['icons', 'colors_icons']].forEach(function (pair) {
    var box = byId('tkfg-colors-' + pair[0] + '-' + id);
    if (!box || !tpl) return;
    box.innerHTML = '';
    var rows = [];
    try { rows = JSON.parse(data[pair[1]] || '[]'); } catch (e) { rows = []; }
    (Array.isArray(rows) ? rows : []).forEach(function (r) {
      var frag = tpl.content.cloneNode(true);
      frag.querySelector('.tkfg-color-val').value = r.color || '';
      frag.querySelector('.tkfg-color-label').value = r.label || '';
      box.appendChild(frag);
    });
  });
  wrapColorInputs();
  syncColorMode(id);

  // Version history
  var hEn = g('history-enabled', id); if (hEn) hEn.checked = (data.history_enabled || '1') === '1';
  var hMx = g('history-max', id);     if (hMx) hMx.value = data.history_max || '10';
  var hKp = g('history-keep', id);    if (hKp) hKp.value = data.history_keep || '10';
  var hAc = g('history-autoclean', id); if (hAc) hAc.checked = (data.history_autoclean || '0') === '1';
  var hTt = g('history-ttl', id);       if (hTt) hTt.value = data.history_ttl_days || '90';

  // Paste images
  var pIm = g('paste-images', id); if (pIm) pIm.checked = (data.paste_images || '0') === '1';
  var pFo = g('paste-folder', id); if (pFo) pFo.value = data.paste_folder || 'pasted';
  var pWe = g('paste-webp', id); if (pWe) pWe.checked = (data.paste_webp || '0') === '1';
  var pWq = g('paste-webp-quality', id); if (pWq) pWq.value = data.paste_webp_quality || '82';
  var iDc = g('img-dblclick', id); if (iDc) iDc.checked = (data.img_dblclick || '0') === '1';
  var iSb = g('img-size-badge', id); if (iSb) iSb.checked = (data.img_size_badge || '0') === '1';
  var iAr = g('alt-reminder', id); if (iAr) iAr.checked = (data.alt_reminder || '1') !== '0';
  var lCl = g('link-classes', id); if (lCl) lCl.value = data.link_classes || '';
  var lRe = g('link-rels', id);    if (lRe) lRe.value = data.link_rels || '';
}

function resetRows(id) {
  var ed = tinymce.get('tkfg-mce-' + id); if (ed) tinymce.remove(ed);
  var data = panelData(id);
  seedRows(id, data);
  g('height', id).value = data.height || 400;
  g('show-toolbar', id).checked = (data.show_toolbar || '1') === '1';
  g('toolbar-sliding', id).checked = data.toolbar_sliding === '1';
  g('menubar', id).checked = data.menubar === '1';
  g('statusbar', id).checked = data.statusbar === '1';
  var wc = g('wordcount', id); if (wc) wc.checked = (data.wordcount || '0') === '1';
  g('skin', id).value = data.skin || 'oxide';
  g('content-theme', id).value = data.content_theme || 'default';
  var eccs = g('editorcss-source', id); if (eccs) eccs.value = data.editor_css_source || 'auto';
  var eccl = g('editorcss-classes', id); if (eccl) eccl.value = data.editorcss_classes || 'off';
  var ecfl = g('editorcss-filter', id);  if (ecfl) ecfl.value = data.editorcss_selector_filter || '';
  g('auto-minimal', id).checked = data.auto_minimal === '1';
  g('auto-minimal-height', id).value = data.auto_minimal_height || 200;
  seedTypography(id, data);
  renderAll(id);
  g('editor-wrap', id).innerHTML = '<textarea id="tkfg-mce-' + id + '"></textarea>';
  tinymce.init(mceConfig(id, undefined, false));
  setTimeout(function () { refreshMini(id); }, 100);
}

// ── Public API ──
window.TkfgPanel = {
  mount: function (id, data) {
    if (panels[id] && panels[id]._mounted) { this.resume(id); return; }
    panels[id] = { rows: [[], [], [], []], usedMain: new Set(), usedMin: new Set(), _mounted: true };
    data = data || panelData(id);
    seedRows(id, data);
    renderAll(id);
    bind(id);
    tinymce.init(mceConfig(id, undefined, false));
    refreshMini(id);
    updateLocalBadge(id);

    // Live preview defaults to ON the first time a profile is opened, so the
    // preview reflects edits immediately without an extra click.
    var live = g('liveupdate', id);
    if (live) {
      live.checked = true;
      var btn = g('btn-refresh', id); if (btn) { btn.style.display = 'none'; }
    }
  },
  resume: function (id) {
    if (!panels[id]) { this.mount(id); return; }
    g('editor-wrap', id).innerHTML = '<textarea id="tkfg-mce-' + id + '"></textarea>';
    tinymce.init(mceConfig(id, undefined, false));
    refreshMini(id);
  },
  suspend: function (id) {
    var ed = tinymce.get('tkfg-mce-' + id); if (ed) tinymce.remove(ed);
    var em = tinymce.get('tkfg-mce-min-' + id); if (em) tinymce.remove(em);
  },
  serialize: function (id) { return panels[id] ? buildPayload(id) : null; },

  // Mark this preset as the personal "Only me" override. Uses the live panel
  // config when mounted, otherwise the header data island — so it works even
  // for a collapsed, never-opened preset.
  localizeFromData: function (id) {
    var p = (panels[id] && panels[id]._mounted) ? buildPayload(id) : payloadFromData(panelData(id));
    p._preset = id;
    try {
      localStorage.setItem(LS_KEY, JSON.stringify(p));
      if (window.showToast) window.showToast(t('savedMsg', 'Settings saved.'), 'success');
    } catch (e) {}
    if (panels[id]) updateLocalBadge(id);
    window.dispatchEvent(new CustomEvent('tkfg:localChanged', { detail: { id: id } }));
  },

  clearLocal: function (id) { clearLocal(id); }
};

}());
