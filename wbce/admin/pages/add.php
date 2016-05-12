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

// Create new admin object and print admin header
require('../../config.php');

// suppress to print the header, so no new FTAN will be set
$admin = new admin('Pages', 'pages_add', false);
if (!$admin->checkFTAN())
{
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
}

// Include the WB functions file
 

// Get values
$title = $admin->get_post_escaped('title');
$title = htmlspecialchars($title);

$module = preg_replace('/[^a-z0-9_-]/i', "", $admin->get_post('type')); // fix secunia 2010-93-4

$parent = intval($admin->get_post('parent')); // fix secunia 2010-91-2

$visibility = $admin->get_post('visibility');
if (!in_array($visibility, array('public', 'private', 'registered', 'hidden', 'none'))) {$visibility = 'public';} // fix secunia 2010-91-2

// Indirect validation as he checks id all listed groups exist 
// If they do not exist he aborts completely 
$admin_groups = $admin->get_post('admin_groups');
$viewing_groups = $admin->get_post('viewing_groups');

// Work-out if we should check for existing page_code
$field_set = $database->field_exists(TABLE_PREFIX.'pages', 'page_code');

// add Admin to admin and viewing-groups
$admin_groups[] = 1;
$viewing_groups[] = 1;

// After check print the header
$admin->print_header();
// check parent page permissions:

if ($parent!=0) {
    // Write acces to parent, if not you may not create subpages 
    if (!$admin->get_page_permission($parent,'admin'))
    {
        $admin->print_error($MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS']);
    }

} 
// generic test if you may create pages
elseif (!$admin->get_permission('pages_add_l0','system'))
{
    $admin->print_error($MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS']);
}    

// check module permissions: whithout module permissions you may not create a page whith this module
if (!$admin->get_permission($module, 'module'))
{
    $admin->print_error($MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS']);
}    

// Validate title , no title or a title that starts whith a "." 
if($title == '' || substr($title,0,1)=='.')
{
    $admin->print_error($MESSAGE['PAGES_BLANK_PAGE_TITLE']);
}

// Check to see if page created has needed permissions
if(!in_array(1, $admin->get_groups_id()))
{
    $admin_perm_ok = false;
    foreach ($admin_groups as $adm_group)
    {
        if (in_array($adm_group, $admin->get_groups_id()))
        {
            $admin_perm_ok = true;
        } 
    }
    if ($admin_perm_ok == false)
    {
        $admin->print_error($MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS']);
    }
    $admin_perm_ok = false;
    foreach ($viewing_groups as $view_group)
    {
        if (in_array($view_group, $admin->get_groups_id()))
        {
            $admin_perm_ok = true;
        }
    }
    if ($admin_perm_ok == false)
    {
        $admin->print_error($MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS']);
    }
}

$admin_groups = implode(',', $admin_groups);
$viewing_groups = implode(',', $viewing_groups);

// Work-out what the link and page filename should be
if($parent == '0')
{
    $link = '/'.page_filename($title);
    // rename menu titles: index && intro to prevent clashes with intro page feature and WB core file /pages/index.php
    if($link == '/index' || $link == '/intro')
    {
        $link .= '_0';
        $filename = WB_PATH .PAGES_DIRECTORY .'/' .page_filename($title) .'_0' .PAGE_EXTENSION;
    } else {
        $filename = WB_PATH.PAGES_DIRECTORY.'/'.page_filename($title).PAGE_EXTENSION;
    }
} else {
    $parent_section = '';
    $hSql = 'SELECT `link` FROM `'.TABLE_PREFIX.'pages` WHERE `page_id` = '.$parent;
    $parent_section = $database->get_one($hSql);
    
    //echo "parentsection ".$parent_section."<br />";
    $filename = WB_PATH.PAGES_DIRECTORY.$parent_section.'/'.page_filename($title).PAGE_EXTENSION;
    make_dir(WB_PATH.PAGES_DIRECTORY.$parent_section);
    $link = $parent_section.'/'.page_filename($title);
    //echo "filename ".$filename."<br />";
    //echo "link ".$link."<br />";
}

// Check if a page with same page filename exists
$sql = 'SELECT `page_id` FROM `'.TABLE_PREFIX.'pages` '
     . 'WHERE `link`=\''.$link.'\'';
$get_same_page = $database->get_one($sql);
if (
    $get_same_page OR
    file_exists(WB_PATH.PAGES_DIRECTORY.$link.PAGE_EXTENSION) OR
    file_exists(WB_PATH.PAGES_DIRECTORY.$link.'/')
) {
    $admin->print_error($MESSAGE['PAGES_PAGE_EXISTS']);
}

// Include the ordering class

$order = new order(TABLE_PREFIX.'pages', 'position', 'page_id', 'parent');
// First clean order
$order->clean($parent);
// Get new order
$position = $order->get_new($parent);

// Work-out if the page parent (if selected) has a seperate template or language to the default
$query_parent = $database->query("SELECT template, language, menu FROM ".TABLE_PREFIX."pages WHERE page_id = '$parent'");
if ($query_parent->numRows() > 0) {
    $fetch_parent = $query_parent->fetchRow();
    $template = $fetch_parent['template'];
    $language = $fetch_parent['language'];
    $menu= $fetch_parent['menu'];
} else {
    $template = '';
    $language = DEFAULT_LANGUAGE;
}

// Insert page into pages table
$sql = 'INSERT INTO `'.TABLE_PREFIX.'pages` '
     . 'SET `parent`='.$parent.', '
     .     '`link` = \'\', '
     .     '`description`=\'\', '
     .     '`keywords`=\'\', '
     .     '`page_trail`=\'\', '
     .     '`admin_users`=\'\', '
     .     '`viewing_users`=\'\', '
     .     '`target`=\'_top\', '
     .     '`page_title`=\''.$title.'\', '
     .     '`menu_title`=\''.$title.'\', '
     .     '`template`=\''.$template.'\', '
     .     '`visibility`=\''.$visibility.'\', '
     .     '`position`='.$position.', '
     .     '`menu`='.$menu.', '
     .     '`language`=\''.$language.'\', '
     .     '`searching`=1, '
     .     '`modified_when`='.time().', '
     .     '`modified_by`='.$admin->get_user_id().', '
     .     '`admin_groups`=\''.$admin_groups.'\', '
     .     '`viewing_groups`=\''.$viewing_groups.'\'';
if (!$database->query($sql)) {
    $admin->print_error($database->get_error());
}
// Get the new page id
$page_id = $database->getLastInsertId();
// Work out level
$level = level_count($page_id);
// Work out root parent
$root_parent = root_parent($page_id);
// Work out page trail
$page_trail = get_page_trail($page_id);
// Update page with new level and link
$sql  = 'UPDATE `'.TABLE_PREFIX.'pages` SET ';
$sql .= '`root_parent` = '.$root_parent.', ';
$sql .= '`level` = '.$level.', ';
$sql .= '`link` = "'.$link.'", ';
$sql .= '`page_trail` = "'.$page_trail.'"';
$sql .= (defined('PAGE_LANGUAGES') && PAGE_LANGUAGES)
         && $field_set
         && ($language == DEFAULT_LANGUAGE)
         && (file_exists(WB_PATH.'/modules/mod_multilingual/update_keys.php')
         )
         ? ', `page_code` = '.(int)$page_id.' ' : ' ';
$sql .= 'WHERE `page_id` = '.$page_id;
if (!$database->query($sql)) {
    $admin->print_error($database->get_error());
}
// Create a new file in the /pages dir
create_access_file($filename, $page_id, $level);

// add position 1 to new page
$position = 1;

// Add new record into the sections table
$sql = 'INSERT INTO `'.TABLE_PREFIX.'sections` '
     . 'SET `page_id`='.$page_id.', '
     .     '`position`='.$position.', '
     .     '`module`=\''.$module.'\', '
     .     '`block`=1';
if (!$database->query($sql)) {
    $admin->print_error($database->get_error());
}
// Get the section id
if (!($section_id = $database->getLastInsertId())) {
    $admin->print_error($database->get_error());
}
// Include the selected modules add file if it exists
if(file_exists(WB_PATH.'/modules/'.$module.'/add.php')) {
    require(WB_PATH.'/modules/'.$module.'/add.php');
}
$admin->print_success($MESSAGE['PAGES_ADDED'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
// Print admin footer
$admin->print_footer();
