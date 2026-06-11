# CodeMirror_Config

**CodeMirror integration for WBCE CMS**  
Provides a full-featured code editor for any `<textarea>` — syntax highlighting, code folding, search & replace, an optional toolbar and AJAX save.

Available everywhere after boot. No additional includes needed in your module.

---

## Quick Start

```php
// In view.php, modify.php or any admin script — after boot
CodeEditor::init('my_textarea', 'php');
```

That's it. The textarea with `id="my_textarea"` becomes a CodeMirror editor, inheriting the user's theme, font and font size from the CMS settings.

---

## Supported Syntax Identifiers

| Identifier               | Language                 |
| ------------------------ | ------------------------ |
| `php` `phtml` `tpl`      | PHP (mixed HTML/CSS/JS)  |
| `x-php`                  | PHP without HTML wrapper |
| `html` `htm` `htt`       | HTML                     |
| `twig`                   | Twig templates           |
| `css` `scss`             | CSS / SCSS               |
| `js` `javascript` `json` | JavaScript / JSON        |
| `xml` `svg`              | XML / SVG                |
| `sql`                    | SQL                      |
| `ini` `properties`       | INI / Properties         |
| `txt`                    | Plain text               |

**Auto-detect from file extension:**

```php
$ext    = pathinfo($filename, PATHINFO_EXTENSION);
$syntax = CodeEditor::syntaxFromExt($ext);  // e.g. 'twig', 'php', 'css'
CodeEditor::init('content', $syntax);
```

---

## Full Options Reference

```php
CodeEditor::init(string $textareaId, string $syntax, array $options = []);
```

| Option      | Type   | Default           | Description                                                                         |
| ----------- | ------ | ----------------- | ----------------------------------------------------------------------------------- |
| `height`    | int    |`'auto'` // `500`  | Height in pixels, or `'auto'` — editor grows with content                           |
| `toolbar`   | bool   | `false`           | Show the CodeEditorToolbar (font size, theme toggle, line wrap, fullscreen, search) |
| `line_wrap` | bool   | `false`           | Enable line wrapping                                                                |
| `readonly`  | bool   | `false`           | Read-only mode — user can select and copy but not edit                              |
| `ajax_save` | bool   | `false`           | Show an AjaxSave checkbox in the toolbar footer; also injects toast assets automatically (see below) |
| `ajax_url`  | string | `''`              | POST endpoint that receives the editor content                                      |
| `ajax_data` | array  | `[]`              | Additional POST fields sent with every AJAX save (IDKEY, section_id, …)             |
| `on_save`   | string | `''`              | Name of a global JS function called after a successful AJAX save                    |
| `theme`     | string | *(from settings)* | Override the user's theme — `'wbce-day'` or `'wbce-night'`                          |

---

## Examples

### Minimal — editor with toolbar

```php
CodeEditor::init('content', 'php');
```

### Small field — no line numbers, no toolbar, fixed height

For inline snippets, template overrides or single-value fields where a full editor
would be too heavy:

```php
CodeEditor::init('snippet', 'css', [
    'height'  => 120,
    'toolbar' => false,
]);
```

The textarea keeps CM's gutter disabled automatically when `toolbar` is false.
Result: a compact, borderless field that still highlights syntax.

### Read-only display

Useful for showing generated code, diffs or examples in a backend panel:

```php
CodeEditor::init('generated_sql', 'sql', [
    'height'   => 200,
    'readonly' => true,
    'toolbar'  => false,
]);
```

### Auto-growing field

Useful for config values or template snippets where content length varies:

```php
CodeEditor::init('meta_content', 'html', [
    'height'  => 'auto',
    'toolbar' => false,
]);
```

The editor starts at 80px and grows line by line as the user types.

### Twig template — via Twig SimpleFunction

`CodeEditor` is registered as a Twig function and available in all templates:

```twig
{# Simple editor #}
{{ CodeEditor('content', 'twig') }}

{# Read-only display block #}
{{ CodeEditor('preview', 'html', {height: 180, readonly: true, toolbar: false}) }}

{# Full editor with toolbar #}
{{ CodeEditor('content', 'php', {height: 600, toolbar: true}) }}
```

---

## Full Example — Editor with Toolbar and AJAX Save

This is the recommended pattern for `modify.php` in a page module or admin tool.

### Toast Feedback — Automatic

When `ajax_save: true` is set, `CodeEditor::init()` calls `Alerts::ensureToastAssets()`
automatically. This injects `_toast.inc.twig` into the page, which defines
`window.showToast()`.

The toolbar JS reads the `HX-Trigger` response header set by `Alerts::toast()` in your
endpoint and calls `window.showToast()` — no extra setup needed on the JS side.

**All you need to do:** call `(new Alerts(false))->toast(...)` in your AJAX endpoint.

Preferred message keys for AJAX save feedback:

| Situation | Key                             | EN text                                  |
| --------- | ------------------------------- | ---------------------------------------- |
| Success   | `MESSAGE:CHANGES_SAVE_SUCCESS`  | "Changes have been saved successfully."  |
| Error     | `MESSAGE:CHANGES_SAVE_FAILED`   | "Saving changes failed."                 |

> These keys are provided by `CodeMirror_Config/languages.php` and are available on every
> backend request after boot — no extra `Lang::loadLanguage()` call needed in your endpoint.

### Security — IDKEY, not FTAN

FTAN is consumed on first use (designed for one-shot form submissions). For AJAX save,
use IDKEY with `$ajax = true` — the key stays alive for repeated saves in the same session.

1. `modify.php` embeds `$admin->getIDKEY($section_id)` as a hidden field or via `ajax_data`
2. Each AJAX save POSTs the key value
3. Endpoint validates with `checkIDKEY('idKey', false, 'POST', true)` — non-consuming

### `modify.php`

```php
<?php
require '../../config.php';
$admin = new Admin('Pages', 'pages_modify');

$row      = $database->fetchRow(
    'SELECT * FROM `{TP}mod_mymodule` WHERE `section_id` = ?',
    [$section_id]
);
$editorId = 'mod_mymodule_content';

// ajax_data passes the IDKEY to every AJAX save request.
// ensureToastAssets() is called automatically — window.showToast() is guaranteed.
CodeEditor::init($editorId, 'twig', [
    'height'    => 550,
    'toolbar'   => true,
    'ajax_save' => true,
    'ajax_url'  => WB_URL . '/modules/mod_mymodule/ajax_save.php',
    'ajax_data' => ['idKey' => $admin->getIDKEY($section_id)],
]);

$admin->print_header();
?>
<form id="mod_mymodule_form" method="post">
    <?= $admin->getFTAN() ?>
    <textarea id="<?= $editorId ?>"><?= htmlspecialchars($row['content'] ?? '') ?></textarea>
</form>
<?php $admin->print_footer(); ?>
```

### `ajax_save.php`

```php
<?php
require '../../config.php';
$admin = new Admin('Pages', 'pages_modify', false);
header('Content-Type: application/json; charset=utf-8');

// Validate IDKEY — $ajax = true keeps the key alive for repeated saves
$section_id = $admin->checkIDKEY('idKey', false, 'POST', true);
if ($section_id === false) {
    http_response_code(403);
    (new Alerts(false))->toast('MESSAGE:GENERIC_SECURITY_ACCESS', 'error');
    exit(json_encode(['ok' => false]));
}

$content = $_POST['code_area_text'] ?? '';

$database->upsertRow('{TP}mod_mymodule', 'section_id', [
    'section_id' => (int)$section_id,
    'content'    => $content,
]);

if ($database->hasError()) {
    http_response_code(500);
    (new Alerts(false))->toast('MESSAGE:CHANGES_SAVE_FAILED', 'error');
    exit(json_encode(['ok' => false]));
}

// HX-Trigger header → window.showToast() in the toolbar JS
(new Alerts(false))->toast('MESSAGE:CHANGES_SAVE_SUCCESS', 'success');
exit(json_encode(['ok' => true]));
```

---

## Backward Compatibility

The legacy functions remain available and continue to work without changes.
Existing modules do not need to be updated.

```php
// Still works — delegates to CodeEditor::init() internally
registerCodeMirror('content', 'php', ['height' => 400]);

// EditArea legacy function — also still works
registerEditArea('content', 'php', null, null, null, null, null, 500);
```

The only difference: `registerCodeMirror()` now defaults `toolbar` to `false`
to preserve the previous look for modules that haven't opted in.
Pass `'toolbar' => true` to get the full CodeEditorToolbar.

---

## User Settings

The editor respects the user's preferences set in the **CodeMirror_Config AdminTool**:
theme, font family and font size. These are applied automatically — no code needed.

To lock a specific theme regardless of user preference, pass `theme` in options:

```php
CodeEditor::init('content', 'php', ['theme' => 'wbce-night']);
```

---

## Multiple Editors on One Page

Call `CodeEditor::init()` once per textarea. Core assets (CM library, addons, toolbar)
are loaded only once regardless of how many editors are on the page.

```php
CodeEditor::init('template_html', 'html',  ['height' => 300]);
CodeEditor::init('template_css',  'css',   ['height' => 200]);
CodeEditor::init('template_js',   'js',    ['height' => 200]);
```
