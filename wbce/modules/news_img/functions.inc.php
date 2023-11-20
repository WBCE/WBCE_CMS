<?php

require_once __DIR__.'/../../config.php';

// Include WB functions file
require_once WB_PATH.'/framework/functions.php';

// Include the ordering class
require_once WB_PATH.'/framework/class.order.php';

// load module language file
require __DIR__ . '/languages/EN.php';
$lang = __DIR__ . '/languages/' . LANGUAGE . '.php';
if(file_exists($lang)) {
    require $lang;
}

global $allowed_suffixes;
$allowed_suffixes = array('jpg','jpeg','gif','png','webp');

$mod_nwi_file_dir = WB_PATH.MEDIA_DIRECTORY.'/.news_img/';
$mod_nwi_thumb_dir = WB_PATH.MEDIA_DIRECTORY.'/.news_img/thumb/';

// ========== Tag Sorting helper ===============================================
 /**
 * sort an array
 *
 * @access public
 * @param  array   $array          - array to sort
 * @param  mixed   $index          - key to sort by
 * @param  string  $order          - 'asc' (default) || 'desc'
 * @param  boolean $natsort        - default: false
 * @param  boolean $case_sensitive - sort case sensitive; default: false
 *
 **/
function mod_nwi_tag_sort($array, $index, $order='asc', $natsort=false, $case_sensitive=false)
{
	if (is_array($array) && count($array)) {
		foreach (array_keys($array) as $key) {
			$temp[$key] = $array[$key][$index];
		}
		if (!$natsort) {
			($order=='asc') ? asort($temp) : arsort($temp);
		} else {
			($case_sensitive) ? natsort($temp) : natcasesort($temp);
			if ($order != 'asc') {
				$temp = array_reverse($temp, true);
			}
		}
		foreach (array_keys($temp) as $key) {
			(is_numeric($key)) ? $sorted[]=$array[$key] : $sorted[$key]=$array[$key];
		}
		return $sorted;
	}
	return $array;
}   // end function mod_nwi_tag_sort()

// ========== Groups ===========================================================

/**
 * get groups for section with ID $section_id
 *
 * @param   int   $section_id
 * @return
 **/
function mod_nwi_get_group(int $group_id) : array
{
    global $database;
    $query = $database->query(sprintf(
        "SELECT * FROM `%smod_news_img_groups` " .
        "WHERE `group_id`=%d",
        TABLE_PREFIX,$group_id
    ));
    if ($query->numRows() > 0) {
        return $query->fetchRow();
    }
    return array();
}   // end function mod_nwi_get_groups()

/**
 * get groups for section with ID $section_id
 *
 * @param   int   $section_id
 * @return
 **/
function mod_nwi_get_groups(int $section_id) : array
{
    global $database, $admin;
    $groups = array();
    $query = $database->query(sprintf(
        "SELECT * FROM `%smod_news_img_groups` " .
        "WHERE `section_id`=%d ORDER BY `position` ASC",
        TABLE_PREFIX,
        $section_id
    ));
    if ($query->numRows() > 0) {
        // Loop through groups
        while ($group = $query->fetchRow()) {
            $group['id_key'] = $admin->getIDKEY($group['group_id']);
            if (defined('WB_VERSION') && (version_compare(WB_VERSION, '2.8.3', '>'))) {
                $group['id_key'] = $group['group_id'];
            }
                $group['image'] = '';
            foreach(array_values(array('png','jpg','jpeg','gif','webp')) as $suffix) {
                if (file_exists(WB_PATH.MEDIA_DIRECTORY.'/.news_img/image'.$group['group_id'].'.'.$suffix)) {
                    $group['image'] = WB_URL.MEDIA_DIRECTORY.'/.news_img/image'.$group['group_id'].'.'.$suffix;
                }
            }
            $groups[] = $group;
        }
    }
    return $groups;
}   // end function mod_nwi_get_groups()

/**
 * get groups for all NWI sections on all pages
 *
 * result array:
 * page_id => array of groups
 *
 * @param   int   $section_id
 * @param   int   $page_id
 * @return  array
 **/
function mod_nwi_get_all_groups($section_id, $page_id)
{
    global $database, $admin;

    $groups = array();
    $pages = array();

    // get groups for this section
    if($section_id != 0) {
    $groups[$page_id] = array();
    $groups[$page_id][$section_id] = mod_nwi_get_groups(intval($section_id));
    }

    // get all other NWI sections
    $sections = mod_nwi_sections();
    foreach($sections as $sect) {
        if($sect['section_id'] != $section_id) { // skip current
            $groups[$sect['page_id']] = array();
            // groups
            $groups[$sect['page_id']][$sect['section_id']] = mod_nwi_get_groups(intval($sect['section_id']));
            // get page details for the dropdown
            $pid = intval($sect['page_id']);
            $page_title = "";
            $page_details = "";
            if ($pid != 0) { // find out the page title and print separator line
                $page_details = $admin->get_page_details($pid);
                if (!empty($page_details)) {
                    $page_title = isset($page_details['page_title'])
                                ? $page_details['page_title']
                                : ( isset($page_details['menu_title'])
                                    ? $page_details['menu_title']
                                    : "" );
                }
                $pages[$pid] = $page_title;
            }
        }
    }
    return array($groups,$pages);
}   // end function mod_nwi_get_all_groups()

// ========== Tags =============================================================

/**
 *
 * @access 
 * @return
 **/
function mod_nwi_get_tag($tag_id)
{
    global $database;
    $query_tags = $database->query(sprintf(
        "SELECT *, " .
        "(SELECT GROUP_CONCAT(`section_id`) FROM `%smod_news_img_tags_sections` AS `t2` WHERE `tag_id`=%d) as `sections` " .
        "FROM `%smod_news_img_tags` AS t1 ".
        "WHERE `tag_id`=%d",
        TABLE_PREFIX,intval($tag_id),TABLE_PREFIX,intval($tag_id)
    ));
    if (!empty($query_tags) && $query_tags->numRows() > 0) {
        return $query_tags->fetchRow();
    }
}   // end function mod_nwi_get_tag()

/**
 * get existing tags for current section
 * @param  int   $section_id
 * @param  bool  $alltags
 * @return array
 **/
function mod_nwi_get_tags($section_id=null,$alltags=false) {
    global $database;
    $tags = array();
    $where = "WHERE `section_id`=0";
    if(!empty($section_id)) {
        $section_id = intval($section_id);
        $where .= " OR `section_id` = '$section_id'";
    }
    if($alltags===true) {
        $where = null;
    }
    $query_tags = $database->query(sprintf(
        "SELECT * FROM `%smod_news_img_tags` AS t1 " .
        "JOIN `%smod_news_img_tags_sections` AS t2 " .
        "ON t1.tag_id=t2.tag_id ".
        $where, TABLE_PREFIX, TABLE_PREFIX
    ));
    if (!empty($query_tags) && $query_tags->numRows() > 0) {
        while($t = $query_tags->fetchRow()) {
            $tags[$t['tag_id']] = $t;
        }
    }
    return $tags;
}   // end function mod_nwi_get_tags()

/**
 * get tags for given post
 * @param  int   $post_id
 * @return array
 **/
function mod_nwi_get_tags_for_post($post_id)
{
    global $database;
    $tags = array();

    $query_tags = $database->query(sprintf(
        "SELECT  t1.*, t4.`page_id` " .
        "FROM `%smod_news_img_tags` AS t1 " .
        "JOIN `%smod_news_img_tags_posts` AS t2 " .
        "ON t1.`tag_id`=t2.`tag_id` ".
        "JOIN `%smod_news_img_posts` AS t3 ".
        "ON t2.`post_id`=t3.`post_id` ".
        "JOIN `%ssections` AS t4 ".
        "ON t3.`section_id`=t4.`section_id` ".
        "WHERE t2.`post_id`=%d",
        TABLE_PREFIX, TABLE_PREFIX, TABLE_PREFIX, TABLE_PREFIX, $post_id
    ));

    if (!empty($query_tags) && $query_tags->numRows() > 0) {
        while($t = $query_tags->fetchRow()) {
            $tags[$t['tag_id']] = $t;
        }
    }
	$tags = mod_nwi_tag_sort($tags, 'tag', 'asc', true);
    return $tags;
}   // end function mod_nwi_get_tags_for_post()


/**
 * check if tag is valid for given section
 * @param  int    $section_id
 * @param  string $tag
 * @return bool
 **/
function mod_nwi_tag_exists(int $section_id, string $tag)
{
    global $database;
    $sql   = sprintf(
        "SELECT * FROM `%smod_news_img_tags` AS t1 " .
        "JOIN `%smod_news_img_tags_sections` AS t2 " .
        "ON `t1`.`tag_id`=`t2`.`tag_id` " .
        "WHERE `tag`='%s' ".
        "AND (`t2`.`section_id`=%d OR `t2`.`section_id`=0)",
        TABLE_PREFIX, TABLE_PREFIX, $tag, $section_id
    );
    $query = $database->query($sql);
    if (!empty($query) && $query->numRows() > 0) {
        return true;
    }
    return false;
}   // end function mod_nwi_tag_exists()

// ========== Users ============================================================
/**
 *
 * @access 
 * @return
 **/
 function mod_nwi_users_get()
{
    global $database;
    $users = array();
    $query_users = $database->query(sprintf(
        "SELECT `user_id`,`username`,`display_name`,`email` FROM `%susers`",
        TABLE_PREFIX
    ));
    if(!empty($query_users) && $query_users->numRows() > 0) {
        while($user = $query_users->fetchRow()) {
            // Insert user info into users array
            $user_id = $user['user_id'];
            $users[$user_id]['username'] = $user['username'];
            $users[$user_id]['display_name'] = $user['display_name'];
            $users[$user_id]['email'] = $user['email'];
        }
    }
    return $users;
}   // end function mod_nwi_users_get()


// ========== Images ===========================================================

function mod_nwi_img_copy($source, $dest){
    if(is_dir($source)) {
        $dir_handle=opendir($source);
        while($file=readdir($dir_handle)){
            if($file!="." && $file!=".."){
                if(is_dir($source."/".$file)){
                    if(!is_dir($dest."/".$file)){
                        mkdir($dest."/".$file);
                    }
                    mod_nwi_img_copy($source."/".$file, $dest."/".$file);
                } else {
                    copy($source."/".$file, $dest."/".$file);
                }
            }
        }
        closedir($dir_handle);
    } else {
        if(file_exists($source))
            copy($source, $dest);
    }
}

function mod_nwi_img_get($pic_id)
{
    global $database;
    $query_img = $database->query(sprintf(
        "SELECT * FROM `%smod_news_img_img` " .
        "WHERE `id` = %d",
        TABLE_PREFIX,intval($pic_id)
    ));
    if (!empty($query_img) && $query_img->numRows() > 0) {
        return $query_img->fetchRow();
    }
    return array();
}

function mod_nwi_img_makedir($dir, $with_thumb=true)
{
    if (make_dir($dir)) {
        // Add a index.php file to prevent directory spoofing
        $content = ''.
"<?php

/**
*
* @category        modules
* @package         news_img
* @author          WBCE Community
* @copyright       2004-2009, Ryan Djurovich
* @copyright       2009-2010, Website Baker Org. e.V.
* @copyright       2019-, WBCE Community
* @link            https://www.wbce.org/
* @license         http://www.gnu.org/licenses/gpl.html
* @platform        WBCE
*
*/

header('Location: ../');
?>";
        $handle = fopen($dir.'/index.php', 'w');
        fwrite($handle, $content);
        fclose($handle);
        change_mode($dir.'/index.php', 'file');
    }
    if ($with_thumb) {
        $dir .= '/thumb';
        if (make_dir($dir)) {
            // Add a index.php file to prevent directory spoofing
            $content = ''.
"<?php

/**
*
* @category        modules
* @package         news_img
* @author          WBCE Community
* @copyright       2004-2009, Ryan Djurovich
* @copyright       2009-2010, Website Baker Org. e.V.
* @copyright       2019-, WBCE Community
* @link            https://www.wbce.org/
* @license         http://www.gnu.org/licenses/gpl.html
* @platform        WBCE
*
*/

header('Location: ../');
?>";
            $handle = fopen($dir.'/index.php', 'w');
            fwrite($handle, $content);
            fclose($handle);
            change_mode($dir.'/index.php', 'file');
        }
    }
}

function mod_nwi_img_upload($post_id,$is_preview_image=false)
{
    global $database, $mod_nwi_file_dir, $allowed_suffixes;

    // upload.php = 'file'
    // modify_post.php (preview image) = 'postfoto'
    $key = 'file';
    if($is_preview_image) {
        $key = 'postfoto';
    } else {
        $mod_nwi_file_dir .= "$post_id/";
    }
    $mod_nwi_thumb_dir = $mod_nwi_file_dir . "thumb/";
    $imageErrorMessage = null;

    // get section id
    $post_data = mod_nwi_post_get($post_id);
    $section_id = intval($post_data['section_id']);

    // get settings
    $settings = mod_nwi_settings_get($section_id);

    $settings['imgmaxsize'] = intval($settings['imgmaxsize']);
    $iniset = ini_get('upload_max_filesize');
    $iniset = mod_nwi_return_bytes($iniset);

    list($previewwidth,
        $previewheight,
        $thumbwidth,
        $thumbheight
    ) = mod_nwi_get_sizes($section_id);

    // gallery images size
    $imagemaxsize  = ($settings['imgmaxsize']>0 && $settings['imgmaxsize'] < $iniset)
        ? $settings['imgmaxsize']
        : $iniset;

    $imagemaxwidth  = $settings['imgmaxwidth'];
    $imagemaxheight = $settings['imgmaxheight'];
    $crop           = ($settings['crop_preview'] == 'Y') ? 1 : 0;

    // make sure the folder exists
    if(!is_dir($mod_nwi_file_dir)) {
        mod_nwi_img_makedir($mod_nwi_file_dir);
    }

    // handle upload
    if(isset($_FILES[$key]) && is_array($_FILES[$key]))
    {
        $picture = $_FILES[$key];
        if (isset($picture['name']) && $picture['name'] && (strlen($picture['name']) > 3))
        {
            $pic_error = '';
            // change special characters
            $imagename = media_filename($picture['name']);
            // validate suffix
            $suffix = strtolower(pathinfo($imagename,PATHINFO_EXTENSION));
            if(!in_array($suffix,$allowed_suffixes)) {
                $imageErrorMessage = 'invalid file type';
            } else {
                // lowercase filename and find a free one
                $imagename = mod_nwi_find_free_filename($mod_nwi_file_dir,strtolower($imagename));
                $filepath = $mod_nwi_file_dir.$imagename;
                // check size limit
                if (empty($picture['size']) || $picture['size'] > $imagemaxsize) {
                    $imageErrorMessage .= $MOD_NEWS_IMG['IMAGE_LARGER_THAN'].mod_nwi_byte_convert($imagemaxsize).'<br />';
                } elseif (strlen($imagename) > '256') {
                    $imageErrorMessage .= $MOD_NEWS_IMG['IMAGE_FILENAME_ERROR'].'<br />';
                } else {
                    // move to media folder
                    if (true===move_uploaded_file($picture['tmp_name'], $filepath)) {
                        // preview images have a different size (smaller in most cases)
                        if($is_preview_image) {
                            $imagemaxwidth  = $previewwidth;
                            $imagemaxheight = $previewheight;
                        }
                        // fix for empty max values
                        if(empty($imagemaxwidth)) {
                            $imagemaxwidth = 150;
                        }
                        if(empty($imagemaxheight)) {
                            $imagemaxheight = 150;
                        }

                        // resize image (if larger than max width and height)
                        if (list($w, $h) = getimagesize($mod_nwi_file_dir.$imagename)) {
                            if ($w>$imagemaxwidth || $h>$imagemaxheight) {
                                if (true !== ($pic_error = @mod_nwi_image_resize($mod_nwi_file_dir.$imagename, $mod_nwi_file_dir.$imagename, $imagemaxwidth, $imagemaxheight, $crop))) {
                                    $imageErrorMessage .= $pic_error.'<br />';
                                    @unlink($mod_nwi_file_dir.$imagename); // delete image (cleanup)
                                }
                            }
                        }
                        if($is_preview_image) {
                            $database->query(sprintf(
                                "UPDATE `%smod_news_img_posts` SET `image`='%s' " .
                                "WHERE `post_id`=%d",
                                TABLE_PREFIX, $imagename, $post_id
                            ));
                            if($database->is_error()) {
                                $imageErrorMessage .= $database->get_error()."<br />";
                            }
                        } else {
                            // create thumb
                            if (true !== ($pic_error = @mod_nwi_image_resize($mod_nwi_file_dir.$imagename, $mod_nwi_thumb_dir.$imagename, $thumbwidth, $thumbheight, $crop))) {
                                $imageErrorMessage .= $pic_error.'<br />';
                                @unlink($mod_nwi_file_dir.$imagename); // delete image (cleanup)
                            } else {
                                $pic_id = null;
                                // insert image into image table
                                $database->query(sprintf(
                                    "INSERT INTO `%smod_news_img_img` " .
                                    "(`picname`) " .
                                    "VALUES ('%s')",
                                    TABLE_PREFIX,$imagename
                                ));
                                if($database->is_error()) {
                                    $imageErrorMessage .= $database->get_error()."<br />";
                                } else {
                                    $pic_id = $database->getLastInsertId();
                                }
                                // image position
                                $order = new order(TABLE_PREFIX.'mod_news_img_img', 'position', 'id', 'post_id');
                                $position = $order->get_new($post_id);
                                // connect with current post
                                $database->query(sprintf(
                                    "INSERT INTO `%smod_news_img_posts_img` " .
                                    "(`post_id`,`pic_id`,`position`) " .
                                    "VALUES ('%s',%d,%d)",
                                    TABLE_PREFIX,$post_id,$pic_id,$position
                                ));
                                if($database->is_error()) {
                                    $imageErrorMessage .= $database->get_error()."<br />";
                                }
                            }
                        }
                    } else {
                        $imageErrorMessage .= "Unable to move uploaded image ".$picture['tmp_name']." to ".$mod_nwi_file_dir.$imagename."<br />";
                    }
                }
            }
        }
    }
    return $imageErrorMessage;
}

// ===== POSTS =================================================================

function mod_nwi_post_activate($value)
{
    global $database;
    $posts = array();
    if(isset($_POST['manage_posts']) && is_array($_POST['manage_posts'])) {
        $posts = $_POST['manage_posts'];
    } else {
        return false;
    }

    $errors = 0;
    foreach($posts as $post_id) {
        // Update row
        $database->query(sprintf(
            "UPDATE `%smod_news_img_posts`"
            . " SET `active` = '$value' "
            . " WHERE `post_id` = '$post_id'",
            TABLE_PREFIX
        ));
        if($database->is_error()) {
            $errors++;
        }
    
		$postQuery = "SELECT * from `".TABLE_PREFIX."mod_news_img_posts` WHERE `post_id`=".$post_id;	
		$query_post = $database->query($postQuery);
		if ($query_post->numRows() > 0) {
		$post      = $query_post->fetchRow();
		}
		
		$pageQuery = "SELECT * from `".TABLE_PREFIX."sections` WHERE `section_id`=".$post['section_id'];	
		$query_page = $database->query($pageQuery);
		if ($query_page->numRows() > 0) {
		$page      = $query_page->fetchRow();
		}
		
		
		if ($post['active'] == "0" ) {		
			if(is_writable(WB_PATH.PAGES_DIRECTORY.$post['link'].PAGE_EXTENSION)) {
					unlink(WB_PATH.PAGES_DIRECTORY.$post['link'].PAGE_EXTENSION);
			}
			
		} else{
			$filename = WB_PATH.PAGES_DIRECTORY.$post['link'].PAGE_EXTENSION;
			mod_nwi_create_file($filename, '', $post_id, $post['section_id'], $page['page_id']);
		}

	}
	return ( $errors>0 ? false : true );
}

function mod_nwi_post_copy($section_id,$page_id,$with_tags=false)
{
    global $mod_nwi_file_dir, $database, $admin;

    $posts = array();
    if(isset($_POST['manage_posts']) && is_array($_POST['manage_posts'])) {
        $posts = $_POST['manage_posts'];
    } else {
        return false;
    }

    $group_id = 0;
    $old_section_id = $section_id;
    $old_page_id = $page_id;

    $group = $admin->get_post_escaped('group');

    if (!empty($group)) {
        $gid_value = urldecode($group);
        $values = unserialize($gid_value);
        if (!isset($values['s']) or  !isset($values['g']) or  !isset($values['p'])) {
            header("Location: ".ADMIN_URL."/pages/index.php");
            exit(0);
        }
        if (intval($values['p'])!=0) {
            $group_id = intval($values['g']);
            $section_id = intval($values['s']);
            $page_id = intval($values['p']);
        }
    }

    // store this one for later use
    $mod_nwi_file_base = $mod_nwi_file_dir;

    foreach($posts as $idx=>$pid)
    {
        $original_post_id = intval($pid);
        if($original_post_id != 0)
        {
        	// Get new order
        	$order = new order(TABLE_PREFIX.'mod_news_img_posts', 'position', 'post_id', 'section_id');
        	$position = $order->get_new($section_id);

        	// Insert new row into database
        	$sql = "INSERT INTO `%smod_news_img_posts` "
                 . "(`section_id`,`group_id`,`position`,`link`,`content_short`,`content_long`,`content_block2`,`active`) "
                 . "VALUES ('$section_id','$group_id','$position','','','','','0')";
        	$database->query(sprintf($sql,TABLE_PREFIX));

            // get new postID
        	$post_id = $database->get_one("SELECT LAST_INSERT_ID()");

        	$mod_nwi_file_dir = "$mod_nwi_file_base/$post_id/";
        	$mod_nwi_thumb_dir = $mod_nwi_file_dir . "thumb/";

            // orig. post
            $fetch_content = mod_nwi_post_get($original_post_id);

        	$title = mod_nwi_escapeString($fetch_content['title']);
        	$link = mod_nwi_escapeString($fetch_content['link']);
        	$short = mod_nwi_escapeString($fetch_content['content_short']);
        	$long = mod_nwi_escapeString($fetch_content['content_long']);
        	$block2 = mod_nwi_escapeString($fetch_content['content_block2']);
        	$image = mod_nwi_escapeString($fetch_content['image']);
        	$active = mod_nwi_escapeString($fetch_content['active']);
        	$publishedwhen =  $fetch_content['published_when'];
        	$publisheduntil =  $fetch_content['published_until'];

        	// Get page link URL
        	$query_page = $database->query("SELECT `level`,`link` FROM `".TABLE_PREFIX."pages` WHERE `page_id` = '$page_id'");
        	$page = $query_page->fetchRow();
        	$page_level = $page['level'];
        	$page_link = $page['link'];

        	// get old link
        	$old_link = $link;

        	// new link
        	$post_link = '/posts/'.page_filename(preg_replace('/^\/?posts\/?/s', '', preg_replace('/-[0-9]*$/s', '', $link, 1)));
        	// make sure to have the post_id as suffix; this will make the link unique (hopefully...)
        	if(substr_compare($post_link,$post_id,-(strlen($post_id)),strlen($post_id))!=0) {
        	    $post_link .= PAGE_SPACER.$post_id;
        	}

        	// Make sure the post link is set and exists
        	// Make news post access files dir
        	make_dir(WB_PATH.PAGES_DIRECTORY.'/posts/');
        	$file_create_time = '';
        	if (!is_writable(WB_PATH.PAGES_DIRECTORY.'/posts/')) {
        	    $admin->print_error($MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE']);
        	} else {
        	    // Specify the filename
				if ($active == "1") {
					$filename = WB_PATH.PAGES_DIRECTORY.'/'.$post_link.PAGE_EXTENSION;
					mod_nwi_create_file($filename, $file_create_time, $post_id, $section_id, $page_id);
				}
        	}

            // create image dir and copy images
        	if(!is_dir($mod_nwi_file_dir)) {
        	    mod_nwi_img_makedir($mod_nwi_file_dir);
        	}
        	mod_nwi_img_copy(WB_PATH.MEDIA_DIRECTORY.'/.news_img/'.$original_post_id,$mod_nwi_file_dir);

        	// Update row
        	$database->query(
        	    "UPDATE `".TABLE_PREFIX."mod_news_img_posts`"
        	        . " SET `section_id` = '$section_id',"
        	        . " `group_id` = '$group_id',"
        	        . " `title` = '$title',"
        	        . " `link` = '$post_link',"
        	        . " `content_short` = '$short',"
        	        . " `content_long` = '$long',"
        	        . " `content_block2` = '$block2',"
        	        . " `image` = '$image',"
        	        . " `active` = '$active',"
        	        . " `published_when` = '$publishedwhen',"
        	        . " `published_until` = '$publisheduntil',"
        	        . " `posted_when` = '".time()."',"
        	        . " `posted_by` = '".$admin->get_user_id()."'"
        	        . " WHERE `post_id` = '$post_id'"
            );

        	if(!($database->is_error())){
        	    // update gallery images
        	    $database->query(sprintf(
                    "INSERT INTO `%smod_news_img_img` " .
                    "(`picname`, `picdesc`, `post_id`, `position`) " .
                    "SELECT `picname`, `picdesc`, '".$post_id."', `position` " .
                    "FROM `%smod_news_img_img` WHERE `post_id` = '".$original_post_id."'",
                    TABLE_PREFIX,TABLE_PREFIX
                ));

                // copy tags (optional)
                if($with_tags==true) {
                    $tags = mod_nwi_get_tags_for_post($original_post_id);
                    if(count($tags)>0) {
                        // different section: make sure the tags exist
                        if(!$section_id != $old_section_id) {
                            $section_tags = mod_nwi_get_tags($section_id);
                            foreach($tags as $id => $tag) {
                                // find tag in $section_tags
                                if(!isset($section_tags[$id]) || $section_tags[$id]['section_id']!=0) {
                                    // link tag to section
                                    $database->query(sprintf(
                                        "INSERT IGNORE INTO `%smod_news_img_tags_sections` " .
                                        "(`section_id`,`tag_id`) VALUES (%d,%d)",
                                        TABLE_PREFIX,$section_id,$id
                                    ));
                                }
                            }
                        }
                        // link tag to post
                        foreach($tags as $id => $tag) {
                            $database->query(sprintf(
                                "INSERT IGNORE INTO `%smod_news_img_tags_posts` " .
                                "(`post_id`,`tag_id`) VALUES (%d,%d)",
                                TABLE_PREFIX,$post_id,$id
                            ));
                        }
                    }
                }
        	}
        }

        // Clean up ordering (e.g. if we were moving posts across section borders
        $order = new order(TABLE_PREFIX.'mod_news_img_posts', 'position', 'post_id', 'section_id');
        $order->clean($old_section_id);

        if($database->is_error()) {
            return false;
        }
    }

    return true;
}

/**
 *
 * @access 
 * @return
 **/
function mod_nwi_posts_count(int $section_id)
{
    global $database;
    $query_extra = mod_nwi_get_query_extra();
    $t = time();
    $sql = sprintf(
        "SELECT count(`post_id`) AS `count` " .
        "FROM `%smod_news_img_posts` AS `t1` " .
        "WHERE `section_id`='$section_id' ".
        "AND `active` = '1' AND `title` != '' " .
        "AND (`published_when` = '0' OR `published_when` <= $t) " .
        "AND (`published_until` = '0' OR `published_until` >= $t) " .
        "$query_extra ",
        TABLE_PREFIX
    );
    $query_posts = $database->query($sql);
    if(!empty($query_posts) && $query_posts->numRows()>0) {
        return $query_posts->fetchRow();
    }
    return 0;
}   // end function mod_nwi_posts_count()


function mod_nwi_post_delete($posts)
{
    global $database, $mod_nwi_file_dir, $section_id;

    //store this one for later use
    $mod_nwi_file_base = $mod_nwi_file_dir;

    $errors = 0;

    foreach($posts as $post_id)
    {
        // Get post details
        $get_details = mod_nwi_post_get($post_id);

        if(is_array($get_details) && count($get_details)>0)
        {
            // Unlink post access file
            if(is_writable(WB_PATH.PAGES_DIRECTORY.$get_details['link'].PAGE_EXTENSION)) {
        	    unlink(WB_PATH.PAGES_DIRECTORY.$get_details['link'].PAGE_EXTENSION);
            }

            // delete images
            $mod_nwi_file_base .= "$post_id";
            rm_full_dir($mod_nwi_file_base);
            $database->query(sprintf(
                "DELETE FROM `%smod_news_img_img` WHERE `post_id` = ".$post_id,
                TABLE_PREFIX
            ));

            // Delete post
            $database->query(sprintf(
                "DELETE FROM `%smod_news_img_posts` WHERE `post_id` = '$post_id' LIMIT 1",
                TABLE_PREFIX
            ));
            if($database->is_error()) {
                $errors++;
            }

            // Clean up ordering
            $order = new order(TABLE_PREFIX.'mod_news_img_posts', 'position', 'post_id', 'section_id');
            $order->clean($section_id);
        }
    }
    return ( $errors>0 ? false : true );
}

function mod_nwi_post_get($post_id)
{
    global $database,$section_id;
    list($order_by,$direction) = mod_nwi_get_order($section_id);
	if (isset($_GET['g'])) {
		$query_group = ' AND `group_id` = '.intval($_GET['g']).' ';
	} else {
		$query_group = '';
	}
	$t = time();	
    $prev_dir = ($direction=='DESC'?'ASC':'DESC');
    $sql = sprintf(
        "SELECT `t1`.*, " .
        "  (SELECT `link` FROM `%smod_news_img_posts` AS `t2` WHERE `t2`.`$order_by` > `t1`.`$order_by` AND `section_id`=$section_id AND (`published_when` = '0' OR `published_when` <= $t) AND (`published_until` = '0' OR `published_until` >= $t) AND `active`=1 $query_group ORDER BY `$order_by` $prev_dir LIMIT 1 ) as `prev_link`, ".
        "  (SELECT `link` FROM `%smod_news_img_posts` AS `t3` WHERE `t3`.`$order_by` < `t1`.`$order_by` AND `section_id`=$section_id AND (`published_when` = '0' OR `published_when` <= $t) AND (`published_until` = '0' OR `published_until` >= $t) AND `active`=1 $query_group ORDER BY `$order_by` $direction LIMIT 1 ) as `next_link` " .
        "FROM `%smod_news_img_posts` AS `t1` " .
        "WHERE `post_id`=%d",
        TABLE_PREFIX, TABLE_PREFIX, TABLE_PREFIX, $post_id
    );
    $query_content = $database->query($sql);
    if(!empty($query_content)) {
        $post = $query_content->fetchRow();
				
		// create accessfiles of prev/next items if missing
		// we need the page_id for the access file
		$sectionArray = mod_nwi_get_section_array($post['section_id']); 
		$this_page_id = $sectionArray['page_id'];
		
		$filename_next = WB_PATH.PAGES_DIRECTORY.'/'.$post['next_link'].PAGE_EXTENSION;
		if ($post['next_link']!=='' && !file_exists($filename_next)) {
			mod_nwi_create_file($filename_next, '', $post['post_id'], $post['section_id'], $this_page_id);
		}
		$filename_prev = WB_PATH.PAGES_DIRECTORY.'/'.$post['prev_link'].PAGE_EXTENSION;
		if ($post['prev_link']!=='' && !file_exists($filename_prev)) {
			mod_nwi_create_file($filename_prev, '', $post['post_id'], $post['section_id'], $this_page_id);
		}
		
        // get users
        $users = mod_nwi_users_get();
        // add "unknown" user
        $users[0] = array(
            'username' => 'unknown',
            'display_name' => 'unknown',
            'email' => ''
        );


        return mod_nwi_post_process($post, $section_id, $users);
    }
    return array();
}

/**
 *
 * @access 
 * @return
 **/
function mod_nwi_post_list(int $section_id, bool $process=true)
{
    $query_extra = mod_nwi_get_query_extra();

    // ----- get posts ---------------------------------------------------------
    $posts =  mod_nwi_posts_getall($section_id, false, $query_extra, $process);

    return $posts;
}   // end function mod_nwi_post_list()


function mod_nwi_post_move($section_id,$page_id,$with_tags=false)
{
    global $database, $mod_nwi_file_dir, $admin;

    $group_id = 0;
    $old_section_id = $section_id;
    $old_page_id = $page_id;

    $group = $admin->get_post_escaped('group');

    if (!empty($group)) {
        $gid_value = urldecode($group);
        $values = unserialize($gid_value);
        if (!isset($values['s']) or  !isset($values['g']) or  !isset($values['p'])) {
            header("Location: ".ADMIN_URL."/pages/index.php");
            exit(0);
        }
        if (intval($values['p'])!=0) {
            $group_id = intval($values['g']);
            $section_id = intval($values['s']);
            $page_id = intval($values['p']);
        }
    }

    //store this one for later use
    $mod_nwi_file_base = $mod_nwi_file_dir;

    $posts = array();
    if(isset($_POST['manage_posts']) && is_array($_POST['manage_posts'])) {
        $posts = $_POST['manage_posts'];
    } else {
        return false;
    }
    foreach($posts as $idx=>$pid)
    {
        $post_id = intval($pid);

        if($post_id != 0){
        	// Update row
        	$database->query(sprintf(
                "UPDATE `%smod_news_img_posts` SET ".
                "`section_id` = '$section_id', `group_id` = '$group_id' ".
                "WHERE `post_id` = '$post_id'",
                TABLE_PREFIX
            ));
        }

        // Clean up ordering (e.g. if we were moving posts across section borders
        $order = new order(TABLE_PREFIX.'mod_news_img_posts', 'position', 'post_id', 'section_id');
        $order->clean($old_section_id);
        $order->clean($section_id);

        if($database->is_error()) {
            return false;
        }

        // get post link
        $query_post = $database->query(sprintf(
            "SELECT `link` FROM `%smod_news_img_posts` WHERE `post_id`='$post_id'",
            TABLE_PREFIX
        ));
        $post = $query_post->fetchRow();
        $post_link = $post['link'];
        // We need to create a new file
        // First, delete old file if it exists
        if (file_exists(WB_PATH.PAGES_DIRECTORY.$post_link.PAGE_EXTENSION)) {
            $file_create_time = filemtime(WB_PATH.PAGES_DIRECTORY.$post_link.PAGE_EXTENSION);
            unlink(WB_PATH.PAGES_DIRECTORY.$post_link.PAGE_EXTENSION);
        }

        // Specify the filename
        $filename = WB_PATH.PAGES_DIRECTORY.'/'.$post_link.PAGE_EXTENSION;
        mod_nwi_create_file($filename, '', $post_id, $section_id, $page_id);
    }
    return true;
}

/**
 *
 * @access 
 * @return
 **/
function mod_nwi_post_show(int $post_id)
{
    global $database, $admin, $section_id;

    $post_section = (
        defined('POST_SECTION') ?
        POST_SECTION :
        $section_id
    );

    // get settings
    $settings = mod_nwi_settings_get($post_section);

    // get users
    $users = mod_nwi_users_get();

    // add "unknown" user
    $users[0] = array(
        'username' => 'unknown',
        'display_name' => 'unknown',
        'email' => ''
    );

    // get post data
    $post = mod_nwi_post_get($post_id);
	
	$pageQuery = "SELECT * from `".TABLE_PREFIX."sections` WHERE `section_id`=".$post['section_id'];	
	$query_page = $database->query($pageQuery);
	if ($query_page->numRows() > 0) {
	$page      = $query_page->fetchRow();
	}

    // get group data
	$gid = 0;
	if (is_array($post_id)) {
		$gid = $post_id['group_id'];
	}
    if($gid != 0) {
        $group = mod_nwi_get_group($post_id['group_id']);
        if($group['active'] != 1) {
            return false;
        }
    }
	if ($post['active'] ==0 ) {		
		if(is_writable(WB_PATH.PAGES_DIRECTORY.$post['link'].PAGE_EXTENSION)) {
        	    unlink(WB_PATH.PAGES_DIRECTORY.$post['link'].PAGE_EXTENSION);
        }
		return false;
	} else{
		$filename = WB_PATH.PAGES_DIRECTORY.$post['link'].PAGE_EXTENSION;
        mod_nwi_create_file($filename, '', $post_id, $section_id, $page['page_id']);
	}

    return $post;
}   // end function mod_nwi_post_show()

/**
 *
 * @access 
 * @return
 **/
function mod_nwi_posts_getall(int $section_id, bool $is_backend, string $query_extra, bool $process=true)
{
    global $database, $admin;

    $posts    = array();
    $groups   = mod_nwi_get_groups($section_id);
    $t        = time();
    $limit    = '';
    $filter   = '';
    $active   = '';

    list($order_by,$direction) = mod_nwi_get_order($section_id);

    $settings = mod_nwi_settings_get($section_id);
    if ($settings['posts_per_page'] != 0) {
        if (isset($_GET['p']) and is_numeric($_GET['p']) and $_GET['p'] >= 0) {
            $position = $_GET['p'];
        } else {
            $position = 0;
        }
        if(!$is_backend) {
            $limit = " LIMIT $position,".$settings['posts_per_page'];
        }
    }

    if(!$is_backend) {
        $query_extra .= " AND `active` = '1' AND `title` != '' "
                     .  "AND (`published_when`  = '0' OR `published_when`  <= $t) "
                     .  "AND (`published_until` = '0' OR `published_until` >= $t) ";
        $active       = 'AND `active`=1';
    }

    if(isset($_GET['tags']) && strlen($_GET['tags'])) {
        $filter_posts = array();
        $tags = mod_nwi_escape_tags($_GET['tags']);
        $r = $database->query(
            "SELECT `t2`.`post_id` FROM `".TABLE_PREFIX."mod_news_img_tags` as `t1` ".
            "JOIN `".TABLE_PREFIX."mod_news_img_tags_posts` AS `t2` ".
            "ON `t1`.`tag_id`=`t2`.`tag_id` ".
            "WHERE `tag` IN ('".implode("', '", $tags)."') ".
            "GROUP BY `t2`.`post_id`"
        );
        while ($row=$r->fetchRow()) {
            $filter_posts[] = $row['post_id'];
        }
        if (count($filter_posts)>0) {
            $filter = " AND `t1`.`post_id` IN (".implode(',', array_values($filter_posts)).") ";
        }
		 else {
			$filter = " AND `t1`.`post_id` = '-999'";
		}
    }

    $prev_dir = ($direction=='DESC'?'ASC':'DESC');

    $sql = sprintf(
        "SELECT " .
        "  *, " .
        "  (SELECT `position`       FROM `%smod_news_img_groups`     AS `t4` WHERE `t4`.`group_id`=`t1`.`group_id`) as `gposition`, " .
        "  (SELECT COUNT(`post_id`) FROM `%smod_news_img_tags_posts` AS `t2` WHERE `t2`.`post_id`=`t1`.`post_id`) as `tags`, " .
        "  (SELECT `post_id` FROM `%smod_news_img_posts` AS `t3` WHERE `t3`.`$order_by` > `t1`.`$order_by` AND `section_id`='$section_id' $active ORDER BY `$order_by` $direction LIMIT 1 ) as `next`, ".
        "  (SELECT `post_id` FROM `%smod_news_img_posts` AS `t3` WHERE `t3`.`$order_by` < `t1`.`$order_by` AND `section_id`='$section_id' $active ORDER BY `$order_by` $prev_dir LIMIT 1 ) as `prev` " .
        "FROM `%smod_news_img_posts` AS `t1` WHERE `section_id`='$section_id' $filter ".
        "$query_extra ORDER BY `$order_by` $direction $limit",
        TABLE_PREFIX, TABLE_PREFIX, TABLE_PREFIX, TABLE_PREFIX, TABLE_PREFIX
    );

    $query_posts = $database->query($sql);

    if(!empty($query_posts) && $query_posts->numRows()>0) {
            // map group index to title
            $group_map = array();
            foreach($groups as $i => $g) {
                $group_map[$g['group_id']] = ( empty($g['title']) ? $TEXT['NONE'] : $g['title'] );
            }
            // get users
            $users = mod_nwi_users_get();
            // add "unknown" user
            $users[0] = array(
                'username' => 'unknown',
                'display_name' => 'unknown',
                'email' => ''
            );
            while($post = $query_posts->fetchRow()) {
            if($process === true) {
                $posts[] = mod_nwi_post_process($post, $section_id, $users);
        	} else {
            	$posts[] = $post;
            }			
			$sectionArray = mod_nwi_get_section_array($post['section_id']);
			$filename = WB_PATH.PAGES_DIRECTORY.$post['link'].PAGE_EXTENSION;
			
			// check if accessfile should be created...
			$createFile = true;																				// by default: yes.
			if ($post['published_when'] != 0 && $post['published_when'] > time()) {$createFile = false;}    // no, because the post is not public yet.
			if ($post['published_until'] != 0 && $post['published_until'] < time()) {$createFile = false;}  // no, because the post is no longer public.
			if ($post['active'] != 1) {$createFile = false;}												// no, the post is created inactive.
			if (!file_exists($filename) && $createFile == true) {
				mod_nwi_create_file($filename, '', $post['post_id'], $post['section_id'], $sectionArray['page_id']);
			}
        }
    }
	


    return $posts;
}   // end function mod_nwi_posts_getall()

/**
 *
 * @access 
 * @return
 **/
function mod_nwi_posts_render($section_id,$posts,$posts_per_page=0)
{
    global $TEXT, $MOD_NEWS_IMG, $wb, $page_id;

    // if called by droplet
    if(!is_array($MOD_NEWS_IMG)) {
        require __DIR__ . '/languages/EN.php';
        $lang = __DIR__ . '/languages/' . LANGUAGE . '.php';
        if(file_exists($lang)) {
            require $lang;
        }
    }

    $list  = array();
    $settings = mod_nwi_settings_get($section_id);

    // position to start off (=offset)
    if (isset($_GET['p']) and is_numeric($_GET['p']) and $_GET['p'] > 0) {
        $position = $_GET['p'];
    } else {
        $position = 0;
    }

    // image sizes
    list(
        $previewwidth,
        $previewheight,
        $thumbwidth,
        $thumbheight
    ) = mod_nwi_get_sizes($section_id);

    // filter by tags
    $tags_header = null;
    $tags_append = null;
    if(isset($_GET['tags']) && strlen($_GET['tags'])) {
        $requested_tags = mod_nwi_escape_tags($_GET['tags']);
        foreach ($requested_tags as $i => $tag) {
            $requested_tags[$i] = "<span class=\"mod_nwi_tag\" id=\"mod_nwi_tag_".$i."\">".$tag."</span>";
        }
        $tags_header = implode("\n",$requested_tags);
        $tags_append = $_GET['tags'];
    }

    // Create previous and next links
    $display_previous_next_links = 'hidden';
    if ($posts_per_page != 0) { // 0 = unlimited = no paging
        $cnt = mod_nwi_posts_count($section_id); // all posts in this section
        $total_num = $cnt['count'];
        if ($position > 0) {
            $pl_prepend = '<a href="?p='.($position-$posts_per_page).(empty($tags_append) ? '' : '&amp;tags='.$tags_append).'">';
            if (isset($_GET['g']) and is_numeric($_GET['g'])) {
                $pl_prepend = '<a href="?p='.($position-$posts_per_page).(empty($tags_append) ? '' : '&amp;tags='.$tags_append).'&amp;g='.$_GET['g'].'">';
            }
            $pl_append = '</a>';
            $previous_link = $pl_prepend.$TEXT['PREVIOUS'].$pl_append;
            $previous_page_link = $pl_prepend.$TEXT['PREVIOUS_PAGE'].$pl_append;
        } else {
            $previous_link = '';
            $previous_page_link = '';
        }
        if ($position + $posts_per_page >= $total_num) {
            $next_link = '';
            $next_page_link = '';
        } else {
            if (isset($_GET['g']) and is_numeric($_GET['g'])) {
                $nl_prepend = '<a href="?p='.($position+$posts_per_page).(empty($tags_append) ? '' : '&amp;tags='.$tags_append).'&amp;g='.$_GET['g'].'"> ';
            } else {
                $nl_prepend = '<a href="?p='.($position+$posts_per_page).(empty($tags_append) ? '' : '&amp;tags='.$tags_append).'"> ';
            }
            $nl_append = '</a>';
            $next_link = $nl_prepend.$TEXT['NEXT'].$nl_append;
            $next_page_link = $nl_prepend.$TEXT['NEXT_PAGE'].$nl_append;
        }
        if ($position+$posts_per_page > $total_num) {
            $num_of = $position+$total_num;
        } else {
            $num_of = $position+$posts_per_page;
        }

        if($num_of>$total_num) {
            $num_of=$total_num;
        }

        $out_of = ($position+1).'-'.$num_of.' '.strtolower($TEXT['OUT_OF']).' '.$total_num;
        $of = ($position+1).'-'.$num_of.' '.strtolower($TEXT['OF']).' '.$total_num;

        if($previous_link || $next_link) {
            $display_previous_next_links = 'visible';
        }

    } else {
        $next_page_link = $next_link = $previous_page_link = $previous_link = $out_of = $of = '';
    }

    list($vars,$default_replacements) = mod_nwi_replacements();

    foreach($posts as $i => $post) {
        // tags
        $tags = mod_nwi_get_tags_for_post($post['post_id']);
        foreach ($tags as $i => $tag) {
            $tags[$i] = "<span class=\"mod_nwi_tag\" id=\"mod_nwi_tag_".$post['post_id']."_".$i."\""
                  . (strlen($tag['tag_color'])>0 ? " style=\"background-color:".$tag['tag_color']."\"" : "" ) .">"
                  . "<a href=\"".$wb->page_link($page_id)."?tags=".$tag['tag']."\">".$tag['tag']."</a></span>";
        }
        // gallery images - wichtig für link "weiterlesen"  SHOW_READ_MORE
        $images = mod_nwi_img_get_by_post($post['post_id'],false);
        $anz_post_img = count($images);
		$post_href_link = 'href="'. $post['post_link'].'"';
		$post_a_open_tag = '<a '.$post_href_link.'>';
		$post_a_close_tag = '</a>';
        // no "read more" link if no long content
        if ( (strlen($post['content_long']) < 9) && ($anz_post_img < 1)) {
            $post['post_link'] = '#" onclick="javascript:void(0);return false;" style="cursor:no-drop;';
			$post_href_link ='';
			$post_a_open_tag ='';
			$post_a_close_tag ='';
        }
		$post['content_short']=str_replace('{SYSVAR:MEDIA_REL}',WB_URL.MEDIA_DIRECTORY,$post['content_short']);
		$post['content_long']=str_replace('{SYSVAR:MEDIA_REL}',WB_URL.MEDIA_DIRECTORY,$post['content_long']);	
		$post['content_block2']=str_replace('{SYSVAR:MEDIA_REL}',WB_URL.MEDIA_DIRECTORY,$post['content_block2']);	

        // set replacements for current line
        $replacements = array_merge(
            $default_replacements,
            $TEXT,
            $MOD_NEWS_IMG,
            array_change_key_case($post,CASE_UPPER),
            array(
                'IMAGE'           => $post['post_img'],
                'SHORT'           => $post['content_short'],
                'LINK'            => $post['post_link'],
				'HREF'			  => $post_href_link,
				'AOPEN'			  => $post_a_open_tag,
				'ACLOSE'		  => $post_a_close_tag,	
                'MODI_DATE'       => $post['post_date'],
                'MODI_TIME'       => $post['post_time'],
                'TAGS'            => implode(" ", $tags),
                'SHOW_READ_MORE'  => (strlen($post['content_long'])<1 && ($anz_post_img<1))
                                     ? 'hidden' : 'visible',
                'DISPLAY_PREVIOUS_NEXT_LINKS'
                                  => $display_previous_next_links,
            )
        );

        $list[] = preg_replace_callback(
            '~\[('.implode('|',$vars).')+\]~',
            function($match) use($replacements) {
                return (isset($match[1]) && isset($replacements[$match[1]]))
                    ? $replacements[$match[1]]
                    : '';
            },
            $settings['post_loop']
        );
    }

    // overall header
    $prev_next_header = str_replace(
        array(
            '[NEXT_PAGE_LINK]',
            '[NEXT_LINK]',
            '[PREVIOUS_PAGE_LINK]',
            '[PREVIOUS_LINK]',
            '[OUT_OF]',
            '[OF]',
            '[DISPLAY_PREVIOUS_NEXT_LINKS]'
        ),
        array(
            $next_page_link,
            $next_link,
            $previous_page_link,
            $previous_link,
            $out_of,
            $of,
            $display_previous_next_links
        ),
        $settings['header']
    );

    // footer
    $prev_next_footer = str_replace(
        array(
            '[NEXT_PAGE_LINK]',
            '[NEXT_LINK]',
            '[PREVIOUS_PAGE_LINK]',
            '[PREVIOUS_LINK]',
            '[OUT_OF]',
            '[OF]',
            '[DISPLAY_PREVIOUS_NEXT_LINKS]'
        ),
        array(
            $next_page_link,
            $next_link,
            $previous_page_link,
            $previous_link,
            $out_of,
            $of,
            $display_previous_next_links
        ),
        $settings['footer']
    );

	if (empty($list)) {$list[]='Nichts gefunden';}
    return array(
        'rendered_posts' => $list,
        'prev_next_footer' => $prev_next_footer,
        'prev_next_header' => $prev_next_header
    );
}   // end function mod_nwi_posts_render()


/**
 *
 * @access 
 * @return
 **/
function mod_nwi_post_process($post,$section_id,$users)
{
    global $MOD_NEWS_IMG, $TEXT, $admin;
	
	$filename = WB_PATH.PAGES_DIRECTORY.$post['link'].PAGE_EXTENSION;

    // get groups
    $groups = mod_nwi_get_groups(intval($section_id));

    // map group id to group data for easier handling
    $group_map = array();
    foreach($groups as $i => $g) {
        $group_map[$g['group_id']] = $g;
    }

    // secure form
    $post['id_key'] = $admin->getIDKEY($post['post_id']);
    if (defined('WB_VERSION') && (version_compare(WB_VERSION, '2.8.3', '>'))) {
        $post['id_key'] = $post['post_id'];
    }
   
    // this is for the backend only
	$icon = '';
    $t = time();
    if ($post['published_when']<=$t && $post['published_until']==0) {
        $post['icon'] ='<span class="fa fa-fw fa-calendar-o" title="'.$MOD_NEWS_IMG['POST_ACTIVE'].'"></span>';
    } elseif (($post['published_when']<=$t || $post['published_when']==0) && $post['published_until']>=$t) {
        $post['icon'] ='<span class="fa fa-fw fa-calendar-check-o nwi-active" title="'.$MOD_NEWS_IMG['POST_ACTIVE'].'"></span>';
    } else {
        $post['icon'] ='<span class="fa fa-fw fa-calendar-times-o nwi-inactive" title="'.$MOD_NEWS_IMG['POST_INACTIVE'].'"></span>';
    }

    list($previewwidth,
        $previewheight,
        $thumbwidth,
        $thumbheight
    ) = mod_nwi_get_sizes($section_id);

    // posting (preview) image
    if ($post['image'] != "") {
        $imgdata = mod_nwi_img_get($post['image']);
        $post_img = "<img src='".WB_URL.MEDIA_DIRECTORY.'/.news_img/'.$post['image']."' alt='".htmlspecialchars($post['title'], ENT_QUOTES | ENT_HTML401)."' />";
    } else {
        $post_img = "<img src='".WB_URL."/modules/news_img/images/nopic.png' alt='empty placeholder' style='width:".$previewwidth."px;' />";
    }
    $post['post_img'] = $post_img;

    // post link
    $post['post_link'] = page_link($post['link']);
    $post['post_link_path'] = str_replace(WB_URL, WB_PATH, $post['post_link']);
    $post['next_link'] = (isset($post['next_link']) && strlen($post['next_link'])>0 ? page_link($post['next_link']) : null);
    $post['prev_link'] = (isset($post['prev_link']) && strlen($post['prev_link'])>0 ? page_link($post['prev_link']) : null);

    if (isset($_GET['p']) and intval($_GET['p']) > 0) {
        $post['post_link'] .= '?p='.intval($_GET['p']);
    }
    if (isset($_GET['g']) and is_numeric($_GET['g'])) {
        if (isset($_GET['p']) and $position > 0) {
            $delim = '&amp;';
        } else {
            $delim = '?';
        }
        $post['post_link'] .= $delim.'g='.$_GET['g'];
        $post['next_link'] = (strlen($post['next_link'])>0 ? $post['next_link'].'?g='.$_GET['g'] : null);
		$post['prev_link'] = (strlen($post['prev_link'])>0 ? $post['prev_link'].'?g='.$_GET['g'] : null);
    }

    // publishing date
    if ($post['published_when'] === '0') {
        $post['published_when'] = time();
    }
    if(!defined('CAT_PATH')) {
        if ($post['published_when'] > $post['posted_when']) {
            $post['post_date'] = date(DATE_FORMAT, $post['published_when']+TIMEZONE);
            $post['post_time'] = date(TIME_FORMAT, $post['published_when']+TIMEZONE);
        } else {
            $post['post_date'] = date(DATE_FORMAT, $post['posted_when']+TIMEZONE);
            $post['post_time'] = date(TIME_FORMAT, $post['posted_when']+TIMEZONE);
        }
        $post['published_date'] = date(DATE_FORMAT, $post['published_when']+TIMEZONE);
        $post['published_time'] = date(TIME_FORMAT, $post['published_when']+TIMEZONE);

        $post['publishing_date'] = date(DATE_FORMAT, ( $post['published_when']==0 ? time()+TIMEZONE : $post['published_when']+TIMEZONE )) . ' ' . $post['published_time'];
        $post['publishing_end_date'] = ( $post['published_until']!=0 ? date(DATE_FORMAT, $post['published_until']+TIMEZONE) : '' );
        if(strlen($post['publishing_end_date'])>0) {
            $post['publishing_end_date'] .= ' ' . date(TIME_FORMAT, $post['published_until']+TIMEZONE);
        }

        if (file_exists($post['post_link_path'])) {
            $post['create_date'] = date(DATE_FORMAT, filemtime($post['post_link_path'])+TIMEZONE);
            $post['create_time'] = date(TIME_FORMAT, filemtime($post['post_link_path'])+TIMEZONE);
        } else {
            $post['create_date'] = $post['published_date'];
            $post['create_time'] = $post['published_time'];
        }
    } else {
        if ($post['published_when'] > $post['posted_when']) {
            $post['post_date'] = CAT_Helper_DateTime::getDate($post['published_when']);
            $post['post_time'] = CAT_Helper_DateTime::getTime($post['published_when']);
        } else {
            $post['post_date'] = CAT_Helper_DateTime::getDate($post['posted_when']);
            $post['post_time'] = CAT_Helper_DateTime::getTime($post['posted_when']);
        }
        $post['published_date'] = CAT_Helper_DateTime::getDate($post['published_when']);
        $post['published_time'] = CAT_Helper_DateTime::getTime($post['published_when']);
        $post['publishing_date'] = ( $post['published_when']==0 ? CAT_Helper_Datetime::getDateTime() : CAT_Helper_Datetime::getDateTime($post['published_when']) );
        $post['publishing_end_date'] = ( $post['published_until']==0 ? '' : CAT_Helper_Datetime::getDateTime($post['published_until']) );

        if (file_exists($post['post_link_path'])) {
            $post['create_date'] = CAT_Helper_DateTime::getDate(filemtime($post['post_link_path']));
            $post['create_time'] = CAT_Helper_DateTime::getTime($post['post_link_path']);
        } else {
            $post['create_date'] = $post['published_date'];
            $post['create_time'] = $post['published_time'];
        }
    }

    // Get group id, title, and image
    $group_id                = $post['group_id'];
    $post['group_title']     = ( isset($group_map[$group_id]) ? $group_map[$group_id]['title'] : null );
    $post['group_image']     = ( isset($group_map[$group_id]) ? $group_map[$group_id]['image'] : null );
    $post['group_image_url'] = WB_URL."/modules/news_img/images/nopic.png";
    $post['display_image']   = ($post['group_image'] == '') ? "none" : "inherit";
    $post['display_group']   = ($group_id == 0) ? 'none' : 'inherit';
    if ($post['group_image'] != "") {
        $post['group_image_url'] = $post['group_image'];
        $post['group_image'] = "<img class='mod_nwi_grouppic' src='".$post['group_image_url']."' alt='".htmlspecialchars($post['group_title'], ENT_QUOTES | ENT_HTML401)."' title='".htmlspecialchars($TEXT['GROUP'].": ".$post['group_title'], ENT_QUOTES | ENT_HTML401)."' />";
    }

    // fallback to group image if there's no preview image
    $post['post_or_group_image'] = (
        ($post['image'] != "")
        ? $post['post_img']
        : $post['group_image']
    );

    // user
    $post['display_name'] = isset($users[$post['posted_by']]) ? $users[$post['posted_by']]['display_name'] : '<i>'.$users[0]['display_name'] .'</i>';
    $post['username'] = isset($users[$post['posted_by']]) ? $users[$post['posted_by']]['username'] : '<i>'.$users[0]['username'] .'</i>';
    $post['email'] = isset($users[$post['posted_by']]) ? $users[$post['posted_by']]['email'] : '<i>'.$users[0]['email'] .'</i>';

    return $post;

}   // end function mod_nwi_post_process()


/**
 *
 * @access
 * @return
 **/
function mod_nwi_img_get_by_post(int $post_id, bool $render)
{
    global $database, $section_id;

    $settings = mod_nwi_settings_get($section_id);

    $query_img = $database->query(sprintf(
        "SELECT * FROM `%smod_news_img_img` " .
        "WHERE `post_id`=%d " .
        "ORDER BY `position`,`id` ASC",
        TABLE_PREFIX,intval($post_id)
    ));
	
	$thumbsizeraw = explode('x',(string)$settings['imgthumbsize']);

    $images = array();
    if (!empty($query_img) && $query_img->numRows() > 0) {
        while ($row = $query_img->fetchRow()) {
            if($render===true) {
                $images[] = str_replace(
                    array(
                        '[IMAGE]',
                        '[DESCRIPTION]',
						'[THUMB]',
						'[THUMBWIDTH]',
						'[THUMBHEIGHT]',
						'[WB_URL]'
                    ),
                    array(
                        WB_URL.MEDIA_DIRECTORY.'/.news_img/'.POST_ID.'/'.$row['picname'],
                        $row['picdesc'],
						WB_URL.MEDIA_DIRECTORY.'/.news_img/'.POST_ID.'/thumb/'.$row['picname'],
						$thumbsizeraw[0],
						$thumbsizeraw[1],
						WB_URL
                    ),
                    $settings['image_loop']
                );
            } else {
                $images[] = $row;
            }
        }
    }
    
    return $images;
}

// ========== Other ============================================================

/**
 *
 * @access 
 * @return
 **/
function mod_nwi_sections()
{
    global $database;
    $sections = array();
    $query_sections = $database->query(sprintf(
        "SELECT `section_id`,`page_id` FROM `%ssections` " .
        "WHERE `module`='%s' ORDER BY `page_id`,`section_id` ASC",
        TABLE_PREFIX, 'news_img'
    ));
    if ($query_sections->numRows() > 0) {
        while ($sect = $query_sections->fetchRow()) {
            $sections[] = $sect;
        }
    }
    return $sections;
}   // end function mod_nwi_sections()


/**
 *
 * @access
 * @return
 **/
 function mod_nwi_settings_get($section_id)
{
    global $database;
    $query_content = $database->query(sprintf(
        "SELECT * FROM `%smod_news_img_settings` WHERE `section_id`=%d",
        TABLE_PREFIX,
        $section_id
    ));
    if(!empty($query_content)) {
        return $query_content->fetchRow();
    }
    return array();
}   // end function mod_nwi_settings_get()

/**
 *
 * @access 
 * @return
 **/
function mod_nwi_get_dates()
{
    global $admin;

    // get publishedwhen and publisheduntil
    $publishedwhen = jscalendar_to_timestamp(mod_nwi_escapeString($admin->get_post('publishdate')));
    if ($publishedwhen == '' || $publishedwhen < 1) {
        $publishedwhen=0;
    } else {
        if(!defined('CAT_PATH')) {
            $publishedwhen -= TIMEZONE;
        }
    }

    $publisheduntil = jscalendar_to_timestamp(mod_nwi_escapeString($admin->get_post('enddate')), $publishedwhen);
    if ($publisheduntil == '' || $publisheduntil < 1) {
        $publisheduntil=0;
    } else {
        if(!defined('CAT_PATH')) {
            $publisheduntil -= TIMEZONE;
        }
    }
    return array($publishedwhen, $publisheduntil);
}   // end function mod_nwi_get_dates()


/**
 *
 * @access 
 * @return
 **/
function mod_nwi_get_order(int $section_id)
{
    $settings = mod_nwi_settings_get($section_id);
    $order_by  = 'position'; // default
    $direction = 'DESC';
    switch($settings['view_order']) {
        case 1:
            $order_by = "published_when";
            $direction = 'DESC';
            break;
        case 2:
            $order_by = "published_until";
            $direction = 'DESC';
            break;
        case 3:
            $order_by = "posted_when";
            $direction = 'DESC';
            break;
        case 4:
            $order_by = "post_id";
            $direction = 'DESC';
            break;
    }
    return array($order_by,$direction);
}   // end function mod_nwi_get_order()

/**
 *
 * @access 
 * @return
 **/
function mod_nwi_get_query_extra()
{
    global $database;
    $query_extra = '';

    // ----- filter by group? --------------------------------------------------
    if (isset($_GET['g']) and is_numeric($_GET['g'])) {
        $query_extra = " AND group_id = '".$_GET['g']."'";
    }

    // ----- filter by date?  --------------------------------------------------
    if(
           isset($_GET['m'])      && is_numeric($_GET['m'])
        && isset($_GET['y'])      && is_numeric($_GET['y'])
        && isset($_GET['method']) && is_numeric($_GET['method'])
    ) {
        $startdate = mktime(0, 0, 0, $_GET['m'], 1, $_GET['y']);
        $enddate   = mktime(0, 0, 0, $_GET['m']+1, 1, $_GET['y']);
        switch ($_GET['method']) {
        case 0:
            $date_option = "posted_when";
            break;
        case 1:
            $date_option = "published_when";
            break;
        }
        $query_extra .= " AND ".$date_option." >= '$startdate' AND ".$date_option." < '$enddate'";
    }

    // ----- filter by tags? ---------------------------------------------------
    if(isset($_GET['tags']) && strlen($_GET['tags'])) {
        $filter_posts = array();
        $tags = mod_nwi_escape_tags($_GET['tags']);
        $r = $database->query(
            "SELECT `t2`.`post_id` FROM `".TABLE_PREFIX."mod_news_img_tags` as `t1` ".
            "JOIN `".TABLE_PREFIX."mod_news_img_tags_posts` AS `t2` ".
            "ON `t1`.`tag_id`=`t2`.`tag_id` ".
            "WHERE `tag` IN ('".implode("', '", $tags)."') ".
            "GROUP BY `t2`.`post_id`"
        );
        while ($row=$r->fetchRow()) {
            $filter_posts[] = $row['post_id'];
        }
        if (count($filter_posts)>0) {
            $query_extra.= " AND `t1`.`post_id` IN (".implode(',', array_values($filter_posts)).") ";
        }
    }

    return $query_extra;
}   // end function mod_nwi_get_query_extra()

/**
 *
 * @access 
 * @return
 **/
function mod_nwi_get_sizes($section_id)
{
    $settings = mod_nwi_settings_get($section_id);
    // preview images size
    $previewwidth = $previewheight = $thumbwidth = $thumbheight = '';
    if (substr_count($settings['resize_preview'], 'x')>0) {
        list($previewwidth, $previewheight) = explode('x', $settings['resize_preview'], 2);
    }
    if (substr_count($settings['imgthumbsize'], 'x')>0) {
        list($thumbwidth, $thumbheight) = explode('x', $settings['imgthumbsize'], 2);
    }
    return array(
        $previewwidth,
        $previewheight,
        $thumbwidth,
        $thumbheight
    );
}   // end function mod_nwi_get_sizes()



// if file exists, find new name by adding a number
function mod_nwi_find_free_filename($mod_nwi_file_dir,$imagename)
{
    if (file_exists($mod_nwi_file_dir.$imagename)) {
        $num = 1;
        $f_name = pathinfo($mod_nwi_file_dir.$imagename, PATHINFO_FILENAME);
        $suffix = pathinfo($mod_nwi_file_dir.$imagename, PATHINFO_EXTENSION);
        while (file_exists($mod_nwi_file_dir.$f_name.'_'.$num.'.'.$suffix)) {
            $num++;
        }
        $imagename = $f_name.'_'.$num.'.'.$suffix;
    }
    return $imagename;
}

function mod_nwi_byte_convert($bytes)
{
    $symbol = array(' bytes', ' KB', ' MB', ' GB', ' TB');
    $exp = 0;
    $converted_value = 0;
    if ($bytes > 0) {
        $exp = floor(log($bytes) / log(1024));
        $converted_value = ($bytes / pow(1024, floor($exp)));
    }
    return sprintf('%.2f '.$symbol[$exp], $converted_value);
}   // end function mod_nwi_byte_convert()

function mod_nwi_escapeString($string)
{
    global $database;
    if(method_exists($database,'escapeString')) {
        return $database->escapeString($string);
    } else {
        if(defined('CAT_PATH')) {
            $quoted = $database->conn()->quote($string);
            $quoted = substr_replace($quoted,'',0,1);
            $quoted = substr_replace($quoted,'',-1,1);
            return $quoted;
        }
    }
}

function mod_nwi_return_bytes($val)
{
    $val  = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    $val  = intval($val);
    switch ($last) {
        case 'g':
            $val *= 1024;
            // no break
        case 'm':
            $val *= 1024;
            // no break
        case 'k':
            $val *= 1024;
    }

    return $val;
}

function mod_nwi_create_file(string $filename, ?string $filetime=null, ?string $postID=null, ?string $sectionID=null, ?string $pageID=null)
{
    global $page_id, $section_id, $post_id;

    if(!empty($postID)) {
        $post_id = $postID;
    }
    // on copy/move, the ID of the new sections is passed; in all other cases
    // we use the ID of the current section
    if(empty($sectionID)) {
        $sectionID = $section_id;
    }
    if(empty($pageID)) {
        $pageID = $page_id;
    }

    // We need to create a new file
    // First, delete old file if it exists
    if (file_exists($filename)) {
        $filetime = !empty($filetime) ? $filetime :  filemtime($filename);
        unlink($filename);
    } else {
        $filetime = !empty($filetime) ? $filetime : time();
    }
    // The depth of the page directory in the directory hierarchy
    // '/pages' is at depth 1
    $pages_dir_depth = count(explode('/', PAGES_DIRECTORY))-1;
    // Work-out how many ../'s we need to get to the index page
    $index_location = '../';
    for ($i = 0; $i < $pages_dir_depth; $i++) {
        $index_location .= '../';
    }

    // Write to the filename
    $content = ''.
'<?php
$page_id = '.$pageID.';
$section_id = '.$sectionID.';
$post_id = '.$post_id.';

define("POST_SECTION", $section_id);
define("POST_ID", $post_id);
require("'.$index_location.'config.php");
require(WB_PATH."/index.php");
?>';
    if ($handle = fopen($filename, 'w+')) {
        fwrite($handle, $content);
        fclose($handle);
        if ($filetime) {
            touch($filename, $filetime);
        }
        change_mode($filename);
    }
}

/**
 *
 * @access 
 * @return
 **/
function mod_nwi_escape_tags($tags)
{
    global $database;
    $tags = explode(",", $tags);
    foreach($tags as $i => $tag) {
        $tags[$i] = mod_nwi_escapeString($tag);
    }
    return $tags;
}   // end function mod_nwi_escape_tags()


/**
 * resize image
 *
 * return values:
 *    true - ok
 *    1    - image is smaller than new size
 *    2    - invalid type (unable to handle)
 *
 * @param $src    - image source
 * @param $dst    - save to
 * @param $width  - new width
 * @param $height - new height
 * @param $crop   - 0=no, 1=yes
 **/
function mod_nwi_image_resize($src, $dst, $width, $height, $crop=0)
{
    //var_dump($src);
    if (!list($w, $h) = getimagesize($src)) {
        return 2;
    }
    $type = strtolower(pathinfo($src,PATHINFO_EXTENSION));
    if ($type == 'jpeg') {
        $type = 'jpg';
    }
    switch ($type) {
        case 'bmp':
            if(!function_exists('imagecreatefromwbmp')) {
                return 2;
            } else {
                try{
                    $img = imagecreatefromwbmp($src);
                } catch (\Exception $e) {
                    return 2;
                }
            }
            break;
        case 'gif':
            if(!function_exists('imagecreatefromgif')) {
                return 2;
            } else {
                try{
                    $img = imagecreatefromgif($src);
                } catch (\Exception $e) {
                    return 2;
                }
            }
            break;
        case 'jpg':
            if(!function_exists('imagecreatefromjpeg')) {
                return 2;
            } else {
                try{
                    $img = imagecreatefromjpeg($src);
                } catch (\Exception $e) {
                    return 2;
                }
            }
            break;
        case 'png':
            if(!function_exists('imagecreatefrompng')) {
                return 2;
            } else {
                try{
                    $img = imagecreatefrompng($src);
                } catch (\Exception $e) {
                    return 2;
                }
            }
            break;
		case 'webp':
            if(!function_exists('imagecreatefromwebp')) {
                return 2;
            } else {
                try{
                    $img = imagecreatefromwebp($src);
                } catch (\Exception $e) {
                    return 2;
                }
            }
            break;	
        default: return 2;
    }

    // resize
    if ($crop) {
        if ($w < $width or $h < $height) {
            return 1;
        }
        $ratio = max($width/$w, $height/$h);
        $h = $height / $ratio;
        $x = ($w - $width / $ratio) / 2;
        $w = $width / $ratio;
    } else {
        if ($w < $width and $h < $height) {
            return 1;
        }
        $ratio = min($width/$w, $height/$h);
        $width = $w * $ratio;
        $height = $h * $ratio;
        $x = 0;
    }

    $new = imagecreatetruecolor($width, $height);

    // preserve transparency
    if ($type == "gif" or $type == "png") {
        imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
        imagealphablending($new, false);
        imagesavealpha($new, true);
    }

    imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);

    switch ($type) {
        case 'bmp': imagewbmp($new, $dst); break;
        case 'gif': imagegif($new, $dst); break;
        case 'jpg': imagejpeg($new, $dst); break;
        case 'png': imagepng($new, $dst); break;
		case 'webp': imagewebp($new, $dst); break;
    }
    return true;
}


function mod_nwi_replacements()
{
    // all known placeholders
    $vars = array(
        'BACK',                         // back to list link
        'CONTENT',                      // content_short + content_long
        'CONTENT_BLOCK2',               // optional block 2
        'CONTENT_LONG',                 // long content
        'CONTENT_SHORT',                // short content (teaser)
        'CREATED_DATE',                 // post added
        'CREATED_TIME',                 // post added time
        'DISPLAY_GROUP',                // wether to show the group name
        'DISPLAY_IMAGE',                // wether to show the preview image
        'DISPLAY_NAME',                 // user's (who posted) display name
        'DISPLAY_PREVIOUS_NEXT_LINKS',  // wether to show prev/next
        'EMAIL',                        // user's (who posted) email address
        'GROUP_ID',                     // ID of the group the post is linked to
        'GROUP_IMAGE',                  // image of the group
        'GROUP_IMAGE_URL',              // image url
        'GROUP_TITLE',                  // group title
        'IMAGE',                        // preview image
		'IMAGE_URL',					// URL of preview image without <img src>
        'IMAGES',                       // gallery images
        'LINK',                         // "read more" link
		'HREF',							// link to post detail including href=""
		'AOPEN',						// complete <a href=""> if long text or gallery exists
		'ACLOSE',						// closing </a> if long text or gallery exists
        'MODI_DATE',                    // post modification date
        'MODI_TIME',                    // post modification time
        'NEXT_LINK',                    // next link
        'NEXT_PAGE_LINK',               // next page link
        'OF',                           // text "of" ("von")
        'OUT_OF',                       // text "out of" ("von")
        'PAGE_TITLE',                   // page title
        'POST_ID',                      // ID of the post
        'POST_OR_GROUP_IMAGE',          // use group image if there's no preview image
        'PREVIOUS_LINK',                // prev link
        'PREVIOUS_PAGE_LINK',           // prev page link
        'PUBLISHED_DATE',               // published date
        'PUBLISHED_TIME',               // published time
        'SHORT',                        // alias for CONTENT_SHORT
        'SHOW_READ_MORE',               // wether to show "read more" link
        'TAGS',                         // tags
        'TEXT_AT',                      // text for "at" ("um")
        'TEXT_BACK',                    // text for "back" ("zurück")
        'TEXT_LAST_CHANGED',            // text for "last changed" ("zuletzt geändert")
        'TEXT_NEXT_POST',                // text for "next post" ("nächster Beitrag")
        'TEXT_O_CLOCK',                 // text for "o'clock" ("Uhr")
        'TEXT_ON',                      // text for "on" ("am")
        'TEXT_POSTED_BY',               // text for "posted by" ("verfaßt von")
        'TEXT_PREV_POST',               // text for "previous post" ("voriger Beitrag")
        'TEXT_READ_MORE',               // text for "read more" ("Weiterlesen")
        'TITLE',                        // post title (heading)
        'USER_ID',                      // user's (who posted) ID
        'USERNAME',                     // user's (who posted) username
    );
    $default_replacements = array(
        
    );
    return array($vars,$default_replacements);
}

function mod_nwi_display_news_items(
	$group_id = 0,                  // IDs of news to show, matching defined $group_id_type (default:=0, all news, 0..N, or array(2,4,5,N) to limit news to IDs matching $group_id_type)
	$max_news_items = 10,           // maximum number of news shown (default:= 10, min:=1, max:= 999)
	$max_news_length = -1,          // maximum length of the short news text shown (default:=-1 => full news length)
	$lang_id = 'AUTO',              // language file to load and lang_id used if $lang_filer = true (default:= auto, examples: AUTO, DE, EN)
	$strip_tags = true,             // true:=remove tags from short and long text (default:=true); false:=don´t strip tags
	$allowed_tags = '<p><a><img>',  // tags not striped off (default:='<p><a><img>')
	$custom_placeholder = false,    // false:= none (default), array('MY_VAR_1' => '%TAG%#', ... 'MY_VAR_N' => '#regex_N#' ...)
	$sort_by = 1,                   // 1:=position (default), 2:=posted_when, 3:=published_when, 4:=random order
	$sort_order = 1,                // 1:=descending (default), 2:=ascending
	$not_older_than = 0,            // 0:=disabled (default), 0-999 (only show news `published_when` date <=x days; 12 hours:=0.5)
    $is_not_older_than = 0,         // alias for not_older_than
	$group_id_type = 'group_id',    // type used by group_id to extract news entries (supported: 'group_id', 'page_id', 'section_id', 'post_id')
	$lang_filter = false,           // flag to enable language filter (default:= false, show only news from a news page, which language fits $lang_id)
    $skip = null,
    $tags = null,
    $groups_on_tags = false,        // wether to use the group_id if $skip or $tags is set
    $view = null,                   // CSS view to use
    $aslist = false
) {
	$output = mod_nwi_get_news_items(
		$options = array(
			'group_id_type'      => $group_id_type,
			'group_id'           => $group_id,
			'max_news_items'     => $max_news_items,
			'max_news_length'    => $max_news_length,
			'strip_tags'         => $strip_tags,
			'allowed_tags'       => $allowed_tags,
			'custom_placeholder' => $custom_placeholder,
			'sort_by'            => $sort_by,
			'sort_order'         => $sort_order,
			'not_older_than'     => $not_older_than,
            'is_not_older_than'  => $is_not_older_than,
			'lang_id'            => $lang_id,
			'lang_filter'        => $lang_filter,
            'skip'               => $skip,
            'tags'               => $tags,
            'groups_on_tags'     => $groups_on_tags,
            'view'               => $view,
            'aslist'             => $aslist,
		)
	);
	echo $output;
}

function mod_nwi_get_news_items($options=array())
{
	global $wb, $database, $LANG;

	// default settings
	$defaults = array(
		'group_id_type' => 'group_id',    // type used by group_id to extract news entries (supported: 'group_id', 'page_id', 'section_id', 'post_id')
		'group_id' => 0,                  // IDs of news to show, matching defined $group_id_type (default:=0, all news, 0..N, or array(2,4,5,N) to limit news to IDs matching $group_id_type)
		'start_news_item' => 0,           // start showing news from the Nth news item onwards (default:= 0, min:=-999, max:= 999); Note: -1: last item, -2: 2nd last etc.
		'max_news_items' => 10,           // maximum number of news shown (default:= 10, min:=1, max:= 999)
		'max_news_length' => -1,          // maximum length of the short news text shown (default:=-1 => full news length)
		'strip_tags' => true,             // true:=remove tags from short and long text (default:=true); false:=dont strip tags
		'allowed_tags' => '<p><a><img>',  // tags not striped off (default:='<p><a><img>')
		'sort_by' => 1,                   // 1:=position (default), 2:=posted_when, 3:=published_when, 4:=random order
		'sort_order' => 1,                // 1:=descending (default), 2:=ascending
		'not_older_than' => 0,            // 0:=disabled (default), 0-999 (only show news `published_when` date <=x days; 12 hours:=0.5)
        'is_not_older_than' => 0,
		'lang_id' => 'AUTO',              // language file to load and lang_id used if $lang_filer = true (default:= auto, examples: AUTO, DE, EN)
		'lang_filter' => false,	          // flag to enable language filter (default:= false, show only news from a news page, which language fits $lang_id)
        'skip' => null,                   // do not show posts with the given list of tags (default:=none)
        'tags' => null,                   // show posts with only the given list of tags
        'groups_on_tags' => false,        // wether to use the group_id if $skip or $tags is set
        'view' => 'default',              // use css from subfolder ('default','faq',...)
        'aslist' => false                 // unordered list of titles
	);

	// merge defaults and options array and remove unsupported keys
	$settings = array_merge($defaults, $options);
	foreach($settings as $key => $value) {
		if(!array_key_exists($key, $defaults)) {
			unset($settings[$key]);
		}
	}

	// export variables into function scope
	extract($settings);

	/**
	 * Sanitize user specified function parameters
	 */
	mod_nwi_sanitize_input($group_id, 'i{0;0;999}');
	mod_nwi_sanitize_input($start_news_item, 'i{0;-999;999}');
	mod_nwi_sanitize_input($max_news_items, 'i{10;1;999}');
	mod_nwi_sanitize_input($max_news_length, 'i{-1;0;250}');
	mod_nwi_sanitize_input($strip_tags, 'b');
	mod_nwi_sanitize_input($allowed_tags, 's{TRIM}');
	mod_nwi_sanitize_input($sort_by, 'i{1;1;5}');
	mod_nwi_sanitize_input($sort_order, 'i{1;1;2}');
	mod_nwi_sanitize_input($not_older_than, 'd{0;0;999}');
    mod_nwi_sanitize_input($is_not_older_than, 'd{0;0;999}');
	mod_nwi_sanitize_input($group_id_type, 'l{group_id;group_id;page_id;section_id;post_id}');
	mod_nwi_sanitize_input($lang_filter, 'b');
    mod_nwi_sanitize_input($skip,'s{TRIM|STRIP|ENTITY}');
    mod_nwi_sanitize_input($tags,'s{TRIM|STRIP|ENTITY}');
    mod_nwi_sanitize_input($groups_on_tags,'b');
    mod_nwi_sanitize_input($view,'s{TRIM|STRIP|ENTITY}');

    $sql_group_id = null;
    $sql_not_older_than = null;
    $sql_lang_filter = null;
    $sql_sort_order = null;
    $sql_limit = null;

    // the "tags" param may be passend by a page link
    if(!strlen($tags) && isset($_GET['tags']) ) {
        $tags = $_GET['tags'];
        mod_nwi_sanitize_input($tags,'s{TRIM|STRIP|ENTITY}');
    }

    // ---------- group handling -----------------------------------------------

    // show all news items if 0 is contained in group_id array
	if (is_array($group_id) && in_array(0, $group_id)) $group_id = 0;

    // check for multiple groups or single group values
	if (is_array($group_id)) {
		// SQL query for multiple groups
		$sql_group_id = "t1.`$group_id_type` IN (" . implode(',', $group_id) . ")";
	} else {
		// SQL query for single or empty groups
		$sql_group_id = ($group_id) ? "t1.`$group_id_type` = '$group_id'" : '1';
	}

    // ---------- time filter --------------------------------------------------
	$server_time = time();
	$sql_not_older_than = '1';
    if($is_not_older_than > 0) {
        $not_older_than = $is_not_older_than;
    }
	if ($not_older_than > 0) {
		$sql_not_older_than = ' (t1.`published_when` >= \'' . ($server_time - ($not_older_than * 24 * 60 * 60)) . '\')';
	}

    // ---------- language filter ----------------------------------------------
    $sql_lang_filter = '1';
	if ($lang_filter) {
        if(!defined('CAT_PATH')) {
		    $page_ids = getPageIdsByLanguage($lang_id);
        } else {
            $pages = CAT_Helper_Page::getPagesForLang($lang_id);
            $page_ids = array();
            foreach($pages as $i => $pg) {
                $pages_ids[] = $pg['page_id'];
            }
        }
		if (count($page_ids) > 0) {
			$sql_lang_filter = 't1.`page_id` in (' . implode(',', $page_ids) . ')';
		}
	}

    // ---------- tag filter ---------------------------------------------------
    $filter_posts = array();
    $sql_filter_posts = null;
    if(!empty($skip)) {
        $skip_tags = explode(",",urldecode($skip));
        foreach($skip_tags as $i => $t) {
            $skip_tags[$i] = mod_nwi_escapeString($t);
        }
        $r = $database->query(
            "SELECT `t2`.`post_id` FROM `".TABLE_PREFIX."mod_news_img_tags` as `t1` ".
            "JOIN `".TABLE_PREFIX."mod_news_img_tags_posts` AS `t2` ".
            "ON `t1`.`tag_id`=`t2`.`tag_id` ".
            "WHERE `tag` IN ('".implode("', '", $skip_tags)."') ".
            "GROUP BY `t2`.`post_id`"
        );
        while($row=$r->fetchRow()) {
            $filter_posts[] = $row['post_id'];
        }
        if(count($filter_posts)>0) {
            $sql_filter_posts = " AND `t1`.`post_id` NOT IN (".implode(',',array_values($filter_posts)).") ";
        }
    }
    if(!empty($tags)) {
        $tags = explode(",",urldecode($tags));
        foreach($tags as $i => $t) {
            $tags[$i] = mod_nwi_escapeString($t);
        }
        $r = $database->query(
            "SELECT `t2`.`post_id` FROM `".TABLE_PREFIX."mod_news_img_tags` as `t1` ".
            "JOIN `".TABLE_PREFIX."mod_news_img_tags_posts` AS `t2` ".
            "ON `t1`.`tag_id`=`t2`.`tag_id` ".
            "WHERE `tag` IN ('".implode("', '", $tags)."') ".
            "GROUP BY `t2`.`post_id`"
        );
        while($row=$r->fetchRow()) {
            $filter_posts[] = $row['post_id'];
        }
        if(count($filter_posts)>0) {
            $sql_filter_posts = " AND `t1`.`post_id` IN (".implode(',',array_values($filter_posts)).") ";
        } else { // no posts for any tag
            $sql_filter_posts = " AND `t1`.`post_id`='-1'";
        }
        if($groups_on_tags == false) {
            $sql_group_id = '1';
        }
    }

    // ---------- sort order ---------------------------------------------------
    $order_by_options = array('t1.`position`', 't1.`posted_when`', 't1.`published_when`', 'RAND()');
	$sql_order_by = $order_by_options[$sort_by - 1];
	$sql_sort_order = ($sort_order == 1) ? 'DESC' : 'ASC';

    $sql = "SELECT t1.*
		FROM `%smod_news_img_posts` as t1
		WHERE t1.`active`='1'
		AND $sql_group_id
		AND $sql_lang_filter
		AND (t1.`published_when` = '0' or t1.`published_when` <= '$server_time')
		AND (t1.`published_until` = '0' OR t1.`published_until` >= '$server_time')
		AND $sql_not_older_than
        $sql_filter_posts
		GROUP BY t1.`post_id`
		ORDER BY $sql_order_by $sql_sort_order
	";

    // ---------- limit --------------------------------------------------------
    // start from N-th last news item if $start_news_items is negative
	if ($start_news_item < 0) {
		// find total news items matching SQL query
		$results = $database->query(sprintf($sql,TABLE_PREFIX));
		$total_news = ($results) ? $results->numRows() : 0;
		// adjust start_news_item to the N-th last news item
		$start_news_item = $total_news + $start_news_item;
		if ($start_news_item < 0) $start_news_item = 0;
	}

    $sql .= "
		LIMIT $start_news_item, $max_news_items
	";

    $query_posts = $database->query(sprintf($sql,TABLE_PREFIX));

    if(!empty($query_posts) && $query_posts->numRows()>0) {
        // map group index to title
        list($groups,$pages) = mod_nwi_get_all_groups(0,0);
        $group_map = array();
        foreach($groups as $pg => $sections) {
            foreach($sections as $section_id => $grps) {
                foreach($grps as $i => $g) {
                    $group_map[$g['group_id']] = ( empty($g['title']) ? $TEXT['NONE'] : $g['title'] );
                }
            }
        }
        // get users
        $users = mod_nwi_users_get();
        // add "unknown" user
        $users[0] = array(
            'username' => 'unknown',
            'display_name' => 'unknown',
            'email' => ''
        );
        while($post = $query_posts->fetchRow()) {
            $post['content_short'] = ($strip_tags) ? strip_tags($post['content_short'], $allowed_tags) : $post['content_short'];
			$post['content_long'] = ($strip_tags) ? strip_tags($post['content_long'], $allowed_tags) : $post['content_long'];
			// shorten news text to defined news length (-1 for full text length)
			if ($max_news_length != -1 && strlen($row['content_short']) > $max_news_length) {
				// truncate text if user asked for using CakePHP truncate function
				$post['content_short'] = nia_truncate($post['content_short'], $max_news_length);
			}
            // tags
            $tags = mod_nwi_get_tags_for_post($post['post_id']);
            foreach ($tags as $i => $tag) {
                $tags[$i] = "<span class=\"mod_nwi_tag\" id=\"mod_nwi_tag_".$post['post_id']."_".$i."\""
                          . (strlen($tag['tag_color'])>0 ? " style=\"background-color:".$tag['tag_color']."\"" : "" ) .">"
                          . "<a href=\"".$wb->page_link(PAGE_ID)."?tags=".$tag."\">".$tag."</a></span>";
            }
            // gallery images - wichtig für link "weiterlesen"  SHOW_READ_MORE
            $images = mod_nwi_img_get_by_post($post['post_id'],false);
            $anz_post_img = count($images);
			$post_href_link = 'href="'. $post['post_link'].'"';
			$post_a_open_tag = '<a '.$post_href_link.'>';
			$post_a_close_tag = '</a>';
            // no "read more" link if no long content
            if ( (strlen($post['content_long']) < 9) && ($anz_post_img < 1)) {
                $post['post_link'] = '#" onclick="javascript:void(0);return false;" style="cursor:no-drop;';
				$post_href_link = '';
				$post_a_open_tag ='';
				$post_a_close_tag ='';
            }
            $posts[] = mod_nwi_post_process($post, $post['section_id'], $users);
        }
    }

    if(!empty($settings['aslist']) && $settings['aslist']==true) {
        $output = array('<ul class="nia_posts">');
        foreach($posts as $p) {
            $output[] = '<li class="nia_post"><a href="'.WB_URL.PAGES_DIRECTORY.$p['link'].PAGE_EXTENSION.'">'.$p['title'].'</a></li>';
        }
        $output[] = '</ul>';
        return implode("\n",$output);
    }

    $tpl = '/templates/default/view.phtml';

    $tpl_data = mod_nwi_posts_render($section_id,$posts,0);

    ob_start();
        include __DIR__.$tpl;
        $content = ob_get_contents();
    ob_end_clean();

    if(defined('CAT_PATH')) {
        $sql = 'SELECT `drop_file` FROM `%smod_droplets_extension` WHERE  `drop_droplet_name`="%s" AND `drop_page_id`=%d';
        $stmt = $database->query(sprintf($sql,TABLE_PREFIX,'getnewsitems',PAGE_ID));
        $data = $stmt->fetchAll();
        if(is_array($data)) {
            foreach($data as $i => $d) {
                if(pathinfo($d['drop_file'],PATHINFO_BASENAME)=='frontend.css') {
                    if(pathinfo(pathinfo($d['drop_file'],PATHINFO_DIRNAME),PATHINFO_BASENAME) != $view) {
                        CAT_Helper_Droplet::unregister_droplet_css('getNewsItems',PAGE_ID);
                    }
                }
            }
        }
        if(!CAT_Helper_Droplet::is_registered_droplet_css('getNewsItems',PAGE_ID)) {
            CAT_Helper_Droplet::register_droplet_css('getNewsItems',PAGE_ID,'news_img','views/'.$view.'/frontend.css');
        }
    }

    return $content;
}

/**
 * Function to sanitize function input parameters
*/
function mod_nwi_sanitize_input(&$input, $filter)
{
	// $input...        input variable to filter
	// $filter...       filter to apply for input variable
	//	Numeric filter: b|i¦d{default;min;max}
	//	List filter:    l{default;string1;string2;..;stringN}
	//	String filter:  s{TRIM|STRIP|ENTITY}

	// check if a valid filter was supplied
	if (!preg_match('#(b|i|d|s|l)#i', $filter, $match)) {
		echo 'Filter: <b>' . htmlentities($filter) . '</b> is no valid filter expression.';
		die;
	}

	// convert user input to array (allows to handle single values and array identical)
	$temp = is_array($input) ? $input : array($input);

	// evaluate filter expressions
	$filter_type = strtolower($match[1]);
	switch ($filter_type) {
		case 'b': case 'i': case 'd': // numeric filter ($input can be single value or array)
			// check if optional filter values are supplied (default, min, max)
			$advanced_filter = (preg_match('#(i|d)\{([-.0-9]+);([-.0-9]+);([-.0-9]+)\}#i', $filter, $match));

			// loop over input values
			foreach($temp as $key => $value) {
				// force type casting to either integer or double
				if ($filter_type == 'b') $temp[$key] = (boolean) $temp[$key];
				if ($filter_type == 'i') $temp[$key] = (int) $temp[$key];
				if ($filter_type == 'd') $temp[$key] = (double) $temp[$key];

				// check if value is within min/max range, if not use default value
				if ($advanced_filter) {
					$temp[$key] = ($temp[$key] >= $match[3] && $temp[$key] <= $match[4] ) ? $temp[$key] : $match[2];
				}
			}
			break;

		case 'l': // list filter
			// check for correct list filter: l{default;list1;list2;..;listN}
			if (!preg_match('#(l)\{([^;]+?);(.+)\}#i', $filter, $match)) {
				echo 'List filter: <b>' . htmlentities($filter) . '</b> invalid. Usage: <b>l{default;list1;list2;..listN}</b>.';
				die;
			}

			// create array with list values from regular expression
			$list_values = explode(';', $match[3]);

			// loop over input values
			foreach($temp as $key => $value) {
				// check if value is in list (return default value if not in list)
				$temp[$key] = (in_array($value, $list_values) ? $value : $match[2]);
			}
			break;

		case 's': // string filter
			// check for correct string filter: s{TRIM|STRIP|ENTITY}
			if (!preg_match('#(s)\{(.+)\}#i', $filter, $match)) {
				echo 'String filter: <b>' . htmlentities($filter) . '</b> invalid. Usage: <b>s{STRIP;TRIM;ENTITIES}</b>.';
				die;
			}

			// get filter options from regular expression
			$filter_options = strtoupper($match[2]);

			// loop over input values
			foreach($temp as $key => $value) {
				// check if value is in list (return default value if not in list)
				if (strpos($filter_options, 'STRIP') !== false) $temp[$key] = strip_tags($temp[$key]);
				if (strpos($filter_options, 'TRIM') !== false) $temp[$key] = trim($temp[$key]);
				if (strpos($filter_options, 'ENTITIES') !== false) $temp[$key] = htmlentities($temp[$key]);
			}
			break;
	}

	// revert user input back to array or single value
	$input = is_array($input) ? $temp : $temp[0];
}


if (!function_exists('mod_nwi_get_section_array')) {
    /**
     * @brief  Get Array with all the details of a section by using the section_id.
     *
     * @param integer $iSectionID
     * @return array
     */
    function mod_nwi_get_section_array($iSectionID)
    {
        $aSection = array();
        if (isset($iSectionID) && $iSectionID > 0) {
            global $database;
            $sSql = 'SELECT * FROM `{TP}sections` WHERE `section_id`=%d';
            if ($rSections = $database->query(sprintf($sSql, (int)$iSectionID))) {
                $aSection = $rSections->fetchRow(MYSQLI_ASSOC);
            }
        }
        return $aSection;
    }
}
