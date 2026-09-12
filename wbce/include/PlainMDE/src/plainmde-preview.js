/**
 * include/PlainMDE/src/plainmde-preview.js
 *
 * PlainMDE.markdown(text) - turns markdown source into preview HTML, with
 * each top-level block wrapped in <div class="pmde-src-block" data-src-
 * start=".." data-src-end="..">, which plainmde-sync.js uses for the
 * cursor-preview and scroll sync.
 *
 * Always uses PlainMDE's own renderer below - it does NOT reach for
 * `window.marked` from modules/md_viewer, despite that having been this
 * file's original design (documented here previously). Turned out that
 * "marked" is a ~60-line bespoke regex converter md_viewer wrote for its
 * own simple internal preview, not a real (CommonMark/GFM) parser -
 * confirmed by calling its .parse() directly: headings, code fences and
 * tables all come out wrapped in a single stray <p>...<br></p> with no real
 * block structure at all (`marked.parse("# Hi")` -> `<p><h1>Hi</h1><br></p>`).
 * Every case tested came out *worse* than this file's own fallback
 * renderer, not just checklists - so the "fallback" was promoted to the
 * only path rather than kept as a last resort that silently degraded
 * quality whenever it seemed most available. If md_viewer's marked is
 * ever replaced with a real parser, reintroducing it here as the
 * preferred path (behind the same splitBlocks() line-range logic) is a
 * localized change to PlainMDE.markdown() only.
 */
(function () {
    'use strict';

    function escapeHtml(s) {
        return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    // Language hint on a fenced code block's opening line (``` php etc.) —
    // trial: highlight.js reads this as class="language-xxx" (see
    // plainmde-core.js's _renderPreview(), which calls hljs on whatever this
    // emits). Sanitized to alnum/dash/underscore since it's attacker-typed
    // markdown source landing straight in an HTML attribute.
    function fenceLangClass(openingLine) {
        var m = openingLine.match(/^\s*```\s*(\S*)/);
        var lang = m && m[1] ? m[1].replace(/[^a-zA-Z0-9_-]/g, '') : '';
        return lang ? ' class="language-' + lang + '"' : '';
    }

    // The two fence tags that render as a visual tree rather than as code —
    // same normalisation (letters only, case-insensitive) as
    // ParsedownWbce::blockFencedCode(), so file-tree / filetree / fileTree /
    // file_tree are all the same tag, and likewise for page. Returns the kind
    // ('file' | 'page') or '' for a normal code fence.
    function fenceTreeKind(openingLine) {
        var m = openingLine.match(/^\s*```\s*(\S*)/);
        var tag = m && m[1] ? m[1].toLowerCase().replace(/[^a-z]/g, '') : '';
        return tag === 'pagetree' ? 'page' : (tag === 'filetree' ? 'file' : '');
    }

    // Block boundary detection.
    // Fenced code blocks and list runs (including "loose" ones with a blank
    // line between items, as long as list content follows) are each kept
    // as one block; blockquotes, tables, headings, and paragraphs get their
    // usual boundaries. Mirrors plainmde-decorations.js's line-
    // classification rules so the editor's "what block is this line part
    // of" and the preview's agree.
    var LIST_MARKER = /^\s*([-*+]|\d+[.)])\s+/;
    var CHECKLIST_ITEM = /^\s*[-*+]\s+\[([ xX])\]\s+/;
    var CONTINUATION = /^\s+\S/;
    var TABLE_LINE   = /^\s*\|.*\|\s*$/;
    var TABLE_SEP    = /^\s*\|?\s*:?-+:?\s*(\|\s*:?-+:?\s*)*\|?\s*$/;
    var QUOTE_LINE   = /^\s*>/;
    var HEADING_LINE = /^\s*#{1,6}\s/;
    var FENCE_LINE   = /^\s*```/;
    var BLANK        = /^\s*$/;

    function isListLine(l) { return LIST_MARKER.test(l) || CONTINUATION.test(l); }

    function splitBlocks(text) {
        var lines = text.split('\n');
        var n = lines.length;
        var blocks = [];
        var i = 0;

        while (i < n) {
            if (BLANK.test(lines[i])) { i++; continue; }
            var start = i;

            if (FENCE_LINE.test(lines[i])) {
                i++;
                while (i < n && !FENCE_LINE.test(lines[i])) i++;
                if (i < n) i++; // include closing fence
            } else if (TABLE_LINE.test(lines[i])) {
                while (i < n && TABLE_LINE.test(lines[i])) i++;
            } else if (QUOTE_LINE.test(lines[i])) {
                while (i < n && QUOTE_LINE.test(lines[i])) i++;
            } else if (HEADING_LINE.test(lines[i])) {
                i++;
            } else if (LIST_MARKER.test(lines[i])) {
                while (i < n) {
                    if (isListLine(lines[i])) { i++; continue; }
                    if (BLANK.test(lines[i])) {
                        var j = i;
                        while (j < n && BLANK.test(lines[j])) j++;
                        if (j < n && isListLine(lines[j])) { i = j; continue; }
                    }
                    break;
                }
            } else {
                while (i < n && !BLANK.test(lines[i]) && !FENCE_LINE.test(lines[i]) &&
                       !TABLE_LINE.test(lines[i]) && !QUOTE_LINE.test(lines[i]) &&
                       !HEADING_LINE.test(lines[i]) && !LIST_MARKER.test(lines[i])) i++;
            }

            var end = i - 1;
            blocks.push({ start: start, end: end, raw: lines.slice(start, end + 1).join('\n') });
        }
        return blocks;
    }

    // Block renderer.
    function renderBlock(raw, startLine) {
        var lines = raw.split('\n');

        if (FENCE_LINE.test(lines[0])) {
            var body = lines.slice(1, FENCE_LINE.test(lines[lines.length - 1]) ? -1 : undefined).join('\n');
            // Tree fences get the markup the tree renderer looks for; whoever
            // renders the preview turns it into the visual tree afterwards
            // (plainmde-core.js's _renderPreview() calls WbceFileTree.enhance()).
            var treeKind = fenceTreeKind(lines[0]);
            if (treeKind) {
                return '<pre class="' + treeKind + '-tree"><code>' + escapeHtml(body) + '</code></pre>';
            }
            return '<pre><code' + fenceLangClass(lines[0]) + '>' + escapeHtml(body) + '</code></pre>';
        }
        if (HEADING_LINE.test(lines[0])) {
            var level = lines[0].match(/^\s*#+/)[0].trim().length;
            return '<h' + level + '>' + inline(lines[0].replace(HEADING_LINE, '')) + '</h' + level + '>';
        }
        if (QUOTE_LINE.test(lines[0])) {
            return '<blockquote><p>' + lines.map(function (l) { return inline(l.replace(/^\s*>\s?/, '')); }).join('<br>') + '</p></blockquote>';
        }
        if (TABLE_LINE.test(lines[0]) && lines.length > 1 && TABLE_SEP.test(lines[1])) {
            return renderTable(lines);
        }
        if (LIST_MARKER.test(lines[0])) {
            // Lists get per-item sync granularity (see renderList) instead
            // of the single outer .pmde-src-block wrapper every other block
            // type gets from PlainMDE.markdown() below - a long list would
            // otherwise highlight/scroll as one solid unit no matter which
            // item the cursor is actually on.
            return { html: renderList(lines, startLine), skipWrap: true };
        }
        if (/^\s*(---|\*\*\*|___)\s*$/.test(lines[0])) {
            return '<hr>';
        }
        return '<p>' + lines.map(inline).join('<br>') + '</p>';
    }

    function splitTableRow(line) {
        var cells = line.trim().replace(/^\|/, '').replace(/\|$/, '').split('|');
        return cells.map(function (c) { return c.trim(); });
    }

    function renderTable(lines) {
        var header = splitTableRow(lines[0]);
        var rows = lines.slice(2).filter(function (l) { return TABLE_LINE.test(l); }).map(splitTableRow);

        var thead = '<thead><tr>' + header.map(function (c) { return '<th>' + inline(c) + '</th>'; }).join('') + '</tr></thead>';
        var tbody = '<tbody>' + rows.map(function (row) {
            return '<tr>' + row.map(function (c) { return '<td>' + inline(c) + '</td>'; }).join('') + '</tr>';
        }).join('') + '</tbody>';
        return '<table>' + thead + tbody + '</table>';
    }

    // GFM task-list items ("- [ ] foo" / "- [x] foo") render as a disabled
    // checkbox input, matching what a real marked/CommonMark renderer does
    // - plainmde.css's `li[style*="list-style-type: none"]` rule (and
    // fixChecklistStyling() below) depend on that exact shape.
    function renderList(lines, startLine) {
        var ordered = /^\s*\d+[.)]\s/.test(lines[0]);

        // Group lines per item first: only a LIST_MARKER line starts a NEW
        // <li> - everything after it (wrapped continuation text, an indented
        // "> ..." blockquote, or a nested "```" code fence) belongs to that
        // same item until the next marker. Rendering line-by-line here used
        // to turn every wrapped line and every nested blockquote line into
        // its own top-level <li>, flattening e.g. a two-step numbered list
        // with a quoted screenshot note under step 1 into a single 9-item
        // list. Blank lines are dropped as item separators EXCEPT while
        // inside a nested fence, where they're part of the code content.
        // Each group also tracks its own line range (relative to this list's
        // first line) so the rendered <li> can carry its OWN data-src-start/
        // -end pair - see PlainMDE.markdown() below: without this, cursor
        // sync/highlighting treated an entire (possibly very long) list as
        // one solid block, so any line inside it lit up every item at once.
        var itemGroups = [];
        var inFence = false;
        lines.forEach(function (l, idx) {
            if (!inFence && BLANK.test(l)) return;
            if (!inFence && LIST_MARKER.test(l)) {
                itemGroups.push({ raw: [l], startIdx: idx, endIdx: idx });
            } else if (itemGroups.length) {
                var g = itemGroups[itemGroups.length - 1];
                g.raw.push(l);
                g.endIdx = idx;
            }
            if (FENCE_LINE.test(l)) inFence = !inFence;
        });

        var items = itemGroups.map(function (itemGroup) {
            var group = itemGroup.raw;
            var srcAttrs = ' class="pmde-src-block" data-src-start="' + (startLine + itemGroup.startIdx)
                + '" data-src-end="' + (startLine + itemGroup.endIdx) + '"';
            var first = group[0];
            var checklistMatch = CHECKLIST_ITEM.exec(first);
            var firstText = checklistMatch
                ? first.slice(checklistMatch[0].length)
                : first.replace(LIST_MARKER, '');

            // Walk the item's continuation lines as an ORDERED sequence of
            // paragraph / blockquote / fenced-code segments, since any of
            // these can appear anywhere under a list item (not only after
            // all its prose) and each needs real HTML rather than being
            // bucketed by type and flattened into one inline-escaped
            // paragraph regardless of where it actually occurred.
            var rest = group.slice(1);
            var segments = [{ type: 'p', lines: [firstText] }];
            var i = 0;
            while (i < rest.length) {
                var l = rest[i];
                if (FENCE_LINE.test(l)) {
                    var codeLines = [];
                    var codeLangClass = fenceLangClass(l);
                    i++;
                    while (i < rest.length && !FENCE_LINE.test(rest[i])) {
                        codeLines.push(rest[i].replace(/^\s{1,4}/, ''));
                        i++;
                    }
                    if (i < rest.length) i++; // consume closing fence
                    segments.push({ type: 'code', lines: codeLines, langClass: codeLangClass });
                } else if (QUOTE_LINE.test(l)) {
                    var quoteLines = [];
                    while (i < rest.length && QUOTE_LINE.test(rest[i])) {
                        quoteLines.push(rest[i]);
                        i++;
                    }
                    segments.push({ type: 'quote', lines: quoteLines });
                } else {
                    var last = segments[segments.length - 1];
                    if (last.type !== 'p') {
                        last = { type: 'p', lines: [] };
                        segments.push(last);
                    }
                    last.lines.push(l.replace(/^\s+/, ''));
                    i++;
                }
            }

            var body = segments.map(function (seg) {
                if (seg.type === 'code') {
                    return '<pre><code' + (seg.langClass || '') + '>' + escapeHtml(seg.lines.join('\n')) + '</code></pre>';
                }
                if (seg.type === 'quote') {
                    return '<blockquote><p>' + seg.lines.map(function (l) {
                        return inline(l.replace(/^\s*>\s?/, ''));
                    }).join('<br>') + '</p></blockquote>';
                }
                return seg.lines.length ? inline(seg.lines.join(' ')) : '';
            }).join('');

            if (checklistMatch) {
                var checked = /[xX]/.test(checklistMatch[1]);
                // Space after the colon matters: plainmde.css's alignment fix
                // targets li[style*="list-style-type: none"] (substring match).
                return '<li' + srcAttrs + ' style="list-style-type: none; margin-left: -1.5em;">'
                    + '<input type="checkbox" disabled' + (checked ? ' checked' : '') + '> '
                    + body + '</li>';
            }
            return '<li' + srcAttrs + '>' + body + '</li>';
        }).join('');
        return ordered ? '<ol>' + items + '</ol>' : '<ul>' + items + '</ul>';
    }

    // Image/link URLs and code-span content must not be touched by the
    // emphasis regexes that run after them - e.g. a pasted image filename
    // like ".../WBCE_170/.../mceclip0-1.png" contains an "_..._" pair that
    // the italic regex would otherwise match and wrap in <em>, splitting the
    // src="..." attribute in two and breaking the image. Extract those three
    // first, stash the finished HTML behind a placeholder token (delimited
    // by an ASCII control character built at runtime via fromCharCode, so
    // nothing unusual ever needs to be a literal byte in this file, and it
    // cannot collide with anything a user could type in markdown), run
    // emphasis on what's left, then substitute the real HTML back in.
    var PLACEHOLDER_MARK = String.fromCharCode(1);
    var PLACEHOLDER_RE = new RegExp(PLACEHOLDER_MARK + '(\\d+)' + PLACEHOLDER_MARK, 'g');

    function inline(text) {
        text = escapeHtml(text);
        var placeholders = [];
        function protect(html) {
            placeholders.push(html);
            return PLACEHOLDER_MARK + (placeholders.length - 1) + PLACEHOLDER_MARK;
        }

        text = text
            .replace(/`([^`]+)`/g, function (m, code) { return protect('<code>' + code + '</code>'); })
            .replace(/!\[([^\]]*)\]\(([^)]+)\)/g, function (m, alt, src) { return protect('<img alt="' + alt + '" src="' + src + '">'); })
            .replace(/\[([^\]]*)\]\(([^)]+)\)/g, function (m, label, href) { return protect('<a href="' + href + '" target="_blank" rel="noopener">' + label + '</a>'); });

        text = text
            .replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>')
            .replace(/~~([^~]+)~~/g, '<del>$1</del>')
            .replace(/\*([^*]+)\*/g, '<em>$1</em>')
            .replace(/_([^_]+)_/g, '<em>$1</em>');

        return text.replace(PLACEHOLDER_RE, function (m, i) { return placeholders[i]; });
    }

    // Belt-and-braces: catches any checkbox <li> not already carrying the
    // inline style (e.g. a future renderBlock() change that forgets it).
    function fixChecklistStyling(html) {
        var parser = new DOMParser();
        var doc = parser.parseFromString(html, 'text/html');
        var items = doc.getElementsByTagName('li');
        for (var i = 0; i < items.length; i++) {
            if (items[i].querySelector('input[type="checkbox"]')) {
                items[i].style.listStyleType = 'none';
                items[i].style.marginLeft = '-1.5em';
            }
        }
        return doc.body.innerHTML;
    }

    function addExternalLinkTargets(html) {
        var doc = new DOMParser().parseFromString(html, 'text/html');
        var links = doc.getElementsByTagName('a');
        for (var i = 0; i < links.length; i++) {
            if (/^https?:\/\//i.test(links[i].getAttribute('href') || '')) {
                links[i].setAttribute('target', '_blank');
                links[i].setAttribute('rel', 'noopener');
            }
        }
        return doc.body.innerHTML;
    }

    /**
     * @param {string} text     Markdown source
     * @return {string} HTML
     */
    PlainMDE.markdown = function (text) {
        var blocks = splitBlocks(text);

        var html = blocks.map(function (b) {
            var blockHtml = renderBlock(b.raw, b.start);
            // Lists render their own per-item .pmde-src-block <li>s (see
            // renderBlock/renderList above) instead of being wrapped as one
            // block here - wrapping the whole list AGAIN in an outer
            // .pmde-src-block div would highlight/scroll it as a single
            // unit regardless of the finer-grained per-item ranges.
            if (blockHtml && typeof blockHtml === 'object' && blockHtml.skipWrap) {
                return blockHtml.html || '';
            }
            if (!blockHtml || !blockHtml.trim()) return '';
            return '<div class="pmde-src-block" data-src-start="' + b.start + '" data-src-end="' + b.end + '">' + blockHtml + '</div>';
        }).join('');

        html = fixChecklistStyling(html);
        html = addExternalLinkTargets(html);
        return html;
    };
})();
