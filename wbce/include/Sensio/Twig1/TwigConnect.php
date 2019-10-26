<?php

require_once __DIR__ . '/Twig_Autoloader.php';
Twig_Autoloader::register();


if (!function_exists('getTwig')) {

    /**
     * @brief 
     *    EN: This function can be called and used in both, the Frontend and
     *        the Backend and will immediately provide all relevant variables
     *        that can be used within the Twig Templates.
     *        You don't need to care too much for these variables within your
     *        PHP Code anymore (i.e. setting them up and handing over to the template).
     * 
     *        Should one of the variables be needed in the template you can use it
     *        right away.
     *           
     *    DE: Diese Funktion kann im Backend und Frontend aufgerufen werden und
     *        stellt sofort eine Anzahl von relevanten Variablen zur Verfuegung, 
     *        die in den Twig-Templates verwenden werden koennen.
     *        Man muss sich auf PHP-Code Ebene gar nicht mehr darum kuemmern.
     * 
     *        Wird im Template eine der gesetzten Variablen benoetigt, 
     *        kann sie gradewegs benutzt werden.
     * 
     * @global object $database
     * @param  unspec $uTemplateLocs //may be an array of locations or a single string
     * @param  int    $iSectionID
     * @param  unspec $uCache
     * @return \Twig_Environment
     */
    function getTwig($uTemplateLocs = null, $iSectionID = null, $uCache = false) {
        $oEngine = isset($GLOBALS['wb']) ? $GLOBALS['wb'] : $GLOBALS['admin']; // use function in BE and FE alike

        $aOptions = array(
            'autoescape'       => false,
            'strict_variables' => false,
            'debug'            => _debugStatus(),
            'cache'            => _cacheStatus($uCache)
        );

        $oLoader = new \Twig\Loader\FilesystemLoader($uTemplateLocs);
        $oTwig   = new \Twig\Environment($oLoader, $aOptions);
		
        #$oLoader = new \Twig\Loader\FilesystemLoader($uTemplateLocs);
		#$oTwig   = new \Twig\Environment($oLoader, $aOptions);
		
        // VARS always present in all Twig Templates
        $oTwig->addGlobal('FTAN',         $oEngine->getFTAN());
        $oTwig->addGlobal('WB_URL',       WB_URL);
        $oTwig->addGlobal('ADMIN_URL',    ADMIN_URL);
        $oTwig->addGlobal('THEME_URL',    THEME_URL);
        $oTwig->addGlobal('SEC_ANCHOR',   SEC_ANCHOR);
        $oTwig->addGlobal('LANGUAGE',     LANGUAGE);
        $oTwig->addGlobal('MEDIA_URL',    WB_URL . MEDIA_DIRECTORY);
        $oTwig->addGlobal('DATE_FORMAT',  DATE_FORMAT);
        $oTwig->addGlobal('TIME_FORMAT',  TIME_FORMAT);
        if (defined('TEMPLATE_DIR')) {
            $oTwig->addGlobal('TEMPLATE_URL', TEMPLATE_DIR); // may come in handy in some layouts ;-)
        }

        $oTwig->addGlobal('IS_ADMIN',       $oEngine->isAdmin());
        $oTwig->addGlobal('IS_SUPERADMIN',  $oEngine->isSuperAdmin());
        $oTwig->addGlobal('USER_LOGGED_IN', $oEngine->isLoggedIn());
            
        // VARS present in Twig Templates for *AdminTools*		
          // Constant ADMIN_TOOL_DIR does not exist in WB/WBCE
          // -----(figure out an alternative later)
        if(defined('ADMIN_TOOL_DIR') && is_string(ADMIN_TOOL_DIR) ){
            $oTwig->addGlobal('TOOL_NAME', $GLOBALS['module_name']);
            $oTwig->addGlobal('TOOL_URI', ADMIN_URL.'/admintools/tool.php?tool='.ADMIN_TOOL_DIR);
            $oTwig->addGlobal('ADDON_URL', WB_URL.'/modules/'.ADMIN_TOOL_DIR);
        }
        // VARS present in Twig Templates for *Section/Page Type modules*		
        if ($iSectionID != null) {
            foreach (getSectionDetails(intval($iSectionID)) as $VAR => $value) {
                $oTwig->addGlobal($VAR, $value);
                // add LayoutPlugins "root" Path for use with Twig {% include %} function
                if ($VAR == 'ADDON_URL') {
                    // Mod LayoutPlugins root
                    $sModPlugins = str_replace(WB_URL, WB_PATH, $value) . '/LayoutPlugins/';                    
                    if(is_dir($sModPlugins))
                        $oLoader->addPath($sModPlugins);
                    // Template LayoutPlugins root
                    if (defined('TEMPLATE_DIR')) {
                        $value = basename($value);
                        $sTplPlugins = str_replace(WB_URL, WB_PATH, TEMPLATE_DIR) . '/mod_' . $value . '/LayoutPlugins/';
                        if (is_readable($sTplPlugins)) {
                            $oLoader->addPath($sTplPlugins);
                        }
                    }
                }
            }
        }

        // *************************************************
        //    Include additional practical Twig functions
        // *************************************************
        
        // Load Twig_SimpleFunction Files from directory 
        foreach (glob(__DIR__ . "/SimpleFunctions/tsf_*.php") as $sFile) include $sFile;
        
        // Load external Twig_SimpleFunction files from modules which have the 'twig_extend' marker set
        $sSql = 'SELECT `directory` FROM `{TP}addons` WHERE function LIKE \'%twig_extend%\' ';
        if (($aExtends = $GLOBALS['database']->get_array($sSql))) {
            foreach ($aExtends as $rec) {
                $sFile = WB_PATH . '/modules/' . $rec['directory'] . '/TwigFunctions.php';
                if (file_exists($sFile)) include $sFile;
            }
        }    

        $oTwig->addExtension(new Twig_Extension_Debug());        
        return $oTwig;
    }


    /**
     * @short  This  function  retrieves  all  relevant  Vars of a sections
     *         and  returns  them  as  an  array (incl.: PAGE_ID, PAGE_URL)
     *         This simple array can then be made available in the template
     *         for the use in Layouts.
     * 
     * @global object $database
     * @param  int    $iSectionID
     * @return array
     */
    function getSectionDetails($iSectionID = null) {
        $aSection = array(); // collect Section Details here
        if ($iSectionID != null) {
            global $database;
            $sSql = 'SELECT s.`module`, s.`publ_start`, s.`publ_end`, s.`block`';
            $sSql .= ', p.`page_id`, p.`link`, p.`page_title`';
            #$sSql = ', s.`visibility`'; //there is no visibility status for sections in WBCE yet
            $sSql .= ' FROM `{TP}sections` s';
            $sSql .= '   INNER JOIN `{TP}pages` p';
            $sSql .= '   ON p.`page_id`=s.`page_id`';
            $sSql .= ' WHERE `section_id`=' . intval($iSectionID);

            if ($resSection = $database->query($sSql)) {
                $rec = $resSection->fetchRow(MYSQL_ASSOC);
                // CAPITAL LETTER VARIABLES to use in layouts.
                $aSection['SECTION_ID']   = $iSectionID;   // (int)
                $aSection['PAGE_ID']      = $rec['page_id'];
                $aSection['PAGE_TITLE']   = $rec['page_title'];
                $aSection['PAGE_URL']     = WB_URL . PAGES_DIRECTORY . $rec['link'] . PAGE_EXTENSION;
                $aSection['ADDON_URL']    = WB_URL . '/modules/' . $rec['module'];
                $aSection['LAYOUT_BLOCK'] = $rec['block'];
                $aSection['PUBLIC_START'] = $rec['publ_start'];
                $aSection['PUBLIC_END']   = $rec['publ_end'];
                #$aSection['SECTION_VISIBILITY'] = $rec['visibility'];	
                unset($rec);
            }
        }
        return $aSection;
    }
    
    function _debugStatus(){
        $oEngine = isset($GLOBALS['wb']) ? $GLOBALS['wb'] : $GLOBALS['admin'];
        $bDebug = (isset($_GET['twig_debug']) && $_GET['twig_debug'] == true) ? true : false;
        $bDebug = (defined('WB_TWIG_DEBUG') && WB_TWIG_DEBUG == true) ? true : false;
        if($oEngine->isAdmin() == true){
            $bDebug = true;
        }
            $bDebug = true;
        return $bDebug;
    }
    
    function _cacheStatus($uCache){
        $uRetVal = false;
        if (is_string($uCache)) 
            if(strpos($uCache, WB_PATH. '/temp/') !== FALSE)
                $uRetVal =  $uCache;
		
        if (!is_string($uCache) && $uCache == true) 
            $uRetVal = WB_PATH . '/temp/LopiCache/';
        
        return $uRetVal;
    }
    
    function getPluginLangArray($sLoc) {
        $sLoc = $sLoc . 'languages/';
        $aLang = array();
        
        $sLocEN = $sLoc . 'EN.php';
        if (file_exists($sLocEN))
            $aLang = include($sLocEN);
            
        $sCorrectedLoc = $sLoc . LANGUAGE . '.php';
        if (file_exists($sCorrectedLoc))
            $aLang = array_merge($aLang, include($sCorrectedLoc));
        return $aLang;
    }
}