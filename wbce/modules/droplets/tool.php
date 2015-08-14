<?php
/**
 *
 * @category        module
 * @package         droplet
 * @author          Ruud Eisinga (Ruud) John (PCWacht)
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			      http://www.websitebaker2.org/
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

global $module_version;

require_once( dirname(__FILE__).'/functions.inc.php' );
require_once( dirname(__FILE__).'/info.php' );

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
$loggedin_user = ($admin->ami_group_member('1') ? 1 : $admin->get_user_id());
$loggedin_group = $admin->get_groups_id();
if ( version_compare(WB_VERSION, '2.8.2', '>=') && WB_VERSION<> "2.8.x" ) {
      $admin_user     = ( ($admin->get_home_folder() == '') && ($admin->ami_group_member('1') ) || ($loggedin_user == '1'));
} else {
      $admin_user     = $loggedin_user ;
}
	
// And... action
$admintool_url = ADMIN_URL .'/admintools/index.php';

//removes empty entries from the table so they will not be displayed
$database->query("DELETE FROM ".TABLE_PREFIX."mod_droplets WHERE name=''");

// ----- added by WebBird, 2010-11-04 -----
// do we have droplet backups?
$backup_files = wb_find_backups( WB_PATH.'/modules/droplets/export/' );
$backup_mgmt  = NULL;
if ( count( $backup_files ) > 0 ) {
    $backup_mgmt = '<button class="button" type="button" name="backup_mgmt" onclick="javascript: window.location = \''
                 . WB_URL.'/modules/droplets/manage_backups.php\';">'
                 . $DR_TEXT['MANAGE_BACKUPS'] . '</button>';
}
// ----- /added by WebBird, 2010-11-04 -----

?>

<script type="text/javascript" src="<?php echo WB_URL; ?>/modules/droplets/js/jquery.tablesorter.js"></script>
<br />
<table summary="" cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td valign="bottom">
		<button class="button" type="button" name="add_droplet" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/droplets/add_droplet.php';"><?php echo $TEXT['ADD'].' '.$DR_TEXT['DROPLETS']; ?></button>
  	<button class="button" type="button" name="upload_droplet" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/admintools/tool.php?tool=droplets&amp;upload=1';"><?php echo $DR_TEXT['UPLOAD']; ?></button>
<?php
if ( version_compare(WB_VERSION, '2.8.2', '>=') && WB_VERSION<> "2.8.x" ) {
?>
  	  	<button class="button" type="button" name="backup" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/droplets/backup_droplets.php?id=<?php echo $admin->getIDKEY(999); ?>'"><?php echo $DR_TEXT['BACKUP']; ?></button>
<?php
} else {
?>
  	  	<button class="button" type="button" name="backup" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/droplets/backup_droplets.php?id=999'"><?php echo $DR_TEXT['BACKUP']; ?></button>
<?php
}
?>
  	<?php echo $backup_mgmt; ?>
	</td>
	<td valign="top">
 	<a href="#" onclick="javascript: window.open('<?php echo WB_URL; ?>/modules/droplets/readme/<?php echo $DR_TEXT['README']; ?>','helpwindow','width=700,height=550,directories=no,location=no,menubar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes');">
		<img src="<?php echo WB_URL; ?>/modules/droplets/img/help.png" alt="<?php echo $DR_TEXT['HELP']; ?>" />
    </a>
	</td>
</tr>
</table>
<br />

<?php
    $new   = array();
    // ----- added by WebBird, 2010-11-16 -----
    if ( isset($_GET['copy']) ) {
        $id = $_GET['copy'];
        if ( version_compare(WB_VERSION, '2.8.2', '>=')  && WB_VERSION<>"2.8.x" ) {
            $id = $admin->checkIDKEY( 'copy', NULL, 'GET' );
        }
        if ( is_numeric($id) ) {
            wb_handle_copy($id);
        }
    }
    // ----- added by WebBird, 2010-11-02 -----
    if ( isset($_GET['upload']) && $_GET['upload'] ) {
        $new = wb_handle_upload();
    }
    if ( isset($_GET['recover']) && file_exists( WB_PATH.'/modules/droplets/export/'.$_GET['recover'] ) ) {
        $result = wb_unpack_and_import( WB_PATH.'/modules/droplets/export/'.$_GET['recover'], WB_PATH.'/temp/unzip/' );
        // show errors
        if ( isset( $result['errors'] ) && is_array( $result['errors'] ) && count( $result['errors'] ) > 0 ) {
            echo '<div style="border: 1px solid #f00; padding: 5px; color: #f00; font-weight: bold;">',
                 $DR_TEXT['IMPORT_ERRORS'],
                 "<br />\n";
            foreach ( $result['errors'] as $droplet => $error ) {
                echo 'Droplet: ', $droplet, '<br />',
                     '<span style="padding-left: 15px">',
                     $error,
                     '</span>';
            }
            echo "</div>\n";
        } else {
            $new = isset( $result['imported'] ) ? $result['imported'] : array();
            echo '<div class="drok">',
                 $result['count'], " ", $DR_TEXT['IMPORTED'],
                 '</div>';
        }
    }
    if ( isset($_REQUEST['export']) && $_REQUEST['export'] ) {
        wb_handle_export();
    }
    if ( isset($_REQUEST['delete']) && $_REQUEST['delete'] ) {
        // Changed by PCWacht
        wb_handle_delete();
    }
    // ----- /added by WebBird, 2010-11-02 -----
?>

<h2><?php echo $TEXT['MODIFY'].'/'.$TEXT['DELETE'].' '.$DR_TEXT['DROPLETS']; ?></h2>
<?php
// if ($loggedin_user == '1') {
if ($admin_user) {
	$query_droplets = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_droplets ORDER BY modified_when DESC");
} else { 
	$query_droplets = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_droplets WHERE admin_view <> '1' ORDER BY modified_when DESC");
}
$num_droplets = $query_droplets->numRows();
if($num_droplets > 0) {
// ----- added by WebBird, 2010-11-02 -----
	?>
	<form method="post" action="<?php echo ADMIN_URL; ?>/admintools/tool.php?tool=droplets">
<?php
    if ( method_exists( $admin, 'getFTAN' ) ) { echo $admin->getFTAN(); }
// ----- /added by WebBird, 2010-11-02 -----
?>
	<table class="droplets row_a tablesorter" border="0" cellspacing="0" cellpadding="3" width="100%" id="myTable">
	<thead>
		<tr>
			<th width="15%"></th>
			<th width="20%"><?php echo $TEXT['NAME']; ?></th>
			<th width="55%"><?php echo $TEXT['DESCRIPTION']; ?></th>
			<th width="7%"><?php echo $TEXT['ACTIVE']; ?></th>
			<th width="3%"></th>
		</tr>
	</thead>
	<tbody>
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
		$comments = str_replace(array("\r\n", "\n", "\r"), '<br />', $droplet['comments']);
		if (!strpos($comments,"[[")) $comments = "Use: [[".$droplet['name']."]]<br />".$comments;
		$comments = str_replace(array("[[", "]]"), array('<b>[[',']]</b>'), $comments);
		$valid_code = check_syntax($droplet['code']);
		if (!$valid_code === true) $comments = '<font color=\'red\'><strong>'.$DR_TEXT['INVALIDCODE'].'</strong></font><br /><br />'.$comments;
		$unique_droplet = check_unique ($droplet['name']);
		if ($unique_droplet === false) $comments = '<font color=\'red\'><strong>'.$DR_TEXT['NOTUNIQUE'].'</strong></font><br /><br />'.$comments;
		$comments = '<span>'.$comments.'</span>';
		
		$id = $droplet['id'];
        if ( version_compare(WB_VERSION, '2.8.2', '>=') && WB_VERSION<>"2.8.x") {
              $id = $admin->getIDKEY($id);
		}
		?>
		
		<tr class="row_<?php echo $row;?>" >
			<td<?php if(isset($new[$droplet['name']])) { echo ' class="newdroplet"'; }?>>
			<?php // Changed by PCWacht - send secured id instead of plain id ?>
        <input type="checkbox" name="markeddroplet[]" id="markeddroplet[]" value="<?php echo $id; ?>" />
				<a href="<?php echo WB_URL; ?>/modules/droplets/modify_droplet.php?droplet_id=<?php echo $id; ?>" title="<?php echo $TEXT['MODIFY']; ?>">
					<img src="<?php echo THEME_URL; ?>/images/modify_16.png" border="0" alt="Modify" /> 
				</a>
				<a href="<?php echo ADMIN_URL; ?>/admintools/tool.php?tool=droplets&amp;copy=<?php echo $id; ?>" class="tooltip">
          <img src="<?php echo WB_URL; ?>/modules/droplets/img/copy.png" alt="" /><span><?php echo $DR_TEXT['DUPLICATE']; ?></span>
        </a>
        <a href="<?php echo WB_URL; ?>/modules/droplets/modify_droplet.php?droplet_id=<?php echo $id; ?>" class="tooltip">
          <img src="<?php echo WB_URL; ?>/modules/droplets/img/info.png" alt="" /><?php echo $comments; ?>
        </a>
<?php if ($valid_code && $unique_droplet) { ?><img src="<?php echo WB_URL; ?>/modules/droplets/img/droplet.png" border="0" alt=""/>
<?php } else {  ?><img src="<?php echo WB_URL; ?>/modules/droplets/img/invalid.gif" border="0" title="" alt=""/><?php }  ?>
			</td>
			<td<?php if(isset($new[$droplet['name']])) { echo ' class="newdroplet"'; }?>>
        <?php echo $droplet['name']; ?>
			</td>
			<td<?php if(isset($new[$droplet['name']])) { echo ' class="newdroplet"'; }?>>
				<small><?php echo substr($droplet['description'],0,90); ?></small>
			</td>
			<td<?php if(isset($new[$droplet['name']])) { echo ' class="newdroplet"'; }?>>
				<b><?php if($droplet['active'] == 1){ echo '<span style="color: green;">'. $TEXT['YES']. '</span>'; } else { echo '<span style="color: red;">'.$TEXT['NO'].'</span>';  } ?></b>
			</td>
			<td<?php if(isset($new[$droplet['name']])) { echo ' class="newdroplet"'; }?>>
				<a href="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']; ?>', '<?php echo WB_URL; ?>/modules/droplets/delete_droplet.php?droplet_id=<?php echo $id; ?>');" title="<?php echo $TEXT['DELETE']; ?>">
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
	</tbody>
	<tr class="row_<?php echo $row;?>">
    <td colspan="5" style="text-align: right; border-top: 1px solid #000; border-bottom: 3px double #000;"><?php echo $num_droplets; ?></td>
  </tr>
	</table>
<?php
// ----- added by WebBird, 2010-11-02 -----
// include info.php to show module version
	include( realpath( dirname(__FILE__).'/info.php' ) );
?>
	<?php echo $DR_TEXT['MARKED']; ?>:
  <input type="submit" class="button" name="export" id="export" value="<?php echo $DR_TEXT['EXPORT']; ?>" />
  <input type="submit" class="button" name="delete" id="delete" value="<?php echo $TEXT['DELETE']; ?>" onclick="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']; ?>', '<?php echo ADMIN_URL; ?>/admintools/tool.php?tool=droplets&amp;delete=1');" />
  </form><br /><br />
  <div style="text-align: center;">
    <strong>Droplets V<?php echo $module_version; ?></strong><br /><br />
    [ <a href="http://www.websitebakers.com/pages/droplets/module-wb2.8.php" target="_blank">Check AMASP for updates</a>
    | <a href="http://www.websitebakers.com/pages/droplets/downloads.php" target="_blank">Download Droplets at AMASP</a>
    ]
  </div>
<?php
// ----- /added by WebBird, 2010-11-02 -----
}

function check_syntax($code) {
    return @eval('return true;' . $code);
}

function check_unique($name) {
	global $database;
	$query_droplets = $database->query("SELECT name FROM ".TABLE_PREFIX."mod_droplets WHERE name = '$name'");
	return ($query_droplets->numRows() == 1);
}

?>
