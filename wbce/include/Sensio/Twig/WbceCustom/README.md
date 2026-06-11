# WBCE CMS — Twig Reference

> Valid for WBCE CMS 1.7.0+  
> All Twig templates are instantiated via `getTwig()`.  
> **No Symfony, no `app.*`, no `request.*`** — this is pure Twig with WBCE extensions.

---

## Table of Contents

1. [getTwig() — Instantiation](#gettwigtemplatepath-section_id-cache)
2. [Automatically Available Globals](#automatically-available-globals)
3. [Twig Functions (SimpleFunctions)](#twig-functions-simplefunctions)
4. [Twig Filters (Extensions)](#twig-filters-extensions)
5. [Twig Tags (TokenParsers)](#twig-tags-tokenparsers)
6. [Registering Module-Specific Twig Functions](#registering-module-specific-twig-functions)
7. [Do & Don't — Common Mistakes](#do--dont--common-mistakes)

---

## `getTwig($templatePath, $section_id, $cache)`

`getTwig()` is the only method for instantiating Twig in WBCE. It is available in both the frontend and backend.

```php
// Simple (template path only):
$oTwig = getTwig(CHUB_PATH . '/twig/');

// With section ID (loads additional section globals, see below):
$oTwig = getTwig(WB_PATH . $this->activeLopi, $section_id);

// With cache (path to a writable directory):
$oTwig = getTwig($path, null, WB_PATH . '/temp/LopiCache/');
```

### Loading and Rendering a Template

```php
// render() returns a string:
$html = $oTwig->load('my_template.twig')->render(['key' => 'value']);
echo $html;

// display() outputs directly:
$oTwig->load('my_template.twig')->display(['key' => 'value']);

// Add a global variable afterwards:
$oTwig->addGlobal('MY_VAR', $value);
```

### Configuration

| Option             | Value                                                                            |
|--------------------|----------------------------------------------------------------------------------|
| `autoescape`       | `false` — no automatic escaping; must be done manually with `\|e` or `\|raw`    |
| `strict_variables` | `false` — undefined variables return `null`, no error                           |
| `debug`            | `true` when an admin is logged in or `WB_TWIG_DEBUG = true`                     |

---

## Automatically Available Globals

These variables are **available in every template** without being passed explicitly.

### Always Available (Frontend + Backend)

| Variable         | Type   | Content                                                    |
|------------------|--------|------------------------------------------------------------|
| `FTAN`           | string | CSRF token HTML `<input type="hidden" ...>`                |
| `WB_URL`         | string | Base URL of the website, e.g. `https://example.com/wbce`  |
| `ADMIN_URL`      | string | URL to the admin area                                      |
| `INCLUDE_URL`    | string | `WB_URL . '/include'`                                      |
| `MODULES_URL`    | string | `WB_URL . '/modules'`                                      |
| `THEME_URL`      | string | URL of the active theme                                    |
| `SEC_ANCHOR`     | string | Anchor prefix for sections                                 |
| `LANGUAGE`       | string | Active language, e.g. `'DE'`                               |
| `MEDIA_URL`      | string | URL to the media directory                                 |
| `DATE_FORMAT`    | string | Date format from CMS settings                              |
| `TIME_FORMAT`    | string | Time format from CMS settings                              |
| `TEMPLATE_URL`   | string | URL of the active template (if defined)                    |
| `IS_ADMIN`       | bool   | Is the current user an admin?                              |
| `IS_SUPERADMIN`  | bool   | Is the current user a superadmin?                          |
| `USER_LOGGED_IN` | bool   | Is a user logged in?                                       |

### Only When Section ID Is Provided

When `getTwig($path, $section_id)` is called, the following are additionally available:

| Variable           | Type   | Content                                                      |
|--------------------|--------|--------------------------------------------------------------|
| `SECTION_ID`       | int    | Current section ID                                           |
| `PAGE_ID`          | int    | Page ID of the section                                       |
| `PAGE_TITLE`       | string | Title of the page                                            |
| `PAGE_URL`         | string | Full URL of the page                                         |
| `ADDON_URL`        | string | URL of the module (`WB_URL . '/modules/' . $module_dir`)     |
| `LAYOUT_BLOCK`     | int    | Template block number                                        |
| `PUBLIC_START`     | int    | Publication start (Unix timestamp, 0 = always)               |
| `PUBLIC_END`       | int    | Publication end (Unix timestamp, 0 = always)                 |
| `SECTION_POSITION` | int    | Position of the section on the page                          |

### AdminTools Only

| Variable    | Type   | Content                        |
|-------------|--------|--------------------------------|
| `TOOL_NAME` | string | Name of the AdminTool          |
| `TOOL_URI`  | string | URL to the AdminTool           |
| `ADDON_URL` | string | URL of the module directory    |

---

## Twig Functions (SimpleFunctions)

### `L_(key)` — Translation String

Calls the WBCE-native `L_()` function.

```twig
{{ L_('TEXT:SAVE') }}
{{ L_('TEXT:TITLE') }}
{{ L_('MENU:PAGES') }}
```

Format: `'ARRAY:KEY'` — e.g. `TEXT:SAVE` → `$TEXT['SAVE']`.

### `Ln_(key, n)` — Plural Form

```twig
{{ Ln_('TEXT:PAGE_COUNT', 5) }}
{# → '5 pages' (if plural form is defined) #}
```

### `getIDKEY(id)` — Secure ID Passing

Generates a signed one-time token for sensitive IDs (IDKEY system).
Equivalent to `$admin->getIDKEY($id)`.

```twig
<input type="hidden" name="item_id" value="{{ getIDKEY(item.item_id) }}">
```

### `getSetting(name, default)` — Read CMS Setting

Reads a setting from `Settings::get()`.

```twig
{{ getSetting('website_title') }}
{{ getSetting('my_module_flag', 'fallback_value') }}
{% if getSetting('frontend_login') == 'true' %}...{% endif %}
```

### `has_value(value)` — Check for Value

Returns `true` if the value is not empty (also works for arrays).

```twig
{% if has_value(item.teaser) %}
    <p>{{ item.teaser }}</p>
{% endif %}

{% if has_value(item.images) %}
    {# images is a non-empty array #}
{% endif %}
```

### `check_io(value, compare)` — Checkbox/Radio State

Returns `' checked'` if `value` matches `compare`. Loose comparison: `'1'`, `1`, and `true` all match.

```twig
<input type="checkbox" name="use_cats" value="1"{{ check_io(cfg.use_cats, 1) }}>
<input type="radio" name="sort" value="ASC"{{ check_io(cfg.sort_dir, 'ASC') }}>
```

### `insertJsFile(url, position, id)` — Include JavaScript

Equivalent to `I::insertJsFile()`. Position values: `'BODY'`, `'HEAD'`, `'BODY BTM-'`, `'HEAD BTM-'`.

```twig
{{ insertJsFile(MODULES_URL ~ '/my_module/js/app.js', 'BODY') }}
{{ insertJsFile([url1, url2], 'BODY BTM-') }}
```

### `insertCssFile(url, position, id)` — Include CSS

Equivalent to `I::insertCssFile()`. Default position: `'HEAD BTM-'`.

```twig
{{ insertCssFile(MODULES_URL ~ '/my_module/css/style.css') }}
```

### `insertJsCode(code, position, id)` — Inline JS

```twig
{{ insertJsCode('console.log("hello");', 'BODY BTM-') }}
```

### `insertCssCode(code, position, id)` — Inline CSS

```twig
{{ insertCssCode('.my-class { color: red; }') }}
```

### `insertFile(url, position, type)` — Generic Asset Loader

Automatically detects the file type from the file extension.

```twig
{{ insertFile(MODULES_URL ~ '/my_module/lib.js') }}
```

### `wordcut(str, length, break, cut)` — Truncate Text

Truncates text to `length` characters, strips HTML tags, and appends `…`.

```twig
{{ wordcut(item.teaser, 150) }}
{{ wordcut(item.description, 200, '\n', true) }}
```

### `debug_dump(var, caption, isVarDump)` — Debug Output

Equivalent to the WBCE function `debug_dump()`. Formatted `print_r()` output.

```twig
{{ debug_dump(item) }}
{{ debug_dump(cfg, 'Config array') }}
```

### `uniqid(prefix)` — Unique ID

```twig
<div id="{{ uniqid('section-') }}">
```

### `parse_simple_md_tags(str)` / `md_filter(str)` — Inline Markdown Parsing

Converts basic Markdown tags: `**bold**`, `*italic*`, `` `code` ``.

```twig
{{ item.teaser|raw }}
{# or: #}
{{ parse_simple_md_tags(item.teaser)|raw }}
```

### `theme_file(filename)` — Theme File URL

Returns the URL of a theme file, with fallback to `theme_fallbacks/`.

```twig
<img src="{{ theme_file('icons/logo.svg') }}">
```

### `theme_icon(filename)` — Theme Icon URL

Specific to backend icons from the active theme or `theme_fallbacks/icons/`.

```twig
<img src="{{ theme_icon('edit.png') }}" alt="Edit">
```

### `CodeMirror(id, syntax, options)` — CodeMirror Editor

Only available if the `CodeMirror_Config` module is installed.

```twig
{{ CodeMirror('my-textarea', 'php', {theme: 'monokai'})|raw }}
```

### `has_permission(name, type)` — Permission Check

```twig
{% if has_permission('pages_modify') %}...{% endif %}
{% if has_permission('my_module', 'MODULE') %}...{% endif %}
{# type: 'SYSTEM' (default) | 'MODULE' | 'TEMPLATE' #}
```

### `get_group_permissions(groupId)` — Group Permissions

Returns the permission structure of a group (for group management UI).

### `get_area_states(groupId)` — Area States

Returns tri-state status (`none`, `partial`, `full`) per permission area.

---

## Twig Filters (Extensions)

In addition to standard Twig filters, the following are available:

### `|strip_tags` — Remove HTML Tags

```twig
{{ item.teaser|strip_tags }}
```

### `|string`, `|int`, `|float`, `|bool` — Type Casting

Explicitly converts a value to the desired PHP type.
Registered via `CastExtension` in `TwigLoader.php`.

```twig
{# Type-safe comparison (most common use case): #}
{% if active_tag == tag.tag_id|string %}...{% endif %}
{% if "1" == use_cats|string %}...{% endif %}

{# Type conversion: #}
{{ item.price|float }}
{{ item.count|int }}
{% if item.visible|bool %}...{% endif %}
```

**Installation:**
1. Copy `CastExtension.php` to `include/Twig/WbceCustom/Extension/`
2. Add after the existing `addExtension()` lines in `TwigLoader.php`:
   ```php
   $oTwig->addExtension(new \Twig\WbceCustom\Extension\CastExtension());
   ```

### `|unserialize` — Unserialize PHP Data

```twig
{% set data = serialized_string|unserialize %}
{% for key, val in data %}...{% endfor %}
```

### Standard Twig Filters That Work in WBCE

```twig
{{ value|e }}           {# HTML-escape (alias: |escape) #}
{{ value|raw }}         {# No escaping — for HTML output #}
{{ value|default('') }} {# Fallback value if null/undefined #}
{{ value|upper }}
{{ value|lower }}
{{ value|trim }}
{{ value|length }}
{{ value|slice(0, 80) }}
{{ value|replace({'foo': 'bar'}) }}
{{ value|json_encode }}
{{ value|date('d.m.Y') }}
{{ array|join(', ') }}
{{ array|keys }}
{{ array|first }}
{{ array|last }}
{{ array|sort }}
{{ array|merge(other_array) }}
```

**Not available:** `|convert_encoding`.  
**Not available:** `app.*`, `request.*` (Symfony-specific).

---

## Twig Tags (TokenParsers)

### `{% switch %}` — Switch/Case

WBCE adds a `switch` block to Twig (not included in standard Twig):

```twig
{% switch variable %}

    {% case "value1" %}
        First case

    {% case "value2" or "value3" %}
        Second or third case

    {% default %}
        Default case

{% endswitch %}
```

---

## Registering Module-Specific Twig Functions

### Method A — `TwigFunctions.php` (recommended for `twig_extend` modules)

If a module has `'twig_extend'` in `$module_function`, `modules/{dir}/TwigFunctions.php` is loaded automatically. The `$oTwig` variable is directly available:

```php
// modules/my_module/TwigFunctions.php
$oTwig->addFunction(new \Twig\TwigFunction('df_picture',
    function (array $img, string $cut = 'th', array $attrs = []): string {
        return df_picture($img, $cut, $attrs);
    },
    ['is_safe' => ['html']]  // Output will not be escaped
));
```

```php
// info.php
$module_function = 'page,initialize,twig_extend';
```

### Method B — Directly After `getTwig()`

```php
$oTwig = getTwig(CHUB_PATH . '/twig/');
$oTwig->addFunction(new \Twig\TwigFunction('my_func', function($x) {
    return my_helper($x);
}));
```

### `is_safe` — When the Function Returns HTML

By default, return values of Twig functions are escaped. If the function returns trusted HTML, set `is_safe`:

```php
new \Twig\TwigFunction('render_widget', $fn, ['is_safe' => ['html']])
```

In the template, use **without** `|raw`:
```twig
{{ render_widget(data) }}     {# correct when is_safe is set #}
{{ render_widget(data)|raw }} {# also possible, but redundant #}
```

---

## Do & Don't — Common Mistakes

### Symfony Syntax — Does Not Exist in WBCE

```twig
{# WRONG — Symfony-specific: #}
{{ app.request.query.get('tag') }}
{{ app.user.username }}

{# CORRECT — Determine the value in PHP and pass it as a variable: #}
{# PHP: 'active_tag' => (int)($_GET['tag'] ?? 0) #}
{{ active_tag }}
```

### `|string` Filter — Now Available via CastExtension

```twig
{# Correct (after installing CastExtension): #}
{% if active_tag == tag.tag_id|string %}...{% endif %}

{# Alternative — native Twig approach without extension: #}
{% if active_tag == tag.tag_id %}          {# Direct integer comparison #}
{% if active_tag == (tag.tag_id ~ '') %}   {# Concatenation = string cast #}
```

### HTML Output from Functions

```twig
{# PHP code that returns HTML — always use |raw: #}
{{ DF_FORM|raw }}
{{ DF_IMAGE_MANAGER|raw }}
{{ FTAN|raw }}

{# Alternatively, set is_safe=['html'] in the function definition #}
```

### Escaping

```twig
{# autoescape is false — escape manually where needed: #}
{{ item.title|e }}           {# HTML-safe #}
{{ item.user_html|raw }}     {# Trusted HTML #}
<input value="{{ item.title|e('html_attr') }}">  {# In attributes #}
```

### FTAN in AJAX Forms

```twig
{# The FTAN global contains the complete <input> element: #}
{{ FTAN|raw }}

{# For AJAX (token value only): #}
ftan: '{{ FTAN|strip_tags }}'
```

### `strict_variables = false`

Undefined variables return `null`, not an error. Use `default()` for safety:

```twig
{{ item.optional_field|default('') }}
{% if item.images|default([])|length > 0 %}
```

---

## Lopi Path Extension

When `getTwig($path, $section_id)` is called and the module has a `LayoutPlugins/` directory, it is automatically added to the Twig loader as a search path. This allows `{% include 'my_partial.twig' %}` relative to `LayoutPlugins/`.

Likewise, `templates/{theme}/mod_{module_dir}/LayoutPlugins/` is added as a search path if it exists — enabling theme overrides for Lopi templates.

---

*WBCE CMS 1.7.0 — https://wbce.org*  
*Document created based on the TwigWbceCustom implementation, 2026-05-15*
