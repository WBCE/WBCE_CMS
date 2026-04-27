
## wysiwyg — Changelog

### 3.x.x — 2026-04-27

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
