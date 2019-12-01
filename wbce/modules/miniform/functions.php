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


if(defined('WB_PATH') == false) { die("Cannot access this file directly"); }

class mform {
	
	public $templates = array();
	public $attachements = array();
	public $isArray = false;
	public $fieldGetSeen = false;
	public $dataPosted = false;
	public $myPost = true;
	public $upload_whitelist = "jpg,jpeg,gif,png,zip,rar,7z,pdf,doc,docx,xls,xlsx,csv";
	public $error;
	public $current = '';
	public $next = '';

	function __construct($section_id = 0) {
		if(isset($_POST['miniform']) && $_POST['miniform'] != $section_id) $this->myPost = false;
		$this->dataPosted = (isset($_POST) && is_array($_POST) && count($_POST) > 0)? true:false;;
	}
	
	function getSelectTemplate($current = null) {
		$listarray = $this->getTemplates();
		$list = '<select class="templates mf-input" name="template">';
		foreach ($listarray as $key => $value) {
			$s = $value == $current ? ' selected="selected"' : '';
			$list .= '<option '.$s.' value="'.$value.'">'.$key.'</option>';
		}
		$list .= '</select>';
		return $list;
	}
	
	
	function getTemplates($pattern = '') {
		if(!$pattern) $pattern = dirname(__FILE__).DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'form_*.htt';
        foreach(glob($pattern) as $filename) {
			$name = substr( basename($filename,".htt") , 5);
			$title = ucwords (str_replace(array("_","-")," ", $name));
			$this->templates[$title] = substr( basename($filename,".htt") , 5);
			
		}
        return $this->templates;
    }

	function get_template($name) {
		global $wb;
		if(!file_exists(dirname(__FILE__).'/templates/'.$name.'.htt')) return '';
		$this->current = substr($name,5);
		$tmp = file_get_contents(dirname(__FILE__).'/templates/'.$name.'.htt');
		$var[] = "{REFERER}";			$value[] = @$_SESSION["REF"];
		$var[] = "{URL}";				$value[] = ''; // $this->page(PAGE_ID);
		$var[] = "{WB_URL}";			$value[] = WB_URL;
		$var[] = "{WEBSITE_TITLE}";		$value[] = WEBSITE_TITLE;
		$var[] = "{MEDIA_DIRECTORY}";	$value[] = MEDIA_DIRECTORY;
		$var[] = "{SERVER_EMAIL}";		$value[] = SERVER_EMAIL;
		$var[] = "{UPLOAD_LIMIT}";		$value[] = $this->get_upload_limit();
		$var[] = "{UPLOAD_WHITELIST}";	$value[] = $this->get_upload_whitelist($tmp);
		$tmp = str_replace($var,$value,$tmp);
		return $tmp;
	}
	
	function get_upload_limit() {
		$max_upload = (int)(ini_get('upload_max_filesize'));
		$max_post = (int)(ini_get('post_max_size'));
		$memory_limit = (int)(ini_get('memory_limit'));
		$upload_mb = min($max_upload, $max_post, $memory_limit);
		return $upload_mb;
	}
	
	function get_upload_whitelist($template) {
		if(preg_match('/{WHITELIST (.*)}/i',$template,$name)) {
			$this->upload_whitelist = strtolower($name[1]);
		}
		return $this->upload_whitelist;
	}
	function check_whitelist($filename) {
		$file_tmp=explode('.',$filename);
		$file_ext=strtolower(end($file_tmp));
		return(in_array($file_ext,explode(',',$this->upload_whitelist)));
	}
	function add_template($template, $var, $value) {
		$tmp = str_ireplace($var,$value,$template);
		return $tmp;
	}	
	
	function page($pid = 0) {
		global $database, $wb;
		$link = $database->get_one("SELECT link FROM ".TABLE_PREFIX."pages WHERE `page_id` = '".$pid."'");
		return $wb->page_link($link);
	}
	
	function safe_get_post($postfield) {
		global $MF;
		$this->isArray = false;
		$val = null;
		$getfield = substr($postfield,0,5)=='mf_r_' ? substr($postfield,5) : substr($postfield,3);
		if(isset($_FILES[$postfield]['name']) && $_FILES[$postfield]['name']) {
			if ($this->check_whitelist($_FILES[$postfield]['name'])) {
				if($_FILES[$postfield]['error'] == 0) {
					if($_FILES[$postfield]['size'] > 0 && file_exists($_FILES[$postfield]['tmp_name'])) {
						$this->attachements[$_FILES[$postfield]['name']] = $_FILES[$postfield]['tmp_name'];
						$val = $_FILES[$postfield]['name'];
					} else {
						$this->error = $_FILES[$postfield]['name'].' - '.$MF['INVALID'];
					}
				}
			} else {
				$this->error = $_FILES[$postfield]['name'].' - '.$MF['INVALID'];
			}
		}elseif(isset($_POST[$postfield])) {
			$val =  $_POST[$postfield];
			if(substr($postfield,0,3) == 'mf_') $_SESSION['form'][$postfield] = $val;
		} elseif(isset($_GET[$getfield])) {
			$val = urldecode($_GET[$getfield]);
			$this->fieldGetSeen = true;
			if(substr($postfield,0,3) == 'mf_') $_SESSION['form'][$postfield] = $val;
		} else {
			$val = isset($_SESSION['form'][$postfield]) ? $_SESSION['form'][$postfield] : null;
		}
		if(is_array($val)) {
			$val = join(" | ",$val);
			$this->isArray = true;
		}
		$val = htmlspecialchars($val);
		$val = preg_replace("/(\n)/","",$val);
		return $val?$val:'';
	}
	
	function stripslashes_deep($value) {
		$value = is_array($value) ? array_map(array($this,'stripslashes_deep'), $value) : stripslashes($value);
		return $value;
	}

	function captcha($section_id = 0) {	
		require_once(WB_PATH.'/include/captcha/captcha.php');
		ob_start();
		call_captcha("all","",$section_id);
		$captcha = ob_get_contents();
		ob_end_clean();
		return $captcha;
	}
	
	function reCaptcha($siteKey = null) {
		$rval = '';
		if($siteKey) {
			$rval = '<div class="g-recaptcha" data-sitekey="'.$siteKey.'"></div>';
			$rval .= '<script src="https://www.google.com/recaptcha/api.js"></script>';
		}
		return $rval;
	}
	
	function reCaptcha_check($secretKey = null) {
		if($secretKey) {
			if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
				$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['g-recaptcha-response']);
				$responseData = json_decode($verifyResponse);
				if($responseData->success == true) {
					return true;
				}
			}
		}
		return false;
	}
	
	// Insert or Update table with posted fields in array
	function update_record ( $table, $id_field, $data ) {
		global $database;
		$set = '';
		$val = '';
		if ($data[$id_field] <= 0) {
			foreach ( $data as $key => $value ) {
				$value = $this->escapeString($value);
				if ($key != $id_field) {
					$set .= $set ? ',`'.$key.'`':'`'.$key.'`';
					$val .= $val ? ",'$value'" : "'$value'";
				}
			}
			$query = "INSERT INTO ".TABLE_PREFIX.$table." ($set) VALUES ($val)";
			$database->query($query);
			return $database->get_one("SELECT LAST_INSERT_ID()");
		} else {
			$id = $data[$id_field];
			foreach ( $data as $key => $value ) {
				$value = $this->escapeString($value);
				if ($key != $id_field) {
					$set .= $set ? ',':'';
					$set .= "`$key`='$value'";
				}
			}
			$query = "UPDATE ".TABLE_PREFIX.$table." SET $set where `$id_field` = '$id'";
			$database->query($query);
			return $id;
		}
	}

	function get_record ( $table, $id_field, $id) {
		global $database;
		$result = array();
		$res = $database->query("SELECT * FROM ".TABLE_PREFIX.$table." WHERE `$id_field` = '$id'");
		if($res) {
			while ($row = $res->fetchRow(MYSQL_ASSOC)) {
				$result[] = $row;
			}
		}
		return $result;
	}

	function delete_record ( $id) {
		global $database;
		$result = array();
		$res = $database->query("DELETE FROM ".TABLE_PREFIX."mod_miniform_data WHERE `message_id` = '$id'");
		return;
	}

	function get_history ( $id, $max = 20) {
		global $database;
		$result = array();
		$res = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_miniform_data WHERE `section_id` = '$id' order by message_id desc limit 0,$max ");
		if($res) {
			while ($row = $res->fetchRow(MYSQL_ASSOC)) {
				$result[] = $row;
			}
		}
		return $result;
	}
	
	function count_messages($section_id) {
		global $database;
		$res = $database->query("SELECT message_id FROM ".TABLE_PREFIX."mod_miniform_data WHERE `section_id` = '$section_id'");
		return ($res) ? $res->numRows() : 0;
	}
	
	function build_pagelist($parent, $this_page) {
		global $database, $links;
		$iterated_parents = array(); // keep count of already iterated parents to prevent duplicates

		$table_pages = TABLE_PREFIX."pages";
		if ( $query = $database->query("SELECT link, menu_title, page_title, page_id, level 
			FROM ".$table_pages." 
			WHERE parent = ".$parent." 
			ORDER BY level, position ASC")) {
			while($res = $query->fetchRow()) {
				$links[$res['page_id']] = $res['page_id'].'|'.str_repeat("  -  ",$res['level']).$res['menu_title'].' ('.$res['page_title'].')';
				if (!in_array($res['page_id'], $iterated_parents)) {
					$this->build_pagelist($res['page_id'], $this_page);
					$iterated_parents[] = $res['page_id'];
				}
			}
		}
	}
 
	
	
	//HTML Email funtionality
	function mail_old_version ($to = NULL, $from = NULL, $subject = NULL, $html_message = NULL, $plain_message = NULL) {
		if (!$to || !$from || !$subject || !$html_message) return false;
		if (!$plain_message) $plain_message = strip_tags($html_message);
		$random_hash = 'boundary-'.md5(date('r', time()));
		$headers = "From: ".$from."\r\n";
		$headers .= "Reply-To: ".$from."\r\n";
		$headers .= "Content-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\"";
		$lf = "\n";
		$mailbody = '--PHP-mixed-'.$random_hash.$lf.
		'Content-Type: multipart/alternative; boundary="PHP-alt-'.$random_hash.'"'.$lf.$lf.
		'--PHP-alt-'.$random_hash.$lf.
		'Content-Type: text/plain; charset="iso-8859-1"'.$lf.
		'Content-Transfer-Encoding: 7bit'.$lf.$lf.
		$plain_message.$lf.$lf.
		'--PHP-alt-'.$random_hash.$lf.
		'Content-Type: text/html; charset="iso-8859-1"'.$lf.
		'Content-Transfer-Encoding: 7bit'.$lf.$lf.
		$html_message.$lf.$lf.
		'--PHP-alt-'.$random_hash.'--'.$lf;
		$mailbody .= '--PHP-mixed-'.$random_hash.'--'.$lf;
		return @mail( $to, $subject, $mailbody, $headers );
	}
	
		
	function mail($fromaddress, $toaddress, $subject, $message, $fromname='', $replyto = '') {
		if (!class_exists('wbmailer')){
			require_once(WB_PATH."/framework/class.wbmailer.php");
		}
		$toArray = explode(',',$toaddress);
	
		$myMail = new wbmailer();
		// set user defined from address
		if ($fromaddress!='') {
			if($fromname!='') $myMail->FromName = $fromname;  	// FROM-NAME
			$myMail->From = $fromaddress;                     	// FROM:
		}
		if ($replyto!='') {
			$myMail->AddReplyTo($replyto);              	  	// REPLY TO:
		}
		// define recepient and information to send out
		
		foreach ($toArray as $toAddr) {							// TO:
			$myMail->AddAddress($toAddr);
		}
		$myMail->Subject = $subject;                          	// SUBJECT
		$myMail->Body = $message; 		                     	// CONTENT (HTML)
		$textbody = strip_tags($message);  
		$textbody = str_replace("\t","",$textbody);  
		while (strpos($textbody,"\n\n\n") !== false) 
			$textbody = str_replace("\n\n\n","\n\n",$textbody);  
		while (strpos($textbody,"\r\n\r\n\r\n") !== false) 
			$textbody = str_replace("\r\n\r\n\r\n","\r\n\r\n",$textbody);  
		$myMail->AltBody = $textbody;			              	// CONTENT (TEXT)
		
		foreach($this->attachements as $filename => $file) {
			$myMail->AddAttachment($file, $filename);
		}
		
 		/*
		$myMail->SMTPAutoTLS = false; // tell phpmailer not to autouse TLS is not configured
		*/
		
		$myMail->SMTPOptions = array( // allow self_signed ssl.
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		); 
		// check if there are any send mail errors, otherwise say successful
		if (!$myMail->Send()) {
			$this->error = $myMail->ErrorInfo;
			return false;
		} else {
			return true;
		}
	}
	
	
	function remote_data($url = ''){
	 
		if (!function_exists('curl_init')){
			die('Sorry cURL is not installed!');
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, "Miniform remote_data()");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

	function load_history($section_id, $user_id = 0, $guid = '') {
		global $database;
		if(!$user_id && !$guid) return;
		if($user_id) $query = "SELECT `session_data` FROM ".TABLE_PREFIX."mod_miniform_data WHERE `section_id`='$section_id' AND `user_id`='$user_id' ORDER BY `message_id` DESC LIMIT 1";
		if($guid) $query = "SELECT `session_data` FROM ".TABLE_PREFIX."mod_miniform_data WHERE `section_id`='$section_id' AND `guid`='$guid' LIMIT 1";
		$res = $database->get_one($query);
		if($res) $_SESSION['form'] = $this->unserialize($res);
		return;
		
	}
	
	function serialize ($sObject) {
		return serialize($sObject);
	}
	
	function unserialize($sObject) {
		$__ret = preg_replace_callback( '/s:([0-9]+):\"(.*?)\";/',
			function ($matches) { return "s:".strlen($matches[2]).':"'.$matches[2].'";'; },
			$sObject
		);
		return unserialize($__ret);
	}

	function guid() {
		if (function_exists('com_create_guid') === true){
			return str_replace(array('{','}','-'),'',com_create_guid());
		}
		return sprintf('%04X%04X%04X%04X%04X%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
	}
	
	function escapeString($string) {
		global $database;
		if(is_object($database->DbHandle)) {
			$rval = $database->escapeString($string);
		} else {
			$rval = mysql_real_escape_string($string);
		}
		return $rval;
	}	
	
} //end class

function isAjaxRequest () {
	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest") return true;
	return false;
								   
}
function sendOutput($data) {
	if(isAjaxRequest()) {
		while (ob_get_level()) ob_end_clean();
		die($data);
	} else {
		echo $data;
	}
}
