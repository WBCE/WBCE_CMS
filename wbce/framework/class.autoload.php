<?php
//no direct file access
if(count(get_included_files()) ==1){$z="HTTP/1.0 404 Not Found";header($z);die($z);}

// !!! Need to remember, that i need to extend this so it loads directories above webroot!!!

class WbAuto{

    // Vars
    public static $Dirs=array();    // Dirs to search
    public static $Files=array();   // Class filename combinations for loader 
    
    //Search templates to search for files in the directories
    public static $Types=array('%s.class.php',
                               '%s.class.inc', 
                               '%s.php', 
                               '%s.inc', 
                               '%s.inc.php', 
                               'class.%s.php', 
                               'class.%s.inc'); 
    
    // The function actually registered to PHP autoloading
    static function Loader($ClassName){
        //already loaded, never mind
        //echo $ClassName;
        if (class_exists($ClassName, FALSE)) return FALSE; 
        
        //if there is an fileentry for this class
        if (array_key_exists ($ClassName, WbAuto::$Files)) { 
            //echo (WbAuto::$Files[$ClassName]); 
            require_once (WbAuto::$Files[$ClassName]); 
            return FALSE;
        }
        
        //Search dirs for matching class files
        foreach (WbAuto::$Dirs as $sDirVal) {
            foreach (WbAuto::$Types as $sTypeVal) {
                $sTestFile= $sDirVal. sprintf($sTypeVal, $ClassName);
                $sTestFile= strtolower($sTestFile);
                //echo $sTestFile."</br>";
                if (is_file($sTestFile)) {
                    include_once($sTestFile);
                    return FALSE;
                }
            }
        }   
    }
    
    // add a single class file 
    // WbAuto::AddFile("MultiCrud","/classes/multicrud.php");
    static function AddFile ($ClassName="", $ClassFile="", $overwrite= false){
    
        //Check for valid call return error if invalid
        if ($ClassName=="") return ("No class name set!");
        if ($ClassFile=="") return ("No class file set!");
        if (isset(WbAuto::$Files[$ClassName]) and $overwrite== false) return  ("Class already exists ($ClassName)");
        
        // More Checks
        $LoadFile = WB_PATH.$ClassFile;
        if (!is_file($LoadFile)) return ("Not a file: $LoadFile");
        if (!is_readable($LoadFile)) return ("File not readable: $LoadFile");  
         
        // if all is ok set new Classfile 
        WbAuto::$Files[$ClassName]=$LoadFile;
        return FALSE;
    
    }
 
    // add a full directory to search for matching classfiles
    // WbAuto::AddDir("/classes/fields");
    static function AddDir ($Dir){
 
        // for windooze
        $Dir=str_replace ("\\", "/", $Dir);
        
        //always trim at front end end
        $Dir=trim($Dir, "/");
        
        //construct full dir
        $AddDir=WB_PATH."/".$Dir."/";
        
        //Some checks
        if (!is_dir($AddDir)) return ("Not a directory: $AddDir");
        if (!is_readable($AddDir)) return ("Directory not readable: $AddDir");
        
        // Add Dir
        WbAuto::$Dirs[]=$AddDir;
        
        //avoid double entries
        WbAuto::$Dirs= array_unique(WbAuto::$Dirs);
 
        return FALSE;
    }
 
    // Add a new template to search for in directorys.
    // WbAuto::AddType("class.%s.inc.php");
    static function AddType ($Type){

        // Add Search template
        WbAuto::$Types[]=$Type;
       
        //avoid double entries
        WbAuto::$Types= array_unique(WbAuto::$Types);

        return FALSE;
    }
}

//finally register this autoloader
spl_autoload_register('WbAuto::Loader');

//WbAuto::AddDir("/classes/");
//WbAuto::AddDir("/classes/fields");

//WbAuto::AddFile("R","/classes/rb.php");
//WbAuto::AddFile("MultiCrud","/classes/multicrud.php");

//WbAuto::AddType("class.%s.inc.php");

//echo  "<pre>"; print_r(WbAuto::$Dirs); echo  "</pre>";
//echo  "<pre>"; print_r(WbAuto::$Files); echo  "</pre>";
//echo  "<pre>"; print_r(WbAuto::$Types); echo  "</pre>";


