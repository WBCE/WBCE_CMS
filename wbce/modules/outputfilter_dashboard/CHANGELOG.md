# Version History

## 1.7.0 *(Christian M. Stefan, 18 August – 11 September 2026)*

- Documentation reorganised. `README.md` was a 1665-line manual; it is now a
  ~100-line module overview (what the pipeline is, the filters that ship with
  WBCE, requirements, credits, license, links), and the manual itself moved to
  `documentation/`, split by audience:
  - `documentation/USER_GUIDE.md` — for site administrators, no PHP: the
    dashboard screen by screen, what each core filter does in plain language,
    installing/exporting/deleting filters, page and module targeting, the
    `WB_OPF_BE_OFF` backend-lockout escape hatch, troubleshooting.
  - `documentation/DEVELOPER_GUIDE.md` — for filter authors: the seven pipeline
    stages and how to pick one, the filter function contract, the three delivery
    formats (inline / plugin ZIP / module) with complete file layouts, path
    tokens, ordering, scoping, `additional_fields`, AssetQueue instead of raw
    tags, the CodeVet gate, backend filters, deprecated APIs, debugging.
  - `documentation/API_REFERENCE.md` — every `opf_*` function and every
    `$filter` key, with the stage constants, path tokens and the deprecated
    legacy frontend-file API.
  - Corrections found while rewriting: the type table listed four stages where
    the tool has offered seven for some time; the core filter table described
    *Colorbox* as "(standalone)" when it registers at `OPF_TYPE_PAGE_FIRST`, and
    *Internal Link Replacer* as handling only `[pagelink:NN]` when it resolves
    `[wblinkNN]` and `[<module>:NN]` through `LinkResolver` as well;
    `additional_fields` documented the pre-1.3.2 `text` key instead of `label`.
    Newly documented: `opf_register_filter()` keeps an existing filter's
    `active`/`modules`/`pages`/`pages_parent` on re-registration unless
    `'force' => TRUE`, so re-running an installer on upgrade never clobbers the
    targeting an admin configured.
- The dashboard's **Help** link now opens all four documents as tabs
  (`MdReaderLink::docs()`) instead of just `README.md`. Verified end-to-end
  against the live site via a synthetic authenticated session: all four tabs
  return 200 with tables, TOC and heading anchors intact.
- Anchors in the new docs carry an explicit `<a id="…">` in the dash-slug form
  next to each function heading, so intra-document links resolve both in the
  MarkdownWbce reader (which slugs `_` to `-`) and on GitHub (which keeps `_`).
- The legacy `docs/` tree (generated phpDocumentor HTML, the old DE/EN text
  pages, the 2455-line combined Markdown) is superseded by `documentation/` and
  is no longer referenced from anywhere in the module.
- The User Guide's two icon tables now show the *actual* dashboard icons instead
  of approximate emoji. The eight glyphs (`code`, `plug`, `puzzle-piece`,
  `question-circle`, `cog`, `file-text-o`, `cloud-download`, `trash`) were cut
  out of the FontAwesome 4.7 SVG webfont WBCE already ships in
  `include/font-awesome/fonts/` and written to `documentation/icons/` — nothing
  downloaded, and the shapes are identical to what the backend renders (verified
  side by side against the live webfont).
  - Referenced as plain Markdown images (`![trash](icons/trash.svg)`), not
    `<img>`, not inline `<svg>`, not `<i class="fa …">`. The files carry
    `width="16" height="16"` themselves, so they come out correctly sized in any
    renderer, including ones that strip HTML entirely. Inline `<svg>` was ruled
    out twice over: GitHub's sanitiser drops the element, and
    `MdReaderHelper::_rewriteImagePaths()` runs DOMDocument across any document
    containing an `<img>`, which lowercases `viewBox` into a meaningless
    `viewbox`. `<picture>` for a dark-mode variant was ruled out too — its
    `<source srcset>` is not path-rewritten, and DOMDocument mis-nests the
    `<img>` inside the void `<source>` element.
  - Colour is baked in at `#7D92AA` (the module's own `drag_indicator.svg` tone)
    because an SVG referenced through `<img>` cannot inherit `currentColor`. It
    reads on white and on the reader's `#0d1117` dark theme alike, verified in
    both.
  - Each image carries real alt text (`![trash](…)`, not `![](…)`), so the
    icon-only column is not silent to a screen reader and degrades to a readable
    word if a file ever fails to load.
  - `modules/MarkdownWbce/layout/markdown.css`: `.markdown-body img` is styled
    for screenshots (`display: block; margin: 1em 0`), which made an inline icon
    break onto its own line and stretch the table row by 2em. Added a `td img` /
    `th img` override. This only brings the reader in line with every other
    renderer — GitHub ships no such rule and was already correct.
- `upgrade.php`: all file and directory removals collapsed into one
  `$obsoletePaths` pass built on the core's `removePath()`, replacing this
  module's `opf_io_unlink()`/`opf_io_rmdir()` and the older `rm_full_dir()`
  (three implementations of the same thing). Two real defects fell out of it:
  - `plugins/cachecontrol/` was never deleted on upgrade, and the loop that
    runs every `plugin_install.php` under `plugins/` therefore re-registered
    the old "Cache Control" filter on each upgrade — undoing the rename to
    "Assets Cache Busting" performed a few lines earlier. The cleanup now runs
    before that loop.
  - Three removals passed `$mod_dir` — the bare directory *name*, not a path —
    so they resolved against the admin script's working directory and never
    matched. Two of them targeted `config_init.php` and `precheck.php`, which
    this module still ships: the broken path was the only thing keeping the
    upgrade from deleting two live files. Those two are gone from the list.
  - Removals that this release makes necessary were added at the same time:
    `docs/` and `readme/` (superseded by `documentation/`), `dialog/`,
    `images/`, `backend.css`, `backend_body.js`,
    `ajax/jquery.collapser.min.js`, `CHANGELOG`, `README.txt`, `licenses.txt`,
    `FTAN_SUPPORTED`. Every entry was checked against the package: none of them
    matches a file this version still ships.
  - Output is now limited to what actually happened: `RM_PATH_NOT_FOUND` is the
    normal outcome on a fresh install and on every upgrade after the first, so
    it is skipped, and only removals and failures print a line.
  - Signals are read with `L_("SIGNAL['…']", $path)` rather than the raw
    `$SIGNAL` array. `upgrade.php` is `require`d from inside `upgrade_module()`
    and therefore runs in function scope, where a global is only visible if
    that function imported it — and it imports `$database`, `$admin` and
    `$MESSAGE`, not `$SIGNAL`. `L_()` reads the Lang registry, which is static
    and scope-independent. (The same line in `modules/droplets/upgrade.php`
    reads `$SIGNAL` directly and has the same blind spot.)
  - Core, in passing: `$SIGNAL['RM_DIR_COULD_NOT_REMOVE']` did not exist in any
    language file — the one signal that reports an actual failure. Added to
    `languages/EN.php`, `DE.php` and `NL.php`, the three that carry the block.
    `removePath()`'s own docblock recommended `sprintf($MESSAGE[$signal], …)`,
    which is doubly wrong: `$MESSAGE` carries `RM_*` entries only in the German
    file, and a global array is the thing that is not reliably in scope.
    Corrected to the `L_()` form.
- `$L` was never assigned — not by the language files, not by `tool.php`, which
  only registers the `L` *namespace* for Twig's `L_()`. Two PHP-side reads
  depended on it and had therefore been silently returning empty strings (plus
  an undefined-variable warning) for as long as they have existed: the filter
  list's type tooltip (`tool_dashboard.php`, "Inline-filter" / "Plugin-filter" /
  "Module-filter") and the download link text in the export toast (`tool.php`).
  Fixed with one assignment in `tool.php`; the included `tool_*.php` files share
  its scope, so both call sites are covered. Verified live: the tooltips now
  carry text.
- The **Help** link moved out of the shared frame into the dashboard's own
  button row and is now called **Documentation** / **Dokumentation**
  (`TXT_DOCUMENTATION`, `TXT_DOCUMENTATION_OPEN`), with a book icon.
  - It only ever worked on the dashboard anyway: `tool_dashboard.php` is the one
    screen that sets `tpl_help_url`/`tpl_help_onclick`, so on the filter editor
    and the CSS editor the shared frame rendered it as an empty `href` that just
    reloaded the page. Those two screens lose a link that did nothing.
  - Kept as a real `<a href>` rather than a `<button onclick>` so middle-click
    and "open in new tab" still work.
  - The row is now cp_chrome's own `.cp-toolbar` with `.pull-right` on the link
    (the pattern errorlogger's log view already uses), replacing the
    `width="100%"` layout table it had been. That table carried `margin:10px` on
    all four sides, so it started 10px in from the left *and* kept its full
    width — overflowing its container by exactly that 10px on the right. Nobody
    noticed while the row held only left-aligned buttons; a right-aligned link
    made it obvious, sitting past the title bar and the filter table. The
    toolbar now shares its left and right edge with both.
- **Upload panel rebuilt on cp_chrome components.** It was a bordered box with
  `text-align: center`, a bold inline label and the browser's raw
  "Choose File / No file chosen" control — which this module's own
  `input { color: #6c1111; font-family: monospace }` rule additionally rendered
  in dark red monospace. It is now a `.cp-card` (head with the close control,
  body, footer with the submit) whose body is a dashed drop zone.
  - The `<input type="file">` is untouched as far as the server is concerned —
    same `name`, same form, same `multipart/form-data`, so `upload.php` needed
    no change. It is only moved out of sight (clipped, not `display:none`, so it
    keeps its place in the tab order) with its `<label>` acting as the visible
    control. Picking by click, by keyboard and by drag & drop therefore all end
    up in the same native field.
  - States: dashed and grey at rest, solid and blue while a file is dragged
    over, solid and green with the file name once one is chosen — all from
    cp_theme's existing tokens (`--info`, `--success`, `--border`, `--r`), with
    literal fallbacks for themes that do not define them.
  - The card spans the width of the toolbar above it and the filter table below,
    inset 5px on each side; capping it at a fixed width just left half the row
    empty, and running it flush to the edge made it look welded to the list
    rather than like the transient overlay it is. Being wide, the drop zone is a
    band rather than a tall box — icon beside the label, not stacked above it —
    which keeps it a large drop target without turning into a void: 80px tall
    instead of 140.
  - **Gap worth naming:** cp_chrome has no file-input component at all, which is
    why this control had been left as the browser default. The drop zone lives
    in the module's own CSS for now; it is a candidate for promotion to
    cp_chrome, since any module offering an upload faces the same thing.
- **Clicking the drop zone scrolled the page down before the file dialog
  opened.** `input { … bottom: 30px }` had been sitting in this module's CSS
  doing nothing for years — box offsets only apply to positioned elements, and
  every input here was static. Hiding the upload field with `position: absolute`
  activated it: the field was placed 30px from the bottom of the pane, roughly
  1100px below the drop zone that owns it, so focusing it sent the browser
  scrolling after it. The dead declaration is gone, and the visually-hidden rule
  now pins its own offsets and `min-height` instead of inheriting whatever else
  happens to target `input`.
- Spacing in the header area: the button row is inset 10px at both ends, the
  upload panel 5px.
- Three findings while making the buttons match, each of which the measurements
  alone would have missed:
  - The theme targets `a.button` (one class + one element) while cp_chrome
    targets `.button` (one class), so the theme wins on specificity and a link
    styled as a button ends up with different padding than a real button beside
    it. Two classes outrank both.
  - Three stylesheets put margins on buttons — `10px 0` from the theme,
    `margin-right: .6em` from `ACPI_buttons.css`, `margin-left: 3px` from the
    design system — and each showed up as its own small misalignment: the
    vertical pair threw the `<a>` and the `<button>` 2px apart (because
    `align-items: center` centres an item's *margin* box), the right one left
    the last control 7px short of its container's edge, the left one pushed the
    first control 3px past the row's inset. All cleared; spacing comes from the
    container's `gap` and padding.
  - Clearing them then cancelled cp_chrome's own `margin-left: auto` on
    `.pull-right`/`.pos-right` — same specificity, later in the cascade — and
    both right-hand controls snapped back to the left. It is handed back
    explicitly now. This one was invisible in the numbers and obvious in a
    screenshot.
  - The module's legacy `button { min-width: 150px }` is now scoped to
    `button:not(.button)`, so the design system decides the shape of anything
    carrying the class.
  - Verified after the rebuild: all three toolbar controls on one baseline at
    identical height and padding, the documentation link and the upload button
    each flush with their container's right edge, the form still posting
    `save_settings` + FTAN + the file field, and the panel toggle, the file
    pick, the drag-over state and the close control all working.
  - With the link gone, the `pageinfo` bar in `_framer.twig` had nothing left in
    it — no screen in this tool ever sets `data.author` — and rendered as an
    empty 30px white strip above the title on every page. It is now conditional.
  - The tab labels needed `html_entity_decode()`: this module's language files
    write umlauts as HTML entities, which is right for the `title` attribute
    next to it, but a tab label travels as a JSON value inside a GET parameter
    and is rendered as text on the other side — the German "Übersicht" arrived
    as a literal `&Uuml;bersicht`. Caught by reading the rendered popup, not the
    markup.
- German translations of all four documents: `README_DE.md`,
  `documentation/USER_GUIDE_DE.md`, `DEVELOPER_GUIDE_DE.md`,
  `API_REFERENCE_DE.md`. `MdReaderHelper::findExistingDoc()` picks the `_DE`
  variant by backend language on its own, so `tool_dashboard.php` still passes
  only the base paths and needs no knowledge of which translations exist —
  verified for EN (base files), DE (all four `_DE`) and NL (falls back to base).
  - The user guide references the German screenshot
    (`images/dashboard_overview_DE.webp`, which had been sitting unused) and
    carries German alt text on the icons.
  - Headings differ per language, and the two renderers disagree on how to slug
    an umlaut — MdReader's `[^a-z0-9]+` mangles the multi-byte character, GitHub
    keeps it. Every German section therefore carries an explicit ASCII
    `<a id="…">`, and those ids are deliberately **identical to the English
    file's**, so both language versions stay structurally parallel and a link
    can be checked against either.
  - Tab labels in the help popup are now translated too
    (`TXT_DOC_OVERVIEW`/`_USER`/`_DEVELOPER`/`_API` in `languages/EN.php` and
    `DE.php`), instead of being hardcoded English.
  - Verified live against a DE backend session: each tab returns 200 serving the
    `_DE` file, tables and icons intact, no encoding damage. Because both
    language variants now exist, the reader additionally shows its own EN/DE
    flag switcher.
- Documentation assets now live under `documentation/`, split by kind:
  `documentation/images/` holds the dashboard screenshots (moved out of the old
  `readme/`, which is gone), `documentation/icons/` the eight UI glyphs. Keeping
  them apart matters because they are maintained differently — the screenshots
  are retaken by hand whenever the UI changes, the icons are regenerated from
  the webfont by a script. All three new directories carry the usual 301
  `index.php` guard, matching `assets/images/` and the rest of the module.
- Further jQuery reduction in `assets/backend_body.js` (574 → 302 lines):
  - `data-redirect-location` click handler and the scroll-to-last-modified-filter behaviour ported to plain `addEventListener`/`scrollTo({behavior:'smooth'})`.
  - Replaced the dynamically-loaded third-party `jquery.collapser.min.js` (used only for the filter list's truncated description column) with `opfInitShortDescriptions()`, a ~20-line vanilla equivalent (same 130-char truncation, ellipsis, ▼/▲ show-more/less toggle) — the plugin file itself was removed.
  - Replaced the ~280-line jQuery Growfield Library 2 (auto-grow textareas, only ever used with default options on `#desc` and the `additional_fields`-driven `opf_growfield_list`) with `opfAutoGrowTextarea()`, an ~10-line `input`-listener doing the same resize.
  - `ajax/ajax.js` (drag & drop reordering, status toggle, delete/convert inline confirms) intentionally left untouched — it still needs jQuery UI Sortable, which is the one genuinely non-trivial piece to port; scoped as a separate task.
  - Verified end-to-end against the live site (dashboard list + filter edit page, session-authenticated via the `wbce-admin-session-test-trick`): the minified AssetQueue bundle regenerates cleanly and contains both new functions, the description-truncation markup renders, and the `#desc`/`data-redirect-location` elements are present and correctly targeted.

- Turned `opf_register_frontend_files()`, `opf_register_onload_event()`, `opf_register_onload()` and `opf_register_document_ready()` (`functions_outputfilter.php`) into deprecated compatibility shims instead of removing them outright — none had any callers left in this shipped codebase (only doc-comment examples), but site-specific custom filters written before WBCE 1.7.0 may still call them. Each now delegates to the equivalent AssetQueue call (`I::insertCssFile()` / `I::insertJsFile()` / `I::insertCssCode()` / `I::insertJsCode()`) instead of writing to `$opf_HEADER`/`$opf_BODY`. `opf_register_frontend_files()`'s inline-tag path extracts the raw code and routes it through `insertJsCode()`/`insertCssCode()` rather than `I::insertHtmlCode()` — the latter is restricted by AssetQueue to `<body>` only (arbitrary HTML is never allowed in `<head>`), which would have silently broken `$target='head'` for inline usage; verified live for both file and inline cases, `head` and `body` targets, and the legacy IE-conditional-comment path (which does still land in `<body>` regardless of `$target`, an unavoidable limitation of `insertHtmlCode()` for that one path — documented in the function's docblock).
- The underlying `$opf_HEADER`/`$opf_BODY` globals and `opf_insert_frontend_files()` (`functions.php`) were kept as-is: the shipped "Cache Control" core filter (`plugins/cachecontrol/filter.php`) reads and writes those globals directly, independent of the four removed functions.
- Module version bumped to 1.7.0, matching the WBCE 1.7.0 release this cleanup targets.
- Cache Control plugin (`plugins/cachecontrol`, v1.0.9): fixed to only skip CSS/JS URLs that already end in a cache-busting `?<mtime>` (its own format, e.g. as applied by AssetQueue's `ASSET_CACHE_BUSTING`), instead of skipping anything with any query string at all — a URL like `?media=print` previously never got busted. Switched from the `opf_cut_extract()`/`opf_glue_extract()` placeholder-swap to `preg_replace_callback()`, since matching multiple variants of the same URL (busted vs. not) made the old string-replace mechanism unsafe against substring collisions.
- Rebuilt the "Plugin-Filter hochladen" upload panel toggle from scratch. `tool_dashboard.twig` now renders the panel with the native HTML `hidden` attribute (`tpl_show_upload` boolean from `tool_dashboard.php`, true only right after a failed upload so the error stays visible) and toggles it via a small inline vanilla-JS block co-located in the same template — no external CSS class, no cache/asset-pipeline dependency, no jQuery. The old jQuery handlers were removed from `assets/backend_body.js`.
  - Root cause, confirmed by inspecting the live DOM/CSSOM: the template's own `<noscript><style>#upload-panel[hidden]{display:block !important;}</style>...</noscript>` accessibility fallback was ending up as an *active* stylesheet rule (`document.styleSheets` contained it) even with JavaScript running, and its `!important` beat every hiding mechanism tried — the `.hideupload` class, an inline `style="display:none"`, and finally the `hidden` attribute itself. That fallback rule was removed entirely (the plain-text noscript warning is kept).
  - Also fixed in passing: the close button (`#close-panel`) never worked — the old `backend_body.js` selector was `p#close-panel img`, but the markup is an `<i>` icon, never an `<img>`.
- Renamed the "Cache Control" plugin to **Assets Cache Busting** (`plugins/cachecontrol/` → `plugins/opf_assets_cache_busting/`, function `opff_cachecontrol()` → `opff_assets_cache_busting()`, v1.0.9 → v1.1.0) and moved it from `OPF_TYPE_PAGE_LAST` to `OPF_TYPE_PAGE_FINAL`, positioned after "Remove System PH" — it previously shared a stage with "Class Insert Helper" (which triggers AssetQueue's own flush), with no guarantee it ran after it; now it structurally always runs last, against the final assembled page.
  - `upgrade.php` renames the existing DB row in place (`name`/`plugin` columns) *before* `plugin_install.php` re-registers under the new name, so `opf_register_filter()`'s own name-based lookup finds it and takes the update path — preserving `active` and every other setting instead of inserting a fresh default-active row and orphaning the old one. Verified end-to-end against the live DB (set `active=0`, ran the migration, confirmed the same row id survived with `active=0` intact and correct new `type`/`position`).
  - The old `opf_cache_control` / `opf_cache_control_be` Settings keys (derived from the old filter name) are deleted on upgrade; fresh `opf_assets_cache_busting` / `_be` keys are written automatically by `opf_register_filter()`'s existing `opf_set_active()` call.
- Renamed the `ASSET_CACHE_BUSTING` config constant to **`OPF_ASSETS_CACHE_BUSTING`** throughout (`framework/Assets/AssetQueue.php`, `ASSETS.md`, `ASSETS_TUTORIAL.md`, `FontCache_TUTORIAL.md`). Toggling the "Assets Cache Busting" filter on/off now doubles as the switch for this constant: `opf_set_active()` already mirrors the filter's state into a `Settings` key, and WBCE's `Settings::setup()` bootstrap already promotes every settings row to a same-named PHP constant on every request — no new plumbing needed on either side to wire the two together.
- Removed `assets/dialog/` entirely (`jquery.dialog.js`/`.css` + 10 image files, ~28.7 KB, 14 files) — the last of the module's own popup-dialog mechanism (`opf_message()`), replaced by mechanisms already established elsewhere in this same modernization pass:
  - Upload and export feedback (`tool.php`) now use `Alerts::sessionToast()`, same as save/CSS-save. Export's success toast embeds a clickable download link directly in the message (`Alerts::toast()` renders via `innerHTML`, confirmed safe for embedded HTML) — verified live for both the upload-error and export-success cases, including the escaped download link.
  - The inline/plugin filter conversion confirmation (previously `opf_message(..., 'query', ...)`, a real popup) now reuses the dashboard's existing inline row-confirm pattern (`.delete-item` in `ajax/ajax.js`) instead of introducing a third confirmation style: clicking the type icon swaps the row for a Confirm/Cancel prompt in place; confirming submits the `#outputfilter` form to the (FTAN-protected) convert URL, same as the old dialog's "OK" button did. Verified live: row swap, cancel-reverts, and the submitted URL/FTAN are all correct.
  - `backend_body.js`'s `#dashboard`-gated `$.insert(...)` calls for `jquery.dialog.css`/`.js` were removed along with the now-unused `tpl_export_*`/`tpl_upload_message`/`tpl_upload_ok`/`tpl_upload_success`/`tpl_upload_message_type` template variables and the `export_block`/`upload_block` script sections in `tool_dashboard.twig`.
- Audited every image in `assets/images/` for real usage (not just a `url()` mention in `backend.css`, but whether the CSS rule using it is ever actually reachable) and removed the ones that weren't: `delete_16.png`, `help_big.png`, `opf-dashboard-logo.gif` (zero references anywhere), plus `activity.png`, `empty.gif`, `empty.png` — these three *are* referenced by a CSS rule (`.activity img`), but that rule only ever applies to elements created by the `jQuery.fn.checkbox` plugin (`backend_body.js`) when invoked on `input[class=activity]`, and no template anywhere outputs that element. Removed the dead plugin definition, its dead invocation, and the now-unreachable `.activity`/`.activity-checked`/`.activity-disabled` CSS rules along with the images. `drag_indicator.svg` was checked the same way and confirmed genuinely reachable (live drag & drop reordering) — kept.
- Replaced the module/page target picker ("checktree") with a small vanilla-JS tree, removing the last piece of jQuery-plus-image-sprite UI in this module:
  - Removed `jQuery.fn.checkTree_my` (JJ Geewax 2008, WBCE-modified by thorn, ~240 lines) and its five sprite images (`checked.png`, `collapsed.png`, `expanded.png`, `marker.png`, `unchecked.png`). Replaced by `opfInitCheckTree()` (`backend_body.js`) — native `<input type=checkbox>` + FontAwesome chevrons, state derived purely from DOM nesting (no `data-node`/`data-parent` bookkeeping). Server-side rendering goes through one shared helper, `opf_checktree_node()` (`functions.php`), used for both the module tree and the page tree.
  - Fixed a real bug that predates this rewrite entirely, confirmed by reading the original plugin's own invocation (`allChildrenMarksParentChecked: "yes"`, its documented "old behaviour"): once every sub-page under a page's "(Seitenhierarchie)" branch got checked individually, that page's own hierarchy checkbox silently flipped to fully checked too — silently adding the parent page itself to the filter's page selection even though the admin never checked it, with no way to represent "all sub-pages, but not this page" at all. The new `recomputeNode()` never derives a branch's own checked/submitted value from its descendants — only the native `indeterminate` (dash) visual is computed from them, shown whenever a branch's descendants don't unanimously agree with the branch's own value. `:indeterminate` gets the same accent color at 70% opacity so it reads as visually distinct from a fully checked box.
  - Every branch, including the top-level "Alle Seiten"/"Alle Module", now starts collapsed on page load (previously the top level auto-expanded).
  - The "(einzelne Seite)"/"(Seitenhierarchie)" text next to a page-with-children's two rows now renders through `opf_checktree_node()`'s existing `$hint` param (its own non-bold, muted `.node-hint` span) instead of being concatenated into the checkbox label, where it inherited the label's bold weight.
  - Found and fixed a genuine bug in this rewrite along the way: `li.querySelectorAll('.node-children input[type=checkbox]')` doesn't scope the *ancestor-matching* part of a descendant selector to `li` — a node nested inside an outer `.node-children` could match its own selector as if it were its own descendant, corrupting cascade state. Fixed by anchoring with `:scope > .node-children ...`.
- `ajax_save_filter.php` and `ajax_save_css.php`: the header docblock opened with `/**` but was never closed with `*/` in either file — the entire rest of each file (all actual PHP code) was silently swallowed into one unterminated comment, a fatal parse error on every request (`php -l` confirms: "Unterminated comment starting line 2"). Both AJAX-save endpoints (filter code and CSS, via the CodeEditor toolbar's "AjaxSave" checkbox / Ctrl-S) have been non-functional since whichever change dropped the closing `*/` — the request hit a raw PHP fatal error instead of the expected JSON, so the toolbar's `$.post().done()/.fail()` handlers had nothing valid to parse and silently did nothing. Fixed by closing both docblocks properly.
- AjaxSave (`CodeEditorToolbar.jquery.js`, shared by `outputfilter_dashboard`/`droplets`/CSS editing) used to POST only `{ idKey, code_area_text }` — for the filter add/edit form specifically, that meant checking "AjaxSave" (or just pressing Ctrl-S) silently discarded any other field the admin had changed on the same page load (name, description, target pages/modules, active, ...), since only the `func` column ever got updated. `doAjaxSave()` now sends the whole form (`$form.serializeArray()`, which correctly preserves repeated keys like `pages_parent[]`) alongside the existing `idKey`/`code_area_text` pair. `ajax_save_filter.php` now calls `opf_save()` — the same function the regular, full-page "Save" submit already uses — instead of running its own narrow `UPDATE ... SET func = ?`; CodeVet's syntax/security check (and its error-line reporting) now comes from `opf_register_filter()` internally (`$GLOBALS['opf_codevet_error']`) rather than being duplicated in the endpoint. Added a check that the posted `id` field agrees with the id IDKEY was actually issued for, closing a gap `opf_save()`'s direct `$_POST['id']` trust would otherwise leave open. `ajax_save_css.php` needed no equivalent change — its form has no other user-editable fields besides the CSS itself. Verified end-to-end against the live DB via a synthetic authenticated session (see `wbce-admin-session-test-trick` memory): posting a changed `desc` alongside the code now actually persists `desc` (previously it never would have), and a deliberately mismatched `id`/`idKey` pair is correctly rejected with `403` and no DB write.

## 1.6.12 *(Christian M. Stefan, 17 August 2026)*

- Removed dead `class_exists("Tool")` scaffolding (`opf_get_module_query()`, `opf_list_target_modules()`, `opf_modules_categories()`) — `Tool` has never existed in any WBCE core release; it was forward scaffolding for a per-admin-tool target picker planned for a WBCE 2.0 that never shipped this way. The condition was always `FALSE`, so removing it changes no behavior.
- Docs corrected to match: backend filtering only ever worked at whole-page level through the Dashboard UI (the "Backend" checkbox in the pages tree, unrelated to the dead code above); targeting one specific backend module/admin-tool has no UI path, only a code-only one via `opf_register_filter()`.

## 1.6.11 *(Christian M. Stefan, 17 August 2026)*

- Save feedback + CodeVet consistency for the filter editor (form save and AJAX save):
  - Form save (`tool.php`) had no success/failure feedback at all — added via `Alerts::sessionToast()`, same pattern as `modules/code2/save.php`.
  - `opf_register_filter()` never ran the PHP syntax/security check that `ajax_save_filter.php` already had — added, hard-blocks regardless of `$force`.
  - A rejected save left `$id` empty, bouncing the admin to the list instead of staying on the filter; "Save & Close" bypassed failed saves entirely. Both fixed — `$id` is recovered from POST, and any failed save now forces staying on the edit page no matter which button was clicked.
  - Rejected code, its specific error, and the flagged line are now preserved and highlighted on reload via `$_SESSION['codevet_draft']` + CodeEditor's `error_line` option, matching droplets/code2, instead of being discarded.
  - `ajax_save_filter.php`: a literal `?>` inside a `//` comment silently ended PHP mode mid-file — valid code was never actually saved, no toast either. Fixed.
  - `opf_css_save()` always reported success even when the file was never written — fixed to report failure honestly.
  - Duplicate empty `id` attributes on extra-field textareas (`tool_add_edit_filter.twig`) fixed.

## 1.6.10 *(Christian M. Stefan, 17 August 2026)*

- Full PDO migration: removed the legacy `escapeString()+vsprintf()` query helpers (`opf_db_query()` and friends, ~25 call sites), replaced by native `Database::fetchAll()` / `fetchValue()` / `query()+hasError()`.
- Cleaned up the last legacy method aliases: `is_error()` / `get_error()` → `hasError()` / `getError()`, `delRow()` → `deleteRow()`.
- Removed `opf_check_patched()` — dead code, zero callers.
- DB failures are now always logged (`error_log()`), not only under DEBUG.

## 1.6.9 *(Christian M. Stefan, 10 August 2026)*

- Multi-driver SQL corrections (MySQL/SQLite) as preparation for experimental SQLite readiness
  - `install.php`'s `mod_outputfilter_dashboard` table DDL moved to `install_struct.sql`, routed through `Database::importSql()` instead of `opf_db_run_query()` so it gets normalized for the active driver.
  - `opf_db_query()` / `opf_db_query_vars()` / `opf_db_run_query()` (`functions.php`) checked `$result === NULL` to detect a failed query — a leftover from the old mysqli-era Database contract. The current PDO-based `Database::query()` always returns a `DatabaseResult` object, never null, so every failure was silently reported as success. Switched all three to `Database::hasError()`.
  - `opf_register_filter()` (`functions_outputfilter.php`) built its insert/update as `INSERT INTO ... SET col=val, ...` — MySQL-only syntax invalid on SQLite — split into a proper `INSERT INTO (cols) VALUES (...)` / `UPDATE ... SET ...` pair. Also fixed a pre-existing, driver-independent bug found in the process: the UPDATE branch's WHERE clause (`$sql_where`) was declared but never assigned, so every filter update ran against the whole table instead of the one row being edited.

## 1.6.8 *(Christian M. Stefan, 25 July 2026)*

- Consolidate standalone `mod_opf_*` modules into `core_outputfilters` plugin
  - Seven standalone opffilter modules (`mod_opf_auto_placeholder`, `mod_opf_csstohead`, `mod_opf_insert`, `mod_opf_move_stuff`, `mod_opf_remove_system_ph`, `mod_opf_replace_stuff`, `mod_opf_wblink`) are replaced by a single internal OPF Dashboard plugin bundled at `outputfilter_dashboard/plugins/core_outputfilters/`.
  - Three filters are dropped entirely — their functionality is now handled natively by AssetQueue: `auto_placeholder` (`process()` finds anchors directly), `csstohead` (`scan()` moves all stylesheet links), `move_stuff` (`processMoveBlocks()` handles MOVE syntax).
  - `upgrade.php` removes the three obsolete filter rows from the DB, then deletes all seven old module directories and their addons-table entries. `install.php` is cleaned up.

## 1.6.7 *(Christian M. Stefan (Stefek), 25 July 2026)*

- Ensure inline filter funcs re-open PHP context before eval
  - `ajax_save_filter.php` now stores inline code with a leading `<?php` tag.
  - `functions.php` adds a defensive guard for existing DB entries that lack it — both needed because `eval('?>'.$func)` starts in HTML mode and will echo the function source as plain text if no `<?php` tag is present.

## 1.6.5 *(Stefek, 27 February 2023)*

- Make use of the newly introduced MarkdownReader:
  - Create a mechanism that will automatically initialize README.md files to the dashboard entry.

## 1.6.4 *(Stefek, 24 February 2023)*

- Further cosmetic upgrade
- Handling of several bugs pertaining to upload and deletion of plugins

## 1.6.3 *(Stefek, 04 February 2023)*

- Add file `allways_active_array.php` that contains an array of the filters that should never be disabled (deactivated)

## 1.6.2 *(Stefek, 31 January 2023)*

- Change the delete mechanism to a jQuery/AJAX based inline confirm prompt

## 1.6.1 *(Stefek, 27 January 2023)*

- Change status activation mechanism to a checkbox jQuery/AJAX based switch

## 1.6.0 *(Stefek, 25 January 2023)*

- Change the Template Engine to Twig (remove phpLib TE templates).
- Improve (simplify) the look of the list of filters.
- Small change to the page and module tree layout (height adjustment).
- Remove JS page reload after AJAX drag & drop of filters.

---

## 1.5.16 *(mrbaseman, 1 July 2021)*

- Update cachecontrol plugin filter to version 1.0.7

## 1.5.15 *(mrbaseman, 31 May 2021)*

- Fail-safe fix for filter function, thanks to Atlasfreak
- Add logging a notice when filter fails

## 1.5.14 *(mrbaseman, 23 April 2021)*

- Fix a typo for the handling of `helppath`, thanks to florian
- New implementation of `!opf_insert_sysvar!` similar to the changes made in version 1.5.13

## 1.5.13 *(bianka, November 2020)*

- Support PHP 8 with a new implementation of `!opf_replace_sysvar!`

## 1.5.12 *(bernd, 16 May 2020)*

- Fixed hardcoded admin-dir in `backend_body.js`

## 1.5.11 *(mrbaseman, 9 January 2020)*

- Remove gpc functions which have been deprecated since PHP 5.4
- Replace curly braces for array index
- Update style files
- Minor update of correct_date_format plugin
- Update formatting of help page
- Add opf_controller mode `insert`
- Update documentation

## 1.5.10 *(mrbaseman, 7 October 2019)*

- Keep `additional_values` of plugin filters during upgrade

## 1.5.9 *(mrbaseman, 11 July 2019)*

- Include `edit_area.js` only on pages where an editarea exists
- Allow `search` in `pages_parent` for specifying searchresults
- Enable cachecontrol plugin for backend and search by default

## 1.5.8 *(mrbaseman, 20 March 2019)*

- Include `http_to_https` plugin
- Support placeholders in helppath
- Automatically install/update plugins from the plugins directory
- Support relative path for helppath
- Use placeholders in file path when editing a filter

## 1.5.7 *(mrbaseman, 07 March 2019)*

- Starting with PHP 7.2 `create_function` is deprecated
- Use `{SYSVAR:WB_URL}`, `{SYSVAR:WB_PATH}`, `{OPF:PLUGIN_URL}`, and `{OPF:PLUGIN_PATH}` when storing to database
- If it exists, use `/temp` directory instead of media folder
- Introduce new filter types:
  - `OPF_TYPE_SECTION_FIRST`: is executed before `OPF_TYPE_SECTION`
  - `OPF_TYPE_PAGE_FIRST`: is executed before `OPF_TYPE_PAGE`
  - `OPF_TYPE_PAGE_FINAL`: is executed after `OPF_TYPE_PAGE_LAST`
- Rewrite of `opf_filter_get_rel_pos()` — it did not return reliable results
- Introduce internal functions for sysvar replacements

## 1.5.6 *(mrbaseman, 04 November 2018)*

- Support a legacy module `opf_simple_backend` to switch between basic and advanced backend view
- Avoid warning during installation when shipped as core module
- PHP 7.2 fixes
- Add configurl button in the filter edit view, if applicable
- Allow multiple uses of the same field type in extra fields
- Change the logic when backend filtering shall be offered
- Correctly start incrementing position of installed filters
- Fix bug in `opf_filter_get_data()` to find index correctly
- Fix the logic for the backend filtering — module filtering not for inline filters
- Print footer always when an `exit()` is issued in the backend tool

## 1.5.5 *(mrbaseman, 5 September 2018)*

- Fix `uninstall.php` in case `MEDIA_DIRECTORY` does not start with a slash
- Fix AJAX drag & drop which caused warning when issued after editing a filter
- Fix: hand over `$OPF_TYPE_ASSIGNMENTS` correctly to `opf_revert_type_consts`
- Only apply filter to backend when `backend` is selected — `ALL` is for frontend
- Correctly apply filters for special pages like searchresults with `$pageid==0`
- Fix CSS for checktree so that indentation works correctly
- Bugfix: converting filter types between plugin and inline failed
- Updated assignments of modules to the categories
- Minor update of the help pages

## 1.5.4 *(mrbaseman, 19 April 2017)*

- Fix for opf_db functions when previous queries have triggered already an error

## 1.5.3 *(mrbaseman, 7 April 2017)*

- Added fallbacks in `opf_insert_frontend_files` in case the template lacks head
- Improved debug messages for calls to `opf_is_registered`

## 1.5.2 *(mrbaseman, 8 March 2017)*

- Use `OPF_TYPE` constants when exporting filters

## 1.5.1 *(mrbaseman, 25 January 2017)*

- Allow arrays for `$ref_name` in `opf_move_up_before`
- Switch to colon separator in AJAX helper
- If `$ref_name` is omitted in `opf_move_up_before`, move `$name` up to the top
- Update patch detection for WBCE and module filter replacement
- Suppress warning when `opf_move_up_before` is called and `$ref_name` not found
- Store active/inactive state not only inside opf but also as WBCE Settings value
- Add additional hint upon an attempt to install a module filter as a plugin
- Trigger upgrade/install of module filters
- In `opf_filter_is_active` take settings constants into account
- Immediately reflect toggle of the active/inactive state in the dashboard

## 1.5.0 *(mrbaseman, 29 September 2016)*

- Fetch `$page_id` from constant `PAGE_ID` for all cases
- Automagically initialize the global filter list
- Allow to sort filters via drag & drop
- Make exported filter human-readable
- Improve processing of formatting of documentation
- When exporting a filter create a `filter.php` file
- During export also insert placeholders introduced in version 1.4.4
- Allow to convert between inline filters and plugin filters
- Added `opf_move_up_before` to move filters up in the list during installation
- Search for pre-existing module filters during installation of opf
- Cleaned up `backend_body.js`
- Support backend filtering, provided that the class "Tool" exists and the settings have been converted to modules (this relies on developments in the WBCE 2.0-dev Branch, so you might see the checkbox for the backend, but unless the backend is implemented as modules, most probably no filters are going to be applied)

  In case you have created a filter which screws up the backend completely, add the following line to your global `config.php` of your WBCE installation:

  ```php
  define('WB_OPF_BE_OFF', 'off');
  ```

  The value doesn't really matter — just if the constant is defined, no filter will be applied to the backend and you have access to the dashboard again so you can fix the filters.

---

## 1.4

*This version is a major re-engineering of the module with the goal to use it without PMF.*  
Special thanks to all who have tested and especially to NorHei for fruitful discussions and for providing additional testing resources.

### 1.4.9 *(mrbaseman, 23 March 2016)*

- Change cachecontrols filter type to page(last)
- Realign code: wrap long lines and set tab width=4
- Make help browser work without JavaScript
- Fix CSS edit for filters that supply CSS files
- Provide export download link when JavaScript is disabled
- Allow to delete filters even without JavaScript
- Added French and Italian language support

### 1.4.8 *(mrbaseman, 21 February 2016)*

- Remove unused code part from `add_filter` to fix array conversion warnings
- Update plugin filters to use new placeholders introduced in 1.4.4  
  *(in order to pick up this change you have to remove the example filters before upgrading OpF)*
- Update documentation: added a section which explains the use of the constants and placeholders

### 1.4.7 *(mrbaseman, 21 February 2016)*

- A couple of cosmetic bugfixes (correctly display Umlauts in filter list, replace German ss special char with ss in filter description, remove unused `filter_id` from template parsing, properly initialize `TPL_EXTRA_FIELDS_BLOCK` in edit_filter, fix for using global `LANG` inside method in WBCE)
- Security fix: check ftan for upload
- Several fixes for arrays as additional field (like in correct date example)
- Fix CSS edit for filters that provide CSS

### 1.4.6 *(mrbaseman, 18 February 2016)*

- Add module icon for WBCE 1.2
- Bugfix: do not allow moving up uppermost filter
- Switch from FTANs to IDKEYs in many places of the backend

### 1.4.5 *(mrbaseman, 09 February 2016)*

- Update documentation layout
- Include example plugins again
- Update patch check
- Merge in Stefek's changes, thanks for your contribution and for your ideas
- Automatically remove naturaldocs sources during install
- Various minor changes to make it working in upcoming wb-classic releases

### 1.4.4 *(Stefek, 05 February 2016)*

- Added the following tokens to apply instead of hardcoded paths within OpF-Plugins:
  - `{SYSVAR:WB_URL}` = will replace with the content of the constant `WB_URL`
  - `{SYSVAR:WB_PATH}` = will replace with the content of the constant `WB_PATH`
  - `{OPF:PLUGIN_URL}` = will replace with `WB_URL.'/modules/outputfilter_dashboard/plugins/{your_plugin}'`
  - `{OPF:PLUGIN_PATH}` = will replace with `WB_PATH.'/modules/outputfilter_dashboard/plugins/{your_plugin}'`
- Added 2 constants to work with OPF Plugins:
  - `OPF_PLUGINS_PATH`
  - `OPF_PLUGINS_URL`  
    The path and URL to the plugins folder is very long and is being used extensively in Filters, therefore the constants are a convenient addition.

### 1.4.3 *(mrbaseman, 03 February 2016)*

- Fix ftan for sp5 and for singletab mode in earlier versions
- A couple of fixes for the additional fields loop
- Correctly unregister from pmf

### 1.4.2 *(mrbaseman, 29 January 2016)*

- Add default value for `$opt` in `opf_controller`

### 1.4.1 *(mrbaseman, 26 January 2016)*

- Fix headers/ftan issue for latest WBCE versions

### 1.4.0 *(mrbaseman, 26 January 2016)*

- All references to PMF removed
- First release of the new series: removed dependencies from pmf
- Use phplib templates and usual language files instead of the solutions of PMF
- Removed cache functions which are disabled by default and for practical use anyway
- Switched from tokens to ftan support
- Update documentation (current patches and removed references to PMF from the documentation as well)

---

## 1.3.4 *(thorn, 07 August 2010)*

- Fixed an issue with the CSS-Editor: it may lose all data on save under special circumstances.
- Fixed wrong usage of `pmf_fetch_clean()` after pmf has changed order of parameters in this function.
- Fixed docu about Simple HTML Dom Parser
- Added function `opf_register_document_ready()` to use jQuery's `$(document).ready()` to register JS in page's `<head>` section.
- Changed `opf_insert_frontend_files()` to add additional JS before the closing `</head>` instead of directly after `<head>`.

## 1.3.3 *(thorn, 31 July 2010)*

- Require pmf 0.9.5
- Check versions of plugin-filter during upload, do not allow to overwrite newer versions by older ones.
- Added documentation for usage of PHP Simple HTML DOM Parser
- Documentation updated.
- Minor issues fixed.

## 1.3.2 *(thorn, 25 July 2010)*

- Plugin-Filters can have their own `plugin_uninstall.php` now.
- `additional_fields`: added `"label"` as alias for `"text"` (the text used as label), and allow usage of an array for different languages.

## 1.3.1 *(thorn, Christian M. Stefan; 23 July 2010)*

- First public beta of this new version.

## 1.3.0 *(thorn, Christian M. Stefan; 09 April 2010)*

- Renamed module "outputfilter-dashboard", bumped version to 1.3.0
- Many additions
- New layout/design by Christian M. Stefan
- Prevent inline-filters from overwriting other filters
- Changed name from Frontend-Filter to OutputFilter Dashboard (aka opf-dashboard, aka opf)
- Added plugin-filters and filter-upload
- Added export of installed filters
- Usage of pmf's caching-system for filter and page data
- Usage of "practical module functions" (pmf)
- Usage of jQuery

---

## 1.2.2 *(thorn; 15 November 2009)*

- Added new (English/German) documentation, removed old (German) one
- Store filter and page data in SESSION
- Updated for use with WB2.8
- Added module-category "form" for form, formx, mpform
- Removed "Searchresult Highlighting" filter

*(some entries missing)*

---

## 1.1.0 *(thorn; 12 January 2009)*

- `opf_register_filter()`: added `additional_fields` and `additional_fields_languages`, to add additional config fields.  
  For Filters without own backend, which need few config-elements.  
  Supported Field-Tags: `"text"`, `"textarea"`, `"editarea"`, `"select"` (no multi-select), `"radio"`, `"checkbox"`.  
  Additional Field-Tags: `"array"`, will convert arrays (simple arrays only) into pairs of text-fields.  
  `"textarea"` and `"editarea"` support arrays (simple arrays only) [not recommended]

---

## 1.0.7 *(thorn; 09 January 2009)*

- `opf_register_frontend_files()`: added parameter `iehack`.  
  To add an IE-conditional e.g. `[if lt IE 7]`, which will add `<!--[if lt IE 7]><script ...></script><![endif]-->`

## 1.0.6 *(thorn; 02 January 2009)*

- Fixed some issue with allowedit and EditArea.

## 1.0.5 *(thorn; 31 December 2008)*

- Added missing braces in `opf_unregister_filter()`
- Changed "css editor" to use EditArea-Editor, too.

## 1.0.4 *(thorn; 30 December 2008)*

- Changed "add filter" and "edit filter" to use the new EditArea-Editor, if available.

## 1.0.3 *(thorn; 29 December 2008)*

- Added missing `$wb` in "Add filter" function-template
- CSS-Editor may fail on some servers, due to disabled `escapeshellcmd()`-function. Fixed.

## 1.0.2 *(thorn; 27 December 2008)*

- Undo one fix from 1.0.1.
- Fixed an issue with mangled description (missing `unserialize()`-call in `opf_save()`).

## 1.0.1 *(thorn; 27 December 2008)*

- Fixed two "call-time pass-by-reference"-issues. Thanks to Ralf (Berlin) for pointing this out.

## 1.0.0 *(thorn; 26 December 2008)*

- Initial version
