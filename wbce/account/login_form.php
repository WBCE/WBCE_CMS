<?php
/**
 *
 * @category        frontend
 * @package         account
 * @author          WebsiteBaker Project
 * @copyright       Ryan Djurovich
 * @copyright       Website Baker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: login_form.php 1599 2012-02-06 15:59:24Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/account/login_form.php $
 * @lastmodified    $Date: 2012-02-06 16:59:24 +0100 (Mo, 06. Feb 2012) $
 *
 */

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { die("Cannot access this file directly"); }

$username_fieldname = 'username';
$password_fieldname = 'password';

if(defined('SMART_LOGIN') AND SMART_LOGIN == 'enabled') {
	// Generate username field name
	$username_fieldname = 'username_';
	$password_fieldname = 'password_';

	$temp = array_merge(range('a','z'), range(0,9));
	shuffle($temp);
	for($i=0;$i<=7;$i++) {
		$username_fieldname .= $temp[$i];
		$password_fieldname .= $temp[$i];
	}
}

$thisApp->redirect_url = (isset($thisApp->redirect_url) && ($thisApp->redirect_url!='')  ? $thisApp->redirect_url : $_SESSION['HTTP_REFERER'] );
?>

<style type="text/css">

.login-info {
	margin:1em 0;
}

.login-box table {
	width:100%;
}

.login-box table td {
	padding:0.5em;
	width:50%;
}

.login-box input {
	width:100%;
	padding:0.2em;
}

.login-box input[type="text"], .login-box input[type="password"] {
	background-color:#fff;
	border-style:solid; 
	border-width:1px;
}
	
</style>

<h1><?php echo $TEXT['LOGIN']; ?></h1>

<p class="login-info">
<?php 
  if(isset($thisApp->message)) {
    echo $thisApp->message; 
  }
?>
</p>

<form class="login-box" action="<?php echo WB_URL.'/account/login.php'; ?>" method="post" autocomplete="off">
<input type="hidden" name="username_fieldname" value="<?php echo $username_fieldname; ?>" />
<input type="hidden" name="password_fieldname" value="<?php echo $password_fieldname; ?>" autocomplete="off"/>
<input type="hidden" name="redirect" value="<?php echo $thisApp->redirect_url;?>" />




<table cellpadding="5" cellspacing="0" border="0">
<tr>
	<td><?php echo $TEXT['USERNAME']; ?>:</td>
	<td>
		<input type="text" name="<?php echo $username_fieldname; ?>" />
    	<script type="text/javascript">
    	// document.login.<?php echo $username_fieldname; ?>.focus();
    	var ref= document.getElementById("<?php echo $username_fieldname; ?>");
    	if (ref) ref.focus();
    	</script>
	</td>
</tr>
<tr>
	<td><?php echo $TEXT['PASSWORD']; ?>:</td>
	<td>
		<input type="password" name="<?php echo $password_fieldname; ?>" />
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>
		<input type="submit" name="submit" value="<?php echo $TEXT['LOGIN']; ?>"  />
		
	</td>
</tr>
</table>

</form>

<p><a href="<?php echo WB_URL; ?>/account/forgot.php"><?php echo $TEXT['FORGOTTEN_DETAILS']; ?></a></p>
