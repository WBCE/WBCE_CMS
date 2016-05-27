<?php 
/**
@file
@brief Simple static class for handling global settings in Core and Modules

The basic idea is to make handling of global settings al lot easier. 

@author Norbert Heimsath(heimsath.org)
@copyright GPLv2 or any later

For detailed information take a look at the actual documentation of class Settings.
*/

/**
@brief This class handles the management of global settings.

It takes care of getting and setting those and it handles the converion and generation into good old constants.

All setting names may only contain a-z 0-9 and "_" \n     
Core settings are prepended by "wb_". (e.g. wb_maintainance_mode)  \n   
Module settings are prepended by module name or maybe by a shortened form of the module name.  \n  
(e.g. wysi_my_setting for wysiwyg) 

@attention 
    Please keep in mind that WB stores settings only as strings.  

All those settings are converted into constants in the init process using Settings::Setup();
So "wb_maintainance_mode" is available as WB_MAINTAINANCE_MODE allover in WB(CE).

Some examples:
@code
// create or edit a setting
Settings::Set("wb_new_setting","the value");

// using a setting
if (WB_NEW_SETTING =="the value") echo "Horay";

// there is a get function but this is mostly used internal
$myValue= Settings::Get ("wb_new_setting");

// deleting
Settings::Delete("wb_new_setting");

// if used in modules please prepend (shortened)module name to avoid collisions
Settings::Set("wysi_new_setting","another value");
@endcode

@todo Extend this class to handle different tables.
@todo Allow to fetch module speciffic settings as an array, maybe even prefetch settings as an array.
    So we only need to return partial arrays of this main array.

*/

class Settings {

    /** 
    @brief Sets a global setting.

    The Overwrite attribute has been added for use in upgradescripts where you do not want 
    to overwrite an existing setting. Often you only want to add a setting only if its not already set. 
    
    @param string $name
        The settings name.

    @param undefined $value  
        The value, only strings/boolean allowed.

    @param boolean $overwrite
        Set to false to only write if setting does not exist.

    @retval boolean/string
        Returns false on success and an error message on failure.

    */
    public static function Set($name="", $value="", $overwrite=true) {
	    global $database;
        
        //Make sure we only  got 'a-zA-Z0-9_'
        if (!preg_match("/[a-zA-Z0-9\-]+/u", $name)) return "Name only may contain 'a-zA-Z0-9_'";
        $name = strtolower($name);

        // need to make sure , we only store a string
        if (is_array($value))    return "Arrays can't be stored in constants";
        if (is_object($value))   return "Objects can't be stored in constants";
        if (is_resource($value)) return "Resources can't be stored in constants";   
     
        if     (is_bool($value))   $value = $value ? 'true' : 'false';
        //elseif (is_string($value)) $value = $value;
        //else                       $value="$value";
        // Not needed as Type juggling is done in sql composing 


        // Already set ? Database always returns a string here not a boolean.
	    $prev_value = Settings::Get($name, false);

        // echo "value=".$value."<br>";

        // better go for savety
        $name =  $database->escapeString($name);
        $value = $database->escapeString($value);

        // If its a boolean there was nothing set.
	    if($prev_value === false) {
            $sql="INSERT INTO ".TABLE_PREFIX."settings (name,value) VALUES ('$name','$value')";
		    $database->query($sql);
	    } else {
            // stop here if overwrite is false
            if ($overwrite===false) return "Setting already exists, overwrite forbidden";

            $sql="UPDATE ".TABLE_PREFIX."settings SET value = '$value' WHERE name = '$name'";
            //echo htmlentities( $sql);
		    $database->query($sql);
	    }
        return false;
    }

    /**
    @brief Fetches a single setting. 
    
    Used mostly as helper, as all setings converted to constants.

    @param string $name
        The settings name, eg. "wb_new_setting".

    @param undefined $default
        Whatever you like as a returnvalue if the method does not find a matching entry.

    @retval array/undefined
        Returns the value of the setting or $Default if nothing is found. 
 
    */
    public static function Get($name, $default= false) {
        global $database; 

        $sql="SELECT value FROM ".TABLE_PREFIX."settings WHERE name = '".$name."'";
	    $rs = $database->query($sql);

	    if($row = $rs->fetchRow()) return $row['value'];

	    return $default;
    }
    /**
    @brief Deletes single setting. 
    
    Simply removes a setting , nothing more.

    @param string $name
        The settings name, eg. "wb_new_setting".

    @retval boolean/string
        Returns false on success and an error message on failure.
 
    */
    public static function Del($name) {
	    global $database;

        // is it set ?
	    $prev_value = Settings::get($name);

  	    if($prev_value === false) { 
            return "Setting not set";
        }      
        else {
            $sql="DELETE FROM ".TABLE_PREFIX."settings WHERE name = '$name'";
            $database->query($sql);
        }
        return false;
    }
    

    /**
    @brief Method to setup constants

    This Method is used in /framework/initialize.php to setup all settings as constants.  

    @retval boolean/string
        Returns false on success and an error message on failure.
 
    */
    public static function Setup() {
        global $database; 
        
        // Get website settings (title, keywords, description, header, footer...)
        $sql = 'SELECT `name`, `value` FROM `' . TABLE_PREFIX . 'settings`';
        if (($get_settings = $database->query($sql))) {
            $x = 0; //counter for debug

            while ($setting = $get_settings->fetchRow(MYSQL_ASSOC)) {
                $setting_name = strtoupper($setting['name']);
                $setting_value = $setting['value'];
                if ($setting_value == 'false') {
                    $setting_value = false;
                }
                if ($setting_value == 'true') {
                    $setting_value = true;
                }
                if (!defined($setting_name)) //already set manually in config ?
                    define($setting_name, $setting_value);
                $x++;
            }
        } 
        else {
            die($database->get_error());
        }
        return false;
    }

    /**
    @brief A little method to display all settings in DB.

    Basically it doe the same as Setup() but it generates a nice readable list.

    @retval string 
        All recent settings as a simple <br /> seperated list. 
    */
    // a function to display all settings in DB same as setup but returns a nice list
    public static function Info() {
        global $database;

        $sql = 'SELECT `name`, `value` FROM `' . TABLE_PREFIX . 'settings`';
        if (($get_settings = $database->query($sql))) {
            $out = "<h3>All Settings in DB</h3>";

            while ($setting = $get_settings->fetchRow(MYSQL_ASSOC)) {
                $setting_name = strtoupper($setting['name']);
                $setting_value = $setting['value'];
                $setting_value = htmlentities($setting_value);
               
                $out.= "$setting_name = $setting_value <br />";                   
            }
        } 
        else {
            die($database->get_error());
        }
        return $out;
    }
}



