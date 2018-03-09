<?php
/**
 * websiteinformations.php
 *
 * @copyright    Tom Mayer <tom.mayer@example.com>
 * @author       Yetie based on hints of wb development team
 * @license      GPL License
 */

if (!defined('WB_PATH')) {
    // include wb system data/functions
    include '../../../config.php';
}
// --- check if logged in
$bLoggedIn = (isset($_SESSION['USER_ID']) && is_numeric($_SESSION['USER_ID']));

// go on only forward if logged in
if ($bLoggedIn) {

    // ### realize multilingual support for dashboard
    // ### for multilingual dashboards use language files in diretory: 'themediretcory/languages/...'
    // #################################################################################################

    $sLangPath = '../languages/';
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

    // ### html output for dashboard information
    // ### --> use prepared php-vars to show dashboard content
    // ###############################################################################################
    ?>

<a class="db_blocklink" href="<?php echo ADMIN_URL . '/pages/index.php';?>">
<div class="row fg-no-gutter">
	<div class="fg2"><span class="label_icon count_pages"></span></div>
	<div class="fg10"><?php echo $TEXT['TOTAL'];?>: <?php echo $iCountPages;?></div>
</div>
<div class="row">
  <div class="fg2"><span class="label_icon last_modified"></span></div>
  <div class="fg10"><?php echo $TEXT['LAST_UPDATE'];?>:    <?php echo date('Y-m-d', $iLastModifiedDate);?></div>
</div> 
</a>
<?php } // endif ($bLoggedIn) ?>