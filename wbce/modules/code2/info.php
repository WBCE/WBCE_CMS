<?php

/**
 *
 *	@module       code2
 *	@version		  2.1.11
 *	@authors		  Ryan Djurovich, Chio Maisriml, Thorsten Hornik, Dietrich Roland Pehlke, Martin Hecht 
 *	@copyright		Ryan Djurovich, Chio Maisriml, Thorsten Hornik, Dietrich Roland Pehlke, Martin Hecht
 *  @license	    GNU General Public License
 *	@platform		  WebsiteBaker 2.8.x
 *	@requirements	PHP 5.2.x and higher
 *
 *      2.1.11  2014-05-04      - Add FTAN-Check inside save.php again and correct the header issue to make it run
 *						   again under "single-tab-mode" (modification by Martin Hecht), 
 *						   thanks to jacobi22 for hints about this issue
 *
 *	2.1.10	2011-08-31	- Remove FTAN-Check inside save.php to get the module run under "single-tab-mode"
 *						  within WB 2.8.2 and newer - as the FTAN-Check is passed by the core.
 *
 *	2.1.9	2011-01-17	- Build in FTAN code for secure-reasons inside modify.php and save.php.
 *						- Upgrade the modul-platform to 2.8.x and skip 2.7 support for the module.
 *
 *	2.1.7	2010-10-15	- Some bugfixes inside modify.php to display the correct value of the mode.
 *
 *	2.1.6	2010-05-03	- Minor typos-bugfix inside the html-template
 *
 *	2.1.5	2010-05-03	- Bugfix in the templatefile for the width of the textarea that causes
 *						  problems within e.g. argos-theme.
 *
 *	2.1.4	2010-04-12	- Add (md5) hash-id[-test] to the modify.php and save.php code.
 *
 *	2.1.3	2010-04-10	- Codechanges and optimazions inside modify.php.
 *
 *	2.1.3	2010-04-09	- Ad admin-permission test to save.php
 *						- Remove the HTTP-REFERER test from the save.php
 *
 *	2.1.2	2010-04-06	- Remove obsolete PHP-Code for correct loading the backend.js.
 *						- Remove directory "js" from the project.
 *
 *	2.1.1	2010-04-06	- Bugfix typos inside the module-description.
 *						- Bugfix inside upgrade.php, if there isn't a code2-section, the
 *						  script trys to add an existing field.
 *
 *	2.1.0 	2010-04-02	- Code-additions inside save.php for more security.
 *						- Minor cosmetic code-changes inside the info.php.
 *						- Removing wrong HTML-Tags and other replacements in the template.htt.
 *						- Removing backend.js to avoid javascript-conflicts ... the used javascript
 *						  is still loaded!
 *						- Massive recoding the upgrade.php script.
 *
 */

$module_directory	= 'code2';
$module_name		  = 'Code2';
$module_function	= 'page';
$module_version		= '2.1.11';
$module_platform	= '2.8.x';
$module_author		= 'Ryan Djurovich, minor changes by Chio Maisriml, websitbaker.at, Search-Enhancement by thorn, Mode-Select by Aldus, FTAN Support corrected by Martin Hecht';
$module_license		= 'GNU General Public License';
$module_description = 'This module allows you to execute PHP, HTML, Javascript commands and internal comments (<span style="color:#FF0000;">limit access to users you trust!</span>).';
$module_guid		  = 'http://www.websitebakers.com';
$module_guid		  = '968243F3-EC4A-439D-8936-7D727D9EF8C2';

?>
