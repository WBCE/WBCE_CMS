<?php
/**
 * @brief     The basic idea is to have global available methods that allow 
 *            any script whithin the WBCE CMS to register meta-data, JavaScript 
 *            and StyleSheets, that are later inserted into the website's
 *            HTML DOM source code.
 *            The actual insertion is done via OutputFilter Placeholders (PH)
 * 
 * @author    Norbert Heimsath for the WBCE Project
 *            Initial code and work on both classes (I and Insert).
 * @author    Christian M. Stefan <stefek@designthings.de>
 *            adaptation and code expansion for WBCE 1.3.x
 *            Procedural alias functions have been implemented for 
 *            even more ease of use.
 * 
 * @copyright http://www.gnu.org/licenses/lgpl.html (GNU LGPLv2 or any later) 
 */

class I {

    /**
     * @brief   The recent instance of Insert Class    
     * @var     instance $Instance
     */
    private static $oInstance = NULL;

    /**
     * @brief   Create a new instance of Insert if not already done. 
     *          Store the object in a static var.    
     *          This would be the constructor if this wasn't a static facade class.
     */
    private static function getInstance() {
        if (I::$oInstance === NULL) {
            I::$oInstance = new Insert();
        }
        return I::$oInstance;
    }

    /**
     * @brief    Replaces or adds a string to the <title>
     *           If $bOverwrite is set to true the old value will be replaced    
     *           by the new value. 
     *    
     * @param    string   $sTitle    
     * @param    boolean  $bOverwrite     
     * @return   boolean  false on sucess, string error message on failure
     */
    public static function insertTitle($sTitle = "", $bOverwrite = true) {
        return I::getInstance()->insertTitle($sTitle, $bOverwrite);
    }

    /**
     * @brief  insert a JS File to DOM and place it at the given position ($sDomPos)
     * 
     * @param  unspec  $uFileLoc may be a single file loc or an array with file locations
     * @param  string  $sDomPos
     * @param  string  $sID
     * @return 
     */
    public static function insertJsFile($uFileLoc = '', $sDomPos = 'HEAD BTM-', $sID = false) {
        return I::getInstance()->insertJsFile($uFileLoc, $sDomPos, $sID);
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
    public static function insertJsCode($sCode = '', $sDomPos = 'HEAD BTM-', $sID = false) {
        return I::getInstance()->insertJsCode($sCode, $sDomPos, $sID);
    }

    /**
     * @brief  insert a CSS File to DOM and place it at the given position ($sDomPos)
     * 
     * @param  unspec  $uFileLoc may be a single file loc or an array with file locations
     * @param  string  $sDomPos
     * @param  string  $sID
     * @return 
     */
    public static function insertCssFile($uFileLoc = '', $sDomPos = 'HEAD BTM+', $sID = false, $sMedia = '')  {
        return I::getInstance()->insertCssFile($uFileLoc, $sDomPos, $sID, $sMedia);
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
    public static function insertCssCode($sCode = '', $sDomPos = 'HEAD BTM+', $sID = false, $sMedia = '')  {
        return I::getInstance()->insertCssCode($sCode, $sDomPos, $sID, $sMedia);
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
    public static function insertHtmlCode($sCode = '', $sDomPos = 'BODY TOP+', $sID = '') {
        return I::getInstance()->insertHtmlCode($sCode, $sDomPos, $sID);
    }

    /**
     * @brief    insert a Meta Tag to DOM 
     *           Allows to add additional Metas to the Head of the Template.
     *           Expects an array to be given as metas got a countless number 
     *           of attributes the array may contain anything you like.
     * 
     * Example usage:
     * 
     * I::insertMetaTag(array (    
     *    "setname" => "description",     
     *    "name"    => "description",     
     *    "content" => "This is a nice description"    
     * ));    
     * 
     * // <meta name="description" content="This is a nice description" /> 
     *  
     * I::insertMetaTag(array (    
     *    "setname" => "keywords_en",     
     *    "name"    => "keywords",    
     *    "content" => "This, That, Which, Something",
     *    "lang"    => "en"    
     * ));  
     * // <meta name="keywords" content="Dieses, Jenes, Welches, Irgendwas" lang="de" />  
     *   
     * // Redirect    
     * I::insertMetaTag(array (    
     *    "setname"    => "refresh",     
     *    "http-equiv" => "refresh",    
     *    "content"    => "0;url=http://www.domain.de/"    
     * ));   
     * // <meta http-equiv="refresh" content="0;url=http://www.domain.de/">
     * 
     * // charset   
     * I::insertMetaTag(array (    
     *    "setname" => "charset",    
     *    "charset" => "ISO-8859-1"
     * ));
     *    
     * // depending on render method (html|xhtml|html5) this will produce different outputs:  
     * // html : <meta http-equiv="content-type" content="text/html; charset=utf-8">    
     * // xhtml: <meta http-equiv="content-type" content="text/html; charset=utf-8" />     
     * // html5: <meta charset="utf-8">  
     *     
     * @param array $aMetaData   This is always an array whith all atributes stored 
     *                           as "key" => "value" see examples above.
     *                           All other parameters are simply piped to the meta output. 
     *                           There are quite a few attributes that schould be used as 
     *                           setname so other Scripts can interact whith those correctly:
     * 
     *                           -------------|-------------------------------------   
     *                            setname     | function 
     *                           -------------|-------------------------------------
     *                            author      | name="author" content="Author Name"    
     *                            description | name="description" ....    
     *                            keywords    | name="keywords".....     
     *                            date        | name="date"    
     *                            robots      | name="robots"   
     *                            charset     | charset="utf-8" for example   
     *                            expires     | http-equiv="expires"   
     *                            refresh     | http-equiv="refresh"
     *                           -------------|-------------------------------------
     * 
     * @return 
     */
    public static function insertMetaTag($aMetaData = array()) {
        return I::getInstance()->addMeta($aMetaData);
    }

    /**
     * @brief  reset the title that was previously added to _TitleQueue 
     * 
     * @return
     */
    public static function resetTitle() {
        I::getInstance()->deleteFromQueue('', "Title");
        return false;
    }

    /**
     * @brief  delete a JS Entry from _JsQueue
     * 
     * @param  string $sID the identifier
     * @return
     */
    public static function delJs($sID) {
        I::getInstance()->deleteFromQueue($sID, "Js");
        return false;
    }

    /**
     * @brief  delete a CSS Entry from _CssQueue
     * 
     * @param  string $sID the identifier
     * @return
     */
    public static function delCss($sID) {
        I::getInstance()->deleteFromQueue($sID, "Css");
        return false;
    }

    /**
     * @brief  delete a HTML Entry from _HtmlQueue
     * 
     * @param  string $sID the identifier
     * @return
     */
    public static function delHtml($sID) {
        I::getInstance()->deleteFromQueue($sID, "Html");
        return false;
    }

    /**
     * @brief  delete a Meta Entry from _MetaQueue
     * 
     * @param  string $sSetName
     * @return
     */
    public static function delMeta($sSetName) {
        I::getInstance()->deleteFromQueue($sSetName, "Meta");
        return false;
    }

    /**
     * @brief   get contents of a specific queue (Js, Css, Meta, Html)
     * 
     * @param   string   $sSetName   if set, only one specific entry will be shown
     * @param   string   $sQueue     if $sSetName is empty and the $sQueue is set
     *                               (Js, Css, Meta, Html) only the array of this 
     *                               queue will be shown
     * @param   string   $sPosition  if set, only queue entries of this position 
     *                               will be shown
     * @param   unspec   $uDefault
     * @return  
     */
    public static function getQueueArray($sSetName = "", $sQueue = "", $sPosition = "All", $uDefault = false) {
        return I::getInstance()->getQueueArray($sSetName, $sQueue, $sPosition, $uDefault);
    }

    /**
     * @brief  adds a URL Token for later processing of the file location 
     * 
     * @param  string  $sToken  The Key that will be later used when setting 
     *                          the URL (src or href)
     * @param  string  $sURL    The Key's corresponding URL partial string
     * 
     * @return 
     */
    public static function addUrlToken($sToken = "", $sURL = "") {
        if ($sToken != "" && $sURL != "") {
            return I::getInstance()->addUrlToken($sToken, $sURL);
        } else {
            return false;
        }
    }

    /**

     * @brief   The output filter function that does the actual replacement of the 
     *          template placeholders. 
     *          In WBCE CMS you do not need to take care of this as this is loaded 
     *          by the default output filters.     
     *          Although the method needs to be public for outputfilter call. 
     * 
     * @param   string  $Content  The HTML content to filter 
     * @return  string  The filtered/replaced content.  
     */
    public static function doFilter($Content) {
       $result = I::getInstance()->doFilter($Content);
       #I::$oInstance = NULL;
       return $result;
    }

    /**
     * @brief  Automatically add all placeholders on autopilot to the Content.
     *         Basically this still is an output filter that simply adds some 
     *         placeholders to a HTML page. 
     *
     * @param  string  $Content  The HTML content to filter 
     * @return string  The filtered/replaced content.  
     */
    public static function addPlaceholdersToDom($Content) {
        return I::getInstance()->addPlaceholdersToDom($Content);
    }

}
// end of class I


// //////////////////////////////////////////////////////////////////
// 
//    procedural alias functions that can be
//    used instead of the static methods
//
// //////////////////////////////////////////////////////////////////

/**
 * @brief  insert a JS File to DOM and place it at the given position ($sDomPos)
 * 
 * @param  unspec  $uFileLoc  may be a single file loc or an array with file locations
 * @param  string  $sDomPos
 * @param  string  $sName
 * @return 
 */
function insertJsFile($uFileLoc = '', $sDomPos = 'HEAD BTM-', $sID = '') {
    return I::insertJsFile($uFileLoc, $sDomPos, $sID);
}

/**
 * @brief  insert a CSS File to DOM and place it at the given position ($sDomPos)
 * 
 * @param  unspec  $uFileLoc  may be a single file loc or an array with file locations
 * @param  string  $sDomPos
 * @param  string  $sName
 * @return 
 */
function insertCssFile($uFileLoc = '', $sDomPos = 'HEAD BTM+', $sID = '', $sMedia = '')  {
    return I::insertCssFile($uFileLoc, $sDomPos, $sID, $sMedia);
}

/**
 * @brief  insert JS Code to DOM and place it at the given position ($sDomPos)
 * 
 * @param  unspec  $sCode    the JS Code; it's not needed  (nor advised) 
 *                           to surround the code with the 
 *                           <script> and </script> tags but it's allowed
 * @param  string  $sDomPos
 * @param  string  $sName
 * @return 
 */
function insertJsCode($sCode = '', $sDomPos = 'HEAD BTM-', $sID = '') {
    return I::insertJsCode($sCode, $sDomPos, $sID);
}

/**
 * @brief  insert CSS Code to DOM and place it at the given position ($sDomPos)
 * 
 * @param  unspec  $sCode    the CSS Code; it's not needed (nor advised) to 
 *                           surround the code with the 
 *                           <style> and </style> tags but it's allowed
 * @param  string  $sDomPos
 * @param  string  $sName
 * @return 
 */
function insertCssCode($sCode = '', $sDomPos = 'HEAD BTM+', $sID = '') {
    return I::insertCssCode($sCode, $sDomPos, $sID);
}
