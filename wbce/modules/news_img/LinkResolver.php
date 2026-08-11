<?php
/**
 * news_img — LinkResolver provider.
 *
 * Lets the tinymce_wbce link picker offer individual news posts (linkItems())
 * and lets [news_img:NN] tokens in rendered content resolve to that post's
 * URL at render time (resolve()) — see framework/LinkResolver.php.
 *
 * Discovered via 'linkitems' in {TP}addons.function (see info.php).
 */

defined('WB_PATH') or die('Access denied');

class NewsImgLinkResolver extends LinkResolver
{
    public function linkItems(int $sectionId, int $pageId): array
    {
        global $database;
        $rows = $database->fetchAll(
            'SELECT `post_id`, `title`, `link` FROM `{TP}mod_news_img_posts`
              WHERE `section_id` = ? AND `active` = 1
              ORDER BY `post_id` DESC',
            [$sectionId]
        );

        $items = [];
        foreach ($rows as $row) {
            $items[] = [
                'label' => (string) $row['title'],
                'value' => '[news_img:' . (int) $row['post_id'] . ']',
            ];
        }
        return $items;
    }

    public function resolve(int $itemId): ?string
    {
        global $database;
        $link = $database->fetchValue(
            'SELECT `link` FROM `{TP}mod_news_img_posts` WHERE `post_id` = ? AND `active` = 1',
            [$itemId]
        );

        return $link !== '' ? page_link((string) $link) : null;
    }
}
