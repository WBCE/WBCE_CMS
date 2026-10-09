/* layout/filetree.js - visual FileTree / PageTree renderer
 * Vendored from modules/tiptap_editor/assets/filetree.js (same author) so
 * MarkdownWbce's reader renders ```file-tree and ```page-tree blocks
 * identically to the TipTap editor, without a hard runtime dependency on
 * tiptap_editor being installed. Keep in sync manually if the original
 * changes - EXCEPT enhance()'s toolbar (badge + copy button), which
 * deliberately diverges from the original (which has none): it reuses the
 * same .mdr-code-toolbar/.mdr-code-lang/.mdr-code-copy classes and SVG icon
 * as the reader's regular code-block toolbar (see layout/reader.htt), badged
 * "FileTree"/"PageTree" instead of a real language, so both toolbars
 * look and behave identically in the rendered output.
 *
 * The <pre class="file-tree"> / <pre class="page-tree"> markup comes from
 * ParsedownWbce::blockFencedCode() special-casing those two language tags -
 * see modules/MarkdownWbce/Parsedown/ParsedownWbce.php.
 *
 * Two entry points:
 *   window.WbceFileTree.render(text, opts) — pure function, returns a detached
 *       .file-tree-container element for the given ASCII tree. Used by the
 *       TipTap node views (live preview inside the editor) and by enhance().
 *   window.WbceFileTree.enhance(root)      — replaces every
 *       <pre class="file-tree">/<pre class="page-tree"> under `root` with the
 *       rendered tree. Runs automatically on load for the whole document.
 *
 * opts.kind: 'file' (default) picks icons from the file extension,
 *            'page' renders page/page-group icons instead, and understands the
 *            page visibility markers {public} {hidden} {private} {registered}
 *            {none} (they set the row icon and are stripped from the text).
 *
 * Inline markers work in both kinds and are replaced where they stand:
 *            {menulink} — see INLINE_ICONS.
 *
 * enhance() skips anything inside a .ProseMirror: there the node views own the
 * DOM and replacing nodes underneath ProseMirror would corrupt its mapping.
 */
(function () {
    'use strict';

    var FILE_RE   = /^([a-zA-Z0-9_\-\.]+\.(js|css|php|txt|json|html|md|sql|woff2|woff|ttf|otf|eot))(\s.*|$)/i;
    var FOLDER_RE = /^([a-zA-Z0-9_\-\.][a-zA-Z0-9_\-\.\/]*\/)(\s.*|$)/;
    // Comment start in a PAGE tree — an explicit marker only, never a space.
    var COMMENT_RE = /\s*(\/\/|#|\/\*)/;
    // Page visibility marker, e.g. "Blog {private}" — the five states WBCE
    // knows for a page. Recognised in page trees only; stripped before the
    // name/comment split, so it never shows up as text.
    var STATUS_RE = /\s*\{(public|hidden|private|registered|none)\}/i;

    /* Inline icons: a marker is replaced by an icon AT ITS POSITION in the text,
     * unlike the visibility markers above, which set the row's leading icon and
     * disappear. One entry per marker — add a token here and it works in both
     * tree kinds, in the name as well as in the comment. Icons are drawn in
     * currentColor, so they take the colour and size of the surrounding text. */
    var INLINE_ICONS = {
        menulink: {
            title: 'Menulink',
            glyph: '\uD83D\uDD17',   // 🔗 U+1F517 LINK SYMBOL
            viewBox: '0 0 455 453',
            path: 'M330.497,449.907C291.655,447.709 286.388,438.057 241.198,392.804C222.662,374.241 188.78,348.493 197.008,301.415C203.556,263.955 223.338,260.881 218.276,255.717C197.754,234.781 197.92,233.796 195.419,234.089C195.024,234.136 175.719,254.712 147.473,257.012C103.751,260.573 85.036,236.382 61.835,213.166C28.864,180.173 6.122,161.615 3.949,131.454C1.761,101.087 9.492,82.522 31.047,62.045C62,32.637 76.057,9.344 109.54,4.798C156.724,-1.608 173.427,22.26 210.54,59.46C232.99,81.963 259.186,102.528 256.976,141.523C254.323,188.337 228.553,191.953 234.901,198.093C239.431,202.474 255.888,219.26 256.516,219.467C259.359,220.408 258.819,218.72 261.823,215.836C278.921,199.419 321.204,184.035 358.778,210.107C368.988,217.191 427.85,275.943 435.866,287.251C438.056,290.341 450.425,307.789 449.731,332.509C448.726,368.316 427.726,385.879 422.285,391.284C374.062,439.193 369.488,447.876 330.497,449.907ZM291.549,313.909C264.776,315.956 261.015,290.568 255.874,295.903C244.409,307.8 247.26,323.382 253.976,331.077C256.376,333.827 312.121,390.117 314.832,392.081C325.341,399.692 340.646,398.525 350.198,389.184C391.286,349.005 391.462,349.053 393.676,344.589C404.304,323.155 390.189,313.772 366.707,290.293C329.345,252.936 324.133,243.528 303.61,250.786C301.975,251.364 295.098,255.668 295.123,257.516C295.152,259.682 327.844,281.541 307.339,305.365C301.818,311.779 292.955,313.617 291.549,313.909ZM122.504,56.189C108.803,57.328 108.586,58.752 77.52,89.52C67.383,99.56 41.707,118.202 65.499,142.501C69.654,146.746 97.343,175.026 118.042,194.963C120.311,197.148 138.683,214.844 157.354,197.354C159.374,195.462 152.6,190.554 147.724,185.285C130.514,166.688 143.295,149.63 147.869,145.918C170.487,127.563 188.936,152.08 194.049,156.98C198.69,161.428 203.777,146.851 204.063,145.407C207.634,127.379 198.654,122.018 178.89,102.114C137.17,60.101 137.397,57.956 122.504,56.189Z'
        }
    };
    /* A marker may be written as {token} or, where the registry gives one, as a
     * plain glyph — 🔗 is the same thing as {menulink}, so an author can just
     * type the emoji. Regex and glyph lookup are built from the registry above,
     * so a new entry needs no changes here. */
    var INLINE_RE, GLYPH_TOKENS = {};
    (function () {
        var tokens = [], glyphs = [], name;
        for (name in INLINE_ICONS) {
            if (!Object.prototype.hasOwnProperty.call(INLINE_ICONS, name)) continue;
            tokens.push(name);
            if (INLINE_ICONS[name].glyph) {
                glyphs.push(INLINE_ICONS[name].glyph);
                GLYPH_TOKENS[INLINE_ICONS[name].glyph] = name;
            }
        }
        INLINE_RE = new RegExp(
            '\\{(' + tokens.join('|') + ')\\}' +
            // trailing U+FE0F: the emoji-presentation selector some editors add
            (glyphs.length ? '|(' + glyphs.join('|') + ')\\uFE0F?' : ''),
            'gi'
        );
    }());
    var SVG_NS    = 'http://www.w3.org/2000/svg';

    function inlineIcon(token) {
        var spec = INLINE_ICONS[token];
        var svg  = document.createElementNS(SVG_NS, 'svg');
        svg.setAttribute('class', 'tree-inline-icon tree-inline-icon--' + token);
        svg.setAttribute('viewBox', spec.viewBox);
        svg.setAttribute('fill', 'currentColor');
        svg.setAttribute('focusable', 'false');
        svg.setAttribute('role', 'img');
        svg.setAttribute('aria-label', spec.title);
        var path = document.createElementNS(SVG_NS, 'path');
        path.setAttribute('d', spec.path);
        svg.appendChild(path);
        return svg;
    }

    /* Writes `text` into `el`, turning every inline marker into its icon. Text
     * always goes in as a text node — never as HTML — so tree content can never
     * inject markup. */
    function writeText(el, text) {
        var rest = String(text == null ? '' : text);
        var m, last = 0;
        INLINE_RE.lastIndex = 0;
        while ((m = INLINE_RE.exec(rest)) !== null) {
            if (m.index > last) el.appendChild(document.createTextNode(rest.slice(last, m.index)));
            el.appendChild(inlineIcon(m[1] ? m[1].toLowerCase() : GLYPH_TOKENS[m[2]]));
            last = m.index + m[0].length;
        }
        if (last < rest.length) el.appendChild(document.createTextNode(rest.slice(last)));
    }

    function classify(contentPart, kind) {
        var out = { name: '', comment: '', icon: 'icon-file', isFolder: false, status: '' };

        if (kind === 'page') {
            // Page titles contain spaces ("Über Mich"), so unlike a file tree a
            // page tree must NOT treat the first space as the start of a comment:
            // only an explicit //, # or /* does. A trailing "/" (or a root-level
            // entry, handled by the caller) marks a node with children.
            var cm = contentPart.match(COMMENT_RE);
            out.name     = (cm ? contentPart.slice(0, cm.index) : contentPart).replace(/\s+$/, '');
            out.comment  = cm ? contentPart.slice(cm.index) : '';
            out.isFolder = /\/$/.test(out.name.replace(INLINE_RE, '').replace(/\s+$/, ''));
            out.icon     = out.isFolder ? 'icon-pagegroup' : 'icon-page';
            return out;
        }

        var fileMatch = contentPart.match(FILE_RE);
        if (fileMatch) {
            var ext = fileMatch[2].toLowerCase();
            out.name    = fileMatch[1];
            out.icon    = /^(woff2|woff|ttf|otf|eot)$/.test(ext) ? 'icon-font' : 'icon-' + ext;
            out.comment = fileMatch[3] || '';
            return out;
        }

        var folderMatch = contentPart.match(FOLDER_RE);
        if (folderMatch) {
            out.name     = folderMatch[1];
            out.icon     = 'icon-folder';
            out.comment  = folderMatch[2] || '';
            out.isFolder = true;
            return out;
        }

        var fallbackMatch = contentPart.match(/^([a-zA-Z0-9_\-\.]+)(\s.*|$)/);
        if (fallbackMatch) {
            out.name     = fallbackMatch[1];
            out.icon     = 'icon-folder';
            out.comment  = fallbackMatch[2] || '';
            out.isFolder = true;
        } else {
            out.name     = contentPart;
            out.isFolder = true;
        }
        return out;
    }

    function render(text, opts) {
        opts = opts || {};
        var kind = opts.kind === 'page' ? 'page' : 'file';

        var wrapper = document.createElement('div');
        wrapper.className = 'file-tree-container file-tree-container--' + kind;

        var rootUl = document.createElement('ul');
        rootUl.className = 'treeview-root';

        String(text == null ? '' : text).split('\n').forEach(function (line) {
            var isContentless = !line.replace(/[│├└─\s ]/g, '').trim();

            var prefixMatch = line.match(/^([│├└─\s ]+)/);
            var prefix      = prefixMatch ? prefixMatch[1] : '';
            var contentPart = line.substring(prefix.length);

            var li = document.createElement('li');
            li.className = 'treeview-item';

            if (prefix) {
                var indentWrapper = document.createElement('div');
                indentWrapper.className = 'tree-indent-wrapper';
                (prefix.match(/.{1,4}/g) || []).forEach(function (chunk) {
                    var cell = document.createElement('div');
                    cell.className = 'tree-line-cell';
                    if      (chunk.indexOf('├') !== -1) cell.classList.add('line-t');
                    else if (chunk.indexOf('└') !== -1) cell.classList.add('line-corner');
                    else if (chunk.indexOf('│') !== -1) cell.classList.add('line-vertical');
                    indentWrapper.appendChild(cell);
                });
                li.appendChild(indentWrapper);
            }

            if (isContentless) {
                if (prefix) rootUl.appendChild(li);
                return;
            }

            var status = '';
            if (kind === 'page') {
                contentPart = contentPart.replace(STATUS_RE, function (m, s) {
                    status = s.toLowerCase();
                    return '';
                });
            }

            var node = classify(contentPart, kind);

            // A page tree's root-level entries group the ones below them.
            if (kind === 'page' && !prefix) {
                node.icon     = 'icon-pagegroup';
                node.isFolder = true;
            }

            // Visibility icon: explicit marker always wins (a whole section can be
            // private, too); a page without a marker is public, which is what the
            // WBCE page settings default to.
            if (kind === 'page' && (status || !node.isFolder)) {
                node.icon   = 'icon-status-' + (status || 'public');
                node.status = status || 'public';
            }

            var iconSpan = document.createElement('span');
            iconSpan.className = 'tree-icon ' + node.icon;
            if (node.status) iconSpan.title = node.status;
            li.appendChild(iconSpan);

            var nameSpan = document.createElement('span');
            nameSpan.className = node.isFolder ? 'tree-name tree-name--folder' : 'tree-name';
            writeText(nameSpan, node.name);
            li.appendChild(nameSpan);

            if (node.comment) {
                var commentSpan = document.createElement('span');
                commentSpan.className = 'tree-comment';
                writeText(commentSpan, node.comment);
                li.appendChild(commentSpan);
            }

            rootUl.appendChild(li);
        });

        wrapper.appendChild(rootUl);
        return wrapper;
    }

    /* Reader-only: the same toolbar the regular code blocks get (layout/reader.htt),
     * badged with the tree kind instead of a language. */
    function toolbar(text, kind) {
        var bar = document.createElement('div');
        bar.className = 'mdr-code-toolbar';

        var badge = document.createElement('span');
        badge.className = 'mdr-code-lang';
        badge.textContent = kind === 'page' ? 'PageTree' : 'FileTree';
        bar.appendChild(badge);

        var copyBtn = document.createElement('button');
        copyBtn.type = 'button';
        copyBtn.className = 'mdr-code-copy';
        copyBtn.title = 'Copy code';
        copyBtn.setAttribute('aria-label', 'Copy code');
        copyBtn.innerHTML = '<svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="currentColor">'
            + '<path fill-rule="evenodd" d="M14 12V2H4V0h12v12h-2zM0 4h12v12H0V4zm2 2v8h8V6H2z"/></svg>';
        copyBtn.addEventListener('click', function () {
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
                try { document.execCommand('copy'); done(); } catch (e) {}
                document.body.removeChild(ta);
            }
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(done, fallbackCopy);
            } else {
                fallbackCopy();
            }
        });
        bar.appendChild(copyBtn);
        return bar;
    }

    function enhance(root) {
        var scope = root || document;
        scope.querySelectorAll('pre.file-tree, pre.page-tree').forEach(function (pre) {
            if (pre.closest('.ProseMirror')) return;
            var code = pre.querySelector('code');
            if (!code) return;
            var kind    = pre.classList.contains('page-tree') ? 'page' : 'file';
            var wrapper = render(code.textContent, { kind: kind });
            wrapper.appendChild(toolbar(code.textContent, kind));
            pre.parentNode.replaceChild(wrapper, pre);
        });
    }

    window.WbceFileTree = { render: render, enhance: enhance };

    document.readyState === 'loading'
        ? document.addEventListener('DOMContentLoaded', function () { enhance(); })
        : enhance();
    window.WbceFileTree = { render: render, enhance: enhance };

    document.readyState === 'loading'
        ? document.addEventListener('DOMContentLoaded', function () { enhance(); })
        : enhance();
}());
