<?php
/**
    @brief A rudimentary Minify function 
    
    I hope this is usefull 
    https://stackoverflow.com/questions/1418780/removing-new-lines-except-in-pre
    
    Removing of comments is done by another function 
*/

function  doFilterBasicHtmlMinify($content)
{
    $search = array(
        '/\>[^\S ]+/s', //strip whitespaces after tags, except space
        '/[^\S ]+\</s', //strip whitespaces before tags, except space
        '/(\s)+/s'  // shorten multiple whitespace sequences
        );
    $replace = array(
        '>',
        '<',
        '\\1'
        );

    // We need to ignore   pre|code|samp|xmp tags , as whitespaces in there are intentionaly  
    $blocks = preg_split('/(<\/?(pre|code|samp|xmp)[^>]*>)/', $content, null, PREG_SPLIT_DELIM_CAPTURE);
    $content = '';
    
    // Runn through all blocks and put result in content
    foreach($blocks as $i => $block)
    {
    if($i % 4 == 2)
        $content .= $block; //break out <pre>...</pre> with \n's
    else 
        $content .= preg_replace($search, $replace, $block);
    }

    return $content;
}

