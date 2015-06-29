<?php
/**
 * $Id: index.php 1106 2009-08-06 16:07:39Z Ruebenwurzel $
 * Website Baker template: simple
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
	<?php 
	// automatically include optional WB module files (frontend.css, frontend.js)
	if (function_exists('register_frontend_modfiles')) {
		register_frontend_modfiles('css');
		register_frontend_modfiles('js');
	} ?>
	<link rel="stylesheet" type="text/css" href="<?php 
		echo TEMPLATE_DIR; ?>/template.css" media="screen,projection" />
	<link rel="stylesheet" type="text/css" href="<?php 
		echo TEMPLATE_DIR; ?>/print.css" media="print" />
	<title><?php page_title('', '[WEBSITE_TITLE]'); ?></title>
</head>

<body>

<table cellpadding="5" cellspacing="0" border="0" width="750" align="center">
<tr>
	<td colspan="2" class="header">
		<?php page_title('','[WEBSITE_TITLE]'); ?>
	</td>
</tr>
<tr>
	<td colspan="2" class="footer">
		&nbsp;
	</td>
</tr>
<tr>
	<td class="menu">
		<?php 
		if (SHOW_MENU) { 
			// navigation menu
			echo $TEXT['MENU'] . ':';
			show_menu();
		} ?>
		
		<?php 
		if (SHOW_SEARCH) { /* Only show search box if search is enabled */ ?>
			<br />
			<?php echo $TEXT['SEARCH']; ?>: <br />
			<form name="search" action="<?php echo WB_URL; ?>/search/index.php" method="get">
				<input type="hidden" name="referrer" value="<?php
					echo defined('REFERRER_ID') ? REFERRER_ID : PAGE_ID; ?>" />
				<input type="text" name="string" style="width: 100%;" />
				<input type="submit" name="submit" value="<?php echo $TEXT['SEARCH']; ?>" style="width: 100%;" />
			</form>
		<?php } ?>
		
		<br />
		<a href="http://www.websitebaker.org" target="_blank">Powered by <br /> Website Baker</a>
	</td>
	<td class="content">
		<?php page_content(); ?>
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