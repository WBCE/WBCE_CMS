<?php

  $oTwig->addFunction(new \Twig\TwigFunction("md_filter", 
        function ($sStr) {
            $sStr = preg_replace('#\*{2}(.*?)\*{2}#', '<b>$1</b>', $sStr);
            $sStr = preg_replace('#\*{1}(.*?)\*{1}#', '<i>$1</i>', $sStr);
            $sStr = preg_replace('#\`{1}(.*?)\`{1}#', '<pre class="code">$1</pre>', $sStr);
            $sStr = nl2br($sStr);
            #require_once __DIR__.'/_skel/Parsers/Text/Markdown.class.php';
            #return new Markdown($sStr);
            return ($sStr);
        }
    ));

