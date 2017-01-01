<?php
/**
@file
@brief This file contains class insert which is usually used in conjunction whith class I as a facade class.\n
Its a class for insertion of  Javascript, Metas, Title and CSS into a pagetemplate on WB 
and maybe other systems.

@author Norbert Heimsath for the WBCE Project
@copyright http://www.gnu.org/licenses/lgpl.html   (GNU LGPLv2 or any later) 
@version 1.0.0-alpha.2 

The basic idea is to have global available methods that allow any script whithin Websitbaker CE
to register Metadata, Javascript and Stylesheets, that are later displayed in the Websitetemplate.
The actual insertion is done via outputfilter placeholders (Droplets [[Css]], [[Js]]....)

For more information look into the detailed description of class Insert.
*/

/**
@brief Class for insertion of  Javascript, Metas, Title and CSS into a pagetemplate on WB 
and maybe other systems.\n In Websitebaker CE  this class will use a global facade (class I) to guarantee 
a fully global presence. 

The basic idea is to have global available methods that allow any script whithin Websitbaker CE
to register metadata, javascript and stylesheets. Later those are  inserted into the Websitetemplate.
The actual insertion is done via outputfilter placeholders (Droplets [[Css]], [[Js]]....)
The outputfilter is delivered whith this class.

@attention
The following documentation is about using this class drirectly, if you want to know how to use script 
insertion whith Websitebaker CE\n please look into class  I(facade).

 
###Register

@code
$i=new Insert();

//Meta
$i->AddMeta(array (
    "setname"=>"charset",
    "charset"=>"ISO-8859-1"
));

//CSS
$i->AddCss (array(
    'setname'=>"test", 
    'href'   =>"https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css", 
    'media'  =>"screen"
));
$i->AddCss (array(
    'setname'=>"test2", 
    'style'  =>"#htto {position:absolute}"
));

//Js
$i->AddJs (array(
    'setname'=>"jquery", 
    'position'=>"BodyLow", 
    'src'=>"https://code.jquery.com/jquery-2.1.4.min.js"
));
$i->AddJs (array(
    'setname'=>"test2", 
    'position'=>"HeadLow", 
    'src'=>"http://google.de", 
    'script'=>"alert('TaTaaa');" 
));
@endcode 

###Special keys

AddMetas, AddCss and AddJs got some special keys that are used to provide some 
functionality to allow mamagement of double entries.
@verbatim

    key          typ       default      description  
    --------------------------------------------------------------------------------------------------
    "setname"    string    Unique ID    Is a name that is simply for preventing doublets if 
                                        nothing is given it gets an unique id.
    "overwrite"  boolean   true         Per default metas whith the same setname are overwritten, 
                                        but if you set overwrite to false.
                                        The method returns an error message as result.  
    "setsave"    boolean   false        Metas whith "setsave" set true the Meta cannot be overwritten.

@endverbatim

###Template

The Template should look like this:

@code
<!DOCTYPE html>
<html lang="de">
<head>
[[Metas]]
[[Title]]
[[Css]]
[[Js?pos=HeadTop]]
[[Js?pos=HeadLow]]
</head>
<body>
[[Js?pos=BodyTop]]
[[Html?pos=BodyTop]]
    <!-- Sichtbarer Dokumentinhalt im body -->
    <p>Sehen Sie sich den Quellcode dieser Seite an.
      <kbd>(Kontextmenu: Seitenquelltext anzeigen)</kbd></p>
[[Html?pos=BodyLow]]
[[Js?pos=BodyLow]]
</body>
</html>
@endcode

###Replacement

Replacement is done by the filter function 
@code
echo $i->Filter($string);
@endcode


@todo We need methods to remove entries from the lists.  

*/
class Insert {

    /**
    @brief Storage array for javascript. 

    @var array $Js
    */
    private $Js = array();
    
    /**
    @brief Storage array for CSS
    @var array $Css
    */
    private $Css = array();
    
    /**
    @brief Storage array for Meta entries.
    @var array $Metas
    */
    private $Metas = array();

    /**
    @brief Storage array for Html entries.
    @var array $Html
    */
    private $Html = array();

    /**
    @brief Storage variable for Title.
    @var string $Title
    */
    private $Title="";

    /**
    @brief What is the prefered Rendering method

    Possible values are "html", "xhtml", "html5"
    @var string $Render
    */    
    public $Render= "html5"; 
    
    /**
    @brief The constructor only fetches the WB_RENDER Constant and copies 
    the value to the local $Render variable. 

    Default value is "html5".
    */
    function __construct() {
        if (defined('WB_RENDER')) $this->Render=WB_RENDER;
    }
    

    ////////// TITLE SECTION //////////
    
    
    
    /**
    @brief Adds a value to the title var. 

    Default behavior is to append the value  to an existing value. 
    If $overwrite is set to true the old value will be overwritten by the new value replaced. 
    
    @param string $Title
    @param boolean $Overwrite 
    @return boolean 
        false on sucess| string error message on failure
    */
    public function AddTitle ($Title="", $Overwrite=false){
        if ($Title=="") return "Title empty!";
        
        if ($Overwrite) $this->Title=$Title;
        else            $this->Title.=$Title;
        return false;
    } 
    
    
    
    /**
    @brief Returns the recent title or $Default if title is not found.
    
    @param undefined $Default 
    @return returns $Default on failure and th title as string if title is found.
    */    
    public function GetTitle ($Default=false){
        if (!empty($this->Title)) return $this->Title;
        else return $Default;
    }
    
    /**
    @brief Renders the Title and returns it. 

    Returns $Default if Title is not set.

    @code
    $i= new Insert();
    $i->AddTitle('The Websitetitle');
    $i->RenderTitle();
    $i->RenderTitle("\t<title>No Title</title>")
    @endcode

    @param undefined $Default 
    @retval string 
        the full redered HTML title tag 
    */
    public function RenderTitle($Default="\t<title></title>"){
       if (!empty($this->Title))  return "\t<title>".$this->Title."</title>";
       else return $Default;
    }
    

    ////////// METAS SECTION //////////

    /**
    @brief Allows to add additional Metas to the Head of the Template.

    Expects an array to be given as metas got a countless number 
    of attributes the array may contain anything you like.
    @code
    //
    $i->AddMeta(array ("name"=>"description", "content"=>"This is a nice description", -8", "overwrite"=>true, "setsave"=>true));
    $i->AddMeta(array ("name"=>"keywords", "content"=>"Dieses, Beschreibung, Was auch immer", "lang"=>"de" ));
    $i->AddMeta(array ("setname"=>"keywords_en", "name"=>"keywords", "content"=>"This, Description, Something", "lang"=>"en"));
    $i->AddMeta(array ("setname"=>"keywords_en", "name"=>"keywords", "content"=>"This, Bla, Blub, blubber, Something", "lang"=>"en", "overwrite"=>false));
    $i->AddMeta(array ("name"=>"robots", "content"=>"noindex,nofollow" ));
    $i->AddMeta(array ("name"=>"robots", "content"=>"index,follow"));
    $i->AddMeta(array ("setname"=>"expires", "http-equiv"=>"expires", "content"=>"43200"));
    $i->AddMeta(array ("setname"=>"expires", "http-equiv"=>"expires", "content"=>"0")); //overwrites the former setting
    $i->AddMeta(array ("setname"=>"charset", "charset"=>"ISO-8859-1" ));
    @endcode 
    
    @param array $Content 
    This is always an array whith all atributes stored as "key" => "value" see example above. 

    There are quite a few attributes that schould be used as setname so other 
    Scripts can interact whith those correctly:
    
    @verbatim    
    setname      function  
    -------------------------------------------------------
    author       name="author" content="Author Name" 
    description  name="description" ....
    keywords     name="keywords"..... 
    date         name="date"
    robots       name="robots"
    charset      charset="utf-8" for example (This key gets special threatment in the render section.)
    expires      http-equiv="expires"
    refresh      http-equiv="refresh"

    All other parameters are simply piped to the meta output.
    @endverbatim    

    @retval boolean/string
        Returns false on success, and an error message on failure. 

    */
    public function AddMeta ($Content=""){
        
        // invalid input ?
        if (!is_array($Content)) return "AddMeta() expects an array as parameter !";
        
        // setname is used for identification of double entries , set to uniqid() if empty
        if (isset($Content['setname']) and $Content['setname']!="") $SetName=$Content['setname'];
        else                                                        $SetName=uniqid();
        // no longer needed
        unset ($Content['setname']);
        
        // check for setnames that are alreasdy set if overwrite === false
        if (isset($Content['overwrite']) and $Content['overwrite']===false and isset($this->Metas[$SetName])) {
            return "Cannot add Meta, setname already in use!";
        }
        // no longer needed
        unset ($Content['overwrite']);
        
        // maybe the entry has the setsave flag
        if (isset($this->Metas[$SetName]) and !empty($this->Metas[$SetName]['setsave'])) {
            return "Cannot add Meta, Another entry whith same name ($SetName) has the save flag on!";
        }
        
        // Hey its all empty now!!!
        if (empty($Content)) return "Cannot add meta, no content set!";

        //Do the actual adding
        $this->Metas[$SetName] = $Content;
        
        //all ok return false
        return false;
    }
    
    /**
    @brief Method to get the metas array for checking or processing.

    You can set a default return value if nothing is found. 

    @param undefined $Default
        You can define a special return var if the meta array is empty.

    @retval array/undefined
        Returns the Array of already defined Metas.
    */
    public function GetMetas ($Default=false) {
        if (empty($this->Metas)) return $Default; 
        return $this->Metas;
    }
    
    /**
    @brief Method to get the values of a single Meta named whith "setname"

    The method returns $Default if nothing is found. 

    @param string $SetName 
        Simply the setname used in AddMeta

    @param undefined $Default
        Whatever you like as a returnvalue if the method does not find a matching entry.

    @retval array/undefined
        Returns the value of the meta whith this "setname" or $Default if nothing is found.  
    */
    public function GetMeta ($SetName="", $Default=false) {
        if (empty($this->Metas)) return $Default;
        if ($SetName=="") return $Default;
        if (!empty($this->Metas[$SetName])) return $this->Metas[$SetName];       
        return $Default;
    }
   
    /**
    @brief Rendering the Metas dependent on defined rendering method(html, html5, xhtml). 

    Or return nothing or maybe a defined $Default if you like. 
    Especially having "charset" set will get very different results depending on Render Method.
    having set Charset also stops the rendering of other fields

    @param undefined $Default
        Whatever you like as a returnvalue if the meta array is empty.

    @retval string 
        All metas rendered are returned as a string.

    */
    public function RenderMetas ($Default=""){
        if (empty($this->Metas)) return $Default;
        
        $RetVal="";
        foreach ($this->Metas as $Meta) {
            $RetVal .= "\t<meta ";
            // charset is a special case where output is different on render method
            if (isset($Meta['charset']) and $Meta['charset']!=""){
                if ($this->Render=="html5") {
                    $RetVal .= ' charset="'.$Meta['charset'].'" ';
                } else {
                    $RetVal .= ' http-equiv="content-type" content="text/html; charset='.$Meta['charset'].'" ';
                }
            } else {
                foreach ($Meta as $Key=>$Value) {
                    $RetVal.=" $Key=\"$Value\" ";
                } 
            }
            if ($this->Render=="xhtml") $RetVal .= " />\n";
            else                        $RetVal .= " >\n";
        }
        return $RetVal;
    }


    ///// CSS /////

    /**
    @brief Method to add CSS entries to the CSS array. 

    Compared to the AddMeta() this one got a fixed complement of available keys, 
    but basic functionality is the same.

    @code
    // adding font awesome
    $i->AddCss (array(
        'setname'=>"font-awesome", 
        'href'   =>"https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css", 
        'media'  =>"screen"
    ));
    // adding some direct style commands
    $i->AddCss (array(
        'setname'=>"test23", 
        'style'  =>"#htto {position:absolute}"
    ));
    @endcode

    ###Allowed Keys

    @verbatim
    Key         Typ         Description  
    --------------------------------------------------------------------------------------------------
    href        string      Simply the full URL where to load the Script
    style       string      The plain style definitions to insert.
    media       string      The "media" attribute for stylesheets.
    title       string      "title" atribute NOT IMPLEMENTED YET! 
    @endverbatim
    All other Keys are the default ones you find in the class description.

    For good setnames pleass try to use the lowercase version of the script loaded.
    @verbatim    
    jquery
    font-awesome
    colorbox
    fancybox
    ... 
    @endverbatim

    @param array $Content
    The array that defines an entry. 

    @retval boolean/string
    Returns false on success, and an error message on failure. 

    */
    public function AddCss ($Content){
        // setname is used for identification of double entries , set to uniqid() if empty
        if (isset($Content['setname']) and $Content['setname']!="") $SetName=$Content['setname'];
        else                                                        $SetName=uniqid();
        // no longer needed
        unset ($Content['setname']);
        
        // check for setnames that are alreasdy set if overwrite === false
        if (isset($Content['overwrite']) and $Content['overwrite']===false and isset($this->Metas[$SetName])) {
            return "Cannot add CSS, setname($SetName) already in use!";
        }
        // no longer needed
        unset ($Content['overwrite']);
        
        // maybe the entry has the setsave flag
        if (isset($this->Metas[$SetName]) and !empty($this->Metas[$SetName]['setsave'])) {
            return "Cannot add CSS, Another entry whith same name ($SetName) has the save flag on!";
        }
        
        // Hey its all empty now!!!
        if (empty($Content)) return "Cannot add CSS, no content set!";
        
        // The main atributes are empty?
        if (empty($Content['href']) and empty($Content['style'])) return "CSS Nothing set no href no style";
   
        if (!empty($Content['title'])) $this->Css[$SetName]['title']  = $Content['title']; // not in use right now
        
        // Href gets some special threatment so you can have an always refreshed browser cache
        // by changing the file URL whith a get parameter
        if (!empty($Content['href'])) {
            // Set value if exits
            $this->Css[$SetName]['href'] = $Content['href'];

            // if its an internal file we handle the caching options
            $sFilePath = str_replace(WB_URL, WB_PATH, $Content['href']);
            if(is_file($sFilePath)){
                if (!defined('WB_CSS_REFRESH_BROWSER_CACHE') OR  WB_CSS_REFRESH_BROWSER_CACHE===true){
                    // Append a Parameter that depends on the last time the file was modified 
                    $this->Css[$SetName]['href'] .= '?' . filemtime($sFilePath);
                }   
            }
        }

        if (!empty($Content['media']))$this->Css[$SetName]['media'] = $Content['media'];
        if (!empty($Content['style']))$this->Css[$SetName]['style'] = $Content['style'];
        if (!empty($Content['setsave']))$this->Css[$SetName]['setsave'] = $Content['setsave'];
        
        //echo "\n<pre>\n"; print_r($Content); echo "</pre>";
        return false;
       
    }


    /**
    @brief Method to delete a named CSS  entry.
    
    Simply remove an entry by its "setname" 
    ~~~~~~~~~~
        $hInsertHandle->DelCss ("setnameOfYourEntry");
    ~~~~~~~~~~
    @param string $SetName
        The setname for the entry to delete 
    @retval boolean/string
    Returns false on success, and an error message on failure. 

    */
    public function DelCss ($SetName=""){
         if (empty ($SetName))
            return "DelCss can not delete unknown Entry!";

         if (!isset($this->Css[$SetName])) 
            return "DelCss entry does not exist ($SetName)!";
         
         // maybe the entry has the setsave flag
        if (!empty($this->Css[$SetName]['setsave'])) {
            return "DelCss Cannot delete Css Entry , $SetName has the save flag on!";
        }
         
        unset ($this->Css[$SetName]);
        return false;
    }



    /**
    @brief Method to get the CSS array for checking or processing.

    You can set a default return value if nothing is found. 

    @param undefined $Default
        You can define a special return var if the CSS array is empty.

    @retval array/undefined 
        Returns the Array of already defined CSS.
    */    
    public function GetCss ($Default=false){
        if (empty($this->Css)) return $Default;
        return $this->Css;
    }
    

    /**
    @brief Method to get the values of a single CSS entry named whith "setname"

    The method returns $Default if nothing is found. 

    @param string $SetName 
        Simply the setname used in AddCss().

    @param undefined $Default
        Whatever you like as a returnvalue if the method does not find a matching entry.

    @retval array/undefined
        Returns the value of the CSS whith this "setname" or $Default if nothing is found.  
    */
    public function GetCs ($SetName="", $Default=false){
        if (empty($this->Css)) return $Default;
        if ($SetName=="") return $Default;
        if (!empty($this->Css[$SetName])) return $this->Css[$SetName];       
        return $Default;
    }


    /**
    @brief Renders all the CSS entries saved in the array. 

    @param undefined $Default
        You can define a special return var if the CSS array is empty.

    @retval string/undefined 
        Renders all of already defined CSS or $Default if empty
    */
    public function RenderCss ($Default=""){
        if (empty($this->Css)) return $Default;  
        $RetVal ="";
        foreach ($this->Css as $Cs) {  
            if (!empty($Cs['href'])) {
                $RetVal .= "\t<link rel=\"stylesheet\" href=\"";
                $RetVal .= $Cs['href']."\" ";  
                if (!empty($Cs['media'])) {  
                    $RetVal .= "media=\"";
                    $RetVal .= $Cs['media']."\" ";
                }
                if ($this->Render=="xhtml") $RetVal .= " />\n";
                else                        $RetVal .= " >\n";             
            }
            if (!empty($Cs['style'])){
                $RetVal .= "\t<style type=\"text/css\" ";
                if (!empty($Cs['media'])) {  
                    $RetVal .= "media=\"";
                    $RetVal .= $Cs['media']."\" ";
                }                
                $RetVal .=" >\n";
                $RetVal .= $Cs['style'];
                $RetVal .= "\n\t</style>\n";
            }          
        }
        return $RetVal;   
    }
    
    
    ////////////////// JS //////////////////////////////////


    /**
    @brief Method to add Js entries to the JS array. 

    Compared to the AddMeta()this on got a fixed complement of available keys, 
    but basic functionality is the same.

    @code
    $i->AddJs (array(
        'setname'=>"test1", 
        'position'=>"HeadLow", 
        'script'=>"var hier='dfdfdf'", 
        'overwrite'=>true
    ));
    $i->AddJs (array(
        'setname'=>"jquery", 
        'position'=>"BodyLow", 
        'src'=>"https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"
    ));
    @endcode

    ###Allowed Keys
    @verbatim
    Key         Typ         Description  
    --------------------------------------------------------------------------------------------------
    position    string      The Position where to insert this piece of JS
    script      string      The scritp to insert. 
    src         string      Simply the full URL where to load the Script
    @endverbatim
    All other Keys are the default ones you find in the class description.

    For good setmanes pleass try to use the lowercase version of the script loaded.
    @verbatim    
    jquery
    font-awesome
    colorbox
    fancybox
    ... 
    @endverbatim

    ###The default Positions for insert JS are. But you can define your own if you like.
    @verbatim HeadTop, HeadLow, BodyTop, BodyLow @endverbatim

    @param array $Content
        The array that defines an entry. 

    @retval boolean/string
        Returns false on success, and an error message on failure. 
    */

    public function AddJs ($Content){
          // setname is used for identification of double entries , set to uniqid() if empty
        if (isset($Content['setname']) and $Content['setname']!="") $SetName=$Content['setname'];
        else                                                        $SetName=uniqid();
        // no longer needed
        unset ($Content['setname']);
        
        // check for setnames that are alreasdy set if overwrite === false
        if (isset($Content['overwrite']) and $Content['overwrite']===false and isset($this->Js[$SetName])) {
            return "Cannot add JS, setname($SetName) already in use!";
        }
        // no longer needed
        unset ($Content['overwrite']);
        
        // maybe the entry has the setsave flag
        if (isset($this->Js[$SetName]) and !empty($this->Js[$SetName]['setsave'])) {
            return "Cannot add JS, Another entry whith same setname ($SetName) has the save flag on!";
        }
        
        // Hey its all empty now!!!
        if (empty($Content)) return "Cannot add JS, no content set!";
        
        // The main atributes are empty?
        if (empty($Content['src']) and empty($Content['script'])) return "JS Nothing set no src no script";

        // Set default position
        if (empty($Content['position'])) $Position="HeadLow";
        else                             $Position=$Content['position'];
          //echo "\n<pre>check:\n"; print_r($Content); echo "</pre>";
        
        if (!empty($Content['position'])) $this->Js[$SetName]['position'] = $Position;

        // SRC gets some special threatment so you can have an always refreshed browser cache
        // by changing the file URL whith a get parameter
        if (!empty($Content['src'])) {
            // Set value if exits
            $this->Js[$SetName]['src'] = $Content['src'];

            // if its an internal file we handle the caching options
            $sFilePath = str_replace(WB_URL, WB_PATH, $Content['src']);
            if(is_file($sFilePath)){
                if (!defined('WB_JS_REFRESH_BROWSER_CACHE') OR  WB_JS_REFRESH_BROWSER_CACHE===true){
                    // Append a Parameter that depends on the time the file was last modified 
                    $this->Js[$SetName]['src'] .= '?' . filemtime($sFilePath);
                }
            }  
        }

        if (!empty($Content['script']))   $this->Js[$SetName]['script'] = $Content['script'];
        if (!empty($Content['setsave']))  $this->Js[$SetName]['setsave'] = $Content['setsave'];

        return false;
    }



    /**
    @brief Method to delete a named JS  entry.
    
    Simply remove an entry by its SetName 
    ~~~~~~~~~~
        $hInsertHandle->DelJs ($SetName="SetNameOfYourEntry");
    ~~~~~~~~~~
    @param string $SetName
        The setname for the entry to delete 
    @retval boolean/string
    Returns false on success, and an error message on failure. 

    */
    public function DelJs ($SetName=""){
         if (empty ($SetName))
            return "DelJs can not delete unknown Entry!";

         if (!isset($this->Js[$SetName])) 
            return "DelJs entry does not exist ($SetName)!";
         
         // maybe the entry has the setsave flag
        if (!empty($this->Js[$SetName]['setsave'])) {
            return "DelJs Cannot delete JS Entry , $SetName has the save flag on!";
        }
         
        unset ($this->Js[$SetName]);
        return false;
    }











    /**
    @brief Method to get the Js array for checking or processing.

    You can set a default return value if nothing is found. 

    @param undefined $Default
        You can define a special return var if the Js array is empty.

    @param string $Position
        Set this to only return Scripts whith a certain position set. 
        Default position names can be found in docs to AddJs().
        $Position="All" returns the full Js array.

    @retval array/undefined 
        Returns the Array of already defined Java Scripts(for a certain position).
    */
    public function GetJs ($Position="All", $Default=false){
        if (empty($this->Js)) return $Default;
        if ($Position=="All") return $this->Js;
        $Ret =array();
        foreach  ($this->Js as $SetName=>$J){
            if ($J['position']==$Position) 
                $Ret[$SetName]=$J;
        }
        return $Ret;
    }


    /**
    @brief Method to get the values of a single JS entry named whith "setname"
    The method returns $Default if nothing is found. 

    @param string $SetName 
        Simply the setname used in AddJs().

    @param undefined $Default
        Whatever you like as a returnvalue if the method does not find a matching entry.

    @retval array/undefined 
        Returns the value of the JS whith this "setname" or $Default if nothing is found.  
    */
    public function GetJ ($SetName="", $Default=false){
        if (empty($this->Js)) return $Default;
        if ($SetName!="") return $Default;
        if (!empty($this->Js[$SetName])) return $this->Js[$SetName];       
        return $Default;
    }


    /**
    @brief Renders all the JS for a certain position. 

    The prefered render method is taken into account.

    @code
    $i->RenderJs();           //renders the default JS  "HeadLow"
    $i->RenderJs("BodyLow");  //Renders JS for "BodyLow" position. 
    @endcode

    @param string $Position
        Set this to only return Scripts whith a certain position set. 
        Default position names can be found in docs to AddJs().
        NO $Position="All" here!!!

    @param undefined $Default
        Whatever you like as a returnvalue if the method does not find a matching entrys.

    @retval string/undefined 
        Returns the rendered Javascript definitions for one position or $Default if nothing is found.  

    */   
    public function RenderJs ($Position="HeadLow", $Default=""){
        if (empty($this->Js)) return $Default;  
        
        // sort aout the ones we want to show
        $Ret =array();
        foreach  ($this->Js as $SetName=>$J){
            if ($J['position']==$Position) 
                $Ret[$SetName]=$J;
        }      
        //print_r($Ret);
        
        // none in this position
        if (!count($Ret)) return $Default;  
        
        // Run the render loop if src and script are set , both a rendered.
        $RetVal ="";
        foreach ($Ret as $J) {  
            if (!empty($J['src'])) {
                $RetVal .= "\t<script src=\"";
                $RetVal .= $J['src']."\" ";  
                if ($this->Render!="html5") {  
                    $RetVal .= "type=\"text/javascript\" >";
                }
                $RetVal .= ">";
                $RetVal .= "</script>\n";       
            }
            if (!empty($J['script'])){
                $RetVal .= "\t<script ";
                if ($this->Render!="html5") {  
                    $RetVal .= "type=\"text/javascript\"";
                }
                $RetVal .= ">\n";
                $RetVal .= $J['script'];
                $RetVal .= "\n\t</script>\n";
            }          
        }
        return $RetVal;   
    }
    




  ////////////////// Html //////////////////////////////////


    /**
    @brief Method to add Html entries to the Html array. 

    AddHtml is for adding plain HTML to a set of predifined places.
    (BodyTop, BodyLow). You even can define your own locations but then 
    you have to add extra placeholders in the template. E.G  'position'=>"SomePos"
    then you have to add  [[Html?pos=SomePos]] somewhere in you template.
 
    @code
    $i->AddHtml (array( 
        'position'=>"BodyLow", 
        'html'=>'<a href="http://www.startpage.de">Startpage, search whithout tracking</a>', 
        'overwrite'=>true
    ));
    $i->AddHtml (array(
        'setname'=>"cookie-warn", 
        'position'=>"BodyTop", 
        'html'=>'<div style="position: absolut top left; whidth: 100%; height: 50px">... Some Cookie Waring Stuff ...</div>'
    ));
    @endcode

    ###Allowed Keys
    @verbatim
    Key         Typ         Description  
    --------------------------------------------------------------------------------------------------
    position    string      The Position where to insert this piece of JS
    html        string      The HTML Text to insert. 
    @endverbatim
    All other Keys are the default ones you find in the class description.

    For good setmanes pleass try to use the lowercase version of what this thing is.
    @verbatim    
    footer
    cookie-warning
    ... 
    @endverbatim

    ###The default Positions for insert Html are listed here. But you can define your own if you like.
    @verbatim BodyTop, BodyLow @endverbatim

    @param array $Content
        The array that defines an entry. 

    @retval boolean/string
        Returns false on success, and an error message on failure. 
    */

    public function AddHtml ($Content){
          // setname is used for identification of double entries , set to uniqid() if empty
        if (isset($Content['setname']) and $Content['setname']!="") $SetName=$Content['setname'];
        else                                                        $SetName=uniqid();
        // no longer needed
        unset ($Content['setname']);
        
        // check for setnames that are alreasdy set if overwrite === false
        if (isset($Content['overwrite']) and $Content['overwrite']===false and isset($this->Html[$SetName])) {
            return "Cannot add Html, setname($SetName) already in use!";
        }
        // no longer needed
        unset ($Content['overwrite']);
        
        // maybe the entry has the setsave flag
        if (isset($this->Html[$SetName]) and !empty($this->Html[$SetName]['setsave'])) {
            return "Cannot add JS, Another entry whith same setname ($SetName) has the save flag on!";
        }
        
        // Hey its all empty now!!!
        if (empty($Content)) return "Cannot add Html, no content set!";
        
        // The main atributes are empty?
        if (empty($Content['html'])) return "Html Nothing set no html content";

        // Set default position
        if (empty($Content['position'])) $Position="BodyLow";
        else                             $Position=$Content['position'];
          //echo "\n<pre>check:\n"; print_r($Content); echo "</pre>";
        

        // Set the actual entrx to the Html Array
        if (!empty($Content['position'])) $this->Html[$SetName]['position'] = $Position;
        if (!empty($Content['src']))      $this->Html[$SetName]['src'] = $Content['src'];
        if (!empty($Content['script']))   $this->Html[$SetName]['script'] = $Content['script'];
        if (!empty($Content['setsave']))  $this->Html[$SetName]['setsave'] = $Content['setsave'];

        return false;
    }

    /**
    @brief Method to get the Html array for checking or processing.

    You can set a default return value if nothing is found. 

    @param undefined $Default
        You can define a special return var if the Html array is empty.

    @param string $Position
        Set this to only return Scripts whith a certain position set. 
        Default position names can be found in docs to AddHtml().
        $Position="All" returns the full Js array.

    @retval array/undefined 
        Returns the Array of already defined Java Scripts(for a certain position).
    */
    public function GetHtmls ($Position="All", $Default=false){
        if (empty($this->Html)) return $Default;
        if ($Position=="All") return $this->Html;
        $Ret =array();
        foreach  ($this->Html as $SetName=>$J){
            if ($J['position']==$Position) 
                $Ret[$SetName]=$J;
        }
        return $Ret;
    }


    /**
    @brief Method to get the values of a single Html entry named whith "setname"
    The method returns $Default if nothing is found. 

    @param string $SetName 
        Simply the setname used in AddHtml().

    @param undefined $Default
        Whatever you like as a returnvalue if the method does not find a matching entry.

    @retval array/undefined 
        Returns the value of the Html whith this "setname" or $Default if nothing is found.  
    */
    public function GetHtml ($SetName="", $Default=false){
        if (empty($this->Html)) return $Default;
        if ($SetName!="") return $Default;
        if (!empty($this->Js[$SetName])) return $this->Html[$SetName];       
        return $Default;
    }


    /**
    @brief Renders all the Html for a certain position. 

    @code
    $i->RenderHtml();           //renders the default JS  "BodyLow"
    $i->RenderHtml("BodyLow");  //Renders JS for "BodyLow" position. 
    @endcode

    @param string $Position
        Set this to only return Scripts whith a certain position set. 
        Default position names can be found in docs to AddHtml().
        NO $Position="All" !!!

    @param undefined $Default
        Whatever you like as a returnvalue if the method does not find a matching entrys.

    @retval string/undefined 
        Returns the rendered Html for one position or $Default if nothing is found.  

    */   
    public function RenderHtml ($Position="BodyLow", $Default=""){
        // All empty ??
        if (empty($this->Html)) return $Default;  
        
        // sort aout the ones we want to show
        $Ret =array();
        foreach  ($this->Html as $SetName=>$J){
            if ($J['position']==$Position) 
                $Ret[$SetName]=$J;
        }      
        //print_r($Ret);
        
        // none in this position
        if (!count($Ret)) return $Default;  
        
        // Run the render loop if src and script are set , both a rendered.
        $RetVal ="";
        foreach ($Ret as $J) {  
            if (!empty($J['html'])) { 
                $RetVal .= $J['src'];
            }
        }
        return $RetVal;   
    }
    







     /**
    @brief The output filter function that does the actual replacement of the template placeholders.

    @param string $Content 
        The HTML content to filter 

    @retval string 
        The filtered/replaced content.  
    */
    public function Filter($Content) {
            $i=$this;
            // replace all exept those whith a "\" escape character.  
            $Content=preg_replace_callback( 
                '/\[\[(Metas|Title|Css|Js|Html)(?:\?pos\=)?(.*?)\]\]/i',
                function($match) use ($i) { 
                    return call_user_func(array($i, "Render".$match[1]), $match[2]);               
                },
                $Content
            );
            return $Content;
    }
    
    
    /**
    @brief Auto Add function will add all placeholders on autopilot to the Content .
    
    Basically this still is an output filter that simply adds some placeholders to a HTML page. 
    It is based on regexes at this is more error resistant that using a DOM class.

    @param string $Content 
        The HTML content to filter 

    @retval string 
        The filtered/replaced content.  
    */
    public function AddPlaceholder($Content) {

            //  no [[Js?pos=headTop]] present, so add it 
            if (!preg_match("/\[\[Js\?pos\=HeadTop\]\]/i", $Content)) {
                $Content = preg_replace("/<\s*head.*>/iU","$0\n[[Js?pos=HeadTop]]" ,$Content );
            }
            //  no [[Css]] present, so add it 
            if (!preg_match("/\[\[Css\]\]/i", $Content)) {
                $Content = preg_replace("/<\s*head.*>/iU","$0\n[[Css]]" ,$Content );
            }
             //  no [[Metas]] present, so add it 
            if (!preg_match("/\[\[Metas\]\]/i", $Content)) {
                $Content = preg_replace("/<\s*head.*>/iU","$0\n[[Metas]]" ,$Content );
            }          
            //  no [[Title]] present, so add it 
            if (!preg_match("/\[\[Title\]\]/i", $Content)) {
                $Content = preg_replace("/<\s*head.*>/iU","$0\n[[Title]]" ,$Content );
            }
            
            
            //  no [[Js?pos=HeadLow]] present, so add it 
            if (!preg_match("/\[\[Js\?pos\=HeadLow\]\]/i", $Content)) {
                $Content = preg_replace("#<\s*/\s*head\s*>#iU","\n[[Js?pos=HeadLow]]\n$0" ,$Content );
                //$Content = preg_replace("#<\s*/\s*head\s*>#iU","\n[[Js?pos=HeadLow]]\n$0  <!-- $0 -->" ,$Content );
            }
            

            //  no [[Html?pos=BodyTop]] present, so add it 
            if (!preg_match("/\[\[Html\?pos\=BodyTop\]\]/i", $Content)) {
                $Content = preg_replace("/<\s*body.*>/iU","$0\n[[Html?pos=BodyTop]]\n" ,$Content );
            }
            //  no [[Js?pos=BodyTop]] present, so add it 
            if (!preg_match("/\[\[Js\?pos\=BodyTop\]\]/i", $Content)) {
                $Content = preg_replace("/<\s*body.*>/iU","$0\n[[Js?pos=BodyTop]]" ,$Content );
            }
            //  no [[Html?pos=BodyLow]] present, so add it 
            if (!preg_match("/\[\[Html\?pos\=BodyLow\]\]/i", $Content)) {
                $Content = preg_replace("/<\s*\/\s*body\s*>/iU","\n[[Html?pos=BodyLow]]\n$0" ,$Content );
            }
            //  no [[Js?pos=BodyLow]] present, so add it 
            if (!preg_match("/\[\[Js\?pos\=BodyLow\]\]/i", $Content)) {
                $Content = preg_replace("/<\s*\/\s*body\s*>/iU","\n[[Js?pos=BodyLow]]\n$0" ,$Content );
            }

 

            
            
            
            
            return $Content;
    }
}    


