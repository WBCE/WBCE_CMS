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

if (!defined('WB_URL')) {
    exit(header('Location: ../index.php'));
}

// convert string from jscalendar to timestamp.
// converts dd.mm.yyyy and mm/dd/yyyy, with or without time.
// strtotime() may fails with e.g. "dd.mm.yyyy"
function jscalendar_to_timestamp($str, $offset = '', $timeshift = '')
{
    $str = trim($str);
    if ($str == '0' || $str == '') {
        return ('0');
    }
    if ($offset == '0') {
        $offset = '';
    }
    // convert to yyyy-mm-dd
    // "dd.mm.yyyy"?
    if (preg_match('/^\d{1,2}\.\d{1,2}\.\d{2}(\d{2})?/', $str)) {
        $str = preg_replace('/^(\d{1,2})\.(\d{1,2})\.(\d{2}(\d{2})?)/', '$3-$2-$1', $str);
    }
    // "mm/dd/yyyy"?
    if (preg_match('#^\d{1,2}/\d{1,2}/(\d{2}(\d{2})?)#', $str)) {
        $str = preg_replace('#^(\d{1,2})/(\d{1,2})/(\d{2}(\d{2})?)#', '$3-$1-$2', $str);
    }
    // use strtotime()
    if ($timeshift == '') {
        if ($offset != '') {
            return (strtotime($str, $offset));
        } else {
            return (strtotime($str));
        }
    } else {
        if ($offset != '') {
            return (strtotime($str, $offset) - TIMEZONE);
        } else {
            return (strtotime($str) - TIMEZONE);
        }
    }
}
