<?php
/**
 *
 * @category        modules
 * @package         SecureFormSwitcher
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.2
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: EN.php 1479 2011-07-25 00:42:10Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/SecureFormSwitcher/languages/EN.php $
 * @lastmodified    $Date: 2011-07-25 02:42:10 +0200 (Mo, 25. Jul 2011) $
 *
*/

//Module description
$module_description = 'This module switch between the <strong>SingleTab SecureForm</strong> and <strong>MultiTab SecureForm</strong>.';

// Backend variables
$SFS_TEXT['TEXT_SWITCH'] = 'Change';
$SFS_TEXT['TXT_FTAN_SWITCH'] = 'Change to ';
$SFS_TEXT['SECURE_FORM'] = 'SingleTab SecureForm';
$SFS_TEXT['SECURE_FORMMTAB'] = 'Multitab SecureForm';
$SFS_TEXT['FILE_FORMTAB_NOT_GOUND'] = '<strong>Multitab not possible!<br />Needed file \'/framework/SecureForm.mtab.php\' not found!</strong><br />
<span>You have to upload the file manually via FTP</span>';
$SFS_TEXT['SUBMIT_FORM'] = 'Single Tab (recommended)';
$SFS_TEXT['SUBMIT_FORMTAB'] = 'Multi Tab';
$SFS_TEXT['SUBMIT'] = 'Accept';
$SFS_TEXT['INFO'] = 'Please select if you want to use the default security settings or the settings for working with several WebsiteBaker instances in parallel browser tabs.';
$SFS_TEXT['RESET_SETTINGS'] = 'Default setting';
$SFS_TEXT['ON_OFF'] = 'On/OFF';

// Variablen fuer AdminTool Optionen
$SFS_TEXT['WB_SECFORM_USEIP'] = 'IP-Blocks (1-4, 0=no check)';
$SFS_TEXT['WB_SECFORM_USEIP_TOOLTIP'] = '<span class="custom help"><em>Help</em>
These number of segments of an IP address can be used for the fingerprint. "4" means the whole IP address (this makes sense e.g. for servers with a stable IP address). "2" is a good compromise, because at home there\'s often the 24-hour reset and therefore only the first two segments keep constant.
<ul>
<li>4= xxx.xxx.xxx.xxx</li>
<li>3= xxx.xxx.xxx</li>
<li>2= xxx.xxx</li>
<li>1= xxx</li>
<li>0= no usage of the IP</li></ul></span>';
$SFS_TEXT['WB_SECFORM_TOKENNAME'] = 'Tokenname';
$SFS_TEXT['WB_SECFORM_TOKENNAME_TOOLTIP'] = '<span class="custom help"><em>Help</em>The name of the token. Coll. a token is often called TAN.</span>';
$SFS_TEXT['WB_SECFORM_SECRET'] = 'Secret (whatever you like)';
$SFS_TEXT['WB_SECFORM_SECRET_TOOLTIP'] = '<span class="custom help"><em>Help</em>A random key, that is being used for creating a TAN. Recommend are at least 20 digits.</span>';
$SFS_TEXT['WB_SECFORM_SECRETTIME'] = 'Secrettime';
$SFS_TEXT['WB_SECFORM_SECRETTIME_TOOLTIP'] = '<span class="custom help"><em>Help</em>Time (in seconds), until the secret-key will be renewed.</span>';
$SFS_TEXT['WB_SECFORM_TIMEOUT'] = 'Timeout';
$SFS_TEXT['WB_SECFORM_TIMEOUT_TOOLTIP'] = '<span class="custom help"><em>Help</em>Time (in seconds), until the form-token is void.</span>';
$SFS_TEXT['WB_SECFORM_USEFP'] = 'Fingerprinting';
$SFS_TEXT['WB_SECFORM_USEFP_TOOLTIP'] = '<span class="custom help"><em>Help</em>Require OS and browser for every TAN-validation additionally to the IP-address.</span>';
