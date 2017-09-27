<?php
/**
 * execute droplets
 * @param string $content
 * @return string 
 */
function doFilterDroplets($content) {
    // check file and include
    $sFile = WB_PATH .'/modules/droplets/droplets.php';
    if(file_exists($sFile)) {
        include_once $sFile;
        
                    
        // remove <p> tags that are added by CKE editor every time you 
        // have a droplet in an empty line
  
        if(strpos($content, '<p>[') !== false){
            $content = str_replace('<p>[#[','[#[', $content);
            $content = str_replace('<p>[[','[[', $content);
            $content = str_replace(']]</p>',']]', $content);
        } 

        //remove commented droplets 
        // example: [#[Lorem?blocks=6]] 
        // the hash symbol will cause the droplet to be excluded from output 
        $content = preg_replace('/\[\#\[[^]]*]]/', '', $content); 
        
        // load filter function 
        if(function_exists('evalDroplets')) {
            $content = evalDroplets($content, 'frontend');
        }
    }
    return $content;
}