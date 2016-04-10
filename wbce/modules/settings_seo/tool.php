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


    // ONLY HERE THE ACTUAL ACTION IS GOING ON!!    
    $setError="";
  
    
    // WEBSITE_TITLE
    $value=$admin->get_post("website_title");   

    if ($value){ 
        $value=strip_tags ($value);
        $setError.=Settings::Set ("website_title", $value);
        $setError.=Settings::Set ("wb_default_title", $value);                 
    }

    $value=$admin->get_post("website_description");    
    if ($value){ 
        $value=strip_tags ($value);
        $setError.=Settings::Set ("website_description", $value);
        $setError.=Settings::Set ("wb_default_description", $value);                 
    }
    $value=$admin->get_post("website_keywords");    
    if ($value){ 
        $value=strip_tags ($value);
        $setError.=Settings::Set ("website_keywords", $value);
        $setError.=Settings::Set ("wb_default_keywords", $value);                 
    }    
    
    // We cannot do much validation here if someon got access to this settings
    // he can add dangerous stuff. HTML purifyer would be a possible option 
    // but he is only (x)html4 compilant.
    // the really bad thing is that some malicious browser plugins tend to add stuff here
    // At lease we remove Script tags and on...whatever= events
    $value=$admin->get_post("website_header");    
    if ($value){ 
        // at least some protection
        $value=preg_replace ("/<\s*\/?\s*script.*>/iusU"," !!Script Tag Not allowed!! ", $value); //garbles the output , but no script.

        $value=preg_replace ("/on[a-zA-Z0-9]+\s*\=/iusU"," !!removed!!=", $value); // replaces all onclick onmouseover.....
        
        $setError.=Settings::Set ("website_header", $value);
        $setError.=Settings::Set ("wb_site_header", $value);                 
    }
    
    $value=$admin->get_post("website_footer");    
    if ($value){ 
        // at least some protection
        $value=preg_replace ("/<\s*\/?\s*script.*>/iusU"," !!Script Tag Not allowed!! ", $value); //garbles the output , but no script.

        $value=preg_replace ("/on[a-zA-Z0-9]+\s*\=/iusU"," !!removed!!=", $value); // replaces all onclick onmouseover.....
 
        $setError.=Settings::Set ("website_footer", $value);
        $setError.=Settings::Set ("wb_site_footer", $value);                 
    }    
    
    // END ACTION!! 

    // report success or failure
    Tool::Msg ($setError, $returnUrl );
    

} else if ($saveDefault) {
    $setError="";
    
    $setError.=Settings::Set ("website_title", "Enter your website title");
    $setError.=Settings::Set ("wb_default_title", "Enter your website title");
    
    $setError.=Settings::Set ("website_description", " ");
    $setError.=Settings::Set ("wb_default_description", " ");
    
    $setError.=Settings::Set ("website_keywords", " ");
    $setError.=Settings::Set ("wb_default_keywords", " ");
    
    $setError.=Settings::Set ("website_header", "This is the website header.");
    $setError.=Settings::Set ("wb_site_header", "This is the website header.");
    
    $setError.=Settings::Set ("website_footer", "This is the website footer.");
    $setError.=Settings::Set ("wb_site_footer", "This is the website footer.");
    
 
    
    // report success or failure
    Tool::Msg ($setError, $returnUrl );

} else { 

    // we need to preload no values , as they all stored in constants

    include($this->GetTemplatePath("tool.tpl.php")); 
}

//////////////////////////////
// Helper functions down here 

