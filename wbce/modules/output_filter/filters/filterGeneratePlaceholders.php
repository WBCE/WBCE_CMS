<?php
/**
    @brief Creates placeholders for moving S and other Stuff around in the template 
    @param string $content
    @return string
 
 

If your template is completely fucked up and invalid HTML,
this filter may produce strange results.  
 
This filter produces some Placeholders for your template based on good will and good guess. 
These Placeholders are later used to move around small snipits of JS, CSS, HTML and more to 
Predefined places in the template. 

In general it would be far better to place those Placeholders yourself.  

This enables Droplets , snipets and modules to bring their own JS CSS and more whithout 
the use of the very limited modfiles.    
 
 */
function doFilterGeneratePlaceholders($sContent) {

    
// HTML tags are often written in strange fashion (<  head  > <body style="...") < / head >
// thats the reason we need to use Regex here , in the later processes we prefer php buildin functions as they are much faster.

// HEAD
    
    // look for <!--(PH) JS HEAD TOP+ -->  if not present , add it, 
    if (strpos($sContent,'<!--(PH) JS HEAD TOP+ -->') === false){
        $sContent = preg_replace(
            "/<\s*head.*>/iU",
            "$0\n<!--(PH) JS HEAD TOP+ -->\n<!--(PH) JS HEAD TOP- -->\n" ,
            $sContent,1 );
    }
    
    // look for <!--(PH) CSS HEAD TOP+ -->  if not present , add it. 
    // Comes second so its on to and loaded before the JS  
    if (strpos($sContent,'<!--(PH) CSS HEAD TOP+ -->') === false){
        $sContent = preg_replace(
            "/<\s*head.*>/iU",
            "$0\n<!--(PH) CSS HEAD TOP+ -->\n<!--(PH) CSS HEAD TOP- -->\n" ,
            $sContent );
    }
    
    // look for <!--(PH) META HEAD+ -->  if not present , add it.
    // Here Metas needs to come first , so its added fisrst 
    // Still it will override all former Metas !!!
    if (strpos($sContent,'<!--(PH) META HEAD+ -->' ) === false){
        $sContent = preg_replace(
            "#<\s*/\s*head\s*>#iU",
            "\n<!--(PH) META HEAD+ -->\n<!--(PH) META HEAD- -->\n$0" ,
            $sContent );
    }
    
    
    
    // look for <!--(PH) CSS HEAD BTM+ -->  if not present , add it.
    // HERE CSS needs to come first , so its added first 
    // Still it will override all former css !!!
    if (strpos($sContent,'<!--(PH) CSS HEAD BTM+ -->' ) === false){
        $sContent = preg_replace(
            "#<\s*/\s*head\s*>#iU",
            "\n<!--(PH) CSS HEAD BTM+ -->\n<!--(PH) CSS HEAD BTM- -->\n$0" ,
            $sContent );
    }
    
    // look for <!--(PH) JS HEAD BTM+ -->  if not present , add it, 
    if (strpos($sContent,'<!--(PH) JS HEAD BTM+ -->' ) === false){
        $sContent = preg_replace(
            "#<\s*/\s*head\s*>#iU",
            "\n<!--(PH) JS HEAD BTM+ -->\n<!--(PH) JS HEAD BTM- -->\n$0" ,
            $sContent );
    }

// BODY

    // look for <!--(PH) HTML BODY TOP+ -->  if not present , add it, 
    // needs to be loaded after a possible JS
    if (strpos($sContent,'<!--(PH) HTML BODY TOP+ -->') === false){
        $sContent = preg_replace(
            "/<\s*body.*>/iU",
            "$0\n<!--(PH) HTML BODY TOP+ -->\n<!--(PH) HTML BODY TOP- -->\n" ,
            $sContent );
    }
    
    // look for <!--(PH) JS BODY TOP+ -->  if not present , add it, 
    if (strpos($sContent,'<!--(PH) JS BODY TOP+ -->') === false){
        $sContent = preg_replace(
            "/<\s*body.*>/iU",
            "$0\n<!--(PH) JS BODY TOP+ -->\n<!--(PH) JS BODY TOP- -->\n" ,
            $sContent );
    }

    
    // look for <!--(PH) JS BODY BTM+ -->  if not present , add it,
    // HTML footers need to be loaded before final JS stuff
    if (strpos($sContent,'<!--(PH) HTML BODY BTM+ -->' ) === false){
        $sContent = preg_replace(
            "#<\s*/\s*body\s*>#iU",
            "\n<!--(PH) HTML BODY BTM+ -->\n<!--(PH) HTML BODY BTM- -->\n$0" ,
            $sContent );
    }

    // look for <!--(PH) JS BODY BTM+ -->  if not present , add it, 
    if (strpos($sContent,'<!--(PH) JS BODY BTM+ -->' ) === false){
        $sContent = preg_replace(
            "#<\s*/\s*body\s*>#iU",
            "\n<!--(PH) JS BODY BTM+ -->\n<!--(PH) JS BODY BTM- -->\n$0" ,
            $sContent );
    }

// Placeholders for replacement actions 

///- The title Tag gets some placeholders for easy replacement and to show 
///  its part of the party. But only the first  
    if (strpos($sContent,'<!--(PH) TITLE+ -->' ) === false){
        $sContent = preg_replace(
            "/<\s*title.*?<\s*\/\s*title\s*>/si",
            "<!--(PH) TITLE+ -->$0<!--(PH) TITLE- -->",
            $sContent,
            1
        );
    }
 
    // Second Step , we have no title , still create a Placeholder on top of all 
    if (strpos($sContent,'<!--(PH) TITLE+ -->' ) === false){
        $sContent = preg_replace(
            "/<\s*head.*>/iU",
            "$0\n<!--(PH) TITLE+ -->\n<!--(PH) TITLE- -->\n",
            $sContent,
            1
        );
    }
    
    // Going for metas description
    if (strpos($sContent,'<!--(PH) META DESC+ -->' ) === false){
        $sPattern = '';
        $sContent = preg_replace(
            "/<\s*meta[^>]*?\=\"description\".*?\/?\s*>/si",
            "<!--(PH) META DESC+ -->$0<!--(PH) META DESC- -->",
            $sContent,
            1
        );
    }
    
        // Going for metas keywords
    if (strpos($sContent,'<!--(PH) META KEY+ -->' ) === false){
        $sPattern = '';
        $sContent = preg_replace(
            "/<\s*meta[^>]*?\=\"keywords\".*?\/?\s*>/si",
            "<!--(PH) META KEY+ -->$0<!--(PH) META KEY- -->",
            $sContent,
            1
        );
    }





   // thats it here we go  
   
    return $sContent;
    
    
}
