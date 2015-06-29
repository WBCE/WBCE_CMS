<?php
/**
 *
 * @category        module
 * @package         precheck
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: addon.precheck.inc.php 1499 2011-08-12 11:21:25Z DarkViper $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/framework/addon.precheck.inc.php $
 * @lastmodified    $Date: 2011-08-12 13:21:25 +0200 (Fr, 12. Aug 2011) $
 *
 */

/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {
	require_once(dirname(__FILE__).'/globalExceptionHandler.php');
	throw new IllegalFileException();
}
/* -------------------------------------------------------- */
function getVersion($version, $strip_suffix = true)
{
	/**
	 * This funtion creates a version string following the major.minor.revision convention
	 * The minor and revision part of the version may not exceed 999 (three digits)
	 * An optional suffix part can be added after revision (requires $strip_suffix = false)
	 *
	 * EXAMPLES: input --> output
	 *	5 --> 5.000000; 5.0 --> 5.000000; 5.0.0 --> 5.000000
	 * 	5.2 --> 5.002000; 5.20 --> 5.002000; 5.2.0 --> 5.002000
	 * 	5.21 --> 5.002001; 5.2.1 --> 5.002001;
	 * 	5.27.1 --> 5.027001; 5.2.71 --> 5.002071;
	 * 	5.27.1 rc1 --> 5.027001_RC1 ($strip_suffix:= false)
	 */
	// replace comma by decimal point
	$version = str_replace(',', '.', $version);

	// convert version into major.minor.revision numbering system
	list($major, $minor, $revision) = explode('.', $version, 3);

	// convert versioning style 5.21 into 5.2.1
	if ($revision == '' && strlen(intval($minor)) == 2) {
		$revision = substr($minor, -1);
		$minor = substr($minor, 0, 1);
	}
	
	// extract possible non numerical suffix from revision part (e.g. Alpha, Beta, RC1)
	$suffix = strtoupper(trim(substr($revision, strlen(intval($revision)))));

/*
	return (int)$major . '.' . sprintf('%03d', (int)$minor) . sprintf('%03d', (int)$revision) .
		(($strip_suffix == false && $suffix != '') ? '_' . $suffix : '');
*/
	// return standard version number (minor and revision numbers may not exceed 999)
    return sprintf('%d.%03d.%03d%s', (int)$major, (int)minor, (int)$revision,
    (($strip_suffix == false && $suffix != '') ? '_' . $suffix : ''));
}

/**
 *	As "version_compare" it self seems only got trouble 
 *	within words like "Alpha", "Beta" a.s.o. this function
 *	only modify the version-string in the way that these words are replaced by values/numbers.
 *
 *	E.g:	"1.2.3 Beta2" => "1.2.322"
 *			"0.1.1 ALPHA" => "0.1.11"
 *
 *	Notice:	Please keep in mind, that this will not correct the way "version_control" 
 *			handel "1 < 1.0 < 1.0.0 < 1.0.0.0" and will not correct missformed version-strings
 *			below 2.7, e.g. "1.002 released candidate 2.3"
 *			
 *	@since	2.8.0 RC2
 *
 *	@param	string	A versionstring
 *	@return	string	The modificated versionstring
 *
 */
function getVersion2 ($version="") {
	
	$states = array (
		'1' => "alpha",
		'2' => "beta",
		'4' => "rc",
		'8' => "final"	
	);

	$version = strtolower($version);
	
	foreach($states as $value=>$keys) $version = str_replace($keys, $value, $version);

	$version = str_replace(" ", "", $version);

	return $version;
}

function versionCompare($version1, $version2, $operator = '>=')
{
	/**
	 * This funtion performs a comparison of two provided version strings
	 * The versions are first converted into a string following the major.minor.revision 
	 * convention and performs a version_compare afterwards.
	 */
	// return version_compare(getVersion($version1), getVersion($version2), $operator);
	return version_compare(getVersion2($version1), getVersion2($version2), $operator);
}

function sortPreCheckArray($precheck_array)
{
	/**
	 * This funtion sorts the precheck array to a common format
	 */
	// define desired precheck order
	$key_order = array('WB_VERSION', 'WB_ADDONS', 'PHP_VERSION', 'PHP_EXTENSIONS', 'PHP_SETTINGS', 'CUSTOM_CHECKS');

	$temp_array = array();
	foreach($key_order as $key) {
		if (!isset($precheck_array[$key])) continue;
		$temp_array[$key] = $precheck_array[$key];
	}
	return $temp_array;
}

function preCheckAddon($temp_addon_file)
{
	/**
	 * This funtion performs pretest upfront of the Add-On installation process.
	 * The requirements can be specified via the array $PRECHECK which needs to
	 * be defined in the optional Add-on file precheck.php.
	 */
	global $database, $admin, $TEXT, $HEADING, $MESSAGE;
	
	// path to the temporary Add-on folder
	$temp_path = WB_PATH . '/temp/unzip';
	
	// check if file precheck.php exists for the Add-On uploaded via WB installation routine
	if (!file_exists($temp_path . '/precheck.php')) return;
	
	// unset any previous declared PRECHECK array
	unset($PRECHECK);

	// include Add-On precheck.php file
	include($temp_path . '/precheck.php');
	
	// check if there are any Add-On requirements to check for
	if (!(isset($PRECHECK) && count($PRECHECK) > 0)) return;
	
	// sort precheck array
	$PRECHECK = sortPreCheckArray($PRECHECK);
	
	$failed_checks = 0;
	$msg = array();
	// check if specified addon requirements are fullfilled
	foreach ($PRECHECK as $key => $value) {
		switch ($key) {
			case 'WB_VERSION':
				if (isset($value['VERSION'])) {
					// obtain operator for string comparison if exist
					$operator = (isset($value['OPERATOR']) &&  trim($value['OPERATOR']) != '') ? $value['OPERATOR'] : '>=';
				
					// compare versions and extract actual status
					$status = versionCompare(WB_VERSION, $value['VERSION'], $operator);
					$msg[] = array(
						'check'		=> 'WB-' . $TEXT['VERSION'] .': ',
						'required'	=> htmlentities($operator) . $value['VERSION'],
						'actual'	=> WB_VERSION,
						'status'	=> $status
					);

					// increase counter if required
					if (!$status) $failed_checks++;
				}
				break;

			case 'WB_ADDONS':
				if (is_array($PRECHECK['WB_ADDONS'])) {
					foreach($PRECHECK['WB_ADDONS'] as $addon => $values) {
						if (is_array($values)) {
							// extract module version and operator
							$version = (isset($values['VERSION']) &&  trim($values['VERSION']) != '') ? $values['VERSION'] : '';
							$operator = (isset($values['OPERATOR']) &&  trim($values['OPERATOR']) != '') ? $values['OPERATOR'] : '>=';
						} else {
							// no version and operator specified (only check if addon exists)
							$addon = strip_tags($values);
							$version = ''; $operator = '';
						}
					
						// check if addon is listed in WB database
						$table = TABLE_PREFIX . 'addons';
						$sql = "SELECT * FROM `$table` WHERE `directory` = '" . addslashes($addon) . "'";
						$results = $database->query($sql);
					
						$status = false; $addon_status = $TEXT['NOT_INSTALLED'];
						if ($results && $row = $results->fetchRow()) {
							$status = true; 
							$addon_status = $TEXT['INSTALLED'];
						
							// compare version if required
							if ($version != '') {
								$status = versionCompare($row['version'], $version, $operator);
								$addon_status = $row['version'];
							}
						}
					
						// provide addon status
						$msg[] = array(
							'check'		=> '&nbsp; ' . $TEXT['ADDON'] . ': ' . htmlentities($addon),
							'required'	=> ($version != '') ? $operator . '&nbsp;' . $version : $TEXT['INSTALLED'],
							'actual'	=> $addon_status,
							'status'	=> $status
						);
						
						// increase counter if required
						if (!$status) $failed_checks++;
					}
				}
				break;

			case 'PHP_VERSION':
				if (isset($value['VERSION'])) {
					// obtain operator for string comparison if exist
					$operator = (isset($value['OPERATOR']) &&  trim($value['OPERATOR']) != '') ? $value['OPERATOR'] : '>=';
				
					// compare versions and extract actual status
					$status = versionCompare(PHP_VERSION, $value['VERSION'], $operator);
					$msg[] = array(
						'check'		=> 'PHP-' . $TEXT['VERSION'] .': ',
						'required'	=> htmlentities($operator) . '&nbsp;' . $value['VERSION'],
						'actual'	=> PHP_VERSION,
						'status'	=> $status
					);

					// increase counter if required
					if (!$status) $failed_checks++;

				}
				break;

			case 'PHP_EXTENSIONS':
				if (is_array($PRECHECK['PHP_EXTENSIONS'])) {
					foreach($PRECHECK['PHP_EXTENSIONS'] as $extension) {
						$status = extension_loaded(strtolower($extension));
						$msg[] = array(
							'check'		=> '&nbsp; ' . $TEXT['EXTENSION'] . ': ' . htmlentities($extension),
							'required'	=> $TEXT['INSTALLED'],
							'actual'	=> ($status) ? $TEXT['INSTALLED'] : $TEXT['NOT_INSTALLED'],
							'status'	=> $status
						);

						// increase counter if required
						if (!$status) $failed_checks++;
					}
				}
				break;

			case 'PHP_SETTINGS':
				if (is_array($PRECHECK['PHP_SETTINGS'])) {
					foreach($PRECHECK['PHP_SETTINGS'] as $setting => $value) {
						$actual_setting = ($temp = ini_get($setting)) ? $temp : 0;
						$status = ($actual_setting == $value);
					
						$msg[] = array(
							'check'		=> '&nbsp; '. ($setting),
							'required'	=> $value,
							'actual'	=> $actual_setting,
							'status'	=> $status
						);

						// increase counter if required
						if (!$status) $failed_checks++;
					}
				}
				break;

			case 'CUSTOM_CHECKS':
				if (is_array($PRECHECK['CUSTOM_CHECKS'])) {
					foreach($PRECHECK['CUSTOM_CHECKS'] as $key => $values) {
						$status = (true === array_key_exists('STATUS', $values )) ? $values['STATUS'] : false;
						$msg[] = array(
							'check'		=> $key,
							'required'	=> $values['REQUIRED'],
							'actual'	=> $values['ACTUAL'],
							'status'	=> $status
						);
					}

					// increase counter if required
					if (!$status) $failed_checks++;
				}
				break;
		}
	}

	// leave if all requirements are fullfilled
	if ($failed_checks == 0) return;
	
	// output summary table with requirements not fullfilled
	echo <<< EOT
	<h2>{$HEADING['ADDON_PRECHECK_FAILED']}</h2>
	<p>{$MESSAGE['ADDON']['PRECHECK_FAILED']}</p> 

	<table width="700px" cellpadding="4" border="0" style="margin: 0.5em; border-collapse: collapse; border: 1px solid silver;">
	<tr>
		<th>{$TEXT['REQUIREMENT']}:</th>
		<th>{$TEXT['REQUIRED']}:</th>
		<th>{$TEXT['CURRENT']}:</th>
	</tr>
EOT;

	foreach($msg as $check) {
		echo '<tr>';
		$style = $check['status'] ? 'color: #46882B;' : 'color: #C00;';
		foreach($check as $key => $value) {
			if ($key == 'status') continue;
			
			echo '<td style="' . $style . '">' . $value . '</td>';
		}
		echo '</tr>';
	}
	echo '</table>';

	// delete the temp unzip directory
	rm_full_dir($temp_path);	

	// delete the temporary zip file of the Add-on
	if(file_exists($temp_addon_file)) { unlink($temp_addon_file); }	
	
	// output status message and die
	$admin->print_error('');
}

?>