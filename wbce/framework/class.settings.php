<?php 
/*
Simple static Class for handling settings in Core and Modules
All setting names may only contain a-z 0-9 and "_" 
Core Settings are prepended by "wb_". (e.g. wb_maintainance_mode)
Module settings are prepended by module name or maybe by a shortened form of the module name
(e.g. wysi_my_setting for wysiwyg)
Please keep in mind that WB stores settings only as strings.

Copyright Norbert Heimsath
License GPLv2 or any later
*/

class Settings {

    // sets or overwrites a config setting
    public static function Set($name, $value) {
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
            $sql="UPDATE ".TABLE_PREFIX."settings SET value = '$value' WHERE name = '$name'";
            //echo htmlentities( $sql);
		    $database->query($sql);
	    }
        return false;
    }


    // only used as helper, as all setings converted to constants
    public static function Get($name, $default= false) {
        global $database; 

        $sql="SELECT value FROM ".TABLE_PREFIX."settings WHERE name = '".$name."'";
	    $rs = $database->query($sql);

	    if($row = $rs->fetchRow()) return $row['value'];

	    return $default;
    }

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


    // function to setup constants in init 
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
