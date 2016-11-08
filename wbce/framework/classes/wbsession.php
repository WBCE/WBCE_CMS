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

    // Define the name for a Sub array to store all information , 
    // so we do not interferre whith other implemented scripts in the $_SESSION var.  
    public static $Store="WBCE";
    public static $StorePerm="WBCE_Perm";
   

    public static function  Start(){
    
        // WBCE always uses Cookies 
        ini_set('session.use_cookies', true);  # use session cookies
        
        
        // WB_SECFORM_TIMEOUT we use this for now later we get seperate settings 
        // Later we should get a nice session class instead of this improvised stuff.
        ini_set('session.gc_maxlifetime', intval(WB_SECFORM_TIMEOUT));
        //ini_set('session.cookie_lifetime', intval(WB_SECFORM_TIMEOUT));
        ini_set( 'session.cookie_httponly', 1 );
        
        // Secure Cookies if we use https
        if(WB_PROTOCOLL=="https"){ 
            ini_set( 'session.cookie_secure', 1 );
        }
        
    

        // Start a session if needed 
        if (!self::IsStarted()) {
            // Session parameter
            session_name(APP_NAME . '-sid');
            session_set_cookie_params(0);
            //session_set_cookie_params(WB_SECFORM_TIMEOUT);
        
            session_start();
            
            // this is used by only by installer in index.php and save.php we will remove this later
            define('SESSION_STARTED', true);
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

        // ASP Still expects old placement of Session Var
        /// @todo Change ASP to use WbSession::Get() then change setting session startet in Session class too.  
        if (!isset($_SESSION['session_started'])) 
            $_SESSION['session_started'] = time();

        return false;
    }

    public static function  test ($text=""){
        return $text;
    
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
        if (!self::IsStarted) return "no session running!";
        
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
        if (!self::IsStarted) return $Default;

        if (isset($_SESSION[self::$StorePerm][$sVar])) return $_SESSION[self::$StorePerm][$sVar];

        return $Default;
    }


/**
    @brief Simple method to test if a session is started, or not.
    
    As we want to store our Session stuff in an area where we do not collide whith other Software,
    this is much shorter and more easy to read than the direct call for the Variable. 
    
    @code
        if (self::IsStarted){}
        
        if ($_SESSION['WBCE']['SessionStarted']=true){}
    @endcode
    
    @return string/boolean  Session Start time of false. 
    
*/
    public static function  IsStarted(){
        // minimum PHP 5.4 we can do this 
        if (function_exists ( "session_status" ) AND session_status() == PHP_SESSION_NONE ) 
            return true;
    
        if (
            isset ($_SESSION) AND
            isset ($_SESSION[self::$Store]['SessionStarted']) AND    
            is_int($_SESSION[self::$Store]['SessionStarted'])
        )
            return true;
            
        //This one is for old Installer 
        if (defined('SESSION_STARTED'))
           return true; 
    
        return false;
    }
    
/**
     @brief Simple debug helper to show all session vars in an overview.

    Just call it self::Debug() and it will return an overview

    @return string Returns an html Overview of aall Session Vars 
*/    
    public static function  Debug(){    
        $aSession=array();
        $aPerm=array();
        $aGlobal=array();
        $sOut="";
       
        $aSession=$_SESSION[self::$Store];
        $aPerm=$_SESSION[self::$StorePerm];
        $aGlobal=$_SESSION;
        unset ($aGlobal[self::$Store]);
        unset ($aGlobal[self::$StorePerm]);
        
        
        if (!empty($aSession)){
            $sOut="<h3>WBCE Session</h3>";
            foreach ($aSession as $sKey=>$uValue){
                $sOut="$sKey: $uValue<br>\n";
            }
        }
        if (!empty($aPerm)){
            $sOut="<h3>WBCE Permanent Session</h3>";
            foreach ($aPerm as $sKey=>$uValue){
                $sOut="$sKey: $uValue<br>\n";
            }
        }
        if (!empty($aGlobal)){
            $sOut="<h3>Global session vars</h3>";
            foreach ($aGlobal as $sKey=>$uValue){
                $sOut="$sKey: $uValue<br>\n";
            }
        }
    }
    

}

