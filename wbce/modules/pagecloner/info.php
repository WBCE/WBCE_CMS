<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

$module_directory = 'pagecloner';
$module_name = 'Page Cloner';
$module_function = 'tool';
$module_version = '1.0.3';
$module_platform = '2.8.x';
$module_author = 'John Maats, PCWacht, Dietrich Roland Pehlke, Stephan Kuehn, vBoedefeld, WebBird, Colinax';
$module_license = 'GNU General Public License';
$module_description = 'This addon allows you to clone a page or a complete tree with all page and sections.';
$module_home = 'http://wbce.org';
$module_guid = '25bfa866-2ee3-4731-8f44-f49f01c8294a';
$module_icon        = 'fa fa-clone';

/**
 * Version history
 *
 * v1.0.3 (WebBird, 10.08.2018)
 *        - Fix: Problem with more than one WBCE installation into same mySQL instance
 *        - fixed some DB statements (added quotes)
 *
 * v1.0.2 (Colinax, 24.05.2017)
 *        - Page Cloner uses now Theme icons and not longer Admin icons
 *        - Updated language files
 *        - Moved template.html to template.php
 *
 * v1.0.1 (05.11.2015)
 *        - fix page_id column detection (thx to florian: http://forum.wbce.org/viewtopic.php?pid=2137#p2137)
 *        - copy pics for minigallery (thx to florian: http://forum.wbce.org/viewtopic.php?pid=2137#p2137)
 *
 * v1.0.0 (03.11.2015)
 *        - try to copy modules in a generic way 
 *
 *  v0.60 (WebBird; 06.07.2015)
 *        - fixed problem with mysql_real_escape_string() (deprecated)
 *        - new: option to copy the page title of the cloned page
 *        - new: option to set the visibility of the cloned page
 *
 *  v0.59 (WebBird; 31.10.2013)
 *        - fixed error cloning MPForm pages https://github.com/webbird/pagecloner/issues/1
 *
 **/
