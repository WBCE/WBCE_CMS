<?php
/**
 * Twig function: {{ theme_file('name.twig') }}
 * 
 * Acts as a wrapper for Wb::correct_theme_source(). It resolves the file 
 * location (Theme vs. Fallback) and converts the resulting absolute 
 * server path into a publicly accessible URL.
 * Checks if the requested file exists in the DEFAULT_THEME directory and if not,
 * it falls back to the system default theme folder: /templates/theme_fallbacks/
 * 
 * @param  string $fileLoc The filename including extension (e.g., 'layout.twig' or '../css/style.css').
 * @return string The public URL of the resolved theme file.
 */

defined('WB_PATH') or exit('sorry, no sufficient privileges.');

$oTwig->addFunction(new \Twig\TwigFunction
    ( "theme_file", 
        function (string $fileLoc = 'none') : string {
            $filePath = $GLOBALS['admin']->correct_theme_source($fileLoc);
            return get_url_from_path($filePath);            
        }
    )
);