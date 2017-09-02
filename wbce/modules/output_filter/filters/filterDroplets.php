<?php
/**
 * execute droplets
 * @param string $content
 * @return string 
 */
    function doFilterDroplets($content) {
        // check file and include
        if(file_exists(WB_PATH .'/modules/droplets/droplets.php')) {
            include_once(WB_PATH .'/modules/droplets/droplets.php');
            
            // remove <p> tags that are added by CKE editor every time you 
            // have a droplet in an empty line
            if(strpos($content, '<p>[[') !== false){
                $content = str_replace('<p>[[','[[', $content);
                $content = str_replace(']]</p>',']]', $content);
            }  
            
            // load filter function 
            if(function_exists('evalDroplets')) {
                $content = evalDroplets($content, 'frontend');
            }
        }
        return $content;
    }
