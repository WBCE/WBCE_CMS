<?php

// Include config file
require('../../../config.php');
if(!defined('WB_PATH')) { exit("Cannot access this file directly"); }

require('../info.php');
$mod_dir = $module_directory;
$tablename = $mod_dir;
$mpath = WB_PATH.'/modules/'.$mod_dir.'/';
// include module_settings
require_once(WB_PATH.'/modules/'.$mod_dir.'/defaults/module_settings.default.php');
require_once(WB_PATH.'/modules/'.$mod_dir.'/module_settings.php');
require_once (WB_PATH.'/modules/'.$mod_dir.'/functions_small.php');

require_once(WB_PATH.'/framework/class.wb.php');
$wb = new wb;

// Load Language file
if(LANGUAGE_LOADED) {
	if(!file_exists(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php')) {
		require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/EN.php');
	} else {
		require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php');
	}
}




// Check if we should show the form or add a comment
if(isset($_POST['page_id']) AND is_numeric($_POST['page_id']) AND isset($_POST['section_id']) AND is_numeric($_POST['section_id']) AND isset($_POST['topic_id']) AND is_numeric($_POST['topic_id'])
	AND (( ENABLED_ASP AND isset($_POST['c0mment_'.date('W')]) AND $_POST['c0mment_'.date('W')] != '')
		OR  (!ENABLED_ASP AND isset($_POST['comment']) AND $_POST['comment'] != ''))
) {
	
	if(ENABLED_ASP) {
		$commentpost = $_POST['c0mment_'.date('W')];
	} else {
		$commentpost = $_POST['comment'];
	}
	$comment = $wb->add_slashes(trim(strip_tags($commentpost)));
	$thename = $wb->add_slashes(trim(strip_tags($_POST['thenome'])));
	$thesite = $wb->add_slashes(trim(strip_tags($_POST['thesote'])));
	$themail = $wb->add_slashes(trim(strip_tags($_POST['themoil'])));
	$page_id = (int) $_POST['page_id'];
	$section_id = (int) $_POST['section_id'];
	$topic_id = (int) $_POST['topic_id'];
	define('TOPIC_ID',$topic_id);

	// Check captcha
	$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE section_id = '$section_id'");
	if($query_settings->numRows() == 0) { 
		//exit(header('Location: '.WB_URL.'/modules/'.$mod_dir.'/nopage.php?err=6')); //exit(header("Location: ".WB_URL.PAGES_DIRECTORY.""));
		exit('<h1 class="comment_error">No valid section</h1>');
	} else {
		$settings_fetch = $query_settings->fetchRow();
		
		//various values
		$use_commenting_settings = 0;
		if ($settings_fetch['various_values'] != '') {
			$vv = explode(',',$settings_fetch['various_values']);		
			$use_commenting_settings = (int) $vv[3];
			$emailsettings = (int) $vv[4]; if ($emailsettings < 0) {$emailsettings = 2;} //Wie bisher: Pflichtfeld
		} else {
			die(); //nur zur Sicherheit
		}
		
					

		$query_topic = $database->query("SELECT link, commenting, posted_by,title  FROM ".TABLE_PREFIX."mod_".$tablename." WHERE topic_id = '$topic_id'");
		if($query_topic->numRows() != 1) { die('no topic!'); }
		$topicfetch = $query_topic->fetchRow();		
		$link = $topicfetch['link'];		
		$commenting = (int) $topicfetch['commenting'];
		$topicauthornr = $topicfetch['posted_by'];
		
	
		
		if( $commenting < -1) {$use_commenting_settings = 1;} //Defaultwert verenden
		//Wenn: angekreuzt: Individielle EInstellungen ignorieren, dann die Settings-Einstellungen verwenden.
		if ($use_commenting_settings == 1) { $commenting = (int) $settings_fetch['commenting'];}
	
		
		
		if ($commenting < 1) { 
			//exit(header('Location: '.WB_URL.'/modules/'.$mod_dir.'/nopage.php?err=7')); 
			exit('<h1 class="comment_error">Commenting is not allowed</h1>');
		}
		
		$topic_link = WB_URL.$topics_virtual_directory.$link.PAGE_EXTENSION;		
		$backend_link = WB_URL.'/modules/'.$mod_dir.'/modify_topic.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.$paramdelimiter.'topic_id='.$topic_id;
				
		
		if ($commenting < 2) { //Einstellung: Moderiert
			$active=0;					
		} else { //Einstellung: Höher als Moderiert (Verzögert freischalten, sofort)
			$active=1;			
		} 
		
		
		
		
		
		$t=time(); //Here use time();
		if(ENABLED_ASP && ( // Advanced Spam Protection
			($_SESSION['session_started']+ASP_SESSION_MIN_AGE > $t) OR // session too young
			(!isset($_SESSION['comes_from_view'])) OR // user doesn't come from view.php
			(!isset($_SESSION['comes_from_view_time']) OR $_SESSION['comes_from_view_time'] > $t-ASP_VIEW_MIN_AGE) OR // user is too fast
			(!isset($_SESSION['submitted_when']) OR !isset($_POST['submitted_when'])) OR // faked form
			($_SESSION['submitted_when'] != $_POST['submitted_when']) OR // faked form
			//($_SESSION['submitted_when'] > $t-ASP_INPUT_MIN_AGE && !isset($_SESSION['captcha_retry_topics'])) OR // user too fast
			//($_SESSION['submitted_when'] < $t-43200) OR // form older than 12h
			($_POST['email'] OR $_POST['url'] OR $_POST['homepage'] OR $_POST['comment']) // honeypot-fields
		)) {
		//exit ("fehler ENABLED_ASP");
		exit('<h1 class="comment_error">Blocked by ASP </h1>');
			//exit(header("Location: ".WB_URL.PAGES_DIRECTORY.""));
		}
		if(ENABLED_ASP) {
			if(isset($_SESSION['captcha_retry_topics'])) unset($_SESSION['captcha_retry_topics']);
		}
		if($settings_fetch['use_captcha']) {
			if(isset($_POST['captcha']) AND $_POST['captcha'] != '') {
				// Check for a mismatch
				if(!isset($_POST['captcha']) OR !isset($_SESSION['captcha']) OR $_POST['captcha'] != $_SESSION['captcha']) {
					$_SESSION['captcha_error'] = $MESSAGE['MOD_FORM']['INCORRECT_CAPTCHA'];
					$_SESSION['comment_nome'] = $thename;
					$_SESSION['comment_sote'] = $thesite;
					$_SESSION['comment_moil'] = $themail;
					$_SESSION['comment_body'] = $comment;
					//exit(header('Location: '.WB_URL."/modules/".$mod_dir."/comment.php?id=$topic_id&sid=$section_id&nok=1"));
					echo '<h1 class="comment_error">Capcha Error (1)</h1>';
					//echo $themail.$thesite.$thename;
					include('comment.inc.php');
					exit();
				}
			} else {
				$_SESSION['captcha_error'] = $MESSAGE['MOD_FORM']['INCORRECT_CAPTCHA'];
				$_SESSION['comment_nome'] = $thename;
				$_SESSION['comment_sote'] = $thesite;
				$_SESSION['comment_moil'] = $themail;
				$_SESSION['comment_body'] = $comment;
				echo '<h1 class="comment_error">Capcha Error (2)</h1>';			
				include('comment.inc.php');
				exit();
			}
		}
	}
	if(isset($_SESSION['captcha'])) { unset($_SESSION['captcha']); }
	if(ENABLED_ASP) {
		unset($_SESSION['comes_from_view']);
		unset($_SESSION['comes_from_view_time']);
		unset($_SESSION['submitted_when']);
	}
	
		
		
	//Brachialer Spamschutz
	include ('../inc/spamfilter.inc.php');
	
	$hpstart = substr ($thesite, 0, 7);
	if ($hpstart  != 'http://') { $thesite = 'http://'.$thesite; }
	if ($thesite == 'http://') {$thesite  = '';}
	
	$show_link = $settings_fetch['default_link'];
	
	
	
	
	
	
	$commented_when = topics_localtime();
	
	if($wb->is_authenticated() == true) {
		$commented_by = $wb->get_user_id();
	} else {
		$commented_by = '';
	}
	

	//Sending an Email:	
	$admin_email = '';
	$query_topicauthor = $database->query("SELECT email FROM ".TABLE_PREFIX."users WHERE user_id = '".$topicauthornr."'");
	if ($query_topicauthor->numRows() > 0) {
		$authorfetch = $query_topicauthor->fetchRow();
		$admin_email = $authorfetch['email'];
	}		
	
	
	//Der spamfilter koennte $active veraendert haben, deswegen $commentextra erst hier vergeben:
	$commentextra = '';
	if ($active==0) {$commentextra = rand ( 1000000 , 9999999 );}
	
	
	
	
	if ($spamlevel > 1) {
		exit(header("Location: ".WB_URL."/modules/".$mod_dir."/nopage.php")); //exit(header("Location: ".WB_URL.PAGES_DIRECTORY.""));
	}
	
	// Insert the comment into db
	
	$theq = "INSERT INTO ".TABLE_PREFIX."mod_".$tablename."_comments (topic_id,name,website,email,comment,commented_when,commented_by,active,show_link,commentextra) VALUES ('$topic_id','$thename','$thesite','$themail','$comment','$commented_when','$commented_by','$active','$show_link','$commentextra')";
	$theq = str_replace(array("[", "]"), array("&#91;", "&#93;"), $theq);
	$query = $database->query($theq);
	$last_insert = $database->get_one("SELECT LAST_INSERT_ID()");
	
	
	if ( $active==1) { topics_update_comments_count ($topic_id) ;}
	
	
	
	// Get page link
	//$query_page = $database->query("SELECT link FROM ".TABLE_PREFIX."mod_topics WHERE topic_id = '$topic_id'");
	//$page = $query_page->fetchRow();
	//header('Location: '.$wb->page_link($page['link']).'?id='.$topic_id);
	

	if (!$topics_comment_cookie) $topics_comment_cookie = 120;
	if ($commenting < 3) {	
		$gueltigkeit = time() + $topics_comment_cookie;
		setcookie("comment".$topic_id, $last_insert.','.time(), $gueltigkeit);
	}
	$gueltigkeit = time()+3456000;	//40 Tage
	setcookie("commentdetails", $last_insert, $gueltigkeit);
	
	$cid = $last_insert;
	
	
	//Mail:
	
	if ($admin_email != '') { 
		$mail_subject = "Comment: " . $topicfetch['title'];
	
		if($themail != '') {$email = $themail; } else {$email = 'noname@domain.com'; }	
		$headers = "Content-Type: text/html\n";
		$headers .= "Content-Transfer-Encoding: 8bit\n";
		$headers .= "From: " . $thename . "<" . $email . ">\n";
		$headers .= "Reply-To: " . $email . "\n";

		
		
	
		$mail_message = nl2br(strip_tags(stripslashes($commentpost)));	
		$mail_message .= '<br/><br/>
		<a href="'.$topic_link.'#tcid'.$cid.'"> | <a href="'.$backend_link.'#comments">Edit Comments</a>';
		
		if ($commentextra != '') {
			$mail_message .=  '| <a href="'.$topic_link.'?publ='.$commentextra.'#tcid'.$cid.'">Publish</a>';
		}
			
		$wb->mail(SERVER_EMAIL,$admin_email,$mail_subject,$mail_message);
		//echo $mail_message;
		//die('mail wurde versendet');
		
	} else {
		//die('mail konnte nicht versendet werden');
	
	}// End Mail
	
	
	
	include('commentdone.php');
	
	
	
} else {
	exit('<h1>Da hat was nicht gepasst!</h1>');
}

?>