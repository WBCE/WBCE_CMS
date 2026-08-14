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

$cfg    = $database->fetchRow('SELECT `url`, `height` FROM `{TP}mod_wrapper` WHERE `section_id` = ?', [$section_id]);

// NOTE: this file is require()'d by admin/pages/modify.php inside its section
// loop, sharing that file's variable scope. Do NOT reuse $oTemplate/$oTwig —
// the includer already has its own $oTemplate (phplib Template instance)
// alive at that point (same convention as modules/code2/modify.php).
$wrapperTwig     = getTwig(__DIR__ . '/twig/');
$wrapperTemplate = $wrapperTwig->load('modify.twig');
$wrapperTemplate->display([
    'page_id'    => $page_id,
    'section_id' => $section_id,
    'url'        => $cfg['url'] ?? '',
    'height'     => $cfg['height'] ?? 400,
]);
