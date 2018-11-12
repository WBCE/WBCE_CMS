<?php
/**
 * @brief   Creates placeholders (hooks) for the placement of inserted 
 *          CSS, JS, HTML code and Meta Tags
 * 
 *          If your template is completely messed up and contains invalid HTML,
 *          this filter may produce strange results. 
 *  
 *          In general it would be the best option to place those Placeholders 
 *          yourself within the template!
 *  
 *          The whole functionality enables Droplets, Snipets and Modules 
 *          to insert their own Code and MetaTags at will and in a simple way.
 * 
 * @author  Norbert Heimsath (Norhei)
 *          Initial code and the main part of the RegEx definitions
 * @author  Christian M. Stefan <stefek@designthings.de>
 *          Total rewrite and correction of some of the hooks/placeholders
 *          that are being populated automatically
 * 
 * @param   string $sContent
 * @return  string
 */
function doFilterGeneratePlaceholders($sContent) {

	
    // Template does not want placeholders to be populated on automatic?
    // OK, return right away.
    if (strpos($sContent,'<!--(NO PH)-->') !== false) {
		return $sContent;
    }
   
    // While working with jQuery and other JS Libraries it's important to have its  
    // CSS files added before the actual JS code. 
    // We have taken care of it using the proper order of placeholders/hooks.
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
            "#<\s*/\s~head\s*>#iU",
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
    
    // populate Placeholders in $sContent
    foreach ($aPlaceholders as $sPlaceholder => $rec) {
        $sPlaceholder = '<!--(PH) ' . $sPlaceholder . '+ -->';
        if (strpos($sContent, $sPlaceholder) === false)
            $sContent = preg_replace($rec[0], $rec[1], $sContent);
    }
   
    return $sContent;    
}