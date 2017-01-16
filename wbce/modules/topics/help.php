<?php

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2007, Ryan Djurovich

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
 
// STEP 1:	Initialize
require('../../config.php');


require('permissioncheck.php');

// Load correct help fil
if(LANGUAGE_LOADED) {
    $help = WB_PATH.'/modules/'.$mod_dir.'/languages/help-EN.php';
    if(file_exists(WB_PATH.'/modules/'.$mod_dir.'/languages/help-'.LANGUAGE.'.php')) {
        $help = WB_PATH.'/modules/'.$mod_dir.'/languages/help-'.LANGUAGE.'.php';
    }
}

// STEP 2:	Get actual settings from database
//$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_topics_settings WHERE section_id = '$section_id'");
//$settings = $query_settings->fetchRow();

// STEP 3:	Display the help page.
?>

<table cellpadding="2" cellspacing="0" border="0" align="center" width="100%">
	<tr>
		<td>
		<?php include($help); // Load help file ?>
			
		
			
		</td>
	</tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td>
			<input type="button" value="<?php echo $TEXT['BACK']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" style="width: 100px; margin-top: 5px;" />
		</td>
	</tr>	
</table>