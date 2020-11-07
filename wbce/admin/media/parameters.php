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

// Must include code to stop this file being access directly
if (defined('WB_PATH') == false) {
    die("Cannot access this file directly");
}

function __unserialize($sObject)
{  // found in php manual :-)
    $_ret = preg_replace_callback(
        '!s:(\d+):"(.*?)";!',
        function ($matches) {
            return 's:' . strlen($matches[2]) . ':"' . $matches[2] . '";';
        },
        $sObject
    );
    return unserialize($_ret);
}

$pathsettings = array();

$query = $database->query("SELECT * FROM " . TABLE_PREFIX . "settings where `name`='mediasettings'");
if ($query && $query->numRows() > 0) {
    $settings = $query->fetchRow();
    $pathsettings = __unserialize($settings['value']);
} else {
    $database->query("INSERT INTO " . TABLE_PREFIX . "settings (`name`,`value`) VALUES ('mediasettings','')");
}
