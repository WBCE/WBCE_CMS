<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * More Baking. Less Struggling.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

// function to extract var/value-pair from DB
function db_get_key_value($table, $key)
{
    global $database;
    $table = TABLE_PREFIX . $table;
    $sql = "SELECT value FROM $table WHERE name = '$key' LIMIT 1";
    return $database->get_one($sql);
}

// function to add a var/value-pair into settings-table
function db_add_key_value($key, $value)
{
    global $database;global $OK;global $FAIL;
    $table = TABLE_PREFIX . 'settings';
    $query = $database->query("SELECT value FROM $table WHERE name = '$key' LIMIT 1");
    if ($query->numRows() > 0) {
        echo "$key: already exists. $OK.<br />";
        return true;
    } else {
        $database->query("INSERT INTO $table (name,value) VALUES ('$key', '$value')");
        echo ($database->is_error() ? $database->get_error() . '<br />' : '');
        $query = $database->query("SELECT value FROM $table WHERE name = '$key' LIMIT 1");
        if ($query->numRows() > 0) {
            echo "$key: $OK.<br />";
            return true;
        } else {
            echo "$key: $FAIL!<br />";
            return false;
        }
    }
}

// function to add a new field into a table
function db_add_field($field, $table, $desc)
{
    global $database;global $OK;global $FAIL;
    $table = TABLE_PREFIX . $table;
    $query = $database->query("DESCRIBE $table '$field'");
    if ($query->numRows() == 0) {
        // add field
        $query = $database->query("ALTER TABLE $table ADD $field $desc");
        echo ($database->is_error() ? $database->get_error() . '<br />' : '');
        $query = $database->query("DESCRIBE $table '$field'");
        echo ($database->is_error() ? $database->get_error() . '<br />' : '');
        if ($query->numRows() > 0) {
            echo "'$field' added. $OK.<br />";
        } else {
            echo "adding '$field' $FAIL!<br />";
        }
    } else {
        echo "'$field' already exists. $OK.<br />";
    }
}

// analyze/check database tables
function mysqlCheckTables($dbName)
{
    global $database, $table_list;
    $table_prefix = TABLE_PREFIX;
    $sql = "SHOW TABLES FROM " . $dbName;
    $result = $database->query($sql);
    $data = array();
    $x = 0;

    while (($row = $result->fetchRow(MYSQLI_NUM)) == true) {
        $tmp = str_replace($table_prefix, '', $row[0]);

        if (stristr($row[0], $table_prefix) && in_array($tmp, $table_list)) {
            $sql = "CHECK TABLE " . $dbName . '.' . $row[0];
            $analyze = $database->query($sql);
            $rowFetch = $analyze->fetchRow(MYSQLI_ASSOC);
            $data[$x]['Op'] = $rowFetch["Op"];
            $data[$x]['Msg_type'] = $rowFetch["Msg_type"];
            $msgColor = '<span class="error">';
            $data[$x]['Table'] = $row[0];
            $msgColor = ($rowFetch["Msg_text"] == 'OK') ? '<span class="ok">' : '<span class="error">';
            $data[$x]['Msg_text'] = $msgColor . $rowFetch["Msg_text"] . '</span>';
            $x++;
        }
    }
    return $data;
}

// check existings tables for upgrade or install
function check_wb_tables()
{
    global $database, $table_list;

    // if prefix inludes '_' or '%'
    $search_for = addcslashes(TABLE_PREFIX, '%_');
    $get_result = $database->query('SHOW TABLES LIKE "' . $search_for . '%"');

    // $get_result = $database->query( "SHOW TABLES FROM ".DB_NAME);
    $all_tables = array();
    if ($get_result->numRows() > 0) {
        while ($data = $get_result->fetchRow()) {
            $tmp = str_replace(TABLE_PREFIX, '', $data[0]);
            if (in_array($tmp, $table_list)) {
                $all_tables[] = $tmp;
            }
        }
    }
    return $all_tables;
}

/* display a status message on the screen
 * @param string $message: the message to show
 * @param string $class:   kind of message as a css-class
 * @param string $element: witch HTML-tag use to cover the message
 * @return void
 */
function status_msg($message, $class = 'check', $element = 'span')
{
    // returns a status message
    $msg = '<' . $element . ' class="' . $class . '">';
    $msg .= '<strong>' . strtoupper(strtok($class, ' ')) . '</strong>: ';
    $msg .= $message . '</' . $element . '>';
    echo $msg;
}

// include required scripts and setup admin object
@require_once 'config.php';
require_once WB_PATH . '/framework/functions.php';
require_once WB_PATH . '/framework/class.admin.php';
$admin = new admin('Addons', 'modules', false, false);

// database tables including in WB package
$table_list = array('settings', 'groups', 'addons', 'pages', 'sections', 'search', 'users');
/*
$table_list = array (
'settings','groups','addons','pages','sections','search','users',
'mod_captcha_control','mod_code','mod_droplets','mod_form_fields',
'mod_form_settings','mod_form_submissions','mod_jsadmin','mod_menu_link',
'mod_news_comments','mod_news_groups','mod_news_posts','mod_news_settings',
'mod_output_filter','mod_wrapper','mod_wysiwyg'
);
 */

$OK = ' <span class="ok">OK</span> ';
$FAIL = ' <span class="error">FAILED</span> ';
$DEFAULT_THEME = 'advancedThemeWbFlat';
$stepID = 1;
$dirRemove = array(
/*
'[TEMPLATE]/allcss/',
'[TEMPLATE]/blank/',
'[TEMPLATE]/round/',
'[TEMPLATE]/simple/',
 */
);

$filesRemove['0'] = array(

    '[ADMIN]/preferences/details.php',
    '[ADMIN]/preferences/email.php',
    '[ADMIN]/preferences/password.php',

);

$filesRemove['1'] = array(

    '[TEMPLATE]/argos_theme/templates/access.htt',
    '[TEMPLATE]/argos_theme/templates/addons.htt',
    '[TEMPLATE]/argos_theme/templates/admintools.htt',
    '[TEMPLATE]/argos_theme/templates/error.htt',
    '[TEMPLATE]/argos_theme/templates/groups.htt',
    '[TEMPLATE]/argos_theme/templates/groups_form.htt',
    '[TEMPLATE]/argos_theme/templates/languages.htt',
    '[TEMPLATE]/argos_theme/templates/languages_details.htt',
    '[TEMPLATE]/argos_theme/templates/media.htt',
    '[TEMPLATE]/argos_theme/templates/media_browse.htt',
    '[TEMPLATE]/argos_theme/templates/media_rename.htt',
    '[TEMPLATE]/argos_theme/templates/modules.htt',
    '[TEMPLATE]/argos_theme/templates/modules_details.htt',
    '[TEMPLATE]/argos_theme/templates/pages.htt',
    '[TEMPLATE]/argos_theme/templates/pages_modify.htt',
    '[TEMPLATE]/argos_theme/templates/pages_sections.htt',
    '[TEMPLATE]/argos_theme/templates/pages_settings.htt',
    '[TEMPLATE]/argos_theme/templates/preferences.htt',
    '[TEMPLATE]/argos_theme/templates/setparameter.htt',
    '[TEMPLATE]/argos_theme/templates/settings.htt',
    '[TEMPLATE]/argos_theme/templates/start.htt',
    '[TEMPLATE]/argos_theme/templates/success.htt',
    '[TEMPLATE]/argos_theme/templates/templates.htt',
    '[TEMPLATE]/argos_theme/templates/templates_details.htt',
    '[TEMPLATE]/argos_theme/templates/users.htt',
    '[TEMPLATE]/argos_theme/templates/users_form.htt',
);

// check existing tables
$all_tables = check_wb_tables();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Upgrade script</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
html { overflow: -moz-scrollbars-vertical; /* Force firefox to always show room for a vertical scrollbar */ }

body {
	margin:0;
	padding:0;
	border:0;
	background: #EBF7FC;
	color:#000;
	font-family: 'Trebuchet MS', Verdana, Arial, Helvetica, Sans-Serif;
	font-size: small;
	height:101%;
}

#container {
	width:85%;
	background: #A8BCCB url(templates/wb_theme/images/background.png) repeat-x;
	border:1px solid #000;
	color:#000;
	margin:2em auto;
	padding:0 15px;
	min-height: 500px;
	text-align:left;
}

p { line-height:1.5em; }

form {
	display: inline-block;
	line-height: 20px;
	vertical-align: baseline;
}
input[type="submit"].restart {
	background-color: #FFDBDB;
	font-weight: bold;
}

h1,h2,h3,h4,h5,h6 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #369;
	margin-top: 1.0em;
	margin-bottom: 0.1em;
}

h1 { font-size:150%; }
h2 { font-size: 130%; border-bottom: 1px #CCC solid; }
h3 { font-size: 120%; }

.ok, .error { font-weight:bold; }
.ok { color:green; }
.error { color:red; }
.check { color:#555; }

.warning {
	width: 98%;
	background:#FFDBDB;
	padding:0.2em;
	margin-top:0.5em;
	border: 1px solid black;
}
.info {
	width: 98%;
	background:#99CC99;
	padding:0.2em;
	margin-top:0.5em;
	border: 1px solid black;
}

</style>
</head>
<body>
<div id="container">
<h1>WebsiteBaker Community Edition - Upgrade Script</h1>
<?php
// extract previous WBCE version from DB (if exists)
$old_wbce_version = array(
    'VERSION' => db_get_key_value('settings', 'wbce_version'),
    'TAG' => db_get_key_value('settings', 'wbce_tag'),
);

// extract previous WB-classic version from DB or admin/interface/info.php
$old_wb_version = array(
    'VERSION' => defined('WB_VERSION') ? WB_VERSION : VERSION,
    'REV' => defined('WB_REVISION') ? WB_REVISION : REVISION,
    'SP' => defined('WB_SP') ? WB_SP : SP,
);

// check if we upgrade from WBCE or WB-classic
if (!is_null($old_wbce_version['VERSION'])) {
    // we upgrade from an older WBCE version
    $oldVersion = 'WBCE v' . $old_wbce_version['VERSION'] . ' (' . $old_wbce_version['TAG'] . ')';
} else {
    // we upgrade from WB-classic, make sure that WB-classic is 2.7 or higher
    if (version_compare($old_wb_version['VERSION'], '2.7', '<')) {
        status_msg('<br />WebsiteBaker version below 2.7 can´t be upgraded to WBCE.<br />Please upgrade your WB version to 2.7 before upgrading to WBCE in a second step!', 'warning', 'div');
        echo '<br /><br /></div></body></html>';
        exit();
    }
    $oldVersion = 'WB-Classic v' . $old_wb_version['VERSION'] . ' (REV: ' . $old_wb_version['REV'] . ', SP: ' . $old_wb_version['SP'] . ')';
}

// string for new version
$newVersion = 'WBCE v' . WBCE_VERSION . ' (' . WBCE_TAG . ')';

// set addition settings if not exists, otherwise upgrade will be breaks
if (!defined('WB_SP')) {define('WB_SP', '');}
if (!defined('WB_REVISION')) {define('WB_REVISION', '');}

?>
<p>This script upgrades <strong> <?php echo $oldVersion;?></strong> to <strong> <?php echo $newVersion?> </strong>.<br />The upgrade script modifies the existing database to reflect the changes introduced with the new version.</p>

<?php
/*
 * Check if disclaimer was accepted
 */
if (!(isset($_POST['backup_confirmed']) && $_POST['backup_confirmed'] == 'confirmed')) {
    ?>
<h2>Step 1: Backup your files</h2>
<p>It is highly recommended to <strong>create a manual backup</strong> of the entire <strong>/pages folder</strong> and the <strong>MySQL database</strong> before proceeding.<br /><strong class="error">Note: </strong>The upgrade script alters some settings of your existing database!!! You need to confirm the disclaimer before proceeding.</p>

<form name="send" action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method="post">
<textarea cols="80" rows="5">DISCLAIMER: The WebsiteBaker CE upgrade script is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. One needs to confirm that a manual backup of the /pages folder (including all files and subfolders contained in it) and backup of the entire WebsiteBaker CE database was created before you can proceed.</textarea>
<br /><br /><input name="backup_confirmed" type="checkbox" value="confirmed" />&nbsp;I confirm that a manual backup of the /pages folder and the MySQL database was created.
<br /><br /><input name="send" type="submit" value="Start upgrade script" />
</form>
<br />

<?php
status_msg('<br />You need to confirm that you have created a manual backup of the /pages directory and the MySQL database before you can proceed.', 'warning', 'div');
    echo '<br /><br /></div></body></html>';
    exit();
}

echo '<h2>Step ' . (++$stepID) . ' : Updating database entries</h2>';

/**********************************************************
 *  - Adding field default_theme to settings table
 */
echo "<br />Adding default_theme to settings table<br />";
db_update_key_value('settings', 'default_theme', $DEFAULT_THEME);
/**********************************************************
 *  - install droplets
 */
$drops = (!in_array("mod_droplets", $all_tables)) ? "<br />Install droplets<br />" : "<br />Upgrade droplets<br />";
echo $drops;

$file_name = (!in_array("mod_droplets", $all_tables) ? "install.php" : "upgrade.php");
require_once WB_PATH . "/modules/droplets/" . $file_name;

// check again all tables, to get a new array
if (sizeof($all_tables) < sizeof($table_list)) {$all_tables = check_wb_tables();}
/**********************************************************
 *  - check tables comin with WebsiteBaker
 */
$check_text = 'total ';
// $check_tables = mysqlCheckTables( DB_NAME ) ;

if (sizeof($all_tables) == sizeof($table_list)) {
    echo '<h4>NOTICE: Your database ' . DB_NAME . ' has ' . sizeof($all_tables) . ' ' . $check_text . ' tables from ' . sizeof($table_list) . ' included in package ' . $OK . '</h4>';
} else {
    status_msg('<br />Can\'t run Upgrade, missing tables', 'warning', 'div');
    echo '<h4>Missing required tables. You can install them in backend->addons->modules->advanced. Then again run upgrade-script.php</h4>';
    $result = array_diff($table_list, $all_tables);
    echo '<h4 class="warning"><br />';
    while (list($key, $val) = each($result)) {
        echo TABLE_PREFIX . $val . ' ' . $FAIL . '<br>';
    }
    echo '<br /></h4>';
    echo '<br /><form action="' . $_SERVER['PHP_SELF'] . '">';
    echo '<input type="submit" value="kick me back" style="float:left;" />';
    echo '</form>';
    if (defined('ADMIN_URL')) {
        echo '<form action="' . ADMIN_URL . '" target="_self">';
        echo '&nbsp;<input type="submit" value="kick me to the Backend" />';
        echo '</form>';
    }
    echo "<br /><br /></div>
        </body>
        </html>
        ";
    exit();
}

/**********************************************************
 *  - Adding field sec_anchor to settings table
 */
echo "<br />Adding sec_anchor to settings table<br />";
$cfg = array(
    'sec_anchor' => 'wb_',
);
foreach ($cfg as $key => $value) {
    db_add_key_value($key, $value);
}

/**********************************************************
 *  - Adding redirect timer to settings table
 */
echo "<br />Adding redirect timer to settings table<br />";
$cfg = array(
    'redirect_timer' => '1500',
);
foreach ($cfg as $key => $value) {
    db_add_key_value($key, $value);
}

/**********************************************************
 *  - Adding rename_files_on_upload to settings table
 */
echo "<br />Updating rename_files_on_upload to settings table<br />";
$cfg = array(
    'rename_files_on_upload' => 'ph.*?,cgi,pl,pm,exe,com,bat,pif,cmd,src,asp,aspx,js',
);
db_update_key_value('settings', 'rename_files_on_upload', $cfg['rename_files_on_upload']);

/**********************************************************
 *  - Adding mediasettings to settings table
 */
echo "<br />Adding mediasettings to settings table<br />";
$cfg = array(
    'mediasettings' => '',
);

foreach ($cfg as $key => $value) {
    db_add_key_value($key, $value);
}

/**********************************************************
 *  - Adding fingerprint_with_ip_octets to settings table
 */
echo "<br />Adding fingerprint_with_ip_octets to settings table<br />";
$cfg = array(
    'fingerprint_with_ip_octets' => '0',
    'secure_form_module' => 'mtab',
);
foreach ($cfg as $key => $value) {
    db_add_key_value($key, $value);
}

/**********************************************************
 *  - Add field "redirect_type" to table "mod_menu_link"
 */
echo "<br />Adding field redirect_type to mod_menu_link table<br />";
db_add_field('redirect_type', 'mod_menu_link', "INT NOT NULL DEFAULT '302' AFTER `target_page_id`");

/**********************************************************
 *  - Update search no results database filed to create
 *  valid XHTML if search is empty
 */
if (version_compare(WB_VERSION, '2.8', '<')) {
    echo "<br />Updating database field `no_results` of search table: ";
    $search_no_results = addslashes('<tr><td><p>[TEXT_NO_RESULTS]</p></td></tr>');
    $sql = 'UPDATE `' . TABLE_PREFIX . 'search` ';
    $sql .= 'SET `value`=\'' . $search_no_results . '\' ';
    $sql .= 'WHERE `name`=\'no_results\'';
    echo ($database->query($sql)) ? ' $OK<br />' : ' $FAIL<br />';
}
/**********************************************************
 * upgrade media folder index protect files
 */
$dir = (WB_PATH . MEDIA_DIRECTORY);
echo '<h4>Upgrade ' . MEDIA_DIRECTORY . '/ index.php protect files</h4><br />';
$array = rebuildFolderProtectFile($dir);
if (sizeof($array)) {
    print '<br /><strong>Upgrade ' . sizeof($array) . ' ' . MEDIA_DIRECTORY . '/ protect files</strong>' . " $OK<br />";
} else {
    print '<br /><strong>Upgrade ' . MEDIA_DIRECTORY . '/ protect files</strong>' . " $FAIL!<br />";
    print implode('<br />', $array);
}
/**********************************************************
 * upgrade posts folder index protect files
 */
$sPostsPath = WB_PATH . PAGES_DIRECTORY . '/posts';
echo '<h4>Upgrade /posts/ index.php protect files</h4><br />';
$array = rebuildFolderProtectFile($sPostsPath);
if (sizeof($array)) {
    print '<br /><strong>Upgrade ' . sizeof($array) . ' /posts/ protect files</strong>' . " $OK<br />";
} else {
    print '<br /><strong>Upgrade /posts/ protect files</strong>' . " $FAIL!<br />";
    print implode('<br />', $array);
}
/* *****************************************************************************
 * - check for deprecated / never needed files
 */
if (sizeof($filesRemove)) {
    echo '<h2>Step ' . (++$stepID) . ': Remove deprecated and old files</h2>';
}
$searches = array(
    '[ADMIN]',
    '[MEDIA]',
    '[PAGES]',
    '[FRAMEWORK]',
    '[MODULES]',
    '[TEMPLATE]',
);
$replacements = array(
    substr(ADMIN_PATH, strlen(WB_PATH) + 1),
    MEDIA_DIRECTORY,
    PAGES_DIRECTORY,
    '/framework',
    '/modules',
    '/templates',
);

foreach ($filesRemove as $filesId) {
    $msg = '';
    foreach ($filesId as $file) {
        $file = str_replace($searches, $replacements, $file);
        $file = WB_PATH . '/' . $file;
        if (file_exists($file)) {
            // try to unlink file
            if (!is_writable($file) || !unlink($file)) {
                // save in err-list, if failed
                $msg .= $file . '<br />';
            }
        }
    }

    if ($msg != '') {
        $msg = '<br /><br />Following files are deprecated, outdated or a security risk and
				    can not be removed automatically.<br /><br />Please delete them
					using FTP and restart upgrade-script!<br /><br />' . $msg . '<br />';
        status_msg($msg, 'error warning', 'div');
        echo '<p style="font-size:120%;"><strong>WARNING: The upgrade script failed ...</strong></p>';

        echo '<form action="' . $_SERVER['SCRIPT_NAME'] . '">';
        echo '&nbsp;<input name="send" type="submit" value="Restart upgrade script" />';
        echo '</form>';
        echo '<br /><br /></div></body></html>';
        exit;
    }
}

/**********************************************************
 * - check for deprecated / never needed files
 */
if (sizeof($dirRemove)) {
    echo '<h2>Step  ' . (++$stepID) . ': Remove deprecated and old folders</h2>';
    $searches = array(
        '[ADMIN]',
        '[MEDIA]',
        '[PAGES]',
        '[TEMPLATE]',
    );
    $replacements = array(
        substr(ADMIN_PATH, strlen(WB_PATH) + 1),
        MEDIA_DIRECTORY,
        PAGES_DIRECTORY,
        '/templates',
    );
    $msg = '';
    foreach ($dirRemove as $dir) {
        $dir = str_replace($searches, $replacements, $dir);
        $dir = WB_PATH . '/' . $dir;
        if (is_dir($dir)) {
            // try to delete dir
            if (!rm_full_dir($dir)) {
                // save in err-list, if failed
                $msg .= $dir . '<br />';
            }
        }
    }
    if ($msg != '') {
        $msg = '<br /><br />Following files are deprecated, outdated or a security risk and
					can not be removed automatically.<br /><br />Please delete them
					using FTP and restart upgrade-script!<br /><br />' . $msg . '<br />';
        status_msg($msg, 'error warning', 'div');
        echo '<p style="font-size:120%;"><strong>WARNING: The upgrade script failed ...</strong></p>';
        echo '<form action="' . $_SERVER['SCRIPT_NAME'] . '">';
        echo '&nbsp;<input name="send" type="submit" value="Restart upgrade script" />';
        echo '</form>';
        echo '<br /><br /></div></body></html>';
        exit;
    }
}

/**********************************************************
 * upgrade modules if newer version is available
 */
$aModuleList = array('news');
foreach ($aModuleList as $sModul) {
    if (file_exists(WB_PATH . '/modules/' . $sModul . '/upgrade.php')) {
        $currModulVersion = get_modul_version($sModul, false);
        $newModulVersion = get_modul_version($sModul, true);
        if ((version_compare($currModulVersion, $newModulVersion) <= 0)) {
            echo '<h2>Step ' . (++$stepID) . ' : Upgrade module \'' . $sModul . '\' to version ' . $newModulVersion . '</h2>';
            require_once WB_PATH . '/modules/' . $sModul . '/upgrade.php';
        }
    }
}
/**********************************************************
 *  - Reload all addons
 */

echo '<h2>Step ' . (++$stepID) . ' : Reload all addons database entry (no upgrade)</h2>';
////delete modules
//$database->query("DELETE FROM ".TABLE_PREFIX."addons WHERE type = 'module'");
// Load all modules
if (($handle = opendir(WB_PATH . '/modules/'))) {
    while (false !== ($file = readdir($handle))) {
        if ($file != '' and substr($file, 0, 1) != '.' and $file != 'admin.php' and $file != 'index.php') {
            load_module(WB_PATH . '/modules/' . $file);
            //     upgrade_module($file, true);
        }
    }
    closedir($handle);
}
echo '<br />Modules reloaded<br />';

////delete templates
//$database->query("DELETE FROM ".TABLE_PREFIX."addons WHERE type = 'template'");
// Load all templates
if (($handle = opendir(WB_PATH . '/templates/'))) {
    while (false !== ($file = readdir($handle))) {
        if ($file != '' and substr($file, 0, 1) != '.' and $file != 'index.php') {
            load_template(WB_PATH . '/templates/' . $file);
        }
    }
    closedir($handle);
}
echo '<br />Templates reloaded<br />';

////delete languages
//$database->query("DELETE FROM ".TABLE_PREFIX."addons WHERE type = 'language'");
// Load all languages
if (($handle = opendir(WB_PATH . '/languages/'))) {
    while (false !== ($file = readdir($handle))) {
        if ($file != '' and substr($file, 0, 1) != '.' and $file != 'index.php') {
            load_language(WB_PATH . '/languages/' . $file);
        }
    }
    closedir($handle);
}
echo '<br />Languages reloaded<br />';

/**********************************************************
 *  - End of upgrade script
 */

// require(WB_PATH.'/framework/initialize.php');

if (!defined('DEFAULT_THEME')) {define('DEFAULT_THEME', $DEFAULT_THEME);}
if (!defined('THEME_PATH')) {define('THEME_PATH', WB_PATH . '/templates/' . DEFAULT_THEME);}
/**********************************************************
 *  - Set Version to new Version
 */
echo '<br />Update database version number to ' . WBCE_VERSION . ' (Tag: ' . WBCE_TAG . ')';
db_update_key_value('settings', 'wbce_version', WBCE_VERSION);
db_update_key_value('settings', 'wbce_tag', WBCE_TAG);
db_update_key_value('settings', 'wb_version', VERSION);   // Legacy: WB-classic
db_update_key_value('settings', 'wb_revision', REVISION); // Legacy: WB-classic
db_update_key_value('settings', 'wb_sp', SP);             // Legacy: WB-classic

echo '<p style="font-size:120%;"><strong>Congratulations: The upgrade script is finished ...</strong></p>';
status_msg('<br />Please delete the file <strong>upgrade-script.php</strong> via FTP before proceeding.', 'warning', 'div');
// show buttons to go to the backend or frontend
echo '<br />';

if (defined('WB_URL')) {
    echo '<form action="' . WB_URL . '/">';
    echo '&nbsp;<input type="submit" value="kick me to the Frontend" />';
    echo '</form>';
}
if (defined('ADMIN_URL')) {
    echo '<form action="' . ADMIN_URL . '/">';
    echo '&nbsp;<input type="submit" value="kick me to the Backend" />';
    echo '</form>';
}

echo '<br /><br /></div></body></html>';
