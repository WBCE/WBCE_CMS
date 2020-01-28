<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */
 
$module_directory = 'sitemap';
$module_name      = 'Sitemap';
$module_function  = 'page';
$module_version   = '4.0.8';
$module_platform  = '1.4.0';
$module_author    = 'Ryan Djurovich, Frank Schoep, Woudloper, Ruebenwurzel, Rob Smith, Mouring Kolhoff, Michael Milette, Dietrich Roland Pehlke, Christian M. Stefan (Stefanek) (last)';
$module_license   = 'GNU General Public License';
$module_description = 'This module allows you to easily create a sitemap. You can even work with additional layout settings for the sitemap. It also gives you the option to show hidden menu items if required';




/*******************************************************************************\
[changelog]
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	DEVELOPMENT HISTORY (Change Log):

	v.4.0.7 Florian		
			fix upgrade.php (missing variable definitions), branding

	v.4.0.7 Colinax		
			upgrade.php fixes a bug that prevents the module from being updated manually
	
	v.4.0.6 Colinax		
			Update module_platform
    
	v.4.0.5 Christian M. Stefan (Stefanek)		
			clean up and use new DB functions
    
	v.4.0.4 2017-09-16 Christian M. Stefan (Stefanek)		
		[c] mostly CSS file addition for the backend
		[+]	added support for new CSS move to head functions
			see: https://forum.wbce.org/viewtopic.php?id=1598
	
	v.4.0.3 2017-01-30 Christian M. Stefan (Stefanek)		
		[c] upgrade.php replace $database->error() with $database->get_error();
			thanks to Florian for appropriate handling suggestions
		
	v.4.0.2 2017-01-29 Christian M. Stefan (Stefanek)		
		[c] remmove testing output in (view.php)

	v.4.0.1 2017-01-29 Christian M. Stefan (Stefanek)		
		[c] correct typos in installation file (install.php)
		[c] rewrite upgrade.php 
		
	v.4.0.0 2017-01-23 Christian M. Stefan (Stefanek)
		New DB fields allowing for new features 
		[+] select the menu or several menus the SiteMap should 
			include in the frontend output (only selectable if the DEFAULT_TEMPLATE
			has multiple menus enabled)
		[+] new placeholder [LEVEL] can be now used in level-header and sitemap-loop 
		[+] add button to hide/show the settings 
		[+] allow for tab indentation in textareas for more comfort
			in layout fields
		[+] add FTAN Support
		[+] added if(!function_exists("sitemap")) before the function;
			its non existence caused the page to break if sitemap module
			was used more than once on the same page
		[+] added preg_replace mechanism for advanced output filtering;
			this feature is disabled by default -- in order to use it: rename
			folder "_regex" to "regex" and include your own replace rules in 
			the file regex_section_all.php if rules shall run on all sections
			or in the file regex_section_[section_in].php if only for specific 
			sections
		[+] added initial README.md file
		[c] get rid of table layout in the settings 
		[c] code cleanups
		[c] upgrade script changes to add new columms on upgrade
		
	v.3.1.3 2011-08-09 Dietrich Roland Pehlke
		remove old WB 2.4 stuff, vars inside the view.php

	v3.1.2 (Michael Milette; August 9, 2010)
		Fixed: (with error reporting on 'E_ALL'), notice message
		regarding undefined index: show_hidden that appeared when
		saving settings.
		Updated this changelog using info from help.php.
		Remove change log from help.php to minimize techy info in
		user interface.

	v3.1.1 (Michael Milette; August 8, 2010) No new features.
		+ [update_sitemap.php] Now replaced by new upgrade.php which is automatically
		run when upgrading using Modules installation tool.
		+ [upgrade.php] Upgrades now include the creation of the show_hidden field.
		Upgrades now reports errors.
		+ [info.php]    Now reflects compatibility with Website Baker v2.8.x.
		Updated: All copyright notices upgraded to now include 2010.

	v3.1 (kozmoz; 10-16-2006)
		Added: Option to show hidden pages.
		Added: Dutch Translation

	v3.0   (rssmith, Ruebenwurzel; 9-12-2006)
		Fixed: [MODIFIED_BY] displays now the displayname instead of
		the userid.
		Fixed: install script now works with mysql5 strict mode

	v2.9   (Ruebenwurzel; 05-07-2006)
		Added: New variables: MODIFIED_DATE] and [MODIFIED_TIME]
		setup to allow for a more controlled layout
		Changed: The date/time is now more customizable.

	v2.8   (Ruebenwurzel; 03-04-2006)
		Renamed: databasefield 'loop' to 'sitemaploop' to be
		compatible to MySQL 5.x
		Added: update_sitemap.php script to update from previous
		versions
		Updated: All copyright notices includes now 2006
		Added: Default value to all varchar and text fields

	v2.7   (Ruebenwurzel; 12-28-2005)
		Added: New multilanguage support
		Changed: Minor layout changes in admin interface

	v2.6.2 (Ruebenwurzel; 11-25-2005)
		Added: Language file works with help file
		Changed: Minor layout changes in admin Interface
		Fixed: Code cleaning and all files stored UNIX conform

	v2.6.1 (Woudloper,Ruebenwurzel; 11-24-2005)
		Added: Uninstall functionality for the module.
		Fixed: Layout of the help.php file within Internet Explorer.

	v2.6.0 (Woudloper; 11-16-2005)
		Added: External language file</li>
		Fixed: Declaration of variables. On some servers (with error
		reporting on 'E_ALL') warnings where displayed about some
		variables.
		Fixed: Warning message (regarding variables) when saving the
		settings within the admin console.
		Changed: Layout of the admin section for the sitemap module.

[/changelog]
\*******************************************************************************/