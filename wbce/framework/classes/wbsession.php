<?php  
/**
@file
@brief Improvised Class to allow override of session functions by Modules

This is just an improvised pseudo class that is made to be easyly replaced by Replacement modules. 

*/


/**
@brief Improvised Class to allow override of session functions by Modules

This is just an improvised pseudo class that is made to be easyly replaced by Replacement modules. 
I decided to use a static class as all this functionality should be available everywhere in the code 
whithout any implementation stuff. 

Why a new Session class ?

- If you use get/set methods , the vars are stored in a subarray that does not collide whith other implemented software products
- Modules can override the default behavior by registering an override class whith a single file direct registration in the autoloader.
- Extra functionality like permanent arrays that stay even after Logout .

*/
class WbSession{

    // Sub array to store all information , so we do not interferre whith other implemented scripts.  
    private static $Store="WBCE";
    private static $StorePerm="WBCE_Perm"  ;
   

    public static function  Start(){
    
        ini_set('session.use_cookies', true);  # use session cookies
    
    
        // WB_SECFORM_TIMEOUT we use this for now later we get seperate settings 
        // Later we should get a nice session class instead of this improvised stuff.
        ini_set('session.gc_maxlifetime', intval(WB_SECFORM_TIMEOUT));
        //ini_set('session.cookie_lifetime', intval(WB_SECFORM_TIMEOUT));
        ini_set( 'session.cookie_httponly', 1 );
        if(WB_PROTOCOLL=="https"){ 
            ini_set( 'session.cookie_secure', 1 );
        }
        session_name(APP_NAME . '-sid');
        session_set_cookie_params(0);
        // this was commented out, because cookie is not refreshed on every pageload(silly php) , so 
        // we always timed out WB_SECFORM_TIMEOUT seconds after first page load ... 
        //session_set_cookie_params(WB_SECFORM_TIMEOUT);
        
        
        // Start a session
        if (!self::IsStarted()) {
            session_start();
            
            // this is used by only by installer in index.php and save.php we will remove this later
            if (!defined('SESSION_STARTED')) define('SESSION_STARTED', true);
            
            // New way for check if session exists uses this Var
            self::Set("SessionStarted", time());
        } 
    
    
        // make sure session never exeeds lifetime
        /**
        //That will set the session cookie with a fresh ttl.
        setcookie( ini_get("session.name"), session_id(),
        time()+ini_get("session.cookie_lifetime"),
        ini_get("session.cookie_path"),
        ini_get("session.cookie_domain"),
        ini_get("session.cookie_secure"),
        ini_get("session.cookie_httponly"));
        */


        $now=time();
        //echo "Now: $now <br>";
        //echo "discard_after:".$_SESSION['WB']['discard_after']."<br>";
        //echo "Secform timeout:".WB_SECFORM_TIMEOUT."<br>";
        if (self::Get('discard_after') && $now > self::Get('discard_after')) {
            // this session has worn out its welcome; kill it and start a brand new one
            self::ReInit();
            echo "Session Time Run out , killing session";
        }
        self::Set('discard_after', $now + WB_SECFORM_TIMEOUT);
        //echo "discard_after2:".$_SESSION['WB']['discard_after']."<br>";


        if (defined('ENABLED_ASP') && ENABLED_ASP && !isset($_SESSION['session_started'])) {
            $_SESSION['session_started'] = time();
        }
   
        return false;
    }

    
    public static function  ReStart($Kill=false){
    
        //delete all session variables
        $_SESSION = array();
        session_unset ();
    
        // Kill the cookie
        if (isset($_COOKIE[session_name()])) {setcookie(session_name(), '', 0, '/');}
        
        #destroy the session
        session_destroy();
    
        #if kill is set, end script here.
        if ($Kill) {die('Scrip and session ended by function ReStart($Kill=true) ');}
   
        # restarting
        self::Start(true);
        self::RegenerateId(true);
   
    }
    
 /**
    @brief Logs the recent user out, but keeps permanent data.  
    
    As all login stuff is only session based, this is the place to logout.
    This saves the permanent array before deleting session data, and restores 
    it after session is restarted. 
*/       
    
     public static function  ReInit(){
        if (!WBSession::IsStarted) return "no session running!";
        
        // save permanent Data
        $SavePerm=array();
        if (isset($_SESSION[self::$StorePerm])){
            $SavePerm=$_SESSION[self::$StorePerm];
        }
        
        //delete all session variables
        session_unset ();
    
        # the true parameter let the function delete the old session file
        self::RegenerateId(true);
        
        // reset Session Started
        self::Set("SessionStarted", time());
        
        // regenerate permanent storage
        $_SESSION[self::$StorePerm]=$SavePerm;
        
        return false;
    }
   

/**
    @brief does the same as session_regenerate_id ()
    
    For now this is only for completeness

*/
    public static function  RegenerateId($delete_old_session = false){
        session_regenerate_id ($delete_old_session);
    }
    
/**
    @brief Logs the recent user out, but keeps permanent data.  
    
    As all login stuff is only session based, this is the place to logout.
    This saves the permanent array before deleting session data, and restores 
    it after session is restarted. 
    
    this is only a placeholder for ReInit()
*/    
    public static function  Logout(){
        self::ReInit();   
    }

/**
    @brief Sets a value in the normal Session save space. 
*/
    public static function  Set($sVar="", $Value=""){

        if (empty($sVar)) return "No variable name set..!";
        if (!self::IsStarted()) return "no session running!";

        $_SESSION[self::$Store][$sVar]=$Value;

    }

/**
    @brief Sets a value in the permanent Session save space.
    
    e.g. Username can be stored here 
*/
    public static function  SetPerm($sVar="", $Value=""){

        if (empty($sVar)) return "No variable name set..!";
        if (!self::IsStarted()) return "no session running!";
        
        $_SESSION[self::$StorePerm][$sVar]=$Value;
    }


/**
    @brief Gets a value in the normal Session save space.
*/
    public static function  Get($sVar="",$Default=false){

        if (empty($sVar)) return $Default;
        if (!self::IsStarted()) return $Default;

        if (isset($_SESSION[self::$Store][$sVar])) return $_SESSION[self::$Store][$sVar];

        return $Default;
    }

/**
    @brief Gets a value in the permanent Session save space.
*/
    public static function  GetPerm($sVar="",$Default=false){

        if (empty($sVar)) return $Default;
        if (!WBSession::IsStarted) return $Default;

        if (isset($_SESSION[self::$StorePerm][$sVar])) return $_SESSION[self::$StorePerm][$sVar];

        return $Default;
    }


/**
    @brief Simple method to test if a session is started, or not.
    
    As we want to store our Session stuff in an area where we do not collide whith other Software,
    this is much shorter and more easy to read than the direct call for the Variable. 
    
    @code
        if (WbSession::IsStarted){}
        
        if ($_SESSION['WBCE']['SessionStarted']=true){}
    @endcode
    
    Returns Session Start time of false 
    
*/
    public static function  IsStarted(){
        if (
            isset ($_SESSION[self::$Store]['SessionStarted']) AND    
            is_int($_SESSION[self::$Store]['SessionStarted'])
        ) 
            return $_SESSION[self::$Store]['SessionStarted'];
        else 
            return false;
    }

}