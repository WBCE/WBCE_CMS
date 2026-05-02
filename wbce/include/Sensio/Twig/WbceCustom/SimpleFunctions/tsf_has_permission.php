<?php

/**
 * Get 
 *      SYSTEM_PERMISSIONS
 *      MODULE_PERMISSIONS
 *      TEMPLATE_PERMISSIONS
 *  
 */
$oTwig->addFunction(new \Twig\TwigFunction("has_permission", 
    function ($sName = '', $sType = 'SYSTEM') {
        $retVal = false;
        if ($_SESSION['GROUP_ID'] == '1'){
            return true;
        }
        $aPerms = $_SESSION[$sType.'_PERMISSIONS'];
        if ($sName == ''){
            $retVal = $aPerms;
        } else {
            if (in_array($sName, $aPerms)){
                $retVal = true;
            }
        }        
        return $retVal;
    }
));

