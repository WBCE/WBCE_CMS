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

require_once __DIR__.'/functions.inc.php';

// Get id
if ((!isset($_POST['continue']) or !isset($_POST['action'])) or !isset($_POST['section_id']) or !isset($_POST['page_id'])) {
    header("Location: ".ADMIN_URL."/pages/index.php");
    exit(0);
} 

$admin_header = FALSE;
// Include WB admin wrapper script
require WB_PATH.'/modules/admin.php';
if (!$admin->checkFTAN()){
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']
	 .' (FTAN) '.__FILE__.':'.__LINE__,
         ADMIN_URL.'/pages/index.php');
    $admin->print_footer();
    exit();
} else $admin->print_header();

$action='';
if($_POST['action']=='copy'||$_POST['action']=='move'||$_POST['action']=='delete'||$_POST['action']=='activate'||$_POST['action']=='deactivate') {
    $action=$_POST['action'];
} else {
    header("Location: ".ADMIN_URL."/pages/index.php");
    exit(0);
} 
$section_id = intval($_POST['section_id']);
$page_id = intval($_POST['page_id']);
$FTAN = $admin->getFTAN();
$value = "";
$activate = "";
if($action=="activate") {
    $value = "?value=1";
    $activate = 1;
}
if($action=="deactivate") {
    $action = "activate";
    $value = "?value=0";
    $activate = 0;
}   

?>
<div class="mod_news_img">
   
    <h2><?php echo $MOD_NEWS_IMG['MANAGE_POSTS']; ?></h2>
    <form name="manage" action="<?php echo WB_URL; ?>/modules/news_img/<?php echo $action; ?>_post.php<?php echo $value; ?>" method="post" enctype="multipart/form-data">
<?php
    echo $FTAN;
    $posts=array();
    if(isset($_POST['manage_posts'])&&is_array($_POST['manage_posts'])) $posts=$_POST['manage_posts'];
    foreach($posts as $pid) 
        if(is_numeric($pid))
	    echo 	'<input type="hidden" name="manage_posts[]" value="'.$pid.'" />';
	   
?>
	<input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
	<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
<?php 
    if($action=="delete")
	echo $TEXT['DELETE'].' '.$TEXT['POST'].' ';
    elseif($activate === 1) 
        echo $MOD_NEWS_IMG['ACTIVATE_POST'].' '; 
    elseif ($activate === 0) 
        echo $MOD_NEWS_IMG['DEACTIVATE_POST'].' ';
    if($action=="delete" || $action=="activate"){
	foreach($posts as $pid) 
	    if(is_numeric($pid))
	    	echo "$pid, ";
	echo $TEXT['ARE_YOU_SURE'];
?>
    <table>
    <tr>
    	<td align="left">
    		<input name="ok" type="submit" value="OK" style="width: 100px; margin-top: 5px;" />
    	</td>
    	<td align="right">
    		<input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" style="width: 100px; margin-top: 5px;" />
    	</td>
    </tr>
    </table>
<?php	
    } else {
?>	
    <table>
    <tr>
        <td class="setting_name"><?php echo $MOD_NEWS_IMG[strtoupper($action)].' '.$MOD_NEWS_IMG['TO']  ?>:</td>
        <td class="setting_value">
            <select name="group">
<?php
        // We encode the group_id, section_id and page_id into an urlencoded serialized array.
        // So we have a single string that we can submit safely and decode it when receiving.
            echo '<option value="'.urlencode(serialize(array('g' => 0, 's' => $section_id, 'p' => $page_id))).'">'
            . $TEXT['NONE']." (".$TEXT['CURRENT']." ".$TEXT['SECTION']." ".$section_id.")</option>";
            $query = $database->query("SELECT `group_id`,`title` FROM `".TABLE_PREFIX."mod_news_img_groups`"
            . " WHERE `section_id` = '$section_id' ORDER BY `position` ASC");
            if ($query->numRows() > 0) {
                // Loop through groups
                while ($group = $query->fetchRow()) {
                    echo '<option value="'
                        . urlencode(serialize(array('g' => intval($group['group_id']), 's' => $section_id, 'p' => $page_id)))
		        .'">'.$group['title'].' ('.$TEXT['CURRENT']." ".$TEXT['SECTION'].' '.$section_id.')</option>';
                }
            }
            // this was just assignment to a group within the local section. Let's find out which sections exist
        // and offer to move the post to another news_img section
            $query_sections = $database->query("SELECT `section_id`,`page_id` FROM `".TABLE_PREFIX."mod_news_img_settings`"
            . " WHERE `section_id` != '$section_id' ORDER BY `page_id`,`section_id` ASC");
            $pid = $page_id;
            if ($query_sections->numRows() > 0) {
                // Loop through all news_img sections, do sanity checks and filter out the current section which is handled above
                while ($sect = $query_sections->fetchRow()) {
                    if ($sect['section_id'] != $section_id) {
                        if ($sect['page_id'] != $pid) { // for new pages insert a separator
                            $pid = intval($sect['page_id']);
                            $page_title = "";
                            $page_details = "";
                            if ($pid != 0) { // find out the page title and print separator line
                                $page_details = $admin->get_page_details($pid);
                                if (!empty($page_details)) {
                                    $page_title=isset($page_details['page_title'])?$page_details['page_title']:"";
                                    echo '<option disabled value="0">'
                                .'[--- '.$TEXT['PAGE'].' '.$pid.' ('.$page_title.') ---]</option>';
                                }
                            }
                        }
                        if ($pid != 0) {
                            echo '<option value="'.urlencode(serialize(array('g' => 0, 's' => $sect['section_id'], 'p' => $pid))).'">'
                       . $TEXT['NONE']." (".$TEXT['SECTION']." ".$sect['section_id'].")</option>";
                            // now loop through groups of this section, at least for the ones which are not dummy sections
                            $query_groups = $database->query("SELECT `group_id`,`title` FROM `".TABLE_PREFIX."mod_news_img_groups`"
                    . " WHERE `section_id` = '".intval($sect['section_id'])."' ORDER BY `position` ASC");
                            if ($query_groups->numRows() > 0) {
                                // Loop through groups
                                while ($group = $query_groups->fetchRow()) {
                                    echo '<option value="'
                        . urlencode(serialize(array(
                        'g' => intval($group['group_id']),
                        's' => intval($sect['section_id']),
                        'p' => $pid)))
                    .'">'.$group['title'].' ('.$TEXT['SECTION'].' '.$sect['section_id'].')</option>';
                                }
                            }
                        }
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
<?php
    }
?>
    </form>
</div>
<?php 
// Print admin footer
$admin->print_footer();
