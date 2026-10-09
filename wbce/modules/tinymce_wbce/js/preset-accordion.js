/**
 * tinymce_wbce — js/preset-accordion.js
 * Accordion controller for the preset list.
 *
 *  - One panel open at a time.
 *  - First open lazy-loads the panel body via HTMX (hx-trigger="tkfg:load");
 *    on htmx:afterSwap the body is mounted (TkfgPanel.mount).
 *  - Re-open resumes the panel (re-boots the editor); collapse suspends it.
 *  - Header badges: [Default] persists via ?action=set_default; [Only me] toggles
 *    the personal localStorage override (TkfgPanel.localizeFromData / clearLocal).
 *
 * @license GNU GPL2
 */
(function () {
  var C   = window.TKFG_CONFIG || {};
  var I18N = window.TKFG_I18N || {};
  var acc = document.getElementById('tmce-preset-accordion');
  if (!acc) return;

  var openId = null;

  function preset(id)  { return acc.querySelector('.tmce-preset[data-preset="' + id + '"]'); }
  function headEl(id)  { var p = preset(id); return p ? p.querySelector('.tmce-preset-head') : null; }
  function bodyEl(id)  { return document.getElementById('tkfg-panel-' + id); }
  function loaded(id)  { var b = bodyEl(id); return !!(b && b.getAttribute('data-loaded') === '1'); }

  function open(id) {
    if (openId && openId !== id) close(openId);
    var head = headEl(id), body = bodyEl(id), p = preset(id);
    if (!head || !body) return;
    body.hidden = false;
    head.setAttribute('aria-expanded', 'true');
    var chev = head.querySelector('.tmce-chev'); if (chev) chev.textContent = '▾';
    if (p) p.classList.add('open');
    openId = id;
    if (loaded(id)) {
      window.TkfgPanel.resume(id);
    } else {
      head.dispatchEvent(new CustomEvent('tkfg:load')); // htmx fetches → afterSwap mounts
    }
  }

  function close(id) {
    var head = headEl(id), body = bodyEl(id), p = preset(id);
    if (!head || !body) return;
    if (loaded(id) && window.TkfgPanel) window.TkfgPanel.suspend(id);
    body.hidden = true;
    head.setAttribute('aria-expanded', 'false');
    var chev = head.querySelector('.tmce-chev'); if (chev) chev.textContent = '▸';
    if (p) p.classList.remove('open');
    if (openId === id) openId = null;
  }

  function toggle(id) { if (openId === id) close(id); else open(id); }

  // Mount the panel once its body has been swapped in by HTMX
  document.body.addEventListener('htmx:afterSwap', function (e) {
    var tgt = e.target;
    if (tgt && tgt.classList && tgt.classList.contains('tmce-panel-body')) {
      tgt.setAttribute('data-loaded', '1');
      var id = tgt.id.replace('tkfg-panel-', '');
      if (window.TkfgPanel) window.TkfgPanel.mount(id);
    }
  });

  // Header click (badges handled separately)
  acc.addEventListener('click', function (e) {
    var badge = e.target.closest('.tmce-badge');
    if (badge) { e.stopPropagation(); onBadge(badge); return; }
    var head = e.target.closest('.tmce-preset-head');
    if (head) { toggle(head.closest('.tmce-preset').getAttribute('data-preset')); }
  });

  // Keyboard: Enter/Space toggles the focused header
  acc.addEventListener('keydown', function (e) {
    var head = e.target.closest && e.target.closest('.tmce-preset-head');
    if (head && (e.key === 'Enter' || e.key === ' ')) {
      e.preventDefault();
      toggle(head.closest('.tmce-preset').getAttribute('data-preset'));
    }
  });

  // ── Badges ──
  function onBadge(badge) {
    var id  = badge.getAttribute('data-preset');
    var act = badge.getAttribute('data-act');
    if (act === 'default') setDefault(id);
    else if (act === 'local') toggleLocal(id);
    else if (act === 'rename') renamePreset(id);
    else if (act === 'delete') deletePreset(id);
    else if (act === 'reset')  resetPreset(id);
  }

  function nameEl(id) { var p = preset(id); return p ? p.querySelector('.tmce-preset-name') : null; }
  function toast(msg, type) { if (window.showToast) window.showToast(msg, type); }
  function postAction(action, id, extra) {
    var fd = new FormData(); fd.append('id', id);
    if (extra) { Object.keys(extra).forEach(function (k) { fd.append(k, extra[k]); }); }
    return fetch(C.toolUrl + '&action=' + action, { method: 'POST', body: fd, credentials: 'same-origin' })
      .then(function (r) { return r.json().catch(function () { return null; }); });
  }

  // Rename = change the display label only (the id is an API key modules use).
  function renamePreset(id) {
    var el = nameEl(id), cur = el ? el.textContent.trim() : '';
    var next = window.prompt(I18N.renamePrompt || 'New name:', cur);
    if (next === null) return;
    next = next.trim(); if (!next) return;
    postAction('rename_preset', id, { label: next }).then(function (res) {
      if (res && res.success) { if (el) el.textContent = res.label; toast(I18N.savedMsg || 'Saved', 'success'); }
      else toast(I18N.saveError || 'Error', 'error');
    });
  }
  function deletePreset(id) {
    if (!window.confirm(I18N.deleteConfirm || 'Delete this preset?')) return;
    postAction('delete_preset', id).then(function (res) {
      if (res && res.success) window.location.reload(); else toast(I18N.saveError || 'Error', 'error');
    });
  }
  function resetPreset(id) {
    if (!window.confirm(I18N.resetConfirm || 'Reset this preset to defaults?')) return;
    postAction('reset_preset', id).then(function (res) {
      if (res && res.success) window.location.reload(); else toast(I18N.saveError || 'Error', 'error');
    });
  }

  function setDefault(id) {
    acc.querySelectorAll('.tmce-badge-default').forEach(function (b) {
      b.classList.toggle('on', b.getAttribute('data-preset') === id);
    });
    var fd = new FormData(); fd.append('id', id);
    fetch(C.toolUrl + '&action=set_default', { method: 'POST', body: fd })
      .then(function (r) { return r.json(); })
      .then(function () { if (window.showToast) window.showToast(I18N.defaultSet || 'Default preset updated.', 'success'); })
      .catch(function () {});
  }

  function localPresetId() {
    try { var raw = localStorage.getItem('tinymce_wbce_user_cfg'); if (!raw) return null; return JSON.parse(raw)._preset || null; }
    catch (e) { return null; }
  }

  function refreshLocalBadges() {
    var cur = localPresetId();
    acc.querySelectorAll('.tmce-badge-local').forEach(function (b) {
      b.classList.toggle('on', b.getAttribute('data-preset') === cur);
    });
  }

  function toggleLocal(id) {
    if (!window.TkfgPanel) return;
    if (localPresetId() === id) window.TkfgPanel.clearLocal(id);
    else                        window.TkfgPanel.localizeFromData(id);
    refreshLocalBadges();
  }

  window.addEventListener('tkfg:localChanged', refreshLocalBadges);
  refreshLocalBadges();
}());
