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
 * @version         $Id: signup_form.php 1599 2012-02-06 15:59:24Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/account/signup_form.php $
 * @lastmodified    $Date: 2012-02-06 16:59:24 +0100 (Mo, 06. Feb 2012) $
 *
 */

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { die("Cannot access this file directly"); }

require_once(WB_PATH.'/include/captcha/captcha.php');

?>
<div style="margin: 1em auto;">
	<button type="button" value="cancel" onClick="javascript: window.location = '<?php print $_SESSION['HTTP_REFERER'] ?>';"><?php print $TEXT['CANCEL'] ?></button>
</div>
<h1>&nbsp;<?php echo $TEXT['SIGNUP']; ?></h1>

<form name="user" action="<?php echo WB_URL.'/account/signup.php'; ?>" method="post">
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
<table summary="" cellpadding="5" cellspacing="0" border="0" width="90%">
<tr>
	<td width="180"><?php echo $TEXT['USERNAME']; ?>:</td>
	<td class="value_input">
		<input type="text" name="username" maxlength="30" style="width:300px;"/>
	</td>
</tr>
<tr>
	<td><?php echo $TEXT['DISPLAY_NAME']; ?> (<?php echo $TEXT['FULL_NAME']; ?>):</td>
	<td class="value_input">
		<input type="text" name="display_name" maxlength="255" style="width:300px;" />
	</td>
</tr>
<tr>
	<td><?php echo $TEXT['EMAIL']; ?>:</td>
	<td class="value_input">
		<input type="text" name="email" maxlength="255" style="width:300px;"/>
	</td>
</tr>
<?php
// Captcha
if(ENABLED_CAPTCHA) {
	?><tr>
		<td class="field_title"><?php echo $TEXT['VERIFICATION']; ?>:</td>
		<td><?php call_captcha(); ?></td>
		</tr>
	<?php
}
?>
<tr>
	<td>&nbsp;</td>
	<td>
		<input type="submit" name="submit" value="<?php echo $TEXT['SIGNUP']; ?>" />
		<input type="reset" name="reset" value="<?php echo $TEXT['RESET']; ?>" />
	</td>
</tr>
</table>

</form>

<br />
&nbsp; 
