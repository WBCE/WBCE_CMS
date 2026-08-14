<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */
defined('WB_PATH') or die('Cannot access this file directly');

Lang::loadLanguage(__DIR__);

// get url
$cfg = $database->fetchRow("SELECT url,height FROM {TP}mod_wrapper WHERE section_id = ?", [$section_id]);
$url = h($cfg['url']);

?>
<iframe src="<?=$url; ?>" width="100%" height="<?=$cfg['height']; ?>" frameborder="0" scrolling="auto">
<?= $TEXT['NO_IFRAME_SUPPORT']; ?>
<a href="<?=$url; ?>" target="_blank"><?=$url; ?></a>
</iframe>