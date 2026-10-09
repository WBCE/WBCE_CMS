/* asset_optimizer — backend.js
 * - Bundle inspector: click a bundle row to expand / collapse its source rows.
 * - Cache-busting: while the front-end switch is on, the backend switch follows
 *   it live (forced on + read-only); it frees up when the front end goes off.
 * (The doc-tab sticky-TOC offset lives in MarkdownWbce/layout/markdown-embed.js.)
 */
(function () {
    'use strict';

    function initBundleInspector() {
        var groups = document.querySelectorAll('.asset-optimizer-tool tr.ao-grp');
        for (var i = 0; i < groups.length; i++) {
            (function (row) {
                if (row.hasAttribute('data-nosrc')) { return; }
                row.addEventListener('click', function () {
                    var open = row.classList.toggle('open');
                    var src = document.querySelectorAll(
                        '.asset-optimizer-tool tr.ao-src[data-src="' + row.getAttribute('data-grp') + '"]'
                    );
                    for (var j = 0; j < src.length; j++) {
                        src[j].classList.toggle('show', open);
                    }
                });
            })(groups[i]);
        }
    }

    // While front-end cache busting is on, the backend switch is forced on and
    // disabled; turning the front end off frees it again. Mirrors the runtime
    // rule in AssetQueue::cacheBustingEnabled() and the server-rendered state.
    function initBustFollow() {
        var fe   = document.getElementById('ao_opf_assets_cache_busting');
        var be   = document.getElementById('ao_opf_assets_cache_busting_be');
        var note = document.querySelector('.asset-optimizer-tool .ao-follow-note');
        // No follow note ⇒ the backend switch is config.php-pinned; leave it be.
        if (!fe || !be || !note) { return; }

        function sync() {
            if (fe.checked) {
                be.checked  = true;
                be.disabled = true;
                note.classList.remove('is-hidden');
            } else {
                be.disabled = false;
                note.classList.add('is-hidden');
            }
        }
        fe.addEventListener('change', sync);
        sync();
    }

    // Cache directory: the field is prefilled read-only with the resolved
    // default path. "Set a custom directory" unlocks it, clears it, and reveals
    // the help text.
    function initDirOverride() {
        var wrap = document.querySelector('.asset-optimizer-tool .ao-dir');
        var btn  = document.getElementById('ao-dir-edit');
        var inp  = document.getElementById('minify_assets_dir');
        if (!wrap || !btn || !inp) { return; }
        btn.addEventListener('click', function () {
            inp.readOnly = false;
            inp.value = '';
            wrap.classList.add('is-editing');
            btn.parentNode.removeChild(btn);
            inp.focus();
        });
    }

    function init() {
        initBundleInspector();
        initBustFollow();
        initDirOverride();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
