/* MarkdownWbce — layout/markdown-embed.js
 *
 * Three jobs for a MdReaderHelper::renderForEmbed() result shown inline in a
 * backend admin page:
 *
 *   1. Measure whatever the active backend theme puts in front of the page —
 *      the larger of `html { scroll-padding-top }` (wbce_flat_theme declares it
 *      for anchor-jump clearance) and the actual bottom edge of a full-width
 *      position:fixed element anchored at the top of the viewport.
 *   2. From that: raise `html { scroll-padding-top }` so anchor jumps clear the
 *      header (only ever raised, never lowered — a theme that declares more
 *      wins), and park the sticky `.mdr-embed-toc` sidebar just below it
 *      (--mdr-toc-top). The .h-anchor markers carry NO scroll-margin-top, so
 *      the two offsets can't stack and push every jump target too far down.
 *   3. Scroll spy — mark the current section's TOC link `a.mdr-nav--active`
 *      (the same class and look the reader popup uses).
 *
 * Loaded by MdReaderHelper::embedAssets(); safe to load on a page without an
 * .mdr-embed (it then does nothing) and safe to load more than once.
 */
(function () {
    'use strict';

    var headerPx = 0; // cached; refreshed by layout() on load + resize

    function topFixedBottom() {
        var max = 0;
        var all = document.body ? document.body.getElementsByTagName('*') : [];
        for (var i = 0; i < all.length; i++) {
            if (getComputedStyle(all[i]).position !== 'fixed') { continue; }
            var r = all[i].getBoundingClientRect();
            if (r.top <= 1 && r.width >= window.innerWidth * 0.5 && r.bottom > max) {
                max = r.bottom;
            }
        }
        return max;
    }

    function declaredScrollPad() {
        return parseFloat(getComputedStyle(document.documentElement).scrollPaddingTop) || 0;
    }

    function layout(wraps) {
        headerPx = Math.max(declaredScrollPad(), topFixedBottom(), 0);
        if (headerPx <= 0) { return; }

        // Anchor jumps land at `scroll-padding-top` from the viewport top —
        // make sure that clears the header. Raise only.
        if (declaredScrollPad() < headerPx) {
            document.documentElement.style.scrollPaddingTop = Math.round(headerPx) + 'px';
        }

        // Sticky TOC sits just under the header.
        if (headerPx > 24) {
            var px = (Math.round(headerPx) + 8) + 'px';
            for (var i = 0; i < wraps.length; i++) {
                wraps[i].style.setProperty('--mdr-toc-top', px);
            }
        }
    }

    /* Scroll spy for a single .mdr-embed block. */
    function initSpy(wrap) {
        var toc = wrap.querySelector('.mdr-embed-toc');
        var art = wrap.querySelector('.markdown-body');
        if (!toc || !art) { return; }

        var targets = [];
        var links = toc.querySelectorAll('a[href^="#"]');
        for (var i = 0; i < links.length; i++) {
            var id = decodeURIComponent(links[i].getAttribute('href').slice(1));
            var el = id ? document.getElementById(id) : null;
            if (el) { targets.push({ a: links[i], el: el }); }
        }
        if (!targets.length) { return; }

        var active = null;
        var ticking = false;

        function update() {
            ticking = false;
            // A heading is "current" once its anchor has scrolled up to just
            // past where an anchor jump would land it (scroll-padding-top),
            // plus a small tolerance so the highlight doesn't lag a section.
            var line = (declaredScrollPad() || headerPx) + 28;
            var cur = targets[0];
            for (var i = 0; i < targets.length; i++) {
                if (targets[i].el.getBoundingClientRect().top - line <= 0) {
                    cur = targets[i];
                } else {
                    break;
                }
            }
            if (cur.a === active) { return; }
            if (active) { active.classList.remove('mdr-nav--active'); }
            cur.a.classList.add('mdr-nav--active');
            active = cur.a;

            // Keep the active link visible inside a scrolling TOC — by nudging
            // the TOC's *own* scrollTop only. Never scrollIntoView(): that also
            // scrolls the window/ancestors, and a window scroll re-fires this
            // handler, which can re-pick the active link and loop the page into
            // a freeze.
            if (toc.scrollHeight > toc.clientHeight + 4) {
                var ar = cur.a.getBoundingClientRect();
                var tr = toc.getBoundingClientRect();
                if (ar.top < tr.top) {
                    toc.scrollTop -= (tr.top - ar.top) + 8;
                } else if (ar.bottom > tr.bottom) {
                    toc.scrollTop += (ar.bottom - tr.bottom) + 8;
                }
            }
        }

        function onScroll() {
            if (ticking) { return; }
            ticking = true;
            (window.requestAnimationFrame || window.setTimeout)(update, 0);
        }

        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', onScroll);
        update();
    }

    function apply() {
        var wraps = document.querySelectorAll('.mdr-embed');
        if (!wraps.length) { return; }
        layout(wraps);
        window.addEventListener('resize', function () { layout(wraps); });
        for (var i = 0; i < wraps.length; i++) {
            initSpy(wraps[i]);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', apply);
    } else {
        apply();
    }
})();
