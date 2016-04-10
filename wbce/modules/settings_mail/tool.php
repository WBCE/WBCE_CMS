<?php
/**
 * @category        modules
 * @package         SEO Settings
 * @author          WBCE Project
 * @copyright       Norbert Heimsath
 * @license         GPLv2 or any later 
 */

//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

/*
Info for module(tool) builders.
Already included here :
config.php
framework/initialize.php
framework/class.wb.php
framework/class.admin.php
framework/functions.php

Admin class is initialized($admin) and header printed.

Additional vars for this tool: 
$modulePath     Path to this module directory
$languagePath   Path to language files of this module
$returnToTools  Url to return to generic tools page
$returnUrl      Url for return link after saving AND for sending the form!
$doSave         Set true if form is send
$saveSettings   Set true if there are actual settings send
$saveDefault    Set true if default button was pressed
$toolDir        Plain tool directory name like "maintainance_mode"
$toolName       The name of the tool eg "Maintainance Mode"

For language vars please take a look in the language files.
Language files no longer need manual loading.

All other vars usually abailable in Admin pages schould be available here too.
Maybe you need to import them via global.

backend.js and backend.css are automatically loaded, 
manual loading is no longer required.
*/
 
 
//Fetch the default content for Template 
 
$query = "SELECT * FROM ".TABLE_PREFIX."search WHERE extra = ''";
$results = $database->query($query);



// Check if this is is no CSRF attack or timeout.
// but only check if form was send. Maybe we move this to tool too if all tools got secform
if($doSave) {
    if (!$admin->checkFTAN()) {
        //3rd param = false =>no auto footer, no exit.
        $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'],$returnUrl, false); 
    }
}

// Form send , ok lets see what to do
if($saveSettings) {
    $setError="";
    //I simply copied this stuff in as i am so tired of redoing this shit


    
    //WBMAILER_DEFAULT_SENDER_MAIL  (server_email)
    $value=$admin->get_post("server_email");
    if ($value){ 
        $value= (string)$value;
        if ($admin->validate_email($value)){

            $setError.=Settings::Set ("SERVER_EMAIL", $value);
            $setError.=Settings::Set ("WB_SERVER_EMAIL", $value);
        
            $setError.=Settings::Set ("WBMAILER_DEFAULT_SENDER_MAIL", $value);
            $setError.=Settings::Set ("WB_MAILER_DEFAULT_SENDER_MAIL", $value);     
        } else {
            $setError.=$MESSAGE['USERS_INVALID_EMAIL']. '<br /><strong>Email: '.htmlentities($_POST['server_email']).'</strong>'."<br />";
        }
    }
       
    // default_sendername
    $value=$admin->get_post("wbmailer_default_sendername");
    if ($value){
        $value= (string)$value;
        $value =preg_replace ("/[^\w\.\ ]/su","", $value) ;

        $setError.=Settings::Set ("WBMAILER_DEFAULT_SENDERNAME", $value);
        $setError.=Settings::Set ("WB_MAILER_DEFAULT_SENDERNAME", $value);     
    
    }   
    
    
    // Hostname/IP is next 
    $IpRegex = "/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/";
    $HostRegex = "/^(([a-zA-Z]|[a-zA-Z][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z]|[A-Za-z][A-Za-z0-9\-]*[A-Za-z0-9])$/";
    $value=$admin->get_post("wbmailer_smtp_host");
    if ($value and (preg_match($IpRegex, $value) or preg_match($HostRegex, $value))) {
        
        $setError.=Settings::Set ("WBMAILER_SMTP_HOST", $value);
        $setError.=Settings::Set ("WB_MAILER_SMTP_HOST", $value); 
        $hostOk=true;
    }   
       
    // smtp_username
    $value=$admin->get_post("wbmailer_smtp_username");
    $value=strip_tags($value); 
    $value= preg_replace("/[\n\r\t\f]/","",$value);
    if ($value){
        $setError.=Settings::Set ("WBMAILER_SMTP_USERNAME", $value);
        $setError.=Settings::Set ("WB_MAILER_SMTP_USERNAME", $value);  
        $userOk=true;
    }   
    
    // default_sendername
    $value=$admin->get_post("wbmailer_smtp_password");
    $value=strip_tags($value); 
    $value= preg_replace("/[\n\r\t\f]/","",$value);
    if ($value){
        $setError.=Settings::Set ("WBMAILER_SMTP_PASSWORD", $value);
        $setError.=Settings::Set ("WB_MAILER_SMTP_PASSWORD", $value);  
        $passOk=true;
    }   
    
    
    // wbmailer_routine smtp or php mail();
    $value=$admin->get_post("wbmailer_routine");
    if ($value=="smtp" ){ 
        if ($passOk and $userOk and $hostOk){
            $setError.=Settings::Set ("WBMAILER_ROUTINE", "smtp");
            $setError.=Settings::Set ("WB_MAILER_ROUTINE", "smtp");  
        } else {
                $setError.= $TEXT['REQUIRED'].' '.$TEXT['WBMAILER_SMTP_AUTH'].'<br /><strong>'.$MESSAGE['GENERIC_FILL_IN_ALL'].'</strong>';
        }    
    }   
    if ($value=="phpmail"){
        $setError.=Settings::Set ("WBMAILER_ROUTINE", "phpmail");
        $setError.=Settings::Set ("WB_MAILER_ROUTINE", "phpmail");     
    }   
    
    
    
    
    // END ACTION!! 
    // report success or failure
    Tool::Msg ($setError, $returnUrl );    

} else if ($saveDefault) {
    $setError="";

    $setError.=Settings::Set ("WBMAILER_DEFAULT_SENDERNAME", 'WBCE Mailer');
    $setError.=Settings::Set ("WB_MAILER_DEFAULT_SENDERNAME", 'WBCE Mailer');  
    
    $setError.=Settings::Set ("WBMAILER_SMTP_AUTH", 1);
    $setError.=Settings::Set ("WB_MAILER_SMTP_AUTH", 1);
    
    $setError.=Settings::Set ("WBMAILER_SMTP_HOST", "");
    $setError.=Settings::Set ("WB_MAILER_SMTP_HOST", "");
    
    $setError.=Settings::Set ("WBMAILER_SMTP_USERNAME", "");
    $setError.=Settings::Set ("WB_MAILER_SMTP_USERNAME", "");
    
    $setError.=Settings::Set ("WBMAILER_SMTP_PASSWORD", "");    
    $setError.=Settings::Set ("WB_MAILER_SMTP_PASSWORD", "");    
    
    $setError.=Settings::Set ("WBMAILER_ROUTINE", "phpmail");
    $setError.=Settings::Set ("WB_MAILER_ROUTINE","phpmail");
    
    $setError.=Settings::Set ("WBMAILER_DEFAULT_SENDER_MAIL", SERVER_EMAIL);
    $setError.=Settings::Set ("WB_MAILER_DEFAULT_SENDER_MAIL", SERVER_EMAIL);
    
           
    // report success or failure
    Tool::Msg ($setError, $returnUrl );

} else { 

    //This hapens if form is not send in any way 
    // We simply go for display the form 

    //Fetch form  content from DB
    include($modulePath."/includes/fetch_form_content.php");

    // we need to preload no values , as they all stored in constants
    include($this->GetTemplatePath("tool.tpl.php")); 
}

//////////////////////////////
// Helper functions down here 

function se_GetTemplatesArray(){
    
    global $database;
    $ret = array();
    
    $result = $database->query("SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'template' AND function != 'theme' ORDER BY name");
    if($result->numRows() > 0) {
        while($addon = $result->fetchRow())
        {
            $ret[]=$addon;   
        }
        return $ret;
    } 
    return false;
}

function se_TemplatePossible($Name=""){

    global $database;
    if ($Name=="") return true;
    if (empty ($Name)) return false;
    if (!preg_match("/^[a-z0-9\-\_]+$/is",$Name)) return false;
    
    $sql="SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'template' AND function != 'theme' AND directory ='$Name'";

    $results = $database->query($sql);
    if($results->numRows() > 0) return true;
    else                        return false;
}

function se_VisibilityPossible($value=""){
    if ($value=="public" or 
        $value=="private" or
        $value=="registered" or
        $value=="none"
        ) return true;
        
        return false;
}

