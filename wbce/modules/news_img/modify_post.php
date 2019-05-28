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


// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');
$post_id = $admin->checkIDKEY('post_id', 0, 'GET',true);
if(defined('WB_VERSION') && (version_compare(WB_VERSION, '2.8.3', '>'))) 
    $post_id = intval($_GET['post_id']);
if (!$post_id){
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']
	 .' (IDKEY) '.__FILE__.':'.__LINE__,
         ADMIN_URL.'/pages/index.php');
    $admin->print_footer();
    exit();
}

$FTAN = $admin->getFTAN();
$post_id_key = $admin->getIDKEY($post_id);
if(defined('WB_VERSION') && (version_compare(WB_VERSION, '2.8.3', '>'))) 
    $post_id_key = intval($_GET['post_id']);
$mod_nwi_file_dir .= "$post_id/";
$mod_nwi_thumb_dir = $mod_nwi_file_dir . "thumb/";

// delete image
if (isset($_GET['img_id'])) {
    $img_id = $admin->checkIDKEY('img_id', 0, 'GET');
    if (!$img_id){
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']
	     .' (IDKEY) '.__FILE__.':'.__LINE__,
             ADMIN_URL.'/pages/index.php');
	$admin->print_footer();
	exit();
    }
    $query_img=$database->query("SELECT * FROM `".TABLE_PREFIX."mod_news_img_img` WHERE `id` = '$img_id'");
    $row = $query_img->fetchRow();
 
    if (!$row) {
        echo "Datei existiert nicht!";
    } else {
        unlink($mod_nwi_file_dir.$row['picname']);
        unlink($mod_nwi_thumb_dir.$row['picname']);
    }
    $database->query("DELETE FROM `".TABLE_PREFIX."mod_news_img_img` WHERE `id` = '$img_id'");
}   //end delete

// delete previewimage
if (isset($_GET['post_img'])) {
    $post_img = basename($_GET['post_img']);
    $database->query("UPDATE `".TABLE_PREFIX."mod_news_img_posts` SET `image` = '' WHERE `post_id` = '$post_id'");
    @unlink($mod_nwi_file_dir.$post_img);
    @unlink($mod_nwi_thumb_dir.$post_img);
}   //end delete  preview

// re-order images
if (isset($_GET['id']) && (isset($_GET['up']) || isset($_GET['down']))) {
    require WB_PATH.'/framework/class.order.php';
    $order = new order(TABLE_PREFIX.'mod_news_img_img', 'position', 'id', 'post_id');
    $id = $admin->checkIDKEY('id', 0, 'GET');
    if (!$id){
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']
	     .' (IDKEY) '.__FILE__.':'.__LINE__,
             ADMIN_URL.'/pages/index.php');
	$admin->print_footer();
	exit();
    }
    if (isset($_GET['up'])) {
        $order->move_up(intval($id));
    } else {
        $order->move_down(intval($id));
    }
}

// Get header and footer
$query_content = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_news_img_posts` WHERE `post_id` = '$post_id'");
$fetch_content = $query_content->fetchRow();

if (!defined('WYSIWYG_EDITOR') or WYSIWYG_EDITOR=="none" or !file_exists(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php')) {
    function show_wysiwyg_editor($name, $id, $content, $width, $height)
    {
        echo '<textarea name="'.$name.'" id="'.$id.'" rows="10" cols="1" style="width: '.$width.'; height: '.$height.';">'.$content.'</textarea>';
    }
} else {
    $id_list=array("short","long");
    if(NWI_USE_SECOND_BLOCK){
        $id_list[]="block2";
    }
    require(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php');
}

// split link
$link = $fetch_content['link'];
$parts = explode('/', $link);
$link = array_pop($parts);
$linkbase = implode('/', $parts);
$parts = explode(PAGE_SPACER, $link);
array_pop($parts);
$link = implode(PAGE_SPACER, $parts);

// include jscalendar-setup
$jscal_use_time = true; // whether to use a clock, too
require_once(WB_PATH."/include/jscalendar/wb-setup.php");
$jscal_today = date('Y/m/d H:i', time()+TIMEZONE);
?>
<link href="uploader/css/jquery.dm-uploader.css" rel="stylesheet">
<link href="uploader/styles.css" rel="stylesheet">
<div class="mod_news_img">
    <script src="<?php echo WB_URL; ?>/modules/news_img/js/jquery.furl.js"></script>
    
    <h2><?php echo $TEXT['ADD'].'/'.$TEXT['MODIFY'].' '.$TEXT['POST']; ?></h2>
    <div class="jsadmin jcalendar hide"></div>
    <form name="modify" action="<?php echo WB_URL; ?>/modules/news_img/save_post.php" method="post" enctype="multipart/form-data">
    <?php echo $FTAN; ?>
    <input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
    <input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>" />
    <input type="hidden" name="savegoback" id="savegoback" value="" />

    <table>
    <tr>
    	<td class="setting_name"><?php echo $TEXT['TITLE']; ?>:</td>
    	<td class="setting_value">
    		<input type="text" name="title" id="title<?php echo $page_id ?>" value="<?php echo(htmlspecialchars($fetch_content['title'])); ?>" maxlength="255" />
    	</td>
    </tr>
<?php
    //if(!strlen($fetch_content['title']) && !strlen($fetch_content['link'])): // new post
?>
    <tr>
    	<td class="setting_name"><?php echo $MOD_NEWS_IMG['LINK']; ?>:</td>
    	<td class="setting_value">
    		<?php echo $linkbase ?>/<input type="text" name="link" id="link<?php echo $page_id ?>" value="<?php echo(htmlspecialchars($link)); ?>" maxlength="255" style="width:80%" /><?php echo PAGE_SPACER.$post_id.PAGE_EXTENSION ?>
    	</td>
    </tr>
<?php // endif;?>
    <tr>
    	<td class="setting_name"><?php echo $MOD_NEWS_IMG['PREVIEWIMAGE']; ?>:</td>
    	<td>
<?php
         if ($fetch_content['image'] != "") {
             echo '<img class="img_list" style="float:left;margin-right:15px" src="'.WB_URL.MEDIA_DIRECTORY.'/.news_img/'.$post_id.'/'.$fetch_content['image'].'" /> '.$fetch_content['image'].'<br /><a href="'.WB_URL.'/modules/news_img/modify_post.php?page_id='.$page_id.'&section_id='.$section_id.'&post_id='. $post_id_key.'&post_img='.$fetch_content['image'].'">'.$TEXT['DELETE'].'</a>';
             echo '<input type="hidden" name="previewimage" value="'.$fetch_content['image'].'" />';
         } else {
             echo '<input type="file" name="postfoto" accept="image/*" />  <br />';
         }
?>
		</td>
    </tr>
    <tr>
        <td class="setting_name"><?php echo $TEXT['GROUP']; ?>:</td>
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
                . urlencode(serialize(array('g' => intval($group['group_id']), 's' => $section_id, 'p' => $page_id))).'"';
                    if ($fetch_content['group_id'] == $group['group_id']) {
                        echo ' selected="selected"';
                    }
                    echo '>'.$group['title'].' ('.$TEXT['CURRENT']." ".$TEXT['SECTION'].' '.$section_id.')</option>';
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
    	<td class="setting_name"><?php echo $TEXT['ACTIVE']; ?>:</td>
    	<td class="setting_value">
    		<input type="radio" name="active" id="active_true" value="1" <?php if ($fetch_content['active'] == 1) {
                    echo ' checked="checked"';
} ?> />
    		<a href="#" onclick="javascript: document.getElementById('active_true').checked = true;">
    		<?php echo $TEXT['YES']; ?>
    		</a>
    		&nbsp;
    		<input type="radio" name="active" id="active_false" value="0" <?php if ($fetch_content['active'] == 0) {
                    echo ' checked="checked"';
} ?> />
    		<a href="#" onclick="javascript: document.getElementById('active_false').checked = true;">
    		<?php echo $TEXT['NO']; ?>
    		</a>
    	</td>
    </tr>
    <tr>
    	<td class="setting_name"><?php echo $TEXT['PUBL_START_DATE']; ?>:</td>
    	<td class="setting_value">
    	   <input type="text" id="publishdate" name="publishdate" value="<?php if ($fetch_content['published_when']==0) {
                    print date($jscal_format, time()+TIMEZONE);
} else {
                    print date($jscal_format, $fetch_content['published_when']+TIMEZONE);
}?>" style="width:33%;" />
        	<img src="<?php echo THEME_URL ?>/images/clock_16.png" id="publishdate_trigger" style="cursor: pointer;" title="<?php echo $TEXT['CALENDAR']; ?>" alt="<?php echo $TEXT['CALENDAR']; ?>" onmouseover="this.style.background='lightgrey';" onmouseout="this.style.background=''" />
        	<img src="<?php echo THEME_URL ?>/images/clock_del_16.png" style="cursor: pointer;" title="<?php echo $TEXT['DELETE_DATE']; ?>" alt="<?php echo $TEXT['DELETE_DATE']; ?>" onmouseover="this.style.background='lightgrey';" onmouseout="this.style.background=''" onclick="document.modify.publishdate.value=''" />
    	</td>
    </tr>
    <tr>
    	<td class="setting_name"><?php echo $TEXT['PUBL_END_DATE']; ?>:</td>
    	<td class="setting_value">
    	   <input type="text" id="enddate" name="enddate" value="<?php if ($fetch_content['published_until']==0) {
                    print "";
} else {
                    print date($jscal_format, $fetch_content['published_until']+TIMEZONE);
}?>" style="width:33%;" />
        	<img src="<?php echo THEME_URL ?>/images/clock_16.png" id="enddate_trigger" style="cursor: pointer;" title="<?php echo $TEXT['CALENDAR']; ?>" alt="<?php echo $TEXT['CALENDAR']; ?>" onmouseover="this.style.background='lightgrey';" onmouseout="this.style.background=''" />
        	<img src="<?php echo THEME_URL ?>/images/clock_del_16.png" style="cursor: pointer;" title="<?php echo $TEXT['DELETE_DATE']; ?>" alt="<?php echo $TEXT['DELETE_DATE']; ?>" onmouseover="this.style.background='lightgrey';" onmouseout="this.style.background=''" onclick="document.modify.enddate.value=''" />
    	</td>
    </tr>
    </table>

    <table>
    <tr>
    	<td valign="top"><?php echo $TEXT['SHORT']; ?>:</td>
    </tr>
    <tr>
    	<td>
    	<?php
        show_wysiwyg_editor("short", "short", htmlspecialchars($fetch_content['content_short']), "100%", "350px");
        ?>
    	</td>
    </tr>
    <tr>
    	<td valign="top"><?php echo $TEXT['LONG']; ?>:</td>
    </tr>
    <tr>
    	<td>
<?php
    show_wysiwyg_editor("long", "long", htmlspecialchars($fetch_content['content_long']), "100%", "650px");
?>
    	</td>
    </tr>
<?php
    if(NWI_USE_SECOND_BLOCK){
?>
    <tr>
    	<td valign="top"><?php echo $TEXT['BLOCK']; ?> 2:</td>
    </tr>
    <tr>
    	<td>
<?php
        show_wysiwyg_editor("block2", "block2", htmlspecialchars($fetch_content['content_block2']), "100%", "350px");
?>
    	</td>
    </tr>
<?php
}
?>
    </table>

<?php


// Include the ordering class
require_once(WB_PATH.'/framework/class.order.php');
// Create new order object and reorder
$order = new order(TABLE_PREFIX.'mod_news_img_img', 'position', 'id', 'post_id');
$order->clean($post_id);

//show all images
// 2014-04-10 by BlackBird Webprogrammierung: added position to sort order
$query_img = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_news_img_img` WHERE `post_id` = ".$post_id." ORDER BY `position`,`id` ASC");

if ($query_img->numRows() > 0) {
    echo '<div id="fotoshow"><a name="fs"></a><h3>'.$MOD_NEWS_IMG['GALLERYIMAGES'].'</h3><table class="dragdrop_form"><tbody>';
    $i=1;

    // 2014-04-10 by BlackBird Webprogrammierung:
    // added up/down links
    $first=true;
    $last=$query_img->numRows();

    while ($row = $query_img->fetchRow()) {
        $row_id_key = $admin->getIDKEY($row['id']);
	if(defined('WB_VERSION') && (version_compare(WB_VERSION, '2.8.3', '>'))) 
    	    $row_id_key = $row['id'];
        $up='<span style="display:inline-block;width:20px;"></span>';
        $down=$up;
        if (!$first) {
            $up = '<a href="'.WB_URL.'/modules/news_img/modify_post.php?page_id='.$page_id.'&section_id='.$section_id.'&post_id='. $post_id_key.'&id='.$row_id_key.'&up=1">'
                . '<img src="'.THEME_URL.'/images/up_16.png"  class="mod_news_img_arrow" /></a>';
        }
        if ($i!=$last) {
            $down = '<a href="'.WB_URL.'/modules/news_img/modify_post.php?page_id='.$page_id.'&section_id='.$section_id.'&post_id='. $post_id_key.'&id='.$row_id_key.'&down=1">'
                  . '<img src="'.THEME_URL.'/images/down_16.png"  class="mod_news_img_arrow" /></a>';
        }
        echo '<tr id="img_id:'.$row_id_key.'">'.
	     '<td class="dragdrop_item">&nbsp;</td>'.
             '<td>'.$up.$down.'</td>',
             '<td><a href="javascript:void(0);" onmouseover="XBT(this, {id:\'tt'.$i.'\'})"><img class="img_list" src="'.WB_URL.MEDIA_DIRECTORY.'/.news_img/'.$post_id.'/thumb/'.$row["picname"].'" /></a><div id="tt'.$i.'" class="xbtooltip"><img src="'.WB_URL.MEDIA_DIRECTORY.'/.news_img/'.$post_id.'/'.$row["picname"].'" /></div></td>',
             '<td>'.$row["picname"].'<br /><input type="text" name="picdesc['.$row["id"].']" value="'.$row["picdesc"].'"></td>',
             '<td><a onclick="return confirm(\''.$MOD_NEWS_IMG['DELETEIMAGE'].'\')" href="'.WB_URL.'/modules/news_img/modify_post.php?page_id='.$page_id.'&section_id='.$section_id.'&post_id='.$post_id_key.'&img_id='.$row_id_key.'#fs"><img src="'.THEME_URL.'/images/delete_16.png" /></a></td>'.
             '<td class="dragdrop_item">&nbsp;</td>'.
	     '</tr>';
        $i++;
        $first=false;
    }
    echo '</tbody></table></div>';
}

// load imagemaxsize for the current section
$query_settings = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_news_img_settings` WHERE `section_id` = '$section_id'");
$fetch_settings = $query_settings->fetchRow();
$imgmaxsize = $fetch_settings['imgmaxsize'];
?>
<noscript>
    <!-- Formular -->
    <div id="fotos"><h3><?php echo $MOD_NEWS_IMG['IMAGEUPLOAD']?></h3>
          <input type="file" name="foto[]" accept="image/*" />  <br />
          <input type="file" name="foto[]" accept="image/*" />  <br />
          <input type="file" name="foto[]" accept="image/*" />  <br />
          <input type="file" name="foto[]" accept="image/*" />   <br />
          <input type="file" name="foto[]" accept="image/*" />   <br />
          <input type="file" name="foto[]" accept="image/*" />   <br />
    </div>
</noscript>

    <main role="main" class="container">
      <div class="row">
        <div class="col-md-6 col-sm-12">
          
          <!-- Our markup, the important part here! -->
          <div id="drag-and-drop-zone" class="dm-uploader p-5">
            <h3 class="mb-5 mt-5 text-muted"><?php echo $MOD_NEWS_IMG['DRAG_N_DROP_HERE']; ?></h3>

            <div class="btn btn-primary btn-block mb-5">
                <input type="file" title=<?php echo "'".$MOD_NEWS_IMG['CLICK_TO_ADD']."'"; ?> />
            </div>
          </div><!-- /uploader -->

        </div>
        <div class="col-md-6 col-sm-12">
          <div class="card h-100">
            <ul class="list-unstyled p-2 d-flex flex-column col" id="files">
              <li class="text-muted text-center empty"><?php echo $MOD_NEWS_IMG['NO_FILES_UPLOADED']; ?></li>
            </ul>
          </div>
        </div>
      </div><!-- /file list -->

      <div class="row">
        <div class="col-12">
           <div class="card h-100">
            <ul class="list-group list-group-flush" id="status">
            </ul>
          </div>
        </div>
      </div> <!-- /status messages -->

    </main> <!-- /container -->

    <table>
    <tr>
    	<td align="left">
    		<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;" />
    		<input name="save" type="submit" onclick="document.getElementById('savegoback').value='1'" value="<?php echo $MOD_NEWS_IMG['SAVEGOBACK']; ?>" style="width: 200px; margin-top: 5px;" />
    	</td>
    	<td align="right">
    		<input type="button" value="<?php echo $MOD_NEWS_IMG['GOBACK'] ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" style="width: 100px; margin-top: 5px;" />
    	</td>
    </tr>
    </table>
    </form>


<script type="text/javascript"> 
    var NWI_UPLOAD_URL = "<?php echo WB_URL."/modules/news_img/uploader/upload.php?post_id=$post_id_key"; ?>";
    var NWI_COMPLETE_MESSAGE = "<?php echo $MOD_NEWS_IMG['COMPLETE_MESSAGE']; ?>";
    var NWI_IMAGE_MAX_SIZE = <?php echo $imgmaxsize;?>;
</script>

    <script src="<?php echo WB_URL."/modules/news_img/uploader/js/jquery.dm-uploader.js"; ?>"></script>
    <script src="<?php echo WB_URL."/modules/news_img/uploader/ui.js"; ?>"></script>
    <script src="<?php echo WB_URL."/modules/news_img/uploader/config.js"; ?>"></script>

    <!-- File item template -->
    <script type="text/html" id="files-template">
      <li class="media">
        <div class="media-body mb-1">
          <p class="mb-2">
            <strong>%%filename%%</strong> - Status: <span class="text-muted">Waiting</span>
          </p>
          <div class="progress mb-2">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
              role="progressbar"
              style="width: 0%" 
              aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
            </div>
          </div>
          <hr class="mt-1 mb-1" />
        </div>
      </li>
    </script>

    <!-- Status item template -->
    <script type="text/html" id="status-template">
      <li class="list-group-item"><strong>%%message%%</strong></li>
    </script>

<script type="text/javascript">

    $('#title<?php echo $page_id ?>').furl({id:'link<?php echo $page_id ?>', seperate: '<?php echo PAGE_SPACER ?>' });
    
    /* Cross-Browser Tooltip von Mathias Karstädt steht unter einer Creative Commons Namensnennung 3.0 Unported Lizenz.
  http://webmatze.de/ein-einfacher-cross-browser-tooltip-mit-javascript-und-css/ */
  (function(window, document, undefined){
    var XBTooltip = function( element, userConf, tooltip) {
      var config = {
        id: userConf.id|| undefined,
        className: userConf.className || undefined,
        x: userConf.x || 20,
        y: userConf.y || 20,
        text: userConf.text || undefined
      };
      var over = function(event) {
        tooltip.style.display = "block";
      },
      out = function(event) {
        tooltip.style.display = "none";
      },
      move = function(event) {
        event = event ? event : window.event;
        if ( event.pageX == null && event.clientX != null ) {
          var doc = document.documentElement, body = document.body;
          event.pageX = event.clientX + (doc && doc.scrollLeft || body && body.scrollLeft || 0) - (doc && doc.clientLeft || body && body.clientLeft || 0);
          event.pageY = event.clientY + (doc && doc.scrollTop  || body && body.scrollTop  || 0) - (doc && doc.clientTop  || body && body.clientTop  || 0);
        }
        tooltip.style.top = (event.pageY+config.y) + "px";
        tooltip.style.left = (event.pageX+config.x) + "px";
      }
      if (tooltip === undefined && config.id) {
        tooltip = document.getElementById(config.id);
        if (tooltip) tooltip = tooltip.parentNode.removeChild(tooltip)
      }
      if (tooltip === undefined && config.text) {
        tooltip = document.createElement("div");
        if (config.id) tooltip.id= config.id;
        tooltip.innerHTML = config.text;
      }
      if (config.className) tooltip.className = config.className;
      tooltip = document.body.appendChild(tooltip);
      tooltip.style.position = "absolute";
      element.onmouseover = over;
      element.onmouseout = out;
      element.onmousemove = move;
      over();
    };
    window.XBTooltip = window.XBT = XBTooltip;
  })(this, this.document);
    
	Calendar.setup(
		{
			inputField  : "publishdate",
			ifFormat    : "<?php echo $jscal_ifformat ?>",
			button      : "publishdate_trigger",
			firstDay    : <?php echo $jscal_firstday ?>,
			<?php if (isset($jscal_use_time) && $jscal_use_time==true) {
    ?>
				showsTime   : "true",
				timeFormat  : "24",
			<?php
} ?>
			date        : "<?php echo $jscal_today ?>",
			range       : [1970, 2037],
			step        : 1
		}
	);
	Calendar.setup(
		{
			inputField  : "enddate",
			ifFormat    : "<?php echo $jscal_ifformat ?>",
			button      : "enddate_trigger",
			firstDay    : <?php echo $jscal_firstday ?>,
			<?php if (isset($jscal_use_time) && $jscal_use_time==true) {
        ?>
				showsTime   : "true",
				timeFormat  : "24",
			<?php
    } ?>
			date        : "<?php echo $jscal_today ?>",
			range       : [1970, 2037],
			step        : 1
		}
	);

  
  </script>
</div>

<?php
// Print admin footer
$admin->print_footer();
