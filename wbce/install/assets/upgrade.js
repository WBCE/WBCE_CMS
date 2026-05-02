/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @file       install/assets/upgrade.js
 * @brief      Javascript for the install-script
 * @copyright  2026 WBCE CMS Project
 * @license    GNU/GPL 2  https://www.gnu.org/licenses/gpl-2.0.html
 */
/* ════════════════════════════════════════════════════════════
   upgrade.js — WBCE update.php streaming + animated log
   ════════════════════════════════════════════════════════════ */

(function () {
  'use strict';

  var TOTAL_PHASES = 4;  // db-tables, settings+modules, cleanup, reload-addons
  var ITEM_DELAY   = 38; // ms between log items

  // ── DOM refs ─────────────────────────────────────────────────────────────────
  var confirmCheck = document.getElementById('confirm-check');
  var btnUpdate    = document.getElementById('btn-update');
  var updateForm   = document.getElementById('update-form');
  var progressCard = document.getElementById('progress-card');
  var mainCard     = document.querySelector('.card');
  var logEl        = document.getElementById('update-log');
  var barFill      = document.getElementById('progress-fill');
  var barLabel     = document.getElementById('progress-label');
  var phaseLabel   = document.getElementById('phase-label');
  var actionsEl    = document.getElementById('update-actions');

  if (!logEl || !updateForm) return;

  // ── Enable button only when checkbox is ticked ────────────────────────────
  if (confirmCheck) {
    confirmCheck.addEventListener('change', function () {
      btnUpdate.disabled = !this.checked;
    });
  }

  // ── Intercept submit ──────────────────────────────────────────────────────
  updateForm.addEventListener('submit', function (e) {
    e.preventDefault();
    if (confirmCheck && !confirmCheck.checked) return;

    mainCard.style.transition = 'opacity .3s';
    mainCard.style.opacity    = '0';
    setTimeout(function () {
      mainCard.style.display    = 'none';
      progressCard.style.display = 'block';
      progressCard.style.opacity = '0';
      requestAnimationFrame(function () {
        progressCard.style.transition = 'opacity .3s';
        progressCard.style.opacity    = '1';
      });
    }, 320);

    setProgress(0, 'Starting update\u2026');
    streamUpdate();
  });

  // ── Stream ────────────────────────────────────────────────────────────────
  function streamUpdate() {
    var data = new FormData(updateForm);
    fetch('update.php', { method: 'POST', body: data })
      .then(function (r) {
        if (!r.ok) { enqueueHtml('<div class="err">\u2717 HTTP ' + r.status + '</div>'); return; }
        return readStream(r.body.getReader());
      })
      .then(onDone)
      .catch(function (err) {
        enqueueHtml('<div class="err">\u2717 ' + esc(err.message) + '</div>');
        onDone();
      });
  }

  // ── ReadableStream ────────────────────────────────────────────────────────
  var phaseCount = 0;
  var buffer     = '';
  var decoder    = new TextDecoder();

  function readStream(reader) {
    return reader.read().then(function (chunk) {
      if (chunk.done) return;
      buffer += decoder.decode(chunk.value, { stream: true });

      var safe = safeFlush(buffer);
      if (safe.flushed) {
        enqueueHtml(safe.html);
        buffer = safe.remainder;

        var seps = (safe.html.match(/class="sep"/g) || []).length;
        if (seps > 0) {
          phaseCount = Math.min(phaseCount + seps, TOTAL_PHASES);
          var pct    = Math.round((phaseCount / TOTAL_PHASES) * 95);
          var m      = safe.html.match(/class="sep"[^>]*>\u2500\u2500\s*([^<\u2500]+?)\s*\u2500\u2500/);
          setProgress(pct, m ? m[1] : '');
        }
      }
      return readStream(reader);
    });
  }

  function safeFlush(buf) {
    var last = buf.lastIndexOf('</div>');
    if (last === -1) return { flushed: false, html: '', remainder: buf };
    return { flushed: true, html: buf.slice(0, last + 6), remainder: buf.slice(last + 6) };
  }

  // ── Done ──────────────────────────────────────────────────────────────────
  function onDone() {
    if (buffer.trim()) enqueueHtml(buffer);
    buffer = '';

    tickToFull('Complete', function (logHtml) {
      var success = logHtml.includes('update complete') || logHtml.includes('update finished');
      var hasFail = logHtml.includes('class="err"') || logHtml.includes('class="log-err"');
      return success && !hasFail;
    });
  }

  function showActions(success) {
    if (!actionsEl) return;
    actionsEl.style.display = 'block';
    if (success) {
      actionsEl.innerHTML =
        '<p class="inst-done-msg inst-done-ok">' + (I18N.upgradeComplete || 'Update complete!') + '</p>' +
        '<a href="update.php" class="inst-btn inst-btn-sec">' + (I18N.runAgain || 'Run again') + '</a>' +
        (typeof ADMIN_URL !== 'undefined' && ADMIN_URL
          ? '<a href="' + esc(ADMIN_URL) + '/login/index.php" class="inst-btn inst-btn-primary">' + (I18N.loginToBackend || 'Login to Backend') + '</a>'
          : '');
    } else {
      actionsEl.innerHTML =
        '<p class="inst-done-msg inst-done-err">' + (I18N.updateFailed || 'Update had errors \u2014 check the log above.') + '</p>' +
        '<a href="update.php" class="inst-btn inst-btn-sec">' + (I18N.runAgain || 'Run again') + '</a>';
    }
  }

  // ── Progress bar ──────────────────────────────────────────────────────────
  function setProgress(pct, label) {
    if (barFill)  barFill.style.width   = pct + '%';
    if (barLabel) barLabel.textContent  = Math.round(pct) + '%';
    if (label && phaseLabel) phaseLabel.textContent = label;
  }

  /**
   * Animate the bar from its current position to 100%, paced so it arrives
   * roughly when the log queue finishes draining.
   * showActions() is called only after the bar reaches 100% AND queue is empty.
   */
  function tickToFull(doneLabel, successFn) {
    var current   = parseFloat(barFill ? barFill.style.width : '0') || 0;
    var queueTime = _queue.length * ITEM_DELAY + 400;
    var duration  = Math.max(queueTime, 600);
    var TICK_MS   = 40;
    var steps     = Math.ceil(duration / TICK_MS);
    var increment = (100 - current) / steps;
    var tick      = 0;

    function step() {
      tick++;
      var pct = Math.min(current + increment * tick, 100);
      if (barFill)  barFill.style.width  = pct.toFixed(1) + '%';
      if (barLabel) barLabel.textContent = Math.round(pct) + '%';

      if (pct < 100) { setTimeout(step, TICK_MS); return; }

      if (phaseLabel) phaseLabel.textContent = doneLabel;

      (function waitForQueue() {
        if (_queue.length > 0 || _draining) {
          setTimeout(waitForQueue, ITEM_DELAY * 2);
          return;
        }
        showActions(successFn(logEl.innerHTML.toLowerCase()));
      }());
    }

    step();
  }

  // ── Animated log queue ────────────────────────────────────────────────────
  var _queue    = [];
  var _draining = false;

  function enqueueHtml(html) {
    var re = /<div[^>]*>[\s\S]*?<\/div>/g;
    var match;
    while ((match = re.exec(html)) !== null) {
      _queue.push(match[0]);
    }
    if (!_draining) drainQueue();
  }

  function drainQueue() {
    if (_queue.length === 0) { _draining = false; return; }
    _draining = true;

    var item  = _queue.shift();
    var inner = document.createElement('div');
    inner.innerHTML = item;
    var el = inner.firstElementChild;

    if (el) {
      el.style.cssText = 'opacity:0;transform:translateY(4px);transition:opacity .18s ease,transform .18s ease';
      logEl.appendChild(el);
      requestAnimationFrame(function () {
        requestAnimationFrame(function () {
          el.style.opacity   = '1';
          el.style.transform = 'translateY(0)';
        });
      });
    } else {
      logEl.insertAdjacentHTML('beforeend', item);
    }

    logEl.scrollTop = logEl.scrollHeight;
    setTimeout(drainQueue, ITEM_DELAY);
  }

  // ── Helpers ───────────────────────────────────────────────────────────────
  function esc(s) {
    return String(s)
      .replace(/&/g, '&amp;').replace(/</g, '&lt;')
      .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }

}());