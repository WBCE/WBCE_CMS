<?php


// Make sure page cannot be accessed directly
require('../info.php');
$mod_dir = $module_directory;
$tablename = $mod_dir;

if(!defined('WB_URL')) { 
	header("Location: ".WB_URL."/modules/".$mod_dir."/nopage.php");
	exit(0);
}
$thedelimiter = "&amp;";

require_once(WB_PATH.'/include/captcha/captcha.php');

// Get comments page template details from db
$query_settings = $database->query("SELECT use_captcha,commenting, various_values, default_link FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE section_id = '".SECTION_ID."'");
if($query_settings->numRows() == 0) {
	header("Location: ".WB_URL.'/modules/'.$mod_dir.'/nopage.php');
	exit(0);
} else {
// Load Language file
	if(LANGUAGE_LOADED) {
		if(!file_exists(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php')) {
			require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/EN.php');
		} else {
			require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php');
		}
	}
	$settings_fetch = $query_settings->fetchRow();	
	$vv = explode(',',$settings_fetch['various_values'].',-2,-2,-2,-2,-2,-2');
	$emailsettings = (int) $vv[4]; if ($emailsettings < 0) {$emailsettings = 2;} //Wie bisher: Pflichtfeld
	
	$default_link = $settings_fetch['default_link'];
	
	?>
   
	
   
    
    
    <table id="wraptable"><tr><td>
	<div class="topicsc_the_f">
	<h3><?php echo $TEXT['COMMENT']; ?></h3>
	<form name="comment" action="<?php echo WB_URL.'/modules/'.$mod_dir.'/comments_iframe/submit_comment.php?page_id='.PAGE_ID.$thedelimiter.'section_id='.SECTION_ID.$thedelimiter.'topic_id='.TOPIC_ID; ?>" method="post" onsubmit="validateForm(); return document.returnValue">
	
	<?php if(ENABLED_ASP) { // add some honeypot-fields  // 
	?>
	<input type="hidden" name="submitted_when" value="<?php $t=time(); echo $t; $_SESSION['submitted_when']=$t; ?>" />
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
	<?php }
	
	
	$theemail = '';
	$thesite = '';
	$thename = '';
	if (isset($_COOKIE['commentdetails']) AND is_numeric($_COOKIE['commentdetails'])) {
		$query_comments = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_comments WHERE comment_id = '".$_COOKIE['commentdetails']."'");
		if($query_comments->numRows() == 1) {
			$commentfetch = $query_comments->fetchRow();
			$thename = $commentfetch['name'];				
			$theemail =  $commentfetch['email'];
			$thesite = $commentfetch['website'];
		} 
		
	}
	
	
	
	
	
	?>
	<p class="commentthenome"><?php echo $TEXT['NAME']; ?>:<br />
	<input type="text" name="thenome" maxlength="255" value="<?php echo $thename; ?>" />
	</p>
	
	<?php if ($emailsettings > 0) {
	echo '<p class="commentthemoil">'.$TEXT['EMAIL'];
	if ($emailsettings > 1) { echo ' (required, not public)'; } else { echo ' (not public)'; } 
	echo ':<br />
	<input type="text" name="themoil" maxlength="255" value="'.$theemail.'" />
	</p>';
	} else {
		echo '<input type="hidden" name="themoil" maxlength="255" value="'.$theemail.'" />';
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
	<!--p class="commentthesote"><?php echo $TEXT['WEBSITE']; ?>:<br />
	<input type="text" name="thesote" maxlength="255" value="<?php echo  $thesite; ?>" />
	</p-->
	
	
	<p class="commentc0mment"><?php echo $TEXT['COMMENT']; ?> :<br />	
	<?php if(ENABLED_ASP) { ?>
		<textarea onchange="doresize();" rows="10" cols="1" id="c0mment" name="c0mment_<?php echo date('W'); ?>"><?php if(isset($_SESSION['comment_body'])) { echo $_SESSION['comment_body']; unset($_SESSION['comment_body']); } ?></textarea>
	<?php } else { ?>
		<textarea onchange="doresize();" rows="10" cols="1" id="c0mment" name="comment"><?php if(isset($_SESSION['comment_body'])) { echo $_SESSION['comment_body']; unset($_SESSION['comment_body']); } ?></textarea>
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
	<div ><input type="submit" name="submit" class="submitbutton" value="<?php echo $submitbuttontext; ?>" /></div>
	</form></div>	
	<?php
}

?>
</td></tr></table>
 <script language="JavaScript" type="text/JavaScript">
<!--
	h = document.getElementById('wraptable').offsetHeight;
	if (h > 300 && h < 800) {parent.resizeframe(h); }
//-->
 </script>	
