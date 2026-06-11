<?php
/**
 * tsf_insert.php — Twig wrappers for the I:: asset queue.
 *
 * Available Twig functions:
 *
 *   insertFile($url, $pos, $type)        Single CSS or JS file (type detected by extension)
 *   insertCssFile($url, $pos, $id)       Enqueue a CSS file
 *   insertJsFile($url, $pos, $id)        Enqueue a JS file
 *   insertCssCode($code, $pos, $id)      Inject inline CSS
 *   insertJsCode($code, $pos, $id)       Inject inline JS
 *   insertHtmlCode($html, $pos, $id)     Inject an HTML block at a body position
 *   insertTitle($title)                  Replace the page <title> tag
 *   insertMeta($nameOrTag, $content)     Set a meta tag (name + content or raw tag string)
 *
 * Intentionally NOT available as Twig functions:
 *
 *   insertCssBundle / insertJsBundle — Bundles must be configured once at boot time
 *     (initialize_fe.php or index.php), before any template is rendered. Calling
 *     them from inside a Twig view would be too late and make load order unpredictable. *
 *   addUrlToken — Infrastructure setup that belongs at boot time, not during rendering.
 *     Has no meaningful use from within a Twig template.
 */

/**
 * insertFile
 * insert a single JS or CSS File
 */
$oTwig->addFunction(new \Twig\TwigFunction("insertFile",
    function ($sFileLoc = '', $sDomPos = '', $sType = '') {
        if($sFileLoc == '') return; // return early if no loc is set
        if($sType == ''){
          $sType = pathinfo($sFileLoc, PATHINFO_EXTENSION);
        }
        if($sDomPos == ''){
            $sDomPos = 'BODY BTM-';
            if(strtolower($sType) == 'css'){
                $sDomPos = 'HEAD BTM-';
            }
        }
        $call = 'insert'.strtolower(ucfirst($sType)).'File';
        I::$call($sFileLoc, $sDomPos);
    }
));

/**
 * insertJsFile  // Twig adaptation of the Insert class method
 */
$oTwig->addFunction(new \Twig\TwigFunction("insertJsFile",
    function ($uFileLoc = '', $sDomPos = 'BODY BTM-', $sID = '') {
        if (!is_array($uFileLoc)) {
            if ($uFileLoc != '') {
                I::insertJsFile($uFileLoc, $sDomPos, [], $sID);
            } else {
                return;
            }
        } else {
            foreach ($uFileLoc as $sLoc) {
                I::insertJsFile($sLoc, $sDomPos, [], $sID);
            }
        }
    }
));

$oTwig->addFunction(new \Twig\TwigFunction("insertJsCode",
    function ($sCode = '', $sDomPos = 'BODY BTM-', $sID = '') {
        I::insertJsCode($sCode, $sDomPos, $sID);
    }
));

/**
 * insertCssFile  // Twig adaptation of the Insert class method
 */
$oTwig->addFunction(new \Twig\TwigFunction("insertCssFile",
    function ($uFileLoc = '', $sDomPos = 'HEAD BTM-', $sID = '') {
        if (!is_array($uFileLoc)) {
            if ($uFileLoc != '') {
                I::insertCssFile($uFileLoc, $sDomPos, [], $sID);
            } else {
                return;
            }
        } else {
            foreach ($uFileLoc as $sLoc) {
                I::insertCssFile($sLoc, $sDomPos, [], $sID);
            }
        }
    }
));

$oTwig->addFunction(new \Twig\TwigFunction("insertCssCode",
    function ($sCode = '', $sDomPos = 'HEAD BTM-', $sID = '') {
        I::insertCssCode($sCode, $sDomPos, $sID);
    }
));

/**
 * insertHtmlCode  // inject a raw HTML block at a body position
 */
$oTwig->addFunction(new \Twig\TwigFunction("insertHtmlCode",
    function ($sCode = '', $sDomPos = 'body_early', $sID = '') {
        I::insertHtmlCode($sCode, $sDomPos, $sID);
    }
));

/**
 * insertTitle  // replace the page <title> tag
 */
$oTwig->addFunction(new \Twig\TwigFunction("insertTitle",
    function ($sTitle = '') {
        I::insertTitle($sTitle);
    }
));

/**
 * insertMeta  // set a meta tag by name + content, or pass a raw tag string
 * Uses method_exists guard — insertMeta() is new in I.php (old API had insertMetaTag only).
 */
$oTwig->addFunction(new \Twig\TwigFunction("insertMeta",
    function ($nameOrTag = '', $content = 'replace') {
        if (method_exists('I', 'insertMeta')) {
            I::insertMeta($nameOrTag, $content);
        }
    }
));