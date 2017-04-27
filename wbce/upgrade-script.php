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

/*
////////////////////////////////////////////////////////////
Functions Section
This needs to stay up here !!!
////////////////////////////////////////////////////////////
*/

define ("WB_UPGRADE_SCRIPT", true);

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
            $tmp = substr($data[0], strlen(TABLE_PREFIX));
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

/*
////////////////////////////////////////////////////////////
Functions Section END
////////////////////////////////////////////////////////////
*/





// include required scripts and setup admin object
define ("WB_SECFORM_TIMEOUT", 7200);
require_once 'config.php';
$admin = new admin('Addons', 'modules', false, false);

// database tables including in WB package
$table_list = array('settings', 'groups', 'addons', 'pages', 'sections', 'search', 'users', 'mod_droplets', 'mod_topics', 'mod_miniform','mod_wbstats_day');
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
'[MODULES]/SecureFormSwitcher/',
'[MODULES]/output_filter/'
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

// hopefully we add the removed files here these files are for 1.1.0
// as a result of adding class Settings and rework of the admin tool system
$filesRemove['2'] = array(

    '[FRAMEWORK]/SecureForm.mtab.php',
    '[FRAMEWORK]/PasswordHash.php',
    '[FRAMEWORK]/classes/PasswordHash.php',
    '[MODULES]/SecureFormSwitcher/FTAN_SUPPORTED',
    '[MODULES]/SecureFormSwitcher/files/SecureForm.mtab.php',
    '[MODULES]/SecureFormSwitcher/htt/help.png',
    '[MODULES]/SecureFormSwitcher/htt/switchform.htt',
    '[MODULES]/SecureFormSwitcher/language_load.php',
    '[MODULES]/captcha_control/FTAN_SUPPORTED',
    '[MODULES]/captcha_control/install_struct.sql'
);

// removes old jquery stuff
$filesRemove['3'] = array(

    '[INCLUDE]/jquery/MIT-LICENSE.txt',
    '[INCLUDE]/jquery/GPL-LICENSE.txt',
    '[INCLUDE]/jquery/version.txt',
    '[INCLUDE]/jquery/jquery-pngFix.js',
    '[INCLUDE]/jquery/jquery-min132.js',
    '[INCLUDE]/jquery/jquery-min161.js',
    '[INCLUDE]/jquery/jquery-min164.js',
    '[INCLUDE]/jquery/jquery-min170.js',
    '[INCLUDE]/jquery/jquery-ui.css',
    '[INCLUDE]/jquery/images/ui-anim_basic_16x16.gif',
    '[INCLUDE]/jquery/images/ui-bg_flat_0_aaaaaa_40x100.png',
    '[INCLUDE]/jquery/images/ui-bg_flat_75_ffffff_40x100.png',
    '[INCLUDE]/jquery/images/ui-bg_glass_55_fbf9ee_1x400.png',
    '[INCLUDE]/jquery/images/ui-bg_glass_65_ffffff_1x400.png',
    '[INCLUDE]/jquery/images/ui-bg_glass_75_dadada_1x400.png',
    '[INCLUDE]/jquery/images/ui-bg_glass_75_e6e6e6_1x400.png',
    '[INCLUDE]/jquery/images/ui-bg_glass_95_fef1ec_1x400.png',
    '[INCLUDE]/jquery/images/ui-bg_highlight-soft_75_cccccc_1x100.png',
    '[INCLUDE]/jquery/images/ui-icons_222222_256x240.png',
    '[INCLUDE]/jquery/images/ui-icons_2e83ff_256x240.png',
    '[INCLUDE]/jquery/images/ui-icons_454545_256x240.png',
    '[INCLUDE]/jquery/images/ui-icons_888888_256x240.png',
    '[INCLUDE]/jquery/images/ui-icons_cd0a0a_256x240.png'
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
$newVersion = 'WBCE v' . NEW_WBCE_VERSION . ' (' . NEW_WBCE_TAG . ')';

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
<h2>Backup your files !!</h2>
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

echo '<h2>Step ' . ($stepID++) . ' : Updating database entries</h2>';


/**********************************************************
 *  - Adding field default_theme to settings table
 */
echo "<br />Adding default_theme to settings table<br />";
Settings::Set('default_theme', $DEFAULT_THEME);


/**********************************************************
 *  - Checking Core modules for installation status and install if necessary
 */

// Droplets
$drops = (!in_array("mod_droplets", $all_tables)) ? "<br />Install droplets<br />" : "<br />Upgrade droplets<br />";
echo $drops;

$file_name = (!in_array("mod_droplets", $all_tables) ? "install.php" : "upgrade.php");
require_once WB_PATH . "/modules/droplets/" . $file_name;

// check again all tables, to get a new array
if (sizeof($all_tables) < sizeof($table_list)) {$all_tables = check_wb_tables();}


// Topics
$drops = (!in_array("mod_topics", $all_tables)) ? "<br />Install Topics<br />" : "<br />Upgrade Topics<br />";
echo $drops;

$file_name = (!in_array("mod_topics", $all_tables) ? "install.php" : "upgrade.php");
require_once WB_PATH . "/modules/topics/" . $file_name;


// OpF Dashboard
if (!in_array("mod_outputfilter_dashboard", $all_tables)) {
   echo "<br />Install OpF Dashboard<br />";
   require_once WB_PATH . "/modules/outputfilter_dashboard/install.php";
} else {
   echo "<br />Upgrade  OpF Dashboard<br />";
   require_once WB_PATH . "/modules/outputfilter_dashboard/upgrade.php";
}

// uninstall classical output filter module
$file_name = WB_PATH . "/modules/output_filter/uninstall.php";
if (file_exists($file_name)) {
    include_once ($file_name);
}

// check again all tables, to get a new array
if (sizeof($all_tables) < sizeof($table_list)) {$all_tables = check_wb_tables();}


// Miniform
$drops = (!in_array("mod_miniform", $all_tables)) ? "<br />Install Miniform<br />" : "<br />Upgrade Miniform<br />";
echo $drops;

$file_name = (!in_array("mod_miniform", $all_tables) ? "install.php" : "upgrade.php");
require_once WB_PATH . "/modules/miniform/" . $file_name;

// check again all tables, to get a new array
if (sizeof($all_tables) < sizeof($table_list)) {$all_tables = check_wb_tables();}


// Wb Stats //
$drops = (!in_array("mod_wbstats_day", $all_tables)) ? "<br />Install WB Stats<br />" : "<br />Upgrade WB Stats<br />";
echo $drops;

$file_name = (!in_array("mod_wbstats_day", $all_tables) ? "install.php" : "upgrade.php");
require_once WB_PATH . "/modules/wbstats/" . $file_name;

// check again all tables, to get a new array
if (sizeof($all_tables) < sizeof($table_list)) {$all_tables = check_wb_tables();}


// Captcha Controll //
echo "<br />Upgrade Captcha Controll<br />";
$ccontroll= WB_PATH . "/modules/captcha_control/upgrade.php" ;
if (is_file($ccontroll)) require_once $ccontroll;


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
 *  Adding or removing Settings
 */

echo "<br />Adding sec_anchor to settings table<br />";
Settings::Set('sec_anchor','wb_', false);

echo "<br />Adding redirect timer to settings table<br />";
Settings::Set('redirect_timer','500', false);

echo "<br />Updating rename_files_on_upload to settings table<br />";
Settings::Set('rename_files_on_upload','ph.*?,cgi,pl,pm,exe,com,bat,pif,cmd,src,asp,aspx,js,lnk', false);

echo "<br />Adding mediasettings to settings table<br />";
Settings::Set('mediasettings','', false);

echo "<br />Adding Secureform Settings if not exits.<br />";
// Settings::Set ("wb_maintainance_mode", false, false);
Settings::Set ("wb_secform_secret", "5609bnefg93jmgi99igjefg", false);
Settings::Set ("wb_secform_secrettime", '86400', false);
Settings::Set ("wb_secform_timeout", '7200', false);
Settings::Set ("wb_secform_tokenname", 'formtoken', false);
Settings::Set ("wb_secform_usefp", false, false);
Settings::Set('fingerprint_with_ip_octets', '0', false);

echo "<br />Removing Secureform selector, no longer needed.<br />";
Settings::Del('secure_form_module'); // No longer needed as Singletab is removed


/**********************************************************
 *  Adding DB Fields
 */


echo "<br />Adding field group_id to users table as this may not be there on very old installations <br />";
db_add_field('group_id', 'users', "INT NOT NULL DEFAULT '0' AFTER `user_id`");


echo "<br />Adding field redirect_type to mod_menu_link table<br />";
db_add_field('redirect_type', 'mod_menu_link', "INT NOT NULL DEFAULT '302' AFTER `target_page_id`");


echo "<br />Adding field namesection to sections table<br />";
db_add_field('namesection', 'sections', "VARCHAR( 255 ) NULL");

echo "<br />Adding field icon to pages table<br />";
db_add_field('icon', 'pages', "varchar(255) collate utf8_unicode_ci NOT NULL DEFAULT ''");

echo "<br />Adding field image to pages table<br />";
db_add_field('image', 'pages', "varchar(255) collate utf8_unicode_ci NOT NULL DEFAULT ''");

echo "<br />Adding field thumb to pages table<br />";
db_add_field('thumb', 'pages', "varchar(255) collate utf8_unicode_ci NOT NULL DEFAULT ''");

echo "<br />Adding field image2 to pages table<br />";
db_add_field('image2', 'pages', "varchar(255) collate utf8_unicode_ci NOT NULL DEFAULT ''");

echo "<br />Adding field image3 to pages table<br />";
db_add_field('image3', 'pages', "varchar(255) collate utf8_unicode_ci NOT NULL DEFAULT ''");

echo "<br />Adding field image4 to pages table<br />";
db_add_field('image4', 'pages', "varchar(255) collate utf8_unicode_ci NOT NULL DEFAULT ''");

echo "<br />Adding field publish to pages table<br />";
db_add_field('publish', 'pages', "int(11) NOT NULL DEFAULT '0'");

echo "<br />Adding unpublish icon to pages table<br />";
db_add_field('unpublish', 'pages', "int(11) NOT NULL DEFAULT '0'");

echo "<br />Adding direct_link icon to pages table<br />";
db_add_field('dlink', 'pages', "text collate utf8_unicode_ci NOT NULL DEFAULT '' AFTER link");

/**********************************************************
 *  - making sure group_id is set correct there was a big bug in original WB
 *  WBCE 1.0.0
 */

$table = TABLE_PREFIX."users";

// set group_id to first group of groups_id
$sql = "UPDATE $table SET `group_id` = CAST(groups_id AS SIGNED)";
$query = $database->query($sql);
echo ($database->is_error() ? __LINE__ .': '.$database->get_error().'<br />' : '');

// if admin, set group_id to 1
$sql = "UPDATE $table SET `group_id` = 1 WHERE FIND_IN_SET('1', groups_id) > '0'";
echo ($database->is_error() ? __LINE__ .': '.$database->get_error().'<br />' : '');
$query = $database->query($sql);


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
    echo '<h2>Step ' . ($stepID++) . ': Remove deprecated and old files</h2>';
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
 * - check for deprecated / never needed directories
 */

if (sizeof($dirRemove)) {
    echo '<h2>Step  ' . ($stepID++) . ': Remove deprecated and old folders</h2>';
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
            echo '<h2>Step ' . ($stepID++) . ' : Upgrade module \'' . $sModul . '\' to version ' . $newModulVersion . '</h2>';
            require_once WB_PATH . '/modules/' . $sModul . '/upgrade.php';
        }
    }
}


/**********************************************************
 *  - Reload all addons
 */

echo '<h2>Step ' . ($stepID++) . ' : Reload all addons database entry (no upgrade)</h2>';
////delete modules
$database->query("DELETE FROM ".TABLE_PREFIX."addons WHERE type = 'module'");
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
$database->query("DELETE FROM ".TABLE_PREFIX."addons WHERE type = 'template'");
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
$database->query("DELETE FROM ".TABLE_PREFIX."addons WHERE type = 'language'");
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
 *  - Set Version to new Version
 */

echo '<br />Update database version number to ' . NEW_WBCE_VERSION . ' (Tag: ' . NEW_WBCE_TAG . ')';
Settings::Set('wbce_version',NEW_WBCE_VERSION);
Settings::Set('wbce_tag',NEW_WBCE_TAG);
Settings::Set('wb_version',VERSION);    // Legacy: WB-classic
Settings::Set('wb_revision',REVISION);  // Legacy: WB-classic
Settings::Set('wb_sp',SP);              // Legacy: WB-classic


/**********************************************************
 *  - Set some new standard conform settings
 */
if (!defined('WB_DEFAULT_TITLE')) Settings::Set ("wb_default_title", WEBSITE_TITLE);
if (!defined('WB_DEFAULT_DESCRIPTION')) Settings::Set ("wb_default_description", WEBSITE_DESCRIPTION);
if (!defined('WB_DEFAULT_KEYWORDS')) Settings::Set ("wb_default_keywords", WEBSITE_KEYWORDS);
if (!defined('WB_SITE_HEADER')) Settings::Set ("wb_site_header", WEBSITE_HEADER);
if (!defined('WB_SITE_FOOTER')) Settings::Set ("wb_site_footer", WEBSITE_FOOTER);
if (!defined('WB_PAGE_LEVEL_LIMIT')) Settings::Set ("wb_page_level_limit", PAGE_LEVEL_LIMIT);
if (!defined('WB_PAGE_TRASH')) Settings::Set ("wb_page_trash", PAGE_TRASH);
if (!defined('WB_PAGE_LANGUAGES')) Settings::Set ("wb_page_languages", PAGE_LANGUAGES);
if (!defined('WB_MULTIPLE_MENUS')) Settings::Set ("wb_multiple_menus", MULTIPLE_MENUS);
if (!defined('WB_HOME_FOLDERS')) Settings::Set ("wb_home_folders", HOME_FOLDERS);
if (!defined('WB_MANAGE_SECTIONS')) Settings::Set ("wb_manage_sections", MANAGE_SECTIONS);
if (!defined('WB_SECTION_BLOCKS')) Settings::Set ("wb_section_blocks", SECTION_BLOCKS);
if (!defined('WB_INTRO_PAGE')) Settings::Set ("wb_intro_page", INTRO_PAGE);
if (!defined('WB_HOMEPAGE_REDIRECTION')) Settings::Set ("wb_homepage_redirection", HOMEPAGE_REDIRECTION);
if (!defined('WB_SMART_LOGIN')) Settings::Set ("wb_smart_login", SMART_LOGIN);
if (!defined('WB_FRONTEND_LOGIN')) Settings::Set ("wb_frontend_login", FRONTEND_LOGIN);
if (!defined('WB_REDIRECT_TIMER')) Settings::Set ("wb_redirect_timer", REDIRECT_TIMER);
if (!defined('WB_FRONTEND_SIGNUP')) Settings::Set ("wb_frontend_signup", FRONTEND_SIGNUP);
if (!defined('WB_ER_LEVEL')) Settings::Set ("wb_er_level", ER_LEVEL);
if (!defined('WB_WYSIWYG_STYLE')) Settings::Set ("wb_wysiwyg_style", WYSIWYG_STYLE);
if (!defined('WB_WYSIWYG_EDITOR')) Settings::Set ("wb_wysiwyg_editor", WYSIWYG_EDITOR);
//if (!defined('') Settings::Set ("", );

// NEw admin Permission "preferences" added
$database->query("
INSERT INTO `".TABLE_PREFIX."groups`
(`group_id`, `name`, `system_permissions`, `module_permissions`, `template_permissions`)
VALUES
(1, 'Administrators', 'pages,pages_view,pages_add,pages_add_l0,pages_settings,pages_modify,pages_intro,pages_delete,media,media_view,media_upload,media_rename,media_delete,media_create,addons,modules,modules_view,modules_install,modules_uninstall,templates,templates_view,templates_install,templates_uninstall,languages,languages_view,languages_install,languages_uninstall,settings,settings_basic,settings_advanced,access,users,users_view,users_add,users_modify,users_delete,groups,groups_view,groups_add,groups_modify,groups_delete,admintools,preferences,preferences_settings', '', '');


");



// Add new Sessions Table

$database->query("

CREATE TABLE IF NOT EXISTS `{TABLE_PREFIX}sessions` (
  `id` char(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Session Id',
  `data` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Session Data',
  `last_accessed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Last timestamp',
  `user` int(11) NOT NULL COMMENT 'User Id',
   PRIMARY KEY (`id`),
   INDEX (`last_accessed`),
   INDEX (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='WBCE Session Table';

");


/**********************************************************
 *  - End of upgrade script only some output stuff down here
 */

if (!defined('DEFAULT_THEME')) {define('DEFAULT_THEME', $DEFAULT_THEME);}
if (!defined('THEME_PATH')) {define('THEME_PATH', WB_PATH . '/templates/' . DEFAULT_THEME);}

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

