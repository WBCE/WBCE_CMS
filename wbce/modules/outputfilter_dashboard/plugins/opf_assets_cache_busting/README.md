
# OpF Filter: Assets Cache Busting

## formerly known as CacheControl

This filter takes care to always load the latest version of a CSS or JS file from the server.

**This means:**
> as soon as you make any changes to the files (CSS, JS) the browser will request the new version from the server instead of presenting a version from the browser's cache.

Without Assets Cache Busting:
````php
../css/file.css
````

Will result in:
````php
../css/file.css?1677324972
````
when Assets Cache Busting is turned on.

**Two mechanisms, one switch:**
1. Turning this filter on also defines the `OPF_ASSETS_CACHE_BUSTING` constant (via WBCE's
   `Settings`-to-constant bootstrap), which tells `AssetQueue` (`I::`) to append `?<mtime>`
   to every CSS/JS URL it manages itself.
2. The filter's own code additionally scans the fully rendered page for CSS/JS
   `href`/`src` references that were **not** queued through AssetQueue (e.g. hand-written
   `<link>`/`<script>` tags in section content or legacy templates) and busts those too.
   URLs that already carry a `?<mtime>` (from either mechanism) are left untouched.

Registered at `OPF_TYPE_PAGE_FINAL`, positioned after "Remove System PH", so it always
runs against the final, fully assembled page content.

## This Plugin was formerly known as CacheControl
A similar OutputFilter Plugin named `CacheControl` already existed before but had to be 
renamed and improved in order to align more closely to the AssetQueue which was introduced
with WBCE CMS 1.7.0
