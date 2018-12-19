<?php
/**
 * @brief     This file contains class Insert which is usually used 
 *            in conjunction whith class I as a facade class.
 *            It's a class for insertion of Meta Tags, Title, 
 *            JavaScript, CSS and HTML code into the DOM of the site.
 *            It was created to work with the WBCE CMS but could be used 
 *            in other systems aswell, when some additional changes are given.
 * 
 * @author    Norbert Heimsath for the WBCE Project
 *            Initial code and work on both classes (I and Insert).
 * @author    Christian M. Stefan <stefek@designthings.de>
 *            adaptation and code expansion for WBCE 1.3.x
 * 
 * @copyright http://www.gnu.org/licenses/lgpl.html (GNU LGPLv2 or any later) 
 */

defined('INSERT_FRESH_FILES') or define('INSERT_FRESH_FILES', true);

class Insert {

    /**
     * @brief Queue array for JavaScript insertions   
     * @var   array $_JsQueue
     */
    private $_JsQueue = array();

    /**
     * @var   array   $_CssQueue
     */
    private $_CssQueue = array();

    /**
     *  @var   array  $_MetaQueue
     */
    private $_MetaQueue = array();

    /**
     * @var   array   $_HtmlQueue
     */
    private $_HtmlQueue = array();

    /**
     * @var   string  $_TitleQueue
     */
    private $_TitleQueue = NULL;

    /**
     * @brief Determines the prefered Rendering method  
     *        Possible values are "html", "xhtml", "html5"
     * @var   string  $sRenderType
     */
    public $sRenderType = "html5";

    /**
     *  @brief contains an array of str_replace URL Tokens to use when setting URLs
     *  @var   array  $aUrlTokens
     */
    public $aUrlTokens = array();

    public function __construct() 
    {
        if (defined('WB_RENDER'))
            $this->sRenderType = WB_RENDER;
        $this->aUrlTokens = $this->replacementTokens();
    }

    /**
     * @brief  Adds a string to the _TitleQueue. 
     *         Default behavior is to append the value to an existing value.
     * 
     * @param  string    $sTitle
     * @param  boolean   $bOverwrite If $bOverwrite is set to true the old value will                         
     *                   be overwritten by the new value replaced. 
     * @return boolean   false on sucess| string error message on failure
     */
    public function insertTitle($sTitle = "", $bOverwrite = false) 
    {
        if ($sTitle == "") {
            return "Title empty!";
        }

        if ($bOverwrite) {
            $this->_TitleQueue = $sTitle;
        } else {
            $this->_TitleQueue .= $sTitle;
        }
        return false;
    }

    /**
     * @brief  insert a JS File to DOM and place it at the given position ($sDomPos)
     * 
     * @param  unspec  $uFileLoc may be a single file loc or an array with file locations
     * @param  string  $sDomPos
     * @param  string  $sID
     * @return 
     */
    public function insertJsFile($uFileLoc = '', $sDomPos = 'BODY BTM-', $sID = false) 
    {
        if (!is_array($uFileLoc)) {
            // single file
            if ($uFileLoc != '') {
                $aSetting = array();
                if (is_string($sID))
                    $aSetting['setname'] = $sID;

                $aSetting['src']      = $uFileLoc;
                $aSetting['position'] = $sDomPos;
                $this->addJS($aSetting);
            } else {
                return;
            }
        } else {
            // array of files
            foreach ($uFileLoc as $sLoc) {
                $aSetting = array();
                $aSetting['src']      = $sLoc;
                $aSetting['position'] = $sDomPos;
                $this->addJS($aSetting);
            }
        }
        return false;
    }

    /**
     * @brief  insert a CSS File to DOM and place it at the given position ($sDomPos)
     * 
     * @param  unspec  $uFileLoc may be a single file loc or an array with file locations
     * @param  string  $sDomPos
     * @param  string  $sID
     * @return 
     */
    public function insertCssFile($uFileLoc = '', $sDomPos = 'HEAD BTM+', $sID = false) 
    {
        if (!is_array($uFileLoc)) {
            // single file      
            if ($uFileLoc != '') {
                $aSetting = array();
                if (is_string($sID))
                    $aSetting['setname'] = $sID;
                $aSetting['href']     = $uFileLoc;
                $aSetting['position'] = $sDomPos;
                $this->addCSS($aSetting);
            } else {
                return;
            }
        } else {
            // array of files
            foreach ($uFileLoc as $sLoc) {
                $aSetting = array();
                $aSetting['href']     = $sLoc;
                $aSetting['position'] = $sDomPos;
                $this->addCSS($aSetting);
            }
        }
        return false;
    }

    /**
     * @brief  insert CSS Code to DOM and place it at the given position ($sDomPos)
     * 
     * @param  unspec  $sCode    the CSS Code; it's not needed (nor advised) to 
     *                           surround the code with the 
     *                           <style> and </style> tags but it's allowed    
     * @param  string  $sDomPos
     * @param  string  $sID
     * @return 
     */
    public function insertCssCode($sCode = '', $sDomPos = 'HEAD BTM+', $sID = '') 
    {
        return $this->addCss(array(
                    'setname'  => $sID,
                    'position' => $sDomPos,
                    'style'    => $sCode,
        ));
    }

    /**
     * @brief  insert JS Code to DOM and place it at the given position ($sDomPos)
     * 
     * @param  unspec  $sCode    the JS Code; it's not needed (nor advised) 
     *                           to surround the code with the 
     *                           <script> and </script> tags but it's allowed
     * @param  string  $sDomPos
     * @param  string  $sID
     * @return 
     */
    public function insertJsCode($sCode = '', $sDomPos = 'HEAD BTM-', $sID = '') 
    {
        return $this->addJs(array(
                    'setname'  => $sID,
                    'position' => $sDomPos,
                    'script'   => $sCode,
        ));
    }

    /**
     * @brief  insert HTML Code to DOM and place it at the given position ($sDomPos)
     *         The HTML Code can of course consist of JavaScript aswell, but it's 
     *         advised to use insertJsCode for that case
     * 
     * @param  unspec  $sCode   
     * @param  string  $sDomPos
     * @param  string  $sID
     * @return 
     */
    public function insertHtmlCode($sCode = '', $sDomPos = 'BODY TOP+', $sID = '') 
    {
        return $this->addHtml(array(
                    'html'     => $sCode,
                    'position' => $sDomPos,
                    'setname'  => $sID,
        ));
    }

    /**
     * @brief    Allows to add additional Metas to the Head of the Template.
     *           Expects an array to be given as metas got a countless number 
     *           of attributes the array may contain anything you like.
     * 
     * Example usage:
     * 
     * $i->addMeta(array (    
     *    "setname" => "description",     
     *    "name"    => "description",     
     *    "content" => "This is a nice description"    
     * ));    
     * 
     * // <meta name="description" content="This is a nice description" /> 
     *  
     * $i->addMeta(array (    
     *    "setname" => "keywords_de",     
     *    "name"    => "keywords",    
     *    "content" => "Dieses, Jenes, Welches, Irgendwas",
     *    "lang"    => "de"    
     * ));  
     * // <meta name="keywords" content="Dieses, Jenes, Welches, Irgendwas" lang="de" />  
     *   
     * // Redirect    
     * $i->addMeta(array (    
     *    "setname"    => "refresh",     
     *    "http-equiv" => "refresh",    
     *    "content"    => "0;url=http://www.domain.de/"    
     * ));   
     * // <meta http-equiv="refresh" content="0;url=http://www.domain.de/">
     * 
     * // charset   
     * $i->addMeta(array (    
     *    "setname" => "charset",    
     *    "charset" => "ISO-8859-1"
     * ));
     *    
     * // depending on render method (html|xhtml|html5) this will produce different outputs:  
     * // html : <meta http-equiv="content-type" content="text/html; charset=utf-8">    
     * // xhtml: <meta http-equiv="content-type" content="text/html; charset=utf-8" />     
     * // html5: <meta charset="utf-8">  
     *     
     * @param array $aData   This is always an array whith all atributes stored 
     *                       as "key" => "value" see example above.
     *                       All other parameters are simple piped to the meta output. 
     *                       There are quite a few attributes that schould be used as 
     *                       setname so other Scripts can interact whith those correctly:
     * 
     *                       --------------|-------------------------------------   
     *                         setname     | function 
     *                       --------------|-------------------------------------
     *                         author      | name="author" content="Author Name"    
     *                         description | name="description" ....    
     *                         keywords    | name="keywords".....     
     *                         date        | name="date"    
     *                         robots      | name="robots"   
     *                         charset     | charset="utf-8" for example   
     *                         expires     | http-equiv="expires"   
     *                         refresh     | http-equiv="refresh"
     *                       --------------|-------------------------------------
     * 
     * @return bool/string   Returns false on success, or an error message on failure.      
     */
    public function addMeta($aMetaData = array()) 
    {
        $aErrors = array();
        // check for invalid input
        if (!is_array($aMetaData))
            $aErrors[] = "addMeta() expects an array as parameter!";

        $sSetName = $this->_computeFileID($aMetaData);

        // check for setnames that are alreasdy set if overwrite === false
        if (isset($aMetaData['overwrite']) and $aMetaData['overwrite'] === false and isset($this->_MetaQueue[$sSetName]))
            $aErrors[] = "Cannot add Meta, setname already in use!";

        // maybe the entry has the setsave flag
        if (isset($this->_MetaQueue[$sSetName]) and ! empty($this->_MetaQueue[$sSetName]['setsave']))
            $aErrors[] = "Cannot add Meta, Another entry whith same name ($sSetName) has the save flag on!";

        // Hey its all empty now!!!
        if (empty($aMetaData))
            $aErrors[] = "Cannot add meta, no content set!";

        if (!empty($aErrors)) {
            $sErrMsg = '';
            foreach ($aErrors as $msg)
                $sErrMsg .= $msg . '<br />';
            // Stefek: We need to write this into the JS Console for convenience
            return $sErrMsg;
        }

        $sDomPos = 'HEAD+';
        if (isset($aMetaData['name'])) {
            switch ($aMetaData['name']) {
                case 'keywords':
                    $sDomPos = 'KEY+';
                    break;
                case 'description':
                    $sDomPos = 'DESC+';
                    break;
                default:
                    $sDomPos = 'HEAD+';
            }
        }

        $aMetaData['position'] = $sDomPos;

        $this->_MetaQueue[$sSetName] = $aMetaData;
        unset($aMetaData); // no longer needed        
        return false;
    }

    /**
     * @brief   Method to add Html entries to the Html array. 
     *          addHtml is for adding plain HTML to a set of predifined places.
     *          (BODY TOP+, BODY BTM-). You even can define your own locations but then 
     *          you have to add extra placeholders in the template. E.G  'position'=>"SomePos"
     *          then you have to add  [[Html?pos=SomePos]] somewhere in you template.
     *  
     * @code
     *  $i->addHtml (array( 
     *    'position'  => 'BODY BTM-', 
     *    'html'      => '<div class="class">Your Content</div>', 
     *    'overwrite' => true
     * ));
     * $i->addHtml (array(
     *    'setname'  => "cookie-warn", 
     *    'position' => 'BODY TOP+', 
     *    'html'     => '<div style="whidth: 100%; height: 50px">... Some Message ...</div>'
     * ));
     * @endcode
     * 
     * ###Allowed Keys
     * @verbatim
     * Key         Typ         Description  
     * --------------------------------------------------------------------------------------------------
     * position    string      The Position where to insert the HTML/CODE
     * html        string      The HTML Text/Code to insert 
     * @endverbatim
     * 
     * All other Keys are the default ones you find in the class description.
     * For good setnames it's recommended to use lowercase version of what this thing is.
     * @verbatim    
     * footer
     * cookie-warning
     * ... 
     * @endverbatim
     * 
     * ###The default Positions for insert Html are listed here.
     * @verbatim BODY TOP+, BODY BTM- @endverbatim
     * 
     * @param  array $aData  The array that defines an entry. 
     * @retval bool/string   Returns false on success, and an error message on failure. 
     * */
    public function addHtml($aData) 
    {
        $aErrors = array();
        $sSetName = $this->_computeFileID($aData);

        // check for setnames that are alreasdy set if overwrite === false
        if (isset($aData['overwrite']) and $aData['overwrite'] === false and isset($this->_HtmlQueue[$sSetName]))
            $aErrors[] = "Cannot add Html, setname($sSetName) already in use!";

        // maybe the entry has the setsave flag
        if (isset($this->_HtmlQueue[$sSetName]) and ! empty($this->_HtmlQueue[$sSetName]['setsave']))
            $aErrors[] = "Cannot add JS, Another entry whith same setname ($sSetName) has the save flag on!";

        // Hey its all empty now!!!
        if (empty($aData))
            $aErrors[] = "Cannot add Html, no content set!";

        // The main atributes are empty?
        if (empty($aData['html']))
            $aErrors[] = "Html Nothing set, no html content";

        if (!empty($aErrors)) {
            $sErrMsg = '';
            foreach ($aErrors as $msg)
                $sErrMsg .= $msg . '<br />';
            // Stefek: We need to write this into the JS Console for convenience
            return $sErrMsg;
        }

        // Set the actual entry to the Html Array        
        if (!empty($aData['setsave']))
            $this->_HtmlQueue[$sSetName]['setsave'] = $aData['setsave'];
        if (!empty($aData['src']))
            $this->_HtmlQueue[$sSetName]['src'] = $aData['src'];
        if (!empty($aData['html']))
            $this->_HtmlQueue[$sSetName]['html'] = $aData['html'];

        $this->_HtmlQueue[$sSetName]['position'] = empty($aData['position']) ? "BODY BTM-" : $aData['position'];

        unset($aData);
        return false;
    }

    /**
     * @brief   Method to add CSS entries to the DOM via a storage array.
     *  
     * Example code:
     * // adding font awesome
     * $i->addCss (array(
     *    'setname' => "font-awesome", 
     *    'href'    => "https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css",
     *    'media'   => "screen"
     * ));
     * 
     * // adding some direct style commands
     * $i->addCss (array(
     *    'setname'=>"superheadline", 
     *    'style'  =>"h1#htto {position: absolute;}"
     * ));
     * 
     * ---------|----------|-----------------------------------------------
     *   Key    |  Typ     |  Description 
     * ---------|----------|-----------------------------------------------
     *   href   |  string  |  Simply the full URL where to load the Script
     *   style  |  string  |  The plain style definitions to insert.
     *   media  |  string  |  The "media" attribute for stylesheets.
     * ---------|----------|----------------------------------------------- 
     * 
     * 
     * @param  array $aData     The array that defines an entry. 
     * @return bool/string   Returns false on success, and an error message on failure. 
     */
    public function addCss($aFileData) 
    {
        $aErrors = array();
        $sSetName = $this->_computeFileID($aFileData);

        // check for setnames that are alreasdy set if overwrite === false
        if (isset($aFileData['overwrite']) and $aFileData['overwrite'] === false and isset($this->_MetaQueue[$sSetName]))
            $aErrors[] = "Cannot add CSS, setname($sSetName) already in use!";

        // maybe the entry has the setsave flag
        if (isset($this->_MetaQueue[$sSetName]) and ! empty($this->_MetaQueue[$sSetName]['setsave']))
            $aErrors[] = "Cannot add CSS, Another entry whith same name ($sSetName) has the save flag on!";

        // Hey its all empty now!!!
        if (empty($aFileData))
            $aErrors[] = "Cannot add CSS, no content set!";

        // The main atributes are empty?
        if (empty($aFileData['href']) and empty($aFileData['style']))
            $aErrors[] = "CSS Nothing set no href no style";

        if (!empty($aErrors)) {
            $sErrMsg = '';
            foreach ($aErrors as $msg)
                $sErrMsg .= $msg . '<br />';
            // Stefek: We need to write this into the JS Console for convenience
            return $sErrMsg;
        }

        // href gets some special threatment so you can have an always refreshed browser cache
        // by changing the file URL whith a get parameter
        if (!empty($aFileData['href']))
            $this->_CssQueue[$sSetName]['href'] = $this->_checkAndReplaceFileLoc($aFileData['href']);

        if (!empty($aFileData['setsave']))
            $this->_CssQueue[$sSetName]['setsave'] = $aFileData['setsave'];
        if (!empty($aFileData['style']))
            $this->_CssQueue[$sSetName]['style'] = $aFileData['style'];
        if (!empty($aFileData['title']))
            $this->_CssQueue[$sSetName]['title'] = $aFileData['title'];
        if (!empty($aFileData['media']))
            $this->_CssQueue[$sSetName]['media'] = $aFileData['media'];
        $this->_CssQueue[$sSetName]['position'] = empty($aFileData['position']) ? "HEAD BTM+" : $aFileData['position'];

        unset($aFileData); // no longer needed        
        return false;
    }

    /**
     * @brief   Method to add Js entries to the JS array. Compared to the addMeta() 
     *          this one got a fixed complement of available keys, but basic functionality is the same.
     * 
     * Example usage 
     * $i->addJs (array(    
     *     'setname'   => "myalert",     
     *     'position'  => "HEAD BTM+",     
     *     'script'    => "var msg='This is the alert message'; alert(msg);",     
     *     'overwrite' =>  true   
     * ));  
     *   
     * $i->addJs (array(   
     *      'setname'  => "jquery",    
     *      'position' => "BODY TOP-",    
     *      'src'      => "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"   
     * ));
     * 
     * ###Allowed Keys
     * 
     * ----------|----------|--------------------------------------------------
     *  Key      |   Type   |   Description  
     * ----------|----------|--------------------------------------------------
     * position  |  string  |   The Position where to insert this piece of JS
     * script    |  string  |   The scritp to insert. 
     * src       |  string  |   Simply the full URL where to load the Script    
     * ----------|----------|--------------------------------------------------
     * 
     * 
     * @param  array $aData   The array that defines an entry.    
     * @return bool/string    Returns false on success, and an error message on failure. 
     */
    public function addJs($aFileData) 
    {
        $aErrors = array();
        $sSetName = $this->_computeFileID($aFileData);

        // check for setnames that are alreasdy set if overwrite === false
        if (isset($aFileData['overwrite']) and $aFileData['overwrite'] === false and isset($this->_JsQueue[$sSetName]))
            $aErrors[] = "Cannot add JS, setname($sSetName) already in use!";

        // maybe the entry has the setsave flag
        if (isset($this->_JsQueue[$sSetName]) and ! empty($this->_JsQueue[$sSetName]['setsave']))
            $aErrors[] = "Cannot add JS, Another entry whith same setname ($sSetName) has the save flag on!";

        // Hey its all empty now!!!
        if (empty($aFileData))
            $aErrors[] = "Cannot add JS, no content set!";

        // The main atributes are empty?
        if (empty($aFileData['src']) and empty($aFileData['script']))
            $aErrors[] = "JS Nothing set no src no script";

        if (!empty($aErrors)) {
            $sErrMsg = '';
            foreach ($aErrors as $msg)
                $sErrMsg .= $msg . '<br />';
            // Stefek: We need to write this into the JS Console for convenience
            return $sErrMsg;
        }

        if (!empty($aFileData['src']))
            $this->_JsQueue[$sSetName]['src'] = $this->_checkAndReplaceFileLoc($aFileData['src']);

        if (!empty($aFileData['script']))
            $this->_JsQueue[$sSetName]['script'] = $aFileData['script'];

        if (!empty($aFileData['setsave']))
            $this->_JsQueue[$sSetName]['setsave'] = $aFileData['setsave'];

        $this->_JsQueue[$sSetName]['position'] = empty($aFileData['position']) ? "HEAD BTM+" : $aFileData['position'];

        unset($aFileData); // no longer needed
        return false;
    }

    /**
     * @brief  Processes the _TitleQueue when using the doFilter Method. 
     * 
     * @return string
     */
    private function _processTitle() 
    {
        return (!empty($this->_TitleQueue)) ? "\t<title>" . $this->_TitleQueue . "</title>" . PHP_EOL : '';
    }

    /**
     * @brief  Processes the _MetaQueue when using the doFilter Method.
     * 
     * @param  string   $sPosition  Set this to only return Scripts whith a certain position set. 
     * @param  unspec   $uDefault   Whatever you like as a returnvalue if the meta array is empty.
     * @retval string               All metas rendered are returned as a string.
     */
    private function _processMeta($sDomPos, $uDefault = "") 
    {
        if (empty($this->_MetaQueue))
            return $uDefault;

        $sRetVal = "";

        foreach ($this->_MetaQueue as $rec) {
            if ($rec['position'] != $sDomPos)
                continue;
            $sRetVal .= "\t<meta";
            // charset is a special case where output is different on render method
            if (isset($rec['charset']) && $rec['charset'] != "") {
                if ($this->sRenderType == "html5") {
                    $sRetVal .= ' charset="' . $rec['charset'] . '"';
                } else {
                    $sRetVal .= ' http-equiv="content-type" content="text/html; charset=' . $rec['charset'] . '"';
                }
            } else {
                foreach ($rec as $key => $val) {
                    if (in_array($key, array(
                                'setname',
                                'overwrite',
                                'position'
                            )))
                        continue;
                    $sRetVal .= sprintf(' %s="%s"', $key, $val);
                }
            }
            $sRetVal .= (($this->sRenderType == "xhtml") ? ' />' : '>') . PHP_EOL;
        }
        return $sRetVal;
    }

    /**
     * @brief  Processes the _HtmlQueue when using the doFilter Method.  
     *     
     * @param  string    $sPosition  Set this to only return Scripts whith a certain position set.     
     * @param  undefined $uDefault   Whatever you like as a returnvalue if the method does
     *                               not find any matching entry.    
     * @retval string/undefined      Returns the rendered Html for one position. 
     * */
    private function _processHtml($sPosition = "BODY BTM-", $uDefault = "") 
    {

        if (empty($this->_HtmlQueue))
            return $uDefault; // return immediately if nothing set

            
        // sort out the ones we want to show
        $aData = array();
        foreach ($this->_HtmlQueue as $sSetName => $rec) {
            if ($rec['position'] == $sPosition)
                $aData[$sSetName] = $rec;
        }

        // none in this position
        if (!count($aData))
            return $uDefault;

        // Run the render loop; if src and script are set, both are rendered.
        $sRetVal = "";
        foreach ($aData as $rec) {
            if (!empty($rec['html']))
                $sRetVal .= $rec['html'];
        }
        return $sRetVal;
    }

    /**
     * @brief  Processes the _CssQueue when using the doFilter Method.

     * @param  string $sPosition   Set this to only return Scripts whith a certain position set. 
     * @param  unspec $uDefault    Whatever you like as a returnvalue if the method 
     *                             does not find a matching entry.
     * @retval string              Returns the rendered CSS definitions for one position.
     */
    private function _processCss($sPosition = "HEAD BTM+", $uDefault = "") 
    {
        if (empty($this->_CssQueue))
            return $uDefault;

        // sort out the ones we want to show
        $aData = array();
        foreach ($this->_CssQueue as $sSetName => $rec)
            if ($rec['position'] == $sPosition)
                $aData[$sSetName] = $rec;

        $sTPL = "\t" . '<link rel="stylesheet" id="%s" href="%s" type="text/css"%s%s>' . PHP_EOL;
        $sRetVal = "";
        foreach ($aData as $sSetName => $aCssSet) {
            if (!empty($aCssSet['href'])) {
                $sMedia = (!empty($aCssSet['media'])) ? 'media="' . $aCssSet['media'] . '"' : '';
                $sClosing = ($this->sRenderType == "xhtml") ? '/' : '';
                $sRetVal .= sprintf($sTPL, $sSetName, $aCssSet['href'], $sMedia, $sClosing);
                if (strpos($aCssSet['href'], '#missing') !== false) {
                    $sRetVal = str_replace('#missing#', '', $sRetVal);
                    $sRetVal = "<!-- Missing File was set: " . PHP_EOL . "\t" . $sRetVal . " -->";
                }
            }
            if (!empty($aCssSet['style'])) {
                if (strpos('<style', $aCssSet['style']) !== false) {
                    $sRetVal .= $aCssSet['style'] . PHP_EOL;
                } else {
                    $sRetVal .= "\t<style id=\"" . $sSetName . "\" type=\"text/css\" ";
                    if (!empty($aCssSet['media'])) {
                        $sRetVal .= 'media="' . $aCssSet['media'] . '"';
                    }
                    $sRetVal .= ">" . PHP_EOL;
                    $sRetVal .= $aCssSet['style'] . PHP_EOL;
                    $sRetVal .= "\t</style>" . PHP_EOL;
                }
            }
        }
        return $sRetVal;
    }

    /**
     * @brief Processes the _JsQueue when using the doFilter Method.
     * 
     * @param  string $sPosition   Set this to only return Scripts whith a certain position set. 
     * @param  unspec $uDefault    Whatever you like as a returnvalue if the method 
     *                             does not find a matching entry.
     * @retval string              Returns the rendered Javascript definitions for one position.
     * 
     */
    private function _processJs($sPosition = "HEAD BTM+", $uDefault = "") 
    {
        if (empty($this->_JsQueue))
            return $uDefault;

        // sort out the ones we want to show
        $aData = array();
        foreach ($this->_JsQueue as $sSetName => $rec)
            if ($rec['position'] == $sPosition)
                $aData[$sSetName] = $rec;


        // none in this DOM position
        if (!count($aData))
            return $uDefault;

        $sTPL = "\t" . '<script id="%s" ';
        $sTPL .= ($this->sRenderType != "html5" ? 'type="text/javascript" ' : '');
        $sTPL .= 'src="%s"></script>' . PHP_EOL;

        // Run the render loop if src and script are set , both a rendered.
        $sRetVal = "";
        foreach ($aData as $sSetName => $rec) {
            if (!empty($rec['src'])) {
                $sRetVal .= sprintf($sTPL, $sSetName, $rec['src']);
                if (strpos($rec['src'], '#missing') !== FALSE) {
                    $sRetVal = str_replace('#missing#', '', $sRetVal);
                    $sRetVal = "<!-- Missing File was set: " . PHP_EOL . "\t" . $sRetVal . " -->";
                }
            }
            if (!empty($rec['script'])) {

                if (strpos('<script', $rec['script']) !== false) {
                    $sRetVal .= $rec['script'] . PHP_EOL;
                } else {
                    $sRetVal .= "\t" . '<script id="' . $sSetName . '"';
                    if ($this->sRenderType != "html5") {
                        $sRetVal .= " type=\"text/javascript\"";
                    }
                    $sRetVal .= ">" . PHP_EOL;
                    $sRetVal .= "\t</script>" . PHP_EOL;
                }
            }
        }
        return $sRetVal;
    }

    /**
     * 
     * @param   string $sSetName
     * @param   string $sType
     * @return 
     */
    public function deleteFromQueue($sSetName = "", $sType = "") 
    {
        $sQueue = '_' . $sType . 'Queue';

        if ($sQueue == "_TitleQueue") {
            $this->_TitleQueue = NULL;
            return false;
        }
        if (empty($sSetName))
            return "'del" . $sType . "' can not delete an unknown entry";

        if (!isset($this->$sQueue[$sSetName]))
            return "" . $sQueue . "['" . $sSetName . "'] entry does not exist";

        // maybe the entry has the setsave flag
        if (!empty($this->$sQueue[$sSetName]['setsave']))
            return "'del" . $sType . "' cannot delete " . $sQueue . " Entry , $sSetName has the save flag on!";

        unset($this->$sQueue[$sSetName]);
        return false;
    }

    /**
     * @brief   Compute and return the FileID based on setname/id setting. 
      If not set function will computer the ID based on Fileloc.
      If no Fileloc set, a unique ID will be generated using the uniqid(function)
     * 
     * @param  array $aData
     * @return string
     */
    private function _computeFileID($aData) 
    {
        $sSetName = (isset($aData['setname']) and $aData['setname'] != "") ? $aData['setname'] : '';

        if ($sSetName == '') {
            $sLoc = isset($aData['src']) ? $aData['src'] : (isset($aData['href']) ? $aData['href'] : '');
            $sSetName = ($sLoc != "") ? pathinfo($sLoc, PATHINFO_FILENAME) . '__' .
                    basename(pathinfo($sLoc, PATHINFO_DIRNAME)) : uniqid();
        }
        return str_replace('.', '_', $sSetName);
    }

    /**
     * @brief  Provides a handling to check whether or not the file exists 
     *         on the server (if local) and corrects the URL to the file 
     *         (will use the $this->aUrlTokens property to rewrite the URL)
     * 
     * @param  string  $sFileUrl
     * @param  string  $sCallingPos
     * @return string
     */
    private function _checkAndReplaceFileLoc($sFileUrl = '', $sCallingPos = '') 
    {
        if (strpos($sFileUrl, '{') !== false) {
            // check for {TOKENS} and replace with corresponding URLs    
            $sFileUrl = strtr($sFileUrl, $this->aUrlTokens);
        }

        // check whether the file location is internal or external (off domain)
        $aUrlParts = parse_url($sFileUrl);
        $sUrlHost = isset($aUrlParts["host"]) ? $aUrlParts["host"] : '';
        $bAbsoluteInternalUrl = (strpos($sFileUrl, WB_URL) !== false);

        $sErrorLog = "\t\tconsole.error('Class Insert report: File \"" . $sFileUrl . "\" can not be found')";

        // check if internal file exists and append filemtime 
        // in order to always load the freshest (most recent) version of the file
        if ($bAbsoluteInternalUrl) {
            $sFilePath = str_replace(WB_URL, WB_PATH, $sFileUrl);
            if (file_exists($sFilePath)) {
                if (defined('INSERT_FRESH_FILES') && true) {
                    $sFileUrl = $sFileUrl . '?' . filemtime($sFilePath);
                }
            } else {
                $this->addJs(array(
                    'script' => $sErrorLog
                ));
                $sFileUrl = '#missing#' . $sFileUrl;
            }
        } elseif (!$bAbsoluteInternalUrl && $sUrlHost != '') {
            $aUrlParts = parse_url($sFileUrl);
            if (isset($aUrlParts["host"]) && $aUrlParts["host"] != WB_URL) {
                $sFileUrl = '#external#' . $sFileUrl;
            }
        } else {
            $this->addJs(array(
                'script' => $sErrorLog
            ));
            $sFileUrl = '#missing#' . $sFileUrl;
        }
        return $sFileUrl;
    }

    /**
     * @brief   Provides an array of key-value pairs for easier placement 
     *          of file urls implemented in _checkAndReplaceFileLoc() method. 
     *          Using the static function I::addUrlToken(); you can add new 
     *          token pairs from inside modules, plugins or what have you...
     * 
     * @return array
     */
    public function replacementTokens() 
    {
        return array(
            '{WB_URL}' => WB_URL,
            '{MODULES}' => WB_URL . '/modules',
            // below Tokens are accessible in Frontend only!
            '{DEFAULT_TEMPLATE}' => WB_URL . '/templates/' . (defined('TEMPLATE') ? DEFAULT_TEMPLATE : ''),
            '{TEMPLATE_URL}' => defined('TEMPLATE') ? TEMPLATE : ''
        );
        // More replacement Tokens can be added to this array using the addUrlToken method
    }

    /**
     * @brief   Makes it possible to add additional replacement Tokens to the 
     *          aUrlTokens array.
     *          Using the static counterpart I::addUrlToken(); you can 
     *          add new token pairs from inside modules, plugins or what have you...
     * 
     * @param   string $sToken
     * @param   string $sURL
     * @return  
     */
    public function addUrlToken($sToken = "", $sURL = "") 
    {
        if ($sToken != "" && $sURL != "") {
            if (!is_dir(str_replace(WB_URL, WB_PATH, $sURL))) {
                // this URL/Directory does not exist on this CMS installation
                return false;
            }
            $aNewTokens = array(
                $sToken => $sURL
            );
            $this->aUrlTokens += $aNewTokens;
        } else {
            return false;
        }
    }

    /**
     * @brief Method to get the Queue(s) for checking or processing.
     * You can set a default return value if nothing is found. 
     * 
     * @param  string   $sSetName   If you want a single entry, specify it's $sSetName/ID 
     *                              If empty, the whole JsQueue will be prepared for output
     * 
     * @param  string   $sPosition  Will only work if $sSetName is not specified
     *                              Set this to only return Scripts whith a certain position set. 
     *                              Default position names can be found in docs to addJs().
     *                              $sPosition="All" returns the full Js array.
     * @param  unspec   $uDefault   You can define a special return var if the Js array is empty.
     * @retval array or unspecified Returns the single Entry or an Array depending on parameter 
     *                              settings or if nothing found whatever was specified in $uDefault
     */
    public function getQueueArray($sSetName = "", $sQueue = "", $sPosition = "All", $uDefault = false) 
    {
        if ($sQueue != '') {
            $sQueue = '_' . ucfirst(strtolower($sQueue)) . 'Queue';
        }
        $aRetVal = array();
        $aQueue = array();

        if (($sQueue != "") && isset($this->$sQueue)) {
            $aQueue = $this->$sQueue;
        } else {
            $aQueue['_CssQueue']   = $this->_CssQueue;
            $aQueue['_JsQueue']    = $this->_JsQueue;
            $aQueue['_HtmlQueue']  = $this->_HtmlQueue;
            $aQueue['_MetaQueue']  = $this->_MetaQueue;
            $aQueue['_TitleQueue'] = $this->_TitleQueue;
        }

        // check if there are records in this Queue already
        if (empty($aQueue))
            return $uDefault;

        if ($sSetName == "") {
            // return the whole $aQueue array if $sSetName isn't specified
            if ($sQueue != "_TitleQueue") {
                if (strtolower($sPosition) == "all") {
                    foreach ($aQueue as $key => $aSubQueue) {

                        //
                        if (is_array($aSubQueue) && !empty($aSubQueue)) {
                            foreach ($aSubQueue as $sSetName => $rec) {
                                $aRetVal[$sQueue][$key][$sSetName] = $rec;
                            }
                        }
                    }
                }
                if (strtolower($sPosition) != "all") {
                    foreach ($aQueue as $key => $aSubQueue) {
                        //
                        if (is_array($aSubQueue) && !empty($aSubQueue)) {
                            foreach ($aSubQueue as $sSetName => $rec) {
                                if (isset($rec['position']) && $rec['position'] == $sPosition) {
                                    $aRetVal[$key][$sSetName] = $rec;
                                }
                            }
                        }
                    }
                }
            } else {
                $aRetVal = array(
                    '_TitleQueue' => ($aQueue)
                );
            }
        } elseif (!empty($aQueue[$sSetName])) {
            // return the record with specified $sSetName
            $aRetVal[$sSetName] = $aQueue[$sSetName];
        } else {
            return $uDefault;
        }
        return $aRetVal;
    }

    /**
     * @brief   returns an array of Placeholders and their RegEx strings for use    
     *          with addPlaceholdersToDom() and doFilter() methods  
     *  
     * @retval array
     */
    public function placeholderArrays() 
    {
        // While working with jQuery and other JS Libraries it's important to have its  
        //    CSS files added before the actual JS code. 
        //    We have taken care of it using the proper order of placeholders.
        $aPlaceholders = array(
            'JS HEAD TOP' => array(
                "/<\s*meta[^>]*?charset.*?\/?\s*>/si",
                "$0\n<!--(PH) JS HEAD TOP+ -->\n<!--(PH) JS HEAD TOP- -->\n"
            ),
            'CSS HEAD TOP' => array(
                "/<\s*meta[^>]*?charset.*?\/?\s*>/si",
                "$0\n<!--(PH) CSS HEAD TOP+ -->\n<!--(PH) CSS HEAD TOP- -->\n"
            ),
            'CSS HEAD BTM' => array(
                "#<\s*/\s*head\s*>#iU",
                "\n<!--(PH) CSS HEAD BTM+ -->\n<!--(PH) CSS HEAD BTM- -->\n$0"
            ),
            'JS HEAD BTM' => array(
                "#<\s*/\s*head\s*>#iU",
                "\n<!--(PH) JS HEAD BTM+ -->\n<!--(PH) JS HEAD BTM- -->\n$0"
            ),
            'HTML BODY TOP' => array(
                "/<\s*body.*>/iU",
                "$0\n<!--(PH) HTML BODY TOP+ -->\n<!--(PH) HTML BODY TOP- -->\n"
            ),
            'JS BODY TOP' => array(
                "/<\s*body.*>/iU",
                "$0\n<!--(PH) JS BODY TOP+ -->\n<!--(PH) JS BODY TOP- -->\n"
            ),
            'HTML BODY BTM' => array(
                "#<\s*/\s~body\s*>#iU",
                "\n<!--(PH) HTML BODY BTM+ -->\n<!--(PH) HTML BODY BTM- -->\n$0"
            ),
            'JS BODY BTM' => array(
                "#<\s*/\s*body\s*>#iU",
                "\n<!--(PH) JS BODY BTM+ -->\n<!--(PH) JS BODY BTM- -->\n$0"
            ),
            'META HEAD' => array(
                "/<\s*meta[^>]*?charset.*?\/?\s*>/si",
                "\n<!--(PH) META HEAD+ -->\n<!--(PH) META HEAD- -->\n$0"
            ),
            'TITLE' => array(
                "/<\s*title.*?<\s*\/\s*title\s*>/si",
                "<!--(PH) TITLE+ -->$0<!--(PH) TITLE- -->"
            ),
            'META DESC' => array(
                "/<\s*meta[^>]*?\=\"description\".*?\/?\s*>/si",
                "<!--(PH) META DESC+ -->$0<!--(PH) META DESC- -->"
            ),
            'META KEY' => array(
                "/<\s*meta[^>]*?\=\"keywords\".*?\/?\s*>/si",
                "<!--(PH) META KEY+ -->$0<!--(PH) META KEY- -->"
            )
        );
        return $aPlaceholders;
    }

    /**
     * @brief  This method will populate all placeholders on autopilot to the $sContent    
     *         It is based on regular expressions as this is more error resistant than using a DOM class.
     * 
     * @param  string  $sContent  The HTML content to filter    
     * @retval string             The filtered/replaced content.  
     */
    public function addPlaceholdersToDom($sContent) 
    {
        if (strpos($sContent, '<!--(NO PH)-->') !== false) {
            // Template doesn't want automatic placeholder generation? 
            // OK, return right away.
            return $sContent;
        }
        // populate Placeholders in $sContent
        foreach ($this->placeholderArrays() as $sPlaceholder => $rec) {
            $sPlaceholder = '<!--(PH) ' . $sPlaceholder . '+ -->';
            if (strpos($sContent, $sPlaceholder) === false)
                $sContent = preg_replace($rec[0], $rec[1], $sContent);
        }
        return $sContent;
    }

    /**
     * @brief  The output filter function that does the actual replacement of the template placeholders. 
     *    
     * @param  string $sContent   The HTML content to filter     
     * @retval string             The filtered/replaced content.  
     */
    public function doFilter($sContent) 
    {
        $aProcessReplace = array(); // array with values to be replaced ('TITLE', 'META DESC', 'META KEY')
        $aProcessInsert  = array();  // array with values to be added to the corresponding Placeholder
        foreach (array_keys($this->placeholderArrays()) as $sPlacer) {
            if (in_array($sPlacer, array('TITLE', 'META DESC', 'META KEY'))) {
                $aProcessReplace[] = $sPlacer;
            } else {
                $aProcessInsert[] = $sPlacer . '+';
                $aProcessInsert[] = $sPlacer . '-';
            }
        }

        // iterate through the array and insert the entries to their corresponding PlaceHolders
        foreach ($aProcessInsert as $sPlaceholder) {

            #if($iMetaDesc > 0 or $iMetaKey > 0) continue;
            $sToPlaceHolder = "<!--(PH) " . $sPlaceholder . " -->";
            $aTmp = explode(' ', trim($sPlaceholder));
            $sType = ucfirst(strtolower($aTmp[0]));
            $sType = str_replace(array('+', '-'), '', $sType);
            $_sProcessFunc = '_process' . $sType;

            $sDomPos = '';
            if (isset($aTmp[1])) {
                $sDomPos .= $aTmp[1];
                if (isset($aTmp[2]))
                    $sDomPos .= ' ' . $aTmp[2];
            }

            // add before or after the placeholder?
            if (strpos($sDomPos, '+') !== false)
                $sInsert = $sToPlaceHolder . $this->$_sProcessFunc($sDomPos); // at beginning of the block ["+"]
            else
                $sInsert = $this->$_sProcessFunc($sDomPos) . $sToPlaceHolder; // at the end of the block ["-"]

            $sContent = preg_replace(
                '/' . preg_quote($sToPlaceHolder) . '/s', 
                $sInsert, 
                $sContent, 
                1
            );
        }

        foreach ($aProcessReplace as $sPlaceholder) {
            $aTmp = explode(' ', trim($sPlaceholder));
            $sType = ucfirst(strtolower($aTmp[0]));
            $sType = str_replace(array('+', '-'), '', $sType);
            if ($sType == "Title" && $this->_TitleQueue === NULL)
                continue;
            $_sProcessFunc = '_process' . $sType;
            $sDomPos = '';
            if (isset($aTmp[1])) {
                $sDomPos .= $aTmp[1];
                if (isset($aTmp[2])) {
                    $sDomPos .= ' ' . $aTmp[2];
                }
                $sDomPos .= '+';
            }
            $sPH1 = "<!--(PH) " . ($sPlaceholder) . "+ -->";
            $sPH2 = "<!--(PH) " . ($sPlaceholder) . "- -->";
            $sIns = $sPH1 . $this->$_sProcessFunc($sDomPos) . $sPH2;
            $sContent = preg_replace(
                '/' . preg_quote($sPH1) . '.*' . preg_quote($sPH2) . '/sU', 
                $sIns, 
                $sContent, 
                1
            );
        }
        return $sContent;
    }
}
// end of Class Insert
