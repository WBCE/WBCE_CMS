 
<?php
/**
 * @category        modules
 * @package         preferences
 * @author          WBCE Project
 * @copyright       Norbert Heimsath
 * @license         GPLv2
 */

// no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

class Preferences {

    /**
    
        For now we need to bring our own methods for Authentication handling     
        Returns false on success and an error message if something goes wrong.
        This one already capable of handling bcryp so they are somewhat an 
        example for the new authentication coming soon. 
        
    
    */
    public function CheckPass ($password="", $userId=false){
    
        global $MESSAGE, $database;

        if (!defined('WB_PASS_LENGTH_MIN')) define('WB_PASS_LENGTH_MIN', 6);
        if (!defined('WB_PASS_LENGTH_MAX')) define('WB_PASS_LENGTH_MAX', 50); 

        // empty
        if (empty($password)) return $MESSAGE['USERS_PASSWORD_EMPTY'];
        
        // too short
        $regex="/.{".WB_PASS_LENGTH_MIN.",}/su";
        if (!preg_match($regex, $password)) return $MESSAGE['USERS_PASSWORD_TOO_SHORT'];
        
        // too long
        $regex="/^.{1,".WB_PASS_LENGTH_MAX."}$/su";
        if (!preg_match($regex, $password)) return $MESSAGE['USERS_PASSWORD_TOO_LONG'];
        
        // Check password against DB only if a user is set 
        if (!$userId) return false;
        
        if(WbAuth::CheckUser ($password, (int)$userId)){
            return false;
        }
        else {
            return $MESSAGE['USERS_PASSWORD_INCORRECT'];
        }
    }
    
    public function HashPass ($password, $forceOld=false){  
        return WBAuth::Hash($password);
    }






}
