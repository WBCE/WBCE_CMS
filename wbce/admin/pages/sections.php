<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

// Include config file
require '../../config.php';

// Make sure people are allowed to access this page
if (MANAGE_SECTIONS != 'enabled') {
    header('Location: ' . ADMIN_URL . '/pages/index.php');
    exit(0);
}

$debug = false; // to show position and section_id
if (!defined('DEBUG')) {define('DEBUG', $debug);}
// Include the WB functions file
require_once WB_PATH . '/framework/functions.php';
// Create new admin object
require_once WB_PATH . '/framework/class.admin.php';
$admin = new admin('Pages', 'pages_modify', false);

$action = 'show';
// Get page id
$requestMethod = '_' . strtoupper($_SERVER['REQUEST_METHOD']);
$page_id = intval((isset(${$requestMethod}['page_id'])) ? ${$requestMethod}['page_id'] : 0);
$action = ($page_id ? 'show' : $action);
// Get section id if there is one
$requestMethod = '_' . strtoupper($_SERVER['REQUEST_METHOD']);
$section_id = NULL;
if (isset(${$requestMethod}['section_id'])){
    if ((!($section_id = intval($admin->checkIDKEY('section_id', 0, $_SERVER['REQUEST_METHOD']))))) {
        if ($admin_header)
            $admin->print_header();
        $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], $backlink);
    }
}
$action = ($section_id ? 'delete' : $action);
// Get module if there is one
$requestMethod = '_' . strtoupper($_SERVER['REQUEST_METHOD']);
$module = ((isset(${$requestMethod}['module'])) ? ${$requestMethod}['module'] : 0);
$action = ($module != '' ? 'add' : $action);
$admin_header = true;
$backlink = ADMIN_URL . '/pages/sections.php?page_id=' . $page_id;

        #debug_dump($section_id);
switch ($action) {
    case 'delete':
        $action = 'show';
        $sSql = 'SELECT `module` FROM `{TP}sections` WHERE `section_id` = '.$section_id;
        if ((($sModDir = $database->get_one($sSql)) == $module) && ($section_id > 0)) {
            // Include the modules delete file if it exists
            $sDeleteFile = WB_PATH . '/modules/' . $sModDir . '/delete.php';
            if (file_exists($sDeleteFile)) 
                require $sDeleteFile;
            $sSql = 'DELETE FROM `{TP}sections` WHERE `section_id` = '.$section_id.' LIMIT 1';
            if (!$database->query($sSql)) {
                if ($admin_header) 
                    $admin->print_header(); 
                $admin->print_error($database->get_error(), $backlink);
            } else {
                require_once WB_PATH . '/framework/class.order.php';
                $order = new order('{TP}sections', 'position', 'section_id', 'page_id');
                $order->clean($page_id);
                $format = $TEXT['SECTION'] . ' %d  %s %s ' . strtolower($TEXT['DELETED']);
                $message = sprintf($format, $section_id, '', strtolower($TEXT['SUCCESS']));
                if ($admin_header) 
                    $admin->print_header();
                $admin_header = false;
                unset($_POST);
                $admin->print_success($message, $backlink);
            }
        } else {
            if ($admin_header) 
                $admin->print_header();
            $admin->print_error($module . ' ' . strtolower($TEXT['NOT_FOUND']), $backlink);
        }
        break;

    case 'add':
        if (!$admin->checkFTAN()) {
            $admin->print_header();
            $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], $backlink);
        }
        $action = 'show';
        $module = preg_replace('/\W/', '', $module); // fix secunia 2010-91-4
        require_once WB_PATH . '/framework/class.order.php';
        // Get new order
        $order = new order('{TP}sections', 'position', 'section_id', 'page_id');
        $position = $order->get_new($page_id);
        // Insert module into DB
        $aInsert = array(
            'page_id'  => (int) $page_id,
            'module'   => $module,
            'position' => (int) $position,
            'block'    => 1,
        );
        if ($database->insertRow('{TP}sections', $aInsert)) {
            // Get the section id
            $section_id = $database->get_one("SELECT LAST_INSERT_ID()");
            // Include the selected modules add file if it exists
            $sAddFile = WB_PATH . '/modules/' . $module . '/add.php';
            if (file_exists($sAddFile)) 
                require $sAddFile;
        } elseif ($database->is_error()) {
            if ($admin_header) 
                $admin->print_header(); 
            $admin->print_error($database->get_error());
        }
        break;

    default:
        break;
}

switch ($action) {
    default:
        if ($admin_header) 
            $admin->print_header();
        // Get perms
        $sSql = 'SELECT `admin_groups`,`admin_users` FROM `{TP}pages` WHERE `page_id` = '.$page_id;
        $results = $database->query($sSql);

        $results_array = $results->fetchRow(MYSQL_ASSOC);
        $old_admin_groups = explode(',', $results_array['admin_groups']);
        $old_admin_users  = explode(',', $results_array['admin_users']);
        $in_old_group = false;
        foreach ($admin->get_groups_id() as $cur_gid) {
            if (in_array($cur_gid, $old_admin_groups)) {
                $in_old_group = true;
            }
        }
        if ((!$in_old_group) && !is_numeric(array_search($admin->get_user_id(), $old_admin_users))) {
            $admin->print_header();
            $admin->print_error($MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS']);
        }

        // Get page details
        $rPageDetails = $database->query('SELECT * FROM `{TP}pages` WHERE `page_id` = '.$page_id);

        if ($database->is_error()) {
            // $admin->print_header();
            $admin->print_error($database->get_error());
        }
        if ($rPageDetails->numRows() == 0) {
            // $admin->print_header();
            $admin->print_error($MESSAGE['PAGES_NOT_FOUND']);
        }
        $aPage = $rPageDetails->fetchRow(MYSQL_ASSOC);

        // Set module permissions
        $module_permissions = $_SESSION['MODULE_PERMISSIONS'];

        // Unset block var
        unset($block);
        // Include template info file (if it exists)
        if ($aPage['template'] != '') {
            $template_location = WB_PATH . '/templates/' . $aPage['template'] . '/info.php';
        } else {
            $template_location = WB_PATH . '/templates/' . DEFAULT_TEMPLATE . '/info.php';
        }
        if (file_exists($template_location)) {
            require $template_location;
        }
        // Check if $menu is set
        if (!isset($block[1]) || $block[1] == '') {
            // Make our own menu list
            $block[1] = $TEXT['MAIN'];
        }

        // Load css files with jquery 
        // Include jscalendar-setup
        $jscal_use_time = true; // tell to use a clock, too
        require_once WB_PATH . "/include/jscalendar/wb-setup.php";

        // Get display name of person who last modified the page
        $user = $admin->get_user_details($aPage['modified_by']);

        // Convert the unix ts for modified_when to human a readable form
        if($aPage['modified_when'] != 0) {
            $modified_ts = gmdate(TIME_FORMAT.', '.DATE_FORMAT, $aPage['modified_when']+TIMEZONE);
        } else {
            $modified_ts = 'Unknown';
        }

        // Setup template object, parse vars to it, then parse it
        // Create new template object
        $oTemplate = new Template(dirname($admin->correct_theme_source('pages_sections.htt')));
        $oTemplate->set_file('page', 'pages_sections.htt');
        
        $oTemplate->set_block('page',          'main_block',     'main');
        $oTemplate->set_block('main_block',    'module_block',   'module_list');
        $oTemplate->set_block('main_block',    'section_block',  'section_list');
        $oTemplate->set_block('section_block', 'block_block',    'block_list');
        $oTemplate->set_block('main_block',    'calendar_block', 'calendar_list');

        // set first defaults and messages
        $oTemplate->set_var(
            array(
                'ADMIN_URL'               => ADMIN_URL,
                'WB_URL'                  => WB_URL,
                'THEME_URL'               => THEME_URL,
                'FTAN'                    => $admin->getFTAN(),
                'PAGE_ID'                 => $aPage['page_id'],
                'PAGE_IDKEY'              => $aPage['page_id'],
                'PAGE_TITLE'              => $aPage['page_title'],
                'MENU_TITLE'              => $aPage['menu_title'],
                'MODIFIED_BY'             => $user['display_name'],
                'MODIFIED_BY_USERNAME'    => $user['username'],  
                'MODIFIED_WHEN'           => $modified_ts,  
                
                // Insert language text and messages                
                'TEXT_ID'                 => 'ID',
                'TEXT_PAGE'               => $TEXT['PAGE'],
                'TEXT_TYPE'               => $TEXT['TYPE'],
                'TEXT_BLOCK'              => $TEXT['BLOCK'],
                'TEXT_ACTIONS'            => $TEXT['ACTIONS'],
                'TEXT_BACK'               => $TEXT['BACK'],  
                'TEXT_MANAGE_SECTIONS'    => $HEADING['MANAGE_SECTIONS'],
                'TEXT_ARE_YOU_SURE'       => $TEXT['ARE_YOU_SURE'],
                'TEXT_ADD'                => $TEXT['ADD'],
                'TEXT_ADD_SECTION'        => $TEXT['ADD_SECTION'],
                'TEXT_SAVE'               => $TEXT['SAVE'],
                'TEXTLINK_MODIFY_PAGE'    => $HEADING['MODIFY_PAGE'],
                'TEXT_CALENDAR'           => $TEXT['CALENDAR'],
                'TEXT_DELETE_DATE'        => $TEXT['DELETE_DATE'],
                'TEXT_MOVE_UP'            => $TEXT['MOVE_UP'],
                'TEXT_MOVE_DOWN'          => $TEXT['MOVE_DOWN'],            
                'TEXT_CURRENT_PAGE'       => $TEXT['CURRENT_PAGE'],
                'HEADING_MANAGE_SECTIONS' => $HEADING['MANAGE_SECTIONS'],
                'HEADING_MODIFY_PAGE'     => $HEADING['MODIFY_PAGE'],
                'TEXT_CHANGE_SETTINGS'    => $TEXT['CHANGE_SETTINGS'],
                'TEXT_NAMESECTION'        => $TEXT['SECTION'] . ' ' . $TEXT['NAME'],
                'TEXT_PUBL_START_DATE'    => $TEXT['PUBL_START_DATE'],
                'TEXT_PUBL_END_DATE'      => $TEXT['PUBL_END_DATE'],
                'LAST_MODIFIED'           => $MESSAGE['PAGES_LAST_MODIFIED'],
                'VAR_PAGE_TITLE'          => $aPage['page_title'],
                'SETTINGS_LINK'           => ADMIN_URL.'/pages/settings.php?page_id='.$aPage['page_id'],
                'MODIFY_LINK'             => ADMIN_URL.'/pages/modify.php?page_id=' . $aPage['page_id'],
            )
        );

        $sSql = 'SELECT * FROM `{TP}sections` WHERE `page_id` = '.$page_id.' ORDER BY `position` ASC';
        $rSections = $database->query($sSql);

        $iSectionsCount = $rSections->numRows();
        if ($iSectionsCount > 0) {
            while ($section = $rSections->fetchRow(MYSQL_ASSOC)) {
                if (!is_numeric(array_search($section['module'], $module_permissions))) {
                    // Get the modules real name                    
                    $sLinkEditSection = '';
                    $sSql = 'SELECT `name` FROM `{TP}addons` WHERE `directory` = "'.$section['module'].'"';
                    if (!$database->get_one($sSql) || !file_exists(WB_PATH . '/modules/' . $section['module'])) {
                        $sLinkEditSection = '<span class="module_disabled">' . $section['module'] . '</span>';
                    } 
                    
                    // Get correct section anchor
                    // We definitely need a section anchor in the Backend (no matter if it's set to empty for the Frontend)
                    // Therefore, if SEC_ANCHOR is empty, in the backend we will use 'sec_anchor_' instead.
                    $sSecAnchor = '#'.(defined('SEC_ANCHOR') && SEC_ANCHOR ? SEC_ANCHOR : 'sec_anchor_');
                    
                    // Prepare the Section Link       
                    $sLinkTpl = '<a id="sid{SECTION_ID}" href="{LINK}{EOS}{SEC_ANCHOR}">{MODULE_NAME}</a>';
                    $aReplacements = array(
                        'SECTION_ID'  => $section['section_id'], 
                        'MODULE_NAME' => $admin->get_module_name($section['module']), 
                        'LINK'        => ADMIN_URL.'/pages/modify.php?page_id='.$aPage['page_id'], 
                        'EOS'         => (defined('EDIT_ONE_SECTION') && EDIT_ONE_SECTION) ? '&amp;wysiwyg=' . $section['section_id'] : '', 
                        'SEC_ANCHOR'  => $sSecAnchor . $section['section_id'], 
                    );
                    
                    if (SECTION_BLOCKS) {
                        if ($sLinkEditSection == '')                             
                            $sLinkEditSection = replace_vars($sLinkTpl, $aReplacements, '{%s}');
                        
                        $input_attribute = 'input_normal';
                        $oTemplate->set_var(
                            array(
                                'STYLE_DISPLAY_SECTION_BLOCK'    => ' style="visibility:visible;"',
                                'NAME_SIZE'                      => 300,
                                'INPUT_ATTRIBUTE'                => $input_attribute,
                                'VAR_SECTION_ID'                 => $section['section_id'],
                                'VAR_SECTION_IDKEY'              => $admin->getIDKEY($section['section_id']),
                                'VAR_POSITION'                   => $section['position'],
                                'LINK_MODIFY_URL_VAR_MODUL_NAME' => $sLinkEditSection,
                                'SELECT'                         => '',
                                'SET_NONE_DISPLAY_OPTION'        => '',
                            )
                        );
                        // Add block options to the section_list
                        $oTemplate->clear_var('block_list');
                        foreach ($block as $number => $name) {
                            $oTemplate->set_var('NAME', htmlentities(strip_tags($name)));
                            $oTemplate->set_var('VALUE', $number);
                            $oTemplate->set_var('SIZE', 1);
                            if ($section['block'] == $number) {
                                $oTemplate->set_var('SELECTED', ' selected="selected"');
                            } else {
                                $oTemplate->set_var('SELECTED', '');
                            }
                            $oTemplate->parse('block_list', 'block_block', true);
                        }
                    } else {
                        if ($sLinkEditSection == '') 
                            $sLinkEditSection = replace_vars($sLinkTpl, $aReplacements, '{%s}');
                            
                        $input_attribute = 'input_normal';
                        $oTemplate->set_var(
                            array(
                                'STYLE_DISPLAY_SECTION_BLOCK'    => ' style="visibility:hidden;"',
                                'NAME_SIZE'                      => 300,
                                'INPUT_ATTRIBUTE'                => $input_attribute,
                                'VAR_SECTION_ID'                 => $section['section_id'],
                                'VAR_SECTION_IDKEY'              => $admin->getIDKEY($section['section_id']),
                                'VAR_POSITION'                   => $section['position'],
                                'LINK_MODIFY_URL_VAR_MODUL_NAME' => $sLinkEditSection,
                                'NAME'                           => htmlentities(strip_tags($block[1])),
                                'VALUE'                          => 1,
                                'SET_NONE_DISPLAY_OPTION'        => '',
                            )
                        );
                    }
                    // named sections patch
                    $oTemplate->set_var('NAMESECTION', $section['namesection']);
                    // Insert icon and images
                    $oTemplate->set_var(array(
                        'CLOCK_16_PNG'     => 'clock_16.png',
                        'CLOCK_DEL_16_PNG' => 'clock_del_16.png',
                        'DELETE_16_PNG'    => 'delete_16.png',
                    )
                    );
                    // set calendar start values
                    if ($section['publ_start'] == 0) {
                        $oTemplate->set_var('VALUE_PUBL_START', '');
                    } else {
                        $oTemplate->set_var('VALUE_PUBL_START', date($jscal_format, $section['publ_start']));
                    }
                    // set calendar start values
                    if ($section['publ_end'] == 0) {
                        $oTemplate->set_var('VALUE_PUBL_END', '');
                    } else {
                        $oTemplate->set_var('VALUE_PUBL_END', date($jscal_format, $section['publ_end']));
                    }
                    // Insert icons up and down
                    if ($section['position'] != 1) {
                        $oTemplate->set_var(
                            'VAR_MOVE_UP_URL',
                            '<a href="' . ADMIN_URL . '/pages/move_up.php?page_id=' . $page_id . '&amp;section_id=' . $section['section_id'] . '">
                            <img src="' . THEME_URL . '/images/up_16.png" alt="{TEXT_MOVE_UP}" />
                            </a>'
                        );
                    } else {
                        $oTemplate->set_var('VAR_MOVE_UP_URL', '');
                    }
                    if ($section['position'] != $iSectionsCount) {
                        $oTemplate->set_var(
                            'VAR_MOVE_DOWN_URL',
                            '<a href="' . ADMIN_URL . '/pages/move_down.php?page_id=' . $page_id . '&amp;section_id=' . $section['section_id'] . '">
                            <img src="' . THEME_URL . '/images/down_16.png" alt="{TEXT_MOVE_DOWN}" />
                            </a>'
                        );
                    } else {
                        $oTemplate->set_var('VAR_MOVE_DOWN_URL', '');
                    }
                } else {
                    continue;
                }

                $oTemplate->set_var(array(
                    'DISPLAY_DEBUG'      => ' style="visibility="visible;"',
                    'TEXT_SID'           => 'SID',
                    'DEBUG_COLSPAN_SIZE' => 9,
                )
                );
                if ($debug) {
                    $oTemplate->set_var(array(
                        'DISPLAY_DEBUG' => ' style="visibility="visible;"',
                        'TEXT_PID'      => 'PID',
                        'TEXT_SID'      => 'SID',
                        'POSITION'      => $section['position'],
                    )
                    );
                } else {
                    $oTemplate->set_var(array(
                        'DISPLAY_DEBUG' => ' style="display:none;"',
                        'TEXT_PID'      => '',
                        'POSITION'      => '',
                    )
                    );
                }
                $oTemplate->parse('section_list', 'section_block', true);
            }
        }

        // Now add the calendars -- remember to set the range to [1970, 2037] if the date is used as timestamp!
        // The loop is simply a copy from above.
        $sSql = 'SELECT `section_id`,`module` FROM `{TP}sections` WHERE page_id = '.$page_id.' ORDER BY `position` ASC';
        $rSections = $database->query($sSql);

        $iSectionsCount = $rSections->numRows();
        if ($iSectionsCount > 0) {
            while ($section = $rSections->fetchRow(MYSQL_ASSOC)) {
                // Get the modules real name
                $sSql = 'SELECT `name` FROM `{TP}addons` WHERE `directory` = "'.$section['module'].'"';
                $module_name = $database->get_one($sSql);

                if (!is_numeric(array_search($section['module'], $module_permissions))) {
                    $oTemplate->set_var(array(
                        'jscal_ifformat' => $jscal_ifformat,
                        'jscal_firstday' => $jscal_firstday,
                        'jscal_today'    => $jscal_today,
                        'start_date'     => 'start_date' . $section['section_id'],
                        'end_date'       => 'end_date' . $section['section_id'],
                        'trigger_start'  => 'trigger_start' . $section['section_id'],
                        'trigger_end'    => 'trigger_stop' . $section['section_id'],
                        'showsTime'      => (isset($jscal_use_time) && $jscal_use_time == true) ? "true" : "false",
                        'timeFormat'     => "24",
                        )
                    );
                }
                $oTemplate->parse('calendar_list', 'calendar_block', true);
            }
        }

        // Work-out if we should show the "Add Section" form
      
        $sSql = "SELECT `section_id` FROM `{TP}sections` WHERE `page_id` = ".$page_id." AND `module` = 'menu_link'";
        $rSections = $database->query($sSql);
        if ($rSections->numRows() == 0) {
            // Query for all the Modules which should appear in the dropdown menu
            $sWhereAnd = " AND `directory` != 'menu_link' ";
            if(defined("MENU_LINK_TRANSFORMER") && MENU_LINK_TRANSFORMER == true){
                $sWhereAnd = "";
            }
            $sSql = "SELECT `name`,`directory`,`type` FROM `{TP}addons` 
                        WHERE `type` = 'module' AND `function` LIKE '%page%'
                        ".$sWhereAnd." 
                        ORDER BY `name`";
            $rResult = $database->query($sSql);
            
            if ($rResult->numRows() > 0) {
                while ($module = $rResult->fetchRow(MYSQL_ASSOC)) {
                    // Check if user is allowed to use this module   echo  $module['directory'],'<br />';
                    if (!is_numeric(array_search($module['directory'], $module_permissions))) {
                        $oTemplate->set_var('VALUE', $module['directory']);
                        $oTemplate->set_var('NAME', $admin->get_module_name($module['directory']));
                        if ($module['directory'] == 'wysiwyg') {
                            $oTemplate->set_var('SELECTED', ' selected="selected"');
                        } else {
                            $oTemplate->set_var('SELECTED', '');
                        }
                        $oTemplate->parse('module_list', 'module_block', true);
                    } else {
                        continue;
                    }
                }
            }
        }

        $oTemplate->parse('main', 'main_block', false);
        $oTemplate->pparse('output', 'page');
        // include the required file for Javascript admin
        $sFile = WB_PATH . '/modules/jsadmin/jsadmin_backend_include.php';
        if (file_exists($sFile)) include $sFile;
        break;
}

// Print admin footer
$admin->print_footer();
