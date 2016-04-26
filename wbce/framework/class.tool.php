<?php
//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

class Tool {

    public $toolDir        = false;        // Directory of the tool and unique identifier  
    public $toolType       = "tool";       // type of the tool "tool", "setting", "backend"
    public $toolFile       = "tool.php";    // the file to be called in single tool link
    public $toolRetFile    = "index.php" ;  // the file used in the retunrn to list link
    public $toolLister     = false;
    public $typeDir        = "/admintools";// type directory of the tool "/admintools", "/settings", "".
    
    public $doSave         = false;        // User pressed the save button
    public $returnToTools  = "";           // return path too admintools, settings or mainpage.
    public $modulePath     = "";           // Path to the module dir 
    public $languagePath   = "";           // Path to the modules language Directory
    public $returnUrl      = "";           // Url to send forms to the tool itself 
    public $moduleUrl      = "";           // URL to module main path (for accessing other files than tool.php)
    public $saveSettings   = false;        // Indicator if standard save button has been pressed 
    public $saveDefault    = false;        // Indicator if standard default button has been pressed 
    public $noPage         = false;        // Suppress displaying of pagetemplate
    
    private $admin         = false; 
    public $adminAccess   = "admintools"; // second parameter for calling the Admin Class, controlls access rights
    public $adminSection  = ""; // first parameter for calling the Admin Class, Section name.
    
    
    // info from info.php
    public $module_directory   = '';
    public $module_name        = '';
    public $module_function    = '';
    public $module_version     = '';
    public $module_platform    = '';
    public $module_author      = '';
    public $module_license     = '';
    public $module_description = '';
    public $module_icon        = '';
    public $module_image       = '';
    public $module_home        = '';
    public $module_guid        = '';
    public $module_nopage      = false;
    

    /**
        The Constructor does some basic Variable setting 
    
    */
    public function __construct ($toolType=false, $toolDir=false ) {
        // no tool Dir given, try to fetch from get or post
        if (!$toolDir) { 
            // POST overwrites GET
            if (isset($_GET['tool']) && (trim($_GET['tool']) != ''))    $toolDir = $_GET['tool'];
            if (isset($_POST['tool']) && (trim($_POST['tool']) != ''))  $toolDir = $_POST['tool'];
        }
        if ($toolDir) { 
            $toolDir=trim($toolDir);
            $this->toolDir=$toolDir;
        } else {
            $this->toolDir="list_tools";   
            if ($toolType == "setting") $this->toolDir="list_settings";  
            if ($toolType == "backend") $this->toolDir="list_backends"; 
            if ($toolType == "panel") $this->toolDir="list_panels"; 
        }
        
        
        
        $toolType=strtolower($toolType);
        if ($toolType=="tool" or $toolType=="setting" or $toolType=="backend") {
            $this->toolType=$toolType;
        }
    }

    
    /**
        Controlls the variables  that are needed by the render Proceess
    */
    private function CheckVars () {
    
        global $database;
    
        // return url if something goes wrong , or back button is used
        // Admin access section
        $this->returnToTools = ADMIN_URL.'/admintools/index.php';
        $this->typeDir = "/admintools";
        $this->adminAccess = "admintools";
         
        // only set if nothing is manually set
        if ($this->adminSection =="") $this->adminSection = "admintools";
        if ($this->toolType=="setting") {
            $this->returnToTools = ADMIN_URL.'/settings/index.php';
            $this->typeDir = "/settings";
            $this->adminAccess = "settings";
            $this->adminSection = "settings";
        }  
        
        //Possibly we need on for each page here ... we will see
        // this one is not used anyway right now
        if ($this->toolType=="backend") {
            $this->returnToTools = ADMIN_URL.'/index.php';
            $this->typeDir = "";
        }
         
        // check if Tool directory is fully valid
        if ($this->toolDir===false) $this->ReturnTo();
        
        // test for valid tool name
        if(!preg_match('/^[a-z][a-z_\-0-9]{2,}$/i', $this->toolDir)) $this->ReturnTo();

        // file_exists?
        $path=WB_PATH."/modules/".$this->toolDir;
        // die ($path);
        if (!file_exists($path)) $this->ReturnTo();
               
        //echo "<pre>"; print_r($_SESSION); echo "</pre>";
        
        // Check if tool is installed
        $tt=$this->toolType;
        if ($this->toolLister) $tt="backend" ;
        
        $sql = 'SELECT `name` FROM `'.TABLE_PREFIX.'addons` '.
            'WHERE `type`=\'module\' AND `function` LIKE \'%'.$tt.'%\' '.
            'AND `directory`=\''.$database->escapeString($this->toolDir).'\' '.
            'AND `directory` NOT IN(\''.(implode("','",$_SESSION['MODULE_PERMISSIONS'])).'\') '; 
        //echo $sql;    
        if(!($toolName = $database->get_one($sql)))  echo "";//$this->ReturnTo();

        // back button triggered, so go back.
        if (isset ($_POST['admin_tools'])) $this->ReturnTo();
        
        // ok we got so far les generate some vars

        // figure out if the form of the tool was send
        // the form needs to have exactly the right field names for this to function.
        // 'save_settings' set or 'action'set and == 'save'
        $this->doSave = (isset($_POST['save_settings'])|| isset($_POST['save_default']) || (isset($_POST['action']) && strtolower($_POST['action']  ) == 'save'));
   
        // Defining some path for use in the actual admin tool
        $this->modulePath=WB_PATH."/modules/".$this->toolDir."/"; // we need this one later on too 
        $this->languagePath=$this->modulePath.'languages/';    
        
        $this->moduleUrl = WB_URL."/modules/".$this->toolDir."/";

        if ($this->toolType=="tool")
            $this->returnUrl= ADMIN_URL."/admintools/tool.php?tool=".$this->toolDir;
        if ($this->toolType=="setting")
            $this->returnUrl= ADMIN_URL."/settings/index.php?tool=".$this->toolDir;   
       // if ($this->toolType=="backend")
        //    $this->returnUrl= ADMIN_URL."/tool.php?tool=".$this->toolDir;   
            
        //include info.php for additional infos 
        // TODO: make a better mechanism like for Language Tools
        include($this->modulePath."/info.php" );  
        
        if (isset($module_directory) )    $this->module_directory    = $module_directory;
        if (isset($module_name))          $this->module_name         = $module_name;
        if (isset($module_function))      $this->module_function     = $module_function;
        if (isset($module_version))       $this->module_version      = $module_version;
        if (isset($module_platform))      $this->module_platform     = $module_platform;
        if (isset($module_author))        $this->module_author       = $module_author;
        if (isset($module_license))       $this->module_license      = $module_license;
        if (isset($module_description))   $this->module_description  = $module_description;
        if (isset($module_icon))          $this->module_icon         = $module_icon;
        if (isset($module_image))         $this->module_image        = $module_image;
        if (isset($module_home))          $this->module_home         = $module_home;
        if (isset($module_guid))          $this->module_guid         = $module_guid;
        if (isset($module_nopage))        $this->module_nopage       = $module_nopage;
        
        //fetch the language Var if not set ////moved to Process
        //if (!$this->moduleLangVar)  $this->moduleLangVar= $this->LangVar ();

        // a few more helper vars (save values or reset to default settings)
        $this->saveSettings= (isset($_POST['save_settings'])|| (isset($_POST['action']) && strtolower($_POST['action']  ) == 'save'));
        $this->saveDefault = (isset($_POST['save_default']));
        
        // Use noPage or not 
        if (isset($_POST['no_page']) and $_POST['no_page']=="no_page") $this->noPage=true;
        if (isset($module_nopage) and $module_nopage)                  $this->noPage=true;
       
       // Now after we collected all necessary Variables , 
       // we are ready to set up the enviroment for loading tools
       // this is done in ToolProcess()
       
    } 
    
    /**
        Does the actual rendering of the Tool 
    
    */
    public function Process($echo=false){

        //Set the Enviroment
        global $database, $admin, $TEXT, $MENU, $HEADING, $MESSAGE, $OVERVIEW;
        
        // only for Droplet Module as its using strange Globals
        global $twig;
            
        //check and generate vars and initialize the object
        $this->CheckVars ();
       
        // templateengine users like to have this in an Array
        $VARS=$this->GetPubVars();

        // PHP templater like all vars in a direct manner 
        extract($VARS);
    
        // Loading language files we start whith default EN
        if(is_file($this->languagePath.'EN.php')) {
            include($this->languagePath.'EN.php'); 
        }        
        // Get actual language if exists
        if(is_file($this->languagePath.LANGUAGE.'.php')) {
            include($this->languagePath.LANGUAGE.'.php'); 
        } 

        // Setting the Category name for breadcrumb
        $categoryName= $HEADING['ADMINISTRATION_TOOLS'];
        if ($this->toolType=="setting")
            $categoryName= $MENU['SETTINGS'];   
        if ($this->toolType=="backend")
            $categoryName= "Backend Pages";   
        
        // locally defined function for compatibility
        
        // The following Stuff is now loaded 
        // framework/config.php
        // functions.php
        // framework/initialize.php
        // framework/class.admin.php
        // all module and core classes are registered in Autoloader 
        // Info.php whith slightly modified Vars 
        // Language Vars of this Template are in 
        
        // backend.js/css should be loaded by admin. (hopefully)
        
        // ok lets start output buffer As those old stuff does print and echo imediately 
        // this makes it hard to filter later on. 
        // Output buffer for full page 
        ob_start();
        

        // create admin-object but suppress headers if no page is set 
        // for example this offers opportunety to give back  files for download
        // this possibly creates output already
        // class Admin gets a 
        if ($noPage) $admin = new admin($this->adminSection, $this->adminAccess,false,true,$operateBuffer=false);
        else         $admin = new admin($this->adminSection, $this->adminAccess,true, true,$operateBuffer=false);
        
        // Output buffer for module only 
        ob_start();       
        
        // for use in this class methods
        $this->admin=$admin;
        
        // show title if not function 'save' is requested
         // only if we do not look at page listing
        if(!$doSave and !$noPage and !preg_match("/backend/", $module_function) ) {
            print '<h4><a href="'.$returnToTools.
                'title="'.$categoryName.'">'.
                $categoryName.'</a>'.
                '&nbsp;&raquo;&nbsp;'.$module_name.'</h4>'."\n";
        }

        //Load actual tool
        require(WB_PATH.'/modules/'.$toolDir.'/tool.php');
        
        // Fetch the Buffer for later filtering
        $toolOutput = ob_get_clean ();
        
        // FILTER for OPF DASHBOARD just for this module(tool)
        if(function_exists('opf_controller')) { 
            $toolOutput = opf_controller('backend', $toolOutput, $this->toolDir);
        }
        echo $toolOutput;

        // output footer if  we are not in no_page mode
        if (!$noPage) $admin->print_footer($activateJsAdmin = false,$operateBuffer=false);  
        
        
        // Fetch the Buffer for later filtering
        $fullOutput = ob_get_clean ();

        // FILTER for OPF DASHBOARD for whole page
        if(function_exists('opf_controller')) { 
            $fullOutput = opf_controller('backend', $fullOutput);
        }
        
        
        // echo if set so 
        if (!$echo)  return $fullOutput;
        
        echo $fullOutput;
        return false;

    }
    
    
///////////////////////////////////
// HELPER Functions    
    
    /**
        Read all public vars of this object into one array
    */
        public function GetPubVars () {
        return call_user_func('get_object_vars', $this);
    }

    
    /**
        Returns path for template file, either the one from the BE template 
        or the default one from the module.
    */
    private function GetTemplatePath($filename) {
    
        // Path in BE template
        $path=THEME_PATH.'/modules/'.$this->toolDir.'/templates/'.$filename;
 
        // If no file exists in BE template use the one in module
        if (!file_exists($path)) $path=$this->modulePath.'templates/'.$filename;
        
        // return for include
        return $path;
    }

    
    /**
        Wrapper for $admin->print_error,$admin->print_success
        If a error is set it prits the error otherwise it prints the sucess 
        In Addition it controlls the return address and sets a default if needed.
        
        This is just a comfort Function to shorten the Code of the Tool
    */
    public static function Msg ($setError=false, $returnUrl="" ){
        
        //fetch Globals
        global $MESSAGE, $admin;
  
        if ($returnUrl=="" )   $returnUrl=WB_ADMIN_URL;

        // Check if there is error, otherwise say successful
        if($setError) {
            //3rd param = false =>no auto footer, no exit
            $admin->print_error($setError, $returnUrl,false); 
        } else {
            $admin->print_success($MESSAGE['PAGES_SAVED'], $returnUrl); 
        }
    } 

    
    /**
        Simply returns to a givem address
    */
    private function ReturnTo($returnUrl="") {
        echo $this->returnToTools;
        if (!$returnUrl) $returnUrl=$this->returnToTools;
        //header('location: '.$returnUrl); 
        exit;   
    }

 

}

