# OutputFilter Dashboard — API Reference

The complete `opf_*` function and `$filter` array reference.

For how these fit together — writing a filter, packaging it, shipping it — read
the [Developer Guide](DEVELOPER_GUIDE.md) first.

---

## Contents

**Constants and tokens**
[Stage constants](#stage-constants) ·
[Path constants and tokens](#path-constants-and-tokens)

**The filter function**
[Prototype and parameters](#the-filter-function)

**Registration**
[opf_register_filter](#opf-register-filter) ·
[The filter array](#the-filter-array) ·
[opf_move_up_before](#opf-move-up-before) ·
[opf_unregister_filter](#opf-unregister-filter)

**Content helpers**
[opf_find_class](#opf-find-class) ·
[opf_add_class](#opf-add-class) ·
[opf_add_class_to_class](#opf-add-class-to-class) ·
[opf_add_class_to_attr](#opf-add-class-to-attr) ·
[opf_cut_extract](#opf-cut-extract) ·
[opf_glue_extract](#opf-glue-extract)

**Runtime helpers**
[opf_filter_get_data](#opf-filter-get-data) ·
[opf_filter_get_rel_pos](#opf-filter-get-rel-pos) ·
[opf_filter_exists](#opf-filter-exists) ·
[opf_filter_is_active](#opf-filter-is-active) ·
[opf_is_childpage](#opf-is-childpage) ·
[opf_filter_get_additional_values](#opf-filter-get-additional-values) ·
[opf_filter_name_to_setting](#opf-filter-name-to-setting)

**Deprecated**
[Legacy frontend-file registration](#legacy-frontend-file-registration)

---

## Constants and tokens

<a id="stage-constants"></a>

### Stage constants

Defined in `functions.php`. Always use the constant, never its stored string
value.

| Constant                 | Stored value     | Applied to                                                               |
| ------------------------ | ---------------- | ------------------------------------------------------------------------ |
| `OPF_TYPE_SECTION_FIRST` | `2section_first` | Section content, before all `OPF_TYPE_SECTION` filters.                  |
| `OPF_TYPE_SECTION`       | `3section`       | Section content — the output of one module's `view.php`.                 |
| `OPF_TYPE_SECTION_LAST`  | `5section_last`  | Section content, after all `OPF_TYPE_SECTION` filters.                   |
| `OPF_TYPE_PAGE_FIRST`    | `6page_first`    | The assembled page, before all `OPF_TYPE_PAGE` filters.                  |
| `OPF_TYPE_PAGE`          | `7page`          | The assembled page — template, `<head>`, menus, snippets, every section. |
| `OPF_TYPE_PAGE_LAST`     | `8page_last`     | The assembled page, after all `OPF_TYPE_PAGE` filters.                   |
| `OPF_TYPE_PAGE_FINAL`    | `9page_final`    | The assembled page, after all `OPF_TYPE_PAGE_LAST` filters. Dead last.   |

Section filters run per section, before the section is placed into the template.
Page filters run once per request, on the finished page.

<a id="path-constants-and-tokens"></a>

### Path constants and tokens

Tokens are expanded when a filter's stored values are read, so a registration
stays portable across installs:

| Token               | Expands to                                                     |
| ------------------- | -------------------------------------------------------------- |
| `{SYSVAR:WB_PATH}`  | The `WB_PATH` constant.                                        |
| `{SYSVAR:WB_URL}`   | The `WB_URL` constant.                                         |
| `{OPF:PLUGIN_PATH}` | `WB_PATH/modules/outputfilter_dashboard/plugins/<your_plugin>` |
| `{OPF:PLUGIN_URL}`  | `WB_URL/modules/outputfilter_dashboard/plugins/<your_plugin>`  |

Inside filter code, use the constants:

| Constant           | Value                                                     |
| ------------------ | --------------------------------------------------------- |
| `OPF_PLUGINS_PATH` | `WB_PATH . '/modules/outputfilter_dashboard/plugins/'`    |
| `OPF_PLUGINS_URL`  | `WB_URL . '/modules/outputfilter_dashboard/plugins/'`     |
| `OPF_VERBOSE`      | Whether `opf_*` functions emit warnings. Follows `DEBUG`. |

Two constants read from `config.php` change the pipeline's behaviour:

| Constant                   | Effect                                                                                       |
| -------------------------- | -------------------------------------------------------------------------------------------- |
| `WB_OPF_BE_OFF`            | If defined (any value), no filter is applied to backend output. The lockout escape hatch.    |
| `OPF_ASSETS_CACHE_BUSTING` | Switches on AssetQueue's own `?<mtime>` cache busting. Normally set via the filter's toggle. |

---

<a id="the-filter-function"></a>

## The filter function

Must have a unique name; the `opff_` prefix is the convention.

**Prototype**

`bool` **opff_unique_name** ( `string` &$content, `int` $page_id, `int` $section_id, `string` $module, `object` $wb )

**Parameters**

| Parameter     | Type   | Description                                                                               |
| ------------- | ------ | ----------------------------------------------------------------------------------------- |
| `&$content`   | string | The page's or section's content, by reference. Modify in place.                           |
| `$page_id`    | int    | The current page id.                                                                      |
| `$section_id` | int    | The current section id. Always `FALSE` for the four `OPF_TYPE_PAGE*` stages.              |
| `$module`     | string | The current module's directory name. Always `FALSE` for the four `OPF_TYPE_PAGE*` stages. |
| `$wb`         | object | Instance of the WBCE frontend class.                                                      |

**Returns**

`TRUE` normally. `FALSE` **only** when `$content` may be damaged or in an
undefined state.

**Example**

```php
function opff_x_as_u(&$content, $page_id, $section_id, $module, $wb)
{
    $content = str_replace('U', 'X', $content);
    return true;
}
```

---

## Registration

<a id="opf-register-filter"></a>

### opf_register_filter()

Registers a new filter, or updates an existing one with the same name. Used by
plugin and module filters; inline filters go through the dashboard instead.

**Prototype**

`bool` **opf_register_filter** ( `array` $filter, `bool` $serialized = FALSE )

**Parameters**

- **$filter** — `(array)` the filter definition, see
  [The filter array](#the-filter-array).
- **$serialized** — `(bool)` whether `$filter` is passed serialized.

**Returns**

`TRUE` on success, `FALSE` otherwise. On failure it also emits an
`E_USER_WARNING` describing the problem.

**Updating an existing registration.** If a filter of the same name (or `id`)
already exists, this is an update rather than an insert. Unless `'force' => TRUE`
is passed, the stored `active`, `modules`, `pages` and `pages_parent` values are
**kept** — so re-running your installer on an upgrade never overwrites the
targeting an administrator configured.

**CodeVet.** *New in WBCE 1.7.0.* When `$filter['func']` is set, the code is run
through [`CodeVet`](../../../framework/CodeVet.php) — a PHP syntax check plus a
security scan — before anything is written to the database. A rejection returns
`FALSE` **unconditionally**: unlike the softer validation below, this one is not
relaxed by `'force' => TRUE`. The specific reason is left in
`$GLOBALS['opf_codevet_error']` as `['message' => …, 'line' => …]`.

**Example**

```php
$filter = [
    'name'     => 'PrettyPrint: Google-Code-Prettify',
    'type'     => OPF_TYPE_SECTION,
    'file'     => '{SYSVAR:WB_PATH}/modules/opf_prettify/filter.php',
    'funcname' => 'opff_prettify',
    'csspath'  => '{SYSVAR:WB_PATH}/modules/opf_prettify/prettify/prettify.css',
    'modules'  => 'download_gallery,manual,news,wysiwyg',
];
opf_register_filter($filter);
```

<a id="the-filter-array"></a>

### The filter array

| Key                 | Type         | Description                                                                                            |
| ------------------- | ------------ | ------------------------------------------------------------------------------------------------------ |
| `name`              | string       | Unique name of the filter, e.g. `Correct Date filter`. Required.                                       |
| `type`              | string       | The stage — one of the [stage constants](#stage-constants). Required.                                  |
| `funcname`          | string       | Unique name of the filter function. Should start with `opff_`. Required.                               |
| `file`              | string       | Absolute path (or token path) of the file containing the function. Use either `file` or `func`.        |
| `func`              | string       | The function source itself, as a string. For very short functions. See [func](#func).                  |
| `plugin`            | string       | Your plugin's directory name. Marks the filter as a plugin filter; required for plugin filters.        |
| `active`            | int          | Active (`1`) or inactive (`0`) after installation. Defaults to `1`.                                    |
| `allowedit`         | int          | Whether the admin may edit the filter's settings. Defaults to `0`. See [allowedit](#allowedit).        |
| `allowedittarget`   | int          | Whether the admin may edit only the page/module targeting. Defaults to `1`.                            |
| `desc`              | string/array | Description. A plain string, or a language-keyed array. See [desc](#desc).                             |
| `helppath`          | string/array | Path or URL of a help file, per language, e.g. `['EN' => …, 'DE' => …]`. Shown as the row's `?` icon.  |
| `configurl`         | string       | URL of the filter's own settings screen. Shown as the row's cog icon. Default `''`.                    |
| `csspath`           | string       | Absolute path of a CSS file. Makes the row's CSS-editor icon appear. Default `''`.                     |
| `modules`           | string/array | Modules the filter applies to (section stages only). See [modules](#modules).                          |
| `pages`             | string/array | Page ids the filter applies to (page stages only). See [pages](#pages).                                |
| `pages_parent`      | string/array | Page ids **plus their sub-pages**. See [pages_parent](#pages-parent).                                  |
| `additional_fields` | array        | Extra settings fields. See [additional_fields](#additional-fields).                                    |
| `force`             | bool         | Relax the softer validation checks, and overwrite stored targeting on update. Does not bypass CodeVet. |
| `targets`           | string/array | Legacy alias for `modules`.                                                                            |

<a id="func"></a>

#### func

The filter function as a string. Convenient for very short functions; use `file`
for anything larger.

```php
$function = '
    function opff_search_highlight(&$content, $page_id, $section_id, $module, $wb) {
        if (isset($_GET["searchresult"]) && is_numeric($_GET["searchresult"])
            && !isset($_GET["nohighlight"]) && !empty($_GET["sstring"])) {
            $arr_string = explode(" ", $_GET["sstring"]);
            if ($_GET["searchresult"] == 2) { // exact match
                $arr_string[0] = str_replace("_", " ", $arr_string[0]);
            }
            $content = search_highlight($content, $arr_string);
        }
        return true;
    }
';

opf_register_filter([
    'name'     => 'Searchresult Highlighting',
    'type'     => OPF_TYPE_SECTION_LAST,
    'funcname' => 'opff_search_highlight',
    'func'     => $function,
    'modules'  => 'all',
]);
```

The function name inside the string must match `funcname`, otherwise
registration fails (or, with `'force' => TRUE`, the filter is stored inactive
with a warning comment prepended).

Code passed as `func` goes through CodeVet — see
[opf_register_filter](#opf-register-filter).

<a id="file"></a>

#### file

Absolute path of the file containing the function. Use a token so the
registration stays portable:

```php
opf_register_filter([
    'name'     => 'Searchresult Highlighting',
    'type'     => OPF_TYPE_SECTION_LAST,
    'funcname' => 'opff_search_highlight',
    'file'     => '{SYSVAR:WB_PATH}/modules/myfilter/filter.php',
    'modules'  => 'all',
]);
```

```php
<?php
// modules/myfilter/filter.php
function opff_search_highlight(&$content, $page_id, $section_id, $module, $wb)
{
    // ...
    return true;
}
```

Registration fails if the file does not exist or is not readable.

<a id="allowedit"></a>

#### allowedit

With `allowedit => 1`, the administrator may change, from the dashboard:

- the filter's name,
- its stage,
- the function name,
- the function body (inline filters only),
- the module and page targeting.

**Recommendation: `'allowedit' => 0`** (the default) for filters you ship. You
wrote the filter; letting a site admin rename its function is not a feature.

<a id="allowedittarget"></a>

#### allowedittarget

An extension of `allowedit`. With `allowedit => 0` and `allowedittarget => 1`,
the administrator may change **only** the module and page targeting — nothing
else.

**Recommendation: `'allowedittarget' => 1`** (the default). You know the filter;
the site admin knows where it is needed.

Meaningless when `allowedit` is `1`.

<a id="desc"></a>

#### desc

Either a plain string:

```php
'desc' => "Description follows here.\nText...",
```

or a language-keyed array, which must contain at least an `EN` entry:

```php
'desc' => [
    'EN' => "Description\n...",
    'DE' => "Beschreibung\n...",
],
```

<a id="modules"></a>

#### modules

Which modules a section-stage filter applies to. Ignored for the page stages.

```php
'modules' => 'wysiwyg,news'
'modules' => ['wysiwyg', 'news']
'modules' => 'all'
```

Category aliases expand to a list of modules:

| Alias                | Expands to                                                                                                                                                                                                                    |
| -------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `all`                | All installed modules                                                                                                                                                                                                         |
| `all_page_types`     | wysiwyg, news, guestbook, download_gallery, manual, mapbaker, newsarc                                                                                                                                                         |
| `all_form_types`     | form, formx, mpform, miniform                                                                                                                                                                                                 |
| `all_gallery_types`  | Auto_Gallery, fancy_box, flickrgallery, foldergallery, gallery, gdpics, imageflow, imagegallery, lightbox2, lightbox, minigallery, minigal2, panoramic_image, pickle, picturebox, tiltviewer, smoothgallery, swift, slideshow |
| `all_wrapper_types`  | curl, inlinewrapper, wrapper                                                                                                                                                                                                  |
| `all_calendar_types` | calendar, concert, event, event_calendar, extcal, procalendar                                                                                                                                                                 |
| `all_shop_types`     | bakery, gocart, anyitems, lastitems                                                                                                                                                                                           |
| `all_code_types`     | code, code2, show_code, show_code_geshi, color4code                                                                                                                                                                           |
| `all_poll_types`     | doodler, polls                                                                                                                                                                                                                |
| `all_listing_types`  | accordion, addressbok, aggregator, bookings_v2, bookmarks, cabin, dirlist, enhanced_aggregator, faqbaker, faqmaker, glossary, jqCollapse, members, mfz, sitemap                                                               |
| `all_various_types`  | audioplayer, feedback, forum, newsreader, shoutbox, simpleviewer, small_ads, wb-forum, zitate                                                                                                                                 |

The authoritative mapping is `opf_modules_categories()` in `functions.php`.

<a id="pages"></a>

#### pages

Page ids a page-stage filter applies to. Alias `all` for every page.

```php
'pages' => '21,121,16'            // as a list
'pages' => ['21', '121', '16']    // as an array
'pages' => 'all'
```

<a id="pages-parent"></a>

#### pages_parent

Like `pages`, but the filter is applied to the listed pages **and all their
sub-pages**.

```php
// page 12 and 21, plus everything below them
'pages_parent' => '12,21'
'pages_parent' => ['12', '21']
```

Two special values are accepted here:

| Value     | Meaning                                                                      |
| --------- | ---------------------------------------------------------------------------- |
| `backend` | Also apply the filter to WBCE's whole admin/backend output.                  |
| `search`  | Also apply the filter to search result output (stored internally as id `0`). |

```php
'pages_parent' => 'all,backend,search',
```

<a id="additional-fields"></a>

#### additional_fields

Extra configuration fields, rendered on the filter's settings page. For filters
that need a little configuration but do not warrant their own admin tool. Read
the stored values back with
[opf_filter_get_additional_values](#opf-filter-get-additional-values).

`allowedit` has no effect on these fields — they are always editable.

**Field types**

| Type       | Renders as                                     |
| ---------- | ---------------------------------------------- |
| `text`     | An HTML text field                             |
| `textarea` | An HTML textarea                               |
| `editarea` | A textarea with the editor attached            |
| `radio`    | HTML radio buttons                             |
| `checkbox` | HTML checkboxes                                |
| `select`   | An HTML select field                           |
| `array`    | A simple key/value array as paired text fields |

**Field keys**

| Key        | Description                                                                                                     |
| ---------- | --------------------------------------------------------------------------------------------------------------- |
| `label`    | Field label. A plain string (`'Enter Date'`) or a language-keyed array. (`text` is accepted as a legacy alias.) |
| `variable` | Name of the variable the value is stored under — this is the key you read back.                                 |
| `type`     | One of the field types above.                                                                                   |
| `name`     | Value for the HTML `name` attribute. Radio buttons share one name.                                              |
| `value`    | Default value. `select` and `array` require an array.                                                           |
| `checked`  | For `radio`/`checkbox`: `'checked' => 1`. For `select`: `'checked' => 'value'` — repeat the selected value.     |
| `style`    | Optional, for `text`/`textarea`/`editarea`: `width:` and/or `height:`.                                          |

**Example — assorted fields**

```php
'additional_fields' => [
    [                              // text field "Name"
        'type'     => 'text',
        'label'    => 'Name',
        'variable' => 'name',
        'name'     => 'af_name',
        'value'    => '',
        'style'    => 'width: 98%;',
    ],
    [                              // textarea "Long-Text"
        'type'     => 'textarea',
        'label'    => 'Long-Text',
        'variable' => 'text',
        'name'     => 'af_long_text',
        'value'    => 'Default text',
        'style'    => 'width: 98%;',
    ],
    [                              // select "Colour"
        'type'     => 'select',
        'label'    => 'Colour',
        'variable' => 'colour',
        'name'     => 'af_colour',
        'value'    => ['red' => 'Red', 'blue' => 'Blue', 'green' => 'Green'],
        'checked'  => 'blue',
    ],
    [                              // checkbox
        'type'     => 'checkbox',
        'label'    => 'Use ECMA-variant',
        'variable' => 'use_ecma',
        'name'     => 'af_use_ecma',
        'value'    => 'use_ecma',
        'checked'  => 1,
    ],
],
```

**Example — with language support**

```php
'additional_fields' => [
    [
        'type'     => 'text',
        'label'    => ['EN' => 'Locale', 'DE' => 'Spracheinstellung'],
        'variable' => 'locale',
        'name'     => 'af_locale',
        'value'    => 'de_DE.utf-8',
        'style'    => 'width: 98%;',
    ],
    [
        'type'     => 'array',
        'label'    => ['EN' => 'Date format', 'DE' => 'Datumsformat'],
        'variable' => 'date_formats',
        'name'     => 'af_date_formats',
        'value'    => [
            'D M d, Y'    => 'a b d Y',
            'M d Y'       => 'b d. %Y',
            'd M Y'       => 'd. b %Y',
            'jS F, Y'     => 'e. B %Y',
            'l, jS F, Y'  => 'A, e. B Y',
        ],
    ],
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

<a id="opf-move-up-before"></a>

### opf_move_up_before()

Moves a filter up, above another one. Call it after
[opf_register_filter](#opf-register-filter) in your installer — freshly
registered filters are appended to the end of their stage group.

Repeat the call with different reference names for every filter you know has to
run after yours. Both filters must be of the same stage.

**Prototypes**

`bool` **opf_move_up_before** ( `string` $name, `string` $ref_name )
`array` **opf_move_up_before** ( `string` $name, `array` $ref_name )
`int` **opf_move_up_before** ( `string` $name )

**Parameters**

- **$name** — `(string)` the filter to move.
- **$ref_name** — `(string)` the filter to move it above, or `(array)` several
  names, in which case `$name` is moved above the topmost of them. Omit it to
  move `$name` to the top of its group.

**Returns**

`TRUE` on success; `FALSE` if the stages do not match or a filter was not found;
an array of booleans when `$ref_name` is an array; the new position when
`$ref_name` is omitted.

**Example**

```php
opf_move_up_before('opf CSS to head', 'Searchengine Highlighter');
```

<a id="opf-unregister-filter"></a>

### opf_unregister_filter()

Removes a filter. Call it from your module's `uninstall.php`.

Do **not** call it from a plugin's `plugin_uninstall.php` — the dashboard
removes plugin registrations itself.

**Prototype**

`bool` **opf_unregister_filter** ( `string` $name )

**Parameters**

- **$name** — `(string)` name of the filter to remove.

**Returns**

`TRUE` on success, `FALSE` otherwise.

**Example**

```php
opf_unregister_filter('PrettyPrint: Google-Code-Prettify');
```

---

<a id="content-helpers"></a>

## Content helpers

All of these treat `$tag`, `$attr` and similar arguments as PCRE regular
expressions, applied **ungreedy** (`PCRE_UNGREEDY`). Capturing groups are not
allowed in those arguments — always use `(?:…)`.

<a id="opf-find-class"></a>

### opf_find_class()

Checks whether a class — or any other attribute value — is present in the
content. The cheap early-exit check every filter should start with.

**Prototype**

`bool` **opf_find_class** ( `string` $content, `string` $match, `string` $tag = '', `string` $attr = 'class' )

**Parameters**

| Parameter  | Type         | Description                            |
| ---------- | ------------ | -------------------------------------- |
| `$content` | string       | The content to search.                 |
| `$match`   | string/regex | What to search for.                    |
| `$tag`     | string/regex | HTML tag to restrict to. Default `''`. |
| `$attr`    | string/regex | Attribute to check. Default `class`.   |

**Returns**

`1` if `$match` is present, `0` if not, `FALSE` on error.

**Examples**

```php
// anywhere in the content
if (opf_find_class($content, 'special_class')) {
    // ... apply filter
}

// only inside a <pre> tag: <pre class="class1 php_highlighter">
if (opf_find_class($content, 'php_highlighter', 'pre')) {

// an id instead of a class: <div class="class2" id="tagSphere">
if (opf_find_class($content, 'tagSphere', 'div', 'id')) {

// regex: class "cpp", "c" or "c++" inside <pre> or <textarea>
if (opf_find_class($content, '(cpp|c|c\+\+)', '(pre|textarea)')) {
```

<a id="opf-add-class"></a>

### opf_add_class()

Adds a class to every occurrence of an HTML tag.

**Prototype**

`bool` **opf_add_class** ( `string` &$content, `string` $class, `string` $tag )

**Parameters**

| Parameter  | Type         | Description            |
| ---------- | ------------ | ---------------------- |
| `$content` | string       | Content, by reference. |
| `$class`   | string       | Class to add.          |
| `$tag`     | string/regex | HTML tag, e.g. `pre`.  |

**Returns**

`TRUE` if the class was added, `FALSE` otherwise.

**Examples**

```php
// every <pre>
opf_add_class($content, 'prettyPrint', 'pre');

// every <pre> and <code> -- note the non-capturing group
opf_add_class($content, 'prettyPrint', '(?:pre|code)');
```

<a id="opf-add-class-to-class"></a>

### opf_add_class_to_class()

Adds a class to every element that already carries another given class.

**Prototype**

`bool` **opf_add_class_to_class** ( `string` &$content, `string` $class, `string` $present_class, `string` $tag = '' )

**Parameters**

| Parameter        | Type         | Description                            |
| ---------------- | ------------ | -------------------------------------- |
| `$content`       | string       | Content, by reference.                 |
| `$class`         | string       | Class to add.                          |
| `$present_class` | string/regex | Class that must already be present.    |
| `$tag`           | string/regex | HTML tag to restrict to. Default `''`. |

**Returns**

`TRUE` if the class was added, `FALSE` otherwise.

**Examples**

```php
opf_add_class_to_class($content, 'php', 'prettify');
// <pre class="prettify">      becomes  <pre class="prettify php">

opf_add_class_to_class($content, 'php', 'prettify', 'code');
// <pre class="prettify">      stays    <pre class="prettify">
// <code class="prettify">     becomes  <code class="prettify php">
```

<a id="opf-add-class-to-attr"></a>

### opf_add_class_to_attr()

Adds a class to every HTML tag carrying a given attribute value.

**Prototype**

`bool` **opf_add_class_to_attr** ( `string` &$content, `string` $class, `string` $attr, `string` $value, `string` $tag = '' )

**Parameters**

| Parameter  | Type         | Description                            |
| ---------- | ------------ | -------------------------------------- |
| `$content` | string       | Content, by reference.                 |
| `$class`   | string       | Class to add.                          |
| `$attr`    | string/regex | Attribute that must be present.        |
| `$value`   | string/regex | The attribute's value.                 |
| `$tag`     | string/regex | HTML tag to restrict to. Default `''`. |

**Returns**

`TRUE` if the class was added, `FALSE` otherwise.

<a id="opf-cut-extract"></a>

### opf_cut_extract()

Cuts regex matches out of the content and replaces them with unique
placeholders, so the rest of your processing cannot touch them. Put them back
with [opf_glue_extract](#opf-glue-extract).

**Prototype**

`array` **opf_cut_extract** ( `string` &$content, `string` $regex, `string` $subpattern = 0, `string` $modifiers = 'iU', `string` $delimiter = '~', `array` $extracts = '' )

**Parameters**

| Parameter     | Type   | Description                                                                               |
| ------------- | ------ | ----------------------------------------------------------------------------------------- |
| `$content`    | string | Content, by reference.                                                                    |
| `$regex`      | string | PCRE pattern without delimiters and modifiers, e.g. `<input[^>]+/>`. Ungreedy by default. |
| `$subpattern` | int    | Which subpattern to use. Default `0` (the whole match).                                   |
| `$modifiers`  | string | PCRE modifiers. Default `iU`.                                                             |
| `$delimiter`  | string | Delimiter. Default `~`.                                                                   |
| `$extracts`   | array  | An array returned by an earlier call, to accumulate into. Optional.                       |

**Returns**

An array of the extracts, empty if the pattern did not match. `FALSE` on error
(with an `E_USER_WARNING`).

**Example — what it looks like**

```php
function opff_work_on_links(&$content, $page_id, $section_id, $module, $wb)
{
    $extracts = opf_cut_extract($content, '<a href=[^>]+>.*</a>');
    // ... work on $extracts ...
    opf_glue_extract($content, $extracts);
    return true;
}
```

`$extracts` then holds placeholder → original pairs:

```php
'@@@OPF_EXTRACT_494e430fc8cf1@@@' => '<a href="…" class="menu_current"> start </a>'
'@@@OPF_EXTRACT_494e430fc8d06@@@' => '<a href="…" class="menu_default"> News </a>'
```

and `$content` carries the placeholders where those links used to be.

**Example — a complete filter**

```php
// Page (last): turn '#' in a menu title into a line break, so a menu title
// "entry# with linebreak" renders as "entry<br /> with linebreak".
// Note $subpattern = 1: only the (.*) part is cut out, not the whole <a>.
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

<a id="opf-glue-extract"></a>

### opf_glue_extract()

Puts extracts back into the content, replacing the placeholders with their
(possibly modified) values.

**Prototype**

`bool` **opf_glue_extract** ( `string` &$content, `array` $extracts )

**Parameters**

- **$content** — `(string)` content, by reference.
- **$extracts** — `(array)` an array returned by
  [opf_cut_extract](#opf-cut-extract).

**Returns**

`TRUE`.

---

## Runtime helpers

<a id="opf-filter-get-data"></a>

### opf_filter_get_data()

Returns a filter's complete stored record. Called without an argument from
inside a filter function, it returns the record of the filter currently running
— the usual way to inspect your own registration.

**Prototype**

`array` **opf_filter_get_data** ( `string` $name = '' )

**Parameters**

- **$name** — `(string)` name of the filter. `''` = the current one.

**Returns**

The filter's data, or `FALSE` on error.

**Example**

```php
$data = opf_filter_get_data();
var_dump($data);
```

```php
array
    'id'              => '12'
    'userfunc'        => '0'
    'position'        => '1'
    'active'          => '1'
    'allowedit'       => '0'
    'allowedittarget' => '1'
    'name'            => 'PrettyPrint: Google-Code-Prettify'
    'func'            => ''
    'type'            => '3section'
    'file'            => '/var/www/wbce/modules/opf_prettify/filter.php'
    'csspath'         => '/var/www/wbce/modules/opf_prettify/prettify/prettify.css'
    'funcname'        => 'opff_prettify'
    'configurl'       => ''
    'modules'         => array('download_gallery', 'manual', 'news', 'wysiwyg')
    'desc'            => 'Prettifies all <pre class="prettyprint"> and ...'
    'pages'           => array('all', '99', '104', …)
    'pages_parent'    => array('all', '99', '104', …)
    'current'         => true
    'activated'       => false
    'failed'          => false
```

<a id="opf-filter-get-rel-pos"></a>

### opf_filter_get_rel_pos()

Where another filter sits relative to the one currently running — has it already
been executed, or is it still to come?

**Prototype**

`int` **opf_filter_get_rel_pos** ( `string` $name )

**Parameters**

- **$name** — `(string)` name of the filter to test.

**Returns**

| Value   | Meaning                                                          |
| ------- | ---------------------------------------------------------------- |
| `-1`    | Filter `$name` ran before the current one.                       |
| `0`     | Filter `$name` *is* the current one.                             |
| `1`     | Filter `$name` will run later.                                   |
| `FALSE` | Filter `$name` is inactive, not installed, or an error occurred. |

**Examples**

```php
if (opf_filter_get_rel_pos('Menu Linebreak')) {
    // installed and active (and not the current one)
}
```

```php
$pos = opf_filter_get_rel_pos('Menu Linebreak');
if ($pos == 1) {
    // will be called after this one
} elseif ($pos == -1) {
    // was already called before this one
}
```

<a id="opf-filter-exists"></a>

### opf_filter_exists()

Whether a filter is installed at all — regardless of whether it is active.

**Prototype**

`bool` **opf_filter_exists** ( `string` $name, `bool` $verbose = FALSE )

**Parameters**

- **$name** — `(string)` name of the filter to test.
- **$verbose** — `(bool)` emit an `E_USER_WARNING` when it does not exist.

**Returns**

`TRUE` if the filter is installed, `FALSE` otherwise.

**Example**

```php
if (opf_filter_exists('Menu Linebreak')) {
    // ...
}
```

<a id="opf-filter-is-active"></a>

### opf_filter_is_active()

Whether a filter is active **for the current module and page** — not just
switched on globally.

**Prototype**

`bool` **opf_filter_is_active** ( `string` $name )

**Parameters**

- **$name** — `(string)` name of the filter.

**Returns**

`TRUE` if the filter is active for the current module and page id, `FALSE`
otherwise.

**Example**

```php
if (opf_filter_is_active('Menu Linebreak')) {
    // ...
}
```

<a id="opf-is-childpage"></a>

### opf_is_childpage()

Whether one page is a sub-page of another — or the same page.

**Prototype**

`bool` **opf_is_childpage** ( `int` $child, `int` $parent )

**Parameters**

- **$child** — `(int)` page id of the possible sub-page.
- **$parent** — `(int)` page id to test against.

**Returns**

`TRUE` if `$child` is below `$parent`, or if both are the same page. `FALSE`
otherwise.

**Example**

```php
if (opf_is_childpage(101, 9)) {
    // page 101 is below page 9
}
```

<a id="opf-filter-get-additional-values"></a>

### opf_filter_get_additional_values()

Returns the current values of the fields declared in
[additional_fields](#additional-fields), keyed by each field's `variable`.

**Prototype**

`array` **opf_filter_get_additional_values** ( `void` )

**Returns**

An array of the values, or `FALSE` on error.

**Example**

```php
$values       = opf_filter_get_additional_values();
$locale       = $values['locale'];
$date_formats = $values['date_formats'];
```

<a id="opf-filter-name-to-setting"></a>

### opf_filter_name_to_setting()

Converts a filter's name into the `settings` key that holds its active state.
Each filter's on/off state is mirrored into a settings row, which WBCE's
`Settings::setup()` then promotes to a same-named PHP constant on every request
— which is how a dashboard toggle can drive a config constant.

**Prototype**

`string` **opf_filter_name_to_setting** ( `string` $name )

**Parameters**

- **$name** — `(string)` name of the filter.

**Returns**

The name of the corresponding setting, lowercased.

**Example**

```php
if (Settings::Get(opf_filter_name_to_setting($name))) {
    // the filter is switched on
}
```

A filter that also targets the backend gets a second row with a `_be` suffix.

---

<a id="legacy-frontend-file-registration"></a>

## Deprecated: legacy frontend-file registration

**Deprecated in WBCE 1.7.0.** `opf_register_frontend_files()`,
`opf_register_onload_event()`, `opf_register_onload()` and
`opf_register_document_ready()` were this module's own head/body injection
system, predating WBCE's asset pipeline. They wrote into the module-local
`$opf_HEADER` / `$opf_BODY` globals, which `opf_insert_frontend_files()` then
flushed into the page.

All four had zero callers left in the shipped codebase, but rather than break
site-specific filters written earlier, they were kept as thin compatibility
shims that delegate to the matching AssetQueue call
(`I::insertCssFile()` / `insertJsFile()` / `insertCssCode()` / `insertJsCode()`).

**Write new filters against the `I::` methods directly.**

One behavioural caveat: `opf_register_frontend_files()`'s `$iehack` parameter
(IE conditional-comment wrapping) always lands in `<body>` regardless of
`$target`, because that path goes through `I::insertHtmlCode()`, and AssetQueue
never permits arbitrary HTML in `<head>`.

`$opf_HEADER` / `$opf_BODY` and `opf_insert_frontend_files()` themselves were
**not** removed — the shipped *Assets Cache Busting* filter reads and writes
those globals directly. Do not write to them from your own filters.

---

*OutputFilter Dashboard documentation — CC BY-SA 3.0 DE. See
[LICENSE.md](../LICENSE.md).*
