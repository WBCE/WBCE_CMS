<?php
//no direct file access
if(count(get_included_files()) ==1){$z="HTTP/1.0 404 Not Found";header($z);die($z);}

$module_directory     = 'colorbox';
$module_name          = 'colorbox_v1.6.4';
$module_function      = 'snippet';
$module_version       = '1.6.4';
$module_status	      = '';
$module_platform      = '1.x';
$module_author        = 'Complete rewrite by Norbert Heimsath, updated by instantflorian';
$module_license       = '<a href="http://www.gnu.org/licenses/gpl.html">GNU General Public Licencse 3.0</a>';
$module_description   = 'ColorBox v1.6 - a full featured, light-weight, customizable lightbox based on-<a href="http://www.jacklmoore.com/colorbox">colorbox</a> For details about the License see the License file.';
$module_home          = '';
$module_requirements  = 'WBCE 1.x+, jQuery';


