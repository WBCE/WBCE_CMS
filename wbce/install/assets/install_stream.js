/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @file       install/assets/upgrade.js
 * @brief      WBCE Installer — streaming progress handler for the install-script
 * @copyright  2026 WBCE CMS Project
 * @license    GNU/GPL 2  https://www.gnu.org/licenses/gpl-2.0.html
 */

(function () {
  'use strict';

  const TOTAL_PHASES = 8;

  // ── DOM references ──────────────────────────────────────────────────────────
  const form          = document.getElementById('install-form');
  const btnInstall    = document.getElementById('btn-install');
  const progressCard  = document.getElementById('progress-card');
  const logEl         = document.getElementById('install-log');
  const barFill       = document.getElementById('progress-fill');
  const barLabel      = document.getElementById('progress-label');
  const phaseLabel    = document.getElementById('phase-label');
  const actionsEl     = document.getElementById('install-actions');

  if (!form || !btnInstall || !progressCard || !logEl) return;

  // ── Hash Handler: Zeige Progress-Card direkt bei #progress-card ─────────────
  function handleHashChange() {
    if (window.location.hash === '#progress-card') {
      // Alle normalen Karten ausblenden
      document.querySelectorAll('.card:not(#progress-card)').forEach(card => {
        card.style.transition = 'opacity .4s ease';
        card.style.opacity = '0';
        setTimeout(() => { card.style.display = 'none'; }, 450);
      });

      // Progress-Card einblenden
      progressCard.style.display = 'block';
      requestAnimationFrame(() => {
        progressCard.style.transition = 'opacity .4s ease';
        progressCard.style.opacity = '1';
      });

      // Falls die Installation noch nicht läuft → starten
      if (logEl.children.length === 0 && !btnInstall.disabled) {
        setTimeout(() => {
          startInstall();
        }, 100);
      }
    }
  }

  // Hash-Listener + initialer Check
  window.addEventListener('hashchange', handleHashChange);
  window.addEventListener('load', handleHashChange);

  // ── Start Install ───────────────────────────────────────────────────────────
  function startInstall() {
    window.location.hash = 'progress-card';   // Hash sicherstellen

    btnInstall.disabled = true;
    btnInstall.value = I18N.installing || 'Installing…';

    // Alle Karten (inkl. Progress, falls nötig) vorbereiten
    document.querySelectorAll('.card').forEach(c => {
      if (c.id !== 'progress-card') {
        c.style.transition = 'opacity .3s, max-height .4s';
        c.style.opacity = '0';
        setTimeout(() => { c.style.display = 'none'; }, 350);
      }
    });

    form.querySelector('.warningbox') && (form.querySelector('.warningbox').style.display = 'none');

    // Progress-Card sicher anzeigen
    setTimeout(() => {
      progressCard.style.display = 'block';
      progressCard.style.opacity = '0';
      requestAnimationFrame(() => {
        progressCard.style.transition = 'opacity .4s';
        progressCard.style.opacity = '1';
      });
    }, 400);

    setProgress(0, I18N.phaseStarting || 'Starting…');
    streamInstall();
  }

  // ── Button Click Handler ────────────────────────────────────────────────────
  btnInstall.addEventListener('click', function (e) {
    e.preventDefault();

    // Client-seitige Validierung
    const p1 = document.getElementById('admin_password');
    const p2 = document.getElementById('admin_repassword');
    if (p1 && p2 && p1.value !== p2.value) {
      alert(I18N.pwMismatch || 'Passwords do not match.');
      p2.focus();
      return;
    }

    const dbTested = document.getElementById('db_tested');
    if (dbTested && dbTested.value !== '1') {
      alert(I18N.dbUntested || 'Please test the database connection first.');
      document.getElementById('database_host')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
      return;
    }

    startInstall();
  });

  // ── Fetch + Stream (unverändert) ────────────────────────────────────────────
  function streamInstall() {
    const data = new FormData(form);

    fetch(form.action, { method: 'POST', body: data })
      .then(function (response) {
        if (!response.ok) {
          appendRaw('<div class="err">✗ HTTP ' + response.status + '</div>');
          return;
        }
        return readStream(response.body.getReader());
      })
      .then(onStreamDone)
      .catch(function (err) {
        appendRaw('<div class="err">✗ ' + escHtml(err.message) + '</div>');
        onStreamDone();
      });
  }

  // ── ReadableStream reader ───────────────────────────────────────────────────

  var phaseCount   = 0;
  var buffer       = '';
  var decoder      = new TextDecoder();

  function readStream(reader) {
    return reader.read().then(function (chunk) {
      if (chunk.done) return;

      buffer += decoder.decode(chunk.value, { stream: true });

      // Flush complete HTML tags/lines from buffer to log
      var safe = safeFlush(buffer);
      if (safe.flushed) {
        appendRaw(safe.html);
        buffer = safe.remainder;
      }

      // Count phase separators for progress bar
      var seps = (safe.html.match(/class="sep"/g) || []).length;
      if (seps > 0) {
        phaseCount = Math.min(phaseCount + seps, TOTAL_PHASES);
        var pct = Math.round((phaseCount / TOTAL_PHASES) * 95); // cap at 95% until done

        // Extract phase label from the sep div text
        var phaseMatch = safe.html.match(/class="sep"[^>]*>──\s*([^<─]+?)\s*──/);
        var phaseTxt   = phaseMatch ? phaseMatch[1] : '';
        setProgress(pct, phaseTxt);
      }

      logEl.scrollTop = logEl.scrollHeight;
      return readStream(reader);
    });
  }

  // Flush everything up to the last complete closing tag
  function safeFlush(buf) {
    // Find last complete </div> or </script> so we don't split a tag
    var lastClose = buf.lastIndexOf('</div>');
    if (lastClose === -1) return { flushed: false, html: '', remainder: buf };
    var end = lastClose + 6; // length of '</div>'
    return { flushed: true, html: buf.slice(0, end), remainder: buf.slice(end) };
  }

  // ── Stream done ─────────────────────────────────────────────────────────────

  function onStreamDone() {
    // Flush any remaining buffer
    if (buffer.trim()) appendRaw(buffer);
    buffer = '';

    // Tick bar smoothly to 100% over the remaining queue drain time,
    // then show actions only after both bar and queue are finished.
    tickToFull(I18N.phaseDone || 'Complete', function (logHtml) {
      var success  = logHtml.includes('installation complete') || logHtml.includes('━━━');
      var hasFatal = logHtml.includes('class="err"') || logHtml.includes('class="log-err"');
      return success && !hasFatal;
    });
  }

  // ── Progress bar ────────────────────────────────────────────────────────────

  function setProgress(pct, label) {
    barFill.style.width  = pct + '%';
    barLabel.textContent = Math.round(pct) + '%';
    if (label) phaseLabel.textContent = label;
  }

  /**
   * Animate the progress bar from its current position to 100%, paced to
   * roughly match the remaining queue drain time.  Calls showActions() only
   * after the bar reaches 100% AND the animation queue is empty.
   *
   * @param {string}   doneLabel  Label shown when bar hits 100%
   * @param {function} successFn  fn(logHtml) → bool — determines success state
   */
  function tickToFull(doneLabel, successFn) {
    var current   = parseFloat(barFill.style.width) || 0;
    // Estimate how long the queue will take; add 400ms buffer for final items
    var queueTime = _queue.length * ITEM_DELAY + 400;
    var duration  = Math.max(queueTime, 600);  // at least 600ms tick
    var TICK_MS   = 40;
    var steps     = Math.ceil(duration / TICK_MS);
    var increment = (100 - current) / steps;
    var tick      = 0;

    function step() {
      tick++;
      var pct = Math.min(current + increment * tick, 100);
      barFill.style.width  = pct.toFixed(1) + '%';
      barLabel.textContent = Math.round(pct) + '%';

      if (pct < 100) {
        setTimeout(step, TICK_MS);
        return;
      }

      // Bar reached 100% — show label, then wait for queue
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

  // ── Success / failure actions ────────────────────────────────────────────────

  function showActions(success) {
    if (!actionsEl) return;

    // Try to extract WB_URL from the log (the streaming PHP echoes the links)
    // Fallback: read from a hidden field the PHP wrote
    var urlField = document.getElementById('install-wb-url');
    var adminField = document.getElementById('install-admin-url');
    var wbUrl    = urlField    ? urlField.value    : '';
    var adminUrl = adminField  ? adminField.value  : '';

    if (success) {
      actionsEl.innerHTML =
        '<div class="inst-done"><p class="inst-done-msg">' + escHtml(I18N.installSuccess || 'Installation complete!') + '</p>' +
        (adminUrl ? '<a href="' + escHtml(adminUrl) + '/login/index.php" class="inst-btn inst-btn-sec">' + escHtml(I18N.goAdmin || 'Go to Admin Login') + '</a>' : '') +
        '</div>';
    } else {
      actionsEl.innerHTML =
        '<p class="inst-done-msg inst-done-err">' + escHtml(I18N.installFailed || 'Installation failed — see errors above.') + '</p>' +
        '<a href="index.php" class="inst-btn inst-btn-sec">' + escHtml(I18N.tryAgain || '← Try again') + '</a>';
    }
    actionsEl.style.display = 'block';
  }

  // ── Animated log queue ──────────────────────────────────────────────────────
  // Items arrive fast (especially on localhost) but we animate them in one-by-one.
  // Each div is queued and displayed with a short delay for a "live" feel.

  var _queue    = [];        // pending HTML strings (one per <div>)
  var _draining = false;
  var ITEM_DELAY = 38;       // ms between items — adjust for taste

  function enqueueHtml(html) {
    // Split incoming HTML into individual <div>…</div> items
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

    var item = _queue.shift();
    var el   = document.createElement('div');
    el.style.cssText = 'opacity:0;transform:translateY(4px);transition:opacity .18s ease,transform .18s ease';
    el.innerHTML     = item;

    // Unwrap: we want the inner div, not a wrapper
    var inner = el.firstElementChild;
    if (inner) {
      inner.style.cssText = 'opacity:0;transform:translateY(4px);transition:opacity .18s ease,transform .18s ease';
      logEl.appendChild(inner);
      requestAnimationFrame(function() {
        requestAnimationFrame(function() {
          inner.style.opacity   = '1';
          inner.style.transform = 'translateY(0)';
        });
      });
    } else {
      logEl.insertAdjacentHTML('beforeend', item);
    }

    logEl.scrollTop = logEl.scrollHeight;
    setTimeout(drainQueue, ITEM_DELAY);
  }

  function appendRaw(html) {
    enqueueHtml(html);
  }

  function escHtml(s) {
    return String(s)
      .replace(/&/g, '&amp;').replace(/</g, '&lt;')
      .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }

}());