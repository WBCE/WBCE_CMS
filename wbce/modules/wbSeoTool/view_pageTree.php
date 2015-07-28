<?php
/**
 * WebsiteBaker CMS AdminTool: wbSeoTool
 *
 * view_pageTree.php
 * 
 * 
 * @platform    CMS WebsiteBaker 2.8.x
 * @package     wbSeoTool
 * @author      Christian M. Stefan (Stefek)
 * @copyright   Christian M. Stefan
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 */

// prevent this file from being accessed directly
if(!defined('WB_PATH')) exit("Cannot access this file directly ".__FILE__);

// user needs permission for admintools OR pages
if(!$admin->get_permission('admintools') || !$admin->get_permission('pages')) {
	exit("insuficient privileges");
}

$sFunctionsFile = dirname(__FILE__).'/functions.php';	
if(file_exists($sFunctionsFile)){
	require_once($sFunctionsFile);
}

function exportPageTreeToTwig() {
	global $_CONFIG, $HEADING, $TEXT, $TOOL_TEXT, $toolUrl;
	/**
	 * 		Create Twig template object and configure it
	 */
	 if(!class_exists('Twig_Autoloader')) {
		$sTmp = dirname(__FILE__) . '/TwigTE/Twig/Autoloader.php';
		
		if(file_exists($sTmp)){
			include($sTmp); 
			Twig_Autoloader::register();
		}else{
			if(LANGUAGE == "DE"){
				echo '<b>TWIG ist nicht initialisiert!</b><br />Bitte installieren Sie das TWIG Patch oder das TWIG Snippet. Siehe WebsiteBaker Forum Thread zu diesem Modul für weitere Information';
			} else {
				echo '<b>TWIG is not initialized!</b><br />Please install the TWIG Patch or the TWIG Snippet; see the WebsiteBaker Forum Thread of this Module for further information';		
			}		
		}
	}
	$oTwigLoader = new Twig_Loader_Filesystem(dirname(__FILE__) . '/skel'); // tell Twig where templates will come from
	$oTwig = new Twig_Environment($oTwigLoader, array(
		'autoescape'       => false,
		'cache'            => false,
		'strict_variables' => false,
		'debug'            => true,
	));
	$oTwig->addExtension(new Twig_Extension_Debug());	// load extension		
	$oTemplate = $oTwig->loadTemplate("pageTree.twig");	// load the template by name
	
	$oTwig->addGlobal('lang', array_merge($TEXT, $HEADING, $TOOL_TEXT)); 
	$oTwig->addGlobal('WB_URL', WB_URL); 
	$oTwig->addGlobal('ADDON_URL', "../../modules/".basename(dirname(__FILE__))); 
	$oTwig->addGlobal('ICONS', "../../modules/".basename(dirname(__FILE__)).'/icons'); 
	$oTwig->addGlobal('TOOL_URL', $toolUrl); 
	if(defined("USE_FLAGS")){
		$oTwig->addGlobal('USE_FLAGS', USE_FLAGS); 
	}
	if(defined("KEYWORDS_CONFIG")){
		$oTwig->addGlobal('KEYWORDS_CONFIG', KEYWORDS_CONFIG); 
	}
	if(defined("REWRITE_URL")){
		$oTwig->addGlobal('REWRITE_URL', REWRITE_URL); 
	}
	
	// ouput Template
	$oTemplate->display(
		array('pages' => pagesArray(TRUE))
	);	
}
exportPageTreeToTwig();
