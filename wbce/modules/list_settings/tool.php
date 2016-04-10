<?php
/**
 * @category        core modules
 * @package         list_settings
 * @author          WBCE Project
 * @copyright       Norbert Heimsath
 * @license         GPL v2 or any later
 */


//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

// Generate Array of tools
$myTools=array();
$sql="SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'module' AND `function` LIKE '%setting%' AND `function` NOT LIKE '%hidden%' AND `directory` not in ('".(implode("','",$_SESSION['MODULE_PERMISSIONS']))."') order by name";
$results = $database->query($sql);
if($results->numRows() > 0) {
    while($tool = $results->fetchRow()) {

        // DefaultIcon if undefined
        if (!isset($tool['module_icon'])) $tool['module_icon'] = "fa fa-graduation-cap";

        $path=WB_PATH.'/modules/'.$tool['directory'].'/languages/'.LANGUAGE .'.php';
        
        if (file_exists($path)) {
            $data = file_get_contents($path);
            $tool_description = get_variable_content('module_description', $data, true, false);
            if ($tool_description) $tool['description']=$tool_description;
            $tool_name        = get_variable_content('module_name', $data, true, false);
            if ($tool_name) $tool['name']=$tool_name;
        }
        
        // Sooner or later this needs to be added to the Database
        // For now this just works

        // Default Icon
        $tool['icon']="fa fa-anchor";       
        
        $path=WB_PATH .'/modules/' .$tool['directory'] .'/info.php';
        if (file_exists($path)) {
            $data = file_get_contents($path);
            $tool_icon = get_variable_content('module_icon', $data, true, false);
            if ($tool_icon) $tool['icon']=$tool_icon;               
        }
        
        $myTools[]=$tool;        
    }      
}

include($this->GetTemplatePath("list.tpl.php"));
    

 
