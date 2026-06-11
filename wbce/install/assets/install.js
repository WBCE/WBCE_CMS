/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @file       install/assets/install.js
 * @brief      Javascript for the install-script
 * @copyright  2026 WBCE CMS Project
 * @license    GNU/GPL 2  https://www.gnu.org/licenses/gpl-2.0.html
 */
/* ════════════════════════════════════════════════════════════
   AJAX DB CONNECTION TEST
   ════════════════════════════════════════════════════════════ */
(function () {
  const btn       = document.getElementById('db-test-btn');
  const status    = document.getElementById('db-status');
  const testedFld = document.getElementById('db_tested');

  if (!btn || !status || !testedFld) return;

  // Reset test state whenever any DB field changes
  const fields = ['database_host', 'database_name', 'database_username', 'database_password'];
  fields.forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', resetTest);
  });

  function resetTest() {
    testedFld.value = '0';
    status.style.display = 'none';
    status.className = '';

    // Hide steps 4 + 5 until DB test succeeds again
    ['step4-card', 'step5-card'].forEach(function (id) {
      var el = document.getElementById(id);
      if (el) el.style.display = 'none';
    });

    // Reset button to initial "Test Connection" state
    btn.disabled = false;
    btn.classList.remove('loading');
    btn.innerHTML = `
      <span class="spinner"></span>
      <span class="btn-icon">⚡</span> ${I18N.btnTest || 'Test Connection'}
    `;
  }

  function showStatus(ok, msg) {
    status.className = ok ? 'ok' : 'fail';
    status.innerHTML = (ok ? '✔ ' : '✖ ') + msg;
    status.style.display = 'block';
  }

  btn.addEventListener('click', async () => {
    const host = document.getElementById('database_host').value.trim();
    const name = document.getElementById('database_name').value.trim();
    const user = document.getElementById('database_username').value.trim();
    const pass = document.getElementById('database_password').value;

    if (!host || !name || !user) {
      showStatus(false, I18N.required + ' (host / database name / username)');
      return;
    }

    // Set loading state
    btn.disabled = true;
    btn.classList.add('loading');
    btn.innerHTML = `
      <span class="spinner"></span>
      <span class="btn-icon"></span> ${I18N.dbTesting || 'Testing...'}
    `;

    status.style.display = 'none';

    // Build POST body with the values already read above (correct IDs)
    const body = new URLSearchParams({
      db_host: host,
      db_name: name,
      db_user: user,
      db_pass: pass
    });

    try {
      // lang is passed as a GET param; db_conn_check.php reads $_GET['lang']
      const res = await fetch('db_conn_check.php?lang=' + encodeURIComponent(I18N.currLang || ''), {
        method:  'POST',
        headers: {
          'Accept':           'application/json',
          'X-Requested-With': 'XMLHttpRequest'
          // Content-Type is set automatically for URLSearchParams
        },
        body
      });

      if (!res.ok) {
        throw new Error('HTTP ' + res.status);
      }

      const data = await res.json();

      showStatus(data.ok, data.message);
      testedFld.value = data.ok ? '1' : '0';

      // Reveal steps 4 + 5 only on success
      if (data.ok) {
        ['step4-card', 'step5-card'].forEach(function (id) {
          var el = document.getElementById(id);
          if (el) {
            el.style.display = 'block';
            el.style.animation = 'fadeUp .7s ease both';
          }
        });
        // Scroll just a little way down so the user notices the new cards
        setTimeout(function () {
          window.scrollBy({ top: 180, behavior: 'smooth' });
        }, 200);
      }

    } catch (err) {
      showStatus(false, 'Request failed — ' + (err.message || 'Unknown error'));
      testedFld.value = '0';
    } finally {
      btn.disabled = false;
      btn.classList.remove('loading');
      btn.innerHTML = `
        <span class="spinner"></span>
        <span class="btn-icon">⚡</span> ${I18N.dbRetest || 'Test again'}
      `;
    }
  });

  // Initial reset
  resetTest();
})();


/* ════════════════════════════════════════════════════════════
   FORM VALIDATION
   Strategy:
    · novalidate on <form> so we control the UX
    · On submit: add .was-validated (enables :invalid CSS),
      run custom checks, show setCustomValidity messages
    · Browser shows native tooltip on first invalid field
   ════════════════════════════════════════════════════════════ */
(function () {
  const form = document.getElementById('install-form');
  if (!form) return;

  const pw1  = document.getElementById('admin_password');
  const pw2  = document.getElementById('admin_repassword');

  // Live password-match feedback
  function checkPwMatch() {
    if (!pw1 || !pw2) return;
    if (pw2.value && pw1.value !== pw2.value) {
      pw2.setCustomValidity(I18N.pwMismatch);
    } else {
      pw2.setCustomValidity('');
    }
  }
  if (pw1) pw1.addEventListener('input', checkPwMatch);
  if (pw2) pw2.addEventListener('input', checkPwMatch);

  form.addEventListener('submit', function (e) {
    // ── Custom checks before native validation ─────────
    checkPwMatch();

    // DB test gate (only when install button is enabled)
    const installBtn = document.getElementById('btn-install');
    if (installBtn && !installBtn.disabled) {
      const tested = document.getElementById('db_tested');
      if (tested && tested.value !== '1') {
        e.preventDefault();
        const dbStatus = document.getElementById('db-status');
        dbStatus.className = 'fail';
        dbStatus.innerHTML = '✖ ' + I18N.dbUntested;
        // Scroll to DB section
        document.getElementById('database_host')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
      }
    }

    // ── Native HTML5 validation ────────────────────────
    form.classList.add('was-validated');
    if (!form.checkValidity()) {
      e.preventDefault();
      // Focus the first invalid field and let the browser show its tooltip
      const first = form.querySelector(':invalid');
      if (first) {
        first.focus();
        first.reportValidity();
      }
    }
  });
})();


/* ── Legacy helpers (unchanged) ───────────────────────────── */
function confirm_link(message, url) {
  if (confirm(message)) location.href = url;
}
function change_os(type) {
  var perms = document.getElementById('file_perms_box');
  if (type === 'linux') {
    document.getElementById('operating_system_linux').checked  = true;
    document.getElementById('operating_system_windows').checked = false;
    if (perms) perms.style.display = 'block';
  } else {
    document.getElementById('operating_system_linux').checked  = false;
    document.getElementById('operating_system_windows').checked = true;
    if (perms) perms.style.display = 'none';
  }
}