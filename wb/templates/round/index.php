<?php
/**
 * $Id: index.php 1606 2012-02-08 22:31:52Z Luisehahne $
 * Website Baker template: round
 * This template is one of four basis templates distributed with Website Baker.
 * Feel free to modify or build up on this template.
 *
 * This file contains the overall template markup and the Website Baker
 * template functions to add the contents from the database.
 *
 * LICENSE: GNU General Public License
 * 
 * @author     Ryan Djurovich, C. Sommer
 * @copyright  GNU General Public License
 * @license    http://www.gnu.org/licenses/gpl.html
 * @version    2.70
 * @platform   Website Baker 2.7
 *
 * Website Baker is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Website Baker is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
*/

// prevent this file from being accessed directly
if (!defined('WB_PATH')) die(header('Location: ../../../index.php'));

// TEMPLATE CODE STARTS BELOW
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php 
	echo defined('DEFAULT_CHARSET') ? DEFAULT_CHARSET : 'utf-8'; ?>" />
	<meta name="description" content="<?php page_description(); ?>" />
	<meta name="keywords" content="<?php page_keywords(); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php
		echo TEMPLATE_DIR; ?>/template.css" media="screen,projection" />
	<link rel="stylesheet" type="text/css" href="<?php
		echo TEMPLATE_DIR; ?>/print.css" media="print" />
	<title><?php page_title('', '[WEBSITE_TITLE]'); ?></title>
	<?php
	// automatically include optional WB module files (frontend.css, frontend.js)
	if (function_exists('register_frontend_modfiles')) {
		register_frontend_modfiles('css');
		// register_frontend_modfiles('jquery');
		register_frontend_modfiles('js');
	} ?>
</head>

<body>

<table summary="" cellpadding="0" cellspacing="0" border="0" align="center" class="main" width="750">
<tr>
	<td colspan="2" class="header" height="80">
		<a href="<?php echo WB_URL; ?>">
			<img src="<?php echo TEMPLATE_DIR; ?>/images/banner.jpg" border="0" width="750" height="80" alt="<?php
			page_title('', '[WEBSITE_TITLE]'); ?>" />
		</a>
	</td>
</tr>
<tr>
	<?php
	// navigation menu
	if(SHOW_MENU) {
	?>	
	<td style="padding: 10px; background-color: #FFF;" valign="top">
		<table summary="" cellpadding="0" cellspacing="0" border="0" width="150" align="center" class="menu">
		<tr>
			<td class="border">
				<img src="<?php echo TEMPLATE_DIR; ?>/images/menu_top.gif" border="0" alt="" />
			</td>
		</tr>
		<tr>
			<td width="170">
				<?php show_menu2(0,SM2_ROOT,SM2_CURR+1,SM2_TRIM,'<li><span class="menu-default">[ac][menu_title]</a></span>','</li>','<ul>','</ul>');?>
			</td>
		</tr>
		<tr>
			<td class="border">
				<img src="<?php echo TEMPLATE_DIR; ?>/images/menu_bottom.gif" border="0" alt="" />
			</td>
		</tr>
		</table>
		
		
		<!-- frontend search -->
		<?php if (SHOW_SEARCH) { ?>
		<form name="search" action="<?php echo WB_URL; ?>/search/index.php" method="get">
			<input type="hidden" name="referrer" value="<?php
				echo defined('REFERRER_ID') ? REFERRER_ID : PAGE_ID; ?>" />
			<table summary="" cellpadding="0" cellspacing="0" border="0" width="150" align="center" style="margin-top: 10px;">
				<tr>
					<td class="border">
						<img src="<?php echo TEMPLATE_DIR; ?>/images/menu_top.gif" border="0" alt="" />
					</td>
				</tr>
				<tr>
					<td class="login">
						<input type="text" name="string" />
					</td>
				</tr>
				<tr>
					<td class="login">
						<input type="submit" name="submit" value="<?php echo $TEXT['SEARCH']; ?>" />
					</td>
				</tr>
				<tr>
					<td class="border">
						<img src="<?php echo TEMPLATE_DIR; ?>/images/menu_bottom.gif" border="0" alt="" />
					</td>
				</tr>
			</table>
		</form>
		<?php } ?>
		
<?php
		if(FRONTEND_LOGIN AND !$wb->is_authenticated() AND VISIBILITY != 'private' ) {
			$redirect_url = ((isset($_SESSION['HTTP_REFERER']) && $_SESSION['HTTP_REFERER'] != '') ? $_SESSION['HTTP_REFERER'] : WB_URL );
			$redirect_url = (isset($thisApp->redirect_url) ? $thisApp->redirect_url : $redirect_url );
?>
		<form name="login" action="<?php echo LOGIN_URL; ?>" method="post">
			<input type="hidden" name="redirect" value="<?php echo $redirect_url;?>" />
			<table summary="" cellpadding="0" cellspacing="0" border="0" width="150" align="center" style="margin-top: 10px;">
			<tr>
				<td class="border">
					<img src="<?php echo TEMPLATE_DIR; ?>/images/menu_top.gif" border="0" alt="" />
				</td>
			</tr>
			<tr>
				<td class="login" style="text-transform: uppercase;">
					<b><?php echo $TEXT['LOGIN']; ?></b>
				</td>
			</tr>
			<tr>
				<td class="login" style="text-align: left;">
					<?php echo $TEXT['USERNAME']; ?>:
				</td>
			</tr>
			<tr>
				<td class="login">
					<input type="text" name="username" />
				</td>
			</tr>
			<tr>
				<td class="login" style="text-align: left;">
					<?php echo $TEXT['PASSWORD']; ?>:
				</td>
			</tr>
			<tr>
				<td class="login">
					<input type="password" name="password" />
				</td>
			</tr>
			<tr>
				<td class="login">
					<input type="submit" name="submit" value="<?php 
						echo $TEXT['LOGIN']; ?>" style="margin-top: 3px; text-transform: uppercase;" />
				</td>
			</tr>
			<tr>
				<td class="login">
					<a href="<?php echo FORGOT_URL; ?>"><?php echo $TEXT['FORGOT_DETAILS']; ?></a>
					<?php if (is_numeric(FRONTEND_SIGNUP)) { ?>
						<a href="<?php echo SIGNUP_URL; ?>"><?php echo $TEXT['SIGNUP']; ?></a>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td class="border">
					<img src="<?php echo TEMPLATE_DIR; ?>/images/menu_bottom.gif" border="0" alt="" />
				</td>
			</tr>
			</table>
		
		</form>
		<?php
		} elseif (FRONTEND_LOGIN AND $wb->is_authenticated()) {
		?>
		<form name="logout" action="<?php echo LOGOUT_URL; ?>" method="post">
			
			<table summary="" cellpadding="0" cellspacing="0" border="0" width="150" align="center" style="margin-top: 10px;">
			<tr>
				<td class="border">
					<img src="<?php echo TEMPLATE_DIR; ?>/images/menu_top.gif" border="0" alt="" />
				</td>
			</tr>
			<tr>
				<td class="login" style="text-transform: uppercase;">
					<b><?php echo $TEXT['LOGGED_IN']; ?></b>
				</td>
			</tr>
			<tr>
				<td class="login" style="padding-top: 15px; padding-bottom: 15px;">
					<?php echo $TEXT['WELCOME_BACK']; ?>, <?php echo $wb->get_display_name(); ?>
				</td>
			</tr>
			<tr>
				<td class="login">
					<input type="submit" name="submit" value="<?php 
						echo $MENU['LOGOUT']; ?>" style="margin-top: 3px; text-transform: uppercase;" />
				</td>
			</tr>
			<tr>
				<td class="login">
					<a href="<?php echo PREFERENCES_URL; ?>"><?php echo $MENU['PREFERENCES']; ?></a>
				</td>
			</tr>
			<tr>
				<td class="border">
					<img src="<?php echo TEMPLATE_DIR; ?>/images/menu_bottom.gif" border="0" alt="" />
				</td>
			</tr>
			</table>
		
		</form>
		<?php
		}
		?>
	</td>
	<?php } ?>
	<td class="content" width="600" rowspan="2">
		<?php page_content(); ?>
	</td>
</tr>
<tr>
	<td height="20" width="155" valign="bottom" class="powered_by">
		<a href="http://www.websitebaker.org/" target="_blank">
			<img src="<?php echo TEMPLATE_DIR; ?>/images/powered.jpg" border="0" alt="Powered By Website Baker" />
		</a>
	</td>
</tr>
<tr>
	<td colspan="2" class="border">
		<img src="<?php echo TEMPLATE_DIR; ?>/images/footer.png" border="0" alt="" />
	</td>
</tr>
<tr>
	<td colspan="2" class="footer">
		<?php page_footer(); ?>
	</td>
</tr>
</table>
<?php 
// automatically include optional WB module file frontend_body.js)
if (function_exists('register_frontend_modfiles_body')) { register_frontend_modfiles_body(); } 
?>
</body>
</html>