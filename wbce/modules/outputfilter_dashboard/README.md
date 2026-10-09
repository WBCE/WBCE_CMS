# OutputFilter Dashboard

**Central management for WBCE's output filter pipeline.**


![The Dashboard Overview](documentation/images/OpFDash-LOGO.webp)

Every page WBCE renders passes through a chain of *output filters* — small PHP
functions that get the finished HTML handed to them and may rewrite it before it
reaches the browser. Injecting stylesheets and scripts, resolving link tokens,
executing Droplet calls, highlighting search hits: all of that is a filter on
that chain.

OutputFilter Dashboard owns the chain. It runs it on every request, and it gives
you one screen to see every filter on it, switch filters on and off, reorder
them, restrict them to certain pages or modules, and install new ones.

It ships with WBCE core and is active on every install — open it under
**Admin-Tools → OutputFilter Dashboard**.


---

## What it gives you

- **Two hook levels, seven ordered stages.** A filter can run on a single
  section's output or on the whole assembled page, each with *first* / *last* /
  *final* variants — not one flat, unordered chain.
- **Fine-grained scoping.** Restrict a filter to certain modules, certain pages,
  a page and all its sub-pages, or the WBCE backend — so it only runs where it
  is actually needed.
- **Three authoring paths, one API.** A filter can be a snippet typed straight
  into the tool, a self-contained ZIP plugin, or code shipped inside another
  module — all registered through the same `opf_register_filter()` call.
- **A safety gate.** Filter code is run through WBCE's `CodeVet` syntax and
  security check before it is ever written to the database.

## Filters that ship with WBCE

Registered and active out of the box, listed in the order they run:

| Filter                     | Stage        | What it does                                                                                     |
| -------------------------- | ------------ | ------------------------------------------------------------------------------------------------ |
| **Colorbox**               | Page (first) | Loads the Colorbox lightbox for links carrying `class="colorbox"`, `iframe`, `youtube`, …        |
| **Internal Link Replacer** | Page         | Resolves `[pagelink:NN]`, `[wblinkNN]` and `[<module>:NN]` tokens into real URLs.                |
| **Droplets Injector**      | Page         | Executes Droplet calls (`[[name]]`). Registered by `modules/droplets`, not by this module.       |
| **Replace Contents**       | Page (last)  | Replaces marked content or code inside placeholder blocks.                                       |
| **Class Insert Helper**    | Page (last)  | Triggers AssetQueue injection — moves queued CSS/JS/HTML into the right head and body positions. |
| **Remove System PH**       | Page (final) | Strips any `<!--(PH)...-->` markers left in the finished page.                                   |
| **Assets Cache Busting**   | Page (final) | Appends `?<mtime>` to CSS/JS URLs so browsers stop serving stale files.                          |

*Internal Link Replacer*, *Replace Contents*, *Class Insert Helper* and *Remove
System PH* ship together as one plugin, `plugins/core_outputfilters/`;
*Colorbox* and *Assets Cache Busting* are plugins of their own, and the
*Droplets Injector* is registered by the Droplets module.

## Documentation

| Document                                            | For                                                                    |
| --------------------------------------------------- | ---------------------------------------------------------------------- |
| [User Guide](documentation/USER_GUIDE.md)           | Site administrators. The dashboard screen by screen — no PHP required. |
| [Developer Guide](documentation/DEVELOPER_GUIDE.md) | Anyone writing or packaging a filter.                                  |
| [API Reference](documentation/API_REFERENCE.md)     | The complete `opf_*` function and `$filter` array reference.           |
| [CHANGELOG](CHANGELOG.md)                           | Version history.                                                       |

The same three guides open in the backend from the **Help** link in the tool's
top bar.

## Requirements

- WBCE CMS 1.7.x
- PHP 8.1+

## Credits

Originally written by **Thomas "thorn" Hornik** for WebsiteBaker CMS, first
released in December 2008 — the section/page hook pipeline and the
inline/plugin/module authoring split are his design.

**Christian M. Stefan** ([www.wbEasy.de](https://www.wbEasy.de)) — long-time
co-maintainer; the 1.6.x/1.7.0 modernization (PDO migration, Twig templates,
jQuery removal, AJAX drag & drop, CodeVet integration, `LinkResolver`).

**Martin Hecht** (mrbaseman) — brought the module into WBCE and carried the
majority of the 1.4.x/1.5.x releases, including the PHP 5.4/8 compatibility work.

With fixes and reports from **Bianka Martinovich** (WebBird), **BerndJM**,
**Ralf Hertsch**, **Atlasfreak**, **florian** and others — see the
[CHANGELOG](CHANGELOG.md) for who did what, when.

## License

Software: **GNU General Public License, Version 3** —
<http://www.gnu.org/licenses/gpl.html>
Documentation: **CC BY-SA 3.0 DE** —
<http://creativecommons.org/licenses/by-sa/3.0/de/deed.en>

Full text and copyright holders: [LICENSE.md](LICENSE.md).

## Links

- Add-on page: <https://addons.wbce.org/pages/addons.php?do=item&item=53>
- Forum thread: <https://forum.wbce.org/viewtopic.php?id=176>
- Upstream repository: <https://github.com/mrbaseman/outputfilter_dashboard>
