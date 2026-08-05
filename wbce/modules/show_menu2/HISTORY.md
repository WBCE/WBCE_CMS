# show_menu2 — Version and Author History

Reconstructed from multiple sources (see footnotes). Not 100% gap-free —
especially the early 2006–2009 version numbers come from
forum posts and web searches, not from a continuous version control
system. Where information comes from only a single source and could not
be cross-checked, this is noted.

## Origin

**Original author: Brodie Thiesfield** (forum/project alias **brofield**,
GitHub handle [brofield](https://github.com/brofield), e-mail
`brofield@jellycan.com`, project page `code.jellycan.com/show_menu2/`).

He announced `show_menu2` on **20 February 2006** in the WebsiteBaker forum
(topic “show_menu2: valid XHTML and CSS selectable menus”) as a complete,
faster replacement for the built-in `show_menu()` function — core promise
at the time: **a single** DB query per page load instead of many, plus freely
customisable HTML output.

## Timeline

### Phase 1 — brofield solo (WebsiteBaker era, 2006–2013)

| Version  | Date           | Change                                                                                                       |
|----------|----------------|--------------------------------------------------------------------------------------------------------------|
| 1.2      | 21.02.2006     | Bugfixes, documentation update, minor features                                                               |
| 1.4      | 21.02.2006     | same day as 1.2                                                                                              |
| 1.6      | 21.02.2006     | Bugfixes from code review                                                                                    |
| 2.0      | 22.02.2006     | “final update” (apart from bugfixes); optional HTML tag + ID parameter for the top-level menu tag            |
| 2.1      | 22.02.2006     | Removed all warnings/notices at `error_reporting = E_ALL`                                                    |
| 2.2      | 24.02.2006     | Added breadcrumb generation, multiple tag outputs                                                            |
| 2.5      | 25.02.2006     | Breadcrumb bugfix, `SM2_MAX` flag (max-level feature), parameter changes                                     |
| 2.6–3.x  | 2006–2007      | Conditional formatting (`[if(...)]`), keywords/description fields etc. — details no longer fully recoverable |
| 4.0      | 19.12.2006     | `>=`/`<=` operators in conditional formatting, tests against page level/page ID                              |
| 4.5      | 10.04.2008     | Support for WB 2.7 multiple groups + publish dates                                                           |
| 4.6      | 22.04.2008     | Fix for `page_id = 0` in search results                                                                      |
| 4.7      | Oct. 2008      | Rerelease with updated German translation, new functionality                                                 |
| 4.8      | 08.04.2009     | Fix for menu output when a hidden page is current/parent                                                     |
| 4.9      | 10.08.2009     | Partial reversal of 4.8 — hidden pages only visible with explicit `SM2_SHOWHIDDEN` flag when active          |
| 4.9.6    | 11.07.2013     | Last “standalone” stable version according to WebsiteBaker documentation                                     |
| 4.9.11   | at latest 2013 | `module_author` in `info.php` still only **"Brodie Thiesfield"**                                            |

*(Source comes from a general web search, not from a specific source — separate codebase, referenced here only as
a data point for the pure WebsiteBaker 4.x history, not as a supplier of our code.)*


### Phase 2 — Handover to the community (WebsiteBaker Org. e.V., ~2009–2015)

Brodie Thiesfield handed the module over to the WebsiteBaker community around 2009.
The copyright headers of our current files reflect this:

@copyright Ryan Djurovich (2004-2009)          ← WebsiteBaker core founder, generic header
@copyright WebsiteBaker Org. e.V. (2009-2015)
@copyright WBCE Project (2015-)


A GitHub mirror repo (`WebsiteBaker-modules/show_menu2`) was created by
**Martin Hecht (mrbaseman)** on **21.12.2016** with an “initial import of
v4_9_10”, followed by an update to **4.9.11 on 25.04.2017** — this mirror, however, has only 2 commits (squash import), no
continuous history before that.

### Phase 3 — WBCE era (2015–today)

From here on we have the **complete, gap-free Git history locally**
(39 commits, `git log --follow -- modules/show_menu2`). Oldest recorded
change: **28.07.2015, cwsoft** — “Smaller changes for WBCE branding
purposes” (exactly at the WBCE fork point).

| Date                    | Author                   | Contribution                                                                                                                                             |
|-------------------------|--------------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------|
| 2015-07-28              | **cwsoft**              | WBCE branding adjustments                                                                                                                                |
| 2017-05-01 – 2018-03-27 | **Christoph Bleiweis**  | several updates (index.php/info.php, SM2 update #285, compatibility mode #318, old sm functions removed #336)                                            |
| 2017-05-11 – 2020-05-23 | **instantflorian**      | wording/branding, PHP 7.4 fixes, SM2 debug switch, performance fix for SM2_CORRECT_MENU_LINKS                                                            |
| 2018-10-31 – 2018-11-26 | **Christian M. Stefan** | `is_countable()` fix, menu-link URL replacement, SM2_CORRECT_MENU_LINKS correction                                                                       |
| 2019-12-07              | **Martin Hecht**        | merge follow-up work                                                                                                                                     |
| 2019-03-19 – 2021-07-22 | **Colinax**             | versioning corrected, `MYSQLI_ASSOC` removed/reverted, PHP8 fix, `show_menu2 4.14.1`                                                                     |
| 2023-11-14              | **Bianka Martinovic**   | update include.php                                                                                                                                       |
| 2026-07-29              | **Christian M. Stefan** | `SM2_CORRECT_MENU_LINKS` completely removed (native menu-link resolution), `SM2_EXTERNAL_MENULINKS`/`SM2_USE_ARIA` flags, `[aria]` placeholder — v4.15.0 |
| 2026-08-05              | **Christian M. Stefan** | `show_menu()` as alias for `show_menu2()`, `SM_*` constant aliases for all `SM2_*` flags — v4.16.0                                                       |

## Complete author list (as complete as can be reconstructed)

- **Brodie Thiesfield** (“brofield”) — **original author**, 2006–~2013
- Manuela v.d.Decken — co-author (WebsiteBaker era, details unclear)
- Norbert Heimsath — co-author (WebsiteBaker era, details unclear)
- Martin Hecht (mrbaseman) — maintainer at the WB→WBCE transition, 2016–2019
- cwsoft — WBCE branding, 2015
- Christoph Bleiweis — WBCE maintainer, 2017–2018
- instantflorian — WBCE maintainer, 2017–2020
- Colinax — WBCE maintainer, 2019–2021
- Bianka Martinovic — WBCE, 2023
- Bernd Michna — listed as involved according to the former `module_author` field, WBCE wrapper developer according to web search[^bernd]; exact SM2 contributions not verifiable
- Christian M. Stefan (Stefek) — WBCE maintainer since 2018, among other things menu-link resolution, ARIA support, `show_menu()`/`SM_*` aliases (2026)

## Sources
