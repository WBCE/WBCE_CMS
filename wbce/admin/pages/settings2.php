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
require '../../config.php';

// suppress to print the header, so no new FTAN will be set
$admin = new admin('Pages', 'pages_settings',false);

// Get page id
if (!isset($_POST['page_id']) || !is_numeric($_POST['page_id'])) {
    header("Location: index.php");
    exit(0);
} else {
    $page_id = (int)$_POST['page_id'];
}

$pagetree_url = ADMIN_URL.'/pages/index.php';
$target_url   = ADMIN_URL.'/pages/settings.php?page_id='.$page_id;

if (!$admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'],$target_url);
}
// After check print the header
$admin->print_header();


// Get POST values from submitted form
$page_title     = remove_droplet_brackets($admin->get_post_escaped('page_title'));
$menu_title     = remove_droplet_brackets($admin->get_post_escaped('menu_title'));
$the_link       = remove_droplet_brackets($admin->get_post_escaped('link'));
$description    = remove_droplet_brackets($admin->add_slashes($admin->get_post('description')));
$keywords       = remove_droplet_brackets($admin->add_slashes($admin->get_post('keywords')));
$page_code      = intval($admin->get_post('page_code')) ;
$parent         = intval($admin->get_post('parent')); // fix secunia 2010-91-3
$template       = preg_replace('/[^a-z0-9_-]/i', "", $admin->get_post('template')); // fix secunia 2010-93-3
$target         = preg_replace("/\W/", "", $admin->get_post('target'));
$admin_groups   = $admin->get_post_escaped('admin_groups');
$viewing_groups = $admin->get_post_escaped('viewing_groups');
$searching      = intval($admin->get_post('searching'));
$language       = strtoupper($admin->get_post('language'));
$language       = (preg_match('/^[A-Z]{2}$/', $language) ? $language : DEFAULT_LANGUAGE);
$menu           = intval($admin->get_post('menu')); // fix secunia 2010-91-3
$visibility     = $admin->get_post_escaped('visibility');
if (!in_array($visibility, array('public', 'private', 'registered', 'hidden', 'none'))) {
    // fix secunia 2010-93-3
    $visibility = 'public';
} 

// Validate data
if ($page_title == '' || substr($page_title,0,1)=='.') {
    $admin->print_error($MESSAGE['PAGES_BLANK_PAGE_TITLE']);
}
if ($menu_title == '' || substr($menu_title,0,1)=='.') {
    $admin->print_error($MESSAGE['PAGES_BLANK_MENU_TITLE']);
}
if ($the_link == '' || substr($the_link,0,1)=='.'){
    $admin->print_error($MESSAGE['PAGES_BLANK_LINK_TITLE']);
}

// Get existing permissions
$sSql = 'SELECT `parent`,`link`,`position`,`admin_groups`,`admin_users` FROM `{TP}pages` WHERE `page_id`='.$page_id;
$results = $database->query($sSql);

$results_array    = $results->fetchRow();
$old_parent       = $results_array['parent'];
$old_link         = $results_array['link'];
$old_position     = $results_array['position'];
$old_admin_groups = explode(',', str_replace('_', '', $results_array['admin_groups']));
$old_admin_users  = explode(',', str_replace('_', '', $results_array['admin_users']));

// Work-out if we should check for existing page_code
$field_set = $database->field_exists(TABLE_PREFIX.'pages', 'page_code');

$in_old_group = FALSE;
foreach($admin->get_groups_id() as $cur_gid) {
    if (in_array($cur_gid, $old_admin_groups)) {
        $in_old_group = TRUE;
    }
}
if ((!$in_old_group) && !is_numeric(array_search($admin->get_user_id(), $old_admin_users))) {
    $admin->print_error($MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS']);
}

// Setup admin groups
$admin_groups[] = 1;
$admin_groups = preg_replace("/[^\d,]/", "", implode(',', $admin_groups));
// Setup viewing groups
$viewing_groups[] = 1;
$viewing_groups = preg_replace("/[^\d,]/", "", implode(',', $viewing_groups));

// If needed, get new order
$position = $old_position;
if ($parent != $old_parent) {
    // Include ordering class
    require WB_PATH.'/framework/class.order.php';
    $order = new order(TABLE_PREFIX.'pages', 'position', 'page_id', 'parent');
    // Get new order
    $position = $order->get_new($parent);
    // Clean new order
    $order->clean($parent);
} 

// Work out level and root parent
$level = '0';
$root_parent = '0';
if ($parent != '0'){
    $level = level_count($parent)+1;
    $root_parent = root_parent($parent);
} 

// Work-out what the link should be
if ($parent == '0') {
    $link = '/'.page_filename($the_link);
    
    // rename menu titles: index && intro to prevent clashes with intro page feature and WB core file /pages/index.php
    if ($link == '/index' || $link == '/intro') {
        $link .= '_' .$page_id;
        $filename = WB_PATH.PAGES_DIRECTORY.'/'.page_filename($the_link).'_'.$page_id .PAGE_EXTENSION;
    } else {
        $filename = WB_PATH.PAGES_DIRECTORY.'/'.page_filename($the_link).PAGE_EXTENSION;
    }
} else {
    $sParentLink = $database->get_one('SELECT `link` FROM `{TP}pages` WHERE `page_id` = '.$parent); 
    $filename = WB_PATH.PAGES_DIRECTORY.$sParentLink.'/'.page_filename($the_link).PAGE_EXTENSION;
    make_dir(WB_PATH.PAGES_DIRECTORY.$sParentLink);
    $link = $sParentLink.'/'.page_filename($the_link);

}

// Check if a page with same page filename exists
// $database = new database();
$sSql = 'SELECT `page_id`, `page_title` FROM `{TP}pages` WHERE `link` = "'.$link.'" AND `page_id` != '.$page_id;
$get_same_page = $database->query($sSql);

if ($get_same_page->numRows() > 0){
    $admin->print_error($MESSAGE['PAGES_PAGE_EXISTS']);
}

// Update page with new order
$sSql = 'UPDATE `{TP}pages` SET `parent`='.$parent.', `position`='.$position.' WHERE `page_id`='.$page_id.'';
// $database = new database();
$database->query($sSql);


$aUpdate = array( 
    'page_id'        => $page_id,
    'parent'         => $parent,
    'page_title'     => $page_title,
    'menu_title'     => $menu_title,
    'menu'           => $menu,
    'level'          => $level,
    'page_trail'     => get_page_trail($page_id),
    'root_parent'    => $root_parent,
    'link'           => $link,
    'template'       => $template,
    'target'         => $target,
    'description'    => $description,
    'keywords'       => $keywords,
    'position'       => $position,
    'visibility'     => $visibility,
    'searching'      => $searching,
    'language'       => $language,
    'admin_groups'   => $admin_groups,
    'viewing_groups' => $viewing_groups
);
$bFieldSet = $field_set && (file_exists(WB_PATH.'/modules/mod_multilingual/update_keys.php'));
if ((defined('PAGE_LANGUAGES') && PAGE_LANGUAGES) && $bFieldSet){
    $aUpdate['page_code'] = (int)$page_code;
}
$database->updateRow('{TP}pages', 'page_id', $aUpdate);


$target_url = ADMIN_URL.'/pages/settings.php?page_id='.$page_id;
if ($database->is_error()){
    $admin->print_error($database->get_error(), $target_url );
}
// Clean old order if needed
if ($parent != $old_parent) {
    $order->clean($old_parent);
}

/* BEGIN page "access file" code */

// Create a new file in the /pages dir if title changed
if (!is_writable(WB_PATH.PAGES_DIRECTORY.'/')) {
    $admin->print_error($MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE']);
} else {
    $sOldFilename = WB_PATH.PAGES_DIRECTORY.$old_link.PAGE_EXTENSION;
    $sOldDirname  = WB_PATH.PAGES_DIRECTORY.$old_link.'/';
    if($visibility != 'none'){
        // First check if we need to create a new file
        if (($old_link != $link) || (!file_exists($sOldFilename))) {
            // Delete old file
            $sOldFilename = WB_PATH.PAGES_DIRECTORY.$old_link.PAGE_EXTENSION;
            if (file_exists($sOldFilename)) {
                unlink($sOldFilename);
            }
            // Create access file
            create_access_file($filename, $page_id, $level);
            // Move a directory for this page
            if (file_exists($sOldDirname) && is_dir($sOldDirname)) {
                rename($sOldDirname, WB_PATH.PAGES_DIRECTORY.$link.'/');
            }
            // Update any pages that had the old link with the new one
            $old_link_len = strlen($old_link);
            $sql = '';
            $query_subs = $database->query(
                "SELECT page_id, link, level 
                    FROM `{TP}pages`
                    WHERE `link` LIKE '%$old_link/%' 
                    ORDER BY LEVEL ASC"
            );

            if ($query_subs->numRows() > 0){
                while($sub = $query_subs->fetchRow()){
                    // Double-check to see if it contains old link
                    if (substr($sub['link'], 0, $old_link_len) == $old_link) {
                        // Get new link
                        $replace_this = $old_link;
                        $old_sub_link_len =strlen($sub['link']);
                        $new_sub_link = $link.'/'.substr($sub['link'],$old_link_len+1,$old_sub_link_len);
                        // Work out level
                        $new_sub_level = level_count($sub['page_id']);
                        // Update level and link
                        $database->query(
                            "UPDATE `{TP}pages`
                                SET `link = '$new_sub_link', `level` = '$new_sub_level' 
                                WHERE `page_id` = '".$sub['page_id']."'
                                LIMIT 1"
                        );
                        // Re-write the access file for this page
                        $old_subpage_file = WB_PATH.PAGES_DIRECTORY.$new_sub_link.PAGE_EXTENSION;
                        if (file_exists($old_subpage_file)) {
                            unlink($old_subpage_file);
                        }
                        $sAccessFileUrl = WB_PATH.PAGES_DIRECTORY.$new_sub_link.PAGE_EXTENSION;
                        create_access_file($sAccessFileUrl, $sub['page_id'], $new_sub_level);
                    }
                }
            }
        }
    } else {
        if (file_exists($sOldFilename)) {
            unlink($sOldFilename);
        }
    }
}

// Fix sub-pages page trail
fix_page_trail($page_id, $root_parent);

/* END page "access file" code */

// Check if there is a db error, otherwise say successful
if ($database->is_error()) {
    $admin->print_error($database->get_error(), $target_url );
} else {
    $admin->print_success($MESSAGE['PAGES_SAVED_SETTINGS'], $target_url );
}

// Print admin footer
$admin->print_footer();

/**
 * Functions
 */

// Function to fix page trail of subs
function fix_page_trail($parent,$root_parent) {
    // Get objects and vars from outside this function
    global $admin, $template, $database, $TEXT, $MESSAGE;
    // Get page list from database
    // $database = new database();
    $query = "SELECT page_id FROM {TP}pages WHERE parent = '$parent'";
    $get_pages = $database->query($query);
    // Insert values into main page list
    if ($get_pages->numRows() > 0){
        while($page = $get_pages->fetchRow()) {
            // Fix page trail
            $aUpdate = array(
                'page_id'    => $page['page_id'],
                'page_trail' => get_page_trail($page['page_id'])
            );
            if ($root_parent != 0){
                $aUpdate['root_parent'] = $root_parent;
            }            
            $database->updateRow('{TP}pages', 'page_id', $aUpdate);
            // Run this query on subs
            fix_page_trail($page['page_id'], $root_parent);
        }
    }
}

// function to remove_droplet_brackets from inputs
function remove_droplet_brackets($sStr){
    return str_replace(array("[[", "]]"), '', htmlspecialchars($sStr));
}