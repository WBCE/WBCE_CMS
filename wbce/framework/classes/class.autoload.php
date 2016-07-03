<?php
//no direct file access
if(count(get_included_files()) ==1){$z="HTTP/1.0 404 Not Found";header($z);die($z);}
/**
@file
@brief This file contains the new registry based autoloader.

The autoloader has no dependencies for other files only constant WB_PATH(path to webroot) is needed. 
So it maybe usefull in other enviroments too.  
*/

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

        $ClassName=preg_replace("/\\\/s", "/" ,$ClassName);
    
        //already loaded, never mind
        if (class_exists(basename($ClassName), FALSE)) return FALSE; 
       
        $ClassPath=dirname($ClassName);
        if      ($ClassPath==".") $ClassPath="";
        else if($ClassPath=="/") $ClassPath="";
        else                      $ClassPath=strtolower($ClassPath)."/"; 
        
        //if there is an fileentry for this class
        if (array_key_exists ($ClassName, WbAuto::$Files)) { 
            require_once (WbAuto::$Files[$ClassName]); 
            return FALSE;
        }
        
         
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
    
    @code
    WbAuto::AddFile("MultiCrud","/modules/multicrud/classes/multicrud.php");
    WbAuto::AddFile("R","/classes/rb.php");
    WbAuto::AddFile("R","/includes/rb.php", true);
    @endcode


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
    static public function AddFile ($ClassName="", $ClassFile="", $Overwrite= false,$DirektPath=false){
    
        //Check for valid call return error if invalid
        if ($ClassName=="") return ("No class name set!");
        if ($ClassFile=="") return ("No class file set!");
        if (isset(WbAuto::$Files[$ClassName]) and $Overwrite== false) return  ("Class already exists ($ClassName)");
        
        // More Checks
        $LoadFile = WB_PATH.$ClassFile;
        
        // allow to add dirs directly
        if ($DirektPath) {
            //construct full filepath whithout WB path
            $LoadFile = WB_PATH.$ClassFile;
        } else {
            //construct full filepath whith WB path
            $LoadFile = WB_PATH.$ClassFile;
        }
          
        if (!is_file($LoadFile)) return ("File does not exist or not a file: $LoadFile");
        if (!is_readable($LoadFile)) return ("File not readable: $LoadFile");  
         
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
        if (!is_string($Dir)) return ("Directory needs to be a string.");
        if (empty ($Dir))     return ("Directory is empty.");
 
        // for windooze
        $Dir=str_replace ("\\", "/", $Dir);
        
        //always trim at front and end
        $Dir=trim($Dir, "/");
        
        // allow to add dirs directly
        if ($DirektPath) {
            //construct full dir whithout WB path
            $AddDir="/".$Dir."/";
        } else {
            //construct full dir whith WB path
            $AddDir=WB_PATH."/".$Dir."/";
        }
                
        //Some more checks
        if (!is_dir($AddDir)) return ("Not a directory: $AddDir");
        if (!is_readable($AddDir)) return ("Directory not readable: $AddDir");
        
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

//finally register this autoloader
spl_autoload_register('WbAuto::Loader');




