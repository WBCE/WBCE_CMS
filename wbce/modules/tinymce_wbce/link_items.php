<?php
/**
 * tinymce_wbce — link_items.php
 *
 * "Link items" contract: lets the link plugin offer not just a page, but a
 * SPECIFIC item within a page's module section (a news post, a calendar
 * entry, …). The modern, section-scoped, PDO'd replacement for what
 * modules/ckeditor/ckeditor/plugins/wblink/pages.php did — that dumped every
 * item on the WHOLE site into one JS blob on every editor load, via raw SQL
 * string concatenation and addslashes()-as-JS-escaping.
 *
 * TWO ways a module's items show up here:
 *
 *   1. BUILT IN — the legacy WB modules below (news, topics, bakery,
 *      procalendar, responsiveFG) don't exist as real modules in this repo
 *      (no folder to put a provider in) and/or don't know this contract
 *      exists, so tinymce_wbce ships stand-in providers itself, wired
 *      through TINYMCE_WBCE_BUILTIN_LINK_ITEMS. They are never looked up by
 *      name — only ever called through that table.
 *
 *   2. DISCOVERED — any real module opts in simply by having a
 *      `LinkResolver.php` at the root of its module directory (no {TP}addons
 *      marker — framework/LinkResolver.php finds it with a filesystem glob,
 *      see its discoverProviders()), defining a single class extending
 *      framework/LinkResolver.php:
 *
 *          class <StudlyCaseDir>LinkResolver extends LinkResolver
 *          {
 *              public function linkItems(int $sectionId, int $pageId): array { … }
 *              public function resolve(int $itemId): ?string { … }
 *          }
 *
 *      One class, one registration point — linkItems() feeds this picker,
 *      resolve() is what framework/LinkResolver.php calls at render time to
 *      turn a "[<dir>:NN]" token back into a URL (see modules/news_img for a
 *      real example, and opf_wblink.php for where resolve() gets called).
 *
 * CONTRACT
 * --------
 *   linkItems(int $sectionId, int $pageId): array<int, array{label: string, value: string}>
 *
 *   'value' should be a "[pagelink:NN]" or "[<dir>:NN]" token when possible
 *   (survives the target item/page being renamed — LinkResolver re-resolves
 *   it on every render instead of freezing a URL at save time), or a fully
 *   resolved URL only when there's no stable id to token (as topics/bakery
 *   below still do — they're stand-ins for modules that aren't in this repo).
 *
 * @license GNU GPL2
 */

defined('WB_PATH') or die('Access denied');

// ── Built-in providers (the module itself has no idea this contract exists) ─

function link_items_news(int $sectionId, int $pageId): array
{
    global $database;
    $rows = $database->fetchAll(
        'SELECT `title`, `link` FROM `{TP}mod_news_posts`
          WHERE `section_id` = ? AND `active` = 1
          ORDER BY `post_id` DESC',
        [$sectionId]
    );
    $items = [];
    foreach ($rows as $row) {
        $items[] = [
            'label' => (string) $row['title'],
            // $pageId unused here — this module resolves a real URL from its
            // own `link` slug, not a [pagelink:NN] token. Built via the core
            // page_link() (framework/functions.php -> Wbce::page_link()) —
            // today it's the same WB_URL+PAGES_DIRECTORY+…+PAGE_EXTENSION
            // concatenation, but it's the one place the planned routing
            // rework (.claude/routing-redesign.md) will actually change, so
            // going through it now means this provider won't need touching
            // again when that lands.
            'value' => page_link((string) $row['link']),
        ];
    }
    return $items;
}

function link_items_topics(int $sectionId, int $pageId): array
{
    global $database;
    $rows = $database->fetchAll(
        'SELECT `title`, `link` FROM `{TP}mod_topics`
          WHERE `section_id` = ? AND `active` > 0
          ORDER BY `topic_id` DESC',
        [$sectionId]
    );
    $items = [];
    foreach ($rows as $row) {
        $items[] = [
            'label' => (string) $row['title'],
            // NOT switched to page_link() like its siblings below — topics
            // pages live under their own /topics/ sub-path, and page_link()
            // has no concept of a subfolder prefix (only a single slug or
            // page id). Forcing one in by string-prefixing the slug would be
            // a fragile hack against exactly the future page_link() rework
            // this change is meant to get ahead of, so this one stays as
            // direct concatenation until page_link() itself gains a way to
            // express it (or topics moves to a plain page + section instead
            // of its own sub-path).
            'value' => WB_URL . PAGES_DIRECTORY . '/topics/' . (string) $row['link'] . PAGE_EXTENSION,
        ];
    }
    return $items;
}

function link_items_bakery(int $sectionId, int $pageId): array
{
    global $database;
    $rows = $database->fetchAll(
        'SELECT `title`, `link` FROM `{TP}mod_bakery_items`
          WHERE `section_id` = ? AND `active` = 1
          ORDER BY `item_id` DESC',
        [$sectionId]
    );
    $items = [];
    foreach ($rows as $row) {
        $items[] = [
            'label' => (string) $row['title'],
            'value' => page_link((string) $row['link']),
        ];
    }
    return $items;
}

function link_items_procalendar(int $sectionId, int $pageId): array
{
    global $database;
    $rows = $database->fetchAll(
        'SELECT `id`, `name`, `date_start` FROM `{TP}mod_procalendar_actions`
          WHERE `section_id` = ?
          ORDER BY `date_start` DESC',
        [$sectionId]
    );
    $items = [];
    foreach ($rows as $row) {
        $date = explode('-', (string) $row['date_start']); // stored as Y-M-D
        if (count($date) !== 3 || !ctype_digit($date[0]) || !ctype_digit($date[1]) || !ctype_digit($date[2])) {
            continue;
        }
        [$year, $month, $day] = $date;
        $items[] = [
            'label' => (string) $row['name'],
            // Deliberate divergence from the legacy version: the event name
            // is now rawurlencode()'d in the query string. The old code put
            // it in raw (only addslashes()'d for ITS OWN JS-string-literal
            // output, never URL-encoded) — any '&', '#' or space in a name
            // would already have broken the resulting link before this change.
            'value' => '[pagelink:' . $pageId . ']?' . rawurlencode((string) $row['name'])
                . '&month=' . (int) $month . '&year=' . (int) $year . '&day=' . (int) $day
                . '&page_id=' . $pageId . '&id=' . (int) $row['id'] . '&detail=1',
        ];
    }
    return $items;
}

function link_items_responsivefg(int $sectionId, int $pageId): array
{
    global $database;
    $rows = $database->fetchAll(
        'SELECT `id`, `cat_path`, `categorie` FROM `{TP}mod_responsiveFG_categories`
          WHERE `section_id` = ? AND `active` = 1 AND `is_empty` = 0
          ORDER BY `cat_path` ASC',
        [$sectionId]
    );
    $items = [];
    foreach ($rows as $row) {
        $items[] = [
            'label' => $row['cat_path'] . ' - ' . $row['categorie'],
            // Here $pageId IS the whole point: the value is a [pagelink:NN]
            // token, and pagelink tokens address PAGES, not sections.
            'value' => '[pagelink:' . $pageId . ']?cat_id=' . (int) $row['id'],
        ];
    }
    return $items;
}

const TINYMCE_WBCE_BUILTIN_LINK_ITEMS = [
    'news'         => 'link_items_news',
    'topics'       => 'link_items_topics',
    'bakery'       => 'link_items_bakery',
    'procalendar'  => 'link_items_procalendar',
    'responsiveFG' => 'link_items_responsivefg',
];

// ── Master entry point ──────────────────────────────────────────────────────

/**
 * All linkable items across every module-backed section on one page, grouped
 * by section. Used by ajax_link_items.php.
 *
 * @return array<int, array{module: string, section_id: int, items: array}>
 */
function tinymce_wbce_link_items_for_page(int $pageId): array
{
    global $database;

    $sections = $database->fetchAll(
        'SELECT `section_id`, `module` FROM `{TP}sections` WHERE `page_id` = ?',
        [$pageId]
    );
    if (!$sections) { return []; }

    $out = [];

    foreach ($sections as $section) {
        $sectionId = (int) $section['section_id'];
        $module    = (string) $section['module'];

        $fn = TINYMCE_WBCE_BUILTIN_LINK_ITEMS[$module] ?? null;

        if ($fn !== null) {
            $items = $fn($sectionId, $pageId);
        } else {
            $provider = LinkResolver::providerFor($module);
            if ($provider === null) { continue; }
            $items = $provider->linkItems($sectionId, $pageId);
        }

        if (is_array($items) && $items) {
            $out[] = ['module' => $module, 'section_id' => $sectionId, 'items' => $items];
        }
    }
    return $out;
}
