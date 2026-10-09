# Changelog

All notable changes to the `MarkdownWbce` module.

## 0.3.2 - 2026-09-12 Christian M. Stefan

### Fixed
- **Edit mode saved into the wrong language file.** The edit form posted
  `rel_path` = the path the *link* asked for, while the editor was showing
  what `findExistingDoc()` had resolved it to. On a DE install, opening
  `/modules/foo/README.md` displays `README_DE.md` — and Save then overwrote
  `README.md` with the German text, reported success, and the reader kept
  showing the unchanged `README_DE.md`, so the edit looked lost. `reader.php`
  now derives `$activeRelPath` from the resolved `absPath` and uses it for the
  form, the footer path and the image base URL; `MdReaderHelper::_toWebRelPath()`
  became public for it.

### Changed
- **Save feedback moved onto the Save button.** Instead of a grey "Saved." to the
  far left of the bar, the button itself reports: "Saving…" while the request is
  out, then a checkmark that draws itself inside a ring for 1.6 s before the
  label returns. Only failures still put text next to the button, now in red.
  Honours `prefers-reduced-motion`.
- **"Cancel" is now "Back"** — it never discarded anything, it left edit mode.

### Added
- **```page-tree fenced blocks.** `ParsedownWbce::blockFencedCode()` now
  special-cases this tag next to ```file-tree, emitting
  `<pre class="page-tree">`; `MdReaderHelper::needsFileTree()` matches both, so
  the reader loads `layout/filetree.css` / `layout/filetree.js` for either.
- **`layout/filetree.js` re-vendored** from `modules/tiptap_editor/assets/` at
  its current state: renders page trees with page/page-group icons, understands
  the page visibility markers `{public}` `{hidden}` `{private}` `{registered}`
  `{none}` and the inline `{menulink}` marker (or its glyph 🔗), and treats a space in a page
  title as a title (only `//`, `#`, `/*` start a comment there). The reader's
  own toolbar divergence is kept and now badges "PageTree" / "FileTree" by kind.
- `layout/filetree.css`: the new icon rules ported in, leaving the
  MarkdownWbce-only dark-mode block and toolbar padding untouched.
- **Every spelling of the two tags works**: `file-tree`, `filetree`, `fileTree`,
  `file_tree` (and the same for page) all normalise to the same block — letters
  only, case-insensitive. Also makes the TipTap editor's Markdown export, which
  writes ```fileTree, round-trip.
- **Inline tree previews while editing** (`reader.php`): in edit mode the tree
  assets are now always loaded, not only when the current document happens to
  contain a tree, because PlainMDE renders a tree the moment one is typed (see
  `include/PlainMDE/src/plainmde-treeview.js`).
- `DOCS.md` / `DOCS_DE.md`: a "Tree Diagrams" section documenting both fence
  types and every marker.

## 0.3.1 - 2026-09-10 Christian M. Stefan

### Changed
- **Fixed-viewport reader shell.** The popup no longer scrolls as one page:
  header and footer are `flex:none` and always on screen, and `.mdr-content` /
  `.mdr-sidebar-wrap` scroll independently inside the middle row. So the footer
  is static, and the sidebar panel now stretches the full height between header
  and footer even when its TOC is short. Anchor jumps use `.mdr-content`'s
  `scroll-padding-top`; the scroll spy watches that container instead of the
  window. The `.h-anchor` negative-offset hack is gone.
- **Reader resize handle rebuilt** on the FEE/VES shell's pattern: a flex item
  between the sidebar and the content, driven by Pointer Events +
  `setPointerCapture()` (so a drag can't "stick" when the cursor crosses the
  scrolling content), width as `--mdr-sidebar-w` on `<html>`, persisted to
  `localStorage`, transition suppressed during the drag, double-click resets to
  the default.
- **Reader visual refresh.** The popup's header, sidebar and footer now sit on
  a `--mdr-panel` surface (a small neutral offset from the content background,
  light + dark), so the article reads as "the page". The footer gained a
  second line: `WBCE CMS MarkdownWbce` on the left, the doc's repo-relative
  path on the right (`[DOC_PATH]`).
- **Reader font.** A single `--mdr-font` token (declared in `markdown.css`, a
  `"Segoe UI"`-first system stack) is used by `.markdown-body`, `.mdr-embed`
  and the reader chrome — scoped to the reader and its inline embeds, so the
  surrounding backend keeps its own font. No web font, no third-party request.

## 0.3.0 - 2026-09-09 Christian M. Stefan

### Added
- **Inline backend rendering.** `MdReaderHelper::renderForEmbed($relPath, $lang, $tocClass)`
  returns `{abs, html, toc, title, langs, needsCode, needsFileTree}` for
  dropping a rendered doc straight into a backend page — no `reader.php`
  popup. First consumer: the **Asset Optimizer** admin tool's *Documentation*
  tab.
- **`MdReaderHelper::embedAssets($doc = null)`** → `{css:[…], js:[…]}` — one
  call gives a consumer every stylesheet/script the embed needs
  (`markdown.css` + `markdown-embed.css`, plus file-tree / highlight.js when
  the doc uses them). No per-module CSS.
- **`layout/markdown-embed.css` + `layout/markdown-embed.js`** — the doc +
  sticky-TOC-sidebar layout (`.mdr-embed` / `.mdr-embed-toc`), and a **scroll
  spy** that marks the current section's TOC link `a.mdr-nav--active` (same
  class and look the reader popup already uses). The JS now also raises
  `html { scroll-padding-top }` to clear the backend theme's fixed header
  instead of setting `scroll-margin-top` on the anchors — the two were stacking,
  landing every anchor jump ~1 header-height too low and desyncing the spy.
- **TOC links styled by heading depth.** `buildToc()` now tags each `<li>`
  `mdr-toc-l1 … l6`; `markdown-embed.css` and `style.css` size/weight the link
  accordingly (h1 bold, deeper levels smaller and fainter) — in both the embed
  and the popup. The active link gets an accent left-border and always keeps
  the accent colour, even at a faint deep level.
- **TOC entity fix.** `buildToc()` decodes the heading's inner HTML before
  re-encoding it for the link, so a heading like `Cache & Status` shows as
  `Cache & Status` and not a literal `Cache &amp; Status` — and its slug/anchor
  match too.
- **`embedAssets()` / `highlightAssets()` now cache-bust** their URLs with an
  mtime `?v=` (the reader popup already did via `reader.php`), so an embedding
  page never serves a stale `markdown-embed.css` / `.js`.
- **Shared syntax highlighting.** `MdReaderHelper::highlightAssets()` →
  `{js:[…], css_light, css_dark}`, and a new `layout/highlight.js` that
  highlights `.markdown-body pre code[class*="language-"]` and adds the
  language-badge / copy-button toolbar. Used by the embed and the reader alike.

### Changed
- **`file-tree` blocks**: `.woff` / `.woff2` / `.ttf` / `.otf` / `.eot` now get
  a dedicated font icon (magenta page + serif "A") instead of falling through
  to the folder icon.
- **Code-block toolbar** no longer overlaps the first code line — the
  `.mdr-code-wrapped { padding-top }` reserve was losing to `.markdown-body pre`
  on specificity; now `.markdown-body pre.mdr-code-wrapped`. Toolbar got an
  opaque backing so a wide first line scrolling underneath doesn't show through.
- `markdown.css` block-margin fixes so an embedded doc has no external reset to
  lean on (blockquote / first-child top gaps).
- **`layout/markdown.css` is now self-contained** — the `--mdr-*` colour
  tokens (light + dark + `[data-theme]`) moved out of `layout/style.css` into
  it, so any page that shows a `.markdown-body` needs only `markdown.css` and
  gets the same GitHub-style palette as the reader popup. `style.css` is now
  reader-chrome only (still carries a global reset — never load it into an
  embed).
- **highlight.js is now self-hosted** (`layout/vendor/hljs/`, v11.9.0 common
  build + GitHub light/dark themes) instead of loaded from cdnjs. `reader.htt`'s
  inline highlight/toolbar script moved into `layout/highlight.js`; `reader.js`
  reads the light/dark theme URLs from `#mdr-hljs-theme`'s `data-hljs-*`
  attributes.

## 0.2.0 - 2026-08-19 Christian M. Stefan

### Added
- **Editing.** `reader.php` gained a full edit mode (PlainMDE, loaded via
  `I::loadPlugin('include/PlainMDE')`) alongside the existing read-only view,
  backed by a new `ajax_save_doc.php` save endpoint.
- **Permission model.** Reading requires only a valid backend `Admin`
  session; writing requires `$admin->isAdmin()` or the `MarkdownWbce_tool`
  AdminTool permission specifically (same per-tool check as
  `admin/admintools/tool.php`) — not the generic
  `modules_install`/`modules_uninstall` system permission, and not tied to
  page ACL, since these are documentation files, not page content.
- **Hardening.** `MdReaderHelper::safePath()` now enforces a `.md` extension
  whitelist. `ajax_save_doc.php` is overwrite-only (never creates a new
  file), requires a valid FTAN token, and writes a JSON-lines audit log to
  `var/markdown_wbce/save.log`.
- **Frontend reach.** `MdReaderLink` is registered FE+BE (via the new
  `initialize.php`, not just `initialize_be.php`), so a link embedded on a
  live page works for any editor already logged into the backend in the
  same browser — the same cookie-based session `reader.php` already
  required, just no longer gated behind a `/admin/`-only registration.
- **Missing-base-file fallback.** `MdReaderHelper::findExistingDoc()` — if
  the requested `README.md` doesn't exist but a `README_<LANG>.md` variant
  does (or vice versa), that variant is served instead of a hard error.
- **Language switcher.** A segmented-control flag bar (styled after
  DynamicFields' `df-segmented` widget) lists every available
  `README_<CODE>.md` variant, including the base file itself as an implicit
  `EN` entry when at least one localized sibling also exists. Each flag
  carries an `exact` flag so an explicit language choice is never silently
  overridden by the visitor's own active `LANGUAGE`.
- **`` ```file-tree `` fenced blocks.** `ParsedownWbce::blockFencedCode()`
  special-cases this language tag to emit `<pre class="file-tree">` instead
  of a normal code block; `layout/filetree.js`/`filetree.css` (vendored from
  `modules/tiptap_editor`, restyled to match this module's own code-block
  toolbar) render it as a visual, icon-annotated tree. Lines starting with
  `//` or `#` render as plain comments instead of being mis-parsed as
  folders.
- **Syntax highlighting.** Rendered code blocks in the viewer use
  highlight.js (trial, loaded via cdnjs) instead of read-only CodeMirror
  instances, with a language badge and copy-to-clipboard button per block.
- **Light/dark theme.** The whole reader — chrome, sidebar, article content,
  code blocks, the FileTree renderer, and scrollbars — is CSS-variable
  driven with a light default, automatic `prefers-color-scheme: dark`
  support, and a manual toggle (persisted via `localStorage`) that always
  wins over the OS setting. highlight.js's theme stylesheet is swapped to
  match. The PlainMDE editor itself also gets a dark mode, scoped under a
  `.mdr-pmde-theme` wrapper class so the shared PlainMDE component's other
  four consumers are completely unaffected.
- Internal markdown links (`[x](./CHANGELOG.md)`, `[x](../docs/y.md)`) now
  resolve against the *document's own* directory instead of `reader.php`'s
  URL, using the same resolution the rendered viewer already applied to
  relative image paths.
- Relative image paths in the PlainMDE edit-mode preview now resolve the
  same way the rendered viewer already did.
- mtime-based cache-busting (`?v=…`) on all of this module's own
  `layout/*.css`/`*.js` assets.

### Changed
- Renamed from `MarkdownReader` to `MarkdownWbce` (directory, `info.php`,
  all internal path references).
- `MdrLink` → `MdReaderLink`, `MdrHelper` → `MdReaderHelper` (classes and
  filenames) — the shorter names read as too easily confused with each
  other and with unrelated `Mdr*`-prefixed symbols elsewhere.
- `ParsedownWbce` is now registered core-wide via `initialize.php`
  (`function = 'initialize, tool'`), not gated behind the backend-only
  `initialize_be.php` — WBCE needs a Markdown parser regardless of whether
  this module's own viewer/editor UI is ever opened.
- The reader's client-side filter/search box was removed — the browser's
  own find-in-page already covers it, and the extra UI wasn't earning its
  keep.
- Edit mode: Save moved to the right of the action row (Cancel stays left),
  the panel widened and centered, the sidebar hides while editing, and
  Cancel now triggers a full reload instead of a local DOM toggle — so a
  completed Save is reflected on screen without an extra manual refresh.
- Sidebar resize handle got a permanently visible grip ("handlebar") — the
  original was invisible until hovered, which turned out to be too subtle
  to find.

### Fixed
- `tool.php` didn't pass `returnToTools` to its Twig template, leaving the
  AdminTool's own "Back" button with an empty `href`.
- `modules/outputfilter_dashboard`'s help links (`opf_md_link()`) still
  pointed at the removed `include/MarkdownReader/reader.php?url=…`
  mechanism; also fixed a pre-existing missing-argument bug in the same
  function's `(md)`-marked DB-configured helppath branch.
- Windows-style backslashes could leak into generated doc URLs
  (`/modules\Foo\docs\README_DE.md`) on this Windows dev install — fine by
  browser leniency locally, broken on a real Linux/Apache deployment.

## 0.1.0 2026-08-09 Christian M. Stefan

- Initial release (as `MarkdownReader`): renders Markdown files as
  formatted documentation pages via `reader.php`, with `MdrLink` as the
  public API other modules use to link to their own docs. Manifest-driven
  multi-file (`md_reader.json`) and single/multi-doc-with-tabs modes,
  automatic `README_<LANG>.md` language-variant preference, table of
  contents generation, and `ParsedownWbce` (a small `Parsedown` extension
  adding GFM task-list checkboxes).
