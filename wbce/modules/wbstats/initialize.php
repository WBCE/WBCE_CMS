<?php
/**
 *
 * @category        admintool
 * @package         wbstats
 * @author          Ruud Eisinga - dev4me.com
 * @link			https://dev4me.com/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x / WBCE 1.4
 * @requirements    PHP 5.6 and higher
 * @version         0.2.5.3
 * @lastmodified    October17, 2022
 *
 */


if (isset($_SERVER['HTTP_REFERER']) && !defined('ORG_REFERER')) {
    define('ORG_REFERER',$_SERVER['HTTP_REFERER']);
}
