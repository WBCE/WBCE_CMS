<?php
require('../../../config.php');
if (defined('WB_PATH') == false) { exit("Cannot access this file directly"); }
require_once WB_PATH . '/framework/class.frontend.php';
$wb = new frontend();
$wb->get_website_settings();

$pA = explode(DIRECTORY_SEPARATOR,dirname(__FILE__));
array_pop ($pA);
$t_dir = array_pop ($pA );
$template_dir = WB_URL.'/templates/'.$t_dir;


if(FRONTEND_LOGIN AND !$wb->is_authenticated() ) {	
	?>	
	<form class="loginform" name="login" action="<?php echo LOGIN_URL; ?>" method="post">
	<input type="hidden" name="redirect" id="redirect_url" value="" />
	<table>			
		<tr><td><label for="inputusername"><?php echo $TEXT['USERNAME']; ?></label>:</td><td><input id="inputusername" type="text" name="username" class="inputfield" /></td></tr>
		<tr><td><label for="inputpassword"><?php echo $TEXT['PASSWORD']; ?></label>:</td><td><input id="inputpassword" type="password" name="password" class="inputfield"/></td></tr> 
		<tr><td>&nbsp;</td><td style="text-align:right;"><input type="image"  class="loginsubmit" src="<?php echo $template_dir; ?>/img/login.png" alt="Log In" /></td></tr> 
		
		<!-- frontend signup -->
		<?php	if (is_numeric(FRONTEND_SIGNUP)) { echo '<tr><td colspan="2"><a href="'.SIGNUP_URL.'">'.$TEXT['SIGNUP'].'</a></td></tr> '; } ?>		
	</table></form>
<?php 	
} else  {
?>
	<form class="loginform" name="logout" action="<?php echo LOGOUT_URL; ?>" method="post">
	<?php echo $TEXT['WELCOME_BACK']; ?>, <?php echo $wb->get_display_name(); ?> 
	 <input type="submit" name="submit" value="<?php echo $MENU['LOGOUT']; ?>" class="inputfield"  />
	<p><a href="<?php echo PREFERENCES_URL; ?>"><?php echo $MENU['PREFERENCES']; ?></a></p>	
	</form>		
<?php } 	?>
