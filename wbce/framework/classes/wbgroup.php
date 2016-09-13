<?php







class WbGroup{

    private $Database="";

    public $iId=NULL;
    public $sName="";
    
    public $sSystemPermissions="";
    public $sModulePermissions="";
    public $sTemplatePermissions="";
    

    public function __construct($iGroup=0){
        global $database;

        // fetch database handle into class
        $this->Database=$database;
        
        // secure validation
        $iGroup=(int)$iGroup;
        
        // If a group is set, load it 
        if ($iGroup !=0) 
            $this->Load($iGroup);   
    } 
    
    

    public function Load ($iGroupId=false){
        
        global $TEXT,$MESSAGE
        
        // if we dont ave an Id set whith the functioncall look if we got an ID in the class. 
        if ($iGroupId===false and $this->iId!==NULL) $iGroupId=$this->iId;
        
        // still nothing selected 
        if (!$iGroupId) return "WbGroup::Load: {$MESSAGE['USERS_NO_GROUP']}";
        
        // DB security :-)
        $iGroupId=(int)$iGroupId;
    
        $sql  = "SELECT * FROM " . WB_TABLE_PREFIX . "groups ";
        $sql .= "WHERE group_id='" . $iGroupId . "' ";
         
        $hResult = $this->Database->query($sql);
        $NumRows = $hResult->numRows();
        
        if ($NumRows ==1) 
            return $this->FetchResult($hResult); 
        
        if ($NumRows !=1)
            return "WbGroup::Load: $iGroupId {$TEXT['NOT_FOUND']}";        
    
    }

    public function Save (){
    
        global $MESSAGE
    
        if ($this->sName=="") 
            return "WbGroup::Save: {$MESSAGE['GROUPS_GROUP_NAME_BLANK']}";
        
        // already existing group ??
        if ($this->iId!==NULL) {
            $this->iId=(int)$this->iId;
            $sql="DELETE FROM `" . WB_TABLE_PREFIX . "groups` WHERE `group_id` = {$this->iId}";
            
            // do query and return array if we encounter some trouble 
            $this->Database->query($sql); 
            if ($this->Database->is_error()) return "WbGroup::Save : ".$this->Database->get_error(); 
        }
        // going to create a new group 
        else {
            $count = 0;
            
            $sql="SELECT COUNT(name) FROM `" . WB_TABLE_PREFIX . "groups` WHERE `name` = {$this->iName}";
            $count = $count + $database->get_one($sql);

            if ($count > 0) return "WbGroup::Save : ".$MESSAGE['GROUPS_GROUP_NAME_EXISTS'];   
        }
      
        // now insert again 
        $sql  = "INSERT INTO `" . WB_TABLE_PREFIX . "groups` ";
        $sql .= " 
                   ( `group_id`,     `name`,          `system_permissions`,          `module_permissions`,          `template_permissions`     )
            VALUES ('{$this->iId}', '{$this->Name}', '{$this->sSystemPermissions}', '{$this->sModulePermissions}', '{$this->sTemplatePermissions}' )    
        ";
        
       //echo "$sql<br>";
       // do query and return error if we encounter some trouble 
       $this->Database->query($sql); 
       if ($this->Database->is_error()) return "WbGroup::Save: ".$this->Database->get_error(); 
       
       // Set uid if this was a new entry so object remain consistent
       if ($this->UserId!==NULL) 
            $this->UserId = $this->Database->getLastInsertId();
       
       return false;
    } 
    
    public function AddPermission ($sPermission, $sType){
        // just a little Error corection
        $sType= ucfirst(trim($sType));        
        $sType= "s{$sType}Permissions";
        if (empty($this->$sType ) ) {
            $this->$sType=$sPermission;
            return false;
        }
        
        $aPermissions=explode(",", $this->$sType);
        
        // Add the element
        $aPermissions[]=$sPermission;
        
        $this->$sType=implode(",", $aPermissions); 
        return false;
    } 
    
    public function DelPermission ($sPermission, $sType){
        $sType= "s{$sType}Permissions";
        if (empty($this->$sType ) ) {
            // All empty, all ok! 
            return false;
        }
        
        $aPermissions=explode(",", $this->$sType);
        
        // Remove the element
        $aPermissions = array_diff($aPermissions, array($sPermission));
    
        $this->$sType=implode(",", $aPermissions); 
        return false;
    } 

    public function List ($bNoAdmin=true) {
        $aGroupList=array();
        
        $sql  = "SELECT group_id,name FROM `" . WB_TABLE_PREFIX . "groups` ";
        if  ($bNoAdmin) $sql .= " WHERE group_id !='1' ";
        $hResult=$this->Database->query($sql);
        $iNumRows = $hResult->numRows();
        
        // found some Groups
        if ($iNumRows > 0) {
            while ($aRow=$hResult->fetch_assoc()){
                $aGroupList[$aRow['group_id']]= $aRow['name'];
            }
        }
        return $aGroupList;
    }
    
    public function Delete ($iGroupId=false) {
        global $MESSAGE;
        
        // if we dont ave an Id set whith the functioncall look if we got an ID in the class. 
        if ($iGroupId===false and $this->iId!==NULL) $iGroupId=$this->iId;
        
        $iGroupId=(int)$iGroupId;
        
        // Still nothing set or set 0 
        if (!$iGroupId) return "WbGroup::Delete: No GroupID set!\n";
        
        // count group members
        $count = 0;
        
        // the check for group_id field is only for old broken entries
        $sql = 'SELECT COUNT(*) FROM `' . TABLE_PREFIX . 'users` WHERE groups_id ="' . $iGroupId . '"
                                                                      OR group_id ="' . $iGroupId . '"
                                                                      OR groups_id LIKE "' . $iGroupId . ',%"
                                                                      OR groups_id LIKE "%,' . $iGroupId . '"
                                                                      OR groups_id LIKE "%,' . $iGroupId . ',%"  ';
        // we got more than one , thats bad
        $count = $count + $database->get_one($sql);
        if ($count > 0) {
            return "WbGroup::Delete: {$MESSAGE['GROUP_HAS_MEMBERS']}\n";
        }
        
        
        $sql="DELETE FROM `" . WB_TABLE_PREFIX . "groups` WHERE `group_id` = {$iGroupId}";
       
        // do query and return error if we encounter some trouble 
        $this->Database->query($sql); 
        if ($this->Database->is_error()) return "WbGroup::Delete, DB error : ".$this->Database->get_error();
        
        return false;
    }
    
    /**
    @brief Sorts the Group result into this object data structure.  

    @param handle $Result
        The result from the user Query
        
    @retval boolean/string
        Returns the retval of the called function, and an error message on failure.          
*/
    private function   FetchResult($hResult){
        // resultat holen
        $oRow = $hResult->fetchRow();
       //echo "<pre>"; var_dump($Row); echo "</pre>";
        $this->iId=$oRow['group_id'];
        $this->sName=$oRow['name'];
        $this->sSystemPermissions=$oRow['system_permissions'];
        $this->sModulePermissions=$oRow['module_permissions'];
        $this->sTemplatePermissions=$oRow['template_permissions'];
      
        return false;
    }   
    
    
}







 
