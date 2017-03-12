<?php
/**
 *
 * @category        frontend
 * @package         account
 * @author          WebsiteBaker Project
 * @copyright       Ryan Djurovich
 * @copyright       WebsiteBaker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: signup_form.php 1599 2012-02-06 15:59:24Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/account/signup_form.php $
 * @lastmodified    $Date: 2012-02-06 16:59:24 +0100 (Mo, 06. Feb 2012) $
 *
 */

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { die("Cannot access this file directly"); }
$sCallingScript = $_SERVER['SCRIPT_NAME'];
$_SESSION['HTTP_REFERER'] =  isset($_SESSION['HTTP_REFERER']) ? $_SESSION['HTTP_REFERER'] : $sCallingScript;
require_once(WB_PATH.'/include/captcha/captcha.php');

?>

<style type="text/css">



table.user-form  {
	width:100%;
	margin:2em 0;
}

td.user-form-desc {
	width:33%;
	vertical-align:top;
	text-align:left;
	padding:0.2em 0.2em 0.2em 0;
}

td.user-form-field {
	width:66%;
	vertical-align:top;
	text-align:left;
	padding:0.2em 0.2em 0.2em 0;
}

td.user-form-field input {
	width:100%;
	padding:0.2em;
}

td.user-form-field input[type="text"] {
	background-color:#fff;
	border-style:solid; 
	border-width:1px;
}



</style>

<h1><?php echo $TEXT['SIGNUP']; ?></h1>

<form name="user" class="user-box" action="<?php echo WB_URL.'/account/signup.php'; ?>" method="post">
	<?php echo $admin->getFTAN(); ?>
	<?php if(ENABLED_ASP) { // add some honeypot-fields
	?>
    <div style="display:none;">
	<input type="hidden" name="submitted_when" value="<?php $t=time(); echo $t; $_SESSION['submitted_when']=$t; ?>" />
	<p class="nixhier">
	email-address:
	<label for="email-address">Leave this field email-address blank:</label>
	<input id="email-address" name="email-address" size="60" value="" /><br />
	username (id):
	<label for="name">Leave this field name blank:</label>
	<input id="name" name="name" size="60" value="" /><br />
	Full Name:
	<label for="full_name">Leave this field full_name blank:</label>
	<input id="full_name" name="full_name" size="60" value="" /><br />
	</p>
	<?php }
	?>
    </div>
	
<table class="user-form">
<tr>
	<td class="user-form-desc"><?php echo $TEXT['USERNAME']; ?>:</td>
	<td class="user-form-field"><input type="text" name="username" maxlength="30" /></td>
</tr>
<tr>
	<td class="user-form-desc"><?php echo $TEXT['DISPLAY_NAME']; ?> (<?php echo $TEXT['FULL_NAME']; ?>):</td>
	<td class="user-form-field"><input type="text" name="display_name" maxlength="255"  /></td>
</tr>
<tr>
	<td class="user-form-desc"><?php echo $TEXT['EMAIL']; ?>:</td>
	<td class="user-form-field"><input type="text" name="email" maxlength="255" /></td>
</tr>
<?php
// Captcha
if(ENABLED_CAPTCHA) {
	?><tr>
		<td class="user-form-desc"><?php echo $TEXT['VERIFICATION']; ?>:</td>
		<td><?php call_captcha(); ?></td>
		</tr>
	<?php
}
?>
<tr>
	<td class="user-form-desc">&nbsp;</td>
	<td class="user-form-field"><input type="submit" name="submit" value="<?php echo $TEXT['SIGNUP']; ?>" /></td>
</tr>
</table>

</form>
