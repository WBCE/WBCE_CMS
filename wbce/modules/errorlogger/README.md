# Errorlog viewer

**Catch PHP warnings and errors into a logfile and read them comfortably in the backend.**

Errorlog viewer installs its own error and exception handler very early in the
WBCE bootstrap (`preinit.php` / `initialize.php`), forces error reporting to
`E_ALL` and writes everything to a private, non-public logfile instead of the
screen. The captured log is then browsable from **Admin-Tools → Errorlog
viewer**.

Because the handler is registered before the CMS core, it also catches errors
that happen while the page is still being assembled — including the URL that was
being requested when the error occurred, which makes a problem far easier to
locate.

A second job, unrelated to PHP errors: the **CodeVet** tab surfaces the security
audit log written by `framework/CodeVet.php` — a running record of every Droplet,
Outputfilter or Code2 save and every add-on ZIP upload that was **blocked** or
**flagged** for dangerous code (`eval()`, shell functions, obfuscation, …). That
log is written on every such event but has no viewer of its own, so it lives
here.

---

## What it does

* Registers `set_error_handler()` / `set_exception_handler()` in `preinit.php`.
* Writes to `WB_PATH/var/logs/php_error.log.php` (or the path from
  `ini_get('error_log')` if one is configured). The file starts with a
  `<?php die() ?>` guard, so it can never be served directly.
* Records the requested URL once per request, so each error block shows which
  page triggered it.
* Never prints errors to the visitor — `display_errors` is forced off by the
  handler regardless of the PHP configuration.
* Does **not** write the CodeVet log — that is `framework/CodeVet.php`'s job.
  This tool only *reads* `var/code_vet/codevet.log` for the CodeVet tab.

## The backend tool

The tool has three tabs: **Error-Log**, **CodeVet** and **Settings**.

### Error-Log

* **Plain / Colour / Table** view of the captured PHP error log, remembered per
  user via a cookie.
* **Search box** — a client-side live filter over the visible lines/rows.
* **Reload** and **Delete logfile** (the current file is archived as
  `<timestamp>_php_error.log.php`, never truly deleted).
* Entries logged since your last visit are highlighted.

The **Table view** parses each raw line into columns:

| Column  | Content                                                            |
| ------- | ------------------------------------------------------------------ |
| Type    | Colour-coded badge (see below), full PHP severity on hover         |
| Time    | Relative (“5 minutes ago”, localised) **and** the exact timestamp  |
| Message | The error text, plus the call context where available              |
| File    | The file that raised it, and `↳ from` the calling file             |
| Line    | Line number (a range when several occurrences are folded together) |

Consecutive identical errors are collapsed into a single row with an `×N`
counter. Type badges:

| Badge        | Meaning                                                                                                   |
| ------------ | --------------------------------------------------------------------------------------------------------- |
| `Fatal`      | Errors, exceptions, parse errors                                                                          |
| `Warning`    | Warnings (incl. Core/Compile/User)                                                                        |
| `Notice`     | Notices                                                                                                   |
| `Deprecated` | Genuine PHP language deprecations                                                                         |
| `SQL`        | Real database errors (`SQLSTATE…`, PDO/mysqli)                                                            |
| `PDO`        | `PDO_CANONICAL_DEBUG` nudges — legacy `Database::` method calls that should move to the canonical PDO API |

### CodeVet

Surfaces `framework/CodeVet.php`'s audit log (`var/code_vet/codevet.log`): every
Droplet, Outputfilter or Code2 save and every add-on ZIP upload that CodeVet
**blocked** or **flagged**. Per-profile badges, the individual findings, `↳ from`
paths, `×N` folding of repeats, and an *Archive log* / *Include archived logs*
control. Where possible the offending Droplet / Outputfilter is shown by name.

### Settings

One FTAN-protected form for the diagnostic switches:

| Setting               | Stored in                      | Effect                                                                                                                                                                                                |
| --------------------- | ------------------------------ | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `ER_LEVEL`            | `settings` table               | PHP error-reporting level for the whole site (E0–E3)                                                                                                                                                  |
| `WBCE_DEBUG`          | `var/config_constants.ini.php` | Global development mode — forces `E_ALL` + `display_errors`, mirrored as the legacy `WB_DEBUG`; also enables visible Droplet syntax errors, asset-manager console output and other module debug paths |
| `SQL_DEBUG`           | `var/config_constants.ini.php` | On a failed query, `framework/Database.php` raises a warning containing the error **and** the offending SQL                                                                                           |
| `PDO_CANONICAL_DEBUG` | `var/config_constants.ini.php` | Every legacy `Database::` method call raises an `E_USER_DEPRECATED` naming the canonical replacement — for developers porting code to the new API                                                     |

Each switch is described in full in the tool itself. All of them are meant for
development and staging — keep them off on a live site.

---

## Requirements

* WBCE CMS 1.7.x
* PHP 8.1+

## Installation

Errorlog viewer ships with WBCE CMS as a core module and is installed by
default. A standalone package is available from the
[WBCE Add-On Repository](https://addons.wbce.org).

---

## Credits

**Original author — [Ruud Eisinga](https://dev4me.com/) · Dev4me · www.dev4me.com**
Created the module for WebsiteBaker / WBCE: the early error/exception handler,
the caller-URL capture, the logfile format and the original log viewer.

Further contributions over the years by *florian*, *Colinax* and
*Christian M. Stefan* — see the change log in [`info.php`](info.php).

**WBCE 1.7.0 rework — Christian M. Stefan · [www.wbEasy.de](https://www.wbEasy.de)**

* Backend UI ported to the WBCE 1.7.0 `cp_chrome` / `cp_theme` design system
  (tabbed layout, toolbar, cards) — follows the active backend theme.
* Split into **Error-Log**, **CodeVet** and **Settings** tabs.
* Settings tab: `WBCE_DEBUG`, `SQL_DEBUG`, `PDO_CANONICAL_DEBUG` and `ER_LEVEL`
  as fully documented switches in a single FTAN-protected form, replacing the
  old unprotected GET toggle links.
* Table view rebuilt: a robust regex parser (handles brackets and quotes inside
  messages, folds multi-line errors/stack traces), Type badge column, relative
  **and** exact timestamps, dedicated File/Line columns, duplicate folding with
  an `×N` counter, and dedicated `SQL` / `PDO` badges.
* Client-side live search filter for the log output.
* Full multi-language coverage (EN, DE, NL, PL, NO, FR, IT, ES, RU).

---

## License

GNU General Public License — <http://www.gnu.org/licenses/gpl.html>
