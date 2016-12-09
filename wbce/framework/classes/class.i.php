<?php
/**
@file
@brief This file holds the Static facade for the Insert class and provides global availability \n
       For more information about how to use Insert in WB look into the class documentation of this faceadeclass I(facade).

@author Norbert Heimsath for the WBCE Project
@copyright http://www.gnu.org/licenses/lgpl.html   (GNU LGPLv2 or any later) 
@version 1.0.0-alpha.2 


The basic idea is to have global available methods that allow any script whithin Websitbaker CE
to register Metadata, Javascript and Stylesheets, that are later displayed in the Websitetemplate.
The actual insertion is done via outputfilter placeholders (Droplets [[Css]], [[Js]]....)

For more information and a decent "how to use" look into the detailed description of class I(facade).
*/


/**
@brief This Class is the static facade for the insert class and provides global availability. \n
If you want to know how to insert scripts, metas and css whithin Websitebaker CE look here.

As this class is a static facade for the insert class so we need to provide it whith a Instance to call on. 
But there is no problem if you manual initialize insert again for some other purpose(subtemplating for example). 
So i choose to not make it a singleton pattern. Later this even gets a static selector for multiple instances like 
selecting a  Database in [ReadbeanPHP](http://www.redbeanphp.com/).

How to use class I on Websitebaker CE
========================================

Using class I in WBCE is done in 2 Steps:
-# Registration of Scripts 
-# Adding placeholders to Template



##Registration

Registration of scripts, metas....and so on is done by by simply calling a static method for each type of script. 
Here we have some registration examples, more options and examples can be found in the documentation of each method:

@code

//Meta
I::AddMeta(array (
    "setname"=>"charset",
    "charset"=>"ISO-8859-1"
));

//CSS
I::AddCss (array(
    'setname'=>"font-awesome", 
    'href'   =>"https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css", 
    'media'  =>"screen"
));
I::AddCss (array(
    'setname'=>"local-script2", 
    'style'  =>"#htto {position:absolute}"
));

//Js
I::AddJs (array(
    'setname'=>"jquery", 
    'position'=>"BodyLow", 
    'src'=>"https://code.jquery.com/jquery-2.1.4.min.js"
));
I::AddJs (array(
    'setname'=>"test2", 
    'position'=>"HeadLow", 
    'src'=>WB_URL."modules/colorbox/js/colorbox.min.js", 
    'script'=>"jQuery('a.gallery').colorbox();" 
));
@endcode 

###Global Keys

AddMetas, AddCss and AddJs got some special keys that are used to provide at least some functionality to 
allow mamagement of double entries. Those are the same in all Add... methods except AddTitle.
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

###Additional Registration Examples

####setname

@code
I::AddCss (array(
    'setname'=>"font-awesome", 
    'href'   =>"https://maxcdn.bootstrapcdn.com/font-awesome/3.3.0/css/font-awesome.min.css", 
    'media'  =>"screen"
));
I::AddCss (array(
    'setname'=>"font-awesome", 
    'href'   =>"https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css", 
    'media'  =>"screen"
));
@endcode
If two entries share the same setname the second overwrites the first. so only the second will be rendered.

####overwrite

@code
I::AddCss (array(
    'setname'=>"font-awesome", 
    'href'   =>"https://maxcdn.bootstrapcdn.com/font-awesome/3.5.0/css/font-awesome.min.css", 
    'media'  =>"screen"
));
I::AddCss (array(
    'setname'=>"font-awesome", 
    'href'   =>"https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css", 
    'media'  =>"screen",
    'overwrite'=>false
));
@endcode
If you do not want the above behavior, you can add 'overwrite'=>false and instead of overwriting an 
already existing entry I::AddCss() only returns an error message if an entry whith same setname already exits.



####setsave

@code
I::AddCss (array(
    'setname'=>"font-awesome", 
    'href'   =>"https://maxcdn.bootstrapcdn.com/font-awesome/3.5.0/css/font-awesome.min.css", 
    'media'  =>"screen",
    'setsave'=>true
));
I::AddCss (array(
    'setname'=>"font-awesome", 
    'href'   =>"https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css", 
    'media'  =>"screen"
    
));
@endcode
To ensure your never entry is overwritten you can add the 'setsave'=>true flag and the system will never overwrite this entry.

@attention More examples can be found in the documentation for the specific method. 

##Template

There are a some placeholders that need to be added to the template. 
This replaces the old frontend functions. The old frontend functions are moved into a module. 
And we add a compatibility function that imports the old files into the new system. 
So old modules will still run whith the new system. 
@verbatim
[[Metas]]
[[Title]]
[[Css]]
[[Js?pos=HeadTop]]
[[Js?pos=HeadLow]]
[[Js?pos=BodyTop]]
[[Js?pos=BodyLow]]
@endverbatim


An example template may look like this:

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
    <!-- Sichtbarer Dokumentinhalt im body -->
    <p>Sehen Sie sich den Quellcode dieser Seite an.
      <kbd>(Kontextmenu: Seitenquelltext anzeigen)</kbd></p>
[[Js?pos=BodyLow]]
</body>
</html>
@endcode

@todo I need to add a manager use different instances like in ReadbeanPHP. 


*/
class I {

    /** 
    @brief The recent instance of Insert Class 

    later this will get a management for multiple instances 
    like managing multiple DBs in ReadbeanPhp
    @var instance $Instance
    */
    private static $Instance="nope"; 
    
    /**
    @brief Create a new instance of Insert if not already one. Store the object in static var.

    This would be the constructor if this wasn't a static facade class.
    */
    private static function getInstance(){
        if (I::$Instance=="nope") {
            I::$Instance=new Insert();
        } 
        return I::$Instance;
    } 

    
   
    ///// Title features/////   

    /**
    @brief Adds a value to the title var, default behavior is to append the value 
    to an existing value. 

    If $Overwrite is set to true the old value will be replaced
    by the new value. 
     
    @param string $Title
    @param boolean $Overwrite 
    @return boolean false on sucess| string error message on failure
    
    ####Example

    @code
    I::AddTitle ("My Cool Website");  
    //renders as "\t<title>My Cool Website</title>"

    I::AddTitle (" - Page Introduction");  
    //renders as "\t<title>My Cool Website - Page Introduction</title>"

    I::AddTitle ("A far mor important title", true);  
    //now renders as "\t<title>A far mor important title</title>"
    @endcode
    */
    public static function AddTitle ($Title="", $Overwrite=false){
        $i=I::getInstance();
        return $i->AddTitle ($Title, $Overwrite);
    }


    /**
    @brief Returns the recent title or $Default, if title is not found or empty.
    
    Use it for debugging or doing complex processimg whith the title.
    
    @param undefined $Default 
    @return returns $Default on failure and the title as string if title is found.
    */    
    public static function GetTitle ($Default=false){
        $i=I::getInstance();
        return $i->GetTitle ($Default);
    }
    


    /**
    @brief Renders the title and returns it. Returns $Default if title is not set.

    ####Example

    @code
    I::RenderTitle("\t<title>No Title</title>");
    // renders : \t<title>No Title</title>

    I::AddTitle('The Websitetitle');
    I::RenderTitle("\t<title>No Title</title>");
    // renders :  \t<title>The Websitetitle</title>
    @endcode

    @param undefined $Default 
    @retval string 
        the full redered HTML title tag 
    */
    public static function RenderTitle($Default="<title>No Title</title>"){
       $i=I::getInstance();
       return $i->RenderTitle ($Default);
    }
    

    ///// Metas features /////

    /**
    @brief Allows to add additional Metas to the Head of the Template.
    
    Expects an array to be given as metas got a countless number 
    of attributes the array may contain anything you like.
    @code
    //
    $i->AddMeta(array (
        "setname"=>"description", 
        "name"=>"description", 
        "content"=>"This is a nice description"
    ));
    // <meta name="description" content="This is a nice description" />

    
    $i->AddMeta(array (
        "setname"=>"keywords",
        "name"=>"keywords", 
        "content"=>"Dieses, Beschreibung, Was auch immer"
    ));
    // <meta name="keywords" content="Dieses, Beschreibung, Was auch immer" />

    // In an international enviroment
    $i->AddMeta(array (
        "setname"=>"keywords_en", 
        "name"=>"keywords", 
        "content"=>"This, Description, Something", 
        "lang"=>"en"
    ));
    $i->AddMeta(array (
        "setname"=>"keywords_de", 
        "name"=>"keywords", 
        "content"=>"Dieses, Jenes, Welches, Irgendwas", 
        "lang"=>"de" 
    ));
    // <meta name="keywords" content="This, Description, Something" lang="en" />    
    // <meta name="keywords" content="Dieses, Jenes, Welches, Irgendwas" lang="de" />    

    // Robots
    $i->AddMeta(array (
        "name"=>"robots", 
        "content"=>"noindex,nofollow" 
    ));

    // Cache controll
    $i->AddMeta(array (
        "setname"=>"expires", 
        "http-equiv"=>"expires", 
        "content"=>"43200"
    ));

    // Redirect
    $i->AddMeta(array (
        "setname"=>"refresh", 
        "http-equiv"=>"refresh", 
        "content"=>"0;url=http://www.domain.de/"
    ));
    // <meta http-equiv="refresh" content="0;url=http://www.domain.de/">

    // charset
    $i->AddMeta(array (
        "setname"=>"charset", 
        "charset"=>"ISO-8859-1" 
    ));
    // depending on render method this will produce very different output
    // html : <meta http-equiv="content-type" content="text/html; charset=utf-8">
    // xhtml: <meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
    // html5: <meta charset="utf-8">  

    @endcode 
    
    @param array $Content 
    This is always an array whith all atributes stored as "key" => "value" see example above. 

    All other parameters are simple piped to the meta output.

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
    charset      charset="utf-8" for example
    expires      http-equiv="expires"
    refresh      http-equiv="refresh"
    @endverbatim    

    @retval boolean/string
    Returns false on success, and an error message on failure. 

    */
    public static function AddMeta ($Content){ 
        $i=I::getInstance();
        return $i->AddMeta ($Content);
    }
    



    /**
    @brief Method to get the metas array for checking or processing.
    
    You can set a default return value if nothing is found. 

    @param undefined $Default
    You can define a special return var if the meta array is empty.

    @retval array/undefined
    Returns the Array of already defined Metas.
    */
    public static function GetMetas ($Default=false) {
        $i=I::getInstance();
        return $i->GetMetas ($Default);    
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
    public static function GetMeta ($SetName="", $Default=false) {
        $i=I::getInstance();
        return $i->GetMeta ($SetName, $Default);    
    }


    /**
    @brief Rendering the Metas dependent on defined rendering method(html, html5, xhtml). 

    Returns $Default if nothing is set.  
    Especially having set "charset" will get very different results depending on render method.
    having set charset also stops the rendering of other fields

    @param undefined $Default
    Whatever you like as a returnvalue if the meta array is empty.

    @retval string 
    All metas rendered are returned as a string.

    */
    public static function RenderMetas ($Default=""){
        $i=I::getInstance();
        return $i->RenderMetas($Default);
    }
    

    ///// CSS features /////


    /**
    @brief Method to add CSS entries to the template via a storage array. 

    Compared to the AddMeta() this on got a fixed complement of available keys, but basic functionality is the same.

    @code
    // adding font awesome
    I::AddCss (array(
        'setname'=>"font-awesome", 
        'href'   =>"https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css", 
        'media'  =>"screen"
    ));
    // adding some direct style commands
    I::AddCss (array(
        'setname'=>"superheadline", 
        'style'  =>"h1 #htto {position:absolute}"
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
    All other Keys are only the default ones you find in the class description(setname,overwrite,setsave..).

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
    public static function AddCss ($Content){
        $i=I::getInstance();
        return $i->AddCss($Content);
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
         if (!isset($Content['setname']) OR empty ($Content['setname']))
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
    public static function GetCss($Default=false){
        $i=I::getInstance();
        return $i->GetCss($Default);    
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
    public static function GetCs($SetName="", $Default=false){
        $i=I::getInstance();
        return $i->GetCss($SetName, $Default);    
    }

    /**
    @brief Renders all the CSS entries saved in the array.
    
    Dependent on defined rendering method(html, html5, xhtml).

    @param undefined $Default
    You can define a special return var if the CSS array is empty.

    @retval string/undefined 
    Renders all of already defined CSS or $Default if empty
    */
    public static function RenderCss ($Default=""){
        $i=I::getInstance();
        return $i->GetCss($Default);        
    }

    
    ///// JS features /////


    /**
    @brief Method to add Js entries to the JS array. Compared to the AddMeta() 
    this on got a fixed complement of available keys, but basic functionality is the same.

    @code
    I::AddJs (array(
        'setname'=>"myalert", 
        'position'=>"HeadLow", 
        'script'=>"var hier='Das ist ein Alert'; alert(hier);", 
        'overwrite'=>true
    ));
    I::AddJs (array(
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

    ###The default positions for insert JS are. 
    @verbatim HeadTop, HeadLow, BodyTop, BodyLow @endverbatim
    But you can define your own positions if you like.

    @param array $Content
    The array that defines an entry. 

    @retval boolean/string
    Returns false on success, and an error message on failure. 
    */
    public static function AddJs ($Content){
        $i=I::getInstance();
        return $i->AddJs($Content);
    }
    

    /**
    @brief Method to delete a named JS  entry.
    
    Simply remove an entry by its SetName 
    ~~~~~~~~~~
        I::DelJs ($SetName="SetNameOfYourEntry");
    ~~~~~~~~~~
    @param string $SetName
        The setname for the entry to delete 
    @retval boolean/string
    Returns false on success, and an error message on failure. 

    */
    public static function DelJs ($SetName=""){
        $i=I::getInstance();
        return $i->DelJs($SetName);
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
    public static function GetJs($Position="All", $Default=false){
        $i=I::getInstance();
        return $i->GetJs($Position, $Default);    
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
    public static function GetJ($SetName="", $Default=false){
        $i=I::getInstance();
        return $i->GetJ($SetName, $Default);    
    }

    /**
    @brief Renders all the JS for a certain position.(e.g. HeadTop, HeadLow, BodyTop, BodyLow...) 

    The prefered render method is taken into account.\n
    Returns $Default if array is empty.    

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

    public static function RenderJs ($Position="HeadLow", $Default=""){
        $i=I::getInstance();
        return $i->GetJs($Default);        
    }

    ///// Filter features /////


    /**
    @brief The output filter function that does the actual replacement of the template placeholders.

    In Websitebaker (CE) you do not need to take care of this as this is loaded by the default output filters. 
    Although the method needs to be public for outputfilter call.   

    @param string $Content 
    The HTML content to filter 

    @retval string 
    The filtered/replaced content.  
    */    
    public static function Filter($Content) {
        $i=I::getInstance();
        return $i->Filter($Content);
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
    public static function AddPlaceholder($Content) {
        $i=I::getInstance();
        return $i->AddPlaceholder($Content);
    }

}

