# OutputFilter Dashboard — Developer Guide

How to write, package and ship an output filter for WBCE.

Every function mentioned here is documented in full in the
[API Reference](API_REFERENCE.md). The dashboard itself — what the screens do,
what the icons mean — is covered in the [User Guide](USER_GUIDE.md).

---

## Contents

- [The pipeline](#the-pipeline)
- [The filter function](#the-filter-function)
- [Three ways to ship a filter](#three-ways-to-ship-a-filter)
- [Inline filters](#inline-filters)
- [Plugin filters](#plugin-filters)
- [Module filters](#module-filters)
- [Controlling execution order](#controlling-execution-order)
- [Scoping a filter](#scoping-a-filter)
- [Adding settings fields](#adding-settings-fields)
- [Loading CSS and JavaScript](#loading-css-and-javascript)
- [CodeVet: the security gate](#codevet-the-security-gate)
- [Working with the content](#working-with-the-content)
- [Backend filters](#backend-filters)
- [Assets Cache Busting: two mechanisms, one switch](#assets-cache-busting-two-mechanisms-one-switch)
- [Deprecated APIs](#deprecated-apis)
- [Debugging](#debugging)

---

## The pipeline

WBCE calls `opf_controller()` at fixed points while rendering a page. Each call
runs every active filter registered for that stage, in the order the dashboard
shows, handing the content along from one to the next.

There are two levels, seven stages:

| Constant                 | Applied to                                                               |
| ------------------------ | ------------------------------------------------------------------------ |
| `OPF_TYPE_SECTION_FIRST` | Section content, before all `OPF_TYPE_SECTION` filters.                  |
| `OPF_TYPE_SECTION`       | Section content — the output of one module's `view.php`.                 |
| `OPF_TYPE_SECTION_LAST`  | Section content, after all `OPF_TYPE_SECTION` filters.                   |
| `OPF_TYPE_PAGE_FIRST`    | The assembled page, before all `OPF_TYPE_PAGE` filters.                  |
| `OPF_TYPE_PAGE`          | The assembled page — template, `<head>`, menus, snippets, every section. |
| `OPF_TYPE_PAGE_LAST`     | The assembled page, after all `OPF_TYPE_PAGE` filters.                   |
| `OPF_TYPE_PAGE_FINAL`    | The assembled page, after all `OPF_TYPE_PAGE_LAST` filters. Dead last.   |

Section filters run per section, before the section is placed into the template.
Page filters run once per request, on the finished page, just before it goes to
the browser.

The constants are defined in `functions.php`; their stored values are ordering
strings (`'3section'`, `'7page'`, …). Always use the constants, never the
strings.

Picking the right stage matters more than it looks:

- Need to react to markup another filter produces? Run after it.
- Need to see the template's `<head>`? You need a page stage — a section filter
  never sees it.
- Cleaning up after everything else? `OPF_TYPE_PAGE_FINAL`. That is where
  *Remove System PH* and *Assets Cache Busting* sit.

## The filter function

One function, a unique name, an `opff_` prefix by convention:

```php
function opff_unique_name(&$content, $page_id, $section_id, $module, $wb)
{
    // ...
    return true;
}
```

| Parameter     | Type   | Description                                                                              |
| ------------- | ------ | ---------------------------------------------------------------------------------------- |
| `&$content`   | string | The page's or section's content, **by reference**. Modify it in place; do not return it. |
| `$page_id`    | int    | The current page id.                                                                     |
| `$section_id` | int    | The current section id. Always `FALSE` at the page stages.                               |
| `$module`     | string | The current module's directory name. Always `FALSE` at the page stages.                  |
| `$wb`         | object | The WBCE frontend class instance.                                                        |

**Return value.** Return `TRUE` normally. Return `FALSE` **only** if `$content`
may have been damaged or left in an undefined state — that is a signal to the
pipeline, not a generic error code.

A minimal example:

```php
function opff_x_as_u(&$content, $page_id, $section_id, $module, $wb)
{
    $content = str_replace('U', 'X', $content);
    return true;
}
```

A realistic one — load a syntax highlighter, but only on pages that use it:

```php
function opff_prettify(&$content, $page_id, $section_id, $module, $wb)
{
    $PATH = WB_URL . '/modules/opf_prettify/prettify';

    if (opf_find_class($content, 'prettyprint', '(pre|code)')) {
        I::insertCssFile($PATH . '/prettify.css');
        I::insertJsFile($PATH . '/prettify.js');

        if (opf_find_class($content, 'lang-sql', '(pre|code)')) {
            I::insertJsFile($PATH . '/lang-sql.js');
        }

        I::insertJsCode(
            "window.addEventListener('DOMContentLoaded', prettyPrint, false);"
        );
    }

    return true;
}
```

Note the shape: **check first, work second**. A filter that runs on every page
should cost almost nothing on the pages that do not need it.

## Three ways to ship a filter

All three end up in the same table and go through the same
`opf_register_filter()` call. The difference is where the code lives and who
installs it.

| Form       | Code lives in                | Installed by                                  | Use it for                                                 |
| ---------- | ---------------------------- | --------------------------------------------- | ---------------------------------------------------------- |
| **Inline** | The database (`func` column) | An admin typing it into the dashboard         | Site-specific one-offs, quick experiments.                 |
| **Plugin** | `plugins/<name>/filter.php`  | Uploading a ZIP, or shipping it in `plugins/` | A filter you want to distribute on its own.                |
| **Module** | Your module's own folder     | Your module's `install.php`                   | A filter that only makes sense as part of a larger module. |

Inline and plugin filters convert into each other from the dashboard; export
always produces a plugin package.

## Inline filters

There is nothing to build. An admin clicks **New inline filter**, fills in name,
description, stage and targeting, saves once, and the code editor appears.

Two consequences worth designing around:

- The code is stored in the database, so it is not in version control and not in
  a deployment. Anything you want to survive a site rebuild belongs in a plugin
  or a module.
- It goes through [CodeVet](#codevet-the-security-gate) on every save. Inline
  filter code cannot use `eval()`, variable function calls, backticks, or
  `call_user_func()`.

## Plugin filters

A plugin is a folder — distributed as a ZIP — under
`modules/outputfilter_dashboard/plugins/`:

```file-tree

opf_my_filter/
├── plugin_info.php        metadata
├── plugin_install.php     the opf_register_filter() call
├── filter.php             the filter function
├── plugin_uninstall.php   optional cleanup
├── index.php              redirect guard
└── README.md              optional, shown as the filter's help page
```

Every `plugin_install.php` under `plugins/` is executed when the module is
installed or upgraded, so a plugin shipped in that folder is installed
automatically. An uploaded ZIP is unpacked there and its installer run.

### plugin_info.php

```php
<?php
$plugin_directory   = 'opf_assets_cache_busting';
$plugin_name        = 'Assets Cache Busting';
$plugin_version     = '1.1.0';
$plugin_author      = 'Your Name';
$plugin_license     = 'GNU General Public License, Version 3';
$plugin_description = 'Prevents browsers from delivering outdated CSS/JS from cache';
```

### plugin_install.php

```php
<?php
if (!defined('WB_PATH')) die(header('Location: ../../index.php'));

opf_register_filter([
    'name'            => 'Assets Cache Busting',
    'type'            => OPF_TYPE_PAGE_FINAL,
    'file'            => '{OPF:PLUGIN_PATH}/filter.php',
    'funcname'        => 'opff_assets_cache_busting',
    'plugin'          => 'opf_assets_cache_busting',
    'desc'            => [
        'EN' => 'Prevents browsers from delivering outdated CSS/JS files from cache.',
        'DE' => 'Verhindert, dass Browser veraltete CSS-/JS-Dateien aus dem Cache ausliefern.',
    ],
    'modules'         => 'all',
    'active'          => 1,
    'allowedit'       => 0,
    'allowedittarget' => 1,
]);
```

Set `'plugin'` to your plugin's directory name — that is what marks the filter
as a plugin filter in the dashboard and makes export and conversion work.

Freshly registered filters are appended to the end of their stage group. To sit
somewhere specific, see [Controlling execution order](#controlling-execution-order).

Every key is documented in
[`$filter` array](API_REFERENCE.md#the-filter-array).

### filter.php

```php
<?php
if (!defined('WB_PATH')) die(header('Location: ../../index.php'));

function opff_assets_cache_busting(&$content, $page_id, $section_id, $module, $wb)
{
    // ...
    return true;
}
```

### plugin_uninstall.php

Optional. Clean up files or settings your plugin created.

```php
<?php
if (!defined('WB_PATH')) die(header('Location: ../../index.php'));

// clean up here.
// But do NOT call opf_unregister_filter() in this file --
// the dashboard removes the registration itself.
```

### Path tokens and constants

Never hardcode an absolute path into a registration — the same package has to
work on any install. Four tokens are expanded when the filter is loaded:

| Token               | Expands to                                                     |
| ------------------- | -------------------------------------------------------------- |
| `{SYSVAR:WB_PATH}`  | The `WB_PATH` constant.                                        |
| `{SYSVAR:WB_URL}`   | The `WB_URL` constant.                                         |
| `{OPF:PLUGIN_PATH}` | `WB_PATH/modules/outputfilter_dashboard/plugins/<your_plugin>` |
| `{OPF:PLUGIN_URL}`  | `WB_URL/modules/outputfilter_dashboard/plugins/<your_plugin>`  |

Inside the filter function itself, use the constants instead:

```php
OPF_PLUGINS_PATH   // WB_PATH . '/modules/outputfilter_dashboard/plugins/'
OPF_PLUGINS_URL    // WB_URL  . '/modules/outputfilter_dashboard/plugins/'
```

## Module filters

To register a filter as part of your own module, call
[`opf_register_filter()`](API_REFERENCE.md#opf-register-filter) from its
`install.php` and [`opf_unregister_filter()`](API_REFERENCE.md#opf-unregister-filter)
from its `uninstall.php`.

Guard both with a file check so your module still installs on a site where the
dashboard is missing, and write the installer so it survives being run a second
time (`CREATE TABLE ... IF NOT EXISTS` for any tables of your own).

### install.php

```php
<?php

// ... your normal install code ...

if (file_exists(WB_PATH . '/modules/outputfilter_dashboard/functions.php')) {
    require_once WB_PATH . '/modules/outputfilter_dashboard/functions.php';

    opf_register_filter([
        'name'      => 'Searchengine Highlighter',
        'type'      => OPF_TYPE_PAGE_LAST,
        'file'      => '{SYSVAR:WB_PATH}/modules/searchengine_highlight/filter.php',
        'funcname'  => 'opff_searchengine_highlight',
        'desc'      => 'Highlights search engine hits',
        'active'    => 1,
        'allowedit' => 0,
    ]);
}

// ... rest of your install code ...
```

### uninstall.php

```php
<?php

// ... your normal uninstall code ...

if (file_exists(WB_PATH . '/modules/outputfilter_dashboard/functions.php')) {
    require_once WB_PATH . '/modules/outputfilter_dashboard/functions.php';
    opf_unregister_filter('Searchengine Highlighter');
}
```

### precheck.php

Use WBCE's precheck system to refuse installation when the dashboard is not
there or is too old:

```php
<?php
if (!defined('WB_PATH')) die(header('Location: ../index.php'));

$PRECHECK = [];
$PRECHECK['WB_VERSION'] = ['VERSION' => '2.8', 'OPERATOR' => '>='];
$PRECHECK['WB_ADDONS']  = [
    'outputfilter_dashboard' => ['VERSION' => '1.3.2', 'OPERATOR' => '>='],
];
```

### The rest of the module

A filter-only module is still a normal WBCE module and needs the usual two
files.

`index.php`:

```php
<?php
header('Location: ../../index.php');
```

`info.php`:

```php
<?php
$module_directory   = 'searchengine_highlight';
$module_name        = 'Search Engine Highlighter';
$module_function    = 'filter';
$module_version     = '0.1';
$module_platform    = 'WBCE 1.7.x';
$module_author      = 'Your Name';
$module_license     = 'GNU General Public License, Version 3';
$module_description = 'Highlights search engine hits on the page';
```

## Controlling execution order

New filters land at the end of their stage group. When yours has to run before
another one, say so at install time with
[`opf_move_up_before()`](API_REFERENCE.md#opf-move-up-before):

```php
// must run before the search highlighter sees the page
opf_move_up_before('opf CSS to head', 'Searchengine Highlighter');

// or: move to the very top of its group
opf_move_up_before('Droplets Injector');
```

Pass an array of names to move above the topmost of several.

Prefer picking the right *stage* over fighting for a position inside one. If
your filter genuinely has to be last, `OPF_TYPE_PAGE_FINAL` says so structurally
and keeps saying it after someone reorders the list.

## Scoping a filter

Three registration keys decide where a filter runs. Getting them right is the
difference between a filter that costs nothing and one that runs a regex over
every page of the site.

| Key            | Applies to     | Meaning                                                                          |
| -------------- | -------------- | -------------------------------------------------------------------------------- |
| `modules`      | Section stages | Which module types the filter runs on. `'all'` or a list, e.g. `'wysiwyg,news'`. |
| `pages`        | Page stages    | Page ids the filter runs on. `'all'`, or `'21,121,16'`.                          |
| `pages_parent` | Page stages    | Page ids **and all their sub-pages**. Also takes `backend` and `search`.         |

Several convenience aliases exist for `modules` (`'all_page_types'`,
`'all_gallery_types'`, `'all_form_types'`, …) — see
[`modules`](API_REFERENCE.md#modules) for the full list.

`allowedittarget => 1` (the default) lets an admin adjust this targeting from
the dashboard without being able to touch anything else. That is almost always
what you want — you know the filter, they know their site.

## Adding settings fields

A filter that needs configuration but does not warrant its own admin tool can
declare `additional_fields` at registration. The dashboard renders them on the
filter's settings page; your filter reads the values back with
[`opf_filter_get_additional_values()`](API_REFERENCE.md#opf-filter-get-additional-values):

```php
'additional_fields' => [
    [
        'type'     => 'select',
        'label'    => ['EN' => 'Colour', 'DE' => 'Farbe'],
        'variable' => 'colour',
        'name'     => 'af_colour',
        'value'    => [
            'red'   => ['EN' => 'Red',   'DE' => 'Rot'],
            'blue'  => ['EN' => 'Blue',  'DE' => 'Blau'],
            'green' => ['EN' => 'Green', 'DE' => 'Grün'],
        ],
        'checked'  => 'blue',
    ],
],
```

```php
$values = opf_filter_get_additional_values();
$colour = $values['colour'];
```

Field types: `text`, `textarea`, `editarea`, `radio`, `checkbox`, `select`,
`array`. Full details in
[`additional_fields`](API_REFERENCE.md#additional-fields).

## Loading CSS and JavaScript

Use **AssetQueue** — WBCE's own asset pipeline, available everywhere as `I::`:

```php
I::insertCssFile(WB_URL . '/modules/my_filter/style.css');
I::insertJsFile(WB_URL . '/modules/my_filter/script.js');
I::insertCssCode('.my-class { color: red; }');
I::insertJsCode("console.log('hello');");
```

AssetQueue de-duplicates entries, orders them, minifies and bundles them where
configured, and injects the result into `<head>` and `<body>` at the right
moment — the *Class Insert Helper* core filter is what triggers that injection.

Queue assets from inside your filter function, conditionally, once you know the
page actually needs them. Do not write `<link>` or `<script>` tags into
`$content` yourself.

See [`framework/Assets/AssetQueue.php`](../../../framework/Assets/AssetQueue.php)
for the complete API.

## CodeVet: the security gate

*New in WBCE 1.7.0.* Filter code that an admin can edit is code that arrives
through a web form, so it is checked before it is stored.
[`framework/CodeVet.php`](../../../framework/CodeVet.php) runs on every path
that writes filter code:

- the classic form save,
- the code editor's AjaxSave,
- `opf_register_filter()` itself whenever `$filter['func']` is set,
- and the contents of an uploaded plugin ZIP, before it is staged.

It performs a real PHP syntax check and a `token_get_all()`-based scan that
blocks `eval()`, `call_user_func()`/`call_user_func_array()`, variable function
calls, backtick operators, and assorted system and obfuscation functions.

Three things follow from this:

- **A CodeVet rejection is absolute.** `opf_register_filter()` returns `FALSE`,
  and unlike its softer validation checks (missing name, missing funcname, …)
  this one is *not* relaxed by `'force' => TRUE`. Broken or unsafe code is never
  persisted, active or not.
- **Write filters that do not need the blocked constructs.** They are not
  obscure corners; dispatching through `call_user_func()` is a common habit.
  Call the function directly instead.
- **Findings are logged.** Blocks and warnings land in `var/code_vet/codevet.log`
  and are visible in the errorlogger's *CodeVet* tab.

Filter code loaded from a `file` (plugin and module filters) is scanned when the
package is installed, not on every page load.

## Working with the content

The module ships helpers for the things filters do over and over. They are
documented in full in the
[API Reference](API_REFERENCE.md#content-helpers); the short version:

| Function                   | Use                                                                     |
| -------------------------- | ----------------------------------------------------------------------- |
| `opf_find_class()`         | Is this class/attribute present at all? The cheap early-exit check.     |
| `opf_add_class()`          | Add a class to every occurrence of a tag.                               |
| `opf_add_class_to_class()` | Add a class to elements that already carry another class.               |
| `opf_add_class_to_attr()`  | Add a class to elements carrying a given attribute value.               |
| `opf_cut_extract()`        | Cut regex matches out of the content, replacing them with placeholders. |
| `opf_glue_extract()`       | Put them back.                                                          |

`opf_cut_extract()` / `opf_glue_extract()` are the pattern for "modify these
fragments without the rest of my processing touching them":

```php
function opff_menu_linebreak(&$content, $page_id, $section_id, $module, $wb)
{
    $extracts = opf_cut_extract($content, '<a href=[^>]*class="menu[^>]*>(.*)</a>', 1, 'iUs');

    if ($extracts === false) {
        return false;   // content may be damaged -- say so
    }

    foreach ($extracts as $key => $str) {
        $extracts[$key] = str_replace('#', '<br />', $str);
    }

    opf_glue_extract($content, $extracts);
    return true;
}
```

You can also ask the pipeline about itself from inside a filter:
[`opf_filter_get_data()`](API_REFERENCE.md#opf-filter-get-data) (your own
registration record, including your `additional_values`),
[`opf_filter_get_rel_pos()`](API_REFERENCE.md#opf-filter-get-rel-pos) (has
filter X already run, or is it still coming?),
[`opf_filter_is_active()`](API_REFERENCE.md#opf-filter-is-active) and
[`opf_filter_exists()`](API_REFERENCE.md#opf-filter-exists).

## Backend filters

Adding `backend` to `pages_parent` applies the filter to WBCE's admin output as
well:

```php
'pages_parent' => 'all,backend,search',
```

The core filters do this — the backend is HTML that needs the same asset
injection and placeholder cleanup as the frontend.

Two constraints:

- **It is all-or-nothing through the dashboard.** There is no UI for targeting a
  single admin tool. The engine can do it — `opf_apply_filters()` is called with
  the tool's directory as `$module` from `admin/admintools/tool.php` — but only
  a registration written in code can make use of that.
- **A broken backend filter locks you out of the backend.** Tell your users
  about the escape hatch: defining `WB_OPF_BE_OFF` in `config.php` disables all
  backend filtering.

Test backend filters on a site you can afford to lock yourself out of.

## Assets Cache Busting: two mechanisms, one switch

Worth understanding if you touch asset URLs, because it explains why one
checkbox appears to do two different things.

The **Assets Cache Busting** filter is registered at `OPF_TYPE_PAGE_FINAL`,
after *Remove System PH*, so it always sees the final assembled page. It does
two separate jobs:

1. **It switches on AssetQueue's own cache busting.** AssetQueue appends
   `?<mtime>` to every file it manages whenever the `OPF_ASSETS_CACHE_BUSTING`
   constant is truthy. Toggling the filter writes the `opf_assets_cache_busting`
   settings row, and `Settings::setup()` promotes every settings row to a
   same-named constant at the start of each request — so the checkbox is exactly
   equivalent to defining the constant by hand.
2. **It busts everything AssetQueue does not manage.** Hand-written `<link>` and
   `<script>` tags in section content, legacy templates, other filters' output —
   `opff_assets_cache_busting()` scans the final page for those and appends
   `?<mtime>` to any that lack one.

The constant covers AssetQueue's own files; the filter's regex pass covers
everything else; one checkbox drives both. The same setting is surfaced in the
**Asset Optimizer** admin tool.

## Deprecated APIs

**Deprecated in WBCE 1.7.0:** `opf_register_frontend_files()`,
`opf_register_onload_event()`, `opf_register_onload()` and
`opf_register_document_ready()`.

These were this module's own head/body injection system, predating WBCE's asset
pipeline. They used to write into the module-local `$opf_HEADER`/`$opf_BODY`
globals, which `opf_insert_frontend_files()` then flushed into the page.

They had no callers left anywhere in the shipped codebase, but rather than break
site-specific filters written before 1.7.0, all four were kept as thin
compatibility shims that delegate to the matching `I::` call. **Write new code
against `I::insertCssFile()` / `insertJsFile()` / `insertCssCode()` /
`insertJsCode()` directly.**

One behavioural caveat if you still call the old `opf_register_frontend_files()`:
its `$iehack` parameter (IE conditional-comment wrapping) always lands in
`<body>` regardless of `$target`, because that path has to go through
`I::insertHtmlCode()`, and AssetQueue never allows arbitrary HTML in `<head>`.

Do not write to `$opf_HEADER`/`$opf_BODY` from your own filters.

## Debugging

**Verbose warnings.** Most `opf_*` functions stay quiet about "filter not found"
style problems unless `OPF_VERBOSE` is on. It follows `DEBUG` by default —
define `DEBUG` in your `config.php` while developing and the functions start
emitting `E_USER_WARNING`s.

**Database errors** are always written to the PHP error log, independent of
`DEBUG`.

**Backend lockout.** `define('WB_OPF_BE_OFF', 'off');` in `config.php` disables
all backend filtering. Keep it in mind before you test a backend filter, not
after.

**Inspect your own registration** from inside the filter:

```php
var_dump(opf_filter_get_data());
```

That returns the complete stored record — stage, targeting, funcname, extra
values — which is usually enough to see why a filter is not firing where you
expected.

**CodeVet findings** are logged as JSON lines to `var/code_vet/codevet.log` and
shown in the errorlogger's *CodeVet* tab.

---

*OutputFilter Dashboard documentation — CC BY-SA 3.0 DE. See
[LICENSE.md](../LICENSE.md).*
