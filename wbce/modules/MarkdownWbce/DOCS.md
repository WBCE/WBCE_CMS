# MdReaderLink — Documentation

`MdReaderLink` is available throughout the entire WBCE backend as soon as the module
`MarkdownWbce` is installed. No `require`, no `include` needed.

---

## Variants at a Glance

| Variant | Method | When to use |
|---------|--------|-------------|
| A | `MdReaderLink::file()` | A single file — the normal case |
| B | `MdReaderLink::docs()` | Multiple files as tabs |
| D | `MdReaderLink::dir()` | Directory with `md_reader.json` |

---

## Variant A — Single File

The most common case. Path relative to `WB_PATH` or absolute — both work.

```php
// Relative to WB_PATH
echo MdReaderLink::file('/modules/my_mod/docs/README.md')
            ->title('My Module')
            ->linkHtml('Documentation');

// Absolute path — is automatically converted
echo MdReaderLink::file(WB_PATH . '/modules/my_mod/docs/README.md')
            ->linkHtml('Documentation');

// As a button instead of a link
echo MdReaderLink::file('/modules/my_mod/docs/README.md')
            ->title('My Module')
            ->buttonHtml('Open Documentation');

// Only the URL — for custom <a> tags
$url = MdReaderLink::file('/modules/my_mod/docs/README.md')->url();
```

---

## Variant B — Multiple Files

Multiple documents appear as tabs in the reader header.

```php
echo MdReaderLink::docs([
        ['path' => '/modules/my_mod/docs/README.md',    'label' => 'Overview'],
        ['path' => '/modules/my_mod/docs/CHANGELOG.md', 'label' => 'Changelog'],
        ['path' => '/modules/my_mod/docs/API.md',       'label' => 'API'],
    ])
    ->title('My Module – Documentation')
    ->linkHtml('Documentation');
```

---

## Variant D — Directory

Just pass the folder path. The reader automatically reads `md_reader.json`.

```php
echo MdReaderLink::dir('/modules/my_mod/docs/')
            ->linkHtml('Documentation');
```

### md_reader.json — Simple (one file)

```json
{
    "title": "My Module"
}
```

No `docs` array → Reader loads `README.md` in the same folder.

### md_reader.json — Multiple Files

```json
{
    "title": "My Module",
    "docs": [
        { "file": "README.md",    "label": "Overview" },
        { "file": "CHANGELOG.md", "label": "Changelog" },
        { "file": "API.md",       "label": "API" }
    ]
}
```

---

## Rendering inline in a backend page (no popup)

`MdReaderLink` opens the reader in a popup window. To render a doc **inside**
your own backend page instead, call `MdReaderHelper::renderForEmbed()`:

```php
$doc = MdReaderHelper::renderForEmbed('/modules/my_mod/docs/GUIDE.md', null, 'mdr-toc');
// → ['abs', 'html', 'toc', 'title', 'langs', 'needsCode', 'needsFileTree'] | null

if ($doc) {
    // One call — every stylesheet + script the embed needs (markdown.css +
    // markdown-embed.css, plus file-tree / highlight.js when the doc uses them).
    $a = MdReaderHelper::embedAssets($doc);
    foreach ($a['css'] as $u) { I::insertCssFile($u); }
    foreach ($a['js']  as $u) { I::insertJsFile($u, 'body_late'); }
}
```

Markup — wrap the two pieces so `markdown-embed.css`'s sidebar layout applies
(add `mdr-embed--notoc` and drop the `<aside>` when there is no TOC):

```html
<div class="mdr-embed">
  <aside class="mdr-embed-toc">
    <div class="mdr-embed-toc-h">On this page</div>
    <?= $doc['toc'] ?>
  </aside>
  <article class="markdown-body"><?= $doc['html'] ?></article>
</div>
```

`markdown.css` is self-contained (it brings its own `--mdr-*` tokens, light +
dark) so the embedded doc renders **identically to the reader popup** — no
per-module theming. `markdown-embed.js` measures the backend theme's fixed
header, raises `html { scroll-padding-top }` so anchor jumps clear it, parks the
sticky TOC just below, and runs a scroll spy that marks the current section's
link `a.mdr-nav--active`. The TOC `<li>`s carry `mdr-toc-l1 … l6` (heading depth)
so links are sized/weighted by level. `renderForEmbed()`'s 2nd arg is a locale
(defaults to the current `LANGUAGE`), the 3rd the CSS class for the generated
TOC `<ul>`. `embedAssets()` returns the URLs already `?v=`-stamped with each
file's mtime — pass them straight to `insertCssFile()` / `insertJsFile()`.

---

## Output Methods

```php
// <a> tag with popup JS
->linkHtml('Link text')
->linkHtml('Link text', 'my-css-class')
->linkHtml('Link text', 'btn btn-sm', 'data-foo="bar"')

// <button> tag with popup JS
->buttonHtml('Button text')
->buttonHtml('Button text', 'my-css-class')

// Only the URL — no HTML
->url()
```

---

## Adjusting Popup Size

Default is 1100 × 820 px. Can be overridden per link:

```php
echo MdReaderLink::file('/modules/my_mod/docs/README.md')
            ->popupSize(900, 700)
            ->linkHtml('Documentation');
```

---

## Language Versions

The reader automatically detects language variants — no additional code needed.

If a file `README_DE.md` exists next to `README.md`, it will be preferred automatically
when the language `DE` is active. Flag icons for all available language versions
appear in the reader header.

```file-tree
docs/
 ├── README.md        ← Fallback / English
 ├── README_DE.md     ← German
 ├── README_NL.md     ← Dutch
 // further languages... 
 └── README_FR.md     ← French
```

Flag SVGs must be located under `WB_PATH/languages/`:
`DE.svg`, `EN.svg`, `FR.svg`, etc.

---

## Tree Diagrams — `file-tree` and `page-tree`

Two fenced-block languages render as visual trees instead of code, via
`layout/filetree.js` (the same renderer the TipTap editor uses, so a tree looks
identical in the editor, in the reader and on the frontend):

    ```file-tree      icons from the file extension, folders end in "/"
    ```page-tree      page icons, plus WBCE's page visibility states

Connector characters (`|`, `+`, `` ` ``, or the box-drawing `│ ├ └ ─`) set the
indent depth; in a **file** tree the first space after `name.ext` starts the
comment column, in a **page** tree only an explicit `//`, `#` or `/*` does —
page titles contain spaces.

### Markers

| Marker | Where | Effect |
|---------------------------------------------------------|------------|-------------------------------------------------|
| `{public}` `{hidden}` `{private}` `{registered}` `{none}` | page-tree | sets the row icon to that visibility state |
| `{menulink}` or 🔗 | both | inserts a link icon **where it stands** |

A page without a visibility marker renders as `public`. A row that groups others
(top level, or a name ending in `/`) keeps the group icon unless it carries a
marker itself. Markers are stripped from the rendered text.

```page-tree
EN {menulink}
│   ├── Home
│   ├── Blog {hidden}          // not in the menu
│   └── Über Mich {private}
```

---

## Complete Example

```php
// In a module backend, e.g. in pages.php or modify.php:

$helpLink = MdReaderLink::file(WB_PATH . '/modules/catalogue_hub/docs/README.md')
                   ->title('CatalogueHub – Help')
                   ->popupSize(1100, 840)
                   ->linkHtml('? Help', 'btn-help');

echo '<div class="module-toolbar">' . $helpLink . '</div>';
```

---

## Security Model (Read/Write)

`reader.php` and `ajax_save_doc.php` always require a valid backend
`Admin` session — even if the link is opened from a frontend page
(cookie-based, not URL-path-based; a logged-in editor browsing
the live site sees the same reader as in the backend).

| Action | Check |
|---|---|
| Read | valid `Admin` session (any logged-in backend user) |
| Write/Overwrite | `$admin->isAdmin()` **or** `MarkdownWbce_tool` permission (Access Management → Groups → AdminTools, same pattern as `admin/admintools/tool.php`) |

Additional hardening in `MdReaderHelper::safePath()`: only `.md` files, only
within `WB_PATH`, and `ajax_save_doc.php` exclusively overwrites
already existing files (no creation of new files via the editor).
Every save is logged to `var/markdown_wbce/save.log` (JSON-Lines,
who/when/which path).