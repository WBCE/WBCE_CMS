<?php

// $Id: module.functions.php 1499 2011-08-12 11:21:25Z DarkViper $

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

/**
	This file contains routines to edit the optional module files: frontend.css and backend.css
	Mechanism was introduced with WB 2.7 to provide a global solution for all modules
	To use this function, include this file from your module (e.g. from modify.php)
	Then simply call the function edit_css('your_module_directory') - that�s it
	NOTE: Some functions were added for module developers to make the creation of own module easier
*/

/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {
	require_once(dirname(__FILE__).'/globalExceptionHandler.php');
	throw new IllegalFileException();
}
/* -------------------------------------------------------- */

/*
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
 FUNCTIONS REQUIRED TO EDIT THE OPTIONAL MODULE CSS FILES
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
*/ 

// this function checks the validity of the specified module directory
if(!function_exists('check_module_dir')) {
	function check_module_dir($mod_dir) {
		// check if module directory is formal correct (only characters: "a-z,0-9,_,-")
		if(!preg_match('/^[a-z0-9_-]+$/iD', $mod_dir)) return '';
		// check if the module folder contains the required info.php file
		return (file_exists(WB_PATH .'/modules/' .$mod_dir .'/info.php')) ? $mod_dir : '';
	}
}

// this function checks if the specified optional module file exists
if (!function_exists('mod_file_exists')) {
	function mod_file_exists($mod_dir, $mod_file='frontend.css') {
  	// check if the module file exists
		return file_exists(WB_PATH .'/modules/' .$mod_dir .'/' .$mod_file);
	}
}

// this function displays the "Edit CSS" button in modify.php 
if (!function_exists('edit_module_css')) {
	function edit_module_css($mod_dir) {
		global $page_id, $section_id;

		// check if the required edit_module_css.php file exists
		if(!file_exists(WB_PATH .'/modules/edit_module_files.php')) return;
		
		// check if specified module directory is valid
		if(check_module_dir($mod_dir) == '') return;
		
		// check if frontend.css or backend.css exist
		$frontend_css = mod_file_exists($mod_dir, 'frontend.css');
		$backend_css = mod_file_exists($mod_dir, 'backend.css');
		
		// output the edit CSS submtin button if required
		if($frontend_css || $backend_css) {
			// default text used for the edit CSS routines if not defined in the WB core language files
			$edit_css_caption = (isset($GLOBALS['TEXT']['CAP_EDIT_CSS'])) ?$GLOBALS['TEXT']['CAP_EDIT_CSS'] :'Edit CSS';
			?>
			<form name="edit_module_file" action="<?php echo WB_URL .'/modules/edit_module_files.php?page_id='.$page_id;?>" 
				method="post" style="margin: 0; align:right;">
				<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
				<input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
				<input type="hidden" name="mod_dir" value="<?php echo $mod_dir; ?>" />
				<input type="hidden" name="edit_file" value="<?php echo ($frontend_css) ? 'frontend.css' : 'backend.css';?>" />
				<input type="hidden" name="action" value="edit" />
				<input type="submit" value="<?php echo $edit_css_caption;?>" class="mod_<?php echo $mod_dir;?>_edit_css" />
			</form>
			<?php
    }
  }
}

// this function displays a button to toggle between CSS files (invoked from edit_css.php)
if (!function_exists('toggle_css_file')) {
	function toggle_css_file($mod_dir, $base_css_file = 'frontend.css') {
		global $page_id, $section_id;
		// check if the required edit_module_css.php file exists
		if(!file_exists(WB_PATH .'/modules/edit_module_files.php')) return;

		// check if specified module directory is valid
		if(check_module_dir($mod_dir) == '') return;

		// do sanity check of specified css file
		if(!in_array($base_css_file, array('frontend.css', 'backend.css'))) return;
		
		// display button to toggle between the two CSS files: frontend.css, backend.css
		$toggle_file = ($base_css_file == 'frontend.css') ? 'backend.css' : 'frontend.css';
		if(mod_file_exists($mod_dir, $toggle_file)) {
			?>
			<form name="toggle_module_file" action="<?php echo WB_URL .'/modules/edit_module_files.php?page_id='.$page_id;?>" method="post" style="margin: 0; align:right;">
				<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
				<input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
				<input type="hidden" name="mod_dir" value="<?php echo $mod_dir; ?>" />
				<input type="hidden" name="edit_file" value="<?php echo $toggle_file; ?>" />
				<input type="hidden" name="action" value="edit" />
				<input type="submit" value="<?php echo ucwords($toggle_file);?>" class="mod_<?php echo $mod_dir;?>_edit_css" />
			</form>
			<?php
		}
  }
}

/*
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
 FUNCTIONS WHICH CAN BE USED BY MODULE DEVELOPERS FOR OWN MODULES (E.G. VIEW.PHP, MODIFY.PHP)
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
*/ 

// function to obtain the module language file depending on the backend language of the current user
if (!function_exists('get_module_language_file')) {
	function get_module_language_file($mymod_dir) {
		$mymod_dir = strip_tags($mymod_dir);
		if(file_exists(WB_PATH .'/modules/' .$mymod_dir .'/languages/' .LANGUAGE .'.php')) {
			// a module language file exists for the users backend language
			return (WB_PATH .'/modules/' .$mymod_dir .'/languages/' .LANGUAGE .'.php');
		} else {
			// an English module language file must exist in all multi-lingual modules
			if(file_exists(WB_PATH .'/modules/' .$mymod_dir .'/languages/EN.php')) {
				return (WB_PATH .'/modules/' .$mymod_dir .'/languages/EN.php');
			} else {
				echo '<p><strong>Error: </strong>';
				echo 'Default language file (EN.php) of module "' .htmlentities($mymod_dir) .'" does not exist.</p><br />';
				return false;
			}
		}
	}
}

// function to include module CSS files in <body> (only if WB < 2.6.7 or register_frontend_modfiles('css') not invoked in template)
if (!function_exists('include_module_css')) {
	function include_module_css($mymod_dir, $css_file) {
		if(!in_array(strtolower($css_file), array('frontend.css', 'backend.css'))) return;
		
		if($css_file == 'frontend.css') {
			// check if frontend.css needs to be included into the <body> section
			if(!((!function_exists('register_frontend_modfiles') || !defined('MOD_FRONTEND_CSS_REGISTERED')) &&
					file_exists(WB_PATH .'/modules/' .$mymod_dir .'/frontend.css'))) {
				return false;
			} 
		} else {
			// check if backend.css needs to be included into the <body> section
			global $admin;
			if(!(!method_exists($admin, 'register_backend_modfiles') && file_exists(WB_PATH .'/modules/' .$mymod_dir .'/backend.css'))) {
				return false;
			}
		}
		// include frontend.css or backend.css into the <body> section
		echo "\n".'<style type="text/css">'."\n";
  	include(WB_PATH .'/modules/' .$mymod_dir .'/' .$css_file);
  	echo "\n</style>\n";
		return true;
	}
}

// function to check if the optional module Javascript files are loaded into the <head> section
if (!function_exists('requires_module_js')) {
	function requires_module_js($mymod_dir, $js_file) {
		if(!in_array(strtolower($js_file), array('frontend.js', 'backend.js'))) {
			echo '<strong>Note: </strong>Javascript file "' .htmlentities($js_file) .'"
			specified in module "' .htmlentities($mymod_dir) .'" not valid.';
			return false;
		}

		if($js_file == 'frontend.js') {
			// check if frontend.js is included to the <head> section
			if(!defined('MOD_FRONTEND_JAVASCRIPT_REGISTERED')) {
				echo '<p><strong>Note:</strong> The module: "' .htmlentities($mymod_dir) .'" requires WB 2.6.7 or higher</p>
				<p>This module uses Javascript functions contained in frontend.js of the module.<br />
				Add the code below to the &lt;head&gt; section in the index.php of your template
				to ensure that module frontend.js files are automatically loaded if required.</p>
				<code style="color: #800000;">&lt;?php<br />if(function_exists(\'register_frontend_modfiles\')) { <br />
				&nbsp;&nbsp;register_frontend_modfiles(\'js\');<br />?&gt;</code><br />
				<p><strong>Tip:</strong> For WB 2.6.7 copy the code above to the index.php of your template.
				Then open the view.php of the "' .htmlentities($mymod_dir) .'" module and set the variable
				<code>$requires_frontend_js</code> to false. This may do the trick.</p><p>All WB versions below 2.6.7 needs
				to be upgraded to work with this module.</p>
				';
				return false;
			}
		} else {
			// check if backend.js is included to the <head> section
			global $admin;
				if(!method_exists($admin, 'register_backend_modfiles') && file_exists(WB_PATH .'/modules/' .$mymod_dir .'/backend.js')) {
				echo '<p><strong>Note:</strong> The module: "' .htmlentities($mymod_dir) .'" requires WB 2.6.7 or higher</p>
				<p>This module uses Javascript functions contained in backend.js of the module.<br />
				You need WB 2.6.7 or higher to ensure that module backend.js files are automatically loaded if required.</p>
				<p>Sorry, you can not use this tool with your WB installation, please upgrade to the latest WB version available.</p><br />
				';
				return false;
			}
		}
		return true;
	}
}
// function to check if the optional module Javascript files are loaded into the <body> section
if (!function_exists('requires_module_body_js')) {
	function requires_module_body_js($mymod_dir, $js_file) {
		if(!in_array(strtolower($js_file), array('frontend_body.js', 'backend_body.js'))) {
			echo '<strong>Note: </strong>Javascript file "' .htmlentities($js_file) .'"
			specified in module "' .htmlentities($mymod_dir) .'" not valid.';
			return false;
		}

		if($js_file == 'frontend_body.js') {
			// check if frontend_body.js is included to the <body> section
			if(!defined('MOD_FRONTEND_BODY_JAVASCRIPT_REGISTERED')) {
				echo '<p><strong>Note:</strong> The module: "' .htmlentities($mymod_dir) .'" requires WB 2.6.7 or higher</p>
				<p>This module uses Javascript functions contained in frontend_body.js of the module.<br />
				Add the code below before to the &lt;/body&gt; section in the index.php of your template
				to ensure that module frontend_body.js files are automatically loaded if required.</p>
				<code style="color: #800000;">&lt;?php<br />if(function_exists(\'register_frontend_modfiles_body\')) { <br />
				&nbsp;&nbsp;register_frontend_modfiles_body(\'js\');<br />?&gt;</code><br />
				<p><strong>Tip:</strong> For WB 2.6.7 copy the code above to the index.php of your template.
				Then open the view.php of the "' .htmlentities($mymod_dir) .'" module and set the variable
				<code>$requires_frontend_body_js</code> to false. This may do the trick.</p><p>All WB versions below 2.6.7 needs
				to be upgraded to work with this module.</p>
				';
				return false;
			}
		} else {
			// check if backend_body.js is included to the <body> section
			global $admin;
				if(!method_exists($admin, 'register_backend_modfiles_body') && file_exists(WB_PATH .'/modules/' .$mymod_dir .'/backend_body.js')) {
				echo '<p><strong>Note:</strong> The module: "' .htmlentities($mymod_dir) .'" requires WB 2.6.7 or higher</p>
				<p>This module uses Javascript functions contained in backend_body.js of the module.<br />
				You need WB 2.6.7 or higher to ensure that module backend_body.js files are automatically loaded if required.</p>
				<p>Sorry, you can not use this tool with your WB installation, please upgrade to the latest WB version available.</p><br />
				';
				return false;
			}
		}
		return true;
	}
}
