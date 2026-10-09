# WBCE asset system — reference

Complete reference for the asset system: every `I::` method, every position
name, the file convention, the `asset-pos` syntax and the old-to-new mappings
from WBCE 1.x.

> For **learning**, the [**Assets in templates**](?tool=asset_optimizer&tab=docs&doc=embed)
> tab is a better fit (`ASSETS_TUTORIAL.md` — step by step, with "call → this is
> the HTML"). If you only want to operate the back-end tool: the
> [**Guide**](?tool=asset_optimizer&tab=docs&doc=guide) (`TOOL_GUIDE.md`) tab.

---

## 1. What is the asset system?

Anyone who develops a module or template in WBCE sooner or later has to include
CSS and JavaScript files in the page. The naive solution — writing `<link>` and
`<script>` tags directly into the module code — quickly leads to problems:

- The same jQuery version is loaded by five different modules.
- A module writes `<link>` in the middle of the `<body>` because it knows no
  other way.
- The `<head>` is full of tags nobody controls.

The **asset system** solves this. It provides a central queue that every
component (modules, templates, snippets) registers its assets into. Only at the
end of the page build does the system inject everything at once at the right
place — deduplicated, ordered, with no double loading.

**Access:** through the static `I` class — callable from anywhere in the CMS.

```php
// Example: a module registers a CSS file
I::insertCssFile(WB_URL . '/modules/my_module/assets/frontend.css');
```

---

## 2. How does it work?

```
┌─────────────────────────────────────────────────────────┐
│  Page build (PHP)                                       │
│                                                         │
│  Modules, templates, snippets call I::                  │
│    → assets land in the internal queue                  │
│                                                         │
│  At the end: I::process($html)                          │
│    1. Scanner: finds assets in the body with asset-pos  │
│    2. Injection: writes queue entries to HTML anchors   │
│    3. Reset: the queue is cleared                       │
└─────────────────────────────────────────────────────────┘
```

**Key properties:**

- **Deduplication** — the same file is loaded only once, however often it is
  registered. This also applies when the URL is given once as a token
  (`{MODULES}/foo/bar.css`) and once as a full URL.
- **No template changes needed** — the system finds `</head>` and `</body>`
  itself. Templates need no placeholders.
- **Order** — within one position: whoever registers first appears first in the
  HTML.

---

## 3. Injection points — overview

```
<html>
<head>
  <title>Page title</title>
  │
  ├── head_early   ← after </title> — preloads, critical CSS
  │
  │   <meta ...>
  │   <meta name="description" ...>
  ├── head_middle  ← meta tags, title replacement
  │
  │   <link rel="stylesheet" href="theme.css">
  ├── head_late    ← default CSS position
  │
  └── head_last    ← very last before </head>
</head>
<body>
  │
  ├── body_top     ← very first in the body (e.g. GTM noscript)
  │
  ├── body_early   ← early body area
  │
  │   ... page content ...
  │
  ├── body_late    ← default JS position (with defer)
  │
  └── body_last    ← very last before </body>
</body>
</html>
```

**In short:** when no position is given, CSS files land in `head_late` and
JavaScript files in `body_late`.

---

## 4. Getting started: the file convention

The simplest way to include assets in WBCE: **name the files correctly and put
them in the module directory.** The CMS loads them automatically.

```file-tree
modules/
└── my_module/
    ├── assets/              ← recommended subfolder (tidier)
    │   ├── frontend.css     → loaded on front-end pages (head_late)
    │   ├── frontend.js      → loaded on front-end pages (body_late)
    │   └── frontend_body.js → loaded on front-end pages in body_late
    │
    ├── frontend.css         → alternatively: directly in the module folder (legacy)
    └── frontend.js          → alternatively: directly in the module folder (legacy)
```

The same rules apply to the back end — just with `backend` instead of
`frontend`:

```file-tree
assets/
    backend.css
    backend.js
    backend_body.js
```

**How is this triggered?** The template calls `register_frontend_modfiles()`
once — the CMS does the rest automatically for every active module on the page.

> The file convention is the preferred method for simple cases. For more complex
> scenarios the PHP API is available (→ section 5).

---

## 5. PHP API — step by step

All methods are static and can be called from anywhere:

```php
I::insertCssFile(...);
I::insertJsFile(...);
// etc.
```

### 5.1 Including CSS files

```php
// Simplest form — lands in head_late
I::insertCssFile(WB_URL . '/modules/my_module/assets/frontend.css');

// With token syntax (shorter, recommended)
I::insertCssFile('{MODULES}/my_module/assets/frontend.css');

// Different position
I::insertCssFile('{MODULES}/my_module/assets/critical.css', 'head_early');

// With a media attribute (print only)
I::insertCssFile('{MODULES}/my_module/assets/print.css', 'head_late', ['media' => 'print']);

// Several files at once
I::insertCssFile([
    '{MODULES}/my_module/assets/base.css',
    '{MODULES}/my_module/assets/layout.css',
]);
```

**Available URL tokens:**

| Token         | Replaced with                        |
| ------------- | ------------------------------------ |
| `{MODULES}`   | URL of the modules/ directory        |
| `{WB_URL}`    | base URL of the WBCE install         |
| `{TEMPLATE}`  | URL of the active front-end template |
| `{THEME_URL}` | URL of the active admin theme        |
| `{MEDIA_URL}` | URL of the media directory           |

You can register your own tokens:

```php
I::addUrlToken('{MY_PLUGIN}', WB_URL . '/modules/my_plugin');
I::insertCssFile('{MY_PLUGIN}/assets/style.css');
```

---

### 5.2 Including JavaScript files

```php
// Default — lands in body_late, gets defer="" automatically
I::insertJsFile('{MODULES}/my_module/assets/frontend.js');

// Into the head (e.g. for libraries that must load early)
I::insertJsFile('{MODULES}/my_module/assets/lib.js', 'head_early');

// As an ES module
I::insertJsFile('{MODULES}/my_module/assets/app.js', 'body_late', ['type' => 'module']);

// Disable defer explicitly (unusual)
I::insertJsFile('{MODULES}/my_module/assets/sync.js', 'body_late', ['defer' => false]);
```

> **Note:** files that land in `body_*` positions automatically get
> `defer="defer"`. That's the modern, performant default behaviour.

---

### 5.3 Inline CSS and JS

Sometimes small code snippets need to be embedded directly in the page — without
a file of their own.

```php
// Inline CSS — lands in a <style> block in head_late
I::insertCssCode('.my-banner { background: #f00; padding: 1em; }');

// Set CSS variables early
I::insertCssCode(':root { --primary: #3498db; }', 'head_early');

// Inline JS — lands in a <script> block in body_late
I::insertJsCode('document.addEventListener("DOMContentLoaded", function() {
    console.log("page loaded");
});');

// Make a JS variable available early
I::insertJsCode('window.MY_MODULE_CONFIG = ' . json_encode($config) . ';', 'head_late');
```

---

### 5.4 Injecting HTML blocks

For cases where plain HTML (no CSS or JS) needs to go into the body:

```php
// Cookie banner at the top of the page
I::insertHtmlCode(
    '<div id="cookie-banner" class="banner">We use cookies...</div>',
    'body_early',
    'cookie-banner'   // optional ID for later removal
);

// Hidden modal at the bottom of the page
I::insertHtmlCode('<div id="modal-overlay" hidden></div>', 'body_late');
```

---

### 5.5 Setting meta tags

```php
// Short form: name + content (replaces an existing tag of the same name)
I::insertMeta('description', 'My page about wooden furniture');
I::insertMeta('robots',      'noindex, nofollow');
I::insertMeta('author',      'Jane Doe');

// Full tag — for Open Graph, Twitter Cards etc.
I::insertMeta('<meta property="og:title" content="My title">');
I::insertMeta('<meta property="og:image" content="' . WB_URL . '/media/image.jpg">');

// Always add, never replace
I::insertMeta('<meta property="og:locale" content="en_GB">', 'add');

// Legacy array syntax (still supported)
I::insertMetaTag(['name' => 'keywords', 'content' => 'furniture, wood, design']);
```

**How does the replacement work?** The system searches the existing `<head>` for
a `<meta>` tag with the same `name`, `property` or `http-equiv` attribute and
replaces it in place. If no matching tag is found, the new tag is inserted in
`head_middle`.

---

### 5.6 Setting the page title

```php
// Replaces the template's <title> tag
I::insertTitle('My product — ' . WEBSITE_TITLE);
```

Here too the system finds the existing `<title>` tag and replaces it in place.

---

### 5.7 Combining files into bundles

Several CSS or JS files can be merged into a single file. This reduces HTTP
requests and speeds the page up.

```php
// CSS bundle
I::insertCssBundle([
    '{MODULES}/my_module/assets/base.css',
    '{MODULES}/my_module/assets/layout.css',
    '{MODULES}/my_module/assets/components.css',
], 'my-module-bundle');

// JS bundle
I::insertJsBundle([
    '{MODULES}/my_module/assets/utils.js',
    '{MODULES}/my_module/assets/app.js',
], 'my-module-app');
```

**How does the cache work?** The bundle is stored as a file in `cache/assets/`.
The cache is invalidated automatically as soon as one of the source files
changes (the modification timestamp is checked).

**Important:** files that are part of a bundle are not loaded individually again
— not even when they are later registered via `I::insertCssFile()` or the
automatic file convention.

**CDN URLs in bundles:** external URLs (http/https) cannot be bundled. They are
queued automatically as individual `<link>` / `<script>` tags after the bundle.
Recommendation: include CDN files directly with `I::insertCssFile()` /
`I::insertJsFile()` to control the load order explicitly.

**Minification.** These constants control the shrinking. Day to day you set them
through the **Asset Optimizer** tool in the back end (which writes them to
`var/config_constants.ini.php`); if you set them by hand, use
`var/config_constants.ini.php` or — not recommended — `config.php`:

```php
define('MINIFY_CSS', true);   // minify CSS (bundles + individual files)
define('MINIFY_JS',  true);   // minify JS  (bundles + individual files)

// Developer source view: logged-in admins only — bundling is undone and
// minification disabled, every file appears individually in DevTools.
// Every other visitor still gets bundled/minified assets.
// (The old spellings ASSET_MINIFY_DEBUG / MINIFY_ASSETS_DEBUG still work.)
define('ASSETS_MINIFY_DEBUG', true);

// Custom cache directory for minified and bundled files.
// Default: WB_PATH/cache/assets/
define('MINIFY_ASSETS_DIR', '/absolute/path/to/custom/cache/');

// Set to false to drop the .min. in the cache filename.
// Default: true  →  mymodule-css-main.min.css
//          false →  mymodule-css-main.css
define('MINIFY_USE_SUFFIX', false);

// Cache busting: appends ?mtime to every local asset URL. Set through the
// Asset Optimizer tool or the "Assets Cache Busting" filter, and stored in
// the settings table, not in a file.
define('OPF_ASSETS_CACHE_BUSTING', true);
```

When `MINIFY_CSS` / `MINIFY_JS` is active, individual local files are also
minified on the first call and stored in `cache/assets/` — with a
human-readable name:

```
modules/mymodule/assets/backend_custom.css  →  cache/assets/mymodule-assets-backend_custom.min.css
templates/wbcetik/css/main.css              →  cache/assets/wbcetik-css-main.min.css
```

External URLs (CDN) are never minified.

---

## 6. Assets without PHP: `asset-pos` and `<!--(MOVE)-->`

Sometimes calling `I::` directly in PHP isn't possible. That happens e.g.:

- in the **WYSIWYG editor** — an editor pastes embed code with `<script>` or
  `<link>` that ends up in the middle of the page content
- in a **Twig template** — you write plain HTML/Twig with no embedded PHP
- in **module output** that comes as finished HTML strings from a database
- with **third-party code** (Google Maps, Analytics, widget embeds) that you can
  only copy-paste

The asset system solves this with two mechanisms: the `asset-pos` attribute and
the `<!--(MOVE)-->` block syntax. Both are processed by the scanner at the end
of the page build — regardless of where in the HTML they sit.

---

### 6.1 The `asset-pos` attribute

Add `asset-pos="POSITION"` directly to an HTML tag. The scanner recognises it,
takes the tag out of its current position and inserts it at the right place — as
if it had been registered with `I::insertCssFile()` or `I::insertJsFile()`.

#### Including a CSS file

```html
<link rel="stylesheet" href="/modules/my_module/assets/frontend.css" asset-pos="head_late">
```

Equivalent PHP call: `I::insertCssFile('/modules/my_module/assets/frontend.css')`

#### Inline CSS

```html
<style asset-pos="head_early">
  :root {
    --primary-color: #3d7fbc;
    --font-base: 'Inter', sans-serif;
  }
</style>
```

Without `asset-pos`, a `<style>` block in the body would count as valid and not
be moved. With `asset-pos` it lands cleanly in the `<head>`.

#### Including a JS file

```html
<script src="/modules/my_module/assets/frontend.js" asset-pos="body_late"></script>
```

The tag automatically gets `defer="defer"` — exactly as with `I::insertJsFile()`.

#### Inline JS

```html
<script asset-pos="body_late">
  document.addEventListener('DOMContentLoaded', function () {
    initMyWidget({ color: '#3d7fbc', speed: 400 });
  });
</script>
```

> **Important:** `<script>` tags **without** `asset-pos` are not touched by the
> scanner at all. Inline scripts meant to run immediately stay exactly where
> they are. Only explicitly marked tags are moved.

#### Several tags — each gets its own `asset-pos`

Even when embed code consists of several related tags, no block syntax is
needed. Each tag simply gets its own `asset-pos`:

```html
<!-- Google Analytics — both tags marked individually -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"
        asset-pos="head_late"></script>
<script asset-pos="head_late">
  window.dataLayer = window.dataLayer || [];
  function gtag(){ dataLayer.push(arguments); }
  gtag('js', new Date());
  gtag('config', 'G-XXXXXXXXXX');
</script>
```

The system processes each tag independently and inserts both at the right
position — in the order they appear in the document.

#### All supported tag types

| Tag                             | With `asset-pos` | Without `asset-pos`                         |
| ------------------------------- | ---------------- | ------------------------------------------- |
| `<link rel="stylesheet">`       | to the position  | moved to `head_late` automatically          |
| `<style>...</style>`            | to the position  | stays in the body                           |
| `<script src="...">`            | to the position  | stays in the body (no defer)                |
| `<script>...</script>` (inline) | to the position  | stays in the body, runs immediately         |
| `<meta ...>`                    | –                | moved into the head automatically           |
| `<title>...</title>`            | –                | replaces the existing `<title>` in the head |

---

### 6.2 Several assets as a group — `<asset-group>`

When several related tags should be moved to a position together,
`<asset-group>` wraps them in one container:

```html
<asset-group pos="body_late">
  <link rel="stylesheet" href="widget.css">
  <script src="widget-loader.js"></script>
  <script>initWidget({ id: 'main' });</script>
</asset-group>
```

The scanner processes each child tag individually, puts it at the given
position, and removes the container from the document entirely.

#### Per-tag override

A child tag can override the group position with its own `asset-pos`:

```html
<asset-group pos="body_late">
  <!-- CSS should be earlier — override to head_early -->
  <link rel="stylesheet" href="critical.css" asset-pos="head_early">

  <!-- JS stays at body_late (group default) -->
  <script src="app.js"></script>

  <!-- inline script also body_late -->
  <script>bootstrap();</script>
</asset-group>
```

#### Use cases

**Third-party embed with several tags** — no `asset-pos` needed on each
individual tag:

```html
<asset-group pos="head_late">
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){ dataLayer.push(arguments); }
    gtag('js', new Date());
    gtag('config', 'G-XXXXXXXXXX');
  </script>
</asset-group>
```

**Cookie banner as a self-contained HTML snippet:**

```html
<asset-group pos="body_early">
  <link rel="stylesheet" href="/templates/my_template/css/cookie-banner.css"
        asset-pos="head_late">

  <div id="cookie-banner" role="alertdialog">
    <p>We use cookies. <a href="/privacy">Learn more</a></p>
    <button id="cookie-accept">Accept</button>
  </div>

  <script asset-pos="body_last">
    document.getElementById('cookie-accept').addEventListener('click', function () {
      document.getElementById('cookie-banner').remove();
      document.cookie = 'cookies_ok=1; max-age=31536000; path=/';
    });
  </script>
</asset-group>
```

The `<div>` (not an asset tag) lands as an HTML block at `body_early`. CSS and
script land at their respective positions thanks to their own `asset-pos`
attributes.

---

### 6.3 The `<!--(MOVE)-->` block syntax *(deprecated)*

> **Don't use for new code.** Supported only for backward compatibility. For all
> new cases: `<asset-group>` (→ 6.2) or `asset-pos` directly on the tag (→ 6.1).

```html
<!--(MOVE) body_late -->
<script>...</script>
<!--(END)-->
```

Migration: replace the container with `<asset-group pos="...">`, remove the
`<!--(MOVE)-->` / `<!--(END)-->` comments.

---

### 6.4 Practical scenarios

#### Scenario 1 — WYSIWYG editor (an editor pastes embed code)

An editor pastes the following code straight into TinyMCE:

```html
<!-- This is how it comes out of the third party's embed form -->
<link rel="stylesheet" href="https://cdn.example.com/widget.css">
<div id="my-widget" data-id="12345"></div>
<script src="https://cdn.example.com/widget.js"></script>
```

The problem: `<link>` and `<script>` land in the middle of the `<body>`. With a
small change by the editor, or an adjustment in the module template:

```html
<link rel="stylesheet" href="https://cdn.example.com/widget.css" asset-pos="head_late">
<div id="my-widget" data-id="12345"></div>
<script src="https://cdn.example.com/widget.js" asset-pos="body_late"></script>
```

Result: CSS lands in the `<head>`, the script lands before `</body>` with
`defer` — cleanly separated, without the editor having to understand PHP.

---

#### Scenario 2 — Twig template with no PHP calls

A module renders its output through Twig. In the `.twig` file you can't call
`I::insertCssFile()` — but you can write `asset-pos` directly into the HTML:

```twig
{# modules/my_module/twig/view.twig #}

<link rel="stylesheet" href="{{ WB_URL }}/modules/my_module/assets/frontend.css" asset-pos="head_late">

<div class="my-module">
    {% for item in items %}
        <article>{{ item.title }}</article>
    {% endfor %}
</div>

<script src="{{ WB_URL }}/modules/my_module/assets/frontend.js" asset-pos="body_late"></script>
```

The scanner processes the finished HTML after rendering — the Twig syntax is
long resolved by then.

> **Better:** if the module has an `initialize_fe.php`, register the assets there
> in PHP. That's cleaner. `asset-pos` in Twig makes sense when the module is
> only loaded in certain cases and the assets should appear conditionally.

---

#### Scenario 3 — conditional asset load directly in HTML

A template block is only output on certain pages. The assets should only be
loaded then:

```html
<!-- This block only appears on the contact page -->
<div class="contact-form">
  <form>...</form>
</div>

<style asset-pos="head_late">
  .contact-form { max-width: 640px; margin: 0 auto; }
  .contact-form input { width: 100%; margin-bottom: 1rem; }
</style>

<script asset-pos="body_late">
  document.querySelector('.contact-form').addEventListener('submit', function (e) {
    e.preventDefault();
    // form validation
  });
</script>
```

Because the block only appears in the HTML when the page outputs it, CSS and JS
are automatically loaded only on that page — with no PHP logic.

---

#### Scenario 4 — static HTML snippet (include)

A template includes a plain HTML file (e.g. a cookie banner). Each tag gets its
own `asset-pos` — no block syntax needed:

```html
<!-- templates/my_template/partials/cookie-banner.html -->

<link rel="stylesheet" href="/templates/my_template/css/cookie-banner.css"
      asset-pos="head_late">

<div id="cookie-banner" role="alertdialog" aria-modal="true" asset-pos="body_early">
  <p>We use cookies. <a href="/privacy">Learn more</a></p>
  <button id="cookie-accept">Accept</button>
</div>

<script asset-pos="body_late">
  document.getElementById('cookie-accept').addEventListener('click', function () {
    document.getElementById('cookie-banner').remove();
    document.cookie = 'cookies_ok=1; max-age=31536000; path=/';
  });
</script>
```

The HTML file registers its own assets — each tag lands exactly where it
belongs, no PHP needed, no change to the template.

---

### 6.5 Quick reference

| Goal                        | Method                                                    |
| --------------------------- | --------------------------------------------------------- |
| include a CSS file          | `<link href="..." asset-pos="head_late">`                 |
| insert inline CSS           | `<style asset-pos="head_late">...</style>`                |
| include a JS file           | `<script src="..." asset-pos="body_late">`                |
| insert inline JS            | `<script asset-pos="body_late">...</script>`              |
| group several tags          | `<asset-group pos="body_late">...</asset-group>`          |
| per-tag override in a group | `asset-pos="..."` directly on the child tag               |
| set a meta tag              | `<meta name="..." content="...">` (no `asset-pos` needed) |
| block syntax *(deprecated)* | `<!--(MOVE) POSITION -->...<!--(END)-->` — legacy only    |

---

## 7. File convention — full reference

For every module and every context there are three file types:

| Suffix                  | Type                             | Behaviour                                  |
| ----------------------- | -------------------------------- | ------------------------------------------ |
| `frontend.css`          | **base**                         | loaded unless a custom file exists         |
| `frontend_custom.css`   | **custom**                       | replaces the base file entirely            |
| `frontend_override.css` | **override**                     | loaded additively **after** base or custom |
| `frontend.override.css` | **legacy custom** *(deprecated)* | behaves like `_custom`                     |

The same logic applies to all file kinds:

```file-tree
frontend.css              backend.css
frontend.js               backend.js
frontend_body.js          backend_body.js

frontend_custom.css       backend_custom.css
frontend_custom.js        backend_custom.js
frontend_body_custom.js   backend_body_custom.js

frontend_override.css     backend_override.css
frontend_override.js      backend_override.js
frontend_body_override.js backend_body_override.js
```

**Locations** (both are supported, `assets/` is preferred):

```file-tree
    modules/my_module/assets/frontend.css   // preferred (tidier)
    modules/my_module/frontend.css          // legacy (still supported)
```

**Priority** (the first file found wins):

```
1. assets/frontend_custom.css     ← custom in assets/
2. frontend_custom.css            ← custom in root
3. assets/frontend.override.css   ← legacy custom in assets/ (deprecated)
4. frontend.override.css          ← legacy custom in root    (deprecated)
5. assets/frontend.css            ← base in assets/
6. frontend.css                   ← base in root
```

After that, regardless of which base/custom file was chosen:

```
7. assets/frontend_override.css   ← override in assets/ (additive)
8. frontend_override.css          ← override in root    (additive)
```

**Typical use cases:**

```
# Module ships base styles, the site operator wants to replace them entirely:
modules/news_img/frontend_custom.css

# Module ships base styles, the site operator wants only a few tweaks:
modules/news_img/frontend_override.css

# Module ships a new layout, the old layout stays as a fallback:
modules/news_img/assets/frontend.css     ← new layout
modules/news_img/frontend.css            ← never reached, because assets/ has priority
```

---

## 8. Cheat sheets

### 8.1 Injection points

| Position      | Anchor in the HTML | Default for                            |
| ------------- | ------------------ | -------------------------------------- |
| `head_top`    | after `<head>`     | DNS prefetch, the very first resources |
| `head_early`  | after `</title>`   | critical CSS, preloads                 |
| `head_middle` | before `</head>`   | meta tags, title                       |
| `head_late`   | before `</head>`   | **default CSS**                        |
| `head_last`   | before `</head>`   | last CSS/JS in the head                |
| `body_top`    | after `<body>`     | tag manager noscript etc.              |
| `body_early`  | after `<body>`     | early body HTML                        |
| `body_late`   | before `</body>`   | **default JS** (with defer)            |
| `body_last`   | before `</body>`   | very last JS                           |

**Shorthands** (resolved automatically):

| Shorthand  | Becomes                              |
| ---------- | ------------------------------------ |
| `'head'`   | `head_late`                          |
| `'body'`   | `body_late`                          |
| `'early'`  | `head_early`                         |
| `'middle'` | `head_middle`                        |
| `'late'`   | `head_late` (CSS) / `body_late` (JS) |

---

### 8.2 I:: methods

```php
// Files
I::insertCssFile($url, $position = 'head_late', $attrs = [], $id = '')
I::insertJsFile($url,  $position = 'body_late', $attrs = [], $id = '')

// Bundles
I::insertCssBundle([$url, ...], $identifier, $position = 'head_late')
I::insertJsBundle( [$url, ...], $identifier, $position = 'body_late')

// Inline code
I::insertCssCode($code, $position = 'head_late', $id = '')
I::insertJsCode( $code, $position = 'body_late', $id = '')

// HTML
I::insertHtmlCode($html, $position = 'body_early', $id = '')

// Head elements
I::insertMeta($nameOrTag, $contentOrAction = 'replace', $position = 'head_middle')
I::insertMetaTag(['name' => '...', 'content' => '...'])   // legacy array syntax
I::insertTitle($text)

// Management
I::addUrlToken('{TOKEN}', $url)
I::remove($type, $id)          // remove an entry from the queue
I::clearCache()                // clear the bundle cache
I::process(string &$content)   // run manually (normally automatic)

// Legacy / compat
I::insertCssBundle([...], $id)   // @deprecated → insertCssBundle()
I::insertJsBundle([...],  $id)   // @deprecated → insertJsBundle()
I::doFilter($content)              // → returns a string (OutputFilter compatibility)
I::addPlaceholdersToDom($html)     // → removes old <!--(PH)--> markers
I::resetTitle()
I::delJs($id)
I::delCss($id)
```

---

### 8.3 File convention

```file-tree
modules/{name}/
├── assets/                          ← recommended
│   ├── frontend.css                 front-end CSS  (base)
│   ├── frontend_custom.css          front-end CSS  (replaces base)
│   ├── frontend_override.css        front-end CSS  (additive after base/custom)
│   ├── frontend.js                  front-end JS   (base, head)
│   ├── frontend_custom.js           front-end JS   (replaces base)
│   ├── frontend_override.js         front-end JS   (additive)
│   ├── frontend_body.js             front-end JS   (base, body)
│   ├── frontend_body_custom.js      front-end JS   (replaces body base)
│   ├── frontend_body_override.js    front-end JS   (additive, body)
│   ├── backend.css                  back-end CSS   (base)
│   ├── backend_custom.css           back-end CSS   (replaces base)
│   ├── backend_override.css         back-end CSS   (additive)
│   ├── backend.js                   back-end JS    (base, head)
│   ├── backend_custom.js            back-end JS    (replaces base)
│   ├── backend_override.js          back-end JS    (additive)
│   ├── backend_body.js              back-end JS    (base, body)
│   ├── backend_body_custom.js       back-end JS    (replaces body base)
│   └── backend_body_override.js     back-end JS    (additive, body)
└── (the same files directly in the module root) ← legacy, still supported
```

---

### 8.4 Old → new position names

If you're migrating from older WBCE code, here are the equivalents:

| Old position                 | New position  | Note                              |
| ---------------------------- | ------------- | --------------------------------- |
| `HEAD TOP+`                  | `head_top`    |                                   |
| `HEAD TOP-`                  | `head_early`  |                                   |
| `HEAD BTM+`                  | `head_late`   | default for CSS                   |
| `HEAD BTM-`                  | `head_last`   |                                   |
| `HEAD`                       | `head_late`   |                                   |
| `CSS HEAD TOP+`              | `head_top`    |                                   |
| `CSS HEAD TOP-`              | `head_early`  |                                   |
| `CSS HEAD BTM+`              | `head_late`   |                                   |
| `CSS HEAD BTM-`              | `head_last`   |                                   |
| `JS HEAD TOP+`               | `head_top`    |                                   |
| `JS HEAD TOP-`               | `head_early`  |                                   |
| `JS HEAD BTM+`               | `head_late`   |                                   |
| `JS HEAD BTM-`               | `head_last`   |                                   |
| `CSS HEAD MODFILES`          | `head_late`   | automatic via the file convention |
| `JS HEAD MODFILES`           | `head_late`   | automatic via the file convention |
| `BODY TOP+`                  | `body_top`    |                                   |
| `BODY TOP-`                  | `body_early`  |                                   |
| `BODY BTM+`                  | `body_late`   | default for JS                    |
| `BODY BTM-`                  | `body_last`   |                                   |
| `BODY`                       | `body_late`   |                                   |
| `JS BODY TOP+`               | `body_top`    |                                   |
| `JS BODY TOP-`               | `body_early`  |                                   |
| `JS BODY BTM+`               | `body_late`   |                                   |
| `JS BODY BTM-`               | `body_last`   |                                   |
| `JS BODY MODFILES`           | `body_late`   | automatic via the file convention |
| `HTML BODY TOP+`             | `body_top`    |                                   |
| `HTML BODY TOP-`             | `body_early`  |                                   |
| `HTML BODY BTM+`             | `body_late`   |                                   |
| `HTML BODY BTM-`             | `body_last`   |                                   |
| `HEAD+` *(meta)*             | `head_middle` |                                   |
| `KEY+` *(keywords meta)*     | `head_middle` |                                   |
| `DESC+` *(description meta)* | `head_middle` |                                   |

**Legacy `<!--(MOVE)-->` syntax** *(deprecated, still supported):*

```html
<!-- Old — still processed, but no longer recommended -->
<!--(MOVE) JS BODY BTM- -->
<script>...</script>
<!--(END)-->

<!-- New — directly on the tag -->
<script asset-pos="body_last">...</script>

<!-- Or in PHP -->
<?php I::insertJsCode('...', 'body_last'); ?>
```
