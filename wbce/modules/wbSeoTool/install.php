<?php
/**
 * WBCE CMS AdminTool: wbSeoTool
 *
 * install.php
 * Seeds default configuration into the shared {TP}settings table
 * (via the Settings class — same mechanism as modules/CodeMirror_Config).
 * No module-owned table.
 *
 * @package     wbSeoTool
 * @author      Christian M. Stefan 
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 */

// prevent this file from being accessed directly
if (!defined('WB_PATH')) exit("Cannot access this file directly " . __FILE__);

$aSettings = [
    'iTitleCount' => [
        'use'     => true,
        'minimum' => 30,
        'optimum' => 50,
        'maximum' => 60,
    ],
    'iDescriptionCount' => [
        'use'     => true,
        'minimum' => 90,
        'optimum' => 150,
        'maximum' => 160,
    ],
    'keywordsConfig' => [
        'use'         => true,
        // If you want to replace the label "Keywords", you can do it here.
        'wordReplace' => 'keywords',
    ],
    'menuTitleConfig' => [
        'use' => false,
    ],
    'rewriteUrl' => [
        'use'      => false,
        // Column name in the {TP}pages table, if enabled.
        'dbString' => 'slug',
    ],
    'bUseRemainingChars' => true,
    'bUseFlags'          => false,
];

Settings::set('seo_cfg', json_encode($aSettings));
