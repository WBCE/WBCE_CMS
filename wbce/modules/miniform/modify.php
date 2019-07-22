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
 * @version         0.15.0
 * @lastmodified    April 30, 2019
 *
 */


$sLangPath = __DIR__ . '/languages';
require_once($sLangPath.'/EN.php');
if(LANGUAGE != 'EN'){
	if(file_exists($sLangPath.'/'.LANGUAGE.'.php')) {
		require_once($sLangPath.'/'.LANGUAGE.'.php');
	}
}
require_once  __DIR__ . '/functions.php';

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
	$mform->build_pagelist(0, $page_id);
}

$sel = ' selected';

$query = "SELECT * FROM ".TABLE_PREFIX."mod_miniform WHERE section_id = '$section_id'";
$get_settings = $database->query($query);
$settings = $get_settings->fetchRow();
if(!$settings['email'])           $settings['email'] = SERVER_EMAIL;
if(!$settings['emailfrom'])       $settings['emailfrom'] = SERVER_EMAIL;
if(!$settings['subject'])         $settings['subject'] = $MF['SUBJECT'];
if(!$settings['confirm_subject']) $settings['confirm_subject'] = $MF['SUBJECT'];
$remote = $settings['remote_id'];

$manage_url = WB_URL.'/modules/miniform/modify_template.php?page_id='.$page_id.'&section_id='.$admin->getIDKEY($section_id).'&mt=1&name=';

$cu_checked       = $settings['confirm_user'] ? 'checked': '';
$cs_display       = $settings['confirm_user'] ? '' : 'style="display:none;"';
$no_store_checked = $settings['no_store'] ? 'checked': '';
$ajax_checked     = $settings['use_ajax'] ? 'checked': '';
$rc_checked       = $settings['use_recaptcha'] ? 'checked': '';
$rc_display       = $settings['use_recaptcha'] ? '' : 'style="display:none;"';

$number_load = 7; // how many messages should be loaded?
?>
<script>
$(function() {
	$(".msgt<?=$section_id ?> td.line").click(function(e){
		e.preventDefault();
		$(this).children(".msg").slideToggle();
		console.log("click");
    });
	
	$(".doremote").click(function(){
		$(".mf_editor").toggle();
		$(".mf_remote").toggle();
	});
	
    $("select.templates").on("change", function() {
        var link = $(this).parent().find("a.manage");
        link.attr("href", "<?=$manage_url ?>" + $(this).val());
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

    <form action="<?=WB_URL ?>/modules/miniform/save.php" method="post"  >
	<input type="hidden" name="page_id" value="<?=$page_id ?>" />
	<input type="hidden" name="section_id" value="<?=$section_id ?>" />
	<table class="settable" id="mfsettings-<?=$section_id ?>" cellpadding="3" cellspacing="3" border="0">
		<tr>
			<td colspan="3"><h2><?=$MF['MINIFORM'] ?> - <?=$MF['SETTINGS'] ?></h2></td>
		</tr>
		<tr>
			<td class="small"><?=$MF['TEXT_FORM'] ?>: </td>
			<td><?=$mform->getSelectTemplate($settings['template']) ?>  
				<a class="manage mf-button" href="<?=$manage_url.$settings['template']?>"><?=$MF['MANAGE'] ?></a>
			</td>
		</tr>
		<tr>
			<td><?=$MF['TEXT_EMAILFROM'] ?>: </td>
			<td><input class="mf-input wide" size="50" type="text" name="emailfrom" value="<?=$settings['emailfrom'] ?>" /></td>
		</tr>
		<tr>
			<td><?=$MF['TEXT_EMAIL'] ?>: </td>
			<td><input class="mf-input wide" size="50" type="text" name="email" value="<?=$settings['email'] ?>" /></td>
		</tr>
		<tr>
			<td><?=$MF['TEXT_SUBJECT'] ?>: </td>
			<td><input class="mf-input wide" size="50" type="text" name="subject" value="<?=$settings['subject'] ?>" /></td>
		</tr>
		<tr>
			<td><?=$MF['TEXT_CONFIRM_USER'] ?>: </td>
			<td>
				<input type="hidden" name="confirm_user" value="0" />
				<input type="checkbox" id="cs" name="confirm_user" value="1" <?=$cu_checked ?> />
			</td>
		</tr>
		<tr class="cs" <?=$cs_display ?>>
			<td><?=$MF['TEXT_CONFIRM_SUBJ'] ?>: </td>
			<td>
				<input class="mf-input wide" size="50" type="text" name="confirm_subject" value="<?=$settings['confirm_subject'] ?>" />
			</td>
		</tr>
		<tr>
			<td><?=$MF['TEXT_SUCCESS'] ?>: </td>
			<td>		
				<select  class="mf-input wide" name="successpage" style="font-family:'Courier New',Courier,monospace;font-size:12px;" />
				<option value="0"<?=$settings['successpage']=='0' ? $sel : '' ?>><?=$MF['TEXT_NOPAGE'] ?></option>
				<?php foreach($links AS $li) {
					$option_link = explode('|',$li);
					$disabled = $option_link[0] == $page_id ? ' disabled':'';
					echo "<option $disabled value=\"".$option_link[0]."\" ".($settings['successpage']==$option_link[0] ? $sel : '').">$option_link[1]</option>\n";
				} ?>
				</select>
			</td>
		</tr>

		<tr>
			<td><?=$MF['TEXT_NO_STORE'] ?>: </td>
			<td>
				<input type="hidden" name="no_store" value="0" />
				<input type="checkbox"  name="no_store" value="1" <?=$no_store_checked ?> />
			</td>
		</tr>
		<tr>
			<td><?=$MF['TEXT_AJAX'] ?>: </td>
			<td>
				<input type="hidden" name="use_ajax" value="0" />
				<input type="checkbox"  name="use_ajax" value="1" <?=$ajax_checked ?> />
			</td>
		</tr>
		<tr>
			<td><?=$MF['TEXT_RECAPTCHA'] ?>: </td>
			<td>
				<input type="hidden" name="use_recaptcha" value="0" />
				<input type="checkbox" id="rc" name="use_recaptcha" value="1" <?=$rc_checked ?> />
			</td>
		</tr>
		<tr class="rc" <?=$rc_display ?>>
			<td><?=$MF['TEXT_RCKEY'] ?>: </td>
			<td>
				<input class="mf-input wide" type="text" name="recaptcha_key" value="<?=$settings['recaptcha_key'] ?>" /> - <a href="https://www.google.com/recaptcha/admin" target="_blank">get Google reCaptcha keys</a>
			</td>
		</tr>
		<tr class="rc" <?=$rc_display ?>>
			<td><?=$MF['TEXT_RCSECRET'] ?>: </td>
			<td><input class="mf-input wide" type="text" name="recaptcha_secret" value="<?=$settings['recaptcha_secret'] ?>" /></td>
		</tr>
		<tr>
			<td><input type="submit" value="<?=$MF['TEXT_SAVE'] ?>" style="width: 120px; margin-top: 5px;" /></td>
			<td colspan="2" align="right">
				<input type="button" value="<?=$MF['TEXT_CANCEL'] ?>" onclick="javascript: window.location = 'index.php';" style="width: 120px; margin-top: 5px;" />
			</td>
		</tr>
	</table>
    </form>
	
	<?php 
	$show_messages = false;
	// write/read SESSION to determine whether or not the Submission list should be shown
	if(isset($_GET['show_messages']) && in_array($_GET['show_messages'], array(1,0))){
		// provide it in a way that multiple miniform sections can store their unique toggle state
		if($_GET['section_id'] == $section_id){	
			$_SESSION['show_mf_messages'.$section_id] = $_GET['show_messages'];
		}
	}
	if(isset($_SESSION['show_mf_messages'.$section_id])){
		$show_messages = $_SESSION['show_mf_messages'.$section_id];
	}
	$TEXT['TOGGLE_VIEW'] = $TEXT['SHOW'];
	$toggle = 1;
	if($show_messages == 1){
		$TEXT['TOGGLE_VIEW'] = $TEXT['HIDE'];	
		$toggle = 0;			
	}			
	$sLink = ADMIN_URL."/pages/modify.php?page_id=%d&section_id=%d&show_messages=%d#mf_msgs_%s";
	$sLink = sprintf($sLink, $page_id, $section_id, $toggle, $section_id);
		
	$msg_count = $mform->count_messages($section_id);
	$count_txt = ($msg_count) > 0 ? $msg_count : '<i>'.strtolower($TEXT['NONE_FOUND']).'</i>';
	?>
	<table id="mf_msgs_<?=$section_id ?>" class="mf_messages" cellpadding="2" cellspacing="0" border="0" width="100%">
		<thead>
			<tr>
				<th colspan="2"><?=$MF['RECEIVED'] ?> (<?=$count_txt?>)</th>
				<th colspan="1">
					<?php if($msg_count > 0) { ?>
						<a style="float:right; font-size: 14px;" class="mf-button" href="<?=$sLink?>"><?=$TEXT['TOGGLE_VIEW']?></a>
					<?php }	?>
				</th>
			</tr>
		</thead>
		<tbody>

	<?php if($show_messages){ 		
		$delete_url = ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&section_id='.$section_id.'&delete=';
		include __DIR__ .'/messages_loop.php';
	} ?>
	</tbody>
</table>
	
<br>