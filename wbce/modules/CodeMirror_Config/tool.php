<?php
/**
 *
 * @category        admintool / initialize 
 * @package         CodeMirror_Config
 * @author          Christian M. Stefan
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WBCE 1.5.4

 *
 */

// Must include code to stop this file from being accessed directly
defined('WB_PATH') or die("This file can't be accessed directly!");

// check if user is allowed to use admin-tools (to prevent this file to be called by an unauthorized user e.g. from a code-section)
$admin->get_permission('admintools') or die(header('Location: ../../index.php'));

define('CMC_TOOL_RUNNING', true);

$sSelected = 'wbce-day';
$aToolUri = ADMIN_URL.'/admintools/tool.php?tool='. basename(__DIR__);
$sCodeMirrorPath = __DIR__ . '/codemirror';
$sThemeLoc = $sCodeMirrorPath.'/theme/';

// are we saving the form or just showing the form and needing config from DB?
if ($doSave && !$admin->checkFTAN()) 
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], $returnUrl, false);

if ($saveSettings) {
    // We set the setting
    if (isset($_POST["save_settings"])) {       
        $aCfg = array(
            'theme'      => $admin->get_post("theme"),
            'font'       => $admin->get_post("font"),
            'font_size'  => $admin->get_post("font_size"),
        );
        $setError = Settings::Set("cmc_cfg", serialize( $aCfg ));
        toolMsg($setError, $returnUrl);
    } 
}

// Get the Config from DB
$aCfg = unserialize(Settings::Get("cmc_cfg", ""));
registerCodeMirror('code', 'x-php');

// form data fill
$aThemeFiles = list_files_from_dir($sThemeLoc, 'css');
$aFontFiles = list_files_from_dir($sCodeMirrorPath.'/fonts', ['woff2', 'woff']);
$aFontSizes = [12, 13, 14, 15, 16, 17, 18];
$oTwig = getTwig(__DIR__ . '/twig/');
$aToTwig = [];
$aToTwig['cfg']             = $aCfg;
$aToTwig['cmc_code_sample'] = cmc_code_sample();
$aToTwig['aThemeFiles']     = $aThemeFiles;
$aToTwig['aFontFiles']      = $aFontFiles;
$aToTwig['aFontSizes']      = $aFontSizes;
$oTemplate = $oTwig->load('tool.twig');
$oTemplate->display($aToTwig);

ob_start() ?>
<script>
var input = document.getElementById("select");
function selectTheme() {
    var theme = input.options[input.selectedIndex].textContent;
    code.setOption("theme", theme);
    location.hash = "#" + theme;
}
code.setOption("theme", '<?= $aCfg['theme'] ?>');    
CodeMirror.on(window, "hashchange", function() {
    var theme = location.hash.slice(1);
    if (theme) { input.value = theme; selectTheme(); }
});

// select font
function selectFontFamily(element) { 
    const selected = element.options[element.selectedIndex].value;  
    $(".CodeMirror").css("fontFamily", selected);
}

function selectFontSize(element) {   
   const selected = element.options[element.selectedIndex].value;  
    $(".CodeMirror").css("fontSize", selected + 'px');
}
</script>
<?php 
$sJsCode = ob_get_clean();
$aJsFiles = [
    $CodeMirror_dir . "lib/codemirror.js",
    $CodeMirror_dir . "mode/javascript/javascript.js",
    $CodeMirror_dir . 'mode/php/php.js',
    $CodeMirror_dir . 'mode/clike/clike.js',
    $CodeMirror_dir . "addon/selection/active-line.js",
    $CodeMirror_dir . "addon/edit/matchbrackets.js",
    $CodeMirror_dir . 'addon/fold/foldcode.js',
    $CodeMirror_dir . 'addon/fold/foldgutter.js',
    $CodeMirror_dir . 'addon/fold/brace-fold.js',
    $CodeMirror_dir . 'addon/fold/comment-fold.js',
    $CodeMirror_dir . 'addon/display/fullscreen.js'
];
I::insertJsFile($aJsFiles, 'BODY TOP+');
I::insertJsCode($sJsCode, 'BODY BTM-');

// CodeMirror CSS
$aCssFiles = [
    $CodeMirror_dir . 'lib/codemirror.css',          
    $CodeMirror_dir . 'addon/fold/foldgutter.css',          
    $CodeMirror_dir . 'theme/wbce-day.css',          
    $CodeMirror_dir . 'addon/display/fullscreen.css',    
    
    // make use of theme_fallbacks CSS files 
    // if the THEME does not deliver them yet
    get_url_from_path($admin->correct_theme_source('../css/ACPI_backend.css')),
    get_url_from_path($admin->correct_theme_source('../css/ACPI_content.css')),
    get_url_from_path($admin->correct_theme_source('../css/ACPI_buttons.css'))
];
I::insertCssFile($aCssFiles, 'HEAD TOP+');
// Load all the CodeMirror Theme CSS Files
foreach($aThemeFiles as $sCssFile)
    I::insertCssFile(get_url_from_path($sThemeLoc).$sCssFile, 'HEAD BTM');

function cmc_code_sample(){
    ob_start();
    ?>
/** 
 * This is a sample of how your code syntax will be highlighted
 */
global $wb, $page_id, $TEXT, $MENU, $HEADING;

$sRetVal = '<div class="login-box">'.PHP_EOL;
// Return a system permission
function get_permission($name, $type = 'system') {
    global $wb;
    // Append to permission type
    $type .= '_permissions';
    // Check if we have a section to check for
    if ($name == 'start') {
        return true;
    } else {
        // Set template permissions var
        $template_permissions = $wb->get_session('TEMPLATE_PERMISSIONS');
        // Return true if system perm = 1
        if (isset($$type) && is_array($$type) && is_numeric(array_search($name, $$type))) {
            if ($type == 'system_permissions') {
                return true;
            }
        }
    }
}
<?php
    return ob_get_clean();
}