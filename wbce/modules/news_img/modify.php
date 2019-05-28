<?php
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

// Must include code to stop this file being access directly
if(!defined('WB_PATH')) { exit("Cannot access this file directly"); }

require_once __DIR__.'/functions.inc.php';

$database->query("DELETE FROM `".TABLE_PREFIX."mod_news_img_posts`  WHERE `page_id` = '$page_id' and `section_id` = '$section_id' and `title`=''");
$database->query("DELETE FROM `".TABLE_PREFIX."mod_news_img_groups`  WHERE `page_id` = '$page_id' and `section_id` = '$section_id' and `title`=''");

//overwrite php.ini on Apache servers for valid SESSION ID Separator
if(function_exists('ini_set')) {
	ini_set('arg_separator.output', '&amp;');
}
$section_key = $admin->getIDKEY($section_id);

?>
<div class="mod_news_img">
    <input type="button" class="mod_img_news_add" value="<?php echo $MOD_NEWS_IMG['ADD_POST']; ?>" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/news_img/add_post.php?page_id=<?php echo $page_id; ?>&amp;section_id=<?php echo  $section_id; ?>&amp;section_key=<?php echo  $section_key; ?>';"  />	
	<input class="mod_news_img_help"  type="button" onclick="window.open('<?php echo WB_URL; ?>/modules/news_img/readme.html','readme','width=800,height=600,top=50,left=50')" name="help" value="Readme" />
    <input  class="mod_img_news_options" type="button" value="<?php echo $MOD_NEWS_IMG['OPTIONS']; ?>" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/news_img/modify_settings.php?page_id=<?php echo $page_id; ?>&amp;section_id=<?php echo  $section_id; ?>&amp;section_key=<?php echo  $section_key; ?>';"  />
    <input  class="mod_img_news_add_group" type="button" value="<?php echo $MOD_NEWS_IMG['ADD_GROUP']; ?>" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/news_img/add_group.php?page_id=<?php echo $page_id; ?>&amp;section_id=<?php echo  $section_id; ?>&amp;section_key=<?php echo  $section_key; ?>';"  />
    <br />

    <h2><?php echo $TEXT['MODIFY'].'/'.$TEXT['DELETE'].' '.$TEXT['POST']; ?></h2>

<?php
    // Get settings
    $query_settings = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_news_img_settings` WHERE `section_id` = '$section_id'");
    if($query_settings->numRows() > 0)
    {
        $fetch_settings = $query_settings->fetchRow();
        $setting_view_order = ($fetch_settings['view_order']);
    } else {
	$setting_view_order = 0;
    }
    
    $order_by = "position";
    if($setting_view_order==1) $order_by = "published_when"; 
    if($setting_view_order==2) $order_by = "published_until"; 
    if($setting_view_order==3) $order_by = "posted_when"; 
    if($setting_view_order==4) $order_by = "post_id"; 

    // map order to lang string
    $lang_map = array(
        0 => $TEXT['CUSTOM'],
        1 => $TEXT['PUBL_START_DATE'],
        2 => $TEXT['PUBL_END_DATE'],
        3 => $TEXT['SUBMITTED'],
        4 => $TEXT['SUBMISSION_ID']
    );

    $FTAN = $admin->getFTAN();
?>

    <div style="text-align:right;font-style:italic"><?php echo $MOD_NEWS_IMG['ORDERBY'], ": <span class=\"\" title=\"", $MOD_NEWS_IMG['ORDER_CUSTOM_INFO'] ,"\">", $lang_map[$setting_view_order] ?></span></div>

<?php
    // Loop through existing posts

// Include the ordering class
require_once(WB_PATH.'/framework/class.order.php');
// Create new order object and reorder
$order = new order(TABLE_PREFIX.'mod_news_img_posts', 'position', 'post_id', 'section_id');
$order->clean($section_id);
    
    $query_posts = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_news_img_posts` WHERE `section_id` = '$section_id' ORDER BY `$order_by` DESC");
    if($query_posts->numRows() > 0) {
    	$num_posts = $query_posts->numRows();
?>
	<form name="modify_<?php echo $section_id; ?>" action="<?php echo WB_URL; ?>/modules/news_img/manage_posts.php" method="post" enctype="multipart/form-data">
	
	<?php echo $FTAN; ?>
	<input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
	<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
	<input type="hidden" name="savegoback" id="savegoback" value="" />
    	<table class="striped dragdrop_form" id="mod_news_post_list">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th>ID</th>
                    <th><?php echo $TEXT['TITLE'] ?></th>
                    <th><?php echo $TEXT['GROUP'] ?></th>
                    <th><?php echo $TEXT['ACTIVE'] ?></th>
                    <th><?php echo $TEXT['PUBL_START_DATE']; ?></th>
                    <th><?php echo $TEXT['PUBL_END_DATE']; ?></th>
					<th></th>
					<th></th>
					<th></th>
                    <th><input type="checkbox" name="manage_posts[]" id="<?php echo $section_id; ?>_all" value="all" onchange='javascript: var boxes = document.forms["modify_<?php echo $section_id; ?>"].elements[ "manage_posts[]" ]; for (var i=0, len=boxes.length; i<len; i++) { boxes[i].checked = this.checked;}' /></th>
					<th></th>
                    <th></th>
                </tr>
            </thead> 
            <tbody>
    	<?php
    	while($post = $query_posts->fetchRow()) {
	        $post_id_key = $admin->getIDKEY($post['post_id']);
		if(defined('WB_VERSION') && (version_compare(WB_VERSION, '2.8.3', '>'))) 
    			$post_id_key = $post['post_id'];
    		?>
    		<tr id="post_id:<?php echo $post_id_key; ?>">
			<td <?php if($setting_view_order == 0) echo 'class="dragdrop_item"';?>>&nbsp;</td>
    			<td>
    				<a href="<?php echo WB_URL; ?>/modules/news_img/modify_post.php?page_id=<?php echo $page_id; ?>&amp;section_id=<?php echo $section_id; ?>&amp;post_id=<?php echo $post_id_key; ?>" title="<?php echo $TEXT['MODIFY']; ?>">
    					<span class="fa fa-fw fa-edit"></span>
    				</a>
    			</td>
                <td>
                    <span title="Post ID"><?php echo $post['post_id'] ?></span>
                </td>
    			<td>
    				<a href="<?php echo WB_URL; ?>/modules/news_img/modify_post.php?page_id=<?php echo $page_id; ?>&amp;section_id=<?php echo $section_id; ?>&amp;post_id=<?php echo $post_id_key; ?>">
    					<?php echo $post['title']; ?>
    				</a>
    			</td>
    			<td><?php
    				// Get group title
    				$query_title = $database->query("SELECT `title` FROM `".TABLE_PREFIX."mod_news_img_groups` WHERE `group_id` = '".$post['group_id']."'");
    				if($query_title->numRows() > 0) {
    					$fetch_title = $query_title->fetchRow();
    					echo ($fetch_title['title']);
    				} else {
    					echo $TEXT['NONE'];
    				}
    				?>
    			</td>
    			<td>
				<a href="<?php echo WB_URL; ?>/modules/news_img/activate_post.php?page_id=<?php echo $page_id; ?>&amp;section_id=<?php echo $section_id; ?>&amp;post_id=<?php echo $post_id_key; ?>&amp;value=<?php echo $post['active']!=0 ? '0':'1'; ?>');" title="<?php if($post['active'] == 1) { echo $MOD_NEWS_IMG['DEACTIVATE_POST']; } else { echo $MOD_NEWS_IMG['ACTIVATE_POST']; }  ?>">
    				<?php if($post['active'] == 1) { echo '<span class="fa fa-fw fa-eye nwi-active"></span>'; } else { echo '<span class="fa fa-fw fa-eye-slash nwi-inactive"></span>'; } ?></a>
    			</td>
    			<td>
<?php
	$start = $post['published_when'];
	$end = $post['published_until'];
	$t = time();
	$icon = '';
	if($start<=$t && $end==0) {
        $icon='<span class="fa fa-fw fa-calendar-o"></span>';
    }
	elseif(($start<=$t || $start==0) && $end>=$t) {
		$icon='<span class="fa fa-fw fa-calendar-check-o nwi-active"></span>';
    }
	else {
		$icon='<span class="fa fa-fw fa-calendar-times-o nwi-inactive"></span>';
    }
?>
                    <?php echo ( $start>0 ? gmdate(DATE_FORMAT.' '.TIME_FORMAT, $start+TIMEZONE) : '') ?></td>
                <td><?php echo ( $end>0   ? gmdate(DATE_FORMAT.' '.TIME_FORMAT, $end+TIMEZONE)   : '') ?></td>
				<td><?php echo $icon; ?></td>
				<td>
			
<?php
    // Icons
    if(($post['position'] != $num_posts)&&($setting_view_order == 0)) {
?>
    				<a href="<?php echo WB_URL; ?>/modules/news_img/move_down.php?page_id=<?php echo $page_id; ?>&amp;section_id=<?php echo $section_id; ?>&amp;post_id=<?php echo $post_id_key; ?>" title="<?php echo $TEXT['MOVE_UP']; ?>">
    					<span class="fa fa-fw fa-arrow-circle-up mod_news_img_arrow"></span>
    				</a>
					
<?php } else {
    echo '<span class="fa fa-fw fa-arrow-circle-up nwi-disabled mod_news_img_arrow"></span>';
}
?>					</td>
					<td>
<?php    if(($post['position'] != 1)&&($setting_view_order == 0)) { ?>
    				<a href="<?php echo WB_URL; ?>/modules/news_img/move_up.php?page_id=<?php echo $page_id; ?>&amp;section_id=<?php echo $section_id; ?>&amp;post_id=<?php echo $post_id_key; ?>" title="<?php echo $TEXT['MOVE_DOWN']; ?>">
    					<span class="fa fa-fw fa-arrow-circle-down mod_news_img_arrow"></span>
    				</a>					
<?php } else {
     echo '<span class="fa fa-fw fa-arrow-circle-down nwi-disabled mod_news_img_arrow"></span>';
} ?>
					</td>
					<td>
    <?php echo "<input type=\"checkbox\" name=\"manage_posts[]\" value=".$post['post_id']." onchange='javascript: document.getElementById(\"${section_id}_all\").checked &= this.checked' />";
?>
				</td>
				<td>
    				<a href="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']; ?>', '<?php echo WB_URL; ?>/modules/news_img/delete_post.php?page_id=<?php echo $page_id; ?>&amp;section_id=<?php echo $section_id; ?>&amp;post_id=<?php echo $post_id_key; ?>');" title="<?php echo $TEXT['DELETE']; ?>">
    					<span class="fa fa-fw fa-close nwi-delete"></span>
    				</a>
    			</td>
			<td <?php if($setting_view_order == 0) echo 'class="dragdrop_item"';?>>&nbsp;</td>
    		</tr>
<?php
	}
?>
        </tbody>
        </table>


	<div class="mod_news_post_tools"><?php echo $MOD_NEWS_IMG['ACTION'];  ?>:
	
	<select name="action">
	<option value="copy"><?php echo $MOD_NEWS_IMG['COPY']; ?></option>
	<option value="move"><?php echo $MOD_NEWS_IMG['MOVE']; ?></option>
	<option value="delete"><?php echo $MOD_NEWS_IMG['DELETE']; ?></option>
	<option value="activate"><?php echo $MOD_NEWS_IMG['ACTIVATE']; ?></option>
	<option value="deactivate"><?php echo $MOD_NEWS_IMG['DEACTIVATE']; ?></option>
	</select>
	
    		   
		    <input name="continue" type="submit" onclick="return checkActionAndPosts()" value="<?php echo $MOD_NEWS_IMG['CONTINUE']; ?>" />
    	 </div>
	</form>

<script type="text/javascript">
        var LOAD_DRAGDROP = true;
        var ICONS = '<?php echo WB_URL."/modules/news_img/images" ?>';
</script>
	
<?php
} else {
	// count groups
	$query_groups = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_news_img_groups` WHERE `section_id` = '$section_id'");
	$num_groups = $query_groups->numRows();
	if ($num_groups != 0) echo $TEXT['NONE_FOUND'];
	else {
	    // news with images
            $query_nwi = $database->query("SELECT `section_id` FROM `".TABLE_PREFIX."sections`"
            . " WHERE `module` = 'news_img' AND `section_id` != '$section_id' ORDER BY `section_id` ASC");
	    $importable_sections = $query_nwi->numRows();
	    // classical news
            $query_news = $database->query("SELECT `section_id` FROM `".TABLE_PREFIX."sections`"
            . " WHERE `module` = 'news' ORDER BY `section_id` ASC");
	    $importable_sections += $query_news->numRows();
	    // topics
	    $topics_names = array();
	    $query_tables = $database->query("SHOW TABLES");
            while ($table_info = $query_tables->fetchRow()) {
	        $table_name = $table_info[0];    
		$topics_name=preg_replace('/'.TABLE_PREFIX.'mod_/','',$table_name);
	    	$res = $database->query("SHOW COLUMNS FROM `$table_name` LIKE 'topic_id'");
		if ($res->numRows() > 0) {
		    $topics_names[] = $topics_name;
        	    $query_topics = $database->query("SELECT `section_id` FROM `".TABLE_PREFIX."sections`"
        	    . " WHERE `module` = '$topics_name' ORDER BY `section_id` ASC");
		    $importable_sections += $query_topics->numRows();
		}
	    }
	    
	    if($importable_sections>0){
?>
    <h2><?php echo $MOD_NEWS_IMG['IMPORT'].' '.$TEXT['SECTION']; ?></h2>
    <form name="import" action="<?php echo WB_URL; ?>/modules/news_img/import.php" method="post" enctype="multipart/form-data">
    <?php echo $FTAN; ?>
    <input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
    <input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
    <table>
    <tr>
        <td class="setting_name"><?php echo $MOD_NEWS_IMG['IMPORT']; ?>:</td>
        <td class="setting_value">
            <select name="source_id">
<?php
            if ($query_nwi->numRows() > 0) {
                echo '<option disabled value="0">[--- News with Images ---]</option>';
                // Loop through possible sections
                while ($source = $query_nwi->fetchRow()) {
                    echo '<option value="'.$source['section_id'].'">'.$TEXT['SECTION'].' '.$source['section_id'].'</option>';
                }
            }
            if ($query_news->numRows() > 0) {
                echo '<option disabled value="0">[--- (classical) News ---]</option>';
                // Loop through possible sections
                while ($source = $query_news->fetchRow()) {
                    echo '<option value="'.$source['section_id'].'">'.$TEXT['SECTION'].' '.$source['section_id'].'</option>';
                }
            }
	    foreach($topics_names as $topics_name){
        	$query_topics = $database->query("SELECT `section_id` FROM `".TABLE_PREFIX."sections`"
        	. " WHERE `module` = '$topics_name' ORDER BY `section_id` ASC");
        	if ($query_topics->numRows() > 0) {
                    echo '<option disabled value="0">[--- '.$topics_name.' ---]</option>';
                    // Loop through possible sections
                    while ($source = $query_topics->fetchRow()) {
                	echo '<option value="'.$source['section_id'].'">'.$TEXT['SECTION'].' '.$source['section_id'].'</option>';
                    }
        	}
	    }
?>
            </select>
        </td>
    </tr>
    <tr>
    	<td align="left">
    		<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;" />
    	</td>
    	<td>
    	</td>
    </tr>
    </table>

    </form>
<?php
	    } else echo $TEXT['NONE_FOUND'];
	}
}

?>

    <h2><?php echo $TEXT['MODIFY'].'/'.$TEXT['DELETE'].' '.$TEXT['GROUP']; ?></h2>

<?php
$order = new order(TABLE_PREFIX.'mod_news_img_groups', 'position', 'group_id', 'section_id');
$order->clean($section_id);

// Loop through existing groups
$query_groups = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_news_img_groups` WHERE `section_id` = '$section_id' ORDER BY `position` ASC");
if($query_groups->numRows() > 0) {
	$num_groups = $query_groups->numRows();
	?>
    	<table class="striped dragdrop_form">
	<?php
	while($group = $query_groups->fetchRow()) {
		$group_id_key = $admin->getIDKEY($group['group_id']);
		if(defined('WB_VERSION') && (version_compare(WB_VERSION, '2.8.3', '>'))) 
    		    $group_id_key = $group['group_id'];
		?>
    		<tr id="group_id:<?php echo $group_id_key; ?>">
			<td class="dragdrop_item">&nbsp;</td>
			<td style="width:20px">
				<a href="<?php echo WB_URL; ?>/modules/news_img/modify_group.php?page_id=<?php echo $page_id; ?>&amp;section_id=<?php echo $section_id; ?>&amp;group_id=<?php echo $group_id_key; ?>" title="<?php echo $TEXT['MODIFY']; ?>">
					<span class="fa fa-fw fa-edit"></span>
				</a>
			</td>		
			<td>
				<a href="<?php echo WB_URL; ?>/modules/news_img/modify_group.php?page_id=<?php echo $page_id; ?>&amp;section_id=<?php echo $section_id; ?>&amp;group_id=<?php echo $group_id_key; ?>">
					<?php echo $group['title'].' (ID: '.$group['group_id'].')'; ?>
				</a>
			</td>
			<td style="width:150px">
				<?php echo $TEXT['ACTIVE'].': '; if($group['active'] == 1) { echo $TEXT['YES']; } else { echo $TEXT['NO']; } ?>
			</td>
			<td style="width:20px">
			<?php if($group['position'] != 1) { ?>
				<a href="<?php echo WB_URL; ?>/modules/news_img/move_up.php?page_id=<?php echo $page_id; ?>&amp;section_id=<?php echo $section_id; ?>&amp;group_id=<?php echo $group_id_key; ?>" title="<?php echo $TEXT['MOVE_UP']; ?>">
					<span class="fa fa-fw fa-arrow-circle-up mod_news_img_arrow"></span>
				</a>
			<?php } ?>
			</td>
			<td style="width:20px">
			<?php if($group['position'] != $num_groups) { ?>
				<a href="<?php echo WB_URL; ?>/modules/news_img/move_down.php?page_id=<?php echo $page_id; ?>&amp;section_id=<?php echo $section_id; ?>&amp;group_id=<?php echo $group_id_key; ?>" title="<?php echo $TEXT['MOVE_DOWN']; ?>">
					<span class="fa fa-fw fa-arrow-circle-down mod_news_img_arrow"></span>
				</a>
			<?php } ?>
			</td>
			<td style="width:20px">
				<a href="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']; ?>', '<?php echo WB_URL; ?>/modules/news_img/delete_group.php?page_id=<?php echo $page_id; ?>&amp;group_id=<?php echo $group_id_key; ?>');" title="<?php echo $TEXT['DELETE']; ?>">
					<span class="fa fa-fw fa-close nwi-delete"></span>
				</a>
			</td>
			<td class="dragdrop_item">&nbsp;</td>
		</tr>
<?php
	}
?>
	</table>
	<?php
} else {
	echo $TEXT['NONE_FOUND'];
}
