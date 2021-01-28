<?php
/**
 * @file
 * @brief Simple static class for handling global settings in Core and Modules
 *
 * The basic idea is to make handling of global settings al lot easier.
 *
 * @author Norbert Heimsath(heimsath.org)
 * @copyright GPLv2 or any later
 *
 * For detailed information take a look at the actual documentation of class Settings.
 */

/**
 * @brief This class handles the management of global settings.
 *
 * It takes care of getting and setting those and it handles the converion and generation into good old constants.
 *
 * All setting names may only contain a-z 0-9 and "_" \n
 * Core settings are prepended by "wb_". (e.g. wb_maintainance_mode) \n
 * Module settings are prepended by module name or maybe by a shortened form of the module name. \n
 * (e.g. wysi_my_setting for wysiwyg)
 *
 * This class Now stores its data values although in its $aSettings array. for cached handling of requests.
 *
 *
 * @attention
 * Please keep in mind that WB stores settings only as strings.
 *
 * All those settings are converted into constants in the init process using Settings::Setup();
 * So "wb_maintainance_mode" is available as WB_MAINTAINANCE_MODE allover in WBCE.
 *
 *
 * Some examples:
 * @code
 * // create or edit a setting
 * Settings::Set("wb_new_setting","the value");
 *
 * // using a setting
 * if (WB_NEW_SETTING =="the value") echo "Horay";
 *
 * // there is a get function but this is mostly used internal
 * $myValue= Settings::Get ("wb_new_setting");
 *
 * // deleting
 * Settings::Delete("wb_new_setting");
 *
 * // if used in modules please prepend (shortened)module name to avoid collisions
 * Settings::Set("wysi_new_setting","another value");
 * @endcode
 *
 * @attention
 * Arrays and objects are now automatically serialized and de serialized by the "Get" functions.
 * As constants aren't capable of containing arrays and objects bevore PHP7 constants keep the serialized version
 * whith a !!SARRAY!! or !!SOBJECT!! prefix. But you can use the Settings::DeSerialize() function to de
 * serialize a constant
 *
 * @code
 * $aNeededArray = Settings::DeSerialize(WB_MY_ARRAY_SETTING);
 * @endcode
 *
 *
 * @todo Extend this class to handle different tables. Maybe by making this a class whith instances and a static facade.
 */
class Settings
{
    public static $aSettings = array();

    /**
     * @brief Sets a global setting.
     *
     * The Overwrite attribute has been added for use in upgradescripts where you do not want
     * to overwrite an existing setting. Often you only want to add a setting only if its not already set.
     *
     * @param string $name
     * The settings name.
     *
     * @param undefined $value
     * The value, only strings/boolean allowed.
     *
     * @param boolean $overwrite
     * Set to false to only write if setting does not exist.
     *
     * @retval boolean/string
     * Returns false on success and an error message on failure.
     */
    public static function Set($name = "", $value = "", $overwrite = true)
    {
        global $database;


        // Make sure we only  got 'a-zA-Z0-9_'
        if (!preg_match("/[a-zA-Z0-9\-]+/u", $name)) {
            return "Name only may contain 'a-zA-Z0-9_'";
        }
        $name = strtolower($name);

        // need to make sure , we only store a string
        if (is_array($value)) {
            $value = "!!SARRAY!!" . serialize($value);
        }
        if (is_object($value)) {
            $value = "!!SOBJECT!!" . serialize($value);
        }
        if (is_resource($value)) {
            return "Resources can't be stored in constants";
        }

        if (is_bool($value)) {
            $value = $value ? 'btrueb' : 'bfalseb';
        }

        // elseif (is_string($value)) $value = $value;
        // else                       $value="$value";
        // Not needed as Type juggling is done in sql composing


        // Already set ? Database always returns a string here not a boolean.
        // false können wir nicht mehmen NULL auch nicht
        $prev_value = Settings::Get($name, "#++#nullMAdrinn==?");

        // echo "value=".$value."<br>";


        // better go for savety
        $ename = $database->escapeString($name);
        $evalue = $database->escapeString($value);

        // If its a boolean there was nothing set.
        if ($prev_value === "#++#nullMAdrinn==?") {
            $sql = "INSERT INTO `{TP}settings` (`name`,`value`) VALUES ('$ename','$evalue')";
            $database->query($sql);

            // Set it to our Dataarray
            self::$aSettings[$name] = $value;
        } else {
            // stop here if overwrite is false
            if ($overwrite === false) {
                return "Setting already exists, overwrite forbidden";
            }

            $sql = "UPDATE `{TP}settings` SET `value` = '$evalue' WHERE `name` = '$ename'";
            // echo htmlentities( $sql);
            $database->query($sql);

            // Set it to our Dataarray
            self::$aSettings[$name] = $value;
        }

        return false;
    }

    /**
     * @brief Fetches a single setting.
     *
     * Prefered Way of fetching Data As constants  update only on next reload.
     *
     * Example:
     *
     * @code
     * GetDb("WB_DEFAULT_LANGUAGE");
     * // Returns "DE" or whatever language is set.
     * @endcode
     *
     * @param string $name
     * The settings name, eg. "wb_new_setting".
     *
     * @param undefined $default
     * Whatever you like as a returnvalue if the method does not find a matching entry.
     *
     * @retval array/undefined
     * Returns the value of the setting or $Default if nothing is found.
     */
    public static function Get($name, $default = false)
    {
        $name = strtolower($name);
        // echo $name;
        // echo self::$aSettings[$name];
        if (isset(self::$aSettings[$name])) {
            return self::DeSerialize(self::$aSettings[$name]);
        }

        return $default;
    }

    /**
     * @brief Unserialize arrays and objects stored as serialized strings
     *
     * It is used internally and to unserialize arrays and objects stored in constants
     *
     * @param string $value
     * The serialized value
     *
     * @retval boolean/string
     * Returns the unserialized object or array.
     */
    public static function DeSerialize($value)
    {
        if (preg_match("/^!!SARRAY!!/", $value)) {
            $value = preg_replace("/^!!SARRAY!!/", "", $value);
            return unserialize($value);
        }
        if (preg_match("/^!!SOBJECT!!/", $value)) {
            $value = preg_replace("/^!!SOBJECT!!/", "", $value);
            return unserialize($value);
        }

        return $value;
    }

    /**
     * @brief Fetches a single setting from DB
     *
     * Used mostly as helper, as all setings converted to constants and stored in settings Class.
     *
     * Example:
     *
     * @code
     * GetDb("WB_DEFAULT_THEME");
     * // Returns "argos_theme" or whatever theme is set.
     * @endcode
     *
     * @param string $name
     * The settings name, eg. "wb_new_setting".
     *
     * @param undefined $default
     * Whatever you like as a returnvalue if the method does not find a matching entry.
     *
     * @retval array/undefined
     * Returns the value of the setting or $Default if nothing is found.
     */
    public static function GetDb($name, $default = false)
    {
        global $database;

        $name = strtolower($name); // DB stores lowercase
        $name = $database->escapeString($name); // never thrust an input ;-)

        $sql = "SELECT `value` FROM `" . TABLE_PREFIX . "settings` WHERE `name` = '" . $name . "'";
        $rs = $database->query($sql);

        if ($row = $rs->fetchRow()) {
            $setting_value = self::DeSerialize($row['value']);
            if ($setting_value == 'bfalseb') {
                return false;
            }
            if ($setting_value == 'btrueb') {
                return true;
            }
            return self::DeSerialize($setting_value);
        }


        return $default;
    }

    /**
     * @brief Fetches all settings whith a certain prefix.
     *
     * Prefered Way of fetching Data As constants  update only on next reload.
     *
     * Example:
     *
     * @code
     * echo "<pre>";
     * print_r (Settings::GetPrefix("WB"));
     * echo "<pre>";
     *
     * // Returns
     *
     * Array
     * (
     * [WB_VERSION] => 2.8.3
     * [WB_REVISION] => 1641
     * [WB_SP] => SP4
     * [WB_MAINTAINANCE_MODE] =>
     * [WB_SUPPRESS_OLD_OPF] => 0
     * ...
     * [WB_DEFAULT_THEME] => argos_theme
     * }
     *
     * @endcode
     *
     * @param string $name
     * The settings name, eg. "wb_new_setting".
     *
     * @param string $prefix
     * The prefix of the setting WB_ ,   TOPICS_ ,   MYMODULE_ ,....
     *
     * @param undefined $default
     * Whatever you like as a returnvalue if the method does not find a matching entry.
     *
     * @retval array/undefined
     * Returns the valuee for this prefix or $Default if nothing is found.
     */
    public static function GetPrefix($prefix, $default = false)
    {

        // fetch array
        $MyArray = self::$aSettings;

        //  values are all uppercase
        $prefix = strtolower($prefix);

        // May only contain A-Z0-9_ case insensitiv
        $prefix = preg_replace("/[^A-Z0-9]/s", "", $prefix);

        // compensate for input errors (All "_" removed in regex)
        $prefix = $prefix . "_";

        // Filter the Array for matching keys
        $MyNewArray = array();
        foreach ($MyArray as $key => $value) {
            if (preg_match("/^$prefix/", $key)) {
                $MyNewArray[$key] = self::DeSerialize($value);
            }
        }

        // return if not empty
        if (!empty($MyNewArray)) {
            return $MyNewArray;
        }

        return $default;
    }

    /**
     * @brief Deletes single setting.
     *
     * Simply removes a setting , nothing more.
     *
     * @param string $name
     * The settings name, eg. "wb_new_setting".
     *
     * @retval boolean/string
     * Returns false on success and an error message on failure.
     */
    public static function Del($name)
    {
        global $database;

        $name = strtolower($name);

        // is it set ?
        $prev_value = Settings::Get($name);

        if ($prev_value === false) {
            return "Setting not set";
        } else {
            unset(self::$aSettings[$name]);
            $sql = "DELETE FROM `{TP}settings WHERE `name` = '$name'";
            $database->query($sql);
        }
        return false;
    }

    /**
     * @brief Method to setup constants and variables
     *
     * This Method is used in /framework/initialize.php to setup all settings as constants.
     * And create the aSettings Array inside the class for faster getting of values.
     *
     * @retval boolean/string
     * Returns false on success and an error message on failure.
     */
    public static function Setup()
    {
        global $database;

        // empty array
        self::$aSettings = array();
        // Get website settings (title, keywords, description, header, footer...)
        $sql = 'SELECT `name`, `value` FROM `{TP}settings`';
        if (($get_settings = $database->query($sql))) {
            $x = 0; //counter for debug

            while ($setting = $get_settings->fetchRow(MYSQLI_ASSOC)) {
                $setting_name = strtoupper($setting['name']);
                $setting_value = $setting['value'];
                if ($setting_value == 'bfalseb') {
                    $setting_value = false;
                }
                if ($setting_value == 'btrueb') {
                    $setting_value = true;
                }

                // @attention  This is for 1.x compatibility
                // @todo for 2.0 We need to cleanup the backend and remove this.
                if ($setting_value === 'false') {
                    $setting_value = false;
                }
                if ($setting_value === 'true') {
                    $setting_value = true;
                }
                // End of part to remove
                if (!defined($setting_name)) { //already set manually in config ?
                    define($setting_name, $setting_value);
                }

                self::$aSettings[strtolower($setting_name)] = self::DeSerialize(constant($setting_name));
                $x++;
            }
        } else {
            die($database->get_error());
        }

        return false;
    }

    /**
     * @brief A little method to display all settings in DB.
     *
     * This now calls to the self::$aSettings Array too to see difference between Constant and Variables.
     *
     * @retval string
     * All recent settings as a simple <br /> seperated list.
     */
    public static function Info()
    {
        global $database;


        $sql = "SELECT `name`, `value` FROM `{TP}settings` ";
        if (($get_settings = $database->query($sql))) {
            $out = "<h3>All Settings in DB </h3>";

            while ($setting = $get_settings->fetchRow(MYSQLI_ASSOC)) {
                $setting_name = strtoupper($setting['name']);
                $setting_value = $setting['value'];
                $setting_value = htmlentities($setting_value);
                $setting_value_var = htmlentities(self::$aSettings[strtolower($setting['name'])]);

                $out .= "<br /><b style=\"font-size:120%\">$setting_name</b><br /><b>Db</b>: $setting_value<br /><b>Array</b>: " . htmlentities(var_export(self::$aSettings[strtolower($setting['name'])], true)) . "<br />";
            }
        } else {
            die($database->get_error());
        }

        return $out;
    }
}
