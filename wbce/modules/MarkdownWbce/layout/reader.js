$(function () {

    // ── Theme toggle (light/dark) ─────────────────────────────────────────────
    //
    // reader.htt already applies a saved choice before first paint (its own
    // small inline <script>, same 'mdr_theme' key) — this only wires up the
    // click handler and keeps highlight.js's theme stylesheet in sync, since
    // hljs has no CSS-variable hook of its own (its theme IS the stylesheet).
    var THEME_KEY   = 'mdr_theme';
    var $themeBtn   = $('#mdr-theme-toggle');
    var $hljsTheme  = $('#mdr-hljs-theme');
    // The two theme stylesheet URLs are carried on the <link> itself
    // (data-hljs-light / -dark), set by reader.htt — self-hosted now, so
    // there is no hard-coded CDN URL to keep in sync here.
    var HLJS_LIGHT  = $hljsTheme.data('hljs-light') || $hljsTheme.attr('href');
    var HLJS_DARK   = $hljsTheme.data('hljs-dark')  || HLJS_LIGHT;

    function currentIsDark() {
        var explicit = document.documentElement.getAttribute('data-theme');
        if (explicit === 'dark') return true;
        if (explicit === 'light') return false;
        return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    }

    function syncHljsTheme() {
        if (!$hljsTheme.length) return;
        $hljsTheme.attr('href', currentIsDark() ? HLJS_DARK : HLJS_LIGHT);
    }

    syncHljsTheme();

    $themeBtn.on('click', function () {
        var next = currentIsDark() ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', next);
        localStorage.setItem(THEME_KEY, next);
        syncHljsTheme();
    });

    // ── Sidebar collapse toggle ───────────────────────────────────────────────
    var $wrap        = $('#mdr-sidebar-wrap');
    var $toggleBtn   = $('#mdr-sidebar-toggle');
    var STORAGE_KEY  = 'mdr_sidebar_collapsed';
    var WIDTH_KEY    = 'mdr_sidebar_width';
    var MIN_W        = 160;

    function maxWidth() {
        return Math.max(MIN_W, Math.min(560, Math.round(window.innerWidth * 0.5)));
    }

    // Restore persisted state
    if (localStorage.getItem(STORAGE_KEY) === '1') {
        $wrap.addClass('mdr-sidebar-wrap--collapsed');
        $toggleBtn.attr('title', 'Sidebar aufklappen');
    }
    var savedW = parseInt(localStorage.getItem(WIDTH_KEY), 10);
    if (!isNaN(savedW)) {
        savedW = Math.max(MIN_W, Math.min(maxWidth(), savedW));
        document.documentElement.style.setProperty('--mdr-sidebar-w', savedW + 'px');
    }

    $toggleBtn.on('click', function () {
        var collapsed = $wrap.toggleClass('mdr-sidebar-wrap--collapsed')
                             .hasClass('mdr-sidebar-wrap--collapsed');
        $toggleBtn.attr('title', collapsed ? 'Sidebar aufklappen' : 'Sidebar zuklappen');
        localStorage.setItem(STORAGE_KEY, collapsed ? '1' : '0');
    });

    // ── Resize drag ───────────────────────────────────────────────────────────
    // Pointer Events + setPointerCapture(), the same pattern the FEE/VES shell
    // uses: capture routes every pointermove/up to the handle even when the
    // cursor is over the scrolling content — so the drag can never "stick"
    // after release. Width is a CSS var on <html> (--mdr-sidebar-w), persisted
    // to localStorage on release; double-click resets to the CSS default.
    var handleEl = document.getElementById('mdr-resize-handle');
    if (handleEl && window.PointerEvent) {
        handleEl.addEventListener('pointerdown', function (e) {
            if (e.button !== 0) { return; }
            e.preventDefault();
            handleEl.setPointerCapture(e.pointerId);
            var startX = e.clientX;
            var startW = $wrap[0].getBoundingClientRect().width;
            handleEl.classList.add('mdr-resizing');
            document.body.style.userSelect = 'none';
            document.body.style.cursor = 'col-resize';
            // Kill the width transition for the drag — otherwise the panel
            // lags ~120ms behind the cursor. Restored on release so
            // collapse/expand still animates.
            $wrap[0].style.transition = 'none';

            function onMove(ev) {
                var w = Math.max(MIN_W, Math.min(maxWidth(), startW + (ev.clientX - startX)));
                document.documentElement.style.setProperty('--mdr-sidebar-w', w + 'px');
            }
            function onUp(ev) {
                handleEl.releasePointerCapture(ev.pointerId);
                handleEl.removeEventListener('pointermove', onMove);
                handleEl.removeEventListener('pointerup', onUp);
                handleEl.removeEventListener('pointercancel', onUp);
                handleEl.classList.remove('mdr-resizing');
                document.body.style.userSelect = '';
                document.body.style.cursor = '';
                $wrap[0].style.transition = '';
                var w = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--mdr-sidebar-w'), 10);
                if (w) { try { localStorage.setItem(WIDTH_KEY, String(w)); } catch (err) { /* no storage */ } }
            }
            handleEl.addEventListener('pointermove', onMove);
            handleEl.addEventListener('pointerup', onUp);
            handleEl.addEventListener('pointercancel', onUp);
        });
        handleEl.addEventListener('dblclick', function () {
            document.documentElement.style.removeProperty('--mdr-sidebar-w');
            try { localStorage.removeItem(WIDTH_KEY); } catch (err) { /* no storage */ }
        });
    }

    // ── Scroll spy — highlight active TOC link ────────────────────────────────
    // .mdr-content is the scroll container now (the page itself never scrolls),
    // so compare each heading anchor's viewport position to the top of that
    // container plus a small offset — same idea as markdown-embed.js.
    var contentEl = document.getElementById('mdr-content');
    var OFFSET = 24;
    var $links = $('#mdr-sidebar a[href^="#"]');

    function getActive () {
        var line = contentEl.getBoundingClientRect().top + OFFSET;
        var active = $links.first();
        $links.each(function () {
            var id = decodeURIComponent(($(this).attr('href') || '').slice(1));
            var el = id && document.getElementById(id);
            if (el && el.getBoundingClientRect().top - line <= 0) { active = $(this); }
        });
        return active;
    }

    if (contentEl) {
        var spyTicking = false;
        contentEl.addEventListener('scroll', function () {
            if (spyTicking) { return; }
            spyTicking = true;
            requestAnimationFrame(function () {
                spyTicking = false;
                var $current = getActive();
                if (!$current.hasClass('mdr-nav--active')) {
                    $links.removeClass('mdr-nav--active');
                    $current.addClass('mdr-nav--active');
                }
            });
        }, { passive: true });
    }

    var $article = $('#mdr-article');

    // ── Internal MD links — rewrite to reader.php ─────────────────────────────
    //
    // href is relative to the DOC's own location, not to reader.php's own
    // URL (window.location.pathname is always .../MarkdownWbce/reader.php
    // regardless of which doc is open) — so "./CHANGELOG.md" or
    // "../docs/x.md" must resolve against document.body.dataset.docDir
    // (set server-side from the active doc's relPath), not the page URL.
    // Delegating ./ and ../ resolution to the URL API instead of hand-
    // rolling it handles every case (nested ../.., bare filenames, etc.)
    // the same way a browser would.
    var docDir = document.body.dataset.docDir || '';

    $article.on('click', 'a[href$=".md"]', function (e) {
        var href = $(this).attr('href');

        // Skip absolute external links
        if (/^https?:\/\//i.test(href)) return;

        e.preventDefault();

        var absHref = href.startsWith('/')
            ? href
            : new URL(href, window.location.origin + docDir + '/').pathname;

        var url = window.location.pathname
                + '?doc=' + encodeURIComponent(absHref)
                + '&title=' + encodeURIComponent($(this).text());

        window.location.href = url;
    });

    // ── Mobile header toggle ──────────────────────────────────────────────────
    $('.mdr-toggle').on('click', function () {
        $wrap.toggle();
    });

    if ($(window).width() < 900) {
        $wrap.hide();
    }

    $(window).on('resize', function () {
        if ($(window).width() >= 900) {
            $wrap.show();
        }
    });

});
