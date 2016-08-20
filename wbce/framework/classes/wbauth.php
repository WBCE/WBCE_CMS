<?php
/**
    @file 
    @brief Contains the new class to handle authentication stuff  
*/
/**
    @brief Contains all the stuff to handle authentication
        This is a static class to be easyly available everywhere. (Global)
*/
class WbAuth  {

    public static function AutoAuthenticate(){
    
        global $wb;
    
        //already logged in, ok please logout bevore changing user
        if($wb->is_authenticated()) return false;
        
        // Spam Protection can create wiered namend fields for login as the default 
        // fields are used as a honeypot 
        if ($this->get_post('username_fieldname') != '') {
            $username_fieldname = $this->get_post('username_fieldname');
            $password_fieldname = $this->get_post('password_fieldname');
        } else {
            // no special fields use the default ones 
            $username_fieldname = 'username';
            $password_fieldname = 'password';
        }
        
        // username may only be lowercase.. html chars dont belong here 
        $uUser = $this->get_post($username_fieldname);
        $uUser = $this->CleanUser($uUser);

        $sPassword = $this->get_post($password_fieldname);
        
        return self::Authenticate ($sPassword,$uUser);  
    }
 /**
    @brief Handles the full authentication process 
   
    @param string $sPassword The actual password to checks
    @param undefined $uUser Either the username , usermail, or userid 
    @return boolean/string returns false or Error message.      
*/
    public static function Authenticate ($sPassword,$uUser) {
        global $database, $wb;

        // check the username/mail/ID and password combination
        $oUser=self::CheckUser ($sPassword, $uUser);
        
        // if failed $oUser contains the error string otherwise it contains the user object
        if (is_string($oUser)) return $oUser;
        
        // Authentication successfull so :
        
        // get access rights for the user 
        $oUser->FetchGroupInfo();

        // store necessary userdata to session
        $oUser->StoreToSession();

        // change last login and ip 
        $oUser->LoginWhen = time();
        $oUser->LoginIp = $_SERVER['REMOTE_ADDR'];
        
        // store changes to user 
        $oUser->Save();
        
        
        // Return false as all is ok 
        return false;
    }

/**
   @brief  Fetches USer PWhash from DB and compares to recent given password.
   
    Does Automatic rehash. A Loginform must validate that you dont log in whith you user id 
    if its not wanted that way.
    
    @param string $sPassword The actual password to checks
    @param undefined $uUser Either the username , usermail, or userid 
    @return object/string returns user object or Error message.      
*/
    public static function CheckUser ($sPassword, $uUser){
    
        global $MESSAGE;
        
        // hey no input ... 
        if (empty($sPassword) AND empty($uUser)) return $MESSAGE['LOGIN_BOTH_BLANK']; 
        if (empty($uUser)) return $MESSAGE['LOGIN_USERNAME_BLANK']; 
        if (empty($sPassword)) return $MESSAGE['LOGIN_PASSWORD_BLANK']; 
        
        // load user
        $oUser = new WbUser();
        $sbUserOk = $oUser->Load($uUser);
        // loading failed
        // Still we only return failed for an attacker to have no clue what went wrong.
        if ($sbUserOk)  {
            unset($oUser);
            return $MESSAGE['LOGIN_AUTHENTICATION_FAILED'];
        }
    
        // test for old  MD5 password
        if ($oUser->Password=md5($sPassword)) {
            // try to rehash 
            $oUser->Password=self::Hash($sPassword);
            //save user to db
            $oUser->Save();
           
            return $oUser;
        }
        // test for modern password
        if (function_exists('password_verify') AND password_verify ( $sPassword , $oUser->Password )){
            // try to rehash maybe some stronger Algo available 
            $oUser->Password=self::Hash($sPassword);
            //save user to db
            $oUser->Save();
            
            return $oUser;
        }
        
        unset($oUser);
        return $MESSAGE['LOGIN_AUTHENTICATION_FAILED'];
    }

    
    
/**
    @brief Encrypt/Hash a Password

    Uses the new password_hash function (PHP 5.5) if available . 
    Otherwise it uses the old md5 variant. So if you want secure passwords get PHP 5.5

    You can suppress modern hashing by defining WB_SUPPRESS_PHP55_HASH and setting it to true.

    @param string $sPassword The actual password to encrypt 
    @return string  The encrypted(hashed) Password 
*/
    public static function Hash ($sPassword){

        if ( defined("WB_SUPPRESS_PHP55_HASH") AND WB_SUPPRESS_PHP55_HASH===true ) {
            return md5($sPassword);
        } 

        if (!function_exists("password_hash")) {
            return md5($sPassword);
        }
        
        return password_hash($sPassword, PASSWORD_DEFAULT) ;
    }
    
    
    
/**
    @brief Generate a temporary password for a certain user 

        Uses the random password creator , GenerateRandomPassword() 

    @param undefined $uUser The user to generate a temp password for (You may use name, ID or email.)  
    @retval boolean/string Returns false on success, and an error message on failure.          
*/    
   public static function GenerateTempPassword ($uUser){
        $oUser = new WbUser();
        $sbUserOk = $oUser->LoadUser($uUser);
        if ( !$sbUserOk) return "Invalid User";
        
        $sPassword=self::GenerateRandomPassword();
        $oUser->Password=self::Hash($sPassword);
        //save user to db
        $oUser->Save();
        
        return false;     
    }
    
    
    
/**
    @brief Generates Human readable/memorizable  Password 

    at least they are far better than sl7jg.ior3ei:o 
    Examples:(WB_PW_BLOCKS=2)  
    ifog3-Enez5
    oguz2+xuna7
    woki9+hehi2
    Apan9+Adad3
    Sebi3-Godi6
    ihun1+dunu3

    Yes i know it is less secure but its still far better than most 
    passwords people would choose for themselfes.  
    If you want more secure ones simmply add an additional block.  
    faxu8-ebip3+Eded8  
*/
    public static function GenerateRandomPassword() {

        //validate Input
        if (defined('WB_PW_BLOCKS')) $iBlocks=WB_PW_BLOCKS;
        else $iBlocks=2;
        $iBlocks=(int)$iBlocks;
        if ($iBlocks < 1) $iBlocks=1;

        $sPassword="";

        //Set defaults
        if (defined('WB_PW_DIGITS')) $sDigits=WB_PW_DIGITS;
        else $sDigits='123456789';

        if (defined('WB_PW_CONSONANTS')) $sConsonants=WB_PW_CONSONANTS;
        else $sConsonants = 'bdfghkmnprstvwxzBDFGHKMNPRSTVWXZ';

        if (defined('WB_PW_VOWELS')) $sVowels=WB_PW_VOWELS;
        else $sVowels='aeiouAEIOU';

        if (defined('WB_PW_SEPARATORS')) $sSeparators=WB_PW_SEPARATORS;
        else $sSeparators = '+-';

        //Generate Arrays
        $aDigits = str_split($sDigits, 1);
        $aConsonants = str_split($sConsonants, 1);
        $aVowels = str_split($sVowels, 1);
        $aSeparators = str_split($sSeparators, 1);

        // create PW blocks
        for($i=0; $i<$iBlocks; $i++) {

        $aBlock = array();

            if (rand (0,1) !=0) {
                $aBlock[] = $aConsonants[array_rand($aConsonants)] . strtolower( $aVowels[array_rand($aVowels)]. $aConsonants[array_rand($aConsonants)] . $aVowels[array_rand($aVowels)]);
            } else {
                $aBlock[] = $aVowels[array_rand($aVowels)] . strtolower( $aConsonants[array_rand($aConsonants)]. $aVowels[array_rand($aVowels)] . $aConsonants[array_rand($aConsonants)]);
            }

            $aBlock[] = $aDigits[array_rand($aDigits)];

            //shuffle($aBlock);
            $aBlock[] = $aSeparators[array_rand($aSeparators)];
            $sPassword.= implode('', $aBlock);
        }


        $sPassword=substr($sPassword,0,-1);

        return $sPassword;
    }

//////////////////////////
// Helper functions
/////////////////////////
    
    
    /**
        @brief the username does not need html tags , is all lowercase and specialchars are convertet to entities
    */
    private function CleanUser($sUserName) {
       $sUserName= strip_tags($sUserName);
       $sUserName= htmlspecialchars($sUserName);
       $sUserName= strtolower($sUserName);  
    }
    
}

