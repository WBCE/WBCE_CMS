# Asset Optimizer — Guide

This guide explains the **Asset Optimizer** tool in plain language. You do not
need any programming knowledge. If you build templates or modules yourself and
want to call the functions in code, read the
[**Assets in templates**](?tool=asset_optimizer&tab=docs&doc=embed)
(`ASSETS_TUTORIAL.md`) tab above instead.

---

## What does this tool do?

Every website is made of many small helper files: style files that decide how it
looks, and script files that make things move. A browser has to fetch each of
these files from the server one by one. The more files there are, and the bigger
they are, the longer the page takes to build.

The **Asset Optimizer** makes those files smaller (it strips out the whitespace
and comments that are only there for humans) and makes sure visitors get the new
version immediately after a change, instead of a stale one from their browser's
cache.

You flip a few switches here, click **Save settings**, and WBCE does the rest
automatically on every page load.

> **Important:** *combining* many files into one (this is called "bundling") is
> set up by your template, not by this tool. What you turn on here is the
> **shrinking** and the **cache busting**. Both also apply to files that are
> already bundled.

---

## A short glossary

These words come up again and again in the tool:

| Word            | Meaning in one sentence                                                                |
| --------------- | -------------------------------------------------------------------------------------- |
| Asset           | A helper file of the page: a style file, a script file, a font, an image.              |
| CSS             | The language of style files — it decides colours, spacing, font sizes.                 |
| JavaScript (JS) | The language of script files — it makes menus, sliders and form validation work.       |
| minify          | Make a file smaller by removing whitespace and comments. The content stays the same.   |
| Bundle          | Several files combined into one delivered file.                                        |
| Cache           | A holding area. The server keeps finished files; the browser remembers what it loaded. |
| Cache busting   | A trick that forces the browser to reload a changed file instead of the old copy.      |
| Front end       | The public website your visitors see.                                                  |
| Back end        | The admin area you work in while logged in.                                            |

---

## Quick start: "I just want my site to load faster"

1. Open **Admin-Tools → Asset Optimizer**, the **Settings** tab.
2. Turn on **Minify CSS**.
3. Turn on **Minify JavaScript**.
4. Under *Browser cache busting*, turn on the **Front end** switch.
5. Click **Save settings**.
6. Visit your website once, normally. Done.

Then switch to the **Cache & status** tab. After the first page load it shows how
many files were built and what percentage was saved.

If something looks odd afterwards, see [If something looks broken](#if-something-looks-broken).

---

## The "Settings" tab

At the top is a note about where the values are stored. In short: the
minification switches go into a config file (`var/config_constants.ini.php`), the
two cache-busting switches into the database. Both survive a WBCE update. You
don't need to worry about it — unless the yellow box further down appears.

### The "Minification" card

#### Minify CSS

- **What it does:** Strips the whitespace and comments out of every style file on
  your site and stores the smaller version in the cache. Files from other servers
  (CDN) are left alone.
- **Recommendation:** **On.** This makes sense for almost every site.
- **How you can tell it's working:** On the *Cache & status* tab, entries appear
  under "Minified files" and "Bundles", and the "Saved" column shows a
  percentage.
- **Can it break anything?** With CSS, almost never.

#### Minify JavaScript

- **What it does:** The same for script files.
- **Recommendation:** **On** — but click through the first page afterwards
  (menu, sliders, forms), because JavaScript is more sensitive to shrinking than
  CSS.
- **Can it break anything?** Rarely. If it does, see [If something looks
  broken](#if-something-looks-broken).

#### Keep the .min suffix

- **What it does:** Whether the cache files are named `...min.css` or just
  `...css`.
- **Recommendation:** **Leave it on** (the default). Purely cosmetic in the
  filename. Changing this setting rewrites the cache.

#### Developer source view

- **What it does:** While **you** are logged in, this switch turns off shrinking
  and bundling *for you*, so your browser's developer tools show the original
  individual files. Every other visitor still gets the optimised version.
- **Recommendation:** **Off**, unless you are hunting a bug. It is harmless to
  leave on — nothing changes for the public.

### The "Browser cache busting" card

In short: without cache busting, a visitor's browser often keeps a style or
script file it has loaded once for days — even after you have long since changed
it. Cache busting appends the modification time to the file's address
(`main.css?1712345678`). When the file changes, the address changes, and the
browser fetches the new version automatically. Third-party CDN addresses are
left alone.

#### Front end

- **What it does:** Cache busting on the public website.
- **Recommendation:** **On** if you maintain your site now and then. **Off** only
  if nothing has changed in years and you want to squeeze out the last bit of
  browser caching.

#### Back end

- **What it does:** Cache busting for the admin area, separate from the front
  end.
- **When you can change it:** only while **Front end** is off. As long as
  front-end cache busting is on, this one is locked on as well — the admin area
  never lags behind the public site.
- **What it's for:** turning it on while **Front end** is off keeps the admin
  area fresh (CSS/JS is edited constantly there) on an otherwise static public
  site.

### The "Advanced" card

#### Cache directory

- **What it does:** The folder the built files are written to. It shows the
  current path (the default is `cache/assets/` inside your WBCE folder).
- **How to change it:** click **Set a custom directory**, then type an absolute
  path. Clear the field and save to go back to the default. Only do this if your
  host gives you a specific path — it must be writable by the web server and
  reachable over the web.

#### Admin console errors

- **What it does:** When on, the asset pipeline reports its own technical
  problems to logged-in administrators in the browser console. It has its own
  switch — the global development mode turns it on too, but you don't have to
  turn that on just for this.
- **Recommendation:** **Off** on a live site. Only turn it on to hunt a bug.

### The yellow box "… constant(s) defined in config.php"

If this box appears, one of the values is hard-coded in the `config.php` file. A
value there **always wins** — over the config file and the database — cannot be
changed here (the switch is greyed out), and can be lost during an update.

**What to do:** take the named line out of `config.php` and move it into
`var/config_constants.ini.php`. After that this tool manages it normally. If you
don't feel confident doing that yourself, pass the message to whoever looks
after your server — it's a two-minute job.

---

## The "Cache & status" tab

This shows what is currently in the cache. There is nothing to configure here —
it's an overview plus two clean-up buttons.

### The number strip at the top

| Field        | Meaning                                                      |
| ------------ | ------------------------------------------------------------ |
| cached files | How many built files are stored right now.                   |
| original     | Combined size of the files *before* they were shrunk.        |
| optimised    | Size *after* shrinking.                                      |
| gzipped est. | An estimate of how small the files actually are on the wire. |
| saved        | The difference as a percentage — the actual saving.          |
| last build   | When a cache file was last built.                            |

Example: original 240 KB → optimised 180 KB → saved 25 %. Thanks to gzip, what's
actually transferred is often a fraction of that.

### "Bundles" and "Minified files"

Two lists. **Bundles** are groups combined into one file (set up by the
template). **Minified files** are individually shrunk files.

Click a bundle row: it expands and shows the individual files the bundle was
built from — each with its size and modification time. If a source file has
since been deleted from disk, it appears **struck through in red** with the note
"source deleted". That's a sign the template or a module was tidied up and the
bundle should be rebuilt (click "Clear asset cache" once, see below).

### The "Clear asset cache" and "Clear font cache" buttons

- **What they do:** delete the built files, or the self-hosted fonts,
  respectively, from the cache.
- **Is that dangerous?** **No.** The next page load rebuilds whatever is needed.
  The only effect: the very first load afterwards is minimally slower.
- **When it helps:** after a template or module update, when a change doesn't
  show through, or when a source file is shown struck through in red.

### The "Health checks" card

A list of green and yellow dots.

- **Green:** all fine, nothing to do.
- **Yellow:** a hint — the site works, but something isn't ideal.

Possible yellow messages and what to do:

| Message                                 | What to do                                                                                                                                             |
| --------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------ |
| Asset cache directory is *not* writable | The web server can't create anything in the cache folder. Your host needs to grant write permission.                                                   |
| Font cache directory is *not* writable  | The same for `cache/fonts/` — only needed if you self-host web fonts.                                                                                  |
| `matthiasmullie/minify` is missing      | Only shows on a broken install — the library is part of WBCE core. Reinstall it or ask your host. Until then, WBCE shrinks JavaScript only cautiously. |
| cURL is *not* available                 | Only matters if you want to self-host web fonts. Your host needs to enable the cURL PHP extension.                                                     |
| … constant(s) pinned in config.php      | See the yellow box on the *Settings* tab — move the line into `var/config_constants.ini.php`.                                                          |

---

## If something looks broken

After turning on shrinking, something occasionally looks different — usually it's
JavaScript. Work through this in order:

1. **Clear the cache.** *Cache & status* tab → "Clear asset cache". Reload the
   page (best with Ctrl+F5). That often settles it.
2. **Turn Minify JavaScript off as a test.** *Settings* tab → turn "Minify
   JavaScript" off, save, clear the cache, check the page. If the fault is gone,
   that was it.
3. **Turn Developer source view on.** With it, you (as a logged-in admin) see the
   original files and the exact error message in your browser's developer tools.
   Pass that message to your developer.
4. **Switch back.** Turn off every switch the fault appeared with, save, clear the
   cache — the site is back to how it was immediately. Nothing breaks
   permanently.

---

## Frequently asked questions

**Do I have to leave shrinking on permanently?**
Yes, that's the point — it works on every page load. Only turn it off to hunt a
bug.

**Will I lose the settings on a WBCE update?**
No. They live in `var/config_constants.ini.php` and in the database, both
update-safe. The one exception is the yellow-box case (value is in `config.php`)
— which is why it tells you to move it.

**What about visitors who already have my site in their browser cache?**
As long as cache busting is on, they automatically get the new version on their
next visit as soon as you have changed something.

**Do I need the `matthiasmullie/minify` library?**
It's already there — it ships with WBCE and does the shrinking. Only if it goes
missing on a broken install does the *Cache & status* tab flag it, and WBCE
shrinks JavaScript cautiously until it's back.

**The "Clear asset cache" button — can I press it without worrying?**
Yes, any time. Worst case, the first page load afterwards is a fraction of a
second slower.

---

*For developers:* the other tabs above —
[**Assets in templates**](?tool=asset_optimizer&tab=docs&doc=embed)
(`ASSETS_TUTORIAL.md`, loading assets from a template or module),
[**Web fonts**](?tool=asset_optimizer&tab=docs&doc=fonts)
(`FONTS_TUTORIAL.md`, self-hosting fonts) and
[**Full reference**](?tool=asset_optimizer&tab=docs&doc=ref)
(`ASSETS_REFERENCE.md`, the complete reference).
