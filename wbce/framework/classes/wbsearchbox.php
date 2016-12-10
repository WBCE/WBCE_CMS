<?php
/**
    @file 
    @brief Small Class for displaying a templated Search box 
    
    This file should be found in the Classes folder of the core. 
    
    It relies on searchbox.tpl.php in the systemplates folder.  
*/
/**
    @brief Small Class for displaying a templated Search box
    
    I was extremely tired of the complicated ways i had to use to display loginboxes and seachboxes 
    in WBCE templates. there where a few snippets for this but they where not templateable at all, 
    depended on TWIG , did not match all my needs....
    
    So here we got a new Searchbox whith enough Divs to get a decent styling, an option to override 
    the whole design in your Template , options to override almost everything and you may fetch the 
    result instead of echo it immediately. 
    
    The Basic call usually looks like this: 
    @code
        WbSearchBox::Show();
    @endcode
    You may supply an options array to this Static method.  
    @code
        WbSearchBox::Show(array(
            "sHeadline"   => "Search here:",
            "sSendText"   => "GO"
            "sFormMethod" => "post"     
        ));
    @endcode
    Or you may use the OOP variant: 
    @code
        $SB= new WbSearchBox()
        $SB->sHeadline    = "Search here:",
        $SB->sSendText    =  "GO"
        $SB->sFormMethod  = "post" 
        $SB->Display();
    @endcode
    The Display Method allows for returning the value instead of echo it directly
    @code
        $sMySearchBoxHtml=$SB->Display(false);
    @endcode    
    In addition it is possible to hand an options array directly to the constructor 
    @code
        $aOptions= array(
            "sHeadline"   => "Search here:",
            "sSendText"   => "GO"
            "sFormMethod" => "post"     
        ));
    
        $SB= new WbSearchBox($aOptions)
        $SB->Display();
    @endcode
    
    __Templates__
    
    The Template for this Class is stored in: @n 
        /templates/systemplates/searchbox.tpl.php
        
    To override it whith your own create the folder /systemplates/ in your template folder and copy the file 
    "searchbox.tpl.php" from the default folder to your folder. Change the file to match your needs. 
    The class will override the system file automatic. 
 
@todo extend this documanteation     
*/

class WbSearchBox {
    public $sHeadline="";
    public $sText=" ";
    public $sAltText=" ";
    public $sTitleText=" ";
    public $sIcon=""; // the full icon , like <i class="fa fa-shopping-cart"></i>
    public $sImageUrl="";
    public $sBoxClass="searchbox"; // possibly place multiple fearch fields on a page
    public $sInputValue="";
    public $sSendText=" ";
    public $sDeactivated="<!-- Search is deactivated in backend -->";
    public $sReferer="";
    public $sFormAction="";
    public $sFormMethod="get";
    public $sCustomTemplatePath="";


    
    public function __construct ($aSettings=false){

        // fetching necessary globals
        global $TEXT;
        
        // Setting Default Values that can not be set in Variable list
        $this->sText     = $TEXT['SEARCH'];
        $this->sSendText = $TEXT['SEARCH'];
        $this->sReferer  = defined('REFERRER_ID') ? REFERRER_ID : PAGE_ID;
        $this->sFormAction = WB_URL."/search/index.php";
        
        // We got supplied a settings Array ?
        if (!empty($aSettings) AND is_array($aSettings)) {
            // Set the Settings to this instance
            foreach ($aSettings as $uKey => $uValue) {
                if (is_string($uKey)) {
                     $this->$uKey=$uValue;
                }
            }
        }

    }
    
    public  function Display($echo=true){
        
        // We don't want to display the content 
        // We want it as a return value  
        if ($echo===false) {
            ob_start();     
            $this->Display();
            $out = ob_get_contents();
            ob_end_clean();
            return $out; 
        }

        // Ups Deactivated
        if (!SHOW_SEARCH)  {
           echo  $this->$sDeactivated; 
           return;
        }
        
        // Import Class Vars to this Namespace vor easy templating
        $aVars=(array)$this;
        foreach ($aVars as $sVar=> $uValue){
            $$sVar=$uValue;
        } 
    
        // finally include Template 
        if (!empty($this->sCustomTemplatePath)) 
            require ($this->sCustomTemplatePath);
        else
            require (wb_systemplate_override("searchbox.tpl.php"));
        
    } 
    
 
    public static  function Show ($aSettings=false){
        $SB = new WbSearchBox($aSettings);
        $SB->Display();
        unset($SB);
    }
}

