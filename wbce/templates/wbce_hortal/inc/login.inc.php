<?php

if(!$wb->is_authenticated()) {
?>
<br style="clear:both;" />
<a id="showlogin" href="javascript:showloginbox();"><img src="<?php echo TEMPLATE_DIR; ?>/img/key.gif" alt="K" /></a>
<div id="loginbox" style="display:none;">
<form class="loginform" name="login" action="<?php echo LOGIN_URL; ?>" method="post">
<table>			
	<tr><td><label for="inputusername"><?php echo $TEXT['USERNAME']; ?></label>:</td><td><input type="text" id="inputusername" name="username" class="inputfield" /></td></tr>
	<tr><td><label for="inputpassword"><?php echo $TEXT['PASSWORD']; ?></label>:</td><td><input type="password" id="inputpassword" name="password" class="inputfield"/></td></tr> 
	<tr><td>&nbsp;</td><td><input type="image"  class="loginsubmit" src="<?php echo TEMPLATE_DIR; ?>/img/next.gif" alt="Log In" /></td></tr> 
	<!--input type="submit" name="submit" value="<?php echo $TEXT['LOGIN']; ?>" class="submit" alt="Login" /-->
	
	<tr><td colspan="2">
	<p>
	<!-- frontend signup -->
	<?php	if (is_numeric(FRONTEND_SIGNUP)) { echo ' | <a href="'.SIGNUP_URL.'">'.$TEXT['SIGNUP'].'</a>'; } ?>
	</p></td></tr> 		
</table></form></div>
<?php 



} else  {
?>
<br style="clear:both;" />
<div id="loginbox">
<form class="loginform" name="logout" action="<?php echo LOGOUT_URL; ?>" method="post">
<?php echo $TEXT['WELCOME_BACK']; ?>, <?php echo $wb->get_display_name(); ?><br />
 <input type="submit" name="submit" value="<?php echo $MENU['LOGOUT']; ?>" class="inputfield"  />
<p><a href="<?php echo PREFERENCES_URL; ?>"><?php echo $MENU['PREFERENCES']; ?></a></p>

</form></div>
		
<?php } 	?>
