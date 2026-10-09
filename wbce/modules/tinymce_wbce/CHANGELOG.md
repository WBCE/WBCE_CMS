# CHANGELOG — tinymce_wbce

*Changelog entries from v0.2.53 onward are written in English.*

## v0.3.4 — 2026-09-13

### Fixed
- **Link dialog "Bild" tab: picking a file in elFinder never filled in the Image URL field** (reported via forum, with screenshot). The browse button's `postMessage` listener checked for `e.data.mceAction === 'fileSelected'` — a shape that is never sent. `modules/elfinder/ef/elfinder_postmessage.php` actually posts `{ wbceMediaPick: true, url, title }`, exactly what the module's other two elFinder integrations (the image-dblclick handler and TinyMCE's own `file_picker_callback`, both in `include.php`) already listen for. Fixed `plugins/link/plugin.min.js` to use the same contract, plus the same `?select=` current-folder prefill and `e.origin` check the other two already had.

### Changed
- **Link dialog: the "Bild" (Image) tab is now labelled "Medien"/"Media"** (`$MENU['MEDIA']`, the core admin's own string — falls back to `TXT:LINKIMG_TAB` if that's ever unavailable) — it always opened the whole media library via elFinder, not an image-only picker, so the old label undersold it. Field/button text (`TXT:LINKIMG_URL`, `TXT:LINKIMG_BROWSE`) generalized from "Image URL"/"Choose image…" to "File URL"/"Choose file…" to match. This is now the intended way to link to any media file — PDF, Markdown, whatever — rather than adding a second browse button to the "Link" tab (tried, then reverted — see below). The live `<img>` preview now hides itself gracefully (via the image's own `error` event) instead of showing a broken-image glyph when the picked file isn't actually an image.

~~### Added~~ (reverted, same session)
- ~~"Browse…" button on the Link dialog's "Link" tab~~ — superseded by the "Bild" → "Medien" tab rename above; a second browse button duplicating the Media tab's job was decided against.

### Fixed
- **Re-opening a link to a non-image media file (PDF, Markdown, …) landed on the "Link" tab instead of "Medien"** (live-tested after the rename above). The tab that opens for an *existing* link was still decided purely by image file extension (`imgMatch`) — a link to `CLAUDE.md` or any other non-image never matched, regardless of the tab rename. Added a second signal, `mediaMatch`: does the href fall under the media library's own base URL (`TINYMCE_MEDIA_URL`, new — `WB_URL . MEDIA_DIRECTORY`, injected in `include.php` next to `TINYMCE_ELFINDER`), checked both as an absolute URL and, as a fallback, path-only (for root-relative hrefs). This covers every file type the Media tab's own browse button can pick, without an ever-incomplete per-extension whitelist. Where `TINYMCE_MEDIA_URL` isn't injected (e.g. the toolbar configurator's live preview), detection degrades gracefully back to the extension-only check.

### Fixed (shared library, not tinymce_wbce-specific)
- **Selecting text inside the Link dialog's URL field could close the whole dialog** (same forum report, follow-up). Root cause was in the vendored `include/micromodal/modal.min.js`, not in this module: MicroModal's `onClick` only checks which element the terminating `mouseup`/`click` landed on — a drag-selection inside the URL field can end with the cursor drifting onto `.modal__overlay` (it wraps the whole dialog as the backdrop), which the library reads as "clicked the backdrop" and closes. Patched `onClick` to also require an empty `window.getSelection()`, so a click that's the tail end of a text-selection drag no longer closes the modal. Fixes every MicroModal dialog in the project (also the preset import-collision dialog in `tool.php`), not just this one.

## v0.3.3 — 2026-08-11

### Changed
- **`LinkResolver` discovery: DB marker → filesystem glob.** Module providers no longer need `linkitems` in their `{TP}addons.function` column — `framework/LinkResolver.php`'s `discoverProviders()` now finds them with a plain `glob('modules/*/LinkResolver.php')`, same idea as the `predb_*` glob in `framework/initialize.php` (filename instead of directory-name prefix). Nothing to keep in sync in `info.php` after a module update, no DB query on every resolve. `news_img`'s and `oneforall`'s `info.php` no longer carry the marker.
- **Provider files renamed `include.php` → `LinkResolver.php`** (`modules/news_img/LinkResolver.php`, `modules/oneforall/LinkResolver.php`) — every provider now has the exact same filename, which is what made the glob-based discovery above possible in the first place.
- **New `LinkResolver::providerFor(string $keyword): ?LinkResolver`** — centralizes the "find the file, require it, verify it's a real subclass, instantiate" logic that was previously duplicated between `resolveModuleItem()` and `tinymce_wbce_link_items_for_page()`'s class-provider branch. Both now just call this.

## v0.3.2 — 2026-08-10
- **Default height was ignored in the backend** (reported by Florian): `modules/wysiwyg/modify.php` and `modules/docs_section/modify.php` both passed a hardcoded `height` option to `WysiwygEditor::init()` (350px / 500px respectively) — an explicit height always wins over the preset's own configured height (`$height ?: $preset['height']` in `tinymce_wbce_render_editor()`), so a hardcoded value silently overrode whatever an admin set in the configurator, every time. Both call sites now omit the override and let the preset's height apply. The FEE/VES modal editors (`modify_fee.php`, `ves/element_modal.php`) keep their intentional `height: '600'` — that's a documented guard against triggering the compact auto-minimal toolbar in a full-screen modal, not the same bug.

## v0.3.1 — 2026-07-21

### Fixed
- **CKEditor (and any other not-yet-migrated editor) could silently stop working** once `wysiwyg/modify.php` was migrated to `WysiwygEditor::init()`: CKEditor's `include.php` only ever defined the legacy global `show_wysiwyg_editor()`, never the dispatcher's `<dir>_wysiwyg_render()` convention, so it was simply skipped — falling through to a different installed editor, or to a bare `<textarea>` on CKEditor-only installs. Fixed at the **dispatcher level** (`framework/WysiwygEditor.php`), not with a per-module wrapper: when a candidate has no provider function, `WysiwygEditor::render()` now automatically bridges to that candidate's legacy `show_wysiwyg_editor()` before moving on. Because that function name is global, a `ReflectionFunction` ownership check confirms the currently-defined `show_wysiwyg_editor()` actually belongs to the candidate being tried (guards against a different, already-loaded legacy editor's definition being called by mistake) before using it.
- **Preset live preview didn't boot on open:** a stale event-binding call (`#tkfg-btn-reset-<id>`, a button no longer present in the markup) threw an uncaught error partway through the panel's `bind()` step, silently skipping every binding registered after it — including the `tinymce.init()` call that boots the live preview. A second dead reference (`#tkfg-save-local-btn-<id>`, left over from before the "Only me" save moved to the accordion header badge) had the same problem. Both are now null-guarded.
- **Admin tool layout wasn't responsive:** the configurator (and the tool page generally) had zero responsive breakpoints — fixed 200–220px flex columns (setting-row labels, accordion header name columns, the toolbar-row builder) overflowed instead of wrapping on narrow viewports. Added breakpoints at 900px and 520px across `css/tool.css`, `css/toolbar-configurator.css`, `css/preset-accordion.css` that stack labels above controls and let accordion headers wrap onto multiple lines.
- **Linking an image replaced it with the link:** the "link" plugin's insert/edit logic always used the anchor's plain-text content (`anchor.textContent = text`, or `insertContent('<a>'+text+'</a>')`) — but an image selection has no text, so applying a link either wiped an existing image out of its anchor or replaced the selected `<img>` outright with a text-only link. Both paths now detect an image-only selection/anchor (`selectedImageNode()`) and preserve the actual `<img>` element — updating just the anchor's attributes when editing an existing image link, or wrapping the existing `<img>` node (instead of re-inserting text) when creating a new one. The now-irrelevant "Text to display" field is hidden in that case.

### Changed
- **Live preview** now defaults to ON as soon as a profile panel is opened, instead of requiring a manual click or checkbox toggle first.
- **"Refresh" button** relabelled "Refresh preview" for clarity.
- **`editor_css_source`** (which editor.css layer wins) moved from a single global setting to a **per-preset** field — different fields on the same page can now show different in-editor CSS on purpose. The global panel now only shows a live, read-only scan of which editor.css files exist, for reference.
- **Inline (FEE) presets:** dropped the `content_theme` and `statusbar` fields — inline editing has no iframe, so neither ever had a visible effect (`fee_config.php` never emitted `content_theme`; TinyMCE's inline mode has no fixed slot for a status bar). Every remaining field (toolbar, profile name, min height, skin, menubar) now has a short inline hint explaining what it does.
- **"Import preset"** control moved to sit after the config-profile accordion (it imports full presets, not inline ones).
- Toolbar-profile and inline-profile sections now carry a short, translated intro paragraph explaining what they are and how "Default" / "Only me" resolution works.

### Added
- **Second, lightweight preset list for inline (FEE) editing** — `inline_presets` + `default_inline_preset` in the v2 config. A dedicated accordion with far fewer settings than a full profile: a single drag-and-drop toolbar row plus min-height/skin/menubar, built as a self-contained module (`js/inline-presets.js`) that reuses the full configurator's chip/icon system without depending on it. The legacy single `inline_toolbar` string is migrated into the `default` inline preset on first load and kept as a back-compat mirror.
- FontAwesome 4.7 icons on every "Typography & formats" sub-section header (Fonts, Font sizes, Block formats, Colors, Version history, Paste images, Link options).
- The inline (FEE) presets section is now hidden entirely when the `fee` module isn't installed.
- **Link to a specific item on a page, not just the page itself** — the "link" plugin's page picker now offers a second, page-scoped picker underneath once a page with linkable module content is selected (or immediately, when editing an existing such link). Modernizes what `modules/ckeditor/ckeditor/plugins/wblink/pages.php` did for CKEditor — section-scoped instead of dumping every item on the whole site into one JS blob per editor load, PDO'd instead of raw SQL string concatenation, real JSON instead of `addslashes()`-as-JS-escaping. Five legacy modules ship as built-in stand-ins (`news`, `topics`, `bakery`, `procalendar`, `responsiveFG` — none exist as real modules in this repo); real modules opt in simply by having a `LinkResolver.php` at the root of their module directory (see `news_img`/`oneforall` below) — no `{TP}addons` marker needed, `framework/LinkResolver.php` finds them with a filesystem glob. New files `link_items.php`, `ajax_link_items.php`. `news` and `bakery` (both stand-ins) build their URL through the core `page_link()` instead of concatenating `WB_URL`/`PAGES_DIRECTORY`/`PAGE_EXTENSION` directly, so they pick up the planned slug-based routing rework (`.claude/routing-redesign.md`) automatically once that lands — `topics` couldn't follow (its `/topics/` sub-path has no equivalent in `page_link()` today) and was deliberately left as direct concatenation rather than forcing a fragile workaround.
- **`framework/LinkResolver.php`** — new class, both the static dispatcher and the base class module providers extend. Resolves `[wblinkNN]` (deprecated but still supported), the new `[pagelink:NN]`, and generic `[<module>:NN]` tokens to real URLs at **render time** instead of freezing a URL at save time. Wired into the existing `opf_wblink.php` output filter (`modules/outputfilter_dashboard/plugins/core_outputfilters/opf_wblink.php`) — no new filter added; its old hand-rolled `[wblinkNN]` regex/SQL/`is_readable()` logic is now a one-line call to `LinkResolver::resolveContent()`. Note: the previous `is_readable(WB_PATH.$link)` guard (only replace a `[wblinkNN]` token if the target page's physical file still exists on disk) is **not** carried over — `page_link()` returning an empty string is now the only "unresolvable" signal. Module providers are a single class per module (`class <StudlyCaseDir>LinkResolver extends LinkResolver`, with `linkItems()` for the picker and `resolve()` for render-time lookup) rather than a pair of loosely-named functions — one registration point instead of two. Discovery is a plain filesystem glob on `modules/*/LinkResolver.php` (same idea as the `predb_*` glob in `framework/initialize.php`, just matched on filename instead of a directory-name prefix) — no `{TP}addons.function` marker, nothing to keep in sync in `info.php` after an update. The keyword in a `[<module>:NN]` token is always the module's directory name; there's no separate short-alias mechanism.
- **`news_img` migrated to a real `LinkResolver` provider** (`modules/news_img/LinkResolver.php`, new file) — a built-in provider backed by a module that actually exists in this repo. Its picker entries now emit `[news_img:NN]` tokens instead of a resolved URL, which also fixes the picker's known limitation (couldn't pre-select the item when re-opening an existing link) for this module going forward, since a token round-trips cleanly while a frozen URL didn't.
- **`oneforall` migrated to a real `LinkResolver` provider** (`modules/oneforall/LinkResolver.php`, new file) — the more involved of the two real migrations: an item's own `link` column only holds the item's URL fragment (e.g. `/my-item`), the full URL also needs the carrier page's own `link` (item.page_id → {TP}pages join), reproducing what `save_item.php` does when it writes the physical access file. Also respects `view_detail_pages`, a module-wide (not per-section) on/off switch in `{TP}mod_oneforall_general_settings` — when it's off, `resolve()` returns `null` for every item regardless of section, same as the module itself not generating any access files in that state.

---

## v0.3.0 — 2026-07-13

### Added
- **`WysiwygEditor` dispatcher** (`framework/WysiwygEditor.php`): a modern, editor-agnostic facade — `render()` / `init()` — with two independent fallback chains (`editor`: which editor module; `config`: which preset inside it). No registry: editor modules are discovered via `{TP}addons` (type=module, function LIKE '%wysiwyg%') and resolved by convention (`<module_dir>_wysiwyg_render()`). Degrades to a plain `<textarea>` when nothing is installed. Documented in `framework/WysiwygEditor.md`.
- **FEE inline editing as the default**, with a `⧉` button to escalate to the full, backend-parity editor in a modal (`Fee::renderModal()` → `modify_fee.php` in an iframe, synced back via `postMessage`). New endpoint `fee_config.php` serves the inline toolbar/plugin config as JSON.
- Editor-agnostic AJAX save / Ctrl+S convention: `window.WBCE_WYSIWYG_FLUSH` (array of flush callbacks), field read by name — not id — after flushing. Adopted by `docs_section`'s AJAX save and `modify_fee.php`.
- **Version-history cleanup without a cron:** an opportunistic sentinel compares `NOW()` against stored entries on snapshot/open, gated by an on/off setting and a configurable max age. Individual history entries can now be deleted (× with a confirmation prompt).
- **editor.css configurator awareness:** a live, page-template-aware scan (only templates actually installed/in use, via `{TP}pages.template` ∪ `DEFAULT_TEMPLATE`) shows which `editor.css` files exist and may override the content theme.
- **Preset management:** rename (label only — the id is a stable API key other modules reference), delete (custom presets), reset (built-ins, from `presets/defaults/*.json` when present), export/import with an overwrite-vs-copy collision dialog.
- **CSS class picker** for the Styles dropdown, sourced from the template's `editor.css` — `auto` (importcss) or `annotated` (`/* tinymce: label="…" */` comments) modes.
- **Word count** as a per-preset setting.
- **Alt-text reminder:** a small ⚠ marker (opt-out) on images missing an `alt` attribute.
- **Paste → WebP:** pasted clipboard images can be converted to WebP via `claviska/SimpleImage` (bundled under `/include/`), as a per-preset setting.

### Fixed
- FEE modal editor: a `height: '68vh'` value was silently cast to `(int)`, becoming `68` (px) — this tripped the auto-minimal toolbar switch and produced a tiny canvas with the wrong (compact) toolbar. Fixed by passing a real pixel height well above the threshold.
- `docs_section`'s AJAX save (including Ctrl+S) didn't work with TinyMCE selected — root cause was Ctrl+S being trapped inside the TinyMCE iframe instead of reaching the host page. TinyMCE now forwards it via `addShortcut('meta+s', …)` → `form.requestSubmit()`.

### Changed
- `wysiwyg/modify.php`, `wysiwyg/modify_fee.php` (new), and `docs_section/modify.php` adopted `WysiwygEditor::init()`.
- `docs_section` no longer hardcodes TipTap — editor choice is now a fallback chain (`tiptap_editor>tinymce_wbce`).

---

## v0.2.54 — 2026-07-10

### Added
- **Color swatch editors** per preset (forecolor / backcolor / icon palettes, shared or split mode, column count, custom-color toggle) using **wbeColoris** (bundled under `include/wbeColoris/`), sanitized server-side through core `sanitizeCssColor()`.
- **Free version-history plugin** (`wbce_history`) as a no-cost replacement for TinyMCE's premium Document History: one row per editor instance in `{TP}mod_tinymce_history`, a rotating JSON array of snapshots (max 10, deduplicated by content hash). Snapshotted client-side (debounced change/blur/submit) since most WBCE editors have no dedicated save hook; the `wysiwyg` module additionally snapshots on the real, server-side save.
- New bundled plugins: `wbce_casechange` (lower/UPPER/Title/small-caps), `wbce_shy` (soft hyphen, visualized via TinyMCE's `visualchars`), `fa_picker` (Font Awesome icon picker), `wbce_codesample` (CodeMirror 5 popup for syntax-highlighted code blocks). The menu bar's Insert menu mirrors whichever of these buttons are actually in the configured toolbar.
- **Creatable link options:** the link dialog's class/rel fields are now creatable multi-selects (via a new `allowNew` option added to `wbeSelect`, reusable project-wide) backed by a per-preset, server-authoritative token list.

---

## v0.2.53 — 2026-07-05

### Added
- **Preset accordion redesign** (Phase 1 & 2): the flat global `tinymce_cfg` became named **presets**, each a complete, self-contained editor profile (toolbar rows, small-toolbar row, height, skin, menubar/statusbar, content theme, fonts, font-size unit, custom style formats). A `default_preset` pointer replaces the old single active toolbar; the tool collapses to one accordion tab, one panel open at a time, lazy-loaded via HTMX.
- Per-preset drag-and-drop toolbar configurator, with a live TinyMCE preview that boots on panel expand and tears down on collapse.
- Typography sub-sections per preset: curated font-stack catalogue plus custom entries, a size-unit-aware size list with sane defaults, block-format checkboxes, and custom style rows (title/element/class → `style_formats`).
- **Localized (i18n) throughout:** `languages/EN.php` + `languages/DE.php` replace the old single `languages.php`; all admin-tool strings and JS-side copy migrated to `L_()` / `TKFG_I18N`.

---

## v0.2.52 — 2026-06-14

### Behoben
- **Dark-Skin:** Format-Dropdown (Absatz/Überschriften) — Vorschau-Texte waren im Dark-Modus zu dunkel (tinymce_backend.css)

### Geändert
- **Tab 1:** Umbenennung "Grundeinstellungen" → "Allgemein" (Feedback Florian)

---

## v0.2.51 — 2026-06-14

### Behoben
- **Toolbar-Konfigurator:** Buttons aus Zeilen 1–3 konnten nicht in die Kleine Toolbar (<200px) gezogen werden — `draggable` im Pool war fälschlicherweise `false` für bereits verwendete Buttons
- **Toolbar-Konfigurator:** Wechsel von Custom-Toolbar zurück auf Standard/Minimal/Vollständig hatte keine Wirkung wenn localStorage-Config aktiv war — nach Tab-1-Save mit Preset ≠ `custom` wird localStorage jetzt automatisch geleert

---

## v0.2.50 — 2026-06-14

### Geändert
- **Tab 1:** Umbenennung "Grundeinstellungen" → "Allgemein"

---

## v0.2.49 — 2026-06-14

### Behoben
- **elFinder-Bridge:** URL-Parameter `?callback=` war durch fehlendes `?` als `&callback=` kodiert → "Not Found" beim Öffnen der Mediathek

---

## v0.2.48 — 2026-06-14

### Behoben
- **tool.php:** Syntax-Fehler "Unmatched }" nach Entfernen von Tab 3 — `header()`/`exit` außerhalb des POST-Handlers gelandet, toter `foreach`-Block und verwaiste Tab-3-JS-Fragmente entfernt

---

## v0.2.47 — 2026-06-14

### Entfernt (Feedback Florian)
- **Tab 3 Mediathek-Theme** vollständig entfernt — elFinder 2.1.68 benötigt kein Custom-Theme mehr
- `generate_theme.php` wird nicht mehr aufgerufen (Datei bleibt für Rückwärtskompatibilität)
- `theme=` URL-Parameter aus `file_picker_callback` entfernt
- 7 Farb-Einstellungs-Keys (`ef_theme`, `wbce_toolbar`, `wbce_toolbar2`, `wbce_accent`, `wbce_navbar`, `wbce_status`, `wbce_icon_color`, `wbce_icon_hover`) aus Defaults entfernt

### Geändert
- **`elfinder_tinymce.php`** radikal vereinfacht: nur jQuery, jQuery-UI, elfinder.min.js — kein RequireJS, kein Theme, kein MutationObserver
- **`upgrade.php`:** `$obsoleteKeys` entfernt beim nächsten Upgrade automatisch veraltete Keys aus `tinymce_cfg`

---

## v0.2.46 — 2026-06-14

### Behoben
- **Dark-Skins (`oxide-dark`, `tinymce-5-dark`):** Dialoge (Bild einfügen, Medien einfügen) zeigten Upload-Icon-Button unsichtbar — SVG-Icons `fill: #ffffff` erzwungen
- **Helle Skins:** Dialog-Overrides (weißer Hintergrund) greifen jetzt nur noch wenn `html.wbce-tmce-skin-light` gesetzt — kein Konflikt mehr mit Dark-Skins

### Neu
- `SkinLoaded`-Event in `setup`-Callback: setzt/entfernt CSS-Klasse `wbce-tmce-skin-light` am `<html>`-Element je nach aktivem Skin

---

## v0.2.45 — 2026-06-13

### Behoben
- **elFinder theme-wbce:** `elfinder.min.css` wird jetzt separat vor `theme-wbce.min.css` geladen — Basis-Layout-Fix für Darstellungsfehler (Opera/Firefox)
