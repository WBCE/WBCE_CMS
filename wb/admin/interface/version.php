<?php
/*
 * 						About WebsiteBaker
 *
 * Website Baker is a PHP-based Content Management System (CMS)
 * designed with one goal in mind: to enable its users to produce websites
 * with ease.
 *
 * 						LICENSE INFORMATION
 *
 * WebsiteBaker is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * WebsiteBaker is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * 				WebsiteBaker Extra Information
 *
 * This file is where the WB release version is stored.
 *
 */
/**
 *
 * @category     	admin
 * @package      	interface
 * @author          WebsiteBaker Project
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @revision     	$Revision: 1638 $
 * @version      	$Id: version.php 1638 2012-03-13 23:01:47Z darkviper $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/admin/interface/version.php $
 * @lastmodified    $Date: 2012-03-14 00:01:47 +0100 (Mi, 14. Mrz 2012) $
 * 
 */

if(!defined('WB_URL')) {
	header('Location: ../index.php');
	exit(0);
}

// check if defined to avoid errors during installation (redirect to admin panel fails if PHP error/warnings are enabled)
if(!defined('VERSION')) define('VERSION', '2.8.3');
if(!defined('REVISION')) define('REVISION', '1640');
if(!defined('SP')) define('SP', 'SP3');



