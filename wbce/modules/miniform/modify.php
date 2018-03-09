<?php
/**
 *
 * @category        modules
 * @package         miniform
 * @author          Ruud Eisinga / Dev4me
 * @link			http://www.dev4me.nl/modules-snippets/opensource/miniform/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.6 and higher
 * @version         0.12.0
 * @lastmodified    Januari 19, 2018
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
	$url = ADMIN_URL."/pages/modify.php?page_id=$page_id";
	if(isset($_GET['unlink'])) {
		unlink (WB_PATH.'/modules/miniform/new_frontend.css');
	} else {
		echo "<h6 style='background:rgba(231, 76, 60,0.5);padding:10px;border-radius:10px;'>If you upgraded this module from an older version and you want to use the new responsive forms you need to rename the file new_frontend.css to frontend.css.";
		echo '<br>Otherwise <a style="text-decoration:underline" href="'.$url.'&unlink=1">click here</a> to remove the file new_frontend.css</h6>';
	}
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
if(!$settings['emailfrom']) $settings['emailfrom'] = SERVER_EMAIL;
if(!$settings['subject']) $settings['subject'] = $MF['SUBJECT'];
if(!$settings['confirm_subject']) $settings['confirm_subject'] = $MF['SUBJECT'];
$remote = $settings['remote_id'];

$manage_url = ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&section_id='.$section_id.'&mt=1&name=';
$delete_url = ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&section_id='.$section_id.'&delete=';

$cu_checked = $settings['confirm_user'] ? 'checked': '';
$cs_display = $settings['confirm_user'] ? '' : 'style="display:none;"';
$no_store_checked = $settings['no_store'] ? 'checked': '';
$ajax_checked = $settings['use_ajax'] ? 'checked': '';
$rc_checked = $settings['use_recaptcha'] ? 'checked': '';
$rc_display = $settings['use_recaptcha'] ? '' : 'style="display:none;"';
?>
<script>
$(function() {
	<?php if (!$d) { ?> $(".msgtable").hide(); <?php } ?>
	$(".msgtable .msg").hide(); 
	$(".msgt<?php echo $section_id ?> td.line").click(function(e){
		e.preventDefault();
		$(this).children(".msg").slideToggle();
		console.log("click");
    });
	$(".recved<?php echo $section_id ?>").click(function(e){
		e.preventDefault();
		$(".msgt<?php echo $section_id ?>").toggle();
	});
	$(".doremote").click(function(){
		$(".mf_editor").toggle();
		$(".mf_remote").toggle();
	});
	
    $("select.templates").on("change", function() {
        var link = $(this).parent().find("a.manage");
        link.attr("href", "<?php echo $manage_url ?>" + $(this).val());
    });
	$('#rc').click(function() {
		if( $(this).is(':checked')) {
			$(".rc").show();
		} else {
			$(".rc").hide();
		}
	}); 
	$('#cs').click(function() {
		if( $(this).is(':checked')) {
			$(".cs").show();
		} else {
			$(".cs").hide();
		}
	}); 
});
</script>
<?php if (isset($_GET['mt'])) {
	include dirname(__FILE__).'/modify_template.php';
	exit();
} ?>

<form action="<?php echo WB_URL ?>/modules/miniform/save.php" method="post"  >
	<input type="hidden" name="page_id" value="<?php echo $page_id ?>" />
	<input type="hidden" name="section_id" value="<?php echo $section_id ?>" />
	<table class="settable" id="mfsettings-<?php echo $section_id ?>" cellpadding="3" cellspacing="3" border="0">
		<tr><td colspan="2"><h2><?php echo $MF['MINIFORM'] ?> - <?php echo $MF['SETTINGS'] ?></h2></td><td><a style="float:right" href="#" class="recved<?php echo $section_id ?> mf-button"><?php echo $MF['HISTORY'] ?></a></td></tr>
		<tr><td class="small"><?php echo $MF['TEXT_FORM'] ?>: </td><td><?php echo $mform->getSelectTemplate($settings['template']) ?>  <a class="manage mf-button" href="<?php echo $manage_url.$settings['template']?>"><?php echo $MF['MANAGE'] ?></a></td></tr>
		<tr><td><?php echo $MF['TEXT_EMAILFROM'] ?>: </td><td><input class="mf-input wide" size="50" type="text" name="emailfrom" value="<?php echo $settings['emailfrom'] ?>" /></td></tr>
		<tr><td><?php echo $MF['TEXT_EMAIL'] ?>: </td><td><input class="mf-input wide" size="50" type="text" name="email" value="<?php echo $settings['email'] ?>" /></td></tr>
		<tr><td><?php echo $MF['TEXT_SUBJECT'] ?>: </td><td><input class="mf-input wide" size="50" type="text" name="subject" value="<?php echo $settings['subject'] ?>" /></td></tr>
		<tr><td><?php echo $MF['TEXT_CONFIRM_USER'] ?>: </td><td><input type="hidden" name="confirm_user" value="0" /><input type="checkbox" id="cs" name="confirm_user" value="1" <?php echo $cu_checked ?> /></td></tr>
		<tr class="cs" <?php echo $cs_display ?>><td><?php echo $MF['TEXT_CONFIRM_SUBJ'] ?>: </td><td><input class="mf-input wide" size="50" type="text" name="confirm_subject" value="<?php echo $settings['confirm_subject'] ?>" /></td></tr>
		<tr><td><?php echo $MF['TEXT_SUCCESS'] ?>: </td><td>		
				<select  class="mf-input wide" name="successpage" style="font-family:'Courier New',Courier,monospace;font-size:12px;" />
				<option value="0"<?php echo $settings['successpage']=='0' ? $sel : '' ?>><?php echo $MF['TEXT_NOPAGE'] ?></option>
				<?php foreach($links AS $li) {
					$option_link = explode('|',$li);
					$disabled = $option_link[0] == $page_id ? ' disabled':'';
					echo "<option $disabled value=\"".$option_link[0]."\" ".($settings['successpage']==$option_link[0] ? $sel : '').">$option_link[1]</option>\n";
				} ?>
				</select>
		</td></tr>

		<tr><td><?php echo $MF['TEXT_NO_STORE'] ?>: </td><td><input type="hidden" name="no_store" value="0" /><input type="checkbox"  name="no_store" value="1" <?php echo $no_store_checked ?> /></td></tr>
		<tr><td><?php echo $MF['TEXT_AJAX'] ?>: </td><td><input type="hidden" name="use_ajax" value="0" /><input type="checkbox"  name="use_ajax" value="1" <?php echo $ajax_checked ?> /></td></tr>
		<tr><td><?php echo $MF['TEXT_RECAPTCHA'] ?>: </td><td><input type="hidden" name="use_recaptcha" value="0" /><input type="checkbox" id="rc" name="use_recaptcha" value="1" <?php echo $rc_checked ?> /></td></tr>
		<tr class="rc" <?php echo $rc_display ?>><td><?php echo $MF['TEXT_RCKEY'] ?>: </td><td><input class="mf-input wide" type="text" name="recaptcha_key" value="<?php echo $settings['recaptcha_key'] ?>" /> - <a href="https://www.google.com/recaptcha/admin" target="_blank">get Google reCaptcha keys</a></td></tr>
		<tr class="rc" <?php echo $rc_display ?>><td><?php echo $MF['TEXT_RCSECRET'] ?>: </td><td><input class="mf-input wide" type="text" name="recaptcha_secret" value="<?php echo $settings['recaptcha_secret'] ?>" /></td></tr>

		<tr>
			<td><input type="submit" value="<?php echo $MF['TEXT_SAVE'] ?>" style="width: 120px; margin-top: 5px;" /></td>
			<td colspan="2" align="right"><input type="button" value="<?php echo $MF['TEXT_CANCEL'] ?>" onclick="javascript: window.location = 'index.php';" style="width: 120px; margin-top: 5px;" /></td>
		</tr>
	</table>
</form>
<?php 
$sub = $mform->get_history($section_id,50);
?>
<table class='msgtable msgt<?php echo $section_id ?>' cellpadding="3" border="0" style="margin-top:25px;">
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
