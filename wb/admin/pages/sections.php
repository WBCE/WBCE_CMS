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
 * @version         $Id: sections.php 5 2015-04-27 08:02:19Z luisehahne $
 * @filesource      $HeadURL: https://localhost:8443/svn/wb283Sp4/SP4/branches/wb/admin/pages/sections.php $
 * @lastmodified    $Date: 2015-04-27 10:02:19 +0200 (Mo, 27. Apr 2015) $
 *
 */

// Include config file
require('../../config.php');

// Make sure people are allowed to access this page
if(MANAGE_SECTIONS != 'enabled')
{
    header('Location: '.ADMIN_URL.'/pages/index.php');
    exit(0);
}
/* */
$debug = false; // to show position and section_id
If(!defined('DEBUG')) { define('DEBUG',$debug);}
// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');
// Create new admin object
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages_modify', false);

$action = 'show';
// Get page id
$requestMethod = '_'.strtoupper($_SERVER['REQUEST_METHOD']);
$page_id = intval((isset(${$requestMethod}['page_id'])) ? ${$requestMethod}['page_id'] : 0);
$action = ($page_id ? 'show' : $action);
// Get section id if there is one
$requestMethod = '_'.strtoupper($_SERVER['REQUEST_METHOD']);
$section_id = ((isset(${$requestMethod}['section_id'])) ? ${$requestMethod}['section_id']  : 0);
$action = ($section_id ? 'delete' : $action);
// Get module if there is one
$requestMethod = '_'.strtoupper($_SERVER['REQUEST_METHOD']);
$module = ((isset(${$requestMethod}['module'])) ? ${$requestMethod}['module']  : 0);
$action = ($module != '' ? 'add' : $action);
$admin_header = true;
$backlink = ADMIN_URL.'/pages/sections.php?page_id='.(int)$page_id;

switch ($action):
    case 'delete' :

        if( ( !($section_id = intval($admin->checkIDKEY('section_id', 0, $_SERVER['REQUEST_METHOD'])) )) )
        {
            if($admin_header) { $admin->print_header(); }
            $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'],$backlink);
        }

        $action = 'show';
        $sql  = 'SELECT `module` FROM `'.TABLE_PREFIX.'sections` ';
        $sql .= 'WHERE `section_id` ='.$section_id;
        if( ( ($modulname = $database->get_one($sql)) == $module) && ($section_id > 0 ) ) {
            // Include the modules delete file if it exists
            if(file_exists(WB_PATH.'/modules/'.$modulname.'/delete.php'))
            {
                require(WB_PATH.'/modules/'.$modulname.'/delete.php');
            }
            $sql  = 'DELETE FROM `'.TABLE_PREFIX.'sections` ';
            $sql .= 'WHERE `section_id` ='.(int)$section_id.' LIMIT 1';
            if( !$database->query($sql) ) {
                if($admin_header) { $admin->print_header(); }
                $admin->print_error($database->get_error(),$backlink);
            }  else {
                require_once(WB_PATH.'/framework/class.order.php');
                $order = new order(TABLE_PREFIX.'sections', 'position', 'section_id', 'page_id');
                $order->clean($page_id);
                $format = $TEXT['SECTION'].' %d  %s %s '.strtolower( $TEXT['DELETED']);
                $message = sprintf ($format,$section_id,strtoupper($modulname),strtolower($TEXT['SUCCESS']));
                if($admin_header) { $admin->print_header(); }
                $admin_header = false;
                unset($_POST);
                $admin->print_success($message, $backlink );
            }
        } else {
            if($admin_header) { $admin->print_header(); }
            $admin->print_error($module.' '.strtolower($TEXT['NOT_FOUND']),$backlink);
        }

        break;
    case 'add' :

        if (!$admin->checkFTAN())
        {
            $admin->print_header();
            $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'],$backlink);
        }
        $action = 'show';
        $module = preg_replace('/\W/', '', $module );  // fix secunia 2010-91-4
        require_once(WB_PATH.'/framework/class.order.php');
        // Get new order
        $order = new order(TABLE_PREFIX.'sections', 'position', 'section_id', 'page_id');
        $position = $order->get_new($page_id);
        // Insert module into DB
        $sql  = 'INSERT INTO `'.TABLE_PREFIX.'sections` SET ';
        $sql .= '`page_id` = '.(int)$page_id.', ';
        $sql .= '`module` = \''.$module.'\', ';
        $sql .= '`position` = '.(int)$position.', ';
        $sql .= '`block` = 1';
        if($database->query($sql)) {
            // Get the section id
            $section_id = $database->get_one("SELECT LAST_INSERT_ID()");
            // Include the selected modules add file if it exists
            if(file_exists(WB_PATH.'/modules/'.$module.'/add.php'))
            {
                require(WB_PATH.'/modules/'.$module.'/add.php');
            }
        } elseif ($database->is_error())  {
            if($admin_header) { $admin->print_header(); }
            $admin->print_error($database->get_error());
        }
        break;
    default:
        break;
endswitch;

switch ($action):
    default:

        if($admin_header) { $admin->print_header(); }
        // Get perms
        $sql  = 'SELECT `admin_groups`,`admin_users` FROM `'.TABLE_PREFIX.'pages` ';
        $sql .= 'WHERE `page_id` = '.$page_id;
        $results = $database->query($sql);

        $results_array = $results->fetchRow();
        $old_admin_groups = explode(',', $results_array['admin_groups']);
        $old_admin_users = explode(',', $results_array['admin_users']);
        $in_old_group = FALSE;
        foreach($admin->get_groups_id() as $cur_gid)
        {
            if (in_array($cur_gid, $old_admin_groups))
            {
                $in_old_group = TRUE;
            }
        }
        if((!$in_old_group) && !is_numeric(array_search($admin->get_user_id(), $old_admin_users)))
        {
            $admin->print_header();
            $admin->print_error($MESSAGE['PAGES']['INSUFFICIENT_PERMISSIONS']);
        }

        // Get page details
        $sql  = 'SELECT * FROM `'.TABLE_PREFIX.'pages` ';
        $sql .= 'WHERE `page_id` = '.$page_id;
        $results = $database->query($sql);

        if($database->is_error())
        {
            // $admin->print_header();
            $admin->print_error($database->get_error());
        }
        if($results->numRows() == 0)
        {
            // $admin->print_header();
            $admin->print_error($MESSAGE['PAGES']['NOT_FOUND']);
        }
        $results_array = $results->fetchRow();

        // Set module permissions
        $module_permissions = $_SESSION['MODULE_PERMISSIONS'];

        // Unset block var
        unset($block);
        // Include template info file (if it exists)
        if($results_array['template'] != '')
        {
            $template_location = WB_PATH.'/templates/'.$results_array['template'].'/info.php';
        } else {
            $template_location = WB_PATH.'/templates/'.DEFAULT_TEMPLATE.'/info.php';
        }
        if(file_exists($template_location))
        {
            require($template_location);
        }
        // Check if $menu is set
        if(!isset($block[1]) || $block[1] == '')
        {
            // Make our own menu list
            $block[1] = $TEXT['MAIN'];
        }

        /*-- load css files with jquery --*/
        // include jscalendar-setup
        $jscal_use_time = true; // whether to use a clock, too
        require_once(WB_PATH."/include/jscalendar/wb-setup.php");

        // Setup template object, parse vars to it, then parse it
        // Create new template object
        $tpl = new Template(dirname($admin->correct_theme_source('pages_sections.htt')));
        // $template->debug = true;
        $tpl->set_file('page', 'pages_sections.htt');
        $tpl->set_block('page', 'main_block', 'main');
        $tpl->set_block('main_block', 'module_block', 'module_list');
        $tpl->set_block('main_block', 'section_block', 'section_list');
        $tpl->set_block('section_block', 'block_block', 'block_list');
        $tpl->set_block('main_block', 'calendar_block', 'calendar_list');
        $tpl->set_var('FTAN', $admin->getFTAN());

        // set first defaults and messages
        $tpl->set_var(array(
                        'PAGE_ID' => $results_array['page_id'],
                        // 'PAGE_IDKEY' => $admin->getIDKEY($results_array['page_id']),
                        'PAGE_IDKEY' => $results_array['page_id'],
                        'TEXT_PAGE' => $TEXT['PAGE'],
                        'PAGE_TITLE' => ($results_array['page_title']),
                        'MENU_TITLE' => ($results_array['menu_title']),
                        'TEXT_CURRENT_PAGE' => $TEXT['CURRENT_PAGE'],
                        'HEADING_MANAGE_SECTIONS' => $HEADING['MANAGE_SECTIONS'],
                        'HEADING_MODIFY_PAGE' => $HEADING['MODIFY_PAGE'],
                        'TEXT_CHANGE_SETTINGS' => $TEXT['CHANGE_SETTINGS'],
                        'TEXT_ADD_SECTION' => $TEXT['ADD_SECTION'],
                        'TEXT_ID' => 'ID',
                        'TEXT_TYPE' => $TEXT['TYPE'],
                        'TEXT_BLOCK' => $TEXT['BLOCK'],
                        'TEXT_PUBL_START_DATE' => $TEXT{'PUBL_START_DATE'},
                        'TEXT_PUBL_END_DATE' => $TEXT['PUBL_END_DATE'],
                        'TEXT_ACTIONS' => $TEXT['ACTIONS'],
                        'ADMIN_URL' => ADMIN_URL,
                        'WB_URL' => WB_URL,
                        'THEME_URL' => THEME_URL
                        )
                    );

        // Insert variables
        $tpl->set_var(array(
                        'PAGE_ID' => $results_array['page_id'],
                        // 'PAGE_IDKEY' => $admin->getIDKEY($results_array['page_id']),
                        'PAGE_IDKEY' => $results_array['page_id'],
                        'VAR_PAGE_TITLE' => $results_array['page_title'],
                        'SETTINGS_LINK' => ADMIN_URL.'/pages/settings.php?page_id='.$results_array['page_id'],
                        'MODIFY_LINK' => ADMIN_URL.'/pages/modify.php?page_id='.$results_array['page_id']
                        )
                    );

        $sql  = 'SELECT `section_id`,`module`,`position`,`block`,`publ_start`,`publ_end` ';
        $sql .= 'FROM `'.TABLE_PREFIX.'sections` ';
        $sql .= 'WHERE `page_id` = '.$page_id.' ';
        $sql .= 'ORDER BY `position` ASC';
        $query_sections = $database->query($sql);

        if($query_sections->numRows() > 0)
        {
            $num_sections = $query_sections->numRows();
            while($section = $query_sections->fetchRow())
            {
                if(!is_numeric(array_search($section['module'], $module_permissions)))
                {
                    // Get the modules real name
                    $sql = 'SELECT `name` FROM `'.TABLE_PREFIX.'addons` ';
                    $sql .= 'WHERE `directory` = "'.$section['module'].'"';
                    if(!$database->get_one($sql) || !file_exists(WB_PATH.'/modules/'.$section['module']))
                    {
                        $edit_page = '<span class="module_disabled">'.$section['module'].'</span>';
                    }else
                    {
                        $edit_page = '';
                    }
                    $sec_anchor = (defined( 'SEC_ANCHOR' ) && ( SEC_ANCHOR != '' )  ? SEC_ANCHOR : '');
                    $edit_page_0 = '<a id="sid'.$section['section_id'].'" href="'.ADMIN_URL.'/pages/modify.php?page_id='.$results_array['page_id'];
                    $edit_page_1  = ($sec_anchor!='') ? '#'.$sec_anchor.$section['section_id'].'">' : '">';
                    $edit_page_1 .= $section['module'].'</a>';
                    if(SECTION_BLOCKS)
                    {
                        if($edit_page == '')
                        {
                            if(defined('EDIT_ONE_SECTION') && EDIT_ONE_SECTION)
                            {
                                $edit_page = $edit_page_0.'&amp;wysiwyg='.$section['section_id'].$edit_page_1;
                            } else {
                                $edit_page = $edit_page_0.$edit_page_1;
                            }
                        }
                        $input_attribute = 'input_normal';
                        $tpl->set_var(array(
                                'STYLE_DISPLAY_SECTION_BLOCK' => ' style="visibility:visible;"',
                                'NAME_SIZE' => 300,
                                'INPUT_ATTRIBUTE' => $input_attribute,
                                'VAR_SECTION_ID' => $section['section_id'],
                                'VAR_SECTION_IDKEY' => $admin->getIDKEY($section['section_id']),
                                // 'VAR_SECTION_IDKEY' => $section['section_id'],
                                'VAR_POSITION' => $section['position'],
                                'LINK_MODIFY_URL_VAR_MODUL_NAME' => $edit_page,
                                'SELECT' => '',
                                'SET_NONE_DISPLAY_OPTION' => ''
                                )
                            );
                        // Add block options to the section_list
                        $tpl->clear_var('block_list');
                        foreach($block AS $number => $name)
                        {
                            $tpl->set_var('NAME', htmlentities(strip_tags($name)));
                            $tpl->set_var('VALUE', $number);
                            $tpl->set_var('SIZE', 1);
                            if($section['block'] == $number)
                            {
                                $tpl->set_var('SELECTED', ' selected="selected"');
                            } else {
                                $tpl->set_var('SELECTED', '');
                            }
                            $tpl->parse('block_list', 'block_block', true);
                        }
                    } else {
                        if($edit_page == '')
                        {
                            $edit_page = $edit_page_0.'#wb_'.$edit_page_1;
                        }
                        $input_attribute = 'input_normal';
                        $tpl->set_var(array(
                                'STYLE_DISPLAY_SECTION_BLOCK' => ' style="visibility:hidden;"',
                                'NAME_SIZE' => 300,
                                'INPUT_ATTRIBUTE' => $input_attribute,
                                'VAR_SECTION_ID' => $section['section_id'],
                                'VAR_SECTION_IDKEY' => $admin->getIDKEY($section['section_id']),
                                // 'VAR_SECTION_IDKEY' => $section['section_id'],
                                'VAR_POSITION' => $section['position'],
                                'LINK_MODIFY_URL_VAR_MODUL_NAME' => $edit_page,
                                'NAME' => htmlentities(strip_tags($block[1])),
                                'VALUE' => 1,
                                'SET_NONE_DISPLAY_OPTION' => ''
                                )
                            );
                    }
                    // Insert icon and images
                    $tpl->set_var(array(
                                'CLOCK_16_PNG' => 'clock_16.png',
                                'CLOCK_DEL_16_PNG' => 'clock_del_16.png',
                                'DELETE_16_PNG' => 'delete_16.png'
                                )
                            );
                    // set calendar start values
                    if($section['publ_start']==0)
                    {
                        $tpl->set_var('VALUE_PUBL_START', '');
                    } else {
                        $tpl->set_var('VALUE_PUBL_START', date($jscal_format, $section['publ_start']));
                    }
                    // set calendar start values
                    if($section['publ_end']==0)
                    {
                        $tpl->set_var('VALUE_PUBL_END', '');
                    } else {
                        $tpl->set_var('VALUE_PUBL_END', date($jscal_format, $section['publ_end']));
                    }
                    // Insert icons up and down
                    if($section['position'] != 1 )
                    {
                        $tpl->set_var(
                                    'VAR_MOVE_UP_URL',
                                    '<a href="'.ADMIN_URL.'/pages/move_up.php?page_id='.$page_id.'&amp;section_id='.$section['section_id'].'">
                                    <img src="'.THEME_URL.'/images/up_16.png" alt="{TEXT_MOVE_UP}" />
                                    </a>' );
                    } else {
                        $tpl->set_var(array(
                                    'VAR_MOVE_UP_URL' => ''
                                    )
                                );
                    }
                    if($section['position'] != $num_sections ) {
                        $tpl->set_var(
                                    'VAR_MOVE_DOWN_URL',
                                    '<a href="'.ADMIN_URL.'/pages/move_down.php?page_id='.$page_id.'&amp;section_id='.$section['section_id'].'">
                                    <img src="'.THEME_URL.'/images/down_16.png" alt="{TEXT_MOVE_DOWN}" />
                                    </a>' );
                    } else {
                        $tpl->set_var(array(
                                    'VAR_MOVE_DOWN_URL' => ''
                                    )
                                );
                    }
                } else {
                  continue;
                }

                    $tpl->set_var(array(
                                    'DISPLAY_DEBUG' => ' style="visibility="visible;"',
                                    'TEXT_SID' => 'SID',
                                    'DEBUG_COLSPAN_SIZE' => 9
                                    )
                                );
                if($debug)
                {
                    $tpl->set_var(array(
                                    'DISPLAY_DEBUG' => ' style="visibility="visible;"',
                                    'TEXT_PID' => 'PID',
                                    'TEXT_SID' => 'SID',
                                    'POSITION' => $section['position']
                                    )
                                );
                } else {
                    $tpl->set_var(array(
                                    'DISPLAY_DEBUG' => ' style="display:none;"',
                                    'TEXT_PID' => '',
                                    'POSITION' => ''
                                    )
                                );
                }
                $tpl->parse('section_list', 'section_block', true);
            }
        }

        // now add the calendars -- remember to to set the range to [1970, 2037] if the date is used as timestamp!
        // the loop is simply a copy from above.
        $sql  = 'SELECT `section_id`,`module` FROM `'.TABLE_PREFIX.'sections` ';
        $sql .= 'WHERE page_id = '.$page_id.' ';
        $sql .= 'ORDER BY `position` ASC';
        $query_sections = $database->query($sql);

        if($query_sections->numRows() > 0)
        {
            $num_sections = $query_sections->numRows();
            while($section = $query_sections->fetchRow())
            {
                // Get the modules real name
                $sql  = 'SELECT `name` FROM `'.TABLE_PREFIX.'addons` ';
                $sql .= 'WHERE `directory` = "'.$section['module'].'"';
                $module_name = $database->get_one($sql);

                if(!is_numeric(array_search($section['module'], $module_permissions)))
                {
                    $tpl->set_var(array(
                                'jscal_ifformat' => $jscal_ifformat,
                                'jscal_firstday' => $jscal_firstday,
                                'jscal_today' => $jscal_today,
                                'start_date' => 'start_date'.$section['section_id'],
                                'end_date' => 'end_date'.$section['section_id'],
                                'trigger_start' => 'trigger_start'.$section['section_id'],
                                'trigger_end' => 'trigger_stop'.$section['section_id']
                                )
                            );
                    if(isset($jscal_use_time) && $jscal_use_time==TRUE) {
                        $tpl->set_var(array(
                                'showsTime' => "true",
                                'timeFormat' => "24"
                                )
                            );
                    }  else {
                        $tpl->set_var(array(
                                'showsTime' => "false",
                                'timeFormat' => "24"
                                )
                            );
                    }
                }
                $tpl->parse('calendar_list', 'calendar_block', true);
            }
        }

        // Work-out if we should show the "Add Section" form
        $sql  = 'SELECT `section_id` FROM `'.TABLE_PREFIX.'sections` ';
        $sql .= 'WHERE `page_id` = '.$page_id.' AND `module` = "menu_link"';
        $query_sections = $database->query($sql);
        if($query_sections->numRows() == 0)
        {
            // Modules list
            $sql  = 'SELECT `name`,`directory`,`type` FROM `'.TABLE_PREFIX.'addons` ';
            $sql .= 'WHERE `type` = "module" AND `function` = "page" AND `directory` != "menu_link" ';
            $sql .= 'ORDER BY `name`';
            $result = $database->query($sql);
        // if(DEBUG && $database->is_error()) { $admin->print_error($database->get_error()); }

            if($result->numRows() > 0)
            {
                while ($module = $result->fetchRow())
                {
                    // Check if user is allowed to use this module   echo  $module['directory'],'<br />';
                    if(!is_numeric(array_search($module['directory'], $module_permissions)))
                    {
                        $tpl->set_var('VALUE', $module['directory']);
                        $tpl->set_var('NAME', $module['name']);
                        if($module['directory'] == 'wysiwyg')
                        {
                            $tpl->set_var('SELECTED', ' selected="selected"');
                        } else {
                            $tpl->set_var('SELECTED', '');
                        }
                        $tpl->parse('module_list', 'module_block', true);
                    } else {
                      continue;
                    }
                }
            }
        }
        // Insert language text and messages
        $tpl->set_var(array(
                            'TEXT_MANAGE_SECTIONS' => $HEADING['MANAGE_SECTIONS'],
                            'TEXT_ARE_YOU_SURE' => $TEXT['ARE_YOU_SURE'],
                            'TEXT_TYPE' => $TEXT['TYPE'],
                            'TEXT_ADD' => $TEXT['ADD'],
                            'TEXT_SAVE' =>  $TEXT['SAVE'],
                            'TEXTLINK_MODIFY_PAGE' => $HEADING['MODIFY_PAGE'],
                            'TEXT_CALENDAR' => $TEXT['CALENDAR'],
                            'TEXT_DELETE_DATE' => $TEXT['DELETE_DATE'],
                            'TEXT_ADD_SECTION' => $TEXT['ADD_SECTION'],
                            'TEXT_MOVE_UP' => $TEXT['MOVE_UP'],
                            'TEXT_MOVE_DOWN' => $TEXT['MOVE_DOWN']
                            )
                        );
        $tpl->parse('main', 'main_block', false);
        $tpl->pparse('output', 'page');
        // include the required file for Javascript admin
        if(file_exists(WB_PATH.'/modules/jsadmin/jsadmin_backend_include.php'))
        {
            include(WB_PATH.'/modules/jsadmin/jsadmin_backend_include.php');
        }
        break;
endswitch;

// Print admin footer
$admin->print_footer();
