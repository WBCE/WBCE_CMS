<?php
/**
 * Asset Optimizer — uninstall.php
 *
 * @package asset_optimizer
 * @author  Christian M. Stefan · www.wbEasy.de
 * @license http://www.gnu.org/licenses/gpl.html
 */

if (!defined('WB_PATH')) {
    die('Cannot access this file directly');
}

// Deliberately leaves every config constant and the settings-table rows in
// place — they belong to AssetQueue / the Outputfilter Dashboard, not to this
// tool, and removing them would silently change how assets are delivered.
