<?php
/**
 * AdminTool: addonMonitor
 *
 * This file provides some functions for the addonMonitor Tool.
 *
 * @package     addonMonitor
 * @author      Christian M. Stefan (Stefek)
 * @copyright   Christian M. Stefan
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 */

// prevent this file from being accessed directly:
defined('WB_PATH') or die("Cannot access this file directly");

if (!function_exists('getModulesArray')) {
    function getModulesArray()
{
	$whiteListAddons = ['page', 'tool', 'snippet', 'wysiwyg'];
    global $database;
    $aAddons['addons'] = array();
    $aAddons['count_tools'] = 0;
    $aAddons['count_snippets'] = 0;
    $aAddons['count_pagemodules'] = 0;
    $aAddons['count_wysiwygeditors'] = 0;
    $aAddons['default_wysiwyg'] = $database->get_one("SELECT `value` FROM `{TP}settings` WHERE `name` = 'wysiwyg_editor'");
    
    $sQueryAddons = (
        "SELECT DISTINCT a.*,
        IF(s.module IS NOT NULL, 'Y', 'N') AS active
        FROM {TP}addons a
        LEFT JOIN {TP}sections s ON a.directory = s.module
        WHERE `type` = 'module'"
    );

    if ($oAddons = $database->query($sQueryAddons)) {
        $sLanguageFileLocation = WB_PATH . "/modules/%s/languages/" . LANGUAGE . ".php";

        // Loop through addons
        while ($aRec = $oAddons->fetchRow(MYSQLI_ASSOC)) {
            // Split the function string into an array
            $functions = array_map('trim', explode(',', $aRec['function']));
            foreach ($functions as $function) {
				if (!in_array($function, $whiteListAddons)) continue;
				$aRec['function'] = $function;
				#debug_dump($function, $aRec['name']);
                switch ($function) {
                    case 'page':
                        ++$aAddons['count_pagemodules'];
                        if ($aRec['active'] == 'Y') {
                            $sQueryActiveSections = ("SELECT `section_id`, `page_id` FROM `{TP}sections` WHERE `module` = '" . $aRec['directory'] . "'");
                            if ($oActiveSections = $database->query($sQueryActiveSections)) {
                                while ($aSections = $oActiveSections->fetchRow(MYSQLI_ASSOC)) {
                                    $aRec['active_sections'][$aSections['section_id']] = $aSections['page_id'];
                                }
                            }
                        }
                        break;

                    case 'tool':
                        $aAddons['count_tools']++;
                        $sIconFile = WB_PATH . "/modules/" . $aRec['directory'] . "/tool_icon.png";
                        $aRec['icon'] = is_readable($sIconFile) ? "../../modules/" . $aRec['directory'] . "/tool_icon.png" : "../../modules/" . basename(dirname(__FILE__)) . "/icons/tool.png";
                        break;

                    case 'snippet':
                        ++$aAddons['count_snippets'];
                        break;

                    case 'wysiwyg':
                        ++$aAddons['count_wysiwygeditors'];
                        break;
                }

                // Handle icons for snippets and page modules
                if ($function === 'snippet' || $function === 'page') {
                    $sIconFile = "/modules/" . $aRec['directory'] . "/addon_icon.png";
                    $aRec['icon'] = is_readable(WB_PATH . $sIconFile) ? "../.." . $sIconFile : "../../modules/" . basename(dirname(__FILE__)) . "/icons/" . ($function === 'snippet' ? 'snippet' : 'page_module') . ".png";
                }

				// Replace description if description in the current LANGUAGE exists
				$sLanguageFile = sprintf($sLanguageFileLocation, $aRec['directory']);
				if (is_readable($sLanguageFile)) {
					require_once($sLanguageFile);
					if (isset($module_description) && $module_description != "") {
						$aRec['description'] = $module_description;
						unset($module_description);
					}
				}

				$aAddons['addons'][] = $aRec;
            }
        }
    }
    
    return $aAddons;
}
}

if (!function_exists('getTemplatesArray')) {
    function getTemplatesArray()
    {
        global $database;
        $sDefaultAcp = $database->get_one("SELECT `value` FROM `{TP}settings` WHERE `name` = 'default_theme'");
        $aAddons['addons'] = array();
        $aAddons['count_pagetemplates'] = 0;
        $aAddons['count_acpthemes'] = 0;
        $sQueryAddons = (
            "SELECT DISTINCT a . * ,
            IF( p.template IS NOT NULL , 'Y', 'N' ) AS active
            FROM {TP}addons a
                LEFT JOIN {TP}pages p
                ON a.directory = p.template
            WHERE `function` = 'theme' OR `function` = 'template'
            ORDER BY `function`, `name`"
        );

        if ($oAddons = $database->query($sQueryAddons)) {
            // Loop through addons
            while ($aRec = $oAddons->fetchRow(MYSQLI_ASSOC)) {
                // grab for page_id's Addon is used on different pages
                if ($aRec['function'] == 'template') {
                    ++$aAddons['count_pagetemplates'];
                    // grab for page_id's if Addon is used on different pages
                    if ($aRec['active'] == 'Y') {
                        $sQueryActiveSections = ("SELECT `page_id` FROM `{TP}pages` WHERE `template` = '".$aRec['directory']."'");
                        if ($oActiveSections = $database->query($sQueryActiveSections)) {
                            while ($aSections = $oActiveSections->fetchRow(MYSQLI_ASSOC)) {
                                $aRec['active_pages'][] = $aSections['page_id'];
                            }
                        }
                    }
                } else {
                    ++$aAddons['count_acpthemes'];
                }
                // icon
                $sIconFile = "/templates/".$aRec['directory']."/preview.jpg";
                if (is_readable(WB_PATH.$sIconFile)) {
                    $aRec['icon'] = "../..".$sIconFile;
                } else {
                    $sType = ($aRec['function'] == 'theme') ? 'acp_theme' : 'page_template';
                    $aRec['icon'] = "../../modules/".basename(dirname(__FILE__))."/icons/".$sType."_preview.jpg";
                }
                $aAddons['addons'][]  = $aRec;
            }
        }
        return $aAddons;
    }
}
if (!function_exists('getLanguagesArray')) {
    function getLanguagesArray()
    {
        global $database;
        $aAddons['addons'] = array();
        $sQueryAddons = (
            "SELECT DISTINCT a . * ,
            IF( p.language IS NOT NULL , 'Y', 'N' ) AS active
            FROM {TP}addons a
                LEFT JOIN {TP}pages p
                ON a.directory = p.language
            WHERE type = 'language'"
        );

        if ($oAddons = $database->query($sQueryAddons)) {
            // Loop through addons
            while ($aRec = $oAddons->fetchRow(MYSQLI_ASSOC)) {
                // grab for page_id's if Addon is used on different pages
                if ($aRec['active'] == 'Y') {
                    $sQueryActiveSections = ("SELECT `page_id` FROM `{TP}pages` WHERE `language` = '".$aRec['directory']."'");
                    if ($oActiveSections = $database->query($sQueryActiveSections)) {
                        while ($aSections = $oActiveSections->fetchRow(MYSQLI_ASSOC)) {
                            $aRec['active_pages'][] = $aSections['page_id'];
                        }
                    }
                }
                // icon
                $sIconFile = "/languages/".strtolower($aRec['directory']).".png";
                if (is_readable(WB_PATH.$sIconFile)) {
                    $aRec['icon'] = "../..".$sIconFile;
                } else {
                    $sType = ($aRec['function'] == 'theme') ? 'acp_theme' : 'frontend_template';
                    $aRec['icon'] = "../../modules/".basename(dirname(__FILE__))."/icons/unknown.png";
                }
                $aAddons['addons'][]  = $aRec;
            }
        }
        return $aAddons;
    }
}
