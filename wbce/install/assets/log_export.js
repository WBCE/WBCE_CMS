/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @file       install/assets/log_export.js
 * @brief      "Download log" button for the install and update progress logs.
 *             Purely client-side: the streamed log is already in the DOM, so
 *             the text is rebuilt from it and handed to the browser as a .txt
 *             download. Nothing is written on the server.
 * @copyright  2026 WBCE CMS Project
 * @license    GNU/GPL 2  https://www.gnu.org/licenses/gpl-2.0.html
 */
(function (window, document) {
  'use strict';

  var cfg = null;

  function pad(n) { return n < 10 ? '0' + n : '' + n; }

  function stamp(d) {
    return d.getFullYear() + pad(d.getMonth() + 1) + pad(d.getDate())
         + '_' + pad(d.getHours()) + pad(d.getMinutes());
  }

  /**
   * Rebuilds the log as plain text, one line per streamed <div>.
   *
   * The status icons (✓ ✗ ⚠ ──) are part of the text content already, so no
   * class-to-marker mapping is needed — only the hidden .install-meta marker
   * install_save.php streams has to be skipped.
   */
  function buildText() {
    var logEl = document.querySelector(cfg.logSelector);
    if (!logEl) return '';

    var lines = [];
    Array.prototype.forEach.call(logEl.children, function (el) {
      if (el.classList.contains('install-meta')) return;
      var txt = (el.innerText || el.textContent || '').replace(/\s+$/, '');
      if (txt !== '') lines.push(txt);
    });

    // Support cases usually arrive without any of this context, so put it on top.
    var head = [
      cfg.title,
      'Date:    ' + new Date().toString(),
      'Version: ' + (cfg.version || 'unknown'),
      'URL:     ' + window.location.href,
      'Browser: ' + navigator.userAgent,
      new Array(61).join('-')
    ];

    // CRLF so the file opens correctly in Windows Notepad.
    return head.concat(lines).join('\r\n') + '\r\n';
  }

  function download() {
    var text = buildText();
    if (text === '') return;

    var blob = new Blob([text], { type: 'text/plain;charset=utf-8' });
    var url  = URL.createObjectURL(blob);
    var a    = document.createElement('a');

    a.href     = url;
    a.download = cfg.filePrefix + '_' + stamp(new Date()) + '.txt';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);

    // Revoke late — Firefox needs the URL to still resolve when the click is handled.
    setTimeout(function () { URL.revokeObjectURL(url); }, 1000);
  }

  function escHtml(s) {
    return String(s).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
  }

  window.WbceLogExport = {
    /**
     * @param {object} options  logSelector, filePrefix, title, label, version
     */
    init: function (options) {
      cfg = options;
      // Delegated, because showActions() rebuilds its innerHTML after this runs.
      document.addEventListener('click', function (e) {
        var btn = e.target.closest ? e.target.closest('[data-wbce-log-export]') : null;
        if (!btn) return;
        e.preventDefault();
        download();
      });
    },

    /** Markup for the button — inserted by showActions() in both log views. */
    buttonHtml: function () {
      if (!cfg) return '';
      return '<button type="button" class="inst-btn inst-btn-sec" data-wbce-log-export>'
           + '\u2b07 ' + escHtml(cfg.label) + '</button>';
    }
  };

}(window, document));
