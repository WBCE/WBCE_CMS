# Asset Optimizer

**Manage WBCE's asset pipeline from the backend — minification, cache-busting,
and a look inside the asset cache.**

Asset Optimizer is a thin admin tool over
[`framework/Assets/AssetQueue.php`](../../framework/Assets/AssetQueue.php), the
queue that collects every stylesheet, script, font and meta tag on a page and
injects it at the right place in the HTML. The queue is configured entirely
through PHP constants; this tool writes those constants to the right place and
shows what the cache currently holds.

Open it from **Admin-Tools → Asset Optimizer**.

---

## Settings

Two groups of switches, stored in two different places on purpose:

| Setting                       | Stored in                      | Effect                                                                                                                                                      |
| ----------------------------- | ------------------------------ | ----------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `MINIFY_CSS`                  | `var/config_constants.ini.php` | Minify every local stylesheet (bundles and stand-alone files); cache the result. CDN files are never touched.                                               |
| `MINIFY_JS`                   | `var/config_constants.ini.php` | The same for scripts.                                                                                                                                       |
| `MINIFY_USE_SUFFIX`           | `var/config_constants.ini.php` | Keep the `.min` marker in cached filenames. On by default. Changing it rewrites the cache.                                                                  |
| `ASSETS_MINIFY_DEBUG`         | `var/config_constants.ini.php` | **Admin only.** Turn off minification and bundling for the logged-in admin so DevTools shows the original files.                                            |
| `MINIFY_ASSETS_DIR`           | `var/config_constants.ini.php` | Absolute path for the cache directory. Empty = the default `cache/assets/`.                                          |
| `ASSET_QUEUE_DEBUG`           | `var/config_constants.ini.php` | Admin-only `console.error()` block when AssetQueue fails to assemble a page. Own switch; `WBCE_DEBUG` implies it.     |
| `OPF_ASSETS_CACHE_BUSTING`    | `settings` table               | Append `?<mtime>` to every asset URL on the public site.                                                                                                    |
| `OPF_ASSETS_CACHE_BUSTING_BE` | `settings` table               | The same, scoped to the admin backend. Locked on while the front-end switch is on; editable only when it is off.                                            |

The minification switches are genuine config-file constants that nothing else
manages. The cache-busting pair uses the exact `settings`-table rows
(`opf_assets_cache_busting` / `opf_assets_cache_busting_be`) that the **Assets
Cache Busting** filter in *Outputfilter Dashboard* writes — so there is one
value, not two, whichever screen you change it from.

A constant already `define()`d in `config.php` wins over both the ini file and
the settings table. The tool shows those read-only with a notice; move the line
to `var/config_constants.ini.php` to manage it here.

`ASSET_QUEUE_DEBUG` only governs AssetQueue's own console diagnostics — it is
deliberately separate from the global `WBCE_DEBUG` (Errorlog viewer), so you can
watch the asset pipeline without turning on every other debug output. Turning on
`WBCE_DEBUG` still enables it.

---

## Cache & status

* **Byte savings** — per bundle and in total: original size (summed from the
  bundle's source files), minified size, a gzip estimate, and the percentage
  saved.
* **Bundle inspector** — click a `combined_*` row to see the ordered list of
  source files that went into it, each with its size and modification time. A
  source that has since been deleted from disk is struck through in red.
* **Minified files** — the stand-alone (non-bundled) minified copies.
* **Web fonts** — what `cache/fonts/` currently holds (self-hosted fonts fetched
  by `insertWebFont()`), with a total.
* **Clear asset cache** / **Clear font cache** — both safe at any time; the next
  page load rebuilds whatever it needs.
* **Health checks** — cache directories writable, cURL for fonts, constants
  pinned in `config.php`, and (only on a broken install) a missing
  `matthiasmullie/minify`.

The bundle inspector reads a `<bundle>.meta.json` sidecar that `AssetQueue`
writes next to each bundle; bundles built before this module was installed show
no source list until they are next rebuilt.

### Documentation

The third tab renders the `AssetQueue` docs inline in the backend (via
`MarkdownWbce` — `MdReaderHelper::renderForEmbed()`), with sub-tabs:

| Sub-tab             | File                       | Topic                                                         |
| ------------------- | -------------------------- | ------------------------------------------------------------- |
| Guide               | `docs/TOOL_GUIDE.md`       | plain-language walkthrough of this tool — no code             |
| Assets in templates | `docs/ASSETS_TUTORIAL.md`  | how a template/module loads CSS and JS through the queue      |
| Web fonts           | `docs/FONTS_TUTORIAL.md`   | self-hosting fonts with the `insertWebFont()` / FontCache API |
| Full reference      | `docs/ASSETS_REFERENCE.md` | the complete `AssetQueue` reference                           |

`ASSETS_TUTORIAL.md`, `FONTS_TUTORIAL.md` and `ASSETS_REFERENCE.md` were the
`AssetQueue` documentation that used to live in `framework/Assets/`;
`TOOL_GUIDE.md` is new and written for non-technical site owners. Without
`MarkdownWbce` the tab shows the on-disk path instead.

Each doc ships in English (the base `*.md`) and German (a `*_DE.md` sibling);
`MdReaderHelper::renderForEmbed()` serves whichever matches the backend
`LANGUAGE`, falling back to the English base.

---

## Requirements

* WBCE CMS 1.7.x
* PHP 8.1+

`matthiasmullie/minify` (used for minification and CSS `url()` rewriting in
bundles) ships with WBCE core — no separate install.

---

## Credits

**Christian M. Stefan · [www.wbEasy.de](https://www.wbEasy.de)** — module, and
the accompanying `AssetQueue` changes (the `ASSETS_MINIFY_DEBUG` constant name,
backend-scoped cache busting via `OPF_ASSETS_CACHE_BUSTING_BE` — the backend now
follows the front-end switch — the bundle `.meta.json` sidecar, and the
dedicated `ASSET_QUEUE_DEBUG` console-diagnostics constant).

`AssetQueue` itself is part of WBCE CMS 1.7.0.

---

## License

GNU General Public License — <http://www.gnu.org/licenses/gpl.html>
