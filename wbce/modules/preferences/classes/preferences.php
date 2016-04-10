 
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
        
        $sql  = 'SELECT `password` ';
        $sql .= 'FROM `'.TABLE_PREFIX.'users` ';
        $sql .= 'WHERE `user_id` = '.(int)$userId;
        $passHash = $database->get_one($sql);
        
        // old md5 passwords
        if (md5($password) ==  $passHash) return false;

        // sha1 replacement
        if ($this->HashPass($password,"sha1") == $passHash) return false;

        // PHP 5.5 buildin password functions
        if (version_compare(PHP_VERSION, '5.5.0' ) >= 0){
            if (password_verify ($password, $passHash)) return false;
        }
        return $MESSAGE['USERS_PASSWORD_INCORRECT'];
    }
    
    public function HashPass ($password, $forceOld=false){  
        
        if (version_compare(PHP_VERSION, '5.5.0' ) >= 0  and $forceOld===false) {
            //use_bcrypt
            if (defined ('WB_PASS_COST'))
                $password = password_hash($password, PASSWORD_BCRYPT, array('cost'=> WB_PASS_COST));
            else
                $password = password_hash($password, PASSWORD_BCRYPT);           
        } else if  ($forceOld=="md5") {
            //now we go for Very Old
            $password= md5($password);     
        } else { 
            //This is a extremely poor replacement for bcrypt 
            // but far better than md5
            $password = "hier ist dfdfd".$password."allesFtdg";
            for ($i = 1; $i <= 1959; $i++) {
                $password=sha1($password);
            }        
        }
        return $password;
    }






}