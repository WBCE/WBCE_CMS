<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

/*
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
FUNCTIONS REQUIRED TO EDIT THE OPTIONAL MODULE CSS FILES
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
 */

// this function checks the validity of the specified module directory
if (!function_exists('check_module_dir')) {
    function check_module_dir($mod_dir)
    {
        // check if module directory is formal correct (only characters: "a-z,0-9,_,-")
        if (!preg_match('/^[a-z0-9_-]+$/iD', $mod_dir)) {
            return '';
        }

        // check if the module folder contains the required info.php file
        return (file_exists(WB_PATH . '/modules/' . $mod_dir . '/info.php')) ? $mod_dir : '';
    }
}

// this function checks if the specified optional module file exists
if (!function_exists('mod_file_exists')) {
    function mod_file_exists($mod_dir, $mod_file = 'frontend.css')
    {
        // check if the module file exists
        return file_exists(WB_PATH . '/modules/' . $mod_dir . '/' . $mod_file);
    }
}

// this function displays the "Edit CSS" button in modify.php
if (!function_exists('edit_module_css')) {
    function edit_module_css($mod_dir)
    {
        global $page_id, $section_id;

        // check if the required edit_module_css.php file exists
        if (!file_exists(WB_PATH . '/modules/edit_module_files.php')) {
            return;
        }

        // check if specified module directory is valid
        if (check_module_dir($mod_dir) == '') {
            return;
        }

        // check if frontend.css or backend.css exist
        $frontend_css = mod_file_exists($mod_dir, 'frontend.css');
        $backend_css = mod_file_exists($mod_dir, 'backend.css');

        // output the edit CSS submtin button if required
        if ($frontend_css || $backend_css) {
            // default text used for the edit CSS routines if not defined in the WB core language files
            $edit_css_caption = (isset($GLOBALS['TEXT']['CAP_EDIT_CSS'])) ? $GLOBALS['TEXT']['CAP_EDIT_CSS'] : 'Edit CSS';
            ?>

            <form name="edit_module_file" action="<?php echo WB_URL . '/modules/edit_module_files.php?page_id=' . $page_id;?>" method="post" style="margin: 0; text-align:right;">
               <input type="hidden" name="page_id" value="<?php echo $page_id;?>" />
               <input type="hidden" name="section_id" value="<?php echo $section_id;?>" />
               <input type="hidden" name="mod_dir" value="<?php echo $mod_dir;?>" />
               <input type="hidden" name="edit_file" value="<?php echo ($frontend_css) ? 'frontend.css' : 'backend.css';?>" />
               <input type="hidden" name="action" value="edit" />
               <input type="submit" value="<?php echo $edit_css_caption;?>" class="mod_<?php echo $mod_dir;?>_edit_css" />
            </form>
            <?php
        }
    }
}

// this function displays a button to toggle between CSS files (invoked from edit_css.php)
if (!function_exists('toggle_css_file')) {
    function toggle_css_file($mod_dir, $base_css_file = 'frontend.css')
    {
        global $page_id, $section_id;
        // check if the required edit_module_css.php file exists
        if (!file_exists(WB_PATH . '/modules/edit_module_files.php')) {
            return;
        }

        // check if specified module directory is valid
        if (check_module_dir($mod_dir) == '') {
            return;
        }

        // do sanity check of specified css file
        if (!in_array($base_css_file, array('frontend.css', 'backend.css'))) {
            return;
        }

        // display button to toggle between the two CSS files: frontend.css, backend.css
        $toggle_file = ($base_css_file == 'frontend.css') ? 'backend.css' : 'frontend.css';
        if (mod_file_exists($mod_dir, $toggle_file)) {
            ?>
            <form name="toggle_module_file" action="<?php echo WB_URL . '/modules/edit_module_files.php?page_id=' . $page_id;?>" method="post" style="margin: 0; align:right;">
                <input type="hidden" name="page_id" value="<?php echo $page_id;?>" />
                <input type="hidden" name="section_id" value="<?php echo $section_id;?>" />
                <input type="hidden" name="mod_dir" value="<?php echo $mod_dir;?>" />
                <input type="hidden" name="edit_file" value="<?php echo $toggle_file;?>" />
                <input type="hidden" name="action" value="edit" />
                <input type="submit" value="<?php echo ucwords($toggle_file);?>" class="mod_<?php echo $mod_dir;?>_edit_css" />
            </form>
            <?php
        }
    }
}


// function to obtain the module language file depending on the backend language of the current user
if (!function_exists('get_module_language_file')) {
   function get_module_language_file($mymod_dir) {
      $mymod_dir = strip_tags($mymod_dir);
      if(file_exists(WB_PATH .'/modules/' .$mymod_dir .'/languages/' .LANGUAGE .'.php')) {
         // a module language file exists for the users backend language
         return (WB_PATH .'/modules/' .$mymod_dir .'/languages/' .LANGUAGE .'.php');
      } else {
         // an English module language file must exist in all multi-lingual modules
         if(file_exists(WB_PATH .'/modules/' .$mymod_dir .'/languages/EN.php')) {
            return (WB_PATH .'/modules/' .$mymod_dir .'/languages/EN.php');
         } else {
            echo '<p><strong>Error: </strong>';
            echo 'Default language file (EN.php) of module "' .htmlentities($mymod_dir) .'" does not exist.</p><br />';
            return false;
         }
      }
   }
}