<?php
/**
 *
 * @category        module
 * @package         droplet
 * @author          Ruud Eisinga (Ruud) John (PCWacht)
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: tool.php 1543 2011-12-14 00:13:54Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/droplets/tool.php $
 * @lastmodified    $Date: 2011-12-14 01:13:54 +0100 (Mi, 14. Dez 2011) $
 *
 */
/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {

	require_once(dirname(dirname(dirname(__FILE__))).'/framework/globalExceptionHandler.php');
	throw new IllegalFileException();
}
/* -------------------------------------------------------- */

$msg = array();
// Load Language file
if(LANGUAGE_LOADED) {
	if(!file_exists(WB_PATH.'/modules/droplets/languages/'.LANGUAGE.'.php')) {
		require_once(WB_PATH.'/modules/droplets/languages/EN.php');
	} else {
		require_once(WB_PATH.'/modules/droplets/languages/'.LANGUAGE.'.php');
	}
}

// check if backend.css file needs to be included into the <body></body>
if(!method_exists($admin, 'register_backend_modfiles') && file_exists(WB_PATH .'/modules/droplets/backend.css')) {
	echo '<style type="text/css">';
	include(WB_PATH .'/modules/droplets/backend.css');
	echo "\n</style>\n";
}

// Get userid for showing admin only droplets or not
$loggedin_user = ($admin->ami_group_member('1') ? 1 : $admin->user_id());
$loggedin_group = $admin->get_groups_id();
$admin_user = ( ($admin->get_home_folder() == '') && ($admin->ami_group_member('1') ) || ($loggedin_user == '1'));

// And... action
$admintool_url = ADMIN_URL .'/admintools/index.php';

//removes empty entries from the table so they will not be displayed
$sql = 'DELETE FROM `'.TABLE_PREFIX.'mod_droplets` ';
$sql .= 'WHERE name = \'\' ';
if( !$database->query($sql) ) {
	$msg[] = $database->get_error();
}
// if import failed after installation, should be only 1 time
$sql = 'SELECT COUNT(`id`) FROM `'.TABLE_PREFIX.'mod_droplets` ';
if( !$database->get_one($sql) ) {
	include('install.php');
}
?><br />
<table summary="" cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td valign="bottom" width="50%">
		<button class="add" type="button" name="add_droplet" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/droplets/add_droplet.php';"><?php echo $TEXT['ADD'].' '.$DR_TEXT['DROPLETS']; ?></button>
	</td>
	<!-- commentet out the droplets logo for a more similar backend design with other admin tools
	<td align="center"><img src="<?php /*echo WB_URL;*/ ?>/modules/droplets/img/droplets_logo.png" border="1" alt=""/></td>
	-->
	<td valign="top" width="50%" align="right">
		<a href="#" onclick="javascript: window.open('<?php echo WB_URL; ?>/modules/droplets/readme/<?php echo $DR_TEXT['README']; ?>','helpwindow','width=700,height=550,directories=no,location=no,menubar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes');"><?php echo $DR_TEXT['HELP']; ?></a>
		<br /><br />
		<a href="#" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/droplets/backup_droplets.php?id=<?php echo $admin->getIDKEY(999); ?>'"><?php echo $DR_TEXT['BACKUP']; ?></a>
	</td>
</tr>
</table>
<br />

<h2><?php echo $TEXT['MODIFY'].'/'.$TEXT['DELETE'].' '.$DR_TEXT['DROPLETS']; ?></h2>
<?php

$sql = 'SELECT * FROM `'.TABLE_PREFIX.'mod_droplets` ';
if (!$admin_user) {
	$sql .= 'WHERE `admin_view` <> 1 ';
}
$sql .= 'ORDER BY `modified_when` DESC';
$query_droplets = $database->query($sql);
$num_droplets = $query_droplets->numRows();
if($num_droplets > 0) {
	?>
	<table summary="" class="row_a" border="0" cellspacing="0" cellpadding="3" width="100%">
	<thead>
		<tr>
			<td width="3%"></td>
			<td width="21%"><?php echo $TEXT['NAME']; ?></td>
			<td width="68%"><?php echo $TEXT['DESCRIPTION']; ?></td>
			<td width="4%"><?php echo $TEXT['ACTIVE']; ?></td>
			<td width="3%"></td>
		</tr>
	</thead>
	<?php
	$row = 'a';
	while($droplet = $query_droplets->fetchRow()) {
		$get_modified_user = $database->query("SELECT display_name,username, user_id FROM ".TABLE_PREFIX."users WHERE user_id = '".$droplet['modified_by']."' LIMIT 1");
		if($get_modified_user->numRows() > 0) {
			$fetch_modified_user = $get_modified_user->fetchRow();
			$modified_user = $fetch_modified_user['username'];
			$modified_userid = $fetch_modified_user['user_id'];
		} else {
			$modified_user = $TEXT['UNKNOWN'];
			$modified_userid = 0;
		}
        $iDropletIdKey = $admin->getIDKEY($droplet['id']);
		$comments = str_replace(array("\r\n", "\n", "\r"), '<br />', $droplet['comments']);
		if (!strpos($comments,"[[")) $comments = "Use: [[".$droplet['name']."]]<br />".$comments;
		$comments = str_replace(array("[[", "]]"), array('<b>[[',']]</b>'), $comments);
		$valid_code = check_syntax($droplet['code']);
		if (!$valid_code === true) $comments = '<font color=\'red\'><strong>'.$DR_TEXT['INVALIDCODE'].'</strong></font><br /><br />'.$comments;
		$unique_droplet = check_unique ($droplet['name']);
		if ($unique_droplet === false ) {$comments = '<font color=\'red\'><strong>'.$DR_TEXT['NOTUNIQUE'].'</strong></font><br /><br />'.$comments;}
		$comments = '<span>'.$comments.'</span>';
		?>

		<tr class="row_<?php echo $row; ?>" >
			<td >
				<a href="<?php echo WB_URL; ?>/modules/droplets/modify_droplet.php?droplet_id=<?php echo $iDropletIdKey; ?>" title="<?php echo $TEXT['MODIFY']; ?>">
					<img src="<?php echo THEME_URL; ?>/images/modify_16.png" border="0" alt="Modify" />
				</a>
			</td>
			<td >
				<a href="<?php echo WB_URL; ?>/modules/droplets/modify_droplet.php?droplet_id=<?php echo $iDropletIdKey; ?>" class="tooltip">
							<?php if ($valid_code && $unique_droplet) { ?><img src="<?php echo WB_URL; ?>/modules/droplets/img/droplet.png" border="0" alt=""/>
							<?php } else {  ?><img src="<?php echo WB_URL; ?>/modules/droplets/img/invalid.gif" border="0" title="" alt=""/><?php }  ?>
					<?php echo $droplet['name']; ?><?php echo $comments; ?>
				</a>
			</td>
			<td >
				<small><?php echo substr($droplet['description'],0,90); ?></small>
			</td>
			<td >
				<b><?php if($droplet['active'] == 1){ echo '<span style="color: green;">'. $TEXT['YES']. '</span>'; } else { echo '<span style="color: red;">'.$TEXT['NO'].'</span>';  } ?></b>
			</td>
			<td >
				<a href="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']; ?>', '<?php echo WB_URL; ?>/modules/droplets/delete_droplet.php?droplet_id=<?php echo $iDropletIdKey; ?>');" title="<?php echo $TEXT['DELETE']; ?>">
					<img src="<?php echo THEME_URL; ?>/images/delete_16.png" border="0" alt="X" />
				</a>
			</td>
		</tr>
		<?php
		// Alternate row color
		if($row == 'a') {
			$row = 'b';
		} else {
			$row = 'a';
		}
	}
	?>
	</table>
	<?php
}

function check_syntax($code) {
    return @eval('return true;' . $code);
}

function check_unique($name) {
	global $database;
	$retVal = 0;
	$sql = 'SELECT COUNT(*) FROM `'.TABLE_PREFIX.'mod_droplets` ';
	$sql .= 'WHERE `name` = \''.$name.'\'';
	$retVal = intval($database->get_one($sql));
	return ($retVal == 1);
}
