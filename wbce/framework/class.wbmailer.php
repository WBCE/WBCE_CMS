<?php

// $Id: class.wbmailer.php 1499 2011-08-12 11:21:25Z DarkViper $

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2009, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/
/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {
	require_once(dirname(__FILE__).'/globalExceptionHandler.php');
	throw new IllegalFileException();
}
/* -------------------------------------------------------- */
// Include PHPMailer class
require_once(WB_PATH."/include/phpmailer/class.phpmailer.php");

class wbmailer extends PHPMailer 
{
	// new websitebaker mailer class (subset of PHPMailer class)
	// setting default values 

	function wbmailer() {
		global $database;
		// set mailer defaults (PHP mail function)
		$db_wbmailer_routine = "phpmail";
		$db_wbmailer_smtp_host = "";
		$db_wbmailer_default_sendername = "WB Mailer";
		$db_server_email = SERVER_EMAIL;

		// get mailer settings from database
		// $database = new database();
		$query = "SELECT * FROM " .TABLE_PREFIX. "settings";
		$results = $database->query($query);
		while($setting = $results->fetchRow()) {
			if ($setting['name'] == "wbmailer_routine") { $db_wbmailer_routine = $setting['value']; }
			if ($setting['name'] == "wbmailer_smtp_host") { $db_wbmailer_smtp_host = $setting['value']; }
			if ($setting['name'] == "wbmailer_smtp_auth") { $db_wbmailer_smtp_auth = (bool)$setting['value']; }
			if ($setting['name'] == "wbmailer_smtp_username") { $db_wbmailer_smtp_username = $setting['value']; }
			if ($setting['name'] == "wbmailer_smtp_password") { $db_wbmailer_smtp_password = $setting['value']; }
			if ($setting['name'] == "wbmailer_default_sendername") { $db_wbmailer_default_sendername = $setting['value']; }
			if ($setting['name'] == "server_email") { $db_server_email = $setting['value']; }
		}

		// set method to send out emails
		if($db_wbmailer_routine == "smtp" AND strlen($db_wbmailer_smtp_host) > 5) {
			// use SMTP for all outgoing mails send by Website Baker
			$this->IsSMTP();                                            
			$this->Host = $db_wbmailer_smtp_host;
			// check if SMTP authentification is required
			if ($db_wbmailer_smtp_auth == "true" && strlen($db_wbmailer_smtp_username) > 1 && strlen($db_wbmailer_smtp_password) > 1) {
				// use SMTP authentification
				$this->SMTPAuth = true;     	  								// enable SMTP authentification
				$this->Username = $db_wbmailer_smtp_username;  	// set SMTP username
				$this->Password = $db_wbmailer_smtp_password;	  // set SMTP password
			}
		} else {
			// use PHP mail() function for outgoing mails send by Website Baker
			$this->IsMail();
		}

		// set language file for PHPMailer error messages
		if(defined("LANGUAGE")) {
			$this->SetLanguage(strtolower(LANGUAGE),"language");    // english default (also used if file is missing)
		}

		// set default charset
		if(defined('DEFAULT_CHARSET')) { 
			$this->CharSet = DEFAULT_CHARSET; 
		} else {
			$this->CharSet='utf-8';
		}

		// set default sender name
		if($this->FromName == 'Root User') {
			if(isset($_SESSION['DISPLAY_NAME'])) {
				$this->FromName = $_SESSION['DISPLAY_NAME'];            // FROM NAME: display name of user logged in
			} else {
				$this->FromName = $db_wbmailer_default_sendername;			// FROM NAME: set default name
			}
		}

		/* 
			some mail provider (lets say mail.com) reject mails send out by foreign mail 
			relays but using the providers domain in the from mail address (e.g. myname@mail.com)
		*/
		$this->From = $db_server_email;                           // FROM MAIL: (server mail)

		// set default mail formats
		$this->IsHTML(true);                                        
		$this->WordWrap = 80;                                       
		$this->Timeout = 30;
	}
}
