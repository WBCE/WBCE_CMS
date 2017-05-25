<?php
/**
 * websiteinformations.php
 *
 * @category     Theme
 * @package      Theme_AdvancedWbFlat
 * @subpackage   TotalPageInfo
 * @copyright    Tom Mayer <tom.mayer@example.com>
 * @author       Yetie based on hints of wb development team
 * @license      http://www.gnu.org/licenses/gpl.html   GPL License
 * @version      0.0.1
 * @lastmodified $Date: $
 * @since        File available since 14.01.2015
 * @deprecated   This file/class/function is deprecated since the ...
 * @description  xyz
 */

if (!defined('WB_PATH')) {
    // include wb system data/functions
    include '../../../../config.php';
}
// --- check if logged in
$bLoggedIn = (isset($_SESSION['USER_ID']) && is_numeric($_SESSION['USER_ID']));

// go on only forward if logged in
if ($bLoggedIn) {

    // ### realize multilingual support for dashboard
    // ### for multilingual dashboards use language files in diretory: 'themediretcory/languages/...'
    // #################################################################################################

    $sLangPath = '../../languages/';
    if (is_readable($sLangPath . 'EN.php')) {include $sLangPath . 'EN.php';}
    if (is_readable($sLangPath . DEFAULT_LANGUAGE . '.php')) {include $sLangPath . DEFAULT_LANGUAGE . '.php';}
    if (is_readable($sLangPath . LANGUAGE . '.php')) {include $sLangPath . LANGUAGE . '.php';}

    // ### Prepare variables from wb system and database
    // ### --> to use for html output below
    // ###############################################################################################

    // Count of total pages
    $sql = 'SELECT COUNT(*) FROM `' . TABLE_PREFIX . 'pages`';
    $iCountPages = (int) $database->get_one($sql);

    // Date last updated
    $sql = 'SELECT MAX(`modified_when`) FROM `' . TABLE_PREFIX . 'pages`';
    $iLastModifiedDate = (int) $database->get_one($sql); // (unix-timestamp)

                                              // name default template
    $sDefaultTemplateName = DEFAULT_TEMPLATE; // (string)
                                              // name websitetitel
    $sWebsiteTitelName = WEBSITE_TITLE;       // (string)

                                              // name default language
    $sDefaultLanguageName = DEFAULT_LANGUAGE; // (string)

    // ### html output for dashboard information
    // ### --> use prepared php-vars to show dashboard content
    // ###############################################################################################
    ?>

<a class="db_blocklink" href="<?php echo ADMIN_URL . '/pages/index.php';?>">
<table class="positioning">
 <tr class="count_pages">
  <td><span class="label_icon count_pages"></span></td>
  <td><span class="extra_large"><?php echo $iCountPages;?></span> <span class="extra_small"><?php echo $TEXT['TOTAL'];?></span></td>
 </tr>
 <tr class="last_modified">
  <td><span class="label_icon last_modified"></span></td>
  <td><span class="large"><?php echo date('Y-m-d', $iLastModifiedDate);?></span><br/>
   <span class="extra_small"><?php echo $TEXT['LAST_UPDATE'];?></span></td>
 </tr>
  </tr>
 
</table>
</a>
<?php } // endif ($bLoggedIn) ?>