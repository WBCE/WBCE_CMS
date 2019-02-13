<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

 
// Prevent this file from being accessed directly
defined('WB_PATH') or die("Access Denied");


class wb extends SecureForm
{
    // store a direct output 
    public $sDirectOutput = '';

    public $password_chars = 'a-zA-Z0-9\_\-\!\#\*\+\@\$\&\:'; 
	
	
	
    /**
     * @brief  General initialization function performed
     *          when frontend or backend is loaded.
     */
    public function __construct($mode = SecureForm::FRONTEND)
    {
        parent::__construct($mode);
    }

    
    
    /**
     * @brief  For easy output of JSON strings XML or Ajax...... 
     *         If you want the whole page to be processed 
     *         (for internal functionality or whatever)
     *         before output is done, just set the class variable manually 
     *
     *         @code
     *            $wb->sDirectOutput     = "My cool string.";
     *            $admin->sDirectOutput  = "My cool string.";
     *         @endcode
     *
     *         DirectOutput is triggered once  before normal output is done. 
     *
     * @param  $sContent The content to pipe out 
     *	@return string echos out this string 
     */
    public function DirectOutput($sContent = false) {
        
        // Move to class
        if (is_string($sContent)){
            $this->sDirectOutput .= $sContent;
        }
        
        // Return if Class var is still empty
        if (empty ($this->sDirectOutput)) return;
        
        // kill all output buffering
        while (ob_get_level())
        {
            ob_end_clean ();
        }
        
        // directly output everyting and exit
        echo $this->sDirectOutput;
        exit;
    }  
    
    /** 
     * @brief   Check if one or more group_ids are in both group_lists
     * 
     * @param   unspec $groups_list1: an array or a coma seperated list of group-ids
     * @param   unspec $groups_list2: an array or a coma seperated list of group-ids
     * @param   array  &$matches: an array-var whitch will return possible matches
     * @return  bool   true there is a match, otherwise false
     */
    public function is_group_match($groups_list1 = '', $groups_list2 = '', &$matches = null)
    {
        if ($groups_list1 == '') {return false;}
        if ($groups_list2 == '') {return false;}
        if (!is_array($groups_list1)) {
            $groups_list1 = explode(',', $groups_list1);
        }
        if (!is_array($groups_list2)) {
            $groups_list2 = explode(',', $groups_list2);
        }
        $matches = array_intersect($groups_list1, $groups_list2);
        return (sizeof($matches) != 0);
    }
    
    /**
     * @brief   Check if current user is member of at least one of given groups
     *          SuperAdmin (user_id = 1) is always member of ALL groups
     *
     * @param   unspec $groups_list  An array or a comma seperated list of group-ids
     * @return  bool   true: if current user is member of one of this groups, otherwise false
     */
    public function ami_group_member($groups_list = '')
    {
        if ($this->get_group_id() == 1) {return true;}
        return $this->is_group_match($groups_list, $this->get_groups_id());
    }

    /**
     * @brief   Check whether a page is visible or not.
     *          This will check page-visibility and user- and group-rights.
     * 
     * @param   array  $page
     * @return  bool   false: If page-visibility is 'none' or 'deleted', or page-vis. is 'registered' 
     *                        or 'private' and user isn't allowed to see the page.
     *                 true:  If page-visibility is 'public' or 'hidden', or page-vis. is 'registered' 
     *                        or 'private' and user _is_ allowed to see the page.
     */
    public function page_is_visible($page)
    {
        $show_it        = false; // shall we show the page_link?
        $page_id        = $page['page_id'];
        $visibility     = $page['visibility'];
        $viewing_groups = $page['viewing_groups'];
        $viewing_users  = $page['viewing_users'];

        // First check if visibility is 'none', 'deleted'
        if ($visibility == 'none') {
            return (false);
        } elseif ($visibility == 'deleted') {
            return (false);
        }

        // Now check if visibility is 'hidden', 'private' or 'registered'
        if ($visibility == 'hidden') {
            // hidden: hide the menu-link, but show the page
            $show_it = true;
        } elseif ($visibility == 'private' || $visibility == 'registered') {
            // Check if the user is logged in
            if ($this->is_authenticated() == true) {
                // Now check if the user has perms to view the page
                $in_group = false;
                foreach ($this->get_groups_id() as $cur_gid) {
                    if (in_array($cur_gid, explode(',', $viewing_groups))) {
                        $in_group = true;
                    }
                }
                if ($in_group || in_array($this->get_user_id(), explode(',', $viewing_users))) {
                    $show_it = true;
                } else {
                    $show_it = false;
                }
            } else {
                $show_it = false;
            }
        } elseif ($visibility == 'public') {
            $show_it = true;
        } else {
            $show_it = false;
        }
        return ($show_it);
    }
    
    /**
     * @brief   Check if there is at least one active section on this page.
     * 
     * @global  object $database
     * @param   array $page
     * @return  bool
     */
    public function page_is_active($page)
    {
        global $database;
        $has_active_sections = false;
        $now = time();
        $sql = 'SELECT `publ_start`, `publ_end` FROM `{TP}sections` WHERE `page_id`='.(int) $page['page_id'];
        $query_sections = $database->query($sql);
        if ($query_sections->numRows() != 0) {
            while ($section = $query_sections->fetchRow()) {
                if ($now < $section['publ_end'] &&
                    ($now > $section['publ_start'] || $section['publ_start'] == 0) ||
                    $now > $section['publ_start'] && $section['publ_end'] == 0) {
                    $has_active_sections = true;
                    break;
                }
            }
        }
        return ($has_active_sections);
    }

    /**
     * @brief   Check whether we should show a page or not (for front-end)
     * 
     * @param   array  $page
     * @return  bool
     */
    public function show_page($page)
    {
        $retval = ($this->page_is_visible($page) && $this->page_is_active($page));
        return $retval;
    }

    /**
     * @brief   Check whether the user is already authenticated (loged in)
     * 
     * @return  bool
     */
    public function is_authenticated()
    {
        return (isset($_SESSION['USER_ID']) && $_SESSION['USER_ID'] != "" &&  is_numeric($_SESSION['USER_ID']));
    }

    /**
     * @brief   Modified addslashes function which takes into account magic_quotes
     * 
     * @param   string $input  
     * @return  string
     */
    public function add_slashes($input)
    {
        if (get_magic_quotes_gpc() || (!is_string($input))) {
            return $input;
        }
        return addslashes($input);
    }

    /**
     * @brief   The purpose of $this->strip_slashes() is to undo the effects of magic_quotes_gpc==ON	 
     *          NOTE: this is _not_ the counterpart to $this->add_slashes() !
     *          Use stripslashes() to undo a preliminarily done $this->add_slashes()
     * 
     * @param   string $input  
     * @return  string
     */
    public function strip_slashes($input)
    {
        if (!get_magic_quotes_gpc() || (!is_string($input))) {
            return $input;
        }
        return stripslashes($input);
    }
	
    /**
     * @brief   Strip slashes from input string if get_magic_quotes_gpc() is ON 
     * 
     * @param   string $input  
     * @return  string
     */
    function strip_magic($input) {
    if (get_magic_quotes_gpc() and is_string($input)) {
            return stripslashes($input);
        }
        return $input;
    }
    
    /**
     * @brief   Escape backslashes for use with mySQL LIKE strings
     * 
     * @param   string $input  
     * @return  string
     */
    public function escape_backslashes($input)
    {
        return str_replace("\\", "\\\\", $input);
    }
    
    /**
     * @brief   Generate full page_link based on the 
     *          `link` content from the `{TP}pages` table
     * 
     * @param   unspec $uLinkId  
     * @return  string
     */
    public function page_link($uLinkId = NULL)
    {
        $sLinkUrl = '';
        if($uLinkId == NULL && defined('PAGE_ID')){
            $uLinkId = (int) PAGE_ID;
        }
        if($uLinkId == NULL && isset($_GET['page_id'])){
            $uLinkId = (int) $_GET['page_id'];
        }
        if(is_numeric($uLinkId)){
            $sSql = "SELECT `link` FROM `{TP}pages` WHERE `page_id` = %d";
            $sLink = $GLOBALS['database']->get_one(sprintf($sSql, $uLinkId));
            $sLinkUrl = WB_URL . PAGES_DIRECTORY . $sLink . PAGE_EXTENSION;
        } else {
            // Check for :// in the link (used in URL's) as well as mailto:
            if (strstr($uLinkId, '://') == '' and substr($uLinkId, 0, 7) != 'mailto:'){
                $sLinkUrl = WB_URL . PAGES_DIRECTORY . $uLinkId . PAGE_EXTENSION;
            } else {
                $sLinkUrl = $uLinkId;
            }
        }
        return $sLinkUrl;
    }
    
    /**
     * @brief   Get POST data	
     * 
     * @param   string $field  
     * @return  string
     */
    public function get_post($field)
    {
        return (isset($_POST[$field]) ? $_POST[$field] : null);
    }

    /**
     * @brief   Get POST data and escape it   
     * 
     * @param   string $field  
     * @return  string
     */
    public function get_post_escaped($field)
    {
        $result = $this->get_post($field);
        return (is_null($result)) ? null : $this->add_slashes($result);
    }

    /**
     * @brief   Get GET data 
     * 
     * @param   string $field  
     * @return  string
     */
    public function get_get($field)
    {
        return (isset($_GET[$field]) ? $_GET[$field] : null);
    }

    /**
     * @brief   Get SESSION data    
     * 
     * @param   string $field  
     * @return  string
     */
    public function get_session($field)
    {
        return (isset($_SESSION[$field]) ? $_SESSION[$field] : null);
    }
    
    /**
     * @brief   Get SERVER data  
     * 
     * @param   string $field  
     * @return  string
     */
    public function get_server($field)
    {
        return (isset($_SERVER[$field]) ? $_SERVER[$field] : null);
    }
	
    /**
     * @brief   Get the current users USER_ID   
     *  
     * @return  int
     */
    public function get_user_id()
    {
        return $this->get_session('USER_ID');
    }

    /**
     * @brief   Get the current users main GROUP_ID. 
     *         NOTE: a user may be member in differend user groups.
     *  
     * @return  int
     */
    public function get_group_id()
    {
        return $this->get_session('GROUP_ID');
    }
	
    /**
     * @brief   Get the current users GROUPS_IDs. 
     *          NOTE: a user may be member in differend user groups.
     * 
     * @return  array
     */
    public function get_groups_id()
    {
        return explode(",", $this->get_session('GROUPS_ID'));
    }
	
    /**
     * @brief   Get the current users GROUP_NAMEs as CSV string.
     *  NOTE: a user may be member in differend user groups.
     * 
     * @return  string
     */
    public function get_group_name()
    {
        return implode(",", $this->get_session('GROUP_NAME'));
    }

    /**
     * @brief   Get the current users GROUP_NAMEs as array.
     * NOTE: a user may be member in differend user groups.
     * 
     * @return  array
     */
    public function get_groups_name()
    {
        return $this->get_session('GROUP_NAME');
    }

    /**
     * @brief   Get the current users USERNAME
     * 
     * @return  string
     */
    public function get_username()
    {
        return $this->get_session('USERNAME');
    }

    /**
     * @brief   Get the current users DISPLAY_NAME
     *  
     * @return  string
     */
    public function get_display_name()
    {
        return $this->get_session('DISPLAY_NAME');
    }

    /**
     * @brief   Get the current users EMAIL address
     * 
     * @return  string
     */
    public function get_email()
    {
        return $this->get_session('EMAIL');
    }

    /**
     * @brief   Get the current users HOME_FOLDER
     * 
     * @return  string
     */
    public function get_home_folder()
    {
        return $this->get_session('HOME_FOLDER');
    }

    /**
     * @brief   Get the current users TIMEZONE
     * 
     * @return  string
     */
    public function get_timezone()
    {
        return (isset($_SESSION['USE_DEFAULT_TIMEZONE']) ? '-72000' : $_SESSION['TIMEZONE']);
    }

    /**
     * @brief   Validate the supplied email address
     *  
     * @param   string
     * @return  string
     */
    public function validate_email($email)
    {
        if (function_exists('idn_to_ascii')) {
            // use pear if available
            $email = @idn_to_ascii($email);
        } else {
            require_once WB_PATH . '/include/idna_convert/idna_convert.class.php';
            $IDN = new idna_convert();
            $email = $IDN->encode($email);
            unset($IDN);
        }
        // regex from NorHei 2011-01-11
        $retval = preg_match("/^((([!#$%&'*+\\-\/\=?^_`{|}~\w])|([!#$%&'*+\\-\/\=?^_`{|}~\w][!#$%&'*+\\-\/\=?^_`{|}~\.\w]{0,}[!#$%&'*+\\-\/\=?^_`{|}~\w]))[@]\w+(([-.]|\-\-)\w+)*\.\w+(([-.]|\-\-)\w+)*)$/", $email);
        return ($retval != false);
    }

    /**
     * @brief   set one or more bit in a integer value
     * 
     * @param   int   $value      Reference to the integer, containing the value
     * @param   int   $bits2set   The bitmask which should be added to the value
     * @return  void
     */
    public function bit_set(&$value, $bits2set)
    {
        $value |= $bits2set;
    }

    /**
     * @brief   reset one or more bit from a integer value
     *  
     * @param   int   $value        Reference to the integer, containing the value
     * @param   int   $bits2reset   The bitmask which should be removed from value
     * @return  void
     */
    public function bit_reset(&$value, $bits2reset)
    {
        $value &= ~$bits2reset;
    }

    /**
     * @brief   check if one or more bit in a integer value is set
     * 
     * @param   int   $value     Reference to the integer, containing the value
     * @param   int   $bits2set  The bitmask which should be added to value
     * @return  void
     */
    public function bit_isset($value, $bits2test)
    {
        return (($value & $bits2test) == $bits2test);
    }
	
    /**
     * @brief   Print a success message which then automatically redirects 
     *          the user to a specified page
     * 
     * @global  array    $TEXT
     * @param   string   $message   
     * @param   string   $redirect  URL to the redirect page
     * @return  string   Templated success modal
     */
    public function print_success($message, $redirect = 'index.php')
    {
        global $TEXT;
        if (is_array($message)) {
            $message = implode('<br />', $message);
        }
        // fetch redirect timer for sucess messages from settings table
        $redirect_timer = ((defined('REDIRECT_TIMER')) && (REDIRECT_TIMER <= 10000)) ? REDIRECT_TIMER : 0;
        // add template variables
        // Setup template object, parse vars to it, then parse it
        $tpl = new Template(dirname($this->correct_theme_source('success.htt')));
        $tpl->set_file('page', 'success.htt');
        $tpl->set_block('page', 'main_block', 'main');
        $tpl->set_block('main_block', 'show_redirect_block', 'show_redirect');
        $tpl->set_var('MESSAGE', $message);
        $tpl->set_var('REDIRECT', $redirect);
        $tpl->set_var('REDIRECT_TIMER', $redirect_timer);
        $tpl->set_var('NEXT', $TEXT['NEXT']);
        $tpl->set_var('BACK', $TEXT['BACK']);
        if ($redirect_timer == -1) {
            $tpl->set_block('show_redirect', '');
        } else {
            $tpl->parse('show_redirect', 'show_redirect_block', true);
        }
        $tpl->parse('main', 'main_block', false);
        $tpl->pparse('output', 'page');
    }

    /**
     * @brief   Print an error message with a "back" link/button to a specified page
     * 
     * @global  array    $TEXT
     * @param   string   $message   
     * @param   string   $link  URL for the "back" link 
     * @return  string   Templated error modal with a "back" link/button
     */
    public function print_error($message, $link = 'index.php', $auto_footer = true)
    {
        global $TEXT;
        if (is_array($message)) {
            $message = implode('<br />', $message);
        }
        // Setup template object, parse vars to it, then parse it
        $success_template = new Template(dirname($this->correct_theme_source('error.htt')));
        $success_template->set_file('page', 'error.htt');
        $success_template->set_block('page', 'main_block', 'main');
        $success_template->set_var('MESSAGE', $message);
        $success_template->set_var('LINK', $link);
        $success_template->set_var('BACK', $TEXT['BACK']);
        $success_template->parse('main', 'main_block', false);
        $success_template->pparse('output', 'page');
        if ($auto_footer == true) {
            if (method_exists($this, "print_footer")) {
                $this->print_footer();
            }
            exit();
        }        
    }
    
    /**
     * @brief Validate and send a mail
     * 
     * @param string $fromaddress  // FROM:
     * @param string $toaddress    // TO:
     * @param string $subject      // SUBJECT 
     * @param string $message      // The Message to be send
     * @param string $fromname     // From Name
     * @return boolean
     */
    public function mail($fromaddress, $toaddress, $subject, $message, $fromname = '')
    {
        // INTEGRATED OPEN SOURCE PHPMAILER CLASS FOR SMTP SUPPORT AND MORE.
        // SOME SERVICE PROVIDERS DO NOT SUPPORT SENDING MAIL VIA PHP AS IT DOES NOT PROVIDE SMTP AUTHENTICATION.
        // NEW WBMAILER CLASS IS ABLE TO SEND OUT MESSAGES USING SMTP WHICH RESOLVE THESE ISSUE. (C. Sommer)

        $fromaddress = preg_replace('/[\r\n]/', '', $fromaddress);
        $toaddress =   preg_replace('/[\r\n]/', '', $toaddress);
        $subject =     preg_replace('/[\r\n]/', '', $subject);

        // create PHPMailer object and define default settings
        $myMail = new Mailer();
        
        // set user defined FROM address
        if ($fromaddress != '') {
            if ($fromname != '') {
                $myMail->FromName = $fromname;
            }
            
            $myMail->From = $fromaddress;        // FROM:
            $myMail->AddReplyTo($fromaddress);   // REPLY TO:
        }
        
        // define recepient and information to send out
        $myMail->AddAddress($toaddress);         // TO:
        $myMail->Subject = $subject;             // SUBJECT
        $myMail->Body =    nl2br($message);      // CONTENT (HTML)
        $myMail->AltBody = strip_tags($message); // CONTENT (TEXT)

        // check if there are any send mail errors and return accordingly
        if (!$myMail->Send()) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @brief   Checks if there is an override Backend Theme template file
     *
     * @param   string $sThemeFile set the template file name including the file extension
     * @return  string the relative theme path
     *
     */
    public function correct_theme_source($sThemeFile = 'start.htt')
    {
        $sSysThemeFile = WB_PATH . '/templates/default_theme/templates/' . $sThemeFile;
        $sOverrideFile = THEME_PATH . '/templates/' . $sThemeFile;
        if (file_exists($sOverrideFile)) {
            return $sOverrideFile;
        } elseif (file_exists($sSysThemeFile)) {
            return $sSysThemeFile;
        } else {
            die('Following Backend Theme file is missing: '. $sThemeFile);
        }
    }

    /**
     * @brief   Check if a foldername doesn't have invalid characters
     *
     * @param   string $str to check
     * @return  bool
     */
    public function checkFolderName($str)
    {
        return !(preg_match('#\^|\\\|\/|\.|\?|\*|"|\'|\<|\>|\:|\|#i', $str) ? true : false);
    }

    /**
     * @brief   Check the given path to make sure current path 
     *          is within given basedir normally document root
     *
     * @param  string $sCurrentPath
     * @param  string $sBaseDir
     * @return string $sCurrentPath or bool FALSE
     */
    public function checkpath($sCurrentPath, $sBaseDir = WB_PATH)
    {
        // Clean the cuurent path
        $sCurrentPath = rawurldecode($sCurrentPath);
        $sCurrentPath = realpath($sCurrentPath);
        $sBaseDir     = realpath($sBaseDir);
        // $sBaseDir needs to exist in the $sCurrentPath
        $pos = stripos($sCurrentPath, $sBaseDir);

        if ($pos === false) {
            return false;
        } elseif ($pos == 0) {
            return $sCurrentPath;
        } else {
            return false;
        }
    }
	
    /**
     * @brief   Look for modfiles in a specified module directory 
     *          and collect the modfiles in a array based on 
     *          modfile type (css|js_head|js_body)
     *
     * @param   string $sModuleName
     * @param   string $sEndPosition (frontend|backend)
     * @return  array
     */
    public function retrieve_modfiles_from_dir($sModuleName, $sEndPosition = "frontend")
    {
        $aCollection = array();
        $sModDir = '/modules/' . $sModuleName . '/';

        // retrieve frontend.css and/or frontend.override.css if exists
        $sCssFile = $sModDir . $sEndPosition . '%s.css';
        if(file_exists(sprintf(WB_PATH . $sCssFile, ''))){
            $aCollection['css'][] = sprintf(WB_URL . $sCssFile, '');
        }
        if(file_exists(sprintf(WB_PATH . $sCssFile, '.override'))){
            $aCollection['css'][] = sprintf(WB_URL . $sCssFile, '.override');
        }			

        // retrieve frontend.js and/or frontend.override.js if exists
        $sJsHeadFile = $sModDir . $sEndPosition . '%s.js';
        if(file_exists(sprintf(WB_PATH . $sJsHeadFile, ''))){
            $aCollection['js_head'][] = sprintf(WB_URL . $sJsHeadFile, '');
        }
        if(file_exists(sprintf(WB_PATH . $sJsHeadFile, '.override'))){
            $aCollection['js_head'][] = sprintf(WB_URL . $sJsHeadFile, '.override');
        }

        // retrieve frontend_body.js and/or frontend_body.override.js if exists
        $sJsBodyFile = $sModDir .  $sEndPosition . '_body%s.js';
        if(file_exists(sprintf(WB_PATH . $sJsBodyFile, ''))){
            $aCollection['js_body'][] = sprintf(WB_URL . $sJsBodyFile, '');
        }
        if(file_exists(sprintf(WB_PATH . $sJsBodyFile, '.override'))){
            $aCollection['js_body'][] = sprintf(WB_URL . $sJsBodyFile, '.override');
        }
        return $aCollection;
    }
	
	
    /**
     * @brief   Query the database in order to determine which modfiles 
     *          to use (from snippets, page-type modules, tools etc.)
     *          and create a assoc array of all the modfiles to be inserted.
     *
     * @global  object  $database
     * @param   string  $sEndPosition  frontend|backend
     * @return  array   modfiles collection as assoc array
     */	
    public function collect_modfiles($sEndPosition = 'frontend')
    {
        global $database;
        $aToInsert = array();

        $sSql = ''; // start collecting the SQL Query

        // get snippet modfiles if in FRONTEND
        if(defined('WB_FRONTEND')){
            $sSql .= "SELECT `directory` as 'module_name' FROM `{TP}addons` WHERE `function` LIKE '%snippet%'";
        }

        // check if we should use page-type module modfiles 
        $iPageTypePID = NULL;
        if(defined('PAGE_ID')){ 
            $iPageTypePID = PAGE_ID;
        } 		
        if(!defined('PAGE_ID') && 
            isset($_GET['page_id']) && 
            strposm($_SERVER['PHP_SELF'], array('pages/sections.php', 'pages/settings.php')) == false
        ){ 
            $iPageTypePID = (int) $_GET['page_id'];
        } 
        if($iPageTypePID != NULL && !defined('WB_FRONTEND')) {
            // dev note: frontend modfiles for page type modules are being added 
            // with the get_section_content() function in the frontend.functions.php
            if($sSql != ''){
                $sSql .= " UNION ALL ";
            }
            $sSql .= "SELECT `module` as 'module_name' FROM `{TP}sections` WHERE `page_id` = ".$iPageTypePID;			
        }

        // if it's a tool, get its modfiles
        if (isset($_GET['tool'])) {
            if($sSql != ''){
                    $sSql .= " UNION ALL ";
            }
            $sSql .= "SELECT `directory` as 'module_name' FROM `{TP}addons` WHERE `function` LIKE '%tool%' AND `directory`= '".$database->escapeString($_GET['tool'])."'";	
        }
        if($sSql != ''){
            if (($resSnippets = $database->query($sSql))) {
                while ($recSnippet = $resSnippets->fetchRow()) {	
                    $aToInsert = $this->retrieve_modfiles_from_dir($recSnippet['module_name'], $sEndPosition);
                }
            }	
        }			
        return $aToInsert;
    }
	
    /**
     * @brief   This method registers the files and writes them 
     *          to the DOM using the Insert Class methods 
     *
     * @param   string  $sModfileType  css|js|jquery|js_vars
     * @param   string  $sEndPosition  frontend|backend
     * @return  void    uses Insert Class methods
     */	
    public function register_modfiles($sModfileType = "css", $sEndPosition = "frontend")
    {        
        $aToInsert = $this->collect_modfiles($sEndPosition);
        
        $sModfileType = strtolower($sModfileType);
        switch ($sModfileType) {
            case 'css': 
                if(isset($aToInsert['css']) && is_array($aToInsert['css'])){
                    foreach($aToInsert['css'] as $sCssFile){
                        I::insertCssFile($sCssFile, 'HEAD TOP-');
                    }
                }
                break;

            case 'js_sysvars': 
            case 'jquery':		
                if($sModfileType != 'js_sysvars'){
                    $aJqueryFiles = array();
                    if ($sModfileType == 'jquery' and file_exists(WB_PATH . '/include/jquery/jquery-min.js')) {
                        $aJqueryFiles[] = WB_URL . '/include/jquery/jquery-min.js';
                        $aJqueryFiles[] = WB_URL . '/include/jquery/jquery-insert.js';
                        $aJqueryFiles[] = WB_URL . '/include/jquery/jquery-include.js';
                        $aJqueryFiles[] = WB_URL . '/include/jquery/jquery-migrate-min.js';

                        // workout to insert ui.css and theme 
                        $sFile = WB_URL . '/modules/jquery/jquery_theme.js';
                        $aJqueryFiles[] = file_exists(str_replace(WB_URL, WB_PATH, $sFile))
                        ? $sFile
                        : WB_URL . '/include/jquery/jquery_theme.js';
                        // workout to insert plugins functions, set in templatedir
                        $sFile = TEMPLATE_DIR . '/jquery_frontend.js';
                        if(file_exists(str_replace(WB_URL, WB_PATH, $sFile))){
                            $aJqueryFiles[] = $sFile;
                        }
                    }
                    foreach($aJqueryFiles as $sJsFile){
                        I::insertJsFile($aJqueryFiles, 'HEAD TOP-');
                    }
                }
                break;
            case 'js':
                // insert system vars to be ready for all JS code
                $sJsSysvars  = "\t\tvar URL          = '" . WB_URL . "';";
                $sJsSysvars .= "\n\t\tvar WB_URL       = '" . WB_URL . "';";

                if(defined("LANGUAGE")){
                    $sJsSysvars .= "\n\t\tvar LANGUAGE     = '" . strtolower(LANGUAGE) . "';";
                }	
                if(defined("PAGE_ID")){
                    $sJsSysvars .= "\n\t\tvar PAGE_ID      = '" . PAGE_ID . "';";
                }	
                if(isset($_GET['page_id']) && is_numeric($_GET['page_id'])){
                    $sJsSysvars .= "\n\t\tvar PAGE_ID      = '" . (int) $_GET['page_id'] . "';";
                }	
                if(isset($_GET['section_id']) && is_numeric($_GET['section_id'])){
                    $sJsSysvars .= "\n\t\tvar SECTION_ID   = '" . (int) $_GET['section_id'] . "';";
                }	
                if(defined("TEMPLATE_DIR")){
                    $sJsSysvars .= "\n\t\tvar TEMPLATE_DIR = '" . TEMPLATE_DIR . "';";
                }
                if(defined("THEME_URL") && !defined("WB_FRONTEND")){
                    $sJsSysvars .= "\n\t\tvar THEME_URL    = '" . THEME_URL . "';";
                }
                if(defined("ADMIN_URL") && !defined("WB_FRONTEND")){
                    $sJsSysvars .= "\n\t\tvar ADMIN_URL    = '" . ADMIN_URL . "';";
                }

                $sJsSysvars .= "\n\t\tvar SESSION_TIMEOUT = '" . $this->get_session_timeout() . "';";
                $sJsSysvars .= "\n";
                I::insertJsCode ($sJsSysvars, "HEAD TOP+", 'js_sysvars');

                // insert js files to head
                if(isset($aToInsert['js_head']) && is_array($aToInsert['js_head'])){
                    foreach($aToInsert['js_head'] as $sJsFile){
                        I::insertJsFile($sJsFile, 'HEAD BTM-');
                    }
                }

                // insert js files before the closing </body> tag
                if(isset($aToInsert['js_body']) && is_array($aToInsert['js_body'])){
                    foreach($aToInsert['js_body'] as $sJsFile){
                        I::insertJsFile($sJsFile, 'BODY BTM-');
                    }
                }
                break;
            default:
                break;
        }
    }
	
    /**
     * @brief   get the correct session timeout value
     *
     * @return  string
     */
    public function get_session_timeout()
    {		
        if ($sSessionTimeout = Settings::get("wb_session_timeout")) {

        } elseif ($sSessionTimeout = Settings::get("wb_secform_timeout")) {

        } else {
            $sSessionTimeout = "7200";
        }
        return $sSessionTimeout;
    }
    
}