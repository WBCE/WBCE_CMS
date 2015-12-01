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

 /*
todo: file uploads

- upload one or more files 
- save the files to a temp location with random name
- keep original name and temp name as sessions
- when sending mail, add temp file and set name to original name
- after sending, clean temp files 
- clean old files (orphans that did not send)
  
 
 */
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }
global $MF;
if(!file_exists(WB_PATH.'/modules/miniform/languages/'.LANGUAGE.'.php')) {
	require(WB_PATH.'/modules/miniform/languages/EN.php');
} else {
	require(WB_PATH.'/modules/miniform/languages/'.LANGUAGE.'.php');
}

require_once (dirname(__FILE__).'/functions.php');

mb_internal_encoding(DEFAULT_CHARSET);

$mf = new mform($section_id);

$get_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_miniform WHERE section_id = '$section_id'");
$settings = $get_settings->fetchRow();
$form = $settings['template'];
$email = $settings['email'];
$subject = $settings['subject'];
$emailmessage = $mf->get_template('email');
$emailmessage = $mf->add_template($emailmessage, "{SUBJECT}", $subject);
$email_field_val = $mf->get_template('email_field_value');
$email_field_data = '';
$replyto = '';

$successpage = $settings['successpage'];

$prevdata = '';
$dbdata = '';
if(isset($_POST['__next'])) {
	$next_template = $mf->get_template('form_'.$_POST['__next']);
	$mf->next = $_POST['__next'];
}	
if(isset($_POST['__previous'])) {
	$template = $mf->get_template('form_'.$_POST['__previous']);
	$next_template = $mf->get_template('form_'.$_POST['__previous']);
	$mf->next = $_POST['__previous'];
} elseif(isset($_POST['__current'])) {
	$template = $mf->get_template('form_'.$_POST['__current']);
} else {	
	$template = $mf->get_template('form_'.$form);
}
$message = "";
$message_class = "hidden";
$form_class = "";
$use_captcha = ( strpos($template,"{CAPTCHA}")==false ) ? false : true;
$use_asp = ( strpos($template,"{ASPFIELDS}")==false ) ? false : true;
$captcha_class = $use_captcha ? "":"hidden";
$captcha = "";
$asp = "";
$all_required = true;
$next_required = true;
$aspdetect = false;

if ($use_captcha && $mf->dataPosted && $mf->myPost) {

	if(isset($_POST['captcha']) AND $_POST['captcha'] != ''){
		$ccheck = time(); $ccheck1 = time();
		if(isset($_SESSION['captcha'.$section_id])) $ccheck1 = $_SESSION['captcha'.$section_id];
		if(isset($_SESSION['captcha'])) $ccheck = $_SESSION['captcha'];
		if($_POST['captcha'] != $ccheck && $_POST['captcha'] != $ccheck1) {
			$var[] = "{CAPTCHA_ERROR}"; $value[] = " missing";
			$all_required = false;
		}
	} else {
		$var[] = "{CAPTCHA_ERROR}"; $value[] = " missing";
		$all_required = false;
	}
}			


if($mf->myPost) {

	//Find all posted data
	foreach ($_POST as $key => $rawpost) { 
		$fields[$key] = '!';
	}
	//Find all fields in next template
	if(isset($next_template) && $next_template) {
		preg_match_all('/<input [^>]*>|<select [^>]*>|<textarea [^>]*>/',$next_template,$matches);
		foreach($matches[0] as $match){
			if(preg_match('/name="([^"]*)"/i',$match,$name)) {
				$name = str_replace("[]","",$name[1]);
				$fields[$name] = '-';
			}
		}
	}		
	//Find all fields in template
	preg_match_all('/<input [^>]*>|<select [^>]*>|<textarea [^>]*>/',$template,$matches);
	foreach($matches[0] as $match){
		if(preg_match('/name="([^"]*)"/i',$match,$name)) {
			$name = str_replace("[]","",$name[1]);
			$fields[$name] = $name;
		}
	}
	//Read all fields
	foreach($fields as $key => $type) {
		$post = $mf->safe_get_post($key);
		//if($type!='-' && substr($key,0,2)!='__' && $post) $prevdata .= '<input type="hidden" name="'.$key.'" value="'.$post.'" />'."\n\t";
		if($type!='-' && substr($key,0,2)!='__' && $post) $prevdata .= '<input type="hidden" name="'.$key.'" value="'.$post.'" />'."\n\t";
		elseif($type!='-' && substr($key,0,3)=='mf_' ) $prevdata .= '<input type="hidden" name="'.$key.'" value="'.$post.'" />'."\n\t";
		if($mf->error) {
			$message .= $mf->error.'<br/>';
			$message_class = "error";
			$all_required = false;
			$mf->error = '';
		}
		$required = false;
		$emailmessage = $mf->add_template($emailmessage, '{'.mb_strtoupper($key).'}', $post);
		if(substr($key,0,3)=="mf_") {
			$key =  substr($key,3);
			if (substr($key,0,2)=="r_") {
				$required = true;
				$key =  substr($key,2);
			}
			$label_post = str_replace(" ","_",$post);
			$var[] = "{".mb_strtoupper($key)."}"; $value[] = $post;
			$var[] = "{".mb_strtoupper($key.'_'.$label_post)."}"; $value[] = " checked='checked' ";
			$var[] = "{".mb_strtoupper($key.'_CHECKED_'.$label_post)."}"; $value[] = " checked='checked' ";
			$var[] = "{".mb_strtoupper($key.'_SELECTED_'.$label_post)."}"; $value[] = " selected='selected' ";
			if($mf->isArray || strpos($post," | ")!==false) {
				$tmppost = explode(' | ',$post);
				foreach($tmppost as $tmpdata) {
					$label_post = str_replace(" ","_",$tmpdata);
					$var[] = "{".mb_strtoupper($key.'_'.$label_post)."}"; $value[] = " checked='checked' ";
					$var[] = "{".mb_strtoupper($key.'_CHECKED_'.$label_post)."}"; $value[] = " checked='checked' ";
					$var[] = "{".mb_strtoupper($key.'_SELECTED_'.$label_post)."}"; $value[] = " selected='selected' ";
				}
			}
			if($required && $mf->dataPosted && trim($post) == '') {
				if( $type != '-' && $type != '!') {
					$var[] = "{".mb_strtoupper($key)."_ERROR}"; $value[] = " missing";
					$all_required = false;
				} else {
					$next_required = false;
				}
			}			
			//try guessing email adresses
			if(in_array($key,array('email','e-mail','mail','email_address','e-mail_address'))) $replyto = $post;
			
			$key = ucwords($key);
			$key = str_replace("_"," ",$key);
			$post = nl2br($post);
			$email_field_data .= $mf->add_template($email_field_val, array('{FIELD}','{VALUE}'), array($key,$post));
		} elseif(substr($key,0,3)=="my_" && $post !== '') {
			$aspdetect = true;
		}
	}

	// If parameters were given through _GET, reload the page without parameters
	if($mf->fieldGetSeen) {
		$page_link = $mf->page(PAGE_ID);
		if(headers_sent()) {
			echo '<script type="text/javascript">window.location = "'.$page_link.'"</script>';
		} else {
			die(header('Location: '.$page_link , TRUE , 301));
		}
	}

	// Check form is  filled completely
	if(!$aspdetect) {
		if ($all_required && $mf->dataPosted) {
			if(!isset($next_template) || !$next_template) {
				// store message in database
				$data['message_id'] = 0;
				$data['section_id'] = $section_id;
				$emailmessage = $mf->add_template($emailmessage, '{MAILMESSAGE}', $email_field_data);
				$data['data'] = addslashes($emailmessage); 
				$data['submitted_when'] = time();
				$mf->update_record('mod_miniform_data', 'message_id', $data );
				// send message by mail
				if($mf->mail ($email, $subject, $emailmessage, WBMAILER_DEFAULT_SENDERNAME, $replyto)) {
					unset($_SESSION['form']);
					if($successpage) {
						$page_link = $mf->page($successpage);
						if(headers_sent()) {
							echo '<script type="text/javascript">window.location = "'.$page_link.'"</script>';
						} else {
							die(header('Location: '.$page_link , TRUE , 301));
						}
					}
					$message .= $MF['THANKYOU'];
					$message_class = "ok";
					$form_class = "hidden";
				}
			} else {
				$template = $next_template;
				$prevdata .= '<input type="hidden" name="__current" value="'.$mf->next.'" />'."\n\t";
			}
		} elseif ($mf->dataPosted)  {
				$message .= $MF['NOTALL'];
				$message_class = "error";
				$prevdata .= '<input type="hidden" name="__current" value="'.$mf->current.'" />'."\n\t";
		}
	}
}
if ($use_asp) {
	$asp = 	'<div style="top: -2000px; position: absolute; height:0px; overflow: hidden">'."\n\t\t".
			'<label for="ucomp">CompanyName</label><input autocomplete="off" type="text" id="ucomp" name="my_company" /><br/>'."\n\t\t".
			'<label for="uname">Username</label><input autocomplete="off" type="text" id="uname" name="my_name" /><br/>'."\n\t\t".
			'<label for="umail">Email</label><input autocomplete="off" type="text" id="umail" name="my_email" /><br/>'."\n\t\t".
			'</div>';
}
if ($use_captcha) {
	$captcha = $mf->captcha($section_id);
}
$var[] = "{EMAILMESSAGE}"; $value[] = $mf->add_template($emailmessage, '{MAILMESSAGE}', $email_field_data);
$var[] = "{PREVIOUS}"; $value[] = $prevdata;
$var[] = "{ASPFIELDS}";	$value[] = $asp;
$var[] = "{CAPTCHA}";	$value[] = $captcha;
$var[] = "{STATUSMESSAGE}";	$value[] = $message;
$var[] = "{CAPTCHA_CLASS}";	$value[] = $captcha_class;
$var[] = "{MESSAGE_CLASS}";	$value[] = $message_class;
$var[] = "{FORM_CLASS}";	$value[] = $form_class;
$var[] = "{PAGE_ID}";	$value[] = $page_id;
$var[] = "{SECTION_ID}";$value[] = $section_id;
$var[] = "{DATE}";$value[] = date( DATE_FORMAT , time()+TIMEZONE );
$var[] = "{TIME}";$value[] = date( TIME_FORMAT , time()+TIMEZONE );
$template = $mf->add_template($template, $var, $value);
//clean unused fields in the template
$template = preg_replace('#\{[A-Za-z_][A-Za-z_0-9.,-\/\\ ]*?\}#s', '', $template); 
unset($var);
unset($value);
echo $template;
//echo nl2br(htmlspecialchars($prevdata));