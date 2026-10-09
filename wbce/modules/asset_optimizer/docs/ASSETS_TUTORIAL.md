# AssetQueue — Tutorial

This tutorial shows how CSS and JavaScript files can be included since WBCE
1.7.0, and what each call actually writes into the HTML output.

> **Who is this for?** People who build templates or modules and write code
> along the way. If you only want to operate the **Asset Optimizer** tool in the
> back end (switches for minification and cache busting), read the
> [**Guide**](?tool=asset_optimizer&tab=docs&doc=guide) (`TOOL_GUIDE.md`) tab
> instead — it has no code in it.

---

## Terms in one sentence

| Term          | Meaning                                                                              |
| ------------- | ------------------------------------------------------------------------------------ |
| HTTP request  | A single request from the browser to the server to fetch one file.                   |
| CDN           | A third-party server that delivers a file (e.g. `code.jquery.com`).                  |
| minify        | Remove whitespace and comments from a file; the content stays the same.              |
| Bundle        | Several source files combined into one delivered file.                               |
| `defer`       | HTML attribute: the script loads in parallel and runs only after the page is parsed. |
| `async`       | HTML attribute: the script loads in parallel and runs as soon as it arrives.         |
| Cache busting | A `?<number>` on the URL that changes whenever the file changes — forces a reload.   |
| `mtime`       | The "modification time", a file's change timestamp on the server.                    |

> **`insertCssFile()` and `I::insertCssFile()` are the same thing.** The short
> form without `I::` is a global shortcut for the call on the `I` class. Both
> work in PHP; in Twig templates only the short form exists. This tutorial uses
> the short form — except for `insertCssBundle()` / `insertJsBundle()`, which
> only exist as an `I::` call.

---

## Why use the queue system?

The traditional approach in templates (and modules) is:

```php
<link rel="stylesheet" href="<?=TEMPLATE_DIR; ?>/styles.css">
<script src="<?=TEMPLATE_DIR; ?>/mymod/frontend.js"></script>
```

That works — but it has several problems:

**1. Duplicate includes.** If two modules include the same jQuery, it ends up in
the HTML twice. The queue system recognises identical files and skips the second
entry automatically.

**2. Uncontrolled order.** An `echo` lands exactly where it sits in the PHP code
— often in the middle of the body, sometimes too early, sometimes too late for
dependencies.

**3. No bundling.** Ten `<link>` tags mean ten HTTP requests. On HTTP/1.1
especially, that makes a measurable difference. Even on HTTP/2, bundling pays off
for often-cached bundles (one cache miss instead of ten).

**4. No automatic cache busting.** After an update the browser cache keeps
serving the old file until the user clears it manually — or until `?v=2` is added
by hand.

> **The queue system solves all four:** calls are collected, sorted, injected at
> the right position in the document, (optionally) bundled and minified, and
> given cache-busting parameters automatically.

---

## insertCssFile() / insertJsFile()

The basic functions. In PHP or in a template's bootstrap:

```php
insertCssFile('{TEMPLATE}/styles.css');
insertJsFile('{MODULES}/mymod/frontend.js');
```

**What ends up in `<head>`:**

```html
<link rel="stylesheet" href="/wbce/templates/mytheme/styles.css?1720000000">
```

**What ends up before `</body>`:**

```html
<script src="/wbce/modules/mymod/frontend.js?1720000000"></script>
```

CSS defaults to `head_late`, JS to `body_late`.

### With a position

```php
insertCssFile('{TEMPLATE}/critical.css', 'head_early');
insertJsFile('{TEMPLATE}/init.js', 'head_late');
```

### With HTML attributes

```php
// Print only
insertCssFile('{TEMPLATE}/print.css', 'head_late', ['media' => 'print']);

// Defer — loads in parallel, runs after parsing
insertJsFile('{MODULES}/mymod/heavy.js', 'body_late', ['defer' => true]);

// Async — loads and runs immediately once available
insertJsFile('{MODULES}/mymod/tracker.js', 'body_late', ['async' => true]);

// ES module
insertJsFile('{TEMPLATE}/app.js', 'body_late', ['type' => 'module']);
```

**HTML output for the examples:**

```html
<link rel="stylesheet" href="…/print.css?…" media="print">
<script src="…/heavy.js?…" defer></script>
<script src="…/tracker.js?…" async></script>
<script src="…/app.js?…" type="module"></script>
```

### With an ID (dedup key)

```php
insertJsFile(WB_URL . '/include/jquery/jquery-min.js', 'head_early', [], 'jquery');
```

An ID prevents duplicate includes even when the same file is requested under
different URLs. Anyone who includes jQuery again later (same ID) is ignored.

---

## The token system

Instead of absolute URLs, token placeholders are used and resolved at runtime:

| Token             | Resolves to                    |
| ----------------- | ------------------------------ |
| `{TEMPLATE}`      | URL of the active template     |
| `{MODULES}`       | `WB_URL/modules`               |
| `{MODULES_URL}`   | same as `{MODULES}`, alternate |
| `{TEMPLATES_URL}` | `WB_URL/templates`             |
| `{WB_URL}`        | root URL of the WBCE install   |
| `{ADMIN_URL}`     | URL of the admin area          |
| `{MEDIA_URL}`     | URL of the media directory     |

Benefit: no hard-coded `WB_URL . '/modules/...'`, no `TEMPLATE_DIR`
concatenations. Install-independent and testable.

### Registering your own tokens

```php
I::addUrlToken('{MYMOD}', WB_URL . '/modules/my_module');

insertCssFile('{MYMOD}/assets/backend.css');
insertJsFile('{MYMOD}/assets/backend.js');
```

Best done in a module's `index.php` or `initialize_fe.php` — register once, use
everywhere.

---

## insertCssCode() / insertJsCode()

Write inline code straight into the head or body — without a `<style>` or
`<script>` wrapper, the queue adds that itself.

```php
insertCssCode(':root { --primary: #3a7bd5; --gap: 1.5rem; }');
insertJsCode('window.SITE_LANG = "' . LANGUAGE . '";');
```

**HTML output:**

```html
<style>
:root { --primary: #3a7bd5; --gap: 1.5rem; }
</style>

<script>
window.SITE_LANG = "EN";
</script>
```

With a position — CSS variables as early as possible, so they're there when
parsing starts:

```php
insertCssCode(':root { --primary: #3a7bd5 }', 'head_early');
```

With an ID — prevents duplicate output when the same snippet could be inserted
from several places:

```php
insertCssCode('.sr-only { position: absolute; … }', 'head_late', 'sr-only-helper');
```

---

## insertHtmlCode()

Inject arbitrary HTML blocks at a queue position.

```php
// Preconnect hint
insertHtmlCode('<link rel="preconnect" href="https://api.example.com">', 'head_early');

// Structured data (JSON-LD)
insertHtmlCode('<script type="application/ld+json">' . json_encode($schema) . '</script>', 'head_late');

// No-JS fallback
insertHtmlCode('<noscript><style>.js-only { display:none }</style></noscript>', 'head_late');
```

**HTML output (structured-data example):**

```html
<script type="application/ld+json">{"@context":"https://schema.org","@type":"Article",...}</script>
```

---

## loadPlugin()

Include a plugin directory with a single line. The `plugin.json` in the directory
declares which files to load and whether there are dependencies.

```php
loadPlugin('include/wbeSelect');
```

**`include/wbeSelect/plugin.json`:**

```json
{
    "css": ["wbeSelect.css"],
    "js":  ["wbeSelect.js"]
}
```

**HTML output:**

```html
<link rel="stylesheet" href="/wbce/include/wbeSelect/wbeSelect.css?1720000000">
<script src="/wbce/include/wbeSelect/wbeSelect.js?1720000000"></script>
```

### With dependencies

```json
{
    "css": ["datepicker.css"],
    "js":  ["datepicker.js"],
    "require": ["include/jquery-slim"]
}
```

`require` entries are loaded first (depth-first); deduplication applies here too
— if `include/jquery-slim` is requested by two plugins, it ends up in the HTML
only once.

### JS files with an explicit position

When a plugin ships both a polyfill (must go in the head) and the main code
(body):

```json
{
    "css": ["wbeSelect.css"],
    "js":  {
        "head_early": ["wbeSelect-polyfill.js"],
        "body_late":  ["wbeSelect.js"]
    }
}
```

### Overriding the position

```php
// CSS in head_early instead of head_late
loadPlugin('include/wbeSelect', 'head_early');

// CSS head_late, JS in head_early
loadPlugin('include/wbeSelect', 'head_late', 'head_early');
```

---

## insertCssBundle() / insertJsBundle()

Combine several files into one cached file — one HTTP request instead of many.

```php
I::insertCssBundle([
    '{TEMPLATE}/styles.css',
    '{TEMPLATE}/cookie-consent.css',
    '{MODULES}/ckeditor/frontend.css',
    '{MODULES}/mod_multilingual/frontend.css',
], 'dolce-piano-main');
```

**What ends up in `<head>`:**

```html
<link rel="stylesheet" href="/wbce/cache/assets/combined_dolce-piano-main.css?1720000000">
```

One request instead of four. The combined file is built on the first call and
served from the cache after that. When a source file changes, the cache is
invalidated automatically (via an mtime comparison).

### Why bundle module CSS too?

Modules like `ckeditor` or `mod_multilingual` put their `frontend.css` in the
module folder and register it via `register_frontend_modfiles('css')`. That goes
through the queue, but as a single file. Pulling those files into the bundle puts
them under your control — minification, order and bundling included.

### Important: order

The bundle must be registered **before** `register_frontend_modfiles('css')`.
The queue recognises already-bundled files and skips them during the individual
registration — but only if the bundle came first.

```php
// Correct ✓
I::insertCssBundle([
    '{TEMPLATE}/styles.css',
    '{MODULES}/ckeditor/frontend.css',
], 'my-bundle');
register_frontend_modfiles('css');   // ckeditor is skipped — already in the bundle

// Wrong ✗
register_frontend_modfiles('css');   // ckeditor lands in the queue individually
I::insertCssBundle([
    '{TEMPLATE}/styles.css',
    '{MODULES}/ckeditor/frontend.css',  // too late — ckeditor is already in
], 'my-bundle');
// Result: ckeditor appears twice
```

### JS bundle

```php
I::insertJsBundle([
    '{TEMPLATE}/vendor/alpine.js',
    '{TEMPLATE}/js/main.js',
    '{TEMPLATE}/js/cookieconsent.js',
], 'dolce-piano-scripts');
```

**What ends up before `</body>`:**

```html
<script src="/wbce/cache/assets/combined_dolce-piano-scripts.js?1720000000"></script>
```

### With a position

```php
// Bundle explicitly into the head (e.g. for critical JS)
I::insertJsBundle(['{TEMPLATE}/js/critical.js'], 'critical', 'head_early');
```

### Remote files in a bundle

CDN URLs are filtered out automatically and loaded individually after the bundle
tag:

```php
I::insertCssBundle([
    '{TEMPLATE}/styles.css',
    'https://cdn.example.com/lib.css',   // loaded individually, not bundled
], 'my-bundle');
```

**HTML output:**

```html
<link rel="stylesheet" href="/wbce/cache/assets/combined_my-bundle.css?…">
<link rel="stylesheet" href="https://cdn.example.com/lib.css">
```

---

## Recipe: bundle everything from the template

A template can combine all of the page's CSS and JS files into one request each —
its own template files together with the `frontend.css` / `frontend.js` files of
selected modules. The right place for this is
**`templates/my_template/index.php`**, right at the top, before the template
outputs any HTML.

### CSS

```php
<?php
// templates/my_template/index.php

I::insertCssBundle([
    // 1. Template's own files — in the desired cascade order
    '{TEMPLATE}/css/reset.css',
    '{TEMPLATE}/css/base.css',
    '{TEMPLATE}/css/layout.css',
    '{TEMPLATE}/css/components.css',

    // 2. Module styles the template knows about and wants to bundle
    '{MODULES}/news_img/assets/frontend.css',
    '{MODULES}/topics/assets/frontend.css',
], 'my-template-css');
```

When `register_frontend_modfiles()` later walks the active modules, it
recognises the already-bundled files and skips them. Modules **not** in the
bundle are loaded individually as usual — you only list the ones you want to
control yourself.

> **Which file to bundle — `frontend.css` or `frontend_custom.css`?** The bundle
> contains exactly the file you name. If a module ships a `frontend_custom.css`
> and you bundle `frontend.css`, those are **two different files** and both get
> loaded. When in doubt, list the custom file. `frontend_override.css` is always
> loaded additionally after the bundle — that's by design.

### JavaScript — two strategies for jQuery

**Strategy A — jQuery from a CDN (recommended for public sites).** jQuery loads
individually and synchronously in the head; the browser often already has it
cached. Your own bundle comes after it with `defer` and may assume `$` exists.

```php
I::insertJsFile(
    'https://code.jquery.com/jquery-3.7.1.min.js',
    'head_late',
    ['integrity' => 'sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=',
     'crossorigin' => 'anonymous']
);

I::insertJsBundle([
    '{TEMPLATE}/js/navigation.js',
    '{TEMPLATE}/js/slider.js',
    '{MODULES}/news_img/assets/frontend.js',
], 'my-template-js');
```

**Strategy B — jQuery local, in the bundle (intranet, offline).** jQuery goes in
as the **first** file, then the plugins, then your own code.

```php
I::insertJsBundle([
    '{TEMPLATE}/js/vendor/jquery-3.7.1.min.js',   // must come first
    '{TEMPLATE}/js/vendor/jquery.easing.min.js',
    '{TEMPLATE}/js/navigation.js',
    '{MODULES}/news_img/assets/frontend.js',
], 'my-template-js');
```

| Criterion               | A — CDN                           | B — Local                |
| ----------------------- | --------------------------------- | ------------------------ |
| jQuery in browser cache | yes (CDN, high hit rate)          | no (own domain)          |
| external request        | yes                               | no                       |
| bundle size             | smaller (no jQuery)               | larger (jQuery included) |
| load order              | explicit (head sync + body defer) | implicit (array order)   |
| recommended for         | public sites                      | intranet / offline       |

> **Order matters:** the bundle must be registered **before**
> `register_frontend_modfiles()`, otherwise a module file lands in the queue
> individually before the bundle can "take it over" — and then appears twice.

---

## insertWebFont() / insertFont()

Web fonts from a CDN are cached locally and included in a GDPR-compliant way.

```php
// Google Fonts, Bunny Fonts, Fontshare, … — one call
insertWebFont('https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap');

// Local .woff2 file
insertFont('{TEMPLATE}/fonts/MyFont.woff2', ['family' => 'MyFont', 'weight' => '400']);
```

→ Full tutorial: the [**Web fonts**](?tool=asset_optimizer&tab=docs&doc=fonts) (`FONTS_TUTORIAL.md`) tab

---

## Twig functions

The following functions are available in a Twig template:

| Twig call                                        | Equivalent PHP                    |
| ------------------------------------------------ | --------------------------------- |
| `{{ loadPlugin('include/wbeSelect') }}`          | `loadPlugin(...)`                 |
| `{{ insertCssFile(TEMPLATE_DIR ~ '/x.css') }}`   | `insertCssFile(...)`              |
| `{{ insertJsFile(INCLUDE_URL ~ '/x.js') }}`      | `insertJsFile(...)`               |
| `{{ insertCssCode('.foo { color:red }') }}`      | `insertCssCode(...)`              |
| `{{ insertJsCode('window.x = 1') }}`             | `insertJsCode(...)`               |
| `{{ insertHtmlCode('<noscript>…</noscript>') }}` | `insertHtmlCode(...)`             |
| `{{ insertFile(url, pos, type) }}`               | `insertCssFile` or `insertJsFile` |
| `{{ insertTitle('My page') }}`                   | `I::insertTitle(...)`             |
| `{{ insertMeta('description', 'My text') }}`     | `I::insertMeta(...)`              |

**Not available in Twig:**

| Function          | Reason                                                          |
| ----------------- | --------------------------------------------------------------- |
| `insertCssBundle` | must be registered before the template render (order guarantee) |
| `insertJsBundle`  | like `insertCssBundle`                                          |
| `insertWebFont`   | triggers I/O — belongs in the bootstrap, not the view layer     |
| `insertFont`      | like `insertWebFont`                                            |
| `I::addUrlToken`  | infrastructure setup, belongs in the bootstrap                  |

Always register bundles and fonts in `initialize_fe.php` or the template's
`index.php`, before Twig renders.

---

# Reference

## Position quick overview

Every call accepts an optional position. Without one: CSS goes to `head_late`,
JS to `body_late`.

| Position      | Anchor in the document | Typical content                                     |
| ------------- | ---------------------- | --------------------------------------------------- |
| `head_top`    | right after `<head>`   | charset, viewport                                   |
| `head_early`  | right after `</title>` | critical CSS, CSS variables, preloads, fonts        |
| `head_middle` | before `</head>`       | meta tags, OG tags                                  |
| `head_late`   | before `</head>`       | module CSS, plugin CSS — **default for CSS**        |
| `head_last`   | before `</head>`       | override CSS that should come after everything      |
| `body_top`    | right after `<body>`   | very first thing in the body                        |
| `body_early`  | right after `<body>`   | feature detection, early-running JS                 |
| `body_late`   | before `</body>`       | module JS, plugin JS — **default for JS** (`defer`) |
| `body_last`   | before `</body>`       | analytics, tracking — always last                   |

**Shorthands:** `head` → `head_late`, `body` → `body_late`, `early` →
`head_early`, `middle` → `head_middle`.

→ The full position table with the ordering rules and **all the legacy names
from WBCE 1.x** (`HEAD BTM+`, `BODY MODFILES`, …) is in the
[**Full reference**](?tool=asset_optimizer&tab=docs&doc=ref) (`ASSETS_REFERENCE.md`)
tab, section 8.

---

## Appendix: how it works internally (you can skip this)

You don't need this for day-to-day work — it just explains what the queue does
behind the scenes.

### What happens during bundling

1. `I::insertCssBundle(['a.css', 'b.css'], 'my-bundle')` is called.
2. The queue resolves the tokens and determines the absolute paths of the source
   files.
3. It checks whether `cache/assets/combined_my-bundle.css` exists and whether all
   source files are unchanged (comparing modification time, `mtime`).
4. If stale or missing: the files are loaded, optionally minified (with
   `MatthiasMullie\Minify` if present), concatenated and written atomically into
   `cache/assets/`.
5. The source files are noted internally as "already seen" — later
   `insertCssFile()` calls for the same files are silently ignored.
6. The bundle URL is added to the queue and rendered as a single `<link>` tag.

**Minification without a bundle:** individual `insertCssFile()` /
`insertJsFile()` calls are minified too when `MINIFY_CSS` / `MINIFY_JS` is
active. The minified version lands in `cache/assets/` with a readable name (e.g.
`wbcetik-css-main.min.css`).

### Cache busting in detail

When `OPF_ASSETS_CACHE_BUSTING` is active, the queue automatically appends
`?{mtime}` to every local file URL:

```html
<link rel="stylesheet" href="/wbce/templates/mytheme/styles.css?1720000000">
```

The number is the file's `filemtime()` on the server. After a change it changes —
browsers and CDNs discard their old copy automatically. No more hand-written
`?v=2`. External URLs (CDN) get no `?mtime` — the server has no file access
there.

### Deduplication in detail

The queue keeps two lookup lists:

- one for the exact same URL string (token notation)
- one for the resolved absolute file path

The second catches cases like this:

```php
I::insertCssFile('{MODULES}/ckeditor/frontend.css');
// … elsewhere in the code:
I::insertCssFile(WB_URL . '/modules/ckeditor/frontend.css');  // same file, different URL
```

Both point to the same absolute path — the second entry is skipped. This also
applies to bundle sources: once you've put a file in a bundle, you can safely
register it again as an individual file — it still appears only once in the HTML.
