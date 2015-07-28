<?php
/**
 * WebsiteBaker CMS AdminTool: addonMonitor
 *
 * This file monitors the functionality of the AdminTool
 * 
 * 
 * @platform    CMS WebsiteBaker 2.8.x
 * @package     addonMonitor
 * @author      Christian M. Stefan (Stefek)
 * @copyright   Christian M. Stefan
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 */
 
// Direct access prevention
defined('WB_PATH') OR die(header('Location: ../index.php'));


if(!class_exists('admin', false)){ 	
	$admin_header = FALSE;
	include(WB_PATH.'/framework/class.admin.php');
	$admin = new admin('admintools', 'admintools');
}
// check for permission
if(!$admin->get_permission('admintools')) {
	die( header('Location: ../../index.php') );
}

require_once((dirname(__FILE__)) . '/info.php');
// get functions file for this AdminTool
require_once((dirname(__FILE__)) . '/functions.php');

$sAddonDir = $module_directory;
// register TWIG autoloader if not done already 
// this is of importance for WebsiteBaker Versions prior to 2.8.4. or derivate systems
if(!class_exists('Twig_Autoloader')) {
	$sTwigAutoloader = dirname(__FILE__) . '/TwigTE/Twig/Autoloader.php';
	
	if(file_exists($sTwigAutoloader)){
		include($sTwigAutoloader); 
		Twig_Autoloader::register();
	}
}
/**
 * Create Twig template object and configure it
 */
$oTwigLoader = new Twig_Loader_Filesystem(dirname(__FILE__) . '/skel'); // tell Twig where the template will come from
$oTwig = new Twig_Environment($oTwigLoader, array(
	'autoescape'       => false,
	'cache'            => false,
	'strict_variables' => false,
	'debug'            => true
));
$oTwig->addExtension(new Twig_Extension_Debug());	// load extension	
// SET SOME GLOBALS FOR USE ALONG WITH TWIG-TEMPLATES
$oTwig->addGlobal('WB_URL', WB_URL); 
$oTwig->addGlobal('ICONS_DIR', '../../modules/'.$sAddonDir.'/icons'); 

$aOuptut = array();
$sMonitorCase = isset($_GET['addons']) ? (string) $_GET['addons'] : 'modules';
$sActiveModules = '';
$sActiveTemplates = '';
$sActiveLanguages = '';	
	switch($sMonitorCase){		
		case 'templates':
			// frontend templates AND admin control panel (acp) themes
			$sActiveTemplates = 'current_tab';	
			$aOuptut = getTemplatesArray();			
		break;		
		case 'languages':
			// languages
			$sActiveLanguages = 'current_tab';	
			$aOuptut = getLanguagesArray();
		break;	
		case 'modules':
			// page-type modules, admin-tools AND snippets
		default:
			$aOuptut = getModulesArray();
			$sActiveModules = 'current_tab';		
		break;
	}	
$oTemplate = $oTwig->loadTemplate('monitor_' . $sMonitorCase . '.twig');	// load the template by name

$sToolUrl = ADMIN_URL.'/admintools/tool.php?tool='.$sAddonDir;
?>
<div id="tool">
	<h4 style="font-size: 12px;"><b>Addon Monitor</b> <i><?php echo $module_version;?></i></h4>
</div>
<div class="tabs" id="sometabs">
	<ul class="tabs-list">
		<li class="<?php echo $sActiveModules;?>"><a href="<?php echo $sToolUrl;?>&addons=modules">MODULES</a></li>
		<li class="<?php echo $sActiveTemplates;?>"><a href="<?php echo $sToolUrl;?>&addons=templates">TEMPLATES</a></li>
		<li class="<?php echo $sActiveLanguages;?>"><a href="<?php echo $sToolUrl;?>&addons=languages">LANGUAGES</a></li>
	</ul><div style="clear:both;"></div>
	<div class="">
		<?php $oTemplate->display($aOuptut); ?>
	</div>
</div>
<noscript>You should turn on JavaScript in your browser for full benefit of this Admin Tool</noscript>
<script type="text/javascript" language="javascript">
	var TOOL_URL = WB_URL + "/modules/<?php echo $sAddonDir ?>";
</script>