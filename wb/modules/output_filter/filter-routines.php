<?php
/**
 *
 * @category        modules
 * @package         output_filter
 * @author          Christian Sommer, WB-Project, Werner v.d. Decken
 * @copyright       WebsiteBaker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: filter-routines.php 1626 2012-02-29 22:45:20Z darkviper $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/modules/output_filter/filter-routines.php $
 * @lastmodified    $Date: 2012-02-29 23:45:20 +0100 (Mi, 29. Feb 2012) $
 *
 */
/* --- this is a wrapper for backward compatibility only --- */
    $sModuleDir = str_replace('\\', '/', dirname(__FILE__)).'/';
    if (file_exists($sModuleDir.'index.php')) {
        require($sModuleDir.'index.php');
    }
