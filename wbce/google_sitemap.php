<?php

/** ACKNOWLEDGMENTS ON BASED CODE AUTHOR AND MODS
 * Google Site Map
 * @author Karelkin Vladislav
 * @copyright 2007/2013 GPL
 *
 * Version 1.9.0 2026-05-04 (Christian M. Stefan)
 * - PDO cleanup: canonical Database methods, parameter binding throughout
 *
 * Version 1.8.11 20201107 (Colinax)
 * - cs fixed
 *
 * Version 1.8.10 20200421 (Colinax)
 * - replaced gmdate with date
 *
 * Version 1.8.9 20190712 (Florian)
 * - Add News with images
 *
 * Version 1.8.8 20181204 (Ruud)
 * - fixed multiple sections not seen after pages scan
 *
 * Version 1.8.7 20180529 (Ruud)
 * - added the $show_hidden variable. When set true hidden pages will be included.
 *
 * Version 1.8.6 20160215 (Ruud)
 * - set Shorturl default to false. (was true by accident)
 * - xsl style set to relative path
 *
 * Version 1.8.5 20160210 (Ruud)
 * - added oneforall support (multiple named modules possible)
 * - added optional xsl for browser viewing.
 *
 * Version 1.8.4 20131004 (Ruud)
 * - few small bugfixes for Topics module
 *
 * Version 1.8.3 20130814 (Ruud)
 * - small bugix on using an undeclared static variable
 *
 * Version 1.8.2 20130814 (Ruud)
 * - Added support when shorturl is used
 * - Prevent multiple listings when pages have multiple sections
 * - Prevent "empty links" to be listed.
 *
 * Version 1.8.1 20130426 (Christoph Marti)
 * - Relies on section_id instead of page_id to check module page visibility
 *
 * Version 1.8.0 (Christoph Marti)
 * - Reworked some parts of the code
 * - Only list pages and module pages if they have visiblity active or hidden
 * - Only list sections if they are in-between publication dates
 * - Fixed various small bugs
 * - Added Showcase module
 *
 * Version 1.7.0 (Ruud)
 * - List only post on newspages that are public
 *
 * Version 1.6.0.20100917 (Ruud)
 * - Added Topics module
 *
 * Version 1.5.4.20100520 (MurgtalNet on WB Forum)
 * - Debugs on admin path
 *
 * Version 1.5.3.20100514 (Olivier Labbe (a.k.a. VotreEspace))
 * - Debugs on sql commands
 *
 * Version 1.5.2.20091015 (Olivier Labbe (a.k.a. VotreEspace))
 * - Debug on menulinks problems
 *
 * Version 1.5.2.20090918 (Olivier Labbe (a.k.a. VotreEspace))
 * - Add portfolio module possibilities
 *
 * Version 1.5.1.20090725 (Olivier Labbe (a.k.a. VotreEspace))
 * - Will not show outside links from menu link
 *
 * Version 1.5 (Ruud, thanks to "Mike")
 * - The homepage is now listed as the WB_URL (without /pages/home.php)
 *
 * Version 1.4 (Christoph Marti)
 * - Replaced the hardcoded page directory name /pages by the wb constant PAGES_DIRECTORY
 * - Added feature to hide urls which contain unwanted words (eg. for web pages which are blocked by robots.txt)
 *
 * Version 1.3 (Christoph Marti)
 * - Added module auto detection.
 * - Removed page title comment (Google ERROR with non utf-8 chars).
 *
 * Version 1.21 (Ruud Eisinga)
 * - Added htmlencoding to the url's listed.
 * - Fixed external url's to be not included in the sitemap.
 */

// -------------------------------------------------------------------------
// CONFIGURATION
// -------------------------------------------------------------------------

$sitemap_version = '1.9.0';

// Debug information on / off
$debug = false;
if (isset($_GET['debug'])) {
    $debug = true;
}

// If shorturl is used, no pages and .php
$shorturl = false;

// Ban urls with unwanted words
$exclude = array();
// Show hidden pages
$show_hidden = false;

// Priorities
$page_priority      = "0.5";
$page_home_priority = "1.0";
$page_root_priority = "0.6";
$page_frequency     = "weekly";

$news_priority      = "0.7";
$news_old_priority  = "0.5";
$news_frequency     = "weekly";

$nwi_priority       = "0.7";
$nwi_old_priority   = "0.5";
$nwi_frequency      = "weekly";

$bakery_priority    = "0.5";
$bakery_frequency   = "weekly";

$catalogs_priority  = "0.5";
$catalogs_frequency = "weekly";

$portfolio_priority = "0.5";
$portfolio_frequency = "weekly";

$topics_mod_name    = "topics";
$topics_priority    = "0.5";
$topics_frequency   = "weekly";

$showcase_priority  = "0.5";
$showcase_frequency = "weekly";

$oneforall_mod_names = "oneforall";
$oneforall_priority  = "0.5";
$oneforall_frequency = "weekly";

// -------------------------------------------------------------------------
// END OF CONFIGURATION
// -------------------------------------------------------------------------

require_once __DIR__ . '/config.php';

defined("WB_PATH") or die("Website not configured");

$wb = new Frontend();
$wb->get_website_settings();

$counter    = 0;
$ts         = time();
$public     = [];
$modules    = [];
$debug_info = [];


// ── Helpers ───────────────────────────────────────────────────────────────────

function check_link($link, $exclude)
{
    static $listed = array();

    foreach ($exclude as $value) {
        if (strpos($link, $value)) {
            return "&quot;$link&quot; contains &quot;$value&quot; and will not show up in the google sitemap\n";
        }
    }

    if (strpos($link, '://')) {
        return "$link is an external link and will not show up in the google sitemap\n";
    }
    if (in_array($link, $listed)) {
        return "$link is already listed\n";
    }
    if (trim($link) === '') {
        return "Skipped empty link\n";
    }

    $listed[] = $link;
    return true;
}

function output_xml($link, $lastmod, $freq, $pri)
{
    global $shorturl;
    if ($shorturl) {
        $linkstart = strlen(WB_URL . PAGES_DIRECTORY);
        $linkend   = strlen(PAGE_EXTENSION);
        $link      = WB_URL . substr($link, $linkstart);
        if (substr($link, 0, -$linkend) == PAGE_EXTENSION) {
            $link = substr($link, 0, -$linkend) . '/';
        } else {
            $link = str_replace(PAGE_EXTENSION, '/', $link);
        }
    }
    echo '
  <url>
    <loc>' . $link . '</loc>
    <lastmod>' . $lastmod . '</lastmod>
	<changefreq>' . $freq . '</changefreq>
    <priority>' . $pri . '</priority>
  </url>';
}


// ── XML header ────────────────────────────────────────────────────────────────

if ($debug) {
    ?>
    <!DOCTYPE html>
    <head>
        <meta charset="utf-8"/>
        <style>
            url { display: block; }
            loc { font-weight: bold; line-height: 25px; }
        </style>
    </head>
    <?php
} else {
    @header("Content-Type: application/xml");
    echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
    if (file_exists('./google_sitemap.xsl')) {
        echo '<?xml-stylesheet type="text/xsl" href="google_sitemap.xsl"?>' . PHP_EOL;
    }
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
}


// ── Public WB pages ───────────────────────────────────────────────────────────

$visibilityClause = $show_hidden
    ? "AND (p.`visibility` = 'public' OR p.`visibility` = 'hidden')"
    : "AND p.`visibility` = 'public'";

// $ts is compared against stored integers — passed as a bound parameter.
// The visibility clause is structural SQL (no user input), so it is safe
// to interpolate as a string fragment.
$pages = $database->fetchAll(
    "SELECT p.`link`, p.`modified_when`, p.`parent`, p.`position`,
            s.`section_id`, s.`module`
     FROM `{TP}pages` p
     JOIN `{TP}sections` s ON p.`page_id` = s.`page_id`
     WHERE s.`module` != 'menu_link'
       $visibilityClause
       AND (s.`publ_start` = '0' OR s.`publ_start` <= ?)
       AND (s.`publ_end`   = '0' OR s.`publ_end`   >= ?)
     ORDER BY p.`parent`, p.`position` ASC",
    [$ts, $ts]
);

foreach ($pages as $page) {
    $checked   = check_link($page['link'], $exclude);
    $public[]  = $page['section_id'];
    $modules[] = $page['module'];

    if ($checked === true) {
        $link    = htmlspecialchars($wb->page_link($page['link']));
        $lastmod = date("Y-m-d", $page['modified_when'] + TIMEZONE);
        $freq    = $page_frequency;
        $pri     = $page_priority;

        if ($page['parent'] == 0) {
            if ($page['position'] == 1) {
                $pri  = $page_home_priority;
                $link = WB_URL . '/';
            } else {
                $pri = $page_root_priority;
            }
        }
        output_xml($link, $lastmod, $freq, $pri);
        $counter++;
    } else {
        $debug_info[] = $checked;
    }
}

$modules     = array_unique($modules);
$page_counter = $counter;


// ── News ──────────────────────────────────────────────────────────────────────

if (in_array('news', $modules)) {
    $news_posts = $database->fetchAll(
        "SELECT `section_id`, `link`, `posted_when`, `published_when`
         FROM `{TP}mod_news_posts`
         WHERE `active` = '1'
           AND (`published_when`  = '0' OR `published_when`  <= ?)
           AND (`published_until` = '0' OR `published_until` >= ?)",
        [$ts, $ts]
    );

    $lastweek = $ts - (4 * 7 * 24 * 60 * 60);

    foreach ($news_posts as $news) {
        if (!in_array($news['section_id'], $public)) continue;

        $checked = check_link($news['link'], $exclude);
        if ($checked === true) {
            $link = htmlspecialchars($wb->page_link($news['link']));
            $pri  = ($news['posted_when'] < $lastweek) ? $news_old_priority : $news_priority;

            if ((version_compare(WB_VERSION, '2.7.0') <= 0) && $news['published_when'] > 0) {
                $lastmod = date("Y-m-d", $news['published_when'] + TIMEZONE);
            } else {
                $lastmod = date("Y-m-d", $news['posted_when'] + TIMEZONE);
            }
            output_xml($link, $lastmod, $news_frequency, $pri);
            $counter++;
        } else {
            $debug_info[] = $checked;
        }
    }
}


// ── News with images (NWI) ────────────────────────────────────────────────────

if (in_array('news_img', $modules)) {
    $nwi_posts = $database->fetchAll(
        "SELECT `section_id`, `link`, `posted_when`, `published_when`
         FROM `{TP}mod_news_img_posts`
         WHERE `active` = '1'
           AND (`published_when`  = '0' OR `published_when`  <= ?)
           AND (`published_until` = '0' OR `published_until` >= ?)",
        [$ts, $ts]
    );

    $lastweek = $ts - (4 * 7 * 24 * 60 * 60);

    foreach ($nwi_posts as $nwi) {
        if (!in_array($nwi['section_id'], $public)) continue;

        $checked = check_link($nwi['link'], $exclude);
        if ($checked === true) {
            $link = htmlspecialchars($wb->page_link($nwi['link']));
            $pri  = ($nwi['posted_when'] < $lastweek) ? $nwi_old_priority : $nwi_priority;

            if ((version_compare(WB_VERSION, '2.7.0') <= 0) && $nwi['published_when'] > 0) {
                $lastmod = date("Y-m-d", $nwi['published_when'] + TIMEZONE);
            } else {
                $lastmod = date("Y-m-d", $nwi['posted_when'] + TIMEZONE);
            }
            output_xml($link, $lastmod, $nwi_frequency, $pri);
            $counter++;
        } else {
            $debug_info[] = $checked;
        }
    }
}


// ── Bakery ────────────────────────────────────────────────────────────────────

if (in_array('bakery', $modules)) {
    $bakery_items = $database->fetchAll(
        "SELECT `section_id`, `link`, `modified_when`
         FROM `{TP}mod_bakery_items`
         WHERE `active` = '1'"
    );

    foreach ($bakery_items as $bakery) {
        if (!in_array($bakery['section_id'], $public)) continue;

        $checked = check_link($bakery['link'], $exclude);
        if ($checked === true) {
            $link    = htmlspecialchars($wb->page_link($bakery['link']));
            $lastmod = date("Y-m-d", $bakery['modified_when'] + TIMEZONE);
            output_xml($link, $lastmod, $bakery_frequency, $bakery_priority);
            $counter++;
        } else {
            $debug_info[] = $checked;
        }
    }
}


// ── Catalog ───────────────────────────────────────────────────────────────────

if (in_array('catalogs', $modules)) {
    $catalog_items = $database->fetchAll(
        "SELECT `section_id`, `link`, `modified_when`
         FROM `{TP}mod_catalogs_list`
         WHERE `active` = '1'"
    );

    foreach ($catalog_items as $catalog) {
        if (!in_array($catalog['section_id'], $public)) continue;

        $checked = check_link($catalog['link'], $exclude);
        if ($checked === true) {
            $link    = htmlspecialchars($wb->page_link($catalog['link']));
            $lastmod = date("Y-m-d", $catalog['modified_when'] + TIMEZONE);
            output_xml($link, $lastmod, $catalogs_frequency, $catalogs_priority);
            $counter++;
        } else {
            $debug_info[] = $checked;
        }
    }
}


// ── Portfolio ─────────────────────────────────────────────────────────────────

if (in_array('portfolio', $modules)) {
    $portfolio_items = $database->fetchAll(
        "SELECT p.`link`, p.`position`, p.`modified_when`, s.`section_id`
         FROM `{TP}sections` s
         JOIN `{TP}pages` p ON s.`page_id` = p.`page_id`
         WHERE s.`module` = 'portfolio_detail'
           AND p.`position` > 1
         ORDER BY p.`parent`, p.`position` ASC"
    );

    foreach ($portfolio_items as $portfolio) {
        $checked = check_link($portfolio['link'], $exclude);
        if ($checked === true) {
            $length  = strrpos($portfolio['link'], '/');
            $link    = substr($portfolio['link'], 0, $length);
            $link    = htmlspecialchars($wb->page_link($link)) . '?item=' . $portfolio['position'];
            $lastmod = date("Y-m-d", $portfolio['modified_when'] + TIMEZONE);
            output_xml($link, $lastmod, $portfolio_frequency, $portfolio_priority);
            $counter++;
        } else {
            $debug_info[] = $checked;
        }
    }
}


// ── Topics ────────────────────────────────────────────────────────────────────

if (in_array($topics_mod_name, $modules)) {
    require(WB_PATH . '/modules/' . $topics_mod_name . '/module_settings.php');

    $t = mktime(
        (int)date("H"), (int)date("i"), (int)date("s"),
        (int)date("n"), (int)date("j"), (int)date("Y")
    ) + DEFAULT_TIMEZONE;

    // Table name contains the dynamic module name — cannot be a bound parameter.
    // $topics_mod_name is set by the configuration block above (not user input).
    // The comparison values $t are bound parameters.
    $topics_posts = $database->fetchAll(
        "SELECT `section_id`, `link`, `posted_modified`
         FROM `{TP}mod_" . $topics_mod_name . "`
         WHERE (`active` > '3' OR `active` = '1')
           AND (`published_when`  = '0' OR `published_when`  < ?)
           AND (`published_until` = '0' OR `published_until` > ?)
         ORDER BY `position` DESC",
        [$t, $t]
    );

    foreach ($topics_posts as $topics) {
        if (!in_array($topics['section_id'], $public)) continue;

        $checked = check_link($topics['link'], $exclude);
        if ($checked === true) {
            $link    = htmlspecialchars(WB_URL . $topics_directory . $topics['link'] . PAGE_EXTENSION);
            $lastmod = date("Y-m-d", $topics['posted_modified'] + TIMEZONE);
            output_xml($link, $lastmod, $topics_frequency, $topics_priority);
            $counter++;
        } else {
            $debug_info[] = $checked;
        }
    }
}


// ── Showcase ──────────────────────────────────────────────────────────────────

if (in_array('showcase', $modules)) {
    $showcase_items = $database->fetchAll(
        "SELECT `section_id`, `link`, `modified_when`
         FROM `{TP}mod_showcase_items`
         WHERE `active` = '1'"
    );

    foreach ($showcase_items as $showcase) {
        if (!in_array($showcase['section_id'], $public)) continue;

        $checked = check_link($showcase['link'], $exclude);
        if ($checked === true) {
            if (!empty($showcase['link'])) {
                // fetchValue() replaces the old get_one() with parameter binding.
                $path = $database->fetchValue(
                    "SELECT p.`link`
                     FROM `{TP}pages` p
                     JOIN `{TP}mod_showcase_items` i ON p.`page_id` = i.`page_id`
                     WHERE i.`link` = ?
                     LIMIT 1",
                    [$showcase['link']]
                );
                $showcase['link'] = ($path !== '') ? $path . $showcase['link'] : $showcase['link'];
            }
            $link    = htmlspecialchars($wb->page_link($showcase['link']));
            $lastmod = date("Y-m-d", $showcase['modified_when'] + TIMEZONE);
            output_xml($link, $lastmod, $showcase_frequency, $showcase_priority);
            $counter++;
        } else {
            $debug_info[] = $checked;
        }
    }
}


// ── OneForAll (and variants) ──────────────────────────────────────────────────

$oneforall_mods = explode(',', $oneforall_mod_names);

foreach ($oneforall_mods as $oneforall_mod_name) {
    $oneforall_mod_name = trim($oneforall_mod_name);
    if (!in_array($oneforall_mod_name, $modules)) continue;

    // Table name contains the dynamic module name — cannot be a bound parameter.
    // $oneforall_mod_name is set by the configuration block above (not user input).
    $oneforall_items = $database->fetchAll(
        "SELECT `section_id`, `page_id`, `link`, `modified_when`
         FROM `{TP}mod_" . $oneforall_mod_name . "_items`
         WHERE `active` = '1'"
    );

    foreach ($oneforall_items as $oneforall) {
        if (!in_array($oneforall['section_id'], $public)) continue;

        // fetchValue() replaces the old get_one() with parameter binding.
        $page = $database->fetchValue(
            "SELECT `link` FROM `{TP}pages` WHERE `page_id` = ?",
            [(int)$oneforall['page_id']]
        );

        $checked = check_link($page . $oneforall['link'], $exclude);
        if ($checked === true) {
            $link    = htmlspecialchars($wb->page_link($page . $oneforall['link']));
            $lastmod = date("Y-m-d", $oneforall['modified_when'] + TIMEZONE);
            output_xml($link, $lastmod, $oneforall_frequency, $oneforall_priority);
            $counter++;
        } else {
            $debug_info[] = $checked;
        }
    }
}


// Add another module here...
// Example code
/*
if (in_array('xxxxxx', $modules)) {
    $items = $database->fetchAll(
        "SELECT `section_id`, `link`, `modified_when`
         FROM `{TP}mod_xxxxxx_items`
         WHERE `active` = '1'"
    );
    foreach ($items as $item) {
        if (!in_array($item['section_id'], $public)) continue;
        $checked = check_link($item['link'], $exclude);
        if ($checked === true) {
            $link    = htmlspecialchars($wb->page_link($item['link']));
            $lastmod = date("Y-m-d", $item['modified_when'] + TIMEZONE);
            output_xml($link, $lastmod, $xxxxxx_frequency, $xxxxxx_priority);
            $counter++;
        } else {
            $debug_info[] = $checked;
        }
    }
}
*/


// ── Debug output ──────────────────────────────────────────────────────────────

if ($debug) {
    echo '<div style="display:block;white-space:pre;border:2px solid #c77;padding:0 1em 1em 1em;margin:1em;line-height:18px;background-color:#fdd;color:black">';
    echo '<h3>DEBUG</h3>';
    echo '<h3>Number of Pages</h3>';
    echo '<div style="font-family:monospace;font-size:12px">Number of Pages excluding module pages: ' . $page_counter . '<br>';
    echo 'Number of all Pages including module pages: ' . $counter . '</div>';
    if (count($debug_info) > 0) {
        echo '<h3>Banned Pages</h3><div style="font-family:monospace;font-size:12px">' . implode('', $debug_info) . '</div>';
    }
    echo '</div>';
} else {
    echo "\n" . '</urlset>';
}