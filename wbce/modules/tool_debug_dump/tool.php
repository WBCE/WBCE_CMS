<?php
/**
 * WBCE CMS AdminTool: tool_debug_dump
 *
 * @platform    WBCE CMS 1.7.0
 * @package     modules/tool_debug_dump
 * @author      Christian M. Stefan (https://www.wbEasy.de)
 * @copyright   Christian M. Stefan (2026)
 * @license     GNU/GPL2
 */
 
// prevent this file from being accessed directly
defined('WB_PATH') or exit("insufficient privileges");
// check if user is allowed to use admin-tools (to prevent this file to be called by an unauthorized user e.g. from a code-section)
$admin->get_permission('admintools') or die(header('Location: ../../index.php'));

$sSelected = 'wbce-day';
$aToolUri = ADMIN_URL.'/admintools/tool.php?tool='. basename(__DIR__);
$sCodeMirrorPath = WB_PATH . '/modules/CodeMirror_Config/codemirror';

// are we saving the form or just showing the form and needing config from DB?
if ($doSave && !$admin->checkFTAN()) {
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], $returnUrl, false);
}

if ($saveSettings) {
    // We set the setting
    if (isset($_POST["save_settings"])) {       
        $cfg = array(
            'theme'      => $admin->get_post("theme"),
            'font'       => $admin->get_post("font"),
            'font_size'  => $admin->get_post("font_size"),
            'height'     => $admin->get_post("height"),
            'run'        => $admin->get_post("run"),
        );
        $setError = Settings::Set("debug_dump_cfg", serialize( $cfg ));
        if($setError == true){
            toolMsg($setError, $returnUrl);
        } else {
            header("Location: {$returnUrl}");
        }
    } 
}

// Get the Config from DB
$cfg = unserialize(Settings::Get("debug_dump_cfg", ""));

// form data fill
$aFontFiles  = list_files_from_dir($sCodeMirrorPath.'/fonts', ['woff2', 'woff']);
$aFontSizes = [12, 13, 14, 15, 16, 17, 18];
$oTwig = getTwig(__DIR__ . '/twig/');
$aToTwig = [];
$aToTwig['cfg']             = $cfg;
$aToTwig['demo_dump']       = _demo_array();
$aToTwig['aFontFiles']      = $aFontFiles;
$aToTwig['aFontSizes']      = $aFontSizes;
$oTemplate = $oTwig->load('tool.twig');
$oTemplate->display($aToTwig);

ob_start() ?>
<script>
$(function () {

    var ddCssBase = <?php echo json_encode(get_url_from_path(__DIR__) . '/css/'); ?>;

    var $ddThemeLink = $('<link id="dd-live-theme" rel="stylesheet">').appendTo('head');

    function applyTheme(theme) {
        if (!theme || theme === '0') return;
        $ddThemeLink.attr('href', ddCssBase + 'debug_dump_' + theme + '.css');
    }

    function applyFont(font) {
        if (!font || font === '0') return;
        $('.dd-pre').css('font-family', font + ', monospace');
    }

    function applySize(size) {
        if (!size || size === '0') return;
        $('.dd-pre').css('font-size', size + 'px');
    }
        
    // ── Live Theme-Change ──────────────────────────────────────────────────────
    $('#theme_sel').on('change', function () {
        applyTheme($(this).val());
    }).trigger('change');   

    // ── Live Font-Change ───────────────────────────────────────────────────────
    $('#font_name_sel').on('change', function () {
        applyFont($(this).val());
    }).trigger('change');

    // ── Font-Size ───────────────────────────────────────────────────────────────
    $('#font_size_sel').on('change', function () {
        applySize($(this).val());
    }).trigger('change');    

    // ── Enabled / Disabled ────────────────────────────────────────────────────
    function applyRunState() {
        var isEnabled = $('#dd-run-enabled').is(':checked');
        if (isEnabled) {
            $('#dd-settings-section').show();
        } else {
            $('#dd-settings-section').hide();
        }
    }

    $('input[name="run"]').on('change', applyRunState);
    applyRunState(); 

});
</script>
<?php 
$sJsCode = ob_get_clean();
$aJsFiles = [
];
I::insertJsFile($aJsFiles, 'BODY TOP+');
I::insertJsCode($sJsCode, 'BODY BTM-');

// CodeMirror CSS
$aCssFiles = [
    // make use of theme_fallbacks CSS files 
    // if the THEME does not deliver them yet
    get_url_from_path($admin->correct_theme_source('../css/ACPI_backend.css')),
    get_url_from_path($admin->correct_theme_source('../css/ACPI_content.css')),
    get_url_from_path($admin->correct_theme_source('../css/ACPI_buttons.css'))
];
I::insertCssFile($aCssFiles, 'HEAD TOP+');
// --- Load all Fonts from CodeMirror/fonts folder
$sCss = '';        
$sModUrl = WB_URL.'/modules/CodeMirror_Config/codemirror/';
foreach($aFontFiles as $sFileName){

    $sFam = strstr($sFileName, '.', true);
    $sCss .= "
    @font-face {
        font-family: ".$sFam.";         src: url(".$sModUrl."fonts/".$sFileName.");
    }";
}

$sCss .= " 
    .dd-pre {
        font-family: ".$cfg['font'].";
        font-size: ".$cfg['font_size']."px;
        max-height: ".$cfg['height']."px;
    }";     
I::insertCssCode($sCss, 'HEAD TOP+', 'CodeMirror_loadFontCss');

function _demo_array(){
    // ── nice Demo-Object for debug_dump() ─────────────────────────────

    return [
        'app_name' => 'WBCE Debug Dump Demo',
        'version'  => '2.1.4',
        'enabled'  => true,
        'debug_mode' => false,
        'pi'       => 3.1415926535,
        'null_value' => null,
        'empty_array' => [],
        'empty_object' => (object)[],

        'user' => [
            'id' => 7842,
            'username' => 'stefek',
            'roles' => ['admin', 'editor', 'developer'],
            'active' => true,
            'last_login' => '2026-03-27 20:15:33',
            'settings' => [
                'theme' => 'classic',
                'language' => 'de',
                'notifications' => true,
                'font_size' => 14
            ]
        ],

        'config' => [
            'database' => [
                'host' => 'localhost',
                'port' => 3306,
                'database' => 'wbce_cms',
                'charset' => 'utf8mb4',
                'credentials' => [
                    'username' => 'wbce_user',
                    'password' => '••••••••••'   // shows as string
                ]
            ],
            'features' => [
                'debug_dump' => true,
                'code_mirror' => true,
                'full_screen' => true,
                'live_preview' => false
            ]
        ],

        'statistics' => [
            'pages' => 47,
            'users' => 12,
            'modules' => 8,
            'memory_usage' => 2457600,     // Bytes
            'execution_time' => 0.0847,    // Sekunden
            'peak_memory' => '4.82 MB'
        ],

        'colors' => [
            'primary'   => '#d4a800',
            'success'   => '#2e9e6b',
            'warning'   => '#f59e0b',
            'danger'    => '#d82d20',
            'background'=> '#fffef5'
        ],

        'tags' => ['php', 'twig', 'javascript', 'css', 'debug', 'development'],

        'metadata' => (object) [
            'demo'       => 'Debug Dump',
            'created_at' => '2026-03-01',
            'author'     => 'Christian M. Stefan',
            'platform'   => 'WBCE 1.7.x'
        ],

        'deep_nesting_example' => [
            'level1' => [
                'level2' => [
                    'level3' => [
                        'level4' => [
                            'message' => 'This is a deeply nested structure to test indentation and readability',
                            'value' => 42,
                            'active' => true
                        ]
                    ]
                ]
            ]
        ]
    ];
}
