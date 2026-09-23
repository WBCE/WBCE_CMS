# CHANGELOG — Droplets

## v2.4.7 — 2026-09-23 (Christian M. Stefan)
- Fixed the Droplet-deduplication check in `processDroplets()`: it used
  `array_key_exists()` to test whether a tag string was already processed,
  but `$droplet_tags` is only ever appended to with numeric keys. 
  A separate associative `$seen` array (tag string as key) now does the check, 
  while `$droplet_tags`/`$droplet_replacements` stay index-based for `str_replace()`. 
  As a side effect, this also cuts one DB lookup + one `eval()` per repeated 
  occurrence of the same droplet placeholder on a page.
  Reported in the forum by user **beach**, who tracked down the root cause
  and proposed the exact fix used here.

## v2.4.6 — 2026-08-18 (Christian M. Stefan)
- AjaxSave (the CodeEditor toolbar's "AjaxSave" checkbox / Ctrl-S in
  `ajax_save_droplet.php`) used to POST only `{ idKey, code_area_text }` and
  write just `code`/`modified_when`/`modified_by` to the DB, so any other
  field the admin had changed on the same page load (title, description,
  comments, active, admin_edit/admin_view) was silently discarded. Now saves
  all form data via Ajax correctly.

## v2.4.5 — 2026-08-10 (Christian M. Stefan)
- Multi-driver SQL corrections (MySQL/SQLite) as preparation for
  experimental SQLite readiness.

## v2.4.4 — 2026-08-09 (Christian M. Stefan)
- Wired the new `framework/CodeVet.php` into every save path
  (`ajax_save_droplet.php`, `save_droplet.php`,
  `functions.inc.php::check_droplet_syntax()`): saved Droplet code is now
  scanned for `eval()`-in-code, dynamic/variable function calls, backticks,
  system/obfuscation functions and superglobal access — not just checked for
  valid PHP syntax.
- `check_droplet_syntax()` intentionally keeps `CodeVet::scan()` out of its
  own path (it re-runs on every admin overview load, not just on save) — the
  security scan is enforced once, at save time.

## v2.4.3 — 2026-08-09 (Christian M. Stefan)
- Fixed a SQL injection in the per-request Droplet lookup (`droplets.php`);
  the name was interpolated straight into a `LIKE` clause, now parameterized.
- Removed `addslashes()`/`add_slashes()` before PDO writes; it was corrupting
  stored titles/comments/code containing quotes.
- Migrated remaining mysqli-style calls to the PDO API
  (`fetchValue`/`fetchAll`/`insertRow`/`upsertRow`/`deleteRow`/`hasError`/`addField`).
- Fixed `if (!$database->query/insertRow(...))` checks that could never
  detect a failure (those calls never return a falsy value).
- Rebuilt the admin list on the same cp-table chrome as `admin/users`
  (cp-table/table-bordered/table-hover, sort icons, working
  search-as-you-type filter; fixed a `bcp-pane-inlay` typo that ate the
  content padding).
- Replaced `jquery.tablesorter.js` with a small vanilla-JS click-to-sort
  script; dropped `js/mdcr.js` (unreferenced legacy email-obfuscation
  script) and `img/*.gif` sort icons (superseded by text icons).
- Aligned the inline delete-confirm row class with `cp_theme.css`
  (`on-delete`, not `row-on-delete`) so it gets the warning color.

## v2.4.2 — 2026-04-24 (Christian M. Stefan)
- Set `$core` var.
- Removed deprecated `$module_level` var.

## v2.4.0 — 2023-02-06 (Christian M. Stefan)
- Moved the OpF filter file to `/modules/droplets/` and removed the
  `/modules/mod_opf_droplets/` directory.

## v2.3.4 — 2023-02-05 (Christian M. Stefan)
- Improved JavaScript behavior: inline delete confirm; deactivate a droplet
  from within the droplets list.

## v2.3.3 — 2023-02-04 (Christian M. Stefan)
- Expanded the readme/help file.
- Added a German translation for the readme/help file.
- Inserted credits of the original and subsequent module developers.

## v2.3.2 — 2023-02-02 (Christian M. Stefan)
- Template rework making the module ACPI-ready.
- Added a setting to show the date (latest edit/creation).

## v2.3.1 (Colinax)
- Added the `mp4video` Droplet.
- Removed some old Droplets.

## v2.3.0 (Bianka Martinovic, "WebBird")
- Fixed issues with the changed Twig version and PHP 8.

## v2.2.9 (Bernd)
- Fixed `upgrade.php` regarding MySQL-Strict / Doctrine.

## v2.2.8 (Bernd)
- `MYSQL_ASSOC` → `MYSQLI_ASSOC`.

## v2.2.7
- Added `module_level` core status.
- Updated `module_platform`.

## v2.2.6
- `SectionPicker` Droplet uses the `Insert` class.

## v2.2.5
- Small fixes.

## v2.2.4 (norhei)
- Added support for backend droplets.

## v2.1.4 (colinax)
- Reverted "Fix for no droplet bug" — the fix did not work correctly.
- Added some missing CSS styles.

## v2.1.3 (colinax)
- Fix for the "no droplet" bug.

## v2.1.2 (colinax)
- Bugfix for the backup function.

## v2.1.1 (colinax)
- Some template bugfixes.

## v2.1.0 (colinax)
- Disabled DEBUG mode in `add_droplet.php`.
- Updated language files.
- Updated help.
- Removed deprecated HTML tags in `functions.inc.php`.
- Updated `jquery.tablesorter.js` (https://github.com/Mottie/tablesorter).

## v2.0.2 (cwsoft & colinax)
- Fixed a fatal error in case the Droplet code syntax was invalid. See
  [WBCE/WebsiteBaker_CommunityEdition#216](https://github.com/WBCE/WebsiteBaker_CommunityEdition/issues/216).

## v2.0.1 (norhei & colinax)
- See [commit f6e6920](https://github.com/WBCE/WebsiteBaker_CommunityEdition/commit/f6e69206e22a6aa277b02b6c6887554a0da6371b).

## v2.0.0 (Bianka Martinovic, "WebBird")
- See [WBCE/WebsiteBaker_CommunityEdition#92](https://github.com/WBCE/WebsiteBaker_CommunityEdition/issues/92).

## v1.75 (Bianka Martinovic, "WebBird")
- Fixed an "Undefined variable: imports" issue.
- Fixed an "Undefined offset: 0 in ./modules/droplets/install.php" issue.

## v1.74 (Bianka Martinovic, "WebBird")
- Added the `shorturl` droplet to the installation.
- Fixed a small layout issue with the Flat theme.

## v1.73 (Bianka Martinovic, "WebBird")
- Added `false` to all occurrences of `new admin()`; thanks to Martin Hecht.

## v1.72 (Bianka Martinovic, "WebBird")
- Fix for WB 2.8.3 (one `intval()` too many).

## v1.71 (Bianka Martinovic, "WebBird")
- Added some minor changes from Droplets 1.2.0, which is part of the WB 2.8.3
  bundle.

## v1.70 (Bianka Martinovic, "WebBird")
- No longer uses jQueryAdmin or LibraryAdmin; the tablesorter jQuery plugin
  is now included directly. Note: jQuery itself must be loaded separately.

---

## Origins — before WBCE (pre-v1.70)

WBCE's own tracking starts at v1.70 (2015). Everything below is reconstructed
from what's still findable outside this repo — the module's own file headers,
its still-shipped upstream readme text, and the diverged post-split fork —
not from a continuous changelog. Treat dates as approximate; several could
not be pinned down at all, and that's noted where it applies.

- **Origin.** Droplets was written for WebsiteBaker by **Ruud Eisinga**
  ("Ruud", of [dev4me.com](https://dev4me.com/)) and **John** ("PCWacht"),
  under Ryan Djurovich's original WebsiteBaker copyright (2004–2009), later
  WebsiteBaker Org. e.V. (2009–2015). Every WBCE file header in this module,
  including [`droplets.php`](droplets.php) and [`info.php`](info.php),
  still carries this exact attribution chain, and the module's own
  [readme](readme/readme_EN.php) still lists "Ruud" and "John (PCWacht)" as
  original authors.

- **Pre-1.0 versioning.** The module's readme text (still shipped verbatim
  in the non-WBCE upstream fork,
  [`WebsiteBaker-modules/droplets`](https://github.com/WebsiteBaker-modules/droplets/blob/master/readme/readme.html)
  on GitHub — WBCE's own copy was rewritten in v2.3.3 above and no longer
  has this wording) documents three features added "since version 0.3":
  full-page content rewriting via the `$wb_page_data` variable, PHP-syntax
  validation with a red/blue status icon in the backend list, and no longer
  requiring a return value (`return true;` suppresses the error). This means
  the module was already versioned below 1.0 early in its life — no date
  recoverable.

- **v1.0.2 / v1.0.3.** The upstream (non-WBCE) module's own file header
  comment ([`droplets.php`](https://raw.githubusercontent.com/WebsiteBaker-modules/droplets/master/droplets.php))
  records two more pre-fork entries that never made it into WBCE's `info.php`:
  - `1.0.2` — bugfix: reused the `evalDroplet` function so extracted
    parameters are only available within the scope of the `eval()` call and
    are cleared afterward.
  - `1.0.3` — optimize: reduced memory consumption, increased speed, removed
    CSS handling, enabled nested droplets.

  No dates recoverable for either.

- **The WB 2.8.3 fork point.** WBCE's own v1.71 entry above says it "added
  some minor changes from Droplets 1.2.0, which is part of the WB 2.8.3
  bundle" — i.e., at the moment WBCE forked WebsiteBaker 2.8.3 SP3
  (2015-06-29, this repo's very first commit), the bundled Droplets module
  was at upstream version **1.2.0**. WBCE then started tracking its own copy
  under an independent counter beginning at **v1.70** — the jump from
  "1.2.0" to "1.70" is WBCE's own renumbering scheme, not a continuation of
  the pre-fork counter.

- **The parallel, non-WBCE line.** After the 2015 split, the original
  (non-WBCE) WebsiteBaker project kept evolving Droplets on its own,
  unrelated version track and had reached **v3.0.25** by 2017-03-03
  (authors listed as "Ruud and pcwacht, Luisehahne" — Luisehahne joined
  only on this side of the split). See
  [`WebsiteBaker-modules/droplets`](https://github.com/WebsiteBaker-modules/droplets)
  on GitHub. This is a sibling lineage, not an ancestor of WBCE's v1.70+
  history — mentioned here for completeness only.

- **What I could not verify.** Exact release dates for the 0.x and 1.0.x
  releases, and the original announcement/author intent behind the module.
  The likely primary source — the WebsiteBaker community forum thread
  titled "New Admin-Tool: Droplets"
  (`forum.websitebaker.org`, topic 12279) — is behind a login wall today and
  was never captured by the Internet Archive's Wayback Machine (no
  snapshots exist for that URL). Fossies' historical WebsiteBaker source
  archives (which do host old tarballs back through the 2.x line) block
  automated/bot access (HTTP 401) and could not be checked either. If exact
  dates ever matter, the SMF forum's own database export or a maintainer
  with archive access would be the next thing to try.

### Sources
- [WBCE `droplets.php` — copyright header](droplets.php)
- [WBCE `info.php` — module metadata and version history](info.php)
- [WBCE `readme/readme_EN.php` — current author credits](readme/readme_EN.php)
- [`WebsiteBaker-modules/droplets` on GitHub](https://github.com/WebsiteBaker-modules/droplets) (non-WBCE fork, reached v3.0.25)
- [`WebsiteBaker-modules/droplets/droplets.php` (raw)](https://raw.githubusercontent.com/WebsiteBaker-modules/droplets/master/droplets.php) — header comment with v1.0.2/v1.0.3 entries
- [`WebsiteBaker-modules/droplets/readme/readme.html` (raw)](https://raw.githubusercontent.com/WebsiteBaker-modules/droplets/master/readme/readme.html) — "since version 0.3" feature notes
- [`WebsiteBaker-modules/droplets/info.php` (raw)](https://raw.githubusercontent.com/WebsiteBaker-modules/droplets/master/info.php) — v3.0.25 metadata, author list including Luisehahne
