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

Lang::loadLanguage();

// Get data for this MenuLink instance
$aData = $database->fetchRow(
    "SELECT
        mml.*,
        p.target
     FROM `{TP}mod_menu_link` mml
     INNER JOIN `{TP}pages` p ON p.page_id = mml.page_id
     WHERE mml.section_id = ?",
    [$section_id]
);

// Build page tree and flat combobox list
$tree   = PageTree::load();
$aLinks = PageTree::pageTreeCombobox($tree, 0, (int)($aData['target_page_id'] ?? 0));

// Build section-anchor map keyed by page_id
$aTargets = [];
foreach ($aLinks as $p) {
    if (!defined('SEC_ANCHOR') || SEC_ANCHOR === '') {
        continue;
    }
    $sections = $database->fetchAll(
        "SELECT `section_id`, `namesection`
         FROM `{TP}sections`
         WHERE `page_id` = ?
         ORDER BY `position`",
        [$p['page_id']]
    );
    foreach ($sections as $section) {
        $anchor = '[#' . SEC_ANCHOR . $section['section_id'] . ']';
        $label  = isset($section['namesection']) ? $anchor . '  ' . $section['namesection'] : $anchor . ' ';
        $aTargets[$p['page_id']][] = $label;
    }
}

// Pre-compute initial anchor display label
$anchorValue = $aData['anchor'] ?? '0';
$sAnchor     = ($anchorValue == '0') ? ' ' : '[#' . $anchorValue . ']';
if (defined('SEC_ANCHOR') && SEC_ANCHOR !== '' && strpos($anchorValue, SEC_ANCHOR) !== false) {
    $aTmp     = explode(SEC_ANCHOR, $anchorValue);
    $iSecID   = $aTmp[1];
    $sNameSec = $database->fetchValue(
        "SELECT `namesection` FROM `{TP}sections` WHERE `section_id` = ?",
        [$iSecID]
    );
    if ($sNameSec) {
        $sAnchor .= ' ' . $sNameSec;
    }
}

// Build JS anchor map
$aAnchorsJs = [];
$sPlsSelect = ($TEXT['PLEASE_SELECT'] ?? '') . ' …';
foreach ($aLinks as $p) {
    $pid = (int)$p['page_id'];
    $aAnchorsJs[$pid] = [$sPlsSelect => '0'];
    if (!empty($aTargets[$pid])) {
        foreach ($aTargets[$pid] as $label) {
            $parts  = explode('[#' . SEC_ANCHOR, $label);
            $parts2 = explode(']', $parts[1]);
            $aAnchorsJs[$pid][$label] = SEC_ANCHOR . $parts2[0];
        }
    }
}

loadPlugin('include/wbeSelect');

$toTwig = [
    'page_id'            => $page_id,
    'section_id'         => $section_id,
    'aData'              => $aData,
    'aLinks'             => $aLinks,
    'sAnchor'            => $sAnchor,
    'anchorValue'        => $anchorValue,
    'currentAnchorJson'  => json_encode($anchorValue),
    'aAnchorsJson'       => json_encode($aAnchorsJs),
    'targetOptions'      => [
        '_blank' => $TEXT['NEW_WINDOW'] ?? 'New Window',
        '_self'  => $TEXT['SAME_WINDOW'] ?? 'Same Window',
        '_top'   => $TEXT['TOP_FRAME']   ?? 'Top Frame',
    ],
    'rTypeOptions'       => [
        '301' => 'Moved Permanently',
        '302' => 'Found / Temporary Redirect',
        '200' => 'OK - No Redirect',
    ],
];

getTwig(__DIR__ . '/twig/')->load('modify.twig')->display($toTwig);
