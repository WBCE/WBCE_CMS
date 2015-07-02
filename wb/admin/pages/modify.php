<?php
/**
 *
 * @category        admin
 * @package         pages
 * @author          WebsiteBaker Project
 * @copyright       Ryan Djurovich
 * @copyright       WebsiteBaker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: modify.php 1625 2012-02-29 00:50:57Z Luisehahne $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/admin/pages/modify.php $
 * @lastmodified    $Date: 2012-02-29 01:50:57 +0100 (Mi, 29. Feb 2012) $
 *
*/
/*
*/
// Create new admin object
require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');

$admin = new admin('Pages', 'pages_modify');
/*
// Get page id
if(!isset($_GET['page_id']) || !is_numeric($_GET['page_id'])) {
    header("Location: index.php");
    exit(0);
} else {
    $page_id = (int)$_GET['page_id'];
}
*/
// Get page id
    $requestMethod = '_'.strtoupper($_SERVER['REQUEST_METHOD']);
    $page_id = intval(isset(${$requestMethod}['page_id']) ? ${$requestMethod}['page_id'] : 0);
    if(    ($page_id == 0)) {
        header("Location: index.php");
        exit(0);
    }

/*
if( (!($page_id = $admin->checkIDKEY('page_id', $page_id, $_SERVER['REQUEST_METHOD']))) )
{
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
}
*/

// Get perms
if(!$admin->get_page_permission($page_id,'admin')) {
    $admin->print_error($MESSAGE['PAGES']['INSUFFICIENT_PERMISSIONS']);
}

$sectionId = isset($_GET['wysiwyg']) ? htmlspecialchars($admin->get_get('wysiwyg')) : NULL;

// Get page details
$results_array=$admin->get_page_details($page_id);

// Get display name of person who last modified the page
$user=$admin->get_user_details($results_array['modified_by']);

// Convert the unix ts for modified_when to human a readable form

$modified_ts = ($results_array['modified_when'] != 0)
        ? $modified_ts = date(TIME_FORMAT.', '.DATE_FORMAT, $results_array['modified_when']+TIMEZONE)
        : 'Unknown';
// $ftan_module = $GLOBALS['ftan_module'];
// Setup template object, parse vars to it, then parse it
// Create new template object
$template = new Template(dirname($admin->correct_theme_source('pages_modify.htt')));
// $template->debug = true;
$template->set_file('page', 'pages_modify.htt');
$template->set_block('page', 'main_block', 'main');
$template->set_var('FTAN', $admin->getFTAN() );

$template->set_var(array(
            'PAGE_ID' => $results_array['page_id'],
            // 'PAGE_IDKEY' => $admin->getIDKEY($results_array['page_id']),
            'PAGE_IDKEY' => $results_array['page_id'],
            'PAGE_TITLE' => ($results_array['page_title']),
            'MENU_TITLE' => ($results_array['menu_title']),
            'ADMIN_URL' => ADMIN_URL,
            'WB_URL' => WB_URL,
            'THEME_URL' => THEME_URL
            ));

$template->set_var(array(
            'MODIFIED_BY' => $user['display_name'],
            'MODIFIED_BY_USERNAME' => $user['username'],
            'MODIFIED_WHEN' => $modified_ts,
            'LAST_MODIFIED' => $MESSAGE['PAGES']['LAST_MODIFIED'],
            ));

$template->set_block('main_block', 'show_modify_block', 'show_modify');
if($modified_ts == 'Unknown')
{
    $template->set_block('show_modify', '');
    $template->set_var('CLASS_DISPLAY_MODIFIED', 'hide');

} else {
    $template->set_var('CLASS_DISPLAY_MODIFIED', '');
    $template->parse('show_modify', 'show_modify_block', true);
}

// Work-out if we should show the "manage sections" link
$sql = 'SELECT COUNT(*) FROM `'.TABLE_PREFIX.'sections` '
     . 'WHERE `page_id`='.(int)$page_id.' AND `module`=\'menu_link\'';
$query_sections = $database->get_one($sql);

$template->set_block('main_block', 'show_section_block', 'show_section');
if($query_sections) {
    $template->set_block('show_section', '');
    $template->set_var('DISPLAY_MANAGE_SECTIONS', 'display:none;');
} elseif(MANAGE_SECTIONS == 'enabled') {
    $template->set_var('TEXT_MANAGE_SECTIONS', $HEADING['MANAGE_SECTIONS']);
    $template->parse('show_section', 'show_section_block', true);
} else {
    $template->set_block('show_section', '');
    $template->set_var('DISPLAY_MANAGE_SECTIONS', 'display:none;');
}

// Insert language TEXT
$template->set_var(array(
                'TEXT_CURRENT_PAGE' => $TEXT['CURRENT_PAGE'],
                'TEXT_CHANGE_SETTINGS' => $TEXT['CHANGE_SETTINGS'],
                'HEADING_MODIFY_PAGE' => $HEADING['MODIFY_PAGE']
                ));

// Parse and print header template
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

// get template used for the displayed page (for displaying block details)
if (SECTION_BLOCKS)
{
    $sql = 'SELECT `template` FROM `'.TABLE_PREFIX.'pages` '
         . 'WHERE `page_id`='.(int)$page_id;
    if (($sTemplate = $database->get_one($sql)) !== null) {
        $page_template = ($sTemplate == '') ? DEFAULT_TEMPLATE : $sTemplate;
//    $result = $database->query($sql);
//    if ($result && $result->numRows() == 1) {
//        $row = $result->fetchRow();
//        $page_template = ($row['template'] == '') ? DEFAULT_TEMPLATE : $row['template'];
        // include template info file if exists
        if (is_readable(WB_PATH.'/templates/'.$page_template.'/info.php')) {
            include_once(WB_PATH.'/templates/'.$page_template.'/info.php');
        }
    }
}

// Get sections for this page
$module_permissions = $_SESSION['MODULE_PERMISSIONS'];
// workout for edit only one section for faster pageloading
// Constant later set in wb_settings, in meantime defined in framework/initialize.php
$sql = 'SELECT `section_id`, `module`, `block` FROM `'.TABLE_PREFIX.'sections` ';
$sql .= (defined('EDIT_ONE_SECTION') && EDIT_ONE_SECTION && is_numeric($sectionId))
        ? 'WHERE `section_id` = '.$sectionId
        : 'WHERE `page_id` = '.intval($page_id);
$sql .= ' ORDER BY position ASC';
$query_sections = $database->query($sql);
if($query_sections->numRows() > 0)
{
    while($section = $query_sections->fetchRow())
    {
        $section_id = $section['section_id'];
        $module = $section['module'];
        
        //Have permission?
        if(!is_numeric(array_search($module, $module_permissions)))
        {
            // Include the modules editing script if it exists
            if(file_exists(WB_PATH.'/modules/'.$module.'/modify.php'))
            {
                print /* '<a name="'.$section_id.'"></a>'. */"\n";
                // output block name if blocks are enabled
                if (SECTION_BLOCKS) {
                    if (isset($block[$section['block']]) && trim(strip_tags(($block[$section['block']]))) != '')
                    {
                        $block_name = htmlentities(strip_tags($block[$section['block']]));
                    } else {
                        if ($section['block'] == 1)
                        {
                            $block_name = $TEXT['MAIN'];
                        } else {
                            $block_name = '#' . (int) $section['block'];
                        }
                    }

                    $sec_anchor = (defined( 'SEC_ANCHOR' ) && ( SEC_ANCHOR != '' )  ? 'id="'.SEC_ANCHOR.$section['section_id'].'"' : '');
                    $sSectionInfoLine = '<div class="section-info" '.$sec_anchor.' ><b>'.$TEXT['BLOCK']
                                      . ': </b>'.$block_name.' ('.$section['block'].') <b> Modul: </b>'
                                      . $section['module'].'<b>  ID: </b>'.$section_id.'</div>'.PHP_EOL;
                    echo $sSectionInfoLine;


                }
                
                require(WB_PATH.'/modules/'.$module.'/modify.php');
            }
        }
    }
}

// Print admin footer
$admin->print_footer();
