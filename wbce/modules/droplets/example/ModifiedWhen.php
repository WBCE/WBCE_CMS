//:Displays the last modification time of the current page
//:Use [[ModifiedWhen]]
global $database, $wb;if (PAGE_ID>0) {	$query=$database->query("SELECT modified_when FROM ".TABLE_PREFIX."pages where page_id=".PAGE_ID);	$mod_details=$query->fetchRow();	return "This page was last modified on ".date("d/m/Y",$mod_details[0]). " at ".date("H:i",$mod_details[0]).".";}