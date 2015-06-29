<?php
/**
 *
 * @category        modules
 * @package         wrapper
 * @author          WebsiteBaker Project
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version      	$Id: modify.php 1538 2011-12-10 15:06:15Z Luisehahne $
 * @filesource		$HeadURL: http://svn.websitebaker2.org/branches/2.8.x/wb/modules/wrapper/install.php $
 * @lastmodified    $Date: 2011-01-10 13:21:47 +0100 (Mo, 10 Jan 2011) $
 *
 */

// Must include code to stop this file being access directly
/* -------------------------------------------------------- */
if(defined('WB_PATH') == false)
{
	// Stop this file being access directly
		die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */

// Setup template object
$template = new Template(WB_PATH.'/modules/wrapper');
$template->set_file('page', 'modify.htt');
$template->set_block('page', 'main_block', 'main');

// Get page content
$query = "SELECT url,height FROM ".TABLE_PREFIX."mod_wrapper WHERE section_id = '$section_id'";
$get_settings = $database->query($query);
$settings = $get_settings->fetchRow();
$url = ($settings['url']);
$height = $settings['height'];

// Insert vars
$template->set_var(array(
					'PAGE_ID' => $page_id,
					'SECTION_ID' => $section_id,
					'WB_URL' => WB_URL,
					'URL' => $url,
					'HEIGHT' => $height,
					'TEXT_URL' => $TEXT['URL'],
					'TEXT_HEIGHT' => $TEXT['HEIGHT'],
					'TEXT_SAVE' => $TEXT['SAVE'],
					'TEXT_CANCEL' => $TEXT['CANCEL'],
					'FTAN' => $admin->getFTAN()
				)
			);

// Parse template object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');
