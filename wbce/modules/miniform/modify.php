<?php
/**
 *
 * @category        modules
 * @package         miniform
 * @author          Ruud Eisinga / Dev4me
 * @link			http://www.dev4me.nl/modules-snippets/opensource/miniform/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         0.8
 * @lastmodified    november 26, 2015
 *
 */

if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }
if(!file_exists(WB_PATH.'/modules/miniform/languages/'.LANGUAGE.'.php')) {
	require_once(WB_PATH.'/modules/miniform/languages/EN.php');
} else {
	require_once(WB_PATH.'/modules/miniform/languages/'.LANGUAGE.'.php');
}
require_once ('functions.php');

if(file_exists(WB_PATH.'/modules/miniform/new_frontend.css') && !file_exists(WB_PATH.'/modules/miniform/frontend.css') ) {
	$path = WB_PATH.'/modules/miniform/';
	rename($path.'new_frontend.css',$path.'frontend.css');
}
if(file_exists(WB_PATH.'/modules/miniform/new_frontend.css')) {
	echo "<h2>If you upgraded this module from an older version and you want to use the new responsive forms you need to rename the file new_frontend.css to frontend.css</h2>";
}
$mform = new mForm();
$d = 0;
if(isset($_GET['delete'])) {
	$d = intval($_GET['delete']);
	$mform->delete_record($d);
}
if (!isset($links)) {
	$links = array();
	$mform->build_pagelist(0,$page_id);
}
$sel = ' selected';

$query = "SELECT * FROM ".TABLE_PREFIX."mod_miniform WHERE section_id = '$section_id'";
$get_settings = $database->query($query);
$settings = $get_settings->fetchRow();
if(!$settings['email']) $settings['email'] = SERVER_EMAIL;
if(!$settings['subject']) $settings['subject'] = $MF['SUBJECT'];
$manage_url = WB_URL.'/modules/miniform/modify_template.php?page_id='.$page_id.'&section_id='.$section_id.'&name=';
$delete_url = ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&section_id='.$section_id.'&delete=';
?>
<script>
$(function() {
	<?php if (!$d) { ?> $(".msgtable").hide(); <?php } ?>
	$(".msgtable .msg").hide(); 
	$(".msgtable td.line").click(function(){
		$(this).children(".msg").slideToggle();
    });
	$(".recved").click(function(){
		$(".msgtable").toggle();
	});
    $("select.templates").on("change", function() {
        var link = $(this).parent().find("a.manage");
        link.attr("href", "<?php echo $manage_url ?>" + $(this).val());
    });
});
</script>
<form action="<?php echo WB_URL ?>/modules/miniform/save.php" method="post"  >
	<input type="hidden" name="page_id" value="<?php echo $page_id ?>" />
	<input type="hidden" name="section_id" value="<?php echo $section_id ?>" />
	<table class="settable" id="mfsettings-<?php echo $section_id ?>" cellpadding="3" cellspacing="3" border="0" style="border:1px solid #28609B; width:100%">
		<tr><td colspan="2"><h2><?php echo $MF['MINIFORM'] ?> - <?php echo $MF['SETTINGS'] ?></h2></td><td><a style="float:right" href="#" class="recved"><?php echo $MF['HISTORY'] ?></a></td></tr>
		<tr><td class="small"><?php echo $MF['TEXT_FORM'] ?>: </td><td><?php echo $mform->getSelectTemplate($settings['template']) ?>  <a class="manage" href="<?php echo $manage_url.$settings['template']?>"><?php echo $MF['MANAGE'] ?></a></td></tr>
		<tr><td><?php echo $MF['TEXT_EMAIL'] ?>: </td><td><input size="50" type="text" name="email" value="<?php echo $settings['email'] ?>" /></td></tr>
		<tr><td><?php echo $MF['TEXT_SUBJECT'] ?>: </td><td><input size="50" type="text" name="subject" value="<?php echo $settings['subject'] ?>" /></td></tr>
		<tr><td><?php echo $MF['TEXT_SUCCESS'] ?>: </td><td>		
				<select name="successpage" style="font-family:monospace;" />
				<option value="0"<?php echo $settings['successpage']=='0' ? $sel : '' ?>><?php echo $MF['TEXT_NOPAGE'] ?></option>
				<?php foreach($links AS $li) {
					$option_link = explode('|',$li);
					$disabled = $option_link[0] == $page_id ? ' disabled':'';
					echo "<option $disabled value=\"".$option_link[0]."\" ".($settings['successpage']==$option_link[0] ? $sel : '').">$option_link[1]</option>\n";
				} ?>
				</select>
		</td></tr>
		<tr>
			<td><input type="submit" value="<?php echo $MF['TEXT_SAVE'] ?>" style="width: 120px; margin-top: 5px;" /></td>
			<td colspan="2" align="right"><input type="button" value="<?php echo $MF['TEXT_CANCEL'] ?>" onclick="javascript: window.location = 'index.php';" style="width: 120px; margin-top: 5px;" /></td>
		</tr>
	</table>
</form>
<?php 
$sub = $mform->get_history($section_id,50);
?>
<table class='msgtable' cellpadding="3" border="0" style="margin-top:25px; border:1px solid #28609B; width:100%">
<tr><th colspan="3"><?php echo $MF['RECEIVED'] ?></th></tr>
<tr>
	<td ><?php echo $MF['MSGID'] ?> - <?php echo $MF['TIMESTAMP'] ?></td>
	<td class="small"><?php echo $MF['REMOVE'] ?> </td>
</tr>
<?php	
	foreach ($sub as $msg) {
		echo "<tr >
				<td style='cursor:pointer' class='line'>".$msg['message_id']." - ".date(DATE_FORMAT.' - '.TIME_FORMAT,$msg['submitted_when']+TIMEZONE)."<div class='msg'>".($msg['data'])."</div></td>
				<td><a href='".$delete_url.$msg['message_id']."'>X</a></td>
			</tr>";
	}
?>
</table>
