<?php


// Must include code to stop this file being access directly
/* -------------------------------------------------------- */
if(defined('WB_PATH') == false) { die('Access denied. Cannot access this file directly');}
/* -------------------------------------------------------- */


require_once(WB_PATH.'/include/captcha/captcha.php');
?>


<h3><?php echo $TEXT['COMMENT']; ?></h3>
<form name="c_mment_form" id="c_mment_form" method="post">
	<input type="hidden" name="submitted_when" value="<?php $t=time(); echo $t; $_SESSION['submitted_when']=$t; ?>" />
	<input type="hidden" name="topic_id" value="<?php echo TOPIC_ID ?>" />
	<input type="hidden" name="section_id" value="<?php echo $section_id ?>" />
	<input type="hidden" name="page_id" value="<?php echo $page_id ?>" />

<?php if(ENABLED_ASP) { // add some honeypot-fields  // 
	?>
	
	<p class="nixhier">
	email address:
	<label for="email">Leave this field email blank:</label>
	<input id="email" name="email" size="60" value="" /><br />
	Homepage:
	<label for="homepage">Leave this field homepage blank:</label>
	<input id="homepage" name="homepage" size="60" value="" /><br />
	URL:
	<label for="url">Leave this field url blank:</label>
	<input id="url" name="url" size="60" value="" /><br />
	Comment:
	<label for="comment">Leave this field comment blank:</label>
	<input id="comment" name="comment" size="60" value="" /><br />
	</p>
	<?php 
}

if (!isset($themail)) {
	$themail = '';
	$thesite = '';
	$thename = '';
}


$vv = explode(',',$settings_fetch['various_values'].',-2,-2,-2,-2,-2,-2');
$emailsettings = (int) $vv[4]; if ($emailsettings < 0) {$emailsettings = 2;} //Wie bisher: Pflichtfeld
$default_link = $settings_fetch['default_link'];
	
/*Was ist das? BRauchen wir das noch?:
if (isset($_COOKIE['commentdetails']) AND is_numeric($_COOKIE['commentdetails'])) {
	$query_comments = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_comments WHERE comment_id = '".$_COOKIE['commentdetails']."'");
	if($query_comments->numRows() == 1) {
		$commentfetch = $query_comments->fetchRow();
		$thename = $commentfetch['name'];				
		$themail =  $commentfetch['email'];
		$thesite = $commentfetch['website'];
	} 
	
}
*/

?>
<p class="commentthenome"><?php echo $TEXT['NAME']; ?>:<br />
<input type="text" name="thenome" maxlength="255" value="<?php echo $thename; ?>" />
</p>

<?php if ($emailsettings > 0) {
	echo '<p class="commentthemoil">'.$TEXT['EMAIL'];
	if ($emailsettings > 1) { echo ' (required, not public)'; } else { echo ' (not public)'; } 
	echo ':<br />
	<input type="text" name="themoil" maxlength="255" value="'.$themail.'" />
	</p>';
} else {
	echo '<input type="hidden" name="themoil" maxlength="255" value="'.$themail.'" />';
}

?>
	
<?php if ($default_link > -1) {
	echo '<p class="commentthesote">'.$TEXT['WEBSITE'].':<br />
	<input type="text" name="thesote" maxlength="255" value="'.$thesite.'" />
	</p>';
} else {
	echo '<input type="hidden" name="thesote" maxlength="255" value="'.$thesite.'" />';
}	
?>	



<p class="commentc0mment"><?php echo $TEXT['COMMENT']; ?> :<br />	
<?php if(ENABLED_ASP) { ?>
	<textarea rows="10" cols="1" id="c0mment" name="c0mment_<?php echo date('W'); ?>"><?php if(isset($_SESSION['comment_body'])) { echo $_SESSION['comment_body']; unset($_SESSION['comment_body']); } ?></textarea>
<?php } else { ?>
	<textarea rows="10" cols="1" id="c0mment" name="comment"><?php if(isset($_SESSION['comment_body'])) { echo $_SESSION['comment_body']; unset($_SESSION['comment_body']); } ?></textarea>
<?php } ?>
</p>
<?php
if(isset($_SESSION['captcha_error'])) {
	echo '<font color="#FF0000">'.$_SESSION['captcha_error'].'</font><br />';
	$_SESSION['captcha_retry_topics'] = true;
}
// Captcha
if($settings_fetch['use_captcha']) {
?>
<table cellpadding="2" cellspacing="0" border="0">
<tr>
	<td><?php echo $TEXT['VERIFICATION']; ?>:</td>
	<td><?php call_captcha(); ?></td>
</tr></table>
<br />
<?php
if(isset($_SESSION['captcha_error'])) {
	unset($_SESSION['captcha_error']);
	?><script>document.comment.captcha.focus();</script>
<?php
}?>
<?php
}

if (LANGUAGE == 'DE') {$submitbuttontext = 'Kommentar hinzuf&uuml;gen';} else {$submitbuttontext = $TEXT['ADD'].' '.$TEXT['COMMENT'];}
?>
<div ><input type="button" name="submit" onclick="submitform(); return false;" class="submitbutton" value="<?php echo $submitbuttontext; ?>" /></div>
</form>	

	

