<?php
/**
@file
@brief This file contains the new class for handling users
@author Norbert Heimsath for the WBCE Project
@copyright http://www.gnu.org/licenses/lgpl.html   (GNU LGPLv2 or any later) 
@version 1.0.0-alpha.1 

For more information look into the class documentation for class wbuser. 
*/


/**
@brief This class tries to handle all user releated tasks. 

    This includes loading of userdata , manipulating and storing 

@todo We need a method to add and remove groups and group as groups is a comma seperated list. 
@todo We possibly need methods that handle setting of timezones timeformats and so on 
@todo Add a method to add the user to the recent session.  
@todo Somehow we need to handle permissions as well 
*/

class WbUser {

    private $Database="";
    
    public $UserId=NULL;
    public $GroupId=NULL;
    public $GroupIds="";
    public $Active=0;
    public $UserName="";
    public $Password="";
    public $RememberKey="";
    public $LastReset=0;
    public $DisplayName="";
    public $Email="";
    public $TimeZone="-72000";
    public $DateFormat="";
    public $TimeFormat="";
    public $Language="";
    public $HomeFolder="";
    public $LoginWhen=0;
    public $LoginIp="0.0.0.0";
    
    
    public $SystemPermissions = array();
    public $ModulePermissions = array();
    public $TemplatePermissions = array();
    public $GroupName = array();
    
/**
    @brief Load user if set , load Database handle into class. 

    @param undefined $User
        The user identifyer, either its username or its user id. 

*/       
    public function __construct($User=""){
        global $database;
    
        // fetch database handle
        $this->Database=$database;
        
        // If an user is set, load him 
        if (!empty($User)) 
            $this->Load($User);   
    } 
    
/**
    @brief Load user , autodetect if id or name is given.

    @param undefined $User
        The user identifyer, either its username or its user id. 
        
    @retval boolean/string
            Returns the retval of the called function, and an error message on failure.      
*/       
    public function Load ($User="") {
        
        if (!empty($User)){
        
            if (is_integer($User)) 
                return $this->LoadUserById($User);
            
            if (is_string($User) AND preg_match("/@/s", $User)) 
                return $this->LoadUserByEmail($User);
                
            if (is_string($User))
                return $this->LoadUserByName($User);
        } 
        
        return "LoadUser: Invalid vartype for user, only string and integer allowed given". gettype ($User)."/n" ; 
    }

/**
    @brief Load user by its username

    @param string $UserName
        The users username

*/       
    public function LoadUserByName ($UserName="") {
    
        $sql  = "SELECT * FROM " . WB_TABLE_PREFIX . "users ";
        $sql .= "WHERE username='" . $UserName . "' AND active=1";
         
        $Results = $this->Database->query($sql);
        $NumRows = $Results->numRows();
        
        if ($NumRows ==1) 
            return $this->FetchUserResult($Results); 
        
        if ($NumRows >1)
            return "LoadUserByName: Multiple results ($NumRows) for username/n";

        if ($NumRows <1)
            return "LoadUserByName: Username not found./n";
         
    }
    

     
/**
@brief Load user by its user id.

@param string $UserId
    The users id
*/       
    public function LoadUserById ($UserId="") {
    
        $sql  = "SELECT * FROM " . WB_TABLE_PREFIX . "users ";
        $sql .= "WHERE user_id='" . $UserId . "' AND active=1";
         
        $Results = $this->Database->query($sql);
        $NumRows = $Results->numRows();
        
        if ($NumRows ==1) 
            return $this->FetchUserResult($Results); 
        
        if ($NumRows !=1)
            return "LoadUserByID: ID not found/n";        
    }
    
    

/**
@brief Load user by its email

@param string $UserEmail
    The users email
*/       
    public function LoadUserByEmail ($UserMail="") {
    
         if (defined(!("WB_ALLOW_EMAIL_AUTH") AND WB_ALLOW_EMAIL_AUTH==true))
            return "Authentication via email not allowed, check/set WB_ALLOW_EMAIL_AUTH";
         
        $sql  = "SELECT * FROM " . WB_TABLE_PREFIX . "users ";
        $sql .= "WHERE email='" . $UserMail . "' AND active=1";
        
        $Results = $this->Database->query($sql);
        $NumRows = $Results->numRows();
        
        if ($NumRows ==1) 
            return $this->FetchUserResult($Results); 
        
        if ($NumRows >1)
            return "LoadUserByEmail: Multiple results ($NumRows) for username/n";

        if ($NumRows <1)
            return "LoadUserByEmail: Username not found./n"; 
         
        
    }

    
    
/**
    @brief Sorts the user result into this object data structure.  

    @param handle $Result
        The result from sthe user Query
        
    @retval boolean/string
        Returns the retval of the called function, and an error message on failure.          
*/
    private function   FetchUserResult($Result){
        // resultat holen
        $Row = $Result->fetchRow();
       //echo "<pre>"; var_dump($Row); echo "</pre>";
        $this->UserId=$Row['user_id'];
        $this->GroupId=$Row['group_id'];
        $this->GroupIds=$Row['groups_id'];
        $this->Active=$Row['active'];
        $this->UserName=$Row['username'];
        $this->Password=$Row['password'];
        $this->RememberKey=$Row['remember_key'];
        $this->LastReset=$Row['last_reset'];
        $this->DisplayName=$Row['display_name'];
        $this->Email=$Row['email'];
        $this->TimeZone=$Row['timezone'];
        $this->DateFormat=$Row['date_format'];
        $this->TimeFormat=$Row['time_format'];
        $this->Language=$Row['language'];
        $this->HomeFolder=$Row['home_folder'];
        $this->LoginWhen=$Row['login_when'];
        $this->LoginIp=$Row['login_ip'];

        return false;
    }   
    
    
/**
    @brief Store relevant userdata to the session. 
        
    @retval boolean/string
        Returns false on success, and an error message on failure.          
*/
    public function  UserToSession(){
    
        if ($this->UserId===NULL)
            return "UserToSession: User has no ID cannot store to session.";
        if ($this->GroupId===NULL OR $this->GroupIds=="") 
            return "UserToSession: You need to set a group before saving.";
            
            
        // Noch kleine Gruppen infos geholt .. 
        if (empty ($this->SystemPermissions)) 
            $this->FetchGroupInfo(); 
    
        $_SESSION['USER_ID'] = $this->UserId;
        $_SESSION['GROUP_ID'] = $this->GroupId;
        $_SESSION['GROUPS_ID'] = $this->GroupIds;
        $_SESSION['USERNAME'] = $this->UserName;
        $_SESSION['DISPLAY_NAME'] = $this->DisplayName;
        $_SESSION['EMAIL'] = $this->Email;
        $_SESSION['HOME_FOLDER'] = $this->HomeFolder;
           
        // Set language
        if ($this->Language != '') {
            $_SESSION['LANGUAGE'] = $this->Language;
        }
        // Set timezone
        if ($this->TimeZone != '-72000') {
            $_SESSION['TIMEZONE'] = $this->TimeZone;
        } else {
            // Set a session var so apps can tell user is using default tz
            $_SESSION['USE_DEFAULT_TIMEZONE'] = true;
        }
        // Set date format
        if ($this->DateFormat != '') {
            $_SESSION['DATE_FORMAT'] = $this->DateFormat;
        } else {
            // Set a session var so apps can tell user is using default date format
            $_SESSION['USE_DEFAULT_DATE_FORMAT'] = true;
        }
        // Set time format
        if ($this->TimeFormat != '') {
            $_SESSION['TIME_FORMAT'] = $this->TimeFormat;
        } else {
            // Set a session var so apps can tell user is using default time format
            $_SESSION['USE_DEFAULT_TIME_FORMAT'] = true;
        }

        // Set permission stuff 
        $_SESSION['SYSTEM_PERMISSIONS'] = array();
        $_SESSION['MODULE_PERMISSIONS'] = array();
        $_SESSION['TEMPLATE_PERMISSIONS'] = array();
        $_SESSION['GROUP_NAME'] = array();

    
    
    }




/**
    @brief Store the user to the Database. 
        
    @retval boolean/string
        Returns false on success, and an error message on failure.          
*/
    public function  Store(){
        
        if ($this->GroupId===NULL OR $this->GroupIds=="") 
            return "StoreUser: You need to set a group before saving";
        
        //Delete entry if we  already find him 
        if ($this->UserId!==NULL) {
            $sql="DELETE FROM `usr_web207_4`.`wbce00users` WHERE `wbce00users`.`user_id` = {$this->UserId}";
        }
        
        // do query and return array if we encounter some trouble 
        $this->Database->query($sql); 
        if ($this->Database->is_error()) return "StoreUser: ".$this->Database->get_error(); 
        
        
        // now insert again 
        $sql  = "INSERT INTO `" . WB_TABLE_PREFIX . "users` ";
        $sql .= " 
                  ( `user_id`,         `group_id`,         `groups_id`,         `active`,          `username`,          `password`,          `remember_key`,         `last_reset`,         `display_name`,         `email`,          `timezone`,          `date_format`,         `time_format`,         `language`,          `home_folder`,         `login_when`,         `login_ip`)
            VALUES ('{$this->UserId}', '{$this->GroupId}', '{$this->GroupIds}', '{$this->Active}', '{$this->UserName}', '{$this->Password}', '{$this->RememberKey}', '{$this->LastReset}', '{$this->DisplayName}', '{$this->Email}', '{$this->TimeZone}', '{$this->DateFormat}', '{$this->TimeFormat}', '{$this->Language}', '{$this->HomeFolder}', '{$this->LoginWhen}','{$this->LoginIp}')    
        ";
        
       // echo "$sql<br>";
       // do query and return array if we encounter some trouble 
       $this->Database->query($sql); 
       if ($this->Database->is_error()) return "StoreUser: ".$this->Database->get_error(); 
       
       // Set uid if this was a new entry so object remain consistent
       if ($this->UserId!==NULL) 
            $this->UserId = $this->Database->getLastInsertId();
       
       return false;
    }
    
/**
    @brief Fetches all relevant permission data and Groups information

    @todo I am not sure if Array intersect is ok for permissions as it only returns intersectiog elements 
        usually i think it schould add up all permissions but i am not so sure right now.   
*/
    public function FetchGroupInfo(){
         if ($this->GroupId===NULL OR $this->GroupIds=="") 
            return "FetchGroupInfo: Please load or setup a user before. There are no Groups set";

        $first_group = true;
        foreach (explode(",", $this->GroupIds) as $cur_group_id) {
            $sql = 'SELECT * FROM `' . WB_TABLE_PREFIX . 'groups` WHERE `group_id`=\'' . $cur_group_id . '\'';
            $results = $this->Database->query($sql);
            $results_array = $results->fetchRow();
            $this->GroupName[$cur_group_id] = $results_array['name'];
            // Set system permissions
            if ($results_array['system_permissions'] != '') {
                $this->SystemPermissions = array_merge($this->SystemPermissions, explode(',', $results_array['system_permissions']));
            }
            // Set module permissions
            if ($results_array['module_permissions'] != '') {
                if ($first_group) {
                    $this->ModulePermissions = explode(',', $results_array['module_permissions']);
                } else {
                    $this->ModulePermissions = array_intersect($this->ModulePermissions, explode(',', $results_array['module_permissions']));
                }
            }
            // Set template permissions
            if ($results_array['template_permissions'] != '') {
                if ($first_group) {
                    $this->TemplatePermissions = explode(',', $results_array['template_permissions']);
                } else {
                    $this->TemplatePermissions = array_intersect($this->TemplatePermissions, explode(',', $results_array['template_permissions']));
                }
            }
            $first_group = false;
        } 
    }    
    

} 

