<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

// Stop direct file access
if(count(get_included_files()) ==1){header('Location: ../index.php');die();}



if (!function_exists('simplepagehead')) {
	function simplepagehead($endtag="/", $norobotstag=1, $notoolbartag=1, $favicon=1, $generator=1, $metaend=0) {



		// Define module vars
		// ******************
		
		// To add other modules extend this list by adding new lines, e.g.:
		// $module['module_name'] = array('table_name', 'id_name', 'title_field_name', 'description_field_name', 'keywords_field_name');
		$module['news'] = array('mod_news_posts', 'post_id', 'title', 'content_short', '');
		$module['bakery'] = array('mod_bakery_items', 'item_id', 'title', 'description', '');
		$module['topics'] = array('mod_topics', 'topic_id', 'title', 'description', 'keywords');
		$module['responsiveFG'] = array( "mod_responsiveFG_categories", "section_id", "cat_name", "description", "searchtext");
		#$module['module_name'] = array('table_name', 'id_name', 'title_field_name', 'description_field_name');



		// Register outside object
		global $database;
		global $wb;
		global $page_id;
		global $section_id;

		// Set defaults
		$the_title = ''; //$wb->page_title;
		$the_description = ''; //$wb->page_description; 
		$the_keywords = ''; //$wb->page_keywords;
		
		if(!isset($section_id) AND (isset($_GET['cat_id'])) ) {
			$section_id = $database->get_one("SELECT `section_id` FROM `".TABLE_PREFIX."sections` WHERE `page_id`=".$page_id." AND `module`='responsiveFG'");
		}

		// Look for the module name of the current section		
		if(isset($page_id) && isset($section_id)) {
			$sections_query = $database->query("SELECT module FROM ".TABLE_PREFIX."sections WHERE page_id = '$page_id' AND section_id = '$section_id'");
			$section = $sections_query->fetchRow();
			
			// Check if the module is added to the module list
			$module_name = $section['module'];
			if(array_key_exists($module_name, $module)) {

				// Prepare vars for the query string depending on the module
				$table_name = $module[$module_name][0];
				$id_name = $module[$module_name][1];
				$title_field_name = $module[$module_name][2];
				$description_field_name = $module[$module_name][3];
				$keywords_field_name = $module[$module_name][4];
				
				// Register outside object
				global $$id_name;
				$id_value = $$id_name;
	
				// Get the header data out of the DB
				$query = "SELECT $title_field_name, $description_field_name FROM ".TABLE_PREFIX."$table_name WHERE $id_name = '$id_value'";
				if ($keywords_field_name !='') {$query = "SELECT $title_field_name, $description_field_name, $keywords_field_name FROM ".TABLE_PREFIX."$table_name WHERE $id_name = '$id_value'";}
				$query_module = $database->query($query);
				$results_array = $query_module->fetchRow();
				$the_title = $results_array[$title_field_name]; 
				$the_description = strip_tags($results_array[$description_field_name]);
				if ($keywords_field_name !='') {$the_keywords = strip_tags($results_array[$keywords_field_name]);}
			}
		}

		if ($the_description == '') {
			$the_description = $wb->page_description;
		}
		else {
			$the_description = str_replace('"', '', $the_description); 
			if (strlen($the_description) > 160) {
				if(preg_match('/.{0,160}(?:[.!?:,])/su', $the_description, $match)) {   //thanks to thorn	
					$the_description = $match[0];
				}			
				if (strlen($the_description) > 160) {
					$pos = strpos($the_description, " ", 120);
					if ($pos > 0) {
						$the_description = substr($the_description, 0,  $pos);
					}					
				}
			}
		}
		
		if ($the_title=='') {$the_title = $wb->page_title; }
		//if (strlen($the_title) < 15) {$the_title = WEBSITE_TITLE. " - " .$the_title; }
		if (WEBSITE_TITLE != $the_title) {$the_title = WEBSITE_TITLE. " - " .$the_title; }
		

		if ($the_description == '') { $the_description = $wb->page_description; }
		if ($the_description == '') { $the_description = WEBSITE_DESCRIPTION; }
		
		if ($the_keywords == '') { $the_keywords = $wb->page_keywords; }
		if ($the_keywords == '') { $the_keywords = WEBSITE_KEYWORDS; }
		
		
		 if (OPF_AUTO_PLACEHOLDER) echo "<!--(PH) META HEAD+ -->"; 
        echo "\n";       
        echo '<meta http-equiv="Content-Type" content="text/html; charset='; if(defined('DEFAULT_CHARSET')) { echo DEFAULT_CHARSET; } else { echo 'utf-8'; } echo "\"$endtag>\n";
		
		$the_language = strtolower(LANGUAGE);
		echo "<meta name=\"language\" content=\"$the_language\"$endtag>\n";
		
		
		if (OPF_AUTO_PLACEHOLDER) echo "<!--(PH) TITLE+ -->"; 
        echo "<title>$the_title</title>"; 
        if (OPF_AUTO_PLACEHOLDER) echo "<!--(PH) TITLE- -->"; 
        echo "\n";
		
		if (OPF_AUTO_PLACEHOLDER) echo '<!--(PH) META DESC+ -->'; 
		echo '<meta name="description" content=""'."$endtag>";
		if (OPF_AUTO_PLACEHOLDER) echo "<!--(PH) META DESC- -->"; 
		echo "\n";
		if (OPF_AUTO_PLACEHOLDER) echo '<!--(PH) META KEY+ -->'; 
		echo '<meta name="keywords" content=""'. "$endtag>";
		if (OPF_AUTO_PLACEHOLDER) echo "<!--(PH) META KEY- -->"; 
		echo "\n";
        
		I::insertMetaTag(array (
		   "setname" => "description",
		   "name"    => "description",
		   "content" => "$the_description"
		));

		I::insertMetaTag(array (
		   "setname" => "keywords",
		   "name"    => "keywords",
		   "content" => "$the_keywords"
		));


		if ($favicon == 1) {              
            $tp = WB_PATH.'/templates/'.TEMPLATE;
            $iconInRoot = false;
            if (OPF_AUTO_PLACEHOLDER) echo "<!--(PH) FAVICON+ -->";
            if (file_exists($tp.'/favicon.ico')) {
				echo '<link rel="shortcut icon" href="'.TEMPLATE_DIR.'/favicon.ico" type="image/x-icon'."\"$endtag>\n";
			} else {
				if (file_exists(WB_PATH.'/favicon.ico')) {
					echo '<link rel="shortcut icon" href="'.WB_URL.'/favicon.ico'."\"$endtag>\n";
				}			
			}
            if (OPF_AUTO_PLACEHOLDER) echo "<!--(PH) FAVICON- -->";
            
            if (OPF_AUTO_PLACEHOLDER) echo "<!--(PH) APPLE TOUCH+ -->";
            if (file_exists($tp.'/apple-touch-icon.png')) { echo '<link rel="apple-touch-icon" href="'.TEMPLATE_DIR.'/apple-touch-icon.png'."\"$endtag>\n";  }
            if (file_exists($tp.'/apple-touch-icon-57x57.png')) {echo '<link rel="apple-touch-icon" sizes="57x57" href="'.TEMPLATE_DIR.'/apple-touch-icon-57x57.png'."\"$endtag>\n"; }
            if (file_exists($tp.'/apple-touch-icon-72x72.png')) { echo '<link rel="apple-touch-icon" sizes="72x72" href="'.TEMPLATE_DIR.'/apple-touch-icon-72x72.png'."\"$endtag>\n"; }
            if (file_exists($tp.'/apple-touch-icon-76x76.png')) { echo '<link rel="apple-touch-icon" sizes="76x76" href="'.TEMPLATE_DIR.'/apple-touch-icon-76x76.png'."\"$endtag>\n"; }
            if (file_exists($tp.'/apple-touch-icon-114x114.png')) { echo '<link rel="apple-touch-icon" sizes="114x114" href="'.TEMPLATE_DIR.'/apple-touch-icon-114x114.png'."\"$endtag>\n"; }
            if (file_exists($tp.'/apple-touch-icon-120x120.png')) { echo '<link rel="apple-touch-icon" sizes="120x120" href="'.TEMPLATE_DIR.'/apple-touch-icon-120x120.png'."\"$endtag>\n"; }
            if (file_exists($tp.'/apple-touch-icon-144x144.png')) { echo '<link rel="apple-touch-icon" sizes="144x144" href="'.TEMPLATE_DIR.'/apple-touch-icon-144x144.png'."\"$endtag>\n"; }
            if (file_exists($tp.'/apple-touch-icon-152x152.png')) { echo '<link rel="apple-touch-icon" sizes="152x152" href="'.TEMPLATE_DIR.'/apple-touch-icon-152x152.png'."\"$endtag>\n"; }
            if (OPF_AUTO_PLACEHOLDER) echo "<!--(PH) APPLE TOUCH- -->";            
        }
       
		
		if ($norobotstag == 1) {
			$indexstring = '';
			if ($page_id === 0) {$indexstring = '<meta name="robots" content="noindex,nofollow"'. "$endtag>\n";}
			//if (isset($_GET['id'])) {$indexstring = '<meta name="robots" content="noindex,nofollow"'. "$endtag>\n";}		 
			echo $indexstring; 
		}
		
		if ($generator == 1) {echo '<meta name="generator" content="WBCE CMS; https://wbce.org"'."$endtag>\n";}
		if ($notoolbartag == 1) {echo '<meta http-equiv="imagetoolbar" content="no"'."$endtag>\n"; }		
		
		if($metaend AND OPF_AUTO_PLACEHOLDER) echo "<!--(PH) META HEAD- -->\n";
	}
}

