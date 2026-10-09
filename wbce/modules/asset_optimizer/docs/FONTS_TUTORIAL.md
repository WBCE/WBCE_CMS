# FontCache — Tutorial

This tutorial shows how `insertWebFont()` and `insertFont()` are used, and what
they actually write into the HTML output.

> **Who is this for?** People who build templates or modules. The Asset
> Optimizer tool in the back end has no switch for fonts — fonts are always
> included in template code. If you only want to know what the *Cache & status*
> tab shows under "Self-hosted web fonts": that's the cache the calls described
> here fill; "Clear font cache" is safe at any time.

---

## Terms in one sentence

| Term                 | Meaning                                                                                                                             |
| -------------------- | ----------------------------------------------------------------------------------------------------------------------------------- |
| Web font             | A typeface loaded as a file with the page instead of coming from the device.                                                        |
| `.woff2`             | The compact, heavily-compressed file format for web fonts in common use today.                                                      |
| `@font-face`         | The CSS rule that gives a font a name and says which file belongs to it.                                                            |
| Google Fonts problem | Loading Google Fonts straight from Google's server sends every visitor's IP to Google (USA) — legally risky without consent (GDPR). |
| FOUT                 | "Flash of Unstyled Text" — the brief moment where text shows in the fallback font until the web font arrives.                       |
| Variable font        | A single file containing every weight of a family (instead of one file per weight).                                                 |
| Alias                | A name you choose for a font so the template CSS stays the same no matter which font is behind it.                                  |

`FontCache` downloads web fonts once to your own server, rewrites the
`@font-face` CSS to local paths, and then serves everything from your own domain
— that solves the Google Fonts problem and the external dependency in one go.

---

## The problem with CDN web fonts

The usual approach in templates looks like this:

```html
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
```

Or in PHP:

```php
echo '<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">';
```

That works — but it has three problems:

**1. GDPR.** On the first page load the browser opens a connection to
`fonts.googleapis.com`. The visitor's IP address is sent to Google servers in
the USA. Without explicit consent that is legally problematic.

**2. External dependency.** If the CDN is unreachable (outage, firewall, slow
connection), the font doesn't load — and the page shows in the fallback font.

**3. No queue system.** An `echo` lands somewhere in the template output,
uncontrolled. WBCE's queue system (`I::`) cannot capture, sort or de-duplicate
that tag.

---

## insertWebFont() — font from a CDN, cached locally

### Simplest usage

```php
insertWebFont('https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap');
```

**What happens on the first page load:**

1. `FontCache` downloads the CSS file from Google Fonts
2. FontCache parses each `@font-face` block individually — `font-family` and `font-weight` are read out
3. Each referenced `.woff2` file is downloaded and stored under a **stable, readable name**
4. Every `url(https://fonts.gstatic.com/…)` in the CSS is rewritten to a local path
5. CSS + font files are stored atomically in `cache/fonts/`

**What is written into `<head>` after that (and on every further call):**

```html
<link rel="stylesheet" href="/cache/fonts/a3f8b2c1d4e5f6a7b8c9d0e1.css">
```

The cached CSS file contains:

```css
/* @font-face blocks with local paths instead of Google URLs */
@font-face {
  font-family: 'Inter';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: url(/cache/fonts/Inter_400.woff2) format('woff2');
}
@font-face {
  font-family: 'Inter';
  font-style: normal;
  font-weight: 700;
  font-display: swap;
  src: url(/cache/fonts/Inter_700.woff2) format('woff2');
}
```

The font files are named `Inter_400.woff2` and `Inter_700.woff2` — derived
directly from `font-family` and `font-weight` in each `@font-face` block, **with
no hash suffix**. The name is the same and predictable on every install.

No request goes to Google any more. All font files sit on your own server.

> **Tip: spotting the source font behind an alias.**
> If a template uses `'MainFont'` as an alias, you can see which font is
> actually behind it in the browser DevTools (*Network* or *Sources* tab) or
> directly in the `cache/fonts/` directory — without opening the template:

```css
font-family: 'MainFont';
src: url(/cache/fonts/Inter_400.woff2)  /*  source is Inter, weight 400 */
```

> That makes debugging easier and dependencies transparent — even for someone
> who doesn't know the template.

---

### With a font alias

An alias has two effects:

1. **CSS filename** — the cached CSS file is named `{alias}.css` instead of a
   hash. Predictable, directly referenceable from `editor.css` or other static
   files.
2. **font-family in the output** — only the alias name appears; the original
   name (`Inter`) is suppressed. The rename happens directly in the CSS file —
   no separate `<style>` block in the HTML.

> **Important — one alias, one family:**
> An alias renames **all** `@font-face` blocks in the CSS file.
> With a URL for multiple families (`family=Anton&family=Poppins`) both would get
> the same alias name and could no longer be told apart.
> **Rule of thumb: one separate `insertWebFont()` call per font family.**
> See [Multiple families with an alias](#multiple-families-with-an-alias).

```php
insertWebFont(
    'https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap',
    'mainfont'
);
```

**What sits in `cache/fonts/`:**

```file-tree
cache/
    fonts/
        mainfont.css     ← CSS with local paths and an already-renamed font-family
        Inter_400.woff2
        Inter_700.woff2
```

**What `mainfont.css` contains:**

```css
@font-face {
  font-family: 'mainfont';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: url(/cache/fonts/Inter_400.woff2) format('woff2');
}
@font-face {
  font-family: 'mainfont';
  font-style: normal;
  font-weight: 700;
  font-display: swap;
  src: url(/cache/fonts/Inter_700.woff2) format('woff2');
}
```

**What is written into `<head>`:**

```html
<link rel="stylesheet" href="/cache/fonts/mainfont.css">
```

No separate `<style>` block. The CSS file contains the renamed declarations
directly. The cached font files are the same as without an alias — the browser
loads them only once. The template CSS can now always say `'mainfont'`,
whichever font is actually behind it.

**Alias convention:** lowercase, spaces and special characters become
underscores. `"Template Script"` → `template_script` → `template_script.css`.
Common short forms like `inter`, `mainfont`, `heading_font` read best.

**Switching fonts — without touching the template CSS:**

```php
// Before: Inter
insertWebFont('https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap', 'mainfont');

// After: Satoshi — change this line only
insertWebFont('https://fonts.bunny.net/css?family=satoshi:400,700&display=swap', 'mainfont');
```

The font files are now named `Satoshi_400.woff2` and `Satoshi_700.woff2`, the
CSS file stays `mainfont.css`. The template CSS is unchanged:

```css
body { font-family: 'mainfont', sans-serif; }
```

---

### editor.css — font in the TinyMCE back end

TinyMCE renders its content in an `<iframe>`. To show the same typeface there as
on the website, `editor.css` (the template's static stylesheet file) has to load
the font itself.

With an alias, the CSS filename in the cache is **predictable** — you can
reference it directly in `editor.css`:

```php
// Template index.php:
insertWebFont(
    'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap',
    'inter'
);
```

```css
/* templates/mytheme/editor.css */
@import url('/cache/fonts/inter.css');

body {
    font-family: 'inter', sans-serif;
    font-size: 16px;
    line-height: 1.6;
    color: #1a1a1a;
}
```

`/cache/fonts/inter.css` contains the `@font-face` blocks with local paths and
is always present after the first front-end request. TinyMCE thus loads the font
straight from your own server — no external dependency, GDPR-compliant.

> **Note:** if the cached CSS file doesn't exist yet (very first page load, or
> after a cache clear), the font briefly shows in the editor as the fallback
> face. On the next load the file is there. That's not a bug — the `@import`
> simply points to nothing while the target file is missing.

---

### Including font CSS in a bundle

To save an HTTP request, you can pull the cached font CSS into a combined
stylesheet bundle. The `{CACHE}` token is available for this — it resolves to
`WB_URL . '/cache'`, like `{TEMPLATE}` and `{MODULES}`:

```php
I::insertCssBundle([
    '{CACHE}/fonts/inter.css',
    '{TEMPLATE}/assets/css/main.css',
], 'mytheme');
```

`markCombinedSources()` automatically removes any individual `<link>` on
`inter.css` that may already be queued — so there is no double include, no
matter what order the template and modules register their assets in.

> **Trade-off:** if `inter.css` is part of a bundle, it still exists as a
> stand-alone file in the `cache/fonts/` directory — `editor.css` can still
> `@import` it. But anyone who works **exclusively** through the bundle and
> never puts `@import url('/cache/fonts/inter.css')` in `editor.css` has no font
> in the TinyMCE back end. The two paths don't exclude each other — the file
> stays on disk, the browser just combines it once.

---

### Both names at once

When both the original name and the alias should be available (e.g. because a
module uses `'Inter'` hard-coded):

```php
// First call: original name 'Inter' available (no alias → hash-based CSS file)
insertWebFont('https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap');

// Second call: alias 'inter' available
// No second download — FontCache recognises the URL and uses the cache
insertWebFont('https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap', 'inter');
```

**What is written into `<head>`:**

```html
<!-- First call: <link> to the hash-named cached CSS file (font-family: 'Inter') -->
<link rel="stylesheet" href="/cache/fonts/a3f8b2c1d4e5f6a7b8c9d0e1.css">

<!-- Second call: <link> to inter.css (font-family: 'inter') — no <style> block -->
<link rel="stylesheet" href="/cache/fonts/inter.css">
```

---

### Multiple families with an alias

When a template uses several font families with aliases — e.g. a sans-serif for
body text and a display face for headings — each family needs its own
`insertWebFont()` call with its own alias:

```php
// CORRECT — one call per family
insertWebFont(
    'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,700;1,400;1,700&display=swap',
    'poppins'
);
insertWebFont(
    'https://fonts.googleapis.com/css2?family=Anton&display=swap',
    'anton'
);
```

Result in `cache/fonts/`:

```file-tree
cache/
    fonts/
        poppins.css               ← font-family: 'poppins'
        anton.css                 ← font-family: 'anton'
        Poppins_400.woff2
        Poppins_400_italic.woff2
        Poppins_700.woff2
        Poppins_700_italic.woff2
        Anton_400.woff2
```

```css
/* editor.css */
@import url('../../cache/fonts/poppins.css');
@import url('../../cache/fonts/anton.css');

h1, h2, h3 { font-family: 'anton', sans-serif; }
body        { font-family: 'poppins', sans-serif; }
```

> **Why not one URL with multiple families?**
> Google Fonts allows `family=Anton&family=Poppins` in one URL — that's
> convenient but doesn't work with an alias. FontCache would rename both
> families to the same alias name, so Anton and Poppins could no longer be told
> apart in the CSS. A URL with multiple families only makes sense without an
> alias (then the filename is hashed).

---

### Bunny Fonts — same method, different host

Bunny Fonts is a GDPR-compliant drop-in replacement for Google Fonts (European
CDN, no IP logging). The method is identical:

```php
insertWebFont('https://fonts.bunny.net/css?family=inter:400,700&display=swap', 'MainFont');
```

`FontCache` detects `fonts.bunny.net` automatically and uses the right
User-Agent. The cached files are named `Inter_400.woff2` here too — derived from
the `font-family` value in the returned CSS, not from the URL.

---

### Variable font from a CDN

```php
insertWebFont('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap', 'MainFont');
```

For variable fonts Google returns an `@font-face` block with
`font-weight: 100 900`. The cached file is named accordingly:

```file-tree
cache/
    fonts/
        Inter_100_900.woff2
```

---

### Admin force-refresh

As a logged-in admin: **CTRL+F5** on the front-end page clears the cache for that
font and re-downloads it on the next request. Useful after a URL change (e.g. a
new weight added).

Or in code (clears the entire font cache):

```php
I::clearFontCache();
```

After a cache clear, every file is re-downloaded on the next call — with the same
readable names.

---

## Supported font providers

Every provider whose CSS URL can be passed to `insertWebFont()`. The code call is
the same for all of them — only the URL differs.

| Provider           | Link                           | Notes                                                                                            |
| ------------------ | ------------------------------ | ------------------------------------------------------------------------------------------------ |
| **Google Fonts**   | https://fonts.google.com       | UA-sensitive; GDPR problem without a local cache → FontCache solves it                           |
| **Bunny Fonts**    | https://fonts.bunny.net        | drop-in replacement for Google Fonts; EU CDN, no IP logging; UA-sensitive                        |
| **Fontshare**      | https://www.fontshare.com      | Indian Type Foundry; high-quality free fonts; no UA trick needed                                 |
| **Fontsource**     | https://fontsource.org         | all Google Fonts + many more, packaged via jsDelivr; version-pinned                              |
| **Font Awesome**   | https://fontawesome.com        | icon font; technically identical to text fonts — same `insertWebFont()` method                   |
| **Material Icons** | https://fonts.google.com/icons | runs over the same Google Fonts CDN; UA-sensitive like Google Fonts                              |
| **Adobe Fonts**    | https://fonts.adobe.com        | commercial (Creative Cloud); no local download possible — use `insertCssFile()` directly instead |

### Fontsource — URL scheme

Fontsource packages are included via jsDelivr. There are two variants:

```php
// All weights at once (index.css)
insertWebFont('https://cdn.jsdelivr.net/npm/@fontsource/inter@5.0/index.css', 'MainFont');

// Only a specific weight (smaller CSS file)
insertWebFont('https://cdn.jsdelivr.net/npm/@fontsource/inter@5.0/400.css', 'MainFont');

// Variable font (one file for all weights)
insertWebFont('https://cdn.jsdelivr.net/npm/@fontsource-variable/inter@5.0/index.css', 'MainFont');
```

The version number (`@5.0`) pins the font to a specific state — the font never
changes unexpectedly, even when the provider ships updates.

### Fontshare — URL scheme

```php
// One family
insertWebFont('https://api.fontshare.com/v2/css?f[]=satoshi@400,700&display=swap', 'MainFont');

// Multiple families in one request
insertWebFont(
    'https://api.fontshare.com/v2/css?f[]=satoshi@400,700&f[]=cabinet-grotesk@500&display=swap',
    'MainFont'
);
```

Well-known Fontshare fonts: Satoshi, Cabinet Grotesk, Clash Display, General
Sans, Switzer, Chillax.

---

## insertFont() — direct font files

When font files sit directly in the template or module (no external CSS source),
`insertFont()` generates the `@font-face` CSS automatically.

### Simplest form — @font-face with a family

```php
insertFont('{TEMPLATE}/fonts/inter.woff2', [
    'family'  => 'MainFont',
    'weight'  => '400',
    'display' => 'swap',
]);
```

**What is written into `<head>`:**

```html
<style>
@font-face {
  font-family: 'MainFont';
  font-weight: 400;
  font-style: normal;
  font-display: swap;
  src: url(/templates/mytheme/fonts/inter.woff2) format('woff2');
}
</style>
```

The `{TEMPLATE}` token is resolved to the correct path automatically. Cache
busting (`?mtime`) is appended automatically when `OPF_ASSETS_CACHE_BUSTING` is
active.

---

### Multiple weights

```php
insertFont('{TEMPLATE}/fonts/inter-400.woff2', ['family' => 'MainFont', 'weight' => '400']);
insertFont('{TEMPLATE}/fonts/inter-700.woff2', ['family' => 'MainFont', 'weight' => '700']);
```

**What is written into `<head>`:**

```html
<style>
@font-face {
  font-family: 'MainFont';
  font-weight: 400;
  font-style: normal;
  font-display: swap;
  src: url(/templates/mytheme/fonts/inter-400.woff2) format('woff2');
}
</style>
<style>
@font-face {
  font-family: 'MainFont';
  font-weight: 700;
  font-style: normal;
  font-display: swap;
  src: url(/templates/mytheme/fonts/inter-700.woff2) format('woff2');
}
</style>
```

---

### Multiple formats (woff2 + woff fallback)

```php
insertFont(
    [
        '{TEMPLATE}/fonts/inter.woff2' => 'woff2',
        '{TEMPLATE}/fonts/inter.woff'  => 'woff',
    ],
    ['family' => 'MainFont', 'weight' => '400']
);
```

**What is written into `<head>`:**

```html
<style>
@font-face {
  font-family: 'MainFont';
  font-weight: 400;
  font-style: normal;
  font-display: swap;
  src: url(/templates/mytheme/fonts/inter.woff2) format('woff2'),
       url(/templates/mytheme/fonts/inter.woff) format('woff');
}
</style>
```

---

### Variable font

A variable font covers every weight with a single file:

```php
insertFont('{TEMPLATE}/fonts/inter-variable.woff2', [
    'family'  => 'MainFont',
    'weight'  => '100 900',   // range instead of a single value
    'display' => 'swap',
]);
```

**What is written into `<head>`:**

```html
<style>
@font-face {
  font-family: 'MainFont';
  font-weight: 100 900;
  font-style: normal;
  font-display: swap;
  src: url(/templates/mytheme/fonts/inter-variable.woff2) format('woff2');
}
</style>
```

---

### Preload only — without @font-face

When the font is included via a separate CSS file but the browser should still
preload it immediately (to avoid FOUT):

```php
insertFont('{TEMPLATE}/fonts/inter-400.woff2');
// No 'family' → no @font-face, just a preload hint
```

**What is written into `<head>`:**

```html
<link rel="preload" href="/templates/mytheme/fonts/inter-400.woff2"
      as="font" type="font/woff2" crossorigin>
```

---

### Preload + @font-face combined

```php
insertFont('{TEMPLATE}/fonts/inter-400.woff2', [
    'family'  => 'MainFont',
    'weight'  => '400',
    'preload' => true,
]);
```

**What is written into `<head>`:**

```html
<link rel="preload" href="/templates/mytheme/fonts/inter-400.woff2"
      as="font" type="font/woff2" crossorigin>
<style>
@font-face {
  font-family: 'MainFont';
  font-weight: 400;
  font-style: normal;
  font-display: swap;
  src: url(/templates/mytheme/fonts/inter-400.woff2) format('woff2');
}
</style>
```

---

## Typical template setup

```php
// functions.php or template_preprocess in the template:

// Main typeface from a CDN, cached locally, available as 'inter'
insertWebFont(
    'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap',
    'inter'
);

// Monospace typeface straight from the template
insertFont('{TEMPLATE}/fonts/fira-code.woff2', [
    'family'  => 'mono',
    'weight'  => '400',
    'display' => 'swap',
]);

// Optional: font CSS + template CSS in a single HTTP request
// {CACHE} resolves to WB_URL . '/cache'
I::insertCssBundle([
    '{CACHE}/fonts/inter.css',
    '{TEMPLATE}/assets/css/main.css',
], 'mytheme');
```

```css
/* style.css in the template — never change, whichever font is behind it */
body         { font-family: 'inter', system-ui, sans-serif; }
code, pre    { font-family: 'mono', monospace; }
```

```css
/* editor.css in the template — TinyMCE back end.
   Static CSS file: the {CACHE} token does not work here.
   The path is entered directly. */
@import url('/cache/fonts/inter.css');

body { font-family: 'inter', system-ui, sans-serif; }
```

After the first page load, `cache/fonts/` holds:

```file-tree
cache/
    fonts/
        inter.css          ← font-family: 'inter', referenceable directly from editor.css
        Inter_400.woff2
        Inter_600.woff2
        Inter_700.woff2
```

---

## Appendix: how FontCache works internally (you can skip this)

Everything from here explains the mechanics behind the scenes — the filename
scheme, the User-Agent trick, alias generation. For day-to-day work, the part
above is enough.

### Download and filename

`FontCache` is created lazily by `AssetQueue` — only when `insertWebFont()` is
actually called. All font files land in `WB_PATH/cache/fonts/`.

During the download, FontCache processes the CSS **block by block**: for each
`@font-face` block it reads `font-family` and `font-weight` and builds the
filename from them:

```
font-family: 'Inter', font-weight: 400, font-style: normal  →  Inter_400.woff2
font-family: 'Inter', font-weight: 400, font-style: italic  →  Inter_400_italic.woff2
font-family: 'Inter', font-weight: 700, font-style: normal  →  Inter_700.woff2
font-family: 'Inter', font-weight: 700, font-style: italic  →  Inter_700_italic.woff2
font-family: 'Inter', font-weight: 100 900                  →  Inter_100_900.woff2   (variable font)
```

The names are **stable and predictable** — the same on every install.
`font-style: normal` gets no suffix; `italic` and `oblique` are appended. When
the same filename results from several `@font-face` blocks (e.g. Google Fonts
returns several Unicode-range subsets for Inter 400 normal), only the first block
is downloaded; all the others reference the same file. For Western European
sites (Latin subset) that is correct; TinyMCE only needs the Latin subset
anyway.

### Cache key, CSS filename and deduplication

The cache key for the CSS file depends on whether an alias was passed:

| Call                                     | CSS filename                                               |
| ---------------------------------------- | ---------------------------------------------------------- |
| `insertWebFont($url)`                    | `md5(url + format) + ".css"` — internal, not referenceable |
| `insertWebFont($url, 'inter')`           | `inter.css` — stable, directly referenceable               |
| `insertWebFont($url, 'Template Script')` | `template_script.css`                                      |

Alias sanitisation: lowercase, every run of non-alphanumerics → a single
underscore, leading/trailing underscores removed. The result is always a valid
filename.

### URL tokens in PHP calls

In PHP functions (`insertCssBundle()`, `insertFont()`, `insertCssFile()`) WBCE
URL tokens are resolved. For the font cache, `{CACHE}` in particular is relevant:

| Token        | Resolves to                |
| ------------ | -------------------------- |
| `{CACHE}`    | `WB_URL . '/cache'`        |
| `{TEMPLATE}` | URL of the active template |
| `{MODULES}`  | `WB_URL . '/modules'`      |

In **static CSS files** (e.g. `editor.css`) the browser doesn't know these
tokens — there the path has to be entered directly:

```css
/* editor.css — direct, no token */
@import url('/cache/fonts/inter.css');

/* PHP — token is resolved by AssetQueue */
I::insertCssBundle(['{CACHE}/fonts/inter.css', ...], 'bundle');
```

The same URL with the same format is never downloaded twice — every further call
hits the cache immediately.

Atomic writes prevent a concurrent request from reading a half-written file:
write to `file.tmp.{PID}` first, then rename.

### User-Agent trick (Google Fonts, Bunny Fonts)

Google Fonts and Bunny Fonts inspect the HTTP User-Agent to decide which format
to return in the CSS. Without a UA they return ttf instead of woff2.

`FontCache` sends a format-specific UA:

| Format          | User-Agent                             |
| --------------- | -------------------------------------- |
| `woff2`         | Firefox 40 UA (returns woff2)          |
| `woff2-unicode` | Chrome 104 UA (Unicode ranges + woff2) |
| `woff`          | Firefox 27 UA                          |
| `ttf`           | Safari/Daum UA                         |

All other providers (Fontshare, Fontsource, …) get a neutral `WBCE-CMS` UA —
they return woff2 directly without UA steering.

### Alias generation

`buildAliasCss()` parses the cached CSS file for `@font-face` blocks and creates
a copy in which every `font-family` property is replaced by the alias name. The
`src` URLs — and thus filenames like `Inter_400.woff2` — stay identical. The
browser therefore loads each font file only once, even when it's registered
under two names.

### Position in the queue system

All font entries land in `head_early` — the earliest possible point in `<head>`,
before regular CSS files. That minimises FOUT (Flash of Unstyled Text), because
the browser sees the font declaration as early as possible.
