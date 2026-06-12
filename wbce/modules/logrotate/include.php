<?php
/*
*	@version	0.1.0
*	@author		Ruud Eisinga - Dev4me.com
*	@date		2020-12-09
*
*/


if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

if(isset($_SESSION['checkedlogs'])) return; 						// Been here, no checks needed anymore
$_SESSION['checkedlogs'] = true;									// Set for once per session

require __DIR__ . '/settings.php';
if($logname = ini_get ('error_log')) { 								// Find logfile name
	if(stripos($logname,WB_PATH) !== false) {						// Is logfile in WB/WBCE path ?
		if(file_exists($logname)) {									// Does it exist?
			if((filesize($logname)/1024/1024) > MAXLOGSIZE) {		// Is it larger then configured in settings
				$newname = date("Ymd_Hi").'_php_error.log'; 		// New name for this logfile
				rename ($logname , dirname($logname).'/'.$newname); // rename the logfile
				if(defined('WARNINGMAIL')) {						// Email address defined?
					$headers = "From: ".WARNINGMAIL."\r\n";			// Send email
					@mail( WARNINGMAIL, "Renamed errorlog on ".WB_URL, "Renamed errorlog to {$newname} for size > ".MAXLOGSIZE."Mb", $headers );
				}
			}
		}
	}
}
