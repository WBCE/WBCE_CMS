<?php
/**
 *
 * @category        admintool / preinit / initialize
 * @package         errorlogger
 * @author          Ruud Eisinga - www.dev4me.com
 * @link			https://dev4me.com/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WBCE 1.4+
 * @version         1.0
 * @lastmodified    November 12, 2019
 *
 */

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { die("Cannot access this file directly"); }

// check if user is allowed to use admin-tools (to prevent this file to be called by an unauthorized user e.g. from a code-section)
if(!$admin->get_permission('admintools')) die(header('Location: ../../index.php'));

$link = ADMIN_URL.'/admintools/tool.php?tool=errorlogger';
$del = "javascript:confirm_link('Are you sure you want to delete the errorlog?','$link&delete=1');";



// Use colored view, stored in cookie
$cookie = 'errorlog_colors';
$color_set = false;
if(isset($_GET['color'])) {
	if($_GET['color']=='1') {
		setcookie($cookie, '1', time() + (86400 * 365));
		$_COOKIE[$cookie] = '1';
	} else {
		setcookie($cookie, null, 1);
		unset($_COOKIE[$cookie]);
	}
}
$colorclass = '';
$use_color = $link.'&color=1';
$use_color_text = 'Use Colors';
if(isset($_COOKIE[$cookie])) {
	$colorclass = ' color';
	$use_color = $link.'&color=0';
	$use_color_text = 'No Colors';
}


$res = array("Great news. No errors reported");

$lines = 250;
$warning = '';
$logfile = ini_get('error_log');
if(!$logfile) $logfile = WB_PATH.'/var/logs/php_error.log.php';

if(isset($_GET['delete'])) {
	if(file_exists($logfile)) {
		$now = date("Ymd_Hi");
		rename ( $logfile , dirname($logfile).'/'.$now.'_php_error.log.php' );
		$lname = str_replace(WB_PATH,'',dirname($logfile).'/'.$now.'_php_error.log.php');
		$warning .= '<div class="messagelevel">Message: Logfile renamed to '.$lname.'</div>';
	}
}

if(!isset($_SESSION['lastview'])) $_SESSION['lastview'] = date('c');

if(file_exists($logfile)) {
	$res = file($logfile);
	unset($res[0]); // = strip_tags($res[0]);
	$res = array_slice($res, -$lines);
}
if(count($res) == 0) $res = array("Great news. No errors reported");
$row = "even";
$last = strtotime($_SESSION['lastview']);

$tmpErrorLevel = error_reporting(-1);
$warning .= $tmpErrorLevel != E_ALL ? '<div class="errorlevel">Note: Do not forget to set your error reporting to the maximum level! Currently this is not the case.</div>':'';


?>
<h2 style="display:block;background:#f7f7f7;padding:10px;">Errorlog viewer  <span class="lvlink"><a href="<?php echo $use_color ?>"><?php echo $use_color_text ?></a></span> <span class="lvlink"><a href="<?php echo $del ?>">Delete logfile</a></span> <span class="lvlink"><a href="<?php echo $link ?>">Reload</a></span></h2>
<?php echo $warning ?>
<div class="logviewWrapper<?php echo $colorclass ?>">
<?php foreach($res as $line) {
	$row = $row == "odd" ? "even" : "odd";
	$curline = strtotime(str_replace(array('[',']'),'',substr($line,0,26)));
	$row = $curline > $last ? " newline": $row;
	$type = '';	
	if(strpos($line,"Notice]")!== false) $type = " lognotice";
	if(strpos($line,"Warning]")!== false) $type = " logwarning";
	if(strpos($line,"Error]")!== false) $type = " logerror";
	if(strpos($line,"PHP Parse error")!== false) $type = " logerror";
	if(strpos($line,"Exception]")!== false) $type = " logerror";
	if(strpos($line,"Deprecated]")!== false) $type = " logdeprecated";
	echo '<div class="logline '.$row.$type.'">'.$line.'</div>';
}
?>
</div>
<?php 
$_SESSION['lastview'] = date('c');



