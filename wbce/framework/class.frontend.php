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

//no direct file access
if(count(get_included_files())==1) header("Location: ../index.php", TRUE, 301);

class Frontend extends Wb
{
    // defaults
    public $default_link; 
    public $default_page_id;
    
    // when multiple blocks are used, show home page blocks on
    // pages where no content is defined (search, login, ...)
    public $default_block_content = true;

    // page details
    public $page;
    public $page_id; 
    public $page_title; 
    public $menu_title; 
    public $parent; 
    public $root_parent; 
    public $level; 
    public $position; 
    public $visibility;
    public $page_description; 
    public $page_keywords; 
    public $page_link;
    public $page_trail = array();

    public $page_access_denied;
    public $page_no_active_sections;

    // website settings
    public $website_title; 
    public $website_description; 
    public $website_keywords; 
    public $website_header; 
    public $website_footer;

    // database query related
    public $extra_where_sql; 
    public $sql_where_language;

    public function __construct()
    {
        parent::__construct(SecureForm::FRONTEND);
    }
        
   

    public function page_select()
    {
        global $page_id, $no_intro;
        
         // We have a Maintainance situation print under construction if not in group admin
        if (defined("WB_MAINTAINANCE_MODE") and WB_MAINTAINANCE_MODE === true and !$this->ami_group_member('1'))
            $this->print_under_construction();             
        
        
        // We have no page id and are supposed to show the intro page
        if ((INTRO_PAGE and !isset($no_intro)) and (!isset($page_id) or !is_numeric($page_id))) {
            // Since we have no page id check if we should go to intro page or default page
            // Get intro page content
            $filename = WB_PATH . PAGES_DIRECTORY . '/intro' . PAGE_EXTENSION;
            if (file_exists($filename)) {
                $handle = @fopen($filename, "r");
                $content = @fread($handle, filesize($filename));
                @fclose($handle);
                $this->preprocess($content);
                // send intro.php as header to allow parsing of php statements
                header("Location: " . WB_URL . PAGES_DIRECTORY . "/intro" . PAGE_EXTENSION . ""); 
                echo ($content);
                return false;
            }
        }
        // Check if we should add page language sql code
        if (PAGE_LANGUAGES) {
            $this->sql_where_language = ' AND `language`=\'' . LANGUAGE . '\'';
        }
        // Get default page
        // Check for a page id
        $now = time();
        $sSql = 'SELECT `p`.`page_id`, `link` ';
        $sSql .= 'FROM `{TP}pages` AS `p` INNER JOIN `{TP}sections` USING(`page_id`) ';
        $sSql .= 'WHERE `parent`=0 AND `visibility`=\'public\' ';
        $sSql .= 'AND ((' . $now . '>=`publ_start` OR `publ_start`=0) ';
        $sSql .= 'AND (' . $now . '<=`publ_end` OR `publ_end`=0)) ';
        if (trim($this->sql_where_language) != '') {
            $sSql .= trim($this->sql_where_language) . ' ';
        }
        $sSql .= 'ORDER BY `p`.`position` ASC';
        $get_default = $this->_oDb->query($sSql);
        $default_num_rows = $get_default->numRows();
        if (!isset($page_id) or !is_numeric($page_id)) {
            // Go to or show default page
            if ($default_num_rows > 0) {
                $fetch_default         = $get_default->fetchRow(MYSQLI_ASSOC);
                $this->default_link    = $fetch_default['link'];
                $this->default_page_id = $fetch_default['page_id'];
                // Check if we should redirect or include page inline
                if (HOMEPAGE_REDIRECTION) {
                    // Redirect to page
                    header("Location: " . $this->page_link($this->default_link));
                    exit();
                } else {
                    // Include page inline
                    $this->page_id = $this->default_page_id;
                }
            } else {
                // No pages have been added, so print under construction page
                $this->print_under_construction();
                exit();
            }
        } else {
            $this->page_id = $page_id;
        }
        // Get default page link
        if (!isset($fetch_default)) {
            $fetch_default         = $get_default->fetchRow(MYSQLI_ASSOC);
            $this->default_link    = $fetch_default['link'];
            $this->default_page_id = $fetch_default['page_id'];
        }
        return true;
    }

    public function get_page_details()
    {
        if ($this->page_id != 0) {
            // Query page details
            $sSql = 'SELECT * FROM `{TP}pages` WHERE `page_id` = ' . (int) $this->page_id;
            $resPage = $this->_oDb->query($sSql);
            
            // Make sure page was found in database otherwise print "Page not found" message
            if ($resPage->numRows() == 0) exit("Page not found");
            
            // Fetch page details
            $this->page = $resPage->fetchRow(MYSQLI_ASSOC);
            
            // Check if the page language is also the selected language. If not, send headers again.           
            if ($this->page['language'] != LANGUAGE) {
                $sUri = $this->page_link($this->page['link']).'?lang=' . $this->page['language'];
                if (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != '') {
                    // check if there is an query-string
                    header('Location: ' . $sUri . '&' . $_SERVER['QUERY_STRING']);
                } else {
                    header('Location: ' . $sUri);
                }
                exit();
            } 
            // Begin code to set details as either variables of constants
            defined('PAGE_ID')    or define('PAGE_ID',    $this->page['page_id']);
            defined('PAGE_TITLE') or define('PAGE_TITLE', $this->page['page_title']);
            $this->page_title = PAGE_TITLE;
            
            // Menu Title
            $sMenuTitle = $this->page['menu_title'];
            defined('MENU_TITLE') or define('MENU_TITLE', ($sMenuTitle != '') ? $sMenuTitle : PAGE_TITLE);
            $this->menu_title = MENU_TITLE;
            
            // Page parent
            defined('PARENT') or define('PARENT', $this->page['parent']);
            $this->parent = $this->page['parent'];
            
            // Page root parent 
            defined('ROOT_PARENT') or define('ROOT_PARENT', $this->page['root_parent']);
            $this->root_parent = $this->page['root_parent'];
            
            // Page level
            defined('LEVEL') or define('LEVEL', $this->page['level']);
            $this->level = $this->page['level'];
            
            // Page position
            $this->level = $this->page['position'];
            
            // Page visibility
            defined('VISIBILITY') or define('VISIBILITY', $this->page['visibility']);
            $this->visibility = $this->page['visibility'];
            
            // Page trail
            foreach (explode(',', $this->page['page_trail']) as $pid) {
                $this->page_trail[$pid] = $pid;
            }
            
            // Page description
            $this->page_description = $this->page['description'];
            if ($this->page_description != '') {
                define('PAGE_DESCRIPTION', $this->page_description);
            } else {
                define('PAGE_DESCRIPTION', WEBSITE_DESCRIPTION);
            }
            
            // Page keywords
            $this->page_keywords = $this->page['keywords'];
            
            // Page link
            $this->link = $this->page_link($this->page['link']);
            $_SESSION['PAGE_ID'] = $this->page_id;
            $_SESSION['HTTP_REFERER'] = $this->link;

            // End code to set details as either variables or constants
        }

        // Figure out what template to use
        if (!defined('TEMPLATE')) {
            $sTemplate = DEFAULT_TEMPLATE;
            if (isset($this->page['template']) and $this->page['template'] != '') {
                if (file_exists(WB_PATH . '/templates/' . $this->page['template'] . '/index.php')) {
                    $sTemplate = $this->page['template'];
                }
            }
            define('TEMPLATE', $sTemplate);
        }
        // Set the template dir
        define('TEMPLATE_DIR', WB_URL . '/templates/' . TEMPLATE);

        // Check if user is allowed to view this page
        if ($this->page && $this->page_is_visible($this->page) == false) {
            if (VISIBILITY == 'deleted' or VISIBILITY == 'none') {
                // User isn't allowed on this page so tell them
                $this->page_access_denied = true;
            } elseif (VISIBILITY == 'private' or VISIBILITY == 'registered') {
                // Check if the user is authenticated
                if ($this->is_authenticated() == false) {
                    // User needs to login first
                    header("Location: " . WB_URL . "/account/login.php?redirect=" . $this->link);
                    exit(0);
                } else {
                    // User isnt allowed on this page so tell them
                    $this->page_access_denied = true;
                }

            }
        }
        // check if there is at least one active section
        if ($this->page && $this->page_is_active($this->page) == false) {
            $this->page_no_active_sections = true;
        }
    }

    public function get_website_settings()
    {
        // Set visibility SQL code
        // Never show pages of visibility none, hidden or deleted
        $this->extra_where_sql = "`visibility` != 'none' AND `visibility` != 'hidden' AND `visibility` != 'deleted'";
        // Set extra private sql code
        if ($this->is_authenticated() == false) {
            // if user is not authenticated, don't show private pages either
            $this->extra_where_sql .= " AND `visibility` != 'private'";
            // and 'registered' without frontend login doesn't make much sense!
            if (FRONTEND_LOGIN == false) {
                $this->extra_where_sql .= " AND `visibility` != 'registered'";
            }
        }
        $this->extra_where_sql .= $this->sql_where_language;

        // Work-out if any possible in-line search boxes should be shown
        if(!defined('SHOW_SEARCH')){
            $bShowSearch = false;
            if     (SEARCH == 'public')                                          $bShowSearch = true;
            elseif (SEARCH == 'private'    && VISIBILITY == 'private')           $bShowSearch = true;
            elseif (SEARCH == 'private'    && $this->is_authenticated() == true) $bShowSearch = true;
            elseif (SEARCH == 'registered' && $this->is_authenticated() == true) $bShowSearch = true;
                        
            define('SHOW_SEARCH', $bShowSearch);            
        }
        
        // define SHOW_MENU constant
        defined('SHOW_MENU') or define('SHOW_MENU', true);    
    }
    

    // Function to show the "Under Construction" page
    public function print_under_construction()
    {
        global $MESSAGE;
        
        $sProtocol = "HTTP/1.0";  //Header https://yoast.com/http-503-site-maintenance-seo/
        if ( "HTTP/1.1" == $_SERVER["SERVER_PROTOCOL"] ) $protocol = "HTTP/1.1";

        header( "$sProtocol 503 Service Unavailable", true, 503 );
        header( "Retry-After: 7200" ); //Searchengine revisits after 2 Hours 
        
        $Template=DEFAULT_TEMPLATE;
        if (defined('TEMPLATE')) $Template = TEMPLATE;
        
        $TemplatePath = WB_PATH.'/templates/'.$Template."/systemplates/maintainance.tpl.php";
        $DefaultPath  = WB_PATH."/templates/systemplates/maintainance.tpl.php";
        
        if (is_file($TemplatePath))  include_once ($TemplatePath); 
        else                         include_once ($DefaultPath);
        
        exit;
    }
    
    /**
     * @brief  This method is used together with the AdminTool 
     *         Captcha and Advanced-Spam-Protection (ASP) Control
     * 
     * @return string
     */
    public function renderAspHoneypots(){
       $sASPFields = '';
       if (ENABLED_ASP) { 
           $sTimeStamp = time();
           $_SESSION['submitted_when'] = $sTimeStamp;
           // add some honeypot-fields
           ob_start();	
       ?>
           <div style="display:none;">
               <input type="hidden" name="submitted_when" value="<?=$sTimeStamp ?>" />
               <p class="nixhier">
                   <label for="email-address" title="Leave this field email-address blank">Email address:</label>
                   <input id="email-address" name="email-address" size="60" value="" />
               </p>
               <p class="nixhier">				
                   <label for="name" title="Leave this field name blank">Username (id):</label>
                   <input id="name" name="name" size="60" value="" /></p>
               <p class="nixhier">
                   <label for="full_name" title="Leave this field full_name blank">Full Name:</label>
                   <input id="full_name" name="full_name" size="60" value="" />
               </p>
           </div>		
       <?php 
           $sASPFields = ob_get_clean();
       } //end:ENABLED_ASP
       return $sASPFields;
    }
    
    // Obsolete since the introduction of OpF Dashboard into the core
    /**
     * // public function preprocess(&$content)
     * // this method is obsolete since [wblinkXXX] replacement is done
     * // standardly in the OpF Dashboard since 1.4.0
     */
    public function preprocess($content = NULL)
    {
        // Return a note that this method is obsolete
        if ($this->is_authenticated()) {
            if ($this->ami_group_member('1') && defined('WB_DEBUG') && WB_DEBUG == true) { 
                // if Admin and WB_DEBUG on: display Notice to inform the developer
                $caller = debug_backtrace()[0]; 
                $sNotice  = "<br />The <i><b>".__FUNCTION__."</b> method</i> of <i>class <b>".__CLASS__."</b></i> is obsolete.";
                $sNotice .= "<br /> There's no need to use it any longer.";
                $sNotice .= "<br />Used in file <b>".$caller['file']."</b> on line <b>".$caller['line']."</b>";
                trigger_error($sNotice);
            }
        }
        return;        
    }

    // No longer supported since WBCE 1.4.0
    public function menu()
    {
        // Return a note that this method is no longer supported
        if ($this->is_authenticated()) {
            if ($this->ami_group_member('1') && defined('WB_DEBUG') && WB_DEBUG == true) { 
                // if Admin and WB_DEBUG on: display Notice to inform the developer
                $caller = debug_backtrace()[0]; 
                $sNotice  = "<br />The <i><b>".__FUNCTION__."</b> method</i> of <i>class <b>".__CLASS__."</b></i> is obsolete.";
                $sNotice .= "<br /> Please consider using the <b>show_menu2</b> function.";
                $sNotice .= "<br />Used in file <b>".$caller['file']."</b> on line <b>".$caller['line']."</b>";
                trigger_error($sNotice);
            }
        }
        return;  
    }
    
    // No longer supported since WBCE 1.4.0
    public function show_menu()
    {
         // Return a note that this method is no longer supported
        if ($this->is_authenticated()) {
            if ($this->ami_group_member('1') && defined('WB_DEBUG') && WB_DEBUG == true) { 
                // if Admin and WB_DEBUG on: display Notice to inform the developer
                $caller = debug_backtrace()[0]; 
                $sNotice  = "<br />The <i><b>".__FUNCTION__."</b> method</i> of <i>class <b>".__CLASS__."</b></i> is obsolete.";
                $sNotice .= "<br /> Please consider using the <b>show_menu2</b> function.";
                $sNotice .= "<br />Used in file <b>".$caller['file']."</b> on line <b>".$caller['line']."</b>";
                trigger_error($sNotice);
            }
        }
        return;  
    }
}