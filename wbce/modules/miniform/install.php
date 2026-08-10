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


if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }
	
// Create tables
foreach ($database->importSql(__DIR__ . '/install_struct.sql', null, false) as $r) {
    if (!$r['ok']) {
        echo "<h2>DB error creating " . h($r['statement']) . ": " . h($r['msg']) . "</h2>";
    }
}


$path = WB_PATH.'/modules/miniform/';
if(file_exists($path.'new_frontend.css')) {
	if(!rename($path.'new_frontend.css',$path.'frontend.css')) {
		echo "<h2>Error renaming frontend.css. Please rename new_frontend.css manually to frontend.css</h2>";
	}
}


$files = scandir ( $path.'/defaults/');
foreach ( $files as $file ) {
	if ($file != "." && $file != "..") {
		if(!rename ( $path.'/defaults/'.$file, $path.'/templates/'.$file )) {
			echo "<h2>Error copying file: ".$file." to the templates folder. Please move the files from the defaults folder manually to the templates folder!</h2>";
		}
	}
}