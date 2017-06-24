<?php
/**
    @file
    @version 1.1.0
    @brief This file contains the new registry based autoloader.

    The autoloader has no dependencies for other files only constant WB_PATH(path to webroot) is needed. 
    So it maybe usefull in other enviroments too.  
*/
//no direct file access
if(count(get_included_files()) ==1){$z="HTTP/1.0 404 Not Found";header($z);die($z);}


/**
    @brief This is the main autoloader class in Websitebaker(CE)

    Actually this is the only one too besides from the Twig autoloader which is moved to a module soon.

    The autoloader is registry based , so all directories/files/patterns need to be registered somwhere in the CMS code. 
    The registry allows Modules snipits Droplets and any other piece of code anywhere in WBCE to register its classes 
    for further execution. 

    Here some examples for register actions, all path values are expected to be given from WB_PATH as the starting point.

    @code
    // register a new directory to be searched
    WbAuto::AddDir("/classes/");
    WbAuto::AddDir("/classes/fields");

    // register a single file, this allows for classname != filename 
    WbAuto::AddFile("MultiCrud","/classes/multicrud.php");
    WbAuto::AddFile("R","/includes/rb.php");

    // register additional search patterns for file search in directory %s is the classname.
    WbAuto::AddType("class.%s.inc.php");

    @endcode 

    Everything is Global and can easyly be called from everywhere even the internal variables (for debugging if you like)  
    @code
    echo  "<pre>"; print_r(WbAuto::$Dirs); echo  "</pre>";
    echo  "<pre>"; print_r(WbAuto::$Files); echo  "</pre>";
    echo  "<pre>"; print_r(WbAuto::$Types); echo  "</pre>";
    @endcode

    @attention There is one very usefull thing to keep in mind @code class_exists('MyClass'); @endcode triggers the autoloader, so it tests if the class is available and loads it.

    @note A last note to mention the $DirektPath Option should allow to access directories outside of webroot . 

*/ 
// !!! Need to remember, that i need to extend this so it loads directories above webroot!!!
// !!! Need to add a list of loaded classes for debugging.
class WbAuto{

    /**
        @brief Stores the dirs to search in loader 
        @var array $Dirs
    */    
    public static $Dirs=array();  

    /**
        @brief Stores class filename combinations for loader.
        @var array $Files
    */    
    public static $Files=array();   
    
    /**
        @brief Stores search templates to search for in the search dirs.
        @var array $Types
    */    
    public static $Types=array('class.%s.php',
                               '%s.php', 
                               '%s.class.php',
                               '%s.class.inc', 
                               '%s.inc', 
                               '%s.inc.php',  
                               'class.%s.inc'); 



    /**
        @brief The actual loader that is registered whith spl_autoload_register('WbAuto::Loader');
        
        This is the workhorse that does the actual loading. 
    */
    static public function Loader($ClassName){
    
        //already loaded, never mind
        if (class_exists($ClassName, FALSE)) return FALSE; 

        // Filebased loading first 
        //if there is an fileentry for this class
        if (array_key_exists ($ClassName, self::$Files)) { 
            require_once (self::$Files[$ClassName]); 
            return FALSE;
        }

        //replace "\" whith DIRECTORY_SEPARATOR
        $ClassName=str_replace(array("\\", "/"), DIRECTORY_SEPARATOR ,$ClassName);
    
        //already loaded, never mind
        //if (class_exists(basename($ClassName), FALSE)) return FALSE; 
       
        $ClassPath=dirname($ClassName);
        if      ($ClassPath==".") $ClassPath="";
        else if($ClassPath=="/") $ClassPath="";
        else                      $ClassPath=strtolower($ClassPath)."/"; 
        
       
          
        //Search dirs for matching class files
        foreach (WbAuto::$Dirs as $sDirVal) {
            foreach (WbAuto::$Types as $sTypeVal) {
                $sTestFile= $sDirVal.$ClassPath. strtolower(sprintf($sTypeVal, basename($ClassName)));
                if (is_file($sTestFile)) {
                    include($sTestFile);
                    return FALSE;
                }
            }
        }   
    }
    

    /**
        @brief Adds a single class <-> file combination to the autoload list.

        These combinations allow for filename != clasname and are loaded first as it needs no directory searching.
        The path starts from WB_PATH. And it allows for overwrite of already existing entries.
        And as its loaded first it has priority over directory based class loading.
        
        @code
        WbAuto::AddFile("MultiCrud","/modules/multicrud/classes/multicrud.php");
        WbAuto::AddFile("R","/classes/rb.php");
        WbAuto::AddFile("R","/includes/rb.php", true);
        
        // namespace classes .. (needs more testing) 
        WbAuto::AddFile("persistence\database","/framework/classes/persistence/database.php", true);
        @endcode

        @note Autodetection for direct path added , so in most cases you no longer need 
        the direct path parameter. 
    
        @param string $ClassName
            The actual name of the class to be loaded.

        @param string $ClassFile
            The path to the file to load for this class.

        @param boolean $Overwrite 
            Set to true already set entries are overwritten.

        @param boolean $DirektPath 
            Set to true to add a direkt path whithout added WB_PATH

        @retval boolean/string
            Returns false on success, and an error message on failure. 
    */
    static public function AddFile ($ClassName="", $ClassFile="", $Overwrite= false, $DirektPath=false){
    
        //Check for valid call return error if invalid
        if ($ClassName=="") return ("AddFile, no 'ClassName' set!");
        if ($ClassFile=="") return ("AddFile, no 'ClassFile' set!");
        if (!is_string($ClassName)) return ("AddFile,'ClassName' is not a string");
        if (!is_string($ClassFile)) return ("AddFile,'ClassFile' is not a string");
        if (!is_bool($Overwrite)) return ("AddFile invalid parameter 'Overwrite' is not a boolean! ");
        if (!is_bool($DirektPath)) return ("AddFile invalid parameter 'DirektPath' is not a boolean! ");
        if (isset(WbAuto::$Files[$ClassName]) and $Overwrite== false) return  ("Class already exists ($ClassName)");
        
        // for windooze make sure we got the right seperator 
        $ClassFile = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $ClassFile);
        
        // allow to add files directly
        if (
            strpos( $ClassFile, WB_PATH ) !== FALSE ||  //Autodetection of direct path added
            $DirektPath
        ) {
            //this file delivers a full file path  whithout adding WB path
            $LoadFile = $ClassFile;
        } else {
            //construct full filepath whith WB path default behavior
            $LoadFile = WB_PATH.$ClassFile;
        }
          
        if (!is_file($LoadFile)) return ("AddFile, File does not exist or not a file: $LoadFile");
        if (!is_readable($LoadFile)) return ("AddFile, File not readable: $LoadFile");  
         
        // if all is ok set new Classfile 
        WbAuto::$Files[$ClassName]=$LoadFile;
        return false;
    
    }
 
    /**
        @brief Add a directory to be searched for matching class names/files.

        Directories added here are searched for matching classfiles using patterns from the types array.
        The direcctory path starts from WB_PATH(webroot). The defautl patters in WB is class.classname.php all lowercase letters.

        @code
        WbAuto::AddDir("/classes/");
        WbAuto::AddDir("/modules/mymodule/classes/");
        WbAuto::AddDir("/var/web345/html/websitebaker/modules/mymodule/classes/" ,true);   
        @endcode

        @note Autodetection for direct path added , so in most cases you no longer need 
        the direct path parameter. 

        @param string $Dir
            The path to the Directory to include starting from WB_PATH

        @param bool $Dir
            if set to true WB_PATH is not added
            
        @param boolean $DirektPath 
            Set to true to add a direkt path whithout added WB_PATH


        @retval boolean/string
            Returns false on success, and an error message on failure. 
    */
    static public function AddDir ($Dir="" ,$DirektPath=false){

        // some checks
        if (!is_string($Dir)) return ("AddDir: Directory needs to be a string.");
        if (empty ($Dir))     return ("AddDir: Directory is empty.");
 
        // for windooze make sure we got the right seperator 
        $Dir = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $Dir);
        
        //always trim and re-add so we make sure we have a seperator at the end 
        $Dir=rtrim($Dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        
        // allow to add dirs directly
        if (
            strpos( $Dir, WB_PATH ) !== FALSE ||  //Autodetection of direct path added
            $DirektPath
        ) {
            //construct full dir whithout WB path
            $AddDir=$Dir;
        } else {
            //construct full dir whith WB path
            $AddDir=WB_PATH."/".$Dir;
        }
                
        //Some more checks
        if (!is_dir($AddDir)) return ("AddDir: Not a directory using is_dir: $AddDir");
        if (!is_readable($AddDir)) return ("AddDir: Directory not readable: $AddDir");
        
        // Add Dir
        WbAuto::$Dirs[]=$AddDir;
        
        //avoid double entries
        WbAuto::$Dirs= array_unique(WbAuto::$Dirs);
 
        return false;
    }
 
    /**
        @brief Adds a new search template to the list of search templates.

        Templates are used to look for matching files in the directory search.

        @code
        //  %s is the classname.
        WbAuto::AddType("class.%s.inc.php");
        WbAuto::AddType("%s.php");
        WbAuto::AddType("%s.class.php");
        @endcode 

        @param string $Type
            The search pattern/template to add.

        @retval boolean/string
            Returns false on success, and an error message on failure.     

    */
    static public function AddType ($Type){

        // Add Search template
        WbAuto::$Types[]=$Type;
       
        //avoid double entries
        WbAuto::$Types= array_unique(WbAuto::$Types);

        return false;
    }
}

/// Finally register this autoloader
spl_autoload_register('WbAuto::Loader');

/// Then we add all stuff needed for WBCE
WbAuto::AddDir(dirname(__FILE__)."/",true);




