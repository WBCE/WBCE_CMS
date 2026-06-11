
## wysiwyg — Changelog

### 3.1.0 — 2026-05-31
> This update implements AjaxSave, Saving of WYSIWYG sections without page reload.

#### modify.php
- Added `Alerts::ensureToastAssets()` to inject `_toast.inc.twig` into the page, guaranteeing `window.showToast()` is available when AJAX responses arrive
- Added `I::insertJsFile(…/ajax_save.js, 'BODY BTM-')` — loads AjaxSave JS once, deduplicated across multiple sections on the same page
- Form extended with `data-ajax-url` attribute and a hidden `idKey` field (`$admin->getIDKEY($section_id)`) for AJAX security
- Added AjaxSave checkbox per section; "Save & Back" button is hidden while AjaxSave is active
- Removed `$update_when_modified = true` — timestamp is now set explicitly in save.php after the upsert

#### save.php
- Added explicit `$admin->touchSection((int) $section_id)` after the upsert  
  (previously `$update_when_modified = true` caused the touch to run before the DB write)

#### ajax_save.php _(new)_
- AJAX endpoint for in-place saves without page reload
- IDKEY validation (`$admin->checkIDKEY(…, $ajax: true)`) — non-consuming, allowing repeated saves in the same session
- Content processing identical to `save.php` (absolute media URL → `{SYSVAR:MEDIA_REL}` replacement)
- `$database->upsertRow()` + `$admin->touchSection()` after successful write
- `(new Alerts(false))->toast()` sets `HX-Trigger` header for success and error feedback

#### ajax_save.js _(new)_
- Editor-agnostic AjaxSave script, loaded once via `I::insertJsFile`
- Checkbox state persisted to `localStorage` per section (`wysiwyg_ajaxSave_{sid}`)
- Form submit intercepted when checkbox is checked — fires AJAX save instead of full POST
- Ctrl+S on the outer document triggers AJAX save when checkbox is active
- Ctrl+S inside a **CKEditor** iframe wired via `editor.addCommand()` / `editor.setKeystroke()`
- Ctrl+S inside a **TinyMCE** iframe wired via `editor.addShortcut()`
- Content sync before serialise: CKEditor → `updateElement()`, TinyMCE → `ed.save()`
- Toast feedback via `fireToast()`: reads `HX-Trigger` response header set by `Alerts::toast()` and calls `window.showToast()`

### 3.0.0 — 2026-04-27

> This update was done to implement all new features that came with WBCE CMS 1.7.0
> Most notable of which is the new PDO Database class and the Lang class

#### install.php
- SQL moved to `install_struct.sql` — uses `{TP}` prefix and `{TABLE_ENGINE}` placeholder
- Engine changed from `MyISAM` to `InnoDB`
- Charset/collation upgraded from `utf8` / `utf8_unicode_ci` to `utf8mb4` / `utf8mb4_unicode_ci`
- Uses `$database->importSql()` instead of inline `$database->query()`

#### install_struct.sql _(new)_
- Canonical SQL schema using `{TP}mod_wysiwyg`, `{TABLE_ENGINE}` placeholders

#### upgrade.php
- Engine converted from `MyISAM` to `InnoDB` via `ALTER TABLE`
- Charset/collation upgraded to `utf8mb4` / `utf8mb4_unicode_ci` via `ALTER TABLE … CONVERT TO CHARACTER SET`
- Media URL replacement uses `?`-parameters instead of string interpolation
- Replaced `$database->get_error()` with `$database->getError()`
- Replaced `$database->query()` with `$database->query($sql, $params)`

#### view.php
- Replaced `$database->get_one()` + string-interpolated SQL with `$database->fetchValue($sql, $params)`
- Added `?? ''` null-coalescing fallback

#### modify.php
- Replaced `$database->get_one()` with `$database->fetchValue($sql, $params)`
- Replaced `while ($row = $query->fetchRow())` loop with `$database->fetchAll($sql, $params)` + `array_map()`
- Removed `$database->query()` return value check in favour of `hasError()`

#### save.php
- Replaced `$database->escapeString()` + string-interpolated `UPDATE` with `$database->upsertRow()`
- Replaced `$database->is_error()` with `$database->hasError()`
- Replaced `$database->get_error()` with `$database->getError()`
- `require()` calls converted to `require` (no parentheses)

#### add.php
- Replaced inline `INSERT … SET` string query with `$database->insertRow('{TP}mod_wysiwyg', [...])`
- Values cast to `(int)` explicitly

#### delete.php
- Replaced raw `DELETE` string query with `$database->deleteRow('{TP}mod_wysiwyg', 'section_id', (int) $section_id)`

#### languages.php
- a single languages.php file now replaces the /languages directory that shiped files for each individual language
- all languages WBCE languages are supported now
