/* MarkdownWbce — layout/highlight.js
 *
 * Syntax-highlights fenced code blocks in any rendered `.markdown-body` and
 * adds a small toolbar (language badge + copy button) to each. Shared by the
 * reader popup (reader.htt) and by inline embeds
 * (MdReaderHelper::renderForEmbed() consumers, e.g. the Asset Optimizer tool).
 *
 * Expects highlight.js (vendor/hljs/highlight.min.js) and a hljs theme
 * stylesheet to be loaded already. Safe to load more than once and safe when
 * hljs is missing (it simply does nothing).
 */
(function () {
    'use strict';

    var COPY_ICON =
        '<svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="currentColor">' +
        '<path fill-rule="evenodd" d="M14 12V2H4V0h12v12h-2zM0 4h12v12H0V4zm2 2v8h8V6H2z"/></svg>';

    function wrap(block) {
        if (window.hljs) {
            try { window.hljs.highlightElement(block); } catch (e) { /* unknown language */ }
        }

        var pre = block.parentElement;
        if (!pre || pre.tagName !== 'PRE' || pre.classList.contains('mdr-code-wrapped')) {
            return;
        }
        pre.classList.add('mdr-code-wrapped');

        var m    = block.className.match(/language-(\S+)/);
        var lang = m ? m[1] : '';
        if (lang) { pre.classList.add(lang + '-block'); }

        var toolbar = document.createElement('div');
        toolbar.className = 'mdr-code-toolbar';

        if (lang) {
            var badge = document.createElement('span');
            badge.className = 'mdr-code-lang';
            badge.textContent = lang;
            toolbar.appendChild(badge);
        }

        var copyBtn = document.createElement('button');
        copyBtn.type = 'button';
        copyBtn.className = 'mdr-code-copy';
        copyBtn.title = 'Copy code';
        copyBtn.setAttribute('aria-label', 'Copy code');
        copyBtn.innerHTML = COPY_ICON;
        copyBtn.addEventListener('click', function () {
            var text = block.textContent;

            function done() {
                copyBtn.classList.add('mdr-code-copy--done');
                setTimeout(function () { copyBtn.classList.remove('mdr-code-copy--done'); }, 1200);
            }
            function fallbackCopy() {
                var ta = document.createElement('textarea');
                ta.value = text;
                ta.style.position = 'fixed';
                ta.style.opacity = '0';
                document.body.appendChild(ta);
                ta.select();
                try { document.execCommand('copy'); done(); } catch (e) { /* noop */ }
                document.body.removeChild(ta);
            }

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(done, fallbackCopy);
            } else {
                fallbackCopy();
            }
        });
        toolbar.appendChild(copyBtn);

        pre.insertBefore(toolbar, pre.firstChild);
    }

    function run() {
        var blocks = document.querySelectorAll('.markdown-body pre code[class*="language-"]');
        for (var i = 0; i < blocks.length; i++) {
            wrap(blocks[i]);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', run);
    } else {
        run();
    }
})();
