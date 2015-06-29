<?php
/**
 *
 * @category        frontend
 * @package         account
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
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
<div style="margin: 1em auto;">
	<button type="button" value="cancel" onClick="javascript: window.location = '<?php print $_SESSION['HTTP_REFERER'] ?>';"><?php print $TEXT['CANCEL'] ?></button>
</div>
<h1>&nbsp;Login</h1>
&nbsp;<?php echo $thisApp->message; ?>
<br />
<br />

<form class="login-box" action="<?php echo WB_URL.'/account/login.php'; ?>" method="post">
<input type="hidden" name="username_fieldname" value="<?php echo $username_fieldname; ?>" />
<input type="hidden" name="password_fieldname" value="<?php echo $password_fieldname; ?>" />
<input type="hidden" name="redirect" value="<?php echo $thisApp->redirect_url;?>" />

<table cellpadding="5" cellspacing="0" border="0" width="90%">
<tr>
	<td style="width:100px"><?php echo $TEXT['USERNAME']; ?>:</td>
	<td class="value_input">
		<input type="text" name="<?php echo $username_fieldname; ?>" maxlength="30" style="width:220px;"/>
    	<script type="text/javascript">
    	// document.login.<?php echo $username_fieldname; ?>.focus();
    	var ref= document.getElementById("<?php echo $username_fieldname; ?>");
    	if (ref) ref.focus();
    	</script>
	</td>
</tr>
<tr>
	<td style="width:100px"><?php echo $TEXT['PASSWORD']; ?>:</td>
	<td class="value_input">
		<input type="password" name="<?php echo $password_fieldname; ?>" maxlength="30" style="width:220px;"/>
	</td>
</tr>
<?php if($username_fieldname != 'username') { ?>
<tr>
	<td>&nbsp;</td>
	<td>
		<input type="checkbox" name="remember" id="remember" value="true"/>
		<label for="remember"><?php echo $TEXT['REMEMBER_ME']; ?></label>
	</td>
</tr>
<?php } ?>
<tr>
	<td>&nbsp;</td>
	<td>
		<input type="submit" name="submit" value="<?php echo $TEXT['LOGIN']; ?>"  />
		<input type="reset" name="reset" value="<?php echo $TEXT['RESET']; ?>"  />
	</td>
</tr>
</table>

</form>

<br />

<a href="<?php echo WB_URL; ?>/account/forgot.php"><?php echo $TEXT['FORGOTTEN_DETAILS']; ?></a>