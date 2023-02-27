<?php
/**
 * 
 * @package MarkdownReader
 * @author  Christian M. Stefan (Stefek)
 * @version 0.0.1
 * 
 * This package has the purpose to supply a simple way to parse Markdown files
 * and present its contents in a human readable format.
 * 
 * The implementation is meant to be as easy as possible.
 * 
 * Ways of implementation will be provided at a later iteration of the package
 * once it is ready for general consumption. (It's still in dev phase.)
 * 
 * Scripts and Packages use with MarkdownReader are
 *  - Parsedown for MD parsing < https://github.com/erusev/parsedown >
 *  - Prism for code highlighting < https://prismjs.com/ >
 * 
 */

require_once '../../config.php';
require_once __DIR__ . '/functions.php';

// Setup admin object, print header and check section permissions
$admin = new Admin('Start', 'start', false, true);

// Get CodeMirror config for code font and theme
$aCodeMirrorCfg = unserialize(Settings::Get("cmc_cfg", ""));

// Get CodeMirror font
$sFontName = $aCodeMirrorCfg['font'];
$sFontsDir = '/modules/CodeMirror_Config/codemirror/fonts/';
if(file_exists(WB_PATH.$sFontsDir.$sFontName.'.woff')){
    $sFontFile = WB_URL.$sFontsDir.$sFontName.'.woff';
} else {    
    $sFontFile = WB_URL.$sFontsDir.$sFontName.'.woff2';
}

// Get CodeMirror theme
$sTheme = "wbce-day";
if($aCodeMirrorCfg['theme'] == "wbce-night"){    
    $sTheme = "wbce-night";
}

$sDirUrl = get_url_from_path(__DIR__)
?><!DOCTYPE html>
<html lang="en">
    <head>
        <title><?=($_GET['title'])?> README.md</title>
        <meta charset="utf-8">        
        <link rel="stylesheet" href="<?=$sDirUrl.'/css/markdown.css'?>"/>
        <link rel="stylesheet" href="<?=$sDirUrl.'/css/md_reader.css'?>"/>
        <link rel="stylesheet" href="<?=$sDirUrl.'/prism-highlighter/'.$sTheme.'.css'?>"/>
        <script src="<?=$sDirUrl.'/prism-highlighter/prism.js'?>" type="text/javascript"></script>
        <style>
            @font-face {
                font-family: <?=$sFontName?>;
                src: url(<?=$sFontFile?>);
            } 
            code[class*="language-"],
            pre[class*="language-"],
            code,
            pre{
                font-family: <?=$sFontName?>;                
                font-size: <?=$aCodeMirrorCfg['font_size']?>px;
            }
        </style>
    </head>
    <body>
        <article class="markdown-body">
        <?php 
            $sFileLoc = urldecode($_GET['url']);
            echo render_md_file($sFileLoc);
        ?>
        </article>
    </body>
</html>