<?php
/*
  *
 * WBCE CMS
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 
  v0.50 (florian; 08.05.2017)
  !fix XSS vulnerability (reported by strukt93@gmail.com)
 
  v0.41 (marmot; 25.05.2013)
  ! admin url is now set by config.php
  ! notice regarding mktime
 
 v0.40 (marmot; 22.12.2012)
 	+ made user edit work with wb283 (might not work with wb < 283 anymore) by changing $_POST and introducing IDKEY
 	+ made jscalendar look better by adding backend backend.css file and using time option
 	+ updated DE language file.

 v0.32b (Quinto; 04.04.2009)
 	+ fixed important bug in group selection now it works in ALL the cases even when with combined groups
 	+ source code refining now it use template files (stored in the htt directory), source code is easier to read now.
 	+ added 'days since last connection' and 'Ip address' in results
 	~ we still need to Update DE and Nl language files.
 
 v0.31b (Quinto; 07.04.2009)
 	+ cosmetic changes to consistency with the Website Baker Admin UI
 	+ some source code refining and added some little thing to help in the future
 	  for example to allow to store the search.
 	+ added functionality to search users according the last login date
 	+ changed the way to work now you can mix all the search criteria (searchterm, date and group)
 	+ added FR language support (support the new functionalities)
 	+ EN language updated to support new functionalities
 	~ now we need to Update DE and Nl language files.
  	
 v0.30 (BerndJM; 24.03.2009)
 	+ added functionality to search als users that are members of a given group 
 
 v0.21 (BerndJM, Aldus, Bramus; 06.03.2009)
 	+ added NL language support (thanks to Bramus)
 	~ some source code refining (tanks to Aldus)
 	
 v0.20  (BerndJM; 02.03.2009)
 	+ added language support (DE and EN only)
   ~ some minor cosmetic changes to provide it as an admin-tool
    
 v0.10  (BerndJM; 28.02.2009)
   + initial release of the module (for internal use only)
 -----------------------------------------------------------------------------------------
*/

$module_directory = 'user_search';
$module_name = 'User search';
$module_function = 'tool';
$module_version = '0.50';
$module_platform = '2.8.3';
$module_author = 'Bernd Michna';
$module_license = 'GNU General Public License';
$module_description = 'This module provides a search function for the user management.';
$module_guid = 'EE87F4AD-F675-4E44-81EE-91B8D491977B';