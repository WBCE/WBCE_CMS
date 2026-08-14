<?php
/**
 *
 * @category        modules
 * @package         news_img
 * @author          WBCE Community
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2010, Website Baker Org. e.V.
 * @copyright       2019-, WBCE Community
 * @link            https://www.wbce.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WBCE
 *
 */

/**
 * CHANGELOG
 *
 * Version 2026-05-03 (Christian M. Stefan)
 * - PDO cleanup: canonical Database methods, parameter binding, AND instead of &&
 *
 */

// Check that GET values have been supplied
if (isset($_GET['section_id']) && is_numeric($_GET['section_id'])) {
    $section_id = intval($_GET['section_id']);
} else {
    die('section_id missing');
}

if (isset($_GET['page_id']) && is_numeric($_GET['page_id'])) {
    $page_id = intval($_GET['page_id']);
} else {
    die('page_id missing');
}

if (isset($_GET['group_id']) && is_numeric($_GET['group_id'])) {
    $group_id = intval($_GET['group_id']);
    define('GROUP_ID', $group_id);
}

// Boot WBCE
require_once '../../config.php';
$database = new Database();
$wb = new Frontend();
$wb->page_id = $page_id;
$wb->get_page_details();
$wb->get_website_settings();

$charset = defined('DEFAULT_CHARSET') ? DEFAULT_CHARSET : 'utf-8';

header("Content-type: text/xml; charset=$charset");

$t = time();

echo '<?xml version="1.0" encoding="' . $charset . '"?>';
?>
<rss version="2.0">
    <channel>
        <title><?= PAGE_TITLE ?></title>
        <link><?= WB_URL; ?></link>
        <description><?= PAGE_DESCRIPTION ?></description>
        <language><?= strtolower(DEFAULT_LANGUAGE) ?></language>
        <copyright><?php
            $thedate      = date('Y');
            $websitetitle = WEBSITE_TITLE;
            echo "Copyright {$thedate}, {$websitetitle}";
        ?></copyright>
        <category><?= WEBSITE_TITLE; ?></category>
<?php

// ── Main news query ───────────────────────────────────────────────────────────
// $t, $section_id and (optionally) $group_id are bound as parameters.
// && operators (non-standard MySQL alias) replaced with AND.

if (isset($group_id)) {
    $posts = $database->fetchAll(
        "SELECT * FROM `{TP}mod_news_img_posts`
         WHERE `group_id`   = ?
           AND `section_id` = ?
           AND `active`     = 1
           AND (`published_when`  = 0 OR `published_when`  <= ?)
           AND (`published_until` = 0 OR `published_until` >= ?)
         ORDER BY `posted_when` DESC",
        [$group_id, $section_id, $t, $t]
    );
} else {
    $posts = $database->fetchAll(
        "SELECT * FROM `{TP}mod_news_img_posts`
         WHERE `section_id` = ?
           AND `active`     = 1
           AND (`published_when`  = 0 OR `published_when`  <= ?)
           AND (`published_until` = 0 OR `published_until` >= ?)
         ORDER BY `posted_when` DESC",
        [$section_id, $t, $t]
    );
}

// ── Generate RSS items ────────────────────────────────────────────────────────

foreach ($posts as $item) {

    // Resolve link tokens ([pagelink:NN], [wblinkNN], [module:NN]) in content_short.
    // RSS feeds don't pass through OPF, so LinkResolver is called directly here.
    $item['content_short'] = LinkResolver::resolveContent($item['content_short']);
    $itemLink = WB_URL . PAGES_DIRECTORY . $item['link'] . PAGE_EXTENSION;

?>
        <item>
            <title><![CDATA[<?= stripslashes($item['title']) ?>]]></title>
            <description><![CDATA[<?= stripslashes($item['content_short']) ?>]]></description>
            <guid><?= $itemLink ?></guid>
            <link><?= $itemLink ?></link>
        </item>
<?php
} // end foreach $posts
?>
    </channel>
</rss>