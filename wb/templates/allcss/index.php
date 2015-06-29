<?php
/**
 * $Id: index.php 1606 2012-02-08 22:31:52Z Luisehahne $
 * Website Baker template: allcss
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
if (!defined('WB_PATH')) die(header('Location: ../../index.php'));

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

<div class="main">
	
	<div class="banner">
		<a href="<?php echo WB_URL; ?>/" target="_top"><?php page_title('', '[WEBSITE_TITLE]'); ?></a>
		<span>| <?php page_title('', '[PAGE_TITLE]'); ?></span>
	</div>
	
	<!-- frontend search -->
	<div class="search_box">
		<?php 
		// CODE FOR WEBSITE BAKER FRONTEND SEARCH
		if (SHOW_SEARCH) { ?>
			<form name="search" action="<?php echo WB_URL; ?>/search/index.php" method="get">
				<input type="hidden" name="referrer" value="<?php 
				echo defined('REFERRER_ID') ? REFERRER_ID : PAGE_ID; ?>" />
				<input type="text" name="string" class="search_string" />
				<input type="submit" name="wb_search" id="wb_search" value="<?php 
				echo $TEXT['SEARCH']; ?>" class="search_submit" />
			</form><?php 
		} ?>
	</div>

	<!-- main navigation menu -->
	<div class="menu">
		<?php
		show_menu2(0,SM2_ROOT,SM2_CURR+1,SM2_TRIM,'<li><span class="menu-default">[ac][menu_title]</a></span>','</li>','<ul>','</ul>');
		// CODE FOR WEBSITE BAKER FRONTEND LOGIN
		if (FRONTEND_LOGIN == 'enabled' && VISIBILITY != 'private' && $wb->get_session('USER_ID') == '') {
			$redirect_url = ((isset($_SESSION['HTTP_REFERER']) && $_SESSION['HTTP_REFERER'] != '') ? $_SESSION['HTTP_REFERER'] : WB_URL );
			$redirect_url = (isset($thisApp->redirect_url) ? $thisApp->redirect_url : $redirect_url );
		?>
			<!-- login form -->
			<br />
			<form name="login" id="login" action="<?php echo LOGIN_URL; ?>" method="post">
				<input type="hidden" name="redirect" value="<?php echo $redirect_url;?>" />
				<fieldset>
					<legend><?php echo $TEXT['LOGIN']; ?></legend>
					<label for="username" accesskey="1"><?php echo $TEXT['USERNAME']; ?>:</label>
					<input type="text" name="username" id="username" style="text-transform: lowercase;" /><br />
					<label for="password" accesskey="2"><?php echo $TEXT['PASSWORD']; ?>:</label>
					<input type="password" name="password" id="password" /><br />
					<input type="submit" name="wb_login" id="wb_login" value="<?php echo $MENU['LOGIN']; ?>"/><br />
	
					<!-- forgotten details link -->
					<a href="<?php echo FORGOT_URL; ?>"><?php echo $TEXT['FORGOT_DETAILS']; ?></a>

					<!-- frontend signup -->
					<?php
					if (is_numeric(FRONTEND_SIGNUP)) { ?>
						<a href="<?php echo SIGNUP_URL; ?>"><?php echo $TEXT['SIGNUP']; ?></a>
					<?php } ?>
				</fieldset>
			</form>
			
		<?php 
		} elseif (FRONTEND_LOGIN == 'enabled' && is_numeric($wb->get_session('USER_ID'))) { ?>
			<!-- logout form -->
			<br />
			<form name="logout" id="logout" action="<?php echo LOGOUT_URL; ?>" method="post">
				<fieldset>
					<legend><?php echo $TEXT['LOGGED_IN']; ?>:</legend>
					<?php echo $TEXT['WELCOME_BACK']; ?>, <?php echo $wb->get_display_name(); ?>
					<input type="submit" name="wb_logout" id="wb_logout" value="<?php echo $MENU['LOGOUT']; ?>" />
					<!-- edit user preferences -->
					<a href="<?php echo PREFERENCES_URL; ?>"><?php echo $MENU['PREFERENCES']; ?></a>
				</fieldset>
			</form>
		<?php 
		} ?>
	</div>
	
	<div class="content">
		<?php page_content(); ?>
	</div>
	
	<div class="footer">
		<?php page_footer(); ?>
	</div>
	
</div>

<div class="powered_by">
	Powered by <a href="http://www.websitebaker.org" target="_blank">Website Baker</a>
</div>
<?php 
// automatically include optional WB module file frontend_body.js)
if (function_exists('register_frontend_modfiles_body')) { register_frontend_modfiles_body(); } 
?>
</body>
</html>